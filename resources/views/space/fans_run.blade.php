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
			<div class="box">
				<div class="my_article">
					<div class="hd"><h2>{{ $host }}的粉丝</h2></div>
				</div>
				<div class="space_fans">

@if ($fans)


@foreach ($fans as $key => $value)

					<dl class="cc">
						<dt><a data-uid="{{ $value['uid'] }}" class="J_user_card_show" href="{{ url('space/index/run?uid=' . $value['uid']) }}"><img class="J_avatar" src="{{ App\Core\Tool::getAvatar($value['uid'], 'small') }}" data-type="small" width="50" height="50" /></a><a rel="nofollow" href="{{ url('message/message/pop?username=' . $value['username']) }}" data-name="{{ $value['username'] }}" class="called J_send_msg_pop J_qlogin_trigger">打招呼</a></dt>
						<dd>
							<div class="title">
								<a href="{{ url('space/index/run?uid=' . $value['uid']) }}" data-uid="{{ $value['uid'] }}" class="name J_user_card_show">{{ $value['username'] }}</a>
							<!--# $status = App\Core\Tool::checkOnline($value['lastvisit']) ? '' : 'un';
								if($value['gender']){ #-->
								<span class="women_{$status}ol"></span>

@else

								<span class="man_{$status}ol"></span>
							<!--# } #-->
							</div>
							<div class="num">
								关注<a href="{{ url('space/follows/run?uid=' . $value['uid']) }}">{{ $value['follows'] }}</a><span>|</span>粉丝<a href="{{ url('space/fans/run?uid=' . $value['uid']) }}">{{ $value['fans'] }}</a><span>|</span>帖子<a href="{{ url('space/thread/run?uid=' . $value['uid']) }}">{{ $value['postnum'] }}</a>
							</div>
							<!-- <div class="action">回复了帖子<a href="">那些年，我们一起游过的海南（完整版）</a><span class="time">（{{ App\Core\Tool::time2str($value['created_time'], 'auto') }}）</span></div> -->
							<div class="attribute">

@if (isset($follows[$value['uid']]))
if (isset($fans[$value['uid']])) {#-->
									<span class="mnfollow" title="互相关注">互相关注</span>
										<!--#}#-->

@elseif (Core::getLoginUser()->isExists() && Core::getLoginUser()->uid != $value['uid'])
$isfan = isset($fans[$value['uid']]) ? 'true' : 'false';
								#-->
									<a href="{{ url('my/follow/add') }}" class="core_follow J_space_fans J_qlogin_trigger" data-followed="{{ $isfan }}" data-uid="{{ $value['uid'] }}">加关注</a>
								<!--# } #-->
								
							</div>
						</dd>
					</dl>
					<!--# } #-->
					<page tpl="TPL:common.page" page="$page" per="$perpage" count="$count" url="space/fans/run?uid=$space->spaceUid"/>

@else


@if (Core::getLoginUser()->uid == $space->spaceUid)

					<!--看自己-->
					<div class="not_content J_check_wrap">啊哦，你还没有粉丝，试试关注几个争取点人气吧！</div>

@else

					<!--看别人-->
					<div class="not_content">啊哦，Ta还没有粉丝，我来第一个<a href="{{ url('my/follow/add') }}" id="J_nofans_follow" data-uid="{{ $space->spaceUid }}">关注Ta</a>！</div>
					<!--# } #-->
				<!--# } #-->
				</div>
			</div>
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


@if ($fans)

	
	//关注 取消
	var lock = false;
	$('a.J_space_fans').on('click', function(e){
		if(!GV.U_ID) {
			return;
		}
		e.preventDefault();
		var $this = $(this);

		if(lock) {
			return false;
		}
		lock = true;

		Wind.Util.ajaxMaskShow();
		$.post(this.href, {
			uid: $this.data('uid')
		}, function(data){
			Wind.Util.ajaxMaskRemove();
			if(data.state == 'success') {
				var followed = $this.data('followed');
				if(followed) {
					//已关注我
					$this.replaceWith('<span class="mnfollow">互相关注</span>');
				}else{
					$this.replaceWith('<span class="core_unfollow">已关注</span>');
				}

				$('#J_user_card_'+ $this.data('uid')).remove();
			}else if(data.state == 'fail') {
				Wind.Util.resultTip({
					elem : $this,
					error : true,
					follow : true,
					msg : data.message
				});
			}

			lock = false;

		}, 'json');
	});


@else


	//我来第一个关注Ta
	$('#J_nofans_follow').on('click', function(e){
		e.preventDefault();
		if(!GV.U_ID) {
			Wind.Util.quickLogin();
			return;
		}
		
		var $this = $(this);
		Wind.Util.ajaxMaskShow();
		$.post(this.href, {
			uid: $this.data('uid')
		}, function(data){
			Wind.Util.ajaxMaskRemove();
			if(data.state == 'success') {
				Wind.Util.resultTip({
					follow : $this,
					msg : data.message,
					callback : function(){
						location.reload();
					}
				});
			}else if(data.state == 'fail') {
				Wind.Util.resultTip({
					error : true,
					follow : $this,
					msg : data.message
				});
			}
		}, 'json');
		
	});

<!--# } #-->
	
});
</script>
</body>
</html>