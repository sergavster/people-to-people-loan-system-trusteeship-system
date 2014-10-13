<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("credit_".$_A['query_type']);//检查权限

include_once("credit.class.php");

$_A['list_purview'] =  array("credit"=>array("积分类型管理"=>array("credit_list"=>"查看用户积分","credit_log"=>"查看积分明细","credit_type_list"=>"积分类型列表","credit_type_new"=>"添加积分类型","credit_type_edit"=>"编辑积分类型","credit_type_del"=>"删除积分类型")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>用户积分</a> - <a href='{$_A['query_url']}/rank{$_A['site_url']}'>等级管理</a> - <a href='{$_A['query_url']}/type{$_A['site_url']}'>积分类型列表</a> - <a href='{$_A['query_url']}/type_new{$_A['site_url']}'>添加积分类型</a> ";

//$_A['list_menu'] = "<a href='{$_A['admin_url']}&q=module/userinfo{$_A['site_url']}'>用户信息管理</a> - <a href='{$_A['admin_url']}&q=module/user{$_A['site_url']}'>用户管理</a> - <a href='{$_A['admin_url']}&q=module/user/new{$_A['site_url']}'>添加用户</a> - <a href='{$_A['admin_url']}&&q=module/credit{$_A['site_url']}'>积分管理</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=2{$_A['site_url']}'>工薪阶层资料配置</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=1{$_A['site_url']}'>私营业主资料配置</a>";
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "用户积分列表";
	
	if (isset($_REQUEST['username'])){
		$data['username'] = $_REQUEST['username'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = creditClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['credit_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

elseif ($_A['query_type'] == "log"){

	$_A['list_title'] = "用户积分明细";
	
	if (isset($_REQUEST['user_id'])){
		$data['user_id'] = $_REQUEST['user_id'];
	}
	$data['page'] = $_A['page'];
	$result = creditClass::GetLogList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['credit_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

elseif ($_A['query_type'] == "rank"){
	$_A['list_title'] = "积分等级";
	if (isset($_POST['name']) && $_POST['name']!=""){
		$data['name'] = $_POST['name'];
		$data['id'] = $_POST['id'];
		$data['rank'] = $_POST['rank'];
		$data['point1'] = $_POST['point1'];
		$data['point2'] = $_POST['point2'];
		$data['pic'] = $_POST['pic'];
		$result = creditClass::ActionRank($data);
		$msg = array("操作成功","",$_A['query_url']);
	}else{
		$result = creditClass::GetRankList($data);
		$_A['credit_rank_list'] = $result;
	}
	
}
elseif ($_A['query_type'] == "rank_new"){
	if (isset($_POST['name']) && $_POST['name']!=""){
		$var = array("name","rank","point1","point2","pic");
		$data = post_var($var);
		$result = creditClass::AddRank($data);
		$msg = array("添加成功");
	}else{
		$msg = array("操作有误");
	}
}

/**
 * 删除
**/
elseif ($_A['query_type'] == "rank_del"){
	$data['id'] = $_REQUEST['id'];
	$result = creditClass::DeleteRank($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}
/**
 * 如果类型为空的话则显示所有的文件列表
**/
elseif ($_A['query_type'] == "type" ){
	$_A['list_title'] = "类型列表";
	
	if (isset($_REQUEST['keywords'])){
		$data['name'] = $_REQUEST['keywords'];
	}
	
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = creditClass::GetTypeList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['credit_type_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

/**
 * 添加
**/
elseif ($_A['query_type'] == "type_new" || $_A['query_type'] == "type_edit" ){	
	if ($_A['query_type'] == "type_new"){
		$_A['list_title'] = "添加积分类型";
	}else{
		$_A['list_title'] = "修改积分类型";
	}
	
	if (isset($_POST['name'])){
		$var = array("nid","name","value","cycle","award_times","interval","remark");
		$data = post_var($var);
		
		if ($_A['query_type'] == "type_new"){
			$result = creditClass::AddType($data);
		}else{
			$data['id'] = $_POST['id'];
			$result = creditClass::UpdateType($data);
		}
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功","",$_A['query_url']."/type");
		}
		$user->add_log($_log,$result);//记录操作
	}elseif ($_A['query_type'] == "type_edit" ){
		$data['id'] = $_REQUEST['id'];
		$result = creditClass::GetTypeOne($data);
		if (is_array($result)){
			$_A['credit_type_result'] = $result;
		}else{
			$msg = array($result);
		}
		
	}
}


/**
 * 删除
**/
elseif ($_A['query_type'] == "type_del"){
	$data['id'] = $_REQUEST['id'];
	$result = creditClass::DeleteType($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功","",$_A['query_url']."/type");
	}
	$user->add_log($_log,$result);//记录操作
}

//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作","",$url);
}

?>