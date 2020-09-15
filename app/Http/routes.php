<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    /*    dd(DB::table('_user as u')
        ->leftJoin('_user_info as i', 'u.uid', '=', 'i.uid')
        ->where('i.gender', '1')
            ->where('uid', 1)
        ->get());*/
//    $response = new \Illuminate\Http\Response('');
//    $response->withCookie(cookie()->forever('aaaa', 'value'));
//    $value  =  'something from somewhere' ;
//dd(\Illuminate\Support\Facades\Request::cookie('laravel_session'));
//    setcookie ( "TestCookie" ,  $value );

//    $response->withCookie(cookie('c', 'value', '10'));
//    return $response;

//    return view('welcome');
//    dd(cookie('a', 'aaaa', '100'));
//    dd(app(\App\Core\WindidBo::class)->config->reg->toArray());

//    dd(app(\App\Core\WindidBo::class)->config->C('reg'));
});


Route::group(['prefix' => 'announce', 'namespace' => 'announce/admin', 'as' => 'announce.'], function () {
    Route::get('add', ['as' => 'add', 'uses' => 'AnnounceController@addAction']);
    Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'AnnounceController@doAddAction']);
    Route::get('doBatchDelete', ['as' => 'doBatchDelete', 'uses' => 'AnnounceController@doBatchDeleteAction']);
    Route::get('doDelete', ['as' => 'doDelete', 'uses' => 'AnnounceController@doDeleteAction']);
    Route::get('doRun', ['as' => 'doRun', 'uses' => 'AnnounceController@doRunAction']);
    Route::get('doUpdate', ['as' => 'doUpdate', 'uses' => 'AnnounceController@doUpdateAction']);
    Route::get('run', ['as' => 'run', 'uses' => 'AnnounceController@run']);
    Route::get('update', ['as' => 'update', 'uses' => 'AnnounceController@updateAction']);
});

Route::group(['prefix' => 'backup', 'namespace' => 'backup/admin', 'as' => 'backup.'], function () {
    Route::get('batchdelete', ['as' => 'batchdelete', 'uses' => 'BackupController@batchdeleteAction']);
    Route::get('before', ['as' => 'before', 'uses' => 'BackupController@beforeAction']);
    Route::get('doback', ['as' => 'doback', 'uses' => 'BackupController@dobackAction']);
    Route::get('import', ['as' => 'import', 'uses' => 'BackupController@importAction']);
    Route::get('optimize', ['as' => 'optimize', 'uses' => 'BackupController@optimizeAction']);
    Route::get('repair', ['as' => 'repair', 'uses' => 'BackupController@repairAction']);
    Route::get('restore', ['as' => 'restore', 'uses' => 'BackupController@restoreAction']);
    Route::get('run', ['as' => 'run', 'uses' => 'BackupController@run']);
    Route::get('subcat', ['as' => 'subcat', 'uses' => 'BackupController@subcatAction']);
});

Route::group(['prefix' => 'bbs', 'namespace' => 'bbs', 'as' => 'bbs.'], function () {

    Route::group(['prefix' => 'cate', 'namespace' => 'Controller', 'as' => 'cate.'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'CateController@run']);
        Route::post('topictypes', ['as' => 'topictypes', 'uses' => 'CateController@topictypesAction']);
    });

    Route::group(['prefix' => 'forumlist', 'namespace' => 'Controller', 'as' => 'forumlist.'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'ForumListController@run']);
    });

    Route::group(['prefix' => 'forum', 'namespace' => 'Controller', 'as' => 'forum.'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'ForumController@run']);
        Route::post('list', ['as' => 'list', 'uses' => 'ForumController@listAction']);
        Route::get('my', ['as' => 'my', 'uses' => 'ForumController@myAction']);

        Route::post('join', ['as' => 'join', 'uses' => 'ForumController@joinAction']);
        Route::post('quit', ['as' => 'quit', 'uses' => 'ForumController@quitAction']);
        Route::get('password', ['as' => 'password', 'uses' => 'ForumController@passwordAction']);
        Route::post('verify', ['as' => 'verify', 'uses' => 'ForumController@verifyAction']);

        Route::get('topictype', ['as' => 'topictype', 'uses' => 'ForumController@topictypeAction']);
    });

    Route::group(['prefix' => 'thread', 'namespace' => 'Controller', 'as' => 'thread.'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'ThreadController@run']);
    });

    Route::group(['prefix' => 'post', 'as' => 'post.', 'namespace' => 'Controller', 'as' => 'thread.'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'PostController@run']);
        Route::post('doadd', ['as' => 'doAdd', 'uses' => 'PostController@doaddAction']);
        Route::get('domodify', ['as' => 'domodify', 'uses' => 'PostController@domodifyAction']);
        Route::get('doreply', ['as' => 'doreply', 'uses' => 'PostController@doreplyAction']);
        Route::get('fastreply', ['as' => 'fastreply', 'uses' => 'PostController@fastreplyAction']);
        Route::get('modify', ['as' => 'modify', 'uses' => 'PostController@modifyAction']);
        Route::get('reply', ['as' => 'reply', 'uses' => 'PostController@replyAction']);
        Route::get('replylist', ['as' => 'replylist', 'uses' => 'PostController@replylistAction']);

    });

    Route::group(['prefix' => 'Read', 'namespace' => 'Controller'], function () {
        Route::get('jump', ['as' => 'jump', 'uses' => 'ReadController@jumpAction']);
        Route::get('log', ['as' => 'log', 'uses' => 'ReadController@logAction']);
        Route::get('next', ['as' => 'next', 'uses' => 'ReadController@nextAction']);
        Route::get('pre', ['as' => 'pre', 'uses' => 'ReadController@preAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'ReadController@run']);
    });

    Route::group(['prefix' => 'Alipay', 'namespace' => 'Controller'], function () {
        Route::get('/', ['as' => 'index', 'uses' => 'IndexController@run']);
    });

    Route::group(['prefix' => 'admin', 'namespace' => 'admin'], function () {
        Route::group(['prefix' => 'Article', 'namespace' => 'Article'], function () {
            Route::get('before', ['as' => 'before', 'uses' => 'ArticleController@beforeAction']);
            Route::get('deletereply', ['as' => 'deletereply', 'uses' => 'ArticleController@deletereplyAction']);
            Route::get('deletethread', ['as' => 'deletethread', 'uses' => 'ArticleController@deletethreadAction']);
            Route::get('remove', ['as' => 'remove', 'uses' => 'ArticleController@removeAction']);
            Route::get('reply', ['as' => 'reply', 'uses' => 'ArticleController@replyAction']);
            Route::get('replyadvanced', ['as' => 'replyadvanced', 'uses' => 'ArticleController@replyadvancedAction']);
            Route::get('replylist', ['as' => 'replylist', 'uses' => 'ArticleController@replylistAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'ArticleController@run']);
            Route::get('searchreply', ['as' => 'searchreply', 'uses' => 'ArticleController@searchreplyAction']);
            Route::get('searchthread', ['as' => 'searchthread', 'uses' => 'ArticleController@searchthreadAction']);
            Route::get('threadadvanced', ['as' => 'threadadvanced', 'uses' => 'ArticleController@threadadvancedAction']);
        });
        Route::group(['prefix' => 'Cache', 'namespace' => 'Cache'], function () {
            Route::get('buildCss', ['as' => 'buildCss', 'uses' => 'CacheController@buildCssAction']);
            Route::get('doCss', ['as' => 'doCss', 'uses' => 'CacheController@doCssAction']);
            Route::get('doforum', ['as' => 'doforum', 'uses' => 'CacheController@doforumAction']);
            Route::get('doHook', ['as' => 'doHook', 'uses' => 'CacheController@doHookAction']);
            Route::get('doRun', ['as' => 'doRun', 'uses' => 'CacheController@dorunAction']);
            Route::get('doTpl', ['as' => 'doTpl', 'uses' => 'CacheController@doTplAction']);
        });
        Route::group(['prefix' => 'Configbbs', 'namespace' => 'Configbbs'], function () {
            Route::get('doRun', ['as' => 'doRun', 'uses' => 'ConfigbbsController@dorunAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'ConfigbbsController@run']);
        });
        Route::group(['prefix' => 'Contentcheck', 'namespace' => 'Contentcheck'], function () {
            Route::get('doDeletePost', ['as' => 'doDeletePost', 'uses' => 'ContentcheckController@doDeletePostAction']);
            Route::get('doDeleteThread', ['as' => 'doDeleteThread', 'uses' => 'ContentcheckController@doDeleteThreadAction']);
            Route::get('doPassPost', ['as' => 'doPassPost', 'uses' => 'ContentcheckController@doPassPostAction']);
            Route::get('doPassThread', ['as' => 'doPassThread', 'uses' => 'ContentcheckController@doPassThreadAction']);
            Route::get('reply', ['as' => 'reply', 'uses' => 'ContentcheckController@replyAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'ContentcheckController@run']);
        });
        Route::group(['prefix' => 'Recycle', 'namespace' => 'Recycle'], function () {
            Route::get('before', ['as' => 'before', 'uses' => 'RecycleController@beforeAction']);
            Route::get('doDeleteReply', ['as' => 'doDeleteReply', 'uses' => 'RecycleController@doDeleteReplyAction']);
            Route::get('doDeleteTopic', ['as' => 'doDeleteTopic', 'uses' => 'RecycleController@doDeleteTopicAction']);
            Route::get('doRevertReply', ['as' => 'doRevertReply', 'uses' => 'RecycleController@doRevertReplyAction']);
            Route::get('doRevertTopic', ['as' => 'doRevertTopic', 'uses' => 'RecycleController@doRevertTopicAction']);
            Route::get('reply', ['as' => 'reply', 'uses' => 'RecycleController@replyAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'RecycleController@run']);
        });
        Route::group(['prefix' => 'Setbbs', 'namespace' => 'Setbbs'], function () {
            Route::get('doread', ['as' => 'doread', 'uses' => 'SetbbsController@doreadAction']);
            Route::get('doRun', ['as' => 'doRun', 'uses' => 'SetbbsController@dorunAction']);
            Route::get('dothread', ['as' => 'dothread', 'uses' => 'SetbbsController@dothreadAction']);
            Route::get('read', ['as' => 'read', 'uses' => 'SetbbsController@readAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'SetbbsController@run']);
            Route::get('thread', ['as' => 'thread', 'uses' => 'SetbbsController@threadAction']);
        });
        Route::group(['prefix' => 'Setforum', 'namespace' => 'Setforum'], function () {
            Route::get('deleteforum', ['as' => 'deleteforum', 'uses' => 'SetforumController@deleteforumAction']);
            Route::get('deleteicon', ['as' => 'deleteicon', 'uses' => 'SetforumController@deleteiconAction']);
            Route::get('deletelogo', ['as' => 'deletelogo', 'uses' => 'SetforumController@deletelogoAction']);
            Route::get('deletetopictype', ['as' => 'deletetopictype', 'uses' => 'SetforumController@deletetopictypeAction']);
            Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'SetforumController@doeditAction']);
            Route::get('doRun', ['as' => 'doRun', 'uses' => 'SetforumController@dorunAction']);
            Route::get('dounite', ['as' => 'dounite', 'uses' => 'SetforumController@douniteAction']);
            Route::get('edit', ['as' => 'edit', 'uses' => 'SetforumController@editAction']);
            Route::get('editname', ['as' => 'editname', 'uses' => 'SetforumController@editnameAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'SetforumController@run']);
            Route::get('searchforum', ['as' => 'searchforum', 'uses' => 'SetforumController@searchforumAction']);
            Route::get('unite', ['as' => 'unite', 'uses' => 'SetforumController@uniteAction']);
        });


    });
    Route::group(['prefix' => 'Alipay', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'AlipayController@beforeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'AlipayController@run']);
    });
    Route::group(['prefix' => 'Attach', 'namespace' => 'Controller'], function () {
        Route::get('delete', ['as' => 'delete', 'uses' => 'AttachController@deleteAction']);
        Route::get('download', ['as' => 'download', 'uses' => 'AttachController@downloadAction']);
        Route::get('record', ['as' => 'record', 'uses' => 'AttachController@recordAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'AttachController@run']);
    });
    Route::group(['prefix' => 'Buythread', 'namespace' => 'Controller'], function () {
        Route::get('buy', ['as' => 'buy', 'uses' => 'BuythreadController@buyAction']);
        Route::get('record', ['as' => 'record', 'uses' => 'BuythreadController@recordAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'BuythreadController@run']);
    });

    Route::group(['prefix' => 'Draft', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'DraftController@beforeAction']);
        Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'DraftController@doAddAction']);
        Route::get('doDelete', ['as' => 'doDelete', 'uses' => 'DraftController@doDeleteAction']);
        Route::get('myDrafts', ['as' => 'myDrafts', 'uses' => 'DraftController@myDraftsAction']);
    });
    Route::group(['prefix' => 'filter', 'namespace' => 'Controller'], function () {
        Route::group(['prefix' => 'PwGlobalFilter', 'namespace' => 'PwGlobalFilter'], function () {
            Route::get('postHandle', ['as' => 'postHandle', 'uses' => 'PwGlobalFilter@postHandle']);
            Route::get('preHandle', ['as' => 'preHandle', 'uses' => 'PwGlobalFilter@preHandle']);
        });
    });


    Route::group(['prefix' => 'Manage', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'ManageController@addAction']);
        Route::get('addReceiver', ['as' => 'addReceiver', 'uses' => 'ManageController@addReceiverAction']);
        Route::get('area', ['as' => 'area', 'uses' => 'ManageController@areaAction']);
        Route::get('batchdelete', ['as' => 'batchdelete', 'uses' => 'ManageController@batchdeleteAction']);
        Route::get('batchedit', ['as' => 'batchedit', 'uses' => 'ManageController@batcheditAction']);
        Route::get('bbs', ['as' => 'bbs', 'uses' => 'ManageController@bbsAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'ManageController@beforeAction']);
        Route::get('category', ['as' => 'category', 'uses' => 'ManageController@categoryAction']);
        Route::get('clear', ['as' => 'clear', 'uses' => 'ManageController@clearAction']);
        Route::get('dealCheck', ['as' => 'dealCheck', 'uses' => 'ManageController@dealCheckAction']);
        Route::get('defaultAvatar', ['as' => 'defaultAvatar', 'uses' => 'ManageController@defaultAvatarAction']);
        Route::get('del', ['as' => 'del', 'uses' => 'ManageController@delAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'ManageController@deleteAction']);
        Route::get('deleteCategory', ['as' => 'deleteCategory', 'uses' => 'ManageController@deleteCategoryAction']);
        Route::get('deletehot', ['as' => 'deletehot', 'uses' => 'ManageController@deletehotAction']);
        Route::get('deleteMessages', ['as' => 'deleteMessages', 'uses' => 'ManageController@deleteMessagesAction']);
        Route::get('deleteReceiver', ['as' => 'deleteReceiver', 'uses' => 'ManageController@deleteReceiverAction']);
        Route::get('detail', ['as' => 'detail', 'uses' => 'ManageController@detailAction']);
        Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'ManageController@doAddAction']);
        Route::get('dobatchedit', ['as' => 'dobatchedit', 'uses' => 'ManageController@dobatcheditAction']);
        Route::get('doclear', ['as' => 'doclear', 'uses' => 'ManageController@doClearAction']);
        Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'ManageController@doEditAction']);
        Route::get('doEditCategory', ['as' => 'doEditCategory', 'uses' => 'ManageController@doEditCategoryAction']);
        Route::get('doEditCredit', ['as' => 'doEditCredit', 'uses' => 'ManageController@doEditCreditAction']);
        Route::get('doeditforum', ['as' => 'doeditforum', 'uses' => 'ManageController@doeditforumAction']);
        Route::get('doEditGroup', ['as' => 'doEditGroup', 'uses' => 'ManageController@doEditGroupAction']);
        Route::get('dogroup', ['as' => 'dogroup', 'uses' => 'ManageController@dogroupAction']);
        Route::get('doimport', ['as' => 'doimport', 'uses' => 'ManageController@doimportAction']);
        Route::get('domerge', ['as' => 'domerge', 'uses' => 'ManageController@domergeAction']);
        Route::get('domove', ['as' => 'domove', 'uses' => 'ManageController@domoveAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'ManageController@doRunAction']);
        Route::get('doSend', ['as' => 'doSend', 'uses' => 'ManageController@doSendAction']);
        Route::get('edit', ['as' => 'edit', 'uses' => 'ManageController@editAction']);
        Route::get('editCategory', ['as' => 'editCategory', 'uses' => 'ManageController@editCategoryAction']);
        Route::get('editCredit', ['as' => 'editCredit', 'uses' => 'ManageController@editCreditAction']);
        Route::get('editforum', ['as' => 'editforum', 'uses' => 'ManageController@editforumAction']);
        Route::get('editGroup', ['as' => 'editGroup', 'uses' => 'ManageController@editGroupAction']);
        Route::get('export', ['as' => 'export', 'uses' => 'ManageController@exportAction']);
        Route::get('import', ['as' => 'import', 'uses' => 'ManageController@importAction']);
        Route::get('like', ['as' => 'like', 'uses' => 'ManageController@likeAction']);
        Route::get('manage', ['as' => 'manage', 'uses' => 'ManageController@manageAction']);
        Route::get('merge', ['as' => 'merge', 'uses' => 'ManageController@mergeAction']);
        Route::get('move', ['as' => 'move', 'uses' => 'ManageController@moveAction']);
        Route::get('open', ['as' => 'open', 'uses' => 'ManageController@openAction']);
        Route::get('receiverList', ['as' => 'receiverList', 'uses' => 'ManageController@receiverListAction']);
        Route::get('resolvedMethod', ['as' => 'resolvedMethod', 'uses' => 'ManageController@resolvedActionMethod']);
        Route::get('run', ['as' => 'run', 'uses' => 'ManageController@run']);
        Route::get('search', ['as' => 'search', 'uses' => 'ManageController@searchAction']);
        Route::get('send', ['as' => 'send', 'uses' => 'ManageController@sendAction']);
        Route::get('setCategory', ['as' => 'setCategory', 'uses' => 'ManageController@setCategoryAction']);
        Route::get('setconfig', ['as' => 'setconfig', 'uses' => 'ManageController@setconfigAction']);
        Route::get('sethot', ['as' => 'sethot', 'uses' => 'ManageController@sethotAction']);
        Route::get('topic', ['as' => 'topic', 'uses' => 'ManageController@topicAction']);
    });
    Route::group(['prefix' => 'Masingle', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'MasingleController@beforeAction']);
        Route::get('manage', ['as' => 'manage', 'uses' => 'MasingleController@manageAction']);
        Route::get('resolvedMethod', ['as' => 'resolvedMethod', 'uses' => 'MasingleController@resolvedActionMethod']);
    });
    Route::group(['prefix' => 'Pay99bill', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'Pay99billController@beforeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'Pay99billController@run']);
    });
    Route::group(['prefix' => 'Paypal', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'PaypalController@beforeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'PaypalController@run']);
    });


    Route::group(['prefix' => 'Remind', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'RemindController@beforeAction']);
        Route::get('friend', ['as' => 'friend', 'uses' => 'RemindController@friendAction']);
        Route::get('getfollow', ['as' => 'getfollow', 'uses' => 'RemindController@getfollowAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'RemindController@run']);
    });
    Route::group(['prefix' => 'Tenpay', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'TenpayController@beforeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'TenpayController@run']);
    });

    Route::group(['prefix' => 'Upload', 'namespace' => 'Controller'], function () {
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'UploadController@dorunAction']);
        Route::get('replace', ['as' => 'replace', 'uses' => 'UploadController@replaceAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'UploadController@run']);
    });
    Route::group(['prefix' => 'User', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'UserController@addAction']);
        Route::get('addBlack', ['as' => 'addBlack', 'uses' => 'UserController@addBlackAction']);
        Route::get('addUser', ['as' => 'addUser', 'uses' => 'UserController@addUserAction']);
        Route::get('batchdelete', ['as' => 'batchdelete', 'uses' => 'UserController@batchDeleteAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'UserController@beforeAction']);
        Route::get('checkInput', ['as' => 'checkInput', 'uses' => 'UserController@checkInputAction']);
        Route::get('checkquestion', ['as' => 'checkquestion', 'uses' => 'UserController@checkQuestionAction']);
        Route::get('clearCredit', ['as' => 'clearCredit', 'uses' => 'UserController@clearCreditAction']);
        Route::get('defaultAvatar', ['as' => 'defaultAvatar', 'uses' => 'UserController@defaultAvatarAction']);
        Route::get('delBlack', ['as' => 'delBlack', 'uses' => 'UserController@delBlackAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'UserController@deleteAction']);
        Route::get('doactive', ['as' => 'doactive', 'uses' => 'UserController@doactiveAction']);
        Route::get('docheck', ['as' => 'docheck', 'uses' => 'UserController@docheckAction']);
        Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'UserController@doEditAction']);
        Route::get('doEditCredit', ['as' => 'doEditCredit', 'uses' => 'UserController@doEditCreditAction']);
        Route::get('edit', ['as' => 'edit', 'uses' => 'UserController@editAction']);
        Route::get('editCredit', ['as' => 'editCredit', 'uses' => 'UserController@editCreditAction']);
        Route::get('editDmCredit', ['as' => 'editDmCredit', 'uses' => 'UserController@editDmCreditAction']);
        Route::get('editUser', ['as' => 'editUser', 'uses' => 'UserController@editUserAction']);
        Route::get('email', ['as' => 'email', 'uses' => 'UserController@emailAction']);
        Route::get('fecth', ['as' => 'fecth', 'uses' => 'UserController@fecthAction']);
        Route::get('fecthCredit', ['as' => 'fecthCredit', 'uses' => 'UserController@fecthCreditAction']);
        Route::get('fetchBlack', ['as' => 'fetchBlack', 'uses' => 'UserController@fetchBlackAction']);
        Route::get('get', ['as' => 'get', 'uses' => 'UserController@getAction']);
        Route::get('getBlack', ['as' => 'getBlack', 'uses' => 'UserController@getBlackAction']);
        Route::get('getCredit', ['as' => 'getCredit', 'uses' => 'UserController@getCreditAction']);
        Route::get('login', ['as' => 'login', 'uses' => 'UserController@loginAction']);
        Route::get('replaceBlack', ['as' => 'replaceBlack', 'uses' => 'UserController@replaceBlackAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'UserController@run']);
        Route::get('synLogin', ['as' => 'synLogin', 'uses' => 'UserController@synLoginAction']);
        Route::get('synLogout', ['as' => 'synLogout', 'uses' => 'UserController@synLogoutAction']);
    });
});

Route::group(['prefix' => 'config', 'namespace' => 'config', 'as' => 'config.'], function () {
    Route::group(['prefix' => 'Attachment', 'namespace' => 'Attachment'], function () {
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'AttachmentController@dorunAction']);
        Route::get('dostroage', ['as' => 'dostroage', 'uses' => 'AttachmentController@dostroageAction']);
        Route::get('dothumb', ['as' => 'dothumb', 'uses' => 'AttachmentController@dothumbAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'AttachmentController@run']);
        Route::get('storage', ['as' => 'storage', 'uses' => 'AttachmentController@storageAction']);
        Route::get('thumb', ['as' => 'thumb', 'uses' => 'AttachmentController@thumbAction']);
        Route::get('view', ['as' => 'view', 'uses' => 'AttachmentController@viewAction']);
    });
    Route::group(['prefix' => 'Config', 'namespace' => 'Config'], function () {
        Route::get('deleteConfig', ['as' => 'deleteConfig', 'uses' => 'ConfigController@deleteConfigAction']);
        Route::get('deleteConfigByName', ['as' => 'deleteConfigByName', 'uses' => 'ConfigController@deleteConfigByNameAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'ConfigController@dorunAction']);
        Route::get('dosite', ['as' => 'dosite', 'uses' => 'ConfigController@dositeAction']);
        Route::get('fetchConfig', ['as' => 'fetchConfig', 'uses' => 'ConfigController@fetchConfigAction']);
        Route::get('get', ['as' => 'get', 'uses' => 'ConfigController@getAction']);
        Route::get('getConfig', ['as' => 'getConfig', 'uses' => 'ConfigController@getConfigAction']);
        Route::get('getConfigByName', ['as' => 'getConfigByName', 'uses' => 'ConfigController@getConfigByNameAction']);
        Route::get('getValues', ['as' => 'getValues', 'uses' => 'ConfigController@getValuesAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'ConfigController@run']);
        Route::get('setconfig', ['as' => 'setconfig', 'uses' => 'ConfigController@setConfigAction']);
        Route::get('setConfigs', ['as' => 'setConfigs', 'uses' => 'ConfigController@setConfigsAction']);
        Route::get('setCredits', ['as' => 'setCredits', 'uses' => 'ConfigController@setCreditsAction']);
        Route::get('site', ['as' => 'site', 'uses' => 'ConfigController@siteAction']);
    });
    Route::group(['prefix' => 'Editor', 'namespace' => 'Editor'], function () {
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'EditorController@dorunAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'EditorController@run']);
    });
    Route::group(['prefix' => 'Email', 'namespace' => 'Email'], function () {
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'EmailController@dorunAction']);
        Route::get('doSend', ['as' => 'doSend', 'uses' => 'EmailController@dosendAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'EmailController@run']);
        Route::get('send', ['as' => 'send', 'uses' => 'EmailController@sendAction']);
    });
    Route::group(['prefix' => 'Message', 'namespace' => 'Message'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'MessageController@addAction']);
        Route::get('addBlack', ['as' => 'addBlack', 'uses' => 'MessageController@addBlackAction']);
        Route::get('after', ['as' => 'after', 'uses' => 'MessageController@afterAction']);
        Route::get('batchDeleteDialog', ['as' => 'batchDeleteDialog', 'uses' => 'MessageController@batchDeleteDialogAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'MessageController@beforeAction']);
        Route::get('checkReaded', ['as' => 'checkReaded', 'uses' => 'MessageController@checkReadedAction']);
        Route::get('countDialog', ['as' => 'countDialog', 'uses' => 'MessageController@countDialogAction']);
        Route::get('countMessage', ['as' => 'countMessage', 'uses' => 'MessageController@countMessageAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'MessageController@deleteAction']);
        Route::get('deleteByMessageIds', ['as' => 'deleteByMessageIds', 'uses' => 'MessageController@deleteByMessageIdsAction']);
        Route::get('deleteDialog', ['as' => 'deleteDialog', 'uses' => 'MessageController@deleteDialogAction']);
        Route::get('deletemessage', ['as' => 'deletemessage', 'uses' => 'MessageController@deletemessageAction']);
        Route::get('deleteUserMessages', ['as' => 'deleteUserMessages', 'uses' => 'MessageController@deleteUserMessagesAction']);
        Route::get('dialog', ['as' => 'dialog', 'uses' => 'MessageController@dialogAction']);
        Route::get('doAddDialog', ['as' => 'doAddDialog', 'uses' => 'MessageController@doAddDialogAction']);
        Route::get('doAddMessage', ['as' => 'doAddMessage', 'uses' => 'MessageController@doAddMessageAction']);
        Route::get('doset', ['as' => 'doset', 'uses' => 'MessageController@doSetAction']);
        Route::get('editNum', ['as' => 'editNum', 'uses' => 'MessageController@editNumAction']);
        Route::get('fetchDialog', ['as' => 'fetchDialog', 'uses' => 'MessageController@fetchDialogAction']);
        Route::get('follows', ['as' => 'follows', 'uses' => 'MessageController@followsAction']);
        Route::get('getDialog', ['as' => 'getDialog', 'uses' => 'MessageController@getDialogAction']);
        Route::get('getDialogByUser', ['as' => 'getDialogByUser', 'uses' => 'MessageController@getDialogByUserAction']);
        Route::get('getDialogByUsers', ['as' => 'getDialogByUsers', 'uses' => 'MessageController@getDialogByUsersAction']);
        Route::get('getDialogList', ['as' => 'getDialogList', 'uses' => 'MessageController@getDialogListAction']);
        Route::get('getMessageById', ['as' => 'getMessageById', 'uses' => 'MessageController@getMessageByIdAction']);
        Route::get('getMessageList', ['as' => 'getMessageList', 'uses' => 'MessageController@getMessageListAction']);
        Route::get('getNum', ['as' => 'getNum', 'uses' => 'MessageController@getNumAction']);
        Route::get('pop', ['as' => 'pop', 'uses' => 'MessageController@popAction']);
        Route::get('read', ['as' => 'read', 'uses' => 'MessageController@readAction']);
        Route::get('readDialog', ['as' => 'readDialog', 'uses' => 'MessageController@readDialogAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'MessageController@run']);
        Route::get('search', ['as' => 'search', 'uses' => 'MessageController@searchAction']);
        Route::get('searchMessage', ['as' => 'searchMessage', 'uses' => 'MessageController@searchMessageAction']);
        Route::get('send', ['as' => 'send', 'uses' => 'MessageController@sendAction']);
        Route::get('set', ['as' => 'set', 'uses' => 'MessageController@setAction']);
        Route::get('showVerify', ['as' => 'showVerify', 'uses' => 'MessageController@showverifyAction']);
    });
    Route::group(['prefix' => 'Mobile', 'namespace' => 'Mobile'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'MobileController@beforeAction']);
        Route::get('checkmobilecode', ['as' => 'checkmobilecode', 'uses' => 'MobileController@checkmobilecodeAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'MobileController@dorunAction']);
        Route::get('doset', ['as' => 'doset', 'uses' => 'MobileController@dosetAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'MobileController@run']);
        Route::get('set', ['as' => 'set', 'uses' => 'MobileController@setAction']);
    });
    Route::group(['prefix' => 'Notice', 'namespace' => 'Notice'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'NoticeController@beforeAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'NoticeController@deleteAction']);
        Route::get('detail', ['as' => 'detail', 'uses' => 'NoticeController@detailAction']);
        Route::get('detaillist', ['as' => 'detaillist', 'uses' => 'NoticeController@detaillistAction']);
        Route::get('ignore', ['as' => 'ignore', 'uses' => 'NoticeController@ignoreAction']);
        Route::get('minilist', ['as' => 'minilist', 'uses' => 'NoticeController@minilistAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'NoticeController@run']);
    });
    Route::group(['prefix' => 'Pay', 'namespace' => 'Pay'], function () {
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'PayController@dorunAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'PayController@run']);
    });
    Route::group(['prefix' => 'Punch', 'namespace' => 'Punch'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'PunchController@beforeAction']);
        Route::get('dofriend', ['as' => 'dofriend', 'uses' => 'PunchController@dofriendAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'PunchController@dorunAction']);
        Route::get('friend', ['as' => 'friend', 'uses' => 'PunchController@friendAction']);
        Route::get('getfollow', ['as' => 'getfollow', 'uses' => 'PunchController@getfollowAction']);
        Route::get('punch', ['as' => 'punch', 'uses' => 'PunchController@punchAction']);
        Route::get('punchtip', ['as' => 'punchtip', 'uses' => 'PunchController@punchtipAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'PunchController@run']);
    });
    Route::group(['prefix' => 'Regist', 'namespace' => 'Regist'], function () {
        Route::get('doguide', ['as' => 'doguide', 'uses' => 'RegistController@doguideAction']);
        Route::get('dologin', ['as' => 'dologin', 'uses' => 'RegistController@dologinAction']);
        Route::get('doregist', ['as' => 'doregist', 'uses' => 'RegistController@doregistAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'RegistController@dorunAction']);
        Route::get('guide', ['as' => 'guide', 'uses' => 'RegistController@guideAction']);
        Route::get('login', ['as' => 'login', 'uses' => 'RegistController@loginAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'RegistController@run']);
    });
    Route::group(['prefix' => 'Security', 'namespace' => 'Security'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'SecurityController@run']);
    });
    Route::group(['prefix' => 'Storage', 'namespace' => 'Storage'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'StorageController@beforeAction']);
        Route::get('doftp', ['as' => 'doftp', 'uses' => 'StorageController@doftpAction']);
        Route::get('dostroage', ['as' => 'dostroage', 'uses' => 'StorageController@dostroageAction']);
        Route::get('ftp', ['as' => 'ftp', 'uses' => 'StorageController@ftpAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'StorageController@run']);
    });
    Route::group(['prefix' => 'Watermark', 'namespace' => 'Watermark'], function () {
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'WatermarkController@dorunAction']);
        Route::get('doset', ['as' => 'doset', 'uses' => 'WatermarkController@dosetAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'WatermarkController@run']);
        Route::get('set', ['as' => 'set', 'uses' => 'WatermarkController@setAction']);
        Route::get('view', ['as' => 'view', 'uses' => 'WatermarkController@viewAction']);
    });

});

Route::group(['prefix' => 'credit', 'namespace' => 'credit', 'as' => 'credit.'], function () {
    Route::get('before', ['as' => 'before', 'uses' => 'CreditController@beforeAction']);
    Route::get('delexchange', ['as' => 'delexchange', 'uses' => 'CreditController@delexchangeAction']);
    Route::get('docredit', ['as' => 'docredit', 'uses' => 'CreditController@docreditAction']);
    Route::get('doDelete', ['as' => 'doDelete', 'uses' => 'CreditController@doDeleteAction']);
    Route::get('doDeletecredit', ['as' => 'doDeletecredit', 'uses' => 'CreditController@doDeletecreditAction']);
    Route::get('doexchange', ['as' => 'doexchange', 'uses' => 'CreditController@doexchangeAction']);
    Route::get('dorecharge', ['as' => 'dorecharge', 'uses' => 'CreditController@dorechargeAction']);
    Route::get('doSetting', ['as' => 'doSetting', 'uses' => 'CreditController@doSettingAction']);
    Route::get('dotransfer', ['as' => 'dotransfer', 'uses' => 'CreditController@dotransferAction']);
    Route::get('editStrategy', ['as' => 'editStrategy', 'uses' => 'CreditController@editStrategyAction']);
    Route::get('exchange', ['as' => 'exchange', 'uses' => 'CreditController@exchangeAction']);
    Route::get('log', ['as' => 'log', 'uses' => 'CreditController@logAction']);
    Route::get('order', ['as' => 'order', 'uses' => 'CreditController@orderAction']);
    Route::get('pay', ['as' => 'pay', 'uses' => 'CreditController@payAction']);
    Route::get('recharge', ['as' => 'recharge', 'uses' => 'CreditController@rechargeAction']);
    Route::get('run', ['as' => 'run', 'uses' => 'CreditController@run']);
    Route::get('strategy', ['as' => 'strategy', 'uses' => 'CreditController@strategyAction']);
    Route::get('transfer', ['as' => 'transfer', 'uses' => 'CreditController@transferAction']);


});

Route::group(['prefix' => 'emotion', 'namespace' => 'emotion', 'as' => 'emotion.'], function () {
    Route::get('deletecate', ['as' => 'deletecate', 'uses' => 'EmotionController@deletecateAction']);
    Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'EmotionController@doaddAction']);
    Route::get('dobatchadd', ['as' => 'dobatchadd', 'uses' => 'EmotionController@dobatchaddAction']);
    Route::get('dobatchedit', ['as' => 'dobatchedit', 'uses' => 'EmotionController@dobatcheditAction']);
    Route::get('doRun', ['as' => 'doRun', 'uses' => 'EmotionController@dorunAction']);
    Route::get('doused', ['as' => 'doused', 'uses' => 'EmotionController@dousedAction']);
    Route::get('emotion', ['as' => 'emotion', 'uses' => 'EmotionController@emotionAction']);
    Route::get('run', ['as' => 'run', 'uses' => 'EmotionController@run']);

});

Route::group(['prefix' => 'guide', 'namespace' => 'guide', 'as' => 'guide.'], function () {
    Route::group(['prefix' => 'Attention', 'namespace' => 'Controller'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'AttentionController@run']);
    });
    Route::group(['prefix' => 'Interest', 'namespace' => 'Controller'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'InterestController@run']);
    });

});

Route::group(['prefix' => 'hook', 'namespace' => 'hook', 'as' => 'hook.'], function () {
    Route::get('add', ['as' => 'add', 'uses' => 'InjectController@addAction']);
    Route::get('del', ['as' => 'del', 'uses' => 'InjectController@delAction']);
    Route::get('detail', ['as' => 'detail', 'uses' => 'InjectController@detailAction']);
    Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'InjectController@doAddAction']);
    Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'InjectController@doEditAction']);
    Route::get('edit', ['as' => 'edit', 'uses' => 'InjectController@editAction']);
    Route::get('add', ['as' => 'add', 'uses' => 'ManageController@addAction']);
    Route::get('addReceiver', ['as' => 'addReceiver', 'uses' => 'ManageController@addReceiverAction']);
    Route::get('area', ['as' => 'area', 'uses' => 'ManageController@areaAction']);
    Route::get('batchdelete', ['as' => 'batchdelete', 'uses' => 'ManageController@batchdeleteAction']);
    Route::get('batchedit', ['as' => 'batchedit', 'uses' => 'ManageController@batcheditAction']);
    Route::get('bbs', ['as' => 'bbs', 'uses' => 'ManageController@bbsAction']);
    Route::get('before', ['as' => 'before', 'uses' => 'ManageController@beforeAction']);
    Route::get('category', ['as' => 'category', 'uses' => 'ManageController@categoryAction']);
    Route::get('clear', ['as' => 'clear', 'uses' => 'ManageController@clearAction']);
    Route::get('dealCheck', ['as' => 'dealCheck', 'uses' => 'ManageController@dealCheckAction']);
    Route::get('defaultAvatar', ['as' => 'defaultAvatar', 'uses' => 'ManageController@defaultAvatarAction']);
    Route::get('del', ['as' => 'del', 'uses' => 'ManageController@delAction']);
    Route::get('delete', ['as' => 'delete', 'uses' => 'ManageController@deleteAction']);
    Route::get('deleteCategory', ['as' => 'deleteCategory', 'uses' => 'ManageController@deleteCategoryAction']);
    Route::get('deletehot', ['as' => 'deletehot', 'uses' => 'ManageController@deletehotAction']);
    Route::get('deleteMessages', ['as' => 'deleteMessages', 'uses' => 'ManageController@deleteMessagesAction']);
    Route::get('deleteReceiver', ['as' => 'deleteReceiver', 'uses' => 'ManageController@deleteReceiverAction']);
    Route::get('detail', ['as' => 'detail', 'uses' => 'ManageController@detailAction']);
    Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'ManageController@doAddAction']);
    Route::get('dobatchedit', ['as' => 'dobatchedit', 'uses' => 'ManageController@dobatcheditAction']);
    Route::get('doclear', ['as' => 'doclear', 'uses' => 'ManageController@doClearAction']);
    Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'ManageController@doEditAction']);
    Route::get('doEditCategory', ['as' => 'doEditCategory', 'uses' => 'ManageController@doEditCategoryAction']);
    Route::get('doEditCredit', ['as' => 'doEditCredit', 'uses' => 'ManageController@doEditCreditAction']);
    Route::get('doeditforum', ['as' => 'doeditforum', 'uses' => 'ManageController@doeditforumAction']);
    Route::get('doEditGroup', ['as' => 'doEditGroup', 'uses' => 'ManageController@doEditGroupAction']);
    Route::get('dogroup', ['as' => 'dogroup', 'uses' => 'ManageController@dogroupAction']);
    Route::get('doimport', ['as' => 'doimport', 'uses' => 'ManageController@doimportAction']);
    Route::get('domerge', ['as' => 'domerge', 'uses' => 'ManageController@domergeAction']);
    Route::get('domove', ['as' => 'domove', 'uses' => 'ManageController@domoveAction']);
    Route::get('doRun', ['as' => 'doRun', 'uses' => 'ManageController@doRunAction']);
    Route::get('doSend', ['as' => 'doSend', 'uses' => 'ManageController@doSendAction']);
    Route::get('edit', ['as' => 'edit', 'uses' => 'ManageController@editAction']);
    Route::get('editCategory', ['as' => 'editCategory', 'uses' => 'ManageController@editCategoryAction']);
    Route::get('editCredit', ['as' => 'editCredit', 'uses' => 'ManageController@editCreditAction']);
    Route::get('editforum', ['as' => 'editforum', 'uses' => 'ManageController@editforumAction']);
    Route::get('editGroup', ['as' => 'editGroup', 'uses' => 'ManageController@editGroupAction']);
    Route::get('export', ['as' => 'export', 'uses' => 'ManageController@exportAction']);
    Route::get('import', ['as' => 'import', 'uses' => 'ManageController@importAction']);
    Route::get('like', ['as' => 'like', 'uses' => 'ManageController@likeAction']);
    Route::get('manage', ['as' => 'manage', 'uses' => 'ManageController@manageAction']);
    Route::get('merge', ['as' => 'merge', 'uses' => 'ManageController@mergeAction']);
    Route::get('move', ['as' => 'move', 'uses' => 'ManageController@moveAction']);
    Route::get('open', ['as' => 'open', 'uses' => 'ManageController@openAction']);
    Route::get('receiverList', ['as' => 'receiverList', 'uses' => 'ManageController@receiverListAction']);
    Route::get('resolvedMethod', ['as' => 'resolvedMethod', 'uses' => 'ManageController@resolvedActionMethod']);
    Route::get('run', ['as' => 'run', 'uses' => 'ManageController@run']);
    Route::get('search', ['as' => 'search', 'uses' => 'ManageController@searchAction']);
    Route::get('send', ['as' => 'send', 'uses' => 'ManageController@sendAction']);
    Route::get('setCategory', ['as' => 'setCategory', 'uses' => 'ManageController@setCategoryAction']);
    Route::get('setconfig', ['as' => 'setconfig', 'uses' => 'ManageController@setconfigAction']);
    Route::get('sethot', ['as' => 'sethot', 'uses' => 'ManageController@sethotAction']);
    Route::get('topic', ['as' => 'topic', 'uses' => 'ManageController@topicAction']);


});

/*Route::group(['prefix' => 'like', 'namespace' => 'like', 'as' => 'like.'], function () {
    Route::group(['prefix' => 'Like', 'namespace' => 'Controller'], function () {
        Route::get('data', ['as' => 'data', 'uses' => 'LikeController@dataAction']);
        Route::get('getLast', ['as' => 'getLast', 'uses' => 'LikeController@getLastAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'LikeController@run']);
    });
    Route::group(['prefix' => 'Mylike', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'MylikeController@beforeAction']);
        Route::get('data', ['as' => 'data', 'uses' => 'MylikeController@dataAction']);
        Route::get('doAddLogTag', ['as' => 'doAddLogTag', 'uses' => 'MylikeController@doAddLogTagAction']);
        Route::get('doAddTag', ['as' => 'doAddTag', 'uses' => 'MylikeController@doAddTagAction']);
        Route::get('doDelLike', ['as' => 'doDelLike', 'uses' => 'MylikeController@doDelLikeAction']);
        Route::get('doDelTag', ['as' => 'doDelTag', 'uses' => 'MylikeController@doDelTagAction']);
        Route::get('doEditTag', ['as' => 'doEditTag', 'uses' => 'MylikeController@doEditTagAction']);
        Route::get('doLike', ['as' => 'doLike', 'uses' => 'MylikeController@doLikeAction']);
        Route::get('doLogTag', ['as' => 'doLogTag', 'uses' => 'MylikeController@doLogTagAction']);
        Route::get('getTagList', ['as' => 'getTagList', 'uses' => 'MylikeController@getTagListAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'MylikeController@run']);
        Route::get('ta', ['as' => 'ta', 'uses' => 'MylikeController@taAction']);
    });
    Route::group(['prefix' => 'Source', 'namespace' => 'Controller'], function () {
        Route::get('addlike', ['as' => 'addlike', 'uses' => 'SourceController@addlikeAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'SourceController@beforeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'SourceController@run']);
    });


});*/

Route::group(['prefix' => 'link', 'namespace' => 'link', 'as' => 'link.'], function () {
    Route::get('add', ['as' => 'add', 'uses' => 'LinkController@addAction']);
    Route::get('addtype', ['as' => 'addtype', 'uses' => 'LinkController@addTypeAction']);
    Route::get('check', ['as' => 'check', 'uses' => 'LinkController@checkAction']);
    Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'LinkController@doaddAction']);
    Route::get('doAddType', ['as' => 'doAddType', 'uses' => 'LinkController@doAddTypeAction']);
    Route::get('docheck', ['as' => 'docheck', 'uses' => 'LinkController@doCheckAction']);
    Route::get('doDelete', ['as' => 'doDelete', 'uses' => 'LinkController@doDeleteAction']);
    Route::get('doDeleteType', ['as' => 'doDeleteType', 'uses' => 'LinkController@doDeleteTypeAction']);
    Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'LinkController@doeditAction']);
    Route::get('doRun', ['as' => 'doRun', 'uses' => 'LinkController@dorunAction']);
    Route::get('dotypes', ['as' => 'dotypes', 'uses' => 'LinkController@dotypesAction']);
    Route::get('edit', ['as' => 'edit', 'uses' => 'LinkController@editAction']);
    Route::get('run', ['as' => 'run', 'uses' => 'LinkController@run']);
    Route::get('types', ['as' => 'types', 'uses' => 'LinkController@typesAction']);


});

Route::group(['prefix' => 'log', 'namespace' => 'log', 'as' => 'log.'], function () {
    Route::group(['prefix' => 'Adminlog', 'namespace' => 'Adminlog'], function () {
        Route::get('clear', ['as' => 'clear', 'uses' => 'AdminlogController@clearAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'AdminlogController@run']);
    });
    Route::group(['prefix' => 'Loginlog', 'namespace' => 'Loginlog'], function () {
        Route::get('clear', ['as' => 'clear', 'uses' => 'LoginlogController@clearAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'LoginlogController@run']);
    });

});

Route::group(['prefix' => 'manage', 'namespace' => 'manage', 'as' => 'manage.'], function () {
    Route::group(['prefix' => 'BaseManage', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'BaseManageController@beforeAction']);
    });
    Route::group(['prefix' => 'Content', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'ContentController@beforeAction']);
        Route::get('doDeletePost', ['as' => 'doDeletePost', 'uses' => 'ContentController@doDeletePostAction']);
        Route::get('doDeleteThread', ['as' => 'doDeleteThread', 'uses' => 'ContentController@doDeleteThreadAction']);
        Route::get('doPassPost', ['as' => 'doPassPost', 'uses' => 'ContentController@doPassPostAction']);
        Route::get('doPassThread', ['as' => 'doPassThread', 'uses' => 'ContentController@doPassThreadAction']);
        Route::get('reply', ['as' => 'reply', 'uses' => 'ContentController@replyAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'ContentController@run']);
    });
    Route::group(['prefix' => 'ManageLog', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'ManageLogController@beforeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'ManageLogController@run']);
    });
    Route::group(['prefix' => 'Recycle', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'RecycleController@beforeAction']);
        Route::get('doDeleteReply', ['as' => 'doDeleteReply', 'uses' => 'RecycleController@doDeleteReplyAction']);
        Route::get('doDeleteTopic', ['as' => 'doDeleteTopic', 'uses' => 'RecycleController@doDeleteTopicAction']);
        Route::get('doRevertReply', ['as' => 'doRevertReply', 'uses' => 'RecycleController@doRevertReplyAction']);
        Route::get('doRevertTopic', ['as' => 'doRevertTopic', 'uses' => 'RecycleController@doRevertTopicAction']);
        Route::get('reply', ['as' => 'reply', 'uses' => 'RecycleController@replyAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'RecycleController@run']);
    });
    Route::group(['prefix' => 'Report', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'ReportController@beforeAction']);
        Route::get('dealCheck', ['as' => 'dealCheck', 'uses' => 'ReportController@dealCheckAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'ReportController@deleteAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'ReportController@run']);
    });


});

Route::group(['prefix' => 'message', 'namespace' => 'message', 'as' => 'message.'], function () {
    Route::get('before', ['as' => 'before', 'uses' => 'NoticeController@beforeAction']);
    Route::get('delete', ['as' => 'delete', 'uses' => 'NoticeController@deleteAction']);
    Route::get('detail', ['as' => 'detail', 'uses' => 'NoticeController@detailAction']);
    Route::get('detaillist', ['as' => 'detaillist', 'uses' => 'NoticeController@detaillistAction']);
    Route::get('ignore', ['as' => 'ignore', 'uses' => 'NoticeController@ignoreAction']);
    Route::get('minilist', ['as' => 'minilist', 'uses' => 'NoticeController@minilistAction']);
    Route::get('run', ['as' => 'run', 'uses' => 'NoticeController@run']);


});

Route::group(['prefix' => 'misc', 'namespace' => 'misc', 'as' => 'misc.'], function () {
    Route::get('area', ['as' => 'area', 'uses' => 'WebDataController@areaAction']);
    Route::get('school', ['as' => 'school', 'uses' => 'WebDataController@schoolAction']);


});

/*Route::group(['prefix' => 'my', 'namespace' => 'my', 'as' => 'my.'], function () {
    Route::group(['prefix' => 'Article', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'ArticleController@beforeAction']);
        Route::get('deletereply', ['as' => 'deletereply', 'uses' => 'ArticleController@deletereplyAction']);
        Route::get('deletethread', ['as' => 'deletethread', 'uses' => 'ArticleController@deletethreadAction']);
        Route::get('remove', ['as' => 'remove', 'uses' => 'ArticleController@removeAction']);
        Route::get('reply', ['as' => 'reply', 'uses' => 'ArticleController@replyAction']);
        Route::get('replyadvanced', ['as' => 'replyadvanced', 'uses' => 'ArticleController@replyadvancedAction']);
        Route::get('replylist', ['as' => 'replylist', 'uses' => 'ArticleController@replylistAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'ArticleController@run']);
        Route::get('searchreply', ['as' => 'searchreply', 'uses' => 'ArticleController@searchreplyAction']);
        Route::get('searchthread', ['as' => 'searchthread', 'uses' => 'ArticleController@searchthreadAction']);
        Route::get('threadadvanced', ['as' => 'threadadvanced', 'uses' => 'ArticleController@threadadvancedAction']);
    });
    Route::group(['prefix' => 'Fans', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'FansController@beforeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'FansController@run']);
    });
    Route::group(['prefix' => 'Follow', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'FollowController@addAction']);
        Route::get('addtype', ['as' => 'addtype', 'uses' => 'FollowController@addtypeAction']);
        Route::get('batchadd', ['as' => 'batchadd', 'uses' => 'FollowController@batchaddAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'FollowController@beforeAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'FollowController@deleteAction']);
        Route::get('deleteType', ['as' => 'deleteType', 'uses' => 'FollowController@deleteTypeAction']);
        Route::get('editType', ['as' => 'editType', 'uses' => 'FollowController@editTypeAction']);
        Route::get('recommendfriend', ['as' => 'recommendfriend', 'uses' => 'FollowController@recommendfriendAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'FollowController@run']);
        Route::get('samefriend', ['as' => 'samefriend', 'uses' => 'FollowController@samefriendAction']);
        Route::get('savetype', ['as' => 'savetype', 'uses' => 'FollowController@savetypeAction']);
    });
    Route::group(['prefix' => 'Fresh', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'FreshController@beforeAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'FreshController@deleteAction']);
        Route::get('doreply', ['as' => 'doreply', 'uses' => 'FreshController@doreplyAction']);
        Route::get('post', ['as' => 'post', 'uses' => 'FreshController@postAction']);
        Route::get('read', ['as' => 'read', 'uses' => 'FreshController@readAction']);
        Route::get('reply', ['as' => 'reply', 'uses' => 'FreshController@replyAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'FreshController@run']);
    });
    Route::group(['prefix' => 'Friend', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'FriendController@beforeAction']);
        Route::get('friend', ['as' => 'friend', 'uses' => 'FriendController@friendAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'FriendController@run']);
        Route::get('search', ['as' => 'search', 'uses' => 'FriendController@searchAction']);
    });
    Route::group(['prefix' => 'Invite', 'namespace' => 'Controller'], function () {
        Route::get('allowBuy', ['as' => 'allowBuy', 'uses' => 'InviteController@allowBuyAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'InviteController@beforeAction']);
        Route::get('buy', ['as' => 'buy', 'uses' => 'InviteController@buyAction']);
        Route::get('inviteFriend', ['as' => 'inviteFriend', 'uses' => 'InviteController@inviteFriendAction']);
        Route::get('online', ['as' => 'online', 'uses' => 'InviteController@onlineAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'InviteController@run']);
        Route::get('statistics', ['as' => 'statistics', 'uses' => 'InviteController@statisticsAction']);
    });
    Route::group(['prefix' => 'Visitor', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'VisitorController@beforeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'VisitorController@run']);
        Route::get('tovisit', ['as' => 'tovisit', 'uses' => 'VisitorController@tovisitAction']);
    });


});*/

Route::group(['prefix' => 'nav', 'namespace' => 'nav', 'as' => 'nav.'], function () {
    Route::get('del', ['as' => 'del', 'uses' => 'NavController@delAction']);
    Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'NavController@doeditAction']);
    Route::get('doRun', ['as' => 'doRun', 'uses' => 'NavController@dorunAction']);
    Route::get('edit', ['as' => 'edit', 'uses' => 'NavController@editAction']);
    Route::get('run', ['as' => 'run', 'uses' => 'NavController@run']);


});

Route::group(['prefix' => 'profile', 'namespace' => 'profile', 'as' => 'profile.'], function () {
    Route::group(['prefix' => 'Avatar', 'namespace' => 'Controller'], function () {
        Route::get('default', ['as' => 'default', 'uses' => 'AvatarController@defaultAction']);
        Route::get('doavatar', ['as' => 'doavatar', 'uses' => 'AvatarController@doavatarAction']);
        Route::get('get', ['as' => 'get', 'uses' => 'AvatarController@getAction']);
        Route::get('getAvatarUrl', ['as' => 'getAvatarUrl', 'uses' => 'AvatarController@getAvatarUrlAction']);
        Route::get('getFlash', ['as' => 'getFlash', 'uses' => 'AvatarController@getFlashAction']);
        Route::get('getStorages', ['as' => 'getStorages', 'uses' => 'AvatarController@getStoragesAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'AvatarController@run']);
        Route::get('setStorages', ['as' => 'setStorages', 'uses' => 'AvatarController@setStoragesAction']);
    });
    Route::group(['prefix' => 'BaseProfile', 'namespace' => 'Controller'], function () {
        Route::get('after', ['as' => 'after', 'uses' => 'BaseProfileController@afterAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'BaseProfileController@beforeAction']);
    });
    Route::group(['prefix' => 'Credit', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'CreditController@beforeAction']);
        Route::get('delexchange', ['as' => 'delexchange', 'uses' => 'CreditController@delexchangeAction']);
        Route::get('docredit', ['as' => 'docredit', 'uses' => 'CreditController@docreditAction']);
        Route::get('doDelete', ['as' => 'doDelete', 'uses' => 'CreditController@doDeleteAction']);
        Route::get('doDeletecredit', ['as' => 'doDeletecredit', 'uses' => 'CreditController@doDeletecreditAction']);
        Route::get('doexchange', ['as' => 'doexchange', 'uses' => 'CreditController@doexchangeAction']);
        Route::get('dorecharge', ['as' => 'dorecharge', 'uses' => 'CreditController@dorechargeAction']);
        Route::get('doSetting', ['as' => 'doSetting', 'uses' => 'CreditController@doSettingAction']);
        Route::get('dotransfer', ['as' => 'dotransfer', 'uses' => 'CreditController@dotransferAction']);
        Route::get('editStrategy', ['as' => 'editStrategy', 'uses' => 'CreditController@editStrategyAction']);
        Route::get('exchange', ['as' => 'exchange', 'uses' => 'CreditController@exchangeAction']);
        Route::get('log', ['as' => 'log', 'uses' => 'CreditController@logAction']);
        Route::get('order', ['as' => 'order', 'uses' => 'CreditController@orderAction']);
        Route::get('pay', ['as' => 'pay', 'uses' => 'CreditController@payAction']);
        Route::get('recharge', ['as' => 'recharge', 'uses' => 'CreditController@rechargeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'CreditController@run']);
        Route::get('strategy', ['as' => 'strategy', 'uses' => 'CreditController@strategyAction']);
        Route::get('transfer', ['as' => 'transfer', 'uses' => 'CreditController@transferAction']);
    });
    Route::group(['prefix' => 'Education', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'EducationController@addAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'EducationController@deleteAction']);
        Route::get('edit', ['as' => 'edit', 'uses' => 'EducationController@editAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'EducationController@run']);
    });
    Route::group(['prefix' => 'Extends', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'ExtendsController@beforeAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'ExtendsController@dorunAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'ExtendsController@run']);
    });
    Route::group(['prefix' => 'Password', 'namespace' => 'Controller'], function () {
        Route::get('checkOldPwd', ['as' => 'checkOldPwd', 'uses' => 'PasswordController@checkOldPwdAction']);
        Route::get('checkpwd', ['as' => 'checkpwd', 'uses' => 'PasswordController@checkpwdAction']);
        Route::get('checkpwdStrong', ['as' => 'checkpwdStrong', 'uses' => 'PasswordController@checkpwdStrongAction']);
        Route::get('dosetQ', ['as' => 'dosetQ', 'uses' => 'PasswordController@dosetQAction']);
        Route::get('edit', ['as' => 'edit', 'uses' => 'PasswordController@editAction']);
        Route::get('question', ['as' => 'question', 'uses' => 'PasswordController@questionAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'PasswordController@run']);
    });
    Route::group(['prefix' => 'Right', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'RightController@beforeAction']);
        Route::get('doset', ['as' => 'doset', 'uses' => 'RightController@dosetAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'RightController@run']);
    });
    Route::group(['prefix' => 'Secret', 'namespace' => 'Controller'], function () {
        Route::get('black', ['as' => 'black', 'uses' => 'SecretController@blackAction']);
        Route::get('doblack', ['as' => 'doblack', 'uses' => 'SecretController@doblackAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'SecretController@dorunAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'SecretController@run']);
    });
    Route::group(['prefix' => 'Tag', 'namespace' => 'Controller'], function () {
        Route::get('cancleHot', ['as' => 'cancleHot', 'uses' => 'TagController@cancleHotAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'TagController@deleteAction']);
        Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'TagController@doAddAction']);
        Route::get('doAddByid', ['as' => 'doAddByid', 'uses' => 'TagController@doAddByidAction']);
        Route::get('doDelete', ['as' => 'doDelete', 'uses' => 'TagController@doDeleteAction']);
        Route::get('hot', ['as' => 'hot', 'uses' => 'TagController@hotAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'TagController@run']);
        Route::get('sethot', ['as' => 'sethot', 'uses' => 'TagController@setHotAction']);
    });
    Route::group(['prefix' => 'Work', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'WorkController@addAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'WorkController@deleteAction']);
        Route::get('edit', ['as' => 'edit', 'uses' => 'WorkController@editAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'WorkController@run']);
    });


});

Route::group(['prefix' => 'search', 'namespace' => 'search', 'as' => 'search.'], function () {
    Route::group(['prefix' => 'S', 'namespace' => 'Controller'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'SController@run']);
    });
    Route::group(['prefix' => 'Search', 'namespace' => 'Controller'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'SearchController@run']);
    });


});

Route::group(['prefix' => 'space', 'namespace' => 'space', 'as' => 'space.'], function () {
    Route::group(['prefix' => 'Ban', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'BanController@beforeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'BanController@run']);
    });
    Route::group(['prefix' => 'Card', 'namespace' => 'Controller'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'CardController@run']);
    });
    Route::group(['prefix' => 'Follows', 'namespace' => 'Controller'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'FollowsController@run']);
    });
    Route::group(['prefix' => 'Myspace', 'namespace' => 'Controller'], function () {
        Route::get('allowdomain', ['as' => 'allowdomain', 'uses' => 'MyspaceController@allowdomainAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'MyspaceController@beforeAction']);
        Route::get('doEditBackground', ['as' => 'doEditBackground', 'uses' => 'MyspaceController@doEditBackgroundAction']);
        Route::get('doEditSpace', ['as' => 'doEditSpace', 'uses' => 'MyspaceController@doEditSpaceAction']);
        Route::get('doeditstyle', ['as' => 'doeditstyle', 'uses' => 'MyspaceController@doEditStyleAction']);
        Route::get('doreply', ['as' => 'doreply', 'uses' => 'MyspaceController@doreplyAction']);
    });
    Route::group(['prefix' => 'Profile', 'namespace' => 'Controller'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'ProfileController@run']);
    });
    Route::group(['prefix' => 'Punch', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'PunchController@beforeAction']);
        Route::get('dofriend', ['as' => 'dofriend', 'uses' => 'PunchController@dofriendAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'PunchController@dorunAction']);
        Route::get('friend', ['as' => 'friend', 'uses' => 'PunchController@friendAction']);
        Route::get('getfollow', ['as' => 'getfollow', 'uses' => 'PunchController@getfollowAction']);
        Route::get('punch', ['as' => 'punch', 'uses' => 'PunchController@punchAction']);
        Route::get('punchtip', ['as' => 'punchtip', 'uses' => 'PunchController@punchtipAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'PunchController@run']);
    });
    Route::group(['prefix' => 'SpaceBase', 'namespace' => 'Controller'], function () {
        Route::get('after', ['as' => 'after', 'uses' => 'SpaceBaseController@afterAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'SpaceBaseController@beforeAction']);
    });


});


Route::group(['prefix' => 'u', 'namespace' => 'u', 'as' => 'u.'], function () {

    Route::group(['prefix' => 'filter', 'namespace' => 'Controller'], function () {
        Route::get('postHandle', ['as' => 'postHandle', 'uses' => 'UserRegisterFilter@postHandle']);
        Route::get('preHandle', ['as' => 'preHandle', 'uses' => 'UserRegisterFilter@preHandle']);
    });

    Route::group(['prefix' => 'FindPwd', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'FindPwdController@beforeAction']);
        Route::get('bymail', ['as' => 'bymail', 'uses' => 'FindPwdController@bymailAction']);
        Route::get('bymobile', ['as' => 'bymobile', 'uses' => 'FindPwdController@bymobileAction']);
        Route::get('checkMailFormat', ['as' => 'checkMailFormat', 'uses' => 'FindPwdController@checkMailFormatAction']);
        Route::get('checkmobile', ['as' => 'checkmobile', 'uses' => 'FindPwdController@checkmobileAction']);
        Route::get('checkmobilecode', ['as' => 'checkmobilecode', 'uses' => 'FindPwdController@checkmobilecodeAction']);
        Route::get('checkPhoneFormat', ['as' => 'checkPhoneFormat', 'uses' => 'FindPwdController@checkPhoneFormatAction']);
        Route::get('checkUsername', ['as' => 'checkUsername', 'uses' => 'FindPwdController@checkUsernameAction']);
        Route::get('dobymail', ['as' => 'dobymail', 'uses' => 'FindPwdController@dobymailAction']);
        Route::get('doresetpwd', ['as' => 'doresetpwd', 'uses' => 'FindPwdController@doresetpwdAction']);
        Route::get('resetpwd', ['as' => 'resetpwd', 'uses' => 'FindPwdController@resetpwdAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'FindPwdController@run']);
        Route::get('sendmobile', ['as' => 'sendmobile', 'uses' => 'FindPwdController@sendmobileAction']);
    });

    Route::group(['prefix' => 'login', 'namespace' => 'Controller', 'as' => 'login.'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'LoginController@run']);
        Route::post('dorun', ['as' => 'doRun', 'uses' => 'LoginController@dorunAction']);
//        Route::get('dologin', ['as' => 'dologin', 'uses' => 'LoginController@dologinAction']);
        Route::get('fast', ['as' => 'fast', 'uses' => 'LoginController@fastAction']);
        Route::get('logout', ['as' => 'logout', 'uses' => 'LoginController@logoutAction']);
        Route::get('show', ['as' => 'show', 'uses' => 'LoginController@showAction']);
        Route::get('welcome', ['as' => 'welcome', 'uses' => 'LoginController@welcomeAction']);


//        Route::get('setquestion', ['as' => 'setquestion', 'uses' => 'LoginController@setquestionAction']);
//        Route::get('showquestion', ['as' => 'showquestion', 'uses' => 'LoginController@showquestionAction']);
//        Route::get('doshowquestion', ['as' => 'doshowquestion', 'uses' => 'LoginController@doshowquestionAction']);
//        Route::get('doSetting', ['as' => 'doSetting', 'uses' => 'LoginController@dosettingAction']);


        Route::post('checkname', ['as' => 'checkname', 'uses' => 'LoginController@checknameAction']);
        Route::post('checkpwd', ['as' => 'checkpwd', 'uses' => 'LoginController@checkpwdAction']);
        Route::post('checkquestion', ['as' => 'checkquestion', 'uses' => 'LoginController@checkquestionAction']);


    });

    Route::group(['prefix' => 'Mobile', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'MobileController@beforeAction']);
        Route::get('checkmobilecode', ['as' => 'checkmobilecode', 'uses' => 'MobileController@checkmobilecodeAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'MobileController@dorunAction']);
        Route::get('doset', ['as' => 'doset', 'uses' => 'MobileController@dosetAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'MobileController@run']);
        Route::get('set', ['as' => 'set', 'uses' => 'MobileController@setAction']);
    });

    Route::group(['prefix' => 'register', 'namespace' => 'Controller', 'as' => 'register.'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'RegisterController@run']);
        Route::post('dorun', ['as' => 'doRun', 'uses' => 'RegisterController@dorunAction']);
        Route::get('welcome', ['as' => 'welcome', 'uses' => 'RegisterController@welcomeAction']);
//        Route::get('guide', ['as' => 'guide', 'uses' => 'RegisterController@guideAction']);
        Route::get('close', ['as' => 'close', 'uses' => 'RegisterController@closeAction']);
//        Route::get('invite', ['as' => 'invite', 'uses' => 'RegisterController@inviteAction']);


        Route::get('sendActiveEmail', ['as' => 'sendActiveEmail', 'uses' => 'RegisterController@sendActiveEmailAction']);
        Route::get('activeEmail', ['as' => 'activeEmail', 'uses' => 'RegisterController@activeEmailAction']);
        Route::get('editemail', ['as' => 'editemail', 'uses' => 'RegisterController@editEmailAction']);
        Route::get('sendActiveEmailAgain', ['as' => 'sendActiveEmailAgain', 'uses' => 'RegisterController@sendActiveEmailAgainAction']);
//        Route::get('sendmobile', ['as' => 'sendmobile', 'uses' => 'RegisterController@sendmobileAction']);


        Route::post('checkemail', ['as' => 'checkemail', 'uses' => 'RegisterController@checkemailAction']);
        Route::post('checkInvitecode', ['as' => 'checkInvitecode', 'uses' => 'RegisterController@checkInvitecodeAction']);
        Route::post('checkmobile', ['as' => 'checkmobile', 'uses' => 'RegisterController@checkmobileAction']);
        Route::post('checkpwd', ['as' => 'checkpwd', 'uses' => 'RegisterController@checkpwdAction']);
        Route::post('checkpwdStrong', ['as' => 'checkpwdStrong', 'uses' => 'RegisterController@checkpwdStrongAction']);
        Route::post('checkusername', ['as' => 'checkUsername', 'uses' => 'RegisterController@checkusernameAction']);
    });

    /*Route::group(['prefix' => 'UError', 'namespace' => 'Controller'], function () {
        Route::get('loginError', ['as' => 'loginError', 'uses' => 'UErrorController@loginErrorAction']);
        Route::get('regError', ['as' => 'regError', 'uses' => 'UErrorController@regErrorAction']);
    });*/

    Route::group(['prefix' => 'admin', 'namespace' => 'Controller'], function () {
        Route::group(['prefix' => 'Check', 'namespace' => 'Check'], function () {
            Route::get('delete', ['as' => 'delete', 'uses' => 'CheckController@deleteAction']);
            Route::get('doactive', ['as' => 'doactive', 'uses' => 'CheckController@doactiveAction']);
            Route::get('docheck', ['as' => 'docheck', 'uses' => 'CheckController@docheckAction']);
            Route::get('email', ['as' => 'email', 'uses' => 'CheckController@emailAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'CheckController@run']);
        });
        Route::group(['prefix' => 'Forbidden', 'namespace' => 'Forbidden'], function () {
            Route::get('auto', ['as' => 'auto', 'uses' => 'ForbiddenController@autoAction']);
            Route::get('del', ['as' => 'del', 'uses' => 'ForbiddenController@delAction']);
            Route::get('doRun', ['as' => 'doRun', 'uses' => 'ForbiddenController@dorunAction']);
            Route::get('dosetauto', ['as' => 'dosetauto', 'uses' => 'ForbiddenController@dosetautoAction']);
            Route::get('list', ['as' => 'list', 'uses' => 'ForbiddenController@listAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'ForbiddenController@run']);
        });
        Route::group(['prefix' => 'Groups', 'namespace' => 'Groups'], function () {
            Route::get('delete', ['as' => 'delete', 'uses' => 'GroupsController@deleteAction']);
            Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'GroupsController@doeditAction']);
            Route::get('dosave', ['as' => 'dosave', 'uses' => 'GroupsController@dosaveAction']);
            Route::get('dosetright', ['as' => 'dosetright', 'uses' => 'GroupsController@dosetrightAction']);
            Route::get('edit', ['as' => 'edit', 'uses' => 'GroupsController@editAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'GroupsController@run']);
            Route::get('setright', ['as' => 'setright', 'uses' => 'GroupsController@setrightAction']);
        });
        Route::group(['prefix' => 'Tag', 'namespace' => 'Tag'], function () {
            Route::get('cancleHot', ['as' => 'cancleHot', 'uses' => 'TagController@cancleHotAction']);
            Route::get('delete', ['as' => 'delete', 'uses' => 'TagController@deleteAction']);
            Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'TagController@doAddAction']);
            Route::get('doAddByid', ['as' => 'doAddByid', 'uses' => 'TagController@doAddByidAction']);
            Route::get('doDelete', ['as' => 'doDelete', 'uses' => 'TagController@doDeleteAction']);
            Route::get('hot', ['as' => 'hot', 'uses' => 'TagController@hotAction']);
            Route::get('run', ['as' => 'run', 'uses' => 'TagController@run']);
            Route::get('sethot', ['as' => 'sethot', 'uses' => 'TagController@setHotAction']);
        });
    });
});

Route::group(['prefix' => 'vote', 'namespace' => 'vote', 'as' => 'vote.'], function () {
    Route::group(['prefix' => 'Hot', 'namespace' => 'Controller'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'HotController@run']);
    });
    Route::group(['prefix' => 'My', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'MyController@beforeAction']);
        Route::get('create', ['as' => 'create', 'uses' => 'MyController@createAction']);
    });
    Route::group(['prefix' => 'Ta', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'TaController@beforeAction']);
        Route::get('create', ['as' => 'create', 'uses' => 'TaController@createAction']);
    });
    Route::group(['prefix' => 'Vote', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'VoteController@beforeAction']);
        Route::get('forumlist', ['as' => 'forumlist', 'uses' => 'VoteController@forumlistAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'VoteController@run']);
    });


});

Route::group(['prefix' => 'admin', 'namespace' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['prefix' => 'Auth', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'AuthController@addAction']);
        Route::get('del', ['as' => 'del', 'uses' => 'AuthController@delAction']);
        Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'AuthController@doAddAction']);
        Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'AuthController@doEditAction']);
        Route::get('edit', ['as' => 'edit', 'uses' => 'AuthController@editAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'AuthController@run']);
    });

    Route::group(['prefix' => 'Custom', 'namespace' => 'Controller'], function () {
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'CustomController@doRunAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'CustomController@run']);
    });

    Route::group(['prefix' => 'Find', 'namespace' => 'Controller'], function () {
        Route::get('run', ['as' => 'run', 'uses' => 'FindController@run']);
    });

    Route::group(['prefix' => 'Founder', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'FounderController@addAction']);
        Route::get('del', ['as' => 'del', 'uses' => 'FounderController@delAction']);
        Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'FounderController@doAddAction']);
        Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'FounderController@doEditAction']);
        Route::get('edit', ['as' => 'edit', 'uses' => 'FounderController@editAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'FounderController@run']);
        Route::get('show', ['as' => 'show', 'uses' => 'FounderController@showAction']);
    });

    Route::group(['prefix' => 'Home', 'namespace' => 'Controller'], function () {
        Route::get('notice', ['as' => 'notice', 'uses' => 'HomeController@noticeAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'HomeController@run']);
    });

    Route::group(['prefix' => 'Index', 'namespace' => 'Controller'], function () {
        Route::get('after', ['as' => 'after', 'uses' => 'IndexController@afterAction']);
        Route::get('applicableList', ['as' => 'applicableList', 'uses' => 'IndexController@applicableListAction']);
        Route::get('applyTask', ['as' => 'applyTask', 'uses' => 'IndexController@applyTaskAction']);
        Route::get('attention', ['as' => 'attention', 'uses' => 'IndexController@attentionAction']);
        Route::get('attentionlist', ['as' => 'attentionlist', 'uses' => 'IndexController@attentionlistAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'IndexController@beforeAction']);
        Route::get('card', ['as' => 'card', 'uses' => 'IndexController@cardAction']);
        Route::get('center', ['as' => 'center', 'uses' => 'IndexController@centerAction']);
        Route::get('check', ['as' => 'check', 'uses' => 'IndexController@checkAction']);
        Route::get('completeList', ['as' => 'completeList', 'uses' => 'IndexController@completeListAction']);
        Route::get('contact', ['as' => 'contact', 'uses' => 'IndexController@contactAction']);
        Route::get('data', ['as' => 'data', 'uses' => 'IndexController@dataAction']);
        Route::get('database', ['as' => 'database', 'uses' => 'IndexController@databaseAction']);
        Route::get('deloption', ['as' => 'deloption', 'uses' => 'IndexController@deloptionAction']);
        Route::get('deloptionimg', ['as' => 'deloptionimg', 'uses' => 'IndexController@deloptionimgAction']);
        Route::get('demo', ['as' => 'demo', 'uses' => 'IndexController@demoAction']);
        Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'IndexController@doaddAction']);
        Route::get('doApply', ['as' => 'doApply', 'uses' => 'IndexController@doApplyAction']);
        Route::get('doAward', ['as' => 'doAward', 'uses' => 'IndexController@doAwardAction']);
        Route::get('docontact', ['as' => 'docontact', 'uses' => 'IndexController@docontactAction']);
        Route::get('doeditemail', ['as' => 'doeditemail', 'uses' => 'IndexController@doeditemailAction']);
        Route::get('doOrder', ['as' => 'doOrder', 'uses' => 'IndexController@doOrderAction']);
        Route::get('doReport', ['as' => 'doReport', 'uses' => 'IndexController@doReportAction']);
        Route::get('doRun', ['as' => 'doRun', 'uses' => 'IndexController@dorunAction']);
        Route::get('doshield', ['as' => 'doshield', 'uses' => 'IndexController@doshieldAction']);
        Route::get('editemail', ['as' => 'editemail', 'uses' => 'IndexController@editemailAction']);
        Route::get('editReadTag', ['as' => 'editReadTag', 'uses' => 'IndexController@editReadTagAction']);
        Route::get('finish', ['as' => 'finish', 'uses' => 'IndexController@finishAction']);
        Route::get('fresh', ['as' => 'fresh', 'uses' => 'IndexController@freshAction']);
        Route::get('get', ['as' => 'get', 'uses' => 'IndexController@getAction']);
        Route::get('getAudio', ['as' => 'getAudio', 'uses' => 'IndexController@getAudioAction']);
        Route::get('getHotTags', ['as' => 'getHotTags', 'uses' => 'IndexController@getHotTagsAction']);
        Route::get('info', ['as' => 'info', 'uses' => 'IndexController@infoAction']);
        Route::get('login', ['as' => 'login', 'uses' => 'IndexController@loginAction']);
        Route::get('logout', ['as' => 'logout', 'uses' => 'IndexController@logoutAction']);
        Route::get('member', ['as' => 'member', 'uses' => 'IndexController@memberAction']);
        Route::get('my', ['as' => 'my', 'uses' => 'IndexController@myAction']);
        Route::get('notice', ['as' => 'notice', 'uses' => 'IndexController@noticeAction']);
        Route::get('order', ['as' => 'order', 'uses' => 'IndexController@orderAction']);
        Route::get('read', ['as' => 'read', 'uses' => 'IndexController@readAction']);
        Route::get('reply', ['as' => 'reply', 'uses' => 'IndexController@replyAction']);
        Route::get('report', ['as' => 'report', 'uses' => 'IndexController@reportAction']);
        Route::get('reward', ['as' => 'reward', 'uses' => 'IndexController@rewardAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'IndexController@run']);
        Route::get('show', ['as' => 'show', 'uses' => 'IndexController@showAction']);
        Route::get('showcredit', ['as' => 'showcredit', 'uses' => 'IndexController@showcreditAction']);
        Route::get('showVerify', ['as' => 'showVerify', 'uses' => 'IndexController@showVerifyAction']);
        Route::get('table', ['as' => 'table', 'uses' => 'IndexController@tableAction']);
        Route::get('view', ['as' => 'view', 'uses' => 'IndexController@viewAction']);
    });

    Route::group(['prefix' => 'Message', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'MessageController@addAction']);
        Route::get('addBlack', ['as' => 'addBlack', 'uses' => 'MessageController@addBlackAction']);
        Route::get('after', ['as' => 'after', 'uses' => 'MessageController@afterAction']);
        Route::get('batchDeleteDialog', ['as' => 'batchDeleteDialog', 'uses' => 'MessageController@batchDeleteDialogAction']);
        Route::get('before', ['as' => 'before', 'uses' => 'MessageController@beforeAction']);
        Route::get('checkReaded', ['as' => 'checkReaded', 'uses' => 'MessageController@checkReadedAction']);
        Route::get('countDialog', ['as' => 'countDialog', 'uses' => 'MessageController@countDialogAction']);
        Route::get('countMessage', ['as' => 'countMessage', 'uses' => 'MessageController@countMessageAction']);
        Route::get('delete', ['as' => 'delete', 'uses' => 'MessageController@deleteAction']);
        Route::get('deleteByMessageIds', ['as' => 'deleteByMessageIds', 'uses' => 'MessageController@deleteByMessageIdsAction']);
        Route::get('deleteDialog', ['as' => 'deleteDialog', 'uses' => 'MessageController@deleteDialogAction']);
        Route::get('deletemessage', ['as' => 'deletemessage', 'uses' => 'MessageController@deletemessageAction']);
        Route::get('deleteUserMessages', ['as' => 'deleteUserMessages', 'uses' => 'MessageController@deleteUserMessagesAction']);
        Route::get('dialog', ['as' => 'dialog', 'uses' => 'MessageController@dialogAction']);
        Route::get('doAddDialog', ['as' => 'doAddDialog', 'uses' => 'MessageController@doAddDialogAction']);
        Route::get('doAddMessage', ['as' => 'doAddMessage', 'uses' => 'MessageController@doAddMessageAction']);
        Route::get('doset', ['as' => 'doset', 'uses' => 'MessageController@doSetAction']);
        Route::get('editNum', ['as' => 'editNum', 'uses' => 'MessageController@editNumAction']);
        Route::get('fetchDialog', ['as' => 'fetchDialog', 'uses' => 'MessageController@fetchDialogAction']);
        Route::get('follows', ['as' => 'follows', 'uses' => 'MessageController@followsAction']);
        Route::get('getDialog', ['as' => 'getDialog', 'uses' => 'MessageController@getDialogAction']);
        Route::get('getDialogByUser', ['as' => 'getDialogByUser', 'uses' => 'MessageController@getDialogByUserAction']);
        Route::get('getDialogByUsers', ['as' => 'getDialogByUsers', 'uses' => 'MessageController@getDialogByUsersAction']);
        Route::get('getDialogList', ['as' => 'getDialogList', 'uses' => 'MessageController@getDialogListAction']);
        Route::get('getMessageById', ['as' => 'getMessageById', 'uses' => 'MessageController@getMessageByIdAction']);
        Route::get('getMessageList', ['as' => 'getMessageList', 'uses' => 'MessageController@getMessageListAction']);
        Route::get('getNum', ['as' => 'getNum', 'uses' => 'MessageController@getNumAction']);
        Route::get('pop', ['as' => 'pop', 'uses' => 'MessageController@popAction']);
        Route::get('read', ['as' => 'read', 'uses' => 'MessageController@readAction']);
        Route::get('readDialog', ['as' => 'readDialog', 'uses' => 'MessageController@readDialogAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'MessageController@run']);
        Route::get('search', ['as' => 'search', 'uses' => 'MessageController@searchAction']);
        Route::get('searchMessage', ['as' => 'searchMessage', 'uses' => 'MessageController@searchMessageAction']);
        Route::get('send', ['as' => 'send', 'uses' => 'MessageController@sendAction']);
        Route::get('set', ['as' => 'set', 'uses' => 'MessageController@setAction']);
        Route::get('showVerify', ['as' => 'showVerify', 'uses' => 'MessageController@showverifyAction']);
    });

    Route::group(['prefix' => 'Role', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'RoleController@addAction']);
        Route::get('del', ['as' => 'del', 'uses' => 'RoleController@delAction']);
        Route::get('doAdd', ['as' => 'doAdd', 'uses' => 'RoleController@doAddAction']);
        Route::get('doEdit', ['as' => 'doEdit', 'uses' => 'RoleController@doEditAction']);
        Route::get('edit', ['as' => 'edit', 'uses' => 'RoleController@editAction']);
    });

    Route::group(['prefix' => 'Safe', 'namespace' => 'Controller'], function () {
        Route::get('add', ['as' => 'add', 'uses' => 'SafeController@addAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'SafeController@run']);
    });

    Route::group(['prefix' => 'Storage', 'namespace' => 'Controller'], function () {
        Route::get('before', ['as' => 'before', 'uses' => 'StorageController@beforeAction']);
        Route::get('doftp', ['as' => 'doftp', 'uses' => 'StorageController@doftpAction']);
        Route::get('dostroage', ['as' => 'dostroage', 'uses' => 'StorageController@dostroageAction']);
        Route::get('ftp', ['as' => 'ftp', 'uses' => 'StorageController@ftpAction']);
        Route::get('run', ['as' => 'run', 'uses' => 'StorageController@run']);
    });
});

