<?php

function magic_modifier_linkage($string, $parse_var = '',$magic_vars = ''){
	global $mysql;
	if ($string=="") return "";
	$linkage_result = $magic_vars["_G"]['linkage'];
	if($linkage_result){
		$_parse_var = explode("/",$parse_var);
		$parse_var = $_parse_var[0];
		
		$var = explode(",",$string);
		$result = array();
		foreach ($var as $key => $val){
			if (isset($_parse_var[1]) && $_parse_var[1] =="value"){
				foreach ($linkage_result[$parse_var] as $key => $value){
					if ($linkage_result[$val]==$value){
						$result[] = $key;
					}
				}
			}elseif ($parse_var != ""){
				$result[] = $linkage_result[$parse_var][$val];
			}elseif (isset($linkage_result[$val])){
				$result[] = $linkage_result[$val];
			}
		}
		
		return join(",",$result);
	}else{
	
		$sql = "select * from {linkage_type} where nid='$parse_var'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			return ;
		}else{
			$type_id = $result['id'];
			$sql = "select * from {linkage} where type_id={$type_id} and value LIKE '{$string}' order by `order` asc";
			$result = $mysql->db_fetch_array($sql);
			if($result==false){
				$sql = "select * from {linkage} where type_id={$type_id} and id LIKE '{$string}' order by `order` asc";
				$result = $mysql->db_fetch_array($sql);
			}
		}
		
		return  $result['name'];
	}
}
?>
