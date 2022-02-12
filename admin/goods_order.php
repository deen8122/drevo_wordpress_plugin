<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  Настройка отчета товаров группы
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

$rubric_id = (int)@$_GET['rubric_id'];

// берем градации
$resgrad = $database->query("
	SELECT rubricfeature_graduation, ID_FEATURE
	FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features
	WHERE feature_graduation=1 and ID_RUBRIC = ".$rubric_id."
	ORDER BY rubricfeature_graduation, rubricfeature_pos
");
$arrgrads = array();
$arrorderby = array();
$ii=0;
while(list($numgrad,$idgrad) = mysql_fetch_array($resgrad)){
	if($numgrad==0){
		$numgrad = 1000+$ii;
		$ii++;
	}
	$arrgrads[$numgrad] = $idgrad;
	if($numgrad>=1000) $arrorderby[] = $idgrad;
}
ksort($arrgrads);

$graduation = current(($arrgrads));

if(!$graduation){
	$chet = false;
	$resf = $database -> query("SELECT ".DB_PREFIX."features.* FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and feature_enable=1 and rubric_type=$type and ID_RUBRIC=".$rubric_id." and ID_FEATURE<>'$graduation' and rubricfeature_ls_man=1 ORDER BY rubricfeature_pos,feature_text");
	
	$resnews = $database->query("
		SELECT ".DB_PREFIX."goods.ID_GOOD
		FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods
		WHERE ID_RUBRIC=".$rubric_id." and good_deleted=0 
		ORDER BY ".DB_PREFIX."goods.ID_GOOD
		LIMIT 1000
	");
	if(mysql_num_rows($resnews)>0){
		print "<table class='list' border=1 cellspacing=0>";
		print "<tr>";
		print "<th>ID</th>";
		mysql_data_seek($resf,0);
		while($line = mysql_fetch_array($resf,MYSQL_ASSOC)){
			print "<th>".getIdToPrint("features",$line['ID_FEATURE'])." ".$line['feature_text']."</th>";
		}
		print "</tr>";
		
		while( list($id) = mysql_fetch_array($resnews)){
			print "<tr>";
			print "<td>".getIdToPrint("goods",$id)."</td>";
			mysql_data_seek($resf,0);
			while($line = mysql_fetch_array($resf,MYSQL_ASSOC)){
				print "<td>".getFeatureText($id,$line['ID_FEATURE'])."</td>";
			}
			print "</tr>";
		}
		print "</table>";
	}
	$i++;
	
} else {
	$chet = false;
	$res = $database->query("
		SELECT ".DB_PREFIX."feature_directory.ID_FEATURE, ".DB_PREFIX."feature_directory.ID_FEATURE_DIRECTORY, ".DB_PREFIX."feature_directory.featuredirectory_text
		FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."feature_directory
		WHERE ID_FEATURE=$graduation and ID_RUBRIC = ".$rubric_id."
		ORDER BY featuredirectory_text
	");
	$resf = $database -> query("SELECT ".DB_PREFIX."features.* FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and feature_enable=1 and rubric_type=$type and ID_RUBRIC=".$rubric_id." and ID_FEATURE<>$graduation and rubricfeature_ls_man=1 ORDER BY rubricfeature_pos,feature_text");

		
	while(list($feat_id,$featdir_id,$feat_text) = mysql_fetch_array($res,MYSQL_NUM)){
		if($database->getArrayOfQuery("SELECT ".DB_PREFIX."goods_features.ID_FEATURE FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE = $feat_id and goodfeature_value = '$featdir_id'")){
			$r = $w = $o = "";
			foreach($arrorderby AS $i => $f){
				$r .= " LEFT JOIN cprice_goods_features AS ord$i ON (cprice_goods.ID_GOOD=ord$i.ID_GOOD)";
				$w .= " and ord$i.ID_FEATURE=$f";
				$o .= "ord$i.goodfeature_value+0,";
			}
			$resnews = $database->query("
				SELECT ".DB_PREFIX."goods.ID_GOOD
				FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."goods_features
					$r
				WHERE 
					ID_RUBRIC=".$rubric_id." and 
					".DB_PREFIX."goods_features.ID_FEATURE=$feat_id and ".DB_PREFIX."goods_features.goodfeature_value='$featdir_id' and 
					good_deleted=0 $w
				ORDER BY $o ".DB_PREFIX."goods.ID_GOOD
				LIMIT 1000
			");
		
		
			if(mysql_num_rows($resnews)>0){
				print "<h1>$feat_text</h1>";
				print "<table class='list' border=1 cellspacing=0>";
				print "<tr>";
				print "<th>ID</th>";
				mysql_data_seek($resf,0);
				while($line = mysql_fetch_array($resf,MYSQL_ASSOC)){
					print "<th>".getIdToPrint("features",$line['ID_FEATURE'])." ".$line['feature_text']."</th>";
				}
				print "</tr>";
				
				while( list($id) = mysql_fetch_array($resnews) ){
					print "<tr>";
					print "<td>".getIdToPrint("goods",$id)."</td>";
					mysql_data_seek($resf,0);
					while($line = mysql_fetch_array($resf,MYSQL_ASSOC)){
						print "<td>".getFeatureText($id,$line['ID_FEATURE'])."</td>";
					}
					print "</tr>";
				}
				print "</table>";
			}
		}
	}
}

$html = ob_get_contents();
ob_end_clean(); 
print "<html><body>".$html."</body></html>";
die();

?>