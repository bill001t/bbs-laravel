
@if($notice['typeid'])
list($typeName) = explode('_',$typeName);
	$typeTpl = 'notice_segment_' . $typeName;
 #-->
{{-- <segment alias='notice_list' tpl="$typeTpl" args='$detailList,$notice,$prevNotice,$nextNotice' name="detail" /> --}}
<!--# } #-->