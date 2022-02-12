<?php

if (!isset($_status)) {
	header("HTTP/1.0 404 Not Found");
	die;
}
define("AJAX", isset($_SERVER['HTTP_X_REQUESTED_WITH']));
ob_start();
$_status = 1;
global $_CONF;
global $str;
if (!isset($_GET['pg']))
	$_GET['pg'] = 'rubric';
if (!isset($_GET['type'])){
	$_GET['type'] = '1';
}
$_GET['curbase'] = '143';
$_CONF = @parse_ini_file("./conf.ini", false);
$teConf[] = parse_ini_file(ROOT_FLD . "engine/conf.ini", true);
global $skin;
$skin = $_CONF['skin'];
$skinpath = DEEN_FOLDERS_DIR . "admin/engine/view.html";
include ROOT_FLD . "engine/includes.php";
foreach ($file as $include_path) {
	$CURFLD = ROOT_FLD . "engine/server/" . $include_path . "/";
	$lang = "ru";
	include "{$CURFLD}index.php";
}

function teSetStatus($status) {
	
}

function teInclude($name) {
	$CURFLD = ROOT_FLD . "engine/server/" . $name . "/";
	$lang = "ru";
	if (file_exists("{$CURFLD}langs/{$lang}.ini")) {
		$str['cur'] = parse_ini_file("{$CURFLD}langs/{$lang}.ini", true);
	}
	include "{$CURFLD}index.php";
}

$CONFIG['max_upl_size'] = '20480';
$max_file_size = $CONFIG['max_upl_size'] << 10;
addGet("page", 'dn-rubrics');
if (!empty($_GET['op'])) {
	teInclude("operations");
} else {
	if (!empty($_GET[G_PAGE])) {
		if ($_GET[G_PAGE] == "index") {
			$page_arr[G_PAGE] = "default";
		} else {
			$page_arr[G_PAGE] = $_GET[G_PAGE];
		}
	} else
		$page_arr[G_PAGE] = "default";
	require ROOT_FLD . '' . $page_arr[G_PAGE] . ( (substr($page_arr[G_PAGE], -4) == ".php") ? "" : ".php" );
}
$_template->addToVar("body", ob_get_contents());
ob_end_clean();
if (empty($_template->variables['body'])) {
	$_template->addToVar("body", "404. ��������, ������ ����� ���!");
}   

$_template->addToVar("time", (getmicrotime() - MTIME_BEGIN)); 
$_template->addToVar("tnow", date("H:i:s"));  
$_template->addToVar("queries", $database->countQueries);  
$_template->sendText($skinpath);
?>