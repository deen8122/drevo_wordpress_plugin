<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  Раздел SEO
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

global $USER;
global $database;

// проверка на доступ
if($USER['group']>2) die;

// переменные из GET
$id = (int)@$_GET['id'];
$rubric_id = (int)@$_GET['rubric_id'];
$showid = @$_GET['showid'];
$type = @$_GET['type'];
$simpled = @$_GET['simpled'];
$nograd = (int)@$_GET['nograd'];

// параметры в новые урлы
addGet("type",$type);
addGet("simpled",$simpled);

// ссылка "ВВЕРХ"
print_link_main();


// в субменю добавляем все разделы рубрикатора
if(!empty($type)){
	$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 ORDER BY ID_RUBRIC_TYPE");
	while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
		addSubMenu(teGetUrlQuery("showid=2","type=".$line['ID_RUBRIC_TYPE']),$line['rubrictype_name']);
	}
}


// ссылка на сайт
// на любой странице раздела SEO выводит в субменю ссылку на соответствующую страницу сайта
if($type==teGetConf("rtpl_type")){
	if($hosts[DB_ID]['siteversion']==4||$hosts[DB_ID]['siteversion']==3||$hosts[DB_ID]['siteversion']==2){
		if( ($showid==3&&!isset($_GET['action'])) || ($showid==2&&isset($_GET['action'])) ) {
			if($showid==2) $rubric_id = $id;
			if($hosts[DB_ID]['siteversion']==4){
				list($url) = $database -> getArrayOfQuery("SELECT rubric_textid FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
			}
			if($hosts[DB_ID]['siteversion']==3){
				list($url) = $database -> getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
				$url = filename2(translit2($url));
			}
			//addSubMenu($hosts[DB_ID]['url']."?p=".$url."' target='blank","<img src='{$skinpath}images/b_www_big.ico' alt='на сайт' title='на сайт'/>","submenustd");
		} elseif($rubric_id>0) {
			if($hosts[DB_ID]['siteversion']==4){
				list($url) = $database -> getArrayOfQuery("SELECT rubric_textid FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
			}
			if($hosts[DB_ID]['siteversion']==3){
				list($url) = $database -> getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
				$url = filename2(translit2($url));
			}
			// берем 1 градацию
			list($grad) = $database->getArrayOfQuery("
				SELECT ID_FEATURE
				FROM ".DB_PREFIX."rubric_features
				WHERE rubricfeature_graduation=1 and ID_RUBRIC = ".$rubric_id."
			");
			if(!empty($grad)){
				$vl = getFeatureValue($id,$grad);
				$grad = "&grad[$grad]=".$vl."&graduation[$grad]=".$vl."&group_id=".$vl;
			}
			if($hosts[DB_ID]['siteversion']==4||$hosts[DB_ID]['siteversion']==3){
				//addSubMenu($hosts[DB_ID]['url']."?p=".$url.$grad."&id=".$id."' target='blank","<img src='{$skinpath}images/b_www_big.ico' alt='на сайт' title='на сайт'/>","submenustd");
			} else {
				//addSubMenu($hosts[DB_ID]['url']."?page=".$rubric_id."&id=".$id."' target='blank","<img src='{$skinpath}images/b_www_big.ico' alt='на сайт' title='на сайт'/>","submenustd");
			}
		}
	}
}



if(isset($_GET['action'])){
	switch($_GET['action']){
				case 'msword':	// генерация в WORD (Все вопросы к Галлямову Дамиру)
				$graduation = array();
				$graduation = @$_GET['graduation'];

				if(count($graduation)>0){
					/*          [rubric]feat1=val1;feat2=val2;featN=valN            */
					$s = "[$id]";
					foreach($graduation AS $feature => $value){
						$s .= $feature."=".$value.";";
					}
					$id = $s;
				}

				$grads = "";
				if(!empty($graduation))
				foreach($graduation AS $i => $feature_id){
					$grads .= "graduation[$i]=$feature_id&";
				}

				// sdie($id);
			//}
//			if(!empty($_GET['id'])) $id = (int)@$_GET['id'];

			// если нет записи
			$res = $database -> query("SELECT * FROM ".DB_PREFIX."metadata WHERE metadata_page=".$showid." and metadata_id='".$id."'");
			if( !$line = mysql_fetch_array($res,MYSQL_ASSOC) ){
				// создаем
				$database -> getArrayOfQuery("INSERT INTO ".DB_PREFIX."metadata (metadata_page,metadata_id) VALUES ($showid,'$id')");
				// берем значения (пустые)
				$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."metadata WHERE metadata_page=".$showid." and metadata_id='".$id."'",MYSQL_ASSOC);
			}

        	header( "Content-type: application/msword" );
		    header( "Content-Disposition: inline, filename=metta1.rtf" );
		    //$output = "{\rt}";
		    $filename = "metta.rtf";
		    $fp = fopen($filename, "r");
		    $output = fread($fp, filesize($filename));
		    fclose($fp);
		    $arr1 = array('%title1%', '%h1%', '%after_h1%', '%h2%', '%after_h2%', '%title2%', '%keywords%','%description%');
		    $arr2 = array($line['metadata_head_title'],$line['metadata_body_h1'],$line['metadata_body_description'],$line['metadata_body_h2'],$line['metadata_body_keywords'],$line['metadata_meta_title'],$line['metadata_meta_keywords'],$line['metadata_meta_description']);
		    $output = str_replace($arr1,$arr2, $output);
		    echo $output;
		    exit;
		break;

		case 'edit':	// Редактирование метаданных страницы (форма и сохранение)
//			if($showid==2){
				$graduation = array();
				$graduation = @$_GET['graduation'];
                $mid = $id;

			if($showid==2 || $showid==4 ){				$rubric_id = $id;
				if(count($graduation)>0){
					/*          [rubric]feat1=val1;feat2=val2;featN=valN            */
					$s = "[$id]";
					foreach($graduation AS $feature => $value){
						$s .= $feature."=".$value.";";
					}
					$mid = $s;
				}
    		}
				$grads = "";
				if(!empty($graduation))
				foreach($graduation AS $i => $feature_id){
					$grads .= "graduation[$i]=$feature_id&";
				}
                $grads .= "nograd=".$nograd;
				// sdie($id);
			//}
//			if(!empty($_GET['id'])) $id = (int)@$_GET['id'];

			// если нет записи
			$create = false;
			$res = $database -> query("SELECT * FROM ".DB_PREFIX."metadata WHERE metadata_page=".$showid." and metadata_id='".$mid."'");
			if( !$line = mysql_fetch_array($res,MYSQL_ASSOC) ){
				$create = true;
				// создаем
				//$database -> getArrayOfQuery("INSERT INTO ".DB_PREFIX."metadata (metadata_page,metadata_id) VALUES ($showid,'$mid')");
				// берем значения (пустые)
				//$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."metadata WHERE metadata_page=".$showid." and metadata_id='".$mid."'",MYSQL_ASSOC);
			}

			print "<div align=center>";
			setTitle("SEO. Метаданные страницы.");

			if($showid==2 || $showid==4){
				print "<h3>".getRubricName($rubric_id)."</h3>";
			} else {
				if($rubric_id>0 && $id>0)
				print "<h3>".getFirstFeatureText($rubric_id,$id)."</h3>";
			}

			$frm = new teForm("form1","post");

			$frm->addTitle("".$line['metadata_head_title']."");

			$frm->addf_text("metadata_head_title", "Заголовок документа", @$line['metadata_head_title']);
			$frm->addf_desc("metadata_head_title", "Показывается в заголовке окна браузера<br><b>&lt;title&gt;...&lt;/title&gt;</b>");
			$frm->setFieldWidth("metadata_head_title", "400px");

			if($line['metadata_head_title']==$line['metadata_body_h1']) $v = "true"; else $v = "false";
			teAddJSScript("var ish1=$v");
			if($line['metadata_head_title']==$line['metadata_body_h2']) $v = "true"; else $v = "false";
			teAddJSScript("var ish2=$v");
			if($line['metadata_head_title']==$line['metadata_meta_title']) $v = "true"; else $v = "false";
			teAddJSScript("var ishm=$v");

			$frm->setJSScript(
				"metadata_head_title",
				"onKeyUp","
				if(ish1) this.form.metadata_body_h1_0.value=this.value;
				if(ish2) this.form.metadata_body_h2_0.value=this.value;
				if(ishm) this.form.metadata_meta_title_0.value=this.value;
			"
			);
			$frm->setJSScript("metadata_body_h1","onKeyUp","ish1=false");
			$frm->setJSScript("metadata_body_h2","onKeyUp","ish2=false");
			$frm->setJSScript("metadata_meta_title","onKeyUp","ishm=false");

			$frm->addf_text("metadata_body_h1", "H1-заголовок", @$line['metadata_body_h1']);
			$frm->addf_desc("metadata_body_h1", "Будет показываться сверху страницы<br><b>&lt;h1&gt;...&lt;/h1&gt;</b>");
			$frm->setFieldWidth("metadata_body_h1", "400px");

			$frm->addf_text("metadata_body_description", "Блок сверху страницы.", @$line['metadata_body_description'], true);
			$frm->addf_desc("metadata_body_description", "Будет показываться после H1-заголовка<br><b>&lt;h1&gt;&lt;/h1&gt;...</b>");
			$frm->setFieldWidth("metadata_body_description", "400px");
			$frm->setFieldHeight("metadata_body_description", "100px");
			//word("#metadata_body_description");

			$frm->addf_text("metadata_body_h2", "H2-заголовок", @$line['metadata_body_h2']);
			$frm->addf_desc("metadata_body_h2", "Будет показываться снизу страницы<br><b>&lt;h2&gt;...&lt;/h2&gt;</b>");
			$frm->setFieldWidth("metadata_body_h2", "400px");

			$frm->addf_text("metadata_body_keywords", "Блок снизу страницы.", @$line['metadata_body_keywords'], true);
			$frm->addf_desc("metadata_body_keywords", "Будет показываться после H2-заголовка.<br><b>&lt;h2&gt;&lt;/h2&gt;...</b>");
			$frm->setFieldWidth("metadata_body_keywords", "400px");
			$frm->setFieldHeight("metadata_body_keywords", "100px");
			//word("#metadata_body_keywords");

			$frm->addf_text("metadata_meta_title", "Мета-заголовок", @$line['metadata_meta_title']);
			$frm->addf_desc("metadata_meta_title", "<b>&lt;meta name=\"title\" content=\"...\"&gt;</b>");
			$frm->setFieldWidth("metadata_meta_title", "400px");

			$frm->addf_text("metadata_meta_keywords", "Мета-Ключевые слова", @$line['metadata_meta_keywords'], true);
			$frm->addf_desc("metadata_meta_keywords", "Через пробел или запятую.<br><b>&lt;meta name=\"keywords\" content=\"...\"&gt;</b>");
			$frm->setFieldWidth("metadata_meta_keywords", "400px");
			$frm->setFieldHeight("metadata_meta_keywords", "100px");

			$frm->addf_text("metadata_meta_description", "Мета-описание", @$line['metadata_meta_description'], true);
			$frm->addf_desc("metadata_meta_description", "<b>&lt;meta name=\"description\" content=\"...\"&gt;</b>");
			$frm->setFieldWidth("metadata_meta_description", "400px");
			$frm->setFieldHeight("metadata_meta_description", "100px");

			if($frm->send()){

			} else {
				// сохранение метаданных
				if($create)$database -> query("INSERT INTO ".DB_PREFIX."metadata (metadata_head_title, metadata_meta_title, metadata_meta_keywords, metadata_meta_description, metadata_body_h1, metadata_body_description, metadata_body_h2, metadata_body_keywords, metadata_page,metadata_id) VALUES
					('".$frm->get_value('metadata_head_title')."','".$frm->get_value('metadata_meta_title')."','".$frm->get_value('metadata_meta_keywords')."','".$frm->get_value('metadata_meta_description')."','".$frm->get_value('metadata_body_h1')."','".$frm->get_value('metadata_body_description')."','".$frm->get_value('metadata_body_h2')."','".$frm->get_value('metadata_body_keywords')."',".$showid.",'".$mid."')");
				else $database -> query("UPDATE ".DB_PREFIX."metadata SET metadata_head_title='".$frm->get_value('metadata_head_title')."', metadata_meta_title='".$frm->get_value('metadata_meta_title')."', metadata_meta_keywords='".$frm->get_value('metadata_meta_keywords')."', metadata_meta_description='".$frm->get_value('metadata_meta_description')."', metadata_body_h1='".$frm->get_value('metadata_body_h1')."', metadata_body_description='".$frm->get_value('metadata_body_description')."', metadata_body_h2='".$frm->get_value('metadata_body_h2')."', metadata_body_keywords='".$frm->get_value('metadata_body_keywords')."' WHERE metadata_page=".$showid." and metadata_id='".$mid."'");

				if(@$_GET['from']=='rubric'){
					teRedirect(teGetUrlQuery("=rubric","type=$type","showid=0"));
				}
				teRedirect(teGetUrlQuery("=metadata","showid=3","rubric_id=".$rubric_id,$grads));
			}
			print "</div>";
		break;
	}
}


// если не редактирование метаданных, и не генерация в ворд, то...
// ... вывод списка раздела рубрикатора
// ... вывод дерева рубрик
// ... вывод градаций
if(!isset($_GET['action'])){

	if( !empty($showid) ){

		print_link_up("");


		switch( $showid ){

			case 2:	// дерево рубрик выбранного раздела $_GET[type]
				list($s) = $database -> getArrayOfQuery("SELECT rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE ID_RUBRIC_TYPE=".$type);
				setTitle("SEO. ".$s);
				// выводим древо раздела
				print getChild();
			break;

			case 3:	// вывод записей рубрики $_GET[rubric_id]

				$rubric_id = (int)@$_GET['rubric_id'];
				$graduation = @$_GET['graduation'];
				// ф-я для выборки всех детей рубрики
				function getChilds($id){
					global $database;

					$arr = array($id);
					$res = $database -> query("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=".$id."");
					while($line=mysql_fetch_array($res,MYSQL_NUM)){
						$arr[] = $line[0];
						$arr = array_merge($arr,getChilds($line[0]));
					}
					return $arr;
				}

				$rub_name = getRubricName($rubric_id,1,0,0);
				setTitle("SEO. Рубрика: ".(empty($graduation)?$rub_name:'<a href="'.teGetUrlQuery("showid=3","rubric_id=".$rubric_id,"nograd=".$nograd).'">'.$rub_name.'</a>'));

				$grads = "";
				if(is_array($graduation))
				{
					print 'Градации: ';
					$n = count($graduation);$br='';$i = 1;
					foreach($graduation as $feat => $value)
					{						$grads .= "graduation[$feat]=$value&";
						list($name_feat) = $database->getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".$value);
						print $br;
						if($i<$n)print '<a href="'.teGetUrlQuery("showid=3","rubric_id=".$rubric_id,"nograd=".$nograd,$grads).'">'.$name_feat.'</a> ';
						else print '<b>'.$name_feat.'</b>';
						$br= ' &gt; ';
						$i++;					}
                }
                if(!$nograd)
                {
	                $out = get_grad($rubric_id,$graduation);
	                if(!empty($out))
	                {
	                	print $out;
	                	print '<a href="'.teGetUrlQuery("showid=3","rubric_id=".$rubric_id,"nograd=1",$grads).'">Не выводить градации</a>';
	                	break;
	                }
                }
				$arr = getChilds($rubric_id);
				$arr_buf = array();

				foreach($arr AS $rub) if(empty($arr_buf[$rub])){
					$arr_buf[$rub] = 1;
					if(empty($graduation)){
						$sql = "
							SELECT ".DB_PREFIX."goods.ID_GOOD, ".DB_PREFIX."goods.*
							FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods
							WHERE good_deleted=0 and good_visible=1 and rubricgood_deleted=0 and ".DB_PREFIX."rubric_goods.ID_RUBRIC=$rubric_id
							ORDER BY good_name
						";
					} else {
						$s = "false";
						foreach($graduation AS $i => $feature_id){
							$s .= " or goodfeature_value=".(int)$feature_id;
						}
						$sql = "
							SELECT ".DB_PREFIX."goods.*, count(".DB_PREFIX."goods.ID_GOOD) AS cnt
							FROM
								".DB_PREFIX."goods_features RIGHT JOIN ".DB_PREFIX."goods ON( cprice_goods_features.ID_GOOD = cprice_goods.ID_GOOD )
								INNER JOIN ".DB_PREFIX."rubric_goods ON ( ".DB_PREFIX."rubric_goods.ID_GOOD=cprice_goods.ID_GOOD )
							WHERE ".DB_PREFIX."rubric_goods.ID_RUBRIC=".$rubric_id." and rubricgood_deleted=0 and good_deleted=0 and good_visible=1 and ($s)
							GROUP BY cprice_goods.ID_GOOD
						";
						// die($sql);
					}
					$res=$database->query($sql);


					if(mysql_num_rows($res)>0){

						$i = 0;
						$exist = false;

						while($line=mysql_fetch_array($res,MYSQL_ASSOC)){	/*---*die($line0['cnt'].'='.count($graduation)); /*---*/
							if((@$_GET['action']!='filter' || filter_goods($line0['ID_GOOD'])) && ( empty($line0['cnt']) || @$line0['cnt']==count($graduation) )){
								if($i==0){
									$exist = true;
									$s2 = @$database->getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".(int)@$_GET['feature_id']);
									$s2 = (!empty($s2[0]))?" - ".$s2[0]:"";
									print "<h3>".getRubricName($rub,true,false,false).$s2."</h3>";
								}
								print "<div><a href='".teGetUrlQuery("id=".$line['ID_GOOD'],"rubric_id=$rubric_id",$grads,"showid=$showid","action=edit","nograd=".$nograd)."'>";
								$pqednfres = $database->query("
									SELECT ".DB_PREFIX."features.ID_FEATURE
									FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features
									WHERE ID_RUBRIC=$rubric_id and feature_deleted=0 and rubric_type=$type
									ORDER BY rubricfeature_ls_man DESC, rubricfeature_pos ASC
									LIMIT 3
								");
								$i = 0;
								while($pqednf = mysql_fetch_array($pqednfres)){
									$dat=smallText(strip_tags(getFeatureText($line['ID_GOOD'],$pqednf[0],0,(mysql_num_rows($pqednfres)==1)?1:0)),100);
									if(!empty($dat)){
										if($i==0) print "<b>";
										print /*$pqednf[0].": ".*/$dat;
										if($i==0) print "</b> <small>(";
										$i++;
										if($i<3&&$i>1) print ", ";
									}
								}
								print ")</small></a></div>";
								$i++;
							}
						}
					}
				}


			break;

			case 4:	// не знаю что это, упоминается сайт феникс, а он управляется не здесь...
				$query = "SELECT ID_RUBRIC,rubric_name FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=2";
				$OList = new teList($query,50);
				while($OList->row()){
					$line = $database -> getArrayOfQuery("SELECT metadata_head_title FROM ".DB_PREFIX."metadata WHERE metadata_page=".$showid." and metadata_id=".$OList->getValue("ID_RUBRIC"));
					if($line[0]==""){
						$title = "<div class=disabled>".$OList->getValue("rubric_name")."</div>";
					} else {
						$title = $line[0];
					}
					$rubric_id = $database -> getArrayOfQuery("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$OList->getValue("ID_GOOD"));
					$rubric_id = $rubric_id[0];
					$OList->addUserField("<a href='http://phoenixufa.ru/".teGetUrlQuery("=price","type=1","rubric_id=".$rubric_id,"rubric_id={ID_RUBRIC}")."' target=_blank>".$title."</a>");

					$OList->addUserField("<a href='".teGetUrlQuery("=metadata","action=edit","showid=$showid","id={ID_RUBRIC}")."'>Ред.</a>");
				}
				$OList->addParamTable('');
				echo $OList->getHTML();
				unset($OList);
			break;

			case 5:	// аналогично...
				$query = "SELECT ".DB_PREFIX."goods.ID_GOOD,".DB_PREFIX."goods.good_name FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."rubric WHERE good_deleted=0 and rubric_type=2";
				$OList = new teList($query,50);
				while($OList->row()){
					$line = $database -> getArrayOfQuery("SELECT metadata_head_title FROM ".DB_PREFIX."metadata WHERE metadata_page=".$showid." and metadata_id=".$OList->getValue("ID_GOOD"));
					if($line[0]==""){
						$title = "<div class=disabled>".$OList->getValue("good_name")."</div>";
					} else {
						$title = $line[0];
					}

					$OList->addUserField("<a href='http://phoenixufa.ru/".teGetUrlQuery("=price","type=2","rubric_id={ID_RUBRIC}")."' target=_blank>".$title."</a>");

					$OList->addUserField("<a href='".teGetUrlQuery("=metadata","action=edit","showid=$showid","id={ID_GOOD}")."'>Ред.</a>");
				}
				$OList->addParamTable('');
				echo $OList->getHTML();
				unset($OList);
			break;

			case 7:	// аналогично...
				$query = "SELECT ID_GOOD_NEW,goodnew_title FROM ".DB_PREFIX."goods_news";
				$OList = new teList($query,50);
				while($OList->row()){
					$line = $database -> getArrayOfQuery("SELECT metadata_head_title FROM ".DB_PREFIX."metadata WHERE metadata_page=".$showid." and metadata_id=".$OList->getValue("ID_GOOD_NEW"));
					if($line[0]==""){
						$title = "<div class=disabled>".$OList->getValue("goodnew_title")."</div>";
					} else {
						$title = $line[0];
					}

					$OList->addUserField("<a href='http://phoenixufa.ru/".teGetUrlQuery("=price","type=2","good_id={ID_GOOD_NEW}")."' target=_blank>".$title."</a>");

					$OList->addUserField("<a href='".teGetUrlQuery("=metadata","action=edit","showid=$showid","id={ID_GOOD_NEW}")."'>Ред.</a>");
				}
				$OList->addParamTable('');
				echo $OList->getHTML();
				unset($OList);
			break;

		}
	} else {
		// вывод списка разделов рубрикатора

		setTitle("SEO. Выберите раздел...");

		$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 ORDER BY ID_RUBRIC_TYPE");
		print "<table class=list>";
		while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
			if(@$_USER['rubric_types'][$line['ID_RUBRIC_TYPE']] || $_USER['group']<3){
				print "<tr><td><a href='".teGetUrlQuery("showid=2","type=".$line['ID_RUBRIC_TYPE'],"simpled=1")."'>".$line['rubrictype_name']."</a> "./*buttonEdit(teGetUrlQuery("showid=".($line['ID_RUBRIC_TYPE']*2),"id=0","action=edit")).*/"</td></tr>";
			} else {
				print "<tr><td><font color='red'>".$line['rubrictype_name']." (нет доступа)</font></td></tr>";
			}
		}
		print "</table>";
	}
}
	// понятно из названия

	function get_count_feature($rubric_id, $feature_ids){
		global $database;
		if(!is_array($feature_ids)){
			return false;
		}
		$s = "";$br="";
		foreach($feature_ids AS $i => $feature_id){
			$s .= $br."(ID_FEATURE=$i && goodfeature_value=".$feature_id.")";
			$br = " || ";
		}
		$res = $database->query("
			SELECT ".DB_PREFIX."goods.ID_GOOD
			FROM  ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods
			WHERE ID_RUBRIC=".$rubric_id." and rubricgood_deleted=0 and good_deleted=0 and good_visible=1
		");
		$i = 0;$fcnt = count($feature_ids);
		while($line = mysql_fetch_array($res)){
			list($cnt) = $database -> getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=$line[0] && (".$s.")");
			if($cnt==$fcnt){
				return true;
			}
		}
		return false;
	}

	// возвращает градации рубрики (список в HTML-разметке, в дополнение к древу рубрик)
	function get_grad($rubric_id, $feature_ids=array()){
		if($_GET['simpled']==2) return false;

		global $database;
		global $type;
		$out = "";

		/// левые (дерево) градации

		$level = count($feature_ids)+1;

		list($graduation) = $database -> getArrayOfQuery("SELECT ".DB_PREFIX."features.ID_FEATURE FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and feature_enable=1 and ID_RUBRIC=".$rubric_id." and rubricfeature_graduation=$level and feature_type=4");
		if(empty($graduation)) return false;

		$res = $database->query("
			SELECT * FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$graduation."
		");
		$cnt = 0;
		while( $line1 = mysql_fetch_array($res,MYSQL_ASSOC) ){
			$am = $feature_ids;
			$am[$graduation] = $line1['ID_FEATURE_DIRECTORY'];
			$cnt1 = get_count_feature($rubric_id,$am);

			if($cnt1){
				$out .= "<li>";
				$s = "";
				foreach($am AS $grad => $rid){
					$s .= "graduation[$grad]=$rid&";
				}
				$out .= "<a href='".teGetUrlQuery("showid=3","rubric_id=".$rubric_id,$s)."'>".$line1['featuredirectory_text']." ".buttonEdit(teGetUrlQuery("showid=4","id=".$rubric_id,$s,"action=edit"))."</a>  <a href='".teGetUrlQuery("showid=4","id=".$rubric_id,$s,"action=msword")."'><img src='/files/icon_word.gif' width='15' height='' alt='ms word' /></a>";
				//$out .= get_grad($rubric_id, $am);
				$out .= "</li>";
				$cnt++;
			}
		}
		if(!empty($out))
		{
			$out = "<ul>".$out."</ul>";
		}
		return $out;
	}


	// отдает браузеру дерево рубрик (рекурсия)
	function getChild($rubric_id=0,$left = 0,$max_level=1){
		global $database;
		global $type;
		global $_USER;
           $out = '';
		$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$rubric_id." ORDER BY rubric_pos,rubric_name");
		while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
			//// если сущностей больше 0
			//if(getCountGoods($line['ID_RUBRIC'],true)>0){
				$out .= "<ul>";
				$out .= "<li>";
				$out .= ( ($left==0)?"<h3>":"" );
				if(checkAccess($line['ID_RUBRIC']) || $_USER['rubric_types'][999999999]==2){
					$elink = teGetUrlQuery("showid=2","id=".$line['ID_RUBRIC'],"action=edit");

					list($cnt) = $database->getArrayOfQuery("
						SELECT count(ID_RUBRIC) FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$line['ID_RUBRIC']
					);
					if($cnt>0){
						$mlink = $elink;
					} else {
						$mlink = teGetUrlQuery("showid=3","rubric_id=".$line['ID_RUBRIC']);
					}
					//if($cnt>0) $cnt = "(".$cnt.")"; else
					$cnt = "";
					$out .= "<a href='".$mlink."'>".$line['rubric_name']." $cnt</a> ".buttonEdit($elink)." <a href='".teGetUrlQuery("showid=2","id=".$line['ID_RUBRIC'],"action=msword")."'><img src='/files/icon_word.gif' width='15' height='' alt='ms word' /></a>";
				} else {
					$out .= "<strike title='нет доступа'>".$line['rubric_name']."</strike>";
				}
				$out .= ( ($left==0)?"</h3>":"" );
				$out .= getChild($line['ID_RUBRIC'],$left+1);
				$out .= "</li>";
				$out .= "</ul>";
			//}
		}
		return $out;
	}

?>