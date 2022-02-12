<?

	function translit_utf($string,$en=false){
		$arr['а'] = "a";
		$arr['б'] = "b";
		$arr['в'] = "v";
		$arr['г'] = "g";
		$arr['д'] = "d";
		$arr['ё'] = "e";
		$arr['е'] = "e";
		$arr['ж'] = "gh";
		$arr['з'] = "z";
		$arr['и'] = "i";
		$arr['й'] = "y";
		$arr['к'] = "k";
		$arr['л'] = "l";
		$arr['м'] = "m";
		$arr['н'] = "n";
		$arr['о'] = "o";
		$arr['п'] = "p";
		$arr['р'] = "r";
		$arr['с'] = "s";
		$arr['т'] = "t";
		$arr['у'] = "u";
		$arr['ф'] = "f";
		$arr['х'] = "h";
		$arr['ц'] = "c";
		$arr['ч'] = "ch";
		$arr['ш'] = "sh";
		$arr['щ'] = "shc";
		$arr['ь'] = "";
		$arr['ы'] = "i";
		$arr['ъ'] = "";
		$arr['э'] = "e";
		$arr['ю'] = "yu";
		$arr['я'] = "ya";
		$arr['А'] = "A";
		$arr['Б'] = "B";
		$arr['В'] = "V";
		$arr['Г'] = "G";
		$arr['Д'] = "D";
		$arr['Е'] = "E";
		$arr['Ё'] = "E";
		$arr['Ж'] = "GH";
		$arr['З'] = "Z";
		$arr['И'] = "I";
		$arr['Й'] = "Y";
		$arr['К'] = "K";
		$arr['Л'] = "L";
		$arr['М'] = "M";
		$arr['Н'] = "N";
		$arr['О'] = "O";
		$arr['П'] = "P";
		$arr['Р'] = "R";
		$arr['С'] = "S";
		$arr['Т'] = "T";
		$arr['У'] = "U";
		$arr['Ф'] = "F";
		$arr['Х'] = "H";
		$arr['Ц'] = "C";
		$arr['Ч'] = "CH";
		$arr['Ш'] = "SH";
		$arr['Щ'] = "SHC";
		$arr['Ь'] = "";
		$arr['Ы'] = "I";
		$arr['Ъ'] = "";
		$arr['Э'] = "E";
		$arr['Ю'] = "YU";
		$arr['Я'] = "YA";
		$arr['№'] = "#";

		foreach($arr AS $w => $k){
			$string = str_replace($w,$k,$string);
		}

		return $string;
	}

?>