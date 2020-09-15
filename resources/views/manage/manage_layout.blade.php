<!doctype html>
<html>
<head>
	@include('common.head')
	<link href="{{ asset('assets/themes/site/default/css/dev/profile.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
	{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('manage/content/run') }}">前台管理</a>
		</div>
		<div class="cc profile_page">
			<div class="md">
				@include('manage.menubar_left')
			</div>
			<div class="cm">
				<div class="cw">
					<div class="box_wrap">
				<!--#$this->content();#-->
					</div>
				</div>
			</div>
		</div>
	</div>
	{{--  @include('common.footer') --}}
</div>
<script>
Wind.use('jquery', 'global', 'ajaxForm', function(){
	Wind.js(GV.JS_ROOT +'pages/recycle/recycle_index.js?v='+ GV.JS_VERSION);
});
</script>
</body>
</html>