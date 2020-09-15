<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/task.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('task/index/run') }}">任务</a><em>&gt;</em><a href="{{ url('task/index/completeList') }}">已完成</a>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content">
					<div class="box_wrap task_page">
					<nav>
						<div class="content_nav">
							<ul>
								<li><a href="{{ url('task/index/run') }}">进行中</a></li>
								<li><a href="{{ url('task/index/applicableList') }}">可领取</a></li>
								<li class="current"><a href="{{ url('task/index/completeList') }}">已完成</a></li>
							</ul>
						</div>
					</nav>

@if (!$list)

						<div class="not_content">啊哦，暂没有已完成的任务！</div>

@else

						<div class="task_list">

@foreach ($list as $id => $item)
$reward = $item['reward']['descript'] ? $item['reward']['descript'] : '无';
		#-->
								<dl id="J_task_{{ $item['taskid'] }}" class="dl_complete">
									<dt>

@if ($item['icon'])

									<img src="{{ asset('assets/images/common/blank.gif') }}" width="80" height="80" style="background:url({{ App\Core\Tool::getPath($item['icon']) }}) no-repeat;" alt="{{ $item['title'] }}" />

@else

									<img src="{{ asset('assets/images/common/blank.gif') }}" width="80" height="80" style="background:url({{ asset('assets/images/task/none.png') }}) no-repeat;" alt="{{ $item['title'] }}" />
	   					<!--#}#-->
	   								</dt>
									<dd>
										<p><strong class="name">{{ $item['title'] }}</strong></p>
										<p>目标：{!! $item['description'] !!}</p>
										<p>奖励：<span class="green">{{ $reward }}</span></p>
									</dd>
								</dl>
		<!--#}#-->
						</div>
						<div class="p20">
							<page tpl="TPL:common.page" url="task/index/completeList" page="$page" count="$count" per="$perpage" />
						</div>
		<!--#}#-->
					</div>
				</div>
			</div>
			<div class="main_sidebar">
				@include('common.sidebar_1')
			</div>
<!--弹窗-->
<div class="pop_deep" tabindex="0" style="display:none;">
	<div class="core_pop">
		<div class="hd" style="cursor: move;">
			<a class="close J_close" href="#">关闭</a>
			<strong>领取任务</strong>
		</div>
		<div class="ct">
			<dl class="cc">
				<dt class="reward"><img src="{{ asset('assets/images/task/task_reward.png') }}" width="160" height="119"></dt>
					<dd>
						<p><span class="b">恭喜您，成功领取喜欢1个帖子任务 ！</span></p>
						<p>奖励：<strong class="org">10个威望</strong></p>
					</dd>
			</dl>
			<div class="tac">
				<button type="submit" class="btn btn_submit btn_big">去做任务</button>
			</div>
		</div>
	</div>
</div>
<!--弹窗结束-->
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script>
Wind.use('jquery', 'global');
</script>
</body>
</html>