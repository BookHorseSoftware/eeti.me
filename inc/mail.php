<?php

  require("../lib/PHPMailerAutoload.php");

  require_once("log.php");

  function sendMail($to, $subject, $message){
    if( ! EETI_CONFIG_SMTPENABLED ) return;
    else {


      $mail = new PHPMailer;

      $mail->isSMTP();
      $mail->SMTPAuth = true;
      $mail->Username = EETI_CONFIG_SMTPUSER;
      $mail->Password = EETI_CONFIG_SMTPPASS;

      $serverinfo = explode(":", EETI_CONFIG_SMTPSERVER);

      if( count($serverinfo) < 3 ){
        elog("mail", "This site's email configuration is incorrect (invalid format). The email to " . $to . " was not sent. Please run the setup wizard again to fix it.");
        return;
      }

      try {
        if( $serverinfo[0] != "plain" ){
          $mail->SMTPSecure = $serverinfo[0];
        }

        $mail->Host = $serverinfo[1];
        $mail->Port = intval($serverinfo[2]);
      } catch(Exception $e){
        elog("mail", "This site's email configuration is incorrect (error parsing). The email to " . $to . " was not sent. Please run the setup wizard again to fix it.");
        return;
      }

      $mail->From = EETI_CONFIG_SMTPFROM;
      $mail->FromName = EETI_CONFIG_TITLE;
      $mail->addAddress($to);

      $mail->Subject = $subject;
      $mail->Body = $message;

      if( ! $mail->Send() ){
        elog("mail", "Message to " . $to . " could not be sent: " . $mail->ErrorInfo);
      } else {
        elog("mail", "Message sent to " . $to);
      }
    }
  }

?>
