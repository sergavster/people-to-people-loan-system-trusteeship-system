<?
	$CmdId = $_POST['CmdId'];			//��Ϣ����
	$MerId = $_POST['MerId']; 	 		//�̻���
	$RespCode = $_POST['RespCode']; 	//Ӧ�𷵻���
	$TrxId = $_POST['TrxId'];  			//Ǯ�ܼҽ���Ψһ��ʶ
	$OrdAmt = $_POST['OrdAmt']; 		//���
	$CurCode = $_POST['CurCode']; 		//����
	$Pid = $_POST['Pid'];  				//��Ʒ���
	$OrdId = $_POST['OrdId'];  			//������
	$MerPriv = $_POST['MerPriv'];  		//�̻�˽����
	$RetType = $_POST['RetType'];  		//��������
	$DivDetails = $_POST['DivDetails']; //������ϸ
	$GateId = $_POST['GateId'];  		//����ID
	$ChkValue = $_POST['ChkValue']; 	//ǩ����Ϣ
	
	/*
	//��֤ǩ��
	$SignObject = new COM("CHINAPNR.NetpayClient");
	$MsgData = $CmdId.$MerId.$RespCode.$TrxId.$OrdAmt.$CurCode.$Pid.$OrdId.$MerPriv.$RetType.$DivDetails.$GateId;  	//����˳���ܴ�
	$MerFile = $_SERVER["DOCUMENT_ROOT"]."/hftx/PgPubk.key";			//�̻���ǩ��Կ�ļ�
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
		//510010��Ϊ�̻���
	//if($SignData == "0"){
		if($RespCode == "000000"){
			//���׳ɹ�
			//���ݶ����� ������Ӧҵ�����
			//��Щ�������
			echo "֧���ɹ�";
		}else{
			//����ʧ��
			//���ݶ����� ������Ӧҵ�����
			//��Щ�������
			echo "֧��ʧ��";
		}
	}else{
		//��ǩʧ��
		echo "��ǩʧ��[".$SignData."]";
	}

?>