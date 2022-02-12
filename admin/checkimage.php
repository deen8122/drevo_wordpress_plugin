<? 
/* 
	создание картинки для каптчи
*/

	session_start();
	//этот файл является картинкой с расширением гиф
	header("Content-type: image/gif");
	//создать картинкку
	define("X",36);
	define("Y",16);
	$im = @imagecreate(X,Y);
	$bg_color=imagecolorallocate($im,0,0,0);
	imagecolortransparent($im,$bg_color);
	
	$set_color=imagecolorallocate($im,150,150,150);
	
	for($i=1;$i<=X;$i+=3)
		if(rand(0,1)==1)
			imageline($im,$i,0,$i,Y,$set_color);
			
	for($i=1;$i<=Y;$i+=3)
		if(rand(0,1)==1)
			imageline($im,0,$i,X,$i,$set_color);
			
	$text_color=imagecolorallocate($im,0,0,0);
	
	
	$_SESSION["checkimage"]=sprintf("%04d",rand(10,9999));
	if(rand(0,6)==5){
		$_SESSION["checkimage"] = substr($_SESSION["checkimage"],1);
	}
	$x = rand(0,1);
	for($i=0;$i<strlen($_SESSION["checkimage"]);$i++){
				
		$fontsize = rand(3,5);
		imagestring($im, $fontsize,$x,rand(0,1),substr($_SESSION["checkimage"],$i,1),$text_color);
		$x += imagefontwidth($fontsize)+rand(0,(strlen($_SESSION["checkimage"])>3)?1:3);
	}
	$_SESSION["checkimage"] = md5($_SESSION["checkimage"]);
	imagegif($im);

/*
	$_SESSION["checkimage"]=sprintf("%04d",rand(0,9999));
	imagestring($im,5,0,0,$_SESSION["checkimage"],$text_color);
	$_SESSION["checkimage"] = md5($_SESSION["checkimage"]);
	imagegif($im);
*/
?>