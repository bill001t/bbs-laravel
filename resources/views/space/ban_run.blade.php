<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/style.css') }} "rel="stylesheet" />
</head>
<body {!! $space->space['backbround'] !!}>
<div class="wrap">
{{-- @include('common.header') --}}
<div class="space_page">
	@include('space.common.nav')
	<div class="cc box">
		<div class="ban_page">
			<dl>
				<dt><img class="J_avatar" src="{{ App\Core\Tool::getAvatar($space->spaceUid,'middle') }}" data-type="middle" width="50" height="50" /></dt>
				<dd>
					<div class="title">{{ $space->spaceUser['username'] }}</div>
					<div class="num">
					关注 <span>{{ $space->spaceUser['follows'] }}</span> <span>|</span>
					粉丝 <span>{{ $space->spaceUser['fans'] }}</span><span>|</span>
					帖子 <span>{{ $space->spaceUser['postnum'] }}</span></div>
				</dd>
			</dl>
			<h1>
				抱歉！由于对方的隐私设置，您没有权限查看。
			</h1>
		</div>
	</div>
</div>
{{--  @include('common.footer') --}}
</div>
<script>
//引入js组件
Wind.use('jquery', 'global', 'dialog', 'ajaxForm', 'tabs', 'draggable', 'uploadPreview', function(){
	Wind.js(GV.JS_ROOT +'pages/space/space_index.js?v='+ GV.JS_VERSION);
});
</script>
</body>
</html>