<?php
interface trusteeship
{
	public function openAnAccount($data = array());//����
	public function login($data = array());//��½
	public function recharge($data = array());//��ֵ
	public function tender($data = array());//Ͷ��
	public function review($data = array());//����
	public function liubiao($data = array());//����
	public function getAccount($data = array());//��ȡ�˻����
	public function repayment($data = array());//����
	public function cash($data = array());//ȡ��
	public function deductMoney($data = array());//�ۿ�
	public function addBorrow();//����
	public function authorizationAutoTender($data = array());//��Ȩ�Զ�Ͷ��
	public function orderInquire($data = array());//������ѯ
}
?>