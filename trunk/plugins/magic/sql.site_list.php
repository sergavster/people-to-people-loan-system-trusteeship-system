<?php

function magic_sql_site_list($parse_var,$magic_vars,$mysql){
	$result = $mysql->db_fetch_arrays("select * from {site} where status=1 and pid=0 order by `order` desc");
	foreach ($result as $key=> $value){
		$result['result'][$key]['url'] = "?".$value['site_id'];
		$result['result'][$key]['name'] = $value['name'];
		$result['result'][$key]['site_id'] = $value['site_id'];
	}
	return $result;
}
?>
