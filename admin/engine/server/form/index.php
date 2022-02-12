<?

if (!isset($_status)) {
	header("HTTP/1.0 404 Not Found");
	die;
}
/* * ***
 *   class teForm()
  создает и обрабатывает формы...
 *
 * TDSCMS v1.0
 * tdssc@mail.ru
 * *** */

/* * ***
 * class teForm()
 * *** */
define("LIB_FORM_PATH", DEEN_FOLDERS_URL.'admin/engine/server/form/');
require_once( $CURFLD . "ajax.class.php" );
teAddCSSFile(LIB_FORM_PATH . "style.css");
teAddJSFile(LIB_FORM_PATH . "select.js");
teAddJSFile(LIB_FORM_PATH . "events.js");
teAddJSScript("var form_save_var=false;");
      //  wp_register_style('form1',DEEN_FOLDERS_URL . 'assets/css/bootstrap.min.css');
	//wp_enqueue_style('form1');
       // wp_register_script('df1',LIB_FORM_PATH . "events.js");
	//wp_enqueue_script('df1');
	
//teAddCSSFile(LIB_FORM_PATH . "css/ui-lightness/jquery-ui-1.7.2.custom.css");
//teAddJSFile(LIB_FORM_PATH . "js/jquery-1.3.2.min.js");
//teAddJSFile(LIB_FORM_PATH . "js/jquery-ui-1.7.2.custom.min.js");
       // wp_register_script('ajax_init1',DEEN_FOLDERS_URL.'admin/engine/server/form/ajax_init.js');
	//wp_register_script('ajax_init2',DEEN_FOLDERS_URL.'admin/engine/server/form/select.js');
	//wp_register_script('ajax_init3',DEEN_FOLDERS_URL.'admin/engine/server/form/events.js');
class teForm {

	var $str;
	var $name;
	var $repeat;
	var $edit;
	var $method;
	var $action;
	var $fields;
	var $input;
	var $form_title;
	var $submit_caption;
	var $formParams;
	var $params; // параметры таблицы формы
	var $langs; //Для мультиязчности
	var $n_langs; //Количество дополнительных языков
	//Выводить в двух <td> или в одной
	var $inOnetd = false;
	/*	 * ***
	 * Constructor
	 * *** */

	function teForm($name, $method, $repeat = false, $edit = false) {
		$this->name = $name;
		$this->repeat = $repeat;
		$this->edit = $edit;
		$this->method = strtolower($method);
		$this->formParams = '';
		$this->param = '';
		//teAddJSFile(LIB_FORM_PATH . "form.js");
		teAddJsScript("function ctrls_function(){document.getElementById('" . $name . "').submit();}");
		$this->langs = array();
	}

	function addf_langs($langs) {
		$this->langs = $langs;
		$this->n_langs = count($langs);
	}

	function addf_lvalue($lang, $name, $value) {
		$this->fields[$name]['lang' . $lang]['default'] = $value;
	}

	function setLJSScript($lang, $name, $action, $script) {
		$this->fields[$name]['lang' . $lang]['scripts'][$action] = $script;
	}

	function setLnet($name) {
		$this->fields[$name]['langs'] = false;
	}

	/*	 * ***
	 * Указать поле как множественное
	 * *** */

	function setFieldMultiple($name, $multiple = true) {
		$this->fields[$name]['multiple'] = $multiple;
	}

	function addf_ereg($name, $ereg = '*') {
		$this->fields[$name]['ereg'] = $ereg;
	}

	function addf_noereg($name, $ereg = '') {
		$this->fields[$name]['noereg'] = $ereg;
	}

	function addf_desc($name, $desc) {
		$this->fields[$name]['desc'] = $desc;
	}

	/*	 * ***
	 *  добавить значения
	 * *** */

	function add_value($name, $value, $add = true) {
		$value = ( is_array($value) ) ? $value : array(
			$value
		);
		if (!empty($this->fields[$name]['default']) && $add) {
			if (is_array($this->fields[$name]['default'])) {
				$this->fields[$name]['default'][] = array_merge($this->fields[$name]['default'], $value);
			} else {
				$this->fields[$name]['default'] = array_merge(array(
					$this->fields[$name]['default']
					), $value);
			}
		} else {
			$this->fields[$name]['default'] = $value;
		}
	}

	function addf_string($fieldname, $label) {
		$this->fields[$fieldname]['type'] = 12;
		$this->fields[$fieldname]['label'] = $label;
		$this->fields[$fieldname]['arr'] = false;
		$this->fields[$fieldname]['step'] = 1;
	}

	function addf_subform($fieldname, $label) {
		$this->fields[$fieldname]['type'] = 11;
		$this->fields[$fieldname]['label'] = $label;
		$this->fields[$fieldname]['arr'] = false;
		$this->fields[$fieldname]['step'] = 1;
	}

	function addSubformField($fieldname, $name, $value) {
		$this->fields[$fieldname]['default'][$name] = $value;
	}

	function addSubformGetQuery($fieldname, $type, $name, $value) {
		$this->fields[$fieldname]['getquery'][$type][$name] = $value;
	}

	/*	 * ***
	 *  <input type=text> and <textarea>
	 * *** */


	/*	 * ***
	 * function addf_text()
	 *  @param String Name of input
	 *  @param String Label of input
	 *  @param String Default value input
	 *  @param int Number of rows
	 *
	 * Add <input type=text> in form.
	 * *** */

	function addf_text($name, $label, $default = '', $multirows = false, $arr = true) {
		$this->fields[$name]['type'] = 1;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['default'] = $default;
		$this->fields[$name]['multirows'] = $multirows;
		$this->fields[$name]['arr'] = $arr;

		$this->fields[$name]['width'] = "300px";
		$this->fields[$name]['height'] = ( $multirows ) ? "7em" : "1em";
		$this->fields[$name]['step'] = 1;
		$this->fields[$name]['langs'] = true;
	}
	function addf_custom($name, $label, $default = '', $multirows = false, $arr = true) {
		$this->fields[$name]['type'] = 999;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['default'] = $default;
		$this->fields[$name]['multirows'] = $multirows;
		$this->fields[$name]['arr'] = $arr;

		$this->fields[$name]['width'] = "300px";
		$this->fields[$name]['height'] = ( $multirows ) ? "7em" : "1em";
		$this->fields[$name]['step'] = 1;
		$this->fields[$name]['langs'] = true;
	}

	function setFieldWidth($name, $w) {
		$this->fields[$name]['width'] = $w;
	}

	function setFieldHeight($name, $h) {
		$this->fields[$name]['height'] = $h;
	}

	/*	 * ***
	 *  добавляем JS-скрипты к полям
	 * *** */

	function setJSScript($name, $action, $script) {
		$this->fields[$name]['scripts'][$action] = $script;
	}

	function addJSScript($name, $action, $script) {
		if (isset($this->fields[$name]['scripts'][$action]))
			$this->fields[$name]['scripts'][$action] .= $script;
		else
			$this->fields[$name]['scripts'][$action] = $script;
	}

	/*	 * ***
	 *  <input type=password>
	 * *** */


	/*	 * ***
	 * function addf_password()
	 *  @param String Name of password
	 *  @param String Label of password
	 *
	 * Add <input type=password> in form.
	 * *** */

	function addf_password($name, $label, $check = false) {
		$this->fields[$name]['type'] = 2;
		$this->fields[$name]['label'] = (!empty($label) ) ? $label : "Введите пароль";
		$this->fields[$name]['check'] = $check;
		$this->fields[$name]['arr'] = true;
		$this->fields[$name]['step'] = 1;
	}

	function addf_passRule($name, $min = 6, $max = 10, $chars = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXWZ') {
		$this->fields[$name]['min'] = $min;
		$this->fields[$name]['max'] = $max;
		$this->fields[$name]['chars'] = $chars;
	}

	/*	 * ***
	 *  <input type=hidden>
	 * *** */

	/*	 * ***
	 * function addf_hidden()
	 *  @param String Name of input
	 *  @param String Value input
	 *
	 * Add <input type=hidden> in form.
	 * *** */

	function addf_hidden($name, $value, $arr = true) {
		$this->fields[$name]['type'] = 3;
		$this->fields[$name]['default'] = $value;
		$this->fields[$name]['arr'] = $arr;
		$this->fields[$name]['step'] = 1;
	}

	/*	 * ***
	 *  <input type=checkbox>
	 * *** */

	/*	 * ***
	 * function addf_checkbox()
	 *  @param String Name of input
	 *  @param String Label of input
	 *  @param Bool Checked
	 *
	 * Add <input type=checkbox> in form.
	 * *** */

	function addf_checkbox($name, $label, $checked = false, $text_search = '') {
		$this->fields[$name]['type'] = 4;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['checked'] = $checked;
		$this->fields[$name]['arr'] = true;
		$this->fields[$name]['step'] = 1;
		$this->fields[$name]['scripts']['onchange'] = 'enableSubmit();';
		$this->fields[$name]['scripts']['onclick'] = 'enableSubmit();';
		if (!empty($text_search))
			$this->fields[$name]['checked'] = false;
		if (!empty($text_search) && strpos($text_search, $label) !== false)
			$this->fields[$name]['checked'] = true;
	}

	function addf_checkboxGroup($name, $label) {
		$this->fields[$name]['type'] = 5;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['default'] = array();
		$this->fields[$name]['arr'] = true;
		$this->fields[$name]['step'] = 1;
	}

	function addf_checkboxItem($nameGroup, $name, $label, $checked = false, $text_search = '', $another = false) {
		$this->fields[$nameGroup]['items'][$name]['label'] = $label;
		$this->fields[$nameGroup]['items'][$name]['checked'] = $checked;
		if (!empty($text_search) && strpos($text_search, $label) !== false) {
			$this->fields[$nameGroup]['items'][$name]['checked'] = true;
			if (!empty($another)) {
				$string = substr($text_search, ( strpos($text_search, $label) + strlen($label) + 2));
				$this->input[$nameGroup . $name . '_'] = substr($string, 0, -4);
			}
		}
		$this->fields[$nameGroup]['items'][$name]['another'] = $another;
	}

	/*	 * ***
	 *  <input type=radio>
	 * *** */

	/*	 * ***
	 * function addf_radioGroup()
	 *  @param String Name of input
	 *  @param String Label of input
	 *
	 * Add group of <input type=radio> in form.
	 * *** */

	function addf_radioGroup($name, $label) {
		$this->fields[$name]['type'] = 6;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['arr'] = true;
		$this->fields[$name]['step'] = 1;
	}

	/*	 * ***
	 * function addf_radio()
	 *  @param String Name input of radio group
	 *  @param String Name of input
	 *  @param String Label of input
	 *  @param Bool Checked
	 *
	 * Add <input type=radio> in group @param 0 in form.
	 * *** */

	function addf_radioItem($nameGroup, $name, $label, $checked = false, $text_search = '', $another = '') {
		$this->fields[$nameGroup]['items'][$name]['label'] = $label;
		$this->fields[$nameGroup]['items'][$name]['checked'] = $checked;
		if (!empty($text_search) && strpos($text_search, $label) !== false)
			$this->fields[$nameGroup]['items'][$name]['checked'] = true;
		$this->fields[$nameGroup]['items'][$name]['another'] = $another;
		if (!empty($another) && !empty($text_search)) {
			$this->fields[$nameGroup]['items'][$name]['checked'] = true;
			$this->input[$nameGroup . $name . '_'] = $text_search;
		}
	}

	/*	 * ***
	 *  <select>
	 * *** */

	/*	 * ***
	 * function addf_selectGroup()
	 *  @param String Name of input
	 *  @param String Label of input
	 *
	 * Add <select> in form.
	 * *** */

	function addf_selectGroup2($name, $label, $rubric_type, $id, $id_feature, $edit = false, $arr = true) {
		$this->fields[$name]['type'] = 7;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['edit'] = $edit;
		$this->fields[$name]['rubric_type'] = $rubric_type;
		$this->fields[$name]['id'] = $id;
		$this->fields[$name]['id_feature'] = $id_feature;
		$this->fields[$name]['arr'] = $arr;
		$this->fields[$name]['step'] = 1;
		$this->fields[$name]['scripts']['onchange'] = 'enableSubmit();';
	}

	function addf_selectGroup($name, $label, $edit = false, $arr = true) {
		$this->fields[$name]['type'] = 7;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['edit'] = $edit;
		$this->fields[$name]['arr'] = $arr;
		$this->fields[$name]['step'] = 1;
		$this->fields[$name]['scripts']['onchange'] = 'enableSubmit();';
		$this->fields[$name]['items'] = array();
		/* обнуление элементов */
	}

	//Текстовое поле со списком из айакса (как у поисковиков)
	function addf_TextList($name, $label, $url_ajax, $arr = true) {
		$this->fields[$name]['type'] = 14;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['url_ajax'] = $url_ajax;
		$this->fields[$name]['width'] = "300px";
		$this->fields[$name]['arr'] = $arr;
		$this->fields[$name]['step'] = 1;
	}

	//Текстовое поле со списком из массива $array (как у поисковиков)
	function addf_TextListArray($name, $label, $array, $arr = true) {
		$this->fields[$name]['type'] = 15;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['array'] = $array;
		$this->fields[$name]['width'] = "300px";
		$this->fields[$name]['arr'] = $arr;
		$this->fields[$name]['step'] = 1;
	}

	/*	 * ***
	 * function addf_selectItem()
	 *  @param String Name input of radio group
	 *  @param String Name of input
	 *  @param String Label of input
	 *  @param Bool Checked
	 *
	 * Add value in <select> @param 0 in form.
	 * *** */

	function addf_selectItem($nameGroup, $name, $label, $checked = false) {
		$this->fields[$nameGroup]['items'][$name]['label'] = $label;
		$this->fields[$nameGroup]['items'][$name]['checked'] = $checked;
	}

	/*	 * ***
	 *  <input type=file>
	 * *** */


	/*	 * ***
	 * function addf_file()
	 *  @param String Name of input
	 *  @param String Label of input
	 *  @param int Max size of file
	 *
	 * Add <input type=file> in form.
	 * ------
	 * *** */

	function addf_file($name, $label, $old = "", $maxsize = 26214400, $folder = '', $allowExt = '', $disallowExt = '') {
		$this->fields[$name]['type'] = 8;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['old'] = $old;
		$this->fields[$name]['maxsize'] = $maxsize;
		$this->fields[$name]['arr'] = true;
		$this->fields[$name]['step'] = 1;
		$this->fields[$name]['folder'] = $folder;
		$this->fields[$name]['allowExt'] = $allowExt;
		$this->fields[$name]['disallowExt'] = $disallowExt;
		//print_r($this->fields[$name]);
	}

	function setf_require() {
		$list = func_get_args();
		foreach ($list as $name) {
			$this->fields[$name]['require'] = true;
		}
	}

	function addf_button($name, $txt, $params) {
		$this->fields[$name]['type'] = 9;
		$this->fields[$name]['label'] = $name;
		$this->fields[$name]['txt'] = $txt;
		$this->fields[$name]['params'] = $params;
		$this->fields[$name]['arr'] = true;
		$this->fields[$name]['step'] = 1;
	}

	function addf_group($name, $label, $txt = "") {
		$this->fields[$name]['type'] = 10;
		$this->fields[$name]['label'] = $label;
		$this->fields[$name]['txt'] = $txt;
		$this->fields[$name]['arr'] = true;
		$this->fields[$name]['step'] = 1;
	}

	function setAfterCaption($name, $text) {
		$this->fields[$name]['afterCaption'] = $text;
	}

	function setSubmitCaption($txt) {
		$this->submit_caption = $txt;
	}

	function addTitle($txt, $step = 1) {
		$this->form_title[$step] = $txt;
	}

	function addFormParams($params) {
		$this->formParams .= $params;
	}

	//стилевые и т.п. параметры таблицы (к примеру выравнивание по центру)
	function addParams($params) {
		$this->params .= $params;
	}

	function errorValue($name, $text) {
		$this->fields[$name]['error'] = $text;
	}

	function step() {
		$list = func_get_args();
		$i = 0;
		foreach ($list as $name) {
			if ($i == 0)
				$step = $name;
			else
				$this->fields[$name]['step'] = $step;
			$i++;
		}
	}

	/*	 * ***
	 * function send()
	 * Send HTML-code to browser
	 * *** */

	function send($viewform = false, $steps = 1) {
		global $str, $_USER, $database;

		// берем текущий шаг
		if ($this->method == "get") {
			$step = (int) @$_GET['stepstepstep'];
			$actn = @$_GET['actn'];
		} else {
			$step = (int) @$_POST['stepstepstep'];
			$actn = @$_POST['actn'];
		}
		if ($step == 0)
			$step = 1;


		if (!empty($_POST['dbid'])) {
			if (@!( $_POST['dbid'] == DB_ID )) {
				$formerr = true;
				$viewform = false;
				global $hosts;
				print "<div class='error'>Сохраняемая форма относится к другой базе!";
				print "<div>Форма была сгенерирована для базы <b><u>" . $hosts[$_POST['dbid']]['name'] . "</u></b>,";
				print "а Вы пытаетесь сохранить в базе <b><u>" . $hosts[DB_ID]['name'] . "</u></b></div>";
				print "</div>";
				print "<p align='left'>";
				print "<b>Инструкция по переходу в базу <b><u>" . $hosts[$_POST['dbid']]['name'] . "</u></b> без потери данных.</b>";
				print "<br/>1. Откройте новое окно (или вкладку) браузера.";
				print "<br/>2. Впишите в адресную строку (обычно сверху) адрес: <b>http://cprice.ddmitriev.ru/</b>.";
				print "<br/>3. Нажмите клавишу <b>Enter</b>.";
				print "<br/>4. В открывшейся системе \"Древо\" в верхней панели слева выберите базу <b>" . $hosts[DB_ID]['name'] . "</b>.";
				print "<br/>5. Закройте новое окно (вкладку).";
				print "<br/>6. Перейдите к этому окну (вкладке).";
				print "<br/>7. Нажмите кнопку <button onclick='window.location.reload( true );'>F5</button>.";
				print "</p><br/><br/>";
				print "<p align='left'>";
				print "Пока несохранённые данные:<table align='left'>";
				foreach ($_POST AS $cur_name => $cur_field) {
					print "<tr><td><b>$cur_name</b></td><td>" . ( ( is_array($cur_field) ) ? current($cur_field) : $cur_field ) . "</td></tr>";
				}
				print "</table></p>";
			}
		}

		$enter = false;
		$error_field = '';
		if (!$viewform) {
			if (( $this->method == "get" && @$_GET['frm_name'] == $this->name ) || ( $this->method == "post" && @$_POST['frm_name'] == $this->name )) {
				$viewform = false;
				$enter = true;

				if (count($this->fields) > 0)
					foreach ($this->fields AS $cur_name => $cur_field) {

						if ($this->method == "get") {
							@$this->input[$cur_name] = @$_GET[$cur_name];
						} else {

							if (@$cur_field['multiple'] == 1 && $cur_field['type'] == 8) {
								if (isset($_POST['mfiles' . $cur_name]) && isset($_POST['fold' . $cur_name]))
									$this->input[$cur_name] = array_merge($_POST['mfiles' . $cur_name], $_POST['fold' . $cur_name]);
								elseif (isset($_POST['mfiles' . $cur_name]))
									$this->input[$cur_name] = $_POST['mfiles' . $cur_name];
								else
									$this->input[$cur_name] = @$_POST['fold' . $cur_name];
							}else {
								@$this->input[$cur_name] = @$_POST[$cur_name];
							}
						}

						//if(is_array(@$this->input[$cur_name][0])) $this->input[$cur_name] = $this->input[$cur_name][0];

						$inpt = ( is_array($this->input[$cur_name]) ) ? $this->input[$cur_name][0] : $this->input[$cur_name];

						if (@$cur_field['type'] == 1) {
							$inpt = str_replace("'", "\"", $inpt);
						}

						if (@$cur_field['type'] == 8 && isset($_FILES[$cur_name]['name']) && @$cur_field['multiple'] != 1) {
							$inpt = $_FILES[$cur_name]['name'];
						}

						if (@$cur_field['type'] == 4) {
							if (@$cur_field['step'] == $step) {
								if ($this->method == "get") {
									if (isset($_GET[$cur_name]) && $_GET[$cur_name] != 'off') {
										$this->input[$cur_name] = 'on';
										$inpt = 'on';
									} else
										$this->input[$cur_name] = 'off';
								}
								else {
									if (isset($_POST[$cur_name]) && $_POST[$cur_name] != 'off') {
										$this->input[$cur_name] = 'on';
										$inpt = 'on';
									} else
										$this->input[$cur_name] = 'off';
								}
							}
							if (!isset($this->input[$cur_name]) && $cur_field['checked'] == true) {
								$this->input[$cur_name] = 'on';
								$inpt = 'on';
							}
						}
						if (@$cur_field['type'] == 5) {
							foreach ($cur_field['items'] as $curr_name => $cur) {
								if ($this->method == "get") {
									if (isset($_GET[$cur_name . $curr_name])) {
										if ($_GET[$cur_name . $curr_name] != 'off') {
											if (!empty($cur['another'])) {
												@$val = $_GET[$cur_name . $curr_name . '_'];
												if (!empty($val)) {
													$this->input[$cur_name . $curr_name . '_'] = $val;
													$inpt = $val;
												}
											} else
												$inpt = 'on';
											$this->input[$cur_name . $curr_name] = 'on';
										} else
											$this->input[$cur_name . $curr_name] = 'off';
									}
								}
								else {
									if (isset($_POST[$cur_name . $curr_name])) {
										if ($_POST[$cur_name . $curr_name] != 'off') {
											if (!empty($cur['another'])) {
												@$val = $_POST[$cur_name . $curr_name . '_'];
												if (!empty($val)) {
													$this->input[$cur_name . $curr_name . '_'] = $val;
													$inpt = $val;
												}
											} else
												$inpt = 'on';
											$this->input[$cur_name . $curr_name] = 'on';
										} else
											$this->input[$cur_name . $curr_name] = 'off';
									}
								}
								/*
								  if(!isset($this->input[$cur_name.$curr_name]) && ($cur['checked']==true || in_array($curr_name,$cur_field['default'])))
								  {
								  $this->input[$cur_name.$curr_name] = 'on';
								  $inpt = 'on';
								  }
								 */
							}
						}
						if (@$cur_field['type'] == 6) {
							foreach ($cur_field['items'] as $curr_name => $cur) {
								if ($inpt == $curr_name && !empty($cur['another'])) {
									$inpt = ( $this->method == "get" ) ? $_GET[$cur_name . $curr_name . '_'] : $_POST[$cur_name . $curr_name . '_'];
									$this->input[$cur_name . $curr_name . '_'] = $inpt;
								}
							}
						}

						$err[$cur_name] = 0;

						if (@$cur_field['step'] == $step || $step == $steps) {
							if (!empty($inpt)) {
								$inpt = trim($inpt);
								if (!empty($cur_field['ereg'])) {
									if (!eregi($cur_field['ereg'], $inpt)) {
										$err[$cur_name] = 2;
										$this->fields[$cur_name]['error'] = "Поле не соответствует нужному формату";
									}
								}
								if (!empty($cur_field['noereg'])) {
									if (eregi($cur_field['noereg'], $inpt)) {
										$err[$cur_name] = 2;
										$this->fields[$cur_name]['error'] = "Поле такого формата не допускается";
									}
								}
								if (empty($inpt)) {
									// unset($this->input[$cur_name][$idid]);
									//unset($this->fields[$cur_name]['default'][$idid]);
									@$this->fields[$cur_name]['default'][$idid] = "";
									unset($cur_field['error']);
								}

								/* во изб.глюков замена ' на " */
								$inpt = str_replace("'", "\"", $inpt);
							}

							if (@$cur_field['type'] == 2 and @ $cur_field['check']) {

								if ($this->method == "get") {
									$check = @$_GET[$cur_name . "_check"];
								} else {
									$check = @$_POST[$cur_name . "_check"];
								}

								if ($inpt != trim($check)) {
									$err[$cur_name] = 1;
									$cur_field['error'] = "Пароли не совпадают";
									$this->fields[$cur_name]['error'] = "Пароли не совпадают";
								}
							}

							if (@$cur_field['require'] && empty($inpt) && @$cur_field['type'] != 8) {
								$err[$cur_name] = 1;
								$this->fields[$cur_name]['error'] = "Поле обязательно для заполнения";
								$cur_field['error'] = "Поле обязательно для заполнения";
							} elseif (@$cur_field['require'] && empty($inpt) && $cur_field['type'] == 8) {
								$err[$cur_name] = 1;
								$this->fields[$cur_name]['error'] = "Вам необходимо указать файл";
								$cur_field['error'] = "Вам необходимо указать файл";
							}
							if (isset($cur_field['error'])) {
								$err[$cur_name] = 1;
							}
							if ($err[$cur_name] > 0) {
								$step = $cur_field['step'];
								$enter = false;
							}
						}
						if (!is_array($this->input[$cur_name])) {
							$this->input[$cur_name] = array(
								$this->input[$cur_name]
							);
						}

						if (!@$cur_field['multiple'] && array_key_exists(0, $this->input[$cur_name])) {
							$this->input[$cur_name] = $this->input[$cur_name][0];
						} else {
							
						}

						$this->input[$cur_name] = $this->input[$cur_name];
						if ($err[$cur_name] > 0 && empty($error_field)) {
							$error_field = $cur_name . '_0';
							if ($cur_field['type'] == 8 || ( $cur_field['type'] == 1 && $cur_field['multirows'] )) {
								$error_field = $cur_name;
							}
						}
					}
			} else {
				$viewform = true;
			}
		}

		if ($actn != 'save')
			$viewform = true;
		if (!$enter)
			$viewform = true;
		if ($step == -1) {
			$viewform = true;
			$step = 1;
		}
		if (!$viewform) {
			if ($step < $steps) {
				$step++;
				$viewform = true;
			}
		}

		if ($viewform) {
			global $_template;
			if (!empty($error_field))
				$_template->addToVar("param_body", " onload='document." . $this->name . "." . $error_field . ".focus();'");
			teAddJSScript("
				//window.attachEvent('onbeforeunload', confirmnotsave);
			");
			$param = '';

			if (count($this->fields) > 0) {
				foreach ($this->fields as $fld) {
					if (@$fld['type'] == 8) {
						$param .= " enctype=\"multipart/form-data\"";
						break;
					}
				}
				if ($steps > 1)
					print '<style type="text/css">
						#steps {position:relative; height:48px; overflow: auto; margin:2px auto;}
                        #steps input {background-color:#ffffff;cursor:pointer;font-size:8pt;color:#000080;text-decoration:none;height:25px;margin:2px;float:left;padding:3px; border: solid 1px #7F9DB9;display:inline;}
                        #steps input.sel {cursor:default;background-color:#C0C0C0;}
                        #steps input:hover {background-color:#C0C0C0;color:#000080;}
					</style>';
				print "<form id='" . $this->name . "' name='" . $this->name . "' method='" . $this->method . "' " . $param . ">";
				if ($steps < 2)
					print $this->form_title[$step];
				@print "<input type='hidden' id='dbid' name='dbid' value='" . DB_ID . "' />";
				print "<input type='hidden' id='frm_name' name='frm_name' value='" . $this->name . "' />";
				print "<input type='hidden' id='stepstepstep' name='stepstepstep' value='" . $step . "' />";
				print "<input type='hidden' id='repeat' name='repeat' value='0' />";
				print "<input type='hidden' id='edit' name='edit' value='0' />";
				print "<input type='hidden' id='actn' name='actn' value='save' />";
				/* Возможность выбора значений характеристик из отдельного списка вместо выпадающего */
				print "<input type='hidden' id='rubric_multi' name='rubric_multi' value='' />";
				print "<input type='hidden' id='rubric_type' name='rubric_type' value='' />";
				print "<input type='hidden' id='id_good' name='id_good' value='' />";
				print "<input type='hidden' id='id_feature' name='id_feature' value='' />";
				if ($steps > 1) {
					teAddJSFile(LIB_FORM_PATH . "flexcroll.js");
					teAddJSScript("CSBfleXcroll('steps');");
					$width = 0;
					for ($i = 0; $i < $steps; $i++)
						$width += strlen($this->form_title[$i + 1]) * 7 + 16;
					print '<div id="steps" style="width:' . $width . 'px;">';
					for ($i = 0; $i < $steps; $i++) {
						if ($i == ( $step - 1 ))
							print '<input type="button" value="' . $this->form_title[$i + 1] . '" class="sel" /> ';
						else
							print '<input type="button" onclick="stepstepstep.value=' . ( $i == 0 ? -1 : $i ) . ';document.getElementById(\'' . $this->name . '\').submit();" value="' . $this->form_title[$i + 1] . '" /> ';
					}
					print '<div style="clear: both;"></div></div>';
				}
				/*				 * ********************************************************************************* */
				print "<table class='form-table' " . $this->params . ">";
				print "<tr>";
				print "<td class='form_body'>";
				print "<table align='center' cellpadding=6 " . $this->formParams . " class='table-bordered good-add-table'>";


				// разбор [] в именах, для мультивкладок
				if ($this->n_langs > 0) {
					print '<tr class="form_field">
						<td class="form_left">Язык</td><td class="form_right"><b>Русский</b></td>';
					foreach ($this->langs as $lang => $lname) {
						print '<td class="form_right"><b>' . $lname . '</b></td>';
					}
					print '</tr>';
				}
				$fields = array();
				foreach ($this->fields as $cur_name => $cur_field) {
					/* temporarry */
					//					if(@$cur_field['step']==$step){
					if (ereg("(\[[0-9]*\])", $cur_name, $numval)) {
						$numval = substr($numval[0], 1, -1);

						$newname = str_replace("[" . $numval . "]", "", $cur_name);


						$default = @$this->fields[$cur_name]['default'];
						if (!is_array(@$default))
							$default = array(
								$default
							);

						$default1 = @$fields[$newname]['default'];

						$fields[$newname] = $this->fields[$cur_name];

						$fields[$newname]['default'] = ( empty($default1) ) ? array(
							$numval => $default
							) : array_merge(@$default, @$default1);
					}
					else {

						$fields[$cur_name] = $this->fields[$cur_name];
					}
					/* /temporarry */
					//					}
				}
				$this->fields = $fields;
				unset($fields);
				/**/
				//  /разбор [] в именах, для мультивкладок


				if (@$addfldscnt < 1) {
					$addfldscnt = 0;
					/* 					foreach($this->fields AS $nm => $cntnt){
					  if(@$cntnt['step']==$step){
					  $addfldscnta = (empty($this->input[$nm]))?array($cntnt['default']):$this->input[$nm];
					  $addfldscnt = ($addfldscnt>count($addfldscnta))?$addfldscnt:count($addfldscnta);
					  }
					  } */
					if ($actn != "addflds") {
						$actn = "showflds";
					} else {
						$addfldscnt--;
					}
					if (count($addfldscnt) == 0)
						$addfldscnt = 1;
				}
				if ($actn == "addflds")
					$addfldscnt++;

				$addflds = 0;
				while (( $actn == "addflds" && $addflds <= $addfldscnt ) || ( $actn == "showflds" && $addflds < $addfldscnt ) || ( $actn != "addflds" && $addflds == 0 )) {
					$addflds++;
					if ($actn == "addflds")
						$fldsprearr = "[$addflds]";
					else
						$fldsprearr = "";

					if ($addfldscnt > 1 || $actn == "addflds")
						print "<tr><td colspan='2' align='left'><br><div style='font-size:1.1em'><b><u>Запись № $addflds</u></b></div></td></tr>";


					//if(!empty($_GET['ajax'])){
					//	teAddJSScript("var subformarr = new Array();");
					//}
					$i_radio = 0;
					$maxsize = false;
					foreach ($this->fields as $cur_name => $cur_field) {
						//if(!empty($_GET['ajax'])){
						//	print teGetJSScript("subformarr.join('$cur_name');alert('$cur_name');");
						//}
						if ($cur_field['arr']) {
							if (!empty($cur_field['default']) && !is_array(@$cur_field['default'])) {
								$cur_field['default'] = array(
									$cur_field['default']
								);
							}


							unset($value);
							unset($thinpt);

							if (!empty($this->input[$cur_name]))
								$thinpt = $this->input[$cur_name];

							if (@$cur_field['step'] == $step) {
								if (( $actn == "addflds" || $actn == "showflds" ) && $addfldscnt > 1) {
									if (isset($thinpt[$addflds])) {
										$thinpt = $thinpt[$addflds];
									}
								}


								if (( $addfldscnt == 1 && $addflds == 2 ) || ( $addflds == $addfldscnt + 1 && $addfldscnt != 0 ))
									$thinpt = "";
							}
							//print $addflds."---".($addfldscnt+1)."<br>";

							if (is_array(@$thinpt)) {
								$value = $thinpt;
							} else {
								if (isset($thinpt)) {
									$value = array(
										$thinpt
									);
								} else {
									/* глюк со временным полем где-то тут */
									if (!empty($cur_field['default'])) {
										$value = $cur_field['default'];
									} else {
										$value = array(
											""
										);
									}
								}
							}
						} elseif (isset($cur_field['default']))
							$value = $cur_field['default'];
						else
							$value = "";
						if ($cur_field['type'] == 999){
							$value = "--";
						}
						if ($cur_field['type'] == 4)
							@$value = $this->input[$cur_name];
						if ($cur_field['type'] == 5) {
							$value = array();
							foreach ($cur_field['items'] as $curr_name => $cur) {
								@$value[$cur_name . $curr_name] = $this->input[$cur_name . $curr_name];
								@$value[$cur_name . $curr_name . '_'] = $this->input[$cur_name . $curr_name . '_'];
							}
						}
						$value2 = array();
						if ($cur_field['type'] == 6) {
							foreach ($cur_field['items'] as $curr_name => $cur) {
								@$value2[$cur_name . $curr_name . '_'] = $this->input[$cur_name . $curr_name . '_'];
								if ($cur['checked'])
									$value = $curr_name;
							}
						}
						/**  steps  * */
						//					print '<!-- '.$cur_name.' '.$cur_field['type'].' -->';
						$old_type = $cur_field['type'];
						if (@$cur_field['step'] == $step) {
							$type = $cur_field['type'];
						} else {
							//	если дру-гой шаг
							$type = 3;
							unset($cur_field['require']);
						}
						///print  $cur_field['step']."=".$step." => ".$type."<br>";
						/**  /steps  * */
						if ($type == 3) {
							//if($addflds==1){ /* !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! */
							// $value = ((!empty($this->input[$cur_name]))?$this->input[$cur_name]:@$cur_field['value']);
							if (is_array($value)) {
								foreach ($value AS $num => $val) {
									if (is_array($val)) {
										foreach ($val AS $num1 => $val1) {
											if (!empty($val1))
												print "<input  type='hidden' name='" . $cur_name . "[$num][$num1]' value='" . $val1 . "' />";
										}
									}
									elseif (!empty($val)) {
										if ($old_type == 5)
											print "<input type='hidden' id='" . $num . "' name='" . $num . "' value='" . $val . "' />";
										else
											print "<input type='hidden' id='" . $cur_name . "_$num' name='" . $cur_name . "[$num]' value='" . ( ( $step > 1 ) ? str_replace(array(
														"'",
														"\\"
														), array(
														"`",
														""
														), $val) : $val ) . "' />";
									}
								}
							}
							elseif (!empty($value)) {
								print "<input type='hidden' id='" . $cur_name . "' name='" . $cur_name . "' value='" . $value . "' />";
							}
							foreach ($value2 AS $num => $val) {
								if (!empty($val))
									print "<input type='hidden' id='" . $num . "' name='" . $num . "' value='" . $val . "' />";
							}
						}
						else {
							$tr_class = "form_field";
							if (!empty($err[$cur_name]))
								$tr_class .= "_err";
							print "<tr class='" . $tr_class . "'>";
							//if($cur_field['type']!=10){
							print "<td data-type='".$cur_field['type']."' " . ( $cur_field['type'] == 12 ? "class='form_center' colspan='2'" : "class='form_left'" ) . ">";

							$label = $cur_field['label'];

							if ($cur_field['type'] == 4) {
								$label = "<label for='" . $cur_name . "'>" . $label . "</label>";
							}

							if (@$cur_field['require']) {
								if ($steps < 2)
									print "<div class='form_field_require'>" . $label . "<span class='form_field_require'>*</span></div>";
								else
									print $label . "<span class='form_field_require'>*</span>";
							}
							else {
								print $label;
								//print "<div class='form_field_label'>".$label."</div>";
							}

							if (!empty($cur_field['desc']))
								print "<div class='form_field_desc'>" . $cur_field['desc'] . "</div>";

							if (isset($cur_field['error'])) {
								print "<div class='form_field_desc'><span style='color:red;'>" . $cur_field['error'] . "<span></div>";
							}
							if ($cur_field['type'] != 12) {
								if($this->inOnetd){
									print "<br><a name='" . $cur_name . "'></a>";
								}else {
									print "</td>";
								print "<td class='form_right'><a name='" . $cur_name . "'></a>";
								}
								
								
							}
							//} else {
							//	print "<td colspan=2 class='form_all'>";
							//}
							//print_r($value);
							// print_r($value);
							//if(count($value)==0) $value = array(0);
							//print "dft: "; print_r(@$cur_field['default']);
							//print "inpt: "; print_r(@$value);

							$out_langs = false;
							$print = "";
							if (@$cur_field['multiple'])
								$print_ = "";
							switch ($cur_field['type']) {

								case 1:
									if (empty($value) && !empty($cur_field['default'])) {
										$value = $cur_field['default'];
									}
									if ($cur_field['multirows']) {
										$ic = 0;
										if ($steps > 1)
											@$value[0] = stripslashes($value[0]);
										@$page = $_GET['pg'];

										//curbase();
										$print .= "<textarea onchange='enableSubmit();'" . ( $page == 'goods' ? " class='goods good-textarea'" : "" ) . " id='" . $cur_name . "' name='" . $cur_name . "{$fldsprearr}' style=\"width:" . $cur_field['width'] . ";height:" . $cur_field['height'] . ";\">" . @$value[0] . "</textarea>";
										if ($cur_field['langs'])
											foreach ($this->langs as $lang => $lname) {
												$out_langs = true;
												@$value = $cur_field['lang' . $lang]['default'];
												if (is_array($value))
													@$value = $value[0];
												$print .= "</td><td class='form_right'><textarea  data-line='1078' onchange='enableSubmit();' id='" . $cur_name . $lang . "' name='" . $cur_name . $lang . "{$fldsprearr}' style=\"width:" . $cur_field['width'] . ";height:" . $cur_field['height'] . ";\">" . $value . "</textarea>";
											}
									}
									else {
										$onChange = "enableSubmit();";
										if (!empty($cur_field['ereg'])) {
											$onChange .= "var rg = /" . $cur_field['ereg'] . "/; if(!rg.test(this.value)) alert(\"Не верное значение\");";
										}
										//$print .= _r($str);
										if (@$cur_field['require']) {
											$onChange .= "if(this.value.replace(\" \",\"\")==\"\") alert(\"Поле обязательно к заполнению\");";
										}
										@$cur_field['scripts']['onChange'] .= $onChange;

										// суём пользовательские cкрипты

										$scripts = "";
										if (!@$cur_field['multiple'])
											foreach ($cur_field['scripts'] AS $action => $script) {
												$scripts .= " " . $action . "='" . str_replace("\'", "\\'", $script) . "' ";
											}

										$ic = 0;
										if ($cur_field['arr'])
											foreach ($value AS $num => $val) {
												if (!( $ic > 0 && $val == '' )) {
													if (( ++$ic ) > 1)
														$print .= "<br>";
													$print .= "<input type='text' id='" . $cur_name . "_$num' name='" . $cur_name . "{$fldsprearr}[$num]' value='" . str_replace(array(
															"'",
															"\\"
															), array(
															"`",
															""
															), $val) . "' " . $scripts . " onkeyup='enableSubmit();' style=\"width:" . $cur_field['width'] . ";\" />";
												}
											} else
											$print .= "<input type='text' id='" . $cur_name . "' name='" . $cur_name . "' value='" . str_replace(array(
													"'",
													"\\"
													), array(
													"`",
													""
													), $value) . "' " . $scripts . " onkeyup='enableSubmit();' style=\"width:" . $cur_field['width'] . ";\" />";

										if (@$cur_field['multiple'])
											$print_ = "<input type=text id=" . $cur_name . " name=" . $cur_name . "{$fldsprearr} style=width:" . $cur_field['width'] . "; onchange='enableSubmit();' />";

										if ($cur_field['langs'])
											foreach ($this->langs as $lang => $lname) {
												$scripts = "";
												if (!@$cur_field['multiple'] && isset($cur_field['lang' . $lang]['scripts']))
													foreach ($cur_field['lang' . $lang]['scripts'] AS $action => $script) {
														$scripts .= " " . $action . "='" . str_replace("\'", "\\'", $script) . "' ";
													}

												$out_langs = true;
												$print .= "</td><td class='form_right'>";
												@$value = $cur_field['lang' . $lang]['default'];
												if (!is_array($value))
													$value = array(
														$value
													);
												if (!isset($value[0]))
													$value[0] = '';
												$ic = 0;
												if ($cur_field['arr'])
													foreach ($value AS $num => $val) {
														if (!( $ic > 0 && $val == '' )) {
															if (( ++$ic ) > 1)
																$print .= "<br>";
															$print .= "<input type='text' id='" . $cur_name . $lang . "_$num' name='" . $cur_name . $lang . "{$fldsprearr}[$num]' value='" . str_replace(array(
																	"'",
																	"\\"
																	), array(
																	"`",
																	""
																	), $val) . "' " . $scripts . " onkeyup='enableSubmit();' style=\"width:" . $cur_field['width'] . ";\" />";
														}
													} else
													$print .= "<input type='text' id='" . $cur_name . $lang . "' name='" . $cur_name . $lang . "' value='" . str_replace(array(
															"'",
															"\\"
															), array(
															"`",
															""
															), $value) . "' " . $scripts . " onkeyup='enableSubmit();' style=\"width:" . $cur_field['width'] . ";\" />";

												if (@$cur_field['multiple'])
													$print_ .= "<input type=text id=" . $cur_name . $lang . " name=" . $cur_name . $lang . "{$fldsprearr} style=width:" . $cur_field['width'] . "; onchange='enableSubmit();' />";
											}
									}
									break;

								case 2:
									$print .= "<input type='password' id='" . $cur_name . "' name='" . $cur_name . "{$fldsprearr}' onchange='enableSubmit();' />";
									if ($cur_field['check']) {
										$s = "Повторите пароль";
										if (@$cur_field['require']) {
											$s = "<div class='form_field_require'>" . $s . "<span class='form_field_require'>*</span></div>";
										}
										$print .= "</td></tr><tr><td class='form_left'>" . $s . "</td><td class='form_right'><input type='password' id='" . $cur_name . "_check' name='" . $cur_name . "_check{$fldsprearr}' onchange='enableSubmit();' />";
									}
									break;

								case 4:
									$value1 = "";
									if (( $cur_field['checked'] && empty($value) ) || $value == "on")
										$value1 = "checked";
									$scripts = "";
									foreach ($cur_field['scripts'] AS $action => $script) {
										$scripts .= " " . $action . "='" . str_replace("\'", "\\'", $script) . "' ";
									}
									$print .= "<input type='checkbox' id='" . $cur_name . "' name='" . $cur_name . "{$fldsprearr}' " . $value1 . " " . $scripts . " />";
									break;

								case 5:
									$print .= "<div style='padding-bottom:4px;'><input type='checkbox' id='" . $cur_name . "' name='" . $cur_name . "' onclick='if(this.checked)$(this).closest(\"tr\").find(\"input\").attr(\"checked\",\"checked\");else $(this).closest(\"tr\").find(\"input\").attr(\"checked\",\"\");' /><label for='" . $cur_name . "'><b>Выделить все/Снять выделение</b></label></div>";
									foreach ($cur_field['items'] as $curr_name => $cur) {
										$valuet = "";
										if (( $this->method == "get" && @$_GET['frm_name'] == $this->name ) || ( $this->method == "post" && @$_POST['frm_name'] == $this->name ))
											@$valuet = $value[$cur_name . $curr_name];
										elseif ($cur['checked'] || in_array($curr_name, $cur_field['default']))
											$valuet = "on";
										$value1 = "";
										if ($valuet == "on") {
											$value1 = "checked";
										}
										if (!empty($cur['another'])) {
											$print .= "<div style='padding-bottom:4px;'><input type='checkbox' id='" . $cur_name . $curr_name . "' name='" . $cur_name . $curr_name . "' " . $value1 . " onchange='enableSubmit();' onclick='enableSubmit();if(this.checked){document.getElementById(\"" . $cur_name . $curr_name . "_\").style.display=\"block\";} else document.getElementById(\"" . $cur_name . $curr_name . "_\").style.display=\"none\";' /><label for='" . $cur_name . $curr_name . "'>" . $cur['label'] . "</label></div>";
											$print .= "<div id='" . $cur_name . $curr_name . "_' " . ( empty($value1) ? "style='display:none'" : "" ) . "><input type=text name='" . $cur_name . $curr_name . "_' style='width:" . $cur['another'] . "px;' value='" . @$value[$cur_name . $curr_name . '_'] . "' /></div>";
										} else
											$print .= "<div style='padding-bottom:4px;'><input type='checkbox' id='" . $cur_name . $curr_name . "' name='" . $cur_name . $curr_name . "' " . $value1 . " onchange='enableSubmit();' onclick='enableSubmit();' /><label for='" . $cur_name . $curr_name . "'>" . $cur['label'] . "</label></div>";
									}
									break;

								case 6:
									$jss_another = '';
									foreach ($cur_field['items'] as $curr_name => $cur) {

										$value1 = "";
										if (( $cur['checked'] ) || ( $value == $curr_name ) || ( is_array($value) && $value[0] == $curr_name )) {
											$value1 = "checked";
										}
										if (!empty($cur['another'])) {
											$jss_another = "if(document.getElementById(\"" . $cur_name . "_" . $curr_name . "\").checked){document.getElementById(\"" . $cur_name . $curr_name . "_\").style.display=\"block\";} else document.getElementById(\"" . $cur_name . $curr_name . "_\").style.display=\"none\";";
											$print .= "<div style='padding-bottom:4px;'><input type='radio' id='" . $cur_name . "_" . $curr_name . "' name='" . $cur_name . "{$fldsprearr}' value=\"" . $curr_name . "\" " . $value1 . " onchange='enableSubmit();' onclick='enableSubmit();showDiv" . $i_radio . "();' /><label for='" . $cur_name . "_" . $curr_name . "'>" . $cur['label'] . "</label></div>";
											$print .= "<div id='" . $cur_name . $curr_name . "_' " . ( empty($value1) ? "style='display:none'" : "" ) . "><input type=text name='" . $cur_name . $curr_name . "_' style='width:" . $cur['another'] . "px;' value='" . @$value2[$cur_name . $curr_name . '_'] . "' /></div>";
										} else
											$print .= "<div style='padding-bottom:4px;'><input type='radio' id='" . $cur_name . "_" . $curr_name . "' name='" . $cur_name . "{$fldsprearr}' value=\"" . $curr_name . "\" " . $value1 . " onchange='enableSubmit();' onclick='enableSubmit();showDiv" . $i_radio . "();' /><label for='" . $cur_name . "_" . $curr_name . "'>" . $cur['label'] . "</label></div>";
									}
									if (!empty($jss_another))
										$jss_another = 'function showDiv' . $i_radio . '(){' . $jss_another . '}';
									else
										$jss_another = 'function showDiv' . $i_radio . '(){return true;}';
									teAddJSScript($jss_another);
									$i_radio++;
									break;

								case 7:
									// шаблон для новых
									if (@$cur_field['multiple']) {
										if ($cur_field['edit']) {

											$print_ = "<input type='text' id='" . $cur_name . "' name='" . $cur_name . "{$fldsprearr}' class='select' onfocus='sel_show(\"" . $cur_name . "_items\")' onchange='enableSubmit();'/>";
											$print_ .= "<br><div class='sel_items_h' id='" . $cur_name . "_items'>";
											foreach ($cur_field['items'] as $curr_name => $cur) {
												$print_ .= "<a href='#' onmousedown='sel_cur(this,\"" . $cur_name . "\")'>" . $cur['label'] . "</a>";
											}
											$print_ .= "</div>";
										} else {
											$print_ = "<select id=" . $cur_name . " name=" . $cur_name . "{$fldsprearr} onchange='enableSubmit();' onclick='enableSubmit();' " . ( isset($cur_field['width']) ? "style=\"width:" . $cur_field['width'] . ";\"" : "" ) . ">";
											$print_ .= "<option value=''> </option>";
											if (isset($cur_field['items'])) {
												foreach ($cur_field['items'] as $curr_name => $cur) {
													$print_ .= "<option value='" . $curr_name . "'>" . $cur['label'] . "</option>";
												}
											}
											$print_ .= "</select>";
										}
									}

									/* if( empty($value) && !empty($cur_field['default']) ){
									  $value = $cur_field['default'];
									  }/* */

									// старые
									if ($cur_field['edit']) {
										$ic = 0;
										foreach ($value AS $num => $val) {
											if (!( $ic > 0 && empty($val) )) {
												if (( ++$ic ) > 1)
													$print .= "<br>";
												$print3 = '';
												$values = '';
												foreach ($cur_field['items'] as $curr_name => $cur) {
													$print3 .= "<a href='#' onmousedown='sel_cur(this,\"" . $cur_name . "_$num\")'>" . $cur['label'] . "</a>";
													if ($cur['checked'] || $val == $curr_name)
														$values = $cur['label'];
												}
												$print .= "<div style='position:relative'><input type='text' id='" . $cur_name . "_$num' name='" . $cur_name . "{$fldsprearr}[$num]' class='select' value='" . $val . "' onchange='enableSubmit();' /><a href='#' onclick='sel_show(\"" . $cur_name . "_" . $num . "_items\");return false;' style='display:block;width:15px;height:15px;position:absolute;top:1px;right:4%;z-index:10;'></a>";
												$print .= "<br><div class='sel_items_h' id='" . $cur_name . "_" . $num . "_items'>";
												$print .= $print3;
												$print .= "</div>";
												$print .= "</div>";
											}
										}
									}
									else {
										$scripts = "";
										foreach ($cur_field['scripts'] AS $action => $script) {
											$scripts .= " " . $action . "='" . str_replace("\'", "\\'", $script) . "' ";
										}
										$ic = 0;
										if ($cur_field['arr'])
											foreach ($value AS $num => $val) {
												if (!( $ic > 0 && empty($val) )) {
													if (( ++$ic ) > 1)
														$print .= "<br>";
													$print .= "<select id='" . $cur_name . "_$num' name='" . $cur_name . "{$fldsprearr}[$num]' onclick='enableSubmit();'" . $scripts . " " . ( isset($cur_field['width']) ? "style=\"width:" . $cur_field['width'] . ";\"" : "" ) . ">";
													$print .= "<option value=''> </option>";
													if (isset($cur_field['items'])) {
														foreach ($cur_field['items'] as $curr_name => $cur) {
															if ($cur['checked'] || $val == $curr_name) {
																$v = "selected";
															} else {
																$v = "";
															}
															$print .= "<option value='" . $curr_name . "' " . $v . ">" . $cur['label'] . "</option>";
														}
													}
													$print .= "</select>";
												}
											} else {
											$print .= "<select id='" . $cur_name . "' name='" . $cur_name . "' onclick='enableSubmit();'" . $scripts . ">";
											$print .= "<option value=''> </option>";
											foreach ($cur_field['items'] as $curr_name => $cur) {
												if ($cur['checked'] || $value == $curr_name) {
													$v = "selected";
												} else {
													$v = "";
												}
												$print .= "<option value='" . $curr_name . "' " . $v . ">" . $cur['label'] . "</option>";
											}
											$print .= "</select>";
										}
									}
									break;
								case 14:
									if (@$cur_field['multiple']) { //$cur_field['width'] -= 20;
										//$cur_field['width'] = $cur_field['width'].'px';
										$print_ = "<input type='text' id='" . $cur_name . "' name='" . $cur_name . "{$fldsprearr}' onkeyup='sel_show_ajax(\"" . $cur_name . "_new_{new}\",\"" . $cur_field['url_ajax'] . "\",{new})' style=\"width:" . $cur_field['width'] . ";\" onchange='enableSubmit();' />";
										$print_ .= "<br><div class='sel_items_h' id = '" . $cur_name . "_new_{new}_items'>";
										$print_ .= "</div>";
									}

									$ic = 0;
									foreach ($value AS $num => $val) {
										if (!( $ic > 0 && empty($val) )) {
											if (( ++$ic ) > 1)
												$print .= "<br>";
											$print .= "<input type='text' id='" . $cur_name . "_$num' name='" . $cur_name . "{$fldsprearr}[$num]' onkeyup='sel_show_ajax(\"" . $cur_name . "_" . $num . "\",\"" . $cur_field['url_ajax'] . "\",0)' value='" . $val . "' onchange='enableSubmit();' style=\"width:" . $cur_field['width'] . ";\" />";
											$print .= "<br><div class='sel_items_h' id='" . $cur_name . "_" . $num . "_items'>";
											$print .= "</div>";
										}
									}
									break;

								case 15:
									$jss_arr = "";
									$br_arr = "";
									$select = "";
									foreach ($cur_field['array'] as $item) {
										$jss_arr .= $br_arr . "'" . str_replace("'", "\'", $item) . "'";
										$select .= "<a href='#" . $cur_name . "' onmousedown='sel_cur(this,\"" . $cur_name . "_new_{new}\")'>" . $item . "</a>";
										$br_arr = ', ';
									}
									if (@$cur_field['multiple']) {
										//$cur_field['width'] -= 20;
										//$cur_field['width'] = $cur_field['width'].'px';
										$print_ = "<input type='text' id='" . $cur_name . "' name='" . $cur_name . "{$fldsprearr}' onkeyup='sel_show_list(\"" . $cur_name . "_new_{new}\"," . $cur_name . "_arr)' onclick='sel_show_list_all(\"" . $cur_name . "_new_{new}\"," . $cur_name . "_arr)' style=\"width:" . $cur_field['width'] . ";\" onchange='enableSubmit();' />";
										$print_ .= "<br><div class='sel_items_h' id = '" . $cur_name . "_new_{new}_items'>";
										$print_ .= $select;
										$print_ .= "</div>";
									}
									$jss_arr = "var " . $cur_name . "_arr = [$jss_arr];";
									teAddJSScript($jss_arr);
									$ic = 0;
									foreach ($value AS $num => $val) {
										if (!( $ic > 0 && empty($val) )) {
											if (( ++$ic ) > 1)
												$print .= "<br>";
											$print .= "<input type='text' id='" . $cur_name . "_$num' name='" . $cur_name . "{$fldsprearr}[$num]' onkeyup='sel_show_list(\"" . $cur_name . "_" . $num . "\"," . $cur_name . "_arr)' onclick='sel_show_list_all(\"" . $cur_name . "_" . $num . "\"," . $cur_name . "_arr)' value='" . $val . "' onchange='enableSubmit();' style=\"width:" . $cur_field['width'] . ";\" />";
											$print .= "<br><div class='sel_items_h' id='" . $cur_name . "_" . $num . "_items' >";
											foreach ($cur_field['array'] as $item) {
												$print .= "<a href='#" . $cur_name . "' onmousedown='sel_cur(this,\"" . $cur_name . "_$num\")'>" . $item . "</a>";
											}
											$print .= "</div>";
										}
									}
									break;
								case 8:
									//echo 'file';
									//print_r($cur_field);
									if (@$cur_field['multiple']) {
										teAddCSSFile(DEEN_FOLDERS_URL."assets/js/fileupload/fileuploader.css");
										teAddJSFile(DEEN_FOLDERS_URL."assets/js/fileupload/fileuploader.js");
										$aExt = '';
										if (!empty($cur_field['allowExt'])) {
											$aExt = str_replace("'", "", $cur_field['allowExt']);
										}
										$url = DEEN_FOLDERS_URL;
										
										$jss = <<<TXT
	 
window.onload = function () {
	  // alert(812);ajaxurl+"?pg=ajax&action=wp_ajax
	console.log('good uploader 812');
            var uploader{$cur_name} = new qq.FileUploader({
                element: document.getElementById('$cur_name'),
                elname: '$cur_name',
                action: ajaxurl+'?pg=upload&action=wp_ajax&aExt={$aExt}&dExt={$cur_field['disallowExt']}&size={$cur_field['maxsize']}&folde2r={$cur_field['folder']}',
            	sizeLimit: {$cur_field['maxsize']},
            	allowedExtensions: [{$cur_field['allowExt']}],
	            minSizeLimit: 10,
                debug: true
             });
};
TXT;
										teAddJSScript($jss);

										print '<div></div>
										       <div id="' . $cur_name . '">|--></div>';
										$k = 0;
										foreach ($value as $val) {
											if (!empty($val)) {
												print '
												<div>
												 
												<a target=_blank href="' . str_replace(DATA_FLD, URLDATA_FLD, $cur_field['folder'] . $val) . '">' . $val . '</a> 
												<input type="checkbox" id="fdel' . $cur_name . '' . $k . '" name="fdel' . $cur_name . '[' . $k . ']" value="' . $val . '" /><label for="fdel' . $cur_name . '' . $k . '">удалить</label>
												<input type="hidden" name="fold' . $cur_name . '[' . $k . ']" value="' . $val . '" />
												</div>';
												$k++;
											}
										}
									} else {
										//echo '--->';
										if (!$maxsize) {
											$print .= "<input type='hidden' name='MAX_FILE_SIZE' value='" . $cur_field['maxsize'] . "'>";
											$maxsize = true;
										}
										$ic = 0;
										foreach ($value AS $num => $val) {
											if (!( $ic > 0 && empty($val) )) {
												if (( ++$ic ) > 1)
													$print .= "<br>";
												$print .= "<input type='file' id='" . $cur_name . "' name='" . $cur_name . "{$fldsprearr}' onchange='enableSubmit();' onclick='enableSubmit();' />";
												if (file_exists($cur_field['folder'] . $val) && !is_dir($cur_field['folder'] . $val)) {
													$print .= " <a target=_blank href='" . str_replace(DATA_FLD, URLDATA_FLD, $cur_field['folder'] . $val) . "'>Просмотр текущего файла</a>&nbsp;";
													$print .= " <input type='checkbox' id='" . $cur_name . "' name='" . $cur_name . "{$fldsprearr}' onclick='enableSubmit();'  onchange='enableSubmit();' /><label for='" . $cur_name . "'>удалить</label>";
												}
											}
										}
									}
									//$print_ .=  "<input type=file id=".$cur_name." name=".$cur_name." />";
									break;
								case 9:
									$print .= "<input type='button' name='but' value='" . $cur_field['txt'] . "' " . $cur_field['params'] . ">";

									break;
								case 10:
									$print .= "<div id='" . $cur_name . "' name='" . $cur_name . "{$fldsprearr}' >" . $cur_field['txt'] . "</div>";
									break;

								case 11:
									teAddJSFile(LIB_FORM_PATH . "ajax_subform.js");


									$ic = 0;
									foreach ($value AS $num => $val) {
										//if(!($ic>0 && empty($val))){
										if (!empty($num) || !empty($val)) {

											$ifrsrc = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "&";
											if (!empty($cur_field['getquery']))
												foreach ($cur_field['getquery']['edit'] AS $nm => $vl) {
													$ifrsrc .= $nm . "=" . $vl . "&";
												}
											$ifrsrc .= "action=edit&id=$num";


											print "<div><span id='_ajx$cur_name'>" . $val . "</span><span id='ajx$cur_name$num'></span> [<a href='#$num' onclick=\"show_subform('ajx$cur_name$num','$ifrsrc');enableSubmit()\" >изменить</a>]</div>";


											$ic++;
										}
									}

									if (@$cur_field['multiple'] || $ic == 0) {

										$ifrsrc = "http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . "&";
										if (!empty($cur_field['getquery']))
											foreach ($cur_field['getquery']['add'] AS $nm => $vl) {
												$ifrsrc .= $nm . "=" . $vl . "&";
											}
										$ifrsrc .= "action=add";
										$ss = "[<a class='add' href='#new' onclick=\"show_subform('ajx$cur_name','$ifrsrc');enableSubmit()\">добавить</a>]";

										if (@$cur_field['multiple'])
											$ss = "<span id='a_ajx$cur_name'></span>" . $ss . "";
										else
											$ss = "<span id='a_ajx$cur_name'>" . $ss . "</span>";

										print "<span id='_ajx$cur_name'></span><span id='ajx$cur_name'></span> $ss";
									}

									break;
							}
							if (!$out_langs && count($this->langs) > 0) {
								foreach ($this->langs as $lang => $lname) {
									$print .= "</td><td class='form_right'>&nbsp;";
									
								}
							}
							print "\n\n";
							if (@$cur_field['multiple'] && $cur_field['type'] != 4 && $cur_field['type'] != 5 && $cur_field['type'] != 6 && $cur_field['type'] != 8 && $cur_field['type'] != 11) {
								teAddJSScript("var ar$cur_name = 0;");
								$print1 = str_replace("\"", "\'", str_replace("'", "", "<br/>" . $print_));
								$print1 = str_replace("name=" . $cur_name . "", "name=" . $cur_name . "[]", $print1);
								$print1 = str_replace("{new}", "'+(document.getElementById('" . $cur_name . "_i').value-1)+'", $print1);

								$print1 = str_replace("id=" . $cur_name . "", "id=" . $cur_name . "_new_'+(document.getElementById('" . $cur_name . "_i').value++)+'", $print1); //."<a href=# onclick=\'document.getElementById(\\\\\\\\\'".$cur_name."_i\\\\\\\\\').disabled=true;\' class=del>x</a>";
								$print .= "<input type='hidden' id='" . $cur_name . "_i' value='1' /><span id='" . $cur_name . "_dop0'></span> <a class='add' style='text-decoration:none;' name='add$cur_name' href='#$cur_name' onclick=\"document.getElementById('" . $cur_name . "_dop'+ar$cur_name).innerHTML+='" . $print1 . "<span id=" . $cur_name . "_dop'+(++ar$cur_name)+'></span>';\">+</a> ";

								/* Возможность выбора значений характеристик из отдельного списка вместо выпадающего */
								if (( $cur_field['type'] == 7 ) && ( @$cur_field['id_feature'] )) {
									global $database;
									$id_good2 = $cur_field['id'];
									$id_feature2 = $cur_field['id_feature'];
									$print .= "<input class='form_save' id='frm_sbmt2'  type='submit' value='Выбрать' onclick='this.disabled=true;var st=\".\";$(\"#rubric_multi\").val(1);$(\"#rubric_type\").val();$(\"#id_good\").val($id_good2);$(\"#id_feature\").val($id_feature2);this.form.submit();'   />";
								}
								/*								 * ********************************************************************************* */
							}

							print $print;
							if (isset($cur_field['afterCaption']))
								$print .= " " . $cur_field['afterCaption'];
							print "</td>";
							print "</tr>";
						}
					}
				}
				if (!empty($this->submit_caption)) {
					$s = $this->submit_caption;
				} else {
					$s = "Сохранить";
				}
				print "</table>";
				print "<div class='form_botpanel'>";
				if ($this->repeat)
					print "	<input class='btn btn-block btn-success' type='submit' value='$s и повторить' onclick='this.disabled=true;var st=\".\";repeat.value=1;this.value=\"Идёт сохранение, подождите...\";this.form.submit();' />";
				if ($this->edit)
					print "	<input class='btn btn-block btn-success' type='button' value='Применить' onclick='this.disabled=true;var st=\".\";edit.value=1;this.value=\"Идёт сохранение, подождите...\";this.form.submit();' />";
				print "	<input class='btn btn-block btn-success' id='frm_sbmt' type='submit' value='$s' onclick='this.disabled=true;var st=\".\";this.value=\"Идёт сохранение, подождите...\";this.form.submit();' />";
				if ($step > 1)
					print "	<input class='btn btn-block btn-default' type='button' value='Назад' onClick='stepstepstep.value=" . ( $step == 2 ? -1 : $step - 2 ) . ";this.form.submit();' />";
				else
					print " <input class='btn btn-block btn-default' type='button' value='Отмена' onClick='" . ( ( empty($_GET['iframe']) ) ? "history.back();" : "parent.close_subform_query(1);" ) . "'/>";
				if ($steps > 1)
					print " <input class='btn btn-block btn-default' type='button' value='Сохранить' onClick='this.disabled=true;var st=\".\";this.value=\"Идёт сохранение, подождите...\";stepstepstep.value=" . $steps . ";this.form.submit();' />";
				print "</div>";
				print "</td>";
				print "</tr>";
				print "</table>";
				print "</form>";
			}
		}

		if (isset($formerr))
			$viewform = true;
		return $viewform;
	}

	/*	 * ***
	 * function get_value()
	 *  @params String Name of value
	 *  @return Value
	 * Return value
	 * *** */

	function get_value($name, $str_tags = false, $mysql = false, $trim = false) {
		if ($this->send()) {
			return false;
		} else {
			if ($this->fields[$name]['type'] == 5) {
				$out = array();
				foreach ($this->fields[$name]['items'] as $curr_name => $cur) {
					@$val = $this->input[$name . $curr_name];
					if ($val == 'on') {
						if (!empty($this->input[$name . $curr_name . '_']))
							$out[] = $cur['label'] . ': ' . $this->input[$name . $curr_name . '_'];
						else
							$out[] = $curr_name;
					}
				}
				return $out;
			}
			if (!isset($this->input[$name])) {
				return false;
			} else {
				$out = $this->input[$name];
				if ($this->fields[$name]['type'] == 6 && !empty($this->input[$name . $out . '_']))
					return $this->input[$name . $out . '_'];

				if ($str_tags && !is_array($out))
					$out = strip_tags($out);
				if ($mysql && !is_array($out))
					$out = mysql_escape_string($out);
				if ($trim && !is_array($out))
					$out = trim($out);
				return $out;
			}
		}
	}

	function get_label($name) {
		return $this->fields[$name]['label'];
	}

	//возвращает массив имен полей, которые имеют тип взятый из массива $types
	//$with_label - указывает возвращать массив имен с названиями(лейбелами)
	function get_names($types = array(1, 6), $with_label = false) {
		$out = array();
		foreach ($this->fields as $cur_name => $cur_field) {
			if (in_array($cur_field['type'], $types)) {
				if ($with_label)
					$out[$cur_name] = $cur_field['label'];
				else
					$out[] = $cur_name;
			}
		}
		return $out;
	}

	function get_value_checkbox($name, $type = true) {
		if ($type) {
			return ( ( $this->get_value($name) == "on" ) ? 1 : 0 );
		} else {
			return ( ( $this->get_value($name) == "on" ) ? true : false );
		}
	}

	/*	 * ***
	 * function get_value()
	 *  @params String Name of value
	 *  @return Array
	 * Return array of values
	 * *** */

	function get_arr_value() {
		if ($this->send()) {
			return false;
		} else {
			$list = func_get_args();
			foreach ($list as $param) {
				$out[$param] = $this->get_value($param);
			}
			return $out;
		}
	}

	function move_file($name, $folder = '', $def_dir = "", $delfile = true) {
		if (empty($folder))
			$folder = $this->fields[$name]['folder'];
		if (substr($folder, -1) == '/')
			$folder = substr($folder, 0, -1);
		if (@$this->fields[$name]['multiple']) {
			@$mfiles = $_POST['mfiles' . $name];
			$files = array();
			if (is_array($mfiles))
				foreach ($mfiles as $fid => $fname) {
					if (isset($_POST['chfiles' . $name][$fid]))
						$files[] = $fname;
					else
						unlink($def_dir . $folder . "/" . $fname);
				}
			if (!$delfile) {
				@$old = $_POST['fold' . $name];
				if (is_array($old)) {
					foreach ($old as $fid => $fname) {
						if (!isset($_POST['fdel' . $name][$fid]))
							$files[] = $fname;
					}
				}
			}
			if (count($files) > 0)
				return $files;
			return false;
		}
		else {
			if ($_FILES[$name]['error'] == 1)
				return 1;
			if (isset($_FILES[$name]) && file_exists($_FILES[$name]['tmp_name'])) {
				if (empty($def_dir))
					$def_dir = DATA_FLD;
				$filename = pathinfo($_FILES[$name]['name']);
				if ($filename['extension'] == 'php')
					return false;
				$fn = filename(translit(substr($_FILES[$name]['name'], 0, -strlen($filename['extension']) - 1)));
				$fn = substr($fn, 0, 55);
				$i = 0;
				while (file_exists($def_dir . $folder . "/" . $fn . ( empty($i) ? "" : "_" . $i ) . "." . $filename['extension'])) {
					$i++;
				}
				$filename = $fn . ( empty($i) ? "" : "_" . $i ) . "." . $filename['extension'];

				if (@move_uploaded_file($_FILES[$name]['tmp_name'], $def_dir . $folder . "/" . $filename))
					return $filename;

				return false;
			}
			else {
				return false;
			}
		}
	}

}

?>