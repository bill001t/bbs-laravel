<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<!--# 
	$api = $run = '';
	if ($isapi == 'api'){
		$api = 'current';
	}else{
		$run = 'current';
	}
#-->
<div class="wrap">
	<div class="nav">
		<ul class="cc">
			<li class="{{ $run }}"><a href="{{ url('design/module/run') }}">模块管理</a></li>
			<li class="{{ $api }}"><a href="{{ url('design/module/run?type=api') }}">调用管理</a></li>
		</ul>
	</div>
	<div class="h_a">搜索</div>
	<form method="post" action="{{ url('design/module/run') }}">
	<div class="search_type cc mb10">
		<div class="mb10">
		<span class="mr20">ID：<input type="text" class="input length_2" name="moduleid" value="{{ $args['moduleid'] }}"></span>
		<span class="mr20">模块名称：<input type="text" class="input length_2" name="name" value="{{ $args['name'] }}"></span>
		<!-- <span class="mr20">所属页面：
			<select class="select_2" name="pagename">
				<option value="">不限制</option>
			</select>
		</span> -->
		<span class="mr20">数据分类：
			<select class="select_2" name="model">
				<option  value="">不限制</option>

@foreach ($models as $k=>$model)

				<option value="{{ $k }}" {{ App\Core\Tool::isSelected($k == $args['model']) }}>{{ $model['name'] }}</option>
				<!--# } #-->
			</select>
		</span>
		<button class="btn" type="submit">搜索</button>
		</div>
	</div>
	</form>

@if ($list)

	<div class="table_list">
		<table width="100%">
			<colgroup>
				<col width="70">
				<col width="200">
				<col width="200">
				<col width="90">
			</colgroup>
			<thead>
				<tr>
					<td>ID</td>
					<td>模块名称</td>
					<td>所属页面</td>
					<td>数据分类</td>
					<td>操作</td>
				</tr>
			</thead>

@foreach ($list as $v)

			<tr>
				<td>{{ $v['module_id'] }}</td>
				<td>{{ $v['module_name'] }}</td>
				<td> {{ $v['pageInfo']['page_name'] }}
				</td>
				<td>{{ $models[$v['model_flag']]['name'] }}</td>

				<td>

@if ($v['isdata'])

					<a href="{{ url('design/data/run?moduleid=' . $v['module_id']) }}" class="mr10">[管理]</a>

@else

					<a href="{{ url('design/property/edit?moduleid=' . $v['module_id']) }}" class="mr10">[管理]</a>
				<!--# } #-->
					<!--a href="{{ url('design/data/run?moduleid=' . $v['module_id']) }}" class="mr10">[数据]</a-->
					<a href="{{ url('design/permissions/module?moduleid=' . $v['module_id']) }}" class="mr10">[权限管理]</a>
					<!-- <a href="{{ url('design/module/script?moduleid=' . $v['module_id']) }}"  class="mr10">[调用代码]</a> -->
					<!--a href="{{ url('design/module/delete?moduleid=' . $v['module_id']) }}" class="mr10">[删除]</a-->
				</td>
			</tr>
		<!--# } #-->
		</table>
		<div class="p10"><page tpl='TPL:common.page'  total="$totalpage" page="$page" per="$perpage" count="$count" url="design/module/run" args="$args"/></div>
	</div>
	<form class="J_ajaxForm"  action="{{ url('design/module/clear') }}" method="post">
	<div class="btn_wrap">
		<div class="btn_wrap_pd"><button class="btn J_ajax_submit_btn" type="submit">清空前台已删除模块</button></div>
	</div>
	</form>

@else

		<div class="not_content_mini"><i></i>啊哦，没有符合条件的内容！</div>
		<!--# } #-->

</div>
@include('admin.common.footer')
</body>
</html>