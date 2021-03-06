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
			<a href="{{ url('my/friend/run') }}">找人</a><em>&gt;</em>
			<a href="{{ url('my/friend/search') }}">查找关注</a>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content cc">
					<div class="box_wrap fans_page">
						@include('bbs.mine_tab')
						<div class="content_type">
							<ul class="cc">
								<li><a href="{{ url('my/friend/run') }}">推荐关注</a></li>
								<li class="line"></li><li><a href="{{ url('my/friend/friend') }}">可能认识</a></li>
								<li class="line"></li><li class="current"><a href="{{ url('my/friend/search') }}">查找关注</a></li>
							</ul>
						</div>
						<div class="friend_page">
							<form action="{{ url('my/friend/search') }}" method="post">
							<h2>按用户名查找</h2>
							<div class="user">
								<input type="text" name="username" class="input length_4 J_friends_search_input" placeholder="输入昵称，如张三" value="{{ $args['username'] }}"><button type="submit" class="btn J_friends_search_btn">查找</button>
							</div>
							<h2>按标签查找</h2>
							<div class="tags">

@if ($usertags)

								<div class="hd"><em>我的标签</em>：

@foreach ($usertags as $v)

								<a href="{{ url('my/friend/search?usertag=' . $v['name']) }}">{{ $v['name'] }}</a>
								<!--# } #-->
								</div>

@else

								<div class="hd"><em>热门标签</em>：

@foreach ($hotTags as $v)

								<a href="{{ url('my/friend/search?usertag=' . $v['name']) }}">{{ $v['name'] }}</a>
								<!--# } #-->
								</div>
								<!--# } #-->
								<input type="text" name="usertag" class="input length_4 J_friends_search_input" placeholder="输入你感兴趣的标签" value="{{ $args['usertag'] }}"><button type="submit" class="btn J_friends_search_btn">查找</button>
							</div>
							</form>
						</div>
					</div>

@if ($userList)

					<div class="box_wrap fans_page">
						<div class="content_type">
							<ul class="cc">
								<li>总共<span class="org">{{ $count }}</span>人</li>
							</ul>
						</div>
						<div class="fans_list">

@foreach ($userList as $key => $value)

							<dl class="cc J_friends_items">
								<dt><a data-uid="{{ $value['uid'] }}" class="J_user_card_show" href="{{ url('space/index/run?uid=' . $value['uid']) }}"><img class="J_avatar" src="{{ App\Core\Tool::getAvatar($value['uid'], 'small') }}" data-type="small" width="50" height="50" alt="{{ $value['username'] }}" /></a>

@if (Core::getLoginUser()->uid != $value['uid'])

								<a href="{{ url('message/message/pop?username=' . $value['username']) }}" class="called J_send_msg_pop" data-name="{{ $value['username'] }}">打招呼</a>
								<!--# } #-->
								</dt>
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

@if (Core::getLoginUser()->uid != $value['uid'])
$isfan = App\Core\Tool::inArray($value['uid'],array_keys((array)$fans)) ? 'true' : 'false';
									#-->
									<div class="attribute">

@if (isset($friends[$value['uid']]))

										<span class="mnfollow" title="互相关注">互相关注</span>
										<!--# } if (isset($follows[$value['uid']])) { #-->
										<a href="{{ url('my/follow/delete?csrf_token=' . $csrf_token) }}" class="core_unfollow J_fans_follow J_unfollow_btn" data-role="unfollow" data-uid="{{ $value['uid'] }}" data-role="unfollow" style="display:none;" data-followed="{{ $isfan }}">取消关注</a>

@else

										<a href="{{ url('my/follow/add?csrf_token=' . $csrf_token) }}" class="core_follow J_fans_follow" data-role="follow" data-uid="{{ $value['uid'] }}" data-role="follow" data-followed="{{ $isfan }}">加关注</a>
										<!--# } #-->
									</div>
									<!--# } #-->
								</dd>
							</dl>
							<!--# } #-->
							<div class="tac"><page tpl="TPL:common.page" page="$page" per="$perpage" count="$count" url="my/friend/search?username=$args['username']&usertag=$args['usertag']" args="$args" /></div>
						</div>
					</div>

@else


@if ($args)

					<div class="box_wrap"><div class="not_content">哎呀，没找到呢，再找找看</div></div>
					<!--# } #-->
					<!--# } #-->
				</div>
			</div>
			<div class="main_sidebar">
				@include('common.sidebar_1')
			</div>
		</div>
	</div>
{{--  @include('common.footer') --}}
<script>
var URL_UNFOLLOW = "{{ url('my/follow/delete/') }}",
		URL_FOLLOW = "{{ url('my/follow/add/') }}";
Wind.use('jquery', 'global', GV.JS_ROOT +'pages/my/fansFollow.js?v='+ GV.JS_VERSION, function(){
	$('input.J_friends_search_input').each(function(){
		Wind.Util.buttonStatus($(this), $(this).next('.J_friends_search_btn'));
	});
});
</script>
</div>
</body>
</html>