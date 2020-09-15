<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">	
	
	
<!--添加模板-->

	<div class="nav">
		<div class="return"><a href="{{ url('design/module/run?type=' . $isapi) }}">返回上一级</a></div>
		<ul class="cc">
			<li class="current"><a href="{{ url('design/data/run?moduleid=' . $moduleid) }}">显示数据</a></li>
			<li><a href="{{ url('design/data/push?moduleid=' . $moduleid) }}">推送审核</a></li>
			<li><a href="{{ url('design/property/edit?moduleid=' . $moduleid) }}">属性</a></li>
			<li><a href="{{ url('design/template/edit?moduleid=' . $moduleid) }}">模板</a></li>
		</ul>
	</div>
	<div class="h_a">显示数据</div>
	<form class="J_ajaxForm" action="{{ url('design/data/batchEditData') }}" method="post">
	<div class="design_ct">
	@include('design.segment.data_run')
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit J_ajax_submit_btn" type="submit" >提交</button>
			<input type="hidden" name="moduleid" value="{{ $moduleid }}">
		</div>
	</div>
	</form>

	
</div>
@include('admin.common.footer')
<script>
Wind.use('dialog', function(){
	//删除 屏蔽
	$('a.J_design_data_ajax').on('click',function(e) {
		e.preventDefault();
		var $this = $(this),
			href = $this.prop('href'),
			msg = ($this.text().indexOf('删除') > 0 ? '确定要删除本条吗？' : '确定要屏蔽本条吗？'),
			pdata = $this.data('pdata');
		var params = {
			message	: msg, 
			type	: 'confirm', 
			isMask	: false,
			follow	: $(this),//跟随触发事件的元素显示
			onOk	: function() {
				$.ajax({
					url: href,
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
			}
		};
		Wind.dialog(params);
	});
});
</script>
</body>
</html>
