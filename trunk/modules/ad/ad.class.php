<?
/******************************
 * $File: ad.class.php
 * $Description: 证书
 * $Author: ahui 
 * $Time:2010-08-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class adClass{
	
	const ERROR = '操作有误，请不要乱操作';
	const AD_NAME_NO_EMPTY = '认证名称不能为空';

	/**
	 * 获得列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		
		$name = isset($data['name'])?$data['name']:"";
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where 1=1 ";
		if ($name!=""){
			$_sql .= " and p1.`name` like '%$name%'";
		}
		
		
		$sql = "select SELECT from {ad} as p1 {$_sql}   ORDER LIMIT";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = " where limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' * ', 'order by p1.`order` desc,p1.`id` desc', $_limit), $sql));
		}
		
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*', 'order by p1.`order` desc,p1.`id` desc', $limit), $sql));		
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
	public static function GetOne($data = array()){
		global $mysql;
		$id = $data['id'];
		if($id == "") return self::ERROR;
		$sql = "select * from {ad} where id=$id";
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
        if ($data['name'] == "" ) {
            return self::AD_NAME_NO_EMPTY;
        }
		$sql = "insert into `{ad}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        $mysql->db_query($sql);
    	return $mysql->db_insert_id();
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
		$sql = "update `{ad}` set ";
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
		$sql = "delete from {ad}  where id in (".join(",",$id).")";
		$mysql->db_query($sql);
		return true;
	}
	
	
}
?>