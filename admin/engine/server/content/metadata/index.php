<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* TDSCMS v1.0
* tdscms@mail.ru
*****/
global $_CONF;
if($_CONF['metadata']){
	teAddMeta( teGenURL() );

global $_template;
global $database;

$head_title="";
$meta_title="";
$meta_keywords="";
$meta_description="";
$h1="";
$h2="";
$body_keywords="";
$body_description="";

if( $line = $database -> getArrayOfQuery("SELECT * FROM metadata WHERE metadata_url='".teGenURL()."'",MYSQL_ASSOC) ){
	//$line = $database -> getArrayOfQuery("SELECT * FROM metadata WHERE metadata_url LIKE '%".teGenURL()."%' ORDER BY ID_METADATA LIMIT 1",MYSQL_ASSOC) || 

	if(!empty($line['metadata_head_title'])){
		$head_title = $line['metadata_head_title'];
	}
	if(!empty($line['metadata_meta_title'])){
		$meta_title = $line['metadata_meta_title'];
	}
	if(!empty($line['metadata_meta_keywords'])){
		$meta_keywords = $line['metadata_meta_keywords'];
	}
	if(!empty($line['metadata_meta_description'])){
		$meta_description = $line['metadata_meta_description'];
	}
	if(!empty($line['metadata_body_h1'])){
		$h1 = "<h1>".$line['metadata_body_h1']."</h1>";
	}
	if(!empty($line['metadata_body_h2'])){
		$h2 = "<h2>".$line['metadata_body_h2']."</h2>";
	}
	if(!empty($line['metadata_body_keywords'])){
		$body_keywords = "<div>".$line['metadata_body_keywords']."</div>";
	}
	if(!empty($line['metadata_body_description'])){
		$body_description = "<div>".$line['metadata_body_description']."</div>";
	}
}

$_template -> addVar("head_title",$head_title);
$_template -> addVar("meta_title",$meta_title);
$_template -> addVar("meta_keywords",$meta_keywords);
$_template -> addVar("meta_description",$meta_description);
$_template -> addVar("h1",$h1);
$_template -> addVar("h2",$h2);
$_template -> addVar("body_keywords",$body_keywords);
$_template -> addVar("body_description",$body_description);
}
?>