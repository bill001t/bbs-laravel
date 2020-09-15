<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/invite.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em>
			<a href="{{ url('my/invite/inviteFriend') }}">邀请好友</a>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content cc">
					<div class="box_wrap invite_page">
						@include('bbs.mine_tab')
		<!--链接邀请-->
						<div class="invite_address_copy">
							<h2 class="mb20">复制链接邀请</h2>
							<div class="mb20">
								<p class="mb5">复制链接发送给好友，邀请好友一起加入！</p>
								<p><input id="J_clipboard_invite" type="text" class="input length_6 mr5" value="{{ $url }}"><a class="btn btn_submit J_clipboard_input" href="javascript:;" data-rel="J_clipboard_invite">复制链接</a></p>
							</div>
						</div>
		<!--结束-->
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
<script type="text/javascript">
Wind.use('jquery', 'global', function(){

});
</script>
</body>
</html>