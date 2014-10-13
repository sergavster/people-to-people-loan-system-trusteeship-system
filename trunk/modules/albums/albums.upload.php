<?
@header("Expires: -1");
@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
@header("Pragma: no-cache");
@header("Content-type: application/xml; charset=utf-8");
$op = empty($_GET['op'])?'':$_GET['op'];
$isupload = empty($_GET['cam']) && empty($_GET['doodle']) ? true : false;
$iscamera = isset($_GET['cam']) ? true : false;
$isdoodle = isset($_GET['doodle']) ? true : false;
$_P = array();

if($_FILES && $_POST && $_G['user_id']!="") {
	$proid = isset($_POST['proid'])?$_POST['proid']:"";
	$albumid = isset($_POST['albumid'])?$_POST['albumid']:0;
	$title = isset($_POST['title'])?$_POST['title']:"";
	if ($albumid==0){
		$_P['status'] = "failure";
	}else{
		if($title!=""){
			$title = addslashes(urldecode($title));
		}
		$data = array("file"=>"Filedata","file_size"=>"2048","user_id"=>$_G['user_id'],"name"=>$title,"aid"=>$albumid,"data_type"=>"user_ablums","code"=>"albums","cut_status"=>1,"cut_type"=>2,"cut_width"=>680,"cut_height"=>680);
		$pic_result = $upload->UpfileSwfupload($data);
		$_P['proid'] = $proid;
		$_P['uploadResponse'] = true;
		$_P['status'] = "success";
		$_P['albumid'] = $albumid;
	}
}

$magic->assign("_P",$_P);
$magic->template_dir = ROOT_PATH."/modules/albums";
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$magic->display("albums.upload.html");
exit;
?> 