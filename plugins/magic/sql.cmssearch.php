<?php

function magic_sql_cmssearch($parse_var,$magic_vars,$mysql){

	$site_id = !isset($_REQUEST['site_id'])?"":$_REQUEST['site_id'];
	
	$row =  !isset($parse_var['row'])?10:$parse_var['row'];
	$p =  !isset($_REQUEST['page'])?1:$_REQUEST['page'];
	$p = $p<=0?1:$p;
	$epage = ($p-1)*$row;
	$limit = "$epage,$row";
	
	$_sql = "";
	if (!isset($_REQUEST['code'])){
		$result = $mysql->db_fetch_array("select * from {site} where site_id = ".$site_id);
		if ($result==false){
			return array("result"=>array(),"num"=>"");
		}else{
			$module_table = $result['code'];
		}
	}else{
		$module_table = $_REQUEST['code'];
	}
	
	//ËÑË÷
	if (isset($_REQUEST['title']) && $_REQUEST['title']!=""){
		$_sql .= " and title like '%".$_REQUEST['title']."%'";
	}
	if ($site_id!=""){
		$_sql .= " and site_id=$site_id";
	}
	
	
	if (isset($parse_var['fields'])){
		$fields = "";
		if ($parse_var['fields']=="all"){
			$fields = "p2.*";
		}else{
			$_fie = explode(",",$parse_var['fields']);
			foreach($_fie as $val){
				$fields .= "p2.".$val;
			}
		}
		$sql = "select p1.*,".$fields." from {cms_".$module_table."} as p1 left join {cms_".$module_table."_fields} as p2 on p1.id = p2.article_id where p1.id!=''";
	}else{
		$sql = "select * from {cms_".$module_table."} as p1 where p1.id!=''";
	}
	
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
		$_order = " order by p1.order desc,p1.id desc";
	}else{
		$_order = " order $order";
	}
	
	$limit = !isset($parse_var['limit'])?$limit:$parse_var['limit'];
	$sql .= $_sql.$_order;
	if ($limit!="") {
		$sql .= " limit $limit";
	}
	$_result['result'] = $mysql->db_fetch_arrays($sql);
	foreach ($_result['result'] as $key=> $value){
		$_result['result'][$key]['url'] = format_url("?".$value['site_id']."/".$value['id'],$value['addtime']);
		$_result['result'][$key]['urltitle'] = "<a href='".$_result['result'][$key]['url']."'>".$value['title']."</a>";
	}
	
	$sql = "select count(*) as num from {cms_".$module_table."} as p1 where p1.id!='' ".$_sql;
	$__result = $mysql->db_fetch_array($sql);
	$_result['num'] = $__result['num'];
	
	if (isset($p)){
		$_result['page'] = array('total'=>$_result['num'],'perpage'=>$row,'nowindex'=>$p);
	}
	return $_result;
}
?>
