<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
include_once("message.class.php");
$_U['epage'] = 20;
$valicode = isset($_POST['valicode'])?$_POST['valicode']:"";

$url = $_U['query_url']."/{$_U['query_type']}";

//if (isset($_POST['valicode']) && $valicode!=$_SESSION['valicode']){
if(1==2){
		$msg = array("验证码错误","",$url);
}else{
	if ($_U['query_type'] == "list"){	
		if (isset($_POST['type']) && $_POST['type']==1){
			$data['id'] = $_POST['id'];
			$data['receive_user'] = $_G['user_id'];
			$data['deltype'] = 1;
			$result = messageClass::update($data);
			if ($result!==true){
				$msg = array($result,"",$_U['query_url']);
			}else{
				$msg = array("删除成功","返回短信中心","/index.php?user&q=code/message");
			}
		}elseif (isset($_POST['type']) && $_POST['type']==2){
			$data['id'] = $_POST['id'];
			$data['receive_user'] = $_G['user_id'];
			$data['status'] = 1;
			$result = messageClass::update($data);
			if ($result!==true){
				$msg = array($result,"",$_U['query_url']);
			}else{
				$msg = array("已标记已读","返回短信中心","/index.php?user&q=code/message");
			}
		}elseif (isset($_POST['type']) && $_POST['type']==3){
			$data['id'] = $_POST['id'];
			$data['receive_user'] = $_G['user_id'];
			$data['status'] = 0;
			$result = messageClass::update($data);
			if ($result!==true){
				$msg = array($result,"",$_U['query_url']);
			}else{
				$msg = array("已标记未读","返回短信中心","/index.php?user&q=code/message");
			}
		}else{
			$data['receive_user'] = $_G['user_id'];
			$data['page'] = $_U['page'];
			$data['epage'] = $_U['epage'];
			$data['deltype'] = "0";
			$result = messageClass::GetList($data);
			if (is_array($result)){
				$pages->set_data($result);
				$_U['message_list'] = $result['list'];
				$_U['show_page'] = $pages->show(3);
			}else{
				$msg = array($result,"",$_U['query_url']);
			}
		}
	}
	
	elseif ($_U['query_type'] == "sented"){	
		if (isset($_POST['type']) && $_POST['type']==1){
			$data['id'] = $_POST['id'];
			$data['sent_user'] = $_G['user_id'];
			$data['sented'] = 0;
			$result = messageClass::update($data);
			if ($result!==true){
				$msg = array($result,"",$_U['query_url']);
			}else{
				$msg = array("删除成功","返回短信中心","/index.php?user&q=code/message");
			}
			
		}else{
			$data['sent_user'] = $_G['user_id'];
			$data['page'] = $_U['page'];
			$data['epage'] = $_U['epage'];
			$data['sented'] = 1;
			$result = messageClass::GetList($data);
			if (is_array($result)){
				$pages->set_data($result);
				$_U['message_list'] = $result['list'];
				$_U['show_page'] = $pages->show(3);
			}else{
				$msg = array($result,"",$_U['query_url']);
			}
		}
	}
	
	elseif($_U['query_type'] == "sent"){
		if(isset($_POST['content'])){
			if ($_POST['receive_user']==""){
				$msg = array("发送的收件人不能为空");
			}elseif (trim($_POST['name'])==""){
				$msg = array("标题不能为空");
			}elseif (trim($_POST['content'])==""){
				$msg = array("内容不能为空");
			}else{
                                require_once("modules/userinfo/userinfo.class.php");
                                global $user;
                                $userInfo = userinfoClass::GetOne(array("user_id"=>$_G['user_id']));
								$results = userClass::GetOnes(array("user_id"=>$_G['user_id']));
                                $real_status=$results['real_status'];
								 
								
                                $sql = "select p1.vip_status from `{user_cache}` as p1  where p1.user_id = ".$_G['user_id'];
                                $vipResult = $mysql->db_fetch_array($sql);
                                
								if($real_status!=1){
									  $msg = array("对不起，未实名认证会员不能发送站内信，请先进行实名认证。");
								}elseif($_POST['receive_user'] != "admin" && $vipResult["vip_status"] != 1){
                                    $msg = array("对不起，非VIP会员不能发送站内信，请先申请VIP会员!");
                                }else{
                                    $var = array("receive_user","content","sented","name");
                                    $data = post_var($var);
                                    $data['sent_user'] = $_G['user_id'];
                                    $data['type'] = "friend";  
                                    $data['status'] = 0; 
                                    //add by fengke 2012-05-30
                                    $ruresult = $user->GetOne(array("username"=>$data['receive_user']));
                                    if  (!$ruresult){
                                        $msg = array("用户不存在，请输入正确的用户名，谢谢。");
                                    }else{
                                        $data['receive_user'] = $ruresult['user_id'];
                                        //**********************
                                        $result = messageClass::Add($data);
                                        if ($result!==true){
                                                $msg = array($result,"",$_U['query_url']);
                                        }else{
                                                $msg = array("发送成功","",$_U['query_url']."/sented");
                                        }
                                    }
                                }
                                
			}
		}
	}	
	
	//查看并回复
	elseif ($_U['query_type'] == "view"){	
		if (isset($_POST['content'])){
			$data['id'] = $_POST['id'];
			$result = messageClass::GetOne($data);
			$data = post_var(array("content"));
			$data['name'] = "Re:".$result['name'];
			$data['content'] .= "<br>------------------ 原始信息 ------------------<br>".$result['content'];
			$data['sent_user'] = $_G['user_id'];
			$data['receive_user'] = $result['sent_user'];
			$data['type'] = "friend";  
			$data['status'] = 0; 
			$data['sented'] = 1; 
			$result = messageClass::Add($data);
			if ($result!==true){
				$msg = array($result,"",$_U['query_url']);
			}else{
				$msg = array("发送成功","返回短信中心","/index.php?user&q=code/message");
			}
		}else{
			$data['receive_user'] = $_G['user_id'];
			$data['id'] = $_REQUEST['id'];
			$data['deltype'] = 0;
			$result = messageClass::GetOne($data);
			if (is_array($result)){
				$_U['message_result'] = $result;
			}else{
				$msg = array($result,"",$_U['query_url']);
			}
		}
	}
	
	//查看并回复
	elseif ($_U['query_type'] == "viewed"){	
		$data['sent_user'] = $_G['user_id'];
		$data['id'] = $_REQUEST['id'];
		$data['deltype'] = 0;
		$result = messageClass::GetOne($data);
		if (is_array($result)){
			$_U['message_result'] = $result;
		}else{
			$msg = array($result,"",$_U['query_url']);
		}
	}
        
	//转发并回复
	elseif ($_U['query_type'] == "forward"){	
		if (isset($_POST['content'])){
			$data['id'] = $_POST['id'];
			$result = messageClass::GetOne($data);
			$data = post_var(array("content"));
			$data['name'] = "Re:".$_POST['name'];
			$data['content'] .= "<br>------------------ 原始信息 ------------------<br>".$result['content'];
			$data['sent_user'] = $_G['user_id'];
			$data['receive_user'] = $_POST['receive_user'];
			$data['type'] = "friend";  
			$data['status'] = 0; 
			$data['sented'] = 1; 
			$result = messageClass::Add($data);
			if ($result!==true){
				$msg = array($result,"",$_U['query_url']);
			}else{
				$msg = array("发送成功","返回短信中心","/index.php?user&q=code/message");
			}
		}else{
			$data['receive_user'] = $_G['user_id'];
			$data['id'] = $_REQUEST['id'];
			$data['deltype'] = 0;
			$result = messageClass::GetOne($data);
			if (is_array($result)){
				$_U['message_result'] = $result;
				$_U['message_result']['content'] = "";
			}else{
				$msg = array($result,"",$_U['query_url']);
			}
		}
	}
        
//举报
	elseif ($_U['query_type'] == "jubao"){	
		if (isset($_POST['content'])){
			$data['id'] = $_POST['id'];
			$result = messageClass::GetOne($data);
			$data = post_var(array("content"));
			$data['name'] = "举报:".$_POST['name'];
			$data['content'] .= "<br>------------------ 原始信息 ------------------<br>".$result['content'];
			$data['sent_user'] = $_G['user_id'];
			$data['receive_user'] = 'wzdadmin';
			$data['type'] = "friend";  
			$data['status'] = 0; 
			$data['sented'] = 1; 
			$result = messageClass::Add($data);
			if ($result!==true){
				$msg = array($result,"",$_U['query_url']);
			}else{
				$msg = array("发送成功","返回短信中心","/index.php?user&q=code/message");
			}
		}else{
			$data['receive_user'] = $_G['user_id'];
			$data['id'] = $_REQUEST['id'];
			$data['deltype'] = 0;
			$result = messageClass::GetOne($data);
			if (is_array($result)){
				$_U['message_result'] = $result;
				$_U['message_result']['content'] = "";
			}else{
				$msg = array($result,"",$_U['query_url']);
			}
		}
	}
	
	elseif ($_U['query_type'] == "deled"){	
		if (isset($_REQUEST['id']) ){
			$data['id'] = $_REQUEST['id'];
			$data['sent_user'] = $_G['user_id'];
			$data['sented'] = 0;
			$result = messageClass::update($data);
			
			if ($result!==true){
				$msg = array($result,"",$_U['query_url']);
			}else{
				$msg = array("删除成功","返回短信中心","/index.php?user&q=code/message");
			}
		}else{
			$msg = array("操作有误","返回短信中心","/index.php?user&q=code/message");
		}
	}
	
	elseif ($_U['query_type'] == "del"){
		if (isset($_REQUEST['id']) ){
			$data['id'] = $_REQUEST['id'];
			$data['receive_user'] = $_G['user_id'];
			$data['deltype'] = 1;
			$result = messageClass::update($data);
			if ($result!==true){
				$msg = array($result,"",$_U['query_url']);
			}else{
				$msg = array("删除成功","返回短信中心","/index.php?user&q=code/message");
			}
		}else{
			$msg = array("操作有误","返回短信中心","/index.php?user&q=code/message");
		}
	}
}
$template = "user_message.html.php";
?>
