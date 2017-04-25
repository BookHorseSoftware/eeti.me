<?php

  require_once("../inc/init.inc");

  function session_fully_destroy(){
    // Invalidate the cookie just in case session.use_strict_mode is still set to 0 for some reason
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time()-42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    session_destroy();
  }

  if( @isset($_GET['logout']) ){
    session_start();
    session_fully_destroy();
    header("Location: index.php?loggedout");
  }

  if ( basename(__FILE__) != basename($_SERVER["SCRIPT_FILENAME"]) ) {
    $notme=true;
    if( defined("GUEST_ACCESS_ALLOWED") && GUEST_ACCESS_ALLOWED === true ) $nologinok=true;
    else $nologinok=false;
  }
  else {
    $notme=false;
    $nologinok=false;
  }

  @session_start();

  if( ! @isset($_SESSION['user']) && ! $nologinok ){

    $loggedin=false;

    if( @isset($_POST['user']) && @isset($_POST['pass']) ){
      $res=authenticate($_POST['user'], $_POST['pass']);

      if( $res > -1 ){
        $_SESSION['user']=$_POST['user'];
        $_SESSION['uid']=$res;
        $_SESSION['ip']=$_SERVER["REMOTE_ADDR"];
        $loggedin=true;
        $logUser=updateLogUser($_SESSION);
      }
      else $error="Invalid username or password.";
    }

    if( ! $loggedin ){
      @pheader("log in");
      ?>
      <div class="login">
        <h1>Log in to use <?php echo EETI_CONFIG_TITLE; ?>.</h1>
        <?php if($notme){ elog("denied", $logUser . " tried to access a protected page as an anonymous user, but was denied"); ?><div class="alert alert-danger" role="alert"><span class="sr-only">Error:</span> You have to log in to do that.</div><?php } ?>
        <?php if(@isset($error)){ ?><div class="alert alert-danger" role="alert"><span class="sr-only">Error:</span> <?php echo $error; ?></div><?php } ?>

        <?php if(EETI_CONFIG_REQUESTSENABLED ){ ?> Don't have an account? <a href="./join.php">Click here to request to join.</a> It's free! <?php } ?>
        <form action="" method="POST">
          <table align="center">
            <tr>
              <td>Username&nbsp;</td>
              <td><input type="text" name="user"></input></td>
            </tr>
            <tr>
              <td>Password</td>
              <td><input type="password" name="pass"></input></td>
            </tr>
          </table>
          <input type="submit" value="Log in"></input>
        </form>
      </div>
      <?php
      @pfooter();
      die();
    }
  }

  if( @isset($_SESSION['uid']) && accountHasFlag($_SESSION['uid'], "D") ){

    elog("denied", $logUser . " tried to access a protected page as a suspended user, but was denied");

    @pheader("account disabled");
    ?>
    <h1>Your account has been disabled.</h1>
    Please contact the staff for more information.
    <?php
    pfooter();
    die();
  }

  if( @isset($_SESSION['uid']) && accountHasFlag($_SESSION['uid'], "L")){
    elog("denied", $logUser . " tried to access a protected page as a limited user, but was denied");
    @pheader("account limited");
    ?>
    <h1>Your account is currently limited.</h1>
    We'll let you know when your invite has been accepted!
    <?php
    pfooter();
    die();
  }

  if( ! $notme ) header("Location: ./home.php");
?>
