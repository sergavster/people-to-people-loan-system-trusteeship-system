{if $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return check_form();" enctype="multipart/form-data" >
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }编辑{else}添加{/if}友情链接</strong></div>
	
	<div class="module_border">
		<div class="l">网站名称：</div>
		<div class="c">
			<input type="text" name="webname"  class="input_border" value="{ $_A.links_result.webname}" size="30" />  
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">类别：</div>
		<div class="c">
			<select name="type_id">
			{foreach from=$_A.links_type_list item=item}
			<option  value="{ $item.id}" {if $item.id==$_A.links_result.type_id} selected="selected"{/if} />{ $item.typename}</option>
			
			{/foreach}
			</select>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			<input type="radio" name="status" value="0"  { if $_A.links_result.status == 0 }checked="checked"{/if}/>隐藏 <input type="radio" name="status" value="1"  { if $_A.links_result.status ==1 ||$_A.links_result.status ==""}checked="checked"{/if}/>显示 </div>
	</div>
	
	<div class="module_border">
		<div class="l">排序：</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{ $_A.links_result.order|default:10}" size="10" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">LOGO地址:</div>
		<div class="c">
			<input type="text" name="logo"  class="input_border" value="{ $_A.links_result.logo}" size="30" /> 请填写url地址
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">LOGO上传:</div>
		<div class="c">
			<input type="file" name="logoimg"  class="input_border" size="20" />{if $_A.links_result.logoimg!=""}<a href="./{$_A.links_result.logoimg}" target="_blank" title="有图片"><img src="{ $tpldir }/images/ico_1.jpg" border="0"  /></a>{/if}
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">网站地址:</div>
		<div class="c">
			<input type="text" name="url"  class="input_border" value="{ $_A.links_result.url}" size="30" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">联系人:</div>
		<div class="c">
			<input type="text" name="linkman"  class="input_border" value="{ $_A.links_result.linkman}" size="20" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">网站简介:</div>
		<div class="c">
			<textarea name="summary" cols="40" rows="5">{$_A.links_result.summary}</textarea>
		</div>
	</div>
	
	<div class="module_submit" >
		{ if $_A.query_type == "edit" }<input type="hidden" name="id" value="{ $_A.links_result.id }" />{/if}
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
		</div>
	</div>
	</form>
</div>

{literal}
<script>
function check_form(){
	 var frm = document.forms['form1'];
	 var webname = frm.elements['webname'].value;
	 var url = frm.elements['url'].value;
	 var errorMsg = '';
	  if (webname.length == 0 ) {
		errorMsg += '网站标题必须填写' + '\n';
	  }
	  if (url.length == 0 ) {
		errorMsg += '链接地址不能为空' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return submit_fool();
	  }
}

</script>
{/literal}
{elseif $_A.query_type == "type"}
<form name="form1" method="post" action="" onsubmit="return submit_fool()">
<table width="100%" border="0"  cellspacing="1" bgcolor="#CCCCCC">
<tr >
		<td width="" class="main_td">ID</td>
		<td width="*" class="main_td">名称</td>
		<td width="" class="main_td">操作</td>
	</tr>
	{ foreach  from=$_A.links_type_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.id}</td>
		<td class="main_td1" align="left">&nbsp;&nbsp;&nbsp;<input type="text" value="{$item.typename}" name="typename[]" /><input type="hidden" name="id[]" value="{$item.id}" /></td>
		<td class="main_td1" align="center" width="160"><a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/type&del_id={$item.id}'">删除</a> </td>
	</tr>
	{ /foreach}
	<tr >
		<td width="" class="main_td" colspan="3">新增一个类型：<input type="text" name="typename1" /></td>
	</tr>

<tr>
	<td bgcolor="#ffffff" colspan="3"  align="center">
	<input type="submit"  name="submit" value="确认提交" />
	</tr>
<tr>
</table>
</form>
{elseif $_A.query_type == "view"}

{else}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
  <form action="{$_A.query_url}/order" method="post" onsubmit="return submit_fool()">
	<tr >
		<td width="" class="main_td">ID</td>
		<td width="*" class="main_td">网站名称</td>
		<td width="" class="main_td">类型</td>
		<td width="" class="main_td">状态</td>
		<td width="" class="main_td">排序</td>
		<td width="" class="main_td">添加时间</td>
		<td width="" class="main_td">网站logo</td>
		<td width="" class="main_td">操作</div>
	</div>
	{ foreach  from=$_A.links_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.id}</td>
		<td class="main_td1" align="center">{$item.webname}</td>
		<td class="main_td1" align="center" width="60">{$item.typename }</td>
		<td class="main_td1" align="center" width="50">{ if $item.status ==1}显示{else}隐藏{/if}</td>
		<td class="main_td1" align="center" width="50"><input type="text" value="{$item.order|default:10}" name="order[]" size="5" /><input type="hidden" name="id[]" value="{$item.id}" /></td>
		<td class="main_td1" align="center" width="90">{$item.addtime|date_format:"Y-m-d"}</td>
		<td class="main_td1" align="center" width="80">{if $item.logoimg!=""}<a href="./{$item.logoimg}" target="_blank"><img height="20" src="./{$item.logoimg}" border="0" /></a>{else}无logo{/if}</td>
		<td class="main_td1" align="center" width="130"><a href="{$_A.query_url}/edit&id={$item.id}&a=system">修改</a> / <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/del&id={$item.id}'">删除</a></div>
	</div>
	{ /foreach}
	<tr >
		<td colspan="8" class="page">
			{$_A.showpage}
		</td>
	</tr>
	</form>	
</table>
{/if}