<!doctype html>
<html>
<head>
    @include('common.head')
    <link href="{{ asset('assets/themes/site/default/css/dev/forum.css') }} " rel="stylesheet"/>
</head>
<body>

<div class="wrap">
    {{-- @include('common.header') --}}
    <div class="main_wrap">
        <div class="bread_crumb"> {!! $headguide !!}<em>&gt;</em><a href="{{ url('bbs/user/run?fid=' . $fid) }}">会员</a>
        </div>

        <div class="main cc">
            <div class="main_body">

                <div class="main_content">
                    @include('widget_forum')
                    <div class="mb10 cc">
                        <a href="{{ url('bbs/post/run?fid=' . $pwforum->fid) }}"
                           class="btn_post J_thread_post_btn{{ $postNeedLogin }}"
                           data-rel="J_thread_post_types_1">发帖</a>
                        @include('widget_thread_page')
                    </div>
                    <!--需要js定位-->
                    <div id="J_thread_post_types_1" class="btn_post_menu"
                         style="display:none;left:50%;margin:-11px 0 0 120px;">
                        <ul>
                            <?php
                            foreach (($threadType = $pwforum->getThreadType(Core::getLoginUser())) as $key => $value){
                            $_urladd_ = ($key != 'default') ? ('&special=' . $key) : '';
                            ?>
                            <li><a href="{{ url('bbs/post/run?fid=' . $pwforum->fid) }}{{ $_urladd_ }}"
                                   data-referer="true" class="{{ @trim($postNeedLogin) }}">{{ $value[0] }}</a></li>
                            <?php }?>
                        </ul>
                    </div>

                    <div class="box_wrap thread_page">
                        <nav>
                            <div class="content_nav" id="hashpos_ttype">
                                <ul class="cc">
                                    <li><a href="{{ url('bbs/thread/run?fid=' . $fid) }}">全部</a></li>
                                    <li><a href="{{ url('bbs/thread/run?fid=' . $fid . '&tab=digest') }}">精华</a></li>
                                    <li class="current"><a href="{{ url('bbs/user/run?fid=' . $fid) }}">会员</a></li>
                                </ul>
                            </div>
                        </nav>

                        <div class="thread_uesr_list">
                            <h2><span>共<em id="J_user_count">{{ $totalJoin }}</em>个会员</span>最新加入本版块的会员</h2>
                            @if ($joinUser)
                                <ul class="cc" id="J_add_list">
                                    @foreach ($joinUser as $key => $value)
                                        <li id="J_user_{{ $key }}">
                                            <a class="J_user_card_show" data-uid="{{ $key }}"
                                               href="{{ url('space/index/run?uid=' . $key) }}"><img class="J_avatar"
                                                                                                    src="{{ App\Core\Tool::getAvatar($key, 'middle') }}"
                                                                                                    data-type="middle"
                                                                                                    width="90"
                                                                                                    height="90"
                                                                                                    alt="{{ $users[$key]['username'] }}"/></a>
                                            <p><a class="J_user_card_show" data-uid="{{ $key }}"
                                                  href="{{ url('space/index/run?uid=' . $key) }}">{{ $users[$key]['username'] }}</a>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                                <div id="J_add_none" class="not_content" style="display:none;">啊哦，暂没有会员加入该版块哦，我要<a
                                            class="J_qlogin_trigger" id="J_forum_join_trigger" target="_blank"
                                            href="{{ url('bbs/forum/join?fid=' . $fid) }}">加入</a>！
                                </div>
                            @else
                                <ul class="cc" id="J_add_list" style="display:none;"></ul>
                                <div id="J_add_none" class="not_content">啊哦，暂没有会员加入该版块哦，我要<a id="J_forum_join_trigger"
                                                                                             class="J_qlogin_trigger"
                                                                                             target="_blank"
                                                                                             href="{{ url('bbs/forum/join?fid=' . $fid) }}">加入</a>！
                                </div>
                            @endif
                            <h2><span>前{@count($activeUser)}名</span>本周最活跃用户</h2>
                            @if ($activeUser)
                                <ul class="cc">
                                    @foreach ($activeUser as $key => $value)
                                        if (!isset($users[$key])) continue;?>
                                        <li>
                                            <a class="J_user_card_show" data-uid="{{ $key }}"
                                               href="{{ url('space/index/run?uid=' . $key) }}"><img class="J_avatar"
                                                                                                    src="{{ App\Core\Tool::getAvatar($key, 'middle') }}"
                                                                                                    data-type="middle"
                                                                                                    width="90"
                                                                                                    height="90"
                                                                                                    alt="{{ $users[$key]['username'] }}"/></a>
                                            <p><a class="J_user_card_show" data-uid="{{ $key }}"
                                                  href="{{ url('space/index/run?uid=' . $key) }}">{{ $users[$key]['username'] }}</a>
                                            </p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="not_content">啊哦，本版块没有活跃用户！</div>
                            @endif
                        </div>
                        <!--版块成员列表结束-->
                    </div>

                    <div class="mb10 cc">
                        <a href="{{ url('bbs/post/run?fid=' . $pwforum->fid) }}"
                           class="btn_post J_thread_post_btn{{ $postNeedLogin }}"
                           data-rel="J_thread_post_types_2">发帖</a>
                        @include('widget_thread_page')
                    </div>

                    <div id="J_thread_post_types_2" class="btn_post_menu"
                         style="display:none;left:50%;margin:-11px 0 0 120px;">
                        <ul>
                            @foreach ($threadType as $key => $value)
                                <?php
                                $_urladd_ = $key ? ('&special=' . $key) : '';
                                ?>
                                <li><a href="{{ url('bbs/post/run?fid=' . $pwforum->fid) }}{{ $_urladd_ }}"
                                       data-referer="true" class="{{ @trim($postNeedLogin) }}">{{ $value[0] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
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
    Wind.use('jquery', 'global', function () {

                @if (Core::getLoginUser()->isExists())

        var JOIN_URL = "{{ url('bbs/forum/join') }}",		//版块加入
                QUIT_URL = "{{ url('bbs/forum/quit') }}",		//版块退出
                user_count = $('#J_user_count'),	//会员数
                add_list = $('#J_add_list'),
                add_none = $('#J_add_none'),
                lock = false;

        //ie6 hover显示版块退出
        if ($.browser.msie && $.browser.version < 7) {
            var forum_info_box = $('#J_forum_info_box'),
                    forum_join = forum_info_box.find('a.J_forum_join');
            $('div.J_forum_info_box').hover(function () {
                if (forum_join.data('role') == 'quit') {
                    forum_join.children().show();
                }
            }, function () {
                if (forum_join.data('role') == 'quit') {
                    forum_join.children().hide();
                }
            });
        }

        //版块加入 退出
        $('a.J_forum_join').on('click', function (e) {
            e.preventDefault();
            var $this = $(this),
                    role = $this.data('role'),
                    url = (role == 'join' ? JOIN_URL : QUIT_URL);
            if (lock) {
                return false;
            }
            lock = true;

            $.post(url, {fid: $this.data('fid')}, function (data) {
                if (data.state == 'success') {
                    if (role == 'join') {
                        $this.html('已加入<span>&nbsp;&nbsp;|&nbsp;&nbsp;取消</span>').removeClass('core_follow').addClass('core_unfollow').data('role', 'quit');

                        //加入用户
                        if (!add_list.children().length) {
                            add_list.show();
                            add_none.hide();
                        }
                        add_list.prepend('<li id="J_user_' + GV.U_ID + '">\
						<a class="J_user_card_show" data-uid="' + GV.U_ID + '" href="' + GV.U_CENTER + '"><img width="90" height="90" alt="' + GV.U_NAME + '" data-type="middle" src="' + GV.U_AVATAR + '" class="J_avatar"></a>\
						<p><a class="J_user_card_show" data-uid="' + GV.U_ID + '" href="' + GV.U_CENTER + '">' + GV.U_NAME + '</a></p>\
					</li>');

                        //global.js
                        Wind.Util.avatarError($('#J_user_' + GV.U_ID).find('img.J_avatar'));

                        //小名片
                        if ($('a.J_user_card_show').length) {
                            Wind.js(GV.JS_ROOT + 'pages/common/userCard.js?v=' + GV.JS_VERSION);
                        }

                        user_count.text(parseInt(user_count.text()) + 1);
                    } else {
                        $this.html('加入版块').removeClass('core_unfollow').addClass('core_follow').data('role', 'join');
                        $('#J_user_' + GV.U_ID).remove();
                        if (!add_list.children().length) {
                            add_list.hide();
                            add_none.show();
                        }
                        user_count.text(parseInt(user_count.text()) - 1);
                    }
                } else if (data.state == 'fail') {
                    Wind.Util.resultTip({
                        error: true,
                        msg: data.message,
                        elem: $this,
                        follow: true
                    });
                }
                lock = false;

            }, 'json');
        });

        $('#J_forum_join_trigger').on('click', function (e) {
            e.preventDefault();
            $('a.J_forum_join').click();
        });

                @endif

        var thread_post_btn = $('a.J_thread_post_btn');
        thread_post_btn.each(function (i, o) {
            Wind.Util.hoverToggle({
                elem: $(o),						//hover元素
                list: $('#' + $(o).data('rel'))		//下拉菜单
            });
        });

    });
</script>

</body>
</html>