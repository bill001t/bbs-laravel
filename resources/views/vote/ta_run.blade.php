<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/vote.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('vote/my/run') }}">投票</a><em>&gt;</em><a href="{{ url('vote/ta/run') }}">Ta的投票</a>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content">
					<div class="box_wrap vote_page">
					<nav>
						<div class="content_nav">
							<ul>
								<li><a href="{{ url('vote/hot/run') }}">热门投票</a></li>
								<li class="current"><a href="{{ url('vote/ta/run') }}">Ta的投票</a></li>
								<li><a href="{{ url('vote/my/run') }}">我的投票</a></li>
							</ul>
						</div>
						<div class="content_type">
							<ul class="cc">
								<li class="current"><a href="{{ url('vote/ta/run') }}">Ta参与的</a></li>
								<li class="line"></li><li><a href="{{ url('vote/ta/create') }}">Ta发起的</a></li>
							</ul>
						</div>
					</nav>
						<div class="vote_list">

@if ($total)
foreach($pollInfo as $value){ #-->
								@include('vote.listcommon')
							<!--# }} else { #-->
								<!--无关注人时-->
								<form action="{{ url('my/follow/batchadd') }}" method="post" id="J_nofollow_form">
								<div class="nofollow_list J_check_wrap">
									<div class="hd">啊哦，Ta的投票暂没有任何内容，赶紧先关注些人！</div>

@if($recommend)

									<ul class="cc">

@foreach ($recommend as $value)

										<li><a data-uid="{{ $value['uid'] }}" class="J_user_card_show" href="{{ url('space/index/run?uid=' . $value['uid']) }}"><img class="J_avatar" src="{{ App\Core\Tool::getAvatar($value['uid'], 'middle') }}" data-type="middle" width="90" height="90" alt="{{ $value['username'] }}" /></a><label><input class="J_check" type="checkbox" name="uids[]" value="{{ $value['uid'] }}">{{ $value['username'] }}</label></li>
										<!--# } #-->
									</ul>
									<div class="ft">
										<button type="submit" class="btn btn_big btn_success disabled" disabled="true" id="J_nofollow_btn"><span class="add"></span>关注</button>
										<label><input class="J_check_all" type="checkbox">全选</label>
									</div>
									<!--# } #-->
								</div>
								</form>
								<!--无关注人时结束-->
							<!--# } #-->
							<div><page tpl='TPL:common.page' page='$page' per='$perpage' count='$total' url='vote/ta/run' /></div>
						</div>
					</div>
				</div>
			</div>
			<div class="main_sidebar">
				@include('common.sidebar_1')
				@include('vote.sidebar')
			</div>
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script>
Wind.use('jquery', 'global', 'ajaxForm', function(){
	Wind.js(GV.JS_ROOT +'pages/vote/vote_index.js?v=' + GV.JS_VERSION);
	
		/*
		 * 无关注
		*/
		var nofollow_btn = $('#J_nofollow_btn');
		$('input:checkbox').prop('checked', false);
		//复选框
		$('input.J_check_all').on('change', function(){
			if(this.checked) {
				nofollow_btn.prop('disabled', false).removeClass('disabled');
			}else{
				nofollow_btn.prop('disabled', true).addClass('disabled');
			}
		});

		var checks = $('input.J_check');

		checks.on('change', function(){
			if (checks.filter(':checked').length > 0) {
				nofollow_btn.prop('disabled', false).removeClass('disabled');
			}else{
				nofollow_btn.prop('disabled', true).addClass('disabled');
			}
		});

		$('#J_nofollow_form').ajaxForm({
			dataType : 'json',
			beforeSubmit : function(){
				//global.js
				Wind.Util.ajaxBtnDisable(nofollow_btn);
			},
			success : function(data){
				if(data.state == 'success') {
					location.reload();
				}else{
					//global.js
					Wind.Util.ajaxBtnEnable(nofollow_btn);
				}
			}
		});
});
</script>
</body>
</html>