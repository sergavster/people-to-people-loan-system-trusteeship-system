<?php
/*
 * 	��ֵ��ҵ���߼���
���괦�� add
Ͷ�괦�� tender
���괦�������ں�����������cancel
��˴��� verify
����� repay
���ڴ��� overdue

*/

include_once(ROOT_PATH."modules/borrow/biao/biaotype.class.php");
include_once(ROOT_PATH."modules/borrow/borrow.class.php");
include_once(ROOT_PATH."modules/account/account.class.php");
require_once(ROOT_PATH."modules/remind/remind.class.php");
/*
//liukun add for bug 52 begin
$firePHPEnable=TRUE;
if ($firePHPEnable){
	require_once('modules/FirePHPCore/FirePHP.class.php');
	require_once('modules/FirePHPCore/fb.php');
	ob_start();

	$firephp = FirePHP::getInstance(true);
}*/
//liukun add for bug 52 end

class jinbiaoClass extends biaotypeClass{
	protected $biao_type = "jin";
	/**
	 * ����
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function add($data = array()){
		global $mysql;

		$user_id = $data["user_id"];

		$_result_wait = borrowClass::GetWaitPayment(array("user_id"=>$user_id));

		$account_result =  accountClass::GetOne(array("user_id"=>$user_id));//��ȡ��ǰ�û������
		//���ʲ�
		$account_moneyJin = $account_result['use_money']+$account_result['collection']-$_result_wait['wait_payment'];

		//��������ڿ������ö��
		if (($data['account'] > $account_moneyJin)){
			$result = "���þ��ʲ����㡣";
			return $result;
		}
		//�Զ���˴���
		$auto_verify = self::get_auto_verify();
		if ($auto_verify == 1){
			$data['status'] = 1;
			$data['verify_user'] = 1;
			$data['verify_remark'] = '�Զ����';
			$data['verify_time'] = time();
		}
		/*
		$sql = "insert into `{borrow}` set `addtime` = '".time()."',`addip` = '127.0.0.1'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$result = $mysql->db_query($sql);
		*/
		$result = $mysql->db_add("borrow", $data);
		$newid = $mysql->db_insert_id();

		if ($result && $auto_verify){
			$auto['id']=$newid;
			$auto['user_id']=$data['user_id'];
			$auto['total_jie']=$data['account'];
			$auto['zuishao_jie']=$data['lowest_account'];
			borrowClass::auto_borrow($auto);
		}
		return $result;
	}
	/**
	 * Ԥ��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	 function late_repay($data = array()){
		
		return true;
	 
	 
	 
	 }
}
?>