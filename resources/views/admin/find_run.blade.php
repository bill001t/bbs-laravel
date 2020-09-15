<!doctype html>
<html>
<head>
@include('common.head')
</head>
<body>
<div class="wrap">

<!--搜索开始-->
	<div class="h_a">有关“<span class="red">{{ $keyword }}</span>”的搜索结果</div>
	<div class="search_list">

@if($result)


@foreach($result as $k=>$v)

			<h2><a class="J_tabframe_trigger" href="{!! $v['url'] !!}&searchword={{ $keyword }}" data-id="{!! $k !!}" data-parent="{!! $v['parent'] !!}" data-level="{{ $v['level'] }}">{!! $v['name'] !!}</a></h2>

@if($v['items'])
foreach($v['items'] as $v2){
			#-->
			<dl>
				<dd>{!! $v2 !!}</dd>
			</dl>
				<!--# } #-->
			<!--# } #-->

@if ($v['sub'])

			<dl>

@foreach($v['sub'] as $v2)

				<dt><a class="J_tabframe_trigger" href="{!! $v2['url'] !!}&searchword={{ $keyword }}" data-id="{!! $k !!}" data-parent="{!! $v['parent'] !!}" data-level="{{ $v['level'] }}">{!! $v2['name'] !!}</a></dt>

@foreach($v2['items'] as $v3)

				<dd>{!! $v3 !!}</dd>
					<!--# } #-->
				<!--# } #-->
			</dl>
			<!--# } #-->
		<!--# 
			}
		} else { #-->
		<dl>
			<dt><span class="red">没有找到相关内容</span></dt>
		</dl>
		<!--# } #-->
	</div>

<!--搜索结束-->

</div>
{{--  @include('common.footer') --}}
</body>
</html>