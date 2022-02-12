<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
print '<div style="clear:both;"></div><div align="center"><br/>';
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798
@$id = (int)$_GET['id'];
if($id>0)
{	print '<a href="javascript:history.go(-1);">Назад</a><br/><br/>';
	$line = $database -> getArrayOfQuery("select * from visitors WHERE id=".$id,MYSQL_ASSOC);
    print '<b>IP:</b> '.$line['ip'].'<br/>';
    print '<b>Браузер:</b> '.$line['browser'].'<br/><br/>';

	$res = $database -> query("select * from visitors_pages WHERE id_visitor=$id order by tdate",MYSQL_ASSOC);
	print '<table border="1">';
	print '<tr><th>Дата/время</th><th>Страница</th><th>Время пребывания</th></tr>';
	$arr = array();$i=0;
	while($row = mysql_fetch_array($res))
	{
		//$line2 = $database -> getArrayOfQuery("select count(id) from visitors_pages WHERE id_visitor=".$row['id']);
		$arr[$i]['str']='<td>'.date("d.m.Y H:i:s",$row['tdate']).'</td><td><a href="'.substr($hosts[DB_ID]['url'],0,(strlen($hosts[DB_ID]['url'])-1)).$row['page'].'" target="_blank">'.$row['page'].'</a></td>';
		$arr[$i++]['time']=$row['tdate'];
		//print '<tr><td>'.date("d.m.Y H:i:s",$row['tdate']).'</td><td><a href="'.substr($hosts[DB_ID]['url'],0,(strlen($hosts[DB_ID]['url'])-1)).$row['page'].'">'.$row['page'].'</a></td></tr>';
	}
	$i=0;
	foreach($arr as $item)
	{
		if(isset($arr[($i+1)]['time'])){
			$tm = $arr[($i+1)]['time']-$item['time'];
			if($tm<60)$tm_str=sprintf("00:%02d",$tm);
			elseif($tm<3600)
			{
				$min = intval(($tm/60));
				$sec = $tm - $min*60;
				$tm_str=sprintf("%02d:%02d",$min,$sec);
			}else $tm_str ='больше часа';
			$time='<td>'.$tm_str.'</td>';
		}
		else $time='<td>&nbsp;</td>';
		print '<tr>'.$item['str'].$time.'</tr>';
		$i++;	}
	print '</table>';
}
else
{
$arr_m = array(1=>'Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь');
@$day = (int)$_GET['day'];
@$month = (int)$_GET['month'];
@$year = (int)$_GET['year'];
if($day<1 || $day>31) {$day=date("j");$dayt=date("d");}
else $dayt = ($day<10)? '0'.strval($day):$day;
if($month<1 || $month>12) {$month=date("n");$montht=date("m");}
else $montht = ($month<10)? '0'.strval($month):$month;
if($year<1)$year=date("Y");

$n_days=date("t",mktime(0,0,0,$month,$day,$year));
$bweek=date("w",mktime(0,0,0,$month,1,$year));
if($bweek==0)$bweek=7;
$cal='';
if($bweek!=1)
{
	$cal='<tr>';$i=1;
	while($i!=$bweek){
		$cal .= '<td>&nbsp;</td>';
		$i++;
	}
}
for($i=1;$i<=$n_days;$i++)
{
	$week=date("w",mktime(0,0,0,$month,$i,$year));
	if($week==1)$cal.='<tr>';
	if($i!=$day)$cal .= '<td><a href="'.teGetUrlQuery("pg=config","step=7","day=".$i,"month=".$month,"year=".$year).'">'.$i.'</a></td>';
	else $cal .= '<td><b>'.$i.'</b></td>';
	if($week==0)$cal.='</tr>';
}
if($week!=0)
{
	while($week!=7)
	{
		$cal .= '<td>&nbsp;</td>';
		$week++;
	}
	$cal .= '</tr>';
}

print '<table>
	<tr><td colspan="7"><a href="'.teGetUrlQuery("pg=config","step=7","day=1","month=".(($month==1)?12:($month-1)),"year=".(($month==1)?($year-1):$year)).'">&lt;</a> '.$arr_m[$month].' <a href="'.teGetUrlQuery("pg=config","step=7","day=1","month=".(($month==12)?1:($month+1)),"year=".(($month==12)?($year+1):$year)).'">&gt;</a></td></tr>
	<tr><td>пн</td><td>вт</td><td>ср</td><td>чт</td><td>пт</td><td>сб</td><td>вс</td></tr>
	'.$cal.'</table>';

print "<h2>Статистика посещений за $dayt.$montht.$year</h2>";
$bdate = mktime(0,0,0,$month,$day,$year);
$edate = mktime(23,59,59,$month,$day,$year);

@$p = (int) $_GET['p'];
$per_page=50;
$nums =numbers("select * from visitors WHERE tdate>$bdate && tdate<$edate",
			$p, $per_page, '', '&pg=config&amp;step=7&amp;day='.$day.'&amp;month='.$month.'&amp;year='.$year);
$res = $database -> query("select * from visitors WHERE tdate>$bdate && tdate<$edate order by tdate limit $nums[0], $per_page",MYSQL_ASSOC);
$tasks='';
$hour=0;$min=0;
$sel_task='';
$sel_rep='';$n_rep=0;
print '<table border="1">';
	print '<tr><th>Дата/время</th><th>IP</th><th>Браузер</th><th>Количество страниц</th><th>Проигрыватель</th></tr>';
while($row = mysql_fetch_array($res))
{	$line2 = $database -> getArrayOfQuery("select count(id) from visitors_pages WHERE id_visitor=".$row['id']);
	print '<tr><td>'.date("d.m.Y H:i:s",$row['tdate']).'</td><td>'.$row['ip'].'</td><td>'.$row['browser'].'</td><td><a href="'.teGetUrlQuery("pg=config","step=7","id=".$row['id']).'">'.$line2[0].'</td><td><a href="/page.php?db_id='.DB_ID.'&amp;id='.$row['id'].'" target="_blank">Смотреть</a></td></tr>';}
print '</table>';
print $nums[1];
}
print '</div>';
function i_a($href,$name){	return '<a href="'.$href.'">'.$name.'</a>';}
function numbers($qstr, $pg, $per_page=20, $page, $add='',$all_num=0)
{
//Проверка входных данных
if(empty($pg) || !is_numeric($pg) || $pg<1)$pg=1;
if($per_page<1)$per_page=1;
if(!empty($qstr))$num =  mysql_num_rows(mysql_query($qstr));
else $num=$all_num;

$num_of_page = ceil($num/$per_page);
$nums='';
if($num_of_page<10)
{
    if($num_of_page>1)
    {
	    for($i=1;$i <= $num_of_page;$i++)
	    {
	        //Если $i == номеру текущей странице, то
	        //выводим без ссылки:
	        if($i == $pg)
	        {
	            $nums.= "&nbsp;<b>".$i."</b>&nbsp;";
	        }
	        else
	        {
	            //Иначе, с ссылкой:
	            $nums.= '&nbsp;'.i_a($page.'?p='.$i.$add,$i).'&nbsp;';
	        }
	    }
    }
}
else
{
	$tek=$pg;
    if($tek<7)
    {
	    for($i=1;$i <= ($tek+6) && $i <= $num_of_page;$i++)
	    {
	        //Если $i == номеру текущей странице, то
	        //выводим без ссылки:
	        if($i == $pg)
	        {
	            $nums.= "&nbsp;<b>".$i."</b>&nbsp;";
	        }
	        else
	        {
	            //Иначе, с ссылкой:
	            $nums.= '&nbsp;'.i_a($page.'?p='.$i.$add,$i).'&nbsp;';
	        }
	    }
        if($num_of_page>($tek+7))$nums.='&nbsp;'.i_a($page.'?p='.($tek+7).$add,'>>').'&nbsp;';
   }
   else
   {
		if(($tek-7)>0)$nums.='&nbsp;'.i_a($page.'?p='.($tek-7).$add,'<<').'&nbsp;';
	    for($i=($tek-6);$i <= ($tek+7) && $i <= $num_of_page;$i++)
	    {
	        //Если $i == номеру текущей странице, то
	        //выводим без ссылки:
	        if($i == $pg)
	        {
	            $nums.= "&nbsp;<b>".$i."</b>&nbsp;";
	        }
	        else
	        {
	            //Иначе, с ссылкой:
	            $nums.= '&nbsp;'.i_a($page.'?p='.$i.$add,$i).'&nbsp;';
	        }
	    }
        if($num_of_page>($tek+8))$nums.='&nbsp;'.i_a($page.'?p='.($tek+8).$add,'>>').'&nbsp;';
   }
}
$start_page=$per_page*($pg-1);
//$num_arr=array($start_page,$nums);

return array($start_page, $nums, $num);
}
//}}========Нумерация страниц===================================================

?>