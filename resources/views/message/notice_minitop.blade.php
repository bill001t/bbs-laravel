<div class="my_message_top" id="J_hm_top">
	<span class="fr">
		<span>

@if ($prevNotice)

		<a class="J_hm_page" href="{{ url('message/notice/detail?id=' . $prevNotice['id']) }}">&lt;&nbsp;前一条</a>

@else

		&lt;&nbsp;前一条
		<!--# } #-->
		</span><i>|</i>

@if ($nextNotice)

		<a class="J_hm_page" href="{{ url('message/notice/detail?id=' . $nextNotice['id']) }}">后一条&nbsp;&gt;</a>

@else

		后一条&nbsp;&gt;
		<!--# } #-->
		</span>
	<a class="J_hm_back" href="{{ url('message/notice/minilist') }}">&lt;&lt;&nbsp;返回</a>
</div>