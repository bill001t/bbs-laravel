<!doctype html>
<html>
<head>
@include('common.head')
</head>
<body>
<div class="wrap">
	<div class="h_a">常用菜单</div>
	<form class="J_ajaxForm" action="{{ url('custom/doRun') }}" method="post">
	<div class="table_full J_check_wrap">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />

@foreach($menus as $menu)
$disabled = $menu['id'] == 'custom' ? 'disabled="true" checked' : '';
			#-->
			<tr>
				<th><label><input {$disabled} id="J_role_{{ $menu['id'] }}" class="J_check_all" data-direction="y" data-checklist="J_check_{{ $menu['id'] }}" type="checkbox"><span>{{ $menu['name'] }}</span></label></th>
				<td>
					<ul data-name="{{ $menu['id'] }}" class="three_list cc J_ul_check">

@foreach($menu['items'] as $item)

						<li><label><input {$disabled} name="customs[]" data-yid="J_check_{{ $menu['id'] }}" class="J_check" type="checkbox" value="{{ $item['id'] }}"><span>{{ $item['name'] }}</span></label></li>
						<!--# } #-->
					</ul>
				</td>
				<td><div class="fun_tips"></div></td>
			</tr>
			<!--# } #-->
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="J_ajax_submit_btn btn btn_submit" type="submit">提交</button>
		</div>
	</div>
	</form>
</div>
{{--  @include('common.footer') --}}
<script>
var ROLE_AUTH_CONFIG = {{ Security::escapeEncodeJson($myMenu) }}; //当前角色的已有权限集合
Wind.js(GV.JS_ROOT+ 'pages/admin/role_manage.js?v=' +GV.JS_VERSION);
</script>
</body>
</html>