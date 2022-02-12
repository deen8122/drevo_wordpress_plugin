<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* TDSCMS v1.0 
* tdssc@mail.ru
*****/

function print_link_main($url="?pg=index"){
	
	//echo "<div align=right><a href='".$url."'>На главную</a></div>";
	
}

function print_link_up($url = "", $txt = "вверх"){
	if($url==""){
		$url = teGetUrlQuery("");
	}
	echo "<div><a href='".$url."'><img src='".DEEN_FOLDERS_URL."assets/images/btn-left.png' alt='Вверх'></a></div>";
	//echo "<div><a href='".$url."'>".$txt."</a></div>";
}

function setWindowTitle($str){
	global $_template;
	$_template -> addVar("title",$str." - ".teGetConf("title"));
}
$_template -> addVar("title","");

?>