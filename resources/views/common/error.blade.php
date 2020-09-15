<!doctype html>
<html>
<head>
    @include('common.head')
    <link href="{{ asset('assets/themes/site/default/css/dev/forum.css') }} " rel="stylesheet"/>

    @if(!empty($refresh))
        <meta http-equiv="refresh" content="1; url={{ url($referer) }}"/>
    @endif

</head>
<body>

<div class="wrap">
    @include('common.header')
    <div class="main_wrap">


@if(!empty($title))
    <h2 class="reg_head">{{ $title }}</h2>
@endif

<div class="reg_cont_wrap">
    <div class="reg_message reg_ignore">
        <ul class="mb10 f14">
            <?php
            foreach ($message as $value){
            if (!is_string($value)) {
                continue;
            }
            /*if (!WIND_DEBUG) {
                $value = str_replace(url(), '~/', $value);
            }*/
            ?>
            <li id="J_html_error">{{ $value }}</li>
            <?php } ?>
        </ul>
        <?php
        $url = Core::C('site', 'info.url');
        ?>

        @if(!empty($referer))
            <div class="error_return"><a href="{{ $referer }}">返回上一页</a> 或者 <a href="
<?php if ($url) {
                    echo $url;
                } else {
                    echo url();
                }?>">回到首页</a></div>
        @else
            <div class="error_return"><a href="javascript:window.history.go(-1);">返回上一页</a> 或者 <a
                        href="<?php if ($url) {
                            echo $url;
                        } else {
                            echo url();
                        }?>">回到首页</a></div>
        @endif
    </div>
</div>

    </div>

    @include('common.footer')
</div>

</body>
</html>