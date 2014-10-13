<?
/******************************
 * $File: message.class.php
 * $Description: 数据库处理文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class messageClass{
	
	const ERROR = '操作有误，请不要乱操作';
	const SEND_NO_SELF = '不能给自己发送';
	const SEND_USERNAME_NO_EXISTS = '发送的用户不存在';
	const RECEIVE_USERNAME_NO_EXISTS = '接收的用户不存在';
	
	
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		$sent_user = !isset($data['sent_user'])?"":$data['sent_user'];
		$receive_user = !isset($data['receive_user'])?"":$data['receive_user'];
		$username = !isset($data['username'])?"":$data['username'];
		$sented = !isset($data['sented'])?"":$data['sented'];
		$status = !isset($data['status'])?"":$data['status'];
		$deltype = !isset($data['deltype'])?"":$data['deltype'];
		$page = !isset($data['page'])?1:$data['page'];
		$epage = !isset($data['epage'])?10:$data['epage'];
		
		$_sql = "where 1=1 ";		 
	 
		if (!empty($receive_user)){
			$_sql .= " and p1.receive_user = $receive_user";
		}		 
		if (!empty($sent_user)){
			$_sql .= " and p1.sent_user = $sent_user";
		}
		if (!empty($username)){
			$_sql .= " and p2.username = '$username'";
		}
		if (isset($data['sented'])){
			$_sql .= " and p1.sented = {$data['sented']}";
		}
		if (isset($data['status'])){
			$_sql .= " and p1.status = {$data['status']}";
		}
		if (isset($data['deltype'])){
			$_sql .= " and p1.deltype = {$data['deltype']}";
		}
		$sql = "select SELECT from {message} as p1 
				 left join {user} as p2 on p2.user_id=p1.sent_user
				 left join {user} as p3 on p3.user_id=p1.receive_user
				$_sql ORDER LIMIT";
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.username as sent_username,p3.username as receive_username ', 'order by p1.id desc', $limit), $sql));		
		$list = $list?$list:array();
		/**
			  * Author: Liu Yaoyao
			  * Time :  2012-04-21
			  * Fix xss bug in module of Message !
			  */
		/*
		foreach ($list as $key => $value){
		#$list[$key]['name'] = htmlspecialchars_decode($value["name"],ENT_QUOTES); 
		}*/
                
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
		$sent_user = isset($data['sent_user'])?$data['sent_user']:"";
		$id = intval($data['id']);
		if ($id == "") return self::ERROR;
		
		$_sql = "where p1.id = $id ";
		
		if (isset($data['deltype'])){
			$_sql .= " and p1.deltype = {$data['deltype']}";
		}
		if (isset($data['sent_user'])){
			$_sql .= " and p1.sent_user = {$data['sent_user']}";
			$user_id = $data['sent_user'];
		}
		if (isset($data['receive_user'])){
			$_sql .= " and p1.receive_user = {$data['receive_user']}";
			$user_id = $data['receive_user'];
		}
		$sql = "select p1.* ,p2.username as sent_username,p3.username as receive_username from {message} as p1 
				  left join {user} as p2 on p1.sent_user=p2.user_id 
				  left join {user} as p3 on p1.receive_user=p3.user_id 
				$_sql;
				";
		$result =  $mysql->db_fetch_array($sql);
		
		if ($result == false){
			return self::ERROR;
		}else{
			$sql = "update {message} set status=1 where id=$id";
			$mysql->db_query($sql);
			/**
			  * Author: Liu Yaoyao
			  * Time :  2012-04-21
			  * Fix xss bug in module of Message !
			  */
			 //$result["name"]=htmlspecialchars_decode($result["name"],ENT_QUOTES);  //No need to decode here!
             //$result["content"]=htmlspecialchars_decode($result["content"],ENT_QUOTES);
			
			return $result;
		}
	}
	
	/**
	 * 获得用户的短消息数
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function GetCount($data = array()){
		global $mysql;
		$_sql = " where 1=1 ";
		if (isset($data['status'])){
			$_sql .= " and status = '{$data['status']}'";
		}
		if (isset($data['deltype'])){
			$_sql .= " and deltype = '{$data['deltype']}'";
		}
		if (isset($data['user_id'])){
			$_sql .= " and receive_user = '{$data['user_id']}'";
		}
		$sql = "select count(1) as num from `{message}` {$_sql}";
		return $mysql->db_fetch_array($sql);
	}
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Add($data = array()){
		global $mysql;global $user;
       	$send_user = isset($data['sent_user'])?$data['sent_user']:"";
		$receive_user = isset($data['receive_user'])?$data['receive_user']:"";
		
		if ($send_user==$receive_user)  return self::SEND_NO_SELF;
		
		if(!is_numeric($send_user)){
			if ($send_user=="admin"){
				$data['sent_user'] = 0;
			}else{
				$suresult =  $user->GetOne(array("username"=>$sent_user));
				if  (!$suresult){
					return self::SEND_USERNAME_NO_EXISTS;
				}else{
					$data['sent_user'] = $suresult['user_id'];
				}
			}
		}
		
		if(!is_numeric($receive_user)){
			if ($receive_user=="admin"){
				$data['receive_user'] = 1;
			}else{
				$ruresult = $user->GetOne(array("username"=>$receive_user));
				if  (!$ruresult){
					return self::RECEIVE_USERNAME_NO_EXISTS;
				}else{
					$data['receive_user'] = $ruresult['user_id'];
				}
			}
		}
		
		$sql = "insert into `{message}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		
		foreach($data as $key => $value){
			//$sql .= ",`$key` = '".htmlspecialchars($value,ENT_QUOTES)."'";
			$sql .= ",`$key` = '".htmlgl($value,1)."'";
		}
		
		$result = $mysql->db_query($sql);
		if ($result==false){
			return self::ERROR;
		}else{
			return true;
		}
		
		
	}
	function htmlsafe($str)
{
$str = preg_replace( "@<script(.*?)</script>@is", "", $str ); 
$str = preg_replace( "@<iframe(.*?)</iframe>@is", "", $str ); 
$str = preg_replace( "@<style(.*?)</style>@is", "", $str ); 
$str = preg_replace( "@<(.*?)>@is", "", $str ); 
return $str;
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
		if (is_array($id)){
			$id = join(",",$id);
			unset($data['id']);
		}
		$_where = "";
		$sent_user = isset($data['sent_user'])?$data['sent_user']:"";
		$receive_user = isset($data['receive_user'])?$data['receive_user']:"";
		if ($sent_user != "") {
            $_where .= "and sent_user=$sent_user";
        }
		if ($receive_user != "") {
            $_where .= "and receive_user=$receive_user";
        }
		
		$sql = "update `{message}` set ";
                
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '".htmlspecialchars($value,ENT_QUOTES)."'";
		}
		$sql .= join(",",$_sql)." where id in ($id) $_where";
                /*
		foreach($data as $key => $value){
			$sql .= "$key` = '".htmlspecialchars($value,ENT_QUOTES)."'";
		}
                
		$sql .= join(",",$_sql)." where id in ($id) $_where";
                */
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
		
		$sent_user = isset($data['sent_user'])?$data['sent_user']:"";
		$receive_user = isset($data['receive_user'])?$data['receive_user']:"";
        if (count($id)<1) {
            return self::ERROR;
        }
		
		$_sql = "";
		if ($sent_user != "") {
            $_sql .= "and sent_user=$sent_user";
        }
		if ($receive_user != "") {
            $_sql .= "and receive_user=$receive_user";
        }
		$sql = "delete from {message}  where id in (".join(",",$id).") $_sql";
		return $mysql->db_query($sql);
	}
	
}
?>
