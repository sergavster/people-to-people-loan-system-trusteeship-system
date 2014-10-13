<?
/******************************
 * $File: friends.class.php
 * $Description: 好友处理文件
 * $Author: jackfeng 
 * $Time:2011-10-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class friendsClass{
	

	/**
     * 获得用户的好友信息
     * @param $param array('user_id' => '会员ID')，status(0=>申请中的好友，1表示正式好友，2，表示黑名单)
	 * @return bool true/false
     */
	public static function GetFriendsList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = " where 1=1 ";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .=" and p1.user_id = '{$data['user_id']}'";
		}
		if (isset($data['username']) && $data['username']!=""){
			$_sql .=" and p2.username like '%{$data['username']}%'";
		}
		if (isset($data['status']) && $data['status']!=""){
			$_sql .=" and p1.status = '{$data['status']}'";
		}
		$_order = "";
		$_select = " p1.*,p2.username as friend_username ";
		$sql = "select SELECT from `{friends}` as p1 left join `{user}` as p2 on  p1.friends_userid =p2.user_id {$_sql} LIMIT";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		}			 
				 
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
	
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));		
		$list = $list?$list:array();
		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		$result = $mysql -> db_fetch_arrays($sql);
		return $result;
	}
	
	function GetFriendsOne($data = array()){
		global $mysql;
		$_sql = " where user_id='{$data['user_id']}'";
		if (isset($data['friends_userid']) && $data['friends_userid']!=""){
			$_sql .= " and friends_userid='{$data['friends_userid']}'";
		}
		$sql = "select * from `{friends}` where{$_sql}";
		$result = $mysql->db_fetch_array($sql);
		return $result;
	}
	
	/**
	 * 获得用户的好友数
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function GetFriendsCount($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		if (isset($data['status'])){
			$_sql .= " and `status` = '{$data['status']}'";
		}
		if (isset($data['user_id'])){
			$_sql .= " and `user_id` = '{$data['user_id']}'";
		}
		$sql = "select count(1) as num from `{friends}` {$_sql}";
		return $mysql->db_fetch_array($sql);
	}
	
	/**
	 * 获得用户的好友数
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function GetFriendsRCount($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		if (isset($data['status'])){
			$_sql .= " and `status` = '{$data['status']}'";
		}
		if (isset($data['user_id'])){
			$_sql .= " and `user_id` = '{$data['user_id']}'";
		}
		$sql = "select count(1) as num from `{friends_request}` {$_sql}";
		return $mysql->db_fetch_array($sql);
	}
	
	/**
     * 获得用户的好友信息
     * @param $param array('user_id' => '会员ID')，status(0=>申请中的好友，1表示正式好友，2，表示黑名单)
	 * @return bool true/false
     */
	public static function GetFriendsInvite($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
                
                
		$_sql = " and 1=1 ";
		if (isset($data['user_id']) && $data['user_id']!="" && $data['user_id']!="-1"){
                    $_sql .=" and p1.invite_userid = '{$data['user_id']}'";
		}else if($data['user_id']=="-1"){
                    $_sql .=" and p1.invite_userid  != ''";
                }
		if (isset($data['status']) && $data['status']!=""){
			$_sql .=" and  p1.status = '{$data['status']}'";
		}
                if (isset($data['username']) && $data['username']!=""){
			$_sql .=" and  p1.invite_userid in(Select user_id from `{user}` where username='{$data['username']}') ";
		}
                
                if (isset($data['username2']) && $data['username2']!=""){
			$_sql .=" and  p1.username='{$data['username2']}' ";
		}
                
		$_order = "";
		$_select = " p1.username,p1.realname,p1.invite_money,p1.addtime,p1.invite_userid,p2.vip_status";
		$sql = "select SELECT from `{user}` p1  join `{user_cache}` p2 where p1.user_id=p2.user_id {$_sql} ORDER LIMIT";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
                 
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		}			 
               
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";

		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));		
		$list = $list?$list:array();
		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		$result = $mysql -> db_fetch_arrays($sql);
		return $result;
	}
	
	
	
	/**
	 * 添加好友
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	
	public static function AddFriends($data = array()){
		global $mysql;
		$user_id = isset($data['user_id'])?$data['user_id']:"";
		$type = isset($data['type'])?$data['type']:"";
		$content = isset($data['content'])?$data['content']:"";
		$friends_userid = isset($data['friends_userid'])?$data['friends_userid']:"";
		$sql = "select * from `{friends}` where user_id='{$user_id}' and friends_userid='{$friends_userid}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false){
			return "已经是你的好友，请不要重复添加";
		}
		
		$sql = "insert into `{friends}` set user_id='{$user_id}',friends_userid='{$friends_userid}',content='{$content}',type='{$type}',status=0,addtime='".time()."',addip='".ip_address()."'";
		$mysql ->db_query($sql);
		
		$sql = "insert into `{friends_request}` set user_id='{$friends_userid}',friends_userid='{$user_id}',content='{$content}',status=0,addtime='".time()."',addip='".ip_address()."'";
		return $mysql ->db_query($sql);
	}
	
	
	/**
	 * 添加请求的好友
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	
	public static function RAddFriends($data = array()){
		global $mysql;
		$user_id = isset($data['user_id'])?$data['user_id']:"";
		$type = isset($data['type'])?$data['type']:"";
		$content = isset($data['content'])?$data['content']:"";
		$friends_userid = isset($data['friends_userid'])?$data['friends_userid']:"";
		$sql = "select * from `{friends}` where user_id='{$user_id}' and friends_userid='{$friends_userid}' and status=1";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false){
			return "已经是你的好友，请不要重复添加";
		}
		
		$sql = "insert into `{friends}` set user_id='{$user_id}',friends_userid='{$friends_userid}',content='{$content}',type='{$type}',status=1,addtime='".time()."',addip='".ip_address()."'";
		$mysql ->db_query($sql);
		
		$sql = "update `{friends}` set status=1 where  user_id='{$friends_userid}' and friends_userid='{$user_id}'";
		$mysql ->db_query($sql);
		
		$sql = "delete  from `{friends_request}`  where user_id='{$user_id}' and friends_userid='{$friends_userid}' ";
		return $mysql ->db_query($sql);
	}
	
	function updateFriends($data = array()){
		global $mysql;
		$user_id = isset($data['user_id'])?$data['user_id']:"";
		$friends_userid = isset($data['friends_userid'])?$data['friends_userid']:"";
		
		$sql = "update `{friends_request}` set status=1 where user_id='{$friends_userid}' and friends_userid='{$user_id }'";
		$mysql ->db_query();
		$sql = "update `{friends}` set status=1 where user_id='{$friends_userid}' and friends_userid='{$user_id}'";
		$mysql ->db_query();
		$sql = "insert into `{friends}` set type='{$type}',user_id='{$user_id}',friends_userid='{$friends_userid}',status=0,addtime='".time()."',addip='".ip_address()."'";
		return $mysql ->db_query($sql);
	
	}
	
	
	/**
     * 获得用户的好友请求列表
     * @param $param array('user_id' => '会员ID')
	 * @return bool true/false
     */
	public static function GetFriendsRlist($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = " where 1=1 ";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .=" and p1.user_id = '{$data['user_id']}'";
		}
		if (isset($data['status']) && $data['status']!=""){
			$_sql .=" and p1.status = '{$data['status']}'";
		}
		$_order = "";
		$_select = " p2.user_id as user_id,p2.username as username,p1.addtime as addtime,p1.content as content";
		$sql = "select SELECT from `{friends_request}` as p1 left join `{user}` as p2 on  p1.friends_userid=p2.user_id {$_sql}";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		}			 
				 
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));		
		$list = $list?$list:array();
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		//$result = $mysql -> db_fetch_arrays($sql);
		//return $result;
	}
	
	/**
     * 删除好友
     * @param $param array('user_id' => '会员ID')
	 * @return bool true/false
     */
	public static function DeleteFriends($data = array()){
		global $mysql;
		$sql = "select user_id from `{user}` where username='{$data['friend_username']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false){
			$sql = "delete from `{friends}` where user_id='{$data['user_id']}' and friends_userid='{$result['user_id']}'";
			$mysql->db_query($sql);
			$sql = "delete from `{friends}` where friends_userid='{$data['user_id']}' and user_id='{$result['user_id']}'";
			$mysql->db_query($sql);
			$sql = "delete from `{friends_request}` where friends_userid='{$data['user_id']}' and user_id='{$result['user_id']}'";
			$mysql->db_query($sql);
			$sql = "delete from `{friends_request}` where user_id='{$data['user_id']}' and friends_userid='{$result['user_id']}'";
			$mysql->db_query($sql);
		}
		return true;
	}
	
	/**
     * 加入黑名单
     * @param $param array('user_id' => '会员ID')
	 * @return bool true/false
     */
	public static function BlackFriends($data = array()){
            
		global $mysql;

		$user_id = isset($data['user_id'])?$data['user_id']:"";

		$sql = "select user_id from `{user}` where username='{$data['friend_username']}'";
		$result = $mysql->db_fetch_array($sql);
		
		if ($result==false){
			$friend_username=urldecode($data['friend_username']);
			$sql = "select user_id from `{user}` where username='{friend_username}'";
			$result = $mysql->db_fetch_array($sql);
		}

		$friends_userid=$result['user_id'];
		
		$sql = "select * from `{friends}` where user_id='{$user_id}' and friends_userid='{$friends_userid}' ";
		$result2 = $mysql->db_fetch_array($sql);

		
		if ($result2!=false){
			$sql = "update `{friends}` set status=2 where user_id='{$data['user_id']}' and friends_userid='{$result['user_id']}'";
			
			$mysql->db_query($sql);
		}else{
                    $sql = "insert into `{friends}` set user_id='{$user_id}',friends_userid='{$result['user_id']}',status=2,addtime='".time()."',addip='".ip_address()."'";
                    $mysql ->db_query($sql);
                }
		return true;
	}
	 /**
	 * author : timest
     * 获得用户的好友提成
     * @param $param array('user_id' => '会员ID')，
	 * @return bool true/false
     */
	public static function GetTiChengList($data = array()){
		global $mysql;

		if (isset($data['user_id']) && $data['user_id']!=""){
            $_sql = "select  date_format(from_unixtime(addtime),'%Y-%m') as addtimes, sum(account) as money from view_tc where invite_userid = {$data['user_id']} group by addtimes order by addtimes desc" ;
            //$_sql = "select  date_format(from_unixtime(addtime),'%Y-%m') as addtimes, sum(money) as money from view_tc where invite_userid = 300 group by addtimes order by addtimes desc" ;
			$result = $mysql->db_fetch_arrays($_sql);
		}		
		$i = 0;
		foreach ( $result as $key=>$value){
			$dict[$value['addtimes']] = $dict[$value['addtimes']] ? $dict[$value['addtimes']] : 0;
			$dict[$value['addtimes']] += $value['money'];
			$result[$key]['money'] = number_format( $value['money'], 2, '.', ',');
		}
		$result = $result?$result:array();
		$result2 = array();
		if($dict){
			foreach ( $dict as $key=>$value){
				$result2[$i]['addtimes'] = $key;
				$result2[$i]['money'] = $value;
				$i++;
			}
		}//if($dict)
		return array(
            'list' => $result2
        );
	}
	/**
     * 重新加为好友
     * @param $param array('user_id' => '会员ID')
	 * @return bool true/false
     */
	public static function ReaddFriends($data = array()){
		global $mysql;
		$sql = "select user_id from `{user}` where username='{$data['friend_username']}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false){
			$sql = "select * from `{friends}` where  friends_userid='{$data['user_id']}' and user_id='{$result['user_id']}'";
			$result = $mysql ->db_fetch_array($sql);
			if ($result==false){
				$sql = "insert into `{friends_request}` set friends_userid='{$data['user_id']}',user_id='{$result['user_id']}',content='想跟你成为朋友'";
				$mysql->db_query($sql);
				$sql = "update `{friends}` set status=0 where user_id='{$data['user_id']}' and friends_userid='{$result['user_id']}'";
				$mysql->db_query($sql);
			}else{
				$sql = "update `{friends}` set status=1 where user_id='{$data['user_id']}' and friends_userid='{$result['user_id']}'";
				$mysql->db_query($sql);
			}
		}
		return true;
	}
	
	/**
     * 最近来访
     * @param $param array('user_id' => '会员ID')
	 * @return bool true/false
     */
	public static function AddVisit($data = array()){
		global $mysql;
		if (isset($data['visit_userid']) && $data['visit_userid']!="" && $data['user_id']!= $data['visit_userid']){	
			$time = time();
			$ip = ip_address();
			$sql = "select id from `{user_visit}` where user_id={$data['user_id']} and visit_userid = {$data['visit_userid']}";
			$result = $mysql->db_fetch_array($sql);
			//判断是否
			if ($result!=false){
				$sql = "update `{user_visit}` set addtime='{$time}',addip='{$ip}' where id='{$result['id']}'";
				$mysql->db_query($sql);
			}else{
				$sql = "insert into `{user_visit}` set user_id='{$data['user_id']}',visit_userid='{$data['visit_userid']}',addtime='{$time}',addip='{$ip}'";
				$mysql->db_query($sql);
			}
			//如果超过10条，则删除最早的一条
			$sql = "select count(1) as num from `{user_visit}` where user_id={$data['user_id']}";
			$result = $mysql->db_fetch_array($sql);
			if ($result['num']>10){
				$sql = "select id from `{user_visit}` where user_id={$data['user_id']} order by addtime asc";
				$result = $mysql->db_fetch_array($sql);
				$sql = "delete from `{user_visit}` where id='{$result['id']}'";
				$mysql->db_query($sql);
			}
		
		}
	}
	
	/**
     * 获得来访用户列表
     * @param $param array('user_id' => '会员ID')
	 * @return bool true/false
     */
	public static function GetVisitList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = " where 1=1 ";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .=" and p1.user_id = '{$data['user_id']}'";
		}
		$_order = " order by p1.id desc";
		$_select = " p1.*,p2.username as visit_username ";
		$sql = "select SELECT from `{user_visit}` as p1 left join `{user}` as p2 on  p1.visit_userid=p2.user_id {$_sql} ORDER LIMIT";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, $_order, $_limit), $sql));
		}			 
				 
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,$_order, $limit), $sql));		
		$list = $list?$list:array();
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		$result = $mysql -> db_fetch_arrays($sql);
		return $result;
	}
}
?>