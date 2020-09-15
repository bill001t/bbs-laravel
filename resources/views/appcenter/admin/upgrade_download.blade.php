<!doctype html>
<html>
<head>

@if(isset($step) && !$msg)

<meta http-equiv="refresh" content="1; url={{ url('appcenter/upgrade/download?usezip=0') }}" />
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
	<div class="cc">
		<ul class="stepstat">
			<li>1.获取待更新文件列表</li>
			<li class="current">2.下载更新</li>
			<li>3.本地文件比对</li>
			<li>4.正在升级</li>
			<li>5.升级完成</li>
		</ul>
	</div>

@if(isset($step))

	<div class="table_list">
		<table width="100%">
			<thead>
				<tr>
					<td>待更新文件列表</td>
					<td></td>
				</tr>
			</thead>

@foreach($fileList as $k => $v)

			<tr>
				<td>{{ $v }}</td>

@if($k < $step)

				<td><span class="green">成功</span></td>

@else

				<td><span class="red">失败</span></td>
				<!--# } #-->
			</tr>
			<!--# } #-->
			<tr>
				<td colspan="2"> 升级文件存放目录： data/upgrade/{$version} </td>
			</tr>
			<tr>

@if (isset($msg))

				<td><a href="{{ url('appcenter/upgrade/download') }}" class="btn">下载更新</a></td>
				<td><span class="tips_error">{{ $msg }}</span></td>

@else

				<td><button disabled="disabled">下载更新</button></td>
				<td> 下载进度：{@intval(($step * 100) / count($fileList))}% </td>
				<!--# } #-->
			</tr>
		</table>
	</div>
	<!--# } #-->
	<!--# else{ #-->
	<div class="tips_block"> <span class="tips_error">{{ $msg }}</span> </div>
	<!--# } #-->
</div>
@include('admin.common.footer')
</body>
<script>

</script>
</html>