<?
/**********
*  ��� "���������-������"
*
*  �����������:  ������� �.
**********/
function str_rus( $str )
{
	$nstr=""; // �������� ������
	for( $i=0; $i<strlen($str);$i++)
	{
		$symbol=substr($str, $i, 1); // get symbol
		$ascii=ord( $symbol );    // get ascii code of this symbol
		if( $ascii < 128 )
		{    $nstr .= $symbol;        }
		elseif( $ascii > 191 and $ascii < 256 )
		{    $nstr .= "&#". (string)(848 + ord($symbol)).';';    }
		else
		{    $nstr .= $symbol;    }
	} // end of for( $i ) loop

	return $nstr ;
} // end of function str_rus	
function teImgTrumb($filename,$prefix = "trumb_",$w=false,$h=false,$tofolder = NULL){

	// ������� ������� ��������� �����������
	$size_img = getimagesize($filename);
	// ������� ����������� ������ ��������� �����������
	$src_ratio=$size_img[0]/$size_img[1];
	$pro = $size_img[1]/$h;
	$w1 = $size_img[0]/$pro;
	if($w1>$w){
		$src_ratio=$size_img[1]/$size_img[0];
		$pro = $size_img[0]/$w;
		$h = $size_img[1]/$pro;
	} else {
		$w = $w1;
	}

	if( $w>$size_img[0] && $h>$size_img[1] ){
		$w=$size_img[0];
		$h=$size_img[1];
	}

	// ��������� ����������� ������ �����������, ������� ����� ��������
	$ratio = $w/$h;
	// �������� ������ ����������� �� �������� ��������
	$dest_img = imagecreatetruecolor($w, $h);

	imagecolortransparent($dest_img,imagecolorat($dest_img,1,1));

	// ����� ��������� �������, ����� ��� ��������������� �����������
	// 1. ��������� ��������� �����������
	// 2. �������� ����������� ��������� ���������� �� ��������� �����
	// (�� ����������)
    if ($src_ratio>$ratio)
    {
        $old_h=$size_img[1];
        $size_img[1]=floor($size_img[0]/$ratio);
        $old_h=floor($old_h*$h/$size_img[1]);
    }
    else
    {
        $old_w=$size_img[0];
        $size_img[0]=floor($size_img[1]*$ratio);
        $old_w=floor($old_w*$w/$size_img[0]);
    }

    // ������ �� ���� ����� ��� ����� �����������
    // �������� ������� ��������
    switch ($size_img['mime'])
    {
        // ���� ��� ����� JPEG
        case 'image/jpeg':
            // ������� jpeg �� �����
            $src_img = imagecreatefromjpeg($filename);
            $ext="jpg";
            break;
        // ���� ��� ����� GIF
        case 'image/gif':
            // ������� gif �� �����
            $src_img = imagecreatefromgif($filename);
            $ext="gif";
            break;
        case 'image/png':
            // ������� gif �� �����
            $src_img = imagecreatefrompng($filename);
            $ext="png";
            break;
    }
    // ������������ �����������    �������� imagecopyresampled()
    // $dest_img - ����������� �����
    // $src_img - �������� �����������
    // $w - ������ ����������� �����
    // $h - ������ ����������� �����
    // $size_img[0] - ������ ��������� �����������
    // $size_img[1] - ������ ��������� �����������
    imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $w, $h, $size_img[0], $size_img[1]);

	$filepath = pathinfo($filename);
	if(empty($tofolder)) $tofolder=$filepath['dirname']; else $tofolder = DATA_FLD.$tofolder;
	$trumbfilename = $tofolder."/".$prefix.$filepath['basename'];

    // � ����������� �� ���� ����� �������� ������� ���������� � ����
    switch ($size_img['mime']){
        case 'image/jpeg':
            // ��������� � ���� small.jpg
            imagejpeg($dest_img, $trumbfilename);
            break;
        case 'image/gif':
            // ��������� � ���� small.gif
            imagegif($dest_img, $trumbfilename);
            break;
        case 'image/png':
            imagepng($dest_img, $trumbfilename);
        break;
	}

    // ������ ������ �� ��������� �����������
    imagedestroy($dest_img);
    imagedestroy($src_img);
}
function resize_picture($filename,$percent)
{
    // Content type
	header('Content-type: image/jpeg');

	// ��������� ������ �������
	list($width, $height) = getimagesize($filename);
	$newwidth = $width * $percent;
	$newheight = $height * $percent;

	// ���������
	$thumb = imagecreatetruecolor($newwidth, $newheight);
	$source = imagecreatefromjpeg($filename);

	// Resize
	imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

	// �����
	imagejpeg($thumb);

}
//{������� �������-----------------------------------------------------
include 'api.watermark.php';
function new_wm_image($image)
{
	global $database;
	list($wm_text1)=$database->getArrayOfquery("SELECT var_value FROM ".DB_PREFIX."configtable WHERE  var_name='wm_text'");
	//���� ����� �� �����
		
		$wm_file=DATA_FLD."watermark.png";

		   $watermark = new watermark();

		   $filename=$image;
		   $size_img = getimagesize($filename);
			switch ($size_img['mime'])
			{
				// ���� ��� ����� JPEG
				case 'image/jpeg':
					// ������� jpeg �� �����
					$src_img = imagecreatefromjpeg($filename);
					$ext="jpg";
					break;
				// ���� ��� ����� GIF
				case 'image/gif':
					// ������� gif �� �����
					$src_img = imagecreatefromgif($filename);
					$ext="gif";
					break;
				case 'image/png':
					// ������� gif �� �����
					$src_img = imagecreatefrompng($filename);
					$ext="png";
					break;
			}
	if(empty($wm_text1))
	{		
		$result = mysql_query("SELECT * FROM ".DB_PREFIX."configtable WHERE  var_name='wm_pos'") or die("������ ������� ����������� �� ��������");
		$line = mysql_fetch_array($result);
		$ret = mysql_query("SELECT * FROM ".DB_PREFIX."configtable WHERE  var_name='wm_alfa'") or die("������ ������������ �� ��������");

		$ln = mysql_fetch_array($ret);
		if(mysql_num_rows($result)==0 || !file_exists($wm_file)){}
		else{
		   $where_watermark=$line['var_value'];
		   $watermark_img_obj = imagecreatefrompng($wm_file);
		   if(!empty($ln['var_value'])){$alfa=$ln['var_value'];}else{$alfa=50;}

		   $return_img_obj = $watermark->create_watermark($src_img, $watermark_img_obj, $alfa, $where_watermark);

		   imagejpeg($return_img_obj, $image, 60);
		}
	}
	//���� ����� �����
	else
	{
		$fontfile="ERASLGHT.TTF";
		list($where_watermark) = $database->getArrayOfquery("SELECT var_value FROM ".DB_PREFIX."configtable WHERE  var_name='wm_pos'") or die("������ ������� ����������� �� ��������");
		list($text_watermark) = $database->getArrayOfquery("SELECT var_value FROM ".DB_PREFIX."configtable WHERE  var_name='wm_text'") or die("������ ������ �� ��������");
		list($color_watermark) = $database->getArrayOfquery("SELECT var_value FROM ".DB_PREFIX."configtable WHERE  var_name='wm_color'") or die("������ ����� �� ��������");
		# ������ �������� ����������� (������ � ������)
        $main_img_obj_w = imagesx( $src_img );
        $main_img_obj_h = imagesy( $src_img );
		//����������
		$x=0;
		$y=0;
		//
		
		//print "mx=".$main_img_obj_w."<br>";
            //print "my=".$main_img_obj_h."<br>";
			//die();
		//w_px- wordpx- ���-�� �������� �� 1 �����
		//$w_px=8;
		//$mf = imageloadfont ('../engine/server/images/fonts/Arial12BI.phpfont');
				//���� ������ ������ 200 �������� �� ���������
		if($main_img_obj_w<200)
		{
			
			$font_size=10;
			//$mf = imageloadfont ('../engine/server/images/fonts/myfont.phpfont');
		}
		//�����
		else
		{
			$font_size=20;
			//$mf = imageloadfont('../engine/server/images/fonts/Arial12BI.phpfont');
			
		}
		//$w_px_w=imagefontwidth($mf);
		//$w_px_h=imagefontheight($mf);
		$w_px_w=$font_size;
		$w_px_h=$font_size*1.5;
		
		//print "w_px_w=".$w_px_w."<br>";
		//print "w_px_h=".$w_px_h."<br>";
			
		//print "w_px=".$w_px;die();
		//���-�� �������� �� �����
		//$handle = fopen('../engine/server/images/debug.txt', 'a');
		//$content="file=".$filename."\r\n";
		$pathttf="../engine/server/images/fonts/";
		$text_posit_array=imagettfbbox ($font_size, 0,$pathttf.$fontfile, $text_watermark);
		$i=0;
		/*
		foreach($text_posit_array as $k=>$v)
		{	
			//$content.="posit$i=".$v."\r\n";
			$i++;
		}
		*/
		$text_lenght=$text_posit_array[2];	
		//$text_lenght=strlen($text_watermark)*$w_px_w;
		//print "text_lenght".$text_lenght."<br>";
		//die();
		//-----------------------------------------------		
		$split_text=split(" ",$text_watermark);
		//-----------------------------------------------
		/*
		foreach($split_text as $key=>$val)
		{
			//$content.="text=".$val."\r\n";
		}
		*/
		//$content.="main_img_obj_w= ".$main_img_obj_w ."\r\n";
		//$content.="main_img_obj_h= ".$main_img_obj_h ."\r\n";
		//$content.="w_px_w=".$w_px_w."\r\n";
		//$content.="w_px_h=".$w_px_h."\r\n";
		
		
		////$content.="text_lenght=".$text_lenght."\r\n \r\n";
		
//-----------------------------------------------
		if($text_lenght>$main_img_obj_w)
		{
			$font_size=10;
			$w_px_w=$font_size;
			$w_px_h=$font_size*1.5;
			//$mf = imageloadfont ('../engine/server/images/fonts/myfont.phpfont');
			$text_posit_array=imagettfbbox ($font_size, 0,$pathttf.$fontfile, $text_watermark);
			$text_lenght=$text_posit_array[2];
			//$text_lenght=strlen($text_watermark)*$w_px_w;
			////$content.="new_text_lenght".$text_lenght."\r\n \r\n";
		}	
			
		//�������
		switch($where_watermark)
		{
			case "1":
			//����� ������� ����
			 $watermark_x        = $x+$w_px_w;
			 $watermark_y        = $y+$w_px_h;
			 $pos=0;
			break;
			case "2":
			//������ ������� ����
			  //$watermark_x        = $x - 2*$main_img_obj_w;
			  $watermark_x        = ($x + $main_img_obj_w)-$text_lenght-10;
			  $watermark_y        = $y+$w_px_h+10;
			  $pos=0;
			break;
			case "3":
			//����� ������ ����
			  $watermark_x        = $x+10;
			  $watermark_y        = ($y + $main_img_obj_h)-10-$w_px_h;
			  $pos=1;
			break;
			case "4":
			//������ ������ ����
			  //$watermark_x        = $x - 2*$main_img_obj_w;
			  $watermark_x        = ($x + $main_img_obj_w)-$text_lenght-10;
			  //$watermark_y        = $y - 2*$main_img_obj_h;
			  $watermark_y        = ($y + $main_img_obj_h)-10-$w_px_h;
			  $pos=1;
			break;
			case "5":
			//������
			  //$watermark_x        = $x - $main_img_obj_w;
			 $watermark_x        = ($x + $main_img_obj_w/2)-($text_lenght/2);
			 $watermark_y        = $y+10+$w_px_h;
			 $pos=0;
			break;
			case "6":
			//�����
			  //$watermark_x        = $x - $main_img_obj_w;
			  //$watermark_y        = $y - 2*$main_img_obj_h;
			  $watermark_x        = ($x + $main_img_obj_w/2)-($text_lenght/2);
			  $watermark_y        = ($y + $main_img_obj_h)-10-$w_px_h;
			  $pos=1;
			break;								
			case "7":
				//�� ������
				//$watermark_x        = $x - $main_img_obj_w;
				$watermark_x        = ($x + $main_img_obj_w/2)-($text_lenght/2);
				//$watermark_y        = $y - $main_img_obj_h;
				$watermark_y        = ($y + $main_img_obj_h/2);
			break;
		}
		
		//$color_watermark;
		//print "image=".$image;	die();
		//------ ��������� ���� � ������� ������
		$img=$src_img;
		//$img = imagecreatefromjpeg($filename);
		switch($color_watermark)
		{
			//�����
			case "1":
				$color = imagecolorallocate ($img, 10, 0, 250);
			break;	
			//�������
			case "2":
				$color = imagecolorallocate ($img, 255, 0, 0);
			break;
			//������
			case "3":
				$color = imagecolorallocate ($img, 255, 255, 0);
			break;
			//�����
			case "4":
				$color = imagecolorallocate ($img, 255, 255, 255);
			break;
			//������
			case "5":
				$color = imagecolorallocate ($img, 0, 0, 0);
			break;
			//�������
			case "6":
				$color = imagecolorallocate ($img, 0, 128, 0);
			break;
			//���������
			case "7":
				$color = imagecolorallocate ($img, 255, 128, 0);
			break;			
		}
		
		//print_r($img);die();
			//$color = imagecolorallocate ($img, 255, 0, 0);
		//-----  ����� �� ��������� ���������� ������������ ������� �� �������,
		//-----  ����,�����, � ����� �������
		//print $text_watermark;die(); 8x16.phpfont
		//$mf = imageloadfont ('../engine/server/images/myfont.phpfont');
		/*
		function win2uni($s)
		  {
			$s = convert_cyr_string($s,'w','i'); // �������������� win1251 -> iso8859-5
			// �������������� iso8859-5 -> unicode:
			for ($result='', $i=0; $i<strlen($s); $i++) {
			  $charcode = ord($s[$i]);
			  $result .= ($charcode>175)?"&#".(1040+($charcode-176)).";":$s[$i];
			}
			return $result;
		  }
		  */
		// �������������� ������� ���� � ������ ��� ������ �� ��������
  
		
		//$text_watermark=win2uni($text_watermark);
		//$text_watermark=iconv("KOI8-U", "UTF-8", $text_watermark);
		$text_watermark=str_rus($text_watermark);
		
		//���� ����� � ������� ��������
		if(($main_img_obj_w/$main_img_obj_h)<0.7 && ($text_lenght>$main_img_obj_w && $text_lenght<$main_img_obj_h))
		{
			  $watermark_x        = $x+$w_px_h+5;
			  $watermark_y        = ($y + $main_img_obj_h)-10-$w_px_h;
			 // //$content.="watermark_x=$watermark_x \r\n watermark_y=$watermark_y\r\n";
			//ImageStringUp($img, $mf, $watermark_x, $watermark_y,$text_watermark,$color );
			//imagettftext ($im, 20, 0, 10, 20, $white, "/path/arial.ttf", "Testing...Omega: &#937;");
			  //arial.ttf;
			 //���� ����� �� ������� � �� ���������
			if($text_lenght>$main_img_obj_h)
			{	
				//
				$i=0;
				foreach($split_text as $key=>$val)
				{
					if($text_lenght>$main_img_obj_h)
					{
					$font_size=5;
					$w_px_w=$font_size;
					$w_px_h=$font_size*1.5;
					$watermark_x        = $x+$w_px_h+5;
					$watermark_y        = ($y + $main_img_obj_h)-10-$w_px_h;
					}
					if($i==0)
					{	
						//���� ���� �� ����� ���� , ���� ��� �� ����.
						if($watermark_x<5){$watermark_x=10;}
						imagettftext ($img, $font_size, 90, $watermark_x, $watermark_y, $color, $pathttf.$fontfile, $val);
					}
					if($i==1)
					{
						if($watermark_x<5){$watermark_x=10;}
						$smeshen_x=$w_px_w+5;
						imagettftext ($img, $font_size, 90, $watermark_x+$smeshen_x, $watermark_y, $color, $pathttf.$fontfile, $val);
					}	
					$i++;
				}
			}
			else
			{	
				imagettftext ($img, $font_size, 90, $watermark_x, $watermark_y, $color, $pathttf.$fontfile, $text_watermark);
			}
			////$content.="1-� ������� \r\n";  
		}
		// ���� �������� ����� ������, ��� ���� ����� ������ ������� ����� �� ������� �� �� ��������� �� �� �����������
		elseif($text_lenght>$main_img_obj_w && $text_lenght>$main_img_obj_h)
		{
			// � ��� ������ ��
			$i=0;
			foreach($split_text as $key=>$val)
			{
				//������ ����� �� ������ ��������
				$text_posit_array=imagettfbbox ($font_size, 0,$pathttf.$fontfile, $val);
				$text_lenght=$text_posit_array[2];
				//$text_lenght=strlen($val)*$w_px_w;//����� �����
				$watermark_x  = ($main_img_obj_w/2)-($text_lenght/2);
				$watermark_y  = ($main_img_obj_h)-10-$w_px_h;
				////$content.="main_img_obj_w/2=".($main_img_obj_w/2)."\r\n";
				////$content.="text_lenght/2=".($text_lenght/2)."\r\n";
				////$content.="watermark_x=$watermark_x \r\n watermark_y=$watermark_y\r\n";
				if($text_lenght>$main_img_obj_w){$font_size=7;$w_px_w=$font_size;$w_px_h=$font_size*1.5;}
				if($i==0)
				{	
					//���� ���� �� ����� ���� , ���� ��� �� ����.
					if($pos==0){$smeshen_y=0;}else{$smeshen_y=$w_px_h+5;}
					if($watermark_x<5){$watermark_x=5;}
					imagettftext ($img, $font_size, 0, $watermark_x, $watermark_y+$smeshen_y, $color, $pathttf.$fontfile, $val);
				}
				if($i==1)
				{
					if($watermark_x<5){$watermark_x=5;}
					if($pos==0){$smeshen_y=$w_px_h+5;}else{$smeshen_y=0;}
					imagettftext ($img, $font_size, 0, $watermark_x, $watermark_y+$smeshen_y, $color, $pathttf.$fontfile, $val);
				}	
				$i++;
			}
			////$content.="2-� ������� \r\n";
		}	
		//���� �������� ����������
		else
		{	
			////$content.="watermark_x=$watermark_x \r\n watermark_y=$watermark_y\r\n";
			imagettftext ($img, $font_size, 0, $watermark_x, $watermark_y, $color, $pathttf.$fontfile, $text_watermark);
			//ImageString($img, $mf, $watermark_x, $watermark_y,$text_watermark,$color );
			////$content.="3-� ������� \r\n";
		}
		//imagettftext($img, 20, 0, 120, 120, $color, 'C:\WINDOWS\Fonts\arial.ttf','HELLO!!!!');
		
		//fwrite($handle,//$content); 
		//fclose($handle);
		//----- �������� ��������� ����������� ��������, � ������ ������ image/jpeg
		header('Content-type: image/jpeg');
		//----- ������� � ����� �����������
		imagejpeg($img,$image, 100);
		//----- ������� ������, �� �� ����
		imagedestroy($img);
	}	
}
//������� ������� }-----------------------------------------------------
?>