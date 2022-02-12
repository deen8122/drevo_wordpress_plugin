<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/**********
*  Заменяет во всех сайтах текст $_GET['q'] на соответствующую ссылку (урл настраивается в конце в поле input name='linkurl'
*
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/


	$q = strip_tags(@$_GET['q']);
	$tid = (int)@$_GET['tid'];
	
	// "до" и "после" во всплывающем окне для каждого найденного соответствия
	if($tid){	
		$hid = (int)@$_GET['hid'];
		if($hid) otherbase($hid);
		
		list($text) = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT = $tid");
		$text = nl2br($text);
		print "<table border=0 cellpadding=4>";
		print "<tr><th style='border-bottom:1px solid #666666;'>ДО</th><th style='border-bottom:1px solid #666666;'>После</th></tr>";
		print "<td style='border-right:1px solid #666666;'>".$text."</td>";
		print "<td>".eregi_replace("($q)","<a href='#' style='background-color:yellow;'>\\1</a>",$text)."</td>";
		print "</table>";
		die();
	}
	
	setTitle("Вставка ссылок по всем базам в «".$q."»");
	
	if( !empty($_POST['linkurl']) && !empty($_POST['t']) ){
		// сохраняем в базу, если подтверждено
		foreach($_POST['t'] AS $hid => $t){
			otherbase($hid);
			foreach($t AS $tid => $on){
				list($text) = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT = $tid");
				$text = eregi_replace("($q)","<a href='http://$_POST[linkurl]' class='auto'>\\1</a>",$text);
				$database->query("UPDATE ".DB_PREFIX."texts SET text_text='".str_replace("'","\'",$text)."' WHERE ID_TEXT=$tid");
			}
		}
		
	} else {
		print "<form method='post'>";
		$iitem=0;
		
		/// ищем соответствия
		// для каждой базы
		foreach($hosts AS $hid => $hinfo)if($hinfo['version']==2){
			otherbase($hid);
			// запрос на соотвествия
			$res = $database->query("
				SELECT r.rubric_name AS rn,f.feature_text,g.ID_GOOD,txt.ID_TEXT,SUBSTRING(txt.text_text,1,256),LENGTH(txt.text_text)
				FROM 
					".DB_PREFIX."texts AS txt 
						INNER JOIN ".DB_PREFIX."goods_features AS gf ON (txt.ID_TEXT=gf.goodfeature_value and goodfeature_visible=1)
						INNER JOIN ".DB_PREFIX."goods AS g ON (gf.ID_GOOD=g.ID_GOOD and g.good_deleted=0 and g.good_visible=1)
						INNER JOIN ".DB_PREFIX."features AS f ON (gf.ID_FEATURE=f.ID_FEATURE and f.feature_deleted=0 and f.feature_type=7 and f.feature_enable=1)
						INNER JOIN ".DB_PREFIX."rubric_goods AS rg ON (rg.ID_GOOD=g.ID_GOOD and rg.rubricgood_deleted=0)
						INNER JOIN ".DB_PREFIX."rubric AS r ON (r.ID_RUBRIC=rg.ID_RUBRIC and r.rubric_visible=1 and r.rubric_deleted=0)
				WHERE txt.text_text LIKE '%$q%'
				ORDER BY rn
			");
			if(mysql_num_rows($res)>0){
				
				print "<h2>База «".$hinfo['name']."»</h2>";
				print "<table class='list'>";
				$js = "";
				for($i=$iitem;$i<=mysql_num_rows($res)+$iitem-1;$i++) $js .= "document.getElementById('t$i').checked=";
				$js .= "this.checked";
				
				print "<tr><th style='width:1%'><input type='checkbox' checked onclick=\"$js\"/></th><th>Рубрикака</th><th>Характеристика</th><th>ID записи</th><th>Текст</th><th style='width:1%'>&hArr;</th><th style='width:1%'>&hellip;</th></tr>";
				
				
				// выводим соответствия
				$rname_l = $txt_l = $fname_l = "";
				while(list($rname,$fname,$gid,$tid,$txt,$txtlen) = mysql_fetch_array($res)){
					
					if($rname_l==$rname){
						$rname = "<div align=center>&uarr;</div>";
					} else {
						$rname_l=$rname;
					}
					if($fname_l==$fname){
						$fname = "&uarr";
					} else {
						$fname_l=$fname;
					}
					if($txt_l==$txt){
						$txt = "<div align=center>&uarr</div>";
					} else {
						$txt_l=$txt;
						$txt .= "&hellip;";
					}
					
					$txt = strip_tags($txt,"<b><i><u>");
					print "<tr><td><input type='checkbox' name='t[$hid][$tid]' id='t$iitem' checked/></td><td>$rname</td><td align=center>$fname</td><td>$gid</td><td>$txt</td><td>$txtlen</td><td><a href='".teGetUrlQuery("hid=$hid","tid=$tid","q=$q")."' onclick=\"return hs.htmlExpand(this,{objectType:'iframe'})\">...</a></td></tr>";
					$iitem++;
				}
				print "</table><br/>";
			}
		}
		
		
		print "<br/>";
		print "<div align='center'>Ссылаться на <input type='text' value='http://' disabled size='5' style='background:white;border:1px #C2C7CF solid;border-right:0px'/><input type='text' name='linkurl' value='' size='50' style='border:1px #C2C7CF solid;border-left:1px #dddddd solid'/></div>";
		print "<br/><br/>";
		print "<div align='center'><input type='submit' value='Вставить ссылки >>>'/></div>";
		print "</form>";
	}
?>