<?php
require_once ('../../core/config.inc.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once('paytype/spay.class.php');
$shengpay=new shengpay();
#$shengpay->setKey('shengfutongSHENGFUTONGtest');
if($shengpay->returnSign()){
	/*支付成功*/
	$oid=$_POST['OrderNo'];
	$fee=$_POST['TransAmount'];
	/*
		商家自行检测商家订单状态，避免重复处理，而且请检查fee的值与订单需支付金额是否相同
	*/
	$file = $cachepath['pay'].$oid;   
		//判断缓存中是否有交易cache文件
		//创建交易cache缓存文件
	$fp = fopen($file , 'w+');    
	@chmod($file, 0777);	  
	if(flock($fp , LOCK_EX | LOCK_NB)){    //设定模式独占锁定和不堵塞锁定
		$result = accountClass::OnlineReturn(array("trade_no"=>$oid, 'money'=>$fee));
		if($result==true){
			echo 'OK';
		}else{
			echo 'Error'; // 支付金额与订单金额不符
		}
	}else{
		echo 'Error';
	}
	flock($fp , LOCK_UN);   
	fclose($fp);
}else{
	echo 'Error';
}
?>