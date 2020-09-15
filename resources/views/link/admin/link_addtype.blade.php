<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body class="body_none" style="width:420px;">

<!--==============================添加分类================================-->
	<form class="J_ajaxForm" data-role="list" action="{{ url('link/link/doAddType') }}" method="post">
		<div class="pop_cont pop_table" style="height:auto;">
			<table width="100%">
				<tr>
					<th>分类名称</th>
					<td><span class="must_red">*</span><input name="typename" type="text" class="input length_5"><p class="gray">最多不超过6个字</p></td>
				</tr>
				<tr>
					<th>显示顺序</th>
					<td><input name="vieworder" type="number" class="input length_5"></td>
				</tr>
			</table>
		</div>
		<div class="pop_bottom">
			<button type="submit" class="btn btn_submit J_ajax_submit_btn">提交</button>
		</div>
	</form>
	@include('admin.common.footer')
</body>
</html>
