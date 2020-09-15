<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body class="body_none" style="width:355px;">
	<form class="J_ajaxForm" data-role="list" action="{{ url('appcenter/develop/doEditHook') }}" method="post">
	<div class="pop_cont pop_table" style="height:auto;padding-top:0;">
		<table width="100%">
			<tr>
				<th>hook名称</th>
				<td>
				<input type="hidden" name="alias" value="{{ $alias }}">
				<select name="hook_name" class="select_4">

@foreach($hooks as $v)
list($desc) = explode("\r\n", $v['document']);
				#-->
				<option value="{{ $v['name'] }}">{{ $v['name'] }}({$desc})</option>
				<!--# } #-->
				</select>
				</td>
			</tr>
		</table>
	</div>
	<div class="pop_bottom">
		<button class="btn fr" id="J_dialog_close" type="button">取消</button>
		<button type="submit" class="btn btn_submit J_ajax_submit_btn fr mr10">提交</button>
	</div>
	</form>
	@include('admin.common.footer')
</body>
</html>
