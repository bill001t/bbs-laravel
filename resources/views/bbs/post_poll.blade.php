{{--<hook-action name="displayPostPollHtml" args='vote'>--}}
{{ $disabled = $vote->info['poll']['voter_num'] > 0 ? 'disabled' : ''; }}
<input type="hidden" name="poll_id" value="{{ $vote->info['poll_id'] }}">
<div class="cc post_type">
    <div class="post_type_ct">
        <div class="post_vote_item">
            <input type="hidden" id="J_vote_max_size" value="{{ $vote->post_max_size }}">
            <input type="hidden" id="J_max_file_uploads" value="{{ $vote->max_file_uploads }}">
            <h4>投票选项：</h4>
            <ul id="J_post_vote_list">

                @if($vote->action == 'add')
                    <li data-id="0">
                        <input name="option[]" type="text" class="input length_5 fl"/>
                        <div class="icon_image_up fl">
                            <a href="javascript:;" tabindex="-1">上传图片</a>
                            <input class="J_vote_img" data-preview="#J_preview0" name="optionpic_0" type="file"
                                   title="可插入图片" tabindex="-1">
                        </div>
                        <div class="c"></div>
                        <!--图片选择-->
                        <div class="vote_preview" style="display:none;">
                            <a href="" class="icon_del J_vicon_loc_del">删除</a>
                            <img id="J_preview0" class="J_vote_preview_img" style="display:none;" width="120"
                                 height="120"/>
                        </div>
                        <!--图片选择ie系列-->
                        <!--结束-->
                    </li>
                    <li data-id="1">
                        <input name="option[]" type="text" class="input length_5 fl"/>
                        <div class="icon_image_up fl">
                            <a href="javascript:;" tabindex="-1">上传图片</a>
                            <input class="J_vote_img" data-preview="#J_preview1" name="optionpic_1" type="file"
                                   title="可插入图片" tabindex="-1">
                        </div>
                        <!--图片选择-->
                        <div class="c"></div>
                        <div class="vote_preview" style="display:none;">
                            <a href="" class="icon_del J_vicon_loc_del">删除</a>
                            <img id="J_preview1" class="J_vote_preview_img" style="display:none;" width="120"
                                 height="120"/>
                        </div>
                        <!--结束-->
                    </li>

                @elseif($vote->action == 'modify')
                    @foreach((array)$vote->info['option'] as $key=>$value)
                        <li data-id="{{ $key }}">
                            <input name="option[{{ $value['option_id'] }}]" type="text" class="input length_5 fl"
                                   value="{{ $value['content'] }}"{{ $disabled }}/>
                            <div class="icon_image_up fl"><a href="javascript:;" tabindex="-1">上传图片</a>
                                <input class="J_vote_img" data-preview="#J_preview{{ $value['option_id'] }}"
                                       name="optionpic_{{ $value['option_id'] }}"
                                       type="{{ @$disabled ? 'input' : 'file' }}"
                                       title="{{ @$disabled ? '' : '可插入图片' }}" tabindex="-1">
                            </div>
                            @if (!$disabled)
                                <a class="icon_del J_post_vote_del" data-saved="true"
                                   href="{{ url('vote/index/deloption?pollid=' . $vote->info['poll_id'] . '&optionid=' . $value['option_id']) }}">删除</a>
                            @endif
                            <div class="c"></div>
                            {{ $value['image'] = $value['image'] ? App\Core\Tool::getPath($value['image'], 1) : '' }}
                            <div class="vote_preview" style="
                            @if (!$value['image'])
                                    display:none;
                            @endif
                                    ">
                                <a href="{{ url('vote/index/deloptionimg?pollid=' . $vote->info['poll_id'] . '&optionid=' . $value['option_id']) }}"
                                   class="icon_del J_vicon_saved_del">删除</a>
                                <img id="J_preview{{ $value['option_id'] }}"
                                     class="vote_preview_img J_vote_preview_img" src="{{ $value['image'] }}"
                                     width="120" height="120"/>
                            </div>
                        </li>
                    @endforeach
                @endif

                @if (!$disabled)
                    <li><a href="#" id="J_post_vote_add" data-role="{{ $vote->action }}">+增加选项</a></li>
                @endif
            </ul>
        </div>
    </div>
    <div class="post_type_sd">
        <h4 class="mb5 b">&nbsp;</h4>
        <ul class="post_type_operate">
            <li>
                <em>是否多选：</em>
                <label class="mr10"><input class="J_post_vote_radio" name="poll[ismultiple]" value="0"
                                           {{ $disabled }} type="radio" {{ App\Core\Tool::ifcheck($vote->info['poll']['ismultiple'] == 0) }}/>单选</label>
                <label class="mr10"><input class="J_post_vote_radio" data-type="multiple" name="poll[ismultiple]"
                                           value="1"
                                           {{ $disabled }} type="radio" {{ App\Core\Tool::ifcheck($vote->info['poll']['ismultiple'] == 1) }}/>多选</label>
                {{ $display = $vote->info['poll']['ismultiple'] ? '' : 'display:none;' }}
                <span style="{{ $display }}" id="J_post_vote_mcount"><input name="poll[optionlimit]" type="text"
                                                                            class="input mr5" size="4"
                                                                            value="{{ $vote->info['poll']['option_limit'] }}"{{ $disabled }}/>项</span>
            </li>
            <li><em>有效天数：</em><input name="poll[expiredday]" type="text" class="input mr5" size="4"
                                     value="{{ $vote->info['poll']['expiredday'] }}"{{ $disabled }}/>天
            </li>

            {{ $regtime_limit = $vote->info['poll']['regtime_limit'] ? App\Core\Tool::time2str($vote->info['poll']['regtime_limit'], 'Y-m-d') : '' }}
            <li><em>注册时间：</em><input name="poll[regtimelimit]" type="text" class="input mr5 J_date" size="10"
                                     value="{{ $regtime_limit }}" autocomplete="off"{{ $disabled }}/>前注册方可投票
            </li>

            {{ $checked = ($vote->info['poll']['isafter_view']) ? 'checked' : ''; }}
            <li><input id="isviewresult" name="poll[isviewresult]" type="checkbox" class="checkbox"
                       {{ $checked }} value="1"{{ $disabled }} /><label for="isviewresult">投票后才可见结果</label></li>
        </ul>
    </div>
</div>
{{--
</hook-action>--}}
