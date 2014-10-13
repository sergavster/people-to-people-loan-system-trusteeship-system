<?
/**
 *  基本图片处理，用于完成图片缩入，水印添加
 *  当水印图超过目标图片尺寸时，水印图能自动适应目标图片而缩小
 *  水印图可以设置跟背景的合并度
 *
 *  Copyright(c) 2010 by dwcms. All rights reserved
 *
*/

class upload
{
	var $bucket = 'wzdoss'; //阿里云OSS 的bucket
	var $oss_used = 1;  //是否使用OSS服务
    var $dst_img;// 目标文件
    var $h_src; // 图片资源句柄
    var $h_dst;// 新图句柄
    var $h_mask;// 水印句柄
    var $img_create_quality = 100;// 图片生成质量
    var $img_display_quality = 100;// 图片显示质量,默认为75
    var $img_scale = 0;// 图片缩放比例
    var $dst_w = 0;// 新图总宽度
    var $dst_h = 0;// 新图总高度
    var $fill_w;// 填充图形宽
    var $fill_h;// 填充图形高
    var $copy_w;// 拷贝图形宽
    var $copy_h;// 拷贝图形高
    var $src_x = 0;// 原图绘制起始横坐标
    var $src_y = 0;// 原图绘制起始纵坐标
    var $start_x;// 新图绘制起始横坐标
    var $start_y;// 新图绘制起始纵坐标
    var $mask_word;// 水印文字
    var $mask_img;// 水印图片
    var $mask_pos_x = 0;// 水印横坐标
    var $mask_pos_y = 0;// 水印纵坐标
    var $mask_offset_x = 5;// 水印横向偏移
    var $mask_offset_y = 5;// 水印纵向偏移
    var $font_w;// 水印字体宽
    var $font_h;// 水印字体高
    var $mask_w;// 水印宽
    var $mask_h;// 水印高
    var $mask_font_color = "#ffffff";// 水印文字颜色
    var $mask_font = 2;// 水印字体
    var $font_size;// 尺寸
    var $mask_position = 0;// 水印位置
    var $mask_img_pct = 50;// 图片合并程度,值越大，合并程序越低
    var $mask_txt_pct = 50;// 文字合并程度,值越小，合并程序越低
    var $img_border_size = 0;// 图片边框尺寸
    var $img_border_color;// 图片边框颜色
    var $_flip_x=0;// 水平翻转次数
    var $_flip_y=0;// 垂直翻转次数
	var $pic_w;// 上传图片的宽度
    var $pic_h;// 上传图片的高度
    var $file_upname;// 上传图片的高度
	var $src_w = "";
	var $src_h = "";
	var $file_name="";// 上传图片名称
	var $file_maxsize="300";// 上传图片最大的大小
	var $file_dir="data/upfiles";// 上传的文件夹
	var $file_type = array('jpg','gif','bmp','png');//上传图片
	var $file_quality = "100";//输出质量
	
	var $cut_width="";// 截图宽度
	var $cut_height="";//截图高度
	var $cut_scale="50";//截图比例
	var $cut_type = "";//截图类型

    var $img_type;// 文件类型
    // 文件类型定义,并指出了输出图片的函数
    var $all_type = array(
        "jpg"  => array("output"=>"imagejpeg"),
        "gif"  => array("output"=>"imagegif"),
        "png"  => array("output"=>"imagepng"),
        "wbmp" => array("output"=>"image2wbmp"),
        "jpeg" => array("output"=>"imagejpeg"));

	 /**
     * 构造函数
     */
    function upload() {
        $this->mask_font_color = "#ffffff";
        $this->font = 2;
        $this->font_size = 12;
    }
	

	
	//设置相关的数据
	function setData($data = array()){
		if (isset($data['file_newname']) && $data['file_newname']!=""){
			$this->setDstImg($data['file_newname']);//设置新的图片
		}
		$this->file_dir = isset($data['file_dir'])?$data['file_dir']:"data/upfiles/images/";//设置图片上传的路径
		$this->file_dir .= date("Y-m",time())."/".date("d",time())."/";
		$this->file_type = isset($data['file_type'])?$data['file_type']:array('jpg','gif','bmp','png');//最大的图片
		$this->file_size = isset($data['file_size'])?$data['file_size']:"2048";//最大的图片
		$this->file_quality = isset($data['file_quality'])?$data['file_quality']:"80";//最大的图片
		$this->file_newname = (isset($data['user_id'])?$data['user_id']:"0")."_".(isset($data['code'])?$data['code']:"system")."_".time().rand(0,9);//新文件名
		$this->cut_status = isset($data['cut_status'])?$data['cut_status']:"";//截图类型
		if ($this->cut_status==1){
			$this->cut_type = isset($data['cut_type'])?$data['cut_type']:"";//截图类型
			$this->cut_scale = isset($data['cut_scale'])?$data['cut_scale']:"";//图片缩放比例
			$this->cut_width = isset($data['cut_width'])?$data['cut_width']:"";//截图的宽度
			$this->cut_height = isset($data['cut_height'])?$data['cut_height']:"";//截图的宽度
			$this->cut_quality = isset($data['cut_quality'])?$data['cut_quality']:"";//图片的质量
		}
		
		$this->mask_status = isset($data['mask_status'])?$data['mask_status']:"";//截图类型
		if ($this->mask_status==1){
			$this->mask_word = isset($data['mask_word'])?$data['mask_word']:"";//水印的文字
			$this->mask_font_color = isset($data['mask_color'])?$data['mask_color']:"#ffffff";//水印的文字颜色
			$this->font_size = isset($data['mask_size'])?$data['mask_size']:"";//水印的大小
			$this->font = isset($data['mask_font'])?$data['mask_font']:"";//水印的字体
			$this->mask_img = isset($data['mask_img'])?$data['mask_img']:"";//水印的图片
			$this->mask_position = isset($data['mask_position'])?$data['mask_position']:"1";//水印的位置
		}
	}
	
	/*
	 *$error:-1:文件名不存在，-2：文件不存在,-3:图片类型不正确，-4:不是图片类型，-5:图片尺寸过大
	
	*/
	
	function upfile($data = array()){
		global $mysql;
		$error = "";
		if (!isset($data['file'])) return "";//
		$filename = isset($data['filename'])?$data['filename']:"";
		$file = $data['file'];
		if(!isset($_FILES[$file]['name'])) return "";
		
		//$dateFile = date("Y-m",time());
		$this->setData($data);
		$newDir = ROOT_PATH.$this->file_dir;
		//print_r("new_dir" . $newDir);  //result: new_dir/data/htdocs/dai3/core/../data/upfiles/images/2012-07/18/
		//上次多个图片
		$err_var = array("-2"=>"文件不存在","-3"=>"图片类型不正确","-4"=>"不是图片类型","-5"=>"上传图片过大");
		require_once 'oss/sdk.class.php';
		$oss_service = new ALIOSS();
		//判断是不是数组
		if (is_array($_FILES[$file]['name'])){
			$_result = array();
			foreach($_FILES[$file]['name'] as $i => $value){
				if ($value!=""){
					$this->img_type = strtolower(substr($_FILES[$file]['name'][$i],-3,3));
					if ($_FILES[$file]['size'][$i]==0)	$error = -2;//文件不存在
					if(!in_array($this->img_type,$this->file_type)) $error = -3;
					if(strpos($_FILES[$file]['type'][$i],'image')===false) $error = -4;
					if($_FILES[$file]['size'][$i] > $this->file_size*1024) $error = -5;
					if($_FILES[$file]['error'][$i] !=0 ) $error = -2;
					$this->_mkdirs($this->file_dir);//创建文件夹
					
					$newFile = $this->file_newname.$i.substr($_FILES[$file]['name'][$i],-4,4);//新文件名
					$oldFile = $_FILES[$file]['name'][$i];//旧文件名
					$allFile = $newDir.$newFile; //
					if ($error<0){
						echo "<script>alert('".$err_var[$error]."');history.go(-1);</script>";
						exit;
					}
					if(function_exists('move_uploaded_file')){
						$result = move_uploaded_file($_FILES[$file]['tmp_name'][$i],$allFile);
					}else{
						@copy($_FILES[$file]['tmp_name'][$i],$allFile);
					}
					
					$this->setSrcImg($allFile);//设置图片生成路径
					$this->setCutimg();//截取的类型
					$this->setDstImg($allFile);
					//$this->_createMask();
					$this->_output();
					
					if ($error==""){
						$sql = "insert into `{upfiles}` set code='{$data['code']}',aid='{$data['aid']}',user_id='{$data['user_id']}',`name`='{$_name}',filesize='{$_FILES[$file]['size'][$i]}',filetype='{$this->img_type}',fileurl='".$this->file_dir.$newFile."',filename='".$newFile."',`addtime` = '".time()."', `updatetime` = '".time()."',`addip` = '".ip_address()."',`updateip` = '".ip_address()."'";
						$mysql ->db_query($sql);
						$upfiles_id = $mysql->db_insert_id();
						$_result[$i]['filename'] = $this->file_dir.$newFile;
						$_result[$i]['upfiles_id'] = $upfiles_id;
					}else{
						echo "<script>alert('".$err_var[$error]."');history.go(-1);</script>";
						exit;
					}
				}
			}
			return $_result;
		}else{
			$this->img_type = strtolower(substr($_FILES[$file]['name'],-3,3));
			if ($_FILES[$file]['size']==0)	return "";//文件不存在
			if(!in_array($this->img_type,$this->file_type)) $error = -3; //不是合法的扩展名
			if(strpos($_FILES[$file]['type'],'image')===false) $error = -4;//不是图片
			if($_FILES[$file]['size'] > $this->file_size*1024) $error = -5; //图片大小超出限制
			if($_FILES[$file]['error'] !=0 ) $error = -2;
			$this->_mkdirs($this->file_dir);//创建文件夹			
			//timest 2012-07-18 
			// 在 OSS上 新建文件夹
			if($this->oss_used==1){ 
				$oss_file_dir = date("Y-m",time())."/".date("d",time())."/";
				$oss_dir = 'data/upfiles/images/'.$oss_file_dir;
				$oss_service->create_object_dir($this->bucket,$oss_dir);
			}
			$newFile = $this->file_newname.substr($_FILES[$file]['name'],-4,4);//新文件名
			$oldFile = $_FILES[$file]['name'];//旧文件名
			$allFile = $newDir.$newFile; //新文件路径+ 新文件名 ,本地上的图片

			//print_r('allFile' . $allFile); allFile/data/htdocs/dai3/core/../data/upfiles/images/2012-07/19/2263_attestation_13426680195.png
			if ($error!=""){
				echo "<script>alert('".$err_var[$error]."');history.go(-1);</script>";
				exit;
			}
			if(function_exists('move_uploaded_file')){
				$result = move_uploaded_file($_FILES[$file]['tmp_name'],$allFile);
				//将上传的资料放到data下，然后再放到OSS中,然后删除data下的临时图片
				//$allFile 存放的是临时文件的地址
				/*print_r('$oss_dir.$newFile  '.$oss_dir.$newFile . "<br/>" );
				print_r('$allFile  '.$allFile );
				exit;*/
				if($this->oss_used==1){	
					$oss_service->upload_file_by_file($this->bucket, $oss_dir.$newFile, $allFile);   //把图片上传到OSS上 params: bucket  to from
					//如果要删除本地上的文件，在这里操作
				}
			}else{
				@copy($_FILES[$file]['tmp_name'],$allFile);
			}
			//$this->setSrcImg($allFile);//设置图片生成路径
			
			$this->setCutimg();//截取的类型
			$this->setDstImg($allFile);
			//$this->_createMask();
			//@$this->_output();
			//图片上传到OSS当中，需要修改图片存放的URL(两处)  By:timest
			if ($error==""){
				$sql = "insert into `{upfiles}` set code='{$data['code']}',aid='{$data['aid']}',user_id='{$data['user_id']}',`name`='{$data['file']}',filesize='{$_FILES[$file]['size']}',filetype='{$this->img_type}',fileurl='".$this->file_dir.$newFile."',filename='".$newFile."',`addtime` = '".time()."', `updatetime` = '".time()."',`addip` = '".ip_address()."',`updateip` = '".ip_address()."'";
				$mysql ->db_query($sql);
				$upfiles_id = $mysql->db_insert_id();
				return array("filename"=>$this->file_dir.$newFile,"upfiles_id"=>$upfiles_id);
			}else{
				print_r("error: " . $error);
				exit;
			}
			return "";
		}
		//上次单个图片
	}
	function litpic ($data = array()){
		$this->img_type = strtolower(substr($data['url'],-3,3));
		$this->setData($data);
		$this->setSrcImg(ROOT_PATH.$data['url']);//设置图片生成路径
		$this->setCutimg();//截取的类型
		$this->setDstImg(ROOT_PATH.$data['new_url']);
		$this->_createMask();
		$this->_output();
	}
	
	function UpfileSwfupload($data = array()){
		global $mysql;
		$error = "";
		$file = $data['file'];
		$dateFile = date("Y-m",time());
		$this->setData($data);
		$newDir = ROOT_PATH.$this->file_dir;
		$count = $num = 0;
		$error_msg = array();
		//上次多个图片
		$err_var = array("-2"=>"文件不存在","-3"=>"图片类型不正确","-4"=>"不是图片类型","-5"=>"上传图片过大");
		//判断是不是数组
		$_result = array();
		$this->img_type = strtolower(substr($_FILES[$file]['name'],-3,3));
		if ($_FILES[$file]['size']==0)	$error = -2;//文件不存在
		//if(!in_array($this->img_type,$this->file_type)) $error = -3;
		//if(strpos($_FILES[$file]['type'],'image')===false) $error = -4;
		if($_FILES[$file]['size'] > $this->file_size*1024) $error = -5;
		//if($_FILES[$file]['error'] !=0 ) $error = -2;
		
		if ($error<0) return;
		$this->_mkdirs($this->file_dir);//创建文件夹
		
		$newFile = $data['user_id']."_".$data['data_type']."_".time().rand(110,999).substr($_FILES[$file]['name'],-4,4);//新文件名
		$oldFile = $_FILES[$file]['name'];//旧文件名
		$allFile = $newDir.$newFile; //
		
		if(function_exists('move_uploaded_file')){
			$result = move_uploaded_file($_FILES[$file]['tmp_name'],$allFile);
		}else{
			@copy($_FILES[$file]['tmp_name'],$allFile);
		}
		
		$this->setSrcImg($allFile);//设置图片生成路径
		$this->setCutimg();//截取的类型
		$this->setDstImg($allFile);
		$this->_createMask();
		$this->_output();
		
		if($data['aid']==""){
			$sql = "select * from `{upfiles}` where user_id='{$data['user_id']}' order by id desc";
			$result = $mysql->db_fetch_array($sql);
			$data['aid'] = $result['aid'];
		}
		$sql = "insert into `{upfiles}` set 	
		code='{$data['code']}',aid='{$data['aid']}',user_id='{$data['user_id']}',
		`name`='{$data['name']}',filesize='{$_FILES[$file]['size']}',
		filetype='{$this->img_type}',fileurl='".$this->file_dir.$newFile."',
		filename='".$newFile."',`addtime` = '".time()."', `updatetime` = '".time()."',
		`addip` = '".ip_address()."',`updateip` = '".ip_address()."'";
		$mysql ->db_query($sql);
		$upfiles_id = $mysql->db_insert_id();
		$sql = "select * from `{upfiles}` where code = '{$data['code']}' and aid = '{$data['aid']}' and if_cover = 1";
		$result = $mysql->db_fetch_array($sql);
		if($result==false){
			$sql = "update `{upfiles}` set if_cover=1 where id= {$upfiles_id}";
			$mysql->db_query($sql);
		}
		return array("filename"=>$this->file_dir.$newFile,"upfiles_id"=>$upfiles_id);
	}
	
	
	//上传多个图片
	function upfiles($data = array()){
		global $mysql;
		$error = "";
		$file = $data['file'];
		$dateFile = date("Y-m",time());
		$this->setData($data);
		$newDir = ROOT_PATH.$this->file_dir;
		$count = $num = 0;
		$error_msg = array();
		//上次多个图片
		$err_var = array("-2"=>"文件不存在","-3"=>"图片类型不正确","-4"=>"不是图片类型","-5"=>"上传图片过大");
		//判断是不是数组
		$_result = array();
		foreach($_FILES[$file]['name'] as $i => $value){
			if ($value!=""){
				$count ++;
				$this->img_type = strtolower(substr($_FILES[$file]['name'][$i],-3,3));
				if ($_FILES[$file]['size'][$i]==0)	$error = -2;//文件不存在
				if(!in_array($this->img_type,$this->file_type)) $error = -3;
				if(strpos($_FILES[$file]['type'][$i],'image')===false) $error = -4;
				if($_FILES[$file]['size'][$i] > $this->file_size*1024) $error = -5;
				if($_FILES[$file]['error'][$i] !=0 ) $error = -2;
				$this->_mkdirs($this->file_dir);//创建文件夹
				
				$newFile = md5(time().rand(1,9)).$i.substr($_FILES[$file]['name'][$i],-4,4);//新文件名
				$oldFile = $_FILES[$file]['name'][$i];//旧文件名
				$allFile = $newDir.$newFile; //
				if ($error<0){
					$error_msg[] = $oldFile.$err_var[$error];
				}else{
					if(function_exists('move_uploaded_file')){
						$result = move_uploaded_file($_FILES[$file]['tmp_name'][$i],$allFile);
					}else{
						@copy($_FILES[$file]['tmp_name'][$i],$allFile);
					}
					$this->setSrcImg($allFile);//设置图片生成路径
					$this->setCutimg();//截取的类型
					$this->setDstImg($allFile);
					$this->_createMask();
					$this->_output();
					if($data['name'][$i]==""){
						$_name = $oldFile;
					}else{
						$_name = $data['name'][$i];
					}
					$sql = "insert into `{upfiles}` set code='{$data['code']}',aid='{$data['aid']}',user_id='{$data['user_id']}',`name`='{$_name}',filesize='{$_FILES[$file]['size'][$i]}',filetype='{$this->img_type}',fileurl='".$this->file_dir.$newFile."',filename='".$newFile."',`addtime` = '".time()."', `updatetime` = '".time()."',`addip` = '".ip_address()."',`updateip` = '".ip_address()."'";
					$mysql ->db_query($sql);
					$file_id = $mysql->db_insert_id();
					$num++;
				}
			}
		}
		//设为封面
		if ($num>0){
			$sql = "select * from `{upfiles}` where code = '{$data['code']}' and aid = '{$data['aid']}' and if_cover = 1";
			$result = $mysql->db_fetch_array($sql);
			if($result==false){
				$sql = "update `{upfiles}` set if_cover=1 where id= {$file_id}";
				$mysql->db_query($sql);
			}
		}
		$display = "有{$count}张相片上传，其中{$num}上传成功。".join(",",$error_msg);
		return $display;
	}
	
	function UpdateMore($data = array()){
		global $mysql;
		foreach ($data['id'] as $key => $value){
			$sql = "update `{upfiles}` set `name` = '{$data['name'][$key]}' where `id` = '{$value}' and user_id = '{$data['user_id']}'";
			$mysql->db_query($sql);
		}
	
	}
	
	//获得单个相片的信息
	function GetOne($data = array()){
		global $mysql;
		$_sql = "where 1=1 ";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and  p1.user_id='{$data['user_id']}'";
		}
		if (isset($data['id']) && $data['id']!=""){
			$_sql .= " and  p1.id='{$data['id']}'";
		}
		$sql = "select * from `{upfiles}` as p1 {$_sql}";
		return $mysql->db_fetch_array($sql);
	}
	
	//删除相片
	function Delete($data = array()){
		global $mysql;
		$_sql = "where id={$data['id']}";
		if (isset($data['user_id']) && $data['user_id']!=""){
			$_sql .= " and user_id = '{$data['user_id']}'";
		}
		$sql = "select * from `{upfiles}`  {$_sql}";
		$result = $mysql->db_fetch_array($sql);
		if ($result!=false){
			$_dir = explode($result['filename'],$result['fileurl']);
			self::DelPic($_dir[0],$result['filename']);
			$sql = "delete from `{upfiles}` {$_sql}" ;
			$mysql ->db_query($sql);
		}
	}
	
	function  DelPic($dir,$filename){
		$_filename = substr($filename,0,strlen($filename)-4);
		if (is_dir($dir)) { 
			$dh=opendir($dir);//打开目录 //列出目录中的所有文件并去掉 . 和 .. 
			while (false !== ( $file = readdir ($dh))) { 
				if($file!="." && $file!="..") {
					$fullpath=$dir."/".$file;
					$_url = explode($_filename,$file);
					if(!is_dir($fullpath) && isset($_url[0]) && $_url[0]=="") { 
						unlink($fullpath);
					} 
				}
			}
			closedir($dh); 
		} 
	
	}
	
	  /**
     * 图片输出
     */
    function _output(){
        $img_type  = $this->img_type;
		if ($img_type!=""){
			$func_name = $this->all_type[$img_type]['output'];
			
			if(function_exists($func_name))        {
				// 判断浏览器,若是IE就不发送头
				if(isset($_SERVER['HTTP_USER_AGENT']))            {
					$ua = strtoupper($_SERVER['HTTP_USER_AGENT']);
					if(!preg_match('/^.*MSIE.*\)$/i',$ua))                {
						header("Content-type:$img_type");
					}
				}
				@$func_name($this->h_dst, $this->dst_img, $this->file_quality);
			}
			else{
				return false;
			}
		}else{
			return false;
		}
    }
	
	function cutImg(){	
		$this->src_w = $this->getImgWidth($this->h_src);
        $this->src_h = $this->getImgHeight($this->h_src);
		 // 创建新图并拷贝
        $this->h_dst = imagecreatetruecolor($this->dst_w, $this->dst_h);
	}
	
	  /**
     * 设置图片生成路径
     *
     * @param    string    $src_img   图片生成路径
     */
    function setSrcImg($src_img, $img_type=null)  {
        if(!file_exists($src_img))   return -1 ;//die("图片不存在");

		//读取文件类型
		if(!empty($img_type)){
			$this->img_type = $img_type;
		}
		else{
			$img_info = $this->getImgInfo($src_img);
	
		}

		$this->h_src =$img_info['img_src'];
		$this->src_w = $img_info['img_width'];
		$this->src_h = $img_info['img_height'];

    }
	
	
	function setCutimg(){
		
		if ($this->cut_status==1){
		
			//按比例进行缩小
			if ($this->cut_type==1){
				$this->fill_w = round($this->src_w * $this->cut_scale / 100) - $this->img_border_size*2;
				$this->fill_h = round($this->src_h * $this->cut_scale / 100) - $this->img_border_size*2;
	
				// 源文件起始坐标
				$this->src_x  = 0;
				$this->src_y  = 0;
				
				$this->copy_w = $this->src_w;
				$this->copy_h = $this->src_h;
	
				// 目标尺寸
				$this->dst_w   = $this->fill_w + $this->img_border_size*2;
				$this->dst_h   = $this->fill_h + $this->img_border_size*2;
			
			}
			
			elseif ($this->cut_type==2){
				$fill_w   = (int)$this->cut_width - $this->img_border_size*2;
				$fill_h   = (int)$this->cut_height - $this->img_border_size*2;
				
				if($fill_w < 0 || $fill_h < 0){
					return -8 ;//图片边框过大，已超过了图片的宽度;
				}
				
				$rate_w = $this->src_w/$fill_w;
				$rate_h = $this->src_h/$fill_h;
				
				// 如果原图大于缩略图，产生缩小，否则不缩小
				if($rate_w < 1 && $rate_h < 1){
					$this->fill_w = (int)$this->src_w;
					$this->fill_h = (int)$this->src_h;
				}
				else{
					if($rate_w >= $rate_h)	{
						$this->fill_w = (int)$fill_w;
						$this->fill_h = round($this->src_h/$rate_w);
					}
					else{
						$this->fill_w = round($this->src_w/$rate_h);
						$this->fill_h = (int)$fill_h;
					}
				}
				
				$this->src_x  = 0;
				$this->src_y  = 0;
	
				$this->copy_w = $this->src_w;
				$this->copy_h = $this->src_h;
	
				// 目标尺寸
				$this->dst_w   = $this->fill_w + $this->img_border_size*2;
				$this->dst_h   = $this->fill_h + $this->img_border_size*2;
				
			}
			
			// 如果图片是缩小剪切才进行操作
			elseif ($this->cut_type==3){
			
				$fill_w   = (int)$this->cut_width - $this->img_border_size*2;
				$fill_h   = (int)$this->cut_height - $this->img_border_size*2;
				
				if($fill_w < 0 || $fill_h < 0){
					return -8 ;//图片边框过大，已超过了图片的宽度;
				}
				
				$rate_w = $this->src_w/$fill_w;
				$rate_h = $this->src_h/$fill_h;
			
				// 如果图片是缩小剪切才进行操作
				if($rate_w >= 1 && $rate_h >=1){
					if($this->src_w > $this->src_h)	{
						$src_x = round($this->src_w-$this->src_h)/2;
						
						$this->src_x  = $src_x;
						$this->src_y  = 0;
						
						$this->fill_w = $this->fill_h;
						$this->fill_h = $this->fill_h;
	
						$this->copy_w = $this->src_h;
						$this->copy_h = $this->src_h;
						
					}
					elseif($this->src_w < $this->src_h){
						$src_y = round($this->src_h-$this->src_w)/2;
						
						$this->src_x  = 0;
						$this->src_y  = $src_y;
						
						$this->fill_w = $this->fill_w;
						$this->fill_h = $this->fill_h;
	
						$this->copy_w = $this->src_w;
						$this->copy_h = $this->src_w;
					}
					else{
						$this->src_x  = 0;
						$this->src_y  = 0;
						
						$this->copy_w = $this->src_w;
						$this->copy_h = $this->src_w;
						
						$this->fill_w = $this->fill_w;
						$this->fill_h = $this->fill_h;
					}
				}
				else{
					$this->src_x  = 0;
					$this->src_y  = 0;
					
					$this->fill_w = $this->src_w;
					$this->fill_h = $this->src_h;
	
					$this->copy_w = $this->src_w;
					$this->copy_h = $this->src_h;
					
				}
				
				// 目标尺寸
				$this->dst_w   = $this->fill_w + $this->img_border_size*2;
				$this->dst_h   = $this->fill_h + $this->img_border_size*2;
	
			}
			
			// 手动截图，想截多少就多少
			elseif ($this->cut_type==4){
			
				$fill_w   = $this->cut_width - $this->img_border_size*2;
				$fill_h   = $this->cut_height - $this->img_border_size*2;
				
				if($fill_w < 0 || $fill_h < 0){
					return -8 ;//图片边框过大，已超过了图片的宽度;
				}
				
				$this->copy_w = $fill_w;
				$this->copy_h = $fill_h;
	
				// 目标尺寸
				$this->dst_w   = $fill_w + $this->img_border_size*2;
				$this->dst_h   = $fill_h + $this->img_border_size*2;	
				
				$this->fill_w = $fill_w;
				$this->fill_h = $fill_h;
			
			}
		}else{
			$this->src_x  = 0;
			$this->src_y  = 0;
			
			$this->fill_w = $this->src_w;
			$this->fill_h = $this->src_h;

			$this->copy_w = $this->src_w;
			$this->copy_h = $this->src_h;
			
			// 目标尺寸
			$this->dst_w   = $this->fill_w + $this->img_border_size*2;
			$this->dst_h   = $this->fill_h + $this->img_border_size*2;
		
		}
		 // 目标文件起始坐标
        $this->start_x = $this->img_border_size;
        $this->start_y = $this->img_border_size;
	}
	
	
	
	
	/**
     * 设置图片生成路径
     *
     * @param    string    $dst_img   图片生成路径
     */
    function setDstImg($dst_img)    {
        $arr  = explode('/',$dst_img);
        $last = array_pop($arr);
        $path = implode('/',$arr);
        $this->_mkdirs(ROOT_PATH.$path);
        $this->dst_img = $dst_img;
    }
	
	
	function getImgInfo($src_img){
		if ($src_img=="") return -1;	
		 if(function_exists("file_get_contents"))   {
            $src = file_get_contents($src_img);
        }
        else {
            $handle = fopen ($src_img, "r");
            while (!feof ($handle))
            {
                $src .= fgets($fd, 4096);
            }
            fclose ($handle);
        }
        if(empty($src))   {
            return -1;//die("图片源为空");
        }
        $h_src = @ImageCreateFromString($src);
		$img_info = @getimagesize ($src_img);
		
		return array("img_width"=>$img_info[0],"img_height"=>$img_info[1],"img_type"=>$img_info[2],"img_src"=>$h_src);
		
	}
	
	 /**
     * 检查图片类型是否合法,调用了array_key_exists函数，此函数要求
     * php版本大于4.1.0
     *
     * @param    string     $img_type    文件类型
     */
    function _checkValid($img_type)
    {
        if(!array_key_exists($img_type, $this->all_type))
        {
            return false;
        }
    }
	
	 /**
     * 按指定路径生成目录
     *
     * @param    string     $path    路径
     */
    function _mkdirs($path)  {
        $adir = explode('/',$path);
        $dirlist = '';
        $rootdir = array_shift($adir);
        if(($rootdir!='.'||$rootdir!='..')&&!file_exists($rootdir))
        {
            @mkdir($rootdir);
        }
        foreach($adir as $key=>$val)
        {
            if($val!='.'&&$val!='..')
            {
                $dirlist .= "/".$val;
                $dirpath = $rootdir.$dirlist;
                if(!file_exists($dirpath))
                {
                    @mkdir($dirpath);
                    @chmod($dirpath,0777);
                }
            }
        }
    }
	/**
     * 分析颜色
     *
     * @param    string     $color    十六进制颜色
     */
    function _parseColor($color)
    {
        $arr = array();
        for($ii=1; $ii<strlen($color); $ii++)
        {
            $arr[] = hexdec(substr($color,$ii,2));
            $ii++;
        }

        Return $arr;
    }

    /**
     * 计算出位置坐标
     */
    function _countMaskPos()
    {
        if($this->_isFull())
        {
            switch($this->mask_position)
            {
                case 1:
                    // 左上
                    $this->mask_pos_x = $this->mask_offset_x + $this->img_border_size;
                    $this->mask_pos_y = $this->mask_offset_y + $this->img_border_size;
                    break;

                case 2:
                    // 左下
                    $this->mask_pos_x = $this->mask_offset_x + $this->img_border_size;
                    $this->mask_pos_y = $this->src_h - $this->mask_h - $this->mask_offset_y;
                    break;

                case 3:
                    // 右上
                    $this->mask_pos_x = $this->src_w - $this->mask_w - $this->mask_offset_x;
                    $this->mask_pos_y = $this->mask_offset_y + $this->img_border_size;
                    break;

                case 4:
                    // 右下
                    $this->mask_pos_x = $this->src_w - $this->mask_w - $this->mask_offset_x;
                    $this->mask_pos_y = $this->src_h - $this->mask_h - $this->mask_offset_y;
                    break;

                default:
                    // 默认将水印放到右下,偏移指定像素
                    $this->mask_pos_x = $this->src_w - $this->mask_w - $this->mask_offset_x;
                    $this->mask_pos_y = $this->src_h - $this->mask_h - $this->mask_offset_y;
                    break;
            }
        }
        else
        {
            switch($this->mask_position)
            {
                case 1:
                    // 左上
                    $this->mask_pos_x = $this->mask_offset_x + $this->img_border_size;
                    $this->mask_pos_y = $this->mask_offset_y + $this->img_border_size;
                    break;

                case 2:
                    // 左下
                    $this->mask_pos_x = $this->mask_offset_x + $this->img_border_size;
                    $this->mask_pos_y = $this->dst_h - $this->mask_h - $this->mask_offset_y - $this->img_border_size;
                    break;

                case 3:
                    // 右上
                    $this->mask_pos_x = $this->dst_w - $this->mask_w - $this->mask_offset_x - $this->img_border_size;
                    $this->mask_pos_y = $this->mask_offset_y + $this->img_border_size;
                    break;

                case 4:
                    // 右下
                    $this->mask_pos_x = $this->dst_w - $this->mask_w - $this->mask_offset_x - $this->img_border_size;
                    $this->mask_pos_y = $this->dst_h - $this->mask_h - $this->mask_offset_y - $this->img_border_size;
                    break;

                default:
                    // 默认将水印放到右下,偏移指定像素
                    $this->mask_pos_x = $this->dst_w - $this->mask_w - $this->mask_offset_x - $this->img_border_size;
                    $this->mask_pos_y = $this->dst_h - $this->mask_h - $this->mask_offset_y - $this->img_border_size;
                    break;
            }
        }
    }
	
	/**
     * 生成水印,调用了生成水印文字和水印图片两个方法
     */
    function _createMask(){
		
		 if($this->mask_status!=1 || (empty($this->mask_word) && empty($this->mask_img)))  {
            $this->h_dst = @imagecreatetruecolor($this->dst_w, $this->dst_h);
            $white = @imageColorAllocate($this->h_dst,255,255,255);
            @imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$white);// 填充背景色
            $this->_drawBorder();

            @imagecopyresampled( $this->h_dst, $this->h_src,
                        $this->start_x, $this->start_y,
                        $this->src_x, $this->src_y,
                        $this->fill_w, $this->fill_h,
                        $this->copy_w, $this->copy_h);
						
        }else {
			if($this->mask_word!="") {
				// 获取字体信息
				$this->_setFontInfo();
				
				if($this->_isFull()) {
					//die("水印文字过大");
				}
				else{
					$this->h_dst = imagecreatetruecolor($this->dst_w, $this->dst_h);
					$white = ImageColorAllocate($this->h_dst,255,255,255);
					imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$white);// 填充背景色
					$this->_drawBorder();
					imagecopyresampled( $this->h_dst, $this->h_src,
										$this->start_x, $this->start_y,
										$this->src_x, $this->src_y,
										$this->fill_w, $this->fill_h,
										$this->copy_w, $this->copy_h);
					$this->_createMaskWord($this->h_dst);
				}
				
			}else if($this->mask_img){
				$this->_loadMaskImg();//加载时，取得宽高
				if($this->_isFull()) {
				   $this->h_dst = @imagecreatetruecolor($this->dst_w, $this->dst_h);
				$white = @imageColorAllocate($this->h_dst,255,255,255);
				@imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$white);// 填充背景色
				$this->_drawBorder();
	
				@imagecopyresampled( $this->h_dst, $this->h_src,
							$this->start_x, $this->start_y,
							$this->src_x, $this->src_y,
							$this->fill_w, $this->fill_h,
							$this->copy_w, $this->copy_h);
				}else{
					// 创建新图并拷贝
					$this->h_dst = imagecreatetruecolor($this->dst_w, $this->dst_h);
					$white = ImageColorAllocate($this->h_dst,255,255,255);
					imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$white);// 填充背景色
					$this->_drawBorder();
					imagecopyresampled( $this->h_dst, $this->h_src,
										$this->start_x, $this->start_y,
										$this->src_x, $this->src_y,
										$this->fill_w, $this->fill_h,
										$this->copy_w, $this->copy_h);
					$this->_createMaskImg($this->h_dst);
				}
			}
        }

       
    }
  /**
     * 设置字体信息
     */
    function _setFontInfo()
    {
        if(is_numeric($this->font)) {
            $this->font_w  = imagefontwidth($this->font);
            $this->font_h  = imagefontheight($this->font);

            // 计算水印字体所占宽高
            $word_length   = strlen($this->mask_word);
            $this->mask_w  = $this->font_w*$word_length;
            $this->mask_h  = $this->font_h;
        }
        else{
            $arr = imagettfbbox ($this->font_size,0, $this->font,$this->mask_word);
            $this->mask_w  = abs($arr[0] - $arr[2]);
            $this->mask_h  = abs($arr[7] - $arr[1]);
        }
    }

    /**
     * 画边框
     */
    function _drawBorder()
    {
        if(!empty($this->img_border_size))
        {
            $c = $this->_parseColor($this->img_border_color);
            $color = ImageColorAllocate($this->h_src,$c[0], $c[1], $c[2]);
            imagefilledrectangle($this->h_dst,0,0,$this->dst_w,$this->dst_h,$color);// 填充背景色
        }
    }

    /**
     * 生成水印文字
     */
    function _createMaskWord($src)
    {
        $this->_countMaskPos();
        $result = $this->_checkMaskValid();
		if ($result != false){
			$c = $this->_parseColor($this->mask_font_color);
			$color = imagecolorallocatealpha($src, $c[0], $c[1], $c[2], $this->mask_txt_pct);
	
			if(is_numeric($this->font)){
				imagestring($src,
							$this->font,
							$this->mask_pos_x, $this->mask_pos_y,
							$this->mask_word,
							$color);
							
			}
			else{
				imagettftext($src,
							$this->font_size, 0,
							$this->mask_pos_x, $this->mask_pos_y,
							$color,
							$this->font,
							$this->mask_word);
			}
		}
    }

    /**
     * 生成水印图
     */
    function _createMaskImg($src) {
        $this->_countMaskPos();
		$result = $this->_checkMaskValid();
		if ($result != false){
			imagecopymerge($src,
							$this->h_mask,
							$this->mask_pos_x ,$this->mask_pos_y,
							0, 0,
							$this->mask_w, $this->mask_h,
							$this->mask_img_pct);
			imagedestroy($this->h_mask);
		}
    }

    /**
     * 加载水印图
     */
    function _loadMaskImg(){
        $mask_type = $this->_getImgType($this->mask_img);
        $this->_checkValid($this->img_type);

        // file_get_contents函数要求php版本>4.3.0
        $src = '';
        if(function_exists("file_get_contents")){
            $src = file_get_contents(ROOT_PATH.$this->mask_img);
        } else {
            $handle = fopen ($this->mask_img, "r");
            while (!feof ($handle)){
                $src .= fgets($fd, 4096);
            }
            fclose ($handle);
        }
        if(empty($this->mask_img))  {
           return false;
        }else{
			$this->h_mask = ImageCreateFromString($src);
			$this->mask_w = $this->getImgWidth($this->h_mask);
			$this->mask_h = $this->getImgHeight($this->h_mask);
		}
    }
	
	 /**
     * 取得图片类型
     *
     * @param    string     $file_path    文件路径
     */
    function _getImgType($file_path){
        $type_list = array("1"=>"gif","2"=>"jpg","3"=>"png","4"=>"swf","5" => "psd","6"=>"bmp","15"=>"wbmp");
        if(file_exists($file_path)){
            $img_info = @getimagesize ($file_path);
            if(isset($type_list[$img_info[2]])){
                return $type_list[$img_info[2]];
            }
        } else{
            return  false;
        }
    }
	
	/**
     * 检查水印图是否大于生成后的图片宽高
     */
    function _isFull()
    {
        Return (   $this->mask_w + $this->mask_offset_x > $this->fill_w
                || $this->mask_h + $this->mask_offset_y > $this->fill_h)
                   ?true:false;
    }
	
	 /**
     * 检查水印图是否超过原图
     */
    function _checkMaskValid()
    {
        if($this->mask_w + $this->mask_offset_x > $this->src_w
            || $this->mask_h + $this->mask_offset_y > $this->src_h)
        {
           return false;
        }else{
			return true;
		}
    }
	  /**
     * 取得图片的宽
     */
    function getImgWidth($src)
    {
        return imagesx($src);
    }

    /**
     * 取得图片的高
     */
    function getImgHeight($src)
    {
        return imagesy($src);
    }
}
?>