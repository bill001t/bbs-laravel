<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="nav">
		<div class="return">
		<a href="{{ url('appcenter/app/run') }}">返回上一级</a>
		</div>
		<ul class="cc">
			<li><a href="{{ url('appcenter/develop/edit?alias=' . $app['alias']) }}">设置</a></li>
			<li class="current"><a href="{{ url('appcenter/develop/edithook?alias=' . $app['alias']) }}">hook</a></li>
			<li><a href="{{ url('appcenter/develop/editxml?alias=' . $app['alias']) }}">xml</a></li>
		</ul>
	</div>
<div class="h_a">提示信息</div>
	<div class="prompt_text">
		<ul>
			<li>该功能仅供应用开发者使用，通过创建应用可以生成一个最简单的demo。开发者可以基于这个demo继续开发。</li>
			<li>应用开发文档请参考《云平台文档中心》，有疑问请至phpwind官方论坛开发者论坛交流。</li>
			<li>在应用开发之前，请先在phpwind云平台创建应用，获取“应用标识”。</li>
		</ul>
	</div>

<div class="cc mb10"><a href="{{ url('appcenter/develop/addhook?alias=' . $app['alias']) }}" class ="btn J_dialog mr10" title="添加新扩展"><span class="add"></span>添加新扩展</a>
</div>
<div class="table_list">
	<table width="100%">
		<thead>
		<tr>
			<td width="150">hook名称</td>
			<td width="340">类名</td>
			<td>方法名</td>
		</tr>
		</thead>

@foreach($myHooks as $v)

		<tr>
		<td>{{ $v['hook_name'] }}</td>
		<td>{{ $v['class'] }}</td>
		<td>{{ $v['method'] }}</td>
		</tr>
		<!--# } #-->
	</table>
</div>

@include('admin.common.footer')
</div>

</body>
</html>