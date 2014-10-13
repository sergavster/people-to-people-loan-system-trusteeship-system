<?php
/******************************
 * $File: config.inc.php
 * $Description: ��վ�����ļ�
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
 * $UpdateDate:None 
******************************/

Define("YJ_SysError",false);

//���������ʽ
IF(YJ_SysError){
	Function MyError($code, $msg, $file, $line,$context){
		$IntTime=(time()+8*3600);$Now=Gmdate("Y-m-d H:i:s",$IntTime);
		$Err='['.$Now."] Fatal error: $msg in $file on line $line".Chr(10);
		Error_log($Err,3,ROOT_PATH_WEBERROR);
		//Die();
	}
	Set_error_handler('MyError');
}

session_cache_limiter('private,must-revalidate');
session_start();//�򿪻���
error_reporting(E_ALL || ~E_NOTICE);
//error_reporting(E_ALL );//�������д���

//define('ROOT_PATH', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) )."/../" );//��Ŀ¼
define('ROOT_PATH', dirname(__FILE__) . '/../');
header('Content-Type:text/html;charset=GB2312');
define('IN_TEMPLATE', "TRUE");
 
//�趨log������Ŀ¼
define('ROOT_PATH_WEBERROR', ROOT_PATH."log/error.log"); //ϵͳ����log
define('ROOT_PATH_LZREPAY', ROOT_PATH."log/autoLZ.log"); //��ת������log
define('ROOT_PATH_SAFE', ROOT_PATH."log/safe.log"); //safeģ�� ע�����Ϣlog
define('IS_TG', true);//�Ƿ�Ϊ�ʽ��й�ģʽ


//�趨cacha������Ŀ¼
$cachepath['pay']=ROOT_PATH."data/pay_cache/"; //����֧�����ļ�log��¼
$cachepath['html']=ROOT_PATH."data/html_cache/"; //��վ�������ļ�����·��
$cachepath['html_cachetime']=20; //��վ�������ļ�����ʱ�� ��λ����
$cachepath['html_type']= array("index.html", "invest.html"); //��վ������ļ�����
$cachepath['html_on']=false; //��վ�������ļ����湦�ܣ�false�ر�,true����

//ϵͳ��������
$rdGlobal['lz_reBackTime']=3600; //��ת����ǰ����ʱ��ع� 0Ϊ������3600 Ϊ��ǰ1Сʱ
$rdGlobal['lz_awardfirst']=false; //��ת�꽱�� trueͶ��ɹ��ͷ��� false�ع�ʱ����
$rdGlobal['serial_on']=false; //��̨U�ܵ�¼��false�ر�,true����
$rdGlobal['uc_on']=false; //UC�ӿڿ�����false�ر�,true����
$rdGlobal['uc_key']="8f5cAiE8xhZYMOarnRuL3o5+7DT6Z+sigPHNYaI"; //UC ������Կ
$rdGlobal['admin_login_forbid']=true; //�Ƿ��ֹ����Ա�ʻ���¼ǰ̨
$rdGlobal['cache_para_forbid']=true; //�Ƿ��ֹ����Ա�ʻ���¼ǰ̨

/*   ��ʼ������  
@ini_set('memory_limit',          '64M');
@ini_set('session.cache_expire',  180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        1); */

/* �жϲ�ͬϵͳ�ָ��� */
if (DIRECTORY_SEPARATOR == '\\'){
    @ini_set('include_path','.;' . ROOT_PATH);
}else{
    @ini_set('include_path','.:' . ROOT_PATH);
}

date_default_timezone_set('Asia/Shanghai');//ʱ������

//memcache ��ʹ��
$memcache_result  = "0";
$memcache = "";
$memcachelife = "60";
/*
$memcache = new Memcache;  
$memcache->addServer('localhost', 11211);
$memcache_result = $memcache->getserverstatus('localhost', 11211); 
if ($memcache_result!=0){
	$memcache->connect('localhost', 11211) ; 
}

*/

require_once(ROOT_PATH.'core/common.inc.php');//������Ϣ����
//require_once(ROOT_PATH.'core/hack_safe.inc.php');//������Ϣ����

require_once(ROOT_PATH.'core/function.inc.php');//��վ�ĺ���

require_once(ROOT_PATH.'core/safe.inc.php');//��ȫ����

require_once(ROOT_PATH.'core/input.inc.php');//�������Ϣ

require_once(ROOT_PATH.'core/mysql.class.php');//���ݿ⴦���ļ�

require_once(ROOT_PATH.'core/apply.class.php');//����������

require_once(ROOT_PATH.'core/system.class.php');//ϵͳ����
$mysql = new Mysql($db_config);
//$mysql->db_show_msg(true);

require_once('module.class.php');//ģ��Ĵ���
$module = new moduleClass();

require_once('page.class.php');//��ҳ��ʾ
$page = new Page();

require_once('pages.class.php');//��ҳ��ʾ2
$pages = new pages();
$_G['class_pages'] = $pages;

require_once('magic.class.php');//ģ������
$magic = new Magic();

require_once('user.class.php');//�û�
$user = new userClass();

require_once('upload.class.php');//�ϴ��ļ�ˮӡ��������
$upload = new upload();

require_once(ROOT_PATH.'trusteeship/HX_tuoguan/HX_trusteeship.php');
$TG_OB = new HX_trusteeship($HX_config);

$_log['url'] = $_SERVER['QUERY_STRING'];
$_log['query'] = !isset($_REQUEST['q'])?'':$_REQUEST['q'];

?>
