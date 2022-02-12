<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
/*Возможность выбора значений характеристик из отдельного списка вместо выпадающего 

**********/
Error_Reporting(E_ALL & ~E_NOTICE);
if( !isset($_POST['rubric']) ){
	
	// вывод
	print "<h2>Выберите рубрики".$suffix."</h2>";
	
	// функция для рекурсии
	function get_child22($type, $id, $template, $id_feature, $id_good, $cnt=false){
		global $database;
		global $showid;
		$s = "";
		$res = $database -> query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$id." ORDER BY rubric_pos,rubric_name");
		// если $cnt не указан (ф-я вызвана 1-й раз), $i = 0, иначе $cnt + 1;
		$i = (!$cnt)?1:$cnt+1;
		
		while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
			// фрагмент отмечает в HTML уже отмеченные записи и их детей
			$checked=false;
			/*if( isset( $_GET['id'] ) ){
				if(
					@$database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."rubric_goods WHERE rubricgood_deleted=0 and ID_GOOD=".@$_GET['id']." and ID_RUBRIC=".$line['ID_RUBRIC'])
				){
					$checked=true;
				}
			} elseif($line['ID_RUBRIC'] == $showid) {
				$checked=true;
			} else {
				$checked=false;
			}*/
			
			@$res_features = $database -> query("SELECT goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE=".$id_feature." and ID_GOOD=".$id_good,MYSQL_ASSOC);

			while($line2 = mysql_fetch_array($res_features,MYSQL_ASSOC)){
			//foreach($res_features['goodfeature_value'] as $id_rubric)
				if($line['ID_RUBRIC']==$line2['goodfeature_value'])
					$checked=true;
			}
			
			if($checked){
				$template = str_replace("{param}","checked {param}",$template);
			}
			
			// вызываем эту же ф-ю с $id равным текущему
			$arr = get_child22($type, $line['ID_RUBRIC'], $template, $id_feature, $id_good, $i);
			
			// заменяем переменные шаблона на данные
			$s1 = @str_replace("{name}",$line['rubric_name'].$arr['s'],$template);
			$s1 = str_replace("{formname}",$line['ID_RUBRIC'],$s1);
			$s1 = str_replace("{id}","ch".$i,$s1);
			
			// фрагмент прекращает отмечать  в HTML уже отмеченные записи
			if($checked){
				$template = str_replace("checked {param}","{param}",$template);
			}
			
			$s2 = "";
			// фрагмент генерирует JS, который отмечает флажки всех детей текущей записи по изменению текущей записи.
			if( @($arr['n']>$i) ){
				$s2 .= " onClick=\"";
				for($ii=$i+1;$ii<=$arr['n'];$ii++){
					$s2 .= "this.form.ch$ii.checked=";
				}
				$s2 .= "this.checked;\" ";
			}
			
			if( getCountRubricChild($line['ID_RUBRIC'])>0 ){
				//$s1 = str_replace("{param}","{param} disabled",$s1);
			}
			
			$s1 = str_replace("{param}",$s2,$s1);
			$s .= $s1;
			
			@$i=@$arr['n']+1;
		}
		if(!$cnt){
			return $s;
		} else {
			return array('s'=>$s,'n'=>$i-1);
		}
	}
	
	if($_GET['save']!=1){
	//вывод
	// форма. если добавление то переходит на action=add, если изменение, то action=rubric
	$id_good=$_GET['id_good'];$id_feature=$_GET['id_feature'];
	global $database;
	$rubric_type_res = $database -> query("SELECT feature_rubric FROM ".DB_PREFIX."features WHERE ID_FEATURE=$id_feature",MYSQL_ASSOC);
	$rubric_type = mysql_fetch_array($rubric_type_res,MYSQL_ASSOC);
	$rub_type=$rubric_type['feature_rubric'];
	print "<form method='post' action='".str_replace("&amp;","&",teGetUrlQuery("save=1","id_good=$id_good","id_feature=$id_feature","rub_type=$rub_type"))."'><input type=hidden name=rubric_save value=1><table><tr>";
	
	print  "<div >".get_child22($rubric_type['feature_rubric'], 0, "<div style='padding-left:".TREE_LEFT."em'><input type='checkbox' name='check_rubric[]' value={formname} id={id} {param}>{name}</div>", $id_feature, $id_good)."</div>";
	// заголовок кнопки
	if( @$_GET['typerubric']=="add" ){
		$title = "далее";
	}elseif( @$_GET['typerubric']=="edit" ){
		$title = "сохранить";
	}else{
		$title = "сохранить";
	}
	
	print "</tr></table><div align=center><input type=submit value='$title'> <input type=button value='отмена' onClick='history.back()'></div></form>";
	}
	else 
	{
		global $database;
		print print_r($_POST);

		print $rub;
		$id_good=$_GET['id_good'];$id_feature=$_GET['id_feature'];
		$rub_type=$_GET['rub_type'];
		
		$res = $database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE=$id_feature and ID_GOOD=$id_good",MYSQL_ASSOC);
		
		$rub_parent = Array();
		$rubric_parent = $database -> query("SELECT distinct rubric_parent FROM ".DB_PREFIX."rubric WHERE rubric_type=$rub_type",MYSQL_ASSOC);
		while($id_rubric = mysql_fetch_array($rubric_parent,MYSQL_ASSOC)){
			array_push($rub_parent,$id_rubric['rubric_parent']);
		}
		
		$rub = implode(",",$rub_parent);
		print $rub;
		foreach($_POST['check_rubric'] as $id_rubric) {
			if(!in_array($id_rubric,$rub_parent)){
				$res = $database -> query("INSERT INTO ".DB_PREFIX."goods_features (ID_GOOD,ID_FEATURE,goodfeature_value,goodfeature_visible) VALUES ($id_good,$id_feature,$id_rubric,1)",MYSQL_ASSOC);
					$res = "INSERT INTO ".DB_PREFIX."goods_features (ID_GOOD,ID_FEATURE,goodfeature_value,goodfeature_visible) VALUES ($id_good,$id_feature,$id_rubric,1)";
					print $res;
			}

		}
		
		$good_rub_res = $database -> query("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=$id_good",MYSQL_ASSOC);
		$good_rub = mysql_fetch_array($good_rub_res,MYSQL_ASSOC);
		$good_rubric = $good_rub['ID_RUBRIC'];

		$feature_rub_res = $database -> query("SELECT rubric_type FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=$good_rubric",MYSQL_ASSOC);
		$feature_rub = mysql_fetch_array($feature_rub_res,MYSQL_ASSOC);
		print $feature_rubric = $feature_rub['rubric_type'];
		teRedirect(teGetUrlQuery("=goods","typeview=tree","type=$feature_rubric","showid=0","rubric_id=$good_rubric","idshow=id","action=edit","id=$id_good","num=7"));
	}
}
?>