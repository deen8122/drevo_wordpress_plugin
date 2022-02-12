<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* TDSCMS v1.0
* tdscms@mail.ru
*****/
global $_CONF;
if( $_CONF['metadata']==1 )
	require_once "{$CURFLD}main.php";

?>