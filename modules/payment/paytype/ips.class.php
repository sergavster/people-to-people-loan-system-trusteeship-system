<?php
class ipsPayment {
	var $name = '��ѶIPS����֧��3.0';//��ѶIPS����֧��3.0
	var $description = "��ѶIPS����֧��3.0";
	var $type = 1;//1->ֻ��������2->�������
    var $logo = 'IPS3';
    var $version = 20070615;
    var $charset = 'gb2312';
	
	public static function ToSubmit($payment){
		$form_url = 'http://pay.ips.net.cn/ipayment.aspx'; //��������
		$payment['Mer_code'] = "000015";
		$payment['Mer_key']="GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ";
		$flag = 0;
		$argv = array(
			'Mer_code'=>$payment['Mer_code'], 
			'Billno' => $payment['trade_no'],
			'Amount' => number_format($payment['money'], 2, '.', ''),
			//'Date' => date("Ymd",time()),
			'Date' => date("Ymd",time()),
			'Currency_Type' => "RMB",
			//֧������
			'Gateway_Type' => "01", //��ǿ�
			//֧������ɹ����ص��̻�URL
			//'Merchanturl' => $payment['return_url'],
			'Merchanturl' => "http://{$_SERVER['SERVER_NAME']}/modules/payment/IPSOrderReturn.php",
			'DispAmount' => number_format($payment['money'], 2, '.', ''),
			//ԭ�ⲻ���ķ��ظ���վ
			'Attach' => "UID:{$payment['user_id']},��ֵ��{$payment['money']}",
			//����֧���ӿڼ��ܷ�ʽ
			'OrderEncodeType' => "5",
			//���׷��ؽӿڼ��ܷ�ʽ 
			'RetEncodeType' => "17",
			'DoCredit' => '0',
			'Bankco' =>'',
			//�Ƿ��ṩServer���ط�ʽ 
			'Rettype' => "1",
			//Server����ҳ�� 
			'ServerUrl' => "http://{$_SERVER['SERVER_NAME']}/modules/payment/IPSOrderReturn.php",
		 ); 
		 /*
		  * Author : Liu   2012-05-12
		 */
		 if(isset($payment['bankCode'])){
			$argv['DoCredit'] = '1';
			$argv['Bankco'] = $payment['bankCode'];
		 }
		 //����֧���ӿڵ�Md5ժҪ��ԭ��=������+���+����+֧������+�̻�֤�� 
		 $SignMD5 = md5("billno".$argv['Billno']."currencytype".$argv['Currency_Type']."amount".$argv['Amount']."date".$argv['Date']."orderencodetype".$argv['OrderEncodeType'].$payment['Mer_key']  );
		 header("Content-type:text/html; charset=gb2312"); 
	//����Ҫpost���ַ��� 
	?>
			<html>
			  <head>
				<title>��ת......</title>
				<meta http-equiv="content-Type" content="text/html; charset=gb2312" />
			  </head>
			  <body>
				<form action="<?php echo $form_url ?>" method="post" id="frm1">
				  <input type="hidden" name="Mer_code" value="<?php echo $argv['Mer_code'] ?>">
				  <input type="hidden" name="Billno" value="<?php echo $argv['Billno'] ?>">
				  <input type="hidden" name="Amount" value="<?php echo $argv['Amount'] ?>" >
				  <input type="hidden" name="Date" value="<?php echo $argv['Date'] ?>">
				  <input type="hidden" name="Currency_Type" value="<?php echo $argv['Currency_Type'] ?>">
				  <input type="hidden" name="Gateway_Type" value="<?php echo $argv['Gateway_Type'] ?>">
				  <input type="hidden" name="Merchanturl" value="<?php echo $argv['Merchanturl'] ?>">
				  <input type="hidden" name="FailUrl" value="<?php echo $argv['FailUrl'] ?>">
				  <input type="hidden" name="ErrorUrl" value="<?php echo $argv['ErrorUrl'] ?>">
				  <input type="hidden" name="Attach" value="<?php echo $argv['Attach'] ?>">
				  <input type="hidden" name="DispAmount" value="<?php echo $argv['DispAmount'] ?>">
				  <input type="hidden" name="OrderEncodeType" value="<?php echo $argv['OrderEncodeType'] ?>">
				  <input type="hidden" name="RetEncodeType" value="<?php echo $argv['RetEncodeType'] ?>">
				  <input type="hidden" name="Rettype" value="<?php echo $argv['Rettype'] ?>">
				  <input type="hidden" name="ServerUrl" value="<?php echo $argv['ServerUrl'] ?>">
				  <input type="hidden" name="SignMD5" value="<?php echo $SignMD5 ?>">
				  <input type="hidden" name="DoCredit" value="<?php echo $argv['DoCredit'] ?>">
				  <input type="hidden" name="Bankco" value="<?php echo $argv['Bankco'] ?>">
				</form>
				<script language="javascript">
				  document.getElementById("frm1").submit();
				</script>
			  </body>
			</html>
<?php
		exit;
	} // ToSubmit
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
} // class 
?>
