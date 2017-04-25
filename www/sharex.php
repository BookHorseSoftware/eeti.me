<?php
  require_once("login.php");

  @pheader("sharex integration");

  $parentdir = explode("/", $_SERVER['REQUEST_URI']);
  unset($parentdir[count($parentdir)-1]);
  $parentdir = implode("/", $parentdir);

?>
<h1>ShareX integration</h1>
Import the following code into ShareX by copying the below and then opening the
main window (of ShareX), clicking on the Destinations dropdown -> Destinations
-> Destination settings... -> Custom uploaders -> Import from clipboard

<pre>
{
  "Name": "<?php echo EETI_CONFIG_TITLE ?>",
  "RequestType": "POST",
  "RequestURL": "<?php echo((@$_SERVER['HTTPS'] == 'on') ? "https" : "http"); ?>://<?php echo $_SERVER['HTTP_HOST']; ?><?php echo $parentdir; ?>/upload.php?uploaded",
  "FileFormName": "file",
  "Arguments": {
    "user": "<?php echo $_SESSION['user'] ?>",
    "pass": "PUT YOUR PASSWORD HERE"
  },
  "ResponseType": "Text",
  "RegexList": [
    "(.+)"
  ],
  "URL": "$1$"
}
</pre>

Change your password appropriately in ShareX to reflect your current
<i><?php echo EETI_CONFIG_TITLE; ?></i> password.<br><br>

<b>Please note that <?php echo EETI_CONFIG_TITLE; ?> has no affiliation with
  ShareX or similar software, and as such, their ability to provide support for
  this integration is probably very limited, and vice-versa.</b>
<?php
  pfooter();
?>
