
@if($notice['typeid'])
list($typeName) = explode('_',$typeName);
	$typeTpl = 'notice_segment_' . $typeName;
 #-->
{{-- <segment alias='notice_detaillist' tpl="$typeTpl" args='$detailList,$notice' name="detaillist" /> --}}
<!--# } #-->