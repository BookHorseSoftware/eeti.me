<?php

  define("GUEST_ACCESS_ALLOWED", true);

  require_once("login.php");

  if( @$_SESSION['user'] ) die(header("Location: ./home.php"));

  if( ! EETI_CONFIG_REQUESTSENABLED ){
    @pheader("requests disabled");
    ?>
    <h1>Join requests disabled</h1>
    Join requests have been disabled by your administrator.
    <?php
    pfooter();
    die();
  }

  $errors="";

  if( @isset($_POST['inviterequested']) && $_POST['username'] != "" && $_POST['password'] != "" && $_POST['email'] != "" && $_POST['why'] != "" && $_POST['found'] != "" ){

    $usr=strip_tags(trim(preg_replace('/\t+/', '', $_POST['username'])));

    $ok=true;

    if( userExists($usr) ){
      $errors.="That username is already taken. Please pick another one.";
      $ok=false;
    }

    if( ! filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ){
      $errors.="The email address that you supplied is invalid.";
      $ok=false;
    }

    if( $ok ){

      $invs=json_decode(file_get_contents("../stor/invites.db"));

      $uid=addUser($usr, $_POST['password'], $_POST['email'], "L");

      $inv = array();

      $inv["uid"]=$uid;
      $inv["name"]=$usr;
      $inv["email"]=strip_tags($_POST['email']);
      $inv["ip"]=$_SERVER['REMOTE_ADDR'];
      $inv["ua"]=$_SERVER['HTTP_USER_AGENT'];
      $inv["why"]=strip_tags($_POST['why']);
      $inv["found"]=strip_tags($_POST['found']);

      array_push($invs, $inv);

      file_put_contents("../stor/invites.db", json_encode($invs));

      $files=json_decode(file_get_contents("../stor/files.db"));
      array_push($files, array());
      file_put_contents("../stor/files.db", json_encode($files));

      elog("invite", $inv["name"] . " (uid: " . $uid .  ") requested an invite from " . $_SERVER['REMOTE_ADDR']);

      @pheader("invite requested!");

      ?>
      <h1>You've successfully requested access to <?php echo EETI_CONFIG_TITLE; ?>, <?php echo $usr; ?>!</h1>
      We'll email you when you're accepted. While you can log in now, you will not have access to anything. Requesting to join more than once may result in immediate rejection.<br><br>
      Here's to hoping your join request is accepted soon!
      <?php
      pfooter();
      die();
    }
  }
  else if( @isset($_POST['inviterequested']) ){
    $errors.="Oops! You didn't fill out the form all the way.";
  }

  @pheader("request an invite");
?>
  <h1>Request to join <?php echo EETI_CONFIG_TITLE; ?></h1>
  <?php if( $errors != "" ) { ?><div class="alert alert-danger"><?php echo $errors ?></div><?php } ?>
  <form action="" method="POST">
    <input type="hidden" name="inviterequested" value="true">
    Choose a username: <input type="text" name="username"></input><br>
    Choose a password: <input type="password" name="password"></input><br>
    Enter your email address: <input type="text" name="email" <?php if(@isset($_POST['email'])) echo "value='" . $_POST['email'] . "'"; ?>></input>
    <div class="tip">We'll only use this to notify you if you're accepted and for important sitewide announcements.</div>
    Why do you want to use <?php echo EETI_CONFIG_TITLE; ?>?<br>
    <textarea name="why" rows=10 cols=100><?php if(@isset($_POST['why'])) echo $_POST['why']; ?></textarea><br>
    How did you find <?php echo EETI_CONFIG_TITLE; ?>? <input type="text" name="found" <?php if(@isset($_POST['found'])) echo "value='" . $_POST['found'] . "'"; ?>></input><br>
    <input type="submit" value="Submit application"></input>
  </form>
<?php
  pfooter();

?>
