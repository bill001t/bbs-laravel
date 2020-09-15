<div style="width:440px;">
<form id="J_post_manage_ajaxForm"  action="{{ url('design/push/doadd') }}" method="post">
	<div class="pop_top J_drag_handle"><a href="#" class="pop_close J_close">关闭</a><strong>推送</strong></div>
	<div class="ct J_scroll_fixed">
		<!-- <div class="pop_top J_drag_handle"><a href="#" class="pop_close J_close">关闭</a><strong>推送</strong></div> -->
		<div class="pop_cont">
			<dl class="cc">
				<dt>页面：</dt>
				<dd>
				<select data-url="{{ url('design/push/getmodule') }}" id="J_push_select_initiative" class="select_5" name="pageid">

@foreach($pageList AS $v)

					<option value="{{ $v['page_id'] }}">{{ $v['page_name'] }}</option>
				<!--# } #-->
				</select>
				</dd>
			</dl>
			<dl class="cc">
				<dt>模块：</dt>
				<dd>
				<select id="J_push_select_passive" class="select_5" name="moduleid">

@if (!$moduleList)

						<option value="">无可用模块</option>
					<!--# } #-->

@foreach($moduleList AS $v)

					<option value="{{ $v['module_id'] }}">{{ $v['module_name'] }}</option>
					<!--# } #-->
				</select>
				</dd>
			</dl>
			
			<dl class="cc">
				<dt>开始时间：</dt>
				<dd><input type="text" class="input length_2 J_datetime mr5" name="start_time" value="">
					<span class="gray">为空表示立即显示</span>
				</dd>
			</dl>

			<dl class="cc">
				<dt>结束时间：</dt>
				<dd>
					<input type="text" class="input length_2 J_datetime mb5" name="end_time" value="">
					<p class="gray">留空表示一直有效，直至被其他推送数据替换</p>
				</dd>
			</dl>
		
		</div>
	</div>
	<div class="pop_bottom cc">
		<label class="fl"><input type="checkbox" name="isnotice" value="1">发送通知</label>
		<button type="submit" class="btn btn_submit fr" id="J_sub_topped">提交</button>
		<input type="hidden" name="fromid" value="{{ $fromid }}">
		<input type="hidden" name="fromtype" id="J_fromtype" value="{{ $fromtype }}">
	</div>
</form>

</div>