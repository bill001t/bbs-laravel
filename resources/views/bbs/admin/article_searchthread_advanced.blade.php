<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body style="width:450px" class="body_none">
<!--高级管理弹窗-->
		<div class="pop_advanced_search">
		<form id="J_threadadvanced_form" method="post" action="{{ url('bbs/article/searchThread') }}">
			<div class="pop_cont">
				<ul class="cc">
					<li>
						<p>关键字：</p>
						<input type="text" name="keyword" value="{{ $args['keyword'] }}" class="input length_3" placeholder="支持帖子标题和正文搜索">
					</li>
					<li>
						<p>根据IP查找：</p>
						<input type="text" name="created_ip" value="{{ $args['created_ip'] }}" class="input length_3">
					</li>
					<li>
						<p>作者：</p>
						<input type="text" name="created_username" value="{{ $args['created_username'] }}" class="input length_3">
					</li>
					<li>
						<p>UID：</p>
						<input type="text" name="created_userid" value="{{ $args['created_userid'] }}" class="input length_3">
					</li>
					<li class="all">
						<p>主题发布时间：</p>
						<input type="text" name="time_start" value="{{ $args['time_start'] }}" class="input length_3 J_date"><span class="gap">至</span><input type="text" name="time_end" value="{{ $args['time_end'] }}"  class="input length_3 J_date">
					</li>
					<li class="all">
						<p>主题浏览人数：</p>
						<input type="text" name="hits_start" value="{{ $args['hits_start'] }}" class="input length_3"><span class="gap">至</span><input type="text" name="hits_end" value="{{ $args['hits_end'] }}" class="input length_3">
					</li>
					<li class="all">
						<p>主题回复人数：</p>
						<input type="text" name="replies_start" value="{{ $args['replies_start'] }}" class="input length_3"><span class="gap">至</span><input type="text" name="replies_end" value="{{ $args['replies_end'] }}" class="input length_3">
					</li>
					<li>
						<p>所属版块：</p>
						<select name="fid" class="select_3"><option value="0">所有版块</option>{!! $option_html !!}</select>
					</li>
					<li style="display:none">
						<p>&nbsp;</p>
						<label><input type="checkbox" name="digest" value="1"{{ $checkedDigest }}>过滤精华</label>
					</li>
				</ul>
			</div>
			<div class="pop_bottom">
				<button class="btn fr" id="J_dialog_close" type="button">取消</button>
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
		parent.window.location.href = "{{ url('bbs/article/searchthread') }}" + '&' +$('#J_threadadvanced_form').formSerialize();
	});
});
</script>
</body>
</html>