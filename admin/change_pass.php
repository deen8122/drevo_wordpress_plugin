<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
	/**********
	*  ООО "Универсал-Сервис"
	*
	*  Разработчик:  Teлeнкoв Д.С.
	*  e-mail: tdssc@mail.ru
	*  ICQ: 420220502
	*  Тел.: +7 909 3481503
	**********/
	
	print_link_main();
	echo("<h2>Изменить пароль</h2>");
	
	if(!empty($_GET['err'])) print "<div class='error'>Старый пароль не верен!</div>";
	
	// генерируем форму
	print "<div align=center>";
	$frm = new teForm("form1","post");
	$frm->addf_password("old_password", "Текущий пароль");
	$frm->addf_password("pass", "Новый пароль", true);
	
	$frm->setSubmitCaption("Сохранить");
	$frm->setf_require("old_password","pass");
	if(!$frm->send()){
		// обрабатываем форму
		
		$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."users WHERE ID_USER=".$_USER['id'],MYSQL_ASSOC);
		if( $line['user_passwd']==$_USER['user_passwd'] && $_USER['user_passwd']==md5($frm->get_value('old_password')) ){
			$database -> query("UPDATE ".DB_PREFIX."users SET user_passwd='".md5($frm->get_value('pass'))."' WHERE ID_USER=".$_USER['id'],MYSQL_ASSOC);
			print "<div class='ok'>Пароль успешно сменен! Нажмите <a href='".teGetUrlQuery("user_exit")."'>сюда</a> для входа в систему с новым паролем.</div>";
		} else {
			teRedirect(teGetUrlQuery("err=1"));
		}
		
	}	
	print "</div>";
?>