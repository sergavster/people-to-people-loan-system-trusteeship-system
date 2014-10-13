<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("linkage_list");//检查权限


include_once("linkage.class.php");

$_A['list_purview'] =   array("linkage"=>array("联动模块"=>array("linkage_list"=>"联动管理")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>联动管理</a>  -  <a href='{$_A['query_url']}/type_new{$_A['site_url']}'>添加联动类型</a>";
$_A['list_table'] = "";


	
/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$data['page'] = $_A['page'];
	$data['epage'] = 20;

	$result = linkageClass::GetTypeList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['linkage_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}


/**
 * 添加
**/
elseif ($_A['query_type'] == "new"){
	if (isset($_POST['name'])){
		$var = array("name","pid","value","type_id","order");
		$index = post_var($var);
		if($index['value']==""){
			$index['value'] = $index['name'];
		}
		$result = $mysql->db_add("linkage",$index,"notime");
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			systemClass::createParameter_linkage();
			$msg = array("操作成功","",$_U["url"]);
		}
		$user->add_log($_log,$result);//记录操作
	
	}else{
		$data['limit'] = "all";
		$data['id'] = $_REQUEST['id'];
		$_A['linkage_type_result'] =linkageClass::GetTypeOne($data);
		if (is_array($_A['linkage_type_result'])){
			$data['type_id'] = $_REQUEST['id'];
			$_A['linkage_list'] = linkageClass::GetList($data);
		}else{
			$msg = array($result);
		}
		$pname = empty($pname)?"跟类型下":$pname;
		$magic->assign("pname",$pname);
	}
}

/**
 * 添加
**/
elseif ($_A['query_type'] == "subnew"){
	if (isset($_POST['name']) && $_POST['name']!=""){
		$var = array("name","pid","type_id");
		$index = post_var($var);
		$result = $mysql->db_add("linkage",$index,"notime");
		
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			systemClass::createParameter_linkage();
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	
	}else{
		$linkage_type = $mysql->db_select("linkage_type","id=".$_REQUEST['id']);
		if ($result == false){
			$msg = array("你的输入有误");
		}else{
			$magic->assign("linkage_type",$linkage_type);
		}
		
		$linkage_sub= $mysql->db_select("linkage","id=".$_REQUEST['pid']);
		if ($result == false){
			$msg = array("你的输入有误");
		}else{
			$magic->assign("linkage_sub",$linkage_sub);
		}
		
		if ($msg == ""){
			$result = $mysql->db_selects("linkage","type_id=".$_REQUEST['id']." and pid=".$_REQUEST['pid']," `order` desc");
			$magic->assign("result",$result);
		}
		
		$pname = empty($pname)?"跟类型下":$pname;
		$magic->assign("pname",$pname);
	}
}


/**
 * 排序
**/
elseif ($_A['query_type'] == "actions"){
	if (isset($_POST['id'])){
		$data['id'] = $_POST['id'];
		$data['name'] = $_POST['name'];
		$data['value'] = $_POST['value'];
		$data['order'] = $_POST['order'];
		$result = linkageClass::Action($data);
		if ($result !== true){
			$msg = array($result);
		}else{
			systemClass::createParameter_linkage();
			$msg = array("操作成功");
		}
	}else{
		//添加
		if (isset($_POST['name'])){
			$data['type'] = "add";
			$data['name'] = $_POST['name'];
			$data['type_id'] = $_POST['type_id'];
			$data['value'] = $_POST['value'];
			$data['order'] = $_POST['order'];
			$result = linkageClass::Action($data);
			if ($result !== true){
				$msg = array($result);
			}else{
				systemClass::createParameter_linkage();
				$msg = array("操作成功");
			}
		}else{
			$msg = array("操作有误");
		}
	}
}
/**
 * 查看
**/
elseif ($_A['query_type'] == "view"){
	$_A['linkage_result'] = linkageClass::GetOne(array("id"=>$_REQUEST['id']));
}

/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$id = $_REQUEST['id'];
	$result = linkageClass::Delete(array("id"=>$id));
	if ($result !== true){
		$msg = array($result);
	}else{
		systemClass::createParameter_linkage();
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}

/**
 * 链接类型
**/
elseif ($_A['query_type'] == "type_new" || $_A['query_type'] == "type_edit"){
	if (isset($_POST['name'])){
		$var = array("name","nid","order");
		$data = post_var($var);
		if ($_A['query_type'] == "type_new"){
			$result = linkageClass::AddType($data);
			if ($result !== true){
				$msg = array($result);
			}else{
				systemClass::createParameter_linkage();
				$msg = array("添加成功");
			}
		}else{
			$data['id'] = $_POST['id'];
			$result = linkageClass::UpdateType($data);
			if ($result !== true){
				$msg = array($result);
			}else{
				systemClass::createParameter_linkage();
				$msg = array("添加成功");
			}
		}
		$user->add_log($_log,$result);//记录操作
	}elseif( $_A['query_type'] == "type_edit"){
		$data['id'] = $_REQUEST['id'];
		$_A['linkage_type_result'] = linkageClass::GetTypeOne($data);
	}
}

/**
 * 删除
**/
elseif ($_A['query_type'] == "type_del"){
	$result = linkageClass::DeleteType($_REQUEST['id']);
	if ($result !== true){
		$msg = array($result);
	}else{
		systemClass::createParameter_linkage();
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}
/**
 * 类型排序
**/
elseif ($_A['query_type'] == "type_action"){
	if (isset($_POST['id'])){
		$data['id'] = $_POST['id'];
		$data['name'] = $_POST['name'];
		$data['nid'] = isset($_POST['nid'])?$_POST['nid']:"";
		$data['order'] = $_POST['order'];
		$result = linkageClass::ActionType($data);
		if ($result !== true){
			$msg = array($result);
		}else{
			systemClass::createParameter_linkage();
			$msg = array("操作成功");
		}
	}else{
		if (isset($_POST['name'])){
			$data['type'] = $_POST['type'];
			$data['name'] = $_POST['name'];
			$data['nid'] = $_POST['nid'];
			$data['order'] = $_POST['order'];
			$result = linkageClass::ActionType($data);
			if ($result !== true){
				$msg = array($result);
			}else{
				systemClass::createParameter_linkage();
				$msg = array("操作成功");
			}
		}else{
			$msg = array("操作有误");
		}
	}
}

//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作","",$url);
}


?>