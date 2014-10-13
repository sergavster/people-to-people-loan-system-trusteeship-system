<?php
/**
 * @author Tissot.Cai(Email:Tissot.Cai@gmail.com)
 * @copyright Tissot.Cai
 * @version 1.0
 */

/**
 * Description of credit
 *
 * @author TissotCai
 */
class creditClass {

	const ERROR = '操作有误，请不要乱操作';
	const UPDATE_TYPE_CODE_ERROR = '积分类型代码错误';
	const CREDIT_TYPE_ID_NO_EMPTY = '类型ID不能为空';
	
	
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		
		$username = empty($data['username'])?"":$data['username'];
	
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?20:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		if (!empty($username)){
			$_sql .= " and p2.username= '{$username}'";
		}
		
		$sql = "select SELECT from {credit} as p1 
		left join {user} as p2 on p1.user_id=p2.user_id 
		left join {credit_rank} as p3 on p1.value<=p3.point2  
		$_sql and p1.value>=p3.point1 ORDER LIMIT";
				 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.*,p3.pic', 'order by p1.addtime desc', $limit), $sql));		
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
	 * 列表
	 *
	 * @return Array
	 */
	function GetLogList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?20:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p1.user_id={$data['user_id']}";
		}		 
		if (isset($data['username']) && $data['username']!=""){
			$_sql .= " and p1.username={$data['username']}";
		}
		$_select  = "p1.*,p2.username,p2.realname,p3.name as type_name";
		$sql = "select SELECT from {credit_log} as p1 left join {user} as p2 on p1.user_id=p2.user_id left join {credit_type} as p3 on p1.type_id=p3.id $_sql ORDER LIMIT";
			//是否显示全部的信息
			
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			$sql = str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select , '', $_limit), $sql);
			
			return $mysql->db_fetch_arrays($sql);
		}	 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.addtime desc', $limit), $sql));		
		$list = $list?$list:array();
		
		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	
	
	
	//const
	/**
	 * 更新积分
	 * @param $user_id 会员ID
	 * @param $credit_type_code 积分类型代码
	 * @param $value 变动积分值
	 * @param $op_user 操作者
	 */
	public static function UpdateCredit ($data = array()) {
		//require_once ROOT_PATH . 'api/uc.php';
		global $mysql, $module;
		$user_id = $data['user_id'];
		$nid = $data['nid'];
		$value = (int)$data['value'];
		$op_user = $data['op_user'];
		
		$now = time();
		$now_date = strtotime(date('Y-m-d'));
		
		$credit_type = $mysql->db_fetch_array("select * from {credit_type} where nid='{$data['nid']}'");
		if (!$credit_type) {
			return false;
			//return self::UPDATE_TYPE_CODE_ERROR;
		}
		//$value = 0==$value?(int)$credit_type['value']:(int)$value;
		$type_id = (int)$credit_type['id'];
		$cycle = (int)$credit_type['cycle'];
		$type_name = $credit_type['name'];
		$award_times = (int)$credit_type['award_times'];
		$interval    = (int)$credit_type['interval'];
		
		switch ($cycle) {
			case 1:
				if ($mysql->db_fetch_array("select 1 from {credit_log} where user_id={$user_id} and type_id={$type_id}")) {
					return true;
				}
				break;
			case 2:
				$row = $mysql->db_fetch_array("select count(1) as cnt from {credit_log} where user_id={$user_id} and type_id={$type_id} and addtime>={$now_date}");
				if ($row['cnt'] >= $award_times && 0 != $award_times) {
					return true;
				}
				break;
			case 3:
				$start_time = $now - $interval * 60;
				$row = $mysql->db_fetch_array("select count(1) as cnt from {credit_log} where user_id={$user_id} and type_id={$type_id} and addtime>={$start_time}");
				if ($row['cnt'] >= $award_times && 0 != $award_times) {
					return true;
				}
				break;
			case 4:
				$row = $mysql->db_fetch_array("select count(1) as cnt from {credit_log} where user_id={$user_id} and type_id={$type_id}");
				if ($row['cnt'] >= $award_times && 0 != $award_times) {
					return true;
				}
				break;
		   default :
			   return false;
			   break;
		}

		$credit = $mysql->db_fetch_array("select 1 from {credit} where user_id={$user_id}");
		$result = false;
		# 添加
		if (!$credit) {
			$_data = array(
				'user_id' => $user_id,
				'value' => $value,
				'op_user' => $op_user
			);
			$result = $mysql->db_add('credit', $_data);
		}
		# 更新
		else {
			$ip = ip_address();
			$result = $mysql->db_query("
				update {credit} set value=value+{$value},
					op_user='{$op_user}',updatetime={$now},updateip='{$ip}'
					where user_id={$user_id}
			");
			
		}
		//(user_cache)更新缓存的积分
		$sql = "update `{user_cache}` set credit = credit +{$value} where user_id={$user_id}";
		$result = $mysql->db_query($sql);
		
		if (!$result) {
			return false;
		}
		unset($data['nid']);
		$data['type_id'] = $type_id;
		self::AddLog($data);
		return true;
	}


	/**
	 * 获取积分
	 * @param $user_id 会员ID
	 * @return int
	 */
	public static function GetCredit ($data = array()) {
		global $mysql, $module;
		
		$user_id = $data['user_id'];
		$row = $mysql->db_fetch_array("select uc_user_id from {ucenter} where user_id={$user_id}");
		if (!$row) {
			return 0;
		}

		# discuzx 积分
		$dzx = 0;
		if ($module->get_module('ucenter')) {
			$dzx = (int)UcenterClient::GetCredit($row['uc_user_id']);
		}

		# 本系统积分
		$credit = $mysql->db_fetch_array("select value from {credit} where user_id={$user_id}");

		return (int)$credit['value'] + $dzx;
	}
	
	/**
	 * 会员积分日志
	 * @param $user_id 会员ID
	 * @param $value 变动数值
	 * @param $credit_type_id 积分类型ID
	 * @param $remark 备注
	 * @param $op_user 操作者
	 * @return bool
	 */
	private static function AddLog ($data = array()) {
		global $mysql;

		$data['op'] = $data['value']>0?1:2;
		
		$sql = "insert into `{credit_log}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		
		$mysql->db_query($sql);

		return true;
	}
	
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetTypeList($data = array()){
		global $mysql;
		$name = empty($data['name'])?"":$data['name'];
	
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";		 
		if (!empty($name)){
			$_sql .= " and p1.name like '%{$name}%'";
		}
		
		$sql = "select SELECT from {credit_type} as p1 $_sql ORDER LIMIT";
				 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*', 'order by p1.id desc', $limit), $sql));		
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
	public static function GetTypeOne($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		if (isset($data['id'])){
			$_sql .= "and p1.id='{$data['id']}' ";
		}
		if (isset($data['nid'])){
			$_sql .= "and p1.nid='{$data['nid']}' ";
		}
		$sql = "select * from {credit_type} as p1 $_sql ";
		return $mysql->db_fetch_array($sql);
	}
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AddType($data = array()){
		global $mysql;
        if ($data['name'] == "" ) {
            return self::CREDIT_TYPE_ID_NO_EMPTY;
        }
		
		$sql = "insert into `{credit_type}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			if($value==NULL){
				$sql .= ",`$key` = null";
			}else{
				$sql .= ",`$key` = '{$value}'";
			}
		}
		
        return $mysql->db_query($sql);
	}
	
	
	/**
	 * 修改
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function UpdateType($data = array()){
		global $mysql;
		$id = $data['id'];
        if ($data['id'] == "") {
            return self::ERROR;
        }
		
		$_sql = "";
		$sql = "update `{credit_type}` set ";
		foreach($data as $key => $value){
			
			if($value==NULL){
				$_sql[] .= "`$key` = null";
			}else{
				$_sql[] .= "`$key` = '$value'";
			}
		}
		$sql .= join(",",$_sql)." where id = '$id'";
        return $mysql->db_query($sql);
	}
	
	
	
	/**
	 * 删除
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function DeleteType($data = array()){
		global $mysql;
		$id = $data['id'];
		if (!is_array($id)){
			$id = array($id);
		}
		$sql = "delete from {credit_type}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	
	/**
	 * 类型排序
	 *
	 * @param Integer $type_id 
	 * @return boolen
	 */
	public static function OrderType($data = array()){
		global $mysql; 
		$type_id = $data['id'];
		$order = $data['order'];
		if ($type_id == "" || $order == "" ) return self::ERROR;
		foreach ($type_id as $key => $id){
			$sql = "update `{credit_type}` set `order`='".$order[$key]."' where type_id=$id";
			$mysql->db_query($sql);
		}
		return true;
	}
	
	/**
	 * 等级列表
	 *
	 * @return Array
	 */
	function GetRankList(){
		global $mysql;
		$sql = "select * from {credit_rank} ";
		return  $mysql->db_fetch_arrays($sql);
	}
	
	/**
	 * 等级修改
	 *
	 * @return Array
	 */
	function ActionRank($data = array()){
		global $mysql; 
		$type_id = $data['id'];
		$name = $data['name'];
		$rank = $data['rank'];
		$point1 = $data['point1'];
		$point2 = $data['point2'];
		$pic = $data['pic'];
		if ($type_id == "" ) return self::ERROR;
		foreach ($type_id as $key => $id){
			$sql = "update `{credit_rank}` set `name`='".$name[$key]."',`rank`='".$rank[$key]."',`point1`='".$point1[$key]."',`point2`='".$point2[$key]."',`pic`='".$pic[$key]."' where id=$id";
			$mysql->db_query($sql);
		}
		return true;
	}
	
	/**
	 * 等级修改
	 *
	 * @return Array
	 */
	function AddRank($data = array()){
		global $mysql;
        if ($data['name'] == "" ) {
            return self::ERROR;
        }
		$sql = "insert into `{credit_rank}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        return $mysql->db_query($sql);
	}
	
	/**
	 * 删除
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function DeleteRank($data = array()){
		global $mysql;
		$id = $data['id'];
		if (!is_array($id)){
			$id = array($id);
		}
		$sql = "delete from {credit_rank}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
}
?>
