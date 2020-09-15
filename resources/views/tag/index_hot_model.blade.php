
@if($hotTagList)

<div class="box_wrap">
	<h2 class="box_title">热门话题</h2>
	<div class="tag_hot_list">
		<ul>

@foreach($hotTagList as $k=>$v)

			<li><a href="{{ url('tag/index/view?name=' . $v['tag_name']) }}" class="title">{{ $v['tag_name'] }}</a><em class="num">{{ $v['content_count'] }}</em></li>
			<!--# } if (count($hotTagList) > 9) { #-->
			<li><a href="{{ url('tag/index/run') }}#tag_more_list" class="title">查看更多</a></li>
			<!--# } #-->
		</ul>
	</div>
</div>
<!--# } #-->