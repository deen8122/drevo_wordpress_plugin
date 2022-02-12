<?php

addGet("type",$_GET['type']);

//Переменные сессии
if(isset($_GET['type']))
{
	$type=$_GET['type'];
}
//Возвращает тип характеристики
function getTypeFeature($feature)
{
	global $database;
	$feattype=$database->getArrayOfquery("SELECT feature_type FROM ".DB_PREFIX."features WHERE ID_FEATURE='$feature'");
	return $feattype[0];
}

//Функция установки значений свойств характеристики в зависимости от ее типа
//Использую только для копирования свойств с шаблона
function setFeatureText($good,$feature,$featval,$feattype)
{
	   	/*
		("feature_type", "1", "Число (возможны не целые числа)");
		("feature_type", "2", "Текст (максимум 255 символов)");
		("feature_type", "7", "Большой текст (максимум 65535 символов)");
		("feature_type", "3", "Логический (да или нет)");
		("feature_type", "4", "Справочник");
		("feature_type", "5", "Динамическая ветвь");
		("feature_type", "9", "Справочник рубрик раздела");
		("feature_type", "6", "Файл");
		("feature_type", "8", "Дата");
		*/
	if (!$handle = fopen("c:\debug1.txt", 'a')) {}
				$somecontent=$feattype."\r\n";
				if(fwrite($handle, $somecontent) === FALSE) {exit;}
	fclose($handle);

	global $database;
	switch ((int)$feattype)
	{
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		case 8:
		case 9:
			//Есть ли такая запись в goods_features
			$quer="UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='$featval' WHERE ID_GOOD='$good' AND ID_FEATURE='$feature'";

			$database->query($quer);

		break;
			//Есть ли такая запись в goods_features
		case 7:

			//Присваиваем для нового элемента большого текста новый идентификатор и вставляем соответствующие записи

				$database->query("INSERT INTO ".DB_PREFIX."texts (ID_TEXT) VALUES('')");
				list($new_feat_val)=$database->getArrayOfquery("SELECT MAX(ID_TEXT) FROM ".DB_PREFIX."texts");
				//новый идентификатор для текстового поля -он же максимальный
				$quer="UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='$new_feat_val' WHERE ID_GOOD='$good' AND ID_FEATURE='$feature'";
				$database->query($quer);

				if (!$handle = fopen("c:\debug.txt", 'a')) {}
				$somecontent=$quer."\r\n";
				if (fwrite($handle, $somecontent) === FALSE) {exit;}
				fclose($handle);

				//копируем если возможно
				if(!empty($featval))
				{
					list($feat_text)=$database->getArrayOfquery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT='$featval'");
					$database->query("UPDATE ".DB_PREFIX."texts SET text_text='$feat_text' WHERE ID_TEXT='$new_feat_val'");
				}
		break;
	}
}

//Функция размножения
// $idf - массив свойств которые не будут копироваться с шаблона
// $rubriccount - количество новых записей
function rubricmultiplicate($idf=array(),$rubriccount,$rubric_id,$type)
{
	global $database;
	$max_good=$database->getArrayOfquery("SELECT MAX(ID_RUBRIC) FROM ".DB_PREFIX."rubric");
	$curgood=$max_good[0]+1;

	$prov=$database->getArrayOfquery("SELECT * FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=",MYSQL_ASSOC);
    del_cache($rubric_id);//удаление кэша
    for($i=1;$i<=$rubriccount;$i++)
	{
		$rubric_name="(Копия $i) ". $prov['rubric_name'];
		$rubric_textid="(copy$i)". $prov['rubric_textid'];

		$query_add_rubric="INSERT INTO ".DB_PREFIX."rubric VALUES('$curgood','$rubric_textid','$prov[rubric_parent]','$rubric_name','$prov[rubric_unit_prefixname]','$prov[rubric_ex]','$prov[rubric_img]','$prov[rubric_type]','$prov[rubric_pos]','$prov[rubric_close]','$prov[rubric_visible]','$prov[rubric_deleted]')";

		$database->query($query_add_rubric);

		//Копирование свойств
        $res=$database->query("SELECT * FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=$rubric_id and rubric_type='$type'",MYSQL_ASSOC);
        while($row=mysql_fetch_array($res))
		{
			if(!in_array($row['ID_FEATURE'],$idf))
     	  	{
				$query_add_feature="INSERT INTO ".DB_PREFIX."rubric_features VALUES('$curgood','$row[ID_FEATURE]',
				'$row[rubric_type]','$row[rubricfeature_graduation]','$row[rubricfeature_pos]','$row[rubricfeature_ls_man]',
				'$row[rubricfeature_ls_pub]')";

				$database->query($query_add_feature);
     	  	}
		}
		$curgood++;
	}
    return $curgood;
}

//Функция просмотра подрубрик
function N_podrub($rubric,$type,$cnt=false)
{
	global $database;
    // если $cnt не указан (ф-я вызвана 1-й раз), $kol = 0, иначе $cnt + 1;
 	$kol = (!$cnt)?1:$cnt+1;
 	//print "вызвана $kol раз<br>";

	$i=1;
	//список рубрик для текущей закладки
	$pres=$database->query("SELECT ID_RUBRIC,rubric_name FROM ".DB_PREFIX."rubric WHERE rubric_parent='$rubric' and rubric_deleted=0 and rubric_close=0 and rubric_type='$type'");
	if(mysql_num_rows($pres)!=0)
	{
		//Заполняем массив рубрик
		while($prow=mysql_fetch_array($pres))
		{
	       	$arr[$i][$prow['ID_RUBRIC']]=$prow['rubric_name'];
	       	$i++;
		}
		//return $arr;
		//Выводим массив рубрик
		foreach($arr as $key=>$val)
		{
			foreach($val as $idr=>$rn)
			{
				$pad=$kol*10;
				print "<tr><td><div style='padding-left: $pad%'><a href='".teGetUrlQuery("=rubric-multiple","rubric_id=$idr")."'>(ID.$idr) - $rn.(".getCountGoods($idr).")</a></div></td></tr>";
			}
			N_podrub($idr,$type,$kol);
		}

	}
}
//Функция вывода всех рубрик для текущей закладки
function N_rubric($type)
{
	global $database;
	$arr=array();
	//Цикл заполнения элементов рубрик в массив
	$i=1;
	$res=$database->query("SELECT ID_RUBRIC,rubric_name FROM ".DB_PREFIX."rubric WHERE rubric_parent='0' and rubric_close=0 and rubric_deleted=0 and rubric_type='$type'");
	while($row=mysql_fetch_array($res))
	{
       $arr[$i][$row['ID_RUBRIC']]=$row['rubric_name'];
       $i++;
	}
	return $arr;
}

//Функция вывода характеристик для данной подрубрики.
function N_rubric_features($rubric,$max_good=false,$type)
{
	global $database;
	$arr=array();
			//Цикл характеристик*/
			$i=1;
			$res1=$database->query("SELECT ID_FEATURE,rubricfeature_pos FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC='$rubric' and rubric_type='$type'");
		    while($row1=mysql_fetch_array($res1))
		    {
		    	$arr[$row1['rubricfeature_pos']]=$row1['ID_FEATURE'];
	  		    $i++;
		    }
	return $arr;
}

print_link_up();
print "<a href='javascript:history.back()'>назад</a>";
print "<div align='center'><h1>Размножение рубрик</h1></div>";
$bookmark=$database->getArrayOfquery("SELECT rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE ID_RUBRIC_TYPE=$type");
print "<div align='center'><font size=4>Закладка-". $bookmark[0]."</font><br>";

//Первый шаг Выбор Рубрики
if(!isset($_GET['rubric_id']) && !isset($_GET['save']))
{
print "«Выберите рубрику, которую нужно размножить»<br/></div>";
print "<table>";
	$rubrics=N_rubric($type);
	foreach($rubrics as $kl=>$value)
	{
	   foreach($value as $idr=>$rn)
	   {
		   	print "<tr><td><a href='".teGetUrlQuery("=rubric-multiple","rubric_id=$idr")."'>(ID.$idr) - $rn.(".getCountGoods($idr).")</a></td></tr>\r\n";
		    N_podrub($idr,$type);
	   }
	}
print "</table>";
}

//Второй шаг - выбор характеристик по которым создается шаблон для копирования.
if(isset($_GET['rubric_id']) && !isset($_GET['selectfutur']) && !isset($_GET['viewnewgoods']))
{
 	@$err=$_GET['err'];
 	$rubric_id=$_GET['rubric_id'];
	$features=N_rubric_features($rubric_id,false,$type);

     print "<div align='center'>";
     print "<form method='GET'><table border='1'>";
     print "<font size='4'>Рубрика - №$rubric_id</font><br/>";
 	 if(@$err==1)
 	 {
 	 	print "<font color='red'>Правильно введите количество рубрик</font><br/>";
 	 }
	if(@$err==2)
	{
 	 	print "<font color='red'>Выберите хотя бы одну характеристику для изменения</font><br/>";
 	}
     print "Количество размножаемых рубрик(только число)не больше 50 &nbsp";
     print "<input type='text' name='rubriccount' size='6' value=0><br/>";
     print "Выберите характеристики которые <font style='font-weight:bold'>не</font> нужно будет копировать<br/>";
	 print "<table border='1' width=80%>";
	//Цикл вывода характеристик для выбранной записи
			   foreach($features as $rubricfeature_pos=>$fut)
               {
               	print "<tr><td width=5%><input name='fut$fut' value=1 type='checkbox'></td>";
               	print "<td width=6%>$fut-".getFeatureName($fut). "</td>";
               }

     print "</table>";
     //Уже выбранные параметры сессии размножения передаются через элементы hidden
     print "<input type='hidden' name='pg' value='rubric-multiple'>";
     print "<input type='hidden' name='type' value='$type'>";
     print "<input type='hidden' name='action' value='multiple_rubric'>";
     print "<input type='hidden' name='rubric_id' value='$rubric_id'>";
     //print "<input type='hidden' name='good' value='$good'>";
     print "<input type='hidden' name='selectfutur' value='1'>";
     print "<input type='submit' value='Размножить рубрику'>";
     print "</form></div>";
}

//Когда выбраны все параметры начинаем процедуру размножения записей
if(isset($_GET['selectfutur']) && isset($_GET['rubriccount']))
{
	$rubriccount=$_GET['rubriccount'];
	$rubric_id=$_GET['rubric_id'];
	$type=$_GET['type'];
    $int_rubriccount=intval($rubriccount);
	$idf= array();

    if(empty($int_rubriccount) || $int_rubriccount<0 || $int_rubriccount>50)
    {
    	teRedirect("?pg=rubric-multiple&type=$type&action=multiple_rubric&rubric_id=$rubric_id&err=1");
    }
    $i=1;
    $stroka='';
    foreach($_GET as $key=>$val)
    {
	print "$key=>$val".substr($key,0,3);
    	if(substr($key,0,3)=="fut")
        {
			$idf[$i]=substr($key,3);

            $stroka.="&";
   			$stroka.="$key=$val";
            $i++;
        }
    }

    $result=rubricmultiplicate($idf,$rubriccount,$rubric_id,$type);

	teRedirect("?pg=rubric&type=$type&idshow=id");
	}

?>