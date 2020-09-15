<!--回复通知-->
<hook-action name="minilist" args="v">
<!--弹窗列表-->
	<!--#
			$ignoreString = $v['is_ignore'] ? '开启回复提醒' : '关闭回复提醒';
			$doIgnore = $v['is_ignore'] ? 0 : 1;
			if ($v['extend_params']['replyUser']) {
				$i = 0;
				foreach ($v['extend_params']['replyUser'] as $uid => $username) {
					$replyUser .= sprintf ('<a href="%s" target="_blank">%s</a> %s',WindUrlHelper::createUrl('space/index/run?uid='.$uid), $username, '、');
					$i++;
				}
				$replyUser = rtrim($replyUser, "、");
				$i > 1 && $replyUser = $replyUser . "等".$i."人";
			}
	#-->
	<dl class="notice_segment_list cc">
		<dt class="notice_tip_icon">

@if (!$v['is_read'])

			<span class="icon_system_new J_unread_icon" title="未读">[未读]</span>

@else

			<span class="icon_system" title="已读">[已读]</span>
			<!--# } #-->
		</dt>
		<dd class="notice_segment_cont">
			<div class="summary"> {!! $replyUser !!} 回复了您的主题帖《<a href="{{ url('bbs/read/jump?tid=' . $v['param'] . '&pid=' . $v['extend_params']['pid']) }}" target="_blank">{{ $v['extend_params']['threadTitle'] }}</a>》
			</div>
			<div class="time"><!--# echo App\Core\Tool::time2str($v['modified_time'],'auto');#--></div>
		</dd>
	</dl>
</hook-action>

<hook-action name="detail" args="detailList,notice,prevNotice,nextNotice">
<!--弹窗详情-->
<div style="">
	@include('notice_minitop')
	<!--#
			$ignoreString = $notice['is_ignore'] ? '开启回复提醒' : '关闭回复提醒';
			$tips = $notice['is_ignore'] ? '<div class="tips" style="border-left:0;border-right:0;border-top:0;">您不会再收到 通知</div>' : '';
			$doIgnore = $notice['is_ignore'] ? 0 : 1;
			if ($notice['extend_params']['replyUser']) {
				$i = 0;
				foreach ($notice['extend_params']['replyUser'] as $uid=>$username) {
					$replyUser .= sprintf ('<a href="%s" target="_blank">%s</a> %s',WindUrlHelper::createUrl('space/index/run?uid='.$uid), $username, '、');
					$i++;
				}
				$replyUser = rtrim($replyUser, "、");
				$i > 1 && $replyUser = $replyUser . "等".$i."人";
			}
	#--> {!! $tips !!}
	<div class="notice_segment_wrap">
		<dl class="notice_segment_list cc">
			<dt class="notice_tip_icon">
				<span class="icon_system" title="已读">[已读]</span>
			</dt>
			<dd class="notice_segment_cont">
				<div class="summary"> {!! $replyUser !!} 回复了您的主题帖《<a href="{{ url('bbs/read/jump?tid=' . $notice['param'] . '&pid=' . $notice['extend_params']['pid']) }}" target="_blank">{{ $notice['extend_params']['threadTitle'] }}</a>》
				</div>
				<div class="time"><!--# echo App\Core\Tool::time2str($notice['modified_time'],'auto');#--></div>
			</dd>
		</dl>
	</div>
	<div class="my_message_bottom">
	   <a href="{{ url('message/notice/ignore?id=' . $notice['id'] . '&ignore=' . $doIgnore) }}" data-id="{{ $notice['id'] }}" data-type="此帖的回复" data-ignore="{{ $doIgnore }}" data-tid="{{ $notice['extend_params']['postId'] }}" data-role="reply" class="fr J_notice_ignore">{{ $ignoreString }}</a>
		<a href="{{ url('message/notice/run?type=' . $notice['typeid']) }}">查看全部回复通知&nbsp;&gt;&gt;</a>
	</div>
</hook-action>

<hook-action name="list" args="v">
<!--页列表-->
	<!--#
		$ignoreString = $v['is_ignore'] ? '开启回复提醒' : '关闭回复提醒';
		$doIgnore = $v['is_ignore'] ? 0 : 1;
		$type = $v['is_ignore'] ? 'false' : 'true';
		$replyUser = '';
		if (is_array($v['extend_params']['replyUser'])) {
			$i = 0;
			foreach ($v['extend_params']['replyUser'] as $uid => $username) {
				$replyUser .= sprintf ('<a href="%s" target="_blank">%s</a> %s',WindUrlHelper::createUrl('space/index/run?uid='.$uid), $username, '、');
				$i++;
			}
			$replyUser = rtrim($replyUser, "、");
			$i > 1 && $replyUser = $replyUser . "等".$i."人";
			
		}
	#-->	
	<!--回复通知-->
	<div class="ct cc J_notice_item">
		<div class="check"><input name="ids[]" class="J_check" type="checkbox" value="{{ $v['id'] }}" style="display:none;"></div>
		<div class="content">
			
			<div class="title J_notice_part"><span class="notice_tip_icon">

@if (!$v['is_read'])

				<span class="icon_system_new" title="未读"></span>

@else

				<span class="icon_system" title="已读"></span>
				<!--# } #-->
				</span> {!! $replyUser !!} 回复了您的主题帖《<a href="{{ url('bbs/read/jump?tid=' . $v['param'] . '&pid=' . $v['extend_params']['pid']) }}" target="_blank">{{ $v['extend_params']['threadTitle'] }}</a>》
			</div>
			<div class="title J_notice_all" style="display:none;"></div>
			<div class="c"></div>
			<div class="info"><span class="time"><!--# echo App\Core\Tool::time2str($v['modified_time'],'auto');#--></span><span class="operating"><span class="line">|</span><a class="J_msg_del" data-uri="{{ url('message/notice/delete') }}" data-pdata="{'id':{{ $v['id'] }}}" href="#">删除</a><span class="line">|</span><a href="{{ url('message/notice/ignore?id=' . $v['id'] . '&ignore=' . $doIgnore) }}" data-id="{{ $v['id'] }}" data-type="{{ $type }}" data-role="reply" data-tid="{{ $v['extend_params']['threadId'] }}" class="J_notice_ignore">{{ $ignoreString }}</a></span></div>
		</div>
	</div>
</hook-action>

<hook-action name="detaillist" args="detailList,notice">
<!--页详情-->
<div style="">
	<!--#
			$ignoreString = $notice['is_ignore'] ? '开启回复提醒' : '关闭回复提醒';
			$doIgnore = $notice['is_ignore'] ? 0 : 1;
			if ($notice['extend_params']['uids']) {
				$i = 0;
				foreach ($detailList['replyUsers'] as $v) {
					$replyUser .= sprintf ('<a href="%s" target="_blank">%s</a> %s',WindUrlHelper::createUrl('space/index/run?uid='.$v['uid']), $v['username'], '、');
					$i++;
				}
				$replyUser = rtrim($replyUser,"、");
				$i > 1 && $replyUser = $replyUser . "等".$i."人";
			}
	#-->
			<p><span class="notice_tip_icon"><span class="icon_system" title="已读"></span></span>{!! $replyUser !!}&nbsp;回复了您的主题帖《<a href="{{ url('bbs/read/run?tid=' . $notice['param']) }}" target="_blank">{{ $notice['extend_params']['threadTitle'] }}</a>》</p>
</div>
</hook-action>