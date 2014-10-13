<?php
class tlpayPayment {
	var $name = '通联支付平台';
	var $description = "通联支付平台";
        var $type = 1;//1->只能启动，2->可以添加
        var $logo = 'tl';
        var $version = "v1.0";
        var $language = "1";
        
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
	
	public static function ToSubmit($payment){
            
		$serverUrl="https://service.allinpay.com/gateway/index.do";//测试环境
                $inputCharset="1";//2--GBK 1--UTF-8   

                $pickupUrl=$_G['system']['con_weburl']."modules/payment/tlreceive.php";
                $receiveUrl="".$_G['system']['con_weburl']."modules/payment/tlreceive.php";
                $signType="1";
                $version = "v1.0";
                $language = "1";

                //页面编码要与参数inputCharset一致，否则服务器收到参数值中的汉字为乱码而导致验证签名失败。
                
                $merchantId="109060211210013";//商户号
                $key="1234567890";//KEY
                
                $orderNo=$payment['trade_no'];//订单号
                $orderAmount=$payment['money'];//支付金额
                $orderDatetime=date("Ymdhis",time());//订单提交时间date("Ymdhis",time());
                $payType="0"; //payType  支付方式 不能为空，必须放在表单中提交。
                $issuerId=""; //issueId 直联时不为空，必须放在表单中提交。
                $payerName=$payment['user_id'];//付款人姓名
                $payerEmail="";
                $payerTelephone="";
                $payerIDCard="";
                $pid="";
                $orderCurrency="0";//默认为人民币类型0
                $orderExpireDatetime="";
                $productName="erongdu";
                $productId=1;//商品编号
                $productPrice=$payment['money'];//商品单价
                $productNum=1;//商品数量
                $productDescription="";
                $ext1="";
                $ext2="";
                $pan="";

                // 生成签名字符串。
                $bufSignSrc=""; 
                if($inputCharset != "")
                $bufSignSrc=$bufSignSrc."inputCharset=".$inputCharset."&";		
                if($pickupUrl != "")
                $bufSignSrc=$bufSignSrc."pickupUrl=".$pickupUrl."&";		
                if($receiveUrl != "")
                $bufSignSrc=$bufSignSrc."receiveUrl=".$receiveUrl."&";		
                if($version != "")
                $bufSignSrc=$bufSignSrc."version=".$version."&";		
                if($language != "")
                $bufSignSrc=$bufSignSrc."language=".$language."&";		
                if($signType != "")
                $bufSignSrc=$bufSignSrc."signType=".$signType."&";		
                if($merchantId != "")
                $bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";		
                if($payerName != "")
                $bufSignSrc=$bufSignSrc."payerName=".$payerName."&";		
                if($payerEmail != "")
                $bufSignSrc=$bufSignSrc."payerEmail=".$payerEmail."&";		
                if($payerTelephone != "")
                $bufSignSrc=$bufSignSrc."payerTelephone=".$payerTelephone."&";			
                if($payerIDCard != "")
                $bufSignSrc=$bufSignSrc."payerIDCard=".$payerIDCard."&";			
                if($pid != "")
                $bufSignSrc=$bufSignSrc."pid=".$pid."&";		
                if($orderNo != "")
                $bufSignSrc=$bufSignSrc."orderNo=".$orderNo."&";
                if($orderAmount != "")
                $bufSignSrc=$bufSignSrc."orderAmount=".$orderAmount."&";
                if($orderCurrency != "")
                $bufSignSrc=$bufSignSrc."orderCurrency=".$orderCurrency."&";
                if($orderDatetime != "")
                $bufSignSrc=$bufSignSrc."orderDatetime=".$orderDatetime."&";
                if($orderExpireDatetime != "")
                $bufSignSrc=$bufSignSrc."orderExpireDatetime=".$orderExpireDatetime."&";
                if($productName != "")
                $bufSignSrc=$bufSignSrc."productName=".$productName."&";
                if($productPrice != "")
                $bufSignSrc=$bufSignSrc."productPrice=".$productPrice."&";
                if($productNum != "")
                $bufSignSrc=$bufSignSrc."productNum=".$productNum."&";
                if($productId != "")
                $bufSignSrc=$bufSignSrc."productId=".$productId."&";
                if($productDescription != "")
                $bufSignSrc=$bufSignSrc."productDescription=".$productDescription."&";
                if($ext1 != "")
                $bufSignSrc=$bufSignSrc."ext1=".$ext1."&";
                if($ext2 != "")
                $bufSignSrc=$bufSignSrc."ext2=".$ext2."&";	
                if($payType != "")
                $bufSignSrc=$bufSignSrc."payType=".$payType."&";		
                if($issuerId != "")
                $bufSignSrc=$bufSignSrc."issuerId=".$issuerId."&";
                if($pan != "")
                $bufSignSrc=$bufSignSrc."pan=".$pan."&";	
                $bufSignSrc=$bufSignSrc."key=".$key; //key为MD5密钥，密钥是在通联支付网关会员服务网站上设置。

                //签名，设为signMsg字段值。
                $signMsg = strtoupper(md5($bufSignSrc));	
                echo $bufSignSrc;
                //echo "<br>";
                //echo $signMsg;
                //exit;
                header("Content-type:text/html; charset=gb2312"); 
                //构造要post的字符串 
	?>
			<html>
			  <head>
				<title>跳转......</title>
				<meta http-equiv="content-Type" content="text/html; charset=gb2312" />
			  </head>
			  <body>
                                    <form name="frm1" id="frm1" action="<?php echo $serverUrl ?>" method="post">
                                            <input type="hidden" name="inputCharset" id="inputCharset" value="<?=$inputCharset?>" />
                                            <input type="hidden" name="pickupUrl" id="pickupUrl" value="<?=$pickupUrl?>"/>
                                            <input type="hidden" name="receiveUrl" id="receiveUrl" value="<?=$receiveUrl?>" />
                                            <input type="hidden" name="version" id="version" value="<?=$version ?>"/>
                                            <input type="hidden" name="language" id="language" value="<?=$language ?>" />
                                            <input type="hidden" name="signType" id="signType" value="<?=$signType?>"/>
                                            <input type="hidden" name="merchantId" id="merchantId" value="<?=$merchantId?>" />
                                            <input type="hidden" name="payerName" id="payerName" value="<?=$payerName?>"/>
                                            <input type="hidden" name="payerEmail" id="payerEmail" value="<?=$payerEmail?>" />
                                            <input type="hidden" name="payerTelephone" id="payerTelephone" value="<?=$payerTelephone ?>" />
                                            <input type="hidden" name="payerIDCard" id="payerIDCard" value="<?=$payerIDCard ?>" />
                                            <input type="hidden" name="pid" id="pid" value="<?=$pid?>"/>
                                            <input type="hidden" name="orderNo" id="orderNo" value="<?=$orderNo?>" />
                                            <input type="hidden" name="orderAmount" id="orderAmount" value="<?=$orderAmount ?>"/>
                                            <input type="hidden" name="orderCurrency" id="orderCurrency" value="<?=$orderCurrency?>" />
                                            <input type="hidden" name="orderDatetime" id="orderDatetime" value="<?=$orderDatetime?>" />
                                            <input type="hidden" name="orderExpireDatetime" id="orderExpireDatetime" value="<?=$orderExpireDatetime ?>"/>
                                            <input type="hidden" name="productName" id="productName" value="<?=$productName?>" />
                                            <input type="hidden" name="productPrice" id="productPrice" value="<?=$productPrice?>" />
                                            <input type="hidden" name="productNum" id="productNum" value="<?=$productNum ?>"/>
                                            <input type="hidden" name="productId" id="productId" value="<?=$productId?>" />
                                            <input type="hidden" name="productDescription" id="productDescription" value="<?=$productDescription?>" />
                                            <input type="hidden" name="ext1" id="ext1" value="<?=$ext1?>" />
                                            <input type="hidden" name="ext2" id="ext2" value="<?=$ext2?>" />
                                            <input type="hidden" name="payType" value="<?=$payType?>" />
                                            <input type="hidden" name="issuerId" value="<?=$issuerId?>" />
                                            <input type="hidden" name="pan" value="<?=$pan?>" />
                                            <input type="hidden" name="signMsg" id="signMsg" value="<?=$signMsg ?>" />
                                        <input type="submit" value="请不要充值！系统测试中！！！！"/>

                                    </form>
				<script language="javascript">
				 // document.getElementById("frm1").submit();
				</script>
			  </body>
			</html>
<?php
		exit;
	} // ToSubmit 
} // class 
?>
