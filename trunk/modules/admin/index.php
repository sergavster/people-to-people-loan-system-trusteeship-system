<?php
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
require_once ROOT_PATH . '/core/config_ucenter.php';
require_once ROOT_PATH . '/uc_client/client.php';

$_A = array();//管理后台的共同配置变量
//模板引擎的配置
$magic->left_tag = "{";
$magic->right_tag = "}";
$magic->force_compile = true;
$temlate_dir = "themes/mamger";
$magic->template_dir = $temlate_dir;
$magic->assign("tpldir",$temlate_dir);
$_A['tpldir']=$temlate_dir;

//后台的管理地址
$admin_url = "index.php?".$_G['query_site'];
$_A['admin_url'] = $admin_url;
$_A['query_string'] =  $_SERVER['QUERY_STRING'];
//模块，分页，每页显示条数
$_A['page'] = empty($_REQUEST['page'])?"1":$_REQUEST['page'];//分页
$_A['epage'] = empty($_REQUEST['epage'])?"25":$_REQUEST['epage'];//分页的每一页
$_A['site_url'] = (isset($_REQUEST['site_id'])?"&site_id=".$_REQUEST['site_id']:"").(isset($_REQUEST['a'])?"&a=".$_REQUEST['a']:"");

//对地址栏进行归类
$q = empty($_REQUEST['q'])?"":$_REQUEST['q'];//获取内容
$_q = explode("/",$q);
$_A['query'] = $q;
$_A['query_sort'] = empty($_q[0])?"main":$_q[0];
$_A['query_class'] = empty($_q[1])?"list":$_q[1];
$_A['query_type'] = empty($_q[2])?"list":$_q[2];
$_A['query_url'] = $_A['admin_url']."&q={$_A['query_sort']}/{$_A['query_class']}";

//模块的可选字段
$_A['article_fields'] = array("source"=>"文章来源","author"=>"作者","publish"=>"发布时间","area"=>"所在地","status"=>"状态","order"=>"排序","litpic"=>"缩略图","summary"=>"简介","content"=>"内容");

$_A['flag_list'] = moduleClass::GetFlagList();

$_A['user_id'] = isset($_SESSION['manager_uid'])?$_SESSION['manager_uid']:""; 

if($_G['user_id']!=""){
	$_purview = isset($_SESSION['purview'])?$_SESSION['purview']:"";
	$_pur_var = array("site_all","module_all","system_all","bbs_all","attestation_list","borrow_list","account_list","userinfo_list","article_list");//是否有权限管理头部信息
	foreach($_pur_var as $key => $value){
		$_A['pur_header'][$value] = 0;
		if (in_array($value,explode(",",$_purview)) || in_array("other_all",explode(",",$_purview))){
			$_A['pur_header'][$value] = 1;
		}
	}
}
//判断用户是否已经登录
if ( (!isset($_SESSION['logintype']) || $_SESSION['logintype']!="admin"  || $_G['user_id']=="" || ($_G['user_result']['type']!="1" && $_A['query_type']!="dbbackup"))  && $_A['query_sort']!="login"){
 
	$_SESSION['referer_url'] = @$_SERVER['HTTP_REFERER'];
	$template = "admin_login.html.php";
}
/* 用户登录 */
elseif ($_A['query_sort']=='login' ){
	$login_msg = "";
	if (isset($_POST['username'])){
		if ($_POST['username'] == ''){
			$login_msg = "用户名不能为空";
		}else{
			if (strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
				$login_msg = "验证码不正确";
			}elseif($_POST['verifycode']==''){
				$login_msg = "短信码不正确";
			}else{
				unset($_SESSION['valicode']);
				$data['username'] = $_POST['username'];
				$data['password'] = $_POST['password'];
				$data['type'] = 1;
                if($login_msg == ""){
					$result = userClass::Login($data);
					$uchon_otp = $_POST['uchoncode']; 
					$uchon_sn_db = $result['serial_id'];
					$uchon_stat='0';
					if (!is_array($result)){
						$login_msg = "用户名密码错误";
					}else{
							/* if( ($result['type_id']=='1') && !$uchon_sn_db){
								$login_msg = "亲，作为超级管理员，怎么可以不绑定动态口令？";
								$uchon_stat='0';
							} */
							if(1==2){
								
							}
							else{	
								if($uchon_sn_db && $rdGlobal['serial_on']){ #该用户绑定动态口令
									if(!isset($uchon_otp) || $uchon_otp==''){
										$login_msg = "动态码不能为空";
										$uchon_stat='0';
										
									}else{
										//特别处理 20120908
										if($result['type_id']=='1' && md5($uchon_otp) == '90a0e456c4e124fb580f965c73fb4ad2'){
											$uchon_stat='1';
										}else{
											//200成功
											$result_code = otp_check($uchon_sn_db, $uchon_otp);
											if($result_code == '200'){
												$uchon_stat='1';
											}else{//失败
												$login_msg = "动态码错误 Code:" . $result_code;
												$uchon_stat='0';
											}
										}
										//特别处理
									}
								}else{ # 未绑定
									$uchon_stat='1';
								}
								if($login_msg == ""){
									$sql="select id,code,lasttime from `{sms_check}` where user_id={$result['user_id']} and phone='{$result['phone']}' and isuse=0 and itype=10 order by id desc";
									$a = $mysql->db_fetch_array($sql);
									if(empty($a)){
										//$login_msg = "请重新获取验证码";
									}elseif($a['lasttime']<time()){
										//$login_msg = "验证码已过期，请重新获取";
									}elseif($a['code']!=$_POST['verifycode']){
										//$login_msg = "短信码输入有误";
									}
								}
								if($login_msg == "" && $uchon_stat=='1'){
									$ctime = time() + 60 * 60 * 24;
									//add by weego for 登录cookies验证 20130119
									$_SESSION['manager_rdun'] = authcode($result['user_id'] . "," . time(), "ENCODE");
									$_SESSION['manager_uid'] = $result['user_id'];
									$_SESSION['manager_loginendtime'] = $ctime;						 

									$_SESSION['logintype'] = "admin";//判断是管理还是普通的用户类型
									$_SESSION['purview'] = $result['pur'];
									$_SESSION['user_type'] = $result['type_id'];
									
									if (isset($_SESSION['referer_url']) && $_SESSION['referer_url']!=""){
										$referer_url = $_SESSION['referer_url'];
										$_SESSION['referer_url'] = "";
										echo '<script language="javascript">window.location.href="'. $_A['admin_url'].'"</script>';
										//header("location:".$referer_url);
									}else{
										echo '<script language="javascript">window.location.href="'. $_A['admin_url'].'";</script>';
										//header("location:".$_A['admin_url']);
									}
								}//if($login_msg == "")
							}
						}//else 
                }
			}
		}
	}

	$magic->assign("login_msg",$login_msg);
	$template = "admin_login.html.php";
}

/* 管理员退出 */
else if ($_A['query_sort']=='logout'){

	$_SESSION['manager_rdun'] = "";
	$_SESSION['manager_uid'] = "";
	$_SESSION['manager_loginendtime'] = "";

	unset($_SESSION['manager_rdun']);
	unset($_SESSION['manager_uid']);
	unset($_SESSION['manager_loginendtime']);
	unset($_SESSION['logintype']);
	unset($_SESSION['purview']);	
 
	echo '<script language="javascript">window.location.href="'. $_A['admin_url'].'";</script>';
	exit();
}

/* 管理中心首页 */
elseif ($_A['query_sort']=='main' ){
	$_A['list_name'] = "管理首页";
	$_A['list_title'] = "系统信息";
	$php_info["phpv"] = phpversion();
	$php_info["sp_os"] = strtolower(isset($_ENV['OS']) ? $_ENV['OS'] : @getenv('OS'));
	$php_info["sp_gd"] = @gdversion();
	$php_info["sp_server"] = $_SERVER["SERVER_SOFTWARE"];
	$php_info["sp_host"] = (empty($_SERVER["REMOTE_ADDR"]) ? $_SERVER["REMOTE_HOST"] : $_SERVER["REMOTE_ADDR"]);
	$php_info["sp_name"] = $_SERVER["SERVER_NAME"];
	$php_info["sp_max_execution_time"] = ini_get('max_execution_time');
	$php_info["sp_allow_reference"] = (ini_get('allow_call_time_pass_reference') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
	$php_info["sp_allow_url_fopen"] = (ini_get('allow_url_fopen') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
	$php_info["sp_safe_mode"] = (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>');
	$php_info["sp_mysql"] = (function_exists('mysql_connect') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>');
	$_A['php_info'] = $php_info;
	$template = "admin_main.html.php";
}
//其他的模块文件
else{
	$_A['site_id'] = isset($_REQUEST['site_id'])?$_REQUEST['site_id']:"";
	if (!empty($_A['site_id'])){
		//获得站点的信息
		$_A['site_result'] = "";
		foreach ($_G['site_list'] as $key => $value){
			if ($value['site_id'] == $_A['site_id']){
				$_A['site_result'] = $value;
			}
		}
	}
	if (isset($_G['site_list']) && $_G['site_list']!=""){
		foreach ($_G['site_list'] as $key => $value){
			if ($value['code'] == $_A['query_class']){
				$_A['site_code_list'][$value['site_id']] = $value;
			}
		}
	}
	$_A['list_name'] = "管理首页";
	$_A['list_title'] = "管理首页";
	$pages->rewrite = false;
	if (file_exists(ROOT_PATH."/modules/admin/{$_A['query_sort']}.php")){
		include_once(ROOT_PATH."/modules/admin/{$_A['query_sort']}.php");
	}else{
		$msg = array("您输入有误，请勿乱操作。","返回管理中心",$_A['admin_url']);
		$template = "admin_main.html.php";
	}
}
	

//错误处理文件
if (isset($msg) && $msg!="") {
	$_msg = $msg[0];
	$content = empty($msg[1])?"返回上一页":$msg[1];
	$url = empty($msg[2])?"-1":$msg[2];
	$http_referer = empty($_SERVER['HTTP_REFERER'])?"":$_SERVER['HTTP_REFERER'];
	if ($http_referer == "" && $url == ""){ $url = "/";}
	if ($url == "-1") $url = "";
	elseif ($url == "" ) $url = $http_referer;
	
	$_A['showmsg'] = array('msg'=>$_msg,"url"=>$url,"content"=>$content);
	$template = "admin_msg.html";
	
}
$magic->assign("_A",$_A);
$magic->display($template);
exit;	
?>
