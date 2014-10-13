<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
$codeFields = $code."_fields";
check_rank($code."_".$_A['query_type']);//检查权限

include_once($code.".class.php");
$className = $code."Class";
$codeClass = new $className();

$_A['list_purview'] =  array($code=>array("文章管理"=>array($code."_list"=>"文章列表",$code."_new"=>"添加文章",$code."_edit"=>"编辑文章",$code."_del"=>"删除文章")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>{$_A['site_result']['name']}文章列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加文章{$_A['site_result']['name']}</a> ";
$_A['list_table'] = "";


/**
 * 如果类型为空的话则显示所有的文章列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "{$_A['site_result']['name']}列表";
	//修改状态
	if (isset($_GET['id']) && isset($_GET['status'])){
		$sql = "update {".$code."} set status='".$_GET['status']."' where id = ".$_GET['id'];
		$mysql->db_query($sql);	
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$data['code'] = $code;
	$data['site_id'] = $_A['site_id'];
	$data['flag_list'] = $_A['flag_list'];
	if(isset($_GET['title']) && $_GET['title']!=""){
		$data['name'] = urldecode($_GET['title']);
	}
	$result = $codeClass->GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['code_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
/**
 * 添加
**/
elseif ($_A['query_type'] == "new" || $_A['query_type'] == "edit" ){
	if ($_A['query_type'] == "new"){
		$_A['list_title'] = "添加{$_A['site_result']['name']}";
	}else{
		$_A['list_title'] = "修改{$_A['site_result']['name']}";
	}
	if (isset($_POST['name'])){
		$var = array("name","is_jump","jumpurl","summary","source","publish","contentadmin","flag","author","order","status","site_id","province","city","area");
		$data = post_var($var);
		$data['content'] = $data['contentadmin'];
		unset($data['contentadmin']);
		$data['code'] = $code;
		
		if ($_POST['clearlitpic']==1){
			$data['litpic'] = "";
			if (file_exists($_POST['old_litpic'])){
				@unlink($_POST['old_litpic']);
			}
		}else if (isset($_FILES['litpic'])){
			$_G['upimg']['file'] = "litpic";
			$pic_result = $upload->upfile($_G['upimg']);
			if (!empty($pic_result)){
				$data['litpic'] = $pic_result['filename'];
			}
		}
		//上传附件
		if ($_POST['clearfujian']==1){
			$data['fujian'] = "";
			if (file_exists($_POST['old_fujian'])){
				@unlink($_POST['old_fujian']);
			}
		}else if (isset($_FILES['fujian'])){
			$_G['upimg']['file'] = "fujian";
			$pic_result = $upload->upfile($_G['upimg']);
			if (!empty($pic_result)){
				$data['fujian'] = $pic_result['filename'];
			}
		}
		
		//自定义字段
		$fields = array();
		if ($_A['module_result']['fields']==1){
			$fields = post_fields(moduleClass::GetFieldsList(array("code"=>$code)));
		}
		if ($_A['query_type'] == "new"){
			$data['user_id'] = $_G['user_id'];
			$result = $codeClass->Add(array("data"=>$data,"fields"=>$fields));
		}else{
			$data['id'] = $_POST['id'];
			$result = $codeClass->Update(array("data"=>$data,"fields"=>$fields));
		}
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功","",$_A['query_url'].$_A['site_url']);
		}
		$user->add_log($_log,$result);//记录操作
	}else{
		$_A['site_list'] = siteClass::GetList(array("code"=>$code));
		if ($_A['query_type'] == "edit"){
			$data['code'] = $code;
			$data['id'] = $_REQUEST['id'];
			$result = $codeClass->GetOne($data);
			if (is_array($result)){
				$_A['code_result'] = $result;
			}else{
				$msg = array($result);
			}
		}
		
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
		
		//自定义字段
		if ($_A['module_result']['fields']==1){
			$result_fields = "";
			$data['code'] = $code;
			$data['result'] = $_A['code_result'];
			$_A['code_input']  = moduleClass::GetFieldsInput($data);
		}
	}
}

/**
 * 查看
**/
elseif ($_A['query_type'] == "view"){
	$_A['list_title'] = "查看文章";
	$data['code'] = $code;
	$data['id'] = $_REQUEST['id'];
	$_A['code_result'] = $codeClass->GetOne($data);
}


/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$result = $codeClass->Delete(array("code"=>$code,"id"=>$_REQUEST['id']));
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}

//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作");
}
?>