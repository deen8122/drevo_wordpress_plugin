<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}

// входные GET-данные
$showid = (int)@$_GET['showid'];
$rubric_id = (int)@$_GET['rubric_id'];
$id = (int)@$_GET['id'];
$type = (int)@$_GET['type'];
$typeview = (empty($_GET['typeview']))?"tree":$_GET['typeview'];

// доп.параметры генерируемых УРЛов
addGet("typeview",$typeview);
addGet("type",$type);
addGet("pg",'features');
$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 and ID_RUBRIC_TYPE=".$type);
$rtype=mysql_fetch_array($res,MYSQL_ASSOC);
addGet("showid",$showid);
addGet("rubric_id",$rubric_id);

// ссылка на записи текущей рубрики
if($rubric_id>0) addSubMenu(teGetUrlQuery("=goods")."&from=menu", "<img src='".DEEN_FOLDERS_URL."assets/images/btn-left.png' alt='' title='Записи' align='middle'/> Записи", "submenustd");

$idshow = @$_GET['idshow'];if(empty($idshow)) $idshow = "id";
addGet("idshow",$idshow);

global $skin;

// если действие
if(isset($_GET['action']))
switch($_GET['action']){
	// добавление
	case 'add':
		// addHelp("rubric_feature_add.html");


		print "<div align=center>";
		$frm = new teForm("form1","post",true);
        if(isset($_GET['msg'])) print '<div style="color:blue;">Характеристика добавлена</div>';
		if(empty($rubric_id)){
			$frm->addTitle("<h2>Добавление общей характеристики всех ".$rtype['rubrictype_r_m']."</h2>");
		} else {
			$frm->addTitle("<h2>Добавление характеристики ".$rtype['rubrictype_r_m']." в категорию ".getRubricName($rubric_id)."</h2>");
		}

		$frm->addf_text("feature_text", "Наименование характеристики");
		$frm->setFieldWidth("feature_text", "300px");

		$frm->addf_checkbox("feature_require", "Обязательная характеристика", false);
		$frm->addf_desc("feature_require", "Если данная опция отмечена, то при добавлении, ".$rtype['rubrictype_r_s']." не будет<br> добавлен, пока значение этой характеристики не будет указано.");

		// тип данных
		$frm->addf_radioGroup("feature_type", "Здесь необходимо выбрать тип данных характеристики.");
		$frm->addf_desc("feature_type", "
			<b>Например</b>:<br>
			<i>Высота корпуса, мм</i> - это число, и при добавлении единицы, она не будет<br> добавлена, пока в эту характеристику не будет введено число.<br>
			<i>Комплектация</i> - это текст.<br>
			<i>Соответствует стандарту</i> - это логический тип данных,<br> может принимать значения \"да\" и \"нет\".<br>
			<i>Страна-производитель</i> - справочник, т.к. проще (и экономнее) сначала<br> сохранить список всех стран, а при добавлении ".$rtype['rubrictype_r_s']." выбрать<br> страну из списка.<br>
			<i>Двигатель</i> - другой товар, если в рубрикаторе существует отдельная <br>рубрика двигателей.<br>
			<i>Спецификация в формате .pdf</i> - файл.
			<b></b>
		");
		$frm->addf_radioItem("feature_type", "1", "Число (возможны не целые числа)");
		$frm->addf_radioItem("feature_type", "2", "Текст (максимум 255 символов)", true);
		$frm->addf_radioItem("feature_type", "7", "Большой текст (максимум 65535 символов)");
		$frm->addf_radioItem("feature_type", "3", "Логический (да или нет)");
		$frm->addf_radioItem("feature_type", "4", "Справочник");
		$frm->addf_radioItem("feature_type", "5", "Динамическая ветвь");
		$frm->addf_radioItem("feature_type", "9", "Справочник рубрик по разделу");
		$frm->addf_radioItem("feature_type", "10", "Справочник записей по другому разделу");
		$frm->addf_radioItem("feature_type", "6", "Файл");
		$frm->addf_radioItem("feature_type", "8", "Дата");


		$frm->addf_checkbox("feature_multiple", "Разрешить добавлять <b>много значений</b> (связь <b>один-ко-многим</b>)", false);

		$frm->addf_checkbox("feature_graduation", "Использовать как градацию на сайте (только справочники)", false);

		$frm->addf_checkbox("feature_enable", "Характеристика доступна для показа посетителям", true);

		$frm->setSubmitCaption("далее");

		$frm->setf_require("feature_text","feature_type");
		if(!$frm->send()){
			/*   если значения формы уже введены   */

			if( $database->getArrayOfQuery("SELECT ".DB_PREFIX."features.ID_FEATURE FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$rubric_id." and feature_text='".$frm->get_value("feature_text")."'") ){
				// если в рубрике дублируется название характеристики
				print "<div>Характеристика <b>".$frm->get_value("feature_text")."</b> уже существует в данной рубрике!</div>";
				print "<div align='center'> <a href='javascript:history.back()'>назад, к заполнению формы</a> </div>";


				$frm->errorValue("feature_text","Такая характеристика уже существует в данной рубрике!");
				$frm->send();
			} elseif( list($oldfeat) = $database->getArrayOfQuery("SELECT ".DB_PREFIX."features.feature_text FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$rubric_id." and feature_text LIKE '%".$frm->get_value("feature_text")."%'") && empty($_POST['ask']) ) {
				// если в рубрике похожая по названию хар-ка

				print "<div class='note'>Похожая характеристика  <b>".str_replace($frm->get_value("feature_text"),"<u>".$frm->get_value("feature_text")."</u>",$oldfeat)."</b> уже существует в данной рубрике. </div>";

				print "<form method='post'><div><p>Вы можете дать новой характеристике другое название (необязательно): ";
				foreach($_POST AS $post_name => $post_val){
					print "<input type='hidden' name='$post_name' value='$post_val'/>";
				}
				print "<input type='hidden' name='ask' value='1'/>";
				print "<input type='text' name='feature_text[0]' value='".$_POST['feature_text'][0]."'/>";
				print "<p><div><input type='submit' value='Сохранить'/></div></p>";
				print "</p></div></form>";


				$frm->errorValue("feature_text","Такая характеристика уже существует в данной рубрике!");
				$frm->send();
			} else {
				// если новая характеристика уникальна

				$feature_text = $frm->get_value('feature_text');
				$feature_graduation = 0;
				$feature_graduation = ($frm->get_value('feature_graduation')=="on")?1:0;
				$feature_multiple = 0;
				$feature_multiple = ($frm->get_value('feature_multiple')=="on")?1:0;
				$feature_require = 0;
				$feature_require = ($frm->get_value('feature_require')=="on")?1:0;
				$feature_enable = 0;
				$feature_enable = ($frm->get_value('feature_enable')=="on")?1:0;
				$feature_type = $frm->get_value('feature_type');

				/// добавление харки в бд
				// сохр.
				$database -> query("INSERT INTO ".DB_PREFIX."features (feature_text,feature_type,feature_multiple,feature_graduation,feature_require,feature_enable) VALUES ('".$feature_text."',$feature_type,$feature_multiple,$feature_graduation,$feature_require,$feature_enable)");
				$saveid = $database -> id();
				addFeature($type, $rubric_id, $saveid);

                $repeat = @$_POST['repeat'];
				// в зависимости от типа новой характеристики, переходим к:
				// 4: Справочник  -  к заполнению справочника
				// 5: Динамическая ветвь  -  к выбору рубрикиъ
				// 9: Раздел рубрикатора  -  выбор раздела рубрикаторв
				if( $feature_type==4 ){
					$id = $database -> lastQueryId;
					teRedirect(teGetUrlQuery("action=featuresanswers","id=$saveid","repeat=".$repeat));
				}
				if( $feature_type==5 ){
					$id = $database -> lastQueryId;
					teRedirect(teGetUrlQuery("action=featurerubrics","id=$saveid","feature_type=$feature_type"),"repeat=".$repeat);
				}
				if( $feature_type==9 ){
					$id = $database -> lastQueryId;
					teRedirect(teGetUrlQuery("action=section","id=$saveid","feature_type=$feature_type","repeat=".$repeat));
				}
				if( $feature_type==10 ){
					$id = $database -> lastQueryId;
					teRedirect(teGetUrlQuery("action=section2","id=$saveid","feature_type=$feature_type","repeat=".$repeat));
				}

				// а если простой тип данных, то переходим к списку характеристик
				if($repeat==1)teRedirect(teGetUrlQuery("action=add","msg=add"));
				else teRedirect(teGetUrlQuery("showid=$id"));
			}

		}
		print "</div>";

	break;
	// изменение
	case 'edit':
		$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$id,MYSQL_ASSOC);

		print "<div align=center>";
		$frm = new teForm("form1","post");
		$frm->addTitle("<h2>Изменение характеристики «".$line['feature_text']."»</h2>");

		$frm->addf_text("feature_text", "Наименование характеристики", $line['feature_text']);

		$frm->addf_checkbox("feature_multiple", "Разрешить добавлять <b>много значений</b> (связь <b>один-ко-многим</b>)", ($line['feature_multiple']=="1")?true:false);
		$frm->addf_desc("feature_multiple", ($line['feature_multiple']=="1")?"<font color=darkred>Будьте осторожны: если отключить эту опцию,<br>вы можете потерять уже «набитые» данные</font>":"");

		$frm->addf_checkbox("feature_graduation", "Использовать как градацию на сайте (только справочники)", ($line['feature_graduation']=="1")?true:false);

		$feature_require = ($line['feature_require']=="1")?true:false;

		$frm->addf_checkbox("feature_require", "Обязательная характеристика", $feature_require);
		$frm->addf_desc("feature_require", "Если данная опция отмечена, то при добавлении, ".$rtype['rubrictype_i_s']." не будет<br> добавлен, пока эта характеристика не будет указана.");

		$frm->addf_radioGroup("feature_type", "Здесь необходимо выбрать тип данных характеристики.");
		$frm->addf_desc("feature_type", "
			<span style='color:red;font-size:12px;font-weight:bold;'>Осторожно! При изменение типа характеристики<br/> данные могут быть потеряны!</span><br/>
			<span style='font-size:12px;' >Число &nbsp;&nbsp;&lt;-&gt;&nbsp;&nbsp; Текст: без потерь<br/>
			Число, Текст, Файл, Дата &nbsp;&nbsp;-&gt;&nbsp;&nbsp; Большой текст: без потерь<br/>
			Большой Текст &nbsp;&nbsp;-&gt;&nbsp;&nbsp; Текст: обрезание данных до 255 символов<br/>
			Файл, Дата &nbsp;&nbsp;-&gt;&nbsp;&nbsp; Текст: без потерь<br/>
			В остальных случаях данные по этой характеристики стираются</span>
		");
		$frm->addf_radioItem("feature_type", "1", "Число (возможны не целые числа)");
		$frm->addf_radioItem("feature_type", "2", "Текст (максимум 255 символов)");
		$frm->addf_radioItem("feature_type", "7", "Большой текст (максимум 65535 символов)");
		$frm->addf_radioItem("feature_type", "3", "Логический (да или нет)");
		$frm->addf_radioItem("feature_type", "4", "Справочник");
		$frm->addf_radioItem("feature_type", "5", "Динамическая ветвь");
		$frm->addf_radioItem("feature_type", "9", "Справочник рубрик по разделу");
		$frm->addf_radioItem("feature_type", "10", "Справочник записей по другому разделу");
		$frm->addf_radioItem("feature_type", "6", "Файл");
		$frm->addf_radioItem("feature_type", "8", "Дата");
        $frm->add_value("feature_type",$line['feature_type']);
        $old_type = $line['feature_type'];

		$feature_enable = ($line['feature_enable']=="1")?true:false;
		$frm->addf_checkbox("feature_enable", "Характеристика доступна для показа посетителям", $feature_enable);

		$frm->setSubmitCaption("Сохранить");

		$frm->setf_require("feature_text");
		if(!$frm->send()){
			/*   если значения формы уже введены   */
			$feature_text = $frm->get_value('feature_text');
			$feature_type = $frm->get_value('feature_type');

			//{{Контроль данных при изменении типа характеристики, удалять или не удалять
			$del_data = false;
			if($feature_type!=$old_type)
			{				$del_data = true;
				if($feature_type==2 && $old_type==1)$del_data = false;
				if($feature_type==1 && $old_type==2)$del_data = false;
				if($feature_type==2 && ($old_type==6 || $old_type==8))$del_data = false;
			}
			//Если у характеристики поменяли тип данных на "большой текст", то меняем данные полей всех записей с редактируемой характеристикой
			if($feature_type!=$old_type && $feature_type==7)
			{
				$res = $database -> query("SELECT goodfeature_value,ID_GOOD_FEATURE from cprice_goods_features where ID_FEATURE=".$id);
    			while($row = mysql_fetch_array($res))
                {
					$text_id = 0;
					if($old_type==1 || $old_type==2 || $old_type==6 || $old_type==8)
				    {
                    	if(!empty($row[0]))
                    	{                    		$database->query("INSERT INTO ".DB_PREFIX."texts (text_text) VALUES ('$row[0]')");
                    		$text_id = $database->id();
                    	}
                    }
                    $database -> query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$text_id."' WHERE ID_GOOD_FEATURE=$row[1]");
				}
				$del_data = false;
			}
			//Если у характеристики был тип данных "большой текст" и его поменяли
			if($feature_type!=$old_type && $old_type==7)
			{
				$res = $database -> query("SELECT goodfeature_value,ID_GOOD_FEATURE from cprice_goods_features where ID_FEATURE=".$id);
    			while($row = mysql_fetch_array($res))
                {
					$value = '';
					if($feature_type==2)
				    {
                    	if($row[0]>0)
                    	{
                    		list($value) = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts where ID_TEXT=$row[0]");
                    	}
                    }
                    $database -> query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$value."' WHERE ID_GOOD_FEATURE=$row[1]");
				}
				$del_data = false;
			}
			if($del_data)$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE=$id");
			//}}

			$feature_multiple = 0;
			$feature_multiple = ($frm->get_value('feature_multiple')=="on")?1:0;
			$feature_graduation = 0;
			$feature_graduation = ($frm->get_value('feature_graduation')=="on")?1:0;
			$feature_require = 0;
			$feature_require = ($frm->get_value('feature_require')=="on")?1:0;
			$feature_enabled = 0;
			$feature_enabled = ($frm->get_value('feature_enable')=="on")?1:0;
			$database -> query("UPDATE ".DB_PREFIX."features SET feature_text='".$feature_text."', feature_type='".$feature_type."', feature_multiple='$feature_multiple', feature_graduation='$feature_graduation', feature_require='$feature_require', feature_enable='$feature_enable' WHERE ID_FEATURE=$id");

			teRedirect(teGetUrlQuery());
		}
		print "</div>";
	break;
	// включение
	case 'enable':
		$database -> query("UPDATE ".DB_PREFIX."features SET feature_enable=1 WHERE ID_FEATURE=".$id);
		teRedirect(teGetUrlQuery());

	break;
	// отключение
	case 'disable':
		$database -> query("UPDATE ".DB_PREFIX."features SET feature_enable=0 WHERE ID_FEATURE=".$id);
		teRedirect(teGetUrlQuery());
	break;
	// удаление
	case 'delete':
		if( !isset($_GET['method']) ){
			print "<h2>Выберите метод удаления</h2>";
			print "<div align=center>";
			print " <button onClick='location.href=\"".teGetUrlQuery("action=delete","method=all","id=$id")."\"'>Удалить характеристику из рубрики<br>".getRubricName($rubric_id,false,false).",<br> и из её подрубрик</button> ";
			print " <button onClick='location.href=\"".teGetUrlQuery("action=delete","method=once","id=$id")."\"'>Удалить характеристику<br><b>только</b> из рубрики<br>".getRubricName($rubric_id,false,false)."</button> ";
			print " <button onClick='location.href=\"".teGetUrlQuery()."\"'><br><b>Отмена</b>,<br> ничего удалять не нужно.</button> ";
			print "</div>";
		} else {
			if($_GET['method']=='all'){
				deleteFeature($type, $rubric_id, $id);
			} else {
				$database -> query("DELETE FROM ".DB_PREFIX."rubric_features WHERE ID_FEATURE=".$id." and ID_RUBRIC=".$rubric_id." and rubric_type=".$type);
			}
			teRedirect(teGetUrlQuery());
		}
	break;

	// выбор раздела рубрикатора для типа данных 9:Раздел рубрикатора
	case 'section':
		if(!empty($_POST['sect'])){
			$database -> query("UPDATE ".DB_PREFIX."features SET feature_rubric=$_POST[sect] WHERE ID_FEATURE=".$id." and feature_type=$_GET[feature_type]");
			$repeat = @$_GET['repeat'];
			if($repeat==1) teRedirect(teGetUrlQuery("action=add","msg=add"));
			else teRedirect(teGetUrlQuery());
		}

		print "<h2>Выберите раздел, который следуйет связать с этой характеристикой</h2>";

		print "<form method=post>";
		list($cursect) = $database -> getArrayOfQuery("SELECT feature_rubric FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$id." and feature_type=$_GET[feature_type]");
		$res = $database->query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE rubrictype_deleted=0 and rubrictype_visible=1");
		while(list($rub_id,$rub_name) = mysql_fetch_array($res)){
			print "<div><input type='radio' name='sect' id='sect$rub_id' value='$rub_id'".(($cursect==$rub_id)?" checked":"")."><label for='sect$rub_id'>$rub_name</label></div>";
		}
		print "<div align=center><input type='submit' value='сохранить'> <input type='button' value='отмена' onClick='history.back()'></div></form>";

	break;
	case 'section2':
		if(!empty($_POST['sect'])){
			$database -> query("UPDATE ".DB_PREFIX."features SET feature_rubric=$_POST[sect] WHERE ID_FEATURE=".$id." and feature_type=$_GET[feature_type]");
			$repeat = @$_GET['repeat'];
			if($repeat==1) teRedirect(teGetUrlQuery("action=add","msg=add"));
			else teRedirect(teGetUrlQuery());
		}

		print "<h2>Выберите раздел, который следуйет связать с этой характеристикой</h2>";

		print "<form method=post>";
		list($cur_sect) = $database -> getArrayOfQuery("SELECT rubric_type FROM ".DB_PREFIX."rubric natural join ".DB_PREFIX."rubric_features WHERE ID_FEATURE=".$id." limit 1");
		list($cursect) = $database -> getArrayOfQuery("SELECT feature_rubric FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$id." and feature_type=$_GET[feature_type]");
		$res = $database->query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE rubrictype_deleted=0 and rubrictype_visible=1");
		while(list($rub_id,$rub_name) = mysql_fetch_array($res)){			if($cur_sect!=$rub_id)
				print "<div><input type='radio' name='sect' id='sect$rub_id' value='$rub_id'".(($cursect==$rub_id)?" checked":"")."><label for='sect$rub_id'>$rub_name</label></div>";
		}
		print "<div align=center><input type='submit' value='сохранить'> <input type='button' value='отмена' onClick='history.back()'></div></form>";

	break;

	// добавление возможных ответов характеристики (типы 4,6)
	case 'featuresanswers':
	case 'featurefiles':
		if( isset($_POST['checkvar1']) ){

			$rubnew = !empty($_POST['rubnew'])?1:0;
			$database -> query("UPDATE ".DB_PREFIX."features SET feature_rubric=$rubnew WHERE ID_FEATURE=$id");

			if( is_array(@$_POST['rubricanswers']) ){
				foreach( $_POST['rubricanswers'] AS $i => $value ){
					if( (trim($value))!="" && !$database -> getArrayOfQuery("SELECT ID_FEATURE FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$id." and featuredirectory_text='".$value."'") ){
						$database -> query("INSERT INTO ".DB_PREFIX."feature_directory (ID_FEATURE,featuredirectory_text) VALUES ($id,'".str_replace("'","\'",$value)."')");
					}
				}
				if(!empty($_POST['rubricanswerso']))
				foreach( $_POST['rubricanswerso'] AS $i => $value ){
					if( (trim($value))!="" ){
						if( $line = $database -> getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".$i."") ){
							if( $line[0] != $value){
								$database -> query("UPDATE ".DB_PREFIX."feature_directory SET featuredirectory_text='".$value."' WHERE ID_FEATURE_DIRECTORY=".$i);
							}
						}
					} else {
						$database -> query("DELETE FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".$i);
					}
				}

				$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$id,MYSQL_ASSOC);
	//die($_POST['checkvar1']);
				if($_POST['checkvar1']==1){					$repeat = @$_GET['repeat'];
					if($repeat==1) teRedirect(teGetUrlQuery("action=add","msg=add"));
					else teRedirect(teGetUrlQuery());
				} else {
					unset($_POST['checkvar1']);
				}
			} else {
				unset($_POST['checkvar1']);
			}
		}
		if( !isset($_POST['checkvar1']) ){
			// addHelp('rubric_feature_answers.html');
			$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$id,MYSQL_ASSOC);

			if($_GET['action']=="featuresanswers") print "<h2>Добавление возможных значений характеристики \"".$line['feature_text']."\"</h2>";
			if($_GET['action']=="featurefiles"){
				print "<h2>Управление возможными расширениями загружаемых файлов характеристики \"".$line['feature_text']."\"</h2>";
				print "<div class='note'>Пожалуйста пишите только само расширение (т.е. без точки)<br><i><b>напр.</b> \"zip\", \"jpg\", \"pdf\" (без ковычек) </i></div>";
			}
			print("<div class='note'>Для удаления значения просто оставьте поле пустым</div>");


			print "<div><table align='center'><tr><td>";
			print "<form method='post' name='mng' id='mng'>";
			print "<input type=hidden name='checkvar1' value='1' />";
			// добавляем JS скрипт добавления новых полей
			teAddJsScript("
				function add_field(){
					if(updt){
						document.getElementById('mng').submit();
					}else{
						ifields++;
						var rubricanswers=document.getElementById(\"rubricanswers\");
						rubricanswers.innerHTML+=
						\"<div>\"+ifields+\": \"+
						\"<input onkeydown='updt=true;' type='text' size='50' name='rubricanswers[]' /></div>\";
					}
				}
			");
			$i = 0;
			/**print "<!--\n\n\n\n";
			$res = $database -> query("SELECT * FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$id." ORDER BY featuredirectory_text");
			while( $line = mysql_fetch_array($res,MYSQL_ASSOC) ){
				$i++;
				echo "$line[featuredirectory_text]\n";
			}
			print "\n\n\n\n-->";/**/

			$res = $database -> query("SELECT * FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$id." ORDER BY featuredirectory_text");
			while( $line = mysql_fetch_array($res,MYSQL_ASSOC) ){
				$i++;
				echo "<div>".$i.": <input type='text' size='50' name='rubricanswerso[".$line['ID_FEATURE_DIRECTORY']."]' value=\"".str_replace("\"","&quot;",$line['featuredirectory_text'])."\" /> (".getIdToPrint("feature_directory",$line['ID_FEATURE_DIRECTORY']).")</div>";
			}
			// счетчик полей
			teAddJsScript("var ifields=".$i.";var updt=false;");

			print "<div id=\"rubricanswers\"></div>";
			print "<div align=right><input type=button value='добавить ещё' onClick='this.form.checkvar1.value=2;add_field();' />".teGetJsScript("add_field();add_field();")."</div>";

			// checkbox "  Разрешить автозаполнение справочника  "
			if($_GET['action']=="featuresanswers"){
				list($rubnew) = $database->getArrayOfQuery("SELECT feature_rubric FROM ".DB_PREFIX."features WHERE ID_FEATURE=$id");
				print "<br/><div class='note'><input type='checkbox' name='rubnew' id='rubnew11'".(($rubnew==1)?" checked":"")."/> <label for='rubnew11'>Разрешить автозаполнение справочника</label></div><br/>";
			}

			print "<div align='center'><input type=submit value='Сохранить'></div>";
			print "</form>";
			print "</tr></td></table></div>";
		}
	break;


	// выбор рубрики как ТД
	case 'featurerubrics':
		if(!empty($_POST['rubric'])){
			if(is_array($_POST['rubric'])){

				$acc = 1;//0;
				//if(isset($_POST['acc1'])) $acc += 1;
				//if(isset($_POST['acc2'])) $acc += 2;

				$database -> query("UPDATE ".DB_PREFIX."features SET feature_rubric='$acc' WHERE ID_FEATURE=".(int)$_GET['id']);

				$database -> query("DELETE FROM ".DB_PREFIX."feature_rubric WHERE ID_FEATURE=".(int)$_GET['id']);
				foreach($_POST['rubric'] AS $rub_id => $on){
					$database -> query("INSERT INTO ".DB_PREFIX."feature_rubric VALUES ($id,$rub_id)");
				}
				$repeat = @$_GET['repeat'];
				if($repeat==1) teRedirect(teGetUrlQuery("action=add","msg=add"));
				else teRedirect(teGetUrlQuery());
			}
		}

		/// вывод
		function get_child($type, $id, $template, $cnt=false){
			global $database;
			global $showid;
			$s = "";
			$res = $database -> query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$id." ORDER BY rubric_name");
			// если $cnt не указан (ф-я вызвана 1-й раз), $i = 0, иначе $cnt + 1;
			$i = (!$cnt)?1:$cnt+1;

			while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
				// фрагмент отмечает в HTML уже отмеченные записи и их детей
				$checked=false;
				if( isset( $_GET['id'] ) ){
					if(
						@$database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."feature_rubric WHERE ID_FEATURE=".(int)$_GET['id']." and ID_RUBRIC='".(int)$line['ID_RUBRIC']."'")
					){
						$checked=true;
					}
				} else {
					$checked=false;
				}

				// вызываем эту же ф-ю с $id равным текущему
				$arr = get_child($type, $line['ID_RUBRIC'], $template, $i);

				// заменяем переменные шаблона на данные
				$s1 = @str_replace("{name}",getCountRubricChild($line['ID_RUBRIC']),$template);
				$s1 = @str_replace("{name}",$line['rubric_name'].$arr['s'],$template);
				$s1 = str_replace("{formname}",$line['ID_RUBRIC'],$s1);
				$s1 = str_replace("{id}","ch".$i,$s1);


				if( getCountRubricChild($line['ID_RUBRIC'])>0 ){
					//$s1 = str_replace("{param}","disabled",$s1);
				}
				if($checked){
					$s1 = str_replace("{param}","checked",$s1);
				}
				$s1 = str_replace("{param}","",$s1);

				$s .= $s1;
			}
			if(!$cnt){
				return $s;
			} else {
				return array('s'=>$s,'n'=>$i-1);
			}
		}


		// вывод заголовка
		$line = $database->getArrayOfQuery("SELECT feature_text FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$id);

		if($_GET['feature_type']==5) print "<h2>Выберите рубрики, которые могут быть подчинены характеристике \"".$line[0]."\"</h2>";


		//вывод
		// форма. если добавление то переходит на action=add, если изменение, то action=rubric
		print "<form action='".teGetUrlQuery("action=featurerubrics","id=$id")."' method=post><input type=hidden name=rubric_save value=1><table width='100%'><tr valign='top'>";

		$res = $database->query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE rubrictype_deleted=0 and rubrictype_visible=1");
		while($line = mysql_fetch_array($res)){
			print "<td><h3>$line[1]</h3>".get_child($line[0], 0, "<div style='padding-left:".TREE_LEFT."em'><input type='checkbox' name='rubric[{formname}]' id={id} {param}><label for='{id}'>{name}</label></div>")."</td>";
		}
		print "</tr></table>";

		list($acc) = $database->getArrayOfQuery("SELECT feature_rubric FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$id);
		if(empty($acc)) $acc = 3;
		$acc1 = $acc2 = false;
		if($acc>1){ $acc2 = true; $acc-=2;}
		if($acc>0) $acc1 = true;

		//print "<div class='note'>";
		//print "	<div><input type='checkbox' name='acc1' id='acc1'".(($acc1)?"checked":"")."/><label for='acc1'>Разрешить добавлять новые записи</label></div>";
		//print "	<div><input type='checkbox' name='acc2' id='acc2'".(($acc2)?"checked":"")."/><label for='acc2'>Разрешить выбирать записи</label></div>";
		//print "</div>";

		print "<div align=center><input type=submit value='сохранить'> <input type=button value='отмена' onClick='history.back()'></div></form>";

	break;

	// копирование хар-к
	case 'copy_features':
		$step = (int)@$_GET['step'];



		$iii=0;
		function get_child($type, $id, $template, $cnt=false, $rrr = false){
			global $database;
			global $iii;
			$s = "";
			$res = $database -> query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$id." ORDER BY rubric_name");
			// если $cnt не указан (ф-я вызвана 1-й раз), $i = 0, иначе $cnt + 1;
			$i = (!$cnt)?1:$cnt+1;
			while($line = mysql_fetch_array($res,MYSQL_ASSOC)){

				// вызываем эту же ф-ю с $id равным текущему
				$arr = get_child($type, $line['ID_RUBRIC'], $template, $i, true);

				// заменяем переменные шаблона на данные
				$s1 = @str_replace("{name}",$line['rubric_name']." (".getCountFeatures($line['ID_RUBRIC'])." хар.)".$arr['s'],$template);
				$s1 = str_replace("{formname}",$line['ID_RUBRIC'],$s1);
				$s1 = str_replace("{id}","ch".$i,$s1);


				$s2 = "";
				// фрагмент генерирует JS, который отмечает флажки всех детей текущей записи по изменению текущей записи.

				if( @($arr['n']>$i) ){
					$s2 .= " onClick=\"";
					for($ii=$i+1;$ii<=$arr['n'];$ii++){
						$s2 .= "this.form.ch$ii.checked=";
					}
					$s2 .= "this.checked;\" ";
				}

				$s1 = str_replace("{param}",$s2,$s1);
				$s .= $s1;

				@$i=@$arr['n']+1;
				$iii++;
			}
			if(!$rrr){
				return $s;
			} else {
				return array('s'=>$s,'n'=>$i-1);
			}
		}

		/// вывод

		// вывод заголовка
		function setStep($step){
			$prefix = "Шаг ".($step+1)." из 4: ";
			if($step==0){
				print "<h2>{$prefix}Выберите рубрики, из которых нужно копировать характеристики.</h2>";
				print "<div class=note>После выбора рубрики, вам предстоит выбрать характеристики этой рубрики, которые нужно скопировать.</div>";
			}elseif($step==1){
				print "<h2>{$prefix}Выберите копируемые характеристики.</h2>";
			}elseif($step==2){
				print "<h2>{$prefix}Выберите рубрики, в которые копировать характеристики.</h2>";
			}elseif($step==3){
				print "<h2>{$prefix}Подтверждение копирования характеристик.</h2>";
			}
		}

		//вывод
		// форма. если добавление то переходит на action=add, если изменение, то action=rubric
		print "<form method=get><table width=100%><tr>";

		// vars
		print "<input type=hidden name=".G_PAGE." value=features>";
		print "<input type=hidden name=type value=$type>";
		print "<input type=hidden name=typeview value=$typeview>";
		print "<input type=hidden name=showid value=$showid>";
		print "<input type=hidden name=action value=copy_features>";

		// тут всё по шагам
		if($step==4){
			print "<td>";
			print "<input type=hidden name=step value=".($step+1).">";

			foreach($_GET['rubric'] AS $rubric => $on){
				foreach($_GET['feat'] AS $feat => $on){
					addFeature($type, $rubric, $feat);
				}
			}
			teRedirect(teGetUrlQuery("=rubric"));
		}
		if($step==3){
			print "<td>";
			if(!isset($_GET['rubric'])){
				$step = 2;
				$err = "Пожалуйста, выберите по крайней мере одну рубрику!";
			} else {
				setStep($step);
				if(@$err) print "<div class=error>".$err."</div>";

				print "<input type=hidden name=step value=".($step+1).">";

				print "<div><b>В следующие рубрики:</b></div>";

				print "<div style='padding-left:10px'>";
				if(isset($_GET['rubric'][0])){
					print "<input type=hidden name=rubric[0] value=on>";
					print "Общая рубрика ".$rtype['rubrictype_r_m'];
				} else {
					$i=0;
					foreach($_GET['rubric'] AS $name => $on){
						print "<input type=hidden name=rubric[$name] value=on>";
						if($i!=0) print ", ";
						$line = $database -> getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$name);
						print $line[0];
						$i++;
					}
				}
				print "</div>";

				print "<div><b>будут добавлены следующие характеристики:</b></div>";

				print "<div style='padding-left:10px'>";
				foreach($_GET['feat'] AS $name => $on){
					print "<input type=hidden name=feat[$name] value=on>";
					$line = $database -> getArrayOfQuery("SELECT feature_text FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$name);
					print "<div>".$line[0]."</div>";
				}
				print "</div>";

				print "<div><b>Продолжить?</b></div>";
				print "</td></tr></table><div align=center><input type=button value='назад' onClick='history.back()'> <input type=submit value='да'> <input type=button value='нет' onClick='location.href=\"".teGetUrlQuery("type=$type")."\"'> </div>";
			}
		}
		if($step==2){
			print "<td>";

			if(!isset($_GET['feat'])){
				$step = 1;
				$err = "Пожалуйста, выберите по крайней мере одну характеристику!";

			}else {
				setStep($step);
				if(@$err) print "<div class=error>".$err."</div>";

				print "<input type=hidden name=step value=".($step+1).">";
				// суем список выбранных рубрик и показываем список хар-к
				foreach($_GET['feat'] AS $name => $on){
					print "<input type=hidden name=feat[$name] value=on>";
				}

				print "<div><input type='checkbox' name='rubric[0]' id=ch0 />".$rtype['rubrictype_i_m']."</div>";
				print get_child($type, 0, "<div style='padding-left:".TREE_LEFT."em'><input type='checkbox' name='rubric[{formname}]' id={id} {param}>{name}</div>");

				print "</td></tr></table><div align=center> <input type=button value='назад' onClick='history.back()'> <input type=submit value='далее'> <input type=button value='отмена' onClick='location.href=\"".teGetUrlQuery("type=$type")."\"'></div>";
			}

		}
		if($step==1){
			print "<td>";
			if(!isset($_GET['rubric'])){
				$step = 0;
				$err = "Пожалуйста, выберите по крайней мере одну рубрику!";
			} else {
				setStep($step);
				if(@$err) print "<div class=error>".$err."</div>";

				print "<input type=hidden name=step value=".($step+1).">";
				// показываем список хар-к
				$i = 0;

				foreach($_GET['rubric'] AS $name => $on){
					print "<input type=hidden name=rubric[$name] value=on>";
					// выводим наим.рубрики
					$line = $database -> getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$name,MYSQL_NUM);

					// выводим хар-ку
					$res = $database -> query("SELECT ".DB_PREFIX."features.* FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$name." ORDER BY rubricfeature_pos,feature_text");
					if(mysql_num_rows($res)>0){
						$s = "";
						for($ii=$i;$ii<=mysql_num_rows($res)+$i;$ii++){
							$s .= "this.form.feat$ii.checked=";
						}
						print "<div><div><input type='checkbox' onmouseup='$s=this.checked'><label for='g$i'><b>".$line[0]."</b></label></div>";
					}
					while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
						print "<div style='padding-left:10px;'><input type=checkbox name=feat[".$line['ID_FEATURE']."] id=feat".$i."><label for=feat".$i.">".$line['feature_text']."</label></div>";
						$i++;
					}
					print "</div>";
				}
				if($i==0){
					print "<div class=error>Извините, в выбранных рубриках нет характеристик.</div>";
				}
				print "</td></tr></table><div align=center> <input type=button value='назад' onClick='history.back()'> ".(($i!=0)?"<input type=submit value='далее'> <input type=button value='отмена' onClick='location.href=\"".teGetUrlQuery("type=$type")."\"'>":"")."</div>";
			}

		}
		if($step==0){
			setStep($step);
			print "<input type=hidden name=step value=".($step+1).">";

			// показываем список рубрик (откуда копировать)
			if(@$err) print "<div class=error>".$err."</div>";
			$res = $database->query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0");
			while(list($d,$rtn)=mysql_fetch_array($res)){
				$iiid = $iii;
				$sss = get_child($d, 0, "<div style='padding-left:".TREE_LEFT."em'><input type='checkbox' name='rubric[{formname}]' id={id} {param}>{name}</div>",$iii);
				$ss = "";
				for($i=$iiid+1;$i<=$iii;$i++){
					$ss .= "this.form.ch$i.checked=";
				}
				$ss .= "this.checked;";
				$ss = "<div><input type='checkbox' onclick='".$ss."'>".$rtn."</div>";
				print "<td valign='top'>".$ss.$sss."</td>";
			}
			print "</tr></table><div align=center><input type=submit value='далее'> <input type=button value='отмена' onClick='history.back()'></div>";
		}



		print "</form>";
	break;

	// Выбор связи ветви для текущей подрубрики
	case "featrubric_link":
		if(!empty($_POST['rub'])){



		} else {
			print "<h1>Выбор связи ветви для текущей подрубрики</h1>";
			list($cur) = $database -> getArrayOfQuery("SELECT ".DB_PREFIX."rubric.ID_RUBRIC,".DB_PREFIX."rubric.rubric_name FROM ".DB_PREFIX."rubric WHERE ID_FEATURE=".$id);

			$res = $database -> query("SELECT ".DB_PREFIX."rubric.ID_RUBRIC,".DB_PREFIX."rubric.rubric_name FROM ".DB_PREFIX."feature_rubric NATURAL JOIN ".DB_PREFIX."rubric WHERE ID_FEATURE=".$id);
			print "<form method='post'>";
				print "<div><input type='radio' name='rub' id='rub' value='0'".((mysql_num_rows($res)==0)?" checked":"")."/><label for='rub'><b>нет связи</b></label></div>";
			while( $line = mysql_fetch_array($res,MYSQL_ASSOC) ){
				print "<div><input type='radio' name='rub' id='rub".$line['ID_RUBRIC']."' value='".$line['ID_RUBRIC']."'".(($line['ID_RUBRIC']==0)?" checked":"")."/><label for='rub".$line['ID_RUBRIC']."'>".$line['rubric_name']."</label></div>";
			}
			print "</form>";
		}
	break;

	// градация
	case "graduation":
		$database -> query("UPDATE ".DB_PREFIX."rubric_features SET rubricfeature_graduation=0 WHERE ID_FEATURE<>".$id." and ID_RUBRIC=".$rubric_id);
		if(!empty($rubric_id)) $database -> query("UPDATE ".DB_PREFIX."rubric_features SET rubricfeature_graduation=1 WHERE ID_FEATURE=".$id." and ID_RUBRIC=".$rubric_id);
		unset($_GET['action']);
	break;

	// выводить ли характеристику в листинге в древе?
	case "ls_man":
		$ls = $database -> getArrayOfQuery("SELECT rubricfeature_ls_man FROM ".DB_PREFIX."rubric_features WHERE ID_FEATURE=".$id." and ID_RUBRIC=".$rubric_id);
		$ls = (int)!(bool)(int)$ls[0];	//die("UPDATE ".DB_PREFIX."rubric_features SET rubricfeature_ls_man=$ls WHERE ID_FEATURE=".$id." and ID_RUBRIC=".$rubric_id);
		$database -> query("UPDATE ".DB_PREFIX."rubric_features SET rubricfeature_ls_man=$ls WHERE ID_FEATURE=".$id." and ID_RUBRIC=".$rubric_id);
		teRedirect(teGetUrlQuery());
	break;

	// выводить ли характеристику в листинге в сайте?
	case "ls_pub":
		$ls = $database -> getArrayOfQuery("SELECT rubricfeature_ls_pub FROM ".DB_PREFIX."rubric_features WHERE ID_FEATURE=".$id." and ID_RUBRIC=".$rubric_id);
		$ls = (int)!(bool)(int)$ls[0];
		$database -> query("UPDATE ".DB_PREFIX."rubric_features SET rubricfeature_ls_pub=$ls WHERE ID_FEATURE=".$id." and ID_RUBRIC=".$rubric_id);
		teRedirect(teGetUrlQuery());
	break;

	// перенос данных одной характеристики в другую
	case "move_data":
		include "features_move_data.php";
	break;
}

// вывод характеристик
if(!isset($_GET['action'])){

	// функция нужна для проставления правильного порядка рубрик в разделе или подрубрике
	function optimizeNum(){
		global $database;
		global $rubric_id;
		$i = 0;
		$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=$rubric_id ORDER BY rubricfeature_pos");
		while($line=mysql_fetch_array($res,MYSQL_ASSOC)){
			$database->query("UPDATE ".DB_PREFIX."rubric_features SET rubricfeature_pos=".++$i." WHERE ID_RUBRIC=$rubric_id and ID_FEATURE=".$line['ID_FEATURE']);
		}
	}

	// cохранение позиций (если запрошено)
	if( !empty( $_POST['savepos'] ) ){
		foreach($_POST['pos'] AS $id_pos => $pos){
			$database->query("UPDATE ".DB_PREFIX."rubric_features SET rubricfeature_pos=".$pos." WHERE ID_RUBRIC=$rubric_id and ID_FEATURE=".$id_pos);
		}
		optimizeNum();
		if(!empty($_POST['grad']))
		foreach($_POST['grad'] AS $id_grad => $grad){
			$database->query("UPDATE ".DB_PREFIX."rubric_features SET rubricfeature_graduation=".$grad." WHERE ID_RUBRIC=$rubric_id and ID_FEATURE=".$id_grad);
		}
	}

	// addHelp("rubric_feature.html");

	print_link_up(teGetUrlQuery("=rubric"),"назад, к списку рубрик");

	// заголовок
	if($rubric_id==0){
		print "<h2>Общие характеристики ".$rtype['rubrictype_r_m']."</h2>";
	} else {
		print "<h2>Характеристики ".$rtype['rubrictype_r_m']." категории ".getRubricName($rubric_id)."</h2>";
	}

	// если у текущей рубрики есть подрубрики - то is_pos = false;
	// нужно для того, чтобы не показывать поля редактирования порядка характеристик там, где не требуется
	$is_pos = true;
	if( $database->getArrayOfQuery("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent=$rubric_id")){
		$is_pos = false;
	}

	// запрос всех хар-к этой рубрики
	$query = "SELECT ".DB_PREFIX."features.*,".DB_PREFIX."rubric_features.* FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE rubric_type=$type and feature_deleted=0 and ID_RUBRIC=".$rubric_id." ORDER BY rubricfeature_pos, feature_text";
	$res = $database->query($query);

	print "<div class='add-fe'  ><a href='".teGetUrlQuery("action=add","id=$id")."'>Добавить характеристику ".$rtype['rubrictype_r_m']."</a></div>";

	if( mysql_num_rows($res)>0 ){
		$OList = new teList($query, 50);
		$OList->addToHead("<a href='".teGetUrlQuery("idshow=".(($idshow=="id")?"date":"id"))."'>".(($idshow=="id")?"ID":"Дата последнего изменения")."</a>","width=1px");
		if($is_pos) $OList->addToHead("Позиция","width=1px");
		$OList->addToHead("&ndash; &infin;","width=1px");
		$OList->addToHead("Наименование","");
		$OList->addToHead("<small><nobr>В листинге</nobr><wbr> СУ/сайт</small>","width='1%'");
		$OList->addToHead("Тип данных","width=1%");
		if($is_pos) $OList->addToHead("Градация ","width=1%");
		$OList->addToHead("Действия","colspan=3 width=1%");
		$graduation = $graduation1 = $graduation00 = $graduation10 = false;
		$i = 0;
		while($OList->row()){
			// переменные для правильного вывода (тут все просто)
			if($OList->getValue("feature_graduation")==1) $graduation = true; else $graduation = false;
			if($OList->getValue("rubricfeature_graduation")==1) $graduation1 = true; else $graduation1 = false;
			if($OList->getValue("feature_require")==1) $require = true; else $require = false;
			if($OList->getValue("rubricfeature_ls_man")==1) $ls_man = true; else $ls_man = false;
			if($OList->getValue("rubricfeature_ls_pub")==1) $ls_pub = true; else $ls_pub = false;
			if($graduation) $graduation00 = true;
			if($graduation1) $graduation10 = true;
			if(!$is_pos){
				$graduation = $graduation1 = false;
			}

			if($idshow=="id"){
				$shid = getIdToPrint("features","{ID_FEATURE}");
			} else {
				$shid = dateOfChange("features",$OList->getValue("ID_FEATURE"));
			}
			$OList->addUserField($shid);


			if($is_pos) $OList->addUserField("<div align=center><input tabindex='".++$i."' type=text size=3 maxlenght=3 name='pos[{ID_FEATURE}]' value='{rubricfeature_pos}' onKeyUp='this.form.formsub.disabled=false;'></div>");
			$OList->addUserField("<center>".(($OList->getValue("feature_multiple")==0)?"&ndash;":"&infin;")."</center>");

			$OList->addUserField(((!$graduation)?"":"<b>").((!$graduation1)?"":"<u>")."{feature_text}".(($require)?" <font color=red>*</font>":"").((!$graduation1)?"":"</u>").((!$graduation)?"":"</b>"),( $OList->getValue("feature_enable")==0 )?"disabled":"");

			$OList->addUserField("<center><a href='".teGetUrlQuery("action=ls_man","id={ID_FEATURE}")."'>".(($ls_man)?"да":"нет")."</a> / <a href='".teGetUrlQuery("action=ls_pub","id={ID_FEATURE}")."'>".(($ls_pub)?"да":"нет")."</a></center>");

			if( $OList->getValue("feature_type")==4 ){
				// справочник
				$arrcnt = $database->getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$OList->getValue("ID_FEATURE"));
				$arrcnt = (int)$arrcnt[0];
				$OList->addUserField("<a href='".teGetUrlQuery("action=featuresanswers","id={ID_FEATURE}")."' title='Редактировать список возможных значений характеристики «{feature_text}»'><nobr>Справочник ($arrcnt)</nobr></a>");
			} elseif( $OList->getValue("feature_type")==5 ){
				// рубрика
				list($cnt) = $database -> getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."feature_rubric WHERE ID_FEATURE=".$OList->getValue("ID_FEATURE"));
				$OList->addUserField("
					<a href='".teGetUrlQuery("action=featurerubrics","feature_type=5","id={ID_FEATURE}")."' title='Выбрать ветку - тип данных характеристики «{feature_text}»'>Ветвь</a>
					"./*(($cnt>1)?"<span>".buttonLink(teGetUrlQuery("action=featrubric_link","id={ID_FEATURE}"))."</span>":"").*/ "
				");
			} elseif( $OList->getValue("feature_type")==9 ){
				// раздел
				$OList->addUserField("<a href='".teGetUrlQuery("action=section","feature_type=9","id={ID_FEATURE}")."' title='Выбрать раздел - связь характеристики «{feature_text}»'>Раздел</a>");
			} elseif( $OList->getValue("feature_type")==10 ){
				// раздел
				$OList->addUserField("<a href='".teGetUrlQuery("action=section2","feature_type=10","id={ID_FEATURE}")."' title='Выбрать раздел - связь характеристики «{feature_text}»'>Раздел записей</a>");
			} elseif( $OList->getValue("feature_type")==1 ) {
				// число
				$OList->addUserField("число");
			} elseif( $OList->getValue("feature_type")==2 ) {
				// текст
				$OList->addUserField("текст");
			} elseif( $OList->getValue("feature_type")==7 ) {
				// большой текст
				$OList->addUserField("большой текст");
			} elseif( $OList->getValue("feature_type")==3 ) {
				// логическое
				$OList->addUserField("лог");
			} elseif( $OList->getValue("feature_type")==6 ) {
				// файл
				$OList->addUserField("<a href='".teGetUrlQuery("action=featurefiles","id={ID_FEATURE}")."' title='Редактировать список возможных типов файлов характеристики «%feature_text%»'>файл</a>");
			} elseif( $OList->getValue("feature_type")==8 ) {
				// дата
				$OList->addUserField("дата");
			}

			if($is_pos){
			if( $OList->getValue("feature_type")==4 && $OList->getValue("feature_graduation")==1 ){
				//$ln = $database->getArrayOfQuery("SELECT rubricfeature_graduation FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=$rubric_id and ID_FEATURE=".$OList->getValue("ID_FEATURE"));
				$OList->addUserField("<div align='center'><input type='text' size='1' name='grad[{ID_FEATURE}]' value='".(int)$OList->getValue("rubricfeature_graduation")."'  onKeyUp='this.form.formsub.disabled=false;' /></div>");
			} else {
				$OList->addUserField("<div align='center'>-</div>");
			}
			}


			$OList->addUserField(buttonEdit(teGetUrlQuery("action=edit","id={ID_FEATURE}"),"Редактировать свойства характеристики «%feature_text%»"));

			if( $OList->getValue("feature_enable")==1 ){
				$OList->addUserField(buttonDisable(teGetUrlQuery("action=disable","id={ID_FEATURE}")));
				//$OList->addUserField("<a class=del href='".teGetUrlQuery("action=disable","id={ID_FEATURE}")."' title='Запретить показ характеристики «%feature_text%» посетителям сайта'>выкл.</a>");
			} else {
				$OList->addUserField(buttonEnable(teGetUrlQuery("action=enable","id={ID_FEATURE}")));
				//$OList->addUserField("<a class=add href='".teGetUrlQuery("action=enable","id={ID_FEATURE}")."' title='Разрешить показ характеристики «%feature_text%» посетителям сайта'>вкл.</a>");
			}
			$OList->addUserField(buttonDelete("javascript: if(confirm(\"Удалить характеристику «%feature_text%»?\")) location.href =\"".teGetUrlQuery("action=delete","id={ID_FEATURE}")."\"","Удалить характеристику «%feature_text%»"));

		}
		$OList->addParamTable('');

		print "<form method=post><input type=hidden name=savepos value=1>";

		echo($OList->getHTML());
		unset($OList);

		print "<div align='center' style='margin:5px'><input id='formsub' disabled type='submit' value='Сохранить изменения'></div></form>";
		if($is_pos) if($graduation00) print "<div><b>Жирным</b> шрифтом выделены те характеристики, по которым будет включена градация на сайте.</div>";
		print "<div><b><font color=red>*</font></b> - обязательные характеристики.</div>";
	}

	// если характеристик много, то внизу показываем дубликат ссылки "добавить характеристику"
	if( mysql_num_rows($res)>10 ) print "<div class=add><a href='".teGetUrlQuery("action=add","id=$id")."'>Добавить характеристику ".$rtype['rubrictype_r_m']."</a></div>";


	print "<div class='dop-func'><h4>Дополнительные функции</h4><ul>";
	print "<li><a href='".teGetUrlQuery("action=move_data")."'>Перенос данных из характеристики в другую характеристику</a></li>";
	print "</ul></div>";
}

?>