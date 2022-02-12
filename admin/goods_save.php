<?


$goodphoto_alt = '';
if ($act_add > 0) {
	$good_name = $_POST['good_name'][0];
	//echo '<pre>';
	//print_r($_POST);
	//echo '</pre>';
	//print_r($good_name);
	//die();
	$database->query("INSERT INTO " . DB_PREFIX . "goods (good_visible,good_deleted,good_name) VALUES (1,0,'" . mysql_escape_string($good_name) . "')");
	$change_id = $database->change_id();
	$id = $database->lastQueryId;
	// добавляем запись в нужные рубрики
	foreach ($_POST['rubric'] AS $rub => $on) {
		$database->query("INSERT INTO " . DB_PREFIX . "rubric_goods (ID_RUBRIC,ID_GOOD) VALUES ($rub,$id)", true, 0, $change_id);
	}
} else {
	$database->query("UPDATE " . DB_PREFIX . "goods SET good_deleted=0,good_name='".mysql_escape_string($good_name)."' WHERE ID_GOOD=$id", (!@$_GET['lastaction'] ? true : false));
	$change_id = $database->change_id();
	del_cache(0, $id); //удаление кэша
}
if ((DB_ID > 48 || in_array(DB_ID, $arr_db_urls)) && DB_ID != 67) {
	$good_url = $frm->get_value('good_url');
	$j = 0;
	while ($database->getArrayOfQuery("SELECT ID_GOOD FROM " . DB_PREFIX . "goods WHERE ID_GOOD<>$id && good_url='" . $good_url . (empty($j) ? "" : "$j") . "' && good_deleted=0")) {
		$j++;
	}
	$good_url = $good_url . (empty($j) ? "" : "$j");
	$database->query("UPDATE " . DB_PREFIX . "goods SET good_url='$good_url' WHERE ID_GOOD=$id", false);
}


$err = false;
$updt = false;
$err_add = '';
$message = '<table border="1">';
$fvalues = array();
foreach ($_POST['rubric'] AS $iarr => $on) {

	$res = $database->query("
		SELECT " . DB_PREFIX . "features.* 
		FROM " . DB_PREFIX . "features 
		NATURAL JOIN " . DB_PREFIX . "rubric_features 
		WHERE feature_deleted=0 and rubric_type=$type and ID_RUBRIC=" . $iarr);
	while ($line = mysql_fetch_array($res, MYSQL_ASSOC)) {
		echo '-------->';
		print_r($line);
		$sh = true;
		if ($line['feature_type'] == 5) {
			$res_fr = $database->query("SELECT ID_RUBRIC FROM " . DB_PREFIX . "feature_rubric WHERE ID_FEATURE=" . $line['ID_FEATURE']);
			if (mysql_num_rows($res_fr) < 1) {
				$sh = false;
			} else {
				$sh = true;
			}
		}
		if ($sh) {
			if (!empty($to)) {
				list($fname) = $database->getArrayOfQuery("select feature_text from cprice_features where ID_FEATURE='" . $line['ID_FEATURE'] . "'");
				$message .='<tr><td>' . $fname . '</td>';
			}
			$updt1 = true;
			$chng = false;
			$fieldname = "good_hand_" . $line['ID_FEATURE'];
			$fvalues[$line['ID_FEATURE']] = $frm->get_value($fieldname);
			//micron
			if (DB_ID == 18) {
				if ($line['ID_FEATURE'] == 191)
					$database->query("update " . DB_PREFIX . "goods set good_name='" . mysql_escape_string($fvalues[$line['ID_FEATURE']]) . "' where ID_GOOD=" . $id, false);
				if ($line['ID_FEATURE'] == 192)
					$database->query("update " . DB_PREFIX . "goods set good_price='" . $fvalues[$line['ID_FEATURE']] . "' where ID_GOOD=" . $id, false);
				if ($line['ID_FEATURE'] == 193)
					$database->query("update " . DB_PREFIX . "goods set good_nal='" . intval($fvalues[$line['ID_FEATURE']]) . "' where ID_GOOD=" . $id, false);
			}

			switch ($line['feature_type']) {
				case 1: // число
				case 2: // текст
				case 5: // ветвь
				case 8: // дата
				case 9: // раздел
					$goodfeature_value = $frm->get_value($fieldname);
					$vals = '';
					if (is_array($goodfeature_value)) {
						foreach ($goodfeature_value as $item)
							$vals .= $item . '<br/>';
					} else
						$vals = $goodfeature_value;
					$message .='<td>' . $vals . '</td></tr>';
					break;
				case 10: // раздел записей
					@$goodfeature_value = $_POST[$fieldname . "_"];
					if ($line['feature_require'] == 1) {
						$error_fill = true;
						if (count($goodfeature_value) > 0) {
							foreach ($goodfeature_value as $value)
								if (!empty($value))
									$error_fill = false;
						}
						if ($error_fill) {
							$updt1 = false;
							$frm->errorValue($fieldname, "Поле обязательно для заполнения. Хоть одна запись из раздела должна быть присутствовать");
							$err = true;
						}
					}
					break;
				case 7: // большой текст
					$goodfeature_value = $frm->get_value($fieldname);
					$message .='<td>' . $goodfeature_value . '</td></tr>';

					if ($line1 = $database->getArrayOfQuery("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=$id and ID_FEATURE=" . $line['ID_FEATURE'] . " and goodfeature_value<>'0'")) {
						$res_txt = $database->query("
										SELECT text_text
										FROM " . DB_PREFIX . "texts
										WHERE ID_TEXT = '" . (int) $line1[0] . "'
									");
						if (mysql_num_rows($res_txt) == 1) {
							$row_txt = mysql_fetch_row($res_txt);
							$value_old_txt = $row_txt[0];
							if ($goodfeature_value != $value_old_txt) {
								if ($goodfeature_value != '') {
									$database->query("UPDATE " . DB_PREFIX . "texts SET text_text='" . $goodfeature_value . "' WHERE ID_TEXT=" . (int) $line1[0], true, 0, $change_id);
									$goodfeature_value = (int) $line1[0];
								} else {
									$database->query("DELETE FROM " . DB_PREFIX . "texts WHERE ID_TEXT=" . (int) $line1[0], true, 3, $change_id);
									$goodfeature_value = 0;
								}
								$chng = true;
							} else
								$goodfeature_value = (int) $line1[0];
						}else {
							if (!empty($goodfeature_value)) {
								// большой текст сохраняем в отдельной тейбл
								$database->query("INSERT INTO " . DB_PREFIX . "texts (text_text) VALUES ('" . $goodfeature_value . "')", true, 0, $change_id);
								$goodfeature_value = $database->id();
							} else {
								$goodfeature_value = '0';
							}
						}
					} else {
						if (!empty($goodfeature_value)) {
							// большой текст сохраняем в отдельной тейбл
							$database->query("INSERT INTO " . DB_PREFIX . "texts (text_text) VALUES ('" . $goodfeature_value . "')", true, 0, $change_id);
							$goodfeature_value = $database->id();
						} else {
							$goodfeature_value = 0;
						}
					}
					break;
				case 3: // логика
					$goodfeature_value = $frm->get_value_checkbox($fieldname);
					$message .='<td>' . (empty($goodfeature_value) ? 'нет' : 'да') . '</td></tr>';
					break;
				case 4: // справочник
					if ($line['feature_rubric'] == 0) {
						$goodfeature_value = $frm->get_value($fieldname);
						$message .='<td>' . $goodfeature_value . '</td></tr>';
						//print_r($goodfeature_value);die();
					} else {
						$goodfeature_value = array();
						$goodfeature_value1 = $frm->get_value($fieldname);
						if (!is_array($goodfeature_value1))
							$goodfeature_value1 = array($goodfeature_value1);
						foreach ($goodfeature_value1 AS $gf) {
							if (!$db = $database->getArrayOfQuery("SELECT ID_FEATURE_DIRECTORY FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE=" . $line['ID_FEATURE'] . " and featuredirectory_text='" . $gf . "'")) {
								$database->query("INSERT INTO " . DB_PREFIX . "feature_directory (ID_FEATURE,featuredirectory_text) VALUES (" . $line['ID_FEATURE'] . ", '" . $frm->get_value($fieldname) . "')");
								$goodfeature_value[] = $database->id();
							} else {
								$goodfeature_value[] = $db[0];
							}
						}
					}
					break;
				case 6: // файл
					if (isset($_POST['fdel' . $fieldname])) {
						foreach ($_POST['fdel' . $fieldname] as $file) {
							$filedir = DATA_FLD . "features/" . $file;
							unlink($filedir);
							$database->query("DELETE FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=$id && goodfeature_value='$file' and ID_FEATURE=" . $line['ID_FEATURE'], true, 0, $change_id);
						}
					}
					$goodfeature_value = $frm->get_value($fieldname);
					if ($goodfeature_value != "on") {
						$goodfeature_value = $frm->move_file($fieldname, 'features', DATA_FLD);
						if (!empty($goodfeature_value)) {
							$arrs = array();
							if (!is_array($goodfeature_value))
								$arrs[] = $goodfeature_value;
							else
								$arrs = $goodfeature_value;
							foreach ($arrs as $goodfeature_value) {
								$fn = pathinfo($goodfeature_value);
								if (
									!$database->getArrayOfQuery("SELECT ID_FEATURE_DIRECTORY FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE=" . $line['ID_FEATURE'] . "") ||
									$database->getArrayOfQuery("SELECT ID_FEATURE_DIRECTORY FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE=" . $line['ID_FEATURE'] . " and featuredirectory_text='" . $fn['extension'] . "'")
								) {
									
								} else {
									$updt1 = false;
									$frm->errorValue($fieldname, "Тип файла не совпадает с правилами!");
									//$frm->send();
									$err = true;
								}
							}
							$goodfeature_value = $arrs;
						} else {
							$updt1 = false;
						}
					} else {
						$goodfeature_value = $frm->move_file($fieldname, 'features');
						unlink($goodfeature_value);
						$goodfeature_value = "";
					}
					break;
			}


			/*			 * ******** временно отключил Теленков Д.С. *********** */
			/*			 * *  Алексей - 11.11.08   ** */
			/* 					if($line['feature_type']==7){
			  $goodfeature_value1 = $frm->get_value($fieldname);
			  } else {
			  $goodfeature_value1 = $goodfeature_value;
			  }
			  $k=count($param)+1;
			  $param[$k]['idgood']=$id;
			  $param[$k]['idfuture']=$line['ID_FEATURE'];
			  $param[$k]['fut_text']=$goodfeature_value1;
			  $param[$k]['fut_vis']=1;
			 */
			/*			 * *  Алексей - 11.11.08   ** */



			if (!is_array($goodfeature_value)) {
				$goodfeature_value = array($goodfeature_value);
			}
			if (empty($goodphoto_alt) && !empty($goodfeature_value[0]) && $line['feature_type'] == 2)
				$goodphoto_alt = $goodfeature_value[0];
			if ($updt1) {
				if ($act_add == 0) {
					$res2 = $database->query("SELECT ID_GOOD_FEATURE,goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=$id and ID_FEATURE=" . $line['ID_FEATURE']);
					$n_rows = mysql_num_rows($res2);
					if ($n_rows == count($goodfeature_value)) {
						$i = 0;
						while ($row = mysql_fetch_row($res2)) {
							if ($row[1] != $goodfeature_value[$i] || $chng) {
								$float_val = $line['feature_type'] == 1 ? floatval(str_replace(",", ".", $goodfeature_value[$i])) : 0;
								if ($line['feature_type'] == 8 && !empty($goodfeature_value[$i])) {
									$arr = explode(".", $goodfeature_value[$i]);
									if (count($arr) > 2)
										$float_val = mktime(12, 0, 0, $arr[1], $arr[0], $arr[2]);
								}
								if ($n_rows > 1 && empty($val))
									$database->query("DELETE FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD_FEATURE=" . $row[0], true, 0, $change_id);
								else
									$database->query("UPDATE " . DB_PREFIX . "goods_features  set goodfeature_value='$goodfeature_value[$i]',goodfeature_float=" . $float_val . " WHERE ID_GOOD_FEATURE=" . $row[0], true, 0, $change_id);
							}
							$i++;
						}
					}
					else {
						if ($n_rows > 0)
							$database->query("DELETE FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=$id and ID_FEATURE=" . $line['ID_FEATURE'], true, 0, $change_id);
						foreach ($goodfeature_value AS $val) { {
								$float_val = $line['feature_type'] == 1 ? floatval(str_replace(",", ".", $val)) : 0;
								if ($line['feature_type'] == 8 && !empty($val)) {
									$arr = explode(".", $val);
									if (count($arr) > 2)
										$float_val = mktime(12, 0, 0, $arr[1], $arr[0], $arr[2]);
								}
								$database->query("INSERT INTO " . DB_PREFIX . "goods_features (ID_GOOD,ID_FEATURE,goodfeature_value,goodfeature_visible,goodfeature_float) VALUES ($id," . $line['ID_FEATURE'] . ",'" . $val . "',1," . $float_val . ")", true, 0, $change_id);
							}
						}
					}
				}
				else {
					foreach ($goodfeature_value AS $val) {
						$float_val = $line['feature_type'] == 1 ? floatval(str_replace(",", ".", $val)) : 0;
						if ($line['feature_type'] == 8 && !empty($val)) {
							$arr = explode(".", $val);
							if (count($arr) > 2)
								$float_val = mktime(12, 0, 0, $arr[1], $arr[0], $arr[2]);
						}
						$database->query("INSERT INTO " . DB_PREFIX . "goods_features (ID_GOOD,ID_FEATURE,goodfeature_value,goodfeature_visible,goodfeature_float) VALUES ($id," . $line['ID_FEATURE'] . ",'" . $val . "',1," . $float_val . ")", true, 0, $change_id);
					}
				}
			}
			if ($err)
				break;
		}
	}
	if ($err)
		break;
}
if ($err) {
	if ($act_add > 0) {
		teRedirect(teGetUrlQuery("action=edit", "id=" . $id, "ses_add=0") . $err_add);
	} else
		$frm->send();
}

//optimizeGood($id);


/* * *  Алексей - 11.11.08   ** *//*
  if(isset($_GET['ses_add'])){
  if($_GET['ses_add']==1) $create_news_triger->createNews_good($type,2,$param);
  }
  $my_arr_price2=$create_news_triger->what_price($type,$id);
  $create_news_triger->compare_array($my_arr_price1,$my_arr_price2,$id,$type);
  /***  Алексей - 11.11.08   ** */


if (isset($_POST['fdelphoto'])) {
	foreach ($_POST['fdelphoto'] as $file) {
		list($fid) = $database->getArrayOfQuery("SELECT ID_GOOD_PHOTO FROM " . DB_PREFIX . "goods_photos WHERE goodphoto_deleted=0 and ID_GOOD=" . $id . " && goodphoto_file='$file'");
		$database->query("UPDATE " . DB_PREFIX . "goods_photos SET goodphoto_deleted=1 WHERE ID_GOOD_PHOTO=$fid", true, 3);
		unlink(DATA_FLD . "good_photo/image_" . $file);
		unlink(DATA_FLD . "good_photo/trumb_" . $file);
		unlink($filedir);
	}
}
$goodphoto_files = $frm->move_file('photo');
if ($goodphoto_files) {
	teInclude("images");
	$tphw = teGetConf('photo_tmaxw');
	$tphh = teGetConf('photo_tmaxh');
	$mphw = teGetConf('photo_mmaxw');
	$mphh = teGetConf('photo_mmaxh');
	asort($goodphoto_files);
	foreach ($goodphoto_files as $goodphoto_file) {
		teImgTrumb(DATA_FLD . "good_photo/" . $goodphoto_file, "image_", $mphw, $mphh);
		teImgTrumb(DATA_FLD . "good_photo/" . $goodphoto_file, "trumb_", $tphw, $tphh);
		$size_img = getimagesize(DATA_FLD . "good_photo/" . $goodphoto_file);
		if (($size_img[0] > 1024 || $size_img[1] > 1024 || filesize(DATA_FLD . "good_photo/" . $goodphoto_file) > 400000) && (!isset($_POST['malph']))) {
			teImgTrumb(DATA_FLD . "good_photo/" . $goodphoto_file, "", 1024, 1024, NULL, 80);
		}
		if (isset($_POST['wtmrk'])) {
			new_wm_image(DATA_FLD . 'good_photo/' . "image_" . $goodphoto_file);
			new_wm_image(DATA_FLD . 'good_photo/' . $goodphoto_file);
		}
		$database->query("INSERT INTO " . DB_PREFIX . "goods_photos (ID_GOOD,goodphoto_alt,goodphoto_file,goodphoto_pos) VALUES ($id, '$goodphoto_alt','$goodphoto_file'," . ++$n_ph . ")");
	}
}


if (!empty($_GET['iframe']) || !empty($_POST['iframe'])) {
	print teGetJSScript("
					parent.subform_addval($id,'" . getFeatureText($id, 0, true) . "','http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "');
					parent.close_subform();
				");
} else {
	if (!$err && $_POST['edit'] == 1)
		teRedirect(teGetUrlQuery("action=edit", "id=" . $id, isset($_GET['from_nezap']) ? 'from_nezap=' . $_GET['from_nezap'] : ''));

	/* Возможность выбора значений характеристик из отдельного списка вместо выпадающего */
	if (!$err)
		if (!empty($_POST['rubric_multi'])) {
			$id_good2 = $_POST['id_good'];
			$id_feature2 = $_POST['id_feature'];
			teRedirect(teGetUrlQuery("=goods_multirubric", "id_good=$id_good2", "id_feature=$id_feature2"));
		}
	/*	 * ********************************************************************************* */

	if (isset($_GET['events']) && !$err)
		teRedirect(teGetUrlQuery("=events_see", "rub_id=" . $rubric_id));
	if (isset($_GET['from_nezap']) && !$err)
		teRedirect(teGetUrlQuery("=nezapoln_4mmc", "showid=" . $rubric_id, 'nezap=' . $_GET['from_nezap']));
	if (!$err)
		if (!@$acc['v'] && !@$acc['e'])
			teRedirect(teGetUrlQuery("=rubric"));
	if (!$err)
		teRedirect(teGetUrlQuery("action=&lastaction=save"));
}
?>