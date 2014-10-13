<?php
/*
 * 	秒标业务逻辑类
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

class miaobiaoClass extends biaotypeClass{
	protected $biao_type = "miao";

	/**
	 * 发标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function add($data = array()){
		global $mysql;
		$account_result =  accountClass::GetOneAccount(array("user_id"=>$data['user_id']));//获取当前用户的余额
		$freeze_fee = round($data['apr']*$data['account']/(100*12), 2);
		if($account_result['use_money'] < $freeze_fee ){
			$result = "可用余额不足。";
			return $result;
		}
		//自动审核处理
		$auto = self::get_auto_verify();
		if ($auto == 1){
			$data['status'] = 1;
			$data['verify_user'] = 1;
			$data['verify_remark'] = '自动审核';
			$data['verify_time'] = time();
		}
		$result = $mysql->db_add("borrow", $data);
		$newid = $mysql->db_insert_id();
		if (!$result){
			$result = "发标失败。";
			return $result;
		}
		$log['user_id'] = $data['user_id'];
		$log['type'] = "borrow_fee_forst";
		$log['money'] = $freeze_fee;
		$log['total'] = $account_result['total'];
		$log['use_money'] =  $account_result['use_money']-$log['money'];
		$log['no_use_money'] =  $account_result['no_use_money']+$log['money'];
		$log['collection'] =  $account_result['collection'];
		$log['to_user'] = 0;
		$log['remark'] = "发布秒还标<a href='/invest/a{$newid}.html'>{$data['name']}</a>时冻结的费用";
		$log['remark'] = mysql_real_escape_string($log['remark']);
		$result = accountClass::AddLog($log);//添加记录
		return $result;
	}

	/**
	 * 流标处理
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function cancel($data = array()){
		global $mysql;
		$account_result =  accountClass::GetOneAccount(array("user_id"=>$data['user_id']));
		$account_log['user_id'] =$data['user_id'];
		$account_log['type'] = "borrow_fee_unforst";

		$account_log['money'] = round($data['apr']*$data['account']/(100*12), 2);
		$account_log['total'] =$account_result['total'];
		$account_log['use_money'] = $account_result['use_money']+$account_log['money'];
		$account_log['no_use_money'] = $account_result['no_use_money']-$account_log['money'];
		$account_log['collection'] = $account_result['collection'];
		$account_log['to_user'] = "0";
		$account_log['remark'] = "借款失败,对[<a href=\'/invest/a{$data['id']}.html\' target=_blank>{$data['name']}</a>]借款的解冻";
		$result = accountClass::AddLog($account_log);
		return $result;
	}
	/**
	 * 满标审核
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function full_verify($data = array()){
		global $mysql;
		$borrow_result = $data;
		$freeze_fee = round($data['apr']*$data['account']/(100*12), 2);
		$account_result =  accountClass::GetOneAccount(array("user_id"=>$borrow_result['user_id']));//获取当前用户的余额
		$log['user_id'] = $borrow_result['user_id'];
		$log['type'] = "borrow_fee_unforst";
		$log['money'] = $freeze_fee;
		$log['total'] = $account_result['total'];
		$log['use_money'] =  $account_result['use_money']+$log['money'];
		$log['no_use_money'] =  $account_result['no_use_money']-$log['money'];
		$log['collection'] =  $account_result['collection'];
		$log['to_user'] = 0;
		$log['remark'] = "解冻发布秒还标时冻结的费用";
		$result = accountClass::AddLog($log);//添加记录
		$sql="select p1.id from `{borrow_repayment}` as p1  where borrow_id = {$borrow_result['id']}";
		$result = $mysql->db_fetch_array($sql);
		$repay_data['id'] = $result['id'];
		$repay_data['user_id'] = $borrow_result['user_id'];
		$result = borrowClass::Repay($repay_data);
		return $result;
	}
}
?>