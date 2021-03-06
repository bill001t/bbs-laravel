<!doctype html>
<html>
<head>
@include('common.head')
</head>
<body>
	<div class="wrap">

		<div class="h_a">后台安全</div>
		<form class="J_ajaxForm" action="{{ url('safe/add') }}" method="post">
			<div class="table_full">
				<table width="100%">
					<colgroup>
						<col class="th">
						<col width="400">
					</colgroup>
					<!-- <tr>
				<th>安全问题功能</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" value="1" name="question" {{ App\Core\Tool::ifcheck($conf['question.isopen'] == 1) }}><span>开启</span></label></li>
						<li><label><input type="radio" value="0" name="question" {{ App\Core\Tool::ifcheck($conf['question.isopen'] == 0) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td><div class="fun_tips">开启后需要设置安全问题才能登录后台</div></td>
		</tr> -->
					<tr>
						<th>后台登录IP限制</th>
						<td><textarea name="ips" class="length_6">{{ $ips }}</textarea></td>
						<td>
							<div class="fun_tips">
								此功能可绑定登录后台的 IP，只有在列表内的 IP 才能登录站点，创始人不受限制。<br>
								可以绑定单个IP地址格式如:192.0.0.1，也可以绑定一段IP格式如:192.0.0，多个IP "," 分隔。<br>
								<span class="red">当前登录ip:{{ $clientIp }}</span>
							</div>
						</td>
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
	{{--  @include('common.footer') --}}
</body>
</html>