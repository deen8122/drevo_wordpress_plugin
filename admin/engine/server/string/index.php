<?


	require_once $CURFLD."numtotext.php";
	require_once $CURFLD."index2.php";



	// уменьшает текст до нужного количества символов, при этом, если уменьшила, то добавляет "..." в конце.
	function smallText($txt,$n=20){
		if(strlen($txt)>$n+1){
			return substr($txt,0,$n)."...";
		} else {
			return $txt;
		}
	}
//обрезает строку по кол-ву символов или по ширине
function strEx($input,$len=0,$word=0)
{
	if ($len)
		$out = array_slice(explode('<ttt>',wordwrap($input,$len,'<ttt>',false)),0,1);
	elseif ($word)
		$out = array_slice(explode(' ',$input),0,$word);
	else return false;
		$output = implode(' ',$out);
	return $output;
}
	// возвращает обработанную строку, совсместимую для имени файла в unix
	function filename($string){		$new_string = '';
		for($i=0;$i<strlen($string);$i++)
		{			if(eregi("[0-9a-zа-я_-]",$string[$i]))$new_string .= $string[$i];
			elseif($string[$i]==' ' || $string[$i]=='	') $new_string .= '-';
		}
		return $new_string;
		$arr[' '] = "-";
		$arr['	'] = "-";
		$arr['"'] = "";
		$arr['\''] = "";
		$arr['!'] = "";
		$arr['@'] = "";
		$arr['#'] = "";
		$arr['$'] = "";
		$arr['%'] = "";
		$arr['^'] = "";
		$arr['&'] = "-";
		$arr['*'] = "";
		$arr[','] = "";
		$arr['.'] = "";
		$arr['/'] = "";
		$arr['\\'] = "";
		$arr['|'] = "";
		$arr['+'] = "";
		$arr['='] = "";
		$arr['*'] = "";
		$arr['&'] = "";
		$arr['^'] = "";
		$arr['~'] = "";
		$arr['('] = "";
		$arr[')'] = "";
		$arr['`'] = "";
		$arr['’'] = "";
		$arr['«'] = "";
		$arr['»'] = "";

		foreach($arr AS $w => $k){
			$string = str_replace($w,$k,$string);
		}

		return $string;
	}


	//
	function translit($string,$en=false){
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


	//
	function filename2($string,$en=true){
		if($en){
			$arr[' '] = "-";
			$arr['	'] = "-";
		} else {
			$arr['-'] = " ";
			$arr['-'] = "	";
		}
		foreach($arr AS $w => $k){
			$string = str_replace($w,$k,$string);
		}

		return $string;
	}


	//
	function translit2($string,$en=true){
		if($en){

			$arr['сх'] = "sch";
			$arr['Сх'] = "Sch";
			$arr['СХ'] = "SCH";

			$arr['а'] = "a";
			$arr['б'] = "b";
			$arr['в'] = "v";
			$arr['г'] = "g";
			$arr['д'] = "d";
			$arr['е'] = "e";
			$arr['ё'] = "_e";
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
			$arr['ь'] = "_";
			$arr['ы'] = "i_";
			$arr['ъ'] = "\"";
			$arr['э'] = "e_";
			$arr['ю'] = "yu";
			$arr['я'] = "ya";
			$arr['А'] = "A";
			$arr['Б'] = "B";
			$arr['В'] = "V";
			$arr['Г'] = "G";
			$arr['Д'] = "D";
			$arr['Е'] = "E";
			$arr['Ё'] = "_E";
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
			$arr['Ь'] = "__";
			$arr['Ы'] = "I_";
			$arr['Ъ'] = "\"\"";
			$arr['Э'] = "E'";
			$arr['Ю'] = "YU";
			$arr['Я'] = "YA";
		} else {


			$arr['__'] = "Ь";
			$arr['_e'] = "ё";
			$arr['i_'] = "ы";
			$arr['e_'] = "э";
			$arr['_E'] = "Ё";
			$arr['I_'] = "Ы";
			$arr['E_'] = "Э";
			$arr['""'] = "Ъ";
			$arr['_'] = "ь";
			$arr['"'] = "ъ";


			$arr['sch'] = "сх";
			$arr['Sch'] = "Сх";
			$arr['SCH'] = "СХ";


			$arr['gh'] = "ж";
			$arr['ch'] = "ч";

			$arr['shc'] = "щ";
			$arr['sh'] = "ш";
			$arr['yu'] = "ю";
			$arr['ya'] = "я";

			$arr['GH'] = "Ж";
			$arr['YU'] = "Ю";
			$arr['CH'] = "Ч";
			$arr['SHC'] = "Щ";
			$arr['SH'] = "Ш";
			$arr['YA'] = "Я";


			$arr['a'] = "а";
			$arr['b'] = "б";
			$arr['v'] = "в";
			$arr['g'] = "г";
			$arr['d'] = "д";
			$arr['e'] = "е";
			$arr['z'] = "з";
			$arr['i'] = "и";
			$arr['y'] = "й";
			$arr['k'] = "к";
			$arr['l'] = "л";
			$arr['m'] = "м";
			$arr['n'] = "н";
			$arr['o'] = "о";
			$arr['p'] = "п";
			$arr['r'] = "р";
			$arr['s'] = "с";
			$arr['t'] = "т";
			$arr['u'] = "у";
			$arr['f'] = "ф";
			$arr['h'] = "х";
			$arr['c'] = "ц";
			$arr['A'] = "А";
			$arr['B'] = "Б";
			$arr['V'] = "В";
			$arr['G'] = "Г";
			$arr['D'] = "Д";
			$arr['E'] = "Е";
			$arr['Z'] = "З";
			$arr['I'] = "И";
			$arr['Y'] = "Й";
			$arr['K'] = "К";
			$arr['L'] = "Л";
			$arr['M'] = "М";
			$arr['N'] = "Н";
			$arr['O'] = "О";
			$arr['P'] = "П";
			$arr['R'] = "Р";
			$arr['S'] = "С";
			$arr['T'] = "Т";
			$arr['U'] = "У";
			$arr['F'] = "Ф";
			$arr['H'] = "Х";
			$arr['C'] = "Ц";
		}
		foreach($arr AS $w => $k){
			$string = str_replace($w,$k,$string);
		}

		return $string;
	}


	//
	function hach_string($string,$en=false){
		$s = "";
		if($en){
			$arr['a'] = "а";
			$arr['b'] = "б";
			$arr['v'] = "в";
			$arr['g'] = "г";
			$arr['d'] = "д";
			$arr['e'] = "е";
			$arr['e'] = "ё";
			$arr['gh'] = "ж";
			$arr['z'] = "з";
			$arr['i'] = "и";
			$arr['y'] = "й";
			$arr['k'] = "к";
			$arr['l'] = "л";
			$arr['m'] = "м";
			$arr['n'] = "н";
			$arr['o'] = "о";
			$arr['p'] = "п";
			$arr['r'] = "р";
			$arr['c'] = "с";
			$arr['t'] = "т";
			$arr['u'] = "у";
			$arr['f'] = "ф";
			$arr['h'] = "х";
			$arr['c'] = "ц";
			$arr['ch'] = "ч";
			$arr['sh'] = "ш";
			$arr['shc'] = "щ";
			$arr['mz'] = "ь";
			$arr['i'] = "ы";
			$arr['tz'] = "ъ";
			$arr['e'] = "э";
			$arr['yu'] = "ю";
			$arr['ya'] = "я";
			$arr['A'] = "А";
			$arr['B'] = "Б";
			$arr['V'] = "В";
			$arr['G'] = "Г";
			$arr['D'] = "Д";
			$arr['E'] = "Е";
			$arr['E'] = "Ё";
			$arr['GH'] = "Ж";
			$arr['Z'] = "З";
			$arr['I'] = "И";
			$arr['Y'] = "Й";
			$arr['K'] = "К";
			$arr['L'] = "Л";
			$arr['M'] = "М";
			$arr['N'] = "Н";
			$arr['O'] = "О";
			$arr['P'] = "П";
			$arr['R'] = "Р";
			$arr['S'] = "С";
			$arr['T'] = "Т";
			$arr['U'] = "У";
			$arr['F'] = "Ф";
			$arr['H'] = "Х";
			$arr['C'] = "Ц";
			$arr['CH'] = "Ч";
			$arr['SH'] = "Ш";
			$arr['SHC'] = "Щ";
			$arr['MZ'] = "Ь";
			$arr['I'] = "Ы";
			$arr['TZ'] = "Ъ";
			$arr['E'] = "Э";
			$arr['YU'] = "Ю";
			$arr['YA'] = "Я";
			foreach($arr AS $w => $k){
				$string = str_replace("*".$w."*",$k,$string);
			}
		} else {
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
			$arr['ь'] = "mz";
			$arr['ы'] = "i";
			$arr['ъ'] = "tz";
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
			$arr['Ь'] = "MZ";
			$arr['Ы'] = "I";
			$arr['Ъ'] = "TZ";
			$arr['Э'] = "E";
			$arr['Ю'] = "YU";
			$arr['Я'] = "YA";
			foreach($arr AS $w => $k){
				$string = str_replace($w,"*".$k."*",$string);
			}
		}

		return $s;
	}
?>