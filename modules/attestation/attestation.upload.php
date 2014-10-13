<?
@header("Expires: -1");
@header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
@header("Pragma: no-cache");
@header("Content-type: application/xml; charset=utf-8");
$op = empty($_GET['op'])?'':$_GET['op'];
$isupload = empty($_GET['cam']) && empty($_GET['doodle']) ? true : false;
$iscamera = isset($_GET['cam']) ? true : false;
$isdoodle = isset($_GET['doodle']) ? true : false;
if($_FILES && $_POST && $_G['user_id']!="") {
	if ($_POST['albumid']==0){
		$_P['status'] = "failure";
	}else{
		$title = iconv("UTF-8","GBK",urldecode($_POST['title']));
		$pic_result = $upload->UpfileSwfupload(array("file"=>"Filedata","filesize"=>"2048","user_id"=>$_G['user_id'],"name"=>$title,"aid"=>				$_POST['albumid'],"data_type"=>"attestation_litpic","code"=>"attestation"));
		$data['name'] = $title;
		$data['type_id'] = $_POST['albumid'];
		$data['litpic'] = $pic_result['filename'];//上传的图片
		$data['user_id'] = $_G['user_id'];
		include_once("attestation.class.php");
		attestationClass::Add($data);
		$_P['proid'] = $_POST['proid'];
		$_P['uploadResponse'] = true;
		$_P['status'] = "success";
		$_P['albumid'] = $uploadfiles['albumid'];
	}
}
/*
if($_FILES && $_POST) {
	if($_FILES["Filedata"]['error']) {
		$_G['uploadfiles'] = "图片过大";
	} else {
		//$_FILES["Filedata"]['name'] = addslashes(diconv(urldecode($_FILES["Filedata"]['name']), 'UTF-8'));
		//$_POST['albumid'] = addslashes(diconv(urldecode($_POST['albumid']), 'UTF-8'));
		$catid = $_POST['catid'] ? intval($_POST['catid']) : 0;
		$_POST['albumid'] = addslashes(diconv(urldecode($_POST['albumid']), 'UTF-8'));
		/*
		$_aid = explode("new:",$_POST['albumid']);
			if (isset($_aid[1]) && $_aid[1]!=""){
				$data['name'] = $_aid[1];
				$data['user_id'] = $_G['user_id'];
				$_POST['albumid'] = albumsClass::AlbumsAdd($data);
			}
		
		$_POST['albumid'] = iconv("GBK", "UTF-8", $_POST['albumid']);
		$uploadfiles = $upload->UpfileSwfupload(array("file"=>"Filedata","filesize"=>"2048","user_id"=>$_G['user_id'],"name"=>addslashes(diconv(urldecode($_POST['title']), 'UTF-8')),"aid"=>$_POST['albumid'],"code"=>"attestation","function"=>"Add"));
		//$uploadfiles = pic_save($_FILES["Filedata"], $_POST['albumid'], addslashes(diconv(urldecode($_POST['title']), 'UTF-8')), true, $catid);
	}
	$_P['proid'] = $_POST['proid'];
	$_P['uploadResponse'] = true;
	$_P['albumid'] = 0;
	if($uploadfiles && is_array($uploadfiles)) {
		$_P['status'] = "success";
		$_P['albumid'] = $uploadfiles['albumid'];
	} else {
		$_P['status'] = "failure";
	}

	
}*/
$magic->assign("_P",$_P);
$magic->template_dir = ROOT_PATH."/modules/attestation";
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$magic->display("attestation.upload.html");
exit;
?> 