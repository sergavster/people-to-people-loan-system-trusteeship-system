<?php

function magic_sql_cmscontent($parse_var,$magic_vars,$mysql){

	$id = !isset($parse_var['id'])?"":$parse_var['id'];
	
	
	
	$_sql = " and p1.id=".$id;
	if (!isset($parse_var['code'])){
		$module_table = "article";
	}else{
		$module_table = $parse_var['code'];
	}
	
	if (isset($parse_var['fields'])){
		$fields = "";
		if ($parse_var['fields']=="all"){
			$fields = "p2.*";
		}else{
			$_fie = explode(",",$parse_var['fields']);
			foreach($_fie as $val){
				$fields .= "p2.".$val;
			}
		}
		$sql = "select p1.*,".$fields." from {cms_".$module_table."} as p1 left join {cms_".$module_table."_fields} as p2 on p1.id = p2.article_id where p1.id!=''";
	}else{
		$sql = "select * from {cms_".$module_table."} as p1 where p1.id!=''";
	}
	
	$_result['result'] = $mysql->db_fetch_arrays($sql.$_sql);
	return $_result;
}
?>
