<?php

function magic_modifier_credit($integral, $parse_var = '',$magic_vars = ''){
	global $mysql;
	if ($integral=="") return "";
	$sql = "select pic from `{credit_rank}` where point1 <= $integral && $integral<= point2";
	$result = $mysql->db_fetch_array($sql);
	return "<img src='/data/images/credit/".$result['pic']."' title='{$integral}'>";
	var_dump($result);exit;
	$linkage_result = $magic_vars["_G"]['linkage'];
	$var = explode(",",$string);
	foreach ($var as $key => $val){
		if ($parse_var != ""){
			$result[] = $linkage_result[$parse_var][$val];
		}else{
			$result[] = $linkage_result[$val];
		}
	}
	return join(",",$result);
}
?>
