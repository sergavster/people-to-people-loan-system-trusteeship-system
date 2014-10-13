<?php
if (!defined('ROOT_PATH')) die('不能访问'); //防止直接访问
require_once ROOT_PATH . '/core/config_ucenter.php';
require_once ROOT_PATH . '/uc_client/client.php';

if (isset($_POST['password'])) {
	$login_msg = "";
	if($_POST['username'] == "") {
		$msg = array("账号不能为空", "", "?user&q=going/login");
	} elseif ($_POST['password'] == "") {
		$msg = array("密码不能为空", "", "?user&q=going/login");
	}elseif(is_email($_POST['username'])){
		$msg = array("请使用-用户名-登录", "", "?user&q=going/login");
	}elseif(1==2 && ($_POST['valicode']=='' || $_POST['valicode']!=$_SESSION['valicode'])){
		$msg = array("验证码输入有误", "", "?user&q=going/login");
	}
	else {
		unset($_SESSION['valicode']);
		//判断是否是管理员，管理员不允许登录
		$userInfo = $user->GetOne(array('username'=>$_POST['username']));
		$user_type = $user->GetTypeList(array("type"=>1));
		$admin_types = array();
		foreach ($user_type as $value){
			$admin_types[] = $value['type_id'];
		}
		//设置为管理员禁止登录且用户存在且用户类型是管理员则不能登录
		if ($rdGlobal['admin_login_forbid'] && isset($userInfo['type_id']) && in_array($userInfo['type_id'], $admin_types)){
			$msg = array("管理员帐号不能登录前台", "", "?user&q=going/login");
		}else{
		
		if (!isset($index['user_id']) || $index['user_id'] == "") {
			$index['user_id'] = $_POST['username'];
		} 
		$index['email'] = $_POST['username'];
		$index['username'] = $_POST['username'];
		$index['password'] = $_POST['password'];
		$index['openid'] = $_POST['openid']; //qq共享登录 提供openid
		if ($rdGlobal['uc_on']&&empty($index['openid']))
		{
			list($uid, $ucusername, $ucpassword, $email) = uc_user_login($_REQUEST['username'], $_REQUEST['password']);
			if ($uid > 0) {
				//$ucsynlogin = uc_user_synlogin($uid);
				$sql = "select * from `{user}` where username = '" . $ucusername . "'";
				$result = $mysql -> db_fetch_array($sql);
				/***
				 * 登陆时验证动态口令
				 * by:timest 2012-07-26 奥运之夜 
				 */
				if(isset($result['serial_id']) && $result['serial_id']!='' ){
					$is_used_uchon = json_decode($result['serial_status'])->{"login"} == '1' ? 1 : 0;
					if($is_used_uchon == 1 && (!isset($_POST['uchoncode']) || $_POST['uchoncode'] == '') ){
						$msg = array("请输入动态密码", "", "?user&q=going/login&errror=" . $uid);
						return ;
					}
					if( $is_used_uchon == 1 && otp_check($result['serial_id'], $_REQUEST['uchoncode'])!='200'){
						$msg = array("动态密码有误,请重试！", "", "?user&q=going/login&errror=" . $uid);
						return ;
					}
				}
				if (is_array($result)) {
					if($_G['open_connet']['type']=='qq'){
						$update_set="connect_openid='".$_G['open_connet']['openid']."',";
					}else{
						$update_set="";
					}
					$sql = "update `{user}` set ".$update_set." password='" . md5($_POST['password']) . "'  where user_id = {$result['user_id']}";
				
					$mysql -> db_query($sql);
				} else {
					list($uid, $username, $email) = uc_get_user($uid, 1);
					$var = array('email', 'username', 'sex', 'password', 'email', 'realname', 'invite_userid', 'type_id', 'phone', 'area', 'qq', 'card_type', 'card_id');
					$index = post_var($var);
					$index["type_id"] = 2;
					$user_id = $user -> AddUser($index);

					if ($user_id > 0) {
						$data['user_id'] = $user_id;
						$data['username'] = $index['username'];
						$data['email'] = $index['email'];
						$data['webname'] = $_G['system']['con_webname'];
						$data['title'] = $_G['system']['con_webname']."注册邮件确认";
						$data['key'] = $_U['user_reg_key'];
						$data['msg'] = RegEmailMsg($data);
						$data['type'] = "reg";
						$result = $user -> SendEmail($data);
						$data['reg_step'] = "reg_email"; 
						 
				
						
						$_SESSION['reg_step'] = "reg_email";
					} 
				} 

				$result = $user -> Login($index);
				//add by weego 20120625 for 账户锁定功能
				//var_dump($result);
				if($result['islock']==1){
					$_url = '/';
					$msg = array("该账户已经被锁定","返回>>", $_url);			
				}elseif($result['status']==0){
					$_url = '/';
					$msg = array("该账户已经被关闭","返回>>", $_url);		
				}else{
					$data['username'] = $result['username'];
					$data['user_id'] = $result['user_id'];
					$data['user_typeid'] = $result['type_id'];
								if ($result['email_status']==1){
						$data['reg_step'] = "reg_email";
						$_url = 'index.php?user&q=going/reg_email';
					}else{
						$data['reg_step'] = "";
						$_url = 'index.action?user';
					}
					set_session($data); //注册session
					if (isset($_REQUEST['cookietime']) && $_REQUEST['cookietime'] > 0) {
						$ctime = time() + $_REQUEST['cookietime'];
					} else {
						$ctime = time() + 60 * 60 * 24;
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

					$areaLoginMsg=$result['areaLoginMsg'];
					$msg = array($ucsynlogin."登录成功<br/><font style=color:red>请确认您的上一次登录时间</font><br/><font style=color:red>{$areaLoginMsg}</font>","跳过进入用户中心>>", $_url);
					}
			} elseif ($uid == -1) {
				$result = $user -> Login($index);
				
				/***
				 * 登陆时验证动态口令
				 * by:timest 2012-07-26 奥运之夜 
				 */
				if(isset($result['serial_id']) && $result['serial_id']!='' ){
					$is_used_uchon = json_decode($result['serial_status'])->{"login"} == '1' ? 1 : 0;
					if($is_used_uchon == 1 && (!isset($_POST['uchoncode']) || $_POST['uchoncode'] == '') ){
						$msg = array("请输入动态密码", "", "?user&q=going/login&errror=" . $uid);
						return ;
					}
					if( $is_used_uchon == 1 && otp_check($result['serial_id'], $_POST['uchoncode'])!='200'){
						$msg = array("动态密码有误,请重试！", "", "?user&q=going/login&errror=" . $uid);
						return ;
					}
				}
				if (is_array($result)) {
				$ucsynlogin="";
				if ($rdGlobal['uc_on'])
				{
					$uid = uc_user_register($_POST['username'], $_POST['password'], $result['email']);
					if ($uid > 0) {
						$ucsynlogin = uc_user_synlogin($uid);
					} 
				}
				$data['username'] = $result['username'];
				$data['user_id'] = $result['user_id'];
				$data['user_typeid'] = $result['type_id'];
				if ($result['email_status']==1){
						$data['reg_step'] = "reg_email";
						$_url = 'index.php?user&q=going/reg_email';
					}else{
						$data['reg_step'] = "";
						$_url = 'index.action?user';
					}
				set_session($data); //注册session
				if (isset($_REQUEST['cookietime']) && $_REQUEST['cookietime'] > 0) {
					$ctime = time() + $_REQUEST['cookietime'] ;
				} else {
					$ctime = time() + 60 * 60 * 24;
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
				
				if ($result['email_status'] == 1 ){
					echo "<script>location.href='index.php?user';</script>";
				}
				else{
					echo "<script>location.href='index.php?user&q=going/reg_email';</script>";
				}
 				$msg = array("系统登录成功,系统将3秒后跳转" . $ucsynlogin, "进入用户中心", $_url);
			} else {
				$msg = array("用户不存在或密码错误", "", "?user&q=going/login&errror=" . $uid);
			} 
		} elseif ($uid == -2) {
			$msg = array("密码错误", "", "?user&q=going/login&errror=" . $uid);
		} else {
				$msg = array("未定义错误", "", "?user&q=going/login&errror=" . $uid);
			} 
	}else{
			$result = $user -> Login($index);
			if(!is_array($result)){
				$msg = array("用户不存在或密码错误", "", "?user&q=going/login&errror=" . $uid);
			}elseif($result['islock']==1){
				$_url = '/';
				$msg = array("该账户已经被锁定","返回>>", $_url);			
			}elseif($result['status']==0){
				$_url = '/';
				$msg = array("该账户已经被关闭","返回>>", $_url);		
			}else{
			if (is_array($result)) {
				if($_G['open_connet']['type']='qq'){
					$sql = "update `{user}` set connect_openid='" .$_G['open_connet']['openid']. "'  where user_id = {$result['user_id']}";
					$mysql -> db_query($sql);
				}
					
				$ucsynlogin="";
				if ($rdGlobal['uc_on'])
				{
					$uid = uc_user_register($_POST['username'], $_POST['password'], $result['email']);
					if ($uid > 0) {
						$ucsynlogin = uc_user_synlogin($uid);
					} 
				}
				$data['username'] = $result['username'];
				$data['user_id'] = $result['user_id'];
				$data['user_typeid'] = $result['type_id'];
					if ($result['email_status'] == 1 ){
						$data['reg_step'] = "";
 						$_url = 'index.action?user';
					}else{
 						$data['reg_step'] = "reg_email";
 					}
						$_url = 'index.php?user&q=going/reg_email';
				set_session($data); //注册session
				if (isset($_REQUEST['cookietime']) && $_REQUEST['cookietime'] > 0) {
					$ctime = time() + $_REQUEST['cookietime'] ;
				} else {
					$ctime = time() + 60 * 60 * 24;
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
				

					$areaLoginMsg=$result['areaLoginMsg'];
					$msg = array($ucsynlogin."登录成功<br/><font style=color:red>请确认您的上一次登录时间</font><br/><font style=color:red>{$areaLoginMsg}</font>","跳过进入用户中心>>", $_url);

				//跳转到先前查看的标
				if(isset($_GET['redirectURL']) && $_GET['redirectURL']!=''){
					//Header("location:/{$_GET['redirectURL']}");exit();
				}
			} else {
				$msg = array("用户不存在或密码错误", "", "?user&q=going/login&errror=" . $uid);
			}
			}
		}
		}
	} 
	$_U['login_msg'] = $login_msg;
}
$title = '用户登录';
$template = 'user_login.html.php';

?>