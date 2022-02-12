<?
	
	mysql_connect('','root','1');
	mysql_select_db('test');
	mysql_query('SET NAMES cp1251');
	
	header('Content-Type: text/xml');
	header('Cache-Control: must-revalidate, get-check=0, post-check=0, pre-check=0');
	
	echo '<?xml version="1.0" encoding="windows-1251"?>';
	echo '<response>';

	(isset($_GET['parent'])) ? $parent = $_GET['parent'] : $parent = 0;

	$par = $parent;
	$out = '';
	while($par>0){
		$res = mysql_query("SELECT ID, value, parent FROM multiLevelData WHERE ID=$par");
		if(mysql_num_rows($res)>0){
			$line = mysql_fetch_array($res,MYSQL_NUM);
			$out = "<p_id>$line[2]</p_id><p_val>$line[1]</p_val>" . $out;
			$par = $line[2];
		} else break;
	}
	echo $out;

	
	$res = mysql_query("SELECT ID, value FROM multiLevelData WHERE parent=$parent ORDER BY value");	
	
	while($line = mysql_fetch_array($res,MYSQL_NUM)) echo "<value><id>$line[0]</id><str>$line[1]</str></value>";

	echo '</response>';
?>