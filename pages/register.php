<?php
    if(!is_user_logged_in() || $_COOKIE['yoyocheckitout'] > 3){
        wp_redirect( site_url() );
    }else{
        get_header();
?>
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
.user-reg label {
  color: #777;
    font-size: 16px;
    cursor: pointer;
    text-align: left;
    margin-bottom: 2px;
    width: 100%;
}
.user-reg .input {
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

.user-reg{
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
.submit-log{
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
    .user-reg label{
        margin-left: 0px;
    }
    .form-submit{
        margin-left: 0px;
    }
}


</style>
<?php 
/* 验证注册表单 */
if( !empty($_POST['user_reg']) ) {
  $error = '';
  $sanitized_user_login = sanitize_user( $_POST['user_login'] );
  $user_email = apply_filters( 'user_registration_email', $_POST['user_email'] );

  // Check the username
  if ( $sanitized_user_login == '') {
    $error .= '<strong>ERROR</strong>：Please enter user name<br />';
  } elseif ( ! validate_username( $sanitized_user_login ) ) {
    $error .= '<strong>ERROR</strong>：This username contains invalid characters, please enter a valid username<br />';
    $sanitized_user_login = '';
  } elseif ( username_exists( $sanitized_user_login ) ) {
    $error .= '<strong>ERROR</strong>：The username has already been registered, please select another one<br />';
  } elseif( strlen($sanitized_user_login) > 20 ){
    $error .= '<strong>ERROR</strong>：Username is too long, please choose another one<br />';
  } elseif ( preg_match("/[\x7f-\xff]/", $sanitized_user_login) ){
    $error .= '<strong>ERROR</strong>：Username cannot contain Chinese, please choose another one<br />';
  }

  // Check the e-mail address
  if ( $user_email == '' ) {
    $error .= '<strong>ERROR</strong>：Please fill in the email address<br />';
  } elseif ( ! is_email( $user_email ) ) {
    $error .= '<strong>ERROR</strong>：Email address is incorrect<br />';
    $user_email = '';
  } elseif ( email_exists( $user_email ) ) {
    $error .= '<strong>ERROR</strong>：The email address has already been registered, please change one<br />';
  }
	
  // Check the password
  if(strlen($_POST['user_pass']) < 6)
    $error .= '<strong>ERROR</strong>：Password length is at least 6 characters<br />';
  elseif($_POST['user_pass'] != $_POST['user_pass2'])
    $error .= '<strong>ERROR</strong>：The password entered twice must be the same<br />';
	  
	if($error == '') {
    $user_id = wp_create_user( $sanitized_user_login, $_POST['user_pass'], $user_email );
	
    if ( ! $user_id ) {
      $error .= sprintf( '<strong>ERROR</strong>：Unable to complete your registration request...</a><br />', get_option( 'admin_email' ) );
    }
    else if (!is_user_logged_in()) {
      $user = get_user_by( 'login', $sanitized_user_login );
      $user_id = $user->ID;
  
      // 自动登录
      wp_set_current_user($user_id, $user_login);
      wp_set_auth_cookie($user_id);
      $cookie++;
      setcookie('yoyocheckitout',$cookie);
      do_action('wp_login', $user_login);

      wp_redirect( site_url() );
    }
  }
}
?>

<script>
    function close_error(){
        var change=document.getElementById('error');
        change.style.display="none";
    }
</script>


<?php the_content(); ?>
<?php 
/* 输出注册表单 */
if(!empty($error)) {
  echo '<div id="error" class="intro"><div class="intro-bg animations-fadeIn-bg"></div><div id="close_error" class="intro-area animations-fadeInUp-focus" style="border-radius: 3px;box-shadow:0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12)"><div class="intro-content" style="width: 510px;max-height: calc(100vh - 5px * 2);"><div class="intro-content-container" style="font-size: 18px;padding: 40px 30px;text-align:center" id="report_container"><div class="intro-content-header"><div class="intro-content-title">提示</div></div><p style="text-align: center;font-size: 19px;">'.$error.'</p><div class="intro-content-button"><button onclick="close_error();" class="intro-button">Close</button></div></div></div></div></div>';
}


if (is_user_logged_in()) { ?>
<form name="registerform" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="user-reg user-form">
    <div class="form-div">
        <h2 style="font-weight: 600;font-size: 3rem;margin-bottom: 5px;">Sign Up</h2>
        <p style="font-size: 1.4rem;color: #999;letter-spacing: 1px;">Join Snapaper Platform</p>
    </div>
    <p>
      <label for="user_login">Username<br />
        <input type="text" name="user_login" tabindex="1" id="user_login" class="input" value="<?php if(!empty($sanitized_user_login)) echo $sanitized_user_login; ?>" size="25"/>
      </label>
    </p>

    <p>
      <label for="user_email">Email<br />
        <input type="text" name="user_email" tabindex="2" id="user_email" class="input" value="<?php if(!empty($user_email)) echo $user_email; ?>" size="25" />
      </label>
    </p>
    
    <p>
      <label for="user_pwd1">Password(At least 6 characters)<br />
        <input id="user_pwd1" class="input" tabindex="3" type="password" tabindex="21" size="25" value="" name="user_pass" />
      </label>
    </p>
    
    <p style="margin-bottom: 30px;">
      <label for="user_pwd2">Confirm Password<br />
        <input id="user_pwd2" class="input" tabindex="4" type="password" tabindex="21" size="25" value="" name="user_pass2" />
      </label>
    </p>
    
    <p class="form-submit">
      <input type="hidden" name="user_reg" value="ok" />
      <button class="b-submit" type="submit">Sign Up</button>
    </p>
</form>
<div class="reg-notice"><p>Already have an account?&nbsp;<a href="https://platform.snapaper.com/login" style="color:#41464b;font-weight:600">Sign in</a></p></div>
<?php } else {
  echo '<p class="user-error">Welcome</p>';
} ?>
<?php get_footer(); }?>