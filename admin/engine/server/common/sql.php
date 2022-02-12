<?

	// для следующей функции
	function getSQLF($feature_id){
		return "f".$feature_id;
	}
	
	/*****
	**  Возвращает SQL-запрос.
	**  Параметры:
	**  $params - массив(
	**     0 - ID_RUBRIC_TYPE
	**     1 - ID_RUBRIC
	**     2 - 
	**  )
	** остальные параметры - ID-характеристик
	*****/
	function getSQLGF($params){
		if(!isset($params[2])) $params[2] = 0;
		list($rubric_type, $rubric_id, $good_id) = $params;
		
		$list = func_get_args();
		$s = $f = $h = "";
		foreach($list AS $i => $it) if($i>0) {
			$s .= ", ".getSQLF($it).".ID_FEATURE AS f".$it."id, ".getSQLF($it).".goodfeature_value AS ".getSQLF($it)." ";
			$f .= " LEFT JOIN cprice_goods_features AS ".getSQLF($it)." ON (cprice_goods.ID_GOOD=".getSQLF($it).".ID_GOOD) ";
			$h .= " f".$it."id=".$it." and ";
		}
		return "
			SELECT 
				cprice_goods.ID_GOOD AS id, cprice_goods.* $s
			FROM 
				cprice_rubric
				NATURAL JOIN 
				cprice_rubric_goods 
				NATURAL JOIN 
				cprice_goods 
					$f
			WHERE cprice_rubric.ID_RUBRIC=$rubric_id and rubric_type=$rubric_type and rubricgood_deleted=0 and good_deleted=0 ".(!empty($good_id)?"and cprice_goods.ID_GOOD=".$good_id:"")."
			HAVING $h true
		";
	}
	
	function getSQLGoodsFromRubric($rubric_type,$rubric_id){
		return "
			SELECT cprice_goods.ID_GOOD, cprice_goods.*
			FROM cprice_rubric NATURAL JOIN cprice_rubric_goods NATURAL JOIN cprice_goods
			WHERE cprice_rubric.ID_RUBRIC=$rubric_id and rubric_type=$rubric_type and rubricgood_deleted=0 and good_deleted=0
		";
	}

?>