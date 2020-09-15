<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

<!-- start -->
<div class="nav">
	<ul class="cc">
		<li class="current"><a href="">安全优化</a></li>
		<li><a href="">123</a></li>
		<li><a href="">21</a></li>
	</ul>
</div>
<div class="h_a">功能说明</div>
<div class="prompt_text">
说明
</div>
<form method="post" class="J_ajaxForm">
<div class="h_a">论坛首页</div>
<div class="table_full">
	<table width="100%">
		<col class="th" />
		<col width="400" />
		<col />
		<tr>
			<th>title [标题]</th>
			<td>
				<input name="infoName" type="text" class="input length_5" value="">
				<div class="pop_seo">
					<div class="hd">
						<a href="#" class="close">关闭</a>
						<strong>可以使用的代码（点击插入）：</strong>
					</div>
					<div class="ct">
						<a href="">{{ sitename }}</a>
						<a href="">{{ forumname }}</a>
					</div>
				</div>
			</td>
			<td><div class="fun_tips"></div></td>
		</tr>
		<tr>
			<th>description [描述]</th>
			<td>
				<input name="infoName" type="text" class="input length_5" value="">
			</td>
			<td><div class="fun_tips"></div></td>
		</tr>
		<tr>
			<th>keywords [关键字]</th>
			<td>
				<input name="infoName" type="text" class="input length_5" value="">
			</td>
			<td><div class="fun_tips"></div></td>
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