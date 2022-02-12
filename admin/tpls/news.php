<? if(!isset($tplStatus)) die();
/**********
*  Настройка шаблона вывода раздела Новости
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

$TPLNAME = "Новости";
$TPLDESC = "Подходит к разделам: новости, статьи.";

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
		$arr_alltext[$idf] = $txt;
	}
	$res = $database -> query("
		SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features
		WHERE ID_RUBRIC=$id and rubric_type=".$type." and feature_type=7
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_btext[$idf] = $txt;
		$arr_alltext[$idf] = $txt;
	}
	$res = $database -> query("
		SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features
		WHERE ID_RUBRIC=$id and rubric_type=".$type." and feature_type=8
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr_date[$idf] = $txt;
	}

	$arrconf['rtpl_'.$id.'_title'] = array(50,"Что является заголовком новости?","",$arr_text);
	$arrconf['rtpl_'.$id.'_anons'] = array(50,"Что является анонсом новости?","",$arr_alltext);
	$arrconf['rtpl_'.$id.'_text'] = array(50,"Что является текстом новости?","",$arr_btext);
	if(!empty($arr_date)) $arrconf['rtpl_'.$id.'_date'] = array(50,"Что является датой новости?","",$arr_date);
	if(count($arr_text)>1) $arrconf['rtpl_'.$id.'_pub'] = array(50,"Что является источником новости?","",$arr_text);
	$arrconf['rtpl_'.$id.'_pg'] = array(10,"Количество новостей на странице","по умолчанию: 20");

	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
		$arrconf = array();
	}
break;
}

?>