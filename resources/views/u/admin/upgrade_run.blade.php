<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
<!-- mod start -->
<form method="post" class="J_ajaxForm" data-role="list" action="{{ url('u/upgrade/dosave') }}">
<!--div class="h_a">会员组提升方案</div-->
<div class="tabs_contents">
	<div class="table_list">
		<table width="100%">
			<col width="120">
			<col width="200">
			<thead>
				<tr>
					<td class="s5">类别</td>
					<td class="s5">系数</td>
					<td class="s5">综合积分</td>
				</tr>
			</thead>
			<tbody class="J_upgrade_list">
				<tr>
					<td>发帖数</td>
					<td><input name="member[postnum]" type="number" class="input" value="{{ $member['postnum'] }}"></td>
					<td rowspan="100" style="border-left:1px solid #e4e4e4;">
						<div class="J_upgrade_info">
						</div>
					</td>
				</tr>
				<tr>
					<td>精华</td>
					<td><input name="member[digest]" type="number" class="input" value="{{ $member['digest'] }}"></td>
				</tr>

@foreach ($credits->cType as $k => $v)

				<tr>
					<td>{{ $v }}</td>
					<td><input name="member[credit{$k}]" type="number" class="input" value="{{ $member['credit'.$k] }}"></td>
				</tr>
				<!--# } #-->
				<tr>
					<td>会员历史在线时间</td>
					<td><input name="member[onlinetime]" type="number" class="input" value="{{ $member['onlinetime'] }}"></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<!-- mod end -->
<div class="btn_wrap">
	<div class="btn_wrap_pd" id="J_sub_wrap">
		<button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
	</div>
</div>
</form>

</div>
@include('admin.common.footer')
<script>
$(function(){

	//计算函数
	var compute = function (list) {
		var arr = [];
		list.find('tr').each(function() {
			var input = $(this).find('input.input');
			if(input) {
				var val = input.val();
				if( val != '') {
					var text = $(this).find('td:first').html();
					arr.push(text + '*' + val);
				}
			}
		});
		list.find('.J_upgrade_info').html( arr.join(' + ') );
	};

	$('.J_upgrade_list').each(function() {
		var list = $(this);
		compute(list);//载入时计算积分规则
		var isIE9 = $.browser.version == '9.0';
		list.find('input').each(function() {
			(function(elem) {
				if(isIE9) {//IE9对下面两个事件支持有缺陷
					var timer;
					$(elem).focus(function() {
						timer = setInterval(function(){
							compute(list);
						}, 64);
					}).blur(function() {
						clearInterval(timer);
					});
				}else if('oninput' in elem){
					elem.on('input',function() {
						compute(list);
					});
				}else{//IE6/7/8
					elem.onpropertychange = function() {
						if (window.event.propertyName == "value"){
							compute(list);
						}
					}
				}
			})(this);
		});
	});

});
</script>
</body>
</html>