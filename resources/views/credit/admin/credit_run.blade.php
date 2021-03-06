<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	@include('credit_headtab')
	<form class="J_ajaxForm" method="post" action="{{ url('credit/credit/doSetting') }}" data-role="list">
	<div class="table_list">
		<table id="J_table_list" width="100%">
			<col width="220">
			<col width="220">
			<col width="40">
			<col width="100">
			<thead>
				<tr>
					<td width="200">积分名称</td>
					<td width="200">积分单位</td>
					<td width="30">启用</td>
					<td>开启积分日志</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody id="J_credit_tbody">

@foreach ($credits as $key => $credit)

			<tr data-key="{{ $key }}">
				<td><input name="credits[{{ $key }}][name]" type="text" class="input" value="{{ $credit['name'] }}"></td>
				<td><input name="credits[{{ $key }}][unit]" type="text" class="input" value="{{ $credit['unit'] }}"></td>
				<td><input name="credits[{{ $key }}][open]" type="checkbox" value="1"{{ App\Core\Tool::ifcheck($localCredits[$key]['open']) }}></td>
				<td><input name="credits[{{ $key }}][log]" type="checkbox" value="1"{{ App\Core\Tool::ifcheck($localCredits[$key]['log']) }}></td>

@if($key > 4)

				<td><a href="{{ url('credit/credit/doDelete') }}" class="mr10 J_ajax_del" data-pdata="{'creditId': '{{ $key }}'}">[删除]</a></td>

@else

				<td>-- --</td>
				<!--#}#-->
			</tr>
			<!--#}#-->
			</tbody>
		</table>
		<table width="100%">
			<tr>
				<td colspan="4"><a id="J_add_root" data-type="credit_root" data-html="tbody" href="#" class="link_add mr20">添加新积分</a><span id="J_credit_add_tip">
@if(count($credits) >= 8)
过多的积分组，可能会导致社区金融体系的混乱。<!--#}#--></span></td>
			</tr>
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>

</div>
@include('admin.common.footer')
<script>
var last_credit_key = $('#J_credit_tbody > tr:last').data('key'),
	root_tr_html = '<tr class="ct">\
				<td><input type="text" value="" class="input" name="newcredits[credit_key_][name]"></td>\
				<td><input type="text" value="" class="input" name="newcredits[credit_key_][unit]"></td>\
				<td><input type="checkbox" checked="" value="1" name="newcredits[credit_key_][open]"></td>\
				<td><input type="checkbox" value="1" name="newcredits[credit_key_][log]"></td>\
							<td><a href="#" class="mr5 J_newRow_del">[删除]</a></td>\
						</tr>';
Wind.js(GV.JS_ROOT+ 'pages/admin/common/forumTree_table.js?v=' +GV.JS_VERSION);
$(function(){
	var add_tip = $('#J_credit_add_tip');
	
	//添加后判断积分数量，大于等于8个则提示
	$('#J_add_root').click(function(){
		setTimeout(function(){
			if ($('#J_table_list > tbody > tr').length >= 9) {
				add_tip.text('建议不要添加太多积分！');
			}else{
				add_tip.text('');
			}
		}, 0);
	});
	
	//删除后判断积分数量
	$('#J_table_list').on('click', 'a.J_newRow_del', function (e) {
		setTimeout(function(){
			if ($('#J_table_list > tbody > tr').length < 9) {
				add_tip.text('');
			}
		}, 0);
	})
	
});
</script>
</body>
</html>