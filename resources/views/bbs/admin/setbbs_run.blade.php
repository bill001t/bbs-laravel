<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<form method="post" class="J_ajaxForm" action="{{ url('bbs/setbbs/dorun') }}">
	<div class="nav">
		<ul class="cc">
			<li class="current"><a href="{{ url('bbs/setbbs/run') }}">首页</a></li>
			<li><a href="{{ url('bbs/setbbs/thread') }}">列表页</a></li>
			<li><a href="{{ url('bbs/setbbs/read') }}">阅读页</a></li>
		</ul>
	</div>
	<div class="h_a">论坛首页功能细节设置</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>显示今日生日会员</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="show_birthday_members" value="1" {{ App\Core\Tool::ifcheck($config['index.show_birthday_members']==1) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="show_birthday_members" value="0" {{ App\Core\Tool::ifcheck($config['index.show_birthday_members']==0) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td><div class="fun_tips">开启后，将在站点首页显示今天过生日的会员，若无会员生日则隐藏模块。</div></td>
			</tr>
			<tr>
				<th>显示友情链接</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input type="radio" name="show_links" value="1" {{ App\Core\Tool::ifcheck($config['index.show_links']==1) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="show_links" value="0" {{ App\Core\Tool::ifcheck($config['index.show_links']==0) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td><div class="fun_tips">开启后，将在论坛首页显示友情链接模块。</div></td>
			</tr>
			<tr>
				<th>显示在线用户</th>
				<td>
					<ul class="switch_list cc mb10">
						<li><label><input type="radio" name="show_online_users" value="1" {{ App\Core\Tool::ifcheck($config['index.show_online_users']==1) }}><span>开启</span></label></li>
						<li><label><input type="radio" name="show_online_users" value="0" {{ App\Core\Tool::ifcheck($config['index.show_online_users']==0) }}><span>关闭</span></label></li>
					</ul>
					<label><input name="list_online_users" type="checkbox" value="1" {{ App\Core\Tool::ifcheck($config['index.list_online_users']) }}>默认展开在线列表</label>
				</td>
				<td><div class="fun_tips">开启后，将在论坛首页显示在线用户。<br>展开在线用户列表会影响效率，建议不要勾选。</div></td>
			</tr>
			<!-- 
			<tr>
				<th>在线用户组图标</th>
				<td>
					<div class="cross">
						<ul>
							<li>
								<span class="span_2">顺序</span>
								<span class="span_2">用户组</span>
								<span class="span_2">在线图标</span>
							</li>
							<li>
								<span class="span_2"><input name="" type="text" class="input length_1"></span>
								<span class="span_2">管理员</span>
								<span class="span_2"><img src="" width="16" height="16"></span>
							</li>
							<li>
								<span class="span_2"><input name="" type="text" class="input length_1"></span>
								<span class="span_2">管理员</span>
								<span class="span_2"><img src="" width="16" height="16"></span>
							</li>
						</ul>
					</div>
				</td>
				<td><div class="fun_tips">在线图标存放在“images/所选风格的图片目录/group/用户组ID.gif”<br>点击用户组图标可进行修改。</div></td>
			</tr>
			 -->
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit" type="submit">提交</button>
		</div>
	</div>
	</form>
</div>
@include('admin.common.footer')
</body>
</html>