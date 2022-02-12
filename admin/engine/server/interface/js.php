<?

	//teAddJSFile(JS_FLD."jquery.js");
	//teAddJSFile(JS_FLD."jquery.contextMenu.js");
	//teAddCSSFile($skinpath."jquery.contextMenu.css");
	//teAddJSFile(JS_FLD."conf.contextMenu.js");

	// подключаем визуальный редактор

	if(@$_GET["pg"]!="tasks"/*&&@$_GET["pg"]!="metadata"*/){
	//	if(@$_GET["pg"]!="metadata"){
	//		teAddJSFile("../js/nicEdit-latest.js");
	//		teAddJSFile("../js/jq.wysiwyg.js");
	//	} else {
	//	}
	}
	//teAddJSFile("./js/tiny_mce/jquery.tinymce.js");
			
	teAddJSFile(DEEN_FOLDERS_URL.'assets/js/tiny_mce/jquery.tinymce.js');
	/*
	teAddJsScript(
		'tinyMCE.init({
        mode:"textareas",
        theme:"simple",
        language:"ru"
    	});'
    );
*/
?>