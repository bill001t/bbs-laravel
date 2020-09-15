<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body class="body_none founder_pop" style="width:350px;">
<form class="J_ajaxForm" action="{{ url('founder/doEdit') }}" method="post">
<div class="pop_cont pop_table" style="height:auto;">
	<table width="100%">
		<tr>
			<th width="60">用户名</th>
			<td><input name="username" type="text" class="input length_4" value="{{ $username }}" readonly="readonly"></td>
		</tr>
		<tr>
			<th>新密码</th>
			<td><input name="password" type="text" class="input length_4" ></td>
		</tr>
		<tr>
			<th>电子邮箱</th>
			<td>
				<input name="email" type="email" class="input length_4" value="{{ $email }}">
			</td>
		</tr>
	</table>
</div>
<div class="pop_bottom">
	<button type="button" class="btn fr" id="J_dialog_close">取消</button>
	<button type="submit" class="btn btn_submit J_ajax_submit_btn fr mr10">提交</button>
</div>
</form>
@include('admin.common.footer')
</body>
</html>