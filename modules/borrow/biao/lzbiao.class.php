<?php
/*
 * 	流转担保贷业务逻辑类
发标处理 add
投标处理 tender
流标处理（含到期和主动撤消）cancel
审核处理 verify
还款处理 repay
逾期处理 overdue

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
	 * 发标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function add($data = array()){
		global $mysql;

		$user_id = $data["user_id"];

		//自动审核处理
		if (self::get_auto_verify() == 1){
			$data['status'] = 1;
			$data['verify_user'] = 1;
			$data['verify_remark'] = '自动审核';
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
	 * 用户投标
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
		$data_e['repayment_remark'] = '自动审核流转标';
		$re = borrowClass::AddRepaymentForLZ($data_e);
		return $re;
	}
	
	
	/**
	 * 流标处理
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