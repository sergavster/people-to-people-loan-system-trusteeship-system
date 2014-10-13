<?php
/*
 * 	��ת������ҵ���߼���
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

class lzbiaoClass extends biaotypeClass{
	protected $biao_type = "lz";


	/**
	 * ����
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function add($data = array()){
		global $mysql;

		$user_id = $data["user_id"];

		//�Զ���˴���
		if (self::get_auto_verify() == 1){
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
				
		return $result;
	}



	
	/**
	 * �û�Ͷ��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function tender($data = array()){
		global $mysql;
				
		$tender_id = $data['tender_id'];
		$borrow_id = $data['borrow_result']['id'];
	
		$data_e['id'] = $borrow_id;
		$data_e['status'] = '3';
		$data_e['tender_id'] = $tender_id;
		$data_e['repayment_remark'] = '�Զ������ת��';
		$re = borrowClass::AddRepaymentForLZ($data_e);
		return $re;
	}
	
	
	/**
	 * ���괦��
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function cancel($data = array()){
		global $mysql;

 
		return true;
	}
	
}
?>