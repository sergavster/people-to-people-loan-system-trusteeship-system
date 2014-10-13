<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("userinfo_".$_A['query_type']);//检查权限

include_once("userinfo.class.php");

//liukun add for bug 52 begin
$firePHPEnable=TRUE;
if ($firePHPEnable){
	require_once('modules/FirePHPCore/FirePHP.class.php');
	require_once('modules/FirePHPCore/fb.php');
	ob_start();

	$firephp = FirePHP::getInstance(true);
}
//liukun add for bug 52 end

$_A['list_purview'] =  array("userinfo"=>array("用户信息管理"=>array("userinfo_list"=>"信息列表","userinfo_new"=>"添加信息","userinfo_edit"=>"编辑信息","userinfo_del"=>"删除信息","userinfo_view"=>"查看信息")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['admin_url']}&q=module/userinfo{$_A['site_url']}'>用户信息管理</a> - <a href='{$_A['admin_url']}&q=module/user{$_A['site_url']}'>用户管理</a> - <a href='{$_A['admin_url']}&q=module/user/new{$_A['site_url']}'>添加用户</a> - <a href='{$_A['admin_url']}&&q=module/credit{$_A['site_url']}'>积分管理</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=2{$_A['site_url']}'>工薪阶层资料配置</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=1{$_A['site_url']}'>私营业主资料配置</a>";
/**
 * 如果类型为空的话则显示所有的文件列表
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "信息列表";
	if (isset($_GET['username'])){
		$data['username'] = $_GET['username'];
	}
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = userinfoClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['userinfo_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	
	}else{
		$msg = array($result);
	}
}

/*
 * 担保人资料
*/
elseif ($_A['query_type'] == "vouch_userinfo"){
	$user_id = $_GET['vouch_userid'];
	if($user_id>0){
		$sql = "select p1.*,p2.* from `{userinfo}` p1 left join `{user}` p2 on p1.user_id=p2.user_id where p1.user_id=$user_id";
		$_A['userinfo_result'] = $mysql->db_fetch_array($sql);
		if($_A['userinfo_result']['user_id']>0){
			//工作证明two_work
			//收入证明two_income
			//个人信用记录报告two_credit
			//中小企业信用报告two_qycredit
			//营业执照、组织机构代码证、税务登记证、开户许可证、贷款卡（中小企业）two_rests
			//出资证明/验资报告two_contributive
			//公司章程two_constitution
			//经营场所图片two_operate
			//个人和对公流水（3张以内主要企业资金结算账户近6个月流水）two_stream
			//上缴税费凭证（连续6个月）two_revenue
			//董事会/股东会签字样本two_sign
			//股东会或董事会同意保证的决议two_resolution
			if ($_A['userinfo_result']['user_type']==2){
				$sql = "select type_id,nid from `{attestation_type}` p1 where nid in('two_work','two_income','two_credit')";
			}else{
				$sql = "select type_id,nid from `{attestation_type}` p1 where nid in('two_qycredit','two_rests','two_contributive','two_constitution','two_operate','two_stream','two_revenue','two_sign','two_resolution')";
			}
			$re = $mysql->db_fetch_arrays($sql);
			$count = count($re,0);
			$in = "";
			$array = array();
			for ($i=0; $i<$count; $i++){
				$in .= $in==""?$re[$i]['type_id']:','.$re[$i]['type_id'];
				$array[$re[$i]['type_id']]=$re[$i]['nid'];
			}
			if($in!=""){
				$sql = "select p1.*,p2.name,p2.pid from `{attestation}` p1 left join
				`{attestation_type}` p2 on p1.type_id=p2.type_id where p2.pid in ($in) and p1.user_id={$_A['userinfo_result']['user_id']}";
				$result = $mysql->db_fetch_arrays($sql);
				if(count($result,0)>0){
					foreach ($result as $key=>$value){
						if(isset($array[$value['pid']])){
							$_A[$array[$value['pid']]][] = $value;
						}
					}
				}
			}
		}
	}
	$magic->assign("_A",$_A);
	$magic->display("vouch_userinfo.html");
	exit();
}

/**
 * 用户资料修改
**/
elseif ($_A['query_type'] == "new"){
	$_A['list_title'] = "管理信息";
	//读取用户id的信息
	if ($_A['query_type'] == "new" && $_GET['id']=="" && isset($_POST['user_id'])){
		if(isset($_POST['user_id']) && $_POST['user_id']!=""){
			$data['user_id'] = $_POST['user_id'];
			$result = userClass::GetOne($data);
		}elseif(isset($_POST['username']) && $_POST['username']!=""){
			$data['username'] = $_POST['username'];
			$result = userClass::GetOne($data);
		}
		if ($result==false){
			$msg = array("找不到此用户");
		}else{
			echo "<script>location.href='".$_A['query_url']."/new&id={$result['user_id']}&a={$_GET['a']}'</script>";
		}
	}elseif($_GET['id']!="" && !isset($_POST['user_id'])){
		$data['user_id'] = $_GET['id'];
		$result = userClass::GetOne($data);
		if ($result==false){
			$msg = array("您的输入有误","",$_A['query_url']);
		}else{
			$result = userinfoClass::GetOne($data);
			$_A['userinfo_result'] = $result;
		}
	}elseif (isset($_POST['user_id'])){
		$var = array("user_id","marry","child","education","income","shebao","shebaoid","housing","car","late","house_address","house_area","house_year","house_status","house_holder1","house_holder2","house_right1","house_right2","house_loanyear","house_loanprice","house_balance","house_bank","company_name","company_type","company_industry","company_office","company_jibie","company_worktime1","company_worktime2","company_workyear","company_tel","company_address","company_weburl","company_reamrk","private_type","private_date","private_place","private_rent","private_term","private_taxid","private_commerceid","private_income","private_employee","finance_repayment","finance_property","finance_amount","finance_car","finance_caramount","finance_creditcard","mate_name","mate_salary","mate_phone","mate_tel","mate_type","mate_office","mate_address","mate_income","education_record","education_school","education_study","education_time1","education_time2","tel","phone","post","address","province","city","area","linkman1","relation1","tel1","phone1","linkman2","relation2","tel2","phone2","linkman3","relation3","tel3","phone3","msn","qq","wangwang","ability","interest","others","experience");
		$data = post_var($var);
		$result = userinfoClass::GetOne(array("user_id"=>$_POST['user_id']));
		if ($result == "false"){
			$result = userinfoClass::Add($data);
		}else{
			$result = userinfoClass::Update($data);
		}
		if ($result !== true){
			$msg = array($result);
		}else{
			$msg = array("操作成功");
		}
		$user->add_log($_log,$result);//记录操作
	}elseif($_A['query_type'] == "edit"){
		$data['id'] = $_GET['id'];
		$result = userinfoClass::GetOne($data);
		if (is_array($result)){
			$_A['userinfo_result'] = $result;
		}else{
			$msg = array($result);
		}
	}else{
		$msg = array("该用户未提交任何资料");
	}
}
/*
 * 查看可使用验证码
 */
elseif($_A['query_type']=="code"){
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	if(isset($_GET['itype'])){
		$data['itype'] = $_GET['itype'];
	}
	if(isset($_GET['isuse'])){
		$data['isuse'] = $_GET['isuse'];
	}
	if(isset($_GET['iscanuse'])){
		$data['iscanuse'] = $_GET['iscanuse'];
	}
	if(isset($_GET['username'])){
		$data['username'] = $_GET['username'];
	}
	if(isset($_GET['phone'])){
		$data['phone'] = $_GET['phone'];
	}
	$result = userinfoClass::getCode($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['code_result'] = $result['list'];
		$magic->assign("time",time());
		$_A['showpage'] = $pages->show(3);
	}
}
/*
 * 发送站内信
 */
elseif($_A['query_type']=="send_message"){
	if(isset($_POST['title'])){
		$title = $_POST['title'];
		$content = $_POST['content'];
		$username = $_POST['username'];
		if($username==""){
			$msg = array("用户名不能为空");
		}elseif($title==""){
			$msg = array("标题不能为空");
		}elseif($content==""){
			$msg = array("内容不能为空");
		}else{
			$username = explode(";", $username);
			$in = "";
			for($i=0; $i<count($username); $i++){
				$in .= $in==""?"'".$username[$i]."'":",'".$username[$i]."'";
			}
			$sql = "select user_id from `{user}` where username in($in)";
			$result = $mysql->db_fetch_arrays($sql);
			if(count($result,0)!=count($username)){
				$msg = array("用户不存在");
			}else{
				require_once ROOT_PATH.'modules/message/message.class.php';
				foreach($result as $value){
					$remind['sent_user'] = "0";
					$remind['receive_user'] = $value['user_id'];
					$remind['name'] = $title;
					$remind['content'] = $content;
					$remind['type'] = "system";
					$remind['status'] = 0;
					$re = messageClass::Add($remind);
				}
				$msg = array("发送成功");
			}
		}
	}
}
/*
 * 发送短信
 */
elseif($_A['query_type']=="send_phone"){
	if(isset($_POST['phone'])){
		if($_POST['phone']=="" && is_numeric($_POST['phone'])){
			$msg = array("手机号码不能为空");
		}elseif($_POST['content']==""){
			$msg = array("内容不能为空");
		}else{
			$re = sendSMS(0,$_POST['content'],1,$_POST['phone']);
			if($re==false){
				$msg = array("发送失败");
			}else{
				$msg = array("发送成功");
			}
		}
	}
}
/*
 * 发送邮件
 */
elseif($_A['query_type']=="send_email"){
	if(isset($_POST['username'])){
		$title = $_POST['title'];
		$content = $_POST['content'];
		$username = $_POST['username'];
		if($username==""){
			$msg = array("用户名不能为空");
		}elseif($title==""){
			$msg = array("标题不能为空");
		}elseif($content==""){
			$msg = array("内容不能为空");
		}else{
			$username = explode(";", $username);
			$in = "";
			for($i=0; $i<count($username); $i++){
				$in .= $in==""?"'".$username[$i]."'":",'".$username[$i]."'";
			}
			$sql = "select user_id,email from `{user}` where username in($in)";
			$result = $mysql->db_fetch_arrays($sql);
			if(count($result,0)!=count($username)){
				$msg = array("用户不存在");
			}else{
				foreach($result as $value){
					$remail['user_id'] = $value['user_id'];
					$remail['email'] = $value['email'];
					$remail['title'] = $title;
					$remail['msg'] =  $content;
					$remail['type'] =  "system";
					$re = $user->SendEmail($remail);
					if($re==false){
						$msg = array("发送失败");
					}else{
						$msg = array("发送成功");
					}
				}
			}
		}
	}
}
/**
 * 删除
**/
/*
elseif ($_A['query_type'] == "del"){
	$data['id'] = $_REQUEST['id'];
	$result = userinfoClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功");
	}
	$user->add_log($_log,$result);//记录操作
}
*/

/*
 * 阶层资料配置
*/
elseif($_A['query_type']=="infoconf"){
	check_rank("userinfo_infoconf");//检查权限
	if (isset($_POST['usertype'])){
			$usertype = $_POST['usertype'];
			$data['idnumber'] =(isset($_POST['idnumber']))?isset($_POST['idnumber']):0;
			$data['realname'] =(isset($_POST['realname']))?isset($_POST['realname']):0;
			$data['companyname'] =(isset($_POST['companyname']))?isset($_POST['companyname']):0;
			$data['companyxz'] =(isset($_POST['companyxz']))?isset($_POST['companyxz']):0;
			$data['gsqc'] =(isset($_POST['gsqc']))?isset($_POST['gsqc']):0;
			$data['qylx'] =(isset($_POST['qylx']))?isset($_POST['qylx']):0;

			unset($_POST['usertype']);
			
			$conflist = json_encode($_POST);
			
			$sql = "update  `{userinfo_conf}` set conflist='{$conflist}' where `id` = {$usertype}";
			$result = $mysql->db_query($sql);
			
			if ($result !== true){
				$msg = array($result);
			}else{
				$msg = array("操作成功");
			}
			
			$user->add_log($_log,$result);//记录操作
		
	}else{
		$usertype = $_GET['usertype'];
		if($usertype==1){
			$_A['site_result']['name'] = "私营业主资料配置";
		}elseif ($usertype==2){
			$_A['site_result']['name'] = "工薪阶层资料配置 ";
		}else{
			$_A['site_result']['name'] = "私营业主资料配置";
			$usertype = 1;
		}
		$sql = "select * from `{userinfo_conf}` where `id` = {$usertype}";
		$result = $mysql->db_fetch_array($sql);
		$_A['infoconf_result'] = json_decode($result['conflist'], true);
	}
}

//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作");
}
?>