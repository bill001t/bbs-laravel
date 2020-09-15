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
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('task/index/run') }}">任务</a><em>&gt;</em><a href="{{ url('task/index/applicableList') }}">可领取</a>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content">
					<div class="box_wrap task_page">
						<nav>
						<div class="content_nav">
							<ul>
								<li><a href="{{ url('task/index/run') }}">进行中</a></li>
								<li class="current"><a href="{{ url('task/index/applicableList') }}">可领取</a></li>
								<li><a href="{{ url('task/index/completeList') }}">已完成</a></li>
							</ul>
						</div>
						</nav>

@if (!$list)

						<div class="not_content">啊哦，暂没有可领取的任务！</div>

@else

						<div class="task_list" id="J_task_list">

@foreach ($list as $id => $item)
$reward = $item['reward']['descript'] ? $item['reward']['descript'] : '无';
			$time = '';
			if ($item['start_time'] || $item['end_time'] != PwTaskDm::MAXENDTIME) {
				$start_time = $item['start_time'] ? App\Core\Tool::time2str($item['start_time'], 'Y-m-d') : '不限';
				$end_time = $item['end_time'] == PwTaskDm::MAXENDTIME ? '不限' : App\Core\Tool::time2str($item['end_time'], 'Y-m-d');
				$time = $start_time . ' 至 ' . $end_time;
			} else {
				$time = '不限';
			}
		#-->
							<dl id="J_task_{{ $item['taskid'] }}">
								<dt style="height:95px;">

@if ($item['icon'])

									<img src="{{ asset('assets/images/common/blank.gif') }}" width="80" height="80" style="background:url({{ App\Core\Tool::getPath($item['icon']) }}) no-repeat;" alt="{{ $item['title'] }}" />

@else

									<img src="{{ asset('assets/images/common/blank.gif') }}" width="80" height="80" style="background:url({{ asset('assets/images/task/none.png') }}) no-repeat;" alt="{{ $item['title'] }}" />
	    				<!--#}#-->
								
								</dt>
								<dd>
									<p><strong class="name">{{ $item['title'] }}</strong></p>

@if ($pre)

										<p class="factor">必须完成【{$pre}】才能领取</p>
		<!--#}#-->
									<p>目标：{!! $item['description'] !!}</p>
									<p>时限：{{ $time }}</p>
									<p>奖励：<span class="green">{{ $reward }}</span></p>
									<p class="tar"><a data-id="{{ $item['taskid'] }}" class="btn btn_submit J_task_get_btn" href="{{ url('task/index/applyTask?id=' . $id) }}">领取任务</a></p>
								</dd>
							</dl>
		<!--#}#-->
						</div>
						<div class="p20">
							<page tpl="TPL:common.page" url="task/index/applicableList" page="$page" count="$count" per="$perpage" />
						</div>
		<!--#}#-->
					</div>
				</div>
			</div>
			<div class="main_sidebar">
				@include('common.sidebar_1')
			</div>

<!--弹窗-->
<textarea id="J_task_ta" class="dn">
	<div class="hd">
		<a class="close J_close" href="" id="J_task_pop_close">关闭</a>
		<strong>领取任务</strong>
	</div>
	<div class="ct">
		<dl class="cc">
			<dt class="reward"></dt>
			<dd class="reward_cont">
				<p><span class="b">恭喜您，成功领取<span id="J_task_name"></span>任务 ！</span></p>
				<p>奖励：<strong id="J_task_reward" class="org"></strong></p>
			</dd>
		</dl>
		<div class="tac">
			<a class="btn btn_submit btn_big" id="J_task_link">去做任务</a>
		</div>
	</div>
</textarea>
<!--弹窗结束-->
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script>
Wind.use('jquery', 'global', 'dialog', function(){
	Wind.use(GV.JS_ROOT +'pages/task/task_index.js?v=' + GV.JS_VERSION);
});
</script>
</body>
</html>