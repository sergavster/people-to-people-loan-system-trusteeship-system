
{if $t == "new" || $t == "edit"}
{literal}
<script>
function check_form(){
	 var frm = document.forms['form1'];
	 var title = frm.elements['title'].value;
	 var content = frm.elements['content'].value;
	 var errorMsg = '';
	  if (title.length == 0 ) {
		errorMsg += '标题必须填写' + '\n';
	  }
	  
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return true;
	  }
}

function jump_url(){
	if (document.getElementById('jump_url').style.display == ""){
		document.getElementById('jump_url').style.display = "none";
		document.getElementById('jump_id').style.display = "";
	}else{
		document.getElementById('jump_url').style.display = "";
		document.getElementById('jump_id').style.display = "none";
	}
}
</script>
{/literal}
<form name="form1" method="post" action="{$url}/{ if $t == "edit" }update{else}add{/if}{$site_url}" onsubmit="return check_form();" enctype="multipart/form-data" >
<table width="100%" border="0"  cellspacing="1" bgcolor="#CCCCCC">
	<tr>
		<td colspan="2" bgcolor="#ffffff" class="tr">
			<div class="fl"><strong>{$module_name}</strong> {$site_name} -> { if $t == "edit" }修改{else}添加{/if}内容</div>
			<div class="fr">{if $site_url!=""}<a href="{$query_site}&q=site/edit&site_id={$magic.request.site_id}">修改栏目</a> | {/if}<a href="{$url}/new{$site_url}">添加内容</a> | <a href="{$url}{$site_url}">内容列表</a> </div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="right" bgcolor="#ffffff">标题：</td>
		<td align="left"  class="main_td1" >
			<input type="text" name="title"  class="input_border" value="{ $result.title}" size="30" />  
			<input type="checkbox" onclick="jump_url()" {if $result.is_jump=="1"} checked="checked"{/if} name="is_jump" value="1"/> 跳转
		</td>
	</tr>
	<tr>
		<td width="15%" align="right" bgcolor="#ffffff">定义属性：</td>
		<td align="left"  class="main_td1" >{loop table="flag" order="`order` desc" var="var"}<input type="checkbox" name="flag[]" value="{$var.nid}" {$var.nid|checked:$result.flag } />{$var.name} {/loop}
		</td>
	</tr>
	<tr id="jump_url" style="{if $result.is_jump!=1}display:none{/if}" >
		<td width="15%" align="right" bgcolor="#ffffff">跳转网址：</td>
		<td align="left" class="main_td1" ><input type="text" name="jumpurl"  class="input_border" value="{ $result.jumpurl}" size="30" /></td>
	</tr>
	<tr >
		<td width="15%" align="right" bgcolor="#ffffff">所属栏目：</td>
		<td align="left" class="main_td1" ><select name="site_id"><option value="0">默认类型</option>{foreach from=$site_list item=item key=key}
<option value="{ $key}" {if $result.site_id == $key} selected="selected"{/if} >-{$item.pname}</option>
{ /foreach}</select></td>
	</tr>
	<tr >
		<td width="15%" align="right" bgcolor="#ffffff">文章来源：</td>
		<td align="left" class="main_td1" ><input type="text" name="source"  class="input_border" value="{ $result.source}" size="30" /></td>
	</tr>
	<tr >
		<td width="15%" align="right" bgcolor="#ffffff">作者：</td>
		<td align="left" class="main_td1" ><input type="text" name="author"  class="input_border" value="{ $result.author}" size="30" /></td>
	</tr>
	<tr >
		<td width="15%" align="right" bgcolor="#ffffff">发布时间：</td>
		<td align="left" class="main_td1" ><input type="text" name="publish"  class="input_border" value="{ $result.publish|default:"nowdate"}" size="30" /></td>
	</tr>
	<tbody id="jump_id"  style="{if $result.is_jump==1}display:none{/if}">
	<tr {if $field.litpic==false}style="display:none"{/if}>
		<td width="15%" align="right" bgcolor="#ffffff">缩略图：</td>
		<td align="left" class="main_td1" ><input type="file" name="litpic" size="30" class="input_border"/>{if $result.litpic!=""}<a href="./{$result.litpic}" target="_blank" title="有图片"><img src="{ $tpldir }/images/ico_1.jpg" border="0"  /></a>{/if}</td>
	</tr>
	<tr>
		<td align="right" bgcolor="#ffffff">状态：</td>
		<td align="left" class="main_td1"><input type="radio" name="status" value="0"  { if $result.status == 0 }checked="checked"{/if}/>隐藏 <input type="radio" name="status" value="1"  { if $result.status ==1 ||$result.status ==""}checked="checked"{/if}/>显示 </td>
	</tr>
	<tr>
		<td width="15%" align="right" bgcolor="#ffffff">排序:</td>
		<td align="left" class="main_td1"><input type="text" name="order"  class="input_border" value="{ $result.order|default:10}" size="10" /></td>
	</tr>
	<tr {if $field.summary==false}style="display:none"{/if}>
		<td align="right" bgcolor="#ffffff">内容简介:</td>
		<td align="left" class="main_td1"><textarea name="summary" cols="45" rows="5">{$result.summary}</textarea></td>
	</tr>
	<tr {if $field.content==false}style="display:none"{/if}>
		<td align="right" bgcolor="#ffffff">内容:</td>
		<td align="left" class="main_td1">{editor name="content" type="sinaeditor" value="$result.content"}</td>
	</tr>
	{foreach from=$input item=item}
	<tr >
		<td align="right" bgcolor="#ffffff">{$item.0}:</td>
		<td align="left" class="main_td1">{$item.1}</td>
	</tr>
	{/foreach}
	</tbody>
	<tr>
		<td bgcolor="#ffffff" colspan="2"  align="center">
		{ if $t == "edit" }<input type="hidden" name="id" value="{ $result.id }" />{/if}
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
		</td>
	</tr>
</table>
</form>
{elseif $t == "view"}
<table width="100%" border="0"  cellspacing="1" bgcolor="#CCCCCC">
	<tr>
		<td colspan="2" bgcolor="#ffffff" class="tr">
			<div class="fl"><strong>{$module_name}</strong> {$site_name} -> 内容查看</div>
			<div class="fr">{if $site_url!=""}<a href="{$query_site}&q=site/edit&site_id={$magic.request.site_id}">修改栏目</a> | {/if}<a href="{$url}/new{$site_url}">添加内容</a> | <a href="{$url}{$site_url}">内容列表</a> </div>
		</td>
	</tr>
	<tr>
		<td width="15%" align="right" bgcolor="#ffffff">标题：</td>
		<td align="left"  class="main_td1" >{$result.title}
			
		</td>
	</tr>
	<tr id="jump_url" style="{if $result.is_jump!=1}display:none{/if}">
		<td width="15%" align="right" bgcolor="#ffffff">跳转网址：</td>
		<td align="left" class="main_td1" >{ $result.jumpurl}</td>
	</tr>
	<tr >
		<td width="15%" align="right" bgcolor="#ffffff">所属栏目：</td>
		<td align="left" class="main_td1" >{$result.site_name|default:"默认栏目" }</select></td>
	</tr>
	{if $result.is_jump!=1}
	{if $result.litpic!=""}
	<tr {if $field.litpic==false}style="display:none"{/if}>
		<td width="15%" align="right" bgcolor="#ffffff">缩略图：</td>
		<td align="left" class="main_td1" >{if $result.litpic!=""}<a href="./{$result.litpic}" target="_blank" title="点击查看大图" ><img src="./{$result.litpic}" border="0" width="100" alt="点击查看大图" title="点击查看大图" /></a>{/if}</td>
	</tr>
	{/if}
	<tr>
		<td align="right" bgcolor="#ffffff">状态：</td>
		<td align="left" class="main_td1">{ if $result.status == 0 }隐藏{else}显示{/if} </td>
	</tr>
	<tr>
		<td width="15%" align="right" bgcolor="#ffffff">排序:</td>
		<td align="left" class="main_td1">{ $result.order|default:10}</td>
	</tr>
	<tr>
		<td width="15%" align="right" bgcolor="#ffffff">文章来源:</td>
		<td align="left" class="main_td1">{ $result.source}</td>
	</tr>
	<tr>
		<td width="15%" align="right" bgcolor="#ffffff">作者:</td>
		<td align="left" class="main_td1">{ $result.author}</td>
	</tr>
	<tr {if $field.summary==false}style="display:none"{/if}>
		<td align="right" bgcolor="#ffffff">内容简介:</td>
		<td align="left" class="main_td1">{$result.summary}</td>
	</tr>
	<tr {if $field.content==false}style="display:none"{/if}>
		<td align="right" bgcolor="#ffffff">内容:</td>
		<td align="left" class="main_td1">{$result.content}</td>
	</tr>
	{foreach from=$input item=item}
	<tr >
		<td align="right" bgcolor="#ffffff">{$item.0}:</td>
		<td align="left" class="main_td1">{$item.1}</td>
	</tr>
	{/foreach}
	<tr >
		<td align="right" bgcolor="#ffffff">点击次数/评论:</td>
		<td align="left" class="main_td1">{$result.hits}/{$result.comment}</td>
	</tr>
	{/if}
	<tr >
		<td align="right" bgcolor="#ffffff">添加时间/IP:</td>
		<td align="left" class="main_td1">{$result.addtime|date_format:'Y-m-d'}/{$result.addip}</td>
	</tr>
	<tr >
		<td align="right" bgcolor="#ffffff">添加人:</td>
		<td align="left" class="main_td1">{$result.username}</td>
	</tr>
	<tr>
		<td bgcolor="#ffffff" colspan="2"  align="center">
		{ if $t == "edit" }<input type="hidden" name="id" value="{ $result.id }" />{/if}
		<input type="button"  name="submit" value="返回上一页" onclick="javascript:history.go(-1)" />
		<input type="button"  name="reset" value="修改内容" onclick="javascript:location.href('{$url}/edit{$site_url}&id={$result.id}')"/>
		</td>
	</tr>
</table>
</form>
{else}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="{$url}/action{$site_url}" method="post">
	  <tr>
			<td colspan="8" bgcolor="#ffffff" class="main_tr" >
			<div  class="fl"><strong>{$module_name}</strong> {$site_name}-> 内容列表</div><div class="fr">{if $site_url!=""}<a href="{$query_site}&q=site/edit&site_id={$magic.request.site_id}">修改栏目</a> | {/if}<a href="{$url}/new{$site_url}">添加内容</a> | <a href="{$url}{$site_url}">内容列表</a></div>
			</td>
		</tr>
		<tr >
			<td width="" class="main_td"><input type="checkbox" name="allcheck" onclick="checkFormAll(this.form)"/></td>
			<td width="" class="main_td">ID</td>
			<td width="*" class="main_td">标题</td>
			<td width="" class="main_td">栏目类型</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">排序</td>
			<td width="" class="main_td">属性</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{ foreach  from=$list key=key item=item}
		<tr >
			<td class="main_td1" align="center" width="40"><input type="checkbox" name="aid[]" id="aid[]" value="{$item.id}"/></td>
			<td class="main_td1" align="center" width="30">{ $item.id}</td>
			<td class="main_td1" align="center">{$item.title|truncate:34}</td>
			<td class="main_td1" align="center" width="80">{$item.site_name|default:-}</td>
			<td class="main_td1" align="center" width="50">{ if $item.status ==1}<a href="{$url}{$site_url}&status=0&id={ $item.id}">显示</a>{else}<a href="{$url}{$site_url}&status=1&id={ $item.id}">隐藏</a>{/if}</td>
			<td class="main_td1" align="center" width="50"><input type="text" name="order[]" value="{$item.order}" size="3" /><input type="hidden" name="id[]" value="{$item.id}" /><input type="hidden" name="flag[]" value="{$item.flag}" /></td>
			<td class="main_td1" align="center" width="70">{$item.flagname|default:-}</td>
			<td class="main_td1" align="center" width="130"><a href="{$url}/preview{$site_url}&id={$item.id}" target="_blank">预览</a> <a href="{$url}/view{$site_url}&id={$item.id}">查看</a> <a href="{$url}/edit{$site_url}&id={$item.id}">修改</a> <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$url}/del{$site_url}&id={$item.id}'">删除</a></td>
		</tr>
		{ /foreach}
		<tr>
			<td colspan="8" bgcolor="#ffffff" class="main_tr" align="left" height="40" >
			&nbsp;&nbsp; <select name="type">
			<option value="0">排序</option>
			<option value="1">显示</option>
			<option value="2">隐藏</option>
			<option value="3">推荐</option>
			<option value="4">头条</option>
			<option value="5">幻灯片</option>
			<option value="6">删除</option>&nbsp;&nbsp;&nbsp;
			</select> <input type="submit" value="确认操作" /> 排序不用全选
			</td>
		</tr>
		<tr>
			<td colspan="8" bgcolor="#ffffff" class="main_tr" align="right" >
			{$page} 
			</td>
		</tr>

	</form>	
		
		{literal}
				<script>
var con_id = Array();
function checkFormAll(form) {	
	if(form.allcheck.checked==true){
		con_id.length=0;
	}
	for (var i=1;i<form.elements.length;i++)    {
		 if(form.elements[i].type=="checkbox"){ 
            e=form.elements[i]; 
            e.checked=(form.allcheck.checked)?true:false; 
			if(form.allcheck.checked==true){
				con_id[con_id.length] = e.value;
			}else{
				con_id.length=0;
			}
        } 
	}
}
</script>

				{/literal}
</table>
{/if}