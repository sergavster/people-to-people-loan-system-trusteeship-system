<?php
exit();
if($_G['user_id']==""){
	header("location:/index.php?user");
	exit();
}
require_once ROOT_PATH.'modules/borrow/borrow.class.php';
$ob = new borrowClass();
$id = (int)$_REQUEST["borrow_id"];
$borrow = $ob->GetTenderList(array("borrow_id"=>$id,"limit"=>"all"));
$var = $ob->CheckBorrowTender(array("borrow"=>$id,"user_id"=>$_G['user_id']));
if($var['checkStatus'] < 1){
	echo '<script>alert("对不起，您不是该借款标的投资人，无权查看借款协议书，谢谢!");location.href="/index.php?user";</script>';
	exit();
}
$var = $ob->GetOne(array("id"=>$id));
if($var['status']!=3){
	echo '<script>alert("您的操作有误");location.href="/index.php?user";</script>';
	exit();
}

//define('FPDF_FONTPATH','plugins/fpdf17/font/');
require_once ROOT_PATH.'plugins/fpdf17/chinese.php';
$pdf=new PDF_Chinese();
$pdf -> AddGBFont ('fs', '仿宋体');
$pdf->AddPage();
$pdf -> SetFont ('fs', '', 20);

$h = 5;
$pdf->MultiCell(0,10,"借款协议书",0,"C");
$pdf -> SetFont ('fs', '', 12);
$pdf->ln();
$pdf->Write($h,"借款协议号:{$var['addtime']}{$var['number_id']}");
$pdf->ln();
$pdf->Write($h,"借款人:{$var['username']}");
$pdf->ln();
if($var['biao_type']=="vouch"){
	$voucher_list = str_replace('|', ', ', $var['vouch_user']);
	$pdf->Write($h,"担保人:{$voucher_list}");
	$pdf->ln();
}
$pdf->Write($h,"出借人:详见本协议第一条 ");
$pdf->ln();
$t = date('Y-m-d',$var['repayment_time']);
$pdf->Write($h,"签订日期:{$t}");
$pdf->ln();
$t = $_G["system"]["con_webname"];
$pdf->Write($h,'借款人通过'.$t.'网站(以下简称“本网站”)的居间,就有关借款事项与各出借人达成如下协议：');
$pdf->ln();
$pdf->Write($h,"第一条：借款详情如下表所示：");
$pdf->ln();

$array = array(
		"username"=>"出借人(id)",
		"tender_account"=>"借款金额",
		"time_limit"=>"借款期限",
		"apr"=>"年利率",
		"success_time"=>"借款开始日",
		"end_time"=>"借款到期日",
		"each_time"=>"截止还款日",
		"repayment"=>"还款本息"
);
$Mywidth=array(40,25,20,15,23,23,23,25);
	
$Mytable=array(
		$array["username"],
		$array["tender_account"],
		$array["time_limit"],
		$array["apr"],
		$array["success_time"],
		$array["end_time"],
		$array["each_time"],
		$array["repayment"]
);

$pdf->Row($Mywidth,$Mytable,10);
$pdf->ln();

$vor = $ob->GetTenderList(array("borrow_id"=>$id,"limit"=>"all"));

foreach($vor as $key=>$bor){
	
	if(PROTOCOL == "_fast" || PROTOCOL == "_xin" || PROTOCOL == "_jin"){
		if($var['isday']==1 || $var['style']==2){
			$bor['each_time'] = date("Y-m-d",$var['end_time']);
		}else{
			$bor['each_time'] = $var['each_time'];
		}
	}else if(PROTOCOL == "_miao" || PROTOCOL == ""){
		
		$bor['each_time'] = $var['each_time'];
		
	}
	if($var['isday']==1){
		$bor['time_limit'] = $var['time_limit_day']."天";
	}else{
		$bor['time_limit'] = $var['time_limit']."个月";
	}
	
	$as = array(
			$bor['username'],
			$bor['tender_account']."元",
			$bor['time_limit'],
			$var['apr']."%",
			date("Y-m-d",$var['success_time']),
			date("Y-m-d",$var['end_time']),
			$bor['each_time'],
			round($bor['equal']['monthly_repayment'], 2)."元"
			);
$pdf->Row($Mywidth,$as,10);
$pdf->ln();

}

$pdf->ln();

$content = strip_tags($_G['site_result']['content']);

$arr = preg_split("/&nbsp;/",$content);

for($i=0;$i<count($arr);$i++){
	if($arr[$i]==""){
		continue;
	}
	$pdf->Write($h,$arr[$i]);
	$pdf->ln();
}
$pdf->ln();
$nowX = $pdf->GetX();
$nowY = $pdf->GetY();
$imageW = $imageH = 30;
$imageX = 150;
if($nowY>(300-$imageH)){
	$imageY = 290-$imageH;
}else{
	$imageY = $nowY;
}
$gz_file = "http://".$_SERVER['SERVER_NAME']."/data/images/zsdgz.png";
$pdf->Image($gz_file,$imageX,$imageY,$imageW,$imageH);
$pdf->Output('借款协议书.pdf',D);
exit();

?>