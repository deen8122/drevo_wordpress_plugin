<? if(!isset($_status)){header("HTTP/1.0 404 Not Found");die;}
	//
	if( !isset($_POST['rubric']) && !isset($_GET['rubric']) && !isset($_POST['frm_name']) ){
		teRedirect(teGetUrlQuery("action=rubric","typerubric=add"));
	}

	// GET -> POST
	get_rubric_from_get();



	if(  empty($_GET['iframe']) || (!empty($_GET['iframe']) && ( count($_POST['rubric'])==1 || isset($_POST['rubric_']) ) )  ){
		// если выбраны рубрик
		//die('14');

		foreach( $_POST['rubric'] AS $rubric_id => $on ){
			addGet("rubric[".(int)$rubric_id."]","on");
		}

		if( isset( $_GET['feat'] ) ){
			foreach( $_GET['feat'] AS $feat_id => $good_id ){
				addGet("feat[".(int)$feat_id."]",$good_id);
			}
		}

		// если айфрейм, и много рубрик, редирект на выдачу выбора ветви
		if(isset($_GET['iframe']) && count($_POST['rubric'])>1){
			teRedirect("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&rubcnt=".count($_POST['rubric']));
			die();
		}

		// добавляем запись (добавляем её заранее, до сохранения. так надо...) указываем, что запись удалена
		$id=0;
		if((isset($_GET['iframe']) && isset($_POST['rubric'])) ){
			$database -> query("INSERT INTO ".DB_PREFIX."goods (good_visible,good_deleted) VALUES (1,1)");
			$id = $database->lastQueryId;
			// добавляем запись в нужные рубрики
			foreach($_POST['rubric'] AS $rub => $on){
				$database -> query("INSERT INTO ".DB_PREFIX."rubric_goods (ID_RUBRIC,ID_GOOD) VALUES ($rub,$id)");
			}
		}

		// если запрос идёт от iframe (значит добалвяют связанную (дин.ветвь) запись),
		// сохраняем в БД связь, куда же привязана запись
		if(isset($_GET['iframe'])){
			$linkgood = (int)$_GET['linkgood'];
			$linkfeature = (int)$_GET['linkfeature'];
			$database -> query("INSERT INTO ".DB_PREFIX."goods_features (ID_GOOD,ID_FEATURE,goodfeature_value,goodfeature_visible) VALUES ($linkgood,$linkfeature,'$id',1)");
		}

		// после добавления записи в БД, редирект на страницу редактирования записи
		teRedirect("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."&do=&action=edit&id=$id&lastaction=add&ses_add=1");
	} 
	else {
		$rubcntqwe = count($_POST['rubric']);
	}
?>