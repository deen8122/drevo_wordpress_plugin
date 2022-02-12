<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798

print "<h2>Проверка ссылок</h2>";
print "<div align=center>";

$link = teGetUrlQuery();
$id=0;
$op='';
if(isset($_POST['id']))$id = (int)$_POST['id'];
if(isset($_GET['id']))$id = (int)$_GET['id'];
if(isset($_GET['op1']))$op = $_GET['op1'];
if($id>0)
{
}

combase();
switch($op)
{
	case 'check':
		if($id>0)$res = $database -> query("SELECT * FROM links where database_id='$id'");
		else $res = $database -> query("SELECT * FROM links");
		$content='';
		while($row=mysql_fetch_array($res))
		{
			$bd = $row['database_id'];
            $url = $hosts[$bd]['url'];
			$link1 = $row['link'];
			$url = substr($url,0,(strlen($url)-1));
			$chk=false;
            @ $fp = fopen($link1, "r");
            if($fp){
               while (!feof ($fp)) {
                $line = fgets ($fp, 4096);
                if(preg_match ("/".$url."/i", $line)) {
                    $content .= $link1.': Проверка наличия нашей ссылки('.$url.') пройдена <br/><br/>';
	  	      		$chk=true;
                    break;
                }
               }
               if(!$chk) $content .=$link1.': <span style="color:red">Проверка наличия нашей ссылки('.$url.') не пройдена</span>'
                   .'<br><br>';
            }else $content.=$link1.': '.'<span  style="color:red">Ссылка не доступна.</span>'
               	.'<br><br>';
			$content .= '<a href="'.$link1.'" target=_blank>Проверить самостоятельно</a><br><br>';
   			$content .= '<hr />';

		}
		print $content;
        print '<br/><br/><a href="'.$link.'&id='.$id.'">Вернуться</a>';
	break;
	case 'del':
		$res = $database -> query("SELECT * FROM links where id='$id'");
		while($row=mysql_fetch_array($res))
			$id_bd = $row['database_id'];
		$database -> query("DELETE FROM links where id=".$id);
		teRedirect($link.'&id='.$id_bd);
	break;
	case 'edit2':
	if(isset($_POST['link']))
	{
		if(!empty($_POST['link']))$database -> query("UPDATE links set link='".$_POST['link']."',inform='".mysql_escape_string($_POST['inform'])."' where id=".$id);
		$res = $database -> query("SELECT * FROM links where id='$id'");
		while($row=mysql_fetch_array($res))
			$id_bd = $row['database_id'];
		teRedirect($link.'&id='.$id_bd);
	}
	break;
	case 'add':
	if(isset($_POST['link']))
	{
		$pr = $_POST['pr'];
		$j=count($hosts);
		for($i=1;$i<=$j;$i++)
		{
			if(isset($hosts[$i]))
			{
				if($pr[$i]==1)
					if(!empty($_POST['link']))
						$database -> query("INSERT INTO links (database_id, link, inform) values('$i','".$_POST['link']."', '".mysql_escape_string($_POST['inform'])."')");
			}else $j++;
		}

		teRedirect($link.'&id='.$id);
	}
	break;
	case 'edit':
		$res = $database -> query("SELECT * FROM links where id='$id'");
		$table='';
		while($row=mysql_fetch_array($res))
		{
			print '<h3>Изменение ссылки проекта: '.$hosts[$row['database_id']]['name'].'</h3>';
			print '<form action="'.$link.'&op1=edit2" method="post">';
			print 'Где находится ссылка: <input type="text" name="link" value="'.$row['link'].'" />';
			print '<br/>Дополнительная информация:<br> <textarea name="inform" cols="50" rows="7">'.$row['inform'].'</textarea>';
			print '<input type="hidden" name="id" value="'.$id.'" />';
			print '<br/><br/><input type="submit" value="Сохранить" />';
			print '</form>';
			$id_bd = $row['database_id'];
		}
        print '<a href="'.$link.'&id='.$id_bd.'">Вернуться</a>';
	break;
	default:
		$select ='';
		$select2='';
		$j=count($hosts);
		for($i=1;$i<=$j;$i++)
		{
			if(isset($hosts[$i]))
			{
				if($id==0 && empty($select))$id = $i;
				if($id==$i)$select.='<option value="'.$i.'" selected="selected">'.$hosts[$i]['name'];
				else $select.='<option value="'.$i.'">'.$hosts[$i]['name'];
				$select2.='<input type="checkbox" name="pr['.$i.']" value="1" '.($id==$i ?'checked="checked"' :'').' />'.$hosts[$i]['name'].' ';
			}else $j++;
		}
		$select ='<select name="id" onChange="this.form.submit();">'.$select.'</select>';
		print '<form action="'.$link.'" method="post">Выберите проект: ';
		print $select;
		print '</form>';
		$res = $database -> query("SELECT * FROM links where database_id='$id'");
		$i=0;
		$table='';
		while($row=mysql_fetch_array($res))
		{
			$table .= '<tr><td><a href="'.$row['link'].'" target="_blank">'.$row['link'].'</a></td><td>'.$row['inform'].'&nbsp;</td><td><a href="'.$link.'&op1=edit&id='.$row['id'].'">Изменить</a> <a href="'.$link.'&op1=del&id='.$row['id'].'">Удалить</a></td></tr>';
			$i++;
		}
		if(!empty($table))
			$table = '<table border="1">
				<tr><th>Ссылка</th><th>Инфо</th><th>Действие</th></tr>
			'.$table.'</table><br/><br/>';
		print $table;

		print "<h3>Добавить ссылку</h3>";
		print '<form action="'.$link.'&op1=add" method="post">';
		print 'В проект: '.$select2;;
		print '<br/><br/>Где находится ссылка: <input type="text" name="link" value="http://" />';
		print '<br/>Дополнительная информация:<br> <textarea name="inform" cols="50" rows="7"></textarea>';
		print '<br/><br/><input type="hidden" name="id" value="'.$id.'" />';
		print '<input type="submit" value="Добавить" />';
		print '</form>';

		print "<h3>Проверка наличия</h3>";
		print '<a href="'.$link.'&op1=check&id='.$id.'">Проверить наличия ссылок текущего проекта</a><br/><br/>';
		print '<a href="'.$link.'&op1=check">Проверить наличия ссылок всех проектов</a>';
	break;
}
curbase();

print "</div>";
?>