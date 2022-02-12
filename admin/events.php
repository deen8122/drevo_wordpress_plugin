<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798
print "<br/><br/>";
print "<h2>Важные события по всем проектам</h2>";
print "<div align=center>";
foreach($hosts as $item)
{  if(isset($item['db_host']))
  {
	$database -> teDatabase($item['db_host'], $item['db_user'], $item['db_pass'], $item['db_name']);
	$res = $database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name like 'notify_%'");
	$i=0;
	$table='';
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
		$table .= '<tr><td>'.$name.'</td><td>'.$emails.'</td></tr>';
		$i++;
	}
	if(!empty($table))
		$table = '<h2>Проект: '.$item['name'].'</h2>'.'<table border="1">
			<tr><th>Рубрика</th><th>Емайлы</th></tr>
		'.$table.'</table><br/><br/>';
	print $table;
  }
}
combase();
print "</div>";
?>