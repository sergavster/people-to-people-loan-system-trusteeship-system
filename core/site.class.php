<?php
/******************************
 * $File: site.class.php
 * $Description: 站点处理文件
 * $Author: ahui 
 * $Time:2010-08-02
 * $Update:None 
******************************/

class siteClass {
	const SITE_NAME_NO_EMPTY= '站点名称不能为空';
	const SITE_NAME_NO_REPEAT = '字段名已经存在';
	const SITE_NID_NO_EMPTY = '字段名不能为空';
	const SITE_NID_NO_REPEAT = '字段名已经存在';
	const SITE_ID_NO_EMPTY = '站点ID不能为空';
	
	/**
	 * 获取列表
	 *
	 * @return Array
	 */
	public static function GetList($data = array()){
		global $mysql,$_G;
		
		$sql = "select p1.*,p2.name as module_name from `{site}` as p1 left join `{module}` as p2 on p1.code = p2.code where p1.site_id!=''";
		
		if (isset($data['site_id']) && $data['site_id']!=""){
			$sql .= " and p1.`site_id` = '{$data['site_id']}'";
		}
		if (isset($data['code']) && $data['code']!=""){
			$sql .= " and p1.`code` = '{$data['code']}'";
		}
		
		$sql .= " order by p1.`order` desc,p1.site_id";
		$result = $mysql->db_fetch_arrays($sql);
		
		$_result = "";
		if (count($result)>0){
			$i=0;
			if (!isset($data['code']) || $data['code']==""){
				foreach($result as $key => $value){
					if ($value['pid']==0){
						$_result[$i] = $value;
						$_result[$i]['subnum'] = 0;
						$_result[$i]['aname'] = "<b>".$value['name']."</b>";
						if ($value['isurl']==1){
							$_result[$i]['siteurl'] = $value['url'];
						}else{
							if ($_G["system"]["con_rewrite"]==1){
							$_result[$i]['siteurl'] = "/{$value['nid']}/index.html"; 
							}else{
							$_result[$i]['siteurl'] = "?{$value['nid']}"; 
							}
						}
						$i++;
						foreach($result as $_key => $_value){
							if ($_value['pid']==$value['site_id']){
								$_result[$i] = $_value;
								$_result[$i-1]['subnum'] = 1;
								$_result[$i]['aname'] = "-".$_value['name'];
								if ($_value['isurl']==1){
									$_result[$i]['siteurl'] = $_value['url'];
								}else{
									$_result[$i]['siteurl'] = "/{$_value['nid']}/index.html"; 
								}
								$i++;
								foreach($result as $__key => $__value){
									if ($__value['pid']==$_value['site_id']){
										$_result[$i] = $__value;
										$_result[$i-1]['subnum'] = 1;
										$_result[$i]['aname'] = "--".$__value['name'];
										if ($__value['isurl']==1){
											$_result[$i]['siteurl'] = $__value['url'];
										}else{
											$_result[$i]['siteurl'] = "/{$__value['nid']}/index.html"; 
										}
										$i++;
									}
								}
							}
						}
					}
				}
			}else{
				foreach ($result as $key => $value){
					$_result[$key]['aname'] = $value['name'];
					$_result[$key]['site_id'] = $value['site_id'];
				}
			}
		}
		return $_result;
	}
	
	
	/**
	 * 获得单挑信息
	 *
	 * @param Array $data 
	 * @return Array
	 */
	public static function GetOne($data = array()){
		global $mysql;
		$site_id = $data['site_id'];
		if (empty($site_id)) return self::SITE_ID_NO_EMPTY;
		$sql = "select p1.*,p2.name as module_name,p3.name as pname from `{site}` as p1 
		left join `{module}` as p2 on p1.code = p2.code 
		left join `{site}` as p3 on p3.site_id = p1.pid 
		where p1.site_id = '$site_id' ";
		return $mysql->db_fetch_array($sql);
		
	}
	
	
	/**
	 * 获取菜单列表
	 *
	 * @param Array $code 
	 * @param Array $order 
	 * @return Integer
	 */
	public static function GetMenu($data = array()){
		global $mysql,$_G;
		
		if (isset($_G['site_list_pur'])){
			$result = $_G['site_list_pur'];
		}else{
			$sql = "select * from `{site}` order by `order` desc";
			$result = $mysql->db_fetch_arrays($sql);
		}
		
		$control_menu = array();
		if (is_array($result)){
			foreach ($result as $key => $value){
				if ($value['pid']==0){
					$control_menu[$key] = $value;
					foreach ($result as $_key => $_value){
						if ($_value['pid']==$value['site_id']){
							$control_menu[$key]['sub'][$_key] = $_value;
							foreach ($result as $__key => $__value){
								if ($__value['pid']==$_value['site_id']){
									$control_menu[$key]['sub'][$_key]['_sub'][$__key] = $__value;
								}
							}
						}
					}
				}
			}
		}
		return $control_menu;
	}
	
	
	/**
	 * 添加站点
	 *
	 * @param Array $data 
	 * @return Boolen
	 */
	public static function AddSite($data = array()){
		global $mysql;
		
		if ($data['name'] == ""){
			return self::SITE_NAME_NO_EMPTY;
		}else if (!isset($data['nid'])){
			return self::SITE_NID_NO_EMPTY;
		}
		$sql = "select * from `{site}` where `nid`='".$data['nid']."' ";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false) {
			return self::SITE_NAME_NO_REPEAT;
		} 
		
		//往模型表里插入一条数据
		$sql = "insert into `{site}` set `addtime`='".time()."',`addip`='".ip_address()."'";
		foreach ($data as $key => $value) {
			$sql .= ",`$key`='$value'";
		}
		return $mysql->db_query($sql);
	}
	
	public static function MoveSite($data = array()){
		global $mysql;
		$site_id = $data['site_id'];
		$pid = $data['pid'];
		$sql = "update {site} set pid=$pid where site_id=$site_id";
		return $mysql->db_query($sql);
	
	}
	/**
	 * 修改站点
	 *
	 * @param Array $data 
	 * @return Boolen
	 */
	public static function UpdateSite($data = array()){
		global $mysql;
		
		if ($data['name'] == ""){
			return self::SITE_NAME_NO_EMPTY;
		}else if (!isset($data['nid'])){
			return self::SITE_NID_NO_EMPTY;
		}
		$site_id = $data['site_id'];
		$sql = "select * from `{site}` where `nid`='".$data['nid']."' and site_id != '$site_id'  ";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false) {
			return self::SITE_NAME_NO_REPEAT;
		} 
		
		//往模型表里插入一条数据
		$_sql = "";
		$sql = "update `{site}` set ";
		foreach ($data as $key => $value) {
			$_sql[] = "`$key`='$value'";
		}
		$sql .= join(",",$_sql)." where site_id = '$site_id'";
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
		$site_id = $data['site_id'];
		if (!is_array($site_id)){
			$site_id = array($site_id);
		}
		$sql = "delete from {site}  where site_id in (".join(",",$site_id).")";
		return $mysql->db_query($sql);
	}
	
	function GetArticleSide($data = array()){
		global $mysql,$_G;
		$_sql = " where 1=1 ";
		
		if (isset($data['site_id'])){
			$_sql .= " and p1.site_id = {$data['site_id']} "; 
		}
		if (isset($data['table']) &&  $data['table']!=""){
			$data['code'] = $data['table'];
		}
		$sql = "select p1.*,p2.nid as site_nid,p2.name as site_name from {{$data['code']}} as p1 
				left join {site} as p2 on p1.site_id = p2.site_id
		 $_sql	 and p1.id < {$data['id']} order by p1.id desc ";
		 	//$sql = "select p1.* from {{$data['code']}} as p1 ";
		 $result = $mysql->db_fetch_array($sql);
		 
		 if ($result != false){
		 	if ($_G['system']["con_rewrite"]==true){
				if ($result['site_nid']==""){
					$_result['down'] = "<a href='a{$result['id']}.html'>{$result['name']}</a>";
				}else{
					$_result['down'] = "<a href='/{$result['site_nid']}/a{$result['id']}.html'>{$result['name']}</a>";
				}
			}else{
				$_result['down'] = "<a href='?{$result['site_nid']}/{$result['id']}'>{$result['name']}</a>";
			}
		 }else{
		 	$_result['down'] = "-";
		 }
		
		 $sql = "select p1.*,p2.nid as site_nid,p2.name as site_name from {{$data['code']}} as p1 
				left join {site} as p2 on p1.site_id = p2.site_id
		 $_sql	 and p1.id > {$data['id']} order by p1.id asc ";
		 $result = $mysql->db_fetch_array($sql);
		 if (is_array($result)){
		 	if ($_G['system']["con_rewrite"]==true){
				if ($result['site_nid']==""){
					$_result['up'] = "<a href='a{$result['id']}.html'>{$result['name']}</a>";
				}else{
					$_result['up'] = "<a href='/{$result['site_nid']}/a{$result['id']}.html'>{$result['name']}</a>";
				}
			}else{
				$_result['up'] = "<a href='?{$result['site_nid']}/{$result['id']}'>{$result['name']}</a>";
			}
		 }else{
		 	$_result['up'] = "-";
		 }
		 
		return $_result;
	}
	
	/**
	 * 修改站点模板
	 *
	 * @param Integer $site_id 站点ID
	 * @param Array $val 
	 * @return Integer
	 */
	function UpdateTpl($data = array()){
		global $mysql;
		if (!isset($data['type']) || $data['type']=="" || !isset($data['site_id']) || $data['site_id']==""){
			return "";
		}
		$type = $data["type"];
		$site_id = $data["site_id"];
		if ($type=="all"){
			$site_id = $site_id;
		}elseif ($type=="brother"){
			$sql = "select pid from `{site}` where site_id=$site_id";
			$result = $mysql->db_fetch_array($sql);
			$site_id = $result['pid'];
		}
		$sql = "update `{site}` set `index_tpl`='{$data['index_tpl']}',`list_tpl`='{$data['list_tpl']}',`content_tpl`='{$data['content_tpl']}'  where `pid` = $site_id";
			var_dump($sql);
		return $mysql->db_query($sql);
	}
}
?>
