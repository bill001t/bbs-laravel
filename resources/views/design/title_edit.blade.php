				<!--标题-->
<form  action="{{ url('design/title/doedit') }}" method="post">
	<div class="ct J_scroll_fixed">
		<div class="pop_cont">
			@include('design.segment.title')
		</div>
	</div>
	<div class="pop_bottom">
		<button type="submit" class="btn btn_submit J_module_sub" data-update="title">提交</button>
		<button class="btn J_module_apply" type="submit">应用</button>
		<input type="hidden" name="moduleid" value="{{ $moduleid }}">
	</div>
</form>