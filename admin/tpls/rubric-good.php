<? if(!isset($tplStatus)) die();
/**********
*  Настройка шаблона вывода раздела Рубрика-запись
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

$TPLNAME = "Рубрика-запись";
$TPLDESC = "Дерево рубрикатора, в одной рубрике одна запись.";

switch($tplStatus){
case "name":
	print $TPLNAME;
break;
case "conf":
	
	
	$res = $database -> query("
		SELECT ID_RUBRIC_TYPE,rubrictype_name
		FROM ".DB_PREFIX."rubric_types
		WHERE rubrictype_deleted=0 and rubrictype_visible=1
	");
	while( list($idf, $txt) = mysql_fetch_array($res) ){
		$arr[$idf] = $txt;
	}
	
	$arrconf['rtpl_'.$id.'_rtype'] = array(50,"Рубрики какого раздела показывать?","",$arr);
	
	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
		$arrconf = array();
	}
break;
}

?>