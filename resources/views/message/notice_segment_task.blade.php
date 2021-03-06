<!--任务-->
<hook-action name="minilist" args="v">
<!--弹窗列表-->
	<dl class="notice_segment_list cc">
		<dt class="notice_tip_icon">

@if (!$v['is_read'])

			<span class="icon_task_new J_unread_icon" title="未读">[未读]</span>

@else

			<span class="icon_task" title="已读">[已读]</span>
			<!--# } #-->
		</dt>
		<dd class="notice_segment_cont">
			<div class="summary">

@if ($v['extend_params']['complete'])

				恭喜！您有新的任务奖励可领取《{{ App\Core\Tool::substrs($v['extend_params']['title'],16) }}》 ，马上前往<a href="{{ url('task/index/run') }}" style="margin-right:0px">任务中心</a>领取奖励。

@else

				恭喜您成功领取任务《{{ App\Core\Tool::substrs($v['extend_params']['title'],16) }}》 ，现在就去<a href="{{ url('task/index/run') }}" style="margin-right:0px">任务中心</a>做任务！
		<!--#}#-->
			</div>
			<div class="time">{{ App\Core\Tool::time2str($v['modified_time'],'auto') }}</div>
		</dd>
	</dl>
</hook-action>

<hook-action name="detail" args="detailList,notice,prevNotice,nextNotice">
<!--弹窗详情-->
	@include('notice_minitop')
	<div class="notice_segment_wrap">
		<dl class="notice_segment_list cc">
			<dt class="notice_tip_icon">
				<span class="icon_task" title="已读">[已读]</span>
			</dt>
			<dd class="notice_segment_cont">
				<div class="summary">
	<!--#
	$item = $detailList['extend_params'];
	//$ignoreString = $detailList['is_ignore'] ? '取消忽略' : '忽略';
	//$doIgnore = $detailList['is_ignore'] ? 0 : 1;
	//$tips = $detailList['is_ignore'] ? '<div class="tips">您不会再收到 任务 通知</div>' : '';
	#-->

@if ($item['complete'])

				恭喜！您有新的任务奖励可领取《{$item['title']}》 ，马上前往<a href="{{ url('task/index/run') }}" style="margin-right:0px">任务中心</a>领取奖励。

@else

				恭喜您成功领取任务《{$item['title']}》 ，现在就去<a href="{{ url('task/index/run') }}" style="margin-right:0px">任务中心</a>做任务！
	<!--#}#-->
				</div>
				<div class="time"><!--# echo App\Core\Tool::time2str($notice['modified_time'],'auto');#--></div>
			</dd>
		</dl>
	</div>
	<div class="my_message_bottom">
		<a href="{{ url('message/notice/run?type=' . $notice['typeid']) }}">查看全部任务通知&nbsp;&gt;&gt;</a>
	</div>
</hook-action>

<hook-action name="list" args="v">
<!--页列表-->
<!--#
	//	$ignoreString = $v['is_ignore'] ? '取消忽略' : '忽略';
	//	$doIgnore = $v['is_ignore'] ? 0 : 1;
#-->
	<div class="ct cc J_notice_item">
		<div class="check"><input name="ids[]" class="J_check" type="checkbox" value="{{ $v['id'] }}" style="display:none;"></div>
		<div class="content">
			<div class="title">
				<span class="notice_tip_icon">

@if (!$v['is_read'])

				<span class="icon_task_new" title="未读"></span>

@else

				<span class="icon_task" title="已读"></span>
				<!--# } #-->
				</span>

@if ($v['extend_params']['complete'])

				恭喜！您有新的任务奖励可领取《{$v['extend_params']['title']}》 ，马上前往<a href="{{ url('task/index/run') }}" style="margin-right:0px">任务中心</a>领取奖励。

@else

				恭喜您成功领取任务《{$v['extend_params']['title']}》 ，现在就去<a href="{{ url('task/index/run') }}" style="margin-right:0px">任务中心</a>做任务！
		<!--#}#-->
			</div>
			<div class="info"><span class="time">{{ App\Core\Tool::time2str($v['modified_time'],'auto') }}</span><span class="operating"><span class="line">|</span><a class="J_msg_del" data-uri="{{ url('message/notice/delete') }}" data-pdata="{'id':{{ $v['id'] }}}" href="#">删除</a></span></div>
		</div>
	</div>
</hook-action>