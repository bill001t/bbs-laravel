<dl class="cc dl" id="vote{{ $value['poll_id'] }}">
							<dt class="dt">
							<a class="J_user_card_show" data-uid="{{ $value['created_uid'] }}" href="{{ url('space/index/run?uid=' . $value['created_uid']) }}"><img src="{{ App\Core\Tool::getAvatar($value['created_uid'], 'small') }}" onerror="this.onerror=null;this.src={{ asset('assets/images') }}/face/face_small.jpg'" width="50" height="50" alt="{{ $value['created_username'] }}" /></a>
							</dt>
							<dd class="content">
								<p class="title">
								<a class="name J_user_card_show" data-uid="{{ $value['created_uid'] }}" href="{{ url('space/index/run?uid=' . $value['created_uid']) }}">{{ $value['created_username'] }}</a>  发起投票：<a href="{{ $value['url'] }}">{{ App\Core\Tool::substrs(App\Core\Tool::stripWindCode(strip_tags($value['title'])), 50, 0, false) }}</a>
								</p>
								<p class="descrip">{{ $value['content'] }}</p>
								<form action="{{ url('vote/vote/run') }}" method="post" id="J_vote_form_{{ $value['poll_id'] }}">
									<div class="read_vote_list" id="J_vote_list_{{ $value['poll_id'] }}">
										<input type="hidden" name="typeid" value="{{ $value['typeid'] }}" />
										<input type="hidden" name="apptype" value="{{ $value['app_type'] }}" />
										<div class="ct">
											<ul class="J_vote_list_ul" data-max="{{ @$value['option_limit'] ? $value['option_limit'] : 1 }}">
<!--# $i = $j = 1; $hiddenInfo = ''; #-->

@foreach ($value['option'] as $key => $option)

<!--#
$j = ($j%5 == 0) ? 1 : $j;
$hiddenInfo = $i > 2  ? 'J_dn dn' : '';
$option['image'] = $option['image'] ? App\Core\Tool::getPath($option['image'], 1) : '';
$optionPercent = $value['votedtotal'] ? round( (100 * $option['voted_num'] / $value['votedtotal']), 2) : 0;
$isView = (!$value['isafter_view'] || $value['isvoted']) ? true : false;
$isAllowForumGroup = ($value['allow_visit'] && $value['allow_read']) ? true : false;
$isVote = (!$value['expired_time'] || ($value['expired_time'] && $value['expired_time'] > App\Core\Tool::getTime())) && $pollGroup['allowvote'] && $isAllowForumGroup ? true : false;
#-->

@if($value['isinclude_img'])

											<li class="li_img {{ $hiddenInfo }}">
												<div class="options cc">
													<p class="mb5">{{ $option['content'] }}</p>
													<div class="img">

@if ($option['image'])

														<p><img src="{{ $option['image'] }}" alt="{{ $option['content'] }}" /></p>

@else

														<p><img src="{{ asset('assets/images/vote/none_thumb.jpg') }}"/></p>
														<!--# } #-->

@if ($isVote && !$value['isvoted'])
<!--# } #-->
														<!--# 
															$disabled = ($isVote && !$value['isvoted']) ? '' : 'disabled';
														#-->
														<label for="toupiao_{{ $option['option_id'] }}"></label>
														<input name="optionid[]" id="toupiao_{{ $option['option_id'] }}" type="{{ @$value['ismultiple'] ? 'checkbox' : 'radio' }}" value="{{ $option['option_id'] }}"{{ $disabled }} />
													</div>

@if ($isView)

													<dl class="J_dn cc" style="display:none;">
														<dt>
															<p class="mb5"><span class="progress"><span class="term_{{ $j }}"style="width:{$optionPercent}%;"></span></span></p>
														</dt>
														<dd class="num">{{ $option['voted_num'] }}票 ({$optionPercent}%)</dd>
													</dl>
													<!--# } #-->
												</div>
											</li>
			

@else

											<li class="{{ $hiddenInfo }}">
												<label class="options cp" for="toupiao_{{ $option['option_id'] }}">
														<dl class="cc">
															<dt>
																<p>

@if ($isVote && !$value['isvoted'])
<!--# } #-->
														<!--# 
															$disabled = ($isVote && !$value['isvoted']) ? '' : 'disabled';
														#-->
																<input name="optionid[]" type="{{ @$value['ismultiple'] ? 'checkbox' : 'radio' }}" id="toupiao_{{ $option['option_id'] }}" value="{{ $option['option_id'] }}"{{ $disabled }} /> {{ $option['content'] }}
																</p>


@if ($isView)

																<span class="progress J_dn" style="display:none;">
																	<span class="term_{{ $j }}" style="width:{$optionPercent}%;"></span>
																</span>

																<dd class="num J_dn" style="display:none;">
																	<p>&nbsp;</p>{{ $option['voted_num'] }}票 ({$optionPercent}%)
																</dd>
<!--# } #-->
															</dt>
														</dl>
													</label>
												</li>
<!--# } #-->
<!--# $i++;$j++;} #-->   
											</ul>    
										 </div>


										<div class="ft J_vote_options" style="display:none;">
<!--# $submitDisabled = ($isVote && !$value['isvoted']) ? '' : 'disabled'; #-->
											<button class="btn btn_submit btn_big J_vote_list_sub {{ $submitDisabled }}" data-tid="{{ $value['poll_id'] }}" type="submit"{{ $submitDisabled }}>投票</button>


@if ($isVote)



@if($value['isvoted'])

											<span class="mr10">你已投过票</span>

@else

			

@if ($value['option_limit'] > 1)

											<span class="mr10">最多可选 {$value['option_limit']} 项</span>
			<!--# } #-->


@if ($value['isafter_view'])

											<span class="mr10">投票后才能查看结果</span>
			<!--#  } #-->

	<!--#  } #-->
<!--#  } #-->


@if ($value['expired_time'] && ($value['expired_time'] < App\Core\Tool::getTime()))

<span class="mr10">本次投票已经结束</span>
<!--# } #-->



@if (!$isAllowForumGroup)
$forumTip = (!$value['allow_visit'] && $value['allow_read']) || (!$value['allow_visit'] && !$value['allow_read']) ? true : false;
$threadTip = (!$value['allow_read'] && $value['allow_visit']) ? true : false;
#-->

@if ($threadTip)

	<span class="mr10">你没有该帖子访问权限</span>
	<!--# } #-->
	

@if ($forumTip)

	<span class="mr10">你没有该版块访问权限</span>
	<!--# } #-->
<!--# } #-->

											<a href="#" class="J_vote_up" data-role="show" data-tid="{{ $value['poll_id'] }}">收起&nbsp;&uarr;</a>
										</div>

										<div class="ft J_vote_more">
											<a href="#" class="J_vote_down" data-role="show" data-tid="{{ $value['poll_id'] }}">更多选项&nbsp;&darr;</a>
										</div>
										</div>
								</form>
							</dd>
							<dd class="num">
								<div><span>投票人数</span><em>{{ $value['voter_num'] }}</em></div>
							</dd>
						</dl>
