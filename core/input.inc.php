<?php
//文本框
function input_text($name="",$default="",$choise=""){
	$onclick = "";
	if ($choise!="")
		$onclick = '<input type="button" id="c_'.$name.'" value="选择" onclick="choise(\''.$name.'\',\''.$choise.'\');">';
	return '<input type="text" name="'.$name.'" id="'.$name.'" size=20  value="'.$default.'">' .$onclick;
}
//文本框
function input_password($name="",$default="",$choise=""){
	return '<input type="password" name="'.$name.'" id="'.$name.'" size=20  value="'.$default.'">';
}
//多文本框
function input_multitext($name="",$default="",$value=""){
	if ($value!="")
		$default = $value;
	return '<textarea name="'.$name.'" cols="60" rows="8">'.$default.'</textarea>';
}

//下拉选择
function input_select($name,$default="",$choise=""){
	$result = "<select name='$name' >";
	$def  = explode(",",$choise);
	foreach($def as $key){
		if ($default==$key){
			$result .= "<option value='$key' selected>$key</option>\n";
		}else{
			$result .= "<option value='$key'>$key</option>\n";
		}
	}
	$result .= "</select>";
	return $result;
}


//复选框
function input_checkbox($name,$default="",$choise=""){
	$cho  = explode(",",$choise);
	$def  = explode(",",$default);
	foreach($cho as $id => $key){
		if (in_array($key,$def)){
			$result .= $key." <input name='".$name."[]' type='checkbox' value='$key' checked /> ";
		}else{
			$result .= $key." <input name='".$name."[]' type='checkbox' value='$key' /> ";
		}
	}
	return $result;
}


//单选框
function input_radio($name,$default="",$choise=""){
$result= "";
	$cho  = explode(",",$choise);
	foreach($cho as $id => $key){
		if ($default==$key){
			$result .= " <input name='$name' type='radio' value='$key' checked /> ".$key;
		}else{
			$result .= " <input name='$name' type='radio' value='$key' /> ".$key;
		}
	}
	return $result;
}

//图片
function input_image($name,$default="",$url=""){
	global $query_site ;
	$_default = "";
	if ($default!="") {
		$_default = "<a href='$default' target='_blank'>查看</a>";
	}
	$onclick = "<input type='button' value='上传图片...' onclick=uploadImg('$name'); />";
	return "<input type='text' name='$name' value='$default' size='20' id='$name'> ".$onclick.$_default;
}

//附件
function input_annex($name,$default="",$choise=""){
	global $query_site ;
	$onclick = "<input type='button' value='上传附件...' onclick=uploadAnnex('$name'); />";
	return "<input type='text' name='$name' value='$default' size='20' id='$name'> ".$onclick;
}
//年选择
function input_site($name,$default="",$choise="0"){
	global $mysql;
	$sql = " select * from {site} where pid=$choise";
	$res = $mysql->db_fetch_arrays($sql);
	$result = "<select name='$name' >";
	foreach($res as $key => $value){
		if ($default==$value['site_id']){
			$result .= "<option value='".$value['site_id']."' selected>".$value['name']."</option>\n";
		}else{
			$result .= "<option value='".$value['site_id']."'>".$value['name']."</option>\n";
		}
	}
	$result .= "</select>";
	return $result;
}

//年选择
function input_year($name,$default="",$choise=""){
	$year = date("Y",time());
	$result = "<select name='$name' >";
	for ($i=$year;$i>=1975;$i--){
		if ($i==$defalut){
			$result .= "<option value='$i' selected>$i</option>\n";
		}else{
			$result .= "<option value='$i'>$i</option>\n";
		}
	}
	$result .= "</select>";
	return $result;
}

//
function input_htmltext($name,$default="",$choise=""){
	require_once(ROOT_PATH ."/plugins/editor/sinaeditor/Editor.class.php");
	$editor=new sinaEditor($name);
	$editor->Value= "$default";

	$editor->AutoSave=false;
	return $editor->Create();
}
?>