<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
	/**********
	*  ��� "���������-������"
	*
	*  �����������:  Te�e��o� �.�.
	*  e-mail: tdsweb@ya.ru
	*  ICQ: 398-518-940 
	*  ���.: +7 909 3481503
	**********/

	define("HOSTNAME", "http://".$_SERVER['SERVER_NAME']);
	define("HOST", HOSTNAME."".str_replace("index.php","",$_SERVER['PHP_SELF']));
	define("THIS_FLD",str_replace("\\","/",substr(realpath("./"),strlen(realpath(ROOT_FLD)))."/"));

	//define("DB_HOST","localhost");
	define("DB_USERNAME",DB_USER);
	define("DB_USERPASS",DB_PASSWORD);
	//define("DB_NAME","admin_irbiss");
	//define("DB_PREFIX","cprice_");
	
	define("COMMON_DB_HOST","localhost");
	define("COMMON_DB_USERNAME",DB_USER);
	define("COMMON_DB_USERPASS",DB_PASSWORD);
	define("COMMON_DB_NAME",DB_NAME);
	define("COMMON_DB_PREFIX","cprice_");
	//define('DB_ID',143);
	define("DB_ID",143);
//addGet("curbase",143);
	define("DB_CONFIG", DB_PREFIX."configtable");
	define("DB_CHANGES", DB_PREFIX."changes");
global $_USER;
$_USER['id'] = 1;
/***********************************************************************/
/*                              GETs � POSTs                           */
/***********************************************************************/
	define("G_PAGE",	"pg");
	define("G_IMG",		"img");
	define("G_LANG",	"lang");
	define("G_NUMBER",	"num");
	define("G_ERROR",	"err");
	define("G_DELETE",	"del");
	define("G_EDIT",	"edit");
		
/***********************************************************************/
/*                             �����, ����                             */
/***********************************************************************/
	define("F_ADD","add");
	

/***********************************************************************/
/*		                             			JS									*/
/***********************************************************************/
	
	define("JS_FLD", ROOT_FLD."engine/client/");

/***********************************************************************/
/*                            � � � � � �                              */
/***********************************************************************/
	
	// c���� ��������� � ������ ����� ( ������� * ��� �����) em
	define("TREE_LEFT", "2.5");
	
	define("__version",	"1.0");
	global $hosts;
	$hosts = array();
	$hosts[143] = array(
	'name' => DB_NAME,
	'db_host' => DB_HOST,
	'db_name' => DB_NAME,
	'db_user' => DB_USER,
	'db_pass' => DB_PASSWORD,
	'folder' => '../',
	'url' => '/',
	'data' => 'images/',
	'version' => 2.0,
	'siteversion' => 2,
	'unis' => false,
	'lang' => true
);
?>