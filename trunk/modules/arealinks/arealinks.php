<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("arealinks_".$_A['query_type']);//检查权限

include_once("arealinks.class.php");

$_A['list_purview'] =  array("arealinks"=>array("修网内容管理"=>array("arealinks_list"=>"列表","arealinks_new"=>"添加","arealinks_edit"=>"编辑","arealinks_del"=>"删除")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加</a>  ";


/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "信息列表";
	
	$data['page'] = $_A['page'];
	$data['epage'] = 20;
	$data['keywords'] = $_REQUEST['keywords'];
	$result = arealinksClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['arealinks_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

	
/**
 * 添加
**/
elseif ($_A['query_type'] == "new"  || $_A['query_type'] == "edit" ){
	if ($_A['query_type'] == "new" ){
		$_A['list_title'] = "添加";
	}else{
		$_A['list_title'] = "修改";
	}	
	//读取用户id的信息
	if (isset($_POST['webname'])){
		$var = array("status","order","url","webname","pr","flag","email","province","city","area","summary","linkman","email");
		$data = post_var($var);
		if ($_POST['clearlogo']==1){
			$data['logo'] = "";
		}else{
			$pic_name = upload('logo');
			if (is_array($pic_name)){
				$data['logo'] = $pic_name[0];
			}
		}
		
		if ($_A['query_type'] != "new"){
			$data['id'] = $_POST['id'];
			$result = arealinksClass::Update($data);
		}else{
			$result = arealinksClass::Add($data);
		}
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}
	
	elseif ($_A['query_type'] == "edit" ){
		$data['id'] = $_REQUEST['id'];
		$result = arealinksClass::GetOne($data);
		if (is_array($result)){
			$_A['arealinks_result'] = $result;
		}else{
			$msg = array($result);
		}
		
	}

	
	
}	

/**
 * 查看
**/
elseif ($_A['query_type'] == "view"){
	$_A['list_title'] = "查看";
	
	$data['id'] = $_REQUEST['id'];
	$_A['arealinks_result'] = arealinksClass::GetOne($data);

}


/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = arealinksClass::Delete($data);
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