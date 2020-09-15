<?php
$_page_min = max(1, $page - 3);
$_page_max = min($total, $page + 3);
?>
<div class="pages">
    <?php
    if ($page > $_page_min){
    $_page_i = $page - 1;
    ?>
    <a href="{{ $url }}" class="pages_pre J_pages_pre">&laquo;&nbsp;上一页</a>
    <?php
    if ($_page_min > 1){
    $_page_i = 1;
    ?>
    <a href="{{ $url }}">1...</a>
    <?php } ?>
    @for ($_page_i = $_page_min; $_page_i < $page; $_page_i++)
        <a href="{{ $url }}">{{ $_page_i }}</a>
    @endfor
    <?php } ?>
    <strong>{{ $page }}</strong>
    <?php
    if ($page < $_page_max){
    ?>
    @for ($_page_i = $page+1; $_page_i <= $_page_max; $_page_i++)
        <a href="{{ $url }}">{{ $_page_i }}</a>
    @endfor
    <?php
    if ($_page_max < $total){
    $_page_i = $total;
    ?>
    <a href="{{ $url }}">...{{ $total }}</a>
    <?php }
    $_page_i = $page + 1;
    ?>
    <a href="{{ $url }}" class="pages_next J_pages_next">下一页&nbsp;&raquo;</a>
    <?php } ?>
</div>