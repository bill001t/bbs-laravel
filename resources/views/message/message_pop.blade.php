<div id="J_send_msg_pop" tabindex="0" class="core_pop_wrap" style="display:none;z-index:11;">
	<form id="J_send_msg_form" style="display:block;" action="{{ url('message/message/doAddMessage') }}" method="post" >
	<div class="pop_message_add">
		<div class="core_pop">
			<div class="J_drag_handle pop_top"><a href="#" id="J_send_msg_close" class="pop_close">关闭</a><strong>写私信</strong></div>
			<div class="pop_cont cc">
				<dl class="cc">
					<dt>收信人：</dt>
					<dd>
						<div style="float:left;margin-top:30px;_float:right;_margin-top:27px;_margin-left:-412px;">
						<div id="J_users_pop" class="core_pop_wrap" style="display:none;">
							<div class="core_pop">
								<div class="user_select_pop">
									<div class="pop_top">
										<a href="#" class="pop_close J_close_users">关闭</a>
										<select id="J_users_select">
										<option value="follows">我的关注</option>
										<option value="fans">我的粉丝</option>
										</select>
									</div>
									<div class="pop_cont" id="J_users_wrap">
										<div class="pop_loading"></div>
									</div>
									<div class="pop_bottom">
										<button type="button" class="btn btn_submit J_close_users">确认</button>
									</div>
								</div>
							</div>
						</div>
						</div>
						<div class="user_select_input cc J_user_tag_wrap">
							<a href="{{ url('message/message/follows') }}" class="input_down" id="J_get_follows">下拉选择</a>
							<ul class="fl J_user_tag_ul">

@if ($username)


@foreach ($username as $value)

							<li><a href="javascript:;">
								<span class="J_tag_name">{{ $value }}</span>
								<del class="J_user_tag_del" title="{{ $value }}">×</del>
								<input type="hidden" name="usernames[]" value="{{ $value }}">
								</a>
							</li>
							<!--# } #-->
							<!--# } #-->
							</ul>
							<input data-name="usernames[]" class="J_user_tag_input" type="text" />
						</div>
					</dd>
				</dl>
				<dl class="cc">
					<dt>内容：</dt>
					<dd>
						<textarea id="J_msg_pop_textarea" style="width:390px;height:100px" class="mb5" name="content"></textarea>
						<a href="" class="icon_face J_insert_emotions" data-emotiontarget="#J_msg_pop_textarea">表情</a>
					</dd>
				</dl>

@if ($verify)

				<dl class="cc dl_cd">
					<dt>验证码：</dt>
					<dd>
						<div class="fl mr10">
							<input name="code" type="text" class="input length_4 mb5">
							<div id="J_verify_code"></div>
						</div>
					</dd>
				</dl>
<!--#}#-->
			</div>
			<div class="pop_bottom"><button class="btn btn_submit" id="J_send_msg_btn" type="submit">发送</button></div>
		</div>
	</div>
	</form>
</div>
