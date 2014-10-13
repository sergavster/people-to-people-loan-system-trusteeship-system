<?php
/******************************
 * $File: config.inc.php
 * $Description: 网站配置文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
 * $UpdateDate:None 
******************************/

Define("YJ_SysError",false);

//错误输出方式
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
session_start();//打开缓存
error_reporting(E_ALL || ~E_NOTICE);
//error_reporting(E_ALL );//报告所有错误

//define('ROOT_PATH', ereg_replace("[/\\]{1,}", '/', dirname(__FILE__) )."/../" );//根目录
define('ROOT_PATH', dirname(__FILE__) . '/../');
header('Content-Type:text/html;charset=GB2312');
define('IN_TEMPLATE', "TRUE");
 
//设定log缓存存放目录
define('ROOT_PATH_WEBERROR', ROOT_PATH."log/error.log"); //系统运行log
define('ROOT_PATH_LZREPAY', ROOT_PATH."log/autoLZ.log"); //流转标运行log
define('ROOT_PATH_SAFE', ROOT_PATH."log/safe.log"); //safe模块 注入等信息log
define('IS_TG', true);//是否为资金托管模式


//设定cacha缓存存放目录
$cachepath['pay']=ROOT_PATH."data/pay_cache/"; //在线支付的文件log记录
$cachepath['html']=ROOT_PATH."data/html_cache/"; //网站编译后的文件缓存路径
$cachepath['html_cachetime']=20; //网站编译后的文件缓存时间 单位：秒
$cachepath['html_type']= array("index.html", "invest.html"); //网站编译的文件缓存
$cachepath['html_on']=false; //网站编译后的文件缓存功能，false关闭,true开启

//系统参数配置
$rdGlobal['lz_reBackTime']=3600; //流转标提前多少时间回购 0为当即，3600 为提前1小时
$rdGlobal['lz_awardfirst']=false; //流转标奖励 true投标成功就发放 false回购时发放
$rdGlobal['serial_on']=false; //后台U盾登录，false关闭,true开启
$rdGlobal['uc_on']=false; //UC接口开启，false关闭,true开启
$rdGlobal['uc_key']="8f5cAiE8xhZYMOarnRuL3o5+7DT6Z+sigPHNYaI"; //UC 加密密钥
$rdGlobal['admin_login_forbid']=true; //是否禁止管理员帐户登录前台
$rdGlobal['cache_para_forbid']=true; //是否禁止管理员帐户登录前台

/*   初始化设置  
@ini_set('memory_limit',          '64M');
@ini_set('session.cache_expire',  180);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies',   1);
@ini_set('session.auto_start',    0);
@ini_set('display_errors',        1); */

/* 判断不同系统分隔符 */
if (DIRECTORY_SEPARATOR == '\\'){
    @ini_set('include_path','.;' . ROOT_PATH);
}else{
    @ini_set('include_path','.:' . ROOT_PATH);
}

date_default_timezone_set('Asia/Shanghai');//时区配置

//memcache 的使用
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

require_once(ROOT_PATH.'core/common.inc.php');//基本信息设置
//require_once(ROOT_PATH.'core/hack_safe.inc.php');//基本信息设置

require_once(ROOT_PATH.'core/function.inc.php');//整站的函数

require_once(ROOT_PATH.'core/safe.inc.php');//安全设置

require_once(ROOT_PATH.'core/input.inc.php');//表单相关信息

require_once(ROOT_PATH.'core/mysql.class.php');//数据库处理文件

require_once(ROOT_PATH.'core/apply.class.php');//报名处理类

require_once(ROOT_PATH.'core/system.class.php');//系统设置
$mysql = new Mysql($db_config);
//$mysql->db_show_msg(true);

require_once('module.class.php');//模块的处理
$module = new moduleClass();

require_once('page.class.php');//分页显示
$page = new Page();

require_once('pages.class.php');//分页显示2
$pages = new pages();
$_G['class_pages'] = $pages;

require_once('magic.class.php');//模板引擎
$magic = new Magic();

require_once('user.class.php');//用户
$user = new userClass();

require_once('upload.class.php');//上传文件水印裁切设置
$upload = new upload();

require_once(ROOT_PATH.'trusteeship/HX_tuoguan/HX_trusteeship.php');
$TG_OB = new HX_trusteeship($HX_config);

$_log['url'] = $_SERVER['QUERY_STRING'];
$_log['query'] = !isset($_REQUEST['q'])?'':$_REQUEST['q'];

?>
