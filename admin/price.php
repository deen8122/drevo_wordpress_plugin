<? if ( !isset($_status) )
{
	header("HTTP/1.0 404 Not Found");
	die;
} // прайс-лист

/**********
 *  ООО "УфаПиар.ру"
 *
 *  Разработчик: Давлетбаев Т.И., Галлямов Д.Р.
 *  ICQ: 222-811-798
 * irbis
 **********/


//echo "ТОвар";
define("CPRICE_PREFIX", "cprice_");

define("FNAME", 30);
define("FPRICE", 32);
define("FDESC", 31);

@session_start();
$catalog_textid = 'katalog';

// инициализация переменных
$rubric_txt = getInput('rubric_id');
$type = (int)getInput("type");

$rubric_id = 0;
if ( isset($_GET['rubric_id']) )
	list($rubric_id, $rubric_name, $parent, $rubric_photo, $prefix) = $database->getArrayOfQuery("SELECT ID_RUBRIC,rubric_name,rubric_parent,rubric_img,rubric_unit_prefixname FROM cprice_rubric WHERE rubric_type=".GOODS_TYPE." && rubric_deleted=0 && rubric_textid='".mysql_escape_string($_GET['rubric_id'])."'");
$rubric_id = intval($rubric_id);

$good_id = 0;
$good_name_to_path = '';
if ( isset($_GET['good_id']) )
{
	list($good_id) = $database->getArrayOfQuery("SELECT ID_GOOD
		FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods
		WHERE ID_RUBRIC=".$rubric_id." and good_deleted=0 and good_visible=1 && good_url='".mysql_escape_string($_GET['good_id'])."'"
	);
	$good_name_to_path = ' » <span class="activePage">'.getFeatureValue($good_id,FNAME).'</span>';

}
$content = '';
$parent_name = '';

if ( !empty($prefix) )
	$prefix .= ' ';
// операция поиска
$op = getInput('op');
if ( $op == 'search' )
{
	echo '<div class="pagePath"><a class="pagePath-link" href="/'.$page_arr[G_PAGE].'/">Каталог</a>  » Поиск товара по запросу</div>';	
	$find_text = mysql_escape_string(getInput("find_text",TRUE));
	$rubric = (int)getInput("rubric");
	$proizv = (int)getInput("proizv");
	$price1 = getInput("price1");
	$price2 = getInput("price2");
	$sql_str = 'select ID_GOOD,good_url,goodfeature_value,rubric_textid
				from cprice_rubric natural join cprice_rubric_goods natural join cprice_goods natural join cprice_goods_features
				where rubric_visible=1 && rubric_deleted=0 && good_visible=1 && good_deleted=0 && rubric_type='.GOODS_TYPE.' && ID_FEATURE='.FNAME.' && goodfeature_value like "%'.$find_text.'%"';
	if(!isset($_GET['extr']))
	{
		setWindowTitle("Поиск товара по запросу");
		print '<form action="/katalog/" method="get">
					<input type="hidden" name="op" value="search" />
					  Я ищу: <input type="text" name="find_text" class="inp-text" value="'.$find_text.'" />
					  <input type="submit" value=" найти товар " class="btn-green button" /> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a href="/katalog/?op=search&extr&find_text='.$find_text.'">Расширенный поиск</a>
				 </form>
				 <br/>
		';
	}
	else
	{
		$sel_cat = '';
		$rubrics_l1 = $database->getArrOfQuery('select ID_RUBRIC,rubric_name,rubric_textid
from cprice_rubric
where rubric_visible=1 && rubric_deleted=0 && rubric_type=2 && rubric_parent=0', 'ID_RUBRIC');
                $i = 1;
		foreach ( $rubrics_l1 as $rubric_l1_id => $rubric_l1_data )
		{

			if ( $rubrics_l2 = $database->getArrOfQuery('select ID_RUBRIC,rubric_name,rubric_textid
    from cprice_rubric 
    where rubric_visible=1 && rubric_deleted=0 && rubric_type=2 && rubric_parent='.$rubric_l1_id.' order by rubric_pos,rubric_name', 'ID_RUBRIC')
			)
			{
				foreach ( $rubrics_l2 as $rubric_l2_id => $rubric_l2_data )
				{
					list($n_goods) = $database->getArrayOfQuery("select count(ID_GOOD) from cprice_goods natural join cprice_rubric_goods where good_visible=1 && good_deleted=0 && ID_RUBRIC=".$rubric_l2_id);
					if($n_goods>0)
					{
						$sel_cat .= '<option value="'.$rubric_l2_id.'"'.($rubric_l2_id==$rubric?' selected="selected"':'').'>'.$rubric_l1_data['rubric_name'].' // '.$rubric_l2_data['rubric_name'].' ('.$n_goods.')</option>';
					}
				}
			}
		}
		$sel_proizv = '';
		$proizv_line = $database->getArrOfQuery('select ID_FEATURE_DIRECTORY, featuredirectory_text from cprice_feature_directory where ID_FEATURE=97', 'ID_FEATURE_DIRECTORY');
		foreach ( $proizv_line as $proizv_id => $proizv_data )
		{
			$sel_proizv .= '<option value="'.$proizv_id.'"'.($proizv_id==$proizv?' selected="selected"':'').'>'.$proizv_data['featuredirectory_text'].'</option>';
		}
		setWindowTitle("Расширенный поиск товара по запросу");
		print '<form action="/katalog/" method="get">
				<input type="hidden" name="op" value="search" />
				<input type="hidden" name="extr" value="1" />
					<table class="filtr">
					  <tr><td>Я ищу:</td><td><input type="text" name="find_text" class="inp-text" value="'.$find_text.'" /></td></tr>
					  <tr><td>Выберите категорию товара</td><td><select name="rubric" style="width:187px"><option value="0"></option>'.$sel_cat.'</select></td></tr>
					  <tr><td>Производитель</td><td><select name="proizv" style="width:187px"><option value="0"></option>'.$sel_proizv.'</select></td></tr>
					  <tr><td>Цена,руб</td><td>от <input type="text" name="price1" class="inp-text" value="'.$price1.'" style="width:60px" /> до <input type="text" name="price2" class="inp-text" value="'.$price2.'" style="width:60px" /></td></tr>
					  <tr><td>&nbsp;</td><td><input type="submit" value=" найти товар " class="btn-green button" /></td></tr>
					</table>
				 </form>
				 <br/>
		';
		if(!empty($rubric))$sql_str .= ' && ID_RUBRIC='.$rubric;
		if( !empty($proizv) || !empty($price1) || !empty($price2) || !empty($type) )
		{
			$price1 = intval($price1);$price2 = intval($price2);
			$rubric_sql = '';
			$add_sql = "(ID_FEATURE=".FNAME." && goodfeature_value like '%".$find_text."%')";
			$n = 1;
			if( !empty($proizv) ) {$add_sql .= " || (ID_FEATURE=97 && goodfeature_value='".$proizv."')"; $n++;}
			if( !empty($type) ) {$add_sql .= " || (ID_FEATURE=81 && goodfeature_value='".$type."')"; $n++;}
			if( !empty($price1) && !empty($price2)) {$add_sql .= " || (ID_FEATURE=".FPRICE." && goodfeature_value>=".$price1." && goodfeature_value<=".$price2.")"; $n++;}
			elseif( !empty($price1) ){$add_sql .= " || (ID_FEATURE=".FPRICE." && goodfeature_value>=".$price1.")"; $n++;}
			elseif( !empty($price2) ){$add_sql .= " || (ID_FEATURE=".FPRICE." && goodfeature_value<=".$price2.")"; $n++;}
			if(!empty($rubric))$rubric_sql .= '&& ID_RUBRIC='.$rubric;
			
			$sql_str = "select * from (select ID_GOOD,good_url,goodfeature_value,rubric_textid, count(ID_GOOD) as cnt, rubricgood_pos from cprice_rubric natural join cprice_goods natural join cprice_rubric_goods natural join cprice_goods_features
			where  rubricgood_deleted=0 $rubric_sql && good_visible=1 && good_deleted=0 && rubric_type=".GOODS_TYPE." && (".$add_sql.") group by ID_GOOD) as tbl where cnt=".$n;
			$sql_str = "select * from (select ID_GOOD,good_url,goodfeature_value, count(ID_GOOD) as cnt from cprice_goods natural join cprice_goods_features
			where good_visible=1 && good_deleted=0 && (".$add_sql.") group by ID_GOOD) as tbl natural join cprice_rubric natural join cprice_rubric_goods where cnt=".$n." && rubric_type=".GOODS_TYPE."".$rubric_sql;
		}
	}
	if ( (!empty($find_text) && strlen($find_text)>2) || $type>0 || $proizv>0)
	{
		$sql_str = '
			select temp.*, t2.goodfeature_value as nalichie from (
                        '.$sql_str.
			') as temp inner join cprice_goods_features as t2 on temp.ID_GOOD=t2.ID_GOOD
					where t2.ID_FEATURE=370 order by nalichie DESC'
		;
                $pgcount = getInput('c');
		if(empty($pgcount))$pgcount = 20;
		if($pgcount!='ALL' && intval($pgcount)>0)
		{
			$pgcount = intval($pgcount);
			$pg = (int)getInput("pg");
			$nums = numbers($sql_str, $pg, $pgcount, teGetUrlQuery('op=search', isset($_GET['extr']) ? 'extr=1' : '', $find_text ? 'find_text='.$find_text : '', $rubric ? 'rubric='.$rubric : '', $proizv ? 'proizv='.$proizv : '', $type ? 'type='.$type : '', $price1 ? 'price1='.$price1 : '', $price2 ? 'price2='.$price2 : '', 'c='.$pgcount));
			$sql_str .= ' limit '.$nums[0].','.$pgcount;
		}
		$data = $database->getArrOfQuery($sql_str, 'ID_GOOD');
		$u = 0;
		$items = '';
		foreach ( $data as $good_id => $good_data )
		{
			$good_name = getFeatData(FNAME, $good_id);
			if ( !$good_image = getImages($good_id) )
			{
				$good_image = '/images/nophoto.png';
			}
			$vals = getDataId($good_id, array(FNAME,FPRICE,370), TRUE, TRUE);
                	@$nalichie = (bool)$vals[370];
                        $nalichie_txt = '';
                        if($nalichie) {$button_add = '<a href="#" class="add-basket bottom_buy_cat" data-good="'.$good_id.'"></a>';$nalichie_txt = '<div class="nalichie">В наличии</div>';}
			else $button_add = '<a href="/predzakaz/?f56='.$good_id.'" class="bottom_order_cat btn-green dialog" data-good="'.$good_id.'">Предзаказ</a>';
			$link = '/'.$catalog_textid.'/'.$good_data['rubric_textid'].'/'.$good_data['good_url'].'/';
			$items .= '
							 <div class="img1_catal">
								 <ul class="nav_good">
									  <li>
											<a href="'.$link.'">'.$vals[FNAME].'</a>
									  </li>

								 </ul>
								 <a href="'.$link.'"><img class="image" id="img'.$good_id.'" src="'.$good_image.'" alt="'.$vals[FNAME].'" /></a>
                                                                 '.$nalichie_txt.'
								 <div class="price">'.$vals[FPRICE].' руб.</div>'.$button_add.'
							</div>
			';
			$u++;
			if($u==4)$u=0;
		}
		if(empty($items)) echo '<h2>Нет товаров, удовлетворяющих вашему запросу</h2>';
		else
		{
			echo '<div style="margin-bottom:10px;font-size:16px;">Найдено товаров: '.$nums[2].'</div>';
			echo $items.($nums[1]?'<div class="clear"></div><div style="text-align:center;margin-top:10px;">'.$nums[1].'</div>':'');			
		}

	}else print '<h2>Необходимо ввести параметр запроса</h2>';
	
}







elseif($op == 'cmpr')
{
	setWindowTitle("Товары для сравнения");
	echo '<div class="pagePath"><a class="pagePath-link" href="/'.$page_arr[G_PAGE].'/">Каталог</a>  » Товары для сравнения</div>';
	$cmpr_goods = array();
	if(isset($_COOKIE['cmpr_goods'])){$cmpr_goods = explode("|",$_COOKIE['cmpr_goods']);}
	$items = '';$u = 0;
	$rubrics = array();
	$feats_rubric = array();
	$feats_values = array();
	$nofeats = array(FNAME,FPRICE,31,81,142,306,307,308,308,391,418,419);
	foreach ($cmpr_goods as $good) {
		if($good>0)
		{
				list($rubric_id,$rubric_textid,$rubric_name0) = $database->getArrayOfQuery("SELECT ID_RUBRIC,rubric_textid,rubric_name FROM cprice_rubric natural join cprice_rubric_goods WHERE rubric_type=".GOODS_TYPE." && rubric_deleted=0 && ID_GOOD='".$good."'");
				$vals = getDataId($good, array(FNAME,FPRICE,370), TRUE, TRUE);
				if(!empty($rubric_textid) && !empty($vals['url']))
				{
					if(!array_key_exists($rubric_id, $rubrics))
					{
                                                $rubric_textid0 = $rubric_textid;
                				//list($rubric_name0,$rubric_textid0) = $database->getArrayOfQuery("SELECT rubric_name,rubric_textid FROM cprice_rubric WHERE ID_RUBRIC='".$rubric_id."'");
						$rubrics[$rubric_id] = '<h2><a href="/'.$catalog_textid.'/'.$rubric_textid0.'/">'.$rubric_name0.'</a></h2>';
						$res_feat = $database->query("
							SELECT ID_FEATURE, feature_text
							FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
							WHERE ID_RUBRIC=".$rubric_id." && feature_deleted=0 && ID_FEATURE NOT IN (".implode(',', $nofeats).")
							ORDER BY rubricfeature_pos
						");
						$feats_rubric[$rubric_id][] = FNAME;
						$feats_rubric[$rubric_id][] = FPRICE;
						$feats_values[$rubric_id][9999999] = '<tr><td class="name">Действие</td>';
						$feats_values[$rubric_id][1] = '<tr><td class="name">Фото</td>';
						$feats_values[$rubric_id][FNAME] = '<tr><td class="name">Название</td>';
						$feats_values[$rubric_id][FPRICE] = '<tr><td class="name">Цена,руб</td>';
						while ( list($feature_id,$feature_name) = mysql_fetch_array($res_feat) )
						{
							$feats_rubric[$rubric_id][] = $feature_id;
							$feats_values[$rubric_id][$feature_id] = '<tr><td class="name">'.$feature_name.'</td>';
						}
					}
					if ( !$good_image = getImages($good) )
					{
						$good_image = '/images/nophoto.png';
					}
					$link = '/'.$catalog_textid.'/'.$rubric_textid.'/'.$vals['url'].'/';
					foreach ($feats_values[$rubric_id] as $feat => $values) {
						if($feat==1)$feats_values[$rubric_id][$feat] .= '<td class="cmp'.$good.'"><a href="'.$link.'"><img class="image" id="img'.$good.'" src="'.$good_image.'" alt="'.$vals[FNAME].'" /></a></td>';
						elseif($feat==FNAME)$feats_values[$rubric_id][$feat] .= '<td class="cmp'.$good.'"><a href="'.$link.'">'.$vals[FNAME].'</a></td>';
						elseif($feat==FPRICE)$feats_values[$rubric_id][$feat] .= '<td class="cmp'.$good.'">'.$vals[FPRICE].'</td>';
						elseif($feat==370) $feats_values[$rubric_id][$feat] .= '<td class="cmp'.$good.'">'.($vals[370]>0?'Да':'Предзаказ').'</td>';
                                                elseif($feat==9999999) $feats_values[$rubric_id][$feat] .= '<td class="cmp'.$good.'"><input type="button" value="Удалить из сравнения" data-good="'.$good.'" class="cmpr-del" /></td>';
						else $feats_values[$rubric_id][$feat] .= '<td class="cmp'.$good.'">'.getFeatData($feat, $good).'</td>';
					}
/*					$items .= '
									 <div class="img1_catal" style="'.($u==3?'border-color: #000 #fff;':'').'">
										 <ul class="nav_good">
											  <li>
													<a href="'.$link.'">'.$vals[FNAME].'</a>
											  </li>

										 </ul>
										 <a href="'.$link.'"><img class="image" id="img'.$good.'" src="'.$good_image.'" alt="'.$vals[FNAME].'" /></a>
										 <div class="price">'.$vals[FPRICE].' руб.</div><a href="#" class="add-basket bottom_buy_cat" data-good="'.$good.'"></a>
									</div>
					';
					$u++;
					if($u==4)$u=0;*/
				}
		}
	}
	foreach ($rubrics as $rubric_id=>$rname) {
		$items .= $rname;
		$items .= '<table class="cmpr">';
		foreach ($feats_values[$rubric_id] as $feat => $values) {
			$items .= $values.'</tr>';
		}
		$items .= '</table>';
		
	}
	echo $items;
	if(empty($items))		echo 'Нет товаров для сравнения';
}



else
{
	// генерация страницы товара
	if ( $good_id > 0 )
	{
		$add_jss = '';
		if( $rubric_id==24 || $parent==24 )
		{
			echo '<!-- INTENCY TRACKING CODE BEGIN -->
<img alt="" style="display:none" src="http://display.intencysrv.com/pixel?id=443&t=img" />
<!-- INTENCY TRACKING CODE END -->
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement(\'img\')).src = location.protocol + \'//vk.com/rtrg?r=oZfY60ibuYkixvsWSL9zYPfUvj0zgkmRdSnAaH*wgmWUGwflQLh21QcaUTGVYxWSC3lef3v/uTsj9i9wA6yk5CINDYTMVZxxJabwIWN3ie*ZJ/ShUXgAhNc8O6WEblKlOwl87Kg6OqCaytNJZJdXT8J02HN0V3aXlU6cGv2UPDM-\';</script>
';
			$add_jss = <<<TXT
window.onload = function() {
  _tmr.push({ id: '2546943', type: 'reachGoal', goal: 'PALATKI' });
}
TXT;
		}
		if( $rubric_id==40 || $parent==40 )
		{
			echo '<!-- INTENCY TRACKING CODE BEGIN -->
<img alt="" style="display:none" src="http://display.intencysrv.com/pixel?id=444&t=img" />
<!-- INTENCY TRACKING CODE END -->
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement(\'img\')).src = location.protocol + \'//vk.com/rtrg?r=w64CNFOnCvPw269SBXg8I1ONnNxg75VrdVOScgjGL5mxO9PVgTUPfEWARX1orkHzKE3m9*3crZRyJbaLhDl16LPrCVxWmsuoShs9qMDQlGXbYwwzAiTAzzSQiib2kCy4xG55tpbv3Dvj77fTTFfp3wzRVQlKSMf*ElFiSanuq0g-\';</script>
';
			$add_jss = <<<TXT
window.onload = function() {
  _tmr.push({ id: '2546943', type: 'reachGoal', goal: 'RYUKZAKI' });
}
TXT;
		}
		$add_jss = <<<TXT
<script type="text/javascript">
function rrAsyncInit() {
try{ rrApi.view($good_id); } catch(e) {}
}
$add_jss
</script>
TXT;
		$_template->addToVar('add_jss', $add_jss);
		//товары для сравнения
		$cmpr_goods = array();
		if(isset($_COOKIE['cmpr_goods']))$cmpr_goods = explode("|",$_COOKIE['cmpr_goods']);

		teAddJSScript('','/js/share42/share42.js');
		$_template->addVar('catpanel', '');
		$good_data = getDataId2($good_id);
		setMetadata(2, $good_id, $good_data[FNAME]);
		// рисунки и фото товара
		$images = getImages($good_id, 99, "trumb_", true);
		$images_main = array_shift($images);

		$images_other = '';
		foreach ( $images as $image )
		{
			$title = $good_data[FNAME];
			$alt = $good_data[FNAME];
			if(!empty($image[1]))$title = $image[1];
			if(!empty($image[2]))$alt = $image[2];
			$images_other .= '<a rel="gal" href="'.str_replace("trumb_", '', $image[0]).'" title="'.$title.'"><img class="prodImgMin-img" src="'.$image[0].'" alt="'.$alt.'"/></a> ';
		}
		if(!empty($images_other))$images_other = '<div class="images">'.$images_other.'</div>';

		
		
		
		// цена товара
		$price = $good_data[FPRICE];
		$prod_desc = '';
		$prod_desc .= isset($good_data[FDESC]) ? '<div class="head">Описание товара:</div>'.$good_data[FDESC] : '';
		$prod_desc .= '<script type="text/javascript" src="//dev.mneniya.pro/js/wwwirbis-shopru/mneniyafeed.js"></script><div id="mneniyapro_feed"></div>';
		/*
		 * добавлено 170614
		 * Кагармановым Д
		 * 
		 */
		$prod_desc .='<div class="rr-widget" 
     data-rr-widget-product-id="'.$good_id.'"
     data-rr-widget-id="538032631e99441e68085cdf"
     data-rr-widget-width="100%"></div>';
		
		
		
		@$user = (int)$_COOKIE['user'];
		$feats = '';
		$no_view = array(FNAME,FDESC,FPRICE,142,81,306,307,308,370,308,391,418,419,462);
		foreach($good_data as $feat=>$val)
		{
			if(!in_array($feat, $no_view) && !empty($val))$feats .= '<tr><td>'.  getFeatText($feat).'</td><td>'.(is_array ($val) ? implode(", ", $val) : $val).'</td></tr>';
		}
		if($good_data[370]=='да')$nalichie = true;
		else $nalichie = false;
		$nalichie_txt = '';
		if(@$nalichie)$nalichie_txt = '<div class="nalichie" title="В наличии">В наличии</div>'
			. '<div class="sklads">'
			. 'в интернет-магазине: '.(@$good_data[418]>0||@$good_data[419]>0||@$good_data[391]>0?'да':'нет').'<br/>'
			. 'в Уфе: '.(@$good_data[418]>0?'да':'нет').'<br/>'
			. 'в Стерлитамаке: '.(@$good_data[419]>0?'да':'нет').'<br/>'
			. '</div>';
		if(!empty($feats))$feats = '<div class="head">Характеристики товара:</div>
	                         <table>'.$feats.'</table>';
		$title = $good_data[FNAME];
		$alt = $good_data[FNAME];
		if(!empty($image[1]))$title = $images_main[1];
		if(!empty($image[2]))$alt = $images_main[2];
		echo '
                    <div style="float:left">
                        <div class="left_goods">
                            <div class="image_main">
                                    <a class="prodImgMin-lnk" rel="gal" href="'.str_replace("trumb_", '', $images_main[0]).'" title="'.$title.'"><img class="prodImgMin-img" id="img'.$good_id.'" src="'.str_replace("trumb_", 'image_', $images_main[0]).'" alt="'.$alt.'"/></a>
                            </div>
                            '.$images_other.'	
                        </div>
                        '.(!isset($_GET['print'])?'
                        <div class="social_like"><div class="share42init"></div></div>':'').'
                    </div>
                    <div class="right_goods">
                        <h1 class="name_goods">'.$good_data[FNAME].'</h1>
                        <div class="left">
                            '.$feats.'
                        </div>
								'.(!isset($_GET['print'])?'
                        <div class="rightt">
                            <div class="price_goods">'.number_format($good_data[FPRICE],0,',',' ').' руб.</div>
									 '.$nalichie_txt.'
                            '.($nalichie?'<a href="/predzakaz/?f56='.$good_id.'" id="buy" class="buy_goods add-basket btn-red" href="#" data-good="'.$good_id.'">Купить</a>':'<a href="/predzakaz/?f56='.$good_id.'" id="buy" class="buy_goods btn-red dialog" data-good="'.$good_id.'">Предзаказ</a>').' 
									 <a href="/one-click/?f56='.$good_id.'" class="btn-green by_one dialog">Купить в один клик</a>
									 <label><input type="checkbox" class="cmpr" data-good="'.$good_id.'"'.(in_array($good_id, $cmpr_goods)?' checked="checked"':'').' /> Сравнить</label>
                            <a href="#" class="asterisk '.($user>0?'add_fav':'dis_user').'"" id="f'.$good_id.'">Отложить товар</a>
                            <!-- <a href="#" class="rrr"><div class="rating" ><p>Рейтинг 3,5</p></div></a> кнопка Рейтинг --> 
									 <a href="/rassrochka/?f56='.$good_id.'" class="btn-green by_one buy_on2 dialog">Купить в рассрочку</a>
                            <div class="right_g_b">
                                <a href="/vashi-voprosi/#ask4298" class="delivery">ДОСТАВКА</a>
                                <div>Бесплатная доставка по всей России от 5000 рублей</div>
                                <hr>
                                <a href="/vashi-voprosi/#ask4299" class="payment">ОПЛАТА</a>
                                <div>
                                    Удобные способы оплаты                   
                                </div>
                                <img src="/skins/main/images/mastercard.png" alt="MasterCard">
                                <img src="/skins/main/images/visa.png" alt="Viza">
                            </div>                                                                             
                        </div>':'').'
                    </div>
                    <div class="clear"></div>
                    <div class="desc">
                        '.$prod_desc.'              
                    </div>			
		    
						  '.(!isset($_GET['print'])?'
                    <ul class="centmenu">
                        <li class="menuItem menuItem-first">
                            <a class="menuItem-link" title="Как купить?" href="/vashi-voprosi/">Как купить?</a>
                        </li>
                        <li class="menuItem menuItem-last">
                            <a class="menuItem-link" title="Где купить?" href="/roznichnie-magazini/">Где купить?</a>
                        </li>
                        <!--<li class="menuItem">
                            <a class="menuItem-link" title="Видео" href="#">Видео</a>
                        </li>
                        <li class="menuItem menuItem-last">
                            <a class="menuItem-link" title="Отзывы" href="#" onclick="$(\'#otzivs\').toggle();$(\'#msg\').focus();return false;">Отзывы</a>
                        </li>-->
                    </ul>':'');
			if(!isset($_GET['print']) && 0)
			{
				$data = getData( 269, 'ID_GOOD DESC', '', array( 414, 417  ), false, array( 416 => $good_id ) );
				print '<div id="otzivs" style="display:'.(isset( $_GET[ 'msg' ] ) || isset($_POST['name0']) || count($data)>0?'block':'none').'">';
				print "<h2>Отзывы</h2>";
				if ( count( $data ) > 0 )
				{
					foreach ( $data as $gid => $vals )
					{
						list( $dt ) = $database->getArrayOfQuery( "select DATE_FORMAT(change_dt,'%d.%m.%y %H:%i') from cprice_changes  where change_row='".$gid."' && change_table='cprice_goods' && change_type=1" );
						print '
							<div>
								<b>'.$vals[ 414 ].'</b> ('.$dt.')<br/>
								'.$vals[ 417 ].'
							</div>
							<div class="right2">&nbsp;</div>';
					}
				}
				if ( isset( $_GET[ 'msg' ] ) )
				{
					print "<div style='color:#00f' id='msg0'>Ваш отзыв принят, после просмотра модератором, он будет включен</div>";
				}
				
				$frm = new teForm( "form2", "post" );
				$frm->setSubmitCaption( "  Добавить отзыв  " );
				$frm->addf_text( "name0", 'Имя' );
				$frm->addf_text( "email0", 'E-mail' );
				if($user>0){
					$frm->add_value ( "name0" , $_COOKIE['user_name']);
					$frm->add_value ( "email0" , getFeatureValue($user, 21));
				}
				$frm->addf_ereg( "email0", "^[a-z0-9_-]+[a-z0-9_.-]*@[a-z0-9_-]+[a-z0-9_.-]*\.[a-z]{2,5}$" );
				$frm->addf_text( "msg", 'Сообщение', "", true );
				$frm->addf_text( "checkimage", "Код на картинке: <img src='/checkimage.php' alt='код на картинке' title='введите цифры с этой картинки в поле рядом' /> ", "" );
				$frm->setf_require( "name0", "email0", "msg", "checkimage" );
				if ( !$frm->send() )
				{
					@session_start();
					if ( $_SESSION[ 'checkimage' ] == md5( $frm->get_value( 'checkimage' ) ) )
					{
						$data = array();
						$data[ 414 ] = $frm->get_value( "name0" );
						$data[ 415 ] = $frm->get_value( "email0" );
						$data[ 417 ] = $frm->get_value( "msg" );
						$data[ 416 ] = $good_id;
						$oid = insertData( 269, $data, 0 );
						$sitename = $_SERVER[ 'SERVER_NAME' ];
						$date = strftime( "%T %m.%d.%y" );

						$subject = "Письмо с сайта $sitename от $date";
						$message = "
						<h1>Отзыв с сайта $sitename от $date</h1>
						<table>
					";

						foreach ( $data AS $fid => $fval )
						{

							$message .= "<tr>";
							$message .= "<td style='background-color:#DDDDDD;border:1px solid #cccccc;'>".getFeatText( $fid ).": </td>";
							if ( $fid == 416 )
							{
								$fval = $good_data[FNAME];
								$message .= "<td style='background-color:#FEFEFE;border:1px solid #cccccc;'><b>".$fval."</b></td>";
							}
							else
								$message .= "<td style='background-color:#FEFEFE;border:1px solid #cccccc;'><b>".$fval."</b></td>";
							$message .= "</tr>";
						}

						$message .= "
						</table>
					";
						$headers = "MIME-Version: 1.0\r\n";
						$headers .= "Content-type: text/html; charset=windows-1251\r\n";
						$headers .= "From: $sitename <info@$sitename>\r\n";
						$line = $database->getArrayOfQuery( "select var_value from cprice_configtable where var_name='notify_269'" );
						$to = '';
						if ( !empty( $line ) )
						{
							$arr1 = explode( "|", $line[ 0 ] );
							if ( !empty( $arr1[ 1 ] ) )
								$to = $arr1[ 1 ];
							if ( isset( $arr1[ 3 ] ) )
							{
								require_once( "/var/www/cprice/notify.php" );
								ninsert( DB_ID, explode( "$", $arr1[ 3 ] ), 269, $oid );
							}
						}
						$url = "/".$page_arr[ G_PAGE ]."/";
						if ( isset( $_GET[ 'rubric_id' ] ) )
							$url .= $_GET[ 'rubric_id' ]."/";
						if ( isset( $_GET[ 'rubric_id' ] ) )
							$url .= $_GET[ 'good_id' ]."/";
						if ( !empty( $to ) )
						{
							$array_per = array(
								 '%site_name%',
								'%name%',
								'%date%',
								'%body%' 
							);
							$let = $database->getArrayOfQuery( "select var_value from cprice_configtable  where var_name='".$arr1[ 0 ]."'" );
							$arr2 = explode( "|", $let[ 0 ] );
							$letter = explode( "$#", $arr2[ 1 ] );
							$letter[ 1 ] = nl2br( $letter[ 1 ] );
							$message .= '<br/><br/>Сообщение со страницы: http://'.$_SERVER[ 'SERVER_NAME' ].$url;
							$body_admin = str_replace( $array_per, array(
								 $_SERVER[ 'SERVER_NAME' ],
								'',
								date( "d.m.Y" ),
								$message 
							), $letter[ 1 ] );
							mail( $to, str_replace( $array_per, array(
								 $_SERVER[ 'SERVER_NAME' ],
								'',
								date( "d.m.Y" ),
								'' 
							), $letter[ 0 ] ), $body_admin, $headers );
						}
						Header( "Location: ".$url."?msg#msg0" );
					}
					else
					{
						$frm->errorValue( "checkimage", 'Неправильный код на картинке' );
						$frm->send();
					}

				}
				print '</div>';

			}
			echo '
                    <div class="hrr"><hr></div>			
<div class="clear"></div>';
//-------------------------------------------"взаимозаменяемые товары" и "с этим товаром покупают"
	// расположение определяет размер слайдера по классу
	$hits_txt = '';
	$hits = getFeatureValue($good_id, 307, true);
	$hits_items = '';
	foreach ( $hits as $hits_good_id)
	{
		if($hits_good_id!=$good_id && $hits_good_id>0)
		{
		$good_name = getFeatureValue($hits_good_id, FNAME);
		$rubric_textid = $database->getCellOfQuery("SELECT rubric_textid FROM cprice_rubric natural join cprice_rubric_goods WHERE ID_GOOD=".$hits_good_id);
		$good_url = $database->getCellOfQuery("SELECT good_url FROM cprice_goods WHERE ID_GOOD=".$hits_good_id);

		$good_price = getFeatureValue($hits_good_id, FPRICE);

		@$nalichie = (bool)getFeatureValue($hits_good_id, 370);
		if($nalichie) $button_add = '<a href="#" class="add-basket bottom_buy_cat" data-good="'.$hits_good_id.'"></a>';
		else $button_add = '<a href="/predzakaz/?f56='.$hits_good_id.'" class="bottom_order_cat btn-green dialog" data-good="'.$hits_good_id.'">Предзаказ</a>';

		$nalichie_txt = '';
		if(@$nalichie)$nalichie_txt = '<div class="nalichie" title="в наличии">в наличии</div>';

		$good_image = getImages($hits_good_id);
		if(empty($good_image))$good_image = '/images/nophoto.png';
		if(isset($data_feats[306]) && $data_feats[306]>0) $new = '<img src="/skins/main/images/new.png" alt="новинка" class="hit_cat" />';
		$hits_items .= '
                          <div class="img1_catal">
                            <ul class="nav_good">
                                <li>
                                    <a href="'.teGetUrlQuery($rubric_textid, $good_url).'">'.$good_name.'</a>                                   
                                </li>
                                
                            </ul>
			    <a href="'.teGetUrlQuery($rubric_textid, $good_url).'"><img class="image" id="img'.$hits_good_id.'" src="'.$good_image.'" alt="'.$good_name.'" /></a>
                            '.$nalichie_txt.'
                            <div class="price">'.$good_price.' руб.</div>'.$button_add.'
                          </div>
';
		}
	}
	if(!empty($hits_items))
	$hits_txt .= <<<TXT
<div class="head_con_cat">С этим товаром покупают</div>
<div class="slider3"> 
			$hits_items
</div>
<div class="clear"></div>
TXT;
	$hits = getFeatureValue($good_id, 308, true);
	$hits_items = '';
	foreach ( $hits as $hits_good_id)
	{
		if($hits_good_id!=$good_id && $hits_good_id>0)
		{
		$good_name = getFeatureValue($hits_good_id, FNAME);
		$rubric_textid = $database->getCellOfQuery("SELECT rubric_textid FROM cprice_rubric natural join cprice_rubric_goods WHERE ID_GOOD=".$hits_good_id);
		$good_url = $database->getCellOfQuery("SELECT good_url FROM cprice_goods WHERE ID_GOOD=".$hits_good_id);

		$good_price = getFeatureValue($hits_good_id, FPRICE);

		@$nalichie = (bool)getFeatureValue($hits_good_id, 370);
		if($nalichie) $button_add = '<a href="#" class="add-basket bottom_buy_cat" data-good="'.$hits_good_id.'"></a>';
		else $button_add = '<a href="/predzakaz/?f56='.$hits_good_id.'" class="bottom_order_cat btn-green dialog" data-good="'.$hits_good_id.'">Предзаказ</a>';

		$nalichie_txt = '';
		if(@$nalichie)$nalichie_txt = '<div class="nalichie" title="в наличии">в наличии</div>';

		$good_image = getImages($hits_good_id);
		if(empty($good_image))$good_image = '/images/nophoto.png';
		if(isset($data_feats[306]) && $data_feats[306]>0) $new = '<img src="/skins/main/images/new.png" alt="новинка" class="hit_cat" />';
		$hits_items .= '
                          <div class="img1_catal">
                            <ul class="nav_good">
                                <li>
                                    <a href="'.teGetUrlQuery($rubric_textid, $good_url).'">'.$good_name.'</a>                                   
                                </li>
                                
                            </ul>
			    <a href="'.teGetUrlQuery($rubric_textid, $good_url).'"><img class="image" id="img'.$hits_good_id.'" src="'.$good_image.'" alt="'.$good_name.'" /></a>
                            '.$nalichie_txt.'
                            <div class="price">'.$good_price.' руб.</div>'.$button_add.'
                          </div>
';
		}
	}
	if(!empty($hits_items))
	$hits_txt .= <<<TXT
<div class="head_con_cat">Аналогичные товары</div>
<div class="slider3"> 
			$hits_items
</div>
<div class="clear"></div>
TXT;
	
	$_template->addVar('hits', $hits_txt);
		if(!$nalichie)	echo '<div class="rr-widget"
     data-rr-widget-product-id="'.$good_id.'"
     data-rr-widget-id="538032631e99441e68085ce2"
     data-rr-widget-type="forNotAvailableItem"
     data-rr-widget-width="960px"></div>';

	}				
	// выборка каталога по определенной модели автомобиля. выбирается по ИД
	elseif ( isset($_GET['rubric_id']))
	{
		$add_jss = '';
		if( $rubric_id==24 || $parent==24 )
		{
			echo '<!-- INTENCY TRACKING CODE BEGIN -->
<img alt="" style="display:none" src="http://display.intencysrv.com/pixel?id=443&t=img">
<!-- INTENCY TRACKING CODE END -->
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement(\'img\')).src = location.protocol + \'//vk.com/rtrg?r=oZfY60ibuYkixvsWSL9zYPfUvj0zgkmRdSnAaH*wgmWUGwflQLh21QcaUTGVYxWSC3lef3v/uTsj9i9wA6yk5CINDYTMVZxxJabwIWN3ie*ZJ/ShUXgAhNc8O6WEblKlOwl87Kg6OqCaytNJZJdXT8J02HN0V3aXlU6cGv2UPDM-\';</script>
';
			$add_jss = <<<TXT
window.onload = function() {
  _tmr.push({ id: '2546943', type: 'reachGoal', goal: 'PALATKI' });
}
TXT;
		}
		if( $rubric_id==40 || $parent==40 )
		{
			echo '<!-- INTENCY TRACKING CODE BEGIN -->
<img alt="" style="display:none" src="http://display.intencysrv.com/pixel?id=444&t=img">
<!-- INTENCY TRACKING CODE END -->
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement(\'img\')).src = location.protocol + \'//vk.com/rtrg?r=w64CNFOnCvPw269SBXg8I1ONnNxg75VrdVOScgjGL5mxO9PVgTUPfEWARX1orkHzKE3m9*3crZRyJbaLhDl16LPrCVxWmsuoShs9qMDQlGXbYwwzAiTAzzSQiib2kCy4xG55tpbv3Dvj77fTTFfp3wzRVQlKSMf*ElFiSanuq0g-\';</script>
';
			$add_jss = <<<TXT
window.onload = function() {
  _tmr.push({ id: '2546943', type: 'reachGoal', goal: 'RYUKZAKI' });
}
TXT;
		}
		$add_jss = <<<TXT
<script type="text/javascript">
function rrAsyncInit() {
try { rrApi.categoryView($rubric_id); } catch(e) {}
}
$add_jss
$(function (){
	$('#rub{$rubric_id}').addClass('select');
	$('#rub{$rubric_id}').next('.sub').show();
	$('#rubsub{$rubric_id}').addClass('sel');
	$('#rubsub{$rubric_id}').parent('.sub').show();
	$('#rubsub{$rubric_id}').closest('.sub').prev('.main').addClass('select');
})
</script>
TXT;
		$_template->addToVar('add_jss', $add_jss);		
		$view = getInput('v');
		//if(empty($view))$view = 'VIEW_TBL';
		$sort = getInput('s');
		$pgcount = getInput('c');
		if(empty($pgcount))$pgcount = 20;
		$request = getInput('text');
		if(isset($_GET['print']))
		{
			$view = 'VIEW_TBL';
			$pgcount = 'ALL';
		}


		$mod_links = '';
		$mod_options = '';
		setMetadata(2,$rubric_id,$rubric_name);
		$add_params = $rows1 = $rows2 = $add_sql = '';
		$add_url = array();
		$res_feat = $database->query("
				SELECT ID_FEATURE, feature_text, feature_type
				FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
				WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0 && feature_graduation=1
				group by ID_FEATURE
				ORDER BY rubricfeature_pos
			");
		$n = 0;
		$n_uslov = 0;
		$find_text = getInput('find_text');
		if(!empty($find_text))
		{
			$add_sql .= ' || (ID_FEATURE='.FNAME.' && goodfeature_value like "%'.$find_text.'%")';
			$add_url[] = 'find_text='.urldecode($find_text);
			$n_uslov ++;
		}
		while (list($feature_id, $feature_name, $feature_type) = mysql_fetch_array($res_feat))
		{
			if($n==6)
			{
				$add_params .='<table><tr>'.$rows1.'</tr><tr>'.$rows2.'</tr></table>';
				$rows1 = $rows2 = '';
				$n=0;
			}
			$rows1 .= '<td class="hd">'.$feature_name.'</td>';
			$value = getInput('f'.$feature_id);			
			switch ($feature_type) {
				case 1:
					@$value1 = getInput('f'.$feature_id.'_min');
					@$value2 = getInput('f'.$feature_id.'_max');					
					if(!empty($value1) || !empty($value2))
					{
						$value1 = floatval(str_replace(",", ".", $value1));
						$value2 = floatval(str_replace(",", ".", $value2));
						if(!empty($value1))$add_url[] = 'f'.$feature_id.'_min='.$value1;
						if(!empty($value2))$add_url[] = 'f'.$feature_id.'_max='.$value2;

						$add_sql .= ' || (ID_FEATURE='.$feature_id.(!empty($value1)?' && goodfeature_float>='.$value1:'').(!empty($value2)?' && goodfeature_float<='.$value2:'').')';
						$n_uslov ++;
					}
					$rows2 .= '<td>
							от <input type="text" class="sm_inp" name="f'.$feature_id.'_min" value="'.$value1.'" />
							до <input type="text" class="sm_inp" name="f'.$feature_id.'_max" value="'.$value2.'" />
						</td>';					

				break;
				case 4:
					if(!empty($value))
					{
						$add_url[] = 'f'.$feature_id.'='.urldecode($value);						
						$add_sql .= ' || (ID_FEATURE='.$feature_id.' && goodfeature_value='.$value.')';
						$n_uslov ++;
					}
					
					//$res1 = $database -> query("SELECT * FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$feature_id." ORDER BY featuredirectory_text");
					$res1 = $database -> query("SELECT ID_FEATURE_DIRECTORY, featuredirectory_text, goodfeature_value FROM ".DB_PREFIX."feature_directory "
						. "INNER JOIN cprice_goods_features on ID_FEATURE_DIRECTORY=goodfeature_value natural join cprice_rubric_goods natural join cprice_goods natural join cprice_rubric WHERE ".DB_PREFIX."feature_directory.ID_FEATURE=".$feature_id." && (ID_RUBRIC=$rubric_id || rubric_parent=$rubric_id) && good_visible=1 && good_deleted=0 group by ID_FEATURE_DIRECTORY ORDER BY featuredirectory_text");

					$rows2 .= '<td><select name="f'.$feature_id.'" class="inp"><option value=""></option>';
					while( $line1 = mysql_fetch_array($res1,MYSQL_ASSOC) ){
						if($line1['ID_FEATURE_DIRECTORY']==$line1['goodfeature_value'])
						$rows2 .= '<option value="'.$line1['ID_FEATURE_DIRECTORY'].'"'.($value==$line1['ID_FEATURE_DIRECTORY']?' selected="selected"':'').'>'.$line1['featuredirectory_text'].'</option>';
					}
					$rows2 .= '</select></td>';
					
				break;

				default:
					if(!empty($value))
					{
						$add_url[] = 'f'.$feature_id.'='.urldecode($value);						
						$add_sql .= ' || (ID_FEATURE='.$feature_id.' && goodfeature_value like "%'.$value.'%")';
						$n_uslov ++;
					}					
					$rows2 .= '<td><input type="text" class="inp" name="f'.$feature_id.'" value="'.$value.'" /></td>';					
				break;
			}
			$n++;
		}
		$nal_im = getInput("im");
		$nal_ufa = getInput("ufa");
		$nal_sterl = getInput("sterl");
		$usl_city = false; $usl_im = false;
		if($nal_ufa>0 && empty($nal_sterl))
		{
			$add_url[] = 'ufa=1';
			$add_sql .= ' || (ID_FEATURE=418 && goodfeature_value > 0)';
			$n_uslov ++;
			$usl_city = true;
		}
		if($nal_sterl>0 && empty($nal_ufa))
		{
			$add_url[] = 'sterl=1';
			$add_sql .= ' || (ID_FEATURE=419 && goodfeature_value > 0)';
			$n_uslov ++;
			$usl_city = true;
		}
		if($nal_sterl>0 && $nal_ufa>0)
		{
			$add_url[] = 'ufa=1';
			$add_url[] = 'sterl=1';
			$add_sql .= ' || (ID_FEATURE=418 && goodfeature_value > 0)';
			$n_uslov ++;
			$add_sql .= ' || (ID_FEATURE=419 && goodfeature_value > 0)';
			$n_uslov ++;
			$usl_city = true;
		}
		if($nal_im>0)
		{
			$add_url[] = 'im=1';
			if(!$usl_city) $usl_im = true;
		}
		if(!empty($rows1))$add_params = 'Дополнительные параметры '.$add_params.'<table><tr>'.$rows1.'</tr><tr>'.$rows2.'</tr></table>';
		$search_form = '
				<form action="'.teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', $sort ? 's='.$sort : '', $pgcount ? 'c='.$pgcount : '', $type>0 ? 'type='.$type : '').'" method="post">
					<div class="search-form">
						<div class="head">Искать в этом разделе: <input type="text" name="find_text" class="search-text" value="'.$find_text.'" /></div>
						'.$add_params.'
						<b>Наличие в магазинах:</b> <input type="checkbox" id="im" name="im" value="1"'.($nal_im?' checked="checked"':'').' /><label for="im">Интернет-магазин</label> &nbsp;&nbsp;&nbsp;<input type="checkbox" id="ufa" name="ufa" value="1"'.($nal_ufa?' checked="checked"':'').' /><label for="ufa">Уфа</label> &nbsp;&nbsp;&nbsp;<input type="checkbox" id="sterl" name="sterl" value="1"'.($nal_sterl?' checked="checked"':'').' /><label for="sterl">Стерлитамак</label>
						<input type="hidden" name="op" value="search_goods" />	  
						<div class="bottom"><input type="submit" value=" найти товар " class="btn-green button" /></div>
					</div>
				</form>			
		';
		$_template->addVar('search_form', $search_form);
		
		// ================================== ПАНЕЛЬ УПРАВЛЕНИЯ =================================
		$catpanel = '
			Режим отображения: 
			<a href="'.teGetUrlQuery($rubric_txt, ($view != 'VIEW_TBL'?'v=VIEW_TBL':''), $sort ? 's='.$sort : '', $pgcount ? 'c='.$pgcount : '', $type>0 ? 'type='.$type : '',  implode("&", $add_url)).'" ><img class="head_up_img" '.($view != 'VIEW_TBL' ? 'src="/skins/main/images/foto_tabl.png" alt="table"' : 'src="/skins/main/images/foto_tabl_active.png" alt="table"').' /></a>
			&nbsp;&nbsp;&nbsp;&nbsp;
			Сортировать по: 
			<a href="'.teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', 's=SORT_PRC', $pgcount ? 'c='.$pgcount : '', $type>0 ? 'type='.$type : '',  implode("&", $add_url)).'" class="srtFld'.($sort != 'SORT_NM' ? ' srt-chkd' : '').'">[цене]</a>
			<a href="'.teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', 's=SORT_NM', $pgcount ? 'c='.$pgcount : '', $type>0 ? 'type='.$type : '',  implode("&", $add_url)).'" class="srtFld'.($sort == 'SORT_NM' ? ' srt-chkd' : '').'">[названию]</a>
			&nbsp;&nbsp;&nbsp;&nbsp;Выводить по:
			<a class="outCnt'.($pgcount != 40 && $pgcount != 'ALL' ? ' srt-chkd' : '').'" href="'.teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', $sort ? 's='.$sort : '', $type>0 ? 'type='.$type : '', 'c=20',  implode("&", $add_url)).'">[20]</a>
			<a class="outCnt'.($pgcount == 40 ? ' srt-chkd' : '').'" href="'.teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', $sort ? 's='.$sort : '', $type>0 ? 'type='.$type : '', 'c=40',  implode("&", $add_url)).'">[40]</a>
			<a class="outCnt'.($pgcount == 'ALL' ? ' srt-chkd' : '').'" href="'.teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', $sort ? 's='.$sort : '', $type>0 ? 'type='.$type : '', 'c=ALL',  implode("&", $add_url)).'">[все]</a>
			&nbsp;&nbsp;			
			<span class="print"><img class="head_up_img" src="/skins/main/images/print.png" alt="print"><a href="?print" target="_blank">Версия для печати</a></span>

		';
		$_template->addVar('catpanel', $catpanel);
		// ================================== ПАНЕЛЬ УПРАВЛЕНИЯ =================================

		$addsql = '';
		if ( isset($_GET['rubric_id']) )
		{
			$addsql .= ' && (ID_RUBRIC='.$rubric_id.' || rubric_parent='.$rubric_id.')';
		}


		$dev_head = '';
		$dev_query = '';
		$dev_sign = ' order by CAST(SUBSTRING_INDEX(goodfeature_value, "|", 1) AS signed)';

		$sql_str = 'select ID_GOOD,good_url,goodfeature_value,rubric_textid
				from cprice_rubric natural join cprice_rubric_goods natural join cprice_goods natural join cprice_goods_features
				where rubric_visible=1 && rubric_deleted=0 && good_visible=1 && good_deleted=0 && rubric_type=2 && ID_FEATURE='.($sort!='SORT_NM'?FPRICE:FNAME).$addsql.$dev_sign;

		$sql_str = '
			select temp.*, t2.goodfeature_value as nalichie from (
				select ID_GOOD,good_url,goodfeature_value as sort_feat,rubric_textid, rubric_pos, ID_RUBRIC
					from cprice_rubric natural join cprice_rubric_goods natural join cprice_goods natural join cprice_goods_features
					where rubric_visible=1 && rubric_deleted=0 && good_visible=1 && good_deleted=0 && rubric_type=2 && ID_FEATURE='.($sort!='SORT_NM'?FPRICE:FNAME).$addsql.
			') as temp inner join cprice_goods_features as t2 on temp.ID_GOOD=t2.ID_GOOD
					where t2.ID_FEATURE=370'.($usl_im?' && t2.goodfeature_value>0':'').' group by t2.ID_GOOD'
		;
		if($type>0)
		{			
			$sql_str = '
				select temp2.* from ('
					.$sql_str.
				') as temp2 inner join cprice_goods_features as t3 on temp2.ID_GOOD=t3.ID_GOOD
						where t3.ID_FEATURE=81 && t3.goodfeature_value='.$type.' group by temp2.ID_GOOD'
			;
		}
		if(!empty($add_sql))
		{
			$sql_str = '
				select temp4.* from (
					select temp3.*, count(temp3.ID_GOOD) as cnt from ('
						.$sql_str.
					') as temp3 inner join cprice_goods_features as t4 on temp3.ID_GOOD=t4.ID_GOOD
							where ('.substr($add_sql,4).') group by temp3.ID_GOOD
				)
				as temp4 where cnt='.$n_uslov
			;			
		}
		$sql_str .= ' order by nalichie DESC, rubric_pos, '.($sort!='SORT_NM'?'CAST(SUBSTRING_INDEX(sort_feat, "|", 1) AS signed)':'sort_feat');
		$nums = array('','');
		if($pgcount!='ALL' && intval($pgcount)>0)
		{
			$pgcount = intval($pgcount);
			$pg = (int)getInput("pg");
			$nums = numbers($sql_str, $pg, $pgcount, teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', $sort ? 's='.$sort : '', $type>0 ? 'type='.$type : '', 'c='.$pgcount,  implode("&", $add_url)));
			$sql_str .= ' limit '.$nums[0].','.$pgcount;
		}
		$data = $database->getArrOfQuery($sql_str, 'ID_GOOD');
		$u = 0;
		$rubrics = array();
		$features[] = array();
		$feature_names[] = array();
		$feature_types[] = array();
		$items = '';
		//товары для сравнения
		$cmpr_goods = array();
		if(isset($_COOKIE['cmpr_goods']))$cmpr_goods = explode("|",$_COOKIE['cmpr_goods']);
		$no_feats = array(FDESC,81,307,308,370,391,418,419,462);
		foreach ( $data as $good_id => $good_data )
		{
			if( !in_array($good_data['ID_RUBRIC'], $rubrics) )
			{
				$res_feat = $database->query("
					SELECT ID_FEATURE, feature_type
					FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
					WHERE ID_RUBRIC=".$good_data['ID_RUBRIC']." and feature_deleted=0 and feature_enable=1 and ID_FEATURE NOT IN (".implode(",",$no_feats).")
					ORDER BY rubricfeature_pos
				");
				while ( list($feature_id, $ftype) = mysql_fetch_array($res_feat) )
				{
					if($feature_id>0){
						$features[$good_data['ID_RUBRIC']][] = $feature_id;
						$feature_types[$feature_id] = $ftype;
					}
				}
				$rubrics[] = $good_data['ID_RUBRIC'];
			}
			$data_feats = getDataId2($good_id,$features[$good_data['ID_RUBRIC']],$feature_types);
			$feats = '';
			$no_view = array(FNAME,FDESC,FPRICE,142,81,306,307,308,370,391,418,419,462);
			foreach($data_feats as $feat=>$val)
			{
				if(!in_array($feat, $no_view) && !empty($val))
				{
					if(!isset($feature_names[$feat])) $feature_names[$feat] = getFeatText($feat);
					$feats .= '<tr><td>'. $feature_names[$feat] .'</td><td>'.(is_array ($val) ? implode(", ", $val) : $val).'</td></tr>';
				}
			}
			@$nalichie = (bool)getFeatureValue($good_id, 370);
			if($nalichie) $button_add = '<a href="#" class="add-basket bottom_buy_cat" data-good="'.$good_id.'"></a>';
			else $button_add = '<a href="/predzakaz/?f56='.$good_id.'" class="bottom_order_cat btn-green dialog" data-good="'.$good_id.'">Предзаказ</a>';
			$good_name = $data_feats[FNAME];
			if ( !$good_image = getImages($good_id) )
			{
				$good_image = '/images/nophoto.png';
			}
			$good_price = $data_feats[FPRICE];
//			$good_dev = getFeatData(97, $good_id);
			if(empty($good_price))$good_price = '-';
			$new = '';
			if ( $view == 'VIEW_TBL' )
			{
				if($good_data['nalichie']>0)$new = '<div class="nalichie2">В наличии</div>';				
				$items .= '
<tr>
	<td class="goods td1">
		'.(!isset($_GET['print'])?'
		<ul class="nav_good">
			<li>
				<a href="'.teGetUrlQuery($good_data['rubric_textid'], $good_data['good_url']).'" ><img class="catTbl-img" id="img'.$good_id.'" src="'.$good_image.'" alt="'.$good_name.'" id="img'.$good_id.'"/></a>
				'.(!empty($feats) ? '
					<div>
							<table class="feats">
								'.$feats.'
							</table>
						</div>':'').'
			  </li>

		 </ul>':'
			 <img class="catTbl-img" id="img'.$good_id.'" src="'.$good_image.'" alt="'.$good_name.'" id="img'.$good_id.'"/>
		').'
	</td>
	<td class="goods td2"><a href="'.teGetUrlQuery($good_data['rubric_textid'], $good_data['good_url']).'">'.$good_name.'</a>'.$new.'</td>
	<td class="goods td3">'.$good_price.'</td>
	'.(!isset($_GET['print'])?'<td class="goods td5"><input type="checkbox" class="cmpr" data-good="'.$good_id.'"'.(in_array($good_id, $cmpr_goods)?' checked="checked"':'').' /></td>
	<td class="goods td6">'.$button_add.'</td>':'').'
</tr>';
			}
			else
			{
				if(isset($data_feats[306]) && $data_feats[306]>0) $new = '<img src="/skins/main/images/new.png" alt="новинка" class="hit_cat" />';
				if($good_data['nalichie']>0)$new .= '<div class="nalichie">В наличии</div>';
				$items .= '
                          <div class="img1_catal">
                            <ul class="nav_good">
                                <li class="first">
					<a href="'.teGetUrlQuery($good_data['rubric_textid'], $good_data['good_url']).'">'.$good_name.'</a>
					<a href="'.teGetUrlQuery($good_data['rubric_textid'], $good_data['good_url']).'"><img class="image" id="img'.$good_id.'" src="'.$good_image.'" alt="'.$good_name.'" /></a>					    
                                    '.(!empty($feats) ? '
												<div>
														<table class="feats">
															'.$feats.'
														</table>
													</div>':'').'
                                </li>
                                
                            </ul>
                            '.$new.'
                            <div class="price">'.$good_price.' руб.</div>'.$button_add.'
                            <div class="niceCheck"><label><input type="checkbox" class="cmpr" data-good="'.$good_id.'"'.(in_array($good_id, $cmpr_goods)?' checked="checked"':'').' /> Сравнить</label></div>
                            </div>
';
				$u++;
				if($u==3)$u=0;
			}
		}
		
		if ( $items )
		{
			$content = $view == 'VIEW_TBL' ? '<table class="catTbl"><thead>
					<th>Фото</th>
					<th>Название</th>
					<th>Цена, руб.</th>
					'.(!isset($_GET['print'])?'<th>Сравнить</th>
					<th>Купить</th>':'').'
					</thead><tbody>'.$items.'</tbody></table>' : $items;
			if(!empty($nums[1]))$content .= '<div class="clear"></div><div class="nums">'.$nums[1].'<br/>Выводить по:
			<a class="outCnt'.($pgcount != 40 && $pgcount != 'ALL' ? ' srt-chkd' : '').'" href="'.teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', $sort ? 's='.$sort : '', $type>0 ? 'type='.$type : '', 'c=20').'">[20]</a>
			<a class="outCnt'.($pgcount == 40 ? ' srt-chkd' : '').'" href="'.teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', $sort ? 's='.$sort : '', $type>0 ? 'type='.$type : '', 'c=40').'">[40]</a>
			<a class="outCnt'.($pgcount == 'ALL' ? ' srt-chkd' : '').'" href="'.teGetUrlQuery($rubric_txt, $view ? 'v='.$view : '', $sort ? 's='.$sort : '', $type>0 ? 'type='.$type : '', 'c=ALL').'">[все]</a></div>';

			
		}
		else
		{
			$content = '<div class="catMsg">Товары, соответствующие запросу, не найдены!</div>';
		}
		if( $rubric_id == 24 || $parent == 24 )
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=IjOzPOHPtCZ649mXxy*Y5Sz0G*Pxyar9T2xFnSD/KdIaJGRr*4UvVWlIR0nRvt3LzwY9Uu8v4sGbuXQkp4fb7n0E8mJUYVxmx4jQhAlWxnq0j5gFUh0h6INsoyDji4Q/JTtrfuOKymQuALKBcVDDK*wYyNlM50ubF7QiFjiyE8k-';</script>';
TXT;
		}
		if( $rubric_id ==40  || $parent == 40 )
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=Za*oyCr0KUcEh7nEayjQ1qyf7rcHRZnA73e*B6RgdZudyAef5aeDkFeDie4vaKTiFeXliiWkVmTewewM2ORBydpubW/IvjAvj3JwOF06CSCJ8zXVufGjOxRzITxmpDyoi4b6YqpIMaJZXfQ*THX3HRyIWv6Bju0GD3xn7x/PtqU-';</script>
TXT;
		}
		if( $rubric_id == 53 || $parent == 53)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=hujkFOiqcJU*hr7jL0f68kVDKgBvUxfTZhaM62YdMliz6r2zYxZBDTtqc*1BYriIX7udsjI1v/*Bs06ln4kcOWwLr/TZe*5YVUY9vAcUbCdTt/UpM0RoKTqrOPTPzYQ7Lsbsyda1KNMX9yROJQzD/4TEjmmao2tt3CHx/q44rY4-';</script>
TXT;
		}
		if( $rubric_id == 58 || $parent == 58)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=Dv/TblC1JgtuOXaIFpN7sh0sqTOY6P/qxuqGOdIdTsIKXs*yYG79pil/WiW/N8YRBTfH/UHDQVHpUkK*PBl6kEkWS8EtcBSyo8v2IHX2MH7alEGfsylwsoNK1oaHU47VwqmIlosI4Xj/YKDWdyq1sVi2V95yNeHUE2RZ1BOZCpM-';</script>
TXT;
		}
		if( $rubric_id == 64 || $parent == 64)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=pbiQYwKX1r0P5R*/G*WyUYYuc6cwQbRScvgZILIjuFZWNOZT32nuqZJ4yTXRFQRJGb6znQoVNOnLwlZUNcADMUxHDVBWWcqDfsxbhNexqwO93SAWuvrnBEv5KQqoilPu9puBxZ0FZQoHBqiootpOexKhJwkUMFdg*x9Q*90TG1Y-';</script>
TXT;
		}
		if( $rubric_id == 287 || $parent == 287)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=fEPaaRltZvbC/b1nNrmRmmhZwYGPJ*81RYSK4uE7udaQp9Ncbcpx5j/aeSU8g0HrfEpulSvVz4cMjKIMQ4ZVahsLmQDaossmSbTtSDpj3M4MGsg4aw4sIw4JTmj2xaQbgDWmJ5VlyCFt*LxSOs*meKB8SiLu3vWwrdKITHFazqo-';</script>
TXT;
		}
		if( $rubric_id == 72 || $parent == 72)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=plb1V7ZuJxzgZf7Z/yqBQHXSE4hi*XxMiOs1kqHcTGZGr4KqPQdmN7GOot2onNLmV3w9v/QRP9vpZtrDYTBdNUsxbcseh5NjJd73oQ60JlH0QP*7xd4k9OQnpYCBOWMOLtaddw6aFh3Z*LaukioWo5ggF*MDydt8CkMGgZZfpWU-';</script>
TXT;
		}
		if( $rubric_id == 81 || $parent == 81)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=pgoLqK2tXh7mL7mtXu*xMniOugnY2ozbpxGS5MoznK7LRXpTMtphP9JbMBxlY*TWKErT/qUplIJQLgzQKFpNAHVwJ2JJrhwh47vwTt4JsPeSbphboNqBeIRuoi6boEopoKXvIAwgJJAPJsomNoZjYVcma9ttq1yeKjA6IV7pcXo-';</script>
TXT;
		}
		if( $rubric_id == 92 || $parent == 92)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=cSJEclaKkkkMtLWiPqFAhG1C801ZONvPfvt3UOgA98rLK*o4foQOvgPJQTA/MKhmMooSs5SmUnAuKS40qwSkaCDXmcSlnV7Z33XWkkIUDjxYPfmCCPuFKBpT87hwv6Fe7kZ*tABJJQJXkFBuA1ACaTtxN17IZ7hF7Gx7G1u8WWM-';</script>
TXT;
		}
		if( $rubric_id == 110 || $parent == 110)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=U69Qn1A2AgGAK98IjOcgV2SL/F0HD33TuLj5CyYgUhwYvoqO29g1yIoEdoaYW1AEy*ibYWAILxOrcviR92bx7crHzyYZ49BbN6RrSpqN0izbw4/jbq*msSde21c*Yo5CXYKHP9G5jHEBuxy2cREaFC7zfso3FTdRsoJaX31sETA-';</script>
TXT;
		}
		if( $rubric_id == 126 || $parent == 126)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=gQu42wNS*KaFJyhbN4lbE6mkwFCZtPB*SYW71/1*xi8LQy09SVBbpeNmpNb4j3PYC20zG88u5h2i2IkMc/j7800bZWO9E6HRHevSSrKtqS9gJ0HTJCw67hpPKm6/Sp5uyIp9RNMS0J5XHK4D7D8X*2Z4semE7IYGPGR5DKy8AtI-';</script>
TXT;
		}
		if( $rubric_id == 130 || $parent == 130)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=t4/BI8CmxcY8NpEc4CLOLXCwqCuOt*oUgVZ2X6//qKPJgOHhMeAlw6CGCSYK/rydG2Tk25D1nAfZGYYiMSe5HverIt1tc/b4tPLaKHgt53MUoF6rK6mDETxD1oWoZUhvQp7NxxSXnnY9OOH6Ir4uPokg*MgSdfdSn5NJglojsyw-';</script>
TXT;
		}
		if( $rubric_id == 139 || $parent == 139)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=NJqms5cK*F0XKo6hIv41iTAnZJ42ChBm0KKlhVZSJ11lXowgMDrWEL5/107AX/FR1xFefGYVjd4HQ7CezsrpHKWXyA1XyxspDEuiYhdLuWGXaGngaJxeikQs9ZLad7AAOkV8Ufx3ee/TMABLnvIJirC6N/JZVgoULXMI6cpmgEY-';</script>
TXT;
		}
		if( $rubric_id == 145 || $parent == 145)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=0Gj0WAeamiMW3*kFWy4jiiQ7LrMUrdC3tT3lmcBPOsi9h6BRCzuB0eIlYmu6PAyyqVobjaGTSyxtM0YPlja6E6ihT2hm2ftcRf5/BKVoaxlUOb4bQMe7uKWg5ivRYjFUQOmpYqxmGZLWmKgX1w4n8ywcOgi/b8*P*ms3aHgecz0-';</script>
TXT;
		}
		if( $rubric_id == 153 || $parent == 153)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=nbcJ0mZuQ1a7i0w21VYXeCa8oyJg/tdvnehdKJ1ROTUmTUr3o0jm7S2FYFPTG1ZxRMF9wcMFrwzDSBvft4tUngw0i5OsFTmm1ACa/xQWHkwOGgz4muaF5wNPnF5YgO4URsSu0ayMOCfObq8u1KDVWDHwxUoK8PqloO2MpB9iePM-';</script>
TXT;
		}
		if( $rubric_id == 163 || $parent == 163)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=gYkvAlxWuoIaFziPCle2Koc9vPdA2vHeoF0jdmdB6S6asDJNqKKsu7/k2sIputf3tsY3o7*uBPKUCfAkCx0kGvPMXMTWQ8uktV36dVFo4KH4QW9ydBy1l5MsqMMObjmea6grHc76DLXyOuE/srHo/mVIg7lIZUVZ9gtLQky/h78-';</script>
TXT;
		}
		if( $rubric_id == 169 || $parent == 169)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=ULxHETOAvpHgYIraN2wPOYyPrF1*XF*gqa2BGkFl2yoNLO*X/erhBs71rQGSWPCsdJTgDLqL7EanLpu3ILTnTdRoNFZyBBc2MAoPNrC1pE12T9zs7VsDahpC9wHc18nOppwnqdFNudOymxp0TgKnqO2sUDu1MuSe9ZPz9ut*zxQ-';</script>
TXT;
		}
		if( $rubric_id == 174 || $parent == 174)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=EfFAEJv09*Y*jZB85ZJm0Ndpw7f5TvkIDhcLSj0DaQ3BU0*e9sr4V8BEGJha9A0CNbkhGKTgRzf/NW7X8K6z0XfbGFzR8YMh4Jdfwm6pQaIoHHc4coB8HEseA9cYwfrCSWaB9O2imFyANW6ZETujUtX0Ke8grtQm/eB0wkHj2Hk-';</script>
TXT;
		}
		if( $rubric_id == 178 || $parent == 178)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=lymdGNqRWQRoU/22xItPa1jh4IQyJiiwErIJfAvWgdVZVNE2kZ/pV8g99Y3RXUzr4by*2ToDQnObqP2kXwvhxoXxhBomffuDf*j5*AGZNqmzf2ZcEBBwMMBdHPod9MDofguiUEex2HX1unxKvFMK1wkprERJKpO3YRBgagB2C04-';</script>
TXT;
		}
		if( $rubric_id == 183 || $parent == 183)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=UupuRBzkVtJjT*i2miOaxfz1IxPyUu1YhddXkz69nt2xzJ/r/tutDcEDY1Qjo92nG5Ryx9trzYjrrJ8u3S9rZYJ7V0CnmaMm0h8bju9*AdI8F3c5a4Xn6uRLrZ4drr3I5*EYW33WidRo8QWTcWJ7udbWvJjEzpVs6CidhYRJgOo-';</script>
TXT;
		}
		if( $rubric_id == 187 || $parent == 187)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=ab*qoEoRl8ctmaFNjje3dxglBI4ZUxCsVoqksVYJtFkssVhZRvixs60*kgh0Egp9epHJs*cCdQejmfx9NfY9dw0Jhzm0AsEeOediDagYVvHn2cUGQJ3y7GS*vUsQdCtgOITjDNCkIcBtyqkuRkBUqEQZ45dxtvFYoCg/c1jV9Ww-';</script>
TXT;
		}
		if( $rubric_id == 192 || $parent == 192)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=mQKeirtzzfkSwDpc1diCmXVwHUL7Vw8tVgVTxu8*zTEW*9wZ72esSmGRfHrZ2/XAQC71KHPFqsW/Y/y7fD7VIeJk530SBJg1F3Wy9M3LFd7sVY5P3bEx*QDuYfsCPz7W2Zpj48Lrli37ukDCqw1QWRpe41eMt5uC/WglEse9PdA-';</script>
TXT;
		}
		if( $rubric_id == 204 || $parent == 204)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=alsKMvI27*RGVAKZgMC/MAvbBA6yjM32Qtk*bgqRIWQyqWq3hbZhRUe3pKePtMPgcGyY2un4rxVUR9prl/qODt3BEAQZdf0IsUn42mRZE*AuGUbYAk3xKcp23vbw4iMtMcZOtWSY19h/nENSqAy8l5OPpZpqFVFAd8avLegjwC0-';</script>
TXT;
		}
		if( $rubric_id == 211 || $parent == 211)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=nQ2iBdn6Go6qZJAa8bZZdGwm70fveUMIWXWYt8qXPeZkyTrFy/QRkeARc8TL2H6vHGXw8uUjfiocjlhlv1kyIHDNFejT1pE4AtI5KEOT4lFGE*fhaZCVkS44kIjgohBsajwm8d0erbwynzhUCU9d/7ewNhcgKJZh9C5riQn3fpQ-';</script>
TXT;
		}
		if( $rubric_id == 244 || $parent == 244)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=XOX37ARDIfecGpxoPx*TFMnInjVbX2THfw2IqMbLXIyOPTgrQ2HgLXsnTLRQPhCttSCEM1KGHxTLVftyYVjDbHljlIJT2ErpKPK2QmLTz7El6OaaaeXCVNhFYyz*aO64RJI/EMKWTMWsuwRApaf8kG*Wywx4xwQ0x0mUjIv7qAE-';</script>
TXT;
		}
		if( $rubric_id == 250 || $parent == 250)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=Gjnk4yDhclsqTHEcwAw82TVo7GX8MgCComI9BWQL7Vjs6wKZQgCnoA/dD7*d0fp4Jn1aFAekIelfxChnAXzBH2QvKPOwdhcPoF4woGowgDIixxOYfiITS96KhJp14kR8aR5Pp/43dAroW/GOd7QUXC3qCtgXtZIazA3opZ6pSOs-';</script>
TXT;
		}
		if( $rubric_id == 253 || $parent == 253)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=OlYiQvRG8RJ7xuNC5iahE59CfnDWtCUcpysEGhbob*k7GztaD4aC8s8KoypdqADDeElX1Y1vCS3P4YMasuJOT9/jk8jiT*3rEfxPCw1T3QE2BC1KpFAW9M8ckZhVa2LnEsSDCh1wkwAAzh46tz/HHHu4Ti8kHpRdFulXvFi6nZ0-';</script>
TXT;
		}
		if( $rubric_id == 236 || $parent == 236)
		{
			$content .= <<<TXT
<script type="text/javascript">(window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=OCFJc2QUgWLijzxPSr8Q*fd78IuhLH2bowBaC4HboC1wnPozE3x/JYplzDCiHUY4zAcShSvvYIuhNAYYpGVddz5M9Cak6hbTwzuIVPc5hNUDPhFuDe8Y/keSHObfsctxYGS8J/vLam0d1mGSIRyB5zRbyJoNKMvXSYYRkH95Ikc-';</script>
TXT;
		}
	}
	
	
	
	
	
	
	
	
	
	// главная страница каталога со списком моделей и разделами запчастей
	else
	{
		if(isset($_GET['type']))
		{
			$_SESSION['type'] = (int)$_GET['type'];
			$type = (int)$_GET['type'];
			list($type_head) = $database->getArrayOfQuery("select featuredirectory_text from cprice_feature_directory where ID_FEATURE_DIRECTORY=".$type);
			setWindowTitle($type_head);
		}
                else {
			if(isset($_SESSION['type']))
			{
				if($_SESSION['type']>0)
				{
					$_SESSION['type'] = 0;
					teRedirect('/katalog/');
				}
			}
		}
/*		if($type>0)
		{			
			list($rubric_txt) = $database->getArrayOfQuery("select rubric_textid from cprice_rubric natural join cprice_goods natural join cprice_rubric_goods natural join cprice_goods_features
				where  rubricgood_deleted=0 && ID_FEATURE=81 && goodfeature_value=$type && good_visible=1 && good_deleted=0 && rubric_type=".GOODS_TYPE." order by ID_RUBRIC, rubricgood_pos");			
		}
		teRedirect(teGetUrlQuery($rubric_txt,($type>0?'type='.$type:'')));*/
		$_template->addVar('search_form', '');
		$_template->addVar('catpanel', '');
		
		$inner_items = '';
		$rubrics_l1 = $database->getArrOfQuery('select ID_RUBRIC,rubric_name,rubric_textid
from cprice_rubric
where rubric_visible=1 && rubric_deleted=0 && rubric_type=2 && rubric_parent=0 order by rubric_pos,rubric_name', 'ID_RUBRIC');
                //if(empty($type))
                $j = 0;$n_beg = ceil($n_rubrics/7);
		foreach ( $rubrics_l1 as $rubric_l1_id => $rubric_l1_data )
		{
			$inner_items = '';
			if ( $rubrics_l2 = $database->getArrOfQuery('select ID_RUBRIC,rubric_name,rubric_textid
    from cprice_rubric 
    where rubric_visible=1 && rubric_deleted=0 && rubric_type=2 && rubric_parent='.$rubric_l1_id.' order by rubric_pos,rubric_name', 'ID_RUBRIC')
			)
			{
				$goods = '';
				$n = 0;
				$sql_str = '
					select temp.*, t2.goodfeature_value as nalichie from (
						select ID_GOOD,good_url,goodfeature_float as sort_feat,rubric_textid
							from cprice_rubric natural join cprice_rubric_goods natural join cprice_goods natural join cprice_goods_features
							where rubric_visible=1 && rubric_deleted=0 && good_visible=1 && good_deleted=0 && rubric_type=2 && rubric_parent='.$rubric_l1_id.' && ID_FEATURE='.FPRICE.
					') as temp inner join cprice_goods_features as t2 on temp.ID_GOOD=t2.ID_GOOD
							where t2.ID_FEATURE=370'
				;
				if($type>0)
				{			
					$sql_str = '
						select temp2.* from ('
							.$sql_str.
						') as temp2 inner join cprice_goods_features as t3 on temp2.ID_GOOD=t3.ID_GOOD
								where t3.ID_FEATURE=81 && t3.goodfeature_value='.$type.' group by temp2.ID_GOOD'
					;
				}
                                $num_goods_rubric = mysql_num_rows($database->query($sql_str));
				$sql_str .= ' order by nalichie DESC, sort_feat DESC limit '.($j<$n_beg?3:4);
				$res_goods = $database->query($sql_str);
				$data = $database->getArrOfQuery($sql_str, 'ID_GOOD');
				foreach ( $data as $good_id => $good_data )
				{
					$data_feats = getDataId($good_id,array(FNAME,FPRICE,370),true);
/*					$feats = '';
					$no_view = array(FNAME,FDESC,FPRICE,142,81,143,306,307,308,370,391);
					foreach($data_feats as $feat=>$val)
					{
						if(!in_array($feat, $no_view) && !empty($val))$feats .= '<tr><td>'.  getFeatText($feat).'</td><td>'.$val.'</td></tr>';
					}*/
					@$nalichie = (bool)$data_feats[370];
					if($nalichie) $button_add = '<a href="#" class="add-basket bottom_buy_cat" data-good="'.$good_id.'"></a>';
					else $button_add = '<a href="/predzakaz/?f56='.$good_id.'" class="bottom_order_cat btn-green dialog" data-good="'.$good_id.'">Предзаказ</a>';
					$good_name = $data_feats[FNAME];
					if ( !$good_image = getImages($good_id) )
					{
						$good_image = '/images/nophoto.png';
					}
					$good_price = $data_feats[FPRICE];
					if(empty($good_price))$good_price = '-';
					$new = '';
					if(isset($data_feats[306]) && $data_feats[306]>0) $new = '<img src="/skins/main/images/new.png" alt="новинка" class="hit_cat" />';
					if($good_data['nalichie']>0)$new = '<div class="nalichie">В наличии</div>';
					$goods .= '
										<div class="img1_catal">
											 <ul class="nav_good">
												  <li>
														<a href="'.teGetUrlQuery($good_data['rubric_textid'], $good_data['good_url']).'">'.$good_name.'</a>
												  </li>

											 </ul>
											 <a href="'.teGetUrlQuery($good_data['rubric_textid'], $good_data['good_url']).'"><img class="image" id="img'.$good_id.'" src="'.$good_image.'" alt="'.$good_name.'" /></a>
											 '.$new.'
											 <div class="price">'.$good_price.' руб.</div>'.$button_add.'
										</div>
					';
					$n++;
					if($n==3 && $j<$n_beg)break;
					if($n==4)break;					
				}
				if(!empty($goods))
				{                                        
					$content .= '<div'.($j<$n_beg?' class="goods2"':' class="goods"').'>'
							. '<h2><a href="'.teGetUrlQuery($rubric_l1_data['rubric_textid'],($type>0?'type='.$type:'')).'">'.$rubric_l1_data['rubric_name'].' ('.$num_goods_rubric.')</a></h2>'.
							$goods.
							'<div class="all_goods"><a href="'.teGetUrlQuery($rubric_l1_data['rubric_textid'],($type>0?'type='.$type:'')).'">Все товары по разделу: "'.$rubric_l1_data['rubric_name'].'"</a></div>'
						. '</div>'.
						($j<$n_beg?'':'<div class="clear"></div>');
					$j++;
				}				
			}
		}

	}

}


//print_r($_GET);
if(!empty($_GET['rubric_id']) &&empty($_GET['good_id'])){
   $content.='<div class="rr-widget"
     data-rr-widget-category-id="'.$rubric_id.'"
     data-rr-widget-id="539fd2e71e994424286fc7c7"
     data-rr-widget-width="100%"></div>'; 
}
echo $content;
