<!doctype html>
<html>
<head>
@include('admin.common.head')
</head>
<body>
<div class="wrap">

	<div class="nav">
		<ul class="cc">
			<li class="current"><a href="{{ url('log/manage/run') }}">前台日志</a></li>
			<li class=""><a href="{{ url('log/adminlog/run') }}">后台日志</a></li>
			<li class=""><a href="{{ url('log/loginlog/run') }}">用户登录日志</a></li>
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
		<form action="{{ url('log/manage/run') }}" method="post">
		<div class="ul_wrap">
			<ul class="cc">
				<li>
					<label>操作对象：</label><input type="text" class="input length_3" value="{{ $searchData['operated_user'] }}" name="operated_user">
				</li>
				<li>
					<label>操作者：</label><input type="text" class="input length_3" value="{{ $searchData['created_user'] }}" name="created_user">
				</li>
				<li>
					<label>操作描述：</label><input type="text" class="input length_3" value="{{ $searchData['keywords'] }}" name="keywords">
				</li>
				<li>
					<label>操作类型：</label><select name="typeid" class="select_3">
						<option value="" {{ App\Core\Tool::isSelected(!$searchData['typeid']) }}>全部</option>

@foreach ($typeids as $_type => $_typeid)

						<option value="{{ $_typeid }}" {{ App\Core\Tool::isSelected($_typeid == $searchData['typeid']) }}>{{ $typeTitles[$_type] }}</option>
					<!--#}#-->
					</select>
				</li>
				<li>
					<label>所属版块：</label><select name="fid" class="select_3">
						<option value="">所有版块</option>

@foreach($catedb as $cate)

						<optgroup label=">>{{ $cate['name'] }}">

@if ($forumList[$cate['fid']])
foreach ($forumList[$cate['fid']] as $forum) { #-->
									<option value="{{ $forum['fid'] }}" {{ App\Core\Tool::isSelected($forum['fid'] == $searchData['fid']) }}>{!! $forum['level'] !!}|--{{ $forum['name'] }}</option>						<!--#}}#-->
						</optgroup>
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
					<td width="80">操作类型</td>
					<td width="90">操作者</td>
					<td width="90">操作对象</td>
					<td width="90">所属版块</td>
					<td>操作描述</td>
					<td width="120">操作时间</td>
					<td width="80">IP</td>
				</tr>
			</thead>

@foreach ($list as $key => $_item)
$forumN = ($_item['fid']) ? $allForumList[$_item['fid']]['name'] : '----------';
	#-->
			<tr>
				<td>{{ $_item['type'] }}</td>
				<td><a href="{{ url('space/index/run?uid=' . $_item['created_userid']|pw) }}" target="_blank">{{ $_item['created_username'] }}</a></td>
				<td><a href="{{ url('space/index/run?uid=' . $_item['operated_uid']|pw) }}" target="_blank">{{ $_item['operated_username'] }}</a></td>
				<td>{{ $forumN|text }}</td>
				<td>{!! $_item['content'] !!}</td>
				<td>{{ App\Core\Tool::time2str($_item['created_time'], 'Y-m-d H:i:s') }}</td>
				<td>{{ $_item['ip'] }}</td>
			</tr>
	<!--#}#-->
		</table>
		<div class="p10">
			<page tpl='TPL:common.page' page='$page' count='$count' per='$perpage' url='admin/log/manage/run' args='$searchData'/>
		</div>
	</div>

@if ($canClear)

	<div class="btn_wrap">
		<div class="btn_wrap_pd">
			<a class="btn" id="J_clear" href="{{ url('log/manage/clear') }}">确定清除3个月前日志</a>
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