<?php
$forumdb = app('forum.srv.PwForumService')->getCommonForumList();
if ($pwforum instanceof PwForumBo) {
    $__currentCateId = $pwforum->getCateId();
    $__currentFid = $pwforum->fid;
    !isset($forumdb[0][$__currentCateId]) && $__currentCateId = key($forumdb[0]);
} else {
    $__currentCateId = key($forumdb[0]);
    $__currentFid = 0;
}
?>
<div class="box_wrap" aria-label="版块列表" role="tablist">
    <h2 class="box_title J_sidebar_box_toggle">版块列表</h2>
    <div class="forum_menu">
        @foreach ($forumdb[0] as $k => $cate)
            @if ($forumdb[$cate['fid']])
                <dl class="{{ App\Core\Tool::isCurrent($k == $__currentCateId) }}">
                    <dt class="J_sidebar_forum_toggle"><a
                                href="{{ url('bbs/cate/run?fid=' . $cate['fid']) }}">{{ @strip_tags($cate['name']) }}</a>
                    </dt>
                    <dd role="tabpanel">
                        @foreach ($forumdb[$cate['fid']] as $forums)
                            <p><a class="{{ App\Core\Tool::isCurrent($forums['fid'] == $__currentFid) }}"
                                  href="{{ url('bbs/thread/run?fid=' . $forums['fid']) }}">{{ @strip_tags($forums['name']) }}</a>
                            </p>
                        @endforeach
                    </dd>
                </dl>
            @endif
        @endforeach
    </div>
</div>