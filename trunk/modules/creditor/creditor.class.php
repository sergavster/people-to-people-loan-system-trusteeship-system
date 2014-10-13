<?php

class creditorClass{
	/*
	 * 获取用户对指定标的剩余待收记录
	 */
	public function get_collection($borrow_id, $user_id, $tender_id=''){
		global $mysql;
		$where = ' where p1.borrow_id='.$borrow_id.' and p1.user_id='.$user_id;
		$order = ' order by p1.`id` asc ';
		if($tender_id>0){
			$where .= ' and p1.tender_id='.$tender_id;
			$order = ' order by p1.`order` asc ';
		}
		$sql = 'select p1.*,p1.status as repay_status,p1.order as order_1,p1.id as collection_id,p2.name,p2.biao_type,p2.success_time,p2.account_yes,p2.apr,p2.time_limit from {borrow_collection} as p1 left join {borrow} as p2 on p1.borrow_id=p2.id'.$where.$order;
		$list = $mysql->db_fetch_arrays($sql);
		$total_collection = 0;
		$total_capital = 0;
		$collection_id = array();
		$tender_id = array();
		$last_repay_time = 0;
		$next_repay_time = 0;//下一次还款时间，如何本期未还则和本期还款时间$this_repay_time一样
		$success_time = 0;
		$this_repay_time = 0;//本期还款时间
		$this_repay_status = 0;//本期还款状态
		$this_repay_order;//本期期数
		$last_repay_order = 0;//最后一次还款的期数
		$now_time = time();//当前时间
		$this_repay_sydate = 0;//距离本期还款时间，负数表示提前还款天数
		$n = 0;
		foreach($list as $k=>$v){
			$success_time = $v['success_time'];
			if($v['repay_status']==0){
				$total_collection += $v['repay_account'];
				$total_capital += $v['capital'];
				if(!in_array($v['collection_id'], $collection_id)){
					$collection_id[] = $v['collection_id'];
				}
				if(!in_array($v['tender_id'], $tender_id)){
					$tender_id[] = $v['tender_id'];
				}
			}
			if($last_repay_time<$v['repay_time']){
				$last_repay_time = $v['repay_time'];
			}
			if(($next_repay_time==0 || $next_repay_time>$v['repay_time']) && $v['repay_status']==0){
				$next_repay_time = $v['repay_time'];
			}
			if($this_repay_time==0 || ($v['repay_time']-$now_time>0 && $v['repay_time']-$now_time<$n )){
				$this_repay_time = $v['repay_time'];
				$this_repay_status = $v['repay_status'];
				$this_repay_order = $v['order'];
				if($v['repay_status']==1){
					$this_repay_sydate = $v['repay_yestime'] - $this_repay_time;
				}else{
					$this_repay_sydate = $this_repay_time-time();
				}
				$this_repay_sydate = (int)($this_repay_sydate/86400);
			}
			$n = $v['repay_time']-$now_time;
			if($v['repay_status']==1){
				if($this_repay_order==$v['order']){
				}else{
					if($last_repay_order==0 || $v['order']>$last_repay_order){
						$last_repay_order = $v['order'];
					}
				}
			}
		}
		$zq_time = ceil((time()-$success_time)/86400);//债权拥有时间
		return array(
			'list'=>$list,
			'total_collection'=>$total_collection,
			'total_capital'=>$total_capital,
			'last_repay_time'=>$last_repay_time,
			'next_repay_time'=>$next_repay_time,
			'collection_id'=>implode(',', $collection_id),
			'tender_id'=>implode(',', $tender_id),
			'zq_time'=>$zq_time,
			'this_repay_time'=>$this_repay_time,
			'this_repay_status'=>$this_repay_status,
			'this_repay_sydate'=>$this_repay_sydate,
			'this_repay_order'=>$this_repay_order,
			'apr'=>$list[0]['apr'],
			'last_repay_order'=>$last_repay_order
		);
	}
	//添加债权转让
	public function add($data=array()){
		global $mysql;
		$sql = '';
		foreach($data as $k=>$v){
			$sql .= ',`'.$k.'`="'.$v.'"';
		}
		$sql = substr($sql, 1);
		$sql = 'insert into `{creditor_transfer}` set '.$sql;
		return $mysql->db_query($sql);
	}
	//获取债权转让列表
	public function get_zqzr_list($data = array()){
		global $mysql,$_G;
		$page = isset($data['page'])?(int)$data['page']:1;
		if($page<1) $page=1;
		$epage = isset($data['epage'])?(int)$data['epage']:10;
		$where = ' where 1=1 ';
		if(isset($data['user_id']) && $data['user_id']>0){
			$where .= ' and p1.user_id='.intval($data['user_id']);
		}
		if(isset($data['id']) && $data['id']>0){
			$where .= ' and p1.id='.intval($data['id']);
		}
		if(isset($data['borrow_id']) && $data['borrow_id']>0){
			$where .= ' and p1.borrow_id='.intval($data['borrow_id']);
		}
		if(isset($data['tender_id']) && $data['tender_id']>0){
			$where .= ' and p1.tender_id='.intval($data['tender_id']);
		}
		if(isset($data['finance_account_id']) && $data['finance_account_id']>0){
			$where .= ' and p1.finance_account_id='.intval($data['finance_account_id']);
		}
		if(isset($data['status']) && $data['status']!=''){
			$status = explode(',', $data['status']);
			$_status = '';
			foreach($status as $v){
				$_status .= ','.(int)$v;
			}
			$_status = substr($_status, 1);
			$where .= ' and p1.status in('.$_status.')';
		}
		if(isset($data['type']) && $data['type']!=''){
			$type = $data['type'];
			if($type=='full'){
				$where .= ' and p1.account=p1.account_yes';
			}
		}
		$sql = 'select count(1) as sum from {creditor_transfer} as p1 left join {borrow} as p2 on p1.borrow_id=p2.id '.$where;
		$re = $mysql->db_fetch_array($sql);
		$total = $re['sum'];
		$total_page = ceil($total/$epage);
		$limit = ' limit '.($page-1)*$epage.','.$epage;
		$sql = 'select p1.*,p2.name as borrow_name,p2.apr as borrow_apr,p2.credit_grade,p3.username from {creditor_transfer} as p1 left join {borrow} as p2 on p1.borrow_id=p2.id left join {user} as p3 on p1.user_id=p3.user_id '.$where.$limit;
		$list = $mysql->db_fetch_arrays($sql);
		foreach($list as $k=>$v){
			$list[$k]['show_y_timelimit'] = floor(($v['y_timelimit']-time())/86400).'天';
			$list[$k]['surplus_copies'] = ($v['account']-$v['account_yes'])/$v['every_account'];
			$list[$k]['mfjz'] =$v['y_account']/($v['account']/$v['every_account']); //add by fjl at 20140219
			
			$list[$k]['zrxs']=$v['account']/$v['sy_account']*100;
			$width = 30;
			$height = 20;
			switch($v['credit_grade']){
				case 1:
					$list[$k]['credit_img'] = "<img src='{$_G['tpldir']}/images/dj.png' width='{$width}' height='{$height}' />";
					break;
				case 2:
					$list[$k]['credit_img'] = "<img src='{$_G['tpldir']}/images/A.png' width='{$width}' height='{$height}' />";
					break;
				case 3:
					$list[$k]['credit_img'] = "<img src='{$_G['tpldir']}/images/B.png' width='{$width}' height='{$height}' />";
					break;
				case 4:
					$list[$k]['credit_img'] = "<img src='{$_G['tpldir']}/images/C.png' width='{$width}' height='{$height}' />";
					break;
				case 5:
					$list[$k]['credit_img'] = "<img src='{$_G['tpldir']}/images/D.png' width='{$width}' height='{$height}' />";
					break;
				case 6:
					$list[$k]['credit_img'] = "<img src='{$_G['tpldir']}/images/E.png' width='{$width}' height='{$height}' />";
					break;
				case 7:
					$list[$k]['credit_img'] = "<img src='{$_G['tpldir']}/images/HR.jpg' width='{$width}' height='{$height}' />";
					break;
				default :
					$list[$k]['credit_img'] = "<img src='{$_G['tpldir']}/images/dj.jpg' />";
					break;
			}
		}
		return array(
			'list'=>$list,
			'total' => $total,
			'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
		);
	}
	//获取购买的债权列表
	public function get_sell_list($data=array()){
		global $mysql;
		$page = isset($data['page'])?(int)$data['page']:1;
		if($page<1) $page=1;
		$epage = isset($data['epage'])?(int)$data['epage']:10;

		$where = ' where 1=1';
		if(isset($data['transfer_id']) && $data['transfer_id']!=''){
			$where .= ' and p1.transfer_id='.$data['transfer_id'];
		}
		if(isset($data['user_id']) && $data['user_id']!=''){
			$where .= ' and p1.user_id='.$data['user_id'];
		}
		if(isset($data['status']) && $data['status']!=''){
			$status = explode(',', $data['status']);
			$_status = '';
			foreach($status as $v){
				$_status .= ','.(int)$v;
			}
			$_status = substr($_status, 1);
			$where .= ' and p1.status in('.$_status.')';
		}
		$sql = 'select count(1) as num from {creditor_record} as p1'.$where;
		$re = $mysql->db_fetch_array($sql);
		$total = $re['num'];
		$total_page = ceil($total/$epage);
		$limit = ' limit '.($page-1)*$epage.','.$epage;
		$sql = 'select p1.*,p1.account as gm_account,p2.id as zq_id,p2.borrow_id,p2.y_account,p2.every_account,p2.every_collection,p3.name as borrow_name from {creditor_record} as p1 left join {creditor_transfer} as p2 on p1.transfer_id=p2.id left join {borrow} as p3 on p2.borrow_id=p3.id'.$where.$limit;
		$list = $mysql->db_fetch_arrays($sql);
		foreach($list as $k=>$v){
			$list[$k]['z_copies'] = $v['gm_account']/$v['every_account'];
		}
		return array(
			'list'=>$list,
			'total' => $total,
			'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
		);
	}
	public function GetCreditorOne($data){
		global $mysql,$_G;
		if(isset($data['id']) && $data['id']!=''){
			$id = intval($data['id']);
		}else{
			$id = explode('/', $_SERVER['QUERY_STRING']);
			$id = intval($id[1]);
		}
		$sql = 'select p1.*,p2.name as borrow_name,p3.username from {creditor_transfer} as p1 left join {borrow} as p2 on p1.borrow_id=p2.id left join {user} as p3 on p1.user_id=p3.user_id where p1.id='.$id.' limit 1';
		$list = $mysql->db_fetch_array($sql);
		$list['scale'] = $list['account_yes']/$list['account'];
		$list['show_y_timelimit'] = floor(($list['y_timelimit']-time())/86400).'天';
		$list['surplus_copies'] = ($list['account']-$list['account_yes'])/$list['every_account'];
		$list['surplus_yescopies'] = $list['account_yes']/$list['every_account'];
		$list['surplus_account'] = $list['account']-$list['account_yes'];
		$list['zrxs']=$list['account']/$list['sy_account']*100;
		$list['end_time'] = ($list['verify_time']+$list['valid_time']*86400)-time();
		$list['mfjz'] =$list['y_account']/($list['account']/$list['every_account']);
		$return['zq_result'] = $list;

		$sql = 'select p1.*,p2.username from {borrow} as p1 left join {user} as p2 on p1.user_id=p2.user_id where p1.id='.$return['zq_result']['borrow_id'];
		$return['borrow'] = $mysql->db_fetch_array($sql);

		$return['borrow_all'] = borrowClass::GetBorrowAll(array("user_id"=>$return['borrow']['user_id']));
		if($_G['user_id']<1){
			$return['user_account'] = array();
		}else{
			$return['user_account'] = $mysql->db_fetch_array("select * from `{account}`  where  user_id={$_G['user_id']}");
				
				
		}
		return $return;
	}
	//撤销
	public function repeal($id){
		global $mysql;
		$creditor_transfer = $mysql->db_fetch_array('select status,id as transfer_id,user_id,borrow_id from `{creditor_transfer}` where id='.$id);
		if($creditor_transfer['status']==3){//以完成，无法撤销
			return false;
		}
		mysql_query("start transaction");//开启事务
		$a = $this->_canl($id);
		if($a===false){
			mysql_query("rollback");
			return false;
		}
		if(IS_TG){
			if(!empty($a)){
				$juser_result = $mysql->db_fetch_array("select * from {user} where user_id=".$creditor_transfer['user_id']);
				$a = tg_liubiao($creditor_transfer,$juser_result,$a);
				if($a['pErrCode']=='0000'){
					mysql_query("commit");
					return true;
				}else{
					mysql_query("rollback");
					return false;
				}
			}
		}
		mysql_query("commit");
		return true;
		/*
		$re = $mysql->db_query('update  `{creditor_transfer}` set status=5 where id='.$id);
		$re_1 = $mysql->db_query('update  `{creditor_record}` set status=5 where `transfer_id`='.$id);
		$re_2 = $mysql->db_query('update  `{borrow_collection}` set `transfer_id`=0 and `is_sell`=0 where `is_sell`=2 and `transfer_id`='.$id);
		if ($re==false || $re_1==false || $re_2==false){
			mysql_query("rollback");
			return false;
		}
		$render_list = $mysql->db_fetch_arrays('select * from `{creditor_record}` where `transfer_id`='.$creditor_transfer['id']);
		foreach($render_list as $key=>$value){
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
			$log['user_id'] = $value['user_id'];
			$log['type'] = "zqgm_false";//债权购买失败
			$log['money'] = $value['account'];
			$log['total'] = $account_result['total'];
			$log['use_money'] = $account_result['use_money']+$log['money'];
			$log['no_use_money'] = $account_result['no_use_money']-$log['money'];
			$log['collection'] = $account_result['collection'];
			$log['to_user'] = 0;
			$log['remark'] = "购买债权[<a href=\'/zqzr/a{$creditor_transfer['id']}.html\' target=_blank>{$value['borrow_name']}</a>]失败返回的金额";
			$re = accountClass::AddLog($log);
			if($re==false || $account_result==false){
				mysql_query("rollback");
				return false;
			}
		}
		mysql_query("commit");
		return true;
		*/
	}
	//初审
	public function verify_creditor_v($data=array()){
		mysql_query("start transaction");//开启事务
		$a = $this->verify_creditor($data);
		if($a==false){
			mysql_query("rollback");
			return false;
		}else{
			mysql_query("commit");
			return true;
		}
	}
	//初审
	public function verify_creditor($data=array()){
		global $mysql;
		$id = (int)$data['id'];
		if($id<1) return false;

		$re = $this->get_zqzr_list(array('id'=>$id));
		$zq_result = $re['list'][0];
		if($zq_result['status']!=0){
			return false;
		}
		$collection_id = explode(',',$zq_result['collection_id']);
		$in = '';
		foreach($collection_id as $k=>$v){
			$in .= ','.$v;
		}
		$in = substr($in, 1);
		$sql = 'update `{borrow_collection}` set `is_sell`=2 and `transfer_id`='.$id.' where id in('.$in.')';

		$re = $mysql->db_query($sql);
		if($re==false){
			return false;
		}
		$where = ' where id='.$id;
		unset($data['id']);
		$sql = '';
		foreach($data as $k=>$v){
			$sql .= ',`'.$k.'`="'.$v.'"';
		}
		$sql = substr($sql, 1);
		$sql = 'update `{creditor_transfer}` set '.$sql.$where;
		$re = $mysql->db_query($sql);
		if($re==false){
			return false;
		}
		return true;
	}
	//投标
	public function AddTender($data = array()){
		global $mysql,$_G;
		if (!isset($data['id']) || $data['id']==""){
			return false;
		}
		$gm_fs = intval($data['gm_fs']);
		if($gm_fs<1){
			return "认购的份数不能小于1。";
		}
		if ($_G['user_result']['islock']==1){
			return "您账号已经被锁定，不能进行认购债权，请跟管理员联系。";
		}
		$re = $this->get_zqzr_list(array('id'=>$data['id']));
		$zq_result = $re['list'][0];
		if($zq_result['status']!=1){
			return '此债权不在转让中，无法认购。';
		}
		if($zq_result['user_id']==$data['user_id']){
			//return '自己不认购自己的债权。';
		}
		$money = $gm_fs*$zq_result['every_account'];
		if($zq_result['account_yes']+$money>$zq_result['account']){
			$money = $zq_result['account'] - $zq_result['account_yes'];
		}
		$_data['status'] = 0;
		$_data['user_id'] = $data['user_id'];
		$_data['transfer_id'] = $zq_result['id'];
		$_data['account'] = $money;
		$_data['creditor_account'] = ($zq_result['y_account']/$zq_result['account'])*$money;
		$_data['addtime'] = time();

		require_once ROOT_PATH.'modules/account/account.class.php';
		$account_result =  accountClass::GetOneAccount(array("user_id"=>$data['user_id']));
		if($money>$account_result['use_money']){
			return '账户可用余额不足，请先充值！';
		}
		if(IS_TG){
			$_data['status'] = -1;
			$_data['trade_no'] = 'tb'.time().$_G['user_id'].rand(1,100);
			$re = $this->addrecord($_data);
			if(!$re){
				mysql_query("rollback");
				return false;
			}
			/*
			$j = $mysql->db_fetch_array("select card_id,realname,pIpsAcctNo from {user} where user_id=".$zq_result['user_id']);
			$r = tg_tenderzq($zq_result, $j, $_G['user_result'], $_data);
			if($r['pErrCode']!=1){
				return $r['pErrMsg'];
			}*/
		}
		mysql_query("start transaction");//开启事务
		$log['user_id'] = $data['user_id'];
		$log['type'] = "zqgm";//债权购买
		$log['money'] = $money;
		$log['total'] = $account_result['total'];
		$log['use_money'] = $account_result['use_money']-$log['money'];
		$log['no_use_money'] = $account_result['no_use_money']+$log['money'];
		$log['collection'] = $account_result['collection'];
		$log['to_user'] = 0;
		$log['remark'] = "购买债权[<a href=\'/zqzr/a{$zq_result['id']}.html\' target=_blank>{$zq_result['borrow_name']}</a>]资金冻结";
		$re = accountClass::AddLog($log);
		if($re==false || $account_result==false){
			mysql_query("rollback");
			return false;
		}
		$re = $mysql->db_query('update `{creditor_transfer}` set `account_yes`=`account_yes`+'.$money.' where id='.$zq_result['id'].' limit 1');
		if($re==false){
			mysql_query("rollback");
			return false;
		}
		if(IS_TG){
			$j = $mysql->db_fetch_array("select card_id,realname,pIpsAcctNo from {user} where user_id=".$zq_result['user_id']);
			$r = tg_tenderzq($zq_result, $j, $_G['user_result'], $_data);
			if($r['pErrCode']!=1){
				mysql_query("rollback");
				return $r['pErrMsg'];
			}
			$re = $mysql->db_query("update `{creditor_record}` set `status`=0 where `trade_no`='{$_data['trade_no']}' and `transfer_id`={$_data['transfer_id']}");
			$re = true;//阻止回滚
		}else{
			$re = $this->addrecord($_data);
		}
		if($re==false){
			mysql_query("rollback");
			return false;
		}
		mysql_query("commit");
		return true;
	}
	public function addrecord($data=array()){
		global $mysql;
		$sql = '';
		foreach($data as $k=>$v){
			$sql .= ',`'.$k.'`="'.$v.'"';
		}
		$sql = substr($sql, 1);
		$sql = 'insert into `{creditor_record}` set '.$sql;
		return $mysql->db_query($sql);
	}
	//复审
	public function full($data){
		global $mysql;
		if (!isset($data['id']) || $data['id']==""){
			return false;
		}
		$re = $this->get_zqzr_list(array('id'=>$data['id']));
		$zq_result = $re['list'][0];
		if($zq_result['status']!=1){
			return '此债权不在转让中，无法复审。';
		}
		if($zq_result['account_yes']!=$zq_result['account']){
			return '此债权未认购完，无法复审。';
		}
		$sql = 'select * from `{creditor_record}` where `status`=0 and `transfer_id`='.$zq_result['id'];
		$record = $mysql->db_fetch_arrays($sql);

		mysql_query("start transaction");//开启事务
		$re = $mysql->db_query("update {creditor_transfer} set `status`={$data['status']},`success_time`={$data['success_time']},`success_user`={$data['success_user']},`success_remark`='{$data['success_remark']}' where `id`=".$zq_result['id']);
		if($re==false){
			mysql_query("rollback");
			return false;
		}
		require_once ROOT_PATH.'modules/account/account.class.php';
		if($data['status']==3){//通过
			$collection_id = explode(',',$zq_result['collection_id']);
			$in = '';
			foreach($collection_id as $k=>$v){
				$in .= ','.$v;
			}
			$in = substr($in, 1);
			$sql = 'select * from `{borrow_collection}` where id in('.$in.')';
			$collection = $mysql->db_fetch_arrays($sql);
			//添加新的待收
			$re = $mysql->db_query('update `{creditor_record}` set status=1 where `transfer_id`='.$zq_result['id']);
			if($re==false){
				mysql_query("rollback");
				return false;
			}
			foreach($record as $_k=>$_v){
				//债权购买成功，资金扣除
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$_v['user_id']));
				$log['user_id'] = $_v['user_id'];
				$log['type'] = "zqgm_true";//债权购买
				$log['money'] = $_v['account'];
				$log['total'] = $account_result['total']-$log['money'];
				$log['use_money'] = $account_result['use_money'];
				$log['no_use_money'] = $account_result['no_use_money']-$log['money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = 0;
				$log['remark'] = "购买债权[<a href=\'/zqzr/a{$zq_result['id']}.html\' target=_blank>{$zq_result['borrow_name']}</a>]成功，费用扣除";
				$re = accountClass::AddLog($log);
				if($re==false || $account_result==false){
					mysql_query("rollback");
					return false;
				}

				$bl = $_v['account']/$zq_result['account'];//比例
				foreach($collection as $key=>$value){
					$c = round($value['repay_account']*$bl, 2);//总和
					$i = round($value['interest']*$bl, 2);//利息
					$b = round($value['capital']*$bl, 2);//本金
					//写待收
					$sql = "insert into `{borrow_collection}` set `status`=0,`tender_status`=1,`order`={$value['order']},`user_id`={$_v['user_id']},`borrow_id`={$zq_result['borrow_id']},`repay_time`='{$value['repay_time']}',`repay_account`={$c},`interest`={$i},`capital`={$b},`addtime`='".time()."',`addip`='".ip_address()."',`is_buy`=1,`transfer_id`={$zq_result['id']},`record_id`={$_v['id']}";
					$re = $mysql->db_query($sql);
					if($re==false){
						mysql_query("rollback");
						return false;
					}
					//跟新collection已还
					$re = $mysql->db_query('update `{borrow_collection}` set `is_sell`=1,`status`=1 where id='.$value['id'].' limit 1');
					if($re==false){
						mysql_query("rollback");
						return false;
					}
					//添加待收的金额
					$account_result = accountClass::GetOneAccount(array("user_id"=>$_v['user_id']));
					$log['user_id'] = $_v['user_id'];
					$log['type'] = "zqgm_collection";
					$log['money'] = $c;
					$log['total'] = $account_result['total']+$log['money'];
					$log['use_money'] = $account_result['use_money'];
					$log['no_use_money'] = $account_result['no_use_money'];
					$log['collection'] = $account_result['collection']+$log['money'];
					$log['to_user'] = $user_id;
					$log['borrow_id'] = $data['id'];
					$log['remark'] = "购买债权[<a href=\'/zqzr/a{$zq_result['id']}.html\' target=_blank>{$zq_result['borrow_name']}</a>]成功，待收增加";
					$re = accountClass::AddLog($log);
					if($account_result==false || $re==false){
						mysql_query("rollback");
						return false;
					}
				}
			}
			//出售用户的待收资金扣除
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$zq_result['user_id']));
			$borrow_log['user_id'] = $zq_result['user_id'];
			$borrow_log['type'] = "zqcs_ksds";
			$borrow_log['money'] = $zq_result['y_account'];
			$borrow_log['total'] =$account_result['total']-$borrow_log['money'];
			$borrow_log['use_money'] = $account_result['use_money'];
			$borrow_log['no_use_money'] = $account_result['no_use_money'];
			$borrow_log['collection'] = $account_result['collection']-$borrow_log['money'];
			$borrow_log['to_user'] = "0";
			$borrow_log['borrow_id'] = $zq_result['borrow_id'];
			$borrow_log['remark'] = "[<a href=\'/zqzr/a{$zq_result['id']}.html\'>{$zq_result['borrow_name']}</a>]债权转让成功，待收扣除";
			$re = accountClass::AddLog($borrow_log);
			if($re == false || $account_result==false){
				mysql_query("rollback");
				return false;
			}

			//出售用户的资金增加
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$zq_result['user_id']));
			$borrow_log['user_id'] = $zq_result['user_id'];
			$borrow_log['type'] = "zqcs_true";
			$borrow_log['money'] = $zq_result['account'];
			$borrow_log['total'] =$account_result['total']+$borrow_log['money'];
			$borrow_log['use_money'] = $account_result['use_money']+$borrow_log['money'];
			$borrow_log['no_use_money'] = $account_result['no_use_money'];
			$borrow_log['collection'] = $account_result['collection'];
			$borrow_log['to_user'] = "0";
			$borrow_log['borrow_id'] = $zq_result['id'];
			$borrow_log['remark'] = "通过[<a href=\'/zqzr/a{$zq_result['id']}.html\'>{$zq_result['borrow_name']}</a>]债权转让获得的款";
			$re = accountClass::AddLog($borrow_log);
			if($re == false || $account_result==false){
				mysql_query("rollback");
				return false;
			}
			//托管
			if(IS_TG){
				$juser_result = $mysql->db_fetch_array("select * from {user} where user_id=".$zq_result['user_id']);
				$a = tg_reviewzq($zq_result,$juser_result);
				if($a['pErrCode']=='0000'){
					mysql_query("commit");
					return true;
				}else{
					mysql_query("rollback");
					return $a['pErrMsg'];
				}
			}
		}else{//不通过
		/*
			$re = $mysql->db_query('update `{creditor_record}` set status=5 where `transfer_id`='.$zq_result['id']);
			$re_1 = $mysql->db_query('update  `{borrow_collection}` set `transfer_id`=0 and `is_sell`=0 where `is_sell`=2 and `transfer_id`='.$zq_result['id']);
			if($re==false || $re_1==false){
				mysql_query("rollback");
				return false;
			}*/
			$a = $this->_canl($zq_result['id']);
			if($a===false){
				mysql_query("rollback");
				return false;
			}
			if(IS_TG){
				if(!empty($a)){
					$juser_result = $mysql->db_fetch_array("select * from {user} where user_id=".$zq_result['user_id']);
					$a = tg_liubiao($zq_result,$juser_result,$a);
					if($a['pErrCode']=='0000'){
						mysql_query("commit");
						return true;
					}else{
						mysql_query("rollback");
						return $a['pErrMsg'];
					}
				}
			}
			 /*
			foreach($record as $_k=>$_v){
				$account_result =  accountClass::GetOneAccount(array("user_id"=>$_v['user_id']));
				$log['user_id'] = $_v['user_id'];
				$log['type'] = "zqgm_false";//债权购买失败
				$log['money'] = $_v['account'];
				$log['total'] = $account_result['total'];
				$log['use_money'] = $account_result['use_money']+$log['money'];
				$log['no_use_money'] = $account_result['no_use_money']-$log['money'];
				$log['collection'] = $account_result['collection'];
				$log['to_user'] = 0;
				$log['remark'] = "购买债权[<a href=\'/zqzr/a{$zq_result['id']}.html\' target=_blank>{$zq_result['borrow_name']}</a>]失败返回的金额";
				$re = accountClass::AddLog($log);
				if($re==false || $account_result==false){
					mysql_query("rollback");
					return false;
				}
			}*/
		}
		mysql_query("commit");
		return true;
	}
	//用户或者网站还款，撤回相关标的债权转让
	public function transfer_cf($data=array()){
		global $mysql;
		$borrow_id = $data['borrow_id'];
		$status = $data['status'];//1撤回
		$re = $mysql->db_fetch_arrays('select id from `{creditor_transfer}` where status in(0,1) and borrow_id='.$borrow_id);
		foreach($re as $k=>$v){
			$a = $this->_canl($v['id']);
			if($a==false){
				return false;
			}
		}
		return true;
	}
	public function _canl($id){
		global $mysql;
		$re = $mysql->db_query('update  `{creditor_transfer}` set status=5 where id='.$id);
		$re_1 = $mysql->db_query('update  `{creditor_record}` set status=5 where `transfer_id`='.$id);
		$re_2 = $mysql->db_query('update  `{borrow_collection}` set `transfer_id`=0 and `is_sell`=0 where `is_sell`=2 and `transfer_id`='.$id);
		
		if ($re==false || $re_1==false || $re_2==false){
			return false;
		}
		$tg_result = array();
		$render_list = $mysql->db_fetch_arrays('select p1.*,p2.borrow_id,p3.name as borrow_name,p4.pIpsAcctNo from `{creditor_record}` as p1 left join  {creditor_transfer} as p2 on p1.transfer_id=p2.id left join {borrow} as p3 on p2.borrow_id=p3.id left join {user} as p4 on p1.user_id=p4.user_id where p1.`status`=5 and  p1.`transfer_id`='.$id);
		foreach($render_list as $key=>$value){
			$account_result =  accountClass::GetOneAccount(array("user_id"=>$value['user_id']));
			$log['user_id'] = $value['user_id'];
			$log['type'] = "zqgm_false";//债权购买失败
			$log['money'] = $value['account'];
			$log['total'] = $account_result['total'];
			$log['use_money'] = $account_result['use_money']+$log['money'];
			$log['no_use_money'] = $account_result['no_use_money']-$log['money'];
			$log['collection'] = $account_result['collection'];
			$log['to_user'] = 0;
			$log['remark'] = "购买债权[<a href=\'/zqzr/a{$value['transfer_id']}.html\' target=_blank>{$value['borrow_name']}</a>]失败返回的金额";
			$re = accountClass::AddLog($log);
			if($re==false || $account_result==false){
				return false;
			}
			$tg_result[] = array('pIpsAcctNo'=>$value['pIpsAcctNo'], 'account'=>$value['account']);
		}
		if(IS_TG){
			return $tg_result;
		}
		return true;
	}
	//网站直接收购债权
	public function websg($data=array()){
		global $mysql;
		if (!isset($data['id']) || $data['id']==""){
			return false;
		}
		$re = $this->get_zqzr_list(array('id'=>$data['id']));
		$zq_result = $re['list'][0];
		mysql_query("start transaction");//开启事务
		
		$collection_id = explode(',',$zq_result['collection_id']);
		$in = '';
		foreach($collection_id as $k=>$v){
			$in .= ','.$v;
		}
		$in = substr($in, 1);
		$sql = 'select * from `{borrow_collection}` where id in('.$in.')';
		//跟新collection已还
		$re = $mysql->db_query('update `{borrow_collection}` set `is_sell`=1,`status`=1 where id in('.$in.')');
		if($re==false){
			mysql_query("rollback");
			return false;
		}
		$re = $mysql->db_query("update {creditor_transfer} set `status`=3,`success_time`={$data['verify_time']},`success_user`={$data['verify_user']},`success_remark`='网站收购' where `id`=".$zq_result['id']);
		if($re==false){
			mysql_query("rollback");
			return false;
		}
		require_once ROOT_PATH.'modules/account/account.class.php';
		//出售用户的待收资金扣除
		$account_result =  accountClass::GetOneAccount(array("user_id"=>$zq_result['user_id']));
		$borrow_log['user_id'] = $zq_result['user_id'];
		$borrow_log['type'] = "zqcs_ksds";
		$borrow_log['money'] = $zq_result['y_account'];
		$borrow_log['total'] =$account_result['total']-$borrow_log['money'];
		$borrow_log['use_money'] = $account_result['use_money'];
		$borrow_log['no_use_money'] = $account_result['no_use_money'];
		$borrow_log['collection'] = $account_result['collection']-$borrow_log['money'];
		$borrow_log['to_user'] = "0";
		$borrow_log['borrow_id'] = $zq_result['id'];
		$borrow_log['remark'] = "[<a href=\'/invest/a{$zq_result['borrow_id']}.html\'>{$zq_result['borrow_name']}</a>]债权转让成功，待收扣除";
		$re = accountClass::AddLog($borrow_log);
		if($re == false || $account_result==false){
			mysql_query("rollback");
			return false;
		}

		//出售用户的资金增加
		$account_result =  accountClass::GetOneAccount(array("user_id"=>$zq_result['user_id']));
		$borrow_log['user_id'] = $zq_result['user_id'];
		$borrow_log['type'] = "zqcs_true";
		$borrow_log['money'] = $zq_result['account'];
		$borrow_log['total'] =$account_result['total']+$borrow_log['money'];
		$borrow_log['use_money'] = $account_result['use_money']+$borrow_log['money'];
		$borrow_log['no_use_money'] = $account_result['no_use_money'];
		$borrow_log['collection'] = $account_result['collection'];
		$borrow_log['to_user'] = "0";
		$borrow_log['borrow_id'] = $zq_result['id'];
		$borrow_log['remark'] = "通过[<a href=\'/invest/a{$zq_result['borrow_id']}.html\'>{$zq_result['borrow_name']}</a>]债权转让获得的款";
		$re = accountClass::AddLog($borrow_log);
		if($re == false || $account_result==false){
			mysql_query("rollback");
			return false;
		}
		mysql_query("commit");
		return true;
	}
}

?>