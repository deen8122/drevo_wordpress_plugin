<?php
// никулин а


class triger
{
               //Генерация новости
               var $param= array();
               var $my_arr_price1;//массив первичных цен
               var $my_arr_price2;//массив измененных цен
               //if( teGetConf("news_tmpl".$type)==1 )-Условие
    //-----------------ФУНКЦИЯ Проверка цен---------------------------------
                function compare_array($ar1 = array(),$ar2 = array(),$ID_good,$R_type)
                {//Функция проверяет начальное и конечное состояние массивов цен


                 global $database;
                    foreach($ar1 as $key=>$value)
                    {
                     if($ar1[$key]==$ar2[$key]){$t_shab=0;}
                     if($ar1[$key]>$ar2[$key]){$na=$ar1[$key]-$ar2[$key];$t_shab=5;}
                     if($ar1[$key]<$ar2[$key]){$na=$ar2[$key]-$ar1[$key];$t_shab=4;}
                       if ($t_shab!=0)
                       {
                        if(teGetConf("news_tmpl".$R_type."_".$t_shab)==1)
                        {
                        //Ищем случайную запись
                                     $min_usenum="(SELECT min(goodnewtemplate_usenum) FROM ".DB_PREFIX."goods_news_templates WHERE ID_RUBRIC_TYPE=$R_type and goodnewtemplate_deleted=0 and goodnewtemplate_priority=1 and goodnewtemplate_type=".$t_shab.")";
                                     $query="SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE ID_RUBRIC_TYPE=$R_type and goodnewtemplate_type=$t_shab and goodnewtemplate_deleted=0 and goodnewtemplate_priority=1 and goodnewtemplate_usenum=$min_usenum";
                                     $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                          if(mysql_num_rows($mysql_result)!=0)
                          {
                                         $num_rows = mysql_num_rows($mysql_result);
                                          $i=1;
                                         while ($arow=mysql_fetch_array($mysql_result))
                                         {
                                          $my_id=$arow['ID_GOOD_NEW_TEMPLATE'];
                                          $my_arr[$i]=$my_id;

                                          $i++;
                                         }
                                            $a=rand(1,$num_rows);
                                   if(isset($my_arr))
                                    {// есть ли вообще шаблоны
                                       $sz=$my_arr[$a];//-случайная запись
                                    }else{return 0;}

                           //----------------------------------
                             //Подключаемся к goods_news_templates
	                         $query="SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE ID_GOOD_NEW_TEMPLATE=$sz and ID_RUBRIC_TYPE=$R_type and goodnewtemplate_type=$t_shab and goodnewtemplate_deleted=0";
	                         $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
	                         if (mysql_num_rows($mysql_result)==0){return 0;}
	                         $row=mysql_fetch_array($mysql_result);

                              $str_from[0]="{name}";
                              $str_from[1]="{date}";
                              $str_from[2]="{ntime}";
                              $str_from[3]="{lprice}";
                              $str_from[4]="{nprice}";
                              $mydate=date("Y-m-d");
                              $mytime=date("H:i:s");

                              //$query="SELECT * FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=239 and ID_FEATURE_DIRECTORY=".$ID_good;
                              //$query="SELECT * FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=239 and ID_FEATURE_DIRECTORY=(select goodfeature_value from ".DB_PREFIX."goods_features where ID_FEATURE=239 and ID_GOOD=".$ID_good.")";
                              //$my111_result = $database->query($query) or die("Invalid query: " . mysql_error());
                              //$row111=mysql_fetch_array($my111_result);
                              //$repl_array[0]=$row111['featuredirectory_text'];
                              $repl_array[0]=$ID_good;

                              //$repl_array[0]="код".$ID_good." ";

                              $repl_array[1]=$mydate." ";
                              $repl_array[2]=$mytime." ";
                              $repl_array[3]=$ar1[$key]." ";
                              $repl_array[4]=$ar2[$key]." ";

                              $bufer_title=$row['goodnewtemplate_title'];
                              $bufer_text=$row['goodnewtemplate_text'];
                              $my_text=str_replace($str_from,$repl_array,$bufer_text);
                              $my_title=str_replace($str_from,$repl_array,$bufer_title);
                                    //print $my_title."<br>";
                                    //print $my_text."<br>";
	                       $query="INSERT INTO ".DB_PREFIX."goods_news (goodnew_title,goodnew_text,goodnew_type,ID_RUBRIC_TYPE) VALUES ('".$my_title."','".$my_text."','".$t_shab."','".$R_type."')";
	                       $database -> query("UPDATE ".DB_PREFIX."goods_news_templates SET goodnewtemplate_usenum=goodnewtemplate_usenum+1 WHERE ID_GOOD_NEW_TEMPLATE=".$sz);
	                       $database->query($query)or die("Invalid query: " . mysql_error());
                           }else //конец проверки шаблонов
							{return 0;}
                        //----------------------------------
						}//end if $t_shab!=0
                       }//end teGetconf....
                    } //end eache

                }
    //--------------------КОНЕЦ ФУНКЦИИ----------------------------
    //---------------ФУНКЦИЯ выяснение текущего состояния цены-------
                function what_price($R_type,$ID_good)
                {
						global $database;
                        $current_price='';
                        $query="SELECT * from ".DB_PREFIX."configtable where var_name REGEXP '^p_' and var_value=$R_type";
                        $result=$database->query($query);
                        if(mysql_num_rows($result)!=0)
                        {
                          $i=1;
                          while($row=mysql_fetch_array($result))
                          {
                          $pr=substr($row['var_name'],2,-2);
                          //print "pr=$pr";
                                 $query="SELECT * from ".DB_PREFIX."goods_features where ID_FEATURE=$pr and ID_GOOD=$ID_good";
                                 $res2=$database->query($query);
                                 if(mysql_num_rows($res2)!=0)
                                 {
                                   $row2=mysql_fetch_array($res2);
                                   $current_price[$row2['ID_FEATURE']]=$row2['goodfeature_value'];
                                   $i++;
                                 }
                          }
                        }
                        return $current_price;
                }
                //////
    //---------------КОНЕЦ ФУНКЦИИ---------------------
    //----------------ФУНКЦИЯ НОВОСТИ УДАЛЕНИЕ ЕДЕНИЦЫ ТОВАРА--------
                function News_del_good($R_type,$templ_type,$nID,$pID,$intime=false)
                {
                global $database;
                 if( teGetConf("news_tmpl".$R_type."_".$templ_type)==1)
                 {
                  //--------Подготовка переменных
                         $query="SELECT * FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$pID;
                         $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                         $row=mysql_fetch_array($mysql_result);
                         $str_from[0]="{pname}";
                         $repl_array[0]=$row['rubric_name'];
                         $i=1;
                         $query="SELECT * FROM ".DB_PREFIX."goods_features WHERE goodfeature_visible=1 and ID_GOOD=".$nID;
                         $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                         while ($row=mysql_fetch_array($mysql_result))
                                {
                                    $str_from[$i]="{".$row['ID_FEATURE']."}";
                                             $fdt=intval($row['goodfeature_value']);
                                             $query="select * FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$row['ID_FEATURE']." and ID_FEATURE_DIRECTORY=".$fdt;
                                             $my1_result = $database->query($query) or die("Invalid query: " . mysql_error());
                                             if (mysql_num_rows($my1_result)>0)
                                                {
                                                  $my1_row=mysql_fetch_array($my1_result);
                                                  $repl_array[$i]=$my1_row['featuredirectory_text'];
                                                }
                                             else {$repl_array[$i]=$row['goodfeature_value'];}
                                             $i++;
                                }

                 //---------------------------------------
                    //Ищем случайную запись
                                         $min_usenum="(SELECT min(goodnewtemplate_usenum) FROM ".DB_PREFIX."goods_news_templates WHERE ID_RUBRIC_TYPE=$R_type and goodnewtemplate_deleted=0 and goodnewtemplate_priority=1 and goodnewtemplate_type=".$templ_type.")";
                                         $query="SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE ID_RUBRIC_TYPE=$R_type and goodnewtemplate_type=$templ_type and goodnewtemplate_deleted=0 and goodnewtemplate_usenum=$min_usenum";
                                         $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                                         $num_rows = mysql_num_rows($mysql_result);
                                          $i=1;
                                         while ($arow=mysql_fetch_array($mysql_result))
                                         {
                                          $my_id=$arow['ID_GOOD_NEW_TEMPLATE'];
                                          $my_arr[$i]=$my_id;

                                          $i++;
                                         }
                                            $a=rand(1,$num_rows);
                        if(isset($my_arr))
                        {
                                               $sz=$my_arr[$a];//-случайная запись
                        //Подключаемся к goods_news_templates
                         $query="SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE ID_GOOD_NEW_TEMPLATE=$sz and ID_RUBRIC_TYPE=$R_type and goodnewtemplate_type=$templ_type and goodnewtemplate_deleted=0";
                         $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                         if (mysql_num_rows($mysql_result)==0){return "Нет шаблонов";}
                         $row=mysql_fetch_array($mysql_result);
                        //--------Преобразование текста-----------
                              $stcf=count($str_from)+1;
                              $str_from[$stcf]="{date}";
                              $str_from[$stcf+1]="{ntime}";
                              $str_from[$stcf+2]="{name}";
                              $str_from[$stcf+3]="{pname}";
                              $mydate= date("Y-m-d");
                              $mytime=date("H:i:s");
                              $repl_array[$stcf]=$mydate;
                              $repl_array[$stcf+1]=$mytime;
                              $repl_array[$stcf+2]=$nID;
                              $repl_array[$stcf+3]=$pID;

                                    $bufer_title=$row['goodnewtemplate_title'];
                                    $bufer_text=$row['goodnewtemplate_text'];
                                    $my_text=str_replace($str_from,$repl_array,$bufer_text);
                                    $my_title=str_replace($str_from,$repl_array,$bufer_title);
						if(!empty($intime))
						{	
							$query="INSERT INTO ".DB_PREFIX."goods_news (goodnew_title,goodnew_text,goodnew_type,ID_RUBRIC_TYPE,goodnew_dt) VALUES ('".$my_title."','".$my_text."','".$templ_type."','".$R_type."','".$intime."')";
                        }
						else
						{
							$query="INSERT INTO ".DB_PREFIX."goods_news (goodnew_title,goodnew_text,goodnew_type,ID_RUBRIC_TYPE) VALUES ('".$my_title."','".$my_text."','".$templ_type."','".$R_type."')";
						}	
						$database -> query("UPDATE ".DB_PREFIX."goods_news_templates SET goodnewtemplate_usenum=goodnewtemplate_usenum+1 WHERE ID_GOOD_NEW_TEMPLATE=".$sz);
                        $database->query($query)or die("Invalid query: " . mysql_error());
                           return 1;
                           }else
                        {return "Нет масива для формирования шаблонов";}
                 }
                }
    //---------КОНЕЦ ФУНКЦИИ---------------------------------
    //---------ФУНКЦИЯ Создание новости по товарам-----------
                function createNews_good($IDR,$templ_type,$args = array(),$intime=false)
                {
                //
                global $database;
                if(teGetConf("news_tmpl".$IDR."_".$templ_type)==1)
                {
						   $k=1;
						   $str_post=array();
						   $str_from=array();
						   $repl_array=array();
						   $query="SELECT * FROM ".DB_PREFIX."rubric_features WHERE rubric_type=$IDR";
						   $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                                         if(mysql_num_rows($mysql_result)>0)
                                         {
                                           while ($row=mysql_fetch_array($mysql_result))
                                           {

                                             if(!$exists = in_array("{".$row['ID_FEATURE']."}",$str_post))
                                             { $str_post[$k]="{".$row['ID_FEATURE']."}";
                                              //print "str_post[$k]=".$str_post[$k]."<br>";
                                               $k++;
                                             }
                                           }
                                         }
                                         $kol=count($str_post);

                                    $kol_a=count($args);
                                for($i=1; $i<=$kol_a; $i++)
                                {

                                          for($j=1;$j<=$kol;$j++)
                                           {
                                             $tidfut=substr($str_post[$j],1,-1);
                                             $idfut=intval($tidfut);  $my_str="idfut$j".$idfut."!";

                                             if($idfut==$args[$i]['idfuture'])
                                             {
                                             	$repl_array[$i]=$args[$i]['fut_text'];
                                             }

                                           }
                                }
						   $rp_count=count($repl_array);
                            for($i=1;$i<=$rp_count;$i++)
                            {
                              if(intval($args[$i]['fut_text'])!=0)
                              {
                                  $query="SELECT * FROM ".DB_PREFIX."feature_directory WHERE ID_FEATURE=".$args[$i]['idfuture']." and ID_FEATURE_DIRECTORY=".intval($args[$i]['fut_text']);

                                  $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                                  if(mysql_num_rows($mysql_result)>0)
                                  {
                                    $rppu=mysql_fetch_array($mysql_result);
                                    $repl_array[$i]=$rppu['featuredirectory_text'];

                                  }
                              }

                            }

                            for($i=1;$i<=$kol_a;$i++)
                            {
                                for($j=1;$j<=$kol;$j++)
                                {
                                   $stt=substr($str_post[$j],1,-1);
                                   $istt=intval($stt);
                                   if($istt==$args[$i]['idfuture']){$str_from[$i]=$str_post[$j];}
                                }
                            }
                            //Конец определения переменных

                            //Выбираем случайную запись
                                         $min_usenum="(SELECT min(goodnewtemplate_usenum) FROM ".DB_PREFIX."goods_news_templates WHERE ID_RUBRIC_TYPE=$IDR and goodnewtemplate_deleted=0 and goodnewtemplate_priority=1 and goodnewtemplate_type=".$templ_type.")";
                                         $query="SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE ID_RUBRIC_TYPE=$IDR and goodnewtemplate_type=$templ_type and goodnewtemplate_deleted=0 and goodnewtemplate_usenum=$min_usenum";
                                         $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                                         $num_rows = mysql_num_rows($mysql_result);
                                         $i=1;
                                         while ($arow=mysql_fetch_array($mysql_result))
                                         {
                                          $my_id=$arow['ID_GOOD_NEW_TEMPLATE'];
                                          $my_arr[$i]=$my_id;
                                          $i++;
                                         }
                                         $a=rand(1,$num_rows);
                    if(isset($my_arr))
                    {
                         $sz=$my_arr[$a];//-случайная запись

                        //Подключаемся к goods_news_templates
                              $query="SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE ID_GOOD_NEW_TEMPLATE=$sz and ID_RUBRIC_TYPE=$IDR and goodnewtemplate_type=$templ_type and goodnewtemplate_deleted=0";
                              $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                          if (mysql_num_rows($mysql_result)<1){return 0;}
                          else
                          {
                              $row=mysql_fetch_array($mysql_result);
                           //--------Преобразование текста-----------
                               $stcf=count($str_from)+1;
                               $str_from[$stcf]="{date}";
                               $str_from[$stcf+1]="{ntime}";
                               $str_from[$stcf+2]="{name}";
                               $str_from[$stcf+3]="{pname}";
                               //$str_from[0]="{pname}";
                              $mydate = date("Y-m-d");
							    
							   
								//$mydate=date("m.d.y", mktime(0, 0, 0, $month1, $day1, $year1));	
							   
							   $mytime = date("H:i:s");
                               
							   
								//$mytime=date("H.i.s", mktime(, 0, 0, 0, 0, 0));	
							   
							   
							   $repl_array[$stcf]=$mydate;
                               $repl_array[$stcf+1]=$mytime;
                               $repl_array[$stcf+2]=$args[1]['idgood'];
                               $repl_array[$stcf+3]=$IDR;

						             $bufer_title=$row['goodnewtemplate_title'];
                                     $bufer_text=$row['goodnewtemplate_text'];
                                     $my_text=str_replace($str_from,$repl_array,$bufer_text);
                                     $my_title=str_replace($str_from,$repl_array,$bufer_title);
                                     //print $my_title."<br>";
									if(!empty($intime))
									{	 
										$query="INSERT INTO ".DB_PREFIX."goods_news (goodnew_title,goodnew_text,goodnew_type,ID_RUBRIC_TYPE,goodnew_dt) VALUES ('".$my_title."','".$my_text."','".$templ_type."','".$IDR."','".$intime."')";
                                    }
									else
									{
										$query="INSERT INTO ".DB_PREFIX."goods_news (goodnew_title,goodnew_text,goodnew_type,ID_RUBRIC_TYPE) VALUES ('".$my_title."','".$my_text."','".$templ_type."','".$IDR."')";
									}	
									$database->query($query)or die("Invalid query: " . mysql_error());
                          }         $database -> query("UPDATE ".DB_PREFIX."goods_news_templates SET goodnewtemplate_usenum=goodnewtemplate_usenum+1 WHERE ID_GOOD_NEW_TEMPLATE=".$sz);
                           return 1;
                    }else {return 0;}
                  }
                }// End function createNews_good

    //--------------КОНЕЦ ФУНКЦИИ-------------
    //--------------ФУНКЦИЯ создание новости для рубрик------------------
                function createNews_rubric($IDR,$templ_type,$args = array(),$intime=false)
                {
				  global $database;
                  if(teGetConf("news_tmpl".$IDR."_".$templ_type)==1 )
                  {
                    //Ищем случайное значение
                    $min_usenum="(SELECT min(goodnewtemplate_usenum) FROM ".DB_PREFIX."goods_news_templates WHERE ID_RUBRIC_TYPE=$IDR and goodnewtemplate_deleted=0 and goodnewtemplate_priority=1 and goodnewtemplate_type=".$templ_type.")";
                    $query="SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE ID_RUBRIC_TYPE=$IDR and goodnewtemplate_type=$templ_type and goodnewtemplate_deleted=0 and goodnewtemplate_usenum=$min_usenum";

                    $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                    $num_rows = mysql_num_rows($mysql_result);
                    $i=1;
                    while ($arow=mysql_fetch_array($mysql_result))
                    {
                     $my_id=$arow['ID_GOOD_NEW_TEMPLATE'];
                     $my_arr[$i]=$my_id;
                     $i++;
                    }
                    $a=rand(1,$num_rows);
                    if(isset($my_arr))
                    {
                      $sz=$my_arr[$a];//-случайная запись
                        //Подключаемся к goods_news_templates


                        $query="SELECT * FROM ".DB_PREFIX."goods_news_templates WHERE ID_GOOD_NEW_TEMPLATE=$sz and goodnewtemplate_deleted=0";

                        $mysql_result = $database->query($query) or die("Invalid query: " . mysql_error());
                        if(mysql_num_rows($mysql_result)<1){return 0;}
                        else
                        {
                          $row = mysql_fetch_array($mysql_result);
                          $bufer_title=$row['goodnewtemplate_title'];
                          $bufer_text=$row['goodnewtemplate_text'];

                           $str_post[0]="{pname}";
                           $str_post[1]="{name}";
                           $str_post[2]="{date}";
                           $str_post[3]="{ntime}";

                           $mydate=date("Y-m-d");
                           $mytime=date("H:i:s");
										$query="SELECT * FROM ".DB_PREFIX."rubric_types WHERE ID_RUBRIC_TYPE=".$IDR;
										$res333 = $database->query($query) or die("Invalid query: " . mysql_error());
										$row333=mysql_fetch_array($res333);
                                    if(empty($args[0]) || $args[0]==0){$repl_array[0]=$row333['rubrictype_name'];}
                                    else
                                    {
                                           $query="SELECT * FROM ".DB_PREFIX."rubric WHERE ID_RUBRIC=".$args[0];
                                           $my222_result = $database->query($query) or die("Invalid query: " . mysql_error());
                                           $urow=mysql_fetch_array($my222_result);
                                           $repl_array[0]=$urow['rubric_name'];
                                    }
                           $repl_array[1]=$args[1];
                           $repl_array[2]=$mydate;
                           $repl_array[3]=$mytime;
                           $bufer_title=$row['goodnewtemplate_title'];
                           $bufer_text=$row['goodnewtemplate_text'];
                           $my_text=str_replace($str_post,$repl_array,$bufer_text);
                           $my_title=str_replace($str_post,$repl_array,$bufer_title);
						if(!empty($intime))
						{	
							$query="INSERT INTO ".DB_PREFIX."goods_news (goodnew_title,goodnew_text,goodnew_type,ID_RUBRIC_TYPE,goodnew_dt) VALUES ('".str_replace("'","\'",$my_title)."','".$my_text."','".$templ_type."','".$IDR."','".$intime."')";	
						}		
						else{		
							$query="INSERT INTO ".DB_PREFIX."goods_news (goodnew_title,goodnew_text,goodnew_type,ID_RUBRIC_TYPE) VALUES ('".str_replace("'","\'",$my_title)."','".$my_text."','".$templ_type."','".$IDR."')";
						}		
						   $database->query($query)or die("Invalid query: " . mysql_error());
                           $database ->query("UPDATE ".DB_PREFIX."goods_news_templates SET goodnewtemplate_usenum=goodnewtemplate_usenum+1 WHERE ID_GOOD_NEW_TEMPLATE=".$sz);
                        }
                        return 1;
                    }else{return 0;}
                  }
                }
    //------------------Конец функци---------------------------
}
$create_news_triger=new triger();
?>