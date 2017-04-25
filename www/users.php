<?php
  include("login.php");

  if( ! EETI_CONFIG_COMMUNITYENABLED ){
    @pheader("community disabled");
    ?>
    <h1>Community disabled</h1>
    Your administrator disabled community features.
    <?php
    pfooter();
    die();
  }

  @pheader("community");

  $auths=json_decode(file_get_contents("../stor/auth.db"));

  $users=json_decode(file_get_contents("../stor/users.db"));

  ?>
  <h1>Community</h1>

  There are <b><?php echo count($auths); ?></b> users registered on <?php echo EETI_CONFIG_TITLE; ?>.

  <table width="100%" class="usertable">
    <?php
    foreach($auths as $k=>$v){
      if( accountHasFlag($k, "D") || accountHasFlag($k, "L") ) continue;
      echo "<tr valign='center'><td style='width: 30; height: 30;'>";
      if( @isset($users[$k]->avatar) && $users[$k]->avatar != "" ) echo "<img src='" . EETI_CONFIG_FILEURLBASE . $users[$k]->avatar . "' height=24 width=24></img>";
      echo "</td><td><a href='./user.php?u=" . $k . "'>" . $users[$k]->username ."</a>";
      if( accountHasFlag($k, "A") ) echo " <span class='label label-success'>ADMIN</span>";
      if( accountHasFlag($k, "M") ) echo " <span class='label label-success'>MOD</span>";
      echo "</td></tr>";
    }
    ?>
  </table>

  <?php

  pfooter();
?>
