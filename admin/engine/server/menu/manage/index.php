<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* TDSCMS v1.0 
* tdssc@mail.ru
*****/
	
	function addMenu($url,$txt,$color){
		global $_template;
		if( strpos($_SERVER['REQUEST_URI'],$url) ){
			if( preg_match("/".quotemeta($url)."$/i", $_SERVER['REQUEST_URI']) ){
				$txt = "<a><b><span>".$txt."</span></b></a>";
			} else {
				$txt = "<a href='$url'><b><span>".$txt."</span></b></a>";
			}
		} else {
			$txt = "<a href='$url'><span>".$txt."</span></a>";
		}
		$_template->addToVar("topmenu","<li style='background-color:$color;'>$txt</li>");
	}

if($USER['group']<=2){
	//addMenu(teGetUrlQuery("=users"),"��������","#EFFFEF");
}
if($USER['group']<=3){
	//addMenu(teGetUrlQuery("=rubric","type=0"),"�������","#FFFFEF");
	$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 ORDER BY ID_RUBRIC_TYPE");
	while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
		addMenu(teGetUrlQuery("=rubric","type=".$line['ID_RUBRIC_TYPE']),$line['rubrictype_name'],"#FFFFEF");
	}
	/*
	if(teGetConf("goods_enable")) addMenu(teGetUrlQuery("=rubric","type=1"),"������","#FFFFEF");
	if(teGetConf("service_enable")) addMenu(teGetUrlQuery("=rubric","type=2"),"������","#FFFFEF");
	*/
	addMenu(teGetUrlQuery("=filter"),"������","#FFFFEF");
	addMenu(teGetUrlQuery("=xsl"),"Excel","#FFFFEF");
}
if($USER['group']<=2){
	addMenu(teGetUrlQuery("=news_templates"),"�������","#EFFFEF");
	//addMenu(teGetUrlQuery("=metadata"),"SEO","#EFEFFF");
	addMenu(teGetUrlQuery("=changes"),"Changes","#EFEFFF");
	addMenu(teGetUrlQuery("=config"),"������������","#EFEFFF");
}
/*
if($USER['group']<=3){
	addMenu(teGetUrlQuery("=change_pass"),"�������� ������","#FFEFEF");
	addMenu(teGetUrlQuery("user_exit"),"�����","#FFEFEF");
}
*/
	$_template->addToVar("help","");
	
	define('helpprogrampath',$CURFLD);
	
	function addHelp($filename){
		global $_template;
		/*teAddJSFile(helpprogrampath."highslide-with-html.packed.js"); 
		teAddJSScript("
			hs.graphicsDir = '".helpprogrampath."/graphics/';
			hs.outlineType = 'rounded-white';
			hs.outlineWhileAnimating = true;
		");
		teAddCSSFile(helpprogrampath."highslide.css");
		*/
		$_template->addVar("help","<div id='help'><a href='./help/".$filename."' onclick=\"return hs.htmlExpand(this, { objectType: 'iframe' } )\"><b>������� �� �������� �������</b></a></div>");
		
	}
?>