<hook-action name="displayMedalHtmlAfterContent" args='medals'>
	<div class="medal">
		<ul class="cc">

@foreach ($medals as $medal)

			<li><a rel="nofollow" href="{{ url('medal/index/run') }}"><img src="{{ $medal['icon'] }}" title="{{ $medal['name'] }}" width="30" height="30" alt="{{ $medal['name'] }}" /></a></li>
			<!--# } #-->
		</ul>
	</div>
</hook-action>