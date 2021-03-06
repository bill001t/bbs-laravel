<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">	
	<div class="nav">
		<ul class="cc">
			<li  class="current"><a href="{{ url('design/component/run') }}">模块模板</a></li>
			
		</ul>
	</div>
	<div class="h_a">搜索</div>
	<form method="post"  action="{{ url('design/component/run') }}" >
	<div class="search_type cc mb10">
		<span class="mr20">ID：<input type="text" class="input length_2" name="compid"></span>
		<span class="mr20">模块名称：<input type="text" class="input length_2" value="{{ $args['compname'] }}" name="compname"></span>
		<span class="mr20">模块分类：
			<select class="select_2" name="flag">
				<option value="">模块分类</option>

@foreach ($models as $key=>$model)

				<option value="{{ $key }}" {{ App\Core\Tool::isSelected($key == $flag) }}>{{ $model['name'] }}</option>
			<!--# } #-->
			</select>
		</span>
		<button class="btn" type="submit">搜索</button>
	</div>
	</form>
	<div class="mb10">
			<a  class="btn" href="{{ url('design/component/add1') }}"><span class="add"></span>添加模板</a>
	</div>

@if ($list)

	<div class="table_list">
		<table width="100%">
			<colgroup>
				<col width="60">
				<col width="350">
				<col width="120">
			</colgroup>
			<thead>
				<tr>
					<td>ID</td>
					<td>模块名称</td>
					<td>模块分类</td>
					<td>操作</td>
				</tr>
			</thead>

@foreach ($list as $v)

			<tr>
				<td>{{ $v['comp_id'] }}</td>
				<td>{{ $v['comp_name'] }}</td>
				<td>{{ $models[$v['model_flag']]['name'] }}</td>
				<td><a href="{{ url('design/component/edit?id=' . $v['comp_id'] . '&page=' . $page) }}" class="mr10">[编辑]</a><a href="{{ url('design/component/del') }}" class="J_ajax_del" data-pdata="{'id': {{ $v['comp_id'] }}}">[删除]</a></td>
			</tr>
			<!--# } #-->
		</table>
		<div class="p10"><page tpl='TPL:common.page' page="$page" per="$perpage" count="$count" url="design/component/run" args="$args"/></div>
	</div>

@else

			<div class="not_content_mini"><i></i>啊哦，没有符合条件的内容！</div>
			<!--# } #-->


	
</div>
@include('admin.common.footer')
</body>
</html>