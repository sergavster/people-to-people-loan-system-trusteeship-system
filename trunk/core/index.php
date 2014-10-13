<? 
include ("magic.class.php");
$magic = new magic();
//$magic->is_compile = false;
$magic->template_dir = "";
$magic->force_compile = true;
$magic->assign("aa",array("aa"=>"11"));
$magic->display("tpl.html");
?>