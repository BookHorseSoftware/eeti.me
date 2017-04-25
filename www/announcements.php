<?php

  include("login.php");

  @pheader("announcements");

  $cms=json_decode(file_get_contents("../stor/cms.db"));

  ?>

  <h2>Site announcements</h2>

  <?php

  if( count($cms) > 0 ){

    $visible=false;
    foreach($cms as $v){
      if( $v->visible ){
        $visible=true;
        break;
      }
    }

    if( $visible ){
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
    }
    else echo "No announcements to show.";
  }
  else echo "No announcements to show.";

  @pfooter();

?>
