<?
/******************************
 * $File: site.php
 * $Description: վ��
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
check_rank("site_all");//���Ȩ��
check_rank("site_".$_A['query_class']);//���Ȩ��
$_A['list_name'] = "��Ŀ����";
$_A['list_menu'] = "<a href={$_A['admin_url']}&q=site/loop{$_A['site_url']}>��Ŀ����</a> - <a href={$_A['admin_url']}&q=site/new{$_A['site_url']}>�����Ŀ</a> - <a href={$_A['admin_url']}&q=site{$_A['site_url']}>������Ŀ</a>";

//����Ա��������
$_A['admin_type'] = userClass::GetTypeList(array("type"=>1,"where"=>" and type_id!=1"));
foreach($_A['admin_type'] as $key => $value){
	$_A['admin_type_check'][$value['type_id']] = $value['name'];
}
/**
 * ��ʾ������Ŀ
**/
if ($_A['query_class'] == "list" ){
	$_A['list_title'] = "��Ŀ�б�"; 
	$_A['list_menu'] = '';
}
/**
 * ��ʾ�ɱ༭������Ŀ
**/
elseif ($_A['query_class'] == "loop" ){
}
/**
 * ��Ӻͱ༭��Ŀ
**/
elseif ($_A['query_class'] == "new" ||$_A['query_class'] == "edit"){
	if (isset($_POST['name'])){
		$var = array("name","rank","nid","pid","code","order","url","aurl","isurl","status","style","list_name","content_name","sitedir","visit_type","index_tpl","list_tpl","content_tpl","contentadmin","title","keywords","description");
		$data = post_var($var);
		$data['content'] = $data['contentadmin'];
		unset($data['contentadmin']);
		if ($_A['query_class'] == "new"){
			$data['user_id'] = $_G['user_id'];
			$result = siteClass::AddSite($data);
			if ($result===true){
				$msg = array("��Ŀ��ӳɹ�","",$_A['query_url'].$_A['site_url']);
			}else{
				$msg = array($result);
			}
		}else{
			$data['site_id'] = $_POST['site_id'];
			$result = siteClass::UpdateSite($data);
			if ($result===true){
				$_tpl = array("index_tpl","list_tpl","content_tpl");
				$tpl = post_var($_tpl);
				$tpl['site_id'] = $data['site_id'];
				if (isset($_POST['update_all'])){ 
					$tpl['type'] = "all";
					siteClass::UpdateTpl($tpl);
				}
				if (isset($_POST['update_brother'])){
					$tpl['type'] = "brother";
					siteClass::UpdateTpl($tpl);
				}
				$msg = array("��Ŀ�޸ĳɹ�","",$_A['query_url'].$_A['site_url']);
			}else{
				$msg = array($result);
			}
		}
		systemClass::createParameter_site();
	}else{
		$_A['module_list'] = moduleClass::GetList(array("type"=>"install"));//ģ���б�
		if (isset($_GET['pid'])){
			$_A['site_presult'] = siteClass::GetOne(array("site_id"=>$_GET['pid']));
		}
		if (isset($_GET['site_id'])){
			$_A['site_result'] = siteClass::GetOne(array("site_id"=>$_GET['site_id']));
		}
	}
}
/**
 * �޸���ͨ����Ŀ
**/
elseif ($_A['query_class'] == "update"){
	if (isset($_POST['name'])){
		$var = array("name","nid","site_id","status","litpic","clearlitpic","content","title","keywords","description");
		$data = post_var($var);
		$result = siteClass::UpdateSite($data);
		if ($result===true){
			systemClass::createParameter_site();
			$msg = array("�޸ĳɹ�");
		}else{
			$msg = array($result);
		}
	}else{
		if (isset($_GET['pid'])){
				$_A['site_presult'] = siteClass::GetOne(array("site_id"=>$_GET['pid']));
			}
		if (isset($_GET['site_id'])){
			$_A['site_result'] = siteClass::GetOne(array("site_id"=>$_GET['site_id']));
		}
	}
}
/**
 * ɾ����Ŀ
**/
elseif ($_A['query_class'] == "del"){
	$result =  siteClass::Delete(array("site_id"=>$_REQUEST['site_id']));
	if ($result!==true){
		$msg = array($result);
	}else{
		systemClass::createParameter_site();
		$msg = array("ɾ���ɹ�");
	}
}
/**
 * Ԥ��
**/
elseif ($_A['query_class'] == "view"){
	echo "<script>location.href='?".$_REQUEST['site_id']."'</script>";
	exit;
}
/**
 * �޸���Ŀ����
**/
elseif ($_A['query_class'] == "order"){
	//check_rank("site_order");//���Ȩ��
	if (isset($_POST['site_id']) && $_POST['site_id']!=''){
		foreach ($_POST['site_id'] as $key => $value){
			if (isset($_POST['rank']) && isset($_POST['rank'][$key]) && $_POST['rank'][$key]!=''){
				$rank = join(",",$_POST['rank'][$key]);
			}else{
				$rank = "";
			}
			$sql = "update {site} set `order`='".$_POST['order'][$key]."',rank='$rank' where `site_id` = '$value'";
			$result = $mysql->db_query($sql);
		}
	}
	systemClass::createParameter_site();
	$msg = array("�޸ĳɹ�");
}
/**
 * ��Ŀ��ģ��
**/
elseif ($_A['query_class'] == "module"){
	if (isset($_REQUEST['code']) && $_REQUEST['code']!=""){
		$data['code'] = $_REQUEST['code'];
		$result = moduleClass::GetOne($data);
		if ($result !=false){
		$_result = $result['index_tpl'].",".$result['list_tpl'].",".$result['content_tpl'];
		echo $_result;
		}
	}else{
		echo 2;
	}
	die();
}

/**
 * �ƶ���Ŀ
**/
elseif ($_A['query_class'] == "move"){
	if (isset($_POST['pid'])){
		$result = siteClass::MoveSite(array("site_id"=>$_POST['site_id'],"pid"=>$_POST['pid']));
		systemClass::createParameter_site();
		$msg = array("��Ŀ�ƶ��ɹ�");
	}else{
		$result = siteClass::GetOne(array("site_id"=>$_REQUEST['site_id']));
		$_A['site_result'] = $result;
		$move_list = siteClass::GetList(array("site_id"=>$_REQUEST['site_id']));
		$_A['site_list'] = $move_list;
	}
}
/**
 * ��Ŀ����վ
**/
elseif ($_A['query_class'] == "recycle"){
	$result = $module->get_site_recycle();
	$magic->assign("result",$result);
}
/**
 * ���������ģ�����ȡ����ģ��������ļ�
**/
else{
	if (file_exists("modules/{$_A['query_class']}/{$_A['query_class']}.php")){
		include_once(ROOT_PATH."modules/admin/module.php");
	}
	
	exit;
}
$template = "admin_site.html.php";
?>