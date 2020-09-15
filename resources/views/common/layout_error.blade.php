<!doctype html>
<html>
<head>
    @include('common.head')
    <link href="{{ asset('assets/themes/site/default/css/dev/site.css') }}/register.css" rel="stylesheet"/>
</head>
<body>
<div class="wrap">
    {{--{{-- @include('common.header') --}}--}}
    <div class="main_wrap">
        <div class="box_wrap register cc">
            <?php
//            /*$this->content();*/
            ?>
                @include(" $content ")

        </div>
    </div>
   {{--{{--  {{--  @include('common.footer') --}} --}}--}}
    <script>
        Wind.use('jquery', 'global');
    </script>
</div>
</body>
</html>