<?
	addGet("action",$_GET['action']);
	if(isset($_POST['check'])){

	}
/****///print_r($_POST);
	print "<h2>«аполнение данных определенных характеристик</h2>";
	print "<form method='post' action='#' onsubmit='this.sbmt.disabled=true;'>";
	print "<input type='hidden' name='check' value='1' />";
	
	if(!empty($_POST['check'])){
		$features = array();
		$featurest = array();
		$res = $database->query("SELECT ".DB_PREFIX."rubric_features.ID_FEATURE,".DB_PREFIX."features.feature_text,".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features WHERE ID_RUBRIC=$rubric_id ORDER BY rubricfeature_pos");
		while( $line = mysql_fetch_array($res) ){
			if(isset($_POST['feature'.$line[0]])){
				$features[$line[0]] = $line[1];
				$featurest[$line[0]] = $line[2];
				print "<input type='hidden' name='feature$line[0]' value='1'>";
			}
		}
		
		if(!empty($_POST['good'])){
			foreach($_POST['good'] AS $g_id => $g_arr){
				foreach($g_arr AS $f_id => $val){
					
					list($ftype,$multiple) = $database->getArrayOfQuery("SELECT feature_type,feature_multiple FROM ".DB_PREFIX."features WHERE ID_FEATURE=$f_id");
					if($ftype==3){
						if($val=="on") $val=1; else $val=0;
					}
					if($ftype==7){
						$database->query("INSERT INTO cprice_texts (text_text) VALUES ('$val')",false);
						$val = $database->id();
					}
					if($ftype==3){
						$database->query("DELETE FROM cprice_goods_features WHERE ID_FEATURE=$f_id and ID_GOOD=$g_id",false);
						$oldval = false;
					} else {
						$oldres = $database->query("SELECT goodfeature_value FROM cprice_goods_features WHERE ID_FEATURE=$f_id and ID_GOOD=$g_id");
						list($oldval) = mysql_fetch_array($oldres);
					}
					
					if(mysql_num_rows($oldres)>1&&!$multiple){
						$database->query("DELETE FROM cprice_goods_features WHERE ID_FEATURE=$f_id and ID_GOOD=$g_id",false);
						$oldval = "";
					}
					
					if(!empty($oldval)){
						$database->query("UPDATE cprice_goods_features SET goodfeature_value='$val' WHERE ID_FEATURE=$f_id and ID_GOOD=$g_id",true,2);
					} elseif(!empty($val)) {
						$database->query("INSERT INTO cprice_goods_features (ID_GOOD,ID_FEATURE,goodfeature_value) VALUES ($g_id,$f_id,'$val')",true,2);
					}
					//print "<div>".$g_id."*".$f_id."= <strike>".$oldval."</strike> -> ".$val."</div>";
				}
				
			}//die();
			//teRedirect(teGetUrlQuery("action="));
		/**/
		}
		
		if(count($features)>0){
			print "<table class=list>";
			print "<tr class=list>";
			print "<th class=list width='1px'>ID</th>";
			//print "<th class=list>Ќаименование</th>";
			foreach($features AS $n => $v){
				print "<th class=list>".getIdToPrint("features",$n).". $v</th>";
			}
			print "</tr>";
			$res = $database->query($goodssql);
			while( $line = mysql_fetch_array($res,MYSQL_ASSOC) ){
				print "<tr class=list>";
				print "<td class=list>".getIdToPrint("goods",$line['ID_GOOD'])."</td>";
				//print "<td class=list>".smallText(getFeatureText($line['ID_GOOD'],0,true))."</td>";
				foreach($features AS $n => $v){
					print "<td class=list align=center>";
					$feature_type = $database->getArrayOfQuery("SELECT feature_type,feature_rubric FROM ".DB_PREFIX."features WHERE ID_FEATURE=$n");
					$feature_rubric = $feature_type[1];
					$feature_type = $feature_type[0];
					
					$answertext = $database->getArrayOfQuery("SELECT goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE=".$n." and ID_GOOD=".$line['ID_GOOD']);
					$answertext = $answertext[0];
					$elemname = "good[".$line['ID_GOOD']."][".$n."]";
					switch($feature_type){
						case 1:
						case 2:
						case 8:
							print "<input name='$elemname' id='$elemname' type='text' value='".@$answertext."'/>";
						break;
						case 3:
							print "<input name='$elemname' type='hidden' value='0'/>";
							print "<input name='$elemname' type='checkbox' ".((@$answertext)?"checked='1' ":"")."/>";
						break;
						case 7:
							if($answertext!="" && is_numeric($answertext)){
								$answertext = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT=".$answertext);
								$answertext = $answertext[0];
							}
							print "<textarea name='$elemname'>".@$answertext."</textarea>";
							
						break;
						/*case 3:
							print "<input name='$elemname' type='checkbox' ".(($answertext==0)?"checked":"").">";
						break;*/
						case 4:
							print "<select name='$elemname'><option></option>";
								$res1 = $database -> query("SELECT ID_FEATURE_DIRECTORY,featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$n);
								while($line1 = mysql_fetch_array($res1)){
									print "<option value='$line1[0]' ".(($answertext==$line1[0])?"selected":"").">".$line1[1]."</option>";
								}
							print "</select>";
						break;
						/*case 5:  						//die("SELECT ".DB_PREFIX."goods.ID_GOOD,".DB_PREFIX."goods.good_name FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=".$feature_rubric);
							print "<select name='$elemname'><option>"; 
								$res1 = $database -> query("SELECT ".DB_PREFIX."goods.ID_GOOD,".DB_PREFIX."goods.good_name FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=".$feature_rubric);
								while($line1 = mysql_fetch_array($res1)){
									print "<option value='$line1[0]' ".(($answertext==$line1[0])?"selected":"").">".$line1[1]."</option>";
								}
							print "</select>";
						break;*/
						/*
						case 6:
							if($answertext!="-") print "<tr><td class=name>".$line['feature_text'].":</td><td class=value><a href='".URLDATA_FLD."features/".$answertext."'>".$answertext."</a></td></tr>";
						break;
						*/
					}
					print "</td>";
				}
				print "</tr>";
			}
			print "</table><br>";
			print "<div align=center><input id='sbmt' type='submit' value='сохранить' ></div>";
		}
	} else {
		print "<h3>¬ыберите заполн¤емые характеристики</h3>";
		$res = $database->query("SELECT ".DB_PREFIX."rubric_features.ID_FEATURE,".DB_PREFIX."features.feature_text,".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features WHERE ID_RUBRIC=$rubric_id and rubric_type=$type and feature_type!=5 and feature_type!=9 and feature_type!=6 ORDER BY rubricfeature_pos");
		while( $line = mysql_fetch_array($res) ){
			print "<div><input type=checkbox name='feature".$line[0]."' id='f{$line[0]}'><label for='f{$line[0]}'>{$line[0]}. {$line[1]}</label></div>";
		}
		print "<div align=center><input id='sbmt' type='submit' value='перейти к заполнению'/></div>";
	}
	print "</form>";
?>