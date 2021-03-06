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
			<li class="current"><a href="{{ url('/config/regist/login') }}">登录设置</a></li>
			<!-- <li><a href="{{ url('/config/regist/guide') }}">新用户引导设置</a></li> -->
		</ul>
	</div>
	<div class="h_a">登录设置</div>
	<form class="J_ajaxForm" data-role="list" action="{{ url('/config/regist/dologin') }}" method="post">
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>用户登录方式</th>
				<td>
					<ul class="three_list cc">
						<li><label><input type="checkbox" value="1" name="ways[]" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray(1, $config['ways'])) }}><span>UID</span></label></li>
						<li><label><input type="checkbox" value="2" name="ways[]" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray(2, $config['ways'])) }}><span>电子邮箱</span></label></li>
						<li><label><input type="checkbox" value="3" name="ways[]" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray(3, $config['ways'])) }}><span>用户名</span></label></li>
						<li><label><input type="checkbox" value="4" name="ways[]" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray(4, $config['ways'])) }}><span>手机号码</span></label></li>
					</ul>
				</td>
				<td><div class="fun_tips">请至少选定一种用户登录方式。</div></td>
			</tr>
			<tr>
				<th>强制使用安全问题</th>
				<td>
					<div class="user_group J_check_wrap">

@foreach($groupTypes as $type => $typeName)

						<dl>
							<dt><label><input type="checkbox" data-direction="y" data-checklist="J_check_{{ $type }}" class="checkbox J_check_all" />{{ $typeName }}</label></dt>
							<dd>

@foreach($groups as $group)
if($group['type'] == $type){
	$checked = App\Core\Tool::inArray($group['gid'],$config['question.groups']);#-->
								<label><input class="J_check" data-yid="J_check_{{ $type }}" type="checkbox" name="questionGroups[]" value="{{ $group['gid'] }}"{{ App\Core\Tool::ifcheck($checked) }} /><span>{{ $group['name'] }}</span></label>
<!--# } #-->
<!--# } #-->
							</dd>
						</dl>
<!--# } #-->
					</div>
				</td>
				<td><div class="fun_tips">可以强制管理权限较高的用户组(如:管理员,总版主,版主)使用安全问题，可提高密码安全性</div></td>
			</tr>
			<tr>
				<th>密码尝试次数</th>
				<td>
					<input type="number" class="input length_5" value="{{ $config['trypwd'] }}" name="trypwd">
				</td>
				<td><div class="fun_tips">密码输入错误次数限制，超出限制次数后30分钟内不允许再登录。</div></td>
			</tr>
			<tr>
				<th>密码重置邮件标题</th>
				<td>
					<input type="text" class="input length_5" value="{{ $config['resetpwd.mail.title'] }}" name="resetPwdMailTitle">
				</td>
				<td><div class="fun_tips">支持参数，如下：<br>{{ sitename }}：站点名称<br>{{ username }}：用户名</div></td>
			</tr>
			<tr>
				<th>密码重置邮件内容</th>
				<td>
					<textarea class="length_5" name="resetPwdMailContent">{{ $config['resetpwd.mail.content'] }}</textarea>
				</td>
				<td><div class="fun_tips">支持html代码，支持参数：<br>{{ username }}：用户名<br>{{ sitename }}：站点名称<br>{{ url }}:重置链接<br>{{ time }}：发送时间。</div></td>
			</tr>
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>
</div>
@include('admin.common.footer')
</body>
</html>