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
    
    //��ȡ������ͬ��ǩ���̽�����ǩ 
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

    if(strcmp($checkSign,$sign)==0){       //��ǩͨ�������ݰ�ȫ
        $trade_status=$params["trade_status"];//����״̬
        if(strcmp($trade_status,"TRADE_FINISHED")==0){  //���׳ɹ�
			$_TransID = $params["out_trade_no"];
			$file = $cachepath['pay'].$_TransID;
			$fp = fopen($file , 'w+');
			@chmod($file, 0777);
			if(flock($fp , LOCK_EX | LOCK_NB)){
				accountClass::OnlineReturn(array("trade_no"=>$params["out_trade_no"]));
				echo "��ֵ�ɹ����������ز鿴��ֵ��¼<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
			}else{
				echo "��������<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>";
			}
			fclose($fp);
        }else{    //֧��ʧ�� ����Ϊ�������ɼ���֧��
             
 			echo ("��ֵʧ�ܣ���������<a href=http://{$_SERVER['SERVER_NAME']}/?user&q=code/account/recharge> >>>>>></a>");
        	
        }
	}

?>