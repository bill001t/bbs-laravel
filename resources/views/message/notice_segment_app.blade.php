<!--应用通知页面-->
<hook-action name="minilist" args="v">
<!--弹窗列表-->
	<dl class="notice_segment_list cc">
		<dt class="notice_tip_icon">

@if (!$v['is_read'])

			<span class="icon_system_new J_unread_icon" title="未读">[未读]</span>

@else

			<span class="icon_system" title="已读">[已读]</span>
			<!--# } #-->
		</dt>
		<dd class="notice_segment_cont">
			<div class="summary"> {!! $v['title'] !!}
			</div>
			<div class="time"><!--# echo App\Core\Tool::time2str($v['modified_time'],'auto');#--></div>
		</dd>
	</dl>
</hook-action>

<hook-action name="detail" args="detailList,notice,prevNotice,nextNotice">
<!--弹窗详情-->
	@include('notice_minitop')
	<div class="notice_segment_wrap">
		<dl class="notice_segment_list cc">
			<dt class="notice_tip_icon">
				<span class="icon_system" title="已读">[已读]</span>
			</dt>
			<dd class="notice_segment_cont">
				<div class="summary"> {!! $notice['extend_params']['content'] !!}
				</div>
				<div class="time"><!--# echo App\Core\Tool::time2str($notice['modified_time'],'auto');#--></div>
			</dd>
		</dl>
	</div>
	<div class="my_message_bottom">
		<a href="{{ url('message/notice/run?type=' . $notice['typeid']) }}">查看全部默认通知&nbsp;&gt;&gt;</a>
	</div>
</hook-action>

<hook-action name="list" args="v">
<!--页列表-->
	<div class="ct cc J_notice_item">
		<div class="check"><input name="ids[]" class="J_check" type="checkbox" value="{{ $v['id'] }}" style="display:none;"></div>
		<div class="content">

@if ($v['title'] != $v['extend_params']['content'] && App\Core\Tool::strlen($v['extend_params']['content']) > 49)

			<a href="{{ url('message/notice/detaillist?id=' . $v['id']) }}" class="open_up J_notice_show" data-role="down">展开&darr;</a>
			<!--#
				} 
			 #-->
			<div class="title J_notice_part">
				<span class="notice_tip_icon">

@if (!$v['is_read'])

				<span class="icon_system_new" title="未读"></span>

@else

				<span class="icon_system" title="已读"></span>
				<!--# } #-->
				</span>{!! $v['title'] !!}
			</div>
			<div class="title J_notice_all" style="display:none;"></div>
			<div class="info">
				<span class="time"><!--# echo App\Core\Tool::time2str($v['modified_time'],'auto');#--></span>
				<span class="operating"><span class="line">|</span><a class="J_msg_del" data-pdata="{'id':{{ $v['id'] }}}" href="#" data-uri="{{ url('message/notice/delete') }}">删除</a></span>
			</div>
		</div>
	</div>
</hook-action>

<hook-action name="detaillist" args="detailList,notice">
<!--页详情-->
	<span class="notice_tip_icon">

@if (!$notice['is_read'])

	<span class="icon_system_new" title="未读"></span>

@else

	<span class="icon_system" title="已读"></span>
	<!--# } #-->
	</span>{!! $notice['extend_params']['content'] !!}
	<!-- div class="io"><span class="time"><!--# echo App\Core\Tool::time2str($notice['modified_time'],'auto');#--></span></div> -->
</hook-action>