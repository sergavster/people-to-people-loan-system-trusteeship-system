<?

if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
require_once 'vipuser.class.php';
$action = isset($_REQUEST['action'])?$_REQUEST['action']:"";
if ($_U['query_type'] == "apply"){
	if ($_U['user_id']==""){
		$_url = '/index.php?user&q=action/login';
		echo  "<bg><br>您还没有登录，请先登录<br /><br /><a href=$_url>登录</a><br /><br />系统将3秒后跳转<script>setTimeout('curl()',4000);function curl(){ 	location.href='$_url'}</script>";
		exit;
	}
	
	elseif ($_U['user_result']['vip_status']!=1){
		$_url = '/index.php?user&q=vipuser';
		$msg = "<bg><br>您不是vip会员，请先激活vip<br /><br /><a href=$_url>激活vip</a><br /><br />系统将3秒后跳转<script>setTimeout('curl()',4000);function curl(){ 	location.href='$_url'}</script>";
		echo $msg;
		exit;	
	}
	
	else{
		$id = $_REQUEST['id'];
		$result =  HuoDong::Apply($id,$_SESSION['user_id']);
		if ($result===true){
			echo "报名成功";
		}else{
			echo $result;
		}
		echo "";
		exit;
	}
}
elseif ($_U['query_type'] == "active"){
	if (isset($_POST['password'])){
	$result =VipUser::AddVipUser($_U['user_result']['username'],$_POST['card_type'], $_POST['card_number'], $_POST['password']);
	if ($result == VipUser::USER_NOT_EXISTS){
		$vipmsg = "会员不存在";
	}elseif ($result == VipUser::USER_NOT_RESUME){
		$vipmsg = "会员没有简历";
	}elseif ($result == VipUser::USER_NOT_RESUME){
		$vipmsg = "会员没有简历";
	}elseif ($result == VipUser::USER_IS_VIP){
		$vipmsg = "您已经是vip，请勿重新激活";
	}elseif ($result == VipUser::CARD_NOT_EXISTS ){
		$vipmsg = "卡号不存在";
	}elseif ($result == VipUser::CARD_IS_USED   ){
		$vipmsg = "此卡已经被使用";
	}elseif ($result == VipUser::CARD_ERROR_PASSWORD ){
		$vipmsg = "卡号密码错误";
	}else{
		$vipmsg = "激活成功";
	}
	$magic->assign("vipmsg",$vipmsg);
}
$_U['vip_cardtype_list'] = VipUser::CardType();

$template = 'user_vip.html';
}
?>