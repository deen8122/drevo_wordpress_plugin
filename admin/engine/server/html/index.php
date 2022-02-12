<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* TDSCMS v1.0 
* tdssc@mail.ru
*****/



require_once "{$CURFLD}class_template.php";

// объект teTemplate для главного шаблона
global $_template;
$_template = new teTemplate;


require_once "{$CURFLD}func_js.php";
require_once "{$CURFLD}html.php";

?>