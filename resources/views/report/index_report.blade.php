<!--=============举报弹窗=============-->
			<div class="pop_report">
				<form id="J_report_form" action="{{ url('report/index/doReport') }}" method="post" >
				<div class="pop_tips">感谢您能一起协助我们管理站点，我们会尽快处理您的举报内容！</div>
				<div class="pop_cont">
					<textarea class="length_6" name="reason"></textarea>
				</div>
				<div class="pop_bottom">
					<input type="hidden" name="type" value="{{ $type }}">
					<input type="hidden" name="type_id" value="{{ $type_id }}">
					<button type="submit" class="btn btn_submit">提交</button>
				</div>
				</form>
			</div>
<!--=============举报弹窗结束=============-->