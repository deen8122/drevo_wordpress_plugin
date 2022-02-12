<?php
/* * ********
  deen812@mail.ru
 * 
 * ******** */
print_link_up(teGetUrlQuery("step="));
addGet("step", $step);
// вызываем волшебный модуль управления данными
teInclude("admin_tools");
// слишком понятно, чтобы писать комменты
$OMA = new teModuleActions();
$OMA->setTable(DB_PREFIX . "web_forms");
$OMA->setID("ID_WEB_FORMS");

$OMA->titleTable("Веб формы");
$OMA->titleAdd("Добавление Веб формы");
$OMA->titleEdit("Изменение Веб формы");
$OMA->captionAdd("Добавить Веб форму");

//$OMA->createField("ID_WEB_FORMS", "text", "ID веб формы", true, false);
$OMA->setID('ID_WEB_FORMS');
$OMA->createField("ID_RUBRIC", "database", "Рубрика", true, true);
$OMA->link("ID_RUBRIC", DB_PREFIX . "rubric", "ID_RUBRIC", "rubric_name");
$OMA->createField("FORM_CONFIG", "custom", "Настройки", true, true);
$OMA->createField("FORM_TEXT", "textarea", "Форма", true, true);
$OMA->filterDeleted("webforms_deleted");
$OMA->show();
?>
<div class="description">
    <h3>Модуль веб формы позволяет создать функционал для добавления данных в рубрики</h3>

    <table>
	<tr>
	    <td>Шорткод вызова формы:</td>
	    <td>[drevo_form idf=5]  - где idf  это ID веб формы.</td>
	</tr>
    </table>
    <p>Для одной и той же рубрике можно создовать множество веб форм.</p>
</div>

<?


