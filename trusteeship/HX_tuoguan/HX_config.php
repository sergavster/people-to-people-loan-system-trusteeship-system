<?php
/*
*������һЩ�̶����ã��͹��÷���
*/

/*
$HX_config['argMerCode'] = '100000675';
$HX_config['argTerminalId'] = "100000701";
$HX_config['argMerKey'] = 'n725d5gsb7mlyzzw';
$HX_config['openAnccountUrl'] = 'http://paytest.baofoo.com/baofoo-custody/custody/bindState.do';
$HX_config['rechargeUrl'] = "http://paytest.baofoo.com/baofoo-custody/custody/recharge.do";
$HX_config['cashUrl'] = "http://paytest.baofoo.com/baofoo-custody/custody/foCharge.do";
$HX_config['borrowUrl'] = "http://paytest.baofoo.com/baofoo-custody/custody/p2pRequest.do";
$HX_config['getAccountUrl'] = "https://paytest.baofoo.com/baofoo-custody/custody/accountBalance.do";
$HX_config['orderUrl'] = 'http://paytest.baofoo.com/baofoo-custody/custody/p2pQuery.do';
$HX_config['zzUrl'] = 'https://paytest.baofoo.com/baofoo-custody/custody/acctTrans.do';
$HX_config['loginUrl'] = 'http://tmy.baofoo.com';
$HX_config['returnHost'] = 'http://www.hzhnong.com';
*/
$HX_config['argMerCode'] = '400695';//�̻���
$HX_config['argTerminalId'] = "19824";//�ն˺�
$HX_config['argMerKey'] = 'qbcse286m8wq3czh';
$HX_config['openAnccountUrl'] = 'http://pm.baofoo.com/custody/bindState.do';
$HX_config['rechargeUrl'] = "http://pm.baofoo.com/custody/recharge.do";
$HX_config['cashUrl'] = "http://pm.baofoo.com/custody/foCharge.do";
$HX_config['borrowUrl'] = "http://pm.baofoo.com/custody/p2pRequest.do";
$HX_config['getAccountUrl'] = "http://pm.baofoo.com/custody/accountBalance.do";
$HX_config['orderUrl'] = 'http://pm.baofoo.com/custody/p2pQuery.do';
$HX_config['loginUrl'] = 'https://my.baofoo.com';
$HX_config['zzUrl'] = 'https://pm.baofoo.com/custody/acctTrans.do';
$HX_config['returnHost'] = 'http://www.hzhnong.com';

$HX_config['repaymentTranCode'] = 'P004';//����
$HX_config['cashTranCode'] = 'P010';//����
$HX_config['rechargeTranCode'] = 'P009';//��ֵ
$HX_config['repaymentTranCode'] = 'P004';//����
$HX_config['tenderTranCode'] = 'P001';//Ͷ��
$HX_config['fullTranCode'] = 'P003';//����
$HX_config['liubiaoTranCode'] = 'P002';//����
$HX_config['addBorrowTranCode'] = 'P000';//����
$HX_config['apenAccountTranCode'] = 'P007';//'����'
$HX_config['deductTranCode'] = 'P012';//'�ۿ�'
$HX_config['cx_return_key'] = array(
	'order_id'=>'������',
	'state'=>'�����Ƿ�ɹ�',
	'succ_amount'=>'�ɹ����',
	'succ_time'=>'�ɹ�ʱ��',
	'fee'=>'�̻�����',
	'baofoo_fee'=>'����������',
	'fee_taken_on'=>'��������ȡ��',
	'bidId'=>'���',
	'outContractNo'=>'���ǩԼ��',
	'outMobilePhone'=>'����ֻ���',
	'virCardNo'=>'����˻�',
	'inContractNo'=>'��ǩԼ��',
	'inMobilePhone'=>'���ֻ���',
	'virCardNoIn'=>'���˺�',
	'repaymentType'=>'��������',
	'isInFull'=>'�Ƿ�ȫ���',
	'frontMerUrl'=>'�̻�ǰ̨֪ͨ��ַ',
	'backgroundMerUrl'=>'�̻���̨֪ͨ��ַ',
	'signValue'=>'���Ĵ�',
	'respCode'=>'��ѯ��Ӧ��',
	'msgExt'=>'��ѯ��Ӧ�����Ϣ',
	'noKey'=>'δ����'
	);
$HX_config['tg_return_key'] = array(
	'bidId'=>'���',
	'mobilePhone'=>'�ֻ�����',
	'merOrderNum'=>'������',
	'tranAmt'=>'���׽��',
	'tranDateTime'=>'��������',
	'tranIP'=>'����ip',
	'msgExt'=>'������Ϣ',
	'respCode'=>'������Ӧ��',
	//'signValue'=>'���Ĵ�',
	'feeAmt'=>'����������',
	'feePayer'=>'������֧����',
	'orderId'=>'�����ڲ�������',
	'tranFinishTime'=>'�������ʱ��',
	'gopayFeeAmt'=>'����������',
	'riskBalance'=>'���ս�',
	//'frontMerUrl'=>'�̻�ǰ̨֪ͨ��ַ',
	//'backgroundMerUrl'=>'�̻���̨֪ͨ��ַ',
	'mercFeeAmt'=>'�̻�Ӷ��',
	//'repaymentType'=>'��������',
	//'isInFull'=>'�Ƿ����һ�ڻ���',
	'returnFailedMsg'=>'ʧ����Ϣ',
	'noKey'=>'δ����'
);
if( !function_exists('HX_gettype')){
	function HX_gettype($type){
		$a = '';
		switch ($type) {
			case 'P000':
				$a = '����';
				break;
			case 'P001':
				$a = 'Ͷ��';
				break;
			case 'P002':
				$a = '����';
				break;
			case 'P003':
				$a = '����';
				break;
			case 'P004':
				$a = '����';
				break;
			case 'P012':
				$a = '�ۿ�';
				break;
			case 'P007':
				$a = '����';
				break;
			case 'P009':
				$a = '��ֵ';
				break;
			case 'P010':
				$a = '����';
				break;
			case 'P012':
				$a = '�ۿ�';
				break;
		}
		return $a;
	}
}
//������ת��Ϊ����
if( !function_exists('HX_objtoarr')){
	function HX_objtoarr($obj){
		$ret = array();
		foreach($obj as $key =>$value){
			if(gettype($value) == 'array' || gettype($value) == 'object'){
				$ret[$key] = HX_objtoarr($value);
			}
			else{
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}
//��xmlת��Ϊ����
if( !function_exists('HX_XmlToArr')){
	function HX_XmlToArr($xmlstr, $isFirst = false){
		$xml = simplexml_load_string($xmlstr);
		$a = json_decode(json_encode($xml),TRUE);
		$a = HX_Utf8ToGbk($a);
		return $a;
		
		$xml = simplexml_load_string($xmlstr);
		$xmlArray =array();
		foreach ($xml->children() as $key => $value)
		{
			if($value->children() && count($value) > 0)
			{ 
				if (isset($xmlArray[$key]))
				{
					if (!$isFirst)
					{
						$isFirst = true;
						$temp = $xmlArray[$key];
						$xmlArray[$key] = array();
						$xmlArray[$key][] = iconv('utf-8','gbk',$temp);
						parseXml($value, $xmlArray[$key][], $isFirst);
					}
					else
					{
						parseXml($value, $xmlArray[$key][], $isFirst);
					} 
				}
				else
				{
					$xmlArray[$key] = array();
					parseXml($value, $xmlArray[$key], $isFirst);
				}
			}
			else
			{
				if (isset($xmlArray[$key]))
				{
					if (count($xmlArray[$key]) < 2)
					{
						$temp = $xmlArray[$key];
						$xmlArray[$key] = array();
						$xmlArray[$key][] = iconv('utf-8','gbk',$temp);
					}
					$xmlArray[$key][] = iconv('utf-8','gbk',(string)$value);
				}else{
					$xmlArray[$key] = iconv('utf-8','gbk',(string)$value);
				}
			}
		}
		return $xmlArray;
	}
}

//��utf8װ��Ϊgbk
if( !function_exists('HX_Utf8ToGbk')){
	function HX_Utf8ToGbk($str){
		if(is_array($str)){
			foreach($str as $k=>$v){
				if(is_array($v)){
					$str[$k]=HX_Utf8ToGbk($v);
				}else{
					$str[$k]=iconv('utf-8','gbk',$v);
				}
			}
		}else{
			return iconv('utf-8','gbk',$str);
		}
		return $str;
	}
}
?>