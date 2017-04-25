<?php

  define("GUEST_ACCESS_ALLOWED", true);
  require_once("login.php");

  @pheader("rules");
  ?>
  <h1><?php echo EETI_CONFIG_TITLE; ?> rules</h1>
  <h3>We don't really like rules, so we'll make this short.</h3>

  <ol>
    <li><b>Don't be dumb.</b> You were accepted here for a reason and are seen as mentally competent.
      <?php echo EETI_CONFIG_TITLE; ?> is a privilege, not a right.</li>
    <li><b>Don't be a prick.</b> Don't constantly upload 10MB files, don't DDoS the site, don't hurt
      other people, and don't distribute viruses. We're <i>very</i> fair.</li>
    <li><b>Don't do anything that will get me sued or blacklisted.</b> Seriously, don't. I will find
      you.</li>
    <li><b>Don't share your login details.</b> Pretty self-explanatory. You are resposible for what
      happens under your account. No "my dog got me banned" will occur here.</li>
    <li><b>You are allowed to appeal administrative decisions exactly one time.</b> One appeal per
      decision. That's all you get, so make it good. Any further "appeals" will result in a worse
      punishment.</li>
    <li><b>We reserve the right to modify/amend to these rules at any time.</b> You still agree to
      abide by them.</li>
  </ol>

  <h3>That's it. Have fun!</h3>
  <?php
  pfooter();
?>
