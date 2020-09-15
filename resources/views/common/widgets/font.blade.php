<!-- 数据格式定义：__tpl_data = array('font' => '' , 'bold' => '' , 'italic' => '' , 'underline' => '' , 'color' => '')  -->
<!-- name:radio名称,默认值为radio；value:radio当前值,默认值为0；items:为radio的选项,默认一个项 -->
<!--# isset($__tpl_data['font']) || $__tpl_data['font'] = '';
isset($__tpl_data['bold']) || $__tpl_data['bold'] = '';
isset($__tpl_data['italic']) || $__tpl_data['italic'] = '';
isset($__tpl_data['underline']) || $__tpl_data['underline'] = '';
isset($__tpl_data['color']) || $__tpl_data['color'] = '';  #-->

<div class="color_pick_dom J_font_config">
    <div class="case"{{ $__tpl_data['font'] }}><p>字体预览</p>
        <p>ABCD</p></div>
    <ul>
        <li><label><input name="fontBold" class="J_bold" data-class="b" type="checkbox"
                          value="1" {{ App\Core\Tool::ifcheck($__tpl_data['bold']); }}>粗体</label></li>
        <li><label><input name="fontItalic" class="J_italic" data-class="i" type="checkbox"
                          value="1" {{ App\Core\Tool::ifcheck($__tpl_data['italic']); }}>斜体</label></li>
        <li class="none"><label><input name="fontUnderline" data-class="u" type="checkbox" class="J_underline"
                                       value="1" {{ App\Core\Tool::ifcheck($__tpl_data['underline']); }}>下划线</label></li>
    </ul>
    <span class="color_pick"><em style="background:{$__tpl_data['color']};" class="J_bg"></em></span>
    <input name="fontColor" type="hidden" class="J_hidden_color" value="{{ $__tpl_data['color'] }}">
</div>
