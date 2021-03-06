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
		<li><a href="{{ url('/config/watermark/run') }}">水印设置</a></li>
		<li  class="current"><a href="{{ url('/config/watermark/set') }}">水印策略</a></li>
		</ul>
	</div>
	
	<form method="post" class="J_ajaxForm" action="{{ url('/config/watermark/doset') }}" data-role="list">
	<div class="h_a">水印策略</div>
	<div class="table_full mb10">
		<table class="J_check_wrap" width="100%">
			<colgroup>
			<col class="th">
			<col width="400">
			<col>
			</colgroup>

@foreach ($watermarkExt as $name => $title)

			<tr>
				<th>{{ $title }}</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="ext[{{ $name }}]" value="1" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($name,$config['mark.markset'])) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="ext[{{ $name }}]" value="0" {{ App\Core\Tool::ifcheck(!App\Core\Tool::inArray($name,$config['mark.markset'])) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td>&nbsp;</td>
			</tr>
			<!--#}#-->
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>
	
<!--=================结束=================-->
</div>
@include('admin.common.footer')
</body>
</html>