<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap J_check_wrap">	
	
	
<!--添加模板-->

	<div class="nav">
		<div class="return"><a href="{{ url('design/module/run?type=' . $isapi) }}">返回上一级</a></div>
		<ul class="cc">
			<li><a href="{{ url('design/data/run?moduleid=' . $moduleid) }}">显示数据</a></li>
			<li  class="current"><a href="{{ url('design/data/push?moduleid=' . $moduleid) }}">推送12审核</a></li>
			<li><a href="{{ url('design/property/edit?moduleid=' . $moduleid) }}">属性</a></li>
			<li><a href="{{ url('design/template/edit?moduleid=' . $moduleid) }}">模板</a></li>
		</ul>
	</div>
	<div class="h_a">推送数据</div>
	<form method="post" class="J_ajaxForm" action="{{ url('design/data/batchCheckPush') }}" >
	<div class="design_ct">
	@include('design.segment.data_push')
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit J_ajax_submit_btn" type="submit" >通过</button>
			<button type="button" class="btn J_ajax_submit_btn" data-action="{{ url('design/data/batchDelPush') }}">拒绝</button>
			<input type="hidden" name="moduleid" value="{{ $moduleid }}">
		</div>
	</div>
	</form>

	
</div>
@include('admin.common.footer')
<script>
Wind.use('dialog', function(){
	//通过 拒绝
	$('a.J_data_push').on('click', function(e){
		e.preventDefault();
		var pdata = $(this).data('pdata');
		$.ajax({
			url: this.href,
			type : 'post',
			dataType: 'json',
			data: function(){
				if(pdata) {
					return $.parseJSON(pdata.replace(/'/g, '"'));
				}
			}(),
			success: function(data){
				if(data.state === 'success') {
					if(data.referer) {
						location.href = decodeURIComponent(data.referer);
					}else {
						reloadPage(window);
					}
				}else if( data.state === 'fail' ) {
					Wind.dialog.alert(data.message);
				}
			}
		});
	});
});
</script>
</body>
</html>