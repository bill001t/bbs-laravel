<!--=============积分奖励===============-->
<tbody class="J_reward_forum">
	<tr>
		<th>积分名称</th>
		<td>
			<span class="must_red">*</span>
			<input type="hidden" value="id-name-unit" name="reward[key]" />
			<select class="select_5" name="reward[value]">

@foreach ($credit->cType as $id => $item)
$_tmp = $id . '-' . $item . '-' . $credit->cUnit[$id];
	#-->
			<option value="{{ $_tmp }}" {{ App\Core\Tool::isSelected($_tmp == $reward['value']) }}>{{ $item }}</option>
	<!--#}#-->
			</select>
		</td>
		<td><div class="fun_tips"></div></td>
	</tr>
	<tr>
		<th>积分数量</th>
		<td>
			<span class="must_red">*</span>
			<input type="text" class="input length_5 mr5" name="reward[num]" value="{{ $reward['num'] }}">
			<input type="hidden" value="{{ num }}{unit}{{ name }}" name="reward[descript]" />
		</td>
		<td><div class="fun_tips"></div></td>
	</tr>
</tbody>
