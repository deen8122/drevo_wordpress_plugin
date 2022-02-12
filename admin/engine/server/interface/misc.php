<?
	// функция возвращает ссылку на историю changes по таблице и ID

	teAddJSScript("function ws(ths){return hs.htmlExpand(ths, { objectType: 'iframe' })}");
	function getIdToPrint($table,$id,$rid=0){
		global $_USER;
		if($_USER['group']<3){
			return "<a class='ws' href='?pg=ws&t=$table&r=$id".($rid?'&rid='.$rid:'')."' onclick=\"return ws(this)\">$id<i>i</i></a>";
		} else {
			return $id;
		}
	}

	//для раздела Конфиг
	function getIdToPrint_config($table,$id){
		global $_USER;
		if($_USER['group']<3){
			return "<a class='ws' href='?pg=ws&t=$table&row=$id' onclick=\"return ws(this)\">[<i>i</i>]</a>";
		} else {
			return $id;
		}
	}
?>