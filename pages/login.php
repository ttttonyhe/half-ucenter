<?php 
/*
Template Name: 用户登录
*/?>
<?php wp_no_robots(); ?>
<?php
    if(is_user_logged_in()){
        wp_redirect( site_url() );
    }else{
?>
<?php

if(!isset($_SESSION))
  session_start();
  
if( isset($_POST['user_token']) && ($_POST['user_token'] == $_SESSION['user_token'])) {
  $error = '';
  $secure_cookie = false;
  $user_name = sanitize_user( $_POST['log'] );
  $user_password = $_POST['pwd'];
  if ( empty($user_name) || ! validate_username( $user_name ) ) {
    $error .= '<strong>ERROR</strong>：Please enter a valid username<br />';
    $user_name = '';
  }
  
  if( empty($user_password) ) {
    $error .= '<strong>ERROR</strong>：Please enter your password<br />';
  }
  
  if($error == '') {
    // If the user wants ssl but the session is not ssl, force a secure cookie.
    if ( !empty($user_name) && !force_ssl_admin() ) {
      if ( $user = get_user_by('login', $user_name) ) {
        if ( get_user_option('use_ssl', $user->ID) ) {
          $secure_cookie = true;
          force_ssl_admin(true);
        }
      }
    }
	  
    if ( isset( $_GET['r'] ) ) {
      $redirect_to = $_GET['r'];
      // Redirect to https if user wants ssl
      if ( $secure_cookie && false !== strpos($redirect_to, 'updates') )
        $redirect_to = preg_replace('|^http://|', 'https://', $redirect_to);
    }else{
        $redirect_to = 'https://platform.snapaper.com';
    }
	
    if ( !$secure_cookie && is_ssl() && !force_ssl_admin() && ( 0 !== strpos($redirect_to, 'https') ) && ( 0 === strpos($redirect_to, 'http') ) )
      $secure_cookie = false;
	
    $creds = array();
    $creds['user_login'] = $user_name;
    $creds['user_password'] = $user_password;
    $creds['remember'] = !empty( $_POST['rememberme'] );
    $user = wp_signon( $creds, $secure_cookie );
    if ( is_wp_error($user) ) {
      $error .= $user->get_error_message();
    }
    else {
      unset($_SESSION['user_token']);
      wp_safe_redirect($redirect_to);
    }
  }

  unset($_SESSION['user_token']);
}

$rememberme = !empty( $_POST['rememberme'] );
  
$token = md5(uniqid(rand(), true));
$_SESSION['user_token'] = $token;
?>
<?php the_content(); ?>
<style>
html{
    background: #F7F7F7 !important;
}
body{
    background-color: #F7F7F7 !important;
}
.user-form{
    width: 480px;
    background-color: #ffffff;
    box-shadow: 0 2px 3px 0 rgba(213,213,213,0.7);
    margin: 10% auto;
    margin-bottom: 3vh;
    padding: 50px 92px;
}
p.user-error {
  margin: 16px 0;
  padding: 12px;
  background-color: #ffebe8;
  border: 1px solid #c00;
  font-size: 12px;
  line-height: 1.4em;
}
.user-log label {
    color: #777;
    font-size: 16px;
    cursor: pointer;
    text-align: left;
    margin-bottom: 2px;
    width: 100%;
}
.user-log .input {
    margin: 0;
    color: #555;
    width: 100%;
    font-size: 18px;
    padding: 10px;
    background: #f5f5f5;
    height: 44px;
    margin-top: 5px;
    border-radius: 4px;
    margin-bottom: 10px;
    font-weight: 500;
    transition: all .3s;
}


.user-log{
    text-align: left;
    margin-top: 10%;
}
.b-submit{
    padding: 10px 20px 10px 20px !important;
    border-radius: 3px !important;
    color: #fff !important;
    font-size: 16px !important;
    margin-left: 0px !important;
    letter-spacing: 0px !important;
    font-weight: 500 !important;
    text-transform: none !important;
    background-image: linear-gradient( #41464b,#2c3033 ) !important;
    border: none !important;
    width: 100%;
    height: 44px;
}
.b-submit:hover{
    background: #363b3e;
}
.submit-reg{
    padding: 7px 20px 8.5px 20px;
    border-radius: 3px;
    border: 1px solid #9ea2a8;
    color: #7d7d7d !important;
    background: #fff;
    font-size: 16px;
    margin-left: 10px;
}

.form-div{
    text-align:  left;margin-bottom: 40px;
}

.reg-notice{
    text-align: center;margin-bottom: 10vh;
}


@media screen and (max-width:767px){
    .user-form{
        margin-top: 20%;
        width: 100%;
    }
    .form-div{
        margin-left: 0px;
    }
    .user-log label{
        margin-left: 0px;
    }
    .form-submit{
        margin-left: 0px;
    }
}


</style>
<script>
    function close_error(){
        var change=document.getElementById('error');
        change.style.display="none";
    }
</script>
<?php if(!empty($error)) {
  echo '<div id="error" class="intro"><div class="intro-bg animations-fadeIn-bg"></div><div id="close_error" class="intro-area animations-fadeInUp-focus" style="border-radius: 3px;box-shadow:0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12)"><div class="intro-content" style="width: 510px;max-height: calc(100vh - 5px * 2);"><div class="intro-content-container" style="font-size: 18px;padding: 40px 30px;text-align:center" id="report_container"><div class="intro-content-header"><div class="intro-content-title">提示</div></div><p style="text-align: center;font-size: 19px;">'.$error.'</p><div class="intro-content-button"><button onclick="close_error();" class="intro-button">Close</button></div></div></div></div></div>';
}
if (!is_user_logged_in()) { ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Snapaper | Sign In</title>
<link href="https://static.zeo.im/uikit.min.css" rel="stylesheet">
<link href="https://static.zeo.im/uikit-rtl.min.css" rel="stylesheet">
<script type="text/javascript" src="https://static.zeo.im/uikit.min.js"></script>
<script src="https://static.ouorz.com/jquery.min.js"></script>
<link rel="Shortcut Icon" href="https://static.ouorz.com/snapaper_logo.ico" type="image/x-icon">
<?php wp_head(); ?>
</head>
<body>
	<div class="site">
		<header class="header-div">
			<div class="uk-container" style="margin-top: 1px;">
		<ul class="nav-1" style="text-align: center;float:unset">
			<li>
				<a href="https://platform.snapaper.com" style="text-decoration:none">
					<h3 class="nav-title">
					<img src="https://static.ouorz.com/snapaper-logo.png" class="nav-title-img">napaper<b style="margin-left: 10px;font-weight: 200;letter-spacing: 1px;font-size: 1.4rem;">Platform</b></h3>
				</a>
			</li></ul>
	</div>
	</header>
<form class="user-form user-log" name="loginform" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
    <div class="form-div">
        <h2 style="font-weight: 600;font-size: 3rem;margin-bottom: 5px;">Sign In</h2>
        <p style="font-size: 1.4rem;color: #999;letter-spacing: 1px;">Login Snapaper Platform</p>
    </div>
    <p>
      <label for="log">Username/Email<br />
        <input type="text" name="log" id="log" class="input" value="<?php if(!empty($user_name)) echo $user_name; ?>" size="25" />
      </label>
    </p>
    <p>
      <label for="pwd">Password(At least 6 characters)<br />
        <input id="pwd" class="input" type="password" size="25" value="" name="pwd" />
      </label>
    </p>
    
    <p class="forgetmenot" style="margin-bottom: 30px;">
      <label for="rememberme">
        <input class="uk-checkbox" name="rememberme" type="checkbox" id="rememberme" value="1" <?php checked( $rememberme ); ?> />&nbsp;Rememberme</label>
    </p>
    
    <p class="form-submit">
      <input type="hidden" name="redirect_to" value="<?php if(isset($_GET['r'])) echo $_GET['r']; ?>" />
      <input type="hidden" name="user_token" value="<?php echo $token; ?>" />
      <button class="b-submit" type="submit">Sign In</button>
    </p>
</form>
<div class="reg-notice"><p>No account?&nbsp;<a href="https://platform.snapaper.com/register" style="color:#41464b;font-weight:600">Create One</a></p></div>
<?php } else {
 echo '<p class="user-error">Already logged in（<a href="'.wp_logout_url().'" title="登出">Sign Out?</a>）</p>';
} ?>
<?php get_footer(); }?>