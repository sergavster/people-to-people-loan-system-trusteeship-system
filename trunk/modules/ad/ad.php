<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("ad_".$_A['query_type']);//检查权限

include_once("ad.class.php");

$_A['list_purview'] =  array("ad"=>array("广告管理"=>array("ad_list"=>"广告列表","ad_view"=>"查看广告","ad_new"=>"添加广告","ad_edit"=>"编辑广告","ad_del"=>"删除广告")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}/new{$_A['site_url']}'>添加广告</a> - <a href='{$_A['query_url']}{$_A['site_url']}'>广告列表</a> ";
$_A['list_table'] = "";


/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "广告列表";
	//修改状态
	if (isset($_REQUEST['id']) && isset($_REQUEST['status'])){
		$sql = "update {ad} set status='".$_REQUEST['status']."' where id = ".$_REQUEST['id'];
		$mysql->db_query($sql);	
	}
	
	if (isset($_REQUEST['keywords']) && $_REQUEST['keywords']!=""){
		$data['name'] = $_REQUEST['keywords'];
	}
		
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	
	$result = adClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['ad_list'] = $result['list'];
		
		
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
		$_A['list_title'] = "添加广告";
	}else{
		$_A['list_title'] = "修改广告";
	}
	if (isset($_POST['name'])){
		//模块字段的表单
		$var = array("nid","name","timelimit","firsttime","endtime","content","endcontent");
		$data = post_var($var);
		$data['firsttime'] =  get_mktime($data['firsttime']);
		$data['endtime'] =  get_mktime($data['endtime']);
		$_G['upimg']['file'] = "litpic";
		$pic_result = $upload->upfile($_G['upimg']);
		if ($pic_result!=""){
			$data['litpic'] = $pic_result['filename'];
		}
		//添加表单的处理
		if ($_A['query_type'] == "new"){
			$result = adClass::Add($data);
			if ($result>0){
				$msg = array("添加成功","",$_A['query_url']);
			}else{
				$msg = array($result);
			}
		}
		//修改表单的处理
		else{
			$data['id'] = $_POST['id'];
			$result = adClass::Update($data);
			if ($result){
				$msg = array("修改成功","",$_A['query_url']);
			}else{
				$msg = array($result);
			}
		}
		
		//userClass::add_log($_log,$result);//记录操作
	}else{
		//认证机构模块的栏目
		$_A['site_list'] = siteClass::GetList(array("code"=>"ad"));
		
		
		//获取编辑的信息
		if ($_A['query_type'] == "edit"){
			$data['id'] = $_REQUEST['id'];
			$result = adClass::GetOne($data);
			if (is_array($result)){
				$_A['ad_result'] = $result;
			}else{
				$msg = array($result);
			}
			
		}
	
	}
}

/**
 * 查看
**/
elseif ($_A['query_type'] == "view"){
	$_A['ad_result'] = adClass::GetOne(array("id"=>$_REQUEST['id']));
}
	
	
/**
 * 删除
**/
elseif ($_A['query_type'] == "del"){
	$id = $_REQUEST['id'];
	$result = adClass::Delete(array("id"=>$id));
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