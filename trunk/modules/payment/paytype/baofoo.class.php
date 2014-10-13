<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>自动处理提交页</title>
<?php
class baofooPayment{
	var $name = '宝付';//国付宝
	var $logo = 'baofoo';
	var $version = 20070615;
	var $description = "宝付";
	var $type = 1;//1->只能启动，2->可以添加
	var $charset = 'gbk';
	
	public static function ToSubmit($payment){
		$_MerchantID='120383';//商户号
		$_TransID=trim($payment['trade_no']);//流水号
		if($payment['_PayID'] != ''){//支付方式
			$_PayID = $payment['_PayID'];
			$_Server_url = 'http://paygate.baofoo.com/PayReceive/bankpay.aspx';
		}else{
			$_PayID = '1000';
			$_Server_url = 'http://paygate.baofoo.com/PayReceive/bankpay.aspx';
			//$_Server_url = 'http://paygate.baofoo.com/PayReceive/payindex.aspx';
		}
		$_TradeDate=date("YmdHis");//交易时间
		$_OrderMoney=number_format($payment['money'], 2, '.', '')*100;//订单金额
		$_ProductName='www.hufadai.com';//产品名称
		$_Amount='1';//数量
		$_ProductLogo='';//产品logo
		$_Username='';//支付用户名
		$_Email='';
		$_Mobile='';
		$_AdditionalInfo='';//订单附加消息
		$_Merchant_url="http://{$_SERVER['SERVER_NAME']}/modules/payment/baofoo_return.php";//商户通知地址 
		$_Return_url="http://{$_SERVER['SERVER_NAME']}/modules/payment/baofoo_return.php";//用户通知地址
		$_NoticeType='0';//通知方式
		$_Md5Key="v7yckasbweks3p73";
		$_Md5Sign=md5($_MerchantID.$_PayID.$_TradeDate.$_TransID.$_OrderMoney.$_Merchant_url.$_Return_url.$_NoticeType.$_Md5Key);
		//此处加入判断，如果前面出错了跳转到其他地方而不要进行提交
		?>
		</head>

		<body onload="document.form1.submit()">
		<form id="form1" name="form1" method="post" action="<?php echo $_Server_url; ?>">
				<input type='hidden' name='MerchantID' value="<?php echo $_MerchantID; ?>" />
				<input type='hidden' name='PayID' value="<?php echo $_PayID; ?>" />
				<input type='hidden' name='TradeDate' value="<?php echo $_TradeDate; ?>" />
				<input type='hidden' name='TransID' value="<?php echo $_TransID; ?>" />
				<input type='hidden' name='OrderMoney' value="<?php echo $_OrderMoney; ?>" />
				<input type='hidden' name='ProductName' value="<?php echo $_ProductName; ?>" />
				<input type='hidden' name='Amount' value="<?php echo $_Amount; ?>" />
				<input type='hidden' name='ProductLogo' value="<?php echo $_ProductLogo; ?>" />
				<input type='hidden' name='Username' value="<?php echo $_Username; ?>" />
				<input type='hidden' name='Email' value="<?php echo $_Email; ?>" />
				<input type='hidden' name='Mobile' value="<?php echo $_Mobile; ?>" />
				<input type='hidden' name='AdditionalInfo' value="<?php echo $_AdditionalInfo; ?>" />
				<input type='hidden' name='Merchant_url' value="<?php echo $_Merchant_url; ?>" />
				<input type='hidden' name='Return_url' value="<?php echo $_Return_url; ?>" />
				<input type='hidden' name='NoticeType' value="<?php echo $_NoticeType; ?>" />
				<input type='hidden' name='Md5Sign' value="<?php echo $_Md5Sign; ?>" />
		</form>
		</body>
		</html>
<?php
	exit;
	}
	
	function GetFields(){
		return array(
				'member_id'=>array(
						'label'=>'客户号',
						'type'=>'string'
				),
				'PrivateKey'=>array(
						'label'=>'私钥',
						'type'=>'string'
				)
		);
	}
}
?>