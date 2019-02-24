<?php
/**
 * Plugin Name: Half Ucenter
 * Description: 一个优雅而强大的用户中心
 * Version: 0.01
 * Author: HoLP Tech.
 * Author URI: https://www.ouorz.com
 * Text Domain: half-center
 */
 
 //防止外部访问
if ( !defined('ABSPATH') ) {exit;}

// 注册菜单
function half_register_menu(){
    add_menu_page( 'Simple Test', 'Simple Test', 'manage_options', 'half-ucenter', 'half_create_content_page');
}

function half_create_content_page(){
	if(isset($_POST['submit'])){
		update_option( 'half_test_content', $_POST['content'] );
	}?>
	<h1>你可以在此处设置插件内容</h1>
	<div class="wrap">
		<h2>Simple Test Page</h2>
		<form method="post" id="half-ucenter-form">
			<label><strong>内容</strong></label>
			<div>
	        <textarea rows="10" cols="80" name="content" id="content"><?php echo get_option('half_test_content') ;?></textarea>
			</div>
			<div>
				<input type="submit" class="button button-primary button-large" name="submit" value="保存">
			</div>
		</form>
    </div>
	<?php
}

add_action('admin_menu', 'half_register_menu');



add_filter( 'page_template', 'check_page_creation' );
function check_page_creation( $page_template )
{
    if ( is_page( 'register' ) ) {
        $page_template = dirname( __FILE__ ) . '/pages/register.php';
    }
    if ( is_page( 'login' ) ) {
        $page_template = dirname( __FILE__ ) . '/pages/login.php';
    }
    if ( is_page( 'updates' ) ) {
        $page_template = dirname( __FILE__ ) . '/pages/updates.php';
    }
    if ( is_page( 'editinfo' ) ) {
        $page_template = dirname( __FILE__ ) . '/pages/editinfo.php';
    }
    if ( is_page( 'editavatar' ) ) {
        $page_template = dirname( __FILE__ ) . '/pages/editavatar.php';
    }
    if ( is_page( 'usermark' ) ) {
        $page_template = dirname( __FILE__ ) . '/pages/usermark.php';
    }
    return $page_template;
}



function half_css() {
    require 'wp-content/plugins/half-ucenter/func/functions.php';
    ?>
    <link href="<?php echo esc_url( plugins_url( '/half-ucenter/half.css', dirname(__FILE__) ) ) ?>" rel="stylesheet">
    <?php
}
//add_action('get_header', 'half_css');


























































