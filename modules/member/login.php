<?php
if (!defined('ROOT_PATH')) die('���ܷ���'); //��ֱֹ�ӷ���
require_once ROOT_PATH . '/core/config_ucenter.php';
require_once ROOT_PATH . '/uc_client/client.php';

if (isset($_POST['password'])) {
	$login_msg = "";
	if($_POST['username'] == "") {
		$msg = array("�˺Ų���Ϊ��", "", "?user&q=going/login");
	} elseif ($_POST['password'] == "") {
		$msg = array("���벻��Ϊ��", "", "?user&q=going/login");
	}elseif(is_email($_POST['username'])){
		$msg = array("��ʹ��-�û���-��¼", "", "?user&q=going/login");
	}elseif(1==2 && ($_POST['valicode']=='' || $_POST['valicode']!=$_SESSION['valicode'])){
		$msg = array("��֤����������", "", "?user&q=going/login");
	}
	else {
		unset($_SESSION['valicode']);
		//�ж��Ƿ��ǹ���Ա������Ա�������¼
		$userInfo = $user->GetOne(array('username'=>$_POST['username']));
		$user_type = $user->GetTypeList(array("type"=>1));
		$admin_types = array();
		foreach ($user_type as $value){
			$admin_types[] = $value['type_id'];
		}
		//����Ϊ����Ա��ֹ��¼���û��������û������ǹ���Ա���ܵ�¼
		if ($rdGlobal['admin_login_forbid'] && isset($userInfo['type_id']) && in_array($userInfo['type_id'], $admin_types)){
			$msg = array("����Ա�ʺŲ��ܵ�¼ǰ̨", "", "?user&q=going/login");
		}else{
		
		if (!isset($index['user_id']) || $index['user_id'] == "") {
			$index['user_id'] = $_POST['username'];
		} 
		$index['email'] = $_POST['username'];
		$index['username'] = $_POST['username'];
		$index['password'] = $_POST['password'];
		$index['openid'] = $_POST['openid']; //qq�����¼ �ṩopenid
		if ($rdGlobal['uc_on']&&empty($index['openid']))
		{
			list($uid, $ucusername, $ucpassword, $email) = uc_user_login($_REQUEST['username'], $_REQUEST['password']);
			if ($uid > 0) {
				//$ucsynlogin = uc_user_synlogin($uid);
				$sql = "select * from `{user}` where username = '" . $ucusername . "'";
				$result = $mysql -> db_fetch_array($sql);
				/***
				 * ��½ʱ��֤��̬����
				 * by:timest 2012-07-26 ����֮ҹ 
				 */
				if(isset($result['serial_id']) && $result['serial_id']!='' ){
					$is_used_uchon = json_decode($result['serial_status'])->{"login"} == '1' ? 1 : 0;
					if($is_used_uchon == 1 && (!isset($_POST['uchoncode']) || $_POST['uchoncode'] == '') ){
						$msg = array("�����붯̬����", "", "?user&q=going/login&errror=" . $uid);
						return ;
					}
					if( $is_used_uchon == 1 && otp_check($result['serial_id'], $_REQUEST['uchoncode'])!='200'){
						$msg = array("��̬��������,�����ԣ�", "", "?user&q=going/login&errror=" . $uid);
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
						$data['title'] = $_G['system']['con_webname']."ע���ʼ�ȷ��";
						$data['key'] = $_U['user_reg_key'];
						$data['msg'] = RegEmailMsg($data);
						$data['type'] = "reg";
						$result = $user -> SendEmail($data);
						$data['reg_step'] = "reg_email"; 
						 
				
						
						$_SESSION['reg_step'] = "reg_email";
					} 
				} 

				$result = $user -> Login($index);
				//add by weego 20120625 for �˻���������
				//var_dump($result);
				if($result['islock']==1){
					$_url = '/';
					$msg = array("���˻��Ѿ�������","����>>", $_url);			
				}elseif($result['status']==0){
					$_url = '/';
					$msg = array("���˻��Ѿ����ر�","����>>", $_url);		
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
					set_session($data); //ע��session
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
					//add by weego for ��¼cookies��֤ 20120610			
					setcookie('login_uid',$data['user_id'], $ctime);

					$areaLoginMsg=$result['areaLoginMsg'];
					$msg = array($ucsynlogin."��¼�ɹ�<br/><font style=color:red>��ȷ��������һ�ε�¼ʱ��</font><br/><font style=color:red>{$areaLoginMsg}</font>","���������û�����>>", $_url);
					}
			} elseif ($uid == -1) {
				$result = $user -> Login($index);
				
				/***
				 * ��½ʱ��֤��̬����
				 * by:timest 2012-07-26 ����֮ҹ 
				 */
				if(isset($result['serial_id']) && $result['serial_id']!='' ){
					$is_used_uchon = json_decode($result['serial_status'])->{"login"} == '1' ? 1 : 0;
					if($is_used_uchon == 1 && (!isset($_POST['uchoncode']) || $_POST['uchoncode'] == '') ){
						$msg = array("�����붯̬����", "", "?user&q=going/login&errror=" . $uid);
						return ;
					}
					if( $is_used_uchon == 1 && otp_check($result['serial_id'], $_POST['uchoncode'])!='200'){
						$msg = array("��̬��������,�����ԣ�", "", "?user&q=going/login&errror=" . $uid);
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
				set_session($data); //ע��session
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
				//add by weego for ��¼cookies��֤ 20120610			
				setcookie('login_uid',$data['user_id'], $ctime);
				
				if ($result['email_status'] == 1 ){
					echo "<script>location.href='index.php?user';</script>";
				}
				else{
					echo "<script>location.href='index.php?user&q=going/reg_email';</script>";
				}
 				$msg = array("ϵͳ��¼�ɹ�,ϵͳ��3�����ת" . $ucsynlogin, "�����û�����", $_url);
			} else {
				$msg = array("�û������ڻ��������", "", "?user&q=going/login&errror=" . $uid);
			} 
		} elseif ($uid == -2) {
			$msg = array("�������", "", "?user&q=going/login&errror=" . $uid);
		} else {
				$msg = array("δ�������", "", "?user&q=going/login&errror=" . $uid);
			} 
	}else{
			$result = $user -> Login($index);
			if(!is_array($result)){
				$msg = array("�û������ڻ��������", "", "?user&q=going/login&errror=" . $uid);
			}elseif($result['islock']==1){
				$_url = '/';
				$msg = array("���˻��Ѿ�������","����>>", $_url);			
			}elseif($result['status']==0){
				$_url = '/';
				$msg = array("���˻��Ѿ����ر�","����>>", $_url);		
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
				set_session($data); //ע��session
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
				//add by weego for ��¼cookies��֤ 20120610			
				setcookie('login_uid',$data['user_id'], $ctime);
				

					$areaLoginMsg=$result['areaLoginMsg'];
					$msg = array($ucsynlogin."��¼�ɹ�<br/><font style=color:red>��ȷ��������һ�ε�¼ʱ��</font><br/><font style=color:red>{$areaLoginMsg}</font>","���������û�����>>", $_url);

				//��ת����ǰ�鿴�ı�
				if(isset($_GET['redirectURL']) && $_GET['redirectURL']!=''){
					//Header("location:/{$_GET['redirectURL']}");exit();
				}
			} else {
				$msg = array("�û������ڻ��������", "", "?user&q=going/login&errror=" . $uid);
			}
			}
		}
		}
	} 
	$_U['login_msg'] = $login_msg;
}
$title = '�û���¼';
$template = 'user_login.html.php';

?>