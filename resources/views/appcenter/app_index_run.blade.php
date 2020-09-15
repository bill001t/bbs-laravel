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
		<div class="main cc">
			<div class="main_body">
				<div class="main_content">
					<div class="box_wrap app_page">
						<h2 class="box_title">应用中心</h2>
						<div class="ct">
							<ul class="cc">

@foreach ($apps as $k => $app)
if(strpos($app['logo'], '://') === false){
$app['logo'] = Core::url()->extres . '/' . $app['alias'] . '/' . $app['logo'];
}
							 #-->
 								<li>
									<a href="{{ $app['url'] }}" class="app_icon"><b></b><img src="{{ $app['logo'] }}" onerror="this.onerror=null;this.src={{ asset('assets/images') }}/admin/yy.png'" width="80" height="80" alt="{{ $app['name'] }}" /></a>
									<div>
										<h3 class="title"><a href="{{ $app['url'] }}">{{ $app['name'] }}</a></h3>
										<p class="s6">{{ $app['desc'] }}</p>
										<a href="{{ $app['url'] }}" class="in gray">进入<em>&gt;&gt;</em></a>
									</div>
								</li>
								<!--# } #-->
							</ul>
							<page tpl="TPL:common.page" page="$page" per="$perpage" count="$count" url="appcenter/index/run?orderby=$orderby"/>
						</div>
					</div>
				</div>
			</div>
			<div class="main_sidebar">
				@include('common.sidebar_2')
			</div>
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script>
//引入jquery文件
Wind.use('jquery', 'global', function(){
});
</script>
</body>
</html>