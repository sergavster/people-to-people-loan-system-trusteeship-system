<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("limitapp_".$_A['query_type']);//检查权限

include_once("limitapp.class.php");

$_A['list_purview'] =  array("limitapp"=>array("额度管理"=>array("limitapp_list"=>"申请列表","limitapp_new"=>"添加额度","limitapp_edit"=>"编辑申请额度","limitapp_del"=>"删除申请款","limitapp_view"=>"审核申请")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>申请列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加申请</a>  ";


/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "申请列表";
	
	if (isset($_REQUEST['user_id'])){
		$data['user_id'] = $_REQUEST['user_id'];
	}
	
	if (isset($_REQUEST['username'])){
		$data['username'] = $_REQUEST['username'];
	}
	if (isset($_REQUEST['status'])){
		$data['status'] = $_REQUEST['status'];
	}
	
	
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = limitappClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['limitapp_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

	
/**
 * 添加
**/
elseif ($_A['query_type'] == "new"  || $_A['query_type'] == "edit" ){
	
	$_A['list_title'] = "申请信息";
	
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
	
	elseif (isset($_POST['account'])){
		$var = array("user_id","account","recommend_userid","content","other_content");
		$data = post_var($var);

		if ($_A['query_type'] == "new"){
			$result = limitappClass::Add($data);
		}else{
			$result = limitappClass::Update($data);
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
		$result = limitappClass::GetOne($data);
		if (is_array($result)){
			if ($result['status']==1){
				$msg = array("此申请已经通过，不能修改");
			}else{
				$_A['limitapp_result'] = $result;
			}
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
			//$result = limitappClass::GetOne($data);
			//$_A['limitapp_result'] = $result;
		}
		
	}
	
}	

/**
 * 查看
**/
elseif ($_A['query_type'] == "view"){
	$_A['list_title'] = "审核申请";
	if (isset($_POST['id'])){
		$var = array("id","user_id","status","verify_remark");
		$data = post_var($var);
		$data['verify_time'] = time();
		$result = limitappClass::Update($data);
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}else{
		$data['id'] = $_REQUEST['id'];
		$data['user_id'] = $_REQUEST['user_id'];
		$_A['limitapp_result'] = limitappClass::GetOne($data);
	}
}


/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = limitappClass::Delete($data);
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