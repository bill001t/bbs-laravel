<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap J_check_wrap">
	<div class="nav">
		<ul class="cc">

@if ($subcat)

			<div class="return"><a href="{{ url('admin/backup/backup/restore') }}">返回上一级</a></div>

@else

			<li><a href="{{ url('admin/backup/backup/run') }}">数据库备份</a></li>
			<li class="current"><a href="{{ url('admin/backup/backup/restore') }}">数据库还原</a></li>
			<!--# } #-->
		</ul>
	</div>
	<form action="{{ url('admin/backup/backup/batchdelete') }}" method="post" class="J_ajaxForm" >
	<div class="table_list">
		<table width="100%">
			<thead>
				<tr>
					<td><label><input class="J_check_all" data-checklist="J_check_x" data-direction="x"  type="checkbox">全选</label></td>
					<td>目录名</td>
					<td>类型</td>
					<td>版本</td>
					<td>备份时间</td>
					<td>分卷号</td>
					<td>操作</td>
				</tr>
			</thead>

@foreach ($filedb as $v)
$fileLink = $v['nosub'] ? '' : WindUrlHelper::createUrl('admin/backup/backup/subcat',array('name'=>$v['name']));
				$wholeFile = $v['nosub'] ? "$v[dir]/$v[name]" : $v[name];
			#-->
			<tr>
				<td><input class="J_check" data-xid="J_check_x" data-yid="J_check_y" type="checkbox" name="files[]" value="{{ $wholeFile }}"></td>

@if ($fileLink)

				<td><a href="{{ $fileLink }}">{{ $v['name'] }}</a></td>

@else

				<td>{{ $v['name'] }}</td>
				<!--# } #-->
				<td>{{ $v['type'] }}</td>
				<td>{{ $v['version'] }}</td>
				<td>{{ $v['time'] }}</td>
				<td>{{ $v['num'] }}</td>
				<td><a class="J_ajax_del" data-pdata="{'isdir': {{ $v['isdir'] }}, 'dir':{{ $v['dir'] }}, 'file':{{ $v['name'] }}}" data-msg="确定要导入吗？" href="{{ url('admin/backup/backup/import') }}">[导入]</a></td>
			</tr>
			<!--# } #-->
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<label class="mr10"><input type="checkbox" data-direction="y" data-checklist="J_check_y" class="J_check_all">全选</label>
			<button class="btn btn_submit J_ajax_submit_btn" type="submit">删除</button>
		</div>
	</div>
	</form>
	
</div>
@include('admin.common.footer')
</body>
</html>