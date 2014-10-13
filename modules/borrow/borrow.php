<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
$arrnocheck=array("biaoTJ","borrow_user_attestation","image_downloaded","sendsmsprotocol","user_attestation");
if (in_array($_A['query_type'], $arrnocheck)){
	//不限权限
}else{
	check_rank("borrow_".$_A['query_type']);//检查权限
}

include_once("borrow.class.php");

$_A['list_purview'] =  array("borrow"=>array("借款管理"=>array("borrow_list"=>"借款列表",
"borrow_new"=>"添加借款",
"borrow_edit"=>"编辑借款",
"borrow_amount"=>"借款额度",
"borrow_amount_view"=>"额度管理",
"borrow_del"=>"删除借款",
"borrow_view"=>"审核借款",
"borrow_full"=>"满标列表",
"borrow_repayment"=>"已还款",
"borrow_liubiao"=>"流标",
"borrow_late"=>"逾期",
"borrow_full_view"=>"满标查看")));
//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>所有借款</a> - <a href='{$_A['query_url']}&status=0{$_A['site_url']}'>发标待审</a> -  <a href='{$_A['query_url']}&status=1{$_A['site_url']}'>正在招标的借款</a> -  <a href='{$_A['query_url']}/full&status=1{$_A['site_url']}'>满标复审</a> - <a href='{$_A['query_url']}/repayment{$_A['site_url']}&status=1'>已还款借款</a>  -  <a href='{$_A['query_url']}/liubiao{$_A['site_url']}'>流标</a>  - <a href='{$_A['query_url']}/late{$_A['site_url']}'>当前逾期借款</a> - <a href='{$_A['query_url']}/lateFast{$_A['site_url']}'>即将到期借款</a>  - <a href='{$_A['query_url']}/tongji{$_A['site_url']}'>贷款统计</a>";

if ($_A['query_type'] == "list"){
	$_A['list_title'] = "信息列表";
	if (isset($_POST['id']) && $_POST['id']!=""){
		$data['id'] = $_POST['id'];
		$data['flag'] = $_POST['flag'];
		$data['view'] = $_POST['view'];
		$result = borrowClass::Action($data);
		if ($result==true){
			$msg = array("修改成功","",$_A['query_url'].$_A['site_url']);
		}else{
			$msg = array("修改失败，请跟管理员联系");
		}
	
	}else{
		if (isset($_GET['user_id'])){
			$data['user_id'] = $_GET['user_id'];
		}
		if (isset($_GET['status']) && $_GET['status']!=""){
			$data['status'] = $_GET['status'];
		}
		if (isset($_GET['biaoType']) && $_GET['biaoType']!=""){
			$data['biaoType'] = $_GET['biaoType'];
		}
		if (isset($_GET['username'])){
			$data['username'] = $_GET['username'];
		}
		if(isset($_GET['dotime1'])){
			$data['dotime1']=$_GET['dotime1'];
		}
		if(isset($_GET['dotime2'])){
			$data['dotime2']=$_GET['dotime2'];
		}
		if (isset($_GET['type']) && $_GET['type']=="excel"){
			$title = array("序号","用户名称","标种","借款标题","借款金额","利率（%）","借款期限","发布时间","状态");
			$data['limit'] = "all";
			$result = borrowClass::GetList($data);
			borrowClass::borrowListForExcel(array('type'=>'borrowlist','title'=>$title,'excelresult'=>$result));
			exit;
		}
		$data['page'] = $_A['page'];
		$data['epage'] = $_A['epage'];
		//$result = borrowClass::GetList($data);
		$result = borrowClass::GetListAdmin($data);
		if (is_array($result)){
			$pages->set_data($result);
			$_A['borrow_list'] = $result['list'];
			$_A['showpage'] = $pages->show(3);
		}else{
			$msg = array($result);
		}
	}
}
/**
 * 额度管理
**/
elseif ($_A['query_type'] == "amount"){
	check_rank("borrow_amount");//检查权限
	$_A['list_title'] = "额度管理";
	if (isset($_GET['user_id']) && $_GET['user_id']!=""){
		$data['user_id'] = $_GET['user_id'];
	}
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	if (isset($_GET['type']) && $_GET['type']!=""){
		$data['type'] = $_GET['type'];
	}
    if (isset($_GET['status']) && $_GET['status']!=""){
		$data['status'] = $_GET['status'];
	}	
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$result = borrowClass::GetAmountApplyList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['borrow_amount_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
/**
 * 额度管理
**/
elseif ($_A['query_type'] == "amount_view"){
	check_rank("borrow_amount_view");//检查权限
	$data['id'] = $_GET['id'];
	$result = borrowClass::GetAmountApplyOne($data);
	if (isset($_POST['status'])){
		if($_POST['valicode']!=$_SESSION['valicode'] || $_POST['valicode']==''){
			$msg = array("验证码输入有误");
		}else{
			unset($_SESSION['valicode']);
			$data['user_id'] = $result['user_id'];
			$data['status'] = $_POST['status'];
			$data['type'] = $_POST['type'];
			$data['account'] = $_POST['account'];
			$data['verify_remark'] = $_POST['verify_remark'].'操作管理员ID:'.$_G['user_id'];
			$result = borrowClass::CheckAmountApply($data);
			if ($result !=1){
				$msg = array($result);
			}else{
				$msg = array("操作成功","",$_A['query_url']."/amount&a=borrow");
			}
			$user->add_log($_log,$result);//记录操作
		}
	}else{
		if(is_array($result)){
			if($result['credit_file']!=''){
				$result['credit_file'] = explode('|', $result['credit_file']);
			}
			$_A['borrow_amount_result'] = $result;
		}else{
			$msg = array($result);
		}	
	}
}
/**
 * 添加
**/

elseif ($_A['query_type'] == "new"  || $_A['query_type'] == "edit" ){
	check_rank("borrow_new");//检查权限
	$_A['list_title'] = "管理信息";
	//读取用户id的信息
	if (isset($_REQUEST['user_id']) && isset($_POST['username'])){
		if(isset($_POST['user_id']) && $_POST['user_id']!=""){
			$data['user_id'] = $_POST['user_id'];
			$result = userClass::GetOne($data);
		}elseif(isset($_POST['username']) && $_POST['username']!=""){
			$data['username'] = $_POST['username'];
			$result = userClass::GetOne($data);
		}
		if ($result==false){
			$msg = array("找不到此用户");
		}elseif($result['is_tgAccount']!=1){
			$msg = array("用户未绑定宝付账户，清晰绑定");
		}else{
			echo "<script>location.href='".$_A['query_url']."/new&a=borrow&user_id={$result['user_id']}'</script>";
		}
	}
	elseif (isset($_POST['name'])){
		$var = array("user_id","name","use","time_limit","style","account","apr","lowest_account","most_account","valid_time","award","part_account","funds","is_false","content","isday","time_limit_day","biao_type","is_tuijian","dsfdb","user_pro");
		$data = post_var($var);
		if (($_POST['status']!=0 || $_POST['status']!=-1) && $_A['query_type'] == "edit"){
			$msg = array("此标已经在招标或者已经完成，不能修改","",$_A['query_url'].$_A['site_url']);
		}else{
			$a = borrowClass::InterestYearAndMonth($data);
			$data['year_interest'] = $a['year_interest'];
			$data['month_interest'] = $a['month_interest'];
			unset($a);
			switch($data['biao_type']){
				case 'lz':
					$data['is_lz'] = 1;
					break;
				case 'fast':
					$data['is_fast'] = 1;
					break;
				case 'miao':
					$data['is_mb'] = 1;
					break;
				case 'jin':
					$data['is_jin'] = 1;
					break;
				case 'xin':
					$data['is_xin'] = 1;
					break;

			}
			if ($_A['query_type'] == "new"){
				$data['status']= 0;
				$data['p_contract_no']= 'jk'.time();
				$result = borrowClass::Add($data);
			}else{
				$data['id'] = $_POST['id'];
				$result = borrowClass::Update($data);
			}
			
			if ($result !== true){
				$msg = array($result);
			}else{
				$msg = array("操作成功","",$_A['query_url'].$_A['site_url']);
			}
		}
		$user->add_log($_log,$result);//记录操作
	}
	elseif ($_A['query_type'] == "edit" ){
		$data['user_id'] = $_REQUEST['user_id'];
		$data['id'] = $_REQUEST['id'];
		$result = borrowClass::GetOne($data);
		if (is_array($result)){
			$_A['borrow_result'] = $result;
		}else{
			$msg = array($result);
		}
		
	}
	elseif(isset($_REQUEST['user_id']) && !isset($_POST['username'])){
		$data['user_id'] = $_REQUEST['user_id'];
		$result = userClass::GetOne($data);
		if ($result==false){
			$msg = array("您的输入有误","",$_A['query_url']);
		}else{
			$_A['user_result'] = $result;
			//$result = borrowClass::GetOne($data);
			//$_A['borrow_result'] = $result;
		}
	}
}

/**
 * 查看
**/
elseif ($_A['query_type'] == "view"){
	check_rank("borrow_view");//进入权限
	$centre_remark = check_rank_bool("borrow_centre_remark");//初审权限
	$committee_remark = check_rank_bool("borrow_committee_remark");//风险委员会权限
	$verify_remark = check_rank_bool("borrow_verify_remark");//综合意见权限
	$_A['check_rank'] = array('centre_remark'=>$centre_remark,'committee_remark'=>$committee_remark,'verify_remark'=>$verify_remark);
	$_A['list_title'] = "借款标审核";
	
	if(isset($_POST['id']) && $verify_remark===true && $_POST['status']==1){
		
		
		$b['id'] = $_GET['id'];
		$b['user_id'] = $_GET['user_id'];
		$a = borrowClass::GetOne($b);
		$a['merOrderNum'] = $a['p_contract_no'].'_'.rand(001,999);
		$a = tg_addborrow($a);
		if($a===true){
			$msg = '';
		}else{
			$msg = array($a);
		}
	}
	if(is_array($msg)){

	}
	elseif (isset($_POST['id']) && $verify_remark===true){
		$var = array("id","status","verify_remark","show_attestation","content","zjyz","fxkzcs","zcjscfx","qybj","qyxx","credit_grade","borrow_rzlx");
		$data = post_var($var);
		if(isset($_POST['centre_remark'])){
			$data['centre_remark'] = htmlspecialchars($_POST['centre_remark']);
			$data['centre_remark_user'] = $_G['user_id'];
			$data['centre_remark_time'] = time();
		}
		if(isset($_POST['committee_remark'])){
			$data['committee_remark'] = htmlspecialchars($_POST['committee_remark']);
			$data['committee_remark_user'] = $_G['user_id'];
			$data['committee_remark_time'] = time();
		}
		$data['verify_user'] = $_G['user_id'];
		$data['verify_time'] = time();
		$result = borrowClass::Verify($data);
		if ($result==false){
			$msg = array("审核失败");
		}else{
			//添加用户的动态
			$brsql="select * from `{borrow}` where id ='".$_POST['id']."'";
			$br_row = $mysql->db_fetch_array($brsql);
			if($data['status']==1){
				//自动投标
				$auto['id']=$br_row['id'];
				$auto['user_id']=$br_row['user_id'];
				$auto['total_jie']=$br_row['account'];
				$auto['zuishao_jie']=$br_row['lowest_account'];
				borrowClass::auto_borrow($auto);

				$_data['user_id'] = $_POST['user_id'];
				$_data['content'] = "成功发布了\"<a href=\'/invest/a{$data['id']}.html\' target=\'_blank\'>{$_POST['name']}</a>\"借款标";
				$result = userClass::AddUserTrend($_data);
			}elseif ($data['status']==2){
				if($br_row['is_mb'] == 1){
					require_once ROOT_PATH.'/modules/borrow/biao/miaobiao.class.php';
					$data_1['user_id']=$br_row['user_id'];
					$data_1['apr']=$br_row['apr'];
					$data_1['account']=$br_row['account'];
					$data_1['id']=$br_row['id'];
					$data_1['name']=$br_row['name'];
					$miaobiao = new miaobiaoClass();
					$miaobiao->cancel($data_1);
				}
			}
			$msg = array("审核操作成功","",$_A['query_url'].$_A['site_url']."&a=borrow");
			//通知发标人，借款审核成功
			if ($data['status']==1){
				//sendSMS($_POST['user_id'],"您的借款标{$_POST['name']}已经成功发布。",1);
			}
		}
		$user->add_log($_log,$result);//记录操作
	}elseif(isset($_POST['id']) && (isset($_POST['centre_remark']) || isset($_POST['committee_remark']))){//风控中心意见
		if(isset($_POST['centre_remark'])){
			$data['centre_remark'] = htmlspecialchars($_POST['centre_remark']);
			$data['centre_remark_user'] = $_G['user_id'];
			$data['centre_remark_time'] = time();
		}
		if(isset($_POST['committee_remark'])){
			$data['committee_remark'] = htmlspecialchars($_POST['committee_remark']);
			$data['committee_remark_user'] = $_G['user_id'];
			$data['committee_remark_time'] = time();
		}
		$data['id'] = $_POST['id'];
		$data['show_attestation'] = $_POST['show_attestation'];
		$result = borrowClass::Verify($data);
		if ($result==false){
			$msg = array("操作失败");
		}else{
			$msg = array("操作成功");
		}
	}else{
		$data['id'] = $_GET['id'];
		$data['user_id'] = $_GET['user_id'];
		if($data['id']=="" || $data['user_id']==""){
			$msg = array("您的操作有误！");
		}else{
			$_A['borrow_result'] = borrowClass::GetOne($data);

			//用户缺少的资料
			/*$re = userClass::GetAttestation(array('user_id'=>$_A['borrow_result']['user_id']));
			if($re!=''){
				$_A['user_attestation'] = $re;
			}*/
			//是否是担保标
			if($_A['borrow_result']['vouch_user']!=""){
				$vouch_user = explode("|", $_A['borrow_result']['vouch_user']);
				$u = "";
				foreach($vouch_user as $v){
					$u .= $u==""?"'".$v."'":",'".$v."'";
				}
				if($u!=""){
					$result = $mysql->db_fetch_arrays("select user_id,username from `{user}` where username in ({$u})");
				}else{
					$result = "";
				}
				$_A['borrow_result']['vouch_user']=$result;
			}
			//用户上传的其他资料
			$_A['borrow_shus_result'] = $mysql->db_fetch_arrays("select * from {attestation} where user_id={$data['user_id']} and borrow_id={$data['id']} and upload_type=4");
			//判断是否需要风险委员会意见
			$classname = $_A['borrow_result']['biao_type']."biaoClass";
			$dynaBiaoClass = new $classname();
			$re = $dynaBiaoClass->get_biaotype_info();
			if($re['gt_money_committee']<=$_A['borrow_result']['account']){
				$_A['is_committee_remark']=1;
			}else{
				$_A['is_committee_remark']=0;
			}
		}
	}
}
/*
 * 上传标中需要显示的图片
 */
elseif($_A['query_type']=="borrow_attestation"){
	$borrowid = intval($_GET['borrow_id']);
	$type_arr = array('xgxmzl','dyzl','bxbzzl');
	$type = $_GET['zl_type'];
	if(!in_array($type,$type_arr)){
		exit();
	}
	if(isset($_FILES) && !empty($_FILES)){
		$_G['upimg']['user_id'] = $_G['user_id'];
		$_G['upimg']['file'] = "admin_up";
		$_G['upimg']['cut_status'] = 0;
		$_G['upimg']['code'] = $type;
		$pic_result = $upload->upfile($_G['upimg']);
		if ($pic_result!=""){
			$sql = 'select '.$type.' from {borrow} where id='.$borrowid;
			$re = $mysql->db_fetch_array($sql);
			if($re[$type]!=''){
				$re = unserialize($re[$type]);
			}else{
				$re = array();
			}
			$i = count($re);
			$re[$i] = array('name'=>$_POST['filename'],'litpic'=>$pic_result['filename'],'id'=>$i);
			$re = serialize($re);
			$mysql->db_query("update {borrow} set {$type}='{$re}' where id={$borrowid} limit 1");
		}
	}
	$re = $mysql->db_fetch_array('select '.$type.',status from {borrow} where id='.$borrowid);
	$_A['borrow_status']=$re['status'];
	if($re[$type]==''){
		$re = array();
	}else{
		$re = unserialize($re[$type]);
	}
	$_A['attestation_list'] = $re;
	$template = "borrow_attestation.html";
	$magic->assign("_A",$_A);
	$magic->display($template);
	exit();
}
/*
 * 上传标中需要显示的图片
 */
elseif($_A['query_type']=="borrow_user_attestation"){
	if(isset($_FILES) && !empty($_FILES)){
		$_G['upimg']['user_id'] = $_G['user_id'];
		$_G['upimg']['file'] = "admin_up";
		$_G['upimg']['cut_status'] = 0;
		$_G['upimg']['code'] = "attestation";
		$pic_result = $upload->upfile($_G['upimg']);
		if ($pic_result!=""){
			$data_1['litpic'] = $pic_result['filename'];//上传的图片
			$data_1['user_id'] = $_POST['user_id'];
			$data_1['borrow_id'] = 0;
			$data_1['upload_type'] = 2;
			$data_1['type_id'] = $_POST['type_id'];
			$data_1['name'] = htmlspecialchars($_POST['filename'],ENT_QUOTES);
			$mysql->db_add("attestation", $data_1);
		}
	}
	if ($_GET['borrow_status']!=0){
		$_A['borrow_status']=1;
		if($_GET['attestation_id']!=""){
			$sql = "select p1.litpic,p2.name from `{attestation}` p1 left join `{attestation_type}` p2 on p1.type_id=p2.type_id  where p1.id in({$_GET['attestation_id']}) and p1.upload_type in(1,2) order by p1.id desc";
			$re = $mysql->db_fetch_arrays($sql);
			$_A['show_attestation'] = $re;
		}
	}else{
		if($_GET['user_id']<1){
			eixt();
		}
		$_A['attestation_user_id'] = $_GET['user_id'];
		$sql = "select p1.litpic,p1.id,p2.name,p2.type_id from `{attestation}` p1 left join `{attestation_type}` p2 on p1.type_id=p2.type_id where p1.user_id=".$_GET['user_id']." and p1.upload_type in(1,2) order by p1.id desc";
		$_A['borrow_user_attestation'] = $mysql->db_fetch_arrays($sql);
		$_A['attestation_type_list'] = $mysql->db_fetch_arrays("select type_id,name from {attestation_type} where pid>0 and status=1");
		$_A['borrow_status']=0;
	}
	$template = "borrow_image.html";
	$magic->assign("_A",$_A);
	$magic->display($template);
	exit();
}
elseif ($_A['query_type']=='user_attestation'){
	if(!isset($_GET['user_type'])) exit();
	if(!isset($_GET['user_id'])) exit();
	$nid = $_GET['user_type']==1?'private':'wageearners';
	$user_id = intval($_GET['user_id']);
	$sql = 'select p1.id,p1.type_id,p2.pid,p2.name as attestation_name,p1.status from `{attestation}` p1 left join `{attestation_type}` p2 on p1.type_id=p2.type_id where p1.user_id='.$user_id.' and p1.upload_type=1';
	$user_u = $mysql->db_fetch_arrays($sql);//用户现有的资料
	$arr_user_up = array();
	foreach($user_u as $key=>$value){
		$arr_user_up[$value['type_id']][] = $value;
	}
	$pre = $mysql->db_fetch_array("select * from `{attestation_type}` where nid='$nid'");
	$result = $mysql->db_fetch_arrays("select * from `{attestation_type}` where type_id in({$pre['include']})");
	$prearr = unserialize($pre['borrow_must']);
	if (is_array($result)){
		$user_result = $mysql->db_fetch_arrays('select marry from {userinfo} where user_id='.$user_id);
		include_once(ROOT_PATH.'modules/attestation/attestation.class.php');
		foreach ($result as $k=>$v){
			if($v['nid']=='tow_report' && $user_result['marry']==3){//未婚的情况
				unset($result[$k]);
				continue;
			}
			if($v['nid']=='tow_certificate' && $user_result['marry']==3){//未婚的情况
				unset($result[$k]);
				continue;
			}
			$select = $prearr[$v['type_id']]['select'];
			unset($prearr[$v['type_id']]['select']);
			$re = attestationClass::GetTypeList(array('pid'=>$v['type_id'],'limit'=>'all'));
			if($re!=""){
				foreach ($re as $k_1=>$v_1){
					if(in_array($v_1['type_id'],$prearr[$v['type_id']])){
						$re[$k_1] = "<span style='color:red'>{$v_1['name']}</span>";
						$result[$k]['you_must'][$k_1]['attestation_name'] = $v_1['name'];
						$result[$k]['you_must'][$k_1]['is_u'] = 0;
						$result[$k]['you_must'][$k_1]['status'] = 0;
						if(array_key_exists($v_1['type_id'], $arr_user_up)){
							$result[$k]['you_must'][$k_1]['is_u'] = 1;
							foreach ($arr_user_up[$v_1['type_id']] as $u_k){
								$result[$k]['you_must'][$k_1]['status'] = $u_k['status'];
								$result[$k]['you_must'][$k_1]['id'] = $u_k['id'];
								if($u_k['status']==1){
									$result[$k]['you_must'][$k_1]['status'] = 1;
								}
							}
						}
					}else{
						$re[$k_1] = $v_1['name'];
						if(array_key_exists($v_1['type_id'], $arr_user_up)){
							$result[$k]['not_must'][] = $arr_user_up[$v_1['type_id']];
						}
					}
				}
				$count = count($re,0);
				if($count>0 && $select>0){
					$x = $count.'选'.$select;
					array_push($re, $x);
				}
				$result[$k]['son'] = $re;
			}
		}
		//$_A['include_type_list'] = $result;
		//$_A['attestation_presult'] = $pre;
		echo '<div id="must_uplode_list" style="text-align:left">';
		foreach($result as $key=>$item){
			echo '<div style="border-bottom:1px solid #cccccc;margin-top:10px">';
			echo '<div style="margin:0 0 5px 0"><strong>'.$item['name'].':</strong>(&nbsp;&nbsp;&nbsp;';
			foreach($item['son'] as $k=>$v){
				echo $v.'&nbsp;&nbsp;&nbsp;';
			}
			echo ')';
			echo '</div>';
			echo '<table style="margin-left:40px">';
			foreach($item['you_must'] as $k=>$v){
				echo '<tr style="height:20px">';
				echo '<td width="200px"><span style="color:red">*</span>'.$v['attestation_name'].'</td>';
				echo '<td width="200px">';
				if($v['is_u']==1){
					echo '已上传';
				}else{
					echo '未上传';
				}
				echo '</td><td>';
				if($v['status']==1){
					echo '已审核通过';
				}elseif($v['status']==2){
					echo '审核未通过';
				}else{
					if($v['is_u']==1){
						echo '未审核<a href="'.$_A['admin_url'].'&q=module/attestation/view&site_id=26&a=attestation&id='.$v['id'].'" target="_blank">(马上审核)</a>';
					}else{
						echo '未审核';
					}
				}
				echo '</td></tr>';
			}
			foreach($item['not_must'] as $k=>$v){
				foreach($v as $kk=>$vv){
					echo '<tr style="height:20px">';
					echo '<td width="200px">'.$vv['attestation_name'].'</td>';
					echo '<td width="200px">已上传</td>';
					echo '<td width="200px">';
					if($vv['status']==1){
						echo '已审核通过';
					}else if($vv['status']==2){
						echo '审核未通过';
					}else{
						echo '未审核<a href="'.$_A['admin_url'].'&q=module/attestation/view&site_id=26&a=attestation&id='.$vv['id'].'" target="_blank">(马上审核)</a>';
					}
					echo '</td></tr>';
				}
			}
			echo '</table></div>';
		}
		echo '</div>';
	}
	exit();
}
/*
 * 下载图片
 */
/*
elseif($_A['query_type']=="image_downloaded"){
	$image = ROOT_PATH.$_GET['imagepath'];
	$hz = strrchr($image, ".");
	$hz = str_replace(".", "", $hz);
	$array = array("jpg","jpeg","png","gjf");
	if(in_array($hz, $array) && file_exists($image)){
		//header('Content-Type: application/image/gif');//输出的类型
		header("Content-Type: application/image/$hz");
		header("Content-Disposition: attachment; filename={$_GET['imagefile']}.$hz"); //下载显示的名字,注意格式
		readfile($image);
	}else{
		exit();
	}
}*/
/**
 * 删除
**/
/*
elseif ($_A['query_type'] == "del"){
	check_rank("borrow_del");//检查权限
	$data['id'] = $_GET['id'];
	$result = borrowClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("删除成功","",$_A['query_url'].$_A['site_url']);
	}
	$user->add_log($_log,$result);//记录操作
}
*/
/**
 * 满标列表
**/
elseif ($_A['query_type'] == "full"){
	check_rank("borrow_full");//检查权限
	$_A['list_title'] = "信息列表";
	$data['page'] = $_A['page'];
	$data['epage'] = $_A['epage'];
	$data['type'] = 'review';
	if (isset($_GET['status']) && $_GET['status']!=""){
		$data['status'] = $_GET['status'];
	}
	if (isset($_GET['biaoType']) && $_GET['biaoType']!=""){
		$data['biaoType'] = $_GET['biaoType'];
	}
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	if($data['status']==1){
		unset($data['status']);
	}
	if($data['status']==3){
		unset($data['type']);
	}
	$result = borrowClass::GetListAdmin($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['borrow_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
/*
 * 流转标停止流转
 */
elseif ($_A['query_type']=="stoplz"){
	check_rank("borrow_stoplz");
	$id = $_GET['id'];
	if($id>0){
		$sql = "update `{borrow}` set valid_time=0 where id=$id";
		$re = $mysql->db_query($sql);
		if($re==false){
			$msg = array("操作失败");
		}else{
			$msg = array("操作成功");
		}
	}else{
		$msg = array("请不要乱操作");
	}
}
/**
 * 借款标撤回
**/
elseif ($_A['query_type'] == "cancel"){
	check_rank("borrow_cancel");//检查权限
	$_A['list_title'] = "撤回";
	if($_POST['valicode']!=$_SESSION['valicode'] || $_POST['valicode']==''){
		$msg = array("验证码输入有误");
	}else{
		unset($_SESSION['valicode']);
		$re = borrowClass::Cancel(array("id"=>$_POST['id']));
		if($re==false){
			$msg = array("撤回失败","",$_A['query_url'].$_A['site_url']);
		}else{
			$msg = array("撤回成功","",$_A['query_url'].$_A['site_url']);
		}
	}
}
/**
 * 已还款借款
**/
elseif($_A['query_type'] == "repayment"){
	check_rank("borrow_repayment");//检查权限
	$_A['list_title'] = "还款信息";
	$data['page'] = $_A['page'];
	$data['epage'] = 10;
	$data['order'] = "repayment_time";
	$data['borrow_status'] = 3;
	if (isset($_GET['status']) && $_GET['status']!=""){
		$data['status'] = $_GET['status'];
	}
	if (isset($_GET['username']) && $_GET['username']!=""){
		$data['username'] = $_GET['username'];
	}
	if (isset($_GET['keywords']) && $_GET['keywords']!=""){
		$data['keywords'] = $_GET['keywords'];
	}
	if (isset($_GET['biaoType']) && $_GET['biaoType']!=""){
		$data['biaoType'] = $_GET['biaoType'];
	}
	if(isset($_GET['dotime1']) && $_GET['dotime1']!=""){
		$data['dotime1'] = $_GET['dotime1'];
	}
	if(isset($_GET['dotime2']) && $_GET['dotime2']!=""){
		$data['dotime2'] = $_GET['dotime2'];
	}
	if ($_GET['type']=="excel"){
			$title = array("序号","借款人","借款标题","期数","到期时间","还款金额","还款利息","还款时间","状态");
			$data['limit'] = "all";
			$result = borrowClass::GetRepaymentList($data);
			borrowClass::borrowListForExcel(array('type'=>'repaymentlist','title'=>$title,'excelresult'=>$result));
			exit;
	}
	$result = borrowClass::GetRepaymentList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['borrow_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
/**
 * 流标列表
**/
elseif ($_A['query_type'] == "liubiao"){
	check_rank("borrow_liubiao");//检查权限
	$_A['list_title'] = "流标";
	$data['page'] = $_A['page'];
	$data['epage'] = 25;
	$data['type'] = "late";
	$data['status'] = "1";
	$result = borrowClass::GetList($data);
	if (is_array($result)){
		$pages->set_data($result);
		$_A['borrow_list'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}else{
		$msg = array($result);
	}
}
/**
 * 流标修改
**/
elseif ($_A['query_type'] == "liubiao_edit"){
	check_rank("borrow_liubiao");//检查权限
	$_A['list_title'] = "流标管理";
	if (isset($_POST['status'])){
		if(strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
			$msg = array("验证码输入有误");
		}else{
			unset($_SESSION['valicode']);
			$data['days'] = $_POST['days'];
			$data['id'] = $_POST['id'];
			$data['status'] = $_POST['status'];
			$result = borrowClass::ActionLiubiao($data);
			if($result==true){
				$msg = array("操作成功","",$_A['query_url']."/liubiao".$_A['site_url']);
			}else{
				$msg = array("操作失败");
			}
		}
	}else{
		$data['id'] = $_GET['id'];
		$result = borrowClass::GetOne($data);
		$_A['borrow_result'] = $result;
	}
}
/**
 * 满标复审
**/
elseif ($_A['query_type'] == "full_view"){
	global $mysql;
	check_rank("borrow_full_view");//检查权限
	$_A['list_title'] = "满标处理";
	if(!isset($_POST['id']) && !isset($_GET['id'])){
		$msg = array("操作有误");
	}elseif (isset($_POST['id'])){
		if($_SESSION['valicode']!=strtolower($_POST['valicode']) || $_POST['valicode']==''){
			$msg = array("验证码不正确");
		}else{
			unset($_SESSION['valicode']);
			$var = array("id","status","repayment_remark");
			$data = post_var($var);
			$data['repayment_user'] = $_G['user_id'];
            $data['verify_time'] = time();         
            $sql = "select status from {borrow}  where id=".$_POST['id'];
            $resultBorrow = $mysql->db_fetch_array($sql);
            if($resultBorrow['status']==3 && $resultBorrow['status']==4){
                $msg = array("此标已经审核过或正在审核处理中，不能重复审核");
            }else{
                $result = borrowClass::AddRepayment($data);
                if(is_bool($result)){
                	if ($result ==false){
                		$msg = array("操作失败");
                	}else{
                		$msg = array("操作成功","",$_A['query_url']."/full".$_A['site_url']);
                	}
                }else{
                	$msg = array($result);
                }
           	}
           	$user->add_log($_log,$result);//记录操作
		}
	}else{
		$data['id'] = $_GET['id'];
		$_A['borrow_result'] = borrowClass::GetOne($data);
		if ($_A['borrow_result']['status']==1 && ($_A['borrow_result']['is_lz']!=1 || ($_A['borrow_result']['is_lz']==1 && $_A['borrow_result']['account_yes']==0))){
			$data['borrow_id'] = $data['id'];
			$data['page'] = $_A['page'];
			$data['epage'] = $_A['epage'];
			$result = borrowClass::GetTenderList($data);
			$_A['borrow_repayment'] = borrowClass::GetRepayment(array("id"=>$data['id']));
			$protocol = borrowClass::GetBorrowProtocol(array('borrow_id'=>$_GET['id']));
			$_A['borrow_protocol'] = $protocol;
			if (is_array($result)){
				$pages->set_data($result);
				$_A['borrow_tender_list'] = $result['list'];
				$_A['showpage'] = $pages->show(3);
			}else{
				$msg = array($result);
			}
		}else{
			$msg = array("当前借款标用户已经撤回 或 您的操作有误, 请刷新本页面");
		}
	}
}
/*
 * 发送短信通知用户补齐相应材料
 */
elseif ($_A['query_type']=="sendsmsprotocol"){
	$borrow_id = intval($_POST['borrow_id']);
	if ($borrow_id<1){
		echo "没有找到相应的借款标";
	}else{
		$borrow_re = $mysql->db_fetch_array("select user_id,name from {borrow} where id={$borrow_id}");
		if($borrow_re==false){
			echo "没有找到相应的借款标";
		}else{
			$re = borrowClass::GetBorrowProtocol(array('borrow_id'=>$borrow_id));
			$str = "";
			foreach ($re as $v){
				$str .= $str==""?$v['name']:','.$v['name'];
			}
			if($str!=""){
				$str = '您的借款标'.$borrow_re['name'].'已经满标，请尽快提供复审材料：'.$str;
				$re = sendSMS($borrow_re['user_id'],$str,1);
				if ($re==false){
					echo "发送失败";
				}else{
					echo "发送成功";
				}
			}
		}
	}
	eixt();
}
/**
 * 逾期还款
**/
elseif ($_A['query_type'] == "late"){
	check_rank("borrow_late");//检查权限
	$_A['list_title'] = "逾期还款";
	if (isset($_POST['id'])){
		/*
		if($_SESSION['valicode']!=$_POST['valicode']){
			$msg = array("验证码不正确");
		}else{
			$_SESSION['valicode'] = "";
			$var = array("id","status","repayment_remark");
			$data = post_var($var);
			$data['repayment_user'] = $_G['user_id'];
			$result = borrowClass::AddRepayment($data);
			if ($result ==false){
				$msg = array($result);
			}else{
				$msg = array("操作成功","",$_A['query_url']."/full".$_A['site_url']);
			}
		}
		$user->add_log($_log,$result);//记录操作
		*/
	}else{
		$data['page'] = $_A['page'];
		$data['epage'] = $_A['epage'];
		$data['status'] = "0,2";
		$data['repayment_time'] = time();
		if(isset($_GET['username'])){
			$data['username'] = $_GET['username'];
		}
		if(isset($_GET['status'])){
			$data['status'] = $_GET['status'];
		}
		if ($_GET['type']=="excel"){
			$title = array("借款编号","借款人","借款标题","期数","到期时间","应还本息","预期天数","罚金","状态");
			$data['limit'] = "all";
			$result = borrowClass::GetRepaymentList($data);
			borrowClass::borrowListForExcel(array('type'=>'late','title'=>$title,'excelresult'=>$result));
			exit;
		}
		$result = borrowClass::GetRepaymentList($data);
		if (is_array($result)){
			$pages->set_data($result);
			$_A['borrow_repayment_list'] = $result['list'];
			$_A['showpage'] = $pages->show(3);
		}else{
			$msg = array($result);
		}
	}
}
/**
 * 即将到期借款
**/
elseif ($_A['query_type'] == "lateFast"){
	$_A['list_title'] = "抵押标到期";
	if(isset($_POST['id'])){
		if($_SESSION['valicode']!=$_POST['valicode'] || $_POST['valicode']==''){
			$msg = array("验证码不正确");
		}else{
			unset($_SESSION['valicode']);
			$var = array("id","status","repayment_remark");
			$data = post_var($var);
			$data['repayment_user'] = $_G['user_id'];
			$result = borrowClass::AddRepayment($data);
			if ($result ==false){
				$msg = array($result);
			}else{
				$msg = array("操作成功","",$_A['query_url']."/full".$_A['site_url']);
			}
		}
		$user->add_log($_log,$result);//记录操作
	}else{
		$data['page'] = $_A['page'];
		$data['epage'] = $_A['epage'];
		$data['status'] = "0,2";
        $data['is_fast'] = 1;
		$data['repayment_time'] = time();
		if(isset($_GET['status'])){
			$data['status'] = $_GET['status'];
		}
		if(isset($_GET['username'])){
			$data['username'] = $_GET['username'];
		}
		//add by weego for latefast to excel 20120831
		if (isset($_GET['type']) && $_GET['type']=="excel"){
			$title = array("序号","用户名","借款标题","期数","到期时间","应还本息","应还利息","逾期天数","罚金","状态");
			$data['limit'] = "all";
			$result = borrowClass::GetRepaymentList($data);
			borrowClass::borrowListForExcel(array('type'=>'lateFast','title'=>$title,'excelresult'=>$result));
			exit;
		}
		$result = borrowClass::GetRepaymentList($data);
		if (is_array($result)){
			$pages->set_data($result);
			$_A['borrow_repayment_list'] = $result['list'];
			$_A['showpage'] = $pages->show(3);
		}else{
			$msg = array($result);
		}
	}
}
/**
 * 逾期还款网站代还
**/
elseif ($_A['query_type'] == "late_repay"){
	check_rank("borrow_late");//检查权限
	if(isset($_POST['id'])){
		if(strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
			$msg = array("验证码输入有误");
		}else{
			unset($_SESSION['valicode']);
			$sql = "select status from `{borrow_repayment}` where id = {$_POST['id']}";
			$result = $mysql->db_fetch_array($sql);
			if($result==false){
				$msg = array("您的操作有误");
			}else{
				if ($result['status']==1){
					$msg = array("已经还款，请不要乱操作");
				}elseif ($result['status']==2){
					$msg = array("网站已经代还，请不要乱操作");
				}else{
					if(isset($_POST['is_user_repay']) && $_POST['is_user_repay']==1){
						$data['id'] = $_POST['id'];
						$data['user_id'] = $_POST['user_id'];
						$re = accountClass::getOrderResult(array('repayment_id'=>$data['id'],'tran_code'=>$HX_config['repaymentTranCode'],'err_code'=>'0000'));
						if(empty($re['list'])){
							$msg = array("未查询到还款记录，请前往还款订单查询");
						}else{
							$data['tg_repayment_time'] = strtotime($re['list'][0]['tran_time']);
							if($data['tg_repayment_time']<(time()-2592000)){
								$msg = array("还款时间有误，请联系技术解决");
							}else{
								$n =  borrowClass::Repay($data);
								if(is_bool($n)){
									if($n==false){
										$msg = array("还款失败");
									}else{
										$msg = array("还款成功");
									}
								}else{
									$msg = array($n);
								}
							}
						}
					}elseif(isset($_POST['is_user_repay']) && $_POST['is_user_repay']==2){
						$n = borrowClass::LateRepay(array("id"=>$_POST['id']));
						if(is_bool($n)){
									if($n==false){
										$msg = array("还款失败");
									}else{
										$msg = array("还款成功");
									}
								}else{
									$msg = $n;
								}
					}
				}
			}
		}
	}elseif(isset($_GET['id'])){
		$sql = "select id from `{borrow_repayment}` where id = {$_GET['id']}";
		$result = $mysql->db_fetch_array($sql);
		if($result==false){
			$msg = array("没有相关内容");
		}else{
			$data['repayment_time'] = time();
			$data['repayment_id']=$_GET['id'];
			$result = borrowClass::GetRepaymentList($data);
			$biao_type = $result['list'][0]['biao_type'];
			require_once ROOT_PATH.'modules/borrow/biao/'.$biao_type.'biao.class.php';
			$classname = $biao_type."biaoClass";
			$dynaBiaoClass = new $classname();
			$re = $dynaBiaoClass->getWebRepayInfo(array('borrow_id'=>$result['list'][0]['borrow_id'],'order'=>$result['list'][0]['order']));
			$result['list'][0]['advance_time']=$_G['biao_type'][$biao_type]['advance_time'];
			$_A['borrow_tender_list']=$re;
			$_A['borrow_result']=$result['list'][0];
		}
	}
}
/**
 * 标地区统计
**/
elseif ($_A['query_type'] == "borrowtongji"){
	$data['salesman_user'] = $_GET['salesman_user'];
	$data['dotime1'] = $_GET['dotime1'];
	$data['dotime1'] = $_GET['dotime2'];
	$data['belong_organ'] = $_GET['belong_organ'];
	$data['recommend_organ'] = $_GET['recommend_organ'];
	$data['user_type'] = $_GET['user_type'];
	$data['province'] = $_GET['province'];
	$data['city'] = $_GET['city'];
	if($data['province']==-2){
		$sql = 'select count(p1.id) count,sum(p1.account_yes) account_yes,p2.province from {borrow} p1 left join {user} p2 on p1.user_id=p2.user_id where p1.status=3 group by p2.province';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		$account_yes = array();
		foreach($re as $k=>$v){
			$arr[intval($v['province'])] += $v['count'];
			$account_yes[intval($v['province'])] += $v['account_yes'];
		}
		$sql = 'select p1.id,p1.name from {area} p1 where p1.pid=0';
		$area = $mysql->db_fetch_arrays($sql);
		$areaArr = array();
		$zcount = 0;
		$_account_yes = 0;
		foreach($area as $key=>$value){
			$areaArr[] = array('name'=>$value['name'],'value'=>intval($arr[$value['id']]),'account_yes'=>intval($account_yes[$value['id']]));
			$zcount += $arr[$value['id']];
			$_account_yes += intval($account_yes[$value['id']]);
		}
		$areaArr[] = array('name'=>'其他','value'=>intval($arr[0]),'account_yes'=>intval($account_yes[0]));
		$zcount += $arr[0];
		$_account_yes += intval($account_yes[0]);
		$areaArr[] = array('name'=>'合计','value'=>$zcount,'account_yes'=>$_account_yes);
		$_A['borrowtongji'] = $areaArr;
	}elseif($data['belong_organ']==-2){
		$sql = 'select count(p1.id) count,sum(p1.account_yes) account_yes,p2.belong_organ from {borrow} p1 left join {user} p2 on p1.user_id=p2.user_id where p1.status=3 group by p2.belong_organ';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		$account_yes = array();
		foreach($re as $k=>$v){
			$arr[$v['recommend_organ']] += $v['count'];
			$account_yes[$v['recommend_organ']] += $v['account_yes'];
		}
		$sql = "select p1.name from {linkage} p1 left join {linkage_type} p2 on p1.type_id=p2.id where p2.nid='recommend_organ'";
		$recommend = $mysql->db_fetch_arrays($sql);
		$recommend_arr = array();
		$zcount = 0;
		$_account_yes = 0;
		foreach($recommend as $key=>$value){
			$recommend_arr[] = array('name'=>$value['name'],'value'=>intval($arr[$value['name']]),'account_yes'=>intval($account_yes[$value['name']]));
			$zcount += $arr[$value['name']];
			$_account_yes += $account_yes[$value['name']];
		}
		$recommend_arr[] = array('name'=>'其他','value'=>intval($arr['']),'account_yes'=>intval($account_yes['']));
		$zcount += $arr[''];
		$_account_yes += intval($account_yes['']);
		$recommend_arr[] = array('name'=>'合计','value'=>$zcount,'account_yes'=>intval($_account_yes));
		$_A['borrowtongji'] = $recommend_arr;

	}elseif($data['recommend_organ']==-2){//按推荐机构
		$sql = 'select count(p1.id) count,sum(p1.account_yes) account_yes,p2.recommend_organ from {borrow} p1 left join {user} p2 on p1.user_id=p2.user_id where p1.status=3 group by p2.recommend_organ';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		$account_yes = array();
		foreach($re as $k=>$v){
			$arr[$v['recommend_organ']] += $v['count'];
			$account_yes[$v['recommend_organ']] += $v['account_yes'];
		}
		$sql = "select p1.name from {linkage} p1 left join {linkage_type} p2 on p1.type_id=p2.id where p2.nid='recommend_organ'";
		$recommend = $mysql->db_fetch_arrays($sql);
		$recommend_arr = array();
		$zcount = 0;
		$_account_yes = 0;
		foreach($recommend as $key=>$value){
			$recommend_arr[] = array('name'=>$value['name'],'value'=>intval($arr[$value['name']]),'account_yes'=>intval($account_yes[$value['name']]));
			$zcount += $arr[$value['name']];
			$_account_yes += $account_yes[$value['name']];
		}
		$recommend_arr[] = array('name'=>'其他','value'=>intval($arr['']),'account_yes'=>intval($account_yes['']));
		$zcount += $arr[''];
		$_account_yes += intval($account_yes['']);
		$recommend_arr[] = array('name'=>'合计','value'=>$zcount,'account_yes'=>intval($_account_yes));
		$_A['borrowtongji'] = $recommend_arr;
	}elseif($data['salesman_user']==-2){//按业务员
		$sql = 'select count(p1.id) count,sum(p1.account_yes) account_yes,p2.salesman_user from {borrow} p1 left join {user} p2 on p1.user_id=p2.user_id where p1.status=3 group by p2.salesman_user';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		$account_yes = array();
		foreach($re as $k=>$v){
			$arr[intval($v['salesman_user'])] += $v['count'];
			$account_yes[intval($v['salesman_user'])] += $v['account_yes'];
		}
		$sql = 'select type_id from {user_type} where type=3';
		$typearr = $mysql->db_fetch_array($sql);
		$sql = 'select user_id,username from {user} where type_id='.intval($typearr['type_id']);
		$salesman = $mysql->db_fetch_arrays($sql);
		$re_array = array();
		$zcount = 0;
		$_account_yes = 0;
		foreach($salesman as $key=>$value){
			$re_array[] = array('name'=>$value['username'],'value'=>intval($arr[$value['user_id']]),'account_yes'=>intval($account_yes[$value['user_id']]));
			$zcount += intval($arr[$value['user_id']]);
			$_account_yes += intval($account_yes[$value['user_id']]);
		}
		$re_array[] = array('name'=>'其他','value'=>intval($arr[0]),'account_yes'=>intval($account_yes[0]));
		$zcount += intval($arr[0]);
		$_account_yes += intval($account_yes[0]);
		$re_array[] = array('name'=>'合计','value'=>$zcount,'account_yes'=>$_account_yes);
		$_A['borrowtongji'] = $re_array;
	}elseif($data['user_type']==-2){//按用户类型
		$sql = 'select count(p1.id) count,sum(p1.account_yes) account_yes,p2.user_type from {borrow} p1 left join {user} p2 on p1.user_id=p2.user_id where p1.status=3 group by p2.user_type';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		$zcount = 0;
		$_account_yes = 0;
		foreach($re as $k=>$v){
			if($v['user_type']==1){
				$arr[] = array('name'=>'私营业主','value'=>$v['count'],'account_yes'=>$v['account_yes']);
			}elseif($v['user_type']==2){
				$arr[] = array('name'=>'工薪族','value'=>$v['count'],'account_yes'=>$v['account_yes']);
			}else{
				$arr[] = array('name'=>'其他','value'=>$v['count'],'account_yes'=>$v['account_yes']);
			}
			$zcount+=$v['count'];
			$_account_yes+=$v['account_yes'];
		}
		$arr[] = array('name'=>'合计','value'=>$zcount,'account_yes'=>$_account_yes);
		$_A['borrowtongji'] = $arr;
	}else{
		$re = borrowClass::borrowStatistics($data);
		$_A['borrowtongji'] = array(array('name'=>'结果','value'=>$re['count'],'account_yes'=>$re['account_yes']));
	}
	if(isset($_GET['type']) && $_GET['type']=='excel'){
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename='借款标统计.xls'");
		foreach($_A['borrowtongji'] as $k=>$v){
			echo $v['name']."\t".$v['value']."\t".$v['account_yes']."\r\n";
		}
		exit();
	}
}
/**
 * 统计
**/
elseif ($_A['query_type'] == "tongji"){
	$_A['borrow_tongji'] = borrowClass::Tongji();
	$_A['account_tongji'] = accountClass::Tongji();
}
/**
 * 添加信用额度
**/
elseif ($_A['query_type'] == "addamount"){
	if(isset($_POST['valicode']) && strtolower($_POST['valicode'])!=$_SESSION['valicode']){
		$msg = array("验证码输入有误");
	}elseif(isset($_POST['username']) && $_POST['username']!=""){
		$re = $mysql->db_fetch_array('select user_id from {user} where username="'.$_POST['username'].'"');
		if(isset($re['user_id'])){
			$var = array("account","content","type","remark","status","verify_remark");
			$data = post_var($var);
			$data['user_id'] = $re['user_id'];
			$result =  borrowClass::AddAmountApply($data);
			if($result==true){
				$msg = array("添加成功",'',$_A['query_url']."/amount".$_A['site_url']);
			}else{
				$msg = array("添加失败，请重新添加");
			}
		}else{
			$msg = array("用户不存在，请重新输入");
		}
	}
}
//全能贷申请用户
elseif ($_A['query_type'] == "quickborrow"){
	$_A['list_title'] = "借款申请";
	if(isset($_POST['status'])){
		$a = $mysql->db_query('update {user_quickborrow} set status='.$_POST['status'].',remark="'.addslashes($_POST['remark']).'" where id='.$_POST['id']);
		if($a){
			$msg = array("操作成功");
		}else{
			$msg = array("操作失败");
		}
	}else{
		if(isset($_GET['company_name']) && $_GET['company_name']!=''){
			$data['company_name'] = $_GET['company_name'];
		}
		if(isset($_GET['status']) && $_GET['status']!=-1){
			$data['status'] = (int)$_GET['status'];
		}
		if(isset($_GET['phone']) && $_GET['phone']!=''){
			$data['phone'] = $_GET['phone'];
		}
		$result = userClass::GetQuickborrow($data);
		$_A['quickborrow'] = $result['list'];
		$_A['showpage'] = $pages->show(3);
	}
}
elseif($_A['query_type'] == "quickborrow_view"){
	if(isset($_GET['id'])){
		$a=$mysql->db_fetch_array('select * from {user_quickborrow} where id='.$_GET['id']);
		$_A['quickborrow'] = $a;
		$magic->assign("_A",$_A);
		$magic->display("quickborrow_view.html");exit;
	}
}

/*
 * 托管返回
**/
elseif ($_A['query_type'] == 'tgreturn') {
	if(isset($_GET['repayment_id'])){
		$a = borrowClass::tgReturnView(array('repayment_id'=>$_GET['repayment_id']));
		$_A['tgreturn'] = $a;
	}
}
/*
 * 托管扣除利息管理费
**/
elseif ($_A['query_type']=='kcinterestfee'){
	$a = $mysql->db_fetch_array('select p1.id,p1.user_id,p1.borrow_id,p1.interest_fee,p2.card_id,p2.realname,p2.phone,p2.pIpsAcctNo,p2.virCardNo from {borrow_collection} as p1 left join {user} as p2 on p1.user_id=p2.user_id where p1.interest_fee_status=0 and p1.id='.$_GET['collection_id']);
	if(!empty($a) && $a['interest_fee']>0){
		$b['virCardNo'] = $a['virCardNo'];
		$b['merRemark1'] = "借款标还款的利息管理费扣除CID:".$_GET['collection_id'];
		$b['merOrderNum'] = $a['id'].'_'.$a['user_id'].'_'.rand(001,999);
		$b['tranAmt'] = $a['interest_fee'];
		$b['borrow_id'] = $a['borrow_id'];
		$b['collection_id'] = $_GET['collection_id'];
		$v['user_id'] = $a['user_id'];
		$c = tg_deduct($b);
		if($c['pErrCode']=='0000'){
			$mysql->db_fetch_array('update {borrow_collection} set interest_fee_status=interest_fee_status+1 where id='.$b['collection_id']);
			echo 1;
		}else{
			echo $c['pErrMsg'];
		}
		unset($b);
	}else{
		echo '操作有误';
	}
	exit();
}
/*
 * 托管查询标的还款状态
**/
elseif ($_A['query_type']=="tgviewrepayment") {
	if(isset($_GET['borrow_id']) && isset($_GET['repayment_id'])){
		$a = $mysql->db_fetch_arrays("select * from {tg_order} where tran_code='{$HX_config['repaymentTranCode']}' and borrow_id={$_GET['borrow_id']} and repayment_id={$_GET['repayment_id']}");
		foreach($a as $k=>$v){
			if($v['err_code']==''){
				$b['orgTxnType'] = $v['tran_code'];
				$b['orgOrderNum'] = $v['order_number'];
				$b['orgtranDateTime'] = $v['tran_time'];
				$c = tg_orderinquire($b);
			}
		}
	}
}
//防止乱操作
else{
	$msg = array("输入有误，请不要乱操作","",$url);
}
?>