<?php

$name  = isset($_POST['name'])  ? $_POST['name']  : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$text  = isset($_POST['text'])  ? $_POST['text']  : '';

$textCount = mb_strlen($text);

$checkName  = preg_match('/^[a-zA-Z ]{2,60}$/', $name);
$checkEmail = isValidEmail($email);
$checkText  = $textCount > 6 && $textCount < 300;


if ( $checkName && $checkEmail && $checkText )
{
    $response = [
        'error' => 1,
        'isAllValid' => true
    ];

    // mailer
    require 'assets/php/PHPMailer/PHPMailerAutoload.php';

    $mail = new PHPMailer;

    $mail->From = $email;
    $mail->FromName = $name;
    $mail->addAddress('mhacoding@gmail.com', 'mhacoding');
    $mail->isHTML(true);

    $mail->Subject = 'Anfrage von ' . $name;
    $mail->Body    = '<style>p{color:blue;}</style>This is the HTML message body <b>in bold!</b><p>' . htmlspecialchars($text) . '</p>';
    

    if ( ! $mail->send() ) 
    {
        $response['msg'] = $mail->ErrorInfo;
    } 
    else 
    {
        $response['error'] = 0;
        $response['msg']   = 'Message has been sent.';
    }
}
else
{
    $msg = 'Unknown error.';

    $isNameValid  = true;
    $isEmailValid = true;
    $isTextValid  = true;


    if ( ! $checkName ) 
    {
        $isNameValid = false;
        $msg = 'Invalid name.';
    }
    
    if ( ! $checkEmail )
    {
        $isEmailValid = false;
        $msg = 'Invalid email.';
    }
    
    if ( ! $checkText )
    {
        $isTextValid = false;
        $msg = 'Invalid text.';
    }


    $response = [
        'isNameValid'  => $isNameValid,
        'isEmailValid' => $isEmailValid,
        'isTextValid'  => $isTextValid,
        'msg'   => $msg
    ];
}


echo json_encode($response);


/// functions
/**
 * Validate an email address.
 * Provide email address (raw input)
 * Returns true if the email address has the email
 * address format and the domain exists.
 *
 * @see - http://www.linuxjournal.com/article/9585
 */
function isValidEmail ($email)
{
   $isValid = true;
   $atIndex = strrpos($email, '@');

   if ( is_bool($atIndex) && ! $atIndex )
      $isValid = false;
   else
   {
      $domain = substr($email, $atIndex+1);
      $local = substr($email, 0, $atIndex);
      $localLen = strlen($local);
      $domainLen = strlen($domain);

      if ($localLen < 1 || $localLen > 64)
         // local part length exceeded
         $isValid = false;
      else if ($domainLen < 1 || $domainLen > 255)
         // domain part length exceeded
         $isValid = false;
      else if ($local[0] == '.' || $local[$localLen-1] == '.')
         // local part starts or ends with '.'
         $isValid = false;
      else if (preg_match('/\\.\\./', $local))
         // local part has two consecutive dots
         $isValid = false;
      else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
         // character not valid in domain part
         $isValid = false;
      else if (preg_match('/\\.\\./', $domain))
         // domain part has two consecutive dots
         $isValid = false;
      else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace('\\\\', '', $local)))
         // character not valid in local part unless
         // local part is quoted
         if (!preg_match('/^"(\\\\"|[^"])+"$/', str_replace('\\\\', '', $local)))
            $isValid = false;

      //checkdnsrr($domain,"MX") // "MX" is default according to php.net
      if ($isValid && !(checkdnsrr($domain) || checkdnsrr($domain, 'A')))
         // domain not found in DNS
         $isValid = false;
   }

   return $isValid;
}


exit;
