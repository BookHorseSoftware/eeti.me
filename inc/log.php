<?php

  function elog($module, $str){

    $datefile = date("Y-m-d");
    $datequal = date("Y-m-d h:i:s");

    file_put_contents("../stor/" . $datefile . ".log", "[" . $datequal . "] [" . $module . "] " . $str . "\n", FILE_APPEND);

  }

  function updateLogUser($s){
    if( ! @isset($s['user']) || empty($s['user']) ) $logUser="Anonymous";
    else $logUser=$s['user'] . " (uid " . $s['uid'] . ")";
    $logUser.=" (" . $_SERVER['REMOTE_ADDR'] . ")";
    return $logUser;
  }

?>
