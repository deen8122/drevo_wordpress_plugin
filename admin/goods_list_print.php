<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  Распечатка записей рубрики
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/
error_reporting(E_ERROR | E_WARNING | E_PARSE);
/// без вопросов, просто надо
$s1 = ob_get_contents();
ob_end_clean();
unset($s1);

/// библиотека ексель
teInclude("excel");

/// Creating a workbook
$workbook = new Spreadsheet_Excel_Writer();

/// sending HTTP headers
$workbook->send('test.xls');

$worksheet =& $workbook->addWorksheet(getRubricName($rubric_id,0,0,0));

if (PEAR::isError($worksheet)) {
    die($worksheet->getMessage());
}

//$worksheet->setInputEncoding('ISO-8859-7');
$worksheet->setInputEncoding('windows-1251');



$hfrmt =& $workbook->addFormat();
$hfrmt->setLeft(1);
$hfrmt->setRight(1);
$hfrmt->setBottom(1);
$hfrmt->setTop(1);
$hfrmt->setAlign('center');
$hfrmt->setBold();
$hfrmt->setVAlign('top');
$frmt =& $workbook->addFormat();
$frmt->setAlign('left');
$frmt->setTextWrap();
$frmt->setVAlign('top');
$frmt->setBottom(1);
$tfrmt =& $workbook->addFormat();
$tfrmt->setAlign('right');
$tfrmt->setItalic();


$x = 0;
$y = 0;

	$worksheet->setColumn($y,$x,5.0);
	$worksheet->write($y, $x++, "ID", $hfrmt);

	$firstchangeshow = teGetConf("u".$uid."r".$rubric_id."fCRTshow");
	if($firstchangeshow) {		$worksheet->setColumn($y,$x,15.0);		$worksheet->write($y, $x++, "Дата создания", $hfrmt);
	}

	$lastchangeshow = teGetConf("u".$uid."r".$rubric_id."fLSTshow");
	if($lastchangeshow) {
		$worksheet->setColumn($y,$x,15.0);
		$worksheet->write($y, $x++, "Дата последнего изменения", $hfrmt);
	}

	$res1 = $database->query("SELECT ".DB_PREFIX."features.ID_FEATURE AS ID_FEATURE, ".DB_PREFIX."features.feature_text, ".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE $private_visible_features false GROUP BY ID_FEATURE ORDER BY rubricfeature_pos");
	if(mysql_num_rows($res1)==0){
		$res1 = $database->query("SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text, ".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$rubric_id." and rubric_type=".$type." and rubricfeature_ls_man=1 ORDER BY rubricfeature_pos");
		if(mysql_num_rows($res1)==0){
			$res1 = $database->query("SELECT ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_text, ".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$rubric_id." and rubric_type=".$type." ORDER BY rubricfeature_pos LIMIT 3");
		}
	}

	while($line=mysql_fetch_array($res1)){		if($line['feature_type']==7)$worksheet->setColumn($y,$x,30.0);
		else $worksheet->setColumn($y,$x,20.0);
		$worksheet->write($y, $x++, $line[1], $hfrmt);
		$num_feats++;
	}

	///$worksheet->write($y, $x++, "#", $hfrmt);

/*
	//// поля для данных листинга
	$qwer = false;

	// локальные поля листинга
	$res1 = $database->query("SELECT ID_FEATURE FROM ".DB_PREFIX."rubric_features WHERE $private_visible_features false ORDER BY rubricfeature_pos");
	if(mysql_num_rows($res1)>0){
		$qwer = true;
	}

	// глобальные поля листинга
	if(!$qwer){
		$res1 = $database->query("SELECT ID_FEATURE FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=$rubric_id and rubric_type=$type and rubricfeature_ls_man=1 ORDER BY rubricfeature_pos");
		if(mysql_num_rows($res1)>0){
			$qwer = true;
		}
	}
	// поля листинга (если нет)
	if(!$qwer){
		$res1 = $database->query("SELECT ID_FEATURE FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=$rubric_id and rubric_type=$type ORDER BY rubricfeature_pos LIMIT 3");
		if(mysql_num_rows($res1)>0){
			$qwer = true;
		}
	}

*/

	$i=0;
	$res = $database->query($goodssql);
	while($lineg = mysql_fetch_array($res,MYSQL_ASSOC)){
		$x=0;
		$y++;

		$id = $lineg['ID_GOOD'];
		$vis = $lineg['good_visible'];


		if( $vis==0 ){
			$s = "disabled";
		} else {
			$s = "";
		}


		$worksheet->write($y, $x++, $id, $frmt);

		if($firstchangeshow) $worksheet->write($y, $x++, dateOfChange("goods",$id,"ASC"), $frmt);
		if($lastchangeshow) $worksheet->write($y, $x++, dateOfChange("goods",$id), $frmt);

		$i_feats = 0;
		mysql_data_seek($res1,0);
		while($line=mysql_fetch_array($res1)){
			$answertext = $database->getArrayOfQuery("SELECT ".DB_PREFIX."goods_features.goodfeature_value,".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features WHERE ID_GOOD=".$id." and ".DB_PREFIX."features.ID_FEATURE=".$line[0]);
			$feature_type = $answertext[1];
			$answertext = $answertext[0];
			if(empty($answertext)){
				$answertext = "";
			}
			switch($feature_type){
				case 7:
					if($answertext!="" && is_numeric($answertext)){
						$answertext = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT=".$answertext);
						$answertext = str_replace(array("\n","\r","\t")," ",$answertext[0]);
					}
				break;
				case 3:
					$answertext = ($answertext!="")?"да":"нет";
				break;
				case 4:
					if(!empty($answertext) && $answertext!="-"){
						$answertext = getFeatureText($id, $line[0]);
						// $line1 = $database -> getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".$answertext);
						// $answertext = $line1[0];
					}
				break;
				case 5:
					if(!empty($answertext) && $answertext!="-"){
						list($val) = $database->getArrayOfQuery("
							SELECT goodfeature_value
							FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features
							WHERE ID_GOOD='$answertext' and feature_deleted=0 and feature_enable=1 and (feature_type=2)
							LIMIT 1
						");
						$answertext = $val;
					}
				break;
				case 9:
					if(!empty($answertext) && $answertext!="-"){
						$answertext = getRubricName($answertext,false,true,true);
					}
				break;
			}
			if($feature_type!=5 && $feature_type!=9){
				$answertext = strip_tags($answertext);
				$ret = $worksheet->write($y, $x, $answertext, $frmt);
				if($ret==-3)
					$worksheet->writeNote($y, $x, $answertext);
				$x++;

			} else {
				$worksheet->write($y, $x++, $answertext, $frmt);
			}
			if($i_feats==0) $name = str_replace("'","\'",$answertext);
			$i_feats++;
		}
		while($i_feats<$num_feats){
			$worksheet->write($y, $x++, "", $frmt);
			$i_feats++;
		}

		// кол-во заполненных хар-к
		///$goodfeatcnt = $database->getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id." and (goodfeature_value>0 or goodfeature_value>'')");
		///$worksheet->write($y, $x++, $goodfeatcnt[0], $tfrmt);
	}



/// send file
$workbook->close();

/// дальше не работать
die;

?>