<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("liuyan_".$_A['query_type']);//检查权限

include_once("liuyan.class.php");

$_A['list_purview'] =  array("liuyan"=>array("留言管理"=>array("liuyan_list"=>"用户列表","liuyan_reply"=>"回复留言","liuyan_set"=>"留言设置","liuyan_new"=>"添加留言","liuyan_edit"=>"修改留言")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}/new{$site_url}'>添加留言</a> - <a href='{$_A['query_url']}{$site_url}'>留言列表</a> - <a href='{$_A['query_url']}/set{$site_url}'>留言设置</a> ";


/**
 * 留言列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "留言列表";
	
	$data['page'] = $_A['page'];
	$data['epage'] = 20;
	$result = liuyanClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['liuyan_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

	
/**
 * 添加
**/
elseif ($_A['query_type'] == "new"  || $_A['query_type'] == "edit" ){
	
	$_A['list_title'] = "列表";
	
	if (isset($_POST['title'])){
		$var = array("title","name","email","tel","fax","company","address","type","status","content");
		$data = post_var($var);
		$data['user_id'] = $_G['user_id'];
		$_G['upimg']['file'] = "litpic";
		$pic_result = $upload->upfile($_G['upimg']);
		if ($pic_result!=""){
			$data['litpic'] = $pic_result;
		}
		
		if ($_A['query_type'] != "new"){
			$data['id'] = $_POST['id'];
			$result = liuyanClass::Update($data);
		}else{
			$result = liuyanClass::Add($data);
		}
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功","",$_A['query_url']);
		}
		$user->add_log($_log,$result);//记录操作
	}
	
	else{
		$result = liuyanClass::GetSet();
		if ($result!=false){
			$_A['liuyan_type_list'] = explode("|",$result['type']);
		
			if ($_A['query_type'] == "edit"){
				$data['id'] = $_REQUEST['id'];
				$_A['liuyan_result'] = liuyanClass::GetOne($data);
				
			}
		}
	}

	
	
}	


/**
 * 查看
**/
elseif ($_A['query_type'] == "view"){
	$data['id'] = $_REQUEST['id'];
	if (isset($_POST['reply'])){
		$data['reply'] = nl2br($_POST['reply']);
		$data['replytime'] = time(); 
		$data['replyip'] = ip_address(); 
		$result = liuyanClass::Update($data);
		$msg = array("回复成功");
	}else{
		$_A['liuyan_result'] = liuyanClass::GetOne($data);
	}
}


/**
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = liuyanClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}

/**
 * 留言设置
**/
elseif ($_A['query_type'] == "set"){
	if (isset($_POST['type'])){
		$result = liuyanClass::ActionSet($_POST);
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("设置成功");
		}
	}else{
		$_A['liuyan_set'] = liuyanClass::GetSet();
	}
}

//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作","",$url);
}
?>