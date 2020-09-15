<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

<!-- start -->
<div class="nav">
		<div class="return"><a href="{{ url('u/manage/run') }}">返回上一级</a></div>
	<ul class="cc">
		<li><a href="{{ url('u/forbidden/run') }}">手动禁止</a></li>
		<li class="current"><a href="{{ url('u/forbidden/auto') }}">自动禁止</a></li>
		<li><a href="{{ url('u/forbidden/list') }}">解除禁止</a></li>
	</ul>
</div>
<form class="J_ajaxForm" method="post" action="{{ url('u/forbidden/dosetauto') }}">
<div class="h_a">自动禁止</div>
<div class="table_full">
	<table width="100%">
		<col class="th" />
		<col width="400" />
		<col />
		<tr>
			<th>自动禁止</th>
			<td>
				<ul class="switch_list cc">
				<li><label><input type="radio" name="open" value="1" {{ App\Core\Tool::ifcheck($config['autoForbidden.open'] == 1) }}><span>开启</span></label></li>
				<li><label><input type="radio" name="open" value="0" {{ App\Core\Tool::ifcheck($config['autoForbidden.open'] == 0) }}><span>关闭</span></label></li>
				</ul>
			</td>
			<td><div class="fun_tips">
			<p>自动禁止:自动禁止在管理员给会员评分时起作用</p>
			<p>如:后台设定威望小于 0 时自动禁止 3 天，那么当管理员扣除会员的威望至小于 0 时，则该会员就会被自动禁止 3天</p></div></td>
		</tr>
		<tr>
			<th>积分依据</th>
			<td>
				<select class="select_2 mr10" name="condition[credit]">

@foreach ($creditBo->cType as $id => $one)

				<option value="{{ $id }}" {{ App\Core\Tool::isSelected($id == $config['autoForbidden.condition']['credit']) }}>{{ $one }}</option>
<!--#}#--></select><span class="mr10">小于</span><input type="text" class="input length_2" name="condition[num]" value="{{ $config['autoForbidden.condition']['num'] }}">
			</td>
			<td>
				<div class="fun_tips"></div>
			</td>
		</tr>
		<tr>
			<th>禁止期限</th>
			<td>
				<select class="select_5" name="day">

@foreach ($dayTypes as $key => $value)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $config['autoForbidden.day']) }}>{{ $value['title'] }}</option>
<!--#}#-->
				</select>
			</td>
			<td>
				<div class="fun_tips">
当用户积分变动后低于设置值，则自动禁止相应权限；<br>
到期后自动解除禁止，如果用户的积分产生变动后低于设定值，则再次自动禁止。<br>
如果用户在被禁止期间积分已大于设定值，也只会在到期后再自动解禁。
				</div>
			</td>
		</tr>
		<tr>
			<th>禁止类型</th>
			<td>
				<ul class="single_list cc">

@foreach ($types as $key => $value)

					<li><label><input type="checkbox" name="type[]" value="{{ $key }}" {{ App\Core\Tool::ifcheck(App\Core\Tool::inArray($key, $config['autoForbidden.type'])) }}><span>{{ $value['title'] }}</span></label></li>
<!--#}#-->
				</ul>
			</td>
			<td><div class="fun_tips">
					<p>禁止发布：将禁止用户在全站发表内容的权限</p>
					<p>禁止头像：将清空用户当前的头像，并默认显示已禁止的头像，超过设定的天数后，显示网站默认头像</p>
					<p>禁止签名：禁止用户签名后，签名栏显示管理员设置的禁止理由，超过设定的天数后，签名栏默认为空</p>
			</div></td>
		</tr>
		<tr>
			<th>禁止理由</th>
			<td>
				<textarea class="length_5" name="reason">{{ $config['autoForbidden.reason'] }}</textarea>
			</td>
			<td><div class="fun_tips">必填</div></td>
		</tr>
	</table>
</div>

<div class="btn_wrap">
	<div class="btn_wrap_pd">
		<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
	</div>
</div>

</form>

</div>
@include('admin.common.footer')
</body>
</html>