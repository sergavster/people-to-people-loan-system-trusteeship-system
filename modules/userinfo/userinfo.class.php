<?
/******************************
 * $File: userinfo.class.php
 * $Description: 数据库处理文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class userinfoClass{
	
	const ERROR = '操作有误，请不要乱操作';

	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		$user_id = empty($data['user_id'])?"":$data['user_id'];
		$username = empty($data['username'])?"":$data['username'];
	
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		if (!empty($user_id)){
			$_sql .= " and 2.user_id = $user_id";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}
		
		$sql = "select SELECT from {user} as p2 
				 left join {userinfo} as p1 on p1.user_id=p2.user_id
				$_sql ORDER LIMIT";
				 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.username,p2.realname,p2.email ', 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		$vars['building'] = array("house_address","house_area","house_year","house_status","house_holder1","house_holder2","house_right1","house_right2","house_loanyear","house_loanprice","house_balance","house_bank");
		$vars['company'] = array("company_name","company_type","company_industry","company_office","company_jibie","company_worktime1","company_worktime2","company_workyear","company_tel","company_address","company_weburl","company_reamrk");
		$vars['firm'] = array("private_type","private_date","private_place","private_rent","private_term","private_taxid","private_commerceid","private_income","private_employee");
		$vars['finance'] = array("finance_repayment","finance_property","finance_amount","finance_car","finance_caramount","finance_creditcard");
		$vars['contact'] = array("tel","phone","post","address","province","city","area","linkman1","relation1","tel1","phone1","linkman2","relation2","tel2","phone2","linkman3","relation3","tel3","phone3","msn","qq","wangwang");
		$vars['mate'] = array("mate_name","mate_salary","mate_phone","mate_tel","mate_type","mate_office","mate_address","mate_income");
		$vars['edu'] = array("education_record","education_school","education_study","education_time1","education_time2");
		$vars['job'] = array("ability","interest","others","experience");
		foreach ($list as $key => $value){
			foreach ($vars as $_key => $_value){
				$list[$key][$_key.'_status'] =1;
				foreach ($_value as $__key=>$__value){
					if ($value[$__value] == ""){
						$list[$key][$_key.'_status'] =0;
					}
				}
			}
		}
		
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
	public static function GetOne($data = array()){
		global $mysql;
		$user_id = $data['user_id'];
		$sql = "select p1.* ,p2.username,p2.realname,p2.email  from {userinfo} as p1 
				  left join {user} as p2 on p1.user_id=p2.user_id where p1.user_id=$user_id
				";
		$result = $mysql->db_fetch_array($sql);
		if ($result == false) return "";
		$var = array();
		$vars['building'] = array("house_address","house_area","house_year");
		$vars['company'] = array("company_name","company_type","company_industry","company_office","company_jibie","company_worktime1","company_worktime2","company_workyear","company_tel","company_address","company_weburl","company_reamrk");
		$vars['firm'] = array("private_type","private_date","private_place","private_rent","private_term","private_taxid","private_commerceid","private_income","private_employee");
		$vars['finance'] = array("finance_repayment","finance_property","finance_amount","finance_car","finance_caramount","finance_creditcard");
		$vars['contact'] = array("tel","phone","post","address","province","city","area","linkman1","relation1","tel1","phone1","qq","wangwang");
		$vars['mate'] = array("mate_name","mate_salary","mate_phone","mate_tel","mate_type","mate_office","mate_address","mate_income");
		$vars['edu'] = array("education_record","education_school","education_study","education_time1","education_time2");
		$vars['job'] = array("ability","interest","others","experience");
		foreach ($vars as $key => $value){
			$result[$key."_status"] = 1;
			foreach ($value as $_key=>$_value){
				if ($result[$_value] == ""){
					$result[$key."_status"] = 0;
				}
			}
		}
		return $result;
	}
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Add($data = array()){
		global $mysql;
       
		$sql = "insert into `{userinfo}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        return $mysql->db_query($sql);
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
        if ($data['user_id'] == "") {
            return self::ERROR;
        }
		
		$_sql = "";
		$sql = "update `{userinfo}` set  `updatetime` = '".time()."',`updateip` = '".ip_address()."',";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where user_id = '$user_id'";
        return $mysql->db_query($sql);
	}
	/**
	 * 删除
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function Delete($data = array()){
		global $mysql;
		$id = $data['id'];
		if (!is_array($id)){
			$id = array($id);
		}
		$sql = "delete from {userinfo}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	/*
	 * 获取验证码
	 */
	public static function getCode($data=array()){
		global $mysql,$_A;
		$page = isset($data['page'])?$data['page']:$_A['page'];
		$epage = isset($data['epage'])?$data['epage']:$_A['epage'];
		$_sql = 'where 1=1';
		if(isset($data['username']) && $data['username']!=''){
			$re = $mysql->db_fetch_array("select user_id from `{user}` where username='{$data['username']}'");
			if ($re['user_id']>0){
				$_sql .= ' and p1.user_id='.$re['user_id'];
			}else{
				return false;
			}
		}
		if(isset($data['isuse']) && $data['isuse']!=''){
			$_sql .= " and p1.isuse='{$data['isuse']}'";
		}
		if(isset($data['itype']) && $data['itype']!=''){
			$_sql .= " and p1.itype='{$data['itype']}'";
		}
		if(isset($data['phone']) && $data['phone']!=''){
			$_sql .= " and p1.phone='{$data['phone']}'";
		}
		if(isset($data['iscanuse']) && $data['iscanuse']!=''){
			if($data['iscanuse']==1){
				$_sql .= " and p1.lasttime>='".time()."'";
			}
		}
		$sql = "select p1.*,p2.username from `{sms_check}` as p1 left join `{user}` as p2 on p1.user_id=p2.user_id _SQL _LIMIT";
		if(isset($data['limit'])){
			
		}else{
			$sql_1 = str_replace('_SQL', $_sql, "select count(1) total from `{sms_check}` as p1 _SQL");
			$total = $mysql->db_fetch_array($sql_1);
			$total = $total['total'];
			$total_page = ceil($total/$epage);
			
			$index = $epage * ($page - 1);
			$limit = " limit {$index}, {$epage}";
			$sql = str_replace(array('_SQL','_LIMIT'), array($_sql,$limit), $sql);
			$list = $mysql->db_fetch_arrays($sql);
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	
	/*
	 * 获取标的信息
	*/
	public static function get_userinfo_conf($data = array()){
		global $mysql;
		$usertype_id = $data['usertype_id'];
		$sql = "select * from `{userinfo_conf}` where `id`={$usertype_id}";
		$result = $mysql ->db_fetch_array($sql);
		return $result;
	}
	/*
	 * 获取业务员
	 */
	public function GetSalesmanList(){
		global $mysql;
		$re = $mysql->db_fetch_array("select type_id from rd_user_type where type=3");
		if($re==false || empty($re)){
			return false;
		}else{
			$sql = "select p1.user_id,p1.username from {user} p1 where p1.type_id={$re['type_id']}";
			return $mysql->db_fetch_arrays($sql);
		}
	}
	/*
	 * 获取阶层配置
	 */
	public function userinfo_infoconf($nid){
		global $_G,$mysql;
		$arr_nid = array(1=>'private',2=>'wageearners');
		$arr_id = array(1,2);
		if(in_array($nid, $arr_nid)){
			$id = array_search($nid, $arr_nid);
		}elseif(in_array($nid, $arr_id)){
			$id = $nid;
			$nid = $arr_nid[$id];
		}else{
			return false;
		}
		if(isset($_G[$nid])){
			return $_G[$nid];
		}else{
			$re = $mysql->db_fetch_array("select `conflist` from `{userinfo_conf}` where `id` = {$id}");
			return json_decode($re['conflist'],true);
		}
	}
	/*
	 * 获取阶层用户缺少的资料
	 */
	public function userinfo_no_data($data=array()){
		global $mysql,$_G,$user;
		$user_id = intval($data['user_id']);
		$nid = $data['user_type'];
		if ($user_id<1){
			return false;
		}
		if($_G['user_id']==$user_id){
			$userinfo = $_G['user_result'];
			$data_config = self::userinfo_infoconf($_G['user_result']['user_type']);
		}else{
			$userinfo = $user->GetOne(array("user_id"=>$user_id));
			if($userinfo==false){
				return false;
			}
			$data_config = self::userinfo_infoconf($userinfo['user_type']);
		}
		foreach($data_config as $k=>$v){
			if($v==1 && $_G['user_result'][$k]==""){
				return false;
			}
		}
		return true;
	}
}
?>