<?

/**********
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Никулин А.
**********/


print "<h2>Установка водяного знака</h2>";
print "<div align=center>";
print "<div class='note'>Приоритет у текста выше</div>";
//print DATA_FLD."watermark.png";
$result = mysql_query("SELECT * FROM ".DB_PREFIX."configtable WHERE  var_name='wm_pos'") or die("Запрос не выполнен");
$line = mysql_fetch_array($result);
$res = mysql_query("SELECT * FROM ".DB_PREFIX."configtable WHERE  var_name='wm_alfa'");
$ln = mysql_fetch_array($res);
	if(file_exists(DATA_FLD."watermark.png")){
		//print "Водяной знак установлен";
		$path=URLDATA_FLD."watermark.png";
		print "<img src=$path>";
	}

			list($wm_text1)=$database->getArrayOfquery("SELECT var_value FROM ".DB_PREFIX."configtable WHERE  var_name='wm_text'");
			$frm = new teForm("form1","POST");
			$frm->addTitle("<h2></h2>");
			$frm->addf_file("fileform", getIdToPrint_config("configtable",'fileform')." Выберите <b>png</b> файл");
			$frm->addf_text("textcapture", getIdToPrint_config("configtable",'wm_text')." Или введите текст:");
			$frm->addf_desc("textcapture", "url и номер телефона(без пробелов в цифрах)");
			$frm->addf_radioGroup("where_watermark", getIdToPrint_config("configtable",'wm_pos')." Расположение водяного знака");

			if(!empty($wm_text1))
			{
				$frm->add_value("textcapture", $wm_text1);
			}
	//расположение водяного знака
	if(mysql_num_rows($result)==0){$chek1=true;$chek2=false;$chek3=false;$chek4=false;$chek5=false;$chek6=false;$chek7=false;}
	else
	{
	 	    if($line['var_value']=='1'){$chek1=true;}else{$chek1=false;}
   			if($line['var_value']=='2'){$chek2=true;}else{$chek2=false;}
		    if($line['var_value']=='3'){$chek3=true;}else{$chek3=false;}
			if($line['var_value']=='4'){$chek4=true;}else{$chek4=false;}
			if($line['var_value']=='5'){$chek5=true;}else{$chek5=false;}
			if($line['var_value']=='6'){$chek6=true;}else{$chek6=false;}
			if($line['var_value']=='7'){$chek7=true;}else{$chek7=false;}
	}

			$frm->addf_radioItem("where_watermark","1" ,"Левый верхний угол" , $checked = $chek1);
			$frm->addf_radioItem("where_watermark","2" ,"Правый верхний угол" , $checked = $chek2);
			$frm->addf_radioItem("where_watermark","3" ,"Левый нижний угол" , $checked = $chek3);
			$frm->addf_radioItem("where_watermark","4" ,"Правый нижний угол" , $checked = $chek4);
			$frm->addf_radioItem("where_watermark","5" ,"Сверху" , $checked = $chek5);
			$frm->addf_radioItem("where_watermark","6" ,"Снизу" , $checked = $chek6);
			$frm->addf_radioItem("where_watermark","7" ,"По центру" , $checked = $chek7);
	//выбор цвета
	$frm->addf_radioGroup("color_watermark", getIdToPrint_config("configtable",'wm_color')." Цвет текста");
	$res_color=$database->query("SELECT var_value FROM ".DB_PREFIX."configtable WHERE  var_name='wm_color'");
	$row_color=mysql_fetch_array($res_color);
	if(mysql_num_rows($res_color)==0){$color1=true;$color2=false;$color3=false;$color4=false;$color5=false;$color6=false;$color7=false;}
	else
	{
	 	    if($row_color['var_value']=='1'){$color1=true;}else{$color1=false;}
   			if($row_color['var_value']=='2'){$color2=true;}else{$color2=false;}
		    if($row_color['var_value']=='3'){$color3=true;}else{$color3=false;}
			if($row_color['var_value']=='4'){$color4=true;}else{$color4=false;}
			if($row_color['var_value']=='5'){$color5=true;}else{$color5=false;}
			if($row_color['var_value']=='6'){$color6=true;}else{$color6=false;}
			if($row_color['var_value']=='7'){$color7=true;}else{$color7=false;}
	}
			$frm->addf_radioItem("color_watermark","1" ,"Синий" , $check = $color1);
			$frm->addf_radioItem("color_watermark","2" ,"Красный" , $check = $color2);
			$frm->addf_radioItem("color_watermark","3" ,"Желтый" , $check = $color3);
			$frm->addf_radioItem("color_watermark","4" ,"Белый" , $check = $color4);
			$frm->addf_radioItem("color_watermark","5" ,"Черный" , $check = $color5);
			$frm->addf_radioItem("color_watermark","6" ,"Зеленый" , $check = $color6);
			$frm->addf_radioItem("color_watermark","7" ,"Оранжевый" , $check = $color7);
//------------------------------------------------------------------------------------------------------------------------------
            if(empty($ln['var_value'])){$alfa_level=50;}else{$alfa_level=$ln['var_value'];}
            $frm->addf_text('alfa_level', getIdToPrint_config("configtable",'wm_alfa').' Прозрачность число от 0 до 100',$alfa_level);

            $frm->setFieldWidth('alfa_level','30px');


            if(!$frm->send())
            {
	        	$alfa_level=$frm->get_value('alfa_level');
	        	if(!empty($alfa_level) && is_array($alfa_level)) $alfa_level=@$alfa_level[0].@$alfa_level[1].@$alfa_level[2];
	        }
	        	//$alfa_level=$frm->get_value('alfa_level');
	        	//$alfa_level=$alfa_level[0];
	            if(mysql_num_rows($res)==0)
	            {
					if(empty($alfa_level) || $alfa_level<0 || $alfa_level>100 || !is_integer(intval($alfa_level))){$alfa_level=50;}
					mysql_query("INSERT INTO ".DB_PREFIX."configtable (var_name,var_value) values('wm_alfa',$alfa_level)");
	            }
	            else
	            {
					if(empty($alfa_level) || $alfa_level<0 || $alfa_level>100 || !is_integer(intval($alfa_level))){$alfa_level=50;}
					$database -> query("UPDATE ".DB_PREFIX."configtable set var_value=$alfa_level WHERE var_name='wm_alfa'");
				}

//if(isset($_REQUEST['fileform'])){print $_REQUEST['fileform'];}
//Если введен текст
if(empty($_POST['textcapture'][0]))
{
	if(isset($_FILES['fileform']['name']))
	{
	   //print substr($_FILES['fileform']['name'],-3)."<br>";
	   if(substr($_FILES['fileform']['name'],-3)!="png" ){print "<div class=\"warning\">Только png файл</div>";}
	   else
	   {
			$upfile=DATA_FLD.'watermark.png';
			if(!move_uploaded_file($_FILES['fileform']['tmp_name'],$upfile)){print "Невозможно переместить файл";exit;}
			//print "Файл успешно загружен";
	   }
	}
	if(isset($_POST['where_watermark']))
	{
		$pos=$_POST['where_watermark'];
		if(mysql_num_rows($result)<1)
		{
			$database -> query("INSERT INTO ".DB_PREFIX."configtable (var_name,var_value) values('wm_pos','$pos')");
		}else
		{
			$database -> query("UPDATE ".DB_PREFIX."configtable set var_value='$pos' WHERE var_name='wm_pos'");
		}
	}
}
else
{
	//текст текста))))
	$wm_text=$_POST['textcapture'][0];
	$wm_text=str_replace("'","",$wm_text);
	$res_text = $database->query("SELECT * FROM ".DB_PREFIX."configtable WHERE  var_name='wm_text'");
	if(mysql_num_rows($res_text)<1)
	{
		$database -> query("INSERT INTO ".DB_PREFIX."configtable (var_name,var_value) values('wm_text','$wm_text')");
	}else
	{
		$database -> query("UPDATE ".DB_PREFIX."configtable set var_value='$wm_text' WHERE var_name='wm_text'");
	}
	//цвет текста
	$color=$_POST['color_watermark'];
	//$color=str_replace("'","",$wm_text);
	$res_text = $database->query("SELECT * FROM ".DB_PREFIX."configtable WHERE  var_name='wm_color'");
	if(mysql_num_rows($res_text)<1)
	{
		$database -> query("INSERT INTO ".DB_PREFIX."configtable (var_name,var_value) values('wm_color','$color')");
	}else
	{
		$database -> query("UPDATE ".DB_PREFIX."configtable set var_value='$color' WHERE var_name='wm_color'");
	}
	//положение текста
	if(isset($_POST['where_watermark']))
	{
		$pos=$_POST['where_watermark'];
		if(mysql_num_rows($result)<1)
		{
			$database -> query("INSERT INTO ".DB_PREFIX."configtable (var_name,var_value) values('wm_pos','$pos')");
		}else
		{
			$database -> query("UPDATE ".DB_PREFIX."configtable set var_value='$pos' WHERE var_name='wm_pos'");
		}
	}

	print "<br>Установлен текст-<b>\"".$_POST['textcapture'][0]."\"</b>";
}
print "</div>";
?>