<hook-action name="area" args="field,fieldinfo">
	<dl>
		<dt><label>{{ $fieldinfo['title'] }}：</label></dt>
		<dd class="J_region_set"><span class="must_red">*</span>
			<div class="reg_ddtext">
				<span class="J_region_list">
				<span class="J_province mr5" data-id=""></span><span class="J_city mr5" data-id=""></span><span class="J_district mr5" data-id=""></span>
				</span>
				<a href="{{ url('bbs/area/run') }}" class="J_region_change">选择</a>
			</div>
			<input class="J_areaid" type="hidden" name="{{ $field }}" value="" />
		</dd>
		<dd id="J_reg_tip_{{ $field }}" class="dd_r"></dd>
	</dl>
</hook-action>

<hook-action name="input" args="field,fieldinfo">
	<dl>
		<dt><label>{{ $fieldinfo['title'] }}：</label></dt>
		<dd><span class="must_red">*</span>
			<input name="{{ $field }}" type="text" class="input length_4" value="">
		</dd>
		<dd id="J_reg_tip_{{ $field }}" class="dd_r"></dd>
	</dl>
</hook-action>