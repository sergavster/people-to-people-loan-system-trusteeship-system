<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("attestation_".$_A['query_type']);//检查权限

include_once("attestation.class.php");

$_A['list_purview'] =  array("attestation"=>array("证件管理"=>array("attestation_list"=>"证件列表",
		"attestation_new"=>"添加证件",
		"attestation_edit"=>"编辑证件",
		"attestation_del"=>"删除证件",
		"attestation_view"=>"审核证件",
		"attestation_type_list"=>"类型列表",
		"attestation_type_new"=>"添加类型",
		"attestation_type_edit"=>"编辑类型",
		"attestation_type_del"=>"删除类型",
		"attestation_realname"=>"实名认证",
		"attestation_realnameview"=>"实名显示",
		"attestation_all"=>"用户认证信息",
		"attestation_all_s"=>"用户认证信息",
		"attestation_vip"=>"vip认证",
		"attestation_vipview"=>"vip审核",
		"attestation_viewall"=>"用户认证列表",
		"attestation_audit"=>"用户认证操作",
		"attestation_view_all"=>"用户认证查看")));//权限
$_A['list_name'] = $_A['module_result']['name'];
if($_G['user_result']['type_id']==3){
	$_A['list_menu'] = "<a href='{$_A['query_url']}/viewall{$_A['site_url']}'>查看所有的用户</a>  - <a href='{$_A['query_url']}/vip{$_A['site_url']}&type=2'>vip审核</a>";
}elseif($_G['user_result']['type_id']==4){
	$_A['list_menu'] = "<a href='{$_A['query_url']}/viewall{$_A['site_url']}'>查看所有的用户</a> -  <a href='{$_A['query_url']}/vip{$_A['site_url']}&type=2'>vip审核</a>   - <a href='{$_A['query_url']}/vip{$_A['site_url']}&type=1'>vip认证</a>  - <a href='{$_A['query_url']}/all_s{$_A['site_url']}'>用户认证信息</a> - <a href='{$_A['query_url']}/viewall{$_A['site_url']}'>查看所有的用户</a>";
}else{
	$_A['list_menu'] = "<a href='{$_A['query_url']}/type_top_list{$_A['site_url']}'>阶层列表</a> - <a href='{$_A['query_url']}/type_tow_list{$_A['site_url']}'>二级分类</a> - <a href='{$_A['query_url']}/type_list{$_A['site_url']}'>资料类型列表</a> - <a href='{$_A['query_url']}{$_A['site_url']}'>证件列表</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>添加证件</a> - <a href='{$_A['query_url']}/type_new{$_A['site_url']}'>添加类型</a> - <a href='{$_A['query_url']}/vip{$_A['site_url']}&type=2'>vip审核</a>  - <a href='{$_A['query_url']}/vip{$_A['site_url']}&type=1'>查看VIP会员</a>  - <a href='{$_A['query_url']}/realname{$_A['site_url']}'>实名认证审核</a>  - <a href='{$_A['query_url']}/all_s&site_id=26&a=attestation&type=phone&typeStatus=2'>手机认证审核</a>  - <a href='{$_A['query_url']}/all_s&site_id=26&a=attestation&type=video&typeStatus=2'>视频认证审核</a>  - <a href='{$_A['query_url']}/all_s&site_id=26&a=attestation&type=scene&typeStatus=2'>现场认证审核</a>  - <a href='{$_A['query_url']}/viewall&site_id=47&a=attestation'>用户认证总览</a>";
}
/**
 * 如果类型为空的话则显示所有的文件列表
 **/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "证件列表";
	
	if (isset($_GET['user_id']) && $_GET['user_id']!=""){
		$data['user_id'] = $_GET['user_id'];
	}
	if (isset($_GET['type_id']) && $_GET['type_id']!=""){
		$data['type_id'] = $_GET['type_id'];
	}
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	if (isset($_GET['realname']) && $_GET['realname']!=""){
		$data['realname'] = $_GET['realname'];
	}
	if (isset($_GET['typeStatus']) && $_GET['typeStatus']!=""){
		$data['status'] = $_GET['typeStatus'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	//$data['upload_type'] = 1;
	//$result = attestationClass::GetList($data);
	$result = attestationClass::getListAtt($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['attestation_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
elseif ($_A['query_type'] == "ulist"){
	$user_id = intval($_GET['user_id']);
	if($user_id<1){
		$msg = array("请不要乱操作");
	}else{
		$result = attestationClass::getUserAttList($user_id);
		$_A['user_attestation_list'] = $result;
	}
}
/**
 * 如果类型为空的话则显示所有的文件列表
 **/
elseif ($_A['query_type'] == "viewall"){
	$_A['list_title'] = "查看所有的信息";
	if($_G['user_result']['type_id']==3){
		$data['kefu_userid'] = $_G['user_id'];
	}
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	if (isset($_GET['realname']) && $_GET['realname']!=""){
		$data['realname'] = $_GET['realname'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = userClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['viewall_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);

	}else{
		$msg = array($result);
	}
}
/**
 * 查看全部资料
 **/
elseif ($_A['query_type'] == "view_all"){
	if($_GET['user_id']<=0){
		$msg = array("请不要乱操作");
	}else{
		$_A['user_result'] = userClass::GetOne(array("user_id"=>$_GET['user_id']));
		if($_A['user_result']==false){
			$msg = array("无相关用户");
		}elseif($_A['user_result']['kefu_userid']!=$_G['user_id'] && $_G['user_result']['type_id']==3){
			$msg = array("请不要乱操作");
		}else{
			include_once(ROOT_PATH."modules/userinfo/userinfo.class.php");
			$_A['userinfo_result'] = userinfoClass::GetOne(array("user_id"=>$_GET['user_id']));
		}
	}
}
/**
 * 给用户添加修改证件
 **/
elseif ($_A['query_type'] == "new" || $_A['query_type'] == "edit" ){
	if ($_A['query_type'] == "new"){
		$_A['list_title'] = "添加证件";
	}else{
		$_A['list_title'] = "修改证件";
	}
	if (isset($_POST['type_id'])){
		if($_POST['valicode']!=$_SESSION['valicode'] || $_POST['valicode']==''){
			$msg = array("验证码不正确");
		}else{
			unset($_SESSION['valicode']);
			$re = $mysql->db_fetch_array("select user_id from `{user}` where username='{$_POST['username']}'");
			if($re==false){
				$msg = array($_POST['username'].'&nbsp;这个用户不存在');
			}else{
				$var = array("type_id","content","litpic","litpic","status","jifen");
				$data = post_var($var);
				$data['user_id'] = $re['user_id'];
				if($_A['query_type'] == "new"){
					$_G['upimg']['user_id'] = $_G['user_id'];
					$_G['upimg']['file'] = "litpic";
					$_G['upimg']['cut_status'] = 0;
					$_G['upimg']['code'] = "attestation";
					$pic_result = $upload->upfile($_G['upimg']);
					if ($pic_result!=""){
						$data['litpic'] = $pic_result['filename'];//上传的图片
					}
					$result = attestationClass::Add($data);
				}else{
					$data['id'] = $_POST['id'];
					$result = attestationClass::Update($data);
				}
				if ($result !== true){
					$msg = array($result);
				}else{
					$msg = array("操作成功");
				}
				$user->add_log($_log,$result);//记录操作
			}
		}
	}elseif($_A['query_type'] == "edit" ){
		$_A['attestation_type_list'] = attestationClass::GetTypeList(array("limit"=>"all"));
		$data['id'] = $_GET['id'];
		$result = attestationClass::GetOne($data);
		if (is_array($result)){
			$_A['attestation_result'] = $result;
		}else{
			$msg = array($result);
		}
	}else{
		$_A['attestation_type_list'] = attestationClass::GetTypeList(array("limit"=>"all"));
	}
}
/**
 * 查看及审核证件
 **/
elseif ($_A['query_type'] == "view"){
	require_once("modules/credit/credit.class.php");
	require_once("modules/message/message.class.php");
	$_A['list_title'] = "查看认证";
	$result = creditClass::GetTypeOne(array("nid"=>"zhengjian"));
	$_A['arrestation_value'] = $result['value'];
	$_A['credit_type_id'] = $result['id'];
	$_A['credit_type_name'] = $result['name'];
	if (isset($_POST['id'])){
		if($_SESSION['valicode']!=$_POST['valicode'] || $_POST['valicode']==''){
			$msg = array("验证码不正确");
		}elseif($_POST['jifen']<0){
			$msg = array("积分不能为负数");
		}else{
			unset($_SESSION['valicode']);
			$var = array("id","status","verify_remark","jifen");
			$data = post_var($var);
			$data['verify_user'] = $_G['user_id'];
			$data['verify_time'] = time();
			if ($data['status']!=1){
				$data['jifen'] = 0;
			}
			$attestation_result = attestationClass::GetOne(array("id"=>$data['id']));
			if ($attestation_result['status']==1){
				$msg = array("此证件已经审核通过，请不要重复审核。");
			}elseif ($data['status']==1){
				$result = attestationClass::Update($data);//更新类型状态
				$credit['nid'] = "zhengjian";
				$credit['user_id'] = $_POST['user_id'];
				$credit['value'] = $data['jifen'];
				$credit['op_user'] = $_G['user_id'];
				$credit['op'] = 1;//增加
				$credit['type_id'] = $_A['credit_type_id'];
				$credit['remark'] = $data['verify_remark'];
				creditClass::UpdateCredit($credit);//更新积分

				$message['sent_user'] = $_G['user_id'];
				$message['receive_user'] = $_POST['user_id'];
				$message['name'] = "{$_POST['type_name']}审核通过，加{$data['jifen']}分";
				$message['content'] = "{$_POST['type_name']}审核通过，加{$data['jifen']}分".$data['verify_remark'];
				$message['type'] = "system";
				$message['status'] = 0;
				messageClass::Add($message);//发送短消息
				$msg = array("操作成功","",$_A['query_url'].$_A['query_site']."&a=attestation");
			}elseif ($data['status']==2){
				$message['sent_user'] = $_G['user_id'];
				$message['receive_user'] = $_POST['user_id'];
				$message['name'] = "{$_POST['type_name']}审核未通过";
				$message['content'] = $data['verify_remark'];
				$message['type'] = "system";
				$message['status'] = 0;
				messageClass::Add($message);//发送短消息
				$result = attestationClass::Update($data);//更新类型状态
				$msg = array("操作成功","",$_A['query_url'].$_A['query_site']."&a=attestation");
			}
		}
		$user->add_log($_log,$result);//记录操作
	}else{
		if($_GET['id']<=0){
			$msg = array("请不要乱操作");
		}else{
			$data['id'] = $_GET['id'];
			$_A['attestation_result'] = attestationClass::GetOne($data);
			if ($_A['attestation_result']['status']==1){
				$msg = array("此信息已通过认证，请不要重新认证");
			}
		}
	}
}
/**
 * 删除
 **/
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_GET['id'];
	$result = attestationClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}
/**
 * 如果类型为空的话则显示所有的文件列表
 **/
elseif ($_A['query_type'] == "type_list"){
	$_A['list_title'] = "证件列表";
	if (isset($_GET['user_id'])){
		$data['user_id'] = $_GET['user_id'];
	}
	if (isset($_GET['type_id'])){
		$data['type_id'] = $_GET['type_id'];
	}
	if (isset($_GET['username'])){
		$data['username'] = $_GET['username'];
	}
	$data['limit'] = "all";
	$result = attestationClass::GetTypeList($data);
	if (is_array($result)){
		$_A['attestation_type_list'] = $result;
	}else{
		$msg = array($result);
	}
}
/*
 * 阶层列表
*/
elseif($_A['query_type'] == "type_top_list"){
	$_A['list_title'] = "阶层列表";
	$result = attestationClass::GetTypeList(array('pid'=>-2,'limit'=>'all'));
	if (is_array($result)){
		$_A['attestation_type_list'] = $result;
	}else{
		$msg = array($result);
	}
}
/*
 * 修改阶层包含
*/
elseif ($_A['query_type']=="type_top_include"){
	$_A['list_title'] = "阶层包含";
	if($_POST['ptype_id']>0){
		$data['type_id'] = $_POST['ptype_id'];
		$data['include'] = implode(",", $_POST['type_id']);
		$result = attestationClass::UpdateType($data);
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		//$user->add_log($_log,$result);//记录操作
	}elseif (isset($_GET['type_id'])){
		$result = attestationClass::GetTypeList(array('pid'=>-1,'limit'=>'all'));
		$pre = attestationClass::GetTypeOne(array('type_id'=>$_GET['type_id']));
		if (is_array($pre)){
			$_A['attestation_type_list'] = $result;
			$_A['attestation_top_list'] = $pre;
		}else{
			$msg = array($result);
		}
	}else{
		$msg = array("请不要乱操作");
	}
}
/*
 * 二级列表
*/
elseif($_A['query_type']=="type_tow_list"){
	$_A['list_title'] = "二级分类";
	$result = attestationClass::GetTypeList(array('pid'=>-1,'limit'=>'all'));
	if (is_array($result)){
		$_A['attestation_type_list'] = $result;
	}else{
		$msg = array($result);
	}
}
elseif($_A['query_type'] == "type_zj_list"){
	$_A['list_title'] = "发标必选项";
	if(isset($_POST['pid']) && $_POST['pid']>=1){
		$pid=$_POST['pid'];
		unset($_POST['pid']);
		$array_1 = array();
		foreach ($_POST as $k=>$v){
			$arr = explode("_", $k);
			if($arr[2]=="select"){
				$array_1[$arr[1]]['select']=$v;
			}else{
				$array_1[$arr[1]]=$v;
			}
		}
		$borrow_must = serialize($array_1);
		$sql = "update `{attestation_type}` set borrow_must='{$borrow_must}' where type_id={$pid}";
		$mysql->db_query($sql);
		$msg = array("操作成功");
	}else{
		$id=$_GET['type_id'];
		if($id<=0){
			$msg = array("请不要乱操作");
		}else{
			$pre = $mysql->db_fetch_array("select * from `{attestation_type}` where type_id=$id");
			if($pre['include']==""){
				$result = "无相关内容";
			}else{
				$result = $mysql->db_fetch_arrays("select * from `{attestation_type}` where type_id in({$pre['include']})");
			}
			$prearr = unserialize($pre['borrow_must']);
			if (is_array($result)){
				foreach ($result as $k=>$v){
					$select = $prearr[$v['type_id']]['select'];
					unset($prearr[$v['type_id']]['select']);
					$re = attestationClass::GetTypeList(array('pid'=>$v['type_id'],'limit'=>'all'));
					if($re!=""){
						foreach ($re as $k_1=>$v_1){
							if(in_array($v_1['type_id'],$prearr[$v['type_id']])){
								$re[$k_1] = "<input type='checkbox' checked='checked' name='typeid_{$v['type_id']}_{$v['name']}[]' value='{$v_1['type_id']}' />{$v_1['name']}";
							}else{
								$re[$k_1] = "<input type='checkbox' name='typeid_{$v['type_id']}_{$v['name']}[]' value='{$v_1['type_id']}' />{$v_1['name']}";
							}
						}
						$count = count($re,0);
						if($count>0){
							$x = $count."选<input type='text' size='5' name='typeid_{$v['type_id']}_select' value='{$select}' />";
							array_push($re, $x);
						}
						$result[$k]['son'] = $re;
					}
				}
				$_A['attestation_type_list'] = $result;
				$_A['attestation_presult'] = $pre;
			}else{
				$msg = array($result);
			}
		}
	}
}
/**
 * 添加修改证件类型
 **/
elseif ($_A['query_type'] == "type_new" || $_A['query_type'] == "type_edit" ){
	if ($_A['query_type'] == "type_new"){
		$_A['list_title'] = "添加证件";
	}else{
		$_A['list_title'] = "修改证件";
	}
	if (isset($_POST['name'])){
		$var = array("name","order","summary","remark","status","jifen","pid");
		$data = post_var($var);
		if ($_A['query_type'] == "type_new"){
			$result = attestationClass::AddType($data);
		}else{
			$data['type_id'] = $_POST['type_id'];
			$result = attestationClass::UpdateType($data);
		}
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}elseif ($_A['query_type'] == "type_edit" ){
		$data['type_id'] = $_GET['type_id'];
		$result = attestationClass::GetTypeOne($data);
		if (is_array($result)){
			$_A['attestation_type_result'] = $result;
		}else{
			$msg = array($result);
		}
	}
	$_A['attestation_type_plist'] = $mysql->db_fetch_arrays("select * from `{attestation_type}` where pid=-1");
}
/*
 * 添加和修改二级分类
*/
elseif ($_A['query_type']=="type_tow_new" || $_A['query_type']=="type_tow_edit"){
	if(isset($_POST['name'])){
		if($_POST['name']==""){
			$msg = array("类型名称不能为空");
		}else{
			$var = array("name","order","summary","remark","nid");
			$data = post_var($var);
			$data['status']=1;
			$data['pid']=-1;
			if ($_A['query_type'] == "type_tow_new"){
				$result = attestationClass::AddType($data);
			}else{
				$data['type_id'] = $_POST['type_id'];
				$result = attestationClass::UpdateType($data);
			}
			if ($result !== true){
				$msg = array($result);
			}else{
				$msg = array("操作成功");
			}
			$user->add_log($_log,$result);//记录操作
		}
	}else{
		if(isset($_GET['type_id'])){
			$_A['list_title'] = "修改二级分类";
			$data['type_id'] = $_GET['type_id'];
			if($data['type_id']<=0){
				$msg = array("操作有误");
			}else{
				$result = attestationClass::GetTypeOne($data);
				if (is_array($result)){
					$_A['attestation_type_result'] = $result;
				}else{
					$msg = array($result);
				}
			}
		}else{
			$_A['list_title'] = "添加二级分类";
		}
	}
}
/**
 * 删除
 **/
elseif ($_A['query_type'] == "type_del"){
	$data['type_id'] = $_GET['type_id'];
	$result = attestationClass::DeleteType($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}
/**
 * 修改用户类型排序
 **/
elseif ($_A['query_type'] == "type_order"){
	$data['order'] = $_POST['order'];
	$data['type_id'] = $_POST['type_id'];
	$result = attestationClass::OrderType($data);
	if ($result == false){
		$msg = array("输入有误，请跟管理员联系");
	}else{
		$msg = array("排序修改成功");
	}
	$user->add_log($_log,$result);//记录操作
}
/**
 * 如果类型为空的话则显示所有的文件列表
 **/
elseif ($_A['query_type'] == "realname"){
	$_A['list_title'] = "实名认证";
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	if(isset($_GET['real_status'])){
		if($_GET['real_status']==1){
			$data['real_status'] = "1";
		}elseif($_GET['real_status']==0){
			$data['real_status'] = "0";
		}else{
			$data['real_status'] = "2";
		}
	}
	if(isset($_GET['username'])){
		$data['username'] = $_GET['username'];
	}
	if(isset($_GET['cardID'])){
		$data['cardID'] = $_GET['cardID'];
	}
	$data['order'] = "real_status";
	$result = userClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['user_real_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}

/**
 * 如果类型为空的话则显示所有的文件列表
 **/
elseif ($_A['query_type'] == "realnameview"){
	$_A['list_title'] = "实名查看";
	if (isset($_GET['sfznum'])){
		$data['sfznum'] = $_GET['sfznum'];
	}
	if (isset($_GET['truename'])){
		$data['truename'] = iconv('GB2312','UTF-8',$_GET['truename']);
	}
	$data['username']=$_G['system']['con_sfyzusername'];
	$data['password']=$_G['system']['con_sfyzpassword'];
	if ($_G['system']['con_issfyz']=="1")
	{
		$http = $_G['system']['con_sfyzurl'];
		//$re= postSMS($http,$data);			//POST方式提交
		$re= getSend($http,$data);				//GET方式提交
		$re=trim($re);
		$tmp=explode("&",$re);
		$result = '';
		foreach($tmp as $v) {
			$ar_tmp = explode("=", $v);
			$result[$ar_tmp[0]] = $ar_tmp[1];
		}
		$result["sfzname"]=iconv('UTF-8','GB2312',$result["sfzname"]);
		$_A['sfz_result']=$result;
	}else
	{
		$msg = array("站点未开启身份验证功能");
	}
	$magic->assign("_A",$_A);
	$magic->display("view.tpl","modules/attestation");exit;
}
/**
 * 用户所有的相关信息
 **/
elseif ($_A['query_type'] == "all_s"){
	$_A['list_title'] = "用户相关的认证信息";
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	if(isset($_GET['username'])){
		$data['username'] = $_GET['username'];
	}
	if(isset($_GET['realname'])){
		$data['realname'] = $_GET['realname'];
	}
	if(isset($_GET['telphone'])){
		$data['telphone'] = $_GET['telphone'];
	}
	if (isset($_GET['type'])){
		if ($_GET['type']=="phone"){
// 			$data['phone_status'] = 1;
			if(isset($_GET['typeStatus'])){
				$data['phone_status'] = $_GET['typeStatus'];
// 				if($_GET['typeStatus']=="2"){
// 					$data['phone_status'] = 2;
// 				}
			}
		}elseif ($_GET['type']=="video"){
// 			$data['video_status'] = 1;
			if (isset($_GET['typeStatus'])){
				$data['video_status'] = $_GET['typeStatus'];
// 				if ($_GET['typeStatus']=="2"){
// 					$data['video_status'] = 2;
// 				}
			}
		}elseif ($_GET['type']=="email"){
// 			$data['email_status'] = 1;
			if (isset($_GET['typeStatus'])){
				$data['email_status'] = $_GET['typeStatus'];
// 				if ($_GET['typeStatus']=="2"){
// 					$data['email_status'] = 2;
// 				}
			}
		}elseif ($_GET['type']=="scene"){
// 			$data['scene_status'] = 1;
			if (isset($_GET['typeStatus'])){
				$data['scene_status'] = $_GET['typeStatus'];
// 				if ($_GET['typeStatus']=="2"){
// 					$data['scene_status'] = 2;
// 				}
			}
		}elseif ($_GET['type']=="realname"){
// 			$data['real_status'] = 1;
			if (isset($_GET['typeStatus'])){
				$data['real_status'] = $_GET['typeStatus'];
// 				if ($_GET['typeStatus']=="2"){
// 					$data['real_status'] = 2;
// 				}
			}
		}
	}
	$result = userClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['user_all_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
/**
 * 审核
 **/
elseif ($_A['query_type'] == "audit"){
	require_once(ROOT_PATH."modules/credit/credit.class.php");
	require_once(ROOT_PATH."modules/message/message.class.php");
	if (isset($_POST['status'])){
		if($_SESSION['valicode']!=$_POST['valicode'] || $_POST['valicode']==''){
			$msg = array("验证码不正确");
		}else{
			unset($_SESSION['valicode']);
			$_name = array("realname"=>"实名认证","email"=>"邮箱认证","phone"=>"手机认证","video"=>"视频认证","scene"=>"现场认证");
			if ($_POST['status']==2){
				$data['name'] = $_name[$_POST['nid']]."审核没通过";
				if($_POST['nid']=="realname"){
					$user->UpdateUser(array("user_id"=>$_POST['user_id'],"real_status"=>0));
				}elseif($_POST['nid']=="phone"){
					$user->UpdateUser(array("user_id"=>$_POST['user_id'],"phone_status"=>2));
				}elseif($_POST['nid']=="video"){
					$user->UpdateUser(array("user_id"=>$_POST['user_id'],"video_status"=>2));
				}elseif($_POST['nid']=="scene"){
					$user->UpdateUser(array("user_id"=>$_POST['user_id'],"scene_status"=>2));
				}
			}elseif ($_POST['status']==1){
				$_res = true;
				$_data['user_id'] = $_POST['user_id'];
				$user_result = userClass::GetOne($_data);
				if ($_POST['nid']=="realname"){
					if ($user_result['real_status']!=1){
						include_once(ROOT_PATH."modules/account/account.class.php");
						$account_result =  accountClass::GetOneAccount($_data);
						$log['user_id'] = $_data['user_id'];
						$log['type'] = "realname";
						$realname_money = isset($_G["system"]["con_realname_fee"])?$_G["system"]["con_realname_fee"]:0;
						$log['money'] = $realname_money;
						$log['total'] = $account_result['total']-$log['money'];
						$log['use_money'] = $account_result['use_money']-$log['money'];
						$log['no_use_money'] = $account_result['no_use_money'];
						$log['collection'] = $account_result['collection'];
						$log['to_user'] = 0;
						$log['remark'] = "实名认证扣除费用,操作管理员ID:{$_G['user_id']}";
						accountClass::AddLog($log);
						$user->UpdateUser(array("user_id"=>$_POST['user_id'],"real_status"=>1));//用户的真实姓名状态
					}else{
						$_res = false;
					}
				}elseif($_POST['nid']=="phone"){
					if ($user_result['phone_status']==1){
						$_res = false;
					}else{
						if ($user_result['phone_status']>1){
							$phone = $user_result['phone_status'];
							$user->UpdateUser(array("user_id"=>$_POST['user_id'],"phone"=>$phone,"phone_status"=>1));
						}else{
							$user->UpdateUser(array("user_id"=>$_POST['user_id'],"phone_status"=>1));
						}
					}
				}elseif ($_POST['nid']=="video"){
					if ($user_result['video_status']==1){
						$_res = false;
					}else{
						if ($_G['system']["con_video_feestatus"]==1){
							include_once(ROOT_PATH."modules/account/account.class.php");
							$account_result =  accountClass::GetOneAccount($_data);
							$log['user_id'] = $_data['user_id'];
							$log['type'] = "video";
							$log['money'] = 10;
							$log['total'] = $account_result['total']-$log['money'];
							$log['use_money'] = $account_result['use_money']-$log['money'];
							$log['no_use_money'] = $account_result['no_use_money'];
							$log['collection'] = $account_result['collection'];
							$log['to_user'] = 0;
							$log['remark'] = "视频认证扣除费用";
							accountClass::AddLog($log);
						}
						$user->UpdateUser(array("user_id"=>$_POST['user_id'],"video_status"=>1));//视频认证
					}
				}elseif ($_POST['nid']=="scene"){
					if ($user_result['scene_status']==1){
						$_res = false;
					}else{
						$user->UpdateUser(array("user_id"=>$_POST['user_id'],"scene_status"=>1));//视频认证
					}
				}
				if ($_res==true){
					$credit['nid'] = $_POST['nid'];
					$credit['user_id'] = $_POST['user_id'];
					$credit['value'] = $_POST['jifen'];
					$credit['op_user'] = $_G['user_id'];
					$credit['op'] = 1;//增加
					$credit['remark'] = nl2br($_POST['content']);
					creditClass::UpdateCredit($credit);//更新积分
					$data['name'] = $_name[$_POST['nid']]."审核通过，加信用积分{$_POST['jifen']}分。";
				}
			}
			if ($_res==true){
				$data['sent_user'] = "admin";
				$data['receive_user'] = $_POST['user_id'];
				$data['content'] = nl2br($_POST['content']);
				$data['type'] = "system";
				$data['status'] = 0;
				messageClass::Add($data);//发送短消息
				$msg = array("修改成功","",$_A['query_url']."/all_s&a=attestation");
			}else{
				$msg = array("此信息已经审核过，请不要重复审核。");
			}
		}
	}else{
		$type = $_GET['type'];
		$result = creditClass::GetTypeOne(array("nid"=>$type));
		if($type == "video"){
			$_A['arrestation_value']=10;
		}elseif($type == "scene"){
			$_A['arrestation_value']=20;
		}else{
			$_A['arrestation_value'] = $result['value'];
		}
		$_A['credit_type_id'] = $result['id'];
		$magic->assign("_A",$_A);
		$magic->display("audit.tpl","modules/attestation");exit;
	}
}
/**
 * VIP用户
 **/
elseif ($_A['query_type'] == "vip"){
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$data['vip_status'] = isset($_GET['type'])?$_GET['type']:"";
	if($_G['user_result']['type_id']==3){
		$data['kefu_userid'] = $_G['user_id'];
	}
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	if (isset($_GET['kefu']) && $_GET['kefu']!=""){
		$data['kefu_username'] = $_GET['kefu'];
	}
	$result = userClass::GetList($data);//0表示用户组的类别，1表示管理组的类型
	$pages->set_data($result);
	$_A['user_vip_list'] = $result['list'];
	$_A['showpage'] = $pages->show(3);
}
/**
 * VIP用户
 **/
elseif ($_A['query_type'] == "jifen"){
	if ($_POST['id']!=""){
		$_val = 0;
		foreach ($_POST['id'] as $key => $_value){
			$sql = "update `{credit_log}` set value='{$_POST['val'][$key]}' where id='{$_value}'";
			$mysql->db_query($sql);
			$_val +=$_POST['val'][$key];
		}
		$sql = "update  `{credit}` set value = {$_val}  where user_id='{$_POST['user_id']}'";
		$mysql->db_query($sql);
		$sql = "update  `{user_cache}` set credit = {$_val}  where user_id='{$_POST['user_id']}'";
		$mysql->db_query($sql);
		$msg = array("修改成功");
	}else{
		$sql = "select p1.*,p2.username,p2.realname,p3.name as typename from `{credit_log}` as p1
		left join `{user}` as p2 on p1.user_id=p2.user_id
		left join `{credit_type}` as p3 on p1.type_id=p3.id
		where p1.user_id='{$_GET['user_id']}'";
		$result = $mysql->db_fetch_arrays($sql);
		$_A['jifen_result'] = $result;
	}

}
/**
 * VIP审核查看
 **/
elseif ($_A['query_type'] == "vipview"){
	global $mysql,$_G;
	if(isset($_POST['vip_status'])){
		$var = array("vip_status","vip_verify_remark","user_id");
		$data = post_var($var);
		if ($data['vip_status']==1){
			$data['vip_verify_time'] = time();
		}
		$user_id=$_GET['user_id'];
		$data['user_id'] = $user_id;
		//$result = userClass::GetOne($data);
		$result = $mysql->db_select("user_cache","user_id=".$user_id,"vip_status");
		if($result['vip_status']==1){
			$msg = array("vip已经审核通过，请不要重复审核");
		}elseif($result['vip_status']==3){
			$msg = array("对不起，该vip已经审核，审核结果【不通过】，请不要重复审核");
		}elseif($result['vip_status']==0){
			$msg = array("对不起，该用户未申请vip，不能审核");
		}else{
			include_once(ROOT_PATH."/modules/account/account.class.php");
			include_once(ROOT_PATH."/modules/message/message.class.php");
			require_once("modules/credit/credit.class.php");
			if ($data['vip_status']==1){
				//扣除vip的会员费。
				$mysql->db_query("start transaction");
				$result = userClass::UpdateUserCache($data);//更新
				$a = accountClass::AccountVip(array("user_id"=>$user_id,"from"=>"view"));
				if($a==true){
					$mysql->db_query("commit");
				
				$credit['nid'] = "vip";
				$credit['user_id'] = $user_id;
				$credit['value'] = 8;//VIP审核通过加8分
				$credit['op_user'] = "0";
				$credit['op'] = 1;//增加
				$credit['remark'] = "vip审核通过";
				creditClass::UpdateCredit($credit);//更新积分

				$message['sent_user'] = 0;
				$message['receive_user'] = $user_id;
				$message['name'] = "VIP审核通过";
				$message['content'] = "您的vip在".date("Y-m-d",time())."通过审核。";
				$message['type'] = "system";
				$message['status'] = 0;
				messageClass::Add($message);//发送短消息
				$msg = array("VIP用户审核成功","","{$_A['query_url']}/vip&type=1&a=attestation");

				//提醒设置
				$remind['nid'] = "vip_yes";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $user_id;
				$remind['title'] = "恭喜您,您的VIP会员申请已经“通过”了审核";
				$remind['content'] = "恭喜您,您的VIP会员申请已经在".date("Y-m-d",time())."通过审核。";
				$remind['type'] = "system";
				remindClass::sendRemind($remind);
				}else{
					$mysql->db_query("rollback");
					$msg = array("VIP用户审核失败","","{$_A['query_url']}/vip&type=2&a=attestation");
				}
			}elseif($data['vip_status']==3){
				$result = userClass::UpdateUserCache($data);//更新
				$sql = "select p1.vip_money from `{user_cache}` as p1 where p1.user_id = {$user_id}";
				$result = $mysql->db_fetch_array($sql);
				$vip_money = $result['vip_money'];
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$user_id));
				$vip_log['user_id'] = $user_id;
				$vip_log['type'] = "vip4";
				$vip_log['money'] = $vip_money;
				$vip_log['total'] = $account_result['total'];
				$vip_log['use_money'] = $account_result['use_money']+$vip_log['money'];
				$vip_log['no_use_money'] = $account_result['no_use_money']-$vip_log['money'];
				$vip_log['collection'] = $account_result['collection'];
				$vip_log['to_user'] = "0";
				$vip_log['remark'] = "vip申请没有通过，解除冻结资金";
				accountClass::AddLog($vip_log);
				//提醒设置
				$remind['nid'] = "vip_no";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $user_id;
				$remind['title'] = "很遗憾,您的VIP会员申请“没有通过”审核";
				$remind['content'] = "很遗憾，您的VIP会员申请在".date("Y-m-d",time())."没有通过审核。";
				$remind['type'] = "system";
				remindClass::sendRemind($remind);
				$msg = array("审核成功【用户的VIP用户申请不通过】","","{$_A['query_url']}/vip&a=attestation");
			}
		}
		$user->add_log($_log,$result);//记录操作
	}else{
		$_A['user_result'] = userClass::GetOne(array("user_id"=>$_GET['user_id']));
	}
}
elseif ($_A['query_type'] == "wait"){
	$result = $user->wait_list();
	if (is_array($result)){
		$pages->set_data($result);
		$_A['wait_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作","",$url);
}
?>