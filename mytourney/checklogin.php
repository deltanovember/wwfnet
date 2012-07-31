<?php

require_once "login_config.php";
require_once 'email_smtp.php';

//Connection String Variables_________________________________________________

// connect to the server
mysql_connect( $db_host, $db_username, $db_password )
    or die( "Error! Could not connect to database: " . mysql_error() );
   
// select the database
mysql_select_db( $db )
    or die( "Error! Could not select the database: " . mysql_error() );

$username = strip_tags(substr($_REQUEST['username'],0,32));
$userid = 0;
$urlpw = strip_tags(substr($_REQUEST['password'],0,32));

$cleanpw = md5($urlpw);
$sql="SELECT * FROM users WHERE username='$username' and password='$cleanpw'";

$result=mysql_query($sql);
$count=mysql_num_rows($result);
$row= mysql_fetch_array($result);
// If result matches $myusername and $mypassword, table row must be 1 row
if ($count == 1 &&
        $row['verified'] == 0) {
    // unverified
    print "Your e-mail has not yet been verified. Please check your spam filters and if necessary mark the verification email as 'Not Spam' before clicking on the link. If no verification email has arrived within 10 minutes of sign up, please e-mail admin@wordswithfriends.net so that the account can manually be verified.";
    smtp_mail("admin@wordswithfriends.net", "Unverified user login", "Please investigate $username");
}
else if($count==1){

    $userid = $row['id'];
    
    // update IP
    $ip = $_SERVER['REMOTE_ADDR'];
    $ipSql="UPDATE users set last_ip='$ip' where username='$username'";
    $result=mysql_query($ipSql);

    //set to 24 hours
    $cookie_expire ="86400";
    setcookie(NAME_COOKIE, $username,time() + (86400),"/", DOMAIN_COOKIE);
    setcookie(ID_COOKIE, $userid,time() + (86400),"/", DOMAIN_COOKIE);

    if ($debugmode) {
        header("location:$successful_login_url");
    }
    else if (isset ($_REQUEST['referrer']) &&
            $_REQUEST['referrer'] != '' &&
            strrpos($_REQUEST['referrer'], "admin")) {
       $refer = $_REQUEST['referrer'];
       header("location:$refer");
    }
    else {
?>
<script language="JavaScript" type="text/javascript">
<!--
parent.location = "<?php echo "http://{$_SERVER['HTTP_HOST']}/?page_id=386"; ?>";
//-->
</script>
<?php
    }


}
else{
    
    header("location:$failed_login");
}


?>





