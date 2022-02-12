<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/***
*
*  Управление рубрикатором
*
***/

// переменные из GET

// подрубрики этой рубрики показывать
$showid = (int)@$_GET['showid'];
// ид. выбранной рубрики (для изменения, удаления и т.д.)
$id = (int)@$_GET['id'];
// тип (товар или услуга)
$type = (int)@$_GET['type'];
//if($_USER['group']==3 && !@$_USER['rubric_types'][$type] && !empty($_GET['type'])) teRedirect(teGetUrlQuery());
//if(!empty($_GET['id'])){if(checkAccess($id)==0) teRedirect(teGetUrlQuery());}
//$access = @$_USER['rubric_types'][$type];
$idshow = @$_GET['idshow'];if(empty($idshow)) $idshow = "id";
addGet("idshow",$idshow);
addGet("pg",'rubric');

if($showid>0){
	$line = $database -> getArrayOfQuery("SELECT rubric_type FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$showid);
	$type = $line[0];
}

$sql_str = "SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 and ID_RUBRIC_TYPE=".$type;
$line = $database->getArrayOfQUery("select var_value from cprice_configtable where var_name='finrazd'");
if($line[0]>0 && $line[0]==$type)
{	addSubMenu(teGetUrlQuery("pg=finoper","op1=config"),'Настройки рубрик');
	addSubMenu(teGetUrlQuery("pg=finoper","op1=conf_rep"),'Настройки отчетности');
	addSubMenu(teGetUrlQuery("pg=finoper"),'На главную');
	$sql_str = "SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_deleted=0 and ID_RUBRIC_TYPE=".$type;}

$res = $database->query($sql_str);
$rtype=mysql_fetch_array($res,MYSQL_ASSOC);
if($rtype['ID_RUBRIC_TYPE']!=$type) teRedirect(teGetUrlQuery());

if(@$_GET['typeview']) {
	teSetCookie("typeview[".DB_ID."][$type]",$_GET['typeview']);
	$_COOKIE['typeview'][DB_ID][$type] = $_GET['typeview'];
}
if(!empty($_COOKIE['typeview'][DB_ID][$type])){
	$typeview = $_COOKIE['typeview'][DB_ID][$type];
} else {
	$typeview = "tree";
}
teSetCookie("typeview[".DB_ID."][$type]",$typeview);
// addGet("typeview",$typeview);
addGet("type",$type);
addGet("showid",$showid);




$maxlevel =  $database -> getArrayOfQuery("SELECT rubrictype_maxlevel FROM ".DB_PREFIX."rubric_types WHERE ID_RUBRIC_TYPE=".$type);
//////teGetConf( (($type==1)?"maxlevel_goods":"maxlevel_srvcs") );
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

global $skin;


// если какое-то действие
if(isset($_GET['action']))
switch($_GET['action']){

// добавление
case 'add':
	//$acc = checkAccess($id);
	//if(!@$acc['m']){die('Попытка взлома');}
if($maxlevel>$level){
	// если добавляем подрубрику,  иначе
	if(!empty($id)){
		// берем из БД название рубрики, в кот.добавляем подрубрику
		$line = $database -> getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$id);
		// вывод заголовка
		print "<h2>Добавление подрубрики в рубрику \"".$line[0]."\"</h2>";

		$prefix = "под";
	} else {
		// вывод заголовка
		print "<h2>Добавление общей рубрики ".$rtype['rubrictype_r_s']."</h2>";

		$prefix = "";
	}


	print "<div align=center>";
	$frm = new teForm("form1","post");
	$frm->addf_text("rubric_name", "Наименование новой ".$prefix."рубрики");
	$frm->setFieldWidth("rubric_name","300px");

	//$frm->addf_text("rubric_unit_prefixname", "Префикс единиц продукции");
	//$frm->addf_desc("rubric_unit_prefixname", "Слово (словосочетание), которое<br> будет показываться до наименования модели<br><i>напр. <b>погрузчик</b> Komatsu FB05-3</i>");
	//$frm->setFieldWidth("rubric_unit_prefixname","300px");

	$frm->addf_text("rubric_ex", "Дополнительное поле");
	$frm->addf_desc("rubric_ex", "Используется для каждой системы по своему, поэтому обратитесь к администратору");
	$frm->setFieldWidth("rubric_ex","300px");
	$code = <<<TXT
  function encodestring(st)
  {
    st = st.toLowerCase();
    var str1 ="абвгдеёзийклмнопрстуфхыэ ", str2 = "abvgdeeziyklmnoprstufhie_";
    for(var i=0;i<str1.length;i++)
    {    	st = st.replace(new RegExp(str1[i],'g'),str2[i]);    }
    var str3 = "жцчшщьъюяїє";
    var arr = ["zh","ts", "ch","sh","shch", "", "", "yu", "ya", "i", "ie"];
    for(var i=0;i<str3.length;i++)
    {
     	st = st.replace(new RegExp(str3[i],'g'),arr[i]);
    }
    return st;
  }
  function change_rub(elem)
  {
  	if(elem.value!='')
  	{
  		document.getElementById('rubric_textid_0').value = encodestring(elem.value);
  	}  }
TXT;
teAddJSScript($code);
	$frm->addf_text("rubric_textid", "Текстовый идентификатор");
	$frm->setJSScript("rubric_name","onChange","change_rub(this);");

	$frm->addf_file("rubric_img", "Картинка ".$prefix."рубрики");

	$frm->addf_checkbox("rubric_visible", "Показывать ".$prefix."рубрику посетителям сайта", true);

	$frm->setf_require("rubric_name");
	if(!$frm->send()){
		
		/*   если значения формы уже введены   */

		$textid = $frm->get_value('rubric_textid');
		if(empty($textid)){
			$textid = filename(translit($frm->get_value('rubric_name')));
			$textid = mb_strtolower($textid);
		}
		$i = 0;
		while($database->getArrayOfQuery("SELECT ID_RUBRIC FROM cprice_rubric WHERE rubric_textid='$textid".(($i==0)?"":"_$i")."' and rubric_deleted=0")){
			$i++;
		}
		if(!empty($i)) $textid .= "_$i";

		$vis = 0;
		$vis = ($frm->get_value('rubric_visible')=="on")?1:0;
		$rubric_img = $frm->move_file('rubric_img','rubric_img');

		if(!empty($rubric_img)){
			teInclude("images");
			new_wm_image(DATA_FLD.'rubric_img/'.$rubric_img);
		}

		$database -> query("INSERT INTO ".DB_PREFIX."rubric (rubric_textid,rubric_parent,rubric_name,rubric_unit_prefixname,rubric_ex,rubric_img,rubric_type,rubric_visible) VALUES ('".$textid."',$id,'".$frm->get_value('rubric_name')."','".$frm->get_value('rubric_unit_prefixname')."','".$frm->get_value('rubric_ex')."','$rubric_img','$type',$vis)");
		$pid = $id;
		$id = $database -> id();
        del_cache($id);//удаление кэша
        del_cache($pid);//удаление кэша
		// if($_USER['group']==3) $database -> query("INSERT INTO ".DB_PREFIX."rubric_users (ID_RUBRIC,ID_USER) VALUES ($id,".$_USER['id'].")");
		$database -> query("UPDATE ".DB_PREFIX."rubric_goods SET ID_RUBRIC=".$id." WHERE ID_RUBRIC=".$pid);


/***  Алексей - 11.11.08   ***/
		$result_trig = $create_news_triger->createNews_rubric($type,1, array($pid,$frm->get_value('rubric_name')));
/***  /Алексей   ***/

		/// добавление новости
		// берем наим. рубрики-родителя если нет, то Товары/Услуги
		/*
		$res = $database -> query("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$showid);
		if( $line = mysql_fetch_array($res,MYSQL_NUM) ){
			$pname = $line[0];
		} else {
			$pname = (($type==1)?"Товары":"Услуги");
		}*/
		$id = $database->id();
		optimizeFeature($type, 0);

		// сохранение в БД
		// generate_news($type, 1, teGetUrlQuery("=good","showid=".$id), $frm->get_value('rubric_name'), getRubricName($pid,true,false,false));

		teRedirect(teGetUrlQuery());
	}
	print "</div>";
}
break;

// изм.
case 'edit':
	$acc = checkAccess($id);
	if(!@$acc['m']){die('Попытка взлома');}
 if( $line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$id,MYSQL_ASSOC) ){

	// вывод заголовка
	print "<h2>Изменение рубрики ".getRubricName($id)."</h2>";

	print "<div align=center>";
	$frm = new teForm("form1","post");
	$frm->addf_text("rubric_name", "Наименование рубрики", $line['rubric_name']);
	$frm->setFieldWidth("rubric_name","300px");

	//$frm->addf_text("rubric_unit_prefixname", "Префикс единиц продукции", $line['rubric_unit_prefixname']);
	//$frm->addf_desc("rubric_unit_prefixname", "Слово (словосочетание), которое<br> будет показываться до наименования модели<br><i>напр. <b>погрузчик</b> Komatsu FB05-3</i>");
	//$frm->setFieldWidth("rubric_unit_prefixname","300px");

	$frm->addf_text("rubric_ex", "Дополнительное поле", $line['rubric_ex']);
	$frm->addf_desc("rubric_ex", "Используется для каждой системы по своему, поэтому обратитесь к администратору");
	$frm->setFieldWidth("rubric_ex","300px");

	//if($_USER['login']=="root")
	$frm->addf_text("rubric_textid", "Текстовый идентификатор", $line['rubric_textid']);

	$frm->addf_file("rubric_img", "Картинка рубрики");
	$frm->add_value("rubric_img",DATA_FLD."rubric_img/".$line['rubric_img']);

	$chk = ($line['rubric_visible']==1)?true:false;
	$frm->addf_checkbox("rubric_visible", "Показывать рубрику посетителям сайта", $chk);

	$frm->setf_require("rubric_name");
	if(!$frm->send()){
		//print_r(DATA_FLD);
		//print_r($_FILES);exit;
		/*   если значения формы уже введены   */

		$textid = $frm->get_value('rubric_textid');
		if(empty($textid)){
			$textid = filename(translit($frm->get_value('rubric_name')));
		}
		$i = 0;
		while($database->getArrayOfQuery("SELECT ID_RUBRIC FROM cprice_rubric WHERE ID_RUBRIC<>$id and rubric_textid='$textid".(($i==0)?"":"_$i")."' and rubric_deleted=0")){
			$i++;
		}
		if(!empty($i)) $textid .= "_$i";

		$vis = ($frm->get_value('rubric_visible')=="on")?1:0;
		$rubric_img = $frm->move_file('rubric_img','rubric_img');
        $qstr = '';
		if(!empty($rubric_img)){
			teInclude("images");
	    	new_wm_image(DATA_FLD.'rubric_img/'.$rubric_img);
	    	$qstr = "rubric_img='$rubric_img',";
	  	}elseif(isset($_POST['rubric_img']))$qstr = "rubric_img='',";
		$database -> query("UPDATE ".DB_PREFIX."rubric SET ".((empty($textid))?"":"rubric_textid='".$textid."', ")."rubric_name='".$frm->get_value('rubric_name')."',rubric_unit_prefixname='".$frm->get_value('rubric_unit_prefixname')."',rubric_ex='".$frm->get_value('rubric_ex')."',".$qstr."rubric_visible='".$vis."' WHERE ID_RUBRIC=".$id);
        del_cache($id);//удаление кэша

		teRedirect(teGetUrlQuery());
	}
	print "</div>";
 }
break;

case 'pos': // ручная сортировка рубрик-детей $_POST[rubric]
	if(!empty($_POST['rubric'])){
        del_cache($showid);//удаление кэша
		foreach( $_POST['rubric'] AS $rubric_id => $rubric_pos ){
			$res = $database->query("UPDATE ".DB_PREFIX."rubric SET rubric_pos='".$rubric_pos."' WHERE ID_RUBRIC=".$rubric_id);
		}
		teRedirect(teGetUrlQuery());
	} else {
		$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=$showid and rubric_type=$type ORDER BY rubric_pos ASC");
		print "<form method=post>";
		print "<table width=90%>";
		while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
			print "<tr>";
			print "<td width=1%><input type=text size=3 name='rubric[$line[ID_RUBRIC]]' value='$line[rubric_pos]'></td>";
			print "<td>$line[rubric_name]</td>";
			print "</tr>";
		}
		print "<tr align=center><td colspan=2><input type='submit' value='сохранить порядок отображения'></td></tr>";
		print "</table>";
		print "</form>";
	}
break;

case 'copy_structure': // копирование структуры рубрикатора из одной базы в другую (там пошагого, всё просто...)

	include ROOT_FLD."engine/data/hosts.php";

	// выбор БД (откуда копировать)
	if(empty($_GET['db_id'])){
		print "<h2>Выберите Базу данных, из которой следует копировать структуру</h2>";
		print "<ul>";
		combase();
		foreach( $hosts AS $id => $cont ){
			list($access) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and database_id=".$id." and ID_USER=".$_USER['id']);
            if($access>1 || $_USER['id']==0)
            {
				if(@$_COOKIE['host_name']!=$id){
					$y = "";
				} else {
					$y = "style='font-weight:800;'";
				}
				print "<li><a $y href='".teGetUrlQuery("action=copy_structure","db_id=".$id)."'>".$cont['name']."</a></li>";
			}
		}
		print "</ul>";
	} else {
        combase();
        list($access) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and database_id=".intval($_GET['db_id'])." and ID_USER=".$_USER['id']);
		if($access<2 && $_USER['id']!=0)
		{
			print 'Доступ запрещен';
			exit;
		}
		// дерево рубрик. рекурсия.
		function get_child($db_name, $id, $template, $cnt=false){
			global $type;
			$s = "";
			$res = mysql_query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$id." ORDER BY rubric_name");
			// если $cnt не указан (ф-я вызвана 1-й раз), $i = 0, иначе $cnt + 1;
			$i = (!$cnt)?1:$cnt+1;
			while($line = mysql_fetch_array($res,MYSQL_ASSOC)){

				// вызываем эту же ф-ю с $id равным текущему
				$arr = get_child($db_name, $line['ID_RUBRIC'], $template, $i);

				// заменяем переменные шаблона на данные
				$s1 = @str_replace("{name}",$line['rubric_name']." (".getCountFeatures($line['ID_RUBRIC'])." хар.)".$arr['s'],$template);
				$s1 = str_replace("{formname}",$line['ID_RUBRIC'],$s1);
				$s1 = str_replace("{id}","ch".$i,$s1);


				$s2 = "";
				// фрагмент генерирует JS, который отмечает флажки всех детей текущей записи по изменению текущей записи.

				if( @($arr['n']>$i) ){
					$s2 .= " onClick=\"";
					for($ii=$i+1;$ii<=$arr['n'];$ii++){
						$s2 .= "this.form.ch$ii.checked=";
					}
					$s2 .= "this.checked;\" ";
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
		$step = (int)@$_GET['step'];
		print "<form method=get><table width=100%><tr><td>";

		print "<input type=hidden name='".G_PAGE."' value='rubric'>";
		print "<input type=hidden name='type' value='$type'>";
		print "<input type=hidden name='typeview' value='$typeview'>";
		print "<input type=hidden name='showid' value='$showid'>";
		print "<input type=hidden name='action' value='copy_structure'>";
		print "<input type=hidden name='db_id' value='".@$_GET['db_id']."'>";
		print "<input type=hidden name='step' value='".($step+1)."'>";
		//print "<div align=center> <input type=button value='назад' onClick='history.back()'> <input type=submit value='далее'> </div>";

		$host_name1 = @$_GET['db_id'];
		otherbase($host_name1);

		if($step==4){ // редирект на корень рубрики
			teRedirect(teGetUrlQuery());
		}
		if($step==3){ // процедура копирования........
			$arr[] = array();
			$arr_feat[] = array();
			function copydb($rubric_id,$parent=0){
				global $arr;
				if(!isset($arr[$rubric_id])){
					global $database;
					global $hosts;
					global $host_name;
					global $host_name1;

					// берем данные рубрики
					$res = mysql_query("SELECT * FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
					$rub = mysql_fetch_array($res,MYSQL_ASSOC);

					$res_feat = mysql_query("SELECT ".DB_PREFIX."rubric_features.*,".DB_PREFIX."features.* FROM ".DB_PREFIX."rubric_features NATURAL JOIN ".DB_PREFIX."features WHERE rubric_type=".$rub['rubric_type']." && ID_RUBRIC=".$rubric_id);

					// проверяем, есть ли уже такое имя
					// если нет - добавляем рубрику
					// если есть, то добавляем только детей и характеристики
					curbase();
					if(!$rubric_new = $database -> getArrayOfQuery("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=$parent and rubric_type=$_GET[rubric_t] and rubric_name='".$rub['rubric_name']."'")){
						$database -> query("INSERT INTO ".DB_PREFIX."rubric (rubric_parent,rubric_type,rubric_name,rubric_unit_prefixname,rubric_visible,rubric_deleted) VALUES ($parent,$_GET[rubric_t],'$rub[rubric_name]','$rub[rubric_unit_prefixname]',1,0)");
						$rubric_new[0] = $database->id();
						//print "рубрика <b>".$rub['rubric_name']."</b><font color=green> скопирована!</font><br>";
					} else {
						print "<div style='font-size:0.8em'>рубрика <b>".$rub['rubric_name']."</b><font color=red> уже существует!</font></div>";
					}
					$q_f = "INSERT INTO ".DB_PREFIX."features (feature_text,feature_rubric,feature_type,feature_require,feature_graduation,feature_enable) VALUES";
					$q_rf = "INSERT INTO ".DB_PREFIX."rubric_features  (ID_RUBRIC,ID_FEATURE,rubric_type,rubricfeature_graduation,rubricfeature_pos,rubricfeature_ls_man,rubricfeature_ls_pub) VALUES";
					while($line_feat = mysql_fetch_array($res_feat,MYSQL_ASSOC)){
						if(!$id_feat = $database->getArrayOfQuery("SELECT ID_FEATURE FROM ".DB_PREFIX."features WHERE feature_text='$line_feat[feature_text]' and feature_type=$line_feat[feature_type]")){
							$database->query($q_f." ('$line_feat[feature_text]',$line_feat[feature_rubric],$line_feat[feature_type],$line_feat[feature_require],$line_feat[feature_graduation],$line_feat[feature_enable])");
							$id_feat[0] = $database->id();
						}

						$iiii=0;
						if(!$database->getArrayOfQuery("SELECT ID_FEATURE FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=$rubric_new[0] and ID_FEATURE=$id_feat[0]")){
							$q_rf .= " ($rubric_new[0],$id_feat[0],$_GET[rubric_t],$line_feat[rubricfeature_graduation],$line_feat[rubricfeature_pos],$line_feat[rubricfeature_ls_man],$line_feat[rubricfeature_ls_pub]),";
							$iiii++;
						}
						// зн-я справочника
						if($line_feat['feature_type']==4){
							otherbase($host_name1);
							$res = mysql_query("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=$line_feat[ID_FEATURE]");
							curbase();

							$q_fd = "INSERT INTO ".DB_PREFIX."feature_directory (ID_FEATURE,featuredirectory_text) VALUES";
							$iii=0;
							while($line=mysql_fetch_array($res)){
								if(!$database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."feature_directory WHERE featuredirectory_text='$line[0]'")){
									$q_fd .= " ($id_feat[0],'$line[0]'),";
									$iii++;
								}
							}
							if($iii>0) $database->query(substr($q_fd,0,-1));
						}
					}

					if(@$iiii>0) $database->query(substr($q_rf,0,-1));

					otherbase($host_name1);
					$res = mysql_query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_parent=".$rubric_id);
					while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
						if(isset($_GET['rubric_w'][$line['ID_RUBRIC']])){
							copydb($line['ID_RUBRIC'],$rubric_new[0]);
						}
					}

					// указываем что эта рубрика уже скопирована
					$arr[$rubric_id] = $rubric_new[0];
				}
			}
			print "<h2>Копирование рубрик.</h2>";
			print "<div class='ok'>Копирование рубрик завершено! <small><button onclick=\"location.href='".teGetUrlQuery("")."'\"><small>готово >>></small></button></small></div>";
			foreach($_GET['rubric_w'] AS $n => $v){
				copydb($n);
			}
		}
		if($step==2){	// подтверждение действия
			if(!empty($_GET['rubric_w']) || !empty($_GET['rubric_t'])){

				print "<h2>Подтвердите действие</h2>";
				print "<div style='padding:5px;font-size:1.4em;'><b>Рубрики:</b></div><ul>";
				foreach($_GET['rubric_w'] AS $n => $v){
					$res = mysql_query("SELECT rubric_name FROM cprice_rubric WHERE ID_RUBRIC=$n");
					$val = mysql_fetch_array($res);
					$val = $val[0];
					print "<li>".$val."</li>";
				}
				print "</ul><div style='padding:5px;font-size:1.4em;'>из базы данных <b>".$hosts[$host_name1]['name']."</b>";

				curbase();

				$val = $database -> getArrayOfQuery("SELECT rubrictype_name FROM cprice_rubric_types WHERE ID_RUBRIC_TYPE=".(int)$_GET['rubric_t']);
				$val = $val[0];
				print " будут импортированы в раздел <b>$val</b></div>";
				print "<input type='hidden' name='rubric_t' value='$_GET[rubric_t]'>";
				foreach($_GET["rubric_w"] AS $n => $v){
					print "<input type='hidden' name='rubric_w[$n]' value='$v'>";
				}
				print "<br><br><div align=center style='padding:5px;font-size:1.4em;'>Для продолжения нажмите \"<b>далее</b>\"</div>";
			} else {
				//$step=1;
				teAddJSScript("history.back()");
			}
		}
		if($step==1){	// выбор копируемых рубрик
			if(empty($_GET['section_w']) || empty($_GET['rubric_t'])){
				teAddJSScript("history.back()");
				// $step=0;
			} else {
				print "<h2>Выберите копируемые рубрики:</h2>";
				print "<input type=hidden name='rubric_t' value=".$_GET['rubric_t'].">";

				$res = mysql_query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM cprice_rubric_types WHERE ID_RUBRIC_TYPE=".$_GET['section_w']);
				$line = mysql_fetch_array($res);
				//print "<h3>".$line[1]."</h3>";
				$type = $line[0];
				print get_child("database", 0, "<div style='padding-left:".TREE_LEFT."em'><input type='checkbox' name='rubric_w[{formname}]' id={id} {param}>{name}</div>");

			}
		}
		if($step==0){ // выбор: из какого в какоз раздел копировать дерево
			print "<h2>Выберите разделы (что, куда):</h2>";

			print "<table align=center cellpadding=10><tr valign=top><td><div><b>Из какого раздела будем копировать рубрики?</b></div>";
			$res = mysql_query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM cprice_rubric_types");
			while($line = mysql_fetch_array($res)){
				print "<div><input type='radio' name='section_w' value='".$line[0]."' id='sw".$line[0]."' /><label for='sw".$line[0]."'>".$line[1]."</label></div>";
			}
			print "</td><td><div><b>В какой раздел текущей БД копировать рубрики?</b></div>";
			curbase();
			$res = mysql_query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM cprice_rubric_types");
			while($line = mysql_fetch_array($res)){
				print "<div><input type='radio' name='rubric_t' value='".$line[0]."' id='st".$line[0]."' ".(($type==$line[0])?"checked":"")." /><label for='st".$line[0]."'>".$line[1]."</label></div>";
			}
			print "</td></tr></table>";
		}
		print "<div align=center> <input type=button value='назад' onClick='history.back()'> <input type=submit value='далее'> </div>";

		print "</form>";
	}
break;


case 'opench':	// раскрепить рубрику (все, у кого есть доступ, смогут делать операции над рубрикой.
	$acc = checkAccess($id);
	if(!@$acc['m']){die('Попытка взлома');}
	$database -> query("UPDATE ".DB_PREFIX."rubric SET rubric_close=0 WHERE ID_RUBRIC=".$id);
	unset($_GET['action']);
break;
case 'closech': // закрепить рубрику (только root сможет оперировать с рубрикой)
	$acc = checkAccess($id);
	if(!@$acc['m']){die('Попытка взлома');}
	$database -> query("UPDATE ".DB_PREFIX."rubric SET rubric_close=1 WHERE ID_RUBRIC=".$id);
	unset($_GET['action']);
break;
// вкл
case 'enable':
	$acc = checkAccess($id);
	if(!@$acc['m']){die('Попытка взлома');}

	$database -> query("UPDATE ".DB_PREFIX."rubric SET rubric_visible=1 WHERE ID_RUBRIC=".$id);
	del_cache($id);//удаление кэша
	unset($_GET['action']);
break;
// выкл
case 'disable':
	$acc = checkAccess($id);
	if(!@$acc['m']){die('Попытка взлома');}

	$database -> query("UPDATE ".DB_PREFIX."rubric SET rubric_visible=0 WHERE ID_RUBRIC=".$id);
	del_cache($id,0,true);//удаление кэша
	unset($_GET['action']);
break;
case 'move_rubric':
	// перенос (или копирование) рубрики
	include "rubric_move.php";
break;
// удаление
case 'delete':
	$acc = checkAccess($id);
	if(!@$acc['m']){die('Попытка взлома');}
    del_cache($id,0,true);//удаление кэша
	// удаление подрубрик
	function deleteChild($id){
		global $database;

		$res = $database -> query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_parent=".$id, true, 3);
		while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
			deleteChild($line['ID_RUBRIC']);
			$database -> query("UPDATE ".DB_PREFIX."rubric SET rubric_deleted=1 WHERE ID_RUBRIC=".$line['ID_RUBRIC'], true, 3);
			del_cache($line['ID_RUBRIC'],0,true);//удаление кэша
		}
	}
	deleteChild($id);
	// удаление самой рубрики
	$database -> query("UPDATE ".DB_PREFIX."rubric SET rubric_deleted=1 WHERE ID_RUBRIC=".$id, true, 3);

/***  Алексей - 11.11.08   ***/
	$line = $database -> getArrayOfQuery("SELECT rubric_name,rubric_parent FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$id,MYSQL_ASSOC);
	$result_trig=$create_news_triger->createNews_rubric($type,7,array($line['rubric_parent'],$id));
/***  /Алексей   ***/

	unset($_GET['action']);
break;
case 'test':
	// перенос (или копирование) рубрики
	include "rubric_list2.php";
break;

}

// если не запрашиваются действия - вывод дерева рубрик
if(!isset($_GET['action']) || @($_GET['action']=='filter') ){
	include "rubric_list.php";
}

?>