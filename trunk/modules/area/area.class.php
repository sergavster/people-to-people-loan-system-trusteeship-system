<?
/******************************
 * $File: user.class.php
 * $Description: 数据库处理文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class areaClass{
	
	const ERROR = '操作有误，请不要乱操作';
	
	//是否安装
	function IsInstall(){
		global $mysql;
		$sql = "select 1 from {module} where code='area'";
		return $mysql->db_fetch_array($sql);
	}
	/**
	 * 获得列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		
		$pid = isset($data['pid'])?$data['pid']:"";
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_sql = " where 1=1 ";
		
		if (isset($data['name']) && $data['name']!=""){
			$_sql .= " and `name` like '%{$data['name']}%'";
		}
		if (isset($data['pid']) && $data['pid']!=""){
			$_sql .= " and `pid` = '{$data['pid']}'";
		}
	
		$sql = "select SELECT from {area} 
				{$_sql}   ORDER LIMIT";
				
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('*', ' order by `id` asc', $_limit), $sql));
		}
		
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' * ', 'order by `order` desc,`id` desc', $limit), $sql));		
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
	 * 查看用户
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetOne($data = array()){
		global $mysql;
		$id = $data['id'];
		if($id == "") return self::ERROR;
		$id = $data['id'];
		$sql = "select * from {area} where id=$id";
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
            return self::ERROR;
        }
		
		$_sql = "";
		$sql = "insert into `{area}` set ";
		foreach($data as $key => $value){
			$_sql[] = "`$key` = '$value'";
		}
        $mysql->db_query($sql.join(",",$_sql));
    	$id = $mysql->db_insert_id();
		return true;
	}
	
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Update($data = array()){
		global $mysql;
        if ($data['name'] == ""  || $data['id'] == "") {
            return self::ERROR;
        }
		$id = $data['id'];
		
		$sql = "update `{area}` set ";
		$_sql = "";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where id = '$id'";
        $mysql->db_query($sql);
		
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
		
		$sql = "delete from {area}  where id in (".join(",",$id).")";
		$mysql->db_query($sql);
		return true;
	}
	
	public static function UpdateArea($data = array()){
		global $mysql;
		if (isset($data['table'])){
			$table = "{".$data['table']."}";
		}else{
			return self::ERROR;
		}
		$sql = "select * from {area} ";
		$result = $mysql->db_fetch_arrays($sql);
		foreach ($result as $key => $value){
			$arealist[$value['id']]['pid'] = $value['pid'];
		}
		
		$sql = "select id,area from {$table} ";
		$result = $mysql->db_fetch_arrays($sql);
		if ($result!=false){
			foreach($result as $key => $value){
				$area_id = $value['area'];
				if ($area_id!=0){
					$city_id = $arealist[$area_id]['pid'];
					if ($city_id!=0){
						$province_id = $arealist[$city_id]['pid'];
						if ($province_id!=0){
							$province_pid = $arealist[$city_id]['pid'];
						}else{
							$province_pid = $city_id;
							$city_id = $area_id;
						}
					}else{
						$province_id = $area_id;
					}
					$_sql = "update {$table} set province='{$province_id}',city='{$city_id}' where id='{$value['id']}'";
					$mysql->db_query($_sql);
				}
				
			}
		}
		return true;
	}
}
?>