<!doctype html>
<html>
<head>
@include('common.head')
<meta http-equiv="refresh" content="1; url={{ $refUrl|url }}" />
<link href="{{ asset('assets/themes/site/default/css/dev/logout.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="box_wrap logout_page cc">
			<div class="logout_message">
				<h2>退出成功，现在将自动转入到退出前页面</h2>
				<div><a href="{{ $refUrl|url }}"> 如果你的浏览器没有自动转入，请点击此链接 </a></div>
			</div>
		</div>
	</div>
{{--  @include('common.footer') --}}
</div> {!! $synLogout !!}
<script>

</script>
</body>
</html>
