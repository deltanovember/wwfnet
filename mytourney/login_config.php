<?php

header("Pragma: no-cache");
header("cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//**NOTE: No space can exist before <?PHP above or it will mess up the headers***

//**REQUIRED SETUP OPTIONS...SET VALUES 1-5 TO BEGIN USING BASICLOGIN BY CHANGING VALUES IN QUOTES.  WE ADVISE AGAINST CHANGING THE VARIABLE NAMES

//**STEP 1: What is the name of your site? I.E. basiclogin.com
$sitename = "WordsWithFriends.net";

//**STEP 2: What is the site domain for emails?  No http://www.  Just something.com
$email_domain = "wordswithfriends.net";

//**STEP 3: What is the full path to the folder where the login script is located? I.E. https://www.yoursite.com/login_app
$domain = "http://www.wordswithfriends.net/mytourney";
$pieces = explode("/", $domain);
$base_dir = "/".$pieces[sizeof($pieces) - 1];


//**STEP 4: SET DATABASE CONNECTION VARIABLES:
// set to false for production
$debugmode = false;

$db_host = "localhost";
$db_username = "dngu047";
$db_password = "t3stt3st";
$db = "networdswithfriends";

// leave alone
$GLOBALS['db_name'] = $db;

//**OPTIONS___________________________________________________________________________________________________________________________

//**OPTION: Where does a successful login redirect to?  Default is a router page that redirects according to permissions rules...but can be anywhere
$successful_login_url = "router.php";

//**OPTION: Where should the Router send users with various permission levels?
$level_5_url = "index.php";
$level_4_url = "index.php";
$level_3_url = "index.php";
$level_2_url = "index.php";
$level_1_url = "index.php";

//**OPTION: Where does an unsuccessful login rediredt to?

$failed_login = "login_failed.php";

// ensure uniqueness to domain
if (!defined('NAME_COOKIE') )
	define('NAME_COOKIE', "playername".md5($email_domain));

if ( !defined('ID_COOKIE') )
	define('ID_COOKIE', "playerid".md5($email_domain));

if ( !defined('DOMAIN_COOKIE') )
	define('DOMAIN_COOKIE', ".".$email_domain);

//$cookie_domain = "localhost";

//**OPTION: What is the cookie name...can be anything...no whitespaces or special characters
$cookiename = "MyTourney";
$cookieidname = "userID";

//**OPTION: Forgot Password Email Parameters:
$from_email = "admin@wordswithfriends.net";
$reply_to_email = "admin@wordswithfriends.net";
$return_path_email = "postmaster@wordswithfriends.net";

//**OPTION: What is the subject of the email that you send to someone who forgot their password?
$forgot_password_email_subject = "Your $sitename tournament password";


//**NO NEED TO CHANGE THIS
$email_4_pw_email = "";
if (isset ($_REQUEST['email'])) {
  $email_4_pw_email = $_REQUEST['email'];
}


if($email_4_pw_email !==''){

   // connect to the server
   mysql_connect( $db_host, $db_username, $db_password )
      or die( "Error! Could not connect to database: " . mysql_error() );
   
   // select the database
   mysql_select_db( $db )
      or die( "Error! Could not select the database: " . mysql_error() );

//The following Query Gets password from database for email if called for 

$query = "SELECT * FROM users WHERE `email` LIKE '$email_4_pw_email'"; 

$numresults=mysql_query($query);
$numrows=mysql_num_rows($numresults); 

// get results
$result = mysql_query($query) or die("Couldn't execute query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$email= $row["email"];
$password = $row["password"];
$password_hint = $row["password_hint"];

//Debug: echo "<br><br>Password:$password<br><br>";

if($password_hint!==''){

//**OPTION: YOU CAN EDIT THE COPY IN THESE EMAILS IF YOU WANT.  THIS ONE IS FOR PEOPLE WHO HAVE A PASSWORD HINT

$forgot_password_email = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif'>This is the password manager at <b>$sitename</b>. We do not store passwords...only encrypted data. <br><br>Here is a password hint that you provided when you set up your account: $password_hint<br><br>If that helps, then you can <a href='$domain/login.php'>try again</a> without resetting your password<br><br>

<br>If you still can't remember then please <a href='$domain/reset_password.php?email=$email&password=$password'>Click Here</a> to reset your password.  Clicking this link will assign an encrypted, temporary password for added security.  The process can easily be repeated if you experience difficutlties.<br><br> Best Regards - WordsWithFriends.net Tournament Team</font>";
}
else
{

//**OPTION:  THIS ONE IS FOR PEOPLE WHO DON'T HAVE A PASSWORD HINT

$forgot_password_email = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif'>This is the password manager at <b>$sitename</b>. We do not store passwords...only encrypted data.<br>
<br>Please <a href='$domain/reset_password.php?email=$email&password=$password'>Click Here</a> to reset your password.  Clicking this link will assign an encrypted, temporary password for added security.  The process can easily be repeated if you experience difficutlties.<br><br> Best Regards - WordsWithFriends.net Tournament Team</font>";
}

}}

//**OPTION: THAT'S ALL FOR NOW.  MORE OPTIONS MAY BE ADDED LATER.  WE'LL LET YOU KNOW.  OTHERWISE, FEEL FREE TO ADD YOUR OWN!
?>
