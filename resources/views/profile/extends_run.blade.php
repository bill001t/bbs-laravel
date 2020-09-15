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
		<div class="cc">
			<div class="bread_crumb">
				<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('profile/index/run') }}">设置</a>
				<em>&gt;</em><a href="{{ url('profile/index/run?_left=' . $_left) }}">{{ $_menus[$_left]['title'] }}</a>

@if ($_tab && $_menus[$_left]['tabs'][$_tab])

				<em>&gt;</em><a href="{{ url('profile/index/run?_left=' . $_left . '&_tab=' . $_tab) }}">{{ $_menus[$_left]['tabs'][$_tab]['title'] }}</a>
<!--#}#-->
			</div>
			<div class="cc profile_page">
				<div class="md">
					<div class="menubar">
						<ul>

@foreach ($_menus as $key => $_item)
if (isset($_item['url']) && $_item['url']) {
					#-->
							<li class="{{ App\Core\Tool::isCurrent($key == $_left) }}"><a href="{{ url($_item['url'], array('_lef' => $key)) }}" id="profile_{{ $key }}">{{ $_item['title'] }}</a></li>

@else

							<li class="{{ App\Core\Tool::isCurrent($key == $_left) }}"><a href="{{ url('profile/extends/run?_left=' . $key) }}">{{ $_item['title'] }}</a></li>
					<!--#}}#-->
						</ul>
					</div>
				</div>
				<div class="cm">
					<div class="cw">
						<div class="box_wrap">
						<div class="content">

@if ($_menus[$_left]['tabs'])

							<div class="profile_nav">

@if ($_left == 'profile')

								<a href="{{ url('profile/secret/run?_left=secret') }}" class="fr a_privacy">隐私设置</a>
							<!--#}#-->
								<ul>

@foreach ($_menus[$_left]['tabs'] as $key => $_item)
if (isset($_item['url']) && $_item['url']) {
							#-->
									<li class="{{ App\Core\Tool::isCurrent($_tab == $key) }}"><a href="{{ url($_item['url'], array('_lef' => $_left, '_tab' => $key)) }}">{{ $_item['title'] }}</a></li>

@else

									<li class="{{ App\Core\Tool::isCurrent($_tab == $key) }}"><a href="{{ url('profile/extends/run?_left=' . $_left . '&_tab=' . $key) }}">{{ $_item['title'] }}</a></li>
							<!--#}}#-->
								</ul>
							</div>
							<!--#}#-->
							{{-- <hook class='$hookSrc' name='createHtml' args='$_left, $_tab'/> --}}
						</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{--  @include('common.footer') --}}
</div>
<script>
Wind.use('jquery', 'global');
</script>
</body>
</html>