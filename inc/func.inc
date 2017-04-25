<?php
  require_once("header.inc");
  require_once("footer.inc");

  function toBool($var){
    return $var=="true";
  }

  function authenticate($username, $password){
    $f=json_decode(file_get_contents("../stor/auth.db"));
    $uid=EETI_AUTHENTICATION_FAILED;
    foreach($f as $k=>$v){
      if( $v->username == $username && password_verify($password, $v->password) ){
        $uid=$k;
        break;
      }
    }

    if( $uid == EETI_AUTHENTICATION_FAILED ) elog("auth", $_SERVER['REMOTE_ADDR'] . " failed to authenticate: bad username or password");
    else elog("auth", $_SERVER['REMOTE_ADDR'] . " authenticated successfully as as $username " . "(uid: " . $uid . ")");
    return $uid;
  }

  function setProperty($fn, $uid, $prop, $value){
    if( ! file_exists("../stor/" . $fn . ".db") ){
      throw new Exception("Cannot access file: " . $fn);
    }

    $f=json_decode(file_get_contents("../stor/" . $fn . ".db"));
    $f[$uid]->$prop=$value;
    return file_put_contents("../stor/" . $fn . ".db", json_encode($f));
  }

  function getProperty($fn, $uid, $prop){
    if( ! file_exists("../stor/" . $fn . ".db") ){
      throw new Exception("Cannot access file: " . $fn);
    }

    $f=json_decode(file_get_contents("../stor/" . $fn . ".db"));
    if( ! @isset($f[$uid]->$prop) ) return "";
    else return $f[$uid]->$prop;
  }

  function accountHasFlag($uid, $f){

    $m = getProperty("auth", $uid, "flags");
    if( $m == "" ) return false;

    $has=false;
    foreach(str_split($m) as $v){
      if( $v == $f ) $has=true;
    }

    return $has;
  }

  function userExists($identifier){
    $m = json_decode(file_get_contents("../stor/auth.db"));
    if( is_string($identifier) ){
      $has=false;
      foreach($m as $v){
        if( $v->username == $identifier ) $has=true;
      }
      return $has;
    }
    else {
      return @isset($m[$identifier]);
    }
  }

  function addUser($name, $password, $email, $flags){
    if( userExists($name) ) return EETI_USERNAME_ALREADY_EXISTS;

    $f=json_decode(file_get_contents("../stor/auth.db"));
    $usr=array();
    $usr["username"]=$name;
    $usr["password"]=password_hash($password, PASSWORD_DEFAULT);
    $usr["email"]=$email;
    $usr["flags"]=$flags;
    $nuid=array_push($f, $usr)-1;
    file_put_contents("../stor/auth.db", json_encode($f));

    $f=json_decode(file_get_contents("../stor/users.db"));
    $usr=array();
    $usr["username"]=$name;
    array_push($f, $usr);
    file_put_contents("../stor/users.db", json_encode($f));

    return $nuid;
  }
?>
