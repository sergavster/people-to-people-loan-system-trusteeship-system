<?
 
session_cache_limiter('private,must-revalidate');

$_G = array();
//基本配置文件
include ("core/config.inc.php");

//系统基本信息
$system = array();
$system_name = array();
$_system = $mysql->db_selects("system");
foreach ($_system as $key => $value){
	$system[$value['nid']] = $value['value'];
	$system_name[$value['nid']] = $value['name'];
}
$_G['system'] = $system;
$_G['system_name'] = $system_name;


$_G['nowtime'] = time();//现在的时间

$_G['weburl'] = "http://".$_SERVER['SERVER_NAME'];//当前的域名

include_once(ROOT_PATH."modules/borrow/borrow.class.php");
$data='';
$result = borrowClass::autoLZRepay($data);//添加借款标
if($result==false){
	$msg = "回购失败!";
}else{
	$msg = "回购{$result}元成功!";
}
$data = date('Y-m-d H:i:s').$msg;

echo $data;

$fp = @fopen(ROOT_PATH_LZREPAY, "ab");
@fwrite($fp, $data."\r\n");
?>
 