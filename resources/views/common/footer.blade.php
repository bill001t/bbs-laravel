<!--.main-wrap,#main End-->
<div class="tac">
    {{-- <advertisement id='Site.Footer1' sys='1'/> --}}
    <br/>
    {{-- <advertisement id='Site.Footer2' sys='1'/> --}}
</div>
<div class="footer_wrap">
    <div class="footer">
        <div class="bottom">
            <?php
            $nav = app(App\Services\nav\bo\PwNavBo::class);
            $bottom = $nav->getNavFromConfig('bottom');
            ?>
            @foreach($bottom as $key=>$value)
                {!! $value['name'] !!}
            @endforeach
        </div>
        <p><a href="http://www.miitbeian.gov.cn" target="_blank" rel="nofollow">{{ Core::C('site','info.icp') }}</a></p>
        <p>{!! Core::C('site','statisticscode') !!}</p>
    </div>
    {{-- <advertisement id='Site.FloatLeft' sys='1'/> --}}
    {{-- <advertisement id='Site.FloatRight' sys='1'/> --}}
    {{-- <advertisement id='Site.PopupNotice' sys='1'/> --}}
    <div id="cloudwind_common_bottom"></div>
    {{-- <hook name="footer"/> --}}
</div>

<!--返回顶部-->
<a href="#" rel="nofollow" role="button" id="back_top" tabindex="-1">返回顶部</a>
