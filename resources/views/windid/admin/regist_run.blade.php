<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="h_a">注册设置</div>
	<form class="J_ajaxForm" data-role="list" action="{{ url('windid/regist/doregist') }}" method="post" >
	<div class="table_full">
		<table width="100%" class="J_radio_change_items" id="reg2" style="margin-bottom:0;">
			<col class="th" />
			<col width="400" />
			<col />
			<tbody>
				<tr>
					<th>禁用用户名</th>
					<td>
						<textarea class="length_5" name="securityBanUsername">{{ $config['security.ban.username'] }}</textarea>
					</td>
					<td><div class="fun_tips">包含设定词汇的所有用户名将无法成功注册。如你禁用了"版主"，那么所有含有"版主"(如:我是版主)的用户名将被禁止使用。多个词之间用英文半角逗号","分隔。</div></td>
				</tr>
				<tr>
					<th>用户名长度控制</th>
					<td>
						<input type="number" class="input select_2 mr15" value="{{ $config['security.username.min'] }}" name="securityUsernameMin"><span class="mr15">到</span>
						<input type="number" class="input select_2" value="{{ $config['security.username.max'] }}" name="securityUsernameMax">
					</td>
					<td><div class="fun_tips">用户名字符的最小和最大长度，最小值不能小于1，最大值不能大于15。</div></td>
				</tr>
				<tr>
					<th>密码长度控制</th>
					<td>
						<input type="number" class="input select_2 mr15" value="{{ $config['security.password.min'] }}" name="securityPasswordMin"><span class="mr15">到</span>
						<input type="number" class="input select_2" value="{{ $config['security.password.max'] }}" name="securityPasswordMax">
					</td>
					<td><div class="fun_tips">最小值不能小于1，无最大值限制，留空表示不限制。</div></td>
				</tr>
				<tr>
					<th>强制密码复杂度</th>
					<td>
						<ul class="three_list cc">
							<li><label><input type="checkbox" value="1" name="securityPassword[]" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray(1, $config['security.password'])) }}>小写字母</label></li>
							<li><label><input type="checkbox" value="2" name="securityPassword[]" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray(2, $config['security.password'])) }}>大写字母</label></li>
							<li><label><input type="checkbox" value="4" name="securityPassword[]" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray(4, $config['security.password'])) }}>数字</label></li>
							<li><label><input type="checkbox" value="8" name="securityPassword[]" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray(8, $config['security.password'])) }}>符号</label></li>
							<li style="width:66%;"><label><input type="checkbox" value="9" name="securityPassword[]" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray(9, $config['security.password'])) }}>密码不能与用户名相同</label></li>
						</ul>
					</td>
					<td><div class="fun_tips">密码中必须符合所选条件限制。</div></td>
				</tr>
			</tbody>
		</table>
		<!-- <table width="100%" class="J_radio_change_items" id="reg3" style="margin-bottom:0;">
			<col class="th" />
			<col width="400" />
			<col />

			<tbody>
				<tr>
					<th>关闭注册原因</th>
					<td>
						<textarea class="length_5" name="closeMsg">{{ $config['close.msg'] }}</textarea>
					</td>
					<td><div class="fun_tips">当站点关闭注册时，对外的提示信息。<br>支持html代码。</div></td>
				</tr>
			</tbody>
		</table> -->
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>
</div>
@include('admin.common.footer')
<script>

$(function(){
	//是否允许新用户注册
	/*registAreaShow($('#J_register_type input:checked').data('type'));

	$('#J_register_type input:radio').on('change', function(){
			registAreaShow($(this).data('type'));
	});

	function registAreaShow(type) {
		var reg_arr= new Array();
		reg_arr = type.split(",");
		$('.J_reg_tbody').hide();
		$.each(reg_arr, function(i, o){
			$('#'+ o).show();
		});
	}*/
});
</script>
</body>
</html>