	<!--模块属性-->
<form action="{{ url('design/property/doedit?_json=1') }}" method="post">
	<div class="ct J_scroll_fixed">
		<div class="pop_cont">
			<dl class="cc">
				<dt>数据模型：</dt>
				<dd>
					<select class="select_2 mr10" name="model_type" id="J_select_model_type">

@foreach ($types as $k=>$v)

						<option value="{{ $k }}" {{ App\Core\Tool::isSelected($modelInfo['type'] == $k) }}>{{ $v }}</option>
					<!--# } #-->
					</select>
					<select class="select_2 mr10" name="model" id="J_select_model">

@foreach ($models[$modelInfo['type']] as $v)

						<option value="{{ $v['model'] }}" {{ App\Core\Tool::isSelected($model == $v['model']) }}>{{ $v['name'] }}</option>
					<!--# } #-->
					</select>

@if ($isedit)

					<p class="s1">切换数据模型可能导致当前模板不可用，请重新设置模板</p>
					<!--# } #-->
				</dd>
			</dl>
			@include('design.segment.property')
		</div>
	</div>
	<div class="pop_bottom">
		<button type="submit" class="btn btn_submit J_module_sub" data-update="mod">提交</button>
		<button class="btn J_module_apply" type="submit">应用</button>
		<input type="hidden" name="moduleid" value="{{ $module['module_id'] }}">
	</div>
</form>