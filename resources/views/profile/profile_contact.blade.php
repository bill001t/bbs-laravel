<div class="content">
	@include('profile_run_tab')
	<form class="J_profile_form" action="{{ url('profile/index/docontact') }}" method="post">
	<div class="profile_ct">
		<h3>交易信息</h3>
		<dl class="cc">
			<dt>支付宝：</dt>
			<dd>
				<input type="text" class="input length_5 mr10" name="alipay" value="{{ $userinfo['alipay'] }}"/>
		    </dd>
		</dl>
		<dl class="cc">
			<dt>手机号码：</dt>
			<dd>
			<input type="text" class="input length_5 mr10" name="mobile" value="{{ $userinfo['mobile'] }}"/>
			<span class="f12 gray" id="J_profile_tip_mobile"></span>
			</dd>
		</dl>
		<dl class="cc">
			<dt>固定电话：</dt>
			<dd><input type="text" class="input length_5 mr10" name="telphone" value="{{ $userinfo['telphone'] }}"/><span class="f12 gray" id="J_profile_tip_telphone"></span></dd>
		</dl>
		<dl class="cc">
			<dt>邮寄地址：</dt>
			<dd><input type="text" class="input length_5" name="address" value="{{ $userinfo['address'] }}"/></dd>
		</dl>
		<dl class="cc">
			<dt>邮编：</dt>
			<dd><input type="text" class="input length_5 mr10" name="zipcode" value="{{ $userinfo['zipcode'] }}"/><span class="f12 gray" id="J_profile_tip_zipcode"></span></dd>
		</dl>
		<h3>联系信息</h3>
		<dl class="cc">
			<dt>电子邮箱：</dt>
			<dd>
			<input type="hidden" name="email"> {{ Core::getLoginUser()->info['email'] }} &nbsp;<a name="email" href="{{ url('profile/index/editemail?_tab=contact') }}" >修改</a>
			</dd>
		</dl>
		<dl class="cc">
			<dt>阿里旺旺：</dt>
			<dd><input type="text" class="input length_5" name="aliww" value="{{ $userinfo['aliww'] }}"/></dd>
		</dl>
		<dl class="cc">
			<dt>QQ：</dt>
			<dd><input type="text" class="input length_5" name="qq" value="{{ $userinfo['qq'] }}"/></dd>
		</dl>
		<dl class="cc">
			<dt>MSN：</dt>
			<dd><input type="text" class="input length_5" name="msn" value="{{ $userinfo['msn'] }}"/></dd>
		</dl>
		<dl class="cc">
			<dt>&nbsp;</dt>
			<dd><button type="submit" class="btn btn_submit btn_big mr10">提交</button></dd>
		</dl>
	</div>
	</form>
</div>
<script>
Wind.ready(document, function(){
	Wind.use('jquery', 'global', GV.JS_ROOT +'pages/profile/profileIndex.js?v=' +GV.JS_VERSION);
});
</script>
{{-- <hook class='$hookSrc' name='displayFootHtml' args=''/> --}}