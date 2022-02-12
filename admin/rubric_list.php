<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
// если в GET не указан раздел рубрикатора, то выдавать список разделов рубрикатора
if(!isset($_GET['type'])){
	//print "<h2>Рубрикатор</h2>";
	$s = "";
	$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 ORDER BY ID_RUBRIC_TYPE");
	while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
		if( !empty($_USER['rubric_types'][$line['ID_RUBRIC_TYPE']]) ) 
			$s .= "<li><a href='".teGetUrlQuery("","type=".$line['ID_RUBRIC_TYPE'])."'>Рубрикатор ".$line['rubrictype_r_m']."</a></li>";
	}
	if(!empty($s)){
		print "<div>Выберите нужную рубрику:</div><ul>".$s."</ul>";
	} else {
		print "<div class='error'>Извините, вам не доступен ни один раздел рубрикатора</div>";
	}
	unset($s);

} else {
	// ccылка на противоположное представление рубрикатора
	if(!empty($showid)){		if(getCountRubricChild($showid)==0){
			teRedirect(teGetUrlQuery("=goods","rubric_id=".$showid));
		}
		$line = $database->getArrayOfQuery("SELECT rubric_parent FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$showid);
		$line = $line[0];
		print "<div style='float:left;'><a href='".teGetUrlQuery("showid=$line")."'>на один уровень вверх</a></div>";
	}

	// В зависимости от представления рубрикатора (бывает дерево(default) и список), добавляем в субменю обратное представление
	if($typeview=="single"){
		addSubMenuTree(teGetUrlQuery("typeview=tree"));
		//print "<div style='float:right;' align=right><a href='".teGetUrlQuery("typeview=tree")."'>перейти к древовидному представлению рубрикатора</a></div>";
	} else {
		addSubMenuSimple(teGetUrlQuery("typeview=single&$typeview"));
		//print "<div style='float:right;' align=right><a href='".teGetUrlQuery("typeview=single")."'>перейти к поуровневому представлению рубрикатора</a></div>";
	}
	print "<br>";
	// заголовок
	//$rek = " (<a class='add' title='рекомендации по созданию и ведению рубрикатора' href='/help/rubric_comments.html' onclick=\"return hs.htmlExpand(this, { objectType: 'iframe' } )\">?</a>)";
	//$rek .= " <sup><small><a target='_blank' href='?pg=print_rubric&type=$type'>печать</a></small></sup>";
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



	///if(!$rubric_exists){
	///	teRedirect(teGetUrlQuery("=goods","rubric_id=$showid"));
	///}

	// верхняя ссылка добавления общей рубрики
	//if(  $_USER['group']<3 || ( $_USER['group']==3 && $database -> getArrayOfQuery("SELECT * FROM rubric_users WHERE ID_RUBRIC=".$showid." and ID_USER=".$_USER['id']) )  ){
		addSubMenuAdd();
		//print "<div class=add>";
		//if(empty($showid)){
			//print "<a href='".teGetUrlQuery("action=add","id=$showid")."' title='Добавить рубрику в самый первый уровень'>Добавить общую рубрику ".$rtype['rubrictype_r_m']."</a> &nbsp;&nbsp; ";
		//} else {
			//print "<a href='".teGetUrlQuery("action=add","id=$showid")."' title='Добавить подрубрику в эту рубрику'>Добавить подрубрику в ".getRubricName($showid,false)."</a> &nbsp;&nbsp; ";
		//}
		//print "</div>";
	//} else {
	//	print "<div align=center><small>у вас нет доступа для добавления новых общих подрубрик</small></div>";
	//}

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
	// комментарии к дереву
	if(mysql_num_rows($res)>10) print "<div class=note>Для добавления подрубрики, нажмите левой кнопкой мыши на знак \"плюс\" (+) слева от наименования рубрики, в которую нужно добавить подрубрику.</div>";
	if(mysql_num_rows($res)>2)  print "<div style='float:right;padding-top:7px;'>Всего ".$rtype['rubrictype_r_m'].": <b>$goodcnt</b></div>";
	if(mysql_num_rows($res)>10) print "<p style='font:0.8em;font'>В скобках указано количество ".$rtype['rubrictype_r_m']." в рубрике.</p>";
	print "<br>";
	if( $rubric_exists ){
		// заголовок таблицы
		print "<table width=100% class='table table-bordered table-hover dataTable rubric-list'>";
		print "<tr>
			<th width='1'>
			<a href='".teGetUrlQuery("orderby=id","ordertype=".((@$_GET['ordertype']=="asc")?"desc":"asc"))."'>ID</a>
			</th>
			
		      <th>";
		if(empty($showid)){
			print "Наименование рубрики";
		} else {
			print "Наименование рубрик и их".$rtype['rubrictype_i_m'];
		}
		$colspan = 5;
		if(true) $colspan+=2;
			elseif(@$_USER['rubric_types'][999999999]==2) $colspan++;
		print "</th><th colspan='$colspan' width='1%'>Действия</th></tr>";


		// шаблон дерева
		$template = "<tr height={height}>";
		$template .= "<td class='ids'>{id}</td>
			      <td><div style='padding-left:{padding-left}em;border-top:0px solid #CCCCCC;'> {name} </div></td>";
		$template .= "{actions}";
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
		print "<p>В скобках указано количество ".$rtype['rubrictype_r_m']." в рубрике.</p>";

		print "<div class=note>Для добавления подрубрики, нажмите левой кнопкой мыши на знак \"плюс\" (+) слева от наименования рубрики, в которую нужно добавить подрубрику.</div>";
		print "<div class=note>Если добавить характеристику в какую-либо рубрику, эта характеристика будет доступна во всех её подрубриках (также общие харакстеристики будут доступны для всех рубрик и их подрубрик).</div>";
	}


	// если рубрикатор большой, то ссылка снизу листинга на добавление рубрики
	if(mysql_num_rows($res)>10 || mysql_num_rows($res)==0){
		// нижняя ссылка добавления общей рубрики
		//if(  $_USER['group']<3 || ( $_USER['group']==3 && $database -> getArrayOfQuery("SELECT * FROM rubric_users WHERE ID_RUBRIC=".$showid." and ID_USER=".$_USER['id']) )  ){
			print "<div class=add>";
			if(empty($showid)){
				print "<a href='".teGetUrlQuery("action=add","id=$showid")."' title='Добавить рубрику в самый первый уровень'>Добавить общую рубрику ".$rtype['rubrictype_r_s']."</a> &nbsp;&nbsp; ";
			} else {
				print "<a href='".teGetUrlQuery("action=add","id=$showid")."' title='Добавить подрубрику в эту рубрику'>Добавить подрубрику в ".getRubricName($showid,false)."</a> &nbsp;&nbsp; ";
			}

			print "</div>";
		//} else {
		//	print "<div align=center><small>у вас нет доступа для добавления новых общих подрубрик</small></div>";
		//}
	}


	// дополнительные операции над рубриками (ссылки внизу)
	if($_USER['group']<=2){
		print "<div class='dop-func'><h4>Дополнительные функции</h4>";
		print "<ul>";
		if($rubric_exists) print "<li><a href='".teGetUrlQuery("=features","action=copy_features")."'>Копировать характеристики из одной рубрики в другую.</a></li>";
		print "<li><a href='".teGetUrlQuery("=features")."' title='Редактировать характеристики, общие для всех товаров'>Общие характеристики всех рубрик.</a></li>";
		if($rubric_exists) print "<li><a href='".teGetUrlQuery("action=pos")."'>Редактировать порядок вывода рубрик на сайте</a></li>";
		if($rubric_exists) print "<li><a href='".teGetUrlQuery("=goods-multiple","type=$type")."'>Размножить записи</a></li>";
		print "<li><a href='".teGetUrlQuery("=rubric-multiple","type=$type")."'>Размножить рубрику</a></li>";
		if($rubric_exists) print "<li><a href='".teGetUrlQuery("=goods","action=add")."' title='Сначала Вам предстоит выбрать рубрики, потом добавить информацию о товаре'>Добавить ".$rtype['rubrictype_t_s']." в несколько рубрик</a></li>";
		if($rubric_exists) print "<li><a href='".teGetUrlQuery("action=move_rubric")."'>Перемещение рубрик</a></li>";
		if($rubric_exists) print "<li><a href='".teGetUrlQuery("=to_excel")."'>Экспорт рубрикатора в Excel файл</a></li>";
		print "</ul></div>";
	}
}
	/*****
	*  ф-я вывода товаров ( в дереве рубрик )
	*****/
	function get_goods($showid,$template){
		global $database;

		$s = "";

		$res = $database->query("SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.* FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$showid." ORDER BY ".(  (!empty($_GET['ordertype'])?"cprice_goods.ID_GOOD ".(($_GET['ordertype']=="asc")?"ASC":"DESC").",":"")  )." rubricgood_pos");
		while($line=mysql_fetch_array($res,MYSQL_ASSOC)){

			$show = true;
			if(@$_GET['action']=='filter'){
				if(!filter_goods($line["ID_GOOD"])){
					$show = false;
				}
			}
			if($show){
				$text = getFeatureText($line['ID_GOOD'],0,true);

				$s1 = str_replace("{name}","<a href='".teGetUrlQuery("=goods","action=view","id=".$line['ID_GOOD'],"rubric_id=".$showid)."'>".$text."</a>",$template);

				//$text = "<nobr><a class=del href='javascript: if(confirm(\"Удалить ".(($type==1)?"товар":"услугу")." «".$line['good_name']."» из текущей категории?\")) location.href =\"".teGetUrlQuery("action=deletefromrubric","id={ID_GOOD}")."\"' title='Удалить ".(($type==1)?"товар":"услугу")." «{good_name}» из текущей рубрики'>удал. с рубрики</a></nobr>";


				// идентификатор
				$s1 = str_replace("{id}",getIdToPrint("goods",$line['ID_GOOD']),$s1);
				$s1 = str_replace("{actions}","",$s1);
				$s1 = str_replace("{child}","",$s1);

				$s .= $s1;
			}
		}

		return $s;
	}

/*****
	* функция вывода (рекурсия)
	*****/
	function get_child($type, $id, $template, $tree=false, $showgoods=false, $level = 0){
		global $_USER;
		global $showid;
		global $typeview;
		global $database;
		global $maxlevel;
		 $maxlevel=10;
		global $typeview;
		global $idshow,$count_goods;

		$s = "";

		$res = $database -> query("SELECT ID_RUBRIC,rubric_name,rubric_visible,rubric_close,rubric_img,rubric_ex FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$id." ORDER BY ".(  (!empty($_GET['ordertype'])?"ID_RUBRIC ".(($_GET['ordertype']=="asc")?"ASC":"DESC").",":"")  )." ".DB_PREFIX."rubric.rubric_pos, ".DB_PREFIX."rubric.rubric_name");
		$i=0;
		while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
			// ид. текущей записи
			$curid = $line['ID_RUBRIC'];
			//echo $curid;
			if( checkAccessChild($curid) || $_USER['rubric_types'][$type]==2 ){

				// имя текущей записи
				$curname = smallText(addslashes($line['rubric_name']));

				// наименование
				$acc = checkAccess($curid);
				//print_r($acc);exit;
				if(@$acc['v']==1||@$acc['a']==1||@$acc['e']==1||@$acc['d']==1){
					$s2 = "<a href='".teGetUrlQuery("showid=".$curid)."'>".(($showgoods)?"<b>":"").$line['rubric_name'].(($showgoods)?"</b>":"")."</a> (".getCountGoods($curid).")";
				} else {
					$s2 = $line['rubric_name'];
				}
				if($line['rubric_visible']==0){
					$s2 = "<span class='disabled'>".$s2."</span>";
				}
				// если еще возможны уровни, ставим ссылку добавления
				if( ($level+1) < $maxlevel && $acc=checkAccess($curid) /*&& $tree*/ ){
					if($acc['m']){						if(isset($count_goods[$curid])) $s3 = "<a class='del' title='запрещено' href='javascript: alert(\"Добавлять подрубрики  в рубрики с записями запрещено.\")'>+</a>";
						else $s3 = "<a class='add' title='добавить подрубрику' href='".teGetUrlQuery("action=add","id=$curid")."'>+</a>";
					} else {
						$s3 = "<a class='del' title='запрещено' href='javascript: alert(\"Добавлять подрубрики сюда Вам запрещено.\")'>+</a>";
					}
				} else {
					$s3 = "<a class='del' title='запрещено' href='javascript: alert(\"Добавлять подрубрики более $maxlevel уровня запрещено.\")'>+</a>";
				}

				$s2 = $s3.((!empty($line['rubric_img']))?
					""
					:
					"<font color=#888888>|</font>"
				).$s2;

                if(!empty($line['rubric_img'])){
					$s2.="<a href=\"javascript://\" onclick=\"_open( '".URLDATA_FLD."rubric_img/".$line['rubric_img']."', 300 , 300 );\" 
						data-href='".URLDATA_FLD."rubric_img/".$line['rubric_img']."' 
						data-target='_parent' style='float: right;'>img</a>";
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

				if(@$idshow=="id"){
					$shid = getIdToPrint("rubric",$curid);
				} else {
					$shid = dateOfChange("rubric",$curid);
				}
				$s1 = str_replace("{id}",$curid,$s1);



				$s1 = str_replace("{height}","15",$s1);
				// отступ слева (для вида дерева)
				$s1 = str_replace("{padding-left}", $level*TREE_LEFT, $s1);

				$s2 = "";
				if(true|| $access = checkAccess($curid)){
					// вывод столбцов действий

					if(@$_USER['rubric_types'][999999999]==2 || $_USER['group']<3){
						//$s2 .= "<td><a href='".teGetUrlQuery("=metadata","showid=2","action=edit","id=".$line['ID_RUBRIC'],"from=rubric")."'>SEO</a></td>";
					}

					if(true|| $_USER['login']=="root" || ($line['rubric_close']==0 && $access['m']) ){
						$s2 .= "<td><a href='".teGetUrlQuery("=features","rubric_id=".$curid)."' title='управлять характеристиками категории «${curname}»'><nobr>хар-ки</nobr></a></td>";
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
						$s2 .= "<td>".buttonDelete("javascript: if(confirm(\"Удалить «".$curname."»".(($s_child=="")?"":" и все её подрубрики")."?\")) location.href =\"".teGetUrlQuery("action=delete","id=$curid")."\";","удалить «${curname}»'")."</td>";
					} else {
						$s2 .= "<td colspan='4'></td>";
					}
				}
				$s1 = str_replace("{actions}",$s2,$s1);

				// текущую строку с детьми добавляем к общей на этом уровне
				$s .= $s1;
                                //$s .='<td>812</td>';
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






	
teAddJSScript("
	function fDisable(){
		alert('Управление характеристиками в этой рубрике запрещено администратором системы.');
	}
	function fEdit(){
		alert('Изменение рубрики запрещено админимтратором системы.');
	}
");
?>