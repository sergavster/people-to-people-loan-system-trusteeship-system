<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
include_once("liuyan.class.php");

if ($_G['user_id']==""){

	$msg = array("你还没有登录，请先登录","会员登录","index.php?user&q=going/login");
}
if ($_U['query_type']=="add"){
	if ($_SESSION['valicode'] != $_POST['valicode']){
		$msg = array("验证码不正确");
	}else{
		$var = array("title","name","email","tel","fax","company","address","type","status","content");
		$data = post_var($var);
		$data['user_id'] = $_G['user_id'];
		$_G['upimg']['file'] = "litpic";
		$pic_result = $upload->upfile($_G['upimg']);
		if ($pic_result!=""){
			$data['litpic'] = $pic_result;
		}
		$result = liuyanClass::Add($data);
		if ($result !== true){
			$msg = array($result);
		}else{
			$_SESSION['valicode'] = "";
			$msg = array("操作成功","","/zaixian/index.html");
		}
	}

}

$template = "user_msg.html.php";
?>
