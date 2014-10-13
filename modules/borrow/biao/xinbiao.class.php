<?php
/*
 * 	���ñ�ҵ���߼���
���괦�� add
Ͷ�괦�� tender
���괦�������ں�����������cancel
��˴��� verify
����� repay
���ڴ��� overdue

*/

include_once(ROOT_PATH."modules/borrow/biao/biaotype.class.php");
include_once(ROOT_PATH."modules/borrow/borrow.class.php");
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

class xinbiaoClass extends biaotypeClass{
	protected $biao_type = "xin";
	/**
	 * ����
	 * @param Array $data
	 * @return Boolen
	 */
	function add($data = array()){
		global $mysql;
		$user_id = $data["user_id"];
		$resultAmount = borrowClass::GetAmountOne($user_id,"credit");
		//��������ڿ������ö��
		if (($data['account'] > $resultAmount["account_use"])){
			$result = "�������ö�Ȳ��㡣";
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
	 * �������
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function full_verify($data = array()){
		global $mysql;
		$result = true;
		if ($data['status']==3){
			$amountlog_result = borrowClass::GetAmountOne($data['user_id'],"credit");
			$amountlog["user_id"] = $data['user_id'];
			$amountlog["type"] = "borrow_success";
			$amountlog["amount_type"] = "credit";
			$amountlog["account"] = $data['account'];
			$amountlog["account_all"] = $amountlog_result['account_all'];
			$amountlog["account_use"] = $amountlog_result['account_use'] - $amountlog['account'];
			$amountlog["account_nouse"] = $amountlog_result['account_nouse'] + $amountlog['account'];
			$amountlog["remark"] = "����[<a href=\'/invest/a{$data['id']}.html\' target=_blank><font color=red>{$data['name']}</font></a>]�������ͨ����������ö�ȼ���";
			$result = borrowClass::AddAmountLog($amountlog);
		}
		return $result;
	}
	/**
	 * ����
	 * @param Array $data
	 * @return Boolen
	 */
	function repay($data = array()){
		global $mysql;
		//$borrow_id = $data["borrow_id"];
		$borrow_userid=$data["borrow_userid"];
		//$sql="select * from `{borrow}` where `id` = {$borrow_id}";
		//$borrow_result = $mysql->db_fetch_array($sql);
		$capital = $data['capital'];
		$amountlog_result = borrowClass::GetAmountOne($borrow_userid,"credit");
		$amountlog["user_id"] = $borrow_userid;
		$amountlog["type"] = "borrrow_repay";
		$amountlog["amount_type"] = "credit";
		//TODO�ָ����ö�ȵ��㷨
		$amountlog["account"] = $capital;
		$amountlog["account_all"] = $amountlog_result['account_all'];
		$amountlog["account_use"] = $amountlog_result['account_use'] + $amountlog['account'];
		$amountlog["account_nouse"] = $amountlog_result['account_nouse'] - $amountlog['account'];
		$amountlog["remark"] = "�ɹ�������ö������";
		$result = borrowClass::AddAmountLog($amountlog);
		if ($result == 1){
			return true;
		}else{
			return false;
		}
	}
}
?>