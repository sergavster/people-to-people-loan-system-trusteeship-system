<?php

function magic_sql_area($parse_var,$magic_vars,$mysql){
	$sql = "select * from {area} where id!=''";
	$id = !isset($parse_var['id'])?"":$parse_var['id'];
	if (isset($parse_var['upvar'])){
		$id =  $magic_vars[$parse_var['upvar']]['id'];
		$parse_var['type'] = "sub";
	}
	if ($id == "this"){
		$id = $magic_vars['area_id'];
	}
	$type = !isset($parse_var['type'])?"":$parse_var['type'];
	if ($type == "" && !isset($parse_var['id'])){
		$sql .= " and `pid` = 0";
	}elseif ($type=="sub"){
		$sql .= " and `pid` = ".$id;
	}elseif ($type=="brother"){
		$_sql = "select pid from {area} where id=$id";
		$_row =  $mysql->db_fetch_array($_sql);
		$sql .= " and `pid` = ".$_row['pid'];
	}else{
		$sql .= " and `pid` = ".$id;
	}
	
	$pid = !isset($parse_var['pid'])?"":$parse_var['pid'];
	if ($pid!=""){
		$sql .= " and `pid` = ".$pid;
	}
	
	//ÅÅÐò
	$except_id = !isset($parse_var['except_id'])?"":$parse_var['except_id'];
	if ($except_id!=""){
		$sql .= " and `id` not in( $except_id)";
	}
	//ÅÅÐò
	$order = !isset($parse_var['order'])?"":$parse_var['order'];
	if ($order==""){
		$sql .= " order by `order` desc,'id' ";
		
	}elseif ($order=="desc"){
		$sql .= " order by id desc";
	}else if ($order=="asc"){
		$sql .= " order by id asc";
	}else if ($id=="" && $id!=0 ){
		$sql .= " order by find_in_set(id,'$id')";
	}else{
		$sql .= " order by `order` desc";
	}
	$result = $mysql->db_fetch_arrays($sql);
	if ($result!=false){
	foreach ($result as $key=> $value){
		$result['result'][$key]['id'] = $value['id'];
		$result['result'][$key]['name'] = $value['name'];
		$result['result'][$key]['nid'] = $value['nid'];
		$result['result'][$key]['domain'] = $value['domain'];
	}
	}else{
		$result['result'] = array();
	}
	return $result;
}
?>
