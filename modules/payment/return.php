<?
require_once ('../../core/config.inc.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');


/*if (isset($_REQUEST['trade_status']) && $_REQUEST['trade_status']=="TRADE_SUCCESS" ){
	$trade_no = $_REQUEST['out_trade_no'];
	accountClass::OnlineReturn(array("trade_no"=>$_REQUEST['out_trade_no']));
	echo "<script>location.href='/index.php?user&q=code/account/recharge';</script>";
}
else
*/    
    if (isset($_REQUEST['ipsbillno']) && $_REQUEST['ipsbillno']!=""){
	$billno = $_GET['billno'];
	$amount = $_GET['amount'];
	$mydate = $_GET['date'];
	$succ = $_GET['succ'];
	$msg = $_GET['msg'];
	$attach = $_GET['attach'];
	$ipsbillno = $_GET['ipsbillno'];
	$retEncodeType = $_GET['retencodetype'];
	$currency_type = $_GET['Currency_type'];
	$signature = $_GET['signature'];
	$content = $billno . $amount . $mydate . $succ . $ipsbillno . $currency_type;
	$result = paymentClass::GetOne(array("nid"=>"ips"));
	$cert = $result['fields']['PrivateKey']['value'];
	$signature_1ocal = md5($content . $cert);
	if ($signature_1ocal == $signature){
		if ($succ == 'Y'){
			accountClass::OnlineReturn(array("trade_no"=>$billno));
			$msg = '交易成功';
		}else{
			$msg = '交易失败！';
		}
	}else{
		$msg = '签名不正确！';
	}
	echo "<script>alert('{$msg}');location.href='/index.php?user&q=code/account/recharge';</script>";
}
elseif (isset($_REQUEST['sp_billno']) && $_REQUEST['sp_billno']!=""){
	require_once (ROOT_PATH."modules/payment/classes/tenpay/PayResponseHandler.class.php");
	$result = paymentClass::GetOne(array("nid"=>"tenpay"));
	$key = $result['fields']['PrivateKey']['value'];
	$resHandler = new PayResponseHandler();
	$resHandler->setKey($key);
	
	if($resHandler->isTenpaySign()) {
		//交易单号
		$transaction_id = $resHandler->getParameter("transaction_id");
		$sp_billno = $_REQUEST['sp_billno'];
		//金额,以分为单位
		$total_fee = $resHandler->getParameter("total_fee")/100;
		
		//支付结果
		$pay_result = $resHandler->getParameter("pay_result");
		
		if( 0 == $pay_result ) {
			accountClass::OnlineReturn(array("trade_no"=>$sp_billno));
			$msg = "支付成功";
		} else {
			$msg = "支付失败";
		}
			
	} else {
		$msg =  "认证签名失败" ;
	}
	echo "<script>alert('{$msg}');location.href='/index.php?user&q=code/account/recharge';</script>";
}
elseif (isset($_REQUEST['respCode']) && $_REQUEST['respCode']!=""){
	$billno = $_REQUEST['merOrderNum'];
	$amount = $_REQUEST['tranAmt'];
	$mydate = $_REQUEST['tranFinishTime'];
	$succ = $_REQUEST['respCode'];
	$content = $billno . $amount . $mydate;
	$result = paymentClass::GetOne(array("nid"=>"guofubao"));
	$cert = $result['fields']['PrivateKey']['value'];
	$signature_1ocal = md5($content . $cert);
	/*if ($succ == '0000'){
		accountClass::OnlineReturn(array("trade_no"=>$billno));
		$msg = '交易充值成功';
	}else{
		$msg = '交易充值失败！';
	}*/
        if ($succ == '0000'){
            $msg = '充值交易完成，请及时查看充值状态,如有疑问，请及时联系网站财务和客服人员，谢谢';
        }else{
            $msg = '交易充值失败！';
        }
	//echo $msg;
	echo "<script>alert('{$msg}');location.href='/index.php?user&q=code/account/recharge';</script>";
}
?>