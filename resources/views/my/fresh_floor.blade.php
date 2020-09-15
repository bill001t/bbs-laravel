							<dl class="cc"{!! $new_replyattr !!}>
								<dt class="feed_face"><a class="J_user_card_show" data-uid="{{ $fresh['created_userid'] }}" href="{{ url('space/index/run?uid=' . $fresh['created_userid']) }}"><img src="{{ App\Core\Tool::getAvatar($fresh['created_userid'], 'small') }}" width="50" height="50" alt="{{ $fresh['created_username'] }}" class="J_avatar" data-type="small" /></a></dt>
								<dd class="feed_content J_feed_content">

@if (!empty($freshDelete))
<a href="{{ url('my/fresh/delete') }}" data-pdata="{'id':{{ $fresh['id'] }}}" class="feed_lists_del J_fresh_del">删除</a><!--# } #-->
									<div class="content">

@if ($fresh['title'])

										<a data-uid="{{ $fresh['created_userid'] }}" href="{{ url('space/index/run?uid=' . $fresh['created_userid']) }}" class="J_user_card_show name">{{ $fresh['created_username'] }}</a>：
										<em><a href="{{ url('bbs/read/run?tid=' . $fresh['src_id']) }}" class="title">{{ $fresh['title'] }}</a></em>
										<div class="descrip" id="J_feed_content_{{ $fresh['id'] }}">{!! $fresh['content'] !!}

@if ($fresh['is_read_all'])

											<!--em class="J_content_all" style="display:none;"></em-->
											<a target="_blank" href="{{ url('bbs/read/run?tid=' . $fresh['src_id']) }}">全文</a>
										<!--# } #-->
										</div>

@else

										<div class="text"><a data-uid="{{ $fresh['created_userid'] }}" href="{{ url('space/index/run?uid=' . $fresh['created_userid']) }}" class="J_user_card_show name">{{ $fresh['created_username'] }}</a>：
										<em id="J_feed_content_{{ $fresh['id'] }}">{!! $fresh['content'] !!}</em></div>
										<!--# } #-->
										
										<!-- 微博幻灯片 -->

@if ($fresh['pic'])
$_picCount = count($fresh['pic']);
											$k = 0;
										#-->
										<ul class="photo cc J_gallery_list">

@foreach ($fresh['pic'] as $v)
$_isDisplay = $k++ >= 4 ? 'dn' : '';
											#-->
											<li class="fl J_gallery_items {{ @$_isDisplay }}"><a href="javascript:;" data-big="{{ App\Core\Tool::getPath($v['path']) }}"><img onerror="this.onerror=null;this.className='J_error';" src="{{ App\Core\Tool::getPath($v['path'], $v['ifthumb']) }}" alt="" width="120" /></a></li>
											<!--# } #-->

@if($_picCount > 4)
<li class="fl">共{$_picCount}张图片</li><!--#}#-->
										</ul>
										<!--# } #-->
									</div>

@if ($fresh['quote'])

									<div class="feed_repeat feed_quote">
										<div class="feed_repeat_arrow">
											<em>◆</em>
											<span>◆</span>
										</div>
										<div class="content"><a href="{{ url('space/index/run?uid=' . $fresh['quote']['created_userid']) }}" class="name">{{ $fresh['quote']['created_username'] }}</a>：

@if ($fresh['quote']['subject'])
<a href="{{ url('bbs/read/run?tid=' . $fresh['quote']['tid']) }}" class="title">{{ $fresh['quote']['subject'] }}</a><br><!--# } #--><em>{!! $fresh['quote']['content'] !!}</em>
										</div>
										<div class="info">
											<span class="time">{{ App\Core\Tool::time2str($fresh['quote']['created_time'], 'auto') }}</span>&nbsp;&nbsp;<a href="{{ $fresh['quote']['url'] }}">回复
@if ($fresh['quote']['replies'])
({$fresh['quote']['replies']})<!--# } #--></a>
										</div>
									</div>
									<!--# } #-->
									<div class="info">
										<span class="right"><a data-id="{{ $fresh['id'] }}" class="J_feed_toggle" href="{{ url('my/fresh/reply?id=' . $fresh['id']) }}">回复<span style="{{ @$fresh['replies'] ? '' : 'display:none' }}">(<em id="J_feed_count_{{ $fresh['id'] }}">{{ $fresh['replies'] }}</em>)</span></a></span>
										<span class="time"><a href="{{ url('space/index/fresh?uid=' . $fresh['created_userid'] . '&id=' . $fresh['id']) }}">{{ App\Core\Tool::time2str($fresh['created_time'], 'auto') }}</a></span>&nbsp;来自{!! $fresh['from'] !!}
									</div>
									<div id="J_feed_list_{{ $fresh['id'] }}" class="feed_repeat J_feed_list" style="display:none;"></div>
								</dd>
							</dl>