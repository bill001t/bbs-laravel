<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="nav">
		<ul class="cc">
			<li><a href="{{ url('appcenter/app/run') }}">已安装</a></li>
			<li><a href="{{ url('appcenter/app/install') }}">本地安装</a></li>
			<li class="current"><a href="{{ url('appcenter/develop/run') }}">开发助手</a></li>
		</ul>
	</div>
	<!--应用安装-->
	<div class="h_a">提示信息</div>
	<div class="prompt_text">
		<ul>
			<li>该功能仅供应用开发者使用，通过创建应用可以生成一个最简单的demo。开发者可以基于这个demo继续开发。</li>
			<li>应用开发文档请参考《云平台文档中心》，有疑问请至phpwind官方论坛开发者论坛交流。</li>
			<li>在应用开发之前，请先在phpwind云平台创建应用，获取“应用标识”。</li>
		</ul>
	</div>
	<div class="h_a">创建应用</div>
<form class="J_ajaxForm" method="post" action="{{ url('appcenter/develop/doRun') }}">
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>应用标识(alias)</th>
				<td>
					<span class="must_red">*</span>
					<input type="text" class="input length_5" name="alias" value="" required>
				</td>
				<td><div class="fun_tips">请先至<a href="http://open.phpwind.com" target="_blank">phpwind云平台</a>创建应用，获取应用标识</div></td>
			</tr>
			<tr>
				<th>应用名称</th>
				<td>
					<span class="must_red">*</span>
					<input type="text" class="input length_5" name="name" value="" required>
				</td>
				<td><div class="fun_tips">必填</div></td>
			</tr>
			<tr>
				<th>应用描述</th>
				<td>
					<textarea name="description" class="length_5"></textarea>
				</td>
				<td><div class="fun_tips">应用简要介绍</div></td>
			</tr>
			<tr>
				<th>应用版本</th>
				<td>
					<span class="must_red">*</span>
					<input type="text" class="input length_5" name="version" value="1.0" required>
				</td>
				<td><div class="fun_tips">必填</div></td>
			</tr>
			<tr>
				<th>适用phpwind版本</th>
				<td>
					<span class="must_red">*</span>
					<input type="text" class="input length_5" name="pwversion" value="{{ @NEXT_VERSION }}" required>
				</td>
				<td><div class="fun_tips">必填</div></td>
			</tr>
			<tr>
				<th>作者名称</th>
				<td>
					<input type="text" class="input length_5" name="author" value="">
				</td>
				<td><div class="fun_tips">应用作者名称</div></td>
			</tr>
			<tr>
				<th>作者email</th>
				<td>
					<input type="text" class="input length_5" name="email" value="">
				</td>
				<td><div class="fun_tips"></div></td>
			</tr>
			<tr>
				<th>作者网站</th>
				<td>
					<input type="text" class="input length_5" name="website" value="">
				</td>
				<td><div class="fun_tips">官方网站</div></td>
			</tr>
			<tr>
				<th>选择应用额外安装服务</th>
				<td>
					<div class="sql_list J_check_wrap">
					<dl>
					<dt>
					<span class="span_1"><label><input type="checkbox" class="J_check_all" data-checklist="J_check_x" data-direction="x"></label></span><span class="span_2">服务名称</span><span class="span_3">服务描述</span>
					</dt>
					<dd style="height:150px">

@foreach($service as $k => $v)

					<p>
					<span class="span_1"><input type="checkbox" name = "service[]" value = "{{ $k }}" class="J_check" data-xid="J_check_x"></span>
					<span class="span_2">{{ $k }}</span>
					<span class="span_3">{{ $v }}</span>
					</p>
					<!--# } #-->
					</dd>
					</dl>
					</div>
				</td>
				<td><div class="fun_tips"></div></td>
			</tr>
			<tr>
				<th>是否需要管理后台界面</th>
				<td>
					<ul class="three_list cc mb5">
						<li><label><input type="radio" name="need_admin" value="1" checked>是</label></li>
						<li><label><input type="radio" name="need_admin" value="0">否</label></li>
					</ul>
				</td>
				<td><div class="fun_tips">生成应用后台的管理菜单及demo页面</div></td>
			</tr>
			<tr>
				<th>是否生成数据服务类(data access objects)</th>
				<td>
					<ul class="three_list cc mb5">
						<li><label><input type="radio" name="need_service" value="1">是</label></li>
						<li><label><input type="radio" name="need_service" value="0" checked>否</label></li>
					</ul>
				</td>
				<td><div class="fun_tips">生成底层的数据访问代码</div></td>
			</tr>
		</table>
	</div>

	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
</form>
@include('admin.common.footer')
</div>

</body>
</html>