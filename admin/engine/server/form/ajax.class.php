<? 
/*****
*   class teAjax
*
* TDSCMS v1.0 
* tdssc@mail.ru
*****/

//teAddJSFile($CURFLD."ajax_init.js");

/*****
* class teAjax
*****/
if(!class_exists('teAjax')){
class teAjax{
	var $queryVars;
	var $answerVars;
	var $JSVarNames;
	var $inputValues;
	var $HTMLElements;
	
	var $shuffle;
	
	/*****
	* class teAjax
	*****/
	function teAjax($shuffle = ""){
		$this->queryVars = array();
		$this->JSVarNames = array();
		$this->inputValues = array();
		$this->HTMLElements = array();
		$this->answerVars = array();
		$this->shuffle = $shuffle;
	}
	
	function addQueryVar($varQueryName, $value){
		$this->queryVars[$varQueryName] = $value;
	}
	function getQueryVar($varQueryName){
		if( isset($this->queryVars[$varQueryName]) ){
			return $this->queryVars[$varQueryName];
		} else {
			return false;
		}
	}
	function getMainQueryVar(){
		if( isset($_GET['ajaxfld']) ){
			return $_GET['ajaxfld'];
		} else {
			return false;
		}
	}
	
	function addValue($answerVar,$value){
		$this->answerVars[$answerVar][] = $value;
	}
	
	function checkval(){
		$s = $this->shuffle;
		return md5($s);
	}
	
	function server(){
		if( !empty($_GET["ajax"]) & @$_GET["ajax"] == $this->checkval() ){
			return true;
		} else {
			return false;
		}
	}
	
	function client(){
		return !$this->server();
	}
	
	
	function setJSVar($JSVarName,$valueName){
			$this->JSVarName[$inputValue] = $valueName;
	}
	function setInputValue($inputValue,$valueName){
			$this->inputValues[$inputValue] = $valueName;
	}
	function setHTMLElemValue($inputValue,$valueName){
			$this->HTMLElements[$inputValue] = $valueName;
	}
	
	function getFuncName($value = 'this.value'){
		return "ajax".$this->checkval()."(".$value.");";
	}
	
	
	function send(){
		if( $this->server() ){
			
			header('Content-Type: text/xml');
			header('Cache-Control: must-revalidate, get-check=0, post-check=0, pre-check=0');
			//ob_start();
			$s = "";
			$s.= '<?xml version="1.0" encoding="windows-1251"?>';
////// $s.= $_SERVER['REQUEST_URI']."\n";
			$s.= '<response>';
			foreach( $this->answerVars AS $name => $value ){
				foreach( $value AS $value1 ){
					$s.= "<".$name.">".htmlspecialchars($value1)."</".$name.">";
				}
				
			}
			
			$s.= '</response>';
			$s1 = ob_get_contents();
			ob_end_clean();
			die($s);
			
		} else {
			$checkval = $this->checkval();
			
			//$loading = "<font color='#999999'>��������, ���������...</font>";
			
			$loading = "<img src='".LIB_FORM_PATH."images/ajax_loader.gif' style='background:white;padding:0px;'/>";
			print "<img src='".LIB_FORM_PATH."images/ajax_loader.gif' style='position:absolute;top:-100px;left:-100px;visibility:hidden;'/>";
			
			$s = $s1 = $s_load = "";
			foreach( $this->JSVarNames AS $name => $value ){
				$s1 .= "var ".$name.";";
				$s .= $name."=xmlRoot.getElementsByTagName('".$value."').item(0).firstChild.data;";
				if(empty($s_load)) $s_load = $name."=\"".$loading."\";";
			}
			foreach( $this->inputValues AS $name => $value ){
				$s .= "document.getElementById('".$name."').value=xmlRoot.getElementsByTagName('".$value."').item(0).firstChild.data;";
				if(empty($s_load)) $s_load = "document.getElementById('".$name."').value=\"".$loading."\";";
			}
			foreach( $this->HTMLElements AS $name => $value ){
				$s .= "document.getElementById('".$name."').innerHTML=xmlRoot.getElementsByTagName('".$value."').item(0).firstChild.data;";
				if(empty($s_load)) $s_load = "document.getElementById('".$name."').innerHTML=\"".$loading."\";";
			}
			
			$request = "";
			foreach( $this->queryVars AS $name => $value ){
				$request .= "&$name=$value";
			}
			
			print teGetJSScript("
			".$s1."
			function ajax".$checkval."(ths){
				$s_load
				if(xmlHttp.readyState == 4 || xmlHttp.readyState == 0){
					xmlHttp.open('GET', 'http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&ajax=".$checkval.$request."&ajaxfld='+ths, false);
					//alert('http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&ajax=".$checkval.$request."&ajaxfld='+ths);
					xmlHttp.send(null);
					// alert(xmlHttp.responseText);
					xmlRoot = xmlHttp.responseXML.documentElement;
					".$s."
				}
				else setTimeout('ajax".$checkval."(ths)', 100);
				/// alert(ths);
			}
			");
			unset($s);
		}
	}
	
}
}
?>