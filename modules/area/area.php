<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("area_list");//检查权限

include_once("area.class.php");

$_A['list_purview'] =  array("area"=>array("地区模块"=>array("area_list"=>"地区管理")));
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}'>地区管理</a> - <a href='{$_A['query_url']}/data'>导入数据</a> - <a href='{$_A['query_url']}/cache'>数据缓存</a>";
$_A['list_table'] = "";


/**
 * 如果类型为空的话则显示所有的文件列表
**/

if ($_A['query_type'] == "list" || $_A['query_type'] == "edit"){
	$_A['list_title'] = "省份";
	$data['page'] = $_A['page'];
	$data['epage'] = 50;
	$data['pid'] = "0";
	if (isset($_REQUEST['action'])  ){
		if ($_REQUEST['action'] =="city"){
			$_A['list_title'] = "城市";
			$data['pid'] = $_REQUEST['pid'];
		}elseif ($_REQUEST['action'] =="area"){
			$_A['list_title'] = "地区";
			$data['pid'] = $_REQUEST['pid'];
		}
	}
	$result = areaClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['area_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
	
	if (isset($_POST['name']) && $_POST['name']!=""){
		$var = array("name","pid","nid","order");
		$data = post_var($var);
		if ($_A['query_type'] == "edit"){
			$data['id'] = $_POST['id'];
			$result = areaClass::Update($data);
		}else{
			$result = areaClass::Add($data);
		}
		systemClass::createParameter_area();
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		
		$user->add_log($_log,$result);//记录操作
		
	}else{
		if ($_A['query_type'] == "edit"){
			$data['id'] = $_REQUEST['id'];
			$_A['area_result'] = areaClass::GetOne($data);
		}

	}
}


/**
 * 导入数据
**/
elseif ($_A['query_type'] == "data"){
	$sql = "select count(*) as num from {area} ";
	$result = $mysql->db_fetch_array($sql);
	if ($result['num']>0){
	$msg = array("地区表已经有数据，如果要重新安装，请卸载此模块再安装");
	}else{
	include("add_area.php");
	$msg = array("导入成功");
	systemClass::createParameter_area();
	}
}


/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$result = areaClass::Delete(array("id"=>$_REQUEST['id']));
	if ($result !== true){
		$msg = array($result);
	}else{
		systemClass::createParameter_area();
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}


?>