	<form id="J_link_apply_form" data-role="list" action="{{ url('link/index/doadd') }}" method="post">
	<div class="pop_cont pop_table">
		<dl class="cc">
			<dt>站点名称</dt>
			<dd><span class="must_red">*</span><input name="name" type="text" class="input length_5"><p class="gray">最多不超过15个字</p></dd>
		</dl>
		<dl class="cc">
			<dt>站点地址</dt>
			<dd><span class="must_red">*</span><input name="url" type="text" class="input length_5"></dd>
		</dl>
		<dl class="cc">
			<dt>站点LOGO</dt>
			<dd><input name="logo" type="text" class="input length_5"><p class="gray">请输入LOGO的图片地址，设置后自动为图片链接</p></dd>
		</dl>
		<dl class="cc">
			<dt>联系方式</dt>
			<dd><input name="contact" type="text" class="input length_5"></dd>
		</dl>
	</div>
	<input name="ifcheck" type="hidden" value="1">
	<!-- <div id="J_submit_tips" class="tips_error" style="display:none;"></div> -->
	<div class="pop_bottom">
		<button type="submit" class="btn btn_submit" id="J_link_apply_btn">提交</button>
	</div>
	</form>