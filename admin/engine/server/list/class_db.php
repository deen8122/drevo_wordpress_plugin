<? 
/*****
* class teList
*
* TDSCMS v1.0 
* tdssc@mail.ru
*****/

/*****
* Class `teList`
*****/

				function urlnum($num){
					$mnurl = "?";
					foreach($_GET AS $gname => $gvalue){
						$mnurl .= $gname."=".$gvalue."&";
					}
					return $mnurl.G_NUMBER."=".$num;
				}

class teList{
	
	var $query;
	var $arr_head;
	var $arr_head_params;
	var $arr_sort;
	var $arr_fields;
	var $arr_rows_cols;
	var $arr_template_fields;
	var $arr_template_fields_params;
	var $param_table;
	var $classHTML;
	var $countRows;
	var $countInPage;
	var $countInThisPage;
	var $numPage;
	var $irow;
	var $arr_user;
	// ������������ ���������� ������ �������� � ���� �������
	var $arr_maxlenght;
	
	/*****
	* Constructor
	* @param string Query
	* @param int Count in one page
	* @param string Common prefix for all tables
	*****/
	
	function teList($query, $countInPage = 20){
		$this->query = $query;
		$this->countInPage = $countInPage;
		$this->query();
	}
	
	function query(){
		global $database;
		$query = $this->query;
		$countInPage = $this->countInPage;
		
		unset($this->arr_fields,$this->arr_rows_cols);
		
		if(isset($_GET[G_NUMBER])) $this->numPage = $_GET[G_NUMBER]; else $this->numPage = 0;
		$this->countInPage = $countInPage;
		$i=0;
		if(!empty($query)){
			
			if(@$_GET["list_order"] && !empty($this->arr_sort)){
				$qsuff = $this->arr_sort[(int)$_GET["list_order"]].(($_GET["list_orderm"]=='desc')?" DESC":"");
				if($posord = strpos($query,"ORDER BY")){
					$query = substr($query,0,$posord+8)." ".$qsuff.", ".substr($query,$posord+8);
				} else {
					$query .= " ORDER BY ".$qsuff;
				}
				
			}
			
			$res = $database->query($query);
			$this->countRows = mysql_num_rows($res);// or die($query);
			if($this->countRows>0){
				$ii=0;
				for($i=$this->numPage*$this->countInPage;$i<($this->numPage+1)*$this->countInPage;$i++){
					@mysql_data_seek($res,$i);
					$line = @mysql_fetch_array($res,MYSQL_ASSOC);
					if(empty($line)) break;
					if($ii==0){
						foreach($line as $fieldname => $value){
							$this->arr_fields[] = $fieldname;
						}
					}
					
					foreach($line as $fieldname => $value){
						$this->arr_rows_cols[$ii][] = $value;
					}
					$ii++;
				}
			}
		}
		$this->countInThisPage = $i;
		$this->irow = -1;
		$this->classHTML = 'list';
		$this->param_table = "border=0 cellspacing=0";
	}
	
	function addRow(){
		global $page_arr;
		$count = func_num_args();
		$list = func_get_args();
		$arr = array();
		for ($i = 0; $i < $count; $i++) {
			$arr[] = $list[$i];
		}
		$this->arr_rows_cols[] = $arr;
	}
	
	function addToHead($txt,$params = NULL, $sort = NULL){
		$i = count($this->arr_head);
		$this->arr_head[$i] = $txt;
		if(!empty($params)){
			$this->arr_head_params[$i] = $params;
		}
		$this->arr_sort[$i] = $sort;
	}
	
	function addToBody($txt, $params = NULL, $maxlenght=2048){
		$this->arr_template_fields[] = $txt;
		if(!empty($params)){
			$this->arr_template_fields_params[count($this->arr_template_fields)-1] = $params;
		}
		$this->arr_maxlenght[count($this->arr_template_fields)-1] = $maxlenght;
	}
	
	function row(){
		if(isset($this->arr_rows_cols[++$this->irow])){
			return(true);
		} else {
			$this->irow = 0;
			return(false);
		}
	}
	function addUserField($txt, $params = NULL, $maxlenght=2048){
		$this->arr_user[$this->irow][] = $txt;
		if(!empty($params)){
			$this->arr_template_fields_params[$this->irow][count($this->arr_user[$this->irow])-1] = $params;
		}
		$this->arr_maxlenght[$this->irow][count($this->arr_user[$this->irow])-1] = $maxlenght;
	}
	
	function getValue($txt){
		return($this->arr_rows_cols[$this->irow][ array_search($txt,$this->arr_fields) ]);
	}
	
	function addParamTable($params){
		$this->param_table = " ".$params;
	}
	
	function setClass($name){
		$this->classHTML = $name;
	}
	
	function setCountInPage($n){
		$this->countInPage = $n;
	}
	
	function getHTML(){
		global $str;
		static $ilist = 0;
		$ilist++;
				
		if(!empty($this->arr_rows_cols)){
			$class = $this->classHTML;
			$out = "<table class='table  table-hover  table-bordered $class' {$this->param_table}>";
			if(!empty($this->arr_head)){
				$out .= "<tr>";
				foreach($this->arr_head as $i => $txt){
					if(!empty($this->arr_sort[$i])){
						if(@$_GET["list_order"]==$i && @$_GET["list_orderm"]!="desc"){
							$sort_method = "desc";
						} else {
							$sort_method = "asc";
						}
						$txt = "<a href='".teGetUrlQuery("list_order=".$i,"list_orderm=$sort_method")."'>".$txt."</a>";
					}
					$out .= "<th ".@$this->arr_head_params[$i].">$txt</th>";
				}
				$out .= "</tr>";
			}

			if(empty($this->arr_template_fields) && empty($this->arr_user) && empty($this->arr_head)){
				foreach($this->arr_rows_cols as $i => $cols){
					$out .= "<tr>";
					foreach($cols as $i => $txt){
						$out .= "<td>$txt</td>";
					}
					$out .= "</tr>";
				}
			} elseif(!empty($this->arr_user)){
				
				foreach($this->arr_user as $i => $arrfields){
					$out .= "<tr class='tr".$i."'>";
					foreach($arrfields as $ii => $txt){
						foreach($this->arr_fields as $iii => $templatefield){
						
							$txt = str_replace("{".$iii."}",$this->arr_rows_cols[$i][$iii], $txt);
							$txt = str_replace("{".$this->arr_fields[$iii]."}",$this->arr_rows_cols[$i][$iii], $txt);
							
							$txt = str_replace("%".$iii."%",str_replace('"','\"',str_replace("'",'"',$this->arr_rows_cols[$i][$iii])), $txt);
							$txt = str_replace("%".$this->arr_fields[$iii]."%",str_replace('"','\"',str_replace("'",'"',$this->arr_rows_cols[$i][$iii])), $txt);
							
						}
						// ��������� ������� �������
						if( strlen($txt) > $this->arr_maxlenght[$i][$ii] ){
							$txt = substr($txt,0,$this->arr_maxlenght[$i][$ii])." ...";
						}
						$out .= "<td ".@$this->arr_template_fields_params[$i][$ii].">".$txt."</td>";
					}
					$out .= "</tr>";
				}
				
			} elseif(!empty($this->arr_template_fields)) {
				foreach($this->arr_rows_cols as $i => $txt){
					$out .= "<tr class='tr".$i."'>";
					foreach($this->arr_template_fields as $ii => $template){
						foreach($this->arr_fields as $iii => $templatefield){
						
							$template = str_replace("{".$iii."}",$this->arr_rows_cols[$i][$iii],$template);
							$template = str_replace("{".$this->arr_fields[$iii]."}",$this->arr_rows_cols[$i][$iii],$template);
							
							$txt = str_replace("%".$iii."%",str_replace("'",'\"',str_replace("\"",'\"',$this->arr_rows_cols[$i][$iii])), $template);
							$txt = str_replace("%".$this->arr_fields[$iii]."%",str_replace("'",'\"',str_replace("\"",'\"',$this->arr_rows_cols[$i][$iii])), $template);
							
						}
						// ��������� ������� �������
						if( strlen($template)>$this->arr_maxlenght[$ii] ){
							$template = substr($template,0,$this->arr_maxlenght[$i])." ...";
						}
						$out .= "<td ".$this->arr_template_fields_params[$i].">".$template."</td>";
					}
					$out .= "</tr>";
				}
			} else {
				return("<p align=center style='margin:20px'>��� ������.</p>");
			}
			$out .= "</table>";
			
			
			// ������ �������
			
			$vip = 10;
			$countPages = ceil($this->countRows/$this->countInPage);
			
			
			$str['cur']['goInPageCurrent'] = $str['cur']['goInLastPage'] = $str['cur']['goInNextPage'] = $str['cur']['goInPriorPage'] = $str['cur']['goInPageNumber'] = $str['cur']['goInFirstPage'] = "";
			
			
			
			
			if($countPages > 1){
				$out .= '<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
					  <ul class="pagination">';
				
				if($this->numPage>0) 
					$out .= "<li class='paginate_button'> 
						    <a href='".urlnum($this->numPage-1)."' title='".$str['cur']['goInPriorPage']."'>&lt;&lt;</a> 
					        </li>";
				if($this->numPage>$vip) $ilink = $this->numPage - $vip + 1; else $ilink=1;
				if($countPages-$this->numPage>$vip + 1) $link_max = $this->numPage + $vip + 1; else $link_max = $countPages;
			
				if($ilink>1) 
					$out .= "<li class='paginate_button'> 
						     <a href='".urlnum(0)."' title='".$str['cur']['goInFirstPage']."'>1</a> 
						 </li><span class='paginate_button '>  ... </li>";
				while($ilink<=$link_max){
					if($ilink!=$this->numPage+1){
						$out .= "<li class='paginate_button'>
							   <a href='".urlnum($ilink-1)."' title='".$str['cur']['goInPageNumber'].$ilink."'>".$ilink."</a> 
						        </li>"; 
					} else {
						$out .= "<li class='paginate_button active'>
							  <a>".$ilink."</a>
						        </li> ";
					}
					$ilink++;
				}
				if($this->numPage + $vip < $countPages - 2) 
					$out .= "<li class='paginate_button'><a> ...</a></li>
						 <li class='paginate_button'>
						    <a href='".urlnum($countPages-2)."' title='".$str['cur']['goInLastPage']."'>".($countPages-1)."</a>
					        </li>";
				if($this->numPage<$link_max-1) 
					$out .= " <li class='paginate_button'>
						    <a href='".urlnum($this->numPage+1)."' title='".$str['cur']['goInNextPage']."'>&gt;&gt;</a> 
						  </li>";
			
				$out .= "</ul>
					</div>";
			}



			return($out);
		} else {
			return("<p align=center style='margin:20px'>Нет данных</p>");
		}
	}
}

?>