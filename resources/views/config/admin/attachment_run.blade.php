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
		<li class="current"><a href="{{ url('/config/attachment/run') }}">附件设置</a></li>
		<li><a href="{{ url('/config/attachment/storage') }}">附件存储</a></li>
		<li><a href="{{ url('/config/attachment/thumb') }}">附件缩略</a></li>
		<!-- <li><a href="{{ url('/config/storage/run') }}">头像存储</a></li> -->
	</ul>
</div>

<form method="post" class="J_ajaxForm" action="{{ url('/config/attachment/dorun') }}" data-role="list">
	<div class="h_a">基本设置</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>附件路径控制</th>
				<td><input name="pathsize" type="text" class="input length_5 mr5" value="{{ $config['pathsize'] }}">KB</td>
				<td><div class="fun_tips">当附件超过设定值时，系统将直接读取附件真实路径。此功能可帮助减少系统php进程消耗，亦可帮助转移附件流量并减轻附件服务器负担。请根据站点附件实际情况进行设置。0或留空表示不限制。 </div></td>
			</tr>
			<tr>
				<th>单次附件上传个数限制</th>
				<td><input name="attachnum" type="text" class="input length_5 mr5" value="{{ $config['attachnum'] }}"></td>
				<td><div class="fun_tips"></div></td>
			</tr>
			<tr>
				<th>附件类型和尺寸控制</th>
				<td>
					<div class="cross">
						<ul id="J_ul_list_attachment" class="J_ul_list_public">
							<li>
								<span class="span_3">后缀名(小写)</span>
								<span class="span_3">最大值(KB)</span>
							</li>

@foreach($config['extsize'] as $key => $value)

							<li>
								<span class="span_3"><input name="extsize[{{ $key }}][ext]" type="text" class="input length_2" value="{{ $key }}"></span>
								<span class="span_4"><input name="extsize[{{ $key }}][size]" type="text" class="input mr15 length_2"  value="{{ $value }}"><a href="#" class="J_ul_list_remove">[删除]</a></span>
							</li>
							<!--# } #-->
						</ul>
					</div>
					<a href="" class="link_add J_ul_list_add" data-related="attachment">添加附件类型</a>
				</td>
				<td><div class="fun_tips">系统限制上传单个附件的最大值：<span class="red">{{ $maxSize }}</span></div></td>
			</tr>
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
</form>
<!-- end -->

</div>
@include('admin.common.footer')
<script>
//添加附件类型html
var _li_html = '<li>\
					<span class="span_3"><input type="text" value="" class="input length_2" name="extsize[new_][ext]"></span>\
					<span class="span_4">\
						<input type="text" value="" class="input length_2 mr15" name="extsize[new_][size]"><a class="J_ul_list_remove" href="#">[删除]</a>\
					</span>\
				</li>';
</script>
</body>
</html>