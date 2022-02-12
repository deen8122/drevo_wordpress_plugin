<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
// берем из БД доступ к рубрике
$acc = checkAccess((int)@$_GET['rubric_id']);
// если доступа нет - умираем
//if(!@$acc['v']&&!@$acc['a']&&!@$acc['e']&&!@$acc['d']){die('Попытка взлома');}
global $page_arr;
$page_arr[G_PAGE] = 'goods';
if(isset($_GET['ok'])) print '<div class="ok">Операция выполнена успешно</div>';


// эта функция очищает базу данных от ненужных (пустых) записей
function optimizeGood($good_id){
	global $database;
	if(empty($good_id)) return false;
	$good_id = (int)$good_id;
	$res = $database->query("SELECT * FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features WHERE ID_GOOD=".$good_id);
	if( mysql_num_rows($res)>0 ){
		while($line = mysql_fetch_array($res,MYSQL_ASSOC)){
			if($line['feature_type']==5){
				if(optimizeGood($line['goodfeature_value'])){
					$database->query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD='$good_id' and goodfeature_value='".$line['goodfeature_value']."'",false);
				}
			}
		}
	} else {
		$database->query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=$good_id",false);
		$database->query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=$good_id",false);
		return true;
	}
	return false;
}

// инициализация переменных
$showid = (int)@$_GET['showid'];
$rubric_id = (int)@$_GET['rubric_id'];
$id = (int)@$_GET['id'];
$type = (int)@$_GET['type'];
$idshow = @$_GET['idshow'];if(empty($idshow)) $idshow = "id";
$res = $database->query("SELECT * FROM ".DB_PREFIX."rubric_types WHERE rubrictype_deleted=0 and ID_RUBRIC_TYPE=".$type);
$rtype=mysql_fetch_array($res,MYSQL_ASSOC);
if($rtype['ID_RUBRIC_TYPE']!=$type) teRedirect(teGetUrlQuery("pg=rubric","idshow=id"));
$typeview = (empty($_GET['typeview']))?"tree":$_GET['typeview'];

// добавляем в будущие ссылки параметры
addGet("typeview",$typeview);
addGet("type",$type);
addGet("showid",$showid);
addGet("rubric_id",$rubric_id);
addGet("idshow",$idshow);

// если доступ только к добавлению  -  редирект на добавление
if(!@$acc['v']&& @$acc['a']&&!@$acc['e']&&!@$acc['d']&&!(@$_GET['action']=='add'||(@$_GET['action']=='edit'&&@$_GET['ses_add']==1))){teRedirect(teGetUrlQuery("id=0","action=add","rubric[".(int)@$_GET['rubric_id']."]=on"));}

// если это iframe, то заканчиваем (по вузову функции)
// это нужно для вложенных форм
function iframe(){
	$s.= htmlspecialchars(ob_get_contents());
	ob_end_clean();
	die($s);
}

global $skin;
@$del_view = (bool)$_GET['del_view'];
// SQL
$goodssql = "
	SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*
	FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods
	WHERE ".($del_view?'':'good_deleted=0 and ')."rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
	ORDER BY ".DB_PREFIX."rubric_goods.rubricgood_pos, ".DB_PREFIX."rubric_goods.ID_GOOD DESC
";


// функция используется для вложенных форм
// берёт данные о нужной рубрике из GET, и суёт в POST
function get_rubric_from_get(){
	if( !isset($_POST['rubric']) && isset($_GET['rubric']) ){
		$_POST['rubric'] = $_GET['rubric'];
	}

	if(isset($_POST['rubric']) && !is_array($_POST['rubric'])){
		$rubric_id = $_POST['rubric'];
		$_POST['rubric'] = array($_POST['rubric']=>'on');
	}

	if( !empty($_POST['rubric_']) ){
		if(is_array($_POST['rubric_'])) $_POST['rubric_'] = $_POST['rubric_'][0];
		$_POST['rubric'] = array($_POST['rubric_']=>"on");
	}
}


// для вложенных форм
if( !empty($_GET['iframe']) && empty($_GET['method']) ){

	$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&";

	list($feat) = $database->getArrayOfQuery("SELECT feature_rubric FROM ".DB_PREFIX."features WHERE ID_FEATURE=".$_GET['linkfeature']);
	if(empty($feat)) $feat = 3;

	print "<br/><br/><br/><br/><br/><br/>";
	if($_GET['action']=='add'){
		if($feat>2){
			print "<div style='text-align:center;font-size:1.3em;padding:5px;'><a href='".$url."action=edit&method=select'>выбрать <b>существующую</b> запись</a></div><br/><br/>";
			print "<div style='text-align:center;font-size:1.3em;padding:5px;'><a href='".$url."method=add'>добавить <b>новую</b> запись</a></div>";
		} else {
			if($feat>1){
				teRedirect($url."action=edit&method=select");
			} else {
				teRedirect($url."method=add");
			}
		}
	} else {
		if($feat>2){
			print "<div style='text-align:center;font-size:1.3em;padding:5px;'><a href='".$url."method=select'>выбрать <b>другую</b> запись</a></div><br/><br/>";
			print "<div style='text-align:center;font-size:1.3em;padding:5px;'><a href='".$url."method=add'><b>изменить</b> данные</a></div>";
		} else {
			if($feat>1){
				teRedirect($url."method=select");
			} else {
				teRedirect($url."method=add");
			}
		}
	}
	print "<br/>";

} elseif(!empty($_GET['action'])){
// если юзер просит какие то действия

	// если просмотр и редактирование
	if($_GET['action']=='view'||$_GET['action']=='edit'){
		if($type==teGetConf("rtpl_type")){
			$url = "";
			if($hosts[DB_ID]['siteversion']==4){
				list($url) = $database -> getArrayOfQuery("SELECT rubric_textid FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
			}
			if($hosts[DB_ID]['siteversion']==3){
				list($url) = $database -> getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rubric_id);
				$url = filename2(translit2($url));
			}
			if($hosts[DB_ID]['siteversion']==2){
				$url = $rubric_id;
			}


			// берем 1 градацию
			list($grad) = $database->getArrayOfQuery("
				SELECT ID_FEATURE
				FROM ".DB_PREFIX."rubric_features
				WHERE rubricfeature_graduation=1 and ID_RUBRIC = ".$rubric_id."
			");
			if(!empty($grad)){
				$vl = getFeatureValue($id,$grad);
				$grad = "&grad[$grad]=".$vl."&graduation[$grad]=".$vl."&group_id=".$vl;
			}
			if(!empty($url)){
				if($hosts[DB_ID]['siteversion']==4||$hosts[DB_ID]['siteversion']==3){
					//addSubMenu($hosts[DB_ID]['url']."?p=".$url.$grad."&id=".$id."' target='blank","<img src='{$skinpath}images/b_www_big.ico' alt='на сайт' title='на сайт'/>","submenustd");
				} else {
					//addSubMenu($hosts[DB_ID]['url']."?page=".$rubric_id."&id=".$id."' target='blank","<img src='{$skinpath}images/b_www_big.ico' alt='на сайт' title='на сайт'/>","submenustd");
				}
			}
		}
	}

	// выбираем в зависимости от действия
	switch($_GET['action']){
	// добавление
	case 'add':
		require_once "goods_add.php";
	// изменение
	case 'edit':
		require_once "goods_edit.php";
	break;

	// просмотр
	case 'view':
		require_once "goods_view.php";
	break;

	// добавление из екселя
	case 'excel':
		require_once "goods_excel.php";
	break;
	case 'upd_excel':
		require_once "update_excel.php";
	break;

	// выбор рубрики для дальнейших действий
	case 'rubric':
		require_once "goods_rubric.php";
	break;

	// копирование характеристик
	case 'feat_copy':
		require_once "goods_feat_copy.php";
	break;

	// фото записи
	case 'photos':
		require_once "goods_photos.php";
	break;

	// заполнение опр.характеристик
	case 'features_data':
		require_once "goods_features_data.php";
	break;

	// настройки листинга
	case 'list_settings':
		require_once "goods_list_settings.php";
	break;
	
	// ручная сортировка записей
	// новая
	case 'pos':
	{
		addSubMenu( teGetUrlQuery(), '<< Отмена' );
		
		teAddCSSCode( '
			.list th{cursor:pointer;}
			.list th:hover{background-color:#ada;}
		' );
	
		if ( isset($_POST['rubric']) )
		{	//print_r($_POST);
			//	exit;
			foreach( $_POST['rubric'] AS $good_id => $good_pos )
			{
				
				del_cache($rubric_id);
				$res = $database->query("UPDATE ".DB_PREFIX."rubric_goods SET rubricgood_pos=".intval($good_pos)." WHERE ID_RUBRIC=$rubric_id and ID_GOOD=".$good_id);
			}
			teRedirect( teGetUrlQuery( 'ok' ) );
		}
		
		$sort_col = getInput( 'sort_col' );
		$sortdir_f = getInput( 'sort_dir' );
		if ( $sortdir_f )
			$sort_dir = 'DESC';
		else
			$sort_dir = 'ASC';
		$isID = getInput( 'ID' );
		
		$feat_type = $sort_col ? getFeatureType( $sort_col ) : 0;
		switch ( $feat_type )
		{
			case 7:
				$goodssql = "
					SELECT ".DB_PREFIX."goods.ID_GOOD,
						".DB_PREFIX."rubric_goods.rubricgood_pos, 
						SUBSTRING(cprice_texts.text_text,1,60) AS ordbyY 
					FROM ".DB_PREFIX."goods 
						NATURAL JOIN ".DB_PREFIX."rubric_goods 
						NATURAL JOIN ".DB_PREFIX."texts 
						LEFT JOIN cprice_goods_features AS ord
					ON (".DB_PREFIX."goods.ID_GOOD=ord.ID_GOOD && ord.ID_FEATURE=$sort_col)
					WHERE good_deleted=0 && rubricgood_deleted=0 && ID_RUBRIC=$rubric_id && ord.goodfeature_value=".DB_PREFIX."texts.ID_TEXT
					ORDER BY ordbyY $sort_dir, ".DB_PREFIX."rubric_goods.rubricgood_pos";
			break;
			case 1:
				$goodssql = "
					SELECT ".DB_PREFIX."goods.ID_GOOD, 
						".DB_PREFIX."rubric_goods.rubricgood_pos, 
						ord.goodfeature_value AS ordby, 
						ord.goodfeature_float AS ordby0
					FROM ".DB_PREFIX."goods 
						NATURAL JOIN ".DB_PREFIX."rubric_goods 
						LEFT JOIN cprice_goods_features AS ord 
						ON (cprice_goods.ID_GOOD=ord.ID_GOOD && ord.ID_FEATURE=$sort_col)
					WHERE good_deleted=0 && rubricgood_deleted=0 && ID_RUBRIC=".$rubric_id."
					ORDER BY ordby0 $sort_dir, ordby $sort_dir, ".DB_PREFIX."rubric_goods.rubricgood_pos";
			break;
			case 4:
				$goodssql = "
					SELECT ".DB_PREFIX."goods.ID_GOOD, 
						".DB_PREFIX."rubric_goods.rubricgood_pos, 
						fd.featuredirectory_text AS ordby
					FROM
						".DB_PREFIX."goods NATURAL JOIN
						".DB_PREFIX."rubric_goods
						LEFT JOIN cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD && ord.ID_FEATURE=$sort_col)
						LEFT JOIN cprice_feature_directory AS fd ON (ord.goodfeature_value=fd.ID_FEATURE_DIRECTORY && fd.ID_FEATURE=$sort_col)
					WHERE good_deleted=0 && rubricgood_deleted=0 && ID_RUBRIC=".$rubric_id."
					ORDER BY ordby $sort_dir, ".DB_PREFIX."rubric_goods.rubricgood_pos";
			break;
			default:
				$goodssql = '
					SELECT DISTINCT '.DB_PREFIX.'goods.ID_GOOD, 
						rubricgood_pos 
					FROM '.DB_PREFIX.'rubric_goods NATURAL JOIN '.DB_PREFIX.'goods NATURAL JOIN '.DB_PREFIX.'goods_features 
					WHERE good_deleted=0 && rubricgood_deleted=0 && ID_RUBRIC=' . $rubric_id . ( $sort_col ? ' && ID_FEATURE=' . $sort_col : '' ) . ' 
					ORDER BY '.( $sort_col ? 'goodfeature_value ' : ( isset( $isID ) ? DB_PREFIX.'goods.ID_GOOD ' : 'rubricgood_pos ' ) ). $sort_dir . ', ' . DB_PREFIX . 'goods.ID_GOOD ' .$sort_dir;
 				$goodssql = '
					SELECT DISTINCT '.DB_PREFIX.'goods.ID_GOOD, 
						rubricgood_pos 
					FROM '.DB_PREFIX.'rubric_goods NATURAL JOIN '.DB_PREFIX.'goods NATURAL JOIN '.DB_PREFIX.'goods_features 
					WHERE good_deleted=0 && rubricgood_deleted=0 && ID_RUBRIC=' . $rubric_id . ( $sort_col ? ' && ID_FEATURE=' . $sort_col : '' ) . ' 
					ORDER BY rubricgood_pos '; 
		}
		
		//echo $goodssql;
		$OList = new teList(	$goodssql, 999999 );
		
		$characters = $database->getColumnOfQuery( '
			select ID_FEATURE, feature_text 
			from '.DB_PREFIX.'rubric_goods natural join '.DB_PREFIX.'goods_features natural join '.DB_PREFIX.'features 
			where ID_RUBRIC='.$rubric_id, 1 );
		
		$OList->addToHead( 'ID', 'width="1px" onclick="location.href=\''.teGetUrlQuery( 'action=pos', 'ID', !$sortdir_f ? 'sort_dir=1' : '' ).'\'"' );
		$OList->addToHead( 'Текущяя позиц.', 'width="1px" onclick="location.href=\''.teGetUrlQuery( 'action=pos', !$sortdir_f ? 'sort_dir=1' : '' ).'\'"' );
		$OList->addToHead( 'Новая позиц.', 'width="1px"' );
		$i = 2;
		foreach ( $characters as $ID_FEATURE => $feature_text )
			$OList->addToHead( $feature_text, 'onclick="location.href=\''.teGetUrlQuery( 'action=pos', 'sort_col=' . $ID_FEATURE, !$sortdir_f ? 'sort_dir=1' : '' ).'\'"' );
		$OList->query();
		
		//echo '<pre>',print_r($OList),'</pre>';
		$i = 1;
		while( $OList->row() )
		{
			$ID_GOOD = $OList->getValue( 'ID_GOOD' );
			$rubricgood_pos = $OList->getValue( 'rubricgood_pos' );
			
			$OList->addUserField( $OList->getValue( 'ID_GOOD' ) );
			$OList->addUserField( $rubricgood_pos );
			$OList->addUserField( '<input type="text" size="3" name="rubric['.$ID_GOOD.']" value="'.$rubricgood_pos.'">' );
			
			//перебор характеристик
			foreach ( $characters as $ID_FEATURE => $feature_text )
				$OList->addUserField( strip_tags(strEx( getFeatData( $ID_FEATURE, $ID_GOOD ), 90 ) ) );
			$i++;
		}
		$OList->addParamTable('');
		echo '<form method="post">', $OList->getHTML(), '<input type="submit" value="Сохранить" style=""></form>';
		unset( $OList );
	}
	break;
	// ручная сортировка записей
	// старая
	case 'pos2':
	{
		if ( getInput( 'rubric' ) )
		{
			foreach( $_POST['rubric'] AS $good_id => $good_pos )
			{
				del_cache($rubric_id);
				$res = $database->query("UPDATE ".DB_PREFIX."rubric_goods SET rubricgood_pos=".inval($good_pos)." WHERE ID_RUBRIC=$rubric_id and ID_GOOD=".$good_id);
			}
			teRedirect(teGetUrlQuery("ok"));
		}
		else
		{
			addSubMenu( teGetUrlQuery(), 'Назад' );
			$res = $database->query( "
				SELECT ".DB_PREFIX."goods.ID_GOOD,rubricgood_pos
				FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods
				WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=$rubric_id
				ORDER BY rubricgood_pos ASC, ".DB_PREFIX."goods.ID_GOOD ASC" );
			echo "<form method=post>".
				"<table width=90%>";
			$i = 0;
			while ( list( $gid, $gpos ) = mysql_fetch_array( $res ) )
			{
				echo "<tr>".
					"<td width='1%'><input type='text' size='3' name='rubric[$gid]' value='$gpos' tabindex='".(++$i)."'></td>".
					"<td width='100%'>" . getIdToPrint("goods",$gid) . ": " . getFeatureText($gid,0,true)."</td>".
					"</tr>";
			}
			echo "<tr align=center><td colspan=2><input type='submit' value='сохранить порядок отображения'></td></tr>".
				"</table>".
				"</form>";
		}
	}
	break;
	// Перенос, копирование, удаление записей
	case 'copy':
	@$op = $_POST['op1'];
	if($op == 'add_photo' && !empty($_POST['rubric']))
	{
		addSubMenu(teGetUrlQuery(),'Вернуться');
		print '<h2>Добавление фотографий к группам записей</h2><div align="center">';
		$frm = new teForm("form1","post");
		$i = 0;
		$good_names = array();
		foreach( $_POST['rubric'] AS $id_good ){
			list($gname) = $database->getArrayOfQuery("
				SELECT goodfeature_value FROM cprice_goods_features
				WHERE ID_GOOD=".$id_good."
				ORDER BY ID_GOOD_FEATURE
				LIMIT 1
			");
			$good_names[$id_good] = $gname;
			$frm->addf_file("goodphoto_file".$id_good, "<img src='../engine/data/system_skin/images/photo.ico' style='height:1.2em' align='top' alt=''/> Фотографии для записи: <b>ИД:{$id_good}, ".$gname."</b>"
				. '<input type="hidden" name="rubric[]" value="'.$id_good.'" >',
				"", 10*1024*1024, DATA_FLD."good_photo/","'jpg','png','gif','bmp'");
			$frm->setFieldMultiple("goodphoto_file".$id_good);			
			if($i==0) $frm->addf_desc("goodphoto_file".$id_good,"Действие для всех фото:<br/><input type='checkbox' name='malph' id='malph' value='1' /><label for='malph'>не уменьшать</label>
				<br/><input type='checkbox' name='wtmrk' id='wtmrk' value='1'".(DB_ID==18 && $type!=8?'':' checked="checked"')." /><label for='wtmrk'>с водным знаком (если он есть)</label>"
				. '<input type="hidden" name="op1" value="add_photo" >');
			$i++;
		}
		if(!$frm->send()){
			teInclude("images");
			$tphw = teGetConf('photo_tmaxw'); $tphh = teGetConf('photo_tmaxh');
			$mphw = teGetConf('photo_mmaxw'); $mphh = teGetConf('photo_mmaxh');
			foreach( $_POST['rubric'] AS $id_good ){
				$goodphoto_files = $frm->move_file('goodphoto_file'.$id_good);
				$n_ph = 0;
				if($goodphoto_files){
					asort($goodphoto_files);
					foreach($goodphoto_files as $goodphoto_file)
					{
						teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"image_",$mphw,$mphh);
						teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"trumb_",$tphw,$tphh);
						$size_img = getimagesize(DATA_FLD."good_photo/".$goodphoto_file);
						if(($size_img[0]>1024 || $size_img[1]>1024 || filesize(DATA_FLD."good_photo/".$goodphoto_file)>400000) && (!isset($_POST['malph']))){teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"",1024,1024,NULL,80);}
						if(isset($_POST['wtmrk']))
						{
							new_wm_image(DATA_FLD.'good_photo/'."image_".$goodphoto_file);
							new_wm_image(DATA_FLD.'good_photo/'.$goodphoto_file);
						}
						$database -> query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD,goodphoto_alt,goodphoto_file,goodphoto_pos) VALUES ($id_good, '".mysql_escape_string($good_names[$id_good])."','$goodphoto_file',".++$n_ph.")");
					}
				}				
			}
			teRedirect(teGetUrlQuery("ok"));
		}
		echo '</div>';
		break;
	}
	if($op == 'svyazka' && !empty($_POST['rubric']) && !empty($_POST['rubs']))
	{
		del_cache($rubric_id,0,true);//удаление кэша
		$n = 0;
		foreach( $_POST['rubric'] AS $id_good ){
			$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=$id_good");
			foreach($_POST['rubs'] AS $rub ){
				$database -> query("INSERT INTO ".DB_PREFIX."rubric_goods (ID_RUBRIC,ID_GOOD) VALUES ($rub,$id_good)");
			}
			$n++;
		}
		curbase();
		teRedirect(teGetUrlQuery("ok"));
	}
	if($op == 'delete' && !empty($_POST['rubric']))
	{
		del_cache($rubric_id,0,true);//удаление кэша
		$n = 0;
		foreach( $_POST['rubric'] AS $id_good ){
			del_cache($rubric_id,$id_good,true);//удаление кэша
			$database->query("UPDATE cprice_goods set good_deleted=1 WHERE ID_GOOD=".$id_good,true,3);
			$n++;
		}
		teRedirect(teGetUrlQuery("ok","n=".$n));
	}
	if(($op == 'enable' || $op == 'disable')  && !empty($_POST['rubric']))
	{
		del_cache($rubric_id,0,true);//удаление кэша
		foreach( $_POST['rubric'] AS $id_good ){
			del_cache($rubric_id,$id_good,true);//удаление кэша
			$database->query("UPDATE cprice_goods set good_visible=".($op == 'enable'?1:0)." WHERE ID_GOOD=".$id_good);
		}
		teRedirect(teGetUrlQuery("ok"));
	}
	if(($op == 'malph' || $op == 'bolph') && !empty($_POST['rubric']))
	{
		teInclude("images");
		if($op == 'malph')
		{			
			$tmaxw = teGetConf('photo_tmaxw');
			$tmaxh = teGetConf('photo_tmaxh');
		}
		else
		{	
			$tmaxw = teGetConf('photo_mmaxw');
			$tmaxh = teGetConf('photo_mmaxh');
		}
		foreach( $_POST['rubric'] AS $id_good ){			
			$phres = $database->query("select goodphoto_file from ".DB_PREFIX."goods_photos where ID_GOOD=$id_good && goodphoto_deleted=0");
			while(list($goodphoto_file)=mysql_fetch_row($phres))
				teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,($op == 'malph'?"trumb_":"image_"),$tmaxw,$tmaxh);
		}
		teRedirect(teGetUrlQuery("ok"));
	}
	@$prj = (int)$_POST['prj'];
	if(!empty($_POST['rubric']) && !empty($_POST['rubs'])){
		if($op=='move')
		{	
			del_cache($rubric_id,0,true);//удаление кэша
		}
		$rtype = $_POST['rtype'];
		if($op=='copy_val' || $op=='move_val')
		{
			print "<h2>".($op=='copy_val'?'Копирование':'Перемещение')." со связкой характеристик из рубрики №".$rubric_id.": ".getRubricName($rubric_id,false,false,false)."</h2>";
			$resf = $database->query("select ID_FEATURE, feature_text, feature_type from cprice_features natural join cprice_rubric_features WHERE ID_RUBRIC={$rubric_id} && rubric_type=".$type);
			$feats = array();
			while($rowf = mysql_fetch_array($resf))
				$feats[$rowf[0]]=array($rowf[1],$rowf[2]);
			print "<form method='post' id='fform'>
				<input type='hidden' name='op1' value='".($op=='copy_val'?'copy':'move')."' />
				<input type='hidden' name='rtype' value='$rtype' />
				<input type='hidden' name='prj' value='$prj' />
				";
			foreach( $_POST['rubric'] AS $id_good ) print "<input type='hidden' name='rubric[]' value='$id_good' />";
			print "<table width='50%' style='margin-left:25%'>";
       		print "<tr><td colspan='2'><div style='text-align:center;padding:3px;'>Связанные характеристики не будут созданы, скопируются только значения</div></td></tr>";
			if($prj>0)otherbase($prj);
        	foreach( $_POST['rubs'] AS $id_rubric )
        	{
        		print "<tr><td colspan='2'><div style='text-align:center;background-color:#B7DBFF;padding:3px;'><b>Связка характеристик в рубрике №".$id_rubric.": ".getRubricName($id_rubric,false,false,false)."</b></div><input type='hidden' name='rubs[]' value='$id_rubric' /></td></tr>";				
				$resf = $database->query("select ID_FEATURE, feature_text, feature_type from cprice_features natural join cprice_rubric_features WHERE ID_RUBRIC={$id_rubric} && rubric_type=".$rtype);
				while($rowf = mysql_fetch_array($resf))
				{					
					print "<tr><td><b>$rowf[0]</b> $rowf[1]</td><td><select name='f{$id_rubric}[{$rowf[0]}]'><option value=''></option>";
					foreach($feats as $f_id => $feat)
					{						
						if($feat[1]==$rowf[2])
						{							
							print "<option value='{$f_id}'".($rowf[0]==$f_id?' selected':'').">{$f_id} {$feat[0]}</option>";						
						}					
					}
					print "</select></td></tr>";				
				}

        	}
			
			if($op=='copy_val')print "<tr><td colspan='2'><input type='checkbox' value='1' name='cp_val' id='cp_val' /> <label for='cp_val'>Копировать только связанные значения</label></td></tr>";
			print "<tr><td colspan='2'><input type='checkbox' value='1' name='cp_ph' id='cp_ph' checked /> <label for='cp_ph'>Копировать картинки и сео-параметры</label></td></tr>";
			print "<tr><td colspan='2'><div style='text-align:center'><input type='button' value='назад' onclick='javascript:history.go(-1);' /> <input type='submit' value=' Завершить  ' /></div></td></tr></table>";

			print "<input type='hidden' name='addf' value='1'></form>";
		}
		else
		{
		  $j=0;$rtype2=array();
		  $resf = $database->query("select ID_FEATURE, rubricfeature_graduation, rubricfeature_pos from cprice_rubric_features WHERE ID_RUBRIC={$rubric_id} && rubric_type=".$type);
		  $feats = array();/*характеристики исходной рубрики*/
		  while($rowf = mysql_fetch_array($resf))
			$feats[]=array($rowf[0],$rowf[1],$rowf[2]);
          $f_svyaz = array();/*связанные характеристики*/
		  foreach( $_POST['rubric'] AS $id_good ){
		    curbase();
			list($gurl) = $database -> getArrayOfQuery("SELECT good_url FROM  cprice_goods WHERE ID_GOOD=".$id_good);
			if(empty($gurl))$gurl=$id_good;
			$resg = $database->query("select ID_FEATURE, goodfeature_value, feature_type from cprice_goods_features natural join cprice_features natural join cprice_rubric_features WHERE ID_RUBRIC={$rubric_id} && ID_GOOD=".$id_good);
			$vals = array();/*значения копируемой/переносимой записи*/
			while($rowg = mysql_fetch_array($resg))
			{
				$txt='';
				if($rowg[2]==7)
				{
					if($rowg[1]>0) list($txt) = $database -> getArrayOfQuery("SELECT text_text FROM cprice_texts WHERE ID_TEXT=".intval($rowg[1]));
					else $txt = $rowg[1];
				}
				$vals[]=array($rowg[0],$rowg[1],$rowg[2],$txt);
			}
			$i=0;
			if($prj>0)otherbase($prj);
			foreach( $_POST['rubs'] AS $id_rubric ){
				del_cache($id_rubric);
            	if($j==0) list($rtype2[]) = $database -> getArrayOfQuery("SELECT rubric_type FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$id_rubric);
            	if($rtype == $rtype2[$i])
            	{
            		if($j==0)
            		{
           				if(isset($_POST['f'.$id_rubric]))
           				{
           					foreach($_POST['f'.$id_rubric] as $f_id=>$fval)
           					{           						
								if(!empty($fval)) $f_svyaz[$id_rubric][$fval] = $f_id;
							}
           				}	
						if(!isset($_POST['cp_val']))
            			foreach($feats as $item)
            			{
            				$f_id=$item[0];$f_gr=$item[1];$f_pos=$item[2];
            				if(!isset($f_svyaz[$id_rubric][$f_id]))
            				{
	            				if($prj>0)
								{
									list($fid)=$database->getArrayOfQuery("select ID_FEATURE from cprice_features where ID_FEATURE=".$f_id);
									if(empty($fid))
									{
										/*Добавление характеристики в другом проекте*/
										curbase();
										list($feature_text,$feature_rubric,$feature_type,$feature_require,$feature_multiple,$feature_graduation) = $database->getArrayOfQuery("select feature_text,feature_rubric,feature_type,feature_require,feature_multiple,feature_graduation from cprice_features where ID_FEATURE=".$f_id);
										if($feature_type==4)
										{
											$rres = $database->query("select ID_FEATURE_DIRECTORY, featuredirectory_text from cprice_feature_directory where ID_FEATURE=".$f_id);
											otherbase($prj);
											while($rrow=mysql_fetch_array($rres))
												$database->query("INSERT INTO cprice_feature_directory (ID_FEATURE,featuredirectory_text,ID_OLD) values ($f_id,'$rrow[1]','$rrow[0]')");
										}
										otherbase($prj);
										$database->query("INSERT INTO cprice_features (ID_FEATURE,feature_text,feature_rubric,feature_type,feature_require,feature_multiple,feature_graduation) values ($f_id,'$feature_text','$feature_rubric','$feature_type','$feature_require','$feature_multiple','$feature_graduation')");
									}
								}
								$line = $database->getArrayOfQuery("select count(*) from cprice_rubric_features WHERE ID_FEATURE=$f_id && ID_RUBRIC=$id_rubric && rubric_type=".$rtype);
	            				if($line[0]==0)
		            				$database->query("INSERT INTO cprice_rubric_features (ID_RUBRIC, ID_FEATURE, rubric_type, rubricfeature_graduation, rubricfeature_pos) values($id_rubric,$f_id,$rtype,$f_gr,$f_pos)");
	            			}
            			}
            		}
            		if($op=='move')
            		{            		  
					   if($id_rubric!=$rubric_id)
            		  {
            			if($i==0)
            			{
            				$database->query("UPDATE cprice_rubric_goods set ID_RUBRIC=$id_rubric WHERE ID_GOOD=".$id_good);
            				foreach($vals as $item)
            				{
            					$f_id=$item[0];
            					if(isset($f_svyaz[$id_rubric][$f_id]))
            					{
	            					$database->query("UPDATE cprice_goods_features SET ID_FEATURE=".$f_svyaz[$id_rubric][$f_id]." WHERE ID_GOOD=$id_good && ID_FEATURE=".$f_id."");
            					}
            				}
            			}
            			else {
            				$database->query("INSERT INTO cprice_goods  (good_visible,good_url) values(1,'$gurl')");
            				$new_id = $database->id();
            				$database->query("INSERT INTO cprice_rubric_goods (ID_RUBRIC, ID_GOOD) values($id_rubric,$new_id)");
            				foreach($vals as $item)
            				{
								$f_id=$item[0];$g_val=$item[1];$f_type=$item[2];$g_txt=$item[3];
            					if($f_type==7)
            					{
            						$database->query("INSERT INTO cprice_texts (text_text) values ('$g_txt')");
            						$g_val = $database->id();
            					}
            					$database->query("INSERT INTO cprice_goods_features (ID_GOOD,ID_FEATURE, goodfeature_value) values($new_id,".(isset($f_svyaz[$id_rubric][$f_id])?$f_svyaz[$id_rubric][$f_id]:$f_id).",'$g_val')");
            				}
							if(isset($_POST['cp_ph'])){
		               		//Копирование фотографий
		               		$res = $database->query("select * from cprice_goods_photos where ID_GOOD=".$id_good." && goodphoto_visible=1 && goodphoto_deleted=0");
		               		while($row = mysql_fetch_array($res))
		               		{
		               			$file = $row[2];
		               			$filedir = DATA_FLD."good_photo/".$file;
								$def_dir = DATA_FLD."good_photo/";
								$filename = pathinfo($filedir);
								$fn = substr($file,0,-strlen($filename['extension'])-1);
					            $i = 0;
								while(file_exists($def_dir.$fn.(empty($i)?"":"_".$i).".".$filename['extension'])){
									$i++;
								}
								$filename = $fn.(empty($i)?"":"_".$i).".".$filename['extension'];
								copy($filedir,$def_dir.$filename);
								$filedir = str_replace($file,'trumb_'.$file,$filedir);
								if(file_exists($filedir))copy($filedir,$def_dir.'trumb_'.$filename);
								$filedir = str_replace('trumb_'.$file,'image_'.$file,$filedir);
								if(file_exists($filedir))copy($filedir,$def_dir.'image_'.$filename);
		               			$database->query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD, goodphoto_file,goodphoto_desc,goodphoto_alt,goodphoto_pos) VALUES('".$new_id."','".$filename."','".$row[3]."','".$row[4]."','".$row[5]."')");
		               		}
							//Копируем сео-параметры
	                       	$row_seo = $database->getArrayOfquery("SELECT * FROM ".DB_PREFIX."metadata WHERE metadata_page=3 && metadata_id='$id_good' limit 1");
	                       	if($row_seo)
	                       	{
	                       		$database->query("INSERT INTO ".DB_PREFIX."metadata (metadata_page,metadata_id,metadata_head_title,metadata_meta_title,metadata_meta_keywords,metadata_meta_description,metadata_body_h1,metadata_body_h2,metadata_body_description,metadata_body_keywords)
	                       			VALUES (3,'$new_id','$row_seo[3]','$row_seo[4]','$row_seo[5]','$row_seo[6]','$row_seo[7]','$row_seo[8]','$row_seo[9]','$row_seo[10]')");

	                       	}
							}
            			}
            		  }
            		}elseif($op=='copy')
            		{
            			if($prj>0)
						{
							list($new_id) = $database->getArrayOfquery("select ID_GOOD from cprice_goods where ID_GOOD=".$id_good);
							if($new_id>0)$database->query("INSERT INTO cprice_goods (good_visible,good_url) values(1,'$gurl')");
							else $database->query("INSERT INTO cprice_goods (ID_GOOD,good_url,good_visible) values('$id_good','$gurl',1)");
						}
						else $database->query("INSERT INTO cprice_goods (good_visible,good_url) values(1,'$gurl')");
						$new_id = $database->id();
           				$database->query("INSERT INTO cprice_rubric_goods (ID_RUBRIC, ID_GOOD) values($id_rubric,$new_id)");
           				foreach($vals as $item)
           				{
           					$f_id=$item[0];$g_val=$item[1];$f_type=$item[2];$g_txt=$item[3];
           					if(!isset($_POST['cp_val']))
						{
							if($f_type==7)
							{
								$database->query("INSERT INTO cprice_texts (text_text) values ('$g_txt')");
								$g_val = $database->id();
							}
							if($f_type==4 && $prj>0 && !empty($g_val))
							{
								list($g_val) = $database->getArrayOfquery("select ID_FEATURE_DIRECTORY from cprice_feature_directory where ID_FEATURE=$f_id && ID_OLD=".$g_val);
							}
							if($f_type==6)
							{
								$file = $g_val;
								$filedir = DATA_FLD."features/".$file;
								if($prj>0)$def_dir = $hosts[$prj]['folder'].$hosts[$prj]['data']."features/";
								else $def_dir = DATA_FLD."features/";
								$filename = pathinfo($filedir);
								$fn = substr($file,0,-strlen($filename['extension'])-1);
								$i = 0;
								while(file_exists($def_dir.$fn.(empty($i)?"":"_".$i).".".$filename['extension'])){
									$i++;
								}
								$filename = $fn.(empty($i)?"":"_".$i).".".$filename['extension'];
								copy($filedir,$def_dir.$filename);
								$g_val = $filename;
							}
							$database->query("INSERT INTO cprice_goods_features (ID_GOOD,ID_FEATURE, goodfeature_value) values($new_id,".(isset($f_svyaz[$id_rubric][$f_id])?$f_svyaz[$id_rubric][$f_id]:$f_id).",'$g_val')");
						}
							elseif(isset($f_svyaz[$id_rubric][$f_id]))
							{
            					if($f_type==7)
            					{
            						$database->query("INSERT INTO cprice_texts (text_text) values ('$g_txt')");
            						$g_val = $database->id();
            					}
								if($f_type==4 && $prj>0 && !empty($g_val))
								{
									list($g_val) = $database->getArrayOfquery("select ID_FEATURE_DIRECTORY from cprice_feature_directory where ID_FEATURE=$f_id && ID_OLD=".$g_val);
								}
            					$database->query("INSERT INTO cprice_goods_features (ID_GOOD,ID_FEATURE, goodfeature_value) values($new_id,".$f_svyaz[$id_rubric][$f_id].",'$g_val')");									
							}
           				}
						if(isset($_POST['cp_ph']) || !isset($_POST['addf'])){
	               		//Копирование фотографий
	               		curbase();
						$res = $database->query("select * from cprice_goods_photos where ID_GOOD=".$id_good." && goodphoto_visible=1 && goodphoto_deleted=0");
	               		if($prj>0)
						{
							otherbase($prj);
						}
						while($row = mysql_fetch_array($res))
	               		{
	               			$file = $row[2];
	               			$filedir = DATA_FLD."good_photo/".$file;
							if($prj>0)$def_dir = $hosts[$prj]['folder'].$hosts[$prj]['data']."good_photo/";
							else $def_dir = DATA_FLD."good_photo/";
							$filename = pathinfo($filedir);
							$fn = substr($file,0,-strlen($filename['extension'])-1);
				            $i = 0;
							while(file_exists($def_dir.$fn.(empty($i)?"":"_".$i).".".$filename['extension'])){
								$i++;
							}
							$filename = $fn.(empty($i)?"":"_".$i).".".$filename['extension'];
							copy($filedir,$def_dir.$filename);
							$filedir = str_replace($file,'trumb_'.$file,$filedir);
							if(file_exists($filedir))copy($filedir,$def_dir.'trumb_'.$filename);
							$filedir = str_replace('trumb_'.$file,'image_'.$file,$filedir);
							if(file_exists($filedir))copy($filedir,$def_dir.'image_'.$filename);
	               			$database->query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD, goodphoto_file,goodphoto_desc,goodphoto_alt,goodphoto_pos) VALUES('".$new_id."','".$filename."','".$row[3]."','".$row[4]."','".$row[5]."')");
	               		}
						//Копируем сео-параметры
						curbase();
                       	$row_seo = $database->getArrayOfquery("SELECT * FROM ".DB_PREFIX."metadata WHERE metadata_page=3 && metadata_id='$id_good' limit 1");
                       	if($prj>0)otherbase($prj);
						if($row_seo)
                       	{
                       		$database->query("INSERT INTO ".DB_PREFIX."metadata (metadata_page,metadata_id,metadata_head_title,metadata_meta_title,metadata_meta_keywords,metadata_meta_description,metadata_body_h1,metadata_body_h2,metadata_body_description,metadata_body_keywords)
                       			VALUES (3,'$new_id','$row_seo[3]','$row_seo[4]','$row_seo[5]','$row_seo[6]','$row_seo[7]','$row_seo[8]','$row_seo[9]','$row_seo[10]')");

                       	}
						}
            		}
            	}
            	$i++;
			}
			$j++;
		  }
		  teRedirect(teGetUrlQuery("ok"));
		}
	} else {
		addSubMenu(teGetUrlQuery(),'Вернуться');
		print '<h2>Перенос, копирование, удаление и другие действия</h2>';
                list($feat_id) = $database->getArrayOfQuery("
                        SELECT ".DB_PREFIX."features.ID_FEATURE
                        FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
                        WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0
                        ORDER BY rubricfeature_pos, ID_FEATURE
                        LIMIT 1
                ");
		$res = $database->query("
			SELECT ".DB_PREFIX."goods.ID_GOOD,rubricgood_pos,goodfeature_value
			FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods natural join cprice_goods_features
			WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=$rubric_id && ID_FEATURE=".$feat_id."
			ORDER BY rubricgood_pos ASC, goodfeature_value
		");
			$res = $database->query("
			SELECT ".DB_PREFIX."goods.ID_GOOD,rubricgood_pos,goodfeature_value
			FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods natural join cprice_goods_features
			WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=$rubric_id && ID_FEATURE=30
			ORDER BY rubricgood_pos ASC, goodfeature_value
		");
		teAddJSScript("
			function formsumbit()
			{
				if(confirm('Вы уверены в своих действиях?')) return true; else return false;
			}
		");
		if(count($hosts)>1)
		{
			$prjs = '';
			foreach($hosts as $id_prj=>$val_prj)
			{
				if(@$val_prj['version']>1)
				{
					$selected = '';
					if($prj>0 && $prj==$id_prj)$selected = ' selected';
					elseif(DB_ID==$id_prj)$selected = ' selected';
					$prjs .= '<option value="'.$id_prj.'"'.$selected.'>'.$val_prj['name'].'</option>';
				}
			}
			if(!empty($prjs))
			$prjs = '<form method="post">
				<div style="text-align:right">
				Перенести в другой проект<select name="prj" onchange="this.form.submit();">'.$prjs.'</select></div></form>';
			print $prjs;
		}
		print "<form method='post' id='fform' onSubmit='return formsumbit();' >";
		print '<input type="hidden" name="prj" value="'.$prj.'" />';
		print "<table width='100%'><tr><td valign='top' width='50%'><div style='text-align:center;background-color:#B7DBFF;padding:3px;'><b>Что:</b></div>
		<h3>Из рубрики №".$rubric_id.": ".getRubricName($rubric_id,false,false,false)."</h3>";
		print "<table width='100%'>";
		print "<tr><td><input type='radio' name='op1' value='copy' id='copy' checked /></td><td width='100%'><label for='copy'>Копирование</label></td></tr>";
		print "<tr><td><input type='radio' name='op1' value='copy_val' id='copy_val' /></td><td><label for='copy_val'>Копирование со связкой характеристик</label></td></tr>";
		if(empty($prj))
		{
			print "<tr><td><input type='radio' name='op1' value='move' id='move' /></td><td><label for='move'>Перенос</label></td></tr>";
			print "<tr><td><input type='radio' name='op1' value='move_val' id='move_val' /></td><td><label for='move_val'>Перенос со связкой характеристик...</label></td></tr>";
			print "<tr><td><input type='radio' name='op1' value='svyazka' id='svyazka' onclick='$(\"#rub{$rubric_id}\").attr(\"checked\",\"checked\")' /></td><td><label for='svyazka'>Связка с нескольким рубриками</label></td></tr>";
			print "<tr><td><input type='radio' name='op1' value='enable' id='enable' /></td><td><label for='enable'>Включение</label></td></tr>";
			print "<tr><td><input type='radio' name='op1' value='disable' id='disable' /></td><td><label for='disable'>Отключение</label></td></tr>";
			print "<tr><td><input type='radio' name='op1' value='delete' id='delete' /></td><td><label for='delete'>Удаление</label></td></tr>";
			print "<tr><td><input type='radio' name='op1' value='malph' id='malph' /></td><td><label for='malph'>Маленькие фото</label></td></tr>";
			print "<tr><td><input type='radio' name='op1' value='bolph' id='bolph' /></td><td><label for='bolph'>Большие фото</label></td></tr>";
			print "<tr><td><input type='radio' name='op1' value='add_photo' id='add_photo' /></td><td><label for='add_photo'>Добавить фото...</label></td></tr>";
		}
		$i = 0;$out='';$j1 = '';$j2 = '';
		while(list($gid,$gpos,$gname) = mysql_fetch_array($res)){
			$out .= "<tr>";
			$out .= "<td><input class='goods-x' type='checkbox' id='g{$gid}' name='rubric[]' value='$gid' checked='checked' /></td>";
			$out .= "<td><label for='g{$gid}'>".getIdToPrint("goods",$gid).": ".$gname."</label></td>";
			$out .= "</tr>";
			$j1 .= "g{$gid}.checked=false;";
			$j2 .= "g{$gid}.checked=true;";
		}

		print "<tr><td colspan='2' style='text-align:center'><input type='submit' value='Запуск'><br/><br/></td></tr>";
		print "<tr><td colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' onclick='$j1' value='Снять выделение' /> <input type='button' onclick='$j2' value='Выделить все' /></td></tr>";
		print $out;
		print "<tr align=center><td colspan=2><input type='submit' value='Запуск'></td></tr>";
		print "</table></td><td valign='top'><div style='text-align:center;background-color:#B7DBFF;padding:3px;'><b>Куда:</b></div>";
		if($prj>0)
		{
			otherbase($prj);
			addGet("prj",$prj);
		}
        $res = $database->query("select ID_RUBRIC_TYPE, rubrictype_name from cprice_rubric_types WHERE rubrictype_visible=1 && rubrictype_deleted=0");
		teAddCSSCode("
			.rubs ul{margin:2px;list-style-type:none;padding:0px;}
			.rubs ul ul{margin:2px 10px;}
		");
		$table ='';
		function rubs($type, $parent=0, $rubs=array())
		{
			global $database;
			$res = $database->query("select ID_RUBRIC, rubric_name from cprice_rubric WHERE rubric_type='$type' && rubric_parent='$parent' && rubric_deleted=0");
			$out = '';
			while($row = mysql_fetch_array($res))
			{
				$out2 = rubs($type,$row[0],$rubs);
				$out .='<li>'.(empty($out2)?'<input type="checkbox" id="rub'.$row[0].'" value="'.$row[0].'" name="rubs[]"'.(in_array($row[0],$rubs)?' checked="checked"':'').' />':'&bull;&nbsp;').'<b>'.$row[0].'</b> <label for="rub'.$row[0].'">'.$row[1].$out2.'</label></li>';
			}
			if(!empty($out))$out = '<ul>'.$out.'</ul>';
			return $out;
		}

		$radios = '';$js='';
		while(list($tid,$name) = mysql_fetch_array($res)){
			$radios .= '<input class="radio" type="radio" name="rtype" id="type'.$tid.'" onclick="if(this.checked){%js%div'.$tid.'.style.display=\'\';}" value="'.$tid.'"'.($tid==$type?' checked><label for="type'.$tid.'"> Раздел: '.$name.' (текущий)':'><label for="type'.$tid.'"> Раздел: '.$name).'</label><br />';
			$table .= '<div id="div'.$tid.'" class="rubs"'.($tid==$type?'':' style="display:none" ').'>';
			$table .= rubs($tid);
			$table .= '</div>';
			$js .= 'div'.$tid.'.style.display=\'none\';';
		}
		$radios = str_replace("%js%",$js,$radios);
		print $radios;
		print $table;
		print "<div style='text-align:center'><input type='submit' value='Запуск'></div></td></tr></table>";

		print "</form>";
	}
	break;

	// отчет
	case 'order':
		include_once "goods_order.php";
	break;

	// сохранение в куках количества показываемых записей в рубрике
	case 'countinpage':
		setcookie("countinpage",(int)$_GET['value'],time()+60*60*365);
	break;

	// включение/отключение записи
	case 'enable2':
 		list($visible) = $database->getArrayOfQuery("SELECT good_visible FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$id);
 		if($visible) $visible=0; else $visible=1;
		$database -> query("UPDATE ".DB_PREFIX."goods SET good_visible=$visible WHERE ID_GOOD=".$id);
		del_cache($rubric_id,$id);//удаление кэша
		print $id.'|'.$visible;
		die();
	break;
	// включение записи
	case 'enable':
		$database -> query("UPDATE ".DB_PREFIX."goods SET good_visible=1 WHERE ID_GOOD=".$id);
		del_cache($rubric_id,$id);//удаление кэша
		unset($_GET['action']);
		if(isset($_GET['events']))
		{
			$res3 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric_events where ID_GOOD=".$id." && ID_USER=".$_USER['id']);
			if(mysql_num_rows($res3)==0){
				$database -> query("INSERT INTO ".DB_PREFIX."rubric_events (ID_GOOD, ID_USER, tdate) values ('".$id."','".$_USER['id']."','".time()."')");
			}
			teRedirect(teGetUrlQuery("=events_see","rub_id=".$rubric_id));
			exit;
		}
	break;

	// отключение записи
	case 'disable':
		$database -> query("UPDATE ".DB_PREFIX."goods SET good_visible=0 WHERE ID_GOOD=".$id);
		del_cache($rubric_id,$id);//удаление кэша
		unset($_GET['action']);
		if(isset($_GET['events']))
		{
			$res3 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric_events where ID_GOOD=".$id." && ID_USER=".$_USER['id']);
			if(mysql_num_rows($res3)==0){
				$database -> query("INSERT INTO ".DB_PREFIX."rubric_events (ID_GOOD, ID_USER, tdate) values ('".$id."','".$_USER['id']."','".time()."')");
			}
			teRedirect(teGetUrlQuery("=events_see","rub_id=".$rubric_id));
			exit;
		}
	break;

	// удаление записи из рубрики
	case 'deletefromrubric':
		$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$id." and ID_RUBRIC=".$rubric_id);
		del_cache($rubric_id,$id);//удаление кэша
		unset($_GET['action']);

// Добавление новости
/***  Алексей - 11.11.08   ***/
		$trig_result=$create_news_triger->News_del_good($type,6,$id,$rubric_id);
/***  /Алексей - 11.11.08   ***/
	break;

	// удаление записи вообще
	case 'delete':
		$database -> query("UPDATE ".DB_PREFIX."goods SET good_deleted=1 WHERE ID_GOOD=".$id, true, 3);
		del_cache($rubric_id,$id);//удаление кэша

/*		$line = $database -> getArrayOfQuery("SELECT * FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$id,MYSQL_ASSOC);

		$pname = "";
		$res = $database -> query("SELECT ".DB_PREFIX."rubric.ID_RUBRIC FROM ".DB_PREFIX."rubric NATURAL JOIN ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$id);
		while($line=mysql_fetch_array($res,MYSQL_NUM)){
			$pname .= " ".getRubricName($line[0],true,false,false).",";
		}
		$pname = substr($pname,0,strlen($pname));
*/
		unset($_GET['action']);
		if(isset($_GET['events']))
		{
			teRedirect(teGetUrlQuery("=events_see","rub_id=".$rubric_id));
			exit;
		}

		// Добавление новости
/***  Алексей - 11.11.08   ***/
		$trig_result=$create_news_triger->News_del_good($type,6,$id,$rubric_id);
/***  /Алексей - 11.11.08   ***/
	    if(AJAX)
	    {
	    	print 'list';
	    	die();
	    }
	break;

	}
}
// если действий нет
if(empty($_GET['action'])){
	// показываем список записей (во вложении)
	require_once "goods_list.php";
}
?>