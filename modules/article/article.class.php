<?
/******************************
 * $File: user.class.php
 * $Description: 数据库处理文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
 * $UpdateDate:None 
******************************/
class articleClass{
	
	const ERROR = '操作有误，请不要乱操作';
	const MODULE_CODE_NO_EMPTY = '模型名称不能为空';
	const USERLOGIN_USERNAME_NO_EMPTY = '用户名不能为空';
	const USERLOGIN_PASSWORD_NO_EMPTY = '密码不能为空';
	const USERLOGIN_USERNAME_PASSWORD_NO_RIGHT = '用户名密码错误';
	const USER_ADD_LONG_USERNAME = '用户名长度不能超过15个字符';
	

	/**
	 * 获得列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		$code = isset($data['code'])?$data['code']:"";
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		if($code == "") $code = "article";;
		$_sql = "where 1=1 ";
		if (isset($data['name']) && $data['name']!=""){
			$_sql .= " and p1.name like '%{$data['name']}%'";
		}
		if (isset($data['site_id']) && $data['site_id']!=""){
			$_sql .= " and p1.site_id in ({$data['site_id']})";
		}
		if (isset($data['status']) && $data['status']!=""){
			$_sql .= " and p1.status = {$data['status']}";
		}
		if (isset($data['lgnore'])){
			$_sql .= " and p1.site_id != {$data['lgnore']}";
		}
		//建立相应的字段表
		$fields_table = $code."_fields";
		$sql = "CREATE TABLE IF NOT EXISTS  `{".$fields_table."}` (
		  `aid` int(11) unsigned NOT NULL ,
		  PRIMARY KEY (`aid`)
		) ENGINE=MyISAM  ;";
		$result = $mysql->db_query($sql);
		
		$_order = "order by p1.order desc,p1.id desc";		
		if (isset($data['order'])){
			if ($data['order']=="hits")
			$_order = " order by p1.hits desc";
		}
		
		$sql = "select SELECT from {".$code."} as p1 
				left join {".$fields_table."} as p2 on p1.id=p2.aid 
				left join {site} as p3 on p1.site_id=p3.site_id 
				left join {area} as p4 on p1.city=p4.id 
				{$_sql}   ORDER LIMIT";
		//是否显示全部的信息
		if (isset($data['limit']) ){
			$_limit = "";
			if ($data['limit'] != "all"){
				$_limit = "  limit ".$data['limit'];
			}
			return $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.*,p3.name as site_name,p3.nid as site_nid,p4.name as city_name', $_order, $_limit), $sql));
		}			
				
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array(' p1.*,p2.*,p3.name as site_name,p4.name as city_name', $_order, $limit), $sql));
		$list = $list?$list:array();
		$flag_list = isset($data['flag_list'])?$data['flag_list']:"";
		if (count($list)>0){
			$data_flag['result'] = $flag_list;
			foreach ($list as $key => $value){
				$data_flag['flag'] = $value['flag'];
				$list[$key]['flagname'] = getFlagName($data_flag);
			}
		}
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
		
	}
	/*
	*首页文章标题显示
	*/
	function IndexGetArticleList($data = array()){
		global $mysql;
		$_sql = ' where p1.status=1 ';
		$limit = '';
		if ($data['site_id']>0){
			$_sql .= ' and p1.site_id = '.intval($data['site_id']);
		}
		if ($data['limit'] >0){
			$limit = ' limit '.intval($data['limit']);
		}
		$order = ' order by p1.order desc,p1.id desc ';
		if (isset($data['order']) && $data['order']=="order"){
			//$order = ' order by p1.order desc ';
		}
		//$sql = 'select p1.id,p1.name,p2.nid from {article} p1 left join {site} p2 on p1.site_id=p2.site_id '.$_sql.$order.$limit;
		$sql = 'select p1.*,p2.nid from {article} p1 left join {site} p2 on p1.site_id=p2.site_id '.$_sql.$order.$limit;
		return $mysql->db_fetch_arrays($sql);
	}
	/**
	 * 查看用户
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetOne($data = array()){
		global $mysql;
		$code = empty($data['code'])?"article":$data['code'];
		$id = $data['id'];
		if($code == "" || $id == "") return self::ERROR;
		$click = isset($data['click'])?$data['click']:"";
		if ($click){
			$sql = "update  {".$code."} set hits=hits+1 where id=$id";
			$mysql->db_query($sql);
		}
		$fields_table = $code."_fields";
		$id = $data['id'];
		$sql = "select p1.*,p2.*,p3.name as site_name,p4.username from {".$code."} as p1 
				left join {".$fields_table."} as p2 on p1.id=p2.aid 
				left join {site} as p3 on p1.site_id=p3.site_id 
				left join {user} as p4 on p4.user_id=p1.user_id 
				where p1.id=$id
				";
		return $mysql->db_fetch_array($sql);
	}
	
	/**
	 * 添加
	 *
	 * @param Array $result
	 * @return Boolen
	 */
	function Add($result = array()){
		global $mysql;
		$data = $result['data'];
		$fields = $result['fields'];
		$code = $data['code'];
        if ($data['name'] == "" || $data['code'] == "") {
            return self::ERROR;
        }
		
		unset($data['code']);
		$sql = "insert into `{".$code."}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        $mysql->db_query($sql);
    	$id = $mysql->db_insert_id();
		
		$_sql = array();
		if (count($fields)>0){
			$sql = "insert into `{".$code."_fields}` set ";
			if (is_array($fields)){
				foreach ($fields as $key =>$value){
					if ($key!=""){
						$sql .= "`$key`='$value',";
					}
				}
			}
			$sql .= "aid=$id";
			$mysql->db_query($sql);
		}
		return true;
	}
	
	
	/**
	 * 修改
	 *
	 * @param Array $result
	 * @return Boolen
	 */
	function Update($result = array()){
		global $mysql;
		$data = $result['data'];
		$fields = $result['fields'];
		$code = $data['code'];
		$id = $data['id'];
        if ($data['name'] == "" || $data['code'] == "" || $data['id'] == "") {
            return self::ERROR;
        }
		
		unset($data['code']);
		$sql = "update `{".$code."}` set ";
		$_sql = "";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where id = '$id'";
        $mysql->db_query($sql);
		
		$_sql = array();
		if (count($fields)>0){
			$sql = "select aid from `{".$code."_fields}` where aid = '$id'";
			$result = $mysql->db_fetch_array($sql);
			if ($result['aid'] ==""){
				$sql = "insert into `{".$code."_fields}`(aid)values({$id});";
				$mysql->db_query($sql);
			}
			$sql = "update `{".$code."_fields}` set ";
			if (is_array($fields)){
				foreach ($fields as $key =>$value){
					if ($key!=""){
						$sql .= "`$key`='$value',";
					}
				}
			}
			$sql .= "aid=$id where aid = '$id'";
			$mysql->db_query($sql);
		}
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
		$code = $data['code'];//用户的类型，是管理员还是普通用户
		if ($code == "") return self::ERROR;
		$sql = 'select litpic from {'.$code.'} where id in ('.join(",",$id).')';
		$litpic = $mysql->db_fetch_arrays($sql);
		$sql = "delete from {".$code."}  where id in (".join(",",$id).")";
		$mysql->db_query($sql);
		$sql = "delete from {".$code."_fields}  where aid in (".join(",",$id).")";
		$mysql->db_query($sql);
		foreach ($litpic as $v){//删除图片
			if ($v['litpic']!='' && file_exists($v['litpic'])){
				@unlink($v['litpic']);
			}
		}
		return true;
	}
	
	/**
	 * 获取菜单列表
	 *
	 * @param Array $code
	 * @param Array $order
	 * @return Integer
	 */
	public static function GetSiteList($data = array()){
		global $mysql,$_G;
	
		$_sql = " where status=1 ";
		//根据上级栏目的ID取出全部子栏目
		if (isset($data['pid']) && $data['pid']!="0"){
			$_sql .= " and pid = {$data['pid']}";
		}
		if (isset($data['pnid']) && $data['pnid']!=""){
			$_sql .= " and pid = (SELECT site_id FROM `{site}` WHERE nid = '{$data['pnid']}' )";
		}
		
		$_limit = "";
		if(isset($data['limit'])){
			$_limit = " limit {$data['limit']}";
		}
		$sql = "select * from `{site}` {$_sql} order by `order` desc {$_limit}";
		$result = $mysql->db_fetch_arrays($sql);
		return $result;
	}
	/*
	 * 首页显示推荐的文章图片
	 */
	public static function GetRecommendList($data = array()){
		global $mysql,$_G;
		$limit = ' limit 5';
		if(isset($data['limit'])) $limit = ' limit '.intval($data['limit']);
		$sql = "select a.*,s.nid from `{article}` a left join `{site}` s on a.site_id=s.site_id where a.status=1 and a.litpic!='' and a.flag like 't%' order by a.id desc ".$limit;
		return  $mysql->db_fetch_arrays($sql);
	}
}
?>