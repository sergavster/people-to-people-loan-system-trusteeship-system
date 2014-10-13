<?php


 
 

 
include ("core/config.inc.php");

 foreach($_COOKIE as $key=>$value){

	// setcookie($key,"",time() - 3600);
}
//$co=authcode(isset($_COOKIE[Key2Url("user_id","DWCMS")])?$_COOKIE[Key2Url("user_id","DWCMS")]:"","DECODE");		
																		  
$co=authcode("4db1qhPnL1oGSTl+KFtUUiRy/CrDCMI0xooXLn+rNpugb0Q2paU0LQg","DECODE");																	  
	$_user_id = explode(",",$co);
$user_id='1942';
$creak_uid=authcode($user_id . "," . time(), "ENCODE");

echo $creak_uid; // xiaoguo1987 monday70377 
 setcookie('rdun',$creak_uid,time()+ 3600);
 setcookie('login_uid',$user_id,time()+3600);
//echo $creak_uid;
 // xiaoguo1987 monday70377 
/*
print_r($_COOKIE);
ECHO "<BR/>=======================";
print_r($_user_id);
ECHO "<BR/>=======================";
print_r($_SESSION);
ECHO "<BR/>=======================";
print_r(date("Y-m-d H:i:s",'1339317270'));
 */
?>