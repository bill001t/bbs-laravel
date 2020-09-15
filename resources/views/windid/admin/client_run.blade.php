<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap J_check_wrap">	
	<div class="nav">
		<ul class="cc">
			<li class="current"><a href="{{ url('windid/client/run') }}">客户端列表</a></li>
		</ul>
	</div>
	<!-- <div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ul>
			<li>在此设置。</li>
		</ul>
	</div> -->
	<div class="cc mb10"><a class="btn J_dialog" title="添加客户端" href="{{ url('windid/client/add') }}" role="button"><span class="add"></span>添加客户端</a></div>
	<div class="table_list">
		<table width="100%">
			<colgroup>
				<col width="30">
				<col width="120">
				<col width="200">
				<col width="70">
				<col width="70">
				<col width="150">
				<col width="70">
			</colgroup>
			<thead>
				<tr>
					<td title="客户端ID">ID</td>
					<td>客户端名称</td>
					<td>通讯地址</td>
					<td>同步登录</td>
					<td>接收通知</td>
					<td>通讯密钥</td>
					<td>通讯状态</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody id="J_client_tbody">

@foreach ($list as $v): #-->
				<tr>
					<td>
$v['id']}</td>
					<td>{{ $v['name'] }}</td>
					<td>{{ $v['siteurl'] }}/{{ $v['apifile'] }}</td>
					<td>
@if($v['issyn'])
是
@else
否<!--#}#--></td>
					<td>
@if($v['isnotify'])
是
@else
否<!--#}#--></td>
					<td>{{ $v['secretkey'] }}</td>
					<td data-id="{{ $v['id'] }}" class="J_status"><img src="{{ asset('assets/images') }}/admin/content/loading.gif"></td>
					<td>
						<a href="{{ url('windid/client/edit?id=' . $v['id']) }}" class="mr10 J_dialog" title="编辑客户端">[编辑]</a>
						<a href="{{ url('windid/client/delete') }}" class="mr10 J_ajax_del" data-msg="确定要删除选中内容?" data-pdata="{'id': {{ $v['id'] }}}">[删除]</a>
					</td>
				</tr>
				<!--# endforeach; #-->
			</tbody>
		</table>
		
	</div>

</div>
@include('admin.common.footer')
<script>
var CLIENT_URL = "{{ url('windidclient/client/clientTest/') }}";
CLIENT_URL=CLIENT_URL.replace(/windid\//g,'')
Wind.js(GV.JS_ROOT +'pages/admin/common/windId.js?v='+ GV.JS_VERSION);
</script>
</body>
</html>
