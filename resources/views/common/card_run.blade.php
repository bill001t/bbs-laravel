<div class="ct">
    <dl class="cc">
        <dt><a href="{{ url('space/index/run?uid=' . $uid) }}"><img src="{{ App\Core\Tool::getAvatar($uid, 'small') }}"
                                                                    onerror="this.onerror=null;this.src='{{ asset('assets/images') }}/face/face_small.jpg'"
                                                                    width="50" height="50"></a></dt>
        <dd>
            <p class="title">
                <span class="level">{{ @$user->getGroupInfo('name') }}</span>
                <a href="{{ url('space/index/run?uid=' . $uid) }}" class="name">{{ $user->info['username'] }}</a><span
                        class="{{ $female ? 'women' : 'man' }}_{{ @$isol ? 'ol' : 'unol' }}"
                        title="{{ $isol ? '在线' : '离线' }}"></span>
            </p>
            <p class="num">
                关注 <a href="{{ url('space/follows/run?uid=' . $uid) }}">{{ $user->info['follows'] }}</a><span>|</span>粉丝
                <a href="{{ url('space/fans/run?uid=' . $uid) }}">{{ $user->info['fans'] }}</a><span>|</span>帖子 <a
                        href="{{ url('space/thread/run?uid=' . $uid) }}">{{ $user->info['postnum'] }}</a>
            </p>
        </dd>
    </dl>
    @if ($follow2num > 0 && !$isFollowed)
        <div class="card_common_follow">您关注的人中：
            <?php
            $num = 0;
            ?>
            @foreach ($usernames as $key => $value)
                @if ($num++ > 0)
                    、<a href="{{ url('space/index/run?uid=' . $value['uid']) }}">{{ $value['username'] }}</a>
                @endif
            @endforeach
            @if ($follow2num > 2)
                等
                @if ($follow2num > 99)
                    99+
                @else
                    {$follow2num}人
                @endif
            @endif
            也关注了<span class="w">Ta</span>
        </div>
        @endif
                <!-- <div class="card_fresh"><span>3分钟前</span>新鲜事：<a href="">猫狗大作战，猫猫必胜！！！必胜...</a></div> -->
        @if ($medalNum)
            <div class="card_medal">
                <h6><a href="{{ url('medal/index/run') }}">{{ $medalNum }}枚</a></h6>
                <ul class="cc">
                    <?php
                    $i = 0;
                    foreach ($medals as $key => $value){                            ?>
                    <li><a href="{{ url('medal/index/run') }}"><img src="{{ $value['icon'] }}" width="30"
                                                                    height="30"
                                                                    title="{{ $value['descrip'] }}"/></a></li>

                    <?php
                    if (++$i >= 7) {
                        break;
                    }
                    }
                    ?>
                </ul>
            </div>
        @endif
</div>
@if ($uid && Core::getLoginUser()->isExists() && $uid != Core::getLoginUser()->uid)
    <div class="ft">
        <?php
        if ($isFollowed) {
            $style_f = '';
            $style_unf = 'display:none;';
        } else {
            $style_f = 'display:none;';
            $style_unf = '';
        }
        $userurlencode = urlencode($user->info['username']);
        ?>
        <div class="J_follow_wrap" style="{{ $style_f }}"><a href="{{ url('my/follow/delete') }}"
                                                             class="J_card_follow core_unfollow" data-uid="{{ $uid }}">取消关注</a>
        </div>
        <div class="J_follow_wrap" style="{{ $style_unf }}"><a href="{{ url('my/follow/add') }}"
                                                               class="core_follow J_card_follow" data-uid="{{ $uid }}">关注</a>
        </div>
        <a class="message J_send_msg_pop" data-name="{{ $user->info['username'] }}"
           href="{{ url('message/message/pop?uid=' . $uid) }}">写私信</a>
        @if (!$isFans && $isFollowed)
            <span>|</span><a class="J_send_msg_pop" data-name="{{ $user->info['username'] }}"
                             href="{{ url('message/message/pop?username=' . $user->info['username']) }}">求关注</a>
        @endif
    </div>
@endif