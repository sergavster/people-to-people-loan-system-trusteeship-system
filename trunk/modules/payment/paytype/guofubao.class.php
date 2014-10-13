<?php

class guofubaoPayment {

	var $name = '国付宝';//国付宝
	var $logo = 'guofubao';
	var $version = 20070615;
	var $description = "国付宝";
	var $type = 1;//1->只能启动，2->可以添加
    var $charset = 'gbk';
	
	
    public static function ToSubmit($payment){
//    		$submitUrl = 'https://www.gopay.com.cn/PGServer/Trans/WebClientAction.do?';
   		$submitUrl = 'https://211.88.7.30/PGServer/Trans/WebClientAction.do?';
		$Date = date("Ymdhis",time());
		$Currency_Type = "RMB";
		$Mer_key = $payment['PrivateKey'];
		$Amount = number_format($payment['money'], 2, '.', '');
		$SignMD5 = md5($payment['trade_no']. $Amount . $Date . $Currency_Type . $Mer_key);

		$ipAddr = $_SERVER['REMOTE_ADDR'];

		$version = "2.0";          
		$charset = 1;          
		$language = 1;         
		$signType = 1;         
		$tranCode = 8888;         
		$merchantID = "0000003358";
		$merOrderNum = $payment['trade_no'];
		$tranAmt = $Amount;          
		$feeAmt = "";  
		$bankCode = $payment['bankCode'];         
		
		$currencyType = 156;     
		$frontMerUrl = $payment['return_url'];      
		$backgroundMerUrl = $payment['notify_url']; 
		//$frontMerUrl = "http://www.cnaidai.com/index.php?user&q=code/account/recharge"; 
		//$backgroundMerUrl = "http://www.cnaidai.com/index.php?user&q=code/account/recharge"; 

		$tranDateTime = $Date;     
		$virCardNoIn = '0000000001000000584';      
		$tranIP = $ipAddr;           

		$isRepeatSubmit = 1;   
		$goodsName = "";        
		$goodsDetail = "";      
		$buyerName = $payment['member_id'];
		$buyerContact = "";     

		$signValue='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=[]gopayOutOrderId=[]tranIP=['.$tranIP.']respCode=[]VerficationCode=[12345678]';

		$signValue = md5($signValue);

		$url = $submitUrl;
		$url .= "version={$version}&";//网关版本号
		$url .= "charset={$charset}&";//字符集 1 GBK 2 UTF-8
		$url .= "language={$language}&";//1 中文 2 英文
		$url .= "signType={$signType}&";//报文加密方式 1 MD5 2 SHA
		$url .= "tranCode={$tranCode}&";//交易代码 本域指明了交易的类型，支付网关接口必须为8888
		$url .= "merchantID={$merchantID}&";//商户代码
		$url .= "merOrderNum={$merOrderNum}&";//订单号
		$url .= "tranAmt={$tranAmt}&";//交易金额
		$url .= "feeAmt={$feeAmt}&";//

		$url .= "currencyType={$currencyType}&";//156，代表人民币
		$url .= "frontMerUrl={$frontMerUrl}&";//商户前台通知地址
		$url .= "backgroundMerUrl={$backgroundMerUrl}&";//

		$url .= "tranDateTime={$tranDateTime}&";//本域为订单发起的交易时间
		$url .= "virCardNoIn={$virCardNoIn}&";//本域指卖家在国付宝平台开设的国付宝账户号
		$url .= "tranIP={$tranIP}&";//发起交易的客户IP地址
		$url .= "isRepeatSubmit={$isRepeatSubmit}&";//	0不允许重复 1 允许重复 

		$url .= "goodsName={$goodsName}&";//
		$url .= "buyerName={$buyerName}&";//
		$url .= "signValue={$signValue}&";//
		$url .= "bankCode={$bankCode}&";//
		$url .= "userType=1&";//
		$url .= "gopayServerTime=";//本域为订单发起的交易时间


        return $url;
		
    }

    function callback($in,&$paymentId,&$money,&$message,&$tradeno){
        $merId = $this->getConf($in['out_trade_no'],'member_id'); //帐号
        $pKey = $this->getConf($in['out_trade_no'],'PrivateKey');
        $key = $pKey==''?'afsvq2mqwc7j0i69uzvukqexrzd0jq6h':$pKey;//私钥值
        ksort($in);
        //检测参数合法性
        $temp = array();
        foreach($in as $k=>$v){
            if($k!='sign'&&$k!='sign_type'){
                $temp[] = $k.'='.$v;
            }
        }
        $testStr = implode('&',$temp).$key;
        if($in['sign']==md5($testStr)){
            $paymentId = $in['out_trade_no'];    //支付单号
            $money = $in['total_fee'];
            $message = $in['body'];
            $tradeno = $in['trade_no'];
            switch($in['trade_status']){
                case 'TRADE_FINISHED':
                    if($in['is_success']=='T'){                        
                        return PAY_SUCCESS;
                    }else{                        
                        return PAY_FAILED;
                    }
                    break;
                case 'TRADE_SUCCESS':
                    if($in['is_success']=='T'){                        
                        return PAY_SUCCESS;
                    }else{                        
                        return PAY_FAILED;
                    }
                    break;
                case 'WAIT_SELLER_SEND_GOODS':
                    if($in['is_success']=='T'){                        
                        return PAY_PROGRESS;
                    }else{                        
                        return PAY_FAILED;
                    }
                    break;
                case 'TRADE_SUCCES':    //高级用户
                    if($in['is_success']=='T'){
                        return PAY_SUCCESS;
                    }else{
                        return PAY_FAILED;
                    }
                    break;
            }

        }else{
            $message = 'Invalid Sign';            
            return PAY_ERROR;
        }
    }

    function serverCallback($in,&$paymentId,&$money,&$message){
        exit('reserved');
    }

    function applyForm($agentfield){
      $tmp_form='<a href="javascript:void(0)" onclick="document.applyForm.submit();">立即申请支付宝</a>';
      $tmp_form.="<form name='applyForm' method='".$agentfield['postmethod']."' action='http://top.shopex.cn/recordpayagent.php' target='_blank'>";
      foreach($agentfield as $key => $val){
            $tmp_form.="<input type='hidden' name='".$key."' value='".$val."'>";
      }
      $tmp_form.="</form>";
      return $tmp_form;
    }

 	function pay_IPS_relay($status){
        switch ($status){
            case PAY_FAILED:
                $aTemp = 'failed';
                break;
            case PAY_TIMEOUT:
                $aTemp = 'timeout';
                break;
            case PAY_SUCCESS:
                $aTemp = 'succ';
                break;
            case PAY_CANCEL:
                $aTemp = 'cancel';
                break;
            case PAY_ERROR:
                $aTemp = 'status';
                break;
            case PAY_PROGRESS:
                $aTemp = 'progress';
                break;
        }
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
