<?
if (!defined('ROOT_PATH')) die('不能访问');//防止直接访问
if(isset($_POST['email'])){
	$getpwd_msg = "";
	$var = array("email","username","valicode");
	$data = post_var($var);
	if ($data['email']==""){
		$getpwd_msg = "邮箱地址不能为空";
	}elseif ($data['username']==""){
		$getpwd_msg = "用户名不能为空";
	}elseif ($data['valicode']==""){
		$getpwd_msg = "验证码不能为空";
	}elseif (strtolower($data['valicode'])!=$_SESSION['valicode']){
		$getpwd_msg = "验证码不正确";
	}else{
		unset($_SESSION['valicode']);
		$result = $user->GetOne($data);
		if ($result==false){
			$getpwd_msg = "邮箱，用户名对应不正确";
		}else{
			$data['user_id'] = $result['user_id'];
			$data['email'] = $result['email'];
			$data['username'] = $result['username'];
			$data['webname'] = $_G['system']['con_webname'];
			$data['title'] = "用户取回密码";
			$data['encryption'] = md5($data['user_id']);
			$data['msg'] = GetpwdMsg($data);
			$data['type'] = "reg";
			if (isset($_SESSION['sendemail_time']) && $_SESSION['sendemail_time']+60*2>time()){
				$getpwd_msg =  "请2分钟后再次请求。";
			}else{
				$result = userClass::SendEmail($data);
				if ($result) {
					$mysql->db_query('update {user} set encryption=\''.$data['encryption'].'\',user_status=\'getpassword\' where user_id='.$data['user_id']);
					$_SESSION['sendemail_time'] = time();
					$getpwd_msg =  "信息已发送到{$data['email']}，请注意查收您邮箱的邮件";
					echo "<script>alert('{$getpwd_msg}');location.href='/'</script>";
				}
				else{
					$getpwd_msg =  "发送失败，请跟管理员联系";
				}
			}
		}
	}
	$_U['getpwd_msg'] = $getpwd_msg;
}
else if(isset($_POST['phonecode'])){
	$array = array('username','phone','password','password1','phonecode');
	$data = post_var($array);
	if($data['password']!=$data['password1']){
		$getpwd_msg = "两次密码不一致";
	}else if($data['username']==''){
		$getpwd_msg = "用户名不能为空";
	}else if($data['phone']==''){
		$getpwd_msg = "手机号码不能为空";
	}else{
		$sql = 'select user_id,phone,phone_status from {user} where username=\''.$data['username'].'\'';
		$re = $mysql->db_fetch_array($sql);
		
		if($re==false){
			$getpwd_msg = "用户不存在";
		}else{
			$user_id = $re['user_id'];
			$sql="select id,code,lasttime from `{sms_check}` where user_id={$user_id} and phone='{$data['phone']}' and isuse=0 and itype=4 order by id desc";
			$re = $mysql->db_fetch_array($sql);
			if(!isset($re['code'])){
				$getpwd_msg = "手机号码不正确";
			}else{
				if($re['lasttime']<time()){
					$getpwd_msg = "手机验证码已过期，请重新获取";
				}elseif($re['code']!=$data['phonecode']){
					$getpwd_msg = "验证码输入有误";
				}else{
					$user_result = $user->GetOne(array("user_id"=>$user_id));
					require_once ROOT_PATH . '/core/config_ucenter.php';
					require_once ROOT_PATH . '/uc_client/client.php';
					$ucresult = uc_user_edit($user_result['username'], '', $_POST['password'], '', 1);
					if ($ucresult == -1) {
						$msg = array("旧密码不正确,请使用论坛的登录密码","",$url);
					} elseif ($ucresult == -4) {
						$msg = array("Email 格式有误");
					} elseif ($ucresult == -5) {
						$msg = array("Email 不允许注册");
					} elseif ($ucresult == -6) {
						$msg = array("该 Email 已经被注册");
					} else{
						$mysql->db_query("update `{sms_check}` set isuse=1 where id={$re['id']}");
						$password = md5($data['password']);
						$result = $mysql->db_query("update `{user}` set password='{$password}' where user_id={$user_id} and username='{$data['username']}' limit 1");
						if($result){
							echo "<script>alert('密码修改成功')</script>";
							echo "<script>location.href='/index.action?user&q=going/login'</script>";
							exit();
						}else{
							$getpwd_msg = "密码修改失败";
						}
					}
				}
			}
		}
	}
	$_U['getpwd_msg'] = $getpwd_msg;
}
else if(isset($_GET['itype']) && $_GET['itype']==4){
	$phone = $_GET['phone'];
	$username = $_GET['username'];
	if($username=='') exit();
	$sql = 'select user_id,phone,phone_status from {user} where username=\''.$username.'\'';
	$re = $mysql->db_fetch_array($sql);
	if($re==false){
		echo 2;exit();//用户不存在
	}
	if($re['phone_status']!=1){
		echo -1;exit();//未手机认证
	}
	if($re['phone']!=$phone){
		echo 3;exit();//输入的手机号码与认证的不符合
	}
	$randnum=rand(100000,999999);
	$lasttime=time()+5*60;
	$addtime=time();
	$user_id=$re['user_id'];
	$sql = "insert into `{sms_check}`(code,lasttime,user_id,addtime,itype,phone) values('".$randnum."','".$lasttime."',".$user_id.",'".$addtime."',4,'".$phone."')";
	$mysql->db_query($sql);
	$re = sendSMS($re["user_id"],"你的密码重置验证码是：".$randnum."请在5分钟内提交！",1,$phone);
	if($re==1){
		echo 1;//发送成功
	}else{
		echo 0;//发送失败
	}
	exit();
}
$title = '取回密码';
$template = 'user_getpwd.html.php';
?>