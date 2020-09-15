<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	@include('link_tab')
<!--==============================链接分类================================-->
	
	<form method="post" class="J_ajaxForm" action="{{ url('link/link/doTypes') }}" data-role="list">
	<div class="table_list">
		<table width="100%" id="J_table_list">
			<colgroup>
				<col width="60">
				<col width="210">
				<col width="60">
				<col width="320">
			</colgroup>
			<thead>
				<tr>
					<td>顺序</td>
					<td>分类名称</td>
					<td>链接数</td>
					<td>调用代码</td>
					<td>操作</td>
				</tr>
			</thead>
			<tbody>

@if ($typesList)


@foreach ($typesList as $key => $value)

			<tr>
				<td><input type="hidden" name="data[{{ $key }}][typeid]" value="{{ $value['typeid'] }}"><input class="input length_0" type="text" name="data[{{ $key }}][vieworder]" value="{{ $value['vieworder'] }}"></td>
				<td><input class="input length_3" type="text" name="data[{{ $key }}][typename]" value="{{ $value['typename'] }}"></td>
				<td>{{ $value['linknum'] }}</td>
				<td><textarea readonly="true" class="textarea_code" id="J_clipboard_copy{{ $key }}"><!--# echo WindSecurity::escapeHTML("{{-- <component tpl='TPL:link.link' class='SRV:link.srv.PwLinkService' method='getLinksByType' args='{{ $value[typename] }}'/> --}}") #--></textarea></td>
				<td><a href="#" style="z-index:0;" class="mr10 J_copy_clipboard" data-rel="J_clipboard_copy{{ $key }}">[复制调用代码]</a><a href="{{ url('link/link/run?typeid=' . $value['typeid']) }}" class="mr10">[查看]</a><a href="{{ url('link/link/doDeleteType') }}" class="mr10 J_ajax_del" data-pdata="{'typeId': {{ $value['typeid'] }}}">[删除]</a></td>
			</tr>
			<!--# } #-->

@else

				<tr><td colspan="7" class="tac">啊哦，暂无内容！</td></tr>
			<!--# } #-->
			</tbody>
		</table>
		<div class="p10"><a href="#" class="link_add" id="J_add_root" data-html="tbody">添加分类</a></div>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>
</div>
@include('admin.common.footer')
<script>
var root_tr_html = '<tr>\
					<td><input class="input length_0" type="text" name="newdata[NEW_ID_][vieworder]" value=""></td>\
					<td><input class="input length_3" type="text" name="newdata[NEW_ID_][typename]" value=""></td>\
					<td>&nbsp;</td>\
					<td>&nbsp;</td>\
					<td><a href="" class="mr10 J_newRow_del">[删除]</a></td>\
				</tr>';
Wind.js(GV.JS_ROOT+ 'pages/admin/common/forumTree_table.js?v=' +GV.JS_VERSION);
</script>
</body>
</html>
