<?php
define("TOKEN_STRING",       0);
define("TOKEN_AND",          1);
define("TOKEN_OR",           2);
define("TOKEN_NOT",          3);
define("TOKEN_LEFTPAREN",    4);
define("TOKEN_RIGHTPAREN",   5);
define("TOKEN_PLUS",         6);
define("TOKEN_MINUS",        7);
function tokenize($criteria) {

    $tokens = array(
        TOKEN_STRING => '',
        TOKEN_AND => 'and',
        TOKEN_OR => 'or',
        TOKEN_NOT => 'not',
        TOKEN_LEFTPAREN => '(',
        TOKEN_RIGHTPAREN => ')',
        TOKEN_PLUS => '+',
        TOKEN_MINUS => '-');

    $chart = array(
        0   => array(' '=>'',   '"'=>'d',  '\\'=>'ue', '+'=>'uaw ', '-'=>'uaw ', '('=>'uaw ', ')'=>'uaw ', 0 =>'ua'),
        1   => array(' '=>'w ', '"'=>'wd', '\\'=>'e',  '+'=>'waw',  '-'=>'waw',  '('=>'waw',  ')'=>'waw',  0 =>'a'),
        2   => array(' '=>'a',  '"'=>'w ', '\\'=>'e',  '+'=>'a',    '-'=>'a',    '('=>'a',    ')'=>'a',    0 =>'a'),
        3   => array(' '=>'ap', '"'=>'ap', '\\'=>'ap', '+'=>'ap',   '-'=>'ap',   '('=>'ap',   ')'=>'ap',   0 =>'ap'));

    $state = 0;					// STATE_SPACE
    $previous = '';     // stores current state when encountering a backslash (which changes $state to STATE_ESCAPED, but has to fall back into the previous $state afterwards)
    $out = array();     // the return value
    $word = '';
    $type = '';         // type of character

    for ($i=0; $i<=strlen($criteria); $i++) {
        $char = substr($criteria, $i, 1);
        $type = $char;
        if (!isset($chart[0][$type])) {
            $type = 0; //other
            preg_match("/[ \+\-\(\)\"\\\]/", $criteria, $matches, PREG_OFFSET_CAPTURE, $i);
            if ($matches) {
                $matches = $matches[0];
                $char = substr($criteria, $i, $matches[1]-$i); // yep, $char length can be > 1
                $i = $matches[1] - 1;
            }else{
                $word .= substr($criteria, $i);
                break; // jumps out of the for() loop
            }
        }
        $actions = $chart[$state][$type];
        for($j=0; $j<strlen($actions); $j++) {
            $act = substr($actions, $j, 1);
            if ($act == ' ') $state = 0; //STATE_SPACE
            if ($act == 'u') $state = 1; //STATE_UNQUOTED
            if ($act == 'd') $state = 2; //STATE_DOUBLEQUOTED
            if ($act == 'e') { $previous = $state; $state = 3; } //STATE_ESCAPED
            if ($act == 'a') $word .= $char;
            if ($act == 'p') $state = $previous;
            if ($act == 'w') {
                if (!empty($word)) {
                    $tokentype = TOKEN_STRING;
                    if ($state == 1) {
                        $tokentype = array_search(strtolower($word), $tokens);
                        if (!$tokentype) $tokentype = TOKEN_STRING;
                    }
                    $out[] = array($tokentype, $word);
                    $word = '';
                }
            }
        } //for j
    } //for i

    if (!empty($word)) {
        $tokentype = TOKEN_STRING;
        if ($state == 1) {
            $tokentype = array_search(strtolower($word), $tokens);
            if (!$tokentype) $tokentype = TOKEN_STRING;
        }
        $out[] = array($tokentype, $word);
        $word = '';
    }
    return $out;
} //tokenize
function parsecriteria($criteria) {

	$results = array();
	$tokens = array();

	$thisresult = array('','',TRUE,0);
	$nesting = 0;
	$tokens = tokenize(str_replace ('&quot;', '"', $criteria));

	foreach ($tokens as $token) {
	    switch ($token[0]) {
	        case TOKEN_AND:
	            $thisresult[0] = ' AND ';
	            $thisresult[2] = TRUE; //reset wildcard in case of bad syntax
	            break;

	        case TOKEN_OR:
	            $thisresult[0] = ' OR ';
	            $thisresult[2] = TRUE; //reset  wildcard in case of bad syntax
	            break;

	        case TOKEN_PLUS:
	            $thisresult[2] = FALSE;
	            break;

	        case TOKEN_NOT:
	        case TOKEN_MINUS:
	            $thisresult[0] .= ' NOT '; //NOT or AND NOT
	            break;

	        case TOKEN_LEFTPAREN:
	            $nesting += 1;
	            $thisresult[2] = TRUE; //reset just in case of bad syntax
	            break;

	        case TOKEN_RIGHTPAREN:
	            $nesting -= 1;
	            $thisresult[2] = TRUE; //reset just in case of bad syntax
	            break;

	        default:
	            $thisresult[1] .= $token[1];
	            $thisresult[3]  = $nesting;
	            $results[] = $thisresult;
	            $thisresult = array(' AND ','',TRUE,0);
	            break;
	    }
	} //foreach $tokens
	return $results;

} //parsecriteria


function assemble_query ($criteria, $searchfields) {
	global $mysql, $username, $search;
	$whereCriterias = parsecriteria ($criteria);


	$whereData = array();
	$columnNumber = 0;

	$whereClause = "";
	$nesting = 0;
	foreach ($whereCriterias as $mycriteria) {
	    $whereClause .= $mycriteria[0];

	    $thisnesting = $mycriteria[3];
	    if ($thisnesting >= $nesting) {
	        $whereClause .= str_repeat('(', $thisnesting - $nesting);
	    } else {
	        $whereClause .= str_repeat(')', $nesting - $thisnesting);
	    }
	    $nesting = $thisnesting;

	    $firstcolumn = TRUE;
	    $whereClause .= ' (';
	    foreach ($searchfields as $column) {
	        if ($firstcolumn) {
	            $firstcolumn = FALSE;
	        } else    {
	            $whereClause .= ' OR ';
	        }

	        if ($mycriteria[2]) {
	            $whereClause .= "$column LIKE " . '\'' .'%' . $mysql->escape ($mycriteria[1]) . '%' . '\'' ;
	        } else {
	            $whereClause .= "$column RLIKE " . '\'' . '[[:<:]]' . $mysql->escape ($mycriteria[1]) .  '[[:>:]]' . '\'';
	        }
	    } //foreach $column
	    $whereClause .= ')';
	} //foreach $whereCriterias

	$whereClause .= str_repeat(')', $nesting);
	$whereClause = trim ($whereClause);

	if ($whereClause != '') {
		$query = sprintf ("SELECT bookmark.title,
					bookmark.url,
					bookmark.description,
					UNIX_TIMESTAMP(bookmark.date) AS timestamp,
					bookmark.childof,
					bookmark.id,
					bookmark.favicon,
					bookmark.public,
					folder.name,
					folder.id AS fid,
					folder.public AS fpublic
					FROM bookmark LEFT JOIN folder ON bookmark.childof=folder.id
	
					WHERE bookmark.user='%s'
					AND bookmark.deleted!='1'
					AND ( %s )
					ORDER BY title",
					$mysql->escape ($username),
					$whereClause);
	
	}
	else {
		$query = false;
	}
	return $query;

}

?>