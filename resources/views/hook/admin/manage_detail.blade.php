<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
<div class="nav">
	<div class="return">
	<a href="{{ url('hook/manage/run') }}">返回上一级</a>
	</div>
	</div>
	<div class="h_a">基本信息</div>
	<div class="prompt_text">
		<ul>
		<li>系统别名：{{ $hook['name'] }}</li>
		<li>所属模块：{{ $hook['app_name'] }}</li>
		<li>创建时间：{{ App\Core\Tool::time2str($hook['created_time']) }}</li>
		</ul>
	</div>
	<div class="h_a">使用说明</div>
	<div class="prompt_text">
		<pre>{{ $dec }}</pre>
	</div>
	<div class="h_a">参数/返回值</div>
	<div class="prompt_text">
		<pre>{{ $param }}</pre>
	</div>
	<div class="h_a">接口定义</div>
	<div class="prompt_text">
		<pre>{{ $interface }}</pre>
	</div>
	<div class="mb5"><span class="mr20 f14 b ">已注册扩展列表</span>
	<!-- <a class ="btn J_dialog" title="向该钩子下添加新扩展" href="{{ url('hook/inject/add?hook_name=' . $hook['name']) }}"><span class="add"></span>添加</a> -->
	</div>
	<div class="table_list">
		<table width="100%">
			<thead>
				<tr>
					<td>别名</td>
					<td>所属模块</td>
					<td>描述</td>
					<td>类名</td>
					<td>方法名</td>
					<td>加载方式</td>
					<td>挂载条件</td>
					<!-- <td>操作</td> -->
				</tr>
			</thead>

@foreach($injectors as $v)

			<tr>
				<td>{{ $v['alias'] }}</td>
				<td>{{ $v['app_name'] }}</td>
				<td>{{ $v['description'] }}</td>
				<td>{{ $v['class'] }}</td>
				<td>{{ $v['method'] }}</td>
				<td>{{ $v['loadway'] }}</td>
				<td>{{ $v['expression'] }}</td>
				<!-- <td><a class="J_dialog mr10" title="编辑Injector" href="{{ url('hook/inject/edit?id=' . $v['id']) }}">[编辑]</a><a class="J_ajax_del" href="{{ url('hook/inject/del?id=' . $v['id']) }}">[删除]</a></td> -->
			</tr>
			<!--# } #-->
		</table>
	</div>
</div>
@include('admin.common.footer')
</body>
</html>