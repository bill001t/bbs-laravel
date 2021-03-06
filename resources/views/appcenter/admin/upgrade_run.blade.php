<!doctype html>
<html>
<head>

@if(!$action)

<meta http-equiv="refresh" content="2; url={{ url('appcenter/upgrade/check') }}" />
<!--# } #-->
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="nav">
		<ul class="cc">
			<li class="current"><a href="{{ url('appcenter/upgrade/run') }}">版本升级</a></li>
			<li><a href="{{ url('appcenter/fixup/run') }}">安全中心</a></li>
		</ul>
	</div>
	<div class="upgrade_page">

@if(isset($disable))

		<div class="not_content_mini">
			<i></i>通信失败，无法在线升级，请手动升级
		</div>

@else


@if($action)

		<div class="tips" style="padding:15px;">
		检测到您上次的升级操作未完成，是否继续上次操作？
		<p style="padding-bottom:10px;"><a href="{{ url('appcenter/upgrade/'.$action) }}" class="btn">继续升级</a>

@if($recheck)

		<a href="{{ url('appcenter/upgrade/check') }}" class="btn">重新检测</a></p>
		<!--# } #-->
		</div>

@else

		<div class="tips" style="padding:15px;">
			<div class="tips_loading">正在检测可升级版本，请稍候…</div>
			<p>如果您的浏览器没有自动跳转，<a href="{{ url('appcenter/upgrade/check') }}">请点击这里</a></p>
		</div>
		<!--# }} #-->
	</div>
</div>
@include('admin.common.footer')
</body>
<script>

</script>
</html>