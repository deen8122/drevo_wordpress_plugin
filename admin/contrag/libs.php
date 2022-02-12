<?
//Дополнительные функции
function cmp1($a, $b)
{
	if($a[1] == $b[1]) return ($a[2] > $b[2]) ? 1 : -1;
	return ($a[1] > $b[1]) ? 1 : -1;
}
function groups($id,$link='')
{	
	global $database,$main_org;	

	if(isset($_POST['checkvar1']))
	{
		if( is_array(@$_POST['rubricanswers']) ){
			foreach( $_POST['rubricanswers'] AS $i => $value ){
				if( (trim($value))!="" && !$database -> getArrayOfQuery("SELECT ID_FEATURE FROM ".DB_PREFIX."feature_directory WHERE ".($main_org>0?"main_org=$main_org && ":"")."ID_FEATURE=".$id." and featuredirectory_text='".$value."'") ){
					$database -> query("INSERT INTO ".DB_PREFIX."feature_directory (ID_FEATURE,featuredirectory_text".($main_org>0?",main_org":"").") VALUES ($id,'".str_replace("'","\'",$value)."'".($main_org>0?",".$main_org:"").")");
				}
			}
			die();
			if(!empty($_POST['rubricanswerso']))
			foreach( $_POST['rubricanswerso'] AS $i => $value ){
				if( (trim($value))!="" ){
					if( $line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."feature_directory WHERE ".($main_org>0?"main_org=$main_org && ":"")."ID_FEATURE=".$id." and ID_FEATURE_DIRECTORY=".$i."",MYSQL_ASSOC) ){
						if( @$line['faeturedirectory_text'] != $value){
							$database -> query("UPDATE ".DB_PREFIX."feature_directory SET featuredirectory_text='".$value."' WHERE ID_FEATURE_DIRECTORY=".$i);
						}
					}
				} else {
					$database -> query("DELETE FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".$i);
				}
			}
               print '<span style="color:blue;">Изменения внесены</span>';
			if($_POST['checkvar1']==1){
				teRedirect(teGetUrlQuery("op1=groups","id=".$id,"msg=1"));
			} else {
				unset($_POST['checkvar1']);
			}
		} else {
			unset($_POST['checkvar1']);
		}
	}

	print("<div class='note'>Для удаления значения просто оставьте поле пустым</div>");


	print "</div><div><table align='center'><tr><td>";
	print "<form method='post' name='mng' id='mng'>";
	print "<input type=hidden name='checkvar1' value='1' />";
	// добавляем JS скрипт добавления новых полей
	teAddJsScript("
		function add_field(){
			if(updt){
				document.getElementById('mng').submit();
			}else{
				ifields++;
				var rubricanswers=document.getElementById(\"rubricanswers\");
				rubricanswers.innerHTML+=
				\"<div>\"+ifields+\": \"+
				\"<input onkeydown='updt=true;' type='text' size='50' name='rubricanswers[]' /></div>\";
			}
		}
	");
	$i = 0;
	$res = $database -> query("SELECT * FROM ".DB_PREFIX."feature_directory WHERE ".($main_org>0?"main_org=$main_org && ":"")."ID_FEATURE=".$id." ORDER BY featuredirectory_text");
	while( $line = mysql_fetch_array($res,MYSQL_ASSOC) ){
		$i++;
		echo "<div>".$i.": <input type='text' size='50' name='rubricanswerso[".$line['ID_FEATURE_DIRECTORY']."]' value=\"".str_replace("\"","&quot;",$line['featuredirectory_text'])."\" /> (".getIdToPrint("feature_directory",$line['ID_FEATURE_DIRECTORY']).")</div>";
	}
	// счетчик полей
	teAddJsScript("var ifields=".$i.";var updt=false;");

	print "<div id=\"rubricanswers\"></div>";
	print "<div align=right><input type=button value='добавить ещё' onClick='this.form.checkvar1.value=2;add_field();' />".teGetJsScript("add_field();add_field();")."</div>";

	print "<div align='center'><input type=submit value='Сохранить'></div>";
	print "</form>";
	print "</tr></td></table></div><div>";
}

function goods_list($rubric_id,$actions=array(),$op='',$goods=array(),$add_tbl='',$add_sql='',$ffiltr=0,$type = 8)
{
	global $database,$skinpath,$main_org;
	@$id = (int)$_GET['id'];
    @$fid = (int)$_GET['fid'];
    if($id>0 && $fid>0) $add_tbl .='inner join cprice_goods_features tx on (tx.ID_GOOD=cprice_goods.ID_GOOD && tx.ID_FEATURE='.$fid.' && tx.goodfeature_value='.$id.')';
$uid = $_SESSION['user_id'];

$goodssql = "
	SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*
	FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods $add_tbl
	WHERE ".($main_org>0?"main_org=$main_org && ":"")."good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id." $add_sql
	ORDER BY ".DB_PREFIX."rubric_goods.rubricgood_pos
";
	// сортировка по выбранному полю
	$ordertype = $orderby = "";
	$configs = teGetConfs("u".$uid."r".$rubric_id."%");
	@$orderby = $configs["u".$uid."r".$rubric_id."fsort"];
	if((!empty($_GET['orderby']) && !empty($_GET['ordertype'])) || $orderby ){
		$sort = 1;
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
		{
		$otype = getFeatureType($orderby);
		if($otype==8){
			$goodssql = "
				SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*, SUBSTRING(ord.goodfeature_value,7,4) AS ordbyY,SUBSTRING(ord.goodfeature_value,4,2) AS ordbyM,SUBSTRING(ord.goodfeature_value,1,2) AS ordbyD
				FROM
					".DB_PREFIX."goods NATURAL JOIN
					".DB_PREFIX."rubric_goods LEFT JOIN
					cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD and ord.ID_FEATURE=$orderby) $add_tbl
				WHERE ".($main_org>0?"main_org=$main_org && ":"")."good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
				ORDER BY ordbyY $ordertype, ordbyM $ordertype, ordbyD $ordertype, ".DB_PREFIX."rubric_goods.rubricgood_pos
			";
		} elseif($otype!=4){
			$goodssql = "
				SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*, ord.goodfeature_value AS ordby
				FROM
					".DB_PREFIX."goods NATURAL JOIN
					".DB_PREFIX."rubric_goods LEFT JOIN
					cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD and ord.ID_FEATURE=$orderby) $add_tbl
				WHERE ".($main_org>0?"main_org=$main_org && ":"")."good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
				ORDER BY ordby $ordertype, ".DB_PREFIX."rubric_goods.rubricgood_pos
			";
		} else {
			$goodssql = "
				SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*, fd.featuredirectory_text AS ordby
				FROM
					".DB_PREFIX."goods NATURAL JOIN
					".DB_PREFIX."rubric_goods
					LEFT JOIN cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD and ord.ID_FEATURE=$orderby) $add_tbl
					LEFT JOIN cprice_feature_directory AS fd ON (ord.goodfeature_value=fd.ID_FEATURE_DIRECTORY and fd.ID_FEATURE=$orderby)
				WHERE ".($main_org>0?"main_org=$main_org && ":"")."good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
				ORDER BY ordby $ordertype, ".DB_PREFIX."rubric_goods.rubricgood_pos
			";
		}
        }else $orderby=0;
	}
	if(@$asc=$configs["u".$uid."r".$rubric_id."fIDsort"] || (@$_GET['orderby']=="id")){
		if(@$_GET['ordertype']=='asc') $asc = 1; else $asc = 2;

		$goodssql = "
			SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*
			FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods $add_tbl
			WHERE ".($main_org>0?"main_org=$main_org && ":"")."good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
			ORDER BY ".DB_PREFIX."goods.ID_GOOD ".(($asc==1)?"ASC":"DESC")."
		";
	}

	addSubMenu(teGetUrlQuery("pg=goods","type=".$type,"rubric_id=".$rubric_id,"contrag=1","action=list_settings")."&from=menu", "<img src='{$skinpath}images/b_lists_big.png' alt='' title='Настройки списка'/>", "submenustd");


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


		$OList = new teList($goodssql,$count);

		$class = (@$ordertype=="ASC")?"desc":"asc";
		$OList->addToHead("<a href='".teGetUrlQuery("orderby=id","ordertype=".((@$ordertype=="ASC")?"desc":"asc"))."' class='o'>ID</a>","class='$class'");

		@$firstchangeshow = $configs["u".$uid."r".$rubric_id."fCRTshow"];
		if($firstchangeshow) $OList->addToHead("Дата создания","");

		@$lastchangeshow = $configs["u".$uid."r".$rubric_id."fLSTshow"];
		if($lastchangeshow) $OList->addToHead("Дата последнего изменения","");

		$OList->addToHead("Действия","width='1%' colspan='".count($actions)."'");

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
		while($line=mysql_fetch_array($res1)){
			if( $line[2]==1 || $line[2]==2 || $line[2]==3 || $line[2]==4 || $line[2]==8 ){
				$class = (@$ordertype=="ASC")?"desc":"asc";
				$OList->addToHead('<a href="'.teGetUrlQuery("orderby=$line[1]","ordertype=".((@$ordertype=="ASC" && @$orderby==$line[1])?"desc":"asc")).'" class="o">'.$line[0].'</a>',"class='".(($orderby==$line[1])?"orderby":"")." ".$class."'");
			} else {
				$txt = $line[0];
				if($line[2]==5 && $ffiltr==$line[1])
				{
					$form=' <a href="#" onClick="document.getElementById(\'fsel\').style.display=\'\';">фильтр</a>
					<form action="" method="get">
						<input type="hidden" name="pg" value="contrag" />
						<input type="hidden" name="op1" value="'.@$_GET['op1'].'" />
						<input type="hidden" name="fid" value="'.$line[1].'" /><select name="id" onchange="this.form.submit();" id="fsel" style="display: none;"><option value="0"></option>';
					$line_r = $database->getArrayOfQuery("select ID_RUBRIC from cprice_feature_rubric where ID_FEATURE=".$line[1]);
					$main_org2 = $main_org;
					if($main_org==13920 || $main_org==34892 || $main_org==37103)$main_org2=9254;
					$res_goods=$database -> query("select ID_GOOD,goodfeature_value from cprice_rubric_goods
						natural join cprice_goods
						natural join cprice_goods_features
						where ".($main_org2>0?"main_org=$main_org2 && ":"")."ID_RUBRIC='$line_r[0]' && rubricgood_deleted=0 && good_visible=1 && good_deleted=0 && ID_FEATURE=676
						order by goodfeature_value");
					while($row_goods = mysql_fetch_array($res_goods))
					{
/*						$res=$database -> query("select t1.goodfeature_value from cprice_goods_features t1
							natural join cprice_rubric_features t2
							natural join cprice_features t3
							where t2.ID_RUBRIC='$line_r[0]' && t1.ID_GOOD='".$row_goods['ID_GOOD']."' order by t2.rubricfeature_pos limit 1");
					 	$row = mysql_fetch_array($res);
*/					 	if($id==$row_goods['ID_GOOD'] && $fid==$line[1])$form.='<option value="'.$row_goods['ID_GOOD'].'" selected="selected">'.$row_goods[1].'</option>';
					 	else $form.='<option value="'.$row_goods['ID_GOOD'].'">'.$row_goods[1].'</option>';
					}
					$form.='</select></form>';
					$OList->addToHead($txt.$form);
				}else $OList->addToHead($txt);
			}
			$num_feats++;
		}

		$OList->query();

		$i=0;
		while($OList->row()){
			$id_g = $OList->getValue("ID_GOOD");
			if(count($goods)>0 && !in_array($id_g,$goods))
				continue;


			if( $OList->getValue("good_visible")==0 ){
				$s = "disabled";
			} else {
				$s = "";
			}



			$OList->addUserField(getIdToPrint("goods",$id_g,$rubric_id));
			if($firstchangeshow) $OList->addUserField(dateOfChange("goods",$id_g,"ASC"));
			if($lastchangeshow)  $OList->addUserField(dateOfChange("goods",$id_g));

//            $acts='';
//            foreach($actions as $item)$acts.=$item.' ';
//            $OList->addUserField($acts);
            foreach($actions as $item)
            {
            	if(is_array($item)){
            		//Для журнала счетов (ссылки на акт, если он есть)
            		$id_act = getFeatureValue($id_g, $item[0]);
	        		if($id_act>0)$item = str_replace("{ID}",$id_act,$item[2]);
	        		else $item = $item[1];
	        	}
	        	$OList->addUserField($item);
	        }

			$i_feats = 0;
			mysql_data_seek($res1,0);
			while($line=mysql_fetch_array($res1)){
				$line[0] = $line[1];
				$answertext = $database->getArrayOfQuery("SELECT ".DB_PREFIX."goods_features.goodfeature_value,".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features WHERE ID_GOOD=".$id_g." and ".DB_PREFIX."features.ID_FEATURE=".$line[0]);
				$feature_type = $answertext[1];
				$answertext = $answertext[0];
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
						$answertext = ($answertext!="")?'<img id="img'.$id_g.'" src="/engine/data/system_skin/images/b_online.gif" alt="да" />':'<img id="img'.$id_g.'" src="/engine/data/system_skin/images/b_offline.gif" alt="нет" />';
//						$answertext = ($answertext!="")?"да":"нет";
					break;
					case 4:
						if(!empty($answertext) && $answertext!="-"){
							$answertext = getFeatureText($id_g, $line[0]);
							// $line1 = $database -> getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".$answertext);
							// $answertext = $line1[0];
						}
					break;
					case 5:
						if(!empty($answertext) && $answertext!="-"){
							list($val) = $database->getArrayOfQuery("
								SELECT goodfeature_value
								FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features
								WHERE ID_GOOD='$answertext' and feature_deleted=0 and feature_enable=1 and (feature_type=2)
								LIMIT 1
							");
							$answertext = $val;
						}
					break;
					case 9:
						if(!empty($answertext) && $answertext!="-"){
							$answertext = getIdToPrint("rubric",(int)$answertext).": ".getRubricName($answertext,false,true,true);
						}
					break;
				}
				if($feature_type!=5 && $feature_type!=9){					if($line[0]==726) $OList->addUserField('<a onclick="return enable(this.href);" href="'.teGetUrlQuery("op1=proved","id={ID_GOOD}").'">'.smallText($answertext,150).'</a>',$s.(($orderby==$line[0])?" class='orderby'":""));
					else $OList->addUserField('<a onclick="return hs.htmlExpand(this, { objectType: \'iframe\', width:700 } )" href="'.teGetUrlQuery((!empty($op)?"op1=".$op:"op1=view&amp;rub=".$rubric_id),"id={ID_GOOD}").'">'.smallText($answertext,150).'</a>',$s.(($orderby==$line[0])?" class='orderby'":""));
				} else {
					$OList->addUserField($answertext,$s.(($orderby==$line[0])?" class='orderby'":""));
				}
				if($i_feats==0) $name = str_replace("'","\'",$answertext);
				$i_feats++;
			}
			while($i_feats<$num_feats){
				$OList->addUserField("");
				$i_feats++;
			}
			$i++;
		}
		$OList->addParamTable('');
		echo($OList->getHTML());
		unset($OList);
}
function get_var($varname) {
	global $_GET, $_POST;

    if (isset($_POST[$varname]))
            return $_POST[$varname];

    if (isset($_GET[$varname]))
            return $_GET[$varname];

    return NULL;
}
function str_date($tdate) {
	$arr = explode(".",$tdate);
	$months = array(1=>'января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря');
    return intval($arr[0]).' '.$months[intval($arr[1])].' '.$arr[2].' г.';
}
function get_name_sum($sum,$kop=false)
{
	$name_sum='';
	$kop_str = '';
	if($kop)
	{
		$karr = explode(".",strval($sum));
		if(isset($karr[1]))
		{
			if(empty($karr[1]))$kop_str = ' 00';
			else
			{
				if(strlen($karr[1])==1) $kop_str = ' '.$karr[1].'0';
				else $kop_str = ' '.substr($karr[1],0,2);
			}
		}else $kop_str = ' 00';
	}
	$sum_1=strval(intval($sum));
    $len=strlen($sum_1);
    setlocale (LC_ALL, array ('ru_RU.CP1251', 'rus_RUS.1251'));
   	switch($len)
    {
    	case 3:return ucfirst(name_3($sum_1[0])).' '.name_2($sum_1[1]).' '.name_1($sum_1[2],$sum_1[1],array('рубль','рубля','рублей'),2).$kop_str;
    	case 4:return ucfirst(name_1($sum_1[0],0,array('тысяча','тысячи','тысяч'))).' '.name_3($sum_1[1]).' '.name_2($sum_1[2]).' '.name_1($sum_1[3],$sum_1[2],array('рубль','рубля','рублей'),2).$kop_str;
    	case 5:return ucfirst(name_2($sum_1[0])).' '.name_1($sum_1[1],$sum_1[0],array('тысяча','тысячи','тысяч')).' '.name_3($sum_1[2]).' '.name_2($sum_1[3]).' '.name_1($sum_1[4],$sum_1[3],array('рубль','рубля','рублей'),2).$kop_str;
    	case 6:return ucfirst(name_3($sum_1[0])).' '.name_2($sum_1[1]).' '.name_1($sum_1[2],$sum_1[1],array('тысяча','тысячи','тысяч')).' '.name_3($sum_1[3]).' '.name_2($sum_1[4]).' '.name_1($sum_1[5],$sum_1[4],array('рубль','рубля','рублей'),2).$kop_str;
    	case 7:return ucfirst(name_1($sum_1[0],0,array('миллион','миллиона','миллионов'),2)).' '.name_3($sum_1[1]).' '.name_2($sum_1[2]).' '.name_1($sum_1[3],$sum_1[2],array('тысяча','тысячи','тысяч')).' '.name_3($sum_1[4]).' '.name_2($sum_1[5]).' '.name_1($sum_1[6],$sum_1[5],array('рубль','рубля','рублей'),2).$kop_str;
    }
}
//$dig - единицы
//$dig_2 - десятки
//$add_arr массив дополнений к единицам
//Пример array('рубль','рубля','рублей')
//$type: 1,2 - именует цифры, иначе возвращает цифры
function name_1($dig, $dig_2=0,$add_arr,$type=1)
{
    if($dig_2==1)
    {
      if($type)
      {
	    switch($dig)
	    {
	        case 1: return 'одиннадцать '.$add_arr[2];
	        case 2: return 'двенадцать '.$add_arr[2];
	        case 3: return 'тринадцать '.$add_arr[2];
	        case 4: return 'четырнадцать '.$add_arr[2];
	        case 5: return 'пятнадцать '.$add_arr[2];
	        case 6: return 'шестнадцать '.$add_arr[2];
	        case 7: return 'семнадцать '.$add_arr[2];
	        case 8: return 'восемнадцать '.$add_arr[2];
	        case 9: return 'девятнадцать '.$add_arr[2];
	        case 0: return 'десять '.$add_arr[2];
	    }
      }
      else return $dig.' '.$add_arr[2];

    }
    switch($dig)
    {
		case 1:
        	switch($type)
            {
            	case 1: $num='одна '; break;
            	case 2: $num='один '; break;
            	default: $num=$dig.' '; break;
            }
        	return $num.$add_arr[0];
		case 2:
        	switch($type)
            {
            	case 1: $num='две '; break;
            	case 2: $num='два '; break;
            	default: $num=$dig.' '; break;
            }
        	return $num.$add_arr[1];
		case 3: return ((!$type)?$dig:'три').' '.$add_arr[1];
		case 4: return ((!$type)?$dig:'четыре').' '.$add_arr[1];
		case 5: return ((!$type)?$dig:'пять').' '.$add_arr[2];
		case 6: return ((!$type)?$dig:'шесть').' '.$add_arr[2];
		case 7: return ((!$type)?$dig:'семь').' '.$add_arr[2];
		case 8: return ((!$type)?$dig:'восемь').' '.$add_arr[2];
		case 9: return ((!$type)?$dig:'девять').' '.$add_arr[2];
		case 0: return ((!$type)?$dig:'').' '.$add_arr[2];
    }
}
function name_2($dig)
{
	switch($dig)
    {
		case 1: return '';
		case 2: return 'двадцать';
		case 3: return 'тридцать';
		case 4: return 'сорок';
		case 5: return 'пятьдесят';
		case 6: return 'шестьдесят';
		case 7: return 'семьдесят';
		case 8: return 'восемьдесят';
		case 9: return 'девяносто';
		case 0: return '';
    }
}
function name_3($dig)
{
	switch($dig)
    {
		case 1: return 'сто';
		case 2: return 'двести';
		case 3: return 'триста';
		case 4: return 'четыреста';
		case 5: return 'пятьсот';
		case 6: return 'шестьсот';
		case 7: return 'семьсот';
		case 8: return 'восемьсот';
		case 9: return 'девятьсот';
		case 0: return '';
    }
}
function select_goods($old_goods=array(),$main_org)
{
	global $database,$rub_prod,$id_create,$id_ufabs;
	print '<form action="" method="post"><div style="margin-left:140px;">';
	$arr_no = array('sel_good','goods','actn','goods_name','period','kolvo','price');
	foreach($_POST AS $cur_name => $cur_field)
		if(!in_array($cur_name,$arr_no))
		{
			if(is_array($cur_field))print '<input type="hidden" name="'.$cur_name.'[]" value="'.$cur_field[0].'" />';
			else print '<input type="hidden" name="'.$cur_name.'" value="'.$cur_field.'" />';
		}
	$res_goods=$database -> query("select * from cprice_rubric_goods t1
		natural join cprice_goods t2
		where main_org=$main_org && t1.ID_RUBRIC='$rub_prod' && t1.rubricgood_deleted=0 && t2.good_visible=1 && t2.good_deleted=0 order by t1.rubricgood_pos");
	$arr = array();$i=0;
	while($row_goods = mysql_fetch_array($res_goods))
	{
		if(in_array($row_goods['ID_GOOD'],$old_goods))continue;
		$res=$database -> query("select * from cprice_goods_features t1
			natural join cprice_rubric_features t2
			natural join cprice_features t3
			where t2.ID_RUBRIC='$rub_prod' && t1.ID_GOOD='".$row_goods['ID_GOOD']."' order by t2.rubricfeature_pos");
		$value = array();$group='';
	 	while($row = mysql_fetch_array($res))
	 	{
	 		if($row['feature_type']==4 && $row['goodfeature_value']>0)
	 		{
	 			$line = $database->getArrayOfQUery("select featuredirectory_text from cprice_feature_directory where ID_FEATURE_DIRECTORY=".$row['goodfeature_value']);
	 			$value[$row['ID_FEATURE']] = $line[0];
	 			$group = $row['goodfeature_value'];
	 		}
	 		else $value[$row['ID_FEATURE']] = $row['goodfeature_value'];
	 	}
	 	$arr[$i][0] = $row_goods['ID_GOOD'];
	 	$arr[$i][1] = $value[712];
	 	$arr[$i][2] = $value[697];
	 	$arr[$i][3] = $value[699];
	 	$arr[$i][4] = $group;
   		$i++;
	}
	usort($arr,'cmp1');
	@$goods = $_POST['goods'];
	if(!is_array($goods))$goods = array();
	foreach($arr as $item)
	{
		if(!in_array($id_create,$id_ufabs) || $item[4]=='224')
		print '<input type="checkbox" name="goods[]"  value="'.$item[0].'"'.(in_array($item[0],$goods)?' checked="checked"':'').' /> '.$item[1].' &raquo; <b>'.$item[2].'</b> '.($item[3]==1 ? '<i>(периодическая услуга)</i>' :'').'<br/>';
	}
	print '<br/><input type="submit" value="Выбрать"></div></form>';
}
function XMail( $from, $to, $subj, $text, $filename,$dop=true)
{
$farr = explode(".",$filename);
$ext = array_pop($farr);
$f         = fopen($filename,"rb");
$un        = strtoupper(uniqid(time()));
$head      = "From: $from\n";
$head     .= "To: $to\n";
$head     .= "Subject: $subj\n";
$head     .= "X-Mailer: PHPMail Tool\n";
$head     .= "Reply-To: $from\n";
$head     .= "Mime-Version: 1.0\n";
$head     .= "Content-Type:multipart/mixed;";
$head     .= "boundary=\"----------".$un."\"\n\n";
$zag       = "------------".$un."\nContent-Type:text/html;\n";
$zag      .= "Content-Transfer-Encoding: 8bit\n\n$text\n\n";
$zag      .= "------------".$un."\n";
$zag      .= "Content-Type: application/".($ext=='xls'?"vnd.ms-excel":"octet-stream;");
$zag      .= "name=\"".basename($filename)."\"\n";
$zag      .= "Content-Transfer-Encoding:base64\n";
$zag      .= "Content-Disposition:attachment;";
$zag      .= "filename=\"".basename($filename)."\"\n\n";
$zag      .= chunk_split(base64_encode(fread($f,filesize($filename))))."\n";
if($dop){
//дополнительный файл
$filename = '/var/www/cprice/counts/garant_letter.doc';
$f         = fopen($filename,"rb");
$zag      .= "------------".$un."\n";
$zag      .= "Content-Type: application/vnd.ms-word";
$zag      .= "name=\"".basename($filename)."\"\n";
$zag      .= "Content-Transfer-Encoding:base64\n";
$zag      .= "Content-Disposition:attachment;";
$zag      .= "filename=\"".basename($filename)."\"\n\n";
$zag      .= chunk_split(base64_encode(fread($f,filesize($filename))))."\n";
}
if (!@mail("$to", "$subj", $zag, $head))
return 0;
else
return 1;
}
function fun_exc_cnt($id,$dir='',$word=false)
{
	global $database,$rub_rep_cnt,$rub_org,$rub_prod_cnt,$ufapr;
	global $main_org,$main_org_name,$bcount;
	if(!$main_org){$main_org=1;$main_org_name='ООО "УфаПиар.ру"';}
	$res=$database -> query("select * from cprice_goods_features t1
		natural join cprice_rubric_features t2
		natural join cprice_features t3
		where t2.ID_RUBRIC='$rub_rep_cnt' && t1.ID_GOOD='".$id."' order by t2.rubricfeature_pos");
	$value = array();
 	while($row = mysql_fetch_array($res))
 	{
			$value[$row['ID_FEATURE']] = $row['goodfeature_value'];
 	}
	$res1 = $database -> query("select t1.goodfeature_value from cprice_goods_features t1
		natural join cprice_rubric_features t2
		natural join cprice_features t3
		where t2.ID_RUBRIC='$rub_org' && t1.ID_GOOD='".$value[708]."' order by t2.rubricfeature_pos limit 1");
	$row1 = mysql_fetch_array($res1);
	$org_name= $row1['goodfeature_value'];
 	$org_name = str_replace(array("«","»"), array("",""),$org_name);
 	$file_str =  filename(translit(substr($org_name,0,50).'_Schet_'.$value[706].'_'.$value[709].'_rub'));

	$bcount = '';
 	if(isset($value[883]))$bcount = $value[883];
	@$nds = (int)$value[890];

	$s1 = ob_get_contents();
	ob_end_clean();
	unset($s1);
	if($word)
	{    	$word = false;	}
	teInclude("excel");

	if(empty($dir))
	{
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->send($file_str);
	}else
	{
		$file_str.='.xls';
		$workbook = new Spreadsheet_Excel_Writer($dir.$file_str);
	}

	$frmt = & $workbook->addFormat();
	$frmt->setBold();
	$frmt->setAlign('left');
	$frmt->setVAlign('top');
	$frmt->setSize(10);
	$frmt->setTextWrap();
	$frmt->setUnderline(1);

	$worksheet =& $workbook->addWorksheet('Счет на оплату');

          $num=0;$left=1;

	$worksheet->setRow(0,22.5);
	$worksheet->setRow(1,18.75);
	$worksheet->setRow(2,27.75);
	$worksheet->setRow(3,27);
	$worksheet->setRow(4,33.75);

	$worksheet->setColumn(0,0,0.7);
	$worksheet->setColumn(0,1,2.98);
	$worksheet->setColumn(0,2,22.24);
	$worksheet->setColumn(0,3,18.98);
	$worksheet->setColumn(0,4,6.94);
	$worksheet->setColumn(0,5,7.08);
	$worksheet->setColumn(0,6,11.90);
	$worksheet->setColumn(0,7,12.47);

	if($main_org==34892) $worksheet->insertBitmap(1, 2, 'images/logo_2d.bmp',0,15,0.64,0.94);
	else $worksheet->insertBitmap(0, 2, 'images/logo2.bmp',0,23,0.54,0.94);
	$worksheet->setMerge(1, 3, 1, 7);
	$worksheet->setMerge(2, 3, 2, 7);
	$worksheet->setMerge(3, 3, 3, 7);
	$worksheet->write(1, 3, $main_org_name,$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setBold();
	$frmt->setAlign('left');
	$frmt->setVAlign('top');
	$frmt->setSize(10);
	$frmt->setTextWrap();	
	$addr = teGetConf('prms'.$main_org.'uaddr');
	if(!empty($addr))$addr = 'ЮрАдрес: '.$addr;
	$phone = teGetConf('prms'.$main_org.'tels');
	if(!empty($phone))$phone = 'тел.:'.$phone;
	$worksheet->write(2, 3, $addr.'
 '.$phone,$frmt);
	$worksheet->write(3, 3, 'Фактич.адрес: '.teGetConf('prms'.$main_org.'faddr').'',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setBold();
	$frmt->setAlign('center');
	$frmt->setVAlign('vcenter');
	$frmt->setSize(10);
	$worksheet->setMerge(4, 1, 4, 7);
	$worksheet->write(4, 1, 'Образец заполнения платежного поручения',$frmt);
    $k=4;
	$k++;
/*
	if(!empty($bcount) && $main_org<2)
	{		$worksheet->setRow($k,33.75);
		unset($frmt);
		$frmt = & $workbook->addFormat();
		$frmt->setFontFamily('Arial Black');
		$frmt->setBold();
		$frmt->setAlign('center');
		$frmt->setVAlign('vcenter');
		$frmt->setSize(18);
		$worksheet->setMerge($k, 1, $k, 7);
		$worksheet->write($k, 1, 'ВНИМАНИЕ ИЗМЕНИЛИСЬ РЕКВИЗИТЫ!!!',$frmt);
		$k++;
	}
*/	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setSize(10);
	$frmt->setBorder(1);
	$worksheet->setMerge($k, 1, $k, 2);
	$worksheet->write($k, 1, 'ИНН '.teGetConf('prms'.$main_org.'inn').'',$frmt);
	$worksheet->write($k, 2, '',$frmt);
	$worksheet->setMerge($k, 3, $k, 4);
	$worksheet->write($k, 3, 'КПП '.teGetConf('prms'.$main_org.'kpp').'',$frmt);
	$worksheet->write($k, 4, '',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setTop(1);
	$frmt->setRight(1);
	$worksheet->write($k, 5, '',$frmt);
	$worksheet->write($k, 7, '',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setTop(1);
	$worksheet->write($k++, 6, '',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setLeft(1);
	$worksheet->setMerge($k, 1, $k, 2);
	$worksheet->setMerge(($k+1), 1, ($k+1), 2);
	$worksheet->write($k, 1, 'Получатель',$frmt);
	$worksheet->setMerge(($k+1), 1, ($k+1), 4);
	$worksheet->write(($k+1), 1, $main_org_name,$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setLeft(1);
	$frmt->setRight(1);
	$frmt->setAlign('center');
	$worksheet->write($k, 5, '',$frmt);
	$worksheet->write(($k+1), 5, 'Сч. №',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setRight(1);
	$worksheet->setMerge(($k+1), 6, ($k+1), 7);
	$worksheet->write($k++, 7, '',$frmt);
	$worksheet->writeString($k, 6, ''.teGetConf('prms'.$main_org.'rc'.$bcount).'',$frmt);
	$worksheet->write($k++, 7, '',$frmt);

	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setLeft(1);
	$frmt->setTop(1);
	$worksheet->setMerge($k, 1, $k, 2);
	$worksheet->write($k, 1, 'Банк получателя',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setTop(1);
	$worksheet->write($k, 2, '',$frmt);
	$worksheet->write($k, 3, '',$frmt);
	$worksheet->write($k, 4, '',$frmt);
	$worksheet->writeString($k, 6, ''.teGetConf('prms'.$main_org.'bik'.$bcount).'',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setTop(1);
	$frmt->setRight(1);
	$worksheet->write($k, 7, '',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setBorder(1);
	$frmt->setAlign('center');
	$worksheet->write($k++, 5, 'БИК',$frmt);
	$worksheet->write($k, 5, 'Сч. №',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setLeft(1);
	$frmt->setBottom(1);
	$worksheet->setMerge($k, 1, $k, 4);
	$worksheet->write($k, 1, ''.teGetConf('prms'.$main_org.'bank'.$bcount).'',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setBottom(1);
	$worksheet->write($k, 2, '',$frmt);
	$worksheet->write($k, 3, '',$frmt);
	$worksheet->write($k, 4, '',$frmt);
	$worksheet->setMerge($k, 6, $k, 7);
	$worksheet->writeString($k, 6, ''.teGetConf('prms'.$main_org.'cc'.$bcount),$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setBottom(1);
	$frmt->setRight(1);
	$worksheet->write($k, 7, '',$frmt);
    $k+=2;
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setBold();
	$frmt->setSize(14);
	$worksheet->setMerge($k, 1, $k, 4);
	$worksheet->write($k, 1, 'Счет №'.$value[706].($main_org==37103?'-Д':'').' от '.str_date($value[707]),$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setAlign('left');
	$frmt->setVAlign('top');
	$frmt->setTextWrap();
	$frmt->setSize(10);
	$k+=2;
	$worksheet->setMerge($k, 1, $k, 4);
	$worksheet->setMerge(($k+1), 1, ($k+1), 4);
	$worksheet->write($k, 1, 'Заказчик:      '.$org_name,$frmt);
	$worksheet->write(($k+1), 1, 'Плательщик: '.$org_name,$frmt);
	$str = 'Плательщик: '.$org_name;
	if(strlen($str)>60)
	{
		$l = ceil(strlen($str)/60);
		$worksheet->setRow($k,13*$l);
		$worksheet->setRow(($k+1),13*$l);
	}
	$k+=3;
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setBorder(1);
	$frmt->setVAlign('vcenter');
	$frmt->setAlign('center');
	$frmt->setTextWrap();
	$worksheet->setRow($k,27);
	$worksheet->write($k, 1, '№',$frmt);
	$worksheet->setMerge($k, 2, $k, 3);
	$worksheet->write($k, 2, 'Наименование',$frmt);
	$worksheet->write($k, 3, '',$frmt);
	$worksheet->write($k, 4, 'Ед. изм.',$frmt);
	$worksheet->write($k, 5, 'Коли-чество',$frmt);
	$worksheet->write($k, 6, 'Цена '.($nds==294 ? 'с НДС ':'').'(руб)',$frmt);
	$worksheet->write($k, 7, 'Сумма '.($nds==294 ? 'с НДС ':'').'(руб)',$frmt);

	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setBorder(1);
	$frmt->setVAlign('top');
	$frmt->setTextWrap();
	$frmt2 = & $workbook->addFormat();
	$frmt2->setBorder(1);
	$frmt2->setVAlign('top');
	$frmt2->setAlign('right');
	$frmt3 = & $workbook->addFormat();
	$frmt3->setBorder(1);
	$frmt3->setVAlign('top');
	$frmt3->setAlign('center');
    $res = $database -> query("SELECT ID_GOOD FROM cprice_goods_features natural join cprice_goods WHERE main_org=$main_org && good_deleted=0 && goodfeature_value='$id' && ID_FEATURE=703");
    $content='';$i=1;$all=0;$j=$k+1;
    while($row=mysql_fetch_array($res))
    {
		$res2=$database -> query("select * from cprice_goods_features t1
			natural join cprice_rubric_features t2
			natural join cprice_features t3
			where t2.ID_RUBRIC='$rub_prod_cnt' && t1.ID_GOOD='".$row[0]."' order by t2.rubricfeature_pos");
		$value = array();
	 	while($row2 = mysql_fetch_array($res2))
	 	{
	 		if($row2['feature_type']==4 && $row2['goodfeature_value']>0)
	 		{
 				$text =  $database -> getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY='".$row['goodfeature_value']."' limit 1");
	 			$value[$row2['ID_FEATURE']]= $text[0];
	 		}
	 		else $value[$row2['ID_FEATURE']] = $row2['goodfeature_value'];
	 	}
	 	$line = $database -> getArrayOfQuery("select t1.goodfeature_value from cprice_goods_features t1
			where t1.ID_GOOD='".$value[701]."' && t1.ID_FEATURE=698");
	 	$edizm = $database -> getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY='".$line[0]."' limit 1");

		if(strlen($value[702])>42)
		{
			$l = ceil(strlen($value[702])/42);
			$worksheet->setRow($j,13.5*$l);
		}else	$worksheet->setRow($j,27);
		$worksheet->write($j, 1, $i++,$frmt2);
		$worksheet->setMerge($j, 2, $j, 3);
		$worksheet->write($j, 2, $value[702],$frmt);
		$worksheet->write($j, 3, '',$frmt);
		$worksheet->write($j, 4, (empty($edizm[0])?'':$edizm[0]),$frmt3);
		$worksheet->write($j, 5, $value[704],$frmt2);
		$worksheet->write($j, 6, intval($value[705]/$value[704]).',00',$frmt2);
		$worksheet->write($j++, 7, $value[705].',00',$frmt2);
    	$all += $value[705];
    }
	unset($frmt);unset($frmt2);unset($frmt3);
	$frmt = & $workbook->addFormat();
	$frmt->setAlign('right');
	$frmt->setBold();
	$worksheet->write($j, 6, 'Итого',$frmt);
	$worksheet->write(($j+1), 6, ($nds==293 ? 'НДС 18%':'в т.ч. НДС'.($nds==294?' 18%':'')),$frmt);
	if($nds==293)$worksheet->write(($j+2), 6, 'Всего к оплате',$frmt);
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setAlign('right');
	$frmt->setBold();
	$frmt->setBorder(1);
	$worksheet->write($j, 7, $all.',00',$frmt);
	$nds_val = '0.00';
	if($nds==293)
	{		$nds_val = sprintf("%01.2f", ($all*0.18));
		$all += $nds_val;
	}
	if($nds==294) $nds_val = sprintf("%01.2f", ($all*18/118));
	$all_str = sprintf("%01.2f", $all);
	$nds_val = str_replace(".",",",$nds_val);
	$worksheet->write(($j+1), 7, $nds_val,$frmt);
	if($nds==293)
	{		$worksheet->write(($j+2), 7, str_replace(".",",",$all_str),$frmt);
		$j+=1;	}
	$j+=2;
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$frmt->setItalic();
	$frmt->setTextWrap();
	$worksheet->setMerge($j, 1, $j, 7);
	$all = substr($all_str,0,-3);$kop = substr($all_str,-2);
	$kop_nds = substr($nds_val,-2);
	if($nds>0)$worksheet->setRow($j,13.5*2);
	$worksheet->write($j, 1, 'К оплате: '.get_name_sum($all).' '.$kop.' копеек'.($nds>0?', в т.ч. НДС: '.get_name_sum($nds_val).' '.$kop_nds.' копеек':', НДС не облагается'),$frmt);
	$j+=3;
	$pechat = '';
   if($main_org==$ufapr) $pechat = 'pechat1.bmp';
	if($main_org==13920) $pechat = 'pechat3.bmp';
	if($main_org==34892) $pechat = 'pechat4.bmp';
	if($main_org==37103) $pechat = 'pechat5.bmp';
	if(!empty($pechat) && $main_org==34892) $worksheet->insertBitmap(($j+1), 2, 'images/'.$pechat,80,0,0.28,0.40);
	if(!empty($pechat) && $main_org==37103) $worksheet->insertBitmap(($j+4), 2, 'images/'.$pechat,80,0,0.18,0.30);
	elseif(!empty($pechat)) $worksheet->insertBitmap(($j-2), 2, 'images/'.$pechat,80,0,0.53,0.81);
	
	$j+=4;
	unset($frmt);
	$frmt = & $workbook->addFormat();
	$worksheet->write($j, 1, 'Руководитель предприятия',$frmt);
	$worksheet->write($j++, 4, ''.teGetConf('prms'.$main_org.'dir').'',$frmt);
	$worksheet->write(++$j, 1, 'Главный бухгалтер',$frmt);
	$worksheet->write($j, 4, 'Не предусмотрен',$frmt);
	$workbook->close();
	/// дальше не работать
	return $file_str;
}
function fconv($text)
{	return iconv("Windows-1251","UTF-8",$text);}
?>