<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body class="body_none" style="width:408px;">

<!--==============================添加链接================================-->
	<form id="J_link_add_form" class="J_ajaxForm" data-role="list" action="{{ url('link/link/doadd') }}" method="post">
	<div class="pop_cont pop_table" style="height:auto;">
		<table width="100%">
			<tr>
				<th>站点名称</th>
				<td><span class="must_red">*</span><input name="name" type="text" class="input length_5"><p class="gray">最多不超过15个字</p></td>
			</tr>
			<tr>
				<th>站点地址</th>
				<td><span class="must_red">*</span><input name="url" type="text" class="input length_5"></td>
			</tr>
			<tr>
				<th>站点LOGO</th>
				<td><input name="logo" type="text" class="input length_5"><p class="gray">请输入LOGO的图片地址，设置后自动为图片链接</p></td>
			</tr>
			<tr>
				<th>显示顺序</th>
				<td><input name="vieworder" type="number" class="input length_5"></td>
			</tr>
			<tr>
				<th>联系方式</th>
				<td><input name="contact" type="text" class="input length_5"></td>
			</tr>
			<tr>
				<th>链接分类</th>
				<td>
					<span class="must_red">*</span>
					<ul class="three_list cc">

@foreach ($types as $value)

						<li><label><input type="checkbox" name="typeids[]" value="{{ $value['typeid'] }}">{{ $value['typename'] }}</label></li>
					<!--# } #-->
					</ul>
				</td>
			</tr>
		</table>
	</div>
	<input name="ifcheck" type="hidden" value="1">
	<!-- <div id="J_submit_tips" class="tips_error" style="display:none;"></div> -->
	<div class="pop_bottom">
		<button class="btn fr" id="J_dialog_close" type="button">取消</button>
		<button type="submit" class="btn btn_submit J_ajax_submit_btn fr mr10">提交</button>
	</div>
	</form>
	@include('admin.common.footer')
<script type="text/javascript">
/*Wind.use('ajaxForm', function () {
	var submit_tips = $('#J_submit_tips');
	$('#J_link_add_form').ajaxForm({
		dataType	: 'json',
		success     : function(data){
			submit_tips.text(data.message).slideDown('fast');
				if (data.state === 'success') {
					setTimeout(function(){
						window.parent.location.href = window.parent.location.pathname + window.parent.location.search;
						window.parent.Wind.dialog.closeAll();
				}, 1500);
			}
		}
	});
});*/
</script>
</body>
</html>
