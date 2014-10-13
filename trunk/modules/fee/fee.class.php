<?php
/**
 * @author Tissot.Cai(Email:Tissot.Cai@gmail.com)
 * @version 1.0
 */

class feeClass {

	const ERROR = '操作有误，请不要乱操作';

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
		$keywords = urldecode(isset($data['keywords'])?$data['keywords']:"");
		if ((!empty($keywords) && $keywords=="request" ) || !empty($keywords)){
			if (isset($_REQUEST['keywords']) && $_REQUEST['keywords']!=""){
				$_sql .= " and p1.`name` like '%".urldecode($_REQUEST['keywords'])."%'";
			}
		} 
		
		if ($keywords==""  || $_REQUEST['keywords']==""){
			if (isset($data['site_id'])){
				$_sql .= " and (p1.site_id = '{$data['site_id']}' or p2.pid = {$data['site_id']} or p2.pid in (select site_id from {site} where pid={$data['site_id']})) ";
			}
		}
		
		if (isset($data['site_nid'])){
			$_sql .= " and p3.nid = '{$data['site_nid']}' ";
		}
		$keywords = urldecode(isset($data['keywords'])?$data['keywords']:"");
		if ((!empty($keywords) && $keywords=="request" ) || !empty($keywords)){
			if (isset($_REQUEST['keywords']) && $_REQUEST['keywords']!=""){
				$_sql .= " and p1.`name` like '%".urldecode($_REQUEST['keywords'])."%'";
			}
		} 
		$_select = 'p1.*,p2.name as city_name,p3.name as site_name,p3.nid as site_nid,p4.number,p4.company,p4.zhiwei,p4.work_time';
		$sql = "select SELECT from {fee} as p1 
				left join {area} as p2  on p1.city = p2.id
				left join {site} as p3  on p1.site_id = p3.site_id
				left join {jianzhi} as p4 on p1.gangwei=p4.number 
				$_sql ORDER LIMIT";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.`order` desc,p1.id desc', $_limit), $sql));
		}	
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.id desc', $limit), $sql));		
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
		$_sql = " where 1=1 ";
		
		$id = $data['id'];
		
		$click = isset($data['click'])?$data['click']:"";
		if ($click){
			$sql = "update  {fee} set hits=hits+1 where id=$id";
			$mysql->db_query($sql);
		}
		
		if (!empty($id)){
			$_sql .= " and p1.id=$id"; 
		}
		
		$sql = "select p1.*,p2.nid as site_nid,p2.name as site_name,p3.name as city_name from {fee} as p1 
				left join {site} as p2 on p1.site_id = p2.site_id
				left join {area} as p3  on p1.city = p3.id $_sql ";
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
       
		$sql = "insert into `{fee}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
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
		$sql = "update `{fee}` set ";
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
		$sql = "delete from {fee}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	
}
?>
