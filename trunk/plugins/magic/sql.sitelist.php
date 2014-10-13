<?php

function magic_sql_sitelist($parse_var,$magic_vars,$mysql){
	$sql = "select * from {site} where status=1 ";
	$site_id = !isset($parse_var['site_id'])?$magic_vars['site_id']:$parse_var['site_id'];
	if (isset($parse_var['sitevar'])){
		$site_id =  $magic_vars[$parse_var['sitevar']]['site_id'];
		$parse_var['type'] = "sub";
	}
	if (isset($parse_var['nid'])){
		$_sql = "select site_id from {site} where nid='".$parse_var['nid']."'";
		$row =  $mysql->db_fetch_array($_sql);
		$site_id = $row['site_id'];
		$parse_var['type'] = !isset($parse_var['type'])?"sub":$parse_var['type'];
	}
	
	$type = !isset($parse_var['type'])?"":$parse_var['type'];
	if ($type == "" && !isset($parse_var['site_id'])){
		$sql .= " and `pid` = 0";
	}elseif ($type=="this"){
		$sql .= " and `site_id` in($site_id)";
	}elseif ($type=="sub"){
		$sql .= " and `pid` = ".$site_id;
	}elseif ($type=="brother"){
		$_sql = "select pid from {site} where site_id=$site_id";
		$_row =  $mysql->db_fetch_array($_sql);
		$sql .= " and `pid` = ".$_row['pid'];
	}else{
		$sql .= " and `pid` = ".$site_id;
	}
	
	//ÅÅÐò
	$except_id = !isset($parse_var['except_id'])?"":$parse_var['except_id'];
	if ($except_id!=""){
		$sql .= " and `site_id` not in( $except_id)";
	}
	//ÅÅÐò
	$order = !isset($parse_var['order'])?"":$parse_var['order'];
	if ($order==""){
		if ($type == "" ){
			$sql .= " order by `order` desc";
			
		}else{
			$sql .= " order by `order` desc,'site_id' ";
		}
	}elseif ($order=="desc"){
		$sql .= " order by site_id desc";
	}else if ($order=="asc"){
		$sql .= " order by site_id asc";
	}else if ($site_id=="" && $site_id!=0 ){
		$sql .= " order by find_in_set(site_id,'$site_id')";
	}else{
		$sql .= " order by `order` desc";
	}
	$result = $mysql->db_fetch_arrays($sql);
	if ($result!=false){
	foreach ($result as $key=> $value){
		$result['result'][$key]['url'] = format_url("?".$value['site_id'],"sitelist",array($value['isurl'],$value['url']),$value['list_name']);
		$result['result'][$key]['name'] = $value['name'];
		$result['result'][$key]['litpic'] = isset($value['litpic'])?$value['litpic']:'';
		$result['result'][$key]['description'] = $value['description'];
		$result['result'][$key]['site_id'] = $value['site_id'];
		$result['result'][$key]['nid'] = $value['nid'];
		$result['result'][$key]['urlname'] = "<a href='".$result['result'][$key]['url']."'>".$value['name']."</a>";
		$result['result'][$key]['urlidname'] = "<a href='/{$value['nid']}/index.html'>".$value['name']."</a>";
		$result['result'][$key]['sub'] = $mysql->db_count("site","pid=".$value['site_id']);
	}
	}else{
		$result['result'] = array();
	}
	return $result;
}
?>
