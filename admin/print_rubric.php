<html><body onload="print();window.close();">

<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/***
*  
*  Печать рубрики. Писал Гарифьянов И.
*  
***/
// подрубрики этой рубрики показывать
$showid = (int)@$_GET['showid'];
// ид. выбранной рубрики (для изменения, удаления и т.д.)
$id = (int)@$_GET['id'];
// тип (товар или услуга)
$type = (int)@$_GET['type'];
if($_USER['group']==3 && !$_USER['rubric_types'][$type]) teRedirect(teGetUrlQuery());
if(checkAccess($id)==0) teRedirect(teGetUrlQuery());
$access = @$_USER['rubric_types'][$type];

if($showid>0){
	$line = $database -> getArrayOfQuery("SELECT rubric_type FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$showid);
	$type = $line[0];
}

$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 and ID_RUBRIC_TYPE=".$type);
$rtype=mysql_fetch_array($res,MYSQL_ASSOC);
if($rtype['ID_RUBRIC_TYPE']!=$type) teRedirect(teGetUrlQuery());

// тип просмотра
$typeview = (empty($_GET['typeview']))?"tree":$_GET['typeview'];

addGet("typeview",$typeview);
addGet("type",$type);
addGet("showid",$showid);

$maxlevel =  $database -> getArrayOfQuery("SELECT rubrictype_maxlevel FROM ".DB_PREFIX."rubric_types WHERE ID_RUBRIC_TYPE=".$type);
// уровень текущей рубрики

// функ. просчета кол-ва уровней
function sumLevel($id){
	global $database;
	
	$level = 0;
	$id1 = $id;
	while( 1 ){
		$line = $database -> getArrayOfQuery("SELECT rubric_parent FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$id1,MYSQL_ASSOC);
		$id1 = (int)$line['rubric_parent'];
		$level++;
		if($id1==0) break;
	}
	return $level;
}
//считаем уровень
$level = sumLevel($showid);


	
	if(!isset($_GET['type'])){
		if( teGetConf('service_enable')==0 ){
			teRedirect(teGetUrlQuery("type=1"));
		}
		
		print "<h2>Рубрикатор</h2>";
		
		$s = "";
		$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 ORDER BY ID_RUBRIC_TYPE");
		while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
			if( !empty($_USER['rubric_types'][$line['ID_RUBRIC_TYPE']]) ) $s .= "<li><a href='".teGetUrlQuery("","type=".$line['ID_RUBRIC_TYPE'])."'>Рубрикатор ".$line['rubrictype_r_m']."</a></li>";
		}
		if(!empty($s)){
			print "<div>Выберите нужную рубрику:</div><ul>".$s."</ul>";
		} else {
			print "<div class='error'>Извините, вам не доступен ни один раздел рубрикатора</div>";
		}
		unset($s);
		
	} else {
		addHelp("rubric_comments.html");
		
		
		/*****
		*  ф-я вывода товаров ( в дереве рубрик )
		*****/
		function get_goods($showid,$template){
			global $database;
			
			$s = "";
			
			$res = $database->query("SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.* FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$showid." ORDER BY ".DB_PREFIX."goods.good_name");
			while($line=mysql_fetch_array($res,MYSQL_ASSOC)){

				$show = true;
				if(@$_GET['action']=='filter'){
					if(!filter_goods($line["ID_GOOD"])){
						$show = false; 
					}
				}
				if($show){
					$text = "<div style=float:left>".$line['good_name']."</div><div style=float:right>Цена:".$line['good_price']."</div><br>".smallText($line['good_desc'],75);
					
					$s1 = str_replace("{name}","<a href='".teGetUrlQuery("=goods","action=view","id=".$line['ID_GOOD'],"rubric_id=".$showid)."'>".$text."</a>",$template);
					
					
					$s1 = str_replace("{actions}","",$s1);
					$s1 = str_replace("{child}","",$s1);
					
					$s .= $s1;
				}
			}
			
			return $s;
		}
		
		/*****
		* функция вывода
		*****/
		function get_child($type, $id, $template, $tree=false, $showgoods=false, $level = 0){
			global $_USER;
			global $showid;
			global $typeview;
			global $database;
			global $maxlevel;
			global $typeview;
			
			$s = "";
			
			$res = $database -> query("SELECT ID_RUBRIC,rubric_name,rubric_visible,rubric_close FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$id." ORDER BY ".DB_PREFIX."rubric.rubric_pos, ".DB_PREFIX."rubric.rubric_name");
			$i=0;
			while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
				// ид. текущей записи
				$curid = $line['ID_RUBRIC'];
				if( checkAccessChild($curid) ){
					
					// имя текущей записи
					$curname = smallText(addslashes($line['rubric_name']));
					
					// наименование
					$s2 = $line['rubric_name']." &nbsp;(".getCountGoods($line['ID_RUBRIC']).")";
					
					if($line['rubric_visible']==0){
						$s2 = "<span class='disabled'>".$s2."</span>";
					}
					
					
					$s1 = str_replace("{name}",$s2,$template);
					
					// дети
					if($tree){
						$s_child = get_child($type, $line['ID_RUBRIC'], $template, $tree, $showgoods, $level+1);
					} else {
						$s_child = "";
					}
					$s1 = str_replace("{child}", $s_child, $s1);
					
					// идентификатор
					$s1 = str_replace("{id}",$curid,$s1);
					
					
					
					$s1 = str_replace("{height}","15",$s1);
					// отступ слева (для вида дерева)
					$s1 = str_replace("{padding-left}", $level*TREE_LEFT, $s1);
					
					$s2 = "";
					if($access = checkAccess($curid)){
						// вывод столбцов действий
						if( $_USER['login']=="root" || ($line['rubric_close']==0 && $access>1) ){
							$s2 .= "<td><a href='".teGetUrlQuery("=features","rubric_id=".$curid)."' title='управлять характеристиками категории «${curname}»'><nobr>хар-ки (".getCountFeatures($curid).")</nobr></a></td>";
							$s2 .= "<td>".buttonEdit(teGetUrlQuery("action=edit","id=".$curid),"изменить категорию «${curname}»")."</td>";
							if( $_USER['login']=="root" ){
								if($line['rubric_close']==1){
									$s2 .= "<td>".buttonOpen(teGetUrlQuery("action=opench","id=".$curid),"разрешить изменения «${curname}»")."</td>";
								} else {
									$s2 .= "<td>".buttonClose(teGetUrlQuery("action=closech","id=".$curid),"закрепить от изменений «${curname}»")."</td>";
								}
							}
							if($line['rubric_visible']==1){
								$s2 .= "<td>".buttonDisable(teGetUrlQuery("action=disable","id=".$curid))."</td>";
							} else {
								$s2 .= "<td>".buttonEnable(teGetUrlQuery("action=enable","id=".$curid))."</td>";
							}
							$s2 .= "<td>".buttonDelete("javascript: if(confirm(\"Удалить рубрику «".$curname."»".(($s_child=="")?"":" и все её подрубрики")."?\")) location.href =\"".teGetUrlQuery("action=delete","id=$curid")."\";","удалить «${curname}»'")."</td>";
						} else {
							$s2 .= "<td colspan='4'></td>";
						}
					}
					$s1 = str_replace("{actions}",$s2,$s1);
					
					// текущую строку с детьми добавляем к общей на этом уровне
					$s .= $s1;
					
					// вывод товаров
					if($showgoods){	
						$s .= get_goods($curid,str_replace("{padding-left}",($level+1.2)*TREE_LEFT,str_replace("{height}",35,$template)));
					}
					
					$i++;
					
				}
			}
			if($i==0) $s = false;
			return $s;
		}
		// конец функции для древовидного вывода
			
		// ccылка на противоположное представление рубрикатора
		if(!empty($showid)){
			$line = $database->getArrayOfQuery("SELECT rubric_parent FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$showid);
			$line = $line[0];
			print "<div style='float:left;'><a href='".teGetUrlQuery("showid=$line")."'>на один уровень вверх</a></div>";
		}
		
		if($typeview=="single"){
			addSubMenuTree(teGetUrlQuery("typeview=tree"));
			//print "<div style='float:right;' align=right><a href='".teGetUrlQuery("typeview=tree")."'>перейти к древовидному представлению рубрикатора</a></div>";
		} else {
			addSubMenuSimple(teGetUrlQuery("typeview=single"));
			//print "<div style='float:right;' align=right><a href='".teGetUrlQuery("typeview=single")."'>перейти к поуровневому представлению рубрикатора</a></div>";
		}
		print "<br>";
		
		$rek = "";
		
		// заголовок
		if( $typeview == "tree" ){
			if(empty($showid)){
				print "<h2>Рубрикатор ".$rtype['rubrictype_r_m'].$rek."</h2>";
			} else {
				print "<h2>Подрубрики и ".$rtype['rubrictype_i_s']." рубрики ".getRubricName($showid).$rek." </h2>";
			}
		} else {
			if(empty($showid)){
				print "<h2>Рубрикатор ".$rtype['rubrictype_r_m'].$rek."</h2>";
			} else {
				print "<h2>Подрубрики рубрики ".getRubricName($showid).$rek." </h2>";
			}
		}
		
		// есть ли рубрики?
		$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=0");
		if(mysql_num_rows($res)>0) $rubric_exists = true; else $rubric_exists = false;
		if( $rubric_exists==true && $_USER['group']==3 ){
			if( !checkAccessChild(0) ){
				$rubric_exists = false;
			}
		}
			addSubMenuAdd();
			
		$resgoods = $database->query("
			SELECT count(".DB_PREFIX."goods.ID_GOOD) 
			FROM ".DB_PREFIX."rubric NATURAL JOIN ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods
			WHERE rubricgood_deleted=0 and good_deleted=0 and rubric_deleted=0 and rubric_type=$type
			GROUP BY cprice_rubric_goods.ID_RUBRIC
		");
		$goodcnt = 0;
		while($goodln = mysql_fetch_array($resgoods)){
			$goodcnt += $goodln[0];
		}
			
		
		if( $rubric_exists ){
			// заголовок таблицы
			print "<table width=100% class=list>";
			
			// шаблон строки 
			$template = "<tr height={height}>";
			$template .= "<td><div style='padding-left:{padding-left}em;border-top:0px solid #CCCCCC;'> {name} </div></td>";
			//$template .= "{actions}";
			$template .= "</tr> {child} ";
			
			// выводим дерево с шаблоном
			if($typeview=='tree'){
				if(empty($showid)){
					print get_child($type, 0, $template, true, false);
				} else {
					print get_child($type, $showid, $template, true, true);
				}
			} else {
				print get_child($type, $showid, $template, false, false);
			}
			
			print "</table>";
			
			print "<div style='float:right;padding-top:7px;'>Всего ".$rtype['rubrictype_r_m'].": <b>$goodcnt</b></div>";
		}
				
	}
	
	
	die("</body></html>");
?>