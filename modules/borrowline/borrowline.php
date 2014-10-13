<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("borrowline_".$_A['query_type']);//检查权限

include_once("borrowline.class.php");

$_A['list_purview'] =  array("borrowline"=>array("线下借款"=>array("borrowline_list"=>"借款列表","borrowline_new"=>"添加借款","borrowline_edit"=>"编辑借款","borrowline_del"=>"删除借款","borrowline_view"=>"审核借款")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>借款列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加借款</a>  ";


/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	//修改状态
	if (isset($_REQUEST['id']) && isset($_REQUEST['status'])){
		$sql = "update {borrow_line} set status='".$_REQUEST['status']."' where id = ".$_REQUEST['id'];
		$mysql->db_query($sql);	
	}
	
	$_A['list_title'] = "线下借款";
	
	if (isset($_REQUEST['user_id'])){
		$data['user_id'] = $_REQUEST['user_id'];
	}
	
	if (isset($_REQUEST['username'])){
		$data['username'] = $_REQUEST['username'];
	}
	
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = borrowlineClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['borrowline_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}


elseif ($_A['query_type'] == "view"  ){
	if (isset($_POST['status']) && $_POST['status']!=""){
		$data['id'] = $_POST['id'];
		$data['status'] = $_POST['status'];
		$data['verify_remark'] = $_POST['verify_remark'];
		$data['verify_user'] = $_G['user_id'];
		$result = borrowlineClass::Verify($data);
		$msg = array("审核成功");
	}else{
		$data['user_id'] = $_REQUEST['user_id'];
		$data['id'] = $_REQUEST['id'];
		$result = borrowlineClass::GetOne($data);
		if (is_array($result)){
			$_A['borrowline_result'] = $result;
		}else{
			$msg = array($result);
		}
	}
}
/**
 * 添加
**/
elseif ($_A['query_type'] == "new"  || $_A['query_type'] == "edit"   ){
	
	$_A['list_title'] = "管理信息";
	
	//读取用户id的信息
	if (isset($_REQUEST['user_id']) && isset($_POST['username'])){
		if(isset($_POST['user_id']) && $_POST['user_id']!=""){
			$data['user_id'] = $_POST['user_id'];
			$result = userClass::GetOne($data);
		}elseif(isset($_POST['username']) && $_POST['username']!=""){
			$data['username'] = $_POST['username'];
			$result = userClass::GetOne($data);
		}
		if ($result==false){
			$msg = array("找不到此用户");
		}else{
			echo "<script>location.href='".$_A['query_url']."/new&user_id={$result['user_id']}'</script>";
		}
	}
	
	elseif (isset($_POST['name'])){
		$var = array("user_id","borrow_use","borrow_qixian","area","tel","account","pawn","name","xing","sex","email","content");
		$data = post_var($var);

		if ($_A['query_type'] == "new"){
			$result = borrowlineClass::Add($data);
		}else{
			$data['id'] = $_REQUEST['id'];
			$result = borrowlineClass::Update($data);
		}
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}
	
	elseif ($_A['query_type'] == "edit" ){
		$data['user_id'] = $_REQUEST['user_id'];
		$data['id'] = $_REQUEST['id'];
		$result = borrowlineClass::GetOne($data);
		if (is_array($result)){
			$_A['borrowline_result'] = $result;
		}else{
			$msg = array($result);
		}
		
	}

	
	elseif(isset($_REQUEST['user_id']) && !isset($_POST['username'])){
		$data['user_id'] = $_REQUEST['user_id'];
		$result = userClass::GetOne($data);
		if ($result==false){
			$msg = array("您的输入有误","",$_A['query_url']);
		}else{
			$_A['user_result'] = $result;
			//$result = borrowlineClass::GetOne($data);
			//$_A['borrowline_result'] = $result;
		}
		
	}
	
}	



/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = borrowlineClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功","",$_A['query_url']);
	}
	$user->add_log($_log,$result);//记录操作
}


//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作");
}
?>