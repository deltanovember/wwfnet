<?php

require_once "Mail.php";
require_once "Mail/mime.php";

/**
 * Generic email function to easily switch between gmail,
 * postfix etc
 * @param <type> $to
 * @param <type> $subject
 * @param <type> $mailbody
 * @return <type>
 */
function email($to, $subject, $body) {
    return base_mail($to, $subject, $body);
}
// no html
function text_mail($to, $subject, $body) {
    return gmail_text($to, $subject, $body);
}

function base_mail($to, $subject, $mailbody) {
     

    	$from = "Admin <admin@wordswithfriends.net>";
	$reply_to = "admin@wordswithfriends.net";
	$return_path = "admin@wordswithfriends.net";

	//____________________________Begin Multipart Mail Sender
	//add From: header
	$headers = "From:$from\nReply-to:$reply_to\nReturn-path:$return_path\nJobID:".time()."\n";

	//specify MIME version 1.0
	$headers .= "MIME-Version: 1.0\n";

	//unique boundary
	$boundary = uniqid("HTMLDEMO8656856");

	//tell e-mail client this e-mail contains//alternate versions
	$headers.="Content-Type: multipart/alternative; boundary=\"".$boundary."\"\n";
	$headers.="Content-Transfer-Encoding: 7bit\n";

	//message to people with clients who don't
	//understand MIME
	$headers .= "This is a MIME encoded message.\n\n";

	//plain text version of message
	$headers .= "--$boundary\n" .
	   "Content-Type: text/plain; charset=ISO-8859-1\r\n" .
	   "Content-Transfer-Encoding: base64\n\n";
	$headers .= chunk_split(base64_encode("$mailbody"));

	//HTML version of message
	$headers .= "--$boundary\n" .
	   "Content-Type: text/html; charset=ISO-8859-1\n" .
	   "Content-Transfer-Encoding: base64\n\n";
	$headers .= chunk_split(base64_encode("$mailbody"));
        mail("audit@wordswithfriends.net", $subject, "", $headers, '-f admin@wordswithfriends.net');
        return mail($to, $subject, "", $headers, '-f admin@wordswithfriends.net');
 }

 /**
  *
  * @param <type> $to
  * @param <type> $subject
  * @param <type> $body
  * @return true if successful, false otherwise
  */
 function smtp_mail($to, $subject, $body) {

    $from = "Administrator <admin@wordswithfriends.net>";
    $reply = "Administrator <admin@wordswithfriends.net>";
    $port = 25;
    $crlf = "\n";

    $host = "50.22.72.198";
    //$username = "wwf";
    //$password = "t3stt3st";

    $headers = array ('From' => $from,
        'Reply-To' => $reply,
   'To' => $to,
   'Subject' => $subject);
    $smtp = Mail::factory('smtp',
        array ('host' => $host,
       'port' => $port,
       // 'auth' => true,
        //'username' => $username,
        //'password' => $password

           ) );
        
    $mime = new Mail_mime($crlf);
    $mime->setTXTBody($body);
    $mime->setHTMLBody($body);

    $mimebody = $mime->get();
    $mimeheaders = $mime->headers($headers);

    $mail = $smtp->send($to, $mimeheaders, $mimebody);

   // $mimeheaders = $mime->headers($hdrs);
   // $mail = $smtp->send($to, $headers, $bmimebody);

    if (PEAR::isError($mail)) {
        return false;
    }
    else {
        return true;
    }

 }

 function gmail($to, $subject, $body) {

     $crlf = "\n";
     
     $from = "Administrator <admin@wordswithfriends.net>";

    // stick your GMAIL SMTP info here! ------------------------------
    $host = "ssl://smtp.gmail.com";
    $port = "465";
    $which_account = rand(1,9);
    $username = "postman$which_account@wordswithfriends.net";
    $password = "t3stt3st"; 
    // --------------------------------------------------------------

    $headers = array ('From' => $from,
    'To' => $to,
     'Subject' => $subject);
    $smtp = Mail::factory('smtp',
    array ('host' => $host,
        'port' => $port,
    'auth' => true,
    'username' => $username,
    'password' => $password));

    $mime = new Mail_mime($crlf);
    $mime->setTXTBody($body);
    $mime->setHTMLBody($body);

    $mimebody = $mime->get();
    $mimeheaders = $mime->headers($headers);

    $mail = $smtp->send($to, $mimeheaders, $mimebody);


    //$mail = $smtp->send($to, $headers, $body);

    if (PEAR::isError($mail)) {
      return false;
     }
     else {
      return true;
     }

 }

  function gmail_text($to, $subject, $body) {

     $crlf = "\n";

     $from = "Administrator <admin@wordswithfriends.net>";

    // stick your GMAIL SMTP info here! ------------------------------
    $host = "ssl://smtp.gmail.com";
    $port = "465";
    $which_account = rand(1,9);
    $username = "postman$which_account@wordswithfriends.net";
    $password = "t3stt3st";
    // --------------------------------------------------------------

    $headers = array ('From' => $from,
    'To' => $to,
     'Subject' => $subject);
    $smtp = Mail::factory('smtp',
    array ('host' => $host,
        'port' => $port,
    'auth' => true,
    'username' => $username,
    'password' => $password));

    $mime = new Mail_mime($crlf);
    $mime->setTXTBody($body);

    $mimebody = $mime->get();
    $mimeheaders = $mime->headers($headers);

    $mail = $smtp->send($to, $mimeheaders, $mimebody);


    //$mail = $smtp->send($to, $headers, $body);

    if (PEAR::isError($mail)) {
      return false;
     }
     else {
      return true;
     }

 }



?>
