<?


	require_once $CURFLD."numtotext.php";
	require_once $CURFLD."index2.php";



	// ��������� ����� �� ������� ���������� ��������, ��� ����, ���� ���������, �� ��������� "..." � �����.
	function smallText($txt,$n=20){
		if(strlen($txt)>$n+1){
			return substr($txt,0,$n)."...";
		} else {
			return $txt;
		}
	}
//�������� ������ �� ���-�� �������� ��� �� ������
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
	// ���������� ������������ ������, ������������ ��� ����� ����� � unix
	function filename($string){		$new_string = '';
		for($i=0;$i<strlen($string);$i++)
		{			if(eregi("[0-9a-z�-�_-]",$string[$i]))$new_string .= $string[$i];
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
		$arr['�'] = "";
		$arr['�'] = "";
		$arr['�'] = "";

		foreach($arr AS $w => $k){
			$string = str_replace($w,$k,$string);
		}

		return $string;
	}


	//
	function translit($string,$en=false){
		$arr['�'] = "a";
		$arr['�'] = "b";
		$arr['�'] = "v";
		$arr['�'] = "g";
		$arr['�'] = "d";
		$arr['�'] = "e";
		$arr['�'] = "e";
		$arr['�'] = "gh";
		$arr['�'] = "z";
		$arr['�'] = "i";
		$arr['�'] = "y";
		$arr['�'] = "k";
		$arr['�'] = "l";
		$arr['�'] = "m";
		$arr['�'] = "n";
		$arr['�'] = "o";
		$arr['�'] = "p";
		$arr['�'] = "r";
		$arr['�'] = "s";
		$arr['�'] = "t";
		$arr['�'] = "u";
		$arr['�'] = "f";
		$arr['�'] = "h";
		$arr['�'] = "c";
		$arr['�'] = "ch";
		$arr['�'] = "sh";
		$arr['�'] = "shc";
		$arr['�'] = "";
		$arr['�'] = "i";
		$arr['�'] = "";
		$arr['�'] = "e";
		$arr['�'] = "yu";
		$arr['�'] = "ya";
		$arr['�'] = "A";
		$arr['�'] = "B";
		$arr['�'] = "V";
		$arr['�'] = "G";
		$arr['�'] = "D";
		$arr['�'] = "E";
		$arr['�'] = "E";
		$arr['�'] = "GH";
		$arr['�'] = "Z";
		$arr['�'] = "I";
		$arr['�'] = "Y";
		$arr['�'] = "K";
		$arr['�'] = "L";
		$arr['�'] = "M";
		$arr['�'] = "N";
		$arr['�'] = "O";
		$arr['�'] = "P";
		$arr['�'] = "R";
		$arr['�'] = "S";
		$arr['�'] = "T";
		$arr['�'] = "U";
		$arr['�'] = "F";
		$arr['�'] = "H";
		$arr['�'] = "C";
		$arr['�'] = "CH";
		$arr['�'] = "SH";
		$arr['�'] = "SHC";
		$arr['�'] = "";
		$arr['�'] = "I";
		$arr['�'] = "";
		$arr['�'] = "E";
		$arr['�'] = "YU";
		$arr['�'] = "YA";
		$arr['�'] = "#";

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

			$arr['��'] = "sch";
			$arr['��'] = "Sch";
			$arr['��'] = "SCH";

			$arr['�'] = "a";
			$arr['�'] = "b";
			$arr['�'] = "v";
			$arr['�'] = "g";
			$arr['�'] = "d";
			$arr['�'] = "e";
			$arr['�'] = "_e";
			$arr['�'] = "gh";
			$arr['�'] = "z";
			$arr['�'] = "i";
			$arr['�'] = "y";
			$arr['�'] = "k";
			$arr['�'] = "l";
			$arr['�'] = "m";
			$arr['�'] = "n";
			$arr['�'] = "o";
			$arr['�'] = "p";
			$arr['�'] = "r";
			$arr['�'] = "s";
			$arr['�'] = "t";
			$arr['�'] = "u";
			$arr['�'] = "f";
			$arr['�'] = "h";
			$arr['�'] = "c";
			$arr['�'] = "ch";
			$arr['�'] = "sh";
			$arr['�'] = "shc";
			$arr['�'] = "_";
			$arr['�'] = "i_";
			$arr['�'] = "\"";
			$arr['�'] = "e_";
			$arr['�'] = "yu";
			$arr['�'] = "ya";
			$arr['�'] = "A";
			$arr['�'] = "B";
			$arr['�'] = "V";
			$arr['�'] = "G";
			$arr['�'] = "D";
			$arr['�'] = "E";
			$arr['�'] = "_E";
			$arr['�'] = "GH";
			$arr['�'] = "Z";
			$arr['�'] = "I";
			$arr['�'] = "Y";
			$arr['�'] = "K";
			$arr['�'] = "L";
			$arr['�'] = "M";
			$arr['�'] = "N";
			$arr['�'] = "O";
			$arr['�'] = "P";
			$arr['�'] = "R";
			$arr['�'] = "S";
			$arr['�'] = "T";
			$arr['�'] = "U";
			$arr['�'] = "F";
			$arr['�'] = "H";
			$arr['�'] = "C";
			$arr['�'] = "CH";
			$arr['�'] = "SH";
			$arr['�'] = "SHC";
			$arr['�'] = "__";
			$arr['�'] = "I_";
			$arr['�'] = "\"\"";
			$arr['�'] = "E'";
			$arr['�'] = "YU";
			$arr['�'] = "YA";
		} else {


			$arr['__'] = "�";
			$arr['_e'] = "�";
			$arr['i_'] = "�";
			$arr['e_'] = "�";
			$arr['_E'] = "�";
			$arr['I_'] = "�";
			$arr['E_'] = "�";
			$arr['""'] = "�";
			$arr['_'] = "�";
			$arr['"'] = "�";


			$arr['sch'] = "��";
			$arr['Sch'] = "��";
			$arr['SCH'] = "��";


			$arr['gh'] = "�";
			$arr['ch'] = "�";

			$arr['shc'] = "�";
			$arr['sh'] = "�";
			$arr['yu'] = "�";
			$arr['ya'] = "�";

			$arr['GH'] = "�";
			$arr['YU'] = "�";
			$arr['CH'] = "�";
			$arr['SHC'] = "�";
			$arr['SH'] = "�";
			$arr['YA'] = "�";


			$arr['a'] = "�";
			$arr['b'] = "�";
			$arr['v'] = "�";
			$arr['g'] = "�";
			$arr['d'] = "�";
			$arr['e'] = "�";
			$arr['z'] = "�";
			$arr['i'] = "�";
			$arr['y'] = "�";
			$arr['k'] = "�";
			$arr['l'] = "�";
			$arr['m'] = "�";
			$arr['n'] = "�";
			$arr['o'] = "�";
			$arr['p'] = "�";
			$arr['r'] = "�";
			$arr['s'] = "�";
			$arr['t'] = "�";
			$arr['u'] = "�";
			$arr['f'] = "�";
			$arr['h'] = "�";
			$arr['c'] = "�";
			$arr['A'] = "�";
			$arr['B'] = "�";
			$arr['V'] = "�";
			$arr['G'] = "�";
			$arr['D'] = "�";
			$arr['E'] = "�";
			$arr['Z'] = "�";
			$arr['I'] = "�";
			$arr['Y'] = "�";
			$arr['K'] = "�";
			$arr['L'] = "�";
			$arr['M'] = "�";
			$arr['N'] = "�";
			$arr['O'] = "�";
			$arr['P'] = "�";
			$arr['R'] = "�";
			$arr['S'] = "�";
			$arr['T'] = "�";
			$arr['U'] = "�";
			$arr['F'] = "�";
			$arr['H'] = "�";
			$arr['C'] = "�";
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
			$arr['a'] = "�";
			$arr['b'] = "�";
			$arr['v'] = "�";
			$arr['g'] = "�";
			$arr['d'] = "�";
			$arr['e'] = "�";
			$arr['e'] = "�";
			$arr['gh'] = "�";
			$arr['z'] = "�";
			$arr['i'] = "�";
			$arr['y'] = "�";
			$arr['k'] = "�";
			$arr['l'] = "�";
			$arr['m'] = "�";
			$arr['n'] = "�";
			$arr['o'] = "�";
			$arr['p'] = "�";
			$arr['r'] = "�";
			$arr['c'] = "�";
			$arr['t'] = "�";
			$arr['u'] = "�";
			$arr['f'] = "�";
			$arr['h'] = "�";
			$arr['c'] = "�";
			$arr['ch'] = "�";
			$arr['sh'] = "�";
			$arr['shc'] = "�";
			$arr['mz'] = "�";
			$arr['i'] = "�";
			$arr['tz'] = "�";
			$arr['e'] = "�";
			$arr['yu'] = "�";
			$arr['ya'] = "�";
			$arr['A'] = "�";
			$arr['B'] = "�";
			$arr['V'] = "�";
			$arr['G'] = "�";
			$arr['D'] = "�";
			$arr['E'] = "�";
			$arr['E'] = "�";
			$arr['GH'] = "�";
			$arr['Z'] = "�";
			$arr['I'] = "�";
			$arr['Y'] = "�";
			$arr['K'] = "�";
			$arr['L'] = "�";
			$arr['M'] = "�";
			$arr['N'] = "�";
			$arr['O'] = "�";
			$arr['P'] = "�";
			$arr['R'] = "�";
			$arr['S'] = "�";
			$arr['T'] = "�";
			$arr['U'] = "�";
			$arr['F'] = "�";
			$arr['H'] = "�";
			$arr['C'] = "�";
			$arr['CH'] = "�";
			$arr['SH'] = "�";
			$arr['SHC'] = "�";
			$arr['MZ'] = "�";
			$arr['I'] = "�";
			$arr['TZ'] = "�";
			$arr['E'] = "�";
			$arr['YU'] = "�";
			$arr['YA'] = "�";
			foreach($arr AS $w => $k){
				$string = str_replace("*".$w."*",$k,$string);
			}
		} else {
			$arr['�'] = "a";
			$arr['�'] = "b";
			$arr['�'] = "v";
			$arr['�'] = "g";
			$arr['�'] = "d";
			$arr['�'] = "e";
			$arr['�'] = "e";
			$arr['�'] = "gh";
			$arr['�'] = "z";
			$arr['�'] = "i";
			$arr['�'] = "y";
			$arr['�'] = "k";
			$arr['�'] = "l";
			$arr['�'] = "m";
			$arr['�'] = "n";
			$arr['�'] = "o";
			$arr['�'] = "p";
			$arr['�'] = "r";
			$arr['�'] = "s";
			$arr['�'] = "t";
			$arr['�'] = "u";
			$arr['�'] = "f";
			$arr['�'] = "h";
			$arr['�'] = "c";
			$arr['�'] = "ch";
			$arr['�'] = "sh";
			$arr['�'] = "shc";
			$arr['�'] = "mz";
			$arr['�'] = "i";
			$arr['�'] = "tz";
			$arr['�'] = "e";
			$arr['�'] = "yu";
			$arr['�'] = "ya";
			$arr['�'] = "A";
			$arr['�'] = "B";
			$arr['�'] = "V";
			$arr['�'] = "G";
			$arr['�'] = "D";
			$arr['�'] = "E";
			$arr['�'] = "E";
			$arr['�'] = "GH";
			$arr['�'] = "Z";
			$arr['�'] = "I";
			$arr['�'] = "Y";
			$arr['�'] = "K";
			$arr['�'] = "L";
			$arr['�'] = "M";
			$arr['�'] = "N";
			$arr['�'] = "O";
			$arr['�'] = "P";
			$arr['�'] = "R";
			$arr['�'] = "S";
			$arr['�'] = "T";
			$arr['�'] = "U";
			$arr['�'] = "F";
			$arr['�'] = "H";
			$arr['�'] = "C";
			$arr['�'] = "CH";
			$arr['�'] = "SH";
			$arr['�'] = "SHC";
			$arr['�'] = "MZ";
			$arr['�'] = "I";
			$arr['�'] = "TZ";
			$arr['�'] = "E";
			$arr['�'] = "YU";
			$arr['�'] = "YA";
			foreach($arr AS $w => $k){
				$string = str_replace($w,"*".$k."*",$string);
			}
		}

		return $s;
	}
?>