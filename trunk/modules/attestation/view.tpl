
<table  border="0"  cellspacing="1" bgcolor="#CCCCCC" width="100%">
	 		<tr >
			<td width="*" class="main_td1">结果</td>
			<td class="main_td1">{if $_A.sfz_result.result == "1"}{if $_A.sfz_result.status == "3"}<font color="red">一致</font>{else}不一致{/if}{else}查不到该号码{/if}</td>
		</tr>
{if $_A.sfz_result.result == "1"}
		<tr >
			<td width="*" class="main_td1">名字</td>
			<td class="main_td1">{$_A.sfz_result.sfzname}</td>
		</tr>
		<tr >
			<td width="*" class="main_td1">身份证号码</td>
			<td class="main_td1">{$_A.sfz_result.sfznum}</td>
		</tr>
		<tr >
			<td width="*" class="main_td1">照片</td>
			<td class="main_td1"><img src={$_A.sfz_result.pic} /></td>
		</tr>

{/if}
</table>

