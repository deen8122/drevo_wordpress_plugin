<?

if (!isset($_status)) {
	header("HTTP/1.0 404 Not Found");
	die;
}
//Массив баз для реализации урлов записей рубрик
$arr_db_urls = array(25, 22, 23, 19);
//для спецпредложений уфатрэвела
global $hosts;
global $fieldname;
//unlink('../cache_good/'.$_GET['id'].'.html');
/////////////
// GET -> POST
get_rubric_from_get();
@$act_add = (int) $_GET['ses_add'];

/* * *  Алексей - 11.11.08   ** */
$my_arr_price1 = $create_news_triger->what_price($type, $id);
/* * *  /Алексей   ** */

//
if (@$_GET['do'] == "rubric" && empty($_GET['iframe'])) {
	$database->query("DELETE FROM " . DB_PREFIX . "rubric_goods WHERE ID_GOOD=$id");
	foreach ($_POST['rubric'] AS $rub => $on) {
		$database->query("INSERT INTO " . DB_PREFIX . "rubric_goods (ID_RUBRIC,ID_GOOD) VALUES ($rub,$id)");
	}
}

//print_r($_POST['rubric']);die('qwerty');
$rprefix = '';
$rurl = '';
// если не айфрейм
if (!isset($_GET['iframe'])) {
	if ($act_add > 0) { // если добавление
		list($rname, $rprefix, $rurl) = $database->getArrayOfQuery("SELECT rubric_name,rubric_unit_prefixname,rubric_textid FROM " . DB_PREFIX . "rubric WHERE ID_RUBRIC=$rubric_id");
		print "<h2>Добавление " . $rtype['rubrictype_r_s'] . " в рубрику " . getRubricName($rubric_id, false, true) . "</h2>";
		// print "<div align=center><a href='".teGetUrlQuery("action=feat_copy")."'>Копировать значения характеристик из другой единицы</a></div><br>";
	} else { // если изменение
		$rubres = $database->query("SELECT ID_RUBRIC FROM " . DB_PREFIX . "rubric_goods WHERE ID_GOOD=" . $id);
		if (mysql_num_rows($rubres) > 1) {
			$s = "";
			while (list($rubid) = mysql_fetch_array($rubres)) {
				$s .= getRubricName($rubid, false, false) . ", ";
			}
			$s = substr($s, 0, -2);

			print "<h2>Заполнение данных " . $rtype['rubrictype_r_s'] . " ID:" . getIdToPrint("goods", $id) . " в рубриках " . $s;

			unset($s);
		} else {
			list($rubid) = mysql_fetch_array($rubres);
			print "<h2>Заполнение данных " . $rtype['rubrictype_r_s'] . " ID:" . getIdToPrint("goods", $id) . " в рубрике " . getRubricName($rubid, false, true);
		}
		print "</h2>";
	}
}

//
if ($_GET['action'] == 'edit' && @$_GET['method'] == "add") {
	$iframe_new = false;
	$rubres = $database->query("SELECT ID_RUBRIC FROM " . DB_PREFIX . "rubric_goods WHERE ID_GOOD=" . $id);
	if (mysql_num_rows($rubres) > 0) {
		$_POST['rubric'] = array();
		while ($rub = mysql_fetch_array($rubres)) {
			$_POST['rubric'][$rub[0]] = "on";
		}
	} else {
		// если iframe
		$iframe_new = true;
	}
}

if ($act_add > 0)
	$_POST['rubric'] = $_GET['rubric'];


$frm = new teForm("form1", "post", false, true);
$frm->inOnetd = true;

//
function whatrubric($bibibi = true) {
	global $frm;
	global $database;

	unset($_POST['rubric']);

	$res = $database->query("SELECT ID_RUBRIC FROM " . DB_PREFIX . "feature_rubric WHERE ID_FEATURE=" . $_GET['linkfeature']);
	while ($line = mysql_fetch_array($res)) {
		$_POST['rubric'][$line[0]] = "on";
	}


	$frm->addf_selectGroup("rubric_", "Выберите наследуемую рубрику");
	foreach ($_POST['rubric'] AS $rub => $on) {
		//addselitem($rub);
		$frm->addf_selectItem("rubric_", $rub, getRubricName($rub, false, false, false));
	}
	$frm->setf_require("rubric_");

	if ($bibibi)
		$frm->send();
}



// это всё связано с субформами (они глючат, их вообще лучше убрать, или переписать)
if (!empty($_GET['iframe']) && @$_GET['method'] == "select") {
	print "<h2>Выберите подключаемые записи</h2>";

	$linkgood = (int) $_GET['linkgood'];
	$linkfeature = (int) $_GET['linkfeature'];

	if (!empty($_POST['gd'])) {
		if (!is_array($_POST['gd']))
			$_POST['gd'] = array($_POST['gd'] => "on");

		$s = "";
		foreach ($_POST['gd'] AS $id => $on) {
			if (!$database->getArrayOfQuery("SELECT ID_GOOD FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=$linkgood and ID_FEATURE=$linkfeature and goodfeature_value=$id")) {
				$database->query("INSERT INTO " . DB_PREFIX . "goods_features (ID_GOOD,ID_FEATURE,goodfeature_value,goodfeature_visible) VALUES ($linkgood,$linkfeature,'$id',1)");
				$s .= " and goodfeature_value!=$id";
				print teGetJSScript("
						parent.subform_addval($id,'" . getFeatureText($id, 0, true) . "','http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "');
					");
			}
		}
		$database->query("DELETE FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=$linkgood and ID_FEATURE=$linkfeature $s");

		print teGetJSScript("
				parent.close_subform();
			");
	} else {



		if (!empty($_GET['iframe']) && empty($_POST['rubric_']) && (count($_POST['rubric']) > 1 || (count($_POST['rubric']) == 1 && $_POST['rubric'][$_GET['rubric_id']] == "on"))) {
			whatrubric();
		} else {
			print "<form method='post'>";
			$s = "SELECT ID_GOOD FROM " . DB_PREFIX . "rubric_goods WHERE ";
			foreach ($_POST['rubric'] AS $iarr => $on) {
				$s .= "ID_RUBRIC=" . $iarr . " or ";
			}
			$s .= "false";
			$res = $database->query($s);
			while ($line = mysql_fetch_array($res)) {
				print "<div>";
				$feat = $database->getArrayOfQuery("SELECT feature_multiple FROM " . DB_PREFIX . "features WHERE ID_FEATURE=$linkfeature");
				$exists = $database->getArrayOfQuery("SELECT ID_GOOD FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=$linkgood and ID_FEATURE=$linkfeature and goodfeature_value=$line[0]");
				if ($feat[0] == 0)
					print "<input type='radio' name='gd' value='$line[0]' id='gd$line[0]' " . (($exists) ? "checked" : "") . ">";
				if ($feat[0] == 1)
					print "<input type='checkbox' name='gd[" . $line[0] . "]' id='gd$line[0]' " . (($exists) ? "checked" : "") . ">";
				print "<label for='gd$line[0]'>" . getFeatureText($line[0], 0, true) . "</label></div>";
			}
			print "<div align='center'><input type='submit' value='сохранить'></div>";
			print "</form>";
		}
	}
} else {
	print "<div align=center>";

	// вывод сверху уменьшенных фоток записи
	$res = $database->query("SELECT * FROM " . DB_PREFIX . "goods_photos WHERE goodphoto_deleted=0 and ID_GOOD=" . $id . " ORDER BY goodphoto_pos LIMIT 5");
	while ($line = mysql_fetch_array($res, MYSQL_ASSOC)) {
		print "<a href='" . URLDATA_FLD . "good_photo/image_$line[goodphoto_file]'><img src='" . URLDATA_FLD . "good_photo/trumb_$line[goodphoto_file]'></a>";
	}


	if (!empty($_GET['iframe']) && empty($_POST['rubric_']) && @$rubcntqwe > 1) {
		whatrubric(false);
	} else {

		//if(!empty($_GET['rubric'])) $_POST['rubric'] = $_GET['rubric'];

		function gchild_t9($rub_type, $rub_id, $prefix = "") {
			global $database;
			global $frm;
			global $fieldname;

			$res = $database->query("SELECT * FROM " . DB_PREFIX . "rubric WHERE rubric_type=$rub_type and rubric_parent=$rub_id and rubric_deleted=0 ORDER BY rubric_pos,rubric_name");
			if (mysql_num_rows($res) > 0) {
				$frm->addf_selectItem($fieldname, $rub_id, $prefix);
				while ($line = mysql_fetch_array($res, MYSQL_ASSOC)) {
					gchild_t9($rub_type, $line['ID_RUBRIC'], (empty($prefix) ? "" : $prefix . " > ") . $line['rubric_name'] . ($line['rubric_visible'] == 0 ? ' (откл.)' : ''));
				}
			} else {
				$frm->addf_selectItem($fieldname, $rub_id, $prefix);
			}
		}

		if (!empty($_GET['iframe'])) {
			$frm->addf_hidden("iframe", "2");
		}
		if (!empty($_POST['iframe'][0]))
			$_GET['iframe'] == 2;

		// берем принадлежность товара к рубрикам и суем в пост
		if (isset($_POST['rubric'])) {
			$s = "";
			foreach ($_POST['rubric'] AS $shid => $on) {
				$s .= "<input type='hidden' name='rubric[" . $shid . "]' value='$on' />";
			}
			$frm->addTitle($s);
		} else {
			$res = $database->query("SELECT * FROM " . DB_PREFIX . "rubric_goods WHERE ID_GOOD=" . $id);
			while ($line = mysql_fetch_array($res, MYSQL_ASSOC)) {
				$_POST['rubric'][$line['ID_RUBRIC']] = "on";
			}
		}


		// берем с БД главные данные о товаре
		$good_visible = true;
		$good_url = '';
		if ($act_add == 0) {
			$good = $database->getArrayOfQuery("SELECT * FROM " . DB_PREFIX . "goods WHERE ID_GOOD=" . $id, MYSQL_ASSOC);


			if ($_GET['action'] == "edit") {
				if ($good['good_visible'] == 0)
					$good_visible = false;
				else
					$good_visible = true;
				if (DB_ID > 48 || in_array(DB_ID, $arr_db_urls))
					$good_url = $good['good_url'];
				//Просмотр по событию
				if (isset($_GET['events'])) {
					$res3 = $database->query("SELECT * FROM " . DB_PREFIX . "rubric_events where ID_GOOD=" . $id . " && ID_USER=" . $_USER['id']);
					if (mysql_num_rows($res3) == 0)
						$database->query("INSERT INTO " . DB_PREFIX . "rubric_events (ID_GOOD, ID_USER, tdate) values ('" . $id . "','" . $_USER['id'] . "','" . time() . "')");
				}
			}
		}

		$frm->addf_hidden("good_visible", "on");
		$first_txtfield = '';
		// для каждой рубрики, в которой состоит запись
		if (!empty($_POST['rubric']))
			foreach ($_POST['rubric'] AS $iarr => $on) {

				//$frm->addf_text("good_name", "Название", 'Название');
				//$frm->setFieldWidth("good_name", "150px");
							$code = <<<TXT
  function encodestring(st)
  {
    st = st.toLowerCase();
    var str1 ="абвгдеёзийклмнопрстуфхыэ ", str2 = "abvgdeeziyklmnoprstufhie-";
    for(var i=0;i<str1.length;i++)
    {
    	st = st.replace(new RegExp(str1[i],'g'),str2[i]);
    }
    var str3 = "жцчшщьъюяїє";
    var arr = ["zh","ts", "ch","sh","shch", "", "", "yu", "ya", "i", "ie"];
    for(var i=0;i<str3.length;i++)
    {
     	st = st.replace(new RegExp(str3[i],'g'),arr[i]);
    }
   	var re = new RegExp('^([a-z0-9_-])$');
   	var st2 = "";
    for(var i=0;i<st.length;i++)
    {		if (re.test(st[i] + ""))
		{
			st2 += st[i];
		}    }
    return st2;
  }
  function change_rub(elem)
  {
  	if(elem.value!='')
  	{
  		document.getElementById('good_url_0').value = encodestring(elem.value);
  	}
  }
TXT;
				teAddJSScript($code);
				if ($act_add > 0) {
					//$frm->setJSScript('good_name', "onChange", "change_rub(this);");
					//$frm->setJSScript('good_name', "onChange", "change_rub(this);");
				} else {
					$frm->addf_desc('good_url', "Уникальное слово в рубрике, состоящее<br/>из строчных латинских букв, цифр и знаков: - и _ " . '<a href="#" onclick="change_rub(document.getElementById(\'' . $first_txtfield . '_0\'));return false;">(URL)</a>');
				}
				
				
				//$frm->addf_ereg($fieldname, "^[0123456789\.\,]*$");
				// для этой рубрики запрашиваем все характеристики
				$res = $database->query("SELECT " . DB_PREFIX . "features.* FROM " . DB_PREFIX . "features NATURAL JOIN " . DB_PREFIX . "rubric_features WHERE feature_deleted=0 /*and feature_enable=1*/ and rubric_type=$type and ID_RUBRIC=" . $iarr . " ORDER BY rubricfeature_pos,feature_text");
				if (mysql_num_rows($res) > 0) {
					while ($line = mysql_fetch_array($res, MYSQL_ASSOC)) {

						//  проверка на дублирование хар-к (если много рубрик товара) ( так, на всякий ;) )
						if (@$arrnames[$line['feature_text']] == $line['feature_type'] && 0) {
							
						} else {
							@$arrnames[$line['feature_text']] = $line['feature_type'];

							$answer = array();
							// берем ответ (если изменение), или пустое значение ( или скопированные хар-ки с др.товара )  (если добавление)
							if ($act_add > 0) {
								$answer[] = "";
							} else {
								$res_ = $database->query("SELECT goodfeature_value FROM " . DB_PREFIX . "goods_features WHERE ID_GOOD=$id and ID_FEATURE=" . $line['ID_FEATURE']);
								while ($line_ = mysql_fetch_array($res_)) {
									$answer[] = $line_[0];
								}
							}

							$fieldname = "good_hand_" . $line['ID_FEATURE'];

							switch ($line['feature_type']) {
								case 1: // число
									$frm->addf_text($fieldname, $line['feature_text'], $answer);
									$frm->setFieldWidth($fieldname, "150px");
									$frm->addf_ereg($fieldname, "^[0123456789\.\,]*$");
									break;
								case 2: // текст
									if (empty($first_txtfield))
										$first_txtfield = $fieldname;
									$frm->addf_text($fieldname, $line['feature_text'], $answer);
									$frm->setFieldWidth($fieldname, "300px");
									break;
								case 7: // большой текст
									if (!empty($answer[0]) && is_numeric($answer[0])) {
										$answer = $database->getArrayOfQuery("SELECT text_text FROM " . DB_PREFIX . "texts WHERE ID_TEXT=" . $answer[0]);
										$answer = $answer[0];
									}
									$frm->addf_text($fieldname, $line['feature_text'], $answer, true);
									$frm->setFieldWidth($fieldname, "700px");
									$frm->setFieldHeight($fieldname, "150px");
									// word("#".$fieldname);
									break;
								case 3: // логика
									$answer1 = true;
									if ($_GET['action'] == "edit") {
										if (empty($answer[0]))
											$answer1 = false;
										else
											$answer1 = true;
									}

									$frm->addf_checkbox($fieldname, $line['feature_text'], $answer1);
									break;
								case 4: // справочник
									$res1 = $database->query("SELECT * FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE=" . $line['ID_FEATURE'] . " ORDER BY featuredirectory_text");

									$frm->addf_selectGroup($fieldname, " " . $line['feature_text'], (($line['feature_rubric'] == 0) ? false : true), $type, $id);
									while ($line1 = mysql_fetch_array($res1, MYSQL_ASSOC)) {
										$frm->addf_selectItem($fieldname, $line1['ID_FEATURE_DIRECTORY'], $line1['featuredirectory_text']);
									}
									if (isset($answer[0]))
										if ($line['feature_rubric'] > 0 && $answer[0] > 0)
											list($answer) = $database->getArrayOfQuery("SELECT featuredirectory_text FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE_DIRECTORY=" . $answer[0]);
									$frm->add_value($fieldname, $answer);
									break;
								case 5: // дин.ветвь

									$res_fr = $database->query("SELECT ID_RUBRIC FROM " . DB_PREFIX . "feature_rubric WHERE ID_FEATURE=" . $line['ID_FEATURE']);
									if (mysql_num_rows($res_fr) < 1) {
										$frm->addf_group($fieldname, $line['feature_text'], "Характеристика не связана ни с одной из ветвей.");
									} else {
										if (mysql_num_rows($res_fr) > 1)
											$many = true;
										else
											$many = false;
										$frm->addf_selectGroup($fieldname, " " . $line['feature_text'], false);
										while (list($rub_id) = mysql_fetch_array($res_fr)) {
											$rub_name = '';
											if ($many) {
												list($rub_name) = $database->getArrayOfQUery("SELECT rubric_name FROM cprice_rubric WHERE ID_RUBRIC='" . $rub_id . "' LIMIT 1");
												$rub_name .=' &raquo; ';
											}
											$res_good = $database->query("
										SELECT " . DB_PREFIX . "goods.ID_GOOD
										FROM " . DB_PREFIX . "goods NATURAL JOIN " . DB_PREFIX . "rubric_goods
										WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC='" . $rub_id . "'
									");

											list($firstfeat) = $database->getArrayOfQUery("SELECT cprice_features.ID_FEATURE FROM cprice_rubric_features NATURAL JOIN cprice_features WHERE cprice_rubric_features.ID_RUBRIC='" . $rub_id . "' and feature_deleted=0 ORDER BY rubricfeature_pos LIMIT 1");
											$arrt5 = array();
											while (list($id_good) = mysql_fetch_array($res_good)) {
												$arrt5[$id_good] = getFeatureText($id_good, $firstfeat);
											}
											asort($arrt5);
											foreach ($arrt5 AS $id_good => $txt) {
												$frm->addf_selectItem($fieldname, $id_good, $rub_name . $txt);
											}
										}
										$frm->add_value($fieldname, $answer);
									}
									break;
								case 9: // раздел
									$frm->addf_selectGroup2($fieldname, " " . $line['feature_text'], $type, $id, $line['ID_FEATURE']);
									gchild_t9($line['feature_rubric'], 0);
									//print '<!--';								
									//print_r($answer);
									//print '-->';
									//echo $fieldname;
									$frm->add_value($fieldname, $answer);

									break;
								case 10: // раздел записей
									$frm->addf_selectGroup2($fieldname, " " . $line['feature_text'], $type, $id, $line['ID_FEATURE']);
									gchild_t9($line['feature_rubric'], 0);
									//$idrub = getFeatureValue($id, $line['ID_FEATURE']);
									//print_r($idrub);echo '<br>';
									//print_r(getRubricName($idrub,false,false,false));
									$values = '';
									if ($line['feature_multiple'] == 1) {
										$jss = <<<TXT
									$('#{$fieldname}_dlg input:checked').each(function(){
										var id = $(this).val();
										if($('#gd'+id).length==0)$('#{$fieldname}_vals').append('<input type="checkbox" name="{$fieldname}_[]" value="'+id+'" id="gd'+id+'" checked="checked" /> <label for="gd'+id+'">'+$(this).next().text()+'</label><br/>');
									});
TXT;
									} else {
										$jss = <<<TXT
									var id = $('#{$fieldname}_dlg input:radio:checked').val();
									if(id>0)$('#{$fieldname}_vals').html('<input type="checkbox" name="{$fieldname}_[]" value="'+id+'" id="gd'+id+'" checked="checked" /> <label for="gd'+id+'">'+$('#{$fieldname}_dlg input:radio:checked').next().text()+'</label>');
TXT;
									}
									if (isset($_POST[$fieldname . '_']))
										$answer = $_POST[$fieldname . '_'];
									if (count($answer) > 0) {
										foreach ($answer as $val) {
											if ($val > 0) {
												list($gname) = $database->getArrayOfQuery("
													SELECT goodfeature_value
													FROM cprice_goods_features NATURAL JOIN " . DB_PREFIX . "rubric_features
													WHERE ID_GOOD=" . $val . "
													ORDER BY rubricfeature_pos, ID_GOOD_FEATURE
													LIMIT 1
												");
												if (empty($gname))
													$gname = getFeatureText($val, 0, true);
												$arr = explode(',', $gname);
												$gname = $arr[0];

												$values .= '<input type="checkbox" name="' . $fieldname . '_[]" value="' . $val . '" id="gd' . $val . '" checked="checked" /> <label for="gd' . $val . '">' . $gname . '</label><br/>';
											}
										}
									}
									$jss = <<<TXT
$(document).ready(function() {
	var sel = $('#{$fieldname}_0');
	sel.after('<div id="{$fieldname}_vals">{$values}</div>').after('<div id="{$fieldname}_dlg" style="display:none"></div>');
	sel.change(function(){
		if($(this).val()!='')
		{
			$('#{$fieldname}_dlg').html('<center>загрузка...</center>');
			$('#{$fieldname}_dlg').dialog({
				title:'Выберите записи из рубрики: '+$('#{$fieldname}_0 option:selected').text(),
				bgiframe:true,width:800,height:400,modal:true,
				overlay:{backgroundColor:'#000',opacity:0.8},
				buttons: {
					'OK': function() {
						$jss
						$(this).dialog('close');
					}
				},
				close:function(){
					$(this).dialog('destroy');
				}
			});
			
			$.get(ajaxurl+"?pg=ajax&action=wp_ajax&op1=list_goods&rid="+sel.val()+"&mult={$line['feature_multiple']}",function(data){
			//console.log(data);
				$('#{$fieldname}_dlg').html(data);
			});			
		}
	});
});
TXT;
									teAddJSScript($jss);
									break;
								case 6: // файл
									$frm->addf_file($fieldname, " " . $line['feature_text'], "", 26214400, DATA_FLD . "features/");
									$frm->add_value($fieldname, $answer);
									$s = "";
									$res3 = $database->query("SELECT * FROM " . DB_PREFIX . "feature_directory WHERE ID_FEATURE=" . $line['ID_FEATURE'] . " ORDER BY featuredirectory_text");
									while ($line3 = mysql_fetch_array($res3, MYSQL_ASSOC)) {
										$s .= ", " . $line3['featuredirectory_text'];
									}
									if (!empty($s)) {
										$s = substr($s, 2);
										$frm->addf_desc($fieldname, "Возможные расширения: <b>$s</b>");
									}
									if (!empty($answer)) {
										$line['feature_require'] = 0;
									}
									break;
								case 8: // дата

									if (!isset($datepicker)) {
										$datepicker = true;
										//teAddJSFile("../js/jq.datepicker.js");
										//teAddJSFile("../js/jq.maskedinput.js");
										//teAddCSSFile("../js/jq.datepicker.css");
									}
									teAddJSFile(DEEN_FOLDERS_URL . "/assets/js/jq.datepicker.js");
									teAddJSFile(DEEN_FOLDERS_URL . "/assets/js/jq.maskedinput.js");
									teAddCSSFile(DEEN_FOLDERS_URL . "/assets/js/jq.datepicker.css");
									teAddJSScript('jQuery(document).ready(function(){jQuery("#' . $fieldname . '_0").attachDatepicker();});
								(function($) {$(function() {jQuery("#' . $fieldname . '_0").mask("99.99.9999");});})(jQuery);
								');
									//teAddJSScript("$"."(document).ready(function(){"."$"."('#".$fieldname."_0').attachDatepicker();});");

									$frm->addf_text($fieldname, " " . $line['feature_text'], $answer);
									$frm->addf_desc($fieldname, "Заполните в формате ДД.ММ.ГГГГ<br><i>напр. " . (sprintf("%02d.%02d.%04d", rand(1, 31), rand(1, 12), rand(1945, 2099))) . "</i>");
									$frm->setFieldWidth($fieldname, "175px");
									$frm->addf_ereg($fieldname, "^[0-3]{1}[0-9]{1}.[01]{1}[0-9]{1}.[123]{1}[0-9]{3}$");
									break;
							}

							if ($line['feature_require'] == 1)
								$frm->setf_require($fieldname); // обязательное поле
							if ($line['feature_multiple'] == 1 && $line['feature_type'] != 10)
								$frm->setFieldMultiple($fieldname); // мульти поле
						}
					}
				} else {
					$frm->addf_group("div1", "<b>Характеристики</b>", "нет добавленных характеристик");
				}
			}
		if (DB_ID > 48 || in_array(DB_ID, $arr_db_urls)) {
			$frm->addf_text('good_url', 'URL страницы', $good_url);
			$frm->addf_desc('good_url', "Уникальное слово в рубрике, состоящее<br/>из строчных латинских букв, цифр и знаков: - и _");
			if (!empty($first_txtfield)) {
	
				//teAddJSScript($code);
				if ($act_add > 0) {
					//$frm->setJSScript('good_name', "onChange", "change_rub(this);");
				} else {
					$frm->addf_desc('good_url', "Уникальное слово в рубрике, состоящее<br/>из строчных латинских букв, цифр и знаков: - и _ " . '<a href="#" onclick="change_rub(document.getElementById(\'' . $first_txtfield . '_0\'));return false;">(URL)</a>');
				}
			}
		}
	}

	// поле для добавления картинки
	$frm->addf_file("photo", "
			<img src='" . DEEN_FOLDERS_URL . "assets/images/camera.png' style='height:20px' align='top' alt=''/> Прикрепить картинки
			<br/><input type='checkbox' name='malph' id='malph' value='1' /><label for='malph'>не уменьшать</label>
			<br/><input type='checkbox' name='wtmrk' id='wtmrk' value='1' checked='checked' /><label for='wtmrk'>с водным знаком (если он есть)</label>
			", "", 10 * 1024 * 1024, DATA_FLD . "good_photo/", "'jpg','png','gif','bmp'");

	$frm->setFieldMultiple("photo");


	if (file_exists($hosts[DB_ID]['folder'] . 'sitemap.xml') && $act_add > 0) {
		$sidemapFormHidder = '
				var changefreq_tr; 
				var priority_tr;
				var lastmod_tr;
				var sidemapShowed = false;
				window.onload = function()
				{
					changefreq_tr = document.getElementById("changefreq_always").parentNode.parentNode.parentNode;
					changefreq_tr.style.display="none";
					priority_tr = document.getElementById("priority").parentNode.parentNode;
					priority_tr.style.display="none";
					lastmod_tr = document.getElementById("lastmod").parentNode.parentNode;
					lastmod_tr.style.display="none";
				}
				function showSidemapForm(event)
				{
					
					if (sidemapShowed)
					{
						changefreq_tr.style.display="none";
						priority_tr.style.display="none";
						lastmod_tr.style.display="none";
						sidemapShowed = false;
					}
					else
					{
						changefreq_tr.style.display="";
						priority_tr.style.display="";
						lastmod_tr.style.display="";
						sidemapShowed = true;
					}
				}
			';

		$frm->addf_checkbox("add_sitemap", "Добавить в sitemap.xml", false);
		$frm->addf_radioGroup('changefreq', 'Вероятная частота изменения этой страницы.');
		$frm->addf_radioItem('changefreq', 'always', 'always', $checked = true, $text_search = '', $another = '');
		$frm->addf_radioItem('changefreq', 'hourly', 'hourly', $checked = false, $text_search = '', $another = '');
		$frm->addf_radioItem('changefreq', 'daily', 'daily', $checked = false, $text_search = '', $another = '');
		$frm->addf_radioItem('changefreq', 'weekly', 'weekly', $checked = false, $text_search = '', $another = '');
		$frm->addf_radioItem('changefreq', 'monthly', 'monthly', $checked = false, $text_search = '', $another = '');
		$frm->addf_radioItem('changefreq', 'yearly', 'yearly', $checked = false, $text_search = '', $another = '');
		$frm->addf_radioItem('changefreq', 'never', 'never', $checked = false, $text_search = '', $another = '');
		$frm->addf_text('priority', 'Приоритет (от 0 до 1): ', $default = '0.5', $multirows = false, $arr = false);
		$frm->addf_text('lastmod', 'Дата изменения: ', $default = date('Y-m-d'), $multirows = false, $arr = false);
		teAddJSScript($sidemapFormHidder);
		$frm->setJSScript('add_sitemap', 'onclick', 'showSidemapForm();');
	}


	$n_ph = 0;
	if ($_GET['action'] == "edit" && $id > 0) {
		$r_ph = $database->query("SELECT goodphoto_file FROM " . DB_PREFIX . "goods_photos WHERE ID_GOOD=" . $id . " && goodphoto_deleted=0 order by goodphoto_pos");
		$photos = array();
		while ($row_ph = mysql_fetch_row($r_ph))
			$photos[] = $row_ph[0];
		$n_ph = count($photos);
		$frm->add_value("photo", $photos);
	}
	if (substr($rprefix, 0, 4) == 'podp') {
		$frm->addf_checkbox("podpiska", 'Отправить сообщение подписчикам', true);
	}

	// $frm->setSubmitCaption("Далее");

	/*   если значения формы уже введены   */
	if (!$frm->send()) {
		include 'goods_save.php';
								
	}
	print "</div>";
}
?>