<?php

  require_once("login.php");
  require_once("../lib/validurl.php");
  require_once("../inc/initunknowns.php");

  $users=json_decode(file_get_contents("../stor/users.db"));
  $userprofile=$users[$_SESSION['uid']];

  initunknowns($userprofile);

  if( @isset($_POST['settingschange']) && @isset($_POST['myname']) && @isset($_POST['mydesc']) && @isset($_POST['mysite']) && @isset($_POST['myavatar']) && @isset($_POST['old-password']) && @isset($_POST['new-password']) && @isset($_POST['confirm-password']) ){

    $errors="";
    $successes="";


    if( $_POST['myname'] != "" ){
      $n=strip_tags(trim(preg_replace('/\t+/', '', $_POST['myname'])));
      if( strlen($n) > 20 ) $errors.="Name too long.<br>";
      else $userprofile->realname=$n;
    }
    else $userprofile->realname="";

    if( $_POST['mydesc'] != "" ){
      $n=strip_tags(trim(preg_replace('/\t+/', '', $_POST['mydesc'])));
      if( strlen($n) > 512 ) $errors.="Bio too long.<br>";
      else $userprofile->bio=$n;
    }
    else $userprofile->bio="";

    if( $_POST['mysite'] != "" ){
      if( isValidURL($_POST['mysite']) ) $userprofile->homepage=$_POST['mysite'];
      else{
        $userprofile->homepage = "";
        $errors.="Invalid homepage URL.<br>";
      }
    }
    else $userprofile->homepage="";

    if( $_POST['myavatar'] != "" && EETI_CONFIG_UPLOADINGENABLED ){
      $img=preg_replace("@[/\\\]@", "", trim(preg_replace('/\t+/', '', $_POST['myavatar'])));

      $valid=array("jpg", "png", "jpeg", "gif");
      $ok=false;
      $ext=explode(".", $_POST['myavatar']);
      $ext=$ext[count($ext)-1];
      foreach($valid as $v){
        if($ext == $v) $ok=true;
      }
      if( ! $ok ){
        $userprofile->avatar = "";
        $errors.="Invalid avatar.<br>";
      }
      else if( ! file_exists("../stor/files/" . $img) ){
        $userprofile->avatar = "";
        $errors.="Invalid avatar.<br>";
      }
      else {
        $userprofile->avatar = $img;
      }
    }

    if( $_POST['old-password'] != "" && $_POST['new-password'] != "" && $_POST['confirm-password'] != "" ){
      if( authenticate($_SESSION['user'], $_POST['old-password']) < 0 ){
        $errors.="Incorrect password.";
      }
      else if( $_POST['new-password'] != $_POST['confirm-password'] ){
        $errors.="Your passwords do not match.<br>";
      }
      else {
        $authfile=json_decode(file_get_contents("../stor/auth.db"));
        $auser=$authfile[$_SESSION['uid']];
        $auser->password=password_hash($_POST['new-password'], PASSWORD_DEFAULT);
        file_put_contents("../stor/auth.db", json_encode($authfile));
        $successes.="Password successfully reset.<br>";
        elog("settings", $logUser . " reset their password");
      }
    }

    $users[$_SESSION['uid']]=$userprofile;
    file_put_contents("../stor/users.db", json_encode($users));

    $successes.="All fields with valid settings were saved.";

    elog("settings", $logUser . " updated their settings");
  }

  @pheader("my settings");

?>
  <h1>My settings</h1>

  <?php if(@isset($successes) && $successes != "" ){ ?><div class="alert alert-success"><?php echo $successes; ?></div><?php } ?>
  <?php if(@isset($errors) && $errors != ""){ ?><div class="alert alert-danger"><?php echo $errors; ?></div><?php } ?>

  <form action="" method="POST">

    <input type="hidden" name="settingschange" value="true"></input>

    Your user ID: <input type="text" disabled value=<?php echo $_SESSION['uid']; ?> size=3></input>
    <div class="tip">Tip! Use this when communicating with support. User IDs cannot be changed.</div>

    <?php if( EETI_CONFIG_COMMUNITYENABLED ) { ?>
    <h2>Community settings</h2>
    Name: <input type="text" value="<?php echo $userprofile->realname ?>" name="myname"></input>
    <div class="tip">Tip! This is the name that shows on your <a href="user.php?u=<?php echo strval($_SESSION['uid']); ?>">user page</a>. Set to nothing to hide/reset.</div>
    <br>
    Biography:<br>
    <textarea name="mydesc" cols=30 rows=5><?php echo $userprofile->bio ?></textarea><br>
    <div class="tip">Tip! This is the bio that shows up on your <a href="user.php?u=<?php echo strval($_SESSION['uid']); ?>">user page</a>. It is parsed with <a href="http://www.markdowntutorial.com/">Markdown</a> and has a limit of 512 characters. Images must be uploaded through <?php echo EETI_CONFIG_TITLE; ?> to show up. Set to nothing to hide/reset.</div>
    <br>
    <?php if( EETI_CONFIG_UPLOADINGENABLED ){ ?>
    Avatar: <input type="text" value="<?php echo $userprofile->avatar; ?>" name="myavatar"></input>
    <div class="tip">Tip! Enter the filename of a file on <?php echo EETI_CONFIG_FILEURLBASE; ?> to set it to your avatar on your <a href="user.php?u=<?php echo strval($_SESSION['uid']); ?>">user page</a> (e.g. for a file uploaded at <?php echo EETI_CONFIG_FILEURLBASE; ?>myfile.png, type "myfile.png" here). Set to nothing to hide/reset.</div>
    <br>
    <?php } ?>
    Web site: <input type="text" value="<?php echo $userprofile->homepage; ?>" name="mysite"></input>
    <div class="tip">Tip! Enter a Web page here to show on your <a href="user.php?u=<?php echo strval($_SESSION['uid']); ?>">user page</a>. Set to nothing to hide/reset.</div>

    <?php
    }
    ?>

    <h2>Security</h2>
    Old password: <input type="password" name="old-password"></input><br>
    New password: <input type="password" name="new-password"></input><br>
    Confirm password: <input type="password" name="confirm-password"></input>
    <div class="tip">Tip! Use these fields if you would like to set a new password. Otherwise, leave them blank. All 3 fields are required in order to reset your password.</div>

    <input type="submit" value="Save"></input>
  </form>
<?php
  pfooter();
?>
