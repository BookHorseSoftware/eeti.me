<?php
  require_once("login.php");

  require_once("../inc/mail.php");

  function validIP($ip){
    return (preg_match('/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/', $ip) == 1) ||
    (preg_match('/^(([0-9a-fA-F]{1,4}:){7,7}[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,7}:|([0-9a-fA-F]{1,4}:){1,6}:[0-9a-fA-F]{1,4}|([0-9a-fA-F]{1,4}:){1,5}(:[0-9a-fA-F]{1,4}){1,2}|([0-9a-fA-F]{1,4}:){1,4}(:[0-9a-fA-F]{1,4}){1,3}|([0-9a-fA-F]{1,4}:){1,3}(:[0-9a-fA-F]{1,4}){1,4}|([0-9a-fA-F]{1,4}:){1,2}(:[0-9a-fA-F]{1,4}){1,5}|[0-9a-fA-F]{1,4}:((:[0-9a-fA-F]{1,4}){1,6})|:((:[0-9a-fA-F]{1,4}){1,7}|:)|fe80:(:[0-9a-fA-F]{0,4}){0,4}%[0-9a-zA-Z]{1,}|::(ffff(:0{1,4}){0,1}:){0,1}((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])|([0-9a-fA-F]{1,4}:){1,4}:((25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9])\.){3,3}(25[0-5]|(2[0-4]|1{0,1}[0-9]){0,1}[0-9]))$/', $ip) == 1);
  }

  if(! accountHasFlag($_SESSION['uid'], "A") && ! accountHasFlag($_SESSION['uid'], "M") ){
    elog("denied", $logUser . " tried to access the ACP but was denied");
    @pheader("access denied");
    ?>
    <h1>Access denied</h1>
    Hmph! It seems as though you can't do that. You should be able to <a href="index.php">return to the home page just fine</a>, though.
    <?php
    pfooter();
    die();
  }

  if( @isset($_GET['phpinfo']) ){
    phpinfo();
    die();
  }

  $successes="";
  $errors="";

  if( accountHasFlag($_SESSION['uid'], "A") && @isset($_POST['flagsAdjusted']) ){
    $users=json_decode(file_get_contents("../stor/auth.db"));

    foreach($_POST['uid'] as $k => $v){
      $users[$k]->flags=$_POST['fix'][$k];
    }

    file_put_contents("../stor/auth.db", json_encode($users));

    elog("acp", $logUser. " adjusted user flags");

    $successes.="Adjusted flags successfully.<br>";

  }

  if( (accountHasFlag($_SESSION['uid'], "M") || accountHasFlag($_SESSION['uid'], "A")) && @isset($_GET['announcementposted']) ){

    if( @isset($_POST['title']) && @isset($_POST['post']) ){
      $cms=json_decode(file_get_contents("../stor/cms.db"));

      $obj = array();
      $obj['title']=$_POST['title'];
      $obj['post']=str_replace("\n", "<br>", $_POST['post']);
      $obj['time']=time();
      $obj['uid']=$_SESSION['uid'];
      $obj['visible']=true;

      array_unshift($cms, $obj);

      file_put_contents("../stor/cms.db", json_encode($cms));

      elog("acp", $logUser . " posted announcement '" . $obj['title'] . "'");

      $successes.="Posted announcement successfully.<br>";
    }
    else {
      $errors.="Announcement missing title/body<br>";
    }

  }

  if( accountHasFlag($_SESSION['uid'], "A") && @isset($_GET['id']) ){
    if( @isset($_GET['deleteinvite']) ){
      $invites=json_decode(file_get_contents("../stor/invites.db"));
      if( ! @isset($invites[$_GET['id']]) ){
        header("HTTP/1.1 400 Bad Request");
        die("user does not exist");
      }

      setProperty("invites", $_GET['id'], "status", EETI_INVITE_DELETED);

      elog("invite", $logUser . " rejected an invite for " . $_GET['id']);

      header("HTTP/1.1 200 OK");
      die("operation completed successfully");

    }
    else if( @isset($_GET['acceptinvite']) ){
      $invites=json_decode(file_get_contents("../stor/invites.db"));
      if( ! @isset($_GET['id']) || ! @isset($invites[intval($_GET['id'])]) ){
        header("HTTP/1.1 400 Bad Request");
        die("user does not exist");
      }

      setProperty("auth", $invites[$_GET['id']]->uid, "flags", "");
      setProperty("invites", $_GET['id'], "status", EETI_INVITE_ACCEPTED);

      elog("invite", $logUser . " accepted a join request for " . $_GET['id']);

      sendMail($invites[$_GET['id']]->email, "Your join request was accepted!", "Hi, " . $invites[$_GET['id']]->name . "!\n\nYour join request to " . EETI_CONFIG_TITLE . " was just accepted! You should have access to the site now, feel free to log in!\n\n--". EETI_CONFIG_TITLE . " Team");

      header("HTTP/1.1 200 OK");
      die("operation completed successfully");
    }
    else if( @isset($_GET['deleteannouncement']) ){
      $cms=json_decode(file_get_contents("../stor/cms.db") );
      if( ! @isset($_GET['id']) || ! @isset($cms[intval($_GET['id'])])){
        header("HTTP/1.1 400 Bad Request");
        die("post does not exist");
      }

      $cms[intval($_GET['id'])]->visible=false;

      file_put_contents("../stor/cms.db", json_encode($cms));

      elog("acp", $logUser . " deleted announcement '" . $cms[intval($_GET['id'])]->title . "'");
      header("HTTP/1.1 200 OK");
      die("operation completed successfully");
    }

  }

  if( accountHasFlag($_SESSION['uid'], "A") && @isset($_POST['updatebl']) ){
    $bl = $_POST['bl'];
    foreach($bl as $k=>$v){
      if( empty($v) ){
        unset($bl[$k]);
        continue;
      }
      if( ! validIP($v) ){
        $errors.="'" . $v . "' is not a valid IP address, ignoring.<br>";
        unset($bl[$k]);
      }
    }

    file_put_contents("../stor/ipbl.db", json_encode($bl));
    elog("acp", $logUser . " updated IP blacklist");
    $successes.="Updated blacklist with items that had valid values.<br>";
  }

  @pheader("admin control panel");
?>

  <script type="text/javascript">
    function togglepanel(lk, panel){
      if( document.getElementById(panel).getAttribute("style") == "display: block;" ){
        lk.innerHTML="Show " + lk.getAttribute("data-panelname") + "...";
        document.getElementById(panel).setAttribute("style", "display: none;");
      }
      else {
        lk.innerHTML="Hide " + lk.getAttribute("data-panelname") + "...";
        document.getElementById(panel).setAttribute("style", "display: block;");
      }
    }
  </script>

  <h1>Admin Control Panel</h1>

  <?php if( $successes != "" ){ ?><div class="alert alert-success"><?php echo $successes; ?></div><?php } ?>
  <?php if( $errors != "" ){ ?><div class="alert alert-danger"><?php echo $errors; ?></div><?php } ?>

  <?php if( accountHasFlag($_SESSION['uid'], "A") ){ ?>
  <h2>General information</h2>
  <?php echo EETI_CONFIG_TITLE; ?> is running <?php echo getVersion(); ?> on <?php echo php_uname('s'); ?> with PHP <?php echo PHP_VERSION; ?>. <a href="#" onClick="togglepanel(this,'phpinfo');" data-panelname="phpinfo()">Show phpinfo()...</a><br>
  <div id="phpinfo" style="display: none;">
    <iframe src="?phpinfo" height="600px" width="100%"></iframe>
  </div>

  <a href="?settings">Adjust general site settings...</a>

  <?php } ?>

  <?php if( accountHasFlag($_SESSION['uid'], "A") ){ ?>
  <hr>
  <h2>Accounts</h2>
    <a href="#flags" onClick="togglepanel(this, 'flags');" data-panelname="accounts panel">Show accounts panel...</a>
    <div id="flags" style="display: none;">
      <a href="#" onClick="togglepanel(this, 'flagsinfo');" data-panelname="account flags help">Show account flags help...</a>
      <div id="flagsinfo" style="display: none;">
        <h3>Flag information</h3>
        Flags control <?php echo EETI_CONFIG_TITLE; ?>'s access permissions on a user or user(s). They are presented all squished together (e.g. "ADLM") and each letter provides different permissions. Currently implemented are:
        <ul>
          <li><b>A</b> Full control.</li>
          <li><b>D</b> Disables account. No permissions. Overrides ALL other flags.</li>
          <li><b>L</b> Limited account (e.g. after requesting an invite). No permissions. Overrides ALL other flags (except D).</li>
          <li><b>M</b> Allowed to disable and enable uploading and accept invites.</li>
        </ul>
      </div>
      <hr>
      <form action="" method="post">
        <input type="hidden" name="flagsAdjusted" value="true"></input>
        <table width="100%">
          <tr>
            <th>UID</th>
            <th>Username</th>
            <th>Flags</th>
          </tr>
        <?php
          $users=json_decode(file_get_contents("../stor/auth.db"));
          foreach($users as $k => $v){
            echo "<tr><input name='uid[]' value='" . strval($k) . "' type='hidden'></input><td>";
            if( EETI_CONFIG_COMMUNITYENABLED ) echo "<a href='user.php?u=" . strval($k) . "'>" . strval($k) . "</a>";
            else echo strval($k);
            echo "</td><td>" . $v->username . "</td><td><input name='fix[]' value='" . $v->flags . "'></input></td></tr>";
          }
        ?>
        </table>
        <input type="submit" value="Submit changes"></input> (this may take a moment...)
      </form>
    </div>
  <hr>
  <?php } ?>
  <?php if( (accountHasFlag($_SESSION["uid"], "M") || accountHasFlag($_SESSION["uid"], "A")) && EETI_CONFIG_REQUESTSENABLED ){ ?>
  <h2>Pending join requests</h2>
    <a href="#invites" onClick="togglepanel(this, 'invites');" data-panelname="pending join requests panel">Show pending join requests panel...</a>
    <div id="invites" style="display: none;">
      <?php
      $invites=json_decode(file_get_contents("../stor/invites.db"));
      if( count($invites > 0 )){
        ?>
          <?php
        $invitesShown=0;
        foreach($invites as $k=>$v){
          if( ! @isset($v->status) ){
            echo "<table width='100%' id='inv-$k'>";
              echo "<tr><th><h3><a href='user.php?u=" . $v->uid . "'>" . $v->name . "</a></h3></th></tr>";
              echo "<tr><th>UID:</th><td>" . $v->uid . "</td></tr>";
              echo "<th>IP:</th><td>" . $v->ip . "</td></tr>";
              echo "<tr><th>User-Agent:</th><td>" . $v->ua . "</td></tr>";
              echo "<tr><th>Why do you want to use " . EETI_CONFIG_TITLE . "?</th><td>" . $v->why . "</td></tr>";
              echo "<tr><th>How did you find " . EETI_CONFIG_TITLE . "?</th><td>" . $v->found . "</td></tr>";
              echo "<tr><th>Actions:</th><td><a href='#' onClick='acceptInvite($k)'>accept</a>, <a href='#' onClick='deleteInvite($k)'>delete</a></td></tr>";
            echo "</table>";
            $invitesShown++;
          }

        }

        if( count($invites) < 1 || $invitesShown == 0 ) echo "<h3>All clean, good job! Go have a snack. ;3</h3>";
        ?>
        <script type="text/javascript">
          function acceptInvite(id){
            $.ajax("?acceptinvite&id=" + id).success(function(d){
              document.getElementById("inv-" + id).innerHTML = "Request accepted: " + d;
              $("#inv-" + id).effect("highlight", {}, 1500);
            }).fail(function(){
              document.getElementById("inv-" + id).innerHTML = "Operation failed";
              $("#inv-" + id).effect("highlight", {}, 1500);
            });
          }

          function deleteInvite(id){
            $.ajax("?deleteinvite&id=" + id).success(function(d){
              var u = document.getElementById("inv-" + id).getElementsByTagName("h3").innerHTML;
              document.getElementById("inv-" + id).innerHTML = "Request deleted: " + d;
              $("#inv-" + id).effect("highlight", {}, 1500);
            }).fail(function(){
              document.getElementById("inv-" + id).innerHTML = "Operation failed: " + d;
              $("#inv-" + id).effect("highlight", {}, 1500);
            });
          }
        </script>
      </div>
      <hr>
        <?php
      }
    }

    if( accountHasFlag($_SESSION["uid"], "M") || accountHasFlag($_SESSION["uid"], "A") ){
      ?>
    <h2>Site announcements</h2>
    <a href="#announcements" onClick="togglepanel(this, 'announcements');" data-panelname="site announcements panel">Show site announcements panel...</a>
    <div id="announcements" style="display: none;">
      <h3>Post a new announcement</h3>
      <form action="?announcementposted" method="POST">
        Title: <input type="text" name="title"></input><br>
        <textarea name="post" style="width: 100%; height: 10%;"></textarea><br>
        <div class="tip"><b>Note:</b> All HTML input is parsed as-is.</div>
        <input type="submit" value="Post"></input>
      </form>

      <h3>Manage previous announcements</h3>
      <?php
        $cms=json_decode(file_get_contents("../stor/cms.db"));
        if(count($cms) == 0 ) echo "No announcements to show.";
        else {
          $users=json_decode(file_get_contents("../stor/users.db"));

          $visible=false;
          foreach($cms as $k=>$v){
            if( $v->visible ){
              $visible=true;
              break;
            }
          }

          if( ! $visible ) echo "No announcements to show.";
          else {
            echo "<table width='100%'><th>Posted by</th><th>Title</th><th>Actions</th>";
            foreach($cms as $k=>$v){
              if( ! $v->visible ) continue;
              echo "<tr id='announcement-" . $k . "'><td><a href='user.php?u=0'>" . $users[$v->uid]->username . "</a></td><td>" . $v->title . "</td><td><a href='#' onClick='deleteAnnouncement(" . $k . ")'>Delete announcement</a></tr>";
            }
            echo "</table>";
          }
        }
      ?>
      <script type="text/javascript">
        function deleteAnnouncement(id){
          $.ajax("?deleteannouncement&id=" + id).success(function(d){
            $("#announcement-" + id).html("Deleted announcement: " + d);
            $("#announcement-" + id).effect("highlight", {}, 1500);
          });
        }
      </script>
    </div>
    <hr>
    <?php
      if( accountHasFlag($_SESSION["uid"], "A") ){
        ?>
        <h2>Logs</h2>
        <a href="logs.php">View logs...</a>
        <?php
      }
    }

    if( accountHasFlag($_SESSION['uid'], "A")){
      ?>
      <hr>
      <h2>IP blacklist</h2>
      <a href="#ipbl" onClick="togglepanel(this, 'ipbl')" data-panelname="IP blacklist panel">Show IP blacklist panel...</a>
      <div id="ipbl" style="display: none;">

        <script type="text/javascript">
          function addEntry(){
            var inp = document.createElement("input");
            inp.setAttribute("type", "text");
            inp.setAttribute("name", "bl[]");
            document.getElementById("bl").appendChild(inp);
            document.getElementById("bl").appendChild(document.createElement("br"));
          }
        </script>

        <form action="" method="post">
          <b>This form accepts IPv4 or IPv6 addresses.</b> To remove an entry, simply delete the text inside it.
          <div id="bl">
            <input type="hidden" name="updatebl" value="true"></input>
            <?php
              $bl=array();
              if( file_exists("../stor/ipbl.db") ){
                $bl=json_decode(file_get_contents("../stor/ipbl.db"));
              }

              if( count($bl) < 1 ){
                echo "<input type='text' name='bl[]'></input><br>\n";
              }
              else {
                foreach($bl as $v){
                  echo "<input type='text' name='bl[]' value='" . $v . "'></input><br>\n";
                }
              }
            ?>
          </div>
          <input type="submit" value="Submit"></input>
        </form>
        <a href="javascript:void(0);" onClick="addEntry();">Add entry...</a>
      </div>
      <?php
    }
  pfooter();
?>
