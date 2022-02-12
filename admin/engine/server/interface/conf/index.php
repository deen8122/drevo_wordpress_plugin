<?
	// ... самое интересное
	function showConfig($params=''){
		global $_USER;
		global $arrconf;
		global $steps;
		global $step;
		//
		if(!empty($steps) && !empty($steps)) setTitle($steps[$step]);

		//
		if(isset($_GET['save'])){
			print '<div style="clear:both;"></div><div class="alert alert-success">Данные сохранены.</div>';
		}

		print "<div align=center>";
		$frm = new teForm("config","post");

		print "<table align=center>";
		foreach($arrconf AS $name => $param) if(!is_array($param)){
			$frm->addf_group($name,$param);
		} else {
			//if( (isset($param[3]) && !teExistsConf($name)) || !isset($param[3]) ){
				if( $param[0]>9 && $_USER['group']==1){
					$param[0] = $param[0] / 10;
				}

				if(isset($param[4]))
				{
					$frm->setJSScript($name,"onChange",$param[4]);
				}
				// конфиг-массив, 1й параметр
				// 1 - число
				// 2 - текст
				// 4 - чекбокс
				// 5 - селектбокс
				// 6 - сам не понял...
				// *10 - обязательное поле
				switch($param[0]){
					case 1:
						$frm->addf_text($name, $param[1], teGetConf($name));
						$frm->addf_ereg($name, "^[0123456789]*$");
						//$frm->setf_require($name);
					break;
					case 2:
						$frm->addf_text($name, $param[1], teGetConf($name));
						//$frm->setf_require($name);
					break;
					case 4:
						$frm->addf_checkbox($name, $param[1], teGetConf($name));
					break;
					case 5:
						$frm->addf_selectGroup($name, $param[1]);
						foreach($param[3] AS $in => $iv ){
							$frm->addf_selectItem($name, $in, $iv);
						}
						$frm->add_value($name,teGetConf($name));
					break;
					case 6:
						$frm->addf_text($name, $param[1], teGetConf($name), true);
						$frm->add_value($name,teGetConf($name));
						//$frm->setf_require($name);
					break;

					case 10:
					case 20:
						print "<tr><td class=name>".$param[1]."</td><td class=value>".teGetConf($name)."</td></tr>";
					break;
					case 40:
						print "<tr><td class=name>".$param[1]."</td><td class=value>".( (teGetConf($name)==0)?"нет":"да" )."</td></tr>";
					break;
				}

			//}
			if($param[0]<10) $frm->addf_desc($name, $param[2]);
		}
		print "</table>";

		$frm->setSubmitCaption("Сохранить");

		if($frm->send()){

		} else {
			// обработка введенных данных
			foreach($arrconf AS $name => $param){
				if( $param[0]>9 && $_USER['group']==1){
					$param[0] = $param[0] / 10;
				}
				switch($param[0]){
					case 1:
						teSaveConf($name,$frm->get_value($name));
					break;
					case 2:
						teSaveConf($name,$frm->get_value($name));
					break;
					case 4:
						teSaveConf($name,$frm->get_value_checkbox($name));
					break;
					case 5:
						teSaveConf($name,$frm->get_value($name));
					break;
					case 6:
						teSaveConf($name,$frm->get_value($name));
					break;
				}
			}

			// редирект)
			if(!empty($params)) teRedirect(teGetUrlQuery($params,"save=1"));
			else teRedirect(teGetUrlQuery("step=".$step,"save=1"));
		}
		print "</div>";
	}
?>