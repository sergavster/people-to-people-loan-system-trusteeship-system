{if $_A.query_type=="list"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">原始借款标</td>
			<td width="" class="main_td">出售人</td>
 			<td width="" class="main_td">出售债权</td>
			<td width="" class="main_td">出售比率</td>
			<td width="" class="main_td">出售金额</td>
			<td width="" class="main_td">有效期</td>
			<td width="" class="main_td">债权剩余时间</td>
			<td width="" class="main_td">提交时间</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{foreach  from=$_A.zqzr_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1"><a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name}</a></td>
 			<td>{$item.username}</td>
			<td>{$item.y_account}元</td>
			<td>100%</td>
			<td>{$item.account}元</td>
			<td>{$item.valid_time}天</td>
			<td>{$item.show_y_timelimit}</td>
			<td>{$item.addtime|date_format}</td>
			<td>
				{if $item.status ==0}
				等待初审
				{elseif $item.status ==2}
				初审失败
				{elseif $item.status ==4}
				复审失败
				{elseif $item.status ==5}
				用户取消
                {elseif $item.status ==3}
                完成
				{elseif $item.account>$item.account_yes}
				正在转让中..
				{else}
				满标审核中
				{/if}
			</td>
			<td>
				{if $item.status ==0}
				<a href="{$_A.query_url}/view{$_A.site_url}&id={$item.id}">初审</a>
				{elseif $item.status ==2}
				--
				{elseif $item.status ==4}
				--
				{elseif $item.status ==5}
				--
                {elseif $item.status ==3}
				--
				{elseif $item.account>$item.account_yes}
				<a href="{$_A.query_url}/repeal{$_A.site_url}&id={$item.id}">撤销</a>
				{else}
				<a href="{$_A.query_url}/full{$_A.site_url}&id={$item.id}">复审</a>
				{/if}
			</td>
		</tr>
		{/foreach}
		<!--<tr>
		<td colspan="9" >
		<div class="action">
			<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="导出当前列表" />
			</div>
			<div class="floatr">
				用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> 
				标类型：<select name="biaoType" id="biaoType">
						<option value=""> 所有</option>
						<option value="fast" {if $magic.request.biaoType=='fast'}selected{/if} >抵押标</option>
						<option value="jin" {if $magic.request.biaoType=='jin'}selected{/if} >净值标</option>
						<option value="miao" {if $magic.request.biaoType=='miao'}selected{/if} >秒还标</option>
						<option value="xin" {if $magic.request.biaoType=='xin'}selected{/if} >信用标</option>
						<option value="lz" {if $magic.request.biaoType=='lz'}selected{/if} >流转标</option>
					</select>
				状态：<select id="status" ><option value="">全部</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>正在招标..</option><option value="3" {if $magic.request.status==3} selected="selected"{/if}>满标借款成功</option><option value="5" {if $magic.request.status=="5"} selected="selected"{/if}>初审未通过</option><option value="4" {if $magic.request.status=="4"} selected="selected"{/if}>满标复审失败</option></select>
				发布时间：<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()" /> 到 <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()" />
				<input type="button" value="搜索" onclick="sousuo()" />
			</div>
		</div>
		</td>
		</tr>-->
		<tr>
			<td colspan="10" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table>

<!--初审-->
{elseif $_A.query_type=="view"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return submit_fool();" enctype="multipart/form-data" >
	<div class="module_title"><strong>债权转让信息</strong></div>
	<div class="module_border">
		<div class="l">原始借款标：</div>
		<div class="c">
		<a href="/invest/a{$_A.zqzr_result.borrow_id}.html" target="_blank">{$_A.zqzr_result.borrow_name}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">出售人：</div>
		<div class="c">
		{$_A.zqzr_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">出售债权：</div>
		<div class="c">
		{$_A.zqzr_result.y_account}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">出售价格：</div>
		<div class="c">
		{$_A.zqzr_result.account}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">每份价格：</div>
		<div class="c">
		{$_A.zqzr_result.every_account}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">份数：</div>
		<div class="c">
		{$_A.zqzr_result.account/$_A.zqzr_result.every_account}份
		</div>
	</div>
	<div class="module_border">
		<div class="l">每份待收：</div>
		<div class="c">
		{$_A.zqzr_result.every_collection}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">有效期：</div>
		<div class="c">
		{$_A.zqzr_result.valid_time}
		</div>
	</div>
	<div class="module_border">
		<div class="l">债权剩余时间：</div>
		<div class="c">
		{$_A.zqzr_result.show_y_timelimit}
		</div>
	</div>
	<div class="module_border">
		<div class="l">提交时间：</div>
		<div class="c">
		{$_A.zqzr_result.addtime|date_format}
		</div>
	</div>
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			{if $_A.zqzr_result.status ==0}
			等待初审
			{elseif $_A.zqzr_result.status ==2}
			初审失败
			{elseif $_A.zqzr_result.status ==4}
			复审失败
			{elseif $_A.zqzr_result.status ==5}
			用户取消
			{elseif $_A.zqzr_result.account>$_A.zqzr_result.account_yes}
			正在转让中..
			{else}
			满标审核中
			{/if}
		</div>
	</div>
	<div class="module_title"><strong>债权转让初审</strong></div>
	{if $_A.zqzr_result.status==0}
	<div class="module_border">
		<div class="l">初审结果：</div>
		<div class="c">
		<label><input type="radio" value="2" name="status" checked="checked" />不通过</label>
		<label><input type="radio" value="1" name="status" />通过</label>
		</div>
	</div>
	<div class="module_border">
		<div class="l">备注：</div>
		<div class="c">
			<textarea style="width:300px;height:100px" name="verify_remark"></textarea>
		</div>
	</div>
    <div class="module_border">
		<div class="l">注：：</div>
		<div class="c" style="color:red">
			一旦审核通过该笔债权就直接交易给网站
		</div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c">
			<input type="hidden" value="{$_A.zqzr_result.id}" name="id" />
			<input type="submit" value="确认提交" />
		</div>
	</div>
	{/if}
	
	</form>
</div>
{elseif $_A.query_type=="fulllist"}
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	<form action="" method="post">
		<tr>
			<td width="70px" class="main_td">原始借款标</td>
			<td width="" class="main_td">出售人</td>
 			<td width="" class="main_td">出售债权</td>
			<td width="" class="main_td">出售比率</td>
			<td width="" class="main_td">出售金额</td>
			<td width="" class="main_td">有效期</td>
			<td width="" class="main_td">债权剩余时间</td>
			<td width="" class="main_td">提交时间</td>
			<td width="" class="main_td">状态</td>
			<td width="" class="main_td">操作</td>
		</tr>
		{foreach  from=$_A.zqzr_list key=key item=item}
		<tr {if $key%2==1} class="tr2"{/if}>
			<td class="main_td1"><a href="/invest/a{$item.borrow_id}.html" target="_blank">{$item.borrow_name}</a></td>
 			<td>{$item.username}</td>
			<td>{$item.y_account}元</td>
			<td>100%</td>
			<td>{$item.account}元</td>
			<td>{$item.valid_time}天</td>
			<td>{$item.show_y_timelimit}</td>
			<td>{$item.addtime|date_format}</td>
			<td>
				{if $item.status ==0}
				等待初审
				{elseif $item.status ==2}
				初审失败
				{elseif $item.status ==4}
				复审失败
				{elseif $item.status ==5}
				用户取消
				{elseif $item.account>$item.account_yes}
				正在转让中..
				{else}
				满标审核中
				{/if}
			</td>
			<td>
				{if $item.status ==0}
				<a href="{$_A.query_url}/view{$_A.site_url}&id={$item.id}">初审</a>
				{elseif $item.status ==2}
				--
				{elseif $item.status ==4}
				--
				{elseif $item.status ==5}
				--
				{elseif $item.account>$item.account_yes}
				<a href="{$_A.query_url}/repeal{$_A.site_url}&id={$item.id}">撤销</a>
				{else}
				<a href="{$_A.query_url}/full{$_A.site_url}&id={$item.id}">复审</a>
				{/if}
			</td>
		</tr>
		{/foreach}
		<!--<tr>
		<td colspan="9" >
		<div class="action">
			<div class="floatl">
			<input type="button" onclick="sousuo('excel')" value="导出当前列表" />
			</div>
			<div class="floatr">
				用户名：<input type="text" name="username" id="username" value="{$magic.request.username|urldecode}"/> 
				标类型：<select name="biaoType" id="biaoType">
						<option value=""> 所有</option>
						<option value="fast" {if $magic.request.biaoType=='fast'}selected{/if} >抵押标</option>
						<option value="jin" {if $magic.request.biaoType=='jin'}selected{/if} >净值标</option>
						<option value="miao" {if $magic.request.biaoType=='miao'}selected{/if} >秒还标</option>
						<option value="xin" {if $magic.request.biaoType=='xin'}selected{/if} >信用标</option>
						<option value="lz" {if $magic.request.biaoType=='lz'}selected{/if} >流转标</option>
					</select>
				状态：<select id="status" ><option value="">全部</option><option value="1" {if $magic.request.status==1} selected="selected"{/if}>正在招标..</option><option value="3" {if $magic.request.status==3} selected="selected"{/if}>满标借款成功</option><option value="5" {if $magic.request.status=="5"} selected="selected"{/if}>初审未通过</option><option value="4" {if $magic.request.status=="4"} selected="selected"{/if}>满标复审失败</option></select>
				发布时间：<input type="text" name="dotime1" id="dotime1" value="{$magic.request.dotime1}" size="15" onclick="change_picktime()" /> 到 <input type="text"  name="dotime2" value="{$magic.request.dotime2}" id="dotime2" size="15" onclick="change_picktime()" />
				<input type="button" value="搜索" onclick="sousuo()" />
			</div>
		</div>
		</td>
		</tr>-->
		<tr>
			<td colspan="10" class="page">
			{$_A.showpage} 
			</td>
		</tr>
	</form>
</table>

{elseif $_A.query_type=="full"}
<div class="module_add">
	<form name="form1" method="post" action="" onsubmit="return submit_fool();" enctype="multipart/form-data" >
	<div class="module_title"><strong>债权转让信息</strong></div>
	<div class="module_border">
		<div class="l">原始借款标：</div>
		<div class="c">
		<a href="/invest/a{$_A.zqzr_result.borrow_id}.html" target="_blank">{$_A.zqzr_result.borrow_name}</a>
		</div>
	</div>
	<div class="module_border">
		<div class="l">出售人：</div>
		<div class="c">
		{$_A.zqzr_result.username}
		</div>
	</div>
	<div class="module_border">
		<div class="l">出售债权：</div>
		<div class="c">
		{$_A.zqzr_result.y_account}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">出售价格：</div>
		<div class="c">
		{$_A.zqzr_result.account}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">每份价格：</div>
		<div class="c">
		{$_A.zqzr_result.every_account}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">份数：</div>
		<div class="c">
		{$_A.zqzr_result.account/$_A.zqzr_result.every_account}份
		</div>
	</div>
	<div class="module_border">
		<div class="l">每份待收：</div>
		<div class="c">
		{$_A.zqzr_result.every_collection}元
		</div>
	</div>
	<div class="module_border">
		<div class="l">有效期：</div>
		<div class="c">
		{$_A.zqzr_result.valid_time}
		</div>
	</div>
	<div class="module_border">
		<div class="l">债权剩余时间：</div>
		<div class="c">
		{$_A.zqzr_result.show_y_timelimit}
		</div>
	</div>
	<div class="module_border">
		<div class="l">提交时间：</div>
		<div class="c">
		{$_A.zqzr_result.addtime|date_format}
		</div>
	</div>
	<div class="module_border">
		<div class="l">状态：</div>
		<div class="c">
			{if $_A.zqzr_result.status ==0}
			等待初审
			{elseif $_A.zqzr_result.status ==2}
			初审失败
			{elseif $_A.zqzr_result.status ==4}
			复审失败
			{elseif $_A.zqzr_result.status ==5}
			用户取消
			{elseif $_A.zqzr_result.account>$_A.zqzr_result.account_yes}
			正在转让中..
			{elseif $_A.zqzr_result.status ==3}
			复审通过
			{else}
			满标审核中
			{/if}
		</div>
	</div>
	<div class="module_title"><strong>债权转让复审</strong></div>
	{if $_A.zqzr_result.status==1}
	<div class="module_border">
		<div class="l">复审结果：</div>
		<div class="c">
		<label><input type="radio" value="4" name="status" checked="checked" />不通过</label>
		<label><input type="radio" value="3" name="status" />通过</label>
		</div>
	</div>
	<div class="module_border">
		<div class="l">备注：</div>
		<div class="c">
			<textarea style="width:300px;height:100px" name="success_remark"></textarea>
		</div>
	</div>
	<div class="module_border">
		<div class="l"></div>
		<div class="c">
			<input type="hidden" value="{$_A.zqzr_result.id}" name="id" />
			<input type="submit" value="确认提交" />
		</div>
	</div>
	{/if}
	</form>
</div>
{/if}