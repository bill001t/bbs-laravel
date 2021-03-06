<!doctype html>
<html>
<head>
@include('admin.common.head')
<style>
body{width:440px;}
</style>
</head>
<body class="body_none">
<div>
<!-- mod start -->
<form method="post" class="J_ajaxForm" action="{{ url('nav/nav/doedit') }}" data-role="edit">
<div class="pop_cont pop_table">
	<table width="100%" style="table-layout:fixed;">
		<col width="80" />
		<col />
		<tr>
			<th>上级导航</th>
			<td>
				<span class="must_red">*</span>
				<select name="parentid" class="select_5">
					<option value=''>顶级导航</option>
					<!--#echo $navOption#-->
				</select>
			</td>
		</tr>
		<tr>
			<th>栏目名称</th>
			<td>
				<span class="must_red">*</span>
				<input type="text" name="name" class="input input_hd length_5" value="{{ $navInfo['name'] }}">
			</td>
		</tr>
		<tr>
			<th>链接地址</th>
			<td>
				<div class="mb5"><input type="text" name="link" class="input length_5" value="{{ $navInfo['link'] }}"></div>
				<div class="gray">以http://开头</div>
			</td>
		</tr>

@if($navInfo['type'] == 'my')

		<tr>
			<th>图标</th>
			<td>
				<div class="mb5"><input type="text" name="image" class="input length_5" value="{{ $navInfo['image'] }}"></div>
				<div class="gray">以http://开头,需要导航html支持</div>
			</td>
		</tr>
		<!--# } #-->
		<tr>
			<th>名称样式</th>
			<td>
				{{-- <component tpl='TPL:common.widgets.font' args='$navInfo'/> --}}
			</td>
		</tr>
		<tr>
			<th>说明</th>
			<td>
				<div class="mb5"><input type="text" name="alt" class="input length_5" value="{{ $navInfo['alt'] }}"></div>
				<div class="gray">鼠标悬浮于链接文字上时的说明内容</div>
			</td>
		</tr>
		<tr>
			<th>打开方式</th>
			<td>
				<ul class="switch_list cc">
					<li><label><input name="target" type="radio" value="0" {{ App\Core\Tool::ifcheck(!$navInfo['target']) }}><span>本窗口</span></label></li>
					<li><label><input name="target" type="radio" value="1"  {{ App\Core\Tool::ifcheck($navInfo['target']) }}><span>新窗口</span></label></li>
				</ul>
			</td>
		</tr>
		<tr>
			<th>顺序</th>
			<td>
				<input type="text" name="orderid" class="input length_5" value="{{ $navInfo['orderid'] }}">
			</td>
		</tr>

		<tr>
			<th>是否启用</th>
			<td>
				<ul class="three_list cc">
					<li><label><input name="isshow" type="checkbox" value="1"  {{ App\Core\Tool::ifcheck($navInfo['isshow']); }}>启用</label></li>
				</ul>
			</td>
		</tr>
	</table>
</div>
<!-- mod end -->
<div class="pop_bottom">
	<button class="btn fr" id="J_dialog_close" type="button">取消</button>
	<button class="btn btn_submit J_ajax_submit_btn mr10 fr" type="submit">提交</button>
		<input name="type" type="hidden" value="{{ $navInfo['type'] }}">
		<input name="navid" type="hidden" value="{{ $navInfo['navid'] }}">
</div>
</form>
<!-- end -->

</div>
@include('admin.common.footer')
</body>
</html>