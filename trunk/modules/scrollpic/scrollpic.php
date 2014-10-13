<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("scrollpic_".$_A['query_type']);//检查权限

include_once("scrollpic.class.php");

$_A['list_purview'] =  array("scrollpic"=>array("滚动图片"=>array("scrollpic_list"=>"列表","scrollpic_new"=>"添加","scrollpic_del"=>"删除","scrollpic_type"=>"类型")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加</a> - <a href='{$_A['query_url']}/type{$_A['site_url']}'>类型</a>  ";


/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "列表";
	
	$data['page'] = $_A['page'];
	$data['epage'] = 20;
	$data['keywords'] = isset($_REQUEST['keywords'])?$_REQUEST['keywords']:"";
	$result = scrollpicClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['scrollpic_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
	
	
/**
 * 链接类型
**/
elseif ($_A['query_type'] == "type"){
	if (isset($_REQUEST['del_id'])){
		if ($_REQUEST['del_id'] !=1){
			$mysql->db_delete("scrollpic_type","id=".$_REQUEST['del_id']);
			$msg = array("删除成功","",$_A['query_url']."/type&a=system");
		}else{
			$msg = array("类型ID1为系统类型，不能删除","",$_A['query_url']."/type&a=system");
		}
	}elseif (!isset($_POST['submit'])){
		$_A['scrollpic_type_list'] = scrollpicClass::GetTypeList();
	}else{
		foreach ($_POST['id'] as $key => $val){
			$mysql->db_query("update {scrollpic_type} set typename='".$_POST['typename'][$key]."' where id=".$val);
		}
		if ($_POST['typename1']!=""){
			$index['typename'] = $_POST['typename1'];
			$mysql->db_add("scrollpic_type",$index,"notime");
		}
		$msg = array("类型操作成功","",$_A['query_url']."/type&a=system");
	}
}

/**
 * 添加
**/
elseif ($_A['query_type'] == "new" || $_A['query_type'] == "edit" ){
	if (isset($_POST['type_id']) && $_POST['type_id']!=""){
		$var = array("type_id","status","order","url","name","summary");
		foreach ( $var as $val){
			$data[$val] = !isset($_POST[$val])?"":$_POST[$val];
		}
		
		$datapic['file'] = "pic";
		$datapic['code'] = "scrollpic";
		$datapic['user_id'] = $_G['user_id'];
		$datapic['type'] = "new";
		$datapic['aid'] = $data['type_id'];
		$pic_result = $upload->upfile($datapic);
		if ($pic_result!=""){
			$data['pic'] = $pic_result['filename'];
		}
		
		
		if ($_A['query_type'] != "new"){
			$data['id'] = $_POST['id'];
			$result = scrollpicClass::Update($data);
		}else{
			$result = scrollpicClass::Add($data);
		}
		systemClass::createParameter_biaotype();
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			$msg = array("操作成功","返回上一页",$_A['query_url'].'&a=system');
		}
		$user->add_log($_log,$result);//记录操作
	
	
	}else{
		$_A['scrollpic_type_list'] = scrollpicClass::GetTypeList();
		if ($_A['query_type'] == "edit"){
			$_A['scrollpic_result'] = scrollpicClass::GetOne(array("id"=>$_REQUEST['id']));
		}
	}
}
	
	
/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = scrollpicClass::Delete($data);
	systemClass::createParameter_biaotype();
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功","返回上一页",$_A['query_url'].'&a=system');
	}
	$user->add_log($_log,$result);//记录操作
}


?>