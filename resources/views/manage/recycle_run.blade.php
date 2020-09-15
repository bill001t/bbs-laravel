<div class="content">
	<div class="profile_nav">
		<ul>
			<li class="current"><a href="{{ url('manage/recycle/run') }}">主题回收站</a></li>
			<li><a href="{{ url('manage/recycle/reply') }}">回复回收站</a></li>
		</ul>
	</div>
	<div class="profile_search">
		<form method="post" action="{{ url('manage/recycle/run') }}">
		<h2>帖子搜索</h2>
		<table width="100%">
			<colgroup>
				<col>
				<col width="200">
			</colgroup>
			<tr>
				<th>关键字</th>
				<td><input type="text" name="keyword"  class="input length_3" placeholder="可使用通配符*" value="{{ $url['keyword'] }}"></td>
				<th>所属版块</th>
				<td><select name="fid" class="select_3"><option value="0">所有版块</option>{!! $option_html !!}</select></td>
			</tr>
			<tr>
				<th>作者</th>
				<td><input type="text" name="author"  class="input length_3" value="{{ $url['author'] }}"></td>
				<th>发帖时间</th>
				<td><input type="text" class="input length_3 mr10 J_date" name="created_time_start" value="{{ $url['created_time_start'] }}"><span class="mr10">至</span><input type="text" class="input length_3 J_date"  name="created_time_end" value="{{ $url['created_time_end'] }}"></td>
			</tr>
			<tr>
				<th>删除人</th>
				<td><input type="text" name="operator" class="input length_3" value="{{ $url['operator'] }}"></td>
				<th>删除时间</th>
				<td><input type="text" name="operate_time_start"  class="input length_3 mr10 J_date" value="{{ $url['operate_time_start'] }}"><span class="mr10">至</span><input type="text" name="operate_time_end" class="input length_3 J_date" value="{{ $url['operate_time_end'] }}"></td>
			</tr>
		</table>
		<div class="tac"><button type="submit" class="btn">搜索</button></div>
		</form>
	</div>
	<div class="profile_table">

@if ($threaddb)

	<form class="J_form_ajax" action="{{ url('manage/recycle/doRevertTopic') }}" method="post">
		<h2>帖子列表</h2>
		<table width="100%" class="J_check_wrap">
			<colgroup>
				<col width="60">
				<col width="300">
				<col width="115">
				<col width="115">
				<col>
			</colgroup>
			<thead>
				<tr>
					<td><label><input type="checkbox" class="J_check_all">全选</label></td>
					<td>标题</td>
					<td>作者/发帖时间</td>
					<td>删除人/删除时间</td>
					<td>删除理由</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td><label><input type="checkbox" class="J_check_all">全选</label></td>
					<td><button type="submit" class="btn btn_submit J_form_sub_check">还原</button><button type="submit" class="btn J_form_sub_check" data-action="{{ url('manage/recycle/doDeleteTopic') }}">彻底删除</button></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</tfoot>

@foreach ($threaddb as $key => $value)

			<tr>
				<td><input type="checkbox" class="J_check" name="tids[]" value="{{ $value[tid] }}" ></td>
				<td>{{ $value['subject'] }}</td>
				<td><a href="{{ url('space/index/run?uid=' . $value['created_userid']) }}" target="_blank">{{ $value['created_username'] }}</a><br>{{ App\Core\Tool::time2str($value['created_time']) }}</td>
				<td><a href="{{ url('space/index/run?username=' . $value['operate_username']) }}" target="_blank">{{ $value['operate_username'] }}</a><br>{{ App\Core\Tool::time2str($value['operate_time']) }}</td>
				<td>{{ $value['reason'] }}</td>
			</tr>
	<!--# } #-->
		</table>
		</form>
		<div class="p10">
			<page tpl='TPL:common.page' page='$page' count='$count' per='$perpage' url='manage/recycle/run' args='$url'/>
		</div>

@else

		<div class="not_content">啊哦，没有符合条件的内容！</div>
	<!--# } #-->
	</div>
</div>