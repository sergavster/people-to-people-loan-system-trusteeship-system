<?php
require_once ('../../core/config.inc.php');
//require_once ('../../core/slock.class.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');

$_MerchantID=$_REQUEST['MerchantID'];//�̻���
$_TransID =$_REQUEST['TransID'];//��ˮ��
$_Result=$_REQUEST['Result'];//֧�����(1:�ɹ�,0:ʧ��)
$_resultDesc=$_REQUEST['resultDesc'];//֧���������
$_factMoney=$_REQUEST['factMoney'];//ʵ�ʳɽ����
$_additionalInfo=$_REQUEST['additionalInfo'];//����������Ϣ
$_SuccTime=$_REQUEST['SuccTime'];//���׳ɹ�ʱ��
$_Md5Sign=$_REQUEST['Md5Sign'];//md5ǩ��
$_Md5Key="v7yckasbweks3p73";
$_WaitSign=md5($_MerchantID.$_TransID.$_Result.$_resultDesc.$_factMoney.$_additionalInfo.$_SuccTime.$_Md5Key);
if ($_Md5Sign == $_WaitSign) {
  //echo ("ok");
  //�����봦������飬��֤ͨ���������ύ�Ĳ����ж�֧�����
	if($_Result == "1"){
		//���׳ɹ�
		//���ݶ����� ������Ӧҵ�����
		//��Щ�������
			
		$file = $cachepath['pay'].$_TransID;   
		//�жϻ������Ƿ��н���cache�ļ�
		//��������cache�����ļ�
		$fp = fopen($file , 'w+');    
		@chmod($file, 0777);	  
		if(flock($fp , LOCK_EX | LOCK_NB)){    //�趨ģʽ��ռ�����Ͳ���������
			//$lock = new slock();
			//$lock->lock($_TransID);
			accountClass::OnlineReturn(array("trade_no"=>$_TransID));
			echo "��ֵ�ɹ����������ز鿴��ֵ��¼<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
			//$lock->release($_TransID);
			flock($fp , LOCK_UN);     
		} else{     
			echo "��������<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
		}     
		fclose($fp);
	}else{
		//����ʧ��
		//���ݶ����� ������Ӧҵ�����
		//��Щ�������
		echo "֧��ʧ��";
	}
	
} else {
  echo ("��ֵʧ��ERROE:102����������<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>");
   //�����봦�������
} 

?>