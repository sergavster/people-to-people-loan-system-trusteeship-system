<?
if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问
check_rank("fee_".$_t);//检查权限

require_once 'fee.class.php';


$_A['list_purview'] = array("fee"=>array("费用管理"=>array("fee"=>"费用管理")));//权限
$_A['list_name'] = $_A['module_result']['name'];
$_A['list_menu'] = "";

if($_A['query_type']=="list"){
	$sql = "select * from `{cash_rule}`";
	$_A['cash_rule']=$mysql->db_fetch_arrays($sql);
	//$magic->assign("_A",$_A);
}
elseif($_A['query_type']=="edit" || $_A['query_type']=="new"){
	if(isset($_POST['nid'])){
		$var = array('nid','name','status','cash_scale','min_scale','max_money','min_money','max_day_money','arrival_time','fast_min_money','fast_cash_scale');
		$data = post_var($var);
		$data['cash_scale'] = 0;//宝付暂无手续费
		$data['min_scale'] = 0;//宝付暂无手续费
		if($_A['query_type']=="edit"){
			$sql = "update `{cash_rule}` set ";
			$where = ' where id='.$_POST['id'];
		}else{
			$sql = "insert into `{cash_rule}` set ";
			$where = '';
		}
		$_sql = '';
		foreach ($data as $key=>$value){
			$_sql .= ','.$key."='".$value."'";
		}
		$_sql = substr($_sql, 1);
		$sql .= $_sql.$where;
		$re = $mysql->db_query($sql);
		if($re==false){
			$msg = array("操作失败");
		}else{
			//$msg = array("操作成功");
			$p_1 = systemClass::createParameter_biaotype();
			if($p_1>0){
				$msg = array("操作成功");
			}else{
				$msg = array("操作失败");
			}
		}
	}else if(isset($_GET['id'])){
		$id = intval($_GET['id']);
		if($id<1){
			$msg = array("请不要乱操作");
		}else{
			$sql = "select * from {cash_rule} where id=$id";
			$result = $mysql->db_fetch_array($sql);
			if($result==false){
				$msg = array("未找到相关内容");
			}else{
				$_A['cash_rule'] = $result;
			}
		}
	}
}
?>