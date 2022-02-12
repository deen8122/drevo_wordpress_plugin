<?

/**********
*  ООО "Универсал-Сервис"
*
*  Разработчик:  Teлeнкoв Д.С.
*  e-mail: tdssc@mail.ru
*  ICQ: 420220502
*  Тел.: +7 909 3481503
*  В комментариях нет необходимости...
**********/


	$arrconf['company_name'] = array(2,getIdToPrint_config("configtable",'company_name')." Наименование организации", "");
	$arrconf['company_addr'] = array(2,getIdToPrint_config("configtable",'company_addr')." Адрес организации", "");
	$arrconf['company_tels'] = array(2,getIdToPrint_config("configtable",'company_tels')." Контактные телефоны", "");
	$arrconf['company_email'] = array(2,getIdToPrint_config("configtable",'company_email')." Е-mail организации", "");
	$arrconf['company_conts'] = array(6,getIdToPrint_config("configtable",'company_conts')." Прочая контактная информация", "");

	$arrconf['site_name'] = array(2,getIdToPrint_config("configtable",'site_name')." Наименование сайта", "");
	$arrconf['site_slogan1'] = array(2,getIdToPrint_config("configtable",'site_slogan1')." Слоган (краткий)", "");
	$arrconf['site_slogan2']= array(6,getIdToPrint_config("configtable",'site_slogan2')." Слоган (полный) (если есть)", "");




	$res = $database -> query("SELECT ID_RUBRIC_TYPE,rubrictype_name FROM ".DB_PREFIX."rubric_types");
	while( list($id, $rtname) = mysql_fetch_array($res) ){
		$arr[$id] = $rtname;
	}
	//$arrconf['site_goodstype'] = array(50,getIdToPrint_config("configtable",'site_goodstype')." Раздел - каталог товаров","",$arr);
	$arrconf['site_copyright']= array(2,getIdToPrint_config("configtable",'site_copyright')." Copyright ©", "");
	$arrconf['site_links']= array(6,getIdToPrint_config("configtable",'site_links')." Ссылки", "");
	$arrconf['site_cntrs']= array(6,getIdToPrint_config("configtable",'site_cntrs')." Счетчики", "");

?>