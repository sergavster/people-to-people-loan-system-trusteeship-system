<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("user_".$_A['query_type']);//检查权限

$_A['list_purview'] =  array("user"=>array("用户管理"=>array("user_list"=>"用户列表","user_view"=>"查看用户信息","user_new"=>"添加用户","user_edit"=>"修改用户","user_del"=>"删除用户","user_type"=>"用户类型","user_type_order"=>"用户类型排序","user_type_del"=>"删除用户类型","user_type_new"=>"添加用户类型","user_type_edit"=>"编辑用户类型")));//权限
$_A['list_name'] = "用户管理";
//$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>用户列表</a> - <a href='{$_A['query_url']}/vip{$_A['site_url']}'>vip用户</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加用户</a> - <a href='{$_A['query_url']}/type{$_A['site_url']}'>用户类型</a>";
$_A['list_menu'] = "<a href='{$_A['admin_url']}&q=module/userinfo{$_A['site_url']}'>用户信息管理</a> - <a href='{$_A['admin_url']}&q=module/user{$_A['site_url']}'>用户管理</a> - <a href='{$_A['admin_url']}&q=module/user/new{$_A['site_url']}'>添加用户</a> - <a href='{$_A['admin_url']}&&q=module/credit{$_A['site_url']}'>积分管理</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=2{$_A['site_url']}'>工薪阶层资料配置</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=1{$_A['site_url']}'>私营业主资料配置</a>";

$_A['list_table'] = "";

/**
 * 用户列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "用户列表";
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$data['type'] = 2;
	if(isset($_GET['username'])){
		$data['username']=$_GET['username'];
	}
	if(isset($_GET['email'])){
		$data['email']=$_GET['email'];
	}
	if(isset($_GET['realname'])){
		$data['realname']=$_GET['realname'];
	}
	$result = userClass::GetList($data);
	$pages->set_data($result);
	$_A['user_list'] = $result['list'];
	$_A['showpage'] = $pages->show(3);
}
/**
 * 用户列表
**/
/*
if ($_A['query_type'] == "typechange"){
	$_A['list_title'] = "用户改变类型申请";
	if (isset($_REQUEST['id']) && $_REQUEST['id']!=""){
		$data['id'] = $_REQUEST['id'];
		$data['status'] = $_REQUEST['status'];
		$data['type'] = "update";
		$result = userClass::TypeChange($data);
		$msg = array("类型修改成功","",$_A['query_url']."/typechange");
	}else{
		$data['page'] = $_A['page'];
		$data['epage'] = $_A['epage'];
		$data['type'] = "list";
		$result = userClass::TypeChange($data);
		$pages->set_data($result);
		
		$_A['user_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}
}*/
/**
 * 添加和编辑用户
**/
elseif ($_A['query_type'] == "new" || $_A['query_type'] == "edit" || $_A['query_type'] == "view"){
	if ($_A['query_type'] == "new" ){
		$_A['list_title'] = "添加用户";
	}else{
		$_A['list_title'] = "修改用户";
	}
	if (isset($_POST['realname'])){
		$var = array("type_id","username","email","realname","password","sex","qq","wangwang","tel","phone","address","status","province","city","area","card_type","card_id","islock","invite_userid","invite_money","serial_id","user_type");
		$data = post_var($var);
		$data['area'] = post_area();
		$data['birthday'] = get_mktime($_POST['birthday']);
		$purview_usertype = explode(",",$_SESSION['purview']);
		//add by weego 20120929 修改资料用户后导致搜索不到用户bug
		if($data['type_id']==''){
			$data['type_id']='2';
		}
		if (!in_array("userinfo_edit",$purview_usertype) ){
			$msg = array("您没有权限添加或管理此类的管理员".$index['type_id']);
		}elseif($data['email']!='' && !is_email($data['email'])){
			$msg = array('邮箱格式不正确');
		}elseif($data['card_type']==1 && $data['card_id']!='' && !isIdCard($data['card_id'])){
			$msg = array('身份证号码有误');
		}else{
			if ($_A['query_type'] == "new"){
				$check_username = userClass::CheckUsername(array("username"=>$data['username']));
				$check_email = false;
				if($data['email']!='' && userClass::CheckEmail(array("email"=>$data['email']))){
					$check_email = true;
				}
				if (isset($data['card_id']) && $data['card_id']!=""){
					$check_card_id = userClass::CheckIdcard(array("card_id"=>$data['card_id']));
				}
				$msg_content="";
				if ($check_username) {
					$msg_content .= "用户名已经存在！";
				}
				if ($check_email){
					$msg_content .= "邮箱已经存在！";
				}
				if ($check_card_id){
					$msg_content .= "身份证已经存在！";
				}
				if ($msg_content!=""){
					$msg = array($msg_content);
				}
				else{
					$msg = '';
					if ($rdGlobal['uc_on'])
					{
						$uid = uc_user_register($index["username"], $index["password"], $index["email"]);
						if ($uid <= 0) {
							if ($uid == -1) {
								$msg = '用户名不合法';
							} elseif ($uid == -2) {
								$msg = '包含要允许注册的词语';
							} elseif ($uid == -3) {
								$msg = '用户名已经存在';
							} elseif ($uid == -4) {
								$msg = 'Email 格式有误';
							} elseif ($uid == -5) {
								$msg = 'Email 不允许注册';
							} elseif ($uid == -6) {
								$msg = '该 Email 已经被注册';
							} else {
								$msg = '未定义';
							}
						}
					}
					if($msg==''){
						$result = userClass::AddUser($data);
						if ($result>0){
							$msg = array("用户名添加成功","",$_A['query_url']);
						}else{
							$msg = array($result);
						}
					}
				}	
			}else{
				if ($data['password']==""){
					unset($data['password']);
				}
				$data["user_id"] = $_POST['user_id'];
				$check_email = false;
				if($data['email']!=''){
					$check_email = userClass::CheckEmail(array("email"=>$data['email'],"user_id"=>$data["user_id"]));
				}
				//
				if ($check_email==true){
					$msg = array("邮箱已经存在");
				}else{
					$ucresult = 1;
					
					if($rdGlobal['uc_on'] && isset($data['password'])){
						require_once ROOT_PATH . '/core/config_ucenter.php';
						require_once ROOT_PATH . '/uc_client/client.php';
						$ucresult = uc_user_edit($data['username'], '', $_POST['password'], '', 1);
						if ($ucresult == -1) {
							$msg = array("旧密码不正确,请使用论坛的登录密码","",$url);
						} elseif ($ucresult == -4) {
							$msg = array("Email 格式有误");
						} elseif ($ucresult == -5) {
							$msg = array("Email 不允许注册");
						} elseif ($ucresult == -6) {
							$msg = array("该 Email 已经被注册");
						}
					}
					
					if($ucresult==1){
						$result = $user->UpdateUser($data);
						if ($_POST['kefu_userid']!=""){
							$sql = "update `{user_cache}` set kefu_userid=".$_POST['kefu_userid']." where user_id='{$data['user_id']}'";
							$mysql->db_query($sql);
						}
						if ($result===false){
							$msg = array($result);
						}else{
							$msg = array("修改成功");
						}
					}
				}
			}
		}
	}else{
		$user_type = userClass::GetTypeList(array("type"=>2));
		if ($user_type==false){
			$msg = array("还没有类型，请先添加","添加用户类型","{$_A['query_url']}/type_new");
		}else{
			foreach ($user_type as $key => $value){
				$purview_usertype = explode(",",$_SESSION['purview']);
				if (in_array("manager_new_".$value['type_id'],$purview_usertype) || in_array("other_all",$purview_usertype) ){
					$list_type[$value['type_id']] = $value['name']; 
				}
			}
			$magic->assign("list_type",$list_type);
		}
		if ($_A['query_type'] == "edit" || $_A['query_type'] == "view"){
			if ($_REQUEST['user_id']==1){
				$msg = array("此管理员不能编辑,如果要修改，请到修改个人信息");
			}else{
				$_A['user_result'] = userClass::GetOne(array("user_id"=>$_REQUEST['user_id']));
			}
			//用户的查看
			if ($_A['query_type'] == "view"){
				$_A['user_attestation'] = $mysql->db_fetch_arrays('select p1.*,p2.name as type_name from {attestation} p1 left join {attestation_type} p2 on p1.type_id=p2.type_id where user_id='.$_REQUEST['user_id'].' and upload_type=1');
				$magic->assign("_A",$_A);
				$magic->display("view.tpl","modules/user");exit;
			}
		}
	}
}
/**
 * 锁定和解锁用户
**/
elseif ($_A['query_type'] == "lock"){
	if ($_GET['user_id']==1){
		$msg = array("此用户不能删除");
	}else{
		//repair by weego 20120703
		//$result = userClass::DeleteUser(array("user_id"=>$_REQUEST['user_id'],"type"=>2));
		$islock = $_GET['islock']==1?1:0;
		$result = $user->UpdateUser(array("user_id"=>$_REQUEST['user_id'],"islock"=>$islock));
		if($result==false){
			$msg = array("操作失败",'',"{$_A['query_url']}&a=system");
		}else{
			$msg = array("操作成功",'',"{$_A['query_url']}&a=system");
			$user->add_log($_log,$result);//记录操作
		}
	}
}
/**
 * 用户类型列表
**/
elseif ($_A['query_type'] == "type"){
	$_A['user_type_list'] = userClass::GetTypeList(array("type"=>2));//0表示用户组的类别，1表示管理组的类型
}
/**
 * 添加和编辑用户类型
**/
elseif ($_A['query_type'] == "type_new" || $_A['query_type'] == "type_edit"){
	if (isset($_POST['name'])){
		$var = array("name","order","remark","status","summary","purview");
		$data = post_var($var);
		$data['type'] = 2;
		if ($_A['query_type'] == "type_new"){
			$result = userClass::AddType($data);
		}else{
			$data['type_id'] = $_POST['type_id'];
			$result = userClass::UpdateType($data);
		}
		if ($result == false){
			$msg = array($result);
		}else{
			$msg = array("类型操作成功","","{$_A['query_url']}/type&a=userinfo");
		}
		$user->add_log($_log,$result);//记录操作
	}else{
		if ($_A['query_type'] == "type_edit"){
			$result = userClass::GetTypeOne(array("type_id"=>$_GET['type_id']));
			$magic->assign("result",$result);
		}
	}
}
/**
 * 修改用户类型排序
**/
elseif ($_A['query_type'] == "type_order"){
	$data['order'] = $_POST['order'];
	$data['type_id'] = $_POST['type_id'];
	$result = userClass::OrderType($data);
	if ($result == false){
		$msg = array("输入有误，请跟管理员联系");
	}else{
		$msg = array("排序修改成功");
	}
	$user->add_log($_log,$result);//记录操作
}
/**
 * 删除用户类型
**/
elseif ($_A['query_type'] == "type_del"){
	$data['type_id'] = $_REQUEST['type_id'];
	if ($data['type_id']==1){
		$msg = array("超级管理员类型禁止删除");
	}else{
		$result = userClass::DeleteType($data);
		if ($result){
			$msg = array("删除成功");
		}else{
			$msg = array($result);
		}
		$user->add_log($_log,$result);//记录操作
	}
}
/**
 * VIP用户
**/
elseif ($_A['query_type'] == "vip"){
	if(isset($_GET['username'])){
		$data['username']=$_GET['username'];
	}
	$data['vip_status']=1;
	$result = userClass::GetList($data);//0表示用户组的类别，1表示管理组的类型
	$pages->set_data($result);
	$_A['user_vip_list'] = $result['list'];
	$_A['showpage'] = $pages->show(3);
}
/**
 * VIP审核查看
**/
/*
elseif ($_A['query_type'] == "vipview"){
	if (isset($_POST['isvip'])){
		$var = array("isvip","vip_veremark");
		$data = post_var($var);
		if ($data['isvip']==1){
			$data['vip_time'] = time();
		}
		$data['user_id'] = $_POST['user_id'];
		$result = userClass::UpdateUser($data);
		
		if ($result == false){
			$msg = array($result);
		}else{
                    
			$msg = array("VIP用户审核成功","","{$_A['query_url']}/vip");
		}
		
		$user->add_log($_log,$result);//记录操作
	}else{
		$_A['user_result'] = userClass::GetOne(array("user_id"=>$_REQUEST['user_id']));
	}
}
*/

/*
*用户统计
*/
elseif ($_A['query_type'] == "statistics"){
	$data['salesman_user'] = $_GET['salesman_user'];
	$data['dotime1'] = $_GET['dotime1'];
	$data['dotime1'] = $_GET['dotime2'];
	$data['belong_organ'] = $_GET['belong_organ'];
	$data['recommend_organ'] = $_GET['recommend_organ'];
	$data['user_type'] = $_GET['user_type'];
	$data['province'] = $_GET['province'];
	$data['city'] = $_GET['city'];
	if($data['province']==-2){
		$sql = 'select count(p1.user_id) count,p1.province from {user} p1 group by p1.province';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		foreach($re as $k=>$v){
			$arr[intval($v['province'])] += $v['count'];
		}
		$sql = 'select p1.id,p1.name from {area} p1 where p1.pid=0';
		$area = $mysql->db_fetch_arrays($sql);
		$areaArr = array();
		$zcount = 0;
		foreach($area as $key=>$value){
			$areaArr[] = array('name'=>$value['name'],'value'=>intval($arr[$value['id']]));
			$zcount += $arr[$value['id']];
		}
		$areaArr[] = array('name'=>'其他','value'=>intval($arr[0]));
		$zcount += $arr[0];
		$areaArr[] = array('name'=>'合计','value'=>$zcount);
		$_A['user_statistics'] = $areaArr;
	}elseif($data['belong_organ']==-2){
		$sql = 'select count(p1.user_id) count,p1.belong_organ from {user} p1 group by p1.belong_organ';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		foreach($re as $k=>$v){
			$arr[$v['recommend_organ']] += $v['count'];
		}
		$sql = "select p1.name from {linkage} p1 left join {linkage_type} p2 on p1.type_id=p2.id where p2.nid='recommend_organ'";
		$recommend = $mysql->db_fetch_arrays($sql);
		$recommend_arr = array();
		$zcount = 0;
		foreach($recommend as $key=>$value){
			$recommend_arr[] = array('name'=>$value['name'],'value'=>intval($arr[$value['name']]));
			$zcount += $arr[$value['name']];
		}
		$recommend_arr[] = array('name'=>'其他','value'=>intval($arr['']));
		$zcount += $arr[''];
		$recommend_arr[] = array('name'=>'合计','value'=>$zcount);
		$_A['user_statistics'] = $recommend_arr;
	}elseif($data['recommend_organ']==-2){//按推荐机构
		$sql = 'select count(p1.user_id) count,p1.recommend_organ from {user} p1 group by p1.recommend_organ';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		foreach($re as $k=>$v){
			$arr[$v['recommend_organ']] += $v['count'];
		}
		$sql = "select p1.name from {linkage} p1 left join {linkage_type} p2 on p1.type_id=p2.id where p2.nid='recommend_organ'";
		$recommend = $mysql->db_fetch_arrays($sql);
		$recommend_arr = array();
		$zcount = 0;
		foreach($recommend as $key=>$value){
			$recommend_arr[] = array('name'=>$value['name'],'value'=>intval($arr[$value['name']]));
			$zcount += $arr[$value['name']];
		}
		$recommend_arr[] = array('name'=>'其他','value'=>intval($arr['']));
		$zcount += $arr[''];
		$recommend_arr[] = array('name'=>'合计','value'=>$zcount);
		$_A['user_statistics'] = $recommend_arr;
	}elseif($data['salesman_user']==-2){//按业务员
		$sql = 'select count(p1.user_id) count,p1.salesman_user from {user} p1 group by p1.salesman_user';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		foreach($re as $k=>$v){
			$arr[intval($v['salesman_user'])] += $v['count'];
		}
		$zcount = 0;
		$sql = 'select type_id from {user_type} where type=3';
		$typearr = $mysql->db_fetch_array($sql);
		$sql = 'select user_id,username from {user} where type_id='.intval($typearr['type_id']);
		$salesman = $mysql->db_fetch_arrays($sql);
		$re_array = array();
		foreach($salesman as $key=>$value){
			$re_array[] = array('name'=>$value['username'],'value'=>intval($arr[$value['user_id']]));
			$zcount += intval($arr[$value['user_id']]);
		}
		$re_array[] = array('name'=>'其他','value'=>intval($arr[0]));
		$zcount += intval($arr[0]);
		$re_array[] = array('name'=>'合计','value'=>$zcount);
		$_A['user_statistics'] = $re_array;
	}elseif($data['user_type']==-2){//按用户类型
		$sql = 'select count(p1.user_id) count,p1.user_type from {user} p1 group by p1.user_type';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		$zcount = 0;
		foreach($re as $k=>$v){
			if($v['user_type']==1){
				$arr[] = array('name'=>'私营业主','value'=>$v['count']);
			}elseif($v['user_type']==2){
				$arr[] = array('name'=>'工薪族','value'=>$v['count']);
			}else{
				$arr[] = array('name'=>'其他','value'=>$v['count']);
			}
			$zcount+=$v['count'];
		}
		$arr[] = array('name'=>'合计','value'=>$zcount);
		$_A['user_statistics'] = $arr;
	}else{
		$re = $user->userStatistics($data);
		$_A['user_statistics'] = array(array('name'=>'结果','value'=>$re['count']));
	}
	if(isset($_GET['type']) && $_GET['type']=='excel'){
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename='用户统计.xls'");
		foreach($_A['user_statistics'] as $k=>$v){
			echo $v['name']."\t".$v['value']."\r\n";
		}
		exit();
	}
}
?>