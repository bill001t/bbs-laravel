<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	@include('credit_headtab')
	<div class="h_a">积分日志搜索</div>
	<form action="{{ url('credit/credit/log') }}" method="post">
	<div class="search_type cc mb10">
		<div class="ul_wrap">
			<ul class="cc">
				<li>
					<label>积分类型</label><select name="ctype" class="select_2">
					<option value="0">全部</option>

@foreach ($creditBo->cType as $key => $value)

					<option value="{{ $key }}" {{ App\Core\Tool::isSelected($args['ctype'] == $key) }}>{{ $value }}</option>
				<!--# } #-->
					</select>
				</li>
				<li>
					<label>时间</label><input class="input length_2 mr5 J_date" type="text" name="time_start" value="{{ $args['time_start'] }}"><span class="mr5">至</span><input class="input length_2 J_date" type="text" name="time_end" value="{{ $args['time_end'] }}">
				</li>
				<li>
					<label>收入支出</label><select name="award" class="select_2">
						<option value="0">全部</option>
						<option value="1" {{ App\Core\Tool::isSelected($args['award'] == 1) }}>收入</option>
						<option value="2" {{ App\Core\Tool::isSelected($args['award'] == 2) }}>支出</option>
					</select>
				</li>
				<li>
					<label>用户</label><input class="input length_3 mr10" type="text" name="username" value="{{ $args['username'] }}">
				</li>
			</ul>
		</div>
		<div class="btn_side">
			<button class="btn btn_submit" type="submit">搜索</button>
		</div>
	</div>
	</form>

	<div class="table_list">
		<table width="100%" id="J_table_list">
			<colgroup>
				<col width="65">
				<col width="100">
				<col width="100">
				<col width="120">
				<col>
				<col width="120">
				<col>
			</colgroup>
			<thead>
				<tr>
					<!-- <td><label class="mr10"><input class="J_check_all" data-checklist="J_check_x" data-direction="x" type="checkbox">全选</label></td> -->
					<td>序号</td>
					<td>用户</td>
					<td>操作</td>
					<td>明细</td>
					<td>描述</td>
					<td>时间</td>
				</tr>
			</thead>
			<tbody>

@if ($log)

				<!--# $i = 0; #-->

@foreach ($log as $key => $value)

					<!--# $value['affect'] > 0 && $value['affect'] = '+' . $value['affect']; #-->
				<tr>
					<td>{{ @++$i }}</td>
					<td>{{ $value['created_username'] }}</td>
					<td>{{ @$coc->getName($value['logtype']) }}</td>
					<td>{{ $creditBo->cType[$value['ctype']] }} {{ $value['affect'] }}</td>
					<td>{{ $value['descrip'] }}</td>
					<td>{{ App\Core\Tool::time2str($value['created_time']) }}</td>
				</tr>
				<!--# } #-->
			<!--# } #-->
			</tbody>
		</table>
	</div>
	<page tpl="TPL:common.page" total="$totalpage" page="$page" per="$perpage" count="$count" url="credit/credit/log" args="$args"/>
</div>
@include('admin.common.footer')
<script>
var last_credit_key = $('#J_credit_tbody > tr:last').data('key'),
	root_tr_html = '<tr class="ct">\
				<td><input type="text" value="" class="input" name="credits[credit_key_][name]"></td>\
				<td><input type="text" value="" class="input" name="credits[credit_key_][unit]"></td>\
				<td><input type="checkbox" checked="" value="credit_key_" name="opened[]"></td>\
				<td><input name="" type="checkbox" value=""></td>\
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