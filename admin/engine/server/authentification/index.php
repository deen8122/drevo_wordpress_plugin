<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* TDSCMS v1.0
* tdssc@mail.ru
*****/

require "{$CURFLD}strings.php";

// ������ ������
include ROOT_FLD."engine/data/hosts.php";

session_start();

// ����� �����
if(isset($_GET["user_exit"])){
	$_SESSION['access'] = array();
	session_unset();
	session_destroy();
	teRedirect("../");
}

// md5() ������ �� ����
define("DB_ID",143);
addGet("curbase",143);
//define("RTPSWD","93ef2987ed4dafffa02eae8b1f5645d3");
define("RTPSWD", "ec121bd2fe510a676b55393692791b16");

curbase();

global $_USER;
$login = '';
// ���� ������� ����� � ������
/*
if(!empty($_POST['user_passwd']) && !empty($_POST['user_login'])){
	$pwd = $_POST['user_passwd'];
	$_POST['user_passwd'] = md5($_POST['user_passwd']);
	$_POST['user_login'] = trim(str_replace("'","\'",strip_tags($_POST['user_login'])));

	$login = trim(str_replace("'","\'",strip_tags($_POST['user_login'])));
	$base = @$_COOKIE['base'];
}
*/

// �� ��������� �����������
$_USER['auth'] = true;

// ������ ���������
$cap = false;

// ���� � ������ ���� ������
if( empty( $_SESSION['user_passwd'] ) || isset($_POST['user_login'])){
	$_USER['user_login'] = @trim(str_replace("'","\'",strip_tags($_POST['user_login'])));
	$_USER['user_passwd'] = @$_POST['user_passwd'];
} else {
	$_USER['user_login'] = $_SESSION['user_login'];
	$_USER['user_passwd'] = $_SESSION['user_passwd'];
}

// ���� $_USER ����, ����������� �������
if( empty( $_USER['user_passwd'] ) && empty( $_USER['user_login'] ) ){
	$_USER['auth'] = false;
}


if(empty($_USER['user_login'])){
	$_USER['auth'] = false;
	//$err = "�� ������ �����!";
} else {
	// ����� � ���� �����
	teSetCookie("user_login",$_USER['user_login']);
	/*
	list($uid) = $database->getArrayOfQuery("SELECT ID_USER FROM cprice_users WHERE user_login='".$_USER['user_login']."'");
	if(!$database->getArrayOfQuery("
		SELECT ua_viewpages
		FROM cprice_users_activity
		WHERE ID_USER=$uid and ua_ip='".$_SERVER['REMOTE_ADDR']."' and ua_dt_end >= NOW()-INTERVAL 1 MONTH
	")){
		$_USER['auth'] = false;
		$cap = true;
	}
	/**/
}

if(empty($_USER['user_passwd'])){
	$_USER['auth'] = false;
	//$err = "������� ������!";
}

// ���� ������ ������ (�� �����, ���������� ��)
if(!empty($_POST['user_passwd'])){
	if(empty($_POST['cap'])){
		$_USER['auth'] = false;
		$err = "������� ����� � ��������";
		$cap = true;
	} else {
		if( @$_POST['cap']!=@$_SESSION["checkimage"] && @$_POST['cap']!=md5("ipx".date("DMyA")) && md5(@$_POST['cap'])!=@$_SESSION["checkimage"] ){
			$_USER['auth'] = false;
			$err = "����� � �������� �� ������";
			$cap = true;
		}
	}
}

// ���� �����������, ��������� ����� � ������
if($_USER['auth']){
	// ���� root
	if($_USER['user_login']=="root"){
		if($_USER['user_passwd']==RTPSWD){
			$_SESSION['user_id'] = "0";
			$_SESSION['user_login'] = $_USER['user_login'];
			$_SESSION['user_passwd'] = $_USER['user_passwd'];

			$_USER['id'] = $_SESSION['user_id'];

			$id_last_row = -1;

		} else {
			$_USER['auth'] = false;
			$err = "�� ������ ������!";
			$cap = true;
		}
	} else {
	// ���� ���

		if(!$user = $database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE user_login='".$_USER['user_login']."'",MYSQL_ASSOC)){
			$_USER['auth'] = false;
			$err = "��� ������� �� ������!";
			$cap = true;
		}

		if($user['user_deleted']==1){
			$_USER['auth'] = false;
			$err = "��� ������� �����!";
			$cap = true;
		}
		if($user['user_visible']==0){
			$_USER['auth'] = false;
			$err = "��� ������� ��������!";
			$cap = true;
		}
		if($user['user_passwd']!=$_USER['user_passwd']){
			$_USER['auth'] = false;
			$err = "�� ������ ������!";// (<a href=?restorepass>� ����� ������</a>)"; //��������� 21.01.2010, ������� �9741
			$cap = true;
		}
		if($_USER['auth']){
			$_SESSION['user_id'] = $user['ID_USER'];
			$_USER['id'] = $_SESSION['user_id'];
			$_SESSION['user_login'] = $user['user_login'];
			$_SESSION['user_passwd'] = $user['user_passwd'];

			$_USER['name'] = $user['user_name'];
			$_USER['sname'] = $user['user_sname'];
			$_USER['pname'] = $user['user_pname'];
			$_USER['email'] = $user['user_email'];
			$_USER['icq'] = $user['user_icq'];
			$_USER['telephone'] = $user['user_telephone'];

		}
	}

}


// ���� �� �����������
if(!$_USER['auth']){


	global $skinpath;

	// ��� ��������, �������� �� ����
	setcookie("cookie_exists", "yes", time()+30*24*3600);

	// ����� ������, ���� ����
	if(!empty($err)){
		$err = "<div align=center style=\"background-color:#FFEEEE\">".$err."</div>";
	}

	// ������� ����� ����� � ������
	$tmpl = new teTemplate;
	$tmpl -> addToVar("window_title","403. ��� ������� ����� ��������������.");
	$tmpl -> addToVar("err",@$err);
	$tmpl -> addToVar("page_title","������� ���������� ������");
	$tmpl -> addToVar("form_title","����� �����������");
	$tmpl -> addToVar("user_login_caption","�����");
	$tmpl -> addToVar("user_login",strip_tags(@$_COOKIE['user_login']));
	$tmpl -> addToVar("user_passwd_caption","������");

	// ������� ����� (��� �� �������)
	if( !$cap ){
		$_SESSION["checkimage"] = md5("ipx".date("DMyA"));
		$tmpl -> addToVar("cap_caption","");
		$tmpl -> addToVar("cap_input","<input type=\"hidden\" name=\"cap\" value=\"".$_SESSION["checkimage"]."\" />");
	} else {
		$tmpl -> addToVar("cap_caption","<img src=\"/checkimage.php\" alt=\"1234\" title=\"������� ����� � ���� �������� � ���� ������\"/>");
		$tmpl -> addToVar("cap_input","<input type=\"text\" name=\"cap\" id=\"cap\" value=\"\"/>");
	}

	$tmpl -> addToVar("submit_caption","�����");

	die($tmpl -> getText($skinpath."authentification.htm"));

} else {
	// ���� ��� ��� �����������


	// ��������� ������ ������ ��
   	list($access) = $database->getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE database_id=".DB_ID." && ID_USER=".(int)@$_USER['id']." and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and access_type>0");
    if(!empty($access) || $_USER['user_login']=="root")
    {		if($access==1)
		{
			$res = $database -> query("SELECT ID_RUBRIC_TYPE,access_type FROM ".DB_PREFIX."users_privilegies WHERE database_id=".DB_ID." and ID_RUBRIC=0 and ID_RUBRIC_TYPE>0");
			while($line=mysql_fetch_array($res)){
				if($line[1]==0) {
					if($database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE database_id=".DB_ID." and ID_RUBRIC_TYPE=".$line[0]." and access_type>0")){
						$line[1] = 1;
					}
				}
				$_USER['rubric_types'][$line[0]] = $line[1];
			}
	    }
  		define("SITE_FLD",$hosts[DB_ID]['folder']);
		//define("DATA_FLD",SITE_FLD.$hosts[DB_ID]['data']);
		define("URL_FLD",$hosts[DB_ID]['url']);
		define("URLDATA_FLD",URL_FLD.$hosts[DB_ID]['data']);

		if( $_USER['user_login']=="root" || $access==3 ){
			$_USER['group'] = "1";
		} else {
			$_USER['group'] = ($access==1)?3:(($access==3)?1:2);
		}
		$s = $hosts[DB_ID]['name'].", ";


		$s .= ( ($_USER['group']==1)?"�����":"" ).( ($_USER['group']==1 || $_USER['group']==2)?"�������������":"������������" )." ";

		if($_USER['group']==1){
			$s .= $_USER['user_login'];
		} else {
			$s .= $_USER['sname']." ".substr($_USER['name'],0,1).".".substr($_USER['pname'],0,1).". (".$_USER['user_login'].")";
		}

		$_template->addVar("top_title", $s);
		$csspath = "";
		if($hosts[DB_ID]['siteversion']==1||$hosts[DB_ID]['siteversion']==3||$hosts[DB_ID]['siteversion']==4) $csspath = $hosts[DB_ID]['url']."skins/main/style.css";
		if($hosts[DB_ID]['siteversion']==2) $hosts[DB_ID]['url']."style.css";
		teAddJSScript("var CSSPATH = '".$csspath."'");

		if($_USER['group']==3){
			$_SESSION['access'][DB_ID] = array();
			$res = $database -> query("
				SELECT *
				FROM ".DB_PREFIX."users_privilegies
				WHERE ID_USER=".$_USER['id']." and database_id=".DB_ID."
			");
			while($ln = mysql_fetch_array($res)){
				$_SESSION['access'][DB_ID][$ln['ID_RUBRIC_TYPE']][$ln['ID_RUBRIC']] = array($ln['access_m'],$ln['access_v'],$ln['access_a'],$ln['access_e'],$ln['access_d']);
			}
		}
    }else
    {		$_SESSION['access'] = array();
		session_unset();
		session_destroy();
		setcookie("curbase");
		header("HTTP/1.0 404 Not Found");
		die;
    }
}

	$_USER['login'] = $_USER['user_login'];

echo 'auth';
?>