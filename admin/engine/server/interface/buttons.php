<?
// кнопочки для субменю

	function buttonEnable($url,$title="включить"){
		return "<a class='add' href='{$url}'><img src='".DEEN_FOLDERS_URL."assets/images/btn-offed.png'  alt='закр.' title='$title' /></a>";
	}
	function buttonDisable($url,$title="выключить"){
		return "<a  class='del' href='{$url}'><img src='".DEEN_FOLDERS_URL."assets/images/btn-on-off.png' class='btn-control btn-del' alt='откр.' title='$title' /></a>";
	}
	function buttonClose($url,$title="закрыть"){
		return "<a class='add' href='{$url}'><img src='{$skinpath}images/b_open.gif' style='height:1em;' alt='закр.' title='$title' /></a>";
	}
	function buttonOpen($url,$title="открыть"){
		return "<a class='del' href='{$url}'><img src='".DEEN_FOLDERS_URL."assets/images/b_close.gif' style='height:1em;' alt='откр.' title='$title' /></a>";
	}
	function buttonEdit($url,$title="редактировать"){
		return "<a href='{$url}'><img src='".DEEN_FOLDERS_URL."assets/images/btn-edit2.png' class='btn-edit' alt='ред.' title='$title' /></a>";
	}
	function buttonDelete($url,$title="удалить"){
		return "<a class='del' href='{$url}'><img src='".DEEN_FOLDERS_URL."assets/images/btn-delete.png' class='btn-control btn-del' alt='удал.' title='$title' /></a>";
	}

	function buttonLink($url,$title="Связь"){
		return "<a class='del' href='{$url}'><img src='{$skinpath}images/b_link.gif' style='height:1em;' alt='Связь' title='$title' /></a>";
	}
	
	function buttonMail($url,$title="Написать письмо"){
		return "<a class='del' href='{$url}'><img src='{$skinpath}images/mail.png' style='height:1em;' alt='Написать письмо' title='$title' /></a>";
	}
	
?>