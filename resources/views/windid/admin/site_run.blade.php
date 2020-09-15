<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap J_check_wrap">
	<form class="J_ajaxForm"  action="{{ url('windid/site/dorun') }}" method="post">

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
		</table>
	</div>

	<div class="h_a">全局参数设置</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />	
			<tr>
				<th>默认时区</th>
				<td>
					<component tpl='TPL:common.widgets.timezone' args="array('name' => 'timeTimezone' , 'value' => $config['time.timezone'])" />
				</td>
				<td><div class="fun_tips">系统默认时间显示。会员可在前台设置中心进行调整</div></td>
			</tr>
			<tr>
				<th>服务器时间校正</th>
				<td>
					<input name="timecv" type="number" class="input length_5 mr5" value="{{ $config['time.cv'] }}">分钟
				</td>
				<td><div class="fun_tips">如果站点显示时间与服务器时间有差异，可用此功能进行微调</div></td>
			</tr>
			<tr>
				<th>DEBUG 模式运行站点</th>
				<td>
					<input name="debug" type="number" class="input length_5 mr5" value="{{ $config['debug'] }}">
				</td>
				<td><div class="fun_tips">0：否、1：是（DEBUG）。当站点运行出现错误或异常时，建议开启DEBUG模式，以显示程序错误报告信息。并及时将错误信息反馈给程序开发商，以便尽快得到解决</div></td>
			</tr>
			<tr>
				<th>Cookie 路径</th>
				<td>
					<input name="cookiePath" type="text" class="input length_5" value="{{ $config['cookie.path'] }}">
				</td>
				<td><div class="fun_tips">默认为“/”。如果您在同一个域名下运行了多个站点，便需要将它设置为每个站点所在的目录名以防冲突。如“/bbs/”、“/forum/”。<br /><span class="red">注意:只有在特殊情况下才需要进行此设置，若您不能肯定，请填写“/”，输入错误的设置会导致站点无法正常登录</span></div></td>
			</tr>
			<tr>
				<th>Cookie 作用域</th>
				<td>
					<input name="cookieDomain" type="text" class="input length_5" value="{{ $config['cookie.domain'] }}">
				</td>
				<td><div class="fun_tips">默认为空。如果您的站点有两个不同的网址，如“phpwind.net”、“bbs.phpwind.net”，要使用户在两个不同的域名访问下，仍能保持登录状态，您需要在设置为“.phpwind.net” (注意域名需要以点开头)。<br /><span class="red">注意:只有在特殊情况下才需要进行此设置，若您不能肯定，请留空，输入错误的设置会导致站点无法正常登录</span></div></td>
			</tr>
			<tr>
				<th>Cookie 前缀</th>
				<td>
					<input name="cookiePre" type="text" class="input length_5" value="{{ $config['cookie.pre'] }}">
				</td>
				<td><div class="fun_tips">如果您的站点有两个不同的网址，如“phpwind.net”、“bbs.phpwind.net”，需要分别设置各个的前缀，防止cookie混乱。<br /></div></td>
			</tr>
		</table>
	</div>
	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit J_ajax_submit_btn" type="submit" >提交</button>
		</div>
	</div>
	</form>
</div>
@include('admin.common.footer')

</body>
</html>