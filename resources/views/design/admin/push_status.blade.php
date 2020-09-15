<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap J_check_wrap">
<!--# 
	$check = $shield = '';
	if ($status == 1){
		$check = 'current';
	}elseif ($status == 2) {
		$shield = 'current';
	}
#-->
	<div class="nav">
		<ul class="cc">
			<li><a href="{{ url('design/push/run') }}">显示中数据</a></li>
			<li  class="{{ $check }}"><a href="{{ url('design/push/status?status=1') }}">待审核数据</a></li>
			<li class="{{ $shield }}"><a href="{{ url('design/push/status?status=2') }}">推送数据</a></li>
			<li><a href="{{ url('design/push/shield') }}">屏蔽数据</a></li>
		</ul>
	</div>

@if ($status == 1)

	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ul>
			<li>此列表显示所有未被审核的推送数据。</li>
		</ul>
	</div>

@else

	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ul>
			<li>显示所有审核通过的推送数据，手动添加的数据。</li>
		</ul>
	</div>
	<!--# } #-->
	<div class="h_a">搜索</div>
	<form method="post"  action="{{ url('design/push/status?status=' . $status) }}" >
	<div class="search_type cc mb10">
		<span class="mr20">所属页面：
			<select class="select_2" name="pageid" id="J_flag_initiative">
				<option value="">不限制</option>

@foreach ($pagelist as $v)

				<option value="{{ $v['page_id'] }}" {{ App\Core\Tool::isSelected($v['page_id'] == $pageid) }}>{{ $v['page_name'] }}</option>
			<!--# } #-->
			</select>
		</span>
		<span class="mr20">所属模块：
			<select class="select_2" name="moduleid" id="J_flag_passive">
				<option>不限制</option>
			</select>
		</span>
		<button class="btn" type="submit">搜索</button>
		<input type="hidden"  name="check" value="{{ $check }}">
	</div>
	</form>
	<form class="J_ajaxForm" method="post"  action="{{ url('design/push/batchcheck') }}" >

@if ($list)

	<div class="table_list">
		<table width="100%">
			<colgroup>
				<col width="70">
				<col width="200">
				<col width="160">
				<col width="90">
				<col width="90">
			</colgroup>
			<thead>
				<tr>
					<td><label><input type="checkbox" data-checklist="J_check_y" data-direction="y" class="J_check_all" name="checkAll">全选</label></td>
					<td>标题</td>
					<td>所属模块</td>
					<td>推荐人</td>

@if ($status == 1)

					<td>推荐时间</td>

@else

					<td>推送时间</td>
					<!--# } #-->
					<td>操作</td>
				</tr>
			</thead>

@foreach ($list as $v)
$isExpired = false;
				if ($v['end_time'] > 0 && $v['end_time'] < App\Core\Tool::getTime()) $isExpired = true;
			#-->
			<tr>
				<td><input data-yid="J_check_y" data-xid="J_check_x" class="J_check" type="checkbox" name="pushids[]" value="{{ $v['push_id'] }}"></td>
				<td>
@if ($isExpired)
<span style="color:#990000">[已过期]</span><!--# } #--><a href="{{ $v['url'] }}" target="_blank">{{ $v['title'] }}</a></td>
				<td>{{ $modules[$v['module_id']]['module_name'] }}</td>
				<td>{{ $users[$v['created_userid']]['username'] }}</td>

@if ($status == 1)

				<td>{{ App\Core\Tool::time2str($v['created_time']) }}</td>

@else

				<td>{{ App\Core\Tool::time2str($v['start_time']) }}</td>
				<!--# } #-->
				<td>
					<a href="{{ url('design/push/delpush') }}" class="mr10 J_ajax_del" data-pdata="{'pushid': {{ $v['push_id'] }}}">[删除]</a>

@if ($status == 1)

					<a href="{{ url('design/push/dopush') }}" class="mr10 J_ajax_refresh" data-pdata="{'pushid': {{ $v['push_id'] }}}">[通过]</a>
					<!--# } #-->
				</td>
			</tr>
		<!--# } #-->
		</table>
		<div class="p10"><page tpl='TPL:common.page'  total="$totalpage" page="$page" per="$perpage" count="$count" url="design/push/status"  args='$args'/></div>
	</div>

@else

		<div class="not_content_mini"><i></i>啊哦，没有符合条件的内容！</div>
		<!--# } #-->

@if ($list)

	<div class="btn_wrap">
	<div class="btn_wrap_pd">
		<label class="mr20"><input type="checkbox" data-checklist="J_check_x" data-direction="x" class="J_check_all" name="checkAll">全选</label>

@if ($status == 1)

		<button data-subcheck="true" class="btn btn_submit J_ajax_submit_btn" type="submit">通过</button>
		<!--# } #-->
		<button data-subcheck="true" data-msg="确定要删除选中内容?" class="btn btn_submit J_ajax_submit_btn" type="button" data-action="{{ url('design/push/batchdelete') }}">删除</button>
	</div>
	</div>
	<!--# } #-->
	</form>	
</div>
@include('admin.common.footer')
<script>
$(function(){
	//select联动
	var flag_passive = $('#J_flag_passive'),
		flag_initiative = $('#J_flag_initiative'),
		moduleid = '{{ $moduleid }}';		//模块id 后端输出

	flag_initiative.on('change', function(){
		flatSet(this.value);
	});

	//
	if(flag_initiative.val()) {
		flatSet(flag_initiative.val(), moduleid);
	}

	function flatSet(pageid, id){
		if(pageid) {
			$.post('{{ url('design/page/getModuleOption') }}', {pageid : pageid}, function(data){
				if(data.state == 'success') {
					flag_passive.html('<option>不限制</option>'+data.html);

					if(id) {
						flag_passive.children('[value='+ id +']').prop('selected', true);
					}
				}else if(data.state == 'fail'){
					//common.js
					resultTip({
						error : true,
						msg : data.message
					});
				}
			}, 'json');
		}else{
			flag_passive.html('<option>不限制</option>');
		}
	}
});
</script>
</body>
</html>