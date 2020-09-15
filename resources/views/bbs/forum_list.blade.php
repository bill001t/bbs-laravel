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
        <!--div class="bread_crumb">$headguide</div-->
        <div class="forum_page">
            <div class="bbs_info_box">
                <span>今日：{{ $todayposts }}</span><i></i><span>昨日：{{ $bbsinfo['yposts'] }}</span><i></i><span>最高日：{{ $bbsinfo['hposts'] }}</span><i></i><span>帖子：{{ $article }}</span><i></i><span>会员：{{ $bbsinfo['totalmember'] }}</span><i></i><span>新会员：<a
                            href="{{ url('space/index/run?username=' . $bbsinfo['newmember']) }}"
                            target="_blank">{{ $bbsinfo['newmember'] }}</a></span>
            </div>

            <div class="box_wrap">
                @foreach ($cateList as $_cateId => $_cate)
                    <div class="forum_group">
                        <div class="box_title">
                            <div class="fr">
                                @if ($_cate['manager'])
                                    分栏版主：
                                    @foreach ($_cate['manager'] as $_name)
                                        <a class="J_user_card_show" data-username="{{ $_name }}"
                                           href="{{ url('space/index/run?username=' . $_name) }}">{{ $_name }} </a>
                                    @endforeach
                                @endif
                            </div>
                            <h2 class="cname"><a
                                        href="{{ url('bbs/cate/run?fid=' . $_cateId) }}">{!! $_cate['name'] !!}</a></h2>
                        </div>
                        @if ($_cate['across'] > 1)
                            {{-- <segment tpl="forum_list_table" name="more_across" args="$_cate,$forumList[$_cateId]" /> --}}
                        @else
                            {{-- <segment tpl="forum_list_table" name="one_across" args="$_cate,$forumList[$_cateId]" /> --}}
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    @include('common.footer')
</div>

<script>
    Wind.use('jquery', 'global', function () {
    });
</script>

</body>
</html>