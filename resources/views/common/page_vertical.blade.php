<?php
$_page_min = max(1, $page - 1);
$_page_max = min($total, $page + 1);
?>
<div class="floor_page">
    <?php
    if ($page > $_page_min){
    $_page_i = $page - 1;
    ?>
    <a href="{{ $url }}" class="pre" title="上一页">上一页</a>
    <?php
    }else{
    ?>
    <span class="pre">上一页</span>
    <?php } ?>
    <?php
    if ($page < $_page_max){
    $_page_i = $page + 1;
    ?>
    <a href="{{ $url }}" class="next" title="下一页">下一页</a>
    <?php
    }else{
    ?>
    <span class="next">下一页</span>
    <?php } ?>
</div> 