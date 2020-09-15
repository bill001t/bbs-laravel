<div class="space_header cc">
	<div class="title">
		<h1><a href="{{ $space->space['domain'] }}">{{ $space->space['space_name'] }}</a></h1><div class="num">访问量<span><em></em>{{ $space->space['visit_count'] }}</span></div>
	</div>
	<div class="descrip">{{ $space->space['space_descrip'] }}</div>
	<div class="space_nav">
		<ul>
			<li class="{{ App\Core\Tool::isCurrent($src == 'index') }}"><a href="{{ url('space/index/run?uid=' . $space->spaceUid) }}">新鲜事</a></li>
			<li class="{{ App\Core\Tool::isCurrent($src == 'thread') }}"><a href="{{ url('space/thread/run?uid=' . $space->spaceUid) }}">帖子</a></li>
			{{-- <hook name="space_nav" args="array($space, $src)" /> --}}
			<li class="{{ App\Core\Tool::isCurrent($src == 'profile') }}"><a href="{{ url('space/profile/run?uid=' . $space->spaceUid) }}">资料</a></li>
		</ul>
	</div>

@if ($space->tome == 2)

	<!--div class="fr"><div class="space_set_tip"></div></div-->
	<!--# } #-->
	<div class="url"><a href="{{ $space->space['domain'] }}">{{ $space->space['domain'] }}</a></div>
</div>

@if ($space->tome == 2)

		<a id="J_space_set" href="{{ url('space/myspace/run') }}" class="design_space_edit">模块管理</a>
	<!--# } #-->
