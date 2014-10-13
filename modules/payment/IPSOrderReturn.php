<?php
require_once ('../../core/config.inc.php');
require_once ('../../core/slock.class.php');
require_once (ROOT_PATH.'modules/account/account.class.php');
require_once (ROOT_PATH.'modules/payment/payment.class.php');
//----------------------------------------------------
//  接收数据
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
//'   Md5摘要认证
//'   verify  md5
//'----------------------------------------------------
$content = "billno".$billno ."currencytype".$currency_type."amount". $amount ."date". $mydate ."succ". $succ . "ipsbillno". $ipsbillno . "retencodetype".$retEncodeType;
//请在该字段中放置商户登陆merchant.ips.com.cn下载的证书
$cert = 'GDgLwwdK270Qj1w4xho8lyTpRQZV9Jm5x4NwWOTThUa4fMhEBK9jOXFrKRT6xhlJuU2FEa89ov0ryyjfJuuPkcGzO5CeVx5ZIrkkt1aBlZV36ySvHOMcNv8rncRiy3DQ';
$signature_1ocal = md5($content . $cert);

if ($signature_1ocal == $signature){
	//20120917 add by weego for 支付锁V2.0
    $lock = new slock();
	$lock->lock($merOrderNum);
	
	//20120917 add by weego for 支付锁V2.0
	//----------------------------------------------------
	//  判断交易是否成功
	//  See the successful flag of this transaction
	//----------------------------------------------------
	if ($succ == 'Y'){
		/**----------------------------------------------------
		*比较返回的订单号和金额与您数据库中的金额是否相符
		*compare the billno and amount from ips with the data recorded in your datebase
		*----------------------------------------------------
		
		if(不等)
			echo "从IPS返回的数据和本地记录的不符合，失败！"
			exit
		else
			'----------------------------------------------------
			'交易成功，处理您的数据库 
			'The transaction is successful. update your database.
			'----------------------------------------------------
		end if
		**/
		$file = $cachepath['pay'].$billno;   
		//判断缓存中是否有交易cache文件
		//创建交易cache缓存文件
		$fp = fopen($file , 'w+');    
		@chmod($file, 0777);	  
		if(flock($fp , LOCK_EX | LOCK_NB)){    //设定模式独占锁定和不堵塞锁定
			accountClass::OnlineReturn(array("trade_no"=>$billno));
			echo "充值成功，请点击返回查看充值记录<a href="."http://{$_SERVER['SERVER_NAME']}/"."?user&q=code/account/recharge> >>>>>></a>";
			flock($fp , LOCK_UN);     
		} else{     
			echo "充值失败ERROE:002，请点击返回<a href="."http://{$_SERVER['SERVER_NAME']}/"."?user&q=code/account/recharge> >>>>>></a>";
		}     
		fclose($fp);
                
	 
	}else{
		echo "充值失败ERROE:012，请点击返回<a href="."http://{$_SERVER['SERVER_NAME']}/"."?user&q=code/account/recharge> >>>>>></a>";
		exit;
	}
	//20120917 add by weego for 支付锁V2.0
	$lock->release($merOrderNum);
    //20120917 add by weego for 支付锁V2.0
	
}else{
	echo "签名不正确！ERROE:102，请点击返回<a href="."http://{$_SERVER['SERVER_NAME']}/"."?user&q=code/account/recharge> >>>>>></a>";
	exit;
}
?>
