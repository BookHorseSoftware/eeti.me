<?php

  function initUnknowns($userprofile){
    if( ! @isset($userprofile->realname) ) $userprofile->realname="";
    if( ! @isset($userprofile->bio) ) $userprofile->bio="";
    if( ! @isset($userprofile->homepage) ) $userprofile->homepage="";
    if( ! @isset($userprofile->avatar) ) $userprofile->avatar="";
    return $userprofile;
  }

?>
