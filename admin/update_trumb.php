<?

if (!isset($_status)) {
    header("HTTP/1.0 404 Not Found");
    die;
}
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798
//define("DEST_DIR",'Z:\\home\\test1.ru\\www\\files');
@$op1 = $_GET['op1'];
set_time_limit(3000000);
print '<div align="center">';
print '<h1>Обновление уменьшинных фото. Наложение водного знака</h1>';
if (isset($_GET['msg']))
    print '<div class="ok">Обработанно ' . $_GET['msg'] . ' фотографий</div>';

$frm = new teForm("form1", "post");
$frm->addf_selectGroup("rubric0", "В каком разделе делать операции:");
$frm->addf_selectGroup("rubric1", "В каких родительских рубриках делать операции:");
$frm->addf_selectGroup("rubric", "В каких детских рубриках делать операции:");
$r = $database->query("select ID_RUBRIC_TYPE,rubrictype_name from cprice_rubric_types where rubrictype_visible=1 && rubrictype_deleted=0");
while ($row2 = mysql_fetch_array($r)) {
    $frm->addf_selectItem("rubric0", $row2[0], $row2[1]);
    $res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_visible=1 && rubric_deleted=0 && rubric_parent>0 && rubric_type=" . $row2[0]);
    while ($row = mysql_fetch_array($res))
    {
        $frm->addf_selectItem("rubric", $row[0], $row2[1] . ' &gt;&gt; ' . $row[1]);
    }
    $res = $database->query("select ID_RUBRIC,rubric_name from cprice_rubric where rubric_visible=1 && rubric_deleted=0 && rubric_parent=0 && rubric_type=" . $row2[0]);
    while ($row = mysql_fetch_array($res))
    {
        $frm->addf_selectItem("rubric1", $row[0], $row2[1] . ' &gt;&gt; ' . $row[1]);
    }
}
$frm->setFieldMultiple("rubric");
$frm->setFieldMultiple("rubric1");
$frm->addf_checkbox("trumb1", "Делать уменьшенные фото trumb");
$frm->addf_checkbox("trumb2", "Делать уменьшенные фото image");
$frm->addf_checkbox("wtrmrk1", "Наложить водный знак на исходное фото");
$frm->addf_checkbox("wtrmrk2", "Наложить водный знак на фото image");
if (!$frm->send()) {
    $dir = 'good_photo/';


    teInclude("images");
    $mmaxw = teGetConf('photo_mmaxw');
    $mmaxh = teGetConf('photo_mmaxh');
    $tmaxw = teGetConf('photo_tmaxw');
    $tmaxh = teGetConf('photo_tmaxh');
    $rubric0 = $frm->get_value('rubric0');
    $rubric = $frm->get_value('rubric');
    $rubric1 = $frm->get_value('rubric1');
    if ($rubric0 > 0 || count($rubric) > 0 || count($rubric1) > 0) {
        $i = 0;
        $sql = "";
        $br = "";
        $add_tbl = '';
        foreach ($rubric AS $rubric_id) {
            if ($rubric_id > 0) {
                $sql .= $br . "ID_RUBRIC='$rubric_id' ";
                $br = " || ";
            }
        }
        foreach ($rubric1 AS $rubric_id) {
            $add_tbl = "natural join cprice_rubric";
            if ($rubric_id > 0) {
                $sql .= $br . "rubric_parent='$rubric_id' || ID_RUBRIC='$rubric_id' ";
                $br = " || ";
            }
        }
        if ($rubric0 > 0) {
            $add_tbl = "natural join cprice_rubric";
            if (!empty($sql))
                $sql = "rubric_type='$rubric0' && (" . $sql . ")";
            else
                $sql = "rubric_type='$rubric0'";
        }
        $res_goods = $database->query("select ID_GOOD from cprice_rubric_goods natural join cprice_goods $add_tbl where ($sql) && rubricgood_deleted=0 && good_deleted=0 group by ID_GOOD");

        //$res_goods=$database->query("select ID_GOOD FROM ".DB_PREFIX."rubric_goods NATURAL JOIN ".DB_PREFIX."rubric NATURAL JOIN ".DB_PREFIX."goods WHERE rubric_type=2 && rubricgood_deleted=0 && good_deleted=0");
        while ($row_goods = mysql_fetch_array($res_goods)) {
            $res = $database->query("SELECT goodphoto_file from " . DB_PREFIX . "goods_photos WHERE goodphoto_deleted=0 && ID_GOOD=" . $row_goods['ID_GOOD']);
            while (list($goodphoto_file) = mysql_fetch_array($res)) {
                if (file_exists(DATA_FLD . $dir . $goodphoto_file)) {
                    if (isset($_POST['trumb1'])) {
                        print teImgTrumb(DATA_FLD . "good_photo/" . $goodphoto_file, "trumb_", $tmaxw, $tmaxh);
                    }
                    if (isset($_POST['trumb2'])) {
                        print teImgTrumb(DATA_FLD . "good_photo/" . $goodphoto_file, "image_", $mmaxw, $mmaxh);
                    }
                    if (isset($_POST['wtrmrk1']))
                        new_wm_image(DATA_FLD . 'good_photo/' . $goodphoto_file);
                    if (isset($_POST['wtrmrk2']))
                        new_wm_image(DATA_FLD . 'good_photo/' . "image_" . $goodphoto_file);
                    $i++;
                }
            }
        }
        teRedirect(teGetUrlQuery("msg=" . $i));
    }
}

print '</div>';
?>