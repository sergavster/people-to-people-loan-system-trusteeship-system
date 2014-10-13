<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("albums_".$_A['query_type']);//检查权限

include_once("albums.class.php");

$_A['list_purview'] =  array("albums"=>array("相册管理"=>array("albums_list"=>"相册列表","albums_new"=>"添加相册","albums_del"=>"删除相册")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>相册列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加相册</a> - <a href='{$_A['query_url']}/type{$_A['site_url']}'>相册类型</a> ";


/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "相册列表";
	if(isset($_REQUEST['keywords'])){
		$data['keywords'] = $_REQUEST['keywords'];
	}
	if(isset($_REQUEST['site_id'])){
		$data['site_id'] = $_REQUEST['site_id'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = albumsClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['albums_list'] = $result['list'];
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
		$_A['list_title'] = "添加花絮";
	}else{
		$_A['list_title'] = "修改花絮";
	}
	if (isset($_POST['name'])){
		$var = array("name","order","site_id","flag","clearlitpic","status","content","province","city","area");
		$data = post_var($var);
		$_G['upimg']['cut_status'] = 1;
		if ($_POST['clearlitpic']==1){
			$data['litpic'] = "";
		}else{
			$_G['upimg']['file'] = "litpic";
			$_G['upimg']['cut_width'] = "100";
			$_G['upimg']['cut_height'] = "100";
			$pic_result = $upload->upfile($_G['upimg']);
			if (!empty($pic_result)){
				$data['litpic'] = $pic_result;
			}
		}
		
		
		$data['pics'] = "";
		if (isset($_POST['_pics'])){
			$data['pics'] .= join(",",$_POST['_pics']);
		}
		$_G['upimg']['file'] = "pics";
		$_G['upimg']['cut_width'] = "800";
		$_G['upimg']['cut_height'] = "600";
		$pic_result = $upload->upfile($_G['upimg']);
		if (!empty($pic_result)){
			$data['pics'] .= ",".join(",",$pic_result);
		}
		
		
		
		if ($_A['query_type'] == "new"){
			$result = albumsClass::Add($data);
		}else{
			$data['id'] = $_POST['id'];
			$result = albumsClass::Update($data);
		}
		
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}elseif ($_A['query_type'] == "edit" ){
		
		$data['id'] = $_REQUEST['id'];
		$result = albumsClass::GetOne($data);
		if (is_array($result)){
			$_A['albums_result'] = $result;
		}else{
			$msg = array($result);
		}
		
	}
}


/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = albumsClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}

/**
 * 删除
**/
elseif ($_A['query_type'] == "type_del"){
	$data['id'] = $_REQUEST['id'];
	$result = albumsClass::TypeDelete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除类型成功");
	}
	$user->add_log($_log,$result);//记录操作
}

/**
 * 删除
**/
elseif ($_A['query_type'] == "type_del"){
	$data['id'] = $_REQUEST['id'];
	$result = albumsClass::TypeDelete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除类型成功");
	}
	$user->add_log($_log,$result);//记录操作
}

//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作","",$url);
}
?>