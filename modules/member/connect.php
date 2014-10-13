<?php
if (!defined('ROOT_PATH')) die('不能访问'); //防止直接访问

 
function is_email($email){
return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $email);
}

if ($_U['query_class'] == 'login') {
	$title = '第三方登录-绑定已有帐号';
	$template = 'user_connect.html.php'; 
}else{
	$title = '第三方登录-完善帐号信息';
	$template = 'user_connectreg.html.php';

}


?>