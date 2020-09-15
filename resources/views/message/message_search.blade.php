<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/message.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('message/message/run') }}">消息</a><em>&gt;</em><a href="{{ url('message/message/run') }}">私信</a>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content">
					<!--中间内容-->
					<div class="box_wrap message_page">
							@include('message.message_header')
							<div class="notification_center">

@if ($messages)
Wind::import('LIB:ubb.PwUbbCode');
							#-->
								<dl class="hd cc">
									<dd>
										找到"<span class="red">{{ $args['keyword'] }}</span>"相关结果 <span class="red">{{ $count }}</span> 个
									</dd>
								</dl>

@foreach ($messages as $value)
$value['created_time'] = App\Core\Tool::time2str($value['created_time'], 'auto');
									$value['content'] = str_replace ( $args[keyword], '<span class="red">' . $args[keyword] . '</span>', $value['content'] );
									$value['content'] = PwUbbCode::parseEmotion($value['content']);
								#-->
								<dl class="cc">
									<dd>
										<a href="{{ url('space/index/run?uid=' . $value['from_uid']) }}" class="face"><img src="{{ asset('assets/images') }}/face/face_small.jpg" width="50" height="50" alt="{{ $value['username'] }}" /></a>
										<div class="title">
											<a href="{{ url('space/index/run?uid=' . $value['from_uid']) }}" class="b">{{ $value['username'] }}</a>：{$value['content']}
										</div>
										<div class="c"></div>
										<div class="info"><span class="num"><a href="{{ url('message/message/searchdialog?from_uid=' . $value['from_uid'] . '&mes_id=' . $value['id']) }}">查看前后内容</a></span><span class="time">{{ $value['created_time'] }}</span></div>
									</dd>
								</dl>
								<!--# } #-->

@else

					<!--无结果-->
								<div class="f14 p20 tac">没有匹配的结果</div>
							<!--# } #-->
								<page tpl='TPL:common.page' page='$page' count='$count' per='$perpage' url='message/message/search' args='$args'/>
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
Wind.use('jquery', 'global');
</script>
</body>
</html>