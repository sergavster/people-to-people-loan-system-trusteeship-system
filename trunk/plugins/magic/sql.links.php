<?php

function magic_sql_links($parse_var,$magic_vars,$mysql){
	$result = array();
	$_sql = "";
	$sql = "select * from {module} where code='links'";
	$_result = $mysql->db_fetch_array($sql);
	if ($_result==false){
		return array("result"=>array(),"num"=>"");
	}
	$sql ="select * from {links} where id!='' ";
	if (isset($parse_var['logo'])) {
		if ($parse_var['logo']=="true"){
			$sql .= " and logo!='' or logoimg!='' ";
		}else{
			$sql .= " and logo='' and  logoimg=''";
		}
	}
	if (isset($parse_var['type'])) {
		$sql .= " and type_id= ".$parse_var['type'];
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
