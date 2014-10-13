<?php
require_once ('../../core/config.inc.php');
require_once ('../../core/slock.class.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');
//----------------------------------------------------
//  ��������
//  Receive the data
//----------------------------------------------------
$billno = $_GET['billno'];
$amount = $_GET['amount'];
$mydate = $_GET['date'];
$succ = $_GET['succ'];
$msg = $_GET['msg'];
$attach = $_GET['attach'];
$ipsbillno = $_GET['ipsbillno'];
$retEncodeType = $_GET['retencodetype'];
$currency_type = $_GET['Currency_type'];
$signature = $_GET['signature'];

//'----------------------------------------------------
//'   Md5ժҪ��֤
//'   verify  md5
//'----------------------------------------------------
$content = "billno".$billno ."currencytype".$currency_type."amount". $amount ."date". $mydate ."succ". $succ . "ipsbillno". $ipsbillno . "retencodetype".$retEncodeType;
//���ڸ��ֶ��з����̻���½merchant.ips.com.cn���ص�֤��
$cert = 'GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ';
$signature_1ocal = md5($content . $cert);

if ($signature_1ocal == $signature){
	//20120917 add by weego for ֧����V2.0
    $lock = new slock();
	$lock->lock($merOrderNum);
	
	//20120917 add by weego for ֧����V2.0
	//----------------------------------------------------
	//  �жϽ����Ƿ�ɹ�
	//  See the successful flag of this transaction
	//----------------------------------------------------
	if ($succ == 'Y'){
		/**----------------------------------------------------
		*�ȽϷ��صĶ����źͽ���������ݿ��еĽ���Ƿ����
		*compare the billno and amount from ips with the data recorded in your datebase
		*----------------------------------------------------
		
		if(����)
			echo "��IPS���ص����ݺͱ��ؼ�¼�Ĳ����ϣ�ʧ�ܣ�"
			exit
		else
			'----------------------------------------------------
			'���׳ɹ��������������ݿ� 
			'The transaction is successful. update your database.
			'----------------------------------------------------
		end if
		**/
		$file = $cachepath['pay'].$billno;   
		//�жϻ������Ƿ��н���cache�ļ�
		//��������cache�����ļ�
		$fp = fopen($file , 'w+');    
		@chmod($file, 0777);	  
		if(flock($fp , LOCK_EX | LOCK_NB)){    //�趨ģʽ��ռ�����Ͳ���������
			accountClass::OnlineReturn(array("trade_no"=>$billno));
			echo "��ֵ�ɹ����������ز鿴��ֵ��¼<a href="."http://{$_SERVER['SERVER_NAME']}/"."?user&q=code/account/recharge> >>>>>></a>";
			flock($fp , LOCK_UN);     
		} else{     
			echo "��ֵʧ��ERROE:002����������<a href="."http://{$_SERVER['SERVER_NAME']}/"."?user&q=code/account/recharge> >>>>>></a>";
		}     
		fclose($fp);
                
	 
	}else{
		echo "��ֵʧ��ERROE:012����������<a href="."http://{$_SERVER['SERVER_NAME']}/"."?user&q=code/account/recharge> >>>>>></a>";
		exit;
	}
	//20120917 add by weego for ֧����V2.0
	$lock->release($merOrderNum);
    //20120917 add by weego for ֧����V2.0
	
}else{
	echo "ǩ������ȷ��ERROE:102����������<a href="."http://{$_SERVER['SERVER_NAME']}/"."?user&q=code/account/recharge> >>>>>></a>";
	exit;
}
?>
