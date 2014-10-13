<?php

function magic_sql_arealinks($parse_var,$magic_vars,$mysql){
	$result = array();
	$_sql = "";
	$sql = "select * from {module} where code='arealinks'";
	$_result = $mysql->db_fetch_array($sql);
	if ($_result==false){
		return array("result"=>array(),"num"=>"");
	}
	$sql ="select * from {arealinks} where status=1  ";
	if (isset($parse_var['logo'])) {
		if ($parse_var['logo']=="true"){
			$sql .= " and logo!='' ";
		}else{
			$sql .= " and logo='' ";
		}
	}
	
	if (isset($parse_var['area'])) {
		if ($parse_var['area']=="this"){
			$sql .= " and (area=".$magic_vars['area_id']." or flag like '%tong%' )";
		}else{
			$sql .= " and (area=".$parse_var['area']." or flag like '%tong%' )";
		}
	}
	
	
	if (isset($parse_var['limit'])) {
		$_sql = " limit ".$parse_var['limit'];
	}
	$sql .= " order by `order` desc,id desc";
	$_result = $mysql->db_fetch_arrays($sql.$_sql);
	if ($_result==false){
		return array("result"=>array(),"num"=>"");
	}
	
	$result['result'] = $_result;
	foreach ($result['result'] as $key=> $value){
		$result['result'][$key]['url'] = $value['url'];
		$result['result'][$key]['urlname'] = "<a href='".$value['url']."' target='_blank'>".$value['webname']."</a>";
		$result['result'][$key]['urllogo'] = "<a href='".$value['url']."' target='_blank'><img src=./".$value['logo']." height=28 border=0 ></a>";
	}
	return $result;
}
?>
