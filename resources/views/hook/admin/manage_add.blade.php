<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body class="body_none" style="width:370px;">
	<form class="J_ajaxForm" data-role="list" action="{{ url('hook/manage/doAdd') }}" method="post">
	<div class="pop_cont pop_table" style="height:auto;padding-top:0;">
		<table width="100%">
			<tr>
				<th>hook名称</th>
				<td><input name="name" type="text" class="input length_4"></td>
			</tr>
			<tr>
				<th>应用名称</th>
				<td>
				<select name="app" class="select_4">
				<option value="|system" selected="">system</option>

@foreach($apps as $k => $v)

				<option value="{{ $k }}|{{ $v['name'] }}">{{ $v['name'] }}</option>
				<!--# } #-->
				</select>
				</td>
			</tr>
			<tr>
				<th>使用方式/描述</th>
				<td><textarea name="dec" class="length_4"></textarea></td>
			</tr>
			<tr>
				<th>参数/返回值</th>
				<td><textarea name="param" class="length_4"></textarea></td>
			</tr>
			<tr>
				<th>接口定义</th>
				<td><textarea name="interface" class="length_4"></textarea></td>
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
