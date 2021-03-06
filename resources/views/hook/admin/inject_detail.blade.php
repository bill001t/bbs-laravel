<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="h_a">使用说明</div>
	<div class="prompt_text">
		<ol>
			<li>计划任务是一项使系统在规定时间自动执行某些特定任务的功能。</li>
			<li>合理设置执行时间，能有效地为服务器减轻负担。</li>
			<li>对于如“期限头衔自动回收”此类需每天更新的计划任务建议不设置月和周的更新时间，否则对于购买一或几天的头衔的用户可能在一周或者一月内都无法回收头衔。</li>
		</ol>
	</div>
	<div class="h_a">基本信息</div>
	<div class="table_list">
		<table width="100%">
			<thead>
				<tr>
					<td>别名</td>
					<td>类名</td>
					<td>方法名</td>
					<td>loadway</td>
					<td>挂载条件</td>
					<td>创建时间</td>
				</tr>
			</thead>
			<tr>
				<td>{{ $inject['alias'] }}</td>
				<td>{{ $inject['class'] }}</td>
				<td>{{ $inject['method'] }}</td>
				<td>{{ $inject['loadway'] }}</td>
				<td>{{ $inject['expression'] }}</td>
				<td>{{ App\Core\Tool::time2str($inject['created_time']) }}</td>
			</tr>
		</table>
	</div>
	<div class="h_a">详细描述</div>
	<div class="prompt_text"> {{ $inject['description'] }}
	</div>
</div>
@include('admin.common.footer')
</body>
</html>