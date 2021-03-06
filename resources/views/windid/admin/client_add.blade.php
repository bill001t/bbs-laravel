<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body class="body_none" style="width:380px;">

<form class="J_ajaxForm"  action="{{ url('windid/client/doadd') }}" method="post">
	<div class="pop_cont">
		<div class="pop_table" style="height:auto;">
			<table width="100%">
				<tr>
					<th>客户端名称</th>
					<td><span class="must_red">*</span><input type="text" class="input length_4 mr5" name="appname" value=""></td>
				</tr>
				<tr>
					<th>客户端地址</th>
					<td><span class="must_red">*</span><input type="text" class="input length_4 mr5" name="appurl" value="">
						<div class="gray">如http://www.phpwind.net</div>
					</td>
				</tr>
				<!-- <tr>
					<th>客户端IP</th>
					<td><input type="text" class="input length_5" name="appip" value=""></td>
				</tr> -->
				<tr>
					<th>客户端编码</th>
					<td>
						<ul class="switch_list cc">
							<li><label><input name="charset"  value="utf8" type="radio" checked><span>UTF-8</span></label></li>
							<li><label><input name="charset"  value="gbk" type="radio"><span>GBK</span></label></li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>客户端接口文件</th>
					<td><input type="text" class="input length_4 mr5" name="apifile" value="{{ $apifile }}">
					<div class="gray">为空默认为windid.php</div>
					</td>
				</tr>
				<tr>
					<th>通讯密钥</th>
					<td><span class="must_red">*</span><input type="text" class="input length_4 mr5" name="appkey" value="{{ $rand }}"></td>
				</tr>
				<tr>
					<th>同步登录</th>
					<td>
						<ul class="switch_list cc">
							<li><label><input name="issyn"  value="1" type="radio" checked><span>开启</span></label></li>
							<li><label><input name="issyn"  value="0" type="radio"><span>关闭</span></label></li>
						</ul>
					</td>
				</tr>
				<tr>
					<th>接收通知</th>
					<td>
						<ul class="switch_list cc">
							<li><label><input name="isnotify"  value="1" type="radio" checked><span>开启</span></label></li>
							<li><label><input name="isnotify"  value="0" type="radio"><span>关闭</span></label></li>
						</ul>
					</td>
				</tr>
				
			</table>
		</div>
	</div>
	<div class="pop_bottom">
		<button class="btn btn_submit fr J_ajax_submit_btn" type="submit" >提交</button>
	</div>
</form>
@include('admin.common.footer')

</body>
</html>