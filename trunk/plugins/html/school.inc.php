<? 

if (isset($_GET['zhuanye']) ){
	if ($_GET['zhuanye']!=''){
		$id = (int)$_GET["zhuanye"];
		$sql = "select * from {school} where pid=".$id;
		$result = $mysql->db_fetch_arrays($sql);
		$category['type'] = "";
		$category['name'] = gbk2utf8("请选择");
		$categorys[0] = $category;
		if ($result!=false){
			foreach ($result as $key => $row){
				$category = array();
				$category['type'] = $row['type'];
				$category['name'] = gbk2utf8($row['name']);
				$categorys[$key+1] = $category;
			}
		}
		$json = json_encode($categorys);
		echo $json;
		exit;
	}else{
		echo "";
		exit;
	}
}


$sql = "select * from {area} where pid =0";
	$province = $mysql->db_fetch_arrays($sql);
	
	if (!isset($_REQUEST['id'])){
		$id = 1;
	}else{
		$id = empty($_REQUEST['id'])?1:$_REQUEST['id'];
	}
	$id=(int)$id;
	//判断学校的类型
	$type = "hight";
	if (isset($_REQUEST['type'])){
		$type = $_REQUEST['type'];
	}
	if ($type == "hight"){
		$type_id = 1;
		$type_name = "大学";
	}elseif ($type == "mid"){
		$type_id = 2;
		$type_name = "高中";
	}
	$sql = "select * from {school} where province =$id and type=$type_id";
	$college = $mysql->db_fetch_arrays($sql);
	
	
	
?>
<div>
<div style=" border:1px solid #CCCCCC; height:70px;; overflow:hidden; text-align:left; padding:5px; line-height:23px;">
<ul>
	<? foreach ($province as $key=> $value){?>
	<a href="javascript:void(0);" onclick='tipsWindown("选择<? echo $type_name;?>","url:get?plugins/index.php?q=school&type=<? echo $type;?>&name=<? echo $_REQUEST['name'];?>&id=<? echo $value['id'];?>",400,300,"false","","false","text")'> <? echo $value['name'];?></a>  |  
	<? }?>
</ul>
</div>

<div style=" border:1px solid #CCCCCC; height: 120px; overflow: auto; text-align:left; padding:5px; line-height:23px; margin-top:10px;">
<ul>
	<? foreach ($college as $key=> $value){?>
	<li style="width:120px; float:left"><a href="javascript:void(0);" onclick="change('<? echo $value['name'];?>','<? echo $value['id'];?>')"><? echo $value['name'];?></a></li>
	<? }?>
</ul>
</div>
<IFRAME 
style="Z-INDEX: -1; FILTER: progid:DXImageTransform.Microsoft.Alpha(style=0,opacity=0); LEFT: 0px; VISIBILITY: inherit; WIDTH: 400px; POSITION: absolute; TOP: 0px; HEIGHT: 300px;border:none " 
src=""></IFRAME>
</div>

<script>
function change(val,id){
	var type_id = <? echo $type_id; ?>;
	var name = "<? echo $_REQUEST['name']; ?>"
	var atype = "<? echo $_REQUEST['atype']; ?>";
	$("#"+name).val(val);
	if (type_id==1 && atype==1){
		
		var count = 0;
		$.ajax({
			url:"plugins/index.php",
			dataType:'json', 
			data:"q=school&zhuanye="+id,
			success:function(json){
				$("#zhuanye option").each(function(){
					$(this).remove();				 
				});
				$("#xiaoqu option").each(function(){
					$(this).remove();				 
				});
				$(json).each(function(){
					if(json[count].type==6){
					$("<option value='"+json[count].name+"'>"+json[count].name+"</option>").appendTo("#zhuanye");
					}else{
					$("<option value='"+json[count].name+"'>"+json[count].name+"</option>").appendTo("#xiaoqu");
					}
					count++;
				});
				
			}
		});
	}
	$("#windownbg").remove();
	$("#windown-box").fadeOut("slow",function(){$(this).remove();});
}

</script>
<? exit;?>