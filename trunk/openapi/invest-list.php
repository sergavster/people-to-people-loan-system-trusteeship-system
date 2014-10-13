<?
///判断程序是否安装
$filestring = 'invest_list.xml';
if(  !file_exists($filestring) ||  filemtime($filestring) + 60 < time()  ){ //XML 文件不存在 或者 XML文件超过3秒 就重新 新建




session_cache_limiter('private,must-revalidate');
include 'xml.class.php';
include 'iconv.class.php';
$_G = array();
//基本配置文件
include ("../core/config.inc.php");

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

include_once("../modules/borrow/borrow.class.php");



$filestring = ROOT_PATH.'/openapi/invest_list.xml';



 
function write(){
	global $filestring;
	$handle = fopen($filestring,'w');
	$data['order']="account_up";
	$data['site_id'] ='7';
	$data['var'] ='loop';
	$data['epage'] = 70;
	$data['type'] = '';
	$result = borrowClass::GetList($data);
	
	$putdata=$result['list'];
	
	$t = array("id","name", "style", "account_format", "apr", "funds", "time_limit", "scale", "user_id", "username", "addtime", "time_limit_day");
	for($i = 0; $i < count($putdata); $i++){
		$type = '';//标的类型
		foreach( $putdata[$i] as $key=>$val){
			if($key=='is_mb'){
				if($val == 1)$type .= "秒还标";
				else unset($putdata[$i]['is_mb']);
			}else if($key == 'is_fast'){
				if($val == 1)$type .= " 抵押标 ";
				else unset($putdata[$i]['is_fast']);
			}else if($key == 'is_jin' ){
				if($val == 1)$type .= " 净值标 ";
				else unset($putdata[$i]['is_jin']);
			}else if($key == 'is_vouch' ){
				if($val == 1)$type .= " 担保标 ";
				else unset($putdata[$i]['is_vouch']);
			}
			if(!in_array($key,$t)){
				unset($putdata[$i][$key]);
			}
		}
		if($type=='')$type=' 信用标 ';
		if($putdata[$i]['style']==0){ //按月还款
			unset($putdata[$i]['style']);
			$putdata[$i]['repayment_manner'] = '按月还款';
		}
		$putdata[$i]['borrow_type'] = trim($type);
		$putdata[$i]['link_url'] = "".$_G['system']['con_weburl']."invest/a".$putdata[$i]['id'].".html";
	}
	$putdataIconv = new goiconv($putdata);
	$putdata=$putdataIconv->geticonv();  //汉字转码
	$xml = new array2xml($putdata,'utf-8');
	$xmldata=$xml->getXml();
	fwrite($handle,$xmldata);
	header('content-type:text/xml');
	print_r($xmldata);
	fclose();
}

function read(){
	global $filestring;
	$handle = fopen($filestring,'r');
	$xmldata = '';
	while( !feof($handle) ){
		$xmldata .= fread($handle, 1024);
	}
	header('content-type:text/xml');
	print_r($xmldata);
	fclose();
} 



	//写入xml数据
	write();
	
	
	
	
	
}else{
	Header("Location:invest_list.xml");
	//read();
}
 

?>
