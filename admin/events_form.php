<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}

$rtype='';
$rubric='';
$good='';

if(isset($_GET['rtype']))$rtype = $_GET['rtype'];
if(isset($_GET['rubric']))$rubric = $_GET['rubric'];
if(isset($_GET['good']))$good = $_GET['good'];

	function excel($workbook,$rtype,$title,$rubric,$good){
		global $database;

		$worksheet1 =& $workbook->addWorksheet($title);

		$worksheet1->setColumn(0,0,1);
		$worksheet1->setColumn(0,1,25);
		$worksheet1->setColumn(0,2,35);

		/// The actual data

		// номер строки в екселе
		$num = 0;
		// функция вывода (тип (товар или усл), родительская запись, скока отступ слева)

		//// вывод
		/// заголовок
		// формат
		if(DB_ID==19)
		{			$frmt =& $workbook->addFormat();
			$frmt->setSize(10);
			$worksheet1->write($num, 1, "ООО «УНИВЕРСАЛ-СЕРВИС» ИНН/ КПП 0278138763 /027801001",$frmt);
			$worksheet1->mergeCells($num,1,$num,3);
			$num++;
			$worksheet1->write($num, 1, "ОГРН 1070278008492",$frmt);
			$worksheet1->mergeCells($num,1,$num,3);
			$num++;
			$worksheet1->write($num, 1, "Юридический адрес 450001 г. Уфа  Комсомольская д.2",$frmt);
			$worksheet1->mergeCells($num,1,$num,3);
			$num++;
			$worksheet1->write($num, 1, "Фактический адрес 450000 г. Уфа  Ленина д.2, оф. 89",$frmt);
			$worksheet1->mergeCells($num,1,$num,3);
			$num++;
			$worksheet1->write($num, 1, "Телефоны:  2573537, 2508612",$frmt);
			$worksheet1->mergeCells($num,1,$num,3);
			$num++;
			$worksheet1->write($num, 1, "e-mail: unis777@yandex.ru , http://www.UfaBanket.ru",$frmt);
			$worksheet1->mergeCells($num,1,$num,3);
			unset($frmt);
			$num+=2;		}

		$frmt =& $workbook->addFormat();
		$frmt->setBold();
		$frmt->setSize(18);
		$frmt->setAlign('center');
		$worksheet1->setRow($num,30);
		$frmt->setValign('vcenter');
		// вывод
		$res1 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric
				where ID_RUBRIC='$rubric'"
		);
        $row1 = mysql_fetch_array($res1);

		$worksheet1->write($num, 1, $row1['rubric_name'],$frmt);
		$worksheet1->mergeCells($num,1,$num,3);
		unset($frmt);

		$num++;

		$frmt =& $workbook->addFormat();
		$frmt->setAlign('left');
		$frmt->setVAlign('top');
		$frmt->setTextWrap();
		$frmt->setTop(2);
		$frmt->setLeft(2);
		$frmt->setRight(2);
		$frmt->setBottom(2);
		$frmt->setSize(12);

		$num++;$num++;
			$res = $database -> query("SELECT * FROM ".DB_PREFIX."features t1
				inner join ".DB_PREFIX."goods_features t2 on t1.ID_FEATURE=t2.ID_FEATURE
				inner join ".DB_PREFIX."rubric_features  t3 on t1.ID_FEATURE=t3.ID_FEATURE
				where t2.ID_GOOD='$good' && t3.ID_RUBRIC='$rubric' && t1.feature_enable=1 && t1.feature_deleted=0 && t3.rubric_type='$rtype'
				order by t3.rubricfeature_pos"
			);
			$uslugi=array();
			while($row = mysql_fetch_array($res))
			{
				$value ='';
				$worksheet1->setRow($num,20);
				switch($row['feature_type']){
		            case 5:
		            	if($row['goodfeature_value']>0)
		            	{
			            	$line = $database->getArrayOfQuery("SELECT t2.goodfeature_value FROM ".DB_PREFIX."features t1
								inner join ".DB_PREFIX."goods_features t2 on t1.ID_FEATURE=t2.ID_FEATURE
								inner join ".DB_PREFIX."rubric_features  t3 on t1.ID_FEATURE=t3.ID_FEATURE
								where t2.ID_GOOD='".$row['goodfeature_value']."' && t1.feature_enable=1 && t1.feature_deleted=0 && t1.feature_type=2
								order by t3.rubricfeature_pos limit 1");
			            	$value = $line[0];
		            	}else $value = $row['goodfeature_value'];
		            break;
		            case 9:
		            	if($row['goodfeature_value']>0)
		            	{
			            	$line = $database->getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$row['goodfeature_value']);
			            	$uslugi[] = $line[0];
		            	}else $value = $row['goodfeature_value'];
		            break;
		            case 7:
		            	if($row['goodfeature_value']>0)
		            	{
			            	$line = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT=".$row['goodfeature_value']);
			            	$value = $line[0];
							$worksheet1->setRow($num,80);
		            	}else $value = $row['goodfeature_value'];
		            break;
		            case 4:
		            	if($row['goodfeature_value']>0)
		            	{
			            	$line = $database->getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$row['ID_FEATURE']." && ID_FEATURE_DIRECTORY='".$row['goodfeature_value']."'");
			            	$value = $line[0];
			       		}
		            break;
		            default:
		            	$value = $row['goodfeature_value'];
		            break;

			    }
			    if($row['feature_type']!=9)
			    {
					$worksheet1->write($num, 1, $row['feature_text'],$frmt);
					$worksheet1->write($num++, 2, $value,$frmt);
				}
			}

   		if(count($uslugi)>0)
   		{
			$worksheet1->setColumn(0,3,10);
			$worksheet1->setColumn(0,4,10);
	   		$num++;
			unset($frmt);
			$frmt =& $workbook->addFormat();
			$frmt->setBold();
			$frmt->setSize(18);
			$frmt->setAlign('center');
			$frmt->setValign('vcenter');
			$worksheet1->write($num, 1, "Смета услуг",$frmt);
			$worksheet1->mergeCells($num,1,$num,3);
			$worksheet1->setRow($num,30);
			unset($frmt);

	   		$num++;

			$frmt =& $workbook->addFormat();
			$frmt->setAlign('center');
			$frmt->setVAlign('vcenter');
			$frmt->setTop(2);
			$frmt->setLeft(2);
			$frmt->setRight(2);
			$frmt->setBottom(2);
			$frmt->setSize(14);

	   		$num++;
			$worksheet1->setRow($num,20);
			$worksheet1->write($num, 1, "Наименование", $frmt);
			$worksheet1->write($num, 2, "Описание", $frmt);
			$worksheet1->write($num, 3, "Ед.изм", $frmt);
			$worksheet1->write($num++, 4, "Цена", $frmt);
			unset($frmt);
			$frmt =& $workbook->addFormat();
			$frmt->setAlign('left');
			$frmt->setVAlign('bottom');
			$frmt->setTextWrap();
			$frmt->setTop(2);
			$frmt->setLeft(2);
			$frmt->setRight(2);
			$frmt->setBottom(2);
			$frmt->setSize(12);
			foreach($uslugi as $item){
				$worksheet1->write($num, 1, $item, $frmt);
				$worksheet1->write($num, 2, "", $frmt);
				$worksheet1->write($num, 3, "", $frmt);
				$worksheet1->write($num++, 4, "", $frmt);
			}
			$worksheet1->write($num, 1, "Общая цена", $frmt);
			$worksheet1->write($num, 2, "", $frmt);
			$worksheet1->write($num, 3, "", $frmt);
			$worksheet1->write($num, 4, "", $frmt);
			$worksheet1->mergeCells($num,1,$num,3);

        }
		unset($frmt);
		return $worksheet1;
	}



if(empty($good) || empty($rtype) || empty($rubric))
{	print 'Пустые входные данные';
	exit();}
	/// без вопросов, просто надо :-D
	$s1 = ob_get_contents();
	ob_end_clean();
	unset($s1);

	/// библиотека ексель
	teInclude("excel");

	/// Creating a workbook
	$workbook = new Spreadsheet_Excel_Writer();

	/// sending HTTP headers
	$workbook->send('events.xls');

	excel($workbook, $rtype,'Важное событие или заказ',$rubric,$good);

//	excel_print(excel_newlist($rtype,'Важное событие или заказ',$rubric,$good),$rtype,0);	/// send file
	$workbook->close();

	/// дальше не работать
	die;
?>