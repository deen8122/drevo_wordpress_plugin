<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  выбор рубрик записи
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

if( !isset($_POST['rubric']) ){
/// вывод
	
	/// вывод заголовка
	// в зависимости от действия (добавление товара, изменение списка рубрик)
	if( @$_GET['typerubric']=="add" ){
		$suffix = ", в которых будет состоять добавляемая единица";
	}elseif( @$_GET['typerubric']=="edit" ){
		$line = $database -> getArrayOfQuery("SELECT good_name FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$id);
		$suffix = ", в которых будет состоять ".$rtype['rubrictype_i_s']." «".getFirstFeatureText($rubric_id,$id)."»";
	}else{
		$suffix = "";
	}
	// вывод
	print "<h2>Выберите рубрики".$suffix."</h2>";
	
	// примечание
	if( @$_GET['typerubric']=="add" ){
		print "<div class=note>После сохранения принадлежности единицы к рубрикам, вам будет показана форма добавления характеристик этой единицы</div>";
	}else{
		print "<div class=note>После сохранения принадлежности единицы к рубрикам, вам будет показана форма редактирования характеристик этой единицы. <br>Это связано с тем, что у каждой рубрики есть свои определенные характеристики.</div>";
	}
	
	// функция для рекурсии
	function get_child($type, $id, $template, $cnt=false){
		global $database;
		global $showid;
		$s = "";
		$res = $database -> query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$id." ORDER BY rubric_pos,rubric_name");
		// если $cnt не указан (ф-я вызвана 1-й раз), $i = 0, иначе $cnt + 1;
		$i = (!$cnt)?1:$cnt+1;
		
		while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
			// фрагмент отмечает в HTML уже отмеченные записи и их детей
			$checked=false;
			if( isset( $_GET['id'] ) ){
				if(
					@$database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."rubric_goods WHERE rubricgood_deleted=0 and ID_GOOD=".@$_GET['id']." and ID_RUBRIC=".$line['ID_RUBRIC'])
				){
					$checked=true;
				}
			} elseif($line['ID_RUBRIC'] == $showid) {
				$checked=true;
			} else {
				$checked=false;
			}
			if($checked){
				$template = str_replace("{param}","checked {param}",$template);
			}
			
			// вызываем эту же ф-ю с $id равным текущему
			$arr = get_child($type, $line['ID_RUBRIC'], $template, $i);
			
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
	
	//вывод
	// форма. если добавление то переходит на action=add, если изменение, то action=rubric
	print "<form method='post' action='".str_replace("&amp;","&",teGetUrlQuery((!empty($id))?"action=edit":"action=add","type=$type","id=$id","showid=$showid","do=rubric"))."'><input type=hidden name=rubric_save value=1><table><tr>";
	$res = $database->query("SELECT * FROM cprice_rubric_types WHERE rubrictype_deleted=0 and rubrictype_visible=1");
	while($line = mysql_fetch_array($res)){
		print "<td valign='top'>".get_child($line['ID_RUBRIC_TYPE'], 0, "<div style='padding-left:".TREE_LEFT."em'><input type='checkbox' name='rubric[{formname}]' id={id} {param}>{name}</div>")."</td>";
	}
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
/*		else {
		// очищаем БД от устаревшей инфы
		$database -> query("DELETE FROM rubric_goods WHERE ID_GOOD=".$id,false);
		// добавляем новую инфу
		foreach( $_POST['rubric'] AS $rubric => $on ){
			$database -> query("INSERT INTO rubric_goods (ID_RUBRIC,ID_GOOD) VALUES ($rubric,$id)",true,2);
		}
		
		unset($_GET['action']);
	}
*/
?>