<?php

/*
 * Стандартные функции SPRUT
 * разработка Эобард Тоун
 * 
 */
$arr = wp_upload_dir();
define("DATA_FLD", $arr['basedir'] . '/');
define("URLDATA_FLD", $arr['baseurl'] . '/');
add_shortcode('drevo_getdata', 'drevo_getdata');
add_shortcode('drevo_form', 'drevo_form');

//[drevo_newslist rid=4 tpl=specialisti]   specialisti - шаблон должен лежать в /template/drevo/drevo-specialisti.php
/*
<?php
global $data;
?>
<div class="row main-catalog">
<?php
$i=0;
foreach ($data as $arr):?>
       <a class="col-md-4 cat_item" href="<?=$arr[2]?>">
	   <img alt="Изображение" src="<?=  str_replace('trumb_', '', $arr['images'][0]['full_path'])?>">
	   <br><?=$arr[1]?>
       </a>     
<?php endforeach;?>
</div>	
*/
add_shortcode('drevo_newslist', 'drevo_newslist');

require_once 'functions/form.php';
require_once 'functions/newslist.php';









/*
 * Базовые функции
 rid = z
 */
function drevo_getdata($arg) {
	/*
	 * По умолчанию Последние записи идут первыми
	 */
	$orderby = 'ID_GOOD DESC';
	if ($arg['orderby'] != '') {
		$orderby = '';
	}
	/*
	 * Харакетристики текущщей рубрики для вывода.
	 */
	$features = array();
	if ($arg['features'] != '') {
		if (is_array($arg['features'])) {
			$features = $arg['features'];
		} else {
			$features = explode(',', $arg['features']);
		}
	}
	/*
	 * Количество записей, по умолчанию 20
	 */
	if ($arg['limit'] == NULL) {
		$arg['limit'] = 20;
	}
	$data = getData(
		$arg['rid'], $orderby, $arg['limit'], $features, $arg['fvalues'] ? $arg['fvalues'] : false, $arg['uslovia'] ? $arg['uslovia'] : array()
	);
	return $data;
	//$data = getData($arg['rid']);
	//echo '<pre>';
	///print_r($data);
	//echo '</pre>';
	//echo 'deen_rubric_data';
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
	global $wpdb;
	$data = array();
	/*
	 * Если не заданы характеристики  то вытящиваем все.
	 */
	if (count($features) == 0) {
		$res_feat = $wpdb->get_results("SELECT ID_FEATURE FROM " . DB_PREFIX . "features NATURAL JOIN " . DB_PREFIX . "rubric_features WHERE ID_RUBRIC=" . $rubric_id . " and feature_deleted=0 ORDER BY rubricfeature_pos");
		foreach ($res_feat as $obj) {
			$features[] = $obj->ID_FEATURE;
		}
	}
	$add_tbl = '';
	$add_sql = '';
	if (count($uslovia) > 1) {
		$n = count($uslovia);
		foreach ($uslovia as $fid => $fval) {
		if($fid!='ID_GOOD'){
			$add_sql .= " || (ID_FEATURE=" . $fid . " && goodfeature_value='" . $fval . "')";
		}
		}
		$qstr = "select * from (select ID_GOOD, good_url, good_visible, count(ID_GOOD) as cnt, rubricgood_pos from cprice_goods natural join cprice_rubric_goods natural join cprice_goods_features
			where ID_RUBRIC='$rubric_id' && rubricgood_deleted=0 " . ($visible ? " && good_visible=1" : "") . " && good_deleted=0 && (" . substr($add_sql, 4) . ") group by ID_GOOD) as tbl where cnt=" . $n;
			
		if(isset($uslovia['ID_GOOD'])){
		   $add_sql.= ' ID_GOOD IN ( '.implode(',',$uslovia['ID_GOOD']).')';
		
		}
	} else {

		if (count($uslovia) > 0) {
			$add_tbl = 'natural join cprice_goods_features';
			$add_sql = "";
			foreach ($uslovia as $fid => $fval) {
			if($fid!='ID_GOOD')
				$add_sql .= "(ID_FEATURE='" . $fid . "' && goodfeature_value='" . $fval . "') || ";
			}
			if($add_sql!=''){
			//$add_sql =;
			$add_sql = " && (".substr($add_sql, 0, -4) . ")";
			}
			if(isset($uslovia['ID_GOOD'])){
		        $add_sql.= ' AND  ID_GOOD IN ( '.implode(',',$uslovia['ID_GOOD']).')';
		
		    }
		}
		$qstr = "
				SELECT ID_GOOD,good_url,good_visible
				FROM " . DB_PREFIX . "rubric_goods NATURAL JOIN " . DB_PREFIX . "goods $add_tbl
				WHERE ID_RUBRIC=" . $rubric_id . " and good_deleted=0" . ($visible ? " and good_visible=1" : "") . $add_sql
		;
	}
	
	$qstr .= (empty($orderby) ? " ORDER BY rubricgood_pos, ID_GOOD" : " ORDER BY " . $orderby)
		. (empty($limit) ? "" : " LIMIT " . $limit);
		//echo $qstr;
	$result = $wpdb->get_results($qstr);
	//print_r($result);
	foreach ($result as $obj) {
		$data[$obj->ID_GOOD]['images'] = Drevo_getImages($obj->ID_GOOD, 100, "trumb_", true);
		$data[$obj->ID_GOOD]['url'] = $obj->good_url;
		$data[$obj->ID_GOOD]['visible'] = $obj->good_visible;
		foreach ($features as $feature_id) {
			if ($feature_id > 0) {
				if ($fvalues) {
					$good_obj = $wpdb->get_results("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=" . $obj->ID_GOOD . " and ID_FEATURE='" . $feature_id . "' limit 1");
					$data[$obj->ID_GOOD][$feature_id] = $good_obj->goodfeature_value;
				} else {
					$data[$obj->ID_GOOD][$feature_id] = getFeatData($feature_id, $obj->ID_GOOD);
				}
			}
		}
	}

	return $data;
}

function Drevo_getImages($id, $num = 1, $pre = "trumb_", $returnarray = false) {
	global $wpdb;
	$arr = array();
	$result = $wpdb->get_results("SELECT * FROM " . DB_PREFIX . "goods_photos
			WHERE ID_GOOD = '$id' and goodphoto_visible=1 and goodphoto_deleted=0
			ORDER BY goodphoto_pos LIMIT $num");
	foreach ($result as $obj) {
		//print_r($obj->goodphoto_file);
		if (!empty($obj->goodphoto_file)) {
			//echo DATA_FLD . "good_photo/" . $pre . "" . $obj->goodphoto_file;
			$resfeat1 = URLDATA_FLD . "good_photo/" . $pre . "" . $obj->goodphoto_file . "";
			if (file_exists(DATA_FLD . "good_photo/" . $pre . "" . $obj->goodphoto_file)) {
				if ($num == 1) {
					if ($returnarray) {
						return array(
							'full_path' => $resfeat1,
							'id' => $obj->ID_GOOD_PHOTO,
							'path' => "good_photo/" . $pre . "" . $obj->goodphoto_file . "",
							'alt' => $obj->goodphoto_alt,
							'file_name' => $obj->goodphoto_file);
					} else {
						return $resfeat1;
					}
				} else {
					if ($returnarray) {
						$arr[] = array(
							'full_path' => $resfeat1,
							'path' => "good_photo/" . $pre . "" . $obj->goodphoto_file . "",
							'id' => $obj->ID_GOOD_PHOTO,
							'alt' => $obj->goodphoto_alt,
							'file_name' => $obj->goodphoto_file
						);
					} else {
						$arr[$obj->ID_GOOD_PHOTO] = $resfeat1;
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

function getFeatData($feat_id, $good_id, $ppl = true) {
	global $wpdb;
	$obj = $wpdb->get_row("
			SELECT " . DB_PREFIX . "features.feature_type, " . DB_PREFIX . "goods_features.goodfeature_value
			FROM " . DB_PREFIX . "goods_features NATURAL JOIN " . DB_PREFIX . "features
			WHERE " . DB_PREFIX . "goods_features.ID_FEATURE = '" . $feat_id . "' and ID_GOOD = '" . $good_id . "'
		");
	$restype = $obj->feature_type;
	$resfeat = $obj->goodfeature_value;
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
			$resfeat = $wpdb->get_row("SELECT featuredirectory_text FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE_DIRECTORY = '" . $resfeat . "'")->featuredirectory_text;
			break;
		case 7:
			$resfeat = $wpdb->get_row("
					SELECT text_text
					FROM " . DB_PREFIX . "texts
					WHERE ID_TEXT = '" . (int) $resfeat . "'
				")->text_text;
			break;
		case 9:
			return getRubricName($resfeat, false, false, false);
			break;
	}
	return $resfeat;
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

function Drevo_insertData($rubric_id, $data, $visible = 1) {
	global $wpdb;
	if ($rubric_id > 0 && is_array($data)) {
		$wpdb->query("INSERT INTO " . DB_PREFIX . "goods (good_visible,good_deleted) VALUES ($visible,0)");
		$id = $wpdb->insert_id;
		//echo $id;
		$wpdb->query("INSERT INTO " . DB_PREFIX . "rubric_goods (ID_RUBRIC,ID_GOOD) VALUES (" . $rubric_id . ",$id)");
		foreach ($data as $fid => $value) {
			if (is_array($value)) {
				foreach ($value as $item) {
					Drevo_setFeatData($fid, $id, $item);
				}
			} else {
				Drevo_setFeatData($fid, $id, $value);
			}
		}
		return $id;
	} else
		return -1;
}

function Drevo_setFeatData($feat_id, $good_id, $value, $update = false) {
	global $wpdb;
	$obj = $wpdb->get_row("SELECT feature_type FROM " . DB_PREFIX . "features WHERE ID_FEATURE = '" . $feat_id . "'");
	$type = $obj->feature_type;
	if ($update) {
		$res = $wpdb->query("SELECT goodfeature_value,ID_GOOD_FEATURE FROM " . DB_PREFIX . "goods_features WHERE ID_FEATURE = '" . $feat_id . "' and ID_GOOD = '" . $good_id . "'");
		$n = mysql_num_rows($res);
		if ($n == 0) {
			$wpdb->query("INSERT INTO " . DB_PREFIX . "goods_features (ID_FEATURE,ID_GOOD,goodfeature_value) VALUES ('" . $feat_id . "','" . $good_id . "','" . $value . "')");
			return true;
		}
		if ($n > 1)
			return false;
		$row = mysql_fetch_row($res);
		$value_old = $row[0];
		$value_old_id = $row[1];
		if ($type == 7) {
			list($value_old_txt) = $wpdb->getArrayOfQuery("
					SELECT text_text
					FROM " . DB_PREFIX . "texts
					WHERE ID_TEXT = '" . (int) $value_old . "'
				");
			if ($value_old_txt != $value) {
				$wpdb->query("UPDATE " . DB_PREFIX . "texts SET text_text='$value' WHERE ID_TEXT=" . $value_old);
			}
		} elseif ($value_old != $value)
			$wpdb->query("UPDATE " . DB_PREFIX . "goods_features SET goodfeature_value='" . $value . "' WHERE ID_GOOD_FEATURE='" . $value_old_id . "'");
	} else {
		switch ($type) {
			case 7:
				$wpdb->query("INSERT INTO " . DB_PREFIX . "texts (text_text) VALUES('$value')");
				$value = $wpdb->insert_id;
				break;
		}
		$wpdb->query("INSERT INTO " . DB_PREFIX . "goods_features (ID_FEATURE,ID_GOOD,goodfeature_value) VALUES ('" . $feat_id . "','" . $good_id . "','" . $value . "')");
	}
	return true;
}


function drevo_cutText($string,$len = 200){
//Первым делом, уберём все html элементы:
$string = strip_tags($string);
//Теперь обрежем его на определённое количество символов:
$string = substr($string, 0, $len);
//Затем убедимся, что текст не заканчивается восклицательным знаком, запятой, точкой или тире:
$string = rtrim($string, "!,.-");
//Напоследок находим последний пробел, устраняем его и ставим троеточие:
$string = substr($string, 0, strrpos($string, ' '));
return $string."… ";
	
	
}