<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
include_once("account.class.php");

//if (isset($_POST['valicode']) && $_POST['valicode']!=$_SESSION['valicode']){
//临时取消验证码 add by jackfeng 2012-09-07
if(1==2){
		$msg = array("验证码错误","",$_U['query_url']."/".$_U['query_type']);
}else{
	if ($_U['query_type'] == "list"){
		
	}
	
	elseif ($_U['query_type'] == "log"){

		$data['user_id'] = $_G['user_id'];
		$data['page'] = $_U['page'];
		$data['epage'] = 20;
 		$data['dotime1'] = isset($_GET['dotime1'])?$_GET['dotime1']:"";
		$data['dotime2'] = isset($_GET['dotime2'])?$_GET['dotime2']:"";
		$data['type'] = isset($_GET['type'])?$_GET['type']:"";
 		$data['keywords'] = isset($_GET['keywords'])?$_GET['keywords']:"";
		$result = accountClass::GetLogList($data);
		if (is_array($result)){
			$pages->set_data($result);
			$_U['account_log_list'] = $result['list'];
			$_U['show_page'] = $pages->show(3);
			$_U['account_num'] = $result['account'];
		}else{
			$msg = array($result);
		}
	}
	
	elseif ($_U['query_type'] == "logold"){

		$data['user_id'] = $_G['user_id'];
		$data['page'] = $_U['page'];
		$data['epage'] = 20;
		$data['dotime1'] = isset($_REQUEST['dotime1'])?$_REQUEST['dotime1']:"";
		$data['dotime2'] = isset($_REQUEST['dotime2'])?$_REQUEST['dotime2']:"";
		$data['type'] = isset($_REQUEST['type'])?$_REQUEST['type']:"";
		$result = accountClass::GetLogListOld($data);
		if (is_array($result)){
			$pages->set_data($result);
			$_U['account_log_list'] = $result['list'];
			$_U['show_page'] = $pages->show(3);
			$_U['account_num'] = $result['account'];
		}else{
			$msg = array($result);
		}
	}
	
	elseif ($_U['query_type'] == "cash"){

		$data['user_id'] = $_G['user_id'];
		$result = accountClass::GetUserLog($data);
		$_U['cash_log'] = $result;
		$data['page'] = $_U['page'];
		$data['epage'] = $_U['epage'];
		$result = accountClass::GetCashList($data);
		if (is_array($result)){
			$pages->set_data($result);
			$_U['account_cash_list'] = $result['list'];
			$_U['show_page'] = $pages->show(3);;
		}else{
			$msg = array($result);
		}
	}
	
	elseif ($_U['query_type'] == "recharge"){
		$result = accountClass::GetUserLog(array("user_id"=>$_G['user_id']));
		$_U['account_log'] = $result;
	}
	
	elseif ($_U['query_type'] == "recharge_new"){
		if(IS_TG){
			
			include_once(ROOT_PATH."modules/payment/payment.class.php");
		}else{
			include_once(ROOT_PATH."modules/payment/payment.class.php");
		}
		if(IS_TG==true && $_G['user_result']['pIpsAcctNo']==''){
			$msg = array("您还未开通宝付专属账户，请先开通","立即开通",'/index.php?user&q=code/user/openAnAccount');
		}
		elseif(isset($_POST['money']) && IS_TG){
			//$a = tg_getaccount($_G['user_result']);
			if(!is_numeric($_POST['money'])){
				$msg = array("金额填写有误","",$_U['query_url']."/".$_U['query_type']);
			}else{
				$data['user_id'] = $_G['user_id'];
				$data['type'] = 1;
				$data['reward'] = 0;
				$data['fee'] = 0;
				$data['trade_no'] = time().$_G['user_id'].rand(1,9);
				$data['status'] = 0;
				$data['money'] = $_POST['money'];
				$data['remark'] = '线上充值';
				$result = accountClass::AddRecharge($data);
				if($result){
					/*
					$para['pMerBillNo'] = $data['trade_no'];//商户充值订单号,必填
					$para['pIdentNo'] = $_G['user_result']['card_id'];//证件号码,真实身份证（个人）,必填
					$para['pRealName'] = $_G['user_result']['realname'];//姓名,必填
					$para['pMobileNo'] = $_G['user_result']['phone'];//手机号码
					$para['pIpsAcctNo'] = $_G['user_result']['pIpsAcctNo'];//IPS 账户号,账户类型为 1 时，IPS 账号（个人/商户）账户类型为 0 时，平台账号,必填
					$para['pTrdDate'] = date("Ymd",time());//充值日期,格式：YYYYMMDD,必填
					//$para['pTrdBnkCode'] = $_POST['pTrdBnkCode'];//充值银行code;
					$para['pTrdAmt'] = $data['money'];//充值金额,金额单位：元,格式：12.00,必填
					$para['pMerFee'] = $data['fee'];//平台手续费,平台收取的费用,格式：12.00,必填
					*/
					tg_recharge($data, $_G['user_result']);

				}else{
					$msg = array("操作失败，请重试","",$_U['query_url']."/".$_U['query_type']);
				}
			}
		}
		elseif (isset($_POST['money']) && IS_TG==false){
			if($_POST['type']==2 && $_POST['payment2']==''){
				$msg = array("请选择线下充值的银行");
			}else{
			$data['user_id'] = $_G['user_id'];
			$data['type'] = $_POST['type'];
			$data['status'] = 0;
			$data['money'] = $_POST['money'];
			//1)使用盛付通
			$payment_type = '';
			$payment = explode("_",$_POST['payment1']);
			if(count($payment)==2){  // 通过银行连接
				if($payment[1]=='g'){  //  xx_g 结尾，通过国付宝 连接
					$bco = $payment[0];
					$_POST['payment1']=32;
					$payment_type = 'gopay';
				}else if($payment[1] == 's'){ //xx_s 结尾，通过IPS 连接
					$bco = $payment[0];
					$_POST['payment1'] = 10;
					$payment_type = 'IPS';
				}else if($payment[1] == 't'){	//xx_t 结尾，通过盛付通连接
					$bco = $payment[0];
					$_POST['payment1'] = 47;
					$payment_type = 'sto';
				}else if($payment[1] == 'xs'){	  //xx_xs  新生 连接
					$bco = $payment[0];
					$_POST['payment1'] = 9;
					$payment_type = 'hna';
				}
			}
			if (is_numeric($data['money'])){
				$data['remark'] = $_POST['remark'];
				$data['type'] = $_POST['type'];
				$url = "";
				$data['reward'] = 0;
				$data['fee'] = 0;
				if ($data['type']==1){
					$re = $mysql->db_fetch_array("select * from `{payment}` where id={$_POST['payment1']}");
					$data['payment'] = $_POST['payment1'];
					$data['remark'] = $_POST['payname'.$_POST['payment1']]."在线充值";
					if($re['fee_type']==1){
						$data['fee']=$re['max_money'];
					}else{
						$data['fee']=$data['money']*$re['max_fee'];
					}
				}else{
					$re = $mysql->db_fetch_array("select * from `{payment}` where id={$_POST['payment2']}");
					$data['payment'] = $_POST['payment2'];
					if($re['fee_type']==1){
						$data['fee']=$re['max_money'];
					}else{
						$data['fee']=$data['money']*$re['max_fee'];
					}
					if($re['reward']==1 && $data['money']>=$re['reward_where']){
						if($re['reward_type']==1){
							$data['hongbao'] = $data['money']*$re['reward_bl'];
						}elseif($re['reward_type']==2){
							$data['hongbao'] = $re['reward_ed'];
						}
					}elseif ($re['reward']==2 && $data['money']>=$re['reward_where']){
						if($re['reward_type']==1){
							$data['reward'] = $data['money']*$re['reward_bl'];
						}elseif($re['reward_type']==2){
							$data['reward'] = $re['reward_ed'];
						}
					}
				}
				$data['reward'] = round($data['reward'],2);
				$data['fee'] = round($data['fee'],2);
				$data['trade_no'] = time().$_G['user_id'].rand(1,9);
				$result = accountClass::AddRecharge($data);

				if ($data['type']==1){
					if(isset($bco)) {
						if($payment_type == 'gopay'){
							$data['bankCode'] = $bco;//使用国付宝
						}elseif($payment_type == 'hna'){
							$data['bankCode'] = $bco;//海南新生
						}
						else if($payment_type == 'sto'){
							$data['InstCode'] = $bco;
							$data['PayType']='PT001';
						}//使用盛付通
					}//timest 2012-08-31
					
					$data['subject'] = "账号充值";
					$data['body'] = "账号充值";
					$url = paymentClass::ToSubmit($data);
				}

				if ($result!==true){
					$msg = array($result,"",$_U['query_url']."/".$_U['query_type']);
				}else{
					if ($url!=""){
						header("Location: {$url}");
						exit;
					$msg = array("网站正在转向支付网站<br>如果没反应，请点击下面的支付网站接口","支付网站",$url);
					}else{
					$msg = array("你已经成功提交了充值，请等待管理员的审核。","",$_U['query_url']."/".$_U['query_type']);
					}
				}
			}else{
				$msg = array("金额填写有误","",$_U['query_url']."/".$_U['query_type']);
			}
			}
		}else{
			//获取银行列表
			//$_U['bankList'] = HX_GetBankList();
			if(IS_TG){
				$_U['bankList'] = array();
				$_U['account_payment_list'] = paymentClass::GetList(array("status"=>1));
			}else{
				$_U['account_payment_list'] = paymentClass::GetList(array("status"=>1));
			}
		}
	}
	elseif ($_U['query_type'] == "bank"){
		if (isset($_POST['account'])){
			$var = array("user_id","account","bank","branch");
			$data = post_var($var);
			$data['account'] = str_replace(" ", "", $data['account'] );
			$sqls="select id,code,lasttime from {sms_check} where itype=2 and isuse=0 and user_id=".$_G['user_id']." and lasttime>".time()." order by id desc limit 1";
			$coderesult= $mysql->db_fetch_array($sqls);
			if ($coderesult["lasttime"]>time())
			{
				if ($coderesult["code"]==(int)$_POST['mobilecode'])
				{
					if($data['account']=="" || $data['branch']=="" || $data['bank']=="" ){
						$msg = array("请认真填写银行账号等信息，不允许为空");
					}else{
						$result = accountClass::ActionBank($data);
						if ($result!==true){
							$msg = array($result);
						}else{
							$sql = "update `{sms_check}` set isuse=1 where id=".$coderesult["id"];
							$mysql->db_query($sql); 
							$msg = array("操作成功");
						}
					}
				}else
				{
					$msg = array("手机验证码不正确！");
				}
			}else{
				$msg = array("手机验证码不正确请重新发送！");
			}
		}else{
			$data['user_id'] = $_G["user_id"];
			$result = accountClass::GetBankOne($data);
			$data_account = $result['account'];
			
			$length_of_account = strlen($data_account);//length_of_account 为 账户长度
			$str = $data_account; 
			if($length_of_account <= 5){  //长度小于5，全部为*
				for($i=0; $i<$length_of_account;$i++){
					$str[$i] ='*';
				}
			}else{  //最后5位为*
				for($i=$length_of_account-5;$i < $length_of_account;$i++){
					$str[$i] = '*';
				}
			}
			$result['account_view'] = $str;
			$_U['account_bank_result'] = $result;
		}
	}
        //得到提现费用--用于前台显示
        elseif ($_U['query_type'] == "getCashFee"){
            //include_once(ROOT_PATH."modules/borrow/borrow.class.php");
            //$data['user_id'] = $_G["user_id"];
            //$data['cashAmount'] = $_REQUEST['cashAmount'];
            //$cashFee = borrowClass::GetCashFeeAmount($data);
            echo true;
            exit;
        }
        
        
elseif ($_U['query_type'] == "cash_new"){
    
	include_once(ROOT_PATH."modules/borrow/borrow.class.php");
	$data['user_id'] = $_G["user_id"];
	$result = accountClass::GetBankOne($data);
	$_U['account_bank_result'] = $result;
	if($_G['user_result']['pIpsAcctNo']==''){
		$msg = array("您还未开通宝付账号，请先开通","立即开通","/index.php?user&q=code/user/openAnAccount");
	}
	else{
		$a = tg_getaccount($_G['user_result']);
		if(is_array($a)){
			$_U['tg_result'] = $a;
		}
		if(!is_array($a)){
			$msg = array("获取宝付账户资金失败，请重试","","/index.action?user");
		}elseif(isset($_POST['money'])){
			if(strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
				$msg = array("验证码输入有误");
			}else{
				unset($_SESSION['valicode']);
				if ($_G['user_result']['paypassword']==md5($_POST['paypassword'])){
					/*if(IS_TG){
						$data['status'] = -1;
					}else{
						$data['status'] = 0;
					}*/
					$data['status'] = 0;
					$data['total'] = round($_POST['money'],2);
					//repair by weego 20120529 for 提现负数
					if (is_numeric($data['total'])&&$data['total']>0){
						$data['account'] = $result['account'];
						$data['bank'] = '';
						$data['branch'] = '';
						$data['cash_type'] = $_POST['cash_type'];
						$data['trade_no'] = time().$_G['user_id'].rand(1,9);
						$dataCash["user_id"]=$data['user_id'];
						$dataCash["cashAmount"]=$data['total'];
						$dataCash["cash_type"]=$data['cash_type'];
						$data['fee'] = borrowClass::GetCashFeeAmount($dataCash);
						if(is_array($data['fee'])){
							$msg = $data['fee'];
						}else{
							if ($data['total'] <= $a['use_money']){
								$data['hongbao']=0;
								if(isset($_POST['hongbaoUsed'])&& $_POST['hongbaoUsed']>0 ){
									$data['hongbao']=$_POST['hongbaoUsed'];//使用红包
								}else{
									$data['hongbao']=0;
								}
								
								if($data['hongbao']>0){
									//$sql = "update `{user}` set hongbao = hongbao - ".$data['hongbao']." where user_id=".$_G['user_id'];
									//$re = $mysql->db_query($sql);
									$data['hongbao'] = 0;
								}
								$data['credited']=$data['total']-$data['fee']+$data['hongbao'];
	
								$_result = accountClass::AddCash($data);
								if ($_result!==true){
									$msg = array($_result);
								}else{
									
									//tg_cash($data, $_G['user_result']);
									
									
									
									$account_result =  accountClass::GetOneAccount(array("user_id"=>$_G['user_id']));
									$log['user_id'] = $_G['user_id'];
									$log['type'] = "cash_frost";
									$log['money'] = $data['total'];
									$log['total'] = $account_result['total'];
									$log['use_money'] =  $account_result['use_money']-$log['money'];
									$log['no_use_money'] =  $account_result['no_use_money']+$log['money'];
									$log['collection'] =  $account_result['collection'];
									$log['to_user'] = "0";
									$log['remark'] = "用户提现申请";
									$re = accountClass::AddLog($log);
									//$sql = "update `{sms_check}` set isuse=1 where id=".$coderesult["id"];
									//$re_1 = $mysql->db_query($sql);
									if($account_result==false || $re==false){
										 $msg = array("操作失败","",'/index.php?user&q=code/account/cash_new');
									}else{
										$msg = array("您的提现已经提交，请等待审核","",'/index.php?user&q=code/account/cash_new');
									}
								}
							}else{
								$msg = array("您的提现金额大于你所有的可用余额");
							}
						}
					}else{
						$msg = array("金额填写有误");
					}
				}else{
					$msg = array("交易密码填写有误");
				}
			}
 		}elseif (isset($_GET['cash_id'])){
			$a = $mysql->db_fetch_array('select * from {account_cash} where status=-1 and user_id='.$_G['user_id'].' and id='.(int)$_GET['cash_id']);
			if(empty($a)){
				$msg = array("请不要乱操作");
			}else{
				$a['trade_no'] = $a['trade_no'].'_'.rand(0001, 9999);
				tg_cash($a, $_G['user_result']);
			}
		}
	}
}
	elseif ($_U['query_type'] == "cash_new_sms"){
		$data['user_id'] = $_G["user_id"];
		$itype=$_GET["itype"];
		$randnum=rand(100000,999999);
		$lasttime=time()+5*60;
		$addtime=time();
		$sql = "select phone,username,phone_status from `{user}` where user_id=".$_G["user_id"];
		$re = $mysql->db_fetch_array($sql);
		if($re['phone_status']!=1){
			echo -1;
		}else{
			$sql = "insert into `{sms_check}`(code,lasttime,user_id,addtime,itype,phone) values('".$randnum."','".$lasttime."',".$_G["user_id"].",'".$addtime."',".$itype.",'".$re['phone']."')";
			$mysql->db_query($sql);
			if ($itype==1)
			{
				$money = $_GET['money'];
				$re = sendSMS($_G["user_id"],"尊敬的".$re['username']."，您申请提出".$money."元，手机验证码为：".$randnum."。如您没有进行本次操作，请速联系我们谢谢！",1,$re['phone']);
			}elseif($itype==2)
			{
				$re = sendSMS($_G["user_id"],"您的银行验证码是：".$randnum."，请在5分钟内提交！为了资金安全，请不要将验证码泄露给其他人",1,$re['phone']);
			}
			if($re==true){
				echo 1;
			}else{
				echo 0;
			}
		}
		exit();
	}
	//取消提现申请
	elseif ($_U['query_type'] == "cash_cancel"){
		/*
		$data['user_id'] =  $_G['user_id'];
		$data['id'] =  $_REQUEST['id'];
		$cash_result = accountClass::GetCashOne($data);
		
		if($cash_result!=false && $cash_result['status']==0){
			$data['status'] = 3;
			$_result = accountClass::UpdateCash($data);
			if ($_result!==true){
				$msg = array($_result);
			}else{
				$account_result = accountClass::GetOneAccount($data);
				$log['user_id'] = $data['user_id'];
				$log['type'] = "cash_cancel";
				$log['money'] = $cash_result['total'];
				$log['total'] = $account_result['total'];
				$log['use_money'] = $account_result['use_money']+$cash_result['total'];
				$log['no_use_money'] = $account_result['no_use_money']-$cash_result['total'];
				$log['collection'] =  $account_result['collection'];
				$log['to_user'] = "0";
				$log['remark'] = "取消提现解冻";
				accountClass::AddLog($log);
				
                //add by jackfeng 2012-7-9 取消提现 红包返回
                $sql = "update `{user}` set hongbao = hongbao + ".$cash_result['hongbao']." where user_id=".$data['user_id'];
                $mysql->db_query($sql); 

				$msg = array("成功取消提现");
			}
		}else{
			$msg = array("请不要乱操作");
		}
	*/
	}
	//登陆宝付
	elseif($_U['query_type'] == "login_gfb"){
		tg_login($_G['user_result']);
	}
}
	
$template = "user_account.html.php";
?>
