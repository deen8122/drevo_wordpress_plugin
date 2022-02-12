<?
$sql = array();
$sql[] = <<<SQL
CREATE TABLE `cprice_changes` (
  `ID_CHANGE` int(10) unsigned NOT NULL auto_increment,
  `change_type` int(1) unsigned NOT NULL default '1',
  `change_table` varchar(32) NOT NULL default '',
  `change_row` int(255) unsigned NOT NULL default '0',
  `ID_USER` int(10) unsigned NOT NULL default '0',
  `change_dt` timestamp NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(32) NOT NULL,
  `old_values` longtext NOT NULL,
  `ID_GOOD` int(10) NOT NULL default '0',
  PRIMARY KEY  (`ID_CHANGE`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_changes_configtable` (
  `ID_CHANGE` int(10) unsigned NOT NULL auto_increment,
  `change_type` int(1) unsigned default '1',
  `change_table` varchar(32) default NULL,
  `change_row` varchar(32) default NULL,
  `ID_USER` int(10) unsigned default '0',
  `change_dt` timestamp NOT NULL default '0000-00-00 00:00:00',
  `ip` varchar(32) NOT NULL,
  `old_values` text NOT NULL,
  PRIMARY KEY  (`ID_CHANGE`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_configtable` (
  `var_name` varchar(32) NOT NULL default '',
  `var_value` text NOT NULL,
  UNIQUE KEY `var_name` (`var_name`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_features` (
  `ID_FEATURE` int(10) NOT NULL auto_increment,
  `feature_text` varchar(100) NOT NULL default '',
  `feature_rubric` int(10) unsigned NOT NULL default '0',
  `feature_type` int(1) unsigned NOT NULL default '0',
  `feature_unique` int(1) unsigned default NULL,
  `feature_require` int(1) unsigned NOT NULL default '0',
  `feature_multiple` int(1) unsigned NOT NULL default '0',
  `feature_graduation` int(1) unsigned NOT NULL default '0',
  `feature_default` varchar(255) default NULL,
  `feature_enable` int(1) unsigned NOT NULL default '1',
  `feature_deleted` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID_FEATURE`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_feature_directory` (
  `ID_FEATURE_DIRECTORY` int(10) unsigned NOT NULL auto_increment,
  `ID_FEATURE` int(10) unsigned NOT NULL default '0',
  `featuredirectory_text` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`ID_FEATURE_DIRECTORY`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_feature_rubric` (
  `ID_FEATURE` int(10) unsigned NOT NULL default '0',
  `ID_RUBRIC` int(10) unsigned NOT NULL default '0'
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_goods` (
  `ID_GOOD` int(10) unsigned NOT NULL auto_increment,
  `good_name` varchar(255) default NULL,
  `good_url` varchar(255) NOT NULL,
  `good_visible` int(1) unsigned NOT NULL default '0',
  `good_deleted` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID_GOOD`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_goods_features` (
  `ID_GOOD_FEATURE` int(10) unsigned NOT NULL auto_increment,
  `ID_GOOD` int(10) unsigned NOT NULL default '0',
  `ID_FEATURE` int(10) unsigned NOT NULL default '0',
  `goodfeature_value` varchar(255) NOT NULL default '',
  `goodfeature_visible` int(1) unsigned NOT NULL default '1',
  `goodfeature_float` float NOT NULL,
  PRIMARY KEY  (`ID_GOOD_FEATURE`),
  KEY `ind1` (`ID_GOOD`),
  KEY `ind2` (`ID_FEATURE`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_goods_news` (
  `ID_GOOD_NEW` int(10) unsigned NOT NULL auto_increment,
  `goodnew_title` varchar(255) NOT NULL default '',
  `goodnew_text` text NOT NULL,
  `goodnew_type` int(1) unsigned NOT NULL default '0',
  `ID_RUBRIC_TYPE` int(10) unsigned NOT NULL default '0',
  `goodnew_dt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `goodnew_deleted` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID_GOOD_NEW`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_goods_news_templates` (
  `ID_GOOD_NEW_TEMPLATE` int(10) unsigned NOT NULL auto_increment,
  `ID_RUBRIC_TYPE` int(10) unsigned NOT NULL default '0',
  `goodnewtemplate_type` int(2) unsigned NOT NULL default '0',
  `goodnewtemplate_title` varchar(255) NOT NULL default '',
  `goodnewtemplate_text` text NOT NULL,
  `goodnewtemplate_priority` int(1) unsigned NOT NULL default '4',
  `goodnewtemplate_usenum` int(3) unsigned NOT NULL default '0',
  `goodnewtemplate_deleted` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID_GOOD_NEW_TEMPLATE`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_goods_photos` (
  `ID_GOOD_PHOTO` int(10) unsigned NOT NULL auto_increment,
  `ID_GOOD` int(10) unsigned NOT NULL default '0',
  `goodphoto_file` varchar(64) NOT NULL default '',
  `goodphoto_desc` varchar(255) default NULL,
  `goodphoto_alt` varchar(255) default NULL,
  `goodphoto_pos` int(3) unsigned NOT NULL default '0',
  `goodphoto_visible` int(1) unsigned NOT NULL default '1',
  `goodphoto_deleted` int(1) NOT NULL default '0',
  PRIMARY KEY  (`ID_GOOD_PHOTO`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_metadata` (
  `ID_METADATA` int(10) NOT NULL auto_increment,
  `metadata_page` int(3) unsigned NOT NULL default '0',
  `metadata_id` varchar(128) NOT NULL default '0',
  `metadata_head_title` varchar(255) default NULL,
  `metadata_meta_title` varchar(255) default NULL,
  `metadata_meta_keywords` text,
  `metadata_meta_description` text,
  `metadata_body_h1` varchar(255) default NULL,
  `metadata_body_h2` varchar(255) default NULL,
  `metadata_body_description` text,
  `metadata_body_keywords` text,
  PRIMARY KEY  (`ID_METADATA`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_metadata_old` (
  `ID_METADATA` int(10) NOT NULL auto_increment,
  `metadata_page` int(3) unsigned NOT NULL default '0',
  `metadata_id` varchar(128) NOT NULL default '0',
  `metadata_head_title` varchar(255) default NULL,
  `metadata_meta_title` varchar(255) default NULL,
  `metadata_meta_keywords` text,
  `metadata_meta_description` text,
  `metadata_body_h1` varchar(255) default NULL,
  `metadata_body_h2` varchar(255) default NULL,
  `metadata_body_description` text,
  `metadata_body_keywords` text,
  PRIMARY KEY  (`ID_METADATA`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_rubric` (
  `ID_RUBRIC` int(10) unsigned NOT NULL auto_increment,
  `rubric_textid` varchar(128) NOT NULL default '',
  `rubric_parent` int(10) unsigned NOT NULL default '0',
  `rubric_name` varchar(100) NOT NULL default '',
  `rubric_unit_prefixname` varchar(64) default NULL,
  `rubric_ex` varchar(255) NOT NULL default '',
  `rubric_img` varchar(64) default NULL,
  `rubric_type` int(1) unsigned NOT NULL default '0',
  `rubric_pos` int(3) unsigned default NULL,
  `rubric_close` int(1) unsigned NOT NULL default '0',
  `rubric_visible` int(1) unsigned NOT NULL default '0',
  `rubric_deleted` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID_RUBRIC`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_rubric_events` (
  `id` int(11) NOT NULL auto_increment,
  `tdate` int(11) NOT NULL default '0',
  `ID_GOOD` int(11) NOT NULL default '0',
  `ID_USER` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_rubric_features` (
  `ID_RUBRIC` int(10) unsigned NOT NULL default '0',
  `ID_FEATURE` int(10) unsigned NOT NULL default '0',
  `rubric_type` int(1) unsigned NOT NULL default '0',
  `rubricfeature_graduation` int(1) unsigned NOT NULL default '0',
  `rubricfeature_pos` int(3) unsigned NOT NULL default '0',
  `rubricfeature_ls_man` int(1) unsigned NOT NULL default '0',
  `rubricfeature_ls_pub` int(1) unsigned NOT NULL default '0'
) ENGINE=InnoDB DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_rubric_goods` (
  `ID_RUBRIC_GOOD` int(10) unsigned NOT NULL auto_increment,
  `ID_RUBRIC` int(10) unsigned NOT NULL default '0',
  `ID_GOOD` int(10) unsigned NOT NULL default '0',
  `rubricgood_pos` int(4) unsigned NOT NULL default '0',
  `rubricgood_deleted` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID_RUBRIC_GOOD`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251;
SQL;
$sql[] = <<<SQL
CREATE TABLE IF NOT EXISTS `cprice_rubric_types` (
  `ID_RUBRIC_TYPE` int(2) unsigned NOT NULL auto_increment,
  `rubrictype_name` varchar(32) NOT NULL default '',
  `rubrictype_i_s` varchar(48) NOT NULL default '',
  `rubrictype_i_m` varchar(48) NOT NULL default '',
  `rubrictype_r_s` varchar(48) NOT NULL default '',
  `rubrictype_r_m` varchar(48) NOT NULL default '',
  `rubrictype_d_s` varchar(48) NOT NULL default '',
  `rubrictype_d_m` varchar(48) NOT NULL default '',
  `rubrictype_t_s` varchar(48) NOT NULL default '',
  `rubrictype_t_m` varchar(48) NOT NULL default '',
  `rubrictype_stdflds` int(1) unsigned NOT NULL default '1',
  `rubrictype_maxlevel` int(2) unsigned NOT NULL default '3',
  `rubrictype_visible` int(1) unsigned NOT NULL default '1',
  `rubrictype_deleted` int(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`ID_RUBRIC_TYPE`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 ;
SQL;
$sql[] = <<<SQL
CREATE TABLE `cprice_texts` (
  `ID_TEXT` int(10) unsigned NOT NULL auto_increment,
  `text_text` text NOT NULL,
  PRIMARY KEY  (`ID_TEXT`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251;
SQL;
//INSERT SOME DATAS FOR EXAMPLE
$sql[] = <<<SQL
INSERT INTO cprice_rubric_types (`ID_RUBRIC_TYPE`, `rubrictype_name`, `rubrictype_i_s`, `rubrictype_i_m`, `rubrictype_r_s`, `rubrictype_r_m`, `rubrictype_d_s`, `rubrictype_d_m`, `rubrictype_t_s`, `rubrictype_t_m`, `rubrictype_stdflds`, `rubrictype_maxlevel`, `rubrictype_visible`, `rubrictype_deleted`) VALUES
(1, 'Данные', 'запись', 'записи', 'запись', 'записей', 'запись', 'записям', 'записью', 'записями', 0, 2, 1, 0);
SQL;

$sql[] = <<<SQL
INSERT INTO cprice_rubric (`rubric_textid`, `rubric_parent`, `rubric_name`, `rubric_type`, `rubric_visible`) VALUES ('index', '0', 'Example folder', '1', '1');
SQL;



//CONFIG DATAS
$sql[] = <<<SQL
INSERT INTO `cprice_configtable` (`var_name`, `var_value`) VALUES
('company_addr', ''),
('company_conts', '+7(000)000-00-01'),
('company_email', 'my@mail.ru'),
('company_name', 'Название компании'),
('company_tels', '+7(000)000-00-01'),
('module_dilers', '0'),
('module_ishop', ''),
('notify_22', 'type_event_2| mail@mail.ru|0|'),
('photo_kvadr', '0'),
('photo_mmaxh', '1000'),
('photo_mmaxw', '1000'),
('photo_tmaxh', '150'),
('photo_tmaxw', '150'),
('rtpl_4_name', '23'),
('rtpl_4_price', ''),
('rtpl_4_rtype', '2'),
('rtpl_5_pub', '7'),
('rtpl_7', 'form.php'),
('rtpl_7_cap', '1'),
('rtpl_7_fsrc', '16'),
('rtpl_7_pub', '0'),
('rtpl_7_sent', 'Ваше сообщение отправлено, спасибо!'),
('rtpl_7_snd', '1'),
('rtpl_8', 'form.php'),
('rtpl_8_cap', '1'),
('rtpl_8_fsrc', '22'),
('rtpl_8_pub', '0'),
('rtpl_8_sent', 'Ваше сообщение отправлено, спасибо!'),
('rtpl_8_snd', '1'),
('rtpl_defaultpage', '1'),
('rtpl_type', '1'),
('site_cntrs', ''),
('site_copyright', '© Все права защищены, 2017'),
('site_goodstype', '2'),
('site_links', '<a href="http://2dweb.ru">Разработка сайта</a> -<br/>Интернет-агентство 2D'),
('site_name', 'site.ru'),
('site_slogan1', 'Слоган'),
('site_slogan2', ''),
('type_event_1', 'Написать письмо директору|Написать письмо директору %site_name%$#%body%'),
('type_event_2', 'Форма отправки сообщения, вопроса|Форма отправки сообщения, вопроса %site_name%$#%body%'),
('u1111r11CntOnPg', '20'),
('u1111r11f1sort', '0'),
('u1111r11f24sort', '0'),
('u1111r11f25sort', '0'),
('u1111r11f2sort', '0'),
('u1111r11fCRTshow', '0'),
('u1111r11fIDsort', '0'),
('u1111r11fLSTshow', '0'),
('u1111r11fLSTsort', '0'),
('u1111r11vislist', '1;2;24;25'),
('u1111r12CntOnPg', '20'),
('u1111r12f1sort', '0'),
('u1111r12f24sort', '0'),
('u1111r12f25sort', '0'),
('u1111r12f2sort', '0'),
('u1111r12fCRTshow', '0'),
('u1111r12fIDsort', '0'),
('u1111r12fLSTshow', '0'),
('u1111r12fLSTsort', '0'),
('u1111r12vislist', '1;2;24;25'),
('u1145r24CntOnPg', '20'),
('u1145r24f17sort', '0'),
('u1145r24f19sort', '0'),
('u1145r24f20sort', '0'),
('u1145r24f21sort', '0'),
('u1145r24f22sort', '0'),
('u1145r24f26sort', '0'),
('u1145r24fCRTshow', '0'),
('u1145r24fIDsort', '0'),
('u1145r24fLSTshow', '0'),
('u1145r24fLSTsort', '0'),
('u1145r24vislist', '17;20;21'),
('ur24CntOnPg', '5'),
('ur24f17sort', '0'),
('ur24f19sort', '0'),
('ur24f20sort', '0'),
('ur24f21sort', '0'),
('ur24f22sort', '0'),
('ur24f26sort', '0'),
('ur24fCRTshow', '0'),
('ur24fIDsort', '0'),
('ur24fLSTshow', '0'),
('ur24fLSTsort', '0'),
('ur24vislist', '17;19;20;21'),
('ur32CntOnPg', '20'),
('ur32f42sort', '0'),
('ur32f44sort', '0'),
('ur32f45sort', '0'),
('ur32f46sort', '0'),
('ur32f47sort', '0'),
('ur32fCRTshow', '0'),
('ur32fIDsort', '0'),
('ur32fLSTshow', '0'),
('ur32fLSTsort', '0'),
('ur32vislist', '42;44;45;46;47'),
('wm_alfa', '50'),
('wm_color', '5'),
('wm_pos', '4'),
('wm_text', '');
SQL;
?>