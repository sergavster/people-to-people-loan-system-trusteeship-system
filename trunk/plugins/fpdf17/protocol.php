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
	echo '<script>alert("�Բ��������Ǹý����Ͷ���ˣ���Ȩ�鿴���Э���飬лл!");location.href="/index.php?user";</script>';
	exit();
}
$var = $ob->GetOne(array("id"=>$id));
if($var['status']!=3){
	echo '<script>alert("���Ĳ�������");location.href="/index.php?user";</script>';
	exit();
}

//define('FPDF_FONTPATH','plugins/fpdf17/font/');
require_once ROOT_PATH.'plugins/fpdf17/chinese.php';
$pdf=new PDF_Chinese();
$pdf -> AddGBFont ('fs', '������');
$pdf->AddPage();
$pdf -> SetFont ('fs', '', 20);

$h = 5;
$pdf->MultiCell(0,10,"���Э����",0,"C");
$pdf -> SetFont ('fs', '', 12);
$pdf->ln();
$pdf->Write($h,"���Э���:{$var['addtime']}{$var['number_id']}");
$pdf->ln();
$pdf->Write($h,"�����:{$var['username']}");
$pdf->ln();
if($var['biao_type']=="vouch"){
	$voucher_list = str_replace('|', ', ', $var['vouch_user']);
	$pdf->Write($h,"������:{$voucher_list}");
	$pdf->ln();
}
$pdf->Write($h,"������:�����Э���һ�� ");
$pdf->ln();
$t = date('Y-m-d',$var['repayment_time']);
$pdf->Write($h,"ǩ������:{$t}");
$pdf->ln();
$t = $_G["system"]["con_webname"];
$pdf->Write($h,'�����ͨ��'.$t.'��վ(���¼�ơ�����վ��)�ľӼ�,���йؽ��������������˴������Э�飺');
$pdf->ln();
$pdf->Write($h,"��һ��������������±���ʾ��");
$pdf->ln();

$array = array(
		"username"=>"������(id)",
		"tender_account"=>"�����",
		"time_limit"=>"�������",
		"apr"=>"������",
		"success_time"=>"��ʼ��",
		"end_time"=>"������",
		"each_time"=>"��ֹ������",
		"repayment"=>"���Ϣ"
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
		$bor['time_limit'] = $var['time_limit_day']."��";
	}else{
		$bor['time_limit'] = $var['time_limit']."����";
	}
	
	$as = array(
			$bor['username'],
			$bor['tender_account']."Ԫ",
			$bor['time_limit'],
			$var['apr']."%",
			date("Y-m-d",$var['success_time']),
			date("Y-m-d",$var['end_time']),
			$bor['each_time'],
			round($bor['equal']['monthly_repayment'], 2)."Ԫ"
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
$pdf->Output('���Э����.pdf',D);
exit();

?>