<div class="module_add">
	<div class="module_border">
		<div class="s">用户名:</div>
		<div class="h">
		 {$_A.user_result.username} </div>
		 <div class="s">邮箱:</div>
		<div class="h">
		 {$_A.user_result.email} </div>
	
	</div>
	<div class="module_border">
		 <div class="s">真实姓名:</div>
		<div class="h">
		 {$_A.user_result.realname} </div>
		<div class="s">电话:</div>
		<div class="h">
		 {$_A.user_result.phone} </div>
		
	</div>	
	<div class="module_border">
		 <div class="s">QQ:</div>
		<div class="h">
		 {$_A.user_result.qq} </div>
		 <div class="s">性别:</div>
		<div class="h">
		 {if $_A.user_result.sex==1}男{else}女{/if} </div>
	</div>
	
	<div class="module_border">
		 <div class="s">证件:</div>
		<div class="h">
		 {$_A.user_result.card_id} </div>
		 <div class="s">生日:</div>
		<div class="h">
		 {$_A.user_result.birthday|date_format:"Y-m-d "} </div>
	</div>
	
	<div class="module_border">
		<div class="s">籍贯:</div>
		<div class="h">
		 {$_A.user_result.area|area} </div>
		 <div class="s">民族:</div>
		<div class="h">
		 {$_A.user_result.nation|linkage} </div>
		
	</div>
	<div class="module_border">
		<div class="s">可用余额 :</div>
		<div class="h">
		 {$_A.user_result.use_money} </div>
		 <div class="s">信用积分:</div>
		<div class="h">
		 {$_A.user_result.credit_jifen} </div>
	</div>
    <br/><br/><div style="background-color: #e8e8e8;height: 27px;">用户上传的资料</div>
    <table width="100%">
	{foreach from=$_A.user_attestation key=key item=item}
    	<tr {if $key%2==1}style="background-color: #F5F5F5;"{/if}>
        	<td>{$item.type_name}</td><td width="15%">{if $item.status==1}审核通过{elseif $item.status==2}审核未通过{else}未审核{/if}</td><td width="15%"><a href="{$item.litpic}" target="_blank">查看</a>&nbsp;/&nbsp;<a href="{$_A.admin_url}&q=module/attestation/view&site_id=26&a=attestation&id={$item.id}" target="_blank">审核</a></td>
        </tr>
    {/foreach}
    </table>
</div>