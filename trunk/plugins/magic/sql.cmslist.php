<?php

function magic_sql_cmslist($parse_var,$magic_vars,$mysql){

	if (isset($parse_var['site_id'])){
		$site_id = $parse_var['site_id'];
		$limit = !isset($parse_var['limit'])?"":$parse_var['limit'];
	}else{
		$site_id = $magic_vars['site_id'];
		$row =  !isset($parse_var['row'])?10:$parse_var['row'];
		$p =  !isset($magic_vars['page'])?1:$magic_vars['page'];
		$p = $p<=0?1:$p;
		$epage = ($p-1)*$row;
		$limit = "$epage,$row";
	}
	
	
	
	if (isset($parse_var['sitevar'])){
		$site_id =  $magic_vars[$parse_var['sitevar']]['site_id'];
		$parse_var['type'] = "sub";
	}
	
	$_sql = " and p1.site_id=".$site_id;
	if (!isset($parse_var['code'])){
		$result = $mysql->db_fetch_array("select * from {site} where site_id = ".$site_id);
		if ($result==false){
			return array("result"=>array(),"num"=>"");
		}else{
			$module_table = $result['code'];
		}
	}else{
		$module_table = $parse_var['code'];
		$_sql = "";
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
		$sql = "select p1.*,".$fields." from {".$module_table."} as p1 left join {".$module_table."_fields} as p2 on p1.id = p2.article_id where p1.id!=''";
	}else{
		$sql = "select p1.*,p3.name as city_name,p2.nid as site_nid from {".$module_table."} as p1 left join {site} as p2 on p1.site_id=p2.site_id left join {area} as p3 on p1.city = p3.id where p1.id!=''";
	}
	
	//±êÇ©
	$flag = !isset($parse_var['flag'])?"":$parse_var['flag'];
	if ($flag != ""){
		$_flag = explode(",",$flag);
		foreach($_flag as $key => $value){
		$sql .= " and flag like '%$value%'  ";
		}
	}
	
	
	
	//ÀàÐÍ
	$type = !isset($parse_var['type'])?"":$parse_var['type'];
	if ($type == "sub"){
		$_sql = " and p1.site_id=".$site_id;
		$result = $mysql->db_fetch_array("select * from {site} where pid = ".$site_id);
		if ($result!=false){
			$_sql .= " or p1.site_id in(select site_id from  {site} where pid=$site_id)";
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
	
	$sql = "select count(*) as num from {".$module_table."} as p1 where p1.id!='' ".$_sql;
	$__result = $mysql->db_fetch_array($sql);
	$_result['num'] = $__result['num'];
	
	if (isset($p)){
		$_result['page'] = array('total'=>$_result['num'],'perpage'=>$row,'nowindex'=>$p);
	}
	return $_result;
}
?>
