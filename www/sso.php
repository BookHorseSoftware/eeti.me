<?php

  require("login.php");

  if( ! EETI_CONFIG_SSOENABLED ) return header("Location: index.php");

  if( ! @isset($_GET['from']) ) return header("Location: index.php");
  $url = parse_url($_GET['from']);

  if( empty($url["scheme"]) || empty($url["host"]) || empty($url["path"]) ) return header("Location: index.php");

  $hosts = explode(",", EETI_CONFIG_SSOHOSTS);
  if( ! in_array($url["host"], $hosts) ) return header("Location: index.php");
  if( $path == "/favicon.ico" ) return header("Location: index.php");

  $target = $url["scheme"] . "://" . $url["host"] . $url["path"];

  $sig = hash("sha256", $_SESSION['user'] . $_SESSION['uid'], EETI_CONFIG_SSOKEY);

  header("Location: " . $target . "?name=" . $_SESSION['user'] . "&uid=" . $_SESSION['uid'] . "&sig=" . bin2hex($sig));
?>
