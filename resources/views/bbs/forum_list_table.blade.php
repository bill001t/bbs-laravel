{{--<hook-action name="more_across" args="cateForum,forumList">--}}
<div class="ct">
    <table width="100%" summary="横排版块排序">
        <?php
        $i = 0;
        foreach ($forumList as $_id => $_item) {
        $i++;
        $_class = $_item['icon'] ? '' : ($_item['todayposts'] > 0 ? 'new' : 'old');
        ?>
        @if ($i == 1)
            <tr>
                @endif
                <th class="{{ $_class }}">
                    @if($_item['icon'])
                        <a href="{{ url('bbs/thread/run?fid=' . $_id) }}" target="_blank"><img alt="forumlogo"
                                                                                               src="{{ App\Core\Tool::getPath($_item['icon']) }}"
                                                                                               class="fl mr10"></a>
                    @endif
                    <h3 class="fname"><a
                                href="{{ url('bbs/thread/run?fid=' . $_id) }}">{!! $_item['name'] !!}</a>
                        @if ($_item['todayposts'])
                            <span class="org fn">(今日{$_item['todayposts']})</span>
                        @endif
                    </h3>
                    主题：{$_item['threads']}，帖子：<?php echo $_item['threads'] + $_item['posts'];?>
                    <br/>
                    @if ($_item['lastpost_time'])
                        最后回复：
                        <a href="{{ url('bbs/read/run?tid=' . $_item['lastpost_tid'] . '&fid=' . $_id . '&page=e') }}#a"
                           rel="nofollow"><span
                                    class="time">{{ App\Core\Tool::time2str($_item['lastpost_time'], 'auto') }}</span></a>
                    @endif
                </th>
                @if ($i % $cateForum['across'] == 0)
                    $i = 0; #-->
            </tr>
            @endif
            <?php } ?>
            @if ($i > 0)
            </tr>
        @endif
    </table>
</div>
{{--</hook-action>--}}

{{--<hook-action name="one_across" args="cateForum,forumList">--}}
<div class="ct">
    <table width="100%" summary="竖排版块排序">
        <col/>
        <col width="100"/>
        <col width="250"/>
        <?php
        foreach ($forumList as $_id => $_item){
        $_class = $_item['icon'] ? '' : ($_item['todayposts'] > 0 ? 'new' : 'old');
        ?>
        <tr>
            <th class="{{ $_class }}">
                @if($_item['icon'])
                    <a href="{{ url('bbs/thread/run?fid=' . $_id) }}" target="_blank"><img
                                src="{{ App\Core\Tool::getPath($_item['icon']) }}" class="fl mr10" alt="forumlogo"/></a>
                @endif
                <h3 class="fname"><a
                            href="{{ url('bbs/thread/run?fid=' . $_id) }}">{!! $_item['name'] !!}</a>
                    @if ($_item['todayposts'])
                        <span class="org fn">(今日{$_item['todayposts']})</span>
                    @endif
                </h3>
                <p class="descrip">{!! $_item['descrip'] !!}</p>
                @if ($_item['manager'])
                    <p class="descrip">版主：
                        @foreach ($_item['manager'] as $name)
                            <a class="J_user_card_show" data-username="{{ $name }}"
                               href="{{ url('space/index/run?username=' . $name) }}">{{ $name }} </a>
                        @endforeach
                    </p>
                @endif
            </th>
            <td><em class="org">{{ $_item['threads'] }}</em>&nbsp;/&nbsp;
                <?php echo $_item['threads'] + $_item['posts'];?></td>
            <td class="last">
                @if($_item['lastpost_time'])
                    <a href="{{ url('bbs/read/run?tid=' . $_item['lastpost_tid'] . '&fid=' . $_id) }}"
                       class="s4">{{ $_item['lastpost_info'] }}</a><br/>最后回复：<a
                            href="{{ url('space/index/run?username=' . $_item['lastpost_username']) }}"
                            class="last_name J_user_card_show"
                            data-username="{{ $_item['lastpost_username'] }}">{{ $_item['lastpost_username'] }}</a>
                    <a href="{{ url('bbs/read/run?tid=' . $_item['lastpost_tid'] . '&fid=' . $_id . '&page=e') }}#a"
                       aria-label="最后回复时间" title="跳转到最后一个楼层" class="last_name"
                       rel="nofollow">{{ App\Core\Tool::time2str($_item['lastpost_time'], 'auto') }}</a>
                @endif
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
{{--</hook-action> --}}
