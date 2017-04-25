<?php

  require("login.php");

  if( ! EETI_CONFIG_PASTEENABLED ){
    @pheader("pasting disabled");
    ?>
    <h1>Pasting disabled</h1>
    Pasting is disabled by your administrator.
    <?php
    pfooter();
    die();
  }

  if( @isset($_GET['pasted']) ){
    $paste=$_POST['paste'];

    if( $paste == "" ){
      elog("paste", $logUser . " tried to upload an empty paste");
      header("HTTP/1.1 400 Bad Request");
      die("Pastes must be at least 1 character in length.");
    }

    $pasteid=substr(md5($_POST['paste']), 0, 7);

    if( file_exists("../stor/files/" . $pasteid . ".txt") ){
      elog("paste", $logUser . " tried to upload a paste that already exists");
      header("HTTP/1.1 400 Bad Request");
      die("This paste already exists as " . EETI_CONFIG_FILEURLBASE . $pasteid . ".txt");
    }

    if( file_put_contents("../stor/files/" . $pasteid . ".txt", $_POST['paste']) ){
      elog("paste", $logUser . " pasted a file '" . $pasteid . "'");
      $files=json_decode(file_get_contents("../stor/files.db"));
      array_push($files[$_SESSION['uid']], $pasteid . ".txt");
      file_put_contents("../stor/files.db", json_encode($files));
      header("HTTP/1.1 200 OK");
      die(EETI_CONFIG_FILEURLBASE . $pasteid . ".txt");
    }
    else {
      elog("paste", $logUser . " tried to paste a file but the server hit some kind of error");
      header("HTTP/1.1 500 Internal Server Error");
      die("Internal server error");
    }

  }

  @pheader("paste");
?>
  <h1>Paste</h1>
  <span id="messages"></span><br>
  <textarea id="paste" style="width: 100%; height: 33%"></textarea><br>
  <input type="button" value="Paste" id="submit"></input>

  <script type="text/javascript">
    window.addEventListener("load", function(){
      document.getElementById("submit").addEventListener("click", function(){
        $("#paste").attr("disabled", "");
        var val=$("#paste").val();
        $("#paste").val("Submitting...");
        $.ajax({
          method: "POST",
          url: "?pasted",
          data: { paste: val }
        }).success(function(d){
          $("#messages").html("<font color='blue'>Your paste is available at <a href='" + d + "'>" + d + "</a>");
        }).error(function(e){
          var error = e.responseText || e.statusText;
          $("#messages").html("<font color='red'>" + error + "</font>");
        }).always(function(){
          $("#paste").val("");
          $("#paste").removeAttr("disabled");
        });
      });
    });
  </script>
<?php
  pfooter();
