<?
/******************************
 * $File: site.php
 * $Description: 站点
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问




$_A['list_name'] = "栏目管理";
$_A['list_menu'] = "<a href={$_A['admin_url']}&q=site/loop{$_A['site_url']}>栏目管理</a> - <a href={$_A['admin_url']}&q=site/new{$_A['site_url']}>添加栏目</a> - <a href={$_A['admin_url']}&q=site{$_A['site_url']}>空闲栏目</a>";





//管理员管理类型
$_A['admin_type'] = userClass::GetTypeList(array("type"=>1,"where"=>" and type_id!=1"));
foreach($_A['admin_type'] as $key => $value){
	$_A['admin_type_check'][$value['type_id']] = $value['name'];
}



/**
 * 显示所有栏目
**/

if ($_A['query_class'] == "list" ){
	$_A['list_title'] = "栏目管理"; 
	$_A['list_menu'] = '';
}


$template = "admin_site.html.php";
?>