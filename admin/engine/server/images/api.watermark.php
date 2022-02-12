<?php
class watermark{

        # функци€, котора€ сливает два исходных изображени€ в одно
        function create_watermark( $main_img_obj, $watermark_img_obj, $alpha_level = 100 ,$where_watermark ) {
                $alpha_level        /= 100;# переводим значение прозрачности альфа-канала из % в дес€тки

				# расчет размеров изображени€ (ширина и высота)
                $main_img_obj_w        = imagesx( $main_img_obj );
                $main_img_obj_h        = imagesy( $main_img_obj );
                $watermark_img_obj_w        = imagesx( $watermark_img_obj );
                $watermark_img_obj_h        = imagesy( $watermark_img_obj );


	  				// –есайзинг
						                #ѕроверка отношени€ размеров картинок(≈сли $main_img/$watermark_img<4)
	                $relat_w=$main_img_obj_w/$watermark_img_obj_w;
	                $relat_h=$main_img_obj_h/$watermark_img_obj_h;

					$percent=0.5; // ѕроцент уменьшени€



					if($relat_w<4 || $relat_h<4)
					{
                                       //ѕроверка если отношение сторон еще меньше
                                        //≈сли отношение стороны вод. знака к картинки больше чем в 2 раза то процент уменьшени€=0.3
						    if($relat_w<2 || $relat_h<2){$percent=0.3;}
                            //≈сли одна из сторон вод€ного знака больше самой картинки то процент уменьшени€=0.1
							if($relat_w<1 || $relat_h<1){$percent=0.1;}

					$newwidth = round($watermark_img_obj_w * $percent);
					$newheight = round($watermark_img_obj_h * $percent);



					$thumb = imagecreatetruecolor($newwidth, $newheight);
					imagecopyresized($thumb, $watermark_img_obj, 0, 0, 0, 0, $newwidth ,$newheight, $watermark_img_obj_w, $watermark_img_obj_h);

	                    //√енераци€ уменьшенной копии изображени€
						imagepng($thumb,DATA_FLD."thumb_watermark.png")or die("Ќевозможно создать уменьшенный файл");

						$watermark_img_obj = imagecreatefrompng(DATA_FLD."thumb_watermark.png");
						//print_r($watermark_img_obj);die();
						$watermark_img_obj_w=$newwidth;
						$watermark_img_obj_h=$newheight;
					}

                # определение координат центра изображени€
                $main_img_obj_min_x        = floor( ( $main_img_obj_w / 2 ) - ( $watermark_img_obj_w / 2 ) );
                $main_img_obj_max_x        = ceil( ( $main_img_obj_w / 2 ) + ( $watermark_img_obj_w / 2 ) );
                $main_img_obj_min_y        = floor( ( $main_img_obj_h / 2 ) - ( $watermark_img_obj_h / 2 ) );
                $main_img_obj_max_y        = ceil( ( $main_img_obj_h / 2 ) + ( $watermark_img_obj_h / 2 ) );

                # создание нового изображени€
                $return_img        = imagecreatetruecolor( $main_img_obj_w, $main_img_obj_h );

                #Ќаложение
                for( $y = 0; $y < $main_img_obj_h; $y++ ) {
                        for( $x = 0; $x < $main_img_obj_w; $x++ ) {
                                $return_color        = NULL;
                            # определение истинного расположени€ пиксел€ в пределах нашего вод€ного знака
                            switch($where_watermark)
                            {
                            	case "1":
                                //Ћевый верхний угол
                             	 $watermark_x        = $x;
                                 $watermark_y        = $y;
								break;
								case "2":
								//ѕравый верхний угол
                                  $watermark_x        = $x - 2*$main_img_obj_min_x;
                                  $watermark_y        = $y;
								break;
								case "3":
								//Ћевый нижний угол
                                  $watermark_x        = $x;
                                  $watermark_y        = $y - 2*$main_img_obj_min_y;
								break;
								case "4":
								//ѕравый нижний угол
                                  $watermark_x        = $x - 2*$main_img_obj_min_x;
                                  $watermark_y        = $y - 2*$main_img_obj_min_y;
								break;
                            }
                        		 //ѕо центру    (пока не нужно)
                                //$watermark_x        = $x - $main_img_obj_min_x;
                                //$watermark_y        = $y - $main_img_obj_min_y;

                                 # выбор информации о цвете дл€ наших изображений
                                 $main_rgb = imagecolorsforindex( $main_img_obj, imagecolorat( $main_img_obj, $x, $y ) );

                                # если наш пиксель вод€ного знака непрозрачный
                                if (        $watermark_x >= 0 && $watermark_x < $watermark_img_obj_w &&
                                                        $watermark_y >= 0 && $watermark_y < $watermark_img_obj_h ) {
                                        $watermark_rbg = imagecolorsforindex( $watermark_img_obj, imagecolorat( $watermark_img_obj, $watermark_x, $watermark_y ) );
                                        # использование значени€ прозрачности альфа-канала

                                        $watermark_alpha        = round( ( ( 127 - $watermark_rbg['alpha'] ) / 127 ), 2 );
                                        $watermark_alpha        = $watermark_alpha * $alpha_level;
                                        # расчет цвета в месте наложени€ картинок

                                        $avg_red                = $this->_get_ave_color( $main_rgb['red'],                $watermark_rbg['red'],                $watermark_alpha );
                                        $avg_green        = $this->_get_ave_color( $main_rgb['green'],        $watermark_rbg['green'],        $watermark_alpha );
                                        $avg_blue                = $this->_get_ave_color( $main_rgb['blue'],        $watermark_rbg['blue'],                $watermark_alpha );

                                        # использу€ полученные данные, вычисл€ем индекс цвета
                                        $return_color        = $this->_get_image_color( $return_img, $avg_red, $avg_green, $avg_blue );


                                } else {
                                        $return_color        = imagecolorat( $main_img_obj, $x, $y );

                                }

                                # из полученных пикселей рисуем новое изоборажение
                                imagesetpixel( $return_img, $x, $y, $return_color );

                        }
                }

                # отображаем изображение с вод€ным знаком
                return $return_img;

        }

        # функци€ дл€ "усреднени€" цветов изображений
        function _get_ave_color( $color_a, $color_b, $alpha_level ) {
                return round( ( ( $color_a * ( 1 - $alpha_level ) ) + ( $color_b        * $alpha_level ) ) );
        }

       # функци€, котора€ находит ближайшие RGB-цвета дл€ нового изображени€
        function _get_image_color($im, $r, $g, $b) {
                $c=imagecolorexact($im, $r, $g, $b);
                if ($c!=-1) return $c;
                $c=imagecolorallocate($im, $r, $g, $b);
                if ($c!=-1) return $c;
                return imagecolorclosest($im, $r, $g, $b);
        }

}
?>