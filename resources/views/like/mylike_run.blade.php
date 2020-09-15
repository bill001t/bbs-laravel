<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/like.css') }} "rel="stylesheet" />
</head>
<body>

<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">

		<div class="bread_crumb">
			<a href="{{ url() }}" class="home" title="{{ Core::C('site', 'info.name') }}">首页</a><em>&gt;</em><a href="{{ url('like/like/run') }}">喜欢</a><em>&gt;</em><a href="{{ url('like/mylike/run') }}">我的喜欢</a>
		</div>
		<div class="like_nav">
			<ul class="cc">
				<li><a href="{{ url('like/like/run') }}">热门喜欢</a></li>
				<li><a href="{{ url('like/mylike/ta') }}" data-referer="true" class="J_qlogin_trigger">Ta的喜欢</a></li>
				<li class="current"><a href="{{ url('like/mylike/run') }}" data-referer="true" class="J_qlogin_trigger">我的喜欢</a></li>
			</ul>
		</div>
		<div class="main cc">
			<div class="main_body">
				<div class="main_content">

					<div class="box_wrap like_page">

@if ($logLists)

						<div class="like_content">
							<div id="J_like_ul" class="like_lists">

@foreach ($logLists as $logList)
$uid = $likeInfos[$logList['likeid']]['uid'];
								if (!isset($likeInfos[$logList['likeid']])) {
							#-->
								<dl class="cc">
								 <dt class="face"><img class="J_avatar" src="{{ App\Core\Tool::getAvatar(0, 'small') }}" data-type="small" width="50" height="50" alt="游客" /></dt>
									<dd class="text_content">
										<div class="content">原文不存在</div>
										<div class="info">
											<span class="time">喜欢于{{ App\Core\Tool::time2str($logList['created_time']) }}</span><a href="#" data-uri="{{ url('like/mylike/doDelLike') }}" data-pdata="{'logid':{{ $logList['logid'] }}}" class="a_unlike J_unlike">取消喜欢</a>
										</div>
									</dd>
								</dl>
							 <!--# continue; } #-->
								<dl class="cc">
								 <dt class="face"><a class="J_user_card_show" data-uid="{{ $uid }}" href="{{ url('space/index/run?uid=' . $uid) }}"><img  class="J_avatar" src="{{ App\Core\Tool::getAvatar($likeInfos[$logList['likeid']]['uid'], 'small') }}" data-type="small" width="50" height="50" alt="{{ $likeInfos[$logList['likeid']]['username'] }}" /></a></dt>
									<dd class="text_content">

@if (empty($likeInfos[$logList['likeid']]['subject']))

										<div class="content"><a href="{{ url('space/index/run?uid=' . $uid) }}" class="name J_user_card_show" data-uid="{{ $likeInfos[$logList['likeid']]['uid'] }}">{{ $likeInfos[$logList['likeid']]['username'] }}</a>：<em class="f14">{!! $likeInfos[$logList['likeid']]['content'] !!}</em></div>
										<div class="info">
											<div class="sort">
												<a class="J_group_trigger" id="J_group_trigger_{{ $logList['logid'] }}" data-id="{{ $logList['logid'] }}" href="">

@if (empty($logList['tags']))

													<span class="J_group_names">未分组</span>

@else

														<!--#
															$_sbtagname = '';
															foreach((array)$logList['tags'] as $tag){
																if (!$tagLists[$tag]['tagname']) continue;
																$_sbtagname .= $_sbtagname ? ','. $tagLists[$tag]['tagname'] : $tagLists[$tag]['tagname'] ;
															}
														#-->
														<span class="J_group_names" title="{{ App\Core\Tool::substrs($_sbtagname,100) }}">{{ App\Core\Tool::substrs($_sbtagname, 5) }}
														</span>
													<!--# } #-->
													<em class="core_arrow"></em>
												</a>
											</div>
											<span class="time">喜欢于{{ App\Core\Tool::time2str($logList['created_time']) }}</span><a href="#" data-uri="{{ url('like/mylike/doDelLike?logid=' . $logList['logid']) }}" data-pdata="{'logid':{{ $logList['logid'] }}}" class="a_unlike J_unlike">取消喜欢</a>
										</div>

@else

										<div class="content"><a href="{{ url('space/index/run?uid=' . $uid) }}" class="name J_user_card_show" data-uid="{{ $likeInfos[$logList['likeid']]['uid'] }}">{{ $likeInfos[$logList['likeid']]['username'] }}</a>：<em><a href="{{ $likeInfos[$logList['likeid']]['url'] }}" class="title">{{ $likeInfos[$logList['likeid']]['subject'] }}</a></em></div>
										<div class="descrip">{!! $likeInfos[$logList['likeid']]['content'] !!}</div>
										<div class="info">
											<div class="sort">
												<a class="J_group_trigger" id="J_group_trigger_{{ $logList['logid'] }}" data-id="{{ $logList['logid'] }}" href="">

@if (empty($logList['tags']))

													<span class="J_group_names">未分组</span>

@else


														<!--#
															$_sbtagname = '';
															foreach((array)$logList['tags'] as $tag){
																if (!$tagLists[$tag]['tagname']) continue;
																$_sbtagname .= $_sbtagname ? ','. $tagLists[$tag]['tagname'] : $tagLists[$tag]['tagname'] ;
															}
														#-->
														
														<span class="J_group_names" title="{{ App\Core\Tool::substrs($_sbtagname,100) }}">{{ App\Core\Tool::substrs($_sbtagname, 5) }}
														</span>
													<!--# } #-->
													<em class="core_arrow"></em>
												</a>
											</div>
											<span class="time">喜欢于{{ App\Core\Tool::time2str($logList['created_time']) }}</span>&nbsp;&nbsp;<a href="#" data-uri="{{ url('like/mylike/doDelLike') }}" data-pdata="{'logid':{{ $logList['logid'] }}}" class="a_unlike J_unlike">取消喜欢</a>
										</div>
									<!--# } #-->
										<div class="fr">
											<div id="J_group_check_list_{{ $logList['logid'] }}" data-id="{{ $logList['logid'] }}" class="sort_down J_group_check_list" style="display:none;">
												<ul></ul>
												<a href="" class="add J_group_creat_show" data-id="{{ $logList['logid'] }}">创建新分类</a>
											</div>
										</div>
									</dd>
								</dl>
							<!--# } #-->
								<page tpl="TPL:common.page" page="$page" per="$perpage" count="$count" url="like/mylike/run" args="$args"/>
							</div>
						</div>

@else

						<div class="like_not_tips">没有喜欢的帖子</div>
					<!--# } #-->
					</div>
				</div>
			</div>
			<div class="main_sidebar">
				@include('common.sidebar_1')
			
				<div class="box_wrap">
					<h2 class="box_title">我的喜欢分类</h2>
					<div class="side_cate_list">
						<ul id="J_side_group_list" class="cc">

@if ($tagLists)


@foreach ($tagLists as $tag)

							<li id="J_side_group_{{ $tag['tagid'] }}"><a href="{{ url('like/mylike/doDelTag') }}" class="icon_del J_group_del" data-pdata="{'tag': {{ $tag['tagid'] }}}" data-id="{{ $tag['tagid'] }}">删除</a><a href="{{ url('like/mylike/doEditTag?tag=' . $tag['tagid']) }}" data-name="{{ $tag['tagname'] }}" data-id="{{ $tag['tagid'] }}" class="icon_edit J_group_edit">编辑</a><a href="{{ url('like/mylike/run?tag=' . $tag['tagid']) }}" class="title"><span class="J_name">{{ $tag['tagname'] }}</span><em>({$tag['number']})</em></a></li>
							<!--# } #-->
						<!--# } #-->
						</ul>
						<a href="" class="add" id="J_creat_group_side">创建新分类</a>
					</div>
				</div>
			
				<div class="box_wrap">
					<h2 class="box_title">热门喜欢</h2>
					<div class="like_hot_list">
						<ul>

@foreach ($hotBrand as $list)

							<li><a href="{{ url('bbs/read/run?tid=' . $list['fromid']) }}">{{ $list['subject'] }}</a><span class="num">{{ $list['counts'] }}</span></li>
						<!--# } #-->
						</ul>
					</div>
				</div>
			</div>
<!--===========添加分类弹窗=============--> 
	<div id="J_tag_pop" class="core_pop_wrap" style="display:none;">
		<div class="core_pop">
			<div class="pop_like_sort">
				<form id="J_tag_form" method="post" action="{{ url('like/mylike/doAddTag') }}">
				<div class="pop_top J_drag_handle">
					<a  href="#" class="pop_close J_tag_pop_close">关闭</a>
					<strong id="J_pop_title">添加分类</strong>
				</div>
				<div class="pop_cont">
					<dl id="J_my_tag_dl" class="cc">
						<dt>我的分类：</dt>
						<dd style="max-height:150px;overflow:auto;">
							<div class="pick_list" id="J_tag_pick_list"></div>
						</dd>
					</dl>
					<dl class="cc">
						<dt>分类：</dt>
						<dd>
							<input id="J_tag_input" type="text" name="tagname" class="input length_4 mb5">
							<p class="gray" id="J_tag_tips"></p>
						</dd>
					</dl>
				</div>
				<div class="pop_bottom">
					<button id="J_tag_sub" type="submit" class="btn btn_submit">提交</button><button class="btn J_tag_pop_close">取消</button>
					<input id="J_tag_logid" type="hidden" name="logid" />
					<input id="J_tag_tagid" type="hidden" name="tagid" />
				</div>
				</form>
			</div>
		</div>
	</div>
		</div>
	</div>
<ul style="display:none;" id="J_group_check_ul">

@foreach ($tagLists as $tag)

	<li><label><input class="J_group_name" type="checkbox" data-id="{{ $tag['tagid'] }}" data-value="{{ $tag['tagname'] }}">{{ $tag['tagname'] }}</label></li>
	<!--# } #-->
</ul>
{{--  @include('common.footer') --}}
</div>
<script>
var  GRROUP_DATA = '{{ Security::escapeEncodeJson($likeJson) }}';
Wind.use('jquery', 'global', function(){
	
	var my_like = $('#J_side_group_list');
	$('#J_group_check_ul').data({
		'saveList' : function(elem, type, id){
			//保存分组
			Wind.Util.ajaxMaskShow();
			$.post("{{ url('like/mylike/doLogTag') }}", {
				logid : elem.data('id'),
				tagid : id,
				type : (type ? 1 : 0)
			}, function(data){
				Wind.Util.ajaxMaskRemove();
				if(data.state === 'success') {
					
				}else if(data.state === 'fail') {
					Wind.Util.resultTip({
						error : true,
						follow : elem,
						msg : data.message
					});
				}
			}, 'json');
		},
		'saveCreat' : function(btn){
			//保存创建
			var creat_wrap = $('#J_group_creat_wrap'),
				check_list = btn.parents('.J_group_check_list');
			$.post("{{ url('like/mylike/doAddLogTag') }}", {
				tagname : $('#J_group_creat_input').val(),
				logid : check_list.data('id')
			}, function(data){
				if(data.state === 'success') {
					var _data = data.data;
					creat_wrap.remove();
					btn.show();
					
					//所有列表写入新创建分组
					$('div.J_group_check_list > ul').append('<li><label><input type="checkbox" data-id="'+ _data.id +'" data-value="'+ _data.name +'" class="J_group_name">'+ _data.name +'</label></li>');

					//选中新创建的
					var newest = check_list.find('ul > li:last input:checkbox');
					newest.prop('checked', true);
					//checkListGroup.js
					setGroupNames(newest);

					//右侧栏写入新分组
					my_like.append('<li id="J_side_group_'+ _data.id +'"><a class="icon_del J_group_del" data-pdata="{{ \'tag\': \''+ _data.id +'\' }}" href="{{ url('like/mylike/doDelTag/') }}" data-id="'+ _data.id +'">删除</a><a class="icon_edit J_group_edit" data-id="'+ _data.id +'" data-name="'+ _data.name +'" href="{{ url('like/mylike/doEditTag/') }}&tag='+ _data.id +'">编辑</a><a class="title" href="{{ url('like/mylike/run/') }}&tag='+ _data.id +'"><span class="J_name">'+ _data.name +'</span><em>(1)</em></a></li>');

				}else if(data.state === 'fail'){
					Wind.Util.resultTip({
						error : true,
						follow : creat_wrap,
						msg : data.message
					});
				}
			}, 'json');
		}
	});

	my_like.data({
		save : function(elem, url){
			var group_edit_input = $('#J_group_edit_input'),
				id = elem.data('id');
			$.post(url, {
				tag : id,
				tagname : elem.val()
			}, function(data){
				if(data.state === 'success') {
					var _data = data.data,
						li = $('#J_side_group_'+ id);

					if(li.length) {
						//编辑
						li.find('.J_name').text(_data.name);
						li.find('.J_group_edit').data('name', _data.name);
					}else{
						//所有列表写入新创建分组
						$('div.J_group_check_list > ul').append('<li><label><input type="checkbox" data-id="'+ _data.id +'" data-value="'+ _data.name +'" class="J_group_name">'+ _data.name +'</label></li>');

						//右侧栏写入新分组
						my_like.append('<li id="J_side_group_'+ _data.id +'"><a class="icon_del J_group_del" data-pdata="{{ \'tag\': \''+ _data.id +'\' }}" href="{{ url('like/mylike/doDelTag/') }}" data-id="'+ _data.id +'">删除</a><a class="icon_edit J_group_edit" data-id="'+ _data.id +'" data-name="'+ _data.name +'" href="{{ url('like/mylike/doEditTag/') }}&tag='+ _data.id +'">编辑</a><a class="title" href="{{ url('like/mylike/run/') }}&tag='+ _data.id +'"><span class="J_name">'+ _data.name +'</span><em>(0)</em></a></li>');
					}
					var group_edit_wrap = $('#J_group_edit_wrap');
					group_edit_wrap.siblings('li:hidden').show();
					$('#J_creat_group_side').show();
					group_edit_wrap.remove();
				}else if(data.state === 'fail'){
					Wind.Util.resultTip({
						error : true,
						elem : elem,
						follow : true,
						msg : data.message
					});
				}
			}, 'json');
		},
		creatsave :  '{{ url('like/mylike/doAddTag') }}',
		editsave : '{{ url('like/mylike/doEditTag') }}',
		deltip : '确定删除该分类？<br><span class="gray">不会将该分类的喜欢一起删除</span>'
	});

	Wind.js(GV.JS_ROOT +'pages/common/checkListGroup.js?v='+ GV.JS_VERSION);

	
	//取消喜欢
	$('a.J_unlike').on('click', function(e){
		e.preventDefault();
		var $this = $(this);
		$('body').trigger('setCustomPost', [$this]);

		Wind.Util.ajaxMaskShow();
		$.post($this.data('uri'), function(data){
			Wind.Util.ajaxMaskRemove();
			if(data.state === 'success') {
				$this.parents('dl').slideUp('slow', function(){
					$(this).remove();

					//一页取消完
					if($('#J_like_ul').children('dl').length === 0) {
						location.reload();
					}
				});
			}else{
				//global.js
				Wind.Util.resultTip({
					error : true,
					msg : data.message,
					follow : $this
				});
			}
		}, 'json');
		
	});
	
});
</script>

</body>
</html>