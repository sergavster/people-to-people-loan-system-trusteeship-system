<?php
include_once (ROOT_PATH."/core/config.inc.php");
$_user_id = explode(",",authcode(isset($_COOKIE[Key2Url("user_id","DWCMS")])?$_COOKIE[Key2Url("user_id","DWCMS")]:"","DECODE"));

$_G['user_id'] = $_user_id[0];
?>


<div><strong>ĞÕÃû£º</strong><? $_G['user_result']['realname']?></div>
<div><strong>ÊÖ»ú£º</strong><? $_G['user_result']['phone']?></div>
<div><strong>ÓÊÏä£º</strong><? $_G['user_result']['email']?></div>
<div><strong>QQ£º</strong><? $_G['user_result']['qq']?></div>
<? if (!empty($id)){?>
$("#<? echo $name;?>").change(function(){
	var val = $(this).val();
	$("#<? echo $id;?>").val(val);

})
<? }?>