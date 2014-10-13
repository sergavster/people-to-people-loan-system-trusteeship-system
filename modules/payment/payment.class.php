<?php
/**
 * @author Tissot.Cai(Email:Tissot.Cai@gmail.com)
 * @version 1.0
 */

class paymentClass {

	const ERROR = '操作有误，请不要乱操作';

	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetList($data = array()){
		global $mysql;
		$alllist = self::GetListAll();
		$_sql = "where 1=1 ";		 
		if (isset($data['status']) && $data['status']!=""){
			$_sql .= " and status = {$data['status']}";
		}
		$sql = "select * from `{payment}` {$_sql} order by `order` desc";
		$result = $mysql->db_fetch_arrays($sql);
		foreach ($result as $key => $value){
			$result[$key]['logo'] = $alllist[$value['nid']]['logo'];
		}
		return $result;
	}
	
	/**
	 * 列表
	 *
	 * @return Array
	 */
	function GetListAll($data = array()){
		global $mysql;
		$result = get_file(ROOT_PATH."modules/payment/paytype","file");
		$_result = "";
		foreach ($result as $key => $value){
			$_nid = explode(".class.php",$value);
			$nid = $_nid[0];
			$_result[$nid]['nid'] = $nid;
			$classname = $nid."Payment";
			include_once(ROOT_PATH."modules/payment/paytype/{$value}");
			$o = new $classname();
			$_result[$nid]['type'] = $o->type;
			$_result[$nid]['name'] = $o->name;
			$_result[$nid]['description'] = $o->description;
			$_result[$nid]['logo'] = "/data/images/payment/".$o->logo.".gif";
		}
		return $_result;
	}
	
	/**
	 * 查看
	 *
	 * @param Array $data
	 * @return Array
	 */
	public static function GetOne($data = array()){
		global $mysql;
		$nid = $data['nid'];
		$classname = $nid."Payment";
		include_once(ROOT_PATH."modules/payment/paytype/{$nid}.class.php");
		$o = new $classname();
		
		$_result['nid'] = $nid;
		$_result['type'] = $o->type;
		$_result['name'] = $o->name;
		
		$_result['description'] = $o->description;
		
		$_result['fields'] = $o->GetFields();

		$_result['logo'] = "/data/images/payment/".$o->logo.".gif";
		
		if ($_result['type'] ==1 || isset($data['id']) && $data['id']!=""){
			if(isset($data['id']) && $data['id']!=""){
				$sql = "select * from `{payment}` where nid = '{$nid}' and id={$data['id']}";
			}else{
				$sql = "select * from `{payment}` where nid = '{$nid}'";
			}
			$result = $mysql->db_fetch_array($sql);
			if ($result!=false){
			$_config = unserialize($result['config']);
			foreach ($_result['fields'] as $_key => $_value){
				$_result['fields'][$_key]['value'] =  isset($_config[$_key])?$_config[$_key]:"";
			}
			}
			if ($result != false) return $result+$_result;
		}
		return $_result;
	}
	
	 /**
	 * 添加
	 *
	 * @param Array $data
	 * @return Boolen
	 */
	function Action($data = array()){
		global $mysql;
		$nid = $data['nid'];
		$type = $data['type'];
		unset($data['type']);
		$sql = "select * from `{payment}` where nid = '{$nid}'";
		$result = $mysql->db_fetch_array($sql);
		if (($result == false || $type=="new")  && $type!="edit"){
			$_sql = "";
			$sql = "insert into `{payment}` set ";
			foreach($data as $key => $value){
				$_sql[] = "`$key` = '$value'";
			}
			return $mysql->db_query($sql.join(",",$_sql));
		}else{
			$_sql = $__sql = "";
			if (isset($data['id'])){
				$__sql .=" and id = '{$data['id']}'";
			}
			$sql = "update `{payment}` set ";
			foreach($data as $key => $value){
				$_sql[] .= "`$key` = '$value'";
			}
			$sql .= join(",",$_sql)." where nid = '$nid' {$__sql} ";
			return $mysql->db_query($sql);
		}
        
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
		$sql = "update `{payment}` set ";
		foreach($data as $key => $value){
			$_sql[] .= "`$key` = '$value'";
		}
		$sql .= join(",",$_sql)." where id = '$id'";
        return $mysql->db_query($sql);
	}
	
	function ToSubmit($data = array()){
		global $mysql,$_G;
		$data['user_id'] = $_G['user_id'];
		$payment = isset($data['payment'])?$data['payment']:"";
		$payment = isset($data['payment'])?$data['payment']:"";
		$data['notify_url'] = $_G['weburl']."/modules/payment/notify2.php";//通知地址
		$data['return_url'] = $_G['weburl']."/modules/payment/return.php";//回调地址
		$data['webname'] = $_G['system']['con_webname'];//回调地址
		$data['subject'] = isset($data['subject'])?$data['subject']:"";
		$data['body'] = isset($data['body'])?$data['body']:"";
		$data['trade_no'] = isset($data['trade_no'])?$data['trade_no']:"";
		$sql = "select * from `{payment}` where id = '{$payment}'";
		$result = $mysql->db_fetch_array($sql);
		if ($result==false) return self::ERROR;
		if($result['config']!=""){
			$data = unserialize($result['config'])+$data;
	
			include_once(ROOT_PATH."modules/payment/paytype/{$result['nid']}.class.php");
			$classname = $result['nid']."Payment";
			$payclass = new $classname;
			$url = $payclass->ToSubmit($data);
			return $url;
			
		}else{
			return "";
		}
		
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
		$sql = "delete from {payment}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	
}
?>
