{if $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return check_form();" enctype="multipart/form-data" >
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }编辑{else}添加{/if}内容</strong></div>
	<div class="module_border">
		<div class="l">标题：</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{ $_A.code_result.name}" size="30" />  
			<input type="checkbox" onclick="jump_url()" {if $_A.code_result.is_jump=="1"} checked="checked"{/if} name="is_jump" value="1"/> 跳转
		</div>
	</div>
	<div class="module_border">
		<div class="l">定义属性：</div>
		<div class="c">
			{$_A.code_result.flag|flag:"input"}
		</div>
	</div>
	<div class="module_border" id="jump_url" style="{if $_A.code_result.is_jump!=1}display:none{/if}">
		<div class="l">跳转网址：</div>
		<div class="c">
			<input type="text" name="jumpurl"  class="input_border" value="{ $_A.code_result.jumpurl}" size="30" /></div>
	</div>
	<div class="module_border">
		<div class="l">所属栏目：</div>
		<div class="c">
			<select name="site_id"><option value="0">默认类型</option>{foreach from=$_A.site_code_list item=item key=key}
<option value="{ $item.site_id}" {if $_A.code_result.site_id == $item.site_id || $magic.request.site_id == $item.site_id } selected="selected"{/if} >-{$item.name}</option>
{ /foreach}</select></div>
	</div>
	<div class="module_border"  {if $_A.show_fields.source==false}style="display:none"{/if}>
		<div class="l">文章来源：</div>
		<div class="c">
			<input type="text" name="source"  class="input_border" value="{ $_A.code_result.source}" size="30" /></div>
	</div>
	<div class="module_border" {if $_A.show_fields.author==false}style="display:none"{/if}>
		<div class="l">作者：</div>
		<div class="c">
			<input type="text" name="author"  class="input_border" value="{ $_A.code_result.author}" size="30" /></div>
	</div>
	<div class="module_border"  {if $_A.show_fields.publish==false}style="display:none"{/if}>
		<div class="l">发布时间：</div>
		<div class="c">
			<input type="text" name="publish"  class="input_border" value="{ $_A.code_result.publish|default:"nowdate"}" size="30" onclick="change_picktime('yyyy-MM-dd HH:mm:ss')" readonly=""/></div>
	</div>
	<div id="jump_id"  style="{if $_A.code_result.is_jump==1}display:none{/if}">
	<div class="module_border" {if $_A.show_fields.area==false}style="display:none"{/if}>
		<div class="l">所在地：</div>
		<div class="c">
			<script src="./plugins/index.php?&q=procity&area_id={ $_A.code_result.area|default:$magic.session.result.area}" type='text/javascript' language="javascript"></script> </div>
	</div>
	<div class="module_border" {if $_A.show_fields.litpic==false}style="display:none"{/if} id="jump_url">
		<div class="l">缩略图：</div>
		<div class="c">
			<input type="file" name="litpic" size="30" class="input_border"/>{if $_A.code_result.litpic!=""}<input type="hidden" name="old_litpic" value="{$_A.code_result.litpic}" /><a href="{$_A.code_result.litpic}" target="_blank" title="有图片"><img src="{ $tpldir }/images/ico_1.jpg" border="0"  /></a><input type="checkbox" name="clearlitpic" value="1" />去掉缩略图{/if}
		</div>
	</div>
	<div class="module_border" >
		<div class="l">附件：</div>
		<div class="c">
			<input type="file" name="fujian" size="30" class="input_border"/>{if $_A.code_result.fujian!=""}<input type="hidden" name="old_fujian" value="{$_A.code_result.fujian}" /><a href="{$_A.code_result.fujian}" target="_blank" title="有附件"><img src="{ $tpldir }/images/ico_1.jpg" border="0"  /></a><input type="checkbox" name="clearfujian" value="1" />去掉缩略图{/if}
		</div>
	</div>
	<div class="module_border" {if $_A.show_fields.status==false}style="display:none"{/if}>
		<div class="l">状态：</div>
		<div class="c">
			<input type="radio" name="status" value="0"  {if $_A.code_result.status == 0}checked="checked"{/if}/>隐藏 <input type="radio" name="status" value="1"  { if $_A.code_result.status ==1 ||$_A.code_result.status ==""}checked="checked"{/if}/>显示 
		</div>
	</div>
	<div class="module_border" {if $_A.show_fields.order==false}style="display:none"{/if}>
		<div class="l">排序:</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{ $_A.code_result.order|default:10}" size="10" />
		</div>
	</div>
	<div class="module_border" {if $_A.show_fields.summary==false}style="display:none"{/if}>
		<div class="l">内容简介:</div>
		<div class="c">
			<textarea name="summary" cols="45" rows="5">{ $_A.code_result.summary}</textarea>
		</div>
	</div>
<script charset="gb2312" src="/plugins/editor/kindeditor/kindeditor-min.js"></script>
<script charset="gb2312" src="/plugins/editor/kindeditor/lang/zh_CN.js"></script>
	<div class="module_border" {if $_A.show_fields.content==false}style="display:none"{/if}>
		<div class="l">内容:</div>
		<div class="c">
			<textarea name="contentadmin" id="contentadmin"  style="width:800px;height:400px;visibility:hidden;" >
            {$_A.code_result.content}
			</textarea>
		</div>
	</div>
	<!--
	{foreach from=$_A.code_input item=item}
	<div class="module_border">
		<div class="l">{$item.0}:</div>
		<div class="c">
			{$item.1}
		</div>
	</div>
	{/foreach}
	-->
	</div>
	<div class="module_submit" >
		{if $_A.query_type == "edit" }<input type="hidden" name="id" value="{$_A.code_result.id }" />{/if}
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
	</form>
</div>
{literal}
<script type="text/javascript">
//editor add by weego 20120615 for 网页编辑器
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create('textarea[name="contentadmin"]', {
					allowFileManager : true
				});
			});
function check_form(){
	 var frm = document.forms['form1'];
	 var name = frm.elements['name'].value;
	 var content = frm.elements['content'].value;
	 var errorMsg = '';
	 if(name.length == 0 ) {
		errorMsg += '标题必须填写' + '\n';
	  }
	  if(errorMsg.length > 0){
		alert(errorMsg); return false;
	  }else{  
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


<!-- 查看文章 开始 -->
{elseif $_A.query_type == "view"}
<div class="module_add">
	<form name="form1" method="post" action="{$_A.query_url}/{if $_A.query_type == "edit" }update{else}add{/if}{$_A.site_url}" onsubmit="return check_form();" enctype="multipart/form-data" >
	<div class="module_title"><strong>内容查看</strong></div>
	<div class="module_border">
		<div class="l">标题：</div>
		<div class="c">
			{ $_A.code_result.name}
		</div>
	</div>
{if $_A.code_result.jumpurl!=""}
	<div class="module_border">
		<div class="l">跳转网址：</div>
		<div class="c">
			{$_A.code_result.jumpurl}</div>
	</div>
{/if}
	<div class="module_border">
		<div class="l">所属栏目：</div>
		<div class="c">
			{$_A.code_result.site_name|default:"默认栏目" }</select>
		</div>
	</div>
{if $_A.code_result.flag!=""}
	<div class="module_border">
		<div class="l">属性：</div>
		<div class="c">
			{$_A.code_result.flag|flag}</div>
	</div>
{/if}
	{if $_A.code_result.is_jump!=1}
	{if $_A.code_result.litpic!=""}
	<div class="module_border">
		<div class="l">缩略图：</div>
		<div class="c">
			{if $_A.code_result.litpic!=""}<a href="./{ $_A.code_result.litpic}" target="_blank" title="点击查看大图" ><img src="./{ $_A.code_result.litpic}" border="0" width="100" alt="点击查看大图" title="点击查看大图" /></a>{/if}</div>
	</div>
	{/if}
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			{if $_A.code_result.status == 0 }隐藏{else}显示{/if}
		 </div>
	</div>
	<div class="module_border">
		<div class="l">排序:</div>
		<div class="c">
			{$_A.code_result.order|default:10}
		</div>
	</div>
{if $_A.code_result.source!=""}
	<div class="module_border">
		<div class="l">文章来源:</div>
		<div class="c">
			{$_A.code_result.source}</div>
	</div>
	{/if}
{if $_A.code_result.author!=""}
	<div class="module_border">
		<div class="l">作者:</div>
		<div class="c">
			{$_A.code_result.author}</div>
	</div>
{/if}
{if $_A.code_result.summary!=""}
	<div class="module_border">
		<div class="l">内容简介:</div>
		<div class="c">
			{$_A.code_result.summary}</div>
	</div>
{/if}
	<div class="module_border">
		<div class="l">内容:</div>
		<div class="c">
			<table><tr><td align="left">{ $_A.code_result.content}</td></tr></table>
		</div>
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
			{$_A.code_result.hits}/{ $_A.code_result.comment}</div>
	</div>
	{/if}
	<div class="module_border">
		<div class="l">添加时间/IP:</div>
		<div class="c">
			{$_A.code_result.addtime|date_format:'Y-m-d'}/{ $_A.code_result.addip}</div>
	</div>
	<div class="module_border">
		<div class="l">添加人:</div>
		<div class="c">
			{$_A.code_result.username}</div>
	</div>
	<div class="module_submit" >
		{if $_A.query_type == "edit" }<input type="hidden" name="id" value="{ $_A.code_result.id }" />{/if}
		<input type="button" name="submit" value="返回上一页" onclick="javascript:history.go(-1)" />
		<input type="button" name="reset" value="修改内容" onclick="javascript:location.href='{$_A.query_url}/edit{$_A.site_url}&id={ $_A.code_result.id}'"/>
	</div>
	</form>
</div>
<!-- 查看文章 结束 -->
<!-- 文章列表 结束 -->
{elseif $_A.query_type=="list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
		<tr >
			<td width="" class="main_td"><input type="checkbox" name="allcheck" onclick="checkFormAll(this.form)"/></td>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">标题</td>
			<td width="" class="main_td">栏目类型</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">排序</td>
			<td width="" class="main_td">属性</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{foreach  from=$_A.code_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1" align="center" ><input type="checkbox" name="aid[]" id="aid[]" value="{$item.id}"/></td>
			<td class="main_td1" align="center" >{ $item.id}</td>
			<td class="main_td1" align="center">{$item.name|truncate:34}{if $item.is_jump==1}<font color="#CCCCCC">[跳转]</font>{/if}</td>
			<td class="main_td1" align="center" >{$item.site_name|default:-}</td>
			<td class="main_td1" align="center" >{ if $item.status ==1}<a href="{$_A.query_url}{$_A.site_url}&status=0&id={ $item.id}">显示</a>{else}<a href="{$_A.query_url}{$_A.site_url}&status=1&id={ $item.id}">隐藏</a>{/if}</td>
			<td class="main_td1" align="center" ><input type="text" name="order[]" value="{$item.order}" size="3" /><input type="hidden" name="id[]" value="{$item.id}" /><input type="hidden" name="flag[]" value="{$item.flag}" /></td>
			<td class="main_td1" align="center" >{$item.flagname|default:-}</td>
			<td class="main_td1" align="center" ><a href="{$_A.query_url}/view{$_A.site_url}&id={$item.id}">查看</a> <a href="{$_A.query_url}/edit{$_A.site_url}&id={$item.id}">修改</a> <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/del{$_A.site_url}&id={$item.id}'">删除</a></td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="2" class="action">
			<div class="floatl"><select name="type">
			<option value="0">排序</option>
			<option value="1">显示</option>
			<option value="2">隐藏</option>
			<option value="3">推荐</option>
			<option value="4">头条</option>
			<option value="5">幻灯片</option>
			<option value="6">删除</option>&nbsp;&nbsp;&nbsp;
			</select><input type="submit" value="确认操作" /> 排序不用全选
			</div>
			<div class="floatr">
			</div>
			</td>
			<td colspan="6" class="action">
				栏目类型：<select id="site_id"><option value="">全部</option>{foreach from=$_A.site_code_list item=item key=key}
<option value="{$item.site_id}" {if $magic.get.site_id==$item.site_id}select="selected"{/if}>{$item.name}</option>
{/foreach}</select>
				标题：<input type="text" name="title" id="title" value="{$magic.get.title|urldecode}" />
				<input type="button" value="搜索" onclick="sousuo()" />
			</td>
		</tr>
		<tr>
			<td colspan="8" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table>
{/if}
<!-- 文章列表 结束 -->
<script type="text/javascript">
var url = '{$_A.query_url}/&q=module/article/list&a=content';
var site_id = '{$magic.get.site_id}';
{literal}
$("#site_id").val(site_id);
function sousuo(){
	var sousuo = "";
	if($("#title").val()!=""){
		sousuo += "&title="+$("#title").val();
	}
	if($("#site_id").val()!=""){
		sousuo += "&site_id="+$("#site_id").val();
	}
	location.href=url+sousuo;
}
</script>
{/literal}