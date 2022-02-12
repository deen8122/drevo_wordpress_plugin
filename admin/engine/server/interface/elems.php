<?
	 
	
	function setTitle($text){
		global $_template;
		
		//$_template->addToVar("h1",$text);
		$_template->addToVar("title",strip_tags($text));
		print "<h2>".$text."</h2>";
	}
	
?>