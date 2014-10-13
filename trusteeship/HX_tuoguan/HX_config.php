<?php
/*
*宝付的一些固定配置，和共用方法
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
$HX_config['argMerCode'] = '400695';//商户号
$HX_config['argTerminalId'] = "19824";//终端号
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

$HX_config['repaymentTranCode'] = 'P004';//还款
$HX_config['cashTranCode'] = 'P010';//提现
$HX_config['rechargeTranCode'] = 'P009';//充值
$HX_config['repaymentTranCode'] = 'P004';//还款
$HX_config['tenderTranCode'] = 'P001';//投标
$HX_config['fullTranCode'] = 'P003';//复审
$HX_config['liubiaoTranCode'] = 'P002';//流标
$HX_config['addBorrowTranCode'] = 'P000';//发标
$HX_config['apenAccountTranCode'] = 'P007';//'开户'
$HX_config['deductTranCode'] = 'P012';//'扣款'
$HX_config['cx_return_key'] = array(
	'order_id'=>'订单号',
	'state'=>'操作是否成功',
	'succ_amount'=>'成功金额',
	'succ_time'=>'成功时间',
	'fee'=>'商户费率',
	'baofoo_fee'=>'宝付手续费',
	'fee_taken_on'=>'手续费收取方',
	'bidId'=>'标号',
	'outContractNo'=>'出款方签约号',
	'outMobilePhone'=>'出款方手机号',
	'virCardNo'=>'出款方账户',
	'inContractNo'=>'入款方签约号',
	'inMobilePhone'=>'入款方手机号',
	'virCardNoIn'=>'入款方账号',
	'repaymentType'=>'还款类型',
	'isInFull'=>'是否全额还款',
	'frontMerUrl'=>'商户前台通知地址',
	'backgroundMerUrl'=>'商户后台通知地址',
	'signValue'=>'密文串',
	'respCode'=>'查询响应码',
	'msgExt'=>'查询响应结果信息',
	'noKey'=>'未定义'
	);
$HX_config['tg_return_key'] = array(
	'bidId'=>'标号',
	'mobilePhone'=>'手机号码',
	'merOrderNum'=>'订单号',
	'tranAmt'=>'交易金额',
	'tranDateTime'=>'交易日期',
	'tranIP'=>'交易ip',
	'msgExt'=>'交易信息',
	'respCode'=>'交易响应码',
	//'signValue'=>'密文串',
	'feeAmt'=>'交易手续费',
	'feePayer'=>'手续费支付方',
	'orderId'=>'宝付内部订单号',
	'tranFinishTime'=>'交易完成时间',
	'gopayFeeAmt'=>'宝付手续费',
	'riskBalance'=>'风险金',
	//'frontMerUrl'=>'商户前台通知地址',
	//'backgroundMerUrl'=>'商户后台通知地址',
	'mercFeeAmt'=>'商户佣金',
	//'repaymentType'=>'还款类型',
	//'isInFull'=>'是否最后一期还款',
	'returnFailedMsg'=>'失败信息',
	'noKey'=>'未定义'
);
if( !function_exists('HX_gettype')){
	function HX_gettype($type){
		$a = '';
		switch ($type) {
			case 'P000':
				$a = '发标';
				break;
			case 'P001':
				$a = '投标';
				break;
			case 'P002':
				$a = '流标';
				break;
			case 'P003':
				$a = '复审';
				break;
			case 'P004':
				$a = '还款';
				break;
			case 'P012':
				$a = '扣款';
				break;
			case 'P007':
				$a = '开户';
				break;
			case 'P009':
				$a = '充值';
				break;
			case 'P010':
				$a = '提现';
				break;
			case 'P012':
				$a = '扣款';
				break;
		}
		return $a;
	}
}
//将对象转换为数组
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
//将xml转换为数组
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

//将utf8装换为gbk
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