<!doctype html>
<html>
<head>
    @include('common.head')
    <link href="{{ asset('assets/themes/site/default/css/dev/announce.css') }}" rel="stylesheet"/>
</head>
<body>
<div class="wrap">
    {{-- @include('common.header') --}}
    <div class="main_wrap">
        <div class="bread_crumb">
            <a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a
                    href="{{ url('announce/index/run') }}">公告</a>
        </div>
        <div class="main cc">
            <div class="main_body">
                <div class="main_content cc">
                    <div class="box_wrap announce_page" id="J_announce_list">
                        @foreach($list as $value)
                            <?php
                            if ($aid == $value['aid']) {
                                $classValue = 'class=current';
                            }
                            ?>
                            <dl {{$classValue}}>
                                <dt>
                                    <?php
                                    $subject = null;
                                    if ($value['typeid']) {
                                        $subject = "<a href='" . $value['url'] . "' target='_blank'>" . $value['subject'] . "</a>";
                                    } else {
                                        $subject = $value['subject'];
                                    }
                                    ?>
                                    @if($value['typeid'])
                                        <span class="subject"><a href="{{ $value['url'] }}"
                                                                 target="_blank">{{ $value['subject'] }}</a></span>
                                    @else
                                        <span class="subject">{{ $value['subject'] }}</span>
                                    @endif
                                    <a href="{{ url('/space/index/run?uid=' . $value['created_userid']) }}"
                                       class="name">{{ $value['username'] }}</a>
                                    <?php
                                    $start_date = App\Core\Tool::time2str($value['start_date'], 'Y-m-d');
                                    $end_date = App\Core\Tool::time2str($value['end_date'], 'Y-m-d');
                                    ?>
                                    <span class="time">{{ $start_date }} 至 {{ $end_date }}</span></dt>
                                {{ $content = $value['typeid'] ? null : $value['content']; }}
                                <dd>
                                    {!! $content !!}
                                </dd>
                            </dl>
                            <?php $classValue = null; ?>
                            <div class="p10">
                                {{--<page tpl='TPL:common.page' page='$page' per='$perpage' count='$total'
                                      url='announce/index/run'/>--}}
                            </div>
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
        //公告手风琴
        $('#J_announce_list > dl').on('click', function () {
            $(this).toggleClass('current').siblings('.current').removeClass('current');
        });
    });
</script>

</body>
</html>