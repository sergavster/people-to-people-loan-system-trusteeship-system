<?
//被禁止的IP列表
/*
$ip_list = array(
  '42.120.13.157', //yesmyloan
  '58.215.172.36', //yesmyloan
  '211.101.18.40', //yesmyloan
  '124.226.69.0', //other
  '110.75.189.78', //other
  '42.121.96.243', //yesmyloan
  '42.121.88.194', //daishuba
  '118.123.13.181', //p2peye
 '219.234.2.99', //zeyansoft
);
if(!empty($_SERVER["HTTP_CLIENT_IP"])) {
	$ip_address = $_SERVER["HTTP_CLIENT_IP"];
}else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
	$ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
}else if(!empty($_SERVER["REMOTE_ADDR"])){
	$ip_address = $_SERVER["REMOTE_ADDR"];
}else{
	$ip_address = '';
}
$ip=$ip_address;  // 获取用户IP 
if(in_array($ip,$ip_list))
{
	echo $ip;
	die(" IP Forbidden!");
}
*/
session_cache_limiter('private,must-revalidate');
$_G = array();
//基本配置文件
include_once ("core/config.inc.php");
if(file_exists(ROOT_PATH.'data/parameter/parameter_biaotype.php')){
	require_once ROOT_PATH.'data/parameter/parameter_biaotype.php';
}
//系统基本信息
if(!$rdGlobal['cache_para_forbid'] && file_exists(ROOT_PATH.'data/parameter/parameter_system.php')){
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

$_G['nowtime'] = time();//现在的时间
$_G['weburl'] = "http://".$_SERVER['HTTP_HOST'];//当前的域名

//分割url参数
$query_string = explode("&",$_SERVER['QUERY_STRING']);
$_G['query_string'] = $query_string;

if (isset($_REQUEST['query_site']) && $_REQUEST['query_site']!=""){
	$_G['query_site'] = $_REQUEST['query_site'];
}elseif (isset($query_string[0])){
	$_G['query_site'] = $query_string[0];
}

//获取网站管理地址
if (isset($_G['system']['con_houtai']) && $_G['system']['con_houtai']!=""){
	$admin_name = $_G['system']['con_houtai'];
}else{
	$admin_name = "admin";
}

//获取第三方登录状态
if(isset($_SESSION['QC_userData']['state'])&&isset($_SESSION['QC_userData']['access_token'])&&isset($_SESSION['QC_userData']['openid'])){
	//判断如果是采用QQ共享登录
	$_G['open_connet']['type']='qq';
	include("api/qq/qqConnectAPI.php");
	$qc = new QC();
	$arr = $qc->get_user_info(); 
	//获取qq个人信息 
	$_G['open_connet']['gender']=iconv("UTF-8","gbk",$arr["gender"]); //性别
	$_G['open_connet']['nickname']=iconv("UTF-8","gbk",$arr["nickname"]);  //昵称
	$_G['open_connet']['openid']=$qc->get_openid(); //唯一的登录id号
}else{
	//$_G['open_connet']['type']='';
}

//判断采用何种方式登录
$_user_id = array("");
$_G['user_id']=''; //初始化当前登录的用户id
$_G['is_cookie'] = isset($_G['system']['con_cookie'])?(int)$_G['system']['con_cookie']:0;

if ($_G['query_site'] == $admin_name ){
	//后台
	if (isset($_SESSION['manager_loginendtime']) && $_SESSION['manager_loginendtime']>time()){
			$_user_id = explode(",",authcode(isset($_SESSION['manager_rdun'])?$_SESSION['manager_rdun']:"","DECODE"));
			$check_uid=$_SESSION['manager_uid'];
	}
 
	
}else{
	//前台
	if ($_G['is_cookie'] ==1){
		$_user_id = explode(",",authcode(isset($_COOKIE['rdun'])?$_COOKIE['rdun']:"","DECODE"));
		$check_uid=isset($_COOKIE['login_uid'])?$_COOKIE['login_uid']:'';
	}else{
		if (isset($_SESSION['login_endtime']) && $_SESSION['login_endtime']>time()){
			$_user_id = explode(",",authcode(isset($_SESSION['rdun'])?$_SESSION['rdun']:"","DECODE"));
			$check_uid=$_COOKIE['login_uid'];
		}
	}

}

//登录cookies+session验证 add by weego for 异常登录 20120610 begin
if($check_uid == $_user_id[0]){
	$_G['user_id'] = $_user_id[0];
}
//登录cookies+session验证 add by weego for 异常登录 20120610 end
if ($_G['user_id']!=""){
	$_G['user_result'] = $user->GetOne(array("user_id"=>$_G['user_id']));
	$user->GetUserCache(array("user_id"=>$_G['user_id']));
	if($_G['user_result']['islock']==1){
		echo "<script type='text/javascript'>alert('你的账号已被锁定，请联系网站客服了解详情');</script>";
		include_once ROOT_PATH.'modules/member/logout.php';
	}
}
//模块，分页，每页显示条数
$_G['page'] = isset($_REQUEST['page'])?$_REQUEST['page']:1;//分页
$_G['epage'] = isset($_REQUEST['epage'])?$_REQUEST['epage']:10;//分页的每一页

$_G['nowurl'] = "http//".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//获得网站的缓存
$_G['cache'] = systemClass::GetCacheOne();

//获得在线的用户
//$_G['online'] = systemClass::Online(array("user_id"=>$_G['user_id']));

//模板选择
$con_template = "themes/";
$con_template .= empty($system['con_template'])?"default":$system['con_template'];
$template_error = false;
if (!file_exists($con_template)){
	$template_error = true;
	$con_template = "themes/default";
	$system['con_template'] = "default";
	$magic->template_error = $template_error;
}
$magic->template_dir = $con_template;
$magic->force_compile = false;
$_G['tpldir'] = "/".$con_template;
$magic->assign("tpldir",$_G['tpldir']);
$magic->assign("tempdir",$_G['tpldir']);//图片地址


//信息提示
if($_G['query_site']=='message'){
	$temlate_dir = "themes/".$_G['system']['con_template']."_member";
	$magic->template_dir = $temlate_dir;
	$magic->assign("tpldir",$temlate_dir);
	if(isset($_COOKIE['message'])){
		$str = unserialize(base64_decode($_COOKIE['message']));
	}else{
		$str = array('msg'=>'请不要乱操作','content'=>'返回首页','url'=>'/index.php');
	}
	$_U['showmsg'] = $str;
	$magic->assign("_U",$_U);
	$magic->assign("_G",$_G);
	$magic->display('user_msg.html.php');
	exit();
}


//联动模块
if(!$rdGlobal['cache_para_forbid'] && file_exists(ROOT_PATH.'data/parameter/parameter_linkage.php')){
	include_once ROOT_PATH.'data/parameter/parameter_linkage.php';
}else{
	include_once ("modules/linkage/linkage.class.php");
	$linkageclass = new linkageClass();
	if ($linkageclass->IsInstall()){
		$result = $linkageclass->GetList(array("limit"=>"all"));
		foreach ($result as $key => $value){
			$_G['linkage'][$value['type_nid']][$value['value']] = $value['name'];
			$_G['linkage'][$value['id']] = $value['name'];
			if ($value['type_nid']!=""){
				$_G['_linkage'][$value['type_nid']][$value['id']] = array("name"=>$value['name'],"id"=>$value['id'],"value"=>$value['value']);
			}
		}
	}
}
//地区列表
if(!$rdGlobal['cache_para_forbid'] && file_exists(ROOT_PATH.'data/parameter/parameter_area.php')){
	include_once ROOT_PATH.'data/parameter/parameter_area.php';
}elseif(file_exists(ROOT_PATH."modules/area/area.class.php")){
	include_once (ROOT_PATH."modules/area/area.class.php");
	$areaclass = new areaClass();
	//如果已经安装地区模块，则读取地区的信息
	if ($areaclass->IsInstall()){
		$result = $areaclass->GetList(array("limit"=>"all"));
		$_G['arealist'] = $result;
	}
	//如果网站是采用二级地区分区的，则进行相关的配置
	if (isset($_G['system']['con_area_part']) && $_G['system']['con_area_part']==1){
		$city_area = explode(".",$_SERVER['SERVER_NAME']);
		$area_city_nid = $city_area[0] ; 
		
		//获得网站的域名
		if (count($city_area)==2){
			$domain = $_SERVER['SERVER_NAME'];
		}else{
			$domain = $city_area[1].".".$city_area[2];
		}
		$_G['domain'] = $domain;//网站的域名
		$_G['webname'] = "http://".$area_city_nid.".".$domain;//当前的域名
			
		//显示城市的列表
		if ($area_city_nid =="city"){
			$magic->assign("_G",$_G);
			$tpl = "city.html";
			$magic->display($tpl);
			exit;
		}
		
		//基本的地区跳转
		elseif ($area_city_nid =="www" || count($city_area)==2){
			if (isset($_REQUEST['set_city_nid'])){
				setcookie("set_city",$_REQUEST['set_city_nid'],time()+3600*24*30);
				exit;
			}
			if (isset($_COOKIE['set_city'])){
				$url = "http://".$_COOKIE['set_city'].".".$_G['domain'];//有cookie地址
				echo "<script>location.href='$url';</script>";
				exit;
			}
			echo "<script>location.href='http://city.{$_G['domain']}';</script>";
			exit;
			
			
		}
		
		else{
		
			//循环寻找相关的城市信息
			foreach ($_G['arealist'] as $key => $value){
				if ($value['nid']==$area_city_nid){
					//城市的基本信息
					$_G['city_result'] = $_G['arealist'][$key];
				}
			}	
			//循环寻找相关的地区信息
			foreach ($_G['arealist'] as $key => $value){
				//省份的基本信息
				if ($value['id']==$_G['city_result']['pid']){
					$_G['province_result'] = $_G['arealist'][$key];
				}
				//所在城市地区列表
				if ($value['pid']==$_G['city_result']['id']){
					$_G['area_list'][] = $value;
				}
				//地区的基本信息
				if (isset($_REQUEST['area']) && $_REQUEST['area'] == $value['nid']){
					$_G['area_result'] = $value;
				}
			}	
			
			//判断是不是城市的信息，如果不是，则返回城市页继续选择
			if ($_G['province_result']['pid']!=0 || !isset($_G['city_result'])){
				unset($_COOKIE['set_city']);
				echo "<script>location.href='http://city.{$domain}';</script>";
				exit;
			}
			
		
		}
		
	}
}
//站点列表
include_once (ROOT_PATH."core/site.class.php");
if (!$rdGlobal['cache_para_forbid'] && file_exists(ROOT_PATH.'data/parameter/parameter_site.php')){
	include_once ROOT_PATH.'data/parameter/parameter_site.php';
}else{
	$_G['site_list'] = siteClass::GetList(array("limit"=>"all"));
}
if ($_G['site_list']!=false){
	foreach ($_G['site_list'] as $key => $value){
		$_G['site_list'][$key]['pid'] = strval($value['pid']);
		if ($value['rank']!=""){
			$_pur = explode(",",$value['rank']);
			if (isset($_G['user_result']['type_id']) && in_array($_G['user_result']['type_id'],$_pur)){
				$_G['site_list_pur'][$key] = $value;
			}
		}
	}
}

//上传图片的配置
$_G['upimg']['cut_status'] = 0;
$_G['upimg']['user_id'] = empty($_G['user_id'])?0:$_G['user_id'];
$_G['upimg']['cut_type'] = 2;
$_G['upimg']['cut_width'] = isset($_G['system']['con_fujian_imgwidth'])?$_G['system']['con_fujian_imgwidth']:"";
$_G['upimg']['cut_height'] = isset($_G['system']['con_fujian_imgheight'])?$_G['system']['con_fujian_imgheight']:"";

$_G['upimg']['file_size'] = 1000;
$_G['upimg']['mask_status'] = isset($_G['system']['con_watermark_pic'])?$_G['system']['con_watermark_pic']:"";
$_G['upimg']['mask_position'] = isset($_G['system']['con_watermark_position'])?$_G['system']['con_watermark_position']:"";
if (isset($_G['system']['con_watermark_type']) && $_G['system']['con_watermark_type']==1){
	$_G['upimg']['mask_word'] =isset($_G['system']['con_watermark_word'])?$_G['system']['con_watermark_word']:"";
	$_G['upimg']['mask_font'] = "3";
	$_G['upimg']['mask_size'] = $_G['system']['con_watermark_font'];
	$_G['upimg']['mask_color'] = isset($_G['system']['con_watermark_color'])?$_G['system']['con_watermark_color']:"";
}else{
	$_G['upimg']['mask_img'] = isset($_G['system']['con_watermark_file'])?$_G['system']['con_watermark_file']:"";
}

if ($_G['query_site'] == "user" ){
	$_G['site_result']['nid'] = "user";
}

$magic->assign("_G",$_G);
//跳转登陆后台
if ($_G['query_site'] == $admin_name ){
	include_once ("modules/admin/index.php");
	exit;
}
/**
* 关闭网站
**/
if ($_G['system']['con_webopen']==1){
	die($_G['system']['con_closemsg']);
}
//add by weego for 融盾安全检测 20120606
$hackManArray=array('epage','keywords','code','type','area','account','branch','lilv','biaoType');
foreach($hackManArray as $hackKey){
	if(isset($_REQUEST[$hackKey])){
	$_REQUEST[$hackKey] = urldecode($_REQUEST[$hackKey]);
	$_REQUEST[$hackKey]=htmlgl($_REQUEST[$hackKey],'1');
	$_REQUEST[$hackKey]=safegl($_REQUEST[$hackKey]);
	}
}
//add by weego for 融盾安全检测 20120606

//用户中心
if ($_G['query_site'] == "user"){
	//include_once ("modules/member/index_{$_G['system']['con_template']}.php");
	include_once ("modules/member/index.php");
}
elseif ($_G['query_site'] == "u" ){//用户信息
	$_G['U_uid'] = $user_id = $_G['query_string'][1];
	if($_G['U_uid'] == ""){
		$_G['msg'] = array("您的输入有误,找不到相应的页面","<a href='/'>返回首页</a>");
		$magic->assign("_G",$_G);
		$magic->display("error.html");
		exit();
	}
	$magic->assign("GU_uid",$_G['U_uid']);
	$magic->display("u.html");
}
//评论
elseif ($_G['query_site'] == "comment" ){
	include_once ("modules/comment/comment.inc.php");
}

else{
		//获得站点和文章的信息
		$quer = explode("/",$query_string[0]);	
		if (isset($_REQUEST['query_site']) && $_REQUEST['query_site']!=""){
			$site_nid =$_REQUEST['query_site'];
		}else{
			$site_nid = isset($quer[0])?$quer[0]:"";
		}
		$article_id = isset($quer[1])?$quer[1]:"";
		$content_page = isset($quer[2])?$quer[2]:"";//内容的分页
		
		
		$_G['article_id'] = $article_id;
		//获得站点的信息
		$_G['site_result'] = "";
		if (isset($_G['site_list']) && $_G['site_list']!=""){
			foreach ($_G['site_list'] as $key => $value){
				if ($value['nid'] == $site_nid){
					$_G['site_result'] = $value;
				}
			}
		}
		
		//模块信息
		$_G['module_result'] = "";
		if (file_exists(ROOT_PATH."core/module.class.php")){
			include_once (ROOT_PATH."core/module.class.php");
			if (isset($_G['site_result']['code'])){
				$_G['module_result'] = moduleClass::GetOne(array("code"=>$_G['site_result']['code']));
			}
		}
		//判断站点是否存在
		if (!empty($_G['site_result'])){
			if($_G['site_result']['isurl']==1){
				header("location:{$_G['site_result']['url']}");
				exit();
			}
			//获得子站点的信息
			foreach ($_G['site_list'] as $key => $value){
				if ($value['pid'] == $_G['site_result']['site_id']){
					if ($value['status']==1){
						$_G['site_sub_list'][] = $value;//子站点列表
					}
				}
				if ($value['site_id'] == $_G['site_result']['pid']){
					$_G['site_presult'] = $value;//父站点
				}
				if ($value['pid'] == $_G['site_result']['pid']){
					if ($value['status']==1){
						$_G['site_brother_list'][] = $value;//同级站点列表
					}
				}
			}
			
			if (isset($_G['site_presult']) && $_G['site_presult']['pid']!=0){
				foreach ($_G['site_list'] as $key => $value){
					if ($value['site_id'] == $_G['site_presult']['pid']){
						$_G['site_mresult'] = $value;//父站点
					}
				}
			}
			//单条文章
			if ($article_id!="" && is_numeric($article_id)){
				$code = $_G['site_result']['code'];
				$codeclass = $code."Class";
				if (file_exists(ROOT_PATH."modules/{$code}/{$code}.class.php")){
					include_once(ROOT_PATH."modules/{$code}/{$code}.class.php");
					$class = new $codeclass();
					$result = $class->GetOne(array("id"=>$article_id,"click"=>true));
					$_G['article'] = $result;
				}
			
				if (count($_G['article']) <= 0){
					$template = "error.html";
				}else{
					$template = $_G['site_result']['content_tpl'];
				}
			}
			
			//文章列表
			else{
				if ($_G['site_result']['pid']==0){
					$template = $_G['site_result']['index_tpl'];
				}else{
					$template = $_G['site_result']['list_tpl'];
				}
			}
		}else{
			if ($site_nid==""){
			// 默认首页的模板文件
				if($_G['weburl'].$_SERVER['REQUEST_URI']!=$_G['weburl'].'/'){
					header('location:'.$_G['weburl']);
				}
				$template = !isset($_G['system']['con_index_tpl'])?"index.html":$_G['system']['con_index_tpl'];
			}else{
				$msg = array("您的输入有误,找不到相应的页面","<a href='/'>返回首页</a>");
			}
		}

		if(isset($_GET['biao_type'])){
			/*
			$biao_info = $_G['biao_type'][$_GET['biao_type']];
			if($_G['user_id']==''){
				echo "<script type='text/javascript'>window.location.href='/index.action?user&q=going/login';</script>";
				exit();
			}else{
				$_G['biaotype_info']=$biao_info;
			}
			if($_G['user_result']['real_status'] != 1){
				echo "<script type='text/javascript'>alert('请先进行实名认证!');window.location.href='/index.php?user&q=code/user/realname';</script>";
					exit();
			}
			
			}elseif($_G['user_id']==1){
				$_G['biaotype_info']=$biao_info;
			}elseif($biao_info['available']!=1){
				echo "<script type='text/javascript'>alert('该标种已被禁用或不存在!');window.location.href='/borrow/index.html#002';</script>";
				exit();
			}else{
				if($_G['user_result']['real_status'] != 1 && $_G['user_result']['real_status'] != 2){
					echo "<script type='text/javascript'>alert('请先进行实名认证!');window.location.href='/index.php?user&q=code/user/realname';</script>";
					exit();
				}
				if($biao_info['is_vip_borrow']==1 && ($_G['user_result']['vip_status']!=1 && $_G['user_result']['vip_status']!=2)){
					echo "<script type='text/javascript'>alert('该标种只有vip用户才可以申请发布!');window.location.href='/vip/index.html';</script>";
					exit();
				}else{
					require_once ROOT_PATH.'modules/userinfo/userinfo.class.php';
					$userinfo = new userinfoClass();
					$userinfo_infoconf = $userinfo->userinfo_no_data(array("user_id"=>$_G['user_id']));
					if($userinfo_infoconf==false){
						echo "<script type='text/javascript'>alert('请先完善您的个人资料!');location.href='/index.php?user&q=code/userinfo';</script>";
						exit();
					}
					$user_attestation = $user->GetAttestation(array('user_id'=>$_G['user_id']));
					if($user_attestation!=''){
						echo "<script type='text/javascript'>alert('对不起！您的个人证明材料不全，请先上传');window.location.href='/index.action?user&q=code/attestation/one';</script>";
						exit();
					}
					$_G['biaotype_info']=$biao_info;
				}
			}
			*/
		}
		if (isset($msg) && $msg!=""){
			$_G['msg'] = $msg;
			$template = "error.html";
		}
		//pdf协议书下载
		$magic->assign("_G",$_G);
		if(preg_match("/^protocol(.*)\.html$/",$template,$arr) && isset($_REQUEST['type']) && $_REQUEST['type']=="pdf"){
			define(PROTOCOL,$arr[1]);
			include_once ROOT_PATH.'plugins/fpdf17/protocol.php';
		}
		//借款标中用户的图片资料
		if($_G['query_site']=="attestation_list"){
			$url = parse_url($_SERVER['HTTP_REFERER']);
			if('http://'.$url['host']!=$_G['weburl']){
				//exit();
			}
			require_once 'modules/borrow/borrow.class.php';
			$ob = new borrowClass();
			$re = $ob->GetBorrowAttestationList(array('attestationid'=>$_REQUEST['attestationid']));
			$_G['borrow_attestation_list']=$re;
			$magic->assign("_G",$_G);
			$template = "attestation_list.html";
		}
		if (isset($_G['site_result']['code']) && $_G['site_result']['code']!=""){
			$magic->display(format_tpl($template,array("code"=>$_G['site_result']['code'])));
		}else{
			$magic->display($template);
		}
}
$mysql->db_close();
exit;
?>