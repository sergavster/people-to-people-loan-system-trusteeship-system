<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
include_once("borrowline.class.php");

if ($_U['query_type'] == "add" ){	
	if (!isset($_POST['name'])){
		$msg = array("标题必须填写");
	}elseif (strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
		$msg = array("验证码不正确");
	}else{	
		unset($_SESSION['valicode']);
		$var = array("name","borrow_use","type","email","borrow_qixian","province","city","area","account","content","pawn","sex","tel","xing");
		$data = post_var($var);
		$data['status'] = 0;
		$data['user_id'] = $_G['user_id'];
		if (isset($_REQUEST['id']) && $_REQUEST['id']!=""){
			$data['id'] = $_REQUEST['id'];
			$result = borrowlineClass::Update($data);
		}else{
			$result = borrowlineClass::Add($data);
		}
		if ($result===true){
			$msg = array("贷款申请操作成功","","/index.php?user&q=code/company/xuqiu");
		}else{
			$msg = array($result);
		}
	}
}elseif ($_U['query_type']=="del"){
	$data['id'] = $_REQUEST['id'];
	$data['user_id'] = $_G['user_id'];
	$result = borrowlineClass::Delete($data);
	if ($result!=false){
		$msg = array("删除需求成功","","index.php?user&q=code/company/xuqiu");
	}else{
		$msg = array($result);
	}
}
?>
