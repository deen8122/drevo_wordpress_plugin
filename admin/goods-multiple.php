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
//Сохранение изменений в интерфейсе уже скопированных записей
function saveresult($good,$feature,$featvalue)
{
 	global $database;
	//Отдельно для большого текста
	if(getTypeFeature($feature)==7)
	{

		//$database->query="UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='$featvalue' WHERE ID_GOOD='$good' AND ID_FEATURE='$feature'";
		list($goodfetvalue)=$database->getArrayOfquery("SELECT goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=$good AND ID_FEATURE=$feature");
		//print $qwe."<br>";
		$database->query("UPDATE ".DB_PREFIX."texts SET text_text='".$featvalue."' WHERE ID_TEXT='".$goodfetvalue."'");
	}
	else
	{
	$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='$featvalue' WHERE ID_GOOD='$good' AND ID_FEATURE='$feature'");
	}
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
	/*if (!$handle = fopen("c:\debug1.txt", 'a')) {}
				$somecontent=$feattype."\r\n";
				if(fwrite($handle, $somecontent) === FALSE) {exit;}
	fclose($handle);*/

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

				/*if (!$handle = fopen("c:\debug.txt", 'a')) {}
				$somecontent=$quer."\r\n";
				if (fwrite($handle, $somecontent) === FALSE) {exit;}
				fclose($handle);*/

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
// $shabgood- шаблон записи по которой будут создаваться новые записи
// $idf - массив свойств которые не будут копироваться с шаблона
// $goodcount - количество новых записей

function goodmultiplicate($shabgood,$idf=array(),$goodcount,$rubric_id)
{
	global $database;
	$visible=$database->getArrayOfquery("SELECT good_visible FROM ".DB_PREFIX."goods WHERE ID_GOOD=$shabgood");
	$max_good=$database->getArrayOfquery("SELECT MAX(ID_GOOD) FROM ".DB_PREFIX."goods");
	$curgood=$max_good[0]+1;;

    for($i=1;$i<=$goodcount;$i++)
	{
		$prov=array();
		$prov2=array();
		//Проверка нет ли уже такой записи
		$prov=$database->getArrayOfquery("SELECT * FROM ".DB_PREFIX."goods WHERE ID_GOOD=$curgood");
		if(empty($prov[0]))
		{
		  $query="INSERT INTO ".DB_PREFIX."goods(ID_GOOD,good_visible) VALUES($curgood,$visible[0])";
		  $database->query($query);
		}
		$prov2=$database->getArrayOfquery("SELECT * FROM ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=$rubric_id and ID_GOOD=$curgood");
		if(empty($prov2[0]))
		{
			$query2="INSERT INTO ".DB_PREFIX."rubric_goods(ID_RUBRIC,ID_GOOD) VALUES($rubric_id,$curgood)";
			del_cache($rubric_id);//удаление кэша

			$database->query($query2);
		}

        //Копирование свойств
        $res=$database->query("SELECT * FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=$shabgood");
        while($row=mysql_fetch_array($res))
		{
          $prov3=array();
          //Проверяем нет ли у записи уже такого свойства
          $prov3=$database->getArrayOfquery("SELECT * FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=$curgood and ID_FEATURE='".$row['ID_FEATURE']."'");
          if(empty($prov3[0]))
          {

			$query3="INSERT INTO ".DB_PREFIX."goods_features (ID_GOOD,ID_FEATURE) VALUES('$curgood','".$row['ID_FEATURE']."')";
     	  	$database->query($query3);
			//для большого поля отдельно
			/*if (!$handle = fopen("c:\debug.txt", 'a')) {}
				$somecontent="curgood-".$curgood."-featur-".$row['ID_FEATURE']." -featval-".$featval."-feattype".$feattype."\r\n";
				if (fwrite($handle, $somecontent) === FALSE) {exit;}
				fclose($handle);*/
			if(!in_array($row['ID_FEATURE'],$idf))
     	  	{
     	  	 list($featval)=$database->getArrayOfquery("SELECT goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_GOOD='$shabgood' and ID_FEATURE='".$row['ID_FEATURE']."'");
             $feattype=getTypeFeature($row['ID_FEATURE']);

			 setFeatureText($curgood,$row['ID_FEATURE'],$featval,$feattype);
     	  	}
			else
			{
				if(getTypeFeature($row['ID_FEATURE'])==7)
				{
					setFeatureText($curgood,$row['ID_FEATURE'],"",7);
				}
			}

     	  }

		}
		$curgood++;
	}
    return $max_good[0];
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
				print "<tr><td><div style='padding-left: $pad%'><a href='".teGetUrlQuery("action=multiple_goods","rubric_id=$idr")."'>(ID.$idr) - $rn.(".getCountGoods($idr).")</a></div></td></tr>";
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
//Функция вывода записей и их характеристик для данной подрубрики.
function N_rubric_goods_features($rubric,$max_good=false)
{
	global $database;
	$arr=array();
	//Цикл записей
	if(!empty($max_good))
	{
	$res=$database->query("SELECT ID_GOOD FROM ".DB_PREFIX."rubric_goods WHERE rubricgood_deleted=0 and ID_RUBRIC='$rubric' and ID_GOOD>$max_good");
	}
	else
	{
	$res=$database->query("SELECT ID_GOOD FROM ".DB_PREFIX."rubric_goods WHERE rubricgood_deleted=0 and ID_RUBRIC='$rubric'");
    }
	while($row=mysql_fetch_array($res))
	{
		//$arr[$i]=$row['ID_GOOD'];

		//Проверяем не удалена ли запись
		$notdelgood=$database->getArrayOfquery("SELECT good_deleted FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$row['ID_GOOD']);
        if($notdelgood[0]==0)
	    {
	    //print "Не удаленная".$row['ID_GOOD']."<br>";
	    //Цикл характеристик
			$i=1;
			$res1=$database->query("SELECT ID_FEATURE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD='".$row['ID_GOOD']."'");
		    while($row1=mysql_fetch_array($res1))
		    {
		    	//$arr[$row['ID_GOOD']]=array('k'=>$i,'feat'=>$row1['ID_FEATURE']);
		    	$arr[$row['ID_GOOD']][$i]=$row1['ID_FEATURE'];
	  		    $i++;
		    }
		}
	}
	return $arr;
}

print_link_up();
print "<a href='javascript:history.back()'>назад</a>";
print "<div align='center'><h1>Размножение записи</h1></div>";
$bookmark=$database->getArrayOfquery("SELECT rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE ID_RUBRIC_TYPE=$type");
print "<div align='center'><font size=4>Закладка-". $bookmark[0]."</font><br>";

//Первый шаг Выбор Рубрики
if(!isset($_GET['rubric_id']) && !isset($_GET['save']))
{
print "«Выберите рубрику в которой нужно размножить запись»<br/></div>";
print "<table>";
	$rubrics=N_rubric($type);
	foreach($rubrics as $kl=>$value)
	{
	   foreach($value as $idr=>$rn)
	   {
		   	print "<tr><td><a href='".teGetUrlQuery("action=multiple_goods","rubric_id=$idr")."'>(ID.$idr) - $rn.(".getCountGoods($idr).")</a></td></tr>\r\n";
		    N_podrub($idr,$type);
	   }
	}
print "</table>";
}
//Второй шаг Выбор записи
if(!isset($_GET['good']) && isset($_GET['rubric_id']))
{
	$rubric_id=$_GET['rubric_id'];

 	//print $rubric_id;

	$goods=N_rubric_goods_features($rubric_id);
	if(empty($goods))
    {
     	if(getCountRubricChild($rubric_id)>0)
		{
			print "<table>";
			N_podrub($rubric_id,$type);
			print "</table>";
		}
		else
		{
			print "<div align='center'>в данной рубрике нет записей</div><br/>";
		}
    }
    else
    {
	print "<div align='center'>";
 	print "<h2>«Выберите запись, характеристики которой нужно размножить»</h2>";
	print "<table border='1'>";
		//Цикл вывода записей для выбранной рубрики.
		foreach($goods as $good=>$val)
		{
		    print "<tr>";
         	print "<td rowspan='2'><a href='".teGetUrlQuery("action=multiple_goods","rubric_id=$rubric_id","good=$good")."'>Запись $good </a></td";

		  sort($val);
		  //Характеристики отображаются для идентификации записи пользователем
		  foreach($val as $idg=>$fut)
          {
          	print "<td style='font: bold italic 110% serif;'>".getFeatureName($fut)."</td>";
          }
	     print "</tr>";
		    print "<tr>";
		    foreach($val as $idg=>$fut)
	          {
                $f_text=getFeatureText($good, $fut);
                if(empty($f_text)){$f_text='&nbsp';}
	          	print "<td>$f_text</td>";
	          }
	        print "</tr>";
    	}

	print "</table></div>";
    }
}
//Третий шаг - выбор характеристик по которым создается шаблон для копирования.
if(isset($_GET['good']) && !isset($_GET['selectfutur']) && !isset($_GET['viewnewgoods']))
{
 	@$err=$_GET['err'];
 	$good=$_GET['good'];
 	$rubric_id=$_GET['rubric_id'];
 	$goods=N_rubric_goods_features($rubric_id);
 	if(empty($goods))
    {
     	print "<div align='center'>в данной рубрике нет записей</div><br>";
    }
	else
	{
     print "<div align='center'>";
     print "<form method='GET'><table border='1'>";
     print "<font size='4'>Запись - №$good</font><br/>";
 	 if(@$err==1)
 	 {
 	 	print "<font color='red'>Правильно введите количество записей</font><br/>";
 	 }
	if(@$err==2)
	{
 	 	print "<font color='red'>Выберите хотя бы одну характеристику для изменения</font><br/>";
 	}
     print "Количество размножаемых записей(только число)не больше 50 &nbsp";
     print "<input type='text' name='goodcount' size='6' value=0><br/>";
     print "Выберите характеристики которые <font style='font-weight:bold'>не</font> нужно будет копировать<br/>";
     print "эти характеристики можно будет далее установить для каждой скопированной записи<br>";
	 print "<table border='1' width=80%>";
	//Цикл выыода характеристик для выбранной записи
			   foreach($goods[$good] as $num=>$fut)
               {

               	print "<tr><td width=5%><input name='fut$fut' value=1 type='checkbox'></td>";
               	print "<td width=6%>$fut-".getFeatureName($fut)."</td>";
               	$f_text=getFeatureText($good, $fut);
               	print "<td>$f_text</td></tr>";
               }

     print "</table>";
     //Уже выбранные параметры сессии размножения передаются через элементы hidden
     print "<input type='hidden' name='pg' value='goods-multiple'>";
     print "<input type='hidden' name='type' value='$type'>";
     print "<input type='hidden' name='action' value='multiple_goods'>";
     print "<input type='hidden' name='rubric_id' value='$rubric_id'>";
     print "<input type='hidden' name='good' value='$good'>";
     print "<input type='hidden' name='selectfutur' value='1'>";
     print "<input type='submit' value='Размножить запись'>";
     print "</form></div>";
	}
}
//Когда выбраны все параметры начинаем процедуру размножения записей
if(isset($_GET['selectfutur']) && isset($_GET['goodcount']))
{
	$goodcount=$_GET['goodcount'];
	$rubric_id=$_GET['rubric_id'];
	$good=$_GET['good'];
	$type=$_GET['type'];
    $int_goodcount=intval($goodcount);
   // print $int_goodcount."<br>";
    if(empty($int_goodcount) || $int_goodcount<0 || $int_goodcount>50)
    {
	   //print "goodcount=".$int_goodcount."<br>";
    	teRedirect("?pg=goods-multiple&type=$type&action=multiple_goods&rubric_id=$rubric_id&good=$good&err=1");
    }
    $i=1;
    $stroka='';
    foreach($_GET as $key=>$val)
    {
    	if(substr($key,0,3)=="fut")
        {
			$idf[$i]=substr($key,3);
            $stroka.="&";
   			$stroka.="$key=$val";
            $i++;
        }
    }
	if(!empty($idf)){
    	$result=goodmultiplicate($good,$idf,$goodcount,$rubric_id);
        //print $stroka;
		teRedirect("?pg=goods-multiple&type=$type&action=multiple_goods&rubric_id=$rubric_id&good=$good&viewnewgoods=1&maxgood=$result".$stroka);
	}
	else
	{
		teRedirect("?pg=goods-multiple&type=$type&action=multiple_goods&rubric_id=$rubric_id&good=$good&err=2");
	}
}
if(isset($_POST['save']))
{
    print "<div align='center'><font color='green'>Изменения сохранены</font></div><br>";
	foreach($_POST as $key=>$val)
    {
    	if(substr($key,0,4)=="good")
        {

			$arr=split('\.|fut',$key);
			while(list($kk,$vv)=each($arr))
			{
			//print $vv."<br>";
				if(substr($vv,0,4)=="good")
			    {
			    	$good_id=substr($vv,4);
			    	//print $good_id."<br>";
			    }
				$featur_arr[$good_id][$arr[1]]=$val;
            }
        }
    }
    if(!empty($featur_arr))
	{
		foreach($featur_arr as $nkey=>$nvalue)
		{
			foreach($nvalue as $kn=>$vn)
			{
				//print "$nkey -  $kn - $vn <br>";
				saveresult($nkey,$kn,$vn);
			}
		}
	}
}
if(isset($_GET['viewnewgoods']) && isset($_GET['maxgood']))
{
	$maxgood=$_GET['maxgood'];
	$rubric_id=$_GET['rubric_id'];
	$newgoods=N_rubric_goods_features($rubric_id,$maxgood);
    print "Заполните нужные характеристики<br><br>";
	foreach($_GET as $key=>$val)
    {
    	if(substr($key,0,3)=="fut")
        {
			$idf[$i]=substr($key,3);
            $i++;
        }
    }
	print "<div align='center'>";
	print "<form method='POST'>";
		print "<table border='1'>";
		//Цикл вывода записей
		foreach($newgoods as $good=>$val)
		{
		    print "<tr>";
         	print "<td rowspan='2'><a href='".teGetUrlQuery("action=multiple_goods","rubric_id=$rubric_id","good=$good")."'>Запись $good </a></td";
		  	sort($val);
		 //Характеристики
			 foreach($val as $idg=>$fut)
			 {
				print "<td style='font: bold italic 110% serif;'>".getFeatureName($fut)."</td>";
			 }
	     	print "</tr>";
		    print "<tr>";
		      foreach($val as $idg=>$fut)
	          {
	               $f_text=getFeatureText($good, $fut);
	               if(empty($f_text)){$f_text='&nbsp';}
				   if(in_array($fut,$idf))
	          	   {
						//Если свойства уже заполнены
						if(!empty($featur_arr))
	          	   		{
	          	   			$fut_type=getTypeFeature($fut);
						  //print "<td><input type=text name=good$good"."fut$fut value='".$featur_arr[$good][$fut]."'></td>";
						  print "<td>";
						  switch($fut_type)
						    {
								case 1:
								case 2:
								case 8:
										print "<input name='good$good"."fut$fut' type='text' value='".@$featur_arr[$good][$fut]."' >";
								break;
								case 7:
										list($id_text)=$database->getArrayOfquery("SELECT goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_GOOD='$good' AND ID_FEATURE='$fut'");
										list($featur_arr[$good][$fut]) = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT='".$id_text."'");

										print "<textarea name='good$good"."fut$fut'>".@$featur_arr[$good][$fut]."</textarea>";

								break;
								/*case 3:
										print "<input name='$elemname' type='checkbox' ".(($answertext==0)?"checked":"").">";
								break;*/
								case 4:
										print "<select name='good$good"."fut$fut'><option>";
												$res1 = $database -> query("SELECT ID_FEATURE_DIRECTORY,featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$fut);
												while($line1 = mysql_fetch_array($res1)){
														print "<option value='$line1[0]' ".(($featur_arr[$good][$fut]==$line1[0])?"selected":"").">".$line1[1]."</option>";
												}
										print "</select>";
								break;
								/*case 5:                                                  //die("SELECT ".DB_PREFIX."goods.ID_GOOD,".DB_PREFIX."goods.good_name FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=".$feature_rubric);
										print "<select name='$elemname'><option>";
												$res1 = $database -> query("SELECT ".DB_PREFIX."goods.ID_GOOD,".DB_PREFIX."goods.good_name FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=".$feature_rubric);
												while($line1 = mysql_fetch_array($res1)){
														print "<option value='$line1[0]' ".(($answertext==$line1[0])?"selected":"").">".$line1[1]."</option>";
												}
										print "</select>";
								break;*/
								/*
								case 6:
										if($answertext!="-") print "<tr><td class=name>".$line['feature_text'].":</td><td class=value><a href='".URLDATA_FLD."features/".$answertext."'>".$answertext."</a></td></tr>";
								break;
								*/
                            }
							print "</td>";
	          	   		}
					//Если значения свойств не заполнены
	          	   	   else
	          	   	   	{
		          	   		//print "<td><input type=text name=good$good"."fut$fut></td>";
							$fut_type=getTypeFeature($fut);
						  //print "<td><input type=text name=good$good"."fut$fut value='".$featur_arr[$good][$fut]."'></td>";
						  print "<td>";
						  switch($fut_type)
						    {
								case 1:
								case 2:
								case 8:
										print "<input name='good$good"."fut$fut' type='text' value='".@$featur_arr[$good][$fut]."' >";
								break;
								case 7:
										/*
										if($featur_arr[$good][$fut]!="" && is_numeric($featur_arr[$good][$fut])){
												list($featur_arr[$good][$fut]) = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT=".$featur_arr[$good][$fut]);
												//$answertext = $answertext[0];
										}*/

										list($id_text)=$database->getArrayOfquery("SELECT goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_GOOD='$good' AND ID_FEATURE='$fut'");
										list($featur_arr[$good][$fut]) = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT='".$id_text."'");
										//@$featur_arr[$good][$fut]

										print "<textarea name='good$good"."fut$fut'>".@$featur_arr[$good][$fut]."</textarea>";

								break;
								/*case 3:
								print "<input name='$elemname' type='checkbox' ".(($answertext==0)?"checked":"").">";
								break;*/
								case 4:
										print "<select name='good$good"."fut$fut'><option>";
												$res1 = $database -> query("SELECT ID_FEATURE_DIRECTORY,featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$fut);
												while($line1 = mysql_fetch_array($res1)){
														print "<option value='$line1[0]' ".((@$featur_arr[$good][$fut]==$line1[0])?"selected":"").">".$line1[1]."</option>";
												}
										print "</select>";
								break;
								/*case 5:                                                  //die("SELECT ".DB_PREFIX."goods.ID_GOOD,".DB_PREFIX."goods.good_name FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=".$feature_rubric);
										print "<select name='$elemname'><option>";
												$res1 = $database -> query("SELECT ".DB_PREFIX."goods.ID_GOOD,".DB_PREFIX."goods.good_name FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=".$feature_rubric);
												while($line1 = mysql_fetch_array($res1)){
														print "<option value='$line1[0]' ".(($answertext==$line1[0])?"selected":"").">".$line1[1]."</option>";
												}
										print "</select>";
								break;*/
								/*
								case 6:
										if($answertext!="-") print "<tr><td class=name>".$line['feature_text'].":</td><td class=value><a href='".URLDATA_FLD."features/".$answertext."'>".$answertext."</a></td></tr>";
								break;
								*/
                            }
		          	   	}
	          	   }
	          	   else
	          	   {
  	          	   		print "<td>$f_text</td>";
	          	   }
	          }
	        print "</tr>";
    	}
	print "</table>";
    print "<input type='hidden' name='pg' value='goods-multiple'>";
    print "<input type='hidden' name='type' value='$type'>";
    print "<input type='hidden' name='action' value='multiple_goods'>";
    print "<input type='hidden' name='save' value='1'>";
    print "<input type='submit' value='Сохранить'>";
 	print "</form></div>";
}
?>