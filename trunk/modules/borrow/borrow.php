<?
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
$arrnocheck=array("biaoTJ","borrow_user_attestation","image_downloaded","sendsmsprotocol","user_attestation");
if (in_array($_A['query_type'], $arrnocheck)){
	//����Ȩ��
}else{
	check_rank("borrow_".$_A['query_type']);//���Ȩ��
}

include_once("borrow.class.php");

$_A['list_purview'] =  array("borrow"=>array("������"=>array("borrow_list"=>"����б�",
"borrow_new"=>"��ӽ��",
"borrow_edit"=>"�༭���",
"borrow_amount"=>"�����",
"borrow_amount_view"=>"��ȹ���",
"borrow_del"=>"ɾ�����",
"borrow_view"=>"��˽��",
"borrow_full"=>"�����б�",
"borrow_repayment"=>"�ѻ���",
"borrow_liubiao"=>"����",
"borrow_late"=>"����",
"borrow_full_view"=>"����鿴")));
//Ȩ��
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>���н��</a> - <a href='{$_A['query_url']}&status=0{$_A['site_url']}'>�������</a> -  <a href='{$_A['query_url']}&status=1{$_A['site_url']}'>�����б�Ľ��</a> -  <a href='{$_A['query_url']}/full&status=1{$_A['site_url']}'>���긴��</a> - <a href='{$_A['query_url']}/repayment{$_A['site_url']}&status=1'>�ѻ�����</a>  -  <a href='{$_A['query_url']}/liubiao{$_A['site_url']}'>����</a>  - <a href='{$_A['query_url']}/late{$_A['site_url']}'>��ǰ���ڽ��</a> - <a href='{$_A['query_url']}/lateFast{$_A['site_url']}'>�������ڽ��</a>  - <a href='{$_A['query_url']}/tongji{$_A['site_url']}'>����ͳ��</a>";

if ($_A['query_type'] == "list"){
	$_A['list_title'] = "��Ϣ�б�";
	if (isset($_POST['id']) && $_POST['id']!=""){
		$data['id'] = $_POST['id'];
		$data['flag'] = $_POST['flag'];
		$data['view'] = $_POST['view'];
		$result = borrowClass::Action($data);
		if ($result==true){
			$msg = array("�޸ĳɹ�","",$_A['query_url'].$_A['site_url']);
		}else{
			$msg = array("�޸�ʧ�ܣ��������Ա��ϵ");
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
			$title = array("���","�û�����","����","������","�����","���ʣ�%��","�������","����ʱ��","״̬");
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
 * ��ȹ���
**/
elseif ($_A['query_type'] == "amount"){
	check_rank("borrow_amount");//���Ȩ��
	$_A['list_title'] = "��ȹ���";
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
 * ��ȹ���
**/
elseif ($_A['query_type'] == "amount_view"){
	check_rank("borrow_amount_view");//���Ȩ��
	$data['id'] = $_GET['id'];
	$result = borrowClass::GetAmountApplyOne($data);
	if (isset($_POST['status'])){
		if($_POST['valicode']!=$_SESSION['valicode'] || $_POST['valicode']==''){
			$msg = array("��֤����������");
		}else{
			unset($_SESSION['valicode']);
			$data['user_id'] = $result['user_id'];
			$data['status'] = $_POST['status'];
			$data['type'] = $_POST['type'];
			$data['account'] = $_POST['account'];
			$data['verify_remark'] = $_POST['verify_remark'].'��������ԱID:'.$_G['user_id'];
			$result = borrowClass::CheckAmountApply($data);
			if ($result !=1){
				$msg = array($result);
			}else{
				$msg = array("�����ɹ�","",$_A['query_url']."/amount&a=borrow");
			}
			$user->add_log($_log,$result);//��¼����
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
 * ���
**/

elseif ($_A['query_type'] == "new"  || $_A['query_type'] == "edit" ){
	check_rank("borrow_new");//���Ȩ��
	$_A['list_title'] = "������Ϣ";
	//��ȡ�û�id����Ϣ
	if (isset($_REQUEST['user_id']) && isset($_POST['username'])){
		if(isset($_POST['user_id']) && $_POST['user_id']!=""){
			$data['user_id'] = $_POST['user_id'];
			$result = userClass::GetOne($data);
		}elseif(isset($_POST['username']) && $_POST['username']!=""){
			$data['username'] = $_POST['username'];
			$result = userClass::GetOne($data);
		}
		if ($result==false){
			$msg = array("�Ҳ������û�");
		}elseif($result['is_tgAccount']!=1){
			$msg = array("�û�δ�󶨱����˻���������");
		}else{
			echo "<script>location.href='".$_A['query_url']."/new&a=borrow&user_id={$result['user_id']}'</script>";
		}
	}
	elseif (isset($_POST['name'])){
		$var = array("user_id","name","use","time_limit","style","account","apr","lowest_account","most_account","valid_time","award","part_account","funds","is_false","content","isday","time_limit_day","biao_type","is_tuijian","dsfdb","user_pro");
		$data = post_var($var);
		if (($_POST['status']!=0 || $_POST['status']!=-1) && $_A['query_type'] == "edit"){
			$msg = array("�˱��Ѿ����б�����Ѿ���ɣ������޸�","",$_A['query_url'].$_A['site_url']);
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
				$msg = array("�����ɹ�","",$_A['query_url'].$_A['site_url']);
			}
		}
		$user->add_log($_log,$result);//��¼����
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
			$msg = array("������������","",$_A['query_url']);
		}else{
			$_A['user_result'] = $result;
			//$result = borrowClass::GetOne($data);
			//$_A['borrow_result'] = $result;
		}
	}
}

/**
 * �鿴
**/
elseif ($_A['query_type'] == "view"){
	check_rank("borrow_view");//����Ȩ��
	$centre_remark = check_rank_bool("borrow_centre_remark");//����Ȩ��
	$committee_remark = check_rank_bool("borrow_committee_remark");//����ίԱ��Ȩ��
	$verify_remark = check_rank_bool("borrow_verify_remark");//�ۺ����Ȩ��
	$_A['check_rank'] = array('centre_remark'=>$centre_remark,'committee_remark'=>$committee_remark,'verify_remark'=>$verify_remark);
	$_A['list_title'] = "�������";
	
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
			$msg = array("���ʧ��");
		}else{
			//����û��Ķ�̬
			$brsql="select * from `{borrow}` where id ='".$_POST['id']."'";
			$br_row = $mysql->db_fetch_array($brsql);
			if($data['status']==1){
				//�Զ�Ͷ��
				$auto['id']=$br_row['id'];
				$auto['user_id']=$br_row['user_id'];
				$auto['total_jie']=$br_row['account'];
				$auto['zuishao_jie']=$br_row['lowest_account'];
				borrowClass::auto_borrow($auto);

				$_data['user_id'] = $_POST['user_id'];
				$_data['content'] = "�ɹ�������\"<a href=\'/invest/a{$data['id']}.html\' target=\'_blank\'>{$_POST['name']}</a>\"����";
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
			$msg = array("��˲����ɹ�","",$_A['query_url'].$_A['site_url']."&a=borrow");
			//֪ͨ�����ˣ������˳ɹ�
			if ($data['status']==1){
				//sendSMS($_POST['user_id'],"���Ľ���{$_POST['name']}�Ѿ��ɹ�������",1);
			}
		}
		$user->add_log($_log,$result);//��¼����
	}elseif(isset($_POST['id']) && (isset($_POST['centre_remark']) || isset($_POST['committee_remark']))){//����������
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
			$msg = array("����ʧ��");
		}else{
			$msg = array("�����ɹ�");
		}
	}else{
		$data['id'] = $_GET['id'];
		$data['user_id'] = $_GET['user_id'];
		if($data['id']=="" || $data['user_id']==""){
			$msg = array("���Ĳ�������");
		}else{
			$_A['borrow_result'] = borrowClass::GetOne($data);

			//�û�ȱ�ٵ�����
			/*$re = userClass::GetAttestation(array('user_id'=>$_A['borrow_result']['user_id']));
			if($re!=''){
				$_A['user_attestation'] = $re;
			}*/
			//�Ƿ��ǵ�����
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
			//�û��ϴ�����������
			$_A['borrow_shus_result'] = $mysql->db_fetch_arrays("select * from {attestation} where user_id={$data['user_id']} and borrow_id={$data['id']} and upload_type=4");
			//�ж��Ƿ���Ҫ����ίԱ�����
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
 * �ϴ�������Ҫ��ʾ��ͼƬ
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
 * �ϴ�������Ҫ��ʾ��ͼƬ
 */
elseif($_A['query_type']=="borrow_user_attestation"){
	if(isset($_FILES) && !empty($_FILES)){
		$_G['upimg']['user_id'] = $_G['user_id'];
		$_G['upimg']['file'] = "admin_up";
		$_G['upimg']['cut_status'] = 0;
		$_G['upimg']['code'] = "attestation";
		$pic_result = $upload->upfile($_G['upimg']);
		if ($pic_result!=""){
			$data_1['litpic'] = $pic_result['filename'];//�ϴ���ͼƬ
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
	$user_u = $mysql->db_fetch_arrays($sql);//�û����е�����
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
			if($v['nid']=='tow_report' && $user_result['marry']==3){//δ������
				unset($result[$k]);
				continue;
			}
			if($v['nid']=='tow_certificate' && $user_result['marry']==3){//δ������
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
					$x = $count.'ѡ'.$select;
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
					echo '���ϴ�';
				}else{
					echo 'δ�ϴ�';
				}
				echo '</td><td>';
				if($v['status']==1){
					echo '�����ͨ��';
				}elseif($v['status']==2){
					echo '���δͨ��';
				}else{
					if($v['is_u']==1){
						echo 'δ���<a href="'.$_A['admin_url'].'&q=module/attestation/view&site_id=26&a=attestation&id='.$v['id'].'" target="_blank">(�������)</a>';
					}else{
						echo 'δ���';
					}
				}
				echo '</td></tr>';
			}
			foreach($item['not_must'] as $k=>$v){
				foreach($v as $kk=>$vv){
					echo '<tr style="height:20px">';
					echo '<td width="200px">'.$vv['attestation_name'].'</td>';
					echo '<td width="200px">���ϴ�</td>';
					echo '<td width="200px">';
					if($vv['status']==1){
						echo '�����ͨ��';
					}else if($vv['status']==2){
						echo '���δͨ��';
					}else{
						echo 'δ���<a href="'.$_A['admin_url'].'&q=module/attestation/view&site_id=26&a=attestation&id='.$vv['id'].'" target="_blank">(�������)</a>';
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
 * ����ͼƬ
 */
/*
elseif($_A['query_type']=="image_downloaded"){
	$image = ROOT_PATH.$_GET['imagepath'];
	$hz = strrchr($image, ".");
	$hz = str_replace(".", "", $hz);
	$array = array("jpg","jpeg","png","gjf");
	if(in_array($hz, $array) && file_exists($image)){
		//header('Content-Type: application/image/gif');//���������
		header("Content-Type: application/image/$hz");
		header("Content-Disposition: attachment; filename={$_GET['imagefile']}.$hz"); //������ʾ������,ע���ʽ
		readfile($image);
	}else{
		exit();
	}
}*/
/**
 * ɾ��
**/
/*
elseif ($_A['query_type'] == "del"){
	check_rank("borrow_del");//���Ȩ��
	$data['id'] = $_GET['id'];
	$result = borrowClass::Delete($data);
	if ($result !== true){
		$msg = array($result);
	}else{
		$msg = array("ɾ���ɹ�","",$_A['query_url'].$_A['site_url']);
	}
	$user->add_log($_log,$result);//��¼����
}
*/
/**
 * �����б�
**/
elseif ($_A['query_type'] == "full"){
	check_rank("borrow_full");//���Ȩ��
	$_A['list_title'] = "��Ϣ�б�";
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
 * ��ת��ֹͣ��ת
 */
elseif ($_A['query_type']=="stoplz"){
	check_rank("borrow_stoplz");
	$id = $_GET['id'];
	if($id>0){
		$sql = "update `{borrow}` set valid_time=0 where id=$id";
		$re = $mysql->db_query($sql);
		if($re==false){
			$msg = array("����ʧ��");
		}else{
			$msg = array("�����ɹ�");
		}
	}else{
		$msg = array("�벻Ҫ�Ҳ���");
	}
}
/**
 * ���곷��
**/
elseif ($_A['query_type'] == "cancel"){
	check_rank("borrow_cancel");//���Ȩ��
	$_A['list_title'] = "����";
	if($_POST['valicode']!=$_SESSION['valicode'] || $_POST['valicode']==''){
		$msg = array("��֤����������");
	}else{
		unset($_SESSION['valicode']);
		$re = borrowClass::Cancel(array("id"=>$_POST['id']));
		if($re==false){
			$msg = array("����ʧ��","",$_A['query_url'].$_A['site_url']);
		}else{
			$msg = array("���سɹ�","",$_A['query_url'].$_A['site_url']);
		}
	}
}
/**
 * �ѻ�����
**/
elseif($_A['query_type'] == "repayment"){
	check_rank("borrow_repayment");//���Ȩ��
	$_A['list_title'] = "������Ϣ";
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
			$title = array("���","�����","������","����","����ʱ��","������","������Ϣ","����ʱ��","״̬");
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
 * �����б�
**/
elseif ($_A['query_type'] == "liubiao"){
	check_rank("borrow_liubiao");//���Ȩ��
	$_A['list_title'] = "����";
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
 * �����޸�
**/
elseif ($_A['query_type'] == "liubiao_edit"){
	check_rank("borrow_liubiao");//���Ȩ��
	$_A['list_title'] = "�������";
	if (isset($_POST['status'])){
		if(strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
			$msg = array("��֤����������");
		}else{
			unset($_SESSION['valicode']);
			$data['days'] = $_POST['days'];
			$data['id'] = $_POST['id'];
			$data['status'] = $_POST['status'];
			$result = borrowClass::ActionLiubiao($data);
			if($result==true){
				$msg = array("�����ɹ�","",$_A['query_url']."/liubiao".$_A['site_url']);
			}else{
				$msg = array("����ʧ��");
			}
		}
	}else{
		$data['id'] = $_GET['id'];
		$result = borrowClass::GetOne($data);
		$_A['borrow_result'] = $result;
	}
}
/**
 * ���긴��
**/
elseif ($_A['query_type'] == "full_view"){
	global $mysql;
	check_rank("borrow_full_view");//���Ȩ��
	$_A['list_title'] = "���괦��";
	if(!isset($_POST['id']) && !isset($_GET['id'])){
		$msg = array("��������");
	}elseif (isset($_POST['id'])){
		if($_SESSION['valicode']!=strtolower($_POST['valicode']) || $_POST['valicode']==''){
			$msg = array("��֤�벻��ȷ");
		}else{
			unset($_SESSION['valicode']);
			$var = array("id","status","repayment_remark");
			$data = post_var($var);
			$data['repayment_user'] = $_G['user_id'];
            $data['verify_time'] = time();         
            $sql = "select status from {borrow}  where id=".$_POST['id'];
            $resultBorrow = $mysql->db_fetch_array($sql);
            if($resultBorrow['status']==3 && $resultBorrow['status']==4){
                $msg = array("�˱��Ѿ���˹���������˴����У������ظ����");
            }else{
                $result = borrowClass::AddRepayment($data);
                if(is_bool($result)){
                	if ($result ==false){
                		$msg = array("����ʧ��");
                	}else{
                		$msg = array("�����ɹ�","",$_A['query_url']."/full".$_A['site_url']);
                	}
                }else{
                	$msg = array($result);
                }
           	}
           	$user->add_log($_log,$result);//��¼����
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
			$msg = array("��ǰ�����û��Ѿ����� �� ���Ĳ�������, ��ˢ�±�ҳ��");
		}
	}
}
/*
 * ���Ͷ���֪ͨ�û�������Ӧ����
 */
elseif ($_A['query_type']=="sendsmsprotocol"){
	$borrow_id = intval($_POST['borrow_id']);
	if ($borrow_id<1){
		echo "û���ҵ���Ӧ�Ľ���";
	}else{
		$borrow_re = $mysql->db_fetch_array("select user_id,name from {borrow} where id={$borrow_id}");
		if($borrow_re==false){
			echo "û���ҵ���Ӧ�Ľ���";
		}else{
			$re = borrowClass::GetBorrowProtocol(array('borrow_id'=>$borrow_id));
			$str = "";
			foreach ($re as $v){
				$str .= $str==""?$v['name']:','.$v['name'];
			}
			if($str!=""){
				$str = '���Ľ���'.$borrow_re['name'].'�Ѿ����꣬�뾡���ṩ������ϣ�'.$str;
				$re = sendSMS($borrow_re['user_id'],$str,1);
				if ($re==false){
					echo "����ʧ��";
				}else{
					echo "���ͳɹ�";
				}
			}
		}
	}
	eixt();
}
/**
 * ���ڻ���
**/
elseif ($_A['query_type'] == "late"){
	check_rank("borrow_late");//���Ȩ��
	$_A['list_title'] = "���ڻ���";
	if (isset($_POST['id'])){
		/*
		if($_SESSION['valicode']!=$_POST['valicode']){
			$msg = array("��֤�벻��ȷ");
		}else{
			$_SESSION['valicode'] = "";
			$var = array("id","status","repayment_remark");
			$data = post_var($var);
			$data['repayment_user'] = $_G['user_id'];
			$result = borrowClass::AddRepayment($data);
			if ($result ==false){
				$msg = array($result);
			}else{
				$msg = array("�����ɹ�","",$_A['query_url']."/full".$_A['site_url']);
			}
		}
		$user->add_log($_log,$result);//��¼����
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
			$title = array("�����","�����","������","����","����ʱ��","Ӧ����Ϣ","Ԥ������","����","״̬");
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
 * �������ڽ��
**/
elseif ($_A['query_type'] == "lateFast"){
	$_A['list_title'] = "��Ѻ�굽��";
	if(isset($_POST['id'])){
		if($_SESSION['valicode']!=$_POST['valicode'] || $_POST['valicode']==''){
			$msg = array("��֤�벻��ȷ");
		}else{
			unset($_SESSION['valicode']);
			$var = array("id","status","repayment_remark");
			$data = post_var($var);
			$data['repayment_user'] = $_G['user_id'];
			$result = borrowClass::AddRepayment($data);
			if ($result ==false){
				$msg = array($result);
			}else{
				$msg = array("�����ɹ�","",$_A['query_url']."/full".$_A['site_url']);
			}
		}
		$user->add_log($_log,$result);//��¼����
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
			$title = array("���","�û���","������","����","����ʱ��","Ӧ����Ϣ","Ӧ����Ϣ","��������","����","״̬");
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
 * ���ڻ�����վ����
**/
elseif ($_A['query_type'] == "late_repay"){
	check_rank("borrow_late");//���Ȩ��
	if(isset($_POST['id'])){
		if(strtolower($_POST['valicode'])!=$_SESSION['valicode'] || $_POST['valicode']==''){
			$msg = array("��֤����������");
		}else{
			unset($_SESSION['valicode']);
			$sql = "select status from `{borrow_repayment}` where id = {$_POST['id']}";
			$result = $mysql->db_fetch_array($sql);
			if($result==false){
				$msg = array("���Ĳ�������");
			}else{
				if ($result['status']==1){
					$msg = array("�Ѿ�����벻Ҫ�Ҳ���");
				}elseif ($result['status']==2){
					$msg = array("��վ�Ѿ��������벻Ҫ�Ҳ���");
				}else{
					if(isset($_POST['is_user_repay']) && $_POST['is_user_repay']==1){
						$data['id'] = $_POST['id'];
						$data['user_id'] = $_POST['user_id'];
						$re = accountClass::getOrderResult(array('repayment_id'=>$data['id'],'tran_code'=>$HX_config['repaymentTranCode'],'err_code'=>'0000'));
						if(empty($re['list'])){
							$msg = array("δ��ѯ�������¼����ǰ���������ѯ");
						}else{
							$data['tg_repayment_time'] = strtotime($re['list'][0]['tran_time']);
							if($data['tg_repayment_time']<(time()-2592000)){
								$msg = array("����ʱ����������ϵ�������");
							}else{
								$n =  borrowClass::Repay($data);
								if(is_bool($n)){
									if($n==false){
										$msg = array("����ʧ��");
									}else{
										$msg = array("����ɹ�");
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
										$msg = array("����ʧ��");
									}else{
										$msg = array("����ɹ�");
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
			$msg = array("û���������");
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
 * �����ͳ��
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
		$areaArr[] = array('name'=>'����','value'=>intval($arr[0]),'account_yes'=>intval($account_yes[0]));
		$zcount += $arr[0];
		$_account_yes += intval($account_yes[0]);
		$areaArr[] = array('name'=>'�ϼ�','value'=>$zcount,'account_yes'=>$_account_yes);
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
		$recommend_arr[] = array('name'=>'����','value'=>intval($arr['']),'account_yes'=>intval($account_yes['']));
		$zcount += $arr[''];
		$_account_yes += intval($account_yes['']);
		$recommend_arr[] = array('name'=>'�ϼ�','value'=>$zcount,'account_yes'=>intval($_account_yes));
		$_A['borrowtongji'] = $recommend_arr;

	}elseif($data['recommend_organ']==-2){//���Ƽ�����
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
		$recommend_arr[] = array('name'=>'����','value'=>intval($arr['']),'account_yes'=>intval($account_yes['']));
		$zcount += $arr[''];
		$_account_yes += intval($account_yes['']);
		$recommend_arr[] = array('name'=>'�ϼ�','value'=>$zcount,'account_yes'=>intval($_account_yes));
		$_A['borrowtongji'] = $recommend_arr;
	}elseif($data['salesman_user']==-2){//��ҵ��Ա
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
		$re_array[] = array('name'=>'����','value'=>intval($arr[0]),'account_yes'=>intval($account_yes[0]));
		$zcount += intval($arr[0]);
		$_account_yes += intval($account_yes[0]);
		$re_array[] = array('name'=>'�ϼ�','value'=>$zcount,'account_yes'=>$_account_yes);
		$_A['borrowtongji'] = $re_array;
	}elseif($data['user_type']==-2){//���û�����
		$sql = 'select count(p1.id) count,sum(p1.account_yes) account_yes,p2.user_type from {borrow} p1 left join {user} p2 on p1.user_id=p2.user_id where p1.status=3 group by p2.user_type';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		$zcount = 0;
		$_account_yes = 0;
		foreach($re as $k=>$v){
			if($v['user_type']==1){
				$arr[] = array('name'=>'˽Ӫҵ��','value'=>$v['count'],'account_yes'=>$v['account_yes']);
			}elseif($v['user_type']==2){
				$arr[] = array('name'=>'��н��','value'=>$v['count'],'account_yes'=>$v['account_yes']);
			}else{
				$arr[] = array('name'=>'����','value'=>$v['count'],'account_yes'=>$v['account_yes']);
			}
			$zcount+=$v['count'];
			$_account_yes+=$v['account_yes'];
		}
		$arr[] = array('name'=>'�ϼ�','value'=>$zcount,'account_yes'=>$_account_yes);
		$_A['borrowtongji'] = $arr;
	}else{
		$re = borrowClass::borrowStatistics($data);
		$_A['borrowtongji'] = array(array('name'=>'���','value'=>$re['count'],'account_yes'=>$re['account_yes']));
	}
	if(isset($_GET['type']) && $_GET['type']=='excel'){
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename='����ͳ��.xls'");
		foreach($_A['borrowtongji'] as $k=>$v){
			echo $v['name']."\t".$v['value']."\t".$v['account_yes']."\r\n";
		}
		exit();
	}
}
/**
 * ͳ��
**/
elseif ($_A['query_type'] == "tongji"){
	$_A['borrow_tongji'] = borrowClass::Tongji();
	$_A['account_tongji'] = accountClass::Tongji();
}
/**
 * ������ö��
**/
elseif ($_A['query_type'] == "addamount"){
	if(isset($_POST['valicode']) && strtolower($_POST['valicode'])!=$_SESSION['valicode']){
		$msg = array("��֤����������");
	}elseif(isset($_POST['username']) && $_POST['username']!=""){
		$re = $mysql->db_fetch_array('select user_id from {user} where username="'.$_POST['username'].'"');
		if(isset($re['user_id'])){
			$var = array("account","content","type","remark","status","verify_remark");
			$data = post_var($var);
			$data['user_id'] = $re['user_id'];
			$result =  borrowClass::AddAmountApply($data);
			if($result==true){
				$msg = array("��ӳɹ�",'',$_A['query_url']."/amount".$_A['site_url']);
			}else{
				$msg = array("���ʧ�ܣ����������");
			}
		}else{
			$msg = array("�û������ڣ�����������");
		}
	}
}
//ȫ�ܴ������û�
elseif ($_A['query_type'] == "quickborrow"){
	$_A['list_title'] = "�������";
	if(isset($_POST['status'])){
		$a = $mysql->db_query('update {user_quickborrow} set status='.$_POST['status'].',remark="'.addslashes($_POST['remark']).'" where id='.$_POST['id']);
		if($a){
			$msg = array("�����ɹ�");
		}else{
			$msg = array("����ʧ��");
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
 * �йܷ���
**/
elseif ($_A['query_type'] == 'tgreturn') {
	if(isset($_GET['repayment_id'])){
		$a = borrowClass::tgReturnView(array('repayment_id'=>$_GET['repayment_id']));
		$_A['tgreturn'] = $a;
	}
}
/*
 * �йܿ۳���Ϣ�����
**/
elseif ($_A['query_type']=='kcinterestfee'){
	$a = $mysql->db_fetch_array('select p1.id,p1.user_id,p1.borrow_id,p1.interest_fee,p2.card_id,p2.realname,p2.phone,p2.pIpsAcctNo,p2.virCardNo from {borrow_collection} as p1 left join {user} as p2 on p1.user_id=p2.user_id where p1.interest_fee_status=0 and p1.id='.$_GET['collection_id']);
	if(!empty($a) && $a['interest_fee']>0){
		$b['virCardNo'] = $a['virCardNo'];
		$b['merRemark1'] = "���껹�����Ϣ����ѿ۳�CID:".$_GET['collection_id'];
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
		echo '��������';
	}
	exit();
}
/*
 * �йܲ�ѯ��Ļ���״̬
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
//��ֹ�Ҳ���
else{
	$msg = array("���������벻Ҫ�Ҳ���","",$url);
}
?>