<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  все понятно из названий функций

*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdsweb@ya.ru
*  ICQ: 398-518-940 
*  Тел.: +7 909 3481503
**********/


	function getVar($varname, $valid = true){
		if( isset($_SESSION[$varname]) ){
			$return = $_SESSION[$varname];
		} elseif( isset($_POST[$varname]) ){
			$return = $_POST[$varname];
		} elseif( isset($_GET[$varname]) ){
			$return = $_GET[$varname];
		} elseif( isset($_COOKIE[$varname]) ){
			$return = $_COOKIE[$varname];
		} else {
			return false;
		}
		if($valid){
			$return = strip_tags($return);
			$return = trim($return);
		}
		return $return;
	}
	function getInput($varname, $valid = true){
		if( isset($_POST[$varname]) ){
			$return = $_POST[$varname];
		} elseif( isset($_GET[$varname]) ){
			$return = $_GET[$varname];
		} elseif( isset($_SESSION[$varname]) ){
			$return = $_SESSION[$varname];
		} elseif( isset($_COOKIE[$varname]) ){
			$return = $_COOKIE[$varname];
		} else {
			return false;
		}
		if($valid){
			$return = strip_tags($return);
			$return = trim($return);
		}
		return $return;
	}
	
	function teSetCookie( $varname, $value, $time = 259200 ){
		global $_SESSION;
		global $_COOKIE;
		
		setcookie($varname,$value,time()+$time);
		$_COOKIE[$varname] = $value;
		$_SESSION[$varname] = $value;
	}
	
	function unsetVar($varname){
		unset($_GET[$varname]);
		unset($_POST[$varname]);
		unset($_SESSION[$varname]);
		setcookie($varname,NULL,time());
		unset($_COOKIE[$varname]);
	}
	
?>