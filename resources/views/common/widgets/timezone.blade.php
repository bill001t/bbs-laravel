<!-- 数据格式定义：$__tpl_data = array('name' => '' , 'value' => '')  -->
<!-- name:select名称,默认值为timezone；value:select当前值,默认值为8  -->
<!--# isset($__tpl_data['name']) || $__tpl_data['name'] = 'timezone';  
isset($__tpl_data['value']) || $__tpl_data['value'] = '8';  #-->
<select class="select_5" name="{!! $__tpl_data['name'] !!}">
    <option value="-12" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-12') }}>(标准时-12:00) 日界线西</option>
    <option value="-11" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-11') }}>(标准时-11:00) 中途岛、萨摩亚群岛</option>
    <option value="-10" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-10') }}>(标准时-10:00) 夏威夷</option>
    <option value="-9" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-9') }}>(标准时-9:00) 阿拉斯加</option>
    <option value="-8" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-8') }}>(标准时-8:00) 太平洋时间(美国和加拿大)</option>
    <option value="-7" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-7') }}>(标准时-7:00) 山地时间(美国和加拿大)</option>
    <option value="-6" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-6') }}>(标准时-6:00) 中部时间(美国和加拿大)、墨西哥城</option>
    <option value="-5" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-5') }}>(标准时-5:00) 东部时间(美国和加拿大)、波哥大</option>
    <option value="-4" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-4') }}>(标准时-4:00) 大西洋时间(加拿大)、加拉加斯</option>
    <option value="-3.5" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-3.5') }}>(标准时-3:30) 纽芬兰</option>
    <option value="-3" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-3') }}>(标准时-3:00) 巴西、布宜诺斯艾利斯、乔治敦</option>
    <option value="-2" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-2') }}>(标准时-2:00) 中大西洋</option>
    <option value="-1" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '-1') }}>(标准时-1:00) 亚速尔群岛、佛得角群岛</option>
    <option value="0" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '0') }}>(格林尼治标准时) 西欧时间、伦敦、卡萨布兰卡</option>
    <option value="1" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '1') }}>(标准时+1:00) 中欧时间、安哥拉、利比亚</option>
    <option value="2" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '2') }}>(标准时+2:00) 东欧时间、开罗，雅典</option>
    <option value="3" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '3') }}>(标准时+3:00) 巴格达、科威特、莫斯科</option>
    <option value="3.5" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '3.5') }}>(标准时+3:30) 德黑兰</option>
    <option value="4" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '4') }}>(标准时+4:00) 阿布扎比、马斯喀特、巴库</option>
    <option value="4.5" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '4.5') }}>(标准时+4:30) 喀布尔</option>
    <option value="5" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '5') }}>(标准时+5:00) 叶卡捷琳堡、伊斯兰堡、卡拉奇</option>
    <option value="5.5" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '5.5') }}>(标准时+5:30) 孟买、加尔各答、新德里</option>
    <option value="6" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '6') }}>(标准时+6:00) 阿拉木图、 达卡、新亚伯利亚</option>
    <option value="7" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '7') }}>(标准时+7:00) 曼谷、河内、雅加达</option>
    <option value="8" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '8') }}>(北京时间) 北京、重庆、香港、新加坡</option>
    <option value="9" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '9') }}>(标准时+9:00) 东京、汉城、大阪、雅库茨克</option>
    <option value="9.5" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '9.5') }}>(标准时+9:30) 阿德莱德、达尔文</option>
    <option value="10" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '10') }}>(标准时+10:00) 悉尼、关岛</option>
    <option value="11" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '11') }}>(标准时+11:00) 马加丹、索罗门群岛</option>
    <option value="12" {{ App\Core\Tool::isSelected($__tpl_data['value'] == '12') }}>(标准时+12:00) 奥克兰、惠灵顿、堪察加半岛</option>
</select>