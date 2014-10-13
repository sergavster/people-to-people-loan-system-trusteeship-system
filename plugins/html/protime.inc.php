<?php

$timename = !isset($_REQUEST['name'])?"":$_REQUEST['name']."_";
$timetype = !isset($_REQUEST['type'])?"":$_REQUEST['type'];
$time = !isset($_REQUEST['value'])?"":$_REQUEST['value'];
if ($time==""){
$time = !isset($_REQUEST['time'])?"":$_REQUEST['time'];
}
if ($time!=""){
	$_time = explode("-",date("Y-m-d-H-i",$time));
	list($selyear,$selmonth,$seldate,$selhour,$selmin) = $_time;
}
if($timetype!=""){
	$_timetype = explode(",",$timetype);
}else{
	$_timetype= array("y","m","d","h","i");
}
$y = '<select name="'.$timename.'year">';
$nowyear = date("Y",time());
$selyear = empty($selyear)?$nowyear:$selyear;
$y .= "<option value=".($nowyear+1).">".($nowyear+1)."</option>";
for ($i=0;$i<=50;$i++){
	if ($selyear ==($nowyear-$i)){
		$y .= "<option value=".($nowyear-$i)." selected>".($nowyear-$i)."</option>";
	}else{
		$y .= "<option value=".($nowyear-$i).">".($nowyear-$i)."</option>";
	}
}
$y .= '</select>年 ';

$m = '<select name="'.$timename.'month">';
$nowmonth = date("m",time());
$selmonth = empty($selmonth)?$nowmonth:$selmonth;
for ($i=1;$i<=12;$i++){
	if ($selmonth ==$i){
		$m .= "<option value=".$nowmonth." selected>".$i."</option>";
	}else{
		$m .= "<option value=".$i.">".$i."</option>";
	}
}
$m .= '</select>月 ';


$d = '<select name="'.$timename.'date">';
$nowdate = date("d",time());
$seldate = empty($seldate)?$nowdate:$seldate;
for ($i=1;$i<=31;$i++){
	if ($seldate ==$i){
		$d .= "<option value=".$i." selected>".$i."</option>";
	}else{
		$d .= "<option value=".$i.">".$i."</option>";
	}
}
$d .= '</select>日 ';


$h = '<select name="'.$timename.'hour">';
$nowhour = date("H",time());
$selhour = empty($selhour)?$nowhour:$selhour; 
for ($i=0;$i<=23;$i++){
	if ($selhour ==$i){
		$h .= "<option value=".$i." selected>".$i."</option>";
	}else{
		$h .= "<option value=".$i.">".$i."</option>";
	}
}
$h .= '</select>时 ';


$i = '<select name="'.$timename.'min">';
$nowmin = date("i",time());
$selmin = empty($selmin)?$nowmin:$selmin;
for ($j=0;$j<=59;$j++){
	if ($selmin ==$j){
		$i .= "<option value=".$j." selected>".$j."</option>";
	}else{
		$i .= "<option value=".$j.">".$j."</option>";
	}
}
$i .= '</select>分 ';
$display= "";
foreach ($_timetype as $key => $value){
	$display .= $$value;
}
?>

document.write('<? echo $display;?>');