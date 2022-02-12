<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  Настройка листинга товаров группы
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/

$uid = $_SESSION['user_id'];

// сохраняем данные
if(!empty($_POST['save'])){
	$mainsort=0;
	$vislist = "";
	foreach($_POST['rub'] AS $id => $on){
		if(!empty($_POST['show'][$id])) $on = 1; else $on = 0;
		teSaveConf("u".$uid."r".$rubric_id."f".$id."sort",(int)$_POST['sort'][$id]);
		if($_POST['sort'][$id]>0 && !$mainsort){
			$mainsort = 1;
			teSaveConf("u".$uid."r".$rubric_id."fsort",$id);
		}

		// $database -> query("UPDATE ".DB_PREFIX."rubric_features SET rubricfeature_ls_man=$on WHERE ID_FEATURE=$id and ID_RUBRIC=".$rubric_id);
		if($on) $vislist .= $id.";";
	}
	teSaveConf("u".$uid."r".$rubric_id."vislist",substr($vislist,0,-1));

	if(empty($_POST['showCRT'])) teSaveConf("u".$uid."r".$rubric_id."fCRTshow",0); else teSaveConf("u".$uid."r".$rubric_id."fCRTshow",1);
	if(empty($_POST['showLST'])) teSaveConf("u".$uid."r".$rubric_id."fLSTshow",0); else teSaveConf("u".$uid."r".$rubric_id."fLSTshow",1);
	teSaveConf("u".$uid."r".$rubric_id."fIDsort",$_POST['sortID']);
	teSaveConf("u".$uid."r".$rubric_id."fLSTsort",$_POST['sortLST']);
	teSaveConf("u".$uid."r".$rubric_id."CntOnPg",$_POST['CntOnPg']);

	if(@$_GET['contrag']==1)teRedirect(teGetUrlQuery("=contrag2","act_rub=".$rubric_id));
	elseif(@$_GET['contrag']==2)teRedirect(teGetUrlQuery("=ZakazUfabanket","action=new"));
	elseif(isset($_GET['events']))teRedirect(teGetUrlQuery("=events_see","rub_id=".$rubric_id));
	else teRedirect(teGetUrlQuery());

}



setTitle("Настройка листинга ".$rtype['rubrictype_r_m']." рубрики ".getRubricName($rubric_id));

print "<div class='note'>Сортировка может быть только по одному полю, если сортируемых полей много - то будет дейстовать первое.</div>";
print "<div class='note'>Данная конфигурация распространяется только для пользователя <b>".$_SESSION['user_login']."</b>.</div>";

print "<form method='post'><input type='hidden' name='save' value='1'/>";
print "<table class='list'>";
print "<tr>";
print "<th rowspan='2'>ID</th>";
print "<th rowspan='2'>Наименование столбца</th>";
print "<th rowspan='2'>Глобальная видимость</th>";
print "<th rowspan='2'>Видимость</th>";
print "<th colspan='3'>Сортировка</th>";
print "</tr>";
print "<tr>";
print "<th>-</th>";
print "<th class='asc'><font color='blue'>А</font>..<font color='red'>Я</font></th>";
print "<th class='desc'><font color='red'>Я</font>..<font color='blue'>А</font></th>";
print "</tr>";

	$sort = (int)teGetConf("u".$uid."r".$rubric_id."fIDsort");
	print "<tr>";
	print "<td>-</td>";
	print "<td><b><i>ID</i></b></td>";
	print "<td align='center'><b>да</b></td>";
	print "<td align='center'><input type='checkbox' checked='1' disabled='1'/></td>";
	for($i=0;$i<=2;$i++) print "<td align='center' rowspan='2'><input type='radio' name='sortID' value='$i'".(($sort==$i)?" checked='1'":"")."/></td>";
	print "</tr>";

	$show = (int)teGetConf("u".$uid."r".$rubric_id."fCRTshow");
	print "<tr>";
	print "<td>-</td>";
	print "<td><b><i>Дата создания</i></b></td>";
	print "<td align='center'>нет</td>";
	print "<td align='center'><input type='checkbox' name='showCRT'".(($show)?" checked='1'":"")."/></td>";
	print "</tr>";

	$show = (int)teGetConf("u".$uid."r".$rubric_id."fLSTshow");
	$sort = (int)teGetConf("u".$uid."r".$rubric_id."fLSTsort");
	print "<tr>";
	print "<td>-</td>";
	print "<td><b><i>Дата последнего изменения</i></b></td>";
	print "<td align='center'>нет</td>";
	print "<td align='center'><input type='checkbox' name='showLST'".(($show)?" checked='1'":"")."/></td>";
	for($i=0;$i<=2;$i++) print "<td align='center'><input type='radio' name='sortLST' value='$i'".(($sort==$i)?" checked='1'":"")."/></td>";
	print "</tr>";




$vislist = teGetConf("u".$uid."r".$rubric_id."vislist");
$vislist1 = explode(";",$vislist);
$vislist = array();
foreach($vislist1 AS $i => $vislisti){
	$vislist[$vislisti] = 1;
}


// показываем характеристики для настройки показа
$res = $database->query("SELECT ".DB_PREFIX."features.*,".DB_PREFIX."rubric_features.* FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE rubric_type=$type and feature_deleted=0 and ID_RUBRIC=".$rubric_id." ORDER BY rubricfeature_pos, feature_text");
while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
	$id = $line['ID_FEATURE'];
	$type = $line['feature_type'];
	$sort = (int)teGetConf("u".$uid."r".$rubric_id."f".$id."sort");

	print "<tr>";

	print "<td>$id<input type='hidden' name='rub[$id]' value='1'/></td>";
	print "<td>$line[feature_text]</td>";
	print "<td align='center'>".(($line['rubricfeature_ls_man'])?"<b>да</b>":"нет")."</td>";
	print "<td align='center'><input type='checkbox' name='show[$id]'".((@$vislist[$id])?" checked='1'":"")."/></td>";
	if( $type==7 || $type==5 || $type==9 ){
		for($i=0;$i<=2;$i++) print "<td align='center'><input type='radio' name='sort[$id]' value='$i'".(($i==0)?" checked='1'":" disabled='1'")."/></td>";
	} else {
		for($i=0;$i<=2;$i++) print "<td align='center'><input type='radio' name='sort[$id]' value='$i'".(($sort==$i)?" checked='1'":"")."/></td>";
	}
	print "</tr>";
}

print "</table>";

$count = (int)teGetConf("u".$uid."r".$rubric_id."CntOnPg");
if(!$count) $count = 20;

print "<p><table width='100%'><tr align='center'>";
print "<td>Количество ".$rtype['rubrictype_r_m']." на странице: <input type='text' size='3' name='CntOnPg' value='$count'/></td>";
print "</tr></table></p>";

print "<p align='center'><input type='submit' value='сохранить параметры отображения'/></p>";
print "</form>";

?>