<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("links_".$_A['query_type']);//检查权限

include_once("links.class.php");

$_A['list_purview'] =  array("links"=>array("友情链接"=>array("links_list"=>"友情链接列表","links_new"=>"添加链接","links_del"=>"删除链接","links_type"=>"链接类型")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>链接列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加链接</a> - <a href='{$_A['query_url']}/type{$_A['site_url']}'>链接类型</a>  ";


/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "链接列表";
	
	$data['page'] = $_A['page'];
	$data['epage'] = 20;
	$data['keywords'] = isset($_REQUEST['keywords'])?$_REQUEST['keywords']:"";
	$result = linksClass::GetList($data);
	
	if (is_array($result)){
		$pages->set_data($result);
		$_A['links_list'] = $result['list'];
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
			$mysql->db_delete("links_type","id=".$_REQUEST['del_id']);
			$msg = array("删除成功","",$_A['query_url']."/type");
		}else{
			$msg = array("类型ID1为系统类型，不能删除","",$_A['query_url']."/type=".$_GET['a']);
		}
		systemClass::createParameter_biaotype();
	}elseif (!isset($_POST['submit'])){
		$_A['links_type_list'] = linksClass::GetTypeList();
	}else{
		foreach ($_POST['id'] as $key => $val){
			$mysql->db_query("update {links_type} set typename='".$_POST['typename'][$key]."' where id=".$val);
		}
		if ($_POST['typename1']!=""){
			$index['typename'] = $_POST['typename1'];
			$mysql->db_add("links_type",$index,"notime");
		}
		$msg = array("类型操作成功","",$_A['query_url']."/type&a=".$_GET['a']);
		systemClass::createParameter_biaotype();
	}
}

/**
 * 添加
**/
elseif ($_A['query_type'] == "new" || $_A['query_type'] == "edit" ){
	if (isset($_POST['type_id']) && $_POST['type_id']!=""){
		$var = array("type_id","status","order","url","logo","webname","summary","linkman","email");
		foreach ( $var as $val){
			$data[$val] = !isset($_POST[$val])?"":$_POST[$val];
		}
		$_G['upimg']['file'] = "logoimg";
		$_G['upimg']['mask_status'] = 0;
		$pic_result = $upload->upfile($_G['upimg']);
		if ($pic_result!=""){
			$data['logoimg'] = $pic_result['filename'];
		}
		if ($_A['query_type'] != "new"){
			$data['id'] = $_POST['id'];
			$result = linksClass::Update($data);
		}else{
			$result = linksClass::Add($data);
		}
		systemClass::createParameter_biaotype();
		if ($result == false){
			$msg = array("输入有误，请跟管理员联系");
		}else{
			$msg = array("操作成功","返回上一页",$_A['query_url'].'&a='.$_GET['a']);
		}
		$user->add_log($_log,$result);//记录操作
	}else{
		$_A['links_type_list'] = linksClass::GetTypeList();
		if ($_A['query_type'] == "edit"){
			$_A['links_result'] = linksClass::GetOne(array("id"=>$_GET['id']));
		}
	}
}
	
	
/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = linksClass::Delete($data);
	$p = systemClass::createParameter_biaotype();
	if ($result !== true){
		$msg = array($result);
	}elseif($p>0){
		$msg = array("删除成功","返回上一页",$_A['query_url'].'&a='.$_GET['a']);
	}
	$user->add_log($_log,$result);//记录操作
}


?>