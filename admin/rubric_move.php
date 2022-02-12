<?
// делал Никулин, дорабатывал Галлямов

 //teGetUrlQuery();
function NiRubricName($rubric_id)
{
	 global $database;
	 list($rn)=$database->getArrayOfquery("SELECT rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC='$rubric_id'");
	 return $rn;
}
//Функция просмотра ДОСТУПНЫХ подрубрик (МОЯ)

function N_podrub($rubric,$type,$except=array(),$cnt=false)
{
	global $database;
    // если $cnt не указан (ф-я вызвана 1-й раз), $kol = 0, иначе $cnt + 1;
 	$kol = (!$cnt)?1:$cnt+1;
 	//print "вызвана $kol раз<br>";
	$strexp=implode("','",$except);
	$i=1;
	//список рубрик для текущей закладки

	$pres=$database->query("SELECT ID_RUBRIC,rubric_name FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC not in ('".$strexp."') and rubric_parent='$rubric' and rubric_parent not in ('".$strexp."') and rubric_deleted=0 and rubric_close=0 and rubric_type='$type'");
	if(mysql_num_rows($pres)!=0)
	{
		//Заполняем массив рубрик
		while($prow=mysql_fetch_array($pres))
		{
	       	$arr[$i][$prow['ID_RUBRIC']]=$prow['rubric_name'];
	       	$i++;
		}
		//return $arr;
		//Выводим массив рубрик


			foreach($arr as $key=>$val)
			{
				foreach($val as $idr=>$rn)
				{
					$pad=$kol*10;
					$strexp=implode("','",$except);
					$query="SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_parent not in ('".$strexp."')and rubric_type=$type and rubric_close=0 and ID_RUBRIC=$idr and ID_RUBRIC not in ('".$strexp."') and rubric_deleted=0";
					list($EXP)=$database->getArrayOfquery($query);
					if(!empty($EXP))
					{
						//<INPUT TYPE=\"radio\" NAME=\"newrub_id_type$d\" value={formname}>{name}</div>"
						print "<tr><td valign='top'><div style='padding-left: $pad%;text-align:left;'><input type='checkbox' name='rub[$idr]' value='1' />($idr) - $rn</div></td></tr>";
						//print "<tr><td valign='top'><div style='padding-left: $pad%'><a href='".teGetUrlQuery("action=multiple_goods","rubric_id=$idr")."'>($idr) - $rn</a></div></td></tr>";

					}
				}

				N_podrub($idr,$type,$except,$kol);
			}
	}
}
		function get_child($type, $id, $template, $cnt=false, $rrr = false){
			global $database;
			global $iii;
			$s = "";
			$res = $database -> query("SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_deleted=0 and rubric_close=0 and rubric_type=".$type." and rubric_parent=".$id." ORDER BY rubric_name");
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
		}//End function get_child
			//Функция обновление записей
			function set_goods($rubric_id)
			{
				global $database;
				$quer="SELECT * FROM ".DB_PREFIX."rubric_goods WHERE ID_RUBRIC=$rubric_id";
				$res=$database->query($quer);
				if(mysql_num_rows($res)!=0)
				{
					while($row=mysql_fetch_array($res))
					{
						print "<font color='green'>Рубрика-$rubric_id Запись".$row['ID_GOOD']."</font><br>";
					}

				}
			}
			//Функция заполнения записей рубрики при копировании
			//rubric_id -на основе какой рубрики
			//new_rub -в какую
			function add_rub_good($rubric_id,$new_rub,$new_feats)
			{
				global $database;
				//print "<font color='Fuchsia'>$rubric_id-$new_rub</font><br>";
				$query="SELECT * FROM ".DB_PREFIX."rubric_goods t1 natural join ".DB_PREFIX."goods t2 WHERE t1.ID_RUBRIC='$rubric_id' && t2.good_deleted=0";
				$func_res=$database->query($query);
                while($func_row=mysql_fetch_array($func_res))
                {
                   	$query="INSERT INTO ".DB_PREFIX."goods (good_visible) VALUES(1)";
                   	$database->query($query);
                   	$id_good=$database->id();

                   	$query="INSERT INTO ".DB_PREFIX."rubric_goods (ID_RUBRIC,ID_GOOD) VALUES($new_rub,'".$id_good."')";
                   	$database->query($query,true,0,$id_good);
                   	del_cache($new_rub);//удаление кэша

               		//Копируем url
					$arr_db_urls = array(25,22,23,19);
					if(DB_ID>48 || in_array(DB_ID,$arr_db_urls))
					{
						list($good_url) = $database->getArrayOfQuery("SELECT good_url FROM ".DB_PREFIX."goods WHERE ID_GOOD=".$func_row['ID_GOOD']);
						$j_g = 0;
						while($database -> getArrayOfQuery("SELECT ID_GOOD FROM ".DB_PREFIX."goods WHERE good_url='".$good_url.(empty($j_g)?"":"$j_g")."'")){
							$j_g++;
						}
						$good_url = $good_url.(empty($j_g)?"":"$j_g");
						$database->query("UPDATE ".DB_PREFIX."goods SET good_url='$good_url' WHERE ID_GOOD=$id_good",false);
					}
					
               		//Копируем фото
               		$res = $database->query("select * from cprice_goods_photos where ID_GOOD=".$func_row['ID_GOOD']." && goodphoto_visible=1 && goodphoto_deleted=0");
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
               			$database->query("INSERT INTO ".DB_PREFIX."goods_photos (ID_GOOD, goodphoto_file,goodphoto_desc,goodphoto_alt,goodphoto_pos) VALUES('".$id_good."','".$filename."','".$row[3]."','".$row[4]."','".$row[5]."')");
               		}
					//Копируем сео-параметры
                   	$row_seo = $database->getArrayOfquery("SELECT * FROM ".DB_PREFIX."metadata WHERE metadata_page=3 && metadata_id='".$func_row['ID_GOOD']."' limit 1");
                   	if($row_seo)
                   	{
                   		$database->query("INSERT INTO ".DB_PREFIX."metadata (metadata_page,metadata_id,metadata_head_title,metadata_meta_title,metadata_meta_keywords,metadata_meta_description,metadata_body_h1,metadata_body_h2,metadata_body_description,metadata_body_keywords)
                   			VALUES (3,'$id_good','$row_seo[3]','$row_seo[4]','$row_seo[5]','$row_seo[6]','$row_seo[7]','$row_seo[8]','$row_seo[9]','$row_seo[10]')");

                   	}

             		$res = $database->query("select * from cprice_goods_features natural join cprice_features  natural join cprice_rubric_features where ID_GOOD=".$func_row['ID_GOOD']." group by ID_FEATURE order by rubricfeature_pos,ID_FEATURE");
               		$i=0;
               		while($row = mysql_fetch_array($res))
               		{
               			if($row['feature_type']==7 && $row['goodfeature_value']>0)
               			{
               				$line = $database->getArrayOfquery("select text_text from ".DB_PREFIX."texts where ID_TEXT=".$row['goodfeature_value']);
	           				$database->query("INSERT INTO ".DB_PREFIX."texts (text_text) VALUES('".$line[0]."')");
	           				$text_id = $database->id();
	           				$database->query("INSERT INTO ".DB_PREFIX."goods_features (ID_GOOD, ID_FEATURE,goodfeature_value,goodfeature_visible) VALUES('".$id_good."','".$new_feats[$i]."','".$text_id."','".$row['goodfeature_visible']."')");
	           			}
	               		else $database->query("INSERT INTO ".DB_PREFIX."goods_features (ID_GOOD, ID_FEATURE,goodfeature_value,goodfeature_visible) VALUES('".$id_good."','".$new_feats[$i]."','".$row['goodfeature_value']."','".$row['goodfeature_visible']."')");
	               		$i++;
	               	}
                }
		    }//END add_rub_good;
           	//Рекурсия заполнения характеристик
           	// Функция заполнения характеристик
           	function set_fut($rubric_id,$type,$new_type,$newrub)
			{
                    global $database;

					#print "<font color='red'>Старая главная рубрика $rubric_id<br>";
					#print "из закладки $type<br>";
					#print "В закладку $new_type<br>";
					#print "Новая рубрика $newrub</font><br>";

   					 //print "Заполняем характеристики для рубрики копированной из $rubric_id в $newrub<br>";

         		$res=$database->query("SELECT * FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=$rubric_id && rubric_type=$type order by rubricfeature_pos,ID_FEATURE");
				$i=1;
                $feat=array();
                $grad=array();
                $pos =array();
						while($row=mysql_fetch_array($res))
						{
							$feat[$i]=$row['ID_FEATURE'];
							$grad[$i]=$row['rubricfeature_graduation'];
							$pos[$i]=$row['rubricfeature_pos'];
                            //print "feat[$i]=".$feat[$i]."<br>";
							$i++;
						}
				$new_feats=array();
				@$crt = (int)$_POST['crt_feat'];
                 for($i=1;$i<=count($feat);$i++)
      			 {
      			   if($crt>0)
      			   {
	      			   $qu="SELECT ID_FEATURE FROM ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=$newrub and rubric_type=$new_type and ID_FEATURE=$feat[$i]";
	                   $pris=$database->getArrayOfquery($qu);
	                   if(empty($pris[0]))
	                   {
	                   //	print "такой нет добавляем-";
	                   		$featr=$database->getArrayOfquery("select * from ".DB_PREFIX."features where ID_FEATURE=".$feat[$i]);
	                   		$database->query("INSERT INTO ".DB_PREFIX."features (feature_text,feature_type,feature_unique,feature_require,feature_multiple,feature_graduation,feature_default)
	                   			VALUES('".$featr[1]."','".$featr[3]."','".$featr[4]."','".$featr[5]."','".$featr[6]."','".$featr[7]."','".$featr[8]."')");
	                   		$id = $database->id();
	                   		$new_feats[] = $id;
	                   		$res1 = $database->query("select * from ".DB_PREFIX."feature_directory where ID_FEATURE=".$feat[$i]);
	                   		while($row1 = mysql_fetch_array($res1))
		                   		$database->query("INSERT INTO ".DB_PREFIX."feature_directory (ID_FEATURE,featuredirectory_text) VALUES('".$id."','".$row1[2]."')");
	                   		$que="INSERT INTO ".DB_PREFIX."rubric_features (ID_RUBRIC,rubric_type,ID_FEATURE,rubricfeature_graduation,rubricfeature_pos) VALUES('".$newrub."','".$new_type."','".$id."','".$grad[$i]."','".$pos[$i]."')";
	                   		$database->query($que);
	                   }
                   }else{                   		$new_feats[]=$feat[$i];                   		$que="INSERT INTO ".DB_PREFIX."rubric_features (ID_RUBRIC,rubric_type,ID_FEATURE,rubricfeature_graduation,rubricfeature_pos) VALUES('".$newrub."','".$new_type."','".$feat[$i]."','".$grad[$i]."','".$pos[$i]."')";
                   		$database->query($que);
                   }
                 }
                 return $new_feats;
            }
		   //Функция выводит список всех подрубрик данной рубрики
		   //Функция для операции копирования
		    function view_rubric($rubric_id,$rubrics=array(),$type,$new_type,$parent,$newrub,$cop_good)
		    {
	              @$cop_good = (int)$_REQUEST['cop_good'];
              // print "Выполняется...";
					  #print "rubric_id =".$rubric_id."<br>";
                      #print "type=".$type."<br>";
				//print "cop_good=".$cop_good;die();
    			global $database;

                //Выбираем все рубрики у которых родитель rubric_id
    			$query="SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_parent=$rubric_id and rubric_type=$type and rubric_visible=1 and rubric_deleted=0";
    			//print "след рубрика".$query."<br>";
    		$fres=$database->query($query);
    		//Случай если у текущей рубрики нет своих подрубрик
    			if (mysql_num_rows($fres)==0)
    			{
    				//print "<div align='left'><font color='red'>Подрубрика ".$rubric_id."</font></div><br>";
    				//print "<font color='Navy'>Нет подрубрик у нее</font><br>";
    				$query="SELECT ID_RUBRIC,rubric_name,rubric_visible FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=$rubric_id and rubric_type=$type and rubric_visible=1 and rubric_deleted=0";
    				//Обрабатываем данную подрубрику
					$tekRub=$database->getArrayOfquery($query);
					if(in_array($rubric_id,$rubrics))
					{
                         $newrubname=$tekRub['1']."copy_podr1";
						 $new_rub="INSERT INTO ".DB_PREFIX."rubric (rubric_parent,rubric_name,rubric_visible,rubric_type) VALUES(".$parent.",'".$newrubname."','".$tekRub['2']."',$new_type)";
                          $database->query($new_rub);
                          del_cache($parent);//удаление кэша
                          $row=$database->getArrayOfquery("SELECT MAX(ID_RUBRIC) FROM ".DB_PREFIX."rubric WHERE rubric_type=$new_type and rubric_visible=1");
                          //Заполняем характеристики  у конечной рубрики
                          //print "<font color='green'>row[0]=".$row[0]."</font><br>";
                          $new_f = set_fut($rubric_id,$type,$new_type,$row[0]);
                          if ($cop_good==1)
                          {
							add_rub_good($rubric_id,$row[0],$new_f);
                          }
					}
    			}
   		    //Случай если у текущей рубрики есть свои подрубрики
					//Обрабатываем данную подрубрику
   		        while($frow=mysql_fetch_array($fres))
                {
					if(in_array($frow['ID_RUBRIC'],$rubrics))
					{
						//print "<div align='left'><font color='red'>Подрубрика ".$frow['rubric_name']." у рубрики $parent</font></div><br>";
						$newrubname=$frow['rubric_name']."copy_podr2";
						$new_rub="INSERT INTO ".DB_PREFIX."rubric (rubric_parent,rubric_name,rubric_visible,rubric_type) VALUES(".$parent.",'".$frow['rubric_name']."','".$frow['rubric_visible']."',$new_type)";
						print $new_rub."<br>";
						//Проверяем если подрубрики у текущей рубрики
						$database->query($new_rub);
						del_cache($parent);//удаление кэша
						//set_fut($rubric_id,$type,$new_type,$newrub);
						//print "par=".$quer."<br>";

                     $row=$database->getArrayOfquery("SELECT MAX(ID_RUBRIC) FROM ".DB_PREFIX."rubric WHERE rubric_type=$new_type and rubric_visible=1 and rubric_deleted=0");
                     $new_f = set_fut($frow['ID_RUBRIC'],$type,$new_type,$row[0]);
                     if ($cop_good==1)
                     {
						add_rub_good($frow['ID_RUBRIC'],$row[0],$new_f);
                     }
                      //set_fut1( $rubric_id , $feat=array() , $type , $new_type)
                      $quer="SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric WHERE rubric_type=$type and rubric_deleted=0 and rubric_parent=".$frow['ID_RUBRIC'];

                  	 $fres1=$database->query($quer);
                      if (mysql_num_rows($fres1)==0)
                      {	//print "Нет вложенных подрубрик<br>";
                      }
                      else
                      {
                       //Если есть вложенные подрубрики то рекурсивно вызываем функцию для каждой подрубрики
                        while($frrr=mysql_fetch_array($fres1))
                        {
                       	  $farow=$database->getArrayOfquery("SELECT MAX(ID_RUBRIC) FROM ".DB_PREFIX."rubric WHERE rubric_type=$new_type and rubric_visible=1 and rubric_deleted=0");
                       	  //print "Вложенная-".$frrr['ID_RUBRIC']."<br>";
                           view_rubric($frrr['ID_RUBRIC'],$rubrics,$type,$new_type,$farow[0],$newrub,$cop_good);
                        }
                      }
					}
	            }
				//set_fut($rubric_id,$type,$new_type,$newrub);
			}//END view_rubric
			//Установка новых родителей для рубрики
			function setnewrub($rubric_id,$newrub_id,$type,$newrubtype_id)
	    	{
					global $database;
					$quer="SELECT * FROM ".DB_PREFIX."rubric WHERE rubric_parent=$rubric_id and rubric_type=$type and rubric_deleted=0";
                   	$res=$database->query($quer);
                   	         #Изменяем характеристики рубрики
                    	  	 $res_fetur=$database->query("SELECT * FROM ".DB_PREFIX."rubric_features WHERE rubric_type=$type and ID_RUBRIC='".$rubric_id."'");
                    	  	 while($fetrow=mysql_fetch_array($res_fetur))
                    	  	 {
                    	  	 	$fur="UPDATE ".DB_PREFIX."rubric_features SET rubric_type=$newrubtype_id WHERE ID_RUBRIC='".$rubric_id."' and ID_FEATURE='".$fetrow['ID_FEATURE']."'";
                    	  	 	print "$fur<br>";
                    	  	 	$database->query($fur);
                    	  	 }
                             set_goods($rubric_id);
                   	if(mysql_num_rows($res)==0){print "Нет подрубрик<br>";}
                     else
                     {
                    	 // $podr=$database->getArrayOfquery("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric WHERE ");
                    	  //Присвоение parent_rubric данной подрубрики
                    	  while($row=mysql_fetch_array($res))
                    	  {
                    	  	 $quers="UPDATE ".DB_PREFIX."rubric SET rubric_parent=$rubric_id, rubric_type=$newrubtype_id WHERE ID_RUBRIC=".$row['ID_RUBRIC'];

                    	  	 print $quers."<br>";
                    	  	 $database->query($quers);
                             setnewrub($row['ID_RUBRIC'],$newrub_id,$type,$newrubtype_id);
                    	  }
                    	  //Рекурсивно вызываем функцию для нижестоящих по иерархии подрубрик
                     }
	        }
//Основная функция выполнения перемещения или копирования
function mov_or_copy($rubrics=array(),$type,$newrub_id,$newrubtype_id,$act,$cpg)
{
	global $database;
       	@$cop_good = (int)$_REQUEST['cop_good'];
	//Перемещение
	if($act=="move")
	{
		foreach($rubrics as $rubric_id)
		{
			$query="SELECT ID_FEATURE FROM ".DB_PREFIX."rubric_features WHERE rubric_type=$type and ID_RUBRIC='".$rubric_id."'";
			$res = $database -> query($query);
			$i=1;
			$rfeat=array();
			while($row=mysql_fetch_array($res))
			{
				$rfeat[$i]=$row['ID_FEATURE'];
				$i++;
			}

			$query="SELECT ID_FEATURE FROM ".DB_PREFIX."rubric_features WHERE rubric_type=$newrubtype_id and ID_RUBRIC='".$newrub_id."'";
			$res = $database -> query($query);
			$i=1;
			$nrfeat=array();
			while($row=mysql_fetch_array($res))
			{
				$nrfeat[$i]=$row['ID_FEATURE'];
				$i++;
			}
			$add_new_fut=array();
			for($i=1;$i<=count($nrfeat);$i++)
			{
				if(!in_array($nrfeat[$i],$rfeat))
				{
					 if(!in_array($nrfeat[$i],$add_new_fut))
					 {
						//print "Свойства ".$nrfeat[$i]." нет в рубрике $rubric_id<br/>";
						$add_new_fut[count($add_new_fut)+1]=$nrfeat[$i];
					 }
				}
			}
			if(count($add_new_fut)>0)
			{
				for($i=1;$i<=count($add_new_fut);$i++)
				{
					$query="INSERT INTO ".DB_PREFIX."rubric_features (ID_RUBRIC,ID_FEATURE,rubric_type) VALUES('".$rubric_id."','".$add_new_fut[$i]."','".$type."')";
					$database->query($query);
				}
			}

			$query2="UPDATE ".DB_PREFIX."rubric SET rubric_parent=$newrub_id, rubric_type=$newrubtype_id WHERE ID_RUBRIC=$rubric_id";
			del_cache($rubric_id,0,true);//удаление кэша
			$database->query($query2);
			//Установка новых родителей для подрубрик
			if($type!=$newrubtype_id) setnewrub($rubric_id,$newrub_id,$type,$newrubtype_id);
		}
        } //End if act=move
	//Копирование
	if($act=="copy")
	{
		$parents = array();
		//В строку все элементы рубрики
		$elem_rub=implode("','", $rubrics);
	        foreach($rubrics as $key=>$t_rub)
		{
			//Нет ли родителей у текущей рубрики среди выбранных
			list($is_parrent)=$database->getArrayOfquery("SELECT rubric_parent FROM ".DB_PREFIX."rubric WHERE rubric_type=$type and rubric_parent in ('$elem_rub') and ID_RUBRIC='$t_rub'");
			if (empty($is_parrent))
			{
				//print "нет вышестоящих $t_rub <br>";
				//массив рубрик Самых верхних в иерархии среди выбранных
				$parents[]=$t_rub;
			}
		}

		foreach ($parents as $id_prn)
		{
			$query="SELECT rubric_name,rubric_visible,ID_RUBRIC,rubric_unit_prefixname,rubric_ex,rubric_img,rubric_textid FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC='$id_prn' and rubric_type=$type and rubric_deleted=0";
			$row=$database->getArrayOfquery($query);
			$new_type=$newrubtype_id;
			$NEWRUB=$row[0];

			//копирование url рубрики
			$rubric_textid = $row[6];
			$j = 0;
			while($database -> getArrayOfQuery("SELECT rubric_textid FROM ".DB_PREFIX."rubric WHERE rubric_textid='".$rubric_textid.(empty($j)?"":"$j")."' && rubric_deleted=0 && rubric_type=".$new_type)){
				$j++;
			}
			$rubric_textid = $rubric_textid.(empty($j)?"":"$j");

			$database->query("INSERT INTO ".DB_PREFIX."rubric (rubric_parent,rubric_name,rubric_visible,rubric_type,rubric_unit_prefixname,rubric_ex,rubric_img,rubric_textid) VALUES($newrub_id,'".$NEWRUB."','".$row[1]."',$new_type,'".$row[3]."','".$row[4]."','".$row[5]."','".$rubric_textid."')");
			del_cache($newrub_id);//удаление кэша
			//Ищем максимальный элемент в таблице rubric
			$new_rub = $database->id();

			//Копируем сео-параметры
			$row_seo = $database->getArrayOfquery("SELECT * FROM ".DB_PREFIX."metadata WHERE metadata_page=2 && metadata_id='$id_prn' limit 1");
			if($row_seo)
			{
				$database->query("INSERT INTO ".DB_PREFIX."metadata (metadata_page,metadata_id,metadata_head_title,metadata_meta_title,metadata_meta_keywords,metadata_meta_description,metadata_body_h1,metadata_body_h2,metadata_body_description,metadata_body_keywords)
					VALUES (2,'$new_rub','$row_seo[3]','$row_seo[4]','$row_seo[5]','$row_seo[6]','$row_seo[7]','$row_seo[8]','$row_seo[9]','$row_seo[10]')");

			}
			//копируем конфиг параметры (модули)
			$res_cnf = $database->query("SELECT * FROM ".DB_PREFIX."configtable WHERE var_name like 'rtpl_{$newrub_id}%'");
			while($row_cnf = mysql_fetch_array($res_cnf)) $database->query("INSERT INTO ".DB_PREFIX."configtable (var_name,var_value) VALUES ('".str_replace($newrub_id,$new_rub,$row_cnf[0])."','$row_cnf[1]')");
			//Добавляем характеристики для новой скопированной рубрики
			$new_f = set_fut($id_prn,$type,$new_type,$new_rub);
			if ($cop_good==1)
				add_rub_good($row[2],$new_rub,$new_f);
			if($newrub_id>0)
			{
				$res1 = $database->query("select ID_FEATURE from ".DB_PREFIX."rubric_features WHERE ID_RUBRIC=$newrub_id && rubric_type=$new_type");
				while($row1=mysql_fetch_array($res1))
					if(!in_array($row1[0],$new_f))
						$database->query("INSERT INTO ".DB_PREFIX."rubric_features (ID_RUBRIC,ID_FEATURE,rubric_type) VALUES('".$new_rub."','".$row1[0]."','".$new_type."')");
			}
			$res2 = $database->query("SELECT ID_RUBRIC FROM ".DB_PREFIX."rubric WHERE rubric_parent='$id_prn' && rubric_deleted=0");
			$arr_rub = array();
			while($row2 = mysql_fetch_array($res2))$arr_rub[]=$row2[0];
			$childs = @$_POST['cop_chld'];
			if(count($arr_rub)>0 && $childs!=1)mov_or_copy($arr_rub,$type,$new_rub,$newrubtype_id,$act,$cpg);
		}
	}
}//end move_rubric
//Конец блока описания функций
print_link_up();
print "<a href='javascript:history.back()'>назад</a>";
$type=$_REQUEST['type'];
print "<div align=\"center\">";
print "<h1>Перемещение рубрики</h1>";

# Шаг 1 для перемещения по иерархии
if(empty($_POST['setrubrics']))
{
	#Шаг 1 для перемещения по закладкам
	print "<h2>Шаг-1 Выбор рубрики для перемещения по Закладкам</h2><br/>";
	print "<font color='green'>Выберите рубрики для копирования/перемещения</font><br/>";
	print "<form method=\"POST\">";
	print "<table><tr>";
	$iii=0;
	$res = $database->query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 and ID_RUBRIC_TYPE=$type");
	while(list($d,$rtn)=mysql_fetch_array($res)){
		$iiid = $iii;
		$sss = get_child($d, 0, "<div style='padding-left:".TREE_LEFT."em'><input type='checkbox' name=rubric_id"."{formname} value=1>{name}</div>",$iii);
		$ss = "";
		$ss = "<div>".$rtn."</div>";
		print "<td valign='top'>".$ss.$sss."</td>";
	}
	$res = $database->query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0");
	$ss ="<div>Куда переместить/скопировать?</div>";
	$br='CHECKED';
	while(list($d,$rtn)=mysql_fetch_array($res)){
		$ss .= "<div><INPUT TYPE=\"radio\" NAME=\"newrub_id_type\" value='".$d."' ".$br.">".$rtn."</div>";
		$br='';
	}
	print "<td valign='top'>".$ss."</td>";
	print "</tr></table>";
	print "<input type='hidden' name='action' value='move_rubric'>";
	//Рубрики выбраны -да
	print "<input type='hidden' name='setrubrics' value='1'>";
	//Закладка
	print "<input type='hidden' name='type' value='$type'>";
	print "<input type='submit' value='Далее'>";
	print "</form>";
}
elseif ($_POST['setrubrics']==1)
// Шаг 2 для перемещения по иерархии
{
	print "<form method='POST'>";
	print "<h2>Шаг-2 Перемещаем рубрики по Закладкам</h2><br/>";

	print "<form method='POST'>";
	print "<table>";
	print "<tr><td ROWSPAN='2' valign='top'>Выберите тип действия :</td>";
	print "<td valign='top'><input type='radio' name='actions_type' value='copy' CHECKED onClick=\"this.form.cop_good.disabled=false;this.form.cop_feat.disabled=false;this.form.cop_chld.disabled=false;\" />Копирование</td>";
	print "<td align='left'><input type='checkbox' id='cop_good' name='cop_good' value='1'>скопировать записи<br/>
		<input type='checkbox' id='cop_feat' name='crt_feat' value='1' />создавать новые хар-ки<br/>
		<input type='checkbox' id='cop_chld' name='cop_chld' value='1' >только себя (без подрубрик)
		</td></tr>";
	print "<tr><td><input type='radio' name='actions_type' value='move' onClick=\"this.form.cop_good.disabled=true;this.form.cop_feat.disabled=true;this.form.cop_chld.disabled=true;\" />Перемещение</td><td></td></tr>";
	print "</table>";
	print "<br><font>Выберите куда нужно переместить рубрику:</font><br/>";

	print "<table><tr><td width='300'><div style='font-weight:bold;text-align:left;'><input type='checkbox' name='rub[0]' value='1' />В корень</div></td></tr>";
  	$iii=0;
	$res = $database->query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types WHERE rubrictype_visible=1 and rubrictype_deleted=0 and ID_RUBRIC_TYPE=".$_REQUEST['newrub_id_type']);
	while(list($d,$rtn)=mysql_fetch_array($res)){
		$iiid = $iii;
		$except=array();#-Массив исключений рубрик
		// Заполняем рубрики
		foreach($_POST as $name=>$val)
		{
			if(substr($name,0,9)=='rubric_id')
			{
				$except[]=substr($name,9);
			}
		}
		N_podrub(0,$_REQUEST['newrub_id_type'],$except);
		$ss = "";
	}
	print "</table>";
	foreach($_POST as $name=>$value)
	{

		if(substr($name,0,9)=='rubric_id')
		{
			print "<input type='hidden' name='$name' value='$value'>\r\n";
		}
	}
	print "<input type='hidden' name='newrubtype_id' value='".$_REQUEST['newrub_id_type']."'>\r\n";
	print "<input type='hidden' name='action' value='move_rubric'>\r\n";
	print "<input type='hidden' name='type' value='$type'>\r\n";
	print "<input type='hidden' name='setrubrics' value='2'>\r\n";
	print "<input type='submit' value='Далее'>\r\n";
	print "</form>";
}else
{
	# Шаг 3 для перемещения по закладкам
	$newrubtype_id = $_REQUEST['newrubtype_id'];
        $rubs = $_POST['rub'];
	$act=$_POST['actions_type'];
	if(isset($_POST['cop_good'])){$cpg=$_POST['cop_good'];}else{$cpg=0;}

	$rubrics=array();
	foreach($_POST as $k=>$v)
	{
		//print $k."-".$v."<br>";
		if(substr($k,0,9)=='rubric_id')
		{
			//Заполняем массив копируемых рубрик
			$rubrics[]=substr($k,9);
		}
	}
    	if(count($rubs)>0)
        {
		print "<h2>Шаг-3 Перемещаем выбранные рубрики</h2><br/>";
                foreach($rubs as $newrub_id=>$val)
                {
			mov_or_copy($rubrics,$type,$newrub_id,$newrubtype_id,$act,$cpg);
                }
	}
	teRedirect("?pg=rubric&type=$type");
}
print "</div>";
?>