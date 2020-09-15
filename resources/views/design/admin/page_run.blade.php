<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">	
	<div class="nav">
		<ul class="cc">
			<li class="current"><a href="{{ url('design/page/run') }}">系统页面</a></li>
			<li><a href="{{ url('design/portal/run') }}">自定义页面</a></li>
		</ul>
	</div>
	<div class="table_list">
		<table width="100%">
			<colgroup>

				<col width="160">
			</colgroup>
			<thead>
				<tr>
	
					<td>页面名称</td>
					<td>操作</td>
				</tr>
			</thead>

@foreach ($list as $v)

			<tr>
				<td><a href="{{ $v['url'] }}" target="_blank">{{ $v['page_name'] }}</a></td>
				<td>
					<a href="{{ $v['url'] }}" class="mr5" target="_blank">[查看]</a>
					<a href="{{ url('design/permissions/page?id=' . $v['page_id']) }}" class="mr5">[权限管理]</a>
					<a href="{{ url('design/module/run?pageid=' . $v['page_id']) }}" class="mr5">[模块管理]</a>
					<a href="{{ url('design/page/doclear') }}" class="mr5 J_ajax_del" data-pdata="{'id': {{ $v['page_id'] }}}" data-msg="您确定要清空页面上的所有模块？<br>清空后将不可恢复！">[清空模块]</a>
					
				</td>
			</tr>
			<!--# } #-->
		</table>
	</div>
	<div style="padding:0 10px 15px;"><page tpl='TPL:common.page'  total="$totalpage" page="$page" per="$perpage" count="$count" url="design/page/run" args="$args"/></div>
	

		
</div>
@include('admin.common.footer')
</body>
</html>