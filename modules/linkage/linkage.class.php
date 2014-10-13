<?
/******************************
 * $File: linkage.class.php
 * $Description: 证书
 * $Author: ahui 
 * $Time:2010-08-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class linkageClass{
	
	const ERROR = '操作有误，请不要乱操作';
	const LINKAGE_TYPE_NAME_NO_EMPTY = "联动类型名称必须填写";
	const LINKAGE_TYPE_NID_NO_EMPTY = "联动类型标识名必须填写";
	const LINKAGE_TYPE_NID_EXIST = '类型标示名已经存在';
	
	
	function IsInstall(){
		global $mysql;
		$sql = "select 1 from {module} where code='linkage'";
		return $mysql->db_fetch_array($sql);
	}
	/**
	 * 获得列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where 1=1 ";
		if (isset($data['name']) && $data['name']!=""){
			$_sql .= " and p1.`name` like '%{$data['name']}%'";
		}
		if (isset($data['type_id']) && $data['type_id']!=""){
			$_sql .= " and p1.`type_id` = '{$data['type_id']}'";
		}
		
		
		$sql = "select SELECT from {linkage} as p1 
				left join {linkage_type} as p2 on p1.type_id=p2.id
				{$_sql}   ORDER ";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = " where limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.nid as type_nid ', ' order by p1.`order` desc,p1.`id` ', $_limit), $sql));
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
		$sql = "select * from {linkage} where id=$id";
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
            return self::linkage_NAME_NO_EMPTY;
        }
		$sql = "insert into `{linkage}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		
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
		$sql = "update `{linkage}` set ";
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
		$sql = "delete from {linkage}  where id in (".join(",",$id).")";
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
		$value = $data['value'];
		$order = $data['order'];
		$type = isset($data['type'])?$data['type']:"";
		unset($data['type']);
		if ($type == "add"){
			$type_id = $data['type_id'];
			
			foreach ($name as $key => $val){
				if ($value[$key]==""){
					$value[$key] = $val;
				}
				if ($val!=""){
					$sql = "insert into {linkage} set `type_id`='".$type_id."',`name`='".$name[$key]."',`value`='".$value[$key]."',`pid`=0,`order`='".$order[$key]."' ";			
					$mysql->db_query($sql);
				}
			}
		}else{
			$id = $data['id'];
			foreach ($id as $key => $val){
				if ($name[$key]!=""){
					$sql = "update {linkage} set `name`='".$name[$key]."',`value`='".$value[$key]."',`order`='".$order[$key]."' where id=$val";			
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
		
		$name = isset($data['name'])?$data['name']:"";
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where 1=1 ";
		if ($name!=""){
			$_sql .= " and p1.`name` like '%$name%'";
		}
		
		$sql = "select SELECT from {linkage_type} as p1 {$_sql}   ORDER LIMIT";
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
		$sql = "select * from {linkage_type} where id=$id";
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
            return self::LINKAGE_TYPE_NAME_NO_EMPTY;
        }
		 if ($data['nid'] == ""  ) {
            return self::LINKAGE_TYPE_NID_NO_EMPTY;
        }
		$sql = "select * from {linkage_type} where `nid` = '".$data['nid']."'";
		$result = $mysql->db_fetch_array($sql);
		if ($result !=false) return self::LINKAGE_TYPE_NID_EXIST;
		
		$_sql = "";
		$sql = "insert into `{linkage_type}` set ";
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
		
		$sql = "update `{linkage_type}` set ";
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
		$sql = "delete from {`linkage_type`}  where `id` in (".join(",",$id).")";
		$mysql->db_query($sql);
		$sql = "delete from {`linkage`}  where `type_id` in (".join(",",$id).")";
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
		$type = isset($data['type'])?$data['type']:"";
		unset($data['type']);
		if ($type == "add"){
			foreach ($name as $key => $val){
				if ($val!="" && $nid[$key]!=""){
					$sql = "insert into {linkage_type} set `name`='".$name[$key]."',`nid`='".$nid[$key]."',`order`='".$order[$key]."' ";			
					$mysql->db_query($sql);
				}
			}
		}else{
			$id = $data['id'];
			foreach ($id as $key => $val){
				$sql = "update {linkage_type} set `name`='".$name[$key]."',`order`='".$order[$key]."' where id=$val";			
				$mysql->db_query($sql);
			}
		}
		return true;
	}
	
	
}
?>