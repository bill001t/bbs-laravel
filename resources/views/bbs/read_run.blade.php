<!doctype html>
<html>
<head>
    @include('common.head')
    <link href="{{ asset('assets/themes/site/default/css/dev/forum.css') }} " rel="stylesheet"/>
    <link href="{{ asset('assets/themes/site/default/css/dev') }}/editor_content.css" rel="stylesheet"/>
    <style>
        .aPre {
            cursor: url('{{ asset('assets/images/common/pre.cur') }}'), auto;
        }

        .aNext {
            cursor: url('{{ asset('assets/images/common/next.cur') }}'), auto;
            right: 0;
        }
    </style>
</head>
<body>

<div class="wrap">
    {{-- @include('common.header') --}}
    <div class="main_wrap">

        <div class="bread_crumb"> {!! $headguide !!}
        </div>

        <div id="app_test"></div>
        <div id="cloudwind_read_top"></div>
        <div class="read_pages_wrap cc">

            @if($showReply)
                <a rel="nofollow" href="{{ url('bbs/post/reply?tid=' . $tid) }}" data-referer="true"
                   class="btn_replay{{ $replyNeedLogin }}">回复</a>
            @endif
            <div class="pages" style="margin-right:3px;"><a
                        href="{{ url('bbs/thread/run?fid=' . $pwforum->fid . '&page=' . $fpage) }}"
                        class="pages_pre" rel="nofollow">&laquo; 返回列表</a></div>
            {{--<page tpl="TPL:common.page" page="$page" per="$perpage" count="$count" total="$totalpage"
                  url="bbs/read/run?tid=$tid&fid=$fid" args="$urlargs"/>--}}
        </div>
        <input type="hidden" id="js-tid" value="$tid"/>
        <div class="read_page" id="J_posts_list">

            @if ($operateThread || $designPermission > 0)
                <div class="read_management cc J_post_manage_col" data-role="readbar">
                    <?php
                        $hasFirstPart = $operateThread['topped'] || $operateThread['digest'] || $operateThread['highlight'] || $operateThread['up'];
                        $hasSecondPart = $operateThread['type'] || $operateThread['print'] || $operateThread['move'] || $operateThread['copy'] || $operateThread['unite'];
                        $hasThirdPart = $operateThread['lock'] || $operateThread['down'] || $operateThread['delete'];
                    ?>

                    @if ($operateThread['topped'])
                        <a data-type="norefresh" href="{{ url('bbs/manage/topped') }}" class="">置顶</a>
                    @endif
                    @if ($operateThread['digest'])
                        <a href="{{ url('bbs/manage/digest') }}" class="">精华</a>
                    @endif
                    @if ($operateThread['highlight'])
                        <a href="{{ url('bbs/manage/highlight') }}" class="">加亮</a>
                    @endif
                    @if ($operateThread['up'])
                        <a href="{{ url('bbs/manage/up') }}" class="">提前</a>
                    @endif
                    @if ($hasFirstPart && $hasSecondPart)
                        <i>|</i>
                    @endif
                    @if ($operateThread['type'])
                        <a href="{{ url('bbs/manage/type') }}" class="">分类</a>
                    @endif
                    @if ($operateThread['print'])
                        <a href="{{ url('bbs/manage/unite') }}" class="">印戳</a>
                    @endif
                    @if ($operateThread['move'])
                        <a href="{{ url('bbs/manage/move') }}" class="">移动</a>
                    @endif
                    @if ($operateThread['copy'])
                        <a href="{{ url('bbs/manage/copy') }}" class="">复制</a>
                    @endif
                    @if ($operateThread['unite'])
                        <a href="{{ url('bbs/manage/unite') }}" class="">合并</a>
                    @endif
                    @if ($hasThirdPart && ($hasFirstPart ^ $hasSecondPart || $hasFirstPart && $hasSecondPart))
                        <i>|</i>
                    @endif
                    @if ($operateThread['lock'])
                        <a href="{{ url('bbs/manage/lock') }}" class="">锁定</a>
                    @endif
                    @if ($operateThread['down'])
                        <a href="{{ url('bbs/manage/down') }}" class="">压帖</a>
                    @endif
                    @if ($operateThread['delete'])
                        <a href="{{ url('bbs/masingle/delete') }}">删除</a>
                    @endif
                    @if ($designPermission > 0)
                        @if ($hasFirstPart || $hasSecondPart || $hasThirdPart)
                            <i>|</i>
                        @endif
                        <a data-type="norefresh" href="{{ url('design/push/add?fromtype=thread&fromid=' . $tid) }}"
                           class="" title="推送">推送</a>
                        <!--这玩意交互改下class="mr10 J_manage_single" id="J_push_trigger"  以前的推送class="J_read_push" -->
                    @endif
                </div>
            @endif

            @foreach ($readdb as $key => $read)
                <a name="{{ $read['pid'] }}"></a>
                @if ($read['lou'] == $count-1)
                    <a name="a"></a> @endif
                @include('read_floor')
                {{-- <advertisement id='Read.Layer.TidAmong' sys='1'/> --}}
            @endforeach
            {{-- <page tpl="TPL:common.page_vertical" page="$page" per="$perpage" count="$count"
                   total="$totalpage" url="bbs/read/run?tid=$tid&fid=$fid" args="$urlargs"/>--}}
            <div class="read_pages_wrap cc" id="floor_reply">
                <a rel="nofollow" href="{{ url('bbs/post/run?fid=' . $pwforum->fid) }}" id="J_read_post_btn"
                   class="btn_post{{ $postNeedLogin }}">发帖</a>
                <!-- 锁定时间 -->

                @if($showReply)
                    <a rel="nofollow" href="{{ url('bbs/post/reply?tid=' . $tid) }}" data-referer="true"
                       class="btn_replay{{ $replyNeedLogin }}">回复</a>
                @endif
                <div class="J_page_wrap" data-key="true">
                    <div class="pages" style="margin-right:3px;"><a
                                href="{{ url('bbs/thread/run?fid=' . $pwforum->fid . '&page=' . $fpage) }}"
                                class="pages_pre" rel="nofollow">&laquo; 返回列表</a></div>
                    {{--<page tpl="TPL:common.page" page="$page" per="$perpage" count="$count"
                          total="$totalpage" url="bbs/read/run?tid=$tid&fid=$fid" args="$urlargs"/>--}}
                </div>
            </div>
            <div style="display:none;" class="btn_post_menu" id="J_read_post_types">
                <ul>
                    @foreach ($pwforum->getThreadType(Core::getLoginUser()) as $key => $value)
                            <!--# $_urladd_ = ($key != 'default') ? ('&special=' . $key) : ''; ?>
                    <li><a href="{{ url('bbs/post/run?fid=' . $pwforum->fid) }}{{ $_urladd_ }}"
                           data-referer="true" class="{{ @trim($postNeedLogin) }}">{{ $value[0] }}</a></li>
                    @endforeach
                </ul>
            </div>

            @if ($showReply)
                    <!--快速回复-->
            <div class="floor cc">
                <table width="100%" style="table-layout:fixed;" class="floor_table">
                    <tr>
                        <td class="floor_left">
                            <div class="floor_info">
                                <img class="J_avatar" data-type="middle"
                                     src="{{ App\Core\Tool::getAvatar(Core::getLoginUser()->uid,'middle') }}"
                                     alt="{{ Core::getLoginUser()->username }}"/>
                            </div>
                        </td>
                        <td class="floor_reply box_wrap">
                            <div class="fl">
                                <div class="floor_arrow"><em></em><span></span></div>
                            </div>
                            @if (!Core::getLoginUser()->isExists())
                                <div class="reply_login_tips">
                                    您需要登录后才可以回帖，<a href="#floor_reply" rel="nofollow"
                                                   class="J_qlogin_trigger">登录</a>&nbsp;或者&nbsp;<a
                                            rel="nofollow" href="{{ url('u/register/run') }}">注册</a>
                                </div>
                            @else
                                <div class="reply_toolbar_wrap">
                                    <div class="reply_toolbar cc">
                                        <a href="{{ url('bbs/post/reply?tid=' . $tid) }}"
                                           class="reply_high">进入高级模式&gt;&gt;</a>
                                        <a href="" style="display:;" tabindex="-1" rel="nofollow"
                                           class="icon_face J_insert_emotions"
                                           data-emotiontarget="#J_reply_quick_ta">表情</a>
                                    </div>
                                                <textarea name="atc_content" aria-label="快速回复" id="J_reply_quick_ta"
                                                          class="J_at_user_textarea" placeholder="我也说两句"></textarea>
                                </div>
                                <div class="J_reply_ft" id="J_reply_ft">
                                    <button type="submit" data-tid="{{ $tid }}"
                                            data-action="{{ url('bbs/post/doreply?_getHtml=1') }}"
                                            class="btn btn_submit disabled" disabled="disabled"
                                            id="J_reply_quick_btn">回复
                                    </button>
                                </div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <!--快速回复结束-->
            @endif

            <div id="cloudwind_read_bottom"></div>
        </div>

        @if ($operateReply)
            <div id="J_post_manage_main" class="core_pop_wrap J_post_manage_pop"
                 style="display:none;position:fixed;_position:absolute;">
                <div class="core_pop">
                    <div style="width:250px;">
                        <div class="pop_top"><a href="#" id="J_post_manage_close"
                                                class="pop_close">关闭</a><strong>帖子操作</strong>(已选中&nbsp;<span class="red"
                                                                                                             id="J_post_checked_count">1</span>&nbsp;篇&nbsp;&nbsp;<a
                                    href="" class="s4" id="J_post_manage_checkall" data-type="check">全选</a>)
                        </div>
                        <div class="pop_cont">
                            <div class="pop_operat_list">
                                <ul class="cc J_post_manage_col" data-role="read">
                                    @if ($operateReply['delete'])
                                        <li><a data-type="delete" href="{{ url('bbs/masingle/delete') }}">删除</a></li>
                                    @endif
                                    @if ($operateReply['unite'])
                                        <li><a href="{{ url('bbs/masingle/unite') }}">合并</a></li>
                                    @endif
                                    @if ($operateReply['split'])
                                        <li><a href="{{ url('bbs/masingle/split') }}">拆分</a></li>
                                    @endif
                                    @if ($operateReply['shield'])
                                        <li><a href="{{ url('bbs/masingle/shield') }}">屏蔽</a></li>
                                    @endif
                                    @if ($operateReply['remind'])
                                        <li><a href="{{ url('bbs/masingle/remind') }}">提醒</a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    {{--  @include('common.footer') --}}
</div>

<textarea id="J_like_user_ta" class="dn">
	<div id="" class="core_pop_wrap" style="position:absolute;">
        <div class="core_pop">
            <div class="floot_like_pop">
                <div class="pop_top"><a href="#" class="pop_close J_like_user_close">关闭</a>最新喜欢</div>
                <div class="pop_cont">
                    <ul class="cc J_like_user_list"></ul>
                </div>
            </div>
        </div>
    </div>
</textarea>

<script>
    var TID = '{{ $tid }}';
    Wind.use('jquery', 'global', 'dialog', function () {
        @if(!$is_design)
        @if (Core::getLoginUser()->isExists())
        //已登录
        //管理操作
        Wind.js(GV.JS_ROOT + 'pages/bbs/threadManage.js?v=' + GV.JS_VERSION);

        $('a.J_read_mark').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            $('body').trigger('setCustomPost', [$this]);
            $.post($this.data('uri'), function (data) {
                Wind.Util.resultTip({
                    error: (data.state == 'success' ? false : true),
                    elem: $this,
                    follow: true,
                    msg: data.message[0]
                });
            }, 'json');
        });

        //加关注
        $('a.J_read_follow').on('click', function (e) {
            e.preventDefault();
            var $this = $(this);
            Wind.Util.ajaxMaskShow();
            $.post(this.href, {
                uid: $this.data('uid')
            }, function (data) {
                Wind.Util.ajaxMaskRemove();
                if (data.state == 'success') {
                    $this.removeClass('follow').addClass('unfollow').text('已关注');
                    Wind.Util.resultTip({
                        msg: data.message[0],
                        follow: $this
                    });

                    $('#J_user_card_' + $this.data('uid')).remove();
                } else if (data.state == 'fail') {
                    Wind.Util.resultTip({
                        error: true,
                        msg: data.message[0],
                        follow: $this
                    });
                }
            }, 'json');
        });


        @if ($operateReply['toppedreply'] && $read['lou'])
        //帖内置顶
        (function () {
            var top_lock = false;
            $('a.J_post_top').on('click', function (e) {
                e.preventDefault();
                var $this = $(this),
                        topped = $this.data('topped');

                if (top_lock) {
                    return false;
                }
                top_lock = true;
                $('body').trigger('setCustomPost', [$this]);
                $.post($this.data('uri'), function (data) {
                    if (data.state == 'success') {
                        Wind.Util.resultTip({
                            follow: $this,
                            msg: data.message[0],
                            callback: function () {
                                location.reload();
                            }
                        });
                    } else if (data.state == 'fail') {
                        Wind.Util.resultTip({
                            error: true,
                            follow: $this,
                            msg: data.message[0]
                        });
                        top_lock = false;
                    }
                }, 'json');
            })
        })();
        @endif
        @endif


        // 阅读页的常用交互
        Wind.js(GV.JS_ROOT + 'pages/bbs/read.js?v=' + GV.JS_VERSION);

        // 投票帖
        if ($('ul.J_vote_item, a.J_vote_list_show').length) {
            Wind.js(GV.JS_ROOT + 'pages/bbs/readVote.js?v=' + GV.JS_VERSION);
        }

        // 购买记录
        if ($('#J_content_sell, #J_attach_buy, a.J_buy_record, .J_attach_post_del, a.J_attach_post_buy').length) {
            Wind.js(GV.JS_ROOT + 'pages/bbs/buyRecords.js?v=' + GV.JS_VERSION);
        }

        //媒体播放
        if ($('div.J_audio,div.J_video').length) {
            Wind.js(window.GV.JS_ROOT + 'pages/bbs/media_play.js?v=' + GV.JS_VERSION);
        }



        @if(false != Core::C('bbs', 'read.image_lazy'))
        // 图片懒加载
        Wind.js(GV.JS_ROOT + 'util_libs/lazyload.js?v=' + GV.JS_VERSION, function () {
            $("img.J_lazy").lazyload({
                effect: 'fadeIn',
                error: function (settings) {
                    $(this).attr("src", '#').removeClass("J_lazy")
                }
            });
        });
        @endif
        @endif
    });
</script>

{{-- <hook class='$threadDisplay' name="runJs" /> --}}

</body>
</html>
