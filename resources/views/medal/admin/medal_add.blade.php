<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body class="body_none" style="width:500px;">
<div class="">
<!--添加勋章弹窗-->
	<form class="J_ajaxForm" action="{{ url('medal/medal/doAdd?_json=1') }}" method="post">
			<div class="pop_cont pop_table" style="overflow-x:hidden;">
				<table width="100%">
					<tr>
						<th>勋章名称</th>
						<td><span class="must_red">*</span><input type="text" class="input length_6" name="medalname"/></td>
					</tr>
					<tr>
						<th>勋章大图标</th>
						<td><span class="must_red">*</span>
							<div class="single_image_up"><a href="">上传图片</a><input data-preview="#J_medal_preview_big" name="image" type="file" class="J_upload_preview"></div><div class="gray">建议尺寸：96×96像素</div>
							<img id="J_medal_preview_big" style="display:none"/>
						</td>
					</tr>
					<tr>
						<th>勋章小图标</th>
						<td><span class="must_red">*</span>
							<div class="single_image_up"><a href="">上传图片</a><input data-preview="#J_medal_preview_small" name="icon" type="file" class="J_upload_preview"></div><div class="gray">建议尺寸：25×25像素</div>
							<img id="J_medal_preview_small" style="display:none"/>
						</td>
					</tr>
					<tr>
						<th>勋章说明</th>
						<td><span class="must_red">*</span><input type="text" class="input length_6" name="descrip"/></td>
					</tr>
					<tr>
						<th>发放机制</th>
						<td><span class="must_red">*</span>
							<ul class="switch_list J_radio_change cc">
								<li><label><input data-arr="J_medal_show" type="radio" name="receivetype" value="1" checked><span>自动发放</span></label></li>
								<li><label><input data-arr="J_medal_time" type="radio" name="receivetype" value="2"><span>手动发放</span></label></li>
								<!-- <li><label><input type="radio" name="receivetype" value="3"><span>任务勋章</span></label></li> -->
							</ul>
						</td>
					</tr>
					<tr>
						<th>可领用户组</th>
						<td>
							<div class="user_group J_check_wrap">

@foreach($groupTypes as $type => $typeName)

								<dl>
									<dt><label><input data-direction="y" data-checklist="J_check_{{ $type }}" type="checkbox" class="checkbox J_check_all" name="visitGroup[]" value="{{ $type }}">{{ $typeName }}</label></dt>
									<dd>

@foreach($groups as $group)
if($group['type'] == $type){
					$checked = App\Core\Tool::inArray($group['gid'],explode(',', $info['medal_gids']));
										#-->
										<label><input class="J_check" data-yid="J_check_{{ $type }}" type="checkbox" name="visitGid[]" value="{{ $group['gid'] }}"><span>{{ $group['name'] }}</span></label>
										<!--# } #-->
									<!--# } #-->
									</dd>
								</dl>
									<!--# } #-->
							</div>
						</td>
						</tr>
				<tbody class="J_radio_tbody" id="J_medal_show">
						<tr>
							<th>颁发条件</th>
							<td>
								<div class="medal_term">
									<div class="hd">
										<select id="J_awardtype_select" name="awardtype">

@foreach($awardTypes as $key => $awardType)

											<option value="{{ $key }}">{{ $awardType }}</option>
										<!--# } #-->
										</select>
										<span>&ge;</span><input type="text" class="input length_1"  name="awardcondition" value="0"/></div>
									<div id="J_awardtype_warp" class="ct">
										<h6>同条件勋章列表</h6>
										<dl>
											<dt>序号</dt>
											<dd class="num">&ge;</dd>
											<dd class="title">勋章名称</dd>
										</dl>
										<div id="J_awardtype_list">

@foreach($medalList as $key => $medal)
$i = $key + 1;#-->
											
											<dl>
												
											<dt>{{ $i }}</dt>
												
												<dd class="num">{{ $medal['award_condition'] }}</dd>
												
												<dd class="title">{{ $medal['name'] }}</dd>
											
											</dl>
										<!--# } #-->
										</div>
									</div>
								</div>
							</td>
						</tr>
					</tbody>
					<tbody class="J_radio_tbody" id="J_medal_time">
						<tr>
							<th>有效天数</th>
							<td>
								<input type="text" class="input length_3 mr10" name="expired" value="0"/>0为永久有效
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="pop_bottom">
				<button class="btn fr" id="J_dialog_close" type="button">取消</button>
				<button type="submit" class="btn btn_submit J_ajax_submit_btn fr mr10">提交</button>
			</div>

	</form>
<!--添加勋章弹窗-->
</div>
@include('admin.common.footer')
<script>
//颁发条件
var MEDAL_AWARD_JSON = {{ Security::escapeEncodeJson($medalJson) }};
Wind.js(GV.JS_ROOT +'pages/medal/admin/medal_manage.js?v=' + GV.JS_VERSION);
</script>
</body>
</html>