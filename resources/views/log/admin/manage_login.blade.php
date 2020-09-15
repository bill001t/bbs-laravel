<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

	<div class="nav">
		<ul class="cc">
			<li><a href="{{ url('log/manage/run') }}">前台日志</a></li>
			<li><a href="{{ url('log/adminlog/run') }}">后台日志</a></li>
			<li class="current"><a href="{{ url('log/loginlog/run') }}">用户登录日志</a></li>
		</ul>
	</div>
	<div class="h_a">功能说明</div>
	<div class="prompt_text">
		<ol>
			<li>为了保证后台的安全性，系统只允许站点创始人删除日志</li>
			<li>只允许删除3个月前的日志</li>
		</ol>
	</div>
	<div class="h_a">搜索</div>
	<div class="search_type cc mb10">
		<form action="{{ url('log/loginlog/run') }}" method="post">
		<div class="ul_wrap">
			<ul class="cc">
				<li>
					<label>用户名：</label><input type="text" class="input length_3" value="{{ $searchData['created_user'] }}" name="created_user">
				</li>
				<li>
					<label>错误类型：</label>
					<select name="typeid">
					<option value="" {{ App\Core\Tool::isSelected(!$searchData['typeid']) }}>全部</option>

@foreach ($types as $_id => $_name)

					<option value="{{ $_id }}" {{ App\Core\Tool::isSelected($_id == $searchData['typeid']) }}>{{ $_name }}</option>
					<!--#}#-->
					</select>
				</li>
				<li>
					<label>IP地址：</label><input type="text" class="input length_3" name="ip" value="{{ $searchData['ip'] }}">
				</li>
				<li>
					<label>操作时间：</label><input class="input length_2 mr5 J_date" type="text" name="start_time" value="{{ $searchData['start_time'] }}"><span class="mr5">至</span><input class="input length_2 J_date" type="text" name="end_time" value="{{ $searchData['end_time'] }}">
				</li>
			</ul>
		</div>
		<div class="btn_side">
			<button class="btn btn_submit" type="submit">搜索</button>
		</div>
		</form>
	</div>
	
	<div class="table_list">
		<table width="100%">
			<thead>
				<tr>
					<td width="80">用户名</td>
					<td width="90">错误类型</td>
					<td width="90">时间</td>
					<td width="80">IP</td>
				</tr>
			</thead>

@foreach ($list as $key => $_item)

			<tr>
				<td><a href="{{ url('space/index/run?uid=' . $_item['uid']|pw) }}" target="_blank">{{ $_item['username'] }}</a></td>
				<td>{{ $types[$_item['typeid']] }}</td>
				<td>{{ App\Core\Tool::time2str($_item['created_time'], 'Y-m-d H:i:s') }}</td>
				<td>{{ $_item['ip'] }}</td>
			</tr>
	<!--#}#-->
		</table>
		<div class="p10">
			<page tpl='TPL:common.page' page='$page' count='$count' per='$perpage' url='admin/log/loginlog/run' args='$searchData'/>
		</div>
	</div>

@if ($canClear)

	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<a class="btn" id="J_clear" href="{{ url('log/loginlog/clear') }}">确定清除3个月前日志</a>
		</div>
	</div>
	<!--#}#-->
@include('admin.common.footer')
</div>
<script>
Wind.use('ajaxForm', 'dialog', function(){
	var clear = $('#J_clear');
	clear.on('click', function(e){
		e.preventDefault();

		Wind.dialog({
			type : 'confirm',
			isMask	: false,
			message : '确认删除？',
			follow	: clear,
			onOk	: function() {
				clear.parent().find('span').remove();

				$.post(clear.attr('href'), {
					step : '2'
				}, function(data){
					if(data.state == 'success') {
						$( '<span class="tips_success">' + data.message + '</span>' ).appendTo(clear.parent()).fadeIn('slow').delay( 1000 ).fadeOut(function(){
							reloadPage(window);
						});
					}else if(data.state == 'fail'){
						$( '<span class="tips_error">' + data.message + '</span>' ).appendTo(clear.parent()).fadeIn( 'fast' );
					}
				}, 'json');
			}
		});
	});
});
</script>
</body>
</html>