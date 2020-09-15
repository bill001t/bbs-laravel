
@if (Core::getLoginUser()->isExists())

<div class="box_wrap">
	<h2 class="box_title">我的话题</h2>
	<div class="side_cate_list">

@if ($myTags['tags'])

		<ul id="J_side_my_tags">

@foreach($myTags['tags'] as $v)

			<li><a data-id="{{ $v['tag_id'] }}" title="取消关注该话题" href="{{ url('/tag/index/attention?type=del&id=' . $v['tag_id']) }}" class="icon_del J_tag_del">删除</a><a href="{{ url('/tag/index/view?name=' . $v['tag_name']) }}" class="title">{{ $v['tag_name'] }}<em>({$v['content_count']})</em></a></li>
			<!--# } if ($myTags['step']) { #-->
			<li><a id="J_tag_more" href="{{ url('tag/index/attentionlist') }}" data-viewurl="{{ url('/tag//view') }}" data-delurl="{{ url('/tag/index/attention?type=del') }}" class="title" data-step="{{ $myTags['step'] }}">查看更多</a></li>
			<!--# } #-->
		</ul>

@else

		<div style="padding:20px 0;"><div class="not_content_mini"><i></i>你还没关注任何话题</div></div>
		<!--# } #-->
	</div>
</div>
<!--# } #-->