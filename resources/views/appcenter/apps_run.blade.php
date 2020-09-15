<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/app.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url('run') }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('appcenter/index/run') }}">应用中心</a>
		</div>
		<div class="cc">
			<form method="POST" action="{{ $appUrl }}&charset={{ Core::V('charset') }}" id="app_form" target="post_iframe">
				<input type="hidden" name="frompw" value="{{ @base64_encode(pw::getTime()) }}"/>
            </form>

			<iframe width="960" height="700" frameborder="0" src="" name="post_iframe" scrolling="no" id="apps"></iframe>
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script>
document.getElementById("app_form").submit();
</script>
</body>
</html>