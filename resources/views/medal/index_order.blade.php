<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/medal.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('medal/index/run') }}">勋章</a><em>&gt;</em><a href="{{ url('medal/index/order') }}">勋章排行</a>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content">
					<div class="box_wrap medal_page">
					<nav>
						<div class="content_nav">
							<ul>
								<li><a href="{{ url('medal/index/run') }}">我的勋章</a></li>
								<li><a href="{{ url('medal/index/center') }}">勋章中心</a></li>
								<li class="current"><a href="{{ url('medal/index/order') }}">勋章排行</a></li>
							</ul>
						</div>
					</nav>
						<div class="medal_content">
							<div class="medal_rank cc">
							

@if ($attentionMedals)

							
								<div class="medal_rank_follow">
									<h2>

@if ($info['counts']>0)

										<div class="my">我的勋章：{$info['counts']}枚，好友中排名：

@if($info['sort'])

											第{$info['sort']}名

@else

											10名以外
										<!--# } #-->
										</div>

@else

										<div class="my">您还没有勋章，<a href="{{ url('medal/index/center') }}">去领几个吧&gt;&gt;</a></div>
									<!--# } #-->
										好友排名
									</h2>
									<ol class="cc">
									<!--# 
										$k=1;
										foreach ($attentionMedals as $medal) {
											if (!isset($userInfos[$medal['uid']])) continue;
											$uid = $userInfos[$medal['uid']]['uid'];
										#-->
										
										<li>
											<div class="num"><em class="em_{{ $k }}">{{ $k }}</em><span>{{ $medal['counts'] }}枚</span></div>
											<a class="J_user_card_show" data-uid="{{ $userInfos[$medal['uid']]['uid'] }}" href="{{ url('space/index/run?uid=' . $uid) }}" target="_blank"><img class="J_avatar" src="{{ App\Core\Tool::getAvatar($userInfos[$medal['uid']]['uid'], 'middle') }}" data-type="middle" width="90" height="90" alt="{{ $userInfos[$medal['uid']]['username'] }}" /><span class="name">{{ App\Core\Tool::substrs($userInfos[$medal['uid']]['username'],7) }}</span></a>
										</li>

									<!--# 
										$k++; 
									}#-->
									</ol>
									<!-- <div class="not_content">啊哦，关注的人暂没有内容，赶紧先关注些人！</div> -->
								</div>
							<!--# } #-->
								<div class="medal_rank_all">
									<h2>

@if ($info['counts']>0)

										<div class="my">

@if($totalOrder > 0)

											全站排名：第{$totalOrder}名

@else

											未进入前十，继续努力！
										<!--# } #-->
										</div>

@else

										<div class="my">您还没有勋章，<a href="{{ url('medal/index/center') }}">去领几个吧&gt;&gt;</a></div>
									<!--# } #-->
									全站排名
									</h2>
									<ol class="cc">
									<!--# 
										$k=1;
										foreach ($totalMedals as $medal){
										$uid = $userInfos[$medal['uid']]['uid'];
									#-->
										
										<li>
											<div class="num"><em class="em_{{ $k }}">{{ $k }}</em><span>{{ $medal['counts'] }}枚</span></div>
											<a class="J_user_card_show" data-uid="{{ $userInfos[$medal['uid']]['uid'] }}" href="{{ url('space/index/run?uid=' . $uid) }}"  target="_blank"><img class="J_avatar" src="{{ App\Core\Tool::getAvatar($userInfos[$medal['uid']]['uid'], 'middle') }}" data-type="middle" width="90" height="90" alt="{{ $userInfos[$medal['uid']]['username'] }}" /><span class="name">{{ App\Core\Tool::substrs($userInfos[$medal['uid']]['username'],7) }}</span></a>
										</li>
										
									<!--# $k++; } #-->
									</ol>
									<!-- <div class="not_content">啊哦，全站排名暂没有内容！</div> -->
								</div>
							</div>
						</div>
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
Wind.use('jquery', 'global');
</script>
</body>
</html>