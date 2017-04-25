<?php

  require_once("login.php");

  @pheader(EETI_CONFIG_TAGLINE);
  ?>
  <div class="hp">
    <h1>Welcome to <?php echo EETI_CONFIG_TITLE; ?>!</h1>
    (By using <?php echo EETI_CONFIG_TITLE; ?> you agree to adhere to our <a href="./rules.php">simple rules</a>.)

    <?php
      $cms=json_decode(file_get_contents("../stor/cms.db"));

      if( count($cms) > 0 ){

        $visible=false;
        foreach($cms as $v){
          if( $v->visible ){
            $visible=true;
            break;
          }
        }

        if( $visible ){
          echo "<h2>Site announcements</h2>";
          $i=0;
          if( EETI_CONFIG_COMMUNITYENABLED ) $users=json_decode(file_get_contents("../stor/users.db"));
          foreach($cms as $k=>$v){
            if( ! $v->visible ) continue;
            if( $i >= 5 ) break;
            echo "<h3>" . $v->title . "</h3>";
            if( EETI_CONFIG_COMMUNITYENABLED ) echo "<i>Posted by <a href='user.php?u=" . $v->uid . "'>" . $users[$v->uid]->username . "</a></i><br>";
            echo $v->post;
            echo "<hr>";
            $i+=1;
          }
          echo "<a href='announcements.php'>View all announcements...</a>";
        }

      }
    ?>

  </div>
  <?php
  pfooter();
?>
