<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
/*****
*   function teGeneratePassword()
*   function teGenerateId() 
*
* TDSCMS v1.0 
* tdssc@mail.ru
*****/


/*****
* function teGeneratePassword()
*  @param int Length of password
*  @param string Possible chars
* 
* Generate random password
*****/
function teGeneratePassword( $length=8, $chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz" ){
	$out = "";
	for($i=1;$i<=8;$i++){
		$out .= substr($chars,rand(0,strlen($chars)),1);
	}
	return $out;
}	

/*****
* function teGenerateId() 
* Generate random id
*****/
function teGenerateId(){
	return md5(uniqid(crc32(rand(0,9)),1));
}
?>