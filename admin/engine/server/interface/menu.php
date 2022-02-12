<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}

global $database;
// ����. ���������� ���� (���������)
$_template->addVar("topmenu","");
function addMenu($url,$txt,$style = ""){
	global $_template;
	if( strpos($url,'type='.$_GET['type'])!==false ){
		if( preg_match("/".quotemeta($url)."$/i", $_SERVER['REQUEST_URI']) ){
			$style.='active';
			$txt = "<a class='active'>".$txt."</a>";
		} else {
			$style.='active';
			$txt = "<a href='$url' class='active'>".$txt."</a>";
		}
	} else {
		$txt = "<a href='$url'>".$txt."</a>";
	}
	$_template->addToVar("topmenu","<li class='$style'>$txt</li>");
}


// �������� �.
	$user_unis=false;
	//������ �������
	$res = $database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name like 'notify_%'");
	$n=0;
	while($row=mysql_fetch_array($res))
	{
		$tid = intval(substr($row['var_name'],7,strlen($row['var_name'])));
		$val = $row['var_value'];
		$arr1 = explode("|",$val);
		if($hosts[DB_ID]['version']==1.0) $j=2;
		else $j=3;
		if(isset($arr1[$j])){
			$user_id=$_USER['id'];
			if($user_id==0)$user_id=-1;
			$rights = explode("$",$arr1[$j]);
			if(!in_array($user_id,$rights))
				continue;
		}

		list($cnt) = $database->getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."rubric_goods t1 inner join ".DB_PREFIX."goods t2 on t1.ID_GOOD=t2.ID_GOOD
			where t1.ID_RUBRIC=".$tid." && t1.rubricgood_deleted=0 && t2.good_deleted=0");
		list($cnt2) = $database->getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."rubric_goods natural join ".DB_PREFIX."goods natural join ".DB_PREFIX."rubric_events
			where ID_RUBRIC=".$tid." && rubricgood_deleted=0 && good_deleted=0 && ID_USER=".$_USER['id']);
		$i =  $cnt - $cnt2;
		$n+=$i;
	}
	$warnings = "";
	if($n>0){
		$warnings = '������ ������� ('.$n.')';
		$class = "";
		teAddJSScript("
			\$(document).ready(function(){
				window.setInterval(function(){\$('#warnings img').fadeOut().fadeIn()},1000);
				window.setInterval(function(){\$('#warnings').fadeOut().fadeIn()},5000);
			});
		");
	} else {
		$warnings = '������ �������';
		$class = "now";
	}
	$_template->addToVar("warnings","<a href='./' class='$class'><img src='{current_skin}images/s_warning.gif' alt='!' align='top' style='position:relative'/> $warnings</a>");

 // �������..
 $_template->addToVar("news","");
	global $hosts;
	$url = $hosts[DB_ID]['url'];
	$_template->addToVar("to_site","<a href='$url' target='_blank' title='������� �� ����'>��&nbsp;����</a>");


// ���� �������������
if($_USER['group']<=2){
	//addMenu(teGetUrlQuery("=users"),"��������","#EFFFEF");
}

// ���� ���� ���
if($_USER['group']<=3){
	// ����������
	$i=0;
	$iskl="";

	$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 ".$iskl." ORDER BY ID_RUBRIC_TYPE");
	while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
		$acc = 1;
		if($_USER['group']==3)
			list($acc) = $database -> getArrayOfQuery("
				SELECT access_type
				FROM ".DB_PREFIX."users_privilegies
				WHERE ID_USER=".$_USER['id']." and database_id=".DB_ID." and ID_RUBRIC_TYPE=".$line['ID_RUBRIC_TYPE']."
			");
		if($acc>0)
		{
			if($line['rubrictype_maxlevel']==10)
			{
				$url = teGetUrlQuery("=".$line['rubrictype_i_s']);
			}
			else $url = teGetUrlQuery("=rubric","type=".$line['ID_RUBRIC_TYPE']);
			addMenu($url,$line['rubrictype_name'],"");
			$i++;
		}
	}
}




// ����� ��� ������ � ������������ � ���
$accnews = 1;$accseo = 1;
if($_USER['group']==3)
{
	$accnews = 0;$accseo = 0;
	list($accseo) = $database -> getArrayOfQuery("
		SELECT access_type
		FROM ".DB_PREFIX."users_privilegies
		WHERE ID_USER=".$_USER['id']." and database_id=".DB_ID." and ID_RUBRIC_TYPE=999999999
	");
	curbase();
}
// ���� ���� ������, �� �������
//if($accseo>0) addMenu(teGetUrlQuery("=metadata"),"SEO","");


	$_template->addToVar("help","");
	$_template->addToVar("link-all","");

define('helpprogrampath',$CURFLD);

function addHelp($filename){
	global $_template;
	$_template->addVar("help","<div id='help'><a href='./help/".$filename."' onclick=\"return hs.htmlExpand(this, { objectType: 'iframe' } )\"><b>������� �� �������� �������</b></a></div>");

}
	//��������� ���� body
	$_template->addToVar("param_body","");
?>