<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*
*  ООО "Универсал-Сервис"
*
**********/
	
	/*выбираем id и дату у всех людей, кто просмотрел данное событие*/
	curbase();
	if(isset($_GET['event_id'])){
	   $res = $database->query("
		SELECT ID_USER, tdate
		FROM ".DB_PREFIX."rubric_events 
		WHERE ID_GOOD='".$_GET['event_id']."' order by tdate desc");
	}

	print "<table border='1' cellspacing='0'>";
	print "<tr>";
	print "<th>ID</th>";
	print "<th>Логин</th>";
	print "<th>ФИО</th>";
	print "<th>Что делал</th>";
	print "<th>Дата</th>";
	print "</tr>";
	
	/*для получения логина и фио подключаемся к базе древа*/
	combase();

	while(list($ID_USER, $tdate) = mysql_fetch_row($res))
	{
		$tdate =  date('H:i:s d.m.Y', $tdate);
		if($ID_USER!=0)
		{
			$res_user_fio = $database->query("SELECT user_login,user_name,user_sname,user_pname FROM ".DB_PREFIX."users WHERE ID_USER='".$ID_USER."'");
			list($user_login,$user_name,$user_sname,$user_pname) = mysql_fetch_row($res_user_fio); 		
			print "<tr><td>$ID_USER</td><td>$user_login</td><td>$user_sname $user_name $user_pname</td><td>просмотрел</td><td>$tdate</td></tr>";
		}
		else 
		{
			$user_login="root";
			print "<tr><td>$ID_USER</td><td>$user_login</td><td align='center'>-</td><td>просмотрел</td><td>$tdate</td></tr>";
		}
	}
	
	/*вывод истории из changes*/
	curbase();
	if(isset($_GET['event_id'])){
	   $res_changes = $database->query("
		SELECT *
		FROM ".DB_CHANGES." 
		WHERE change_table='cprice_goods' and change_row='".$_GET['event_id']."'
		ORDER BY ID_CHANGE DESC
	");
	}
	
	combase();
	while($line = mysql_fetch_array($res_changes)){
		$us_id = (int)$line['ID_USER'];
		$us = $database->getArrayOfQuery("SELECT * FROM cprice_users WHERE ID_USER=$us_id",MYSQL_ASSOC);
		$tp = ($line['change_type']==1)?"<font color='green'>добавил</font>":"<font color='blue'>изменил</font>";
		$dt = $line['change_dt'];
		$dt = substr($dt,11)." ".substr($dt,8,2).".".substr($dt,5,2).".".substr($dt,0,4);
		
		$tag = "span";
		if($us['user_deleted']==1){
			$tag = "strike";
		}
		if($us['user_visible']==0){
			$tag = "font color='red'";
		}
		if($us_id==0){
			$tag = "font color='darkblue'";
		}
		
		if(substr($us['user_name'],-1)=='а'){
			$suf = 'а';
		} else {
			$suf = '';
		}
		
		print "<tr>";
		print "<td>".getIdToPrint("users",$us_id)."</td>";
		print "<td><$tag>".(($us_id==0)?"root":"$us[user_login]")."</$tag></td>";
		print "<td><$tag>".(($us_id==0)?"<div align='center'>-</div>":"$us[user_sname] $us[user_name] $us[user_pname]")."</$tag></td>";
		print "<td><$tag>$tp$suf</$tag></td>";
		print "<td><nobr>$dt</nobr></td>";
		print "</tr>";
		
	}
	
	print "</table>";
	die();
	
?>