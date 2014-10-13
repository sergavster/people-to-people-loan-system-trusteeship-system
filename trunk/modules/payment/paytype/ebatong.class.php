<?php
class ebatongPayment {

    public $name = '贝付';//特别推荐
    public $logo = 'EBATONG';
    public $version = 20140422;
    public $description = "国内领先的独立第三方支付企业。";
    public $type = 1;//1->只能启动，2->可以添加
    public $charset = 'utf-8';
    public $submitUrl = 'https://www.ebatong.com/direct/gateway.htm'; //  网关
    public $sign_type = 'MD5';

    
    
    public function getRealTime($partner,$key,$input_charset){                     
        $ask_for_time_stamp_gateway = "https://www.ebatong.com/gateway.htm"; // ebatong商户时间戳网关
        $service                    = "query_timestamp";                    // 服务名称：请求时间戳
        
        //对所有参数进行排列
        $params = array("service"=>$service,"partner"=>$partner,"input_charset"=>$input_charset,"sign_type"=>$this->sign_type);
        $paramKey = array_keys($params);
        sort($paramKey);
        $md5src = "";
        $i = 0;
        $paramStr="";
        foreach($paramKey as $arraykey){
            if($i == 0){
                $paramStr .= $arraykey."=".$params[$arraykey];
            }
            else{
                $paramStr .= "&".$arraykey."=".$params[$arraykey];
            }
            $i++;
        }
        $md5src .= $paramStr.$key;  
        $sign = md5($md5src);        
        $paramStr .= "&sign=".$sign;  
         
        $url=$ask_for_time_stamp_gateway."?".$paramStr;             
        $doc = new DOMDocument();
        $doc->load($url);
        $itemEncrypt_key = $doc->getElementsByTagName( "encrypt_key" );
        $encrypt_key = $itemEncrypt_key->item(0)->nodeValue;
        return $encrypt_key;
    }
   
	public function ToSubmit($data){
		//GBK编码提交出现错误
		header("content-type:text/html; charset=$this->charset");
		 //从订单数据中动态获取到的必填参数
        $params["out_trade_no"]    = $data['trade_no'];
        $params["subject"]         = gbk2utf8($data['subject']);
        $params["body"]         = gbk2utf8($data['body']);//商品描述
        $params['show_url']        = "";	// 商品展示网址，可空
		$params['payment_type'] = 1;//支付类型，默认值为：1（商品购买）
		$params['pay_method'] = "bankPay";// 支付方式，directPay(余额支付)、bankPay(网银支付)，可空
		$params['default_bank'] = ""; // 默认网银 ,快捷支付必填
		/**
	     ABC_B2C=农行
	     BJRCB_B2C=北京农村商业银行
	     BOC_B2C=中国银行
	     CCB_B2C=建行
	     CEBBANK_B2C=中国光大银行
	     CGB_B2C=广东发展银行
	     CITIC_B2C=中信银行
	     CMB_B2C=招商银行
	     CMBC_B2C=中国民生银行
	     COMM_B2C=交通银行
	     FDB_B2C=富滇银行
	     HXB_B2C=华夏银行
	     HZCB_B2C_B2C=杭州银行
	     ICBC_B2C=工商银行网
	     NBBANK_B2C=宁波银行
	     PINGAN_B2C=平安银行
	     POSTGC_B2C=中国邮政储蓄银行
	     SDB_B2C=深圳发展银行
	     SHBANK_B2C=上海银行
	     SPDB_B2C=上海浦东发展银行
	     */
		$params['royalty_parameters'] = ""; // 最多10组分润明细。示例：100001=0.01|100002=0.02 表示id为100001的用户要分润0.01元，id为100002的用户要分润0.02元。
		$params['royalty_type'] = ""; // 提成类型，目前只支持一种类型：10，表示卖家给第三方提成；
		$params['service'] = "create_direct_pay_by_user"; // 服务名称：即时交易
		$params['sign_type'] = $this->sign_type; //签名算法
		$params['exter_invoke_ip'] = ip_address(); //用户IP地址
		$params['price'] = ""; //商品单价
		$params['total_fee'] = number_format($data['money'],2,".",""); //交易金额
		$params['quantity'] = "";//购买数量
		$params['seller_email'] = ""; //卖家易八通用户名
		$params['seller_id'] = $data['member_id']; //卖家易八通用户ID
		$params['buyer_email'] = ""; //买家易八通用户名
		$params['buyer_id'] = ""; //买家易八通用户ID
		$params['notify_url'] = "http://{$_SERVER['SERVER_NAME']}/modules/payment/ebatong_return.php";//$data['notify_url'];//交易过程中服务器通知的页面
        $params['return_url'] = "http://{$_SERVER['SERVER_NAME']}/modules/payment/ebatong_return.php";//$data['return_url'];//返回地址
        $params['error_notify_url'] = $data['error_notify_url'];//返回地址
		$params['partner'] = $data['member_id'];//合作者商户ID
		$params['input_charset'] = $this->charset;// 字符集
	    
	    //获取时间戳
	    $realtime = $this->getRealTime($data['member_id'],$data['PrivateKey'],$this->charset);    
	    $params['anti_phishing_key'] = $realtime;//通过时间戳查询接口，获取的加密时间戳
	    
	    //参数排序
	    $paramKey = array_keys($params);
	    sort($paramKey);
	    $md5src = "";
	    $i = 0;
	    $paramStr = "";
	    foreach($paramKey as $arraykey){
	       if($i==0){
	            $paramStr .= $arraykey."=".$params[$arraykey];
	       }
	       else{
	            $paramStr .= "&".$arraykey."=".$params[$arraykey];
	       }
	            $i++;
	    }
	    
	    //加签
	    $md5src .= $paramStr.$data['PrivateKey'];   
	    $sign = md5($md5src);
	    $params['sign'] = $sign;
		?>
		<html>
		<head>
			<title>在线支付跳转......</title>
			<meta http-equiv="content-Type" content="text/html; charset=<?php echo $this->charset;?>" />
		</head>
		<body onload="document.form1.submit()">
		<form id="form1" name="form1" method="post" action="<?php echo $this->submitUrl; ?>">
		<?php
		   while($param=each($params)){ 
		      echo "<input type='hidden' id='".$param['key']."' name='".$param['key']."' value='".$param['value']."' />"; 
		   }
		?>
		</form>
		</body>
		</html>
<?php
	exit;
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
