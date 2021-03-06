<ul id="J_reply_ul_{{ $pid }}">
    @foreach ($replydb as $key => $read)
        <li>
            <a href="{{ url('space/index/run?uid=' . $read['created_userid']) }}" class="face" target="_blank"><img
                        src="{{ App\Core\Tool::getAvatar($read['created_userid']) }}"
                        onerror="this.onerror=null;this.src='{{ asset(\'assets/images/face/face_small.jpg\') }}'"
                        width="30" height="30" alt="{{ $read['created_username'] }}"></a>
            <div class="reply_content">
                <a class="J_user_card_show" data-uid="{{ $read['created_userid'] }}"
                   href="{{ url('space/index/run?uid=' . $read['created_userid']) }}">{{ $read['created_username'] }}</a>：{!! $read['content'] !!}
                <div>
                    <span class="operate"><a href="{{ url('report/index/report?type=post&type_id=' . $read['pid']) }}"
                                             class="J_report J_qlogin_trigger report">举报</a><a
                                href="{{ url('bbs/read/jump?tid=' . $tid . '&pid=' . $read['pid']) }}">查看</a><a href=""
                                                                                                                class="J_read_reply_single"
                                                                                                                data-username="{{ $read['created_username'] }}">回复</a></span>
                    <span class="time">{{ App\Core\Tool::time2str($read['created_time'], 'auto') }}</span>
                </div>
            </div>
        </li>
    @endforeach
</ul>
<div class="J_pages_wrap">
    {{--<page tpl="TPL:common.page" page="$page" per="$perpage" count="$count" url="bbs/post/replylist?tid=$tid&pid=$pid"/>--}}
</div>