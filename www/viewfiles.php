<?php
  require_once("login.php");

  $user=$_SESSION['uid'];

  $isAdmin = accountHasFlag($_SESSION['uid'], "A") || accountHasFlag($_SESSION['uid'], "M");

  if( @isset($_GET['u']) && $isAdmin ) $user=intval($_GET['u']);

  $files=json_decode(file_get_contents("../stor/files.db"));

  if( @isset($_GET['delete']) ){
    $found=false;
    foreach( $files[$user] as $k => $v ){
      if( $v == $_GET['delete'] ){
        $found=true;
        array_splice($files[$user], $k, 1);
        unlink("../stor/files/" . $v);
        file_put_contents("../stor/files.db", json_encode($files));
        break;
      }
    }

    if( ! $found ){
      http_response_code(400);
      die("That file doesn't exist, or you don't own that.");
    } else {
      http_response_code(200);
      die("Deleted " . $v . " successfully.");
    }
  }

  @pheader("uploaded files");

?>

<h1>Uploaded files</h1>

<?php

  $startat = 0;
  if( @isset($_GET['p']) ) $startat=intval($_GET['p']);

  $retHTML = "";

  if( ! @isset($files[$user]) ) $retHTML = "The user you specified doesn't exist.";
  else if( count($files[$user]) == 0 ){
    if( $isAdmin && $user != $_SESSION['uid'] ) $retHTML = "This user has uploaded no files.";
    else $retHTML = "You have uploaded no files.";
  }
  else {
    $no = $startat*20;

    $retHTML="<table border='1' style='width: 100%;'><tbody><tr><th>File preview</th><th>File name</th><th>Operations</th></tr>";

    $currNo = 0;

    require("../inc/mimereader.inc");

    for( $currNo = $no; $currNo < $no+20; $currNo++ ){
      if( $currNo < count($files[$user]) ){
        $mr = new MimeReader("../stor/files/" . $files[$user][$currNo]);
        $mt = $mr->getType();
        $preview = "";
        if( $mt == "image/png" || $mt == "image/gif" || $mt == "image/jpeg" ) $preview="<img src='" . EETI_CONFIG_FILEURLBASE . $files[$user][$currNo] . "' alt='" . $files[$user][$currNo] . "' style='max-height: 100px; max-width: 50%;'></img>";
        $retHTML.="<tr style='height: 75px; align: center; text-align: center;'><td style='100%; text-align: center;'>" . $preview . "</td><td><a href='" . EETI_CONFIG_FILEURLBASE . $files[$user][$currNo] . "'>" . $files[$user][$currNo] . "</a>" . "</td><td><a href='#' onClick='deleteFile(this, \"" . $files[$user][$currNo] . "\", " . $user . ");'>Delete</a></td></tr>";
      }
    }

    $retHTML.="</table></tbody>";

    $uURL="";
    if( @isset($_GET['u']) && $isAdmin ) $uURL="u=" . $user . "&";

    $retHTML.="<span style='float: left;'>";
    if( @intval($_GET['p']) > 0 ) $retHTML.="<a href='?" . $uURL . "p=0' title='First page'>&lt;&lt;</a> ";
    if( $currNo > 20 ) $retHTML.="<a href='?" . $uURL . "p=" . intval($_GET['p']-1) . "' title='Previous page'>&lt;</a>";
    $retHTML.="</span>";
    $retHTML.="<div style='text-align: center;'><b>Showing " . $no . "-" . $currNo . " of " . count($files[$user]) . " files</b></span>";
    $retHTML.="<span style='float: right;'>";
    if( $currNo < count($files[$user]) ){
      $retHTML.="<a href='?". $uURL . "p=" . (@intval($_GET['p'])+1) . "' title='Next page'>&gt;</a> ";
      $retHTML.="<a href='?" . $uURL. "p=" . intval(count($files[$user])/20) . "' title='Last page'>&gt;&gt;</a>";
    }
    $retHTML.="</span>";
  }

  echo $retHTML;

  pfooter();

?>
