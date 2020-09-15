<div class="content">
	@include('profile_run_tab')
	<div class="profile_educat">
		<ul id="J_edu_list">
			<li class="hd">
				<span class="fr"><button type="button" id="J_edu_add" class="btn btn_submit fn f12">+添加教育经历</button></span>
				<span class="edu">学历</span>
				<span class="unit">学校名称</span>
				<span class="time">入学年份</span>
			</li>

@if ($list)


@foreach ($list as $id => $item)

			<li>
				<span class="fr"><a href="{{ url('profile/education/edit?id=' . $id) }}" data-pid="{{ $item['areaid'][0] }}" data-cid="{{ $item['areaid'][1] }}" data-did="{{ $item['areaid'][2] }}" data-degreeid="{{ $item['degreeid'] }}" data-school="{{ $item['school'] }}" data-schoolid="{{ $item['schoolid'] }}" data-startyear="{{ $item['start_time'] }}" class="mr20 J_school_edit">编辑</a><a href="#" data-uri="{{ url('profile/education/delete?&page=' . $page, $args) }}" data-pdata="{'id':'{{ $id }}'}" class="J_edu_del">删除</a></span>
				<span class="edu">{{ $item['degree'] }}</span>
				<span class="unit">{{ $item['school'] }}</span>
				<span class="time">{{ $item['start_time'] }}年</span>
			</li>
	<!--#}#-->

@else

			<li class="tac p20" id="J_work_none">你还没添加教育经历，<a href="#" class="s4" id="J_edu_add_trigger">立即添加&gt;&gt;</a></li>
<!--# } #-->
			<li id="J_edu_op_wrap" style="display:none;">
				<form id="J_edu_form" action="{{ url('profile/education/add') }}" method="post">
					<span class="fr"><button type="submit" id="J_edu_save" class="btn btn_submit mr20">保存</button><a href="" id="J_edu_cancl">取消</a></span>
					<span class="edu">
					<select class="select_2" id="J_edu_select" name="degree">

@foreach ($degrees as $key => $value)

					<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key === 5) }}>{{ $value }}</option>
					<!--#}#--></select></span>
					<span class="unit"><input type="text" class="input length_3 J_plugin_school" data-typeid="3" name="school" readonly="true" />
						<input id="J_edit_id" type="hidden" name="schoolid" value=""/></span>
					<span class="time"><select id="J_startyear" class="select_2" name="startYear">
					<option value="">入学年份</option>

@foreach ($years as $var)

					<option value="{{ $var }}">{{ $var }}</option>
					<!--#}#--></select></span>
				</form>
			</li>
		</ul>
	</div>
</div>
<script>
var URL_EDU_ADD = "{{ url('profile/education/add') }}",
	URL_EDU_EDIT = "{{ url('profile/education/edit') }}";
Wind.ready(document, function(){
	Wind.use('jquery', 'global', GV.JS_ROOT +'pages/profile/profileEducation.js?v=' +GV.JS_VERSION);
});
</script>
