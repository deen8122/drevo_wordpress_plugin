<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
global $step ;
$step = $_REQUEST['step'];
global $page_arr;
global $arrconf;
$_USER['group'] =1;
$page_arr[G_PAGE] = 'config';
if($_USER['group']<3){


	// массив полей конфига
	// как строится массив, станет понятно из модуля, который вызывается в самом конце этого файла
	$arrconf = array();

	// заполняем массив в зависимости от подраздела конфигурации
	// названия разделов сразу после этого swith'а
	switch( $step ){
		default:
			// все фотки при загрузке уменьшаются... это конфиг, как их уменьшать
			$arrconf['photo_mmaxw'] = array(10,getIdToPrint_config("configtable",'photo_mmaxw')." Максимальная <u>ширина</u> фото, пикс.","");
			$arrconf['photo_mmaxh'] = array(10,getIdToPrint_config("configtable",'photo_mmaxh')." Максимальная <u>высота</u> фото, пикс.","");
			$arrconf['photo_tmaxw'] = array(10,getIdToPrint_config("configtable",'photo_tmaxw')." Максимальная <u>ширина</u> уменьшенной фото, пикс.","");
			$arrconf['photo_tmaxh'] = array(10,getIdToPrint_config("configtable",'photo_tmaxh')." Максимальная <u>высота</u> уменьшенной фото, пикс.","");


			$res = $database -> query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types");
			while( list($id, $rtname) = mysql_fetch_array($res) ){
				$arr[$id] = $rtname;
			}
			//$arrconf['domodules'] = "Панель управления модулями";

			//$arrconf['module_ishop'] = array(50,getIdToPrint_config("configtable",'module_ishop')." Привязать модуль \"<b>Интернет-магазин</b>\" к разделу товаров","",$arr);
			/*if($module_ishop = teGetConf("module_ishop")){

				$res = $database -> query("SELECT ID_RUBRIC,rubric_name FROM ".DB_PREFIX."rubric WHERE rubric_type=".$module_ishop." ORDER BY rubric_name");
				$arr = array();
				while( list($id, $name) = mysql_fetch_array($res) ){
					$arr[$id] = $name;
				}

			}*/


			//$arrconf['module_dilers'] = array(40,getIdToPrint_config("configtable",'module_dilers')." Включить модуль \"<b>Управление дилерами</b>\"","");


		break;
		case 1:
			include "includes/config_rubrictypes.php";
		break;
		case 2:
			include "includes/config_site_common.php";
		break;
		case 3:
			include "includes/config_watermark.php";
		break;
		case 4:
			include "includes/config_site_tpls.php";
		break;
		case 5:
			include "includes/config_events.php";
		break;
	       case "web_forms":
			include "includes/config_web_forms.php";
		break;
		case 9:
			include "includes/config_events_type.php";
		break;
	}

	// массив значений разделов конфиги
	$steps = array(
		//0=>"Просмотр настроек \"навсегда\"",
		0=>"Настройки фото",
		1=>"Разделы рубрикатора",
		2=>"Контактная информация",
		3=>"Водяной знак",
		"web_forms"=>"Веб формы",
		//"Вывод на сайте",
		//5=>"Важные события"
	);

	// выводим подменю из массива
	//print_r($_SERVER['REQUEST_URI']);
	//exit;
	foreach($steps AS $istep => $stepname){
		if(!empty($stepname)){
			addSubMenu(teGetUrlQuery("show=conf","step=".$istep),$stepname,"submenu",'config-menu');
		}
	}

	// если массив конфига не пуст, то вызываем файл с программой обработки массива ( в нём всё самое интересное :) )
	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
	}
}
?>