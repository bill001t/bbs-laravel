<style>
.pop_cont dt{
	width:75px;
}
</style>
<div class="pop_login">
	<div class="pop_top">
		<a href="" class="pop_close J_close">关闭</a>
		<strong>登录保护</strong>
	</div>
				<form id="J_head_question_form" action="{{ url('u/login/doshowquestion') }}" method="post" >
				<input type="hidden" name="_statu" value="{{ $_statu }}">
				<input type="hidden" name="v" value="{{ $v }}" />
				<div class="pop_cont">
					<div id="J_login_question_tips" style="display:none;" class="tips"></div>

@if ($hasQuestion)

					<dl class="cc">
						<dt>安全问题：</dt>
						<dd><select id="J_qustion_select" class="select_4" name="question">

@foreach ($safeCheckList as $key => $value)

						<option value='{{ $key }}'>{{ $value }}</option>
<!--#}#-->
						<!--<option value="-2">自定义安全问题</option> -->
						</select></dd>
					</dl>
					<dl id="J_myqustion_dl" class="cc" style="display:none">
						<dt>自定义问题：</dt>
						<dd><input type="text" name="myquestion" value="" class="input length_4"></dd>
					</dl>
					<dl class="cc">
						<dt>你的答案：</dt>
						<dd><input type="text" class="input length_4" name="answer"></dd>
					</dl>
<!--#}#-->

@if ($verify)

					<dl class="cc dl_cd">
						<dt>验证码：</dt>
						<dd>
							<input id="J_head_login_code" type="text" class="input length_4 mb5" name="code">
							<div id="J_verify_code"></div>
						</dd>
					</dl>
<!--#}#-->
				</div>
				<div class="pop_bottom">
					<button type="submit" class="btn btn_submit">登录</button>
				</div>
				</form>
			</div>

<script type="text/javascript">
$(function(){
	
	var login_question_wrap = $('#J_login_question_wrap'),
		login_question_tips = $('#J_login_question_tips');

	//自定义问题
	var myqustion_dl = $('#J_myqustion_dl');
	$('#J_qustion_select').on('change', function(){
		if($(this).val() == '-4') {
			myqustion_dl.show();
		}else{
			myqustion_dl.hide();
		}
	});
	
	//提交
	var form = $('#J_head_question_form'),
		btn = form.find('button:submit');
	form.ajaxForm({
		dataType : 'json',
		beforeSubmit : function (arr, $form, options) {
			Wind.Util.ajaxBtnDisable(btn);
		},
		success : function (data, statusText, xhr, $form) {
			if(data.state === 'success') {
				window.location.href = decodeURIComponent(data.referer);
			}else{
				Wind.Util.ajaxBtnEnable(btn);
				login_question_tips.html('<div class="tips_icon_error">'+ data.message +'</div>').show();
			}
		}
	});
	
});
</script>