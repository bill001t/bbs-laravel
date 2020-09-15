<div class="content">
	<div class="profile_nav">
		<ul>
			<li class="current"><a href="{{ url('manage/content/run') }}">主题审核</a></li>
			<li><a href="{{ url('manage/content/reply') }}">回复审核</a></li>
		</ul>
	</div>
	<div class="profile_search">
		<form method="post" action="{{ url('manage/content/run') }}">
		<h2>搜索</h2>
		<table width="100%">
			<colgroup>
				<col>
				<col width="200">
			</colgroup>
			<tr>
				<th>作者</th>
				<td><input type="text" name="author"  class="input length_3" value="{{ $url['author'] }}"></td>
				<th>所属版块</th>
				<td><select name="fid" class="select_3"><option value="0">所有版块</option>{!! $option_html !!}</select></td>
			</tr>
			<tr>
				<th>发帖时间</th>
				<td colspan="3"><input type="text" class="input length_3 mr10 J_date" name="created_time_start"  value="{{ $url['created_time_start'] }}"><span class="mr10">至</span><input type="text" class="input length_3 J_date"  name="created_time_end" value="{{ $url['created_time_end'] }}"></td>
			</tr>
		</table>
		<div class="tac"><button type="submit" class="btn">搜索</button></div>
		</form>
	</div>
	<div class="profile_table">

@if ($threadb)

	<form class="J_form_ajax" action="{{ url('manage/content/doPassThread') }}" method="post">
		<h2>帖子列表</h2>
		<table width="100%" class="J_check_wrap">
			<colgroup>
				<col width="60">
				<col width="415">
				<col width="115">
				<col>
			</colgroup>
			<thead>
				<tr>
					<td><label><input type="checkbox" class="J_check_all">全选</label></td>
					<td>标题</td>
					<td>作者</td>
					<td>发帖时间</td>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<td><label><input type="checkbox" class="J_check_all">全选</label></td>
					<td><button type="submit" class="btn btn_submit J_form_sub_check">通过</button><button type="submit" class="btn J_form_sub_check" data-action="{{ url('manage/content/doDeleteThread') }}">拒绝</button></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
			</tfoot>

@foreach ($threadb as $key => $value)

			<tr>
				<td><input type="checkbox" class="J_check" name="tid[]" value="{{ $value[tid] }}" ></td>
				<td>
					<a href="{{ url('bbs/read/run?tid=' . $value['tid'] . '&fid=' . $value['fid']) }}" target="_blank">{{ App\Core\Tool::stripWindCode($value['subject']) }}</a>
					<!--p class="summary">
					他不善言辞，从不接受媒体采访，却以“J.Wong”的论坛 ID 为外界熟谙；他彻夜潜水于魅族论坛，偶尔喋喋不休，偶尔勃然大怒；他深居简出捣腾动辄万元的音乐器材，一月只出一次门理发，以至于公司五层的办公室空空荡荡。
					</p-->
				</td>
				<td><a href="{{ url('space/index/run?uid=' . $value['created_userid']) }}">{{ $value['created_username'] }}</a></td>
				<td>{{ App\Core\Tool::time2str($value['created_time']) }}</td>
			</tr>
	<!--# } #-->
		</table>
			<div class="p10">
				<page tpl='TPL:common.page' page='$page' count='$count' per='$perpage' url='manage/content/run' args='$url'/>
			</div>
		</form>

@else

		<div class="not_content">啊哦，没有符合条件的内容！</div>
	<!--# } #-->
	</div>
</div>