<?php 
/*
Template Name: 用户资料修改
*/
get_header();?>
<?php wp_no_robots(); ?>
<?php
    if(!is_user_logged_in()){ ?>
    <script type="text/javascript">
    window.location.href = 'https://platform.snapaper.com/login';
</script>
<?php    }else{
?>
<?php
/* 读取表单数据 */
if( !empty($_POST['edit_info'])) {
    global $current_user;
    $user_id = $current_user->ID;
    $user_old_email = $current_user->user_email;
    $user_old_name = $current_user->nickname;
    $sanitized_user_login = sanitize_user( $_POST['user_name'] );
    $user_email = $_POST['user_email'];
    $error = '';
    $current_pass = '';
    $current_user_name ='';
    $current_user_email = '';

    /* 修改密码 */
    if($_POST['user_pass']==''){ 
        $error='';
    }else{
        if(strlen($_POST['user_pass']) < 6)
        { 
            $error .= '<strong>ERROR</strong>：Password length is at least 6 characters<br />'; 
        }
    else{
        if($_POST['user_pass'] != $_POST['user_pass2']){
        $error .= '<strong>ERROR</strong>：The password entered twice must be the same<br />';}
    else {
        $enter_pass = $_POST['user_pass'];
        $current_pass = md5($enter_pass); }}}
        
    if(!$current_pass == ''){
       header("charset=utf-8");
    @$con=mysqli_connect('localhost','root','Goodhlp616877','snapaper',3306);
    if (mysqli_errno($con)) {
        $error .='Database connection failed<br />';
        exit;
    }else{
        $sql_change = 'UPDATE snap_users SET user_pass="'.$current_pass.'" WHERE ID ='.$user_id;
        @$result=mysqli_query($con,$sql_change);
        if ($result) {
            $error .= 'Password reset complete<br />';
        } else {
            $error .= 'Password modification failed<br />';
        }
    @mysqli_close($con);
}
    }
    /* 修改密码结束 */
    
    /* 修改用户名 */
  if ( $sanitized_user_login == '' || $sanitized_user_login == $user_old_name) {
    $error .= '';
  } elseif ( ! validate_username( $sanitized_user_login ) ) {
    $error .= '<strong>ERROR</strong>：This username contains invalid characters, please enter a valid username<br />。';
    $sanitized_user_login = '';
  } elseif ( username_exists( $sanitized_user_login ) ) {
    $error .= '<strong>ERROR</strong>：The username has already been registered, please select another one<br />';
  }else $current_user_name = $sanitized_user_login;
  
  if(!$current_user_name ==''){
      $status = update_user_meta( $user_id, 'nickname', $current_user_name );
      if($status) $error .= 'User name modified successfully<br />'; else $error .='User name modification failed<br />';
      $status = '';
  }
  /* 修改用户名结束 */
  

}
?>
<style>
.user-form{
    display: block;
    margin-left: auto;
    margin-right: auto;
    background: #fff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    width: 565px;
    padding: 3rem 1rem;
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
  margin-bottom: 5px;
  margin-left: 105px;
}
.user-reg .input {
    margin: 0;
    color: #555;
    font-size: 24px;
    padding: 10px;
    background: #fbfbfb;
    height: 45px;
    border: 1px solid #e1e3e5;
    border-radius: 4px;
    margin-top: 5px;
    margin-bottom: 10px;
    font-weight: 300;
    transition: all .3s;
}
.user-reg .input:focus{
    border: 1px solid #444;
}
.user-reg .input:hover{
    border: 1px solid #666;
}
.user-reg{
    text-align: left;
    margin-top: 5%;
    margin-bottom: 5%;
}
.b-submit{
    padding: 10px 20px 10px 20px !important;
    border-radius: 3px !important;
    border: 1px solid #333 !important;
    color: #fff !important;
    background: #333 !important;
    font-size: 15px !important;
    margin-left: 0px !important;
    letter-spacing: 0px !important;
    font-weight: 500 !important;
    text-transform: none !important;
}
.b-submit:hover{
    background: rgb(40, 40, 40);
    color: #fff;
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
    text-align:  left;margin-left: 105px;margin-bottom: 40px;
}

.form-submit{
    margin-left: 105px;
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


.tip{
    margin-bottom: 50px;
    margin-left: 105px;
    color: #7d7d7d;
}
</style>

<script>
    function close_error(){
        var change=document.getElementById('error');
        change.style.display="none";
    }
</script>


<?php the_content(); ?>
<?php if(!empty($error)) {
  echo '<div id="error" class="intro"><div class="intro-bg animations-fadeIn-bg"></div><div id="close_error" class="intro-area animations-fadeInUp-focus" style="border-radius: 3px;box-shadow:0 2px 2px 0 rgba(0,0,0,.14), 0 3px 1px -2px rgba(0,0,0,.2), 0 1px 5px 0 rgba(0,0,0,.12)"><div class="intro-content" style="width: 510px;max-height: calc(100vh - 5px * 2);"><div class="intro-content-container" style="font-size: 18px;padding: 40px 30px;text-align:center" id="report_container"><div class="intro-content-header"><div class="intro-content-title">提示</div></div><p style="text-align: center;font-size: 19px;">'.$error.'</p><div class="intro-content-button"><button onclick="close_error();" class="intro-button">Close</button></div></div></div></div></div>';
} ?>
<?php 
    global $current_user;
	$author_id = $current_user->ID;
	$author_name = $current_user->nickname;
	$user_email = $current_user->user_email;
?>
<form name="registerform" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" class="user-reg user-form">
    <div style="text-align:  left;margin-left: 105px;margin-bottom: 40px;">
        <h2 style="font-weight: 600;font-size: 3rem;margin-bottom: 5px;">Edit Info</h2>
        <p style="font-size: 1.4rem;color: #999;letter-spacing: 1px;">Update account information</p>
    </div>
    
    <p>
      <label for="user_avatar">Avatar<br><br>
        <a class="up-author-status-1" style="border-radius: 4px;font-size: 1.2rem;background: #eee;padding: 7px 15px;font-weight: 500;color: #666;" href="https://platform.snapaper.com/editavatar">Click to Change</a>
        <br><br>
      </label>
    </p>
    
    <p>
      <label for="user_login">Nickname<br />
        <input type="text" name="user_name" tabindex="1" id="user_login" class="input" value="<?php echo $author_name; ?>" size="25"/>
      </label>
    </p>
    
    <p>
      <label for="user_pwd1">Password(At least 6 characters)<br />
        <input id="user_pwd1" class="input" tabindex="3" type="password" tabindex="21" size="25" name="user_pass" />
      </label>
    </p>
    
    <p>
      <label for="user_pwd2" style="margin-bottom:0px">Confirm Password<br />
        <input id="user_pwd2" class="input" tabindex="4" type="password" tabindex="21" size="25" name="user_pass2" />
      </label>
    </p>
    
    <div class="tip">
        <i class="icon-info-circled"></i>User email address cannot be modified
    </div>
    
    <p style="margin-left: 105px;">
      <input type="hidden" name="edit_info" value="ok" />
      <button class="b-submit" type="submit">Save Changes</button>
      <a href="/updates" class="submit-log">Back</a>
    </p>
</form>
<?php get_footer(); }?>