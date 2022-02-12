<? if(!isset($tplStatus)) die();
/**********
*  Настройка шаблона вывода раздела Прайс-лист
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

$TPLNAME = "Каталог продукции";
$TPLDESC = "";

switch($tplStatus){
case "name":
	print $TPLNAME;
break;
case "conf":
	
	if(teGetConf("site_goodstype")=="") teRedirect(teGetUrlQuery("step=2"));
	
	$res = $database -> query("
		SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features 
		WHERE ID_RUBRIC=0 and rubric_type=".teGetConf("site_goodstype")." and feature_type=1
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_int[$idf] = $txt;
	}
	$res = $database -> query("
		SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features 
		WHERE ID_RUBRIC=0 and rubric_type=".teGetConf("site_goodstype")." and feature_type=7
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_btxt[$idf] = $txt;
	}
	$res = $database -> query("
		SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features 
		WHERE ID_RUBRIC=0 and rubric_type=".teGetConf("site_goodstype")." and feature_type=4
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_dir[$idf] = $txt;
	}
	$res = $database -> query("
		SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features 
		WHERE ID_RUBRIC=0 and rubric_type=".teGetConf("site_goodstype")." and feature_type=2
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_text[$idf] = $txt;
	}
	
	$arrconf['rtpl_'.$id.'_name'] = array(50,"Что считать наименованием товара?","",$arr_text);
	if(@count($arr_dir)>0) $arrconf['rtpl_'.$id.'_cur'] = array(50,"Что считать наименованием товара?","",$arr_dir);
	if(@count($arr_btxt)>0) $arrconf['rtpl_'.$id.'_desc'] = array(50,"Что считать описанием товара?","",$arr_btxt);
	$arrconf['rtpl_'.$id.'_price'] = array(50,"Что является ценой для показа на сайте?","",$arr_int);

	
	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
		$arrconf = array();
	}
break;
}

?>