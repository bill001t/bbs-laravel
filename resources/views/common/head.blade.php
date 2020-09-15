<meta charset="UTF-8"/>
<title>{{ Core::V('seo', 'title') }}</title>
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<meta name="description" content="{{ Core::V('seo', 'description') }}"/>
<meta name="keywords" content="{{ Core::V('seo', 'keywords') }}"/>
<link rel="stylesheet" href="{{ asset('assets/themes/site/default/css/dev/core.css') }}"/>
<link rel="stylesheet" href="{{ asset('assets/themes/site/default/css/dev/style.css') }}"/>
<!-- <base id="headbase" href="{{ url() }}/" /> --> {!! Core::C('site', 'css.tag') !!}
<script>
    //全局变量 Global Variables
    var GV = {
        JS_ROOT: '{{ asset('assets') }}/js/',										//js目录
        JS_VERSION: '',											//js版本号(不能带空格)
        JS_EXTRES: '{{ asset('assets/extres') }}',
        TOKEN: '{{ csrf_token() }}',	//token $.ajaxSetup data
        U_CENTER: '{{ url('space/index/run') }}',		//用户空间(参数 : uid)
        <?php
        $loginUser = Core::getLoginUser();
        ?>
        @if (Core::getLoginUser()->isExists())
        //登录后
        U_NAME: '{{ Core::getLoginUser()->username }}',										//登录用户名
        U_AVATAR: '{{ App\Core\Tool::getAvatar(Core::getLoginUser()->uid) }}',							//登录用户头像
        @endif
        U_AVATAR_DEF: "{{ asset('assets/images/face/face_small.jpg') }}",					//默认小头像
        U_ID: parseInt('{{ Core::getLoginUser()->uid }}'),									//uid
        REGION_CONFIG: '',														//地区数据
        CREDIT_REWARD_JUDGE: '{!! Core::getLoginUser()->showCreditNotice() !!}',			//是否积分奖励，空值:false, 1:true
        URL: {
            LOGIN: '{{ url('u/login/run') }}',										//登录地址
            QUICK_LOGIN: '{{ url('u/login/fast') }}',								//快速登录
            IMAGE_RES: '{{ asset('assets/images') }}',										//图片目录
            CHECK_IMG: '{{ url('u/login/showverify') }}',							//验证码图片url，global.js引用
            VARIFY: '{{ url('verify/index/get') }}',									//验证码html
            VARIFY_CHECK: '{{ url('verify/index/check') }}',							//验证码html
            HEAD_MSG: {
                LIST: '{{ url('message/notice/minilist') }}'							//头部消息_列表
            },
            USER_CARD: '{{ url('space/card/run') }}',								//小名片(参数 : uid)
            LIKE_FORWARDING: '{{ url('post/doreply/') }}',							//喜欢转发(参数 : fid)
            REGION: '{{ url('misc/webData/area') }}',									//地区数据
            SCHOOL: '{{ url('misc/webData/school') }}',								//学校数据
            EMOTIONS: "{{ url('emotion/index/run?type=bbs') }}",					//表情数据
            CRON_AJAX: '{{--{{ $runCron }}--}}',											//计划任务 后端输出执行
            FORUM_LIST: '{{ url('bbs/forum/list') }}',								//版块列表数据
            CREDIT_REWARD_DATA: '{{ url('u/index/showcredit') }}',					//积分奖励 数据
            AT_URL: '{{ url('bbs/remind/run') }}',									//@好友列表接口
            TOPIC_TYPIC: '{{ url('bbs/forum/topictype') }}'							//主题分类
        }
    };
</script>
<script src="{{ asset('assets/js/wind.js') }}"></script>
{{-- <hook name="head" display='false'/> --}}