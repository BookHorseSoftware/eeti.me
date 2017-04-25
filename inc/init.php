<?php

  //if( empty($_SERVER['HTTPS']) ) header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);

  define("EETI_AUTHENTICATION_FAILED", -1);
  define("EETI_USERNAME_ALREADY_EXISTS", -2);

  define("EETI_INVITE_DELETED", 1);
  define("EETI_INVITE_ACCEPTED", 2);
  define("EETI_INVITE_PENDING", 0);

  define("EETI_VERSION", "2.11.0");

  ini_set("session.use_strict_mode", 1);

  require_once("log.php");
  session_start();

  // Regerate session IDs every 5 minutes
  // https://paragonie.com/blog/2015/04/fast-track-safe-and-secure-php-sessions
  if (!isset($_SESSION['canary'])) {
    session_regenerate_id(true);
    $_SESSION['canary'] = time();
  }
  if ($_SESSION['canary'] < time() - 300) {
    session_regenerate_id(true);
    $_SESSION['canary'] = time();
  }

  $logUser=updateLogUser($_SESSION);
  elog("eeti2", "Pageview from " . $logUser . ": " . $_SERVER['REQUEST_URI']);

  // Don't even load the page if you're dumb
  if( file_exists("../stor/ipbl.db") ){
    $ipbl = json_decode(file_get_contents("../stor/ipbl.db"));
    $blocked=false;
    foreach( $ipbl as $v ){
      if( $v == $_SERVER['REMOTE_ADDR'] ) $blocked=true;
    }

    if( $blocked ){
      header("HTTP/1.1 400 Bad Request");
      elog("denied", $logUser . " tried to access a page but was denied due to IP blocking.");
      die();
    }
  }

  require("config.php");

  dbInitializer();
?>
