<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798

print "<h2>Важные события</h2>";
print "<div align=center>";
if(isset($_GET['little']))addGet('little',$_GET['little']);

@$rub_id = (int)$_GET['rub_id'];
if($rub_id>0)
{
  $res1 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric where ID_RUBRIC=".$rub_id);
  if(mysql_num_rows($res1)>0)
  {
	$row1 = mysql_fetch_array($res1);
	$name = $row1['rubric_name'];
	$type = $row1['rubric_type'];

    print "<h3>События по рубрике: <a href='?type=".$row1['rubric_type']."&amp;showid=0&amp;pg=goods&amp;rubric_id=".$rub_id."'>$name</a></h3>";    $rub_type = $row1['rubric_type'];
	@$idl = (int)$_GET['idl'];
    if($idl>0)
    {
		$res3 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric_events where ID_GOOD=".$idl." && ID_USER=".$_USER['id']);
		if(mysql_num_rows($res3)==0)
			$database -> query("INSERT INTO ".DB_PREFIX."rubric_events (ID_GOOD, ID_USER, tdate) values ('$idl','".$_USER['id']."','".time()."')");

    	$rest = $database -> query("SELECT var_value FROM ".DB_PREFIX."configtable where var_name = 'notify_".$rub_id."'");
		$rowt=mysql_fetch_array($rest);
		$arrt = explode("|",$rowt[0]);
		$eshop=false;
        if(isset($arrt[2]))if($arrt[2]==1)$eshop=true;
        if($eshop)
        {        	$status=1;
        	$id=$idl;
        	$type=$rub_type;
			switch($status){
				case 1:
					$s = "Неоплаченный";
				break;
				case 2:
					$s = "Оплаченный";
				break;
				case 3:
					$s = "Отгруженный";
				break;
				default:
					$s = "";
				break;
			}
			setTitle("<h2>$s заказ ".getIdToPrint("goods",$id)." от ".getFeatureText($id,teGetConf("m_ishop_oCf_dt"))."</h2>");

			print "<table cellpadding='10'><tr valign='top' align='center'><td>";

			$usr_id = (int)getFeatureValue($id,teGetConf("m_ishop_oCf_usr"));
			print "<fieldset><legend>Информация о покупателе</legend>";
			print "<div class='ls'><table align='center'>";
			print "<tr><td class='ls_name'>ID</td><td class='ls_val'>".getIdToPrint("goods",$usr_id)."</td></tr>";
			print "<tr><td class='ls_name'>e-mail</td><td class='ls_val'><a href='".getFeatureText($usr_id,teGetConf("m_ishop_usr_ema"))."'>".getFeatureText($usr_id,(int)teGetConf("m_ishop_usr_ema"))."</a></td></tr>";
			print "<tr><td class='ls_name'>Телефон</td><td class='ls_val'>".getFeatureText($usr_id,(int)teGetConf("m_ishop_usr_tel"))."</td></tr>";
			print "<tr><td class='ls_name'>Фамилия</td><td class='ls_val'>".getFeatureText($usr_id,(int)teGetConf("m_ishop_usr_F"))."</td></tr>";
			print "<tr><td class='ls_name'>Имя</td><td class='ls_val'>".getFeatureText($usr_id,(int)teGetConf("m_ishop_usr_I"))."</td></tr>";
			print "<tr><td class='ls_name'>Отчество</td><td class='ls_val'>".getFeatureText($usr_id,(int)teGetConf("m_ishop_usr_O"))."</td></tr>";
			print "<tr><td class='ls_name'>Статус</td><td class='ls_val nobr'>";
				$sta = getFeatureValue($usr_id,(int)teGetConf("m_ishop_usr_sta"));
				switch($sta){
					case 1:
						print "Незарегистрированный покупатель";
					break;
					case 2:
						print "Зарегистрированный покупатель";
					break;
					case 3:
						print "Постоянный покупатель";
					break;
					default:
						print "VIP-клиент";
					break;
				}
			print "</td></tr>";
			print "</table></div>";
			print "</fieldset>";


			print "</td><td>";

			$country = getFeatureValue($id,teGetConf("m_ishop_oCf_cou"));
			$region = getFeatureValue($id,teGetConf("m_ishop_oCf_are"));
			$city = getFeatureValue($id,teGetConf("m_ishop_oCf_cty"));

			list($country) = $database->getArrayOfQuery("SELECT name FROM country WHERE country_id=".(int)$country);
			list($region) = $database->getArrayOfQuery("SELECT name FROM region WHERE region_id=".(int)$region);
			list($city) = $database->getArrayOfQuery("SELECT name FROM city WHERE city_id=".(int)$city);

			print "<fieldset><legend>Информация о заказе</legend>";
			print "<div class='ls'><table align='center'>";
			print "<tr><td class='ls_name'>ID</td><td class='ls_val'>".getIdToPrint("goods",$id)."</td></tr>";
			print "<tr><td class='ls_name'>Страна</td><td class='ls_val'>$country</td></tr>";
			if(!empty($area)) print "<tr><td class='ls_name'>Область</td><td class='ls_val'>$area</td></tr>";
			print "<tr><td class='ls_name'>Город</td><td class='ls_val'>$city</td></tr>";

			// адрес

			print "<tr><td class='ls_name'>Адрес</td><td class='ls_val nobr'>".
					getFeatureValue($id,teGetConf("m_ishop_oCf_ind")).", ".
					getFeatureValue($id,teGetConf("m_ishop_oCf_str")).", ".
					"д. ".getFeatureText($id,teGetConf("m_ishop_oCf_hom")).", ".
					((getFeatureText($id,teGetConf("m_ishop_oCf_cor"))!="")?"корп. ".getFeatureText($id,teGetConf("m_ishop_oCf_cor")).", ":"").
					"кв. ".getFeatureText($id,teGetConf("m_ishop_oCf_fla"))." ".
				"</td></tr>";
			print "</table></div>";
			print "</fieldset>";





			print "</td><td>";


			list($pay_id) = $database->getArrayOfQuery("
				SELECT cprice_goods_features.ID_GOOD
				FROM cprice_rubric_goods NATURAL JOIN cprice_goods NATURAL JOIN cprice_goods_features
				WHERE
					ID_RUBRIC=".teGetConf("m_ishop_pay")." and rubricgood_deleted=0 and good_deleted=0
					and cprice_goods_features.ID_FEATURE=".teGetConf("m_ishop_pay_oid")." and goodfeature_value = $id
			");

			if(!empty($pay_id)){
				print "<fieldset><legend>Информация об оплате</legend>";
				print "<div class='ls'><table align='center'>";
				print "<tr><td class='ls_name'>ID</td><td class='ls_val'>".getIdToPrint("goods",$pay_id)."</td></tr>";
				print "<tr><td class='ls_name'>Способ оплаты</td><td class='ls_val'>".getFeatureValue($pay_id,teGetConf("m_ishop_pay_way"))."</td></tr>";
				print "<tr><td class='ls_name'>Сумма платежа</td><td class='ls_val'>".getFeatureValue($pay_id,teGetConf("m_ishop_pay_amo"))."</td></tr>";
				print "<tr><td class='ls_name'>Дата и время платежа</td><td class='ls_val'>".getFeatureValue($pay_id,teGetConf("m_ishop_pay_dt"))."</td></tr>";
				print "<tr><td class='ls_name'>№ плат.док-та</td><td class='ls_val'>".getFeatureValue($pay_id,teGetConf("m_ishop_pay_desc"))."</td></tr>";
				print "</table></div>";
				print "</fieldset>";
			}

			print "</td>";
			print "<td>";

			list($del_id) = $database->getArrayOfQuery("
				SELECT cprice_goods_features.ID_GOOD
				FROM cprice_rubric_goods NATURAL JOIN cprice_goods NATURAL JOIN cprice_goods_features
				WHERE
					ID_RUBRIC=".teGetConf("m_ishop_del")." and rubricgood_deleted=0 and good_deleted=0
					and cprice_goods_features.ID_FEATURE=".teGetConf("m_ishop_del_oid")." and goodfeature_value = $id
			");

			if(!empty($del_id)){
				print "<fieldset><legend>Информация о доставке</legend>";
				print "<div class='ls'><table align='center'>";
				print "<tr><td class='ls_name'>ID</td><td class='ls_val'>".getIdToPrint("goods",$del_id)."</td></tr>";
				print "<tr><td class='ls_name'>Способ доставки</td><td class='ls_val'>".getFeatureValue($del_id,teGetConf("m_ishop_del_way"))."</td></tr>";
				print "<tr><td class='ls_name'>Служба доставки</td><td class='ls_val'>".getFeatureValue($del_id,teGetConf("m_ishop_del_slu"))."</td></tr>";
				print "<tr><td class='ls_name'>Ф.И.О. сотрудника службы доставки</td><td class='ls_val'>".getFeatureValue($del_id,teGetConf("m_ishop_del_fio"))."</td></tr>";
				print "<tr><td class='ls_name'>Сумма доставки</td><td class='ls_val'>".getFeatureValue($del_id,teGetConf("m_ishop_del_amo"))."</td></tr>";
				print "<tr><td class='ls_name'>Дата и время отгрузки</td><td class='ls_val'>".getFeatureValue($del_id,teGetConf("m_ishop_del_bdt"))."</td></tr>";
				print "<tr><td class='ls_name'>Ожидаемая дата доставки</td><td class='ls_val'>".getFeatureValue($del_id,teGetConf("m_ishop_del_edt"))."</td></tr>";
				print "<tr><td class='ls_name'>Номер накладной</td><td class='ls_val'>".getFeatureValue($del_id,teGetConf("m_ishop_del_bil"))."</td></tr>";
				print "</table></div>";
				print "</fieldset>";
			}

			print "</td>";



			print "</tr></table>";



			$comm = getFeatureText($id,teGetConf("m_ishop_oCf_com"),false,true);
			if(!empty($comm)){
				print "<hr/>";

				print "<div><b>Комментарий покупателя:</b></div><div>";
				print $comm;
				print "</div>";
			}



			print "<hr/>";

			print "<h3>Заказанные товары:</h3>";

			print "<table class='list'>";
			print "<tr>";
			print "<th>ID</th>";
			print "<th>Информация о товаре</th>";
			print "<th><small>Цена</small></th>";
			print "<th><small><b>Последняя цена</b></small></th>";
			print "<th><small><b>Кол-во</b>";
			print "</small></th>";
			print "<th><small><b>ИТОГО</b></small></th>";
			print "</tr>";

			$res = $database->query("
				SELECT cprice_goods.ID_GOOD
				FROM cprice_rubric NATURAL JOIN cprice_rubric_goods NATURAL JOIN cprice_goods NATURAL JOIN cprice_goods_features
				WHERE
					cprice_rubric.ID_RUBRIC=".teGetConf("m_ishop_oGd")." and rubric_type=$type and rubricgood_deleted=0 and good_deleted=0
					and ID_FEATURE=".teGetConf("m_ishop_oGd_oid")." and goodfeature_value=$id
			");
			$sumprice = $sumcnt = 0;
			while(list($id_good) = mysql_fetch_array($res)){
				print "<tr>";
					print "<td>".getIdToPrint("goods",$id_good)."</td>";
					print "<td>".getFeatureText(getFeatureValue($id_good,teGetConf("m_ishop_oGd_gid")),191,true)."</td>";
					print "<td class='nobr' align='right'>".(int)getFeatureValue(getFeatureValue($id_good,teGetConf("m_ishop_oGd_gid")),teGetConf("m_ishop_feat_p"))." руб.</td>";
					$postprice = (getFeatureValue($id_good,teGetConf("m_ishop_oGd_pri"))!="")?(int)getFeatureValue($id_good,teGetConf("m_ishop_oGd_pri")):getFeatureValue(getFeatureValue($id_good,teGetConf("m_ishop_oGd_gid")),teGetConf("m_ishop_feat_p"));
					print "<td class='nobr' align='right'>".(int)$postprice." руб.</td>";
					$cnt = getFeatureValue($id_good,teGetConf("m_ishop_oGd_cnt"));
					print "<td align='center'>".$cnt."</td>";
					print "<td class='nobr' align='right'><b>".($cnt*(int)$postprice)."</b> руб.</td>";

					$sumprice += ($cnt*(int)$postprice);
					$sumcnt += $cnt;

				print "</tr>";
			}
			print "<tr class='total'>";
			print "<td></td>";
			print "<td align='right'>Всего наименований: ".mysql_num_rows($res)."</td>";
			print "<td></td>";
			print "<td></td>";
			print "<td align='center'>";
			print "</td>";
			print "<td align='right'>$sumprice руб.</td>";
			print "</tr>";
			print "</table>";

            print '<div class="ls"><br/><br/><a href="/2/?pg=module_ishop&show=order&status=1&action=view&id='.$idl.'">Просмотреть заказ в Интернет-магазине</a></div>';
        }
        else {
        	print '<h4><i>Подробный просмотр события</i>'.((!$eshop)?' <a href="?pg=goods&typeview=tree&type='.$rub_type.'&showid=0&rubric_id='.$rub_id.'&action=view&id='.$idl.'&num=1">просмотреть в Древо</a>':'').'</h4>';
			$tbl='';
			$res = $database -> query("SELECT * FROM ".DB_PREFIX."features t1
				inner join ".DB_PREFIX."goods_features t2 on t1.ID_FEATURE=t2.ID_FEATURE
				inner join ".DB_PREFIX."rubric_features  t3 on t1.ID_FEATURE=t3.ID_FEATURE
				where t2.ID_GOOD='$idl' && t3.ID_RUBRIC='$rub_id' && t1.feature_enable=1 && t1.feature_deleted=0 && t3.rubric_type='$rub_type'
				order by t3.rubricfeature_pos"
			);
			$i=0;
			$email_ex = '';
			while($row = mysql_fetch_array($res))
			{
				$value ='';
				switch($row['feature_type']){
		            case 5:
		            	if($row['goodfeature_value']>0)
		            	{
			            	$line = $database->getArrayOfQuery("SELECT t2.goodfeature_value FROM ".DB_PREFIX."features t1
								inner join ".DB_PREFIX."goods_features t2 on t1.ID_FEATURE=t2.ID_FEATURE
								inner join ".DB_PREFIX."rubric_features  t3 on t1.ID_FEATURE=t3.ID_FEATURE
								where t2.ID_GOOD='".$row['goodfeature_value']."' && t1.feature_enable=1 && t1.feature_deleted=0 && t1.feature_type=2
								order by t3.rubricfeature_pos limit 1");
			            	$value = $line[0];
		            	}else $value = $row['goodfeature_value'];
		            break;
		            case 9:
		            	if($row['goodfeature_value']>0)
		            	{
			            	$line = $database->getArrayOfQuery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$row['goodfeature_value']);
			            	$value = $line[0];
		            	}else $value = $row['goodfeature_value'];
		            break;
		            case 7:
		            	if($row['goodfeature_value']>0)
		            	{
			            	$line = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT=".$row['goodfeature_value']);
			            	$value = $line[0];
		            	}else $value = $row['goodfeature_value'];
		            break;
		            case 4:
		            	if($row['goodfeature_value']>0)
		            	{
			            	$line = $database->getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$row['ID_FEATURE']." && ID_FEATURE_DIRECTORY='".$row['goodfeature_value']."'");
			            	$value = $line[0];
			       		}else $value = $row['goodfeature_value'];
		            break;
		            case 3:
		            	if($row['goodfeature_value']>0)
			            	$value = 'Да';
			       		else $value = 'Нет';
		            break;
		            default:
		            	$value = $row['goodfeature_value'];
		            break;

			    }
			    if(check_email_addr($value)==1)$email_ex=$value;
				$i++;
				$tbl .= '<tr><td class="ls_name">'.$row['feature_text'].'</td><td class="ls_val">'.$value.'</td></tr>';
			}
			@$send = (int)$_GET['send'];
			@$error = (int)$_GET['error'];
			if($error==3)print '<div style="color:blue;">Сообщение отправлено</div>';
			if($error==4)print '<div style="color:red;">Сообщение не отправлено, ошибка в настройках сервера</div>';
			print '<div class="ls"><table cellpadding="2" cellspacing="2" align="center">'.$tbl.'</table></div>';
			if($send==1 && !empty($email_ex))
			{				print '<br/><center>';
				if($error==1)print '<div style="color:red;">Необходимо заполнить все поля</div>';
				if($error==2)print '<div style="color:red;">Неправильно указаны емайлы</div>';
				$frm = new teForm("form1","post");
//				$frm->addf_hidden("rub_id", $rub_id);
//				$frm->addf_hidden("idl", $idl);
//				$frm->addf_hidden("send", 1);
				$frm->addTitle("<b>Ответить на сообщение</b>");
				$frm->setSubmitCaption("Отправить");
				if($_USER['id']>0)
				{
					$line = $database -> getArrayOfQuery("select * from ".DB_PREFIX."users where ID_USER='".$_USER['id']."' && user_deleted=0",MYSQL_ASSOC);
		   			$from='';
		   			if(!empty($line['user_email']))
		                $from=$line['user_email'];
                }else $from='unis777@yandex.ru';
                @$url_add = $_GET['url_add'];
                if(!empty($url_add))
                {                	$arr1 = explode("|",$url_add);
					$frm->addf_text('from', 'От кого:', $arr1[0]);
					$frm->addf_text('to', 'Кому', $arr1[1]);
					$frm->addf_text('theme', 'Тема сообщения', $arr1[2]);
					$frm->addf_text('txt', 'Сообщение', $arr1[3], true);
			    }else
			    {					$frm->addf_text('from', 'От кого:', $from);
					$frm->addf_text('to', 'Кому', $email_ex);
					$frm->addf_text('theme', 'Тема сообщения', 'Ответ на ваше сообщение на сайте '.$hosts[DB_ID]['name']);
					$frm->addf_text('txt', 'Сообщение', 'Здравствуйте, '."\r\n\r\n".'С уважением, администрация сайта '.$hosts[DB_ID]['name'], true);
			    }
			    $frm->setf_require('txt','theme','from','to');
			    if(!$frm->send())
			    {                	@$from = $_POST['from'];                	@$to = $_POST['to'];
                	@$theme = $_POST['theme'];
                	@$txt = $_POST['txt'];
                	$url= urlencode($from[0].'|'.$to[0].'|'.$theme[0].'|'.$txt);
                	if(empty($from[0]) || empty($to[0]) || empty($theme[0]) || empty($txt))
                		teRedirect(teGetUrlQuery("rub_id=".$rub_id,"idl=".$idl,"send=1","error=1","url_add=".$url));
                	if(check_email_addr($from[0])==0 || check_email_addr($to[0])==0)
	                	teRedirect(teGetUrlQuery("rub_id=".$rub_id,"idl=".$idl,"send=1","error=2","url_add=".$url));
	              	if(mail($to[0],$theme[0],$txt,"From: ".$from[0]."\r\nContent-Type: text/html; charset=\"Windows-1251\""))
		              	teRedirect(teGetUrlQuery("rub_id=".$rub_id,"idl=".$idl,"error=3"));
		         	else teRedirect(teGetUrlQuery("rub_id=".$rub_id,"idl=".$idl,"error=4"));			    }
				print '</center>';
			}
			print ((!empty($email_ex) && $send!=1)?'<br/>
			<a href="'.teGetUrlQuery("rub_id=".$rub_id,"idl=".$idl,"send=1").'">Ответить на сообщение</a>':'');
			print '<br/><br/>
			<a href="?pg=tasks&amp;op1=add_task&amp;good='.$idl.'&amp;rubric='.$rub_id.'&amp;rtype='.$rub_type.'" target="_blank">Создать задание</a>
			<br/><br/>
			<a href="?pg=events_form&amp;good='.$idl.'&amp;rubric='.$rub_id.'&amp;rtype='.$rub_type.'" target="_blank">Excel</a>
			';
			$noreads = '';
			$res2 = $database -> query("SELECT t1.ID_GOOD FROM ".DB_PREFIX."rubric_goods t1 inner join ".DB_PREFIX."goods t2 on t1.ID_GOOD=t2.ID_GOOD where t1.ID_RUBRIC=".$rub_id." && t1.rubricgood_deleted=0 && t2.good_deleted=0");
			while($row2 = mysql_fetch_array($res2))
			{
				$res3 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric_events where ID_GOOD=".$row2['ID_GOOD']." && ID_USER=".$_USER['id']);
				if(mysql_num_rows($res3)==0)$reads = false;
				else $reads = true;
				if(!$reads)
				{					list($mess) = $database -> getArrayOfQuery("SELECT goodfeature_value from ".DB_PREFIX."goods_features natural join cprice_rubric_features where ID_GOOD='".$row2['ID_GOOD']."' && ID_RUBRIC=".$rub_id." order by rubricfeature_pos limit 1");
	                $noreads .= $mess;
	                break;				}
			}
			if(!empty($noreads))
				print '<div class="ls"><table cellpadding="2" cellspacing="2" align="center"><tr><tr><td class="ls_name" colspan="2"><b>Непрочитанные сообщения</b></td></tr><td class="ls_val">'.$noreads.'</td><td><a href="'.teGetUrlQuery("rub_id=".$rub_id,"idl=".$row2['ID_GOOD']).'">Перейти к сообщению</a></td></tr></table></div>';
		}
	    print '<br/><br/><a href="'.teGetUrlQuery("rub_id=".$rub_id).'">Просмотреть все события по этой рубрике</a>';
    }else
    {
    print "<br /><h4><i>Все события</i></h4>";
	$rubric_id = $rub_id;
	@$read=(int)$_GET['read'];
	$acc = checkAccess($rub_id);
	$uid = $_SESSION['user_id'];
	addSubMenu(teGetUrlQuery("pg=goods","action=list_settings","type=".$rub_type,"showid=0","rubric_id=".$rub_id,"events=1"), "<img src='{$skinpath}images/b_lists_big.png' alt='' title='Настройки списка'/>", "submenustd");
	if($read>0)
	{
		$res2 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric_goods t1 inner join ".DB_PREFIX."goods t2 on t1.ID_GOOD=t2.ID_GOOD where t1.ID_RUBRIC=".$rub_id." && t1.rubricgood_deleted=0 && t2.good_deleted=0");
		while($row2 = mysql_fetch_array($res2))
		{
			$res3 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric_events where ID_GOOD=".$row2['ID_GOOD']." && ID_USER=".$_USER['id']);
			if(mysql_num_rows($res3)==0){
				$reads = false;
				if($read>0)
				{
					$database -> query("INSERT INTO ".DB_PREFIX."rubric_events (ID_GOOD, ID_USER, tdate) values ('".$row2['ID_GOOD']."','".$_USER['id']."','".time()."')");
					$reads = true;
				}
			}
        }
	}
	else
	{
		addGet("rub_id",$rubric_id);
		addGet("type",$type);
		// SQL
		$goodssql = "
			SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*
			FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods
			WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
			ORDER BY ".DB_PREFIX."goods.ID_GOOD DESC
		";
		$ordertype = $orderby = "";
		$configs = teGetConfs("u".$uid."r".$rubric_id."%");
		@$orderby = $configs["u".$uid."r".$rubric_id."fsort"];
		if((!empty($_GET['orderby']) && !empty($_GET['ordertype'])) || $orderby ){			$sort = 1;
			if(!empty($_GET['orderby']) && !empty($_GET['ordertype'])){
				$orderby = (int)$_GET['orderby'];
				$ordertype = $_GET['ordertype'];
				if($ordertype=="asc") $ordertype = "ASC"; else $ordertype = "DESC";
			} else {
				$orderby = (int)$orderby;
				@$ordertype = $configs["u".$uid."r".$rubric_id."f".$orderby."sort"];
				$sort = $ordertype;
				if($ordertype==1) $ordertype = "ASC"; else $ordertype = "DESC";
			}
		 if($sort>0)
		 {
	        $otype = getFeatureType($orderby);
			if($otype==8){
				$goodssql = "
					SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*, SUBSTRING(ord.goodfeature_value,7,4) AS ordbyY,SUBSTRING(ord.goodfeature_value,4,2) AS ordbyM,SUBSTRING(ord.goodfeature_value,1,2) AS ordbyD
					FROM
						".DB_PREFIX."goods NATURAL JOIN
						".DB_PREFIX."rubric_goods LEFT JOIN
						cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD and ord.ID_FEATURE=$orderby)
					WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
					ORDER BY ordbyY $ordertype, ordbyM $ordertype, ordbyD $ordertype, ".DB_PREFIX."rubric_goods.rubricgood_pos
				";
			} elseif($otype!=4){
				$goodssql = "
					SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*, ord.goodfeature_value AS ordby
					FROM
						".DB_PREFIX."goods NATURAL JOIN
						".DB_PREFIX."rubric_goods LEFT JOIN
						cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD and ord.ID_FEATURE=$orderby)
					WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
					ORDER BY ordby $ordertype, ".DB_PREFIX."rubric_goods.rubricgood_pos
				";
			} else {
				$goodssql = "
					SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*, fd.featuredirectory_text AS ordby
					FROM
						".DB_PREFIX."goods NATURAL JOIN
						".DB_PREFIX."rubric_goods
						LEFT JOIN cprice_goods_features AS ord ON (cprice_goods.ID_GOOD=ord.ID_GOOD and ord.ID_FEATURE=$orderby)
						LEFT JOIN cprice_feature_directory AS fd ON (ord.goodfeature_value=fd.ID_FEATURE_DIRECTORY and fd.ID_FEATURE=$orderby)
					WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
					ORDER BY ordby $ordertype, ".DB_PREFIX."rubric_goods.rubricgood_pos
				";
			}
		 }else $orderby=0;
		}
		if(@$asc=$configs["u".$uid."r".$rubric_id."fIDsort"] || (@$_GET['orderby']=="id")){
			if(@$_GET['ordertype']=='asc') $asc = 1; else $asc = 2;
			$goodssql = "
				SELECT ".DB_PREFIX."goods.*, ".DB_PREFIX."rubric_goods.*
				FROM ".DB_PREFIX."goods NATURAL JOIN ".DB_PREFIX."rubric_goods
				WHERE good_deleted=0 and rubricgood_deleted=0 and ID_RUBRIC=".$rubric_id."
				ORDER BY ".DB_PREFIX."goods.ID_GOOD ".(($asc==1)?"ASC":"DESC")."
			";
		}
		$num_feats = 0;

		@$count = (int)$configs["u".$uid."r".$rubric_id."CntOnPg"];
		if(!$count) $count = 20;


		@$vislist = $configs["u".$uid."r".$rubric_id."vislist"];
		$vislist1 = explode(";",$vislist);
		$vislist = array();
		$private_visible_features = "";
		foreach($vislist1 AS $i => $vislisti){
			if($vislisti) $private_visible_features .= "ID_FEATURE=$vislisti or ";
		}

        $OList = new teList($goodssql,$count);

		$class = (@$ordertype=="ASC")?"desc":"asc";
		$OList->addToHead("<a href='".teGetUrlQuery("orderby=id","ordertype=".((@$ordertype=="ASC")?"desc":"asc"))."' class='o'>ID</a>","class='$class'","width=1%");

		@$firstchangeshow = $configs["u".$uid."r".$rubric_id."fCRTshow"];
		if($firstchangeshow) $OList->addToHead("Дата создания","");

		@$lastchangeshow = $configs["u".$uid."r".$rubric_id."fLSTshow"];
		if($lastchangeshow) $OList->addToHead("Дата последнего изменения","");

		$OList->addToHead("Действия","colspan=4 width=1%");

		$n_feat = 0;
		if(!empty($private_visible_features))
		{
			$res1 = $database->query("SELECT ".DB_PREFIX."features.feature_text, ".DB_PREFIX."features.ID_FEATURE AS ID_FEATURE, ".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE $private_visible_features false GROUP BY ID_FEATURE ORDER BY rubricfeature_pos");
			$n_feat = mysql_num_rows($res1);
		}
		if($n_feat==0){
			$res1 = $database->query("SELECT ".DB_PREFIX."features.feature_text, ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$rubric_id." and rubric_type=".$type." and rubricfeature_ls_man=1 GROUP BY ID_FEATURE ORDER BY rubricfeature_pos");
			if(mysql_num_rows($res1)==0){
				$res1 = $database->query("SELECT ".DB_PREFIX."features.feature_text, ".DB_PREFIX."features.ID_FEATURE, ".DB_PREFIX."features.feature_type FROM ".DB_PREFIX."features NATURAL JOIN ".DB_PREFIX."rubric_features WHERE feature_deleted=0 and ID_RUBRIC=".$rubric_id." and rubric_type=".$type." GROUP BY ID_FEATURE ORDER BY rubricfeature_pos LIMIT 3");
			}
		}
        $arr_feats = array(); $sql_feat = ''; $br = '';
		while($line=mysql_fetch_array($res1)){
			if( $line[2]==1 || $line[2]==2 || $line[2]==3 || $line[2]==4 || $line[2]==8 ){
				$class = (@$ordertype=="ASC")?"desc":"asc";
				$OList->addToHead(getIdToPrint("features",$line[1]).". <a href='".teGetUrlQuery("orderby=$line[1]","ordertype=".((@$ordertype=="ASC" && @$orderby==$line[1])?"desc":"asc"))."' class='o'>".$line[0]."</a>","class='".(($orderby==$line[1])?"orderby":"")." ".$class."'");
			} else {
				$OList->addToHead(getIdToPrint("features",$line[1]).". ".$line[0]);
			}
			$arr_feats[$line[1]] = $line[2];
			$sql_feat .= $br."ID_FEATURE=".$line[1];
			$br = " || ";
			$num_feats++;
		}
		$i=0;$nreads=0;
		while($OList->row()){
			if( $OList->getValue("good_visible")==0 ){
				$s = "disabled";
			} else {
				$s = "";
			}

			$id_good = $OList->getValue("ID_GOOD");
			$arr_val = array();
			$res_val = $database->query("SELECT ID_FEATURE, goodfeature_value FROM ".DB_PREFIX."goods_features WHERE ID_GOOD=".$id_good." and ($sql_feat) group by ID_FEATURE");
			while($row_val = mysql_fetch_row($res_val))$arr_val[$row_val[0]]=$row_val[1];
			$arr_vals = array();
			$i_feats = 0;
			$name = '';
			foreach($arr_feats as $fid => $feature_type){
				@$answertext = $arr_val[$fid];
				if(empty($answertext)){
					$answertext = "";
				}
				switch($feature_type){
					case 7:
						if($answertext!="" && is_numeric($answertext)){
							$answertext = $database->getArrayOfQuery("SELECT text_text FROM ".DB_PREFIX."texts WHERE ID_TEXT=".$answertext);
							$answertext = $answertext[0];
						}
					break;
					case 3:
						$answertext = ($answertext!="")?"да":"нет";
					break;
					case 4:
						if(!empty($answertext) && $answertext!="-"){
							$answertext = getFeatureText($id_good, $fid);
							// $line1 = $database -> getArrayOfQuery("SELECT featuredirectory_text FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE_DIRECTORY=".$answertext);
							// $answertext = $line1[0];
						}
					break;
					case 10:
					case 5:
						if(!empty($answertext) && $answertext!="-"){
							list($val) = $database->getArrayOfQuery("
								SELECT goodfeature_value
								FROM ".DB_PREFIX."goods_features NATURAL JOIN ".DB_PREFIX."features
								WHERE ID_GOOD='$answertext' and feature_deleted=0 and feature_enable=1 and (feature_type=2)
								LIMIT 1
							");
							$answertext = getIdToPrint("goods",$answertext).": ".$val;
						}
					break;
					case 9:
						if(!empty($answertext) && $answertext!="-"){
							$answertext = getIdToPrint("rubric",(int)$answertext).": ".getRubricName($answertext,false,true,true);
						}
					break;
				}
				$arr_vals[$fid] = array($feature_type,$answertext);
				if(empty($name) && !empty($answertext)) $name = str_replace("\"","\\\"",$answertext);
				$i_feats++;
			}


			$name = smallText($name,50);


			$OList->addUserField("<b><a href='". teGetUrlQuery("pg=events_view_users","event_id=".$id_good."")."' onclick='return ws(this)'>".$id_good."</a></b>");
			if($firstchangeshow) $OList->addUserField(dateOfChange("goods",$id_good,"ASC"));
			if($lastchangeshow)  $OList->addUserField(dateOfChange("goods",$id_good));

			$reads = true;
			$res3 = $database -> query("SELECT * FROM ".DB_PREFIX."rubric_events where ID_GOOD=".$id_good." && ID_USER=".$_USER['id']);
			if(mysql_num_rows($res3)>0){
				$reads = false;
				$nreads++;
			}

            $link = teGetUrlQuery("idl=".$id_good);
            $OList->addUserField('<a href="'.$link.'">'.(!$reads ? 'Подробнее': '<b>Не&nbsp;прочитано</b>').'</a>');
			// действия
			if(@$acc['e']){
				$OList->addUserField(buttonEdit(teGetUrlQuery("=goods","rubric_id=".$rub_id,"action=edit","id={ID_GOOD}","events=1")));
				if( $OList->getValue("good_visible")==1 ){
					$OList->addUserField(buttonDisable(teGetUrlQuery("=goods","rubric_id=".$rub_id,"action=disable","id={ID_GOOD}","events=1")));
				} else {
					$OList->addUserField(buttonEnable(teGetUrlQuery("=goods","rubric_id=".$rub_id,"action=enable","id={ID_GOOD}","events=1")));
				}
			}
			if(@$acc['d']){
				// удаление
				$OList->addUserField(buttonDelete("javascript: if(confirm(\"Удалить «".$name."» без возможности восстановления?\")) location.href =\"".teGetUrlQuery("=goods","rubric_id=".$rub_id,"action=delete","id={ID_GOOD}","events=1")."\"", "Удалить запись"));
			}
			$link = teGetUrlQuery("=goods","rubric_id=".$rub_id,"action=view","id={ID_GOOD}","events=1");
			foreach($arr_vals as $f_id => $item)
			{
				$feature_type = $item[0];
				$answertext = $item[1];
				if($feature_type!=5 && $feature_type!=9){
					$OList->addUserField("<a href='".$link."'>".smallText(strip_tags($answertext),150)."</a>",$s.(($orderby==$f_id)?" class='orderby'":""));
				} else {
					$OList->addUserField($answertext,$s.(($orderby==$f_id)?" class='orderby'":""));
				}
			}

			while($i_feats<$num_feats){
				$OList->addUserField("");
				$i_feats++;
			}

			$i++;
		}
		$OList->addParamTable('');
		print '</div>';
		echo($OList->getHTML());
		print "<div align=center>";
		unset($OList);
	}
    if($read>0)
    	teRedirect(teGetUrlQuery("rub_id=".$rub_id));
	if($nreads>0) print '<br/><br/><a href="'.teGetUrlQuery("read=1").'">Пометить непрочитанные сообщения, как прочитанные</a>';
	print '<br/><br/><a href="'.teGetUrlQuery("pg=goods","action=list_settings","type=".$rub_type,"showid=0","rubric_id=".$rub_id).'">Настроить вид отображения важных событий по этой рубрике</a>';
	}
  }
	print '<br/><br/><a href="'.teGetUrlQuery("pg=").'">Все важные события и новости</a>';
}
function check_email_addr($email) {
        if (preg_match('/^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'. '@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$/i', $email)) return 1;
          else return 0;
}
print "</div>";
?>