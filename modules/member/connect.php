<?php
if (!defined('ROOT_PATH')) die('���ܷ���'); //��ֱֹ�ӷ���

 
function is_email($email){
return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $email);
}

if ($_U['query_class'] == 'login') {
	$title = '��������¼-�������ʺ�';
	$template = 'user_connect.html.php'; 
}else{
	$title = '��������¼-�����ʺ���Ϣ';
	$template = 'user_connectreg.html.php';

}


?>