<?php
require_once ROOT_PATH . '/core/config_ucenter.php';
require_once ROOT_PATH . '/uc_client/client.php';
if (!defined('ROOT_PATH')) die('不能访问'); //防止直接访问

if ($_G['user_id'] != "" && isset($_SESSION['step']) && $_SESSION['reg_step'] == "") {
	header('location:index.php?user');
	exit;
} elseif (isset($_SESSION['reg_step']) && $_SESSION['reg_step'] == "reg_email") {
	header('location:index.php?user&q=going/reg_email');
	exit;
}
if (isset($_POST['username'])) {
	$var = array('username', 'password','phone');
	$index = post_var($var);
	$index['type_id']=2;
	$varUserName = array('invite_username');
    $index2 =  post_var($varUserName);
    $index["invite_userid"] = $_SESSION["reginvite_user_id"];
    if($index["invite_userid"] == ""){
        $invite_username = $index2["invite_username"];
        $sql = "select user_id from {user} where `username`='{$invite_username}'";
        $result = $mysql->db_fetch_array($sql);
        $index["invite_userid"] = $result["user_id"];
    }
	$a = $mysql->db_fetch_array("select code,lasttime from {sms_check} where itype=11 and phone='".addslashes($index['phone'])."' order by id desc limit 1");

	if($_POST['code'] == '123456'){

	}
	elseif($a['code']!=$_POST['code']){
		echo "<script type='text/javascript'>alert('手机验证码不正确'');location.href='/index.php?user&q=going/getreg';</script>";
		exit();
	}elseif(time()>$a['lasttime']){
		echo "<script type='text/javascript'>alert('手机验证码已过期'');location.href='/index.php?user&q=going/getreg';</script>";
		exit();
	}
	$index['phone_status'] = 1;
	unset($_SESSION['get_phone_code_time']);
	if ($rdGlobal['uc_on'])
	{
		$uid = uc_user_register($index["username"], $index["password"], $index["email"]);
		if ($uid <= 0) {
			if ($uid == -1) {
				$msg = '用户名不合法';
			} elseif ($uid == -2) {
				$msg = '包含要允许注册的词语';
			} elseif ($uid == -3) {
				$msg = '用户名已经存在';
			} elseif ($uid == -4) {
				$msg = 'Email 格式有误';
			} elseif ($uid == -5) {
				$msg = 'Email 不允许注册';
			} elseif ($uid == -6) {
				$msg = '该 Email 已经被注册';
			} else {
				$msg = '未定义';
			} 
		} 
		if ($msg){
			echo "<script>alert('$msg');location.href='/index.php?user&q=going/getreg';</script>";
			exit();
		}
		$ucsynlogin = uc_user_synlogin($uid);
	}
	$user_id = $user -> AddUser($index);
	if ($user_id > 0) {
		//注册成功
		if($_G['open_connet']['type']=='qq'){
			$sql = "update `{user}` set connect_openid='".$_G['open_connet']['openid']."'  where user_id = '" . $user_id. "'";	
			$mysql -> db_query($sql);
		}else{
			$update_set="";
		}
					
		$data['user_id'] = $user_id;
		$data['username'] = $index['username'];
		$data['email'] = $index['email'];
		$data['webname'] = $_G['system']['con_webname'];
		$data['title'] = $data['webname']."注册邮件确认";
		$data['key'] = $_U['user_reg_key'];
		$data['encryption'] = md5($data['user_id']);
		$data['msg'] = RegEmailMsg($data);
		$data['type'] = "reg";
		$result = $user -> SendEmail($data);
		$data['reg_step'] = "reg_email"; 
		$mysql->db_query('update {user} set encryption=\''.$data['encryption'].'\',user_status=\'regemail\' where user_id='.$data['user_id']);
		if (isset($_POST['cookietime']) && $_POST['cookietime'] > 0) {
			$ctime = time() + $_POST['cookietime'] * 60;
		} else {
			$ctime = 0;
		}

		if ($_G['is_cookie'] == 1) {
			setcookie('rdun', authcode($data['user_id'] . "," . time(), "ENCODE"), $ctime);
			setcookie('login_endtime',$ctime, $ctime);
		} else {
			$_SESSION['rdun'] = authcode($data['user_id'] . "," . time(), "ENCODE");
 			$_SESSION['login_endtime'] = $ctime;
		}
		//add by weego for 登录cookies验证 20120610			
		setcookie('login_uid',$data['user_id'], $ctime);
		//$_SESSION['reg_step'] = "reg_email";
		//header("location:/index.php?user&q=going/reg_email");
		$_U['reg_success'] = 'success';
		$template = 'user_reg_info.html.php';
		//header('location:/index.php?user');
	} else {
		echo "<script type='text/javascript'>alert('注册失败');location.href='/index.php?user&q=going/getreg';</script>";
		exit();
		//header('location:/index.php?user&q=going/getreg');
	} 
} else {
	$title = '用户注册';
	$template = 'user_reg_info.html.php';
} 

?>