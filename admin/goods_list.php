<?
/******************************************
**	Листинг товаров группы
******************************************/
$uid = $_SESSION['user_id'];
global $page_arr;
 $page_arr['pg']='goods';
	// сортировка по выбранному полю
	$ordertype = $orderby = "";
	$configs = teGetConfs("u".$uid."r".$rubric_id."%");
	@$orderby = $configs["u".$uid."r".$rubric_id."fsort"];
	if((!empty($_GET['orderby']) && !empty($_GET['ordertype'])) || $orderby ){		$sort = 1;
		if(!empty($_GET['orderby']) && !empty($_GET['ordertype'])){
			$orderby = (int)$_GET['orderby'];
			$ordertype = $_GET['ordertype'];
			if($ordertype=="asc") $ordertype = "ASC"; else $ordertype = "DESC";
		} else {
			$orderby = (int)$orderby;
			@$ordertype = $configs["u".$uid."r".$rubric_id."f".$orderby."sort"];
			$sort = $ordertype;
			if($ordertype==1) $ordertype = "ASC"; else $ordertype = "DESC";
		}
        if($sort>0)
		{		$otype = getFeatureType($orderby);
		if($otype==8){
			$goodssql = "
				SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*, SUBSTRING(ord.goodfeature_value,7,4) AS ordbyY,SUBSTRING(ord.goodfeature_value,4,2) AS ordbyM,SUBSTRING(ord.goodfeature_value,1,2) AS ordbyD
				FROM
					".DB_PREFIX."goods NATURAL JOIN
					".DB_PREFIX."rubric_goods LEFT JOIN
					cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD and ord.ID_FEATURE=$orderby)
				WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
				ORDER BY ordbyY $ordertype, ordbyM $ordertype, ordbyD $ordertype, ".DB_PREFIX."rubric_goods.rubricgood_pos
			";
		} elseif($otype!=4){
			$goodssql = "
				SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*, ord.goodfeature_value AS ordby
				FROM
					".DB_PREFIX."goods NATURAL JOIN
					".DB_PREFIX."rubric_goods LEFT JOIN
					cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD and ord.ID_FEATURE=$orderby)
				WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
				ORDER BY ordby $ordertype, ".DB_PREFIX."rubric_goods.rubricgood_pos
			";
		} else {
			$goodssql = "
				SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*, fd.featuredirectory_text AS ordby
				FROM
					".DB_PREFIX."goods NATURAL JOIN
					".DB_PREFIX."rubric_goods
					LEFT JOIN cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD and ord.ID_FEATURE=$orderby)
					LEFT JOIN cprice_feature_directory AS fd ON (ord.goodfeature_value=fd.ID_FEATURE_DIRECTORY and fd.ID_FEATURE=$orderby)
				WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
				ORDER BY ordby $ordertype, ".DB_PREFIX."rubric_goods.rubricgood_pos
			";
		}
        }else $orderby=0;
	}
	if(@$asc=$configs["u".$uid."r".$rubric_id."fIDsort"] || (@$_GET['orderby']=="id")){
		if(@$_GET['ordertype']=='asc') $asc = 1; else $asc = 2;

		$goodssql = "
			SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*
			FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods
			WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
			ORDER BY ".DB_PREFIX."goods.ID_GOOD ".(($asc==1)?"ASC":"DESC")."
		";

	}

	// берем данные о рубрике, товары которой показывать
	$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$showid,MYSQL_ASSOC);




	addSubMenuUp(teGetUrlQuery("=rubric","type=$type","rubric_id=".$line['rubric_parent'],"showid=".$line['rubric_parent']));

	//if($type==teGetConf("rtpl_type")){
		//if($hosts[DB_ID]['siteversion']==5){
		//	list($url) = $database -> getArrayOfQuery("SELECT rubric_textid FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
		//	addSubMenu($hosts[DB_ID]['url'].$url."/' target='blank","<img src='{$skinpath}images/b_www_big.ico' alt='на сайт' title='на сайт'/>","submenustd");
		//}
		//if($hosts[DB_ID]['siteversion']==4){
		//	list($url) = $database -> getArrayOfQuery("SELECT rubric_textid FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
		//	addSubMenu($hosts[DB_ID]['url']."?p=".$url."' target='blank","<img src='{$skinpath}images/b_www_big.ico' alt='на сайт' title='на сайт'/>","submenustd");
		//}
		//if($hosts[DB_ID]['siteversion']==3){
		//	list($url) = $database -> getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
		//	$url = filename2(translit2($url));
		//	addSubMenu($hosts[DB_ID]['url']."?p=".$url."' target='blank","<img src='{$skinpath}images/b_www_big.ico' alt='на сайт' title='на сайт'/>","submenustd");
		//}
		//if($hosts[DB_ID]['siteversion']==2){
		//	addSubMenu($hosts[DB_ID]['url']."?page=".$rubric_id."' target='blank","<img src='{$skinpath}images/b_www_big.ico' alt='на сайт' title='на сайт'/>","submenustd");
		//}
	//}
	addSubMenu(teGetUrlQuery("=features")."&from=menu", "<img src='".DEEN_FOLDERS_URL."assets/images/btn-fields.png' alt='' title='Характеристики' />", "submenustd");
	addSubMenu(teGetUrlQuery("action=list_settings")."&from=menu", "<img src='".DEEN_FOLDERS_URL."assets/images/btn-list.png' alt='' title='Настройки списка'/>", "submenustd");
	//addSubMenu(teGetUrlQuery("download=xsl"), "<img src='{$skinpath}images/excel.ico' alt='' title='Генерация Excel для печати'/>", "submenustd");
	//addSubMenuOrder(teGetUrlQuery("id=0","action=order","rubric_id=$rubric_id"));
	if(@$acc['a']) addSubMenuAdd(teGetUrlQuery("id=0","action=add","rubric[$rubric_id]=on"));


	//print "<a href='".teGetUrlQuery("=rubric","type=$type")."'>перейти к списку рубрик ".$rtype['rubrictype_r_m']."</a>";

	// заголовок
	setTitle("<span style='text-transform:capitalize'>".$rtype['rubrictype_i_m']."</span> рубрики ".getRubricName($rubric_id));
		// вывод товаров
		$num_feats = 0;

		@$count = (int)$configs["u".$uid."r".$rubric_id."CntOnPg"];
		if(!$count) $count = 20;


		@$vislist = $configs["u".$uid."r".$rubric_id."vislist"];
		$vislist1 = explode(";",$vislist);
		$vislist = array();
		$private_visible_features = "";
		foreach($vislist1 AS $i => $vislisti){
			if($vislisti) $private_visible_features .= "ID_FEATURE=$vislisti or ";
		}


		if(@$_GET['download']=="xsl"){
			require "goods_list_print.php";
		}

		$OList = new teList($goodssql,$count);

		$class = (@$ordertype=="ASC")?"desc":"asc";
		$OList->addToHead("<a href='".teGetUrlQuery("orderby=id","ordertype=".((@$ordertype=="ASC")?"desc":"asc"))."' class='o'>ID</a>","class='$class'");

		@$firstchangeshow = $configs["u".$uid."r".$rubric_id."fCRTshow"];
		if($firstchangeshow) $OList->addToHead("Дата создания","");

		@$lastchangeshow = $configs["u".$uid."r".$rubric_id."fLSTshow"];
		if($lastchangeshow) $OList->addToHead("Дата последнего изменения","");

		
		$OList->addToHead("Действия","colspan=7 width=1%");
		//$OList->addToHead("Название","");

		$n_feat = 0;
		if(!empty($private_visible_features))
		{
			$res1 = $database->query("SELECT ".DB_PREFIX."features.feature_text, ".DB_PREFIX."features.ID_FEATURE AS ID_FEATURE, ".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE $private_visible_features false GROUP BY ID_FEATURE ORDER BY rubricfeature_pos");
			$n_feat = mysql_num_rows($res1);
		}
		if($n_feat==0){
			$res1 = $database->query("SELECT ".DB_PREFIX."features.feature_text, ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$rubric_id." and rubric_type=".$type." and rubricfeature_ls_man=1 GROUP BY ID_FEATURE ORDER BY rubricfeature_pos");
			if(mysql_num_rows($res1)==0){
				$res1 = $database->query("SELECT ".DB_PREFIX."features.feature_text, ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$rubric_id." and rubric_type=".$type." GROUP BY ID_FEATURE ORDER BY rubricfeature_pos LIMIT 3");
			}
		}
        $arr_feats = array(); $sql_feat = ''; $br = '';
		while($line=mysql_fetch_array($res1)){
			if( $line[2]==1 || $line[2]==2 || $line[2]==3 || $line[2]==4 || $line[2]==8 ){
				$class = (@$ordertype=="ASC")?"desc":"asc";
				$OList->addToHead("<a href='".teGetUrlQuery("orderby=$line[1]","ordertype=".((@$ordertype=="ASC" && @$orderby==$line[1])?"desc":"asc"))."' class='o'>".$line[0]."</a>","class='".(($orderby==$line[1])?"orderby":"")." ".$class."'");
			} else {
				$OList->addToHead("".$line[0]);
			}
			$arr_feats[$line[1]] = $line[2];
			$sql_feat .= $br."ID_FEATURE=".$line[1];
			$br = " || ";
			$num_feats++;
		}
		$OList->addToHead("<small><small>заполненные хар-ки</small></small>","width=1%");



		$i=0;
		while($OList->row()){
			if( $OList->getValue("good_visible")==0 ){
				$s = "class='disabled'";
			} else {
				$s = "";
			}

			$id_good = $OList->getValue("ID_GOOD");
			$arr_val = array();
			if($sql_feat=="")$sql_feat=0;
			$res_val = $database->query("SELECT ID_FEATURE, goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id_good." and ($sql_feat) group by ID_FEATURE");
			while($row_val = mysql_fetch_row($res_val))$arr_val[$row_val[0]]=$row_val[1];
			$arr_vals = array();
			$i_feats = 0;
			$name = '';
			foreach($arr_feats as $fid => $feature_type){
				@$answertext = $arr_val[$fid];
				if(empty($answertext)){
					$answertext = "";
				}
				switch($feature_type){
					case 7:
						if($answertext!="" && is_numeric($answertext)){
							$answertext = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT=".$answertext);
							$answertext = $answertext[0];
						}
					break;
					case 3:
						$answertext = ($answertext!="")?"да":"нет";
					break;
					case 4:
						if(!empty($answertext) && $answertext!="-"){
							$answertext = getFeatureText($id_good, $fid);
							// $line1 = $database -> getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".$answertext);
							// $answertext = $line1[0];
						}
					break;
					case 10:
					case 5:
						if(!empty($answertext) && $answertext!="-"){
							list($val) = $database->getArrayOfQuery("
								SELECT goodfeature_value
								FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features
								WHERE ID_GOOD='$answertext' and feature_deleted=0 and feature_enable=1 and (feature_type=2)
								LIMIT 1
							");
							$answertext = getIdToPrint("goods",$answertext).": ".$val;
						}
					break;
					case 9:
						if(!empty($answertext) && $answertext!="-"){
							$answertext = getIdToPrint("rubric",(int)$answertext).": ".getRubricName($answertext,false,true,true);
						}
					break;
				}
				$arr_vals[$fid] = array($feature_type,$answertext);
				if(empty($name) && !empty($answertext)) $name = str_replace("\"","\\\"",strip_tags($answertext));
				$i_feats++;
			}

                         $name = smallText($name,50);
			if( $OList->getValue("good_visible")==1 ){
			 $name.= '<span style="color:#ccc">'.$name.'</span>';	
			}else {
				
			}
			


			$OList->addUserField($id_good);
			if($firstchangeshow) $OList->addUserField(dateOfChange("goods",$id_good,"ASC"));
			if($lastchangeshow)  $OList->addUserField(dateOfChange("goods",$id_good));
			/// действия
			$cnt = $database->getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0 and ID_GOOD=".$id_good);
			$cnt = $cnt[0];
			if(!empty($type)){
				// ссылка на заполнение SEO

				$url = teGetUrlQuery("=metadata","showid=3","action=edit","id={ID_GOOD}","&simpled=1");
				$url = str_replace ("&showid=0", "", $url);
				$url = str_replace ("&typeview=tree", "", $url);
				$url = str_replace ("&idshow=id", "", $url);
				$OList->addUserField("<a href='".$url."'>SEO</a>");

				if( ($cnt>0&&!@$acc['e']) || @$acc['e'] ){
					$OList->addUserField("<a href='".teGetUrlQuery("action=photos","good_id={ID_GOOD}")."' title='Управление фотографиями ".$rtype['rubrictype_r_s']."'><nobr>фото($cnt)</nobr></a>");
				} else {
					$OList->addUserField("");
				}
				$cnt = "";
				//list($cnt) = $database->getArrayOfQuery("SELECT count(*) FROM cprice_rubric_goods WHERE ID_GOOD=".$id_good);
				//if(!empty($cnt)) $cnt = " (".$cnt.")";
				if(@$acc['e']) $OList->addUserField("<a href='".teGetUrlQuery("action=rubric","id={ID_GOOD}","typerubric=edit")."' title='Редактировать список рубрик, в которых состоит ".$rtype['rubrictype_i_s']."'>рубрики$cnt</a>");
				else $OList->addUserField("");
			}else {$OList->addUserField("");$OList->addUserField("");}
			if(true||@$acc['e']){
				$OList->addUserField(buttonEdit(teGetUrlQuery("action=edit","id={ID_GOOD}")));
				if( $OList->getValue("good_visible")==1 ){
					$OList->addUserField(buttonDisable(teGetUrlQuery("action=disable","id={ID_GOOD}")));
				} else {
					$OList->addUserField(buttonEnable(teGetUrlQuery("action=enable","id={ID_GOOD}")));
				}
			}else {$OList->addUserField("");$OList->addUserField("");}
			if(true||@$acc['d']){
				if(!empty($type)){
					/// удаление из категории
					// проверка, есть ли товар в дру.рубриках (для защиты от удаления отовсюду)
					if( $database -> getArrayOfQuery("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$id_good." and ID_RUBRIC<>".$rubric_id) ){
						//$js = "";
						$OList->addUserField("<nobr><a class=del href='javascript: if(confirm(\"Удалить ".$rtype['rubrictype_t_s']."  из текущей категории?\")) location.href =\"".teGetUrlQuery("action=deletefromrubric","id={ID_GOOD}")."\"' title='Удалить из рубрики'>удал. из рубрики</a></nobr>");
					} else {
						//$js = "if(confirm(\"".$rtype['rubrictype_i_s']." «".$name."» состоит только в этой рубрике. Вы можете добавить ".$rtype['rubrictype_t_s']." в другую рубрику. Удалить без возможности восстановления?\"))";
						$OList->addUserField("");
					}
					//$OList->addUserField("<nobr><a class=del href='javascript: if(confirm(\"Удалить ".$rtype['rubrictype_t_s']." «".$name."» из текущей категории?\")) $js location.href =\"".teGetUrlQuery("action=deletefromrubric","id={ID_GOOD}")."\"' title='Удалить ".$rtype['rubrictype_t_s']." «".$name."» из текущей рубрики'>удал. из рубрики</a></nobr>");
				}
				// удаление
				$OList->addUserField(buttonDelete("javascript: if(confirm(\"Удалить?\")) location.href =\"".teGetUrlQuery("action=delete","id={ID_GOOD}")."\"", "Удалить ".$rtype['rubrictype_t_s'].""));
			}else {$OList->addUserField(""); $OList->addUserField("");}
			$link = teGetUrlQuery("action=view","id={ID_GOOD}");
			
			//$OList->addUserField($OList->getValue("good_name"));
			//if($firstchangeshow) $OList->addUserField(dateOfChange("goods",$id_good,"ASC"));
			//if($lastchangeshow)  $OList->addUserField(dateOfChange("goods",$id_good));
			foreach($arr_vals as $f_id => $item)
			{
				$feature_type = $item[0];
				$answertext = $item[1];
				if($feature_type!=5 && $feature_type!=9){
					$OList->addUserField("<a href='".$link."'>".smallText(strip_tags($answertext),150)."</a>",$s.(($orderby==$f_id)?" class='orderby'":""));
				} else {
					$OList->addUserField($answertext,$s.(($orderby==$f_id)?" class='orderby'":""));
				}
			}

			while($i_feats<$num_feats){
				$OList->addUserField("");
				$i_feats++;
			}

			// кол-во заполненных хар-к
			$goodfeatcnt = $database->getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id_good." and (goodfeature_value>0 or goodfeature_value>'')");
			$OList->addUserField("<center>".$goodfeatcnt[0]."</center>");

			$i++;
		}
		$OList->classHTML = "table table-bordered table-hover dataTable";
		$OList->addParamTable('');
		echo($OList->getHTML());
		unset($OList);

	//print "<div><a href='javascript: if(var str=prompt(\"Сколько записей нужно выводить на странице?\",\"30\")) location.href=\"".teGetUrlQuery("action=countinpage","value=")."\"+str'></a></div>";

	// ссылка "добавить"
	if(true /*@$acc['a']*/){
		print "<div class='add add-good-list' style='text-align:center;'>";
		print "<a class='btn btn-success' href='".teGetUrlQuery("pg=goods","id=0","action=add","rubric[$rubric_id]=on")."'>Добавить ".$rtype['rubrictype_i_s']." в рубрику</a>";
		print "</div>";
	}
	if(@$acc['a']||@$acc['e']){
		print "<div class='dop-func'><h4>Дополнительные функции</h4><ul>";
		if(@$acc['a']&&@$acc['e']){ print "<li><a href='".teGetUrlQuery("action=add")."' title='Сначала Вам предстоит выбрать рубрики, потом добавить информацию о товаре'>Добавить ".$rtype['rubrictype_t_s']." в несколько рубрик</a></li>";
			print "<li><a href='".teGetUrlQuery("=features","action=copy_features")."'>Копировать характеристики из одной рубрики в другую.</a></li>";
		}
		if($i>1){
			if(@$acc['e']) print "<li><a href='".teGetUrlQuery("action=features_data")."'>заполнить определенные характеристики всех ".$rtype['rubrictype_r_m']."</a></li>";
			if(@$acc['e']) print "<li><a href='".teGetUrlQuery("action=pos")."'>сортировать ".$rtype['rubrictype_t_m']." вручную</a></li>";
			if(@$acc['a']) print "<li><a href='".teGetUrlQuery("=goods-multiple","type=$type","rubric_id=$rubric_id")."'>Размножить записи</a></li>";
		}
		if($i>0){
			if(@$acc['e']) print "<li><a href='".teGetUrlQuery("action=copy")."'>Управление группами записей</a></li>";
		}
		print "</ul></div>";
	}
?>