<?php

$order = " order by `order` desc,id asc ";


$name = isset($_REQUEST['name'])?$_REQUEST['name']:"";
$nid = isset($_REQUEST['nid'])?$_REQUEST['nid']:"";
$id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
$type = !isset($_REQUEST['type'])?"":$_REQUEST['type'];
$val = !isset($_REQUEST['value'])?"":$_REQUEST['value'];

$sql = "select * from {liandong_type} where nid='$nid'";
$result = $mysql->db_fetch_array($sql);
if ($result==false){
	return ;
}else{
	$type_id = $result['id'];
	$sql = "select * from {liandong} where type_id=$type_id";
	$result = $mysql->db_fetch_arrays($sql);
}
$display = "";
$_val = array("");
if ($type=="" || $type == "select"){
	$display .=  "<select name='$name' id=$name >";
	if ($result !="false"){
		$display .=  "<option value=''>≤ªœﬁ</option>";
		foreach ($result as $key => $value){
			if ($val==$value['name']){
				$display .=  "<option value='".$value['name']."' selected>".$value['name']."</option>";
			}else{
				$display .=  "<option value='".$value['name']."'>".$value['name']."</option>";
			}	
		}
	}else{
		$display .=  "<option>«Î—°‘Ò</option>";
	}
	$display .=  "</select>";
	
}elseif ($type == "checkbox"){
	if ($result !="false"){	
		if ($val!=""){
			$_val = explode(",",$val);
		}
		foreach ($result as $key => $value){
			if (in_array($value['name'],$_val)){
				$display .=  "<input  type='checkbox' name=$name value='".$value['name']."' checked>".$value['name']."</option>";
			}else{
				$display .=  "<input type='checkbox' name=$name value='".$value['name']."'>".$value['name']."</option>";
			}	
		}
	}
}
elseif ($type == "radio"){
	if ($result !="false"){	
		foreach ($result as $key => $value){
			if ($value['name']==$val){
				$display .=  "<input  type='radio' name=$name value='".$value['name']."' checked>".$value['name']."</option>";
			}else{
				$display .=  "<input type='radio' name=$name value='".$value['name']."'>".$value['name']."</option>";
			}	
		}
	}
}

?>


document.write("<? echo $display;?>");
<? if (!empty($id)){?>
$("#<? echo $name;?>").change(function(){
	var val = $(this).val();
	$("#<? echo $id;?>").val(val);

})
<? }?>