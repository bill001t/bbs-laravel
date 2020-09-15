<?php

return [
    
    'precache' => [
        'default/index/run' => [['hot_tags', [0, 10]], /*'medal_auto', 'medal_all'*/],
        'bbs/index/run' => [['hot_tags', [0, 10]], /*'medal_auto', 'medal_all'*/],
        'bbs/forum/run' => [['hot_tags', [0, 10]]],
        'bbs/cate/run' => [['hot_tags', [0, 10]]],
        'bbs/thread/run' => [['hot_tags', [0, 10]],/* 'medal_auto', 'medal_all'*/],
        'bbs/read/run' => ['level', 'group_right'/*, 'medal_all'*/],
    ],

    'prehook' => [
        'ALL' => ['s_head', 's_header_nav', 's_footer'],
        'LOGIN' => ['s_header_info_1', 's_header_info_2', 's_header_my'],
        'UNLOGIN' => ['s_header_info_3'],

        'default/index/run' => ['c_index_run', 'm_PwThreadList'],
        'bbs/index/run' => ['c_index_run', 'm_PwThreadList'],
        'bbs/cate/run' => ['c_cate_run', 'm_PwThreadList'],
        'bbs/thread/run' => ['c_thread_run', 'm_PwThreadList', 's_PwThreadType'],
        'bbs/read/run' => ['c_read_run', 'm_PwThreadDisplay', 's_PwThreadType', 's_PwUbbCode_convert', 's_PwThreadsHitsDao_add'],
        'bbs/post/doadd' => ['c_post_doadd', 'm_PwTopicPost', 's_PwThreadsDao_add', 's_PwThreadsIndexDao_add', 's_PwThreadsCateIndexDao_add', 's_PwThreadsContentDao_add', 's_PwForumStatisticsDao_update', 's_PwForumStatisticsDao_batchUpdate', 's_PwTagRecordDao_add', 's_PwTagRelationDao_add', 's_PwTagDao_update', 's_PwTagDao_add', 's_PwThreadsContentDao_update', 's_PwFreshDao_add', 's_PwUserDataDao_update', 's_PwUser_update', 's_PwAttachDao_update', 's_PwThreadAttachDao_update', 's_PwCreditOperationConfig'],
        'bbs/post/doreply' => ['c_post_doreply', 'm_PwReplyPost', 's_PwPostsDao_add', 's_PwForumStatisticsDao_update', 's_PwForumStatisticsDao_batchUpdate', 's_PwThreadsDao_update', 's_PwThreadsIndexDao_update', 's_PwThreadsCateIndexDao_update', 's_PwThreadsDigestIndexDao_update', 's_PwUserDataDao_update', 's_PwUser_update', 's_PwCreditOperationConfig'],
        'u/login/dorun' => ['c_login_dorun', 's_PwUserDataDao_update', 's_PwUser_update', 'm_PwLoginService'],
        'u/login/welcome' => ['s_PwUserDataDao_update', 's_PwUser_update', 'm_PwLoginService', 's_PwCronDao_update'],
        'u/register/dorun' => ['c_register', 'm_PwRegisterService'],
    ],

    'cacheKeys' => [
        'config' => ['config', [], 0, [App\Services\cache\bm\PwCacheUpdateService::class, 'getConfigCacheValue']],
        'level' => ['level', [], 0, [App\Services\usergroup\bm\PwUserGroupsService::class, 'getLevelCacheValue']],
        'group' => ['group_%s', ['gid'], 0, [App\Services\usergroup\bm\PwUserGroupsService::class, 'getGroupCacheValueByGid']],
        'group_right' => ['group_right', [], 0, [App\Services\usergroup\bm\PwUserGroupsService::class, 'getGroupRightCacheValue']],
        'hot_tags' => ['hot_tags_%s_%s', ['cateid', 'num'], 3600, [App\Services\tag\bm\PwTagService::class, 'getHotTagsNoCache']],
        'medal_all' => ['medal_all', [], 0, [App\Services\medal\bm\PwMedalService::class, 'getMedalAllCacheValue']],
        'medal_auto' => ['medal_auto', [], 0, [App\Services\medal\bm\PwMedalService::class, 'getMedalAutoCacheValue']],
        'all_emotions' => ['all_emotions', [], 0, [App\Services\emotion\bm\PwEmotionService::class, 'getAllEmotionNoCache']],
        'word' => ['word', [], 0, [App\Services\word\bm\PwWordFilter::class, 'fetchAllWordNoCache']],
        'word_replace' => ['word_replace', [], 0, [App\Services\word\bm\PwWordFilter::class, 'getReplaceWordNoCache']],
        'advertisement' => ['advertisement', [], 0, [App\Services\advertisement\bm\PwAdService::class, 'getInstalledPosition']],
    ],
];