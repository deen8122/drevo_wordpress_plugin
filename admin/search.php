<?
// если есть поисковая строка
if(!empty($_GET['q'])){
	$q = strip_tags($_GET['q']);
	
	// символы, которые удалять из поисковой строки
	$drops = array(",",":",";","	","?");
	

	// удаление этих символов
	$qq = str_replace($drops," ",trim($q));

	//print $_GET['mode'];
	if (empty($_GET['mode']))
	$mode='and';
	else $mode=$_GET['mode'];
	

	// разделяем слова на SQL-параметры
	$qq = str_replace(" ","%' ".$mode." goodfeature_value LIKE '%",$qq);
	$qq = "goodfeature_value LIKE '%".$qq."%'";
	

	$suff = "";
	if(!empty($_GET['rubric_id'])){
		list($nr) = $database->getArrayOfQuery("SELECT rubric_name FROM cprice_rubric WHERE ID_RUBRIC=".(int)@$_GET['rubric_id']);
		$suff = " в рубрике «".$nr."»";
	} elseif(!empty($_GET['type'])) {
		list($nr) = $database->getArrayOfQuery("SELECT rubrictype_name FROM cprice_rubric_types WHERE ID_RUBRIC_TYPE=".(int)@$_GET['type']);
		$suff = " в разделе «".$nr."»";
	}
	setTitle("Поиск «".$q."»$suff");
	

	// функция для вывода централизованного результата поиска
	global $arrfind;
	$arrfind = array();
	$iii = 0;
	function addfindval($type,$id,$text,$in=1){
		global $database;
		global $arrfind;
		global $iii, $childs;
		$id = (int)$id;
		if($id==0) return false;
		if($iii>100) return false;
		
		//* type - тип записи:
		///* 1 - good
		///* 2 - rubric
		///* 3 - feature
		switch($type){
			case 1:
				$rubrics = array();
				$types = array();
				$res = $database->query("SELECT ID_RUBRIC,rubric_type FROM cprice_rubric NATURAL JOIN cprice_rubric_goods WHERE ID_GOOD='$id' and rubricgood_deleted=0");
				while($row = mysql_fetch_array($res))
				{
					$rubrics[] = $row[0];
					$types[] = $row[1];
					$rid = $row[0];
					$rtype = $row[1];
					
				}
				//list($rid,$rtype) = $database->getArrayOfQuery("SELECT ID_RUBRIC,rubric_type FROM cprice_rubric NATURAL JOIN cprice_rubric_goods WHERE ID_GOOD='$id' and rubricgood_deleted=0 LIMIT 1");
				// если нет рубрики у товара - выкидываем
				if(count($rubrics)==0) return false;
				// если не сответствует разделу, выкидываем
				if(!empty($_GET['type']) && !in_array(@$_GET['type'],$types)) return false;
				elseif(!empty($_GET['type'])) $rtype = $_GET['type'];
				// если не сответствует рубрике, выкидываем
				if(count($childs)>0)
				{
					$exist = false;
					foreach ($childs as $rubric) {
						if(in_array($rubric, $rubrics))
						{
							$exist = true;
							$rid = $rubric;
							break;
						}
					}
					if(!$exist) return false;
				}
				//if(!empty($_GET['rubric_id']) && !in_array(@$_GET['rubric_id'],$rubrics)) return false;
				//elseif(!empty($_GET['rubric_id'])) $rid = $_GET['rubric_id'];

				list($gname) = $database->getArrayOfQuery("
					SELECT goodfeature_value
					FROM cprice_goods_features NATURAL JOIN cprice_rubric_features NATURAL JOIN cprice_features
					WHERE ID_RUBRIC=$rid and ID_GOOD=$id and feature_type=2
					ORDER BY rubricfeature_pos
					LIMIT 1
				");
				if($_GET['q']==$id) $gname = "<b>".$gname."</b>";
				$link = teGetUrlQuery("=goods","from=s","action=view","type=$rtype","rubric_id=$rid","id=$id");
				list($deleted) = $database->getArrayOfQuery("SELECT good_deleted FROM cprice_goods WHERE ID_GOOD=$id");
			break;
			case 2:
				list($rtype) = $database->getArrayOfQuery("SELECT rubric_type FROM cprice_rubric WHERE ID_RUBRIC=$id LIMIT 1");
				// если не сответствует разделу, выкидываем
				if(!empty($_GET['type']) && @$_GET['type']!=$rtype) return false;

				$gname = $text;
				$text = getRubricName($id,true,true,0);
				if($_GET['q']==$id) $gname = "<b>".$gname."</b>";
				$link = teGetUrlQuery("=goods","from=s","type=$rtype","rubric_id=$id");
				list($deleted) = $database->getArrayOfQuery("SELECT rubric_deleted FROM cprice_rubric WHERE ID_RUBRIC=$id");
			break;
			case 3:
				list($rtype) = $database->getArrayOfQuery("SELECT rubric_type FROM cprice_rubric WHERE ID_RUBRIC=$id LIMIT 1");
				// если не сответствует разделу, выкидываем
				if(!empty($_GET['type']) && @$_GET['type']!=$rtype) return false;

				$gname = $text;
				$text = '';$br='';
				$res1 = $database->query("
					SELECT ID_FEATURE,feature_text
					FROM cprice_features natural join cprice_rubric_features natural join cprice_rubric
					WHERE ID_RUBRIC=$id and rubric_deleted=0
					LIMIT 500
				");
				while($row1 = mysql_fetch_array($res1))
				{
					$text .= $br.$row1[1];
					$br=', ';
				}
				$text = 'Характеристики: '.$text;
				if($_GET['q']==$id) $gname = "<b>".$gname."</b>";
				$link = teGetUrlQuery("=goods","from=s","type=$rtype","rubric_id=$id");
				list($deleted) = $database->getArrayOfQuery("SELECT rubric_deleted FROM cprice_rubric WHERE ID_RUBRIC=$id");
			break;
		}

		// запрос в переменную
		$q = strip_tags($_GET['q']);
		// символы, которые вырезаем из запроса
		$drops = array(" ",",",":",";","	","?");
		$q = trim(str_replace($drops," ",$q));
		// делим запрос по словам, и в массив
		$q = explode(" ",$q);
		$q1=array();$qq1=array();
		foreach($q AS $tqq){
				$tqq = mb_strtolower($tqq);
				$q1[] = $tqq;
				$qq1[] = "<b>".$tqq."</b>";
				$tqq = mb_strtoupper(substr($tqq,0,1)).substr($tqq,1,strlen($tqq));
				$q1[] = $tqq;
				$qq1[] = "<b>".$tqq."</b>";
		}
		// заголовок и описание
		$gname = trim(str_replace($q1,$qq1,$gname));
		$text = trim(str_replace($q1,$qq1,$text));


		$rgn = (!empty($rid))?getRubricName($rid,true,true,0):"";

		// если заголовок пуст, то не вносим в список найденных запией
		if(empty($gname)) return false;

		// добавляем в массив найденных записей
		$arrfind[] = array($type,$id,$link,$gname,$text,$rgn,$deleted,$in);
		$iii++;
	}

	// если в строке поиска был введен айди, то выводим его запись
	if(is_numeric($q)){
		if(list($id) = $database->getArrayOfQuery("SELECT ID_GOOD FROM cprice_goods WHERE ID_GOOD=$q and good_deleted=0")){
			addfindval(1,$id,getFeatureText($id,0,true),1);
		}
	}

	$i=0;

	// ищем в именах рубрик
	$res = $database->query("
		SELECT ID_RUBRIC,rubric_name
		FROM cprice_rubric
		WHERE (".str_replace("goodfeature_value","rubric_name",$qq).") and rubric_deleted=0
		LIMIT 500
	");
	while(list($id,$name) = mysql_fetch_array($res)){
		addfindval(2,$id,"<i>Рубрика </i>«".$name."»",1);
	}

	// ищем в именах характеристик рубрик
	$res = $database->query("
		SELECT ID_RUBRIC,rubric_name
		FROM cprice_rubric natural join cprice_rubric_features natural join cprice_features
		WHERE (".str_replace("goodfeature_value","feature_text",$qq).") and rubric_deleted=0
		LIMIT 500
	");
	while(list($id,$name) = mysql_fetch_array($res)){
		addfindval(3,$id,"<i>Характеристики рубрики </i>«".$name."»",1);
	}

	// ищем в данных характеристик
	$res = $database->query("
		SELECT ID_GOOD
		FROM cprice_goods_features
		WHERE $qq
		LIMIT 500
	");	
	function rec_childs($rubric_id)
	{
		global $childs, $database;
		if($rubric_id>0)
		{
			$childs[] = $rubric_id;
			$res = $database->query("SELECT ID_RUBRIC FROM cprice_rubric WHERE rubric_parent=".$rubric_id);
			while ($row = mysql_fetch_array($res)) {
				rec_childs($row[0]);
			}
		}
	}
	$childs = array();
	if(!empty($_GET['rubric_id']))
	{
		rec_childs(intval($_GET['rubric_id']));
	}
	while(list($id) = mysql_fetch_array($res)){
		addfindval(1,$id,getFeatureText($id,0,true),2);
	}
/*
	// ищем в больших текстах
	$res = $database->query("
		SELECT ID_GOOD
		FROM cprice_goods_features natural join cprice_features inner join cprice_texts on ID_TEXT=goodfeature_value
		WHERE (".str_replace("goodfeature_value",'text_text',$qq).") && feature_type=7
		LIMIT 500
	");
	while(list($id) = mysql_fetch_array($res)){
		addfindval(1,$id,getFeatureText($id,0,true),2);
	}
*/
	// ищем в SEO
	$s = "";
	$seoarray = array("metadata_head_title","metadata_meta_title","metadata_meta_keywords","metadata_meta_description","metadata_body_h1","metadata_body_h2","metadata_body_description","metadata_body_keywords");
	foreach($seoarray AS $seo){
		$s .= str_replace("goodfeature_value",$seo,$qq)." or ";
	}
	$res = $database->query("
		SELECT metadata_page,metadata_id
		FROM cprice_metadata
		WHERE $s false
		LIMIT 500
	");
	while(list($page,$id) = mysql_fetch_array($res)){
		if($id) addfindval((($page==2)?2:1),$id,getFeatureText($id,0,true),3);
	}



	teAddJSScript("$(document).ready(function() {
					$('a#sch').click(function(){
						var word='".$q."';
						document.getElementById('iq').value = word;
						document.getElementById('mode').value = 'or';
						document.getElementById('srchform').submit();
				});
	});");
	
	

	//вывод поля для выбора метода поиска: слово целиком или каждое слово по отдельности
	print "Если Вас не устраивают результаты поиска, попробуйте 
	<a href='#' id='sch'> искать вхождение одного из слов словосочетания </a>";
	
	
	
	$bedeleted = 0;
	// вывод результатов поиска из массива, который подготовила функция addfindval
	print "<ol id='search'>";
	$links = array();
	foreach($arrfind AS $url){	  if(!in_array($url[2],$links))
	  {
	  	$links[]=$url[2];
		$deleted = $url[6];
		if( !@$deleted || !empty($_GET['show']) ){
			print "<li>";
			print "<h3><a href='$url[2]'".(($deleted)?" style='color:red'":"").">$url[3]</a> <sup>".(($url[7]==3)?"SEO, ":"").(($deleted)?"удалённая запись":"$url[1] ".buttonEdit("$url[2]&action=edit","Управление")." <a href='$url[2]&action=photos&good_id=$url[1]&id='><img src='$skinpath/images/b_camera.gif' alt='фото' title='управление фото'></a>")."</sup></h3>";
			if(!$deleted) print "<p>Рубрика: $url[5]</p>";
			if(!$deleted) print "<cite>$url[4]</cite>";
			if(!$deleted) print "<div></div>";
			print "</li>";
			$i++;
		}
		if($deleted) $bedeleted++;
	  }
	}
	print "</ol>";

	if(empty($_GET['show'])&&$bedeleted) addSubMenu("?".$_SERVER['QUERY_STRING']."&show=deleted", "<img src='{$skinpath}images/b_delete_big.png'/> Показывать удалённые записи ($bedeleted)", "submenustd");
	if(@$_GET['show']=="deleted") addSubMenu("?".$_SERVER['QUERY_STRING']."&show=", "<img src='{$skinpath}images/b_delete_big.png'/> Скрыть удалённые записи", "submenustd");

	if(empty($arrfind)){
		print "<div class='error'>Извините, по запросу «<u>".$q."</u>» ничего не найдено.</div>";
	}

} else {
	print "<div class='error'>Введите слово или ID для поиска...</div>";
}
?>