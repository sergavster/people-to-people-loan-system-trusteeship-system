<?php
/**********************************************
***  Author: Ahui
***  Date:2008/08/05  
***  Filename: channel php
***  Describe: channel file
**********************************************/

class Channel{
	var $db_link;//数据库连接信息
	var $db_show_error;//是否将错误信息打印出来
	var $db_prefix;//数据库前缀名
	
	function Channel(){
		global $mysql;
		$this->mysql = $mysql;
		$this->ip = ip_address();
	}
	
	/**
	 * 建立内容模型相应的表
	 *
	 * ID，模型名称，标识名，
	 * 
	 * @return Integer
	 */
	function create_channel_table(){ //建立内容模型相应的表
		$sql = "CREATE TABLE IF NOT EXISTS  `{channel}` (
			  `channel_id` int(11) unsigned NOT NULL auto_increment,
			  `name` varchar(255) NOT NULL,
			  `nid` varchar(255) NOT NULL,
			  `module_id` int(11) NOT NULL,
			  `status` varchar(2) NOT NULL,
			  `order` int(11) NOT NULL,
			  `addtime` varchar(50) NOT NULL,
			  `addip` varchar(50) NOT NULL,
			  PRIMARY KEY  (`channel_id`)
			) ENGINE=MyISAM ;";
		
		return $this->mysql->db_query($sql);
	}
	
	/**
	 * 建立内容模型字段相应的表
	 *
	 * ID，所属内容模型，名称，标识名，类型，大小，
	 * 
	 * @return Integer
	 */
	function create_fields_table(){ 
		$sql = "CREATE TABLE IF NOT EXISTS `{fields}` (
		  `fields_id` int(11) unsigned NOT NULL auto_increment,
		  `channel_id` varchar(50) NOT NULL,
		  `name` varchar(50) NOT NULL,
		  `code` varchar(50) NOT NULL,
		  `type` varchar(50) NOT NULL,
		  `size` varchar(50) NOT NULL,
		  `input` varchar(50) NOT NULL,
		  `description` varchar(255) NOT NULL,
		  `default` varchar(255) NOT NULL,
		  `select` varchar(100) NOT NULL,
		  `order` int(11) NOT NULL default '0',
		  PRIMARY KEY  (`fields_id`)
		) ENGINE=MyISAM ";
		return $this->mysql->db_query($sql);
	}
	
	
	/**
	 * 建立文章模块相应的表
	 *
	 * @return Integer
	 */
	function create_table($table,$query=true){ //建立站点相应的表
		$sql = "CREATE TABLE IF NOT EXISTS `{$table}` (
		      `article_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
			  `user_id` int(11) NOT NULL DEFAULT '0',
			  `title` varchar(255) NOT NULL DEFAULT '',
			  `status` int(2) NOT NULL DEFAULT '0',
			  `litpic` varchar(255) NOT NULL DEFAULT '',
			  `jumpurl` varchar(255) NOT NULL DEFAULT '',
			  `summary` varchar(255) NOT NULL DEFAULT '',
			  `content` text NOT NULL,
			  `order` int(11) NOT NULL DEFAULT '0',
			  `hits` int(11) NOT NULL DEFAULT '0',
			  `comment` int(11) NOT NULL DEFAULT '0',
			  `addtime` varchar(50) NOT NULL DEFAULT '',
			  `addip` varchar(50) NOT NULL DEFAULT '',
			  PRIMARY KEY (`article_id`)
			) ENGINE=MyISAM;";
		if ($query) return $this->mysql->db_query($sql);
		else return $sql;
	}
	
	
	/**
	 * 建立文章模块相应的表
	 *
	 * @return Integer
	 */
	function create_type_table($table,$query=true){ //建立站点相应的表
		$table = $table."_type";
		$query = "CREATE TABLE `{$table}` (
		  	`type_id` int(11) unsigned NOT NULL auto_increment,
		  	`name` varchar(100)  NOT NULL default '0',
			`summary` varchar(200)  NOT NULL default '0',
			`remark` varchar(200)  NOT NULL default '0',
		  	`status` int(2)  NOT NULL default '0',
		  	`order` int(10)  NOT NULL default '0',
		  	`user_id` int(11)  NOT NULL default '0' ,
		  	`addtime` varchar(50) NOT NULL DEFAULT '',
			`addip` varchar(50) NOT NULL DEFAULT '',
		  PRIMARY KEY  (`type_id`)
		) ENGINE=MyISAM  ;";
		
		if ($query) return $this->mysql->db_query($sql);
		else return $sql;
	}
	/**
	 * 删除内容模型字段基本表
	 *
	 * @return Integer
	 */
	function drop_table($table){
		$query = "DROP TABLE `{$table}`;";
		return $this->mysql->db_query($sql);
	}
	
	/**
	 * 添加内容模型
	 *
	 * @param Array $var 
	 * @return Integer
	 */
	function add_channel($var){
		if ($var['name']=="" || $var['code']=="" )	return -1;
		$code = $var['code'];
		$module_id = $var['module_id'];
		
		$sql = "select * from `{channel}` where `code`='$code' ";
		if ($this->mysql->db_fetch_array($sql)!=false) return -2; //模型的标识名已经存在
				
		//往模型表里插入一条数据
		$sql = "insert into `{channel}` set ";
		foreach ($var as $key => $value ){
			$_sql[] = "`$key` = '$value'";
		}
		$sql = join(",",$_sql).$sql;
		$sql .= "`addtime`='".time()."',`addip`='$this->ip'";
		
		$this->mysql->db_query($sql);
		
		//建立相应的表
		return $this->create_table($code);
		
	}
	
	
	/**
	 * 修改内容模型
	 *
	 * @param Integer $channel_id
	 * @param Array $var 
	 * @return Integer
	 */
	function update_channel($channel_id,$var){
		if ($channel_id=="")	return -1;
		$query = "update `{channel}` set ";
		foreach ($var as $key => $value ){
			$query .= "`$key` = '$value',";
		}
		$query .= "`channel_id` = '$channel_id' where `channel_id` = $channel_id";
		return $this->mysql->db_query($sql);
	}
	
	/**
	 * 修改排序
	 *
	 * @param Array $channel_id 
	 * @param Array $order 
	 * @return Integer
	 */
	function order_channel($channel_id,$order){
		if ($channel_id == "")	return -1;
		foreach ($channel_id as $key => $item){
			$query = "update {channel} set `order`=".$order[$key]." where channel_id=$item";
			$this->mysql->db_query($sql);
		}
		return true;
	}
	
	/**
	 * 删除内容模型，将会一起删掉模型表，清除站点表，字段表，模型表里面相应的数据
	 *
	 * @param Integer $channel_id 
	 * @return Integer
	 */
	function del_channel($channel_id){
		if ($channel_id=="") return -1;
		$sql = "select * from {channel} where channel_id=$channel_id ;";
		$row = $this->mysql->db_fetch_array($sql);
		$result = $this->mysql->drop_table($row['code']);//删除模型相应的表
		if(result==false) return false;
		
		$sql = "delete  from `{channel}`  where `channel_id` = $channel_id;";
		$this->mysql->db_fetch_array($sql);;
		
		$sql = "delete  from `{site}`  where `channel_id` = $channel_id;";
		$this->mysql->db_fetch_array($sql);
		
		$sql = "delete  from `{fields}`  where `channel_id` = $channel_id;";
		$this->mysql->db_fetch_array($sql);
		
		return true;
	}
	
	
	
	/**
	 * 获取内容模型
	 *
	 * @param Integer $channel_id 
	 * @return Array
	 */
	function view_channel($channel_id){
		if ($channel_id=="") return -1;
		$sql = "select * {channel} from where channel_id=$channel_id;";
		return $this->mysql->db_fetch_array($sql);
	}

	/**
	 * 获取内容模型列表
	 *
	 * @param Integer $channel_id 
	 * @return Array
	 */
	function list_channel(){
		$query = "select * from `{channel}` order by `order` desc,`channel_id` desc ";
		return $this->mysql->db_fetch_arrays($sql);
	}
	
	
	
	
	
	
	
	/**
	 * 添加字段
	 *
	 * @param Integer $fields_id 
	 * @return Array
	 */
	function add_fields($channel_id,$var){
		$notallow = array("name","order","url","type","article_id","id","litpic","content","summary","hits","type_id","user_id");
		if ($channel_id=="" || $index['code'] == "" || $index['name'] =="")	return -1;
		
		//检测标识名是否存在
		$sql = "select * from {fields} where code='".$index['code']."' and channel_id='$channel_id' ";
		$result = $this->mysql->db_fetch_array($sql); 
		
		if ($result!=false || in_array($code,$notallow)){
			return -3; //一些禁止的字段
		}
		
		//往字段表中添加数据
		$sql = "insert into `{fields}` set channel_id='$channel_id'";
		foreach ($var as $key => $value){
			$sql .= ",`$key`='$value'";
		}	
		$result = $this->mysql->db_query($sql); 
		
		$size = $var['size'];
		$type = $var['type'];
		if (!empty($size)){
			$field_size="($size)";
		}
		
		//判断是属于内容模型的还是其他模块的
		
		$fields_table = "{cms_".$code."_fields}";
		$query = "CREATE TABLE IF NOT EXISTS  $fields_table (
		  `content_id` int(11) NOT NULL
		) ENGINE=MyISAM  DEFAULT CHARSET=$this->dbLanguage;";
		$result = $this->mysql->db_query($sql); 
		
		//往表中添加字段
		$query="alter table {$fields_table} add `".$code."`  ".$type.$field_size." NOT NULL ";
		return  $this->mysql->db_query($sql); 
	}
	
	
	
	/**
	 * 修改字段
	 *
	 * @param Integer $fields_id 
	 * @return Array
	 */
	function update_fields($fields_id,$channel_id,$var){
		if ($channel_id==""  || $var['name'] =="")	return -1;
		$code = $var['code'];
		
		//修改字段表中添加数据
		$sql = "update `{fields}` set channel_id='$channel_id'";
		foreach ($var as $key => $value){
			$query .= ",`$key`='$value'";
		}	
		$sql .= " where `fields_id` = $fields_id ";
		if($this->mysql->db_query($sql) ==false) return false;
		
		$size = $var['size'];
		$type = $var['type'];
		if (!empty($size)){
			$field_size="(".$size.")";
		}
		
		//判断是属于内容模型的还是其他模块的
		
		$fields_table = "{cms_".$code."_fields}";
		
		$sql="alter table $fields_table change $code $code ".$type.$field_size." NOT NULL ";
		return $this->mysql->db_query($sql);
	}
	
	
	/**
	 * 删除字段
	 *
	 * @param Integer $fields_id 
	 * @return Array
	 */
	function del_fields($fields_id,$channel_id,$code){
		if ($fields_id=="" || $channel_id=="" || $code=="")	return -1;
		$fields_table = "{cms_".$code."_fields}";
		$sql = "delete  from {fields}  where fields_id=$fields_id;";
		$this->mysql->db_query($sql);
		$sql = "alter table $fields_table drop $nid";
		$this->mysql->db_query($sql);
		return 1;
	}
	

	/**
	 * 获取字段列表
	 *
	 * @param Integer $fields_id 
	 * @return Array
	 */
	function list_fields($channel_id){
		if ($channel_id == "") return -1;
		$query = "select * from `{fields}`  where `channel_id` = '$channel_id' order by `order` desc,`fields_id` ";
		return $this->mysql->db_fetch_arrays($sql);
	}
	
	/**
	 * 获取字段
	 *
	 * @param Integer $fields_id 
	 * @return Array
	 */
	function view_fields($fields_id){
		if ($fields_id=="") return -1;
		$query = "select * from `{fields}` where fields_id=$fields_id ";
		return $this->mysql->db_fetch_array($sql);;
	}
	
	
	/**
	 * 修改字段排序
	 *
	 * @param Array $fields_id 
	 * @param Array $order 
	 * @return Integer
	 */
	function order_fields($fields_id,$order){
		if ($fields_id == "")  return -1;
		foreach ($fields_id as $key => $item){
			$sql = "update `{fields}` set `order`=".$order[$key]." where `fields_id` = $item";
			$this->mysql->db_query($sql);
		}
		return true;
	}
	
	
	function create_file_info($var){
		$content = '<?
		\$code = "'.$var['code'].'";
		\$name = "'.$var['name'].'";
		\$description = "'.$var['description'].'";
		\$version = "'.$var['version'].'";
		\$author = "'.$var['author'].'";
		\$date = "'.$var['date'].'";
		?>';
		mk_file("modules/".$var['code']."/".$var['code'].".info",$content);
	}
	
	function create_file_sql($code){
		$content = $this->create_table($code,false);
		$content .= "\n\n\n\n".$this->create_type_table($code,false);
		mk_file("modules/".$var['code']."/".$var['code'].".sql",$content);
	}
	
	function create_file_index($code){
		$content = $this->create_table($code,false);
		$content .= "\n\n\n\n".$this->create_type_table($code,false);
		mk_file("modules/".$var['code']."/".$var['code'].".sql",$content);
	}
	
	function create_file_tpl($code){
		$content = $this->create_table($code,false);
		$content .= "\n\n\n\n".$this->create_type_table($code,false);
		mk_file("modules/".$var['code']."/".$var['code'].".sql",$content);
	}
	
	function create_file_class($code){
		$content = $this->create_table($code,false);
		$content .= "\n\n\n\n".$this->create_type_table($code,false);
		mk_file("modules/".$var['code']."/".$var['code'].".sql",$content);
	}
}
?>
