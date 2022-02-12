<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С. и Галлямов Д.Р.
**********/

$showconf = false;
$step = (int)@$_GET['step'];
if(isset($_GET['little']))addGet('little',1);

if( !isset( $_GET['show'] ) ){

	$link = teGetUrlQuery("pg=events_see");
//{{Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798
	print "<h2>база «".teGetConf("company_name")."»</h2>";
	//Новые коментарии по заданиям
//	print "<h3>Важные события</h3>";
	$res = $database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name like 'notify_%'");
	$i=0;
	$num_sob=0;
	$events = '';
	while($row=mysql_fetch_array($res))
	{
		$tid = intval(substr($row['var_name'],7,strlen($row['var_name'])));
		$val = $row['var_value'];
		$arr1 = explode("|",$val);
		if(isset($arr1[3])){			$user_id=$_USER['id'];
			if($user_id==0)$user_id=-1;
			$rights = explode("$",$arr1[3]);
			if(!in_array($user_id,$rights))
				continue;		}
		$row1 = mysql_fetch_array($database -> query("SELECT * FROM ".DB_PREFIX."rubric where ID_RUBRIC=".$tid));
		$name = $row1['rubric_name'];
		$res2 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric_goods t1 inner join ".DB_PREFIX."goods t2 on t1.ID_GOOD=t2.ID_GOOD where t1.ID_RUBRIC=".$tid." && t1.rubricgood_deleted=0 && t2.good_deleted=0");
		$j = 0;$i=0;
		while($row2 = mysql_fetch_array($res2))
		{

			$res3 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric_events where ID_GOOD=".$row2['ID_GOOD']." && ID_USER=".$_USER['id']);
			if(mysql_num_rows($res3)==0){
				$i++;
				$num_sob++;
				if(@$_GET['read']==1) $database -> query("INSERT INTO ".DB_PREFIX."rubric_events (ID_GOOD, ID_USER, tdate) values ('".$row2['ID_GOOD']."','".$_USER['id']."','".time()."')");
			}
			$j++;		}
		$events .= '<li><b>'.$name.': </b> Всего важных событий: '.$j.(($i>0)?' <b>(Вами не прочитано: '.$i.')</b>':'').' <a href="'.$link.'&rub_id='.$tid.'">подробнее</a></li>';

		//$i++;
	}
	if(!empty($events))
	print "<h3>Важные события</h3>".
		"<ul>".$events.
		"</ul>";

	if(@$_GET['read']==1){ teRedirect("./"); }
	if($num_sob>0) print '<br/><a href="'.teGetUrlQuery("read=1").'">Пометить непрочитанные сообщения, как прочитанные</a>';

//}}====================================================

	if($cnt=teGetConf("downexcel")) print "<h3>Скачек прайс-листа: $cnt</h3>";
	if($cnt=teGetConf("ban_cnt")) print "<h3>Количество кликов по баннеру: $cnt</h3>";
} else {

	$showconf = (empty($_GET['show']))?false:true;
}
?>