<?php
require_once ('../../core/config.inc.php');
require_once ('../../core/slock.class.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');

require_once("../../core/php_rsa.php");  //请修改参数为php_rsa.php文件的实际位置
require_once("../../core/php_sax.php");  //请修改参数为php_sax.php文件的实际位置
        //
//----------------------------------------------------
//  接收数据
//  Receive the data
	$merchantId=$_POST["merchantId"];
	$version=$_POST['version'];
	$language=$_POST['language'];
	$signType=$_POST['signType'];
	$payType=$_POST['payType'];
	$issuerId=$_POST['issuerId'];
	$paymentOrderId=$_POST['paymentOrderId'];
	$orderNo=$_POST['orderNo'];
	$orderDatetime=$_POST['orderDatetime'];
	$orderAmount=$_POST['orderAmount'];
	$payDatetime=$_POST['payDatetime'];
	$payAmount=$_POST['payAmount'];
	$ext1=$_POST['ext1'];
	$ext2=$_POST['ext2'];
	$payResult=$_POST['payResult'];
	$errorCode=$_POST['errorCode'];
	$returnDatetime=$_POST['returnDatetime'];
	$signMsg=$_POST["signMsg"];

//'----------------------------------------------------
//'   Md5摘要认证
//'   verify  md5
//'----------------------------------------------------
	$bufSignSrc="";
	if($merchantId != "")
	$bufSignSrc=$bufSignSrc."merchantId=".$merchantId."&";		
	if($version != "")
	$bufSignSrc=$bufSignSrc."version=".$version."&";		
	if($language != "")
	$bufSignSrc=$bufSignSrc."language=".$language."&";		
	if($signType != "")
	$bufSignSrc=$bufSignSrc."signType=".$signType."&";		
	if($payType != "")
	$bufSignSrc=$bufSignSrc."payType=".$payType."&";
	if($issuerId != "")
	$bufSignSrc=$bufSignSrc."issuerId=".$issuerId."&";
	if($paymentOrderId != "")
	$bufSignSrc=$bufSignSrc."paymentOrderId=".$paymentOrderId."&";
	if($orderNo != "")
	$bufSignSrc=$bufSignSrc."orderNo=".$orderNo."&";
	if($orderDatetime != "")
	$bufSignSrc=$bufSignSrc."orderDatetime=".$orderDatetime."&";
	if($orderAmount != "")
	$bufSignSrc=$bufSignSrc."orderAmount=".$orderAmount."&";
	if($payDatetime != "")
	$bufSignSrc=$bufSignSrc."payDatetime=".$payDatetime."&";
	if($payAmount != "")
	$bufSignSrc=$bufSignSrc."payAmount=".$payAmount."&";
	if($ext1 != "")
	$bufSignSrc=$bufSignSrc."ext1=".$ext1."&";
	if($ext2 != "")
	$bufSignSrc=$bufSignSrc."ext2=".$ext2."&";
	if($payResult != "")
	$bufSignSrc=$bufSignSrc."payResult=".$payResult."&";
	if($errorCode != "")
	$bufSignSrc=$bufSignSrc."errorCode=".$errorCode."&";
	if($returnDatetime != "")
	$bufSignSrc=$bufSignSrc."returnDatetime=".$returnDatetime;
	
	initPublicKey("../../core/publickey.xml");//请把参数修改为你的publickey.xml文件的存放位置
	$keylength = 1024;
	
	//验签结果
 	$verify_result = rsa_verify($bufSignSrc,$signMsg, $publickey, $modulus, $keylength,"sha1");
        
	$value = null;
	if($verify_result){
		$value = "victory";
	}
	else{
		$value = "failed";
	}
        
        
	//验签成功，还需要判断订单状态，为"1"表示支付成功。
	$payvalue = null;
	$pay_result = false;
        
        if($verify_result){
         //*********************************************************************   
            //20120917 add by weego for 支付锁V2.0
            $lock = new slock();
            $lock->lock($merOrderNum);
            
            if($payResult == 1){
                    $pay_result = true;
                    $payvalue = "victory";
                    $file = $cachepath['pay'].$orderNo;   
                    //判断缓存中是否有交易cache文件
                    //创建交易cache缓存文件
                    $fp = fopen($file , 'w+');  

                    @chmod($file, 0777);	  
                    if(flock($fp , LOCK_EX | LOCK_NB)){    //设定模式独占锁定和不堵塞锁定
                            accountClass::OnlineReturn(array("trade_no"=>$billno));
                            echo "充值成功，请点击返回查看充值记录<a href=".$_G['system']['con_weburl']."?user&q=code/account/recharge> >>>>>></a>";
                            flock($fp , LOCK_UN);     
                    } else{     
                            echo "充值失败ERROE:002，请点击返回<a href=".$_G['system']['con_weburl']."?user&q=code/account/recharge> >>>>>></a>";
                    }     
                    fclose($fp); 
            }else{//************************************************************
                    $payvalue = "failed";
                    echo "充值失败ERROE:012，请点击返回<a href=".$_G['system']['con_weburl']."?user&q=code/account/recharge> >>>>>></a>";
                    exit;
            }
            //20120917 add by weego for 支付锁V2.0
            $lock->release($merOrderNum);
            //20120917 add by weego for 支付锁V2.0
        //**********************************************************************
        }else{
            echo "签名不正确！ERROE:102，请点击返回<a href=http://www.wzdai.com/?user&q=code/account/recharge> >>>>>></a>";
            exit;
        }
        
        

?>
