<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	@include('credit_headtab')
	<form class="J_ajaxForm" action="{{ url('credit/credit/dorecharge') }}" method="post">
	<div class="table_list">
		<table id="J_table_list" width="100%">
			<col width="120">
			<col width="240">
			<col width="240">
			<thead>
				<tr>
					<td>名称</td>
					<td>转换比例（1元人民币兑换的积分值）</td>
					<td>最少充值（人民币：元）</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody>

@foreach ($recharge as $key => $value)


@if (isset($creditBo->cType[$key]))

				<tr>
					<td>{{ $creditBo->cType[$key] }}</td>
					<td><input type="text" name="recharge[{{ $key }}][rate]" value="{{ $value['rate'] }}" class="input length_3"></td>
					<td><input type="text" name="recharge[{{ $key }}][min]" value="{{ $value['min'] }}" class="input length_3"></td>
					<td><a href="" class="J_recharge_del">[删除]</a></td>
				</tr>
				<!--# } #-->
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
				<td><select name="ctype[]">\
@foreach ($creditBo->cType as $key => $value)

@if (!isset($recharge[$key]))
<option value="{{ $key }}">{{ $value }}</option>\<!--# } #--><!--# } #--></select></td>\
				<td><input type="text" name="rate[]" class="input length_3"></td>\
				<td><input type="text" name="min[]" class="input length_3"></td>\
				<td><a href="" class="J_newRow_del">[删除]</a></td>\
			</tr>';

Wind.js(GV.JS_ROOT+ 'pages/admin/common/forumTree_table.js?v=' + GV.JS_VERSION, function(){
	//删除
	$('a.J_recharge_del').on('click', function(e){
		e.preventDefault();
		$(this).parents('tr').remove();
	});
});
</script>
</body>
</html>