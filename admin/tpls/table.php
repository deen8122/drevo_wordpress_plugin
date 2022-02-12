<? if(!isset($tplStatus)) die();
/**********
*  Настройка шаблона вывода раздела Таблица
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

$TPLNAME = "Таблица";
$TPLDESC = "вывод данных в виде таблицы";

switch($tplStatus){
case "name":
	print $TPLNAME;
break;
case "conf":
	
	$res = $database -> query("
		SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features 
		WHERE ID_RUBRIC=$id and rubric_type=".$type." and feature_type=2
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_text[$idf] = $txt;
	}
	$res = $database -> query("
		SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features 
		WHERE ID_RUBRIC=$id and rubric_type=".$type." and feature_type=7
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_btext[$idf] = $txt;
	}
	$res = $database -> query("
		SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features 
		WHERE ID_RUBRIC=$id and rubric_type=".$type." and feature_type=4
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_dir[$idf] = $txt;
	}
	
	$arrconf['rtpl_'.$id.'_pttl'] = array(40,"Выводить заголовок таблицы", "в заголовке будут вставлены имена характеристик в каждый столбец");
	//$arrconf['rtpl_'.$id.'_inpage'] = array(10,"Сколько записей данных выводить на странице", "0 - бесконечно");
	
	//$arrconf['rtpl_'.$id.'_pub'] = array(40,"Публиковать записи","Отметьте, если этот раздел, например - гостевая книга сайта");

	
	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
		$arrconf = array();
	}
break;
}

?>