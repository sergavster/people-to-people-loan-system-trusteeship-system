<?
if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
check_rank("user_".$_A['query_type']);//���Ȩ��

$_A['list_purview'] =  array("user"=>array("�û�����"=>array("user_list"=>"�û��б�","user_view"=>"�鿴�û���Ϣ","user_new"=>"����û�","user_edit"=>"�޸��û�","user_del"=>"ɾ���û�","user_type"=>"�û�����","user_type_order"=>"�û���������","user_type_del"=>"ɾ���û�����","user_type_new"=>"����û�����","user_type_edit"=>"�༭�û�����")));//Ȩ��
$_A['list_name'] = "�û�����";
//$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>�û��б�</a> - <a href='{$_A['query_url']}/vip{$_A['site_url']}'>vip�û�</a> - <a href='{$_A['query_url']}/new{$_A['site_url']}'>����û�</a> - <a href='{$_A['query_url']}/type{$_A['site_url']}'>�û�����</a>";
$_A['list_menu'] = "<a href='{$_A['admin_url']}&q=module/userinfo{$_A['site_url']}'>�û���Ϣ����</a> - <a href='{$_A['admin_url']}&q=module/user{$_A['site_url']}'>�û�����</a> - <a href='{$_A['admin_url']}&q=module/user/new{$_A['site_url']}'>����û�</a> - <a href='{$_A['admin_url']}&&q=module/credit{$_A['site_url']}'>���ֹ���</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=2{$_A['site_url']}'>��н�ײ���������</a> - <a href='{$_A['admin_url']}&q=module/userinfo/infoconf&site_id=46&usertype=1{$_A['site_url']}'>˽Ӫҵ����������</a>";

$_A['list_table'] = "";

/**
 * �û��б�
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "�û��б�";
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
 * �û��б�
**/
/*
if ($_A['query_type'] == "typechange"){
	$_A['list_title'] = "�û��ı���������";
	if (isset($_REQUEST['id']) && $_REQUEST['id']!=""){
		$data['id'] = $_REQUEST['id'];
		$data['status'] = $_REQUEST['status'];
		$data['type'] = "update";
		$result = userClass::TypeChange($data);
		$msg = array("�����޸ĳɹ�","",$_A['query_url']."/typechange");
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
 * ��Ӻͱ༭�û�
**/
elseif ($_A['query_type'] == "new" || $_A['query_type'] == "edit" || $_A['query_type'] == "view"){
	if ($_A['query_type'] == "new" ){
		$_A['list_title'] = "����û�";
	}else{
		$_A['list_title'] = "�޸��û�";
	}
	if (isset($_POST['realname'])){
		$var = array("type_id","username","email","realname","password","sex","qq","wangwang","tel","phone","address","status","province","city","area","card_type","card_id","islock","invite_userid","invite_money","serial_id","user_type");
		$data = post_var($var);
		$data['area'] = post_area();
		$data['birthday'] = get_mktime($_POST['birthday']);
		$purview_usertype = explode(",",$_SESSION['purview']);
		//add by weego 20120929 �޸������û��������������û�bug
		if($data['type_id']==''){
			$data['type_id']='2';
		}
		if (!in_array("userinfo_edit",$purview_usertype) ){
			$msg = array("��û��Ȩ����ӻ�������Ĺ���Ա".$index['type_id']);
		}elseif($data['email']!='' && !is_email($data['email'])){
			$msg = array('�����ʽ����ȷ');
		}elseif($data['card_type']==1 && $data['card_id']!='' && !isIdCard($data['card_id'])){
			$msg = array('���֤��������');
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
					$msg_content .= "�û����Ѿ����ڣ�";
				}
				if ($check_email){
					$msg_content .= "�����Ѿ����ڣ�";
				}
				if ($check_card_id){
					$msg_content .= "���֤�Ѿ����ڣ�";
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
								$msg = '�û������Ϸ�';
							} elseif ($uid == -2) {
								$msg = '����Ҫ����ע��Ĵ���';
							} elseif ($uid == -3) {
								$msg = '�û����Ѿ�����';
							} elseif ($uid == -4) {
								$msg = 'Email ��ʽ����';
							} elseif ($uid == -5) {
								$msg = 'Email ������ע��';
							} elseif ($uid == -6) {
								$msg = '�� Email �Ѿ���ע��';
							} else {
								$msg = 'δ����';
							}
						}
					}
					if($msg==''){
						$result = userClass::AddUser($data);
						if ($result>0){
							$msg = array("�û�����ӳɹ�","",$_A['query_url']);
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
					$msg = array("�����Ѿ�����");
				}else{
					$ucresult = 1;
					
					if($rdGlobal['uc_on'] && isset($data['password'])){
						require_once ROOT_PATH . '/core/config_ucenter.php';
						require_once ROOT_PATH . '/uc_client/client.php';
						$ucresult = uc_user_edit($data['username'], '', $_POST['password'], '', 1);
						if ($ucresult == -1) {
							$msg = array("�����벻��ȷ,��ʹ����̳�ĵ�¼����","",$url);
						} elseif ($ucresult == -4) {
							$msg = array("Email ��ʽ����");
						} elseif ($ucresult == -5) {
							$msg = array("Email ������ע��");
						} elseif ($ucresult == -6) {
							$msg = array("�� Email �Ѿ���ע��");
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
							$msg = array("�޸ĳɹ�");
						}
					}
				}
			}
		}
	}else{
		$user_type = userClass::GetTypeList(array("type"=>2));
		if ($user_type==false){
			$msg = array("��û�����ͣ��������","����û�����","{$_A['query_url']}/type_new");
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
				$msg = array("�˹���Ա���ܱ༭,���Ҫ�޸ģ��뵽�޸ĸ�����Ϣ");
			}else{
				$_A['user_result'] = userClass::GetOne(array("user_id"=>$_REQUEST['user_id']));
			}
			//�û��Ĳ鿴
			if ($_A['query_type'] == "view"){
				$_A['user_attestation'] = $mysql->db_fetch_arrays('select p1.*,p2.name as type_name from {attestation} p1 left join {attestation_type} p2 on p1.type_id=p2.type_id where user_id='.$_REQUEST['user_id'].' and upload_type=1');
				$magic->assign("_A",$_A);
				$magic->display("view.tpl","modules/user");exit;
			}
		}
	}
}
/**
 * �����ͽ����û�
**/
elseif ($_A['query_type'] == "lock"){
	if ($_GET['user_id']==1){
		$msg = array("���û�����ɾ��");
	}else{
		//repair by weego 20120703
		//$result = userClass::DeleteUser(array("user_id"=>$_REQUEST['user_id'],"type"=>2));
		$islock = $_GET['islock']==1?1:0;
		$result = $user->UpdateUser(array("user_id"=>$_REQUEST['user_id'],"islock"=>$islock));
		if($result==false){
			$msg = array("����ʧ��",'',"{$_A['query_url']}&a=system");
		}else{
			$msg = array("�����ɹ�",'',"{$_A['query_url']}&a=system");
			$user->add_log($_log,$result);//��¼����
		}
	}
}
/**
 * �û������б�
**/
elseif ($_A['query_type'] == "type"){
	$_A['user_type_list'] = userClass::GetTypeList(array("type"=>2));//0��ʾ�û�������1��ʾ�����������
}
/**
 * ��Ӻͱ༭�û�����
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
			$msg = array("���Ͳ����ɹ�","","{$_A['query_url']}/type&a=userinfo");
		}
		$user->add_log($_log,$result);//��¼����
	}else{
		if ($_A['query_type'] == "type_edit"){
			$result = userClass::GetTypeOne(array("type_id"=>$_GET['type_id']));
			$magic->assign("result",$result);
		}
	}
}
/**
 * �޸��û���������
**/
elseif ($_A['query_type'] == "type_order"){
	$data['order'] = $_POST['order'];
	$data['type_id'] = $_POST['type_id'];
	$result = userClass::OrderType($data);
	if ($result == false){
		$msg = array("���������������Ա��ϵ");
	}else{
		$msg = array("�����޸ĳɹ�");
	}
	$user->add_log($_log,$result);//��¼����
}
/**
 * ɾ���û�����
**/
elseif ($_A['query_type'] == "type_del"){
	$data['type_id'] = $_REQUEST['type_id'];
	if ($data['type_id']==1){
		$msg = array("��������Ա���ͽ�ֹɾ��");
	}else{
		$result = userClass::DeleteType($data);
		if ($result){
			$msg = array("ɾ���ɹ�");
		}else{
			$msg = array($result);
		}
		$user->add_log($_log,$result);//��¼����
	}
}
/**
 * VIP�û�
**/
elseif ($_A['query_type'] == "vip"){
	if(isset($_GET['username'])){
		$data['username']=$_GET['username'];
	}
	$data['vip_status']=1;
	$result = userClass::GetList($data);//0��ʾ�û�������1��ʾ�����������
	$pages->set_data($result);
	$_A['user_vip_list'] = $result['list'];
	$_A['showpage'] = $pages->show(3);
}
/**
 * VIP��˲鿴
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
                    
			$msg = array("VIP�û���˳ɹ�","","{$_A['query_url']}/vip");
		}
		
		$user->add_log($_log,$result);//��¼����
	}else{
		$_A['user_result'] = userClass::GetOne(array("user_id"=>$_REQUEST['user_id']));
	}
}
*/

/*
*�û�ͳ��
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
		$areaArr[] = array('name'=>'����','value'=>intval($arr[0]));
		$zcount += $arr[0];
		$areaArr[] = array('name'=>'�ϼ�','value'=>$zcount);
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
		$recommend_arr[] = array('name'=>'����','value'=>intval($arr['']));
		$zcount += $arr[''];
		$recommend_arr[] = array('name'=>'�ϼ�','value'=>$zcount);
		$_A['user_statistics'] = $recommend_arr;
	}elseif($data['recommend_organ']==-2){//���Ƽ�����
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
		$recommend_arr[] = array('name'=>'����','value'=>intval($arr['']));
		$zcount += $arr[''];
		$recommend_arr[] = array('name'=>'�ϼ�','value'=>$zcount);
		$_A['user_statistics'] = $recommend_arr;
	}elseif($data['salesman_user']==-2){//��ҵ��Ա
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
		$re_array[] = array('name'=>'����','value'=>intval($arr[0]));
		$zcount += intval($arr[0]);
		$re_array[] = array('name'=>'�ϼ�','value'=>$zcount);
		$_A['user_statistics'] = $re_array;
	}elseif($data['user_type']==-2){//���û�����
		$sql = 'select count(p1.user_id) count,p1.user_type from {user} p1 group by p1.user_type';
		$re = $mysql->db_fetch_arrays($sql);
		$arr = array();
		$zcount = 0;
		foreach($re as $k=>$v){
			if($v['user_type']==1){
				$arr[] = array('name'=>'˽Ӫҵ��','value'=>$v['count']);
			}elseif($v['user_type']==2){
				$arr[] = array('name'=>'��н��','value'=>$v['count']);
			}else{
				$arr[] = array('name'=>'����','value'=>$v['count']);
			}
			$zcount+=$v['count'];
		}
		$arr[] = array('name'=>'�ϼ�','value'=>$zcount);
		$_A['user_statistics'] = $arr;
	}else{
		$re = $user->userStatistics($data);
		$_A['user_statistics'] = array(array('name'=>'���','value'=>$re['count']));
	}
	if(isset($_GET['type']) && $_GET['type']=='excel'){
		header("Content-Type:application/vnd.ms-excel");
		header("Content-Disposition:attachment;filename='�û�ͳ��.xls'");
		foreach($_A['user_statistics'] as $k=>$v){
			echo $v['name']."\t".$v['value']."\r\n";
		}
		exit();
	}
}
?>