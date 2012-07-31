<?php 

session_start();
include"master_inc.php";
include "login_config.php";
require_once 'functions.php';

$lastname = strip_tags(substr($_POST['lastname'],0,50));
$firstname = strip_tags(substr($_POST['firstname'],0,50));
$phone = strip_tags(substr($_POST['phone'],0,50));
$password_hint=strip_tags(substr($_POST['password_hint'],0,140));
$skill=strip_tags(substr($_POST['skill'],0,50));
$timezone=strip_tags(substr($_POST['timezone'],0,50));
$privacy=strip_tags(substr($_POST['privacy'],0,50));
$comment=strip_tags(substr($_POST['comment'],0,140));
$username = strip_tags(substr($_POST['username'],0,50));
$device = strip_tags(substr($_POST['device'],0,25));
$url = strip_tags(substr($_POST['url'],0,75));

//First Name Verification
if(trim($firstname)=='' || strlen(trim($firstname)) < 1){
$firstname_fail = 104;
}

//URL Format Validation
$url_raw = strip_tags(substr($_POST['url'],0,50));
function isValidURL($my_url)
{
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $my_url);
}

if ($url_raw != ""){
   if (!isValidURL($url_raw)){$invalid_url = 104;}
   else
      {$url = $url_raw;}
}

//CAPTCHA Image verification
include_once $_SERVER['DOCUMENT_ROOT'] . '/mytourney/securimage/securimage.php';
$securimage = new Securimage();
if ($securimage->check($_POST['captcha_code']) == false) {
  // the code was incorrect
  // handle the error accordingly with your other error checking

  // or you can do something really basic like this

$captcha_failed = 104;
}

//user unique?
if(trim($username)!=='' || strlen(trim($username)) >= 4){

//email unique?
$sql="SELECT * FROM users WHERE username='$username'";
$result=mysql_query($sql);
$count=mysql_num_rows($result);
if($count>0){
$username_already_in_use = 104;
}

}else{
$username_too_short = 104;}

//email format check
$email_raw = $_REQUEST['email'];
if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@([a-z0-9-]{2,3})+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email_raw))
{ 
$email = $email_raw;
}else{
$bad_email=104;
} 
//email unique?
$sql="SELECT * FROM users WHERE email='$email'";
$result=mysql_query($sql);
$count=mysql_num_rows($result);
if($count>0){
$email_already_in_use=104;
}
//Secure Password Format Checks
$pw_clean = strip_tags(substr($_POST['password'],0,32));
if (preg_match("/[a-z]+[0-9]/", $pw_clean, $matches)) {
}else{
$pw_insecure = 104;
}
if($invalid_url!=104 && 
        $username_already_in_use!=104 &&
        $email_already_in_use!=104 &&
        $pw_insecure!=104 &&
        $bad_email!=104 &&
        $username_too_short!=104 &&
        $captcha_failed!=104 &&
        $firstname_fail!=104) {

    //Encrypt Password
    $encrypted_pw = md5($pw_clean);

    //Set Current Date/Time
    $joined = date("Y-m-d H:i:s", time());

    $query = "INSERT INTO `users` (`username`,
    `password`,
    `lastname`,
    `firstname`,
    `email`,
    `phone`,
    `password_hint`,
    `privacy`,
    `skill`,
    `timezone`,
    `comment`,
    `device`,
    `url`,
    `joined`,
    `verified`)
    VALUES
    (
    '$username',
    '$encrypted_pw',
    '$lastname',
    '$firstname',
    '$email',
    '$phone',
    '$password_hint',
    '$privacy',
    '$skill',
    '$timezone',
    '$comment',
    '$device',
    '$url',
    '$joined',
    0
    )";
    // save the info to the database
    $results = mysql_query( $query );
    // print out the results
    if( $results ) {

        // protect against fake emails
        verify_email($username, $email);
        header("Location:$base_dir/login.php?conf=1&user=$firstname");
    }
    else
    {
    die( "Trouble saving information to the database: " . mysql_error() );
    }


}

// Build error message
$errors="<div id='errors'>Please address the following error(s):<ol> ";
	if($firstname_fail==104){$errors .= "<li>You must provide your First Name. It needs to be at least 2 characters long.</li>";}
	
	if($username_too_short==104){$errors .= "<li>That Screen Name is too short.  Please make it more than 4 characters.</li>";}
	
	if($username_already_in_use==104){$errors .= "<li>That Screen Name is already in use. This probably means you have an existing account. <a href='login.php'>Log in</a> or <a href='email_password.php'>reset your password</a>.</li></li>";}

	if($email_already_in_use==104){$errors .= "<li>That Email is already in use. This probably means you have an existing account. <a href='login.php'>Log in</a> or <a href='email_password.php'>reset your password</a>.</li>";}

	if($pw_insecure==104){$errors .= "<li>Your Password is not formatted correctly.  Please choose a password that is between 4 and 20 characters and has at least one lower case letter and one number. (For example: <i>hello23</i>)</li>";}
	
    if($invalid_url==104){$errors .= "<li>Your URL is not properly formatted. Either leave it blank or format like this: <b>http://mysite.com</b>.</li>";}
	
    if($bad_email==104){$errors .= "<li>Your Email does not appear to be valid.</li>";}

    if($captcha_failed==104){$errors .= "<li>You entered the wrong Image Verification Code.</li>";}
	
$errors .= "</ol><em>Note: For security, you must enter the password and Image Code again.</em></div>";

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Register a User</title>
<style type="text/css">
body {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:515px;}
#wrap {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:515px;}
.shaded {background-color:#ebebeb;}
#errors {padding:10px; background-color:#FFFF99; border:1px solid red; color:#FF0000; width:474px; text-align:left;}
.required {color:#FF0000; font-weight:bold;}
</style>

</head>

<body topmargin="0" onLoad="javascript:alert('OOPS! Looks like you made an error. See instructions at the bottom of the form.');">

<div align="center" id="wrap">

  <form action="user_add_save.php" method="post" name="form" id="form">
    <H3><strong>Register a Player</strong></H3>
        <p><a href="login.php"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Back to Login</font></a></p>
    <table width="474" border="0" cellspacing="0" cellpadding="5">
    <tr><td colspan="2"><span class="required">* - indicates required field</span>
    <tr class="shaded">
      <td>First Name <span class="required">*</span></td>
      <td><input type = "text" value="<?php echo $firstname; ?>" name="firstname" width="50" /></td>
    </tr>
    <tr>
      <td>Last Name<br /><small>(Will not appear to the public)</small></td>
      <td><input type = "text" value="<?php echo $lastname; ?>" name="lastname" width="50" /></td>
    </tr>

    <tr class="shaded">
      <td>Words With Friends Screen Name <span class="required">*</span></td>
      <td><input type = "text" value="<?php echo $username; ?>" name="username" width="50" /></td>
    </tr>
    <tr class="shaded"><td colspan="2"><small>Important: Do NOT include emoticons <img src="http://wordswithfriends.net/wp-includes/images/smilies/icon_wink.gif" /> Also, you MUST use the same nickname for your iPhone games (e.g., "goodplayer23")</small></td></tr>
    <tr>
      <td>Password <span class="required">*</span></td>
      <td><input type = "password" value="" name="password" width="50" /></td>
    </tr>
    <tr>
      <td colspan="2"><small>Your password must be at least 4 characters long and contain at least 1 letter and 1 number. Also, bear in mind that this password has NOTHING to do with your wwf application login; it will be the password for this site only.</small></td>
    </tr>
        <tr class="shaded">
      <td>Password Hint</td>
      <td><input type = "text" value="<?php echo $password_hint; ?>" name="password_hint" width="50" autocomplete="OFF" /></td>
    </tr>
    <tr>
      <td>Skill Level <span class="required">*</span></td>
      <td><select name="skill">
        <option <?php if ($skill=="Beginner") {echo "selected";} ?> value="Beginner">Beginner</option><option <?php if ($skill=="Intermediate") {echo "selected";} ?> value="Intermediate">Intermediate</option><option <?php if ($skill=="Advanced") {echo "selected";} ?> value="Advanced">Advanced</option><option <?php if ($skill=="Expert") {echo "selected";} ?> value="Expert">Expert</option></select></td>
    </tr>        

    <tr class="shaded">
      <td>Time Zone <span class="required">*</span></font></td>
      <td>
        <select name="timezone">
        <option <?php if ($timezone=="Pacific") {echo "selected";} ?> value="Pacific">Pacific</option><option <?php if ($timezone=="Eastern") {echo "selected";} ?> value="Eastern">Eastern</option><option <?php if ($timezone=="Central") {echo "selected";} ?> value="Central">Central</option><option <?php if ($timezone=="Mountain") {echo "selected";} ?> value="Mountain">Mountain</option><option <?php if ($timezone=="Outside U.S.") {echo "selected";} ?> value="Outside U.S.">Non-US (specify in comments)</option></select></td>
    </tr>     
       
    <tr>
      <td>Device <span class="required">*</span></td>
      <td>
        <select name="device">
        <option <?php if ($device=="iPhone") {echo "selected";} ?> value="iPhone">iPhone</option><option <?php if ($device=="iPod Touch") {echo "selected";} ?> value="iPod Touch">iPod Touch</option><option <?php if ($device=="iPad") {echo "selected";} ?> value="iPad">iPad</option></select></td>
    </tr>

        <tr class="shaded">
      <td>Your URL<br /><small>(include 'http://')</small></td>
      <td><input type = "text" value="<?php echo $url; ?>" name="url" width="50" autocomplete="ON" /></td>
    </tr>
    <tr>
      <td>Email <span class="required">*</span><br />
      <small>(Required for tournament notifications)</small></td>
      <td><input type = "text" value="<?php echo $email; ?>" name="email" width="50" autocomplete="ON" /></td>
    </tr>
    <tr class="shaded">
      <td>Phone</font></td>
      <td><input type = "text" value="<?php echo $phone; ?>" name="phone" width="50" autocomplete="ON" /></td>
    </tr>

        <tr>
<td>Privacy</td>      
        <td>
<input type="checkbox" name="privacy" value="on" <?php if ($privacy=="on") {echo "checked='checked'";} ?>  />
Hide my contact info<br />
 </td></tr>
 <tr><td colspan="2"><small>Checking this box will hide your phone number and email address from your opponents. We do not recommend doing this, as it makes it harder to coordinate games.</small></td>
    </tr>  

        <tr class="shaded">
      <td>Profile Comment <br />
        <small>Visible to everyone; max 140 characters.</small></td>
      <td>
<textarea name="comment" id="comment" maxlength="140"><?php echo $comment; ?></textarea>
 </td>
    </tr>  
    
    </tr>  
<tr><td><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /></td><td>Input the code <span class="required">*</span>:</font>&nbsp; <input type="text" name="captcha_code" size="10" maxlength="6" /></td></tr>    
  </table>
  <p><input type="submit" value="Save and Continue" name="submit2" /></p>
  </form>

<?php echo $errors; ?>

</div>
</body>
</html>