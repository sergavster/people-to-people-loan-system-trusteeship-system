<?php
require_once ('../../core/config.inc.php');
require_once ('../../core/slock.class.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');

$version = $_POST["version"];
$charset = $_POST["charset"];
$language = $_POST["language"];
$signType = $_POST["signType"];
$tranCode = $_POST["tranCode"];
$merchantID = $_POST["merchantID"];
$merOrderNum = $_POST["merOrderNum"];
$tranAmt = $_POST["tranAmt"];
$feeAmt = $_POST["feeAmt"];
$frontMerUrl = $_POST["frontMerUrl"];
$backgroundMerUrl = $_POST["backgroundMerUrl"];
$tranDateTime = $_POST["tranDateTime"];
$tranIP = $_POST["tranIP"];
$respCode = $_POST["respCode"];
$msgExt = $_POST["msgExt"];
$orderId = $_POST["orderId"];
$gopayOutOrderId = $_POST["gopayOutOrderId"];
$bankCode = $_POST["bankCode"];
$tranFinishTime = $_POST["tranFinishTime"];
$merRemark1 = $_POST["merRemark1"];
$merRemark2 = $_POST["merRemark2"];
$signValue = $_POST["signValue"];

$signValue2='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=['.$orderId.']gopayOutOrderId=['.$gopayOutOrderId.']tranIP=['.$tranIP.']respCode=['.$respCode.']VerficationCode=[12345678]';

$signValue2 = md5($signValue2);

if ($signValue==$signValue2){

	//20120917 add by weego for 支付锁V2.0
	$lock = new slock();
	$lock->lock($merOrderNum);
	//20120917 add by weego for 支付锁V2.0
	
	if ($respCode=='0000'){
	
		$file = $cachepath['pay'].$merOrderNum;   
		//判断缓存中是否有交易cache文件
		//创建交易cache缓存文件
		$fp = fopen($file , 'w+');    
		@chmod($file, 0777);	  
		if(flock($fp , LOCK_EX | LOCK_NB)){    //设定模式独占锁定和不堵塞锁定
				accountClass::OnlineReturn(array("trade_no"=>$merOrderNum));
				$msg = '交易成功';
				echo "RespCode=0000|JumpURL=".$_G['weburl']."/command/success.php"; 
			flock($fp , LOCK_UN);     
		} else{     
			echo "RespCode=0000|JumpURL=".$_G['weburl']."/command/success.php";  
		}     
		fclose($fp);
                
	}else{
                $msg = '交易失败！';
		echo "RespCode=9999|JumpURL=".$_G['weburl']."/command/faild.php";
                
	}
	//20120917 add by weego for 支付锁V2.0
	$lock->release($merOrderNum);
	//20120917 add by weego for 支付锁V2.0
        
}


//*****************************************************************************

?>