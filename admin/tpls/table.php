<? if(!isset($tplStatus)) die();
/**********
*  ��������� ������� ������ ������� �������
*
*  ��� "���������-������"
*
*  �����������:  Te�e��o� �.�.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  ���.: +7 909 3481503
**********/

$TPLNAME = "�������";
$TPLDESC = "����� ������ � ���� �������";

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
	
	$arrconf['rtpl_'.$id.'_pttl'] = array(40,"�������� ��������� �������", "� ��������� ����� ��������� ����� ������������� � ������ �������");
	//$arrconf['rtpl_'.$id.'_inpage'] = array(10,"������� ������� ������ �������� �� ��������", "0 - ����������");
	
	//$arrconf['rtpl_'.$id.'_pub'] = array(40,"����������� ������","��������, ���� ���� ������, �������� - �������� ����� �����");

	
	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
		$arrconf = array();
	}
break;
}

?>