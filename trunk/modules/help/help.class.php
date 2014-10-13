<?php

class helpClass {

	const ERROR = '操作有误';
	
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$name = isset($data['name'])?$data['name']:"";
		
		$_sql = "where 1=1 ";		 
		if (!empty($user_id)){
			$_sql .= " and p1.name like '%{$name}%'";
		}
		$sql = "select SELECT
					from {help} as p1
					left join {help_type} as p2 on p1.type_id=p2.type_id
					$_sql
				 ORDER LIMIT";
				 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('p1.*,p2.name as type_name', 'order by p1.id desc', $limit), $sql));		
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
	 * 获取培训列表
	 * @param $page 页码
	 * @param $where 附加条件
	 * @param $page_size 每页大小
	 */
	public static function GetOne ($data = array()) {
		global $mysql;
		$id = $data['id'];
		$sql = "select SELECT
					from {help} as p1 left join {help_type} as p2 on p1.type_id = p2.type_id
				    where p1.id=$id ORDER ";

		$result = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER'), array('p1.*,p2.name as type_name', 'order by p1.id desc'), $sql));
		return $result?$result:array();
	}
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Add($data = array()){
		global $mysql;
       
		$sql = "insert into `{help}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
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
		$sql = "update `{help}` set ";
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
		$sql = "delete from {help}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	
	
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetTypeList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		
		$name = isset($data['name'])?$data['name']:"";
		
		$_sql = "where 1=1 ";		 
		if (!empty($user_id)){
			$_sql .= " and p1.name like '%{$name}%'";
		}
		$sql = "select SELECT
					left join {help_type} as p1 
					$_sql
				 ORDER LIMIT";
				 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('p1.*', 'order by p1.type_id desc', $limit), $sql));		
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
	 * 获取培训列表
	 * @param $page 页码
	 * @param $where 附加条件
	 * @param $page_size 每页大小
	 */
	public static function GetTypeOne ($data = array()) {
		global $mysql;
		$id = $data['id'];
		$sql = "select SELECT
					from {help_type} as p1 
				    where p1.id=$id ORDER ";

		$result = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER'), array('p1.*', 'order by p1.type_id desc'), $sql));
		return $result?$result:array();
	}
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AddType($data = array()){
		global $mysql;
       
		$sql = "insert into `{help_type}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
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
	function UpdateType($data = array()){
		global $mysql;
		$type_id = $data['type_id'];
        if ($data['type_id'] == "") {
            return self::ERROR;
        }
		
		$_sql = "";
		$sql = "update `{help_type}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where type_id = '$type_id'";
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
		$type_id = $data['type_id'];
		if (!is_array($type_id)){
			$id = array($type_id);
		}
		$sql = "delete from {help_type}  where type_id in (".join(",",$type_id).")";
		return $mysql->db_query($sql);
	}
}
?>
