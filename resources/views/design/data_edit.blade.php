
<form action="{{ url('design/data/doedit?_json=1') }}" method="post">
	<div class="ct J_scroll_fixed">
		<div class="pop_cont">
			<div style="border-bottom:1px solid #e4e4e4;padding:0 5px 5px;margin-bottom:5px;">
				<a href="#" id="J_module_data_back">&laquo;返回列表</a>
			</div>
			@include('design.segment.data_edit')
		</div>
	</div>
	<div class="pop_bottom">
		<button type="submit" class="btn btn_submit J_module_sub" data-update="all">提交</button>
		<input type="hidden" name="moduleid" value="{{ $moduleid }}">
		<input type="hidden" name="dataid" value="{{ $data['data_id'] }}">
	</div>
	</form>