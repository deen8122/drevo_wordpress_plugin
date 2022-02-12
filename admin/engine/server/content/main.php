<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* вроде нигде не используется
*****/


global $_CONF;
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

$_template -> addToVar("head_title","");
$_template -> addToVar("meta_title","");
$_template -> addToVar("meta_keywords","");
$_template -> addToVar("meta_description","");
$_template -> addToVar("h1","");
$_template -> addToVar("h2","");
$_template -> addToVar("body_keywords","");
$_template -> addToVar("body_description","");

if(defined(@SEOPAGE) && defined(@SEOID))
if( $line = $database -> getArrayOfQuery("SELECT * FROM metadata WHERE metadata_page=".SEOPAGE." and metadata_id=".SEOID,MYSQL_ASSOC) ){
	//$line = $database -> getArrayOfQuery("SELECT * FROM metadata WHERE metadata_url LIKE '%".teGenURL()."%' ORDER BY ID_METADATA LIMIT 1",MYSQL_ASSOC) || 

	if(!empty($line['metadata_head_title'])){
		$_template -> addVar("head_title",$line['metadata_head_title']);
	}
	if(!empty($line['metadata_meta_title'])){
		$_template -> addVar("meta_title",$line['metadata_meta_title']);
	}
	if(!empty($line['metadata_meta_keywords'])){
		$_template -> addVar("meta_keywords",$line['metadata_meta_keywords']);
	}
	if(!empty($line['metadata_meta_description'])){
		$_template -> addVar("meta_description",$line['metadata_meta_description']);
	}
	if(!empty($line['metadata_body_h1'])){
		$_template -> addVar("h1","<h1>".$line['metadata_body_h1']."</h1>");
	}
	if(!empty($line['metadata_body_h2'])){
		$_template -> addVar("h2","<h2>".$line['metadata_body_h2']."</h2>");
	}
	if(!empty($line['metadata_body_keywords'])){
		$_template -> addVar("body_keywords","<div>".$line['metadata_body_keywords']."</div>");
	}
	if(!empty($line['metadata_body_description'])){
		$_template -> addVar("body_description","<div>".$line['metadata_body_description']."</div>");
	}
}


?>