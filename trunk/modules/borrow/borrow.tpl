{if $_A.query_type == "new"}
<div class="module_add">
	{if $magic.request.user_id==""}
	<form name="form1" method="post" action="" enctype="multipart/form-data">
	<div class="module_title"><strong>���������Ϣ���û�����ID</strong></div>
	<div class="module_border">
		<div class="l">�û�ID��</div>
		<div class="c">
			<input type="text" name="user_id"  class="input_border"  size="20" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<input type="text" name="username"  class="input_border"  size="20" />
		</div>
	</div>
	<div class="module_submit" >
		<input type="submit"  name="submit" value="ȷ���ύ" />
		<input type="reset"  name="reset" value="���ñ�" />
	</div>
	</form>
	{else}
	<div class="module_title"><strong>��ӽ����Ϣ</strong></div>
	<form name="form1" method="post" action=""  enctype="multipart/form-data" onsubmit="return check_form();" >
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			{$_A.user_result.username|default:$_A.borrow_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���֣�</div>
		<div class="c">
		<select name="biao_type">
		{foreach from="$_G.biao_type" item="item"}
		{if $item.available==1}
		<option value="{$item.biao_type_name}">{$item.show_name}</option>
		{/if}
		{/foreach}
		</select>
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����;��</div>
		<div class="c">
		{linkages nid="borrow_use" value="$_A.borrow_result.use" name="use"  }
			 <span></span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">������ޣ�</div>
		<div class="c">
			<input type="radio" name="isday" value="0" checked="checked">��<input type="radio" name="isday" value="1">��
		</div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c">
			<div id="time_limit">
			<select name="time_limit" id="time_limit">  <option value="1">1����</option>  <option value="3">3����</option>  <option value="6">6����</option>  <option value="12">12����</option>  <option value="24">24����</option>  <option value="36">36����</option></select>
			</div>
			<div id="time_limit_day" style="display:none">
			<input type="text" value="" name="time_limit_day">��
			</div>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ʽ��</div>
		<div class="c">
			{linkages nid="borrow_style" value="$_A.borrow_result.style" name="style" type="value" value="0"}
		<span ></span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">����ܽ�</div>
		<div class="c"><input type="text" name="account" value="{$_A.borrow_result.account}" />
<span ></span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">�����ʣ�</div>
		<div class="c">
			<input type="text" name="apr" value="{$_A.borrow_result.apr}" /> % <span ></span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">���Ͷ���</div>
		<div class="c">
			{linkages nid="borrow_lowest_account" value="$_A.borrow_result.lowest_account" name="lowest_account" type="value" }
		<span></span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">���Ͷ���ܶ</div>
		<div class="c">
			{linkages nid="borrow_most_account" value="$_A.borrow_result.most_account" name="most_account" type="value" }
			<span ></span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��Чʱ�䣺</div>
		<div class="c">
			{linkages nid="borrow_valid_time" value="$_A.borrow_result.valid_time" name="valid_time" type="value" }
			 <span></span>
		</div>
	</div>
	<div class="module_title"><strong>���ý���</strong></div>
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="0" {if $_A.borrow_result.award==0 || $_A.borrow_result.award==""} checked="checked"{/if}>�����ý���</div>
		<div class="c">
			 <span>����������˽��������ᶳ�����ʻ�����Ӧ���˻������Ҫ���ý�������ȷ�������ʻ����㹻 ���˻��� </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="1" {if $_A.borrow_result.award==1 } checked="checked"{/if}/>���̶�����̯������</div>
		<div class="c">
			<input type="text" name="part_account" value="{$_A.borrow_result.part_account}" size="5" /> Ԫ <span>��������Ԫ��Ϊ��λ���������ñ��α��Ҫ����������Ͷ���û����ܽ�  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="2" {if $_A.borrow_result.award==2 } checked="checked"{/if}/>��Ͷ�������������</div>
		<div class="c">
			<input type="text" name="funds" value="{$_A.borrow_result.funds}" size="5" /> %  <span>�������ñ��α��Ҫ����������Ͷ���û��Ľ���������  </span>
		</div>
	</div>
	<div class="module_title"><strong>��ϸ��Ϣ</strong></div>

	<div class="module_border">
		<div class="l">���⣺</div>
		<div class="c">
			<input type="text" name="name" value="{$_A.borrow_result.name}" size="50" /> 
		</div>
	</div>
	
	<div class="module_border">
		<div class="l">��Ϣ��</div>
		<div class="c" style="width:70%">
			<script charset="gb2312" src="/plugins/editor/kindeditor/kindeditor-min.js"></script>
			<script charset="gb2312" src="/plugins/editor/kindeditor/lang/zh_CN.js"></script>
			<textarea name="content" id="content"  style="width:100%;height:400px;visibility:hidden;" >
		            {$_A.borrow_result.content}
			</textarea>
		</div>
	</div>
	<!--�������� ����-->

	<div class="module_submit" >
	{if $_A.query_type == "edit"}<input type="hidden"  name="id" value="{$magic.request.id}" />{/if}
		<input type="hidden" name="status" value="{$_A.borrow_result.status }" />
		<input type="hidden" name="user_id" value="{$magic.request.user_id}" />
		<input type="submit" name="submit" value="ȷ���ύ" />
		<input type="reset" name="reset" value="���ñ�" />
	</div>
	</form>
	{/if}
</div>
{literal}
<script>

var editor;
KindEditor.ready(function(K) {
	editor = K.create('textarea[name="content"]', {
		allowFileManager : true
	});
});

$("input[name='isday']").click(function(){
	var a = $(this).val();
	if(a==1){
		$("#time_limit").hide();
		$("#time_limit_day").show();
	}else{
		$("#time_limit").show();
		$("#time_limit_day").hide();
	}
})
function check_form(){
	 var frm = document.forms['form1'];
	 var name = frm.elements['name'].value;
	 var account = frm.elements['account'].value;
	 var apr = frm.elements['apr'].value;
	 var errorMsg = '';
	  if (name.length == 0 ) {
		errorMsg += '���������д' + '\n';
	  }
	  if(!/^[1-9]+\.?\d+$/.test(account)){
		errorMsg += '���������' + '\n';
	  }
	  if(!/^[1-9]+\.?\d+$/.test(account)){
		errorMsg += '�����������' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{
		submit_fool();
		return true;
	  }
}

</script>
{/literal}

<!-- �޸� ��ʼ -->
{elseif $_A.query_type == "edit"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return submit_fool();" enctype="multipart/form-data" >
	<div class="module_title"><strong>��˽���</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
		<a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/user/view&user_id={$_A.borrow_result.user_id}&type=scene",500,230,"true","","true","text");'>{$_A.user_result.username|default:$_A.borrow_result.username}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			{if $_A.borrow_result.status==0}����������{elseif $_A.borrow_result.status==1}����ļ��{elseif $_A.borrow_result.status==2}���ʧ��{elseif $_A.borrow_result.status==3}������{elseif $_A.borrow_result.status==4}�������ʧ��{elseif $_A.borrow_result.status==5}����{/if}
		</div>
	</div>
	{if $_A.borrow_result.biao_type=="vouch"}
	<div class="module_border">
		<div class="l">�������û�����</div>
		<div class="c">
		{foreach from=$_A.borrow_result.vouch_user item=vouch_user}
		<a href="/{$_A.admin_url}&q=module/userinfo/vouch_userinfo&vouch_userid={$vouch_user.user_id}" class="thickbox">{$vouch_user.username}</a>&nbsp;&nbsp;
		{/foreach}
		</div>
	</div>
	{/if}
	<div class="module_border">
		<div class="l">�����;��</div>
		<div class="c">
			{$_A.borrow_result.use|linkage:"borrow_use"}
		</div>
	</div>
	<div class="module_border">
		<div class="l">������ޣ�</div>
		<div class="c">
		{if $_A.borrow_result.isday==1 } 
                {$_A.borrow_result.time_limit_day}��
                {else}
                {$_A.borrow_result.time_limit}����
                {/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ʽ��</div>
		<div class="c">
			{if $_A.borrow_result.isday==1 } 
                ����ȫ���
            {else}
                {$_A.borrow_result.style|linkage:"borrow_style"}
            {/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����ܽ�</div>
		<div class="c">
			{$_A.borrow_result.account}Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ʣ�</div>
		<div class="c">
			{$_A.borrow_result.apr} %
		</div>
	</div>
	<div class="module_border">
		<div class="l">���Ͷ���</div>
		<div class="c">
			{$_A.borrow_result.lowest_account}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���Ͷ���ܶ</div>
		<div class="c">
			{if $_A.borrow_result.most_account==0}û������{else}{$_A.borrow_result.most_account}{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Чʱ�䣺</div>
		<div class="c">
			{$_A.borrow_result.valid_time|linkage:"borrow_valid_time"}
		</div>
	</div>
	<div class="module_title"><strong>���ý���</strong></div>
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="0" {if $_A.borrow_result.award==0 || $_A.borrow_result.award==""} checked="checked"{/if}>�����ý���</div>
		<div class="c">
			 <span>����������˽��������ᶳ�����ʻ�����Ӧ���˻������Ҫ���ý�������ȷ�������ʻ����㹻 ���˻��� </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="1" {if $_A.borrow_result.award==1 } checked="checked"{/if}/>���̶�����̯������</div>
		<div class="c">
			<input type="text" name="part_account" value="{$_A.borrow_result.part_account}" size="5" /> Ԫ <span>��������Ԫ��Ϊ��λ���������ñ��α��Ҫ����������Ͷ���û����ܽ�  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="2" {if $_A.borrow_result.award==2 } checked="checked"{/if}/>��Ͷ�������������</div>
		<div class="c">
			<input type="text" name="funds" value="{$_A.borrow_result.funds}" size="5" /> %  <span>�������ñ��α��Ҫ����������Ͷ���û��Ľ���������  </span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���⣺</div>
		<div class="c">
			<input type="text" name="title" value="{$_A.borrow_result.name}" size="100" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Ŀ������</div>
		<div class="c" style="width:70%">
			<script charset="gb2312" src="/plugins/editor/kindeditor/kindeditor-min.js"></script>
			<script charset="gb2312" src="/plugins/editor/kindeditor/lang/zh_CN.js"></script>
			<textarea name="content" id="content"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.content}
			</textarea>
		</div>
	</div>
	<!--
	<div class="module_border">
		<div class="l">�ʽ���ת��</div>
		<div class="c" style="width:70%">
			<textarea name="zjyz" id="zjyz"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.zjyz}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���տ��ƴ�ʩ��</div>
		<div class="c" style="width:70%">
			<textarea name="fxkzcs" id="fxkzcs"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.fxkzcs}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ҵ������</div>
		<div class="c" style="width:70%">
			<textarea name="qybj" id="qybj"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.qybj}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ҵ��Ϣ��</div>
		<div class="c" style="width:70%">
			<textarea name="qyxx" id="qyxx"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.qyxx}
			</textarea>
		</div>
	</div>-->
	{literal}
	<script>
	var editor;
	var editor1;
	var editor2;
	var editor3;
	var editor4;
	var editor5;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true
		});
	});
/*
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="zjyz"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor2 = K.create('textarea[name="fxkzcs"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor4 = K.create('textarea[name="qybj"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor5 = K.create('textarea[name="qyxx"]', {
			allowFileManager : true
		});
	});*/
	</script>
	{/literal}
	<div class="module_title"><strong>�����Ŀ����</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=xgxmzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>

	<div class="module_title"><strong>��Ѻ�������</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=dyzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>
	<div><input type="hidden" value="{$magic.get.id}" name="borrow_id"><input type="submit" name="submit" value="�������"></div>
	</form>
</div>
<!-- ���� ���� -->

<!-- ���� ��ʼ -->
{elseif $_A.query_type == "view"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return submit_fool();" enctype="multipart/form-data" >
	<div class="module_title"><strong>��˽���</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
		<a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/user/view&user_id={$_A.borrow_result.user_id}&type=scene",500,230,"true","","true","text");'>	{$_A.user_result.username|default:$_A.borrow_result.username}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">״̬��</div>
		<div class="c">
			{if $_A.borrow_result.status==0}����������{elseif $_A.borrow_result.status==1}����ļ��{elseif $_A.borrow_result.status==2}���ʧ��{elseif $_A.borrow_result.status==3}������{elseif $_A.borrow_result.status==4}�������ʧ��{elseif $_A.borrow_result.status==5}����{/if}
		</div>
	</div>
	{if $_A.borrow_result.biao_type=="vouch"}
	<div class="module_border">
		<div class="l">�������û�����</div>
		<div class="c">
		{foreach from=$_A.borrow_result.vouch_user item=vouch_user}
		<a href="/{$_A.admin_url}&q=module/userinfo/vouch_userinfo&vouch_userid={$vouch_user.user_id}" class="thickbox">{$vouch_user.username}</a>&nbsp;&nbsp;
		{/foreach}
		</div>
	</div>
	{/if}
	<div class="module_border">
		<div class="l">�����;��</div>
		<div class="c">
			{$_A.borrow_result.use|linkage:"borrow_use"}
		</div>
	</div>
	<div class="module_border">
		<div class="l">������ޣ�</div>
		<div class="c">
		{if $_A.borrow_result.isday==1 } 
                {$_A.borrow_result.time_limit_day}��
                {else}
                {$_A.borrow_result.time_limit}����
                {/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ʽ��</div>
		<div class="c">
			{if $_A.borrow_result.isday==1 } 
                ����ȫ���
            {else}
                {$_A.borrow_result.style|linkage:"borrow_style"}
            {/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����ܽ�</div>
		<div class="c">
			{$_A.borrow_result.account}<input type="hidden" name="account" value="{$_A.borrow_result.account}" /> Ԫ
		</div>
	</div>
	<div class="module_border">
		<div class="l">�����ʣ�</div>
		<div class="c">
			{$_A.borrow_result.apr} %
		</div>
	</div>
	<div class="module_border">
		<div class="l">���Ͷ���</div>
		<div class="c">
			{$_A.borrow_result.lowest_account}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���Ͷ���ܶ</div>
		<div class="c">
			{if $_A.borrow_result.most_account==0}û������{else}{$_A.borrow_result.most_account}{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��Чʱ�䣺</div>
		<div class="c">
			{$_A.borrow_result.valid_time|linkage:"borrow_valid_time"}
		</div>
	</div>
	<div class="module_title"><strong>���ý���</strong></div>
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="0" {if $_A.borrow_result.award==0 || $_A.borrow_result.award==""} checked="checked"{/if}>�����ý���</div>
		<div class="c">
			 <span>����������˽��������ᶳ�����ʻ�����Ӧ���˻������Ҫ���ý�������ȷ�������ʻ����㹻 ���˻��� </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="1" {if $_A.borrow_result.award==1 } checked="checked"{/if}/>���̶�����̯������</div>
		<div class="c">
			<input type="text" name="part_account" value="{$_A.borrow_result.part_account}" size="5" /> Ԫ <span>��������Ԫ��Ϊ��λ���������ñ��α��Ҫ����������Ͷ���û����ܽ�  </span>
		</div>
	</div>
	
	<div class="module_border">
		<div class="w"><input type="radio" name="award" value="2" {if $_A.borrow_result.award==2 } checked="checked"{/if}/>��Ͷ�������������</div>
		<div class="c">
			<input type="text" name="funds" value="{$_A.borrow_result.funds}" size="5" /> %  <span>�������ñ��α��Ҫ����������Ͷ���û��Ľ���������  </span>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���⣺</div>
		<div class="c">
			<input type="text" name="title" value="{$_A.borrow_result.name}" size="100" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">���˵����</div>
		<div class="c" style="width:70%">
			<script charset="gb2312" src="/plugins/editor/kindeditor/kindeditor-min.js"></script>
			<script charset="gb2312" src="/plugins/editor/kindeditor/lang/zh_CN.js"></script>
			<textarea name="content" id="content"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.content}
			</textarea>
		</div>
	</div>
	<!--
	<div class="module_border">
		<div class="l">�ʽ���ת��</div>
		<div class="c" style="width:70%">
			<textarea name="zjyz" id="zjyz"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.zjyz}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���տ��ƴ�ʩ��</div>
		<div class="c" style="width:70%">
			<textarea name="fxkzcs" id="fxkzcs"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.fxkzcs}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���߼��г�������</div>
		<div class="c" style="width:70%">
			<textarea name="zcjscfx" id="zcjscfx"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.zcjscfx}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ҵ������</div>
		<div class="c" style="width:70%">
			<textarea name="qybj" id="qybj"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.qybj}
			</textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l">��ҵ��Ϣ��</div>
		<div class="c" style="width:70%">
			<textarea name="qyxx" id="qyxx"  style="width:100%;height:200px;visibility:hidden;" >
		            {$_A.borrow_result.qyxx}
			</textarea>
		</div>
	</div>-->
	{literal}
	<script>
	var editor;
	//var editor1;
	//var editor2;
	//var editor3;
	//var editor4;
	//var editor5;
	KindEditor.ready(function(K) {
		editor = K.create('textarea[name="content"]', {
			allowFileManager : true
		});
	});
	/*
	KindEditor.ready(function(K) {
		editor1 = K.create('textarea[name="zjyz"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor2 = K.create('textarea[name="fxkzcs"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor3 = K.create('textarea[name="zcjscfx"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor4 = K.create('textarea[name="qybj"]', {
			allowFileManager : true
		});
	});
	KindEditor.ready(function(K) {
		editor5 = K.create('textarea[name="qyxx"]', {
			allowFileManager : true
		});
	});*/
	</script>
	{/literal}
	
	{if $_A.borrow_result.status!=0}
	<div class="module_title"><strong>���״̬</strong></div>
	<div class="module_border" >
		<div class="l">���ʱ�䣺</div>
		<div class="c">
			{$_A.borrow_result.verify_time|date_format:"Y-m-d H:i"}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����ˣ�</div>
		<div class="c">
			{$_A.borrow_result.verify_username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">��˱�ע��</div>
		<div class="c">
			{$_A.borrow_result.verify_remark}
		</div>
	</div>
	{/if}
	<div class="module_title"><strong>�û��ϴ�����������</strong></div>
	<div>
		{if $_A.borrow_shus_result==""}
		<div style="text-align:center;margin:10px">�û�δ�ϴ��κζԸý���Ĳ�������</div>
		{else}
		<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%" algin="center">
		<tr height="30"><td class="main_td">������</td><td class="main_td">�ϴ�ʱ��</td><td class="main_td">����</td></tr>
		{foreach from=$_A.borrow_shus_result item=item key=key}
		<tr {if $key%2==1}class="tr"{/if}>
		<td>{$item.name}</td>
		<td>{$item.addtime|date_format:Y-m-d H:i:s}</td>
		<td><a href="{$item.litpic}" target="_blank">�鿴</a></td>
		</tr>
		{/foreach}
		</table>
		{/if}
	</div>
	<!--
	<div class="module_title"><strong>{if $_A.borrow_result.status==1}�ñ깫����֤��{else}ѡ��ñ깫����֤��{/if}</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<input type="hidden" name="show_attestation" id="show_attestation" show="{$_A.borrow_result.show_attestation}" value="">
		<input type="hidden" id="iframepath" value="/{$_A.query_url}/borrow_user_attestation&user_id={$_A.borrow_result.user_id}" />
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_user_attestation&user_id={$_A.borrow_result.user_id}{if $_A.borrow_result.status!=0}&borrow_status=1&attestation_id={$_A.borrow_result.show_attestation}{/if}"></iframe>
	</div>
	<div style="margin:10px 0 10px 10px">
	<a href='/{$_A.query_url}/user_attestation&user_type={$_A.borrow_result.user_type}&user_id={$_A.borrow_result.user_id}&width=800&height=600' class="thickbox" title='{$_A.borrow_result.username}��֤������'>����鿴�û��ϴ�������</a>
	</div>
	-->
	<div class="module_title"><strong>��������</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=xgxmzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>
	<div class="module_title"><strong>�ʲ���Ϣ</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=dyzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>
	<div class="module_title"><strong>�����</strong></div>
	<div class="module_border" style="overflow-y:hidden;height:270px">
		<iframe height="260px" width="100%" src="{$_A.query_url}/borrow_attestation&zl_type=bxbzzl&borrow_id={$_A.borrow_result.id}"></iframe>
	</div>
	<div style="margin:10px 0 10px 10px">
	<a href='/{$_A.query_url}/user_attestation&user_type={$_A.borrow_result.user_type}&user_id={$_A.borrow_result.user_id}&width=800&height=600' class="thickbox" title='{$_A.borrow_result.username}��֤������'>����鿴�û��ϴ�������</a>
	</div>
	{if $_A.borrow_result.status==0}
	<div class="module_title"><strong>��˴˽��</strong></div>
	
	<div class="module_border">
		<div class="l">���õȼ�:</div>
		<div class="c">
			<select name="credit_grade">
			<option value="2">A</option><option value="3">B</option>
			<option value="4">C</option><option value="5">D</option><option value="6">E</option>
			<option value="7">HR</option>
			</select>
		</div>
	</div>
	<!--
	<div class="module_border">
		<div class="l">�������:</div>
		<div class="c">
			<select name="borrow_rzlx">
			<option value="xyrz">������֤��</option>
			<option value="sdrz">ʵ����֤��</option>
			<option value="jgdb">����������</option>
			<option value="znlc">������Ʊ�</option>
			</select>
		</div>
	</div>
	-->
	<div class="module_border">
		<div class="l">�������:</div>
		<div class="c">
			{if $_A.check_rank.centre_remark==1}
				{if $_A.borrow_result.centre_remark==""}
				<textarea name="centre_remark" cols="45" rows="5">{$_A.borrow_result.centre_remark}</textarea>
				{else}
				{$_A.borrow_result.centre_remark}
				{/if}
			{else}
				{$_A.borrow_result.centre_remark|default:���޸������}
			{/if}
		</div>
	</div>
	<!-- �ж��Ƿ���Ҫ����ίԱ����� -->
	{if $_A.is_committee_remark==1}
	<div class="module_border" >
		<div class="l">����ίԱ�����:</div>
		<div class="c">
			{if $_A.check_rank.committee_remark==1}
				{if $_A.borrow_result.committee_remark=="" && $_A.borrow_result.centre_remark!=""}
				<textarea name="committee_remark" cols="45" rows="5">{$_A.borrow_result.committee_remark}</textarea>
				{elseif $_A.borrow_result.centre_remark==""}
				<span style="color:red">�ȴ�����󷽿���д</span>
				{else}
				{$_A.borrow_result.committee_remark}
				{/if}
			{else}
				{$_A.borrow_result.committee_remark|default:���޸������}
			{/if}
		</div>
	</div>
	{/if}
	{if $_A.check_rank.verify_remark==1}
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
		<input type="radio" name="status" value="1"/>���ͨ�� <input type="radio" name="status" value="2" />��˲�ͨ��<input type="radio" name="status" value="0"  checked="checked"/>�����</div>
	</div>
	<div class="module_border" >
		<div class="l">�ۺ����:</div>
		<div class="c">
			<textarea name="verify_remark" cols="45" rows="5">{$_A.borrow_result.verify_remark}</textarea>
		</div>
	</div>
	{else}
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
		<input type="radio" disabled="disabled" {if $_A.borrow_result.status==1}checked="checked"{/if} />���ͨ�� <input type="radio" disabled="disabled" {if $_A.borrow_result.status==2}checked="checked"{/if} />��˲�ͨ��<input type="radio" disabled="disabled"  {if $_A.borrow_result.status==0}checked="checked"{/if} />�����</div>
	</div>
	<div class="module_border" >
		<div class="l">�ۺ����:</div>
		<div class="c">
			{$_A.borrow_result.verify_remark|default:���޸������}
		</div>
	</div>
	{/if}
	<div class="module_submit" >
		<input type="hidden" name="id" value="{$_A.borrow_result.id}" />
		<input type="hidden" name="user_id" value="{$_A.borrow_result.user_id}" />
		<input type="hidden" name="name" value="{$_A.borrow_result.name}" />
		{if $_A.check_rank.verify_remark==1}
			{if $_A.is_committee_remark==1 && $_A.borrow_result.committee_remark=="" && $_A.borrow_result.centre_remark!="" && $_A.check_rank.committee_remark!=1}
				<input type="button" disabled="disabled" value="�ȴ�������ĵ����" />
			{else $_A.check_rank.committee_remark==1}
				<input type="submit" name="reset" value="��˴˽���" />
			{/if}
		{else}
			{if ($_A.check_rank.centre_remark==1 && $_A.borrow_result.centre_remark=="") || ($_A.is_committee_remark==1 && $_A.check_rank.committee_remark==1 && $_A.borrow_result.committee_remark=="") || ($_A.check_rank.verify_remark==1 && $_A.borrow_result.verify_remark=="")}
			<input type="submit" name="reset" value="�ύ���" />
			{/if}
		{/if}
	</div>
	{/if}
	</form>
</div>
<!-- ���� ���� -->

<!-- ���н�� ��ʼ -->
{elseif $_A.query_type=="list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">�����</td>
			<td width="" class="main_td">�û���</td>
 			<td width="" class="main_td">������</td>
			<td width="" class="main_td">�����</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">�������</td>
			<td width="" class="main_td">����ʱ��</td>
			<td width="" class="main_td">״̬</td>
			{if $magic.get.status==3}<td width="" class="main_td">���ս�۳����</td>{else}
			<td width="" class="main_td">����</td>{/if}
		</tr>
		{foreach  from=$_A.borrow_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}<input type="hidden" name="id[]" value="{$item.id}" /></td>
			<td class="main_td1" ><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene" class="thickbox" title="�û���ϸ��Ϣ�鿴">	{$item.username}</a></td>
 			<td title="{$item.name}"  align="left">
			<span style="color:#FF0000">��{$item.show_name}��</span>
			<a href="/invest/a{$item.id}.html" target="_blank">{$item.name|truncate:10}</a>
			</td>
			<td>{$item.account}Ԫ</td>
			<td>{$item.apr}%</td>
			<td>{if $item.isday ==1}{$item.time_limit_day}��{ else}{$item.time_limit}����{/if}</td>
			<td>{$item.addtime|date_format}</td>
			<td>
				{if $item.status ==1}
				{if $item.biao_type=='lz' && $item.is_liubiao<1}
				��ֹͣ��ת
				{else}
				{if $item.account>$item.account_yes}
				�����б�..
				{else}
				���������
				{/if}
				{/if}
			{elseif $item.status ==0}�ȴ�����
			{elseif $item.status ==-1}<font color="#999999">��δ����</font>
			{elseif $item.status ==3}������ɹ�
			{elseif $item.status ==4}����δͨ��
			{elseif $item.status==5}������
			{else}����δͨ��{/if}
			
			</td>
			{if $magic.get.status==3}<td>{if $item.risk_fee==-1}δ�۳�{else}�ѿ۳�{$item.risk_fee}Ԫ{/if}&nbsp;&nbsp;&nbsp;<a href="{$_A.admin_url}&q=module/account/tgviewcash&view=2&order_id={$item.order_id}&a=cash">[�鿴]</a></td>{/if}
			<td>
				{if $item.status ==0}<a href="{$_A.query_url}/view{$_A.site_url}&user_id={$item.user_id}&id={$item.id}">����</a>{/if}
				{if $item.status==1 && $item.biao_type!='lz'}
					{if $item.account>$item.account_yes}
					<a href="{$_A.query_url}/full_view{$_A.site_url}&id={$item.id}">����</a>
					{else}
					{if $item.status!=3}<a href="{$_A.query_url}/full_view{$_A.site_url}&user_id={$item.user_id}&id={$item.id}">�������</a>{/if}
					{/if}
				{/if}
				{if $item.biao_type=='lz' && $item.is_liubiao>0}
				<a href="javascript:if(confirm('ȷ��Ҫֹͣ��ת�˱�ô')) location.href='{$_A.query_url}/stoplz{$_A.site_url}&id={$item.id}'">ֹͣ��ת</a>
				{/if}
			</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="{if $magic.get.status==3}10{else}9{/if}" >
		<div class="action">
			<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="������ǰ�б�" />
			</div>
			<div class="floatr">
				�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> 
				�����ͣ�<select name="biaoType" id="biaoType">
						<option value=""> ����</option>
						<option value="fast" {if $magic.request.biaoType=='fast'}selected{/if} >��Ѻ��</option>
						<option value="jin" {if $magic.request.biaoType=='jin'}selected{/if} >��ֵ��</option>
						<option value="miao" {if $magic.request.biaoType=='miao'}selected{/if} >�뻹��</option>
						<option value="xin" {if $magic.request.biaoType=='xin'}selected{/if} >���ñ�</option>
						<option value="lz" {if $magic.request.biaoType=='lz'}selected{/if} >��ת��</option>
					</select>
				״̬��<select id="status" ><option value="">ȫ��</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>�����б�..</option><option value="3" {if $magic.request.status==3} selected="selected"{/if}>������ɹ�</option><option value="5" {if $magic.request.status=="5"} selected="selected"{/if}>����δͨ��</option><option value="4" {if $magic.request.status=="4"} selected="selected"{/if}>���긴��ʧ��</option></select>
				����ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()" /> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()" />
				<input type="button" value="����" onclick="sousuo()" />
			</div>
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
<!-- ���н�� ���� -->

<!-- �����б� ��ʼ -->
{elseif $_A.query_type=="full"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">�����</td>
			<td width="" class="main_td" >�û���</td>
			<td width="" class="main_td" >������</td>
			<td width="" class="main_td" >�����</td>
			<td width="" class="main_td" >Ӧ����Ϣ</td>
			<td width="" class="main_td" >������</td>
			<td width="" class="main_td" >Ͷ�����</td>
			<td width="" class="main_td" >�������</td>
			<td width="" class="main_td" >����ʱ��</td>
			<td width="" class="main_td" >����ʱ��</td>
			<td width="" class="main_td" >״̬</td>
			<td width="" class="main_td" >����</td>
		</tr>
		{foreach  from=$_A.borrow_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td >{$item.id}</td>
			<td class="main_td1"  ><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="�û���ϸ��Ϣ�鿴">	{$item.username}</a></td>
			<!--<td title="{$item.name}">{$item.name|truncate:10}</td>-->
            <td title="{$item.name}" align="left">
			<span style="color:#FF0000">��{$item.show_name}��</span>
				<a href="/invest/a{$item.id}.html" target="_blank">{$item.name|truncate:10}</a>
			</td>
			<td>{$item.account}Ԫ</td>
			<td>
				{if $item.status==3}
                <script type="text/javascript">
                var tempInterest = ({$item.repayment_account|default:0}-{$item.account|default:0});
					document.write(tempInterest.toFixed(2));
                </script>
                {else}--{/if}
			</td>
			<td >{$item.apr}%</td>
			<td >{$item.tender_times|default:0}</td>
			<td >{if $item.isday==1 } 
                {$item.time_limit_day}��
                {else}
                {$item.time_limit}����
                {/if}
            </td>
			<td>{$item.addtime|date_format}</td>
			<td>{$item.verify_time|date_format}</td>
			<td>{if $item.status ==1} 
				{if $item.account>$item.account_yes}�����б�..{else}���������{/if}
			{elseif $item.status ==0}�ȴ�����{ elseif $item.status ==-1}<font color="#999999">��δ����</font>{ elseif $item.status ==3}������ɹ�{ elseif $item.status ==4}����δͨ��{else}����δͨ��{/if}</td>
			<td >{if $item.status ==0 }<a href="{$_A.query_url}/view{$_A.site_url}&user_id={$item.user_id}&id={$item.id}">���</a>{/if}  {if ($item.status == 0) || ($item.status==1 && ($item.biao_type!='lz' || ($item.biao_type=='lz' && $item.account_yes==0)))}
				{if $item.account>$item.account_yes}
				<a href="#" onClick="javascript:if(confirm('ȷ��Ҫ������?')) location.href='{$_A.query_url}/cancel{$_A.site_url}&id={$item.id}'">����</a>
				{else}
				{if $item.status!=3}<a href="{$_A.query_url}/full_view{$_A.site_url}&user_id={$item.user_id}&id={$item.id}">���</a>{/if}
				{/if}
			{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="12" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
			�����ͣ�<select name="biaoType" id="biaoType">
					<option value="">����</option>
					<option value="fast" {if $magic.request.biaoType=='fast'}selected{/if} >��Ѻ��</option>
					<option value="jin" {if $magic.request.biaoType=='jin'}selected{/if} >��ֵ��</option>
					<option value="miao" {if $magic.request.biaoType=='miao'}selected{/if} >�뻹��</option>
					<option value="xin" {if $magic.request.biaoType=='xin'}selected{/if} >���ñ�</option>
					<option value="lz" {if $magic.request.biaoType=='lz'}selected{/if} >��ת��</option>
				</select>
			״̬��<select id="status"><option value="">ȫ��</option><option value="3" {if $magic.request.status==3}selected="selected"{/if}>����ͨ��</option><option value="4" {if $magic.request.status==4}selected="selected"{/if}>����ʧ��</option></select>
			<input type="button" value="����" onclick="sousuo()" />
		</div>
		</td>
		</tr>
		<tr>
			<td colspan="12" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>	
</table>
<!-- �����б� ���� -->

<!-- ���긴��ͳ��� ��ʼ -->
{elseif $_A.query_type == "full_view" }
<div class="module_add">
	<div class="module_title"><strong>������������</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="c">
			<a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/user/view&user_id={$_A.borrow_result.user_id}&type=scene",500,230,"true","","true","text");'>	{$_A.borrow_result.username}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">���⣺</div>
		<div class="c">
			{$_A.borrow_result.name}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����</div>
		<div class="h">
			��{$_A.borrow_result.account}
		</div>
		<div class="l">�����ʣ�</div>
		<div class="h">
			{$_A.borrow_result.apr} %
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ѽ赽�</div>
		<div class="h">��{$_A.borrow_result.account_yes}</div>
		<div class="l">������ޣ�</div>
		<div class="h">
			{if $_A.borrow_result.isday==1 } 
                {$_A.borrow_result.time_limit_day}��
                {else}
                {$_A.borrow_result.time_limit}����
                {/if}
		</div>
		<div class="l">�����;��</div>
		<div class="h">
			{$_A.borrow_result.use|linkage:"borrow_use"}
		</div>
	</div>
	<div class="module_border">
	<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">�û�����</td>
			<td width="" class="main_td">Ͷ�ʽ��</td>
			<td width="" class="main_td">��Ч���</td>
			<td width="" class="main_td">״̬</td>
			<td width="" class="main_td">Ͷ��ʱ��</td>
		</tr>
		{foreach from=$_A.borrow_tender_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{ $item.id}</td>
			<td class="main_td1"><a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene",500,230,"true","","true","text");'>	{$item.username}</a></td>
			<td >{$item.money}Ԫ</td>
			<td ><font color="#FF0000">{$item.tender_account}Ԫ</font></td>
			<td >{if $item.money == $item.tender_account}ȫ��ͨ��{else}����ͨ��{/if}</td>
			<td >{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		</tr>
		{/foreach}
		<tr>
			<td colspan="9" class="page">
			{$_A.showpage}
			</td>
		</tr>
	</table>
	</div>
	{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}
	<div class="module_border">
	<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td">�ƻ�������</td>
			<td width="" class="main_td">Ԥ�����</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">��Ϣ</td>
		</tr>
		{foreach from=$_A.borrow_repayment key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$key+1}</td>
			<td>{$item.repayment_time|date_format:"Y-m-d"}</td>
			<td>��{$item.repayment_account}</td>
			<td>��{$item.capital}</td>
			<td>��{$item.interest}Ԫ</td>
		</tr>
		{/foreach}
	</table>
	</div>
	{/if}
	<div class="module_title"><strong>������ϸ����</strong></div>
	<div class="module_border">
		<div class="l">Ͷ�꽱����</div>
		<div class="h">
			{if $_A.borrow_result.award==0}�޽���{elseif $_A.borrow_result.award==2}������{$_A.borrow_result.funds}%{else}{$_A.borrow_result.part_account}{/if}
		</div>
		<div class="l">Ͷ��ʧ���Ƿ�����</div>
		<div class="h">
			{if $_A.borrow_result.is_false==0}��{else}��{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���ʱ�䣺</div>
		<div class="h">
			{$_A.borrow_result.addtime|date_format:"Y-m-d H:i:s"}
		</div>
		<div class="l">�б�ʱ�䣺</div>
		<div class="h">
			{$_A.borrow_result.verify_time|date_format:"Y-m-d H:i:s"}
		</div>
	</div>
	<div class="module_title"><strong>�û�ȷ��Э��</strong></div>
	<div class="module_border">
		<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td class="main_td">����</td>
			<td class="main_td">״̬</td>
			<td class="main_td">�ύʱ��</td>
			<td class="main_td">����</td>
		</tr>
		{foreach from=$_A.borrow_protocol key=key item=item}
		<tr>
			<td>{$item.name}</td>
			<td>{if $item.litpic==""}<img src="/themes/soonmes/images/no.gif" />{else}<img src="/themes/soonmes/images/ico_yes.gif" />{/if}</td>
			<td>{$item.addtime|date_format:Y-m-d H:i:s}</td>
			<td>{if $item.litpic==""}<a href="javascript:sendsmsprotocol('{$_A.query_url}/sendsmsprotocol','{$_A.borrow_result.id}')" name="sendsmsprotocol">���Ͷ���֪ͨ</a>{else}<a href="{$item.litpic}" class="thickbox" title="{$item.name}">�鿴</a>{/if}</td>
		</tr>
		{/foreach}
	</table>
	</div>
	{if $_A.borrow_result.status==1}
	<div class="module_title"><strong>��˴˽��</strong></div>
	<form name="form1" method="post"{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}action=""{else}action="{$_A.query_url}/cancel{$_A.site_url}"{/if} >
	<div class="module_border">
		<div class="l">״̬:</div>
		<div class="c">
		{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}
		<input type="radio" name="status" value="3" />����ͨ�� <input type="radio" name="status" value="4" checked="checked" />����ͨ�� </div>
		{else}
		<input type="radio" name="status" value="5" checked="checked" />����
		{/if}
	</div>
	{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}
	<div class="module_border" >
		<div class="l">��˱�ע:</div>
		<div class="c">
			<textarea name="repayment_remark" cols="45" rows="5">{ $_A.borrow_result.repayment_remark}</textarea>
		</div>
	</div>
	{/if}
	<div class="module_border" >
		<div class="l">��֤��:</div>
		<div class="c">
			<input name="valicode" type="text" size="11" maxlength="4"  tabindex="3"/>&nbsp;<img src="/plugins/index.php?q=imgcode" alt="���ˢ��" onClick="this.src='/plugins/index.php?q=imgcode&t=' + Math.random();" align="absmiddle" style="cursor:pointer" />
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="id" value="{$_A.borrow_result.id }" />
		{if $_A.borrow_result.account<=$_A.borrow_result.account_yes}
		<input type="button" name="reset" value="��˴˽���" onclick="document.forms['form1'].submit();this.disabled=true;submit_fool()" />
		{else}
		<input type="button" name="reset" value="���ش˽���" onclick="document.forms['form1'].submit();this.disabled=true;submit_fool()" />
		{/if}
	</div>
	</form>
	{/if}
</div>
<!-- ���긴��ͳ��� ���� -->
<!-- �ѻ��� ��ʼ -->
{elseif $_A.query_type=="repayment"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="70px" class="main_td">�����</td>
			<td width="" class="main_td">�û���</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">����ʱ��</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">������Ϣ</td>
			<td width="" class="main_td">����ʱ��</td>
			<td width="" class="main_td">������״̬</td>
			<td width="" class="main_td">�����״̬</td>
			<td width="" class="main_td">״̬</td>
		</tr>
		{foreach from=$_A.borrow_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="�û���ϸ��Ϣ�鿴">	{$item.username}</a></td>
			<td title="{$item.borrow_name}" align="left">
			<span style="color:#FF0000">��{$item.show_name}��</span>
			<a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name|truncate:10}</a></td>
			<td >{$item.order+1 }/{$item.time_limit }</td>
			<td >{$item.repayment_time|date_format:"Y-m-d"}</td>
			<td >{$item.repayment_account}Ԫ</td>
			<td >{$item.interest}Ԫ</td>
			<td >{$item.repayment_yestime|date_format:"Y-m-d"|default:-}</td>
			<td >{if $item.tg_status==1}�ѻ�{else}δ��{/if}</td>
			<td ><a href="{$_A.query_url}/tgreturn&repayment_id={$item.id}&a=borrow">{if $item.interest_fee_status==1}��ȫ���۳�{else}���ֿ۳�{/if}</a></td>
			<td >{if $item.status==1}<font color="#006600">�ѻ�</font>{elseif $item.status==2}<font color="#006600">��վ����</font>{else}<font color="#FF0000">δ��</font>{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="�����б�" />
		</div>
		<div class="floatr">
		����ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()"/> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()"/>
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
				�����ͣ�<select id="biaoType" name="biaoType">
				<option value="">ȫ��</option>
				<option value="1" {if $magic.request.biaoType==1}selected="selected"{/if}>��Ѻ��</option>
				<option value="2" {if $magic.request.biaoType==2}selected="selected"{/if}>��ֵ��</option>
				<option value="3" {if $magic.request.biaoType==3}selected="selected"{/if}>�뻹��</option>
				<option value="4" {if $magic.request.biaoType==4}selected="selected"{/if}>���ñ�</option>
			</select>
			<select id="status" >
			<option value="">ȫ��</option>
			<option value="1" {if $magic.request.status==1} selected="selected"{/if}>�ѻ�</option>
			<option value="2" {if $magic.request.status==2} selected="selected"{/if}>��վ����</option>
			<option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>δ��</option>
			</select><input type="button" value="����" onclick="sousuo()" />
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
<!-- �ѻ��� ���� -->
<!-- �йܷ��� ���� -->
{elseif $_A.query_type=="tgreturn"}
<div class="module_add">
	<div class="module_title"><strong>�йܷ���</strong></div>
	{foreach from=$_A.tgreturn.tg_return key=key item=item}
	<div class="module_border">
		<div class="l">{$key}</div>
		<div class="c">
			{$item}
		</div>
	</div>
	{/foreach}
	<div class="module_title"><strong>��Ϣ����ѿ۳�״��</strong></div>
	<div class="module_border">
		<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td width="" class="main_td">�û�����</td>
			<td width="" class="main_td">����Ӧ�ձ���</td>
			<td width="" class="main_td">����Ӧ����Ϣ</td>
			<td width="" class="main_td">Ӧ�պϼ�</td>
			<td width="" class="main_td">��Դ</td>
			<td width="" class="main_td">���պϼ�</td>
			<td width="" class="main_td">����������Ϣ</td>
			<td width="" class="main_td">Ӧ����Ϣ�����</td>
			<td width="" class="main_td">�۳�״̬</td>
			<td width="" class="main_td">����</td>
		</tr>
		{foreach from=$_A.tgreturn.interest_fee key=key item=item}
		<tr>
			<td>{$item.username}</td>
			<td>{$item.capital}Ԫ</td>
			<td>{$item.interest}Ԫ</td>
			<td>{$item.repay_account}Ԫ</td>
			<td>{if $item.is_sell==1}��ת��{elseif $item.is_buy==1}ծȨ��������{else}����{/if}</td>
			<td>{$item.repay_yesaccount}</td>
			<td>{$item.late_interest}Ԫ</td>
			<td>{$item.interest_fee}Ԫ</td>
			<td id="status_{$item.id}">{if $item.interest_fee_status>0}�ѿ۳�{$item.interest_fee_status}��{else}δ�۳�{/if}</td>
			<td id="cz_{$item.id}">{if $item.is_sell!=1 && $item.interest_fee_status==0}<a href="javascript:kc_interest_fee({$item.id})">�۳�</a>{else}--{/if}</td>
		</tr>
		{/foreach}
		</table>
	</div>
</div>
<script type="text/javascript">
var query_url = "{$_A.query_url}";
{literal}
function kc_interest_fee(id){
	$.jBox.tip('������','loading');
	$.get('/'+query_url+'/kcinterestfee','collection_id='+id,function(re){
		if(re==1){
			$.jBox.tip('�����ɹ�','success');
			$("#status_"+id).html("�ѿ۳�");
			$("#cz_"+id).html("--");
		}else{
			$.jBox.tip(re,'error');
		}
	})
}
{/literal}
</script>


<!-- ���� ��ʼ -->
{elseif $_A.query_type=="liubiao"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr>
			<td width="70px" class="main_td">�����</td>
			<td width="*" class="main_td" >�û���</td>
			<td width="" class="main_td" >������</td>
			<td width="" class="main_td" >�������</td>
			<td width="" class="main_td" >�����</td>
			<td width="" class="main_td" >��Ͷ���</td>
			<td width="" class="main_td" >��ʼʱ��</td>
			<td width="" class="main_td" >����ʱ��</td>
			<td width="" class="main_td" >״̬</td>
		</tr>
		{foreach from=$_A.borrow_list key=key item=item}
		<tr {if $key%2==1}class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="�û���ϸ��Ϣ�鿴">	{$item.username}</a></td>
			<td title="{$item.borrow_name}" align="left">
			<span style="color:#FF0000">��{$item.show_name}��</span>
			<a href="/invest/a{$item.id}.html" target="_blank">{$item.name|truncate:10}</a></td>
			<td>{$item.time_limit }����</td>
			<td>{$item.account }Ԫ</td>
			<td>{$item.account_yes }Ԫ</td>
			<td>{$item.verify_time|date_format:"Y-m-d"}</td>
			<td>{$item.verify_time+$item.valid_time*24*60*60|date_format:"Y-m-d"}</td>
			<td><a href="{$_A.query_url}/liubiao_edit&id={$item.id}{$_A.site_url}">�޸�</a></td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="10" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" />
			<input type="button" value="����" onclick="sousuo()" />
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
<!-- ���� ���� -->
<!-- �����޸� ��ʼ -->
{elseif $_A.query_type=="liubiao_edit"}
<div class="module_title"><strong>�������</strong></div>
	<div class="module_border">
		<div class="l">�û�����</div>
		<div class="h">
			{$_A.borrow_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">���⣺</div>
		<div >
			<a href="/invest/a{$_A.borrow_result.id}.html" target="_blank">{$_A.borrow_result.name}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">����ȣ�</div>
		<div class="h">
			{$_A.borrow_result.account}
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ѽ��ȣ�</div>
		<div class="h">
			{$_A.borrow_result.account_yes}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����ʱ�䣺</div>
		<div class="h">
			{$_A.borrow_result.verify_time|date_format}
		</div>
	</div>
	<div class="module_border">
		<div class="l">����ʱ�䣺</div>
		<div class="h">
			{$_A.borrow_result.verify_time+$_A.borrow_result.valid_time*24*60*60|date_format}
		</div>
	</div>
	<div class="module_title"><strong>���</strong></div>
	<form name="form1" method="post" action="">
	<div class="module_border">
		<div class="l">���״̬��</div>
		<div>
			{if $_A.borrow_result.biao_type=='lz'}{else}
			<input type="radio" name="status" value="1" />���귵�ؽ��{/if}<input type="radio" name="status" value="2" checked="checked" />�ӳ��������
		</div>
	</div>
	<div class="module_border">
		<div class="l">�ӳ�������</div>
		<div >
			<input type="text" name="days" value="{$_A.borrow_amount_result.account}" size="5" value="0" />��
		</div>
	</div>
	<div class="module_border">
		<div class="l">��֤�룺</div>
		<div >
			<input type="hidden" name="id" value="{$_A.borrow_result.id}">
			<input type="text" name="valicode" size="5" maxlength="4" /><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="���ˢ��" src="/plugins/index.php?q=imgcode">
		</div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="h">
			<input type="button" value="ȷ�����" onclick="document.forms['form1'].submit();this.disabled=true;submit_fool()" />
		</div>
	</div>
	</form>
<!-- �����޸� ���� -->
<!--��ȹ��� ��ʼ-->
{elseif $_A.query_type=="amount"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td" >�û�����</td>
			<td width="" class="main_td" >��������</td>
			<td width="" class="main_td" >ԭ�����</td>
			<td width="" class="main_td" >������</td>
			<td width="" class="main_td" >�¶��</td>
			<td width="" class="main_td" >����ʱ��</td>
			<td width="" class="main_td" >����</td>
			<td width="" class="main_td" >��ע</td>
			<td width="" class="main_td" >״̬</td>
			<td width="" class="main_td" >����</td>
		</tr>
		{foreach from=$_A.borrow_amount_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="�û���ϸ��Ϣ�鿴">	{$item.username}</a></td>
			<td width="80" >{if $item.type =="tender_vouch"}<a href="{$_A.query_url}/amount&type=tender_vouch&a=borrow">Ͷ�ʵ������</a>{elseif $item.type =="borrow_vouch"}<a href="{$_A.query_url}/amount&type=borrow_vouch&a=borrow">�������</a>{else}<a href="{$_A.query_url}/amount&type=credit&a=borrow">������ö��</a>{/if}</td>
			<td width="70" >{$item.account_old}Ԫ</td>
			<td width="70"  >{$item.account}Ԫ</td>
			<td>{$item.account_new}Ԫ</td>
			<td>{ $item.addtime|date_format}</td>
			<td>{ $item.content}</td>
			<td>{ $item.remark}</td>
			<td width="50">{if $item.status==2}<font color="#6699CC">�����</font>{elseif $item.status==1}�ɹ� {else}<font color="#FF0000">ʧ��</font>{/if}</td>
			<td width="70">{if $item.status==2}<a href="{$_A.query_url}/amount_view{$_A.site_url}&id={$item.id}">���/�鿴</a>{else}--{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="11" class="action">
		<div class="floatl">
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/>
			״̬��<select id="status" ><option value="">ȫ��</option><option value="2" {if $magic.request.status==2} selected="selected"{/if}>�ȴ����</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>��ͨ��</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>δͨ��</option></select> <input type="button" value="����" onclick="sousuo('{$_A.query_url}/amount{$_A.site_url}')" />
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
<!--��ȹ��� ����-->
<!--��ǰ�Ѿ����ڽ�� ��ʼ-->
{elseif $_A.query_type=="late"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">�����</td>
			<td width="" class="main_td" >�����</td>
			<td width="" class="main_td" >������</td>
			<td width="" class="main_td" >����</td>
			<td width="" class="main_td" >����ʱ��</td>
			<td width="" class="main_td" >Ӧ����Ϣ</td>
			<td width="" class="main_td" >��������</td>
			<td width="" class="main_td" >����</td>
			<td width="" class="main_td" >����</td>
		</tr>
		{foreach from=$_A.borrow_repayment_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{ $item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="�û���ϸ��Ϣ�鿴">	{$item.username}</a></td>
			<td align="left">
			<span style="color:#FF0000">��{$item.show_name}��</span>
			<a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name}</a></td>
			<td>{$item.order+1 }/{$item.time_limit}</td>
			<td>{$item.repayment_time|date_format:"Y-m-d"}</td>
			<td>{$item.repayment_account }Ԫ</td>
			<td>{$item.late_days}��</td>
			<td>{$item.late_interest}</td>
			<td>{if $item.status==2}<font color="#FF0000">�Ѵ���</font>{else}{if $item.late_days>0}<a href="{$_A.query_url}/late_repay{$_A.site_url}&id={$item.id}">����</a>{else}-{/if}{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="11" class="action">
		<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="�����б�" />
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username}"/> 
			״̬��<select id="status"><option value="">ȫ��</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>�ѻ�</option><option value="2" {if $magic.request.status==2} selected="selected"{/if}>��վ����</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>δ��</option></select> 
			<input type="button" value="����" onclick="sousuo()" />
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
<!-- ��ǰ�Ѿ����ڽ�� ���� -->

<!-- ��վ���� ��ʼ -->
{elseif $_A.query_type=="late_repay"}
<div class="module_title"><strong>������վ����</strong></div>
<div class="module_border">
	<div class="l">���⣺</div>
	<div>
		<span style="color:#FF0000">��{$_A.borrow_result.show_name}��</span>
		<a href="/invest/a{$_A.borrow_result.borrow_id}.html" target="_blank">{$_A.borrow_result.borrow_name}</a>
	</div>
</div>
<div class="module_border">
	<div class="l">����ˣ�</div>
	<div>{$_A.borrow_result.username}</div>
</div>
<div class="module_border">
	<div class="l">������</div>
	<div>{$_A.borrow_result.order+1 }/{$_A.borrow_result.time_limit}</div>
</div>
<div class="module_border">
	<div class="l">Ӧ��ʱ�䣺</div>
	<div>{$_A.borrow_result.repayment_time|date_format:"Y-m-d"}</div>
</div>
<div class="module_border">
	<div class="l">����������</div>
	<div>{$_A.borrow_result.late_days}��</div>
</div>
<div class="module_border">
	<div class="l">���ڷ���</div>
	<div>{$_A.borrow_result.late_interest}Ԫ</div>
</div>
<div class="module_border">
	<div class="l">Ӧ����</div>
	<div>{$_A.borrow_result.repayment_account}Ԫ</div>
</div>
<div class="module_title"><strong>Ͷ������Ϣ</strong></div>
<div class="module_border">
	<table  border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
		<tr>
			<td width="" class="main_td">ID</td>
			<td width="" class="main_td" >�û�����</td>
			<td width="" class="main_td" >vip״̬</td>
			<td width="" class="main_td" >����Ӧ�ձ���</td>
			<td width="" class="main_td" >����Ӧ����Ϣ</td>
			<td width="" class="main_td" >Ӧ�պϼ�</td>
			<td width="" class="main_td" >��վ��������</td>
			<td width="" class="main_td" >��վ��������</td>
			<td width="" class="main_td" >��վ������Ϣ</td>
			<td width="" class="main_td" >��վ�����ϼ�</td>
		</tr>
		{foreach  from=$_A.borrow_tender_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td>{$item.id}</td>
			<td class="main_td1"><a href="javascript:void(0)" onclick='tipsWindown("�û���ϸ��Ϣ�鿴","url:get?{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene",500,230,"true","","true","text");'>{$item.username}</a></td>
			<td>{if $item.vip_status==1}��{else}��{/if}</td>
			<td>{$item.capital}Ԫ</td>
			<td>{$item.interest}Ԫ</td>
			<td>{$item.repay_account}Ԫ</td>
			<td>{$item.bili*100}%</td>
			<td>{$item.webrepay_capital}Ԫ</td>
			<td>{$item.webrepay_interest}Ԫ</td>
			<td><font style="color:red">{$item.webrepay_account}Ԫ</font></td>
		</tr>
		{/foreach}
	</table>
</div>
<!--
{if $_A.borrow_result.status==0}
<div class="module_title"><strong>��վ����</strong></div>
{if $_A.borrow_result.advance_time<=$_A.borrow_result.late_days}
<form name="form1" method="post" action="">
<div class="module_border">
	<div class="l">��֤�룺</div>
	<input type="hidden" name="id" value="{$_A.borrow_result.id}">
	<div><input type="text" name="valicode" maxlength="4" size="5"><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="���ˢ��" src="/plugins/index.php?q=imgcode"></div>
</div>
<div style="text-align:center"><input type="button" value="ȷ����վ����" onclick="document.forms['form1'].submit();this.disabled=true;submit_fool()" /></div>
{else}
<h2 style="text-align:center">�˽�����δ����{$_A.borrow_result.advance_time}�죬���ܴ���</h2>
{/if}
</form>
{/if}-->
<!--
{if $_A.borrow_result.status==0}
<div class="module_title"><strong>����</strong></div>
<form name="form1" method="post" action="">
<div class="module_border">
	<div class="l">�û��ѻ��</div>
	<div class="c">
	<input type="radio" name="status" value="1" checked="checked" />��
	<input type="hidden" name="id" value="{$_A.borrow_result.id}" />
	<input type="hidden" name="is_user_repay" value="1" id="is_user_repay" />
	<input type="hidden" name="user_id" value="{$_A.borrow_result.user_id}" />
	</div>
</div>
<div class="module_border">
	<div class="l">��֤�룺</div>
	<div class="c"><input type="text" name="valicode" maxlength="4" size="5" /><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="���ˢ��" src="/plugins/index.php?q=imgcode"></div>
</div>
<div class="module_border">
	<div class="l"></div>
	<div class="c" style="color:red">ע����ȷ�ϵ������й��ѵ�����ִ�иò���</div>
</div>
<div style="text-align:center">
<input type="button" value="ȷ��ʹ�÷��մ����������" onclick="repay_dz(2)" />
<input type="button" value="ȷ���û��ѻ���������ѻ�����վδ����ʱʹ�ã�" onclick="repay_dz(1)" />
</div>
</form>
{literal}
<script type="text/javascript">
function repay_dz(u){
	$("#is_user_repay").val(u);
	document.forms['form1'].submit();
	submit_fool();
}
</script>
{/literal}
{/if}-->
<!-- ��վ���� ���� -->
<!-- ��Ѻ�굽�� ��ʼ -->
{elseif $_A.query_type=="lateFast"}
<table border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="" class="main_td">�����</td>
			<td width="" class="main_td">�û���</td>
			<td width="" class="main_td">������</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">����ʱ��</td>
			<td width="" class="main_td">Ӧ����Ϣ</td>
			<td width="" class="main_td">Ӧ����Ϣ</td>
			<td width="" class="main_td">��������</td>
			<td width="" class="main_td">����</td>
			<td width="" class="main_td">����</td>
		</tr>
                <?php  $showtime=date("y-m-d");?>
		{foreach from=$_A.borrow_repayment_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td >{$item.id}</td>
			<td class="main_td1" align="center"><a href="/{$_A.admin_url}&q=module/user/view&user_id={$item.user_id}&type=scene"  class="thickbox" title="�û���ϸ��Ϣ�鿴">	{$item.username}</a></td>
			<td align="left">
			<span style="color:#FF0000">��{$item.show_name}��</span>
			<a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name}</a></td>
			<td>{$item.order+1 }/{$item.time_limit}</td>
			<td >{$item.repayment_time|date_format:"Y-m-d"}</td>
			<td >{$item.repayment_account }Ԫ</td>
			<td >{$item.interest}Ԫ</td>
			<td >{$item.late_days}��</td>
			<td >{$item.late_interest}</td>
			<td >{if $item.status==2}<font color="#FF0000">�Ѵ���</font>{else}{if $item.late_days>=0}<a href="{$_A.query_url}/late_repay{$_A.site_url}&id={$item.id}">����</a>{else}--{/if}{/if}</td>
		</tr>
		{/foreach}
		<tr>
		<td colspan="11" class="action">
		<div class="floatl">
		<input type="button" onclick="sousuo('excel')" value="�����б�" />
		</div>
		<div class="floatr">
			�û�����<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}" />
			״̬��<select id="status" ><option value="">ȫ��</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>�ѻ�</option><option value="2" {if $magic.request.status==2} selected="selected"{/if}>��վ����</option><option value="0" {if $magic.request.status=="0"} selected="selected"{/if}>δ��</option></select> 
			<input type="button" value="����" onclick="sousuo('{$_A.query_url}/amount{$_A.site_url}')" />
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
<!--��Ѻ�굽�� ����-->
<!--������ ��ʼ-->
{elseif $_A.query_type=="amount_view"}
<div class="module_title"><strong>������</strong></div>
<div class="module_border">
	<div class="l">�û�����</div>
	<div class="h">
		{$_A.borrow_amount_result.username}
	</div>
</div>
<div class="module_border">
	<div class="l">������ͣ�</div>
	<div class="h">
		{if $_A.borrow_amount_result.type=="tender_vouch"}<font color="#FF0000">Ͷ�ʵ������</font>{elseif $_A.borrow_amount_result.type=="borrow_vouch"}<font color="#FF0000">�������</font>{else}���ö��{/if}
	</div>
</div>
<div class="module_border">
	<div class="l">ԭ����</div>
	<div class="h">
		{$_A.borrow_amount_result.account_old|default:0}
	</div>
</div>
<div class="module_border">
	<div class="l">�����ȣ�</div>
	<div class="h">
		{$_A.borrow_amount_result.account}
	</div>
</div>
<div class="module_border">
	<div class="l">���ݣ�</div>
	<div class="h">
		{$_A.borrow_amount_result.content}
	</div>
</div>
<div class="module_border">
	<div class="l">��ע��</div>
	<div class="h">
		{$_A.borrow_amount_result.remark}
	</div>
</div>
<div class="module_border">
	<div class="l">����ʱ�䣺</div>
	<div class="h">
		{$_A.borrow_amount_result.addtime|date_format}
	</div>
</div>
{if $_A.borrow_amount_result.status==2}
<div class="module_title"><strong>����</strong></div>
{if $_A.borrow_amount_result.credit_file==''}
�û�δ�ϴ�����
{else}
{foreach from=$_A.borrow_amount_result.credit_file item=item key=key}
<a href="{$item}" target="_blank">����{$key+1}</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
{/foreach}
{/if}
<div class="module_title"><strong>���</strong></div>
<form method="post" action="" name="form1">
<div class="module_border">
	<div class="l">���״̬��</div>
	<div class="h">
		<input type="radio" name="status" value="1" />ͨ��  <input type="radio" name="status" value="0" checked="checked" />��ͨ��
	</div>
</div>
<div class="module_border">
	<div class="l">ͨ����ȣ�</div>
	<div class="h">
		<input type="text" name="account" value="{$_A.borrow_amount_result.account}" />
		<input type="hidden" name="type" value="{ $_A.borrow_amount_result.type}" />
	</div>
</div>
<div class="module_border">
	<div class="l">��˱�ע��</div>
	<div class="h" style="width:305px">
		<textarea name="verify_remark" rows="5" cols="40" >{$_A.borrow_amount_result.verify_remark}</textarea>
	</div>
</div>
<div class="module_border">
	<div class="l">��֤�룺</div>
	<div class="h">
		<input type="text" name="valicode" size="5" maxlength="4"><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="���ˢ��" src="/plugins/index.php?q=imgcode">
	</div>
</div>
<div class="module_border">
	<div class="l"></div>
	<div class="h">
		<input type="button" name="tijiao" value="ȷ�����" onclick="sub_form()" />
	</div>
</div>
{/if}
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var form=document.forms['form1'];
	var verify_remark=form.elements['verify_remark'].value;
	var account=form.elements['account'].value;
	var valicode=form.elements['valicode'].value;
	account=parseFloat(account);
	var err="";
	if(verify_remark.length==0){
		err += "--��ע����Ϊ��\n";
	}
	if(account<=0 || isNaN(account)){
		err += "--ͨ�������������\n";
	}
	if(valicode.length!=4){
		err += "--��֤����������";
	}
	if(err.length>0){
		alert(err);
	}else{
		form.elements['tijiao'].disabled=true;
		form.elements['tijiao'].value="�ύ��..";
		form.submit();
		submit_fool();
	}
}
</script>
{/literal}
<!--������ ����-->
		
<!--��Ӷ�� ��ʼ-->
{elseif $_A.query_type=="addamount"}
<form method="post" action="" name="form1">
<div class="module_title"><strong>��Ӷ��</strong></div>
<div class="module_border">
	<div class="l">�û�����</div>
	<div class="h">
		<input type="text" name="username" />
	</div>
</div>
<div class="module_border">
	<div class="l">������ͣ�</div>
	<div class="h">
		<select name="type"><option value="credit">������ö��</option></select>
	</div>
</div>
<div class="module_border">
	<div class="l">��Ӷ�ȣ�</div>
	<div class="h">
		<input type="text" name="account" value="">
	</div>
</div>
<div class="module_border">
	<div class="l">��ϸ˵����</div>
	<div class="h" style="width:500px">
		<textarea rows="5" cols="40" name="content"></textarea>
	</div>
</div>
<div class="module_border">
	<div class="l">�����ط������ϸ˵����</div>
	<div class="h" style="width:500px">
		<textarea rows="5" cols="40" name="remark"></textarea>
	</div>
</div>
<div class="module_border">
	<div class="l">���״̬��</div>
	<div class="h">
		<input type="radio" name="status" value="1" checked="checked" />����ͨ��  <input type="radio" name="status" value="2"/>�������
	</div>
</div>
<div class="module_border">
	<div class="l">��˱�ע��</div>
	<div class="h" style="width:305px">
		<textarea name="verify_remark" rows="5" cols="40" >����Ա���</textarea>
	</div>
</div>
<div class="module_border">
	<div class="l">��֤�룺</div>
	<div class="h">
		<input type="text" name="valicode" size="5" maxlength="4"><img style="cursor:pointer; margin-left:3px;" onclick="this.src='/plugins/index.php?q=imgcode&amp;t=' + Math.random();" alt="���ˢ��" src="/plugins/index.php?q=imgcode">
	</div>
</div>
<div class="module_border">
	<div class="l"></div>
	<div class="h">
		<input type="button" name="tijiao" value="ȷ�����" onclick="sub_form()" />
	</div>
</div>
</form>
{literal}
<script type="text/javascript">
function sub_form(){
	var form=document.forms['form1'];
	var username = form.elements['username'].value;
	var verify_remark=form.elements['verify_remark'].value;
	var account=form.elements['account'].value;
	var valicode=form.elements['valicode'].value;
	account=parseFloat(account);
	var err="";
	if(username.length==0){
		err += "-- �û�������Ϊ��<br/>";
	}
	if(verify_remark.length==0){
		err += "-- ��ע����Ϊ��<br/>";
	}
	if(account<=0 || isNaN(account)){
		err += "-- ��Ӷ����������<br/>";
	}
	if(valicode.length!=4){
		err += "-- ��֤����������<br/>";
	}
	if(err.length>0){
		jQuery.jBox.info(err,'��ʾ');
		return false;
	}else{
		form.elements['tijiao'].disabled=true;
		form.elements['tijiao'].value="�ύ��..";
		form.submit();
		submit_fool();
	}
}
</script>
{/literal}
<!--��Ӷ�� ����-->

<!--ͳ�� ��ʼ-->
{elseif $_A.query_type=="tongji"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
		<tr >
			<td width="*" class="main_td">����</td>
			<td width="*" class="main_td">�ܶ�</td>
		</tr>
		<tr  class="tr2">
			<td >�ɹ�����ܶ�</td>
			<td >��{$_A.borrow_tongji.success_num}</td>
		</tr>
		<tr  >
			<td >�������ܶ�</td>
			<td >��{$_A.borrow_tongji.success_num1}</td>
		</tr>
		<tr  class="tr2">
			<td >δ�����ܶ�</td>
			<td >��{$_A.borrow_tongji.success_num0}</td>
		</tr>
		<tr  >
			<td >�����ܶ�</td>
			<td >{$_A.borrow_tongji.laterepay}</td>
		</tr>
		<tr  class="tr2">
			<td >���ڼ������ܶ�</td>
			<td >��{$_A.borrow_tongji.success_laterepay}</td>
		</tr>
		<tr >
			<td >����δ�����ܶ�</td>
			<td >��{$_A.borrow_tongji.false_laterepay}</td>
			
		</tr>
		
	</form>	
</table>
<!--ͳ�� ����-->

<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	  <form action="" method="post">
	  {foreach from="$_A.account_tongji" key=key  item="item"}
		<tr >
			<td width="*" class="main_td">��������</td>
			<td width="*" class="main_td">{$key}</td>
			<td width="" class="main_td">���</td>
		</tr>
		{foreach from="$item" key="_key" item="_item"}
		<tr  class="tr2">
			<td >{$_item.type_name}</td>
			<td >{$_item.type}</td>
			<td >��{$_item.num}</td>
		</tr>
		{/foreach}
	{/foreach}
	</form>	
</table>
<!--ͳ�� ����-->

{elseif $_A.query_type=="borrowtongji"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td">����</td>
		<td class="main_td">���</td>
		<td class="main_td">���ϼ�</td>
	</tr>
	{foreach from=$_A.borrowtongji item=item key=key}
	<tr>
	<td>{$item.name}</td>
	<td>{$item.value}</td>
	<td>{$item.account_yes}</td>
	</tr>
	{/foreach}
	<td colspan="3" class="action">
	���������<script src="/plugins/index.php?q=area&area={$magic.request.province}&type=p|c" type="text/javascript" ></script>
	�û����ͣ�<select name="user_type" id="user_type"><option value="">��ѡ��</option><option value="-2" {if $magic.get.user_type==-2}selected="selected"{/if}>������ʾ</option><option value=1 {if $magic.get.user_type==1}selected="selected"{/if}>˽Ӫҵ��</option><option value=2 {if $magic.get.user_type==2}selected="selected"{/if}>��н��</option></select>
	ҵ��Ա��<select name="salesman_user" id="salesman_user">
	<option value=-1>��ѡ��</option>
	<option value="-2" {if $magic.get.salesman_user==-2}selected="selected"{/if}>������ʾ</option>
	<option value=0 {if $magic.get.salesman_user==0}selected="selected"{/if}>��ҵ��Ա</option>
	{loop module="userinfo" function="GetSalesmanList" var="salesman"}
		<option value="{$salesman.user_id}" {if $magic.get.salesman_user==$salesman.user_id}selected="selected"{/if}>{$salesman.username}</option>
	{/loop}
	</select>
	�Ƽ�������{linkages nid="recommend_organ" value="$magic.get.recommend_organ" name="recommend_organ" default="��ѡ��" default2="������ʾ"}
	����������{linkages nid="recommend_organ" value="$magic.get.belong_organ" name="belong_organ" default="��ѡ��" default2="������ʾ"}
	ע��ʱ�䣺<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()" /> �� <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()" />
	<input type="button" value="����EXCEL����" onclick="sousuo_tongji('excel')" />
	<input type="button" value="����" onclick="sousuo_tongji()" />
	</td>
</table>

<!-- ȫ�ܴ������û� -->
{elseif $_A.query_type == "quickborrow"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">�������</td>
		<td width="" class="main_td">��ҵ��</td>
		<td width="" class="main_td">����/������</td>
		<td width="" class="main_td">��ϵ�绰</td>
		<th width="" class="main_td">�����</th>
		<th width="" class="main_td">����</th>
		<th width="" class="main_td">�ύʱ��</th>
		<th width="" class="main_td">״̬</th>
		<td width="" class="main_td">����</td>
	</tr>
	{foreach from=$_A.quickborrow key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{if $item.type==1}��ҵ����{else}��������{/if}</td>
		<td class="main_td1" align="center">{if $item.type==1}{$item.company_name}{else}--{/if}</td>
		<td class="main_td1" align="center">{$item.legal_person}</td>
		<td class="main_td1" align="center">{$item.phone}</td>
		<td class="main_td1" align="center">{$item.borrow_account}</td>
		<td class="main_td1" align="center">{$item.borrow_cycle}</td>
		<td class="main_td1" align="center">{$item.addtime|date_format:"Y-m-d H:i:s"}</td>
		<td class="main_td1" align="center">{if $item.status==1}ͨ��{elseif $item.status==1}δͨ��{else}�ȴ�����{/if}</td>
		<td class="main_td1" align="center"><a href="{$_A.query_url}/quickborrow_view&id={$item.id}&a=borrow" class="thickbox"  title="��ϸ��Ϣ�鿴">�鿴</a></td>
	</tr>
	{/foreach}
	<tr>
		<td colspan="10" class="action">
		<div class="floatl"></div>
		<div class="floatr">
			����/�����ˣ�<input type="text" name="company_name" id="company_name" value="{$magic.request.name|urldecode}"/>
			��ϵ�绰��<input type="text" name="phone" id="phone" value="{$magic.request.phone}"/>
			״̬��<select name="status" id="status"><option value=-1>ȫ��</option><option value=1 {if $magic.get.status==1}selected="selected"{/if}>ͨ��</option><option value=2 {if $magic.get.status==2}selected="selected"{/if}>δͨ��</option><option value=0 {if $magic.get.status==0}selected="selected"{/if}>�ȴ�����</option></select>
			<input type="button" value="����" onclick="sousuo_quickborrow()" />
		</div>
		</td>
	</tr>
	<tr>
		<td colspan="10" class="page">
		{$_A.showpage}
		</td>
	</tr>
<script type="text/javascript">
var url = '{$_A.query_url}/{$_A.query_type}{$_A.site_url}';
{literal}
function sousuo_quickborrow(){
	var name = $("#company_name").val();
	var status = $("#status").val();
	var phone = $("#phone").val();
	var sousuo = '';
	if(name!=''){
		sousuo += '&company_name='+name;
	}
	if(status!=''){
		sousuo += '&status='+status;
	}
	if(phone!=''){
		sousuo += '&phone='+phone;
	}
	location.href = url+sousuo;
}
</script>
{/literal}

{/if}


<script>
var urls = '{$_A.query_url}/{$_A.query_type}{$_A.site_url}';
{literal}
function sousuo(){
	var sou = "";
	if(arguments[0]=='excel'){
		sou += "&type=excel";
	}
	var username = $("#username").val() || "";
	var status = $("#status").val() || "";
	var dotime1 = $("#dotime1").val() || "";
	var dotime2 = $("#dotime2").val() || "";
	var biaoType = $("#biaoType").val() || "";
	if (username!=""){
		sou += "&username="+username;
	}
	if (status!=""){
		sou += "&status="+status;
	}
	if (dotime1!=""){
		sou += "&dotime1="+dotime1;
	}
	if (dotime2!=""){
		sou += "&dotime2="+dotime2;
	}
	if (biaoType!=""){
		sou += "&biaoType="+biaoType;
	}
	location.href=urls+sou;
}

function sousuoExcel(url){
	var sou = "";
	var username = $("#username").val();
	if (username!=""){
		sou += "&username="+username;
	}
	var keywords = $("#keywords").val();
	if (keywords!=""){
		sou += "&keywords="+keywords;
	}
	var status = $("#status").val();
	if (status!=""){
		sou += "&status="+status;
	}
	
	var dotime1 = $("#dotime1").val();
	if (dotime1!=""){
		sou += "&dotime1="+dotime1;
	}
	var dotime2 = $("#dotime2").val();
	if (dotime2!=""){
		sou += "&dotime2="+dotime2;
	}
	
	var biaoType = $("#biaoType").val();
	if (biaoType!=""){
		sou += "&biaoType="+biaoType;
	}
 
 
	if (sou!=""){
		
		location.href=url+sou;
	}
}

function sousuoFull(url){
	var sou = "";
	var username = $("#username").val();
	if (username!=""){
		sou += "&username="+username;
	}
	var biaoType = $("#biaoType").val();
	if (biaoType!=""){
		sou += "&biaoType="+biaoType;
	}
	var is_vouch = $("#is_vouch").val();
	if (is_vouch!=""){
		sou += "&is_vouch="+is_vouch;
	}
	if (sou!=""){
		
		location.href=url+sou;
	}
}

function sousuoBiaoOrder(url){
	var sou = "";

	var dotime1 = $("#dotime1").val();
	if (dotime1!=""){
		sou += "&dotime1="+dotime1;
	}
	var dotime2 = $("#dotime2").val();
	if (dotime2!=""){
		sou += "&dotime2="+dotime2;
	}
	
	if (sou!=""){
		
		location.href=url+sou;
	}
}
function sendsmsprotocol(url,id){
	$("[name='sendsmsprotocol']").html("���ŷ�����....");
	$.post(url,"borrow_id="+id,function(re){
		alert(re);
		$("[name='sendsmsprotocol']").html("���Ͷ���֪ͨ");
	})
}
	var selectCtl = document.getElementById("province");
	addAt(selectCtl,'������ʾ',-2,1);
	function addAt(selectCtl,optionValue,optionText,position){
		var userAgent = window.navigator.userAgent;
		if (userAgent.indexOf("MSIE") > 0) {
			var option = document.createElement("option");
			option.value = optionValue;
			option.innerText = optionText;
			selectCtl.insertBefore(option, selectCtl.options[position]);
		}else{
			selectCtl.insertBefore(new Option(optionValue, optionText), selectCtl.options[position]);
		}
	}
	function sousuo_tongji(){
		var sou = "";
		if(arguments[0]=='excel'){
			sou += '&type=excel';
		}
		var user_type = $("#user_type").val();
		var salesman_user = $("#salesman_user").val();
		var recommend_organ = $("#recommend_organ").val();
		var belong_organ = $("#belong_organ").val();
		var dotime1 = $("#dotime1").val();
		var dotime2 = $("#dotime2").val();
		var province = $("#province").val();
		var city = $("#city").val();
		if(belong_organ!=''){
			sou += "&belong_organ="+belong_organ;
		}
		if(recommend_organ!=''){
			sou += "&recommend_organ="+recommend_organ;
		}
		if(salesman_user!=''){
			sou += "&salesman_user="+salesman_user;
		}
		if(user_type!=''){
			sou += "&user_type="+user_type;
		}
		if(dotime1!=''){
			sou += "&dotime1="+dotime1;
		}
		if(dotime2!=''){
			sou += "&dotime2="+dotime2;
		}
		if(province!=''){
			sou += "&province="+province;
		}
		if(city!=''){
			sou += "&city="+city;
		}
		location.href=urls+sou;
	}
</script>
{/literal}