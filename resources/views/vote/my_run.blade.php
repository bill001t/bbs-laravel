<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/vote.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('vote/my/run') }}">投票</a><em>&gt;</em><a href="{{ url('vote/my/run') }}">我的投票</a>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content">
					<div class="box_wrap vote_page">
					<nav>
						<div class="content_nav">
							<ul>
								<li><a href="{{ url('vote/hot/run') }}">热门投票</a></li>
								<li><a href="{{ url('vote/ta/run') }}">Ta的投票</a></li>
								<li class="current"><a href="{{ url('vote/my/run') }}">我的投票</a></li>
							</ul>
						</div>
						<div class="content_type">
							<ul class="cc">
								<li class="current"><a href="{{ url('vote/my/run') }}">我参与的</a></li>
								<li class="line"></li><li><a href="{{ url('vote/my/create') }}">我发起的</a></li>
							</ul>
						</div>
					</nav>
						<div class="vote_list">

@if ($total)
foreach($pollInfo as $value){ #-->
							@include('vote.listcommon')
							<!--# }} else { #-->
							<div class="not_content">啊哦，你还没有参与过投票哦，赶紧去<a href="{{ url('vote/hot/run') }}">参与</a>！</div>
							<!--# } #-->
							<div><page tpl='TPL:common.page' page='$page' per='$perpage' count='$total' url='vote/my/run' /></div>
						</div>
					</div>
				</div>
			</div>
			<div class="main_sidebar">
				@include('common.sidebar_1')
				@include('vote.sidebar')
			</div>
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script>
Wind.use('jquery', 'global', function(){
	Wind.js(GV.JS_ROOT +'pages/vote/vote_index.js?v=' + GV.JS_VERSION);
});
</script>
</body>
</html>