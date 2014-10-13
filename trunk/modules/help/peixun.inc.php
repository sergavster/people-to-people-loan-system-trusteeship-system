<?

if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
require_once 'peixun.class.php';

if ($_U['query_type']=="list"){
	$data['user_id']   = $_U['user_id'];
	$data['page']      = $_U['page'];
	$data['epage'] = $_U['epage'];
	$data['type'] = "peixun";

	$result = PeiXun::GetApplyList($data);
	
	$_U['peixun_list'] = $result['list'];
	$_U['showpage'] = fun_page($result['record_num'],$_U['page'],$_U['epage']);
	$template  = "user_train.html";
}
	
elseif ($_U['query_type'] == "apply"){
	if ($_U['user_id']==""){
		$_url = '/index.php?user&q=going/login';
		echo  "<bg><br>您还没有登录，请先登录<br /><br /><a href=$_url>登录</a><br /><br />系统将3秒后跳转<script>setTimeout('curl()',4000);function curl(){ 	location.href='$_url'}</script>";
		exit;
	}
	
	elseif ($_U['user_result']['vip_status']!=1){
		$_url = '/index.php?user&q=code/vipuser/active';
		$msg = "<bg><br>您不是vip会员，请先激活vip<br /><br /><a href=$_url>激活vip</a><br /><br />系统将3秒后跳转<script>setTimeout('curl()',4000);function curl(){ 	location.href='$_url'}</script>";
		echo $msg;
		exit;	
	}
	
	else{
		$id = $_REQUEST['id'];
		$result =  PeiXun::Apply($id,$_SESSION['user_id']);
		if ($result===true){
			echo "报名成功";
		}else{
			echo $result;
		}
		echo "";
		exit;
	}
}

elseif ($_U['query_type'] == "apply_list"){
	$result =  PeiXun::GetApply($_REQUEST['id'],"","peixun");
	$display ='';
	foreach ($result as $key => $value){
		$avatar = get_avatar(array("user_id"=>$value['user_id']));
		$display .='<li><div ><a href="/dzx/home.php?mod=space&uid='.$value['uc_user_id'].'" target=_blank><img src="'.$avatar.'" width=80 height=80 /></a></div><a href="">'.$value['realname'].'</a></li>';
	}
	echo "document.write('$display')";
	exit;

}

elseif ($_U['query_type'] == "cannel_apply"){
	$data['user_id']   = $_U['user_id'];
	$data['id']   = $_REQUEST['id'];
	$result =  PeiXun::CancelApply($data);
		if ($result !==true){
		$msg = array($result);
	}else{
		$msg = array("退订成功");
	}

}

?>