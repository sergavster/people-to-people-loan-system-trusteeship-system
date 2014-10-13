<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("email_".$_t);//检查权限

$style = 4;
$result = $mysql->db_selects("system","style={$style}");
if (!$result) {
    $sql = "INSERT INTO `{system}` (`name` ,`nid` ,`value` ,`type` ,`style` ,`status`)VALUES
                ('SMTP服务器', 'con_email_host', '', '0', '{$style}', '0'),
                ('SMTP服务器是否需要验证', 'con_email_auth', '1', '1', '{$style}', '0'),
                ('邮箱地址', 'con_email_email', '', '0', '{$style}', '0'),
                ('邮箱密码', 'con_email_pwd', '', '0', '{$style}', '0'),
                ('发件人Email', 'con_email_from', '', '0', '{$style}', '0'),
                ('发件人昵称或姓名', 'con_email_from_name', '', '0', '{$style}', '0')";
    $mysql->db_query($sql);
}
if ('reg' == $t) {
    
}
else{
    if (isset($_POST['submit'])) {
        $value = isset($_POST['value'])?$_POST['value']:array();
        $sql = array();
        foreach ($value as $key => $var) {
            array_push($sql, "set value='{$var}' where style='{$style}' and nid='{$key}'");
        }
        $sql = 'update {system} ' . implode(';update {system} ', $sql) . ';';
        $mysql->db_querys($sql);
        $msg = array('操作成功', '', $url);
    }
    else{
        $result = $mysql->db_fetch_arrays("select * from {system} where style={$style}");
        $magic->assign('result', $result);
    }
}
if ($msg!=""){
	$template_tpl = show_msg($msg,$msg_tpl);//如果是信息的则直接读取系统的信息模板
}else{
	$template_tpl = "admin_email.html.php";//如果是其他的，则直接读取模块所在的相应模板
	$magic->assign("template_dir","");
}

$magic->assign("module_tpl",$template_tpl);
?>
