<? if(!isset($tplStatus)) die();
/**********
*  ��������� ������� ������ ������� ��������
*
*  ��� "���������-������"
*
*  �����������:  Te�e��o� �.�.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  ���.: +7 909 3481503
**********/

$TPLNAME = "��������";
$TPLDESC = "";

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
	
	$arrconf['rtpl_'.$id.'_ptitle'] = array(10,"����� ����� ����������","����. <b>���������</b>");

	$arrconf['rtpl_'.$id.'_title'] = array(50,"���������","",$arr_text);
	$arrconf['rtpl_'.$id.'_grrab'] = array(50,"������ ������","",$arr_dir);
	$arrconf['rtpl_'.$id.'_zp'] = array(50,"����������� ��","",$arr_text);
	$arrconf['rtpl_'.$id.'_obyaz'] = array(50,"�������������� �����������","",$arr_btext);
	$arrconf['rtpl_'.$id.'_req'] = array(50,"���������� � ����������","",$arr_btext);

	
	if(count($arrconf)>0){
		teInclude("interface/conf/");
		showConfig();
		$arrconf = array();
	}
break;
}

?>