<?php
class spayPayment {

	public static function ToSubmit($payment){
		$shengpay=new shengpay();
		$array=array(
			'Name'=>'B2CPayment',
			'Version'=>'V4.1.1.1.1',
			'Charset'=>'GBK',
			'MsgSender'=>'106924',
			'SendTime'=>date('YmdHis'),
			'OrderTime'=>date('YmdHis'),
			'PayType'=>'',
			'InstCode'=>'',  
			'PageUrl'=>$_G['system']['con_weburl'].'/command/success.php',
			'NotifyUrl'=>$_G['system']['con_weburl'].'modules/payment/SPAYOrderReturn.php',
			'ProductName'=>'测试站支付平台',
			'BuyerContact'=>'',
			'BuyerIp'=>'',
			'Ext1'=>'',
			'Ext2'=>'',
			'SignType'=>'MD5',
		);
		if(isset($payment['InstCode']) && $payment['InstCode'] != '')$array['InstCode']=$payment['InstCode'];
		if(isset($payment['PayType']) && $payment['PayType'] != '')$array['PayType']=$payment['PayType'];
		$shengpay->init($array);
		#$shengpay->setKey('shengfutongSHENGFUTONGtest');
		$shengpay->takeOrder($payment['trade_no'],$payment['money']);
	}
	function GetFields(){
		return array(
				'alipay_id'=>array(
						'label'=>'支付号',
						'type'=>'string'
				),
				'alipay_key'=>array(
						'label'=>'私钥',
						'type'=>'string'
				),
				'alipay_email'=>array(
						'label'=>'支付邮箱',
						'type'=>'string'
				),
				'alipay_type'=>array(
						'label'=>'支付类型',
						'type'=>'string'
				)
		);
	}
}

class shengpay{

	private $payHost;
	private $debug=false;
	private $key='TmpreU5ESTNPREl5TkRkeGNTNWpiMjA9';
	private $params=array(
		'Name'=>'',
		'Version'=>'',
		'Charset'=>'',
		'MsgSender'=>'',#发送方标识
		'SendTime'=>'',#发送支付请求时间,用户通过商户网站提交订单的支付时间,必须为14位正整数数字,格式为:yyyyMMddHHmmss,如:20110707112233
		'OrderNo'=>'', #商户订单号   oid
		'OrderAmount'=>'',  #支付金额  account
		'OrderTime'=>'',  #商户订单提交时间
		'PayType'=>'',
		'InstCode'=>'',
		'PageUrl'=>'',
		'NotifyUrl'=>'', #商户后台回调地址
		'ProductName'=>'',  #商品名称
		'BuyerContact'=>'', #支付人联系方式
		'BuyerIp'=>'', #买家IP地址 
		'Ext1'=>'', #扩展1   英文或中文字符串 支付完成后，挄照原样返回给商户
		'Ext2'=>'',
		'SignType'=>'MD5',
		'SignMsg'=>'',  #签名结果
	);

	function init($array=array()){
		if($this->debug)
			$this->payHost='http://mer.mas.sdo.com/web-acquire-channel/cashier.htm';
		else
			$this->payHost='http://mas.sdo.com/web-acquire-channel/cashier.htm';
		foreach($array as $key=>$value){
			$this->params[$key]=$value;
		}
	}

	function setKey($key){
		$this->key=$key;
	}
	function setParam($key,$value){
		$this->params[$key]=$value;
	}

	function takeOrder($oid,$fee){
		$this->params['OrderNo']=$oid;
		$this->params['OrderAmount']=$fee;
		
		$origin='';
		foreach($this->params as $key=>$value){
			if(!empty($value))
				$origin.=$value;
		}
		$SignMsg=strtoupper(md5($origin.$this->key));
		$this->params['SignMsg']=$SignMsg;
		echo '<meta http-equiv = "content-Type" content = "text/html; charset = '.$this->params['Charset'].'"/>
			<form  method="post" action="'.$this->payHost.'">';
			foreach($this->params as $key=>$value){
				echo '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
			}
			echo '<input type="submit" name="submit" value="" id="dh">
				<script>var a=document.getElementById("dh");a.click();</script>
			</form>';
	}

	function returnSign(){
		$params=array(
			'Name'=>'',
			'Version'=>'',
			'Charset'=>'',
			'TraceNo'=>'',
			'MsgSender'=>'',
			'SendTime'=>'',
			'InstCode'=>'',
			'OrderNo'=>'',
			'OrderAmount'=>'',
			'TransNo'=>'',
			'TransAmount'=>'',
			'TransStatus'=>'',
			'TransType'=>'',
			'TransTime'=>'',
			'MerchantNo'=>'',
			'ErrorCode'=>'',
			'ErrorMsg'=>'',
			'Ext1'=>'',
			'Ext2'=>'',
			'SignType'=>'MD5',
		);
		
		foreach($_POST as $key=>$value){
			if(isset($params[$key])){
				$params[$key]=$value;
			}
		}
		$TransStatus=(int)$_POST['TransStatus'];
		$origin='';
		foreach($params as $key=>$value){
			if(!empty($value))
				$origin.=$value;
		}
		$SignMsg=strtoupper(md5($origin.$this->key));
		if($SignMsg==$_POST['SignMsg'] and $TransStatus==1){
			return true;
		}else{
			return false;
		}
	}
}