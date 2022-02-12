<?

/**********
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
**********/


print_link_up(teGetUrlQuery("step="));
addGet("step",$step);

// вызываем волшебный модуль управления данными
teInclude("admin_tools");

// mysql_select_db(DB_NAME);

// слишком понятно, чтобы писать комменты

$OMA = new teModuleActions();
$OMA->setTable(DB_PREFIX."rubric_types");
$OMA->setID("ID_RUBRIC_TYPE");

$OMA->titleTable("Управление разделами рубрикатора");
$OMA->titleAdd("Добавление нового раздела рубрикатора");
$OMA->titleEdit("Изменение раздела рубрикатора");
$OMA->captionAdd("Добавить раздел рубрикатора");

$OMA->createField("rubrictype_name", "text", "Наименование раздела", true, true);
$OMA->createField("rubrictype_i_s", "text", "Ед.изм., ИП, ед.ч. (<i>напр. \"товар\"</i>)", true, true);
$OMA->createField("rubrictype_i_m", "text", "Ед.изм., ИП, мн.ч. (<i>напр. \"товары\"</i>)", false, true);
$OMA->createField("rubrictype_r_s", "text", "Ед.изм., РП, ед.ч. (<i>напр. \"товара\"</i>)", false, true);
$OMA->createField("rubrictype_r_m", "text", "Ед.изм., РП, мн.ч. (<i>напр. \"товаров\"</i>)", false, true);
$OMA->createField("rubrictype_d_s", "text", "Ед.изм., ДП, ед.ч. (<i>напр. \"товару\"</i>)", false, true);
$OMA->createField("rubrictype_d_m", "text", "Ед.изм., ДП, мн.ч. (<i>напр. \"товарам\"</i>)", false, true);
$OMA->createField("rubrictype_t_s", "text", "Ед.изм., ТП, ед.ч. (<i>напр. \"товаром\"</i>)", false, true);
$OMA->createField("rubrictype_t_m", "text", "Ед.изм., ТП, мн.ч. (<i>напр. \"товарами\"</i>)", false, true);
$OMA->createField("rubrictype_maxlevel", "text", "Максимальная глубина рубрикатора", true, true);
$OMA->createField("rubrictype_stdflds", "checkbox", "Справочник <small>(только дерево, без возможности добавления сущностей)</small>", false, false);
$OMA->createField("rubrictype_visible", "checkbox", "Включить раздел", true, false);

$OMA->filterDeleted("rubrictype_deleted");
$OMA->show();

print "<div class='note'>
	В единицах разделов с отмеченной опцией \"Стандартные поля\" будут доступны специальные поля для создания каталога товаров
	(наименование, описание, цены: розничная, оптовая, дилерская.
</div>";

//mysql_select_db($hosts[$host_name]['db_name']);	die($hosts[$host_name]['db_name']);

?>