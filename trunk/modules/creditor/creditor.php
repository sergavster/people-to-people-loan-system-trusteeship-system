<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("creditor_".$_A['query_type']);//检查权限
include_once("creditor.class.php");
$creditor = new creditorClass();
$_A['list_purview'] =  array("credit"=>array("债权转让"=>array("creditor_list"=>"债权转让列表","creditor_view"=>"初审债权转让","creditor_full"=>"复审债权转让","creditor_repeal"=>"撤销债权转让")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['admin_url']}&q=module/creditor/list{$_A['site_url']}'>债权转让列表</a>";
if ($_A['query_type'] == "list"){//列表
	if(!isset($_GET['status'])) $_GET['status']='0,1,3';
	$result = $creditor->get_zqzr_list(array('status'=>$_GET['status']));
	$pages->set_data($result);
	$_A['zqzr_list'] = $result['list'];
	$_A['showpage'] = $pages->show(3);
}elseif($_A['query_type'] == "view"){//初审
	if(isset($_POST['id'])){
		$var = array("id","status","verify_remark");
		$data = post_var($var);
		$data['verify_user'] = $_G['user_id'];
		$data['verify_time'] = time();
		if($data['status']==1){
			$re = $creditor->websg($data);//网站直接收购债权
		}
		//$re = $creditor->verify_creditor($data);
		if($re===true){
			$msg = array('初审成功！','' ,$_A['query_url'].'/list&status=1'.$_A['site_url']);
		}else{
			$msg = array('初审失败！');
		}
	}else{
		$id = (int)$_GET['id'];
		if($id<1){
			$msg = array('对不起，您的操作有误！');
		}else{
			$result = $creditor->get_zqzr_list(array('id'=>$id));
			$_A['zqzr_result'] = $result['list'][0];
		}
	}
}
elseif($_A['query_type'] == "fulllist"){
	$result = $creditor->get_zqzr_list(array('status'=>1,'type'=>'full'));
	$pages->set_data($result);
	$_A['zqzr_list'] = $result['list'];
	$_A['showpage'] = $pages->show(3);
}
elseif($_A['query_type'] == "full"){//复审
	if(isset($_POST['id'])){
		$id = intval($_POST['id']);
		if($id<1){
			$msg = array('对不起，您的操作有误！');
		}else{
			$data['id'] = $id;
			$data['status'] = intval($_POST['status']);
			$data['success_user'] = $_G['user_id'];
			$data['success_remark'] = isset($_POST['success_remark'])?$_POST['success_remark']:'';
			$data['success_time'] = time();
			$result = $creditor->full($data);
			if($result===true){
				$msg = array('操作成功！', '', $_A['query_url'].'/fulllist'.$_A['site_url']);
			}elseif($result===false){
				$msg = array('操作失败！');
			}else{
				$msg = array($result);
			}
		}
	}elseif(isset($_GET['id'])){
		$id = intval($_GET['id']);
		if($id<1){
			$msg = array('对不起，您的操作有误！');
		}else{
			$result = $creditor->get_zqzr_list(array('id'=>$id));
			$_A['zqzr_result'] = $result['list'][0];
		}
	}else{
		$msg = array('对不起，您的操作有误！');
	}
}elseif($_A['query_type'] == "repeal"){//撤销

}
?>