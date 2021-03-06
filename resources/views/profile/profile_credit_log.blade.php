<div class="content">
	@include('profile_credit_tab')
	<form action="{{ url('profile/credit/log?_tab=log') }}" method="post">
	<div class="profile_search">
		<h2>日志搜索</h2>
		<table width="100%">
			<colgroup>
				<col>
				<col width="200">
			</colgroup>
			<tbody>
			<tr>
				<th>积分类型</th>
				<td><select name="ctype" class="select_3"><option value="0">全部</option>
@foreach ($creditBo->cType as $key => $value)
<option value="{{ $key }}"{{ App\Core\Tool::isSelected($key == $ctype) }}>{{ $value }}</option><!--# } #--></select></td>
				<th>时间</th>
				<td><input type="text" class="input length_3 mr10 J_date date" name="time_start" value="{{ $timeStart }}"><span class="mr10">至</span><input type="text" class="input length_3 J_date date" name="time_end" value="{{ $timeEnd }}"></td>
			</tr>
			<tr>
				<th>奖惩</th>
				<td>
					<select name="award" class="select_3">
						<option value="0">全部</option>
						<option value="1"{{ App\Core\Tool::isSelected($award == 1) }}>收入</option>
						<option value="2"{{ App\Core\Tool::isSelected($award == 2) }}>支出</option>
					</select>
				</td>
				<th></th>
				<td></td>
			</tr>
		</tbody></table>
		<div class="tac"><button type="submit" class="btn">搜索</button></div>
	</div>
	</form>
	<div class="order_history">
		<table width="100%" class="mb10">
			<thead>
				<tr>
					<td class="num" width="50">序号</td>
					<td width="90">操作</td>
					<td width="70">奖惩</td>
					<td>描述</td>
					<td width="110">时间</td>
				</tr>
			</thead>

@if ($log)

			<!--# $i=0; #-->

@foreach ($log as $key => $value)

			<tr>
				<td class="num">{{ @++$i }}</td>
				<td>{{ @$coc->getName($value['logtype']) }}</td>
				<td>

@if ($value['affect'] > 0)
<span class="green">{{ $creditBo->cType[$value['ctype']] }}+{{ $value['affect'] }}</span>
@else
<span class="red">{{ $creditBo->cType[$value['ctype']] }}{{ $value['affect'] }}</span><!--# } #-->
				</td>
				<td>{{ $value['descrip'] }}</td>
				<td>{{ App\Core\Tool::time2str($value['created_time']) }}</td>
			</tr>
			<!--# } #-->

@else

				<tr><td colspan="7" class="tac w">啊哦，没有符合条件的内容！</td></tr>
			<!--# } #-->
		</table>
		<page tpl="TPL:common.page" total="$totalpage" page="$page" per="$perpage" count="$count" url="profile/credit/log" args="$url" />
	</div>
	
</div>
<script>
Wind.ready(document, function(){
	Wind.use('jquery', 'global');
});
</script>