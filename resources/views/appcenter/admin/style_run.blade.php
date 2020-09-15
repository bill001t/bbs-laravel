<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
<!--默认风格列表-->
	<div class="nav">
		<ul class="cc">

@foreach($addons as $key => $value)

			<li class="{{ App\Core\Tool::isCurrent($key==$type) }}"><a href="{{ url('appcenter/style/run?type=' . $key) }}">{{ $value[0] }}</a></li>
			<!--# } #-->
			<li><a href="{{ url('appcenter/style/install') }}">本地安装</a></li>
			<li><a href="{{ url('appcenter/style/manage') }}">界面管理</a></li>

@if(app('APPCENTER:service.srv.PwDebugApplication')->inDevMode1())

			<li><a href="{{ url('appcenter/style/generate') }}">开发助手</a></li>
			<!--# } #-->
		</ul>
	</div>
	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ul>
			<li>整站模板：只能默认选择一套。</li>
			<li>个人空间：安装后前台全部展示，具体显示靠用户选择。</li>
			<li>版块模板：安装后，需要到 <span class="org">论坛->版块管理->编辑->版块风格</span> 选择性展示。</li>
			<li>门户模板：需要到 <span class="org">门户->页面管理->创建自定义页面</span>，然后通过页面模块管理，设置模板</li>
		</ul>
	</div>
	<div id="J_style_tip" style="display:none;" class="tips_light">发现新风格，<a id="J_scan" href="">安装</a></div>
	<div class="mb10 cc">共 <span class="org">{{ $count }}</span> 套模板</div>
	
	<div class="design_page">
		<ul class="cc">
		<!--#
		$app_ids = array();
		foreach($styles as $id => $v){#-->
			<li>
			<!--#
			$logo = $v['logo'];
			if($logo && (strpos($logo,'http://')===false)) {
				$args = array(Core::url()->themes, $addons[$type][1], $v['alias'], $logo);
				$logo = implode('/', $args);
			}
			#-->
				<div class="img"><img src="{{ $logo }}" width="210" height="160" lt="{{ $v['alias'] }}"></div>
				<div class="title" title="{{ $v['name'] }}">{{ $v['name'] }}</div>
				<div class="descrip" title="{{ $v['description'] }}">{{ $v['description'] ? $v['description'] : '这家伙很懒' }}</div>
				<div class="type"><span class="version">风格版本:{{ $v['version'] }}</span>
				<span class="author">作者：

@if($v['website'])

				<a href="{{ $v['website'] }}" target="_blank">{{ $v['author_name'] ? $v['author_name'] : '请叫我雷锋' }}</a>

@else

				{$v['author_name'] ? $v['author_name'] : '请叫我雷锋'}
				<!--# } #-->
				</span></div>

@if($v['app_id'][0] !== 'L')
$app_ids[] = $v['app_id']; }#-->
				<div class="ft">

@if($type != 'forum' && $type != 'portal')


@if($v['iscurrent'] == 1)

					<span class="org">默认模板</span>

@else

					<a href="{{ url('appcenter/style/default') }}" class="J_ajax_refresh" data-pdata="{'styleid': '{{ $id }}'}">设为默认</a><a data-msg="确定要卸载吗？" href="{{ url('appcenter/style/uninstall') }}" class="J_ajax_del" data-pdata="{'styleid': '{{ $id }}'}">卸载</a>
					<!--# }if($type == 'site'){ #-->
					<!-- <a target="_blank" href="{{ url('appcenter/style/preview?styleid=' . $id) }}">预览</a> -->
					<!--# }} else { #-->
					<a href="{{ url('appcenter/style/uninstall') }}" class="J_ajax_del" data-pdata="{'styleid': '{{ $id }}'}">卸载</a>
					<!--# } if(Core::C('site', 'debug') && $type != 'portal'){ #-->
					<a href="{{ url('appcenter/style/export?type=' . $type . '&alias=' . $v['alias']) }}">导出</a>
					<!--# } #-->
					<a id="app_update_{{ $v['app_id'] }}" href="{{ url('appcenter/app/upgrade?app_id=' . $v['app_id']) }}" class="J_ajax_upgrade" data-msg="确定要升级吗？升级将覆盖原先应用" style="display:none">升级</a>
				</div>
			</li>
			<!--#}#-->
		</ul>
	</div>
	<page tpl='TPL:common.page' page='$page' count='$count' per='$perpage' url='appcenter/style/run?type=$type' />
</div>
@include('admin.common.footer')
</body>
<script>
var url = '{{ url('appcenter/app/refresh') }}',
app_ids = '{{ @implode(",", $app_ids) }}';
if(app_ids) {
	$.ajax({
        url: url,
        data: {app_ids : app_ids},
        type: "POST",
        dataType: "json",
        success: function (data) {
        	$.each(data.data, function(k,v){
        		v.update_url && $('#app_update_'+k).show();
        	});
        },
        error: function () {
        }
    });
}
Wind.use('dialog',function() {
	
	$('.J_ajax_upgrade').on('click',function(e) {
		e.preventDefault();
		var $this = $(this), url = $this.attr('href');
		var params = {
				message	: '确定要升级吗？升级将覆盖当前应用',
				type	: 'confirm',
				isMask	: false,
				follow	: $(this),//跟随触发事件的元素显示
				onOk	: function() {
					$.ajax({
				        url: url,
				        type: "GET",
				        dataType: "JSON",
				        beforeSend: function ( xhr ) {
				        	$this.text('正在升级。。。').prop('disabled',true).addClass('disabled tips_loading');
				        },
				        success: function(data) {
				        	$this.removeClass('disabled tips_loading').text('升级').removeProp('disabled');
				        	if(data.state === 'success') {
								if(data.referer) {
									location.href = decodeURIComponent(data.referer);
								}else {
									reloadPage(window);
								}
							}else if( data.state === 'fail' ) {
								Wind.dialog.alert(data.message);
							}
				        }
				    });
				}
			};
			Wind.dialog(params); 
		
	});
	
});
</script>
</html>