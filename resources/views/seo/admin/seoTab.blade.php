<div class="nav">
	<ul class="cc">

@foreach($tabs as $alias => $tab)

		<li class="{{ $tab['current'] }}"><a href="{{ url($tab['url']) }}">{{ $tab['title'] }}</a></li>
	<!--# } #-->
	</ul>
</div>