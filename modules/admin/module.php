<?
/******************************
 * $File: module.php
 * $Description: 模块类处理文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问


require_once(ROOT_PATH.'core/module.class.php');//模块设置
$_A['list_name'] = "模块管理";

$data['type'] = "install";
$_A['module_install_list'] = moduleClass::GetList($data);
unset($data);

/**
 * 默认为模块的列表
**/
if ( $_A['query_class'] == "list"){
	$_A['list_title'] = "模块列表";
	$_A['module_list'] = moduleClass::GetList();
}


/**
 * 如果模块为空的话则显示出文件夹里所有的模块
**/
elseif ( $_A['query_class'] == "channel"){
	$_A['list_menu'] = "<a href='{$_A['query_url']}'>全部模块</a> - <a href='{$_A['query_url']}/install'>已安装模块</a> - <a href='{$_A['query_url']}/unstall'>未安装模块</a>";
	
	if($_A['query_type'] == "list"){
		$_A['list_title'] = "全部模块";
		$_A['module_list'] = moduleClass::GetList();
	}
	
	elseif($_A['query_type'] == "install"){
		$_A['list_title'] = "已安装模块";
		$data['type'] = "install";
		$_A['module_list'] = moduleClass::GetList($data);
	}
	
	elseif($_A['query_type'] == "unstall"){
		$_A['list_title'] = "未安装模块";
		$data['type'] = "unstall";
		$_A['module_list'] = moduleClass::GetList($data);
	}
	
	elseif($_A['query_type'] == "order"){
		moduleClass::OrderModule($_POST['module_id'],$_POST['order']);
		$msg = array("模块排序修改成功");
	}
	
	elseif($_A['query_type'] == "del"){
		$data['code'] = $_REQUEST['code'];
		$result = moduleClass::DeleteModule($data);
		if ($result===true){
			$msg = array("卸载成功");
		}else{
			$msg = array($result);
		}
	}
	
	elseif($_A['query_type'] == "new" || $_A['query_type'] == "edit"){
		if ($_A['query_type'] == "edit"){
			$_A['list_title'] = "编辑模块";
			$data['code'] = $_REQUEST['code'];
			$_A['module_result'] = moduleClass::GetOne($data);
		}elseif ($_A['query_type'] == "new"){
			$_A['list_title'] = "安装模块";
			$_A['module_result'] = get_module_info($_REQUEST['code']);
		}	
		
		if (isset($_POST['name'])){
			$var = array("name","code","status","order","default_field","description","index_tpl","list_tpl","content_tpl","search_tpl","article_status","onlyone","visit_type","title_name","fields","issent","version","author","type");
			$data = post_var($var);
			
			if ($_A['query_type'] == "edit"){
				$result = moduleClass::UpdateModule($data);;
				if ($result!=true){
					$msg = array($result);
				}else{
					$msg = array("编辑成功");
				}
			}elseif ($_A['query_type'] == "new"){
				
				$data['code'] = $_REQUEST['code'];
				$result = moduleClass::AddModule($data);
				if ($result === true){
					$msg = array("模块安装成功","",$_A['query_url']);
				}else{
					$msg = array($result);
				}
				
				userClass::add_log($_log,$result);//记录操作
			}
		}
		
	}
	elseif($_A['query_type'] == "purview"){
		$_A['list_title'] = "权限列表";
		if($_GET['id']>0){
			$sql = "select code,purview from `{module}` where module_id={$_GET['id']}";
			$re_1 = $mysql->db_fetch_array($sql);
			if($re_1==false){
				$msg =array("未找到相应模块!");
			}else{
				$re = unserialize($re_1['purview']);
				$purview = $re[$re_1['code']];
				$magic->assign("purviewlist",current($purview));
				$magic->assign("code",$re_1['code']);
				$magic->assign("module_id",$_GET['id']);
			}
		}else{
			$msg = array("未找到相关模块");
		}
	}
	elseif($_A['query_type']=="newpurview"){
		$_A['list_title'] = "添加权限项";
		if($_POST['purviewcode']!='' && $_POST['module_id']!=''){
			$sql = "select code,purview from `{module}` where module_id={$_POST['module_id']}";
			$re_1 = $mysql->db_fetch_array($sql);
			if($re_1==""){
				$msg = array("未找到相关模块");
			}else{
				$re = unserialize($re_1['purview']);
				$re_2 = $re[$re_1['code']];
				$key = key($re_2);
				$re_2[$key][$_POST['purviewcode']] = $_POST['purviewname'];
				$re[$re_1['code']] = $re_2;
				$re = serialize($re);
				$sql = "update `{module}` set purview='{$re}' where module_id={$_POST['module_id']}";
				$re = $mysql->db_query($sql);
				if($re==false){
					$msg = array("操作失败","",$_A['query_url']."/purview&code={$_GET['code']}&id={$_POST['module_id']}");
				}else{
					$msg = array("操作成功","",$_A['query_url']."/purview&code={$_GET['code']}&id={$_POST['module_id']}");
				}
			}
		}elseif($_POST['module_id']!=''){
			$magic->assign("module_id",$_POST['module_id']);
		}else{
			$msg = array("请不要乱操作");
		}
	}elseif($_A['query_type']=="delpurview"){
		if(isset($_GET['module_id']) && isset($_GET['purviewcode'])){
			$sql = "select code,purview from `{module}` where module_id={$_GET['module_id']}";
			$re_1 = $mysql->db_fetch_array($sql);
			if($re_1==""){
				$msg = array("未找到相关模块");
			}elseif($_G['user_id']!=1){
				$msg = array("您不是超级管理员，不能删除");
			}else{
				$re = unserialize($re_1['purview']);
				$re_2 = $re[$re_1['code']];
				$key = key($re_2);
				unset($re_2[$key][$_GET['purviewcode']]);
				$re[$re_1['code']] = $re_2;
				$re = serialize($re);
				$sql = "update `{module}` set purview='{$re}' where module_id={$_GET['module_id']}";
				$re = $mysql->db_query($sql);
				if($re==false){
					$msg = array("操作失败","",$_A['query_url']."/purview&code={$_GET['code']}&id={$_GET['module_id']}");
				}else{
					$msg = array("操作成功","",$_A['query_url']."/purview&code={$_GET['code']}&id={$_GET['module_id']}");
				}
			}
		}else{
			$msg = array("请不要乱操作");
		}
	}
}



/**
 * 模块字段管理
**/
elseif ( $_A['query_class'] == "fields"){
	$code = $_REQUEST['code'];
	$_A['list_menu'] = "<a href='{$_A['admin_url']}&q=module/fields&code={$code}'>字段管理</a>- <a href='{$_A['admin_url']}&q=module/fields/new&code={$code}'>添加字段</a>";
	
	if($_A['query_type'] == "list"){
		$_A['list_title'] = "全部字段";
		$data['code'] = $code;
		$_A['fields_list'] = moduleClass::GetFieldsList($data);
	}
	
	elseif($_A['query_type'] == "new" || $_A['query_type'] == "edit"){
		$_A['list_title'] = "字段管理";
		if (isset($_POST['name'])){
			$var = array("name","nid","order","code","type","size","input","description","default","select","order");
			$data = post_var($var);
			
			if ($_A['query_type'] == "new"){
				$result = moduleClass::AddFields($data);
			}else{	
				$data['fields_id'] = $_POST['fields_id'];
				$result = moduleClass::UpdateFields($data);
			}
			if ($result===true){
				$msg =array("操作成功！");
			}else{
				$msg = array($result);
				}
			
		}else{
			$_A['fields_type'] = fields_type();
			$_A['fields_input'] = fields_input();
			if ($_A['query_type'] == "edit"){
				$data['code'] = $_REQUEST['code'];
				$data['fields_id'] = $_REQUEST['fields_id'];
				$_A['fields_result'] = moduleClass::GetFieldsOne($data);
			}
		}
	}
	
	elseif($_A['query_type'] == "order"){
		$data['fields_id'] = $_POST['fields_id'];
		$data['order'] = $_POST['order'];
		moduleClass::OrderFields($data);
		$msg = array("字段排序修改成功");
	}
	
	elseif($_A['query_type'] == "del"){
		$data['fields_id'] = $_REQUEST['fields_id'];
		$data['code'] = $_REQUEST['code'];
		$result = moduleClass::DeleteFields($data);
		$msg = array("字段删除成功");
	}
}

/**
 * 如果是其他模块则读取其他模块的配置文件
**/
else{
	$code = $_A['query_class'] ;
	
	if (!file_exists(ROOT_PATH."/modules/$code/$code.php")){
		$msg = array("模块不存在，请检查是否操作有误");
	}else{
		
		//栏目ID和栏目的名称
		if (!isset($_REQUEST['site_id']) || $_REQUEST['site_id']==0){
			$site_id = "";
			$site_url = "";
		}else{
			require_once(ROOT_PATH.'core/site.class.php');//模块设置
			$site_id = $_REQUEST['site_id'];
			//$site = $module->get_site($site_id);
			$site_url = "&site_id=$site_id";
			$_A['site_result'] = siteClass::GetOne(array("site_id"=>$site_id));
			//$magic->assign("site_name"," -> ".$site['name']);
		}
		
		
		
		//获取站点的名称
		if ($site_id!=""){
			$_A['list_title'] = $_A['site_result']['name'];
		}
		
		
		if ($code!="manager" && $code!="user" && isset($_A['site_result']['rank']) &&!in_array($_G['user_result']['type_id'],explode(",",$_A['site_result']['rank'])) && $_G['user_result']['type_id']!=1 && !in_array("other_all",explode(",",$_SESSION['purview']))){
			$msg =array( "你没有此权限管理此站点");
		}
		
	
		/**
		 * 关闭此模块
		**/
		elseif ($_A['query_type'] == "close"){
			$result = $module -> close_module($s);
			$msg = array("您已经成功关闭了此模块，关闭后在内容添加的时候将不会看到此模块。");
			userClass::add_log($_log,$result);//记录操作
		}
	
		/**
		 * 打开此模块
		**/
		elseif ($_A['query_type'] == "open"){
			$result = $module -> open_module($s);
			$msg = array("您已经成功开启了此模块，开启后在内容添加的时候将会看到此模块。");
			userClass::add_log($_log,$result);//记录操作
		}
		/**
		 * 修改排序
		**/
		elseif ($_A['query_type'] == "action"){
			
			$type = $_POST['type'];
			if ($type==0){
				$data['code'] = $_A['query_class'];
				$data['id'] = $_POST['id'];
				$data['order'] = $_POST['order'];
				$data['type'] = "order";
				$result = moduleClass::ActionModule($data);
				if ($result == false){
					$msg = array("输入有误，请跟管理员联系");
				}else{
					$msg = array("修改排序成功");
				}
			}elseif ($type==1 || $type==2){
				if (isset($_POST['aid'])){
					$status = array("1"=>1,"2"=>0);
					$data['code'] = $_A['query_class'];
					$data['status'] = $status[$type];
					$data['id'] = $_POST['aid'];
					$data['type'] = "status";
					$result = moduleClass::ActionModule($data);
					$msg = array("状态修改成功");
				}else{
					$msg = array("请选择参数");
				}
			}elseif ($type==3 || $type==4 || $type==5 || $type==7  || $type==8  ){
				if (isset($_POST['aid'])){
					$flag = array("3"=>'t',"4"=>'h',"5"=>'f',"7"=>'q');
					$data['code'] = $_A['query_class'];
					if ($type=="8"){
						$data['change'] = "t,q";
					}else{
						$data['change'] = $flag[$type];
					}
					$data['flag'] = $_POST['flag'];
					$data['id'] = $_POST['aid'];
					$data['type'] = "flag";
					$result = moduleClass::ActionModule($data);
					$msg = array("状态修改成功");
					
				}else{
					$msg = array("请选择参数");
				}
			}elseif ($type==6){
				if (isset($_POST['aid'])){
					$data['code'] = $_A['query_class'];
					$data['id'] = $_POST['aid'];
					$data['type'] = "del";
					$result = moduleClass::ActionModule($data);
					$msg = array("删除成功");
				}else{
					$msg = array("请选择参数");
				}
				
			}
			userClass::add_log($_log,$result);//记录操作
		}else{
			//当前模块的信息
			$_A['module_result'] = moduleClass::GetOne(array("code"=>$code));
			
			//显示自定义的表单
			$_res = explode(",",$_A['module_result']['default_field']);
			foreach ($_A['article_fields'] as $key => $value){
				if (count($_res)>0 && in_array($key,$_res)){
					$_filed[$key] = true;
				}else{
					$_filed[$key] = false;
				}
			}
			$_A['show_fields'] = $_filed;
			
			if ($_A['module_result'] == "" ){
				$msg = array("此模块尚未安装，请先安装","",$_A['admin_url']."&q=module/channel/new&code=$code");
			}else{
				if (isset($_REQUEST['code'])){
					$code = $_REQUEST['code'];
				}else{
					$code = $_A['query_class'];
				}
				include_once("modules/$code/$code.php");
				$template_tpl = "{$code}.tpl";//如果是其他的，则直接读取模块所在的相应模板
				$magic->assign("template_dir","modules/{$code}/");
				$magic->assign("module_tpl",$template_tpl);
			}
		}
		
	}
}
$template = "admin_module.html.php";
?>