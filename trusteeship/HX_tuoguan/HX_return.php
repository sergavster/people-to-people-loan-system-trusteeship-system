<?php
/** 
 * @version v1.0
 */
header("Content-Type:text/html; charset=gbk");
define('IS_TG', true);//�Ƿ�Ϊ�ʽ��й�ģʽ
date_default_timezone_set('Asia/Shanghai');//ʱ������
if(!isset($_POST['sign'])){
	$msg = array('msg'=>'ϵͳ����ȥ��','content'=>'������ҳ','url'=>'/index.php');
	go_message($msg);
}
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . '/');
require_once(ROOT_PATH.'core/common.inc.php');//������Ϣ����
require_once(ROOT_PATH.'core/function.inc.php');//��վ�ĺ���
require_once(ROOT_PATH.'core/mysql.class.php');//���ݿ⴦���ļ�
require_once(ROOT_PATH.'core/user.class.php');//�û�
require_once(ROOT_PATH.'modules/account/account.class.php');
require_once ROOT_PATH.'trusteeship/HX_tuoguan/HX_trusteeship.php';
$TG_OB = new HX_trusteeship($HX_config);
$mysql = new Mysql($db_config);
$user_ob = new userClass();
//ϵͳ������Ϣ
if(file_exists(ROOT_PATH.'data/parameter/parameter_system.php')){
	require_once ROOT_PATH.'data/parameter/parameter_system.php';
}else{
	$system = array();
	$system_name = array();
	$_system = $mysql->db_selects("system");
	foreach ($_system as $key => $value){
		$system[$value['nid']] = $value['value'];
		$system_name[$value['nid']] = $value['name'];
	}
	$_G['system'] = $system;
	$_G['system_name'] = $system_name;
}


$q = explode("/",$_SERVER['QUERY_STRING']);
$s2s=false;
if(isset($q[0])) $query_string=$q[0];
if(isset($q[1]) && $q[1]=='s2s') $s2s=true;


$file = ROOT_PATH."data/pay_cache/test.txt";
$fp = fopen($file , 'a+');
@chmod($file, 0777);
$s = $_POST;
$s['result'] = @iconv('UTF-8','GBK',$s['result']);
fwrite($fp,var_export($s, true).date("Y-m-d H:i:s", time())."\r\n");
fclose($fp);
$parameter = $_POST;
$parameter['result'] = str_replace('\\', '', $parameter['result']);
$arr = HX_XmlToArr($parameter['result']);
$tranCode = '';
switch ($query_string){
	case 'recharge':
		$tranCode = 'P009';
		break;
	case 'cash':
		$tranCode = 'P010';
		break;
}
if($query_string=='recharge' || $query_string=='cash'){
	tg_updateorder(array('err_code'=>$arr['code'],'tg_return'=>serialize($s)),"order_number='{$arr['order_id']}' and tran_code='{$tranCode}'");
}


$md5sgin = md5($parameter['result'].'~|~'.$HX_config['argMerKey']);

if($md5sgin!=$parameter['sign'] || $query_string==''){
	$argContent = @iconv('UTF-8','GBK',$parameter['result']);
	$mysql->db_query("insert into {tg_error} set arg_content='{$argContent}',p_sign='{$md5sgin}',tg_return='".serialize($parameter)."'");
	$msg = array('msg'=>'��Ϣ����','content'=>'�����˺�����','url'=>'/index.action?user');
	go_message($msg);
}


//�����������
if($query_string=='openAnAccount'){
	if($arr['code']=='CSD000'){
		$update['user_id'] = $arr['user_id'];
		$update['pIpsAcctNo'] = $arr['user_id'];
		$update['is_tgAccount'] = 1;
		$update['pIpsAcctDate'] = time();
		$update['real_status'] = 1;
		$a=$user_ob->UpdateUser($update);
		echo_err('ok');
	}
	echo_err('fail');
	exit();
}

//��ֵ�������
elseif($query_string=='recharge'){
	if($arr['code']=='CSD000' || $arr['code']==1){
		$order_id = $arr['order_id'];//������
		$incash_money = $arr['incash_money'];//ʵ�ʵ���
		$fee = $arr['fee'];//ƽ̨��ȡ����
		
		$file = ROOT_PATH."data/pay_cache/".$order_id;
		$fp = fopen($file , 'w+');
		@chmod($file, 0777);
		if(flock($fp , LOCK_EX | LOCK_NB)){//�趨ģʽ��ռ�����Ͳ���������
			accountClass::OnlineReturn(array("trade_no"=>$order_id));
			$msg = array('msg'=>'��ֵ�ɹ�','content'=>'�����˺�����','url'=>'/index.action?user');
			flock($fp , LOCK_UN);
		} else{
			$msg = array('msg'=>'����������','content'=>'�����˺�����','url'=>'/index.action?user');
		}     
		fclose($fp);
	}else{
		$msg = array('msg'=>'��ֵʧ��','content'=>'�����˺�����','url'=>'/index.action?user');
	}
	go_message($msg);
}

//���ַ���
elseif($query_string=='cash'){
	if($arr['code']=='CSD000' || $arr['code']==1){
		$order_id = explode('_', $arr['order_id']);
		$order_id = $order_id[0];
		$a = accountClass::hxUpdateCash(array('trade_no'=>$order_id,'status'=>0));
		$b = accountClass::hxUpdateCash(array('trade_no'=>$order_id,'status'=>1));
		$msg = array('msg'=>'��������ɹ�','content'=>'�����˺�����','url'=>'/index.action?user');
	}else{
		$msg = array('msg'=>'��������ʧ��','content'=>'�����˺�����','url'=>'/index.action?user');
	}
	go_message($msg);
	
}

//Ͷ�귵��
elseif($query_string=='tender'){

}

//����
elseif($query_string=='review'){
	
}

//����
elseif($query_string=='repayment'){

}

//��½
elseif($query_string=='login'){
	$msg = array('msg'=>$parameter['msgExt'],'content'=>'������һҳ','url'=>'/index.php?user');
}
function go_message($msg){
	setCookie('message',base64_encode(serialize($msg)),time()+120,'/');
	header("location:/index.php?message");
	exit();
}
function echo_err($msg){
	echo $msg;
	exit();
}
?>