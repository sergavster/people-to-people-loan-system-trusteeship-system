<?php

/*
 * @Description �ױ�֧��B2C����֧���ӿڷ��� 
 * @V3.0
 * @Author rui.xin
 */
require_once ('../../core/config.inc.php');
//require_once ('../../core/slock.class.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');

include ROOT_PATH."modules/payment/classes/yeepay/yeepayCommon.php";	
	
#	ֻ��֧���ɹ�ʱ�ױ�֧���Ż�֪ͨ�̻�.
##֧���ɹ��ص������Σ�����֪ͨ������֧����������е�p8_Url�ϣ�������ض���;��������Ե�ͨѶ.

#	�������ز���.
$return = getCallBackValue($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);

#	�жϷ���ǩ���Ƿ���ȷ��True/False��
$bRet = CheckHmac($r0_Cmd,$r1_Code,$r2_TrxId,$r3_Amt,$r4_Cur,$r5_Pid,$r6_Order,$r7_Uid,$r8_MP,$r9_BType,$hmac);
#	���ϴ���ͱ�������Ҫ�޸�.

$orderNo = $r6_Order;

#	У������ȷ.
if($bRet){
	if($r1_Code=="1"){
		
	#	��Ҫ�ȽϷ��صĽ�����̼����ݿ��ж����Ľ���Ƿ���ȣ�ֻ����ȵ�����²���Ϊ�ǽ��׳ɹ�.
	#	������Ҫ�Է��صĴ������������ƣ����м�¼�������Դ����ڽ��յ�֧�����֪ͨ���ж��Ƿ���й�ҵ���߼�������Ҫ�ظ�����ҵ���߼�������ֹ��ͬһ�������ظ��������������.      	  	
		//$lock = new slock();
		//$lock->lock($orderNo);
		$file = $cachepath['pay'].$orderNo;   
		//�жϻ������Ƿ��н���cache�ļ�
		//��������cache�����ļ�
		$fp = fopen($file , 'w+');  

		@chmod($file, 0777);	  
		if(flock($fp , LOCK_EX | LOCK_NB)){    //�趨ģʽ��ռ�����Ͳ���������
				accountClass::OnlineReturn(array("trade_no"=>$orderNo));
				echo "��ֵ�ɹ����������ز鿴��ֵ��¼<a href=http://{$_SERVER['HTTP_HOST']}/?user&q=code/account/recharge> >>>>>></a>";
				flock($fp , LOCK_UN);     
		} else{     
				echo "��ֵʧ��ERROE:002����������<a href=http://{$_SERVER['HTTP_HOST']}/?user&q=code/account/recharge> >>>>>></a>";
		}     
		fclose($fp); 
		//$lock->release($orderNo);
		/*
		if($r9_BType=="1"){
			echo "���׳ɹ�";
			echo  "<br />����֧��ҳ�淵��";
		}elseif($r9_BType=="2"){
			#�����ҪӦ�����������д��,��success��ͷ,��Сд������.
			echo "success";
			echo "<br />���׳ɹ�";
			echo  "<br />����֧������������";      			 
		}
		*/
	}
	
}else{
	echo "������Ϣ���۸�";
}
   
?>