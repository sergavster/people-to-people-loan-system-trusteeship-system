<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
include_once("remind.class.php");
$url = $_U['query_url']."/{$_U['query_type']}";
if ($_U['query_type'] == "list"){	
	$_result = "";
	if (isset($_POST['type']) && $_POST['type']==1){
		foreach ($_POST as $key => $value){
			$_message = explode("message_",$key);
			if ($_message[0] == ""){
				$_result[$_message[1]]['message'] = $_POST[$key];
			}
			$_email = explode("email_",$key);
			if ($_email[0] == ""){
				$_result[$_email[1]]['email'] = $_POST[$key];
			}
			$_phone = explode("phone_",$key);
			if ($_phone[0] == ""){
				$_result[$_phone[1]]['phone'] = $_POST[$key];
			}
			
		}
		if ($_result!=""){
			$data['remind'] = serialize($_result);
		}else{
			$data['remind'] = "";
		}
		$data['user_id'] = $_G['user_id'];
		$result = remindClass::ActionRemindUser($data);
		if ($result!==true){
			$msg = array($result,"",$_U['query_url']);
		}else{
			$msg = array("修改成功");
		}
	}else{
		$data['user_id'] = $_G['user_id'];
		$result = remindClass::GetLists($data);
		if ($result!="" && is_array($result)){
			$_U['remind_list'] = $result;
		}else{
			$msg = array($result,"",$_U['query_url']);
		}
	}
	
}else{
	$msg = array("您的操作有误","",$url);
}

$template = "user_remind.html.php";
?>
