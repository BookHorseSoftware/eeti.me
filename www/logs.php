<?php

  require_once("login.php");

  if(! accountHasFlag($_SESSION['uid'], "A") ){
    elog("denied", $logUser . " tried to access the logs page but was denied");
    @pheader("access denied");
    ?>
    <h1>Access denied</h1>
    Hmph! It seems as though you can't do that. You should be able to <a href="index.php">return to the home page just fine</a>, though.
    <?php
    pfooter();
    die();
  }

  @pheader("logs");

?>
  <h1>View logs</h1>

<?php

  $logs=glob("../stor/*.log");

  if( @isset($_GET['logid']) ){

    ?>

      <script type="text/javascript">

        var oldpre = "";
        window.addEventListener('load', function(){
          oldpre = document.getElementById("logs").innerHTML;
        });

        function view(m, key){
          document.getElementById("reset").setAttribute("style", "display: inline;");
          m.setAttribute("style", "border: 1px solid red;");
          // (^\[.*\] \[KEY\] .*)
          var re = document.getElementById("logs").innerHTML.match(new RegExp("(^\\[.*\\] \\[" + key + "\\] .*)", 'gm'));
          if( re == null ) document.getElementById("logs").innerHTML="No events with that filter found.";
          else document.getElementById("logs").innerHTML=re.join("\n");
        }

        function reset(m){
          m.setAttribute("style", "display: none;");
          Array.prototype.slice.call(document.getElementsByClassName("filter")).forEach(function(v,k){
            v.setAttribute("style", "border: none;");
          });
          document.getElementById("logs").innerHTML=oldpre;
        }
      </script>

      <b>Filter: </b>
      <a href="#" onClick="view(this, 'eeti2')" class="filter" title="Pageviews"><img src="./assets/icon_pageview.png" alt="Pageviews"></img></a>
      <a href="#" onClick="view(this, 'auth')" class="filter" title="Authentication"><img src="./assets/icon_auth.png" alt="Authentication"></img></a>
      <a href="#" onClick="view(this, 'acp')" class="filter" title="ACP"><img src="./assets/icon_acp.png" alt="ACP"></img></a>
      <a href="#" onClick="view(this, 'paste')" class="filter" title="Paste"><img src="./assets/icon_paste.png" alt="Paste"></img></a>
      <a href="#" onClick="view(this, 'upload')" class="filter" title="Upload"><img src="./assets/icon_upload.png" alt="Upload"></img></a>
      <a href="#" onClick="view(this, 'settings')" class="filter" title="User Profile Change"><img src="./assets/icon_usersettings.png" alt="User Profile Change"></img></a>
      <a href="#" onClick="view(this, 'invite')" class="filter" title="Join Requests"><img src="./assets/icon_invite.png" alt="Join Requests"></img></a>
      <a href="#" onClick="view(this, 'denied')" class="filter" title="Access Violations"><img src="./assets/icon_denied.png" alt="Access Violation"></img></a>
      <a id="reset" style="display: none;" href="#" onClick="reset(this);">Reset</a>
    <?php

    echo "<pre id='logs'>";

    if( @isset($logs[$_GET['logid']]) ){
      echo file_get_contents($logs[$_GET['logid']]);
    }
    else {
      echo "No log with that ID exists.";
    }

    echo "</pre>";

    echo "<a href='?'>View all logs</a>";
  }

  else {
    $has=false;

    foreach($logs as $k=>$v){
      echo "<a href='?logid=" . $k . "'>" . $v . "</a><br>";
      $has=true;
    }
    if( ! $has ){
      echo "There are no logs.";
    }

  }
  @pfooter();
