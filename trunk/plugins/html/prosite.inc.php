<?php

$_order = " order by `order` desc";

if (isset($_GET['site_id']) ){
	if ($_GET['site_id']!=''){
		$site_id = (int)$_GET["site_id"];
		$sql = "select * from {site} where pid=".$site_id.$_order;
		$result = $mysql->db_fetch_arrays($sql);
		$category['id'] = "";
		$category['name'] = gbk2utf8("«Î—°‘Ò");
		$categorys[0] = $category;
		if ($result!=false){
			foreach ($result as $key => $row){
				$category = array();
				$category['id'] = $row['site_id'];
				$category['name'] = gbk2utf8($row['name']);
				$categorys[$key+1] = $category;
			}
		}
		$json = json_encode($categorys);
		echo $json;
		exit;
	}else{
		echo "";
		exit;
	}
}
$site_first ="";
$site_center = "";
$site_last = "";
if (isset($_REQUEST['siteid'])  && $_REQUEST['siteid']!=""){
	$id = (int)$_REQUEST['siteid'];
	$sql = "select pid from {site} where site_id=".$id.$_order;
	$result1 = $mysql->db_fetch_array($sql);
	if ($result1['pid']==0){
		$site_first = $id;
	}else{
		$sql = "select pid from {site} where site_id=".$result1['pid'];
		$result2 = $mysql->db_fetch_array($sql);
		if ($result2['pid']==0){
			$site_first = $result1['pid'];
			$site_center = $id;
		}else{
			$site_first = $result2['pid'];
			$site_center = $result1['pid'];
			$site_last = $id;
		}
	}
}
$_site_center = "";
if ($site_first!=""){
	$sql = "select * from {site} where pid=".$site_first.$_order;
	$site_center_res = $mysql->db_fetch_arrays($sql);
	foreach ($site_center_res as $key => $value){
		$sel = "";
		if ($value['site_id'] === $site_center){ $sel = "selected";}
		$_site_center .= "<option value=".$value['site_id']." $sel>".$value['name']."</option>";
	}
}

$_site_last = "";
if ($site_center!=""){
	$sql = "select * from {site} where pid=".$site_center.$_order;
	$site_last_res = $mysql->db_fetch_arrays($sql);
	if ($site_last_res!=false){
	foreach ($site_last_res as $key => $value){
		$sel = "";
		if ($value['site_id'] === $site_last){ $sel = "selected";}
		$_site_last .= "<option value=".$value['site_id']." $sel>".$value['name']."</option>";
	}
	
	}
}else{
	echo '$("#site_last").hide();';
}


$sql = "select * from {site} where pid=0 and site_id!=1 and site_id!=89".$_order;
$result = $mysql->db_fetch_arrays($sql);
$_site_first ="";

foreach ($result as $key => $value){
	$sel = "";
	if ($value['site_id'] === $site_first){ $sel = "selected";}
	$_site_first .= "<option value=".$value['site_id']." $sel>".$value['name']."</option>";
}

?>
$(document).ready(function (){
	$("#site_first").change(function(){
		var site_first = $(this).val();
		var count = 0;
		$.ajax({
			url:"plugins/index.php",
			dataType:'json', 
			data:"q=prosite&site_id="+site_first,
			success:function(json){
				$("#site_center option").each(function(){
					$(this).remove();				 
				});
				$(json).each(function(){
					$("<option value='"+json[count].id+"'>"+json[count].name+"</option>").appendTo("#site_center");
					count++;
				});
				if(count>1){
					$("#site_center").show();
				}else{
					$("#site_center").hide();
				}
				$("#site_last").hide();
				
			}
		});
	});
	$("#site_center").change(function(){
		var site_first = $(this).val();
		var count = 0;
		$("#site_last").hide();
		$.ajax({
			url:"plugins/index.php",
			dataType:'json', 
			data:"q=prosite&site_id="+site_first,
			success:function(json){
				$("#site_last option").each(function(){
					$(this).remove();				 
				});
				$(json).each(function(){					
					$("<option value='"+json[count].id+"'>"+json[count].name+"</option>").appendTo("#site_last");
					count++;
				});
				if(count>1 ){
					$("#site_last").show();
				}else{
					$("#site_last").hide();
				}
			}
		});
	});
	$("#site_last").change(function(){
		//alert($(this).find('option:selected').text());
	});
});


var aa = '<select id="site_first" name="site_first"><option value="">«Î—°‘Ò</option><? echo $_site_first;?></select>&nbsp;<select id="site_center" name="site_center"><option value="">«Î—°‘Ò</option><? echo $_site_center;?></select>&nbsp;<select id="site_last" name="site_last" <? if ($_site_last==""){?>style="display:none"<? }?>><option value="">«Î—°‘Ò</option><? echo $_site_last;?></select>';
document.write(aa);
