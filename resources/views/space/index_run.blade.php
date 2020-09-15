<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/style.css') }} "rel="stylesheet" />
<style>
.aPre{
	cursor:url({{ asset('assets/images/site/images') }}/common/pre.cur),auto;
}
.aNext{
	cursor:url({{ asset('assets/images/common/next.cur }},auto;right:0;
}
</style>
</head>
<body {!! $space->space['backbround'] !!}>
<div class="wrap">
{{-- @include('common.header') --}}
<div class="space_page">
	@include('space.common.nav')
	<div class="cc">
		<div class="space_content" id="J_feed_lists">
			<!--# 
				$perpage = 20;
				list($count, $fresh) = $space->model('fresh',$perpage, $page);
				if (!$fresh) {
					if ($space->tome == 2) {
			#-->
				<div class="box"><div class="not_content">啊哦，新鲜事暂没有内容哦！去<a href="{{ url() }}">首页看看</a>大家都在说什么！</div></div>

@else

			 		<div class="box"><div class="not_content">啊哦，这家伙太懒了，什么也没留下！</div></div>
			 		<!--# } #-->
			 <!--# }
				$totalpage = ceil($count / $perpage);
				foreach ($fresh AS $key => $value) {
					$style_reply = $value['replies'] ? '' : 'display:none;';
			#-->
			<div class="box">
				<div class="space_fresh">
					<dl class="cc">
						<dt><a class="J_user_card_show" data-uid="{{ $value['created_userid'] }}" href="{{ url('space/index/run?uid=' . $value['created_userid']) }}" target="_blank"><img class="J_avatar" src="{{ App\Core\Tool::getAvatar($value['created_userid'], 'small') }}" data-type="small" width="50" height="50" /></a></dt>
						<dd>

@if ($value['title'])

							<div class="title"><a href="{{ url('space/index/run?uid=' . $value['created_userid']) }}" class="author J_user_card_show" data-uid="{{ $value['created_userid'] }}" target="_blank">{{ $value['created_username'] }}</a>：
							<a href="{{ url('bbs/read/run?tid=' . $value['src_id']) }}" class="subject" target="_blank">{{ $value['title'] }}</a>
							</div>
							<div id="J_feed_content_{{ $value['id'] }}" class="descrip">{!! $value['content'] !!}

@if ($value['is_read_all'])

							<a target="_blank" href="{{ url('bbs/read/run?tid=' . $value['src_id']) }}" class="more">全文</a>
							<!--# } #-->
							</div>

@else

							<div class="title"><a href="{{ url('space/index/run?uid=' . $value['created_userid']) }}" class="author J_user_card_show" data-uid="{{ $value['created_userid'] }}" target="_blank">{{ $value['created_username'] }}</a>：
								<span id="J_feed_content_{{ $value['id'] }}" class="descrip fn">{!! $value['content'] !!}</span>
							</div>
							<!--# } #-->
							<div class="J_content_all" style="display:none;"></div>

@if ($value['pic'])
$_picCount = count($value['pic']);
									$k = 0;
							#-->
							<div class="photo">
								<ul class="cc J_gallery_list">

@foreach ($value['pic'] as $v)
$_isDisplay = $k++ >= 4 ? 'dn' : '';
									#-->
									<li class="fl J_gallery_items  {{ @$_isDisplay }}"><a href="#" data-big="{{ App\Core\Tool::getPath($v['path']) }}"><img onerror="this.onerror=null;this.className='J_error';" width="100" height="100" src="{{ App\Core\Tool::getPath($v['path'], $v['ifthumb']) }}" /></a></li>
									<!--# } #-->

@if ($_picCount > 4)
<li>共{$_picCount}张图片</li><!--#}#-->
									<!-- 
									<li style="background:url({{ asset('assets/images/preview.jpg') }}) center center no-repeat;width:160px;"></li>
									<li style="background:url({{ asset('assets/images/preview.jpg') }}) center center no-repeat;width:185px;"></li> -->
									<!-- <li>共20张图，1个附件</li> -->
								</ul>
							</div>
							<!--# } #-->

@if ($value['quote'])

								<div class="feed_repeat feed_quote">
									<div class="feed_repeat_arrow">
										<em>◆</em>
										<span>◆</span>
									</div>
									

@if ($value['quote']['subject'])

									<div class="title"><a href="{{ url('space/index/run?uid=' . $value['quote']['created_userid']) }}" class="name" target="_blank">{{ $value['quote']['created_username'] }}</a>：
									<a href="{{ url('bbs/read/run?tid=' . $value['quote']['tid']) }}" class="subject" target="_blank">{{ $value['quote']['subject'] }}</a>
									</div>
									<div class="descrip">{!! $value['quote']['content'] !!}</div>

@else

									<div class="title"><a href="{{ url('space/index/run?uid=' . $value['quote']['created_userid']) }}" class="name" target="_blank">{{ $value['quote']['created_username'] }}</a>：
										<span class="descrip">{!! $value['quote']['content'] !!}</span>
									</div>
									<!--# } #-->
									
									<div class="info">

@if(Core::getLoginUser()->uid)

										<span class="right"><!-- <a href="#">喜欢(135)</a><i>|</i> --><a href="{{ $value['quote']['url'] }}">回复({$value['quote']['replies']})</a></span>
										<!--# } #-->
										<span class="time">{{ App\Core\Tool::time2str($value['quote']['created_time'], 'auto') }}</span>
									</div>
								</div>
							<!--# } #-->
						</dd>
					</dl>
					<p class="my_info">

@if(Core::getLoginUser()->uid)

						<span class="right"><a rel="nofollow" data-id="{{ $value['id'] }}" class="J_feed_toggle" href="{{ url('space/index/reply?id=' . $value['id'] . '&uid=' . $space->spaceUid) }}">回复<span style="{{ $style_reply }}">(<em id="J_feed_count_{{ $value['id'] }}">{{ $value['replies'] }}</em>)</span></a></span>
						<!--# } #-->
						<span class="time"><a href="{{ url('space/index/fresh?uid=' . $value['created_userid'] . '&id=' . $value['id']) }}">{{ App\Core\Tool::time2str($value['created_time'], 'auto') }}</a></span>&nbsp;来自{!! $value['from'] !!}
					</p>
				</div>
			</div>
			<div id="J_feed_list_{{ $value['id'] }}" class="space_feed_repeat J_feed_list" style="display:none;"></div>
			<!--# }	#-->
			<page tpl="TPL:common.page" total="$totalpage" page="$page" per="$perpage" count="$count" url="space/index/run?uid=$space->spaceUid"/>
		</div>
		<div class="space_sidebar">
			@include('space.common.sidebar')
			@include('space.common.sidebar_more')
		</div>
	</div>
</div>
{{--  @include('common.footer') --}}
</div>
<script>
//新鲜事回复提交地址
var FRESH_DOREPLY = "{{ url('space/myspace/doreply') }}";

//引入js组件
Wind.use('jquery', 'global', 'dialog', 'ajaxForm', 'tabs', 'draggable', 'uploadPreview', function(){
	Wind.js(GV.JS_ROOT +'pages/space/space_index.js?v='+ GV.JS_VERSION, GV.JS_ROOT +'pages/common/freshRead.js?v='+ GV.JS_VERSION);

	//回复表情 表情js由freshRead.js引入
	$('#J_feed_lists').on('click', 'a.J_fresh_emotion', function(e){
		e.preventDefault();
		insertEmotions($(this), $($(this).data('emotiontarget')));
	}); 
});
</script>
</body>
</html>