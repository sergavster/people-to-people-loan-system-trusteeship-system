<?
/******************************
 * $File: albums.class.php
 * $Description: 义工信息
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class albumsClass{
	
	const ERROR = '操作有误，请不要乱操作';

	function albumsClass(){
		global  $mysql,$_G;
		if (!isset($_G['linkage']['albums_type'])){
			$sql = "insert into `{linkage_type}` set `name`='相册类型',`nid`='albums_type',`order`=10,`addtime`='".time()."',addip='".ip_address()."'";
			$mysql->db_query($sql);
			$id = $mysql->db_insert_id();
			$sql = "insert into `{linkage}` set `name`='默认类型',`value`=0,`status`=0,`order`=10,`type_id`='{$id}',`addtime`='".time()."',addip='".ip_address()."'";
			$mysql->db_query($sql);
		}
	
	}
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
		if (isset($data['keywords'])){
			$_sql .= " and p1.`name` like '%{$data['keywords']}%'";
		}
		if (isset($data['site_id']) && $data['site_id']!=""){
			$_sql .= " and p1.`site_id` = '{$data['site_id']}'";
		}
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and p1.`user_id` = '{$data['user_id']}'";
		}
		
		$_select = 'p1.*,p2.name as site_name,p2.nid as site_nid';
		$sql = "select SELECT from `{albums}` as p1 
				left join `{site}` as p2  on p1.site_id = p2.site_id
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
		$id = $data['id'];
		if (empty($id)) return self::ERROR;
		$click = isset($data['click'])?$data['click']:"";
		if ($click){
			$sql = "update  {albums} set hits=hits+1 where id=$id";
			$mysql->db_query($sql);
		}
		
		$sql = "select p1.* from {albums} as p1 
				  where p1.id=$id
				";
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
       
		$sql = "insert into `{albums}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
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
		$_where = "where id = '$id' ";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_where .= " and user_id = '{$data['user_id']}'";
		}
        if ($data['id'] == "") return self::ERROR;
		
		$_sql = "";
		$sql = "update `{albums}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." {$_where}";
        return $mysql->db_query($sql);
	}
	
	/**
	 * 删除
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	public static function DeleteAlbums($data = array()){
		global $mysql,$upload;
		$_sql = "where p1.`code` = 'albums' ";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and  p1.user_id='{$data['user_id']}'";
		}
		if (isset($data['id']) && $data['id']!=""){
			$_sql .= " and  p1.aid='{$data['id']}'";
		}
		$sql = "select p1.id from `{upfiles}` as p1 {$_sql}";
		$result = $mysql ->db_fetch_arrays($sql);
		foreach ($result as $key => $value){
			$upload->Delete(array("user_id"=>$_G['user_id'],"id"=>$value['id']));
		}
		
		$_sql = "where `id` = '{$data['id']}' ";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and user_id='{$data['user_id']}'";
		}
		$sql = "delete from `{albums}`{$_sql}";
		return $mysql->db_query($sql);
	}
	
	/**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function AlbumsAdd($data = array()){
		global $mysql;
       
		$sql = "insert into `{albums}` set `addtime` = '".time()."', `updatetime` = '".time()."',`addip` = '".ip_address()."',`updateip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        $mysql->db_query($sql);
		return $mysql->db_insert_id();
	}
	
	function GetAlbumsList($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
		$page = !empty($data['page'])?$data['page']:"1";
		$epage = !empty($data['epage'])?$data['epage']:"10";
		if (isset($data['user_id'])){
			$_sql .= " and  p1.user_id='{$data['user_id']}' ";
		}
		if (isset($data['type']) && $data['type']=="index"){
			$_sql .= " and  p2.fileurl!='' ";
		}
		$_select = "p1.*,p2.fileurl";
		$sql = "select SELECT from `{albums}` as p1 left join `{upfiles}` as p2 on p1.id = p2.aid and p2.if_cover=1 and p2.code='albums'  {$_sql}  ORDER LIMIT";
		
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select,  'order by p1.id desc', $_limit), $sql));
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
	
	function GetAlbumsOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and  p1.user_id='{$data['user_id']}'";
		}
		if (isset($data['id']) && $data['id']!=""){
			$_sql .= " and  p1.id='{$data['id']}'";
		}
		$sql = "select p1.* from `{albums}` as p1  {$_sql}  ";
		return $mysql->db_fetch_array($sql);
	
	}
	
	
	function GetAlbumsPics($data = array()){
		global $mysql;
		$code = $data['code'];
		$aid = $data['aid'];
		$user_id = $data['user_id'];
		$sql = "select * from `{upfiles}` where code='{$code}'  and aid='{$aid}' and user_id='{$user_id}'";
		$result = $mysql->db_fetch_arrays($sql);
		return $result;
	
	}
	
	function UpdateCover($data = array()){
		global $mysql;
		if ($data['if_cover']==1){
			$sql = "select * from `{upfiles}` where id={$data['id']} and user_id='{$data['user_id']}'";
			$result =  $mysql->db_fetch_array($sql);
			if ($result!=false){
				$code = $result['code'];
				$aid = $result['aid'];
				$sql = "update `{upfiles}` set if_cover=0 where code='{$code}', aid='{$aid}'";
				$mysql->db_query($sql);
				$sql = "update `{upfiles}` set if_cover=1 where id='{{$data['id']}}'";
				$mysql->db_query($sql);
			}
		}
	}
}
?>