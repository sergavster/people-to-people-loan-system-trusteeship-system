{if $t == "new" || $t == "edit"}
<div class="module_add">
	
	<form name="form1" method="post" action="{$url}/{ if $t == "edit" }update{else}add{/if}{$site_url}" onsubmit="return check_form();" enctype="multipart/form-data" >
	<div class="module_title"><strong>{ if $t == "edit" }编辑{else}添加{/if}帮助</strong></div>
	

	<div class="module_border">
		<div class="l">标题：</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{ $result.name}" size="30" />  
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">定义属性：</div>
		<div class="c">
			{loop table="flag" order="`order` desc" var="var"}<input type="checkbox" name="flag[]" value="{$var.nid}" {$var.nid|checked:$result.flag } />{$var.name} {/loop}
		</div>
	</div>

	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			<input type="radio" name="status" value="0"  { if $result.status == 0 }checked="checked"{/if}/>隐藏 <input type="radio" name="status" value="1"  { if $result.status ==1 ||$result.status ==""}checked="checked"{/if}/>显示 </div>
	</div>

	<div class="module_border">
		<div class="l">所属类型：</div>
		<div class="c">
			<select name="type_id"><option value="0">默认类型</option>{foreach from=$list item=item key=key}
<option value="{$item.type_id}" {if $result.type_id == $item.type_id} selected="selected"{/if} >-{$item.aname}</option>
{ /foreach}</select></div>
	</div>

	<div class="module_border">
		<div class="l">排序:</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{ $result.order|default:10}" size="10" />
		</div>
	</div>

	<div class="module_border" {if $field.litpic==false}style="display:none"{/if} id="jump_url">
		<div class="l">缩略图：</div>
		<div class="c">
			<input type="file" name="litpic" size="30" class="input_border"/>{if $result.litpic!=""}<a href="./{$result.litpic}" target="_blank" title="有图片"><img src="{ $tpldir }/images/ico_1.jpg" border="0"  /></a><input type="checkbox" name="clearlitpic" value="1" />去掉缩略图{/if}</div>
	</div>
	
	<div class="module_border">
		<div class="l">文章来源：</div>
		<div class="c">
			<input type="text" name="source"  class="input_border" value="{ $result.source}" size="30" /></div>
	</div>

	<div class="module_border">
		<div class="l">作者：</div>
		<div class="c">
			<input type="text" name="author"  class="input_border" value="{ $result.author}" size="30" /></div>
	</div>

	<div class="module_border">
		<div class="l">所在地：</div>
		<div class="c">
			<script src="./plugins/index.php?&q=procity&area_id={$result.area|default:$magic.session.result.area}" type='text/javascript' language="javascript"></script> </div>
	</div>

	<div class="module_border">
		<div class="l">简介:</div>
		<div class="c">
			<textarea name="summary" cols="45" rows="5">{$result.summary}</textarea>
		</div>
	</div>

	<div class="module_border" >
		<div class="l">内容:</div>
		<div class="c">
			{editor name="content" type="sinaeditor" value="$result.content"}
		</div>
	</div>

	<div class="module_submit" >
		{ if $t == "edit" }<input type="hidden" name="id" value="{ $result.id }" />{/if}
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
	</form>
</div>
{literal}
<script>
function check_form(){
	 var frm = document.forms['form1'];
	 var title = frm.elements['name'].value;
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

{elseif $t == "view"}
<div class="module_add">
	
	<form name="form1" method="post" action="{$url}/{ if $t == "edit" }update{else}add{/if}{$site_url}" onsubmit="return check_form();" enctype="multipart/form-data" >
	<div class="module_title"><strong>帮助查看</strong></div>

	<div class="module_border">
		<div class="l">标题：</div>
		<div class="c">
			{$result.title}
		</div>
	</div>

	<div class="module_border">
		<div class="l">跳转网址：</div>
		<div class="c">
			{ $result.jumpurl}</div>
	</div>

	<div class="module_border">
		<div class="l">所属栏目：</div>
		<div class="c">
			{$result.site_name|default:"默认栏目" }</select>
		</div>
	</div>

	{if $result.is_jump!=1}
	{if $result.litpic!=""}
	<div class="module_border">
		<div class="l">缩略图：</div>
		<div class="c">
			{if $result.litpic!=""}<a href="./{$result.litpic}" target="_blank" title="点击查看大图" ><img src="./{$result.litpic}" border="0" width="100" alt="点击查看大图" title="点击查看大图" /></a>{/if}</div>
	</div>

	{/if}
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			{ if $result.status == 0 }隐藏{else}显示{/if}
		 </div>
	</div>

	<div class="module_border">
		<div class="l">排序:</div>
		<div class="c">
			{ $result.order|default:10}
		</div>
	</div>

	<div class="module_border">
		<div class="l">文章来源:</div>
		<div class="c">
			{ $result.source}</div>
	</div>

	<div class="module_border">
		<div class="l">作者:</div>
		<div class="c">
			{ $result.author}</div>
	</div>

	<div class="module_border">
		<div class="l">简介:</div>
		<div class="c">
			{$result.summary}</div>
	</div>

	<div class="module_border">
		<div class="l">内容:</div>
		<div class="c">
			{$result.content}</div>
	</div>

	{foreach from=$input item=item}
	<div class="module_border">
		<div class="l">{$item.0}:</div>
		<div class="c">
			{$item.1}
		</div>
	</div>

	{/foreach}
	<div class="module_border">
		<div class="l">点击次数/评论:</div>
		<div class="c">
			{$result.hits}/{$result.comment}</div>
	</div>

	{/if}
	<div class="module_border">
		<div class="l">添加时间/IP:</div>
		<div class="c">
			{$result.addtime|date_format:'Y-m-d'}/{$result.addip}</div>
	</div>

	<div class="module_border">
		<div class="l">添加人:</div>
		<div class="c">
			{$result.username}</div>
	</div>

	<div class="module_submit" >
		{ if $t == "edit" }<input type="hidden" name="id" value="{ $result.id }" />{/if}
		<input type="button"  name="submit" value="返回上一页" onclick="javascript:history.go(-1)" />
		<input type="button"  name="reset" value="修改帮助" onclick="javascript:location.href('{$url}/edit{$site_url}&id={$result.id}')"/>
	</div>
	</form>
</div>
{elseif 'type'==$t}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr >
		<td width="" class="main_td">ID</td>
		<td width="*" class="main_td">类型名称</td>
		<td width="" class="main_td">状态</td>
		<td width="" class="main_td">排序</td>
		<td width="" class="main_td">操作</td>
	</tr>
	<form action="{ $url}/type_order" method="post" name="form1" >
	{ foreach  from=$result key=key item=item}
	<tr {if $key%2==1}class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.type_id}</td>
		<td class="main_td1" align="center">{$item.name|truncate:34}</td>
		<td class="main_td1" align="center">{ if $item.status ==1}<a href="{$url}/type{$site_url}&status=0&id={ $item.type_id}">显示</a>{else}<a href="{$url}/type{$site_url}&status=1&id={ $item.type_id}">隐藏</a>{/if}</td>
		<td class="main_td1" align="center" ><input type="text" name="order[{$key}]" value="{$item.order}" size="3" /><input type="hidden" name="type_id[{$key}]" value="{$item.type_id}" /></td>
		<td class="main_td1" align="center" ><a href="{$url}/type_edit{$site_url}&id={$item.type_id}" >修改</a> <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$url}/type_del{$site_url}&id={$item.type_id}'">删除</a></td>
	</tr><input type="hidden" name="flag[{$key}]" value="{$item.flag}" />
	{ /foreach}
	<tr>
		<td colspan="5"  class="submit">
		<input type="submit" value=" 提 交 "  name="submit_ok" />&nbsp;&nbsp;
					<input type="button" name="new" value="添加类型" onclick="javascript:window.location.href='{$url}/type_new';" />
		</td>
	</tr>
	</form>
	<tr>
		<td colspan="5" class="page">
			{$page}
		</td>
	</tr>
</table>
{elseif 'type_new'==$t || 'type_edit'==$t}
<div class="module_add">
	
	<form action="{ $url}/{ if $t=='type_edit'}type_update{ else}type_add{ /if}" method="post" name="form1"  enctype="multipart/form-data">
	<div class="module_title"><strong>{ if $t == "type_edit" }编辑{else}添加{/if}类型</strong></div>
	 <div class="module_border">
		<div class="l">所属类型：</div>
		<div class="c">
			<select name="type_id"><option value="0">默认类型</option>{foreach from=$list item=item key=key}
<option value="{$item.type_id}" {if $result.type_id == $item.type_id} selected="selected"{/if} >-{$item.aname}</option>
{ /foreach}</select></div>
	</div>
	
	 <div class="module_border">
		<div class="l">类型名称 ：</div>
		<div class="c">
			<input type="text" align="absmiddle" name="name" value="{ $result.name}" /> 
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">识别ID(nid)：</div>
		<div class="c">
			<input type="text" align="absmiddle" name="nid"  onkeyup="value=value.replace(/[^a-zA-Z_]/g,'')" value="{$result.nid}"/>只能为 字母和下划线（_）
				
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">状&nbsp;&nbsp;&nbsp; 态 ：</div>
		<div class="c">
			<input type="radio" value="0" name="status" { if $result.status==0}checked="checked"{ /if}/>隐藏
			<input type="radio" value="1" name="status" { if $result.status==1 || $result.status==""}checked="checked"{ /if} />显示
		</div>
	</div>
	
	 <div class="module_border">
		<div class="l">排列顺序：</div>
		<div class="c">
				<input type="text" align="absmiddle" name="order"  onkeyup="value=value.replace(/[^0-9]/g,'')" size="5" value="{$result.order|default:10}"/>
		</div>
	</div>
	
	
	
	<div class="module_border">
		<div class="l">封面模板：</div>
		<div class="c">
			<input name="index_tpl" type="text"  style="width:300px" value="{ $result.index_tpl|default:"help.html"}" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">列表模板：</div>
		<div class="c">
			<input name="list_tpl" type="text"  style="width:300px" value="{ $result.list_tpl|default:"help_list.html"}" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">文章模板：</div>
		<div class="c">
			<input name="content_tpl" type="text"  style="width:300px" value="{$result.content_tpl|default:"help_content.html"}" />
		</div>
	</div>
			  
	{ if $s=="edit"}
	<div class="module_border">
		<div class="l">模板修改：</div>
		<div class="c">
			 <input type="checkbox" value="1" name="update_all" />所属栏目一起修改 <input type="checkbox" value="1" name="update_brother" />同级栏目一起修改
		</div>
	</div>
	 {/if}
	 
	<div class="module_border">
		<div class="l">列表命名规则：</div>
		<div class="c">
			<input name="list_name" type="text"  style="width:300px" value="{$result.list_name|default:"index_[page].html"}" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">文章命名规则：</div>
		<div class="c">
			<input name="content_name" type="text"  style="width:300px" value="{$result.content_name|default:"[id].html"}" />
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">文件保存目录：</div>
		<div class="c">
			<input name="sitedir" type="text"  style="width:300px" value="{$result.sitedir|default:"[nid]"}" />
		</div>
	</div>
	
	{ if $s=="type_new"}
	<div class="module_border">
		<div class="l">目录相对位置：</div>
		<div class="c">
			<input name="referpath" type="radio" value="parent" checked="chekced" />
              上级目录
                            <input name="referpath" type="radio" value="cmspath" />
              CMS根目录
		</div>
	</div>
	{/if}
	
	<div class="module_border">
		<div class="l">文件访问类型：</div>
		<div class="c">
			<input type="radio" name="visit_type" value="0" {if $result.visit_type==0 || $result.visit_type==""} checked="checked"{/if}  title="如：?3/1"/> 动态访问 <input type="radio" name="visit_type" value="1" {if $result.visit_type==1} checked="checked"{/if} title="如：?article/dongtai/1.html"/> 生成html访问 （备注：如果系统设置伪静态为是，则按伪静态的来）
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">title参数：</div>
		<div class="c">
			<textarea name="title" cols="40" rows="3" id="title">{ $result.title}</textarea>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">关键字：</div>
		<div class="c">
			<textarea name="keywords" cols="40" rows="3" id="keywords">{ $result.keywords}</textarea>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">栏目描述：</div>
		<div class="c">
			 <textarea name="description" cols="40" rows="3" id="textarea2">{ $result.description}</textarea>
		</div>
	</div>
	
	
	<div class="module_border">
		<div class="l">栏目内容：</div>
		<div class="c">
			{editor name="content" value="$result.content"}
		</div>
	</div>
	
	<div class="module_submit"><input type="submit" value=" 提 交 "  name="submit_ok" />&nbsp;&nbsp;
					<input name="reset" type="reset"  value=" 重 置 " /><input type="hidden" align="absmiddle" name="type_id" value="{ $result.type_id}" /> </div>
			</form>
 </div>
 
{else}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="{$url}/action{$site_url}" method="post">
	<tr >
		<td width="" class="main_td"><input type="checkbox" name="allcheck" onclick="checkFormAll(this.form)"/></td>
		<td width="" class="main_td">ID</td>
		<td width="*" class="main_td">名称</td>
		<td width="" class="main_td">状态</td>
		<td width="" class="main_td">排序</td>
		<td width="" class="main_td">属性</td>
		<td width="" class="main_td">操作</td>
	</tr>
	{ foreach  from=$result key=key item=item}
	<tr {if $key%2==1}class="tr2"{/if}>
		<td class="main_td1" align="center" ><input type="checkbox" name="aid[{$key}]" value="{$item.id}"/></td>
		<td class="main_td1" align="center" >{ $item.id}</td>
		<td class="main_td1" align="center">{$item.name|truncate:34}</td>
		<td class="main_td1" align="center" >{ if $item.status ==1}<a href="{$url}{$site_url}&status=0&id={ $item.id}">显示</a>{else}<a href="{$url}{$site_url}&status=1&id={ $item.id}">隐藏</a>{/if}</td>
		<td class="main_td1" align="center" ><input type="text" name="order[{$key}]" value="{$item.order}" size="3" /><input type="hidden" name="id[{$key}]" value="{$item.id}" /></td>
		<td class="main_td1" align="center" >{$item.flagname|default:-}{if $item.litpic!=""}图片{/if}</td>
		<td class="main_td1" align="center"><a href="{$url}/edit{$site_url}&id={$item.id}" >修改</a> <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$url}/del{$site_url}&id={$item.id}'">删除</a></td>
	</tr><input type="hidden" name="flag[{$key}]" value="{$item.flag}" />
	{ /foreach}
	<tr>
		<td colspan="7" class="action">
		<div class="floatl">
		 <select name="type">
		<option value="0">排序</option>
		<option value="1">显示</option>
		<option value="2">隐藏</option>
		<option value="3">推荐</option>
		<option value="4">头条</option>
		<option value="5">幻灯片</option>
		<option value="6">删除</option>&nbsp;&nbsp;&nbsp;
		</select> <input type="submit" value="确认操作" /> 排序不用全选
		</div>
		<div class="floatr">关键字：<input type="text" name="keywords" id="keywords" value="{$magic.request.keywords}"/> <input type="button" value="搜索" / onclick="sousuo()"></div>
		</td>
	</tr>
	<tr>
		<td colspan="7" class="page" >
		{$page} 
		</td>
	</tr>
	</form>	
</table>

  <script>
  var url = '{$url}';
	{literal}
	function sousuo(){
		var keywords = $("#keywords").val();
		location.href=url+"&keywords="+keywords;
	}

  </script>
  {/literal}
{/if}