{if $_A.query_type == "new" || $_A.query_type == "edit" || $_A.query_type == "start" }
<div class="module_add">
<form name="form1" method="post" action=""  enctype="multipart/form-data">
	<div class="module_title"><strong>{if $_A.query_type == "edit"}编辑{else}添加{/if}</strong></div>
	<div class="module_border">
		<div class="w">支付方式名称：</div>
		<div class="c">
			<input type="text" name="name"  class="input_border" value="{ $_A.payment_result.name}" size="30" />
		</div>
	</div>
	{foreach from="$_A.payment_result.fields" item="item" }
	<div class="module_border">
		<div class="w">{$item.label}</div>
		<div class="c">
			{if $item.type=="string"}
			<input type="text" name="config[{$key}]"  class="input_border" value="{ $item.value}" size="30" />
			{elseif $item.type=="select"}
			<select name="config[{$key}]">
				{foreach from="$item.options" key="_key" item="var"}
				<option value="{$_key}" {if $item.value==$_key} selected="selected"{/if}>{$var}</option>
				{/foreach}
			</select>
			{/if}
		</div>
	</div>
	{/foreach}
	<div class="module_border">
		<div class="w">支付手续费设置：</div>
		<div class="c">
			<input type="radio" name="fee_type" value="0"  {if $_A.payment_result.fee_type == 0 ||$_A.payment_result.fee_type ==""}checked="checked"{/if} onclick="change(0)"/>按比例收费 <input type="radio" name="fee_type" value="1"  { if $_A.payment_result.fee_type ==1 }checked="checked"{/if } onclick="change(1)"/>固定费用 </div>
	</div>
	<div class="module_border">
		<div class="w">费率/金额：</div>
		<div class="c">
			<div id="fee"><input type="text" name="max_fee" size="5" value="{$_A.payment_result.max_fee}" />说明：顾客将支付订单总金额乘以此费率作为手续费</div>
			<div id="fee_money" style="display:none"><input type="text" name="max_money" size="5" value="{$_A.payment_result.max_money}" />元，说明：顾客每笔订单需要支付的手续费；</div>
		 </div>
	</div>
	{if $_A.payment_result.nid=="offline"}
	<div class="module_border">
		<div class="w">线下充值奖励：</div>
		<div class="c">
			<input type="radio" name="reward" value="0" {if $_A.payment_result.reward==0}checked="checked"{/if} />不奖励
			<input type="radio" name="reward" value="1" {if $_A.payment_result.reward==1}checked="checked"{/if} />奖励红包
			<input type="radio" name="reward" value="2" {if $_A.payment_result.reward==2}checked="checked"{/if} />奖励现金
		 </div>
	</div>
	<div class="module_border">
		<div class="w">线下充值奖励方式：</div>
		<div class="c">
			<input type="radio" name="reward_type" value="1" onclick="rewardType(1)" {if $_A.payment_result.reward_type==1}checked="checked"{/if} />比例奖励
			<input type="radio" name="reward_type" value="2" onclick="rewardType(2)" {if $_A.payment_result.reward_type==2}checked="checked"{/if} />固定奖励
		 </div>
	</div>
	<div class="module_border">
		<div class="w">线下充值奖励条件：</div>
		<div class="c">
			单笔充值金额大于<input type="text" name="reward_where" value="{$_A.payment_result.reward_where}" />元
		 </div>
	</div>
	<div class="module_border">
		<div class="w">线下充值奖励额度：</div>
		<div class="c">
			<div id="reward_bl">比例奖励<input type="text" name="reward_bl" size="5" value="{$_A.payment_result.reward_bl}" /></div>
			<div id="reward_ed" style="display:none">固定奖励<input type="text" name="reward_ed" size="5" value="{$_A.payment_result.reward_ed}" />元</div>
		 </div>
	</div>
	{/if}
	<div class="module_border">
		<div class="w">排序:</div>
		<div class="c">
			<input type="text" name="order"  class="input_border" value="{$_A.payment_result.order|default:10}" size="10" />
		</div>
	</div>
	<script charset="utf-8" src="/plugins/editor/kindeditor/kindeditor-min.js"></script>
<script charset="utf-8" src="/plugins/editor/kindeditor/lang/zh_CN.js"></script>
	<div class="module_border">
		<div class="w">描述：</div>
		<div class="c">
			<textarea name="description" id="description"  style="width:700px;height:300px;" >
            {$_A.payment_result.description}
</textarea>
		</div>
	</div>
	<div class="module_submit" >
		<input type="hidden" name="nid" value="{$_A.payment_result.nid }" />
		<input type="hidden" name="status" value="{$_A.payment_result.status|default:1}" />
		<input type="hidden" name="type" value="{$_A.payment_result.type}" />
		{if $_A.query_type == "edit"}
		<input type="hidden" name="id" value="{$magic.request.id }" />
		{/if}
		<input type="submit"  name="submit" value="确认提交" />
		<input type="reset"  name="reset" value="重置表单" />
	</div>
</div>
</form>
{literal}
<script>
function change(type){
	if (type==1){
		$("#fee").hide();
		$("#fee_money").show();
	}else{
		$("#fee_money").hide();
		$("#fee").show();
	}
}function rewardType(type){
	if(type==1){
		$("#reward_bl").show();
		$("#reward_ed").hide();
	}else{
		$("#reward_bl").hide();
		$("#reward_ed").show();
	}
}
//editor add by weego 20120615 for 网页编辑器
			var editor;
			KindEditor.ready(function(K) {
				editor = K.create('textarea[name="description"]', {
					allowFileManager : true
				});
			});
			
function check_form(){
/*
	 var frm = document.forms['form1'];
	 var title = frm.elements['name'].value;
	 var errorMsg = '';
	  if (title.length == 0 ) {
		errorMsg += '标题必须填写' + '\n';
	  }
	  if (errorMsg.length > 0){
		alert(errorMsg); return false;
	  } else{  
		return true;
	  }
	  */
}

</script>
{/literal}

{elseif $_A.query_type == "all" }
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
	<tr >
		<td width="*" class="main_td">支付LOGO</td>
		<td width="*" class="main_td">支付名称</td>
		<td width="*" class="main_td">支付介绍</td>
		<td width="" class="main_td">操作</td>
	</tr>
	{ foreach  from=$_A.payment_list key=key item=item}
		<tr class="tr1">
		<td><img src="{ $item.logo}" height="50" /></td>
		<td>{$item.name}</td>
		<td>{$item.description}</td>
		<td>{if $item.type==1}<a href="{$_A.query_url}/start{$_A.site_url}&nid={$item.nid}" >开启</a>{else}<a href="{$_A.query_url}/new{$_A.site_url}&nid={$item.nid}" >添加</a>{/if}</td>
		</tr>
		{ /foreach}
		
	</form>	
</table>

{elseif $_A.query_type == "list" }
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="{$_A.query_url}/action{$_A.site_url}" method="post">
	<tr >
		<td width="*" class="main_td">支付LOGO</td>
		<td width="*" class="main_td">支付名称</td>
		<td width="*" class="main_td">支付介绍</td>
		<td width="" class="main_td">操作</td>
	</tr>
	{ foreach  from=$_A.payment_list key=key item=item}
		<tr class="tr1">
		<td><img src="{ $item.logo}" height="50" /></td>
		<td>{$item.name}</td>
		<td>{$item.description}</td>
		<td><a href="{$_A.query_url}/edit{$_A.site_url}&nid={$item.nid}&id={$item.id}" >配置</a> |  <a href="#" onClick="javascript:if(confirm('确定要删除吗?删除后将不可恢复')) location.href='{$_A.query_url}/del{$_A.site_url}&id={$item.id}'">删除</a> | {if $item.status==1}<a href="{$_A.query_url}/list{$_A.site_url}&nid={$item.nid}&id={$item.id}&status=0" >停用</a>{else}<a href="{$_A.query_url}/list{$_A.site_url}&nid={$item.nid}&id={$item.id}&status=1" >启用</a>{/if} </td>
		</tr>
		{ /foreach}
		
	</form>
</table>
{/if}