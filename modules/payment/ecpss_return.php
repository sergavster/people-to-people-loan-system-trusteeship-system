<?php
$MD5key = "owOzCyK^";//MD5˽Կ
$BillNo = $_POST["BillNo"];//������
$Amount = $_POST["Amount"];//���
$Succeed = $_POST["Succeed"];//֧��״̬
$Result = $_POST["Result"];//֧�����
$MD5info = $_POST["MD5info"]; //ȡ�õ�MD5У����Ϣ
$Remark = $_POST["Remark"];//��ע
$md5src = $BillNo.$Amount.$Succeed.$MD5key;//У��Դ�ַ���
$md5sign = strtoupper(md5($md5src));//MD5������
if($MD5info != $md5sign){
	echo 'MD5����ʧ��,��������<a href=/?user&q=code/account/recharge> >>>>>></a>';
	exit();
}
$SucceedArr = array(
	'0' => 'ʧ��',
	'1' => '�߷��տ�',
	'2' => '�ڿ�',
	'3' => '���׽��������޶�',
	'4' => '���½��׽������޶�',
	'5' => 'ͬһIp������ν���',
	'6' => 'ͬһ���䷢����ν���',
	'7' => 'ͬһ���ŷ�����ν���',
	'8' => 'ͬһCookies������ν���',
	'9' => 'Maxmind��ֵ����',
	'10' => '�̻�δע��',
	'11' => '�ܳײ�����',
	'13' => 'ǩ�����䱸�����ݷ����۸�',
	'14' => '������ַ����',
	'15' => '�̻�δ��ͨ',
	'16' => 'ͨ��δ��ͨ',
	'17' => '�ڿ�bean',
	'18' => 'ϵͳ�����쳣',
	'19' => 'Vip�̻����״�����',
	'20' => 'ͨ����Ϣ���ò�ȫ',
	'21' => '����֧����������',
	'22' => '������ַ����ȷ',
	'23' => '�̻����׿��ֲ���ȷ',
	'24' => 'ͬһ��ˮ�ų��ֶ�ν���',
	'25' => '�ֿ�����Ϣ����',
	'26' => '�����޶�ֵ��50000��',
	'27' => 'ͨ���ն�δ����',
	'28' => '�������ô���',
	'88' => '�ɹ�'
);

if($Succeed==88){
	require_once ('../../core/config.inc.php');
	require_once (ROOT_PATH.'modules/account/account.class.php');
	$file = $cachepath['pay'].$BillNo;
	$fp = fopen($file , 'w+');
	@chmod($file, 0777);
	if(flock($fp , LOCK_EX | LOCK_NB)){
		accountClass::OnlineReturn(array("trade_no"=>$BillNo));
		echo "��ֵ�ɹ����������ز鿴��ֵ��¼<a href=/?user&q=code/account/recharge> >>>>>></a>";
		flock($fp , LOCK_UN);
	}else{
		echo "��ֵʧ��ERROE:002����������<a href=/?user&q=code/account/recharge> >>>>>></a>";
	}
	fclose($fp);
}else{
	echo "{$SucceedArr[$Succeed]}����������<a href=/?user&q=code/account/recharge> >>>>>></a>";
	exit();
}
?>
