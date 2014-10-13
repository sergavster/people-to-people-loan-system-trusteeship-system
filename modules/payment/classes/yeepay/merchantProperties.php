<?php

/*
 * @Description 易宝支付产品通用接口范例 
 * @V3.0
 * @Author rui.xin
 */

#	商户编号p1_MerId,以及密钥merchantKey 需要从易宝支付平台获得
// $p1_MerId			= "10012019523";
// $merchantKey	= "KIN6R0j01kcjr674o46vQ0t899oR4YaP8E3a6DI217C4St829yU48K639931";
$p1_MerId			= "10001126856";
$merchantKey	= "69cl522AV6q613Ii4W6u8K6XuW8vM1N6bFgyv769220IuYe9u37N4y7rI4Pl";

$logName	= ROOT_PATH."modules/payment/classes/yeepay/YeePay_HTML.log";
		
	# 业务类型
	# 支付请求，固定值"Buy" .	
	$p0_Cmd = "Buy";
		
	#	送货地址
	# 为"1": 需要用户将送货地址留在易宝支付系统;为"0": 不需要，默认为 "0".
	$p9_SAF = "0";
?> 