<!doctype html>
<html>
<head>
@include('common.head')
</head>
<body>
<design role="start"/>
	<!--# 
	$wrapall = !$portal['header'] ? 'custom_wrap' : 'wrap';
	#-->
	<div class="{{ $wrapall }}">

@if($portal['header'])

	{{-- @include('common.header') --}}
	<!--# } #-->
	<div class="main_wrap">

@if($portal['navigate'])

		<div class="bread_crumb">{!! $headguide !!}</div>
	<!--# } #-->
		<div class="cc">
			<design role="tips" id="nodesign"/>
			<design role="segment" id="segment1"/>
		</div>
	</div>

@if($portal['footer'])

	{{--  @include('common.footer') --}}
	<!--# } #-->
	</div>
<script>
Wind.use('jquery', 'global');
</script>
<design role="end"/>
</body>
</html>