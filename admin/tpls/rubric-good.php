<? if(!isset($tplStatus)) die();
/**********
*  ��������� ������� ������ ������� �������-������
*
*  ��� "���������-������"
*
*  �����������:  Te�e��o� �.�.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  ���.: +7 909 3481503
**********/

$TPLNAME = "�������-������";
$TPLDESC = "������ �����������, � ����� ������� ���� ������.";

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
	
	$arrconf['rtpl_'.$id.'_rtype'] = array(50,"������� ������ ������� ����������?","",$arr);
	
	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
		$arrconf = array();
	}
break;
}

?>