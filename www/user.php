<?php

  require_once("./login.php");

  if( ! EETI_CONFIG_COMMUNITYENABLED ){
    @pheader("community disabled");
    ?>
    <h1>Community disabled</h1>
    Your administrator disabled community features.
    <?php
    pfooter();
    die();
  }

  require_once("../inc/initunknowns.inc");

  if( ! @isset($_GET['u']) ){
    header("Location: index.php");
    die();
  }

  $f=json_decode(file_get_contents("../stor/users.db"));

  if( @is_nan($_GET['u']) === null || ! @isset($f[intval($_GET['u'])]) || intval($_GET['u']) == count($f)){
    header("HTTP/1.1 404 Not Found");
    @pheader("user not found");
    ?>
    <h1>Oh, dear</h1>
    The user you were looking for wasn't found.
    <?php
    pfooter();
    die();
  }

  $user=initunknowns($f[intval($_GET['u'])]);
  @pheader($user->username . "'s profile");
?>
  <h1><?php if( $user->avatar != "" ) echo "<img src='" . EETI_CONFIG_FILEURLBASE . $user->avatar . "' alt='" . $user->avatar . "' height=100 width=100></img>"; ?> <?php echo $user->username; ?>
    <?php

    if( accountHasFlag(intval($_GET['u']), "A") ){ ?> <span class="label label-success">ADMIN</span> <?php }
    if( accountHasFlag(intval($_GET['u']), "M") ){ ?> <span class="label label-success">MOD</span> <?php }
    if( accountHasFlag(intval($_GET['u']), "L") ){ ?> <span class="label label-info">LIMITED</span> <?php }
    if( accountHasFlag(intval($_GET['u']), "D") ){ ?> <span class="label label-danger">DISABLED</span><?php }

    if( $user->realname != "" ) echo "<span class='realname'>(" . $user->realname . ")</span>"; ?></h1>

  <?php
    if( $user->bio != "" ){
      require_once("../inc/parsedown.inc");
      $p = new Parsedown();
      echo "<div>" . $p->text($user->bio) . "</div>";
    } else echo "<div>No information given.</div>" ?>
  <?php if( $user->homepage != "" ) echo "<a href='" . $user->homepage . "'>" . $user->username . "'s Web site</a>"; ?>
  <br><br>
  <?php
    if( accountHasFlag($_SESSION['uid'], "A") || accountHasFlag($_SESSION['uid'], "M") || intval($_GET['u']) == $_SESSION['uid']){
      $isAdmin=(accountHasFlag($_SESSION['uid'], "A") || accountHasFlag($_SESSION['uid'], "M")) && (intval($_GET['u']) != $_SESSION['uid']);
      if( $isAdmin ){ ?><a href="viewfiles.php?u=<?php echo $_GET['u']; ?>">View this user's uploaded files...</a><?php }
    }
  ?>
<?php
  pfooter();
?>
