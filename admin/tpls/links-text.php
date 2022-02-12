<? if(!isset($tplStatus)) die();
/**********
*  Настройка шаблона вывода раздела Ссылки-текст
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

$TPLNAME = "Ссылки-текст";
$TPLDESC = "Список ссылок, при клике - страница с текстом";

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
		WHERE ID_RUBRIC=$id and rubric_type=".$type." and feature_type=8
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_date[$idf] = $txt;
	}
	
	$arrconf['rtpl_'.$id.'_title'] = array(50,"Что является заголовком?","",$arr_text);
	$arrconf['rtpl_'.$id.'_text'] = array(50,"Что является текстом?","",$arr_btext);
	
	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
		$arrconf = array();
	}
break;
}

?>