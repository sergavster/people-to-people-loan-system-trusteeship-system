<?php
require_once("qq/qqConnectAPI.php");
$qc = new QC();
$access_token=$qc->qq_callback(); //access_token
$openid= $qc->get_openid();  //openid


$_G = array();
//基本配置文件
include ("../core/config.inc.php");

$sql="select * from {user} where connect_openid  = '{$openid}'";
$result = $mysql->db_fetch_array($sql);
	
if ($result == false){
	header("location:/");
}else{
	header("location:/index.action?user&q=going/login&openid={$openid}&username={$openid}&password={$access_token}");
	
}
?>

