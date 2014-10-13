<?
/******************************
 * $File: site.php
 * $Description: 站点
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("site_all");//检查权限
check_rank("site_".$_A['query_class']);//检查权限
$_A['list_name'] = "栏目管理";
$_A['list_menu'] = "<a href={$_A['admin_url']}&q=site/loop{$_A['site_url']}>栏目管理</a> - <a href={$_A['admin_url']}&q=site/new{$_A['site_url']}>添加栏目</a> - <a href={$_A['admin_url']}&q=site{$_A['site_url']}>空闲栏目</a>";

//管理员管理类型
$_A['admin_type'] = userClass::GetTypeList(array("type"=>1,"where"=>" and type_id!=1"));
foreach($_A['admin_type'] as $key => $value){
	$_A['admin_type_check'][$value['type_id']] = $value['name'];
}
/**
 * 显示所有栏目
**/
if ($_A['query_class'] == "list" ){
	$_A['list_title'] = "栏目列表"; 
	$_A['list_menu'] = '';
}
/**
 * 显示可编辑所有栏目
**/
elseif ($_A['query_class'] == "loop" ){
}
/**
 * 添加和编辑栏目
**/
elseif ($_A['query_class'] == "new" ||$_A['query_class'] == "edit"){
	if (isset($_POST['name'])){
		$var = array("name","rank","nid","pid","code","order","url","aurl","isurl","status","style","list_name","content_name","sitedir","visit_type","index_tpl","list_tpl","content_tpl","contentadmin","title","keywords","description");
		$data = post_var($var);
		$data['content'] = $data['contentadmin'];
		unset($data['contentadmin']);
		if ($_A['query_class'] == "new"){
			$data['user_id'] = $_G['user_id'];
			$result = siteClass::AddSite($data);
			if ($result===true){
				$msg = array("栏目添加成功","",$_A['query_url'].$_A['site_url']);
			}else{
				$msg = array($result);
			}
		}else{
			$data['site_id'] = $_POST['site_id'];
			$result = siteClass::UpdateSite($data);
			if ($result===true){
				$_tpl = array("index_tpl","list_tpl","content_tpl");
				$tpl = post_var($_tpl);
				$tpl['site_id'] = $data['site_id'];
				if (isset($_POST['update_all'])){ 
					$tpl['type'] = "all";
					siteClass::UpdateTpl($tpl);
				}
				if (isset($_POST['update_brother'])){
					$tpl['type'] = "brother";
					siteClass::UpdateTpl($tpl);
				}
				$msg = array("栏目修改成功","",$_A['query_url'].$_A['site_url']);
			}else{
				$msg = array($result);
			}
		}
		systemClass::createParameter_site();
	}else{
		$_A['module_list'] = moduleClass::GetList(array("type"=>"install"));//模块列表
		if (isset($_GET['pid'])){
			$_A['site_presult'] = siteClass::GetOne(array("site_id"=>$_GET['pid']));
		}
		if (isset($_GET['site_id'])){
			$_A['site_result'] = siteClass::GetOne(array("site_id"=>$_GET['site_id']));
		}
	}
}
/**
 * 修改普通的栏目
**/
elseif ($_A['query_class'] == "update"){
	if (isset($_POST['name'])){
		$var = array("name","nid","site_id","status","litpic","clearlitpic","content","title","keywords","description");
		$data = post_var($var);
		$result = siteClass::UpdateSite($data);
		if ($result===true){
			systemClass::createParameter_site();
			$msg = array("修改成功");
		}else{
			$msg = array($result);
		}
	}else{
		if (isset($_GET['pid'])){
				$_A['site_presult'] = siteClass::GetOne(array("site_id"=>$_GET['pid']));
			}
		if (isset($_GET['site_id'])){
			$_A['site_result'] = siteClass::GetOne(array("site_id"=>$_GET['site_id']));
		}
	}
}
/**
 * 删除栏目
**/
elseif ($_A['query_class'] == "del"){
	$result =  siteClass::Delete(array("site_id"=>$_REQUEST['site_id']));
	if ($result!==true){
		$msg = array($result);
	}else{
		systemClass::createParameter_site();
		$msg = array("删除成功");
	}
}
/**
 * 预览
**/
elseif ($_A['query_class'] == "view"){
	echo "<script>location.href='?".$_REQUEST['site_id']."'</script>";
	exit;
}
/**
 * 修改栏目排序
**/
elseif ($_A['query_class'] == "order"){
	//check_rank("site_order");//检查权限
	if (isset($_POST['site_id']) && $_POST['site_id']!=''){
		foreach ($_POST['site_id'] as $key => $value){
			if (isset($_POST['rank']) && isset($_POST['rank'][$key]) && $_POST['rank'][$key]!=''){
				$rank = join(",",$_POST['rank'][$key]);
			}else{
				$rank = "";
			}
			$sql = "update {site} set `order`='".$_POST['order'][$key]."',rank='$rank' where `site_id` = '$value'";
			$result = $mysql->db_query($sql);
		}
	}
	systemClass::createParameter_site();
	$msg = array("修改成功");
}
/**
 * 栏目的模块
**/
elseif ($_A['query_class'] == "module"){
	if (isset($_REQUEST['code']) && $_REQUEST['code']!=""){
		$data['code'] = $_REQUEST['code'];
		$result = moduleClass::GetOne($data);
		if ($result !=false){
		$_result = $result['index_tpl'].",".$result['list_tpl'].",".$result['content_tpl'];
		echo $_result;
		}
	}else{
		echo 2;
	}
	die();
}

/**
 * 移动栏目
**/
elseif ($_A['query_class'] == "move"){
	if (isset($_POST['pid'])){
		$result = siteClass::MoveSite(array("site_id"=>$_POST['site_id'],"pid"=>$_POST['pid']));
		systemClass::createParameter_site();
		$msg = array("栏目移动成功");
	}else{
		$result = siteClass::GetOne(array("site_id"=>$_REQUEST['site_id']));
		$_A['site_result'] = $result;
		$move_list = siteClass::GetList(array("site_id"=>$_REQUEST['site_id']));
		$_A['site_list'] = $move_list;
	}
}
/**
 * 栏目回收站
**/
elseif ($_A['query_class'] == "recycle"){
	$result = $module->get_site_recycle();
	$magic->assign("result",$result);
}
/**
 * 如果是其他模块则读取其他模块的配置文件
**/
else{
	if (file_exists("modules/{$_A['query_class']}/{$_A['query_class']}.php")){
		include_once(ROOT_PATH."modules/admin/module.php");
	}
	
	exit;
}
$template = "admin_site.html.php";
?>