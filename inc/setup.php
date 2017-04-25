<?php
if( file_exists("../stor/config.db") ) $cfg=json_decode(file_get_contents("../stor/config.db"));

if( ! file_exists("../stor/files.db") ) file_put_contents("../stor/files.db", "[[]]");

if( ! @isset($_POST['setup']) ){
  @pheader("setup");
?>
<h1><?php if( ! file_exists("../stor/config.db") ){ ?>Hey! It seems like you're new here.<?php
} elseif( defined("EETI_UPGRADING") ) {
?>Hey! It seems like you're upgrading to a newer version.<?php
} else { ?>Hey! Ready to tweak some settings?<?php
}
?></h1>
<?php if( ! EETI_INSTALLED || @defined(EETI_UPGRADING) ){ ?>While you have a second, why don't we take some time to set up your <?php echo getVersion(); ?> installation?<?php } ?>

<script type="text/javascript">
  window.addEventListener("load", function(){
    document.getElementById("smtpselectorextra").setAttribute("style", "display: none;");
    function ck(){
      if( document.getElementById("smtpselectorno").checked == false ) document.getElementById("smtpselectorextra").setAttribute("style", "display: block;");
      else document.getElementById("smtpselectorextra").setAttribute("style", "display: none;");
    }

    document.getElementById("smtpselector").addEventListener("click", ck);
    ck();
  });
</script>

<form action="" method="POST">
  <h2>General settings</h2>
  <div>
    Your site's title: <input type="text" name="title" value="<?php if(defined('EETI_CONFIG_TITLE')) echo EETI_CONFIG_TITLE; else echo 'eeti.me'; ?>"></input>
    <div class="tip">Tip! This is what your users will see in the site title and in other places. Don't worry, this can be changed later.</div>
  </div>
  <div>
    Your site's tagline: <input type="text" name="tagline" value="<?php if(defined('EETI_CONFIG_TAGLINE')) echo EETI_CONFIG_TAGLINE; else echo 'simple, free, invite-only file hosting'; ?>"></input>
    <div class="tip">Tip! This is the text that will show up on the homepage to new or non-logged-in users.</div>
  </div>
  <div>
    Your site's logo: <input type="text" name="logourl" value="<?php if(defined('EETI_CONFIG_LOGOURL')) echo EETI_CONFIG_LOGOURL; else echo './assets/logo.png'; ?>"></input>
    <div class="tip">Tip! This is a fully-qualified or relative URL to your site's logo.</div>
  </div>
  <div>
    Address to serve uploaded files from: <input type="text" name="fileurlbase" value="<?php if(defined('EETI_CONFIG_FILEURLBASE')) echo EETI_CONFIG_FILEURLBASE; else echo 'http://f.eeti.me/'; ?>"></input>
    <div class="tip">Tip! This is what all of the URLs to files will start with. Include the trailing slash.</div>
  </div>
  <hr>
  <h2>Administrative account settings</h2>
  <?php if( ! userExists(0) ){ ?>
    <div>
      Choose a username to administrate as: <input type="text" name="adminusername" value="root"></input>
      <div class="tip">Tip! You will need to log into this account to administrate the site unless you configure another account to do so.</div>
    </div>
    <br>
    <div>
      Choose a password for the administrator account: <input type="password" name="adminpassword"></input>
      <div class="tip">Tip! Make sure you write this down somewhere; you will not be able to administer the site if you can't remember your password!</div>
    </div>
    <br>
    <div>
      Enter your e-mail address: <input type="text" name="adminemail" value="some@email.com"></input>
      <div class="tip">Tip! This is also the address users will contact if they need help.</div>
    </div>
  <?php
  }
  else echo "[This section has been disabled because an administrative user already exists]";
  ?>
  <hr>
  <h2>Features</h2>
  <div id="features">
    Enable file uploading?
      <input type="radio" name="uploadingenabled" value="true" <?php if( defined("EETI_CONFIG_UPLOADINGENABLED") && EETI_CONFIG_UPLOADINGENABLED ) echo "checked"; ?>>Yes</input>
      <input type="radio" name="uploadingenabled" value="false" <?php if( defined("EETI_CONFIG_UPLOADINGENABLED") && ! EETI_CONFIG_UPLOADINGENABLED ) echo "checked"; ?>>No</input>
      <div class="tip">Tip! This will enable/disable the uploading of files. Avatars will be disabled if uploading is disabled.</div>
    Enable pasting?
      <input type="radio" name="pasteenabled" value="true" <?php if( defined("EETI_CONFIG_PASTEENABLED") && EETI_CONFIG_PASTEENABLED ) echo "checked"; ?>>Yes</input>
      <input type="radio" name="pasteenabled" value="false" <?php if( defined("EETI_CONFIG_PASTEENABLED") && ! EETI_CONFIG_PASTEENABLED ) echo "checked"; ?>>No</input>
      <div class="tip">Tip! This will enable/disable the pastebin.</div>
    Enable community features?
      <input type="radio" name="communityenabled" value="true" <?php if( defined("EETI_CONFIG_COMMUNITYENABLED") && EETI_CONFIG_COMMUNITYENABLED ) echo "checked"; ?>>Yes</input>
      <input type="radio" name="communityenabled" value="false" <?php if( defined("EETI_CONFIG_COMMUNITYENABLED") && ! EETI_CONFIG_COMMUNITYENABLED ) echo "checked"; ?>>No</input>
      <div class="tip">Tip! This will enable/disable community features such as avatars, the global user list, and user profiles.</div>
    Enable join requests?
      <input type="radio" name="requestsenabled" value="true" <?php if( defined("EETI_CONFIG_REQUESTSENABLED") && EETI_CONFIG_REQUESTSENABLED ) echo "checked"; ?>>Yes</input>
      <input type="radio" name="requestsenabled" value="false" <?php if( defined("EETI_CONFIG_REQUESTSENABLED") && ! EETI_CONFIG_REQUESTSENABLED ) echo "checked"; ?>>No</input>
      <div class="tip">Tip! This will allow users to request to join your eeti.me instance. Moderators and administrators can accept/reject invites from the administrative control panel.</div>
  </div>
  <hr>
  <h2>E-mail</h2>
  <div id="smtpselector">
    Enable e-mail?
    <input type="radio" name="smtpenabled" value="true" id="smtpselector" <?php if( defined("EETI_CONFIG_SMTPENABLED") && EETI_CONFIG_SMTPENABLED ) echo "checked"; ?>>Yes</input>
    <input type="radio" name="smtpenabled" id="smtpselectorno" value="false" <?php if( defined("EETI_CONFIG_SMTPENABLED") && ! EETI_CONFIG_SMTPENABLED ) echo "checked"; ?>>No</input>
    <div class="tip">Tip! If you don't set up e-mail, you will have to manually verify and notify accepted users on your own. Please note that eeti2 only supports SMTP right now.</div>
  </div>
  <br>
  <div id="smtpselectorextra" style="display: none;">
    <div>
      SMTP server: <input type="text" name="smtpserver" value="<?php if(defined('EETI_CONFIG_SMTPSERVER')) echo EETI_CONFIG_SMTPSERVER; else echo 'tls:smtp.mymail.com:587'; ?>"></input>
      <div class="tip">Tip! You can figure out this information from your email provider. Indicate the protocol before the first colon (ssl, tls, plain), then the host, and then the port. Only applicable if you're using e-mail.</div>
    </div>
    <br>
    <div>
      SMTP username: <input type="text" name="smtpuser" value="<?php if(defined('EETI_CONFIG_SMTPUSER')) echo EETI_CONFIG_SMTPUSER; else echo 'some@email.tld'; ?>"></input>
      <div class="tip">Tip! You can figure out this information from your email provider. Only applicable if you're using e-mail. Optional.</div>
    </div>
    <br>
    <div>
      SMTP password: <input type="password" name="smtppass" value="<?php if(defined('EETI_CONFIG_SMTPPASS')) echo EETI_CONFIG_SMTPPASS; else echo 'somepassword'; ?>"></input>
      <div class="tip">Tip! You can figure out this information from your email provider. This password is stored as plaintext. Only applicable if you're using e-mail. Optional.</div>
    </div>
    <br>
    <div>
      E-mail from: <input type="text" name="smtpfrom" value="<?php if(defined('EETI_CONFIG_SMTPFROM')) echo EETI_CONFIG_SMTPFROM; else echo 'some@email.tld'; ?>"></input>
      <div class="tip">Tip! This is the email address from which mail should be sent. Should be in the format of "some@email.tld".</div>
    </div>
  </div>
  <hr>
  <h2>eetiSSO</h2>
  <div id="eetisso">
    Enable eetiSSO?
      <input type="radio" name="ssoenabled" value="true" <?php if( defined("EETI_CONFIG_SSOENABLED") && EETI_CONFIG_SSOENABLED ) echo "checked"; ?>>Yes</input>
      <input type="radio" name="ssoenabled" value="false" <?php if( defined("EETI_CONFIG_SSOENABLED") && ! EETI_CONFIG_SSOENABLED ) echo "checked"; ?>>No</input>
      <div class="tip">Tip! This will allow you to connect your eeti2 instance to other/external software applications.</div>
    eetiSSO key: <input type="text" name="ssokey" value="<?php if(defined('EETI_CONFIG_SSOKEY')) echo EETI_CONFIG_SSOKEY; else echo bin2hex(openssl_random_pseudo_bytes(15)); ?>"></input>
      <div class="tip">Tip! This is the secret key that servers will use to communicate to each other with. Only required if enabling eetiSSO. The default is usually fine.</div>
    eetiSSO hosts: <input type="text" name="ssohosts" value="<?php if(defined('EETI_CONFIG_SSOHOSTS')) echo EETI_CONFIG_SSOHOSTS; ?>"></input>
      <div class="tip">Tip! This is a comma-seperated list of hosts eetiSSO should work on.</div>
  </div>
  <hr>
  <?php if( defined("EETI_UPGRADING") || ! EETI_INSTALLED ){ ?>Was it really that easy? <input name="setup" type="submit" value="Let's find out..."></input><?php }
  else { ?><input name="setup" type="submit" value="Save"></input><?php } ?>
</form>
<?php
  pfooter();
  die();
}
else {
  $config=array();

  $config["installed"]=true;

  unset($_POST['setup']);

  if( (@isset($_POST['adminusername']) && @isset($_POST['adminpassword']) && @isset($_POST['adminemail'])) && ! userExists(0) ) addUser($_POST['adminusername'], $_POST['adminpassword'], $_POST['adminemail'], "A");

  if( @isset($_POST['adminusername']) ) unset($_POST['adminusername']);
  if( @isset($_POST['adminpassword']) ) unset($_POST['adminpassword']);
  if( @isset($_POST['adminemail']) ) unset($_POST['adminemail']);

  $_POST['smtpenabled']=toBool($_POST['smtpenabled']);
  $_POST['uploadingenabled']=toBool($_POST['uploadingenabled']);
  $_POST['pasteenabled']=toBool($_POST['pasteenabled']);
  $_POST['communityenabled']=toBool($_POST['communityenabled']);
  $_POST['requestsenabled']=toBool($_POST['requestsenabled']);
  $_POST['ssoenabled']=toBool($_POST['ssoenabled']);

  $_POST['version']=EETI_VERSION;

  if( ! file_put_contents("../stor/config.db", json_encode($_POST))){
    @pheader("error writing configuration");
    ?>
    <h1>O noes!</h1>
    There was an error writing your configuration. Please ensure that the /stor directory is writable to the Web server, and then try again.
    <?php
    pfooter();
    die();
  }

  @pheader("success");
  ?>
  <h1>Success!</h1>
  <a href="./index.php">Head to the home page</a> or the <a href="./acp.php">the administrative control panel</a>.
  <?php
  pfooter();
  die();
}
?>
