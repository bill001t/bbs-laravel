<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	@include('credit_headtab')
	<form class="J_ajaxForm" action="{{ url('credit/credit/doexchange') }}" method="post">
	<div class="table_list">
		<table id="J_table_list" width="100%">
			<col width="240">
			<col width="100">
			<col width="240">
			<col width="60">
			<thead>
				<tr>
					<td>积分I</td>
					<td>兑换</td>
					<td>积分II</td>
					<td>启用</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody>

@foreach ($exchange as $key => $value)

				<tr>
					<td>{{ $value['value1'] }}个{{ $creditBo->cType[$value['credit1']] }}</td>
					<td>兑换</td>
					<td>{{ $value['value2'] }}个{{ $creditBo->cType[$value['credit2']] }}</td>
					<td><input type="hidden" name="exchange_old[{{ $key }}]" value="1"><input type="checkbox" name="ifopen_old[{{ $key }}]" value="1"{{ App\Core\Tool::ifcheck($value['ifopen']) }}></td>
					<td><a href="{{ url('credit/credit/delexchange') }}" class="J_ajax_del" data-pdata="{'id': '{{ $key }}'}">[删除]</a></td>
				</tr>
				<!--# } #-->
			</tbody>
			
		</table>
		<table width="100%">
			<tr>
				<td colspan="4"><a href="#" class="link_add mr20" id="J_add_root" data-type="" data-html="tbody">添加</a></td>
			</tr>
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
<script>
//forumTree_table.js
var root_tr_html = '<tr>\
				<td><input class="input length_1" name="value1[]" type="text">&nbsp;个&nbsp;<select name="credit1[]" class="select_2">\
@foreach ($creditBo->cType as $key => $value)
<option value="{{ $key }}">{{ $value }}</option>\<!--# } #--></select></td>\
				<td>兑换</td>\
				<td><input class="input length_1" name="value2[]" type="text">&nbsp;个&nbsp;<select name="credit2[]" class="select_2">\
@foreach ($creditBo->cType as $key => $value)
<option value="{{ $key }}">{{ $value }}</option>\<!--# } #--></select></td>\
				<td><input type="checkbox" name="ifopen[]" value="1"></td>\
				<td><a href="" class="J_newRow_del">[删除]</a></td>\
			</tr>';

Wind.js(GV.JS_ROOT+ 'pages/admin/common/forumTree_table.js?v=' + GV.JS_VERSION);
</script>
</body>
</html>