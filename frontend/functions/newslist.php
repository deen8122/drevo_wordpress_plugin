<?php
	
	function drevo_newslist($arg) {
		
		$pagesArr = explode('/',$_SERVER['REQUEST_URI']);
		//print_r($pagesArr );
		if(count($pagesArr)>3){
			ob_start();
			global $data;
			 $arg['uslovia']['ID_GOOD']=array($pagesArr[2]);
			 $arg['limit'] = 1;
			$data = drevo_getdata($arg);
			foreach($data as $gid => $arr){
			$data =  $arr;
			$data['ID_GOOD']=$gid;
			break;
			}
			//$data = $data[0];
			//print_r($arg);
			get_template_part('drevo/drevo', $arg['tpl'].'-detail');
			$ReturnString = ob_get_contents();
			ob_end_clean();
			return $ReturnString;
			
			}else {
			ob_start();
			global $data;
			$data = drevo_getdata($arg);
			//print_r($arg);
			get_template_part('drevo/drevo', $arg['tpl']);
			$ReturnString = ob_get_contents();
			ob_end_clean();
			return $ReturnString;
		}
	}	