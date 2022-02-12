<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  Управление пользователями
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/
@$action = $_GET['action'];

// проверка на доступ
if($_USER['group']>2 && $action!='view')
{	print 'доступ запрещен';	die;
}
$id = (int)@$_GET['id'];
if(!empty($id) && $_USER['id']!=0 && $_USER['id']!=$id && $action!='view'){
	//combase();
	list($access) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and database_id=".DB_ID." and ID_USER=".$id);
	list($access_create) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$_USER['id']." and database_id=".DB_ID." and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0");
	if($access>=$access_create)
	{
		print 'доступ запрещен';
		die;
	}
}
$db_id = (int)@$_GET['db_id'];


// что показывать (для рута)
$view = (@$_GET['view'])?@$_GET['view']:"thisbaseonly";
addGet("view",$view);
$arrview1 = array(0,1);
$arrview2 = array("allbases","thisbaseonly");
$arrview3 = array("Учетные записи всех баз","Учетные записи этой базы");
$view = (int)str_replace($arrview2,$arrview1,$view);

teAddCSSCode("
	.db1{font-weight:800;}
	.db1 img {height:2em;}
	.db2{visibility:hidden;}
	.db2 img {height:1em;}
");
teAddJSScript("
	function hacc(id,val){
		document.getElementById(id).className = (val==1)?'db1':'db2';
	}
");

// названия типов доступа
$arrtypeaccess = array(0=>"<font color='darkred'>никто</font>",1=>"пользователь",2=>"администратор",3=>"<font color='darkgreen'>суперадминистратор</font>");

	print_link_main();

	$user_type = 0;


	// действия
	if(isset($_GET['action'])){
	switch($action){
		case 'add2':
		 addSubMenuUp();
		 if($user_unis)
		 {
			$frm = new teForm("form1","post");
			$frm->addTitle("Предоставление доступа на эту базу");
			//combase();
			$line = $database -> query("
				SELECT ID_USER
				FROM ".DB_PREFIX."users NATURAL JOIN ".DB_PREFIX."users_privilegies
				WHERE user_deleted=0 and database_id=".DB_ID."
				and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and access_type>0
				and user_visible=1
			");
			$arr_users = array();
			while($row = mysql_fetch_array($line))$arr_users[] = $row[0];
			$line = $database -> query("
				SELECT ".DB_PREFIX."users.*
				FROM ".DB_PREFIX."users NATURAL JOIN ".DB_PREFIX."users_privilegies
				WHERE user_deleted=0 and database_id=5
				and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and access_type>0
				and user_visible=1 order by user_name
			");
			$frm->addf_selectGroup("sel_user", "Выберите пользователя:");
			while($row_user = mysql_fetch_array($line))
			{				if(!in_array($row_user['ID_USER'],$arr_users))
				{					$frm->addf_selectItem("sel_user", $row_user['ID_USER'], $row_user['user_name'].' '.$row_user['user_sname']);				}			}
			$frm->addf_selectGroup("sel_right", "Выберите тип доступа:");
			$frm->addf_selectItem("sel_right", 1, 'Пользователь');
			if($_USER['group']==1)$frm->addf_selectItem("sel_right", 2, 'Администратор');
			if($_USER['id']==0)$frm->addf_selectItem("sel_right", 3, 'Суперадминистратор');
			$frm->setf_require("sel_user","sel_right");
			print "<div align='center'>";
			if(!$frm->send()){				list($access_create) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$_USER['id']." and database_id=".DB_ID." and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0");
				$user = $frm->get_value("sel_user");
				$access = $frm->get_value("sel_right");
				if($_USER['id']==0 || $access<$access_create)
				{
					$database -> query("INSERT INTO ".DB_PREFIX."users_privilegies (ID_USER,database_id,ID_RUBRIC_TYPE,ID_RUBRIC,access_type) VALUES ($user,".DB_ID.",0,0,$access)",false);
					teRedirect(teGetUrlQuery());
				}			}
			print "</div>";
		 }
		break;
		case 'add':
		case 'edit':
			//combase();
			addSubMenuUp();
			if($action=='edit')
			{
				addSubMenuAccess();
				addSubMenuDelete();
            }
			$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE ID_USER=".(int)($id),MYSQL_ASSOC);

			if(empty($id)){
				print "<h2>Добавление нового ".(($user_type==2)?"администратора":"пользователя системы")."</h2>";
			} else {
				print "<h2>Редактирование данных ".(($user_type==2)?"администратора":"пользователя системы")." «".$line['user_name']."»</h2>";
			}

			print "<div align=center>";
			$frm = new teForm("form1","post");
			$frm->addf_text("user_login1", "Логин", @$line['user_login']);
			$frm->addf_desc("user_login1", "Будет использоваться для входа в аккаунт");
			$frm->addf_ereg("user_login1", "^[1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM\-\_]*");

			$frm->addf_text("user_sname", "Фамилия", @$line['user_sname']);
			$frm->addf_text("user_name", "Имя", @$line['user_name']);
			$frm->addf_text("user_pname", "Отчество", @$line['user_pname']);

			teAddJSFile("./js/jq.datepicker.js");
			teAddJSFile("./js/jq.maskedinput.js");
			teAddCSSFile("./js/jq.datepicker.css");
			teAddJSScript("$"."(document).ready(function(){"."$"."('#user_dob_0').attachDatepicker();});");
			teAddJSScript("(function(\$) {\$(function() {\$('#user_dob_0').mask('99.99.9999');});})(jQuery);");
			$dob = @$line['user_dob'];
			$dob = substr($dob,8,2).".".substr($dob,5,2).".".substr($dob,0,4);
			$frm->addf_text("user_dob", "Дата рождения", $dob);

			$frm->addf_text("user_email", "E-mail(ы)", @$line['user_email']);
//			$frm->addf_ereg("user_email", "^[a-z0-9_-]+[a-z0-9_.-]*@[a-z0-9_-]+[a-z0-9_.-]*\.[a-z]{2,5}$");

			$frm->addf_text("user_telephone", "Телефон", @$line['user_telephone']);
			$frm->addf_text("user_icq", "ICQ", @$line['user_icq']);

			if(($_USER['group']==1 && !empty($id)) || $_USER['group']<3){
				$frm->addf_password("user_passwd1", "Пароль", true);
				$frm->addf_desc("user_passwd1", ((empty($id))?"":"Если поле останется пустым, пароль не изменится!<br>")."Рекомендуемая длина пароля от 6 до 12 символов");
				$frm->addf_passRule("user_passwd1",6,12);
			}

			$frm->setf_require("user_login1","user_sname","user_name","user_email",((empty($id))?"user_passwd1":"user_login1"));

			if(!$frm->send()){
				$dob = $frm->get_value('user_dob');
				$dob = substr($dob,6,4)."-".substr($dob,3,2)."-".substr($dob,0,2);
				if( !$database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE user_deleted=0 and user_login='".$frm->get_value("user_login1")."'".($id>0?" and ID_USER<>".$id:"")) ){
					if(empty($id)){
						// если такого ника еще нет, записываем в БД
						$database -> query("INSERT INTO ".DB_PREFIX."users (user_login,user_telephone,user_icq,user_email,user_name,user_sname,user_pname,user_dob,user_passwd,user_visible,user_deleted)
							VALUES (
								'".$frm->get_value('user_login1')."',
								'".$frm->get_value('user_telephone')."',
								'".$frm->get_value('user_icq')."',
								'".$frm->get_value('user_email')."',
								'".$frm->get_value('user_name')."',
								'".$frm->get_value('user_sname')."',
								'".$frm->get_value('user_pname')."',
								'".$dob."',
								'".md5($frm->get_value('user_passwd1'))."',
								1,
								0
							)
						");
						$id = $database->id();
						list($id) = $database -> getArrayOfQuery("SELECT ID_USER FROM cprice_users ORDER BY ID_USER DESC");
						$database -> query("INSERT INTO ".DB_PREFIX."users_privilegies (ID_USER,database_id,ID_RUBRIC_TYPE,ID_RUBRIC,access_type) VALUES ($id,".DB_ID.",0,0,1)",false);
						teRedirect(teGetUrlQuery("action=access","id=$id"));
					} else {
						$database -> query("UPDATE ".DB_PREFIX."users SET
								user_login='".$frm->get_value('user_login1')."',
								user_telephone='".$frm->get_value('user_telephone')."',
								user_icq='".$frm->get_value('user_icq')."',
								user_email='".$frm->get_value('user_email')."',
								user_name='".$frm->get_value('user_name')."',
								user_sname='".$frm->get_value('user_sname')."',
								user_pname='".$frm->get_value('user_pname')."' ,
								user_dob='".$dob."'
								".(($frm->get_value('user_passwd1')!="")?",user_passwd='".md5($frm->get_value('user_passwd1'))."'":"")."
							WHERE ID_USER=".(int)$_GET['id']
						);
						teRedirect(teGetUrlQuery());
					}
				} else {
					// если такой ник уже есть, посылаем форму обратно
					$frm->errorValue("user_login1","Пользователь с таким логином уже существует!");
					$frm->send();
				}

			}

		break;


		// просмотр пользователя
		case 'view':
			//combase();
			if(!$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE ID_USER=".$id,MYSQL_ASSOC)){
				teRedirect(teGetUrlQuery("action="));
			} else {
				if($_USER['group']<3)addSubMenuUp();
				list($access) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and database_id=".DB_ID." and ID_USER=".$id);
				list($access_create) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$_USER['id']." and database_id=".DB_ID." and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0");
				if($access<$access_create)
				{
					addSubMenuAccess();
					addSubMenuEdit();
					if($line['user_visible']==1)addSubMenuDisable(); else addSubMenuEnable();
					addSubMenuDelete();
				}

				print "<h2>Просмотр данных пользователя: $line[user_sname] $line[user_name]</h2>";
				print "<div class='ls'><table align='center'>";
				if($_USER['group']<3)print "<tr><td class='ls_name'>ID</td><td class='ls_val'>$line[ID_USER]</td></tr>";
				if($_USER['group']<3)print "<tr><td class='ls_name'>Логин</td><td class='ls_val'>$line[user_login]</td></tr>";
				print "<tr><td class='ls_name'>Фамилия</td><td class='ls_val'>$line[user_sname]</td></tr>";
				print "<tr><td class='ls_name'>Имя</td><td class='ls_val'>$line[user_name]</td></tr>";
				print "<tr><td class='ls_name'>Отчество</td><td class='ls_val'>$line[user_pname]</td></tr>";
				print "<tr><td class='ls_name'>Дата рождения</td><td class='ls_val'>$line[user_dob]</td></tr>";
				print "<tr><td class='ls_name'>e-mail</td><td class='ls_val'><a href='mailto:$line[user_email]'>$line[user_email]</a></td></tr>";
				print "<tr><td class='ls_name'>Телефон</td><td class='ls_val'>$line[user_telephone]</td></tr>";
				print "<tr><td class='ls_name'>ICQ</td><td class='ls_val'>$line[user_icq]</td></tr>";
				print "</table></div>";
			}
		break;

		// просмотр сессий (писал Галлямов)
		case 'ip':
		 if($_USER['id']==0){
			//combase();
			if(!$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE ID_USER=".$id,MYSQL_ASSOC)){
				teRedirect(teGetUrlQuery("action="));
			} else {
				addSubMenuUp();
				print "<h2>Просмотр активности пользователя $line[user_sname] $line[user_name] $line[user_pname]</h2>";
				$OList = new teList("SELECT * FROM ".DB_PREFIX."users_activity where ID_USER=".$id." order by ua_dt_begin desc",40);
				$OList->addToHead('Начало сессии');
				$OList->addToHead('Длительность сессии,<br/>час:мин');
				$OList->addToHead('IP');
				$OList->addToHead('Браузер');
				$OList->addToHead('Количество страниц');
				$OList->query();
				while($OList->row()){
					$d1 = strtotime($OList->getValue('ua_dt_begin'));
					$d2 = strtotime($OList->getValue('ua_dt_end'));
					$h = intval(($d2-$d1)/3600);
					$m = intval(($d2-$d1-$h*3600)/60);
					$br = explode(" ",$OList->getValue('ua_agent'));

					$OList->addUserField(date("d.m.Y H:i",$d1));
					$OList->addUserField(sprintf("%02d:%02d", $h, $m),'style="text-align:center"');
					$OList->addUserField('{ua_ip}');
					$OList->addUserField($br[0]);
					$OList->addUserField('{ua_viewpages}','style="text-align:center"');
				}

				$OList->addParamTable('');
				echo($OList->getHTML());
				unset($OList);
			}
		 }else print 'Доступ запрещен';
		break;

		// писал Галлямов
		case 'history':
		 if($_USER['id']==0){		 	//combase();
			if(!$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE ID_USER=".$id,MYSQL_ASSOC)){
				teRedirect(teGetUrlQuery("action="));
			} else {
				addSubMenuUp();
		        addSubMenu(teGetUrlQuery("pg=users","action=changes","id=".$id),'История изменений(changes)');
				print "<h2>История посещений пользователя $line[user_sname] $line[user_name] $line[user_pname]</h2>";
				if(@$_GET['msg']=='del') print '<div style="color:blue;text-align:center;">Записи удалены</div>';
			    print '
			    <form action="" method="get">
					<input type="hidden" name="pg" value="users" />
					<input type="hidden" name="action" value="'.$_GET['action'].'" />
					<input type="hidden" name="id" value="'.$id.'" />
					<p style="text-align:center;">';
				$per = per_time();
			    print '<br/><br/>
				    <input type="submit" value="Сформировать историю за выбранный период" /><br/><br/>
				    <input type="button" value="Удалить историю за выбранный период" onclick="if(confirm(\'Удалить историю за выбранный период?\')){this.form.action.value=\'del_history\';this.form.submit();}"/>
				    </p>
			    </form>
			    ';
				$OList = new teList("SELECT * FROM ".DB_PREFIX."users_history where ID_USER=".$id." && tdate>{$per[0]} && tdate<{$per[1]} order by tdate desc",40);
				$OList->addToHead('Дата/время');
				$OList->addToHead('База');
				$OList->addToHead('URL');
				$OList->query();
				while($OList->row()){
					$d1 = date($OList->getValue('tdate'));
					$bd_id = $OList->getValue('DB_ID');
                    $base = $hosts[$bd_id]['name'];
                    $url = $OList->getValue('URL');
                    if(strpos($url,'curbase')==0)
                    {
	                    if(strpos($url,'?')>0)$url.='&curbase='.$bd_id;
	                    else $url.='?curbase='.$bd_id;
	                }
					$OList->addUserField(date("d.m.Y H:i",$d1));
					$OList->addUserField($base);
					$OList->addUserField('<a href="'.$url.'">'.'{URL}'.'</a>');
				}

				$OList->addParamTable('');
				echo($OList->getHTML());
				unset($OList);
			}
		 }else print 'Доступ запрещен';
		break;

		// писал Галлямов
		case 'changes':
		 if($_USER['id']==0){
			//combase();
			if(!$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE ID_USER=".$id,MYSQL_ASSOC)){
				teRedirect(teGetUrlQuery("action="));
			} else {
				addSubMenuUp();
		        addSubMenu(teGetUrlQuery("pg=users","action=history","id=".$id),'История посещений');
		        @$db_id = (int)$_GET['db_id'];
				print "<h2>История изменений записей пользователем $line[user_sname] $line[user_name] $line[user_pname]</h2>";
			    print '<div align="center">
			    <form action="" method="get">
					<input type="hidden" name="pg" value="users" />
					<input type="hidden" name="action" value="'.$_GET['action'].'" />
					<input type="hidden" name="id" value="'.$id.'" />
					<input type="hidden" name="db_id" value="'.$db_id.'" />';
				$per = per_time();
			    print '<br/><br/>
				    <input type="submit" value="Сформировать историю за выбранный период" />
			    </form>
			    ';
			    $table='';
			    if($db_id>0)
			    {			        addSubMenu(teGetUrlQuery("pg=users","action=changes","id=".$id).$per[4],'История изменений(changes) всех баз');
			    	print '<h3>'.$hosts[$db_id]['name'].'<h3>';	    			$database -> teDatabase($hosts[$db_id]['db_host'], $hosts[$db_id]['db_user'], $hosts[$db_id]['db_pass'], $hosts[$db_id]['db_name']);
	    			$OList = new teList("SELECT * FROM ".DB_PREFIX."changes WHERE ID_USER={$id} && change_dt>'{$per[2]}' && change_dt<'{$per[3]}' order by change_dt",50);
					$OList->addToHead('Дата/время');
					$OList->addToHead('Таблица');
					$OList->addToHead('ИД записи');
					$OList->addToHead('Тип измененния');
					$OList->query();
					while($OList->row()){
						$d1 = strtotime($OList->getValue('change_dt'));
						$type = $OList->getValue('change_type');
						$type_str ='';
						switch($type){							case 1: $type_str='ДОбавление'; break;							case 2: $type_str='Изменение'; break;
							case 3: $type_str='Удаление'; break;
						}
						$OList->addUserField(date("d.m.Y H:i",$d1));
						$OList->addUserField('{change_table}');
						$OList->addUserField('{change_row}');
						$OList->addUserField($type_str);
					}

					$OList->addParamTable('');
					echo($OList->getHTML());
					unset($OList);
			    }
			    else
			    {
				    foreach($hosts as $key=>$host)
				    {	                    if($key>0)
	                    {
							if(isset($host['db_name']))
							{
				    			$database -> teDatabase($host['db_host'], $host['db_user'], $host['db_pass'], $host['db_name']);
			                    $line = $database -> getArrayOfQuery("SELECT count(ID_CHANGE) FROM ".DB_PREFIX."changes WHERE ID_USER={$id} && change_dt>'{$per[2]}' && change_dt<'{$per[3]}'");
						    	$table .= '<tr><td>'.$key.'</td><td>'.$host['name'].'</td><td>'.intval($line[0]).'</td><td><a href="'.teGetUrlQuery("pg=users","action=changes","id=".$id,"db_id=".$key).$per[4].'">подробнее</a></td></tr>';
					    	}
				    	}				    }
				    if(!empty($table))
				    	print '<br/><br/><table border="1"><tr><td>DB_ID</td><td>База</td><td colspan="2">Количество изменениний</td></tr>'.$table.'</table>';
				}
				print '</div>';
			}
		 }else print 'Доступ запрещен';
		break;

		// Писал Галлямов
		case 'del_history':
		  if($_USER['id']==0){
			//combase();
			@$day1 = (int)$_GET['day1'];
			@$month1 = (int)$_GET['month1'];
			@$year1 = (int)$_GET['year1'];
			@$day2 = (int)$_GET['day2'];
			@$month2 = (int)$_GET['month2'];
			@$year2 = (int)$_GET['year2'];
			$bdate = mktime(0,0,0,$month1,$day1,$year1);
			$edate = mktime(23,59,59,$month2,$day2,$year2);
			$database -> query("DELETE FROM ".DB_PREFIX."users_history WHERE ID_USER=".$id." && tdate>{$bdate} && tdate<{$edate}");
			teRedirect(teGetUrlQuery("pg=users","action=history","id=".$id,"msg=del"));
		  }
		break;

		// отключение юзера
		case 'disable':
			//combase();
			$database -> query("UPDATE ".DB_PREFIX."users SET user_visible = 0 WHERE ID_USER=".$id);
			unset($_GET['action']);
		break;

		// включение юзера
		case 'enable':
			//combase();
			$database -> query("UPDATE ".DB_PREFIX."users SET user_visible = 1 WHERE ID_USER=".$id);
			unset($_GET['action']);
		break;

		// удаление юзера
		case 'delete':
			//combase();
			$database -> query("UPDATE ".DB_PREFIX."users SET user_deleted = 1 WHERE ID_USER=".$id, true, 3);
			unset($_GET['action']);
		break;


		// разделение доступа юзеру по базам
		case 'access':
			if( !isset($_POST['status']) ){
				// addHelp("users_rubric.html");
				//combase();
				addSubMenuUp();

				$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE ID_USER=".$id,MYSQL_ASSOC);
				print "<h2>Управление доступа к базам пользователя «".$line['user_login']."»</h2>";
				print "<form method='post' name='acc' id='acc'>";
				print "<input type='hidden' id='fstatus' name='status' value='save' />";
				print "<table class='list'>";
				print "<tr>";
				print "<th rowspan='2' width='1px'>ID</th>";
				print "<th colspan='4' width='1px'>Доступ</th>";
				print "<th rowspan='2'>Наименование базы</th>";
				print "</tr>";
				print "<tr><th width='1px'>нет</th><th width='1px'>ограниченный</th><th width='1px'>полный</th><th width='1px'>суперадминистратор</th></tr>";

				global $hosts;
				global $skinpath;
				foreach( $hosts AS $db_id => $cont ) if(empty($cont['cms'])) {
					list($access_create) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$_USER['id']." and database_id=$db_id and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0");
					if($access_create>1 || $_USER['id']==0)
					{
						if(!list($access) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=$id and database_id=$db_id and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0")){
							$access = 0;
						}
                        if($access<$access_create || $_USER['id']==0)
                        {
							print "<tr>";
							print "<td align='center'>".$db_id."</td>";
							print "<td align='center'><input type='radio' name='db[$db_id]' value='0' onclick=\"hacc('db_$db_id',0)\"".(($access==0)?" checked":"")."/></td>";
							print "<td align='center'><input type='radio' name='db[$db_id]' value='1' onclick=\"hacc('db_$db_id',1)\"".(($access==1)?" checked":"")."/> <a href=\"javascript: document.getElementById('fstatus').value='$db_id';document.getElementById('acc').submit();\" id='db_$db_id' class='".(($access==1)?"db1":"db2")."'><img src='{$skinpath}images/b_access_big.png' alt='Редактировать доступ' title='Редактировать доступ'/></a></td>";
							if($access_create>2 || $_USER['id']==0)
							{
								print "<td align='center'><input type='radio' name='db[$db_id]' value='2' onclick=\"hacc('db_$db_id',0)\"".(($access==2)?" checked":"")."/></td>";
								if($_USER['id']==0)print "<td align='center'><input type='radio' name='db[$db_id]' value='3' onclick=\"hacc('db_$db_id',0)\"".(($access==3)?" checked":"")."/></td>";
								else print "<td></td>";
							}
							else print "<td colspan='2'></td>";
							print "<td>".$cont['name']."</td>";
							print "</tr>";
						}
					}
				}
				print "</table>";
				print "<div align='center'><input type='submit' value='Сохранить изменения' /></div>";
				print "</form>";
			} else {
				//combase();
				$database -> query("DELETE FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$id." and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0", false);
				foreach($_POST['db'] AS $db_id1 => $db_val){
					list($access_create) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$_USER['id']." and database_id=$db_id1 and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0");
					if($access_create==3 && $db_val>2 && $_USER['id']!=0) continue;
					if($access_create==2 && $db_val>1 && $_USER['id']!=0) continue;
					if(($access_create>1 || $_USER['id']==0) && $db_val>0)
						$database -> query("INSERT INTO ".DB_PREFIX."users_privilegies (ID_USER,database_id,ID_RUBRIC_TYPE,ID_RUBRIC,access_type) VALUES ($id,$db_id1,0,0,".(int)$db_val.")",false);
				}

				if($_POST['status']!='save'){
					teRedirect(teGetUrlQuery("action=rubric","id=$id","db_id=".(int)$_POST['status']));
				} else {
					teRedirect(teGetUrlQuery());
				}

			}
		break;


		// разделение доступа юзеру по рубрикам
		case 'rubric':
		 //combase();
		 list($access_create) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$_USER['id']." and database_id=$db_id and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0");
         if($access_create>1 || $_USER['id']==0)
         {
			if( @$_POST['fstatus']=="save2" ){
				$database -> query("DELETE FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$id." and database_id=$db_id and ID_RUBRIC_TYPE>0 and ID_RUBRIC!=0 and ID_FEATURE=0", false);

				foreach( $_POST['rubric'] AS $rubrictype => $rubric ){
					foreach( $rubric AS $rubric_id => $on ){
						for($i=0;$i<5;$i++){
							if(empty($on[$i])) $on[$i]=0; else $on[$i]=1;
						}
						$database -> query("INSERT INTO ".DB_PREFIX."users_privilegies (ID_USER,database_id,ID_RUBRIC_TYPE,ID_RUBRIC,ID_FEATURE,access_type,access_m,access_v,access_a,access_e,access_d) VALUES ($id,$db_id,$rubrictype,$rubric_id,0,1,$on[0],$on[1],$on[2],$on[3],$on[4])",false);
					}
				}
				teRedirect(teGetUrlQuery("action=access","id=$id","db_id=$db_id"));
			} elseif( @$_POST['fstatus']=="save3" ){
				//combase();
				$d = (int)@$_POST['fval'];
				list($type) = $database->getArrayOfQuery("SELECT rubric_type FROM cprice_rubric WHERE ID_RUBRIC=$d");

				$database -> query("DELETE FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$id." and database_id=$db_id and ID_RUBRIC_TYPE=$type and ID_RUBRIC=$d and ID_FEATURE>0", false);

				foreach( $_POST['feat'] AS $fid => $on ){
					for($i=0;$i<3;$i++){
						if(empty($on[$i])) $on[$i]=0; else $on[$i]=1;
					}
					$database -> query("INSERT INTO ".DB_PREFIX."users_privilegies (ID_USER,database_id,ID_RUBRIC_TYPE,ID_RUBRIC,ID_FEATURE,access_type,access_m,access_v,access_a,access_e,access_d) VALUES ($id,$db_id,$type,$d,$fid,1,$on[0],$on[1],$on[2],0,0)",false);
				}
				teRedirect(teGetUrlQuery("action=access","id=$id","db_id=$db_id"));
			} else {
				global $hosts;
				// addHelp("users_rubric.html");
				//combase();
				addSubMenuUp(teGetUrlQuery("action=access","id=$id","db_id=$db_id"));

				$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE ID_USER=".$id,MYSQL_ASSOC);
				setTitle("Управление доступа пользователя «".$line['user_login']."» в базе «".$hosts[$db_id]['name']."»");

				otherbase($db_id);

				print "<form method='post' name='acc' id='acc'>";
				print "<input type=hidden name='checkvar' value='1'>";
				print "<table class='list'>";

				if(@$_POST['fstatus']!="rtype" && @$_POST['fstatus']!="feats"){
					if(@$_POST['fval']=='-1'){
						//combase();
						/// связи менеджеров по продажам и рубрикатора
						// удаление предыдущих связей
						$database -> query("DELETE FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$id." and database_id=$db_id and ID_RUBRIC_TYPE>0 and ID_RUBRIC=0", false);
						// добавление выбранных
						foreach( $_POST['rubric'] AS $rubrictype => $rubric ){
							foreach( $rubric AS $rubric_id => $on ){
								$database -> query("INSERT INTO ".DB_PREFIX."users_privilegies (ID_USER,database_id,ID_RUBRIC_TYPE,ID_RUBRIC,access_type) VALUES ($id,$db_id,$rubrictype,0,".(int)$on.")",false);
							}
						}
						teRedirect(teGetUrlQuery("action=access","id=$id"));
					}
					print "<input type='hidden' id='fstatus' name='fstatus' value='save1' />";
					print "<input type='hidden' id='fval' name='fval' value='-1' />";
					print "<tr><th rowspan='2'>Наименование раздела</th><th colspan='3'>Доступ</th></tr>";
					print "<tr>";
					print "<th>нет</th>";
					print "<th>ограниченный</th>";
					print "<th>полный</th>";
					print "<tr>";

					$res = $database->query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0");
					//combase();
					while(list($d,$rtn)=mysql_fetch_array($res)){

						// фрагмент отмечает в HTML уже отмеченные записи
						if(! list($access) = $database -> getArrayOfQuery("
							SELECT access_type
							FROM ".DB_PREFIX."users_privilegies
							WHERE ID_USER=".$id." and ID_RUBRIC=0 and ID_RUBRIC_TYPE=".$d." and database_id=$db_id
						") ){
							$access = 0;
						}

						//$sss = get_child($d, 0, "<div style='padding-left:".TREE_LEFT."em'><input type='radio' name='rubric[$d][{formname}]' id={id}1 {param1} value='0'><input type='radio' name='rubric[$d][{formname}]' id={id}2 {param2} value='1'><input type='radio' name='rubric[$d][{formname}]' id={id}3 {param3} value='2'>{name}</div>",$iii);

						print "<tr>";
						print "<td>$rtn</td>";
						print "<td align='center'><input type='radio' name='rubric[$d][0]' value='0' onclick=\"hacc('db_$d',0)\" ".(($access==0)?"checked":"")."/></td>";
						print "<td align='center'><input type='radio' name='rubric[$d][0]' value='1' onclick=\"hacc('db_$d',1)\" ".(($access==1)?"checked":"")."/> <a href=\"javascript: document.getElementById('fstatus').value='rtype';document.getElementById('fval').value='$d';document.getElementById('acc').submit();\" id='db_$d' class='".(($access==1)?"db1":"db2")."'><img src='{$skinpath}images/b_access_big.png' alt='Редактировать доступ' title='Редактировать доступ'/></a></td>";
						print "<td align='center'><input type='radio' name='rubric[$d][0]' value='2' onclick=\"hacc('db_$d',0)\" ".(($access==2)?"checked":"")."/></td>";
						print "</tr>";
					}


					// фрагмент отмечает в HTML уже отмеченные записи
					if(! list($access) = $database -> getArrayOfQuery("
						SELECT access_type
						FROM ".DB_PREFIX."users_privilegies
						WHERE ID_USER=".$id." and ID_RUBRIC=0 and ID_RUBRIC_TYPE=999999999 and database_id=$db_id
					") ){
						$access = 0;
					}
					print "<tr>";
					print "<td>РАЗДЕЛ «SEO»</td>";
					print "<td align='center'><input type='radio' name='rubric[999999999][0]' value='0' ".(($access==0)?"checked":"")."/></td>";
					print "<td align='center'><input type='radio' name='rubric[999999999][0]' value='1' ".(($access==1)?"checked":"")."/></td>";
					print "<td align='center'><input type='radio' name='rubric[999999999][0]' value='2' ".(($access==2)?"checked":"")."/></td>";
					print "</tr>";

					// фрагмент отмечает в HTML уже отмеченные записи
					if(! list($access) = $database -> getArrayOfQuery("
						SELECT access_type
						FROM ".DB_PREFIX."users_privilegies
						WHERE ID_USER=".$id." and ID_RUBRIC=0 and ID_RUBRIC_TYPE=999999998 and database_id=$db_id
					") ){
						$access = 0;
					}
					print "<tr>";
					print "<td>РАЗДЕЛ «АВТОНОВОСТИ»</td>";
					print "<td align='center'><input type='radio' name='rubric[999999998][0]' value='0' ".(($access==0)?"checked":"")."/></td>";
					print "<td align='center'>-</td>";
					print "<td align='center'><input type='radio' name='rubric[999999998][0]' value='2' ".(($access==2)?"checked":"")."/></td>";
					print "</tr>";


					// фрагмент отмечает в HTML уже отмеченные записи
					if(! list($access) = $database -> getArrayOfQuery("
						SELECT access_type
						FROM ".DB_PREFIX."users_privilegies
						WHERE ID_USER=".$id." and ID_RUBRIC=0 and ID_RUBRIC_TYPE=999999997 and database_id=$db_id
					") ){
						$access = 0;
					}
					print "<tr>";
					print "<td>Интернет-Магазин</td>";
					print "<td align='center'><input type='radio' name='rubric[999999997][0]' value='0' ".(($access==0)?"checked":"")."/></td>";
					print "<td align='center'>-</td>";
					print "<td align='center'><input type='radio' name='rubric[999999997][0]' value='2' ".(($access==2)?"checked":"")."/></td>";
					print "</tr>";


					// фрагмент отмечает в HTML уже отмеченные записи
					if(! list($access) = $database -> getArrayOfQuery("
						SELECT access_type
						FROM ".DB_PREFIX."users_privilegies
						WHERE ID_USER=".$id." and ID_RUBRIC=0 and ID_RUBRIC_TYPE=999999996 and database_id=$db_id
					") ){
						$access = 0;
					}
					print "<tr>";
					print "<td>РАЗДЕЛ «ДИЛЕРАМ»</td>";
					print "<td align='center'><input type='radio' name='rubric[999999996][0]' value='0' ".(($access==0)?"checked":"")."/></td>";
					print "<td align='center'>-</td>";
					print "<td align='center'><input type='radio' name='rubric[999999996][0]' value='2' ".(($access==2)?"checked":"")."/></td>";
					print "</tr>";

				} elseif(@$_POST['fstatus']=="rtype") {

					//combase();
					/// связи менеджеров по продажам и рубрикатора
					// удаление предыдущих связей
					$database -> query("DELETE FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$id." and database_id=$db_id and ID_RUBRIC_TYPE>0 and ID_RUBRIC=0", false);
					// добавление выбранных
					foreach( $_POST['rubric'] AS $rubrictype => $rubric ){
						foreach( $rubric AS $rubric_id => $on ){
							$database -> query("INSERT INTO ".DB_PREFIX."users_privilegies (ID_USER,database_id,ID_RUBRIC_TYPE,ID_RUBRIC,access_type) VALUES ($id,$db_id,$rubrictype,0,".(int)$on.")",false);
						}
					}
					curbase();

					print "<input type='hidden' id='fstatus' name='fstatus' value='save2' />";
					print "<input type='hidden' id='fval' name='fval' value='0' />";
					$iii = 0;
					function get_child($type, $id, $template, $cnt=false, $rrr = false, $level = 0){
						global $database;
						global $iii;
						global $db_id;

						otherbase($db_id);

						$res = $database -> query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_type=".$type." and rubric_parent=".$id." ORDER BY rubric_pos ASC, rubric_name ASC");
						// если $cnt не указан (ф-я вызвана 1-й раз), $i = 0, иначе $cnt + 1;
						$i = (!$cnt)?1:$cnt+1;

						$s = "";
						while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
							// вызываем эту же ф-ю с $id равным текущему
							$arr = get_child($type, $line['ID_RUBRIC'], $template, $i, true, $level+1);

							$s1 = @str_replace("{name}",$line['rubric_name'],$template);
							$s1 = str_replace("{formname}",$line['ID_RUBRIC'],$s1);
							$s1 = str_replace("{id}","ch".$i,$s1);

							// фрагмент отмечает в HTML уже отмеченные записи
							//combase();
							if(! list($access_m,$access_v,$access_a,$access_e,$access_d) = $database -> getArrayOfQuery("
								SELECT access_m,access_v,access_a,access_e,access_d
								FROM ".DB_PREFIX."users_privilegies
								WHERE ID_USER=".$GLOBALS['id']." and ID_RUBRIC=".$line['ID_RUBRIC']." and ID_RUBRIC_TYPE=".$type." and database_id=$db_id
							") ){
								$access_m = $access_v = $access_a = $access_e = $access_d = 0;
							}
							// фрагмент генерирует JS, который отмечает флажки всех детей текущей записи по изменению текущей записи.
							$ss1 = $ss2 = $ss3 = $ss4 = $ss5 = "";
							if( @($arr[1]>$i) ){
								$ss1 = " onclick=\"";
								for($ii=$i+1;$ii<=$arr[1];$ii++){
									$ss1 .= "this.form.ch".$ii."1.checked=";
								}
								$ss1 .= "this.checked;\"";
							}
							if( @($arr[1]>$i) ){
								$ss2 = " onclick=\"";
								for($ii=$i+1;$ii<=$arr[1];$ii++){
									$ss2 .= "this.form.ch".$ii."2.checked=";
								}
								$ss2 .= "this.checked;\" ";
							}
							if( @($arr[1]>$i) ){
								$ss3 = " onclick=\"";
								for($ii=$i+1;$ii<=$arr[1];$ii++){
									$ss3 .= "this.form.ch".$ii."3.checked=";
								}
								$ss3 .= "this.checked;\" ";
							}
							if( @($arr[1]>$i) ){
								$ss4 = " onclick=\"";
								for($ii=$i+1;$ii<=$arr[1];$ii++){
									$ss4 .= "this.form.ch".$ii."4.checked=";
								}
								$ss4 .= "this.checked;\" ";
							}
							if( @($arr[1]>$i) ){
								$ss5 = " onclick=\"";
								for($ii=$i+1;$ii<=$arr[1];$ii++){
									$ss5 .= "this.form.ch".$ii."5.checked=";
								}
								$ss5 .= "this.checked;\" ";
							}
							if($access_m==1) $ss1 .= " checked ";
							if($access_v==1) $ss2 .= " checked ";
							if($access_a==1) $ss3 .= " checked ";
							if($access_e==1) $ss4 .= " checked ";
							if($access_d==1) $ss5 .= " checked ";

							$s1 = str_replace("{param1}",$ss1,$s1);
							$s1 = str_replace("{param2}",$ss2,$s1);
							$s1 = str_replace("{param3}",$ss3,$s1);
							$s1 = str_replace("{param4}",$ss4,$s1);
							$s1 = str_replace("{param5}",$ss5,$s1);
							$s1 = str_replace("{level}",$level,$s1);
							$s = $s.$s1.$arr[0];

							@$i=@$arr[1]+1;
							$iii++;
						}
						if(!$rrr){
							return $s;
						} else {
							return array(0=>$s,1=>$i-1);
						}
					}
					$d = @$_POST['fval'];


					print "<tr><th width='1' rowspan='2'>ID</th><th rowspan='2'>Рубрика</th><th colspan='6'>Доступ</th></tr>";
					print "<tr>";
					print "<th><small>упр.</small></th>";
					print "<th><small>просм.</small></th>";
					print "<th><small>доб.</small></th>";
					print "<th><small>изм.</small></th>";
					print "<th><small>удал.</small></th>";
					print "<th><small>хар-ки</small></th>";
					print "</tr>";
					print get_child(
						$d,
						0,
						"
							<tr>
								<td>{formname}</td>
								<td><div style='padding-left:{level}em'>{name}</div></td>
								<td align='center'><input type='checkbox' name='rubric[$d][{formname}][0]' id={id}1 {param1}></td>
								<td align='center'><input type='checkbox' name='rubric[$d][{formname}][1]' id={id}2 {param2}></td>
								<td align='center'><input type='checkbox' name='rubric[$d][{formname}][2]' id={id}3 {param3}></td>
								<td align='center'><input type='checkbox' name='rubric[$d][{formname}][3]' id={id}4 {param4}></td>
								<td align='center'><input type='checkbox' name='rubric[$d][{formname}][4]' id={id}5 {param5}></td>
								<td align='center'><a href=\"javascript: document.getElementById('fstatus').value='feats';document.getElementById('fval').value='{formname}';document.getElementById('acc').submit();\" id='db_$d' class='".(($access==1)?"db1":"db2")."'><img src='{$skinpath}images/b_access_big.png' alt='хар-ки'/></a></td>
							</tr>
						",
						$iii
					);
				} elseif($_POST['fstatus']=="feats"){

					$d = @$_POST['fval'];
					$fres = $database->query("SELECT ".DB_PREFIX."features.ID_FEATURE,".DB_PREFIX."features.feature_text FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$d." ORDER BY rubricfeature_pos, feature_text");
					if( mysql_num_rows($res)>0 ){

					print "<input type='hidden' id='fstatus' name='fstatus' value='save3' />";
					print "<input type='hidden' id='fval' name='fval' value='$d' />";

						// путь
						list($type) = $database->getArrayOfQuery("SELECT rubric_type FROM cprice_rubric WHERE ID_RUBRIC=$d");
						list($typename) = $database->getArrayOfQuery("SELECT rubrictype_name FROM cprice_rubric_types WHERE ID_RUBRIC_TYPE=$type");
						print "<p>
							<a href='".teGetUrlQuery()."'>Персонал</a> /
							база данных «".$hosts[$db_id]['name']."» /
							пользователь «".$line['user_login']."» /
							вкладка «".$typename."» /
							рубрика ".getRubricName($d)."
						</p>";


						print "
							<tr><th rowspan='2'>ID</th><th rowspan='2'>Характеристика</th><th colspan='3'>доступ</th></tr>
							<tr>
								<th><small>упр.</small></th>
								<th><small>просм.</small></th>
								<th><small>изм.</small></th>
							</tr>
						";
						while( list($fid,$ftext) = mysql_fetch_array($fres) ){
							//combase();
							list($m,$v,$a) = $database -> getArrayOfQuery("
								SELECT access_m,access_v,access_a
								FROM ".DB_PREFIX."users_privilegies
								WHERE ID_USER=".$id." and ID_RUBRIC=$d and database_id=$db_id and ID_FEATURE=$fid
							");
							print "
								<tr>
									<td>$fid</td>
									<td>$ftext</td>
									<td align='center'><input type='checkbox' name='feat[$fid][0]' ".(($m)?"checked":"")."/></td>
									<td align='center'><input type='checkbox' name='feat[$fid][1]' ".(($v)?"checked":"")."/></td>
									<td align='center'><input type='checkbox' name='feat[$fid][2]' ".(($a)?"checked":"")."/></td>
								</tr>
							";
							curbase();
						}

					}
				}
				print "</table><div align=center><input type=submit value='Сохранить'></div></form>";
			}
		 }else print 'доступ запрещен';
		break;
		//удаление всех отмеченных пользователей
		case 'delete_all':
			//combase();
			foreach($_POST['cb_user_id'] AS $user_id_val){
				$database -> query("UPDATE ".DB_PREFIX."users SET user_visible=0,user_deleted=1 WHERE ID_USER=$user_id_val");
			}
			teRedirect(teGetUrlQuery());
		break;
		//отключение всех отмеченных пользователей
		case 'disable_all':
			//combase();
			//combase();
			foreach($_POST['cb_user_id'] AS $user_id_val){
				$database -> query("UPDATE ".DB_PREFIX."users SET user_visible=0 WHERE ID_USER=$user_id_val");
			}
			teRedirect(teGetUrlQuery());
		break;
		//включение всех отмеченных пользователей
		case 'turn_on_all':
			//combase();
			foreach($_POST['cb_user_id'] AS $user_id_val){
				$database -> query("UPDATE ".DB_PREFIX."users SET user_visible=1 WHERE ID_USER=$user_id_val");
			}
			teRedirect(teGetUrlQuery());
		break;
	}}


	if(!isset($_GET['action'])){

		// addHelp("users_manage.html");
		addSubMenuAdd();
		if($user_unis && DB_ID!=5)addSubMenuAdd(teGetUrlQuery("action=add2"),' ДОСТУП');
		global $skinpath;

		// селект для выбора, кого показывать (для суперадмина)
		if($_USER['login']=="root"){
			print "<select style='float:right' onChange='location.href=\"".teGetUrlQuery("view=")."\"+this.value'>";
			foreach($arrview1 AS $i){
				print "<option".(($i==$view)?" selected":"")." value='$arrview2[$i]'>$arrview3[$i]</option>"; //onclick='location.href=\"".teGetUrlQuery("view=$arrview2[$i]")."\"'")."
			}
			print "</select>";
		}
		setTitle($arrview3[$view]." системы «Древо»");

		print "<form method='post' name='user_list' id='user_list'>";
		//combase();
		$OList = new teList("
			SELECT ".DB_PREFIX."users.*, ".DB_PREFIX."users_privilegies.access_type
			FROM ".DB_PREFIX."users NATURAL JOIN ".DB_PREFIX."users_privilegies
			WHERE user_deleted=0 ".(($_USER['login']!="root"||$view==1)?"and database_id=".DB_ID."":"")."
			and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and access_type>0
			and user_visible=1 GROUP BY ID_USER
		",40);

		$OList->addToHead(' ',"","user_action");
		$OList->addToHead('Логин',"","user_login");
		$num_act=4;
		if($_USER['id']==0)$num_act=6;
		$OList->addToHead('Действия','colspan="'.$num_act.'" width="1%"');
		$OList->addToHead('Фамилия',"","user_sname");
		$OList->addToHead('Имя',"","user_name");
		$OList->addToHead('Отчество',"","user_pname");
		$OList->addToHead('Телефон',"","user_telephone");
		$OList->addToHead('e-mail',"","user_email");
		$OList->addToHead('ICQ',"","user_icq");
		$OList->addToHead('Список баз',"","base_list");
		if($view==1) $OList->addToHead('Тип',"","access_type");
 		list($access_create) = $database -> getArrayOfQuery("SELECT access_type FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=".$_USER['id']." and database_id=".DB_ID." and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0");

		$OList->query();
		while($OList->row()){
			if( $access_create>$OList->getValue("access_type") || $_USER['id']==0 || $OList->getValue("ID_USER")==$_USER['id'] ){
				$acc = true;
			} else {
				$acc = false;
			}
			//if( $acc ){
				$ab = "<a href='".teGetUrlQuery("action=view", "id={ID_USER}")."'>";
				$aa = "</a>";
			//} else {
			//	$aa = $ab = "";
			//}

			// вывод списка баз, к которым пользователь имеет доступ
			$list_user_bases = "";
			$id_user_b = $OList->getValue("ID_USER");
			$count_base = 0;$i_base = 0;
			//подсчет числа баз
			foreach($hosts as $db_id => $cont )
				if(empty($cont['cms']))
					$count_base++;

			$res_user_bases = $database->query("SELECT DISTINCT database_id FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=$id_user_b and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and (access_type=1 or access_type=2 or access_type=3)");
			while (list($database_id) = mysql_fetch_row($res_user_bases)) {
				$list_user_bases .= "<div>".@$hosts[$database_id]['name']."</div>";
				$i_base++;
			}
			if($count_base == $i_base) $list_user_bases = "Все базы";
			else $list_user_bases = "<a href='". teGetUrlQuery("pg=users_show_bases","user_id=$id_user_b")."' onclick='return ws(this)'>Просмотр</a>";


			$OList->addUserField("<input type='checkbox' name='cb_user_id[]' value='$id_user_b' />");

			$OList->addUserField("<a class='ws' href='?pg=ws&t=users&r={$id_user_b}' onclick='return ws(this);' >{user_login}<i>i</i></a>");

			if( $acc ){
				if($_USER['id']==0)
				{
					$OList->addUserField("<a href='".teGetUrlQuery("action=ip","id={ID_USER}")."'>IP</a>");
					$OList->addUserField("<a href='".teGetUrlQuery("action=history","id={ID_USER}")."'>История</a>");
				}
				if( $access_create>$OList->getValue("access_type") || $_USER['id']==0){
					$OList->addUserField("<a href='".teGetUrlQuery("action=access","id={ID_USER}")."'>Доступ</a>");
				} else {
					$OList->addUserField("");
				}
				$OList->addUserField(buttonEdit(teGetUrlQuery("action=edit","id={ID_USER}")));
				if($OList->getValue('user_visible')==1){
					$OList->addUserField(buttonDisable(teGetUrlQuery("action=disable","id={ID_USER}")));
				} else {
					$OList->addUserField(buttonEnable(teGetUrlQuery("action=enable","id={ID_USER}")));
				}
				$OList->addUserField(buttonDelete("javascript: if(confirm(\"Удалить пользователя %user_login% ?\")) location.href =\"".teGetUrlQuery("type=$user_type","action=delete","id={ID_USER}")."\"' title='удалить  без возможности восстановления"));
			} else {
				$OList->addUserField("-","align='center' colspan='$num_act'");
			}

			$OList->addUserField($ab."{user_sname}".$aa);
			$OList->addUserField($ab."{user_name}".$aa);
			$OList->addUserField($ab."{user_pname}".$aa);

			$dob = $OList->getValue("user_telephone");
			//$dob = substr($dob,8,2).".".substr($dob,5,2).".".substr($dob,0,4);
			$OList->addUserField($ab.$dob.$aa);

			$OList->addUserField("<a href='mailto:{user_email}'>{user_email}</a>");
			$OList->addUserField($ab."{user_icq}".$aa);
			$OList->addUserField($list_user_bases,"");
			if($view==1) $OList->addUserField($ab.@$arrtypeaccess[$OList->getValue("access_type")].$aa);
		}

		$OList->addParamTable('');
		echo($OList->getHTML());
		unset($OList);

		print "<p></p><div align='left'>C отмеченными:	<input name='del_all' type='hidden' value='1'><input type='submit' value='Отключить' action='".teGetUrlQuery("action=disable_all")."'> <input type='submit' value='Удалить' action='".teGetUrlQuery("action=delete_all")."'>	</div>";
		print "</form>";
  	  if($_USER['id']==0)
      {
		/*Добавлено 30112009 для отдельного отображения отключенных пользователей*/
		print "<h3 align='center'>Отключенные пользователи</h3>";
		print "<form method='post' name='disable_user_list' id='disable_user_list'>";

		$OList = new teList("
			SELECT ".DB_PREFIX."users.*, ".DB_PREFIX."users_privilegies.access_type
			FROM ".DB_PREFIX."users NATURAL JOIN ".DB_PREFIX."users_privilegies
			WHERE user_deleted=0 ".(($_USER['login']!="root"||$view==1)?"and database_id=".DB_ID."":"")."
			and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and access_type>0
			and user_visible=0 GROUP BY ID_USER
		",40);
		$OList->addToHead(' ',"","user_action");
		$OList->addToHead('Логин',"","user_login");
		$num_act=4;
		if($_USER['id']==0)$num_act=6;
		$OList->addToHead('Действия','colspan="'.$num_act.'" width="1%"');
		$OList->addToHead('Фамилия',"","user_sname");
		$OList->addToHead('Имя',"","user_name");
		$OList->addToHead('Отчество',"","user_pname");
		$OList->addToHead('Телефон',"","user_telephone");
		$OList->addToHead('e-mail',"","user_email");
		$OList->addToHead('ICQ',"","user_icq");
		$OList->addToHead('Список баз',"","base_list");
		if($view==1) $OList->addToHead('Тип',"","access_type");

		$OList->query();
		while($OList->row()){
			if( $access_create>$OList->getValue("access_type") || $_USER['id']==0){
				$acc = true;
			} else {
				$acc = false;
			}
			//if( $acc ){
				$ab = "<a href='".teGetUrlQuery("action=view", "id={ID_USER}")."'>";
				$aa = "</a>";
			//} else {
			//	$aa = $ab = "";
			//}

			// вывод списка баз, к которым пользователь имеет доступ
			$list_user_bases = "";
			$id_user_b = $OList->getValue("ID_USER");
			$count_base = 0;$i_base = 0;
			//подсчет числа баз
			foreach($hosts as $db_id => $cont )
				if(empty($cont['cms']))
					$count_base++;

			$res_user_bases = $database->query("SELECT DISTINCT database_id FROM ".DB_PREFIX."users_privilegies WHERE ID_USER=$id_user_b and ID_RUBRIC_TYPE=0 and ID_RUBRIC=0 and (access_type=1 or access_type=2 or access_type=3)");
			while (list($database_id) = mysql_fetch_row($res_user_bases)) {
				$list_user_bases .= "<div>".@$hosts[$database_id]['name']."</div>";
				$i_base++;
			}
			if($count_base == $i_base) $list_user_bases = "Все базы";
			else $list_user_bases = "<a href='". teGetUrlQuery("pg=users_show_bases","user_id=$id_user_b")."' onclick='return ws(this)'>Просмотр</a>";

			if(list($m) = $database->getArrayOfQuery("SELECT NOW()-ua_dt_end FROM ".DB_PREFIX."users_activity WHERE ua_dt_end>(NOW()-INTERVAL 5 MINUTE) and ID_USER=".$OList->getValue("ID_USER"))){ $status = "online"; $statustitle = "В сети"; } else
			if(list($m) = $database->getArrayOfQuery("SELECT NOW()-ua_dt_end FROM ".DB_PREFIX."users_activity WHERE ua_dt_end>(NOW()-INTERVAL 8 MINUTE) and ID_USER=".$OList->getValue("ID_USER"))){ $status = "away"; $statustitle = "Отошёл"; } else
			{ $status = "offline"; $statustitle = "Не в сети"; }

			$OList->addUserField("<input type='checkbox' name='cb_user_id[]' value='$id_user_b' />");

			$OList->addUserField("<img src='".$skinpath."images/b_".$status.".gif' title='$statustitle' alt='$statustitle - '/> ".$ab."{user_login}".$aa);

			if( $acc ){
				if($_USER['id']==0)
				{
					$OList->addUserField("<a href='".teGetUrlQuery("action=ip","id={ID_USER}")."'>IP</a>");
					$OList->addUserField("<a href='".teGetUrlQuery("action=history","id={ID_USER}")."'>История</a>");
				}
				if( $access_create>$OList->getValue("access_type") || $_USER['id']==0){
					$OList->addUserField("<a href='".teGetUrlQuery("action=access","id={ID_USER}")."'>Доступ</a>");
				} else {
					$OList->addUserField("");
				}
				$OList->addUserField(buttonEdit(teGetUrlQuery("action=edit","id={ID_USER}")));
				if($OList->getValue('user_visible')==1){
					$OList->addUserField(buttonDisable(teGetUrlQuery("action=disable","id={ID_USER}")));
				} else {
					$OList->addUserField(buttonEnable(teGetUrlQuery("action=enable","id={ID_USER}")));
				}
				$OList->addUserField(buttonDelete("javascript: if(confirm(\"Удалить пользователя %user_login% ?\")) location.href =\"".teGetUrlQuery("type=$user_type","action=delete","id={ID_USER}")."\"' title='удалить  без возможности восстановления"));
			} else {
				$OList->addUserField("-","align='center' colspan='$num_act'");
			}
			$OList->addUserField($ab."{user_sname}".$aa);
			$OList->addUserField($ab."{user_name}".$aa);
			$OList->addUserField($ab."{user_pname}".$aa);

			$dob = $OList->getValue("user_telephone");
			//$dob = substr($dob,8,2).".".substr($dob,5,2).".".substr($dob,0,4);
			$OList->addUserField($ab.$dob.$aa);

			$OList->addUserField("<a href='mailto:{user_email}'>{user_email}</a>");
			$OList->addUserField($ab."{user_icq}".$aa);

			$OList->addUserField($list_user_bases,"");
			if($view==1) $OList->addUserField($ab.@$arrtypeaccess[$OList->getValue("access_type")].$aa);
		}

		$OList->addParamTable('');
		echo($OList->getHTML());
		unset($OList);

		print "<p></p><div align='left'>C отмеченными:	<input type='submit' value='Включить' action='".teGetUrlQuery("action=turn_on_all")."'>	<input type='submit' value='Удалить' action='".teGetUrlQuery("action=delete_all")."'></div>";
		/*Добавлено 30112009 для отдельного отображения отключенных пользователей*/

		print "</form>";
	  }
	}

// писал Галлямов
function per_time(){	$arr_m = array(1=>'Января','Февраля','Марта','Апреля','Мая','Июня','Июля','Августа','Сентября','Октября','Ноября','Декабря');
	@$day1 = (int)$_GET['day1'];if($day1==0)$day1=date("j");
	@$month1 = (int)$_GET['month1'];if($month1==0)$month1=date("n");
	@$year1 = (int)$_GET['year1'];if($year1==0)$year1=date("Y");
	@$day2 = (int)$_GET['day2'];if($day2==0)$day2=date("j");
	@$month2 = (int)$_GET['month2'];if($month2==0)$month2=date("n");
	@$year2 = (int)$_GET['year2'];if($year2==0)$year2=date("Y");
    $sel_day1 = '<select name="day1">';
    $sel_day2 = '<select name="day2">';
    for($i=1;$i<32;$i++){
    	$sel_day1.='<option value="'.$i.'" '.($day1==$i ? 'selected="selected"' : '').'>'.$i.'</option>';
    	$sel_day2.='<option value="'.$i.'" '.($day2==$i ? 'selected="selected"' : '').'>'.$i.'</option>';
    }
    $sel_day1 .= '</select>';
    $sel_day2 .= '</select>';
    $sel_month1 = '<select name="month1">';
    $sel_month2 = '<select name="month2">';
    for($i=1;$i<13;$i++){
    	$sel_month1.='<option value="'.$i.'" '.($month1==$i ? 'selected="selected"' : '').'>'.$arr_m[$i].'</option>';
    	$sel_month2.='<option value="'.$i.'" '.($month2==$i ? 'selected="selected"' : '').'>'.$arr_m[$i].'</option>';
    }
    $sel_month1 .= '</select>';
    $sel_month2 .= '</select>';
    $sel_year1 = '<select name="year1">
		    <option value="'.($year1-1).'">'.($year1-1).'</option>
		    <option value="'.$year1.'" selected="selected">'.$year1.'</option>
		    <option value="'.($year1+1).'">'.($year1+1).'</option>
	    </select>
    ';
    $sel_year2 = '<select name="year2">
		    <option value="'.($year2-1).'">'.($year2-1).'</option>
		    <option value="'.$year2.'" selected="selected">'.$year2.'</option>
		    <option value="'.($year2+1).'">'.($year2+1).'</option>
	    </select>
    ';
    print 'с '.$sel_day1.$sel_month1.$sel_year1.' по '.$sel_day2.$sel_month2.$sel_year2;
	$bdate = mktime(0,0,0,$month1,$day1,$year1);
	$edate = mktime(23,59,59,$month2,$day2,$year2);
	return array($bdate,$edate,$year1.'-'.$month1.'-'.$day1.' 00:00:00',$year2.'-'.$month2.'-'.$day2.' 23:59:59','&year1='.$year1.'&month1='.$month1.'&day1='.$day1.'&year2='.$year2.'&month2='.$month2.'&day2='.$day2);

}
?>