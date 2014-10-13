<?
/******************************
 * $File: user.class.php
 * $Description: 数据库处理文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class applyClass {

	const ERROR = '操作有误，请不要乱操作';
	const APPLY_NO_START_APPLY = '你所报的活动还没开始报名，请稍后再来';
	const APPLY_FULL = '报名人数已满';
	const APPLY_END_APPLY = '此活动已经结束报名，请及时关注我们的网站';
	const APPLY_REAPPLY = '你已经报过此活动，不能重复报名';
	const APPLY_NOT_EXISTS = '你所报的活动不存在,请不要乱操作';

	const CANCELAPPLY_END_TIME = '报名即将结束，不能取消';
	
	/**
	 * 活动报名
	 * @param $id 活动ID
	 * @param $user_id 会员ID
	 * @return
	 *		HuoDong::CANCELAPPLY_END_TIME 报名即将结束，不能取消<br>
	 *		HuoDong::APPLY_FULL 报名人数已满<br>
	 *		HuoDong::APPLY_END_APPLY 活动结束报名<br>
	 *		HuoDong::APPLY_REAPPLY 重复报名<br>
	 *		HuoDong::APPLY_NOT_EXISTS 活动不存在<br>
	 *		true 成功
	 */
	public static function Apply ($data = array()) {
		global $mysql;
		
		$result = self::Check($data);
		if ($result!==true){
			return $result;
		}
		$code = $data['code'];
		$id = $data['id'];
		
		$data = array(
			'code'=>$code,
			'order'=>10,
			'aid' => $id,
			'user_id' =>  $data['user_id'],
			'username' =>  $data['username'],
			'realname' => $data['realname'],
			'phone' => $data['phone'],
			'email' => $data['email'],
			'qq' => $data['qq'],
			'addtime' => time(),
			'addip' => ip_address(),
			'status' => 0
		);
		foreach ($data as $key => $val){
			$_data[] = "`{$key}`='{$val}'";
		}
		$sql = "insert into `{apply}` set ".join(",",$_data);
		$result = $mysql->db_query($sql);
		if (true !== $result) {
			return $result;
		}

		$sql = "update `{".$code."}` set apply_yesman = apply_yesman+1 where id={$id}";
		$mysql->db_query($sql);
		
		return true;
	}
	
	function Check($data = array()){
		global $mysql;
		$code = $data['code'];
		$id = $data['id'];
		$user_id = $data['user_id'];
		if ($user_id=="") return "你还没登陆，请先登录";
		$result = $mysql->db_fetch_array("select * from `{".$code."}` where id={$id}");
		if (!$result) {
			return self::APPLY_NOT_EXISTS;
		}
		
		if (1 != $result['status'] ) {
			return self::APPLY_NO_START_APPLY;
		}
		if ($result['apply_yesman'] >= $result['apply_man']) {
			return self::APPLY_FULL;
		}
		if (time() > $result['apply_endtime']) {
			return self::APPLY_END_APPLY;
		}
		$sql = "select 1 from {apply} where code='{$code}' and  aid={$id} and user_id={$user_id}";
		if ($mysql->db_fetch_array($sql)) {
			return self::APPLY_REAPPLY;
		}
		return true;
	}
	
	/**
	 * 取消活动报名
	 * @param $id 活动ID
	 * @param $user_id 会员ID
	 * @return
	 *		HuoDong::CANCELAPPLY_END_TIME 报名即将结束，不能取消<br>
	 *		true 成功
	 */
	public static function CancelApply($data = array()) {
		global $mysql;
		$code = $data['code'];
		$id = $data['id'];
		$user_id = $data['user_id'];
		if (!$mysql->db_fetch_array("select 1 from `{apply}` where code='".$code."' and aid={$id} and user_id={$user_id}")) {
			return "您的操作有误，此报名不存在。";
		}
		$hd = $mysql->db_fetch_array("select apply_endtime from {".$code."} where id={$id}");
		if ($hd['apply_endtime'] <= time() - 60*60*1) {
			return self::CANCELAPPLY_END_TIME;
		}

		$sql = "
			update `{".$code."}` set apply_yesman=apply_yesman-1 where id={$id};
			delete from `{apply}` where code='".$code."' and aid={$id} and user_id={$user_id};
			";
		return $mysql->db_querys($sql);
		
	}
	
	
	//获得单条报名信息
	function GetApplyOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";		 
		if (isset($data['id']) && $data['id']!=""){
			$_sql .= " and p1.id = {$data['id']}";
		}	 
		
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p1.user_id = {$data['user_id']}";
		}
		
		if (isset($data['code']) && $data['code']!=""){
			$_sql .= " and p1.code = '{$data['code']}'";
		}
		
		$sql = "select * from `{apply}` as p1 {$_sql}";
		
		return $mysql->db_fetch_array($sql);
	}
	
	//所有的报名处理
	function ActionApply ($data= array()){
		global $mysql;
		$sql = array();
		$code = $data['code'];
		$id = $data['aid'];
		$apply_list = self::GetApplyList(array("code"=>$data['code'],"id"=>$data['aid'],"limit"=>"all"));
		$_cp = 0;
		if ($apply_list!=false){
			foreach ($apply_list as $key => $value){
				$_apply_list[] = $value['username'];
				//先删除
				if (!in_array($value['username'],$data['username'])){
					$sql[] = "delete from `{apply}` where username = '{$value['username']}' and code='{$data['code']}' and aid='{$data['aid']}'";
					$_cp ++;
				}
			}
			
		}
		
		
		//添加或修改
		$_add = 0;
		foreach ($data['username'] as $key => $value){
			if ($value!=""){
				if (in_array($value,$_apply_list)){
					$sql[] = "update `{apply}` set realname='{$data['realname'][$key]}',phone='{$data['phone'][$key]}',email='{$data['email'][$key]}',qq='{$data['qq'][$key]}',status='{$data['status'][$key]}',dotime='{$data['dotime'][$key]}',credit='{$data['credit'][$key]}',remark='{$data['remark'][$key]}' where username = '{$value}' and code='{$data['code']}' and aid='{$data['aid']}'";
				}else{
					$_sql = "select user_id from `{user}` where username = '{$value}'";
					$result = $mysql->db_fetch_array($_sql);
					if($result == false){
						$_username[] = $value; 
					}else{
						$sql[$key] = "insert into `{apply}` set realname='{$data['realname'][$key]}',
						phone='{$data['phone'][$key]}',
						email='{$data['email'][$key]}',
						qq='{$data['qq'][$key]}',
						status='{$data['status'][$key]}',
						dotime='{$data['dotime'][$key]}',
						credit='{$data['credit'][$key]}',
						remark='{$data['remark'][$key]}',
						aid='{$data['aid']}',
						code='{$data['code']}',
						`order`=10,
						user_id='{$result['user_id']}',
						addtime ='".time()."',
						addip = '".ip_address()."',
						username='{$value}' ";
						$_add ++;
					}
				}
			}
		}
		$mysql->db_querys(join(";",$sql));
		$sql = "update `{".$code."}` set apply_yesman = apply_yesman+$_add-$_cp where id={$id}";
		$mysql->db_query($sql);
		return true;
	}

/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetApplyList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		if (isset($data['id']) && $data['id']!=""){
			$_sql .= " and p1.aid = {$data['id']}";
		}
		
		if (isset($data['aid']) && $data['aid']!=""){
			$_sql .= " and p1.aid = {$data['aid']}";
		}
		
		if (isset($data['code']) && $data['code']!=""){
			$_sql .= " and p1.code = '{$data['code']}'";
		}
		
		$_select = "p1.*";
		$sql = "select SELECT
					from `{apply}` as p1
					 {$_sql}
				 ORDER LIMIT";
				 
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$result =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id ', $_limit), $sql));
			return $result;
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id ', $limit), $sql));		
		$list = $list?$list:array();
		
		
		return array(
            'list' => $list,
            'record_num' => $total,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
	
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetApplyPhList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		if (isset($data['id']) && $data['id']!=""){
			$_sql .= " and aid = {$data['id']}";
		}
		
		if (isset($data['code']) && $data['code']!=""){
			$_sql .= " and code = '{$data['code']}'";
		}
		$_order = " group by username order by num_dotime desc,user_id asc";
		$_select = "sum(dotime) as num_dotime,sum(credit) as num_credit,username,realname,user_id";
		$sql = "select SELECT from `{apply}` 
					 {$_sql}
				 ORDER LIMIT";
				 
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$result =  $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $_limit), $sql));
			return $result;
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(username) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));		
		$list = $list?$list:array();
		
		
		return array(
            'list' => $list,
            'record_num' => $total,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
	//或者报名总数和时间总数
	function GetApplyNum($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
		if(isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and `user_id` = '{$data['user_id']}'";
		}
		$sql = "select count(*) as num ,sum(dotime) as num_dotime,sum(credit) as num_credit from `{apply}` {$_sql}";
		
		return $mysql->db_fetch_array($sql);
	}

}
?>