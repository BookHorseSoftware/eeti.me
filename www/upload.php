<?php

  require_once("login.php");

  error_reporting(0);

  if( ! EETI_CONFIG_UPLOADINGENABLED ){
    pheader("uploading disabled");
    ?>
    <h1>Uploading disabled</h1>
    Uploading has been disabled by your administrator.
    <?php
    pfooter();
    die();
  }

  if( @isset($_FILES['file']) ){
    if( $_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE ){
      elog("upload", $logUser . " tried to upload a file that was too big");
      header("HTTP/1.1 400 Bad Request");
      die("File size too big");
    }

    if($_FILES['file']['error'] == UPLOAD_ERR_CANT_WRITE){
      elog("upload", $logUser . " tried to upload a file, but the server's tmp directory wasn't writable");
      header("HTTP/1.1 500 Internal Server Error");
      die("Internal server error");
    }

    $f=$_FILES['file'];

    // Code stolen from qtpi.club

    if( strpos($f['name'], ".") !== FALSE ){
      $m=explode(".", $f['name']);
      $ext="." . $m[count($m)-1];
    }
    else $ext="";

    $name=substr(md5_file($f['tmp_name']), 0, 7) . strtolower($ext);

    if( file_exists("../stor/files/" . $name) ){
      elog("upload", $logUser . " uploaded duplicate file " . $name);
      header("HTTP/1.1 200 OK");
      die(EETI_CONFIG_FILEURLBASE . $name);
    }

    if( move_uploaded_file($f['tmp_name'], "../stor/files/" . $name) ){
      elog("upload", $logUser . " uploaded a file '" . $name . "'");
      $files=json_decode(file_get_contents("../stor/files.db"));
      array_push($files[$_SESSION['uid']], $name);
      file_put_contents("../stor/files.db", json_encode($files));
      header("HTTP/1.1 200 OK");
      die(EETI_CONFIG_FILEURLBASE . $name);
    }
    else {
      elog("upload", $logUser . " tried to upload a file, but the server's stor directory wasn't writable");
      header("HTTP/1.1 500 Internal Server Error");
      die("Internal server error");
    }
  }
  else if( @isset($_GET['uploaded']) ){
    header("HTTP/1.1 400 Bad Request");
    elog("upload", $logUser . " tried to upload a file, but the server errored out somehow");
    die("Upload failed; file size likely too big");
  }

  @pheader("upload", "<link rel='stylesheet' href='./assets/dropzone.min.css' type='text/css'></link>\n<script type='text/javascript' src='./assets/dropzone.min.js'></script>");
?>
  <h1>Upload</h1>
  You can upload files up to 30MB here so long as they follow our <a href="./rules.php">simple rules</a>.

  <form action="upload.php?uploaded" method="POST" class="dropzone" id="dropzone">

  </form>

  <script type="text/javascript">
    Dropzone.autoDiscover = false;
    Dropzone.paramName = "file";

    window.addEventListener("load", function(){
      var dz = new Dropzone("#dropzone");
      dz.on('error', function(f,e,x){
        console.log("Error! " + e);
      });
      dz.on('success', function(f,r){
        f.previewElement.getElementsByClassName("dz-filename")[0].innerHTML = "<a href='" + r + "'>" + r + "</a>";
      });
    });
  </script>
<?php

  pfooter();

?>
