<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* File `/engine/server/functions/js.php`
*   function get_js_script()
*   function add_js_script()
*   function add_js_file()
*
* TDSCMS v1.0 
* tdssc@mail.ru
*****/

//всё ясно из названий...

	function teGetJSScript($code, $src=NULL){
		$out = '<script type="text/javascript"';
		if(!empty($src)) $out .= ' src="'.$src.'"';
		if(!empty($code)) $out .= ">//<!--\n".$code."//-->\n"; else $out .= '>'; 
		$out .= '</script>';
		return($out);
	}

	function teAddJSScript($code = "", $src = ""){
		$GLOBALS["_template"] -> addToVar("JS",teGetJSScript($code, $src));
	}
	
	function teAddJSFile(){
		$count = func_num_args();
		$list = func_get_args();
		for ($i = 0; $i < $count; $i++) {
			teAddJSScript(null,$list[$i]);
		}
	}
	
	function jquery($js){
		$GLOBALS["_template"] -> addToVar("JS",'<script type="text/javascript">jQuery(document).ready(function(){'.$js.'})</script>');
	}
	
	// подключает расширенный html-редактор
	function word($js){
		$GLOBALS["_template"] -> addToVar("JS",'<script type="text/javascript">jQuery(document).ready(function(){word("'.$js.'")})</script>');
	}


	function teAddCSSFile($src){
		$GLOBALS["_template"] -> addToVar("JS","<link rel=\"stylesheet\" href=\"".$src."\" type=\"text/css\" />");
	}
	
	function teAddCSSCode($code){
		$GLOBALS["_template"] -> addToVar("JS","<style type=\"text/css\">".$code."</style>");
	}
	
	
?>