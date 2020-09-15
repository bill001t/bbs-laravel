<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/like.css') }} "rel="stylesheet" />
</head>
<body class="like_body">

<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('like/like/run') }}">喜欢</a><em>&gt;</em><a href="{{ url('ike/mylike/ta') }}">Ta的喜欢</a>
		</div>
		<div class="like_page_ta cc">
			<div class="like_nav cc">
				<ul>
					<li><a href="{{ url('like/like/run') }}">热门喜欢</a></li>
					<li  class="current"><a href="{{ url('like/mylike/ta') }}" data-referer="true" class="J_qlogin_trigger">Ta的喜欢</a></li>
					<li><a href="{{ url('like/mylike/run') }}" data-referer="true" class="J_qlogin_trigger">我的喜欢</a></li>
				</ul>
			</div>
			<div class="like_falls cc" id="container" style="width:960px;">
				
			</div>
			<div class="loading" id="J_loading"><span>加载中...</span></div>
			<!-- <div class="dn" id="J_page">这里是分页信息</div> -->
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script>
var J_LIKE_URL = "{{ url('like/mylike/data') }}";
var LIKE_PLUS = "{{ url('like/mylike/doLike?typeid=_FROMTYPE&fromid=_KEY') }}";		//点击喜欢地址
var J_LIKE_DATA = "{{ Security::escapeEncodeJson($data) }}";
		
Wind.use('jquery', 'global', function(){
	Wind.js(GV.JS_ROOT +"util_libs/masonry.js?v="+ GV.JS_VERSION, GV.JS_ROOT +"pages/mylike/like_index.js?v="+ GV.JS_VERSION, GV.JS_ROOT+ 'pages/common/likePlus.js?v='+ GV.JS_VERSION, function(){
		//Ta的喜欢模板
	    var templateTa = '\
				<div class="tmode_waterfall">\
					<% if(image!=null){%>\
						<div class="img img_like"><img src="<%=image%>" width="200" alt="title"></div>\
					<%}%>\
					<div class="title"><a href="<%=url%>"><%=subject%></a></div>\
					<div class="descrip"><%=descrip%></div>\
					<div class="user">\
						<a href="<%=space%>" target="_blank" data-uid="<%=uid%>" class="J_user_card_show"><img src="<%=avatar%>" class="J_avatar" data-type="small" width="30" height="30"><%=username%></a>\
						<span class="time"><%=lasttime%></span>\
					</div>\
					<div class="num">\
						<span class="icon_like J_like_count" title="喜欢"><%=like_count%></span>\
						<span class="icon_reply" title="回复">10</span>\
					</div>\
				</div>';
		   	//pages/mylike/like_index.js
			var fall = new LikeFall({
				container: $("#container"),
				url: J_LIKE_URL,
				template: templateTa,
				dis: 20,
				firstLoaded: function(result){
					if(result == '' || result.length < 1){
						$("#loading").hide();
						$("#container").html('<div class="box_wrap"><div class="not_content">啊哦，Ta的喜欢暂没有任何内容哦!</div></div>');
						return;
					}
					//初始化瀑布流
					$("#container").imagesLoaded(function(){
	                  $("#container").masonry({
	                    itemSelector: '.tmode_waterfall',
	                    columnWidth: function( containerWidth ) {
	                        return containerWidth / 4;
	                      }
	                  });
	                });
				},
				allLoaded: function(){
					$("#J_page").show();
				},
				RenderComplete: function(html){
					//头像加载容错处理
					var avatar = html.find('.J_avatar');
					avatar && Wind.Util.avatarError(avatar);

					html.css("opacity", 0);
					try{
						//这里当图片异常的时候会导致延迟显示
                        html.imagesLoaded(function(){
							$("#J_loading").hide();
							
							
							$("#container").append(html).masonry('appended', html, true);
							html.animate({"opacity": 1},800);

							//likePlus
							//likePlus(html.find('.J_like_btn'));
                        });
                   }catch(e){
                   }
				},
				loadFailed: function(){
					$("#J_loading").hide();
				}
			});
			fall.init();	
	})
});


</script>

</body>
</html>