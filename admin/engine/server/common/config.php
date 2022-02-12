<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
	/**********
	*  ООО "Универсал-Сервис"
	*
	*  Разработчик:  Teлeнкoв Д.С.
	*  e-mail: tdsweb@ya.ru
	*  ICQ: 398-518-940
	*  Тел.: +7 909 3481503
	**********/
	/*****
	* File `/engine/server/functions/config.php`
	*   function teSaveConfDB()
	*   function teGetConfDB()
	*
	* TDSCMS v1.0
	* tdssc@mail.ru
	*****/


// функции для запроса и сохранения конф.данных в базе


function teSaveConf($name,$cont){
	global $database;
	if( $line = $database -> getArrayOfQuery("SELECT var_value FROM ".DB_CONFIG." WHERE var_name='".$name."'") ){
		if( $line[0] != stripslashes($cont) ){
			$database -> query("UPDATE ".DB_CONFIG." SET var_value='".$cont."' WHERE var_name='".$name."'");
		}
	} else {
		$database -> query("INSERT INTO ".DB_CONFIG." (var_name,var_value) VALUES ('".$name."','".$cont."')");
	}
}
function teExistsConf($name){
	global $database;
	$sql = "SELECT var_value FROM ".DB_CONFIG." WHERE var_name='".$name."'";
	$res = $database -> query($sql);
	if(mysql_num_rows($res)>0){
		return true;
	} else {
		return false;
	}
}
function teGetConf($name){
	global $database;
	$arr = $database -> getArrayOfQuery("SELECT var_value FROM ".DB_CONFIG." WHERE var_name='".$name."'");
	return $arr[0];
}
function teGetConfs($name){
	global $database;
	$res = $database -> query("SELECT var_name, var_value FROM ".DB_CONFIG." WHERE var_name like '".$name."'");
	$arr = array();
	while($row = mysql_fetch_row($res)) $arr[$row[0]]=$row[1];
	return $arr;
}
?>