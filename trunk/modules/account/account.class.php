<?
/******************************
 * $File: account.class.php
 * $Description: 数据库处理文件
 * $Author: jack 
 * $Time:2011-05-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
require_once(ROOT_PATH."modules/remind/remind.class.php");
class accountClass{

	const ERROR = '操作有误，请不要乱操作';
	
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";	
			 
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p2.user_id = '{$data['user_id']}'";
		}
		
		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}
		
		$sql = "select SELECT from {account} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				$_sql ORDER LIMIT";
		$_select = "p1.*,p2.username,p2.realname";
		
		//是否显示全部的信息

		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $_limit), $sql));
			
			return $list;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();

		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	/**
	 * Author :jackfeng
	 * 账户信息表--财务专用
	 * @return Array
	 * 2012-09-23
	 */
	/*
	function GetListTJ($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";	
			 
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p1.username like '%{$data['username']}%'";
		}
		
		
		$sql = "select SELECT from {account_tj} as p1 
				$_sql ORDER LIMIT";
		$_select = "p1.*";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $_limit), $sql));
			
			return $list;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		
		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	*/
	//冻结资金是负数或可用是负数监控 add  by jackfeng 2012-10-22
	function GetListFs($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " and (p1.use_money<0 or p1.no_use_money<0) ";	
			 
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}
		
		
		$sql = "select SELECT from {account} as p1 ,{user} as p2 where p1.user_id=p2.user_id
				$_sql ORDER LIMIT";
		$_select = "p1.*,p2.username,p2.realname";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $_limit), $sql));
			
			return $list;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		
		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	/**
	 * Author :LiuYY
	 * 提成列表(后台)
	 * @return Array
	 * 2012-06-07
	 */
		function GetTicheng($data = array()){
		global $mysql;
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where 1=1 ";	
		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and usernames = '{$data['username']} '";
		}
		$ksql = "select SELECT from view_tc_backend  $_sql GROUP ORDER LIMIT";
		$_select = "*";
		$sqls = str_replace(array('SELECT','GROUP','ORDER', 'LIMIT'), array('count(*) as num','','', ''), $ksql);
		$row = $mysql->db_fetch_array($sqls);
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$sqls = str_replace(array('SELECT', 'GROUP', 'ORDER', 'LIMIT'), array($_select,'', 'order by addtimes desc', $limit), $ksql);
	
		$list = $mysql->db_fetch_arrays($sqls);		
		
		$list = $list?$list:array();

		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
	
/*
 * 检验所有用户的资金情况
 */
	function GetUsersMoneyCheckList($data = array()){
		global $mysql;
		//$mysql->db_query("update {user} set email_status=1 where user_id=426");
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";	

		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}

		$sql = "select SELECT from {account} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				$_sql ORDER LIMIT";
		$_select = "p1.*,p2.username,p2.realname";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $_limit), $sql));
			foreach ($list as $key => $value){
                            $user_id = $value["user_id"];
                            //1)资金总额
                            $list[$key]['total'] = round($value["total"],2);
                            //2)可用资金
                            $list[$key]['use_money'] = round($value["use_money"],2);
                            //3)冻结资金
                            $list[$key]['no_use_money'] = round($value["no_use_money"],2);
                            //4)待收资金(1)
                            $list[$key]['collection'] = round($value["collection"],2);
                            //5)待收资金(2)
                            $sql = "Select sum(repay_account) as repay_account From {borrow_collection} where tender_id in(select id from {borrow_tender} where  borrow_id in(Select id from {borrow} where status=3 ) and user_id={$user_id}) and status=0  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['collection2'] = round($result['repay_account'],2);
                            //6)充值资金(1)
                            $sql = "Select sum(money) as reMoney from {account_recharge} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney'] = round($result['reMoney'],2);
                            //7充值资金(2)
                            $sql = "Select sum(money) as reMoney2 from {account_log} where user_id={$user_id} and type='recharge'";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney2'] = round($result['reMoney2'],2);
                            //8)其中：线上
                            $sql = "Select sum(money) as reMoney_1 from {account_recharge} where user_id={$user_id} and status=1 and type=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_1'] = round($result['reMoney_1'],2);
                            //9)其中：线下1
                            $sql = "Select sum(money) as reMoney_2 from {account_recharge} where user_id={$user_id} and status=1 and type=2";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_2'] = round($result['reMoney_2'],2);
                            //10)其中：线下2
                            $sql = "Select sum(money) as reMoney_3 from {account_recharge} where user_id={$user_id} and status=1 and type=0";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_3'] = round($result['reMoney_3'],2);
                            //11)成功提现金额
                            $sql = "Select sum(total) as txTotal from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txTotal'] = round($result['txTotal'],2);
                            //12)提现实际到账
                            $sql = "Select sum(credited) as txCredited from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txCredited'] = round($result['txCredited'],2);
                            //13)提现费用
                            $sql = "Select sum(fee) as txFee from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txFee'] = round($result['txFee'],2);
                            //14)投标奖励金额
                            $sql = "Select sum(money) as awardAdd from {account_log} where user_id={$user_id} and type='award_add'";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['awardAdd'] = round($result['awardAdd'],2);
                            //15)投标已收资金
                            $sql = "Select sum(repay_yesaccount) as repay_yesaccount From {borrow_collection} where tender_id in(select id from {borrow_tender} where borrow_id not in(Select id from {borrow} where status=5) and user_id={$user_id})  and status=1  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['collecdMoney'] = round($result['repay_yesaccount'],2);
                            //16)投标已赚利息
                            $sql = "Select sum(interest) as interestYes From {borrow_collection} where tender_id in(Select id from {borrow_tender} where user_id={$user_id} )  and status=1 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['interestYes'] = round($result['interestYes'],2);
                            //17)投标待收利息
                            $sql = "Select sum(interest) as interestWait From {borrow_collection} where tender_id in(Select id from {borrow_tender} where user_id={$user_id} and borrow_id in(Select id from {borrow} where  status=3) ) and status=0 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['interestWait'] = round($result['interestWait'],2);
                            //19)借款总金额
                            $sql = "Select sum(account) as accountBorrow From {borrow} where user_id={$user_id} and status=3 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['accountBorrow'] = round($result['accountBorrow'],2);
                            //20)自己逾期总额
                            $sql = "select sum(repayment_account) as accountLateAll  from `{borrow_repayment}` as p1,`{borrow}` as p2  where p1.borrow_id=p2.id and p2.status=3 and p1.repayment_time <'".get_mktime(date("Y-m-d",time()))."' and p1.status in (0,2) and p2.user_id= {$user_id}";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['accountLateAll'] = round($result['accountLateAll'],2);
                            //19)借款标奖励
                            $sql = "Select sum(account*funds*0.01) as award1 from {borrow} where funds >0 and award=2 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $borrowAward1 = round($result['award1']);
                            
                            $sql = "Select sum(part_account) as award2 from {borrow} where part_account >0 and award=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $borrowAward2 = round($result['award2']);
                            
                            $list[$key]['borrowAward'] = round(($borrowAward1+$borrowAward2),2);
                            
                            //19)借款管理费
                            $sql = "Select sum(account*0.5*0.01*time_limit) as bowFee1 from {borrow} where is_fast=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee1 = $result['bowFee1'];
                            
                            $sql = "Select sum(account*0.2*0.01*time_limit) as bowFee2 from {borrow} where is_jin=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee2 = $result['bowFee2'];
                            
                            $sql = "Select sum(account*0.5*0.01*time_limit) as bowFee3 from {borrow} where (is_jin != 1 && is_mb != 1 && is_fast != 1 && is_vouch != 1) and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee3 = $result['bowFee3'];
                            
                            $list[$key]['borrowMgrFee'] = round(($bowFee1+$bowFee2+$bowFee3),2);
                            
                            //19)待还总金额
                            $sql = " Select sum(repayment_account) as repayment_account From {borrow_repayment} where status=0 and borrow_id  in(Select id from {borrow} where user_id={$user_id} and status!=5)  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['waitMoney'] = round($result['repayment_account'],2);
                            //191)待还利息
                            $sql = " Select sum(interest) as repayment_account From {borrow_repayment} where status=0 and borrow_id  in(Select id from {borrow} where user_id={$user_id} and status!=5)  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['waitMoney_interest'] = round($result['repayment_account'],2);
							//191)待还本金
                            $sql = " Select sum(capital) as capital From {borrow_repayment} where status=0 and borrow_id  in(Select id from {borrow} where user_id={$user_id} and status!=5)  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['waitMoney_money'] = round($result['capital'],2);
                            //20)借款已还金额（含利息）
                            $sql = "Select sum(interest) as repayment_yesaccount From {borrow_repayment} where borrow_id in(select id from {borrow} where  user_id={$user_id} and status=3) and status=1 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['repayment_yesaccount'] = round($result['repayment_yesaccount'],2);
                            //22)系统扣费
                            $sql = "Select sum(money) as feeSystem from {account_log} where user_id={$user_id} and type in('scene_account','vouch_advanced','borrow_kouhui','account_other')";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['feeSystem'] = round($result['feeSystem'],2);
                             //23)推广奖励vip提成
                            $sql = "Select sum(invite_money) as invite_money From {user} where invite_userid={$user_id} ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['invite_money'] = round($result['invite_money'],2);
                            //24)VIP扣费
                            $sql = "Select sum(money) as vipMoney from {account_log} where user_id={$user_id} and type='vip' and (remark='扣除VIP会员费(扣除VIP冻结金额)' or remark='扣除VIP会员费')";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['vipMoney'] = round($result['vipMoney'],2);
                            
                            //25)账户总额1    
                            $list[$key]['total1'] = $list[$key]['reMoney'] + 0.9*$list[$key]['interestYes']+ $list[$key]['awardAdd'] + $list[$key]['invite_money'] + $list[$key]['accountBorrow'] - $list[$key]['txTotal'] - $list[$key]['repayment_yesaccount'] -$list[$key]['borrowMgrFee']-$list[$key]['borrowAward']-$list[$key]['vipMoney']-$list[$key]['feeSystem'];
                             
                            $list[$key]['total2'] = $list[$key]['reMoney2'] + 0.9*$list[$key]['interestYes']+ $list[$key]['awardAdd'] + $list[$key]['invite_money'] + $list[$key]['accountBorrow'] - $list[$key]['txTotal'] - $list[$key]['repayment_yesaccount'] -$list[$key]['borrowMgrFee']-$list[$key]['borrowAward']-$list[$key]['vipMoney']-$list[$key]['feeSystem'];
                            
                        }
			return $list;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
                foreach ($list as $key => $value){
                            $user_id = $value["user_id"];
                            //1)资金总额
                            $list[$key]['total'] = round($value["total"],2);
                            //2)可用资金
                            $list[$key]['use_money'] = round($value["use_money"],2);
                            //3)冻结资金
                            $list[$key]['no_use_money'] = round($value["no_use_money"],2);
                            //4)待收资金(1)
                            $list[$key]['collection'] = round($value["collection"],2);
                            //5)待收资金(2)
                            $sql = "Select sum(repay_account) as repay_account From {borrow_collection} where tender_id in(select id from {borrow_tender} where  borrow_id in(Select id from {borrow} where status=3 ) and user_id={$user_id}) and status=0  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['collection2'] = round($result['repay_account'],2);
                            //6)充值资金(1)
                            $sql = "Select sum(money) as reMoney from {account_recharge} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney'] = round($result['reMoney'],2);
                            //7充值资金(2)
                            $sql = "Select sum(money) as reMoney2 from {account_log} where user_id={$user_id} and type='recharge'";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney2'] = round($result['reMoney2'],2);
                            //8)其中：线上
                            $sql = "Select sum(money) as reMoney_1 from {account_recharge} where user_id={$user_id} and status=1 and type=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_1'] = round($result['reMoney_1'],2);
                            //9)其中：线下1
                            $sql = "Select sum(money) as reMoney_2 from {account_recharge} where user_id={$user_id} and status=1 and type=2";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_2'] = round($result['reMoney_2'],2);
                            //10)其中：线下2
                            $sql = "Select sum(money) as reMoney_3 from {account_recharge} where user_id={$user_id} and status=1 and type=0";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_3'] = round($result['reMoney_3'],2);
                            //11)成功提现金额
                            $sql = "Select sum(total) as txTotal from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txTotal'] = round($result['txTotal'],2);
                            //12)提现实际到账
                            $sql = "Select sum(credited) as txCredited from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txCredited'] = round($result['txCredited'],2);
                            //13)提现费用
                            $sql = "Select sum(fee) as txFee from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txFee'] = round($result['txFee'],2);
                            //14)投标奖励金额
                            $sql = "Select sum(money) as awardAdd from {account_log} where user_id={$user_id} and type='award_add'";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['awardAdd'] = round($result['awardAdd'],2);
                            //15)投标已收资金
                            $sql = "Select sum(repay_yesaccount) as repay_yesaccount From {borrow_collection} where tender_id in(select id from {borrow_tender} where borrow_id not in(Select id from {borrow} where status=5) and user_id={$user_id})  and status=1  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['collecdMoney'] = round($result['repay_yesaccount'],2);
                            //16)投标已赚利息
                            $sql = "Select sum(interest) as interestYes From {borrow_collection} where tender_id in(Select id from {borrow_tender} where user_id={$user_id} )  and status=1 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['interestYes'] = round($result['interestYes'],2);
                            //17)投标待收利息
                            $sql = "Select sum(interest) as interestWait From {borrow_collection} where tender_id in(Select id from {borrow_tender} where user_id={$user_id} and borrow_id in(Select id from {borrow} where  status=3) ) and status=0 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['interestWait'] = round($result['interestWait'],2);
                            //19)借款总金额
                            $sql = "Select sum(account) as accountBorrow From {borrow} where user_id={$user_id} and status=3 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['accountBorrow'] = round($result['accountBorrow'],2);
                            //19)借款标奖励
                            $sql = "Select sum(account*funds*0.01) as award1 from {borrow} where funds >0 and award=2 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $borrowAward1 = round($result['award1']);
                            
                            $sql = "Select sum(part_account) as award2 from {borrow} where part_account >0 and award=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $borrowAward2 = round($result['award2']);
                            
                            $list[$key]['borrowAward'] = round(($borrowAward1+$borrowAward2),2);
                            
                            //19)借款管理费
                            $sql = "Select sum(money) as bowFee from {account_log} where type='borrow_fee' and user_id={$user_id}";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee1 = $result['bowFee'];  
                            
                            $list[$key]['borrowMgrFee'] = round(($bowFee1),2);
                            
                            //19)待还总金额
                            $sql = " Select sum(repayment_account) as repayment_account From {borrow_repayment} where status=0 and borrow_id  in(Select id from {borrow} where user_id={$user_id} and status!=5)  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['waitMoney'] = round($result['repayment_account'],2);
							//191)待还利息
                            $sql = " Select sum(interest) as repayment_account From {borrow_repayment} where status=0 and borrow_id  in(Select id from {borrow} where user_id={$user_id} and status!=5)  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['waitMoney_interest'] = round($result['repayment_account'],2);
							//191)待还本金
                            $sql = " Select sum(capital) as capital From {borrow_repayment} where status=0 and borrow_id  in(Select id from {borrow} where user_id={$user_id} and status!=5)  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['waitMoney_money'] = round($result['capital'],2);
                            //20)借款已还利息）
                            $sql = "Select sum(interest) as repayment_yesaccount From {borrow_repayment} where borrow_id in(select id from {borrow} where  user_id={$user_id} and status=3) and status=1 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['repayment_yesaccount'] = round($result['repayment_yesaccount'],2);
                            //22)系统扣费
                            $sql = "Select sum(money) as feeSystem from {account_log} where user_id={$user_id} and type in('scene_account','vouch_advanced','borrow_kouhui','account_other')";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['feeSystem'] = round($result['feeSystem'],2);
                             //23)推广奖励vip提成
                            $sql = "Select sum(invite_money) as invite_money From {user} where invite_userid={$user_id} ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['invite_money'] = round($result['invite_money'],2);
                            //24)VIP扣费
                            $sql = "Select sum(money) as vipMoney from {account_log} where user_id={$user_id} and type='vip' and (remark='扣除VIP会员费(扣除VIP冻结金额)' or remark='扣除VIP会员费')";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['vipMoney'] = round($result['vipMoney'],2);
                            
                            //25)账户总额1    
                            $list[$key]['total1'] = $list[$key]['reMoney'] + 0.9*$list[$key]['interestYes']
                                + $list[$key]['awardAdd'] + $list[$key]['invite_money'] + $list[$key]['accountBorrow'] - $list[$key]['txTotal'] - $list[$key]['repayment_yesaccount']
                                -$list[$key]['borrowMgrFee']-$list[$key]['borrowAward']-$list[$key]['vipMoney']-$list[$key]['feeSystem'];
                             
                            $list[$key]['total2'] = $list[$key]['reMoney2'] + 0.9*$list[$key]['interestYes']
                                + $list[$key]['awardAdd'] + $list[$key]['invite_money'] + $list[$key]['accountBorrow'] - $list[$key]['txTotal'] - $list[$key]['repayment_yesaccount']
                                -$list[$key]['borrowMgrFee']-$list[$key]['borrowAward']-$list[$key]['vipMoney']-$list[$key]['feeSystem'];
                            
                        }
                $list = $list?$list:array();
		
		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	function GetUsersMoneyCheckListForExcel($data = array()){
		global $mysql;
		//$mysql->db_query("update {user} set email_status=1 where user_id=426");
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";	

		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}

		$sql = "select SELECT from {account} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				$_sql ORDER LIMIT";
		$_select = "p1.*,p2.username,p2.realname";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $_limit), $sql));
			foreach ($list as $key => $value){
                            $user_id = $value["user_id"];
                            //1)资金总额
                            $list[$key]['total'] = round($value["total"],2);
                            //2)可用资金
                            $list[$key]['use_money'] = round($value["use_money"],2);
                            //3)冻结资金
                            $list[$key]['no_use_money'] = round($value["no_use_money"],2);
                            //4)待收资金(1)
                            $list[$key]['collection'] = round($value["collection"],2);
                            //5)待收资金(2)
                            $sql = "Select sum(repay_account) as repay_account From {borrow_collection} where tender_id in(select id from {borrow_tender} where  borrow_id in(Select id from {borrow} where status=3 ) and user_id={$user_id}) and status=0  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['collection2'] = round($result['repay_account'],2);
                            //6)充值资金(1)
                            $sql = "Select sum(money) as reMoney from {account_recharge} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney'] = round($result['reMoney'],2);
                            //7充值资金(2)
                            $sql = "Select sum(money) as reMoney2 from {account_log} where user_id={$user_id} and type='recharge'";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney2'] = round($result['reMoney2'],2);
                            //8)其中：线上
                            $sql = "Select sum(money) as reMoney_1 from {account_recharge} where user_id={$user_id} and status=1 and type=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_1'] = round($result['reMoney_1'],2);
                            //9)其中：线下1
                            $sql = "Select sum(money) as reMoney_2 from {account_recharge} where user_id={$user_id} and status=1 and type=2";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_2'] = round($result['reMoney_2'],2);
                            //10)其中：线下2
                            $sql = "Select sum(money) as reMoney_3 from {account_recharge} where user_id={$user_id} and status=1 and type=0";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_3'] = round($result['reMoney_3'],2);
                            //11)成功提现金额
                            $sql = "Select sum(total) as txTotal from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txTotal'] = round($result['txTotal'],2);
                            //12)提现实际到账
                            $sql = "Select sum(credited) as txCredited from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txCredited'] = round($result['txCredited'],2);
                            //13)提现费用
                            $sql = "Select sum(fee) as txFee from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txFee'] = round($result['txFee'],2);
                            //14)投标奖励金额
                            $sql = "Select sum(money) as awardAdd from {account_log} where user_id={$user_id} and type='award_add'";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['awardAdd'] = round($result['awardAdd'],2);
                            //15)投标已收资金
                            $sql = "Select sum(repay_yesaccount) as repay_yesaccount From {borrow_collection} where tender_id in(select id from {borrow_tender} where borrow_id not in(Select id from {borrow} where status=5) and user_id={$user_id})  and status=1  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['collecdMoney'] = round($result['repay_yesaccount'],2);
                            //16)投标已赚利息
                            $sql = "Select sum(interest) as interestYes From {borrow_collection} where tender_id in(Select id from {borrow_tender} where user_id={$user_id} )  and status=1 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['interestYes'] = round($result['interestYes'],2);
                            //17)投标待收利息
                            $sql = "Select sum(interest) as interestWait From {borrow_collection} where tender_id in(Select id from {borrow_tender} where user_id={$user_id} and borrow_id in(Select id from {borrow} where  status=3) ) and status=0 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['interestWait'] = round($result['interestWait'],2);
                            //19)借款总金额
                            $sql = "Select sum(account) as accountBorrow From {borrow} where user_id={$user_id} and status=3 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['accountBorrow'] = round($result['accountBorrow'],2);
                            //19)借款标奖励
                            $sql = "Select sum(account*funds*0.01) as award1 from {borrow} where funds >0 and award=2 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $borrowAward1 = round($result['award1']);
                            
                            $sql = "Select sum(part_account) as award2 from {borrow} where part_account >0 and award=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $borrowAward2 = round($result['award2']);
                            
                            $list[$key]['borrowAward'] = round(($borrowAward1+$borrowAward2),2);
                            
                            //19)借款管理费
                            $sql = "Select sum(account*0.5*0.01*time_limit) as bowFee1 from {borrow} where is_fast=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee1 = $result['bowFee1'];
                            
                            $sql = "Select sum(account*0.2*0.01*time_limit) as bowFee2 from {borrow} where is_jin=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee2 = $result['bowFee2'];
                            
                            $sql = "Select sum(account*0.5*0.01*time_limit) as bowFee3 from {borrow} where (is_jin != 1 && is_mb != 1 && is_fast != 1 && is_vouch != 1) and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee3 = $result['bowFee3'];
                            
                            $list[$key]['borrowMgrFee'] = round(($bowFee1+$bowFee2+$bowFee3),2);
                            
                            //19)待还金额
                            $sql = " Select sum(repayment_account) as repayment_account From {borrow_repayment} where status=0 and borrow_id  in(Select id from {borrow} where user_id={$user_id} and status!=5)  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['waitMoney'] = round($result['repayment_account'],2);
                            //20)借款已还金额（含利息）
                            $sql = "Select sum(interest) as repayment_yesaccount From {borrow_repayment} where borrow_id in(select id from {borrow} where  user_id={$user_id} and status=3) and status=1 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['repayment_yesaccount'] = round($result['repayment_yesaccount'],2);
                            //22)系统扣费
                            $sql = "Select sum(money) as feeSystem from {account_log} where user_id={$user_id} and type in('scene_account','vouch_advanced','borrow_kouhui','account_other')";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['feeSystem'] = round($result['feeSystem'],2);
                             //23)推广奖励vip提成
                            $sql = "Select sum(invite_money) as invite_money From {user} where invite_userid={$user_id} ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['invite_money'] = round($result['invite_money'],2);
                            //24)VIP扣费
                            $sql = "Select sum(money) as vipMoney from {account_log} where user_id={$user_id} and type='vip' and (remark='扣除VIP会员费(扣除VIP冻结金额)' or remark='扣除VIP会员费')";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['vipMoney'] = round($result['vipMoney'],2);
                            
                            //25)账户总额1    
                            $list[$key]['total1'] = $list[$key]['reMoney'] + $list[$key]['interestWait'] + 0.9*$list[$key]['interestYes']
                                + $list[$key]['awardAdd'] + $list[$key]['invite_money'] + $list[$key]['accountBorrow'] - $list[$key]['txTotal'] - $list[$key]['repayment_yesaccount']
                                -$list[$key]['borrowMgrFee']-$list[$key]['borrowAward']-$list[$key]['vipMoney']-$list[$key]['feeSystem'];
                             
                            $list[$key]['total2'] = $list[$key]['reMoney2'] + $list[$key]['interestWait'] + 0.9*$list[$key]['interestYes']
                                + $list[$key]['awardAdd'] + $list[$key]['invite_money'] + $list[$key]['accountBorrow'] - $list[$key]['txTotal'] - $list[$key]['repayment_yesaccount']
                                -$list[$key]['borrowMgrFee']-$list[$key]['borrowAward']-$list[$key]['vipMoney']-$list[$key]['feeSystem'];
                            
                        }
			return $list;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
                foreach ($list as $key => $value){
                            $user_id = $value["user_id"];
                            //1)资金总额
                            $list[$key]['total'] = round($value["total"],2);
                            //2)可用资金
                            $list[$key]['use_money'] = round($value["use_money"],2);
                            //3)冻结资金
                            $list[$key]['no_use_money'] = round($value["no_use_money"],2);
                            //4)待收资金(1)
                            $list[$key]['collection'] = round($value["collection"],2);
                            //5)待收资金(2)
                            $sql = "Select sum(repay_account) as repay_account From {borrow_collection} where tender_id in(select id from {borrow_tender} where  borrow_id in(Select id from {borrow} where status=3 ) and user_id={$user_id}) and status=0  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['collection2'] = round($result['repay_account'],2);
                            //6)充值资金(1)
                            $sql = "Select sum(money) as reMoney from {account_recharge} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney'] = round($result['reMoney'],2);
                            //7充值资金(2)
                            $sql = "Select sum(money) as reMoney2 from {account_log} where user_id={$user_id} and type='recharge'";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney2'] = round($result['reMoney2'],2);
                            //8)其中：线上
                            $sql = "Select sum(money) as reMoney_1 from {account_recharge} where user_id={$user_id} and status=1 and type=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_1'] = round($result['reMoney_1'],2);
                            //9)其中：线下1
                            $sql = "Select sum(money) as reMoney_2 from {account_recharge} where user_id={$user_id} and status=1 and type=2";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_2'] = round($result['reMoney_2'],2);
                            //10)其中：线下2
                            $sql = "Select sum(money) as reMoney_3 from {account_recharge} where user_id={$user_id} and status=1 and type=0";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['reMoney_3'] = round($result['reMoney_3'],2);
                            //11)成功提现金额
                            $sql = "Select sum(total) as txTotal from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txTotal'] = round($result['txTotal'],2);
                            //12)提现实际到账
                            $sql = "Select sum(credited) as txCredited from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txCredited'] = round($result['txCredited'],2);
                            //13)提现费用
                            $sql = "Select sum(fee) as txFee from {account_cash} where user_id={$user_id} and status=1";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['txFee'] = round($result['txFee'],2);
                            //14)投标奖励金额
                            $sql = "Select sum(money) as awardAdd from {account_log} where user_id={$user_id} and type='award_add'";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['awardAdd'] = round($result['awardAdd'],2);
                            //15)投标已收资金
                            $sql = "Select sum(repay_yesaccount) as repay_yesaccount From {borrow_collection} where tender_id in(select id from {borrow_tender} where borrow_id not in(Select id from {borrow} where status=5) and user_id={$user_id})  and status=1  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['collecdMoney'] = round($result['repay_yesaccount'],2);
                            //16)投标已赚利息
                            $sql = "Select sum(interest) as interestYes From {borrow_collection} where tender_id in(Select id from {borrow_tender} where user_id={$user_id} )  and status=1 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['interestYes'] = round($result['interestYes'],2);
                            //17)投标待收利息
                            $sql = "Select sum(interest) as interestWait From {borrow_collection} where tender_id in(Select id from {borrow_tender} where user_id={$user_id} and borrow_id in(Select id from {borrow} where  status=3) ) and status=0 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['interestWait'] = round($result['interestWait'],2);
                            //19)借款总金额
                            $sql = "Select sum(account) as accountBorrow From {borrow} where user_id={$user_id} and status=3 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['accountBorrow'] = round($result['accountBorrow'],2);
                            //19)借款标奖励
                            $sql = "Select sum(account*funds*0.01) as award1 from {borrow} where funds >0 and award=2 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $borrowAward1 = round($result['award1']);
                            
                            $sql = "Select sum(part_account) as award2 from {borrow} where part_account >0 and award=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $borrowAward2 = round($result['award2']);
                            
                            $list[$key]['borrowAward'] = round(($borrowAward1+$borrowAward2),2);
                            
                            //19)借款管理费
                            $sql = "Select sum(account*0.5*0.01*time_limit) as bowFee1 from {borrow} where is_fast=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee1 = $result['bowFee1'];
                            
                            $sql = "Select sum(account*0.2*0.01*time_limit) as bowFee2 from {borrow} where is_jin=1 and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee2 = $result['bowFee2'];
                            
                            $sql = "Select sum(account*0.5*0.01*time_limit) as bowFee3 from {borrow} where (is_jin != 1 && is_mb != 1 && is_fast != 1 && is_vouch != 1) and user_id={$user_id} and status=3";
                            $result = $mysql -> db_fetch_array($sql);
                            $bowFee3 = $result['bowFee3'];
                            
                            $list[$key]['borrowMgrFee'] = round(($bowFee1+$bowFee2+$bowFee3),2);
                            
                            //19)待还金额
                            $sql = " Select sum(repayment_account) as repayment_account From {borrow_repayment} where status=0 and borrow_id  in(Select id from {borrow} where user_id={$user_id} and status!=5)  ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['waitMoney'] = round($result['repayment_account'],2);
                            //20)借款已还利息）
                            $sql = "Select sum(interest) as repayment_yesaccount From {borrow_repayment} where borrow_id in(select id from {borrow} where  user_id={$user_id} and status=3) and status=1 ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['repayment_yesaccount'] = round($result['repayment_yesaccount'],2);
                            //22)系统扣费
                            $sql = "Select sum(money) as feeSystem from {account_log} where user_id={$user_id} and type in('scene_account','vouch_advanced','borrow_kouhui','account_other')";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['feeSystem'] = round($result['feeSystem'],2);
                             //23)推广奖励vip提成
                            $sql = "Select sum(invite_money) as invite_money From {user} where invite_userid={$user_id} ";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['invite_money'] = round($result['invite_money'],2);
                            //24)VIP扣费
                            $sql = "Select sum(money) as vipMoney from {account_log} where user_id={$user_id} and type='vip' and (remark='扣除VIP会员费(扣除VIP冻结金额)' or remark='扣除VIP会员费')";
                            $result = $mysql -> db_fetch_array($sql);
                            $list[$key]['vipMoney'] = round($result['vipMoney'],2);
                            
                            //25)账户总额1    
                            $list[$key]['total1'] = $list[$key]['reMoney'] + $list[$key]['interestWait'] + 0.9*$list[$key]['interestYes']
                                + $list[$key]['awardAdd'] + $list[$key]['invite_money'] + $list[$key]['accountBorrow'] - $list[$key]['txTotal'] - $list[$key]['repayment_yesaccount']
                                -$list[$key]['borrowMgrFee']-$list[$key]['borrowAward']-$list[$key]['vipMoney']-$list[$key]['feeSystem'];
                             
                            $list[$key]['total2'] = $list[$key]['reMoney2'] + $list[$key]['interestWait'] + 0.9*$list[$key]['interestYes']
                                + $list[$key]['awardAdd'] + $list[$key]['invite_money'] + $list[$key]['accountBorrow'] - $list[$key]['txTotal'] - $list[$key]['repayment_yesaccount']
                                -$list[$key]['borrowMgrFee']-$list[$key]['borrowAward']-$list[$key]['vipMoney']-$list[$key]['feeSystem'];
                            
                        }
                $list = $list?$list:array();
		
		
                foreach ($list as $key => $value){                       
			$_data[$key] = array($value['username'],$value['total'],$value['use_money'],$value['no_use_money'],$value['collection'],$value['collection2'],$value["reMoney"],$value["reMoney2"],$value["reMoney_1"],$value["reMoney_2"],$value["reMoney_3"],$value["txTotal"],$value["txCredited"],$value["txFee"],$value["awardAdd"],$value["collecdMoney"],$value["interestYes"],$value["interestWait"],$value["accountBorrow"],$value["borrowAward"],$value["borrowMgrFee"],$value["waitMoney"],$value["repayment_yesaccount"],$value["feeSystem"],$value["invite_money"],$value["vipMoney"],$value["total1"],$value["total2"]);
		}
                 
		return $_data;
		
	}   
        function GetAccListForExport($data = array()){
		global $mysql;
                include_once(ROOT_PATH."modules/borrow/borrow.class.php");
		$_sql = "where 1=1 ";	
			 
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p2.user_id = '{$data['user_id']}'";
		}
		
		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}
		
		
		$sql = "select SELECT from {account} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				$_sql ORDER LIMIT";
		$_select = "p1.total,p1.use_money,p1.no_use_money,p1.collection,p2.username,p2.realname,p2.user_id";
		

                $_limit = "";
		$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $_limit), $sql));

                foreach ($list as $key => $value){
			//取用户的待还金额

                        if(isset($value["user_id"]) && $value["user_id"]!=""){
                            $_result_wait = borrowClass::GetWaitPayment(array("user_id"=>$value["user_id"]));
                        }
                        $jinMoney = $value["use_money"] + $value["collection"] - $_result_wait["wait_payment"];
                        
			$_data[$key] = array($key+1,$value['username'],$value['realname'],$value['total'],$value['use_money'],$value['no_use_money'],$value['collection'],$_result_wait["wait_payment"],$jinMoney);
		}
                 
		return $_data;

	}
	/*
 function GetAccListTJForExport($data = array()){
		global $mysql;
                include_once(ROOT_PATH."modules/borrow/borrow.class.php");
		$_sql = "where 1=1 ";	
			 
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p1.user_id = '{$data['user_id']}'";
		}
		
		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p1.username like '%{$data['username']}%'";
		}
		
		
		$sql = "select SELECT from {account_tj} as p1 
				$_sql ORDER LIMIT";
		$_select = "p1.*";
		

        $_limit = "";
		$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $_limit), $sql));

        foreach ($list as $key => $value){
				$_data[$key] = array($key+1,$value['username'],$value['realname'],$value['total'],$value['use_money'],$value['no_use_money'],$value['collection'],$value["wait_repayMoney"],$value["jin_money"]);
		}
                 
		return $_data;

	}
	*/
	/**
	*  好友提成 导出
	* Author :LiuYY 2012-06-08
	*/
	function GetTichengForExport($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";	
			 
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p2.user_id = '{$data['user_id']}'";
		}
		
		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}
		
		
		$sql = "select SELECT from view_tc_backend  $_sql GROUP ORDER LIMIT";
		$_select = "*";
		

        $_limit = "";
		$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT','GROUP', 'ORDER', 'LIMIT'), array($_select,'', 'order by addtimes desc', $_limit), $sql));

		foreach ($list as $key => $value){		
			$_data[$key] = array($key+1,"`".$value['addtimes'],$value['usernames'],$value['money']);
		}
                 
		return $_data;

	}
        
     /**
	 * 参考提现列表
	 *
	 * @return Array
	 */
	function GetCKList($data = array()){
		global $mysql;
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where 1=1 ";	
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p2.user_id = '{$data['user_id']}'";
		}
		if (isset($data['username']) && $data['username']!=""){
			$data['username'] = urldecode($data['username']);
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}
		$sql = "select SELECT from {account} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id 
                 left join {user_amount} as p3 on p1.user_id=p3.user_id
				$_sql ORDER LIMIT";
		$_select = "p1.*,p2.username,p2.realname,p3.*";
			
		//是否显示全部的信息

		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $_limit), $sql));
			
			return $list;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	/*
	 * 获取用户的账余额
	 * 还款，扣费使用，提高性能,只查account表
	 */
	public static function GetOneAccount($data=array()){
		global $mysql;
		$sql = 'select id,user_id,total,use_money,no_use_money,collection from `{account}` where user_id='.$data['user_id'];
		$result = $mysql->db_fetch_array($sql);
		if($result == false){
			$sql="select user_id from {user} where user_id='{$data['user_id']}'";
			$result = $mysql->db_fetch_array($sql);
			if($result == false){
		
			}else{
				$sql = "insert into `{account}` set user_id = '{$data['user_id']}'";
				$mysql ->db_query($sql);
				$result = self::GetOneAccount($data);
			}
		}
		return $result;
	}
	/**
	 * 查看
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetOne($data = array()){
		global $mysql;
		$user_id = $data['user_id'];
		$sql = "select p2.username,p3.*,p1.* from `{account}` as p1 
				  left join {user} as p2 on p1.user_id=p2.user_id
				  left join {account_bank} as p3 on p1.user_id=p3.user_id
				  where p1.user_id=$user_id
				";
		$result = $mysql->db_fetch_array($sql);
		if ($result == false){
                        //add by jackfeng 2012-06-30
                        $sql="select * from {user} where user_id='{$user_id}'";
                        $result = $mysql->db_fetch_array($sql);
                        if($result == false){
                            //不处理
                        }else{
                            $sql = "insert into `{account}` set user_id = '{$user_id}'";
                            $mysql ->db_query($sql);
                            $result = self:: GetOne($data);
                        }
		}
		return $result;
	}
	public static function GetUserLog($data = array()){
		global $mysql;
		$user_id = $data['user_id'];
		$sql = "select type,sum(money) as num from `{account_log}` where user_id = '{$user_id}' group by type ";
		$result = $mysql->db_fetch_arrays($sql);
		$_result = "";
		foreach ($result as $key => $value){
			$_result[$value['type']] = $value['num'];
		}
		$_result['tender_dj'] = $_result['tender']-$_result['invest_false'];
		$result = self::GetOneAccount(array('user_id'=>$user_id));
		//$sql = "select * from `{account}` where user_id = '{$user_id}' ";
		//$result = $mysql->db_fetch_array($sql);
		if($result!=false){
			foreach ($result as $key => $value){
				$_result[$key] = $value;
			}
		}
		
		//借款额度
		$sql = "select borrow_amount from `{user_cache}` where user_id = {$user_id} ";
		$result = $mysql -> db_fetch_array($sql);
		$_result['amount'] = $result['borrow_amount'];

		//充值的统计
		$sql = "select type,sum(money) as num from `{account_recharge}` where user_id = '{$user_id}' and status=1 group by type ";
		$result = $mysql->db_fetch_arrays($sql);
		foreach ($result as $key => $value){
			if ( $value['type']==0){
				$key = "recharge_shoudong";
			}elseif ( $value['type']==1){
				$key = "recharge_online";
			}else{
				$key = "recharge_downline";
			}
			$_result[$key] = $value['num'];
		}
		$sql = "select sum(money) as num,count(id) as times from `{account_recharge}` where user_id = '{$user_id}' and status=1  ";
		$result = $mysql->db_fetch_array($sql);
		$_result['recharge_success'] = $result['num'];
		$_result['recharge_success_times'] = $result['times'];
		$_result['recharge'] =  $result['num'];
		$sql = "select sum(money) as num from `{account_recharge}` where user_id = '{$user_id}' and status=0  ";
		$result = $mysql->db_fetch_array($sql);
		$_result['recharge_false'] = $result['num'];
		
		//提现的统计
		$sql = "select status,sum(total) as num,sum(credited) as cnum,sum(fee) as fnum,count(id) as times from `{account_cash}` where user_id = '{$user_id}'  group by status ";
		$result = $mysql->db_fetch_arrays($sql);
		foreach ($result as $key => $value){
			if ( $value['status']==2){
				$key = "cash_false";
			}elseif ( $value['status']==1){
				$key = "cash_success";
			}elseif ( $value['status']==3){
				$key = "cash_cancel";
			}else{
				$key = "cash_wait";
			}
			$_result[$key] = array("money"=>$value['num'],"credited"=>$value['cnum'],"fee"=>$value['fnum'],"times"=>$value['times']);
		}
		return $_result;
	}
	
	
	function ActionAccount($data=array()){
		global $mysql;	
		if (isset($data['user_id'])){
			$user_id = $data['user_id'];
			unset($data['user_id']);
			$money=$data['money'];
			$mytype=array("recharge"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"fee"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"vip"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"vip2"=>"`total`=`total`-$money,`no_use_money`=`no_use_money`-$money",
					"ticheng"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"vip3"=>"`use_money`=`use_money`-$money,`no_use_money`=`no_use_money`+$money",
					"recharge_success"=>"`total`=`total`-$money,`no_use_money`=`no_use_money`-$money",
					"recharge_false"=>"`use_money`=`use_money`+$money,`no_use_money`=`no_use_money`-$money",
					"realname"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"video"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"vip4"=>"`use_money`=`use_money`+$money,`no_use_money`=`no_use_money`-$money",
					"tender"=>"`use_money`=`use_money`-$money,`no_use_money`=`no_use_money`+$money",
					"borrow_frost"=>"`use_money`=`use_money`+$money,`no_use_money`=`no_use_money`-$money",
					"invest_false"=>"`use_money`=`use_money`+$money,`no_use_money`=`no_use_money`-$money",
					"repayment"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"invest_repayment"=>"`use_money`=`use_money`+$money,`collection`=`collection`-$money",
					"tender_mange"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"late_repayment"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"late_collection"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"system_repayment"=>"`use_money`=`use_money`+$money,`collection`=`collection`-$money",
					"late_rate"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"invest"=>"`total`=`total`-$money,`no_use_money`=`no_use_money`-$money",
					"collection"=>"`total`=`total`+$money,`collection`=`collection`+$money",
					"borrow_success"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"borrow_fee"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"vouch_award"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"vouch_awardpay"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"invest_false"=>"`no_use_money`=`no_use_money`-$money,`use_money`=`use_money`+$money",
					"award_add"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"award_lower"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"scene_account"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"vouch_advanced"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"borrow_kouhui"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"cash_frost"=>"`use_money`=`use_money`-$money,`no_use_money`=`no_use_money`+$money",
					"cash_cancel"=>"`use_money`=`use_money`+$money,`no_use_money`=`no_use_money`-$money",
					// 增加资金操作类型
					"margin"=>"`use_money`=`use_money`-$money,`no_use_money`=`no_use_money`+$money",
					"tixian_fee"=>"`total`=`total`-$money,`no_use_money`=`no_use_money`-$money",
					"borrow_fee_forst"=>"`use_money`=`use_money`-$money,`no_use_money`=`no_use_money`+$money",
					"borrow_fee_unforst"=>"`use_money`=`use_money`+$money,`no_use_money`=`no_use_money`-$money",
					// 增加资金操作类型
					"smssq"=>"`use_money`=`use_money`-$money,`total`=`total`-$money",
					"recharge_reward"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"registerjl"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"account_other"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					//	增加资金操作类型
					"zqgm_false"=>"`use_money`=`use_money`+$money,`no_use_money`=`no_use_money`-$money",
					"zqgm"=>"`use_money`=`use_money`-$money,`no_use_money`=`no_use_money`+$money",
					"zqgm_true"=>"`total`=`total`-$money,`no_use_money`=`no_use_money`-$money",
					"zqgm_collection"=>"`total`=`total`+$money,`collection`=`collection`+$money",
					"zqcs_true"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"zqcs_ksds"=>"`total`=`total`-$money,`collection`=`collection`-$money",
					"hxcash_success"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"risk_fee"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"join_finance"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"out_finance"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					"zhuanz_out"=>"`total`=`total`-$money,`use_money`=`use_money`-$money",
					"zhuanz_in"=>"`total`=`total`+$money,`use_money`=`use_money`+$money",
					);
			
			$sql = "select user_id from {account} where user_id=$user_id";
			$result = $mysql->db_fetch_array($sql);
			if ($result == false){
				$sql="select user_id from {user} where user_id='{$user_id}'";
				$result = $mysql->db_fetch_array($sql);
				if($result == false){
					return false;
				}else{
					$sql = "insert into `{account}` set `user_id` = '$user_id',total=0,user_money=0,no_use_money=0,collection=0";
				}
			}
			$sql = "update `{account}` set ";
			foreach($mytype as $key => $value){
				if ($key==$data['type']){
					$sql.=$value;
				}
			}
			$sql .= " where user_id=$user_id";
			return $mysql->db_query($sql);
		}else{
			return false;
		}
	}
	function ActionAccount_Add($data=array()){
		global $mysql;
		
		if (isset($data['user_id'])){
			$user_id = $data['user_id'];
		
			unset($data['user_id']);
			$sql = "select * from {account} where user_id=$user_id";
			$result = $mysql->db_fetch_array($sql);
			if ($result == false){
				
				//add by jackfeng 2012-06-30
				$sql="select * from {user} where user_id='{$user_id}'";
				$result = $mysql->db_fetch_array($sql);
				if($result == false){
					//不处理
				}else{
					$sql = "insert into `{account}` set `user_id` = '$user_id'";
					foreach($data as $key => $value){
						$sql .= ",`$key` = '$value'";
					}
				}
			}else{
				$sql = "update `{account}` set `user_id` = '$user_id'";
				foreach($data as $key => $value){
					$sql .= ",`$key` = '$value'";
				}
				$sql .= " where user_id=$user_id";
			}
			return $mysql->db_query($sql);
		}else{
			return self::ERROR;
		}
		
	}
		
	/**
	 * 查看银行信息
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetBankOne($data = array()){
		global $mysql;
		$user_id = $data['user_id'];
		$sql = "select p1.username,p1.email,p1.realname,p1.paypassword,p2.*,p3.* from {user} as p1 
				  left join {account_bank} as p2 on p1.user_id=p2.user_id 
				  left join {account} as p3 on p3.user_id=p1.user_id
				  where p1.user_id=$user_id
				";
		return $mysql->db_fetch_array($sql);
	}
	
	/**
	 * 添加或修改银行账号
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function ActionBank($data = array()){
		global $mysql;
        $user_id = isset($data['user_id'])?$data['user_id']:"";
		if (empty($user_id)) return self::ERROR;
       
		$sql = "select * from {account_bank} where user_id = $user_id";
		$result = $mysql->db_fetch_array($sql);
		if ($result == false){
			$sql = "insert into `{account_bank}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
			foreach($data as $key => $value){
				$sql .= ",`$key` = '$value'";
			}
		}else{
			$sql = "update `{account_bank}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
			foreach($data as $key => $value){
				$sql .= ",`$key` = '$value'";
			}
			$sql .= " where user_id=$user_id";
		}
        return $mysql->db_query($sql);
	}
	
	/**
	 * 添加提现记录
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AddCash($data = array()){
		global $mysql;
        $user_id = isset($data['user_id'])?$data['user_id']:"";
		if (empty($user_id)) return self::ERROR;
       
		$sql = "insert into `{account_cash}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        return $mysql->db_query($sql);
	}
	
	/**
	 * 添加资金使用记录
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AddLog($data = array()){
		global $mysql;
        $user_id = isset($data['user_id'])?$data['user_id']:"";
		if (empty($user_id)) return false;
		$sql = "insert into `{account_log}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$result = $mysql->db_query($sql);
		if($result==false){
			return false;
		}
		$account['user_id'] = $user_id;
		//$account['total'] = $data['total'];
		$account['money']=$data['money'];
		$account['type']=$data['type'];
		//$account['use_money']=$data['use_money'];
		//$account['no_use_money']=$data['no_use_money'];
		//$account['collection']=$data['collection'];
		$result = self::ActionAccount($account);
        return $result;
	}
	/**
	 * 添加资金使用记录
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AddLog_add($data = array()){
		global $mysql;
        $user_id = isset($data['user_id'])?$data['user_id']:"";
		if (empty($user_id)) return self::ERROR;
		$account['user_id'] = $user_id;
		$account['total'] = $total;
		if(isset($data['use_money'])){
			$account['use_money'] = $data['use_money'];
		}
		if(isset($data['no_use_money'])){
			$account['no_use_money'] = $data['no_use_money'];
		}
		if(isset($data['collection'])){
			$account['collection'] = $data['collection'];
		}
		$result = self::ActionAccount($account);
       	$total = $data['total'];
		$sql = "insert into `{account_log}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		$result = $mysql->db_query($sql);

        return ;
	}
		
	/**
	 * 修改
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Update($data = array()){
		global $mysql;
		$user_id = $data['user_id'];
        if ($data['user_id'] == "") return self::ERROR;
		
		$_sql = "";
		$sql = "update `{account}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where user_id = '$user_id'";
        return $mysql->db_query($sql);
	}
	
	
	/**
	 * 提现记录
	 *
	 * @return Array
	 */
	function GetCashList($data = array()){
		global $mysql;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$username = empty($data['username'])?"":$data['username'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$dotime1 = empty($data['dotime1'])?"":$data['dotime1'];
        $dotime2 = empty($data['dotime2'])?"":$data['dotime2'];
		//$_sql = "where p1.status!=-1";
        $_sql = ' where 1=1';
		if (!empty($user_id)){
			$_sql .= " and p2.user_id = $user_id";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}
		if (isset($data['status']) && $data['status']!=""){
			$_sql .= " and p1.status = '{$data['status']}' ";
		}
		if (!empty($dotime1)){
			$_sql .= " and p1.addtime  >= ".get_mktime($dotime1.' 0:0:0');
		}
		if (!empty($dotime2)){
			$_sql .= " and p1.addtime  <= ".get_mktime($dotime2.' 23:59:59');
		}
		if(!empty($data['account'])){
			$_sql .= " and p1.account='".$data['account']."'";
		}
		$sql = "select SELECT from {account_cash} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				 left join {linkage} as p3 on p1.bank=p3.id
				$_sql ORDER LIMIT";
		$_select = "p1.*,p2.username,p2.realname,p3.name as bank_name";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$list =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $_limit), $sql));
			return $list;
		}
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.username,p2.realname,p3.name as bank_name', 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
	
	
		
	/**
	 * 查看
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetCashOne($data = array()){
		global $mysql;
		$id = isset($data['id'])?$data['id']:'';
		$user_id = isset($data['user_id'])?$data['user_id']:'';
		$trade_no = isset($data['trade_no'])?$data['trade_no']:'';
		if($trade_no==''){
			if (empty($id) && empty($user_id)) return self::ERROR;
		}
		 
		 $_sql = "where 1=1 ";		 
		if (!empty($id)){
			$_sql .= " and p1.id = '$id'";
		}	 
		if (!empty($user_id)){
			$_sql .= " and p1.user_id = '$user_id'";
		}
		if(!empty($trade_no)){
			$_sql .= " and p1.trade_no = '$trade_no'";
		}
		
		$sql = "select p1.* ,p2.username,p2.email,p3.name as bank_name,p4.username as verify_username from {account_cash} as p1 
				  left join {user} as p2 on p1.user_id=p2.user_id
				  left join {linkage} as p3 on p1.bank=p3.id
				  left join {user} as p4 on p1.verify_userid=p4.user_id
				  {$_sql}
				";
		return $mysql->db_fetch_array($sql);
	}
	
	
	
	
	/**
	 * 审核提现记录
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function UpdateCash($data = array()){
		global $mysql,$_G;
		$id = isset($data['id'])?$data['id']:'';
		$trade_no = isset($data['trade_no'])?$data['trade_no']:'';
		if (empty($id) && empty($trade_no)) return self::ERROR;
		$result = self::GetCashOne(array("id"=>$data['id'],"trade_no"=>$trade_no,"user_id"=>$data['user_id']));
		$data['user_id'] = $result['user_id'];
		$id = $result['id'];
		$hongbao = $result['hongbao'];
		if($result['status'] != 0){
			return "审核失败！当前提现申请状态非原始状态，当前状态为:".$result['status']."!(0--初始状态 1--处理成功 2--处理失败 3--用户取消申请)";
		}else{
			mysql_query("start transaction");
			if ($data['status']==1){
				//liukun add for bug 318 begin
				$account_result =  self::GetOneAccount(array("user_id"=>$data['user_id']));
				$log['user_id'] = $data['user_id'];
				$log['type'] = "recharge_success";
				$log['money'] = $data['credited'];
				$log['total'] = $account_result['total'] - $log['money'];
				$log['use_money'] = $account_result['use_money'] ;
				$log['no_use_money'] = $account_result['no_use_money'] - $log['money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = "0";
				$log['remark'] = "提现成功,提现ID:{$result['id']}";
				$result_addlog = self::AddLog($log);
				if($account_result==false || $result_addlog==false){
					mysql_query("rollback");
					return false;
				}
				$sendSMS[] = array('user_id'=>$data['user_id'],'content'=>"您的提现{$log['money']}元申请通过了审核,即将安排财务处理,请注意查收！。");
				
				$real_tixian_fee = $data['fee'] - $result['hongbao'];
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$data['user_id']));
				$log['user_id'] = $data['user_id'];
				$log['type'] = "tixian_fee";
				$log['money'] = $real_tixian_fee;
				$log['total'] = $account_result['total'] - $log['money'];
				$log['use_money'] = $account_result['use_money'];
				$log['no_use_money'] = $account_result['no_use_money'] - $log['money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = "0";
				$log['remark'] = "扣除提现手续费,提现ID:{$result['id']}";
				$result_addlog = accountClass::AddLog($log);
				if($account_result==false || $result_addlog==false){
					mysql_query("rollback");
					return false;
				}
				//liukun add for bug 318 end

				//提醒设置
				$remind['nid'] = "cash_yes";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $data['user_id'];
				$remind['title'] = "您的提现{$result['total']}元申请“通过”了审核,正在打款中";
				$remind['content'] = "您的提现{$result['total']}元申请“通过”了审核,正在打款中";
				$remind['content'] .= "<br>提现总金额：￥{$result['total']}";
				$remind['content'] .= "<br>提现到帐金额：￥{$data['credited']}";
				$remind['content'] .= "<br>提现手续费：￥{$data['fee']}";
				$remind['content'] .= "<br>提现银行：{$result['branch']}";
				$remind['content'] .= "<br>审核时间：".date("Y-m-d",time());
				$remind['type'] = "cash";
				$sendRemind[] = $remind;
			}elseif ($data['status']==2){
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$data['user_id']));
				$log['user_id'] = $data['user_id'];
				$log['type'] = "recharge_false";
				$log['money'] = $result['total'];
				$log['total'] = $account_result['total'];
				$log['use_money'] = $account_result['use_money'] + $log['money'];
				$log['no_use_money'] = $account_result['no_use_money']- $log['money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = "0";
				$log['remark'] = "提现失败,提现ID:{$result['id']}";
				$result = accountClass::AddLog($log);
				//add by jackfeng 2012-7-9 提现失败 红包返回
				$sql = "update `{user}` set hongbao = hongbao + ".$hongbao." where user_id=".$log['user_id'];
				$re = $mysql->db_query($sql);
				if($account_result==false || $result==false || $re==false){
					mysql_query("rollback");
					return false;
				}
				$sendSMS[] = array('user_id'=>$data['user_id'],'content'=>"您的提现{$log['money']}元申请没有通过审核,请登录网站了解详情！。");
				//提醒设置
				$remind['nid'] = "cash_no";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $data['user_id'];
				$remind['title'] = "您的提现{$result['total']}元申请“没有通过”审核,请联系财务了解详情";
				$remind['content'] = date("Y-m-d",time())."提现{$result['total']}元申请审核失败";
				$remind['type'] = "cash";
				$sendRemind[] = $remind;
			}
			$data['verify_userid'] = isset($data['verify_userid'])?$data['verify_userid']:$_G['user_id'];
			$data['verify_time'] = time();
			$user_id = $data['user_id'];
			$_sql = "";
			$sql = "update `{account_cash}` set ";
			foreach($data as $key => $value){
				$_sql[] .= "`$key` = '$value'";
			}
			$sql .= join(",",$_sql)." where id = '$id' and user_id='$user_id'";
			$re = $mysql->db_query($sql);
			if ($re==false){
				mysql_query("rollback");
				return false;
			}else{
				mysql_query("commit");
				foreach ($sendRemind as $remind){
					remindClass::sendRemind($remind);
				}
				foreach($sendSMS as $value){
					sendSMS($value['user_id'],$value['content'],1);
				}
				return true;
			}
		}
	}
	
	/**
	 * 充值记录
	 *
	 * @return Array
	 */
	function GetRechargeList($data = array()){
		global $mysql;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$username = empty($data['username'])?"":$data['username'];
        $status = empty($data['status'])?"":$data['status'];
        $dotime1 = empty($data['dotime1'])?"":$data['dotime1'];
        $dotime2 = empty($data['dotime2'])?"":$data['dotime2'];	
		$trade_no = empty($data['trade_no'])?"":$data['trade_no'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where 1=1 and p1.status != -1 ";		 
		if (!empty($user_id)){
			$_sql .= " and p2.user_id = $user_id";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}      
		if (!empty($dotime1)){
			$_sql .= " and p1.addtime  >= ".get_mktime($dotime1." 0:0:0");
		}
		if (!empty($dotime2)){
			$_sql .= " and p1.addtime  <= ".get_mktime($dotime2." 23:59:59");
		}
		if (!empty($trade_no)){
			$_sql .= " and p1.trade_no = '".$trade_no."'";
		}
		if(!empty($data['pertainbank'])){
			if($data['pertainbank']==-1){//线下充值
				$_sql .= ' and p1.type!=1';
			}else if($data['pertainbank']==-2){//网上充值
				$_sql .= ' and p1.type=1';
			}else if($data['pertainbank']==-3){//手动充值
				$_sql .= ' and p1.payment=0';
			}else if($data['pertainbank']>0){
				$_sql .= ' and p3.id='.$data['pertainbank'];
			}
		}
        if($status==-1){//未审核
        	$_sql .= ' and p1.status = 0';
        }else if ($status == 1){//审核成功
			$_sql .= ' and p1.status = 1';
		}else if ($status == 2){//审核失败
			$_sql .= ' and p1.status = 2';
		}else if ($status!=''){
			$_sql .= ' and p1.status in('.$status.')';
		}
		$sql = "select SELECT from {account_recharge} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				 left join {payment} as p3 on p1.payment=p3.id
				$_sql ORDER LIMIT";
                
		$_select = "p1.*,p1.money,p1.money-p1.fee as total,p2.username,p2.realname,p3.name as payment_name";	 
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
                   
			$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.addtime desc', $_limit), $sql));
			
                        return $result;
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();

		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
	/**
	 * 查看
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetRechargeOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";		 
		if (isset($data['id']) && $data['id']!=""){
			$_sql .= " and p1.id = {$data['id']}";
		} 
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p2.user_id = {$data['user_id']}";
		}
		if (isset($data['trade_no']) && $data['trade_no']!=""){
			$_sql .= " and p1.trade_no = {$data['trade_no']}";
		}
		$sql = "select p1.*,p1.money-p1.fee as total,p2.username,p2.email,p3.name as payment_name,p4.username as verify_username,p5.total as user_total,p5.use_money as user_use_money,p5.no_use_money as  user_no_user_money from {account_recharge} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				 left join {payment} as p3 on p1.payment=p3.nid
				 left join {user} as p4 on p1.verify_userid=p4.user_id
				 left join {account} as p5 on p1.user_id=p5.user_id
				$_sql";
		return $mysql->db_fetch_array($sql);
	}
	/**
	 * 添加充值记录
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AddRecharge($data = array()){
		global $mysql;
        $user_id = isset($data['user_id'])?$data['user_id']:"";
		if (empty($user_id)) return self::ERROR;
		$result = $mysql->db_add("account_recharge", $data);
		return $result;
	}
	
	/**
	 * 充值审核
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function UpdateRecharge($data = array()){
		global $mysql,$_G;
		$id = $data['id'];
		if (empty($id)) return self::ERROR;
		$result = accountClass::GetRechargeOne(array("id"=>$id));
		if ($result['status']!=0){
			return "此充值已经审核，请不要重复审核。";
		}
		mysql_query("start transaction");
		if($data['recharge_type'] != 1){
			if ($data['status']==1){
				$account_result =  self::GetOneAccount(array("user_id"=>$result['user_id']));
				$log['user_id'] = $result['user_id'];
				$log['type'] = "recharge";
				$log['money'] = $result['money'];
				$log['total'] = $account_result['total']+$result['money'];
				$log['use_money'] =  $account_result['use_money']+$result['money'];
				$log['no_use_money'] =  $account_result['no_use_money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = "0";
				$log['remark'] = "账号充值,流水号:{$result['trade_no']}";
				$re = self::AddLog($log);
				if($account_result==false || $re==false){
					mysql_query("rollback");
					return false;
				}
				if($result['fee']!=0){
					$account_result =  self::GetOne(array("user_id"=>$result['user_id']));
					$log['user_id'] = $result['user_id'];
					$log['type'] = "fee";
					$log['money'] = $result['fee'];
					$log['total'] =$account_result['total']-$log['money'];
					$log['use_money'] = $account_result['use_money']-$log['money'];
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = "0";
					$log['remark'] = "充值手续费扣除,流水号:{$result['trade_no']}";
					$re = self::AddLog($log);
					if($account_result==false || $re==false){
						mysql_query("rollback");
						return false;
					}
				}
				//判断是否有现金奖励
				if($result['reward']>0){
					$account_result =  self::GetOneAccount(array("user_id"=>$result['user_id']));
					$log['user_id'] = $result['user_id'];
					$log['type'] = "recharge_reward";
					$log['money'] = $result['reward'];
					$log['total'] = $account_result['total']+$log['money'];
					$log['use_money'] =  $account_result['use_money']+$log['money'];
					$log['no_use_money'] =  $account_result['no_use_money'];
					$log['collection'] = $account_result['collection'];
					$log['to_user'] = "0";
					$log['remark'] = "账号充值奖励,流水号:{$result['trade_no']}";
					$re = self::AddLog($log);
					if($account_result==false || $re==false){
						mysql_query("rollback");
						return false;
					}
				}
				//判断是否有红包奖励
				if($result['hongbao']>0){
					$hongbao=$result['hongbao'];
					$sql = "update `{user}` set hongbao = hongbao + ".$hongbao." where user_id=".$result['user_id'];
					$re = $mysql->db_query($sql);
					if($re==false){
						mysql_query("rollback");
						return false;
					}
					$remind['nid'] = "recharge";
					$remind['sent_user'] = "0";
					$remind['receive_user'] = $result['user_id'];
					$remind['title'] = "线下充值奖励红包(".$hongbao.")元";
					$remind['content'] = "线下充值奖励红包(".$hongbao.")元";
					$remind['type'] = "recharge";
					$sendRemind[] = $remind;
				}
				//提醒设置
				$remind['nid'] = "recharge";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $result['user_id'];
				$remind['title'] = "您的账户成功充值{$result['money']}元";
				$remind['content'] = "您好，您已经于".date("Y-m-d",time())."成功充值{$result['money']}元,流水号:{$result['trade_no']}";
				$remind['type'] = "recharge";
				$sendRemind[] = $remind;
				$_username = $mysql->db_fetch_array("select username from {user} where user_id=".$result['user_id']);
				$sendSMS[] = array('user_id'=>$result['user_id'],'content'=>"尊敬的".$_username['username']."会员您好，已于".date("Y-m-d",time())."收到您的充值金额：{$result['money']}元。");
			}elseif ($data['status']==2){
				//提醒设置
				$remind['nid'] = "recharge";
				$remind['sent_user'] = "0";
				$remind['receive_user'] = $result['user_id'];
				$remind['title'] = "您的账户充值{$result['money']}元失败";
				$remind['content'] = date("Y-m-d",time())."充值{$result['money']}元审核失败,流水号:{$result['trade_no']}";
				$remind['type'] = "recharge";
				$sendRemind[] = $remind;
			}
		}
		unset($data['recharge_type']);
		$data['verify_userid'] = $_G['user_id'];
		$data['verify_time'] = time();
		$_sql = "";
		$sql = "update `{account_recharge}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where id = '$id'";
		$re = $mysql->db_query($sql);
		if($re==false){
			mysql_query("rollback");
			return false;
		}else{
			mysql_query("commit");
			foreach($sendRemind as $remind){
				remindClass::sendRemind($remind);
			}
			foreach($sendSMS as $value){
				sendSMS($value['user_id'],$value['content'],1);
			}
			return true;
		}
	}
	/**
	 * 资金使用记录
	 *
	 * @return Array
	 */
	function GetLogList($data = array()){
		global $mysql;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$username = empty($data['username'])?"":$data['username'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where 1=1 ";
		if (!empty($user_id)){
			$_sql .= " and p2.user_id = $user_id";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}
		if (isset($data['dotime1']) && $data['dotime1']!=""){
			$_sql .= " and p1.addtime >= '".strtotime($data['dotime1'].' 0:0:0')."'";
		}
		if (isset($data['dotime2']) && $data['dotime2']!=""){
			$_sql .= " and p1.addtime <= '".strtotime($data['dotime2'].' 23:59:59')."'";
		}
		if (isset($data['type']) && $data['type']!=""){
			$_sql .= " and p1.type = '".$data['type']."'";
		}
		if (isset($data['keywords']) && $data['keywords']!=''){
			$_sql .= " and p1.remark like '%".$data['keywords']."%'";
		}
		$_select = "p1.*,p2.username,p3.username as to_username";
		$sql = "select SELECT from {account_log} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				 left join {user} as p3 on p3.user_id=p1.to_user 
				$_sql ORDER LIMIT";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.addtime desc', $_limit), $sql));
			return $result;
		}	
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.addtime desc,id desc', $limit), $sql));		
		$list = $list?$list:array();
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('sum(money) as num', '', ''), $sql));
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'account' => $row['num'],
            'total_page' => $total_page
        );
		
	}
	/**
	 * 资金使用记录
	 *
	 * @return Array
	 */
/*
	function GetLogListOld($data = array()){
		global $mysql;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$username = empty($data['username'])?"":$data['username'];
	
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		if (!empty($user_id)){
			$_sql .= " and p2.user_id = $user_id";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}
		
		if (isset($data['dotime1']) && $data['dotime1']!=""){
			$_sql .= " and p1.addtime >= '".strtotime($data['dotime1'])."'";
		}
		if (isset($data['dotime2']) && $data['dotime2']!=""){
			$_sql .= " and p1.addtime <= '".strtotime($data['dotime2'])."'";
		}
		if (isset($data['type']) && $data['type']!=""){
			$_sql .= " and p1.type = '".$data['type']."'";
		}
		
		$_select = "p1.*,p2.username,p3.username as to_username";
		$sql = "select SELECT from {account_logold} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				 left join {user} as p3 on p3.user_id=p1.to_user 
				$_sql ORDER LIMIT";
				 
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.addtime desc', $_limit), $sql));
			return $result;
		}	
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.addtime desc,id desc', $limit), $sql));		
		$list = $list?$list:array();
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('sum(money) as num', '', ''), $sql));
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'account' => $row['num'],
            'total_page' => $total_page
        );
		
	}*/
	/**
	 * 资金使用记录
	 *
	 * @return Array
	 */
	function GetLogListForExcel($data = array()){
		global $mysql;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$username = empty($data['username'])?"":$data['username'];
	
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		if (!empty($user_id)){
			$_sql .= " and p2.user_id = $user_id";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}
		
		if (isset($data['dotime1']) && $data['dotime1']!=""){
			$_sql .= " and p1.addtime >= '".strtotime($data['dotime1'].' 0:0:0')."'";
		}
		if (isset($data['dotime2']) && $data['dotime2']!=""){
			$_sql .= " and p1.addtime <= '".strtotime($data['dotime2'].' 23:59:59')."'";
		}
		if (isset($data['type']) && $data['type']!=""){
			$_sql .= " and p1.type = '".$data['type']."'";
		}
		$_select = "p1.*,p2.username,p3.username as to_username";
		$sql = "select SELECT from {account_log} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				 left join {user} as p3 on p3.user_id=p1.to_user 
				$_sql ORDER LIMIT";
				 
		//是否显示全部的信息
		$_limit = "";
		if ($data['limit'] != "all"){
			$_limit = "  limit ".$data['limit'];
		}

		$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.addtime desc', $_limit), $sql));

                foreach ($result as $key => $value){
			$_data[$key] = array(date('Y-m-d H:i:s',$value['addtime']),$value['username'],$value['type'],$value['total'],$value['money'],$value['use_money'],$value['no_use_money'],$value['collection'],$value['to_username'],$value['remark']);
		}
      
		return $_data;
		
	}        
        
	/**
	 * 历史资金使用记录
	 *
	 * @return Array
	 */
	function GetLogListForExcelOld($data = array()){
		global $mysql;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$username = empty($data['username'])?"":$data['username'];
	
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		if (!empty($user_id)){
			$_sql .= " and p2.user_id = $user_id";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}
		
		if (isset($data['dotime1']) && $data['dotime1']!=""){
			$_sql .= " and p1.addtime >= '".strtotime($data['dotime1'])."'";
		}
		if (isset($data['dotime2']) && $data['dotime2']!=""){
			$_sql .= " and p1.addtime <= '".strtotime($data['dotime2'])."'";
		}
		if (isset($data['type']) && $data['type']!="" && $data['type']!="excel"){
			$_sql .= " and p1.type = '".$data['type']."'";
		}
		
		$_select = "p1.*,p2.username,p3.username as to_username";
		$sql = "select SELECT from {account_logold} as p1 
				 left join {user} as p2 on p1.user_id=p2.user_id
				 left join {user} as p3 on p3.user_id=p1.to_user 
				$_sql ORDER LIMIT";
				 
		//是否显示全部的信息
		$_limit = "";
		if ($data['limit'] != "all"){
			$_limit = "  limit ".$data['limit'];
		}

		$result= $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.addtime desc', $_limit), $sql));

                foreach ($result as $key => $value){
			$_data[$key] = array(date('Y-m-d H:i:s',$value['addtime']),$value['username'],$value['type'],$value['total'],$value['money'],$value['use_money'],$value['no_use_money'],$value['collection'],$value['to_username'],$value['remark']);
		}
      
		return $_data;
		
	}        
      

	  
	/**
	 *资金统计
	 *
	 * @return Array
	 */
	function GetLogCount($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
		if (isset($data['user_id']) && $data['user_id']!="" ){
			$_sql .= " and p1.user_id={$data['user_id']}";
		}
		$first_time = (isset($data['dotime2']) && $data['dotime2']!="")?$data['dotime2']:date("Y-m-d",time());
		$_first_time = intval(strtotime($first_time.' 23:59:59'));
		if (isset($data['dotime1']) && $data['dotime1']!="" ){
			$end_time =  intval(strtotime($data['dotime1'].' 0:0:0'));
		}else{
			$end_time = $_first_time - 7*60*60*24;
		}
		$_sql .= ' and p1.addtime>'.$end_time.' and p1.addtime<'.$_first_time;
		$_select = 'p1.*';
		$sql = 'select SELECT from {account_log} as p1 '.$_sql.' ORDER LIMIT';
		$result = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, '', ''), $sql));
		$_result = '';
		$i=round(($_first_time - $end_time)/(60*60*24));
		if ($i>60) $i=60;
		for ($j=0;$j<=$i;$j++){
			$day_ftime =  $_first_time - 60*60*24*$j;
			$date = date("Y-m-d",$day_ftime);
			$day_ftime = strtotime($date.' 0:0:0');
			$_result[$date]['i'] = $j;
			foreach ($result as $key=>$value){
				if ($value['addtime']>=$day_ftime && $value['addtime']<=$day_ftime+60*60*24){
					$_result[$date][$value['type']] += $value['money'];
				}
			}
		}
		return $_result;
	}
	
	
	/**
	 * 获取用户资金的全部记录,
	 *
	 * @return Array
	 */
	function GetAccountAll($data = array()){
		global $mysql;
		$user_id = $data['user_id'];
		
		//资金账号情况
		$sql = "select * from `{account}` where user_id = {$user_id} ";
		$result = $mysql -> db_fetch_array($sql);
		//资金账号情况
		$sql = "select borrow_amount from `{user_cache}` where user_id = {$user_id} ";
		$_result = $mysql -> db_fetch_array($sql);
		$result['amount'] = round($_result['borrow_amount'],2);//借款总额
		
		//待还总额
		$sql = "select sum(repayment_account) as borrow_num ,sum(repayment_yesaccount) as borrow_yesnum from {borrow_repayment} where borrow_id in (select id from `{borrow}` where user_id = {$user_id}) ";
		$_result = $mysql -> db_fetch_array($sql);
		$result['wait_payment'] = round(($_result['borrow_num'] - $_result['borrow_yesnum']),2);//待还总额
		$result['borrow_num'] = round($_result['borrow_num'],2);//借款总额
		$result['borrow_yesnum'] = round($_result['borrow_yesnum'],2);//已还总额
		$result['use_amount'] = round($result['amount']-$result['wait_payment'],2);
		
		//待收总金额,待收利息
		//$sql = "select sum(account) as account_num,sum(interest) as interest_num,sum(repayment_account) as repayment_account_num,sum(repayment_yesaccount) as repayment_yesaccount_num,sum(wait_account) as wait_account_num,sum(wait_interest) as wait_interest_num,sum(repayment_yesinterest) as repayment_yesinterest_num from {borrow_tender} where  borrow_id in (select id from `{borrow}` where status=3 or (status=1 and is_lz=1)) and user_id=$user_id";
		//$_result = $mysql -> db_fetch_array($sql);
		//新加债权待收
		$sql = "select sum(capital) as account_num,sum(interest) as interest_num,sum(repay_account) as repayment_account_num,sum(repay_yesaccount) as repayment_yesaccount_num from {borrow_collection} where user_id={$user_id} and tender_status=1";

		$zq_result = $mysql -> db_fetch_array($sql);
		
		$_result['account_num'] = $zq_result['account_num'];//投资总额
		$_result['repayment_account_num'] = $zq_result['repayment_account_num'];//投资总额+利息
		$_result['repayment_yesaccount_num'] = $zq_result['repayment_yesaccount_num'];//已收总额
		$a = $mysql -> db_fetch_array("select sum(interest) as interest_num,sum(repay_yesaccount) as repayment_yesaccount_num from {borrow_collection} where user_id={$user_id} and tender_status=1 and status=0");
		$_result['wait_interest_num'] = $a['interest_num'];//待收利息
		$_result['wait_account_num'] = $a['repayment_account_num'];//待收总额

		$result['tender_num'] = round($_result['account_num'],2);//投资总额
		$result['tender_numall'] = round($_result['repayment_account_num'],2);//投资总额+利息
		$result['tender_yesnum'] = round($_result['repayment_yesaccount_num'],2);//已收总额
		$result['tender_wait'] =  round($_result['wait_account_num'],2);//待收总额
		$result['tender_wait_interest'] = round($_result['wait_interest_num'],2);//待收利息
		$result['tender_interest'] = round(($_result['repayment_account_num'] - $_result['account_num']),2);//净赚利息

		return $result;
	}
		
		
		
	//获取资金记录的列表，按类型和时间分类
	function GetLogGroup($data = array()){
		global $mysql;
		$_sql = "";
		if (isset($data['user_id']) && $data['user_id']!="" ){
			$_sql .= " and p1.user_id={$data['user_id']}";
		}
		$sql = "select sum(p1.money) as num,p1.type,p2.name from `{account_log}` as p1 left join `{linkage}` as p2 on p2.value=p1.type where p2.type_id=30 {$_sql} and p1.type in ('borrow_success','borrow_fee','margin','award_lower','fee') group by type order by p1.type desc";
		$result = $mysql->db_fetch_arrays($sql);
		return $result;
	
	}
	
	//在线充值返回数据处理
	function  OnlineReturn ($data = array()){
		global $mysql;
		$trade_no = $data['trade_no'];
		
		$rechage_result = self::GetRechargeOne(array("trade_no"=>$trade_no));
		if($rechage_result['status']==0){
			$rec['id'] = $rechage_result['id'];
			$rec['return'] = serialize($_REQUEST);
			$rec['status'] = 1;
			$rec['verify_userid'] = 0;
			$rec['verify_time'] = time();
			$rec['verify_remark'] = "订单:".$trade_no."成功充值";
			$rec['recharge_type'] = 1;
			self::UpdateRecharge($rec);
			
			$user_id = str_replace($rechage_result['addtime'],"",$trade_no);
			$user_id = substr($user_id,0,strlen($user_id)-1); 
			
			$account_result =  self::GetOne(array("user_id"=>$user_id));		
			$log['user_id'] = $user_id;
			$log['type'] = "recharge";
			$log['money'] = $rechage_result['money'];
			$log['total'] = $account_result['total']+$rechage_result['money'];
			$log['use_money'] = $account_result['use_money']+$rechage_result['money'];
			$log['no_use_money'] = $account_result['no_use_money'];
			$log['collection'] = $account_result['collection'];
			$log['to_user'] = 0;
			$log['remark'] = "在线充值，订单号:".$trade_no;
			accountClass::AddLog($log);
			
			$account_result =  self::GetOne(array("user_id"=>$user_id));
			$log['user_id'] = $user_id;
			$log['type'] = "fee";
			$log['money'] =$rechage_result['fee'];
			$log['total'] = $account_result['total']-$log['money'];
			$log['use_money'] = $account_result['use_money']-$log['money'];
			$log['no_use_money'] = $account_result['no_use_money'];
			$log['collection'] = $account_result['collection'];
			$log['to_user'] = 0;
			$log['remark'] = "在线充值(订单号:".$trade_no.")手续费";
			accountClass::AddLog($log);
		}
		return true;
	}
	/*
	 * VIP费用的扣除
	 * 推荐人增加提成
	 */
	function AccountVip($data = array()){
		global $mysql,$_G;
		$user_id = $data['user_id'];
		$sql = "select p1.vip_money,p1.vip_status,p2.*,p3.pIpsAcctNo from `{user_cache}` as p1 left join `{account}` as p2 on p1.user_id=p2.user_id left join {user} as p3 on p3.user_id=p1.user_id where p1.user_id = {$user_id}";
		$result = $mysql->db_fetch_array($sql);
		$vip_money = $result['vip_money'];
        if($result['vip_status']==1){
			//扣除vip的会员费。
			if($data['from']=="account"){
            	$account_result =  self::GetOneAccount(array("user_id"=>$user_id));
            	$vip_log['user_id'] = $user_id;
            	$vip_log['type'] = "vip";
            	$vip_log['money'] = $vip_money;
            	$vip_log['total'] = $account_result['total']-$vip_log['money'];
	            $vip_log['use_money'] = $account_result['use_money']-$vip_log['money'];
	            $vip_log['no_use_money'] = $account_result['no_use_money'];
	            $vip_log['collection'] = $account_result['collection'];
	            $vip_log['to_user'] = "0";
	            $vip_log['remark'] = "扣除VIP会员费(最新充值中扣费)";
            	self::AddLog($vip_log);
            }else{
                $account_result =  self::GetOneAccount(array("user_id"=>$user_id));
                $vip_log['user_id'] = $user_id;
                $vip_log['type'] = "vip2";
                $vip_log['money'] = $vip_money;
                $vip_log['total'] = $account_result['total']-$vip_log['money'];
                $vip_log['use_money'] = $account_result['use_money'];
                $vip_log['no_use_money'] = $account_result['no_use_money']-$vip_log['money'];
                $vip_log['collection'] = $account_result['collection'];
                $vip_log['to_user'] = "0";
                $vip_log['remark'] = "扣除VIP会员费(扣除VIP冻结金额)";
                self::AddLog($vip_log);
            }
			$sql = "update `{user_cache}` set vip_money=$vip_money where user_id='{$user_id}'";
			$mysql -> db_query($sql);

            
			$data['out_user'] = $result['pIpsAcctNo'];
			$data['amount'] = $vip_money;
			if($vip_money>0){
				$a = tg_deduct($data);
				if($a!==true){
					return false;
				}
			}
            //如果这个用户是别人介绍进来的，则需要给他的上线进行提成收入
            /*
			$sql = "select p1.invite_userid,p1.invite_money,p2.username  from `{user}` as p1 left join `{user}` as p2 on p1.invite_userid = p2.user_id where p1.user_id = '{$user_id}' ";
			$result = $mysql ->db_fetch_array($sql);
			if ($result['invite_userid']!="" && $result['invite_money']<=0){	
				//给介绍人提成
				$vip_ticheng = (!isset($_G['system']['con_vip_ticheng']) || $_G['system']['con_vip_ticheng']=="")?0:$_G['system']['con_vip_ticheng'];
				
				if($vip_ticheng>0){
					$account_result =  accountClass::GetOneAccount(array("user_id"=>$result['invite_userid']));
					$ticheng_log['user_id'] = $result['invite_userid'];
					$ticheng_log['type'] = "ticheng";
					$ticheng_log['money'] = $vip_ticheng;
					$ticheng_log['total'] = $account_result['total']+$ticheng_log['money'];
					$ticheng_log['use_money'] = $account_result['use_money']+$ticheng_log['money'];
					$ticheng_log['no_use_money'] = $account_result['no_use_money'];
					$ticheng_log['collection'] = $account_result['collection'];
					$ticheng_log['to_user'] = "0";
					$ticheng_log['remark'] = "邀请用户注册(<a href=\'/u/{$result['invite_userid']}\' target=_blank>{$result['username']}</a>)并成为VIP会员的提成收入";
					accountClass::AddLog($ticheng_log);

					$sql = "update `{user}` set invite_money=$vip_ticheng where user_id='{$user_id}'";
					return $mysql -> db_query($sql);
				}
			}*/
			return true;
		}
		return true;
	}
	//资金扣除，比如现场认证费用 type,money,remark
	function Deduct($data){
		global $mysql,$_G;
		$account_result =  self::GetOneAccount(array("user_id"=>$data['user_id']));		
		if($account_result['use_money'] < $data['money']){
			return "此客户可用余额不足，可用余额为{$account_result['use_money']}";
		}
		if($data['money'] < 0){
			return "操作金额不能为负数";
		}
		mysql_query("start transaction");
		$log['user_id'] = $data['user_id'];
		$log['type'] = $data['type'];
		$log['money'] = $data['money'];
		$log['total'] = $account_result['total']-$data['money'];
		$log['use_money'] = $account_result['use_money']-$data['money'];
		$log['no_use_money'] = $account_result['no_use_money'];
		$log['collection'] = $account_result['collection'];
		$log['to_user'] = 0;
		$log['remark'] = $data['remark'];
		$re = accountClass::AddLog($log);
		if($account_result==false || $re==false){
			mysql_query("rollback");
			return false;
		}
		require_once("modules/message/message.class.php");
		$message['sent_user'] = "0";
		$message['receive_user'] = $data['user_id'];
        if($data['type'] == "scene_account"){
			$message['name'] = "现场认证费用";
        }else if($data['type'] == "vouch_advanced"){
            $message['name'] = "担保垫付扣费";
        }else if($data['type'] == "borrow_kouhui"){
            $message['name'] = "借款人罚金扣回";
        }else{
            $message['name'] = $data['remark'];
        }
		$message['content'] = $data['remark'];
		$message['type'] = "system";
		$message['status'] = 0;
		messageClass::Add($message);//发送短消息
		mysql_query("commit");
		return true;
	}
	
	function Tongji($data = array()){
		global $mysql,$_G;
		$_first_month = strtotime("2010-08-01");
		$_now_year = date("Y",time());
		$_now_month = date("n",time());
		$month = ($_now_year-2011)*12 + 5+$_now_month;//现在的月数
		//成功借款
		for ($i=1;$i<=$month;$i++){
			$up_month = strtotime("$i month",$_first_month);
			$now_month = strtotime("-1 month",$up_month);
			$nowlast_day = strtotime("-1 day",$up_month);
			$sql = "select sum(p1.money) as num,p1.type,p2.name as type_name from `{account_log}` as p1 left join `{linkage}` as p2 on p1.type=p2.value where p2.type_id=30 and p1.addtime >= {$now_month} and p1.addtime < {$nowlast_day} group by  p1.type ";
			$result = $mysql->db_fetch_arrays($sql);
			if (count($result)>0){
			$_result[date("Y-n",$now_month)] = $result;
			}
		}
		return $_result;
	}
	
	function vipTichengPay(){

		global $mysql;
		global $_G;

		$current_time = time();
		$sql = "insert into `{auto_log}` set `addtime` = '{$current_time}',`comment` = 'vip ticheng payed begin'";
		$mysql->db_query($sql);

		$sql = "
		SELECT count(*) as vip_ticheng_pay_num
		FROM `{user_cache}` uc
		LEFT JOIN rd_user ur1
		ON uc.user_id = ur1.user_id

		LEFT JOIN rd_account ac
		ON uc.user_id = ac.user_id
		WHERE     uc.vip_status = 1
		AND uc.vip_ticheng_payed = 0
		AND ur1.invite_userid != ''
		AND ac.use_money > 0
		";
		$result = $mysql ->db_fetch_array($sql);
		$vip_ticheng_pay_num = $result['vip_ticheng_pay_num'];



		if ($vip_ticheng_pay_num > 0 ){


			$sql = "
			SELECT uc.user_id, ur1.invite_userid, ur1.invite_money, ur1.username 
			FROM rd_user_cache uc
			LEFT JOIN rd_user ur1
			ON uc.user_id = ur1.user_id
			 
			LEFT JOIN rd_account ac
			ON uc.user_id = ac.user_id
			WHERE     uc.vip_status = 1
			AND uc.vip_ticheng_payed = 0
			AND ur1.invite_userid != ''
			AND ac.use_money > 0
			";

			$vip_user_result = $mysql->db_fetch_arrays($sql);
			foreach ($vip_user_result as $key => $value){
				//liukun add for bug 472 begin
				$mysql->db_query("start transaction");
				//liukun add for bug 472 end
				$transaction_result = true;
				try{
					$user_id = $value['user_id'];
					$invite_userid = $value['invite_userid'];
					$invite_money=$value['invite_money'];

					$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['invite_userid']));
					$ticheng_log['user_id'] = $value['invite_userid'];
					$ticheng_log['type'] = "ticheng";
					$ticheng_log['money'] = $invite_money;
					$ticheng_log['total'] = $account_result['total']+$ticheng_log['money'];
					$ticheng_log['use_money'] = $account_result['use_money']+$ticheng_log['money'];
					$ticheng_log['no_use_money'] = $account_result['no_use_money'];
					$ticheng_log['collection'] = $account_result['collection'];
					$ticheng_log['to_user'] = "0";
					$ticheng_log['remark'] = "邀请用户注册(<a href=\'/u/{$value['invite_userid']}\' target=_blank>{$value['username']}</a>)并成为VIP会员的提成收入";
					accountClass::AddLog($ticheng_log);
					$sql = "update `{user_cache}` set vip_ticheng_payed=1 where user_id='{$user_id}'";
					$transaction_result = $mysql->db_query($sql);
					if ($transaction_result !==true){
						throw new Exception();
					}
				}
				catch (Exception $e){
					$msg = array($transaction_result);
					//必须保证所有不可接受的错误都返回异常，并执行了回滚
					$mysql->db_query("rollback");
				}
				//liukun add for bug 472 begin
				if($transaction_result===true){
					$mysql->db_query("commit");
				}else{
					$mysql->db_query("rollback");
				}
			}


		}

		$sql = "insert into `{auto_log}` set `addtime` = '{$current_time}',`comment` = 'vip ticheng payed begin'";
		$mysql->db_query($sql);
	}
	
	/**
	 * 取消过期VIP
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function CancelVIP($data = array()){
		global $mysql;
		global $_G;
	
		$current_time = time();
	
		$vip_valid_time = 60*60*24*365;
		$sql = "insert into `{auto_log}` set `addtime` = '{$current_time}',`comment` = 'cancel vip begin'";
		$mysql->db_query($sql);
	
		$sql = "update `{user_cache}` set vip_status = 0 where (vip_verify_time + {$vip_valid_time}) < {$current_time} and vip_status = 1";
	
		$mysql->db_query($sql);
	
		$sql = "insert into `{auto_log}` set `addtime` = '{$current_time}',`comment` = 'cancel vip end'";
		$mysql->db_query($sql);
	
		return true;
	}

	//获取用户净资产
	public function get_user_jin($user_id){
		global $mysql;
		$user_id = (int)$user_id;
		$sql = 'select sum(repay_account) as a from {borrow_collection} where status=0 and user_id='.$user_id;
		$re = $mysql->db_fetch_array($sql);
		$data['repay_account'] = $re['a'];//待收

		$sql = 'select sum(p1.repayment_account) as a from {borrow_repayment} as p1 laft join {borrow} as p2 on p1.borrow_id=p2.id where p1.status!=1 and p2.user_id='.$user_id;
		$re = $mysql->db_fetch_array($sql);
		$data['repayment_account'] = $re['a'];//待还
	}

	//托管返回提现处理
	public static function hxUpdateCash($_data=array()){
		global $mysql;
		$trade_no = $_data['trade_no'];
		if($trade_no=='') return array('msg'=>"提现流水号有误",'content'=>'返回','url'=>'/index.php?user&q=code/account/cash_new');
		$sql = 'select p1.*,p2.card_id,p2.pIpsAcctNo from {account_cash} as p1 left join {user} as p2 on p1.user_id=p2.user_id where p1.trade_no="'.$trade_no.'" limit 1';
		$a = $mysql->db_fetch_array($sql);
		if($a['status']!=0 && $a['status']!=-1) return array('msg'=>"订单不处于审核中",'content'=>'返回','url'=>'/index.php?user&q=code/account/cash_new');
		
		if($_data['status']==0 && $a['status']!=-1){
			return array('msg'=>"请不要重复操作",'content'=>'返回','url'=>'/index.php?user&q=code/account/cash_new');
		}
		if($_data['status']==1 && $a['status']!=0){
			return array('msg'=>"请不要重复操作",'content'=>'返回','url'=>'/index.php?user&q=code/account/cash_new');
		}
		if($_data['status']==$a['status']){
			return array('msg'=>"请不要重复操作",'content'=>'返回','url'=>'/index.php?user&q=code/account/cash_new');
		}
		$data = $a;
		if ($_data['status']==0){
			//mysql_query("start transaction");
			$up = $mysql->db_query('update {account_cash} set status=0 where trade_no="'.$trade_no.'"');
			/*
			$account_result =  self::GetOneAccount(array("user_id"=>$data['user_id']));
			$log['user_id'] = $data['user_id'];
			$log['type'] = "cash_frost";
			$log['money'] = $data['total'];
			$log['total'] = $account_result['total'];
			$log['use_money'] =  $account_result['use_money']-$log['money'];
			$log['no_use_money'] =  $account_result['no_use_money']+$log['money'];
			$log['collection'] =  $account_result['collection'];
			$log['to_user'] = "0";
			$log['remark'] = "用户提现申请成功，资金冻结";
			$re = self::AddLog($log);
			if($up==false || $account_result==false || $re==false){
				 mysql_query("rollback");
				 return array('msg'=>"操作失败",'content'=>'返回','url'=>'/index.php?user&q=code/account/cash_new');
			}else{
				mysql_query("commit");
				return array('msg'=>"提现申请成功",'content'=>'返回','url'=>'/index.php?user&q=code/account/cash_new');
			}*/
		}elseif ($_data['status']==1){
			$c['id'] = $data['id'];
			$c['status'] = 1;
			$c['credited']=$data['credited'];
			$c['fee']=$data['fee'];
			$c['user_id']=$data['user_id'];
			$c['verify_userid'] = 0;
			$c['verify_remark']='提现成功';
			$re = self::UpdateCash($c);
			return $re;
		}
	}
	//查询订单信息
	public static function getOrderResult($data = array()){
		global $mysql,$HX_config;
		$page = isset($data['page'])?$data['page']:1;
		$epage = isset($data['epage'])?$data['epage']:10;
		$where = ' where 1=1';
		if(isset($data['id']) && $data['id']!=''){
			$where .= ' and p1.id='.$data['id'];
		}
		if(isset($data['user_id']) && $data['user_id']!=''){
			$where .= ' and p1.user_id='.$data['user_id'];
		}
		if(isset($data['username']) && $data['username']!=''){
			$where .= " and p2.username='{$data['username']}'";
		}
		if(isset($data['order_number']) && $data['order_number']!=''){
			$where .= " and p1.order_number='{$data['order_number']}'";
		}
		if (isset($data['borrow_id']) && $data['borrow_id']!='') {
			$where .= " and p1.borrow_id='{$data['borrow_id']}'";
		}
		if(isset($data['collection_id']) && $data['collection_id']!=''){
			$where .= " and p1.collection_id='{$data['collection_id']}'";
		}
		if(isset($data['repayment_id']) && $data['repayment_id']!=''){
			$where .= " and p1.repayment_id='{$data['repayment_id']}'";
		}
		if(isset($data['tran_code']) && $data['tran_code']!=''){
			$where .= " and p1.tran_code='{$data['tran_code']}'";
		}
		if(isset($data['tran_time']) && $data['tran_time']!=''){
			$where .= " and p1.tran_time='{$data['tran_time']}'";
		}
		if(isset($data['err_code']) && $data['err_code']!=''){
			$where .= " and p1.err_code='{$data['err_code']}'";
		}
		$sql = 'select count(1) as num from {tg_order} as p1 left join {user} as p2 on p1.user_id=p2.user_id '.$where;
		$a = $mysql->db_fetch_array($sql);
		$total = (int)$a['num'];
		$total_page = ceil($total/$epage);
		$limit = ' limit '.($page-1)*$epage.', '.$epage;
		$order = ' order by p1.id desc ';
		if(isset($data['limit'])){
			$limit = ' limit '.($page-1)*$epage.', '.$data['limit'];
			$sql = 'select p1.*,p2.username from {tg_order} as p1 left join {user} as p2 on p1.user_id=p2.user_id '.$where.$order.$limit;
			$a = $mysql->db_fetch_arrays($sql);
			foreach($a as $k=>$v){
				$a[$k]['tran_time_show'] = date("Y-m-d H:i:s",strtotime($v['tran_time']));
				$a[$k]['tran_code_show'] = HX_gettype($v['tran_code']).'('.$v['tran_code'].')';
				$a[$k]['tg_return'] = empty($v['tg_return'])?array():unserialize($v['tg_return']);
				$a[$k]['cx_return'] = empty($v['cx_return'])?array():unserialize($v['cx_return']);
			}
			return $a;
		}
		$sql = 'select p1.*,p2.username from {tg_order} as p1 left join {user} as p2 on p1.user_id=p2.user_id '.$where.$order.$limit;
		$a = $mysql->db_fetch_arrays($sql);
		foreach($a as $k=>$v){
			$a[$k]['tran_time_show'] = date("Y-m-d H:i:s",strtotime($v['tran_time']));
			$a[$k]['tran_code_show'] = HX_gettype($v['tran_code']).'('.$v['tran_code'].')';
			//$a[$k]['tg_return'] = empty($v['tg_return'])?array():unserialize($v['tg_return']);
		}
		return array(
				'total'=>$total,
				'total_page'=>$total_page,
				'list'=>$a,
            	'page' => $page,
            	'epage' => $epage
				);
	}
	//转账记录
	public static function zhuanzList($data = array()){
		global $mysql;
		$page = isset($data['page'])?$data['page']:1;
		$epage = 10;
		$where = ' where 1=1 ';
		if($data['in_username']!=''){
			$where .= " and p3.username='{$data['in_username']}'";
		}
		if($data['out_username']!=''){
			$where .= " and p2.username='{$data['out_username']}'";
		}
		if($data['trade_no']!=''){
			$where .= " and p1.trade_no='{$data['trade_no']}'";
		}
		$select = 'select p1.*,p2.username as out_username,p3.username as in_username ';
		$from = ' from {transfer_accounts} as p1 left join {user} as p2 on p1.out_user=p2.user_id left join {user} as p3 on p1.in_user=p3.user_id ';
		$c = $mysql->db_fetch_array('select count(1) as c '.$from.$where);
		$total = $c['c'];
		$total_page = ceil($total/$epage);
		$limit = ' limit '.($page-1)*$epage.','.$epage;
		$list = $mysql->db_fetch_arrays($select.$from.$where.$limit);
		return array(
			'total'=>$total,
			'total_page'=>$total_page,
			'list'=>$list,
			'page' => $page,
			'epage' => $epage
		);
	}
}
?>