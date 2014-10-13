<?php

function magic_sql_site($parse_var,$magic_vars,$mysql){
	$site_id = !isset($parse_var['site_id'])?$magic_vars['site_id']:$parse_var['site_id'];
	$_result = $mysql->db_fetch_array("select * from {site} where site_id = ".$site_id);
	$_result['url'] =  format_url("?".$_result['site_id'],array($_result['isurl'],$_result['url']));
	$_result['urlname'] = "<a href='".$_result['url']."'>".$_result['name']."</a>";
	$result['result'][0] = $_result;
	return $result;
}
?>
