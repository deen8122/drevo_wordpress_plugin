<? if (!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  Формирование Excel-файла по рубрикатору
*
*  ООО "УфаПиар.ру"
*
*  Разработчик:  Галлямов Д.Р.
*  e-mail: like-person@ya.ru
**********/
//echo HOST;
$type = (int)$_GET['type'];
global$name_href;
if ($type>0)
{
	addSubMenu(teGetUrlQuery("pg=rubric","type=".$type),'Вернуться');
	print "<div align=center>";
	list($name_type) = $database->getArrayOfQuery("select rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0 && ID_RUBRIC_TYPE=".$type);
	if (!empty($name_type))
	{
		$frm = new teForm("form1","post");
		print "<h2>Формирование Excel-файла по рубрикатору: ".$name_type."</h2>";
		//print '<div style="color:red;width:350px;border:1px solid black; font-weight:bold;margin:10px;padding:5px;">Внимание! Большие текста и описания избавляются от тегов и обрезаются до 255 символов</div>';

		$frm->addf_checkboxGroup("feats", "Экспорт записей<br/> с характеристиками:");
		$frm->addf_checkboxItem("feats", 'ID', 'ID', true);
		$r = $database->query("select ID_FEATURE,feature_text,feature_type from cprice_features natural join cprice_rubric_features where feature_enable=1 && feature_deleted=0 && ID_RUBRIC=0 && rubric_type=".$type);
		$fts = array();
		while ($row = mysql_fetch_array($r))
		{
			$frm->addf_checkboxItem("feats", $row[0], $row[1], true);
			$fts[$row[0]]=array($row[1],$row[2]);
		}
		$frm->addf_checkbox("img", 'Отображать наличие картинки');
                                        $frm->addf_checkbox("name_href", 'Выводить название в виде ссылки');// ВУУТС
		$frm->setf_require("feats");
		if (!$frm->send())
		{
			$img = $frm->get_value_checkbox("img",false);
			$name_href = $frm->get_value_checkbox("name_href",false);
			$feats = $frm->get_value('feats');
			$n_flds = count($feats);
			$s1 = ob_get_contents();
			ob_end_clean();
			unset($s1);
header("Content-type: application/vnd.ms-excel");  
header("Content-disposition: attachment; filename='catalog_" . date("Y-m-d") . ".xls"); 
$out = <<<TXT
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" /> 
<title>Пример</title> 
<style>  
     br {  
         mso-data-placement:same-cell;  
     }  
     .style0 {  
        mso-number-format:General;  
        white-space:nowrap;  
        mso-style-id:0;  
    }  
    td {  
        mso-style-parent:style0;  
        text-align: left;  
    }  
        td.head {
		font-weight:bold;
		text-align:center;
	}
	td.rub {
		font-weight:bold;
	}
	td.date {  
        mso-number-format:"dd.mm.yyyy";  
    }  
    td.time {  
        mso-number-format:"[h]:mm:ss";  
    }  
    td.number {  
        mso-number-format:"0";  
    }  
</style>  
</head> 
<body> 
<table border="1">
TXT;
$i=0;
			$out .='<tr>';
			foreach($feats as $feat)
			{
				$size = 5;$name = $feat;
				if($feat!='ID')
				{
					$vals = $fts[$feat];
					if($vals[1]==7)$size = 30;
					if($vals[1]==2)$size = 15;
					$name = $vals[0];
				}
				$out .='<td class="head">'.$name.'</td>';
				$i++;
			}
			if ($img)
			{

				$out .='<td class="head">Картинка</td>';
			}
			$out .='</tr>';
function rubs($type,$parent,$pref='')
{
	global $database,$out,$n_flds,$feats,$img,$name_href,$fts;
	$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_parent=$parent && rubric_type=".$type." order by rubric_pos");
	//$DEBUG_I=0;
	while ($row = mysql_fetch_array($res))
	{
	//    $DEBUG_I++;
	 //   if($DEBUG_I>5)continue;
		$out .='<tr><td colspan="'.($img ? ($n_flds+1) : $n_flds).'" class="rub">'.$pref.$row[1].'</td></tr>';
		$data = getData($row[0], 'ID_GOOD', '', $feats);
		if (count($data)>0)
		{
			foreach($data as $gid=>$vals)
			{
				$out .='<tr>';
				for ($i=0;$i<$n_flds;$i++)
				{
					if ($feats[$i]=='ID') $out .='<td class="number">'.$gid.'</td>';
					else {
					    if($name_href && $feats[$i]=='30' ){
						$answertext = str_replace(array("\n","\r","\t")," ",$vals[$feats[$i]]);
						$answertext = htmlspecialchars($answertext,ENT_COMPAT,'cp1251');
						$out .='<td><a href="http://irbis-shop.ru'.create_url($row[0],$gid).'">'.$answertext.'</a></td>';
					    }else{
						$type_feats = $fts[$feats[$i]];
						$answertext = str_replace(array("\n","\r","\t")," ",$vals[$feats[$i]]);
						$answertext = htmlspecialchars($answertext,ENT_COMPAT,'cp1251');
						$out .='<td'.($type_feats[1]==1?' class="number"':'').'>'.$answertext.'</td>';
					    }
					    
					}
				}
				if ($img)
				{
					list($im) = $database->getArrayOfQuery("select ID_GOOD_PHOTO from cprice_goods_photos where ID_GOOD=$gid && goodphoto_visible=1 && goodphoto_deleted=0 limit 1");
					$out .='<td>'.($im>0?'есть':'нет').'</td>';
				}
				$out .='</tr>';
				$num++;
			}
		}
		rubs($type,$row[0],$pref.$row[1].'>>');
	}
}
		rubs($type,0);
		$out .= '</table> 
</body> 
</html>';
print $out;
die();	      
/*	      
			// библиотека ексель
			teInclude("excel");
			$workbook = new Spreadsheet_Excel_Writer();
			$workbook->send("catalog.xls");
		   	$worksheet =& $workbook->addWorksheet('Каталог товаров');
			$num=0;
			$frmt = & $workbook->addFormat();
			$frmt->setBold();
			$frmt->setBorder(1);
			$frmt->setAlign('center');
			$frmt->setVAlign('vcenter');
			$frmt->setSize(10);
			$frmt->setTextWrap();
			$i=0;
			foreach($feats as $feat)
			{
				$size = 5;$name = $feat;
				if($feat!='ID')
				{
					$vals = $fts[$feat];
					if($vals[1]==7)$size = 30;
					if($vals[1]==2)$size = 15;
					$name = $vals[0];
				}
				$worksheet->setColumn(0,$i,$size);
				$worksheet->writeString($num, $i, $name,$frmt);
				$i++;
			}
			
			
			if ($img)
			{
				$worksheet->setColumn(0,$i,'5');
				$worksheet->writeString($num, $i, 'Картинка',$frmt);
			}
			unset($frmt);
			$frmt = & $workbook->addFormat();
			$frmt->setBorder(1);
			$frmt->setVAlign('vcenter');
			$frmt->setSize(10);
			$frmt->setTextWrap();
			
			$frmt2 = & $workbook->addFormat();
			$frmt2->setBorder(1);
			$frmt2->setVAlign('vcenter');
			$frmt2->setSize(10);
			$frmt2->setTextWrap();
			$frmt2->setColor('blue');
			$num++;
function rubs($type,$parent,$pref='')
{
	global $database,$worksheet,$frmt,$frmt2,$num,$n_flds,$feats,$img,$name_href;
	$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_parent=$parent && rubric_type=".$type." order by rubric_pos");
	//$DEBUG_I=0;
	while ($row = mysql_fetch_array($res))
	{
	//    $DEBUG_I++;
	 //   if($DEBUG_I>5)continue;
		if ($img)
		{
			$worksheet->setMerge($num, 0, $num, $n_flds);
			$worksheet->writeString($num, 0, $pref.$row[1],$frmt);
			for ($i=1;$i<=$n_flds;$i++)
				$worksheet->writeString($num, $i, "",$frmt);
		}
		else
		{
			$worksheet->setMerge($num, 0, $num, ($n_flds-1));
			$worksheet->writeString($num, 0, $pref.$row[1],$frmt);
			for ($i=1;$i<$n_flds;$i++)
				$worksheet->writeString($num, $i, "",$frmt);
		}
		$num++;
		$data = getData($row[0], 'ID_GOOD', '', $feats);
		if (count($data)>0)
		{
			foreach($data as $gid=>$vals)
			{
				for ($i=0;$i<$n_flds;$i++)
				{
					if ($feats[$i]=='ID') $worksheet->writeString($num, $i, $gid,$frmt);
					else {
					    if($name_href && $feats[$i]=='26' ){
						
						$worksheet->writeUrl($num, $i,create_url($row[0],$gid),strip_tags(@$vals[$feats[$i]]),$frmt2);
						//$frmt->setColor('black');
						// $worksheet->writeString($num, $i,'->'.strip_tags(@$vals[$feats[$i]]),$frmt);
						 // $worksheet->writeString($num, $i, '<a href="">'.strip_tags(@$vals[$feats[$i]]).'</a>'  ,$frmt);
						// $worksheet->writeString($num, $i,$row[0]."/".$gid,$frmt);
					    }else{
						
					    $worksheet->writeString($num, $i, strip_tags(@$vals[$feats[$i]]),$frmt);
					    }
					    
					}
				}
				if ($img)
				{
					list($im) = $database->getArrayOfQuery("select ID_GOOD_PHOTO from cprice_goods_photos where ID_GOOD=$gid && goodphoto_visible=1 && goodphoto_deleted=0 limit 1");
					$worksheet->writeString($num, $i, ($im>0?'есть':'нет'),$frmt);
				}
				$num++;
			}
		}
		rubs($type,$row[0],$pref.'>>');
	}
}
	      rubs($type,0);
		   $workbook->close();*/
		   exit;
		}
	}else print "Error";
	print "</div>";
}else print "Error";



function create_url($id_rubric , $id_good){
      global $database;
                    $db_hostname = 'localhost';
                    $db_database = 'cprice';
                    $db_username = 'cprice';
                    $db_password = '7PN9YFRARdduGFKG';

                    $db = mysql_connect ($db_hostname, $db_username, $db_password);
                    mysql_select_db ($db_database, $db);
		    
        $res = mysql_query("select * from cprice.hosts where ID_HOST=".$_GET['curbase']);
        $row = mysql_fetch_assoc($res);
        $res = $database->query("select rubric_textid from cprice_rubric where ID_RUBRIC=".$id_rubric);
	$row2 = mysql_fetch_assoc($res);
        $res = $database->query("select good_url from cprice_goods where ID_GOOD=".$id_good);
	$row3 = mysql_fetch_assoc($res);
    return $row['url'].'/katalog/'.$row2['rubric_textid'].'/'.$row3['good_url'];
}
?>