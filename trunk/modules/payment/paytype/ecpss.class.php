<?php
//$payment = array('trade_no'=>'123456','amount'=>'1000');
//ecpssPayment::ToSubmit($payment);
class ecpssPayment{
	public static function ToSubmit($payment){
		$MD5key = 'owOzCyK^';
		$MerNo = '17765';
		$form_url = "https://pay.ecpss.cn/sslpayment";
		$BillNo = $payment['trade_no'];//网站的购物订单号
		$Amount = $payment['money'];//金额
		$ReturnURL = "http://www.hufadai.com/modules/payment/ecpss_return.php";//支付完成后 web 页面跳转显示支付结果
		$AdviceURL = "http://www.hufadai.com/modules/payment/ecpss_return.php";//支付完成后，后台接收支付结果，可用来更新数据库值
		$md5src = $MerNo.$BillNo.$Amount.$ReturnURL.$MD5key;
		$MD5info = strtoupper(md5($md5src));//MerNo&BillNo&Amount&ReturnURL&MD5key连接起来进行MD5 加密后字符串
		$orderTime = date('YmdHis',time());//交易时间，格式：YYYYMMDDHHMMSS
		$defaultBankNumber = '';//银行代码(见银行编码表)，可为空传递对应编码表中的值，直接跳转到对应的银行网银界面
		$Remark = '';//备注
		$products = '';//物品信息
		header("Content-type:text/html; charset=utf-8");
?>
		<html>
		<head>
		<title>跳转......</title>
		<meta http-equiv="content-Type" content="text/html; charset=utf-8" />
		</head>
		<body>
		正在前往汇潮支付>>>>>>>>
		<form action="<?php echo $form_url ?>" method="post" id="frm1">
		<input type="hidden" name="MerNo" value="<?php echo $MerNo ?>" />
		<input type="hidden" name="BillNo" value="<?php echo $BillNo ?>" />
		<input type="hidden" name="Amount" value="<?php echo $Amount ?>" />
		<input type="hidden" name="ReturnURL" value="<?php echo $ReturnURL ?>" />
		<input type="hidden" name="AdviceURL" value="<?php echo $AdviceURL ?>" />
		<input type="hidden" name="MD5info" value="<?php echo $MD5info ?>" />
		<input type="hidden" name="orderTime" value="<?php echo $orderTime ?>" />
		<input type="hidden" name="defaultBankNumber" value="<?php echo $defaultBankNumber ?>" />
		<input type="hidden" name="Remark" value="<?php echo $Remark ?>" />
		<input type="hidden" name="products" value="<?php echo $products ?>" />
		</form>
		<script language="javascript">
			document.getElementById("frm1").submit();
		</script>
		</body>
		</html>
<?php
	exit();
	}
	function GetFields(){
		return array(
				'alipay_id'=>array(
						'label'=>'客户号',
						'type'=>'string'
				),
				'alipay_key'=>array(
						'label'=>'私钥',
						'type'=>'string'
				)
		);
	}
}
?>