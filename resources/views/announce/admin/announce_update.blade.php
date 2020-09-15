<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="nav">
		<div class="return"><a href="{{ url('announce/announce/run') }}">返回上一级</a></div>
	</div>
<!--==============================编辑公告================================-->

	<form id="J_announce_form" action="{{ url('announce/announce/doUpdate') }}" method="post">
	<div class="h_a">编辑公告</div>
	<input name="aid" type="hidden" value="{{ $announceInfo['aid'] }}">
	<div class="table_full">
		<table width="100%">
			<colgroup>
				<col class="th">
				<col width="400">
			</colgroup>
			<tr>
				<th>显示顺序</th>
				<td><input name="vieworder" type="text" class="input length_6" value="{{ $announceInfo['vieworder'] }}"></td>
				<td></td>
			</tr>
			<tr>
				<th>有效时间</th>
				<td><span class="must_red">*</span><input name="start_date" type="text" class="input length_2 J_date mr20" value="{{ $announceInfo['start_date'] }}"><span class="mr20">至</span><input name="end_date" type="text" value="{{ $announceInfo['end_date'] }}" class="input length_2 J_date"></td>
				<td></td>
			</tr>
			<tr class="J_radio_change">
				<th>公告类别</th>
				<td>
					<ul class="single_list cc">
						<li><label><input name="typeid" type="radio" value="0"{{ $showType[0] }} data-arr="J_announce_content">文字公告</label></li>
						<li><label><input name="typeid" type="radio" value="1"{{ $showType[1] }} data-arr="J_announce_link">链接公告</label></li>
					</ul>
				</td>
				<td></td>
			</tr>
			<tr>
				<th>公告标题</th>
				<td><span class="must_red">*</span><input name="subject" type="text" class="input length_6" value="{{ $announceInfo['subject'] }}"></td>
				<td></td>
			</tr>
			<tbody id="J_announce_content" class="J_radio_tbody">
				<tr>
					<th>公告内容</th>
					<td colspan="2"><span class="must_red">*</span><textarea id="J_textarea" name="content" style="width:600px;height:400px;">{{ $announceInfo['content'] }}</textarea></td>
				</tr>
			</tbody>
			<tbody id="J_announce_link" class="J_radio_tbody">
				<tr>
					<th>公告链接</th>
					<td><span class="must_red">*</span><input name="url" type="text" class="input length_6" value="{{ $announceInfo['url'] }}"></td>
					<td></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit" id="J_announce_sub" type="submit">提交</button>
		</div>
	</div>
	</form>
	@include('admin.common.footer')
<script>
Wind.js(GV.JS_ROOT + '/pages/announce/admin/announceSub.js?v=' + GV.JS_VERSION);
</script>
</body>
</html>
