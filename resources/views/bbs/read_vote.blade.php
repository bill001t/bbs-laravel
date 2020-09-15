{{--<hook-action name="displayVoteHtml" args='vote'>--}}
<style>
    /*
    ===================
    投票
    ===================
    */
    .read_vote_list {
        color: #666;
        width: 540px;
    }

    .read_vote_list .hd {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .read_vote_list .hd span {
        padding: 0 15px;
        color: #cccccc;
        font-family: Simsun;
        font-weight: 100;
    }

    .read_vote_list ul {
        border-top: 1px dotted #d3d3d3;
    }

    .read_vote_list li {
        padding: 3px 0;
        border-bottom: 1px dotted #d3d3d3;
    }

    .read_vote_list .options {
        display: block;
        padding: 5px;
    }

    .read_vote_list .options:hover,
    .read_vote_list li.current .options {
        background-color: #f2f6eb;
    }

    .read_vote_list .ft {
        padding: 10px 0;
    }

    .read_vote_list .btn {
        margin-right: 20px;
        font-weight: 700;
    }

    .read_vote_list dl {
        padding: 2px 0;
    }

    .read_vote_list dt {
        float: left;
        width: 300px;
        margin-right: 10px;
    }

    .read_vote_list .num {
        float: left;
        width: 120px;
        font-family: Simsun;
    }

    .read_vote_list .moreuser {
        float: left;
        width: 80px;
    }

    /*
    ===================
    投票进度条
    ===================
    */
    .read_vote_list .progress,
    .read_vote_list .progress span {
        background-image: url({{ asset('assets/images/vote/progress_bg.png') }});
        background-repeat: repeat-x;
    }

    .read_vote_list .progress {
        width: 100%;
        background-color: #efefef;
        height: 15px;
        display: inline-block;
        vertical-align: middle;
        position: relative;
        border: 1px solid #fff;
    }

    .read_vote_list .progress span {
        background-color: #a5ce45;
        display: inline-block;
        height: 15px;
        position: absolute;
        margin-top: 0;
        left: 0;
        font: 0/0 Arial;
    }

    .read_vote_list .progress span.term_1 {
        background-position: 0 -15px;
    }

    .read_vote_list .progress span.term_2 {
        background-position: 0 -30px;
    }

    .read_vote_list .progress span.term_3 {
        background-position: 0 -45px;
    }

    .read_vote_list .progress span.term_4 {
        background-position: 0 -60px;
    }

    /*
    ===================
    查看投票会员列表
    ===================
    */
    .pop_vote_member {
        width: 320px;
    }

    .pop_vote_member ul {
        height: 80px;
        overflow-y: auto;
    }

    .pop_vote_member li {
        float: left;
        width: 90px;
        line-height: 25px;
        height: 25px;
        overflow: hidden;
    }

    .pop_vote_member li a {
        color: #333;
    }

    /*
    ===================
    图片投票
    ===================
    */
    .read_vote_list .li_img dl {
        overflow: hidden;
        _float: left;
    }

    .read_vote_list .li_img dt {
        width: 380px;
    }

    .read_vote_list .li_img .moreuser {
        float: right;
    }

    .read_vote_list .li_img .progress {
        vertical-align: top;
    }

    .read_vote_list .img {
        margin-top: 2px;
        float: left;
        width: 120px;
        height: 120px;
        background: #fff;
        padding: 4px;
        border: 1px solid #ccc;
        text-align: center;
        margin-right: 10px;
        line-height: 120px;
        position: relative;
    }

    .read_vote_list .img p {
        background: #fff;
        height: 120px;
    }

    .read_vote_list .img label {
        position: absolute;
        left: 4px;
        top: 4px;
        width: 120px;
        height: 120px;
        cursor: pointer;
        background: #000;
        filter: alpha(opacity=0);
        -moz-opacity: 0;
        opacity: 0;
    }

    .read_vote_list .img img {
        vertical-align: middle;
        line-height: 120px;
        text-align: center;
    }

    .read_vote_list .img input {
        position: absolute;
        right: 0px;
        bottom: 3px;
    }
</style>

<form method="post" id="J_read_vote_form" action="{{ url('vote/vote/run') }}">
    <input type="hidden" name="typeid" value="{{ $vote->info['tid'] }}"/>
    <input type="hidden" name="apptype" value="0"/>
    <!-- 投票开始 -->
    <div class="read_vote_list">
        <div class="hd">
            @if ($vote->info['poll']['expired_time'] && ($vote->info['poll']['expired_time'] < App\Core\Tool::getTime()))
                本次投票已经结束
            @else
                <?php $pollExpiredTime = $vote->info['poll']['expired_time'] ? App\Core\Tool::time2str($vote->info['poll']['expired_time'], 'Y-m-d H:i') : '无期限'; ?>
                投票截止时间：{$pollExpiredTime}
            @endif

            <?php
            $pollRegtimeLimit = $vote->info['poll']['regtime_limit'] ? App\Core\Tool::time2str($vote->info['poll']['regtime_limit'], 'Y-m-d') : '';
            ?>

            @if ($pollRegtimeLimit)
                <span>|</span>注册于{$pollRegtimeLimit}前会员可参与投票
            @endif
        </div>
        <div class="ct">
            <?php
            $pollVoteTimes = ($vote->info['poll']['option_limit']) ? $vote->info['poll']['option_limit'] : 1;
            $pollInputType = $pollVoteTimes > 1 ? 'checkbox' : 'radio';
            $i = 1;
            ?>
            <ul class="J_vote_item" data-max="{{ $pollVoteTimes }}">
                <?php
                foreach ((array)$vote->info['option'] as $key => $option) {
                $i = ($i % 5 == 0) ? 1 : $i;
                ?>

                @if ($vote->info['poll']['isinclude_img'])
                    <li class="li_img">
                        <div class="options cc">
                            <p class="mb5">{{ $option['content'] }}</p>
                            <div class="img">
                                <?php
                                $option['image'] = $option['image'] ? App\Core\Tool::getPath($option['image'], 1) : '';
                                ?>
                                @if($option['image'])
                                    <p><img src="{{ $option['image'] }}" alt=""/></p>
                                @else
                                    <p><img src="{{ asset('assets/images/vote/none_thumb.jpg') }}"/></p>
                                @endif
                                <label for="toupiao_{{ $option['option_id'] }}"></label>
                                @if($vote->isAllowVote && !$vote->isVoted)
                                    <input name="optionid[]" id="toupiao_{{ $option['option_id'] }}"
                                           type="{{ $pollInputType }}" value="{{ $option['option_id'] }}"/>
                                @endif
                            </div>
                            <dl class="cc">
                                @if($vote->isAllowView)
                                    <?php
                                    $optionPercent = $vote->info['poll']['votedtotal'] ? round((100 * $option['voted_num'] / $vote->info['poll']['votedtotal']), 2) : 0
                                    ?>
                                    <dt>
                                    <p class="mb5"><span class="progress"><span class="term_{{ $i }}"
                                                                                style="width:{{ $optionPercent }}%;"></span></span>
                                    </p>
                                    </dt>
                                    <dd class="num">{{ $option['voted_num'] }}票 ({$optionPercent}%)</dd>
                                @endif
                                @if($vote->isViewVoter && $option['voted_num'])
                                    <dd class="moreuser"><a class="J_vote_list_show" data-key="{{ $key }}"
                                                            href="{{ url('vote/index/member?pollid=' . $vote->info['poll_id'] . '&optionid=' . $option['option_id']) }}">查看参与人员</a>
                                    </dd>
                                @endif
                            </dl>
                        </div>
                    </li>
                @else
                    <li>
                        <label class="options cp" for="toupiao_{{ $option['option_id'] }}">
                            <dl class="cc">
                                <dt>
                                <p>
                                    @if($vote->isAllowVote && !$vote->isVoted)
                                        <input name="optionid[]" type="{{ $pollInputType }}"
                                               id="toupiao_{{ $option['option_id'] }}"
                                               value="{{ $option['option_id'] }}"/>
                                    @endif
                                    <span>{{ $option['content'] }}</span>
                                </p>
                                @if($vote->isAllowView)
                                    <?php
                                    $optionPercent = $vote->info['poll']['votedtotal'] ? round((100 * $option['voted_num'] / $vote->info['poll']['votedtotal']), 2) : 0
                                    ?>
                                    <span class="progress"><span class="term_{{ $i }}"
                                                                 style="width:{{ $optionPercent }}%;"></span></span>
                                    @endif
                                    </dt>
                                    @if($vote->isAllowView)
                                        <dd class="num"><p>&nbsp;</p>{{ $option['voted_num'] }}票 ({$optionPercent}%)
                                        </dd>
                                    @endif
                                    @if($vote->isViewVoter && $option['voted_num'])
                                        <dd class="moreuser"><p>&nbsp;</p><a class="J_vote_list_show"
                                                                             data-key="{{ $key }}"
                                                                             href="{{ url('vote/index/member?pollid=' . $vote->info['poll_id'] . '&optionid=' . $option['option_id']) }}">查看参与人员</a>
                                        </dd>
                                    @endif
                            </dl>
                        </label>
                    </li>
                @endif
                <?php
                $i++;
                }
                ?>
            </ul>
        </div>

        <div class="ft">
            <?php
            $submitDisabled = ($vote->isAllowVote && !$vote->isVoted) ? '' : 'disabled';
            ?>
            <button class="btn btn_submit btn_big {{ $submitDisabled }}" type="submit"{{ $submitDisabled }}>投票
            </button>
            @if($vote->isAllowVote)
                @if($vote->isVoted)
                    <span class="mr10">你已投过票</span>
                @else
                    @if($pollVoteTimes > 1)
                        <span class="mr10">最多可选 {$pollVoteTimes} 项</span>
                    @endif
                    @if($vote->info['poll']['isafter_view'])
                        <span class="mr10">投票后才能查看结果</span>
                    @endif
                @endif
            @endif
        </div>
    </div>
    <!-- 投票结束 -->
</form>

{{--</hook-action>--}}
{{--<hook-action name="displayVoteHtmlAfterContent">--}}
        <!--<div style="color:red;border:1px solid #f00;padding:5px">是我啦，是我啦</div>-->
{{--
</hook-action>--}}
