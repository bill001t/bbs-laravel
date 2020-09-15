@if (!Core::getLoginUser()->isExists())
    <?php
    $_loginWay = App\Http\Controllers\u\Services\helper\PwUserHelper::getLoginMessage();
    ?>
    <div class="box_wrap sidebar_login">
        <!--
		<form id="J_login_form" action="{{ url('u/login/dologin') }}" method="post">
		<dl>
			<dt id="J_sidebar_login_dt" class="cc">
				<i class="icon_username" title="请输入{{ $_loginWay }}"></i><label for="J_username">用户名</label><input required type="text" class="input" id="J_username" name="username" placeholder="{{ $_loginWay }}">
				<i class="icon_password" title="请输入密码"></i><label for="J_password">密　码</label><input required type="password" class="input" id="J_password" name="password" placeholder="密码">
			</dt>
			<dd class="associate"><a class="sendpwd" rel="nofollow" href="{{ url('u/findPwd/run') }}">找回密码</a><label for="head_checkbox" title="下次自动登录"><input type="checkbox" id="head_checkbox" name="rememberme" value="31536000">自动登录</label></dd>
			<dd class="operate"><button type="submit" id="J_sidebar_login" class="btn btn_big btn_submit">登录</button><a class="btn btn_big btn_error" href="{{ url('u/register/run') }}" rel="nofollow">立即注册</a></dd>
		</dl>
		</form>
        -->
        <dd class="operate">
            <button type="button" id="J_sidebar_login" class="btn btn_big btn_submit"
                    onclick="location.href='{{ url('u/login/run') }}'">登录
            </button>
            <a class="btn btn_big btn_error" href="{{ url('u/register/run') }}" rel="nofollow"
               onclick="location.href='{{ url('u/register/run') }}'">立即注册</a></dd>
    </div>
@else
    <?php
    $_group = Core::getLoginUser()->getGroupInfo();
    ?>
    <div class="box_wrap user_info">
        <dl class="cc">
            <dt id="J_ava_ie6">
                <a href="{{ url('space/index/run?uid=' . Core::getLoginUser()->uid) }}"><img class="J_avatar"
                                                                                   src="{{ App\Core\Tool::getAvatar(Core::getLoginUser()->uid) }}"
                                                                                   data-type="middle" width="72"
                                                                                   height="72"
                                                                                   alt="{{ Core::getLoginUser()->username }}"/></a>
                <a href="{{ url('profile/avatar/run?_left=avatar') }}"><b></b><span>修改头像</span></a>
            </dt>
            <dd>
                <div class="name"><a href="{{ url('space/index/run?uid=' . Core::getLoginUser()->uid) }}"
                                     class="username">{{ Core::getLoginUser()->username }}<i></i></a></div>
                <div class="level"><a href="{{ url('profile/right/run?_left=right') }}">{{ $_group['name'] }}</a></div>
                <div class="level_img">
                    <a href="{{ url('profile/credit/run') }}"><img
                                src="{{ asset('assets/images') }}/level/{{ $_group['image'] }}"
                                alt="{{ $_group['name'] }}"/></a>
                </div>
            </dd>
        </dl>
        <div class="num">
            <ul class="cc">
                <li><a href="{{ url('my/follow/run') }}"><span>{{ Core::getLoginUser()->info['follows'] }}</span><em>关注</em></a>
                </li>
                <li><a href="{{ url('my/fans/run') }}"><span>{{ Core::getLoginUser()->info['fans'] }}</span><em>粉丝</em></a></li>
                <li class="tail"><a
                            href="{{ url('my/article/run') }}"><span>{{ Core::getLoginUser()->info['postnum'] }}</span><em>帖子</em></a>
                </li>
            </ul>
        </div>
        @if (Core::C('site','medal.isopen'))
            <div class="medal_widget" id="J_medal_widget">
                <a href="javascript:;" class="next next_disabled J_lazyslide_next" title="下一组"><em></em></a>
                <a href="javascript:;" class="pre pre_disabled J_lazyslide_prev" title="上一组"><em></em></a>
                <div class="medal_list_wrap">
                    <ul id="J_medal_widget_ul" class="cc J_lazyslide_list" style="width:900px;">
                        <?php
                        $J_medals = app('medal.srv.PwMedalCache')->getMyAndAutoMedal(Core::getLoginUser()->uid);
                        $_medals = array_slice($J_medals, 0, 6, true);
                        ?>
                        @foreach ($_medals as $medal)
                            @if($medal['award_status'] !=4)
                                <li class="doing"><a href="{{ url('medal/index/run') }}"><img src="{{ $medal['icon'] }}"
                                                                                              width="30" height="30"
                                                                                              title="{{ $medal['name'] }}"
                                                                                              alt="{{ $medal['name'] }}"/></a>
                                </li>
                            @else
                                <li><a href="{{ url('medal/index/run') }}"><img src="{{ $medal['icon'] }}" width="30"
                                                                                height="30" title="{{ $medal['name'] }}"
                                                                                alt="{{ $medal['name'] }}"/></a></li>
                            @endif
                        @endforeach
                    </ul>
		            <textarea id="J_sidebar_medal_ta" style="display:none">
                         @foreach ($J_medals as $medal)
                            @if($medal['award_status'] !=4)
                                <li class="doing"><a href="{{ url('medal/index/run') }}"><img
                                                src="{{ $medal['icon'] }}"
                                                width="30" height="30"
                                                title="{{ $medal['name'] }}"
                                                alt="{{ $medal['name'] }}"/></a>
                                </li>
                            @else
                                <li><a href="{{ url('medal/index/run') }}"><img src="{{ $medal['icon'] }}"
                                                                                width="30"
                                                                                height="30"
                                                                                title="{{ $medal['name'] }}"
                                                                                alt="{{ $medal['name'] }}"/></a>
                                </li>
                            @endif
                        @endforeach
                    </textarea>
                </div>
            </div>
        @endif
        <?php
        $punchService = app('space.srv.PwPunchService');
        list($punchOpen, $punchFriendOpen) = $punchService->getPunchConfig();
        if ($punchOpen) {
        list($punchStatus, $punchButton, $punchData) = $punchService->getPunch();
        $punchStatus = $punchStatus ? '' : 'punch_widget_disabled';
        list($monthDay, $weekDay) = $punchService->formatWeekDay(App\Core\Tool::getTime());
        ?>
        <div class="cc punch_widget_wrap">
            <div id="J_punch_main_tip" class="fl dn">
                @if ($punchData)
                    <div class="tips">
                        <div class="core_arrow_top"><em></em><span></span></div> {{ $punchData['username'] }}
                        已帮你领取<span class="red">{{ $punchData['cNum'] }}</span>{{ $punchData['cUnit'] }}
                        {$punchData['cType']}
                    </div>
                @endif
            </div>
            <div class="punch_widget {{ $punchStatus }}" id="J_punch_widget">
                <div class="date">{{ $monthDay }}<span>{{ $weekDay }}</span></div>
                <div class="cont"><a data-tips="{{ url('space/punch/punchtip') }}"
                                     data-uri="{{ url('space/punch/punch') }}" href="#" id="J_punch_mine"
                                     tabindex="-1" target="_blank">{{ $punchButton }}</a></div>

                @if ($punchFriendOpen)

                    <a data-uri="{{ url('space/punch/friend') }}" href="#" id="J_punch_friend" class="help_ta"
                       tabindex="-1" target="_blank">帮Ta打卡</a>
                @endif
            </div>
        </div>
        <?php } ?>
    </div>
@endif
