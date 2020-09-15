<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="h_a">提示信息</div>
	<div class="prompt_text mb10">
		<ol>
			<li>当您开启网上支付功能时，表示您已经同意遵守“phpwind”相关协议</li>
			<li>支付过程出现问题请及时和phpwind官方取得联系</li>
			<li>因网站自身原因引起的非法支付phpwind不负任何责任</li>
		</ol>
	</div>
	<form method="post" class="J_ajaxForm" action="{{ url('config/pay/dorun') }}" data-role="list">
	<div class="h_a">网上支付设置</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>网上支付功能</th>
				<td>
					<ul class="switch_list cc">
						<li><label><input name="ifopen" value="1" type="radio" {{ App\Core\Tool::ifcheck($config['ifopen']) }}><span>开启</span></label></li>
						<li><label><input name="ifopen" value="0" type="radio" {{ App\Core\Tool::ifcheck(!$config['ifopen']) }}><span>关闭</span></label></li>
					</ul>
				</td>
				<td class="td_tips"><div></div></td>
			</tr>
			<tr>
				<th>网上支付功能关闭原因</th>
				<td>
					<textarea name="reason" class="length_5">{{ $config['reason'] }}</textarea>
				</td>
				<td class="td_tips"><div></div></td>
			</tr>
		</table>
	</div>

	<div class="h_a">支付宝信息</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>支付宝接口</th>
				<td>
					<select class="select_3 J_alipay_interface" name="alipayinterface">
						<option value="0"{{ App\Core\Tool::isSelected(!$config['alipayinterface']) }}>双功能收款(推荐)</option>
						<option value="1"{{ App\Core\Tool::isSelected($config['alipayinterface']) }}>即时到帐收款</option>
					</select>
				</td>
				<td class="td_tips"><div>请先选择支付宝接口类型，然后点此<a href="https://b.alipay.com/order/pidKey.htm?pid=2088101561840565&product=dualpay" class="J_alipay_url" target="_blank">获取PID和KEY</a><a href="https://b.alipay.com/order/pidKey.htm?pid=2088101561840565&product=fastpay" class="J_alipay_url" target="_blank" style="display:none">获取PID和KEY</a></div></td>
			</tr>
			<tr>
				<th>支付宝帐号</th>
				<td>
					<input type="text" value="{{ $config['alipay'] }}" class="input length_5" name="alipay">
				</td>
				<td class="td_tips"><div>还没有支付宝帐号？<a href="https://memberprod.alipay.com/account/reg/index.htm" target="_blank">请点击这里注册支付宝帐号</a></div></td>
			</tr>
			<tr>
				<th>合作者身份(PID)</th>
				<td>
					<input type="text" value="{{ $config['alipaypartnerID'] }}" class="input length_5" name="alipaypartnerID">
				</td>
				<td class="td_tips"><div>登录您的支付宝帐户页面，进入“商家服务”查看你的“合作者身份”</div></td>
			</tr>
			<tr>
				<th>安全校验码(KEY)</th>
				<td>
					<input type="text" value="{{ $config['alipaykey'] }}" class="input length_5" name="alipaykey">
				</td>
				<td class="td_tips"><div>登录您的支付宝账号页面，进入“商家服务”查看您的“交易安全校验码”</div></td>
			</tr>
		</table>
	</div>

	<div class="h_a">财付通信息</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>财付通帐号</th>
				<td>
					<input type="text" value="{{ $config['tenpay'] }}" class="input length_5" name="tenpay">
				</td>
				<td class="td_tips"><div>还没有财付通帐号？<a href="http://union.tenpay.com/mch/mch_register.shtml?posid=125&actid=84&opid=50&whoid=31&sp_suggestuser=AD125" target="_blank">请点击这里申请财付通商户帐号</a></div></td>
			</tr>
			<tr>
				<th>财付通密钥</th>
				<td>
					<input type="text" value="{{ $config['tenpaykey'] }}" class="input length_5" name="tenpaykey">
				</td>
				<td class="td_tips"><div></div></td>
			</tr>
		</table>
	</div>

	<div class="h_a">贝宝信息</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>贝宝帐号</th>
				<td>
					<input type="text" value="{{ $config['paypal'] }}" class="input length_5" name="paypal">
				</td>
				<td class="td_tips"><div>还没有贝宝帐号？<a href="https://www.paypal.com/cn/cgi-bin/webscr?cmd=_registration-run" target="_blank">请点击这里申请贝宝帐号</a></div></td>
			</tr>
			<tr>
				<th>贝宝密钥</th>
				<td>
					<input type="text" value="{{ $config['paypalkey'] }}" class="input length_5" name="paypalkey">
				</td>
				<td class="td_tips"><div></div></td>
			</tr>
		</table>
	</div>

	<div class="h_a">快钱信息</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>快钱帐号</th>
				<td>
					<input type="text" value="{{ $config['99bill'] }}" class="input length_5" name="99bill">
				</td>
				<td class="td_tips"><div>还没有快钱帐号？<a href="https://www.99bill.com/mbrentry/signup/bzsignuppage.htm" target="_blank">请点击这里申请快钱帐号</a></div></td>
			</tr>
			<tr>
				<th>快钱密钥</th>
				<td>
					<input type="text" value="{{ $config['99billkey'] }}" class="input length_5" name="99billkey">
				</td>
				<td class="td_tips"><div></div></td>
			</tr>
		</table>
	</div>

	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<button class="btn btn_submit mr10 J_ajax_submit_btn" type="submit">提交</button>
		</div>
	</div>
	</form>

	<div class="mb10">
		<div class="h_a">具体步骤</div>
		<div class="p10">
			<h4 class="mb10">（一）开通支付宝支付步骤:</h4>
			<div class="mb20" style="padding-left:2em;">
				<ol>
					<li>如果您已经拥有一个支付宝帐号，在下面“支付宝帐号”栏填写您的支付宝帐号即可开通支付宝支付功能</li>
					<li>如果您还没有支付宝帐号，请先注册一个支付宝帐号，<a href="https://www.alipay.com/user/user_register.htm" target="_blank" style="margin-left:1em;">请点击这里注册 <span class="xia">支付宝帐号</span></a></li>
				</ol>
				<p>注:除官方论坛另行通知以外，phpwind 提供的支付宝支付服务每笔交易收取 1.5% 的手续费。</p>
			</div>
			<h4 class="mb5">（二）开通财付通支付步骤:</h4>
			<div class="mb20" style="padding-left:2em;">
				<ol>
					<li>申请财付通商户帐号，<a href="http://union.tenpay.com/mch/mch_register.shtml?posid=125&actid=84&opid=50&whoid=31&sp_suggestuser=AD125"><span class="xia">点击这里申请财付通商户帐号</span></a></li>
					<li>申请成功后将商户号和商户密钥填入下面对应的文本框</li>
				</ol>
			</div>

			<h4 class="mb5">（三）开通贝宝支付步骤:</h4>
			<div class="mb20" style="padding-left:2em;">
				<ol>
					<li>注册一个贝宝帐号，<a href="https://www.paypal.com/cn/cgi-bin/webscr?cmd=_registration-run" target="_blank">注册 <span class="xia">贝宝帐号</span></a></li>
					<li>将贝宝帐号填写到下面对应的文本框</li>
					<li>开启贝宝“即时付款通知”<br />
					&nbsp; 登录贝宝网站:进入 我的贝宝 &raquo; 用户信息 &raquo; 即时付款通知习惯设定 &raquo; 编辑 开启“即时付款通知”并填写“通知URL”<br />
					&nbsp; 通知URL是 您的站点网址 + '/paypal.php?verifycode=' + 贝宝公共密钥<br />
					&nbsp; 格式如:<span class="blue">http://www.phpwind.net/paypal.php?verifycode=贝宝公共密钥</span>
					</li>
				</ol>
			</div>
			
			<h4 class="mb5">（四）开通快钱支付步骤:</h4>
			<div class="mb20" style="padding-left:2em;">
				<ol>
					<li>注册一个快钱帐号，<a href="https://www.99bill.com/website/signup/websignup.htm" target="_blank" style="margin-left:1em;">注册 <span class="xia">快钱</span> 帐号</a></li>
					<li>将“快钱商户号”和“快钱密钥”填入下面相应的文本框中</li>
				</ol>
			</div>
		</div>
	</div>

</div>
@include('admin.common.footer')
<script>
(function() {
	$('.J_alipay_interface').on('change', function() {
		var index = $('.J_alipay_interface option:selected').index();
		$('.J_alipay_url').hide();
		$($('.J_alipay_url').get(index)).show();
	});
	$('.J_alipay_interface').trigger("change");
})();
</script>
</body>
</html>