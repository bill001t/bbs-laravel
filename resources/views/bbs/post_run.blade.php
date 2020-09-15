<!doctype html>
<html>
<head>
    @include('common.head')
    <link href="{{ asset('assets/themes/site/default/css/dev/post.css') }} " rel="stylesheet"/>
</head>
<body>

<div class="wrap">
    @include('common.header')
    <div class="main_wrap">
        <div class="bread_crumb">{!! $headguide !!}</div>
        <form method="post" id="mainForm" name="FORM" action="{{ url('bbs/post/' . $do . '/?_json=1&fid=' . $fid) }}"
              enctype="multipart/form-data">
            <div class="box_wrap post_page">
                <nav>
                    <div class="content_nav">
                        <ul id="tabTypeHead">
                            @if ($action == 'reply')
                                <li class="current"><a href="{{ url('bbs/post/reply?tid=' . $tid) }}">发表回复</a></li>
                            @elseif ($action == 'modify')
                                <li class="current"><a href="{{ url('bbs/post/modify?tid=' . $tid . '&pid=' . $pid) }}">编辑帖子</a>
                                </li>
                            @else
                                @foreach ($pwforum->getThreadType(Core::getLoginUser()) as $key => $value)
                                    <li class="{{ App\Core\Tool::isCurrent($special == $key) }}"><a
                                                href="{{ url('bbs/post/run/?fid=' . $fid . ($key != 'default' ? ('&special=' . $key) : '')) }}">{{ $value[1] }}</a>
                                    </li>
                                @endforeach
                            @endif
                        </ul>
                    </div>
                </nav>

                <?php
                $draftCount = app(App\Services\draft\bs\PwDraft::class)->countByUid(Core::getLoginUser()->uid);
                ?>

                <div class="post_content">
                    <a id="J_draft_lis_btn" href="{{ url('bbs/draft/myDrafts') }}" class="my_drafts">草稿箱<span
                                class="red" style="
                        @if (!$draftCount)
                                display:none;
                        @endif
                                ">(<em
                                    id="J_draft_count">{{ $draftCount }}</em>)</span></a>
                    <div id="J_draft_list" class="fr dn">
                        <div class="pop_draft" id="J_draft_wrap">
                            <div class="pop_loading"></div>
                            <!--span class="not_content_mini">暂无草稿</span-->
                        </div>
                    </div>
                    <hgroup class="title">
                        @if (!empty($topictypes['topic_types']))
                            {{--主题分类--}}
                            <input type="hidden" name="topic_type_id" value="" id="J_topic_type_id"/>
                            <select id="J_sort_1" class="mr10" name="topictype">
                                <option value="0">请选择分类</option>
                                @foreach($topictypes['topic_types'] as $v)
                                    <option value="{{ $v['id'] }}">{{ @strip_tags($v['name']) }}</option>
                                @endforeach
                            </select>
                            <select id="J_sort_2" class="mr10" style="display:none;" name="sub_topictype">
                                <option value="0">请选择分类</option>
                            </select>
                        @endif{{--主题分类结束--}}

                        <input name="atc_title" id="J_atc_title" value="{{ $atc_title or ''}}"
                               class="input length_6 mr15"
                               {{ !empty($isTopic) ? ' required' : '' }} aria-required="true"
                               placeholder="{{ @!empty($default_title) ? $default_title : '请输入标题' }}"
                               title="请输入标题" data-max="{{ Core::C('bbs', 'title.length.max') }}"/><span
                                id="J_title_tip"></span>
                    </hgroup>

                    {{-- <hook class='$pwpost' name="createHtmlBeforeContent"/> --}}

                    <div class="c"></div>
                    <div class="cc mb10">
                        <div class="cm">
                            <div class="cw">
                                {{--//$pwpost->displayHtmlFromBeforeContent();--}}
                                @include('bbs.wind_editor')
                            </div>
                        </div>
                        <div class="sd">
                            <div class="sidebar">
                                @if ($action == 'run' || $action == 'modify')
                                    @include('tag.post_tag')
                                @endif
                                {{-- <hook class='$pwpost' name='createHtmlRightContent'/> --}}
                            </div>
                        </div>
                    </div>
                    <input type="hidden" value="{{ $pid or ''}}" name="pid"/>
                    <input type="hidden" value="{{ $tid or ''}}" name="tid"/>
                    <input type="hidden" value="{{ $special or ''}}" name="special"/>
                    <div class="ft cc">
                        <button type="submit" name="Submit" class="btn btn_submit btn_big fl mr10"
                                id="J_post_sub">发布
                        </button>
                        <a href="{{ url('bbs/draft/doAdd') }}" class="btn btn_big mr10" id="J_draftBtn">存为草稿</a>
                        <label for="reply_notice_label"><input name="reply_notice" id="reply_notice_label"
                                                               type="checkbox"
                                                               value="1" {{ $reply_notice }}/>有回复提醒我</label>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('common.footer')
</div>
<!--结束-->

<script>
    Wind.use('jquery', 'global', 'ajaxForm', 'dialog', function () {
        $("#J_atc_title").focus();

        //主题分类
                @if (!empty($topictypes['topic_types']))

        var CURRENT_TOPIC_TYPE = parseInt('{{ $defaultTopicType }}'),					//默认主题分类
                PARENT_TOPIC_TYPE = parseInt('{{ $defaultParentTopicType }}'),		//默认二级父主题分类
                SORT_CONFIG = $.parseJSON('{!! $subTopicTypesJson !!}');				//分类数据
        var sort_1 = $('#J_sort_1'), sort_2 = $('#J_sort_2');

        //选择当前主题分类
        if (PARENT_TOPIC_TYPE) {
            //二级主题
            sort_1.val(PARENT_TOPIC_TYPE);
            sortSelect(PARENT_TOPIC_TYPE, CURRENT_TOPIC_TYPE);
        } else if (!PARENT_TOPIC_TYPE && CURRENT_TOPIC_TYPE) {
            //只有一级
            sort_1.val(CURRENT_TOPIC_TYPE);
            sortSelect(CURRENT_TOPIC_TYPE);
        }

        sort_1.on('change', function () {
            sortSelect($(this).val());
        });

        //切换下拉
        function sortSelect(s1, s2) {
            var data = SORT_CONFIG[s1], //二级分类对象
                    arr = [];

            if (data) {
                //存在二级分类
                //arr.push('<option value="0">请选择分类</option>');
                $.each(data, function (i, o) {
                    arr.push('<option value="' + i + '">' + o + '</option>');
                });
                sort_2.html(arr.join('')).show();

                if (s2) {
                    //选中第二级分类
                    sort_2.val(s2);
                }
            } else {
                //不存在二级分类
                sort_2.hide().empty();
            }
        }

        @endif
        //主题分类结束

        //点击热门标签
        $('#J_hot_tag a').on('click', function (e) {
            e.preventDefault();
            var $this = $(this),
                    text = $this.text();

            //不可点
            if ($this.parent().hasClass('disabled')) {
                return false;
            }

            //判断是否已经添加
            if (!$('ul.J_user_tag_ul input[value="' + text + '"]').length) {
                $('ul.J_user_tag_ul').append('<li><a><span class="J_tag_name">' + text + '</span><del class="J_user_tag_del" title="' + text + '">×</del><input type="hidden" name="tagnames[]" value="' + text + '"></a></li>');
            }
            $this.parent().addClass('disabled');
        });

        //删除标签后，修改热门标签状态
        $('ul.J_user_tag_ul').on('click', '.J_user_tag_del', function (e) {
            $('#J_hot_tag > li[title="' + $(this).attr('title') + '"]').removeClass('disabled');
        });

        var title = $('#J_atc_title'),
                content_editor = $('#J_wind_editor'),
                title_tip = $('#J_title_tip');


        @if ($action == 'reply')
        //编辑页 后端不验证标题空 ie拿掉placeholder
        /*if($.browser.msie) {
         title.removeAttr('placeholder');
         }*/
        @endif

        //标题字数统计
        ;
        (function (title, title_tip) {
            var inputEvent = function () {
                var length = $.trim(title.val().length);
                var max = title.data('max');
                if (length > max) {
                    title_tip.html('最多' + max + '个字，已经超出<span style="color:red;font-weight:bold;">' + (length - max) + '</span>个字');
                } else {
                    title_tip.html('');
                }
            };
            var title_0 = title[0];
            if ($.browser.version == '9.0') {//IE9对下面两个事件支持有缺陷
                setInterval(function () {
                    inputEvent();
                }, 64);
            } else if ('oninput' in title_0) {
                title.on('input', inputEvent);
            } else {//IE6/7/8
                title[0].onpropertychange = function () {
                    if (window.event.propertyName == "value") {
                        var $this = $(this);
                        //处理placeholder的问题
                        if ($this.attr("placeholder") == $this.val()) {
                            return false;
                        }
                        inputEvent();
                    }
                }
            }
        })(title, title_tip);

        //草稿
        ;
        (function () {
            var draft_count = $('#J_draft_count'),		//草稿数
                    draft_wrap = $('#J_draft_wrap'),		//列表内容
                    draft_loaded = false;

            //存为草稿
            $('#J_draftBtn').on('click', function (e) {
                e.preventDefault();
                var $this = $(this),
                        windeditor = content_editor.data('windeditor'),
                        title_v = title.val(),
                //没有编辑器直接取textarea的值，目前没有做自动同步，需要用getValue获取编辑器的值
                        content = windeditor ? windeditor.getValue() : content_editor.val();
                if (!$.trim(title_v) || !$.trim(content)) {
                    Wind.Util.resultTip({
                        follow: $this,
                        error: true,
                        msg: '帖子标题或内容不能为空'
                    });
                    return false;
                }

                $.post(this.href, {'atc_title': title_v, 'atc_content': content}, function (data) {
                    if (data.state === 'success') {
                        Wind.Util.resultTip({
                            elem: $this,
                            follow: true,
                            msg: '保存成功'
                        });
                        draft_loaded = false;

                        //+1
                        draft_count.text(parseInt(draft_count.text()) + 1).parent().show();

                        //清空
                        draft_wrap.html('<div class="pop_loading"></div>');
                    } else if (data.state === 'fail') {
                        Wind.Util.resultTip({
                            error: true,
                            elem: $this,
                            follow: true,
                            msg: data.message
                        });
                    }
                }, 'json');
            });

            //获取草稿列表
            var draft_list_btn = $('#J_draft_lis_btn'),
                    draft_list = $('#J_draft_list'),
                    content_cache = {};

            draft_list_btn.on('click', function (e) {
                e.preventDefault();
                var $this = $(this);

                draft_list.toggleClass('dn');
                draft_list_btn.toggleClass('up_current');

                if (!draft_loaded && draft_list_btn.hasClass('up_current')) {
                    draft_loaded = true;
                    //未请求数据
                    var li_arr = [];
                    $.getJSON($this.attr('href'), function (data) {
                        if (data.state === 'success') {
                            if (!data.data.length) {
                                draft_wrap.html('<div class="not_content_mini"><i></i>啊哦，草稿箱暂没有任何内容哦！</div>');
                                return;
                            }

                            $.each(data.data, function (i, o) {
                                li_arr.push('<li><a data-pdata="\'id\':\'' + o.id + '\'" href="{{ url('bbs/draft/doDelete') }}" class="pop_close J_draft_del" title="删除草稿">[删除]</a><a href="" data-id="' + o.id + '" class="insert J_draft_insert" title="载入草稿">[载入]</a><span class="J_draft_title">' + o.title + '</span><span>' + o.created_time + '</span></li>');

                                //存入内容
                                content_cache[o.id] = o.content;
                                draft_list_btn.addClass('up_current');
                            });
                            draft_wrap.html('<ul>' + li_arr.join('') + '</ul>');

                        } else if (data.state === 'fail') {
                            Wind.Util.resultTip({
                                error: true,
                                msg: data.message
                            });
                            draft_loaded = false;
                        }
                    });

                }
            }).on('blur', function () {
                //失焦隐藏
                if (!h_lock) {
                    draft_list.addClass('dn');
                    draft_list_btn.removeClass('up_current');
                }
            });

            //聚焦判定
            var h_lock = false;
            draft_list.on('mouseenter', function () {
                h_lock = true;
            }).on('mouseleave', function () {
                draft_list_btn.focus();
                h_lock = false;
            });


            draft_list.on('click', 'a.J_draft_del', function (e) {
                //删除草稿
                e.preventDefault();
                var $this = $(this);

                Wind.dialog({
                    message: '确定删除该草稿内容？',
                    type: 'confirm',
                    isMask: false,
                    follow: $this, //跟随触发事件的元素显示
                    onOk: function () {
                        $('body').trigger('setCustomPost', [$this]);
                        $.post($this.attr('href'), function (data) {
                            if (data.state === 'success') {
                                $this.parent('li').remove();
                                var c = parseInt(draft_count.text());
                                draft_count.text(c - 1);
                                if (c <= 1) {
                                    draft_wrap.html('<div class="not_content_mini"><i></i>啊哦，草稿箱暂没有任何内容哦！</div>');
                                }
                            } else if (data.state === 'fail') {
                                //global.js
                                Wind.Util.resultTip({
                                    error: true,
                                    msg: data.message
                                });
                            }
                        }, 'json');
                    }
                });
            }).on('click', 'a.J_draft_insert', function (e) {
                //载入草稿
                e.preventDefault();
                var $this = $(this);
                Wind.dialog({
                    message: '载入草稿内容将会覆盖当前内容，确定载入？',
                    type: 'confirm',
                    isMask: false,
                    follow: $this, //跟随触发事件的元素显示
                    onOk: function () {
                        windeditor = content_editor.data('windeditor');		//编辑器对象
                        title.val($this.siblings('.J_draft_title').text());					//写入标题
                        windeditor.setContent(content_cache[$this.data('id')]);	//写入内容
                    }
                });
            }).on('click', '.J_draft_title', function (e) {
                //点击标题
                $(this).siblings('a.J_draft_insert').click();
            });
        })();


//切换发帖类型选项卡
        $("#tabTypeHead").on('click', 'li:not(.current)', function () {
            window.onbeforeunload = $.noop;
            var title = $('#J_atc_title');
            var content = $('#J_wind_editor');
            var editor = content.data('windeditor');
            editor && editor.saveContent();
            var atc_title = $.trim(title.val());
            var atc_content = $.trim(content.val());
            if (atc_title != "" || (atc_content != "" && atc_content != "<br>")) {
                if (confirm('确认离开并放弃此页面内容?')) {
                    editor && editor.clear_local_data();
                } else {
                    return false;
                }
            } else {
                editor && editor.clear_local_data();
            }
        });

        var forceTopic = '{{ $forceTopic }}';//是否强制主题分类，1强制
        /*
         * 提交
         */
        var word_tpl = '<div class="pop_sensitive"><div class="pop_top J_drag_handle"><a href="#" class="pop_close J_close">关闭</a><strong>提示</strong></div><div class="pop_cont"><div class="not_content_mini" id="J_word_tip"></div></div><div class="pop_bottom" id="J_word_bototm"></div></div>',
                needcheck = ('{{ $needcheck }}' == '1') ? true : false,		//是否审核
                post_btn = $('#J_post_sub');							//提交按钮

        //点击发布
        $('#mainForm').on('submit', function (e) {
            e.preventDefault();
            var titleInput = $("#J_atc_title"),
                    rx_tit = titleInput.val().replace(/\s*/g, '');
            var contentTextarea = $("#J_wind_editor"),
                    editor = contentTextarea.data('windeditor');
            //校验标题和内容是否为空,发新帖时才校验
            if (POST_TYPE === Post_Type_Enum.NEW_POST && (rx_tit === '' || contentTextarea.val().replace(/\s*/g, '') === '')) {
                Wind.Util.resultTip({
                    error: true,
                    msg: '帖子标题或内容不能为空',
                    follow: post_btn,
                    callback: function () {
                        if (!rx_tit) {
                            titleInput.focus();
                        } else {
                            //editor.setFocus($(editor.editorDoc.body));
                        }
                    }
                });
                return;
            }

            //针对不支持placeholder的浏览器优化处理
            if (document.createElement('input').placeholder !== '' && POST_TYPE === Post_Type_Enum.NEW_POST) {
                if (titleInput.val() === titleInput.attr("placeholder")) {
                    Wind.Util.resultTip({
                        error: true,
                        msg: '帖子标题不能为空',
                        follow: post_btn
                    });
                    return;
                }
            }
            //检查强制分类
            if (forceTopic == 1) {
                var needPop = false;//是否需要弹窗显示分类
                var topicSelects = $('.post_page .title').find('select');
                topicSelects.each(function (i, item) {
                    var option = $(item).find('option:selected');
                    //console.log(option.text(), $(item).val())
                    if ($(item).val() == 0) {
                        needPop = true;
                    }
                });
                if (needPop === true) {
                    //如果满足条件，调用弹窗显示分类
                    Wind.js(GV.JS_ROOT + 'pages/bbs/topicType.js?v=' + GV.JS_VERSION, function () {
                        var url = GV.URL.TOPIC_TYPIC;
                        var fid = '{{ $fid }}';
                        var topic = new ShowTopicPop({
                            url: url, fid: fid, callback: function (data) {
                                if (data) {
                                    needPop = false;
                                    $(data).each(function (i, item) {
                                        if (i === 0) {
                                            $("#J_sort_1").val(item.val);
                                            sortSelect(item.val);//调用页面里面的联动方法
                                        }
                                        if (i === 1) {
                                            $("#J_sort_2").val(item.val);
                                        }
                                    });
                                    post_btn.click();
                                }
                            }
                        });
                        topic.init();
                    });
                    return false;
                }
            }
            //是否要审核
            if (needcheck) {
                Wind.dialog({
                    message: '本版块内容需要管理员审核后才能正常显示！',
                    type: 'confirm',
                    isMask: false,
                    okText: '发布',
                    onOk: function () {
                        needcheck = false;
                        postSubmit();
                    }
                });
            } else {
                //fix IE9 bug 包括 IE9兼容模式下的IE9文档模式，虽然version是7.0，但是和9.0一样
                if ($.browser.msie) {
                    var form = $('#mainForm')[0];
                    for (var i = 0, len = form.elements.length; i < len; i++) {
                        var item = form.elements[i];
                        if (item && item.type == 'application/x-shockwave-flash') {
                            item.parentElement.removeChild(item);
                        }
                    }
                }
                postSubmit();
            }

        });

        var verify = false;

        @if ($hasVerifyCode)
        //开启验证码
        verify = true;
        @endif

        //帖子发布 ajax
        function postSubmit() {
            if (verify && Wind.Util.showVerifyPop(post_btn)) {
                //提交前验证码判断 未通过
                return false;
            }

            $('#mainForm').ajaxSubmit({
                dataType: 'json',
                beforeSerialize: function () {
                    //同步编辑器的内容到textarea中
                    //!TODO:编辑器组件里自动同步
                    //content_editor.data('windeditor').saveContent();
                },
                beforeSubmit: function (data) {
                    var title_tip = $('#J_atc_title');
                    var max = parseInt(title_tip.data('max'));
                    if ($.trim(title_tip.val()).length > max) {
                        title_tip.focus();
                    }
                    //global.js
                    Wind.Util.ajaxBtnDisable(post_btn);
                },
                success: function (data, statusText, xhr, $form) {
                    if (!data || !data.state) {
                        Wind.Util.resultTip({
                            error: true,
                            msg: '系统出错',
                            follow: post_btn
                        });
                        return;
                    }
                    if (data.state == 'success') {
                        //提交时页面跳转会触发onbeforeunload
                        window.onbeforeunload = $.noop;
                        //清除编辑器草稿
                        content_editor.data('windeditor').clear_local_data();
                        if (data.referer) {
                            setTimeout(function () {
                                location.href = decodeURIComponent(data.referer);
                            }, 10);
                        }
                    } else if (data.state == 'fail') {
                        //global.js
                        Wind.Util.ajaxBtnEnable(post_btn);
                        var _data = data.data;

                        if (_data) {

                            if (_data.isVerified) {
                                //审核
                                Wind.dialog.html(word_tpl, {
                                    id: 'J_word_wrap',
                                    callback: function () {
                                        $('#J_word_tip').html(data.message + '<br>是否继续发布？');
                                        $('#J_word_bototm').html('<button type="button" id="J_word_change" class="btn btn_submit mr10">马上修改</button><button type="button" class="btn" id="J_word_subdirect">直接发布</button>');

                                        //马上修改
                                        wordCallback();

                                        //马上发布
                                        $('#J_word_subdirect').on('click', function (e) {
                                            e.preventDefault();
                                            //增加action 参数
                                            $form[0].action = $form[0].action + '&verifiedWord=1';

                                            postSubmit();
                                            Wind.dialog.closeAll();
                                        });
                                    }
                                });
                            }
                        } else {
                            Wind.Util.resultTip({
                                error: true,
                                msg: data.message,
                                follow: post_btn
                            });
                        }

                    }
                }
            });
        }

        //敏感词回调
        function wordCallback(text) {
            Wind.use('draggable', function () {
                $('#J_word_wrap').draggable({handle: '.J_drag_handle'});
            });

            //敏感词修改聚焦
            $('#J_word_change').on('click', function (e) {
                e.preventDefault();
                Wind.dialog.closeAll();

                $('iframe.wind_editor_iframe')[0].contentWindow.document.body.focus();
            });

        }

        @if ($special == 'poll')
        //投票帖
        Wind.js(GV.JS_ROOT + 'pages/bbs/postVote.js?v=' + GV.JS_VERSION);
        @endif


    })
    ;
</script>
</body>
</html>
