<?php
interface trusteeship
{
	public function openAnAccount($data = array());//开户
	public function login($data = array());//登陆
	public function recharge($data = array());//充值
	public function tender($data = array());//投标
	public function review($data = array());//复审
	public function liubiao($data = array());//流标
	public function getAccount($data = array());//获取账户余额
	public function repayment($data = array());//还款
	public function cash($data = array());//取款
	public function deductMoney($data = array());//扣款
	public function addBorrow();//发标
	public function authorizationAutoTender($data = array());//授权自动投标
	public function orderInquire($data = array());//订单查询
}
?>