
<dl class="cc">
	<dt><a class="J_user_card_show" data-uid="{{ $uid }}" href="{{ url('space/index/run?uid=' . $uid) }}"><img src="{{ App\Core\Tool::getAvatar($uid, 'small') }}"  width="30" height="30" alt="{{ $username }}" class="J_avatar" data-type="small" /></a></dt>
	<dd>
		<p class="content"><a class="J_user_card_show" data-uid="{{ $uid }}" href="{{ url('space/index/run?uid=' . $uid) }}">{{ $username }}</a>：<em>{!! $content !!}</em><span>({{ App\Core\Tool::time2str($timestamp, 'auto') }})</span></p>
		<p class="repeat_btn"><a data-user="{{ $username }}" href="" class="J_feed_single">回复</a></p>
	</dd>
</dl>


@if ($fresh)
$new_replyattr = ' style="display:none;" id="J_fresh_floor"';
 #-->
@include('fresh_floor')
<!--# } #-->