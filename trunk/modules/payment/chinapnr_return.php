<?
	$CmdId = $_POST['CmdId'];			//消息类型
	$MerId = $_POST['MerId']; 	 		//商户号
	$RespCode = $_POST['RespCode']; 	//应答返回码
	$TrxId = $_POST['TrxId'];  			//钱管家交易唯一标识
	$OrdAmt = $_POST['OrdAmt']; 		//金额
	$CurCode = $_POST['CurCode']; 		//币种
	$Pid = $_POST['Pid'];  				//商品编号
	$OrdId = $_POST['OrdId'];  			//订单号
	$MerPriv = $_POST['MerPriv'];  		//商户私有域
	$RetType = $_POST['RetType'];  		//返回类型
	$DivDetails = $_POST['DivDetails']; //分账明细
	$GateId = $_POST['GateId'];  		//银行ID
	$ChkValue = $_POST['ChkValue']; 	//签名信息
	
	/*
	//验证签名
	$SignObject = new COM("CHINAPNR.NetpayClient");
	$MsgData = $CmdId.$MerId.$RespCode.$TrxId.$OrdAmt.$CurCode.$Pid.$OrdId.$MerPriv.$RetType.$DivDetails.$GateId;  	//参数顺序不能错
	$MerFile = $_SERVER["DOCUMENT_ROOT"]."/hftx/PgPubk.key";			//商户验签公钥文件
	$SignData = $SignObject->VeriSignMsg0($MerFile,$MsgData,strlen($MsgData),$ChkValue);
	*/

	$fp = fsockopen("127.0.0.1", '8733', $errno, $errstr, 10);
	if (!$fp) {
		echo "$errstr ($errno)<br />\n";
	} else {
		
		$MsgData = $CmdId.$MerId.$RespCode.$TrxId.$OrdAmt.$CurCode.$Pid.$OrdId.$MerPriv.$RetType.$DivDetails.$GateId;
		 
		$MsgData_len =strlen($MsgData);
		if($MsgData_len < 100 ){
			$MsgData_len = '00'.$MsgData_len;
		}
		elseif($MsgData_len < 1000 ){
			$MsgData_len = '0'.$MsgData_len;
		}

		$out = 'V'.$MerId.$MsgData_len.$MsgData.$ChkValue;
		
		$out_len = strlen($out);
		if($out_len < 100 ){
			$out_len = '00'.$out_len;
		}
		elseif($out_len < 1000 ){
			$out_len = '0'.$out_len;
		}
		$out =$out_len.$out;
		

		//echo $MsgData_len;exit;
		//$out = '0021S87052400101234567890';
		fputs($fp, $out);

		$ChkValue ='';
		while (!feof($fp)) {
			$ChkValue .= fgets($fp, 128);
		}
		fclose($fp);
		//echo $ChkValue;
	}

	$SignData = $ChkValue;


	if($SignData == "0011V5100100000"){	
		//510010改为商户号
	//if($SignData == "0"){
		if($RespCode == "000000"){
			//交易成功
			//根据订单号 进行相应业务操作
			//在些插入代码
			echo "支付成功";
		}else{
			//交易失败
			//根据订单号 进行相应业务操作
			//在些插入代码
			echo "支付失败";
		}
	}else{
		//验签失败
		echo "验签失败[".$SignData."]";
	}

?>