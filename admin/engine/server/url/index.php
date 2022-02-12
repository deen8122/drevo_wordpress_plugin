<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}

/*****
* function redirect()
* @param string Url link
*****/
function teRedirect( $url ){
	if(!empty($_GET['PHPSESSID'])) $url .= (strpos($url,"?")?"&":"?")."PHPSESSID=".$_GET['PHPSESSID'];
	if(headers_sent()){
		@include_once ROOT_FLD."/engine/server/html/func_js.php";
		print teAddJSScript($code = "document.location.href='$url'");
	} else {
		header("Location: $url");
		die;
	}
}


function teGoToBack($i = 1){
	$s1 = ob_get_contents();
	ob_end_clean();
	unset($s1);
	die("
		<html><head><script>history.go(-$i);</script><noscript>�������� Javascript ����������!!!</noscript></head></html>
	");
}


/*****
* function get_url_query()
* @params string Parameters query page
* @return string
*****/
// ��������� ����� �� ����������
function teGetUrlQuery(){
	global $page_arr;
	//print_r($page_arr);
	//echo G_PAGE;
	$out = "";
	$count = func_num_args();
	$list = func_get_args();
	$setpage = false; $page = '';
	$setop = false;
	$setnum = $setlang = $private = false;
	$ii = 0;

	global $teGetSessionVars;
	if(!in_array('nosession',$list))
	{
		$names = array();
		foreach($list as $item)
		{
			if(empty($item)) $item = '='.$page_arr[G_PAGE];
			if($item[0] == "=") $item = G_PAGE.$item;
			$names[] = substr($item,0,strpos($item,"="));

		}
		if(is_array($teGetSessionVars))
		foreach($teGetSessionVars AS $name => $value){
			if(!in_array($name,$names))
				$out .= $name."=".$value."&";
		}
	}
	for ($i = 0; $i < $count; $i++){
		if(is_integer(strpos($list[$i],G_NUMBER.'='))){
			$setnum = true;
		}
		if($list[$i]==G_NUMBER.'=0'){
			$setnum;
			continue;
		}
		if(is_integer(strpos($list[$i],G_LANG.'='))){
			$setlang = true;
		}
		if(empty($list[$i])) $list[$i] = '='.$page_arr[G_PAGE];
		if($list[$i][0] == "=") $list[$i] = G_PAGE.$list[$i];

		//$out .= urlencode($list[$i]);
		if($list[$i]!='nosession' && substr($list[$i],0,8)!='curbase=')
		{
			if($ii!=0) $out .= "&";
			$out .= $list[$i];
        }
		if(is_integer(strpos($list[$i],G_PAGE.'='))){
			$setpage = true;
			$page = $list[$i];
		}
		if(is_integer(strpos($list[$i],'op'))){
			$setop = true;
			$op = $list[$i];
		}
		$ii++;
	}
	if($setpage == false){
		if(!empty($out)) $out = '&'.$out;
		$out = G_PAGE.'='.$page_arr[G_PAGE].$out;
		$page = G_PAGE.'='.$page_arr[G_PAGE];
	}

	if( !empty($_GET['op']) && !strpos($out,'op') ){
		$out .= "&op=".$_GET['op'];
	}
	if( !empty($_GET['noop']) ){
		str_replace("op=","noop=",$out);
	}

	if(!$setnum & $page == G_PAGE.'='.$page_arr[G_PAGE] & isset($_GET[G_NUMBER])) $out .= '&'.G_NUMBER.'='.$_GET[G_NUMBER];

	if(empty($_COOKIE['cookie_exists'])){
		$out .= "&PHPSESSID=".session_id();
	}

	//return("?curbase=".(int)@getInput('curbase').'&page=dn-rubrics&'.$out);
	return('?page=dn-rubrics&'.$out);
}

function addGet($varname,$varvalue){
	global $teGetSessionVars;
	$teGetSessionVars[$varname] = $varvalue;

}
function delGet($varname){
	global $teGetSessionVars;
	unset($teGetSessionVars[$varname]);
}

?>