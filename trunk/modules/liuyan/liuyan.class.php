<?php

class liuyanClass {

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
		
		$_sql = "where 1=1 ";		 
		if (!empty($title)){
			$_sql .= " and p1.title like '%{$title}%'";
		}
		
		$sql = "select SELECT
					from {liuyan} as p1
					$_sql
				 ORDER LIMIT";
				 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('p1.*', 'order by p1.id desc', $limit), $sql));		
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
		$sql = "select * from {liuyan} where id=$id  ";

		return $mysql->db_fetch_array($sql);
	}
	
	/**
	 * 获取留言的设置信息
	 * @param Array $data 
	 * @return Boolen
	 */
	public static function GetSet() {
		global $mysql;
		$sql = "select * from `{liuyan_set}`  ";
		$result = $mysql->db_fetch_arrays($sql);
		$_result = "";
		if ($result!=false){
			foreach ($result as $key => $value){
				$_result[$value['nid']] = $value['value'];
			}
		}else{
			return false;
		}
		return $_result;
	}
	
	/**
	 * 留言设置
	 * @param Array $data 
	 * @return Boolen
	 */
	public static function ActionSet($data = array()) {
		global $mysql;
		foreach ($data as $key => $value){
			$sql = "update  `{liuyan_set}` set `value`='{$value}' where nid = '{$key}'";
			$mysql->db_query($sql);
		}
		
		return true;
	}
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Add($data = array()){
		global $mysql;
       
		$sql = "insert into `{liuyan}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
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
		$sql = "update `{liuyan}` set ";
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
		$sql = "delete from {liuyan}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	
	public static function GetTypeList($data = array()){	
		global $mysql;
		$sql = "select * from `{liuyan_set}` where nid = 'type' ";
		$result = $mysql->db_fetch_array($sql);
		return explode("|",$result['value']);
	}
}
?>
