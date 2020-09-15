<?php
if (!empty($pid) && Core::getLoginUser()->getPermission('tag_allow_add')){
$hotTags = app('tag.srv.PwTagService')->getHotTags(0, 20);
$hotTags = array_slice($hotTags, 0, 10);
?>
<dl class="current post_tags_add">
    <dt class="J_sidebar_forum_toggle">添加话题</dt>
    <dd>
        <div class="user_select_input cc J_user_tag_wrap">
            <ul class="fl J_user_tag_ul">

                <?php
                if ($action == 'modify'){
                $modifyTags = app('tag.srv.PwTagService')->getTagByType('threads', $tid);
                ?>

                @foreach ($modifyTags as $tag)

                    <li><a href="javascript:;" rel="tag">
                            <span class="J_tag_name">{{ $tag['tag_name'] }}</span>
                            <del class="J_user_tag_del" title="{{ $tag['tag_name'] }}">×</del>
                            <input type="hidden" name="tagnames[]" value="{{ $tag['tag_name'] }}">
                        </a>
                    </li>
                @endforeach
                <?php } ?>
            </ul>
            <input aria-label="给输出的内容添加话题" class="J_user_tag_input" type="text" data-name="tagnames[]"
                   value="{{ $tags->info['tags'] }}"/>
        </div>
        <div class="gray mb10">（话题之间空格隔开，限5个）</div>
        <div class="post_tags_hot">
            <em>热门话题</em>
            <ul class="cc" id="J_hot_tag">

                <?php
                foreach ($hotTags as $v){
                $v['tag_name'] = App\Core\Tool::substrs($v['tag_name'], 10);
                ?>
                <li title="{{ $v['tag_name'] }}"><a href="javascript:;" rel="tag"
                                                    role="button"><span>{{ $v['tag_name'] }}</span></a></li>
                <?php } ?>
            </ul>
        </div>
    </dd>
</dl>
<?php }?>