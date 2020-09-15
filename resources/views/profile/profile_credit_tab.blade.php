<div class="profile_nav">
	<ul>

@foreach ($_tabs as $key => $_item)
if (($key == 'recharge') && !Core::C('pay', 'ifopen')) continue;
	if (isset($_item['url']) && $_item['url']) {
#-->
		<li class="{{ $_item['current'] }}"><a href="{{ url($_item['url'], array('_tab' => $key)) }}">{{ $_item['title'] }}</a></li>

@else

		<li class="{{ $_item['current'] }}"><a href="{{ url('profile/extends/run?_left=credit&_tab=' . $key) }}">{{ $_item['title'] }}</a></li>
<!--#
	}
}#-->
	</ul>
</div>