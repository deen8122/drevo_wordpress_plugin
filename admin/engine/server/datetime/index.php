<?
	function getmicrotime(){ 
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	}
	define("MTIME_BEGIN",getmicrotime());
	
	
	// вычисляет количество дней до даты $date
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
			1 => "Январь",
			2 => "Февраль",
			3 => "Март",
			4 => "Апрель",
			5 => "Май",
			6 => "Июнь",
			7 => "Июль",
			8 => "Август",
			9 => "Сентябрь",
			10 => "Октябрь",
			11 => "Ноябрь",
			12 => "Декабрь"
		);
		return $arr[$m];
	}
	
?>