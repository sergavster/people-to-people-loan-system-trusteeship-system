<div class="module_add">
	<div class="module_border">
		<div class="s">�û���:</div>
		<div class="h">
		 {$_A.user_result.username} </div>
		 <div class="s">����:</div>
		<div class="h">
		 {$_A.user_result.email} </div>
	
	</div>
	<div class="module_border">
		 <div class="s">��ʵ����:</div>
		<div class="h">
		 {$_A.user_result.realname} </div>
		<div class="s">�绰:</div>
		<div class="h">
		 {$_A.user_result.phone} </div>
		
	</div>	
	<div class="module_border">
		 <div class="s">QQ:</div>
		<div class="h">
		 {$_A.user_result.qq} </div>
		 <div class="s">�Ա�:</div>
		<div class="h">
		 {if $_A.user_result.sex==1}��{else}Ů{/if} </div>
	</div>
	
	<div class="module_border">
		 <div class="s">֤��:</div>
		<div class="h">
		 {$_A.user_result.card_id} </div>
		 <div class="s">����:</div>
		<div class="h">
		 {$_A.user_result.birthday|date_format:"Y-m-d "} </div>
	</div>
	
	<div class="module_border">
		<div class="s">����:</div>
		<div class="h">
		 {$_A.user_result.area|area} </div>
		 <div class="s">����:</div>
		<div class="h">
		 {$_A.user_result.nation|linkage} </div>
		
	</div>
	<div class="module_border">
		<div class="s">������� :</div>
		<div class="h">
		 {$_A.user_result.use_money} </div>
		 <div class="s">���û���:</div>
		<div class="h">
		 {$_A.user_result.credit_jifen} </div>
	</div>
    <br/><br/><div style="background-color: #e8e8e8;height: 27px;">�û��ϴ�������</div>
    <table width="100%">
	{foreach from=$_A.user_attestation key=key item=item}
    	<tr {if $key%2==1}style="background-color: #F5F5F5;"{/if}>
        	<td>{$item.type_name}</td><td width="15%">{if $item.status==1}���ͨ��{elseif $item.status==2}���δͨ��{else}δ���{/if}</td><td width="15%"><a href="{$item.litpic}" target="_blank">�鿴</a>&nbsp;/&nbsp;<a href="{$_A.admin_url}&q=module/attestation/view&site_id=26&a=attestation&id={$item.id}" target="_blank">���</a></td>
        </tr>
    {/foreach}
    </table>
</div>