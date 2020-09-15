<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/fans.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em>
			<a href="{{ url('my/visitor/run') }}">访问脚印</a><em>&gt;</em>
			<a href="{{ url('my/visitor/tovisit') }}">我看过谁</a>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content cc">
					<div class="box_wrap fans_page">
						@include('bbs.mine_tab')
						<div class="content_type">
							<ul class="cc">
								<li><a href="{{ url('my/visitor/run') }}">谁看过我</a></li>
								<li class="line"></li><li class="current"><a href="{{ url('my/visitor/tovisit') }}">我看过谁</a></li>
							</ul>
						</div>

@if ($userList)

						<div class="fans_list">

@foreach ($userList as $key => $value)

							<dl class="cc J_friends_items">
								<dt><a data-uid="{{ $value['uid'] }}" class="J_user_card_show" href="{{ url('space/index/run?uid=' . $value['uid']) }}"><img class="J_avatar" src="{{ App\Core\Tool::getAvatar($value['uid'], 'small') }}" data-type="small" width="50" height="50" alt="{{ $value['username'] }}" /></a><a href="{{ url('message/message/pop?username=' . $value['username']) }}" class="called J_send_msg_pop" data-name="{{ $value['username'] }}">打招呼</a></dt>
								<dd>
									<div class="title">
										<a href="{{ url('space/index/run?uid=' . $value['uid']) }}" data-uid="{{ $value['uid'] }}" class="name J_user_card_show">{{ $value['username'] }}</a>
										<!--# $gender = $value['gender'] == 1 ? 'women' : 'man';
											$online = App\Core\Tool::checkOnline($value['lastvisit']) ? 'ol' : 'unol';
										 #-->
										<span class="{{ $gender }}_{{ $online }}"></span>
									</div>
									<div class="num">
										关注<a href="{{ url('space/follows/run?uid=' . $value['uid']) }}">{{ $value['follows'] }}</a><span>|</span>粉丝<a href="{{ url('space/fans/run?uid=' . $value['uid']) }}">{{ $value['fans'] }}</a><span>|</span>帖子<a href="{{ url('space/thread/run?uid=' . $value['uid']) }}">{{ $value['postnum'] }}</a>
									</div>
									<div class="action">访问时间：<span class="time">{{ App\Core\Tool::time2str($visitors[$value['uid']], 'auto') }}</span></div>

@if (Core::getLoginUser()->uid != $value['uid'])
$isfan = App\Core\Tool::inArray($value['uid'],array_keys((array)$fans)) ? 'true' : 'false';
									#-->
									<div class="attribute">

@if (isset($friends[$value['uid']]))

										<span class="mnfollow" title="互相关注">互相关注</span>
										<!--# } if (isset($follows[$value['uid']])) { #-->
										<a href="{{ url('my/follow/delete?csrf_token=' . $csrf_token) }}" class="core_unfollow J_fans_follow J_unfollow_btn" data-role="unfollow" data-uid="{{ $value['uid'] }}" data-role="unfollow" style="display:none;" data-followed="{{ $isfan }}">取消关注</a>

@else
$isfan = App\Core\Tool::inArray($value['uid'],array_keys((array)$fans)) ? 'true' : 'false';
										#-->
										<a href="{{ url('my/follow/add?csrf_token=' . $csrf_token) }}" class="core_follow J_fans_follow" data-role="follow" data-uid="{{ $value['uid'] }}" data-role="follow" data-followed="{{ $isfan }}">加关注</a>
										<!--# } #-->
									</div>
									<!--# } #-->
								</dd>
							</dl>
							<!--# } #-->
						</div>

@else

							<div class="nofollow_list"><div class="hd">啊哦，太不厚道了，去<a href="{{ url('space/index/run?uid=' . $lastPostUser) }}" target="_blank">看看Ta</a>有什么新动态！</div></div>
						<!--# } #-->
					</div>
				</div>
			</div>
			<div class="main_sidebar">
				@include('common.sidebar_1')
			</div>
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script>
var URL_UNFOLLOW = "{{ url('my/follow/delete/') }}",
		URL_FOLLOW = "{{ url('my/follow/add/') }}";
Wind.use('jquery', 'global', GV.JS_ROOT +'pages/my/fansFollow.js?v='+ GV.JS_VERSION);
</script>
</body>
</html>