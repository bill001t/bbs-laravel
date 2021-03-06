<!doctype html>
<html>
<head>
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
	<div class="nav cc">
		<ul class="stepstat">
			<li class="current">1.获取待更新文件列表</li>
			<li>2.下载更新</li>
			<li>3.本地文件比对</li>
			<li>4.正在升级</li>
			<li>5.升级完成</li>
		</ul>
	</div>

@if(isset($error))

	<div class="tips"> 站点状态为完全关闭后再继续升级操作，<a data-level="2" data-parent="config" data-id="config_site" href="{{ url('config/config/run') }}" class="J_tabframe_trigger">去设置</a>
		<p><a href="{{ url('appcenter/upgrade/list') }}" class="btn">继续升级</a></p>
	</div>

@else

	<div class="table_list">
		<table width="100%">
			<thead>
				<tr>
					<td>待更新文件列表</td>
					<td></td>
				</tr>
			</thead>

@foreach($list as $v)

			<tr>
				<td>{{ $v }}</td>
			</tr>
			<!--# } #-->
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">

@if($status['usezip'])

					<a href="{{ url('appcenter/upgrade/download') }}" class="btn btn_submit">下载更新(压缩包)</a>
					<!--# } #-->
					<a href="{{ url('appcenter/upgrade/download?usefile=1') }}" class="btn">下载更新(单文件，较慢)</a>
			<!--# } #-->
		</div>
	</div>
</div>
@include('admin.common.footer')
</body>
</html>
