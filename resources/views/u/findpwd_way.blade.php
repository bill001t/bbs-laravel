<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/register.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="box_wrap register cc">
			<h2 class="reg_head">找回密码</h2>
			<div class="reg_cont_wrap">
				<div class="reg_cont">
					<div class="reg_form">
						<div class="tips">请选择一种密码找回方式，以帮助您快速找回密码</div>
						<dl>
							<dt>用户名：</dt>
							<dd class="username"> {{ $username }}
							</dd>
						</dl>
						<div class="password_back_type">
							<a href="{{ url('u/findPwd/bymail?username=' . $username) }}"><img src="{{ asset('assets/images/register/mail_64.png') }}"alt="邮箱" width="64" height="64">通过邮箱找回</a>
							<a href="{{ url('u/findPwd/bymobile?username=' . $username) }}"><img src="{{ asset('assets/images/register/phone_64.png') }}"alt="手机" width="64" height="64">通过手机找回</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>    
{{--  @include('common.footer') --}}
<script>
Wind.use('jquery', 'global');
</script>
</div>
</body>
</html>