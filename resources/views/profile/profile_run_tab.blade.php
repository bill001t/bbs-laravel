<div class="profile_nav">
	<a href="{{ url('profile/secret/run?_left=secret') }}" class="fr a_privacy">隐私设置</a>
	<ul>

@foreach ($_tabs as $key => $_item)
if (isset($_item['url']) && $_item['url']) {
#-->
		<li class="{{ $_item['current'] }}"><a href="{{ url($_item['url'], array('_tab' => $key)) }}">{{ $_item['title'] }}</a></li>

@else

		<li class="{{ $_item['current'] }}"><a href="{{ url('profile/extends/run?_left=profile&_tab=' . $key) }}">{{ $_item['title'] }}</a></li>
<!--#
	}
}#-->
	</ul>
</div>