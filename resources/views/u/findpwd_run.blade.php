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
			<h2 class="reg_head">找回密码</h2>
			<div class="reg_cont_wrap">
				<div class="reg_cont">
					<div class="reg_form">
						<div class="tips">请输入您需要找回密码的用户名，以帮助您快速找回密码</div>
						<form id="J_findpw_form" action="{{ url('u/findPwd/checkUsername') }}" method="post">
							<input type="hidden" name="step" id="J_step" value="do" />
							<dl>
								<dt><label>用户名：</label></dt>
								<dd><input required id="J_findpw_username" data-id="username" type="text" class="input length_4" name="username" value=""></dd>
								<dd id="J_findpw_tip_username" class="dd_r"></dd>
							</dl>
							<dl>
								<dt>&nbsp;</dt>
								<dd><button class="btn btn_big btn_submit mr20" type="submit">下一步</button><!--a href="{{ url('u/findPwd/run') }}" class="s4">返回上一步</a--></dd>
							</dl>
						</form>
					</div>
				</div>
			</div>
			<div class="reg_side">
				<div class="reg_side_cont">
					<p class="mb10">还记得密码？</p>
					<p><a href="{{ url('u/login/run') }}" class="btn btn_big">立即登录</a></p>
				</div>
			</div>
		</div>
	</div>
{{--  @include('common.footer') --}}
</div>
<script type="text/javascript">
Wind.use('jquery', 'global', 'validate', 'ajaxForm', function(){
	var findpw_username = $('#J_findpw_username');
	
	$("#J_findpw_form").validate({
		errorPlacement: function(error, element) {
			//错误提示容器
			$('#J_findpw_tip_'+ element.data('id')).html(error);
		},
		errorElement: 'span',
		errorClass : 'tips_icon_error',
		validClass		: 'tips_icon_success',
		onkeyup : false, //remote ajax
		highlight	: false,
		focusInvalid : false,
		unhighlight	: function(element, errorClass, validClass) {
			$('#J_findpw_tip_username').html('<span class="'+ validClass +'" data-text="text"><span>');
		},
		onfocusin	: function(element){
			$('#J_findpw_tip_username').html('<span class="reg_tips" data-text="text">请输入您的用户名</span>');
			$(element).parents('dl').addClass('current');
		},
		onfocusout	:  function(element){
			this.element(element);
			$(element).parents('dl').removeClass('current');
		},
		rules: {
			username: {
				required	: true,
				remote : {
					url : '{{ url('u/findPwd/checkUsername') }}',
					dataType: "json",
					type : 'post',
					data : {
						username :  function(){
							return findpw_username.val();
						}
					}
				}
			}
		},
		messages: {
			username : {
				required	: '用户名不能为空',
				remote : '用户名不存在' //ajax验证默认提示
			}
		}
	});

	findpw_username.focus();

});
</script>
</body>
</html>