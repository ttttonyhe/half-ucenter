<?php 
/*
Template Name: 用户头像修改
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

//获取文件后缀函数
function getsuffix($files){
    $suffixarray= explode('.',$files);
    $suffixarray = array_reverse($suffixarray); 
    return $suffixarray [0];
}

$error = '';

if(!empty($_FILES['file']['name'])){
/* 变量赋值 */
$files_type = $_FILES["file"]["type"];
$files_error = $_FILES["file"]["error"];
$files_name = $_FILES["file"]["name"];
$files_suff = getsuffix($files_name);
$files_name = time().'.'.$files_suff;
$files_size = $_FILES["file"]["size"];

global $current_user;
$user_id = $current_user->ID;

/* 变量赋值结束 */

if ((($files_type == "image/png") || ($files_type == "image/gif") || ($files_type == "image/jpeg") || ($files_type == "image/pjpeg")) && ($files_size < 2097152)){
    
  if ($_FILES["file"]["error"] > 0)
    {
        $error .= 'Upload error | <a href="mailto:he@holptech.com">Click here to report the problem</a><br />';
    }
  else
    {
    if (file_exists("wp-content/uploads/avatar/" . $files_name))
      {
          $error .= 'Image already exists | <a href="mailto:he@holptech.com">Click here to report the problem</a><br />';
      }
    else
      {
          move_uploaded_file($_FILES["file"]["tmp_name"],
          "wp-content/uploads/avatar/" . $files_name);
          $files_url = 'https://platform.snapaper.com/wp-content/uploads/avatar/'.$files_name;
          $status = update_user_meta( $user_id, 'avatar', $files_url );
          if($status) $error .= 'The avatar was successfully modified.<br />'; else $error .='Avatar modification failed<br />';
      }
    }
  }
else
  {
      $error .= 'Image type is not supported or the image is over 2MB<br />';
  }
}
?>

<style>
.user-form{
    display: block;
    margin-left: auto;
    margin-right: auto;
    background: #fff;
    box-shadow: 0 1px 2px rgba(0,0,0,0.1);
    width: 465px;
    padding: 6rem 1rem;
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
    font-size: 1rem;
    padding: 5px 10px;
    background: #fbfbfb;
    border: 1px solid #e1e3e5;
    border-radius: 4px;
    position: relative;
    font-weight: 300;
    width: 60%;
    margin-top: 40px;
    margin-bottom: 20px;
    transition: all .3s;
}
.user-reg .input:focus{
    border: 1px solid #444;
}
.user-reg .input:hover{
    border: 1px solid #666;
}
.user-reg{
    text-align: center;
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
?>

<form class="user-reg user-form" action="<?php echo $_SERVER["REQUEST_URI"]; ?>" method="post" enctype="multipart/form-data">
    <?php echo get_avatar($author_id,96,'','user-avatar',array('width'=>120,'height'=>120,'rating'=>'X','class'=>array('uk-card','uk-card-default','uk-card-hover','uc-edit-avatar'),'extra_attr'=>'title="user-avatar"','scheme'=>'https') ); ?>
    <input class="input" type="file" name="file" id="file" /><br>
    <input class="b-submit" type="submit" name="submit" value="Save Changes" />
    <a href="/updates" class="submit-log">Back</a>
</form>
<?php get_footer(); }?>