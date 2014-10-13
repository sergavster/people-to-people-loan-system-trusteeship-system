<?

if (!defined('ROOT_PATH'))  die('不能访问');//防止直接访问

/**
 * 融盾安全登陆模块  用户异地登录检测 add by weego 20120608
 */
function areaLoginCheck($user_id)
{
	global $mysql;
	$sql = "select * from `{user}` where user_id = '{$user_id}'";
	$lastmsg = $mysql->db_fetch_array($sql);
	$login_lasttime=$lastmsg['lasttime']; //上一次登录时间
	$login_lastip=$lastmsg['lastip']; //上一次登录IP
	$login_nowtime = time(); //当前时间
	$login_nowip=ip_address();
	//转化为地区和显示的时间
	require_once(ROOT_PATH.'core/iplocation.class.php');
	$IpLocation = new IpLocation();
	$client = $IpLocation->getlocation();
	//本次
	$login_nowtime = date('m月d日 H:i',$login_nowtime); //当前时间
	$login_nowip=$client['ip']; //本次登录IP
	$login_nowcountry=$client['country']; //本次登录地区
	//上一次
	$client2 = $IpLocation->getlocation($login_lastip);
	$login_lasttime=date('m月d日 H:i',$login_lasttime); //上一次登录时间
	$login_lastip=$client2['ip']; //上一次登录IP
	$login_lastcountry=$client2['country']; //上一次登录地区
	$rstmsg="上次：{$login_lastcountry} {$login_lasttime}<br/>本次：{$login_nowcountry} {$login_nowtime} ";
	return $rstmsg;


}

/**
 * 过滤提交的参数 跨站漏洞 add by weego 20120415
 */
function htmlgl($str,$isa='0')
{
	$str = preg_replace( "@<script(.*?)</script>@is", "hackman", $str );
	$str = preg_replace( "@<iframe(.*?)</iframe>@is", "hackman", $str );
	$str = preg_replace( "@<style(.*?)</style>@is", "hackman", $str );
	if($isa>'0'){

	}else{
		$str = preg_replace( "@<(.*?)>@is", "hackman", $str );
	}
	$str=hackman($str);
	return $str;
}
/**
 * 过滤提交的参数 注入漏洞 add by weego 20120415
 */
function safegl($str)
{
	$str=trim($str);
	$str=str_replace(",","hackman",$str);
	$str=str_replace("'","hackman",$str);
	$str=str_replace('"',"hackman",$str);
	$str=str_replace("%","hackman",$str);
	$str=str_replace("<","hackman",$str);
	$str=str_replace("?","hackman",$str);
	$str=hackman($str);
	return $str;
}
/**
 * 检测提交参数，记录可疑人IP 安全检测 add by weego 20120606
 */
function hackman($str)
{

	$hstat=stristr($str,"hackman");
	if($hstat){
		ob_start();
		ob_get_clean();
		ob_clean();

		die("入侵监测发现可疑请求！  ——融盾安全卫士");
		exit;
	}else{
		return $str;
	}
}


/****
 *动态口令认证
*
*/
function otp_check($uchon_sn_db, $uchon_otp){
	$fp = fsockopen("udp://42.120.11.17", 3344, $errno, $errstr, 5);
	$req = "code=0&id=username&sn={$uchon_sn_db}&otp={$uchon_otp}&svrC=&";
	if(!$fp){
		echo '<script>alert("code:0604-1 ，如果看到此消息，请告诉技术(耀耀)错误Code，顺便去买张彩票，按理你们这辈子是无缘看到此消息的! ");</script>';
		return "cann't to open file";
	}
	$res = '';
	$i = 0;
	while(!$res){
		fputs($fp, $req);
		if(20==$i++)break;
		$res = fread($fp, 1024);
	}
	fclose($fp);
	if($errno){
		return $errno. "  errstr:  ".$errstr ."  res:" . $res;
	}
	if($i == 21 && $res=='')return "网络有问题，请休息一会后重试……";
	//id=username&421 OTP_ERROR
	$space = explode(" ",$res);
	$num = explode("&",$space[0]);
	return $num[1];
}
/**
 * 获取IP地址
 */
function ip_address() {
	if(!empty($_SERVER["HTTP_CLIENT_IP"])) {
		$ip_address = $_SERVER["HTTP_CLIENT_IP"];
	}else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
		$ip_address = array_pop(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']));
	}else if(!empty($_SERVER["REMOTE_ADDR"])){
		$ip_address = $_SERVER["REMOTE_ADDR"];
	}else{
		$ip_address = '';
	}
	return $ip_address;
}

function get_module_info ($module,$module_dir=""){
	$var = array("code","name","version","description","author","date","type");
	if ($module_dir=="") $module_dir = ROOT_PATH."modules/$module/";
	include ($module_dir."".$module.".info");
	foreach($var as $val){
		$result[$val] = empty($$val)?"":$$val;
	}
	return $result;
}

function editor($fname="content",$value="",$width=630,$height=460){
	require_once(ROOT_PATH ."/plugins/sinaeditor/Editor.class.php");
	$editor=new sinaEditor($fname);
	$editor->Value= $value;
	$editor->BasePath='../libs';
	$editor->Height= $height;
	$editor->Width=$width;
	$editor->AutoSave=false;
	return  $editor->Create();
}


function mk_dir($dir,$dir_perms=0775){
	/* 循环创建目录 */
	if (DIRECTORY_SEPARATOR!='/') {
		$dir = str_replace('\\','/', $dir);
	}


	if (is_dir($dir)){
		return true;
	}

	if (@ mkdir($dir, $dir_perms)){
		return true;
	}

	if (!mk_dir(dirname($dir))){
		return false;
	}

	return mkdir($dir, $dir_perms);

}
function mkdirs($path, $mode = 0777){
	$dirs = explode('/',$path);
	$pos = strrpos($path, ".");
	if ($pos === false) {
		$subamount=0;
	}
	else {
		$subamount=1;
	}

	for ($c=0;$c < count($dirs) - $subamount; $c++) {
		$path="";
		for ($cc=0; $cc <= $c; $cc++) {
			$path.=$dirs[$cc].'/';
		}
		if (!file_exists($path)) {
			mkdir($path,$mode);
		}
	}
}


function mk_file($dir,$contents){
	$dirs = explode('/',$dir);
	if($dirs[0]==""){
		$dir = substr($dir,1);
	}
	mk_dir(dirname($dir));
	@chmod($dir, 0777);
	if (!($fd = @fopen($dir, 'wb'))) {
		$_tmp_file = $dir . DIRECTORY_SEPARATOR . uniqid('wrt');
		if (!($fd = @fopen($_tmp_file, 'wb'))) {
			trigger_error("系统无法写入文件'$_tmp_file'");
			return false;
		}
	}
	fwrite($fd, $contents);
	fclose($fd);
	@chmod($dir, 0777);
	return true;
}


function get_file($dir,$type='dir'){
	$result = "";
	if (is_dir($dir)) {
		if ($dh = opendir($dir)){
			while (($file = readdir($dh)) !== false){
				$_file = $dir."/".$file;
				if ($file !="." && $file != ".." && filetype($_file)==$type ){
					$result[] = $file;
				}
			}
			closedir($dh);
		}
	}
	return $result;
}
//删除指定目录（文件夹）中的所有文件函数
function del_file($dir) {
	if (is_dir($dir)) {
		$dh=opendir($dir);//打开目录 //列出目录中的所有文件并去掉 . 和 ..
		while (false !== ( $file = readdir ($dh))) {
			if($file!="." && $file!="..") {
				$fullpath=$dir."/".$file;
				if(!is_dir($fullpath)) {
					unlink($fullpath);
				} else {
					del_file($fullpath);
				}
			}
		}
		closedir($dh);
	}
}
function read_file($filename) {
	if ( file_exists($filename) && is_readable($filename) && ($fd = @fopen($filename, 'rb')) ) {
		$contents = '';
		while (!feof($fd)) {
			$contents .= fread($fd, 8192);
		}
		fclose($fd);
		return $contents;
	} else {
		return false;
	}
}


function fields_input($input=""){
	$_input = array("text"=>"单行文本",
			"multitext"=>"多行文本",
			"password"=>"密码类型",
			"htmltext"=>"HTML文本",
			"datetime"=>"时间类型",
			"image"=>"图片类型",
			"color"=>"颜色类型",
			"annex"=>"附件类型",
			"site"=>"站点栏目",
			"year"=>"年代选择",
			"select"=>"select下拉框",
			"checkbox"=>"checkbox多选框",
			"radio"=>"radio单选框");
	if ($input==""){
		return $_input;
	}else{
		return $_input['input'];
	}
}
function fields_type($type=""){
	$_type = array("varchar"=>"字符串[varchar]",
			"int"=>"数值型[int]",
			"text"=>"一般文本[text]",
			"mediumtext"=>"中型文本[mediumtext]",
			"longtext"=>"大型文本[longtext]");
	if ($type==""){
		return $_type;
	}else{
		return $_type['type'];
	}
}

function UpfileImage($data = array()){
	$error = "";

	//文件名
	$file = isset($data['file'])?$data['file']:"";
	if ($file=="") $error = -1;;

	$upfileDir = isset($data['upfile_dir'])?$data['upfile_dir']:"/data/upfiles/images/";//允许上传的文件类型
	$fileType = isset($data['upfile_type'])?$data['upfile_type']:array('jpg','gif','bmp','png');//上传图片
	$maxSize = isset($data['upfile_size'])?$data['upfile_size']:"300";//单位：KB
	$newDir = ROOT_PATH."/".$upfileDir;

	$cutWidth = isset($data['upfile_width'])?$data['upfile_width']:"300";//截图的宽
	$cutHeight = isset($data['upfile_height'])?$data['upfile_height']:"300";//截图的高
	$cutType = isset($data['cuttype'])?$data['cuttype']:"";//截图的类型
	$min_width = 10;//截图最小的宽度
	$min_height = 10;//截图最小的高度

	//判断是不是数组
	if (is_array($_FILES[$file]['name'])){
		$_result = array();
		foreach($_FILES[$file]['name'] as $i => $value){
				
			if ($value!=""){
				if ($_FILES[$file]['size'][$i]==0)	$error = -1;//文件不存在
				if(!in_array(strtolower(substr($_FILES[$file]['name'][$i],-3,3)),$fileType)) $error = -1;
				if(strpos($_FILES[$file]['type'][$i],'image')===false) $error = -1;
				if($_FILES[$file]['size'][$i] > $maxSize*1024) $error = -2;
				if($_FILES[$file]['error'][$i] !=0 ) $error = -3;
					
				//mkdirs($upfileDir,777);//创建文件夹

				$newFile = md5(time().rand(1,9)).$i.substr($_FILES[$file]['name'][$i],-4,4);//新文件名
				$oldFile = $_FILES[$file]['name'][$i];//旧文件名
				$allFile = $newDir.$newFile; //

				if(function_exists('move_uploaded_file')){
					$result = move_uploaded_file($_FILES[$file]['tmp_name'][$i],$allFile);
						
				}else{
					@copy($_FILES[$file]['tmp_name'][$i],$allFile);
				}

				/*是否截图 开始*/
				if ($cutType==1){
					/*获取图片的信息 开始*/
					$pic_info = @getimagesize($allFile);
					if($pic_info[0]<$min_width || $pic_info[1]<$min_heigth){
						$error = -4;
					}else{
						//获取图片要压缩的比例
						$re_scal = 1;
						if($pic_info[0]>$cutWidth){
							$re_scal = ($cutWidth / $pic_info[0]);
						}elseif($pic_info[1]>$cutHeight){
							$re_scal = ($cutHeight / $pic_info[1]);
						}

						if ($re_scal>0){
							$re_width = round($pic_info[0] * $re_scal);
							$re_height = round($pic_info[1] * $re_scal);
						}else{
							$re_width = $cutWidth;
							$re_height = $cutHeight;
						}

						/*创建空图象 开始*/
						$new_pic = @imagecreatetruecolor($re_width,$re_height);
						if(!$new_pic){
							$error = -4;
						}else{
							//复制图象
							if(function_exists("file_get_contents")){
								$src = file_get_contents($allFile);
							}else{
								$handle = fopen ($allFile, "r");
								while (!feof ($handle)){
									$src .= fgets($fd, 4096);
								}
								fclose ($handle);
							}
								
							/*输入文件 开始*/
							if(!empty($src)){
								$pic_creat = @ImageCreateFromString($src);
								if(!@imagecopyresampled($new_pic,$pic_creat,0,0,0,0,$re_width,$re_height,$pic_info[0],$pic_info[1])){
									$error = -5;
								}else{
									//输出文件
									$out_file = '';
									switch($pic_info['mime']){
										case 'image/jpeg':
											$out_file = @imagejpeg($new_pic,$allFile);
											break;
										case 'image/gif':
											$out_file = @imagegif($new_pic,$allFile);
											break;
										case 'image/png':
											$out_file = @imagepng($new_pic,$allFile);
											break;
										case 'image/wbmp':
											$out_file = @imagewbmp($new_pic,$allFile);
											break;
										default:
											$error = 6;
											break;
									}
								}
							}
							/*输入文件 结束*/
						}
						/*创建空图象 结束*/
					}
					/*获取图片的信息 开始*/
				}
				/*是否截图 结束*/
				if ($error==""){
					$_result[] = $upfileDir.$newFile;
				}
			}
		}

		return $_result;
	}
	else{


	}
}


/**
 * 上传图片
 *
 * @return Boolean
 */
function upload($file,$type="",$fileType="",$upfileDir="",$maxSize=""){

	if ($_FILES[$file]['size']==0)	return 0;
	if ($fileType=="")	$fileType=array('jpg','gif','bmp','png');//允许上传的文件类型
	if ($upfileDir=="")	$upfileDir='/data/upfiles/litpics/'; //上传图片
	if ($maxSize=="") $maxSize=300; //单位：KB

	if(!in_array(strtolower(substr($_FILES[$file]['name'],-3,3)),$fileType)) return -1;
	if(strpos($_FILES[$file]['type'],'image')===false) return -1;
	if($_FILES[$file]['size']> $maxSize*1024) return -2;
	if($_FILES[$file]['error'] !=0 ) return -3;

	$newDir = dirname(__FILE__)."/..".$upfileDir;

	$_upfileDir = explode("/",$upfileDir);
	foreach ($_upfileDir as $key => $value){
		if ($value!="")
			$fileDir[] = $value;
	}
	/*
	 foreach ($fileDir as $key => $value){
	$_fileDir = "";
	for($i=0;$i<=$key;$i++){
	$_fileDir .= $fileDir[$i]."/";
	}
	if (!is_dir("../".$_fileDir)){
	mkdir("../".$_fileDir,0777);
	}
	}
	*/
	$newFile=date('Ymd').time().substr($_FILES[$file]['name'],-4,4);
	$oldFile = $_FILES[$file]['name'];
	$allFile=$newDir.$newFile;

	if(function_exists('move_uploaded_file')){
		$result = move_uploaded_file($_FILES[$file]['tmp_name'],$allFile);
		return array($upfileDir.$newFile,$type,$newFile,$oldFile,$_FILES[$file]['size']);
	}else{
		@copy($_FILES[$file]['tmp_name'],$allFile);
		return array($upfileDir.$newFile,$type,$newFile,$oldFile,$_FILES[$file]['size']);
	}
}


/**
 * 检查权限
 *
 * @param Varchar $purview (表示此文件的所需权限，比如other_all)
 * @param Varchar $admin_purview (管理员的权限值)
 * @return Bollen
 */
function check_rank($purview){
	global $_G;
	$_admin_purview = empty($_SESSION['purview'])?"other_all":$_SESSION['purview'];
	$admin_purview = explode(",",$_admin_purview);
	$_purview = explode("_",$purview);
	if (in_array("other_all",$admin_purview) || $_G['user_result']['type_id']==1){
		return true;
	}else if (!in_array($purview,$admin_purview)){
		echo "<script>alert('你没有权限');history.go(-1);</script>";exit;
	}
}
function check_rank_bool($purview){
	global $_G;
	$_admin_purview = empty($_SESSION['purview'])?"other_all":$_SESSION['purview'];
	$admin_purview = explode(",",$_admin_purview);
	$_purview = explode("_",$purview);
	if (in_array("other_all",$admin_purview) || $_G['user_result']['type_id']==1){
		return true;
	}else if (!in_array($purview,$admin_purview)){
		return false;
	}
	return true;
}
function post_maketime($name){

	$var = array("year","month","date","hour","min");
	foreach ($var as $val){
		$$val = !isset($_POST[$name."_".$val])?"0":$_POST[$name."_".$val];
	}
	return mktime($hour,$min,0,$month,$date,$year);

}

function post_area($nid = ""){
	$pname = $nid."procvince";
	$cname = $nid."city";
	$aname = $nid."area";

	if (isset($_POST[$aname]) && $_POST[$aname]!=""){
		if ($_POST[$cname]==""){
			$area = $_POST[$pname];
		}else{
			$area = $_POST[$aname];
		}
	}else{
		if (isset($_POST[$cname]) && $_POST[$cname]!=""){
			$area = $_POST[$cname];
		}else{
			$area = isset($_POST[$pname])?$_POST[$pname]:"";
		}
	}
	return  $area;
}
function post_fields($fields){
	$_fields = "";
	if (is_array($fields)){
		foreach($fields as $key => $value){
			$_fields[$value['nid']] = empty($_POST[$value['nid']])?"":$_POST[$value['nid']];
		}
	}
	return  $_fields;
}
function post_var($var,$type=""){
	if ($type=="module"){
		$var = array("name","status","code","order","default_field","description","index_tpl","list_tpl","content_tpl","article_status","onlyone","visit_type","title_name","issent","version","author","type");

	}
	if (is_array($var)){
		foreach ($var as $key =>$val){
			$_val = (isset($_POST[$val]) && $_POST[$val]!="")?$_POST[$val]:"";
			if ($_val==""){
				$_val=NULL;
			}elseif (!is_array($_val) ){
				if ($val!="content"){
					$_val = nl2br($_val);
				}
			}else{
				$_val = join(",",$_val);
			}
			$result[$val] = $_val;
				
			if($val=="area"){//地区
				$result[$val] = post_area();
			}elseif($val=="flag"){//地区
				$result[$val] = !isset($_POST["flag"])?NULL:join(",",$_POST["flag"]);
			}elseif ($val=="clearlitpic"){
				if ($result["clearlitpic"]!="" && $result["clearlitpic"]==1){
					$result['litpic'] = NULL;
				}
				unset($result["clearlitpic"]);
			}elseif($val=="updatetime"){//地区
				$result[$val] = time();
			}elseif($val=="updateip"){//地区
				$result[$val] = ip_address();
			}
			/* elseif($_val == "content"){
				$result[$val] = htmlspecialchars($result[$val]);
			} */
			elseif($val=="content" || $val=="contentadmin"){
				$str = $_val;
				$str = preg_replace("/((<br \/>){2,})/i", "jspp2ppos", $str);
				$str = preg_replace("/((<br \/>){1,})/i", "", $str);
				$str = preg_replace("/((jspp2ppos){1,})/i", "<br />", $str);
				$result[$val] = $str;
			}
		}

		return $result;
	}else{
		return (!isset($_POST[$var]) || $_POST[$var]=="")?NULL:$_POST[$var];
	}
}

function  gdversion() {
	static   $gd_version_number  = null;
	if ( $gd_version_number  === null) {
		ob_start();
		phpinfo(8);
		$module_info  = ob_get_contents();
		ob_end_clean();
		if (preg_match( "/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i" ,  $module_info , $matches )) {
			$gdversion_h  =  $matches [1];
		}else {
			$gdversion_h  = 0;
		}
	}
	return  $gdversion_h;
}
function arc_page($total,$nowindex,$url){
	$result = "<div class='hycms_pages'><ul><li><a>共".$total."页: </a></li>";
	for($i=1;$i<=$total;$i++){
		if ($i == $nowindex){
			$result.= "</li><li class='thisclass'><a href='#'>$i</a></li>";
		}else{
			$result.= "<li><a href='".format_url("$url/$i")."'>$i</a></li>";
		}
	}

	$result.= "</ul></div>";
	return $result;

}
function get_mktime($mktime){
	if ($mktime=="") return "";
	$dtime = trim(ereg_replace("[ ]{1,}"," ",$mktime));
	$ds = explode(" ",$dtime);
	$ymd = explode("-",$ds[0]);
	if (isset($ds[1]) && $ds[1]!=""){
		$hms = explode(":",$ds[1]);
		$mt = mktime(empty($hms[0])?0:$hms[0],!isset($hms[1])?0:$hms[1],!isset($hms[2])?0:$hms[2],!isset($ymd[1])?0:$ymd[1],!isset($ymd[2])?0:$ymd[2],!isset($ymd[0])?0:$ymd[0]);
	}else{
		$mt = mktime(0,0,0,!isset($ymd[1])?0:$ymd[1],!isset($ymd[2])?0:$ymd[2],!isset($ymd[0])?0:$ymd[0]);
	}

	return $mt;
}

/**
 * 格式化路径
 */
function format_url($url,$type="",$isurl="",$tplname=""){
	global $system;
	if (is_array($isurl) && $isurl[0]==1){
		return "http://".str_replace("http://","",$isurl[1]);
	}
	if ($system['con_rewrite']==1){
		$url = str_replace("?","",$url);
		$_url = explode("/",$url);
		if (!isset($_url[1])){
			$reurl = "list_".$_url[0];
			if ($type!="sitelist" && isset($_REQUEST['page'])){
				$reurl .= "_".$_REQUEST['page'];
			}
		}else{
			$reurl = "content_".$_url[0]."_".$_url[1];
			if (isset($_url[2])){
				$reurl .= "_".$_url[2];
			}
		}
		$reurl .= ".html";
		return $reurl;
	}elseif ($system['con_rewrite']==2){


	}else{
		return $url;
	}


}

/**
 * 格式化路径
 */
function format_tpl($tpl,$var){
	if ($tpl=="") return "";
	if (isset($var['code'])){
		$tpl = str_replace("[code]",$var['code'],$tpl);
	}
	if (isset($var['site_id'])){
		$tpl = str_replace("[site_id]",$var['site_id'],$tpl);
	}
	if (isset($var['id'])){
		$tpl = str_replace("[id]",$var['id'],$tpl);
	}
	if (isset($var['nid'])){
		$tpl = str_replace("[nid]",$var['nid'],$tpl);
	}
	$page = !isset($_REQUEST['page'])?1:$_REQUEST['page'];
	$tpl = str_replace("[page]",$page,$tpl);

	return trim($tpl);

}
function get_ip_place(){
	$ip=file_get_contents("http://fw.qq.com/ipaddress");
	$ip=str_replace('"',' ',$ip);
	$ip2=explode("(",$ip);
	$a=substr($ip2[1],0,-2);
	$b=explode(",",$a);
	return $b;
}
function gbk2utf8($str){
	return iconv("GBK", "UTF-8", $str);
}


function maketime($name){

	$var = array("year","month","date","hour","min");
	foreach ($var as $val){
		$$val = !isset($_POST[$name."_".$val])?"0":$_POST[$name."_".$val];
	}
	return mktime($hour,$min,0,$month,$date,$year);

}

/**
 * 获取中文首个拼音字母
 * @param $input 中文字符 eg:中国
 */
function getCnFirstChar($input){

	$arr_input = array();
	$input = trim($input);
	$len = strlen($input);
	$str = '';
	for ($i = 0; $i < $len; $i++) {

		$str .= substr($input, $i, 1);
		if ($i % 2) {
			if ($str) {
				array_push($arr_input, $str);
			}
			$str = '';
		}
	}
	if(empty ($arr_input)) {
		return '';
	}

	$word = '';
	foreach ($arr_input as $input) {

		$code = '';
		$asc = ord(substr($input, 0, 1)) * 256 + ord(substr($input, 1, 1)) - 65536;
		if($asc>=-20319 and $asc<=-20284) $code = "A";
		if($asc>=-20283 and $asc<=-19776) $code = "B";
		if($asc>=-19775 and $asc<=-19219) $code = "C";
		if($asc>=-19218 and $asc<=-18711) $code = "D";
		if($asc>=-18710 and $asc<=-18527) $code = "E";
		if($asc>=-18526 and $asc<=-18240) $code = "F";
		if($asc>=-18239 and $asc<=-17923) $code = "G";
		if($asc>=-17922 and $asc<=-17418) $code = "H";
		if($asc>=-17417 and $asc<=-16475) $code = "J";
		if($asc>=-16474 and $asc<=-16213) $code = "K";
		if($asc>=-16212 and $asc<=-15641) $code = "L";
		if($asc>=-15640 and $asc<=-15166) $code = "M";
		if($asc>=-15165 and $asc<=-14923) $code = "N";
		if($asc>=-14922 and $asc<=-14915) $code = "O";
		if($asc>=-14914 and $asc<=-14631) $code = "P";
		if($asc>=-14630 and $asc<=-14150) $code = "Q";
		if($asc>=-14149 and $asc<=-14091) $code = "R";
		if($asc>=-14090 and $asc<=-13319) $code = "S";
		if($asc>=-13318 and $asc<=-12839) $code = "T";
		if($asc>=-12838 and $asc<=-12557) $code = "W";
		if($asc>=-12556 and $asc<=-11848) $code = "X";
		if($asc>=-11847 and $asc<=-11056) $code = "Y";
		if($asc>=-11055 and $asc<=-10247) $code = "Z";

		$word .= $code;
	}

	return strtoupper($word);
}

//导出excel格式表
function exportData($filename,$title,$data){
	header("Content-type: application/vnd.ms-excel");
	header("Content-disposition: attachment; filename="  . $filename . ".xls");
	if (is_array($title)){
		foreach ($title as $key => $value){
			echo $value."\t";
		}
	}
	echo "\n";
	if (is_array($data)){
		foreach ($data as $key => $value){
			foreach ($value as $_key => $_value){
				echo $_value."\t";
			}
			echo "\n";
		}
	}
}


/**
 * 获取属性的列表
 *
 * @param Array $fields_id
 * @param Array $order
 * @return Integer
 */
function getFlagName($data = array()){
	$result = $data['result'];
	$flag = $data['flag'];
	$_flag = "";
	if (is_array($result)){
		foreach($result as $key => $value){
			$flagres[$value['nid']] = $value['name'];
		}
		$flags = explode(",",$flag);
		foreach ($flags as $_key => $_value){
			if ($_value!=""){
				$_flag .= $flagres[$_value]." ";
			}
		}
	}
	return $_flag;
}



/**
 * 将ID转化为URL格式
 *
 * @param Integer $goods_id
 * @param String(eg:goods_vps/goods_hire) $goods_type
 * @return String
 */
function Key2Url($key,$type) {
	$encode=base64_encode ($type .$key );
	$encode_id = urlencode(authcode($encode.",".time(),"ENCODE"));
	return  $encode_id ;

}

/**
 * 将URL格式的字符串转化为ID
 *
 * @param String $str
 * @return Array(goods_type, goods_id)
 */
function Url2Key($key,$type) {
	$key = urldecode($key);
	$key = explode(",",authcode(trim($key),"DECODE"));
	$checktime=time()-(int)$key[1]; //有效期检查 60秒钟

	if($checktime>60){
		echo "time out!";
		exit;
	}

	$key = base64_decode ($key[0]);
	return explode ($type, $key );
}
/**
 * 加密激活链接
 * @param $id
 * @return String
 */
function EnActionCode ($id,$key) {

	return base64_encode((string)($id * 3 + $key));
}

/**
 * 解密激活链接
 * @param $id
 * @return String
 */
function DeActionCode ($id,$key) {
	return (base64_decode($id) - $key) / 3;
}

function RegEmailMsg($data = array()){
	global $mysql;
	$user_id = $data['user_id'];
	$username = $data['username'];
	$webname = $data['webname'];
	$email = $data['email'];
	$query_url = isset($data['query_url'])?$data['query_url']:"going/active";
	//$active_id = urlencode(authcode($user_id.",".time(),"ENCODE"));
	$active_id = email_ciphertext($user_id,'ENCODE',$data['encryption']);
	$_url = "http://{$_SERVER['HTTP_HOST']}/?user&q={$query_url}&keyid={$active_id}";
	$user_url = "http://{$_SERVER['HTTP_HOST']}";
	$send_email_msg = '


	<div style=" font-size:14px;  ">
	<div style="padding: 10px 0px;">

		<div style="padding: 2px 20px 30px;">
			<p>亲爱的 <span style="color: rgb(196, 0, 0);">'.$username.'</span> , 您好！</p>
			<p>感谢您注册'.$webname.'，请确认您的邮箱帐号为 <strong style="font-size: 16px;">'.$email.'</strong></p>
			<p>请点击下面的链接即可完成激活:</p>
			<p style="overflow: hidden; width: 100%; word-wrap: break-word;"><a title="点击完成注册" href="'.$_url.'" target="_blank" swaped="true">'.$_url.'</a>
			<br><span style="color: rgb(153, 153, 153);">(如果链接无法点击，请将它拷贝到浏览器的地址栏中)</span></p>
			<p style="text-align: right;"><br>'.$webname.'用户中心 敬启</p>
		</div>
		
	</div>
</div>
		';
	return $send_email_msg;

}

function getPaypwEmailMsg($data = array()){
	global $mysql;
	$user_id = $data['user_id'];
	$username = $data['username'];
	$webname = $data['webname'];
	$email = $data['email'];
	$query_url = isset($data['query_url'])?$data['query_url']:"going/active";
	//$active_id = urlencode(authcode($user_id.",".time(),"ENCODE"));
	$active_id = email_ciphertext($user_id,'ENCODE',$data['encryption']);
	$_url = "http://{$_SERVER['HTTP_HOST']}/?user&q={$query_url}&keyid={$active_id}";
	$user_url = "http://{$_SERVER['HTTP_HOST']}";
	$send_email_msg = '


	<div style=" font-size:14px;  ">
	<div style="padding: 10px 0px;">

	<div style="padding: 2px 20px 30px;">
	<p>亲爱的 <span style="color: rgb(196, 0, 0);">'.$username.'</span> , 您好！</p>
	<p>请点击下面的链接即可完成交易密码修改（您必须已经登录系统）:</p>
	<p style="overflow: hidden; width: 100%; word-wrap: break-word;"><a title="点击完成交易密码修改" href="'.$_url.'" target="_blank" swaped="true">'.$_url.'</a>
	<br><span style="color: rgb(153, 153, 153);">(如果链接无法点击，请将它拷贝到浏览器的地址栏中)</span></p>
	<p style="text-align: right;"><br>'.$webname.'用户中心 敬启</p>
	</div>

	</div>
	</div>
	';
	return $send_email_msg;

}

function GetpwdMsg($data = array()){
	global $mysql;
	$user_id = $data['user_id'];
	$username = $data['username'];
	$webname = $data['webname'];
	$email = $data['email'];
	//$active_id = urlencode(authcode($user_id.",".time(),"ENCODE"));
	$active_id = email_ciphertext($user_id,'ENCODE',$data['encryption']);
	$_url = "http://{$_SERVER['HTTP_HOST']}/?user&q=going/updatepwd&keyid={$active_id}";
	$user_url = "http://{$_SERVER['HTTP_HOST']}";
	$send_email_msg = '


	<div style="font-size:14px; ">
	<div style="padding: 10px 0px;">

	<div style="padding: 2px 20px 30px;">
	<p>亲爱的 <span style="color: rgb(196, 0, 0);">'.$username.'</span> , 您好！</p>
	<p>请点击下面的链接重新修改密码。</p>
	<p style="overflow: hidden; width: 100%; word-wrap: break-word;"><a title="点击完成注册" href="'.$_url.'" target="_blank" swaped="true">'.$_url.'</a>
	<br><span style="color: rgb(153, 153, 153);">(如果链接无法点击，请将它拷贝到浏览器的地址栏中)</span></p>
		
	<p style="text-align: right;"><br>'.$webname.'用户中心 敬启</p>
	<p><br>此为自动发送邮件，请勿直接回复！如您有任何疑问，请点击<a title="点击联系我们" style="color: rgb(15, 136, 221);" href="http://'.$_SERVER['HTTP_HOST'].'/help/index.html" target="_blank" >联系我们</a></p>
	</div>
	</div>
	</div>
	';
	return $send_email_msg;

}
/*
 * 获取客户端返回状态码
* by:timest 2012-09-21
*/
function gethttpstatus($url){
	$fp = fsockopen("storage.aliyun.com", 80, $errno, $errstr, 30);
	if (!$fp) {
		return false;
	}else{
		$out = "GET $url HTTP/1.1\r\n";
		$out .= "Host: storage.aliyun.com\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite($fp, $out);
		list($status, $code, $ok) = split(" ", fgets($fp,100));
		fclose($fp);
		if($code == '200'){
			return true;
		}else{
			return false;
		}
	}
}



//或得用户的头像
function get_avatar($data = array()) {
	$uid = isset($data['user_id'])?$data['user_id']:"";
	$size = isset($data['size'])?$data['size']:"big";
	$type = isset($data['type'])?$data['type']:"";

	$istrue = isset($data['istrue'])?$data['istrue']:false;
	$size = in_array($size, array('big', 'middle', 'small')) ? $size : 'big';
	$uid = abs(intval($uid));

	$typeadd = $type == 'real' ? '_real' : '';
	//$domain = gethttpstatus('http://storage.aliyun.com/wzdoss/'+ 'data/avatar/'.$uid.$typeadd."_avatar_$size.jpg") ;
	//list($status, $code, $res) = split(' ',$domain[0]);
	//if ($code=='200'){
	if(file_exists('/data/avatar/'.$uid.$typeadd."_avatar_$size.jpg")) {

		//if ($istrue) return true;
		return '/data/avatar/'.$uid.$typeadd."_avatar_$size.jpg";
	}else{
		if ($istrue) return false;
		return "/data/images/noavatar_{$size}.gif";
	}
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
	global $rdGlobal;
	// 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
	$ckey_length = 4;
	// 密匙
	$key = md5($key ? $key : $rdGlobal['uc_key'] );
	// 密匙a会参与加解密
	$keya = md5(substr($key, 0, 16));
	// 密匙b会用来做数据完整性验证
	$keyb = md5(substr($key, 16, 16));
	// 密匙c用于变化生成的密文
	$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	// 参与运算的密匙
	$cryptkey = $keya.md5($keya.$keyc);
	$key_length = strlen($cryptkey);
	// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
	// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
	$string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
	$string_length = strlen($string);
	$result = '';
	$box = range(0, 255);
	$rndkey = array();

	// 产生密匙簿
	for($i = 0; $i <= 255; $i++) {
		$rndkey[$i] = ord($cryptkey[$i % $key_length]);
	}

	// 用固定的算法，打乱密匙簿，增加随机性，好像很复杂，实际上对并不会增加密文的强度
	for($j = $i = 0; $i < 256; $i++) {
		$j = ($j + $box[$i] + $rndkey[$i]) % 256;
		$tmp = $box[$i];
		$box[$i] = $box[$j];
		$box[$j] = $tmp;
	}

	// 核心加解密部分
	for($a = $j = $i = 0; $i < $string_length; $i++) {
		$a = ($a + 1) % 256;
		$j = ($j + $box[$a]) % 256;
		$tmp = $box[$a];
		$box[$a] = $box[$j];
		$box[$j] = $tmp;
		// 从密匙簿得出密匙进行异或，再转成字符
		$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
	}


	if($operation == 'DECODE') {
		// substr($result, 0, 10) == 0 验证数据有效性
		// substr($result, 0, 10) - time() > 0 验证数据有效性
		// substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16) 验证数据完整性
		// 验证数据有效性，请看未加密明文的格式
		if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
			return substr($result, 26);
		} else {
			return '';
		}
	} else {
		// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
		// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
		return $keyc.str_replace('=', '', base64_encode($result));
	}
}



function show_pages($data = array()){
	$total= (int)$data['total'];
	$page= (int)$data['page'];
	$epage= (int)$data['epage'];
	if ($total==0){
		return "没有信息";
	}
	if($total % $epage){
		$page_num=(int)($total / $epage)+1;
	}
	else {
		$page_num=$total / $epage;
	}
	//判断有多少页
	if($page==""){
		$page=1;
	}
	$action = strstr($_SERVER['REQUEST_URI'],"?");

	/* 	$a = explode("&",$action);

	$vs=false;
	foreach($a as $key=>$v){
	if(is_numeric(strpos($v,"type"))) $vs=$v;
	}

	if($vs){
	$action = (is_numeric(strpos($vs,"?")))?$vs:"?".$vs;
	}else{
	$action="";
	} */


	$first_url  = "index.html".$action;
	$up_url  = "index".($page-1).".html".$action;
	$last_url  = "index".$page_num.".html".$action;
	$down_url  = "index".($page+1).".html".$action;

	if ($page!=1 && $page>$page_num){
		header("location:index{$page_num}.html");
	}

	$display = "<b>共".$total."条</b>";

	$display .= " {$epage}条/页<span class='page_line'>|</span>第{$page}/{$page_num}页";

	/* 	//第一页
	 if($page==1){
	$display .= ' <span class="no_page"><font face="webdings" title="第一页">第一页</font></span>';
	}else{
	$display .= " <a href='{$first_url}'><font face='webdings' title='第一页'>第一页</font></a>";
	}

	//上一页
	if($page==1){
	$display .= ' <span class="no_page"><font face="webdings" title="上一页">上一页</font></span>';
	}else{
	$display .= " <a href='{$up_url}'><font face='webdings' title='上一页'>上一页</font></a>";
	}
	*/
	if ($page_num>5){
		if ($page<3){
			$j = 1;
			$n = 5;
		}else{
			if($page +2>$page_num){
				$j = $page_num-4;
				if ($j<=0) $j=1;
				$n = $page_num;
			}else{
				$j = $page-2;
				if ($j<=0) $j=1;
				$n =  $page+2;
			}
		}
	}else{
		$j = $page-2;
		if ($j<=0) $j=1;
		$n =  $page+2;
		if ($n>$page_num) $n=$page_num;
	}

	for($i=$j;$i<=$n;$i++){
		if($i==$page){
			$display .= " <span class='this_page'>{$i}</span>";
		}else{
			$display .= " <a href='index{$i}.html{$action}'>$i</a>";
		}
	}


	/* 	//下一页
	 if($page==$page_num){
	$display .= ' <span class="no_page"><font face="webdings" title="下一页">下一页</font></span>';
	}else{
	$display .= " <a href='{$down_url}'><font face='webdings' title='下一页'>下一页</font></a>";
	}

	//最后一页
	if($page==$page_num){
	$display .= ' <span class="no_page"><font face="webdings" title="最后一页">最后一页</font></span>';
	}else{
	$display .= " <a href='{$last_url}'><font face='webdings' title='最后一页'>最后一页</font></a>";
	} */
	$display .=' <span class="page_go">转到<input type="text" id="page_text" size="4" onkeydown="if (event.keyCode==13){location.href =\'index\'+this.value+\'.html'.$action.'\'}"  value="'.$page.'"  onfocus="this.select()" />页</span>';
	$display .='<input type="button" value="确定" onclick="var pageId;pageId=document.getElementById(\'page_text\').value;location.href =\'index\'+pageId+\'.html'.$action.'\';">';

	return $display;
}


function nltobr($string = ""){
	if($string=="") return "";
	$string = str_replace(" ","&nbsp;",$string);
	$string = nl2br($string);
	return $string;
}


//去掉相应的参数
function url_format($url, $format = ''){
	if ($url=="") return "?";
	$_url =  explode("?",$url);
	$_url_for = "";
	if (isset($_url[1]) && $_url[1]!=""){
		$request = $_url[1];
		if ($request != ""){
			$_request = explode("&",$request);
			foreach ($_request as $key => $value){
				$_value = explode("=",$value);
				if (trim($_value[0])!=$format){
					$_url_for .="&" .$value;
				}
			}
		}
		$_url_for = substr($_url_for, 1,strlen($_url_for));
	}

	return "?".$_url_for;
}

//获得时间天数
function get_times($data=array()){
	if (isset($data['time']) && $data['time']!=""){
		$time = $data['time'];//时间
	}elseif (isset($data['date']) && $data['date']!=""){
		$time = strtotime($data['date']);//日期
	}else{
		$time = time();//现在时间
	}
	if (isset($data['type']) && $data['type']!=""){
		$type = $data['type'];//时间转换类型，有day week month year
	}else{
		$type = "month";
	}
	if (isset($data['num']) && $data['num']!=""){
		$num = $data['num'];
	}else{
		$num = 1;
	}

	if ($type=="month"){
		$month = date("m",$time);
		$year = date("Y",$time);
		$_result = strtotime("$num month",$time);
		$_month = (int)date("m",$_result);
		if ($month+$num>12){
			
			if(($month+$num)-12*(floor (($month+$num)/12))==0){
				$_num = ($month+$num)-12*((floor (($month+$num)/12))-1);
				$year = $year+((floor (($month+$num)/12))-1);
			}else{
				$_num = ($month+$num)-12*(floor (($month+$num)/12));
				$year = $year+(floor (($month+$num)/12));
			}
			
		}else{
			$_num = $month+$num;
		}

		if ($_num!=$_month){

			$_result = strtotime("-1 day",strtotime("{$year}-{$_month}-01"));
		}
	}else{
		$_result = strtotime("$num $type",$time);
	}
	if (isset($data['format']) && $data['format']!=""){
		return date($data['format'],$_result);
	}else{
		return $_result;
	}

}

//编码格式转换
function diconv($str, $in_charset, $out_charset = CHARSET, $ForceTable = FALSE) {
	global $_G;

	$in_charset = strtoupper($in_charset);
	$out_charset = strtoupper($out_charset);
	if($in_charset != $out_charset) {
		require_once ROOT_PATH.'core/chinese.class.php';
		$chinese = new Chinese($in_charset, $out_charset, $ForceTable);
		$strnew = $chinese->Convert($str);
		if(!$ForceTable && !$strnew && $str) {
			$chinese = new Chinese($in_charset, $out_charset, 1);
			$strnew = $chinese->Convert($str);
		}
		return $strnew;
	} else {
		return $str;
	}
}

/*function isIdCard($number) {



//加权因子
$wi = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
//校验码串
$ai = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
//按顺序循环处理前17位
for ($i = 0;$i < 17;$i++) {
//提取前17位的其中一位，并将变量类型转为实数
$b = (int) $number{$i};

//提取相应的加权因子
$w = $wi[$i];

//把从身份证号码中提取的一位数字和加权因子相乘，并累加
$sigma += $b * $w;
}
//计算序号
$snumber = $sigma % 11;

//按照序号从校验码串中提取相应的字符。
$check_number = $ai[$snumber];

if ($number{17} == $check_number) {
return true;
} else {
return false;
}
}
*/
 # CopyRight: zxing
 # Document: 检查符合 GB11643-1999 标准的身份证号码的正确性
 # File:gb11643_1999.func.php Fri Mar 28 09:42:41 CST 2008 zxing
 # Updated:Fri Mar 28 09:42:41 CST 2008
 # Note: 调用函数 check_id();


 # 函数功能：计算身份证号码中的检校码
 # 函数名称：idcard_verify_number
 # 参数表 ：string $idcard_base 身份证号码的前十七位
 # 返回值 ：string 检校码
 # 更新时间：Fri Mar 28 09:50:19 CST 2008

function idcard_verify_number($idcard_base){
	if (strlen($idcard_base) != 17){
		return false;
	}
	$factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2); //debug 加权因子
	$verify_number_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2'); //debug 校验码对应值
	$checksum = 0;
	for ($i = 0; $i < strlen($idcard_base); $i++){
		$checksum += substr($idcard_base, $i, 1) * $factor[$i];
	}
	$mod = $checksum % 11;
	$verify_number = $verify_number_list[$mod];
	return $verify_number;
}
 # 函数功能：将15位身份证升级到18位
 # 函数名称：idcard_15to18
 # 参数表 ：string $idcard 十五位身份证号码
 # 返回值 ：string
 # 更新时间：Fri Mar 28 09:49:13 CST 2008
function idcard_15to18($idcard){
	if (strlen($idcard) != 15){
		return false;
	}else{// 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
		if (array_search(substr($idcard, 12, 3), array('996', '997', '998', '999')) !== false){
			$idcard = substr($idcard, 0, 6) . '18'. substr($idcard, 6, 9);
		}else{
			$idcard = substr($idcard, 0, 6) . '19'. substr($idcard, 6, 9);
		}
	}
	$idcard = $idcard . idcard_verify_number($idcard);
	return $idcard;
}
 # 函数功能：18位身份证校验码有效性检查
 # 函数名称：idcard_checksum18
 # 参数表 ：string $idcard 十八位身份证号码
 # 返回值 ：bool
 # 更新时间：Fri Mar 28 09:48:36 CST 2008
function idcard_checksum18($idcard){
	if (strlen($idcard) != 18){
		return false;
	}
	$idcard_base = substr($idcard, 0, 17);
	if (idcard_verify_number($idcard_base) != strtoupper(substr($idcard, 17, 1))){
		return false;
	}else{
		return true;
	}
}
 # 函数功能：身份证号码检查接口函数
 # 函数名称：check_id
 # 参数表 ：string $idcard 身份证号码
 # 返回值 ：bool 是否正确
 # 更新时间：Fri Mar 28 09:47:43 CST 2008
function isIdCard($idcard) {
	if(strlen($idcard) == 15 || strlen($idcard) == 18){
		if(strlen($idcard) == 15){
			$idcard = idcard_15to18($idcard);
		}
		if(idcard_checksum18($idcard)){
			return true;
		}else{
			return false;
		}
	}else{
		return false;
	}
}


/*
 *@ Date         2010.10.31
*@ Author       lzhhand
*@ 短信接口处理
$sms_type  em,normal
*/
function sendSMS_xxx($userid,$content,$system=0)
{
	global $_G,$mysql;
	require_once ROOT_PATH . 'core/user.class.php';
	if($userid<=0){
		return false;
	}
	if ($_G['system']['con_issms']==1)
	{
		if ($system==0)
		{
			$_results = userClass::GetSmsOnes(array("userid"=>$userid));
			if ($_results)
			{
				$time1=strtotime($_results["end_time"]);
				$time2=time();
				if (DateDiff("d",$time2,$time1)<0)
				{
					return false;
				}
				else
				{
					$mobile=$_results["mobile"];
				}
			}
		}
		else
		{
			$_results = userClass::GetOnes(array("user_id"=>$userid));
			//print_r($_results);
			if ($_results)
			{
				$mobile=$_results["phone"];
			}
		}
		//print_r($mobile);
		$http = $_G['system']['con_smsurl'];
		$data = array
		(
				'username'=>$_G['system']['con_smsusername'],
				'password'=>$_G['system']['con_smspassword'],
				'mobile'=>$mobile,
				'content'=>$content
				//'time'=>$time,
				//'mid'=>$mid
		);
		$sql="insert into {sms_log}(userid,msgcontent,mobile,spnumber,addtime) values(".$userid.",'".$content."','".$mobile."','1069800000091717',now())";
		$mysql->db_query($sql);
		$myid=$mysql->db_insert_id();
		$re= sysendsms($data);
		// 		$re= getSend($http,$data);			//POST方式提交
		//$re= getSend($http,$data);				//GET方式提交
		if( trim($re) == '1' )
		{
			$sql="update {sms_log} set issend=1,reportmsg='".$re."',updatetime=now() where id=".$myid;
			$mysql->db_query($sql);
			return true;
		}
		else
		{
			$sql="update {sms_log} set issend=2,reportmsg='".$re."',updatetime=now() where id=".$myid;
			$mysql->db_query($sql);
			return false;
		}
	}
}

function sendSMS_xx($userid,$content,$system=0)
{
	global $_G,$mysql;
	require_once ROOT_PATH . 'core/user.class.php';
	if($userid<=0){
		return false;
	}
	if ($_G['system']['con_issms']==1)
	{
		if ($system==0)
		{
			$_results = userClass::GetSmsOnes(array("userid"=>$userid));
			if ($_results)
			{
				$time1=strtotime($_results["end_time"]);
				$time2=time();
				if (DateDiff("d",$time2,$time1)<0)
				{
					return false;
				}
				else
				{
					$mobile=$_results["mobile"];
				}
			}
		}
		else
		{
			$_results = userClass::GetOnes(array("user_id"=>$userid));
			//print_r($_results);
			if ($_results)
			{
				$mobile=$_results["phone"];
			}
		}
		//print_r($mobile);
		$http = $_G['system']['con_smsurl'];
		$data = array
		(
				'zh'=>$_G['system']['con_smsusername'],
				'mm'=>$_G['system']['con_smspassword'],
				'hm'=>$mobile,
				'nr'=>$content."【{$_G['system']['con_webname']}】"
				//'time'=>$time,
				//'mid'=>$mid
		);
		$sql="insert into {sms_log}(userid,msgcontent,mobile,spnumber,addtime) values(".$userid.",'".$content."','".$mobile."','1069800000091717',now())";
		$mysql->db_query($sql);
		$myid=$mysql->db_insert_id();
		$re= getSend($http,$data);				//GET方式提交
		if( trim($re) == '0' )
		{
			$sql="update {sms_log} set issend=1,reportmsg='".$re."',updatetime=now() where id=".$myid;
			$mysql->db_query($sql);
			//return true;
		}
		else
		{
			$sql="update {sms_log} set issend=2,reportmsg='".$re."',updatetime=now() where id=".$myid;
			$mysql->db_query($sql);
			//return false;
		}
		return true;
	}
}

function sendSMS($userid,$content,$system=0,$phone="")
{
	global $_G,$mysql;
	require_once ROOT_PATH . 'core/user.class.php';
	if($userid<=0 && $phone==''){
		return false;
	}
	if ($_G['system']['con_issms']==1)
	{
		if($phone!=""){
			$mobile = $phone;
		}else{
			if ($system==0)
			{
				return false;
				$_results = userClass::GetSmsOnes(array("userid"=>$userid));
				if ($_results)
				{
					$time1=strtotime($_results["end_time"]);
					$time2=time();
					if (DateDiff("d",$time2,$time1)<0)
					{
						return false;
					}
					else
					{
						$mobile=$_results["mobile"];
					}
				}
			}
			else
			{
				$_results = userClass::GetOnes(array("user_id"=>$userid));
				if ($_results)
				{
					$mobile=$_results["phone"];
					if($mobile=='') return false;
				}
			}
		}
		$http = $_G['system']['con_smsurl'];
		$data = array
		(
				'username'=>$_G['system']['con_smsusername'],
				'password'=>$_G['system']['con_smspassword'],
				'mobile'=>$mobile,
				'content'=>$content
		);
		$sql="insert into {sms_log}(userid,msgcontent,mobile,spnumber,addtime) values(".$userid.",'".$content."','".$mobile."','1069800000091717',now())";
		$mysql->db_query($sql);
		$myid=$mysql->db_insert_id();

		$iRet = sendsms_ems($url,$data);

		if( $iRet==1 )
		{
			$sql="update {sms_log} set issend=1,reportmsg='".$iRet."',updatetime=now() where id=".$myid;
			$mysql->db_query($sql);
			return true;
		}
		else
		{
			$sql="update {sms_log} set issend=2,reportmsg='".$iRet."',updatetime=now() where id=".$myid;
			$mysql->db_query($sql);
			return false;
		}
	}
}


function postSend($url,$param){

	$ch = curl_init();
	curl_setopt($ch,CURLOPT_URL,$url);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$param);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}
/*
 * 新讯息rukk
 */
function getSend($url,$data)
{
	while (list($k,$v) = each($data))
	{
		$param .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
	}
	$ch = curl_init($url."&".$param);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 4000);
	$output = curl_exec($ch);
	if (curl_errno($ch)) {
		echo '<pre><b>错误:</b><br />('.$url."?".$param.')'.curl_error($ch);
	}
	curl_close($ch);
	return $output;
}
function getSend_xx($url,$data)
{
	while (list($k,$v) = each($data))
	{
		$param .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
	}
	echo $url."?".$param;
	$ch = curl_init($url."?".$param);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true) ;
	curl_setopt($ch, CURLOPT_TIMEOUT_MS, 4000);
	$output = curl_exec($ch);
	if (curl_errno($ch)) {
		echo '<pre><b>错误:</b><br />('.$url."?".$param.')'.curl_error($ch);
	}
	curl_close($ch);
	return $output;
}


function DateDiff($interval, $date1, $date2) {

	// @See: It gets the number of the seconds in the one of the 2nd period day interval.
	$time_difference = $date2 - $date1;
	switch ($interval) {
		 
		case "w": $retval = bcdiv($time_difference, 604800); break;
		case "d": $retval = bcdiv($time_difference, 86400);   break;
		case "h": $retval = bcdiv($time_difference, 3600);    break;
		case "n": $retval = bcdiv($time_difference, 60);      break;
		case "s": $retval = $time_difference;                 break;
	}

	return $retval;

}

function sysendsms_xxx($data = array()){
	global $mysql, $_G;
	require_once (ROOT_PATH."core/HttpClient.class.php");

	$con_sms_server = isset($_G['system']['con_sms_server'])?$_G['system']['con_sms_server']:"";
	$con_sms_corpid = isset($_G['system']['con_sms_corpid'])?$_G['system']['con_sms_corpid']:"";
	$con_sms_loginname = isset($_G['system']['con_sms_loginname'])?$_G['system']['con_sms_loginname']:"";
	$con_sms_passwd = isset($_G['system']['con_sms_passwd'])?$_G['system']['con_sms_passwd']:"";

	if ($con_sms_server =="" || $con_sms_corpid=="" || $con_sms_loginname ==""|| $con_sms_passwd ==""){
		return 2;
	}
	$srv_ip = "sms.mobset.com";//你的目标服务地址.
	$srv_port = 80;//端口
	$url = "http://web.mobset.com/SDK/Sms_Send.asp?"; //接收你post的URL具体地址
	$fp = '';
	$errno = 0;//错误处理
	$errstr = '';//错误处理
	$timeout = 10;//多久没有连上就中断
	//TODO 请求mall信息时，uc_user_id需要加密


	$post_str = "CorpID=".$con_sms_corpid."&LoginName=".$con_sms_loginname."&Passwd=".$con_sms_passwd."&send_no=".$data['mobile']."&Timer=".$data['Timer']. "&msg=" .rawurlencode($data['content']);//要提交的内容.
	//打开网络的 Socket 链接。
	$fp = fsockopen($srv_ip,$srv_port,$errno,$errstr,$timeout);
	if (!$fp){
		echo('fp fail');
	}
	$content_length = strlen($post_str);
	$post_header = "POST $url HTTP/1.1\r\n";
	$post_header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$post_header .= "User-Agent: MSIE\r\n";
	$post_header .= "Host: ".$srv_ip."\r\n";
	$post_header .= "Content-Length: ".$content_length."\r\n";
	$post_header .= "Connection: close\r\n\r\n";
	$post_header .= $post_str."\r\n\r\n";
	fwrite($fp,$post_header);

	$inheader = 1;
	while(!feof($fp)){//测试文件指针是否到了文件结束的位置
		$line = fgets($fp,1024);
		//去掉请求包的头信息
		if ($inheader && ($line == "\n" || $line == "\r\n")) {
			$inheader = 0;
		}
		if ($inheader == 0) {
			//echo $line;
		}
	}
	fclose($fp);

	$Ret=strtok($line,",");
	$iRet=intval($Ret);

	if ($iRet>0) {
		return 1;
	}
	else {
		return 2;
	}


}

function email_ciphertext($m,$type,$key=1){
	$yz = base64_encode('wwwsjspphps%#com');
	$yz_l = strlen($yz);
	for ($i=0; $i<$yz_l; $i+=2){
		$arr[$i*2+2] = substr($yz, $i, 2);
	}
	if($type=='ENCODE'){
		$start = time();
		$end = time()+3600;
		$m = base64_encode($m.','.$start.','.$end.','.md5($start.$end).','.$key);
		foreach ($arr as $k=>$v){
			$m = substr($m, $k).$v.substr($m, 0, $k);
		}
		$m = str_replace('=','!*!',$m);
		return $m;
	}else if($type=='DECODE'){
		$m = str_replace('!*!','=',$m);
		$arr = array_reverse($arr, true);
		foreach ($arr as $k=>$v){
			$m = substr($m, strlen($m)-$k).substr($m, 0, strlen($m)-$k-strlen($v));
		}
		$j = explode(',',base64_decode($m));
		if(count($j)!=5) return -2;//被修改
		if($j[2]>time()){
			if(md5($j[1].$j[2]) == $j[3]){
				return $j;
			}else{
				return -2;//被修改
			}
		}else{
			return -1;//过期
		}
	}
}
//检测邮箱是否符合规则
function is_email($email){
	return strlen($email) > 6 && preg_match("/^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(.[a-zA-Z0-9_-])+/",$email);
	//return strlen($email) > 6 && preg_match("/^[\w\-\.]+@[\w\-]+(\.\w+)+$/", $email);
}
function sendsms_ems($url,$data){
	global $_G;
	
	$data['content'] = iconv("GBK", "UTF-8", $data['content']);
	$curlPost = "enterpriseID=".$_G['system']['con_smsuserid']."&loginName=".$_G['system']['con_smsusername']."&password=".$_G['system']['con_smspassword']."&mobiles={$data['mobile']}&content=".rawurlencode($data['content']);
	//$curlPost = "account={$data['username']}&password={$data['password']}&mobile={$data['mobile']}&content=".rawurlencode($data['content']);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $_G['system']['con_smsurl']);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
	curl_setopt($curl, CURLOPT_TIMEOUT_MS, 10000);
	$return_str = curl_exec($curl);
	curl_close($curl);
	$gets = iconv("UTF-8","GBK",$return_str);
	if($gets && strpos($gets,"<Result>0</Result>")!==false){
		return 1;
	}else{
		return $gets;
	}
}
function sendsms_hrtz($url,$data){
	$data['content'] = iconv("GBK", "UTF-8", $data['content']);
	$curlPost = "account=cf_jxd&password=897Y7R&mobile={$data['mobile']}&content=".rawurlencode($data['content']);
	//$curlPost = "account={$data['username']}&password={$data['password']}&mobile={$data['mobile']}&content=".rawurlencode($data['content']);
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "http://106.ihuyi.com/webservice/sms.php?method=Submit");
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
	curl_setopt($curl, CURLOPT_TIMEOUT_MS, 10000);
	$return_str = curl_exec($curl);
	curl_close($curl);
	$gets = iconv("UTF-8","GBK",$return_str);
	if($gets && strpos($gets,"<code>2</code>")!==false){
		return 1;
	}else{
		return $gets;
	}
}
?>
