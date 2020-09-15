<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap J_check_wrap">
	
		<div class="h_a">版块设置</div>
		<div class="table_full">
			<table width="100%">
				<col class="th" />
				<col width="400" />
				<col />
				<tr>
				<th>允许发布投票的版块</th>
				<td>

@if ($cateIds)

					<div class="user_group mb5">

@foreach($cateList as $cate)
if (!App\Core\Tool::inArray($cate['fid'], $cateIds)) continue;
						#-->
						<dl>

@if ($forumList[$cate['fid']])

								<dt><label>{{ App\Core\Tool::stripWindCode($cate['name'], true) }}</label></dt>
								<dd>

@foreach ($forumList[$cate['fid']] as $forum)
$forum['name'] = strip_tags($forum['name']);
								 #-->

@if (App\Core\Tool::inArray($forum['fid'], array_keys($pollOpenForum)))

									<label><span>{{ $forum['name'] }}</span></label>
									<!--# } #-->
								<!--# } #-->
								</dd>
								<!--# } #-->
						</dl>
						<!--# } #-->
					</div>
					<!--# } #-->
					<a class="J_dialog" title="编辑" href="{{ url('vote/manage/editforum') }}">[编辑]</a>
				</td>
				<td><div class="fun_tips"></div></td>
				</tr>
			</table>
		</div>
		
<form class="J_ajaxForm" data-role="list" action="{{ url('vote/manage/dogroup') }}" method="post">
		<div class="h_a">用户组设置</div>
		<div class="table_full">
			<table width="100%">
				<col class="th" />
				<col width="400" />
				<col />
				<tr>
				<th>发布投票</th>
				<td>
					<div class="user_group">

@foreach($groups as $key=>$value)
$typeName = isset($groupsTypeName[$key]) ? $groupsTypeName[$key] : '';
#-->

						<dl>
							<dt><label><input class="J_check_all" data-direction="y" data-checklist="add_J_check_{{ $key }}" name="" type="checkbox" value="">{{ $typeName }}</label></dt>
							<dd>

@foreach($value as $k=>$val)

<!--# $isChecked = App\Core\Tool::inArray($val['gid'], $permission['allow_add_vote']) ? 'checked': ''; #-->
<label><input class="J_check" data-yid="add_J_check_{{ $key }}" type="checkbox" name="view[allow_add_vote][]" value="{{ $val['gid'] }}"{{ $isChecked }}><span>{{ $val['name'] }}</span></label>
<!--# } #-->
							</dd>
						</dl>


<!--# } #-->
					</div>
				</td>
				<td><div class="fun_tips"></div></td>
				</tr>
				<tr>
				<th>参与投票</th>
				<td>
					<div class="user_group">

@foreach($groups as $key=>$value)
$typeName = isset($groupsTypeName[$key]) ? $groupsTypeName[$key] : '';
#-->

						<dl>
							<dt><label><input class="J_check_all" data-direction="y" data-checklist="participate_J_check_{{ $key }}" name="" type="checkbox" value="">{{ $typeName }}</label></dt>
							<dd>

@foreach($value as $k=>$val)

<!--# $isChecked = App\Core\Tool::inArray($val['gid'], $permission['allow_participate_vote']) ? 'checked': ''; #-->
<label><input class="J_check" data-yid="participate_J_check_{{ $key }}" type="checkbox" name="view[allow_participate_vote][]" value="{{ $val['gid'] }}"{{ $isChecked }}><span>{{ $val['name'] }}</span></label>
<!--# } #-->
							</dd>
						</dl>


<!--# } #-->
					</div>

				</td>
				<td><div class="fun_tips"></div></td>
				</tr>
				<tr>
				<th>查看投票人员</th>
				<td>

					<div class="user_group">

@foreach($groups as $key=>$value)
$typeName = isset($groupsTypeName[$key]) ? $groupsTypeName[$key] : '';
#-->

						<dl>
							<dt><label><input class="J_check_all" data-direction="y" data-checklist="view_J_check_{{ $key }}" name="" type="checkbox" value="">{{ $typeName }}</label></dt>
							<dd>

@foreach($value as $k=>$val)

<!--# $isChecked = App\Core\Tool::inArray($val['gid'], $permission['allow_view_vote']) ? 'checked': ''; #-->
<label><input class="J_check" data-yid="view_J_check_{{ $key }}" type="checkbox" name="view[allow_view_vote][]" value="{{ $val['gid'] }}"{{ $isChecked }}><span>{{ $val['name'] }}</span></label>
<!--# } #-->
							</dd>
						</dl>


<!--# } #-->
					</div>
					
				</td>
				<td><div class="fun_tips"></div></td>
				</tr>
			</table>
		</div>
		
		<div class="btn_wrap">
			<div class="btn_wrap_pd">
				<button class="btn btn_submit J_ajax_submit_btn" type="submit">提交</button>
			</div>
		</div>
	</form>











</div>
@include('admin.common.footer')
</body>
</html>
