<!--at用户消息-->
<hook-action name="minilist" args="v">
<!--弹窗列表-->
	<dl class="notice_segment_list cc">
		<dt class="notice_tip_icon">

@if (!$v['is_read'])

			<span class="icon_at_new J_unread_icon" title="未读">[未读]</span>

@else

			<span class="icon_at" title="已读">[已读]</span>
			<!--# } #-->
		</dt>
		<dd class="notice_segment_cont">
			<div class="summary">
				<a href="{{ url('space/index/run?uid=' . $v['extend_params']['remindUid']) }}" target="_blank">{{ $v['extend_params']['remindUsername'] }}</a> {!! $v['extend_params']['notice'] !!}
			</div>
			<div class="time">{{ App\Core\Tool::time2str($v['modified_time'],'auto') }}</div>
		</dd>
	</dl>
</hook-action>

<hook-action name="detail" args="detailList,notice,prevNotice,nextNotice">
<!--弹窗详情-->
	@include('notice_minitop')
	<!--# 
		Core::getLoginUser() = Core::getLoginUser();
		$blacklist = app('user.PwUserBlack')->getBlacklist(Core::getLoginUser()->uid);
		if (App\Core\Tool::inArray($notice['extend_params']['remindUid'],$blacklist)) {
		#-->
	<div class="tips" style="border-left:0;border-right:0;border-top:0;">已把{$notice['extend_params']['remindUsername']}列入黑名单，您不会再收到Ta的通知。</div>
	<!--# } #-->
	<div class="notice_segment_wrap">
		<dl class="notice_segment_list cc">
			<dt class="notice_tip_icon">
				<span class="icon_at" title="已读">[已读]</span>
			</dt>
			<dd class="notice_segment_cont">
				<div class="summary">
					<a href="{{ url('space/index/run?uid=' . $detailList['extend_params']['remindUid']) }}"  target="_blank">{{ $detailList['extend_params']['remindUsername'] }}</a> {!! $detailList['extend_params']['notice'] !!}
				</div>
				<div class="time">{{ App\Core\Tool::time2str($notice['modified_time'],'auto') }}</div>
			</dd>
		</dl>
	</div>
	<div class="my_message_bottom">
		<a href="{{ url('message/notice/run?type=' . $notice['typeid']) }}">查看全部提醒通知&nbsp;&gt;&gt;</a>
	</div>
</hook-action>

<hook-action name="list" args="v">
<!--页列表-->
	<div class="ct cc J_notice_item">
		<div class="check"><input name="ids[]" class="J_check" type="checkbox" value="{{ $v['id'] }}" style="display:none;"></div>
		<div class="content">
			<div class="title">
				<span class="notice_tip_icon">

@if (!$v['is_read'])

				<span class="icon_at_new" title="未读"></span>

@else

				<span class="icon_at" title="已读"></span>
				<!--# } #-->
				</span><a href="{{ url('space/index/run?uid=' . $v['extend_params']['remindUid']) }}" target="_blank">{{ $v['extend_params']['remindUsername'] }}</a> {!! $v['extend_params']['notice'] !!}
			</div>
			<div class="info">
				<span class="time">{{ App\Core\Tool::time2str($v['modified_time'],'auto') }}</span>
				<span class="operating">
					<span class="line">|</span>
					<a class="J_msg_del" href="#" data-uri="{{ url('message/notice/delete') }}" data-pdata="{'id':{{ $v['id'] }}}">删除</a>
					<!-- span class="line">|</span>
					<a class="J_addblack" data-type="notice" data-user="{{ $v['extend_params']['remindUsername'] }}" href="{{ url('message/message/addBlack?uid=' . $v['extend_params']['remindUid']) }}" data-referer="{{ url('profile/secret/black?_left=secret') }}">加入黑名单</a -->
				</span>
			</div>
		</div>
	</div>
</hook-action>