<?
	function getmicrotime(){ 
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	}
	define("MTIME_BEGIN",getmicrotime());
	
	
	// ��������� ���������� ���� �� ���� $date
	function subdate($date){
		for($i=0;$i<=630;$i++){
			if($date==date("d.m.Y",strtotime("+$i day"))){
				return $i;
			}
		}
		return false;
	}
	
	function rusMonth($m){
		$arr = array(
			1 => "������",
			2 => "�������",
			3 => "����",
			4 => "������",
			5 => "���",
			6 => "����",
			7 => "����",
			8 => "������",
			9 => "��������",
			10 => "�������",
			11 => "������",
			12 => "�������"
		);
		return $arr[$m];
	}
	
?>