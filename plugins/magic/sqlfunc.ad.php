<?php

function magic_sqlfunc_ad($parse_var,$magic_vac_vars,$mysql){
	$result = $mysql->db_fetch_array("select * from {ad} where nid = '".$parse_var['nid']."'");
	if ($result == false) return "";
	if (isset($parse_var['litpic']) && $parse_var['litpic']=="yes"){
		return "<img src={$result['litpic']}>";
	}
	return $result['content'];
}
?>
