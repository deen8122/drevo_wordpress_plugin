<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* ��� ������ ���������, ���� ����� ������ ������� � �� ���� ������ ���: $res = $database->query("sql");
* ��� ��� ��������� ������ � changes...
*****/


class teDatabase{
	/*  id link of this database connection  */
	var $_db;

	var $i;
	var $lastQuery;

	var $countQueries;

	var $lastQueryId;
	var $lastQueryTable;
	var $change_id;
	/*****
	* Constructor
	* @param string Database host
	* @param string Database user name
	* @param string Database user password
	* @param string Database name
	* @param string Common prefix for all tables
	*****/
	function teDatabase( $host, $user, $pass, $name, $cod = "set names utf8" ){
		$this->_db = mysql_connect($host,$user,$pass);
		mysql_select_db($name,$this->_db);
		mysql_query($cod);
		$this->i = 0;
		$this->countQueries = 0;
	}

	function unconnect(){
		mysql_close($this->_db);
	}

	/*****
	* Get a quoted database escaped string
	* @return string
	*****/
	function quote( $text ) {
		return mysql_escape_string($text);
	}

	/*****
	* Get id query from query string
	* @param string Query
	* @return string
	*****/
	function query( $query, $change = true, $changetype = 0 , $id_good=0){
		global $str;
		global $_USER;

        $sql_run = false;
		$id_good = intval($id_good);
		$this->countQueries++;
		if(isset($_GET['test'])) print $this->countQueries.' '.$query.'<br/>';

		if($change & !preg_match("/SELECT */i",$query) ){
			$num_row = array();
			if( preg_match("/^INSERT INTO */i",$query) ){
				$table_name = substr($query,12);
				$i = strpos($table_name," ");
				$table_name = substr($table_name,0,$i);
				$change_type = 1;
				$res = mysql_query($query, $this->_db) or die("������ � SQL-�������: <b>".$query."</b> : ".mysql_error());
				$sql_run = true;
				$num_row[] = mysql_insert_id($this->_db);
				//��� ������� ������
				if($table_name=='cprice_configtable'){

					preg_match_all("!\'(.*?)\'!ism",$query,$matches);
					$num_row[0]=$matches[1][0];

				}

			} elseif( preg_match("/^UPDATE */i",$query) ){
				$table_name = substr($query,7);
				$i = strpos($table_name," ");
				$table_name = substr($table_name,0,$i);
				$change_type = 2;
			} elseif( preg_match("/^DELETE FROM */i",$query) ){

				$table_name = substr($query,12);
				$i = strpos($table_name," ");
				$table_name = substr($table_name,0,$i);
				$change_type = 3;
			} else
			{
				$change = false;
			}
			if($change){
				if( $change_type==2 | $change_type==3 ){

					$i = strpos($query,"WHERE ")+6;
					$s = substr($query,$i);

                    $this->countQueries++;
                    $res1 = mysql_query("SELECT * FROM $table_name WHERE $s", $this->_db);
                    $field = mysql_fetch_field($res1,0);
					while($line=mysql_fetch_array($res1,MYSQL_NUM)){
						$num_row[] = $line[0];
					}
				}

				$this->lastQueryId = @$num_row[0];
				$this->lastQueryTable = @$table_name;



				if($changetype!=0){
					$change_type = $changetype;
				}

			   foreach($num_row AS $inum){
				if($table_name=='cprice_configtable'){
					$this->countQueries++;
					$old_val = '';
					if($change_type==2 || $change_type==3)
					{
						$res_old = mysql_query("SELECT var_value FROM $table_name WHERE var_name='".$inum."'", $this->_db);
						$row_old = mysql_fetch_row($res_old);
						$old_val = $this->quote($row_old[0]);
					}
					mysql_query("	INSERT INTO cprice_changes_configtable
						( change_type, change_table, change_row, ID_USER, change_dt, ip , old_values )
						VALUES
						( $change_type, '$table_name', '$inum', ".((isset($_USER['id']))?$_USER['id']:0).", NOW(), '".$_SERVER['REMOTE_ADDR']."', '".$old_val."' )
					" ,$this->_db);
					$this->change_id = mysql_insert_id();
				}
				else
				{
					$old_values = '';$br='';
					if($change_type==2 || $change_type==3)
					{
						$this->countQueries++;
                        $res_old = mysql_query("SELECT * FROM $table_name WHERE ".$field->name."=".$inum, $this->_db);
						$num = mysql_num_fields($res_old);
						$row_old = mysql_fetch_array($res_old);
						if($table_name=='cprice_metadata')
						{
							$fields = '';
							for ($i = 1; $i < $num; ++$i) {
								$field = mysql_fetch_field($res_old,$i);
								$fields .=$br.$field->name;
								$br = ',';
							}
							$this->countQueries++;
							mysql_query("INSERT INTO cprice_metadata_old ($fields)
								SELECT $fields FROM cprice_metadata where cprice_metadata.ID_METADATA=".$inum);
							$old_values = mysql_insert_id();
						}
						else
						{
							for ($i = 1; $i < $num; ++$i) {
								$old_values .=$br.$row_old[$i];
								$br = '$|$';
								}
						}
					}
					$this->countQueries++;
					mysql_query("	INSERT INTO ".DB_CHANGES."
							( change_type, change_table, change_row, ID_USER, change_dt, ip , old_values, ID_GOOD)
							VALUES
							( $change_type, '$table_name', $inum, ".((isset($_USER['id']))?$_USER['id']:0).", NOW(), '".$_SERVER['REMOTE_ADDR']."', '".$this->quote($old_values)."', $id_good )
						" ,$this->_db);
					$this->change_id = mysql_insert_id();
				}
			   }
			}

		}
		if(!$sql_run)
		{
			$res = mysql_query($query, $this->_db) or die("������ � SQL-�������: <b>".$query."</b> : ".mysql_error());
        }
		return $res;
	}

	function id(){
		return $this->lastQueryId;
	}
	function change_id(){
		return $this->change_id;
	}

	/*****
	* Get array of rows and cols
	* @param string Query
	* @param string Type key of out array
	* @return array
	*****/
	function getArrayOfQuery( $query, $type_key=MYSQL_NUM, $change = true, $changetype = 0){

		if( $query != $this->lastQuery ){
			$this->i = 0;
		}


		$res = $this->query($query,$change,$changetype);

		$cnt = @mysql_num_rows($res);


		if($this->i<$cnt){
			mysql_data_seek($res,$this->i);


			/*  Check type key  */
			if( $type_key!=MYSQL_NUM & $type_key!=MYSQL_ASSOC & $type_key!=MYSQL_BOTH ) $type_key = MYSQL_NUM;

			$line = mysql_fetch_array($res,$type_key);
			$out = $line;
			$this->i++;
			if($this->i+1>$cnt) $this->i = 0;
		} else {
			$out = false;
			$this->i = 0;
		}

		return $out;
	}

	function getDateTime($str,$arr=false){
		$dt['d'] = substr($str,8,2);
		$dt['m'] = substr($str,5,2);
		$dt['y'] = substr($str,0,4);

		$dt['h'] = substr($str,11,2);
		$dt['m'] = substr($str,14,2);
		$dt['s'] = substr($str,17,2);

		if($arr){
			return $dt;
		} else {
			return $dt['d'].".".$dt['m'].".".$dt['y']." ".$dt['h'].":".$dt['m'].":".$dt['s'];
		}
	}
//���������� �������� ������
	function getCellOfQuery($sql){
		global $database;
		
		$cell = FALSE;
		if ($result = $database -> query($sql) AND $row = mysql_fetch_row($result))
			$cell = $row[0];
		return $cell;
	}
//���������� ������ ���������� � ���� �������
	function getRowOfQuery($sql){
		global $database;
		
		$row = array();
		if ($result = $database -> query($sql))
			$row = mysql_fetch_assoc($result);
		return $row;
	}
//���������� ������� ���������� � ���� �������
	function getColumnOfQuery($sql, $makehash = FALSE){
		global $database;
		
		$data = array();
		$result = $database -> query($sql);
		if (!$makehash)
			while ($row = mysql_fetch_row($result))
				$data[] = $row[0];
		else
			while ($row = mysql_fetch_row($result))
				$data[$row{0}] = $row[1];
		return $data;
	}
//���������� ��������� � ���� ���������� �������
	function getArrOfQuery($sql, $keycol = NULL){
		global $database;
		
		$data = array();
		$result = $database -> query($sql);
		if (is_null($keycol))
			while ($row = mysql_fetch_assoc($result))
				$data[] = $row;
		else
			while ($row = mysql_fetch_assoc($result))
				$data[$row{$keycol}] = $row;
		return $data;
	}
}


?>