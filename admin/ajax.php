<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798
//Функции для ajax
@$op1 = $_GET['op1'];
switch($op1)
{
	case 'list_goods':/*вывод записей в рубрике*/
	{
		$rubric_id=(int)$_GET['rid'];
		@$mult=(int)$_GET['mult'];
		if($rubric_id>0)
		{
			list($feat_id) = $database->getArrayOfQuery("
				SELECT ".DB_PREFIX."features.ID_FEATURE
				FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
				WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0
				ORDER BY rubricfeature_pos, ID_FEATURE
				LIMIT 1
			");
			if($feat_id>0)
			{
				$res_feat = $database->query("
					SELECT ".DB_PREFIX."features.ID_FEATURE, feature_text
					FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
					WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0 and feature_type IN (1,2,8)
					ORDER BY rubricfeature_pos, ID_FEATURE
					LIMIT 1,2
				");
				$feats = array();
				while ($row_feat = mysql_fetch_array($res_feat)) {
					$feats[$row_feat[0]] = $row_feat[1];
				}
				$res = $database->query("
					SELECT ID_GOOD, goodfeature_value,good_visible
					FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods natural join cprice_goods_features
					WHERE ID_RUBRIC=".$rubric_id." && good_deleted=0 && ID_FEATURE=".$feat_id.
					" ORDER BY goodfeature_value, rubricgood_pos, ID_GOOD"
				);

				while( list($good_id,$gname,$visible) = mysql_fetch_array($res) ){
					//$gname = getFeatureValue($good_id, $feat_id);
					if(empty($gname))$gname = getFeatureText($good_id,$feat_id,true);
					$add_gname = ''; $br = '';
					foreach ($feats as $fid=>$fname)
					{
						$add_gname .= $br.$fname.': '.getFeatureValue($good_id, $fid);
						$br = ', ';
					}
					if($mult>0)print '<input type="checkbox" name="good" value="'.$good_id.'" id="good'.$good_id.'" />';
					else print '<input type="radio" name="good" value="'.$good_id.'" id="good'.$good_id.'" />';
					print ' <label for="good'.$good_id.'"><b>'.$gname.'</b>'.(!empty($add_gname)?' ('.$add_gname.')':'').($visible?'':' / откл.запись').'</label></br>';
				}
			}
		}
	}
	break;
}
die();
?>