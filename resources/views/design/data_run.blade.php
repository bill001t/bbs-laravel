	<!--显示内容-->
<div id="J_module_data_list">
	<form  action="{{ url('design/data/batchEditData') }}" method="post">
		<div class="ct J_scroll_fixed">
		@include('design.segment.data_run')
		</div>
		<div class="pop_bottom">
			<button type="submit" class="btn btn_submit J_module_sub" data-update="mod">提交</button>
			<button type="button" class="btn" id="J_module_update" data-url="{{ url('design/data/docache') }}">更新</button>
			<input type="hidden" name="moduleid" value="{{ $moduleid }}">
		</div>
	</form>
</div>
<div id="J_module_data_edit" style="display:none;"><div class="pop_loading"></div></div>
