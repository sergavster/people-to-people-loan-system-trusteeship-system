<?
/******************************
 * $File: channel.php
 * $Description: 模块的新模型管理
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/

if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("module_".$_t);//检查权限
$code = empty($_REQUEST['code'])?"":$_REQUEST['code'];

$list_name = "模块管理";
$list_menu = "<a href='{$url}/list'>模块列表</a> - <a href='{$url}/new'>添加模块</a> - <a href='{$admin_url}&q=module/channel/field&code={$code}'>字段管理</a> - <a href='{$admin_url}&q=module/{$code}'>内容管理</a> ";

/**
 * 获取某一个模块的信息
**/
if ($code != ""){
	$module_res = $module->get_module($code);
	$magic->assign("module_res",$module_res);
}

/**
 * 获取所有已添加的模块
**/
if ($t == "list"){
	$result = $module->get_module();
	$magic->assign("result",$result);
}

/**
 * 添加和编辑模块
**/
elseif ($t == "new" || $t == "edit"){
	if ($t == "new" && isset($_REQUEST['code'])){
		$result = get_module_info($_REQUEST['code']);
		$result['code'] =  $_REQUEST['code'];
		$magic->assign("result",$result);
	}
	$magic->assign("article_fields",$article_field);
	if ($t == "edit"){
		$result = $module->get_module($_REQUEST['code']);
		if ($result['type'] == "system"){
			$msg = array("系统模型不能编辑");
		}else{
			$result['default_field'] = explode(",",$result['default_field']);
			$magic->assign("result",$result);
		}
	}
}

/**
 * 添加和修改模块的操作
**/
elseif ($t == "add" || $t == "update"){
	$var = array("name","status","order","default_field","description","index_tpl","list_tpl","content_tpl","search_tpl","article_status","onlyone","visit_type","title_name","fields","issent","version","author","type");
	$index = post_var($var);
	if (is_array($index['default_field'])){
		$index['default_field'] = join(",",$index['default_field']);
	}
	
	if ($index['name'] == ""){
		$msg = array("类型名称不能为空");
	}else if ($_POST['code'] =="" && $t=="add"){
		$msg =array("字段名不能为空");
	}else{
		if ($t == "add"){
			$index['code'] = $_POST['code'];
			$result = $module->add_module($index,"add");
			if ($result!=false) {
				 $module->create_file_info($index);
				$module->create_file($index,"sql","modules/".$query_site."/cms.sql.php");
				$module->create_file($index,"php","modules/".$query_site."/cms.index.php");
				$module->create_file($index,"tpl","modules/".$query_site."/cms.tpl.php");
			}
		}else{	
			$code = $_POST['code'];
			$result = $module->update_module($index,$code);
		}
		if ($result<0){
			if ($result==-1){
				$msg =array("模块中字段名已经存在! ");	
			}else{
				$msg =array("操作错误，请跟管理员联系! ");
			}	
		}else{
			$msg =array("操作成功！");
		}
	}
	$user->add_log($_log,$result);//记录操作
}

/**
 * 字段排序
**/
else if ($t=="order"){
	$module_id = $_REQUEST['module_id'];
	$order = $_REQUEST['order'];
	if ($module_id!=""){
		$result = $module->order_module($module_id,$order);
		if ($result<0){
			$msg =array("排序修改失败，请返回! Error:($result)");
		}else{
			$msg =array("排序修改成功，请返回!");
		}
	}
}

/**
 * 字段管理
**/
elseif ($t == "field"){
	if ($module_res['type']!="cms" && $module_res['fields']!=1){
		$msg = array("只有cms模块或者有自定义字段的才可以添加字段");
	}else{
		$result = $module->get_fields($code);
		$magic->assign("result",$result);
	}
}


/**
 * 添加新字段
**/
elseif ($t == "field_new"){
	$magic->assign("fields_type",fields_type());
	$magic->assign("fields_input",fields_input());
}

/**
 * 编辑字段
**/
elseif ($t == "field_edit"){
	$magic->assign("fields_type",fields_type());
	$magic->assign("fields_input",fields_input());
	$magic->assign("result",$module->view_fields($_REQUEST['nid']));
}

/**
 * 添加和修改字段
**/
elseif ($t == "field_add" || $t == "field_update"){
	//添加或者修改字段
	$var = array("name","nid","order","type","size","input","description","default","select","order");
	$index = post_var($var);
	if ($index['type']=="varchar" || $index['type']=="int"){
		$index['size'] = 255;
	}
	if ($index['name'] == ""){
		$msg =array("类型名称不能为空");
	}else if ($index['nid']=="" && $t=="field_add"){
		$msg =array("字段名不能为空");
	}else{
		if ($t=="field_add"){
			$result = $module->add_fields($code,$index,$module_res['type']);
		}else{	
			$nid = $_POST['nid'];
			$result = $module->update_fields($code,$index,$nid,$module_res['type']);
		}
		if ($result<0){
			if ($result==-2){
				$msg =array("标识名已经存在! ");	
			}else if ($result==-6 || $result==-8){
				$msg =array("操作错误，请跟管理员联系!");
			}else if ($result==-3){
				$msg =array("此标示名为默认标示名，请填写其他的!");	
			}else{
				$msg =array("添加错误，请跟管理员联系!");
			}	
		}else{
			$msg =array("操作成功！");
		}
	}
}

/**
 * 删除字段
**/
else if($t=="field_del"){
	$code = $_REQUEST['code'];
	$nid = $_REQUEST['nid'];
	if ($nid==""){
		$msg =array("你输入有误! Error:(-1)");
	}else{
		$result = $module->del_fields($code,$nid,$module_res['type']);
		if ($result<0){
			$msg =array("操作有误，请返回! Error:($result)");
		}else{
			$msg =array("操作成功，请返回!");
		}
	}
}

/**
 * 字段排序
**/
else if ($t=="field_order"){
	$fields_id = $_REQUEST['fields_id'];
	$order = $_REQUEST['order'];
	if ($fields_id!=""){
		$result = $module->order_fields($fields_id,$order);
		if ($result<0){
			$msg =array("排序修改失败，请返回! Error:($result)");
		}else{
			$msg =array("排序修改成功，请返回!");
		}
	}
}

if ($msg!=""){
	$template_tpl = show_msg($msg,$msg_tpl);//如果是信息的则直接读取系统的信息模板
}else{
	$template_tpl = "admin_channel.html.php";//如果是其他的，则直接读取模块所在的相应模板
	$magic->assign("template_dir","");
}


$magic->assign("module_tpl",$template_tpl);

?>