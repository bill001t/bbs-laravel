
<dl class="cc">
	<dt><a href="{{ url('space/index/run?uid=' . Core::getLoginUser()->uid) }}"><img onerror="this.onerror=null;this.src={{ asset('assets/images') }}/face/face_small.jpg'" class="J_avatar" src="{{ App\Core\Tool::getAvatar(Core::getLoginUser()->uid, 'small') }}" data-type="small" width="30" height="30" /></a></dt>
	<dd>
		<p class="content"><a href="{{ url('space/index/run?uid=' . Core::getLoginUser()->uid) }}">{{ $username }}</a>：<em>{!! $content !!}</em><span>({{ App\Core\Tool::time2str($timestamp, 'auto') }})</span></p>
		<p class="repeat_btn"><a data-user="{{ $username }}" href="" class="J_feed_single">回复</a></p>
	</dd>
</dl>