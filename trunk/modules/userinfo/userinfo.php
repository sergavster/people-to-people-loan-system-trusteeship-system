<?
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
check_rank("userinfo_".$_A['query_type']);//���Ȩ��

include_once("userinfo.class.php");

//liukun add for bug 52 begin
$firePHPEnable=TRUE;
if ($firePHPEnable){
	require_once('modules/FirePHPCore/FirePHP.class.php');
	require_once('modules/FirePHPCore/fb.php');
	ob_start();

	$firephp = FirePHP::getInstance(true);
}
//liukun add for bug 52 end

$_A['list_purview'] =  array("userinfo"=>array("�û���Ϣ����"=>array("userinfo_list"=>"��Ϣ�б�","userinfo_new"=>"�����Ϣ","userinfo_edit"=>"�༭��Ϣ","userinfo_del"=>"ɾ����Ϣ","userinfo_view"=>"�鿴��Ϣ")));//Ȩ��
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['admin_url']}&q=module/userinfo{$_A['site_url']}'>�û���Ϣ����</a> - <a href='{$_A['admin_url']}&q=module/user{$_A['site_url']}'>�û�����</a> - <a href='{$_A['admin_url']}&q=module/user/new{$_A['site_url']}'>����û�</a> - <a href='{$_A['admin_url']}&&q=module/credit{$_A['site_url']}'>���ֹ���</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=2{$_A['site_url']}'>��н�ײ���������</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=1{$_A['site_url']}'>˽Ӫҵ����������</a>";
/**
 * �������Ϊ�յĻ�����ʾ���е��ļ��б�
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "��Ϣ�б�";
	if (isset($_GET['username'])){
		$data['username'] = $_GET['username'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = userinfoClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['userinfo_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

/*
 * ����������
*/
elseif ($_A['query_type'] == "vouch_userinfo"){
	$user_id = $_GET['vouch_userid'];
	if($user_id>0){
		$sql = "select p1.*,p2.* from `{userinfo}` p1 left join `{user}` p2 on p1.user_id=p2.user_id where p1.user_id=$user_id";
		$_A['userinfo_result'] = $mysql->db_fetch_array($sql);
		if($_A['userinfo_result']['user_id']>0){
			//����֤��two_work
			//����֤��two_income
			//�������ü�¼����two_credit
			//��С��ҵ���ñ���two_qycredit
			//Ӫҵִ�ա���֯��������֤��˰��Ǽ�֤���������֤���������С��ҵ��two_rests
			//����֤��/���ʱ���two_contributive
			//��˾�³�two_constitution
			//��Ӫ����ͼƬtwo_operate
			//���˺ͶԹ���ˮ��3��������Ҫ��ҵ�ʽ�����˻���6������ˮ��two_stream
			//�Ͻ�˰��ƾ֤������6���£�two_revenue
			//���»�/�ɶ���ǩ������two_sign
			//�ɶ�����»�ͬ�Ᵽ֤�ľ���two_resolution
			if ($_A['userinfo_result']['user_type']==2){
				$sql = "select type_id,nid from `{attestation_type}` p1 where nid in('two_work','two_income','two_credit')";
			}else{
				$sql = "select type_id,nid from `{attestation_type}` p1 where nid in('two_qycredit','two_rests','two_contributive','two_constitution','two_operate','two_stream','two_revenue','two_sign','two_resolution')";
			}
			$re = $mysql->db_fetch_arrays($sql);
			$count = count($re,0);
			$in = "";
			$array = array();
			for ($i=0; $i<$count; $i++){
				$in .= $in==""?$re[$i]['type_id']:','.$re[$i]['type_id'];
				$array[$re[$i]['type_id']]=$re[$i]['nid'];
			}
			if($in!=""){
				$sql = "select p1.*,p2.name,p2.pid from `{attestation}` p1 left join
				`{attestation_type}` p2 on p1.type_id=p2.type_id where p2.pid in ($in) and p1.user_id={$_A['userinfo_result']['user_id']}";
				$result = $mysql->db_fetch_arrays($sql);
				if(count($result,0)>0){
					foreach ($result as $key=>$value){
						if(isset($array[$value['pid']])){
							$_A[$array[$value['pid']]][] = $value;
						}
					}
				}
			}
		}
	}
	$magic->assign("_A",$_A);
	$magic->display("vouch_userinfo.html");
	exit();
}

/**
 * �û������޸�
**/
elseif ($_A['query_type'] == "new"){
	$_A['list_title'] = "������Ϣ";
	//��ȡ�û�id����Ϣ
	if ($_A['query_type'] == "new" && $_GET['id']=="" && isset($_POST['user_id'])){
		if(isset($_POST['user_id']) && $_POST['user_id']!=""){
			$data['user_id'] = $_POST['user_id'];
			$result = userClass::GetOne($data);
		}elseif(isset($_POST['username']) && $_POST['username']!=""){
			$data['username'] = $_POST['username'];
			$result = userClass::GetOne($data);
		}
		if ($result==false){
			$msg = array("�Ҳ������û�");
		}else{
			echo "<script>location.href='".$_A['query_url']."/new&id={$result['user_id']}&a={$_GET['a']}'</script>";
		}
	}elseif($_GET['id']!="" && !isset($_POST['user_id'])){
		$data['user_id'] = $_GET['id'];
		$result = userClass::GetOne($data);
		if ($result==false){
			$msg = array("������������","",$_A['query_url']);
		}else{
			$result = userinfoClass::GetOne($data);
			$_A['userinfo_result'] = $result;
		}
	}elseif (isset($_POST['user_id'])){
		$var = array("user_id","marry","child","education","income","shebao","shebaoid","housing","car","late","house_address","house_area","house_year","house_status","house_holder1","house_holder2","house_right1","house_right2","house_loanyear","house_loanprice","house_balance","house_bank","company_name","company_type","company_industry","company_office","company_jibie","company_worktime1","company_worktime2","company_workyear","company_tel","company_address","company_weburl","company_reamrk","private_type","private_date","private_place","private_rent","private_term","private_taxid","private_commerceid","private_income","private_employee","finance_repayment","finance_property","finance_amount","finance_car","finance_caramount","finance_creditcard","mate_name","mate_salary","mate_phone","mate_tel","mate_type","mate_office","mate_address","mate_income","education_record","education_school","education_study","education_time1","education_time2","tel","phone","post","address","province","city","area","linkman1","relation1","tel1","phone1","linkman2","relation2","tel2","phone2","linkman3","relation3","tel3","phone3","msn","qq","wangwang","ability","interest","others","experience");
		$data = post_var($var);
		$result = userinfoClass::GetOne(array("user_id"=>$_POST['user_id']));
		if ($result == "false"){
			$result = userinfoClass::Add($data);
		}else{
			$result = userinfoClass::Update($data);
		}
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("�����ɹ�");
		}
		$user->add_log($_log,$result);//��¼����
	}elseif($_A['query_type'] == "edit"){
		$data['id'] = $_GET['id'];
		$result = userinfoClass::GetOne($data);
		if (is_array($result)){
			$_A['userinfo_result'] = $result;
		}else{
			$msg = array($result);
		}
	}else{
		$msg = array("���û�δ�ύ�κ�����");
	}
}
/*
 * �鿴��ʹ����֤��
 */
elseif($_A['query_type']=="code"){
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	if(isset($_GET['itype'])){
		$data['itype'] = $_GET['itype'];
	}
	if(isset($_GET['isuse'])){
		$data['isuse'] = $_GET['isuse'];
	}
	if(isset($_GET['iscanuse'])){
		$data['iscanuse'] = $_GET['iscanuse'];
	}
	if(isset($_GET['username'])){
		$data['username'] = $_GET['username'];
	}
	if(isset($_GET['phone'])){
		$data['phone'] = $_GET['phone'];
	}
	$result = userinfoClass::getCode($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['code_result'] = $result['list'];
		$magic->assign("time",time());
		$_A['showpage'] = $pages->show(3);
	}
}
/*
 * ����վ����
 */
elseif($_A['query_type']=="send_message"){
	if(isset($_POST['title'])){
		$title = $_POST['title'];
		$content = $_POST['content'];
		$username = $_POST['username'];
		if($username==""){
			$msg = array("�û�������Ϊ��");
		}elseif($title==""){
			$msg = array("���ⲻ��Ϊ��");
		}elseif($content==""){
			$msg = array("���ݲ���Ϊ��");
		}else{
			$username = explode(";", $username);
			$in = "";
			for($i=0; $i<count($username); $i++){
				$in .= $in==""?"'".$username[$i]."'":",'".$username[$i]."'";
			}
			$sql = "select user_id from `{user}` where username in($in)";
			$result = $mysql->db_fetch_arrays($sql);
			if(count($result,0)!=count($username)){
				$msg = array("�û�������");
			}else{
				require_once ROOT_PATH.'modules/message/message.class.php';
				foreach($result as $value){
					$remind['sent_user'] = "0";
					$remind['receive_user'] = $value['user_id'];
					$remind['name'] = $title;
					$remind['content'] = $content;
					$remind['type'] = "system";
					$remind['status'] = 0;
					$re = messageClass::Add($remind);
				}
				$msg = array("���ͳɹ�");
			}
		}
	}
}
/*
 * ���Ͷ���
 */
elseif($_A['query_type']=="send_phone"){
	if(isset($_POST['phone'])){
		if($_POST['phone']=="" && is_numeric($_POST['phone'])){
			$msg = array("�ֻ����벻��Ϊ��");
		}elseif($_POST['content']==""){
			$msg = array("���ݲ���Ϊ��");
		}else{
			$re = sendSMS(0,$_POST['content'],1,$_POST['phone']);
			if($re==false){
				$msg = array("����ʧ��");
			}else{
				$msg = array("���ͳɹ�");
			}
		}
	}
}
/*
 * �����ʼ�
 */
elseif($_A['query_type']=="send_email"){
	if(isset($_POST['username'])){
		$title = $_POST['title'];
		$content = $_POST['content'];
		$username = $_POST['username'];
		if($username==""){
			$msg = array("�û�������Ϊ��");
		}elseif($title==""){
			$msg = array("���ⲻ��Ϊ��");
		}elseif($content==""){
			$msg = array("���ݲ���Ϊ��");
		}else{
			$username = explode(";", $username);
			$in = "";
			for($i=0; $i<count($username); $i++){
				$in .= $in==""?"'".$username[$i]."'":",'".$username[$i]."'";
			}
			$sql = "select user_id,email from `{user}` where username in($in)";
			$result = $mysql->db_fetch_arrays($sql);
			if(count($result,0)!=count($username)){
				$msg = array("�û�������");
			}else{
				foreach($result as $value){
					$remail['user_id'] = $value['user_id'];
					$remail['email'] = $value['email'];
					$remail['title'] = $title;
					$remail['msg'] =  $content;
					$remail['type'] =  "system";
					$re = $user->SendEmail($remail);
					if($re==false){
						$msg = array("����ʧ��");
					}else{
						$msg = array("���ͳɹ�");
					}
				}
			}
		}
	}
}
/**
 * ɾ��
**/
/*
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = userinfoClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("ɾ���ɹ�");
	}
	$user->add_log($_log,$result);//��¼����
}
*/

/*
 * �ײ���������
*/
elseif($_A['query_type']=="infoconf"){
	check_rank("userinfo_infoconf");//���Ȩ��
	if (isset($_POST['usertype'])){
			$usertype = $_POST['usertype'];
			$data['idnumber'] =(isset($_POST['idnumber']))?isset($_POST['idnumber']):0;
			$data['realname'] =(isset($_POST['realname']))?isset($_POST['realname']):0;
			$data['companyname'] =(isset($_POST['companyname']))?isset($_POST['companyname']):0;
			$data['companyxz'] =(isset($_POST['companyxz']))?isset($_POST['companyxz']):0;
			$data['gsqc'] =(isset($_POST['gsqc']))?isset($_POST['gsqc']):0;
			$data['qylx'] =(isset($_POST['qylx']))?isset($_POST['qylx']):0;

			unset($_POST['usertype']);
			
			$conflist = json_encode($_POST);
			
			$sql = "update  `{userinfo_conf}` set conflist='{$conflist}' where `id` = {$usertype}";
			$result = $mysql->db_query($sql);
			
			if ($result !== true){
				$msg = array($result);
			}else{
				$msg = array("�����ɹ�");
			}
			
			$user->add_log($_log,$result);//��¼����
		
	}else{
		$usertype = $_GET['usertype'];
		if($usertype==1){
			$_A['site_result']['name'] = "˽Ӫҵ����������";
		}elseif ($usertype==2){
			$_A['site_result']['name'] = "��н�ײ��������� ";
		}else{
			$_A['site_result']['name'] = "˽Ӫҵ����������";
			$usertype = 1;
		}
		$sql = "select * from `{userinfo_conf}` where `id` = {$usertype}";
		$result = $mysql->db_fetch_array($sql);
		$_A['infoconf_result'] = json_decode($result['conflist'], true);
	}
}

//��ֹ�Ҳ���
else{
	$msg = array("���������벻Ҫ�Ҳ���");
}
?>