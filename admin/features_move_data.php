<?



	if(@$_POST['from'] && @$_POST['to']){
		$from = (int)@$_POST['from'];
		$to 	= (int)@$_POST['to'];
		
		$database->query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE=$to");
		$res = $database->query("SELECT ID_GOOD,goodfeature_value FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=".$rubric_id." and ID_FEATURE=$from");
		while($line = mysql_fetch_array($res)){
			if($line[1])
				$database->query("INSERT INTO ".DB_PREFIX."goods_features (ID_FEATURE,ID_GOOD,goodfeature_value) VALUES ($to,$line[0],'$line[1]')");
		}
		
		teRedirect(teGetUrlQuery(""));
		
	} else {
		$query = "SELECT ".DB_PREFIX."features.ID_FEATURE,".DB_PREFIX."features.feature_text FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE rubric_type=$type and feature_deleted=0 and ID_RUBRIC=".$rubric_id." ORDER BY rubricfeature_pos, feature_text";
		$res = $database->query($query);
		
		$arr = array();
		while($line = mysql_fetch_array($res)){
			$arr[$line[0]] = $line[1];
		}
		
		if($arr){
			print "<div class='note'>Будьте осторожны! Данная операция перенесет все данные из левой характеристики в правую, из правой характеристики данные удалятся!</div>";
		
			print "<form action='#' method='post'>";
			print "<table class='table table-bordered table-hover dataTable rubric-list'>";
			print "<tr>";
			print "<th width='1'>ID</th>";
			print "<th>Откуда</th>";
			print "<th>Куда</th>";
			print "<tr>";
			foreach($arr AS $feat_id => $feat_name){
				print "<tr>";
				print "<td>$feat_id</td>";
				print "<td><label><input type='radio' id='from_$feat_id' name='from' value='$feat_id'/>$feat_name<up></sup></label></td>";
				print "<td><label><input type='radio' id='to_$feat_id' name='to' value='$feat_id'/>$feat_name</label></td>";
				print "</tr>";
			}
			print "</table>";
			print "<p align='center'><input type='submit' class='btn btn-primary' value='Перенести данные ->'/></p>";
			print "</form>";
		}
		else{
			print "<p align='center'>В рубрике отсутствуют характеристики или записи.</p>";
			print "<div align=center><input type=button value='назад' onClick='history.back()'> </div>";
		}
	}
?>