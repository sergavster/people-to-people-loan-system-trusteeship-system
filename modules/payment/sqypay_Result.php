<?php
header("Content-Type:text/html; charset=gbk");

if(!isset($_POST["BillNo"])){
	exit();
}

require_once ('../../core/config.inc.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');
$a = paymentClass::GetOne(array("nid"=>"sqypay"));

$BillNo          =     $_POST["BillNo"];
$Amount          =     $_POST["Amount"];
$Succeed         =     $_POST["Succeed"];
$MD5info         =     $_POST["MD5info"];
$Result          =     $_POST["Result"];
$MerNo           =     $_POST['MerNo'];
$MD5key          =		$a['fields']['PrivateKey']['value'];
$MerRemark       =  	 $_POST['MerRemark'];//�Զ�����Ϣ����

$md5sign = getSignature($MerNo, $BillNo, $Amount, $Succeed, $MD5key);

if ($MD5info == $md5sign) {
	if ($Succeed == '88') {
		$_TransID = $BillNo;
		$file = $cachepath['pay'].$_TransID;
		$fp = fopen($file , 'w+');
		@chmod($file, 0777);
		if(flock($fp , LOCK_EX | LOCK_NB)){
			accountClass::OnlineReturn(array("trade_no"=>$params["out_trade_no"]));
			echo "��ֵ�ɹ����������ز鿴��ֵ��¼<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
		}else{
			echo "��������<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
		}
		fclose($fp);
	}else {
		echo ("��ֵʧ�ܣ���������<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>");
	}
}else{
	echo (iconv('utf-8', 'gbk', $Result.$Succeed)."����������<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>");
}

function getSignature($MerNo, $BillNo, $Amount, $Succeed, $MD5key){
	$sign_params  = array(
		'MerNo'         => $MerNo,
		'BillNo'        => $BillNo,
		'Amount'        => $Amount,
		'Succeed'       => $Succeed
	);
	$sign_str = "";
	ksort($sign_params);
	foreach ($sign_params as $key => $val) {

	$sign_str .= sprintf("%s=%s&", $key, $val);

	}
	return strtoupper(md5($sign_str. strtoupper(md5($MD5key))));
}
?>