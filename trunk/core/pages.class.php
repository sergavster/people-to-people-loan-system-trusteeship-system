<?

class pages{
 	/**
  	* config ,public
  	*/
 	var $page_name="page";//page标签，用来控制url页。比如说xxx.php?PB_page=2中的PB_page
 	var $next_page='下一页';//下一页
 	var $pre_page='上一页';//上一页
 	var $first_page='首页';//首页
 	var $last_page='尾页';//尾页
 	var $pre_bar='<<';//上一分页条
 	var $next_bar='>>';//下一分页条
 	var $format_left='[';
 	var $format_right=']';
 	var $very_page='';
 	var $is_ajax=false;//是否支持AJAX分页模式 
	var $suffix="";//伪静态的后缀
	/**
  	* private
  	*
  	*/ 
 	var $pagenum=10;//控制记录条的个数。
 	var $totalpage=0;//总页数
 	var $ajax_action_name='';//AJAX动作名
 	var $nowindex=1;//当前页
 	var $url="";//url地址头
 	var $offset=0;
	var $totalnum = 0;
	var $rewrite = false;
	/**
  	* constructor构造函数
  	*
  	* @param array $array['total'],$array['perpage'],$array['nowindex'],$array['url'],$array['ajax']...
  	*/
 	function set_data($array){
		
  		if(is_array($array)){
     		if(!array_key_exists('total',$array)) $this->error(__FUNCTION__,'need a param of total');//判断总条数是否存在
     		$total = intval($array['total']);
     		$perpage=(array_key_exists('epage',$array))?intval($array['epage']):10;//每页所要显示的条数
     		$nowindex=(array_key_exists('page',$array))?intval($array['page']):'';//当前的页面
     		$url=(array_key_exists('url',$array))?$array['url']:'';//url地址
     		$page_name= empty($array['page_name'])?"page":$array['page_name'];//
			$rewrite=(array_key_exists('rewrite',$array))?$array['rewrite']:'';//url地址
			$very_page=(array_key_exists('very_page',$array))?$array['very_page']:'6';//url地址
			$suffix=(array_key_exists('suffix',$array))?$array['suffix']:'';//url地址
			$canshu=(array_key_exists('canshu',$array))?$array['canshu']:'';//参数
  		}else{
     		$total=$array;
     		$perpage=10;
     		$nowindex='1';
     		$url='';
     		$very_page='6';
     		$page_name='page';
			$rewrite='';
			$suffix="";
			$canshu="";
  		}
		if ($nowindex==0) $nowindex=1; 
		$this->rewrite = empty($rewrite)?$rewrite:false;
  		if((!is_int($total))||($total<0))$this->error(__FUNCTION__,$total.' is not a positive integer!');
  		if((!is_int($perpage))||($perpage<=0))$this->error(__FUNCTION__,$perpage.' is not a positive integer!');
		
		$this->totalnum = $total;
		$this->suffix = $suffix;
		$this->very_page = $very_page;
		$this->page_name = $page_name;
		$this->canshu = $canshu;
  		if(!empty($array['page_name'])) {
			$this->set('page_name',$array['page_name']);//设置page_name
		}
  		$this->nowindex  = $this->_set_nowindex($nowindex);//设置当前页
  		$this->url       = $this->_set_url($url);//设置链接地址
  		$this->totalpage = ceil($total/$perpage);//获取总页数
  		$this->offset    = ($this->nowindex-1)*$perpage;//读取当前的总条数
  		if(!empty($array['ajax']))$this->open_ajax($array['ajax']);//打开AJAX模式
 	}
	
	/**
  	* 设定类中指定变量名的值，如果改变量不属于这个类，将throw一个exception
  	*
  	* @param string $var
  	* @param string $value
  	*/
 	function set($var,$value){
  		if(in_array($var,get_object_vars($this))){
    		$this->$var = $value;
  		}else {
   			$this->error(__FUNCTION__,$var." does not belong to PB_Page!");
  		}
 	}
	
	/**
  	* 设置当前页面
  	*/
 	function _set_nowindex($nowindex){
  		if(empty($nowindex)){//系统获取
   			if(isset($_GET[$this->page_name])){
    			$nowindex = intval($_GET[$this->page_name]);
   			}
  		}else{
   			$nowindex = intval($nowindex);//手动设置
  		}
		return $nowindex;
 	}
	
	
	/**
  	* 设置url头地址
  	* @param: String $url
  	* @return boolean
  	*/
 	function _set_url($url=""){
		
		if($this->rewrite == true || $this->suffix!=""){
			if ($url!=""){
				$url .=$this->page_name;
			}elseif(stristr($_SERVER['REQUEST_URI'],$this->page_name.'=')){
			
				//地址存在页面参数
				$url=str_replace($this->page_name.'='.$this->nowindex,'',$_SERVER['REQUEST_URI']);
				
				$last=$url[strlen($url)-1];
				if($last=='?'||$last=='&'){
					$url.=$this->page_name."=";
				}else{
					$url.='&'.$this->page_name."=";
				}
			}else{
				
				$_url = explode("?",$_SERVER['REQUEST_URI']);
				$url=str_replace($this->page_name.$this->nowindex.$this->suffix,'',$_url[0]);
				
				$url=str_replace($this->page_name.$this->suffix,'',$url);
				$url=str_replace($this->page_name.$this->suffix,'',$url);
				$url .=$this->page_name;
				//var_dump($url);
				//$url = $_SERVER['REQUEST_URI'].'?'.$this->page_name."=";
				
			}
			
			
		}elseif(!empty($url)){//手动设置
			
			$url = $url.((stristr($url,'?'))?'&':'?').$this->page_name."=";
  		}else{          
			
		 //自动获取
   			if(empty($_SERVER['QUERY_STRING'])){//不存在QUERY_STRING时
    			$url=$_SERVER['REQUEST_URI']."?".$this->page_name."=";
   			}else{
    			if(stristr($_SERVER['QUERY_STRING'],$this->page_name.'=')){
				
        			//地址存在页面参数
     				$url=str_replace($this->page_name.'='.$this->nowindex,'',$_SERVER['REQUEST_URI']);
     				$last=$url[strlen($url)-1];
     				if($last=='?'||$last=='&'){
         				$url.=$this->page_name."=";
     				}else{
         				$url.='&'.$this->page_name."=";
     				}
    			}else{
     				$url=$_SERVER['REQUEST_URI'].'&'.$this->page_name.'=';
					
    			}//end if    
   			}//end if
  		}//end if
		return $url;
 	}//end function
	
	
	/**
  	* 为指定的页面返回地址值
  	*
  	* @param int $pageno
  	* @return string $url
  	*/
 	function _get_url($pageno=1){
		if ($this->canshu=="" && $this->rewrite == true){
			$_url = explode("?",$_SERVER['REQUEST_URI']);
			if (isset($_url[1])){
				$this->canshu = "?".$_url[1];
			}
		}
  		return $this->url.$pageno.$this->suffix.$this->canshu;
 	}
 	
 
 	/**
  	* 获取分页显示文字，比如说默认情况下_get_text('<a href="">1</a>')将返回[<a href="">1</a>]
  	*
  	* @param String $str
  	* @return string $url
  	*/ 
 	function _get_text($str){
  		return $this->format_left.$str.$this->format_right;
 	}
 
 	/**
   	* 获取链接地址
 	*/
 	function _get_link($url,$text,$style){
  		$style=(empty($style))?'':'class="'.$style.'"';
  		if($this->is_ajax){
			//如果是使用AJAX模式
   			return '<a '.$style.' href="javascript:'.$this->ajax_action_name.'(\''.$url.'\')">'.$text.'</a>';
  		}else{
   			return '<a '.$style.' href="'.$url.'">'.$text.'</a>';
  		}
 	}
 	
	/**
  	* 显示多条信息，如 1，2，3，4，5
  	* 
  	* @param string $vpage
  	* @return string
  	*/
 	function very_page($vpage=6,$position="center",$style=""){
		$str="";
		$vpage = $this->very_page;
		if ($position=="center"){
			$j = intval($this->nowindex-$vpage/2);
			if ($j<=1) $j=1;
			$q = $vpage/2+$this->nowindex;
			if ($q<$vpage) $q=$vpage;
			if ($q>=$this->totalpage) 	$q = $this->totalpage;
 			if ($this->nowindex==1) $j=1;
			for($i=$j;$i<=$q;$i++){
				if ($i == $this->nowindex){
					$str .= $this->now_page('current');
				}else{
					$str .= $this->_get_link($this->_get_url($i),$i,$style);
				}
			}
		
			
		
		}
  		return $str;
 	}
	
	
	/**
  	* 获取显示"下一页"的代码
  	* 
  	* @param string $style
  	* @return string
  	*/
 	function next_page($style=''){
  		if($this->nowindex<$this->totalpage){
   			return $this->_get_link($this->_get_url($this->nowindex+1),$this->next_page,$style);
  		}
  		return '<span class="'.$style.'">'.$this->next_page.'</span>';
 	}
 
 	
 	/**
  	* 获取显示“上一页”的代码
  	*
  	* @param string $style
  	* @return string
  	*/
 	function pre_page($style=''){
  		if($this->nowindex>1){
   			return $this->_get_link($this->_get_url($this->nowindex-1),$this->pre_page,$style);
  		}
  		return '<span class="'.$style.'">'.$this->pre_page.'</span>';
 	}
 	
 
 	/**
  	* 获取显示“首页”的代码
  	*
  	* @return string
  	*/
 	function first_page($style=''){
  		if($this->nowindex==1){
      		return '<span class="'.$style.'">'.$this->first_page.'</span>';
  		}
  		return $this->_get_link($this->_get_url(1),$this->first_page,$style);
 	}
 	
 
 	/**
  	* 获取显示“尾页”的代码
  	*
  	* @return string
  	*/
 	function last_page($style=''){
  		if($this->nowindex==$this->totalpage){
      		return '<span class="'.$style.'">'.$this->last_page.'</span>';
  		}
  		return $this->_get_link($this->_get_url($this->totalpage),$this->last_page,$style);
 	}
	
	
	/**
  	* 当前页
  	*
  	* @return string
  	*/
 	function now_page($style=''){
  		return '<span class="'.$style.'">'.$this->nowindex.'</span>';
 	}
 	
	/**
 	 * 
 	 *
 	 * @param String $style
 	 * @param String $nowindex_style
 	 * @return String
 	 */
 	function nowbar($style='',$nowindex_style=''){
  		$plus=ceil($this->pagenum/2);
  		if($this->pagenum-$plus+$this->nowindex>$this->totalpage){
  			$plus=($this->pagenum-$this->totalpage+$this->nowindex);
  		}
  		$begin=$this->nowindex-$plus+1;
  		$begin=($begin>=1)?$begin:1;
  		$return='';
  		for($i=$begin;$i<$begin+$this->pagenum;$i++){
   			if($i<=$this->totalpage){
    			if($i!=$this->nowindex){
        			$return.=$this->_get_text($this->_get_link($this->_get_url($i),$i,$style));
    			}else {
        			$return.=$this->_get_text('<span class="'.$nowindex_style.'">'.$i.'</span>');
    			}
   			}else{
    			break;
   			}
   			$return.="\n";
  		}
  		unset($begin);
  		return $return;
 	}
	
	function show($mode=1,$url=''){
 		if ($this->totalpage < 1) {
 			$this->totalpage = 1;
 		}
  		switch ($mode){
   			case '1':
    			$this->next_page='下一页';
    			$this->pre_page='上一页';
    			$this->first_page='首页';
    			$this->last_page='尾页';
    			return $this->first_page().' '.$this->pre_page().' '.$this->next_page().' '.$this->last_page().' 页次:<strong>'.$this->nowindex.'</strong>/'.$this->totalpage.'  第'.$this->input_page().'页';
    			break;
   			case '2':
    			$this->next_page='下一页';
    			$this->pre_page='上一页';
    			$this->first_page='首页';
    			$this->last_page='尾页';
    			return $this->first_page().$this->pre_page().'[第'.$this->nowindex.'页]'.$this->next_page().$this->last_page().'第'.$this->select($url).'页';
    			break;
   			case '3':
    			$this->next_page='下一页';
    			$this->pre_page='上一页';
    			$this->first_page='首页';
    			$this->last_page='尾页';
    			return "共".$this->totalpage."页/"."当前为第".$this->nowindex."页".' '.$this->first_page().' '.$this->pre_page().' '.$this->next_page().' '.$this->last_page();
    			break;
   			case '4':
    			$this->next_page='下一页';
    			$this->pre_page='上一页';
    			return $this->pre_page().$this->nowbar().$this->next_page();
    			break;
   			case '5':
				$this->pre_page='<font>上一页</font>';
				$this->next_page='<font>下一页</font>';
    			return $this->pre_page("disabled").$this->very_page().$this->next_page("disabled");
    			break;
   			case '6':	//用户中心商品列表专用
    			$this->next_page='下一页';
    			$this->pre_page='上一页';
    			$this->first_page='首页';
    			$this->last_page='尾页';
    			return $this->first_page().' '.$this->pre_page().' '.$this->next_page().' '.$this->last_page().' 页次:<strong>'.$this->nowindex.'</strong>/'.$this->totalpage.'  共有' . $this->totalnum . '条记录   ';
    			break;
			case '7':
				$this->pre_page='<font>上一页</font>';
				$this->next_page='<font>下一页</font>';
    			return $this->pre_page("disabled").$this->very_page(13).$this->next_page("disabled");
    			break;
			
  		}
  
 	}
	
	
 	/**
   	* 出错处理方式
 	*/
 	function error($function,$errormsg){
    	die('Error in file <b>'.__FILE__.'</b> ,Function <b>'.$function.'()</b> :'.$errormsg);
 	}
}
?>