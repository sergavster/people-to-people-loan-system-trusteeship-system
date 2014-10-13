<?php
class hnapayPayment {
	var $name = '新生支付V2.6.3';//新生支付V2.6.3
	var $description = "新生支付V2.6.3";
	var $type = 1;//1->只能启动，2->可以添加
    var $logo = 'hnapay';
    var $version = "2.6"; //版本	version
    var $charset = 'gb2312';
	
	public static function ToSubmit($payment){
		$form_url = 'https://www.hnapay.com/website/pay.htm'; //生产环境
		//$form_url = 'http://qaapp.hnapay.com/website/pay.htm'; //测试环境
		$payment['biz_id'] = "10001016706";
		$payment['Mer_key']="30819f300d06092a864886f70d010101050003818d0030818902818100c744269e6f68d721429b23ca74ce5a9b0f7cb9f82dbcd6d5edc074c0c64a6a5d7856993a25fc5079a93eeafd91c5d8cbf7a2a721fb9ff5baf67779b9ec57f12ca1101b4abedcbdf233be6292c7d32786c448dc75bb56839febdc294a92d250bac47f216d9bef6734aa8cacb05508a81783f1c86703b728167a455facf0b1d5ed0203010001";
		$flag = 0;
		$submitTime=date('YmdHis',time());//订单提交时间	submitTime
		$ipAddr = ip_address(); //用户IP
		$ipAddr = $_SERVER['HTTP_HOST']."[".$ipAddr."]"; //网址+用户IP
		$user_id = $payment['user_id']; //网址+用户IP
		$totalAmount=number_format($payment['money'], 2, '.', '');
		$totalAmount2=$totalAmount*100;
 
		if($payment['bankCode']<>""){
			$bankCode = $payment['bankCode'];   //目标资金机构代码	orgCode  
			$buyerMarked="380814665@qq.com"; //buyerMarked 参数是商户的买家在新生的登录帐号，格式为 手机号或EMAIL
			$directFlag="1";  //是否直连	directFlag 0：非直连 （默认）,1：直连
			$payType="BANK_B2C";  //付款方式	直连时必须明确指定，不能为默认值(ALL)。目前这里只能填 BANK_B2C 及、或 BANK_B2B 及、或 LARGE_CREDIT_CARD
		}else{			
			$bankCode = "";   //目标资金机构代码	orgCode  
			$buyerMarked="";
			$directFlag="0";  
			$payType="ALL";  //付款方式	payType
		}
		
		
		$argv = array(
			'version' => "2.6", //版本	version 2.6
			'serialID' => $payment['trade_no'], //请求流水号 serialID
			'submitTime' => $submitTime, //订单提交时间	submitTime
			'failureTime' => $submitTime+24*3600, //订单失效时间	failureTime
			'customerIP' => $ipAddr, //客户下单域名及IP	customerIP
			'orderDetails' => $payment['trade_no'].",".$totalAmount2.",网站充值,用户uid:{$user_id} 充值金额:{$totalAmount},1", //订单明细信息	orderDetails
			'totalAmount' => $totalAmount2, //订单总金额 totalAmount
			'type' => "1000", //交易类型 	type
			'buyerMarked' => $buyerMarked, //付款方新生账户号	buyerMarked 
			'payType' => $payType, //付款方式	payType
			'orgCode' => $bankCode, //目标资金机构代码	orgCode
			'currencyCode' => "1", //交易币种	currencyCode 1：人民币（默认）,2：预付卡（选择用预付费卡支付时，可选）,3：授信额度
			'directFlag' => $directFlag, //是否直连	directFlag 0：非直连 （默认）,1：直连
			'borrowingMarked' => "0", //资金来源借贷标识	borrowingMarked 0：无特殊要求（默认）,1：只借记,2：只贷记
			'couponFlag' => "1", //优惠券标识 couponFlag  1：可用 （默认）,0：不可用
			'platformID' => "", //平台商ID	 platformID
			'returnUrl' => $_G['system']['con_weburl']."modules/payment/hnapay_return.php", //支付结果成功返回的商户URL 商户回调地址	returnUrl
			'noticeUrl' => $_G['system']['con_weburl']."modules/payment/hnapay_return.php", //商户通知地址	noticeUrl
			'partnerID' => $payment['biz_id'], //商户ID	partnerID
			'remark' => "uid:{$user_id},money:{$totalAmount}", //扩展字段	remark
			'charset' => "1", //编码方式	charset 1.utf-8
			'signType' => "2", //签名类型	signType 1：RSA 方式（推荐）,2：MD5 方式
			 
 
		  
		 ); 
		 $signMsgValue="version=".$argv['version']."&serialID=".$argv['serialID']."&submitTime=".$argv['submitTime']."&failureTime=".$argv['failureTime']."&customerIP=".$argv['customerIP']."&orderDetails=".$argv['orderDetails']."&totalAmount=".$argv['totalAmount']."&type=".$argv['type']."&buyerMarked=".$argv['buyerMarked']."&payType=".$argv['payType']."&orgCode=".$argv['orgCode']."&currencyCode=".$argv['currencyCode']."&directFlag=".$argv['directFlag']."&borrowingMarked=".$argv['borrowingMarked']."&couponFlag=".$argv['couponFlag']."&platformID=".$argv['platformID']."&returnUrl=".$argv['returnUrl']."&noticeUrl=".$argv['noticeUrl']."&partnerID=".$argv['partnerID']."&remark=".$argv['remark']."&charset=".$argv['charset']."&signType=".$argv['signType'];
			 
		 //订单支付接口的签名字符串 signMsg，原文=版本+订单号+提交时间+过期时间+客户IP+订单详情+金额+日期+支付币种+商户证书 
		 $signMsg = $signMsgValue;
		 header("Content-type:text/html; charset=utf-8"); 
	//构造要post的字符串 
	?>
			<html>
			  <head>
				<title>跳转......</title>
				<meta http-equiv="content-Type" content="text/html; charset=utf-8" />
			  </head>
			  <body>
			  <div style="display:none">
			  <?php echo $signMsg;?>
			  </div>
			 正在前往新生支付v2.7>>>>>>>>
				<form action="<?php echo $form_url ?>" method="post" id="frm1">
					<input type="hidden" name="version"  value="<?php echo $argv['version'] ?>"> 	
					<input type="hidden" name="serialID"  value="<?php echo $argv['serialID'] ?>">
					<input type="hidden" name="submitTime"  value="<?php echo $argv['submitTime'] ?>">
					<input type="hidden" name="failureTime"  value="<?php echo $argv['failureTime'] ?>">
					<input type="hidden" name="customerIP"  value="<?php echo $argv['customerIP'] ?>">
					<input type="hidden" name="orderDetails"  value="<?php echo $argv['orderDetails'] ?>">
					<input type="hidden" name="totalAmount"  value="<?php echo $argv['totalAmount'] ?>">
					<input type="hidden" name="type"  value="<?php echo $argv['type'] ?>">
					<input type="hidden" name="buyerMarked"  value="<?php echo $argv['buyerMarked'] ?>">
					<input type="hidden" name="payType"  value="<?php echo $argv['payType'] ?>">
					<input type="hidden" name="orgCode"  value="<?php echo $argv['orgCode'] ?>">
					<input type="hidden" name="currencyCode"  value="<?php echo $argv['currencyCode'] ?>">
					<input type="hidden" name="directFlag"  value="<?php echo $argv['directFlag'] ?>">
					<input type="hidden" name="borrowingMarked"  value="<?php echo $argv['borrowingMarked'] ?>">
					<input type="hidden" name="couponFlag"  value="<?php echo $argv['couponFlag'] ?>">
					<input type="hidden" name="platformID"  value="<?php echo $argv['platformID'] ?>">
					<input type="hidden" name="returnUrl"  value="<?php echo $argv['returnUrl'] ?>">
					<input type="hidden" name="noticeUrl"  value="<?php echo $argv['noticeUrl'] ?>">
					<input type="hidden" name="partnerID"  value="<?php echo $argv['partnerID'] ?>">
					<input type="hidden" name="remark"  value="<?php echo $argv['remark'] ?>">
					<?php 
						if($argv['charset'] == 1)
							$charset = "UTF8";
					?>
					<input type="hidden" name="charset"  value="<?php echo $argv['charset'] ?>">
					<?php
							$signType = $argv['signType'];
							$signMsg = $signMsg;
							if(2 == $signType)
							{
								$pkey = $payment['Mer_key'];

								$signMsg = $signMsg."&pkey=".$pkey;
								$signMsg =  md5($signMsg);
							}
					?>
					<input type="hidden" name="signType"  value="<?php echo $signType ?>">
					<input type="hidden" name="signMsg"   value="<?php echo $signMsg ?>">
	
	 
				</form>
				<script language="javascript">
				  document.getElementById("frm1").submit();
				</script>
			  </body>
			</html>
<?php
		exit;
	} // ToSubmit 
	function GetFields(){
         return array(
                'member_id'=>array(
                        'label'=>'客户号',
                        'type'=>'string'
                    ),
                'PrivateKey'=>array(
                        'label'=>'私钥',
                        'type'=>'string'
                ),
				'authtype'=>array(
						'label'=>'其他',
						'type'=>'string'
				)
            );
    }
} // class 
?>
