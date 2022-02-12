<? 
/*****
* class teList
*
* TDSCMS v1.0 
* tdsweb@ya.ru
*****/

/*****
* Class `teListActions`
	в классе указывается таблица, первичный ключ. 
	если рекурсивная, то указывается поле setParent, также setMaxLevel
	можно указать поле для ручной сортировки
	дальше всё ясно
*****/
class teModuleActions{
	
	var $fields;
	var $hiddens;
	var $date;
	var $IDName;
	var $IDParent;
	var $maxLevel;
	var $minLevel;
	var $IDPos;
	var $posParent;
	var $deletedField;
	var $tableName;
	var $actions;
	var $title;
	
	var $parentEmpty;
	
	function teListActions(){
		$this->fields = array();
		$this->title = array();
		$this->actions = array();
		$this->parentEmpty = array();
		$this->hiddens = array();
	}
	
	function setTable($nameTable){
		$this->tableName = $nameTable;
	}
	
	function setID($nameField){
		$this->IDName = $nameField;
	}
	
	function setParent($nameField){
		$this->IDParent = $nameField;
	}
	
	function setMaxLevel($val){
		$this->maxLevel = $val;
	}
	function setMinLevel($val){
		$this->minLevel = $val;
	}
	
	function setPos($nameField){
		$this->IDPos = $nameField;
		$this->posParent = false;
	}
	function setParentPos($num = 0){
		$this->posParent = $num;
	}
	
	
	function titleTable($string){
		$this->title['table'] = $string;
		$this->title['tableChild'] = $string;
	}
	function titleAdd($string){
		$this->title['add'] = $string;
		$this->title['addChild'] = $string;
	}
	function titleEdit($string){
		$this->title['edit'] = $string;
		$this->title['editChild'] = $string;
	}
	function captionAdd($string){
		$this->title['capadd'] = $string;
		$this->title['capaddChild'] = $string;
	}
	
	function titleTableChild($string){
		$this->title['tableChild'] = $string;
	}
	function titleAddChild($string){
		$this->title['addChild'] = $string;
	}
	function titleEditChild($string){
		$this->title['editChild'] = $string;
	}
	function captionAddChild($string){
		$this->title['capaddChild'] = $string;
	}
	
	function addParentCaption($nameField,$caption,$level=0){
		
		for($i=$level;$i<$this->maxLevel;$i++){
			if(empty($this->fields[$nameField]['parentCaption'][$i])){
				$this->fields[$nameField]['parentCaption'][$i] = $caption;
				$this->parentEmpty[$nameField][$level] = 1;
			}
		}
	}
	
	function addFieldBefore($nameField, $text){
		$this->fields[$nameField]['before'] = $text;
	}
	function addFieldAfter($nameField, $text){
		$this->fields[$nameField]['after'] = $text;
	}
	
	/*****
	*  Добавляем новое поле.
	*  Возможные типы:
	*  *  text          -  текстовое поле
	*  *  textarea  -  текстовое поле
	*  *  number     - числовое поле
	*  *  image       - картинка
	*  *  database  - выборка из БД
	*****/
	function createField($nameField,$type,$title,$showInTable=true,$require=false){
		$this->fields[$nameField] = array( $type, $title, $showInTable, $require );
		$this->fields[$nameField]['after'] = "";
		$this->fields[$nameField]['before'] = "";
	}
	
	function createHidden($nameField,$value){
		$this->hiddens[$nameField] = $value;
	}
	
	/*****
	*  Добавляем связь записи к другой таблице (справочник)
	*****/
	function link($nameField,$table,$id,$name,$where=""){
		if( $this->fields[$nameField][0] == 'database' ){
			$this->fields[$nameField]['table'] = $table;
			$this->fields[$nameField]['id'] = $id;
			$this->fields[$nameField]['name'] = $name;
			$this->fields[$nameField]['where'] = $where;
		}
	}
	
	function setDate($nameField){
		$this->date = $nameField;
	}
	
	function addAction($url,$title){
		$this->actions[$url] = $title;
	}
	
	function folder($nameField,$txt,$trumb = NULL){
		$this->fields[$nameField]['folder'] = $txt;
		if(!empty($trumb)){
			$this->fields[$nameField]['trumb'] = $trumb;
		}
	}
	function trumb($nameField,$trumbPrefix,$trumbHeight=100){
		$this->fields[$nameField]['trumbs'][$trumbPrefix] = $trumbHeight;
	}
	
	
	function filterDeleted($nameField){
		$this->deletedField = $nameField;
	}
	
	function numLevel($showid = false){
		global $database;
		
		if(!$showid) $showid = (int)@$_GET['showid'];
		
		$curlevel = 0;
		if(!empty($this->IDParent)){
			if($showid!=0){
				$id1 = $showid;
				while(1){ 
					$ln = $database->getArrayOfQuery("SELECT ".$this->IDParent." FROM ".$this->tableName." WHERE ".$this->IDName."=".(int)$id1);
					$id1 = (int)$ln[0];
					$curlevel++;
					if($id1==0) break;
				}
			}
		}
		$curlevel++;
		
		return $curlevel;
	}
	
	
	function show(){
		$id = (int)@$_GET['id'];
		$showid = (int)@$_GET['showid'];
		addGet("showid",$showid);
		
		$table = $this->tableName;
		
		setWindowTitle($this->title['table']);
		
		if( !empty($_GET['action']) ){
			
			
			switch( $_GET['action'] ){
				
				case 'add':
				case 'edit':
					
					$addGroup = (@$_GET['addparam']=='group')?true:false;
					
					print "<h2>".$this->title[$_GET['action']]."</h2>";
					
					global $database;
					if( $_GET['action']=="edit" ){
						$content = $database->getArrayOfQuery("SELECT * FROM ".$table." WHERE ".$this->IDName."=".$id, MYSQL_ASSOC);
					}
					
					print "<div align=center>";
					$frm = new teForm("form1","post");
					
					$curlevel = $this->numLevel($showid)-1;
					
					foreach($this->fields AS $name => $cont){
					if( ($addGroup && isset($this->parentEmpty[$name][$curlevel])) || !$addGroup ){
						
						list($type,$title,$show,$require) = $cont;
						
						if( $addGroup ) {
							$title = $cont['parentCaption'][$curlevel];
						}
						
						switch( $type ){
							case 'custom':
								$frm->addf_custom($name, $title, @$content[$name]);
							break;
							case 'text':
								$frm->addf_text($name, $title, @$content[$name]);
							break;
							case 'textarea':
								$frm->addf_text($name, $title, @$content[$name], true);
							break;
							case 'number':
								$frm->addf_text($name, $title, @$content[$name]);
								$frm->addf_ereg($name, "^[0123456789\.\,]*$");
								$frm->addf_desc($name, "Возможны только числовые значения");
								$frm->setFieldWidth($name,"150px");
							break;
							case 'checkbox':
								$frm->addf_checkbox($name, $title, (isset($content[$name]))?$content[$name]:true);
							break;
							case 'image':
								$frm->addf_file($name, $title, ($_GET['action']=="add")?"":URLDATA_FLD.@$this->fields[$name]['folder']."/".@$content[$name]);
							break;
							case 'database':
								if( !empty ($cont['table']) && !empty($cont['id']) && !empty ($cont['name']) ){
									$frm->addf_selectGroup($name, $title);
									
									$res = $database -> query( "SELECT ".$cont['id'].",".$cont['name']." FROM ".$cont['table']." ".$cont['where'] );
									if( mysql_num_rows($res) > 0 ){
										while( $line = mysql_fetch_array($res,MYSQL_NUM) ){
											$frm->addf_selectItem($name, $line[0], $line[1], ($line[0]==@$content[$name])?"checked":"" );
										}
									} else {
										if( $require ){
											$frm->addf_group($name,$title,"Сначала заполните справочник «".$title."»");
										}
									}
								}
							break;
						}
						if( $require && !($type=='image' && $_GET['action']=="edit") ){
							$frm->setf_require($name);
						}
						unset($cont);
					}
					}
					
					if(!$frm->send()){
						teInclude('images');
						
						if( $_GET['action']=="add" ){
							$query = "INSERT INTO ".$table." ";
							$s1=$s2="";
							foreach($this->fields AS $name => $cont){
								$s1 .= ",".$name;
								if(($addGroup && isset($this->parentEmpty[$name])) || !$addGroup ){
									if( $cont[0]=='image' ){
										$value = $frm->move_file($name,$cont['folder']);
										if(!empty($cont['trumbs'])){
											foreach( $cont['trumbs'] AS $trumbName => $trumbHeight ){
												teImgTrumb(DATA_FLD.$cont['folder']."/".$value,$trumbHeight,$trumbName);
											}
										}
									} elseif($cont[0]=='checkbox'){
										$value = $frm->get_value_checkbox($name);
									} else {
										$value = $frm->get_value($name);
									}
									$s2 .= ",'".$value."'";
								} else {
									$s2 .= ",NULL";
								}
							}
							if(!empty($this->hiddens))
							foreach($this->hiddens AS $name => $cont){
								$s1 .= ",".$name;
								$s2 .= ",'".str_replace("'","\'",$cont)."'";
							}
							if(isset($this->date)){
								$s1 .= ",".$this->date;
								$s2 .= ",NOW()";
							}
							$s1 = substr($s1,1);
							$s2 = substr($s2,1);
							
							// если многоуровневая система
							if(!empty( $this->IDParent )){
								$s1 .= ",".$this->IDParent;
								$s2 .= ",".$showid;
							}
							
							// номер позиции (если есть) = макс+1
							if(!empty( $this->IDPos )){
								if(!empty( $this->IDParent )){
									$where = $this->IDParent."=".$showid;
								} else {
									$where = "true";
								}
								$maxpos = $database->getArrayOfQuery("SELECT max(".$this->IDPos.") FROM ".$table." WHERE ".$where);
								$maxpos = $maxpos[0];
								
								$s1 .= ",".$this->IDPos;
								$s2 .= ",".($maxpos+1);
							}
							
							$query .= "  (".$s1.") VALUES (".$s2.")";
						} else {
							$query = "UPDATE ".$table." SET ";
							$s1="";
							foreach($this->fields AS $name => $cont){
								if(($addGroup && isset($this->parentEmpty[$name])) || !$addGroup ){
									$insert = true;
									
									if( $cont[0]=='image' ){
										$delete = false;
										if(@$frm->get_value($name)=="on"){
											$value = "";
											$insert = true;
											$delete = true;
										} else {
											$value = $frm->move_file($name,$cont['folder']);
											if(!empty($cont['trumbs'])){
												foreach( $cont['trumbs'] AS $trumbName => $trumbHeight ){
													teImgTrumb(DATA_FLD.$cont['folder']."/".$value,$trumbHeight,$trumbName);
												}
											}
											if(empty($value)){
												$insert = false;
											} else {
												$delete = true;
											}
										}
										if($delete){
											$line = $database->getArrayOfQuery("SELECT ".$name." FROM ".$table." WHERE ".$this->IDName."=".$id);
											$line = $line[0];
											@unlink(DATA_FLD.$cont['folder']."/".$line);
										}
									} elseif($cont[0]=='checkbox'){
										$value = $frm->get_value_checkbox($name);
									}else {
										$value = $frm->get_value($name);
									}
									
									
									if($insert) $s1 .= ",".$name."='".str_replace("'","\'",$value)."'";
								} else {
									$s1 .= ",".$name."=NULL";
								}
							}
							foreach($this->hiddens AS $name => $cont){
								$s1 .= ",".$name;
								$s2 .= ",'".str_replace("'","\'",$cont)."'";
							}
							$s1 = substr($s1,1);
							$query .= $s1." WHERE ".$this->IDName."=".$id;
						}
						$database->query($query);
						
						///if( $_GET['action']=="add" ){
						///	$cnt = $database->id();
						///	$database->query("INSERT INTO");
						///}
						
						teRedirect(teGetUrlQuery((!empty( $this->IDPos ) && $_GET['action']=="add")?"id=".$id:""));
					}
					print "</div>";
					
				break;
				
				case 'delete':
					global $database;
					if(!empty($this->IDParent)){
						$pid = $database->getArrayOfQuery("SELECT ".$this->IDParent." FROM ".$table." WHERE ".$this->IDName."=".$id);
						$pid = $pid[0];
					} else {
						$pid = $id;
					}
					$database->query("UPDATE ".$table." SET ".$this->deletedField."=1 WHERE ".$this->IDName."=".$id);
					teRedirect(teGetUrlQuery((!empty( $this->IDPos ))?"id=".$pid:""));
				break;
				
				case 'savepos':
					if( !empty( $_POST['lpos'] )){
						global $database;
						foreach( $_POST['lpos'] AS $id => $pos){
							$database->query("UPDATE ".$table." SET ".$this->IDPos."=".$pos." WHERE ".$this->IDName."=".$id);
						}
					}
					teRedirect(teGetUrlQuery((!empty( $this->IDPos ))?"id=".$id:""));
				break;
			}
			
			
			
		} else {
			global $database;
			
			$curlevel = $this->numLevel();
			
			if(!empty($this->IDParent)){
				if($showid!=0){
					$ln = $database->getArrayOfQuery("SELECT ".$this->IDParent." FROM ".$table." WHERE ".$this->IDName."=".(int)$showid);
					$id1 = (int)$ln[0];
					if($curlevel>1) print_link_up(teGetUrlQuery("showid=".$id1),"перейти на уровень выше");
					unset($ln);
				}
			}
			
			
			$addGroup = true;
			$addItem  = true;
			if(!(!empty($this->IDParent) && $curlevel<$this->maxLevel+1)){
				$addGroup = false;
			}
			
			
			if(!empty( $this->IDPos ) && (@($addGroup && @$this->posParent<$curlevel) || !$addGroup ) ){
				$posExists = true;
			} else {
				$posExists = false;
			}
			
			if(count($this->parentEmpty)==0){
				$addItem  = true;
			} elseif( !empty($this->IDParent) ){
				if( $database->getArrayOfQuery("SELECT ".$this->IDName." FROM ".$table." WHERE ".$this->IDParent."=".$showid) ){
					foreach( $this->parentEmpty AS $emtval => $on ){
						if( $database->getArrayOfQuery("SELECT ".$this->IDName." FROM ".$table." WHERE ".$this->IDParent."=".$showid." and ".$emtval."<>NULL") ){
							$addItem = false;
							break;
						}
					}
				}
			}
			
			print "<h2>".$this->title['table']."</h2>";
			
			print "<div class=add align=center>";
			if($addGroup) 
				print "<a  href='".teGetUrlQuery("action=add","addparam=group", ((!empty( $this->IDPos ))?"id=".$id:"").'')."'>".$this->title['capadd']."</a> &nbsp; ";
			if($addItem && !$addGroup) 
				print "<a   href='".teGetUrlQuery("action=add","addparam=item",(!empty( $this->IDPos ))?"id=".$id:"")."'>".$this->title['capaddChild']."</a>";
			print "</div>";
			$fields = "";
			foreach($this->fields AS $name => $cont){
				$fields .= ",".$name;
			}
			$fields = substr($fields,1);
			
			if( isset($this->date) ){
				$orderby = "ORDER BY ".$this->date." DESC";
			} elseif(!empty( $this->IDPos )){
				$fields .= ", ".$this->IDPos;
				
				$orderby = "ORDER BY ".$this->IDPos;
			} else {
				foreach($this->fields AS $name => $cont){
					$orderby = "ORDER BY ".$name;
					break;
				}
			}
			
			$where = "WHERE 1";
			if(!empty( $this->deletedField )){
				$where .= " and ".$this->deletedField."=0";
			}
			if(!empty( $this->IDParent )){
				$where .= " and ".$this->IDParent."=".$showid;
			}
			
			$OList = new teList("SELECT ".$this->IDName.", ".$fields." FROM ".$table." ".$where." ".$orderby);
			//$OList->addToHead("ID");
			//$OList->addUserField("ID",'1');
			
			if($posExists){
				$OList->addToHead("<input type=submit value='№' style='margin:0px;padding:0px;' title='Сохранить номера позиций' />","width=1%");
			}
			foreach($this->fields AS $name => $cont){
				if($cont[2]){											
					if( ($addGroup && isset($this->parentEmpty[$name][$curlevel-1])) || !$addGroup ){
						if($addGroup){
							$title = (isset( $cont['parentCaption'][$curlevel-1] ))?$cont['parentCaption'][$curlevel-1]:$cont[1];
						} else {
							$title = $cont[1];
						}
						$OList->addToHead($title);
					}
				}
			}
			$OList->addToHead("Действия","colspan=".(2+count($this->actions))." width=1%");
			
			
			if($addGroup){
				$linkChild0 = "<a href='".teGetUrlQuery("showid={0}")."'>";
				$linkChild1 = "</a>";
			} else {
				$linkChild0 = $linkChild1 = "";
			}
			if($posExists){
				$body['pos'] = "<input type=text size=3 maxlenght=3 name='lpos[{0}]' value='{".$this->IDPos."}' />";
			}
			$body['edit'] = "<a href='".teGetUrlQuery("action=edit","id={".$this->IDName."}",( ($addGroup)?"addparam=group":"addparam=item" ))."'>ред</a>";
			$body['delete'] = "<a class=del href=\"javascript: if(confirm('Удалить «{1}»?')) location.href='".teGetUrlQuery("action=delete","id={".$this->IDName."}")."'\">удал</a>";
			
			
			// проверка есть ли поля-справочники
			$cycle = false;
			foreach($this->fields AS $cont){
				if($cont[0]=='database' || $cont[0]=='checkbox' || $cont[0]=='textarea'){
					$cycle = true;
					break;
				}
			}
			if($cycle){
				
				global $database;
				while($OList->row()){
					// поз
					if($posExists){
						$OList->addUserField($body['pos']);
					}
					// тело
					foreach($this->fields AS $name => $cont){
						if( ($addGroup && isset($this->parentEmpty[$name][$curlevel-1])) || !$addGroup ){
							list($type,,$show) = $cont;
							if( $show ){
								if( $type=='database' ){
									$txt = $database->getArrayOfQuery("SELECT ".$cont['name']." FROM ".$cont['table']." ".$cont['where'].((!empty($cont['where']))?" and ":"WHERE ").$cont['id']."=".$OList->getValue($name));
									$txt = $txt[0];
									$OList->addUserField($linkChild0.$txt.$linkChild1);
								} elseif( $type=='checkbox' ){
									$OList->addUserField(($OList->getValue($name)==1)?"да":"нет");
								} elseif( $type=='image' ){
									$OList->addUserField("&nbsp;"."<a target=_blank href='".URLDATA_FLD.$cont['folder']."/{".$name."}'>{".$name."}</a>");
								} else {
									$s = $cont['before'].substr(strip_tags($OList->getValue($name)),0,100).((strlen($OList->getValue($name))>100)?"...":"").$cont['after'];
									$OList->addUserField("&nbsp;".$linkChild0.$s.$linkChild1);
									unset($s);
								}
							}
						}
					}
					// действия
					if(count($this->actions)>0) foreach( $this->actions AS $url => $title){
						$OList->addUserField("<a href='".$url."'>".$title."</a>");
					}
					$OList->addUserField($body['edit']);
					$OList->addUserField($body['delete']);
				}
				
			} else {
				
				// поз
				if($posExists){
					$OList->addToBody($body['pos']);
				}
				// тело
				foreach($this->fields AS $name => $cont){
					if( ($addGroup && isset($this->parentEmpty[$name][$curlevel-1])) || !$addGroup ){
						list($type,,$show) = $cont;
						if( $show ){
							if( $type=='image' ){
								$OList->addToBody("&nbsp;"."<a target=_blank href='".URLDATA_FLD.$cont['folder']."/{".$name."}'>{".$name."}</a>");
							} else {
								$OList->addToBody("&nbsp;".$linkChild0."{".$name."}".$linkChild1);
							}
						}
					}
				}
				// действия
				if(count($this->actions)>0) foreach( $this->actions AS $url => $title){
					$OList->addToBody("<a href='".$url."'>".$title."</a>");
				}
				$OList->addToBody($body['edit']);
				$OList->addToBody($body['delete']);
				
			}
			
			
			
			if(!empty( $this->IDPos )) print "<form method=post action='".teGetUrlQuery('action=savepos')."'>";
			print $OList->getHTML();
			if(!empty( $this->IDPos )) print "</form>";
			
			print "<div class=add align=center>";
			if($addGroup) print "<a   class='btn btn-success' href='".teGetUrlQuery("action=add","addparam=group",(!empty( $this->IDPos ))?"id=".$id:"")."'>".$this->title['capadd']."</a> &nbsp; ";
			if($addItem && !$addGroup) print "<a  class='btn btn-success' href='".teGetUrlQuery("action=add","addparam=item",(!empty( $this->IDPos ))?"id=".$id:"")."'>".$this->title['capaddChild']."</a>";
			print "</div>";
		}
	}
	
}

?>