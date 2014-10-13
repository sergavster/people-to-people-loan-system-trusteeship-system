<?php
/**
 * 评论模块
 *
 */
class commentClass {


    const NOT_EXISTS_USER   = '用户不存在';
    const NOT_EXISTS_MODULE = '模块不存在';
    
    /**
     * 发表评论
     * @param $user_id 会员ID
     * @param $module_code 模块
     * @param $article_id 文章ID
     * @param $comment 内容
     */
    public static function AddComment ($data = array()) {
        global $mysql, $_G;
		$user_id = $data['user_id'];
		
        if (!$mysql->db_fetch_array("select 1 from {user} where user_id={$user_id}")) {
            return self::NOT_EXISTS_USER;
        }
		
        if (!$mysql->db_fetch_array("select 1 from {module} where code='{$data['module_code']}'")){
            return self::NOT_EXISTS_MODULE;
        }

        
        if (1 == $_G['system']['con_comment_verify']) {
            $data['status'] = 0;
            $data['flag'] = 0;
        }
        
		$sql = "insert into `{comment}` set `addtime` = '".time()."',`addip` = '".ip_address()."'";
		foreach($data as $key => $value){
			$sql .= ",`$key` = '$value'";
		}
        return $mysql->db_query($sql);
    }

    /**
     * 获取评论列表
     * @param $module 模块
     * @param $article_id 文章ID
     * @param $statu 状态
     * @param $page 页码
     * @param $page_size 每页记录数
     */
    public static function GetList ($data = array()) {
        global $mysql;
		$page = empty($data['page'])?1:$data['page'];
		$epage = empty($data['epage'])?10:$data['epage'];
		$_sql = "where c.pid=0 ";//直接对文章的评论
		
        if(isset($data['code']) && $data['code']!=""){
			$_sql .= " and  c.module_code = '{$data['code']}' "; 
		}
        if(isset($data['article_id']) && $data['article_id']!=""){
			$_sql .= " and  c.article_id = '{$data['article_id']}' "; 
		}
        if(isset($data['status']) && $data['status']!=""){
			$_sql .= " and  c.status = '{$data['status']}' "; 
		}
		
		$_select = "c.*, u.username,u.realname,u.litpic, m.name as module_name";
		 $sql = "select SELECT from {comment} c
                    left join {user} u on c.user_id = u.user_id
                    left join {module} m on c.module_code=m.code {$_sql} ORDER LIMIT";
		
        $row = $mysql->db_fetch_array(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array('count(1) as cnt', '', ''), $sql));
		$total_record = $row['cnt'];
		$total_page = ceil($total_record / $epage);
		$page = max(1, min($page, $total_page));
		$index = $epage * ($page - 1);
		$limit = " limit {$index}, {$epage}";

		$list = $mysql->db_fetch_arrays(str_replace(array('SELECT', 'ORDER', 'LIMIT'), array($_select, 'order by c.order desc,c.id desc', $limit), $sql));
		$list = $list?$list:array();
			
        $list = $list?$list:array();
		foreach ($list as $key => $value){
			$list[$key]['time'] = date("Y-m-d H:i:s",$value['addtime']);
			$list[$key]['litpic'] = get_avatar(array("user_id"=>$value['user_id'],"type"=>"middle"));
			//$list[$key]['sub'] = self::GetSubComment($value['id']);
			if(file_exists($_SERVER['DOCUMENT_ROOT']."/data/avatar/{$value['user_id']}_avatar_middle.jpg")){
				$list[$key]['head'] = "/data/avatar/{$value['user_id']}_avatar_middle.jpg";
			}else{
				$list[$key]['head'] = "/data/avatar/noavatar_middle.gif";
			}
		}
		
        return array(
            'list' => $list,
            'total' => $total_record,
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
		
		
		if (!empty($id)){
			$_sql .= " and c.id=$id"; 
		}
		
		$sql = "select s.nid as site_nid,co.name as title,m.name as module_name,u.username ,c.* from {comment} c 
				left join {user} u on c.user_id = u.user_id 
				left join {module} m on c.module_code = m.code 
				left join {{$data['code']}} co on co.id=c.article_id
				left join {site} s on s.site_id = co.site_id 
				{$_sql}";
		$sql_1 = "select comment from `{comment}` where pid=$id";
		$re_1 = $mysql->db_fetch_array($sql_1);
		$re = $mysql->db_fetch_array($sql);
		return array(
				'comment'=>$re,
				'reply'=>$re_1
				);
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
		$sql = "update `{comment}` set ";
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
		$sql = "delete from {comment}  where id in (".join(",",$id).")";
		return $mysql->db_query($sql);
	}
	
	
    /**
     * 删除评论
     * @param $id 评论ID
     */
    public static function DeleteComment ($id) {
        global $mysql;

        $mysql->db_query("delete from {comment} where id={$id}");

        return true;
    }

    /**
     * 修改评论状态
     * @param $id 评论ID
     */
    public static function ChangeCommentStatus ($id) {
        global $mysql;

        $mysql->db_query("update {comment} set status=1-status where id={$id}");

        return true;
    }

	/**
	 * 获取评论的子评论
	 * @param $id 评论ID
	 */
	public static function GetSubComment ($id) {
		global $mysql;

		$sql = "select c.*, u.username,u.realname,u.litpic, m.name as module_name from {comment} c
                    left join {user} u on c.user_id = u.user_id
                    left join {module} m on c.module_code=m.code where c.pid={$id}";
		
		$rows = $mysql->db_fetch_arrays($sql);
		foreach ($rows as $key => $row) {
			$row['sub'] = self::GetSubComment($row['id']);
			$rows[$key] = $row;
		}

		return $rows;
	}
}
?>
