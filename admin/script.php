<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798
//define("DEST_DIR",'Z:\\home\\test1.ru\\www\\files');
// папка временных файлов /cprice/2/temp/
define('TEMPDIR','temp/');
@$op1 = $_GET['op1'];
@$run = (int)$_GET['run'];
//print mktime(1, 2, 3, 1, 1, 2012);
if(empty($op1))
{

print '<a name="top"></a>';
print '<br/><br/>';
print '<ul>';
print '<li><a href="'.teGetUrlQuery("op1=mail").'">тест отправки письма</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=insert_rubrics").'">Добавление множества рубрик</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=opisanie_csv").'">Загрузка описаний товаров Промесо</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=ph_to_base_promeso").'">Внедрение фото товаров Промесо</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=cre").'">Загрузка товаров Creazioni</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=no_price_and_op").'">Товары без цены и описания (промесо)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=bez_foto").'">Товары без фото (промесо)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=copy_kat").'">Перенос каталога из другого проекта (бпхолд.ру)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=from_exc").'">Выгрузка каталога из Ексел-файла (мопед02.ру)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=del_id").'">Удаление записей по ИД</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=pars").'">Парсинг файла ексел на существительные</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=testimg").'">Тестирование работы водного знака</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=ubemail").'">Формирование базы емайлов для уфабанкета</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=prices").'">Цены и названия в капитале </a></li>';
print '<li><a href="'.teGetUrlQuery("op1=big").'">Большие текста</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=seo").'">СЕО-параметры</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=strong").'">Убрать теги strong и b в текстах и сео-текстах</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=sites").'">Доступность сайтов</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=op").'">Выполнить особый скрипт</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=photo").'">Переместить фотоальбом</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=sendm").'">Создание базы рассылки(Использовать в базе уфабанкета)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=avtor").'">История авторизаций непользователей Древо</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=icq").'">Статус пользователя по номеру</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=rtxt").'">Приведение url-а рубрики в нормальный вид</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=form").'">Тестирование формы</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=drev").'">Калькулятор Древпродукта</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=frchng").'">Date from changes in new feature</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=trumb").'">Приведение ранее загруженных уменьшенных фоток к новому еще меньшему размеру</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=act").'">Создание даты для актов</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=hosts").'">Hosts in database</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=emails").'">Сменить емайлы в важных событиях</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=urls").'">Сделать урлы в записях</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=vs_mails").'">Email-ы по важным событиям</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=vs_new").'">Заполнение новой таблицы по важным событиям</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=from_file").'">Ввод данных из файла</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=rub_from_file").'">Ввод рубрикатора из файла</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=wtmrk").'">Наложение водного знака, создание уменьшенных фоток</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=wtmrk_folder").'">Наложение водного знака на картинки в папке</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=ph_to_base").'">Внедрение фото в Древо (creazioniitalia.ru)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=ph_del").'">Удаление повторяющих записей фото в Древо (creazioniitalia.ru)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=id_no_ph").'">ID без фото (creazioniitalia.ru)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=file_1c").'">Анализ файла 1С</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=eko_kons").'">eko-rb.ru косультации из старого</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=eko_kons_date").'">eko-rb.ru Даты в косультации из старого</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=spec").'">Убрать спец-символы в урле товаров</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=eko_news").'">eko-rb.ru новости из старого</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=slesh").'">Убрать в текстах слеш перед кавычкам</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=sort").'">Обратная сортировка записей</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=num_goods").'">Количество товаров</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=cat_to_exl").'">Формирование каталога в Excel</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=cat_to_exl2").'">Формирование каталога в Excel (Тепломаркет)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=upd_feat").'">Измениние характеристики в рубрике</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=photo_error").'">Исправление в фотках</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=add_id_1c").'">Добавление ИД 1С (тепломаркет)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=price_from_1c").'">Обновление цен и наличия из 1С (тепломаркет)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=update_data").'">Обновление данных из файла</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=unix_date").'">Дата в юниксе</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=zaprosy").'">Запросы из файла</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=zaprosy2").'">Запросы из файла 2</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=add_vals2").'">Добавить значения, если они пустые</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=upd_org").'">Изменение организации в текстах</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=upd_rub").'">Изменение рубрик </a></li>';
print '<li><a href="'.teGetUrlQuery("op1=mail").'">Тест отправки письма на ящик </a></li>';
print '<li><a href="'.teGetUrlQuery("op1=del-goods").'">Удаление записей в удаленных рубриках </a></li>';
print '<li><a href="'.teGetUrlQuery("op1=del-files").'">Удаление файлов в удаленных записях </a></li>';
print '<li><a href="'.teGetUrlQuery("op1=export_to_txt").'">Выгрузка записей товаров для 1С (koleso02.ru only)</a></li>';
print '<li><a href="'.teGetUrlQuery("op1=setup_models").'">Установка множ. характеристики в каталоге (4mmc.ru only)</a></li>';
print '</ul>';
}
else addSubMenu(teGetUrlQuery(),'Вернуться');
switch($op1)
{
    case 'recover':
        $n = 0;
        $res = $database->query("select change_row, old_values from cprice_changes where change_type=3 && change_table='cprice_goods_features' && change_dt>'2016-11-01 18:11:00' && change_dt<'2016-11-01 18:12:00'");
        while ($row = mysql_fetch_array($res)) {
            $arr = explode('$|$', $row[1]);
            if(count($arr)>4)
            {
                echo $row[0].' '.$arr[0].' '.$arr[1].' '.$arr[2].' '.$arr[4].'<br/>';
                if($run)
                {
                    $database->query("insert into cprice_goods_features (ID_GOOD_FEATURE, ID_GOOD, ID_FEATURE, goodfeature_value, goodfeature_float) VALUES ('$row[0]', '$arr[0]', '$arr[1]', '$arr[2]', '$arr[4]' )",false);
                }
                $n++;
            }
        }
        echo 'ok'.$n;
        break;
	case 'upd_phones':
		$data = getData(259, "", "", array(343), true);
		$i = 0;
		foreach ($data as $gid=>$vals)
		{
			if(!empty($vals[343]))
			{
				$login = str_replace(array("+7",'+7'), "8", $vals[343]);
				$login = str_replace(array(" ","-","_","(",")"), "", $login);
				setFeatData2(343, $gid, $login, true);
				$i++;
			}
		}
		echo 'ok '.$i;
		
	break;
	case 'upd_prices':/*micronopt*/
	{
		$i = 0;
		$new_goods = $database->query("select ID_GOOD, `price_00002`, `price_00003`, `price_00005`, `date_create` from cprice_goods WHERE price_00002>0");
		otherbase(18);
		while ( $row = mysql_fetch_array($new_goods) )
		{
			$database->query("update cprice_goods set `price_00002`=$row[1], `price_00003`=$row[2], `price_00005`=$row[3], `date_create`='$row[4]' where ID_GOOD=".$row[0]." && price_00002=0");
			$i++;
		}
		echo 'ok'.$i;
	}	
	break;
        //Удаление емайла из важных событий
        case 'del_email':
        {
            foreach($hosts as $id => $host){
                if(isset($host['db_host'])){
                       $database -> teDatabase($host['db_host'], $host['db_user'], $host['db_pass'], $host['db_name']);

                       $res = $database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name like 'notify_%'");
                       $i=0;
                       $events = '';
                       while($row=mysql_fetch_array($res))
                       {
                               $val = $row['var_value'];
                               $arr1 = explode("|",$val);
                               if(!empty($arr1[1]))
                               {
                                   $emails = explode(",", $arr1[1]);
                                   $emails_new = array();
                                   foreach ($emails as $email) {
                                       $email = trim($email);
                                       if($email!='ufa915000@yandex.ru')$emails_new[] = $email;
                                   }
                                   $arr1[1] = implode(",", $emails_new);
                                   $new_value = implode("|",$arr1);
                                   echo $host['db_name'].' / '.$row['var_name'].': '.$new_value.'<br/>';
                                   if($run>0)$database->query("update ".DB_PREFIX."configtable set var_value='".$new_value."' where var_name='".$row['var_name']."'");
                               }
                       }
                }
            }
            echo 'ok';
        }                       
        break;        
	//Удаление повторяющихся записей в рубриках
	case 'del_mults':
	{
		$new_goods = $database->query("select ID_GOOD from (SELECT ID_GOOD, count(ID_GOOD) as cnt FROM cprice_rubric_goods group by ID_GOOD) t0 WHERE cnt>1");
		while ( list($good_id) = mysql_fetch_array($new_goods) )
		{
			$rub1 = $rub2 = $del_id = 0;
			$res2 = $database->query("select ID_RUBRIC,ID_RUBRIC_GOOD from cprice_rubric_goods WHERE ID_GOOD=".$good_id);
			while ($row = mysql_fetch_array($res2))
			{
				if(empty($rub1))$rub1 = $row[0];
				else 
				{
					$rub2 = $row[0];
					$del_id = $row[1];
				}
				if( $rub1 == $rub2 && $rub1!=0 && $del_id>0 )
				{
					echo $good_id.' '.$rub1.' '.$rub2.'</br>';
					if($run>0) $database->query("DELETE FROM cprice_rubric_goods WHERE ID_RUBRIC_GOOD=".$del_id,FALSE);
				}
			}
		}
		echo '</br>ok';
	}
	break;
	//Удаление повторяющихся значений записей
	case 'del_mult_vals':
	{
		$res_feats = $database->query("select ID_FEATURE from cprice_features WHERE feature_multiple=0");
		while ( list($feat) = mysql_fetch_array($res_feats) )
		{
			$new_goods = $database->query("select ID_GOOD from (SELECT ID_GOOD, count(ID_GOOD) as cnt FROM cprice_goods_features where ID_FEATURE=$feat group by ID_GOOD) t0 WHERE cnt>1");
			while ( list($good_id) = mysql_fetch_array($new_goods) )
			{
				$rub1 = $rub2 = $del_id = 0;
				$res2 = $database->query("select ID_GOOD_FEATURE from cprice_goods_features WHERE ID_FEATURE=$feat && ID_GOOD=".$good_id);
				while ($row = mysql_fetch_array($res2))
				{
					if(empty($rub1))$rub1 = $row[0];
					else 
					{
						echo $good_id.' '.$rub1.' '.$rub2.'</br>';
						if($run>0) $database->query("DELETE FROM cprice_goods_features WHERE ID_GOOD_FEATURE=".$row[0],FALSE);
					}
				}
			}
		}
		echo '</br>ok';
	}
	break;
	case 'latina':
		$new_goods = $database->query("SELECT ID_GOOD, good_visible, good_deleted FROM ".DB_PREFIX."goods_features natural join cprice_goods WHERE ID_FEATURE = 102 && goodfeature_value='1'");
		$goods = array();
		$goods2 = array();
		while ( list($new_id,$vis,$del) = mysql_fetch_array($new_goods) )
		{
			if($vis == 0 || $del == 1)
			{
				$goods[] = $new_id;
			}
			else {
				list($chng_date) = $database->getArrayOfQuery("select change_dt from cprice_changes where change_type=1 && change_table='cprice_goods' && change_row='$new_id' && change_dt<'".date("Y-m-d H:i:s",  mktime(0, 0, 0, date("m")-1, date("d"), date("Y")))."'");
				if(!empty($chng_date))
				{
					$goods[] = $new_id;
				}
			}
		}
		print_r($goods);
		if($run) $database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='0' WHERE ID_FEATURE = 102 && ID_GOOD IN (".implode(",", $goods).")");
		echo 'ok';
	break;
	case 'upd_tasks':
		combase();
		$res = $database->query("SELECT id, text_task 
			FROM  `cprice_tasks` 
			WHERE  `text_task` LIKE  '%http://cprice2.ddmitriev.ru%'
			AND  `done` =0
			AND  `deleted` =0
			AND id_uwork=25");
		$i = 0;
		while($row = mysql_fetch_array($res))
		{
			echo $row[1].'<br/><br/>';
			$i++;
			if($run)
			{
				$database->query("UPDATE cprice_tasks set text_task='".mysql_escape_string(str_replace("cprice2.ddmitriev.ru","cprice.ddmitriev.ru",$row[1]))."' where id=".$row[0],false);
			}
		}
		echo 'ok'.$i;
	break;
	case 'export_img':
		$i = 0;
		$error_ftp = false;
		if($run){
			$ftp_server = "irbis-shop.com";
			$conn_id = ftp_connect($ftp_server) or die("Couldn't connect to $ftp_server");
			if (!ftp_login($conn_id, "tibibo_ufapr", "111210irbis")) {				
				echo "Couldn't connect ftp\n";
				$error_ftp = true;
			}
			
		}
		$res = $database->query("select ID_RUBRIC from cprice_rubric where rubric_type=2 && rubric_visible=1 && rubric_deleted=0");
		while($row = mysql_fetch_array($res))
		{
			$data = getData($row[0], "", "", array(30), true);
			foreach ($data as $gid => $vals) {
				$res2 = $database->query("
					SELECT `goodphoto_file`
					FROM ".DB_PREFIX."goods_photos
					WHERE ID_GOOD = ".$gid." and goodphoto_visible=1 and goodphoto_deleted=0
					ORDER BY goodphoto_pos
					LIMIT 100
				");
				while ( $line = mysql_fetch_array($res2) )
				{
					if(file_exists($hosts[DB_ID]['folder'].'images/good_photo/'.$line[0])){
						echo $gid.' '.$vals[30].$line[0].'';
						$i++;
						if($run && !$error_ftp){
							ftp_put($conn_id, '/bitrix/public_html/bitrix/catalog_export/images/'.$line[0], $hosts[DB_ID]['folder'].'images/good_photo/'.$line[0], FTP_ASCII);
							ftp_put($conn_id, '/bitrix/public_html/bitrix/catalog_export/images/image_'.$line[0], $hosts[DB_ID]['folder'].'images/good_photo/image_'.$line[0], FTP_ASCII);
						}
					}
					echo '<br/>';
				}
			}
		}
		if($run)ftp_close($conn_id);
		echo 'ok '.$i;
	break;
	case 'test_ip':
function getip($GetRIP)
  {
    $data = "<ipquery><fields><all/></fields><ip-list><ip>".$GetRIP."</ip></ip-list></ipquery>";
    
    $ch = curl_init();
    
    curl_setopt($ch, CURLOPT_URL, "http://194.85.91.253:8090/geo/geo.html");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    
    $xml = curl_exec($ch);
    curl_close($ch);
    
    //echo $xml;
    
    $messge="!<message>(.*?)</message>!si";
    
    preg_match($messge, $xml, $main_ar["message"]);
    print_r($xml);
    if(@$main_ar["message"][1]!="Not found")
    {
      $district="!<district>(.*?)</district>!si";
      $region="!<region>(.*?)</region>!si";
      $town="!<city>(.*?)</city>!si";
      
      preg_match($district, $xml, $main_ar["district"]);
      preg_match($region, $xml, $main_ar["region"]);
      preg_match($town, $xml, $main_ar["city"]);
      
      $ArMain=array("FIND"=>1,"DISTRICT"=>$main_ar["district"][1], "REGION"=>$main_ar["region"][1],"TOWN"=>$main_ar["city"][1]);
      return $ArMain;    
    }
    else return array("FIND"=>0);    
  }
	print_r(getip('195.93.180.172'));
	print_r(getip('92.50.163.138'));
	print_r(getip('92.50163138'));
	break;
	case 'update_num_goods':
		$res = $database->query("SELECT ID_GOOD, good_nal FROM cprice_goods natural join cprice_goods_features where goodfeature_value=0 && ID_FEATURE=193 && good_deleted=0 && good_nal>0");
		$i = 0;
		while ($row = mysql_fetch_array($res)) {
			echo $row[0].' ';
			if($run>0)
			{
				$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$row[1]."' WHERE ID_GOOD='".$row[0]."' && ID_FEATURE=193", false);
			}
			$i++;
		}
		echo '<br/>ok '.$i;
	break;
	case 'del_good_feat':
		$data = getData(1000000000,'','',array(338),true,array(348=>'123456'));
		$i = 0;
		foreach ($data as $gid => $vals) {
			echo $vals[338].'<br/>';
			$i++;
			if($run>0)
			{
				$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$gid,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$gid,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$gid,false);				
			}
		}
		echo 'ok'.$i;
	break;
	case 'upd_banks':
		$data = getData(215,'','',array(687,688,689));
		$i = 0;
		foreach ($data as $gid => $vals) {
			if( substr($vals[687], 0, 2) == '40' )
			{
				echo $vals[687].' / '.$vals[688].' / '.$vals[689].'<br/>';
				$i ++ ;
				if($run>0)
				{
					$data2 = array(687=>$vals[689],688=>$vals[687],689=>$vals[688]);
					updateData($gid, $data2);
				}
			}
		}
		echo 'ok'.$i;
	break;
	case 'del-fgoods':
		$res = $database->query("SELECT ID_GOOD FROM cprice_goods_features group by ID_GOOD");
		$i = 0;
		while ($row = mysql_fetch_array($res)) {
			echo $row[0].' ';
			list($exist) = $database->getArrayOfQuery("select count(ID_GOOD) from  cprice_goods where ID_GOOD=".$row[0]);
			if(empty($exist))
			{
				$i++;
				echo $row[0].' / ';
				if($run>0)
				{
					$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$row[0],false);
				}
			}
		}
		echo '<br/>ok '.$i;
	break;
	case 'del-pust':
	{
		$data = getData(18, '', "", array(152), true);
		$emails = array();
		foreach ($data as $gid => $vals) {
			if(empty($vals[152]))
			{
				$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$gid,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$gid,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$gid,false);				
			}
			else
			{
				if(in_array($vals[152], $emails))
				{
					$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$gid,false);
					$database -> query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$gid,false);
					$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$gid,false);					
				}else $emails[] = $vals[152];
			}
		}
		echo 'ok'.count($data);
	}
	break;
	case 'pdf':
	{
		$s1 = ob_get_contents();
		ob_end_clean();
		unset($s1);

		teInclude("tcpdf");

		// создаем объект TCPDF - документ с размерами формата A4
		// ориентация - книжная
		// единицы измерения - миллиметры
		// кодировка - UTF-8
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

		// убираем на всякий случай шапку и футер документа
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false); 

		$pdf->SetMargins(20, 25, 25); // устанавливаем отступы (20 мм - слева, 25 мм - сверху, 25 мм - справа)

		$pdf->AddPage(); // создаем первую страницу, на которой будет содержимое

		$pdf->SetXY(90, 10);           // устанавливаем координаты вывода текста в рамке:
					       // 90 мм - отступ от левого края бумаги, 10 мм - от верхнего

		$pdf->SetDrawColor(0, 0, 200); // устанавливаем цвет рамки (синий)
		$pdf->SetTextColor(0, 200, 0); // устанавливаем цвет текста (зеленый)

		$pdf->SetFont('arial', '', 9); // устанавливаем имя шрифта и его размер (9 пунктов)
		$pdf->Cell(30, 6, iconv("Windows-1251", "UTF-8", 'Привет, Мир!'), 1, 1, 'C'); // выводим ячейку с надписью шириной 30 мм и высотой 6 мм. Строка отцентрирована относительно границ ячейки
		
		$pdf->SetFont('arial', '', 12); // устанавливаем имя шрифта и его размер (9 пунктов)
		$tbl = <<<EOD
		<table cellspacing="0" cellpadding="1" border="1">
		    <tr>
			<td rowspan="3">Колонка 1 - Строка 1<br />COLSPAN 3</td>
			<td>COL 2 - ROW 1</td>
			<td>COL 3 - ROW 1</td>
		    </tr>
		    <tr>
			<td rowspan="2">COL 2 - ROW 2 - COLSPAN 2<br />text line<br />text line<br />text line<br />text line</td>
			<td>COL 3 - ROW 2</td>
		    </tr>
		    <tr>
		       <td>COL 3 - ROW 3</td>
		    </tr>

		</table>
EOD;

		$pdf->writeHTML(iconv("Windows-1251", "UTF-8", $tbl), true, false, false, false, '');
		$pdf->Output('doc.pdf', 'I'); // выводим документ в браузер, заставляя его включить плагин для отображения PDF (если имеется)
	}
	break;
	case 'insert_rubrics'://Добавление множества рубрик
	{
		print '<div align="center">';
		print '<h2>Добавление множества рубрик</h2>';
		if(isset($_GET['msg'])) print '<div class="ok">Рубрики добавлены</div>';
		$frm = new teForm("form1","post");
		$frm->addf_selectGroup("type", "Раздел:");
		$frm->addf_selectGroup("rubric", "Рубрика:");
		$r = $database->query("select ID_RUBRIC_TYPE,rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0");
		while($row2 = mysql_fetch_array($r))
		{
			$frm->addf_selectItem("type", $row2[0], $row2[1]);
			$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_type=".$row2[0]);
			while($row = mysql_fetch_array($res))
				$frm->addf_selectItem("rubric", $row[0], $row2[1].' &gt;&gt; '.$row[1]);
		}
		$frm->addf_text("rubrics", 'Рубрики','',true);
		$frm->addf_checkbox("simple", "Простой список");
		$frm->setf_require("rubrics");
		if(!$frm->send())
		{
			$type = (int)$frm->get_value('type');
			$rubric = (int)$frm->get_value('rubric');
			if( $type>0 || $rubric>0 )
			{
				$first_pos = 10;
				if($rubric>0)
				{
					list($type) = $database->getArrayOfQuery("select rubric_type from cprice_rubric where ID_RUBRIC=".$rubric);
					list($pos) = $database->getArrayOfQuery("select max(rubric_pos) from cprice_rubric where rubric_parent=".$rubric);
					if($pos>0) $first_pos = $pos + 10;
				}
				$rubrics = explode("\r\n", $frm->get_value('rubrics'));
				print_r($rubrics);
				print '<br/>';
				$first = true;
				$rubric_arr = array();
				$rubric_pos = array();
				foreach ($rubrics as $item)
				{
					$arr = explode("\t", $item);
					if(count($arr)==2 || $frm->get_value_checkbox("simple"))
					{
						if($frm->get_value_checkbox("simple")) $arr = array('1.',$item);
						if($first)
						{
							$n_beg = strlen($arr[0])-2;
							$rubric_arr[substr($arr[0],0,-2)] = $rubric;
							$first = false;
							$rubric_pos[$rubric] = $first_pos;
						}
						$parent = $rubric_arr[substr($arr[0],0,-2)];
						$textid = translit(filename($arr[1]));
						$textid = mb_strtolower( $textid );
						$url = preg_replace( "/[^a-z0-9-_]/i", "", $textid );
						$url2 = "";
						$old = "";
						for ( $i = 0; $i < strlen( $url ); $i++ )
						{
							if ( $url[ $i ] == '-' )
							{
								if ( $old != $url[ $i ] && $i > 0 )
								{
									$url2 .= $url[ $i ];
									$old = '-';
								}
								if ( $i == 0 )
									$old = '-';
							}
							else
							{
								$old = '';
								$url2 .= $url[ $i ];
							}

						}
						if ( substr( $url2, -1 ) == '-' )
							$url2 = substr( $url2, 0, -1 );
						$textid = $url2;
						mysql_query("INSERT INTO cprice_rubric "
							. "(rubric_textid,rubric_parent,rubric_name,rubric_unit_prefixname,rubric_ex,rubric_visible,rubric_pos,rubric_type) values "
							. "('".$textid."','".$parent."','".$arr[1]."','','','1','".$rubric_pos[$parent]."', $type)");
						$rubric_new = mysql_insert_id();
						
						//$rubric_new = 899;
						$rubric_arr[$arr[0]] = $rubric_new;
						$rubric_pos[$rubric_new] = 10;
						$rubric_pos[$parent] += 10;
						print $arr[0].'/'.$arr[1].'/'.$parent.'/'.$rubric_arr[$arr[0]].'/<br/>';
					}
				}
				teRedirect(teGetUrlQuery("op1=insert_rubrics",'msg=ok'));
			}  else {
				$frm->errorValue("type", "Необходимо указать рубрику или раздел");
				$frm->send();
			}
			
			
		}
	}
	break;
	case 'update_nums':
		$res = $database->query("select ID_GOOD_FEATURE,goodfeature_value from cprice_goods_features where ID_FEATURE=391");
		$i = 0;
		while ($row = mysql_fetch_array($res)) {
			$num = $row[1];
			if(substr($num,-2)=='.0')
			{
				$num = substr ($num, 0, -2);
				$num = intval($num);
				$database->query("update cprice_goods_features set goodfeature_value='$num' where ID_GOOD_FEATURE=".$row[0]);
				$i++;
			}
		}
		echo 'ok '.$i;
	break;
	case 'test_robokassa':
		// your registration data
$mrh_login = "Elizaveta";
$mrh_pass1 = "HsNS3umXqZPFTyYT";

// order properties
$inv_id    = 5;        // shop's invoice number 
                       // (unique for shop's lifetime)
$inv_desc  = "desc";   // invoice desc
$out_summ  = "5.12";   // invoice summ

// build CRC value
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

// build URL
$url = "https://auth.robokassa.ru/Merchant/Index.aspx?MrchLogin=$mrh_login&".
    "OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";

$url2 = "http://test.robokassa.ru/Index.aspx?MrchLogin=$mrh_login&OutSum=$out_summ&InvId=$inv_id&Desc=$inv_desc&SignatureValue=$crc";
// print URL if you need
echo "<a href='$url' target='_blank'>Payment link</a> <a href='$url2' target='_blank'>Payment link2</a>";
	break;
	case 'send_sms':
	{
		$fp = fsockopen("92.50.156.138", 26676, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
	
    $message = 'Привет мир. Дамир тестирует. Test';
    $phone = '79173464687';
	
    $data = 'user=Micron&pass=87654321&action=post_sms&message=' . urlencode($message) . '&target= ' . urlencode($phone) . '&sender=NULL' .  "\r\n";
    $out = "POST /smw/aisms HTTP/1.0\r\n";

    $out .= "Host: 92.50.156.138\r\n";
    $out .= "Content-type: application/x-www-form-urlencoded; charset=windows-1251\r\n";
    $out .= "Content-length: ".strlen($data)."\r\n";
    $out .= "Connection: Close\r\n\r\n";
    $out .= $data."\r\n\r\n";
	
    $html = '';
	
    fwrite($fp, $out);
    while (!feof($fp)) {
        $html .= fgets($fp, 128);
    }
    fclose($fp);
	
    $pos = strpos($html, "\r\n\r\n");
    echo $html = substr($html, $pos+4);
    echo 'OK';
}
	}
	break;
	case 'creat_contr':
$body = <<<TXT
<?xml version="1.0" encoding="UTF-8"?>
                
<company companyType="FILI" discount="0.0" autoDiscount="0.0" discountCorrection="0.0" archived="false" name="Тестов Иван2" >
<requisite actualAddress="г.Уфа ул. Ленина 2"/>
<contact address="" phones="+2949594" faxes="" mobiles="" email="coder@ufapr.ru"/>
<tags>
<tag>клиенты интернет-магазинов</tag>
<tag>www.irbis-shop.ru</tag>
</tags>
</company>                
TXT;
$body = iconv("Windows-1251", "UTF-8", $body);  
/*
$sock = fsockopen("ssl://online.moysklad.ru", 443, $errno, $errstr, 30);
 
if (!$sock) die("$errstr ($errno)\n");
 
fputs($sock, "PUT /exchange/rest/ms/xml/Agent HTTP/1.1\r\n");
fputs($sock, "Host: online.moysklad.ru\r\n");
fputs($sock, "Authorization: Basic " . base64_encode("admin@irbis:111210irbis") . "\r\n");
fputs($sock, "Content-Type: application/xml \r\n");
*///fputs($sock, "Accept: */*\r\n");
/*fputs($sock, "Content-Length: ".strlen($body)."\r\n");
fputs($sock, "Connection: close\r\n\r\n");
fputs($sock, "$body");
 
while ($str = trim(fgets($sock, 4096)));
 
$body = "";
 
while (!feof($sock))
    $body.= fgets($sock, 4096);
 
fclose($sock);
$xml = simplexml_load_string($body);
$uid = $xml->accountId;
$uname = iconv("UTF-8", "Windows-1251", $xml['name']);
        echo 'User: '.$uid.' '.$uname;
*/	
$uid = 'b270d2f2-a525-11e3-b307-002590a28eca';
$gid = 'd2778a1d-d89d-11e2-8b23-7054d21a8d1e';
//$gid = 'zq6HekXohRObHC88GsTFI0';
$body = <<<TXT
<?xml version="1.0" encoding="UTF-8"?>
<customerOrder vatIncluded="true" applicable="true" payerVat="true" sourceAgentUuid="$uid" targetAgentUuid="604081f6-fd35-4592-ad07-31fc6da5d8b6" stateUuid="69123b55-c182-47a3-8e22-0d24f9a63bd0">
	<sum sum="318000.0" sumInCurrency="318000.0"/>
	<customerOrderPosition vat="18" goodUuid="$gid" quantity="2.0" discount="0.0">
		<basePrice sumInCurrency="159000.0" sum="159000.0"/>
		<price sumInCurrency="159000.0" sum="159000.0"/>
		<reserve>0.0</reserve>
	</customerOrderPosition>
</customerOrder>                
TXT;
//$body = iconv("Windows-1251", "UTF-8", $body);
$sock = fsockopen("ssl://online.moysklad.ru", 443, $errno, $errstr, 30);
 
if (!$sock) die("$errstr ($errno)\n");
 
fputs($sock, "PUT /exchange/rest/ms/xml/CustomerOrder HTTP/1.1\r\n");
fputs($sock, "Host: online.moysklad.ru\r\n");
fputs($sock, "Authorization: Basic " . base64_encode("admin@irbis:111210irbis") . "\r\n");
fputs($sock, "Content-Type: application/xml \r\n");
fputs($sock, "Accept: */*\r\n");
fputs($sock, "Content-Length: ".strlen($body)."\r\n");
fputs($sock, "Connection: close\r\n\r\n");
fputs($sock, "$body");
 
while ($str = trim(fgets($sock, 4096)));
 
$body = "";
 
while (!feof($sock))
    $body.= fgets($sock, 4096);
 
fclose($sock);
$xml = simplexml_load_string( iconv("UTF-8", "Windows-1251",$body));
print_r($xml);
        break;
	case 'export_news': //экспорт новостей для ирбиса
		$res = $database->query("select * from SC_news_table order by priority");
		$n = 0;
		while($row = mysql_fetch_array($res))
		{
			$data = array();
			$data['url'] = $row[0];
			$data[35] = $row['title_ru'];
			$data[37] = $row['textToPublication_ru'];
			$arr = explode(" ", $row['add_date']);
			$data[38] = $arr[0];
			print_r($data);
			print '<br/>--------------------------------------------<br/>';
			if($run>0)
			{
				insertData(12, $data);
			}
		}
		print '<br>OK: '.$n;
	break;
	case 'progs'://доступ программиста
	{
			$supers = array();
			curbase();
			$data = getData(139, '', '', array(342), TRUE, array(1004=>'1'));
			combase();
			foreach ($data as $gid=>$vals)
			{
				list($user) = $database->getArrayOfQuery("SELECT ID_USER FROM cprice_users_task WHERE id_ufapr=".$gid);
				if(!in_array($user, $supers) &&!empty($user))$supers[]=$user;
			}
			print_r($supers);
			foreach ($supers as $super)
			{
				$database->query( "UPDATE ".DB_PREFIX."users_privilegies SET access_type=3 WHERE ID_RUBRIC_TYPE=0 && ID_RUBRIC=0 && ID_USER=".$super );
			}
			foreach ($hosts as $host => $params) {
				if($host>0 && $run>0)
				{
					foreach ($supers as $super)
					{
						list($access,$id) = $database->getArrayOfQuery("SELECT access_type,id FROM cprice_users_privilegies WHERE ID_RUBRIC_TYPE=0 && ID_RUBRIC=0 && database_id=$host && ID_USER=".$super);
						if(empty($id) && $super!=4)$database->query( "INSERT INTO ".DB_PREFIX."users_privilegies (ID_USER,database_id,access_type) values (".$super.", $host, 3)" );
					}
				}
			}
			echo 'ok'.count($supers);
	}
	break;
	case 'linza-move':
        {
            $arr_from = array(188,189,190,191,192,193,194,195);
            $arr_to = array(38,39,40,41,42,43,44,45);
//            $arr_from = array(188);
//            $arr_to = array(38);
            $feats = array(68,84);
            $i=0;
            foreach($arr_from as $from)
            {
                $res = $database->query("select ID_RUBRIC, rubric_name from cprice_rubric where rubric_parent=".$from);
                $goods = array();
                while ($row = mysql_fetch_array($res)) {
                    $from = $row[0];
                    $rname = $row[1];
                    $feat1 = array();
                    $feat2 = array();
                    $data = getData($from,'','',$feats,true);
                    foreach($data as $gid=>$vals)
                    {
                        if($run == -1)$database->query("UPDATE cprice_goods SET good_visible=0 WHERE ID_GOOD=".$gid,false);
                        if(!empty($vals[$feats[0]]) && !in_array($vals[$feats[0]],$feat1))$feat1[]=$vals[$feats[0]];
                        if(!empty($vals[$feats[1]]) && !in_array($vals[$feats[1]],$feat2))$feat2[]=$vals[$feats[1]];
                    }
                    print $from.'/'.count($data).'|';
                    $data = getData($from,'','1',array(),true);
                    print count($data).'|<br/>';
                    foreach($data as $gid=>$vals)
                    {
								if(count($feat1)>0)$vals[$feats[0]] = $feat1;
                        if(count($feat2)>0)$vals[$feats[1]] = $feat2;
                        $vals[26] = $rname;
								if($vals[27]>0)
								{
									list($value_old_txt) = $database->getArrayOfQuery("
										SELECT text_text
										FROM ".DB_PREFIX."texts
										WHERE ID_TEXT = '".(int) $vals[27]."'
									");
									$vals[27] = $value_old_txt;
								}
                        print_r($vals);
                        print '<br/>';
                        $goods[$gid] = $vals;
                    }
                }
                if($run>0)
                {
                    foreach ($goods as $gid=>$vals)
                    {
                            $new_id = insertData($arr_to[$i], $vals);
                            $database->query("UPDATE ".DB_PREFIX."goods SET good_url='$new_id' WHERE ID_GOOD=$new_id",false);
                            $images = getImages($gid, 100, '', TRUE);
                            $res = $database->query("select ID_GOOD_PHOTO from cprice_goods_photos where ID_GOOD=".$gid);
                            while ($row = mysql_fetch_array($res))
                            {
                                $database->query("UPDATE cprice_goods_photos SET ID_GOOD=$new_id WHERE ID_GOOD_PHOTO=".$row[0],false);
                            }
                     }
                }
                $i++;
            }
            print 'OK '.$i;
        }
        break;
        case 'im-smeta':
        {
            //in ufapr
            $usl_im = array();
            $data = getData(216,'','',array(697),true,array(920=>28765));
            foreach($data as $gid=>$vals)
            {
                $usl_im[]=$gid;
            }
            $res = $database->query("select IdSmeta from cprice_smeta_usl where IdUslugi IN (".  implode(',', $usl_im).") group by IdSmeta");
            while ($row = mysql_fetch_array($res)) {
                print $row[0].' ';
                if($run>0)
                {
                    $database->query("INSERT INTO cprice_smeta_groups (IdSmeta,IdGroup) VALUES ($row[0],28765)",false);
                }
            }
        }
        break;
        case 'test_exc':
        {
teInclude("phpexcel");
$pExcel = new PHPExcel();
$pExcel->setActiveSheetIndex(0);
$aSheet = $pExcel->getActiveSheet();
$aSheet->setTitle(iconv("Windows-1251","UTF-8",'Первый лист'));
//устанавливаем данные
//номера по порядку
$aSheet->setCellValue('A1',iconv("Windows-1251","UTF-8",'№'));
$aSheet->setCellValue('A2','1');
$aSheet->setCellValue('A3','2');
$aSheet->setCellValue('A4','3');
$aSheet->setCellValue('A5','4');
//названия сайтов
$aSheet->setCellValue('B1',iconv("Windows-1251","UTF-8",'Названия'));
$aSheet->setCellValue('B2','http://www.web-junior.net');
$aSheet->setCellValue('B3','http://www.google.com');
$aSheet->setCellValue('B4','http://www.yandex.ru');
$aSheet->setCellValue('B5',iconv("Windows-1251","UTF-8",'
Коммерция, а в особенности торговля никогда не обходится без отчетности. Отчеты нужны для эффективного анализа текущего положения дел в бизнесе. В таблицы можно вводить различные данные: числа, текст, даты и т.д. Excel позволяет не просто выстраивать таблицы с данными. Он позволяет делать вычисления над данными таблиц. Кроме того, можно сортировать и фильтровать данные. Как только бизнес начал занимать просторы Интернета, сюда начали переносить все решения для ведения бизнеса на персональных компьютерах. Excel не стал исключением. Сегодня я хочу рассказать про очень удобную объектно-ориентированную библиотеку для работы с Excel-файлами, которая имеет название PHPExcel.
<p>Библиотека</p><br/>
Скачать <b>библиотеку</b> можно со странички Downloads официального сайта. Текущая доступная версия имеет номер 1.7.1. На сайте доступно несколько различных архивов для скачивания.
'));
//мой личный рейтинг
$aSheet->setCellValue('C1',iconv("Windows-1251","UTF-8",'Рейтинг'));
$aSheet->setCellValue('C2','100');
$aSheet->setCellValue('C3','99');
$aSheet->setCellValue('C4','90');
$aSheet->setCellValue('C5','85');
//устанавливаем ширину
$aSheet->getColumnDimension('B')->setWidth(25);
		$frmtZag = array(
                        'font' => array(
                                'bold' => true,
                                'name' => 'Arial',
                                'size' => 10,
                        ),
                        'alignment' => array(
                                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        ),
                        'borders' => array(
                                'allborders' => array(
                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                        'color' => array('argb' => '00000000'),
                                ),
                        ),
                        'fill' => array(
                                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                'color' => array(
                                        'argb' => 'FF00CCFF',
                                ),
                        ),

                );
                $aSheet->getStyle('A1:A5')->applyFromArray($frmtZag);
//отдаем пользователю в браузер
include("../engine/server/phpexcel/PHPExcel/Writer/Excel5.php");
$objWriter = new PHPExcel_Writer_Excel5($pExcel);
            $s1 = ob_get_contents();
            ob_end_clean();
            unset($s1);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="test_exc.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
die();
        }
        break;
	case 'png_jpg':
	{
		$res_ph = $database->query("select ID_GOOD_PHOTO,goodphoto_file from cprice_goods_photos where goodphoto_deleted=0");
		$i=0;
		while($row_ph = mysql_fetch_array($res_ph))
		{
			if(substr($row_ph[1],-3)=='png')
			{
				$new_name = substr($row_ph[1],0,-3).'jpg';
				if(file_exists(DATA_FLD."good_photo/".$new_name))
				{
					print $new_name.' ';
					if($run>0)
					{
						$database -> query("UPDATE cprice_goods_photos set goodphoto_file = '$new_name' WHERE ID_GOOD_PHOTO=".$row_ph[0],false);
					}
					$i++;
				}
			}
		}
		print '<br/>OK'.$i;
	}
	break;
	case 'jpg_png':
	{
		$res_ph = $database->query("select ID_GOOD_PHOTO,goodphoto_file from cprice_goods_photos where goodphoto_deleted=0");
		$i=0;
		while($row_ph = mysql_fetch_array($res_ph))
		{
			if(substr($row_ph[1],-3)=='jpg'||substr($row_ph[1],-3)=='JPG')
			{
				$new_name = substr($row_ph[1],0,-3).'png';
				if(file_exists(DATA_FLD."good_photo/".$new_name))
				{
					print $new_name.' ';
					if($run>0)
					{
						$database -> query("UPDATE cprice_goods_photos set goodphoto_file = '$new_name' WHERE ID_GOOD_PHOTO=".$row_ph[0],false);
					}
					$i++;
				}
			}
		}
		print '<br/>OK'.$i;
	}
	break;
	case 'from_changes':
		$i=0;
		$res = $database->query("select change_row,old_values from cprice_changes where change_table='cprice_texts' && change_type=2 && old_values<>''");
		while(list($idt,$text) = mysql_fetch_array($res))
		{
			if($run>0)
			{
				$database -> query("UPDATE cprice_texts set text_text = '".str_replace("'","\'",$text)."' WHERE ID_TEXT=".$idt,false);
			}
			$i++;
		}
/*		$res = $database->query("select ID_CHANGE from cprice_changes where change_type=2 && change_table='cprice_goods'");
		while(list($id) = mysql_fetch_array($res))
		{
			list($idt,$text) = $database->getArrayOfQuery("select change_row,old_values from cprice_changes where ID_GOOD=$id && change_table='cprice_texts' && change_type=2 && old_values<>'' limit 1");
			if($run>0)
			{
				$database -> query("UPDATE cprice_texts set text_text = '$text' WHERE ID_TEXT=".$idt,false);
			}
			$i++;
		}*/
		print 'OK'.$i;
	break;
	case 'chng_num':
		$rubs = array(101,107,111,105,150,103,133);
		$n=0;
		foreach($rubs as $rub)
		{
			$res = $database->query("select ID_GOOD from cprice_rubric_goods where ID_RUBRIC=".$rub);
			while(list($id) = mysql_fetch_array($res))
			{
				$n++;$photos = array();
				$res_ph = $database->query("select ID_GOOD_PHOTO from cprice_goods_photos where goodphoto_deleted=0 && ID_GOOD=".$id." order by goodphoto_pos");
				while($row_ph = mysql_fetch_array($res_ph))
				{
					$photos[]=$row_ph[0];
				}

				if($run>0 && count($photos)==2)
				{
					$pos=2;
					foreach($photos as $photo)
					{
						$database -> query("UPDATE cprice_goods_photos set goodphoto_pos = $pos WHERE ID_GOOD_PHOTO=".$photo,false);
						$pos=1;
					}
				}
			}
		}
		print 'ok '.$n;

	break;
	case 'del-fvals':
		/*Удаление значений характеристики в рубрике*/
		$feat = 84;
		$rub = 95;
		$res = $database->query("select ID_GOOD from cprice_rubric_goods where ID_RUBRIC=".$rub);
		$n=0;
		while(list($id) = mysql_fetch_array($res))
		{
			$n++;
			if($run>0)$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id." && ID_FEATURE=".$feat,false);
		}
		print 'ok '.$n;
	break;
	case 'add-num':
		foreach($hosts as $host=>$prms)
		{
			if($prms['db_host']=='localhost')
			{
				mysql_connect($prms['db_host'],'damir','cLJ3VF4G6p');
				mysql_select_db($prms['db_name']);
				@mysql_query("ALTER TABLE `cprice_goods_features` ADD `goodfeature_float` FLOAT NOT NULL");
			}
			if($prms['db_host']=='193.106.95.248')
			{
				mysql_connect($prms['db_host'],'root2','ZmAjWXum5NSXusAj');
				mysql_select_db($prms['db_name']);
				@mysql_query("ALTER TABLE `cprice_goods_features` ADD `goodfeature_float` FLOAT NOT NULL");
			}
		}
		curbase();
		print 'OK';
	break;
	case 'add-num-val':
		$res=$database->query("select ID_GOOD_FEATURE, goodfeature_value from cprice_goods_features natural join cprice_features where feature_type=1");
		$i++;
		while($row = mysql_fetch_array($res))
		{
			if(!empty($row[1]))
			{
				$row[1] = str_replace(",", ".", $row[1]);
				$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_float=".floatval($row[1])." WHERE 	ID_GOOD_FEATURE=".$row[0],false);
			}
			$i++;
		}
		print 'OK '.$i;
	break;
	case 'add-date-val':
		$res=$database->query("select ID_GOOD_FEATURE, goodfeature_value from cprice_goods_features natural join cprice_features where feature_type=8");
		$i++;
		while($row = mysql_fetch_array($res))
		{
			if(!empty($row[1]))
                        {
                            $arr = explode(".", $row[1]);
                            $float_val = mktime(12, 0, 0, $arr[1], $arr[0], $arr[2]);
                            $database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_float=".$float_val." WHERE 	ID_GOOD_FEATURE=".$row[0],false);
                        }
			$i++;
		}
		print 'OK '.$i;
	break;
	case 'del-photos':
		$res_ph = $database->query("select goodphoto_file,ID_GOOD_PHOTO from cprice_goods_photos where goodphoto_deleted=0");
		$i++;
		while($row_ph = mysql_fetch_array($res_ph))
		{
			if(!file_exists(DATA_FLD."good_photo/".$row_ph[0]))
			{
				print $row_ph[0].' ';
				$i++;
				if($run>0)$database -> query("UPDATE ".DB_PREFIX."goods_photos SET goodphoto_deleted=1 WHERE ID_GOOD_PHOTO=".$row_ph[1],false);
			}
		}
		print 'OK '.$i;
	break;
	case 'del-goods0':
		/*Удаление товаров с нулевой ценой в ufatdk */
		$res_goods=$database->query("select ID_GOOD, ID_RUBRIC from cprice_rubric_goods natural join cprice_goods natural join cprice_rubric natural join cprice_goods_features where rubric_type=10 && ID_RUBRIC=763 && rubric_deleted=0 && rubricgood_deleted=0 && good_deleted=0 && ID_FEATURE=416 && goodfeature_value='0'");
		$i=0;
		while($row_goods = mysql_fetch_array($res_goods))
		{
			echo ++$i.'. '.$row_goods[0].' / '.$row_goods[1].' :: ';
			if($run)
			{
				$id = $row_goods[0];
				$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$id,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$id,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id,false);
				$database -> query("DELETE FROM c_ost WHERE base_id=".$id,false);
			}
		}

	break;
	case 'del-goods':
		/*Удаление записей в удаленных рубриках*/
		$res=$database->query("select ID_GOOD, ID_RUBRIC from cprice_rubric natural join cprice_rubric_goods where rubric_deleted=1");
		$n=0;
		while($row = mysql_fetch_array($res))
		{
			$id = $row[0];
			list($n_g) = $database->getArrayOfQuery("select count(ID_RUBRIC) from cprice_rubric_goods where ID_GOOD=".$id);
			if($n_g==1)
			{
				$res_ph = $database->query("select goodphoto_file,ID_GOOD_PHOTO from cprice_goods_photos where goodphoto_deleted=0 && ID_GOOD=".$id);
				while($row_ph = mysql_fetch_array($res_ph))
				{
					list($n_ph) = $database->getArrayOfQuery("select count(ID_GOOD) from cprice_goods_photos where goodphoto_file='".$row_ph[0]."' && goodphoto_deleted=0");
					if($n_ph==1)
					{
						$file = $row_ph[0];
						@unlink(DATA_FLD."good_photo/image_".$file);
						@unlink(DATA_FLD."good_photo/trumb_".$file);
						@unlink(DATA_FLD."good_photo/".$file);
						$database -> query("DELETE FROM ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=".$row_ph[1],false);
					}else $database -> query("UPDATE ".DB_PREFIX."goods_photos SET goodphoto_deleted=1 WHERE ID_GOOD_PHOTO=".$row_ph[1],true,3);
				}
				$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$id,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$id,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id,false);
				$n++;
			}else $database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$id,false);
			$database -> query("DELETE FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=".$row[1],false);
			$database -> query("DELETE FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$row[1],false);
		}
		$res=$database->query("select ID_GOOD from cprice_goods where good_deleted=0");
		while($row = mysql_fetch_array($res))
		{
			$id = $row[0];
			list($n_g) = $database->getArrayOfQuery("select ID_RUBRIC from cprice_rubric_goods where ID_GOOD=".$id);
			if(empty($n_g))
			{
				$res_ph = $database->query("select goodphoto_file,ID_GOOD_PHOTO from cprice_goods_photos where goodphoto_deleted=0 && ID_GOOD=".$id);
				while($row_ph = mysql_fetch_array($res_ph))
				{
					list($n_ph) = $database->getArrayOfQuery("select count(ID_GOOD) from cprice_goods_photos where goodphoto_file='".$row_ph[0]."' && goodphoto_deleted=0");
					if($n_ph==1)
					{
						$file = $row_ph[0];
						@unlink(DATA_FLD."good_photo/image_".$file);
						@unlink(DATA_FLD."good_photo/trumb_".$file);
						@unlink(DATA_FLD."good_photo/".$file);
						$database -> query("DELETE FROM ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=".$row_ph[1],false);
					}else $database -> query("UPDATE ".DB_PREFIX."goods_photos SET goodphoto_deleted=1 WHERE ID_GOOD_PHOTO=".$row_ph[1],true,3);
				}
				$database -> query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$id,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id,false);
				$n++;
			}else
			{
				list($n_g) = $database->getArrayOfQuery("select ID_RUBRIC from cprice_rubric where rubric_deleted=0 && ID_RUBRIC=".$n_g);
				if(empty($n_g))
				{
					$res_ph = $database->query("select goodphoto_file,ID_GOOD_PHOTO from cprice_goods_photos where goodphoto_deleted=0 && ID_GOOD=".$id);
					while($row_ph = mysql_fetch_array($res_ph))
					{
						list($n_ph) = $database->getArrayOfQuery("select count(ID_GOOD) from cprice_goods_photos where goodphoto_file='".$row_ph[0]."' && goodphoto_deleted=0");
						if($n_ph==1)
						{
							$file = $row_ph[0];
							@unlink(DATA_FLD."good_photo/image_".$file);
							@unlink(DATA_FLD."good_photo/trumb_".$file);
							@unlink(DATA_FLD."good_photo/".$file);
							$database -> query("DELETE FROM ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=".$row_ph[1],false);
						}else $database -> query("UPDATE ".DB_PREFIX."goods_photos SET goodphoto_deleted=1 WHERE ID_GOOD_PHOTO=".$row_ph[1],true,3);
					}
					$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$id,false);
					$database -> query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$id,false);
					$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id,false);
					$n++;
				}
			}
		}
		$res=$database->query("select ID_GOOD from cprice_goods where good_deleted=1");
		while($row = mysql_fetch_array($res))
		{
			$id = $row[0];
			$res_ph = $database->query("select goodphoto_file,ID_GOOD_PHOTO from cprice_goods_photos where goodphoto_deleted=0 && ID_GOOD=".$id);
			while($row_ph = mysql_fetch_array($res_ph))
			{
				list($n_ph) = $database->getArrayOfQuery("select count(ID_GOOD) from cprice_goods_photos where goodphoto_file='".$row_ph[0]."' && goodphoto_deleted=0");
				if($n_ph==1)
				{
					$file = $row_ph[0];
					@unlink(DATA_FLD."good_photo/image_".$file);
					@unlink(DATA_FLD."good_photo/trumb_".$file);
					@unlink(DATA_FLD."good_photo/".$file);
					$database -> query("DELETE FROM ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=".$row_ph[1],false);
				}else $database -> query("UPDATE ".DB_PREFIX."goods_photos SET goodphoto_deleted=1 WHERE ID_GOOD_PHOTO=".$row_ph[1],true,3);
			}
			$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$id,false);
			$database -> query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$id,false);
			$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id,false);
			$n++;
		}
		print 'OK '.$n;
	break;
	case 'del-goods2':
		/*Удаление записей из рубрики и подрубрик*/
		$n=0;
		function del_rec($rid)
		{
			global $database,$n;
			$res=$database->query("select ID_GOOD from cprice_goods natural join cprice_rubric_goods where ID_RUBRIC=".$rid);
			while($row = mysql_fetch_array($res))
			{
				$id = $row[0];
				$res_ph = $database->query("select goodphoto_file,ID_GOOD_PHOTO from cprice_goods_photos where goodphoto_deleted=0 && ID_GOOD=".$id);
				while($row_ph = mysql_fetch_array($res_ph))
				{
					list($n_ph) = $database->getArrayOfQuery("select count(ID_GOOD) from cprice_goods_photos where goodphoto_file='".$row_ph[0]."' && goodphoto_deleted=0");
					if($n_ph==1)
					{
						$file = $row_ph[0];
						@unlink(DATA_FLD."good_photo/image_".$file);
						@unlink(DATA_FLD."good_photo/trumb_".$file);
						@unlink(DATA_FLD."good_photo/".$file);
						$database -> query("DELETE FROM ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=".$row_ph[1],false);
					}else $database -> query("DELETE FROM ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=".$row_ph[1],false);
				}
				$database -> query("DELETE FROM ".DB_PREFIX."rubric_goods WHERE ID_GOOD=".$id,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$id,false);
				$database -> query("DELETE FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id,false);
				$n++;
			}
			$database -> query("DELETE FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=".$rid,false);
			$database -> query("DELETE FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$rid,false);
			$res2 = $database->query("select ID_RUBRIC from cprice_rubric where rubric_parent=".$rid);
			while($row2 = mysql_fetch_array($res2))
			{
				del_rec($row2[0]);
			}
		}
		del_rec(246);
		print 'OK '.$n;
	break;
	case 'del-files':
		/*Удаление файлов в удаленных записях*/
                $feat_files = array(405);
		$res=$database->query("select ID_GOOD from cprice_goods where good_deleted=1");
		$n=0;
		while($row = mysql_fetch_array($res))
		{
                    $data = getDataId($row[0], $feat_files, TRUE);
                    print_r($data);
                    foreach($data as $feat=>$val)
                    {
			if(!empty($val))
			{
                            print $val.'<br/>';
                            if($run>0)
                            @unlink(DATA_FLD."features/".$val);
                            $n++;
			}
                    }
		}
		print 'OK '.$n;
	break;
	case 'clean-space':
		/*Убрать пробелы в ценах*/
		$fid = 33;$i=0;
		$res=$database->query("select * from cprice_goods_features where ID_FEATURE=".$fid);
		while($row = mysql_fetch_array($res))
		{
			$val = str_replace(" ","",$row['goodfeature_value']);
			print $val.' ';
			if($run>0)$database->query("update cprice_goods_features set goodfeature_value='".$val."' where ID_GOOD_FEATURE=".$row[0],false);
			$i++;
		}
		print '<br/>n='.$i;
	break;
	case 'mail':
		$frm = new teForm("form1","post");
		$frm->addf_text("email", "Email:");
		$frm->addf_text("text", "Text:","Тестовое письмо",true);
		$frm->addf_text("from", "FROM email:","info@ufapr.ru");
		$frm->setf_require("email","from");
		if(!$frm->send())
		{
			$to = $frm->get_value("email");
			$txt = $frm->get_value("text");
			$from = $frm->get_value("from");
			mail($to, 'Тестовое письмо', $txt, "From: {$from}<{$from}>\r\nContent-Type: text/html; charset=\"Windows-1251\"");
			print '<br>'.$to.' '.$txt;
		}

	break;
	case 'opisanie_csv':
		print '<div align="center">';
		$frm = new teForm("form1","post");
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("file");
		if(!$frm->send())
		{
			$file = $_FILES["file"]['tmp_name'];
			$farr = file($file);
            $n=0;
			foreach($farr AS $i => $cont){
				$line = explode(";", $cont);
					if(!empty($line[0]) || !empty($line[2]))
					{
						$gid=$line[0];
						if($run>0 && $gid>0)
						{
							setFeatData2(32,$gid,mysql_escape_string(str_replace(array("'","\""), "",$line[2])),true);
						}
						if($gid>0)$n++;
					}
			}
			print '<br>All: '.$n;
		}
		print '</div>';

	break;
	case 'ph_to_base2':
		//Добавлять фото и к товарам с фото
		$type = 108;
		$fname = 31;
		$type = 4;
		$fname = 26;
		$photos = array();
		$dir = 'new/';
		teInclude("images");
		$tmaxw = teGetConf('photo_tmaxw');
		$tmaxh = teGetConf('photo_tmaxh');
		$n=0;
			$res_goods=$database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_goods natural join cprice_rubric where rubric_type=$type && rubric_deleted=0 && rubricgood_deleted=0 && good_deleted=0");
			while($row_goods = mysql_fetch_array($res_goods))
			{
				//$art = getFeatureValue($id_good,$fname);
				$id_good = $row_goods[0];
				$name = getFeatureValue($id_good,$fname);
				$ext = '.jpg';$ext2 = '.JPG';
				$goodphoto_files = array();
				$goodphoto_file1 = $id_good.'.jpg';
				$goodphoto_file2 = $id_good.'.JPG';
				if(file_exists(DATA_FLD.$dir.$goodphoto_file1))
				{
					$goodphoto_files = array($goodphoto_file1);
				}
				elseif(file_exists(DATA_FLD.$dir.$goodphoto_file2))
				{
					$goodphoto_files = array($goodphoto_file2);
				}
				$goodphoto_file1 = $id_good.'.png';
				$goodphoto_file2 = $id_good.'.PNG';
				if(file_exists(DATA_FLD.$dir.$goodphoto_file1))
				{
					$goodphoto_files = array($goodphoto_file1);
				}
				elseif(file_exists(DATA_FLD.$dir.$goodphoto_file2))
				{
					$goodphoto_files = array($goodphoto_file2);
				}
				for($i=1;$i<20;$i++)
				{
					$goodphoto_file3 = $id_good.'_'.$i.$ext;
					$exist = false;
					if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
					{							
						$goodphoto_files[] = $goodphoto_file3;
						$exist = true;											
					}
					$goodphoto_file3 = $id_good.'_'.$i.$ext2;
					if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
					{
						$goodphoto_files[] = $goodphoto_file3;
						$exist = true;
					}
					if(!$exist)	break;
				}
				if(count($goodphoto_files)>0)
				{
	                foreach($goodphoto_files as $goodphoto_file)
	                {
	                	if(!in_array($goodphoto_file,$photos))
   						{
	   						print $id_good.': '.$name.' '.$goodphoto_file.' | ';
	   						if($run>0)
	   						{
									$filename = pathinfo( DATA_FLD.$dir.$goodphoto_file );
									$fn = substr( $goodphoto_file, 0, -strlen( $filename[ 'extension' ] ) - 1 );
									$i = 0;
									while ( file_exists( DATA_FLD . 'good_photo/' . $fn . ( empty( $i ) ? "" : "_" . $i ) . "." . $filename[ 'extension' ] ) )
									{
										$i++;
									}
									$filename = $fn . ( empty( $i ) ? "" : "_" . $i ) . "." . $filename[ 'extension' ];
		   						$database -> query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD,goodphoto_desc,goodphoto_alt,goodphoto_file,goodphoto_pos) VALUES ($id_good, '$name', '$name','$filename',0)");
		   						if(!empty($dir))
		   						{
		   							rename(DATA_FLD.$dir.$goodphoto_file,DATA_FLD.'good_photo/'.$filename);
		   						}
									$size_img = getimagesize(DATA_FLD."good_photo/".$filename);
									if(($size_img[0]>1024 || $size_img[1]>1024 || filesize(DATA_FLD."good_photo/".$filename)>400000) && (!isset($_POST['malph']))){teImgTrumb(DATA_FLD."good_photo/".$filename,"",1024,1024,NULL,80);}									
	   							new_wm_image(DATA_FLD.'good_photo/'.$filename);
	   							teImgTrumb(DATA_FLD."good_photo/".$filename,"trumb_",$tmaxw,$tmaxh);
		   					}
	   						$n++;
	   						$photos[] = $goodphoto_file;
   						}
	                }
                }
			}
			
		print '<br/><br/><a href="'.teGetUrlQuery("op1=ph_to_base2","run=10").'">Запустить скрипт</a> Кол-во: '.$n;
	break;

	case 'ph_to_base_promeso':
		$type = 108;
		$fname = 31;
		$type = 4;
		$fname = 26;
		$photos = array();
		$dir = 'new/';
		teInclude("images");
		$tmaxw = teGetConf('photo_tmaxw');
		$tmaxh = teGetConf('photo_tmaxh');
		$n=0;
			$res_goods=$database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_goods natural join cprice_rubric where rubric_type=$type && rubric_deleted=0 && rubricgood_deleted=0 && good_deleted=0
										 && ID_GOOD not in (SELECT ID_GOOD FROM ".DB_PREFIX."goods_photos)");
			while($row_goods = mysql_fetch_array($res_goods))
			{
				//$art = getFeatureValue($id_good,$fname);
				$id_good = $row_goods[0];
				$name = getFeatureValue($id_good,$fname);
				$ext = '.jpg';$ext2 = '.JPG';
				$goodphoto_files = array();
				$goodphoto_file1 = $id_good.'.jpg';
				$goodphoto_file2 = $id_good.'.JPG';
				if(file_exists(DATA_FLD.$dir.$goodphoto_file1))
				{
					$goodphoto_files = array($goodphoto_file1);
				}
				elseif(file_exists(DATA_FLD.$dir.$goodphoto_file2))
				{
					$goodphoto_files = array($goodphoto_file2);
				}
				$goodphoto_file1 = $id_good.'.png';
				$goodphoto_file2 = $id_good.'.PNG';
				if(file_exists(DATA_FLD.$dir.$goodphoto_file1))
				{
					$goodphoto_files = array($goodphoto_file1);
				}
				elseif(file_exists(DATA_FLD.$dir.$goodphoto_file2))
				{
					$goodphoto_files = array($goodphoto_file2);
				}
				for($i=1;$i<20;$i++)
				{
					$goodphoto_file3 = $id_good.'_'.$i.$ext;
					$exist = false;
					if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
					{							
						$goodphoto_files[] = $goodphoto_file3;
						$exist = true;											
					}
					$goodphoto_file3 = $id_good.'_'.$i.$ext2;
					if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
					{
						$goodphoto_files[] = $goodphoto_file3;
						$exist = true;
					}
					if(!$exist)	break;
				}
				if(count($goodphoto_files)>0)
				{
	                foreach($goodphoto_files as $goodphoto_file)
	                {
	                	if(!in_array($goodphoto_file,$photos))
   						{
	   						print $id_good.': '.$name.' '.$goodphoto_file.' | ';
	   						if($run>0)
	   						{
		   						$database -> query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD,goodphoto_desc,goodphoto_alt,goodphoto_file,goodphoto_pos) VALUES ($id_good, '$name', '$name','$goodphoto_file',0)");
		   						if(!empty($dir))
		   						{
		   							rename(DATA_FLD.$dir.$goodphoto_file,DATA_FLD.'good_photo/'.$goodphoto_file);
		   						}
									$size_img = getimagesize(DATA_FLD."good_photo/".$goodphoto_file);
									if(($size_img[0]>1024 || $size_img[1]>1024 || filesize(DATA_FLD."good_photo/".$goodphoto_file)>400000) && (!isset($_POST['malph']))){teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"",1024,1024,NULL,80);}									
	   							new_wm_image(DATA_FLD.'good_photo/'.$goodphoto_file);
	   							teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"trumb_",$tmaxw,$tmaxh);
		   					}
	   						$n++;
	   						$photos[] = $goodphoto_file;
   						}
	                }
                }
			}
			
		print '<br/><br/><a href="'.teGetUrlQuery("op1=ph_to_base_promeso","run=10").'">Запустить скрипт</a> Кол-во: '.$n;
	break;

	case 'cre':
		function insertData3($rubric_id, $data, $visible=1, $empty=false){
			global $database,$main_org;
			if(!is_array($rubric_id))
			{
				if($rubric_id>0)$rubric_id = array($rubric_id);
				else $rubric_id = array();
			}
			//$main_org используется для определения предприятия в базе УфаПиар.
			if(count($rubric_id)>0 && is_array($data))
			{
				$database -> query("INSERT INTO ".DB_PREFIX."goods (good_visible,good_deleted".($main_org>0?',main_org':'').") VALUES ($visible,0".($main_org>0?",$main_org":"").")");
				$change_id = $database -> change_id();
				$id =  $database -> id();
				foreach($rubric_id as $rub_id)
				{
					if($rub_id)	$database -> query("INSERT INTO ".DB_PREFIX."rubric_goods (ID_RUBRIC,ID_GOOD) VALUES (".$rub_id.",$id)");
				}
				$url = '';
				foreach($data as $fid=>$value)
				{
					if(is_array($value))
					{
						foreach($value as $item)
							setFeatData3($fid,$id,$item,false,true, $change_id);
					}
					else
					{
						if(empty($url)) $url = mb_strtolower(filename(translit($value)));
						setFeatData3($fid,$id,$value,false,$empty, $change_id);
					}
				}
				@mysql_query("UPDATE ".DB_PREFIX."goods set good_url='$url' where ID_GOOD=".$id);
				return $id;
			}else return -1;
		}
		function setFeatData3($feat_id,$good_id,$value,$update=false,$empty=false,$change_id=0){
			global $database;
			if(empty($value) && $empty)return false;

			$value = str_replace("'","\'",$value);
			list($type,$frub) = $database->getArrayOfQuery("
				SELECT feature_type, feature_rubric
				FROM ".DB_PREFIX."features
				WHERE ID_FEATURE = '".$feat_id."'
			");

			if($update){
				$res = $database->query("SELECT goodfeature_value,ID_GOOD_FEATURE FROM ".DB_PREFIX."goods_features WHERE ID_FEATURE = '".$feat_id."' and ID_GOOD = '".$good_id."'");
				$n = mysql_num_rows($res);
				if($n==1)
				{
					$row = mysql_fetch_row($res);
					$value_old=$row[0];
					$value_old_id=$row[1];
					if($type==7)
					{
						list($value_old_txt) = $database->getArrayOfQuery("
							SELECT text_text
							FROM ".DB_PREFIX."texts
							WHERE ID_TEXT = '".(int)$value_old."'
						");
						if($value_old_txt!=$value)
						{
							$database->query("UPDATE ".DB_PREFIX."texts SET text_text='".$value."' WHERE ID_TEXT='".$value_old."'",true,0,$change_id);
						}
					}
					elseif($value_old!=$value)
						$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$value."' WHERE ID_GOOD_FEATURE='".$value_old_id."'",true,0,$change_id);
					return true;
				}
			}
			switch($type){
				case 7:
					$database->query("INSERT INTO ".DB_PREFIX."texts (text_text) VALUES('".$value."')",true,0,$change_id);
					$value = $database->id();
				break;
				case 4:
					$value = trim($value);
					if(!empty($value))
					{
						$int_val = intval($value);
						$type_txt = false;
						if($int_val>0)
						{
							$str_val = strval($int_val);
							if($str_val!=$value)$type_txt = true;
						}else $type_txt = true;
						if($type_txt)
						{
							list($value_id) = $database->getArrayOfQuery("SELECT ID_FEATURE_DIRECTORY FROM cprice_feature_directory where ID_FEATURE=".$feat_id." && featuredirectory_text like '$value'");
							if(!$value_id)
							{
								$database->query("INSERT INTO cprice_feature_directory (ID_FEATURE,featuredirectory_text) VALUES (".$feat_id.",'$value')");
								$value = $database->id();
							}else $value = $value_id;
						}
					}
				break;
				case 9:
					$value = trim($value);
					if(!empty($value))
					{
						$int_val = intval($value);
						$type_txt = false;
						if($int_val>0)
						{
							$str_val = strval($int_val);
							if($str_val!=$value)$type_txt = true;
						}else $type_txt = true;
						if($type_txt)
						{
							list($value_id) = $database->getArrayOfQuery("SELECT ID_RUBRIC FROM cprice_rubric where rubric_type=".$frub." && rubric_name like '$value'");
							if(!$value_id)
							{
								$textid = filename(translit($value));
								$i = 0;
								while($database->getArrayOfQuery("SELECT ID_RUBRIC FROM cprice_rubric WHERE rubric_textid='$textid".(($i==0)?"":"_$i")."' and rubric_deleted=0 && rubric_type=".$frub)){
									$i++;
								}
								if(!empty($i)) $textid .= "_$i";
								setlocale (LC_ALL, array ('ru_RU.CP1251', 'rus_RUS.1251'));
								$database -> query("INSERT INTO ".DB_PREFIX."rubric (rubric_textid,rubric_parent,rubric_name,rubric_type,rubric_visible) VALUES ('".$textid."',0,'".ucfirst($value)."','$frub',1)");
								$value = $database->id();
							}else $value = $value_id;
						}
					}
				break;
				/* для креациони*/
				case 5:
					list($feature_rubric) = $database->getArrayOfQuery("
						SELECT ID_RUBRIC
						FROM ".DB_PREFIX."feature_rubric
						WHERE ID_FEATURE = '".$feat_id."'
					");
					list($rubric_type) = $database->getArrayOfQuery("
						SELECT rubric_type
						FROM ".DB_PREFIX."rubric
						WHERE ID_RUBRIC = '".$feature_rubric."'
					");
					$value = trim($value);
					if(!empty($value))
					{
						$int_val = intval($value);
						$type_txt = false;
						if($int_val>0)
						{
							$str_val = strval($int_val);
							if($str_val!=$value)$type_txt = true;
						}else $type_txt = true;
						if($type_txt)
						{
							list($value_id) = $database->getArrayOfQuery("SELECT ID_GOOD FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."goods_features where ID_RUBRIC=$feature_rubric  and ID_FEATURE=51 && goodfeature_value like '$value'");
							if(!$value_id)
							{
							}else $value = $value_id;
						}
					}
				break;
			}
			$database->query("INSERT INTO ".DB_PREFIX."goods_features (ID_FEATURE,ID_GOOD,goodfeature_value) VALUES ('".$feat_id."','".$good_id."','".$value."')",true,0,$change_id);
			return true;
		}

		print '<div align="center">';
		print '<h2>Добавление данных из файла</h2>';
		$frm = new teForm("form1","post");
		$frm->addf_selectGroup("rubric", "В какую рубрику добавить:");
		$r = $database->query("select ID_RUBRIC_TYPE,rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0");
		while($row2 = mysql_fetch_array($r))
		{
			$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_type=".$row2[0]);
			while($row = mysql_fetch_array($res))
				$frm->addf_selectItem("rubric", $row[0], $row2[1].' &gt;&gt; '.$row[1]);
		}
		$frm->addf_radioGroup("type", "Запись представлена");
		$frm->addf_radioItem("type", "cols", "В виде столбца");
		$frm->addf_radioItem("type", "rows", "В виде строки", true);
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("type","file");
		if(!$frm->send())
		{
			$rubric = (int)$frm->get_value('rubric');
			$type = $frm->get_value('type');
			$file = $_FILES["file"]['tmp_name'];
			$farr = file($file);

			print '<table>';
			$ids = array();
			$data = array();
			if($type=='cols')
			{
				foreach($farr AS $i => $cont){
					$line = explode("\t", $cont);
					if($i==0)
					{
						print '<tr>';
						foreach($line as $item)
						{
							if(!empty($item))$ids[] = $item;
							print '<td>'.$item.'</td>';
						}
						print '</tr>';
					}
					else
					{
						print '<tr>';$j=0;
						foreach($line as $item)
						{			$item = intval($item);
							if($j>0)
							{
								if(isset($data[($j-1)][$line[0]]))$data[($j-1)][$line[0]].='|'.$item;
								else $data[($j-1)][$line[0]]=$item;
							}
							print '<td>'.$item.'</td>';
							$j++;
						}
						print '</tr>';
					}
				}
				print '</table>';
				if($run==10 && $rubric>0)
				{deleteData($rubric);}
				$i=0;
				foreach($ids as $id)
				{	print $id.': ';
					print_r($data[$i]);
					print '<br/><br/>';
					if($run>0)
					{
						insertData3($rubric,$data[$i]);
					}
					$i++;
				}
			}else
			{
				$rids = array();
				$nfeats = 0;$k=0;
				foreach($farr AS $i => $cont){
					//$line = explode("\t", $cont);
					$line = explode(";", $cont);
					if($i==0)
					{
						print '<tr>';
						foreach($line as $item)
						{
								$ids[] = $item;
								print '<td>'.$item.'</td>';
						}
						$nfeats = count($ids);
						print '</tr>';
					}
					else
					{
						if(count($line)==$nfeats)
						{
							print '<tr>';$j=0;
							foreach($ids as $fid)
							{
								if(!empty($fid))$data[$k][$fid] = $line[$j];
								else $rids[] = $line[$j];
								print '<td>'.(empty($line[$j])?"&nbsp;":$line[$j]).'</td>';
								$j++;
							}
							print '</tr>';$k++;
						}
					}
				}
				print '</table>';
				/*if($run==10 && $rubric>0)
				{deleteData($rubric);}*/
				$tmaxw = teGetConf('photo_tmaxw');
				$tmaxh = teGetConf('photo_tmaxh');
				$dir = 'new/';
				teInclude("images");
				$fname=27;
				for($j=0;$j<$k;$j++)
				{
					print_r($data[$j]);
					$art = $data[$j][$fname];

					$ext = '.jpg';$ext2 = '.JPG';
					$goodphoto_files = array();
					// не удалять!!!
					$goodphoto_file1 = $art.'.jpg';
					$goodphoto_file2 = $art.'.JPG';
					if(file_exists(DATA_FLD.$dir.$goodphoto_file1))
					{
						$goodphoto_files = array($goodphoto_file1);
					}
					elseif(file_exists(DATA_FLD.$dir.$goodphoto_file2))
					{
						$goodphoto_files = array($goodphoto_file2);
					}

					for($n=1;$n<20;$n++)
					{
						$goodphoto_file3 = $art.'_'.$n.$ext;
						//$exist = false;
						if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
						{							$goodphoto_files[] = $goodphoto_file3;
							//$exist = true;
							}

						$goodphoto_file3 = $art.'_'.$n.$ext2;
						if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
						{
							$goodphoto_files[] = $goodphoto_file3;
							//$exist = true;
						}
						//if(!$exist)	break;
					}

					/*$goodphoto_file='';
					if(file_exists(DATA_FLD.$dir.$art.".jpg"))
					{
						$goodphoto_file = $art.".jpg";
						print '<br/>'.$goodphoto_file;
					}
					elseif(file_exists(DATA_FLD.$dir.$art.".JPG"))
					{
						$goodphoto_file = $art.".JPG";
						print '<br/>'.$goodphoto_file;
					}*/
					print '<br/><br/>';
					if($run>0)
					{
						if($rubric>0) $gid = insertData3($rubric,$data[$j]);
						else $gid = insertData3($rids[$j],$data[$j]);
						foreach($goodphoto_files as $goodphoto_file)
						{
							if(!empty($goodphoto_file))
							{	   						$name = $data[$j][61].' '.$data[$j][62].' '.$art;
								$database -> query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD,goodphoto_desc,goodphoto_alt,goodphoto_file,goodphoto_pos) VALUES ($gid, '$name', '$name','$goodphoto_file',1)");
								rename(DATA_FLD.$dir.$goodphoto_file,DATA_FLD.'good_photo/'.$goodphoto_file);
								new_wm_image(DATA_FLD.'good_photo/'.$goodphoto_file);
								teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"trumb_",$tmaxw,$tmaxh);
							}
						}
					}
				}
			}
		}
		print '</div>';

	break;
	case 'bez_foto':
		function getData2($rubric_id, $orderby='', $limit='', $features = array(),$fvalues=false,$uslovia=array()){
			global $database;
			$data = array();
			if(count($features)==0)
			{
				$res_feat = $database->query("
					SELECT ".DB_PREFIX."features.ID_FEATURE
					FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
					WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0
					ORDER BY rubricfeature_pos
				");
				while( list($feature_id) = mysql_fetch_array($res_feat))
				{
					$features[] = $feature_id;
				}

			}
			$add_tbl = '';$add_sql = '';
			if(count($uslovia)>0)
			{
				$add_tbl = 'natural join cprice_goods_features';
				$add_sql = " && (";
				foreach($uslovia as $fid=>$fval)
				{
					$add_sql .=  "(ID_FEATURE='".$fid."' && goodfeature_value='".$fval."') || ";
				}
				$add_sql = substr($add_sql,0,-4).")";
			}
			$res = $database->query("
				SELECT ID_GOOD, good_url
				FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods $add_tbl
				WHERE ID_RUBRIC=".$rubric_id." && good_deleted=0 && good_visible=1 and ID_GOOD not in (SELECT ID_GOOD FROM ".DB_PREFIX."goods_photos) ".$add_sql
				.(empty($orderby)?" ORDER BY rubricgood_pos, ID_GOOD":" ORDER BY ".$orderby)
				.(empty($limit)?"":" LIMIT ".$limit)
			);

			while( list($good_id,$url) = mysql_fetch_array($res) ){
				$data[$good_id]['url']=$url;
				foreach($features as $feature_id)
				{
					if($feature_id>0)
					{
						if($fvalues) $data[$good_id][$feature_id] = getFeatureValue($good_id, $feature_id);
						else $data[$good_id][$feature_id] = getFeatureText($good_id, $feature_id,false,true);
					}
				}
			}
			return $data;
		}

		$type = 2;
		if($type>0)
		{
			addSubMenu(teGetUrlQuery("pg=rubric","type=".$type),'Вернуться');
			print "<div align=center>";
			list($name_type) = $database->getArrayOfQuery("select rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0 && ID_RUBRIC_TYPE=".$type);
			if(!empty($name_type))
			{
				$frm = new teForm("form1","post");
				$frm->addTitle("<h2>Формирование Excel-файла по рубрикатору: ".$name_type."</h2>
					<div>Поля и ширина полей - это заголовки и ширина столбцов, которые будут сгенерированы в Excel-файле. <br/>Их число должно быть равно числу выбранных для экспорта характеристик.<br/>
					<a target='_blank' href='/files/to_excel.png'>Пример заполнения</a><br/><br/> </div>");
				$frm->addf_text("fld", "Поля");
				$frm->setFieldMultiple("fld");

				$frm->addf_text("wfld", "Ширина полей",'10');
				$frm->setFieldMultiple("wfld");


				$frm->addf_selectGroup("feats", "Характеристики по полям");
				$frm->addf_selectItem("feats", 'id', 'ID');
				$r = $database->query("select ID_FEATURE,feature_text from cprice_features natural join cprice_rubric_features where feature_enable=1 && feature_deleted=0 && ID_RUBRIC=0 && rubric_type=".$type);
				while($row = mysql_fetch_array($r))
				{
					$frm->addf_selectItem("feats", $row[0], $row[1]);
				}
				$frm->setFieldMultiple("feats");

				$frm->addf_checkbox("img", 'Отображать наличие картинки');

				$frm->setf_require("fld","wfld");
				if(!$frm->send())
				{
					if($run>0)
					{

						$img = $frm->get_value_checkbox("img",false);
						$flds = $frm->get_value('fld');
						$wflds = $frm->get_value('wfld');
						$feats = $frm->get_value('feats');
						$n_flds = count($flds);
						$s1 = ob_get_contents();
						ob_end_clean();
						unset($s1);
						// библиотека ексель
						teInclude("excel");
						$workbook = new Spreadsheet_Excel_Writer();
						$workbook->send("catalog.xls");
						$worksheet =& $workbook->addWorksheet('Каталог товаров');
						$num=0;
						$frmt = & $workbook->addFormat();
						$frmt->setBold();
						$frmt->setBorder(1);
						$frmt->setAlign('center');
						$frmt->setVAlign('vcenter');
						$frmt->setSize(10);
						$frmt->setTextWrap();
						for($i=0;$i<$n_flds;$i++)
						{
							$worksheet->setColumn(0,$i,$wflds[$i]);
							$worksheet->writeString($num, $i, $flds[$i],$frmt);
						}
						if($img)
						{
							$worksheet->setColumn(0,$i,'10');
							$worksheet->writeString($num, $i, 'Картинка',$frmt);
						}
						unset($frmt);
						$frmt = & $workbook->addFormat();
						$frmt->setBorder(1);
						$frmt->setVAlign('vcenter');
						$frmt->setSize(10);
						$frmt->setTextWrap();
						$num++;
						function rubs($type,$parent,$pref='')
						{
							global $database,$worksheet,$frmt,$num,$n_flds,$feats,$img;
							$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_parent=$parent && rubric_type=".$type." order by rubric_pos");
							while($row = mysql_fetch_array($res))
							{
								if($img)
								{
									$worksheet->setMerge($num, 0, $num, $n_flds);
									$worksheet->writeString($num, 0, $pref.$row[1],$frmt);
									for($i=1;$i<=$n_flds;$i++)
										$worksheet->writeString($num, $i, "",$frmt);
								}
								else
								{
									$worksheet->setMerge($num, 0, $num, ($n_flds-1));
									$worksheet->writeString($num, 0, $pref.$row[1],$frmt);
									for($i=1;$i<$n_flds;$i++)
										$worksheet->writeString($num, $i, "",$frmt);

									/*$line = $database->getArrayOfQuery("SELECT * FROM ".DB_PREFIX."metadata WHERE metadata_page=2 and metadata_id='".$row[0]."'",MYSQL_ASSOC);
									$i++;
									$worksheet->writeString($num, $i, strip_tags($line['metadata_body_description']),$frmt);
									$i++;
									$worksheet->writeString($num, $i, strip_tags($line['metadata_body_keywords']),$frmt);*/
								}
								$num++;

								$data = getData2($row[0], 'ID_GOOD', '', $feats);
								if(count($data)>0)
								{
									foreach($data as $gid=>$vals)
									{
										for($i=0;$i<$n_flds;$i++)
										{
											if($feats[$i]=='id')$worksheet->writeString($num, $i, $gid,$frmt);
											else $worksheet->writeString($num, $i, strip_tags(@$vals[$feats[$i]]),$frmt);
										}
										if($img)
										{
											list($im) = $database->getArrayOfQuery("select ID_GOOD_PHOTO from cprice_goods_photos where ID_GOOD=$gid && goodphoto_visible=1 && goodphoto_deleted=0 limit 1");
											$worksheet->writeString($num, $i, ($im>0?'есть':'нет'),$frmt);
										}
										$num++;
									}
								}
								rubs($type,$row[0],$pref.'>>');
							}
						}
						rubs($type,0);
						$workbook->close();
						exit;
					}
				}
			}else print "Error";
			print "</div>";
		}else print "Error";

	break;
	case 'no_price_and_op':
		function getData3($rubric_id, $orderby='', $limit='', $features = array(),$fvalues=false,$uslovia=array()){
			global $database;
			$data = array();
			if(count($features)==0)
			{
				$res_feat = $database->query("
					SELECT ".DB_PREFIX."features.ID_FEATURE
					FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
					WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0
					ORDER BY rubricfeature_pos
				");
				while( list($feature_id) = mysql_fetch_array($res_feat))
				{
					$features[] = $feature_id;
				}

			}
			$add_tbl = '';$add_sql = '';
			if(count($uslovia)>0)
			{
				$add_tbl = 'natural join cprice_goods_features';
				$add_sql = " && (";
				foreach($uslovia as $fid=>$fval)
				{
					$add_sql .=  "(ID_FEATURE='".$fid."' && goodfeature_value='".$fval."') || ";
				}
				$add_sql = substr($add_sql,0,-4).")";
			}
			$res = $database->query("
				SELECT ID_GOOD, good_url
				FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods $add_tbl
				WHERE ID_RUBRIC=".$rubric_id." && good_deleted=0 && good_visible=1".$add_sql
				.(empty($orderby)?" ORDER BY rubricgood_pos, ID_GOOD":" ORDER BY ".$orderby)
				.(empty($limit)?"":" LIMIT ".$limit)
			);

			while( list($good_id,$url) = mysql_fetch_array($res) ){

				//$txt=getFeatData(32,$good_id);
				//if(empty($txt)){
					$data[$good_id]['url']=$url;
					foreach($features as $feature_id)
					{
						if($feature_id>0)
						{
							if($fvalues) $data[$good_id][$feature_id] = getFeatureValue($good_id, $feature_id);
							else $data[$good_id][$feature_id] = getFeatureText($good_id, $feature_id,false,true);
						}
					}
				//}
			}
			return $data;
		}

		$type = 2;
		if($type>0)
		{
			addSubMenu(teGetUrlQuery("pg=rubric","type=".$type),'Вернуться');
			print "<div align=center>";
			list($name_type) = $database->getArrayOfQuery("select rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0 && ID_RUBRIC_TYPE=".$type);
			if(!empty($name_type))
			{
				$frm = new teForm("form1","post");
				$frm->addTitle("<h2>Формирование Excel-файла по рубрикатору: ".$name_type."</h2>
					<div>Поля и ширина полей - это заголовки и ширина столбцов, которые будут сгенерированы в Excel-файле. <br/>Их число должно быть равно числу выбранных для экспорта характеристик.<br/>
					<a target='_blank' href='/files/to_excel.png'>Пример заполнения</a><br/><br/> </div>");
				$frm->addf_text("fld", "Поля");
				$frm->setFieldMultiple("fld");

				$frm->addf_text("wfld", "Ширина полей",'10');
				$frm->setFieldMultiple("wfld");


				$frm->addf_selectGroup("feats", "Характеристики по полям");
				$frm->addf_selectItem("feats", 'id', 'ID');
				$r = $database->query("select ID_FEATURE,feature_text from cprice_features natural join cprice_rubric_features where feature_enable=1 && feature_deleted=0 && ID_RUBRIC=0 && rubric_type=".$type);
				while($row = mysql_fetch_array($r))
				{
					$frm->addf_selectItem("feats", $row[0], $row[1]);
				}
				$frm->setFieldMultiple("feats");

				$frm->addf_checkbox("img", 'Отображать наличие картинки');

				$frm->setf_require("fld","wfld");
				if(!$frm->send())
				{
					if($run>0)
					{

						$img = $frm->get_value_checkbox("img",false);
						$flds = $frm->get_value('fld');
						$wflds = $frm->get_value('wfld');
						$feats = $frm->get_value('feats');
						$n_flds = count($flds);
						$s1 = ob_get_contents();
						ob_end_clean();
						unset($s1);
						// библиотека ексель
						teInclude("excel");
						$workbook = new Spreadsheet_Excel_Writer();
						$workbook->send("catalog.xls");
						$worksheet =& $workbook->addWorksheet('Каталог товаров');
						$num=0;
						$frmt = & $workbook->addFormat();
						$frmt->setBold();
						$frmt->setBorder(1);
						$frmt->setAlign('center');
						$frmt->setVAlign('vcenter');
						$frmt->setSize(10);
						$frmt->setTextWrap();
						for($i=0;$i<$n_flds;$i++)
						{
							$worksheet->setColumn(0,$i,$wflds[$i]);
							$worksheet->writeString($num, $i, $flds[$i],$frmt);
						}
						if($img)
						{
							$worksheet->setColumn(0,$i,'10');
							$worksheet->writeString($num, $i, 'Картинка',$frmt);
						}
						unset($frmt);
						$frmt = & $workbook->addFormat();
						$frmt->setBorder(1);
						$frmt->setVAlign('vcenter');
						$frmt->setSize(10);
						$frmt->setTextWrap();
						$num++;
						function rubs($type,$parent,$pref='')
						{
							global $database,$worksheet,$frmt,$num,$n_flds,$feats,$img;
							$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_parent=$parent && rubric_type=".$type." order by rubric_pos");
							while($row = mysql_fetch_array($res))
							{
								if($img)
								{
									$worksheet->setMerge($num, 0, $num, $n_flds);
									$worksheet->writeString($num, 0, $pref.$row[1],$frmt);
									for($i=1;$i<=$n_flds;$i++)
										$worksheet->writeString($num, $i, "",$frmt);
								}
								else
								{
									$worksheet->setMerge($num, 0, $num, ($n_flds-1));
									$worksheet->writeString($num, 0, $pref.$row[1],$frmt);
									for($i=1;$i<$n_flds;$i++)
										$worksheet->writeString($num, $i, "",$frmt);
								}
								$num++;
								$usl=array(33=>'');
								$data = getData3($row[0], 'ID_GOOD', '', $feats,false,$usl);
								if(count($data)>0)
								{
									foreach($data as $gid=>$vals)
									{
										for($i=0;$i<$n_flds;$i++)
										{
											if($feats[$i]=='id')$worksheet->writeString($num, $i, $gid,$frmt);
											else $worksheet->writeString($num, $i, strip_tags(@$vals[$feats[$i]]),$frmt);
										}
										if($img)
										{
											list($im) = $database->getArrayOfQuery("select ID_GOOD_PHOTO from cprice_goods_photos where ID_GOOD=$gid && goodphoto_visible=1 && goodphoto_deleted=0 limit 1");
											$worksheet->writeString($num, $i, ($im>0?'есть':'нет'),$frmt);
										}
										$num++;
									}
								}
								rubs($type,$row[0],$pref.'>>');
							}
						}
						rubs($type,0);
						$workbook->close();
						exit;
					}
				}
			}else print "Error";
			print "</div>";
		}else print "Error";

		function getData2($rubric_id, $orderby='', $limit='', $features = array(),$fvalues=false,$uslovia=array()){
			global $database;
			$data = array();
			if(count($features)==0)
			{
				$res_feat = $database->query("
					SELECT ".DB_PREFIX."features.ID_FEATURE
					FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features
					WHERE ID_RUBRIC=".$rubric_id." and feature_deleted=0
					ORDER BY rubricfeature_pos
				");
				while( list($feature_id) = mysql_fetch_array($res_feat))
				{
					$features[] = $feature_id;
				}

			}
			$add_tbl = '';$add_sql = '';
			if(count($uslovia)>0)
			{
				$add_tbl = 'natural join cprice_goods_features';
				$add_sql = " && (";
				foreach($uslovia as $fid=>$fval)
				{
					$add_sql .=  "(ID_FEATURE='".$fid."' && goodfeature_value='".$fval."') || ";
				}
				$add_sql = substr($add_sql,0,-4).")";
			}
			$res = $database->query("
				SELECT ID_GOOD, good_url
				FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."goods $add_tbl
				WHERE ID_RUBRIC=".$rubric_id." && good_deleted=0 && good_visible=1".$add_sql
				.(empty($orderby)?" ORDER BY rubricgood_pos, ID_GOOD":" ORDER BY ".$orderby)
				.(empty($limit)?"":" LIMIT ".$limit)
			);

			while( list($good_id,$url) = mysql_fetch_array($res) ){
				$data[$good_id]['url']=$url;
				foreach($features as $feature_id)
				{
					if($feature_id>0)
					{
						if($fvalues) $data[$good_id][$feature_id] = getFeatureValue($good_id, $feature_id);
						else $data[$good_id][$feature_id] = getFeatureText($good_id, $feature_id,false,true);
					}
				}
			}
			return $data;
		}
	break;
	case 'foviart':
		$feats = array(50,58,66,76,85);
		foreach($feats as $feat)
		{
			$res = $database->query("select ID_GOOD_FEATURE,goodfeature_value from cprice_goods_features where ID_FEATURE=".$feat);
			while($row=mysql_fetch_row($res))
			{
				print $feat.': '.$row[1];
				if($run>0)$database -> query("UPDATE cprice_goods_features set ID_FEATURE='41' WHERE ID_GOOD_FEATURE=".$row[0],false);
			}
		}
	break;
	case 'micron':
		if($run==1)
		{
	       	$res = $database->query("select ID_RUBRIC from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_type=8");
			$i=1;
			while($row=mysql_fetch_row($res))
			{
				$res2 = $database->query("select ID_GOOD from cprice_rubric_goods where ID_RUBRIC=".$row[0]);
				while($row2=mysql_fetch_row($res2))
				{
					print $row[0].' '.$row2[0].'; ';
					$database -> query("UPDATE cprice_goods set good_rubric='$row[0]' WHERE ID_GOOD=".$row2[0],false);
				}
			}
		}
		if($run==2)
		{
			//name
			$res = $database->query("select ID_GOOD,goodfeature_value from cprice_goods_features where ID_FEATURE=191");
			while($row=mysql_fetch_row($res))
			{
				print $row[0].' '.$row[1].'; ';
				$val = mysql_escape_string($row[1]);
				$database -> query("UPDATE cprice_goods set good_name='$val' WHERE ID_GOOD=".$row[0],false);
			}

		}
		if($run==3)
		{
			//price
			$res = $database->query("select ID_GOOD,goodfeature_value from cprice_goods_features where ID_FEATURE=192");
			while($row=mysql_fetch_row($res))
			{
				print $row[0].' '.$row[1].'; ';
				$val = floatval($row[1]);
				$database -> query("UPDATE cprice_goods set good_price='$val' WHERE ID_GOOD=".$row[0],false);
			}

		}
		if($run==4)
		{
			//nalichie
			$res = $database->query("select ID_GOOD,goodfeature_value from cprice_goods_features where ID_FEATURE=193");
			while($row=mysql_fetch_row($res))
			{
				print $row[0].' '.$row[1].'; ';
				$val = intval($row[1]);
				$database -> query("UPDATE cprice_goods set good_nal='$val' WHERE ID_GOOD=".$row[0],false);
			}

		}

	break;
	case 'xml2':
		$file = "https://online.moysklad.ru/exchange/rest/stock/xml";
$ch = curl_init();
curl_setopt ($ch, CURLOPT_URL, "https://online.moysklad.ru/exchange/rest/stock/xml");
$user = "admin@irbis";
$password = "111210irbis";
 
//вразумительный браузер
curl_setopt($ch,CURLOPT_USERAGENT,"Mozilla/4.0");
 
//интересно посмотреть заголовки?
//curl_setopt($ch,CURLOPT_HEADER,1);

curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
curl_setopt($ch,CURLOPT_USERPWD,$user . ":" . $password);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
$result = curl_exec ($ch);
curl_close($ch);
$xml = simplexml_load_string($result);
$n = 0; $j = 0;
foreach($xml->stockTO as $good)
{
	$price = $good['salePrice']*0.01;
	$num = $good['quantity'];
	print '<hr/><b>'.$num.'</b><br/>';
	print '<b>'.$price.'</b><br/>';
	$id_1c = $good['externalCode'];
	print '!'.$id_1c.'!<br/>';
	print iconv("UTF-8","Windows-1251//TRANSLIT//IGNORE",$good->goodRef['name']) .'<br/>';
	if($run>0)
	{
		//$database->query("set names utf8");
		list($good_id) = $database->getArrayOfQuery("SELECT ID_GOOD FROM cprice_goods_features NATURAL JOIN cprice_goods WHERE good_deleted=0 && ID_FEATURE=142 && goodfeature_value = '".$id_1c."'");
		echo "SELECT ID_GOOD FROM cprice_goods_features NATURAL JOIN cprice_goods WHERE good_deleted=0 && ID_FEATURE=142 && goodfeature_value = '".$id_1c."'".$good_id.'<br/>';
		if($good_id>0)
		{
			$data = array();
			$data[32] = $price;
			if($num>0)$data[370] = 1;
			else $data[370] = 0;
			updateData($good_id, $data);
			$j++;
		}
	}
	$n++;
}
echo 'OK '.$n.'; update '.$j;
	break;
	case 'xml':
		$file = 'capital_website_export.xml';
		print $file.'<br/>';
		if (file_exists($file)) {
		    $xml = simplexml_load_file($file);
			foreach($xml->ad as $adv)
			{
				print '<b>'.$adv['source-id'].'</b><br/>';
				print $adv->categories->category[0]['destination'] .'<br/>';
				foreach($adv->items->item as $item)
				{
					print $item['name'].' '.iconv("UTF-8","Windows-1251//TRANSLIT//IGNORE",$item).'<br/>';
				}
				foreach($adv->fotos->foto as $foto)
				{
					print $foto->url.'<br/>';
				}
				print '<br/><br/>';
			}
		} else print 'File not exist';
	break;
	case 'welc-txt':/*Исправление ошибки в текстах в welcomeinufa*/
       	$res = $database->query("select ID_FEATURE,parent_lang from cprice_features where feature_type=7 && parent_lang>0 order by parent_lang");
		$i=1;
		while($row=mysql_fetch_row($res))
		{
			$res_fv = $database->query("select ID_GOOD, goodfeature_value from cprice_goods_features where ID_FEATURE=".$row[1]);
			while($row_fv=mysql_fetch_row($res_fv))
			{
				list($lid,$lval,$id) = $database->getArrayOfQuery("select ID_GOOD,goodfeature_value,ID_GOOD_FEATURE from cprice_goods natural join cprice_goods_features where ID_FEATURE=".$row[0]." && parent_lang=".$row_fv[0]);
				if($lval==$row_fv[1] && !empty($row_fv[1]))
				{
					list($txt) = $database->getArrayOfQuery("select text_text from cprice_texts where ID_TEXT=".$lval);
					print $row_fv[0].' '.$id.' '.$lid.' '.$txt.'<br/><br/>';
					if($run>0)
					{
						$database->query("INSERT INTO ".DB_PREFIX."texts (text_text) VALUES ('".mysql_escape_string($txt)."')",true,0,$lid);
						$val = $database->id();
						$database -> query("UPDATE ".DB_PREFIX."goods_features  set goodfeature_value='$val' WHERE ID_GOOD_FEATURE=".$id,true,0,$lid);
					}
				}
			}
		}

	break;
	case 'seo-copy':
       	$res = $database->query("select ID_RUBRIC from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_type=1");
		$i=1;
		while($row=mysql_fetch_row($res))
		{
			mysql_select_db('bphold2');
			list($txt) = $database->getArrayOfQuery("select metadata_body_keywords from cprice_metadata where metadata_page=2 && metadata_id=".$row[0]);
			if(!empty($txt))
			{
				print $txt.'<br/><br/>';
				if($run>0){
					mysql_select_db('shop');
					list($meta_id) = $database->getArrayOfQuery("select ID_METADATA from cprice_metadata where metadata_page=2 && metadata_id=".$row[0]);
					if($meta_id>0)
					{
						$database -> query("UPDATE cprice_metadata SET metadata_body_keywords='$txt' WHERE ID_METADATA=".$meta_id, false);
					}
					else{
						$database -> query("INSERT INTO cprice_metadata (metadata_page,metadata_id,metadata_body_keywords) VALUES (2,$row[0],'$txt')", false);
					}
				}
			}
		}
	break;
	case 'del_1c':
       	$res = $database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_type=5");
		$i=1;
		while($row=mysql_fetch_row($res))
		{
			$gid = $row[0];
			$id_1c = getFeatureValue($gid,1302);
			if(empty($id_1c))
			{
				print $i++.' '.$gid.' ';
				if($run>0)$database -> query("UPDATE ".DB_PREFIX."goods SET good_deleted=1, id_1c=100 WHERE ID_GOOD=".$gid, false);
			}
		}
	break;
	case 'copy_kat':
		$type  = 2;
       	//Перенос рубрик
		$res = $database->query("select ID_RUBRIC,rubric_textid,rubric_parent,rubric_name,rubric_unit_prefixname,rubric_ex,rubric_img,rubric_pos from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_type=".$type." && ID_RUBRIC=378 order by ID_RUBRIC");
		mysql_select_db('shop');
		$rubs = array();
       	while($row = mysql_fetch_array($res))
       	{
			$rubs[]=$row['ID_RUBRIC'];
			if($run>0)
			{
				if(!empty($row['rubric_img']))
				{
					copy(DATA_FLD."rubric_img/".$row['rubric_img'],'/var/www/shop.bphold.ru/images/rubric_img/'.$row['rubric_img']);
				}
				$database->query("insert into cprice_rubric (ID_RUBRIC,rubric_textid,rubric_parent,rubric_name,rubric_unit_prefixname,rubric_ex,rubric_img,rubric_pos,rubric_type) values (".$row['ID_RUBRIC'].",'".$row['rubric_textid']."','".$row['rubric_parent']."','".$row['rubric_name']."','".$row['rubric_unit_prefixname']."','".$row['rubric_ex']."','".$row['rubric_img']."','".$row['rubric_pos']."', 4)",false);
			}
		}
		//Перенос характеристик
		mysql_select_db('bphold2');
		$res = $database->query("select ID_RUBRIC, ID_FEATURE, rubricfeature_graduation, rubricfeature_pos from cprice_rubric_features where rubric_type=".$type." order by ID_RUBRIC");
		mysql_select_db('shop');
		$feats = array();
       	while($row = mysql_fetch_array($res))
       	{
			if(in_array($row['ID_RUBRIC'],$rubs) || $row['ID_RUBRIC']==0)
			{
				if(!in_array($row['ID_FEATURE'],$feats))
				{
					$feats[]=$row['ID_FEATURE'];
				}
				if($run>0)
				{
					$database->query("insert into cprice_rubric_features (ID_RUBRIC, ID_FEATURE, rubricfeature_graduation, rubricfeature_pos,rubric_type) values (".$row['ID_RUBRIC'].",".$row['ID_FEATURE'].",".$row['rubricfeature_graduation'].",".$row['rubricfeature_pos'].", 4)",false);
				}
			}
		}
		//Добавление характеристик
		$featsT = array();
		foreach($feats as $feat)
		{
			mysql_select_db('bphold2');
			$res = $database->query("select feature_text,feature_type,feature_require,feature_multiple,feature_graduation,feature_enable from cprice_features where ID_FEATURE=".$feat);
			$row = mysql_fetch_array($res);
			$featsT[$feat] = $row['feature_type'];
			$rs2 = $database->query("select * from cprice_feature_directory where ID_FEATURE=".$feat);
			mysql_select_db('shop');
			if($run>0)$database->query("insert into cprice_features (ID_FEATURE, feature_text,feature_type,feature_require,feature_multiple,feature_graduation,feature_enable) values (".$feat.",'".$row['feature_text']."',".$row['feature_type'].",".$row['feature_require'].",".$row['feature_multiple'].",".$row['feature_graduation'].",".$row['feature_enable'].")");
			while($r2 = mysql_fetch_array($rs2))
			{
				if($run>0)
				{
					$database->query("insert into cprice_feature_directory (ID_OLD, ID_FEATURE, featuredirectory_text) values (".$r2['ID_FEATURE_DIRECTORY'].",".$r2['ID_FEATURE'].",'".$r2['featuredirectory_text']."')",false);
				}
			}
		}
		//Добавление записей
		$goods = array();
		foreach($rubs as $rub)
		{
			mysql_select_db('bphold2');
			$res = $database->query("select ID_GOOD, good_url, rubricgood_pos from cprice_goods natural join cprice_rubric_goods where ID_RUBRIC=".$rub." && good_visible=1 && good_deleted=0");
			mysql_select_db('shop');
			while($row = mysql_fetch_array($res))
			{
				if($run>0)
				{
					if(!in_array($row['ID_GOOD'],$goods))$database->query("insert into cprice_goods (ID_GOOD, good_url, good_visible) values (".$row['ID_GOOD'].",'".$row['good_url']."',1)",false);
					$database->query("insert into cprice_rubric_goods (ID_RUBRIC, ID_GOOD, rubricgood_pos) values ($rub, ".$row['ID_GOOD'].",".$row['rubricgood_pos'].")",false);
				}
				if(!in_array($row['ID_GOOD'],$goods))$goods[]=$row['ID_GOOD'];
			}
		}
		print count($goods);
		//Добавление значений характеристик
		foreach($goods as $good)
		{
			mysql_select_db('bphold2');
			$res = $database->query("select ID_FEATURE,	goodfeature_value from cprice_goods_features where ID_GOOD=".$good);
			while($row = mysql_fetch_array($res))
			{
				$value = $row['goodfeature_value'];
				if(isset($featsT[$row['ID_FEATURE']]))
				switch($featsT[$row['ID_FEATURE']])
				{
					case 7:
						if($row['goodfeature_value']>0)
						{
							mysql_select_db('bphold2');
							list($txt) = $database->getArrayOfQuery("select text_text from cprice_texts where ID_TEXT=".$row['goodfeature_value']);
							if($run==-1)
							{
								mysql_select_db('shop');
								$database->query("insert into cprice_texts (text_text) values ('$txt')",false);
								$value = mysql_insert_id();
								$database->query("UPDATE cprice_goods_features SET goodfeature_value='".$value."' WHERE ID_GOOD=$good && ID_FEATURE=".$row['ID_FEATURE'],false);
							}
							if($run>0)
							{
								mysql_select_db('shop');
								$database->query("insert into cprice_texts (text_text) values ('$txt')",false);
								$value = mysql_insert_id();
							}
						}
					break;
					case 4:
						mysql_select_db('shop') or  die(mysql_error());
						if($row['goodfeature_value']>0)list($value) = $database->getArrayOfQuery("select ID_FEATURE_DIRECTORY from cprice_feature_directory where ID_OLD=".$row['goodfeature_value']);
					break;
				}
				if($run>0)
				{
					mysql_select_db('shop');
					$database->query("insert into cprice_goods_features (ID_GOOD,ID_FEATURE,goodfeature_value) values ($good, ".$row['ID_FEATURE'].",'".$value."')",false);
				}
			}
		}
		//Загрузка фото
		foreach($goods as $good)
		{
			mysql_select_db('bphold2');
			$res = $database->query("select goodphoto_file,goodphoto_desc,goodphoto_alt,goodphoto_pos from cprice_goods_photos where goodphoto_deleted=0 && ID_GOOD=".$good);
			mysql_select_db('shop');
			while($row = mysql_fetch_array($res))
			{
				if($run>0)
				{
					$database->query("insert into cprice_goods_photos (ID_GOOD,goodphoto_file,goodphoto_desc,goodphoto_alt,goodphoto_pos) values ($good, '".$row['goodphoto_file']."','".$row['goodphoto_desc']."','".$row['goodphoto_alt']."','".$row['goodphoto_pos']."')",false);
					copy(DATA_FLD."good_photo/".$row['goodphoto_file'],'/var/www/shop.bphold.ru/images/good_photo/'.$row['goodphoto_file']);
					copy(DATA_FLD."good_photo/trumb_".$row['goodphoto_file'],'/var/www/shop.bphold.ru/images/good_photo/trumb_'.$row['goodphoto_file']);
					copy(DATA_FLD."good_photo/image_".$row['goodphoto_file'],'/var/www/shop.bphold.ru/images/good_photo/image_'.$row['goodphoto_file']);
				}
			}
		}
	break;
	case 'ph_mop':
		$id = (int)$_GET['id'];
		$file = $_GET['file'];
		if(!empty($id) && !empty($file))
		{
			print '<b> ID '.$id.'</b><br/>';
			if(copy('http://www.irbismotors.ru/assets/drgalleries/'.$file,DATA_FLD."good_photo/".$id.'.jpg'))
			{
				teInclude("images");
				$goodphoto_file = $id.'.jpg';
				teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"image_",teGetConf('photo_mmaxw'),teGetConf('photo_mmaxh'));
				teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"trumb_",teGetConf('photo_tmaxw'),teGetConf('photo_tmaxh'));

				new_wm_image(DATA_FLD.'good_photo/'."image_".$goodphoto_file);
				new_wm_image(DATA_FLD.'good_photo/'.$goodphoto_file);

				$goodphoto_alt = getFeatureValue($id,26);
				$database -> query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD,goodphoto_desc,goodphoto_alt,goodphoto_file,goodphoto_pos) VALUES ($id, '', '$goodphoto_alt','$goodphoto_file',1)");

				print 'Файл скопировался <img src="http://moped02.ru/images/good_photo/'.$goodphoto_file.'" alt="" />';
			}else print 'Файл не скопировался';
		}
		die();
	break;
	case 'from_exc':
		teInclude("exc_read");
		$type = 4;
		$feats = array(26,27,28,73,74);

		$frm = new teForm("form1","post");
		print '<h2>Выгрузка каталога из Ексел-файла (мопед02.ру)</h2>';
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("file");
		if(!$frm->send())
		{
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('CP1251');
			$file = $_FILES["file"]['tmp_name'];
			$data->read($file);
			for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++)
			{
				@$id = $data->sheets[0]['cells'][$i][1];
				$name = $data->sheets[0]['cells'][$i][2];
				@$ed_izm = $data->sheets[0]['cells'][$i][3];
				if(!empty($name))
				{
					if(empty($id))
					{
                        print 'Рубрика '.$name.'<br/>';
						if($run>0){
							$database->query("INSERT INTO ".DB_PREFIX."rubric (rubric_parent,rubric_name,rubric_visible,rubric_type) VALUES(0,'".$name."','1',$type)");
							$rid = $database -> id();
							$k=1;
							foreach($feats as $feat)
							{
								$database -> query("INSERT INTO ".DB_PREFIX."rubric_features (ID_RUBRIC,rubric_type,ID_FEATURE,rubricfeature_pos) VALUES ($rid,$type,$feat,$k)");
								$k++;
							}
						}
					}else
					{
                        print $id.' '.$name.' ('.$ed_izm.')<br/>';
						/*
						$ch = curl_init("http://www.irbismotors.ru/2/".$id.".html");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						$out = curl_exec($ch);
						curl_close($ch);
						$ph_html = false;
						if($out!==false)$ph_html = explode("\n",$out);
						//$ph_html = file('http://www.irbismotors.ru/2/'.$id.'.html');
						if(is_array($ph_html))
						foreach ($ph_html as $line_num => $line) {
							$pos = strpos($line,'drgalleries');
							if($pos>0)
							{
								$line0 = substr($line,$pos+12);
								$file_name = substr($line,$pos+12,strpos($line0,'thumb'));
								$file_name = 'http://www.irbismotors.ru/assets/drgalleries/'.$file_name.'thumb_'.$id.'.jpg';
								print $file_name.' <img src="'.$file_name.'" alt="" /><br/>';
								break;
							}

						}*/
						if($run>0 && $rid>0)
						{
							$datav=array();
							$datav[26]=$name;
							$datav[73]=$id;
							$datav[74]=$ed_izm;
							insertData($rid,$datav);
						}
					}
				}
			}

		}
	break;
	case 'del_id':
		$frm = new teForm("form1","post");

		$frm->addf_text('txt', 'Список ИД', '',true);
		$frm->setf_require("txt");
		if(!$frm->send())
		{
			$txt = $frm->get_value("txt");
			$arr = explode(" ",$txt);
			foreach($arr as $item)
			{
				if($item>0)
				{
					print $item.'<br/>';
					if($run>0)
					{
						$database -> query("UPDATE ".DB_PREFIX."goods SET good_deleted=1 WHERE ID_GOOD=".$item, true, 3);
					}
				}
			}
		}
	break;
	case 'pars':
		teInclude("exc_read");
		print '<div style="min-width:400px;margin:0px auto;">';
		print '<h2>Парсинг файла ексел на существительные</h2>';
		$frm = new teForm("form1","post");
		$frm->addf_text('row', 'С какой строки начинать', '1');
		$frm->addf_text('col', 'Столбец', '1');
		$frm->addf_file("file", "Файл:");
	    $frm->addf_checkbox('excel','Сгенерировать файл ексел');
		$frm->setf_require("file");
		if(!$frm->send())
		{
			$col = $frm->get_value("col");
			$row = $frm->get_value("row");
			$data = new Spreadsheet_Excel_Reader();
			$data->setOutputEncoding('CP1251');
			$file = $_FILES["file"]['tmp_name'];
			$data->read($file);
			$words = array();
			$count = array();
			for ($i = $row; $i <= $data->sheets[0]['numRows']; $i++)
			{
				$str = $data->sheets[0]['cells'][$i][$col];
				$str = str_replace(array("(",")",'"',",")," ",$str);
				$arr_str = explode(" ",$str);
				foreach($arr_str as $item)
				{
					$item = trim($item);
					if(empty($item))continue;
					if(substr($item,0,1)>0 || strlen($item)<=2)continue;
					$item = mb_strtolower($item);
					if(substr($item,-3)=='ого' || substr($item,-3)=='его' || substr($item,-2)=='ей' || substr($item,-2)=='ее' || substr($item,-2)=='ое' || substr($item,-2)=='ые' || substr($item,-2)=='ие' || substr($item,-2)=='ых' || substr($item,-2)=='яя' || substr($item,-2)=='ый' || substr($item,-2)=='ий' || substr($item,-2)=='ия' || substr($item,-2)=='ий' || substr($item,-2)=='ая' || substr($item,-2)=='ой')continue;
					if(in_array($item,$words))$count[$item]++;
					else
					{
						$words[]=$item;
						$count[$item]=1;
					}
				}
			}
			asort($words);$i=1;
			if(isset($_POST['excel']))
			{
				$s1 = ob_get_contents();
				ob_end_clean();
				unset($s1);
				// библиотека ексель
				teInclude("excel");
				$workbook = new Spreadsheet_Excel_Writer();
				$workbook->send("catalog.xls");
			   	$worksheet =& $workbook->addWorksheet('Каталог товаров');
				$num=0;
				$worksheet->setColumn(0,0,12.0);
				$worksheet->setColumn(0,1,50.0);
				$worksheet->setColumn(0,2,10.0);

				$frmt = & $workbook->addFormat();
				$frmt->setBold();
				$frmt->setBorder(1);
				$frmt->setAlign('center');
				$frmt->setVAlign('vcenter');
				$frmt->setSize(10);
				$frmt->setTextWrap();
				$worksheet->write($num, 0, 'N',$frmt);
				$worksheet->write($num, 1, 'Слово',$frmt);
				$worksheet->write($num++, 2, 'Кол-во',$frmt);
				unset($frmt);
				$frmt = & $workbook->addFormat();
				$frmt->setBorder(1);
				$frmt->setAlign('left');
				$frmt->setVAlign('vcenter');
				$frmt->setTextWrap();
				foreach($words as $word)
				{
					$worksheet->write($num, 0, $i++,$frmt);
					$worksheet->write($num, 1, $word,$frmt);
					$worksheet->write($num++, 2, $count[$word],$frmt);
				}
			    $workbook->close();
			    exit;
			}
			else
			{
				print '<table border="1">';
				print '<tr><th>N</th><th>Слово</th><th>Кол-во</th></tr>';
				foreach($words as $word)print '<tr><td>'.$i++.'</td><td>'.$word.'</td><td>'.$count[$word].'</td></tr>';
				print '</table>';
			}
		}
		print '</div>';



	break;
	case 'delmails':
		$mails = 'islamova_am@rambler.ru
greenalex67@mail.ru
2664770@mail.ru
murtazin.shamil@gmail.com
mahmut_ks@mail.ru
89289091822@mail.ru
cupidon@list.ru
anna-aziay@mail.ru
silaufa@mail.ru
paris-na-volge@yandex.ru
mobservis@yandex.ru
pawellcom@inbox.ru
CDTJ@yandex.ru
azis1234@mail.ru
giv21@mail.ru
mnpospektr@mail.ru
zakaz@dessert-ufa.ru';
		$arr = explode("\n",$mails);
		foreach($arr as $mail)
		{			print $mail.'<br/>';
			if($run>0) mysql_query("delete FROM `mails` WHERE `email`='$mail'");		}
	break;	case 'icq':
error_reporting (E_ALL);
set_time_limit(0);
ini_set ('max_execution_time', "0");
teInclude("icq");
define('UIN', '596300545');
define('PASSWORD', '274UniS');//

$icq = new WebIcqLite();
if(!$icq->connect(UIN, PASSWORD))
{
	echo $icq->error;
	exit();
}
if($icq->is_connected())
{	$icq->send_message('222811798', 'привет как дела?');}
print 'ok';
/*
while(){
	$msg = $icq->read_message();
	if ($msg) {

	}
	flush();
	sleep(1);
}
*/
	break;
	case 'testimg':
		teInclude("images");
		new_wm_image(DATA_FLD.'good_photo/Ducale_MG.jpg');
		print 'OK';
	break;
	case 'ubemail':
		$input = array();
		$input[19][377]=array(624,625);
		$input[19][247]=array(364,362);
		$input[19][350]=array(377,373);
		$input[19][336]=array(552,551);
		$input[19][239]=array(377,373);
		$input[19][245]=array(377,373);
		$input[21][287]=array(439,440);
		$input[21][286]=array(437,434);
		$input[5][155]=array(446,443);
		$input[5][134]=array(319,317);
		$input[5][161]=array(459,456);
		$input[5][162]=array(472,466);
		$input[16][44]=array(143,102);
		$input[45][13]=array(41,9);
		$input[3][364]=array(495,492);
		$input[3][359]=array(460,459);
		$input[60][121]=array(76,74);
		$input[60][13]=array(43,41);
		$input[59][37]=array(69,68);
		$input[59][148]=array(122,95);
		$input[59][150]=array(181,154);
		$input[59][149]=array(150,123);
		$input[59][198]=array(240,239);
		$input[59][152]=array(69,68);
		$input[59][151]=array(69,68);
		$input[69][32]=array(69,67);
		$input[27][85]=array(5,217);
		$input[36][6]=array(27,25);
		$input[68][14]=array(52,50);
		$input[68][13]=array(47,45);
		$input[38][335]=array(496,538);
		$input[10][136]=array(398,396);
		$input[49][184]=array(848,845);
		$input[18][1000000013]=array(405,404);
		$input[18][1302490]=array(315,313);
		$input[18][1000000000]=array(350,337,338,339);
		$input[35][6]=array(12,10);
		$input[61][21]=array(56,54);
		$input[50][25]=array(26,7);
		$input[22][338]=array(550,548);
		$input[22][336]=array(541,540);
		$input[22][318]=array(496,493);
		$input[48][9]=array(30,28);
		$input[20][370]=array(470,469);
		$input[20][374]=array(477,476);
		$input[20][373]=array(474,473);
		$input[20][365]=array(457,449);
		$input[40][374]=array(657,656);
		$input[40][373]=array(555,553);
		$input[40][404]=array(674,671);
		$input[40][338]=array(550,548);
		$input[23][328]=array(540,539);
		$input[23][325]=array(523,524);
		$input[23][327]=array(536,534);
		$input[17][146]=array(229,391);
		$input[17][108]=array(229,226);
		$input[23][46]=array(277,276);
		$input[23][37]=array(245,243);
		$input[51][40]=array(73,71);
		$input[51][39]=array(69,67);
		$input[51][26]=array(30,28);
		$input[70][11]=array(27,25);
		$input[72][16]=array(58,56);
		$input[72][15]=array(53,51);
		$input[73][14]=array(51,49);
		$input[74][12]=array(51,41);
		$input[74][14]=array(51,49);
		$input[76][25]=array(16,14);
		
		$no_emails = array('galinryst@yandex.ru','elzamazitova@yandex.ru','a4lm10@yandex.ru','ilyastrizh@yandex.ru','elvira-faskhetdinova@yandex.ru','geg80@yandex.ru','ann-es@yandex.ru','natabr675@yandex.ru','coder@ufapr.ur','coder@ufapr.ru');
		$emails = array();
		foreach($input as $bid=>$rubs)
		{
			if(isset($hosts[$bid]['db_name']))
			{
			print '<br/>'.$bid.' '.$hosts[$bid]['db_name'].'<br/>';
			foreach($rubs as $rub=>$feats)
			{				otherbase($bid);
				$data = getData($rub,'','',$feats,true);
				foreach($data as $gid=>$vals)
				{
					array_shift($vals);
					$email = mb_strtolower(trim(array_shift($vals)));
					if(!in_array($email,$emails) && !in_array($email,$no_emails) && !empty($email) && eregi("^[a-z0-9_-]+[a-z0-9_.-]*@[a-z0-9_-]+[a-z0-9_.-]*\.[a-z]{2,5}$",$email))
					{						
						$emails[]=$email;
						$name = trim(implode(" ",$vals));
						print $email.'('.$name.'); ';
						if($run>0)
						{	
							otherbase(19);
							@mysql_query("insert into mails (email,name) values ('$email','$name')");													
						}										
					}
				}			
				
			}
			}
		}
		print '<br/><br/>OK: '.count($emails);
	break;
	case 'prices':
		$type = 2;$n = 0;
       	$res = $database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_type=".$type);
		while($row=mysql_fetch_row($res))
		{
			$gid = $row[0];
			$data = array();
/*			$datav = getDataId($gid,array(12,48,59,60),true);
			if(($datav[48]>0 || $datav[59]>0) && (empty($datav[12]) || empty($datav[60])))
			{
				$plosch = empty($datav[48])?$datav[59]:$datav[48];
				if($datav[12]>0 && empty($datav[60])) $datav[60] = round($datav[12]/$plosch,1);
				if($datav[60]>0 && empty($datav[12])) $datav[12] = round($datav[60]*$plosch);
				$data[12] = $datav[12];
				$data[60] = $datav[60];
				if($run>0) updateData($gid,$data);
				$n++;
			}*/
			$datav = getDataId($gid,array(39,41,48,59,73),true);
				$plosch = !empty($datav[48])?$datav[48]:(!empty($datav[59])?$datav[59]:$datav[73]);
				$data[10] = $datav[41].'; '.$plosch.' ID:'.$datav[39];
				print $data[10].'<br/>';
				if($run>0) updateData($gid,$data);
				$n++;
		}
		print $n.' ОК ';
	break;
	case 'upd_rub':
		$type = 14;
       	$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_type=".$type);
		while($row=mysql_fetch_row($res))
		{
			$n = intval(substr($row[1],0,1));
			$new_text = ($n+1).substr($row[1],1);
			print $new_text.'<br/>';
			if($run>0) $database->query("update cprice_rubric set rubric_name='$new_text' where ID_RUBRIC=".$row[0],false);
		}
	break;
	case 'upd_org':
		$old = 'Бессер+';
		$new = 'Интерстройсервиc';
		$res = $database->query("select * from cprice_texts where text_text regexp '$old'");
		while($row=mysql_fetch_row($res))
		{			$new_text = str_replace($old,$new,$row[1]);
			print $new_text.'<br/><br/>';
			if($run>0) $database->query("update cprice_texts set text_text='$new_text' where ID_TEXT=".$row[0],false);		}
	break;
	case 'rest_feats':		
		//Восстановление удаленных характеристики
		$type = 2;$n = 0;
                $res = $database->query("select ID_RUBRIC, rubric_parent from cprice_rubric where rubric_deleted=0 && rubric_parent<>0 && rubric_visible=1 && rubric_type=".$type);                
		while($row=mysql_fetch_row($res))
		{
			$rid = $row[0];
			$prnt = $row[1];
                        $feats_exist = array();
                        $res0 = $database->query("select ID_FEATURE from cprice_rubric_features where ID_RUBRIC=".$rid);
                        while ($row0 = mysql_fetch_array($res0)) {
                                $feats_exist[] = $row0[0];
                        }
                        $res2 = $database->query("select ID_FEATURE from cprice_goods_features natural join cprice_rubric_goods where ID_RUBRIC=".$rid.(count($feats_exist)>0?' && ID_FEATURE NOT IN ('.  implode(' , ', $feats_exist).')':''));
                        while ($row2 = mysql_fetch_array($res2)) {
                                $feat = $row2[0];
                                echo ' '.$feat.'|'.$rid;
                                if(!in_array($feat, $feats_exist) && $run>0)
                                {
                                        addFeature($type, $rid, $feat);
                                        $feats_exist[] = $feat;
                                        $n++;
                                }
                        }
		}
		print ' '.$n.' ОК ';
	break;	
	case 'rem_goods':
		
		//Удаление записей восстановленныых случайно
		$type = 2;$n = 0;
      $res = $database->query("select cprice_goods.ID_GOOD,ID_CHANGE from cprice_rubric_goods natural join cprice_rubric natural join cprice_goods inner join cprice_changes on cprice_changes.change_row=cprice_goods.ID_GOOD where good_deleted=0 && rubric_deleted=0 && rubric_visible=1 && rubric_type=".$type." && change_type=3 && `change_table`= 'cprice_goods' && `change_dt` LIKE '2013-12-02%'");
		while($row=mysql_fetch_row($res))
		{
			$gid = $row[0];
			$zap = $row[1];
			//list($zap) = $database->getArrayOfQuery("SELECT ID_CHANGE FROM `cprice_changes` WHERE change_type=3 && `change_table`= 'cprice_goods' && `change_dt` LIKE '2013-12-02%'");
			if($zap>0 && $gid>0)
			{
				print $gid.'<br/>';
				if($run>0) $database->query("UPDATE ".DB_PREFIX."goods SET good_deleted=1 WHERE ID_GOOD=".$gid);
				$n++;
			}
		}
		print $n.' ОК ';
	break;	
	case 'add_vals2':
		
		//Добавление пустых записей в cprice_goods_features
		$type = 2;$n = 0;
		$fid = 370;
      $res = $database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_rubric natural join cprice_goods where good_deleted=0 && rubric_deleted=0 && rubric_visible=1 && rubric_type=".$type);
		while($row=mysql_fetch_row($res))
		{
			$gid = $row[0];
			list($zap) = $database->getArrayOfQuery("SELECT ID_GOOD_FEATURE from cprice_goods_features where ID_FEATURE=$fid && ID_GOOD=".$gid);
			if(empty($zap))
			{
				$data = array();
				$data[$fid] = '0';
				print $gid.'<br/>';
				if($run>0) updateData($gid,$data);
				$n++;
			}
		}
		print $n.' ОК ';
	break;
	case 'add_vals':
		$data = getData(218, '', '', array(883),true);
		foreach($data as $gid=>$vals)
		{
			print $gid.': '.$vals[883].'; ';
			if($run>0)
			{
				$val = 10789;
				switch($vals[883])
				{
					case 1: $val = 9255; break;
					case 3: $val = 8931; break;
				}
				setFeatData2(883,$gid,$val,true);
			}
		}
		if($run>0)
		{			/*для счетов уфапр*/
			$database->query("update cprice_goods set main_org=9254 where main_org=1",false);
			$database->query("update cprice_feature_directory set main_org=9254 where main_org=1",false);
			$database->query("update cprice_goods set main_org=8930 where main_org=2",false);
			$database->query("update cprice_feature_directory set main_org=8930 where main_org=2",false);
			$database->query("delete from cprice_configtable where var_name like 'main_org_%'",false);
			$database->query("delete from cprice_configtable where var_name like 'params_%'",false);
			$database->query("delete from cprice_configtable where var_name like 'prms%'",false);
		}
	break;
	case 'zaprosy2':
		print '<div align="center">';
		$frm = new teForm("form1","post");
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("file");
		if(!$frm->send())
		{
			$file = $_FILES["file"]['tmp_name'];
			$farr = file($file);
			foreach($farr AS $i => $cont){
				$line = explode("\t", $cont);
				$line[1] = trim($line[1]);
				if(is_numeric($line[0]) && !empty($line[1]) && $line[1]!='удалить' && $line[1]!='без ИД' && $line[1]!='???')
				{
					$price = (int)@$line[4];
					//if(!empty($price))$price=substr(trim($price),0,-3);
					print $line[0].' '.$line[1].' '.$price.'<br>';
					if($run>0 && $price>0)
					{	/*
						$arr = explode(",",$line[1]);
						if(count($arr)>1)
						{
							$arr2 = explode(".",$arr[0]);
							$line[1] = $arr2[1];
						}
						$database->query("update cprice_goods set id_1c='$line[1]' where ID_GOOD=".$line[0],false);
						*/
						setFeatData2(1204,$line[0],$price,true);

						/*
						if(!empty($price))
						{
							setFeatData2(1084,$line[0],$price,true);
							del_cache(0,$line[0]);
						}
						*/
					}

				}
			}
		}
		print '</div>';

	break;
	case 'mfile':
		print '<div align="center">';
		$frm = new teForm("form1","post");
		$frm->addf_file("file", "Файлы:", "", 10*1024*1024, $folder='../uploads/');
		$frm->setFieldMultiple("file");
		if(!$frm->send())
		{			$files = $frm->move_file("file");			if($files)print_r($files);			print 'Готово';
		}
		print '</div>';

	break;
	case 'unix_date':
		print '<div align="center">';
		$frm = new teForm("form1","post");
		$frm->addf_text("file", "Дата:",date("d.m.Y H:i:s"));
		$frm->setf_require("file");
		if(!$frm->send())
		{			$item = $frm->get_value("file");
			$time = mktime(substr($item,-8,2),substr($item,-5,2),substr($item,-2),substr($item,3,2),substr($item,0,2),substr($item,6,4));
			print $time.' '.date("d.m.Y H:i:s",$time);
		}
		print '</div>';

	break;
	case 'update_data':
		print '<div align="center">';
		$frm = new teForm("form1","post");
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("file");
		if(!$frm->send())
		{
			$file = $_FILES["file"]['tmp_name'];
			$farr = file($file);
            $feats = array();$n_f = 0; $j=$n_f;
            $begin=true;$break = false;$val = '';
			setlocale(LC_ALL, 'en_US.UTF8');
			foreach($farr AS $i => $cont){
				$line = explode("\t", $cont);
				if($i==0)
				{					$feats = $line;
					array_shift($feats);
					$n_f = count($feats);
					$j=$n_f;				}
				else
				{					if($begin)
					{
						$gid = $line[0];
						print $line[0].': ';
						$begin=false;
						$j=1;$k_feat = 0;
					}else $j=0;
					for(;$k_feat<$n_f;$j++)
					{
						if(isset($line[$j]))
						{
							/*$line[$j] = iconv("UTF-8","Windows-1251//TRANSLIT//IGNORE",$line[$j]);*/
							if($k_feat!=($n_f-1) && !isset($line[$j+1]))
							{								$break=true;
								$val .= trim($line[$j]).' <br/>';							}
							else
							{								$vals = trim($line[$j]);
								if($break)
								{									$vals = substr($val.$vals,1,-1);
									$vals = str_replace('""','"',$vals);
									$break=false;
									$val='';								}
								//$vals = str_replace("'","\'",$vals);
								$feat = $feats[$k_feat];
								print $feat.' / '.$vals.'; ';
								if($run>0 && !empty($vals)) setFeatData2($feat,$gid,$vals,true,true);
								$k_feat++;
							}
						}else
						{							break;						}
					}
					if($k_feat==$n_f)
					{
						$begin = true;
						print " | <br/>";
					}				}
			}
		}
		print '</div>';

	break;
	case 'price_from_1c':
		print '<div align="center">';
		$frm = new teForm("form1","post");
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("file");
		if(!$frm->send())
		{
			$file = $_FILES["file"]['tmp_name'];
			$farr = file($file);
            $n=0;
			foreach($farr AS $i => $cont){
				$line = explode(";;", $cont);
				if(count($line)==4)
				{
					if(!empty($line[2]) || !empty($line[1]))
					{
						list($gid) = $database->getArrayOfQuery("select ID_GOOD from cprice_goods_features where ID_FEATURE=633 && goodfeature_value like '$line[0]'");
						print $line[0].' ('.$gid.'): '.$line[2]." | ";
						if($run>0 && $gid>0)
						{
							if(!empty($line[2]))setFeatData2(33,$gid,$line[2],true);
							elseif(!empty($line[1]))setFeatData2(33,$gid,$line[1],true);
							if($line[3]>0)setFeatData2(634,$gid,$line[3],true);
						}
						if($gid>0)$n++;
					}
				}
			}
			print '<br>All: '.$n;
		}
		print '</div>';

	break;
	case 'add_id_1c':
		print '<div align="center">';
		$frm = new teForm("form1","post");
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("file");
		if(!$frm->send())
		{
			$file = $_FILES["file"]['tmp_name'];
			$farr = file($file);

			foreach($farr AS $i => $cont){
				$line = explode("\t", $cont);
				if($line[0]>0 && !empty($line[1]))
				{					print $line[0].': '.$line[1]." | ";
					if($run>0) setFeatData2(633,$line[0],trim($line[1]),true);				}
			}
		}
		print '</div>';
	break;
	case 'photo_error':
		$res = $database->query("SELECT ID_GOOD,goodphoto_file, ID_GOOD_PHOTO from ".DB_PREFIX."goods_photos order by ID_GOOD, goodphoto_file, ID_GOOD_PHOTO");
		$old_id = 0; $old_photo = '';
		$dir = 'good_photo/';
		while($row = mysql_fetch_array($res))
		{			if($old_id==$row[0] && $old_photo==$row[1])
			{				print $row[0].' '.$old_photo.' | ';
				if($run>0) $database->query("DELETE from ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=".$row[2]);			}
			else
			{				$old_id=$row[0];
				$old_photo=$row[1];
				if(!file_exists(DATA_FLD.$dir.$old_photo))
				{					print $row[0].' '.$old_photo.' || ';
					if($run>0) $database->query("DELETE from ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=".$row[2]);
				}			}		}
	break;
	case 'upd_feat':
	  $rubrics = array(22,23,24,25,26,27,28,29,30,31,32);
	  foreach($rubrics as $rubric_id)
	  {
		$data = getData($rubric_id, 'ID_GOOD', '', array(44),true);
		$goods = array();
		foreach($data as $good_id=>$vals)
		{
	        print_r ($vals);
	        print '<br/>';
	        if($run>0 && empty($vals[44]))
	        {
				setFeatData2(44,$good_id,'руб',true);
	        }
	        $i++;
		}
	  }
	break;
	case 'cat_to_exl2':
		define("KURS",	39.7937);
		$s1 = ob_get_contents();
		ob_end_clean();
		unset($s1);
		// библиотека ексель
		teInclude("excel");
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->send("catalog.xls");
	   	$worksheet =& $workbook->addWorksheet('Каталог товаров');
		$num=0;
		$worksheet->setColumn(0,0,12.0);
		$worksheet->setColumn(0,1,50.0);
		$worksheet->setColumn(0,2,10.0);
		$worksheet->setColumn(0,3,12.0);

		$frmt = & $workbook->addFormat();
		$frmt->setBold();
		$frmt->setBorder(1);
		$frmt->setAlign('center');
		$frmt->setVAlign('vcenter');
		$frmt->setSize(10);
		$frmt->setTextWrap();
		$worksheet->write($num, 0, 'Артикул',$frmt);
		$worksheet->write($num, 1, 'Название',$frmt);
		$worksheet->write($num, 2, 'Ед.',$frmt);
		$worksheet->write($num++, 3, 'Цена',$frmt);
		unset($frmt);
		$frmt = & $workbook->addFormat();
		$frmt->setBorder(1);
		$frmt->setAlign('left');
		$frmt->setVAlign('vcenter');
		$frmt->setSize(9);
		$frmt->setTextWrap();
		$frmt2 = & $workbook->addFormat();
		$frmt2->setBold();
		$frmt2->setBorder(1);
		$frmt2->setAlign('left');
		$frmt2->setVAlign('vcenter');
		$frmt2->setSize(9);
		$frmt2->setTextWrap();
		$frmt3 = & $workbook->addFormat();
		$frmt3->setBorder(1);
		$frmt3->setAlign('right');
		$frmt3->setVAlign('vcenter');
		$frmt3->setSize(9);
		$frmt3->setTextWrap();
        $type = 2;
        function rubs($type,$parent,$pref='')
        {
        	global $database,$worksheet,$frmt,$frmt2,$frmt3,$num;
        	$fname = 31;
        	$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_parent=$parent && rubric_type=".$type." order by rubric_pos");
        	while($row = mysql_fetch_array($res))
        	{
       			$worksheet->write($num, 0, "",$frmt);
       			$worksheet->write($num, 1, $pref.$row[1],$frmt2);
       			$worksheet->write($num, 2, "",$frmt);
       			$worksheet->write($num++, 3, "",$frmt);
            	$data = getData($row[0], 'ID_GOOD', '', array($fname,33,242,633),true);
            	if(count($data)>0)
            	{
            		foreach($data as $gid=>$vals)
            		{
						$price = $vals[33];
						if(substr($price,0,3)=='EUR')
						{
							$price = str_replace(array(","," "," "),array(".","",""),substr($price,3));
							$price = sprintf("%01.2f",KURS*$price);
							$price = str_replace(".",",",$price);
						}
						$worksheet->writeString($num, 0, $vals[633],$frmt);
						$worksheet->write($num, 1, $vals[$fname],$frmt);
						$worksheet->write($num, 2, $vals[242],$frmt);
						$worksheet->write($num++, 3, $price,$frmt3);
            		}
            	}
            	rubs($type,$row[0],$pref.'    ');
        	}
        }
        rubs($type,0);
	    $workbook->close();
	    exit;
	break;
	case 'cat_to_exl':
		$s1 = ob_get_contents();
		ob_end_clean();
		unset($s1);
		// библиотека ексель
		teInclude("excel");
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->send("catalog.xls");
	   	$worksheet =& $workbook->addWorksheet('Каталог товаров');
		$num=0;
		$worksheet->setColumn(0,0,7.0);
		$worksheet->setColumn(0,1,50.0);
		$worksheet->setColumn(0,2,10.0);

		$frmt = & $workbook->addFormat();
		$frmt->setBold();
		$frmt->setBorder(1);
		$frmt->setAlign('center');
		$frmt->setVAlign('vcenter');
		$frmt->setSize(10);
		$frmt->setTextWrap();
		$worksheet->write($num, 0, 'ИД',$frmt);
		$worksheet->write($num, 1, 'Название',$frmt);
		$worksheet->write($num++, 2, 'Цена, руб',$frmt);
		unset($frmt);
		$frmt = & $workbook->addFormat();
		$frmt->setBorder(1);
		$frmt->setAlign('left');
		$frmt->setVAlign('vcenter');
		$frmt->setSize(10);
		$frmt->setTextWrap();
        $type = 15;
        function rubs($type,$parent,$pref='')
        {        	global $database,$worksheet,$frmt,$num;
        	$fname = 498;
        	$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_parent=$parent && rubric_type=".$type." order by rubric_pos");
        	while($row = mysql_fetch_array($res))
        	{       			$worksheet->setMerge($num, 0, $num, 2);
       			$worksheet->write($num, 0, $pref.$row[1],$frmt);
       			$worksheet->write($num, 1, "",$frmt);
       			$worksheet->write($num++, 2, "",$frmt);
            	$data = getData($row[0], 'ID_GOOD', '', array($fname));
            	if(count($data)>0)
            	{            		foreach($data as $gid=>$vals)
            		{						$worksheet->write($num, 0, $gid,$frmt);
						$worksheet->write($num, 1, $vals[$fname],$frmt);
						$worksheet->write($num++, 2, "",$frmt);
            		}            	}
            	rubs($type,$row[0],$pref.'>>');        	}        }
        rubs($type,0);
	    $workbook->close();
	    exit;
	break;
	case 'num_goods':
		$arr = array('x','y','z');
		for($i=0;$i<10;$i++)
		{			print $i.' | ';
			foreach($arr as $item)
			{				if($i>5)break;
				print $item.' | ';
			}		}
		$type = 2;
		list($num) = $database->getArrayOfQuery("select count(*) from cprice_rubric_goods natural join cprice_goods natural join cprice_rubric where rubric_type='$type' && rubricgood_deleted=0 && good_deleted=0 && rubric_deleted=0 group by ID_GOOD");
		$res = $database->query("select * from cprice_rubric_goods natural join cprice_goods natural join cprice_rubric where rubric_type='$type' && rubricgood_deleted=0 && good_deleted=0 && rubric_deleted=0 group by ID_GOOD");
		print $num.' '.mysql_num_rows($res);

	break;
	case 'sort':
		$rubric_id=13;
		$data = getData($rubric_id, 'ID_GOOD', '', array(),true);
		$goods = array();
		foreach($data as $good_id=>$vals)
		{	        $goods[]=$good_id;		}
		$n = count($goods);$i=1;
		foreach($data as $good_id=>$vals)
		{
	        print_r ($vals);
	        print '<br/>';
	        if($run>0)
	        {	        	foreach($vals as $fid=>$val)
	        	{	        		$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$val."' WHERE ID_FEATURE=".$fid." && ID_GOOD=".$goods[$n-$i]);	        	}	        }
	        $i++;
		}
	break;
	case 'slesh':
		$res = $database->query("select * from cprice_goods_features where goodfeature_value regexp '\"'");
		$i=1;
		while($row = mysql_fetch_array($res))
		{
			print $i++.'--- '.$row['goodfeature_value'].' '.stripslashes($row['goodfeature_value']).'<br/><br/>';
			if($run>0)$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".stripslashes($row['goodfeature_value'])."' WHERE ID_GOOD_FEATURE='".$row[0]."'",false);
		}
		$res = $database->query("select * from cprice_texts where text_text regexp '\"'");
		$i=1;
		while($row = mysql_fetch_array($res))
		{
			print $i++.'--- '.$row['text_text'].' '.stripslashes($row['text_text']).'<br/><br/>';
			if($run>1)$database->query("UPDATE ".DB_PREFIX."texts SET text_text='".stripslashes($row['text_text'])."' WHERE ID_TEXT='".$row[0]."'",false);
		}

	break;
	case 'eko_news':
		$res = $database->query("select * from news order by id");
		$rid = 13;$i=1;
		while($row = mysql_fetch_array($res))
		{
			$data = array();
			$data[37]=strip_tags($row['title']);
			$data[39]=str_replace(array("http://www.eko-rb.ru/","http://eko-rb.ru/"),"/",$row['content']);
			$arr = explode("-",$row['date']);
			$data[40]=$arr[2].'.'.$arr[1].'.'.$arr[0];
			print $i++.'--- '.$data[37].'<br/>'.$data[39].'<br/><br/>';
			if($run>0)insertData($rid,$data);
		}

	break;
	case 'recov':
		$type = 2;$n = 0;$fname = 1;
      $res = $database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_type=".$type);
		while($row=mysql_fetch_row($res))
		{
			$gid = $row[0];
			list($change_id) = $database->getArrayOfQuery("select ID_CHANGE from cprice_changes where change_table='cprice_goods' && change_row='$gid' && change_type=2 && change_dt<'2013-09-27 15:26:50' order by ID_CHANGE desc limit 1");
			if($change_id>0)
			{
				list($changes) = $database->getArrayOfQuery("select old_values from cprice_changes where change_table='cprice_goods_features' && ID_GOOD='$change_id' && change_type=2 && old_values like '{$gid}$|$1$|$%' order by ID_CHANGE desc limit 1");
				if(!empty($changes))
				{
					$arr = explode('$|$', $changes);
					if(!empty($arr[2]))
					{
						$data = array();
						$data[$fname] = $arr[2];
						print $gid.' '.$arr[2].'<br/>';
						if($run>0)
						{
							$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$arr[2]."' WHERE ID_FEATURE='1' AND ID_GOOD='".$gid."'", false);
						}
					}

				}
			}
		}
		print $n.' ОК ';
	break;
	case 'upd_names':
		$type = 2;$n = 0;$fname = 1;
      $res = $database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_type=".$type);
		while($row=mysql_fetch_row($res))
		{
			$gid = $row[0];
			$datav = getDataId($gid,array($fname),true,TRUE);
			if(empty($datav[$fname]))
			{
				$data = array();
				$data[$fname] = $datav['url'];
				print $gid.' '.$datav['url'].'<br/>';
				if($run>0) updateData($gid,$data);
				$n++;
			}
		}
		print $n.' ОК ';
	break;
	case 'spec':
		$fname = 1;
		$res=$database->query("select ID_GOOD, good_url from cprice_goods where good_url<>''");
		while($row = mysql_fetch_array($res))
		{
			$url = preg_replace("/[^a-z0-9-_]/i","",$row[1]);
			$url2 = "";$old="";
			for($i=0;$i<strlen($url);$i++)
			{
				if($url[$i]=='_')
				{
					if($old!=$url[$i] && $i>0)
					{
						$url2.= $url[$i];
						$old='_';
					}
					if($i==0)$old='_';
				}else
				{
					$old= '';
					$url2.= $url[$i];
				}
			}
			if(substr($url2,-1)=='_')$url2=substr($url2,0,-1);
			print $row[0].' '.$row[1].' '.$url2.'<br/>';
			if($run>0 && $url2!=$row[1])$database->query("UPDATE ".DB_PREFIX."goods SET good_url='".$url2."' WHERE ID_GOOD='".$row[0]."'",false);		}
	break;
	case 'eko_kons_date':
		$res = $database->query("select * from guest_ekorb");
		$rid = 152;
		while($row = mysql_fetch_array($res))
		{
			$date = $row['date'];
			$msg = $row['message'];
			list($txt_id) = $database->getArrayOfQuery("select ID_TEXT from cprice_texts where text_text like '$msg'");
			if($txt_id>0)
			{				list($id) = $database->getArrayOfQuery("select ID_GOOD from cprice_goods_features where ID_FEATURE=71 && goodfeature_value='$txt_id'");
				if($id>0)
				{					print $id.' '.$msg.'<br><br>';					if($run>0)
					{						$database->query("UPDATE cprice_changes set change_dt='$date' where change_table='cprice_goods' && change_type=1 && change_row=$id",false);					}				}			}

		}

	break;
	case 'eko_kons':
		$res = $database->query("select * from guest_ekorb where id>3588 order by id");
		$rid = 152;$i=3297;
		while($row = mysql_fetch_array($res))
		{			$data = array();
			$data[68]=$row['name'];			$data[69]=$row['mail'];
			$data[71]=$row['message'];
			$data[72]=$row['answer'];
			$data[218]=$i++;
			print $row['name'].'<br/>'.$row['message'].'<br/><br/>';
			if($run>0)insertData($rid,$data,$row['publish']);
		}

	break;
	case 'file_1c':
		print '<div style="min-width:400px;margin:0px auto;">';
		print '<h2>Анализ данных из файла 1С</h2>';
		$frm = new teForm("form1","post");
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("file");
		if(!$frm->send())
		{
			$file = $_FILES["file"]['tmp_name'];
			$zip = @zip_open($file);
			$notzip = true;

			if ($zip) {
				$farr = array();
				while ($zip_entry = @zip_read($zip)) {
					if (zip_entry_open($zip, $zip_entry, "r")) {
						$txt = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
						$farr = array_merge($farr,explode("\n", $txt));
						zip_entry_close($zip_entry);
						$notzip=false;
					}
				}
			}
			if($notzip){
				$farr = file($file);
	        }

			$data = array();$id = 0;
			foreach($farr AS $i => $cont){
				$line = explode("=", $cont);
				if(isset($line[1]))$line[1] = trim($line[1]);
				switch($line[0])
				{
					case 'Номер':
						$id = $line[1];
						if($notzip)$data[$id]['client'] = 'АЛЬФА-БАНК';
						else $data[$id]['client'] = 'СБЕРБАНК';
					break;
					case 'ДатаСписано':
					case 'Дата':
					case 'ДатаПоступило':
					case 'Сумма':
					case 'НазначениеПлатежа':
						$data[$id][$line[0]] = $line[1];
					break;
					default:

						$type = substr($line[0],0,10);
						if(($type=='Плательщик' || $type=='Получатель') && $id>0)
						{							$param = substr($line[0],10);
							switch($param)
							{								case 'Счет':
									$data[$id][$type.'РасчСчет'] = $line[1];
								break;
								case '1':
									$data[$id][$type.'Имя'] = $line[1];
								break;
								case 'Банк1':
									$data[$id][$type.'Банк'] = $line[1];
								break;
								case 'Банк2':
									if(!empty($line[1]))$data[$id][$type.'Банк'] .= ' '.$line[1];
								break;
								default:
									if(empty($param)) $data[$id][$type.'Имя'] = $line[1];
									else $data[$id][$type.$param] = $line[1];
								break;
							}						}
					break;				}
			}
			print '<table border="1">';
			print '<tr><th>№</th><th>Клиент-банк</th><th>№ пор.</th><th>Дата</th><th>Сумма</th><th width="170">Плательщик</th><th>Б.счет плательщика</th><th width="170">Получатель</th><th>Б.счет получателя</th><th>Назначение платежа</th></tr>';
			$i=0;
			foreach($data as $num => $vals)
			{				$data_input = array();
            	$data_input[891] = $vals['client'];            	$data_input[892] = $num;
            	$data_input[893] = $vals['Дата'];
            	$data_input[894] = $vals['Сумма'];
            	$name_org = $vals['ПлательщикИмя'];
            	$inn_org = $vals['ПлательщикИНН'];
            	list($org_id) = $database->getArrayOfQuery("select ID_GOOD from cprice_goods_features natural join cprice_goods where ID_FEATURE=680 && goodfeature_value='$inn_org' && good_deleted=0 limit 1");
            	$data_input[895] = intval($org_id);
            	$rc = $vals['ПлательщикРасчСчет'];
            	list($rc_id) = $database->getArrayOfQuery("select ID_GOOD from cprice_goods_features natural join cprice_goods where ID_FEATURE=688 && goodfeature_value='$rc' && good_deleted=0 limit 1");
            	$plat_id = 0;
            	if(!empty($rc_id)) list($plat_id) = $database->getArrayOfQuery("select goodfeature_value from cprice_goods_features where ID_FEATURE=691 && ID_GOOD='$rc_id' limit 1");
            	$data_input[896] = intval($rc_id);
            	$inn_org = $vals['ПолучательИНН'];
            	list($org_id) = $database->getArrayOfQuery("select ID_GOOD from cprice_goods_features natural join cprice_goods where ID_FEATURE=680 && goodfeature_value='$inn_org' && good_deleted=0 limit 1");
            	$data_input[897] = intval($org_id);
            	$rc = $vals['ПолучательРасчСчет'];
            	list($rc_id) = $database->getArrayOfQuery("select ID_GOOD from cprice_goods_features natural join cprice_goods where ID_FEATURE=688 && goodfeature_value='$rc' && good_deleted=0 limit 1");
            	$pol_id = 0;
            	if(!empty($rc_id)) list($pol_id) = $database->getArrayOfQuery("select goodfeature_value from cprice_goods_features where ID_FEATURE=691 && ID_GOOD='$rc_id' limit 1");
            	$data_input[898] = intval($rc_id);
            	$data_input[899] = $vals['НазначениеПлатежа'];
            	@$tdate = mktime(12,0,0,substr($vals['Дата'],3,2),substr($vals['Дата'],0,2),substr($vals['Дата'],6));
            	$data_input[900] = $tdate.' '.date("d.m.Y",$tdate);
                print '<tr>';
                print '<td>'.++$i.'</td>';
                print '<td>'.$data_input[891].'</td>';
                print '<td>'.$data_input[892].'</td>';
                print '<td>'.$data_input[893].'</td>';
                print '<td>'.$data_input[894].'</td>';
                print '<td>'.$vals['ПлательщикИмя'].'<br/>ИНН '.$vals['ПлательщикИНН'].($data_input[895]==0 ? '<br/><span style="color:red;font-size:10px;">Контрагента с таким ИНН нет</span>': '').'</td>';
                print '<td>'.$vals['ПлательщикРасчСчет'].', '.$vals['ПлательщикБанк'].($data_input[896]==0 ? '<br/><span style="color:red;font-size:10px;">Такого б.счета нет</span>': ($data_input[895]!=$plat_id?'<br/><span style="color:red;font-size:10px;">Это б.счет не контрагента</span>':'')).'</td>';
                print '<td>'.$vals['ПолучательИмя'].'<br/>ИНН '.$vals['ПолучательИНН'].($data_input[897]==0 ? '<br/><span style="color:red;font-size:10px;">Контрагента с таким ИНН нет</span>': '').'</td>';
                print '<td>'.$vals['ПолучательРасчСчет'].', '.$vals['ПолучательБанк'].($data_input[898]==0 ? '<br/><span style="color:red;font-size:10px;">Такого б.счета нет</span>': ($data_input[897]!=$pol_id?'<br/><span style="color:red;font-size:10px;">Это б.счет не контрагента</span>':'')).'</td>';
                print '<td>'.$data_input[899].'</td>';
                print '</tr>';

            	$exist = false;
            	list($old_num) = $database->getArrayOfQuery("select ID_GOOD from cprice_goods_features natural join cprice_goods where ID_FEATURE=892 && goodfeature_value='".$data_input[892]."' && good_deleted=0 limit 1");
            	if($old_num>0)
            	{       	     		$old_client = getFeatureText($old_num, 891);
       	     		if($old_client == $data_input[891])$exist = true;
            	}
            	if($exist) print '<tr><td colspan="10" style="color:red">Данная запись с таким поручением уже существует</td></tr>';
			}
			print '</table>';
		}
		print '</div>';

	break;
	case 'id_no_ph':
		$type = 2;
		$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && rubric_type=".$type);
		$ids = array();
		while($row = mysql_fetch_array($res))
		{
			$rubric_id = $row[0];
			$res_goods=$database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_goods where ID_RUBRIC='$rubric_id' && rubricgood_deleted=0 && good_deleted=0");
			while($row_goods = mysql_fetch_array($res_goods))
			{
				$id_good = $row_goods[0];
				if(!in_array($id_good, $ids))
				{
					$res2 = $database->query("SELECT goodphoto_file, ID_GOOD_PHOTO from ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0 && ID_GOOD=".$row_goods['ID_GOOD']." order by goodphoto_file");
					if(mysql_num_rows($res2)==0) $ids[] = $id_good;
				}
			}
		}
		sort($ids);
		foreach($ids as $id) print $id.'<br/>';
	break;
	case 'ph_del':
		$type = 2;
		$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && rubric_type=".$type);
		while($row = mysql_fetch_array($res))
		{
			$rubric_id = $row[0];
			$res_goods=$database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_goods where ID_RUBRIC='$rubric_id' && rubricgood_deleted=0 && good_deleted=0");
			while($row_goods = mysql_fetch_array($res_goods))
			{
				$id_good = $row_goods[0];
				$res2 = $database->query("SELECT goodphoto_file, ID_GOOD_PHOTO from ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0 && ID_GOOD=".$row_goods['ID_GOOD']." order by goodphoto_file");
				$old = "";
				while(list($goodphoto_file,$id) = mysql_fetch_array($res2))
				{					if($goodphoto_file!=$old)
					{						$old = $goodphoto_file;					}
					else
					{						if($run>0)
						{							$database->query("DELETE from ".DB_PREFIX."goods_photos WHERE ID_GOOD_PHOTO=".$id);						}					}
		   			print ''.$goodphoto_file.' | ';
	 			}
			}
		}

	break;
	case 'ph_to_base':
	if($run>0)
	{
		$type = 108;
		$fname = 27;
		$fpredmet = 61;
		$predmets = array();
		$photos = array();
		$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_deleted=0 && ID_RUBRIC=".$type);
		$dir = 'good_photo/';
		$tmaxw = teGetConf('photo_tmaxw');
		$tmaxh = teGetConf('photo_tmaxh');
		if($run==10){
			$dir = 'new/';
			teInclude("images");
		}
		while($row = mysql_fetch_array($res))
		{
			$rubric_id = $row[0];
			$res_goods=$database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_goods where ID_RUBRIC='$rubric_id' && rubricgood_deleted=0 && good_deleted=0");
			while($row_goods = mysql_fetch_array($res_goods))
			{
				$art = getFeatureValue($id_good,$fname);
				$id_good = $row_goods[0];
				$ext = '.jpg';$ext2 = '.JPG';
				$goodphoto_files = array();
				$goodphoto_file1 = $art.'.jpg';
				$goodphoto_file2 = $art.'.JPG';
				if(file_exists(DATA_FLD.$dir.$goodphoto_file1))
				{
					$goodphoto_files = array($goodphoto_file1);
				}
				elseif(file_exists(DATA_FLD.$dir.$goodphoto_file2))
				{
					$goodphoto_files = array($goodphoto_file2);
				}
/*				else
				{
					for($i=1;$i<20;$i++)
					{
						$goodphoto_file3 = $id_good.'_'.$i.$ext;
						$exist = false;
						if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
						{							$goodphoto_files[] = $goodphoto_file3;
							$exist = true;						}

						$goodphoto_file3 = $id_good.'_'.$i.$ext2;
						if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
						{
							$goodphoto_files[] = $goodphoto_file3;
							$exist = true;
						}
						if(!$exist)	break;
					}
				}
*/
				if(count($goodphoto_files)>0)
				{
					$name = getFeatureValue($id_good,$fname);
					if(strpos($name,'_')>0)
					{
						$arr = explode('_',$name);
						$name = implode(' ',$arr);
						if($run>0)
						{
							$database->query("UPDATE ".DB_PREFIX."goods_features SET goodfeature_value='".$name."' WHERE ID_FEATURE='".$fname."' AND ID_GOOD='".$id_good."'",false);
						}
					}
					$predmet = getFeatureValue($id_good,$fpredmet);
					if($predmet>0)
	                {
	                	if(isset($predmets[$predmet]))$name = $predmets[$predmet].$name;
	                	else
	                	{
	                		list($predmet_name) = $database->getArrayOfQuery("select rubric_unit_prefixname from cprice_rubric where ID_RUBRIC=".$predmet);
	    	            	if(!empty($predmet_name))
	    	            	{
	    	            		$predmets[$predmet] = $predmet_name.' ';
	    	            		$name = $predmets[$predmet].$name;
	    	            	}
	                	}
	                }
	                $new_name = mb_strtolower($name);
	                $arr = explode(" ",$new_name);
	                $n_arr = count($arr);
	                if($n_arr>2)
	                {
	                	$n_arr--;
	                	if($arr[0]==$arr[$n_arr])
	                	{
	                		$arr = explode(" ",$name);
	                		$name = '';
	                		for($i=0;$i<$n_arr;$i++)$name .= $arr[$i]." ";
	                		$name = trim($name);
	                	}
	                }
	                $max = 1;
	                foreach($goodphoto_files as $goodphoto_file)
	                {
	                	if(!in_array($goodphoto_file,$photos))
   						{
	   						print $id_good.': '.$name.' '.$goodphoto_file.' | ';
	   						if($run>0)
	   						{
		   						$database -> query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD,goodphoto_desc,goodphoto_alt,goodphoto_file,goodphoto_pos) VALUES ($id_good, '$name', '$name','$goodphoto_file',$max)");
		   						if($run==10)
		   						{
		   							rename(DATA_FLD.$dir.$goodphoto_file,DATA_FLD.'good_photo/'.$goodphoto_file);
		   							new_wm_image(DATA_FLD.'good_photo/'.$goodphoto_file);
		   							teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"trumb_",$tmaxw,$tmaxh);
		   						}
		   					}
	   						$max++;
	   						$photos[] = $goodphoto_file;
   						}
	                }
                }
			}
		}
    }else print '<br/><br/><a href="'.teGetUrlQuery("op1=ph_to_base","run=10").'">Запустить скрипт</a>';
	break;
	case 'wtmrk':
		print '<div align="center">';
		print '<h2>Наложение водного знака</h2>';
		$frm = new teForm("form1","post");
		$frm->addf_selectGroup("rubric0", "В каком разделе делать наложение:");
		$frm->addf_selectGroup("rubric", "В каких рубриках делать наложение:");
		$r = $database->query("select ID_RUBRIC_TYPE,rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0");
		while($row2 = mysql_fetch_array($r))
		{
			$frm->addf_selectItem("rubric0", $row2[0], $row2[1]);
        }
		$r = $database->query("select ID_RUBRIC_TYPE,rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0");
		while($row2 = mysql_fetch_array($r))
		{
			$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_type=".$row2[0]);
			while($row = mysql_fetch_array($res))
				$frm->addf_selectItem("rubric", $row[0], $row2[1].' &gt;&gt; '.$row[1]);
        }
        $frm->setFieldMultiple("rubric");
		$frm->addf_checkbox("trumb0", "Сделать уменьшение исходных фото до 1280px");
		$frm->addf_checkbox("trumb1", "Делать уменьшенные фото trumb");
		$frm->addf_checkbox("trumb2", "Делать уменьшенные фото image");
		$frm->addf_checkbox("wtrmrk1", "Наложить водный знак на исходное фото");
		$frm->addf_checkbox("wtrmrk2", "Наложить водный знак на фото image");
		if(!$frm->send())
		{
			$dir = 'good_photo/';


			teInclude("images");
			$mmaxw = teGetConf('photo_mmaxw');
			$mmaxh = teGetConf('photo_mmaxh');
			$tmaxw = teGetConf('photo_tmaxw');
			$tmaxh = teGetConf('photo_tmaxh');
			$rubric0 = $frm->get_value('rubric0');
			$rubric = $frm->get_value('rubric');
			if($rubric0>0 || count($rubric)>0)
			{
				$i=0;
				$sql = "";$br="";$add_tbl = '';
				foreach($rubric AS $rubric_id){
					if($rubric_id>0){
						$sql .= $br."ID_RUBRIC='$rubric_id'";
						$br = " || ";
					}
				}
				if($rubric0>0)
				{
					$add_tbl = "natural join cprice_rubric";
					if(!empty($sql))$sql = "rubric_type='$rubric0' && (".$sql.")";
					else $sql = "rubric_type='$rubric0'";
				}
				$res_goods=$database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_goods $add_tbl where ($sql) && rubricgood_deleted=0 && good_deleted=0 group by ID_GOOD");

				//$res_goods=$database->query("select ID_GOOD FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."rubric NATURAL JOIN ".DB_PREFIX."goods WHERE rubric_type=2 && rubricgood_deleted=0 && good_deleted=0");
				while($row_goods = mysql_fetch_array($res_goods)){
					$res = $database->query("SELECT goodphoto_file from ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0 && ID_GOOD=".$row_goods['ID_GOOD']);
//					$res = $database->query("SELECT goodphoto_file from ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0");
					while(list($goodphoto_file) = mysql_fetch_array($res)){
					  if(file_exists(DATA_FLD.$dir.$goodphoto_file)){
						print ''.$goodphoto_file.' | ';
						if($run>0){
							if(isset($_POST['trumb0'])){
                                                            $size_img = getimagesize(DATA_FLD."good_photo/".$goodphoto_file);
                                                            if(($size_img[0]>1280 || $size_img[1]>1280 || filesize(DATA_FLD."good_photo/".$goodphoto_file)>500000) ){print teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"",1280,1280,NULL,80);}
							}
							if(isset($_POST['trumb1'])){
								print teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"trumb_",$tmaxw,$tmaxh);
							}
							if(isset($_POST['trumb2'])){
								print teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"image_",$mmaxw,$mmaxh);
							}
							if(isset($_POST['wtrmrk1']))new_wm_image(DATA_FLD.'good_photo/'.$goodphoto_file);
							if(isset($_POST['wtrmrk2']))new_wm_image(DATA_FLD.'good_photo/'."image_".$goodphoto_file);
						}
						$i++;
					  }
					}
				}
			}
		}
			print 'Обработанно '.$i.' фотографий';

		print '</div>';

	break;
	case 'wtmrk_folder';
		//Наложение водного знака на картинки в папке
		$folder_main = $hosts[DB_ID]['folder'].'images/'.$_GET['folder'].'/';
		$out = '';
		$files = array();
		function files($folder)
		{			global $files;			if (is_dir($folder)) {
			    if ($dh = opendir($folder)) {
			        while (($file = readdir($dh)) !== false) {
			        	$type = filetype($folder . $file);
			            if($type=='dir' && $file!='.' && $file!='..') files($folder . $file.'/');
			            else
			            {
			            	$ext = substr($file,-3);
			            	if($ext=='jpg' || $ext=='JPG')
			            		 $files[]=$folder.$file;
			            }
			        }
			        closedir($dh);
			    }
			}
		}
		files($folder_main);
        //print_r($files);
        print '<br/><br/>kol-vo: '.count($files).'<br/>';
        if($run==1)
        {			teInclude("images");
        	foreach($files as $goodphoto_file)
        	{        		new_wm_image($goodphoto_file);
        		print $goodphoto_file.'; ';        	}        }
	break;
	case 'rub_from_file':
		print '<div align="center">';
		print '<h2>Добавление рубрикатора из файла</h2>';
		$frm = new teForm("form1","post");
		$frm->addf_selectGroup("rubric", "В какую раздел добавить:");
		$r = $database->query("select ID_RUBRIC_TYPE,rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0");
		while($row2 = mysql_fetch_array($r))
		{
			$frm->addf_selectItem("rubric", $row2[0], $row2[1]);
        }
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("rubric","file");
		if(!$frm->send())
		{			$rubric = $frm->get_value('rubric');
			$feat_arr = array();
			$r3 = $database->query("select ID_FEATURE from cprice_rubric_features where ID_RUBRIC=0 && rubric_type=".$rubric." order by rubricfeature_pos");
			while($row3 = mysql_fetch_array($r3))
			{				$feat_arr[] = $row3[0];			}

			print '<div style="text-align:left;">';
			$file = $_FILES["file"]['tmp_name'];
			$farr = file($file);
			$parent = 0;
			$pids = array();
			$i_old=-1;$id=0;
			foreach($farr AS $i => $cont){
				$rub_name = trim($cont);
            	$textid = filename(translit($rub_name));
            	$textid = mb_strtolower($textid);
            	$pid = 0;
            	$i=0;
            	while($cont[$i]=="\t")
            	{            		$i++;
            		print '&nbsp;&nbsp;&nbsp;&nbsp;';            	}
            	print $rub_name.'<br/>';
            	if($i_old!=$i)
            	{            		if($i_old<$i)$pids[$i]=$id;
            		$i_old=$i;            	}

            	if($cont[0]=="\t")
            	{
            		$pid = $parent;
            	}

            	if($run>0)
            	{
	           		$database -> query("INSERT INTO ".DB_PREFIX."rubric (rubric_textid,rubric_parent,rubric_name,rubric_type,rubric_visible)
	           			VALUES ('".$textid."',$pids[$i],'".$rub_name."','$rubric',1)");
					$id = $database -> id();
					$n=1;
					foreach($feat_arr as $fid)
					{
						$database -> query("INSERT INTO ".DB_PREFIX."rubric_features  (ID_RUBRIC, ID_FEATURE, rubric_type, rubricfeature_pos) VALUES ('".$id."',$fid,'$rubric',$n)");
		           		$n++;
					}
				}
			}
			print '</div>';
		}
		print '</div>';

	break;
	case 'tree':
				$type_goods = 1;
				function print_rub($pid=0,$i=0)
				{
					global $database,$type_goods,$hosts;
					$i++;
					$res = $database->query("select ID_RUBRIC,rubric_name,rubric_textid from cprice_rubric where rubric_deleted=0 && rubric_visible=1 && rubric_parent=$pid  && rubric_type=".$type_goods." order by rubric_pos,rubric_name");
					$out = '';
					while($row = mysql_fetch_array($res)){
						$childs = print_rub($row[0],$i);
						$out .= '<div class="head'.(empty($childs)?' ch':' hd').'"> '.$row[1].' <a href="'.$hosts[DB_ID]['url'].''.$row[2].'">'.$hosts[DB_ID]['url'].''.$row[2].'</a><br/></div>';
						$out .= $childs;
					}
					if(!empty($out))$out = '<div class="rt r'.($i-1).'">'.$out.'</div>'."\r\n";
					return $out;
				}
				print '<h1>Дерево рубрик</h1>';
				print print_rub();
	    $css = <<<TXT
.adv {margin-top:4px;}
.adv, .adv a{font-weight:bold;color:#004000}
.comment table {border-collapse:collapse;}
.comment td, .comment th {border:solid 1px #5C814B;padding:2px}
.comment .price,.comment input {text-align:right;}
.comment .center {text-align:center;}
.rt {margin:5px 0 5px 20px;font-size:14px;}
.rt hr {margin-bottom:5px;}
.hd {cursor:pointer;}
#show_all {margin-top:30px;cursor:pointer;}
.form {text-align:right;width:330px;}
.form textarea {width:330px;font-size:12px;}
.comment {font-weight:normal;color:#5C814B;font-size:12px;}
.comment div {margin-bottom:5px;}
.isp {color:#FF8000;}
TXT;
		teAddCSSCode($css);

	break;
	case 'from_file':
		print '<div align="center">';
		print '<h2>Добавление данных из файла</h2>';
		$frm = new teForm("form1","post");
		$frm->addf_selectGroup("rubric", "В какую рубрику добавить:");
		$r = $database->query("select ID_RUBRIC_TYPE,rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0");
		while($row2 = mysql_fetch_array($r))
		{
			$res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_type=".$row2[0]);
			while($row = mysql_fetch_array($res))
				$frm->addf_selectItem("rubric", $row[0], $row2[1].' &gt;&gt; '.$row[1]);
        }
		$frm->addf_radioGroup("type", "Запись представлена");
		$frm->addf_radioItem("type", "cols", "В виде столбца");
		$frm->addf_radioItem("type", "rows", "В виде строки", true);
		$frm->addf_file("file", "Файл:");
		$frm->setf_require("type","file");
		if(!$frm->send())
		{
			$rubric = (int)$frm->get_value('rubric');
			$type = $frm->get_value('type');
			$file = $_FILES["file"]['tmp_name'];
			$farr = file($file);

			print '<table>';
			$ids = array();
			$data = array();
			if($type=='cols')
			{
				foreach($farr AS $i => $cont){
					$line = explode("\t", $cont);
					if($i==0)
					{
						print '<tr>';
						foreach($line as $item)
						{
							if(!empty($item))$ids[] = $item;
							print '<td>'.$item.'</td>';
						}
						print '</tr>';
					}
					else
					{
						print '<tr>';$j=0;
						foreach($line as $item)
						{			$item = intval($item);
							if($j>0)
							{
								if(isset($data[($j-1)][$line[0]]))$data[($j-1)][$line[0]].='|'.$item;
								else $data[($j-1)][$line[0]]=$item;
							}
							print '<td>'.$item.'</td>';
							$j++;
						}
						print '</tr>';
					}
				}
				print '</table>';
				if($run==10 && $rubric>0)
				{deleteData($rubric);}
				$i=0;
				foreach($ids as $id)
				{	print $id.': ';
					print_r($data[$i]);
					print '<br/><br/>';
					if($run>0)
					{
						insertData($rubric,$data[$i]);
					}
					$i++;
				}
			}else
			{
				$rids = array();
				$nfeats = 0;$k=0;
				foreach($farr AS $i => $cont){
					$line = explode("\t", $cont);
					//$line = explode(";", $cont);
					if($i==0)
					{
						print '<tr>';
						foreach($line as $item)
						{
								$ids[] = $item;
								print '<td>'.$item.'</td>';
						}
						$nfeats = count($ids);
						print '</tr>';
					}
					else
					{
						if(count($line)==$nfeats)
						{
							print '<tr>';$j=0;
							foreach($ids as $fid)
							{
								if(!empty($fid))$data[$k][$fid] = $line[$j];
								else $rids[] = $line[$j];
								print '<td>'.(empty($line[$j])?"&nbsp;":$line[$j]).'</td>';
								$j++;
							}
							print '</tr>';$k++;
						}
					}
				}
				print '</table>';
				if($run==10 && $rubric>0)
				{deleteData($rubric);}
				$tmaxw = teGetConf('photo_tmaxw');
				$tmaxh = teGetConf('photo_tmaxh');
				$dir = 'new/';
				teInclude("images");
				$fname=27;
				for($j=0;$j<$k;$j++)
				{
					print_r($data[$j]);
					//$art = $data[$j][$fname];

					$ext = '.jpg';$ext2 = '.JPG';
					$goodphoto_files = array();
					/* не удалять!!!
					$goodphoto_file1 = $art.'.jpg';
					$goodphoto_file2 = $art.'.JPG';
					if(file_exists(DATA_FLD.$dir.$goodphoto_file1))
					{
						$goodphoto_files = array($goodphoto_file1);
					}
					elseif(file_exists(DATA_FLD.$dir.$goodphoto_file2))
					{
						$goodphoto_files = array($goodphoto_file2);
					}

					for($n=1;$n<20;$n++)
					{
						$goodphoto_file3 = $art.'_'.$n.$ext;
						//$exist = false;
						if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
						{							$goodphoto_files[] = $goodphoto_file3;
							//$exist = true;
							}

						$goodphoto_file3 = $art.'_'.$n.$ext2;
						if(file_exists(DATA_FLD.$dir.$goodphoto_file3))
						{
							$goodphoto_files[] = $goodphoto_file3;
							//$exist = true;
						}
						//if(!$exist)	break;
					}
					*/
					/*$goodphoto_file='';
					if(file_exists(DATA_FLD.$dir.$art.".jpg"))
					{
						$goodphoto_file = $art.".jpg";
						print '<br/>'.$goodphoto_file;
					}
					elseif(file_exists(DATA_FLD.$dir.$art.".JPG"))
					{
						$goodphoto_file = $art.".JPG";
						print '<br/>'.$goodphoto_file;
					}*/
					print '<br/><br/>';
					if($run>0)
					{
						if($rubric>0) $gid = insertData($rubric,$data[$j]);
						else $gid = insertData($rids[$j],$data[$j]);
						foreach($goodphoto_files as $goodphoto_file)
						{
							if(!empty($goodphoto_file))
							{	   						$name = $data[$j][61].' '.$data[$j][62].' '.$art;
								$database -> query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD,goodphoto_desc,goodphoto_alt,goodphoto_file,goodphoto_pos) VALUES ($gid, '$name', '$name','$goodphoto_file',1)");
								rename(DATA_FLD.$dir.$goodphoto_file,DATA_FLD.'good_photo/'.$goodphoto_file);
								new_wm_image(DATA_FLD.'good_photo/'.$goodphoto_file);
								teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"trumb_",$tmaxw,$tmaxh);
							}
						}
					}
				}
			}
		}
		print '</div>';

	break;
	case 'vs_new':
		$out = '';$emails = array();
		foreach($hosts as $id => $host){
		  if(isset($host['db_host']))
		  {
			otherbase($id);

			$res = $database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name like 'notify_%'");
            $out .= '<h3>'.$hosts[$id]['name'].'</h3>';
			while($row=mysql_fetch_array($res))
			{
				$tid = intval(substr($row['var_name'],7,strlen($row['var_name'])));
				list($num_all) = $database->getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."rubric_goods t1 inner join ".DB_PREFIX."goods t2 on t1.ID_GOOD=t2.ID_GOOD
					where t1.ID_RUBRIC=".$tid." && t1.rubricgood_deleted=0 && t2.good_deleted=0");
				$val = $row['var_value'];
				$arr1 = explode("|",$val);
				if($host['version']==1.0) $k=2;
				else $k=3;
				$rights = array();
				if(isset($arr1[$k]) && !empty($arr1[$k])){
					$rights = explode("$",$arr1[$k]);
				}

				foreach($rights as $user)
				{					$user = intval($user);
					if($user==-1 || $user>0)
					{
						if($user==-1) $user=0;
						list($num_view) = $database->getArrayOfQuery("SELECT count(*) FROM ".DB_PREFIX."rubric_goods natural join ".DB_PREFIX."goods natural join ".DB_PREFIX."rubric_events
							where ID_RUBRIC=".$tid." && rubricgood_deleted=0 && good_deleted=0 && ID_USER=".$user);
						if($run>0)
						{
							combase();
							$database->query("insert into notifys (ID_USER,ID_HOST,ID_RUBRIC,num_view,num_all,time_update) values ($user,$id,$tid,$num_view,$num_all,".(time()+60).")",false);							otherbase($id);
						}
					}
				}
				$out .= '<div>'.getRubricName($tid).'</div>';
			}
	      }
		}
		print "<h3>Все емайлы:</h3>";
		foreach($emails as $mail) print $mail.', ';
		print $out;
	break;
	case 'vs_mails':
		$out = '';$emails = array();
		foreach($hosts as $id => $host){
		  if(isset($host['db_host']) && !empty($host['db_name']))
		  {
			$countQueries = (int)@$database->countQueries;
		  	$database -> teDatabase($host['db_host'], $host['db_user'], $host['db_pass'], $host['db_name']);
			$database->countQueries = $countQueries;

			$res = $database -> query("SELECT * FROM ".DB_PREFIX."configtable where var_name like 'notify_%'");
            $out .= '<h3>'.$hosts[$id]['name'].'</h3>';
			while($row=mysql_fetch_array($res))
			{
				$tid = intval(substr($row['var_name'],7,strlen($row['var_name'])));
				$val = $row['var_value'];
				$arr1 = explode("|",$val);
				$mail = $arr1[1];
				$mails = explode(",",$mail);
				foreach($mails as $item)
				{					$item = trim($item);
					if(!in_array($item,$emails))$emails[]=$item;				}
				$out .= '<div>'.getRubricName($tid).'</div>'.$mail;
			}
	      }
		}
		print "<h3>Все емайлы:</h3>";
		foreach($emails as $mail) print $mail.', ';
		print $out;
	break;
	case 'urls':
		$in = array();
		$i=0;
		$in[$i]['type'] = 9;
		$in[$i]['rubric'] = 0;
		$in[$i++]['fname'] = 301;
/*		$in[$i]['type'] = 10;
		$in[$i]['rubric'] = 129;
		$in[$i++]['fname'] = 313;
		$in[$i]['type'] = 10;
		$in[$i]['rubric'] = 132;
		$in[$i++]['fname'] = 342;
		$in[$i]['type'] = 12;
		$in[$i]['rubric'] = 234;
		$in[$i++]['fname'] = 331;
		$in[$i]['type'] = 10;
		$in[$i]['rubric'] = 237;
		$in[$i++]['fname'] = 351;
		$in[$i]['type'] = 10;
		$in[$i]['rubric'] = 127;
		$in[$i++]['fname'] = 307;*/
		foreach($in as $j=>$ins)
		{
			$type = $ins['type'];
			$rub = $ins['rubric'];
			$fname = $ins['fname'];
			$res_goods=$database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_goods natural join cprice_rubric where good_url='' && rubric_type=".$type.($rub>0?" && ID_RUBRIC=".$rub:""));
			$urls = array();
			while($row_goods = mysql_fetch_array($res_goods))
			{
				list($name) = $database->getArrayOfQuery("SELECT goodfeature_value from cprice_goods_features where ID_FEATURE=$fname && ID_GOOD=".$row_goods['ID_GOOD']);
				if(empty($name)) $url = $row_goods['ID_GOOD'];
				else $url = mb_strtolower(filename(translit(trim($name))));
				$url = mb_strtolower($url);
				$url = preg_replace("/[^a-z0-9-_]/i","",$url);
				$url2 = "";$old="";
				for($i=0;$i<strlen($url);$i++)
				{
					if($url[$i]=='-')
					{
						if($old!=$url[$i] && $i>0)
						{
							$url2.= $url[$i];
							$old='-';
						}
						if($i==0)$old='-';
					}else
					{
						$old= '';
						$url2.= $url[$i];
					}

				}
				if(substr($url2,-1)=='_')$url2=substr($url2,0,-1);
				$url = $url2;
				$url = substr($url,0,120);
				if(in_array($url,$urls))
				{					$k=0;$url2 = $url.$k;
					while(in_array($url2,$urls))
					{						$url2 = $url.$k;
						$k++;					}
					$url=$url2;				}
				$urls[]=$url;
				print $name.' ('.$url.'), ';
	   			if($run>0)
				{
					$ID_GOOD=0;
					list($ID_GOOD)=$database->getArrayOfQuery("SELECT ID_GOOD FROM cprice_goods where good_url='$url'");
					if(!$ID_GOOD)
						$database->query("update cprice_goods set good_url='$url' where ID_GOOD='".$row_goods[0]."'",false);
					del_cache(0,$row_goods[0]);
				}
			}
		}
	break;
	case 'years':
		$res_goods = $database->query("select * from cprice_changes where change_dt regexp '2007'");
		while($row_goods = mysql_fetch_array($res_goods))
		{
			$new_val = str_replace("2006",'2011',$row_goods['change_dt']);
			print $row_goods[0].' - '.$new_val.'<br/>';
			if($run>0)$database->query("update cprice_changes set change_dt='$new_val' where ID_CHANGE='".$row_goods[0]."'",false);
		}
	break;
	case 'emails':
		foreach($hosts as $key => $host)
		{
        	if(!isset($host['cms']))
        	{
        		otherbase($key);
				$res_goods = $database->query("select * from cprice_configtable where var_value regexp 'unis777@yandex.ru' && var_name like 'notify%'");
				while($row_goods = mysql_fetch_array($res_goods))
				{
					$new_val = str_replace("unis777@yandex.ru",($host['unis'] ? 'unis777@yandex.ru':'ufa915000@yandex.ru'),$row_goods[1]);
					print $row_goods[0].' - '.$new_val.'<br/>';
					if($run>0)$database->query("update cprice_configtable set var_value='$new_val' where var_name='".$row_goods[0]."'",false);
				}

        	}
		}
	break;
	case 'hosts':
		combase();
		foreach($hosts as $key => $host)
		{        	if(isset($host['cms']))
        	{        		$database->query("insert into hosts (ID_HOST,name,url,cms) values ($key, '".$host['name']."','".$host['url']."','".$host['cms']."')");        	}else
        	{        		$database->query("insert into hosts (ID_HOST,name,db_host,db_name,db_user,db_pass,folder,url,data,version,siteversion,unis) values
        		($key, '".$host['name']."','".$host['db_host']."','".$host['db_name']."','".$host['db_user']."','".$host['db_pass']."','".$host['folder']."','".$host['url']."','".$host['data']."','".$host['version']."','".$host['siteversion']."','".$host['unis']."')");
        	}		}
	break;
	case 'act':
		$rub = 236;
		$res_goods=$database->query("select t2.* from cprice_rubric_goods t1 inner join cprice_goods t2 on t1.ID_GOOD=t2.ID_GOOD where t1.ID_RUBRIC='$rub'");
		$fdate = 707;
		while($row_goods = mysql_fetch_array($res_goods))
		{
			list($date) = $database->getArrayOfQuery("SELECT goodfeature_value from cprice_goods_features where ID_FEATURE=$fdate && ID_GOOD=".$row_goods['ID_GOOD']);
			print $date.', ';
			$arr_td = explode(".",$date);
			$date_num = mktime(12,0,0,intval($arr_td[1]),intval($arr_td[0]),intval($arr_td[2]));
   			if($run>0)setFeatData2(725,$row_goods['ID_GOOD'],$date_num,true);
		}

	break;
	case 'trumb':
		teInclude("images");
		$x = 0;$count=200;
		$goodssql = "select goodphoto_file from cprice_goods_photos where goodphoto_deleted=0 && goodphoto_visible=1";
		$OList = new teList($goodssql,$count);
		$OList->addToHead("photo");
		$OList->addToHead("exist");
		$OList->addToHead("changed 1");
		$OList->addToHead("changed 2");
		$maxw = teGetConf('photo_tmaxw'); $maxh = teGetConf('photo_tmaxh');
		$maxw1 = teGetConf('photo_mmaxw'); $maxh1 = teGetConf('photo_mmaxh');
		$OList->query();
		while($OList->row())
		{
			$OList->addUserField("{goodphoto_file}");
 			$goodphoto_file = $OList->getValue("goodphoto_file");
			if(file_exists(DATA_FLD."good_photo/".$goodphoto_file)){
				$OList->addUserField("ok");
				$filename = DATA_FLD."good_photo/trumb_".$goodphoto_file;
				@$size_img = getimagesize($filename);
				if(($size_img[0]>$maxw || $size_img[1]>$maxh) && $run>0)
				{
					teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"trumb_",$maxw,$maxh);
					$OList->addUserField("yes");
				}else $OList->addUserField("");
				$filename = DATA_FLD."good_photo/image_".$goodphoto_file;
				@$size_img = getimagesize($filename);
				if(($size_img[0]>$maxw1 || $size_img[1]>$maxh1) && $run>0)
				{
					teImgTrumb(DATA_FLD."good_photo/".$goodphoto_file,"image_",$maxw1,$maxh1);
					$OList->addUserField("yes");
				}else $OList->addUserField("");
			}else
			{
				$OList->addUserField("error");
				$OList->addUserField("");
				$OList->addUserField("");
			}
            $x++;
   		}
		echo($OList->getHTML());
		unset($OList);
		print 'ОК '.$x;
	break;
	case "frchng":
		$rub = 1000000008;
		$res_goods=$database->query("select t2.* from cprice_rubric_goods t1 inner join cprice_goods t2 on t1.ID_GOOD=t2.ID_GOOD where t1.ID_RUBRIC='$rub'");
		$fdate = 454;
		while($row_goods = mysql_fetch_array($res_goods))
		{
			list($date) = $database->getArrayOfQuery("SELECT DATE_FORMAT(change_dt,'%d.%m.%Y') as td FROM ".DB_PREFIX."changes WHERE change_type=1 && change_table='cprice_goods' && change_row=".$row_goods['ID_GOOD']);
			print $date.', ';
   			if($run>0)setFeatData2($fdate,$row_goods['ID_GOOD'],$date,true);
		}

	break;
	case 'drev':
	print '<div align="center">';
$farr = file('drevprodukt.txt');
print '<table>';
$ids = array();
$data = array();
foreach($farr AS $i => $cont){
	$line = explode("\t", $cont);
	if($i==0)
	{		print '<tr>';
		foreach($line as $item)
		{
			if(!empty($item))$ids[] = $item;
			print '<td>'.$item.'</td>';
		}
		print '</tr>';
	}
	else
	{
		print '<tr>';$j=0;
		foreach($line as $item)
		{			$item = intval($item);
			if($j>0)
			{				if(isset($data[($j-1)][$line[0]]))$data[($j-1)][$line[0]].='|'.$item;
				else $data[($j-1)][$line[0]]=$item;			}			print '<td>'.$item.'</td>';
			$j++;
		}
		print '</tr>';
	}
}
print '</table>';
if($run==10)
{deleteData(493);}
$i=0;
foreach($ids as $id)
{	print $id.': ';
	print_r($data[$i]);
	print '<br/><br/>';
	if($run>0)
	{
		if($data[$i][807]>0)insertData(493,$data[$i]);
	}
	$i++;}
	print '</div>';

	break;
	case 'form':
	print '<div align="center">';
$frm = new teForm("form1","post");
$frm->addTitle('Организация');
$frm->setSubmitCaption('Далее');
$frm->addf_checkbox("wmpos", "водяного знака",true);
$frm->addf_checkboxGroup("wm_pos", "Расположение водяного знака");
$frm->addf_checkboxItem("wm_pos","1" ,"Левый верхний угол");
$frm->addf_checkboxItem("wm_pos","2" ,"Правый верхний угол");
$frm->addf_checkboxItem("wm_pos","3" ,"Левый нижний угол");
$frm->addf_checkboxItem("wm_pos","4" ,"Правый нижний угол",true);
$frm->addTitle('Для сайта',2);
$frm->addf_text("site_slogan1", "Слоган (краткий)");
$frm->addf_text("site_slogan2", "Слоган (полный)","",true);
$frm->addf_text("site_copyright", "Copyright &copy;");
$frm->addf_text("site_links", "Ссылки","",true);
$frm->addf_text("site_cntrs", "Счетчики","",true);
$frm->step(2,"site_slogan1","site_slogan2","site_copyright","site_links","site_cntrs");
if(!$frm->send(false,2))
{	print $frm->get_value_checkbox("wmpos").' ';
	print_r($frm->get_value("wm_pos"));}
	print '</div>';
	break;
	case 'rtxt':
		$res = $database -> query("SELECT rubric_textid,rubric_name,ID_RUBRIC FROM ".DB_PREFIX."rubric");
		$i=0;
		while($row = mysql_fetch_array($res))
		{
			if(!empty($row[0])) $url = str_replace("_","-",mb_strtolower(filename(translit($row[0]))));
			else $url = mb_strtolower(filename(translit($row[1])));
			print $row[1].' '.$row[0].' '.$url.'<br> ';
			if($run>0) $database -> query("UPDATE ".DB_PREFIX."rubric set rubric_textid='".$url."' WHERE ID_RUBRIC=".$row[2],false);
			$i++;
		}
        print 'ОК '.$i;
	break;
	case 'icq':
		$frm = new teForm("form1","post");
		$frm->addf_text('num', 'Номер ICQ');
		$frm->addf_ereg('num', "^[0123456789\.\,]*$");
		$frm->setf_require("num");
		if(!$frm->send())
		{
			$num = $frm->get_value("num");
			print <<<XTT
Статус:
			<script type="text/javascript">
<!--
  document.write('<img src="http://wwp.icq.com/scripts/online.dll?icq={$num}&img=27&rnd='+Math.random()+'" width="16" height="16" alt="icq" style="border: none;vertical-align:top" /> {$num}');
//-->
</script>
XTT;
		}
	break;
	case 'avtor':
		$s1 = ob_get_contents();
		ob_end_clean();
		unset($s1);
		// библиотека ексель
		teInclude("excel");
		$workbook = new Spreadsheet_Excel_Writer();
		$workbook->send("login_history.xls");
	   	$worksheet =& $workbook->addWorksheet('История авторизаций');
		$num=0;
		$worksheet->setColumn(0,0,20.0);
		$worksheet->setColumn(0,1,20.0);
		$worksheet->setColumn(0,2,20.0);
		$worksheet->setColumn(0,3,20.0);

		$bdate = date("Y-m-j H:i:s",mktime(0,0,0,10,1,2010));
		$edate = date("Y-m-j H:i:s",mktime(23,59,59,1,13,2011));

		$bdate1 = date("d.m.Y",mktime(0,0,0,10,1,2010));
		$edate1 = date("d.m.Y",mktime(23,59,59,1,13,2011));

		combase();
		$frmt = & $workbook->addFormat();
		$frmt->setBold();
		$frmt->setAlign('center');
		$frmt->setVAlign('top');
		$frmt->setSize(12);
		$worksheet->setMerge($num, 0, $num, 3);
	    $tdate = date("d.m.Y H:i");
		$worksheet->write($num++, 0, 'История авторизаций непользователей от '.$bdate1.' до '.$edate1,$frmt);
		$num++;
		unset($frmt);
		$frmt = & $workbook->addFormat();
		$frmt->setBold();
		$frmt->setBorder(1);
		$frmt->setAlign('center');
		$frmt->setVAlign('vcenter');
		$frmt->setSize(10);
		$frmt->setTextWrap();
		$worksheet->write($num, 0, 'Дата',$frmt);
		$worksheet->write($num, 1, 'Время',$frmt);
		$worksheet->write($num, 2, 'Логин',$frmt);
		$worksheet->write($num, 3, 'IP',$frmt);
		unset($frmt);
		$frmt = & $workbook->addFormat();
		$frmt->setBorder(1);
		$frmt->setAlign('left');
		$frmt->setVAlign('vcenter');
		$frmt->setSize(11);
		$frmt->setTextWrap();

        $arr_users = array();
        $arr_users[]='root';
		$res = $database -> query("SELECT *,DATE_FORMAT(data,'%d.%m.%Y') as tdate,DATE_FORMAT(data,'%H:%i') as ttime FROM ".DB_PREFIX."login_history where data>'$bdate' && data<'$edate' && success=0 order by data");
		while($row = mysql_fetch_array($res))
		{
			if(in_array($row['user_login'],$arr_users))
				continue;
			$user = $database->getArrayOfQuery("SELECT ID_USER,user_name,user_sname FROM ".DB_PREFIX."users WHERE user_login='".$row['user_login']."'",MYSQL_ASSOC);
			if($user['ID_USER']>0){
				$arr_users[] = $row['user_login'];
				continue;
			}
			$worksheet->write($num, 0, $row['tdate'],$frmt);
			$worksheet->write($num, 1, $row['ttime'],$frmt);
			$worksheet->write($num, 2, $row['user_login'],$frmt);
			$worksheet->write($num++, 3, $row['ip'],$frmt);
		}
	    $workbook->close();
	    exit;
	break;
	case 'sendm':
		$arr_rub = array(44,350,336,239,247,360);
		$arr_mail = array(143,377,552,377,364,306);
		$arr_fio = array(102,373,551,373,362,590);
		$mailru = array('mail.ru','list.ru','inbox.ru','bk.ru');
		$i = 0;
		$new_mail1 = array();
		$new_fio1 = array();
		$new_mail2 = array();
		$new_fio2 = array();
		foreach($arr_rub as $rubric)
		{			if($i==0)$database -> teDatabase($hosts[16]['db_host'], $hosts[16]['db_user'], $hosts[16]['db_pass'], $hosts[16]['db_name']);
			$res_goods = $database -> query("select ID_GOOD from cprice_rubric_goods natural join cprice_goods WHERE ID_RUBRIC='$rubric' && rubricgood_deleted=0 && good_deleted=0");
			while($row_goods = mysql_fetch_array($res_goods))
			{
				$email = getFeatureValue($row_goods[0],$arr_mail[$i]);
				$name = getFeatureValue($row_goods[0],$arr_fio[$i]);
				if(!in_array($email,$new_mail1) && !in_array($email,$new_mail2) && strpos($email,"@")>0)
				{					print ': '.$email.' '.$rubric.' '.$name.' ';
					$arr = explode("@",$email);
					if(!in_array($arr[1],$mailru))
					{
						$new_mail1[]=$email;
						$new_fio1[]=$name;
					}
					else
					{						$new_mail2[]=$email;
						$new_fio2[]=$name;
					}
				}
			}
			if($i==0)curbase();
			$i++;		}
		$i=0;$j=0;
		foreach($new_mail1 as $email)
		{			$data = array();
			$data[624]= $email;
			$data[625]= $new_fio1[$i];
			$data[626]= '';
			$data[627]= 0;			insertData(377, $data);
			$i++;			$j++;
		}
		$i=0;
		foreach($new_mail2 as $email)
		{
			$data = array();
			$data[624]= $email;
			$data[625]= $new_fio2[$i];
			$data[626]= '';
			$data[627]= 0;
			insertData(378, $data);
			$i++;
			$j++;
		}
		print '<h2>Записей: '.$j.'</h2>';
	break;
	case 'photo':
		$res_goods = $database -> query("select * from photos WHERE photo_parent=0 && photo_deleted=0 order by photo_pos");
		$i=0;
		while($row_goods = mysql_fetch_array($res_goods))
		{			$database -> query("INSERT INTO ".DB_PREFIX."goods (good_visible,good_deleted) VALUES (1,0)");
			$id = $database -> id();
			$database -> query("INSERT INTO ".DB_PREFIX."rubric_goods (ID_RUBRIC,ID_GOOD,rubricgood_pos) VALUES (206,$id,'".$row_goods['photo_pos']."')");
			$database -> query("INSERT INTO ".DB_PREFIX."goods_features (ID_GOOD,ID_FEATURE,goodfeature_value) VALUES ($id,970,'".$row_goods['photo_name']."')");
			$res = $database -> query("select * from photos WHERE photo_parent=".$row_goods['ID_PHOTO']." && photo_deleted=0 order by photo_pos");
			while($row = mysql_fetch_array($res))
			{				$database -> query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD,goodphoto_file,goodphoto_desc,goodphoto_alt,goodphoto_pos) VALUES ($id,'".$row['photo_file']."','".$row['photo_name']."','".$row['photo_name']."','".$row['photo_pos']."')");
				$i++;
			}
		}
		print 'ок '.$i;
	break;
	case 'op':
		$res_goods = $database -> query("select * from cprice_rubric_goods t1 inner join cprice_goods t2 on t1.ID_GOOD=t2.ID_GOOD WHERE t1.ID_RUBRIC='330' && t1.rubricgood_deleted=0 && t2.good_visible=1 && t2.good_deleted=0");
		$i=0;
		while($row_goods = mysql_fetch_array($res_goods))
		{
			$res = $database -> query("select * from cprice_goods_features t1 WHERE t1.ID_FEATURE=177 && t1.ID_GOOD='".$row_goods['ID_GOOD']."'");
		 	$row = mysql_fetch_array($res);
		 	if($row[0]=='290')
		 	{
			 	$database -> query("update cprice_goods set good_deleted=1 where ID_GOOD=".$row_goods['ID_GOOD']);
			 	$i++;
			}
		}
		print 'OK '.$i;
	break;
	case 'sites':
		$database -> teDatabase($hosts[5]['db_host'], $hosts[5]['db_user'], $hosts[5]['db_pass'], $hosts[5]['db_name']);
		$res_goods = $database -> query("select * from cprice_rubric_goods t1 inner join cprice_goods t2 on t1.ID_GOOD=t2.ID_GOOD WHERE t1.ID_RUBRIC='117' && t1.rubricgood_deleted=0 && t2.good_visible=1 && t2.good_deleted=0");
		while($row_goods = mysql_fetch_array($res_goods))
		{
			$res = $database -> query("select * from cprice_goods_features t1 WHERE t1.ID_FEATURE=259 && t1.ID_GOOD='".$row_goods['ID_GOOD']."' order by t1.ID_FEATURE");
		 	$value=array();
		 	while($row = mysql_fetch_array($res))
		 	{
		 		$value[]=$row['goodfeature_value'];
		 	}
		 	$text = trim($value[0]);
			print '<h2>'.$text.'</h2>';
			$url = mb_strtolower($text);
			$f = file_get_contents($url);
			if($f)
			{
				if($row_goods['ID_GOOD']!=159 && strpos($f,'https://cprice.ddmitriev.ru/')>0)
					print '<div style="color:red">Сайт '.$url.' не доступен</div>';
				else
				{
					if(!strpos($f,'ufapr'))print '<div style="color:red">"ufapr" не найден</div>';
					print '<div style="color:green">Сайт '.$url.' доступен</div>';
				}

			}
			else print '<div style="color:red">Сайт '.$url.' не доступен</div>';
/*			$url = substr($url,7);
			$fp = @fsockopen($url, 80, $errno, $errstr, 10);
			if(!$fp)print "$errstr ($errno)<br />\n".'<div style="color:red">Сайт '.$url.' не доступен</div>';
			else {				print '<div style="color:green">Сайт '.$url.' доступен</div>';
				fclose($fp);
			}*/
		}
        combase();
	break;
	case 'strong':
	@$send = (int)$_POST['send'];
	$idt = array();
	$ids1 = array();
	$ids2 = array();
	if($send>0)
	{		@$idt = $_POST['idt'];		@$ids1 = $_POST['ids1'];
		@$ids2 = $_POST['ids2'];
	}
	$res2 = $database -> query("select t1.*,t2.ID_GOOD from cprice_texts t1
		inner join cprice_goods_features t2 on t1.ID_TEXT=t2.goodfeature_value
		natural join cprice_goods t3 natural join cprice_features t4
		where t3.good_deleted=0 && t4.feature_type=7
	");
	$form = '';
	$form .= '<form action="" method="post"><table border="1">';
	$form .= '<tr><td>ID</td><td width="49%">Старый вариант</td><td width="49%">Измененный вариант</td></tr>';
 	while($row2 = mysql_fetch_array($res2))
 	{ 	  $str = mb_strtolower($row2['text_text']);	  if(strpos($str,"</strong>")>0 || strpos($str,"</b>")>0)
	  { 		$new = str_replace(array("<strong>","</strong>","<STRONG>","</STRONG>","<b>","</b>","<B>","</B>"),"",$row2['text_text']);
 		$form .= '<tr><td><input type="checkbox" name="idt[]" value="'.$row2['ID_TEXT'].'" checked="checked" /> '.$row2['ID_GOOD'].'</td><td>'.$row2['text_text'].'</td><td>'.$new.'</td></tr>';
 		if(in_array($row2['ID_TEXT'],$idt))
 			$database -> query("UPDATE cprice_texts set text_text='$new' where ID_TEXT=".$row2['ID_TEXT']);
 	  } 	}
	$form2='';
	$res2 = $database -> query("select * from cprice_metadata");
 	while($row2 = mysql_fetch_array($res2))
 	{
 	  $str = mb_strtolower($row2['metadata_body_description']);
	  if(strpos($str,"</strong>")>0 || strpos($str,"</b>")>0)
	  {
 		$new1 = str_replace(array("<strong>","</strong>","<STRONG>","</STRONG>","<b>","</b>","<B>","</B>"),"",$row2['metadata_body_description']);
 		if(!empty($row2['metadata_body_description']))
	 		$form2 .= '<tr><td><input type="checkbox" name="ids1[]" value="'.$row2['ID_METADATA'].'" checked="checked" /> '.$row2['metadata_id'].'</td><td>'.$row2['metadata_body_description'].'</td><td>'.$new1.'</td></tr>';
 		if(in_array($row2['ID_METADATA'],$ids1))
 			$database -> query("UPDATE cprice_metadata set metadata_body_description='$new1' where ID_METADATA=".$row2['ID_METADATA']);
	  }
 	  $str = mb_strtolower($row2['metadata_body_keywords']);
	  if(strpos($str,"</strong>")>0 || strpos($str,"</b>")>0)
	  {
 		$new2 = str_replace(array("<strong>","</strong>","<STRONG>","</STRONG>","<b>","</b>","<B>","</B>"),"",$row2['metadata_body_keywords']);
 		if(!empty($row2['metadata_body_keywords']))
	 		$form2 .= '<tr><td><input type="checkbox" name="ids2[]" value="'.$row2['ID_METADATA'].'" checked="checked" /> '.$row2['metadata_id'].'</td><td>'.$row2['metadata_body_keywords'].'</td><td>'.$new2.'</td></tr>';
 		if(in_array($row2['ID_METADATA'],$ids2))
 			$database -> query("UPDATE cprice_metadata set metadata_body_keywords='$new2' where ID_METADATA=".$row2['ID_METADATA']);
 	  }
 	}
 	if(!empty($form2))
		$form .= '<tr><td colspan="3"><a name="seo"></a><h2>СЕО-параметры</h2></td></tr>'.$form2;
	$form .= '<tr><td colspan="3" style="text-align:center;"><input type="hidden" name="send" value="1"><input type="submit" value="   Изменить   " /></td></tr>';
	$form .= '</table></form>';

	if($send>0) print '<h3>Данные обновленны</h3>';
	else print $form;
	break;
case 'big':
print '<a name="big"></a><h2>Большие текста</h2>';
	$res2 = $database -> query("select * from cprice_texts");
 	while($row2 = mysql_fetch_array($res2))
 	{
 		print $row2['text_text'];
 		print '<br/><br/>';
 	}
break;
case 'seo':
print '<a name="seo"></a><h2>СЕО-параметры</h2>';
	$res2 = $database -> query("select * from cprice_metadata where `metadata_head_title` LIKE '%. ВИТАДЕНТ плюс в Уфе%'");
 	while($row2 = mysql_fetch_array($res2))
 	{
 		if(!empty($row2['metadata_head_title']))print $row2['metadata_head_title'].'<br/>';
 		if(!empty($row2['metadata_meta_title']))print $row2['metadata_meta_title'].'<br/>';
 		if(!empty($row2['metadata_meta_keywords']))print $row2['metadata_meta_keywords'].'<br/>';
 		if(!empty($row2['metadata_meta_description']))print $row2['metadata_meta_description'].'<br/>';
 		if(!empty($row2['metadata_body_h1']))print $row2['metadata_body_h1'].'<br/>';
 		if(!empty($row2['metadata_body_h2']))print $row2['metadata_body_h2'].'<br/>';
 		if(!empty($row2['metadata_body_description']))print $row2['metadata_body_description'].'<br/>';
 		if(!empty($row2['metadata_body_keywords']))print $row2['metadata_body_keywords'].'<br/>';
 		print '<br/><br/>';
		if($run>0)
		{
			$database -> query("update cprice_metadata set
				metadata_head_title='".str_replace(". ВИТАДЕНТ плюс в Уфе",". Стоматология в Уфе",$row2['metadata_head_title'])."',
				metadata_meta_title='".str_replace(". ВИТАДЕНТ плюс в Уфе",". Стоматология в Уфе",$row2['metadata_meta_title'])."',
				metadata_meta_description='".str_replace(". ВИТАДЕНТ плюс в Уфе",". Стоматология в Уфе",$row2['metadata_meta_description'])."',
				metadata_body_h1='".str_replace(". ВИТАДЕНТ плюс в Уфе",". Стоматология в Уфе",$row2['metadata_body_h1'])."'
				where ID_METADATA=$row2[0]
			");
		}
 	}
print '<br/><br/><a href="#top">Вверх</a><br/><br/>';
break;
	case 'export_to_txt':
		$ord_goods_rubric = 505;
		$ords_rubric = 506;
		$status_feature = 410;

		if ( $orders = $database->getColumnOfQuery('select ID_GOOD
			from cprice_rubric natural join cprice_rubric_goods natural join cprice_goods natural join cprice_goods_features
			where ID_RUBRIC='.$ords_rubric.' && good_visible=1 && good_deleted=0 && ID_FEATURE='.$status_feature.' && goodfeature_value!="1"')
		)
		{
			$file = fopen(TEMPDIR.'export_koleso02.csv', 'a+');

			$txt_header = <<<TXT
имэйл пользователя;id_1C (айди товара из 1С);айди заказа;дата заказа;доставка (надо / не надо);адрес доставки (если надо);вид оплаты (оплачено или нет по безналу);количество;цена;общая стоимость;имя пользователя;контактный телефон\n
TXT;
			fputs($file, $txt_header);
			foreach ( $orders as $order )
			{
				if ( $ord_goods = getData($ord_goods_rubric, 'ID_RUBRIC_GOOD DESC', '', array(), true, array( 358 => $order )) )
				{
					if ( !$order_date = getFeatData(364, $order) )
					{
						// пропуск заказа, в случае, если заказ не оформлен и не введена дата подтверждения
						continue;
					}
					$user_id = getFeatData(362, $order);
					$trans = getFeatData(399, $order);
					$payment = getFeatData(398, $order);
					$address = getFeatData(400, $order);
					$mail = getFeatData(396, $order);
					$username = getFeatData(366, $order);
					$orderphone = getFeatData(397, $order);

					foreach ( $ord_goods as $ord_good_id => $ord_good_data )
					{
						$good_id = $ord_good_data[356];

						if ( !$id_1c = getFeatData(336, $good_id) )
							$id_1c = 'отсутствует ИД 1С';

						$good_price = (int)$ord_good_data[359];
						$good_count = (int)$ord_good_data[360];

						$summa = $good_price * $good_count;

						$txt_row = <<<TXT
$mail;$id_1c;$order;$order_date;$trans;$address;$payment;$good_price;$good_count;$summa;$username;$orderphone\n
TXT;
						fputs($file, $txt_row);
					}
				}
				if ( $database->query('select * from cprice_goods_features where ID_GOOD='.$order.' && ID_FEATURE='.$status_feature) )
				{
					$database->query("UPDATE cprice_goods_features SET goodfeature_value='1' WHERE ID_FEATURE=".$status_feature." AND ID_GOOD=".$order);
				}
				else
				{
					$database->query("INSERT INTO cprice_goods_features (ID_FEATURE,ID_GOOD,goodfeature_value) VALUES (".$status_feature.",".$order.",1)");
				}
			}
			fclose($file);
			file_force_download('temp/export_koleso02.csv');
			@unlink('temp/export_koleso02.csv');
		}
		break;
	case 'setup_models':
		$file = TEMPDIR.'mods.csv';
		if ( file_exists($file) )
		{
			if ( $data = file($file) )
			{
				foreach ( $data as $row )
				{
					$row_data = explode(';', $row);
					$good_id = $row_data[0];
					$database->query('delete from cprice_goods_features where ID_GOOD='.$good_id.' && goodfeature_value=""');

					// делим запятыми
					$mods = explode(',', $row_data[1]);
					$mods = is_array($mods) ? $mods : array( $mods );
					foreach ( $mods as $mod )
					{
						// делим на периоды
						$period = explode('-', $mod);
						if ( isset($period[1]) )
						{
							// указан период
							if ( $period[0] < $period[1] )
							{
								$counter = (int)$period[0];
								$last = (int)$period[1];
							}
							else
							{
								$counter = (int)$period[1];
								$last = (int)$period[0];
							}
							while ( $counter++ <= $last )
							{
								if ( setFeatData(68, $good_id, $counter, true) )
									echo $good_id, ' <- ', $counter, "\n";
							}
						}
						else
						{
							// указан единичный ИД
							if ( setFeatData(68, $good_id, $period[0], true) )
								echo $good_id, ' <- ', $period[0], "\n";
						}
					}
				}
			}
		}
		break;
}


/**
 * отправка файла на загрузку с сервера пользователю
 * @param $file
 */
function file_force_download($file)
{
	if ( file_exists($file) )
	{
		// сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
		// если этого не сделать файл будет читаться в память полностью!
		if ( ob_get_level() )
		{
			ob_end_clean();
		}
		// заставляем браузер показать окно сохранения файла
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: '.filesize($file));
		// читаем файл и отправляем его пользователю
		if ( $fd = fopen($file, 'rb') )
		{
			while ( !feof($fd) )
			{
				print fread($fd, 1024);
			}
			fclose($fd);
		}
	}
}
