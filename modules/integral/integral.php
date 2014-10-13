 <?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("integral_".$_A['query_type']);//检查权限

include_once("integral.class.php");

$_A['list_purview'] = array(""=>array("礼品兑换"=>array("integral_list"=>"礼品兑换列表","integral_new"=>"添加礼品兑换","integral_del"=>"删除礼品兑换","integral_convert"=>"用户兑换信息")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>兑换物品列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加兑换物品</a>  - <a href='{$_A['query_url']}/convert{$_A['site_url']}'>用户兑换信息</a>  ";



/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "礼品兑换列表";
	
	$data['page'] = $_A['page'];
	$data['epage'] = 20;
	$data['name'] = isset($_REQUEST['name'])?$_REQUEST['name']:"";
	$result = integralClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['integral_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
	
}



/**
 * 添加
**/
elseif ($_A['query_type'] == "new" || $_A['query_type'] == "edit"){
	if($_A['query_type'] == "edit"){
		$_A['list_title'] = "积分物品添加";
	}else{
		$_A['list_title'] = "积分物品修改";
	}
	//读取用户id的信息
	if (isset($_POST['name'])){
		$var = array('name','need','number','province','city','area','order','flag','content','status','clearlitpic');
		$data = post_var($var);
		if($_FILES['litpic']['size']>0 && $_FILES['litpic']['error']==0){
			$ym = date("Y-m",time());
			$d = date("d",time());
			$path = ROOT_PATH.'data/upfiles/images/'.$ym.'/'.$d;
			mkdir($path,0777,true);
			$now = time();
			$hz = strrchr($_FILES['litpic']['name'],'.');
			move_uploaded_file($_FILES['litpic']['tmp_name'],$path.'/'.$now.$hz);
			$data['litpic'] = '/data/upfiles/images/'.$ym.'/'.$d.'/'.$now.$hz;
		}
		if($_A['query_type'] == "new"){
			$result = integralClass::Add($data);
		}else{
			$data['id'] = $_POST['id'];
			$result = integralClass::update($data);
		}
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}
	elseif($_A['query_type'] == "edit"){
		$data['id'] = $_REQUEST['id'];
		$result = integralClass::GetOne($data);
		if (is_array($result)){
			$_A['integral_result'] = $result;
		}else{
			$msg = array("操作有误");
		}
	}
}
	
/**
 * 查看
**/	
elseif ($_A['query_type'] == "view"){
	$data['id'] = $_REQUEST['id'];
	$result = integralClass::GetOne($data);
	if (is_array($result)){
		$_A['integral_result'] = $result;
	}else{
		$msg = array("操作有误");
	}
}


/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = integralClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}


/**
 * 显示兑换信息列表
**/
elseif ($_A['query_type'] == "convert"){
	$_A['list_title'] = "礼品兑换详细信息";
	
	$data['page'] = $_A['page'];
	$data['epage'] = 20;
	$data['name'] = isset($_REQUEST['name'])?$_REQUEST['name']:"";
	$result = integralClass::GetConvertList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['integral_convert_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
	
}

	
/**
 * 查看
**/	
elseif ($_A['query_type'] == "convert_view"){
	if (isset($_POST['status'])){
		$data['status'] = $_POST['status'];
		$data['remark'] = $_POST['remark'];
		$data['id'] = $_POST['id'];
		$result = integralClass::ActionConvert($data);
		$msg = array("兑换成功");
	}else{
		$data['id'] = $_REQUEST['id'];
		$result = integralClass::GetConvertOne($data);
		if (is_array($result)){
			$_A['integral_convert_result'] = $result;
		}else{
			$msg = array("操作有误");
		}
	}
}
?>