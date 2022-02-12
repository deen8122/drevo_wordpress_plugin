<?if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  Фото записи
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/
$good_id = (int)$_GET['good_id'];
addGet("action",$_GET['action']);
addGet("good_id",$good_id);

if(!empty($_GET['photo_action'])){
	print "<div align=center>";
	switch($_GET['photo_action']){

		case 'add': // добавление
			if(!@$acc['e'])die('Попытка взлома!');
			print "<h2>Добавление фотографии товара</h2>";
			$frm = new teForm("form1","post");
			$frm->addf_file("goodphoto_file", "Укажите путь к фотографии");
			$frm->addf_text("goodphoto_desc", "Поле TITLE");
			$frm->addf_text("goodphoto_alt", "Поле ALT");
			$frm->setf_require("goodphoto_file","goodphoto_alt" );
			if(!$frm->send()){
				if($_FILES['goodphoto_file']['error']==UPLOAD_ERR_INI_SIZE || $_FILES['goodphoto_file']['error']==UPLOAD_ERR_FORM_SIZE){
					print "<div class='error'>Фото занимает больше 2 мегабайт. <a href='javascript:history.back()'>Загрузить заново</a></div>";
				} else {

					$goodphoto_desc = $frm->get_value('goodphoto_desc');
					$goodphoto_alt = $frm->get_value('goodphoto_alt');
					$goodphoto_file = $frm->move_file('goodphoto_file','good_photo');
					teInclude("images");
					teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"image_",teGetConf('photo_mmaxw'),teGetConf('photo_mmaxh'));
					teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"trumb_",teGetConf('photo_tmaxw'),teGetConf('photo_tmaxh'));
					new_wm_image(DATA_FLD.'good_photo/'.$goodphoto_file);
					new_wm_image(DATA_FLD.'good_photo/'."image_".$goodphoto_file);
					// определение позиции загруженной фото
					$max = $database->getArrayOfQuery("SELECT max(goodphoto_pos) FROM ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0 and ID_GOOD=".$good_id);
					$max = $max[0]+1;
					$database -> query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD,goodphoto_desc,goodphoto_alt,goodphoto_file,goodphoto_pos) VALUES ($good_id, '$goodphoto_desc', '$goodphoto_alt','$goodphoto_file',$max)");
					del_cache(0,$good_id);
					teRedirect(teGetUrlQuery());
				}
			}
		break;

		case 'edit': // изменение
			if(!@$acc['e'])die('Попытка взлома!');
			print "<h2>Изменение фотографии товара</h2>";
			$line = $database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=$id",MYSQL_ASSOC);
			print "<img src='".URLDATA_FLD."good_photo/trumb_$line[goodphoto_file]' />";
			$frm = new teForm("form1","post");
			$frm->addf_text("goodphoto_desc", "Поле TITLE", $line['goodphoto_desc']);
			$frm->addf_text("goodphoto_alt", "Поле ALT", $line['goodphoto_alt']);
			$frm->setf_require("goodphoto_alt" );
			if(!$frm->send()){
				$goodphoto_desc = $frm->get_value('goodphoto_desc');
				$goodphoto_alt = $frm->get_value('goodphoto_alt');
				$database -> query("UPDATE ".DB_PREFIX."goods_photos SET goodphoto_desc='$goodphoto_desc',goodphoto_alt='$goodphoto_alt' WHERE ID_GOOD_PHOTO=$id");
				del_cache(0,$good_id);
				teRedirect(teGetUrlQuery());
			}
		break;

		case 'delete': // удаление
			if(!@$acc['e'])die('Попытка взлома!');
			list($file) = $database->getArrayOfQuery("SELECT goodphoto_file FROM ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0 and ID_GOOD_PHOTO=".$id);

			//Сохранение удаленныех фотографий {{
			$filedir = DATA_FLD."good_photo/".$file;
			$def_dir ='/var/www/old_photos/';
			$filename = pathinfo($filedir);
			$fn = substr($file,0,-strlen($filename['extension'])-1);
            $i = 0;
			while(file_exists($def_dir.$fn.(empty($i)?"":"_".$i).".".$filename['extension'])){
				$i++;
			}
			$filename = $fn.(empty($i)?"":"_".$i).".".$filename['extension'];
			copy($filedir,$def_dir.$filename);
			$database -> query("UPDATE ".DB_PREFIX."goods_photos SET goodphoto_file='$filename' WHERE ID_GOOD_PHOTO=$id",false);
            //}}
			unlink(DATA_FLD."good_photo/image_".$file);
			unlink(DATA_FLD."good_photo/trumb_".$file);
			unlink($filedir);
			$database -> query("UPDATE ".DB_PREFIX."goods_photos SET goodphoto_deleted=1 WHERE ID_GOOD_PHOTO=$id",true,3);
			del_cache(0,$good_id);
			teRedirect(teGetUrlQuery());
	}
	print "</div>";
} else {

	// cохранение позиций
	if( !empty( $_POST['savepos'] ) ){
		@$grad = $_POST['grad'];
		foreach($_POST['pos'] AS $id_pos => $pos){
			$database->query("UPDATE ".DB_PREFIX."goods_photos SET goodphoto_pos=".$pos."".(DB_ID==49?', grad='.$grad[$id_pos]:'')." WHERE ID_GOOD_PHOTO=".$id_pos);
		}
	}

	print_link_up(teGetUrlQuery("action="),"назад, к списку товаров");
	setTitle("<h2>Управление фотографиями товара </h2>");

	global $page_arr;
	$page_arr[G_PAGE] = 'goods';
	if(@$acc['e'])print "<div class=add><a href='".teGetUrlQuery("photo_action=add")."' class='btn btn-success'>Добавить фото</a></div>";

	$sql = "SELECT * FROM ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0 and ID_GOOD=".$good_id." ORDER BY goodphoto_pos";
	$OList = new teList($sql);
	if(@$acc['e'])
	{
		$OList->addToHead("№","width=1%");
		if(DB_ID==49)$OList->addToHead("Grad","width=1%");
	}
	$OList->addToHead("Фотография");
	$OList->addToHead("TITLE","");
	$OList->addToHead("ALT","");
	if(@$acc['e'])$OList->addToHead("Действия","colspan=2 width=1%");
	while($OList->row()){
		if(@$acc['e'])
		{
			$OList->addUserField("<input type=text size=2 name=pos[{ID_GOOD_PHOTO}] value={goodphoto_pos} onKeyUp='this.form.formsub.disabled=false' />");
			if(DB_ID==49)
				$OList->addUserField("<input type=text size=2 name=grad[{ID_GOOD_PHOTO}] value={grad} onKeyUp='this.form.formsub.disabled=false' />");
		}
		$OList->addUserField("<a target=_blank href='".URLDATA_FLD."good_photo/{goodphoto_file}'><img src='".URLDATA_FLD."good_photo/trumb_{goodphoto_file}' /></a>");
		$OList->addUserField("{goodphoto_desc}");
		$OList->addUserField("{goodphoto_alt}");

		/// действия
		if(@$acc['e'])$OList->addUserField("<a href='".teGetUrlQuery("photo_action=edit","id={ID_GOOD_PHOTO}")."'>ред.</a>");
		if(@$acc['e'])$OList->addUserField("<a class=del href='javascript: if(confirm(\"Удалить фотографию «{goodphoto_desc}» без возможности восстановления?\")) location.href=\"".teGetUrlQuery("photo_action=delete","id={ID_GOOD_PHOTO}")."\"'>удал.</a>");

	}
	$OList->addParamTable('');

	$res = $database->query($sql);
	if(mysql_num_rows($res)>0){
		if(@$acc['e'])print "<form method=post><input type=hidden name=savepos value=1>";
		echo($OList->getHTML());
		if(@$acc['e'])print "<input id=formsub disabled type=submit value='Сохранить номера позиций'></form>";
		if(@$acc['e'])print "<div class=add><a href='".teGetUrlQuery("photo_action=add")."'>Добавить фото</a></div>";
	}

	unset($OList);


}
?>