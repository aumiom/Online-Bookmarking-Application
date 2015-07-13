<?php
if (basename ($_SERVER['SCRIPT_NAME']) == basename (__FILE__)) {
	die ("no direct access allowed");
}

$dsn = array(
	'username' => 'bookmarkmgr',
	'password' => '',
	'hostspec' => 'localhost',
	'database' => 'bookmark',
);

$cookie = array (
	'name'   => 'ob_cookie',
	'domain' => '',
	'path'   => '/',
	'seed'   => 'YtU9lLxiXUQxbA',
	'expire' => time() + 31536000,
);

$date_formats = array (
	'd/m/Y',
	'Y-m-d',
	'm/d/Y',
	'd.m.Y',
	'F j, Y',
	'dS \o\f F Y',
	'dS F Y',
	'd F Y',
	'd. M Y',
	'Y F d',
	'F d, Y',
	'M. d, Y',
	'm/d/Y',
	'm-d-Y',
	'm.d.Y',
	'm.d.y',
);

$convert_favicons = false;
$convert = '';
$identify = '';
$timeout = 5;
$folder_closed = '<img src="./images/folder.gif" alt="">';
$folder_opened = '<img src="./images/folder_open.gif" alt="">';
$folder_closed_public = '<img src="./images/folder_red.gif" alt="">';
$folder_opened_public = '<img src="./images/folder_open_red.gif" alt="">';
$bookmark_image = '<img src="./images/bookmark_image.gif" alt="">';
$plus = '<img src="./images/plus.gif" alt=""> ';
$minus = '<img src="./images/minus.gif" alt=""> ';
$neutral = '<img src="./images/spacer.gif" width="13" height="1" alt=""> ';
$edit_image = '<img src="./images/edit.gif" title="%s" alt="">';
$move_image = '<img src="./images/move.gif" title="%s" alt="">';
$delete_image = '<img src="./images/delete.gif" title="%s" alt="">';
$delimiter = "/";
?>