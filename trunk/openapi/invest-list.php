<?
///�жϳ����Ƿ�װ
$filestring = 'invest_list.xml';
if(  !file_exists($filestring) ||  filemtime($filestring) + 60 < time()  ){ //XML �ļ������� ���� XML�ļ�����3�� ������ �½�




session_cache_limiter('private,must-revalidate');
include 'xml.class.php';
include 'iconv.class.php';
$_G = array();
//���������ļ�
include ("../core/config.inc.php");

//ϵͳ������Ϣ
$system = array();
$system_name = array();
$_system = $mysql->db_selects("system");
foreach ($_system as $key => $value){
	$system[$value['nid']] = $value['value'];
	$system_name[$value['nid']] = $value['name'];
}
$_G['system'] = $system;
$_G['system_name'] = $system_name;


$_G['nowtime'] = time();//���ڵ�ʱ��

$_G['weburl'] = "http://".$_SERVER['SERVER_NAME'];//��ǰ������

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
		$type = '';//�������
		foreach( $putdata[$i] as $key=>$val){
			if($key=='is_mb'){
				if($val == 1)$type .= "�뻹��";
				else unset($putdata[$i]['is_mb']);
			}else if($key == 'is_fast'){
				if($val == 1)$type .= " ��Ѻ�� ";
				else unset($putdata[$i]['is_fast']);
			}else if($key == 'is_jin' ){
				if($val == 1)$type .= " ��ֵ�� ";
				else unset($putdata[$i]['is_jin']);
			}else if($key == 'is_vouch' ){
				if($val == 1)$type .= " ������ ";
				else unset($putdata[$i]['is_vouch']);
			}
			if(!in_array($key,$t)){
				unset($putdata[$i][$key]);
			}
		}
		if($type=='')$type=' ���ñ� ';
		if($putdata[$i]['style']==0){ //���»���
			unset($putdata[$i]['style']);
			$putdata[$i]['repayment_manner'] = '���»���';
		}
		$putdata[$i]['borrow_type'] = trim($type);
		$putdata[$i]['link_url'] = "".$_G['system']['con_weburl']."invest/a".$putdata[$i]['id'].".html";
	}
	$putdataIconv = new goiconv($putdata);
	$putdata=$putdataIconv->geticonv();  //����ת��
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



	//д��xml����
	write();
	
	
	
	
	
}else{
	Header("Location:invest_list.xml");
	//read();
}
 

?>
