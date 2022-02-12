<?
	$_template->addVar("submenu","");
	$_template->addVar("submenustd","");
	
	// кнопочка в субменю
	function addSubMenu($url, $txt, $varriable = "submenu",$class=''){
		global $_template;
		global $_view;
		if( strpos($_SERVER['REQUEST_URI'],$url) ){
			if( preg_match("/".quotemeta($url)."$/i", $_SERVER['REQUEST_URI']) ){
				$txt = "<a class='".$class." active' href='#'>".$txt."</a>";
			} else {
				$txt = "<a class='".$class." active' href='$url'>".$txt."</a>";
			}
		} else {
			$txt = "<a href='$url' class='".$class."'>".$txt."</a>";
		}
		$_template->addToVar($varriable,$txt);
	}
	
	// заготовки визуальных кнопочек в субменю
	
	function addSubMenuAdd($url = NULL, $text = ''){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery("action=add");
		addSubMenu($url."&from=menu", "<img src='".DEEN_FOLDERS_URL."assets/images/add.png' alt='Добавить' title='Добавить'/>".$text, "submenustd");
	}
	function addSubMenuEnable($url = NULL){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery("action=enable","id=".@$GLOBALS['id']);
		addSubMenu($url."&from=menu", "<img src='{$skinpath}images/b_disable_big.png' alt='Включить' title='Включить'/>", "submenustd");
	}
	function addSubMenuDisable($url = NULL){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery("action=disable","id=".@$GLOBALS['id']);
		addSubMenu($url."&from=menu", "<img src='{$skinpath}images/b_enable_big.png' alt='Выключить' title='Выключить'/>", "submenustd");
	}
	function addSubMenuEdit($url = NULL){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery("action=edit","id=".@$GLOBALS['id']);
		addSubMenu($url."&from=menu", "<img src='".DEEN_FOLDERS_URL."assets/images/btn-edit2.png' alt='Изменить' title='Изменить'/>", "submenustd");
	}
	function addSubMenuDelete($url = NULL){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery("action=delete","id=".@$GLOBALS['id']);
		addSubMenu("javascript: if(confirm(\"Вы действительно желаете удалить текущую запись?\")) 
			location.href=\"".$url."&from=menu\"", "<img src='".DEEN_FOLDERS_URL."assets/images/btn-delete.png' alt='Удалить' title='Удалить'/>", "submenustd");
	}
	function addSubMenuAccess($url = NULL){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery("action=access","id=".@$GLOBALS['id']);
		addSubMenu($url."&from=menu", "<img src='{$skinpath}images/b_access_big.png' alt='Доступ' title='Доступ'/>", "submenustd");
	}
	
	function addSubMenuBack($i){
		global $skinpath;
		addSubMenu("javascript: history.go($i)", "<img src='{$skinpath}images/b_back_big.png' alt='Назад' title='Назад'/>", "submenustd");
	}
	function addSubMenuUp($url = NULL){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery();
		addSubMenu($url."&from=menu", "<img src='".DEEN_FOLDERS_URL."assets/images/btn-left.png' alt='Вверх' title='Вверх'/>", "submenustd");
	}
	
	
	function addSubMenuTree($url = NULL){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery("action=access","id=".@$GLOBALS['id']);
		addSubMenu($url."&from=menu", "<img src='".DEEN_FOLDERS_URL."assets/images/b_tree_big.png' alt='Древовидный вид' title='Древовидный вид'/>", "submenustd");
	}
	function addSubMenuSimple($url = NULL){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery("action=access","id=".@$GLOBALS['id']);
		addSubMenu($url."&from=menu", "<img src='".DEEN_FOLDERS_URL."assets/images/b_simple_big.png' alt='Обычный вид' title='Обычный (пошаговый) вид'/>", "submenustd");
	}
	
	function addSubMenuOrder($url = NULL){
		global $skinpath;
		if(empty($url)) $url = teGetUrlQuery("action=order","rubric_id=".@$GLOBALS['rubric_id'],"good_id=".@$GLOBALS['good_id']);
		addSubMenu($url."&from=menu", "<img src='{$skinpath}images/b_order_big.gif' alt='Отчет' title='Отчет'/>", "submenustd");
	}
	
?>