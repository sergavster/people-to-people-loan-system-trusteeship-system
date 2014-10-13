<?php
require_once ('../../core/config.inc.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');


    $params=$_GET;
    header("content-type:text/html; charset=gbk");
    $result = paymentClass::GetOne(array("nid"=>"ebatong"));
	$cert = $result['fields']['PrivateKey']['value'];
    $key=$cert;
    $checkSign=$params['sign'];
    
    //获取参数后同加签流程进行验签 
    $paramKey=array_keys($params);
    sort($paramKey);
    $md5src="";
    $i=0;
    $paramStr="";
    foreach($paramKey as $arraykey){
        if(strcmp($arraykey,"sign")==0){
        }else{
            if($i==0){
                $paramStr .=$arraykey."=".$params[$arraykey];
            }
            else{
                $paramStr .="&".$arraykey."=".$params[$arraykey];
            }
            $i++;
        }
    }
    $md5src .= $paramStr.$key;   
    $sign=md5($md5src);

    if(strcmp($checkSign,$sign)==0){       //验签通过，数据安全
        $trade_status=$params["trade_status"];//交易状态
        if(strcmp($trade_status,"TRADE_FINISHED")==0){  //交易成功
			$_TransID = $params["out_trade_no"];
			$file = $cachepath['pay'].$_TransID;
			$fp = fopen($file , 'w+');
			@chmod($file, 0777);
			if(flock($fp , LOCK_EX | LOCK_NB)){
				accountClass::OnlineReturn(array("trade_no"=>$params["out_trade_no"]));
				echo "充值成功，请点击返回查看充值记录<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
			}else{
				echo "请点击返回<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
			}
			fclose($fp);
        }else{    //支付失败 订单为待处理，可继续支付
             
 			echo ("充值失败，请点击返回<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>");
        	
        }
	}

?>