<?
/******************************
 * $File: remind.class.php
 * $Description: 证书
 * $Author: ahui 
 * $Time:2010-08-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class remindClass{
	
	const ERROR = '操作有误，请不要乱操作';
	const REMIND_TYPE_NAME_NO_EMPTY = "联动类型名称必须填写";
	const REMIND_TYPE_NID_NO_EMPTY = "联动类型标识名必须填写";
	const REMIND_TYPE_NID_EXIST = '类型标示名已经存在';
	const REMIND_NAME_NO_EMPTY = '提醒名称不能为空';
	const REMIND_NID_NO_EMPTY = '提醒标示名不能为空';
	
	
	function IsInstall(){
		global $mysql;
		$sql = "select 1 from {module} where code='remind'";
		return $mysql->db_fetch_array($sql);
	}
	
	/**
	 * 获得列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		
		$name = $data['name'];
		$type_id = $data['type_id'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where 1=1 ";
		if ($name!=""){
			$_sql .= " and p1.`name` like '%$name%'";
		}
		if ($type_id!=""){
			$_sql .= " and p1.`type_id` = '$type_id'";
		}
		
		$sql = "select SELECT from {remind} as p1 
				left join {remind_type} as p2 on p1.type_id=p2.id
				{$_sql}   ORDER ";
		
	
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.name as type_name', 'order by p1.`order` desc', $limit), $sql));		
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
	 * 获得列表
	 *
	 * @return Array
	 */
	function GetLists($data = array()){
		global $mysql,$_G;
		if (isset($data['user_id'])){
			$user_id = $data['user_id'];
		}else{
			return self::ERROR;
		}
		$remind_user = unserialize ($_G['user_result']['remind']);
		$sql = "select id,name,nid from {remind_type} order by `order` desc";
		$type_list = $mysql->db_fetch_arrays($sql);
		
		$sql = "select SELECT from {remind} ORDER ";
		$remind_list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER'), array('*', ' order by `order` desc'), $sql));
		
		
		$sql = "select * from {module}  where code='phone'";
		$phone_module = $mysql->db_fetch_array($sql);
		
		
		
		$_result = "";
		foreach ($type_list as $key =>$value){
			$_result[$value['id']] = $value;
			foreach ($remind_list as $_key => $_value){
				if ($_value['type_id']==$value['id']){
					if (isset($remind_user[$_value['nid']]['message']) && $remind_user[$_value['nid']]['message']==1){
						if($_value['message'] == 1 || $_value['message'] == 2){
							if ($_remind_user[$_value['id']]['message']==1){
								$_value['message'] = 1;
							}else{
								$_value['message'] = 2;
							}
						}else{
							if ($_remind_user[$_value['id']]['message']==1){
								$_value['message'] = 3;
							}else{
								$_value['message'] = 4;
							}
						}
					}
					if (isset($remind_user[$_value['nid']]['email']) && $remind_user[$_value['nid']]['email']==1){
						if($_value['email'] == 1 || $_value['email'] == 2){
							if ($_remind_user[$_value['id']]['email']==1){
								$_value['email'] = 1;
							}else{
								$_value['email'] = 2;
							}
						}else{
							if ($_remind_user[$_value['id']]['email']==1){
								$_value['email'] = 3;
							}else{
								$_value['email'] = 4;
							}
						}
					}
					if (isset($remind_user[$_value['nid']]['phone']) && $remind_user[$_value['nid']]['phone']==1){
						if($_value['phone'] == 1 || $_value['phone'] == 2){
							if ($_remind_user[$_value['id']]['phone']==1){
								$_value['phone'] = 1;
							}else{
								$_value['phone'] = 2;
							}
						}else{
							if ($_remind_user[$_value['id']]['phone']==1){
								$_value['phone'] = 3;
							}else{
								$_value['phone'] = 4;
							}
						}
					}
					
					if ($remind_user!=false){
						if ($remind_user[$_value['nid']]['message']==1){
							if ($_value['message']==1 || $_value['message']==2){
								$_value['message'] = 1;
							}else{
								$_value['message'] = 3;
							}
						}else{
							if ($_value['message']==1 || $_value['message']==2){
								$_value['message'] = 2;
							}else{
								$_value['message'] = 4;
							}
						}
						if ($remind_user[$_value['nid']]['email']==1){
							if ($_value['email']==1 || $_value['email']==2){
								$_value['email'] = 1;
							}else{
								$_value['email'] = 3;
							}
						}else{
							if ($_value['email']==1 || $_value['email']==2){
								$_value['email'] = 2;
							}else{
								$_value['email'] = 4;
							}
						}
						if ($remind_user[$_value['nid']]['phone']==1){
							if ($_value['phone']==1 || $_value['phone']==2){
								$_value['phone'] = 1;
							}else{
								$_value['phone'] = 3;
							}
						}else{
							if ($_value['phone']==1 || $_value['phone']==2){
								$_value['phone'] = 2;
							}else{
								$_value['phone'] = 4;
							}
						}
					}
					$_result[$value['id']]['list'][$_value['id']] = $_value;
				}
			}
		}
		
		return $_result;
	}
	
	/**
	 * 查看
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetOne($data = array()){
		global $mysql;
		$id = $data['id'];
		if($id == "") return self::ERROR;
		$sql = "select * from {remind} where id=$id";
		return $mysql->db_fetch_array($sql);
	}
	
	/**
	 * 查看
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetNidOne($data = array()){
		global $mysql;
		$nid = $data['nid'];
		if($nid == "") return self::ERROR;
		$sql = "select * from {remind} where nid='$nid'";
		return $mysql->db_fetch_array($sql);
	}
	
	
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Add($data = array()){
		global $mysql;
        if (!isset($data['name']) || $data['name'] == "" ) {
            return self::REMIND_NAME_NO_EMPTY;
        }if (!isset($data['nid']) || $data['nid'] == "" ) {
            return self::REMIND_NID_NO_EMPTY;
        }
		$sql = "insert into `{remind}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		
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
        if ($data['name'] == ""  || $data['id'] == "") {
            return self::ERROR;
        }
		$sql = "update `{remind}` set ";
		$_sql = "";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where id = '$id'";
        $result = $mysql->db_query($sql);
		if ($result == false) return self::ERROR;
		return true;
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
		$sql = "delete from {remind}  where id in (".join(",",$id).")";
		$mysql->db_query($sql);
		return true;
	}
	
	
	/**
	 * 修改信息
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function Action($data = array()){
		global $mysql;
		$name = $data['name'];
		$nid = $data['nid'];
		$order = $data['order'];
		$message = $data['message'];
		$phone = $data['phone'];
		$email = $data['email'];
		$type = $data['type'];
		unset($data['type']);
		if ($type == "add"){
			$type_id = $data['type_id'];
			foreach ($name as $key => $val){
				if ($val!="" && $nid[$key]!=""){
					$sql = "insert into {remind} set `type_id`='".trim($type_id)."',`name`='".trim($name[$key])."',`nid`='".trim($nid[$key])."',`message`='".$message[$key]."',`email`='".$email[$key]."',`phone`='".$phone[$key]."',`order`='".trim($order[$key])."' ";			
					$mysql->db_query($sql);
				}
			}
		}else{
			$id = $data['id'];
			foreach ($id as $key => $val){
				if ($name[$key]!="" && $nid[$key]!=""){
					$sql = "update {remind} set `name`='".trim($name[$key])."',`nid`='".trim($nid[$key])."',`order`='".$order[$key]."',`message`='".$message[$key]."',`email`='".$email[$key]."',`phone`='".$phone[$key]."' where id=$val";			
					$mysql->db_query($sql);
				}
			}
		}
		
		return true;
	}
	
	
	
	/**
	 * 列表
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetTypeList($data = array()){
		global $mysql;
		
		$name = $data['name'];
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where 1=1 ";
		if ($name!=""){
			$_sql .= " and p1.`name` like '%$name%'";
		}
		
		$sql = "select SELECT from {remind_type} as p1 {$_sql}   ORDER LIMIT";
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' * ', 'order by p1.`order` desc,p1.`id` desc', $limit), $sql));		
		$list = $list?$list:array();
		
		
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = " where limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' * ', 'order by p1.`order` desc,p1.`id` desc', $_limit), $sql));
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
	public static function GetTypeOne($data = array()){
		global $mysql;
		$id = $data['id'];
		if($id == "") return self::ERROR;
		$sql = "select * from {remind_type} where id=$id";
		$result = $mysql->db_fetch_array($sql);
		if ($result == false) return self::ERROR;
		return $result;
	}
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AddType($data = array()){
		global $mysql;
        if ($data['name'] == ""  ) {
            return self::REMIND_TYPE_NAME_NO_EMPTY;
        }
		 if ($data['nid'] == ""  ) {
            return self::REMIND_TYPE_NID_NO_EMPTY;
        }
		$sql = "select * from {remind_type} where `nid` = '".$data['nid']."'";
		$result = $mysql->db_fetch_array($sql);
		if ($result !=false) return self::REMIND_TYPE_NID_EXIST;
		
		$_sql = "";
		$sql = "insert into `{remind_type}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." ";
        return $mysql->db_query($sql);
	}
	
	
	/**
	 * 修改
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function UpdateType($data = array()){
		global $mysql;
        if ($data['name'] == ""  || $data['id'] == "") {
            return self::ERROR;
        }
		$id = $data['id'];
		
		$sql = "update `{remind_type}` set ";
		$_sql = "";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where `id` = '$id'";
        $mysql->db_query($sql);
		return true;
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
		if  ($id == "") return self::ERROR;
		if (!is_array($id)){
			$id = array($id);
		}
		$sql = "delete from {`remind_type`}  where `id` in (".join(",",$id).")";
		$mysql->db_query($sql);
		$sql = "delete from {`remind`}  where `type_id` in (".join(",",$id).")";
		$mysql->db_query($sql);
		return true;
	}
	
	
	/**
	 * 修改信息
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function ActionType($data = array()){
		global $mysql;
		$nid = $data['nid'];
		$name = $data['name'];
		$order = $data['order'];
		$type = $data['type'];
		unset($data['type']);
		if ($type == "add"){
			foreach ($name as $key => $val){
				if ($val!="" && $nid[$key]!=""){
					$sql = "insert into {remind_type} set `name`='".$name[$key]."',`nid`='".$nid[$key]."',`order`='".$order[$key]."' ";			
					$mysql->db_query($sql);
				}
			}
		}else{
			$id = $data['id'];
			foreach ($id as $key => $val){
				$sql = "update {remind_type} set `name`='".$name[$key]."',`order`='".$order[$key]."' where id=$val";			
				$mysql->db_query($sql);
			}
		}
		return true;
	}
	
	
	function ActionRemindUser($data){
		global $mysql;
		if  (!isset($data['user_id'])) return self::ERROR;
		$user_id = $data['user_id'];
		$remind = $data['remind'];
		$sql = "update {user} set remind='{$remind}' where user_id=$user_id";
		return $mysql->db_query($sql);
	}
	
	
	//nid,所要操作的标识名
	//title 标题
	//content 内容
	//phone，手机号码
	//email，邮箱
	//sent_user,发送用户id
	//receive_user,接收用户id
	//type,类型
	function SendRemind($data){
		global $mysql,$user;
		if($data['receive_user']<=0){
			return false;
		}
		$sql = "select remind,email,phone from `{user}` where user_id={$data['receive_user']}";
		$result = $mysql->db_fetch_array($sql);
		$remind_user = unserialize ($result['remind']);
		$remind_result = self::GetNidOne(array("nid"=>$data['nid']));
		$message_status = isset($remind_user[$data['nid']]['message'])?$remind_user[$data['nid']]['message']:$remind_result['message'];	
		$email_status = isset($remind_user[$data['nid']]['email'])?$remind_user[$data['nid']]['email']:$remind_result['email'];
		$phone_status = isset($remind_user[$data['nid']]['phone'])?$remind_user[$data['nid']]['phone']:$remind_result['phone'];			
		$email = isset($data['email'])?$data['email']:$result['email'];
		$phone = isset($data['phone'])?$data['phone']:$result['phone'];
		if ($message_status==1 || $message_status==3){
		require_once ROOT_PATH.'modules/message/message.class.php';
		$message['sent_user'] = $data['sent_user'];
		$message['receive_user'] = $data['receive_user'];
		$message['name'] = $data['title'];
		$message['content'] = $data['content'];
		$message['type'] = $data['type'];
		$message['status'] = 0;
		messageClass::Add($message);//发送短消息
		}
		//$email_status = 0;//add by jackfeng 2012-8-3 暂时关闭
		if ($email_status==1 || $email_status==3){
			$remail['user_id'] = $data['receive_user'];
			$remail['email'] = $email;
			$remail['title'] = $data['title'];
			$remail['msg'] =  $data['content'];
			$remail['type'] =  $data['type'];
			$result = $user->SendEmail($remail);
		}
	}
}
?>