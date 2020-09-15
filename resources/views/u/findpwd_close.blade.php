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
					<div class="reg_message reg_ignore">
						<h2>{{ Core::C('site','info.name') }} 网站暂不支持自助找回密码。如有需要，请联系管理员！</h2>
						<p>{{ $close }}</p>
						<p><a href="javascript:window.history.go(-1);" class="s4 mr10">返回上一页面</a><span class="mr10">或者</span><a href="{{ url('bbs/index/run') }}" class="s4">返回首页</a></p>
					</div>
				</div>
			</div>
			<div class="reg_side">
				<div class="reg_side_cont">
					<p class="mb10">还记得密码？</p>
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
