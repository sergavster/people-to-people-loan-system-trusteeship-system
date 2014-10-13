<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312" />
<title>无标题文档</title>
<link href="/themes/admin/css/tipswindown.css" rel="stylesheet" type="text/css" />
<script src="/themes/admin/js/jquery.js" type="text/javascript"></script>
<script src="/themes/admin/js/tipswindown.js" type="text/javascript"></script>
</head>


<?php

$order = " order by `order` desc,id asc ";
$_REQUEST['name'] = "a";
if (isset($_GET['area_id'])){
	$city = (int)$_GET["area_id"];
	$sql = "select * from {area} where pid=".$city.$order;
	$result = $mysql->db_fetch_arrays($sql);
	$category['id'] = "";
	$category['name'] = gbk2utf8("请选择");
	$categorys[0] = $category;
	if ($result!=false){
		foreach ($result as $key => $row){
			$category = array();
			$category['id'] = $row['id'];
			$category['name'] = gbk2utf8($row['name']);
			$categorys[$key+1] = $category;
		}
	}
	
	$json = json_encode($categorys);
	echo $json;
	exit;
}


$name = isset($_REQUEST['name'])?$_REQUEST['name']:"";

$type = !isset($_REQUEST['type'])?"":$_REQUEST['type'];

if($type!=""){
	$_type = explode(",",$type);
}else{
	$_type= array("p","c","a");
}


$province_id ="";
$city_id = "";
$area_id = "";
if (isset($_REQUEST['area'])  && $_REQUEST['area']!=""){
	$id = $_REQUEST['area'];
	$sql = "select pid from {area} where id=$id ".$order;
	$result1 = $mysql->db_fetch_array($sql);
	if ($result1['pid']==0){
		$province_id = $id;
	}else{
		$sql = "select pid from {area} where id=".$result1['pid'].$order;
		$result2 = $mysql->db_fetch_array($sql);
		if ($result2['pid']==0){
			$province_id = $result1['pid'];
			$city_id = $id;
		}else{
			$province_id = $result2['pid'];
			$city_id = $result1['pid'];
			$area_id = $id;
		}
	}
}

$_city = "<option value=''>请选择</option>";
if ($province_id!=""){
	$sql = "select * from {area} where pid=".$province_id.$order;
	$city_res = $mysql->db_fetch_arrays($sql);
	foreach ($city_res as $key => $value){
		$sel = "";
		if ($value['id'] === $city_id){ $sel = "selected";}
		$_city .= "<option value=".$value['id']." $sel>".$value['name']."</option>";
	}
}

$_area = "<option value=''>请选择</option>";
if ($city_id!=""){
	$sql = "select * from {area} where pid=".$city_id.$order;
		$area_res = $mysql->db_fetch_arrays($sql);
		foreach ($area_res as $key => $value){
			$sel = "";
			if ($value['id'] === $area_id){ $sel = "selected";}
			$_area .= "<option value=".$value['id']." $sel>".$value['name']."</option>";
		}
}


$sql = "select * from {area} where pid=0".$order;
$result = $mysql->db_fetch_arrays($sql);
$_province ="";

foreach ($result as $key => $value){
	$sel = "";
	if ($value['id'] === $province_id){ $sel = "selected";}
	$_province .= "<option value=".$value['id']." $sel>".$value['name']."</option>";
}

?>
<?
$p = "<select id='{$name}province' name='{$name}province'><option value=''>请选择</option>{$_province}</select>&nbsp;";
$c = "<select id='{$name}city' name='{$name}city'><option value=''>请选择</option>{$_city}</select>&nbsp;";
$a = "<select id='{$name}area' name='{$name}area'><option value=''>请选择</option>{$_area}</select>&nbsp;";
$display= "";
foreach ($_type as $key => $value){
	$display .= $$value;
}

?>
<? echo $display;?>
