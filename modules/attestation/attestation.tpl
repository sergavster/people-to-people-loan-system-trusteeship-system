{if $_A.query_type == "new" || $_A.query_type == "edit"}
<div class="module_add">
	<form name="form1" method="post" action="" enctype="multipart/form-data" >
	<div class="module_title"><strong>{if $_A.query_type == "edit" }�༭{else}���{/if}��֤</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<input type="text" name="username"  class="input_border" size="30" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">������Ŀ��</div>
		<div class="c">
			<select name="type_id">{foreach from="$_A.attestation_type_list" item="var"}<option value="{$var.type_id}" {if $_A.attestation_result.type_id == $var.type_id} selected="selected"{/if}>{$var.name}</option>{/foreach}</select>
		</div>
	</div>
	<div class="module_border" >
		<div class="l">�ϴ�ͼƬ��</div>
		<div class="c">
			<input type="file" name="litpic" size="30" class="input_border"/>{if $_A.attestation_result.litpic!=""}<a href="./{ $_A.attestation_result.litpic}" target="_blank" title="��ͼƬ"><img src="{ $tpldir }/images/ico_1.jpg" border="0"  /></a>{/if}</div>
	</div>
	<div class="module_border" >
		<div class="l">����:</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{ $_A.attestation_result.order|default:10}" size="10" />
		</div>
	</div>
	<div class="module_border" >
		<div class="l">���ݼ��:</div>
		<div class="c">
			<textarea name="content" cols="45" rows="5">{ $_A.attestation_result.content}</textarea>
		</div>
	</div>
	<div class="module_border" >
		<div class="l">��֤��:</div>
		<div class="c">
			<input name="valicode" maxlength="4" size="4" /><img src="/plugins/index.php?q=imgcode" alt="���ˢ��" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" align="absmiddle" style="cursor:pointer">
		</div>
	</div>
	<div class="module_submit" >
		{if $_A.query_type == "edit" }<input type="hidden" name="id" value="{ $_A.attestation_result.id }" />{/if}
		<input type="button"  name="tijiao" value="ȷ���ύ" onclick="check_form()" />
		<input type="reset"  name="reset" value="���ñ�" />
	</div>
	</form>
</div>
{literal}
<script type="text/javascript">
function check_form(){
	 var frm = document.forms['form1'];
	 var username = frm.elements['username'].value;
	 var content = frm.elements['content'].value;
	 var litpic = frm.elements['litpic'].value;
	 var valicode = frm.elements['valicode'].value;
	 var errorMsg = '';
	  if(username.length == 0 ) {
		errorMsg += '--�û���������д\n';
	  }
	  if(litpic.length==0){
	  	errorMsg += '--��ѡ��ͼƬ\n';
	  }
	  if(content.length==0){
	  	errorMsg += '--���ݼ�鲻��Ϊ��\n';
	  }
	  if(valicode.length!=4){
	  	errorMsg += '--��֤����������';
	  }
	  if(errorMsg.length > 0){
		alert(errorMsg); return false;
	  }else{
	  	frm.elements['tijiao'].value="�ύ��..";
	  	frm.elements['tijiao'].disabled=true;
	  	frm.submit();
	  	submit_fool();
		return;
	  }
}
</script>
{/literal}

<!-- ���֤�� ��ʼ -->
{elseif $_A.query_type == "view"}
<div class="module_add">
	<form name="form1" method="post" action="">
	<div class="module_title"><strong>֤���鿴</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<a href="{$_A.admin_url}&q=module/user/view&user_id={$_A.attestation_result.user_id}&type=scene" class="thickbox" title="�û���ϸ��Ϣ�鿴">{ $_A.attestation_result.username}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ͣ�</div>
		<div class="c">
			{$_A.attestation_result.type_name }
		</div>
	</div>
	<div class="module_border">
		<div class="l">֤��ͼƬ��</div>
		<div class="c">
			<a href="{$_A.attestation_result.litpic}" target="_blank"><img src="{$_A.attestation_result.litpic}" width="100" height="100" /></a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���:</div>
		<div class="c">
			{$_A.attestation_result.content}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ʱ��/IP:</div>
		<div class="c">
			{$_A.attestation_result.addtime|date_format:'Y-m-d H:i:s'}/{ $_A.attestation_result.addip}
		</div>
	</div>
	{if $_A.attestation_result.status==1}
	<div class="module_border">
		<div class="l">����:</div>
		<div class="c">
			{$_A.attestation_result.jifen} ��</div>
	</div>
	<div class="module_border">
		<div class="l">��˱�ע:</div>
		<div class="c">
			{$_A.attestation_result.verify_remark}</div>
	</div>
	<div class="module_border">
		<div class="l">���ʱ��:</div>
		<div class="c">
			{$_A.attestation_result.verify_time|date_format:'Y-m-d H:i:s'}
		</div>
	</div>
	{else}
	<div class="module_title"><strong>��˴�֤��</strong></div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
		<input type="radio" name="status" value="1"/><font color="#009900">���ͨ��</font> <input type="radio" name="status" value="2" checked="checked"/>��˲�ͨ�� </div>
	</div>
	<div class="module_border" >
		<div class="l">ͨ�������û���:</div>
		<div class="c">
			<input type="text" name="jifen" value="{if $_A.attestation_result.status==1}{$_A.attestation_result.h_jifen}{else}{$_A.attestation_result.d_jifen}{/if}" size="5"> ��
		</div>
	</div>
	<div class="module_border" >
		<div class="l">��˱�ע:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5">{ $_A.attestation_result.verify_remark}</textarea>
		</div>
	</div>
	<div class="module_border" >
		<div class="l">��֤��:</div>
		<div class="c">
			<input name="valicode" type="text" size="11" maxlength="4" tabindex="3" />&nbsp;<img src="/plugins/index.php?q=imgcode" alt="���ˢ��" onClick="this.src='/plugins/index.php?q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="id" value="{$_A.attestation_result.id}" />
		<input type="hidden" name="type_name" value="{$_A.attestation_result.type_name}" />
		<input type="hidden" name="user_id" value="{$_A.attestation_result.user_id}" />
		<input type="button" name="reset" value="��˴�֤��" onclick="check_form()" />
	</div>
	</form>
	{/if}
</div>
{literal}
<script type="text/javascript">
function check_form(){
	 var frm = document.forms['form1'];
	 var verify_remark = frm.elements['verify_remark'].value;
	 var valicode = frm.elements['valicode'].value;
	 var errorMsg = '';
	  if(verify_remark.length == 0 ) {
		errorMsg += '��ע������д' + '\n';
	  }
	  if(valicode.length!=4){
	  	errorMsg += '��֤�벻��ȷ' + '\n';
	  }
	  if(errorMsg.length > 0){
		alert(errorMsg);
		retur;
	  }else{
		frm.elements['reset'].disabled=true;
		frm.elements['reset'].value="����ύ��";
		frm.submit();
		submit_fool();
	  }
}
</script>
{/literal}
<!-- ���֤�� ���� -->

<!-- ֤���б� ��ʼ -->
{elseif $_A.query_type=="list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
  <form action="" method="post">
	<tr>
		<td class="main_td">ID</td>
		<td class="main_td">�û�����</td>
		<td class="main_td">��ʵ����</td>
		<td class="main_td">�����ش�����״̬</td>
		<td class="main_td">���״̬</td>
		<td class="main_td">��״̬</td>
		<td class="main_td">����</td>
	</tr>
	{foreach from=$_A.attestation_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.user_id}</td>
		<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene" class="thickbox" title="�û���ϸ��Ϣ�鿴">{$item.username}</a></td>
		<td class="main_td1" align="center">{$item.realname}</td>
		<td class="main_td1" align="center">{if $item.borrow_must==1}��ȫ���ϴ�{else}���ϲ�ȫ{/if}</td>
		<td class="main_td1" align="center">{if $item.att_nov>0}��{$item.att_nov}��ȴ����{else}��ȫ�����{/if}</td>
		<td class="main_td1" align="center"></td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/ulist&a=attestation&user_id={$item.user_id}">�鿴</a></td>
		</tr>
		{/foreach}
	<tr>
		<td colspan="10" class="action">
		<div class="floatl"></div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username}" />
			��ʵ������<input type="text" name="realname" id="realname" value="{$magic.request.realname|urldecode}" />
			״̬��<select id="typeStatus" ><option value="">ȫ��</option><option value="1" {if $magic.request.typeStatus==1} selected="selected"{/if}>��ͨ��</option><option value="0" {if $magic.request.typeStatus=="0"} selected="selected"{/if}>δͨ��</option></select><input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="9" class="page">
			{$_A.showpage}
		</td>
	</tr>
</form>	
</table>
<!-- ֤���б� ���� -->

{elseif $_A.query_type=="ulist"}
<table  border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
  <form action="" method="post">
	<tr>
		<td class="main_td">˵����Ϣ</td>
		<td class="main_td">��������</td>
		<td class="main_td">�ϴ�ʱ��</td>
		<td class="main_td">���ʱ��</td>
		<td class="main_td">���˵��</td>
		<td class="main_td">����</td>
		<td class="main_td">״̬</td>
		<td class="main_td">����</td>
	</tr>
	{foreach from=$_A.user_attestation_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.name|default:-}</td>
		<td class="main_td1" align="center">{$item.type_name}</td>
		<td class="main_td1" align="center">{$item.addtime|date_format:"Y-m-d H:i"}</td>
		<td class="main_td1" align="center">{$item.verify_time|date_format:"Y-m-d H:i"|default:-}</td>
		<td class="main_td1" align="center">{$item.verify_remark|default:-}</td>
		<td class="main_td1" align="center">{$item.jifen|default:0} ��</td>
		<td class="main_td1" align="center">{if $item.status==0}δ���{elseif $item.status==2}���ʧ��{else}�����{/if}</td>
		<td class="main_td1" align="center">{if $item.status==0}<a href="{$_A.query_url}/view&site_id=26&a=attestation&id={$item.id}">���</a>{/if}</td>
		</tr>
	{/foreach}
</form>	
</table>

<!-- ��ӽײ���� ��ʼ -->
{elseif $_A.query_type=="type_top_include"}
<div class="module_add">
	<div class="module_title"><strong>���{$_A.attestation_top_list.name}�ײ����</strong></div>
<form name="form1" method="post" action="" onsubmit="return submit_fool();">
	{foreach from = $_A.attestation_type_list key=key item=item}
	<div class="module_border">
		<div class="c">
			<input type="checkbox" name="type_id[]" value="{$item.type_id}" />{$item.name}
		</div>
	</div>
	{/foreach}
	<div class="module_border" style="text-align:center">
		<input type="hidden" value="{$magic.get.type_id}" name="ptype_id" />
		<input type="submit" value="�ύ����" />
	</div>
</div>
</form>
<script type="text/javascript">
var include = "{$_A.attestation_top_list.include}";
{literal}
var includeArr = include.split(",");
$("[name='type_id[]']").each(function(){
	var v = this.value;
	if(in_array(v,includeArr)){
		this.setAttribute("checked",true);
	}
})
function in_array(v,includeArr){
	for(var i=0;i<includeArr.length;i++){
		if(v==includeArr[i]){
			return true;
		}
	}
	return false;
}
{/literal}
</script>
<!-- ���͵�һ�� -->
{elseif $_A.query_type == "type_top_list"}
<div class="module_add">
	<div class="module_title"><strong>�ײ��б�</strong></div>
	{foreach from = $_A.attestation_type_list key=key item=item}
	<div class="module_border">
		<div class="c">
			<strong><a href="{$_A.query_url}/type_zj_list&a=attestation&type_id={$item.type_id}">{$item.name}</a></strong>
		</div>
		<div class="c">
			<a href="{$_A.query_url}/type_top_include&a=attestation&type_id={$item.type_id}">�޸�</a>
		</div>
	</div>
	{/foreach}
</div>

<!-- ���ײ㷢���ѡ -->
{elseif $_A.query_type == "type_zj_list"}
<div class="module_title"><strong>{$_A.attestation_presult.name}</strong></div>
<form name="form1" method="post" action="" onsubmit="return submit_fool();">
	{foreach from = $_A.attestation_type_list key=key item=item}
	<div class="module_border">
		<div class="c">
			<input type="checkbox" tow_name="typeid_{$item.type_id}_{$item.name}[]" /><strong>{$item.name}</strong>
		</div>
	</div>
	<div class="module_border">
		{foreach from = $item.son key=k item=v}
			<div class="c">
				{$v}
			</div>
		{/foreach}
	</div>
	{/foreach}
	<div class="module_border" style="text-align:center">
			<input type="hidden" name="pid" value="{$magic.get.type_id}">
			<input type="submit" value="�ύ����" />
	</div>
</form>
{literal}
<script type="text/javascript">
$("[tow_name^='typeid_']").each(function(){
	this.onclick=function(){
		var v = this.getAttribute("tow_name");
		if($(this).attr("checked")){
			$("[name='"+v+"']").attr("checked",true);
		}else{
			$("[name='"+v+"']").attr("checked",false);
		}
	}
})
</script>
{/literal}

<!-- �����б� ��ʼ -->
{elseif $_A.query_type == "type_list"}
<table width="100%" border="0" cellpadding="5" cellspacing="1" >
	<tr>
		<td class="main_td">��������</td>
		<td class="main_td">����</td>
		<td class="main_td">��Ҫ</td>
		<td class="main_td">��ע</td>
		<td class="main_td">����</td>
		<td class="main_td">״̬</td>
		<td class="main_td">����</td>
	</tr>
	<form action="{$_A.query_url}/type_order" method="post">
	{foreach from = $_A.attestation_type_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td bgcolor="#ffffff">{$item.name}</td>
		<td bgcolor="#ffffff">{$item.jifen}</td>
		<td bgcolor="#ffffff">{$item.summary}</td>
		<td bgcolor="#ffffff">{$item.remark}</td>
		<td bgcolor="#ffffff"><input name="order[]" size="2" value="{ $item.order}"type="text" ><input name="type_id[]" type="hidden" size="2" value="{ $item.type_id}" ></td>
		<td  bgcolor="#ffffff">{ if $item.status==1}��ͨ{else}<font color=red>�ر�</font>{/if}</td>
		<td bgcolor="#ffffff"><a href="{$_A.query_url}/type_edit&a=attestation&type_id={$item.type_id}">�޸�</a>/<a href="#" onclick="javascript:if(confirm('ȷ��Ҫɾ����?������')) location.href='{$_A.query_url}/type_del&type_id={$item.type_id}'">ɾ��</a></td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="7" class="action"><input type="button" onclick="javascript:location.href='{$_A.query_url}/type_new{$_A.site_url}'" value="�������" /><input type="submit" value="�޸�����" /></td>
	</tr>
	</form>
</table>
<!-- �����б� ���� -->

<!-- ����޸����� ��ʼ -->
{elseif $_A.query_type == "type_new" || $_A.query_type == "type_edit"}
<div class="module_add">
	<form enctype="multipart/form-data" name="form1" method="post" action="">
	<div class="module_title"><strong>{ if $_A.query_type == "edit" }�༭{else}���{/if}��֤����</strong></div>
	<div class="module_border">
		<div class="l">��������:</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{$_A.attestation_type_result.name}" size="30" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�������ࣺ</div>
		<div class="c">
			<select name="pid"><option value=''>��ѡ��</option>{foreach from="$_A.attestation_type_plist" item="var"}<option value="{$var.type_id}" {if $_A.attestation_type_result.pid==$var.type_id}selected="selected"{/if}>{$var.name}</option>{/foreach}</select>
		</div>
	</div>
	<div class="module_border">
		<div class="l">����:</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{$_A.attestation_type_result.order|default:10}" size="10" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
			<input type="radio" name="status" value="0"  {if $_A.attestation_type_result.status == 0 }checked="checked"{/if}/> �ر�<input type="radio" name="status" value="1"  { if $_A.attestation_type_result.status ==1 ||$_A.attestation_type_result.status ==""}checked="checked"{/if}/>��ͨ
		</div>
	</div>
	<div class="module_border">
		<div class="l">Ĭ�ϻ���:</div>
		<div class="c">
			<input type="text" name="jifen" value="{$_A.attestation_type_result.jifen|default:2}" /
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ҫ˵��:</div>
		<div class="c">
			<textarea name="summary" cols="55" rows="6" >{$_A.attestation_type_result.summary}</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ע:</div>
		<div class="c">
			<textarea name="remark" cols="55" rows="6" >{$_A.attestation_type_result.remark}</textarea>
		</div>
	</div>
	<div class="module_submit" >
	{if $_A.query_type == "type_edit" }<input type="hidden" name="type_id" value="{ $_A.attestation_type_result.type_id }" />{/if}
		<input type="button" name="tijiao" value="ȷ���ύ" onclick="check_form()" />
		<input type="reset" name="reset" value="���ñ�" />
	</div>
	</form>
</div>
{literal}
<script type="text/javascript">
function check_form(){
	 var frm = document.forms['form1'];
	 var title = frm.elements['name'].value;
	 var errorMsg = '';
	  if (title.length == 0 ) {
		errorMsg += '�������Ʊ�����д' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg);return;
	  }else{
		frm.elements['tijiao'].disabled=true;
		frm.elements['tijiao'].value="�ύ��..";
		frm.submit();
		submit_fool();
		return;
	  }
}
{/literal}
</script>
<!-- ����޸����� ���� -->

<!-- ���������б� ��ʼ -->
{elseif $_A.query_type == "type_tow_list"}
<table width="100%" border="0" cellpadding="5" cellspacing="1" >
	<tr>
		<td class="main_td">��������</td>
		<td class="main_td">nid</td>
		<td class="main_td">��Ҫ</td>
		<td class="main_td">��ע</td>
		<td class="main_td">����</td>
		<td class="main_td">����</td>
	</tr>
	<form action="{$_A.query_url}/type_order&a=attestation" method="post" onsubmit="return submit_fool()">
	{foreach from = $_A.attestation_type_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td bgcolor="#ffffff">{$item.name}</td>
		<td bgcolor="#ffffff">{$item.nid}</td>
		<td bgcolor="#ffffff">{$item.summary}</td>
		<td bgcolor="#ffffff">{$item.remark}</td>
		<td bgcolor="#ffffff"><input name="order[]" size="2" value="{$item.order}"type="text" ><input name="type_id[]" type="hidden" size="2" value="{$item.type_id}" ></td>
		<td bgcolor="#ffffff"><a href="{$_A.query_url}/type_tow_edit&a=attestation&type_id={$item.type_id}">�޸�</a></td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="7" class="action"><input type="button" value="��Ӷ�������" onclick="javascript:location.href='{$_A.query_url}/type_tow_new{$_A.site_url}'"><input type="submit" value="�޸�����" /></td>
	</tr>
	</form>
</table>

<!-- ��Ӷ������� ��ʼ -->
{elseif $_A.query_type == "type_tow_new" || $_A.query_type == "type_tow_edit"}
<div class="module_add">
	<form name="form1" method="post" action="">
	<div class="module_title"><strong>{if $_A.query_type=="type_tow_new"}��Ӷ�������{else}�޸Ķ�������{/if}</strong></div>
	<div class="module_border">
		<div class="l">��������:</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{$_A.attestation_type_result.name}" size="30" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">ʶ����:</div>
		<div class="c">
			<input type="text" name="nid" {if $_A.query_type=="type_tow_edit"}readonly="readonly" style="background:#CCCCCC"{/if} class="input_border" value="{$_A.attestation_type_result.nid}" size="30" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">����:</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{$_A.attestation_type_result.order|default:10}" size="10" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ҫ˵��:</div>
		<div class="c">
			<textarea name="summary" cols="55" rows="6" >{$_A.attestation_type_result.summary}</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ע:</div>
		<div class="c">
			<textarea name="remark" cols="55" rows="6" >{$_A.attestation_type_result.remark}</textarea>
		</div>
	</div>
	<div class="module_submit" >
	{if $_A.query_type == "type_tow_edit"}<input type="hidden" name="type_id" value="{$_A.attestation_type_result.type_id}" />{/if}
		<input type="button" name="tijiao" value="ȷ���ύ" onclick="document.forms['form1'].submit();this.disabled=true;submit_fool()" />
		<input type="reset" name="reset" value="���ñ�" />
	</div>
	</form>
</div>

<!-- ʵ����֤ ��ʼ -->
{elseif $_A.query_type=="realname"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">�û�����</td>
		<td class="main_td">��ʵ����</td>
		<td class="main_td">�Ա�</td>
		<td class="main_td">����</td>
		<td class="main_td">����</td>
		<td class="main_td">֤������</td>
		<td class="main_td">֤������</td>
		<td class="main_td">����</td>
		<td class="main_td">���֤ͼƬ</td>
		<td class="main_td">״̬</td>
		<td class="main_td">����</td>
	</tr>
		{foreach from=$_A.user_real_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene" class="thickbox" title="�û���ϸ��Ϣ�鿴">{$item.username}</a></td>
		<td class="main_td1" align="center" >{$item.realname}</td>
		<td class="main_td1" align="center" >{if $item.sex==1}��{else}Ů{/if}</td>
		<td class="main_td1" align="center" >{$item.nation|linkage}</td>
		<td class="main_td1" align="center" >{$item.birthday|date_format:"Y-m-d "}</td>
		<td class="main_td1" align="center" >{$item.card_type|linkage:"card_type"}</td>
		<td class="main_td1" align="center" >{$item.card_id}</td>
		<td class="main_td1" align="center" >{$item.area|area}</td>
		<td class="main_td1" align="center" >{if $item.card_pic1!=""}<a href="{$item.card_pic1}"  class="thickbox" title="����:{$item.realname}  �Ա�:{if $item.sex==1}��{else}Ů{/if} ���֤��:{$item.card_id }">����</a>{else}��{/if}| {if $item.card_pic2!=""}<a  href="{$item.card_pic2}" class="thickbox" title="����:{$item.realname}�Ա�:{if $item.sex==1}��{else}Ů{/if} ���֤��:{$item.card_id }">����</a>{else}��{/if}</td>
		<td class="main_td1" align="center" >{if $item.real_status==2}<font color="#00ffff">�ȴ����</font>{elseif  $item.real_status==1}<font color="#009900">���ͨ��</font>{else}<font color="#FF0000">���δͨ��</font>{/if}</td>
		<td class="main_td1" align="center" >{if $item.real_status!=1}<a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=realname"  class="thickbox" title="�����֤���">���</a>{else}-{/if}</td>
	</tr>
		{/foreach}
	<tr>
		<td colspan="11" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			֤���ţ�<input type="text" name="cardID" id="cardID" value="{$magic.request.cardID}" maxlength="18" />
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>״̬<select id="status"><option value="">ȫ��</option><option value="1" {if $magic.request.real_status==1} selected="selected"{/if}>��ͨ��</option><option value="0" {if $magic.request.real_status=="0"} selected="selected"{/if}>δͨ��</option><option value="2" {if $magic.request.real_status=="2"} selected="selected"{/if}>�ȴ����</option></select><input type="button" value="����" / onclick="sousuo()">
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="11" class="page">
			{$_A.showpage} 
		</td>
	</tr>
	</form>
</table>
<!-- ʵ����֤ ��ʼ -->

<!-- vip�鿴 ��ʼ -->
{elseif $_A.query_type == "vip"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">�û���</td>
		<td width="" class="main_td">�ͷ�����</td>
		<th width="" class="main_td">���ʱ��</th>
		<th width="" class="main_td">�û�����</th>
		<th width="" class="main_td">��¼����</th>
		<th width="" class="main_td">״̬</th>
		<th width="" class="main_td">�Ƿ�ɷ�</th>
		<td width="" class="main_td">����</td>
	</tr>
	{foreach from=$_A.user_vip_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.user_id}</td>
		<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene" class="thickbox" title="�û���ϸ��Ϣ�鿴">{$item.username}</a></td>
		<td class="main_td1" align="center">{$item.kefu_username}</td>
		<td class="main_td1" align="center" >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center" >{$item.typename}</td>
		<td class="main_td1" align="center">{$item.logintime}</td>
		<td class="main_td1" align="center">{if $item.vip_status==2}<font style="color:red">VIP�ȴ����</font>{elseif $item.vip_status==1}VIP��Ա{elseif $item.vip_status==3}���δͨ��{else}δ����VIP{/if}</td>
		<td class="main_td1" align="center">{if $item.vip_money==""}��{else}{$item.vip_money}Ԫ{/if}</td>
		<td class="main_td1" align="center">{if $item.vip_status==2}<a href="{$_A.query_url}/vipview&user_id={$item.user_id}{$_A.site_url}" style="color:red">��˲鿴</a>{else}--{/if}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="10" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" />
			�ͷ��û�����<input type="text" name="kefu" id="kefu" value="{$magic.request.kefu|urldecode}" />
			{if $_A.site_result.site_id!=25}״̬��<select name="type" id="type"><option value="">ȫ��</option><option value="3" {if $magic.request.type==3}selected="selected"{/if}>���δͨ��</option><option value="1" {if $magic.request.type==1}selected="selected"{/if}>�����ͨ��</option><option value="2" {if $magic.request.type==2}selected="selected"{/if}>�ȴ����</option></select>{/if}
			<input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="10" class="page">
		{$_A.showpage}
		</td>
	</tr>
</table>
<!-- vip�鿴 ���� -->

<!-- vip��� ��ʼ -->
{elseif $_A.query_type == "vipview"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return submit_fool();">
	<div class="module_title"><strong>VIP��˲鿴</strong></div>
	<div class="module_border">
		<div class="l">�û���:</div>
		<div class="c">
			{$_A.user_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���:</div>
		<div class="c">
			{if $_A.user_result.vip_status==3}
			���δͨ��
			{elseif $_A.user_result.vip_status==1}
			�����ͨ��
			{elseif $_A.user_result.vip_status==2}
			<input type="radio" value="1" name="vip_status" />���ͨ��<input type="radio" value="3" name="vip_status" checked="checked" />��˲�ͨ��
			{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ע:</div>
		<div class="c">
			{$_A.user_result.vip_remark}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��˱�ע:</div>
		<div class="c">
			{if $_A.user_result.vip_status==2}
			<textarea name="vip_verify_remark" cols="55" rows="6" >{$_A.user_result.vip_verify_remark}</textarea>
			{else}
			{$_A.user_result.vip_verify_remark}
			{/if}
		</div>
	</div>
	{if $_A.user_result.vip_status==2}
	<div class="module_submit">
	<input type="hidden" name="user_id" value="{$_A.user_result.user_id}" />
		<input type="submit" value="ȷ���ύ" />
		<input type="reset" name="reset" value="���ñ�" />
	</div>
	{/if}
	</form>
<!-- vip��� ���� -->

<!-- �ֻ���֤��� ��Ƶ��֤��� �ֳ���֤��� ��ʼ-->
{elseif $_A.query_type=="all_s"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">�û�����</td>
		<td class="main_td">��ʵ����</td>
		<td class="main_td">ʵ����֤</a></td>
		<td class="main_td">������֤</a></td>
		<td class="main_td" width="220">�ֻ���֤</td>
		<td class="main_td">��Ƶ��֤</td>
		<td class="main_td">�ֳ���֤</td>
	</tr>
		{foreach from=$_A.user_all_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene" class="thickbox" title="�û���ϸ��Ϣ�鿴">{$item.username}</a></td>
		<td class="main_td1" align="center" >{$item.realname}</td>
		<td class="main_td1" align="center" >{if $item.real_status==2}<a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=realname" class="thickbox" title="�����������"><font color="#FF0000">�ȴ����</font></a>{elseif $item.real_status==1}<font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
		<td class="main_td1" align="center" >{if $item.email_status==2}<a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=email" class="thickbox" title="������֤���"><font color="#FF0000">�ȴ����</font></a>{elseif $item.email_status==1}<font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
		<td class="main_td1" align="center" >{if $item.phone_status>1}<font color="#999999">[�ֻ���{$item.phone_status}]</font><a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=phone" class="thickbox" title="�ֻ���֤���"><font color="#FF0000">�ȴ����</font></a>{elseif $item.phone_status==1}<font color="#999999">[�ֻ���{$item.phone}]</font><font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
		<td class="main_td1" align="center" >{if $item.video_status==2}<a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=video" class="thickbox" title="��Ƶ��֤���"><font color="#FF0000">�ȴ����</font></a>{elseif $item.video_status==1}<font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
		<td class="main_td1" align="center" >{if $item.scene_status==2}<a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=scene" class="thickbox" title="�ֳ���֤���"><font color="#FF0000">�ȴ����</font></a>{elseif $item.scene_status==1}<font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="7" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
			��ʵ������<input type="text" name="realname" id="realname" value="{$magic.request.realname|urldecode}"/>
			�ֻ����룺<input type="text" name="telphone" id="telphone" value="{$magic.request.telphone}" maxlength="11" />
			��֤����<select id="type" ><option value="">ȫ��</option>
			<option value="phone" {if $magic.request.type=="phone"} selected="selected"{/if}>�ֻ���֤</option>
			<option value="video" {if $magic.request.type=="video"} selected="selected"{/if}>��Ƶ��֤</option>
			<option value="realname" {if $magic.request.type=="realname"} selected="selected"{/if}>ʵ����֤</option>
			<option value="email" {if $magic.request.type=="email"} selected="selected"{/if}>������֤</option>
			<option value="scene" {if $magic.request.type=="scene"} selected="selected"{/if}>�ֳ���֤</option>
			</select>
                        ��֤״̬<select id="typeStatus" ><option value="">ȫ��</option>
			<option value="2" {if $magic.request.typeStatus=="2"} selected="selected"{/if}>�ȴ����</option>
			<option value="1" {if $magic.request.typeStatus=="1"} selected="selected"{/if}>���ͨ��</option>
			</select><input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="11" class="page">
			{$_A.showpage} 
		</td>
	</tr>
</table>
<!-- �ֻ���֤��� ��Ƶ��֤��� �ֳ���֤��� ����-->

<!-- �û���֤���� ��ʼ -->
{elseif $_A.query_type == "viewall"}
<table border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">�û���</td>
		<td width="" class="main_td">��ʵ����</td>
		<th width="" class="main_td">ʵ����֤</th>
		<th width="" class="main_td">������֤</th>
		<th width="" class="main_td">�ֻ���֤</th>
		<th width="" class="main_td">�Ƿ�VIP</th>
		<th width="" class="main_td">���ʱ��</th>
		<td width="" class="main_td">����</td>
	</tr>
	{foreach  from=$_A.viewall_list key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{ $item.user_id}</td>
		<td class="main_td1" align="center">{$item.username}</td>
		<td class="main_td1" align="center">{$item.realname}</td>
		<td class="main_td1" align="center">{if $item.real_status==1}����֤{else}<font color="#FF0000">δ��֤</font>{/if}</td>
		<td class="main_td1" align="center">{if $item.email_status==1}����֤{else}<font color="#FF0000">δ��֤</font>{/if}</td>
		<td class="main_td1" align="center">{if $item.phone_status==1}����֤{else}<font color="#FF0000">δ��֤</font>{/if}</td>
		<td class="main_td1" align="center">{if $item.vip_status==1}VIP��Ա{else}��ͨ��Ա{/if}</td>
		<td class="main_td1" align="center" >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/view_all&user_id={$item.user_id}{$_A.site_url}">�鿴ȫ������</a> </td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="10" class="action">
		<div class="floatl">
			</div>
			<div class="floatr">
				�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> ������<input type="text" name="realname" id="realname" value="{$magic.request.realname|urldecode}"/><input type="button" value="����" onclick="sousuo()" />
			</div>
			</td>
		</tr>
	<tr>
		<td colspan="10" class="page">
		{$_A.showpage}
		</td>
	</tr>
</table>
<!-- �û���֤���� ����-->

<!-- �������� ��ʼ-->
{elseif $_A.query_type == "wait"}
<table border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<th width="" class="main_td">ID</th>
		<th width="" class="main_td">�û���</th>
		<th width="" class="main_td">��ʵ����</th>
		<th width="" class="main_td">ʵ����֤</th>
		<th width="" class="main_td">vip��֤</th>
		<th width="" class="main_td">��Ƶ��֤</th>
		<th width="" class="main_td">�ֻ���֤</th>
		<th width="" class="main_td">�ֳ���֤</th>
	</tr>
	{foreach from=$_A.wait_list key=key item=item}
	<tr {if $key%2==1}class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.user_id}</td>
		<td class="main_td1" align="center"><a href="{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene" class="thickbox" title="�û���ϸ��Ϣ�鿴">{$item.username}</a></td>
		<td class="main_td1" align="center" >{$item.realname}</td>
		<td class="main_td1" align="center" >{if $item.real_status==2}<a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=realname" class="thickbox" title="�����������"><font color="#FF0000">�ȴ����</font></a>{elseif $item.real_status==1}<font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
		<td class="main_td1" align="center" >{if $item.email_status==2}<a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=email" class="thickbox" title="������֤���"><font color="#FF0000">�ȴ����</font></a>{elseif $item.email_status==1}<font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
		<td class="main_td1" align="center" >{if $item.phone_status>1}<font color="#999999">[�ֻ���{$item.phone_status}]</font><a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=phone" class="thickbox" title="�ֻ���֤���"><font color="#FF0000">�ȴ����</font></a>{elseif $item.phone_status==1}<font color="#999999">[�ֻ���{$item.phone}]</font><font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
		<td class="main_td1" align="center" >{if $item.video_status==2}<a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=video" class="thickbox" title="��Ƶ��֤���"><font color="#FF0000">�ȴ����</font></a>{elseif $item.video_status==1}<font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
		<td class="main_td1" align="center" >{if $item.scene_status==2}<a href="{$_A.query_url}/audit&user_id={$item.user_id}&type=scene" class="thickbox" title="�ֳ���֤���"><font color="#FF0000">�ȴ����</font></a>{elseif $item.scene_status==1}<font color="#009900">���ͨ��</font>{else}<font color="#cccccc">û������</font>{/if}</td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="10" class="action">
		<div class="floatl">
			</div>
			<div class="floatr">
				�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> ������<input type="text" name="realname" id="realname" value="{$magic.request.realname|urldecode}"/><input type="button" value="����" onclick="sousuo()" />
			</div>
			</td>
		</tr>
	<tr>
		<td colspan="10" class="page">
		{$_A.showpage}
		</td>
	</tr>
</table>
<!-- �������� ����-->

<!-- �鿴ȫ������ ��ʼ -->
{elseif $_A.query_type == "view_all"}
<div class="module_add">
	<div class="module_title"><strong>�û���Ϣ�鿴</strong></div>
	<div class="module_border">
		<div class="l">�û���:</div>
		<div class="c">
			{$_A.user_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��½����:</div>
		<div class="c">
			{$_A.user_result.logintime}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����¼ʱ��:</div>
		<div class="c">
			{$_A.user_result.lasttime|date_format}
		</div>
		<div class="l">ע��ʱ��:</div>
		<div class="c">
			{$_A.user_result.addtime|date_format}
		</div>
	</div>
	<div class="module_title"><strong>ʵ����֤��Ϣ</strong></div>
	<div class="module_border">
		<div class="l">��ʵ���� :</div>
		<div class="h">
			{$_A.user_result.realname}
		</div>
		<div class="l">�Ա� :</div>
		<div class="c">
			{if $_A.user_result.sex==1}��{else}Ů{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">֤������:</div>
		<div class="h">
			{$_A.user_result.card_type|linkage:"card_type"}
		</div>
		<div class="l">֤������:</div>
		<div class="c">
			{$_A.user_result.card_id }
		</div>
	</div>
	<div class="module_border">
		<div class="l">����:</div>
		<div class="h">
			{$_A.user_result.area|area}
		</div>
		<div class="l">���֤ͼƬ:</div>
		<div class="c">
			{if $_A.user_result.card_pic1!=""}<a href="{$_A.user_result.card_pic1}" class="thickbox" title="����:{$_A.user_result.realname}  �Ա�:{if $_A.user_result.sex==1}��{else}Ů{/if}  ���֤��:{$_A.user_result.card_id }">����</a>{/if} | {if $_A.user_result.card_pic2!=""}<a href="{$_A.user_result.card_pic2}" class="thickbox" title="����:{$_A.user_result.realname}  �Ա�:{if $_A.user_result.sex==1}��{else}Ů{/if}  ���֤��:{$_A.user_result.card_id }">����</a>{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����:</div>
		<div class="h">
			{$_A.user_result.nation|linkage}
		</div>
		<div class="l">����:</div>
		<div class="c">
			{$_A.user_result.birthday|date_format:"Y-m-d"}
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
			{if $_A.user_result.real_status==1}��ͨ����֤{else}δͨ����֤ {/if}
		</div>
	</div>
	<div class="module_title"><strong>������֤��Ϣ</strong></div>
	<div class="module_border">
		<div class="l">���� :</div>
		<div class="h">
			{$_A.user_result.email}
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
			{if $_A.user_result.email_status==1}������ͨ����֤{else}δͨ����֤{/if}
		</div>
	</div>
	<div class="module_title"><strong>��Ƶ��֤</strong></div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
			{if $_A.user_result.video_status==1}��ͨ����Ƶ��֤{elseif $_A.user_result.video_status==2}��Ƶ��֤������{else}δͨ����Ƶ��֤ {/if}
		</div>
	</div>
	<div class="module_title"><strong>�ֳ���֤</strong></div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
			{if $_A.user_result.scene_status==1}��ͨ���ֳ���֤{elseif $_A.user_result.scene_status==2}�ֳ���֤������{else}δͨ���ֳ���֤ {/if}
		</div>
	</div>
	<div class="module_title"><strong>�ֻ���֤��Ϣ</strong></div>
	<div class="module_border">
		<div class="l">�ֻ��� :</div>
		<div class="h">
			{$_A.user_result.phone|default:"-"}
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
			{if $_A.user_result.phone_status==1}�ֻ���ͨ����֤{else}δͨ����֤ {/if}
		</div>
	</div>
	<div class="module_title"><strong>VIP��֤��Ϣ</strong></div>
	<div class="module_border">
		<div class="l">���뱸ע:</div>
		<div class="h">
			{$_A.user_result.vip_verify_remark}
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
			{if $_A.user_result.vip_status==1}VIP��Ա{else}δͨ�� {/if}
		</div>
	</div>
	<div class="module_title"><strong>�û�������Ϣ</strong></div>
	<div class="module_border">
		<div class="w">����״����</div>
		<div class="h">
			{$_A.userinfo_result.marry|linkage}
		</div>
		<div class="w">�� Ů��</div>
		<div class="c">
			{$_A.userinfo_result.child|linkage}
		</div>
	</div>
    <div class="module_border">
        <div class="w">ѧ ����</div>
        <div class="h">
            {$_A.userinfo_result.education|linkage}
        </div>
        <div class="w">�����룺</div>
        <div class="c">
            {$_A.userinfo_result.income|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="w">�� ����</div>
        <div class="h">
            {$_A.userinfo_result.shebao|linkage}
        </div>
        <div class="w">�籣���Ժţ�</div>
        <div class="c">
            {$_A.userinfo_result.shebaoid}
        </div>
    </div>
    <div class="module_border">
        <div class="w">ס��������</div>
        <div class="h">
            {$_A.userinfo_result.housing}
        </div>
        <div class="w">�Ƿ񹺳���</div>
        <div class="c">
            {$_A.userinfo_result.car|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="w">���ڼ�¼��</div>
        <div class="c">
            {$_A.userinfo_result.late|linkage}
        </div>
    </div>
    <div class="module_title"><strong>��������</strong></div>
    <div class="module_border">
        <div class="w">������ַ��</div>
        <div class="h">
            {$_A.userinfo_result.house_address}
        </div>
        <div class="w">���������</div>
        <div class="c">
            {$_A.userinfo_result.house_area}
        </div>
    </div>
    <div class="module_border">
        <div class="w">������ݣ�</div>
        <div class="h">
            {$_A.userinfo_result.house_year|date_format:"Y-m-d"}
        </div>
        <div class="w">����״����</div>
        <div class="c">
            {$_A.userinfo_result.house_status} Ԫ
        </div>
    </div>
    <div class="module_border">
        <div class="w">����Ȩ��1��</div>
        <div class="h">
            {$_A.userinfo_result.house_holder1} ��Ȩ�ݶ�{$_A.userinfo_result.house_right1}
        </div>
        <div class="w">����Ȩ��2��</div>
        <div class="c">
            {$_A.userinfo_result.house_holder2} ��Ȩ�ݶ�{$_A.userinfo_result.house_right2}
        </div>
    </div>
    <div class="module_border">
        <div class="w">���������ڰ�����, ����д��</div>
        <div class="h">
            �������ޣ�{$_A.userinfo_result.house_loanyear} ÿ�¹��� {$_A.userinfo_result.house_loanprice} Ԫ
        </div>
        <div class="w">��Ƿ������</div>
        <div class="c">
            {$_A.userinfo_result.house_balance} Ԫ
        </div>
    </div>
    <div class="module_border">
        <div class="w">�������У�</div>
        <div class="c">
            {$_A.userinfo_result.house_bank}
        </div>
    </div>
    <div class="module_title"><strong>��˾����</strong></div>
    <div class="module_border">
        <div class="w">��˾���ƣ�</div>
        <div class="h">
            {$_A.userinfo_result.company_name}
        </div>
        <div class="w">��˾���ʣ�</div>
        <div class="c">
            {$_A.userinfo_result.company_type|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="w">��˾��ҵ��</div>
        <div class="h">
            {$_A.userinfo_result.company_industry|linkage}
        </div>
        <div class="w">��������</div>
        <div class="c">
            {$_A.userinfo_result.company_jibie|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="w">ְ λ��</div>
        <div class="h">
            {$_A.userinfo_result.company_office|linkage}
        </div>
        <div class="w">����ʱ�䣺</div>
        <div class="c">
            {$_A.userinfo_result.company_worktime1|date_format:"Y-m-d"}  ��{$_A.userinfo_result.company_worktime2|date_format:"Y-m-d"}
        </div>
    </div>
    <div class="module_border">
        <div class="w">�������ޣ�</div>
        <div class="h">
            {$_A.userinfo_result.company_workyear|linkage}
        </div>
        <div class="w">�����绰��</div>
        <div class="c">
            {$_A.userinfo_result.company_tel}
        </div>
    </div>
    <div class="module_border">
        <div class="w">��˾��ַ��</div
        ><div class="h">
            {$_A.userinfo_result.company_address}
        </div>
        <div class="w">��˾��վ��</div>
        <div class="c">
            {$_A.userinfo_result.company_weburl}
        </div>
    </div>
    <div class="module_border">
        <div class="w">��˾��ע˵����</div>
        <div class="c">
            {$_A.userinfo_result.company_reamrk}
        </div>
    </div>
    <div class="module_title"><strong>˽Ӫҵ������</strong></div>
        <div class="module_border">
        <div class="w">˽Ӫ��ҵ���ͣ�</div>
        <div class="h">
            {$_A.userinfo_result.private_type|linkage}
        </div>
        <div class="w">�������ڣ�</div>
        <div class="c">
            {$_A.userinfo_result.private_date|date_format:"Y-m-d"}
        </div>
    </div>
    <div class="module_border">
        <div class="w">��Ӫ������</div>
        <div class="h">
            {$_A.userinfo_result.private_place}
        </div>
        <div class="w">���</div>
        <div class="c">
            {$_A.userinfo_result.private_rent}Ԫ
        </div>
    </div>
    <div class="module_border">
        <div class="w">���ڣ�</div>
        <div class="h">
            {$_A.userinfo_result.private_term} ��
        </div>
        <div class="w">˰���ţ�</div>
        <div class="c">
            {$_A.userinfo_result.private_taxid}
        </div>
    </div>
    <div class="module_border">
        <div class="w">���̵ǼǺţ�</div>
        <div class="h">
            {$_A.userinfo_result.private_commerceid}
        </div>
        <div class="w">ȫ��ӯ��/����</div>
        <div class="c">
            {$_A.userinfo_result.private_income} Ԫ����ȣ�
        </div>
    </div>
    <div class="module_border">
        <div class="w">��Ա������</div>
        <div class="c">
            {$_A.userinfo_result.private_employee}��
        </div>
        </div>
        <div class="module_title"><strong>����״��</strong></div>
        <div class="module_border">
        <div class="w">ÿ���޵�Ѻ�����</div>
        <div class="h">
            {$_A.userinfo_result.finance_repayment} Ԫ
        </div>
        <div class="w">���з�����</div>
        <div class="c">
            {$_A.userinfo_result.finance_property|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="w">ÿ�·��ݰ��ҽ�</div>
        <div class="h">
            {$_A.userinfo_result.finance_amount} Ԫ
        </div>
        <div class="w">����������</div>
        <div class="c">
            {$_A.userinfo_result.finance_car|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="w">ÿ���������ҽ�</div>
        <div class="h">
            {$_A.userinfo_result.finance_caramount} Ԫ
        </div>
        <div class="w">ÿ�����ÿ������</div>
        <div class="c">
            {$_A.userinfo_result.finance_creditcard} Ԫ
        </div>
    </div>
        <div class="module_title"><strong>��ϵ��ʽ</strong></div>
        <div class="module_border">
            <div class="w">��ס�ص绰��</div>
            <div class="h">
                {$_A.userinfo_result.tel}
        </div>
        <div class="w">�ֻ����룺</div>
        <div class="c">
            {$_A.userinfo_result.phone}
        </div>
    </div>
    <div class="module_border">
        <div class="w">��ס����ʡ�У�</div>
        <div class="h">
            {$_A.userinfo_result.area|area}
        </div>
        <div class="w">��ס���ʱࣺ</div>
        <div class="c">
            {$_A.userinfo_result.post}
        </div>
    </div>
    <div class="module_border">
        <div class="w">�־�ס��ַ��</div>
        <div class="h">
            {$_A.userinfo_result.address}
        </div>
    </div>
    <div class="module_border">
        <div class="w">�ڶ���ϵ��������</div>
        <div class="h">
            {$_A.userinfo_result.linkman1}
        </div>
        <div class="w">�ڶ���ϵ�˹�ϵ��</div>
        <div class="h">
            {$_A.userinfo_result.relation1|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="w">�ڶ���ϵ����ϵ�绰��</div>
        <div class="h">
            {$_A.userinfo_result.tel1}
        </div>
        <div class="w">�ڶ���ϵ����ϵ�ֻ���</div>
        <div class="h">
            {$_A.userinfo_result.phone1}
        </div>
    </div>
    <div class="module_border">
        <div class="w">������ϵ��������</div>
        <div class="h">
            {$_A.userinfo_result.linkman2}
        </div>
        <div class="w">������ϵ�˹�ϵ��</div>
        <div class="h">
            {$_A.userinfo_result.relation2|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="w">������ϵ����ϵ�绰��</div>
        <div class="h">
            {$_A.userinfo_result.tel2}
        </div>
        <div class="w">������ϵ����ϵ�ֻ���</div>
        <div class="h">
            {$_A.userinfo_result.phone2}

        </div>
    </div>
    <div class="module_border">
        <div class="w">������ϵ��������</div>
        <div class="h">
            {$_A.userinfo_result.linkman3}
        </div>
        <div class="w">������ϵ�˹�ϵ��</div>
        <div class="c">
            {$_A.userinfo_result.relation3|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="w">������ϵ����ϵ�绰��</div>
        <div class="h">
            {$_A.userinfo_result.tel3}
        </div>
        <div class="w">������ϵ����ϵ�ֻ���</div>
        <div class="c">
            {$_A.userinfo_result.phone3}
        </div>
    </div>
    <div class="module_border">
        <div class="w">MSN��</div>
        <div class="h">
            {$_A.userinfo_result.msn}
        </div>
        <div class="w">QQ��</div>
        <div class="c">
            {$_A.userinfo_result.qq}
        </div>
    </div>
    <div class="module_border">
        <div class="w">������</div>
        <div class="c">
            {$_A.userinfo_result.wangwang}
        </div>
    </div>
    <div class="module_title"><strong>��ż����</strong></div>
        <div class="module_border">
        <div class="l">��ż������</div>
        <div class="h">
            {$_A.userinfo_result.mate_name}
        </div>
        <div class="l">ÿ��н��</div>
        <div class="c">
            {$_A.userinfo_result.mate_salary}
        </div>
    </div>
    <div class="module_border">
        <div class="l">�ƶ��绰��</div>
        <div class="h">
            {$_A.userinfo_result.mate_phone}
        </div>
        <div class="l">��λ�绰��</div>
        <div class="c">
            {$_A.userinfo_result.mate_tel}
        </div>
    </div>
    <div class="module_border">
        <div class="l">������λ��</div>
        <div class="h">
            {$_A.userinfo_result.mate_type|linkage}
        </div>
        <div class="l">ְλ��</div>
        <div class="c">
            {$_A.userinfo_result.mate_office|linkage}
        </div>
    </div>
    <div class="module_border">
        <div class="l">��λ��ַ��</div>
        <div class="h">
            {$_A.userinfo_result.mate_address}
        </div>
        <div class="l">�����룺</div>
        <div class="c">
            {$_A.userinfo_result.mate_income}
        </div>
    </div>
    <div class="module_title"><strong>������Ϣ</strong></div>
    <div class="module_border">
        <div class="l">����������</div>
        <div class="c">
            {$_A.userinfo_result.ability|nl2br}
        </div>
    </div>
    <div class="module_border">
        <div class="l">���˰��ã�</div>
        <div class="c">
            {$_A.userinfo_result.interest|nl2br}
        </div>
    </div>
        <div class="module_border">
        <div class="l">����˵����</div>
        <div class="c">
            {$_A.userinfo_result.others|nl2br}
        </div>
    </div>
</div>
<!-- �鿴ȫ������ ���� -->

{elseif $_A.query_type == "jifen"}

<form method="post">

<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">

	<tr >

		<td width="" class="main_td">ID</td>

		<td width="*" class="main_td">�û���</td>

		<td width="*" class="main_td">��ʵ����</td>

		<th width="" class="main_td">��֤����</th>

		<th width="" class="main_td">��֤����</th>

		<th width="" class="main_td">��ע</th>

		<th width="" class="main_td">���ʱ��</th>

	</tr>

	{ foreach  from=$_A.jifen_result key=key item=item}

	<tr {if $key%2==1} class="tr2"{/if}>

		<td class="main_td1" align="center">{ $item.user_id}<input type="hidden" value="{$item.id}" name="id[]" /></td>

		<td class="main_td1" align="center">{$item.username}</td>

		<td class="main_td1" align="center">{$item.realname}</td>

		<td class="main_td1" align="center">{$item.typename}</td>

		<td class="main_td1" align="center"><input type="text" value="{$item.value}" size="5" name="val[]" /></td>

		<td class="main_td1" align="center">{$item.remark}</td>

		<td class="main_td1" align="center">{$item.addtime|date_format}<input type="hidden" name="user_id" value="{$magic.request.user_id}" /></td>

	</tr>

	{ /foreach}

	<tr >

		<td colspan="7"><input type="submit" value="�޸Ļ���" /></td>

	</tr>

</table>
</form>
{/if}
<script type="text/javascript">
var url = '{$_A.query_url}/{$_A.query_type}{$_A.site_url}';
{literal}
function sousuo(){
	var sou = "";
	var username = $("#username").val() || "";
	var realname = $("#realname").val() || "";
	var type = $("#type").val() || "";
	var typeStatus = $("#typeStatus").val() || "";
	var real_status = $("#status").val() || "";
	var cardID = $("#cardID").val() || "";
	var kefu = $("#kefu").val() || "";
	var telphone = $("#telphone").val() || "";
	if (username!=""){
		sou += "&username="+username;
	}
	if (realname!=""){
		sou += "&realname="+realname;
	}
	if (type!=""){
		sou += "&type="+type;
	}
	if (typeStatus!=""){
		sou += "&typeStatus="+typeStatus;
	}
	if(real_status!=""){
		sou += "&real_status="+real_status;
	}
	if(kefu!=""){
		sou += "&kefu="+kefu;
	}
	if(cardID!=""){
		sou += "&cardID="+cardID;
	}
	if(telphone!=""){
		sou += "&telphone="+telphone;
	}
	location.href=url+sou;
}
</script>
{/literal}