<?
if (!defined('ROOT_PATH')) die('���ܷ���');//��ֱֹ�ӷ���
if(isset($_POST['email'])){
	$getpwd_msg = "";
	$var = array("email","username","valicode");
	$data = post_var($var);
	if ($data['email']==""){
		$getpwd_msg = "�����ַ����Ϊ��";
	}elseif ($data['username']==""){
		$getpwd_msg = "�û�������Ϊ��";
	}elseif ($data['valicode']==""){
		$getpwd_msg = "��֤�벻��Ϊ��";
	}elseif (strtolower($data['valicode'])!=$_SESSION['valicode']){
		$getpwd_msg = "��֤�벻��ȷ";
	}else{
		unset($_SESSION['valicode']);
		$result = $user->GetOne($data);
		if ($result==false){
			$getpwd_msg = "���䣬�û�����Ӧ����ȷ";
		}else{
			$data['user_id'] = $result['user_id'];
			$data['email'] = $result['email'];
			$data['username'] = $result['username'];
			$data['webname'] = $_G['system']['con_webname'];
			$data['title'] = "�û�ȡ������";
			$data['encryption'] = md5($data['user_id']);
			$data['msg'] = GetpwdMsg($data);
			$data['type'] = "reg";
			if (isset($_SESSION['sendemail_time']) && $_SESSION['sendemail_time']+60*2>time()){
				$getpwd_msg =  "��2���Ӻ��ٴ�����";
			}else{
				$result = userClass::SendEmail($data);
				if ($result) {
					$mysql->db_query('update {user} set encryption=\''.$data['encryption'].'\',user_status=\'getpassword\' where user_id='.$data['user_id']);
					$_SESSION['sendemail_time'] = time();
					$getpwd_msg =  "��Ϣ�ѷ��͵�{$data['email']}����ע�������������ʼ�";
					echo "<script>alert('{$getpwd_msg}');location.href='/'</script>";
				}
				else{
					$getpwd_msg =  "����ʧ�ܣ��������Ա��ϵ";
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
		$getpwd_msg = "�������벻һ��";
	}else if($data['username']==''){
		$getpwd_msg = "�û�������Ϊ��";
	}else if($data['phone']==''){
		$getpwd_msg = "�ֻ����벻��Ϊ��";
	}else{
		$sql = 'select user_id,phone,phone_status from {user} where username=\''.$data['username'].'\'';
		$re = $mysql->db_fetch_array($sql);
		
		if($re==false){
			$getpwd_msg = "�û�������";
		}else{
			$user_id = $re['user_id'];
			$sql="select id,code,lasttime from `{sms_check}` where user_id={$user_id} and phone='{$data['phone']}' and isuse=0 and itype=4 order by id desc";
			$re = $mysql->db_fetch_array($sql);
			if(!isset($re['code'])){
				$getpwd_msg = "�ֻ����벻��ȷ";
			}else{
				if($re['lasttime']<time()){
					$getpwd_msg = "�ֻ���֤���ѹ��ڣ������»�ȡ";
				}elseif($re['code']!=$data['phonecode']){
					$getpwd_msg = "��֤����������";
				}else{
					$user_result = $user->GetOne(array("user_id"=>$user_id));
					require_once ROOT_PATH . '/core/config_ucenter.php';
					require_once ROOT_PATH . '/uc_client/client.php';
					$ucresult = uc_user_edit($user_result['username'], '', $_POST['password'], '', 1);
					if ($ucresult == -1) {
						$msg = array("�����벻��ȷ,��ʹ����̳�ĵ�¼����","",$url);
					} elseif ($ucresult == -4) {
						$msg = array("Email ��ʽ����");
					} elseif ($ucresult == -5) {
						$msg = array("Email ������ע��");
					} elseif ($ucresult == -6) {
						$msg = array("�� Email �Ѿ���ע��");
					} else{
						$mysql->db_query("update `{sms_check}` set isuse=1 where id={$re['id']}");
						$password = md5($data['password']);
						$result = $mysql->db_query("update `{user}` set password='{$password}' where user_id={$user_id} and username='{$data['username']}' limit 1");
						if($result){
							echo "<script>alert('�����޸ĳɹ�')</script>";
							echo "<script>location.href='/index.action?user&q=going/login'</script>";
							exit();
						}else{
							$getpwd_msg = "�����޸�ʧ��";
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
		echo 2;exit();//�û�������
	}
	if($re['phone_status']!=1){
		echo -1;exit();//δ�ֻ���֤
	}
	if($re['phone']!=$phone){
		echo 3;exit();//������ֻ���������֤�Ĳ�����
	}
	$randnum=rand(100000,999999);
	$lasttime=time()+5*60;
	$addtime=time();
	$user_id=$re['user_id'];
	$sql = "insert into `{sms_check}`(code,lasttime,user_id,addtime,itype,phone) values('".$randnum."','".$lasttime."',".$user_id.",'".$addtime."',4,'".$phone."')";
	$mysql->db_query($sql);
	$re = sendSMS($re["user_id"],"�������������֤���ǣ�".$randnum."����5�������ύ��",1,$phone);
	if($re==1){
		echo 1;//���ͳɹ�
	}else{
		echo 0;//����ʧ��
	}
	exit();
}
$title = 'ȡ������';
$template = 'user_getpwd.html.php';
?>