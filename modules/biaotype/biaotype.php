<?

if (!defined('ROOT_PATH'))  die('���ܷ���');//��ֱֹ�ӷ���
check_rank("manager_".$_A['query_type']);//���Ȩ��

$list_purview =  array("manager"=>array("�� �� Ա"=>array("manager_list"=>"����Ա�б�","manager_new"=>"��ӹ���Ա","manager_edit"=>"�޸Ĺ���Ա","manager_type"=>"����Ա����","manager_type_order"=>"�޸���������","manager_type_del"=>"ɾ������","manager_type_new"=>"�������","manager_type_edit"=>"�༭����")));//Ȩ��
$_A['list_name'] = "���ֹ���";
$_A['list_menu'] = "<a href='{$_A['query_url']}{$_A['site_url']}'>�����б�</a>";
$list_table ="";

//liukun add for bug 52 begin
$firePHPEnable=TRUE;
if ($firePHPEnable){
	require_once('modules/FirePHPCore/FirePHP.class.php');
	require_once('modules/FirePHPCore/fb.php');
	ob_start();

	$firephp = FirePHP::getInstance(true);
}
//liukun add for bug 52 end

/**
 * ����Ա�б�
**/
if ($_A['query_type'] == "list"){
	$_A['list_title'] = "�б�";
	
		$data['page'] = $_A['page'];
		$data['epage'] = $_A['epage'];
		$data['type'] = 1;
		
		global $mysql;
		
		$sql = "select * from `{biao_type}` order by id";
		$result = $mysql ->db_fetch_arrays($sql);
		
// 		$pages->set_data(array(
//             'list' => $list,
//             'total' => $result,
//             'page' => $page,
//             'epage' => $epage,
//             'total_page' => $total_page
//         ));
		
		$_A['biao_type_list'] = $result;
		//$_A['showpage'] = $pages->show(3);
	
}

/**
 * ��Ӻͱ༭�û�
**/
elseif ($_A['query_type'] == "edit"){

	$_A['list_title'] = "�޸ı���";


	if (isset($_POST['type_id'])){
			
		$type_id = $_REQUEST['type_id'];
		
		$data['biao_type_name'] = $_POST['biao_type_name'];
		$data['available'] = $_POST['available'];
		$data['password_model'] = $_POST['password_model'];
		$data['day_model'] = $_POST['day_model'];
		$data['auto_verify'] = $_POST['auto_verify'];
		$data['auto_full_verify'] = $_POST['auto_full_verify'];
		$data['min_amount'] = $_POST['min_amount'];
		$data['max_amount'] = $_POST['max_amount'];
		$data['min_interest_rate'] = $_POST['min_interest_rate'];
		$data['advance_time'] = $_POST['advance_time'];
		$data['advance_scope'] = $_POST['advance_scope'];
		$data['advance_vip_scope'] = $_POST['advance_vip_scope'];
		$data['late_interest_rate'] = $_POST['late_interest_rate'];
		$data['borrow_fee_rate_start'] = $_POST['borrow_fee_rate_start'];
		$data['borrow_fee_rate_start_month_num'] = $_POST['borrow_fee_rate_start_month_num'];
		$data['borrow_fee_rate'] = $_POST['borrow_fee_rate'];
		$data['borrow_fee_rate_max'] = $_POST['borrow_fee_rate_max'];
		$data['borrow_day_fee_rate'] = $_POST['borrow_day_fee_rate'];
		$data['interest_fee_rate'] = $_POST['interest_fee_rate'];
		$data['interest_fee_rate_vip'] = $_POST['interest_fee_rate_vip'];
		$data['frost_rate'] = $_POST['frost_rate'];
		$data['advance_rate'] = $_POST['advance_rate'];
		$data['advance_vip_rate'] = $_POST['advance_vip_rate'];
		$data['late_customer_interest_rate'] = $_POST['late_customer_interest_rate'];
		$data['max_tender_times'] = $_POST['max_tender_times'];
		$data['show_name'] = $_POST['show_name'];
		$data['late_interest_scope'] = $_POST['late_interest_scope'];
		$data['extract_rate'] = $_POST['extract_rate'];
		$data['gt_money_committee']  = $_POST['gt_money_committee'];
		$data['max_tender_number'] = $_POST['max_tender_number'];
		$data['is_vip_borrow'] = $_POST['is_vip_borrow'];
		$data['is_vip_render'] = $_POST['is_vip_render'];

		$data['frost_rate'] = 0;//�й�ȡ������
		


		$sql = "update `{biao_type}` set `id` = {$type_id}";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$sql .= " where `id` = {$type_id}";
		$result = $mysql->db_query($sql);
		if ($result===false){
			$msg = array($result);
		}else{
			$p = systemClass::createParameter_biaotype();
			if($p == true && $p>0){
				$msg = array("�޸ĳɹ�");
			}else{
				$msg = array("�޸�ʧ��");
			}
		}
	}else{
		if ($_A['query_type'] == "edit"){
			$type_id = $_REQUEST['type_id'];
			$sql = "select * from `{biao_type}` where id = {$type_id}";
			$result = $mysql ->db_fetch_array($sql);
			$_A['biao_type_result'] = $result;
		}
	}
}
?>