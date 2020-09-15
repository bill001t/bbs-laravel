<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<!--# 
$filePath = Wind::getRealPath('ADMIN:conf.openplatformurl.php', true);
$openPlatformUrl = Wind::getComponent('configParser')->parse($filePath);
#-->
<div class="wrap">
	<div id="home_toptip"></div>
	<h2 class="h_a">系统信息</h2>
	<div class="home_info">
		<ul>
			<li>
				<em>软件版本</em>
				<span>{{ $sysinfo['wind_version'] }} <a href="http://www.phpwind.com/product.html" target="_blank">查看最新版本</a></span>
			</li>
			<!-- 
			<li>
				<em>操作系统</em>
				<span>WINNT</span>
			</li>
			 -->
			<li>
				<em>PHP版本</em>
				<span>{{ $sysinfo['php_version'] }}</span>
			</li>
			<li>
				<em>MYSQL版本</em>
				<span>{{ $sysinfo['mysql_version'] }}</span>
			</li>
			<li>
				<em>服务器端信息</em>
				<span>{{ $sysinfo['server_software'] }}</span>
			</li>
			<li>
				<em>最大上传限制</em>
				<span>{{ $sysinfo['max_upload'] }}</span>
			</li>
			<li>
				<em>最大执行时间</em>
				<span>{{ $sysinfo['max_excute_time'] }}</span>
			</li>
			<li>
				<em>邮件支持模式</em>
				<span>{{ $sysinfo['sys_mail'] }}</span>
			</li>
		</ul>
	</div>
	<h2 class="h_a">版权声明</h2>
	<div class="home_info" id="home_devteam">
        <ul> 
            <li><em>版权所有</em><span>www.phpwind.com</span></li>
            <li><em>用户协议</em><span><a href="http://www.phpwind.com/law.html" target="_blank">查看用户协议</a></span></li>
        </ul>
    </div>
</div>
<!--升级提示-->
<div id="J_system_update" style="display:none" class="system_update">
	您正在使用旧版本的phpwind，为了获得更好的体验，请升级至最新版本。<a href="">立即升级</a>
</div>
@include('admin.common.footer')
<script>
$("#btn_submit").click(function(){
	$("#tips_success").fadeTo(500,1);
});
//获取升级信息通知
$.ajax({
    url: "{{ url('pwadmin/home/notice') }}",
    dataType: "json",
    success: function (data) {
    	var r = data.html;
    	if (r.notice) {
    		$('#J_system_update').show();
    		$('#J_system_update').html(r.notice);
    	}
    	var tabframe_trigger = $('a.J_tabframe_trigger');
    	if(tabframe_trigger.length) {
    		try{
    			var _SUBMENU_CONFIG = parent.window.SUBMENU_CONFIG;		//导航数据

    			tabframe_trigger.on('click', function(e){
    				e.preventDefault();
    				var $this = $(this),
    					id = $this.data('id'),						//id
    					par = $this.data('parent'),					//父导航id
    					level = parseInt($this.data('level'));		//二级三级导航标识

    				parent.window.eachSubmenu(_SUBMENU_CONFIG, id, par, level, this.href);
    			});
    		}catch(e){
    			$.error(e);
    		}
    	}
    },
    error: function () {
    }
});

</script>
<!--# 
$siteUrl = $_SERVER ['HTTP_HOST'];
$ip = Wind::getApp()->getRequest()->getClientIp();
$ts = PW::getTime();
#-->

<!--script src="{{ $openPlatformUrl }}sitepush.php?a=push&siteurl={$siteUrl}&ip={$ip}&ts={{ $ts }}" charset="UTF-8"></script-->
</body>
</html>
