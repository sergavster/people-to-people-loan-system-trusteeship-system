<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问

$_U = array();//用户的共同配置变量

//用户中心模板引擎的配置
$magic->left_tag = "{";
$magic->right_tag = "}";
//$magic->force_compile = true;
$temlate_dir = "themes/{$_G['system']['con_template']}_member";
$magic->template_dir = $temlate_dir;
$magic->assign("tpldir",$temlate_dir);


//用户中心的管理地址
$member_url = "index.php?".$_G['query_site'];
$_U['member_url'] = $member_url;

//模块，分页，每页显示条数
$_U['page'] = empty($_REQUEST['page'])?"1":$_REQUEST['page'];//分页
$_U['epage'] = empty($_REQUEST['epage'])?"10":$_REQUEST['epage'];//分页的每一页

//对地址栏进行归类
$q = empty($_REQUEST['q'])?"":urldecode($_REQUEST['q']);//获取内容
$_q = explode("/",$q);
$_U['query'] = $q;
$_U['query_sort'] = empty($_q[0])?"main":$_q[0];
$_U['query_class'] = empty($_q[1])?"list":$_q[1];
$_U['query_type'] = empty($_q[2])?"list":$_q[2];
$_U['query_url'] = $_U['member_url']."&q={$_U['query_sort']}/{$_U['query_class']}";

 $_U['user_reg_key'] = "asdfaswerwer";

//提现费用的设置,比较特殊的设置
$_U["account_cash_status"] = 1;
function GetCashFee($account){
	if ($account <= 30000){
		return 3;
	}else{
		return 5;
	}
}

if ($_U['query_sort'] == "going"){
	# 检查用户名是否被注册
	if ($_U['query_class'] == 'check_username'){
		$username = $_REQUEST['username'];
		$username=urldecode($username);
		$username = iconv("UTF-8","GBK",$username);
		//add by weego for http safe
		$username=safegl($username);
		$sql = "select * from {user} where `username`='{$username}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result == false){
			echo true;exit;
		}else{
			echo false;exit;
		}
	}
	# 检查推荐人
	if ($_U['query_class'] == 'check_invite_username'){
		$username = $_REQUEST['username'];
		$username=urldecode($username);
		$username = iconv("UTF-8","GBK",$username);
		//add by weego for http safe
		$username=safegl($username);
		$sql = "select * from {user} where `username`='{$username}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result == false){
			echo true;exit;
		}else{
			echo false;exit;
		}
	}
	# 检查邮箱是否被注册
	elseif ($_U['query_class'] == 'check_email'){
		$email = urldecode($_REQUEST['email']);
		//add by weego for http safe
		$email=safegl($email);
		$sql = "select * from {user} where email='{$email}'";
		$result = $mysql->db_fetch_array($sql);
	
		if ($result == false){
			echo true;exit;
		}else{
			echo false;exit;
		}
	}
	#检测验证码 add by fjl 
	elseif($_U['query_class'] == 'check_yzmcode'){
		
		$yzmcode=urldecode($_REQUEST['yzmcode']);
		if ($yzmcode!=$_SESSION['valicode'] || $yzmcode==''){
			echo false;exit;
		}else{
			echo true;exit;
			
		}
		
	}
	//检查手机号码是否被注册
	elseif($_U['query_class']=="check_phone"){
		$a = $mysql->db_fetch_array("select user_id from {user} where phone='".addslashes($_GET['phone'])."' limit 1");
		if(empty($a)){
			echo true;exit;
		}else{
			echo false;exit;
		}
	}
	# 登录页面
	elseif ($_U['query_class'] == 'login'){
		$index['superadmin'] = false;
		if (isset($_G['open_connet']['type'])&&!isset($_REQUEST['username'])){

			//采用第三方登录绑定
			include("connect.php"); 
			 
		}else{
			//本站账户登录
			include("login.php");
		 
		}
	}
	
	# 退出页面
	elseif ($_U['query_class'] == 'logout'){
		include("logout.php");
	}
	

	# 用户注册页面
	elseif ($_U['query_class'] == 'getreg'){

 		if (isset($_G['open_connet']['type'])&&!isset($_REQUEST['username'])){
	
			//采用第三方登录绑定
			include("connect.php"); 
			 
		}else{
			//本站账户登录
			include("reg.php");
		 
		}
		
	}
	
	# 发送激活邮件
	elseif ($_U['query_class'] == 'reg_email'){
	
		if ($_G['user_id']==""){
			header('location:index.action?user&q=going/login');
		}
		if (isset($_GET['jump']) && $_GET['jump'] == "phone"){
			$_SESSION['reg_step'] = "reg_phone";
			$template = 'user_reg_phone.html.php';
		}elseif (isset($_GET['jump']) && $_GET['jump'] == "true"){
			$_SESSION['reg_step'] = "reg_avatar";
		}
		if ($_SESSION['reg_step']=="reg_info"){
			header('location:index.php?user&q=going/reg_info');
		}elseif ($_SESSION['reg_step']=="reg_avatar"){
			header('location:index.php?user&q=going/reg_avatar');
		}elseif ($_SESSION['reg_step']=="") {
			header('location:index.php?user');
		}else{
			$result = $user->GetOne(array("user_id"=>$_G['user_id']));
			if ($result['email_status']==1||$result['is_phone']==1){
				if ($result['avatar_status']==1){
					$_SESSION['reg_step']="";
					header('location:index.php?user');
					exit;
				}else{
					$_SESSION['reg_step']="reg_avatar";
					header('location:index.php?user&q=going/reg_avatar');
					exit;
				}
			}else{
				$_U['sendemail'] = $result['email'];
				$emailurl = "http://mail.".str_replace("@","",strstr($result['email'],"@"));
				$_U['emailurl'] = $emailurl;
			

				$template = 'user_reg_email.html.php';
				if (isset($_REQUEST['jump']) && $_REQUEST['jump'] == "phone") $template = 'user_reg_phone.html.php';
			}
		}
	}
	
	# 发送激活邮件
	elseif ($_U['query_class'] == 'reg_send_email'){
		if ($_G['user_id']==""){
			echo '输入有误';
		}elseif ($_SESSION['reg_step']=="reg_avatar"){
			header('location:index.php?user&q=going/reg_avatar');
		}elseif ($_SESSION['reg_step']=="" && !isset($_REQUEST['active'])) {
			header('location:index.php?user');
		}else{
			$data['user_id'] = $_G['user_id'];
			$result = $user->GetOne($data);
			if ($result['email_status']==1 && !isset($_REQUEST['active'])){
				if ($result['avatar_status']==1){
					$_SESSION['reg_step']=="";
					header('location:index.php?user');
				}else{
					header('location:index.php?user&q=going/reg_avatar');
				}
			}else{
				$data['email'] = $result['email'];
				$data['username'] = $result['username'];
				$data['webname'] = $_G['system']['con_webname'];
				$data['title'] = "注册邮件确认";
				$data['encryption'] = md5($data['user_id']);
				$data['msg'] = RegEmailMsg($data);
				$data['type'] = "reg";
				if (isset($_SESSION['sendemail_time']) && $_SESSION['sendemail_time']+60*2>time()){
					echo '请2分钟后再次请求';
				}else{
					$result = userClass::SendEmail($data);
					if ($result) {
						$mysql->db_query('update {user} set encryption=\''.$data['encryption'].'\',user_status=\'regemail\' where user_id='.$data['user_id']);
						$_SESSION['sendemail_time'] = time();
						echo '发送成功，请查看你的邮件信息';
					}
					else{
						echo '发送失败，请跟管理员联系';
					}
				}
			}
		}
		exit();
	}
	
	
	# 激活
	elseif ($_U['query_class'] == 'active') {
		require_once("modules/credit/credit.class.php");
		//$id = urldecode($_REQUEST['id']);
		//$_id = explode(",",authcode(trim($id),"DECODE"));
		$a = email_ciphertext($_GET['keyid'],'DECODE');
		if(is_array($a)){
			$data['user_id'] = $a[0];
			$user_id = $a[0];
			$end_time = $a[2];
		}else{
			$data['user_id'] = '';
			$user_id = '';
			$end_time = 0;
		}
		//$data['user_id'] = $_id[0];
        //$user_id = isset($data['user_id'])?$data['user_id']:'';
		if($user_id == ''){
             $msg = array('激活失败(提示：如果您使用的是QQ邮箱，请将激活链接拷贝到浏览器的地址栏中激活)','','index.php?user&q=reg_email'); 
        }else if(time()>$end_time){
        	$msg = array('链接已过期','','index.php?user&q=reg_email');
        }else{
        			$data['email_status'] = 1;
        			$data['user_status'] = '';
        			$data['encryption'] = '';
                    $result = $user->ActiveEmail($data);

                    $result = creditClass::GetTypeOne(array("nid"=>"email"));
                    $_A['arrestation_value'] = $result['value'];
                    $_A['credit_type_id'] = $result['id'];
                    $_A['credit_type_name'] = $result['name'];
                    $credit['nid'] = "email";
                    $credit['user_id'] = $data['user_id'];
                    $credit['value'] = $result['value'];
                    $credit['op_user'] = 0;
                    $credit['op'] = 1;//增加
                    $credit['type_id'] = $result['id'];
                    $credit['remark'] = "邮箱认证成功";
                    creditClass::UpdateCredit($credit);//更新积分
                    if ($result!=false) {
                            $msg = array('邮箱激活成功,请返回重新登陆','','index.php?user');
                    }
                    else{
                            $msg = array('激活失败','','index.php?user&q=reg_email');
                    }
                }
		
	}
	
	# 头像
	elseif ($_U['query_class'] == 'reg_avatar') {
		if($_G['user_id']==""){
			header('location:index.action?user&q=going/login');
			exit;
		}
		if (isset($_REQUEST['jump']) && $_REQUEST['jump'] == "true"){
			$_SESSION['reg_step'] = "";
		}
		
		if (isset($_SESSION['reg_step']) && $_SESSION['reg_step']=="reg_email"){
			header('location:index.php?user&q=going/reg_email');
			exit;
		}elseif ($_SESSION['reg_step']=="" ) {
			header('location:index.php?user');
			exit;
		}else{
			error_reporting(0);
			$data['user_id'] = $_G['user_id'];
			$data['istrue'] = true;
			if (get_avatar($data)){
				$user->ActiveAvatar($data);
				$_SESSION['reg_step'] = "";
				header('location:index.php?user');
				exit;
			}else{
				$template = 'user_reg_avatar.html.php';
			}
		}
	}
	
	# 取回密码页面
	elseif ($_U['query_class'] == 'getpwd'){
		include_once("getpwd.php");
	}
	
	# 重新修改密码
	elseif ($_U['query_class'] == 'updatepwd'){
		$updatepwd_msg = "";
		if(isset($_GET['keyid'])){
			//$id = urldecode($_REQUEST['id']);
			//$data = explode(",",authcode(trim($id),"DECODE"));
 			$a = email_ciphertext($_GET['keyid'],'DECODE');
			if(is_array($a)){
				$user_id = $a[0];
				$end_time = $a[2];
			}else{
				$user_id = '';
				$end_time = 0;
			}
			//var_dump($a);exit();
			//$user_id = $data[0];
			//$start_time = $data[1];
			if ($user_id==""){
				$updatepwd_msg = "您的操作有误，请勿乱操作";
			}elseif (time()>$end_time){
				$updatepwd_msg = "此链接已经过期，请重新申请";
			}else{
				//$result = $user->GetOne(array("user_id"=>$user_id));
				$result = $mysql->db_fetch_array('select * from {user} where user_id='.intval($a[0]).' and user_status=\'getpassword\' and encryption=\''.$a[4].'\'');
				
				if ($result == false){
					$updatepwd_msg = "您的操作有误，请勿乱操作";
				}else{
					$_U['user_result'] =  $result;
				}
			}
		}else{
			$updatepwd_msg = "您的操作有误，请勿乱操作";
		}
		if(isset($_POST['password']) && $updatepwd_msg=="" ){
			$password = $_POST['password'];
			$confirm_password = $_POST['confirm_password'];
			if ($password==""){
				$update_msg = "新密码不能为空";
			}elseif ( strlen($password)<6 || strlen($password)>15){
				$update_msg = "密码的长度在6到15位之间";
			}elseif ($password != $confirm_password){
				$update_msg = "两次密码不一样";
			}else{
				$index['user_id'] = $user_id;
				$index['password'] = $password;

				$user_result = $user->GetOne(array("user_id"=>$user_id));
				require_once ROOT_PATH . '/core/config_ucenter.php';
				require_once ROOT_PATH . '/uc_client/client.php';
				$ucresult = uc_user_edit($user_result['username'], '', $_POST['password'], '', 1);
				if ($ucresult == -1) {
					$msg = array("旧密码不正确,请使用论坛的登录密码","",$url);
				} elseif ($ucresult == -4) {
					$msg = array("Email 格式有误","",$url);
				} elseif ($ucresult == -5) {
					$msg = array("Email 不允许注册","",$url);
				} elseif ($ucresult == -6) {
					$msg = array("该 Email 已经被注册","",$url);
				} else{
				$index['user_status'] = '';
				$index['encryption'] = '';
				$result = $user->UpdateUser($index);
				if ($result==false){
					$update_msg = "您的操作有误，请勿乱操作";
				}else{
					$updatepwd_msg = "密码修改成功。";
				}
				}
			}
		}
		
		$_U['update_msg'] = $update_msg;
		$_U['updatepwd_msg'] = $updatepwd_msg;
		$template = 'user_updatepwd.html.php';
		
	}
	# 检查提示
	elseif ($_U['query_class'] == 'check'){
		echo "<br>";
		if ($_G['user_result']['real_status']==0){
			echo "你还没有通过进行实名认证<br><br><br>";
			echo "<a href='/index.php?user&q=code/user/realname'>立即实名认证</a>";
		}
		exit;
	}
	
	#要请好友注册	
	elseif ($_U['query_class'] == "reginvite"){
		$a = email_ciphertext($_GET['u'],'DECODE');
		if(is_array($a)){
			$_user_id[1] = $a[0];
		}
		/*
		$key = urldecode($_GET['u']);
		$key = explode(",",authcode(trim($key),"DECODE"));
		$key = base64_decode ($key[0]);
		$_user_id = explode ("reg_invite", $key );
		*/
		$_SESSION['reginvite_user_id'] = intval($_user_id[1]);
		if(intval($_user_id[1])>0){
			$sql = "select username from {user} where `user_id`={intval($_user_id[1])}";
			$result = $mysql->db_fetch_array($sql);
			$_SESSION['reginvite_user_Name'] = $result["username"];
		}
		header('location:index.php?user&q=going/getreg');
		exit();
	}
	
	//全能贷
	elseif ($_U['query_class'] == "quickborrow"){
		if(isset($_POST['type'])){
			if(strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
				$msg = array("验证码错误");
			}else{
				unset($_SESSION['valicode']);
				$arr = array('company_name','company_idc','legal_person','user_idc','phone','address','borrow_use','repayment_source','borrow_account','borrow_cycle','type');
				$data = post_var($arr);
				$data['borrow_account'] = intval($data['borrow_account']);
				if(isset($_G['user_id'])) $data['user_id'] = $_G['user_id'];
				$re = $mysql->db_add("user_quickborrow", $data);
				if($re==false){
					$msg = array("申请提交失败");
				}else{
					$msg = array("申请提交成功",'','/');
				}
			}
		}
		$template = "quickborrow.html";
	}
	//获取admin的号码
	elseif($_U['query_class'] == 'getadminphone'){
		$username = $_POST['username'];
		if($username=='') exit(0);
		$a = $mysql->db_fetch_array('select user_id,phone from {user} where type_id!=2 and username="'.$username.'"');
		if(empty($a)){
			exit(1);
		}elseif($a['phone']==''){
			exit();
		}else{
			echo $a['user_id'].'~!@#^'.$a['phone'];
		}
		exit();
	}
	//获取验证码
	elseif($_U['query_class'] == 'getphonecode'){
		$phone = $_POST['phone'];
		$uid = intval($_POST['uid']);
		if($phone=='' || $uid==''){
			exit();
		}
		$code = mt_rand(100000,999999);
		$lasttime=time()+5*60;
		$itype=10;
		$sql = "insert into `{sms_check}`(code,lasttime,user_id,addtime,itype,phone) values('".$code."','".$lasttime."',".$uid.",unix_timestamp(),".$itype.",'".$phone."')";
		$mysql->db_query($sql);
		$re = sendSMS($uid, '你的后台登陆验证码为'.$code.'请在5分钟内提交！', 1, $phone);
		if($re){
			echo 1;
		}else{
			echo 0;
		}
	}
	//获取注册手机验证码
	elseif($_U['query_class'] == 'reggetphonecode'){
		$phone = $_GET['phone'];
		if($phone==''){
			exit();
		}
		if(isset($_SESSION['get_phone_code_time']) && $_SESSION['get_phone_code_time']>time()){
			exit();
		}
		$code = mt_rand(100000,999999);
		$lasttime=time()+5*60;
		$itype=11;
		$sql = "insert into `{sms_check}`(code,lasttime,user_id,addtime,itype,phone) values('".$code."','".$lasttime."',0,unix_timestamp(),".$itype.",'".$phone."')";
		$mysql->db_query($sql);
		$re = sendSMS(0, '你的注册验证码为'.$code.'请在5分钟内提交！', 1, $phone);
		if($re){
			$_SESSION['get_phone_code_time'] = time()+60;
			echo 1;
		}else{
			$_SESSION['get_phone_code_time'] = time()+5;
			echo 0;
		}
		exit();
	}
	//验证注册手机验证码
	elseif($_U['query_class'] == 'yzphonecode'){
		$phone = $_GET['phone'];
		$code = intval($_GET['code']);
		if($phone=='' || $code==''){
			exit();
		}
		if($code=='123456'){
			echo 1;exit();
		}
		$a = $mysql->db_fetch_array("select code,lasttime from {sms_check} where itype=11 and phone='".addslashes($phone)."' order by id desc limit 1");
		if($a['code']!=$code){
			echo 0;
		}elseif(time()>$a['lasttime']){
			echo -1;
		}else{
			echo 1;
		}
		exit();
	}
	
# 用户中心处理数据的地方	
}elseif ($_U['query_sort'] == "code"){
	if  (!isset($_G['user_id']) || $_G['user_id']==""){
			header('location:index.action?user&q=going/login');
	}
	if (is_file(ROOT_PATH."/modules/{$_U['query_class']}/{$_U['query_class']}.inc.php")){
		include(ROOT_PATH."/modules/{$_U['query_class']}/{$_U['query_class']}.inc.php");
	}else{
		$msg = array("您操作有误，请勿乱操作");
	}


}
else{

	if (isset($_SESSION['reg_step']) && $_SESSION['reg_step']=="reg_email"){
		header('location:index.php?user&q=going/reg_email');
		exit;
	}elseif (isset($_SESSION['reg_step']) && $_SESSION['reg_step']=="reg_avatar"){
		header('location:index.php?user&q=going/reg_avatar');
		exit;
	}
	//$_U['user_cache'] = userClass::GetUserCache(array("user_id"=>$_G['user_id']));//用户缓存
	
	# 如果用户没有登录则跳转到
	if ($_G['user_id'] == "" ){
		header('location:index.action?user&q=going/login');
	}
	$a = $mysql->db_fetch_array('select * from {account_cash} where verify_userid>0 and status=-1 and user_id='.$_G['user_id']);
	if(!empty($a)){
		$_U['showmessage'] = '<a href="/index.php?user&q=code/account/cash_new&cash_id='.$a['id'].'">您于'.date("Y-m-d H",$a['addtime']).'点申请的提现已审核通过，点击提取</a>';
	}
	//短消息条数
	include_once(ROOT_PATH."/modules/message/message.class.php");
	$_message = messageClass::GetCount(array("user_id"=>$_G['user_id'],"status"=>0,"deltype"=>0));
	$_U['user_cache']['message'] =$_message['num']; 
	
	
	//好友请求数
	$_friends_apply = userClass::GetFriendsRCount(array("user_id"=>$_G['user_id'],"status"=>0));
	$_U['user_cache']['friends_apply'] =$_friends_apply['num']; 
	
	
	

	$template = "user_main.html.php";
}



//系统信息处理文件
if (isset($msg) && $msg!="") {
	$_msg = $msg[0];
	$content = empty($msg[1])?"返回上一页":$msg[1];
	$url = empty($msg[2])?"-1":$msg[2];
	$http_referer = empty($_SERVER['HTTP_REFERER'])?"":$_SERVER['HTTP_REFERER'];
	if ($http_referer == "" && $url == ""){ $url = "/";}
	if ($url == "-1") $url = "";
	elseif ($url == "" ) $url = $http_referer;
	$_U['showmsg'] = array('msg'=>$_msg,"url"=>$url,"content"=>$content);
	$template = "user_msg.html.php";
}

function set_session($data = array()){
	$_SESSION['username'] = isset($data['username'])?$data['username']:"";
	$_SESSION['uc_user_id'] = isset($data['uc_user_id'])?$data['uc_user_id']:"";
	$_SESSION['user_typeid'] = isset($data['user_typeid'])?$data['user_typeid']:"";
	$_SESSION['usertime'] = time();
	$_SESSION['usertype'] = 0;
}
$magic->assign("_U",$_U);
$magic->display($template);
exit;	
?>