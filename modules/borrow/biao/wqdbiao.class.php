<?php
/*
 * 	��Ѻ�꣨���ٱ꣩ҵ���߼���
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

class wqdbiaoClass extends biaotypeClass{
	protected $biao_type = "wqd";

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
		$auto_verify = self::get_auto_verify();
		if ($auto_verify == 1){
			$data['status'] = 1;
			$data['verify_user'] = 1;
			$data['verify_remark'] = '�Զ����';
			$data['verify_time'] = time();
		}
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


	
	


}
?>