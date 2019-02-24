<?php 
/*
Template Name: 用户收藏列表
*/
get_header();?>
<?php wp_no_robots(); ?>
<?php
    if(!is_user_logged_in()){
        wp_redirect( site_url("/updates") );
    }else{
?>

<div class="cap-title" style="margin-top: 10%;margin-bottom: 80px;width: 810px;">
    <h1>用户收藏</h1>
    <p>您在小半的收藏内容</p>
</div>
<?php
    global $current_user;
    $id = $current_user -> ID;
    
    /* 文章收藏 */
    $posts = get_the_author_meta('mark_post',$id);
    $posts_array = explode(',',$posts);
    $posts_array_length = count($posts_array);
    if($posts_array_length == 1){
        if($posts_array[0] == ''){
            $av = 1;
        }else{
            $posts_array[0] = $posts;
        }
    }
    /* 文章收藏结束 */

?>
<h1 class="usermark_cate">文章收藏</h1>
<?php
if(count($posts_array)>=1 && !$av){
    for($i=0;$i<count($posts_array);$i++){
        $postid = $posts_array[$i];
        if(!empty(get_post_meta($postid, 'cap_img', true))){
            $img_url = get_post_meta($postid, 'cap_img', true);
        }else{
            $img_id = get_post_thumbnail_id($postid); 
            $img_url = wp_get_attachment_image_src($img_id);
            $img_url = $img_url[0];
        }
        $author = get_post($postid)->post_author;
        $author = get_the_author_meta('display_name',$author);
        $title = get_post($postid)->post_title;
        $url = get_post($postid)->post_name;
        $time = get_post($postid)->post_date;
?>
<div class="warp-post-embed" style="width: 805px;">
    <a href="<?php echo $url; ?>" target="_blank" >
        <div class="embed-bg" style="background-repeat: no-repeat;background-size:cover;background-position:center;background-image:url(<?php echo $img_url; ?>?imageView2/2/w/360/h/360/format/jpg/interlace/1/q/100|imageslim)">
        </div>
        <div class="embed-content">
            <span><?php echo $time; ?></span>
            <h2 style="margin-top: 2%;"><?php echo $title; ?></h2>
            <p><?php echo $author; ?></p>
        </div>
    </a>
</div>
<?php }}else{ ?>
<div class="uk-placeholder uk-text-center" style="width: 805px;margin-left: auto;margin-right: auto;">暂 无 收 藏</div>
<?php } ?>

<?php get_footer(); }?>