<?php

class sqypayPayment {

	 public function ToSubmit($payment){
		global $_G;
		$MerNo 			= $payment['member_id'];
		$MD5key      	= $payment['PrivateKey'];
		$Amount 		= number_format($payment['money'],2,".","");
		$BillNo 		= $payment['trade_no'];
		$ReturnURL 		= $_G['weburl']."/modules/payment/sqypay_Result.php";
		$MD5info 		= $this->getSignature($MerNo, $BillNo, $Amount, $ReturnURL, $MD5key);
		$NotifyURL 		= $_G['weburl']."/modules/payment/sqypay_Result.php";
		$PaymentType 	= "ICBC";
		$PayType 		= "CSPAY";//CSPAY:网银支付;
		?>

<form action="https://www.95epay.cn/sslpayment" method="post">
<input type="hidden" name="MerNo" value="<?=$MerNo?>">
<input type="hidden" name="Amount" value="<?=$Amount?>">
<input type="hidden" name="BillNo" value="<?=$BillNo?>">
<input type="hidden" name="ReturnURL" value="<?=$ReturnURL?>">
<input type="hidden" name="NotifyURL" value="<?=$NotifyURL?>">
<input type="hidden" name="MD5info" value="<?=$MD5info?>">
<input type="hidden" name="PayType" value="<?=$PayType?>">
<input type="hidden" name="PaymentType" value="<?=$PaymentType?>">
<input type="hidden" name="MerRemark" value="CustomData">
<input type="hidden" name="products" value="IphoneNike">

<p align="center"><input type="submit" name="b1" value="95epay(Credit Card)"></p>
</form>


		<?php
exit();
	 }
	public function getSignature($MerNo, $BillNo, $Amount, $ReturnURL, $MD5key){
		$_SESSION['MerNo'] = $MerNo;
		$_SESSION['MD5key'] = $MD5key;
		$sign_params  = array(
			'MerNo'       => $MerNo,
			'BillNo'       => $BillNo, 
			'Amount'         => $Amount,   
			'ReturnURL'       => $ReturnURL
		);
	  $sign_str = "";
	  ksort($sign_params);
	  foreach ($sign_params as $key => $val) {		   
			$sign_str .= sprintf("%s=%s&", $key, $val);
	}
	   return strtoupper(md5($sign_str. strtoupper(md5($MD5key))));   

		
	}
    function GetFields(){
        return array(
                'member_id'=>array(
                        'label'=>'客户号',
                        'type'=>'string'
                    ),
                'PrivateKey'=>array(
                        'label'=>'私钥',
                        'type'=>'string'
                )
            );
    }
}
?>
