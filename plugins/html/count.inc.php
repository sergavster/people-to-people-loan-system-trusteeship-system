<?
$sql = "select p1.code,p2.type  from {site} as p1,{module} as p2  where p1.code = p2.code and site_id=".$_REQUEST['site_id'];
$result = $mysql->db_fetch_array($sql);
if ($result['type']=="cms"){
	$tbl = "cms_".$result['code'];
}else{
	$tbl = $result['code'];
}
$sql ="update {".$tbl."} set hits = hits+1 where id = ".$_REQUEST['id'];
$mysql->db_query($sql);
$sql = "select hits from {".$tbl."} where id=".$_REQUEST['id'];
$result = $mysql->db_fetch_array($sql);
echo "document.write(".$result['hits'].")";
?>