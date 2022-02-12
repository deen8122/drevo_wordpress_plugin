<?php

/**
 * Активация плагина
 */
function activate() {
	global $wpdb;
	require_once('struct_db.php');
	foreach ($sql as $sql_str) {
		$wpdb->query($sql_str);
	}
	$upload_dir = wp_upload_dir(); 
	$rubric_dir  = $upload_dir['basedir'] . '/rubric_img';
	if(!file_exists($rubric_dir)) wp_mkdir_p($rubric_dir);
	$rubric_dir  = $upload_dir['basedir'] . '/good_photo';
	if(!file_exists($rubric_dir)) wp_mkdir_p($rubric_dir);
	$rubric_dir  = $upload_dir['basedir'] . '/features';
	if(!file_exists($rubric_dir)) wp_mkdir_p($rubric_dir);
}

/**
 * Деактивация плагина
 */
function deactivate() {
	/*
	 * 
	 */
	return true;
}

/**
 * Удаление плагина
 */
function uninstall() {
	global $wpdb;
	/*
	 * Не удаляем БД,
	 * по возможности делаем дамп.
	 */
	//$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}adv_reviews");
}

