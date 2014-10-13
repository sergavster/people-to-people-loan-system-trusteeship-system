<?php

require_once 'comment.class.php';
$type = isset($_GET['type'])?$_GET['type']:'list';

$code = isset($_GET['code'])?$_GET['code']:'';
$article_id  = isset($_GET['id'])?$_GET['id']:0;
$pid  = isset($_GET['pid'])?$_GET['pid']:0;
# 评论列表
if ('list' == $type) {
    $page  = isset($_GET['page'])?$_GET['page']:1;
    $epage = isset($_GET['epage'])?$_GET['epage']:10;
    if (!$module || !$article_id) {
    	$template = "error.html";
    	$msg = array("您的输入有误,找不到相应的页面","<a href='/'>返回首页</a>");
    	$_G['msg'] = $msg;
    	$magic->assign("_G",$_G);
    	$magic->display($template);
    	exit();
        //echo jsList(array(), $page, 0, 0, $epage, false, '参数无效');
    }else{
		$data['page'] = $page;
		$data['epage'] = $epage;
		$data['code'] = $code;
		$data['status'] = 1;
		$data['article_id'] = $article_id;
        $result = commentClass::GetList($data);
        if($result['list']==false){
        	echo jsList($result['list'], 0, 0, 0, 0);
        }else{
        	echo jsList($result['list'], $result['page'], $result['total_page'], $result['total'], $epage);
        }
    }
}

elseif ('lists' == $type) {
    $page  = isset($_GET['page'])?$_GET['page']:1;
    $epage = isset($_GET['epage'])?$_GET['epage']:10;
  
	$data['page'] = $page;
	$data['epage'] = $epage;
	$data['code'] = $code;
	$data['status'] = 1;
	$data['article_id'] = $article_id;
	$result = commentClass::GetList($data);
	$magic->display("comment.html.php");
    exit;
}
# 添加评论
elseif ('add' == $type) {
	
    $valicode = isset($_GET['valicode'])?$_GET['valicode']:0;
    $comment = isset($_GET['comment'])?trim($_GET['comment']):'';
    $comment = htmlspecialchars($comment, ENT_QUOTES);
    $comment = preg_replace("/<.*>/U", "", $comment);
	$msg = "";
    if ($_G['user_id']=="") {
        $msg =  '没有登录,请先登录';
        echo "<br>$msg<br /><br /><a href='/index.php?user&type=login'>关闭框</a><br /><br />系统将3秒后跳转<script>setTimeout('curl()',2000);function curl(){ location.href='/index.php?user&type=login'}</script>";
	exit;
    }
	
    if (!isset($_SESSION['valicode']) || $_SESSION['valicode']=="" || $_SESSION['valicode'] != $valicode) {
       $msg = '验证码错误!';
    }
	
    if ($article_id <= 0) {
        $msg = '文章ID无效';
    }
    if (!$comment) {
        $msg =  '无评论内容';
    }
	if (isset($msg) && $msg==""){
		$user_id = isset($_SESSION['user_id'])?$_SESSION['user_id']:0;
		$data = array(
			'pid' => 0,
            'user_id' => $_G['user_id'],
            'module_code' => $code,
            'article_id' => $article_id,
            'comment' => $comment,
            'status' => 1,
            'flag' => 1,
        );
  	 	 $result = commentClass::AddComment($data);
  	 	 $msg = "评论成功";
		 $_SESSION['valicode'] = "";
		 echo "<br>$msg<br /><br /><a href='javascript:void(0)' onclick='curl()' style='color:#FFFFFF'>关闭框</a><br /><br />系统将3秒后跳转<script>setTimeout('curl()',3000);function curl(){location.reload();}</script>";
	exit;
	}
	echo $msg;
	exit;
}
# 无效操作
else{
    
}

/*
 * 列表生成JAVASCRIPT
 * @param $list 记录列表
 * @param $page 当前页码
 * @param $total_page 总页码
 * @param $record_num 总记录数
 * @param $epage 每页记录数
 * @param $code 状态值:true/false
 * @param $message 消息
 */
function jsList ($list, $page, $total_page, $record_num, $epage = 0, $code = true, $message='') {

    $list = $list?$list:array();

    $js_list = createJsList($list);
    
    return "
        var result = new Array();
        result = {
            'code':'" . (string)($code) . "',
            'message':'{$message}',
            'list':{$js_list},
            'page':$page,
            'total_page':{$total_page},
            'total':{$record_num},
            'epage':{$epage}
        };
    ";
}
//表情处理函数
function ubbReplace($str){
 	$str = htmlgl($str);
	$str = preg_replace("[\[/face([0-9]*)\]]","<img src=\"/themes/face/face/$1.gif\" />",$str);
	return $str;
}

function createJsList ($list) {
	
	$js_list = array();
	$js_str = '';
    foreach ($list as $record) {
        $key_value = array();
        foreach ($record as $key => $value) {
			
			if (!is_array($value)) {
				if($key=='comment'){
					
					$value = ubbReplace($value);
					}
				array_push($key_value, "'{$key}':'{$value}'");
			}
			else {
				if (!empty ($value)) {
					$js_str .= "'{$key}':" . createJsList($value);
				}
			}
        }
        array_push($js_list, '{' . implode(',', $key_value) .'}');
    }
    $js_str .= implode(',', $js_list);
	
	return "new Array({$js_str})";
}
?>
