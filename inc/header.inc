<?php

  header("X-Generated-By: " . getVersion());

  @session_start();

  function getVersion(){
    return "eeti2 " . trim(file_get_contents("../stor/VERSION.ver")) . " (pre-" . substr(file_get_contents("../.git/refs/heads/master"), 0, 5) . ")";
  }

  function pheader($page_title, $extraCode){
?>
<html>
  <head>
    <!-- Boring stuff -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- We're mobile friendly! -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- eeti.me CSS -->
    <link rel="stylesheet" href="./styles/style.css">

    <!-- jQuery -->
    <script src="./assets/jq.js"></script>
    <script src="./assets/jqui.js"></script>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="./assets/bootstrap.css">
    <script src="./assets/bootstrap.js"></script>

    <!-- eeti.me JS -->
    <script src="assets/eeti.js" type="text/javascript"></script>

    <!-- Any extra page-specific code -->
<?php if( @isset($extraCode)){ echo $extraCode; } ?>

    <title>
      <?php if( ! EETI_INSTALLED ) echo "eeti2"; else echo EETI_CONFIG_TITLE; if( @isset($page_title) ) echo " - " . $page_title; ?>
    </title>
  </head>
  <body>

    <!-- Navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <!-- Navigation collapse button, because we're mobile friendly! Wahoo. -->
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>

          <!-- Logo -->
          <a class="navbar-brand e-logo" href="./index.php">
            <img src="<?php if( EETI_INSTALLED ) echo EETI_CONFIG_LOGOURL; else echo "./assets/logo.png"; ?>" height=25 alt="Logo"></img>
          </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <?php if( EETI_INSTALLED ){ ?>
          <ul class="nav navbar-nav">
            <li><a href="./index.php">Home</a></li>
            <?php if( @isset($_SESSION['user']) && ! accountHasFlag($_SESSION['uid'], "D") && ! accountHasFlag($_SESSION['uid'], "L") ){ ?>
            <?php if( EETI_CONFIG_UPLOADINGENABLED || EETI_CONFIG_PASTEENABLED ){ ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Upload <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <?php if(EETI_CONFIG_UPLOADINGENABLED){?><li><a href="./upload.php">Files</a></li><?php } ?>
                <?php if(EETI_CONFIG_PASTEENABLED){?><li><a href="./paste.php">Paste</a></li><?php } ?>
                <?php if(EETI_CONFIG_UPLOADINGENABLED){?><li><a href="./sharex.php">Using ShareX</a><?php } ?>
              </ul>
            </li>
            <?php
            }

            if(EETI_CONFIG_COMMUNITYENABLED){?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Community <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="./users.php">Community</a></li>
                <li><a href="./rules.php">Rules</a></li>
              </ul>
            </li>
            <?php } else { ?><li><a href="./rules.php">Rules</a></li><?php } ?>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><b><?php echo $_SESSION['user'] ?></b> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <?php if(EETI_CONFIG_COMMUNITYENABLED){?><li><a href="./user.php?u=<?php echo strval($_SESSION['uid']) ?>">Profile</a></li><?php } ?>
                <?php if(EETI_CONFIG_UPLOADINGENABLED){?><li><a href="./viewfiles.php">Uploaded files</a></li><?php } ?>
                <li><a href="./settings.php">Settings</a></li>
                <li><a href="?logout">Log out</a></li>
              </ul>
            </li>
            <?php if( accountHasFlag($_SESSION['uid'], "A") || accountHasFlag($_SESSION['uid'], "M") ){ ?><li><a href="acp.php" style='color: red'>ACP</a></li><?php } ?>
            <?php
            } ?>
          </ul>
          <?php } ?>
        </div>
      </div>
    </nav>

    <!-- Page content -->
    <div class="container e-container">
<?php
  }
?>
