<?php
require_once ('../../core/config.inc.php');
//require_once ('../../core/slock.class.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');

$_MerchantID=$_REQUEST['MerchantID'];//商户号
$_TransID =$_REQUEST['TransID'];//流水号
$_Result=$_REQUEST['Result'];//支付结果(1:成功,0:失败)
$_resultDesc=$_REQUEST['resultDesc'];//支付结果描述
$_factMoney=$_REQUEST['factMoney'];//实际成交金额
$_additionalInfo=$_REQUEST['additionalInfo'];//订单附加消息
$_SuccTime=$_REQUEST['SuccTime'];//交易成功时间
$_Md5Sign=$_REQUEST['Md5Sign'];//md5签名
$_Md5Key="v7yckasbweks3p73";
$_WaitSign=md5($_MerchantID.$_TransID.$_Result.$_resultDesc.$_factMoney.$_additionalInfo.$_SuccTime.$_Md5Key);
if ($_Md5Sign == $_WaitSign) {
  //echo ("ok");
  //处理想处理的事情，验证通过，根据提交的参数判断支付结果
	if($_Result == "1"){
		//交易成功
		//根据订单号 进行相应业务操作
		//在些插入代码
			
		$file = $cachepath['pay'].$_TransID;   
		//判断缓存中是否有交易cache文件
		//创建交易cache缓存文件
		$fp = fopen($file , 'w+');    
		@chmod($file, 0777);	  
		if(flock($fp , LOCK_EX | LOCK_NB)){    //设定模式独占锁定和不堵塞锁定
			//$lock = new slock();
			//$lock->lock($_TransID);
			accountClass::OnlineReturn(array("trade_no"=>$_TransID));
			echo "充值成功，请点击返回查看充值记录<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
			//$lock->release($_TransID);
			flock($fp , LOCK_UN);     
		} else{     
			echo "请点击返回<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
		}     
		fclose($fp);
	}else{
		//交易失败
		//根据订单号 进行相应业务操作
		//在些插入代码
		echo "支付失败";
	}
	
} else {
  echo ("充值失败ERROE:102，请点击返回<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>");
   //处理想处理的事情
} 

?>