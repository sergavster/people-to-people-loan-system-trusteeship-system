<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>�Զ������ύҳ</title>
<?php
class baofooPayment{
	var $name = '����';//������
	var $logo = 'baofoo';
	var $version = 20070615;
	var $description = "����";
	var $type = 1;//1->ֻ��������2->�������
	var $charset = 'gbk';
	
	public static function ToSubmit($payment){
		$_MerchantID='120383';//�̻���
		$_TransID=trim($payment['trade_no']);//��ˮ��
		if($payment['_PayID'] != ''){//֧����ʽ
			$_PayID = $payment['_PayID'];
			$_Server_url = 'http://paygate.baofoo.com/PayReceive/bankpay.aspx';
		}else{
			$_PayID = '1000';
			$_Server_url = 'http://paygate.baofoo.com/PayReceive/bankpay.aspx';
			//$_Server_url = 'http://paygate.baofoo.com/PayReceive/payindex.aspx';
		}
		$_TradeDate=date("YmdHis");//����ʱ��
		$_OrderMoney=number_format($payment['money'], 2, '.', '')*100;//�������
		$_ProductName='www.hufadai.com';//��Ʒ����
		$_Amount='1';//����
		$_ProductLogo='';//��Ʒlogo
		$_Username='';//֧���û���
		$_Email='';
		$_Mobile='';
		$_AdditionalInfo='';//����������Ϣ
		$_Merchant_url="http://{$_SERVER['SERVER_NAME']}/modules/payment/baofoo_return.php";//�̻�֪ͨ��ַ 
		$_Return_url="http://{$_SERVER['SERVER_NAME']}/modules/payment/baofoo_return.php";//�û�֪ͨ��ַ
		$_NoticeType='0';//֪ͨ��ʽ
		$_Md5Key="v7yckasbweks3p73";
		$_Md5Sign=md5($_MerchantID.$_PayID.$_TradeDate.$_TransID.$_OrderMoney.$_Merchant_url.$_Return_url.$_NoticeType.$_Md5Key);
		//�˴������жϣ����ǰ���������ת�������ط�����Ҫ�����ύ
		?>
		</head>

		<body onload="document.form1.submit()">
		<form id="form1" name="form1" method="post" action="<?php echo $_Server_url; ?>">
				<input type='hidden' name='MerchantID' value="<?php echo $_MerchantID; ?>" />
				<input type='hidden' name='PayID' value="<?php echo $_PayID; ?>" />
				<input type='hidden' name='TradeDate' value="<?php echo $_TradeDate; ?>" />
				<input type='hidden' name='TransID' value="<?php echo $_TransID; ?>" />
				<input type='hidden' name='OrderMoney' value="<?php echo $_OrderMoney; ?>" />
				<input type='hidden' name='ProductName' value="<?php echo $_ProductName; ?>" />
				<input type='hidden' name='Amount' value="<?php echo $_Amount; ?>" />
				<input type='hidden' name='ProductLogo' value="<?php echo $_ProductLogo; ?>" />
				<input type='hidden' name='Username' value="<?php echo $_Username; ?>" />
				<input type='hidden' name='Email' value="<?php echo $_Email; ?>" />
				<input type='hidden' name='Mobile' value="<?php echo $_Mobile; ?>" />
				<input type='hidden' name='AdditionalInfo' value="<?php echo $_AdditionalInfo; ?>" />
				<input type='hidden' name='Merchant_url' value="<?php echo $_Merchant_url; ?>" />
				<input type='hidden' name='Return_url' value="<?php echo $_Return_url; ?>" />
				<input type='hidden' name='NoticeType' value="<?php echo $_NoticeType; ?>" />
				<input type='hidden' name='Md5Sign' value="<?php echo $_Md5Sign; ?>" />
		</form>
		</body>
		</html>
<?php
	exit;
	}
	
	function GetFields(){
		return array(
				'member_id'=>array(
						'label'=>'�ͻ���',
						'type'=>'string'
				),
				'PrivateKey'=>array(
						'label'=>'˽Կ',
						'type'=>'string'
				)
		);
	}
}
?>