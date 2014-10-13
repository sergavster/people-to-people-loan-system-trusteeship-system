<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("invest_".$_A['query_type']);//检查权限

include_once("invest.class.php");

$_A['list_purview'] =  array("invest"=>array("借款管理"=>array("invest_list"=>"借款列表","invest_new"=>"添加借款","invest_edit"=>"编辑借款","invest_del"=>"删除借款","invest_view"=>"审核借款")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>借款列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加借款</a>  ";


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
	$result = investClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['invest_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

	
/**
 * 添加
**/
elseif ($_A['query_type'] == "new"  || $_A['query_type'] == "edit" ){
	
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
		$var = array("user_id","name","status","use","time_limit","style","account","apr","lowest_account","most_account","valid_time","award","part_account","funds","is_false","open_account","open_invest","open_tender","open_credit","content");
		$data = post_var($var);

		if ($_A['query_type'] == "new"){
			$result = investClass::Add($data);
		}else{
			$result = investClass::Update($data);
		}
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}
	
	elseif ($_A['query_type'] == "edit" ){
		$data['user_id'] = $_REQUEST['id'];
		$data['id'] = $_REQUEST['id'];
		$result = investClass::GetOne($data);
		if (is_array($result)){
			$_A['invest_result'] = $result;
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
			//$result = investClass::GetOne($data);
			//$_A['invest_result'] = $result;
		}
		
	}
	
}	

/**
 * 查看
**/
elseif ($_A['query_type'] == "view"){
	$_A['list_title'] = "查看认证";
	if (isset($_POST['id'])){
		$var = array("id","status","verify_remark","jifen");
		$data = post_var($var);
		$data['verify_user'] = $_SESSION['user_id'];
		$result = investClass::Update($data);
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}else{
		$data['id'] = $_REQUEST['id'];
		$data['user_id'] = $_REQUEST['user_id'];
		$_A['invest_result'] = investClass::GetOne($data);
	}
}


/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = investClass::Delete($data);
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