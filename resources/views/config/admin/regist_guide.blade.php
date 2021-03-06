<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="nav">
		<ul class="cc">
			<li><a href="{{ url('/config/regist/run') }}">注册设置</a></li>
			<li><a href="{{ url('/config/regist/login') }}">登录设置</a></li>
			<li class="current"><a href="{{ url('/config/regist/guide') }}">新用户引导设置</a></li>
		</ul>
	</div>
	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ol>
			<li>启用新用户引导页面后，用户注册成功后会进入引导页面。开启的页面按设置的顺序显示。</li>
			<li>也可以通过简单的二次开发自定义引导页面，具体方法见开发文档。</li>
		</ol>
	</div>
	
	<form class="J_ajaxForm" action="{{ url('config/regist/doguide') }}" method="post" >
	<div class="table_list">
		<table width="100%">
			<colgroup>
				<col width="65">
				<col width="100">
				<col width="200">
			</colgroup>
			<thead>
				<tr>
					<td>启用</td>
					<td>顺序</td>
					<td>名称</td>
					<td>操作</td>
				</tr>
			</thead>

@foreach ($list as $key => $item)

			<tr>
				<td><input type="checkbox" {{ App\Core\Tool::ifcheck($item['isopen'] == 1) }} name="config[{{ $key }}][isopen]" value="1"></td>
				<td><input type="number" class="input length_1" name="config[{{ $key }}][order]" value="{{ $item['order'] }}"></td>
				<td>{{ $item['title'] }}</td>
				<td>
@if ($item['setting'])
<a href="{{ url($item['setting']) }}">[设置]</a><!--#}#--></td>
			</tr>
<!--#}#-->
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>
	
<!--设置弹窗-->
<div class="core_pop_wrap" style="display:none">
	<div class="core_pop">
		<div style="width:450px;">
			<div class="pop_top">
				<a href="#" class="pop_close">关闭</a>
				<strong>推荐关注页面设置</strong>
			</div>
			<div class="pop_cont">
				<div class="mb15"><h4 class="mb5">推荐用户</h4><textarea class="mb5" style="width:396px;height:36px;"></textarea><p class="gray">输入用户名，用英文“,”隔开。提交后显示推荐关注用户。</p></div>
				<div class="cc shift mb5">
					<div class="fl">
						<h4>选择版块</h4>
						<select id="J_roles" size="10" name="roles">
							<option value="后台管理员A">后台管理员A</option>
							<option value="dfgsdg">dfgsdg</option>
							<option value="111">111</option>
							<option value="站长">站长</option>
							<option value="副站长">副站长</option>
							<option value="shenzh1">shenzh1</option>
						</select>
					</div>
					<div class="fl shift_operate">
						<p class="mb10"><a id="J_auth_role_add" href="" class="btn ">添加 &gt;&gt;</a></p>
						<p><a id="J_auth_role_del" href="" class="btn">&lt;&lt; 移除</a></p>
					</div>
					<div class="fl">
						<h4>推荐版块</h4>
						<select id="J_user_roles" name="userRoles[]" size="10" multiple="multiple">
							<option value="后台管理员A">后台管理员A</option>
							<option value="dfgsdg">dfgsdg</option>
							<option value="站长">站长</option>
							<option value="副站长">副站长</option>
						</select>
					</div>
				</div>
				<p class="gray">提交后显示推荐关注版块。</p>
			</div>
			<div class="pop_bottom">
				<button type="button" class="btn btn_submit">提交</button>
			</div>
		</div>
	</div>
</div>
<!--结束-->

</div>
@include('admin.common.footer')
</body>
</html>