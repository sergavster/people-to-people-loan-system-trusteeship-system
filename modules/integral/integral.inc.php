<?php
/**
 * @author Tissot.Cai(Email:Tissot.Cai@gmail.com,QQ:1213690001)
 * @copyright Tissot.Cai
 * @version 1.0
 */

/**
 * Description of discount
 *
 * @author TissotCai
 */
require_once 'integral.class.php';
class IntegralModule {

	/**
	 * 获取积分兑换物品列表
	 * @param $param
	 *		array(
	 *			'page' => '页码',
	 *			'where' => 'array(goods=>背包,...)',
	 *			'epage' => '每页记录数'
	 *		)
	 * @return
	 *	array(
	 *		'list' => array(物品信息),
	 *		'record_num' => 记录数,
	 *		'page' => 页码,
	 *		'page_size' => 每页记录数
	 *		'total_page' => 总页码
	 * )
	 */
	public static function Get ($param) {

		$p = isset($param['page'])?(int)$param['page']:1;
		$page_size = isset($param['epage'])?(int)$param['epage']:1;
		$where = isset($param['where'])?$param['where']:array();

		return Integral::ListIntegral($where, $p, $page_size);
	}

	/**
	 * 积分兑换
	 * @param $param 会员ID
	 *		array(
	 *			'user_id' => '会员ID',
	 *			'goods_id' => '物品ID',
	 *			'number' => '[OPTION]兑换数量'
	 *		)
	 * @return
	 *		Integral::NOT_ENOUGH_GOODS 物品不足
	 *		Integral::NOT_ALLOW_CITY 兑换城市限制
	 *		true 成功
	 */
	public static function ExchangeIntegral ($param) {

		$user_id = isset($param['user_id'])?$param['user_id']:0;
		$goods_id = isset($param['goods_id'])?$param['goods_id']:0;
		$number = isset($param['number'])?$param['number']:1;

		return Integral::ExchangeIntegral($user_id, $goods_id, $number);
	}
}
?>
