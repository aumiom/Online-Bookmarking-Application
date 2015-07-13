<?php
if (basename ($_SERVER['SCRIPT_NAME']) == basename (__FILE__)) {
	die ("no direct access allowed");
}

class folder {

	function folder ($user = false) {
		global $settings, $username, $folderid, $expand;

		$this->username = $username;
		$this->folderid = $folderid;
		$this->expand = $expand;
		$this->folders = array ();
		$this->tree = array ();
		$this->get_children = array ();
		$this->level = 0;
		$this->foreign_username = false;

		if ($user) {
			$this->get_shared_data ($user);
		}
		else {
			$this->get_user_data ();
		}

		if ($settings['simple_tree_mode']) {
			$this->expand = $this->get_path_to_root ($this->folderid);
		}

		if ( ! array_key_exists ($this->folderid, $this->folders)) {
			$this->folderid = 0;
		}

		foreach ($this->expand as $key => $value) {
			if (! array_key_exists ($value, $this->folders)) {
				unset ($this->expand[$key]);
			}
		}
	}

	function get_user_data () {
		global $mysql;
		$query = sprintf ("SELECT id, childof, name, public FROM folder WHERE user='%s' AND deleted!='1' ORDER BY name",
			$mysql->escape ($_SESSION['username']));
		if ($mysql->query ($query)) {
			while ($row = mysql_fetch_assoc ($mysql->result)) {
				$this->folders[$row['id']] = $row;
				if (!isset ($this->children[$row['childof']])) {
					$this->children[$row['childof']] = array ();
				}
				array_push ($this->children[$row['childof']], $row['id']);
			}
		}
		else {
			message ($mysql->error);
		}
	}

	function get_shared_data ($user) {
		global $mysql, $username;

		# does the user exist in the database?
		if (check_username ($user)) {
				$this->foreign_username = $user;
		}
		else {
				$this->foreign_username = $username;
		}

		$query = "SELECT id, childof, name, public FROM folder WHERE public='1' AND deleted!='1' AND user='$this->foreign_username' ORDER BY name";
		if ($mysql->query ($query)) {
			$shared_children = array ();
			while ($row = mysql_fetch_assoc ($mysql->result)) {
				$this->folders[$row['id']] = $row;
				if (!isset ($this->children[$row['childof']])) {
					$this->children[$row['childof']] = array ();
				}
				array_push ($this->children[$row['childof']], $row['id']);
				array_push ($shared_children, $row['id']);
			}

			$this->children[0] = array ();
			foreach ($this->folders as $value) {
				if (in_array ($value['childof'], $shared_children)) {
					continue;
				}
				else {
					array_push ($this->children[0], $value['id']);
					$this->folders[$value['id']]['childof'] = 0;
				}
			}
		}
		else {
			message ($mysql->error);
		}
	}


	function make_tree ($id) {
		if (isset ($this->children)){
			$this->level++;
			if (isset ($this->children[$id])) {
				foreach ($this->children[$id] as $value) {
					array_push ($this->tree, array (
						'level'		=> $this->level,
						'id'		=> $value,
						'name'		=> $this->folders[$value]['name'],
						'public'	=> $this->folders[$value]['public'],
					));
					$symbol = &$this->tree[count ($this->tree) - 1]['symbol'];
					if (isset ($this->children[$value])) {
						if (in_array ($value, $this->expand)) {
							$symbol = 'minus';
							$this->make_tree ($value);
						}
						else {
							$symbol = 'plus';
						}
					}
					else {
						$symbol = '';
					}
				}
			}
			$this->level--;
		}
	}
 
	function print_tree ($scriptname="") {
		global $settings, $folder_opened, $folder_closed, $folder_opened_public, $folder_closed_public, $plus, $minus, $neutral;

		if ($scriptname=="")
			$scriptname = $_SERVER['SCRIPT_NAME'];
		
		if ($this->foreign_username) {
			$root_folder_name = $this->foreign_username . "'s Bookmarks";
			$user_var = "&amp;user=$this->foreign_username";
		}
		else {
			$root_folder_name = $settings['root_folder_name'];
			$user_var = "";
		}

		$root_folder = array (
			'level' => 0,
			'id' => 0,
			'name' => $root_folder_name,
			'symbol' => null,
			'public' => 0,
		);
		array_unshift ($this->tree, $root_folder);

		echo '<div class="foldertop"></div>' . "\n";

		foreach ($this->tree as $key => $value) {
			$spacer = '<div style="margin-left:' . $value['level'] * 20 . 'px;">';
			echo $spacer;

			if ($value['id'] == $this->folderid) {
				$folder_name = '<span class="active">' . $value['name'] . '</span>';
				if (!$this->foreign_username && $value['public']) {
					$folder_image = $folder_opened_public;
				}
				else {
					$folder_image = $folder_opened;
				}
			}
			else {
				$folder_name = $value['name'];
				if (!$this->foreign_username && $value['public']) {
					$folder_image = $folder_closed_public;
				}
				else {
					$folder_image = $folder_closed;
				}
			}

			if ($key > 5) {
				$ankor = "#" . $this->tree[$key - 5]['id'];
			}
			else {
				$ankor = "";
			}

			if ($value['symbol'] == "plus" || $value['symbol'] == "minus") {
				if ($value['symbol'] == "plus") {
					$symbol = $plus;
					$expand_s = $this->add_to_expand_list ($value['id']);
					if ($settings['fast_folder_plus']) {
						$expand_f = $expand_s;
					}
					else {
						$expand_f = $this->expand;
					}
				}
				else if ($value['symbol'] == "minus") {
					$symbol = $minus;
					$expand_s = $this->remove_from_expand_list ($value['id']);
					if ($settings['fast_folder_minus'] && $value['id'] == $this->folderid) {
						$expand_f = $expand_s;
					}
					else {
						$expand_f = $this->expand;
					}
				}
				if ($settings['fast_symbol']) {
					$folderid = $value['id'];
				}
				else {
					$folderid = $this->folderid;
				}
				# this prints the symbol (plus or minus) with its appropriate link
				echo '<a folderid="'.$folderid.'" class="f flink" href="' . $scriptname . '?expand=' . implode(",", $expand_s);
				echo '&amp;folderid=' . $folderid  . $user_var . $ankor . '">' . $symbol . '</a>';
			}
			else {
				$symbol = $neutral;
				$expand_f = $this->expand;
				echo $symbol;
			}
			echo '<a folderid="'.$value['id'].'" class="f flink" href="' . $scriptname . '?expand=' . implode(",", $expand_f);
			echo '&amp;folderid=' . $value['id'] . $user_var . $ankor . '" name="' . $value['id'] . '">' . $folder_image . " " . $folder_name . '</a>';
			echo "</div>\n";
		}
	}
	function remove_from_expand_list ($id){
		$expand = $this->expand;
		foreach ($expand as $key => $value) {
			if ($value == $id) {
				unset ($expand[$key]);
			}
		}
		return $expand;
	}

	function add_to_expand_list ($id){
		$expand = $this->expand;
		array_push ($expand, $id);
		return $expand;
	}

	function get_path_to_root ($id) {
		$path = array ();
		while ($id > 0) {
			array_push ($path, $id);
			if (!isset ($this->folders[$id])) {
				#echo "Folder Nr. $id does not have a parent";
				return array ();
			}
			else {
				$id = $this->folders[$id]['childof'];
			}
		}
		return $path;
	}

	function print_path ($id) {
		global $settings, $delimiter;
		$parents = $this->get_path_to_root ($id);
		$parents = array_reverse ($parents);
		$path = $delimiter . $settings['root_folder_name'];
		foreach ($parents as $value) {
			$path .= $delimiter . $this->folders[$value]['name'];
		}
		return $path;
	}

	function get_children ($id) {
		if (isset ($this->children[$id])) {
			foreach ($this->children[$id] as $value) {
				array_push ($this->get_children, $value);
				$this->get_children ($value);
			}
		}
	}
}

?>
