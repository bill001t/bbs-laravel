<!doctype html>
<html>
<head>
    @include('common.head')
    <link href="{{ asset('assets/themes/site/default/css/dev/forum.css') }} " rel="stylesheet"/>
</head>
<body>

<div class="wrap">
    {{--{{-- @include('common.header') --}}--}}
    <div class="main_wrap">
        <div class="bread_crumb" id="bread_crumb">
            <a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a
                    href="{{ url('bbs/forum/my') }}">我的版块</a>
        </div>
        <div class="main cc">
            <div class="main_body">
                <div class="main_content cc">
                    {{-- <advertisement id='Thread.Top' sys='1'/> --}}
                    <div class="box_wrap thread_page">
                        <nav>
                            <div class="content_nav" id="hashpos_ttype">
                                <div class="content_filter">
                                    <a href="{{ url('bbs/forum/my?order=postdate') }}"
                                       class="{{ App\Core\Tool::isCurrent($order == 'postdate') }}">最新发帖</a><span>|</span><a
                                            href="{{ url('bbs/forum/my') }}"
                                            class="{{ App\Core\Tool::isCurrent($order != 'postdate') }}">最后回复</a></div>
                                <ul>
                                    <li><a href="{{ url('bbs/index/run') }}">本站新帖</a></li>
                                    <li class="current"><a rel="nofollow" href="{{ url('bbs/forum/my') }}">我的版块</a></li>
                                </ul>
                            </div>
                        </nav>

                        <div class="thread_posts_list">
                            @if ($threadList)
                                <table width="100%" id="J_posts_list" summary="帖子列表">
                                    @foreach ($threadList as $key => $value)
                                        <tr>
                                            <td class="author"><a class="J_user_card_show"
                                                                  data-uid="{{ $value['created_userid'] }}"
                                                                  href="{{ url('space/index/run?uid=' . $value['created_userid']) }}"><img
                                                            src="{{ App\Core\Tool::getAvatar($value['created_userid'], 'small') }}"
                                                            onerror="this.onerror=null;this.src='{{ asset("assets/images/face/face_small.jpg") }}'"
                                                            width="45" height="45"
                                                            alt="{{ $value['created_username'] }}的头像"/></a></td>
                                            <td class="subject">
                                                <p class="title">
                                                    <a href="{{ url('bbs/read/run?tid=' . $value['tid'] . '&fid=' . $value['fid']) }}"
                                                       target="_blank"><span class="posts_icon"><i
                                                                    class="icon_{{ $value['icon'] }}"
                                                                    title="{{ $icon[$value['icon']] }}  新窗口打开"></i></span></a>
                                                    @if ($forums[$value['fid']])
                                                        <a href="{{ url('bbs/thread/run?fid=' . $value['fid']) }}"
                                                           class="st">[{!! $forums[$value['fid']]['name'] !!}]</a>
                                                    @endif
                                                    <a href="{{ url('bbs/read/run?tid=' . $value['tid'] . '&fid=' . $value['fid']) }}"
                                                       class="st" style="{{ $value['highlight_style'] }}"
                                                       title="{{ $value['subject'] }}">{{ App\Core\Tool::substrs($value['subject'],26) }}</a>
                                                    @if ($value['ifupload'])
                                                        <span class="posts_icon"><i
                                                                    class="icon_{{ $uploadIcon[$value['ifupload']] }}"
                                                                    title="{{ $icon[$uploadIcon[$value['ifupload']]] }}"></i></span>
                                                    @endif
                                                </p>
                                                <p class="info">
                                                    楼主：<a class="J_user_card_show"
                                                          data-uid="{{ $value['created_userid'] }}"
                                                          href="{{ url('space/index/run?uid=' . $value['created_userid']) }}">{{ $value['created_username'] }}</a><span><!--# echo App\Core\Tool::time2str($value['created_time'], 'Y-m-d');#--></span>
                                                    最后回复：<a class="J_user_card_show"
                                                            data-uid="{{ $value['lastpost_userid'] }}"
                                                            href="{{ url('space/index/run?uid=' . $value['lastpost_userid']) }}">{{ $value['lastpost_username'] }}</a><span><a
                                                                href="{{ url('bbs/read/run?tid=' . $value['tid'] . '&fid=' . $value['fid'] . '&page=e') }}#a"
                                                                rel="nofollow">
                                                            {{ App\Core\Tool::time2str($value['lastpost_time'], 'm-d H:i') }}</a></span>
                                                </p>
                                            </td>
                                            <td class="num">
                                                <span>回复<em>{{ $value['replies'] }}</em></span>
                                                <span>浏览<em>{{ $value['hits'] }}</em></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            @else
                                <div class="not_followforum">
                                    还没有内容？点击加入，轻松获取版块最新帖子
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="J_page_wrap cc" data-key="true">
                        {{--<page tpl="TPL:common.page" page="$page" per="$perpage" count="$count" total="$totalpage"  url="bbs/forum/my" args="$urlargs"/>--}}
                    </div>
                    {{-- <advertisement id='Thread.Btm' sys='1'/> --}}
                </div>
            </div>

            <div class="main_sidebar">
                @include('common.sidebar_2')
            </div>

        </div>
    </div>
    {{--  @include('common.footer') --}}
</div>

<script>
    Wind.use('jquery', 'global');
</script>

</body>
</html>