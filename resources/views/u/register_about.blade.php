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

@if ($type == 'activeEmail')

			<meta http-equiv="refresh" content="3; url={{ url() }}" />
			<div class="box_wrap register cc">
				<div class="reg_activation">
					<h1>您好，您的账号已激活，请直接登录网站！</h1>
					<div class="reg_activation_tip">
						<p>3秒后页面自动跳转回 <a href="{{ url() }}" class="s4">首页</a></p>
					</div>
				</div>
			</div>

@elseif ($type == 'activeEmailSuccess')

			<div class="box_wrap register cc">
				<div class="reg_activation">
					<h1>恭喜，您的帐户已激活成功！</h1>
					<div class="reg_activation_tip">
						<p>

@if ($goGuide)

						进入<a href="{{ url('u/register/guide') }}">新手导航</a>

@else

						<meta http-equiv="refresh" content="3; url={{ url() }}" />
						返回 <a href="{{ url() }}" class="s4">首页</a>
						<!--#}#-->
						</p>
					</div>
				</div>
			</div>

@elseif ($type == 'success')

			<div class="box_wrap register cc">
				<div class="reg_activation">
					<h1>{{ $username }}，恭喜您注册成为{@Core::C('site', 'info.name')}会员！</h1>
					<div class="reg_activation_tip">
						<p>
@if ($check)
<span class="mr20">网站已开通注册审核，您的帐户通过审核后，即可正常发帖。</span><!--#}#--><a href="{{ url() }}" class="s4">返回首页</a></p>
					</div>
				</div>
			</div>
		<!--#}#-->
	</div>
{{--  @include('common.footer') --}} {!! $synLogin !!}
<script>
Wind.use('jquery', 'global');
</script>
</div>
</body>
</html>