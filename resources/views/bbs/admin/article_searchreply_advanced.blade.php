<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body style="width:440px;" class="body_none">
<!--高级管理弹窗-->
		<div class="pop_advanced_search">
		<form id="J_threadadvanced_form" method="post" action="{{ url('bbs/article/searchReply') }}">
			<div class="pop_cont" style="">
				<ul class="cc">
					<li>
						<p>关键字：</p>
						<input type="text" name="keyword" value="{{ $args['keyword'] }}"  class="input length_3" placeholder="支持帖子标题和正文搜索">
					</li>
					<li>
						<p>根据IP查找：</p>
						<input type="text" name="created_ip" value="{{ $args['created_ip'] }}"  class="input length_3">
					</li>
					<li>
						<p>作者：</p>
						<input type="text" name="created_username" value="{{ $args['created_username'] }}"  class="input length_3">
					</li>
					<li>
						<p>UID：</p>
						<input type="text"  name="created_userid" value="{{ $args['created_userid'] }}" class="input length_3">
					</li>
					<li class="all">
						<p>主题回复时间：</p>
						<input type="text"  name="created_time_start" value="{{ $args['created_time_start'] }}" class="J_date input length_3"><span class="gap">至</span><input type="text" name="created_time_end" value="{{ $args['created_time_end'] }}"  class="J_date input length_3">
					</li>
					<li>
						<p>所属主题tid：</p>
						<input type="text"  name="tid" value="{{ $args['tid'] }}" class="input length_3">
					</li>
					<li>
						<p>所属版块：</p>
						<select name="fid" class="select_3"><option value="0">所有版块</option>{!! $option_html !!}</select>
					</li>
				</ul>
			</div>
			<div class="pop_bottom">
				<button class="btn fr mr10" id="J_dialog_close" type="button">取消</button>
				<button type="submit" class="btn btn_submit fr mr10" id="J_threadadvanced_btn">搜索</button>
			</div>
			</form>
		</div>
<!--结束-->
@include('admin.common.footer')
<script>
Wind.use('ajaxForm', function(){
	var keyword = $('input[name=keyword]');
	$('#J_threadadvanced_btn').on('click', function(e){
		e.preventDefault();
		if($.browser.msie) {
			//ie 清空placeholder
			if(keyword.val() == keyword.attr('placeholder')) {
				keyword.val('');
			}
		}

		//序列化 写入url
		parent.window.location.href = "{{ url('bbs/article/searchreply') }}" + '&' +$('#J_threadadvanced_form').formSerialize()
	});
});
</script>
</body>
</html>