<?php

function magic_sql_flashroll($parse_var,$magic_vars,$mysql){
	
	if (isset($parse_var['nid'])){
		$nid = $parse_var['nid'];
		$sql = "select type_id from {flashroll_type} where nid='$nid'";
		$result = $mysql -> db_fetch_array($sql);
		$type_id = $result['type_id'];
	}else{
		return "";
	}
	$row =  !isset($parse_var['row'])?5:$parse_var['row'];
	
	
	
	$sql = "select * from {flashroll} where type_id = $type_id ";
	//±êÇ©
	$flag = !isset($parse_var['flag'])?"":$parse_var['flag'];
	if ($flag != ""){
		$_flag = explode(",",$flag);
		foreach($_flag as $key => $value){
		$sql .= " and flag like '%$value%'  ";
		}
	}
	//ÅÅÐò
	$order = !isset($parse_var['order'])?"":$parse_var['order'];
	if ($order == ""){
		$sql .= " order by `order` desc,id desc";
	}else{
		$sql .= " order $order";
	}
	
	
	$result = $mysql -> db_fetch_arrays($sql);
	if ($result==false){
		return "";
	}
	$type =  !isset($parse_var['type'])?"":$parse_var['type'];
	if ($type == "all"){
		$pics = array();
		$licpics = array();
		$urls = array();
		$names = array();
		foreach($result as $key => $value){
			$pics[] = $value['pic'];
			$litpics[] = $value['litpic'];
			$urls[] = $value['url'];
			$names[] = $value['name'];
		}
		$_result['result'][0]['url_all'] = join("|",$urls);
		$_result['result'][0]['litpic_all'] = join("|",$litpics);
		$_result['result'][0]['name_all'] = join("|",$names);
		$_result['result'][0]['pic_all'] = join("|",$pics);
		return $_result;
	}
	
	return $result;
}
?>
