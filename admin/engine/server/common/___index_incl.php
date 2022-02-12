<?

if (!isset($_status))
{
	header("HTTP/1.0 404 Not Found");
	die;
}
/* * ********
 *  ООО "Универсал-Сервис"
 *
 *  Разработчик:  Teлeнкoв Д.С.
 *  e-mail: tdsweb@ya.ru
 *  ICQ: 398-518-940
 *  Тел.: +7 909 3481503
 * ******** */

/* * ***
 * TDSCMS v1.0
 * tdscms@mail.ru
 * *** */

// чтоб рандомы были более разнообразные :)
function teSrand()
{
	list($usec, $sec) = explode(' ', microtime());
	srand((float) $sec + ((float) $usec * 100000));
}

teSrand();

// убирает всё, что до первой запятой в строке
function union($string)
{
	$ii = 0;
	while ($i = strpos($string, ","))
	{
		$string = substr($string, $i + 1);
		$ii++;
	}
	return ($ii > 0) ? $string : "";
}

// убирает всё, что после первой запятой в строке
function withoutunion($string)
{
	$u = union($string);
	return (!empty($u)) ? substr($string, 0, strpos($string, $u) - 1) : $string;
}

// генератор наименования рубрики (для вывода в заголовок)
function getRubricName($id1, $parents = true, $html = true, $k = true, $lang = false, $page = '')
{
	global $database;

	if ($id1 == 0)
	{
		global $type;
		global $hosts;
		if ($hosts[DB_ID]['version'] == 1)
		{
			$s = ($type == 1) ? "товар" : "услуга";
		}
		else
		{
			list($s) = $database->getArrayOfQuery("SELECT rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE ID_RUBRIC_TYPE=".(int) $type);
		}
	}
	else
	{

		$s = "";
		$lastid = 0;
		$i = 0;
		while (1)
		{
			$line = $database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$id1, MYSQL_ASSOC);
			$type = (int) $line['rubric_type'];
			$lastid = (int) $line['ID_RUBRIC'];
			$goods = true;
			if ($database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=".$id1, MYSQL_ASSOC))
			{
				$goods = false;
			}

			if ($lastid == 0)
				break;

			if ($html)
			{
				if ($goods)
				{
					$pre = "<a href='".teGetUrlQuery(empty($page)?"=goods".($lang ? '2' : ''):'='.$page."&showid=$lastid", "type=$type", "rubric_id=$lastid", 'action=')."'>";
				}
				else
				{
					$pre = "<a href='".teGetUrlQuery(empty($page)?"=rubric".($lang ? '2' : ''):'='.$page, "type=$type", "rubric_id=$lastid", "showid=$lastid", 'action=')."'>";
				}
				$suf = "</a>";
			}
			else
			{
				$pre = $suf = "";
			}

			$s = "/".$pre.$line['rubric_name'].$suf.$s;
			$id1 = (int) $line['rubric_parent'];

			if (!$parents)
				break;
			$i++;
		}

		$s = substr($s, 1);
	}

	return (($k) ? "«" : "").$s.(($k) ? "»" : "");
}

// генератор наименования характеристики
function getFeatureName($id)
{
	global $database;
	list($line) = $database->getArrayOfQuery("SELECT feature_text FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$id."");
	return $line;
}

// генератор типа характеристики
function getFeatureType($id)
{
	global $database;
	list($line) = $database->getArrayOfQuery("SELECT feature_type FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$id."");
	return $line;
}

// записать данные в характеристику товара
function setFeatureValue($good_id, $feature_id, $value)
{
	global $database;
	if ($database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='$value' WHERE ID_GOOD='".$good_id."' and ID_FEATURE = '".$feature_id."'"))
	{
		return true;
	}
}

// взять данные из характеристики товара
// если $arr = true, то возвращать в виде массива (для мульти-характеристик)
function getFeatureValue($good_id, $feature_id, $arr = false)
{
	global $database;
	if ($arr)
	{
		$out = array();
		$res = $database->query("SELECT goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_GOOD='".$good_id."' and ID_FEATURE='".$feature_id."'");
		while ($row = mysql_fetch_row($res))
			$out[] = $row[0];
		return $out;
	}
	list($val) = $database->getArrayOfQuery("SELECT goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_GOOD='".$good_id."' and ID_FEATURE='".$feature_id."'");
	return $val;
}

// генератор данных первой характеристики
function getFirstFeatureText($rubric_id, $good_id)
{
	global $database;
	list($feat_id) = $database->getArrayOfQuery("
		SELECT ".DB_PREFIX."features.ID_FEATURE
		FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
		WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0
		ORDER BY rubricfeature_pos, ID_FEATURE
		LIMIT 1
	");
	return getFeatureText($good_id, $feat_id);
}

// взять человекопонятные данные из характеристики товара
function getFeatureText($good_id, $feature_id, $ok = false, $long = false, $subs = true)
{
	global $database;

	if ($ok)
	{
		$type = 5;
		$good = $good_id;
	}
	else
	{
		list($type, $feat_name) = $database->getArrayOfQuery("SELECT feature_type,feature_text FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$feature_id);
		$good = getFeatureValue($good_id, $feature_id);
	}

	switch ($type)
	{
		case 4:
			$line1 = $database->getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".(int) $good);
			return $line1[0];
			break;
		case 5:
			//$res1 = $database -> query("SELECT ".DB_PREFIX."goods_features.ID_FEATURE FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE ID_GOOD=".(int)$good." GROUP BY ".DB_PREFIX."goods_features.ID_FEATURE and rubricfeature_ls_man=1 ORDER BY rubricfeature_pos ASC");
			//if(mysql_num_rows($res1)==0){
			$res1 = $database->query("SELECT ".DB_PREFIX."goods_features.ID_FEATURE FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE ID_GOOD=".(int) $good." GROUP BY ".DB_PREFIX."goods_features.ID_FEATURE ORDER BY rubricfeature_pos ASC");
			//}

			$answertext = "";
			while ($line1 = mysql_fetch_array($res1))
			{
				$answertext1 = getFeatureText($good, $line1[0]);
				if (!empty($answertext1))
				{
					//return $answertext1;
					$answertext .= $answertext1.", ";
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
			list($good) = $database->getArrayOfQuery("SELECT text_text FROM cprice_texts WHERE ID_TEXT = ".(int) $good);
			if ($long)
			{
				return $good;
			}
			else
				return substr(strip_tags($good), 0, 100).'...';
			break;
		case 3:
			return ($good) ? $feat_name : false;
			break;
		case 9:
			return getRubricName($good, false, false, false);
			break;
	}
}

function getFeatureText2($good_id, $feature_id, $type = 0, $change_id = 0)
{
	global $database;
	if (!$type)
	{
		list($type) = $database->getArrayOfQuery("SELECT feature_type FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$feature_id);
	}

	if ($change_id > 0)
	{
		list($changes) = $database->getArrayOfQuery("SELECT old_values FROM cprice_changes WHERE change_table='cprice_goods_features' && old_values like '".$good_id."$|$".$feature_id."$|$%' &&  ID_GOOD = ".(int) $change_id);
		if (!empty($changes))
		{
			$arr_val = explode("$|$", $changes);
			$good = $arr_val[2];
		}
		else
			$good = getFeatureValue($good_id, $feature_id);
	}
	else
		$good = getFeatureValue($good_id, $feature_id);

	switch ($type)
	{
		case 4:
			$line1 = $database->getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".(int) $good);
			return $line1[0];
			break;
		case 5:
			$res1 = $database->query("SELECT ".DB_PREFIX."goods_features.ID_FEATURE FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE ID_GOOD=".(int) $good." GROUP BY ".DB_PREFIX."goods_features.ID_FEATURE ORDER BY rubricfeature_pos ASC");
			$answertext = "";
			while ($line1 = mysql_fetch_array($res1))
			{
				$answertext1 = getFeatureText($good, $line1[0]);
				if (!empty($answertext1))
				{
					$answertext .= $answertext1.", ";
				}

				$answertext;
			}
			$answertext = substr($answertext, 0, -2);
			return $answertext;
			break;
		default:
			return $good;
			break;
		case 7:
			list($good) = $database->getArrayOfQuery("SELECT text_text FROM cprice_texts WHERE ID_TEXT = ".(int) $good);
			if ($change_id > 0)
			{
				list($changes, $id_chng) = $database->getArrayOfQuery("SELECT old_values, ID_CHANGE FROM cprice_changes WHERE change_table='cprice_texts' && ID_GOOD = ".(int) $change_id);
				if ($id_chng)
					$good = $changes;
			}
			if (strlen($good) > 128)
			{
				$text = strip_tags($good);
				$good = substr($text, 0, 100).'<br /><a href="#" onclick="document.getElementById(\'txt'.$change_id.'\').style.display=\'\'">весь текст</a>'.'<div id="txt'.$change_id.'" style="display:none;">'.$good.'</div>';
			}
			return $good;
			break;
		case 3:
			return ($good) ? 'да' : 'нет';
			break;
		case 9:
			return getRubricName($good, false, false, false);
			break;
	}
}

// ф-я подсчёта количества товаров в рубрике
$counts = array();
$count_goods = array();

function getCountGoods($id)
{
	global $database, $counts, $count_goods;

	$i = 0;
	if (isset($counts[$id]))
		return $counts[$id];
	else
	{
		$res = $database->query("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=".$id);
		if (mysql_num_rows($res) == 0)
		{
			list($n) = $database->getArrayOfQuery("SELECT count(".DB_PREFIX."goods.ID_GOOD) FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE rubricgood_deleted=0 and good_deleted=0 and ID_RUBRIC=".$id.(@$_GET['disable']>0?' and good_visible=0':''));
			if ($n > 0)
				$count_goods[$id] = $n;
			$i += $n;
		}
		else
		{
			while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				if (checkAccess($line['ID_RUBRIC']))
				{
					$i += getCountGoods($line['ID_RUBRIC']);
				}
			}
		}
		$counts[$id] = $i;
	}
	return $i;
}

// запрос на кол-во хар-к
function getCountFeatures($id)
{
	global $database;
	global $type;
	if (!empty($type))
		$S = " rubric_type=".$type." and";
	else
		$S = "";
	$res_cnt = $database->getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."rubric_features WHERE$S ID_RUBRIC=".$id);
	return (int) $res_cnt[0];
}

// количество подрубрик в рубрике
function getCountRubricChild($id)
{
	global $database;
	$res_cnt = $database->query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=".$id);
	return mysql_num_rows($res_cnt);
}

// ф-я берет хар-ки рубрики и сует во всех её детей эти хар-ки (для порядка и удобства пользования)
function addFeature($rubric_type, $rubric_id, $feature_id, $graduation = 0)
{
	global $database;
	if (!$database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=".$rubric_id." and rubric_type=".$rubric_type." and ID_FEATURE=".$feature_id))
	{
		$max = $database->getArrayOfQuery("SELECT max(rubricfeature_pos) FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=".$rubric_id);
		$max = $max[0] + 1;
		$database->query("INSERT INTO ".DB_PREFIX."rubric_features (ID_RUBRIC,rubric_type,ID_FEATURE,rubricfeature_pos,rubricfeature_graduation) VALUES ($rubric_id,$rubric_type,$feature_id,$max,$graduation)");
	}
	$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=".$rubric_id." and rubric_type=".$rubric_type);
	while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
	{
		addFeature($rubric_type, $line['ID_RUBRIC'], $feature_id, $graduation);
	}
}

function deleteFeature($rubric_type, $rubric_id, $feature_id)
{
	global $database;
	$database->query("DELETE FROM ".DB_PREFIX."rubric_features WHERE ID_FEATURE=".$feature_id." and ID_RUBRIC=".$rubric_id." and rubric_type=".$rubric_type);
	$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=".$rubric_id);
	while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
	{
		deleteFeature($rubric_type, $line['ID_RUBRIC'], $feature_id);
	}
}

function optimizeFeature($rubric_type, $rubric_id)
{
	global $database;
	$res = $database->query("SELECT ID_FEATURE FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=".$rubric_id." and rubric_type=".$rubric_type);
	while ($line = mysql_fetch_array($res, MYSQL_NUM))
	{
		addFeature($rubric_type, $rubric_id, $line[0]);
	}
	$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=".$rubric_id);
	while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
	{
		optimizeFeature($rubric_type, $line['ID_RUBRIC']);
	}
}

// фунцкии для проверки доступа менеджера к рубрике
// возвращает массив из булевых значений:
// m - управление, v - просмотр, a - добавление, e - изменение, d - удаление
function checkAccess($id)
{
	global $_USER;
	$fullaccess = array('m' => 1, 'v' => 1, 'a' => 1, 'e' => 1, 'd' => 1);
	if ($_USER['login'] == "root")
		return $fullaccess;
	if ($_USER['group'] < 3)
		return $fullaccess;
	if ($id == 0)
		return $fullaccess;

	global $database;

	list($type) = $database->getArrayOfQuery("SELECT rubric_type FROM cprice_rubric WHERE ID_RUBRIC=$id");

	combase();

	if ($_USER['rubric_types'][$type] == 2)
	{
		$access = $fullaccess;
	}
	else
	{
		if (!list($access['m'], $access['v'], $access['a'], $access['e'], $access['d']) = $database->getArrayOfQuery("
			SELECT access_m,access_v,access_a,access_e,access_d
			FROM ".DB_PREFIX."users_privilegies
			WHERE ID_USER=".$_USER['id']." and database_id=".DB_ID." and ID_RUBRIC=".$id."
		"))
		{
			//$access['m'] = $access['v'] = $access['a'] = $access['e'] = $access['d'] = 0;
			$access = false;
			;
		}
	}

	curbase();
	return $access;
}

// доступ, включая детей
function checkAccessChild($parent_id)
{
	global $_USER;
	if ($_USER['group'] > 2)
	{
		global $database;

		if (checkAccess($parent_id))
		{
			return true;
		}

		$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_parent=".$parent_id);
		while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
		{
			if (!checkAccess($line['ID_RUBRIC']))
			{
				if (!checkAccessChild($line['ID_RUBRIC']))
				{
					/* combase();
					  if($database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE access_type>0 and ID_RUBRIC=".$line['ID_RUBRIC']." and ID_USER=".$_USER['id'])){
					  curbase();
					  return true;
					  }
					  curbase(); */
				}
				else
					return true;
			}
			else
				return true;
		}
		return false;
	} else
	{
		return true;
	}
}

//// фильтр товаров и услуг
function filter_goods($good_id)
{
	global $database;

	if (!isset($_GET['price_b']) && !isset($_GET['price_a']))
		return true;

	// рубрика
	$querystr = "";
	// цена
	if (!empty($_GET['price_b_s']))
		$querystr .= " and good_price>=".$_GET['price_b_s'];
	if (!empty($_GET['price_a_s']))
		$querystr .= " and (good_price<=".$_GET['price_a_s']." and good_price>0)";
	if (!empty($_GET['price_b_mo']))
		$querystr .= " and good_price_mopt>=".$_GET['price_b_mo'];
	if (!empty($_GET['price_a_mo']))
		$querystr .= " and (good_price_mopt<=".$_GET['price_a_mo']." and good_price_mopt>0)";
	if (!empty($_GET['price_b_o']))
		$querystr .= " and good_price_opt>=".$_GET['price_b_o'];
	if (!empty($_GET['price_a_o']))
		$querystr .= " and (good_price_opt<=".$_GET['price_a_o']." and good_price_opt>0)";
	if (!empty($_GET['price_b_ko']))
		$querystr .= " and good_price_kopt>=".$_GET['price_b_ko'];
	if (!empty($_GET['price_a_ko']))
		$querystr .= " and (good_price_kopt<=".$_GET['price_a_ko']." and good_price_kopt>0)";
	if (!empty($_GET['price_b_d']))
		$querystr .= " and good_price_dil>=".$_GET['price_b_d'];
	if (!empty($_GET['price_a_d']))
		$querystr .= " and (good_price_dil<=".$_GET['price_a_d']." and good_price_dil>0)";

	// если ещё не отсеилось - даьше сеем.. иначе ретурн фалс.
	$res = $database->query("SELECT ".DB_PREFIX."goods.* FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE ".DB_PREFIX."goods.ID_GOOD=".$good_id." and true".$querystr);

	if (mysql_num_rows($res) <= 0)
	{
		return false;
	}
	else
	{

		$arr_rubrics = array();
		// для всех рубрик товара
		$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$good_id);
		while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
		{

			$id1 = $line['ID_RUBRIC'];

			while (1)
			{
				$line1 = $database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$id1, MYSQL_ASSOC);
				$arr_rubrics[] = $id1;
				if ($id1 == 0)
					break;
				$id1 = (int) $line1['rubric_parent'];
			}
		}
		$show = true;

		foreach ($arr_rubrics AS $iarr)
		{
			$res = $database->query("SELECT ".DB_PREFIX."features.* FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and feature_enable=1 and ID_RUBRIC=".$iarr);
			while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
			{
				$fieldname = "f".$line['ID_FEATURE'];
				switch ($line['feature_type'])
				{
					case 1:
						$querystr = "";
						if (!empty($_GET[$fieldname."b"]))
							$querystr .= " and goodfeature_value>=".$_GET[$fieldname."b"];
						if (!empty($_GET[$fieldname."a"]))
							$querystr .= " and goodfeature_value<=".$_GET[$fieldname."a"]." and goodfeature_value>0";
						if (!$database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$good_id." and ID_FEATURE=".$line['ID_FEATURE']." $querystr"))
						{
							return false;
							break;
						}
						break;
					/*
					  case 2:
					  if(!@($database -> getArrayOfQuery("SELECT * FROM goods_features WHERE ID_GOOD=".$good_id." and ID_FEATURE=".$line['ID_FEATURE']." and goodfeature_value LIKE '%".$_GET[$fieldname]."%'"))){
					  return false;
					  break;
					  }
					  break;
					 */
					case 3:
						$qs = "";
						if (@$_GET[$fieldname."y"])
							$qs .= " or goodfeature_value=1";
						if (@$_GET[$fieldname."n"])
							$qs .= " or goodfeature_value=0";

						if (!$database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$good_id." and ID_FEATURE=".$line['ID_FEATURE']." and (false ".$qs.")"))
						{
							return false;
							break;
						}

						break;
					case 4:
						$res1 = $database->query("SELECT * FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$line['ID_FEATURE']);
						while ($line1 = mysql_fetch_array($res1, MYSQL_ASSOC))
						{
							if (@$_GET[$fieldname.$line1['ID_FEATURE_DIRECTORY']])
								$on = true;
							else
								$on = false;
							if (!$on)
							{
								if ($database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$good_id." and ID_FEATURE=".$line['ID_FEATURE']." and goodfeature_value=".$line1['ID_FEATURE'].""))
								{
									return false;
									break;
								}
							}

							//$s .= "<div><input type=checkbox name='".$fieldname."[".$line1['ID_RUBRIC_ANSWER']."]' id='".$fieldname.$line1['ID_RUBRIC_ANSWER']."'><label for='".$fieldname.$line1['ID_RUBRIC_ANSWER']."'>".$line1['rubricanswer_text']."</label></div>";
						}
						break; /**/
				}
				if (!$show)
					return false;
			}
			if (!$show)
				return false;
		}

		return $show;
	}
}

// генерация новостей
function generate_news($rubric_type, $type, $url = "", $name = "", $pname = "", $lprice = "", $nprice = "")
{
	if (teGetConf("news_tmpl_en".$type) == 1)
	{
		global $database;

		$line = $database->getArrayOfQuery("SELECT min(goodnewtemplate_usenum) FROM ".DB_PREFIX."goods_news_templates WHERE goodnewtemplate_deleted=0 and goodnewtemplate_priority=1 and goodnewtemplate_type=".$type);
		$res = $database->query("SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE goodnewtemplate_deleted=0 and goodnewtemplate_priority=1 and goodnewtemplate_type=".$type." and ID_RUBRIC_TYPE=$rubric_type and goodnewtemplate_usenum=".(int) $line[0]);
		$arr = array();
		while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
		{
			for ($i = 0; $i <= $line['goodnewtemplate_priority']; $i++)
			{
				$arr[] = $line['ID_GOOD_NEW_TEMPLATE'];
			}
		}
		if (count($arr) > 0)
		{
			if (count($arr) == 1)
			{
				$ths = $arr[0];
			}
			else
			{

				$ths = $arr[array_rand($arr, 1)];
			}
			unset($arr);

			// дата и время в переменную
			$dt1 = $database->getArrayOfQuery("SELECT NOW()");
			$dt1 = $dt1[0];
			$dt = substr($dt1, 8, 2).".".substr($dt1, 5, 2).".".substr($dt1, 0, 4);
			$tm = substr($dt1, 11);
			unset($dt1);

			$line = $database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE ID_GOOD_NEW_TEMPLATE=".$ths, MYSQL_ASSOC);
			$title = $line['goodnewtemplate_title'];
			$txt = $line['goodnewtemplate_text'];


			$txt = trim(str_replace("{url}", "<a href='{url}'>", $txt));
			$txt = trim(str_replace("{/url}", "</a>", $txt));
			$txt = trim(str_replace("{name}", $name, $txt));
			$txt = trim(str_replace("{pname}", $pname, $txt));
			$txt = trim(str_replace("{lprice}", $lprice, $txt));
			$txt = trim(str_replace("{nprice}", $nprice, $txt));
			$txt = trim(str_replace("{date}", $dt, $txt));
			$txt = trim(str_replace("{time}", $tm, $txt));

			$title = trim(str_replace("{name}", $name, $title));
			$title = trim(str_replace("{pname}", $pname, $title));
			$title = trim(str_replace("{lprice}", $lprice, $title));
			$title = trim(str_replace("{nprice}", $nprice, $title));
			$title = trim(str_replace("{date}", $dt, $title));
			$title = trim(str_replace("{time}", $tm, $title));


			$database->query("INSERT INTO ".DB_PREFIX."goods_news (ID_RUBRIC_TYPE,goodnew_type,goodnew_title,goodnew_text,goodnew_dt) VALUES ($rubric_type,$type,'".addslashes($title)."','".addslashes($txt)."',NOW())");

			// счетчик генераций для шабона +1
			$database->query("UPDATE ".DB_PREFIX."goods_news_templates SET goodnewtemplate_usenum=goodnewtemplate_usenum+1 WHERE ID_GOOD_NEW_TEMPLATE=".$ths);
		}
	}
}

function dateOfChange($table, $row, $sort = "DESC")
{
	global $database;
	list($return) = $database->getArrayOfQuery("SELECT change_dt FROM ".DB_CHANGES." WHERE change_table='cprice_$table' and change_row='$row' ORDER BY ID_CHANGE $sort LIMIT 1");
	return $return;
}

//Функция удаления кэша
function del_cache($rub_id, $good_id = 0, $del_goods = false)
{
	global $database;
	$res = mysql_query("select id from cache");
	if (!$res)
		return false;
	if ($rub_id == 0 && $good_id > 0)
		list($rub_id) = $database->getArrayOfQuery("SELECT ID_RUBRIC FROM cprice_rubric_goods WHERE ID_GOOD='$good_id'");
	if ($rub_id > 0 && $good_id >= 0 && mysql_num_rows($res) > 0)
	{
		if (DB_ID == 83)
			mysql_query("update cache set upd=1 where ID_RUBRIC={$rub_id} && ID_GOOD=0");
		else
			mysql_query("delete from cache where ID_RUBRIC={$rub_id} && ID_GOOD=0");
		if ($good_id > 0)
			mysql_query("delete from cache where ID_GOOD={$good_id}");
		if ($del_goods)
		{
			if (DB_ID == 83)
				mysql_query("update cache set upd=1 where ID_RUBRIC={$rub_id}");
			else
				mysql_query("delete from cache where ID_RUBRIC={$rub_id}");
		}
		$res = mysql_query("select rubric_parent from cprice_rubric where ID_RUBRIC=".$rub_id);
		$row = mysql_fetch_array($res);
		if ($row[0] > 0)
			del_cache($row[0]);
	}
}

//Удаление данных из рубрики $rubric_id
//Если массив $goods не пустой, то удаляются записи с ИД из этого массива
//Если $from_rub равно true, то удаляется запись только из этой рубрики, в остальных рубриках, если она там есть, остается
//Возвращает количество удаленных записей, либо -1 в случае ошибки
function deleteData($rubric_id, $goods = array(), $from_rub = false)
{
	global $database;
	if ($rubric_id > 0 && is_array($goods))
	{
		$n = 0;
		if (count($goods) > 0)
		{
			foreach ($goods as $good_id)
			{
				if (!$from_rub)
				{
					$database->query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$good_id);
					$database->query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$good_id);
					$database->query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$good_id);
				}
				else
					$database->query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=".$rubric_id." && ID_GOOD=".$good_id);
				$n++;
			}
		}else
		{
			$res = $database->query("SELECT ID_GOOD FROM ".DB_PREFIX."rubric_goods where ID_RUBRIC=".$rubric_id);
			while ($row = mysql_fetch_array($res))
			{
				$good_id = $row[0];
				if (!$from_rub)
				{
					$database->query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$good_id);
					$database->query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$good_id);
					$database->query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$good_id);
				}
				else
					$database->query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=".$rubric_id." && ID_GOOD=".$good_id);
				$n++;
			}
		}
		return $n;
	}
	else
		return -1;
}

//Возвращает данные по записи $good_id характеристик $features
function getDataId($good_id, $features, $fvalues = false, $url = false)
{
	global $database;
	if ($good_id > 0 && count($features) > 0)
	{
		$data = array();
		if ( $url )
		{
			list($url) = $database->getArrayOfQuery('SELECT good_url FROM '.DB_PREFIX.'goods WHERE ID_GOOD='.$good_id);
			$data['url'] = $url;
		}		
		foreach ($features as $feature_id)
		{
			if ($feature_id > 0)
			{
				$data[$feature_id] = getFeatData($feature_id, $good_id, !$fvalues);
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
//$fvalues - указывает возвращать первоначальные значения из таблицы goods_features
//$uslovia - массив $fid => $fval дополнительные условия для возвращаемых данных
function getData($rubric_id, $orderby = '', $limit = '', $features = array(), $fvalues = false, $uslovia = array())
{
	global $database;
	$data = array();
	if (count($features) == 0)
	{
		$res_feat = $database->query("
				SELECT ".DB_PREFIX."features.ID_FEATURE
				FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
				WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0
				ORDER BY rubricfeature_pos
			");
		while (list($feature_id) = mysql_fetch_array($res_feat))
		{
			$features[] = $feature_id;
		}
	}
	$add_tbl = '';
	$add_sql = '';
	if (count($uslovia) > 0)
	{
		$add_tbl = 'natural join cprice_goods_features';
		$add_sql = " && (";
		foreach ($uslovia as $fid => $fval)
		{
			$add_sql .= "(ID_FEATURE='".$fid."' && goodfeature_value='".$fval."') || ";
		}
		$add_sql = substr($add_sql, 0, -4).")";
	}
	$res = $database->query("
			SELECT ID_GOOD, good_url
			FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods $add_tbl
			WHERE ID_RUBRIC=".$rubric_id." && good_deleted=0 && good_visible=1 and rubricgood_deleted=0".$add_sql
		.(empty($orderby) ? " ORDER BY rubricgood_pos, ID_GOOD" : " ORDER BY ".$orderby)
		.(empty($limit) ? "" : " LIMIT ".$limit)
	);

	while (list($good_id, $url) = mysql_fetch_array($res))
	{
		$data[$good_id]['url'] = $url;
		foreach ($features as $feature_id)
		{
			if ($feature_id > 0)
			{
				if ($fvalues)
					$data[$good_id][$feature_id] = getFeatureValue($good_id, $feature_id);
				else
					$data[$good_id][$feature_id] = getFeatData($feature_id, $good_id);
			}
		}
	}
	return $data;
}
//Возвращает данные из рубрики $rubric_id
//Данные представленны в виде массива: $data[ИД записи][ИД характеристики] = array(Имя характеристики, Значение)
//$order_by указывает по каким полям надо сортировать записи (любые поля из таблиц cprice_goods, cprice_rubric_goods)
//по умолчанию сортирует по rubricgood_pos, ID_GOOD (сначала по порядку записи в рубрике, потом по ее ИД)
//$limit указывает количество возвращаемых данных (значение после параметра LIMIT в SQL - запросе)
//$fvalues - указывает возвращать первоначальные значения из таблицы goods_features
//$uslovia - массив $fid => $fval дополнительные условия для возвращаемых данных
function getData2($rubric_id, $orderby = '', $limit = '', $features = array(), $fvalues = false, $uslovia = array())
{
	global $database;
	$data = array();
	if (count($features) == 0)
	{
		$res_feat = $database->query("
				SELECT ".DB_PREFIX."features.ID_FEATURE, feature_type, feature_text, feature_multiple
				FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
				WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0
				ORDER BY rubricfeature_pos
			");
		while (list($feature_id,$ftype,$fname,$fmult) = mysql_fetch_array($res_feat))
		{
			$features[$feature_id] = array($ftype,$fname,(bool)$fmult);
		}
	}
	$add_tbl = '';
	$add_sql = '';
	if (count($uslovia) > 0)
	{
		$add_tbl = 'natural join cprice_goods_features';
		$add_sql = " && (";
		foreach ($uslovia as $fid => $fval)
		{
			$add_sql .= "(ID_FEATURE='".$fid."' && goodfeature_value='".$fval."') || ";
		}
		$add_sql = substr($add_sql, 0, -4).")";
	}
	$res = $database->query("
			SELECT ID_GOOD, good_url
			FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods $add_tbl
			WHERE ID_RUBRIC=".$rubric_id." && good_deleted=0 && good_visible=1 and rubricgood_deleted=0".$add_sql
		.(empty($orderby) ? " ORDER BY rubricgood_pos, ID_GOOD" : " ORDER BY ".$orderby)
		.(empty($limit) ? "" : " LIMIT ".$limit)
	);

	while (list($good_id, $url) = mysql_fetch_array($res))
	{
		$data[$good_id]['url'] = $url;
		foreach ($features as $feature_id=>$fprms)
		{
			if ($feature_id > 0)
			{
				if ($fvalues)
					$data[$good_id][$feature_id] = array($fprms[1],getFeatureValue($good_id, $feature_id,$fprms[2]));
				else
				{
					$data[$good_id][$feature_id] = array($fprms[1],getFeatData($feature_id, $good_id, true, $fprms[0],$fprms[2]));
				}
			}
		}
	}
	return $data;
}
//Вставляет данные в рубрику или в рубрики $rubric_id из массива $data[ИД характеристики] = значение
//$visible видимость записи в рубрике
//Если $empty равно true, то к записи не добавляется характеристика с пустым значением, иначе добавляется
//В случае удачи возвращает ИД записи, противном случае -1
function insertData($rubric_id, $data, $visible = 1, $empty = false)
{
	global $database, $main_org;
	if (!is_array($rubric_id))
	{
		if ($rubric_id > 0)
			$rubric_id = array($rubric_id);
		else
			$rubric_id = array();
	}
	//$main_org используется для определения предприятия в базе УфаПиар.
	if (count($rubric_id) > 0 && is_array($data))
	{
		$database->query("INSERT INTO ".DB_PREFIX."goods (good_visible,good_deleted".($main_org > 0 ? ',main_org' : '').") VALUES ($visible,0".($main_org > 0 ? ",$main_org" : "").")");
		$change_id = $database->change_id();
		$id = $database->id();
		foreach ($rubric_id as $rub_id)
		{
			if ($rub_id)
				$database->query("INSERT INTO ".DB_PREFIX."rubric_goods (ID_RUBRIC,ID_GOOD) VALUES (".$rub_id.",$id)");
		}
		$url = '';
		if(isset($data['url']))$url = $data['url'];
		foreach ($data as $fid => $value)
		{
			if (is_array($value))
			{
				foreach ($value as $item)
					setFeatData2($fid, $id, $item, false, true, $change_id);
			}
			elseif($fid!='url')
			{
				if (empty($url))$url = mb_strtolower(filename(translit($value))); 
				setFeatData2($fid, $id, $value, false, $empty, $change_id);
			}
		}
		@mysql_query("UPDATE ".DB_PREFIX."goods set good_url='$url' where ID_GOOD=".$id);
		return $id;
	}
	else
		return -1;
}

//Обновляет данные записи с ИД: $good_id из массива $data[ИД характеристики] = значение
//В случае удачи возвращает ИД записи, противном случае -1
function updateData($good_id, $data)
{
	global $database;
	if ($good_id > 0 && is_array($data))
	{
		$database->query("UPDATE ".DB_PREFIX."goods SET good_deleted=0 WHERE ID_GOOD=".$good_id);
		$change_id = $database->change_id();
		foreach ($data as $fid => $value)
		{
			list($mult) = $database->getArrayOfQuery("SELECT feature_multiple FROM ".DB_PREFIX."features WHERE ID_FEATURE = '$fid'");
			if (!empty($mult))
			{
				$database->query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE=".$fid." && ID_GOOD=".$good_id);
				if (is_array($value))
				{
					foreach ($value as $item)
						if (!empty($item))
							setFeatData2($fid, $good_id, $item, false, true, $change_id);
				}
				else
					setFeatData2($fid, $good_id, $value, false, true, $change_id);
			}
			else
				setFeatData2($fid, $good_id, $value, true, false, $change_id);
		}
		return $good_id;
	}
	else
		return -1;
}

//Добавляет/обновляет значение характеристики $feat_id записи $good_id
//$value - значение характеристики
//$update - указывает обновлять значение или добавлять
//Если $empty равно true, то к записи не добавляется характеристика с пустым значением, иначе добавляется
function setFeatData2($feat_id, $good_id, $value, $update = false, $empty = false, $change_id = 0)
{
	global $database;
	if (empty($value) && $empty)
		return false;

	$value = str_replace("'", "\'", $value);
	list($type, $frub, $mult) = $database->getArrayOfQuery("
			SELECT feature_type, feature_rubric,feature_multiple
			FROM ".DB_PREFIX."features
			WHERE ID_FEATURE = '".$feat_id."'
		");

	if ($update)
	{
		$res = $database->query("SELECT goodfeature_value,ID_GOOD_FEATURE FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE = '".$feat_id."' and ID_GOOD = '".$good_id."'");
		$n = mysql_num_rows($res);
		if ($n == 1)
		{
			$row = mysql_fetch_row($res);
			$value_old = $row[0];
			$value_old_id = $row[1];
			switch ($type)
			{
				case 7:
					if ($value_old > 0)
					{
						list($value_old_txt) = $database->getArrayOfQuery("
								SELECT text_text
								FROM ".DB_PREFIX."texts
								WHERE ID_TEXT = '".(int) $value_old."'
							");
						if ($value_old_txt != $value)
						{
							$database->query("UPDATE ".DB_PREFIX."texts SET text_text='".$database->quote($value)."' WHERE ID_TEXT='".$value_old."'", true, 0, $change_id);
						}
					}
					elseif (!empty($value))
					{
						$database->query("INSERT INTO ".DB_PREFIX."texts (text_text) VALUES('".$database->quote($value)."')", true, 0, $change_id);
						$value = $database->id();
						$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$value."' WHERE ID_GOOD_FEATURE='".$value_old_id."'", true, 0, $change_id);
					}
					break;
				case 4:
					$value = trim($value);
					if (!empty($value))
					{
						$value_old_list = '';
						if ($value_old > 0)
							list($value_old_list) = $database->getArrayOfQuery("SELECT featuredirectory_text FROM cprice_feature_directory where ID_FEATURE_DIRECTORY=".$value_old);
						$int_val = intval($value);
						$type_txt = false;
						if ($int_val > 0)
						{
							$str_val = strval($int_val);
							if ($str_val != $value)
								$type_txt = true;
						}
						else
							$type_txt = true;
						if ($type_txt)
						{
							if ($value_old_list != $value)
							{
								list($value_id) = $database->getArrayOfQuery("SELECT ID_FEATURE_DIRECTORY FROM cprice_feature_directory where ID_FEATURE=".$feat_id." && featuredirectory_text like '$value'");
								if (!$value_id)
								{
									$database->query("INSERT INTO cprice_feature_directory (ID_FEATURE,featuredirectory_text) VALUES (".$feat_id.",'".$database->quote($value)."')");
									$value = $database->id();
								}
								else
									$value = $value_id;
								$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$database->quote($value)."' WHERE ID_GOOD_FEATURE='".$value_old_id."'", true, 0, $change_id);
							}
						}elseif ($value_old != $value)
							$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$database->quote($value)."' WHERE ID_GOOD_FEATURE='".$value_old_id."'", true, 0, $change_id);
					}elseif ($value_old != $value)
						$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$database->quote($value)."' WHERE ID_GOOD_FEATURE='".$value_old_id."'", true, 0, $change_id);
					break;
				case 9:
					$value = trim($value);
					if (!empty($value))
					{
						$value_old_list = '';
						if ($value_old > 0)
							list($value_old_list) = getRubricName($good, false, false, false);
						$int_val = intval($value);
						$type_txt = false;
						if ($int_val > 0)
						{
							$str_val = strval($int_val);
							if ($str_val != $value)
								$type_txt = true;
						}
						else
							$type_txt = true;
						if ($type_txt)
						{
							if ($value_old_list != $value)
							{
								list($value_id) = $database->getArrayOfQuery("SELECT ID_RUBRIC FROM cprice_rubric where rubric_name like '$value'");
								if ($value_id > 0)
								{
									$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$value_id."' WHERE ID_GOOD_FEATURE='".$value_old_id."'", true, 0, $change_id);
								}
							}
						}
						elseif ($value_old != $value)
							$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$database->quote($value)."' WHERE ID_GOOD_FEATURE='".$value_old_id."'", true, 0, $change_id);
					}elseif ($value_old != $value)
						$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$database->quote($value)."' WHERE ID_GOOD_FEATURE='".$value_old_id."'", true, 0, $change_id);
					break;
				default:
					if ($value_old != $value)
						$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$database->quote($value)."',goodfeature_float=".($type == 1 ? floatval($value) : 0)." WHERE ID_GOOD_FEATURE='".$value_old_id."'", true, 0, $change_id);
					break;
			}
			return true;
		}
		if ($n > 1 && $mult == 0)
		{
			$database->query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE = '".$feat_id."' and ID_GOOD = '".$good_id."'", false);
		}
	}
	switch ($type)
	{
		case 7:
			$database->query("INSERT INTO ".DB_PREFIX."texts (text_text) VALUES('".$database->quote($value)."')", true, 0, $change_id);
			$value = $database->id();
			break;
		case 4:
			$value = trim($value);
			if (!empty($value))
			{
				$int_val = intval($value);
				$type_txt = false;
				if ($int_val > 0)
				{
					$str_val = strval($int_val);
					if ($str_val != $value)
						$type_txt = true;
				}
				else
					$type_txt = true;
				if ($type_txt)
				{
					list($value_id) = $database->getArrayOfQuery("SELECT ID_FEATURE_DIRECTORY FROM cprice_feature_directory where ID_FEATURE=".$feat_id." && featuredirectory_text like '$value'");
					if (!$value_id)
					{
						$database->query("INSERT INTO cprice_feature_directory (ID_FEATURE,featuredirectory_text) VALUES (".$feat_id.",'".$database->quote($value)."')");
						$value = $database->id();
					}
					else
						$value = $value_id;
				}
			}
			break;
		case 9:
			$value = trim($value);
			if (!empty($value))
			{
				$int_val = intval($value);
				$type_txt = false;
				if ($int_val > 0)
				{
					$str_val = strval($int_val);
					if ($str_val != $value)
						$type_txt = true;
				}
				else
					$type_txt = true;
				if ($type_txt)
				{
					list($value_id) = $database->getArrayOfQuery("SELECT ID_RUBRIC FROM cprice_rubric where rubric_type=".$frub." && rubric_name like '$value'");
					if (!$value_id)
					{
						$textid = filename(translit($value));
						$i = 0;
						while ($database->getArrayOfQuery("SELECT ID_RUBRIC FROM cprice_rubric WHERE rubric_textid='$textid".(($i == 0) ? "" : "_$i")."' and rubric_deleted=0 && rubric_type=".$frub))
						{
							$i++;
						}
						if (!empty($i))
							$textid .= "_$i";
						setlocale(LC_ALL, array('ru_RU.CP1251', 'rus_RUS.1251'));
						$database->query("INSERT INTO ".DB_PREFIX."rubric (rubric_textid,rubric_parent,rubric_name,rubric_type,rubric_visible) VALUES ('".$textid."',0,'".ucfirst($value)."','$frub',1)");
						$value = $database->id();
					}
					else
						$value = $value_id;
				}
			}
			break;
		/* для креациони */
		/* case 5:
		  list($feature_rubric) = $database->getArrayOfQuery("
		  SELECT ID_RUBRIC
		  FROM ".DB_PREFIX."feature_rubric
		  WHERE ID_FEATURE = '".$feat_id."'
		  ");
		  list($rubric_type) = $database->getArrayOfQuery("
		  SELECT rubric_type
		  FROM ".DB_PREFIX."rubric
		  WHERE ID_RUBRIC = '".$feature_rubric."'
		  ");
		  $value = trim($value);
		  if(!empty($value))
		  {
		  $int_val = intval($value);
		  $type_txt = false;
		  if($int_val>0)
		  {
		  $str_val = strval($int_val);
		  if($str_val!=$value)$type_txt = true;
		  }else $type_txt = true;
		  if($type_txt)
		  {
		  list($value_id) = $database->getArrayOfQuery("SELECT ID_GOOD FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."goods_features where ID_RUBRIC=$feature_rubric  and ID_FEATURE=51 && goodfeature_value like '$value'");
		  if(!$value_id)
		  {
		  }else $value = $value_id;
		  }
		  }
		  break; */
	}
	$database->query("INSERT INTO ".DB_PREFIX."goods_features (ID_FEATURE,ID_GOOD,goodfeature_value,goodfeature_float) VALUES ('".$feat_id."','".$good_id."','".$database->quote($value)."',".($type == 1 ? floatval($value) : 0).")", true, 0, $change_id);
	return true;
}

function setFeatData($feat_id, $good_id, $value, $multiple = false)
{
	global $database;

	list($type) = $database->getArrayOfQuery("
			SELECT feature_type
			FROM ".DB_PREFIX."features
			WHERE ID_FEATURE = '".$feat_id."'
		");

	if ($value_old = getFeatureValue($good_id, $feat_id))
	{
		if ($multiple)
		{
			$database->query("INSERT INTO ".DB_PREFIX."goods_features (ID_FEATURE,ID_GOOD,goodfeature_value,goodfeature_float) VALUES ('".$feat_id."','".$good_id."','".$value."',".($type == 1 ? floatval($value) : 0).")", true, 0, $good_id);
		}
		else
		{
			$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$value."',goodfeature_float=".($type == 1 ? floatval($value) : 0)." WHERE ID_FEATURE='".$feat_id."' AND ID_GOOD='".$good_id."'", true, 0, $good_id);
		}
	}
	else
	{
		switch ($type)
		{
			case 7:
				$database->query("INSERT INTO ".DB_PREFIX."texts (text_text) VALUES('".$value."')", true, 0, $good_id);
				$value = $database->id();
				break;
		}
		$database->query("INSERT INTO ".DB_PREFIX."goods_features (ID_FEATURE,ID_GOOD,goodfeature_value,goodfeature_float) VALUES ('".$feat_id."','".$good_id."','".$value."',".($type == 1 ? floatval($value) : 0).")", true, 0, $good_id);
	}
}

function getFeatData($feat_id, $good_id, $ppl = true, $restype=0, $mult = false)
{
	global $database;

	$resfeat = '';
	if($restype>0)$resfeatarr = $database->getColumnOfQuery("
			SELECT goodfeature_value
			FROM ".DB_PREFIX."goods_features 
			WHERE ID_FEATURE = '".$feat_id."' and ID_GOOD = '".$good_id."'".(!$mult?' limit 1':''));
	else { 
		list($restype, $resfeat_val) = $database->getArrayOfQuery("
			SELECT ".DB_PREFIX."features.feature_type, ".DB_PREFIX."goods_features.goodfeature_value
			FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features
			WHERE ".DB_PREFIX."goods_features.ID_FEATURE = '".$feat_id."' and ID_GOOD = '".$good_id."'
		");
		$resfeatarr[] = $resfeat;
	}
	$out = array();
	foreach ($resfeatarr as $resfeat) {
		
		if (!$ppl)
			return $resfeat;
		switch ($restype)
		{
			case 3:
				if ($resfeat)
					$out[] = "да";
				else
					$out[] =  "нет";
				break;
			case 4:
				list($resfeat) = $database->getArrayOfQuery("
						SELECT featuredirectory_text
						FROM ".DB_PREFIX."feature_directory
						WHERE ID_FEATURE_DIRECTORY = '".$resfeat."'
					");
				$out[] = $resfeat;
				break;
			case 5:
				list($resfeat) = $database->getArrayOfQuery("
						SELECT goodfeature_value
						FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features
						WHERE ID_GOOD='".$resfeat."' and feature_type = 2
						ORDER BY ID_FEATURE
						LIMIT 1
					");
				$out[] = $resfeat;
				break;
			case 7:
				list($resfeat) = $database->getArrayOfQuery("
						SELECT text_text
						FROM ".DB_PREFIX."texts
						WHERE ID_TEXT = '".(int) $resfeat."'
					");
				$out[] = $resfeat;
				break;
			case 9:
				$out[] = getRubricName($resfeat, false, false, false);
			break;
			default :
				$out[] = $resfeat;
			break;
		}
	}
	if($mult) return $out;
	elseif(count($out)==1)return $out[0];
	return '';
}

function getImages($id, $num = 1, $pre = "trumb_", $returnarray = false)
{
	global $database;

	$arr = array();

	$res = $database->query("
		SELECT *
		FROM ".DB_PREFIX."goods_photos
		WHERE ID_GOOD = '$id' and goodphoto_visible=1 and goodphoto_deleted=0
		ORDER BY goodphoto_pos
		LIMIT $num
	");
	while ($line = mysql_fetch_array($res, MYSQL_ASSOC))
	{
		$id_ph = $line['ID_GOOD_PHOTO'];
		$resfeat = $line['goodphoto_file'];
		if (!empty($resfeat))
		{
			$resfeat1 = "http://ufapr.ru/images/good_photo/".$pre.$resfeat;
			if (file_exists("/var/www/ufapr.ru/images/good_photo/".$pre.$resfeat))
			{
				if ($num == 1)
				{
					if ($returnarray)
					{
						return array($resfeat1, $line['goodphoto_desc'], $line['goodphoto_alt']);
					}
					else
					{
						return $resfeat1;
					}
				}
				else
				{
					if ($returnarray)
					{
						$arr[$id_ph] = array($resfeat1, $line['goodphoto_desc'], $line['goodphoto_alt']);
					}
					else
					{
						$arr[$id_ph] = $resfeat1;
					}
				}
			}
		}
	}
	if ($num == 1)
	{
		return false;
	}
	else
	{
		return $arr;
	}
}

?>