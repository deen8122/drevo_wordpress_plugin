<? if(!isset($tplStatus)) die();
/**********
*  Настройка шаблона вывода раздела Форма
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

$TPLNAME = "Форма";
$TPLDESC = "подойдет для формы отправки письма, формы заявки, гостевой книги...";

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
	
	$arrconf['rtpl_'.$id.'_sent'] = array(20,"Сообщение об успешной отправке формы","напр. Ваше сообщение отправлено, спасибо!");
	$arrconf['rtpl_'.$id.'_cap'] = array(40,"Каптча","Защита от роботов");
	$arrconf['rtpl_'.$id.'_snd'] = array(40,"Уведомлять по e-mail о введенных данных","");
	
	$arrconf['rtpl_'.$id.'_pub'] = array(40,"Публиковать записи","Отметьте, если этот раздел, например - гостевая книга сайта");

	
	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
		$arrconf = array();
	}
break;
}

?>