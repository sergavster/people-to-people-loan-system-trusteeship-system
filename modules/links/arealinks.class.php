<?php

class arealinksClass {

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
		
		$title = isset($data['title'])?$data['title']:"";
		$city_id = isset($data['city_id'])?$data['city_id']:"";
		
		$_sql = "where 1=1 ";	
		if (!empty($city_id)){
			$_sql .= " and p1.city=$city_id";
		}	
		if (isset($data['keywords']) && $data['keywords']!=""){
			$_sql .= " and p1.webname like '%{$data['keywords']}%'";
		}
		$sql = "select SELECT from {arealinks} as p1
					left join {area} as p2 on p1.city = p2.id
					$_sql
				 ORDER LIMIT";
				 
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.name as city_name', 'order by p1.`order` desc,p1.id desc', $_limit), $sql));
		}		 
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('p1.*,p2.name as city_name', 'order by p1.`order` desc,p1.id desc', $limit), $sql));		
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
	function GetLists($data = array()){
		global $mysql;
		
		$city_id = isset($data['city_id'])?$data['city_id']:"";
		
		$_sql = "where 1=1 ";	
		if (!empty($city_id)){
			$_sql .= " and p1.city=$city_id or p1.flag like '%q%'";
		}	
		$sql = "select SELECT from {arealinks} as p1
					left join {area} as p2 on p1.city = p2.id
					WHERE
				 ORDER LIMIT";
				 
		//是否显示全部的信息
		$_limit = "";
		if ($data['limit'] != "all"){
			$_limit = "  limit ".$data['limit'];
		}
		$result = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT', 'WHERE'), array(' p1.*,p2.name as city_name', 'order by p1.`order` desc,p1.id desc', $_limit, $_sql), $sql));
		
		return $result;
		
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
					from {arealinks} as p1 
				    where p1.id=$id ORDER ";

		$result = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER'), array('p1.*', 'order by p1.id desc'), $sql));
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
       
		$sql = "insert into `{arealinks}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
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
		$sql = "update `{arealinks}` set ";
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
		$sql = "delete from {arealinks}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	
	
}
?>
