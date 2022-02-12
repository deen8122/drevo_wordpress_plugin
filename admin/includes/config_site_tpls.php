<? 

/**********
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

/***
*  Управление выводом сайта
***/
addGet("show","conf");
addGet("step","4");

if(!empty($_GET['rtype'])){
	teSaveConf("rtpl_type",(int)$_GET['rtype']);
}
if(!teGetConf("rtpl_type")){
	setTitle("Какой раздел рубрикатора является контентом для сайта?");
	print "<ul>";
	$res = $database->query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM cprice_rubric_types WHERE rubrictype_deleted=0 ORDER BY rubrictype_name");
	while(list($rtype_id,$rtype_name)=mysql_fetch_array($res,MYSQL_NUM)){
		print "
			<li>
				<a href='javascript: if(confirm(\"Вы подтверждаете, что раздел $rtype_name является контентом сайта?\")) location.href=\"".teGetUrlQuery("rtype=$rtype_id")."\"'>$rtype_name</a>
			</li>
		";
	}
	print "</ul>";
} else{
	$type = teGetConf("rtpl_type");
	$id = (int)@$_GET['id'];
	
	if(!empty($_GET['action'])){switch($_GET['action']){
		// действия для рубрик
		
		// выбор шаблона
		case 'tplsel':
			if(isset($_POST['tplsel'])){
				teSaveConf("rtpl_".$id,$_POST['tplsel']);
				teRedirect(teGetUrlQuery("action=tplman","id=$id"));
			}
			setTitle("Выбор шаблона вывода раздела ".getRubricName($id,false,false));
			
			$tplStatus = "name";
			
			print "<form method='post' onsubmit='this.sbmt.disabled=true;this.sbmt.value=\"сохранение...\";'>";
			print "<div><small><input type='radio' name='tplsel' value=''".((teGetConf("rtpl_".$id))?"":" checked")."/>нет шаблона вывода</small></div><br/>";
			foreach (glob("tpls/*.php") as $filename) {
				$tpl_id = basename($filename);
				print "<div><label><input type='radio' name='tplsel' value='$tpl_id'".((teGetConf("rtpl_".$id)==$tpl_id)?" checked":"")."/><b>";
				include $filename;
				print "</b> <sup>($tpl_id)</sup>";
				print "</label><blockquote>$TPLDESC</blockquote></div>";
			}
			print "<div align='center'><input id='sbmt' type='submit' value='Выбор окончен'/></div>";
			print "</form>";
			
		break;
		
		
		// управление шаблоном
		case 'tplman':
			setTitle("Управление выводом раздела ".getRubricName($id,false,false));
			$tplStatus = "conf";
			include "./tpls/".teGetConf("rtpl_".$id);
		break;
		
		
		// управление шаблоном
		case 'def':
			teSaveConf("rtpl_defaultpage",$id);
			teRedirect(teGetUrlQuery());
		break;
		
	}} else {
		// список рубрик
		setTitle("Модуль управления выводом сайта");
		
		
		// выводит дерево рубрик
		function getchild($rubric_id,$left=0){
			global $type;
			global $tplStatus;
			global $database;
			
			$res = $database->query("SELECT ID_RUBRIC,rubric_name FROM cprice_rubric WHERE rubric_deleted=0 and rubric_type=$type and rubric_parent=$rubric_id ORDER BY rubric_pos");
			while(list($rid,$rname) = mysql_fetch_array($res,MYSQL_NUM)){
				$def = false;
				if(teGetConf("rtpl_defaultpage")==$rid) $def = true;
				
				print "<tr>";
				print "<td>".getIdToPrint("rubric",$rid)."</td>";
				print "<td>".(($def)?"+":"<a href='".teGetUrlQuery("action=def","id=$rid")."'>-</a>")."</td>";
				print "<td><div style='padding-left:".($left*2)."em;'>".(($def)?"<b>":"")."$rname".(($def)?"</b>":"")."</div></td>";
				
				// показ имени шаблона, если есть, иначе пусто
				
				if($inc = teGetConf("rtpl_".$rid)){
					
					// иконка модуля
					$ico = "./tpls/".str_replace("php","ico",$inc);
					print "<td>";
					if(file_exists($ico)) print "<img src='./tpls/".str_replace("php","ico",$inc)."' width='16' height='16' align='top'/> "; else print "&nbsp;";
					print "</td>";
					
					print "<td>";
					print "<a href='".teGetUrlQuery("action=tplsel","id=$rid")."'>";
					include "./tpls/$inc";
					print "</a>";
					print "</td>";
					print "<td><a href='".teGetUrlQuery("action=tplman","id=$rid")."'>управление</a></td>";
				} else {
					print "<td>&nbsp;</td>";
					print "<td><a href='".teGetUrlQuery("action=tplsel","id=$rid")."'>".(($def)?"<b>":"")."выбрать...".(($def)?"</b>":"")."</a></td>";
				}
				print "</tr>";
				getchild($rid,$left+1);
			}
		}
		
		$tplStatus = "name";
		
		print "<table class='list'>";
		print "<tr>";
		print "<th width='1'>ID</th>";
		print "<th width='1'></th>";
		print "<th>Наименование раздела сайта</th>";
		print "<th width='1'>&nbsp;</th>";
		print "<th width='1'><small>Шаблон вывода</small></th>";
		print "<th width='1'><small>Управление шаблоном</small></th>";
		print "</tr>";
		getchild(0);
		print "</table>";
		
		
		
	}
}
?>