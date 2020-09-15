<nav>
    <div class="content_nav">
        <ul>
            <li class="{!! App\Core\Tool::isCurrent($li == 'follow') !!}"><a href="{{ url('my/follow/run') }}">我关注的人</a></li>
            <li class="{!! App\Core\Tool::isCurrent($li == 'fans') !!}"><a href="{{ url('my/fans/run') }}">我的粉丝</a></li>
            <li class="{!! App\Core\Tool::isCurrent($li == 'invite') !!}"><a href="{{ url('my/invite/run') }}">邀请好友</a></li>
            <li class="{!! App\Core\Tool::isCurrent($li == 'friend') !!}"><a href="{{ url('my/friend/run') }}">找人</a></li>
            <li class="{!! App\Core\Tool::isCurrent($li == 'visitor') !!}"><a href="{{ url('my/visitor/run') }}">访问脚印</a></li>
        </ul>
    </div>
</nav>