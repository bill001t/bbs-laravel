<div class="menubar">
	<ul>
<!--#
$_profileLeft = Wind::getApp()->getResponse()->getData('G','profileLeft');
foreach ($_profileLeft as $key => $_item) {
	if (isset($_item['url']) && $_item['url']) {
#-->
		<li class="{{ $_item['current'] }}"><a href="{{ url($_item['url'], array('_left' => $key)) }}" id="profile_{{ $key }}">{{ $_item['title'] }}</a></li>

@else

		<li class="{{ $_item['current'] }}"><a href="{{ url('profile/extends/run?_left=' . $key) }}" id="profile_{{ $key }}">{{ $_item['title'] }}</a></li>
<!--#
	}
}#-->
	</ul>
</div>