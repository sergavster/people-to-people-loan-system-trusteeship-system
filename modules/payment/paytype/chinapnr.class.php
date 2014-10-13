<?php
class chinapnrPayment {

	var $name = '汇付天下';//汇付天下
	var $description = "汇付天下";
	var $type = 1;//1->只能启动，2->可以添加
    var $logo = 'chinapnr';
    var $version = "10"; //版本	version
    var $charset = 'gbk';

	
	public static function ToSubmit($payment){
		$form_url = 'https://mas.chinapnr.com/gar/RecvMerchant.do'; //生产环境		
		$payment['MerId'] = "871687"; //商户号
		$submitDate=date('Ymd',time());//订单提交日期	
		$submitTime=date('His',time());//订单提交时间	
		$user_id = $payment['user_id']; 
		$totalAmount=number_format($payment['money'], 2, '.', '');
		$Version = "10";
		$CmdId = "Buy";
		$MerId = "871687";
		$OrdId = trim($payment['trade_no']);
		$OrdAmt = trim($totalAmount);
		$CurCode = "RMB";
		$Pid = "1111";
		$RetUrl = "http://hndai.p2p.com/modules/payment/chinapnr_return.php";
		$BgRetUrl = "http://hndai.p2p.com/modules/payment/chinapnr_return.php";
		$MerPriv = "1111";
		$GateId = "";
		$UsrMp = "";
		$DivDetails = "";
		$PayUsrId = "";
			
	$fp = fsockopen("127.0.0.1", '8733', $errno, $errstr, 10);
	if (!$fp) {
		echo "$errstr ($errno)<br />\n";
	} else {
		
		$MsgData = $Version.$CmdId.$MerId.$OrdId.$OrdAmt.$CurCode.$Pid.$RetUrl.$MerPriv.$GateId.$UsrMp.$DivDetails.$PayUsrId.$BgRetUrl;
		$MsgData_len =strlen($MsgData);
		if($MsgData_len < 100 ){
			$MsgData_len = '00'.$MsgData_len;
		}
		elseif($MsgData_len < 1000 ){
			$MsgData_len = '0'.$MsgData_len;
		}

		$out = 'S'.$MerId.$MsgData_len.$MsgData;
		
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
		$ChkValue = substr($ChkValue, -264,-8);
		fclose($fp);
		//echo $ChkValue;
	}
	
		 header("Content-type:text/html; charset=gbk"); 
	?>
			<html>
			  <head>
				<title>跳转......</title>
				<meta http-equiv="content-Type" content="text/html; charset=gbk" />
			  </head>
			  <body>
			  <div style="display:none">
			  <?php echo $ChkValue;?>
			  </div>
			 进入汇付天下支付页面>>>>>>>>
				<form id="frm1" name="frm1" method="post" action="https://mas.chinapnr.com/gar/RecvMerchant.do" target="_blank">
			 <div style="display:block">
				 <textarea name="ChkValue" cols="60" rows="6"><?=$ChkValue?></textarea>
				</div>
				  <input type=hidden name="Version" value="<?=$Version?>">
				  <input type=hidden name="CmdId" value="<?=$CmdId?>">
				  <input type=hidden name="MerId" value="<?=$MerId?>">
				  <input type=hidden name="OrdId" value="<?=$OrdId?>">
				  <input type=hidden name="OrdAmt" value="<?=$OrdAmt?>">
				  <input type=hidden name="CurCode" value="<?=$CurCode?>">
				  <input type=hidden name="Pid" value="<?=$Pid?>">
				  <input type=hidden name="RetUrl" value="<?=$RetUrl?>">
				  <input type=hidden name="BgRetUrl" value="<?=$BgRetUrl?>">
				  <input type=hidden name="MerPriv" value="<?=$MerPriv?>">
				  <input type=hidden name="GateId" value="<?=$GateId?>">
				  <input type=hidden name="UsrMp" value="<?=$UsrMp?>">
				  <input type=hidden name="DivDetails" value="<?=$DivDetails?>">
				  <input type=hidden name="PayUsrId" value="<?= $PayUsrId ?>">  
				</form>
				<script language="javascript">
				  document.getElementById("frm1").submit();
				</script>
			  </body>
			</html>
<?php
		exit;
		
	} // ToSubmit 
	function GetFields(){
		return array(
				'member_id'=>array(
						'label'=>'客户号',
						'type'=>'string'
				),
				'PrivateKey'=>array(
						'label'=>'私钥',
						'type'=>'string'
				),
				'authtype'=>array(
					'label'=>'其他',
					'type'=>'string'
				)
		);
	}
} // class 
?>