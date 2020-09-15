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
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('profile/index/run') }}">设置</a>
<!--#
$_profileBread = Wind::getApp()->getResponse()->getData('G','profileBread');
foreach ($_profileBread as $key => $_item) {
if (!$_item || !is_array($_item)) continue;
#-->
			<em>&gt;</em><a href="{{ $_item['url'] }}">{{ $_item['title'] }}</a>
<!--#}#-->
		</div>
		<div class="cc profile_page">
			<div class="md">
				@include('profile.profile_left')
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
</body>
</html>