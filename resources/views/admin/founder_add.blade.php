<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body class="body_none founder_pop" style="width:350px;">
<form class="J_ajaxForm" action="{{ url('founder/doAdd') }}" method="post">
<div class="pop_cont pop_table" style="height:auto;">
	<table width="100%">
			<tbody>
			<tr>
				<th width="60">用户名</th>

@if($uid)

				<td><input type="text" class="input length_4" name="username" value="{{ $username }}" readonly="readonly"></td>

@else

				<td><input type="text" class="input length_4" name="username" value="{{ $username }}"></td>
				<!--# } #-->
			</tr>
			<tr>
				<th>密码</th>
				<td><input type="text" class="input length_4" name="password"></td>
			</tr>
			<tr>
				<th>电子邮箱</th>
				<td>
					<input name="email" type="email" class="input length_4" value="{{ $email }}">
				</td>
			</tr>
			</tbody>
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