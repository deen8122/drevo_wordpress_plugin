<?php
/*
  Plugin Name: Drevo
  Plugin URI: http://2dweb.ru
  Description: Организация управления данными через рубрики и записи, с расширяемыми характеристиками.
  Author: Эобард Тоун
  Author URI: http://2dweb.ru
  Version: 1.5
 */
//VERSION SPRUT
define('DF_VERSION', '1.5');
define('DEEN_FOLDERS_DIR', plugin_dir_path(__FILE__));
define('DEEN_FOLDERS_URL', plugin_dir_url(__FILE__));
define('DB_PREFIX', 'cprice_');
include 'includes/functions.php';
register_activation_hook(__FILE__, 'activate');
register_deactivation_hook(__FILE__, 'deactivate');
register_uninstall_hook(__FILE__, 'uninstall');
// Если мы в адм. интерфейсе

if (is_admin()) {
	add_action('wp_print_scripts', 'deen_admin_load_scripts');
	add_action('admin_menu', 'df_options_panel');
	add_action('wp_ajax_wp_ajax', 'ajax_action');
} else {
	include_once 'frontend/frontend.php';
}

function deen_admin_load_scripts() {
	//
	if($_GET['page']=='dn-rubrics' || $_GET['page']=='dn-rubrics-config'){
	wp_register_style('deen_folders_main2', DEEN_FOLDERS_URL . 'assets/css/bootstrap.min.css');
	wp_enqueue_style('deen_folders_main2');
	wp_register_style('deen_folders_main', DEEN_FOLDERS_URL . 'assets/css/admin-style.css');
	wp_enqueue_style('deen_folders_main');
	//wp_enqueue_script('jquery');
	wp_register_script('drevo_functions', DEEN_FOLDERS_URL . 'assets/js/drevo_functions.js');
	wp_enqueue_script('drevo_functions');
        wp_register_script('tinymce',DEEN_FOLDERS_URL.'assets/js/tiny_mce/tiny_mce.js');
	wp_enqueue_script('tinymce');
	wp_register_script('f', DEEN_FOLDERS_URL . 'assets/js/form/form.js');
	}
	//}
}

function ajax_action() {
	drevo_start();
}

// Plugin menu.
function df_options_panel() {
	add_menu_page('Рубрики', 'Рубрики', 'manage_options', 'dn-rubrics', 'show_rubric_list', DEEN_FOLDERS_URL . 'assets/images/drevologo16.png');
	add_submenu_page('dn-rubrics', 'Настройки', 'Настройки', 'manage_options', 'dn-rubrics-config', 'drevo_admin_config');
	//add_submenu_page('dn-rubrics', 'Помощь', 'Помощь', 'manage_options', 'dn-rubrics-help', 'drevo_admin_help');
	add_submenu_page('dn-rubrics', 'Управление содержимом', 'О плагине', 'manage_options', 'ds-plugin_info', 'ds_admin_plugin_info');

	// add_menu_page('Добро пожаловать в модуль управления отзывами', 'Атрибуты', 'manage_options', 'edit-reviews', array('', 'admin_edit_reviews'));
	// add_submenu_page( 'edit-reviews', 'Управление содержимом', 'О плагине', 'manage_options', 'plugin_info', array('','admin_plugin_info')); 
}

function drevo_admin_help() {
	header('location:?page=dn-rubrics&pg=help');
	exit;
}

function drevo_admin_config() {
	//?page=dn-rubrics&pg=config&step=1
	//header('location:?page=dn-rubrics&pg=config&step=1');
	$_GET['step']=1;	
	$_GET['pg']='config';
	show_rubric_list() ;
	//exit;
	//show_rubric_list();
}

function ds_admin_plugin_info() {
	echo '<div class="wrap deen-folders-wrap"> ';
	include 'info.php';
	echo '</div>';
}

function show_rubric_list() {
	echo '<div class="wrap deen-folders-wrap">';
	include 'admin/index.php';
	echo '</div>';
}

function drevo_start() {
	include 'admin/index.php';
}
?>