<!doctype html>
<html>
<head>
    @include('common.head')
    <link href="{{ asset('assets/themes/site/default/css/dev/forum.css') }} " rel="stylesheet"/>
</head>
<body>

<div class="wrap">
    @include('common.header')
    <div class="main_wrap">
        <div class="bread_crumb" id="bread_crumb">
            <a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a
                    href="{{ url('bbs/forum/run') }}">本站新帖</a>
        </div>
        <div id="cloudwind_forum_top"></div>
        <div class="main cc">
            <div class="main_body">
                <div class="main_content cc">
                    {{-- <advertisement id='Thread.Top' sys='1'/> --}}
                    <div class="box_wrap thread_page J_check_wrap">
                        <nav>
                            <div class="content_nav" id="hashpos_ttype">
                                <div class="content_filter">
                                    <a href="{{ url('bbs/forum/run?order=postdate') }}"
                                       class="{{ App\Core\Tool::isCurrent($order == 'postdate') }}">最新发帖</a><span>|</span><a
                                            href="{{ url('bbs/forum/run?order=lastpost') }}"
                                            class="{{ App\Core\Tool::isCurrent($order != 'postdate') }}">最后回复</a></div>
                                <ul>
                                    <li class="current"><a href="{{ url('bbs/index/run?order=lastpost') }}">本站新帖</a>
                                    </li>
                                    @if (Core::getLoginUser()->isExists())
                                        <li><a rel="nofollow" href="{{ url('bbs/forum/my') }}">我的版块</a></li>
                                    @endif
                                </ul>
                            </div>
                        </nav>
                        <!--公告-->
                        {{-- <component tpl='TPL:announce.announce' class='SRV:announce.srv.PwAnnounceService' method='getAnnounceForBbsScroll' args='array()'/> --}}
                                <!--公告结束-->
                        @if ($threadList)
                            <div class="thread_posts_list">
                                <table width="100%" id="J_posts_list" summary="帖子列表">
                                    @foreach ($threadList as $key => $value)
                                        <tr>
                                            <td class="author"><a class="J_user_card_show"
                                                                  data-uid="{{ $value['created_userid'] }}"
                                                                  href="{{ url('space/index/run?uid=' . $value['created_userid']) }}"><img
                                                            src="{{ App\Core\Tool::getAvatar($value['created_userid'], 'small') }}"
                                                            onerror="this.onerror=null;this.src='{{ asset("assets/images/face/face_small.jpg") }}'"
                                                            width="45" height="45"
                                                            alt="{{ $value['created_username'] }}"/></a></td>
                                            <td class="subject">
                                                <p class="title">
                                                    @if ($operateThread)
                                                        <input class="J_check" name="" type="checkbox"
                                                               value="{{ $value['tid'] }}"/>
                                                    @endif
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
                                                       title="{{ $value['subject'] }}">{{ App\Core\Tool::substrs($value['subject'],$numofthreadtitle) }}</a>
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
                                                                aria-label="最后回复时间" rel="nofollow">
                                                            {{ App\Core\Tool::time2str($value['lastpost_time'], 'm-d H:i')}}</a></span>
                                                </p>
                                            </td>
                                            <td class="num">
                                                <span>回复<em>{{ $value['replies'] }}</em></span>
                                                <span>浏览<em>{{ $value['hits'] }}</em></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>
                            @if ($operateThread)
                                <div class="management J_post_manage_col" data-role="list">
                                    <label class="mr10"><input class="J_check_all" type="checkbox">全选</label>
                                    @if ($operateThread['topped'])
                                        <a href="{{ url('bbs/manage/topped') }}">置顶</a>
                                    @endif
                                    @if ($operateThread['digest'])
                                        <a href="{{ url('bbs/manage/digest') }}">精华</a>
                                    @endif
                                    @if ($operateThread['highlight'])
                                        <a href="{{ url('bbs/manage/highlight') }}">加亮</a>
                                    @endif
                                    @if ($operateThread['up'])
                                        <a href="{{ url('bbs/manage/up') }}">提前</a>
                                    @endif
                                    @if ($operateThread['type'])
                                        <a href="{{ url('bbs/manage/type') }}">分类</a>
                                    @endif
                                    @if ($operateThread['move'])
                                        <a href="{{ url('bbs/manage/move') }}">移动</a>
                                    @endif
                                    @if ($operateThread['copy'])
                                        <a href="{{ url('bbs/manage/copy') }}">复制</a>
                                    @endif
                                    @if ($operateThread['unite'])
                                        <a href="{{ url('bbs/manage/unite') }}">合并</a>
                                    @endif
                                    @if ($operateThread['lock'])
                                        <a href="{{ url('bbs/manage/lock') }}">锁定</a>
                                    @endif
                                    @if ($operateThread['down'])
                                        <a href="{{ url('bbs/manage/down') }}">压帖</a>
                                    @endif
                                    @if ($operateThread['delete'])
                                        <a href="{{ url('bbs/manage/delete') }}">删除</a>
                                    @endif
                                </div>
                            @endif
                        @else
                            <div class="not_content">
                                啊哦，本站暂没有任何内容哦！
                            </div>
                        @endif
                    </div>
                    <div class="J_page_wrap cc" data-key="true">
                        {{--<page tpl="TPL:common.page" page="$page" per="$perpage" count="$count" total="$totalpage"
                              url="bbs/forum/run" args="$urlargs"/>--}}
                    </div>
                    @if ($operateThread)
                        <div id="J_post_manage_main" class="core_pop_wrap J_post_manage_pop" style="display:none;">
                            <div class="core_pop">
                                <div style="width:415px;">
                                    <div class="pop_top"><a href="#" id="J_post_manage_close"
                                                            class="pop_close">关闭</a><strong>帖子操作</strong>(已选中&nbsp;<span
                                                class="red" id="J_post_checked_count">1</span>&nbsp;篇&nbsp;&nbsp;<a
                                                href="" class="s4" id="J_post_manage_checkall" data-type="check">全选</a>)
                                    </div>
                                    <div class="pop_cont">
                                        <div class="pop_operat_list">
                                            <ul class="cc">
                                                @if ($operateThread['topped'])
                                                    <li><a href="{{ url('bbs/manage/topped') }}">置顶</a></li>
                                                @endif
                                                @if ($operateThread['digest'])
                                                    <li><a href="{{ url('bbs/manage/digest') }}">精华</a></li>
                                                @endif
                                                @if ($operateThread['highlight'])
                                                    <li><a href="{{ url('bbs/manage/highlight') }}">加亮</a></li>
                                                @endif
                                                @if ($operateThread['up'])
                                                    <li><a href="{{ url('bbs/manage/up') }}">提前</a></li>
                                                @endif
                                                @if ($operateThread['type'])
                                                    <li><a href="{{ url('bbs/manage/type') }}">分类</a></li>
                                                @endif
                                                @if ($operateThread['move'])
                                                    <li><a href="{{ url('bbs/manage/move') }}">移动</a></li>
                                                @endif
                                                @if ($operateThread['copy'])
                                                    <li><a href="{{ url('bbs/manage/copy') }}">复制</a></li>
                                                @endif
                                                @if ($operateThread['unite'])
                                                    <li><a href="{{ url('bbs/manage/unite') }}">合并</a></li>
                                                @endif
                                                @if ($operateThread['lock'])
                                                    <li><a href="{{ url('bbs/manage/lock') }}">锁定</a></li>
                                                @endif
                                                @if ($operateThread['down'])
                                                    <li><a href="{{ url('bbs/manage/down') }}">压帖</a></li>
                                                @endif
                                                @if ($operateThread['delete'])
                                                    <li><a href="{{ url('bbs/manage/delete') }}">删除</a></li>
                                                @endif
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    {{-- <advertisement id='Thread.Btm' sys='1'/> --}}
                </div>
            </div>
            <div class="main_sidebar">
                {{--@include('common.sidebar_2')--}}
            </div>
        </div>
        <div id="cloudwind_forum_bottom"></div>
    </div>

    @include('common.footer')
</div>

<script>
    //var FID = '{{ ''/*$pwforum->fid*/ }}';
    Wind.use('jquery', 'global', function () {
        {{--@if(!$is_design)--}}
        @if (!isset($threadList))
        //无内容 发帖引导
        Wind.js(GV.JS_ROOT + 'pages/bbs/postGuide.js?v=' + GV.JS_VERSION);
        @elseif (Core::getLoginUser()->uid)
            Wind.js(GV.JS_ROOT + 'pages/bbs/threadManage.js?v=' + GV.JS_VERSION);
        @endif
        {{--@endif--}}
    });
</script>

</body>
</html>