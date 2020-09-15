@if ($site_info_notice = Core::C('site','info.notice'))
    <style>
        .header_wrap {
            top: 29px;
        }

        body {
            padding-top: 75px;
        }
    </style>
    <div id="notice">{{ $site_info_notice }}</div>
@endif
<header class="header_wrap">
    <div id="J_header" class="header cc">
        <div class="logo">
            <a href="{{ url() }}">
                @if($__css = Core::C('site', 'css.logo'))
                        <!--后台logo上传-->
                <img src="{{ App\Core\Tool::getPath($__css) }}" alt="{{ Core::C('site','info.name') }}">
                @else
                    <img src="{{ asset('assets/images/site/images/logo.png') }}"
                         alt="{{ Core::C('site','info.name') }}">
                @endif
            </a>
        </div>
        <nav class="nav_wrap">
            <div class="nav">
                <ul>
                    <?php
                    $nav = app(App\Services\nav\bo\PwNavBo::class);
                    $nav->setRouter();
                    $currentId = '';
                    $main = $child = array();
                    if ($nav->isForum()) $nav->setForum(isset($pwforum) ? $pwforum->foruminfo['parentid'] : 0, isset($fid) ? $fid : 0, isset($tid) ? $tid : 0);
                    $main = $nav->getNavFromConfig('main', true);
                    foreach($main as $key=>$value){
                    if (isset($value['current'])) {
                        $current = 'current';
                        $currentId = $key;
                    } else {
                        $current = '';
                    }
                    isset($value['child']) && $child[$key] = $value['child'];
                    ?>
                    <li class="{{ $current }}">{!! $value['name'] !!}</li>
                    <?php } ?>
                </ul>
            </div>
        </nav>
        {{-- <hook name="header_nav"/> --}}
        <div class="header_search" role="search">
            <form action="{{ url('search/s/run') }}" method="post">
                <input type="text" id="s" aria-label="搜索关键词" accesskey="s" placeholder="搜索其实很简单" x-webkit-speech speech
                       name="keyword"/>
                <button type="submit" aria-label="搜索"><span>搜索</span></button>
            </form>
        </div>
        @include('common.header_login')
    </div>
</header>

@if ($child)
    @foreach ($child as $ck => $cv)
        @if ($currentId == $ck)
            <div class="nav_weak" id="{{ $ck }}">
                @else
                    <div class="nav_weak" id="{{ $ck }}" style="display:none">
                        @endif
                        <ul class="cc">
                            @foreach($cv as $_v)
                                <?php
                                $current = $_v['current'] ? 'current' : '';
                                ?>
                                <li class="{{ $current }}">{!! $_v['name'] !!}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                @endif
                <div class="tac">{{-- <advertisement id='Site.NavBanner' sys='1'/> --}}</div>