<?php
/*
 * 	标基类业务逻辑类
发标处理 add
担保处理 vouch
投标处理 tender
流标处理（含到期和主动撤消）cancel
审核处理 verify
满标审核处理 full_verify
还款处理 repay
逾期处理 late_repay

*/
/*
//liukun add for bug 52 begin
$firePHPEnable=TRUE;
if ($firePHPEnable){
	require_once(ROOT_PATH.'modules/FirePHPCore/FirePHP.class.php');
	require_once(ROOT_PATH.'modules/FirePHPCore/fb.php');
	ob_start();

	$firephp = FirePHP::getInstance(true);
}*/
//liukun add for bug 52 end
class biaotypeClass{
	protected $biao_type = "";
	function get_auto_verify(){
		global $mysql, $_G;
		if (isset($_G['biao_type'][$this->biao_type])){
			$result = $_G['biao_type'][$this->biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$this->biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}

		return $result['auto_verify'];

	}
	function get_biaotype_info(){
		global $mysql, $_G;
		if(isset($_G['biao_type'][$this->biao_type])){
			return $_G['biao_type'][$this->biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$this->biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
			$_G['biao_type'][$this->biao_type]=$result;
			return $result;
		}
	}
	function get_auto_full_verify(){
		global $mysql, $_G;
		if (isset($_G['biao_type'][$this->biao_type])){
			$result = $_G['biao_type'][$this->biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$this->biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}

		return $result['auto_full_verify'];

	}

	function get_borrow_fee_rate(){
		global $mysql, $_G;

		if (isset($_G['biao_type'][$this->biao_type])){
			$result = $_G['biao_type'][$this->biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$this->biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}

		$fee_rate['borrow_fee_rate_start'] = $result['borrow_fee_rate_start'];
		$fee_rate['borrow_fee_rate_start_month_num'] = $result['borrow_fee_rate_start_month_num'];
		$fee_rate['borrow_fee_rate'] = $result['borrow_fee_rate'];
		$fee_rate['borrow_day_fee_rate'] = $result['borrow_day_fee_rate'];
		$fee_rate['borrow_fee_rate_max'] = $result['borrow_fee_rate_max'];
		return $fee_rate;
	}

	function get_interest_fee_rate(){
		global $mysql, $_G;

		if (isset($_G['biao_type'][$this->biao_type])){
			$result = $_G['biao_type'][$this->biao_type];
		}else{
			$sql = "select interest_fee_rate from `{biao_type}` where biao_type_name='{$this->biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}
		return $result['interest_fee_rate'];
	}

	function get_frost_rate(){
		global $mysql, $_G;
		if (isset($_G['biao_type'][$this->biao_type])){
			$result = $_G['biao_type'][$this->biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$this->biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}
		return $result['frost_rate'];
	}

	function get_advance(){

		global $mysql, $_G;

		if (isset($_G['biao_type'][$this->biao_type])){
			$result = $_G['biao_type'][$this->biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$this->biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}

		$advance['advance_time'] = $result['advance_time'];
		$advance['advance_scope'] = $result['advance_scope'];
		$advance['advance_rate'] = $result['advance_rate'];
		$advance['advance_vip_scope'] = $result['advance_vip_scope'];
		$advance['advance_vip_rate'] = $result['advance_vip_rate'];

		return $advance;
	}

	function get_late_interest_rate(){
		global $mysql, $_G;

		if (isset($_G['biao_type'][$this->biao_type])){
			$result = $_G['biao_type'][$this->biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$this->biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}

		$late_interest_rate['late_interest_rate'] = $result['late_interest_rate'];
		$late_interest_rate['late_customer_interest_rate'] = $result['late_customer_interest_rate'];
		$late_interest_rate['late_interest_scope'] = $result['late_interest_scope'];

		return $late_interest_rate;
	}

	function get_max_tender_times(){
		global $mysql, $_G;
		if (isset($_G['biao_type'][$this->biao_type])){
			$result = $_G['biao_type'][$this->biao_type];
		}else{
			$sql = "select * from `{biao_type}` where biao_type_name='{$this->biao_type}'";
			$result = $mysql ->db_fetch_array($sql);
		}
		return $result['max_tender_times'];
	}

	/**
	 * 发标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function add($data = array()){
		global $mysql;


		return true;
	}

	/**
	 * 担保
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function vouch($data = array()){
		global $mysql;


		return true;
	}

	/**
	 * 用户投标
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function tender($data = array()){
		global $mysql;


		return true;
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

	/**
	 * 发标审核审核
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function verify($data = array()){
		global $mysql;


		return true;
	}

	/**
	 * 满标审核
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function full_verify($data = array()){
		global $mysql;


		return true;
	}

	/**
	 * 还款
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function repay($data = array()){
		global $mysql;


		return true;
	}

	/**
	 * 逾期处理
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function late_repay($data = array()){
		global $mysql;
		return true;
	}

	/**
	 * 获得标的附加信息
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function getAdditionalInfo($data = array()){
		global $mysql;


		return true;
	}
	/**
	 * 获得逾期利息
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function getLateInterest($data = array()){
		global $mysql;
		$biao_type = $data['biao_type'];
		$late_interest_rate = self::get_late_interest_rate();
		//1:逾期利息是应还本息的基础上罚息
		//0:逾期利息是应还本金的基础上罚息
		if ($late_interest_rate['late_interest_scope'] == 1){
			$loan_account = $data['repayment_account'];
		}else{
			$loan_account = $data['capital'];
		}
		$current_time = isset($data['tg_repayment_time'])?$data['tg_repayment_time']:time();
		$late_rate=$late_interest_rate['late_interest_rate'];
		$now_time = get_mktime(date("Y-m-d",$current_time));
		$repayment_time = get_mktime(date("Y-m-d",$data['repayment_time']));
		$late_days = ($now_time - $repayment_time)/(60*60*24);
		$_late_days = explode(".",$late_days);
		$late_days = ($_late_days[0]<0)?0:$_late_days[0];
		$late_interest = round($loan_account*$late_rate*$late_days,2);
		$interest_result['late_days'] = $late_days;
		$interest_result['late_interest'] = $late_interest;
		return $interest_result;
	}
	/**
	 * 获得逾期利息
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function getLateCustomerInterest($data = array()){
		global $mysql;

		$late_interest_rate = self::get_late_interest_rate();
		//1:逾期利息是应还本息的基础上罚息
		//0:逾期利息是应还本金的基础上罚息
		if ($late_interest_rate['late_interest_scope'] == 1){
			$loan_account = $data['repay_account'];
		}else{
			$loan_account = $data['capital'];
		}
		$now_time = get_mktime(date("Y-m-d",time()));
		$repayment_time = get_mktime(date("Y-m-d",$data['repay_time']));
		$late_days = ($now_time - $repayment_time)/(60*60*24);
		$_late_days = explode(".",$late_days);
		$late_days = ($_late_days[0]<0)?0:$_late_days[0];


		$late_customer_rate=$late_interest_rate['late_customer_interest_rate'];
		// 		if ($data["status"] == 2){
		// 			//如果网站已经垫付，投资人应得逾期利息就要根据垫付时间点计算

		// 			$repayment_time = get_mktime(date("Y-m-d",$data['repayment_time']));
		// 			$advance_time = get_mktime(date("Y-m-d",$data['advance_time']));
		// 			$advance_days = ($advance_time - $repayment_time)/(60*60*24);
		// 			$_advance_days = explode(".",$advance_days);
		// 			$advance_days = ($_advance_days[0]<0)?0:$_advance_days[0];

		// 			$late_customer_interest = round($loan_account*$late_customer_rate*$advance_days,2);
		// 		}
		// 		else{
		// 			//如果网站还没有垫付，那投资人应得逾期利息就是所有逾期时间的利息

		// 			$late_customer_interest = round($loan_account*$late_customer_rate*$late_days,2);
		// 		}

		if ($data["status"] == 2){
			//如果网站已经垫付，投资人没有逾期利息收入
			$late_customer_interest = 0;
		}
		else{
			$late_customer_interest = round($loan_account*$late_customer_rate*$late_days,2);
		}


		$interest_result['late_days'] = $late_days;
		$interest_result["late_customer_interest"] = $late_customer_interest;

		return $interest_result;
	}

	/**
	 * 获得借款手续费
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function getBorrowFee($data = array()){
		global $mysql;
		$borrow_account = $data['account'];
		$month_times = $data['time_limit'];
		$isday = $data['isday'];
		$time_limit_day = $data['time_limit_day'];
		$fee_rate = self::get_borrow_fee_rate();
		$borrow_fee_rate_start = $fee_rate['borrow_fee_rate_start'];
		$borrow_fee_rate_start_month_num = $fee_rate['borrow_fee_rate_start_month_num'];
		$borrow_fee_rate = $fee_rate['borrow_fee_rate'];
		$borrow_day_fee_rate = $fee_rate['borrow_day_fee_rate'];
		$borrow_fee_rate_max = $fee_rate['borrow_fee_rate_max'];
		if($isday==1){
			$_fee_rate = $borrow_day_fee_rate/30*$time_limit_day;
			if($_fee_rate>$borrow_fee_rate_max){
				$_fee_rate = $borrow_fee_rate_max;
			}
			$borrow_fee=round($borrow_account*$_fee_rate,2);
		}else{
			$_fee_rate = $borrow_fee_rate_start + (($month_times - $borrow_fee_rate_start_month_num)>0?($month_times - $borrow_fee_rate_start_month_num)*$borrow_fee_rate:0);
			if($_fee_rate>$borrow_fee_rate_max){
				$_fee_rate = $borrow_fee_rate_max;
			}
			$borrow_fee = round($borrow_account*$_fee_rate,2);
		}
		return $borrow_fee;
	}
	/*
	 * 获取个标种提现费用，提现比例
	 */
	function get_cash_cost($data=array()){
		global $mysql, $_G;
		if (isset($_G['biao_type'][$data['biao_type']])){
			$result = $_G['biao_type'][$data['biao_type']];
		}else{
			$sql = "select extract_rate from `{biao_type}` where biao_type_name='{$data['biao_type']}'";
			$result = $mysql ->db_fetch_array($sql);
		}
		return array('extract_rate'=>$result['extract_rate']);
	}
	function getWebRepayInfo($data=array()){
		global $mysql,$_G;
		$sql = "select p1.id,p1.repay_account,p1.interest,p1.capital,p3.username,p3.user_id,p4.vip_status
		from `{borrow_collection}` p1 left join `{borrow_tender}` p2
		on p1.tender_id=p2.id left join `{user}` p3 on p2.user_id=p3.user_id
		left join `{user_cache}` p4 on p3.user_id=p4.user_id
		where p2.borrow_id={$data['borrow_id']} and
		p1.`order`={$data['order']}";
		$result = $mysql->db_fetch_arrays($sql);
		if(!isset($_G['biao_type'][$this->biao_type])){
			self::get_biaotype_info();
		}
		foreach($result as $key=>$value){
			if($value['vip_status']==1){
				$advance = $_G['biao_type'][$this->biao_type]['advance_vip_scope'];
				$bili = $_G['biao_type'][$this->biao_type]['advance_vip_rate'];
			}else{
				$advance = $_G['biao_type'][$this->biao_type]['advance_scope'];
				$bili = $_G['biao_type'][$this->biao_type]['advance_rate'];
			}
			if($advance==0){
				$result[$key]['webrepay_capital'] = 0;
				$result[$key]['webrepay_interest'] = 0;
			}elseif($advance==1){
				$result[$key]['webrepay_capital'] = $value['capital']*$bili;
				$result[$key]['webrepay_interest'] = 0;
			}elseif($advance==2){
				$result[$key]['webrepay_capital'] = $value['capital']*$bili;
				$result[$key]['webrepay_interest'] = $value['interest']*$bili;
			}
			$result[$key]['bili']=$bili;
			$result[$key]['webrepay_account']=$result[$key]['webrepay_capital']+$result[$key]['webrepay_interest'];
		}
		return $result;
	}
}