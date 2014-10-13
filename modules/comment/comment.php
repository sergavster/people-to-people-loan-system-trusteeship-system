<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("comment_".$_A['query_type']);//检查权限
require_once 'comment.class.php';


$_A['list_purview'] =  array("comment"=>array("评论管理"=>array("comment_list"=>"评论列表","comment_new"=>"添加评论","comment_del"=>"删除评论")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>评论列表</a>";

/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	//修改状态
	if (isset($_REQUEST['id']) && isset($_REQUEST['status'])){
		$sql = "update {comment} set status='".$_REQUEST['status']."' where id = ".$_REQUEST['id'];
		$mysql->db_query($sql);
	}

	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = commentClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['comment_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

/**
 * 添加
**/
elseif ($_A['query_type'] == "new"  || $_A['query_type'] == "edit" || $_A['query_type'] == "view" ){
	
	$_A['list_title'] = "评论管理";
	if (isset($_POST['site_id'])){
		$var = array('user_id','module_code', 'article_id','comment');
		$data = post_var($var);
		
		if ($_A['query_type'] == "edit"){
			$data['id'] = $_GET['id'];
			$result = commentClass::Update($data);
		}
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}
	
	elseif ($_A['query_type'] == "edit" || $_A['query_type'] == "view" ){
		$data['id'] = $_GET['id'];
		$data['code'] = $_GET['module_code'];
		$result = commentClass::GetOne($data);
		if (is_array($result)){
			$_A['comment_result'] = $result['comment'];
			$_A['reply'] = $result['reply'];
		}else{
			$msg = array($result);
		}
		
	}
	
}			
/*
 * 回复评论
 */
elseif ($_A['query_type']=="add"){
	if(isset($_POST['pid'])){
		$var = array('pid','module_code','article_id','comment','flag','status');
		$data = post_var($var);
		$data['user_id'] = $_G['user_id'];
		$re = $mysql->db_add("comment", $data);
		if($re==false){
			$msg = array("回复评论失败");
		}else{
			$msg = array("回复评论成功");
		}
	}
}
/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = commentClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}


?>