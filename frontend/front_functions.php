<?php

function getGoodData($good_id, $fetures = array(), $imgtype = '') {
	global $database;
	$value = array();
	$imgarr = getImages($good_id, 10, $imgtype);
	foreach ($imgarr as $img) {
		//print_r($img);
		$value['IMAGES'][] = array('url' => $img);
	}
	$value['ID'] = $good_id;

	list($url) = $database->getArrayOfQuery("SELECT good_url FROM  " . DB_PREFIX . "goods WHERE ID_GOOD=" . $good_id . " LIMIT 1");
	$value['URL'] = $url;
	list($rubric_url) = $database->getArrayOfQuery(
		"SELECT rubric_textid FROM " . DB_PREFIX . "rubric NATURAL JOIN " . DB_PREFIX . "rubric_goods
		 WHERE ID_GOOD=" . $good_id . ""
	);
	$value['FULL_URL'] = CATALOG_URL . $rubric_url . '/' . $url . '/';
	$res = $database->query("select * from cprice_goods_features t1
			natural join cprice_rubric_features t2
			natural join cprice_features t3
			where  t1.ID_GOOD='" . $good_id . "' order by t2.rubricfeature_pos");

	while ($row = mysql_fetch_array($res)) {
		switch ($row['feature_type']) {
			case 4:
				if ($row['goodfeature_value'] > 0) {
					list($text) = $database->getArrayOfQuery("select featuredirectory_text from cprice_feature_directory where ID_FEATURE_DIRECTORY='" . $row['goodfeature_value'] . "' limit 1");
					$value[$row['ID_FEATURE']] = $text;
				}
				break;
			case 7:
				if ($row['goodfeature_value'] > 0) {
					list($text) = $database->getArrayOfQuery("select text_text from cprice_texts where ID_TEXT='" . $row['goodfeature_value'] . "' limit 1");
					$value[$row['ID_FEATURE']] = $text;
				}
				break;
			default:
				$value[$row['ID_FEATURE']] = $row['goodfeature_value'];
				break;
		}
	}

	return $value;
}

function i_a($url, $text, $params = '') {
	$out = '<a href="' . $url . '" ' . $params . '>' . $text . '</a>';
	return $out;
}

//{{========Нумерация страниц===================================================
//$qstr - запрос
//$pg - номер страницы
//$per_page - количество сообщений на странице
//$page - название страницы
//$add - дополнительные параметры в GET - параметрах страницы
//$all_num - количество всех записей при пустом $qstr
//Возвращает массив:
//[0] - номер записи в БД, для запроса($start_page);
//[1] - отображение нумерации на странице($nums)(типа [1][2][3]...)
//[2] - количество всех записей
function numbers($qstr, $pg, $per_page = 20, $page, $add = '', $all_num = 0) {
//Проверка входных данных
	if (empty($pg) || !is_numeric($pg) || $pg < 1)
		$pg = 1;
	if ($per_page < 1)
		$per_page = 1;
	if (!empty($qstr))
		$num = mysql_num_rows(mysql_query($qstr));
	else
		$num = $all_num;

	if (substr($add, 0, 5) == '&amp;')
		$add2 = '?' . substr($add, 5);
	elseif (substr($add, 0, 1) == '&')
		$add2 = '?' . substr($add, 1);
	else
		$add2 = $add;

	$num_of_page = ceil($num / $per_page);
	$nums = '';
	if ($num_of_page < 10) {
		if ($num_of_page > 1) {
			for ($i = 1; $i <= $num_of_page; $i++) {
				//Если $i == номеру текущей странице, то
				//выводим без ссылки:
				if ($i == $pg) {
					$nums.= "&nbsp;<b>" . $i . "</b>&nbsp;";
				} else {
					//Иначе, с ссылкой:
					$nums.= '&nbsp;' . i_a($page . ($i == 1 ? $add2 : '?pg=' . $i . $add), $i) . '&nbsp;';
				}
			}
		}
	} else {
		$tek = $pg;
		if ($tek < 7) {
			for ($i = 1; $i <= ($tek + 6) && $i <= $num_of_page; $i++) {
				//Если $i == номеру текущей странице, то
				//выводим без ссылки:
				if ($i == $pg) {
					$nums.= "&nbsp;<b>" . $i . "</b>&nbsp;";
				} else {
					//Иначе, с ссылкой:
					$nums.= '&nbsp;' . i_a($page . ($i == 1 ? $add2 : '?pg=' . $i . $add), $i) . '&nbsp;';
				}
			}
			if ($num_of_page > ($tek + 7))
				$nums.='&nbsp;' . i_a($page . '?pg=' . ($tek + 7) . $add, '>>') . '&nbsp;';
		}
		else {
			if (($tek - 7) > 0)
				$nums.='&nbsp;' . i_a($page . (($tek - 7) == 1 ? $add2 : '?pg=' . ($tek - 7) . $add), '<<') . '&nbsp;';
			for ($i = ($tek - 6); $i <= ($tek + 7) && $i <= $num_of_page; $i++) {
				//Если $i == номеру текущей странице, то
				//выводим без ссылки:
				if ($i == $pg) {
					$nums.= "&nbsp;<b>" . $i . "</b>&nbsp;";
				} else {
					//Иначе, с ссылкой:
					$nums.= '&nbsp;' . i_a($page . ($i == 1 ? $add2 : '?pg=' . $i . $add), $i) . '&nbsp;';
				}
			}
			if ($num_of_page > ($tek + 8))
				$nums.='&nbsp;' . i_a($page . '?pg=' . ($tek + 8) . $add, '>>') . '&nbsp;';
		}
	}
	$start_page = $per_page * ($pg - 1);
//$num_arr=array($start_page,$nums);

	return array($start_page, $nums, $num);
}

//}}========Нумерация страниц===================================================
function rec_grad($id, $type, $furl = "/", $num = 1, $grads = array()) {
	global $database;
	$res_grad = $database->query("select ID_FEATURE, rubricfeature_graduation from " . DB_PREFIX . "rubric_features where ID_RUBRIC='$id' && rubric_type=" . $type . " && rubricfeature_graduation>0 && rubricfeature_graduation=$num");
	$out = '';
	if (mysql_num_rows($res_grad) > 0) {
		if (isset($_GET['grad']))
			$grad = $_GET['grad'];
		else
			$grad = array();
		$add_sql = '';
		$i = 1;
		foreach ($grads as $fid => $fval) {
			$add_sql .= " || (ID_FEATURE=" . $fid . " and goodfeature_value='" . $fval . "')";
			$i++;
		}
		while ($row_grad = mysql_fetch_array($res_grad)) {
			$fid = $row_grad[0];
			$res_list = $database->query("select * from " . DB_PREFIX . "feature_directory where ID_FEATURE=" . $fid . " order by featuredirectory_text");
			while ($row_list = mysql_fetch_array($res_list)) {
				$fval = $row_list[0];
				$res_goods = $database->query("select * from (SELECT ID_GOOD, COUNT(ID_GOOD) as cnt FROM cprice_goods natural join cprice_rubric_goods NATURAL JOIN cprice_goods_features WHERE ID_RUBRIC=$id and good_deleted=0 and good_visible=1 and (ID_FEATURE=" . $fid . " and goodfeature_value='" . $fval . "'" . $add_sql . ") group by ID_GOOD) as tbl where cnt={$num}");
				if (mysql_num_rows($res_goods) > 0) {
					if (in_array($row_list[0], $grad))
						$out .= '<li>' . $row_list['featuredirectory_text'];
					else
						$out .= '<li><a href="' . $furl . 'grp' . $fid . '_' . $fval . '/">' . $row_list['featuredirectory_text'] . '</a>';
					$grads2 = array();
					$grads2[$fid] = $fval;
					$out .= rec_grad($id, $type, $furl . 'grp' . $fid . '_' . $fval . '/', ($num + 1), ($grads + $grads2));
					$out .= '</li>';
				}
			}
		}
	}
	if (!empty($out))
		$out = '<ul>' . $out . '</ul>';
	return $out;
}

/**
 * генерирует список-карту сайта для вывода на соответствующей странице
 * @param type $id ид корневой рубрики, начиная с которой начивается генерация
 * @param type $type ид раздела
 * @param type $furl начальная строка пути сгенерированной ссылки списка
 * @return string html список ссылок на страницы сайта
 */
function rec_map($id, $type, $furl = '/', $arrNoUse = array()) {
	global $database, $page_arr;
	$out = '';

	$res = $database->query("select * from " . DB_PREFIX . "rubric where rubric_parent='$id' && rubric_type=" . $type .
		" && ID_RUBRIC<>" . $page_arr[G_PAGE_ID] . " && rubric_deleted=0 && rubric_visible=1 order by rubric_pos");
	if (mysql_num_rows($res) == 0) {
		list($url) = $database->getArrayOfQuery("select rubric_textid from " . DB_PREFIX . "rubric where ID_RUBRIC='$id'");
		$url = $furl . $url . "/";
		return rec_grad($id, $type, $url);
	}
	$out .= '<ul>' . "\r\n";
	while ($row = mysql_fetch_array($res)) {

		if (in_array($row['ID_RUBRIC'], $arrNoUse)) {
			continue;
		}
		$url = $furl . ( ($row['ID_RUBRIC'] == DEFAULT_PAGE_ID) ? "" : $row['rubric_textid'] . "/" );
		$out .= '<li><a href="' . $url . '">' . $row['rubric_name'] . '</a>';
		$modul = teGetConf("rtpl_" . $row['ID_RUBRIC']);
		$new_type = 0;
		if ($modul == 'price.php')
			$new_type = GOODS_TYPE;
		if ($modul == 'rubric-good.php')
			$new_type = teGetConf("rtpl_" . $row['ID_RUBRIC'] . "_rtype");
		if ($new_type > 0)
			$out .= rec_map(0, $new_type, $url);
		else
			$out .= rec_map($row['ID_RUBRIC'], $type, $furl);
		$out .= '</li>' . "\r\n";
	}
	$out .= '</ul>' . "\r\n";
	return $out;
}

define("GI_ARRAY", true);
define("GI_IMAGE", false);

/**
 * Возвращает массив значений характеристик записи good_id
 * @param type $good_id ид записи базы данных
 * @param type $features массив ид характеристик (если опустить - выбирает все видимые не удаленные характеристики)
 * @param type $fvalues по умолчанию FALSE - возвращает значение характеристики с проверкой типа характеристики. Иначе, если TRUE - возвращает значение ячейки этой характеристики в базе без проверки типа характеристики
 * @param type $url флаг возврата текстового идентификатора записи ['url']
 * @return boolean FALSE в случае не успешного завершения
 */
function getDataId($good_id, $features = array(), $fvalues = false, $url = false) {
	global $database;
	if ($good_id) {
		if (!$features) {
			$features = $database->getColumnOfQuery('select ID_FEATURE from ' . DB_PREFIX . 'goods_features natural join ' .
				DB_PREFIX . 'features where ID_GOOD=' . $good_id . ' && feature_enable=1 && feature_deleted=0');
		}
		$data = array();
		if ($url) {
			list($url) = $database->getArrayOfQuery('SELECT good_url FROM ' . DB_PREFIX . 'goods WHERE ID_GOOD=' . $good_id);
			$data['url'] = $url;
		}
		foreach ($features as $feature_id) {
			if ($feature_id > 0) {
				if ($fvalues) {
					list($good) = $database->getArrayOfQuery('SELECT goodfeature_value FROM ' . DB_PREFIX .
						'goods_features WHERE ID_GOOD=' . $good_id . ' and ID_FEATURE="' . $feature_id . '" limit 1');
					$data[$feature_id] = $good;
				} else {
					$data[$feature_id] = getFeatData($feature_id, $good_id);
				}
			}
		}
		return $data;
	}
	return false;
}

//Возвращает данные из рубрики $rubric_id
//Данные представленны в виде массива: $data[ИД записи][ИД характеристики] = значение
//$order_by указывает по каким полям надо сортировать записи (любые поля из таблиц cprice_goods, cprice_rubric_goods)
//по умолчанию сортирует по rubricgood_pos, ID_GOOD (сначала по порядку записи в рубрике, потом по ее ИД)
//$limit указывает количество возвращаемых данных (значение после параметра LIMIT в SQL - запросе)
//$features - массив ИД характеристик, по которым берутся данные. Если он пустой, то берутся все характеристики
//$fvalues - указывает возвращать первоначальные значения из таблицы goods_features
//$uslovia - условия возврата данных формата: array(ИД характеристики=>Значение условия)
//$visible - возвращать только видимые данные
function getData($rubric_id, $orderby = '', $limit = '', $features = array(), $fvalues = false, $uslovia = array(), $visible = true) {
	global $database;
	$data = array();
	if (count($features) == 0) {
		$res_feat = $database->query("
				SELECT ID_FEATURE
				FROM " . DB_PREFIX . "features NATURAL JOIN " . DB_PREFIX . "rubric_features
				WHERE ID_RUBRIC=" . $rubric_id . " and feature_deleted=0
				ORDER BY rubricfeature_pos
			");
		while (list($feature_id) = mysql_fetch_array($res_feat)) {
			$features[] = $feature_id;
		}
	}
	$add_tbl = '';
	$add_sql = '';
	if (count($uslovia) > 1) {
		$n = count($uslovia);
		foreach ($uslovia as $fid => $fval) {
			$add_sql .= " || (ID_FEATURE=" . $fid . " && goodfeature_value='" . $fval . "')";
		}
		$qstr = "select * from (select ID_GOOD, good_url, good_visible, count(ID_GOOD) as cnt, rubricgood_pos from cprice_goods natural join cprice_rubric_goods natural join cprice_goods_features
			where ID_RUBRIC='$rubric_id' && rubricgood_deleted=0 " . ($visible ? " && good_visible=1" : "") . " && good_deleted=0 && (" . substr($add_sql, 4) . ") group by ID_GOOD) as tbl where cnt=" . $n;
	} else {

		if (count($uslovia) > 0) {
			$add_tbl = 'natural join cprice_goods_features';
			$add_sql = " && (";
			foreach ($uslovia as $fid => $fval) {
				$add_sql .= "(ID_FEATURE='" . $fid . "' && goodfeature_value='" . $fval . "') || ";
			}
			$add_sql = substr($add_sql, 0, -4) . ")";
		}
		$qstr = "
				SELECT ID_GOOD,good_url,good_visible
				FROM " . DB_PREFIX . "rubric_goods NATURAL JOIN " . DB_PREFIX . "goods $add_tbl
				WHERE ID_RUBRIC=" . $rubric_id . " and good_deleted=0" . ($visible ? " and good_visible=1" : "") . $add_sql
		;
	}
	$qstr .= (empty($orderby) ? " ORDER BY rubricgood_pos, ID_GOOD" : " ORDER BY " . $orderby)
		. (empty($limit) ? "" : " LIMIT " . $limit);
	$res = $database->query($qstr);
	while (list($good_id, $url, $visible) = mysql_fetch_array($res)) {
		$data[$good_id]['url'] = $url;
		$data[$good_id]['visible'] = $visible;
		foreach ($features as $feature_id) {
			if ($feature_id > 0) {
				if ($fvalues) {
					list($good) = $database->getArrayOfQuery("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=" . $good_id . " and ID_FEATURE='" . $feature_id . "' limit 1");
					$data[$good_id][$feature_id] = $good;
				} else
					$data[$good_id][$feature_id] = getFeatData($feature_id, $good_id);
			}
		}
	}
	return $data;
}

function insertData($rubric_id, $data, $visible = 1) {
	global $database;
	if ($rubric_id > 0 && is_array($data)) {
		$database->query("INSERT INTO " . DB_PREFIX . "goods (good_visible,good_deleted) VALUES ($visible,0)");
		$id = $database->id();
		$database->query("INSERT INTO " . DB_PREFIX . "rubric_goods (ID_RUBRIC,ID_GOOD) VALUES (" . $rubric_id . ",$id)");
		foreach ($data as $fid => $value) {
			if (is_array($value)) {
				foreach ($value as $item)
					setFeatData($fid, $id, $item);
			} else
				setFeatData($fid, $id, $value);
		}
		return $id;
	} else
		return -1;
}

function updateData($good_id, $data) {
	global $database;
	if ($good_id > 0 && is_array($data)) {
		foreach ($data as $fid => $value) {
			if (is_array($value)) {
				$database->query("DELETE FROM " . DB_PREFIX . "goods_features WHERE ID_FEATURE=" . $fid . " && ID_GOOD=" . $good_id);
				foreach ($value as $item)
					setFeatData($fid, $good_id, $item);
			} else
				setFeatData($fid, $good_id, $value, true);
		}
		return $good_id;
	} else
		return -1;
}

function getImages2($id, $num = 1, $pre = "trumb_", $returnarray = false) {
	global $database;

	$arr = array();

	$res = $database->query("
			SELECT *
			FROM " . DB_PREFIX . "goods_photos
			WHERE ID_GOOD = '$id' and goodphoto_visible=1 and goodphoto_deleted=0
			ORDER BY goodphoto_pos
			LIMIT $num
		");
	while ($line = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$id_ph = $line['ID_GOOD_PHOTO'];
		$resfeat = $line['goodphoto_file'];
		if (!empty($resfeat)) {
			$resfeat1 = DATA_FLD2 . "good_photo/" . $pre . "" . $resfeat . "";
			if (file_exists(DATA_FLD3 . "images/good_photo/" . $pre . "" . $resfeat)) {
				if ($num == 1) {
					if ($returnarray) {
						return array($resfeat1, $line['goodphoto_desc'], $line['goodphoto_alt']);
					} else {
						return $resfeat1;
					}
				} else {
					if ($returnarray) {
						$arr[$id_ph] = array($resfeat1, $line['goodphoto_desc'], $line['goodphoto_alt'], $resfeat);
					} else {
						$arr[$id_ph] = $resfeat1;
					}
				}
			}
		}
	}
	if ($num == 1) {
		return false;
	} else {
		return $arr;
	}
}

function getAlt($id) {
	global $database;

	list($alt) = $database->getArrayOfQuery("
			SELECT goodphoto_alt
			FROM " . DB_PREFIX . "goods_photos
			WHERE ID_GOOD = '$id' and goodphoto_visible=1 and goodphoto_deleted=0
			ORDER BY goodphoto_pos
			LIMIT 1
		");
	return $alt;
}

function getTitle($id) {
	global $database;

	list($title) = $database->getArrayOfQuery("
			SELECT goodphoto_desc
			FROM " . DB_PREFIX . "goods_photos
			WHERE ID_GOOD = '$id' and goodphoto_visible=1 and goodphoto_deleted=0
			ORDER BY goodphoto_pos
			LIMIT 1
		");
	return $title;
}

function pasteImages($id, $txt, $title = '', $alt = '') {
	global $database;
	if (empty($alt))
		$alt = $title;
	$res = $database->query("
			SELECT *
			FROM " . DB_PREFIX . "goods_photos
			WHERE ID_GOOD = $id and goodphoto_visible=1 and goodphoto_deleted=0
			ORDER BY goodphoto_pos
			LIMIT 100
		");
	if ($cnt = mysql_num_rows($res)) {
		while ($line = mysql_fetch_array($res, MYSQL_ASSOC)) {
			$imgs1[$line['goodphoto_pos']] = array($line['goodphoto_file'], $line['goodphoto_desc'], $line['goodphoto_alt']);
		}

		foreach ($imgs1 AS $i => $imgs) {
			$title = empty($imgs[1]) ? $title : $imgs[1];
			$alt = empty($imgs[2]) ? $alt : $imgs[2];
			$txt = str_replace("<trumb" . ($i) . ">", "<img src='" . DATA_FLD2 . "good_photo/trumb_" . $imgs[0] . "' title='" . $title . "' alt='" . $alt . "'/>", $txt);
			$txt = str_replace("<trumb" . ($i) . ":left>", "<img src='" . DATA_FLD2 . "good_photo/trumb_" . $imgs[0] . "' title='" . $title . "' alt='" . $alt . "' style='float:left;margin:2px;'/>", $txt);
			$txt = str_replace("<trumb" . ($i) . ":right>", "<img src='" . DATA_FLD2 . "good_photo/trumb_" . $imgs[0] . "' title='" . $title . "' alt='" . $alt . "' style='float:right;margin:2px;'/>", $txt);

			$txt = str_replace("<img" . ($i) . ">", "<img src='" . DATA_FLD2 . "good_photo/" . $imgs[0] . "' title='" . $title . "' alt='" . $alt . "' style='margin:3px;max-width:575px;'/>", $txt);
			$txt = str_replace("<img" . ($i) . ":left>", "<img src='" . DATA_FLD2 . "good_photo/" . $imgs[0] . "' title='" . $title . "' alt='" . $alt . "' style='float:left;margin:3px;max-width:575px;'/>", $txt);
			$txt = str_replace("<img" . ($i) . ":right>", "<img src='" . DATA_FLD2 . "good_photo/" . $imgs[0] . "' title='" . $title . "' alt='" . $alt . "' style='float:right;margin:3px;max-width:575px;'/>", $txt);
		}
	}

	return $txt;
}

function getFeatText($feat_id) {
	global $database;

	list($out) = $database->getArrayOfQuery("
			SELECT feature_text
			FROM " . DB_PREFIX . "features
			WHERE ID_FEATURE = " . (int) $feat_id . "
		");

	return $out;
}

function getFeatData($feat_id, $good_id, $ppl = true) {
	global $database;

	list($restype, $resfeat) = $database->getArrayOfQuery("
			SELECT " . DB_PREFIX . "features.feature_type, " . DB_PREFIX . "goods_features.goodfeature_value
			FROM " . DB_PREFIX . "goods_features NATURAL JOIN " . DB_PREFIX . "features
			WHERE " . DB_PREFIX . "goods_features.ID_FEATURE = '" . $feat_id . "' and ID_GOOD = '" . $good_id . "'
		");
	if (!$ppl)
		return $resfeat;
	switch ($restype) {
		case 3:
			if ($resfeat)
				return "да";
			else
				return "нет";
			break;
		case 4:
			list($resfeat) = $database->getArrayOfQuery("
					SELECT featuredirectory_text
					FROM " . DB_PREFIX . "feature_directory
					WHERE ID_FEATURE_DIRECTORY = '" . $resfeat . "'
				");
			break;
		case 7:
			list($resfeat) = $database->getArrayOfQuery("
					SELECT text_text
					FROM " . DB_PREFIX . "texts
					WHERE ID_TEXT = '" . (int) $resfeat . "'
				");
			break;
		case 9:
			return getRubricName($resfeat, false, false, false);
			break;
	}
	return $resfeat;
}

function setFeatData($feat_id, $good_id, $value, $update = false) {
	global $database;

	list($type) = $database->getArrayOfQuery("
			SELECT feature_type
			FROM " . DB_PREFIX . "features
			WHERE ID_FEATURE = '" . $feat_id . "'
		");

	if ($update) {
		$res = $database->query("SELECT goodfeature_value,ID_GOOD_FEATURE FROM " . DB_PREFIX . "goods_features WHERE ID_FEATURE = '" . $feat_id . "' and ID_GOOD = '" . $good_id . "'");
		$n = mysql_num_rows($res);
		if ($n == 0) {
			$database->query("INSERT INTO " . DB_PREFIX . "goods_features (ID_FEATURE,ID_GOOD,goodfeature_value) VALUES ('" . $feat_id . "','" . $good_id . "','" . $value . "')");
			return true;
		}
		if ($n > 1)
			return false;
		$row = mysql_fetch_row($res);
		$value_old = $row[0];
		$value_old_id = $row[1];
		if ($type == 7) {
			list($value_old_txt) = $database->getArrayOfQuery("
					SELECT text_text
					FROM " . DB_PREFIX . "texts
					WHERE ID_TEXT = '" . (int) $value_old . "'
				");
			if ($value_old_txt != $value) {
				$database->query("UPDATE " . DB_PREFIX . "texts SET text_text='$value' WHERE ID_TEXT=" . $value_old);
			}
		} elseif ($value_old != $value)
			$database->query("UPDATE " . DB_PREFIX . "goods_features SET goodfeature_value='" . $value . "' WHERE ID_GOOD_FEATURE='" . $value_old_id . "'");
	} else {
		switch ($type) {
			case 7:
				$database->query("INSERT INTO " . DB_PREFIX . "texts (text_text) VALUES('$value')");
				$value = $database->id();
				break;
		}
		$database->query("INSERT INTO " . DB_PREFIX . "goods_features (ID_FEATURE,ID_GOOD,goodfeature_value) VALUES ('" . $feat_id . "','" . $good_id . "','" . $value . "')");
	}
	return true;
}

function getFeatures($good_id) {
	global $database;

	/* $s = "<table border=1>"; */
	$s = "<ul class='leftbor'>";

	list($type, $rubric_id) = $database->getArrayOfQuery("
			SELECT " . DB_PREFIX . "rubric.rubric_type," . DB_PREFIX . "rubric.ID_RUBRIC
			FROM " . DB_PREFIX . "rubric NATURAL JOIN " . DB_PREFIX . "rubric_goods
			WHERE ID_GOOD = $good_id
		");
	$res = $database->query("
			SELECT " . DB_PREFIX . "features.*, " . DB_PREFIX . "goods_features.*
			FROM " . DB_PREFIX . "features NATURAL JOIN " . DB_PREFIX . "goods_features
			WHERE ID_GOOD = $good_id and feature_deleted=0 and feature_enable=1
		");
	while ($line = mysql_fetch_array($res, MYSQL_ASSOC)) {

		if (
			$database->getArrayOfQuery("
					SELECT " . DB_PREFIX . "rubric_features.ID_RUBRIC
					FROM " . DB_PREFIX . "rubric NATURAL JOIN " . DB_PREFIX . "rubric_features
					WHERE rubric_type=$type and ID_FEATURE=$line[ID_FEATURE] and ID_RUBRIC=$rubric_id
				")
		) {

			$good = (int) $line['goodfeature_value'];
			switch ($line['feature_type']) {
				case 4:
					list($line1) = $database->getArrayOfQuery("SELECT featuredirectory_text FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE_DIRECTORY=" . (int) $good);
					$s1 = $line1;
					break;
				case 5:
					$s1 = getFeatures($good);
					break;
				case 1:
					$s1 = $good;
					break;
				case 2:
					$s1 = $line['goodfeature_value'];
					break;
				case 3:
					$s1 = ($good == 1) ? "да" : "нет";
					break;
			}
			/*
			  if(!empty($s1)){
			  $s .= "<tr>";
			  $s .= "<td>".$line['feature_text']."</td>";
			  $s .= "<td>".$s1."</td>";
			  $s .= "</tr>";
			  }
			 */
			if (!empty($s1)) {
				$s .= "<div><b>" . $line['feature_text'] . "</b>: " . $s1 . "</div>";
			}
		}
	}

	/* $s .= "</table>"; */
	$s .= "</ul>";

	return $s;
}

// ф-я подсчёта товаров
function getCountGoods($id, $include = false /* системный параметр */, $first = true) {
	global $database;

	$prequery = "SELECT cprice_goods.ID_GOOD FROM cprice_goods NATURAL JOIN cprice_rubric_goods WHERE rubricgood_deleted=0 and good_deleted=0 and good_visible=1 and ID_RUBRIC=";
	$i = 0;
	if ($first) {
		$res1 = $database->query($prequery . $id);
		$i += mysql_num_rows($res1);
	}

	$res = $database->query("SELECT * FROM cprice_rubric WHERE rubric_deleted=0 and rubric_visible=1 and rubric_parent=" . $id);
	while ($line = mysql_fetch_array($res, MYSQL_ASSOC)) {
		$res1 = $database->query($prequery . $line['ID_RUBRIC']);
		$i += mysql_num_rows($res1);
		$i += getCountGoods($line['ID_RUBRIC'], $include, false);
	}
	return $i;
}

function getFeaturesText($good_id, $feature_id = 0, $ok = true) {
	global $database;


	if ($ok) {
		$type = 5;
		$good = $good_id;
	} else {
		$type = $database->getArrayOfQuery("SELECT feature_type FROM " . DB_PREFIX . "features WHERE ID_FEATURE=" . $feature_id);
		$type = $type[0];
		$good = $database->getArrayOfQuery("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=" . $good_id . " and ID_FEATURE='" . $feature_id . "'");
		$good = $good[0];
	}

	switch ($type) {
		case 4:
			$line1 = $database->getArrayOfQuery("SELECT featuredirectory_text FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE_DIRECTORY='" . (int) $good . "'");
			return $line1[0];
			break;
		case 5:
			$res1 = $database->query("SELECT " . DB_PREFIX . "goods_features.ID_FEATURE FROM " . DB_PREFIX . "goods_features NATURAL JOIN " . DB_PREFIX . "rubric_features WHERE ID_GOOD=" . (int) $good . " GROUP BY " . DB_PREFIX . "goods_features.ID_FEATURE ORDER BY rubricfeature_ls_man DESC, rubricfeature_pos ASC");
			$answertext = "";
			while ($line1 = mysql_fetch_array($res1)) {
				$answertext1 = getFeatureText($good, $line1[0]);
				if (!empty($answertext1))
					$answertext .= $answertext1 . ", ";

				// $answertext;
				// if($ok) return $answertext;
			}
			$answertext = substr($answertext, 0, -2);
			return $answertext;
			break;
		case 1:
		case 2:
		case 3:
			return $good;
			break;
	}
}

/**
 * Генератор наименования рубрики для вывода
 * в заголовок в виде пути-ссылок
 * @param type $id1 - ид начальной рубрики, отсчет с которой начинается при генерации пути
 * @param type $parents - учитывать родительские рубрики
 * @param type $html - генерировать ссылки
 * @param type $k - заключать в блок <p>
 * @param type $url - начальный текст или ссылка, который добавляется принудительно вне зависимости от наличия родителя
 * @return type
 */
function getRubricName($id1, $parents = true, $html = true, $k = true, $url = '') {
	global $database, $page_arr;

	global $type;
	$s = "";

	if ($id1 != 0) {

		$lastid = 0;
		$id = $id1;
		$i = 0;
		$grurl = '';
		while (1) {
			$line = $database->getArrayOfQuery("SELECT * FROM " . DB_PREFIX . "rubric WHERE ID_RUBRIC=" . $id1, MYSQL_ASSOC);
			$lastid = (int) $line['ID_RUBRIC'];
			if ($i == 0)
				$grurl = $url . "/" . $line['rubric_textid'] . "/";
			if ($lastid == 0)
				break;

			if ($parents && $html && ($id != $lastid || !empty($_GET['good_id']) || !empty($_GET['grad']))) {
				$pre = "<a href='" . $url . "/" . $line['rubric_textid'] . "/'>";
				$suf = "</a>";
			} else {
				$pre = $suf = "";
			}
			$s = " / " . $pre . $line['rubric_name'] . $suf . $s;
			$id1 = (int) $line['rubric_parent'];

			if (!$parents)
				break;
			$i++;
		}
		$s = substr($s, 2);
		if (isset($_GET['grad'])) {//Для градаций
			$n = count($_GET['grad']);
			$i = 1;
			foreach ($_GET['grad'] as $fid => $fval) {
				list($grname) = $database->getArrayOfQuery("SELECT featuredirectory_text FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE_DIRECTORY=" . $fval);
				if ($i == $n)
					$s .= " / " . $grname;
				else {
					$s .= " / <a href='" . $grurl . "grp" . $fid . "_" . $fval . "/'>" . $grname . "</a>";
					$grurl .= "grp" . $fid . "_" . $fval . "/";
				}
				$i++;
			}
		}
	}

	return ($k) ? "<p>" . $s . "</p>" : $s;
}

function getRubricParent($rid) {
	global $database;
	list($rid) = $database->getArrayOfQuery("SELECT rubric_parent FROM " . DB_PREFIX . "rubric WHERE ID_RUBRIC=" . $rid);
	return $rid;
}

//возвращает дочерние рубрики в формате ID_RUBRIC => rubric_textid
//если $isCount = true то возвращает количество дочерних рубрик
//добавил 20/12/12 Ким
function getChildRubric($rubric_id, $isCount = false) {
	global $database;
	$cnt = 0;
	$result = $database->query("SELECT ID_RUBRIC,rubric_textid FROM " . DB_PREFIX . "rubric WHERE rubric_parent=$rubric_id");
	$childs = array();
	while (list($child_id, $child_textid) = mysql_fetch_array($result)) {
		$childs[$child_id] = $child_textid;
		$cnt++;
	}
	if ($isCount)
		return $cnt;
	else
		return $childs;
}

function union($string) {
	$ii = 0;
	while ($i = strpos($string, ",")) {
		$string = substr($string, $i + 1);
		$ii++;
	}
	return ($ii > 0) ? $string : "";
}

function withoutunion($string) {
	$u = union($string);
	return (!empty($u)) ? substr($string, 0, strpos($string, $u) - 1) : $string;
}

/**
 * Поиск пользователя в рубрике
 * @param $rubric_id - ИД рубрики
 * @param $features - массив характеристик для поиска вида array( login_feature => login, password_feature => password )
 * @return array|bool
 */
function search_simple($rubric_id, $features) {
	global $database;
	if ($rubric_id > 0 && count($features) > 0) {
		$n = 0;
		$query_filter = $separator = '';
		foreach ($features as $feature_id => $feature_value) {
			$query_filter .= $separator . '(ID_FEATURE=' . $feature_id . ' && goodfeature_value="' . $feature_value . '")';
			$separator = ' || ';
			$n++;
		}
		$goods = $database->getColumnOfQuery('select * from (
				select ID_GOOD, count(ID_GOOD) as cnt
				from cprice_goods natural join cprice_rubric_goods natural join cprice_goods_features
				where ID_RUBRIC="' . $rubric_id . '" && rubricgood_deleted=0 && good_visible=1 && good_deleted=0 && (' . $query_filter . ')
				group by ID_GOOD
			) as tbl where cnt=' . $n);

		return $goods;
	}
	return false;
}

//Поиск записей с наличием $value в рубриках $rubs, в характрестиках $features
//Возвращает массив ID_GOOD, good_url, ID_RUBRIC
function search_goods($value, $rubs, $features = array(), $bool = true) {
	global $database;
	$data = array();
	foreach ($rubs as $rubric_id) {
		$features2 = array();
		if (count($features) == 0) {
			$res_feat = $database->query("
				SELECT ID_FEATURE, feature_type
				FROM " . DB_PREFIX . "features NATURAL JOIN " . DB_PREFIX . "rubric_features
				WHERE ID_RUBRIC=" . $rubric_id . " and feature_deleted=0
				ORDER BY rubricfeature_pos
			");
			while (list($feature_id, $ftype) = mysql_fetch_array($res_feat)) {
				$features2[$feature_id] = $ftype;
			}
		} else
			$features2 = $features;

		$res = $database->query("
			SELECT ID_GOOD, good_url
			FROM " . DB_PREFIX . "rubric_goods NATURAL JOIN " . DB_PREFIX . "goods
			WHERE ID_RUBRIC=" . $rubric_id . " and good_deleted=0 and good_visible=1"
		);
		while (list($good_id, $url) = mysql_fetch_array($res)) {
			foreach ($features2 as $feature_id => $ftype) {
				if (search_fval($value, $good_id, $feature_id, $ftype)) {
					if ($bool)
						return true;
					$data[$good_id] = array($url . "/", $rubric_id);
					break;
				}
			}
		}
	}
	if ($bool)
		return false;
	else
		return $data;
}

//поиск $value в записи $good_id в характеристике $feature_id типа $ftype
//возвращает true в случае успеха
function search_fval($value, $good_id, $feature_id, $ftype) {
	global $database;
	switch ($ftype) {
		case 1://Число
		case 2://Текст
		case 6://файл
		case 8://Дата
			$res = $database->query("SELECT ID_GOOD FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD='" . $good_id . "' and ID_FEATURE='" . $feature_id . "' and goodfeature_value like '" . $value . "'");
			if (mysql_num_rows($res) > 0)
				return true;
			break;
		case 3://логический
			list($val) = $database->getArrayOfQuery("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD='" . $good_id . "' and ID_FEATURE='" . $feature_id . "'");
			if ($val > 0) {
				$res = $database->query("SELECT ID_FEATURE FROM " . DB_PREFIX . "features WHERE ID_FEATURE='" . $feature_id . "' && feature_text like '" . $value . "'");
				if (mysql_num_rows($res) > 0)
					return true;
			}
			break;
		case 4://справочник
			$res1 = $database->query("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD='" . $good_id . "' and ID_FEATURE='" . $feature_id . "'");
			while (list($val) = mysql_fetch_array($res1)) {
				$res = $database->query("SELECT ID_FEATURE_DIRECTORY FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE_DIRECTORY='" . $val . "' && featuredirectory_text like '" . $value . "'");
				if (mysql_num_rows($res) > 0)
					return true;
			}
			break;
		case 7://Большой текст
			list($val) = $database->getArrayOfQuery("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD='" . $good_id . "' and ID_FEATURE='" . $feature_id . "'");
			if ($val > 0) {
				$res = $database->query("SELECT ID_TEXT FROM " . DB_PREFIX . "texts WHERE ID_TEXT=$val && text_text like '" . $value . "'");
				if (mysql_num_rows($res) > 0)
					return true;
			}
			break;
		case 9://Справочник рубрик
			$res1 = $database->query("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD='" . $good_id . "' and ID_FEATURE='" . $feature_id . "'");
			while (list($val) = mysql_fetch_array($res1)) {
				$res = $database->query("SELECT ID_RUBRIC FROM " . DB_PREFIX . "rubric WHERE ID_RUBRIC='" . $val . "' && rubric_name like '" . $value . "'");
				if (mysql_num_rows($res) > 0)
					return true;
			}
			break;
	}
	return false;
}

// взять данные из характеристики товара
// если $arr = true, то возвращать в виде массива (для мульти-характеристик)
function getFeatureValue($good_id, $feature_id, $arr = false) {
	global $database;
	if ($arr) {
		$out = array();
		$res = $database->query("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD='" . $good_id . "' and ID_FEATURE='" . $feature_id . "'");
		while ($row = mysql_fetch_row($res))
			$out[] = $row[0];
		return $out;
	}
	list($val) = $database->getArrayOfQuery("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD='" . $good_id . "' and ID_FEATURE='" . $feature_id . "'");
	return $val;
}

// генератор данных первой характеристики
function getFirstFeatureText($rubric_id, $good_id) {
	global $database;
	list($feat_id) = $database->getArrayOfQuery("
		SELECT " . DB_PREFIX . "features.ID_FEATURE
		FROM " . DB_PREFIX . "features NATURAL JOIN " . DB_PREFIX . "rubric_features
		WHERE ID_RUBRIC=" . $rubric_id . " and feature_deleted=0
		ORDER BY rubricfeature_pos, ID_FEATURE
		LIMIT 1
	");
	return getFeatureText($good_id, $feat_id);
}

// взять человекопонятные данные из характеристики товара
function getFeatureText($good_id, $feature_id, $ok = false, $long = false, $subs = true) {
	global $database;


	if ($ok) {
		$type = 5;
		$good = $good_id;
	} else {
		list($type, $feat_name) = $database->getArrayOfQuery("SELECT feature_type,feature_text FROM " . DB_PREFIX . "features WHERE ID_FEATURE=" . $feature_id);
		$good = getFeatureValue($good_id, $feature_id);
	}


	switch ($type) {
		case 4:
			$line1 = $database->getArrayOfQuery("SELECT featuredirectory_text FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE_DIRECTORY=" . (int) $good);
			return $line1[0];
			break;
		case 5:
			//$res1 = $database -> query("SELECT ".DB_PREFIX."goods_features.ID_FEATURE FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE ID_GOOD=".(int)$good." GROUP BY ".DB_PREFIX."goods_features.ID_FEATURE and rubricfeature_ls_man=1 ORDER BY rubricfeature_pos ASC");
			//if(mysql_num_rows($res1)==0){
			$res1 = $database->query("SELECT " . DB_PREFIX . "goods_features.ID_FEATURE FROM " . DB_PREFIX . "goods_features NATURAL JOIN " . DB_PREFIX . "rubric_features WHERE ID_GOOD=" . (int) $good . " GROUP BY " . DB_PREFIX . "goods_features.ID_FEATURE ORDER BY rubricfeature_pos ASC");
			//}

			$answertext = "";
			while ($line1 = mysql_fetch_array($res1)) {
				$answertext1 = getFeatureText($good, $line1[0]);
				if (!empty($answertext1)) {
					//return $answertext1;
					$answertext .= $answertext1 . ", ";
				}

				$answertext;
				// if($ok) return $answertext;
			}
			$answertext = substr($answertext, 0, -2);
			return $answertext;
			break;
		default:
			return $good;
			break;
		case 7:
			list($good) = $database->getArrayOfQuery("SELECT text_text FROM cprice_texts WHERE ID_TEXT = " . (int) $good);
			if ($long) {
				return $good;
			} else
				return substr(strip_tags($good), 0, 100) . '...';
			break;
		case 3:
			return ($good) ? $feat_name : false;
			break;
		case 9:
			return getRubricName($good, false, false, false);
			break;
	}
}
