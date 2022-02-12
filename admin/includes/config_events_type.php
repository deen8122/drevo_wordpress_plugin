<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798
print "<br/><br/>";
print "<h2>Шаблоны писем для важных событий</h2>";
print "<div align=center>";
$vname='';
$vtheme='';
$vmsg='';
$op1='';
$link = teGetUrlQuery("show=conf","step=9");
if(isset($_GET['id']))
{	$id = $_GET['id'];	$op1 = $_GET['op1'];
	if($op1=='del2')
	{		$database -> query("DELETE FROM ".DB_PREFIX."configtable where var_name='".$id."'");
		teRedirect($link);	}
}
$res = $database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name like 'type_event_%'");
$i=0;
$table='';
while($row=mysql_fetch_array($res))
{	$tid = $row['var_name'];	$val = $row['var_value'];
	$arr1 = explode("|",$val);
	$name = $arr1[0];
	$arr2 = explode("$#",$arr1[1]);
	$theme = $arr2[0];	$msg = $arr2[1];
	if($op1=='edit')
	{	  if($id==$tid)
	  {
		$vname=$name;
		$vtheme=$theme;
		$vmsg=$msg;
	  }	}
	if($op1=='del')
	{
	  if($id==$tid)
	  {	  	print 'Вы уверены что собираетесь удалить шаблон письма, '.$name.': <a href="'.$link.'&op1=del2&id='.$tid.'">ДА</a><br/><br/><br/>';
	  }
	}
	$table .= '<tr><td>'.getIdToPrint_config("configtable",$row['var_name']).'</td><td>'.$name.'</td><td><b>'.$theme.'</b> '.$msg.'</td><td><a href="'.$link.'&op1=edit&id='.$tid.'">Изменить</a> <a href="'.$link.'&op1=del&id='.$tid.'">Удалить</a></td></tr>';
	$i++;}
if(!empty($table))
	$table = '<table border="1">
		<tr><th>ID</th><th>Название</th><th>Письмо уведомления</th><th>Действие</th></tr>
	'.$table.'</table><br/><br/>';
print $table;

$ln = mysql_fetch_array($res);
			$frm2 = new teForm("form2","post");
	if($op1=='edit'){		$frm2->addf_hidden("id", $id);
		$frm2->addTitle("<b>Изменить тип события</b>");
	}
	else
	{		$frm2->addTitle("<b>Добавить тип события</b>");
		$frm2->setSubmitCaption("Добавить");

	}
            $frm2->addf_text('name', 'Название шаблона письма',$vname);
            $frm2->addf_text('theme', 'Тема письма',$vtheme);
            $frm2->addf_desc('theme', 'Здесь можно использовать переменные:<br/>%name% - имя выполнившего важное событие<br/>%site_name% - url сайта<br/>%date% - дата написания письма');
            $frm2->addf_text('msg', 'Тело письма',$vmsg,true);
            $frm2->addf_desc('msg', 'Здесь можно использовать переменные:<br/>%name% - имя выполнившего важное событие<br/>%site_name% - url сайта<br/>%body% изменяемое тело письма<br/>%date% - дата написания письма');
		    $frm2->setf_require('name','theme');
			$frm2->send();
if(isset($_POST['name']))
{
		$name=$_POST['name'][0];
		if(!empty($name))
        {
        	$N = $i;
        	$var = 'type_event_'.($N+1);
        	$theme = $_POST['theme'][0];
        	$msg = $_POST['msg'];
        	$value = $name.'|'.$theme.'$#'.$msg;
        	if(isset($_POST['id']))$database -> query("UPDATE ".DB_PREFIX."configtable set var_value='$value' WHERE var_name='".$_POST['id'][0]."'");
        	else $database -> query("INSERT INTO ".DB_PREFIX."configtable (var_name,var_value) values('$var','$value')");
			teRedirect($link);
        }else print "<span style='color:red;font-weight:bold;'>Необходимо заполнить поле названия шаблона письма</span> <br/><a href='javascript:history.go(-1);'>Вернуться</a>";
}
if($op1=='edit')
	print '<br/><a href="'.$link.'">Добавить тип события</a>';

print "</div>";

?>