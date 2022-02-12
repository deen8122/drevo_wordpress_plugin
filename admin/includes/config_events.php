<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798
print "<br/><br/>";
print "<h2>Важные события</h2>";
print "<div align=center>";
/*
$database -> query("CREATE TABLE IF NOT EXISTS `".DB_PREFIX."rubric_events` (
  `id` int(11) NOT NULL auto_increment,
  `tdate` int(11) NOT NULL default '0',
  `ID_GOOD` int(11) NOT NULL default '0',
  `ID_USER` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
");
*/
@$msg = $_GET['msg'];
if($msg=='upd') print '<span style="color:blue;font-weight:bold;">Событие изменено</span>';
if($msg=='add') print '<span style="color:blue;font-weight:bold;">Событие добавлено</span>';
if($msg=='del') print '<span style="color:blue;font-weight:bold;">Событие удалено</span>';
$link = teGetUrlQuery("show=conf","step=5");
$vname='';
$vtype='';
$vemails='';
$veshop=false;
$op1='';
if(isset($_GET['id']))
{
	$id = $_GET['id'];
	$op1 = $_GET['op1'];
	if($op1=='del2')
	{
		$database -> query("DELETE FROM ".DB_PREFIX."configtable where var_name='".$id."'");
		teRedirect($link.'&msg=del');
	}
}

$res = $database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name like 'notify_%'");
$i=0;
$table='';
$users = array();
while($row=mysql_fetch_array($res))
{
	$tid = intval(substr($row['var_name'],7,strlen($row['var_name'])));
	$row1 = mysql_fetch_array($database -> query("SELECT * FROM ".DB_PREFIX."rubric where ID_RUBRIC=".$tid));
	$name = $row1['rubric_name'];
	$val = $row['var_value'];
	$arr1 = explode("|",$val);
	$row1 = mysql_fetch_array($database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name='".$arr1[0]."'"));
	$arr_t = explode("|",$row1['var_value']);
	$type = $arr_t[0];
	$emails = $arr1[1];
	if($op1=='edit')
	{
	  if($id==$row['var_name'])
	  {
		if(isset($arr1[3]))$users = explode("$",$arr1[3]);
		$vname=$tid;
		$vtype=$arr1[0];
		$vemails=$emails;
		if(isset($arr1[2]))if($arr1[2]==1)$veshop=true;
	  }
	}
	if($op1=='del')
	{
	  if($id==$row['var_name'])
	  {
	  	print 'Вы уверены что собираетесь удалить событие по рубрике: '.$name.': <a href="'.$link.'&op1=del2&id='.$row['var_name'].'">ДА</a><br/><br/><br/>';
	  }
	}
	$table .= '<tr><td>'.getIdToPrint_config("configtable",$row['var_name']).'</td><td>'.$name.'</td><td><b>'.$type.'</b></td><td>&nbsp;'.$emails.'</td><td><a href="'.$link.'&op1=edit&id='.$row['var_name'].'">Изменить</a> <a href="'.$link.'&op1=del&id='.$row['var_name'].'">Удалить</a></td></tr>';
	$i++;
}
if(!empty($table))
	$table = '<table border="1">
		<tr><th>ID</th><th>Рубрика</th><th>Шаблон письма</th><th>Емайлы</th><th>Действие</th></tr>
	'.$table.'</table><br/><br/>';
print $table;

//Форма добавления и изменения
	$frm = new teForm("form1","post");
	if($op1=='edit'){
		$frm->addf_hidden("id", $id);
		$line = $database -> getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric where ID_RUBRIC=".$vname." && rubric_deleted=0");
		$frm->addTitle("<b>Изменить событие по рубрике: ".$line[0]."</b>");
	}
	else
	{
		$frm->addTitle("<b>Добавить событие</b>");
		$frm->setSubmitCaption("Добавить");
	}

	$frm->addf_selectGroup("sel_rubric", "Рубрика");
	$frm->addf_desc('sel_rubric', 'При добавленние записей в эту рубрику,<br/> система напомнит об этом');

	$res = $database -> query("SELECT * FROM ".DB_PREFIX."rubric t1 inner join ".DB_PREFIX."rubric_types t2 on t1.rubric_type=t2.ID_RUBRIC_TYPE where t1.rubric_parent=0 && t1.rubric_deleted=0 order by t1.rubric_type, t1.rubric_name");
	if(mysql_num_rows($res)==0)
		$frm->errorValue("sel_rubric", "Добавте рубрики для корректной работы");
	while($row=mysql_fetch_array($res))
	{
		$obsh =  $database -> getArrayOfQuery("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric_features where rubric_type=".$row['rubric_type']." order by ID_RUBRIC limit 1");
		if($obsh[0]=='0')
			continue;
		$res2 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric where rubric_parent=".$row['ID_RUBRIC']." && rubric_deleted=0");

		if(mysql_num_rows($res2)==0)
			$frm->addf_selectItem("sel_rubric", $row['ID_RUBRIC'], $row['ID_RUBRIC'].' '.$row['rubric_name'].' ('.$row['rubrictype_name'].')');
		else
		{
			while($row2=mysql_fetch_array($res2))
			{
				$frm->addf_selectItem("sel_rubric", $row2['ID_RUBRIC'], $row2['ID_RUBRIC'].' '.$row2['rubric_name'].' ('.$row['rubrictype_name'].')');
            }
    	}
	}
    $frm->add_value("sel_rubric",$vname);

	$frm->addf_selectGroup("sel_type", "Шаблон письма");
	$frm->addf_desc('sel_type', 'От выбранного шаблона письма зависит, <br/> какое напоминание придет на выбранные е-mail');

	$res = $database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name like 'type_event_%'");
	if(mysql_num_rows($res)==0)
		$frm->errorValue("sel_type", "Добавте типы событий для корректной работы");

	while($row=mysql_fetch_array($res))
	{		$tid = $row['var_name'];
		$val = $row['var_value'];
		$arr1 = explode("|",$val);
		$name = $arr1[0];
		$frm->addf_selectItem("sel_type", $tid, $name);
	}
    $frm->add_value("sel_type",$vtype);

	$frm->addf_selectGroup("sel_us_em", "Выберите пользователя");

	//combase();
	$res = $database -> query("SELECT * FROM ".DB_PREFIX."users t1 inner join ".DB_PREFIX."users_privilegies t2 on t1.ID_USER=t2.ID_USER where t1.user_email<>'' && t1.user_visible=1 && t1.user_deleted=0 && t2.access_type>0 && t2.database_id=".DB_ID);
	if(mysql_num_rows($res)==0)
		$frm->errorValue("sel_type", "Добавте пользователей для корректной работы");

	while($row=mysql_fetch_array($res))
		$frm->addf_selectItem("sel_us_em", $row['user_email'], $row['user_name'].' '.$row['user_sname']);


	$frm->addf_button("Кнопка","Добавить E-mail",'onclick="if(sel_us_em_0.value!=\'\')emails_0.value=emails_0.value + sel_us_em_0.value + \',\';"');
	$frm->addf_desc('Кнопка', 'Эта кнопка добавляет адреса e-mail<br/>выбранных пользователей в нижнее поле');

	$frm->addf_text('emails', 'E-mail', $vemails);
	$frm->addf_desc('emails', 'На эти адреса e-mail будут приходить<br/>напоминание о важном событии');
    $frm->addf_checkbox('eshop','Интернет магазин',$veshop);
	$frm->addf_desc('eshop', 'Поставте галочку, если выбранная рубрика из Интернет-магазина');
    $frm->setf_require('sel_rubric','sel_type');

	$frm->addf_selectGroup("sel_user", "Выберите пользователя");
	$frm->addf_selectItem("sel_user", -1, 'Root');
	$res = $database -> query("SELECT * FROM ".DB_PREFIX."users t1 inner join ".DB_PREFIX."users_privilegies t2 on t1.ID_USER=t2.ID_USER where t1.user_email<>'' && t1.user_visible=1 && t1.user_deleted=0 && t2.access_type>0 && t2.database_id=".DB_ID);
	while($row=mysql_fetch_array($res))
		$frm->addf_selectItem("sel_user", $row['ID_USER'], $row['user_name'].' '.$row['user_sname']);
	$frm->add_value("sel_user",$users);
	$frm->setFieldMultiple("sel_user");
	$frm->addf_desc('sel_user', 'Выбранным пользователям будут отображаться важные события<br/>и их будут информировать о новых событиях');
	curbase();

if(!$frm->send())
{
		$rubric=(int)$_POST['sel_rubric'][0];
       	$type = $_POST['sel_type'][0];
		if($rubric>0 && $type!='')
        {
        	$var = 'notify_'.$rubric;
        	$emails = $_POST['emails'][0];
        	$users = $_POST['sel_user'];
        	//@ $eshop = $_POST['eshop'];
        	if(isset($_POST['eshop']))$eshop = 1;else $eshop = 0;
        	$value = $type.'|'.$emails.'|'.$eshop.'|'.implode("$",$users);
        	if(isset($_POST['id']))
        	{
        		$database -> query("UPDATE ".DB_PREFIX."configtable set var_value='$value', var_name='$var' WHERE var_name='".$_POST['id'][0]."'");
        		teRedirect($link.'&msg=upd');
        	}
        	else {
        		$res = mysql_query("INSERT INTO ".DB_PREFIX."configtable (var_name,var_value) values('$var','$value')");
        		if(!$res)
        		{
        			print "<span style='color:red;font-weight:bold;'>Ошибка!!! По данной рубрике важное событие уже существует!!!</span> <br/><a href='javascript:history.go(-1);'>Вернуться</a>";
        		}
        		else teRedirect($link.'&msg=add');
        	}
        }else print "<span style='color:red;font-weight:bold;'>Необходимо выбрать рубрику и тип события</span> <br/><a href='javascript:history.go(-1);'>Вернуться</a>";
}
if($op1=='edit')
	print '<br/><a href="'.teGetUrlQuery("show=conf","step=5").'">Добавить событие</a>';
print '<br/><br/><a href="'.teGetUrlQuery("show=conf","step=9").'">Шаблоны писем</a>';
function get_emails($rubric)
{
	$res = mysql_query("select var_value from ".DB_PREFIX."configtable where var_name='notify_".$rubric."'");
	if(mysql_num_rows($res)>0)
	{
		$row = mysql_fetch_row($res);
		$arr = explode("|",$row[0]);
		if(!empty($arr[1]))return $arr[1];
	}
	return false;
}
print "</div>";
?>