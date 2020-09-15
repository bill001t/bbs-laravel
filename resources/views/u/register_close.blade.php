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
			<h2 class="reg_head">注册</h2>
			<div class="reg_cont_wrap">
				<div class="reg_cont">
					<div class="reg_message reg_ignore">
						<!--h2>{{ Core::C('site','info.name') }} 已关闭注册！</h2-->
						<p class="mb5">{!! $close !!}</p>
						<p><a href="javascript:window.history.go(-1);" class="s4 mr10">返回上一页面</a><span class="mr10">或者</span><a href="{{ url() }}" class="s4">返回首页</a></p>
					</div>
				</div>
			</div>
			<div class="reg_side">
				<div class="reg_side_cont">
					<p class="mb10">已经有帐号？</p>
					<p><a href="{{ url('u/login/run') }}" class="btn btn_big">立即登录</a></p>
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
