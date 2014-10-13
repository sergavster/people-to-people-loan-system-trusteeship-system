<?php

function magic_sql_list($parse_var,$magic_vars,$mysql){
	$result = $mysql->db_fetch_array("select * from {site} where site_id = ".$magic_vars['site_id']);
	if ($result==false){
		return "";
	}else{
		$module_table = $result['code'];
	}
	$sql = "select p1.*,p2.nid as site_nid,p2.name as site_name from {".$module_table."} as p1 left join {site} as p2 on p1.site_id =p2.site_id where p1.site_id=".$magic_vars['site_id'];
	$result['result'] = $mysql->db_fetch_arrays($sql);
	foreach ($result['result'] as $key=> $value){
		$result['result'][$key]['url'] = "?".$magic_vars['site_id']."/".$value['id'];
	}
	$sql = "select count(*) as num from {".$module_table."} where site_id=".$magic_vars['site_id'];
	$_result = $mysql->db_fetch_array($sql);
	$result['num'] = $_result['num'];
	return $result;
}
?>
