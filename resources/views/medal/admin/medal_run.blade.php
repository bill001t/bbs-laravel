<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap J_check_wrap">
	<div class="nav">
		<ul class="cc">
			<li class="current"><a href="{{ url('medal/medal/run') }}">勋章管理</a></li>
			<li><a href="{{ url('medal/medal/award') }}">勋章颁发</a></li>
			<li><a href="{{ url('medal/medal/approval') }}">勋章审核</a></li>
			<li><a href="{{ url('medal/medal/set') }}">勋章设置</a></li>
		</ul>
	</div>
	<div class="mb10"><a title="添加勋章" href="{{ url('medal/medal/add') }}" class="btn J_dialog"><span class="add"></span>添加勋章</a></div>
	<form class="J_ajaxForm" data-role="list" action="{{ url('medal/medal/dorun') }}" method="post">
	<div class="table_list">
		<table width="100%">
			<colgroup>
				<col width="45">
				<col width="120">
				<col width="70">
				<col width="265">
				<col width="80">
				<col width="70">
			</colgroup>
			<thead>
				<tr>
					<td>顺序</td>
					<td>勋章名称</td>
					<td>勋章图标</td>
					<td>勋章说明</td>
					<td>发放机制</td>
					<td><label><input class="J_check_all" data-direction="x" data-checklist="J_check_x" type="checkbox">启用</label></td>
					<td>操作</td>
				</tr>
			</thead>

@foreach ($medalList as $medal)
$type = ($medal['receive_type']==1) ? '自动颁发' : '手动颁发';#-->
			<tr>
				<td><input type="nember" class="input length_0" name="orderid[{{ $medal['medal_id'] }}]" value="{{ $medal['vieworder'] }}" /></td>
				<td><input type="text" class="input length_2"  name="name[{{ $medal['medal_id'] }}]" value="{{ $medal['name'] }}" /></td>
				<td><img src="{{ $medal['medalImage'] }}" width="30" height="30" /></td>
				<td><input type="text" class="input length_4" name="descrip[{{ $medal['medal_id'] }}]"  value="{{ $medal['descrip'] }}" /></td>
				<td>{{ $type }}</td>
				<td>
					<input class="J_check" data-yid="J_check_y" data-xid="J_check_x" type="checkbox" name="isopen[{{ $medal['medal_id'] }}]" {{ App\Core\Tool::ifcheck($medal['isopen']) }} value='1'/>
					<input type="hidden" name="medalid[{{ $medal['medal_id'] }}]" value="{{ $medal['medal_id'] }}"/>
				</td>
				<td><a title="编辑勋章" href="{{ url('medal/medal/edit?id=' . $medal['medal_id']) }}" class="mr5 J_dialog">[编辑]</a>

@if($medal['medal_type'] != 1)

					<a class="J_ajax_del" data-msg="确定要删除此勋章？" href="{{ url('medal/medal/doDel') }}" data-pdata="{'id': {{ $medal['medal_id'] }}}">[删除]</a>
					<!--# } #-->
				</td>
			</tr>
		<!--# } #-->
		</table>
		<div class="p10"><page tpl='TPL:common.page'  total="$totalpage" page="$page" per="$perpage" count="$count" url="medal/medal/run"/></div>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<!--label class="mr20"><input class="J_check_all" data-direction="y" data-checklist="J_check_y" type="checkbox" />启用</label-->
			<button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>
</div>
@include('admin.common.footer')
</body>
</html>