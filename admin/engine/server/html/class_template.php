<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* class teTemplate - ��������� � HTML-������ ������
*
* TDSCMS v1.0 
* tdssc@mail.ru
*****/

	class teTemplate{
		var $variables;
		function teTemplate(){
			$this->variables["JS"] = " ";
		}
		function addVar($vName, $vValue){
			$this->variables[$vName] = $vValue;
		}
		function addArray($vName, $vValue){
			$this->variables[$vName][] = $vValue;
		}
		function addToVar($vName, $vValue){
			(isset($this->variables[$vName]))? $this->variables[$vName] .= $vValue : $this->variables[$vName] = $vValue;
		}
		function getVar($vName){
			return @$this->variables[$vName];
		}
		function delVar($vName){
			if(isset($this->variables[$vName])) unset($this->variables[$vName]);
		}
				
		function getText($templateFile){

			$files = func_get_args();
			if(is_array($files))
//print_r($this->variables);
			foreach($files as $file){
				$textFile = @file_get_contents($file);
				if(is_array($this->variables))
				foreach($this->variables as $variable => $value){
					 /*
					 if(is_array($value)){
						
						
						while(true){
							$str_srch = "{".$variable."}";
							$postd = strpos($textFile,$str_srch);
							if($postd===false) break;
							
							if(count($value)==0){
								$textFile = substr($textFile,0,$postd).$this->variables[$variable."_empty"].substr($textFile,$postd+strlen($str_srch)+7);
							} else {
								$rand = array_rand($value,1);
								$textFile = substr($textFile,0,$postd).$value[$rand].substr($textFile,$postd+strlen($str_srch)+7);
								unset($value[$rand]);
							}
						}
						
						
					} else {
					*/
						$textFile = str_replace("{".$variable."}",$value,$textFile);
					/*}*/
				}
				$textFile = str_replace("</head>",$this->variables["JS"]."</head>",$textFile);
			}
			//$textFile = str_replace(">\n",">",$textFile);
			//$textFile = str_replace("\t","",$textFile);
			return($textFile);
		}
		
		function sendText($templateFile){
			echo $this->getText($templateFile)."\n<!-- created by: TDS (tdsweb.net.ru, 420220502) -->";
		}
	}
	function set_window_title($str){
		global $OTemplateCommon;
		$OTemplateCommon -> addToVar("window_title"," -> ".$str);
	}
?>