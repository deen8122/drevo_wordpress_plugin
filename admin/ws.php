<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  История изменений БД в разрезе по ID
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/
	if($_GET['t']=='users')combase();
	if(isset($_GET['r'])){
	   $res = $database->query("
		SELECT *
		FROM ".DB_CHANGES."
		WHERE change_table='cprice_".$_GET['t']."' and change_row='".$_GET['r']."'
		ORDER BY ID_CHANGE DESC
	");
	}
	//вывод истории для раздела Конфиг
	else {
		$res = $database->query("
		SELECT *
		FROM cprice_changes_configtable
		WHERE change_table='cprice_".$_GET['t']."' and change_row='".$_GET['row']."'
		ORDER BY ID_CHANGE DESC
	");
	}

	print "<table border='1' cellspacing='0'>";
	print "<tr>";
	print "<th>ID</th>";
	print "<th>Логин (IP)</th>";
	print "<th>ФИО</th>";
	print "<th>Что делал</th>";
	print "<th>Когда &uarr;</th>";
	print "</tr>";

	while($line = mysql_fetch_array($res)){
		$us_id = (int)$line['ID_USER'];
		$us = $database->getArrayOfQuery("SELECT * FROM cprice_users WHERE ID_USER=$us_id",MYSQL_ASSOC);
		$tp = ($line['change_type']==1)?"<font color='green'>добавил</font>":"изменил";
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
		print "<td><$tag>".(($us_id==0)?"root":"$us[user_login]")." ($line[ip] )</$tag></td>";
		print "<td><$tag>".(($us_id==0)?"<div align='center'>-</div>":"$us[user_sname] $us[user_name] $us[user_pname]")."</$tag></td>";
		print "<td><$tag>$tp$suf</$tag></td>";
		print "<td><nobr>$dt</nobr></td>";
		print "</tr>";

	}
	print "</table>";
	if($_GET['t']=='users')
	{		curbase();
		print '<h2>Активность пользователя в данном проекте</h2>';
		$line = $database->getArrayOfQuery("SELECT count(*) FROM cprice_changes WHERE change_type=1 && ID_USER=".$_GET['r']);
		$add = (int)$line[0];
		$line = $database->getArrayOfQuery("SELECT count(*) FROM cprice_changes WHERE change_type=2 && ID_USER=".$_GET['r']);
		$edit = (int)$line[0];
		$line = $database->getArrayOfQuery("SELECT count(*) FROM cprice_changes WHERE change_type=3 && ID_USER=".$_GET['r']);
		$del = (int)$line[0];
		$line = $database->getArrayOfQuery("SELECT count(*) FROM cprice_changes_configtable WHERE change_type=1 && ID_USER=".$_GET['r']);
		$add += (int)$line[0];
		$line = $database->getArrayOfQuery("SELECT count(*) FROM cprice_changes_configtable WHERE change_type=2 && ID_USER=".$_GET['r']);
		$edit += (int)$line[0];
		$line = $database->getArrayOfQuery("SELECT count(*) FROM cprice_changes_configtable WHERE change_type=3 && ID_USER=".$_GET['r']);
		$del += (int)$line[0];
		print 'Добавления записей: '.$add.'<br/>';
		print 'Изменения записей: '.$edit.'<br/>';
		print 'Удаления записей: '.$del.'<br/>';
	}
	die();

?>