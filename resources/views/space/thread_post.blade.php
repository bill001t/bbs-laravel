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

	<div class="cc">
		<div class="space_content">

<!--我的回复-->
			<div class="box">
				<div class="my_article">
					<div class="hd"><h2 class="name">{{ $host }}的帖子</h2><a href="{{ url('space/thread/run?uid=' . $space->spaceUid) }}" >主题</a><span class="line">|</span><a href="{{ url('space/thread/post?uid=' . $space->spaceUid) }}" class="current">回复</a></div>
					<div class="ct">

@if ($posts)

						<table width="100%" class="mb10">
							<thead>
								<tr>
									<td class="subject">帖子标题</td>
									<td class="time">发布时间</td>
								</tr>
							</thead>

@foreach ($posts as $value)

							<tr>
								<td class="subject">
									<span class="posts_icon">

@if ($value['digest'])

										<i class="icon_digest" title="精华"></i>

@else

										<i class="icon_"></i>
								<!--#}#-->
									</span>
								<a href="{{ url('bbs/read/jump?tid=' . $value['tid'] . '&pid=' . $value['pid']) }}" class="st" style="{{ $value['highlight_style'] }}" title="{{ $value['subject'] }}" target="_blank">{{ $value['threadSubject'] }}</a>
@if ($value['disabled'])
<span class="red">(审核中)</span><!--# } #-->

								</td>
								<td class="time">{{ $value['created_time'] }}</td>
							</tr>
						<!--# } #-->
						</table>
						<div class="">
							<page tpl="TPL:common.page" page="$page" per="$perpage" count="$count" url="space/thread/post/" args="$args"/>
						</div>

@elseif ($space->tome == 2)

						<div class="not_content">啊哦，您暂没有回复任何主题哦！去<a href="{{ url('') }}">首页看看</a>有什么精彩内容吧！</div>

@else

						<div class="not_content">啊哦，Ta暂没有回复过帖子！</div>
						<!--# } #-->
					</div>
				</div>
			</div>
<!--结束-->
		</div>
		<div class="space_sidebar">
			@include('space.common.sidebar')
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