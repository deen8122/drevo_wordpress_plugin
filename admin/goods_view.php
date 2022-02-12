<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*
 * GOODS VIEW
 */
if($type>0)
{
	list($rname) = $database->getArrayOfQuery("SELECT rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE ID_RUBRIC_TYPE=".$type);
	addSubMenu(teGetUrlQuery("pg=rubric","type=".$type,"nosession"),"Раздел: ".$rname);
}
 addSubMenu(teGetUrlQuery(),"Рубрика: ".getRubricName($rubric_id,true,false));
 $GLOBALS['id'] = $id;
 addGet('pg', 'goods');
 addSubMenuEdit();
 addSubMenuDelete();

// заголовок
setTitle("Просмотр ".$rtype['rubrictype_r_s']." ID:".getIdToPrint("goods",$id)/*." «".smallText(getFeatureText($id,0,true),30)."»"*/);
print "<div align=center>";

// вывод сверху уменьшенных фоток записи
$res = $database->query("SELECT * FROM ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0 and ID_GOOD=".$id." ORDER BY goodphoto_pos LIMIT 5");
while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
	print "<img src='".URLDATA_FLD."good_photo/trumb_$line[goodphoto_file]'>";
}

print "<div class='ls'><table  class='table-bordered table-good-view' align='center'>";

// ID записи
print "<tr>
	   <td class='ls_name'>ID:</td>
	   <td class='ls_val'>".getIdToPrint("goods",$id)."</td>
        </tr>";

$res = $database -> query(
" SELECT 
      ".DB_PREFIX."features.* 
  FROM 
      ".DB_PREFIX."features 
  NATURAL JOIN 
      ".DB_PREFIX."rubric_features 
  WHERE 
       feature_deleted=0 
	   and feature_enable=1 
	   and rubric_type=$type 
	   and ID_RUBRIC=".$rubric_id." 
  ORDER BY rubricfeature_pos");
while( $line = mysql_fetch_array($res,MYSQL_ASSOC) ){

	$answerres = $database->query("SELECT goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE=".$line['ID_FEATURE']." and ID_GOOD=".$id);
	$answertext = array();
	while($answeline = mysql_fetch_array($answerres)){
		$answertext[] = $answeline[0];
	}

	// В зависимости от типа данных характеристики, выводим
	switch($line['feature_type']){
		case 1:
		case 2:
		case 8:
			if(!empty($answertext)){
				print "<tr><td class='ls_name'>".$line['feature_text'].":</td><td class='ls_val'>";
				foreach($answertext AS $at) print $at."<br>";
				print "</td></tr>";
			}
		break;
		case 7:
			if(is_numeric(@$answertext[0])){
				$answertext = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT=".$answertext[0]);
				$answertext = $answertext[0];
				print "<tr><td class='ls_name'>".$line['feature_text'].":</td><td class='ls_val'>".nl2br($answertext)."</td></tr>";
			}
		break;
		case 3:
			print "<tr><td class='ls_name'>".$line['feature_text'].":</td><td class='ls_val'>".((@$answertext[0]==1)?"да":"нет")."</td></tr>";
		break;
		case 4:
			if(!empty($answertext) && $answertext!="-"){
				print "<tr><td class='ls_name'>".$line['feature_text'].":</td><td class='ls_val'>";
				foreach($answertext AS $at){
					$line1 = $database -> getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".(int)$at);
					print $line1[0]."<br>";
				}
				print "</td></tr>";
			} else {
				// print "<tr><td class=name>".$line['feature_text'].":</td><td class=value> - </td></tr>";
			}
		break;
		case 10:
		case 5:
			print "<tr><td class='ls_name'>".$line['feature_text'].":</td><td class='ls_val'>";
			foreach($answertext AS $at) print getFeatureText($at,$line['ID_FEATURE'],true)."<br>";
			//print getFeatureText($id,$line['ID_FEATURE']);
			print "</td></tr>";
		break;
		case 6:
			print "<tr><td class='ls_name'>".$line['feature_text'].":</td><td class='ls_val'>";
			foreach($answertext AS $at){
				print "<a href='".URLDATA_FLD."features/".$at."'>".$at."</a><br>";
			}
			print "</td></tr>";
		break;
	}
}
print "</table></div>";
print "</div>";
?>