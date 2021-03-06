<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="nav">
		<div class="return"><a href="{{ url('task/manage/run') }}">返回上一级</a></div>
	</div>
<form class="J_ajaxForm" data-role="list" action="{{ url('task/manage/doEdit?_json=1') }}" method="post">
<input type="hidden" name="id" value="{{ $id }}">
<div class="h_a">编辑任务</div>
	<div class="table_full">
		<table width="100%">
			<col class="th" />
			<col width="400" />
			<col />
			<tr>
				<th>完成条件</th>
				<td><span class="must_red">*</span>
					<div class="task_item_list">
						<div class="hd">
							<ul class="J_tabs_nav">

@foreach ($conditionList as $key => $item)
$current = $task['conditions']['type'] == $key ? 'current' : '';
				#-->
								<li id="J_tab_nav_{{ $key }}" class="{{ $current }}"><a href="">{{ $item['title'] }}</a></li>
				<!--#}#-->
							</ul>
						</div>
						<input type="hidden" id="J_key" value="{{ $task['conditions']['type'] }}" name="condition[type]" />
						<div id="J_task_radio" class="J_tabs_contents">

@foreach ($conditionList as $key => $item)

							<div data-id="J_tab_nav_{{ $key }}" class="ct J_tab_item" id="{{ $key }}">
								<ul class="cc">

@foreach ($item['children'] as $childid => $child)
$check = false;
									if ($task['conditions']['type'] == $key && $task['conditions']['child'] == $childid) $check = true;
							#-->
									<li><label><input data-key="{{ $key }}" type="radio" name="condition[child]" data-param="{{ $child['var'] }}" data-url="{{ $child['url'] }}" value="{{ $childid }}" {{ App\Core\Tool::ifcheck($check) }}/><span>{{ $child['title'] }}</span></label></li>
							<!--#}#-->
								</ul>
							</div>
					<!--#}#-->
							</div>
						</div>
					</td>
					<td><div class="fun_tips">每个任务需要选择一个完成条件，不同的任务会有不同的限制条件。</div></td>
				</tr>
	
				<tbody id="J_task_main">
					<tr>
						<th>任务名称</th>
						<td>
							<span class="must_red">*</span>
							<input name="title" type="text" class="input length_5 input_hd" value="{{ $task['title'] }}">
						</td>
						<td><div class="fun_tips">显示在前台的任务名称，最多显示100字。</div></td>
					</tr>
					<tr>
						<th>任务目标描述</th>
						<td>
							<span class="must_red">*</span>
							<textarea name="description" class="length_5">{{ $task['description'] }}</textarea>
						</td>
						<td><div class="fun_tips">显示在前台的任务完成目标描述，支持html代码。</div></td>
					</tr>
					<tr>
						<th>任务图标</th>
						<td>
							<input type="hidden" value="{{ $task['icon'] }}" name="oldicon" id="J_oldicon" />
							<div class="single_image_up"><a href="">重新选择</a><input name="icon" type="file" class="J_upload_preview" data-preview="#J_icon" value="{{ $task['icon'] }}"></div>

@if ($task['icon'])
<img id="J_icon" src="{{ App\Core\Tool::getPath($task['icon']) }}" /><a href="" id="J_task_icon_reset">[恢复系统默认]</a><!--#}#-->
						</td>
						<td><div class="fun_tips">留空则使用默认图标。</div></td>
					</tr>
					<tr>
						<th>任务有效期</th>
						<td>
							<input name="start_time" type="text" class="input length_2 mr20 J_date" value="{{ $task['start_time'] }}" min="{{ $_current }}"><span class="mr20">至</span><input name="end_time" type="text" class="input length_2 J_date" value="{{ $task['end_time'] }}"  min="{{ $_current }}">
						</td>
						<td><div class="fun_tips">留空代表不限制。</div></td>
					</tr>
					<tr>
						<th>任务周期</th>
						<td>
							<input name="period" type="text" class="input length_5 mr5" value="{{ $task['period'] }}">小时
						</td>
						<td><div class="fun_tips">如设置为24，则表示该任务开始24小时以后可以再次申请，留空表示一次性任务。</div></td>
					</tr>
					<tr>
						<th>前置任务</th>
						<td>
							<select class="select_5" name="pre_task">
							<option value="0" selected>无</option>

@foreach($pre_tasks as $id => $title)

							<option value="{{ $id }}" {{ App\Core\Tool::isSelected($task['pre_task'] == $id) }}>{!! $title !!}</option>
							<!--# } #-->
							</select>
						</td>
						<td><div class="fun_tips"></div></td>
					</tr>
					<tr>
						<th>可申请的用户组</th>
						<td>
							<div class="user_group J_check_wrap">

@foreach($groupTypes as $type => $typeName)

								<dl>
									<dt><label><input class="J_check_all" data-direction="y" data-checklist="J_check_{{ $type }}" type="checkbox" {{ App\Core\Tool::ifcheck(1 == $isAll) }} />{{ $typeName }}</label></dt>
									<dd>

@foreach($groups as $group)
if($group['type'] == $type){
							 $check = App\Core\Tool::inArray($group['gid'], $task['user_groups']) || 1 == $isAll;
						#-->
										<label><input class="J_check" data-yid="J_check_{{ $type }}" type="checkbox" name="user_groups[]" value="{{ $group['gid'] }}" {{ App\Core\Tool::ifcheck($check) }} /><span>{{ $group['name'] }}</span></label>
					<!--#} }#-->
									</dd>
								</dl>
					<!--#}#-->
							</div>
						</td>
						<td><div class="fun_tips"></div></td>
					</tr>
					<tr>
						<th>申请设置</th>
						<td>
							<ul class="single_list cc">
								<li><label><input type="radio" name="is_auto" value="1" {{ App\Core\Tool::ifcheck($task['is_auto'] == 1) }} >自动申请</label></li>
								<li><label><input type="radio" name="is_auto" value="0" {{ App\Core\Tool::ifcheck($task['is_auto'] == 0) }} >手动申请</label></li>
							</ul>
						</td>
						<td><div class="fun_tips"></div></td>
					</tr>
					<tr>
						<th>显示设置</th>
						<td>
							<ul class="single_list cc">
								<li><label><input type="radio" name="is_display_all" value="0" {{ App\Core\Tool::ifcheck($task['is_display_all'] == 0) }} >符合条件才显示</label></li>
								<li><label><input type="radio" name="is_display_all" value="1" {{ App\Core\Tool::ifcheck($task['is_display_all'] == 1) }} >显示给所有用户</label></li>
							</ul>
						</td>
						<td><div class="fun_tips"></div></td>
					</tr>
					<tr>
						<th>任务奖励</th>
						<td>
							<select id="J_reward_select" class="select_5" name="reward[type]">
								<option value="">无</option>

@foreach ($rewardList as $type => $item)

								<option data-id="{{ $type }}" data-param="{{ $item['var'] }}" data-url="{{ $item['url'] }}" value="{{ $type }}" {{ App\Core\Tool::isSelected($task['reward']['type'] == $type) }}>{{ $item['title'] }}</option>
						<!--#}#-->
							</select>
						</td>
						<td><div class="fun_tips"></div></td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="btn_wrap">
			<div class="btn_wrap_pd">
				<button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
				<input id="J_checked_all" type="hidden" name="isAll" value="{{ $isAll }}" >
			</div>
		</div>
	</form>
</div>
@include('admin.common.footer')
<script>
Wind.use(GV.JS_ROOT +'pages/task/admin/task_manage.js?v=' + GV.JS_VERSION, function(){
	$('#J_task_icon_reset').on('click', function(e){
		e.preventDefault();
		$('#J_icon').hide().attr('src', '');
		$('#J_oldicon').val('');
		$('.J_upload_preview').val('');
		$(this).hide();
	});
});
</script>
</body>
</html>