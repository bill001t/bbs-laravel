<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">
	<div class="nav">
		<ul class="cc">
			<li><a href="{{ url('tag/manage/run') }}">话题管理</a></li>
			<li class="current"><a href="{{ url('tag/manage/category') }}">话题分类</a></li>
		</ul>
	</div>
	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ul>
			<li>可以将话题归类，方便用户按分类查看。</li>
			<li>分类别名使用英文字符，设置后分类页面的URL可以显示英文名称。</li>
		</ul>
	</div>
	<form class="J_ajaxForm" data-role="list" action="{{ url('tag/manage/setCategory') }}" method="post">
	<div class="table_list">
		<table width="100%">
			<colgroup>
				<col width="65">
			</colgroup>
			<thead>
				<tr>
					<td>顺序</td>
					<td>分类名称</td>
					<td>分类别名</td>
					<td>话题数</td>
					<td>操作</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td colspan="5"><a href="" id="J_cate_add" class="link_add">添加分类</a></td>
				</tr>
			</tfoot>
			<!-- <tr>
				<td><input type="number" class="input length_1" name="data[{{ $value['vieworder'] }}][vieworder]" value="{{ $value['vieworder'] }}"></td>
				<td><input type="text" class="input length_3" name="data[{$value['category_name'category_name" value="{{ $value['category_name'] }}"></td>
				<td><input type="text" class="input length_3" name="data[{$value['alias'alias" value="{{ $value['alias'] }}"></td>
				<td>{{ $value['tagcount'] }}</td>
				<td></td>
			</tr> -->
			<tbody id="J_cate_list">

@foreach ($categorys as $value)
$category = $value['alias'] ? $value['alias'] : $value['category_id'];
			#-->
				<tr>
					<input type="hidden" name="data[{{ $value['category_id'] }}][category_id]" value="{{ $value['category_id'] }}">
					<td><input type="number" class="input length_1 J_cate_order" name="data[{{ $value['category_id'] }}][vieworder]" value="{{ $value['vieworder'] }}"></td>
					<td><input type="text" class="input length_3" name="data[{{ $value['category_id'] }}][category_name]" value="{{ $value['category_name'] }}"></td>
					<td><input type="text" class="input length_3" name="data[{{ $value['category_id'] }}][alias]" value="{{ $value['alias'] }}"></td>
					<td>{{ $value['tag_count'] }}</td>
					<td><!-- a href="{{ url('tag/index/run?categoryid=' . $value['category_id']|pw) }}" class="mr5" target="_blank">[访问]</a--><a href="{{ url('tag/manage/editCategory?id=' . $value['category_id']) }}" title="分类编辑" class="mr5 J_dialog">[编辑]</a><a href="{{ url('tag/manage/run?categoryId=' . $value['category_id']) }}" class="mr5">[话题管理]</a><a href="{{ url('tag/manage/deleteCategory') }}" class="J_ajax_del" data-pdata="{'id': {{ $value['category_id'] }}}">[删除]</a></td>
				</tr>
			<!--# } #-->
			</tbody>
			</tbody>
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
<script type="text/javascript">
$(function(){
	var cate_list = $('#J_cate_list');
	
	//添加分类
	$('#J_cate_add').on('click', function(e){
		e.preventDefault();
		var lastid = $('#J_cate_list > tr').last().find('input.J_cate_order').val();
		if(!lastid) {
			lastid = 0;
		}
		lastid++;

		cate_list.append('<tr class="J_cate_add_tr">\
				<td><input type="number" class="input length_1 J_cate_order" name="newdata['+ lastid +'][vieworder]" value="'+ lastid +'"></td>\
				<td><input type="text" class="input length_3" name="newdata['+ lastid +'][category_name]" value=""></td>\
				<td><input type="text" class="input length_3" name="newdata['+ lastid +'][alias]"></td>\
				<td>0</td>\
				<td><a href="#" class="J_cate_add_del">[删除]</a></td>\
	</tr>');
	});
	
	//删除未保存项
	cate_list.on('click', 'a.J_cate_add_del', function(e){
		e.preventDefault();
		$(this).parents('tr').remove();
	});
	
});
</script>
</body>
</html>

