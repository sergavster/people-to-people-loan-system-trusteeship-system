<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问

$url = $_U['query_url']."/{$_U['query_type']}";
if (isset($_G['query_string'][2])){
	$url .= "&".$_G['query_string'][2];
}
//if  ($_U['query_type'] == "applyvip") $url = '/vip/index.html';
$_SESSION['valicode'] = isset($_POST['valicode'])?$_SESSION['valicode']:'';
if(strtolower($_POST['valicode']) != $_SESSION['valicode']){
		$msg = array("验证码错误","",$url);
}else{
	$_SESSION['valicode'] = '';
	//密码保护功能
	if ($_U['query_type'] == "protection"){
		/*if ((isset($_POST['type']) && $_POST['type'] == 1)){
			if (  $_G['user_result']['answer']=="" || $_POST['answer'] == $_G['user_result']['answer']){
				$_U['answer_type'] = 2;
			}else{
				$msg = array("问题答案不正确","",$url);
			}
		}elseif (isset($_POST['type']) && $_POST['type'] == 2){
			$var = array("question","answer");
			$data = post_var($var);
			if ($data['answer']==""){
				$msg = array("问题答案不能为空","",$url);	
			}else{
				$data['user_id'] = $_G['user_id'];
				$result = $user->UpdateUserProtection($data);
				if ($result == false){
					$msg = array($result);	
				}else{
					$msg = array("密码保护修改成功","",$url);	
				}
			}
		}*/
	}
	//交易密码设置
	elseif ($_U['query_type'] == "paypwd"){
		if (isset($_POST['oldpassword'])){
			if ($_G['user_result']['paypassword'] == "" && md5($_POST['oldpassword']) !=$_G['user_result']['password']){
				$msg = array("密码不正确，请输入您的登录密码","",$url);	
			}elseif ($_G['user_result']['paypassword'] != "" && md5($_POST['oldpassword']) != $_G['user_result']['paypassword']){
				$msg = array("密码不正确，请输入您的旧交易密码","",$url);	
			}elseif($_POST['newpassword'] != $_POST['newpassword1']){
				$msg = array("两次交易密码输入不一致","",$url);
			}
			else{
				$data['user_id'] = $_G['user_id'];
				$data['paypassword'] = md5($_POST['newpassword']);
				$result = $user->UpdateUserAll($data);
				if ($result == false){
					$msg = array($result);
				}else{
					if(isset($_COOKIE['referer_url'])){
						$msg = array("密码修改成功","立即前往投标",$_COOKIE['referer_url']);
						setcookie('referer_url', $path, time()-1);
					}else{
						$msg = array("密码修改成功","",$url);
					}
				}
			}
		}else{
			$referer = parse_url($_SERVER['HTTP_REFERER']);
			$path = $referer['path'];
			if(preg_match("/^\/invest\/a[0-9]+\.html$/", $path)){
				setcookie('referer_url', $path, time()+36000);
			}
		}
	}
	//短信定制设置
	elseif ($_U['query_type'] == "smsorder"){
		/*
		$results = userClass::GetOnes(array("user_id"=>$_G['user_id']));
		$results_type = userClass::GetAppOnes(array("appid"=>1));
		if (isset($_POST['ordertype'])){
			$ordertype=$_POST['ordertype'];
			$ar_tmp = explode("_", $ordertype); 
			$data["money"]=$ar_tmp[1]?$ar_tmp[1]:0;
			$data["start_time"]=date("Y-m-d");
			$data["userid"]=$_G['user_id'];
			$data["apptypeid"]=1;
			$data["mobile"]=$results["phone"];
			if(!$results["phone"] || strlen($results["phone"])<11 || strlen($results["phone"])>13)
			{
				$msg = array("你未填写过手机号码，或者手机号码有错误！","",$url);	
			}
			else
			{
				if ($ar_tmp[0]==1)
				{
					$data["end_time"]=date( "Y-m-d", mktime(0,0,0,date("m")+1,date("d"),date("Y")));
				}
				elseif ($ar_tmp[0]==2)
				{
					$data["end_time"]=date( "Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")+1));
				}
				$_results = userClass::GetSmsOnes(array("userid"=>$_G['user_id']));			
				if ($_results)
				{
					$time1=strtotime($_results["end_time"]);
					$time2=time();
					if (DateDiff("d",$time2,$time1)>0)
					{
						$msg = array("你已经订购短信提醒功能，不能重复订购！","",$url);	
					}
					else
					{
						 userClass::UpdateSmsUser($data);

					}
				}
				else
				{
					userClass::AddSmsUser($data);
				}
			}

			
		}
			$results_sms = userClass::GetSmsOnes(array("userid"=>$_G['user_id']));
			if($results_sms)
			{
				$results_sms['start_time']=date("Y-m-d",strtotime($results_sms['start_time']));
				$results_sms['end_time']=date("Y-m-d",strtotime($results_sms['end_time']));
			}
			else
			{
				$results_sms['start_time']=date("Y-m-d");
				$results_sms['end_time']=date("Y-m-d");
			}

			$_U['smsuser'] = $results_sms;
			$_U['apptype'] = $results_type;
			*/
	}
	//交易密码设置
	elseif ($_U['query_type'] == "getpaypwd"){
		if(isset($_REQUEST['keyid']) && $_REQUEST['keyid']!=""){
			
			//$id = urldecode($_REQUEST['keyid']);
			//$_id = explode(",",email_ciphertext(trim($id),"DECODE"));
			$_id = email_ciphertext($_REQUEST['keyid'],'DECODE');
			$data['user_id'] = $_id[0];
			if ($_id[2]+60*60<time()){
				$msg = array("信息已过期，请重新申请。");
			}elseif ($data['user_id']!=$_G['user_id']){
				$msg = array("此信息不是你的信息，请不要乱操作");
			}else{
				if (isset($_POST['paypwd']) && $_POST['paypwd']!=""){
					if ($_POST['paypwd']==""){
						$msg = array("密码不能为空","",$url);
					}elseif ($_POST['paypwd']!=$_POST['paypwd1']){
						$msg = array("两次密码不一样","",$url);
					}else{
						$data['user_id'] = $_G['user_id'];
						$data['paypassword'] = md5($_POST['paypwd']);
						$result = $user->UpdateUser($data);
						$msg = array("交易密码修改成功","","index.php?user&q=code/user/paypwd");
					}
				}
			}
		}elseif (isset($_POST['valicode'])){
			$data['user_id'] = $_G['user_id'];
			$data['username'] = $_G['user_result']['username'];
			$data['email'] = $_G['user_result']['email'];
			$data['webname'] = $_G['system']['con_webname'];
			$data['title'] = "交易密码取回";
			$data['key'] = "getPayPwd";
			$data['query_url'] = "code/user/getpaypwd";
			$data['encryption'] = md5($data['user_id']);
			$data['msg'] = getPaypwEmailMsg($data);
			$data['type'] = "getpaypwd";
			$result = $user->SendEmail($data);
			$msg = array("信息已发送到您的邮箱，请注意查收");
		}
	}
	
	//登录密码设置
	elseif ($_U['query_type'] == "userpwd"){
		if (isset($_POST['oldpassword'])){
			if (md5($_POST['oldpassword']) != $_G['user_result']['password']){
				$msg = array("密码不正确，请输入您的旧密码","",$url);
			}elseif($_POST['newpassword'] != $_POST['newpassword1']){
				$msg = array("两次密码输入不一致","",$url);
			}
			else{
				require_once ROOT_PATH . '/core/config_ucenter.php';
				require_once ROOT_PATH . '/uc_client/client.php';
				$ucresult = uc_user_edit($_G['user_result']['username'], $_POST['oldpassword'], $_POST['newpassword']);
			
                 if ($ucresult == -1) {
					$msg = array("旧密码不正确,请使用论坛的登录密码","",$url);
				} elseif ($ucresult == -4) {
					$msg = array("Email 格式有误","",$url);
				} elseif ($ucresult == -5) {
					$msg = array("Email 不允许注册","",$url);
				} elseif ($ucresult == -6) {
					$msg = array("该 Email 已经被注册","",$url);
				} else{
					$data['user_id'] = $_G['user_id'];
					$data['password'] = $_POST['newpassword'];
					$result = $user->UpdateUser($data);
					if ($result == false){
						$msg = array($result);	
					}else{
						$msg = array("登录密码修改成功","",$url);	
					}
				}
			}
		}
	}
	
	//动态口令设置
	elseif ($_U['query_type'] == "serialStatusSet"){
	}
	
	//设置隐私
	/*
	elseif ($_U['query_type'] == "privacy"){
		if (isset($_POST['friend'])){	
			$var = array("friend","friend_comment","borrow_list","loan_log","sent_msg","friend_request","look_black","allow_black_sent","allow_black_request");
			$_result = post_var($var);
			$data['privacy'] = serialize($_result);
			$data['user_id'] = $_G['user_id'];
			$result = $user->UpdateUserAll($data);
			if ($result == false){
				$msg = array($result);	
			}else{
				$msg = array("隐私设置成功","",$url);	
			}
			
		}else{
			$result = unserialize($_G['user_result']['privacy']);
			$_U['user_privacy'] = $result;
		}
	}
	*/
	//实名认证
	elseif ($_U['query_type'] == "realname"){
		if (isset($_POST['realname'])){	
			$var = array("realname","sex","card_type","card_id","province","city","province","city","area","nation");
			$data = post_var($var);
			$data['user_id'] = $_G['user_id'];
			$data['birthday'] = get_mktime($_POST['birthday']);
			$data['real_status'] = 2;
			if ($data['birthday'] == ""){
				$msg = array("出生年月不能为空");
			}elseif (!preg_match("/^[\x7f-\xff]+$/", $data['realname'])){
				$msg = array("真实姓名必须为汉字");
			}else{
				$cord = $data['card_id'];
				if(strlen($cord)==15){
					$cord = idcard_15to18($data['card_id']);
				}
				$birthday1 = substr($cord, 6, 8);
				$birthday2 = str_replace("-", "", $_POST['birthday']);
				/*
				if (!isIdCard($data['card_id']) && $data['card_type']==1){
					$msg = array("身份证号码格式不正确");
				}elseif($birthday1 != $birthday2 && $data['card_type']==1){
					$msg = array("出生日期填写有误,与证件不一致");
				}*/
				if(1==2){}
				else{
					$result = userClass::CheckIdcard(array("user_id"=>$data['user_id'],"card_id"=>$data['card_id']));
					if($result == true){
						$msg = array("身份证号码已经存在");
					}else{
						$_G['upimg']['file'] = "card_pic2";
						$_G['upimg']['code'] = "user";
						$pic_result = $upload->upfile($_G['upimg']);
						if ($pic_result!=""){
							$data['card_pic2'] = $pic_result['filename'];
						}
						$_G['upimg']['file'] = "card_pic1";
						$pic_result = $upload->upfile($_G['upimg']);
						if ($pic_result!=""){
							$data['card_pic1'] = $pic_result['filename'];
						}
						if($data['card_pic1'] == "" || $data['card_pic2'] == "" ){
							$msg = array("请重新上传身份证照片，如有问题，请联系客服");
						}else{
							$result = $user->UpdateUserAll($data);
							if ($result == false){
								$msg = array($result);	
							}else{
								$msg = array("实名认证添加成功，请等待管理员审核","",$url);	
							}
						}
					}
				}
			}
		}
	}
	
	//邮箱认证
	elseif ($_U['query_type'] == "email_status"){
		if (isset($_POST['email']) && $_POST['email']!="" ){
			$data['user_id'] = $_G['user_id'];
			$data['email'] = $_POST['email'];
			$result = $user->CheckEmail($data);
			if ($result==false){
				$result = $user->UpdateUserAll($data);
				if ($result == false){
					$msg = array($result);	
				}else{
					$data['username'] = $_G['user_result']['username'];
					$data['webname'] = $_G['system']['con_webname'];
					$data['title'] = $_G['system']['con_webname']."注册邮件确认";
					$data['encryption'] = md5($data['user_id']);
					$data['msg'] = RegEmailMsg($data);
					$data['type'] = "reg";
					if (isset($_SESSION['sendemail_time']) && $_SESSION['sendemail_time']+60*2>time()){
						$msg = array("请2分钟后再次请求。","",$url);
					}else{
						$result = $user->SendEmail($data);
						if ($result==true) {
							$mysql->db_query('update {user} set encryption=\''.$data['encryption'].'\',user_status=\'regemail\' where user_id='.$data['user_id']);
							$_SESSION['sendemail_time'] = time();
							$msg = array("激活信息已经发送到您的邮箱，请注意查收。","",$url);
						}
						else{
							$msg = array("发送失败，请跟管理员联系。","",$url);
						}
					}
				}
			}else{
				$msg = array("你重新填写的邮箱已经存在","",$url);	
			}
		}
	}
	
	//手机认证
	elseif ($_U['query_type'] == "phone_status"){
		if (isset($_REQUEST['phone']) && is_numeric($_REQUEST['phone']) && $_REQUEST['type']=='getcode'){
			//$data['user_id'] = $_G['user_id'];
			//$data['phone_status'] = $_POST['phone'];
			$phone = $_REQUEST['phone'];
			$code = mt_rand(100000,999999);
			$lasttime=time()+5*60;
			$itype=3;
			$sql = "insert into `{sms_check}`(code,lasttime,user_id,addtime,itype,phone) values('".$code."','".$lasttime."',".$_G["user_id"].",unix_timestamp(),".$itype.",'".$phone."')";
			$mysql->db_query($sql);
			$re = sendSMS($_G['user_id'], '尊敬的客户，您验证码是：'.$code.'。请不要把验证码泄露给其他人。如非本人操作，可不用理会！', 1, $phone);
			if($re==1){
				echo 1;
			}else{
				echo 0;
			}
			exit();
			//$result = $user->UpdateUserAll($data);
		}elseif($_POST['code']!="" && $_POST['phone']!="" && !isset($_POST['type'])){
			$sql="select id,code,lasttime from `{sms_check}` where user_id={$_G["user_id"]} and phone='{$_POST['phone']}' and isuse=0 and itype=3 order by id desc";
			$re = $mysql->db_fetch_array($sql);
			if(!isset($re['code'])){
				$msg = array("手机号码不正确","",$url);
			}else{
				if($re['lasttime']<time()){
					$msg = array("验证码已过期，请重新获取","",$url);
				}elseif($re['code']!=$_POST['code']){
					$msg = array("验证码输入有误","",$url);
				}else{
					$data['user_id'] = $_G['user_id'];
					$data['phone_status'] = 1;
					$data['phone'] = $_POST['phone'];
					$result = $user->UpdateUserAll($data);
					$mysql->db_query("update `{sms_check}` set isuse=1 where id={$re['id']}");
					if ($result == false){
						$msg = array($result);	
					}else{
						$msg = array("手机认证成功","",$url);	
					}
				}
			}
		}
	}
	
	//视频认证
	elseif ($_U['query_type'] == "video_status"){
		if (isset($_POST['submit']) && $_POST['submit']!="" ){
			
			$data['user_id'] = $_G['user_id'];
			$data['video_status'] = 2;
			
			$result = $user->UpdateUserAll($data);
			if ($result == false){
				$msg = array($result);	
			}else{
				$msg = array("操作成功，请等待客服人员与你联系","",$url);	
			}
		}
	}
	//现场认证
	elseif ($_U['query_type'] == "scene_status"){
		if (isset($_POST['submit']) && $_POST['submit']!="" ){
			$data['user_id'] = $_G['user_id'];
			$data['scene_status'] = 2;
			$result = $user->UpdateUserAll($data);
			if ($result == false){
				$msg = array($result);
			}else{
				$msg = array("操作成功，请等待客服人员与你联系","",$url);
			}
		}
	}
	//交易密码设置
	elseif ($_U['query_type'] == "credit"){
		$_U['user_cache'] = userClass::GetUserCache(array("user_id"=>$_G['user_id']));//用户缓存
	}
	
	//邀请好友
	elseif ($_U['query_type'] == "reginvite"){
		$_U['user_inviteid'] = email_ciphertext($_G['user_id'],'ENCODE','ds');
		//$_U['user_inviteid'] =  Key2Url($_G['user_id'],"reg_invite");
	}

	//VIP申请
	elseif ($_U['query_type'] == "applyvip"){
		if (isset($_POST['vip_remark'])){
			$data['user_id'] = $_G['user_id'];
			$data['vip_remark'] = nl2br($_POST['vip_remark']);;
// 			$data['kefu_userid'] = intval($_POST['kefu_userid']);
			$data['kefu_userid'] = 0;
            userClass::ApplyUserVip($data);//用户缓存
            
            $msg = array("VIP申请成功，请等待管理员审核","",$_U['query_url'].'/applyvip');
		}
	}

	//加为好友
	elseif ($_U['query_type'] == "addfriend"){
		if (isset($_POST['type'])){
			$data['type'] = $_POST['type'];
			$data['content'] = nl2br($_POST['content']);
			$data['friends_userid'] = $_POST['friends_userid'];
			$data['user_id'] = $_G['user_id'];
			$results = userClass::GetOnes(array("user_id"=>$_G['user_id']));
			 
            $real_status=$results['real_status'];

			if($real_status!=1){
				$msg = array("对不起，未实名认证会员不能添加好友，请先进行实名认证。"); 
			}else{
				$result = userClass::AddFriends($data);
				if ($result==false){
					$msg = array($result,"","/index.php?user&q=code/user/myfriend");	
				}else{
					$msg = array("添加好友成功，请等待好友的审核","","/index.php?user&q=code/user/myfriend");	
				}
			}
		}else{
			if($_G['user_result']['real_status']!=1){
				echo "<div style='text-align:center'>对不起，未实名认证会员不能添加好友，请先进行实名认证。</div>";
				exit();
			}else{
				$result = userClass::GetOnes(array("username"=>$_REQUEST['username']));
				if ($result==false){
					$result = userClass::GetOnes(array("username"=>urldecode($_REQUEST['username'])));
					$_REQUEST['username'] = urldecode($_REQUEST['username']);
				}
				if ($result==false){
					echo "<div style='text-align:center'>找不到此用户，请不要乱操作</div>";
					exit;
				}elseif ($result['user_id']==$_G['user_id']){
					echo "<div style='text-align:center'>不能加自己为好友</div>";
					exit;
				}else{
					echo "<form method='post' action='/index.php?user&q=code/user/addfriend'>";
					echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;好友：{$_REQUEST['username']}<input type='hidden' name='friends_userid' value='{$result['user_id']}'></div>";
					echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;类型11：<select name='type'>";
					foreach ($_G["_linkage"]['friends_type'] as $key => $value){
						echo "<option value='{$value['value']}'>{$value['name']}</option>";
					}
					echo "</select></div><div align='left'><br>&nbsp;&nbsp;&nbsp;内容：<textarea rows='5' cols='30' name='content'></textarea></div>";
					echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;<input type='submit' value='确定添加'></div>";
					echo "</form>";
					exit;
				}
			}
		}
	}
	
	//请求的加为好友
	elseif ($_U['query_type'] == "raddfriend"){
		if (isset($_POST['type'])){
			$data['type'] = $_POST['type'];
			$data['content'] = nl2br($_POST['content']);
			$data['friends_userid'] = $_POST['friends_userid'];
			$data['user_id'] = $_G['user_id'];
			$result = userClass::RAddFriends($data);
			if ($result==false){
				$msg = array($result,"","/index.php?user&q=code/user/myfriend");	
			}else{
				$msg = array("成功添加好友成功","","/index.php?user&q=code/user/myfriend");	
			}
		}else{
			$result = userClass::GetOnes(array("username"=>$_REQUEST['username']));
			if ($result==false){
				echo "<div style='text-align:center'>找不到此用户，请不要乱操作</div>";
				exit;
			}elseif ($result['user_id']==$_G['user_id']){
				echo "<div style='text-align:center'>不能加自己为好友</div>";
				exit;
			}else{
				echo "<form method='post' action='/index.php?user&q=code/user/raddfriend'>";
				echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;好友：{$_REQUEST['username']}<input type='hidden' name='friends_userid' value='{$result['user_id']}'></div>";
				echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;类型：<select name='type'>";
				foreach ($_G["_linkage"]['friends_type'] as $key => $value){
					echo "<option value='{$value['value']}'>{$value['name']}</option>";
				}
				echo "</select></div><div align='left'><br>&nbsp;&nbsp;&nbsp;内容：<textarea rows='5' cols='30' name='content'></textarea></div>";
				echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;<input type='submit' value='确定添加'></div>";
				echo "</form>";
				exit;
			}
		}
	}
	
	
	//加为好友
	elseif ($_U['query_type'] == "checkaddfriend"){
		if (isset($_POST['type'])){
			$data['type'] = $_POST['type'];
			$data['content'] = nl2br($_POST['content']);
			$data['friends_userid'] = $_POST['friends_userid'];
			$data['user_id'] = $_G['user_id'];
			$result = userClass::AddFriends($data);
			if ($result==false){
				$msg = array($result,"","/index.php?user&q=code/user/myfriend");	
			}else{
				$msg = array("添加好友成功，请等待好友的审核","","/index.php?user&q=code/user/myfriend");	
			}
		}else{
			$result = userClass::GetOnes(array("username"=>$_REQUEST['username']));
			if ($result==false){
				echo "<div style='text-align:center'>找不到此用户，请不要乱操作</div>";
				exit;
			}elseif ($result['user_id']==$_G['user_id']){
				echo "<div style='text-align:center'>不能加自己为好友</div>";
				exit;
			}else{
				echo "<form method='post' action='/index.php?user&q=code/user/addfriend'>";
				echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;好友：{$_REQUEST['username']}<input type='hidden' name='friends_userid' value='{$result['user_id']}'></div>";
				echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;类型：<select name='type'>";
				foreach ($_G["_linkage"]['friends_type'] as $key => $value){
					echo "<option value='{$value['value']}'>{$value['name']}</option>";
				}
				echo "</select></div><div align='left'><br>&nbsp;&nbsp;&nbsp;内容：<textarea rows='5' cols='30' name='content'></textarea></div>";
				echo "<div align='left'><br>&nbsp;&nbsp;&nbsp;<input type='submit' value='确定添加'></div>";
				echo "</form>";
				exit;
			}
		}
	}
	
	//删除好友
	elseif ($_U['query_type'] == "delfriend"){
		$data['user_id'] = $_G['user_id'];
		$data['friend_username'] = $_REQUEST['username'];
		userClass::DeleteFriends($data);
		$msg = array("删除成功","",$_U['query_url']."/myfriend");
	
	}
	
	//加为黑名单
	/*
	elseif ($_U['query_type'] == "blackfriend"){
	
        $data['user_id'] = $_G['user_id'];
		$data['friend_username'] = $_REQUEST['username'];
		userClass::BlackFriends($data);
		$msg = array("已成功加入黑名单","",$_U['query_url']."/black");
            
	
	}*/
	//重新加为好友
	elseif ($_U['query_type'] == "readdfriend"){
		$data['user_id'] = $_G['user_id'];
		$data['friend_username'] = $_REQUEST['username'];
		userClass::ReaddFriends($data);
		$msg = array("已成功加为好友","",$_U['query_url']."/myfriend");
	
	}
	
	//开通ips账号
	elseif ($_U['query_type'] == "openAnAccount"){
// 		if($_G['user_result']['real_status']!=1){
// 			$msg = array("请先进行实名认证","前往认证",$_U['query_url']."/realname");
// 		}
		if($_G['user_result']['phone_status']!=1){
			$msg = array("请先进行手机认证","前往认证",$_U['query_url']."/phone_status");
		}
		if(isset($_POST['submit'])){
			$_G['user_result']['realname'] = $_POST['realname'];
			$_G['user_result']['card_id'] = $_POST['card_id'];
			$result = $user->UpdateUserAll(array('realname'=>$_POST['realname'],'card_id'=>$_POST['card_id'],'card_type'=>1,'user_id'=>$_G['user_id']));
			tg_openaccount($_G['user_result']);
		}
	}
	//申请成为兼职
	/*
	elseif ($_U['query_type'] == "jianzhi"){
		
		if(isset($_POST['content']) && $_POST['content']!=""){
			$data['user_id'] = $_G['user_id'];
			$data['content'] = $_POST['content'];
			$data['old_type'] = $_G['user_result']['type_id'];
			$data['new_type'] = 7;
			userClass::TypeChange($data);
			$msg = array("资料以提交，请等待管理员的审核","",$_U['query_url']."/jianzhi");
		}else{
			$_U['typechange_result'] = userClass::TypeChange($data);
		}
	}*/
}
$template = "user_info.html.php";
?>
