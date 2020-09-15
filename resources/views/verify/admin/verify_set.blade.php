<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

<!-- start -->
	<div class="nav">
		<ul class="cc">
		<li><a href="{{ url('/verify/verify/run') }}">验证码</a></li>
		<li class="current"><a href="{{ url('/verify/verify/set') }}">验证策略</a></li>
		</ul>
	</div>
	<div class="h_a">验证策略</div>
	<form method="post" class="J_ajaxForm" action="{{ url('/verify/verify/doset') }}" data-role="list">
	<div class="table_full mb10">
		<table width="100%" class="J_check_wrap">
			<colgroup>
			<col class="th">
			<col width="400">
			<col>
			</colgroup>
			<tr>
				<th>注册</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="ext[register]" value="1" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('register',$config['showverify'])) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="ext[register]" value="0" {{ App\Core\Tool::ifcheck(!App\Core\Tool::inArray('register',$config['showverify'])) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th>登录</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="ext[userlogin]" value="1" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('userlogin',$config['showverify'])) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="ext[userlogin]" value="0" {{ App\Core\Tool::ifcheck(!App\Core\Tool::inArray('userlogin',$config['showverify'])) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th>找回密码</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="ext[resetpwd]" value="1" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('resetpwd',$config['showverify'])) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="ext[resetpwd]" value="0" {{ App\Core\Tool::ifcheck(!App\Core\Tool::inArray('resetpwd',$config['showverify'])) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th>发消息</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="ext[sendmsg]" value="1" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('sendmsg',$config['showverify'])) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="ext[sendmsg]" value="0" {{ App\Core\Tool::ifcheck(!App\Core\Tool::inArray('sendmsg',$config['showverify'])) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th>发主题帖</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="ext[postthread]" value="1" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('postthread',$config['showverify'])) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="ext[postthread]" value="0" {{ App\Core\Tool::ifcheck(!App\Core\Tool::inArray('postthread',$config['showverify'])) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th>上传照片</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="ext[uploadpic]" value="1" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('uploadpic',$config['showverify'])) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="ext[uploadpic]" value="0" {{ App\Core\Tool::ifcheck(!App\Core\Tool::inArray('uploadpic',$config['showverify'])) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th>后台登录</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="ext[adminlogin]" value="1" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray('adminlogin',$config['showverify'])) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="ext[adminlogin]" value="0" {{ App\Core\Tool::ifcheck(!App\Core\Tool::inArray('adminlogin',$config['showverify'])) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td>&nbsp;</td>
			</tr>

@foreach ($verifyExt as $name => $title)

			<tr>
				<th>{{ $title }}</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="ext[{{ $name }}]" value="1" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($name,$config['showverify'])) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="ext[{{ $name }}]" value="0" {{ App\Core\Tool::ifcheck(!App\Core\Tool::inArray($name,$config['showverify'])) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td>&nbsp;</td>
			</tr>
			<!--#}#-->
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
</body>
</html>