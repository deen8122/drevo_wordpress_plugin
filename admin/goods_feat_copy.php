<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  Копирование данных характеристик
*
*
* ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/
	
	// добавляем в будующие ссылки некоторые параметры
	foreach( $_GET['rubric'] AS $rubric_id => $on ){
		addGet("rubric[".(int)$rubric_id."]","on");
	}
	if( isset( $_GET['feat'] ) ){
		foreach( $_GET['feat'] AS $feat_id => $good_id ){
			addGet("feat[".(int)$feat_id."]",$good_id);
		}
	}
	
	
	if( isset($_POST['feature_id']) && !empty($_POST['good_id'])){
		$s = "";
		foreach( $_POST['feature_id'] AS $feature => $on ){
			$s .= "&feat[".$feature."]=".$_POST['good_id'];
		}
		teRedirect(teGetUrlQuery("action=add",$s));
	}
	
	
	setTitle("<h2>Копирование данных характеристик ".$rtype['rubrictype_r_m']."</h2>");
	
	print "<form method=post>";
	if(isset($_POST['good_id'])){
		$good_id = $_POST['good_id'];
		
		print "<input type=hidden name=good_id value=$good_id>";
		
		$res = $database->query("SELECT ".DB_PREFIX."features.*,".DB_PREFIX."goods_features.* FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features WHERE goodfeature_visible=1 and ID_GOOD=".$good_id);
		
		$onChange = "";
		for($i=1;$i<=mysql_num_rows($res);$i++){
			$onChange .= "this.form.feature_id_$i.checked=";
		}
		
		print "<div><input type=checkbox name=all id=all onClick='$onChange=!this.checked'><label for=all><b>все характеристики</b></label></div>";
		
		$i = 1;
		while( $line = mysql_fetch_array($res,MYSQL_ASSOC) ){
			$feature_id = $line['ID_FEATURE'];
			$feature_text = $line['feature_text'];
			$goodfeature_value = $line['goodfeature_value'];
			
			switch( $line['feature_type'] ){
				case 3:
					$goodfeature_value = ($goodfeature_value==1)?true:false;
				break;
				case 4:
					if(!empty($goodfeature_value) && $goodfeature_value!="-"){
						$line1 = $database -> getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".(int)$goodfeature_value);
						$goodfeature_value = $line1[0];
					} else {
						$goodfeature_value = "";
					}
				break;
				case 5:
					if(!empty($goodfeature_value) && $goodfeature_value!="-"){
						$line1 = $database -> getArrayOfQuery("SELECT good_name FROM ".DB_PREFIX."goods WHERE ID_GOOD=".(int)$goodfeature_value);
						$goodfeature_value = $line1[0];
					} else {
						$goodfeature_value = "";
					}
				break;
			}
			
			if(!empty($goodfeature_value)){
				print "<div><input type=checkbox name='feature_id[$feature_id]' id='feature_id_$i'><label for='feature_id_$i'>$feature_text ( $goodfeature_value )</label></div>";
			}
			
			$i++;
		}
	} else {
		foreach( $_GET['rubric'] AS $iarr => $on){
			print "<div><b>".getRubricName($iarr)."</b></div><div style='margin-left:15px'>";
			$res = $database->query("SELECT ".DB_PREFIX."goods.* FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$iarr);
			while( $line = mysql_fetch_array($res,MYSQL_ASSOC) ){
				$good_id = $line['ID_GOOD'];
				$good_name = $line['good_name'];
				print "<div><input type=radio name='good_id' id='good_id_$good_id' value='$good_id'><label for='good_id_$good_id'>$good_name</label></div>";
			}
			print "</div>";
		}
		
	}
	
	
	print "<div align=center><input type=submit value='далее'></div>";
	print "</form>";
?>