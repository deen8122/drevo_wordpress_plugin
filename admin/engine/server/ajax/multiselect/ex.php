<?

if(isset($_POST['h'])){
	mysql_connect('','root','1');
	mysql_select_db('test');
	mysql_query('SET NAMES cp1251');
	
	if(isset($_POST['addr1_other'])){
		mysql_query("INSERT INTO multiLevelData(parent,value) VALUES ($_POST[addr1],'$_POST[addr1_other]')");
		$_POST["addr1"] = mysql_insert_id();
	}
	echo "Было выбрано: <b>";
	$par = $_POST["addr1"];
	$out = '';
	while($par>0){
		$res = mysql_query("SELECT ID, value, parent FROM multiLevelData WHERE ID=$par");
		if(mysql_num_rows($res)>0){
			$line = mysql_fetch_array($res,MYSQL_NUM);
			$out = "$line[1] ->" . $out;
			$par = $line[2];
		} else break;
	}
	echo $out;//substr($out,strlen($out)-2,2);
	echo "</b>";

}
?>
<html>
<head>
	<script type="text/javascript" language="javascript" src="ajax.js"></script>
</head>
<body>
	<form method="post">	
		<input type="hidden" name="h" />
		<div>Адрес: <span id="par"></span> : <span id="th"></span></div>
		
		<input type="submit">
	</form>
	<script>
		selectAreaUpdate('par', 'th', '1.php?parent=', 0, 'addr1'); 
	</script>
</body>
</html>
