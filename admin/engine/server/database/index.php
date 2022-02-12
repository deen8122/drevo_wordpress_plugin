<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
* TDSCMS v1.0
* tdssc@mail.ru
*****/
global $database;

// ����� database
//echo $CURFLD;

//print($hosts);
require_once "{$CURFLD}class.php";

	// ������� ������� � ����������� ����� �������������

$database = new teDatabase(COMMON_DB_HOST, COMMON_DB_USERNAME,COMMON_DB_USERPASS, COMMON_DB_NAME);
	function curbase(){
		global $hosts;
		global $host_name;
		global $database;

		$countQueries = (int)@$database->countQueries;

		if(!empty($database)) $database->unconnect();
		$database = new teDatabase($hosts[DB_ID]['db_host'], $hosts[DB_ID]['db_user'], $hosts[DB_ID]['db_pass'], $hosts[DB_ID]['db_name']);

		$database->countQueries = $countQueries;
	}

	function combase(){
		echo 'combase';
		global $database;

		$countQueries = (int)@$database->countQueries;

		if(!empty($database)) $database->unconnect();
		$database = new teDatabase(COMMON_DB_HOST, COMMON_DB_USERNAME,COMMON_DB_USERPASS, COMMON_DB_NAME);

		$database->countQueries = $countQueries;
	}

	function adminbase($base=''){
		global $database, $hosts;
		if(empty($base)) $base = $hosts[DB_ID]['db_name'];

		$countQueries = (int)@$database->countQueries;

		if(!empty($database)) $database->unconnect();
		$database = new teDatabase(DB_HOST, DB_USERNAME,DB_USERPASS, $base);

		$database->countQueries = $countQueries;
	}

	function otherbase($host_name){
		global $hosts;
		global $database;

		$countQueries = (int)@$database->countQueries;

		if(!empty($database)) $database->unconnect();
		$database = new teDatabase($hosts[$host_name]['db_host'], $hosts[$host_name]['db_user'], $hosts[$host_name]['db_pass'], $hosts[$host_name]['db_name']);

		$database->countQueries = $countQueries;
	}


?>