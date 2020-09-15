<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>{{ Core::V('c', 'name') }}</title>
<link href="{{ asset('assets/themes/site/default/css/dev') }}/admin_style.css" rel="stylesheet" />
<script>
//全局变量，是Global Variables不是Gay Video喔
var GV = {
	JS_ROOT : "{{ asset('assets') }}/js/dev/",																									//js目录
	JS_VERSION : "",																										//js版本号
	TOKEN : {{ csrf_token() }},	//token ajax全局
	REGION_CONFIG : {},
	SCHOOL_CONFIG : {},
	URL : {
		LOGIN : '{{ Core::V('loginUrl') }}',																													//后台登录地址
		IMAGE_RES: '{{ asset('assets/images') }}',																										//图片目录
		REGION : '{{ url('misc/webData/area|pw') }}',					//地区
		SCHOOL : '{{ url('misc/webData/school|pw') }}'				//学校
	}
};
</script>
<script src="{{ asset('assets/js') }}/wind.js"></script>
<script src="{{ asset('assets/js') }}/jquery.js"></script>
