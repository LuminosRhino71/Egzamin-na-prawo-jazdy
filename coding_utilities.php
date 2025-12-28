<?php
function print_r_with_pre_tag($variable) {
    echo '<pre>';
    print_r($variable);
    echo '</pre>';
}