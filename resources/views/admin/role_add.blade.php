<!doctype html>
<html>
<head>
@include('common.head')
</head>
<body>
<div class="wrap">


<!--角色管理: 添加编辑角色  -->
<div class="nav">
	<div class="return"><a href="{{ url('role/run') }}">返回上一级</a></div>
</div>
<form class="J_ajaxForm" data-role="list" action="{{ url('role/doAdd') }}" method="post">
<div class="h_a">添加新角色</div>
<div class="table_full">
	<table width="100%" class="J_check_wrap">
		<col class="th" />
		<col width="400" />
		<col />
		<tr>
			<th>角色名称</th>
			<td><span class="must_red">*</span>
				<input name="rolename" value="" type="text" class="input input_hd length_5">
			</td>
			<td><div class="fun_tips"></div></td>
		</tr>
		<tr>
			<th>从已有角色复制权限</th>
			<td>
				<select id="J_role_select" name="roleid"  class="select_5">
					<option>请选择角色</option>

@foreach($roles as $role)

					<option value="{{ $role['id'] }}">{{ $role['name'] }}</option>
<!--# } #-->
				</select>
			</td>
			<td><div class="fun_tips"></div></td>
		</tr>

@foreach($auths as $var)

		<tr>
			<th><label><input name="menus" id="J_role_{{ $var['id'] }}" data-direction="x" data-checklist="J_check_{{ $var['id'] }}" type="checkbox" class="checkbox J_check_all" value="{{ $var['id'] }}"><span>{{ $var['name'] }}</span></label></th>
			<td>
				<ul data-name="{{ $var['id'] }}" class="three_list cc J_ul_check">

@foreach($var['items'] as $item)

					<li><label><input name="auths[]" data-xid="J_check_{{ $var['id'] }}" class="J_check"{{ $checked }} type="checkbox" value="{{ $item['id'] }}"><span>{{ $item['name'] }}</span></label></li>
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
		<button type="submit" class="btn btn_submit mr10 J_ajax_submit_btn">提交</button>
	</div>
</div>
</form>

</div>
{{--  @include('common.footer') --}}
<script>
var ROLE_LIST_CONFIG = {{ Security::escapeEncodeJson($roleTable) }}, //已有角色的权限集合
	ROLE_AUTH_CONFIG = {{ Security::escapeEncodeJson($cAuths) }}; //当前角色的已有权限集合
Wind.js(GV.JS_ROOT+ 'pages/admin/role_manage.js?v=' +GV.JS_VERSION);
</script>
</body>
</html>