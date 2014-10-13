<?php
$order = " order by `order` desc,id asc ";
if (isset($_GET['province_id'])){
	$city = (int)$_GET["province_id"];
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
if (isset($_GET['city_id'])){
	$city = (int)$_GET["city_id"];
	$sql = "select * from {school} where city=".$city.$order;
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
$province_id ="";
$city_id = "";
$area_id = "";
if (isset($_REQUEST['area_id'])  && $_REQUEST['area_id']!=""){
	$id = (int)$_REQUEST['area_id'];
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

$_city = '<option value="">«Î—°‘Ò</option>';
if ($province_id!=""){
	$sql = "select * from {area} where pid=".$province_id.$order;
	$city_res = $mysql->db_fetch_arrays($sql);
	foreach ($city_res as $key => $value){
		$sel = "";
		if ($value['id'] === $city_id){ $sel = "selected";}
		$_city .= "<option value=".$value['id']." $sel>".$value['name']."</option>";
	}
}

$_area = '<option value="">«Î—°‘Ò</option>';
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

$(document).ready(function (){
	$("#school_province").change(function(){
		var province = $(this).val();
		var count = 0;
		$.ajax({
			url:"/plugins/index.php",
			dataType:'json', 
			data:"q=proschool&province_id="+province,
			success:function(json){
				$("#school_city option").each(function(){
					$(this).remove();				 
				});
				$(json).each(function(){
					$("<option value='"+json[count].id+"'>"+json[count].name+"</option>").appendTo("#school_city");
					count++;
				});
				
			}
		});
	});
	$("#school_city").change(function(){
		var province = $(this).val();
		var count = 0;
		$.ajax({
			url:"/plugins/index.php",
			dataType:'json', 
			data:"q=proschool&city_id="+province,
			success:function(json){
				$("#school option").each(function(){
					$(this).remove();				 
				});
				$(json).each(function(){					
					$("<option value='"+json[count].id+"'>"+json[count].name+"</option>").appendTo("#school");
					count++;
				});
				
			}
		});
	});
	
});

var aa = '<select id="school_province" name="school_province"><option value="">«Î—°‘Ò</option><? echo $_province;?></select>&nbsp;<select id="school_city" name="school_city" <? if ($_city==""){?>style="display:none"<? }?>><? echo $_city;?></select>';
document.write(aa);
