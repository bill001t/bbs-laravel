@if ($tpc_topped && !isset($value['issort']))
    <tr>
        <td colspan="3" class="tac ordinary">普通主题</td>
    </tr>
@endif

<tr>
    <td class="author"><a class="J_user_card_show" data-uid="{{ $value['created_userid'] }}"
                          href="{{ url('space/index/run?uid=' . $value['created_userid']) }}"><img class="J_avatar"
                                                                                                   src="{{ App\Core\Tool::getAvatar($value['created_userid'], 'small') }}"
                                                                                                   data-type="small"
                                                                                                   width="45"
                                                                                                   height="45"
                                                                                                   alt="{{ $value['created_username'] }}"/></a>
    </td>
    <td class="subject">
        <p class="title">
            @if ($operateThread)
                <input class="J_check" name="" type="checkbox" value="{{ $value['tid'] }}" autocomplete="off"/>
            @endif
            <a href="{{ url('bbs/read/run?tid=' . $value['tid'] . '&fid=' . $value['fid']) }}"
               target="_blank"><span class="posts_icon"><i class="icon_{{ $value['icon'] }}"
                                                           title="{{ $icon[$value['icon']] }}  新窗口打开"></i></span></a>

            @if ($value['topic_type'] && $pwforum->forumset['topic_type_display'] && isset($topictypes['all_types'][$value['topic_type']]))
                @if ($_parentid = $topictypes['all_types'][$value['topic_type']]['parentid'])
                    <a href="{{ url('bbs/thread/run?fid=' . $pwforum->fid . '&type=' . $_parentid) }}"
                       class="st">[{!! $topictypes['all_types'][$_parentid]['name'] !!}]</a>
                @endif
                <a href="{{ url('bbs/thread/run?fid=' . $pwforum->fid . '&type=' . $value['topic_type']) }}"
                   class="st">[{!! $topictypes['all_types'][$value['topic_type']]['name'] !!}]</a>
            @endif

            <a href="{{ url('bbs/read/run?tid=' . $value['tid'] . '&fid=' . $value['fid']) }}"
               class="st" style="{{ $value['highlight_style'] }}"
               title="{{ $value['subject'] }}">{{ App\Core\Tool::substrs($value['subject'],$numofthreadtitle) }}</a>
            {{-- <hook class='$threadList' name='createHtmlAfterSubject' args="array($value)" /> --}}

            <?php
            if ($value['inspect']) {
                $value['inspect'][0] = ($value['inspect'][0]) == 0 ? '主' : $value['inspect'][0];
            }
            ?>

            {{--<span class="red">[{{$value['inspect'][1]}}阅至{{$value['inspect'][0]}}楼]</span>--}}
            @if ($value['ifupload'])
                <span class="posts_icon"><i class="icon_{{ $uploadIcon[$value['ifupload']] }}"
                                            title="{{ $icon[$uploadIcon[$value['ifupload']]] }}"></i></span>
            @endif
        </p>
        <p class="info">
            楼主：<a class="J_user_card_show" data-uid="{{ $value['created_userid'] }}"
                  href="{{ url('space/index/run?uid=' . $value['created_userid']) }}">{{ $value['created_username'] }}</a><span>{{ App\Core\Tool::time2str($value['created_time'], 'Y-m-d') }}</span>
            最后回复：<a class="J_user_card_show" data-uid="{{ $value['lastpost_userid'] }}"
                    href="{{ url('space/index/run?uid=' . $value['lastpost_userid']) }}">{{ $value['lastpost_username'] }}</a><span><a
                        rel="nofollow"
                        href="{{ url('bbs/read/run?tid=' . $value['tid'] . '&fid=' . $value['fid'] . '&page=e') }}#a">{{ App\Core\Tool::time2str($value['lastpost_time'],'m-d H:i') }}</a></span>
        </p>
    </td>
    <td class="num">
        <span>回复<em>{{ $value['replies'] }}</em></span>
        <span>浏览<em>{{ $value['hits'] }}</em></span>
    </td>
</tr>
<?php $tpc_topped = isset($value['issort']); ?>