<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	@include('credit_headtab')
	<form class="J_ajaxForm" action="{{ url('credit/credit/dotransfer') }}" method="post">
	<div class="table_list">
		<table id="J_table_list" width="100%">
			<col width="120">
			<col width="240">
			<col width="240">
			<thead>
				<tr>
					<td>积分名称</td>
					<td>允许转账</td>
					<td>转账手续费比率（%）</td>
					<td>最低转账数(积分)</td>
				</tr>
			</thead>
			<tbody>

@foreach ($creditBo->cType as $key => $value)

				<tr>
					<td>{{ $value }}</td>
					<td><input type="checkbox" name="ifopen[{{ $key }}]" value="1" {{ App\Core\Tool::ifcheck($transfer[$key]['ifopen']) }} ></td>
					<td><input type="text" name="rate[{{ $key }}]" value="{{ $transfer[$key]['rate'] }}" class="input length_1"></td>
					<td><input type="text" name="min[{{ $key }}]" value="{{ $transfer[$key]['min'] }}" class="input length_1"></td>
				</tr>
				<!--# } #-->
			</tbody>
		</table>
	</div>

	<div class="">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>

</div>
@include('admin.common.footer')
</body>
</html>