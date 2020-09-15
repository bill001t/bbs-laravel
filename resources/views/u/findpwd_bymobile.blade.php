<!doctype html>
<html>
<head>
@include('common.head')
<link href="{{ asset('assets/themes/site/default/css/dev/register.css') }} "rel="stylesheet" />
</head>
<body>
<div class="wrap">
{{-- @include('common.header') --}}
	<div class="main_wrap">
		<div class="box_wrap register cc">
			<h2 class="reg_head">您选择通过手机找回密码</h2>
			<div class="reg_cont_wrap">

@if ($step == 2)

				<div class="reg_cont">
					<div class="reg_form">
						<div class="tips">请输入绑定的手机号码，系统会发送一条带有验证码的短信到您的手机</div>
						<form id="J_bymobile_form" action="{{ url('u/findPwd/checkmobilecode') }}" method="post">
							<input type="hidden" id="J_pwd_username" name="username" value="{{ $username }}" />
							<dl>
								<dt><label>用户名：</label></dt>
								<dd><span class="username">{{ $username }}</span></dd>
							</dl>
							<dl>
								<dt><label>手机号码：</label></dt>
								<dd><span class="must_red">*</span><input type="hidden" id="J_reg_mobile_hide" name="mobile"><input required id="J_reg_mobile" data-counttime="90" type="text" class="input length_4" value=""></dd>
								<dd class="dd_r"><button style="display:none;" id="J_show_mcode" name="mobile" type="button" class="btn mr5">获取验证码</button><span class="dd_r" id="J_reg_tip__mobile" role="tooltip" aria-hidden="true"></span><span id="J_mcode_tip" class="reg_tips" style="display:none;">验证码已发送到<span id="J_send_mobile"></span><a href="" id="J_mobile_change" class="s4">（修改号码）</a>超过90秒未收到验证码，请点击重新发送。<button id="J_mcode_resend" class="btn disabled" type="button" disabled>90秒后重新发送</button></span></dd>
							</dl>
							<dl id="J_mcode_dl">
								<dt><label for="">手机验证码：</label></dt>
								<dd><span class="must_red">*</span><input id="J_reg_mobileCode" name="mobileCode" type="text" class="input length_4" value=""></dd>
								<dd class="dd_r"><span class="dd_r" id="J_reg_tip_mobileCode" role="tooltip" aria-hidden="true"></span></dd>
							</dl>

@if ($verify)

							<dl class="dl_cd">
								<dt><label>验证码：</label></dt>
								<dd><span class="must_red">*</span><input data-id="code" id="J_findpw_code" name="code" type="text" class="input length_4 mb5">
									<div id="J_verify_code"></div>
								</dd>
								<dd class="dd_r">
									<div id="J_findpw_tip_code"></div>
								</dd>
							</dl>
							<!--#}#-->
							<dl>
								<dt>&nbsp;</dt>
								<dd><button class="btn btn_big btn_submit mr20" type="submit">下一步</button><a href="javascript:window.history.go(-1);">返回上一步</a></dd>
							</dl>
						</form>
					</div>
				</div>
	<!--#} #-->

			</div>    
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script type="text/javascript">
Wind.use('jquery', 'global', 'validate', 'ajaxForm', function(){
	var focus_tips = {
		mobileCode : '请填写收到的手机验证码'
	};
	
	$("#J_bymobile_form").validate({
		errorPlacement: function(error, element) {
			//错误提示容器
			$('#J_reg_tip_'+ element[0].name).html(error);
		},
		errorElement: 'span',
		errorClass : 'tips_icon_error',
		validClass : 'tips_icon_success',
		onkeyup : false, //remote ajax
		focusInvalid : false,
		rules: {
			mobileCode : {
				required : true,
				remote : {
					url : '{{ url('u/mobile/checkmobilecode') }}',		//验证手机验证码
					dataType: "json",
					type : 'post',
					data : {
						mobileCode : function(){
							return $('#J_reg_mobileCode').val();
						},
						mobile : function(){
							return $('#J_reg_mobile').val();
						}
					}
				}
			}
		},
		highlight	: false,
		unhighlight	: function(element, errorClass, validClass) {
			var tip_elem = $('#J_reg_tip_'+ element.name);
			if(element.value){
				tip_elem.html('<span class="'+ validClass +'" data-text="text"><span>');
			}
		},
		onfocusin	: function(element){
			var id = element.name;
			$('#J_reg_tip_'+ id).html('<span class="reg_tips" data-text="text">'+ focus_tips[id] +'</span>');
			$(element).parents('dl').addClass('current');
		},
		onfocusout	:  function(element){
			var _this = this;
			$(element).parents('dl').removeClass('current');
			_this.element(element);
		},
		messages: {
			mobileCode : {
				required : '手机验证码不能为空',
				remote : '验证码错误'
			}
		},
		submitHandler:function(form) {
			var btn = $(form).find('button:submit');
			
			$(form).ajaxSubmit({
				dataType : 'json',
				beforeSubmit : function(){
					//global.js
					Wind.Util.ajaxBtnDisable(btn);
				},
				success : function(data, statusText, xhr, $form) {
					if(data.state === 'success') {
						if(data.referer) {
							location.href = decodeURIComponent(data.referer);
						}
					}else if(data.state === 'fail'){
						Wind.Util.ajaxBtnEnable(btn);
						Wind.Util.resultTip({
							elem : btn,
							error : true,
							msg : data.message,
							follow : true
						});
					}
				}
			});
		}
	});

	//手机验证
	window.M_CHECK = '{{ url('u/findPwd/sendmobile') }}';
	window.M_CHECK_MOBILE = '{{ url('u/findPwd/checkmobile') }}';
	Wind.js(GV.JS_ROOT +'pages/u/regMobileCheck.js?v='+ GV.JS_VERSION);
});
</script>
</body>
</html>