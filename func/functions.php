<?php

//获取用户评论总数
function get_user_comments_count( $user_id ) {
	global $wpdb;
	$user_id = (int) $user_id;
	$sql     = "SELECT COUNT(*) FROM {$wpdb->comments} WHERE user_id='$user_id' AND comment_approved = 1";
	$coo     = $wpdb->get_var( $sql );
	$coo =  $coo ? $coo : 0;
	return change_num_format($coo);
}

function change_num_format($n){
    switch($n){
        case $n > 10:
            $n = ($n / 1000).'K';
            break;
        case $n > 1000:
            $n = floor($n / 1000).'K';
            break;
        case $n > 10000:
            $n = floor($n / 10000).'W';
            break;
        case $n > 100000:
            $n = '999+';
            break;
        default:
            break;
    }
    return $n;
}
?>