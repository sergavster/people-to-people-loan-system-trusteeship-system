<?
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���

if ($_A['query_type'] != "listTJ" && $_A['query_type'] != "fs_list" && $_A['query_type'] != "logold"){
	check_rank("account_".$_A['query_type']);//���Ȩ��
}
include_once("account.class.php");
include_once(ROOT_PATH."core/friends.class.php");

$_A['list_purview'] =  array("account"=>array("�ʽ����"=>array("account_ticheng"=>"����б�","account_list"=>"��Ϣ�б�","account_bank"=>"�����ʻ�","account_cash"=>"���ּ�¼","account_recharge"=>"��ֵ��¼","account_log"=>"�ʽ��¼")));//Ȩ��
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}/list{$_A['site_url']}'>�ʻ��б�</a> - <a href='{$_A['query_url']}/cash&status=0{$_A['site_url']}'>���ִ����</a> - <a href='{$_A['query_url']}/cash&status=1{$_A['site_url']}'>���ֳɹ�</a> -  - <a href='{$_A['query_url']}/cash&status=2{$_A['site_url']}'>����ʧ��</a> - <a href='{$_A['query_url']}/recharge_new{$_A['site_url']}'>������³�ֵ</a> - <a href='{$_A['query_url']}/recharge&status=-1{$_A['site_url']}'>���³�ֵ����</a> - <a href='{$_A['query_url']}/recharge&status=-2&a=cash'>��ֵ��¼</a> - <a href='{$_A['query_url']}/deduct{$_A['site_url']}'>���ÿ۳�</a> - <a href='{$_A['query_url']}/log{$_A['site_url']}'>�ʽ�ʹ�ü�¼</a> - <a href='{$_A['query_url']}/moneyCheck{$_A['site_url']}'>�ʽ���˱�</a>";


/**
 * �������Ϊ�յĻ�����ʾ���е��ļ��б�
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "�ʻ���Ϣ�б�";
	if (isset($_GET['user_id']) && $_GET['user_id']!=""){
		$data['user_id'] = $_GET['user_id'];
	}
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = accountClass::GetList($data);
	if (isset($_REQUEST['type']) && $_REQUEST['type']=="excel"){
		$title = array("���","�û���","��ʵ����","�����","�������","������","���ս��","�������","���ʲ�");
		$data['limit'] = "all";
		$result = accountClass::GetAccListForExport($data);
		exportData("�˻��б�",$title,$result);
		exit;
	}
	if (is_array($result)){
		$pages->set_data($result);
		$_A['account_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}

/**
 * �������õ�ͳ���˻���Ϣ��ÿ���賿3������ add by jackfeng 2012-09-23
**/
/*
else if ($_A['query_type'] == "listTJ"){
	$_A['list_title'] = "�ʻ���Ϣ�б�";
	if (isset($_REQUEST['user_id']) && $_REQUEST['user_id']!=""){
		$data['user_id'] = $_REQUEST['user_id'];
	}
	if (isset($_REQUEST['username']) && $_REQUEST['username']!=""){
		$data['username'] = $_REQUEST['username'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	if (isset($_REQUEST['type']) && $_REQUEST['type']=="excel"){
		$title = array("���","�û���","��ʵ����","�����","�������","������","���ս��","�������","���ʲ�");
		$data['limit'] = "all";

		$result = accountClass::GetAccListTJForExport($data);

		exportData("�˻��б�",$title,$result);
		exit;
	}
	$result = accountClass::GetListTJ($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['account_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
*/

/***
 * Author:LiuYY
 * 2012-05-04
 * ��̨����б�
 */
else if ($_A['query_type'] == "ticheng"){
$_A['list_title'] = "�ʻ���Ϣ�б�";
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	if (isset($_GET['type']) && $_GET['type']=="excel"){
		$title = array("���","ʱ��","�û���","Ͷ���ܶ�");
		$data['limit'] = "all";
		$result = accountClass::GetTichengForExport($data);
		exportData("��������б�",$title,$result);
		exit;
	}
	$result = accountClass::GetTicheng($data);
	if(is_array($result)){
		$pages->set_data($result);
		$_A['account_ticheng'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
/**
 * �����Աע�Ტ����VIP��Ա������б�
**/
else if ($_A['query_type'] == "vipTC"){
	$_A['list_title'] = "��Ա����б�";

	$data["user_id"]="-1";
        
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
        $data['user_id']="-1";
        
	if (isset($_REQUEST['username']) && $_REQUEST['username']!=""){
		$data['username'] = $_REQUEST['username'];
	}
        
	if (isset($_REQUEST['username2']) && $_REQUEST['username2']!=""){
		$data['username2'] = $_REQUEST['username2'];
	}
        
	$result = friendsClass::GetFriendsInvite($data);
        $list=$result['list'];
	foreach ($list as $key => $value){
     
                $inviteUserId = $value["invite_userid"];
		$sql = "select username from {user} where `user_id`={$inviteUserId}";
		$resultValue = $mysql->db_fetch_array($sql);               
		$list[$key]['inviteUserName'] = $resultValue["username"];
	}
        $result['list']=$list;
        
	if (is_array($result)){
		$pages->set_data($result);
		$_A['vipTC_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

/*
//�����Ǹ������ 
*/
else if ($_A['query_type'] == "fs_list"){
	$_A['list_title'] = "�������";

	if (isset($_REQUEST['user_id']) && $_REQUEST['user_id']!=""){
		$data['user_id'] = $_REQUEST['user_id'];
	}
	
	if (isset($_REQUEST['username']) && $_REQUEST['username']!=""){
		$data['username'] = $_REQUEST['username'];
	}
	
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = accountClass::GetListFs($data);

	if (is_array($result)){
		$pages->set_data($result);
		$_A['fs_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);

	}else{
		$msg = array($result);
	}
}
/**
 * �û��ʽ���������
 */
else if ($_A['query_type'] == "moneyCheck"){
	$_A['list_title'] = "�ʽ���˱�";

	$data["user_id"]="-1";
        
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
        $data['user_id']="-1";
        
	if (isset($_REQUEST['username']) && $_REQUEST['username']!=""){
		$data['username'] = $_REQUEST['username'];
	}
	if (isset($_REQUEST['type']) && $_REQUEST['type']=="excel"){
      
		$title = array("�û���","�ʽ��ܶ�","�����ʽ�","�����ʽ�","�����ʽ�(1)","�����ʽ�(2)","��ֵ�ʽ�(1)","��ֵ�ʽ�(2)","���У�����","���У�����1","���У�����2","�ɹ����ֽ��","����ʵ�ʵ���","���ַ���","Ͷ�꽱�����","Ͷ�������ʽ�","Ͷ��������Ϣ","Ͷ�������Ϣ","����ܽ��","���꽱��","�������","�������","����ѻ���Ϣ","ϵͳ�۷�","�ƹ㽱��","VIP�۷�","�ʽ��ܶ�1","�ʽ��ܶ�2");
		//$data['limit'] = "all";
		$result = accountClass::GetUsersMoneyCheckListForExcel($data);

		exportData("�û��ʽ���������",$title,$result);
		exit;
	}
        if ($data['username']!="")
	{
        $result = accountClass::GetUsersMoneyCheckList($data);
		if (is_array($result)){
		$pages->set_data($result);
		$_A['moneyCheck_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
	}
}
/**
 * ���ֲο�
**/

elseif ($_A['query_type'] == "cashCK"){
	$_A['list_title'] = "���ֲο�";
	if (isset($_GET['user_id']) && $_GET['user_id']!=""){
		$data['user_id'] = $_GET['user_id'];
	}
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = accountClass::GetCKList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['account_cashCK_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}

/**
 * ���ּ�¼
**/
elseif ($_A['query_type'] == "cash"){
	$_A['list_title'] = "���ּ�¼";
	if (isset($_GET['user_id'])){
		$data['user_id'] = $_GET['user_id'];
	}
	if (isset($_GET['username'])){
		$data['username'] = $_GET['username'];
	}
	if (isset($_GET['status']) && $_GET['status']!=""){
		$data['status'] = $_GET['status'];
	}
	if (isset($_GET['dotime1'])){
		$data['dotime1'] = $_GET['dotime1'];
	}
	if (isset($_GET['dotime2'])){
		$data['dotime2'] = $_GET['dotime2'];
	}
	if(isset($_GET['account'])){
		$data['account'] = $_GET['account'];
	}
	if (isset($_GET['type']) && $_GET['type']=="excel"){
		$title = array("Id","�û�����","��ʵ����","�����˺�","��������","֧��","�����ܶ�","���˽��","������","����ֿ�","����ʱ��","״̬");
		$data['limit'] = "all";
		$result = accountClass::GetCashList($data);
		include_once ROOT_PATH.'modules/borrow/borrow.class.php';
		$borrow = new borrowClass();
		$borrow->borrowListForExcel(array('type'=>'cash','title'=>$title,'excelresult'=>$result,'filename'=>'�����б�'));
		exit;
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = accountClass::GetCashList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['account_cash_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
/**
 * ������˲鿴
**/
elseif ($_A['query_type'] == "cash_view"){
	$_A['list_title'] = "������˲鿴";
	if (isset($_POST['id'])){
		$var = array("id","status","credited","fee","verify_remark");
		$data = post_var($var);
		$data['user_id']=$_POST['user_id'];
		$re = accountClass::UpdateCash($data);
		if(is_bool($re)){
			if($re==false){
				$msg = array("����ʧ��","",$_A['query_url']."/cash".$_A['site_url']);
			}else{
				$msg = array("�����ɹ�","",$_A['query_url']."/cash".$_A['site_url']);
			}
		}else{
			$msg = array($re,"",$_A['query_url']."/cash".$_A['site_url']);
		}
		$user->add_log($_log,$re);//��¼����
	}else{
		$data['id'] = $_REQUEST['id'];
		$_A['account_cash_result'] = accountClass::GetCashOne($data);
	}
}
/**
 * �˺ų�ֵ���
**/
elseif ($_A['query_type'] == "recharge_view"){
	$_A['list_title'] = "��ֵ�鿴";
	if (isset($_POST['id'])){
		if(strtolower($_POST['valicode'])==$_SESSION['valicode'] && $_POST['valicode']!=""){
			unset($_SESSION['valicode']);
			$var = array("id","status","verify_remark");
			$data = post_var($var);
			$re = accountClass::UpdateRecharge($data);
			if(is_bool($re)){
				if($re==false){
					$msg = array("����ʧ��","",$_A['query_url']."/recharge".$_A['site_url']);
				}else{
					$msg = array("�����ɹ�","",$_A['query_url']."/recharge".$_A['site_url']);
				}
			}else{
				$msg = array($re,"",$_A['query_url']."/recharge".$_A['site_url']);
			}
			$user->add_log($_log,$re);//��¼����
		}else{
			$msg = array('��֤����������');
		}
	}else{
		$data['id'] = $_GET['id'];
		$_A['account_recharge_result'] = accountClass::GetRechargeOne($data);
	}
}
/**
 * ��ֵ��¼
**/
elseif ($_A['query_type'] == "recharge"){
	$_A['list_title'] = "��ֵ��¼";
	if (isset($_GET['user_id'])){
		$data['user_id'] = $_GET['user_id'];
	}
	if (isset($_GET['username'])){
		$data['username'] = $_GET['username'];
	}
	if (isset($_GET['status']) && $_GET['status']!='-2'){
		$data['status'] = $_GET['status'];
	}
	if (isset($_GET['dotime1'])){
		$data['dotime1'] = $_GET['dotime1'];
	}
	if (isset($_GET['dotime2'])){
		$data['dotime2'] = $_GET['dotime2'];
	}
	if (isset($_GET['trade_no'])){
		$data['trade_no'] = $_GET['trade_no'];
	}
	if(isset($_GET['pertainbank'])){
		$data['pertainbank'] = $_GET['pertainbank'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = 15;
	
	if (isset($_GET['type']) && $_GET['type']=="excel"){
		$title = array("���","��ˮ��","�û�����","��ʵ����","����","��������","��ֵ���","����","���˽��","�������","��ֵʱ��","״̬","���з���");
		$data['limit'] = "all";
		$result = accountClass::GetRechargeList($data);
		include_once ROOT_PATH.'modules/borrow/borrow.class.php';
		$borrow = new borrowClass();
		$borrow->borrowListForExcel(array('type'=>'recharge','title'=>$title,'excelresult'=>$result,'filename'=>'��ֵ��¼'));
		exit;
	}
	include_once(ROOT_PATH."modules/payment/payment.class.php");
	$_A['account_payment_list'] = paymentClass::GetList();
	$result = accountClass::GetRechargeList($data);
	$pages->set_data($result);
	$_A['account_recharge_list'] = $result['list'];
	$_A['showpage'] = $pages->show(3);
}
/**
 * ��ӳ�ֵ��¼
**/
elseif ($_A['query_type'] == "recharge_new"){
	if(isset($_POST['username']) && $_POST['username']!=""){
		$money = floatval($_POST['money']);
		if($money<=0){
			$msg = array("��������ȷ�Ľ��");
		}else{
			$_data['username'] = $_POST['username'];
			$result = userClass::GetOnes($_data);
			if ($result==false){
				$msg = array("�û�������");
			}else{
				$data['user_id'] = $result['user_id'];
				$data['status'] = 0;
				$data['money'] = $money;
				$data['type']=2;
				$data['payment'] = 0;
				$data['fee'] = 0;
				$data['remark'] = $_POST['remark'].",��������ԱID:".$_G['user_id'];
				$data['trade_no'] = time().$result['user_id'].rand(1,9);
				$result = accountClass::AddRecharge($data);
				if ($result !== true){
					$msg = array($result);
				}else{
					$msg = array("�����ɹ�");
				}
			}
		}
	}
}
/*
 * ���������ֵ��¼
 */
elseif($_A['query_type'] == "rechargefromexcel"){
	if($_FILES['excelfile']!=null){
		if($_FILES['excelfile']['error']==0){
			//error_reporting(E_ALL ^ E_NOTICE);
			if(strstr($_FILES['excelfile']['name'],'.')!='.xls'){
				$msg = array("�ļ���ʽ����ȷ����ʹ��xls��ʽ");
			}else{
				$name = ROOT_PATH.'data/upfiles/annexs/'.time().'.xls';
				move_uploaded_file($_FILES['excelfile']['tmp_name'], $name);
				require_once ROOT_PATH.'plugins/excelreader/excel_reader2.php';
				$data = new Spreadsheet_Excel_Reader($name);
				$data->setoutputencoding('GBK');
				$field = '';
				for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
					$username = trim($data->sheets[0]['cells'][$i][1]);
					$result = $mysql->db_fetch_array("select user_id from `{user}` where username='{$username}'");
					if($result['user_id']<=0){
						continue;
					}
					$data_1['user_id'] = $result['user_id'];
					$data_1['status'] = 0;
					$data_1['money'] = trim($data->sheets[0]['cells'][$i][2]);
					$data_1['type']=2;
					$data_1['payment'] = 0;
					$data_1['fee'] = 0;
					$data_1['remark'] = "�������룬��������ԱID:".$_G['user_id'];
					$data_1['trade_no'] = time().$result['user_id'].rand(1,9);
					$result = accountClass::AddRecharge($data_1);
				}
				$msg = array("�����ɹ�");
			}
		}else{
			$msg = array("�ļ�".$_FILES['excelfile']['name']."�ϴ�ʧ��");
		}
	}
}
/**
 * �۳�����
**/
elseif ($_A['query_type'] == "deduct"){
	if(isset($_POST['username']) && $_POST['username']!=""){
		$_data['username'] = $_POST['username'];
		$result = userClass::GetOnes($_data);
		if ($result==false){
			$msg = array("�û���������");
		}elseif (strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
			$msg = array("��֤�벻��ȷ");
		}else{
			unset($_SESSION['valicode']);
			$data['user_id'] = $result['user_id'];
			$data['money'] = $_POST['money'];
			$data['type'] = $_POST['type'];
			$data['remark'] = $_POST['remark'].",��������ԱID:".$_G['user_id'];
			$result = accountClass::Deduct($data);
			if ($result !== true){
				$msg = array($result);
			}else{
				$msg = array("�����ѳɹ��۳�","",$_A['query_url']."/deduct&a=cash");
			}
		}
	}
}
/**
 * �ʽ�ʹ�ü�¼
**/
elseif ($_A['query_type'] == "log"){
	$_A['list_title'] = "�ʽ�ʹ�ü�¼";
	if (isset($_GET['user_id']) && $_GET['user_id']!=""){
		$data['user_id'] = $_GET['user_id'];
	}
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	if (isset($_GET['typeaction'])){
		$data['type'] = $_GET['typeaction'];
	}
	if (isset($_GET['dotime1'])){
		$data['dotime1'] = $_GET['dotime1'];
	}
	if (isset($_GET['dotime2'])){
		$data['dotime2'] = $_GET['dotime2'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
    if (isset($_GET['type']) && $_GET['type']=="excel"){
		$title = array("��¼ʱ��","�û�����","����","�ܽ��","�������","���ý��","������","���ս��","���׶Է�","��ע");
		$data['limit'] = "all";
		$result = accountClass::GetLogListForExcel($data);
		exportData("�ʽ���ˮ��¼",$title,$result);
		exit;
	}
	$result = accountClass::GetLogList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['account_log_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
/**
 * �鿴
**/
elseif ($_A['query_type'] == "view"){
	$_A['list_title'] = "�鿴��֤";
	if (isset($_POST['id'])){
		$var = array("id","status","verify_remark","jifen");
		$data = post_var($var);
		$data['verify_user'] = $_SESSION['user_id'];
		$result = accountClass::Update($data);
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("�����ɹ�");
		}
		$user->add_log($_log,$result);//��¼����
	}else{
		$data['id'] = $_REQUEST['id'];
		$_A['account_result'] = accountClass::GetOne($data);
	}
}
/*
 * �йܶ�����ѯ
**/
elseif ($_A['query_type']=='tgviewcash') {
	if($_GET['view']==1){
		$data['page'] = isset($_GET['page'])?intval($_GET['page']):1;
		$data['username'] = isset($_GET['username'])?$_GET['username']:'';
		$data['order_number'] = isset($_GET['order_number'])?$_GET['order_number']:'';
		$data['borrow_id'] = isset($_GET['borrow_id']) && $_GET['borrow_id']!=0?$_GET['borrow_id']:'';
		$data['repayment_id'] = isset($_GET['repayment_id'])?$_GET['repayment_id']:'';
		$data['collection_id'] = isset($_GET['collection_id'])?$_GET['collection_id']:'';
		if ($_GET['viewtype']=='cash') {
			$data['tran_code'] = $HX_config['cashTranCode'];
		}elseif ($_GET['viewtype']=='recharge') {
			$data['tran_code'] = $HX_config['rechargeTranCode'];
		}elseif($_GET['viewtype']=='repayment'){
			$data['tran_code'] = $HX_config['repaymentTranCode'];
		}elseif($_GET['viewtype']=='tender'){
			$data['tran_code'] = $HX_config['tenderTranCode'];
		}elseif($_GET['viewtype']=='full'){
			$data['tran_code'] = $HX_config['fullTranCode'];
		}elseif($_GET['viewtype']=='liubiao'){
			$data['tran_code'] = $HX_config['liubiaoTranCode'];
		}elseif($_GET['viewtype']=='addborrow'){
			$data['tran_code'] = $HX_config['addBorrowTranCode'];
		}elseif($_GET['viewtype']=='apenaccount'){
			$data['tran_code'] = $HX_config['apenAccountTranCode'];
		}elseif($_GET['viewtype']=='deduct'){
			$data['tran_code'] = $HX_config['deductTranCode'];
		}
		$re = accountClass::getOrderResult($data);
		$pages->set_data($re);
		$_A['showpage'] = $pages->show(3);
		$_A['order_result'] = $re['list'];
	}elseif($_GET['view']==2){
		if(!isset($_GET['order_id']) || $_GET['order_id']<1){
			$msg = array("���������벻Ҫ�Ҳ���");
		}else{
			$re = accountClass::getOrderResult(array('id'=>$_GET['order_id'],'limit'=>1));
			if(empty($re[0])){
				$msg = array("����������");
			}else{
				$_A['order_result'] = $re[0];
				$cx_return_show = array();
				foreach($_A['order_result']['cx_return'] as $k=>$v){
					if(array_key_exists($k, $HX_config['cx_return_key'])){
						if($k=='state'){
							if($v==1)
								$v='�ɹ�';
							else
								$v = 'ʧ��';
						}
						$cx_return_show[$HX_config['cx_return_key'][$k]] = $v;
					}else{
						unset($_A['order_result']['cx_return'][$k]);
					}
				}
				$_A['order_result']['cx_return_show'] = $cx_return_show;

				$_A['order_result']['tg_return_show'] = $_A['order_result']['tg_return'];
				if($_A['order_result']['tran_code']=='P009'){
					$wre = $mysql->db_fetch_array("select * from {account_recharge} where trade_no='{$_A['order_result']['order_number']}'");
					$_A['web_recharge'] = $wre;
				}
			}
		}
	}elseif($_GET['view']==3){
		if(!isset($_GET['order_id'])  || $_GET['order_id']<1){
			$msg = array("���������벻Ҫ�Ҳ���");
		}else{
			$re = accountClass::getOrderResult(array('id'=>$_GET['order_id'],'limit'=>1));
			if(empty($re[0])){
				$msg = array("����������");
			}else{
				$a = $re[0];
				$b['orgTxnType'] = $a['tran_code'];
				$b['orgOrderNum'] = $a['order_number'];
				$b['orgtranDateTime'] = $a['tran_time'];
				$b['order_id'] = $a['id'];
				$c = tg_orderinquire($b);
				$c = $c['result']['order'];
				foreach($c as $k=>$v){
					$c[$k] = iconv('gbk', 'UTF-8', $v);
				}
				echo json_encode($c);
				exit();
			}
		}
	}elseif($_GET['view']==4){//����
		if(!isset($_GET['order_id'])  || $_GET['order_id']<1){
			$msg = array("���������벻Ҫ�Ҳ���");
		}else{
			$re = accountClass::getOrderResult(array('id'=>$_GET['order_id'],'limit'=>1));
			if(empty($re[0])){
				$msg = array("����������");
			}else{
				$re = $re[0];
				if($re['tran_code']==$HX_config['rechargeTranCode']){//��ֵ
					$sub=false;
					if($re['err_code']=='0000'){
						$sub=true;
					}else{
						if(empty($re['cx_return'])){
							$b['orgTxnType'] = $a['tran_code'];
							$b['orgOrderNum'] = $a['order_number'];
							$b['orgtranDateTime'] = $a['tran_time'];
							$b['order_id'] = $a['id'];
							$c = tg_orderinquire($b);
							if($c['orgTxnStat']=='0000'){
								$sub=true;
							}
						}else{
							if($re['cx_return']['orgTxnStat']=='0000'){
								$sub=true;
							}
						}
						
					}
					if($sub==true){
						$_TransID = $re['order_number'];//��ֵ������
						$file = ROOT_PATH."data/pay_cache/".$_TransID;   
						$fp = fopen($file , 'w+');
						@chmod($file, 0777);	  
						if(flock($fp , LOCK_EX | LOCK_NB)){//�趨ģʽ��ռ�����Ͳ���������
							accountClass::OnlineReturn(array("trade_no"=>$_TransID));
							$msg = array("�����ɹ�",'',$_A['query_url'].'/'.$_A['query_type'].'&view=2&order_id='.$_GET['order_id'].$_A['site_url']);
							flock($fp , LOCK_UN);
						} else{
							$msg = array("����ʧ��");
						}     
						fclose($fp);
					}else{
						$msg = array("����δ���ˣ��޷����в�������");
					}
				}elseif ($re['tran_code']==$HX_config['fullTranCode']) {//������ս�۳�
					if($re['err_code']=='0000'){
						$a = $mysql->db_fetch_array('select id,name,user_id from {borrow} where id='.$re['borrow_id']);
						$b = $mysql->db_fetch_array("select * from {account_log} where type='risk_fee' and borrow_id={$re['borrow_id']}");
						if(empty($a)){
							$msg = array("δ�ҵ�idΪ{$re['borrow_id']}�Ľ���");
						}elseif(!empty($b)){
							$msg = array("idΪ{$re['borrow_id']}�Ľ����ѿ۳����ս��޷��ظ��۳�");
						}else{
							mysql_query("start transaction");
							$account_result =  accountClass::GetOneAccount(array("user_id"=>$a['user_id']));
							$log['user_id'] = $a['user_id'];
							$log['type'] = "risk_fee";
							$log['money'] = $re['tg_return']['riskBalance'];
							$log['total'] = $account_result['total']-$log['money'];
							$log['use_money'] = $account_result['use_money']-$log['money'];
							$log['no_use_money'] = $account_result['no_use_money'];
							$log['collection'] = $account_result['collection'];
							$log['to_user'] = '0';
							$log['borrow_id'] = $re['borrow_id'];
							$log['remark'] = '�۳�['."<a href=\'/invest/a{$a['id']}.html\' target=_blank>{$a['name']}</a>".']�ķ��ս�';
							$re = accountClass::AddLog($log);
							if($account_result==false || $re==false){
								mysql_query("rollback");
								$msg = array("����ʧ�ܣ�������");
							}else{
								mysql_query("commit");
								$msg = array("�����ɹ�",'',$_A['admin_url'].'&q=module/account/tgviewcash&view=2&order_id='.$_GET['order_id'].'&a=cash');
							}
						}
					}else{
						$msg = array("���ϸ���δͨ�����޷����з��ս�۳�");
					}
				}
			}
		}
	}else{
		$msg = array("���������벻Ҫ�Ҳ���");
	}
}
//�йܶ���
elseif ($_A['query_type']=='tgcheck') {

	$page = isset($_GET['page'])?$_GET['page']:1;
	$epage = 5;
	//$where = ' where p3.is_tgAccount=1 and p2.status=1';
	$where = ' where p3.is_tgAccount=1';
	if(isset($_GET['username']) && $_GET['username']!=''){
		$where .= " and p3.username='{$_GET['username']}'";
	}
	/*
	$group = ' group by p2.user_id ';
	$_sql = ' from {account_recharge} as p2 left join {user} as p3 on p3.user_id=p2.user_id left join {account} as p1 on p1.user_id=p2.user_id '.$where;
	$sql = 'select count(distinct p2.user_id) as count '.$_sql;
	*/
	$group = ' group by p1.user_id ';
	$_sql = ' from {account} as p1 left join {user} as p3 on p3.user_id=p1.user_id '.$where;
	$sql = 'select count(p1.id) as count '.$_sql;
	$a = $mysql->db_fetch_array($sql);
	$total = $a['count'];
	$total_page = ceil($total/$epage);
	$limit = ' limit '.($page-1)*$epage.', '.$epage;
	$sql = 'select p1.*,p3.username,p3.is_tgAccount,p3.pIpsAcctNo,p3.virCardNo,p3.phone '.$_sql.' order by p1.user_id desc '.$limit;
	$a = $mysql->db_fetch_arrays($sql);
	foreach($a as $k=>$v){
		$a[$k]['tg_account'] = tg_getaccount($v);
	}
	$re = array('list'=>$a,'page'=>$page,'epage'=>$epage,'total_page'=>$total_page,'total'=>$total);
	$pages->set_data($re);
	$_A['showpage'] = $pages->show(3);
	$_A['tgcheck'] = $re['list'];
}
elseif($_A['query_type']=='tgzhuanz'){
	if(isset($_POST['out_user'])){
		if($_POST['valicode']==$_SESSION['valicode']){
			$out = $mysql->db_fetch_array("select user_id,is_tgAccount,pIpsAcctNo,user_id from {user} where username='{$_POST['out_user']}'");
			if(empty($out)){
				$msg = array("�����û�{$_POST['out_user']}������");
			}
			elseif($out['is_tgAccount']!=1){
				$msg = array("�����û�{$_POST['out_user']}δ�󶨱����˺�");
			}else{
				$in = $mysql->db_fetch_array("select user_id,is_tgAccount,pIpsAcctNo,user_id from {user} where username='{$_POST['in_user']}'");
				if(empty($in)){
					$msg = array("����û�{$_POST['out_user']}������");
				}
				elseif($in['is_tgAccount']!=1){
					$msg = array("����û�{$_POST['out_user']}δ�󶨱����˺�");
				}else{
					$data['out_user'] = $out['pIpsAcctNo'];
					$data['in_user'] = $in['pIpsAcctNo'];
					$data['amount'] = floatval($_POST['account']);
					$data['trade_no'] = time().$data['out_user'].rand(001,999);
					$data['status'] = 1;
					mysql_query("start transaction");
					$add = $mysql->db_add('transfer_accounts',array('amount'=>$data['amount'],'out_user'=>$out['user_id'],'in_user'=>$in['user_id'],'status'=>1,'trade_no'=>$data['trade_no']));
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$out['user_id']));
					$log['user_id'] = $out['user_id'];
					$log['type'] = "zhuanz_out";
					$log['money'] = $data['amount'];
					$log['total'] = $account_result['total']-$log['money'];
					$log['use_money'] = $account_result['use_money']-$log['money'];
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = $in['user_id'];
					$log['remark'] = "ת�˿۳�";
					$re = accountClass::AddLog($log);
					if($add==false || $account_result==false || $re==false){
						mysql_query("rollback");
						$msg = array("ת�˿۳�ʧ��");
					}else{
						$account_result =  accountClass::GetOneAccount(array("user_id"=>$in['user_id']));
						$log['user_id'] = $in['user_id'];
						$log['type'] = "zhuanz_in";
						$log['money'] = $data['amount'];
						$log['total'] = $account_result['total']+$log['money'];
						$log['use_money'] = $account_result['use_money']+$log['money'];
						$log['no_use_money'] = $account_result['no_use_money'];
						$log['collection'] = $account_result['collection'];
						$log['to_user'] = $in['user_id'];
						$log['remark'] = "ת�˽���";
						$re = accountClass::AddLog($log);
						if($account_result==false || $re==false){
							mysql_query("rollback");
							$msg = array("ת�˿۳�ʧ��");
						}else{
							$a = tg_deduct_add($data);
							if($a===true){
								mysql_query("commit");
								$msg = array("ת�˳ɹ�",'',$_A['query_url'].'/tgzhuanz&a=cash');
								$status = 1;
							}else{
								mysql_query("rollback");
								$msg = array($a);
								$status = 2;
							}
							$mysql->db_query('update {transfer_accounts} set status='.$status);
						}
					}
				}
			}
		}else{
			$msg = array("��֤����������");
		}
	}
}
elseif($_A['query_type']=='tgzhuanz_c'){//�����ʽ���־��������
	if(isset($_POST['out_user'])){
		if($_POST['valicode']==$_SESSION['valicode']){
			$out = $mysql->db_fetch_array("select user_id,is_tgAccount,pIpsAcctNo,user_id from {user} where username='{$_POST['out_user']}'");
			if(empty($out)){
				$msg = array("�����û�{$_POST['out_user']}������");
			}
			elseif($out['is_tgAccount']!=1){
				$msg = array("�����û�{$_POST['out_user']}δ�󶨱����˺�");
			}else{
				$in = $mysql->db_fetch_array("select user_id,is_tgAccount,pIpsAcctNo,user_id from {user} where username='{$_POST['in_user']}'");
				if(empty($in)){
					$msg = array("����û�{$_POST['out_user']}������");
				}
				elseif($in['is_tgAccount']!=1){
					$msg = array("����û�{$_POST['out_user']}δ�󶨱����˺�");
				}else{
					$data['out_user'] = $out['pIpsAcctNo'];
					$data['in_user'] = $in['pIpsAcctNo'];
					$data['amount'] = floatval($_POST['account']);
					$data['trade_no'] = time().$data['out_user'].rand(001,999);
					$data['status'] = 1;
					$a = tg_deduct_add($data);
					if($a===true){
						$msg = array("ת�˳ɹ�",'',$_A['query_url'].'/tgzhuanz_c&a=cash');
					}else{
						$msg = array($a);
					}
					//mysql_query("start transaction");
					/*$add = $mysql->db_add('transfer_accounts',array('amount'=>$data['amount'],'out_user'=>$out['user_id'],'in_user'=>$in['user_id'],'status'=>1,'trade_no'=>$data['trade_no']));
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$out['user_id']));
					$log['user_id'] = $out['user_id'];
					$log['type'] = "zhuanz_out";
					$log['money'] = $data['amount'];
					$log['total'] = $account_result['total']-$log['money'];
					$log['use_money'] = $account_result['use_money']-$log['money'];
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = $in['user_id'];
					$log['remark'] = "ת�˿۳�";
					$re = accountClass::AddLog($log);
					if($add==false || $account_result==false || $re==false){
						mysql_query("rollback");
						$msg = array("ת�˿۳�ʧ��");
					}else{
						$account_result =  accountClass::GetOneAccount(array("user_id"=>$in['user_id']));
						$log['user_id'] = $in['user_id'];
						$log['type'] = "zhuanz_in";
						$log['money'] = $data['amount'];
						$log['total'] = $account_result['total']+$log['money'];
						$log['use_money'] = $account_result['use_money']+$log['money'];
						$log['no_use_money'] = $account_result['no_use_money'];
						$log['collection'] = $account_result['collection'];
						$log['to_user'] = $in['user_id'];
						$log['remark'] = "ת�˽���";
						$re = accountClass::AddLog($log);
						if($account_result==false || $re==false){
							mysql_query("rollback");
							$msg = array("ת�˿۳�ʧ��");
						}else{
							$a = tg_deduct_add($data);
							if($a===true){
								mysql_query("commit");
								$msg = array("ת�˳ɹ�",'',$_A['query_url'].'/tgzhuanz&a=cash');
								$status = 1;
							}else{
								mysql_query("rollback");
								$msg = array($a);
								$status = 2;
							}
							$mysql->db_query('update {transfer_accounts} set status='.$status);
						}
					}*/
				}
			}
		}else{
			$msg = array("��֤����������");
		}
	}
}
elseif($_A['query_type']=='tgzhuanz_r'){
	if(isset($_POST['out_user'])){
		if($_POST['valicode']==$_SESSION['valicode']){
			$out = $mysql->db_fetch_array("select user_id,is_tgAccount,pIpsAcctNo,user_id from {user} where username='{$_POST['out_user']}'");
			if(empty($out)){
				$msg = array("�����û�{$_POST['out_user']}������");
			}
			elseif($out['is_tgAccount']!=1){
				$msg = array("�����û�{$_POST['out_user']}δ�󶨱����˺�");
			}else{
				if(1==2){
				}else{
					$data['out_user'] = $out['pIpsAcctNo'];
					$data['amount'] = floatval($_POST['account']);
					$data['trade_no'] = time().$data['out_user'].rand(001,999);
					$data['status'] = 1;
					mysql_query("start transaction");
					$add = $mysql->db_add('transfer_accounts',array('amount'=>$data['amount'],'out_user'=>$out['user_id'],'in_user'=>0,'status'=>1,'trade_no'=>$data['trade_no']));
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$out['user_id']));
					$log['user_id'] = $out['user_id'];
					$log['type'] = "zhuanz_out";
					$log['money'] = $data['amount'];
					$log['total'] = $account_result['total']-$log['money'];
					$log['use_money'] = $account_result['use_money']-$log['money'];
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = $in['user_id'];
					$log['remark'] = "ת�˿۳�";
					$re = accountClass::AddLog($log);
					if($add==false || $account_result==false || $re==false){
						mysql_query("rollback");
						$msg = array("ת�˿۳�ʧ��");
					}else{
						$a = tg_deduct($data);
						if($a===true){
							mysql_query("commit");
							$msg = array("ת�˳ɹ�",'',$_A['query_url'].'/tgzhuanz_r&a=cash');
							$status = 1;
						}else{
							mysql_query("rollback");
							$msg = array($a);
							$status = 2;
						}
						$mysql->db_query('update {transfer_accounts} set status='.$status);
					}
				}
			}
		}else{
			$msg = array("��֤����������");
		}
	}
}
elseif($_A['query_type']=='tgzhuanzlist'){
	$data['page'] = isset($_GET['page'])?$_GET['page']:1;
	$data['out_username'] = isset($_GET['out_username'])?$_GET['out_username']:'';
	$data['in_username'] = isset($_GET['in_username'])?$_GET['in_username']:'';
	$data['trade_no'] = isset($_GET['trade_no'])?$_GET['trade_no']:'';
	$result = accountClass::zhuanzList($data);
	$pages->set_data($result);
	$_A['showpage'] = $pages->show(3);
	$_A['tgzhuanzlist'] = $result['list'];
}
//��ֹ�Ҳ���
else{
	$msg = array("���������벻Ҫ�Ҳ���");
}
?>