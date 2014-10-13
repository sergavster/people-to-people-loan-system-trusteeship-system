<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("message_".$_A['query_type']);//检查权限

include_once("message.class.php");

$_A['list_purview'] =  array("message"=>array("短消息管理"=>array("message_list"=>"短消息列表","message_new"=>"添加短消息","message_edit"=>"编辑短消息","message_del"=>"删除短消息","message_view"=>"审核短消息")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>短消息列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加短消息</a>  ";


/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "信息列表";
	
	if (isset($_REQUEST['user_id'])){
		$data['user_id'] = $_REQUEST['user_id'];
	}
	
	if (isset($_REQUEST['username'])){
		$data['username'] = $_REQUEST['username'];
	}
	
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = messageClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['message_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

	
/**
 * 添加
**/
elseif ($_A['query_type'] == "new"){
	
	$_A['list_title'] = "管理短消息";
	
	if (isset($_POST['content'])){
		$var = array("send_userid","receive_userid","type","content");
		$data = post_var($var);
		$data['status'] = 0;
		$result = messageClass::Add($data);
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}
	
}	

/**
 * 查看
**/
elseif ($_A['query_type'] == "view"){
	$_A['list_title'] = "查看短消息";
	if (isset($_POST['id'])){
		$var = array("id","status","verify_remark","jifen");
		$data = post_var($var);
		$data['verify_user'] = $_SESSION['user_id'];
		$result = messageClass::Update($data);
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}else{
		$data['id'] = $_REQUEST['id'];
		$data['user_id'] = $_REQUEST['user_id'];
		$_A['message_result'] = messageClass::GetOne($data);
	}
}


/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = messageClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}

//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作","",$url);
}
?>