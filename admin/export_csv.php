<?	
$_status = 1;
define("DB_PREFIX","cprice_");
define("DB_HOST","localhost");
define("DB_USERNAME","admin_irbiss");
define("DB_USERPASS","VT5LZq5y");
define("DB_NAME","admin_irbiss");

include "/var/www/irbissho/data/www/irbis-shop.ru/admin/engine/server/database/class.php";
include "/var/www/irbissho/data/www/irbis-shop.ru/admin/engine/server/common/index_incl.php";


$database = new teDatabase(DB_HOST, DB_USERNAME, DB_USERPASS, DB_NAME);
$database -> query("set names cp1251");


function ic($in) { return htmlspecialchars(iconv("Windows-1251", "UTF-8", trim($in)));}
$shopName = 'IRBIS-SHOP';
$siteURL = 'http://www.irbis-shop.ru';
$rubric_0lvl = array();
$res_rub = $database->query("select ID_RUBRIC, rubric_name from cprice_rubric where rubric_parent=0 && rubric_type=2 && rubric_visible=1 && rubric_deleted=0");	
while ( list($rub_id,$rubric_name) = mysql_fetch_array($res_rub) ) {
	$rubric_0lvl[$rub_id] = $rubric_name;
}
$f = fopen("/var/www/irbissho/data/www/irbis-shop.ru/images/goods.csv", "w") or die("Ошибка!");
$list = array(ic("Название"),ic("Фото"),ic("Описание"),ic("Категория"),ic("Производитель"));
fputcsv0($f, $list,";",'"');
ini_set("auto_detect_line_endings", true);
$gids = array();
$i = 0;
$res_rub = $database->query("select rubric_textid, ID_RUBRIC, rubric_name, rubric_parent from cprice_rubric where rubric_parent>0 && rubric_type=2 && rubric_visible=1 && rubric_deleted=0");
while ( list($rurl,$rub_id,$rubric_name,$rparent) = mysql_fetch_array($res_rub) ) {
	$rubric_name = ic($rubric_0lvl[$rparent].' > '.$rubric_name);
	
	$data = getData2($rub_id,'','',array(30=>array(2,'',false),31=>array(7,'',false),97=>array(4,'',false)));
	foreach ($data as $gid=>$vals) {
	  if( !in_array($gid, $gids) )
	  {
		$i++;
		$photo = '';
		$sql = "SELECT * FROM ".DB_PREFIX."goods_photos WHERE goodphoto_deleted=0 and ID_GOOD=".$gid." ORDER BY goodphoto_pos limit 1";
		$res = $database->query($sql);
		while($row = mysql_fetch_array($res)) {
			if(@file_exists('/var/www/irbissho/data/www/irbis-shop.ru/images/good_photo/'.$row['goodphoto_file']))
				$photo = ic($siteURL.'/images/good_photo/'.$row['goodphoto_file']);
		}
		$list = array(ic($vals[30][1]),$photo,ic($vals[31][1]),$rubric_name,ic($vals[97][1]));
		fputcsv0($f, $list,";",'"');
	  }
	}
}
fclose($f);

//echo 'Файл сгенерирован. Его можно найти по ссылке: http://irbis-shop.ru/images/goods.csv';
/*
$xml->save('../../irbis-shop.ru/goods.xml');
echo 'Файл сгенерирован. Его можно найти по ссылке: <a href="http://irbis-shop.ru/goods.xml" target="_blank">http://irbis-shop.ru/goods.xml</a>';
*/

//echo ' ok '.$i;
    function fputcsv0($f, $list, $d=",", $q='"') {
        $line = "";
        foreach ($list as $field) {
            # remove any windows new lines,
            # as they interfere with the parsing at the other end
            $field = str_replace("\r\n", "\n", $field);
            # if a deliminator char, a double quote char or a newline
            # are in the field, add quotes
            if(preg_match("/[$d$q\n\r]/", $field)) {
                $field = $q.str_replace($q, $q.$q, $field).$q;
            }
            $line .= $field.$d;
        }
        # strip the last deliminator
        $line = substr($line, 0, -1);
        # add the newline
        $line .= "\n";
        # we don't care if the file pointer is invalid,
        # let fputs take care of it
        return fputs($f, $line);
    }
?>