<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
//Галлямов Д.Р. like-person@yandex.ru, icq: 222-811-798
print "<br/><br/>";
print "<h2>История изменений записей в проекте (changes)</h2>";
print "<div align=center>";

$frm = new teForm("form2","get");
$frm->addTitle("<b>Фильтр изменений</b>");
$frm->setSubmitCaption("Запуск");
$frm->addf_hidden('pg', 'config',false);
$frm->addf_hidden('step', '6',false);
$frm->addf_text('d1', 'Начальная дата', date("Y-m-d"),false,false);
$frm->addf_ereg('d1', '^([0-9]{4}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1})*$');
$frm->addf_text('d2', 'Конечная дата', date("Y-m-d"),false,false);
$frm->addf_ereg('d2', '^([0-9]{4}-[0-1]{1}[0-9]{1}-[0-3]{1}[0-9]{1})*$');

$frm->addf_selectGroup("sel_user", "Пользователь:",false,false);
$res = $database -> query("SELECT ID_USER FROM ".DB_PREFIX."changes group by ID_USER order by ID_USER");
combase();
while($row=mysql_fetch_array($res))
{	$id=-1;
	if($row['ID_USER']>0)
	{		$line = $database -> getArrayOfQuery("select user_name, user_sname from ".DB_PREFIX."users WHERE ID_USER='".$row['ID_USER']."'");
		$create = $line[0].' '.$line[1];
		$id = $row['ID_USER'];
    }else $create = 'Аминистратор';	$frm->addf_selectItem("sel_user", $id, $create);
}
curbase();

$frm->addf_selectGroup("sel_tbl", "Таблица:",false,false);
$res = $database -> query("SELECT change_table FROM ".DB_PREFIX."changes group by change_table order by change_table");
while($row=mysql_fetch_array($res))
	$frm->addf_selectItem("sel_tbl", $row['change_table'], $row['change_table']);
$frm->addf_selectGroup("sel_type", "Тип действия:",false,false);
$frm->addf_selectItem("sel_type", 1, 'Добавление');
$frm->addf_selectItem("sel_type", 2, 'Изменение');
$frm->addf_selectItem("sel_type", 3, 'Удаление');
$frm->setf_require('d1','d2');
if(!$frm->send())
{	$d1 = $frm->get_value('d1').' 00:00:00';
	$d2 = $frm->get_value('d2').' 23:59:59';
	$user = (int)$frm->get_value('sel_user');
	$tbl = $frm->get_value('sel_tbl');
	$type = (int)$frm->get_value('sel_type');
	$sql='';
	if($user>0) $sql .= ' && ID_USER='.$user;
	elseif($user==-1) $sql .= ' && ID_USER=0';
	if(!empty($tbl)) $sql .= " && change_table='".$tbl."'";
	if($type>0) $sql .= ' && change_type='.$type;
	$OList = new teList("SELECT *, DATE_FORMAT(change_dt,'%d.%m.%Y %H:%i:%s') as td FROM ".DB_PREFIX."changes where change_dt > '$d1' && change_dt < '$d2' $sql order by change_dt desc",40);
	$OList->addToHead('Дата/время');
	$OList->addToHead('Пользователь');
	$OList->addToHead('Таблица');
	$OList->addToHead('Действие');
	$OList->addToHead('ИД записи');
	$OList->query();
	combase();
	while($OList->row()){
		$user = $OList->getValue('ID_USER');
		if($user>0)
		{
			$line = $database -> getArrayOfQuery("select user_name, user_sname from ".DB_PREFIX."users WHERE ID_USER='".$user."'");
			$create = $line[0].' '.$line[1];
	    }else $create = 'Аминистратор';
		$OList->addUserField('{td}');
		$OList->addUserField($create);
		$OList->addUserField('{change_table}');
		$type = $OList->getValue('change_type');
		$type_str='';
		switch($type)
		{			case 1: $type_str='Добавление';break;			case 2: $type_str='Изменение';break;
			case 3: $type_str='Удаление';break;
		}
		$OList->addUserField($type_str);
		$OList->addUserField('{change_row}');
	}
	curbase();
	$OList->addParamTable('');
	echo($OList->getHTML());
	unset($OList);
	print '<br/><a href="'.teGetUrlQuery('step=6').'">Изменить настройки фильтра</a>';
}
print "</div>";
?>