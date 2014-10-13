<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
include_once("invest.class.php");

if ($_U['query_type'] == "add"){	
	if (!isset($_POST['name'])){
		$msg = array("请不要乱操作","","/publish/index.html");
	}elseif ($_POST['valicode']!=$_SESSION['valicode']){
		$msg = array("验证码不正确");
	}else{	
		$var = array("name","status","use","time_limit","style","account","apr","lowest_account","most_account","valid_time","award","part_account","funds","is_false","open_account","open_invest","open_tender","open_credit","content");
		$data = post_var($var);
		$data['user_id'] = $_G['user_id'];
		$result = investClass::Add($data);
		if ($result===true){
			$msg = array("添加成功");
		}else{
			$msg = array($result);
		}
		
	}
}

$template = "user_invest.html.php";
?>
