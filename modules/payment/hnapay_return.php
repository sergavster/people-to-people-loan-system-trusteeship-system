<?php
require_once ('../../core/config.inc.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');
//----------------------------------------------------
//  接收数据
//  Receive the data
//----------------------------------------------------
 
			
$orderID = $_REQUEST['orderID']; //订单号
$payAmount = $_REQUEST['payAmount']; //实际支付金额
$remark = $_REQUEST['remark']; //返回给商户的原封信息
$acquiringTime = $_REQUEST['acquiringTime'];  //收单时间
$signType = $_REQUEST['signType'];  //签名类型选择报文签名类型 1：RSA 方式（推荐）2：MD5 方式 默认：选择请求
$charset = $_REQUEST['charset']; //编码方式 1 ：UTF-8;
$stateCode = $_REQUEST['stateCode']; // 状态码 0：已接受 1：处理中 2：处理成功 3：处理失败
$orderNo = $_REQUEST['orderNo']; //支付系统的流水号
$orderAmount = $_REQUEST['orderAmount']; //商户订单金额 比方10元，提交时金额应为1000
$resultCode = $_REQUEST['resultCode']; //处理结果码
$partnerID = $_REQUEST['partnerID']; //支付系统提供给商户的ID号
$completeTime = $_REQUEST['completeTime']; //系统处理完成时间
$signMsg = $_REQUEST['signMsg'];

//'----------------------------------------------------
//'   Md5摘要认证生产环境
//'   verify  md5
//'----------------------------------------------------
$src = "orderID=".$orderID
."&resultCode=".$resultCode
."&stateCode=".$stateCode
."&orderAmount=".$orderAmount
."&payAmount=".$payAmount
."&acquiringTime=".$acquiringTime
."&completeTime=".$completeTime
."&orderNo=".$orderNo
."&partnerID=".$partnerID
."&remark=".$remark
."&charset=".$charset
."&signType=".$signType;
	if($_REQUEST["charset"] == 1)
		$charset = "UTF8";
//请在该字段中放置商户登陆merchant.ips.com.cn下载的证书
if(2 == $signType) //md5验签
{
	$pkey = "30819f300d06092a864886f70d010101050003818d0030818902818100c744269e6f68d721429b23ca74ce5a9b0f7cb9f82dbcd6d5edc074c0c64a6a5d7856993a25fc5079a93eeafd91c5d8cbf7a2a721fb9ff5baf67779b9ec57f12ca1101b4abedcbdf233be6292c7d32786c448dc75bb56839febdc294a92d250bac47f216d9bef6734aa8cacb05508a81783f1c86703b728167a455facf0b1d5ed0203010001";
	$src = $src."&pkey=".$pkey;
	$ret2 = md5($src);
}

if ($signMsg == $ret2)
{
	//----------------------------------------------------
	//  判断交易是否成功
	//  See the successful flag of this transaction
	//----------------------------------------------------
	if ($stateCode == '2')
	{
		/**----------------------------------------------------
		*比较返回的订单号和金额与您数据库中的金额是否相符
		*compare the orderID and amount from ips with the data recorded in your datebase
		*----------------------------------------------------
		状态码
		0：已接受
		1：处理中
		2：处理成功
		3：处理失败
		**/
		$file = $cachepath['pay'].$orderID;   
		//判断缓存中是否有交易cache文件
		//创建交易cache缓存文件
		$fp = fopen($file , 'w+');    
		@chmod($file, 0777);	  
		if(flock($fp , LOCK_EX | LOCK_NB)){    //设定模式独占锁定和不堵塞锁定
			accountClass::OnlineReturn(array("trade_no"=>$orderID));
			echo "充值成功，请点击返回查看充值记录<a href=/?user&q=code/account/recharge> >>>>>></a>";
			flock($fp , LOCK_UN);     
		} else{     
			echo "充值失败ERROE:002，请点击返回<a href=/?user&q=code/account/recharge> >>>>>></a>";
		}     
		fclose($fp);
                
	 
	}
	else
	{
		echo "充值失败ERROE:012，请点击返回<a href=/?user&q=code/account/recharge> >>>>>></a>";
		exit;
	}
}
else
{
	echo "签名不正确！ERROE:102，请点击返回<a href=/?user&q=code/account/recharge> >>>>>></a>";
	exit;
}
 header("Content-type:text/html; charset=utf-8"); 
?>
