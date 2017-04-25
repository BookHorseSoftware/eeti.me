<?php
if( ! file_exists("../stor/config.db") ) define("EETI_INSTALLED", false);
else {
  $cfg=json_decode(file_get_contents("../stor/config.db"));

  if( ! @isset($cfg->version) || EETI_VERSION != $cfg->version ){
    define("EETI_INSTALLED", false);
    define("EETI_UPGRADING", true);
  }
  else define("EETI_INSTALLED", true);

  foreach((array)$cfg as $k=>$v){
    define("EETI_CONFIG_" . strtoupper($k), $v);
  }

}

require_once("func.php");

function dbInitializer(){
  if( ! file_exists("../stor/auth.db") ) file_put_contents("../stor/auth.db", "[]");
  if( ! file_exists("../stor/invites.db") ) file_put_contents("../stor/invites.db", "[]");
  if( ! file_exists("../stor/users.db") ) file_put_contents("../stor/users.db", "[]");
  if( ! file_exists("../stor/cms.db") ) file_put_contents("../stor/cms.db", "[]");

  if( ! EETI_INSTALLED || (@isset($_GET['settings']) && @isset($_SESSION['uid']) && accountHasFlag($_SESSION['uid'], "A")) ){
    require("setup.php");
  }
}

?>
