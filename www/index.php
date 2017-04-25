<?php

  define("GUEST_ACCESS_ALLOWED", true);

  require_once("login.php");

  if(! @isset($_SESSION['user']) ){
    @pheader(EETI_CONFIG_TAGLINE);
    ?>
    <?php if(@isset($_GET['loggedout'])){ ?><div class="alert alert-success" style="text-align: center;">You've logged out. Thanks for visiting!</div> <?php } ?>

    <h1 style="text-align: center;">
      <img src="<?php echo EETI_CONFIG_LOGOURL; ?>" alt="<?php echo EETI_CONFIG_TITLE; ?>" width="100%" style="max-width: 500px;"></img><br>
      <?php echo EETI_CONFIG_TAGLINE; ?><br><br>
      <a class="btn btn-primary btn-lg" href="./login.php">Log in</a><?php if(EETI_CONFIG_REQUESTSENABLED){ ?> or <a class="btn btn-primary btn-lg" href="./join.php" role="button">request access</a><?php } ?>
    </h1>
    <?php
    pfooter();
  }
  else header("Location: ./home.php");

?>
