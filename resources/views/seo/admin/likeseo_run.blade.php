<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

<!-- start -->
@include('seoTab')
<div class="h_a">功能说明</div>
<div class="prompt_text">
SEO信息中可以直接输入文字，也可以使用代码。
<p>可以使用的代码包括：</p>
<ol>
<li>全站名称:{sitename}（应用范围：所有位置）</li>
</ol>

以上标签（必须包含大括号"{{  }}"）可以通过添加在下面来优化页面SEO设置，多个标签之间可以用半角连字符"-"、半角","或半角空格隔开。留空为默认SEO设置

</div>
<form action="{{ url('/seo/manage/doRun?mod=like') }}" method="post" class="J_ajaxForm">

@foreach($pages as $alias => $title)

<div class="h_a">{{ $title }}</div>

	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>title [标题]</th>
				<td>
					<input data-id="{{ $alias }}" name="seo[{{ $alias }}][0][title]" type="text" class="input length_5 J_seo_input" value="{{ $seo[$alias][0]['title'] }}">
				</td>
				<td><div class="fun_tips"></div></td>
			</tr>
			<tr>
				<th>description [描述]</th>
				<td>
					<input data-id="{{ $alias }}" name="seo[{{ $alias }}][0][description]" type="text" class="input length_5 J_seo_input" value="{{ $seo[$alias][0]['description'] }}">
				</td>
				<td><div class="fun_tips"></div></td>
			</tr>
			<tr>
				<th>keywords [关键字]</th>
				<td>
					<input data-id="{{ $alias }}" name="seo[{{ $alias }}][0][keywords]" type="text" class="input length_5 J_seo_input" value="{{ $seo[$alias][0]['keywords'] }}">
				</td>
				<td><div class="fun_tips"></div></td>
			</tr>
		</table>
	</div>
	<!--# } #-->
<div class="btn_wrap">
	<div class="btn_wrap_pd">
		<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
	</div>
</div>
</form>

</div>
@include('seofooter')
</body>
</html>