<?php

  header("HTTP/1.1 404 Not Found");

  define("GUEST_ACCESS_ALLOWED", true);

  require("login.php");

  @pheader("404 not found");
?>
  <h1>This is an error page, 404 not found.</h1>
  Maybe you should stop trying to find things that don't exist.<br><br>
  <a href="./">Here's a step in the right direction.</a>
<?php
  pfooter();
?>
