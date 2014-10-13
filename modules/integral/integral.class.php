<?php

class integralClass {


    const NOT_EXISTS_USER   = '用户不存在';
    const NOT_EXISTS_MODULE = '模块不存在';
	const NOT_ENOUGH_CREDIT = '积分不足';
	const NOT_ENOUGH_GOODS  = '物品不足';
	const NOT_ALLOW_CITY = '兑换城市限制';
    
	 /**
     * 获取折扣列表
	 * @param $where 条件 array('goods'=>'鞋子'...)
     * @param $page 页码
     * @param $page_size 每页记录数
     */
    public static function GetList ($data = array()) {
        global $mysql;

		
		$name = empty($data['name'])?"":$data['name'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where 1=1 ";
		if ($name!=""){
			$_sql .= " and p1.`name` like '%$name%'";
		}
		
		$sql = "select SELECT from {integral} as p1 left join {area} as p2 on p1.city=p2.id  {$_sql}   ORDER LIMIT";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.name as city_name', 'order by p1.`order` desc,p1.`id` desc', $_limit), $sql));
		}
		
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$sql = str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.name as city_name', 'order by p1.`order` desc,p1.`id` desc', $limit), $sql);
		$list = $mysql->db_fetch_arrays($sql);		
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
	 * 获取单条记录
	 * @param Array $data 
	 * @return Boolen
	 */
	public static function GetOne ($data = array()) {
		global $mysql;
		$id = $data['id'];
		$sql = "select SELECT
					from {integral} as p1 
				    where p1.id=$id ORDER ";

		return $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER'), array('p1.*', 'order by p1.id desc'), $sql));
	}
	
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Add($data = array()){
		global $mysql;
       
		$sql = "insert into `{integral}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
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
		$id = $data['id'];
        if ($data['id'] == "") {
            return self::ERROR;
        }
		
		$_sql = "";
		$sql = "update `{integral}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
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
	public static function Delete($data = array()){
		global $mysql;
		$id = $data['id'];
		if (!is_array($id)){
			$id = array($id);
		}
		$sql = "delete from {integral}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	
	 /**
     * 获取折扣兑换列表
	 * @param $data 
     */
    public static function GetConvertList ($data = array()) {
        global $mysql;

		
		$name = empty($data['name'])?"":$data['name'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where 1=1 ";
		if ($name!=""){
			$_sql .= " and p1.`name` like '%$name%'";
		}
		
		$sql = "select SELECT from {integral_convert} as p1 
			left join {integral} as p2 on p1.integral_id=p2.id 
			left join {user} as p3 on p3.user_id=p1.user_id  
			{$_sql}   ORDER LIMIT";
			
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = " where limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.name as goods_name,p3.username,p3.realname', 'order by p1.`id` desc', $_limit), $sql));
		}
		
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$sql = str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.name as goods_name,p3.username,p3.realname', 'order by p1.`id` desc', $_limit), $sql);
		$list = $mysql->db_fetch_arrays($sql);		
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
	 * 获取单条记录
	 * @param Array $data 
	 * @return Boolen
	 */
	public static function GetConvertOne ($data = array()) {
		global $mysql;
		$id = $data['id'];
		$sql = "select SELECT from {integral_convert} as p1 
			left join {integral} as p2 on p1.integral_id=p2.id  
			left join {user} as p3 on p3.user_id=p1.user_id  
			{$_sql}   ORDER ";
		return $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER'), array('p1.*,p2.name as goods_name,p3.username,p3.realname', 'order by p1.id desc'), $sql));
	}
	
	
	/**
	 * 修改
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function ActionConvert($data = array()){
		global $mysql;
		$id = $data['id'];
		
        if ($data['id'] == "" || $data['status'] == "") {
            return self::ERROR;
        }
		
		$sql = "select integral,user_id from {integral_convert} as p1 where id = '$id'";
		$result = $mysql->db_fetch_array($sql);
		$integral = $result['integral'];
		$user_id = $result['user_id'];
		
		//关闭此积分兑换
		if ($status==2){
			$sql = "update {user} set integral = integral + $integral where user_id = '$user_id'";
			$mysql->db_query($sql);
		}
		
		$_sql = "";
		$sql = "update `{integral_convert}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where id = '$id'";
		
        return $mysql->db_query($sql);
	}
	
	
	/**
	 * 修改
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function UpdateConvert($data = array()){
		global $mysql;
		$id = $data['id'];
        if ($data['id'] == "") {
            return self::ERROR;
        }
		
		$_sql = "";
		$sql = "update `{integral_convert}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
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
	public static function DeleteConvert($data = array()){
		global $mysql;
		$id = $data['id'];
		if (!is_array($id)){
			$id = array($id);
		}
		$sql = "delete from {integral_convert}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	
	
   
	/**
	 * 积分兑换
	 * @param $user_id 会员ID
	 * @param $goods_id 物品ID
	 * @param $number 兑换数量
	 * @return
	 *		Integral::NOT_ENOUGH_GOODS 物品不足
	 *		Integral::NOT_ALLOW_CITY 兑换城市限制
	 *		true 成功
	 */
	public static function AddConvert ($data = array()) {
		global $mysql;
		$user_id = $data['user_id'];
		$goods_id = $data['goods_id'];
		$number = $data['number'];
		
		# 获取会员信息
		$user = $mysql->db_fetch_array("select * from {user} where user_id={$user_id}");
		if (!$user) {
			return self::ERROR;
		}
		
		# 获取会员积分
		$integral_num = $user['integral'];//return $credit;
		
		# 获取物品信息
		$integral = $mysql->db_fetch_array("select * from {integral} where id=$goods_id");
		
		# 物品信息不存在
		if (!$integral) {
			return self::ERROR;
		}
		
		# 积分不够
		if ($integral_num < $integral['need'] * $number) {
			return self::NOT_ENOUGH_CREDIT;
		}
		
		# 数量不够
		if ($integral['ex_number'] + $number > $integral['number']) {
			return self::NOT_ENOUGH_GOODS;
		}
		
		# 状态不正确
		if ($integral['status'] !=1 ) {
			return self::ERROR;
		}
		
		/*
		# 城市不正确不正确
		if (
			($integral['city'] > 0 && $user['city'] != $integral['city']) ||
			($integral['province'] > 0 && $user['province'] != $integral['province'])
		) {
			return self::NOT_ALLOW_CITY;
		}
		*/
		
		$data = array(
			'user_id' => $user_id,//用户ID
			'integral_id' => $goods_id,//积分id
			'number' => $number,
			'need' => $integral['need'],
			'integral' => $integral['need']*$number,
			'remark' => $integral['need'],
		);
		$sql = "insert into `{integral_convert}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
		
		$result = $mysql->db_querys($sql);

		if ($result) {
			$ex_num = $integral['ex_number'] + $number ;//成功后的所剩余的数量
			$ex_integral = $user['integral'] -  $integral['need']* $number ;//成功后的所剩余的积分
			
			//更新已兑换的数量
			$sql = "update {integral} set ex_number='$ex_num' where id={$goods_id};";
			$mysql->db_query($sql);
			
			//更新用户的 积分数
			$sql = "update {user} set integral='$ex_integral' where user_id={$user_id};";
			$mysql->db_query($sql);
		}
		return$result;
	}
}
?>
