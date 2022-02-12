<?

	global $frm;
	//global $page_arr;
	$_status = 10;
	define("ROOT_FLD", dirname( __FILE__ ).'/');
	//
	$arr = wp_upload_dir();
	//print_r($arr);
	define("DATA_FLD",$arr['basedir'].'/');
	//print_r($_SERVER);
	define("URLDATA_FLD",$arr['baseurl'].'/');
	define("VERSION", "0");
	$CUR_ROOT=1;
	
	require_once ROOT_FLD."engine/index.php";
?>