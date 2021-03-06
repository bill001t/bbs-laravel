<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

<div class="nav">
	<ul class="cc">
		<li{!! $typeClasses['member'] !!}><a href="{{ url('u/groups/run?type=member') }}">会员组</a></li>
		<li{!! $typeClasses['special'] !!}><a href="{{ url('u/groups/run?type=special') }}">特殊组</a></li>
		<li{!! $typeClasses['system'] !!}><a href="{{ url('u/groups/run?type=system') }}">管理组</a></li>
		<li{!! $typeClasses['default'] !!}><a href="{{ url('u/groups/run?type=default') }}">默认组</a></li>
	</ul>
</div>

<form class="J_ajaxForm" data-role="list" action="{{ url('u/groups/dosave') }}" method="post">
<input type="hidden" name="grouptype" value="{{ $groupType }}" />
<div class="table_list">
	<table width="100%" id="J_group_table">
		<col width="50">
		<col width="210">
		<col width="150">
		<thead>
			<tr>
				<td>编号</td>
				<td>头衔</td>
				<td>用户组图标</td>

@if ($groupType == 'member')

				<td width="200">升级点数需求</td>
				<!--# } #-->
				<td>操作</td>
			</tr>
		</thead>
		<tbody id="J_groupList">

@foreach ($groups as $group)

		<tr>
			<td>{{ $group['gid'] }}</td>
			<td><input type="text" class="input length_3" name="groupname[{{ $group['gid'] }}]" value="{{ $group['name'] }}"></td>
			<td class="cp J_set_icon">
				<img src="{{ asset('assets/images') }}/level/{{ $group['image'] }}">
				<input type="hidden" name="groupimage[{{ $group['gid'] }}]" value="{{ $group['image'] }}" />
			</td>

@if ($groupType == 'member')
$next = next($points);
			#-->
			<td><input type="number" class="input length_2" name="grouppoints[{{ $group['gid'] }}]" value="{{ $group['points'] }}"> ~ <span>{{ $next }}</span></td>
			<!--# } #-->
			<td>

@if ($groupType == 'special' || $groupType == 'system')

				<a href="{{ url('admin/u/groups/edit?gid=' . $group['gid']) }}" class="mr10">[基本权限]</a>
				<a href="{{ url('admin/u/groups/edit?manage=1&gid=' . $group['gid']) }}" class="mr10">[管理权限]</a>

@else

				<a href="{{ url('admin/u/groups/edit?gid=' . $group['gid']) }}" class="mr10">[编辑]</a>
				<!--# } #-->

@if ($group['gid'] > 7)

				<a href="{{ url('admin/u/groups/delete') }}" class="mr10 J_ajax_del" data-pdata="{'gid': {{ $group['gid'] }}}">[删除]</a>
				<!--# } #-->
			</td>
		</tr>
		<!--# } #-->
		</tbody>

@if ($groupType != 'default')

		<tbody id="J_groups_add_temp">
			<tr>
				<td></td>
				<td><input id="J_new_group_name" type="text" class="input length_3" name="newgroupname[]"></td>
				<td class="cp J_set_icon"><img id="J_new_group_icon" data-org="{{ asset('assets/images') }}/level/0.gif" src="{{ asset('assets/images') }}/level/0.gif"><input id="J_new_group_icon_input" type="hidden" name="newgroupimage[]" value="0.gif" /></td>

@if ($groupType == 'member')

				<td><input id="J_new_group_points" type="number" class="input length_2" name="newgrouppoints[]"></td>
				<!--# } #-->
				<td><a id="J_inset_new_group" href="" class="link_add">添加</a></td>
			</tr>
		</tbody>
		
		<!--# } #-->
	</table>
</div>
<div class="btn_wrap">
	<div class="btn_wrap_pd" id="J_sub_wrap">
   <button type="submit" class="btn btn_submit J_ajax_submit_btn">提交</button>
	</div>
</div>
</form>

<div class="core_pop_wrap" id="J_icon_pop" style="display:none;">
<div class="core_pop">
	<div style="width:400px;">
	<div class="pop_top J_drag_handle">
		<a href="#" class="pop_close" id="J_icon_pop_close">关闭</a>
		<strong>等级图标</strong>
	</div>
	<div class="pop_cont" style="padding:3px 15px 18px;">
		<ul class="double_list cc">

@foreach($imageFiles as $v)

			<li><a href="#" class="J_insert_icon" data-name="{{ $v }}"><img src="{{ asset('assets/images') }}/level/{{ $v }}" align="absmiddle"></a></li>
		<!--# } #-->
		</ul>
	</div>
	</div>
</div>
</div>


</div>
@include('admin.common.footer')
<script>
Wind.use('draggable', function(){
	var group_list = $('#J_groupList');
	
	//添加新组
	$('#J_inset_new_group').click(function(e){
		e.preventDefault();
		var new_group_name = $('#J_new_group_name'),
			new_group_icon = $('#J_new_group_icon'),
			new_group_icon_input = $('#J_new_group_icon_input'),
			new_group_points = $('#J_new_group_points');

		if (!(new_group_name.val().replace(/\s/g, ''))) {
			new_group_name.focus();
			return false;
		}
		new_group_html = '<tr class="ct">\
			<td></td>\
			<td><input type="text" value="'+ new_group_name.val() +'" name="newgroupname[]" class="input length_3"></td>\
			<td class="cp J_set_icon"><img src="'+ new_group_icon.attr('src') +'"><input type="hidden" value="'+ new_group_icon_input.val() +'" name="newgroupimage[]"></td>'+ ( new_group_points.length ? '<td><input type="number" value="'+ new_group_points.val() +'" name="newgrouppoints[]" class="input length_2"></td>' : '' ) +'<td>\
				<a class="mr10 J_new_group_del" href="">[删除]</a>\
			</td>\
		</tr>';
		group_list.append(new_group_html);
		new_group_name.val('');
		new_group_icon.attr('src', new_group_icon.data('org'));
		new_group_icon_input.val('0.gif');
		new_group_points.val('');
	});
	
	//删除未提交的组
	Wind.use('dialog',function() {
		group_list.on('click', 'a.J_new_group_del', function(e) {
			e.preventDefault();
			var $this = $(this);
			Wind.dialog.confirm('确定要删除吗？',function() {
				$this.parents('tr.ct').fadeOut('fast', function(){
					$(this).remove();
				});
			})
		});
	});

	//设置等级图标
	var icon_pop = $('#J_icon_pop');
	$('#J_group_table').on('click', '.J_set_icon', function(e){
		e.preventDefault();
		var $this = $(this),
				icon = $this.children('img');
		//common.js
		popPos(icon_pop);

		icon_pop.draggable( { handle : '.J_drag_handle'} );

		$('a.J_insert_icon').off('click').on('click', function(e){
			e.preventDefault();
			icon.attr('src', $(this).children().attr('src'));
			$this.find('input:hidden').val($(this).data('name'));
			icon_pop.hide();
		});
	});

	//关闭
	$('#J_icon_pop_close').on('click', function(e){
		e.preventDefault();
		icon_pop.hide();
	});
})
</script>
</body>
</html>