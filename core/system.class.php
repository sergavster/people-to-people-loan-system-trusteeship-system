<?php
/******************************
 * $File: system.class.php
 * $Description: 模块类处理文件
 * $Author: ahui 
 * $Time:2010-03-09
 * $Update:None 
******************************/

class systemClass {
	const ERROR = '操作有误，请跟管理员联系';
	const SYSTEM_ADD_NO_CON = '变量名请已 con_ 开头！';
	const SYSTEM_NID_IS_EXIST = '标识ID已经存在';
	
	/**
	 * 获得数据表
	 * 
	 * @return Array
	 */
	function GetSystemTables($data = array()){
		global $mysql;
		$_result = "";
		$sql = "show tables";
		$result = $mysql->db_fetch_arrays($sql);
		foreach ($result as $key => $value){
			foreach($value as $val){
				$_val = explode("_",$val);
				if($mysql->db_prefix!="" && $_val[0]."_"==$mysql->db_prefix){
					$num = $mysql->db_count(str_replace($mysql->db_prefix,"",$val));
					$_result[$key]['name'] = $val;
					$_result[$key]['num'] = $num;
				}else{
					$num = $mysql->db_count($val);
					$_result[$key]['name'] = $val;
					$_result[$key]['num'] = $num;
				}
			}
		}
		return  $_result;
	
	}
	
	
	/**
	 * 备份数据表
	 * 
	 * @return Array
	 */
	public static  function BackupTables($data = array() ){
		global $mysql;
		$filedir = $data['filedir'];
		$tables = $data['table'];
		$size = $data['size'];
		$tid = $data['tid'];//读取哪个表
		$limit = $data['limit'];//表读取到那几行
		$table_page = $data['table_page'];//文件的分页
		$table = $tables[$tid];
		if ($tables == "") return self::ERROR;
		
		/*
		 *备份表结构
		*/
		if ($tid==0){
			$sql = "";
			$filename = $filedir."/show_table.sql";
			foreach ($tables as $key => $tbl){
				//$sql .="# 数据表　".$tbl."　的结构;\r\n";	
				$sql .="DROP TABLE IF EXISTS `$tbl`;\r\n";//如果表存在就删除存在的表
				$_sql = "show create table $tbl";
				$result = $mysql->db_fetch_array($_sql);
				$sql .= $result['Create Table'].";\r\n\r\n";
				mk_file($filename,$sql);
			}
		}
		
		if ($table != ""){
			$file = $filedir."/".$table."_".$table_page.".sql";
			$text = read_file($file);
			if (strlen($text) > $size * 1024) {
				 $file = $filedir."/".$table."_".($table_page+1).".sql";
				 $text = read_file($file);
			}
			/*
			 *获取表的所有字段
			*/
			$fields = $mysql->db_show_fields(str_replace($mysql->db_prefix,"",$table));
			$_fields = join(",",$fields);
			
			$sql = "select *  from `$table` limit $limit,100";
			
			$result= $mysql->db_fetch_arrays($sql)  ; 
			if (count($result)>0){
				foreach ($result as $key => $value){
					$text .= "insert into `$table` ( ";
					foreach ($fields as $fkey => $fvalue){
						$_value[$fkey] ="\"".mysql_escape_string($value[$fvalue])."\"";
						$_fie[$fkey] ="`$fvalue`";
					}
					$text .= join(",",$_fie).") values (".join(",",$_value).");\r\n\r\n";
					$limit++;
				}
				mk_file($file,$text);
				$data['limit'] = $limit;
				$data['table_page'] = $table_page;
				$data['tid'] = $tid;
			}else{
				$data['limit'] = 0;
				$data['table_page'] = 0;
				$data['tid'] = $tid+1;
			}
			return $data;
		}
		return "";
	}
	
	/**
	 * 备份数据表
	 * 
	 * @return Array
	 */
	public static function RevertTables($data = array() ){
		global $mysql;
		
		$tables = $data['table'];
		$nameid = $data['nameid'];
		if (isset($tables[$nameid]) && $tables[$nameid]!=""){
			$value = $tables[$nameid];
			if ($value!="show_table.sql"){
				$sql = file_get_contents($data['filedir']."/".$value);
				$_sql = explode("\r\n",$sql);
				foreach ($_sql as $val){
					if ($val!=""){
						$mysql->db_query($val,"true");
					}
				}
			}
			return $value;
		}else{
			return "";
		}
	}
	
	
	/**
	 * 修改系统信息
	 * 
	 * @return Array
	 */
	function  ActionSystem($data = array()){
		global $mysql;
		$class = $data["class"];
		$style = $data["style"];
		if ($class == "list"){
			$sql = "select * from {system} where `style` = '$style' order by id ";
			return $mysql->db_fetch_arrays($sql);
		}
		
		elseif ($class == "view"){
			$id = $data["id"];
			$sql = "select * from {system} where `style` = '$style' and `id` = '$id'";
			return $mysql->db_fetch_array($sql);
		}
		
		elseif ($class == "add"){
			unset($data['class']);
			if (!ereg ("^con_", $data['nid'])){
				return self::SYSTEM_ADD_NO_CON;
			}
			$_sql = "";
			$sql = "select * from {system} where nid = '".$data['nid']."'";
			$result = $mysql -> db_fetch_array($sql);
			if ($result!=false) return  self::SYSTEM_NID_IS_EXIST;
			$sql = "insert into `{system}` set ";
			foreach($data as $key => $value){
				$_sql[] = "`$key` = '$value'";
			}
			$result =  $mysql->db_query($sql.join(",",$_sql));
			if ($result == false) return self::ERROR;
			return true;
		}
		
		elseif ($class == "update"){
			unset($data['class']);
			if (!ereg ("^con_", $data['nid'])){
				return self::SYSTEM_ADD_NO_CON;
			}
			
			$sql = "select * from {system} where nid = '".$data['nid']."' and id !=".$data['id'];
			$result = $mysql -> db_fetch_array($sql);
			if ($result!=false) return  self::SYSTEM_NID_IS_EXIST;
			
			$_sql = "";
			$sql = "update `{system}` set ";
			foreach($data as $key => $value){
				$_sql[] = "`$key` = '$value'";
			}
			$result =  $mysql->db_query($sql.join(",",$_sql)." where id = '".$data['id']."'");
			if ($result == false) return self::ERROR;else return true;
			
		}
		
		elseif ($class == "action"){
			foreach ($data['value'] as $key =>$val){
				$val = nl2br($val);
				$sql  = "update {system} set `value` = '{$val}' where `nid` = '$key'";
				$mysql->db_query($sql);
			}
			return self::ERROR;
		}
	}
	
	/**
	 * 备份数据表
	 * 
	 * @return Array
	 */
	function  GetRemindList($data = array()){
		$id = $data["id"];
			$sql = "select * from `{remind}` where `style` = '$style' and `id` = '$id'";
			return $mysql->db_fetch_array($sql);
	
	}
	
	//网站的缓存信息
	public static function GetCacheOne($data=array()){
		global $mysql;
		if (isset($data['date'])){
			$date = $data['date'];
		}else{
			$date = date("Y-m-d",time());
		}
		$sql = "select * from `{cache}` where date ='{$date}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false){
			//获取用户总数
			$sql = "select count(*) as num from `{user}` ";
			$result = $mysql->db_fetch_array($sql);
			$user_num =  ($result!=false)?$result['num']:0;
			
			//最后注册的会员
			$sql = "select username from `{user}` order by user_id desc";
			$result = $mysql->db_fetch_array($sql);
			$last_user =  ($result!=false)?$result['username']:0;
			
			//插入缓存
			$sql = "insert into `{cache}` set `last_user` = '{$last_user}',`user_num`='$user_num',`date` = '{$date}' ";
			$mysql->db_query($sql);
			
			//重新获取
			$sql = "select * from `{cache}` where date ='{$date}'";
			$result = $mysql->db_fetch_array($sql);
		}
		return $result;
		
	}
	
		//网站的缓存信息
	public static function Online($data=array()){
		global $mysql,$_G;
		
		//先删除5分钟内的在线纪录
		$time = time() - 60*5;
		$sql = "delete from `{online}` where activetime<$time";
		$mysql->db_query($sql);
		
		//判断用户id不为空
		if (isset($data['user_id']) && $data['user_id']!=""){
			//插入用户在线表var_dump($data);
			$sql = "select 1 from `{online}` where  user_id = '{$data['user_id']}'";
			$result = $mysql -> db_fetch_array($sql);
			if($result==false){
				$sql = " select * from  {user} where user_id = '{$data['user_id']}'";
				$result = $mysql -> db_fetch_array($sql);
				if ($result!=false){
				$sql = " insert into `{online}` set user_id='{$result['user_id']}',type_id='{$result['type_id']}',username='{$result['username']}',ip='".ip_address()."',activetime='".time()."'";
				$result = $mysql->db_query($sql);
				}
				
			}
		}else{
			//如果是游客则读取ip判断
			$sql = "select 1 from `{online}` where  ip = '".ip_address()."'";
			$result = $mysql -> db_fetch_array($sql);
			if($result==false){
				$sql = " insert into `{online}` set user_id=0,type_id='',username='',activetime='".time()."',ip='".ip_address()."'";
				$mysql->db_query($sql);
			}
		}
		$sql = "select * from `{online}` ";
		$result = $mysql -> db_fetch_arrays($sql);
		$num =0 ;
		$user_num =0 ;
		$no_user_num =0 ;
		foreach ($result as $key => $value){
			if ($value['user_id']==0){
				$no_user_num++;
			}else{
				$user_num++;
			}
			$num++;
		}
		
		if  ($_G['cache']['user_online_num']<$num){
			$sql = "update `{cache}` set user_online_num='{$num}',user_online_time='".time()."' where date='".date("Y-m-d",time())."'";
			$mysql->db_query($sql);
		}
		return array("num"=>$num,"user_num"=>$user_num,"nouser_num"=>$no_user_num,"list"=>$result);
	}
	
	/**
	 * 获得用户的记录
	 * 
	 * @return Array
	 */
	function GetUserLog($data = array()){
		global $mysql,$_G;
		$_sql = " where 1=1 ";
		if (isset($data['username']) && $data['username']!=""){
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}
		if (isset($data['quer']) && $data['quer']!=""){
			$_sql .= " and p1.query like '%{$data['quer']}%'";
		}
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_select = "p1.*,p2.username,p2.realname";
		$sql = "select SELECT from `{user_log}` as p1 left join `{user}` as p2 on p1.user_id=p2.user_id {$_sql} ORDER LIMIT";
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.addtime desc', $limit), $sql));		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	
	
	
	/**
	 * 获得图片
	 * 
	 * @return Array
	 */
	function GetUpfiles($data = array()){
		global $mysql,$_G;
		$_sql = " where 1=1 ";
		if (isset($data['username']) && $data['username']!=""){
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}
		if (isset($data['quer']) && $data['quer']!=""){
			$_sql .= " and p1.query like '%{$data['quer']}%'";
		}
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_select = "p1.*,p2.username,p2.realname,p3.name as codename";
		$sql = "select SELECT from `{upfiles}` as p1 left join `{user}` as p2 on p1.user_id=p2.user_id left join `{module}` as p3 on p1.code=p3.code {$_sql} ORDER LIMIT";
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.addtime desc', $limit), $sql));		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	
	
	/**
	 * 获得图片
	 * 
	 * @return Array
	 */
	function GetUserCache($data = array()){
		global $mysql,$_G;
		$_sql = " where 1=1 ";
		if (isset($data['username']) && $data['username']!=""){
			$_sql .= " and p2.username like '%{$data['username']}%'";
		}
		if (isset($data['quer']) && $data['quer']!=""){
			$_sql .= " and p1.query like '%{$data['quer']}%'";
		}
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		
		$_select = "p1.*,p2.username,p2.realname,p3.name as codename";
		$sql = "select SELECT from `{upfiles}` as p1 left join `{user}` as p2 on p1.user_id=p2.user_id left join `{module}` as p3 on p1.code=p3.code {$_sql} ORDER LIMIT";
		
		$row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as num', '', ''), $sql));
		
		$total = $row['num'];
		$total_page = ceil($total / $epage);
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";
		
		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by p1.addtime desc', $limit), $sql));		
		return array(
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'epage' => $epage,
            'total_page' => $total_page
        );
	}
	/*
	 * parameter_system.php
	 */
	function createParameter_system(){
		global $mysql;
		$system = array();
		$system_name = array();
		$_system = $mysql->db_selects("system");
		foreach ($_system as $key => $value){
			$system[$value['nid']] = $value['value'];
			$system_name[$value['nid']] = $value['name'];
		}
		$str = '';
		$str .= '$_G["system"]='.self::arrayToString($system).';';
		$str .= '$_G["system_name"]='.self::arrayToString($system_name).';';
		$str .= '$system='.self::arrayToString($system).';';
		$str .= '$system_name='.self::arrayToString($system_name).';';
		return self::createFile($str, "parameter_system.php");
	}
	/*
	 * parameter_site.php
	 */
	function createParameter_site(){
		if (file_exists(ROOT_PATH.'core/site.class.php')){
			include_once (ROOT_PATH."core/site.class.php");
			$result = siteClass::GetList(array("limit"=>"all"));
			$str .= '$_G["site_list"]='.self::arrayToString($result).';';
		}
		return self::createFile($str, "parameter_site.php");
	}
	/*
	 * parameter_biaotype.php
	 */
	function createParameter_biaotype(){
		global $mysql;
		$str = '';
		/****$_G['cash_rule']提现规则****/
		$cash_rule = $mysql->db_fetch_arrays("select * from `{cash_rule}`");
		foreach ($cash_rule as $k=>$v){
			$str .= '$_G["cash_rule"]["'.$v['nid'].'"]='.self::arrayToString($v).';';
		}
		/****$_G['biaotype']标类型****/
		$result = $mysql->db_fetch_arrays("select * from `{biao_type}` where `available`=1");
		$biaotype = array();
		foreach($result as $value){
			$biaotype[$value['biao_type_name']]=$value;
		}
		$str .= '$_G["biao_type"]='.self::arrayToString($biaotype).';';
		/*******$_G['scrollpic']首页滚动图片********/
		$scrollpic = $mysql->db_fetch_arrays("select * from `{scrollpic}` where status=1 and (type_id=1 or type_id=2) order by `order` desc");
		$str .= '$_G["scrollpic"]='.self::arrayToString($scrollpic).';';
		/******$_G['partner']合作伙伴*****/
		$partner = $mysql->db_fetch_arrays("select * from `{links}` where status=1 and type_id=2 order by `order` desc limit 5");
		$str .= '$_G["partner"]='.self::arrayToString($partner).';';
		/******$_G['blogroll']友情链接*****/
		$blogroll = $mysql->db_fetch_arrays("select * from `{links}` where status=1 and type_id=1 order by `order` desc");
		$str .= '$_G["blogroll"]='.self::arrayToString($blogroll).';';
		return self::createFile($str, "parameter_biaotype.php");
	}
	/*
	 * parameter_linkage.php
	 */
	function createParameter_linkage(){
		$str="";
		/************$_G["linkage"]、$_G["_linkage"]数据来源数据库**************/
		include_once (ROOT_PATH.'modules/linkage/linkage.class.php');
		if (linkageClass::IsInstall()){
			$result = linkageClass::GetList(array("limit"=>"all"));
			foreach ($result as $key => $value){
				$_G['linkage'][$value['type_nid']][$value['value']] = $value['name'];
				$_G['linkage'][$value['id']] = $value['name'];
				if ($value['type_nid']!=""){
					$_G['_linkage'][$value['type_nid']][$value['id']] = array("name"=>$value['name'],"id"=>$value['id'],"value"=>$value['value']);
				}
			}
		}
		$str .= '$_G["linkage"]='.self::arrayToString($_G['linkage']).';';
		$str .= '$_G["_linkage"]='.self::arrayToString($_G['_linkage']).';';
		return self::createFile($str, "parameter_linkage.php");
	}
	/************联动 $_G['arealist'],parameter_area.php**********/
	function createParameter_area(){
		$str="";
		include_once (ROOT_PATH.'modules/area/area.class.php');
		$result = areaClass::GetList(array("limit"=>"all"));
		$str .= '$_G["arealist"]='.self::arrayToString($result).';';
		return self::createFile($str, "parameter_area.php");
	}
	function createFile($str, $filename){
		ob_start();
		echo '<?php if (!defined("ROOT_PATH"))  die("不能访问");';
		echo $str;
		echo ' ?>';
		$data = ob_get_contents();
		ob_end_clean();
		$fp = fopen(ROOT_PATH.'data/parameter/'.$filename,'wb');
		$a = fwrite($fp, $data);
		fclose($fp);
		return $a;
	}
	/**将数组拆分成字符串**/
	function arrayToString($arr){
		$str = 'array(';
		if(is_array($arr)){
			$count = count($arr,0);
			for($i=0; $i<$count; $i++){
				$key = key($arr);
				$cur = current($arr);
				if(is_numeric($key)){
					$str .= "$key=>";
				}else{
					$str .= "'$key'=>";
				}
				if(is_array($cur)){
					if($i==$count-1){
						$str .= self::arrayToString($cur);
					}else{
						$str .= self::arrayToString($cur);
						$str .= ',';
					}
				}else{
					$cur = str_replace("'","\'",$cur);
					if($i==$count-1){
						if(is_numeric($cur)){
							$str .= "$cur";
						}else{
							$str .= "'$cur'";
						}
					}else{
						if(is_numeric($cur)){
							$str .= "$cur,";
						}else{
							$str .= "'$cur',";
						}
					}
				}
				next($arr);
			}
		}
		return $str.')';
	}
}
?>
