<?php
$order = " order by `order` desc,id asc ";
if (isset($_GET['procity_id'])){
	$city = (int)$_GET["procity_id"];
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
	$("#province").change(function(){
		var province = $(this).val();
		var count = 0;
		$.ajax({
			url:"/plugins/index.php",
			dataType:'json', 
			data:"q=procity&procity_id="+province,
			success:function(json){
				$("#city option").each(function(){
					$(this).remove();				 
				});
				$(json).each(function(){
					$("<option value='"+json[count].id+"'>"+json[count].name+"</option>").appendTo("#city");
					count++;
				});
				
			}
		});
	});
	$("#city").change(function(){
		var province = $(this).val();
		var count = 0;
		$.ajax({
			url:"/plugins/index.php",
			dataType:'json', 
			data:"q=procity&procity_id="+province,
			success:function(json){
				$("#area option").each(function(){
					$(this).remove();				 
				});
				$(json).each(function(){					
					$("<option value='"+json[count].id+"'>"+json[count].name+"</option>").appendTo("#area");
					count++;
				});
				if(count>0)
				{
					$("#area").show();
				}else
				{
					$("#area").hide();
				}
			}
		});
	});
	$("#area").change(function(){
		
	});
});

var aa = '<select id="province" name="province"><option value="">«Î—°‘Ò</option><? echo $_province;?></select>&nbsp;<select id="city" name="city" <? if ($_city==""){?>style="display:none"<? }?>><? echo $_city;?></select>&nbsp;<select id="area" name="area" <? if ($_area==""){?>style="display:none"<? }?>><? echo $_area;?></select>';
document.write(aa);
