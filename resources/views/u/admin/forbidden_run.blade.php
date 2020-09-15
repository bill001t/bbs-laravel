<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

<!-- start -->
<div class="nav">
	<div class="return"><a href="{{ url('u/manage/run') }}">返回上一级</a></div>
	<ul class="cc">
		<li class="current"><a href="{{ url('u/forbidden/run') }}">手动禁止</a></li>
		<li><a href="{{ url('u/forbidden/auto') }}">自动禁止</a></li>
		<li><a href="{{ url('u/forbidden/list') }}">解除禁止</a></li>
	</ul>
</div>
<form class="J_ajaxForm" data-role="list" method="post" action="{{ url('u/forbidden/dorun') }}">
<div class="h_a">会员禁止</div>
<div class="table_full">
	<table width="100%">
		<col class="th" />
		<col width="400" />
		<col />
		<tr>
			<th>禁止对象</th>
			<td>
				<ul class="three_list cc mb5">
					<li><label><input type="radio" name="key" value="2" checked><span>用户名</span></label></li>
					<li><label><input type="radio" name="key" value="1"><span>UID</span></label></li>
				</ul>
				<input type="text" class="input length_5" name="value" value="{{ $value }}">
			</td>
			<td><div class="fun_tips">多个对象之间用英文半角逗号","分隔。</div></td>
		</tr>
		<tr>
			<th>有效期至</th>
			<td><input type="text" value="" name="end_time" class="input mr5 length_5 J_datetime date"></td>
			<td><div class="fun_tips">输入0表示永久禁止</div></td>
		</tr>
		<tr>
			<th>禁止类型</th>
			<td>
				<ul class="single_list cc">

@foreach ($types as $key => $value)

					<li><label><input type="checkbox" name="type[]" value="{{ $key }}"><span>{{ $value['title'] }}</span></label></li>
<!--#}#-->
				</ul>
			</td>
			<td><div class="fun_tips">
					<p>禁止发布：将禁止用户在全站发表内容的权限</p>
					<p>禁止头像：将清空用户当前的头像，并默认显示已禁止的头像，超过设定的期限后，显示网站默认头像</p>
					<p>禁止签名：禁止用户签名后，签名栏显示管理员设置的禁止理由，超过设定的期限后，签名栏默认为空</p>
			</div></td>
		</tr>
		<tr>
			<th>禁止理由</th>
			<td>
				<textarea class="length_5" name="reason"></textarea>
			</td>
			<td><div class="fun_tips">必填</div></td>
		</tr>
	</table>
</div>

<div class="btn_wrap">
	<div class="btn_wrap_pd">
		<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
	</div>
</div>
</form>

</div>
@include('admin.common.footer')
</body>
</html>