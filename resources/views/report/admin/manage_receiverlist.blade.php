<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="nav">
		<ul class="cc">
			<li><a href="{{ url('report/manage/run') }}">未处理</a></li>
			<li {$ifcheck}><a href="{{ url('report/manage/run?ifcheck=1') }}">已处理</a></li>
			<li class="current"><a href="{{ url('report/manage/receiverlist') }}">举报提醒</a></li>
		</ul>
	</div>
	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ol>
			<li>需手动添加用户，列表中的用户会接收到举报提醒信息，处理举报要有对应的管理权限，需要到相应的"用户-><a href="{{ url('u/groups/run') }}" class="J_linkframe_trigger">用户组权限</a>"中设置。</li>
		</ol>
	</div>
	<div class="h_a">接收提醒用户列表</div>
	<div class="table_purview">
		<table width="100%">
			<colgroup>
				<col width="160">
				<col width="160">
			</colgroup>
			<tr class="hd">
				<th>用户名</th>
				<td>用户组</td>
				<td>操作</td>
			</tr>

@foreach ($receivers as $v)

			<tr>
				<th>{{ $v['username'] }}</th>
				<td>{{ $v['group'] }}</td>
				<td><a href="{{ url('report/manage/deleteReceiver') }}" class="J_ajax_del" data-pdata="{'uid': {{ $v['uid'] }}}">[删除]</a></td>
			</tr>
			<!--# } #-->
		</table>
		<form class="J_ajaxForm" action="{{ url('report/manage/addReceiver') }}" method="post" >
		<table width="100%">
			<colgroup>
				<col width="160">
				<col width="160">
			</colgroup>
			<tr>
				<th><input type="text" class="input length_2" name="username"></th>
				<td>--</td>
				<td><button type="submit" class="btn J_ajax_submit_btn mr10"><span class="add"></span>添加用户</button></td>
				</tr>
			</table>
		</form>
	</div>
</div>
@include('admin.common.footer')
</body>
</html>