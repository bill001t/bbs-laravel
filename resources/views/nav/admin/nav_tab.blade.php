<div class="nav">
	<ul class="cc">

@foreach ($navTypeList as  $key=>$value)


@if($navType == $key)

			<li class="current"><a href="{{ url('nav/nav/run?type=' . $key) }}">{{ $value }}</a></li>

@else

			<li><a href="{{ url('nav/nav/run?type=' . $key) }}">{{ $value }}</a></li>
		<!--# } #-->
	<!--# } #-->
	</ul>
</div>