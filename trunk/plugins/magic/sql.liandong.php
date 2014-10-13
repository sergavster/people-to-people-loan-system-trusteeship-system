<?php

function magic_sql_liandong($parse_var,$magic_vars,$mysql){
	
	if (isset($parse_var['nid'])){
		$nid = $parse_var['nid'];
	}else{
		return "";
	}
	$row =  !isset($parse_var['row'])?5:$parse_var['row'];
	
	
	
	$sql = "select * from {liandong} p1 left join {liandong_type} as p2 on p1.type_id=p2.id where p2.nid = '$nid'";
	//±êÇ©
	$flag = !isset($parse_var['flag'])?"":$parse_var['flag'];
	if ($flag != ""){
		$_flag = explode(",",$flag);
		foreach($_flag as $key => $value){
			$sql .= " and p1.flag like '%$value%'  ";
		}
	}
	//ÅÅÐò
	$order = !isset($parse_var['order'])?"":$parse_var['order'];
	$sql .= " order by p1.`order` desc,p1.id desc";
	
	
	
	$result['result'] = $mysql -> db_fetch_arrays($sql);
	
	if ($result['result']==false){
		return "";
	}
	return $result;
}
?>
