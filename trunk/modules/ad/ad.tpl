
{if $_A.query_type=="view"}
<div class="module_add">

	<div class="module_title"><strong>查看广告</strong></div>
	
	<div class="module_border">
		<div class="l">名称：</div>
		<div class="c">
			{$_A.ad_result.name}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">标识名：</div>
		<div class="c">
			{$_A.ad_result.nid}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">是否限时：</div>
		<div class="c">
			{ if $item.timelimit ==1}限时{else}不限时{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">开始时间：</div>
		<div class="c">
			{$_A.ad_result.firsttime|date_format:"Y-m-d H:i:s"}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">结束时间：</div>
		<div class="c">
			{$_A.ad_result.endtime|date_format:"Y-m-d H:i:s"}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">广告内容：</div>
		<div class="c">
			{$_A.ad_result.content}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">结束内容：</div>
		<div class="c">
			{$_A.ad_result.endcontent}
		</div>
	</div>
</div>

{elseif $_A.query_type == "new" || $_A.query_type == "edit"}

{literal}
<script>
function check_ad(){
	 var frm = document.forms['form_user'];
	 var adname = frm.elements['name'].value;
	 var nid = frm.elements['nid'].value;
	 var errorMsg = '';
	  if (adname.length == 0 ) {
		errorMsg += '广告名称不能为空' + '\n';
	  }
	    if (nid.length == 0 ) {
		errorMsg += '广告标识名不能为空' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return true;
	  }
}

</script>
{/literal}
<form name="form_user" method="post" action="" onsubmit="return check_ad();" enctype="multipart/form-data" >
<div class="module_add">
	<div class="module_title"><strong>{if $_A.query_type == "new"}添加{else}修改{/if}广告</strong></div>
	
	<div class="module_border">
		<div class="l">广告名：</div>
		<div class="c">
			<input name="name" type="text"  class="input_border" value="{$_A.ad_result.name}" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">广告标识名：</div>
		<div class="c">
			<input name="nid" type="text" class="input_border" value="{$_A.ad_result.nid}"/>广告代码请调用这个标识名
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">是否限时：</div>
		<div class="c">
			{ if $item.timelimit ==1}限时{else}不限时{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">时间限制：</div>
		<div class="c">
			 <input name="timelimit" type="radio" value="0"  { if $_A.ad_result.timelimit==1 || $_A.ad_result.timelimit==""} checked="checked"{/if} />永不过期<input name="timelimit" type="radio" value="1" { if $_A.ad_result.timelimit=="0"} checked="checked"{/if}/>在设计时间内有效
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">图片：</div>
		<div class="c">
			<input type="file" name="litpic" />{if $_A.ad_result.litpic!=""}<a href="./{ $_A.ad_result.litpic}" target="_blank" title="有图片"><img src="{ $tpldir }/images/ico_1.jpg" border="0"  /></a><input type="checkbox" name="clearlitpic" value="1" />去掉缩略图{/if}</div>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">开始时间：</div>
		<div class="c">
			<input name="firsttime" value="{ $_A.ad_result.firsttime|default:$firsttime|date_format:"Y-m-d H:i:s" }" type="text"  class="input_border" onclick="change_picktime('yyyy-MM-dd HH:mm')" readonly=""/>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">结束时间：</div>
		<div class="c">
			<input name="endtime" type="text" value="{ $_A.ad_result.endtime|default:$endtime|date_format:"Y-m-d H:i:s" }" class="input_border" onclick="change_picktime('yyyy-MM-dd HH:mm')" readonly=""/>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">广告内容：</div>
		<div class="c">
			<textarea name="content" rows="5" cols="40">{$_A.ad_result.content}</textarea>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">结束内容：</div>
		<div class="c">
			<textarea name="endcontent" rows="5" cols="40">{$_A.ad_result.endcontent}</textarea>
		</div>
	</div>
	
	<div class="module_submit" >
		{ if $_A.query_type == "edit" }<input type="hidden" name="id" value="{ $_A.ad_result.id }" />{/if}
				<input type="submit" value="确认提交" />
				<input type="reset" name="reset" value="重置表单" />
	</div>
	
</div>
</form>	

{else if $_A.query_type=="list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr >
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">广告名</td>
			<th width="" class="main_td">广告标识名</th>
			<th width="" class="main_td">是否限时</th>
			<th width="" class="main_td">结束时间</th>
			<th width="" class="main_td">广告代码</th>
			<td width="" class="main_td">操作</td>
		</tr>
		{ foreach  from=$_A.ad_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1" align="center">{ $item.id}</td>
			<td class="main_td1" align="center">{$item.name}</td>
			<td class="main_td1" align="center">{$item.nid}</td>
			<td class="main_td1" align="center" width="70">{ if $item.timelimit ==1}限时{else}不限时{/if}</td>
			<td class="main_td1" align="center" width="140">{$item.endtime|date_format:"Y-m-d H:i:s"}</td>
			<td class="main_td1" align="center" width="130">{$item.adcode}</td>
			<td class="main_td1" align="center" width="130"> <a href="{$_A.query_url}/view&id={$item.id}">查看</a> / <a href="{$_A.query_url}/edit&id={$item.id}">修改</a> / <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/del&id={$item.id}'">删除</a> </td>
		</tr>
		{ /foreach}
		<tr>
			<td colspan="7" bgcolor="#ffffff"  class="page" align="right" >
			{$_A.showpage}
			</td>
		</tr>
</table>
{/if}