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
		<li class="current"><a href="{{ url('/config/config/run') }}">站点信息</a></li>
		<li><a href="{{ url('/config/config/site') }}">全局参数</a></li>
	</ul>
</div>
<form method="post" class="J_ajaxForm" action="{{ url('/config/config/dorun') }}">
<div class="h_a">站点信息设置</div>
<div class="table_full">
	<table width="100%">
		<col class="th" />
		<col width="400" />
		<col />
		<tr>
			<th>站点名称</th>
			<td>
				<input name="infoName" type="text" class="input length_5" value="{{ $config['info.name'] }}">
			</td>
			<td><div class="fun_tips">默认站点名称，如果各个应用没有填写站点名称，则显示这个名称</div></td>
		</tr>
		<tr>
			<th>站点地址</th>
			<td>
				<input name="infoUrl" type="text" class="input length_5" value="{{ $config['info.url'] }}">
			</td>
			<td><div class="fun_tips">填写您站点的完整域名。例如: http://www.phpwind.net，不要以斜杠 (“/”) 结尾</div></td>
		</tr>
		<tr>
			<th>管理员电子邮箱</th>
			<td>
				<input name="infoMail" type="text" class="input length_5" value="{{ $config['info.mail'] }}">
			</td>
			<td><div class="fun_tips">填写站点管理员的邮箱地址</div></td>
		</tr>
		<tr>
			<th>ICP 备案信息</th>
			<td>
				<input name="infoIcp" type="text" class="input length_5" value="{{ $config['info.icp'] }}">
			</td>
			<td><div class="fun_tips">填写 ICP 备案的信息，例如: 浙ICP备xxxxxxxx号</div></td>
		</tr>
		<tr>
			<th>第三方统计代码</th>
			<td>
				<textarea class="length_5" name="statisticscode">{{ $config['statisticscode'] }}</textarea>
			</td>
			<td><div class="fun_tips">在第三方网站上注册并获得统计代码，并将统计代码粘帖在下面文本框中即可。</div></td>
		</tr>
	</table>
</div>
<div class="h_a">站点状态设置</div>		
<div class="table_full">
<table width="100%">
	<col class="th" />
	<col width="400" />
	<col />
	<tr>
		<th>站点状态</th>
		<td>
			<ul id="J_status_type" class="single_list cc">
				<li><label><input data-title="s1" data-type="" name="visitState" type="radio" value="0"{{ App\Core\Tool::ifcheck(!$config['visit.state']) }}><span>完全开放</span></label></li>
				<li><label><input data-title="s2" data-type="J_status_s1,J_status_s2" name="visitState" type="radio" value="1"{{ App\Core\Tool::ifcheck($config['visit.state']==1) }}><span>内部开放</span></label></li>
				<li><label><input data-title="s3" data-type="J_status_s2" name="visitState" type="radio" value="2"{{ App\Core\Tool::ifcheck($config['visit.state']==2) }}><span>完全关闭</span></label></li>
			</ul>
		</td>
		<td><div id="J_status_tip" class="fun_tips">完全关闭:除站点创始人，其他人都不允许访问站点，一般用于站点关闭、系统维护等情况</div></td>
	</tr>
</table>
</div>
<div class="table_full">
<table width="100%" id="J_status_s1" class="J_status_tbody">
	<col class="th" />
	<col width="400" />
	<col />
		<tr>
			<th>允许访问的用户组</th>
			<td>
				<div class="user_group J_check_wrap">

@foreach($groupTypes as $type => $typeName)
if (!is_array($config['visit.group'])) $config['visit.group'] = array();
			$checked = App\Core\Tool::inArray($type,$config['visit.group']);#-->
					<dl>
						<dt><label><input data-direction="y" data-checklist="J_check_{{ $type }}" type="checkbox" class="checkbox J_check_all" name="visitGroup[]" value="{{ $type }}"{{ App\Core\Tool::ifcheck($checked) }}>{{ $typeName }}</label></dt>
						<dd>

@foreach($groups as $group)
if($group['type'] == $type){
			if (!is_array($config['visit.gid'])) $config['visit.gid'] = array();
			$checked = App\Core\Tool::inArray($group['gid'],$config['visit.gid']);#-->
							<label><input class="J_check" data-yid="J_check_{{ $type }}" type="checkbox" name="visitGid[]" value="{{ $group['gid'] }}"{{ App\Core\Tool::ifcheck($checked) }}><span>{{ $group['name'] }}</span></label>
			<!--# } #-->
			<!--# } #-->
						</dd>
					</dl>
		<!--# } #-->
				</div>
			</td>
			<td><div class="fun_tips">站点内部开放状态下，允许访问站点的特定用户组。<br>留空表示不使用此功能</div></td>
		</tr>
	<tr>
		<th>允许访问的IP段</th>
		<td>
			<textarea class="length_5" name="visitIp">{{ $config['visit.ip'] }}</textarea>
		</td>
		<td><div class="fun_tips">站点内部开放状态下，允许访问站点的特定IP段用户。<br>如：192.168.1.*，表示192.168.1下的所有IP都允许访问站点。<br>多个IP段之间请用英文半角逗号“,”分隔。留空则表示不使用此功能。<br>您当前登录IP：127.0.0.1</div></td>
	</tr>
	<tr>
		<th>允许访问的会员</th>
		<td>
			<textarea class="length_5" name="visitMember">{{ $config['visit.member'] }}</textarea>
		</td>
		<td><div class="fun_tips">站点内部开放状态下，允许访问站点的特定会员。<br>多个会员用户名请用英文半角逗号“,”分隔。<br>留空则表示不使用此功能</div></td>
	</tr>
</table>
<table width="100%" id="J_status_s2" class="J_status_tbody">
	<col class="th" />
	<col width="400" />
	<col />
	<tr>
		<th>限制访问提示信息</th>
		<td>
			<textarea class="length_5"  name="visitMessage">{{ $config['visit.message'] }}</textarea>
		</td>
		<td><div class="fun_tips">当站点处于内部开放状态时，登录界面显示的提示信息</div></td>
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

$(function(){
	//站点状态
	var status_title = {
		s1 : '允许任何人访问站点',
		s2 : '特定会员才能访问站点，通常用于站点内部测试、调试',
		s3 : '除创始人，其他用户不允许访问站点，一般用于站点关闭、系统维护等情况'
	};
	
	var checked = $('#J_status_type input:checked');
	
	statusAreaShow(checked.data('type'));
	statusTitle(checked.data('title'));

	$('#J_status_type input:radio').on('change', function(){
			statusAreaShow($(this).data('type'));
			statusTitle($(this).data('title'));
	});

	//切换显示版块
	function statusAreaShow(type) {
		var status_arr= new Array();
		
		status_arr = type.split(",");
		$('table.J_status_tbody').hide();
		
		$.each(status_arr, function(i, o){
			$('#'+ o).show();
		});
	}
	
	//切换提示文案
	function statusTitle(title){
		$('#J_status_tip').text(status_title[title]);
	}
});
</script>
</body>
</html>