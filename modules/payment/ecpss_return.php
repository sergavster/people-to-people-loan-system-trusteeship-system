<?php
$MD5key = "owOzCyK^";//MD5私钥
$BillNo = $_POST["BillNo"];//订单号
$Amount = $_POST["Amount"];//金额
$Succeed = $_POST["Succeed"];//支付状态
$Result = $_POST["Result"];//支付结果
$MD5info = $_POST["MD5info"]; //取得的MD5校验信息
$Remark = $_POST["Remark"];//备注
$md5src = $BillNo.$Amount.$Succeed.$MD5key;//校验源字符串
$md5sign = strtoupper(md5($md5src));//MD5检验结果
if($MD5info != $md5sign){
	echo 'MD5检验失败,请点击返回<a href=/?user&q=code/account/recharge> >>>>>></a>';
	exit();
}
$SucceedArr = array(
	'0' => '失败',
	'1' => '高风险卡',
	'2' => '黑卡',
	'3' => '交易金额超过单笔限额',
	'4' => '本月交易金额超过月限额',
	'5' => '同一Ip发生多次交易',
	'6' => '同一邮箱发生多次交易',
	'7' => '同一卡号发生多次交易',
	'8' => '同一Cookies发生多次交易',
	'9' => 'Maxmind分值过高',
	'10' => '商户未注册',
	'11' => '密匙不存在',
	'13' => '签名不配备，数据发生篡改',
	'14' => '返回网址错误',
	'15' => '商户未开通',
	'16' => '通道未开通',
	'17' => '黑卡bean',
	'18' => '系统出现异常',
	'19' => 'Vip商户交易处理中',
	'20' => '通道信息设置不全',
	'21' => '卡号支付超过限制',
	'22' => '交易网址不正确',
	'23' => '商户交易卡种不正确',
	'24' => '同一流水号出现多次交易',
	'25' => '持卡人信息错误',
	'26' => '金额超过限定值（50000）',
	'27' => '通道终端未设置',
	'28' => '汇率设置错误',
	'88' => '成功'
);

if($Succeed==88){
	require_once ('../../core/config.inc.php');
	require_once (ROOT_PATH.'modules/account/account.class.php');
	$file = $cachepath['pay'].$BillNo;
	$fp = fopen($file , 'w+');
	@chmod($file, 0777);
	if(flock($fp , LOCK_EX | LOCK_NB)){
		accountClass::OnlineReturn(array("trade_no"=>$BillNo));
		echo "充值成功，请点击返回查看充值记录<a href=/?user&q=code/account/recharge> >>>>>></a>";
		flock($fp , LOCK_UN);
	}else{
		echo "充值失败ERROE:002，请点击返回<a href=/?user&q=code/account/recharge> >>>>>></a>";
	}
	fclose($fp);
}else{
	echo "{$SucceedArr[$Succeed]}，请点击返回<a href=/?user&q=code/account/recharge> >>>>>></a>";
	exit();
}
?>
