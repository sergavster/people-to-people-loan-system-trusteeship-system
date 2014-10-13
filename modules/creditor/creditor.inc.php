<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
include_once("creditor.class.php");
$creditor = new creditorClass;
if($_U['query_type'] == "new_zqzr"){
	$borrow_id = isset($_GET['borrow_id'])?intval($_GET['borrow_id']):0;
	$tender_id = isset($_GET['tender_id'])?intval($_GET['tender_id']):0;
	if($borrow_id<1 || $tender_id<1){
		$msg = array("对不起，您的操作有误！");
	}else{
		$result = $creditor->get_collection($borrow_id, $_G['user_id'],$tender_id);
		$_U['sy_total'] = $creditor->getsyTotal($result);
		$_U['zr_re'] = $creditor->getfs($_U['sy_total']);
		$_U['total_collection'] = $result['total_collection'];
		$_U['collection_list'] = $result['list'];
		$_U['zq_time'] = $result['zq_time'];
		$_U['is_zr'] = 0;
		$a = $creditor->get_zqzr_list(array('user_id'=>$_G['user_id'],'borrow_id'=>$borrow_id,'status'=>'0,1,3'));
		if(!empty($a['list'])) $_U['is_zr'] = 1;
	}
}
elseif ($_U['query_type'] == "add_zqzr"){
	$borrow_id = isset($_POST['borrow_id'])?intval($_POST['borrow_id']):0;
	$tender_id = isset($_POST['tender_id'])?intval($_POST['tender_id']):0;
	if($borrow_id<1 || $tender_id<1){
		$msg = array("对不起，您的操作有误！");
	}else{
		$var = array("valid_time","borrow_id","account","every_account");
		$data = post_var($var);
		$fs = $data['account']/$data['every_account'];
		$result = $creditor->get_collection($borrow_id, $_G['user_id'],$tender_id);
		$zqzr_list = $creditor->get_zqzr_list(array('user_id'=>$_G['user_id'],'borrow_id'=>$borrow_id,'status'=>'0,1,3'));
		if(!empty($zqzr_list['list'])){
			$msg = array("债权转让中或申请转让中！");
		}elseif($data['every_account']<=1){
			$msg = array("债权转让每份价格不能小于1元！");
		}elseif(!is_numeric($fs)){
			$msg = array("债权转让的价格和每份转让价格必须是倍数关系！");
		}elseif($creditor->getsyTotal($result) < $data['account']){
			$msg = array("债权转让的价格不能高于剩余价值！");
		}else{
			$data['status'] = 0;
			$data['user_id'] = $_G['user_id'];
			$data['borrow_id'] = $borrow_id;
			$data['collection_id'] = $result['collection_id'];
			$data['tender_id'] = $result['tender_id'];
			$data['y_account'] = $result['total_collection'];
			$data['y_capital'] = $result['total_capital'];
			$data['y_timelimit'] = $result['last_repay_time'];
			$data['next_repay_time'] = $result['next_repay_time'];
			$data['every_collection'] = $data['y_account']/$fs;
			$data['sy_account'] = $creditor->getsyTotal($result);
			$data['finance_account_id'] = $result['list'][0]['finance_account_id'];
			$data['lcborrow_id'] = $result['list'][0]['lcborrow_id'];
			$data['addtime'] = time();
			$re = $creditor->add($data);
			if($re===true){
				$msg = array("债权转让申请成功！",'','/index.php?user&q=code/creditor/zrz_zq');
			}else{
				$msg = array("债权转让申请失败！");
			}
		}
	}
}

/*add by fjl at 20140219 begin*/
//状态,0等待初审,1正在出售,2初审失败,5用户取消,3完成
elseif($_U['query_type'] == "zrz_zq"){
	$data['user_id'] = $_G['user_id'];
	$data['status'] = "0,1";
	$data['page'] = isset($_GET['page'])?$_GET['page']:1;
	$result = $creditor->get_zqzr_list($data);
	$_U['zqzr_list'] = $result['list'];
	$pages->set_data($result);
	$_U['showpage'] = $pages->show(3);
}
elseif($_U['query_type'] == "yzr_zq"){
	$data['user_id'] = $_G['user_id'];
	$data['status'] = 3;
	$data['page'] = isset($_GET['page'])?$_GET['page']:1;
	$result = $creditor->get_zqzr_list($data);
	$_U['zqzr_list'] = $result['list'];
	$pages->set_data($result);
	$_U['showpage'] = $pages->show(3);
}
elseif($_U['query_type'] == "yqx_zq"){
	$data['user_id'] = $_G['user_id'];
	$data['status'] = "2,5";
	$data['page'] = isset($_GET['page'])?$_GET['page']:1;
	$result = $creditor->get_zqzr_list($data);
	$_U['zqzr_list'] = $result['list'];
	$pages->set_data($result);
	$_U['showpage'] = $pages->show(3);
}

/*add by fjl at 20140219 end*/
elseif($_U['query_type'] == "sg_zq"){
	$data['user_id'] = $_G['user_id'];
	$data['status'] = "0,1";
	$result = $creditor->get_sell_list($data);
	$_U['zqzr_list'] = $result['list'];
	$pages->set_data($result);
	$_U['showpage'] = $pages->show(3);
}
elseif($_U['query_type'] == "zqzr_repeal"){
	$id = intval($_GET['id']);
	if($id<1){
		$msg = array("对不起，您的操作有误！");
	}else{
		$re = $creditor->repeal($id);
		if($re===true){
			$msg = array("债权转让撤销成功！",'',$_U['query_url'].'/zrz_zq');
		}else{
			$msg = array("债权转让撤销失败！",'',$_U['query_url'].'/zrz_zq');
		}
	}
}
elseif($_U['query_type'] == "tender"){//投标
	if(isset($_POST['id'])){
		$fs = intval($_POST['gm_fs']);
		if(strtolower($_POST['valicode'])!=$_SESSION['valicode']){
			$msg = array("验证码输入有误！");
		}elseif(md5($_POST['paypassword'])!=$_G['user_result']['paypassword']){
			$msg = array("支付交易密码不正确");
		}elseif($fs!=$_POST['gm_fs']){
			$msg = array("购买份数必须为整数");
		}else{
			$re = $creditor->AddTender(array('id'=>$_POST['id'],'gm_fs'=>$_POST['gm_fs'],'user_id'=>$_G['user_id']));
			if($re===true){
				$msg = array("债权购买成功！",'','/index.php?user&q=code/creditor/sg_zq');
			}elseif($re===false){
				$msg = array("债权购买失败！");
			}else{
				$msg = array($re);
			}
		}
	}else{
		$msg = array("对不起，您的操作有误！");
	}
}
$template = "user_creditor.html.php";
?>