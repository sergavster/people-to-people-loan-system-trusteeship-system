<?php
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
check_rank("creditor_".$_A['query_type']);//���Ȩ��
include_once("creditor.class.php");
$creditor = new creditorClass();
$_A['list_purview'] =  array("credit"=>array("ծȨת��"=>array("creditor_list"=>"ծȨת���б�","creditor_view"=>"����ծȨת��","creditor_full"=>"����ծȨת��","creditor_repeal"=>"����ծȨת��")));//Ȩ��
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['admin_url']}&q=module/creditor/list{$_A['site_url']}'>ծȨת���б�</a>";
if ($_A['query_type'] == "list"){//�б�
	if(!isset($_GET['status'])) $_GET['status']='0,1,3';
	$result = $creditor->get_zqzr_list(array('status'=>$_GET['status']));
	$pages->set_data($result);
	$_A['zqzr_list'] = $result['list'];
	$_A['showpage'] = $pages->show(3);
}elseif($_A['query_type'] == "view"){//����
	if(isset($_POST['id'])){
		$var = array("id","status","verify_remark");
		$data = post_var($var);
		$data['verify_user'] = $_G['user_id'];
		$data['verify_time'] = time();
		if($data['status']==1){
			$re = $creditor->websg($data);//��վֱ���չ�ծȨ
		}
		//$re = $creditor->verify_creditor($data);
		if($re===true){
			$msg = array('����ɹ���','' ,$_A['query_url'].'/list&status=1'.$_A['site_url']);
		}else{
			$msg = array('����ʧ�ܣ�');
		}
	}else{
		$id = (int)$_GET['id'];
		if($id<1){
			$msg = array('�Բ������Ĳ�������');
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
elseif($_A['query_type'] == "full"){//����
	if(isset($_POST['id'])){
		$id = intval($_POST['id']);
		if($id<1){
			$msg = array('�Բ������Ĳ�������');
		}else{
			$data['id'] = $id;
			$data['status'] = intval($_POST['status']);
			$data['success_user'] = $_G['user_id'];
			$data['success_remark'] = isset($_POST['success_remark'])?$_POST['success_remark']:'';
			$data['success_time'] = time();
			$result = $creditor->full($data);
			if($result===true){
				$msg = array('�����ɹ���', '', $_A['query_url'].'/fulllist'.$_A['site_url']);
			}elseif($result===false){
				$msg = array('����ʧ�ܣ�');
			}else{
				$msg = array($result);
			}
		}
	}elseif(isset($_GET['id'])){
		$id = intval($_GET['id']);
		if($id<1){
			$msg = array('�Բ������Ĳ�������');
		}else{
			$result = $creditor->get_zqzr_list(array('id'=>$id));
			$_A['zqzr_result'] = $result['list'][0];
		}
	}else{
		$msg = array('�Բ������Ĳ�������');
	}
}elseif($_A['query_type'] == "repeal"){//����

}
?>