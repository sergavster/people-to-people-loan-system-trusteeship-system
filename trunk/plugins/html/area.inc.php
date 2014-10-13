<?php
$order = " order by `order` desc,id asc ";
if (isset($_GET['area_id'])){
	$city = (int)$_GET["area_id"];
	$sql = "select * from {area} where pid=".$city.$order;
	$result = $mysql->db_fetch_arrays($sql);
	$category['id'] = "";
	$category['name'] = gbk2utf8("«Î—°‘Ò");
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

if (isset($_REQUEST['value'])){
$_REQUEST['area'] = $_REQUEST['value'];
}
$name = isset($_REQUEST['name'])?$_REQUEST['name']:"";

$type = !isset($_REQUEST['type'])?"":$_REQUEST['type'];
if($type!=""){
	$_type = explode("|",$type);
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
	if ($result1=="") $result1['pid'] =0;
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

$_city = "";
if ($province_id!=""){
	$sql = "select * from {area} where pid=".$province_id.$order;
	$city_res = $mysql->db_fetch_arrays($sql);
	foreach ($city_res as $key => $value){
		$sel = "";
		if ($value['id'] === $city_id){ $sel = "selected";}
		$_city .= "<option value=".$value['id']." $sel>".$value['name']."</option>";
	}
}

$_area = "";
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

jQuery(document).ready(function (){
	jQuery("#<? echo $name;?>province").change(function(){
		var province = jQuery(this).val();
		var count = 0;
		jQuery.ajax({
			url:"/plugins/index.php",
			dataType:'json', 
			data:"q=area&area_id="+province,
			success:function(json){
				jQuery("#<? echo $name;?>city option").each(function(){
					jQuery(this).remove();				 
				});
				jQuery(json).each(function(){
					jQuery("<option value='"+json[count].id+"'>"+json[count].name+"</option>").appendTo("#<? echo $name;?>city");
					count++;
				});
				
			}
		});
	});
	jQuery("#<? echo $name;?>city").change(function(){
		var province = jQuery(this).val();
		var count = 0;
		jQuery.ajax({
			url:"/plugins/index.php",
			dataType:'json', 
			data:"q=area&area_id="+province,
			success:function(json){
				jQuery("#<? echo $name;?>area option").each(function(){
					jQuery(this).remove();				 
				});
				jQuery(json).each(function(){					
					jQuery("<option value='"+json[count].id+"'>"+json[count].name+"</option>").appendTo("#<? echo $name;?>area");
					count++;
				});
				if(count>0)
				{
					jQuery("#<? echo $name;?>area").show();
				}else
				{
					jQuery("#<? echo $name;?>area").hide();
				}
			}
		});
	});
	jQuery("#<? echo $name;?>area").change(function(){
		
	});
	jQuery("#<? echo $name;?>province").val("");
	jQuery("#<? echo $name;?>city").val("");
	jQuery("#<? echo $name;?>area").val("");
});
<?
$p = "<select id='{$name}province' name='{$name}province'><option value=''>«Î—°‘Ò</option>{$_province}</select>&nbsp;";
$c = "<select id='{$name}city' name='{$name}city'><option value=''>«Î—°‘Ò</option>{$_city}</select>&nbsp;";
$a = "<select id='{$name}area' name='{$name}area'><option value=''>«Î—°‘Ò</option>{$_area}</select>&nbsp;";
$display= "";
foreach ($_type as $key => $value){
	$display .= $$value;
}
header('content-type: application/x-javascript');
?>
document.write("<? echo $display;?>");
