<?php
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
include_once("borrow.class.php");
//liukun add for bug 52 begin
$firePHPEnable=TRUE;
if ($firePHPEnable){
	require_once('modules/FirePHPCore/FirePHP.class.php');
	require_once('modules/FirePHPCore/fb.php');
	ob_start();

	$firephp = FirePHP::getInstance(true);
}
//liukun add for bug 52 end
if ($_U['query_type'] == "add"){
	
	//���������� 20121110 add by weego 
	//---------------apc�߲����ڴ湲��������--------------------------
	//liukun add for bug 296 begin
	 
//	require_once(ROOT_PATH."core/slock.class.php");
//	$lockTenderNo="addborrow"; //����addborrow�������������Ŷӽ���
//	$lock = new slock();
//	$lock->lock($lockTenderNo);
 
	//liukun add for bug 296 end
	//---------------apc�߲����ڴ湲��������--------------------------
	
	//��֤�û��Ƿ��з����� add by weego 20120613
	$result = borrowClass::GetOnes(array("user_id"=>$_G['user_id']));
	if (isset($_POST['is_vouch']) && intval($_POST['is_vouch'])==1){
		if($_POST['vouch_user']!=""){
			$vouch_user = explode("|",$_POST['vouch_user']);
			$un = "";
			foreach ($vouch_user as $v){
				$un .= $un==""?"'".$v."'":",'".$v."'";
			}
			$sql = "select username from {user} where username in({$un})";
			$re = $mysql->db_fetch_arrays($sql);
			$user_tmp = array();
			foreach($re as $value){
				$user_tmp[] = $value['username'];
			}
			$no_user = "";
			foreach($vouch_user as $value){
				if(!in_array($value,$user_tmp)){
					$no_user .= $no_user==""?$value['username']:"��".$value['username'];
				}
			}
			$vouch_user = false;
		}else{
			$vouch_user = true;
		}
	}else{
		$vouch_user = false;
		$no_user = "";
	}
	if (!isset($_POST['name'])){
		$msg = array("�벻Ҫ�Ҳ���","","/publish/index.html");
	}elseif (strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
		$msg = array("��֤�벻��ȷ");
	}elseif($_POST['style']==1 && $_POST['time_limit']%3!=0){
		$msg = array("��ѡ����ǰ�����������������д3�ı���");
    }elseif($_POST['award']==1 && $_POST['part_account']<5){
        $msg = array("��ѡ����ǰ�����������д�������ֵ(���ܵ���5Ԫ)");
    }elseif($_POST['award']==2 && $_POST['funds'] < 0.1){
        $msg = array("��ѡ����ǰ���������������д��������ֵ( 0.1% ~ 6% )");
    }elseif(isset($_POST['isDXB']) && (!isset($_POST['pwd']) || $_POST['pwd'] == "" ) ){
        $msg = array("��ѡ���˶���꣬�����붨��������.");
    }elseif (isset($_POST['is_lz']) && $_POST['account']%100!=0){
    	$msg = array("��ת��Ľ���������100��������.");
    }elseif($vouch_user){
    	$msg = array("�����˲���Ϊ��");
    }elseif($no_user!=""){
    	$msg = array("�����˲�����");
    }else{
    	unset($_SESSION['valicode']);
		$var = array("name","use","time_limit","style","account","apr","lowest_account","most_account","valid_time","award","part_account","funds","is_false","open_account","open_borrow","open_tender","open_credit","content","is_vouch","vouch_user","zjyz","fxkzcs","zcjscfx","qybj","qyxx");
		$data = post_var($var);
		$data['content_assets'] = $_POST['content_assets'];
		$data['content_safeguard'] = $_POST['content_safeguard'];
		if(isset($_POST['ismb'])){
			$data['time_limit'] = 1;
			$data['style'] = 0;
			$data['is_mb'] = intval($_POST['ismb']);
		}
		if(isset($_POST['isjin'])){
			$data['is_jin'] = intval($_POST['isjin']);
		}
		if(isset($_POST['isfast'])){
			$data['is_fast'] = intval($_POST['isfast']);
		}
		if(isset($_POST['is_vouch'])){
			$data['is_vouch'] = intval($_POST['is_vouch']);
			$data['vouch_user'] = $_POST['vouch_user'];
			$data['vouch_user_phone'] = $_POST['vouch_user_phone'];
		}
		if(isset($_POST['is_lz'])){
			$data['is_lz'] = intval($_POST['is_lz']);
			$data['lowest_account'] = 100;
			$data['style'] = 0;
		}
		if(isset($_POST['isxin'])){
			$data['is_xin'] = intval($_POST['isxin']);
		}
		
		//���� add by weego for ���  20120513
		if((int)$_POST['isday']==1){
			//liukun add for bug 324 begin
			$data['style'] = 0;
			//liukun add for bug 324 end
			$data['time_limit'] = 1;
			$data['time_limit_day'] = intval($_POST['time_limit_day']);
			$data['time_limit_day'] = $data['time_limit_day']?$data['time_limit_day']:30;
			$data['isday'] = intval($_POST['isday']);
		}
		//���� add by jackfeng for �������� 20120716

        //����� ����
        if(isset($_POST['pwd'])){
            if(isset($_POST['pwd']) && $_POST['pwd'] != ""){
                  $data['pwd'] = htmlspecialchars($_POST['pwd']);
            }
       	}
		//liukun add for bug 294 begin ���ֲ�����������ʱָ����������    
        if(isset($_POST['biao_type'])){
        	$data['biao_type'] = $_POST['biao_type'];
        }
		//liukun add for bug 294   end  ���ֲ�����������ʱָ����������              
		$data['open_account'] = 1;
		$data['open_borrow'] = 1;
		$data['open_credit'] = 1;
		/*
		if ($_POST['submit']=="����ݸ�"){
			$data['status'] = -1;
		}else{
			$data['status'] =0;
		}*/
		$data['user_id'] = $_G['user_id'];
		if ($_U['query_type'] == "add"){
			//��ͬ��
			$data['p_contract_no'] = 'bht'.time().$_G['user_id'];
			$result = borrowClass::Add($data);
		}else{
			$data['id'] = $_POST['id'];
			$data['user_id'] = $_G['user_id'];
			//$result = borrowClass::Update($data);
		}
		if ($result===true){
			if($data['is_fast']==1){
				$msg = array("���귢���ɹ���","��������������","/index.php?user&q=code/borrow/unpublish");
			}else if($data['is_vouch']==1){
				$msg = array("���귢���ɹ���","��������������","/index.php?user&q=code/borrow/unpublish");
			}else{
				$msg = array("���귢���ɹ���","","/index.php?user&q=code/borrow/unpublish");
			}
		}else{
			$msg = array($result);
		}
	}
	//---------------apc�߲����ڴ湲�����ر�--------------------------
	//liukun add for bug 296 begin
	 
//	$lock->release($lockTenderNo);
	 
	//���������� add by weego 20121110
	//---------------apc�߲����ڴ湲�����ر�--------------------------

	
}elseif ($_U['query_type'] == "cancel"){
	$data['id'] = (int)$_REQUEST['id'];
	$data['user_id'] = $_G['user_id'];
	$result = borrowClass::Cancel($data);
	if(is_bool($result)){
		if($result==false){
			$msg = array("����ʧ��!","","index.php?user&q=code/borrow/publish");
		}else{
			$msg = array("�����ɹ�!","","index.php?user&q=code/borrow/publish");
		}
	}else{
		$msg = array($result,"","index.php?user&q=code/borrow/publish");
	}
}

//ɾ��

elseif ($_U['query_type'] == "del"){
	$data['id'] = intval($_GET['id']);
	$data['user_id'] = intval($_G['user_id']);
	$result=false;
	if($data['id']>0 && $data['user_id']>0){
		$result = $mysql->db_query('update {borrow} set status=5 where id='.$data['id'].' and status=0 and user_id='.$data['user_id']);
	}
	//$data['status'] = -1;
	//$result = borrowClass::Delete($data);
	if ($result==false){
		$msg = array("�б곷��ʧ��!");
	}else{
		$msg = array("�б곷�سɹ�!","","/index.php?user&q=code/borrow/unpublish");
	}
}

//��ת���Զ��ع� add by weego 20121208 
elseif ($_U['query_type'] == "autoReBackbuy"){
	$data='';
	$result = borrowClass::autoLZRepay($data);
	if($result==false){
		$msg = array("�ع�ʧ��!");
	}else{
		$msg = array("�ع��ɹ�!");
	}
}
//�û�Ͷ��
elseif ($_U['query_type'] == "tender"){
	//Ͷ���� 20121010 add by weego 
	//liukun add for bug 296 begin
	if(1==2){
	require_once(ROOT_PATH."core/slock.class.php");
	$_POST['id']=(int)$_POST['id'];

	$lockTenderNo=$_POST['id']; //���ݱ��id�������������Ŷӽ���
	//---------------apc�߲����ڴ湲��������--------------------------
	$lock = new slock();
	$lock->lock($lockTenderNo);
	}
	//liukun add for bug 296 end
	 
    //$borrow_result = borrowClass::GetOne(array("id"=>$_POST['id'],"tender_userid"=>$_G['user_id']));//��ȡ����ĵ�����Ϣ
	if ($_POST['yzmcode']!=$_SESSION['valicode'] && $_POST['yzmcode']==''){//��� ��֤�� by timest 2012-10-11
		$msg = array("��֤�����");
	}elseif (md5($_POST['paypassword'])!=$_G['user_result']['paypassword']){
		$msg = array("֧���������벻��ȷ");
	}
	else{
		unset($_SESSION['valicode']);
		include_once(ROOT_PATH."modules/account/account.class.php");
		$borrow_result = borrowClass::GetOne(array("id"=>$_POST['id'],"tender_userid"=>$_G['user_id']));//��ȡ����ĵ�����Ϣ
		$is_lz=$borrow_result['is_lz'];
		if($is_lz==1){
			$account_money = (int)$_POST['flow_count']*100;
			$postmoney = (int)$_POST['flow_count']*100;
		}else{
			$account_money = $_POST['money'];
			$postmoney = $_POST['money'];
		}
		//���������
		$dxbPWD = $_POST['dxbPWD'];
		
		//liukun add for bug 151 begin
		//1.�������ʣ���Ͷ����
		$can_account = $borrow_result['account'] - $borrow_result['account_yes'];
		//2.�������Ͷ��ɣ��뱾�ͻ��ۼ�Ͷ����֮��Ĳ���
		//ϵͳ��ʹ��0��ʾͶ���������ƣ�Ϊ�˱��ڼ��㣬������һ��ת��������0ʱ��ֱ��ת��Ϊ10000,0000
		if ($borrow_result['most_account']==0){
			$borrow_result['most_account']=100000000;
		}
		$can_single_account = $borrow_result['most_account'] - $borrow_result['tender_yes'];
		//3.�жϸ�����СͶ����ʣ��Ͷ�꣬ȡ�����е�С��Ϊ��СͶ����
		$lowest_account = $borrow_result['lowest_account'];


		//add yjf ��ȡ�ύ����δ���ص�Ͷ���¼���йܣ�
		//$s = $mysql->db_fetch_array("select sum(account) as account from {borrow_tender} where status=-1 and  borrow_id={$_POST['id']} and addtime>".(time()-600));
		//$can_account -= $s['account'];
		
		if($can_account < $lowest_account){
			$lowest_account = $can_account;
		}

		if($can_single_account < $lowest_account){
			$lowest_account = $can_single_account;
		}


		//���ʣ��Ͷ����С����СͶ��������ʾ����Ͷ������һ������ʱ��ʵ��Ͷ����ʣ����Ϊ׼��������Ͷ�������
		if ($account_money > $can_account){
			$account_money = $can_account;
		}
		//���Ͷ������ڸ��˻���Ͷ���ʵ��Ͷ����Ϊ���˻���Ͷ����
		if ($account_money > $can_single_account){
			$account_money = $can_single_account;
		}
		//add by jackfeng 2012-10-08 ���ٱ�
		$kuai =  $borrow_result['is_kuai'];
		$cashKuaiMoney = 0;
		//add yjf
		$classname = $borrow_result['biao_type']."biaoClass";
		$dynaBiaoClass = new $classname();
		$biao_type = $dynaBiaoClass->get_biaotype_info();
		$max_tender_number = $biao_type['max_tender_number'];//Ͷ����������
		$is_vip_render = $biao_type['is_vip_render'];//�Ƿ�vip����Ͷ��
		$tenders_times = 0;//Ͷ������
		if($max_tender_number>0){
			$tenders_userid = $mysql->db_fetch_arrays("select user_id from `{borrow_tender}` where borrow_id={$_POST['id']}");
			$ct  = count($tenders_userid,0);
			$array_tender=array();
			for ($i=0; $i<$ct; $i++){
				$array_tender[] = $tenders_userid[$i]['user_id'];
			}
			$array_tender = array_unique($array_tender);
			if (in_array($_G['user_id'], $array_tender)){
				$tenders_times = count($array_tender);
			}else{
				$tenders_times = count($array_tender)+1;
			}
		}
		if ($is_vip_render==1 && $_G['user_result']['vip_status']!=1){
			$msg = array("�˱�ֻ��vip����Ͷ�꣡");
		}elseif ($tenders_times>$max_tender_number){
			$msg = array("�˱��ѴﵽͶ���������ޣ�");
		}elseif($_G['user_id'] == $borrow_result['user_id']){
			$msg = array("�Լ�����Ͷ�Լ������ı꣡");
		}elseif ($_G['user_result']['islock']==1){
			$msg = array("���˺��Ѿ������������ܽ���Ͷ�꣬�������Ա��ϵ");
		}elseif (!is_array($borrow_result)){
			$msg = array($borrow_result);
		}elseif ($borrow_result['account_yes']>=$borrow_result['account']){
			$msg = array("�˱�������������Ͷ");
		}elseif ($borrow_result['verify_time'] == "" || $borrow_result['status'] != 1){
			$msg = array("�˱���δͨ�����");
		}elseif($kuai == 1 && $cashKuaiMoney<$account_money){
			$msg = array("���ã�����Ͷ������ڿ������㵱ǰ��Ͷ���ٱ�Ŀ����ʽ�(�˱귢��������³�ֵ�ʽ����Ͷ��)");
		}
		//liukun add for bug ������ԶҲ�������㣬��Ϊ$borrow_result['valid_time']����Ч������
		//elseif ($borrow_result['verify_time'] + $borrow_result['valid_time']>time()){
		elseif (($borrow_result['verify_time'] + $borrow_result['valid_time'] * 3600 * 24) <time()){
			$msg = array("�˱��ѹ���");
		}
		elseif(!is_numeric($account_money)){
			$msg = array("��������ȷ�Ľ��");
		}
		//liukun add for bug 151 begin
		elseif($account_money < $lowest_account ){
			$msg = array("����Ͷ����{$account_money}����С����СͶ����{$lowest_account}");
		}
		elseif($can_single_account == 0 ){
			$msg = array("������Ͷ�����Ѿ������������{$borrow_result['most_account']}");
		}
		//liukun add for bug 151 end
		elseif($dxbPWD != $borrow_result['pwd']){
			$msg = array("������Ķ�������벻��ȷ�����򷢱���ȡ����ȷ������.");
		}
		//liukun add for bug 58 begin
		
		//liukun add for bug 58 end
		else{
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$_G['user_id']));//��ȡ��ǰ�û������
			if (($borrow_result['account']-$borrow_result['account_yes'])<$account_money){
				$account_money = $borrow_result['account']-$borrow_result['account_yes'];
			}
			if ($account_result['use_money']<$account_money){
				$msg = array("��������");
			}else{
				$data['borrow_id'] = $_POST['id'];
				$data['money'] = $postmoney;
				$data['account'] = $account_money;
				$data['user_id'] = $_G['user_id'];
				if(IS_TG){
					$data['status'] = -1;//�ȴ�����������
				}else{
					$data['status'] = 5;
				}
				$data['trade_no'] = 'tb'.time().$_G['user_id'].rand(1,100);
				$mysql->db_add("borrow_tender",$data);
				$data['tender_id'] = $mysql->db_insert_id();
				$j = $mysql->db_fetch_array("select card_id,realname,pIpsAcctNo from {user} where user_id=".$borrow_result['user_id']);
				//$s = $mysql->db_fetch_arrays('select * from {linkage} where type_id=34');

				$r = tg_tender($borrow_result, $j, $_G['user_result'], $data);//�й�
				unset($data['tender_id']);
				$result = borrowClass::AddTender($data);//��ӽ���
				if ($result === true){
					if ($borrow_result['status'] ==1 && ($borrow_result['account_yes'] + $account_money) >= $borrow_result['account'] && $borrow_result['is_lz']!=1){
						$classname = $borrow_result['biao_type']."biaoClass";
						$dynaBiaoClass = new $classname();
						$auto_full_verify_result = $dynaBiaoClass->get_auto_full_verify($borrow_result['biao_type']);
						if ($auto_full_verify_result==1){
							$data_e['id'] = $_POST['id'];
							$data_e['status'] = '3';
							$data_e['repayment_remark'] = '�Զ�����';
							borrowClass::AddRepayment($data_e);
						}
					}
					$msg = array("Ͷ��ɹ�","","/index.php?user&q=code/borrow/bid");
				}else{
					if(is_bool($result) && $result==false){
						$msg = array("Ͷ��ʧ��");
					}else{
						$msg = array($result);
					}
				}
			}
		}
	}
	//---------------apc�߲����ڴ湲�����ر�--------------------------
	//liukun add for bug 296 begin
	if(1==2){
	$lock->release($lockTenderNo);
	}
	//Ͷ���� add by weego 20121010
}
//������Ͷ��
elseif ($_U['query_type'] == "vouch"){
	$msg = "";
	//if ($_SESSION['valicode']!=$_POST['valicode']){
        if(1==2){
		$msg = array("��֤�����");
	}else if (1==2){
		include_once(ROOT_PATH."modules/account/account.class.php");
		$borrow_result = borrowClass::GetOne(array("id"=>$_POST['id'],"tender_userid"=>$_G['user_id']));//��ȡ����ĵ�����Ϣ
		
		$vouch_account = $_POST['money'];
		if (($borrow_result['account']-$borrow_result['vouch_account'])<$vouch_account){
			$account_money = $borrow_result['account']-$borrow_result['vouch_account'];
		}else{
			$account_money = $vouch_account;
		}
		
		$uacc = borrowClass::GetUserLog(array('user_id'=>$_G['user_id']));
		
		if ($_G['user_result']['islock']==1){
			$msg = array("���˺��Ѿ������������ܽ��е������������Ա��ϵ");
		}elseif (!is_array($borrow_result)){
			$msg = array($borrow_result);
		}elseif ($uacc['total']<$account_money){
			$msg = array("�����ʻ��ܶ�С�����뵣�����ܽ����ܵ���");
		}elseif ($borrow_result['vouch_account']>=$borrow_result['account']){
			$msg = array("�˵����굣����������������ٵ���");
		}elseif ($borrow_result['verify_time'] == "" || $borrow_result['status'] != 1){
			$msg = array("�˱���δͨ�����");
		}elseif ($borrow_result['verify_time'] + $borrow_result['valid_time']>time()){
			$msg = array("�˱��ѹ���");
		}elseif (md5($_POST['paypassword'])!=$_G['user_result']['paypassword']){
			$msg = array("֧���������벻��ȷ");
		}else{
			//��ȡͶ�ʵĵ������borrowClass::GetUserLog
			$vouch_amount =  borrowClass::GetAmountOne($_G['user_id'],"tender_vouch");
			
			if ($vouch_amount['account_use']<$account_money){
				$msg = array("���ĵ�������");
			}else{
				$data['borrow_id'] = $_POST['id'];
				$data['vouch_account'] = $vouch_account;
				$data['account'] = $account_money;
				$data['user_id'] = $_G['user_id'];
				$data['content'] = $_POST['content'];
				$data['status'] = 0;
				
				//�ж��Ƿ��ǵ�����
				if ($borrow_result['vouch_user']!=""){
					$_vouch_user = explode("|",$borrow_result['vouch_user']);
					if (!in_array($_G['user_result']['username'],$_vouch_user)){
						$msg = array("�˵������Ѿ�ָ���˵����ˣ��㲻�Ǵ˵����ˣ����ܽ��е���");
					}
				}
				if ($msg==""){
					$result = borrowClass::AddVouch($data);//��ӵ�����
					if ($result==false){
						$msg = array($result);
					}else{
						$msg = array("�����ɹ�","","/index.php?user&q=code/borrow/bid");
						unset($_SESSION['valicode']);
					}
				}
			}
		}
	}
	elseif ($_G['user_result']['islock']==1){
		$msg = array("���˺��Ѿ������������ܽ��е������������Ա��ϵ");
	}
	else{
	
		$result = borrowClass::AddVouch($_POST);//array("borrow_id"=>$_POST['id'],"tender_userid"=>$_G['user_id']));//��ӵ�����
	
		if ($result===true){
			$msg = array("�����ɹ�","","/index.php?user&q=code/borrow/bid");
			unset($_SESSION['valicode']);
		}else{
			$msg = array($result);
		}
	}
	
}
/*
 * �鿴��ĳ������
 */
elseif($_U['query_type'] == "borrow_verify"){
	$id = $_GET['borrow_id'];
	if($id>0){
		$_U['borrow_result'] = $mysql->db_fetch_array("select * from `{borrow}` where id=$id");
		$_U['borrow_shus_result'] = $mysql->db_fetch_arrays("select * from {attestation} where borrow_id={$id} and upload_type=4");
	}
	$magic->assign("_U",$_U);
	$magic->display("user_borrow_verify.php");
	exit();
}
/*
 * �ϴ����ϵ���,�ȴ�����ʱ�ϴ�������
 */
elseif ($_U['query_type'] == "borrow_upzl"){
	if(isset($_POST['borrow_id'])){
		$type_arr = array(0=>"",1=>'jkxy',2=>'cns',3=>'yqcfqrs');
		$type_name = array(1=>"���Э��",2=>"����ŵ��",3=>"���ڴ���ȷ����");
		$type_id = array(1=>93,2=>4,3=>94);
		$biao_zl_type = $_POST['biao_zl_type'];
		
		$data['borrow_id']=intval($_POST['borrow_id']);
		$data['user_id']=intval($_G['user_id']);
		$data['file']="biao_zl_file";
		$num = 0;
		foreach($_FILES['biao_zl_file']['error'] as $key=>$value){
			if($value==0){
				$file_type = $type_arr[$biao_zl_type[$key]];
				if($file_type!=""){
					$data['key']=$key;
					$data['name']=$type_name[$biao_zl_type[$key]];
					$data['type_id']=$type_id[$biao_zl_type[$key]];
					$data['upload_type']=3;
					$re = $upload->borrow_qr_zl($data);
					if ($re==true){
						$num++;
					}
				}
			}
		}
		$msg = array("�����ɹ�,���ϴ�{$num}���ļ�");
	}else{
		$borrow_id = $_GET['borrow_id'];
		$re = $mysql->db_fetch_array("select id from {borrow} where id={$borrow_id} and status=1");
		if($re==false){
			$_U['borrow_exists']=0;
		}else{
			$_U['borrow_exists']=1;
			$_U['file_re'] = borrowClass::GetBorrowProtocol(array('borrow_id'=>$borrow_id));
		}
		$template = "user_borrow_verify.php";
		$magic->assign("_U",$_U);
		$magic->display($template);
		exit();
	}
}
/*
 * �ϴ����ϵ���,�ȴ�����ʱ�ϴ�������
 */
elseif ($_U['query_type']=="borrow_upzlshus"){
	if(isset($_POST['borrow_id'])){
		$data['borrow_id']=intval($_POST['borrow_id']);
		$data['user_id']=intval($_G['user_id']);
		$data['file']="biao_zlchus_file";
		$biao_zlchus_name = $_POST['biao_zlchus_name'];
		$num = 0;
		foreach($_FILES['biao_zlchus_file']['error'] as $key=>$value){
			if($value==0){
				$file_name = $biao_zlchus_name[$key];
				if($file_name==""){
					$file_name = "�����������";
				}
				$data['key']=$key;
				$data['name']=$file_name;
				$data['type_id']=2;//�������˵��
				$data['upload_type'] = 4;
				$re = $upload->borrow_qr_zl($data);
				if ($re==true){
					$num++;
				}
			}
		}
		$msg = array("�����ɹ�,���ϴ�{$num}���ļ�");
	}
}
//�鿴��Ļ�����Ϣ
elseif ($_U['query_type'] == "repayment_view"){
	$data['id'] = $_GET['id'];
	if ($data['id']==""){
		$msg = array("������������");
	}
	$data['user_id'] = $_G['user_id'];
	$result =  borrowClass::GetOne($data);
	if ($result==false){
		$msg = array("���Ĳ�������");
	}else{
		$_U['borrow_result'] = $result;
	}
}
//����
elseif ($_U['query_type'] == "repay"){
	$data['id'] = $_POST['id'];
	if ($data['id']==""){
		$msg = array("������������");
	}elseif ($_POST['yzmcode']!=$_SESSION['valicode'] || $_POST['yzmcode']==''){//��� ��֤�� by timest 2012-10-11
		$msg = array("��֤�����");
	}elseif (md5($_POST['paypassword'])!=$_G['user_result']['paypassword']){
			$msg = array("֧���������벻��ȷ");
	}
	else{
		$data['user_id'] = $_G['user_id'];
		$a = $mysql->db_fetch_array('select status from {borrow_repayment} where id='.$data['id']);
		if($a['status']==2){
			//$result =  borrowClass::Repay($data);
		}else{
			$result = borrowClass::tg_Repay($data);//�й�
			if($result===true){
				$result = borrowClass::Repay($data);
			}
		}
		
		if (is_bool($result)){
			if($result==false){
				$msg = array("����ʧ��","","/index.php?user&q=code/borrow/repayment");
			}else{
				$msg = array("�����ɹ�","","/index.php?user&q=code/borrow/repayment");
			}
		}else{
			$msg = array($result,"","/index.php?user&q=code/borrow/repayment");
		}
	}
}
//ͳһ����
elseif ($_U['query_type'] == "multirepay"){
	exit();
	$data['id'] = $_POST['id'];
	if ($data['id']==""){
		$msg = array("������������");
	}else{
		$id = $data['id'];

		array_multisort($id, SORT_ASC);


		foreach($id as $key => $value){
			$data['user_id'] = $_G['user_id'];
			$data['id'] = $value;
			$result =  borrowClass::Repay($data);

		}

		if($result===true){
			$msg = array("�����ɹ�","","/index.php?user&q=code/borrow/repayment");
		}else{
			$msg = array("����ʧ��","","/index.php?user&q=code/borrow/repayment");
		}
	}
}
//�������
/*
elseif ($_U['query_type'] == "limitapp"){
	if (isset($_POST['account']) && $_POST['account']>0){
		$var = array("account","content","type","remark");
		$data = post_var($var);
		$data['user_id'] = $_G['user_id'];
		$result = borrowClass::GetAmountApplyOne(array("user_id"=>$data['user_id'],"type"=>$data['type']));
		if ($result!=false && $result['verify_time']+60*60*24*30 >time()){
			$msg = array("��һ���º�������");
		}elseif ($result!=false && $result['addtime']+60*60*24*30 >time() && $result['status']==2){
			$msg = array("���Ѿ��ύ�����룬��ȴ����");
		}else{
			if(isset($_FILES['credit_file'])){
				$_G['upimg']['file'] = "credit_file";
				$_G['upimg']['code'] = "credit";
				$re = $upload->upfile($_G['upimg']);
				$credit_file = '';
				if(is_array($re)){
					foreach ($re as $v){
						$credit_file .= '|'.$v['filename'];
					}
				}
				$data['credit_file'] = substr($credit_file, 1);
			}
			$data['status'] = 2;
			$result =  borrowClass::AddAmountApply($data);//��ȡ��ǰ�û������
			if ($result!==true){
				$msg = array($result);
			}else{
				$msg = array("�������ɹ�����ȴ�����Ա���","","/index.php?user&q=code/borrow/limitapp");
			}
		}
	}
}
*/
/*
//�����Զ�Ͷ��
elseif ($_U['query_type'] == "auto_add"){
	$_POST['user_id'] = $_G['user_id'];
	$_POST['addtime'] = time();
	$re = borrowClass::add_auto($_POST);
	if($re===false){
		$msg = array("���Ѿ������1���Զ�Ͷ�꣬���ֻ�����1����������ɾ�������޸�","","/index.php?user&q=code/borrow/auto");
	}else{
		$msg = array("�Զ�Ͷ�����óɹ�","","/index.php?user&q=code/borrow/auto");
	}
}

//�޸��Զ�Ͷ��
elseif ($_U['query_type'] == "auto_new"&&is_numeric($_GET['id'])){
	$result = borrowClass::GetAutoId($_GET['id']);
	$_U['auto_result'] = $result;
}

//ɾ���Զ�Ͷ��
elseif ($_U['query_type'] == "auto_del"&&is_numeric($_GET['id'])){
	$result = borrowClass::del_auto($_GET['id']);
	if($result) $msg = array("�Զ�Ͷ��ɾ���ɹ�","","/index.php?user&q=code/borrow/auto");
}
*/
elseif($_U['query_type']=='assure_password'){
	$assure_password = isset($_GET['assure_password'])?$_GET['assure_password']:'';
	$borrow_id = (int)$_GET['borrow_id'];
	if($assure_password=='' || $borrow_id<1){
		echo 0;
	}else{
		$a = $mysql->db_fetch_array('select assure_password,user_id from {borrow} where is_assure=1 and id='.$borrow_id);
		if(isset($a['user_id']) && $a['user_id']==$_G['user_id']){
			echo -1;
		}elseif(isset($a['assure_password']) && $a['assure_password']==$assure_password){
			$mysql->db_query("update {borrow} set vouch_user='{$_G['user_id']}' where id={$borrow_id}");
			echo 1;
		}else{
			echo 0;
		}
	}
	exit();
}
elseif($_U['query_type']=='borrow_updbzl'){
	$borrowid = intval($_GET['borrow_id']);
	$type_arr = array('dbzl');
	$type = $_GET['zl_type'];
	if(!in_array($type,$type_arr)){
		exit();
	}
	if(isset($_FILES) && !empty($_FILES)){
		$_G['upimg']['user_id'] = $_G['user_id'];
		$_G['upimg']['file'] = "admin_up";
		$_G['upimg']['cut_status'] = 0;
		$_G['upimg']['code'] = $type;
		$pic_result = $upload->upfile($_G['upimg']);
		if ($pic_result!=""){
			$sql = 'select '.$type.' from {borrow} where id='.$borrowid;
			$re = $mysql->db_fetch_array($sql);
			if($re[$type]!=''){
				$re = unserialize($re[$type]);
			}else{
				$re = array();
			}
			$i = count($re);
			$re[$i] = array('name'=>$_POST['filename'],'litpic'=>$pic_result['filename'],'id'=>$i);
			$re = serialize($re);
			$mysql->db_query("update {borrow} set {$type}='{$re}' where id={$borrowid} limit 1");
		}
	}
	$re = $mysql->db_fetch_array('select '.$type.',status from {borrow} where id='.$borrowid);
	$_A['borrow_status']=$re['status'];
	if($re[$type]==''){
		$re = array();
	}else{
		$re = unserialize($re[$type]);
	}
	$_A['attestation_list'] = $re;
	$template = "../mamger/borrow_attestation.html";
	$magic->assign("_A",$_A);
	$magic->display($template);
	exit();
}
elseif($_U['query_type'] == "succes" || $_U['query_type'] == "gathering" || $_U['query_type'] =="lenddetail" || $_U['query_type']=="publish" || $_U['query_type'] =="unpublish" || $_U['query_type'] =="bid" || $_U['query_type']=='repaymentplan' || $_U['query_type']=='repayment' || $_U['query_type']=='loandetail' || $_U['query_type']=='repaymentyes'){
	
}
else{
	$msg = array("ҳ�治����","","/index.php?user");
}

$template = "user_borrow.html.php";
if($_U['query_type'] == "auto"||$_U['query_type'] == "auto_new")  $template = "auto_user_borrow.html.php";
?>
