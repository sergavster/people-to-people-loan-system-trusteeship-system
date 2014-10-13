{if $_A.query_type == "list"}
<table  border="0" cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<tr>
		<td width="" class="main_td">ID</td>
		<td width="" class="main_td">识别码</td>
		<td width="" class="main_td">会员类型</td>
		<td width="" class="main_td">是否有效</td>
		<td width="" class="main_td">手续费比例</td>
		<td width="" class="main_td">最低提现费用</td>
		<td width="" class="main_td">单笔最高金额</td>
		<td width="" class="main_td">单笔最低金额</td>
		<td width="" class="main_td">每天最高累计金额</td>
		<!--<td width="" class="main_td">到账时间</td>
		<td width="" class="main_td">快速提现最小金额</td>
		<td width="" class="main_td">快速提现费</td>-->
		<td width="" class="main_td">操作</td>
	</tr>
	<form action="" method="post">
	{foreach from=$_A.cash_rule key=key item=item}
	<tr {if $key%2==1} class="tr2"{/if}>
		<td class="main_td1" align="center">{$item.id}</td>
		<td class="main_td1" align="center">{$item.nid}</td>
		<td class="main_td1" align="center">{$item.name}</td>
		<td class="main_td1" align="center">{if $item.status==1}有效{else}无效{/if}</td>
		<td class="main_td1" align="center">{$item.cash_scale}</td>
		<td class="main_td1" align="center">{$item.min_scale}元</td>
		<td class="main_td1" align="center">{$item.max_money}元</td>
		<td class="main_td1" align="center">{$item.min_money}元</td>
		<td class="main_td1" align="center">{$item.max_day_money}元</td>
		<!--<td class="main_td1" align="center">{$item.arrival_time}小时</td>
		<td class="main_td1" align="center">{$item.fast_min_money}元</td>
		<td class="main_td1" align="center">{$item.fast_cash_scale}</td>-->
		<td class="main_td1" align="center"><a href="{$_A.query_url}/edit&id={$item.id}{$_A.site_url}">修改</a></td>
	</tr>
	{/foreach}
	</form>
</table>

{elseif $_A.query_type == "edit" || $_A.query_type == "new"}
<div class="module_add">
	<form name="form_user" method="post" action="">
	<div class="module_title"><strong>{if $_A.query_type == "edit"}编辑{else}添加{/if}</strong></div>
	<div class="module_border">
		<div class="l">识别码：</div>
		<div class="c">
			{if $_A.query_type == "edit"}
			{$_A.cash_rule.nid}<input type="hidden" name="nid" value="{$_A.cash_rule.nid}" />
			{else}
			<input type="text" name="nid" />
			{/if}
		</div>
	</div>
	<div class="module_border">
		<div class="l">会员类别：</div>
		<div class="c">
			<input name="name" type="text" value="{$_A.cash_rule.name }" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">是否有效：</div>
		<div class="c">
			<input type="checkbox" name="status" value="1" {if $_A.cash_rule.status==1} checked="checked"{/if} />
		</div>
	</div>
	<div class="module_border">
		<div class="l">手续费比例：</div>
		<div class="c">
			<input name="cash_scale" type="text" value="{$_A.cash_rule.cash_scale}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">最低提现费用：</div>
		<div class="c">
			<input name="min_scale" type="text" value="{$_A.cash_rule.min_scale}" class="input_border" />元
		</div>
	</div>
	<div class="module_border">
		<div class="l">单笔最高金额：</div>
		<div class="c">
			<input name="max_money" type="text" value="{$_A.cash_rule.max_money}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">单笔最低金额：</div>
		<div class="c">
			<input name="min_money" type="text" value="{$_A.cash_rule.min_money}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">每天最高累计金额：</div>
		<div class="c">
			<input name="max_day_money" type="text" value="{$_A.cash_rule.max_day_money}" class="input_border" />
		</div>
	</div>
    <!--
	<div class="module_border">
		<div class="l">到账时间：</div>
		<div class="c">
			<input name="arrival_time" type="text" value="{$_A.cash_rule.arrival_time}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">快速提现最小金额：</div>
		<div class="c">
			<input name="fast_min_money" type="text" value="{$_A.cash_rule.fast_min_money}" class="input_border" />
		</div>
	</div>
	<div class="module_border">
		<div class="l">快速提现费：</div>
		<div class="c">
			<input name="fast_cash_scale" type="text" value="{$_A.cash_rule.fast_cash_scale}" class="input_border" />
		</div>
	</div>-->
	<div class="module_submit border_b" >
	{if $_A.query_type == "edit"}<input type="hidden" name="id" value="{$_A.cash_rule.id }" />{/if}
	<input type="submit" value="确认提交" />
	<input type="reset" name="reset" value="重置表单" />
	</div>
	</form>
</div>
{/if}