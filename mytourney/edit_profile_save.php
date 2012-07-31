<?php //Set permission level threshold for this page remove if page is good for all levels
require_once "login_config.php";

$permission_level=1;

include"auth_check_header.php";

$nu_lastname = strip_tags(substr($_POST['nu_lastname'],0,50));
$nu_firstname = strip_tags(substr($_POST['nu_firstname'],0,50));
$nu_username = strip_tags(substr($_POST['nu_username'],0,50));
$nu_phone = strip_tags(substr($_POST['nu_phone'],0,50));
$nu_email = strip_tags(substr($_POST['nu_email'],0,50));
$nu_password_hint=strip_tags(substr($_POST['nu_password_hint'],0,140));
$nu_skill=strip_tags(substr($_POST['nu_skill'],0,50));
$nu_timezone=strip_tags(substr($_POST['nu_timezone'],0,50));
$nu_privacy=strip_tags(substr($_POST['nu_privacy'],0,50));
$nu_comment=addslashes(strip_tags(substr($_POST['nu_comment'],0,140)));
$nu_device = strip_tags(substr($_POST['nu_device'],0,25));
$nu_id = strip_tags(substr($_POST['nu_id'],0,5));

//First Name Verification
if(trim($nu_firstname)=='' || strlen(trim($nu_firstname)) < 1){
$firstname_fail = 104;
}

// User name
if(trim($nu_username)=='' || strlen(trim($nu_username)) < 4){
$username_too_short = 104;
}

//URL Format Validation
$url_raw = strip_tags(substr($_POST['nu_url'],0,50));
function isValidURL($my_url)
{
return preg_match('|^http(s)?://[a-z0-9-]+(.[a-z0-9-]+)*(:[0-9]+)?(/.*)?$|i', $my_url);
}

if ($url_raw != ""){
   if (!isValidURL($url_raw)){$invalid_url = 104;}
   else
      {$nu_url = $url_raw;}
}

// 'Apples to Apples' ID check against hacker mischief

$username_from_cookie = $_COOKIE[NAME_COOKIE]; //retrieve contents of cookie
			
$query = "SELECT * FROM users WHERE username='$username_from_cookie'"; 

$result = mysql_query($query) or die("Couldn't execute query");

while ($row= mysql_fetch_array($result)) {
$id= $row["id"];
$username= $row["username"];
$email = $row["email"];
$alias = $row["alias"];
}

if ($id == $nu_id && $id == $userid_from_cookie) {
}else{
session_destroy();
die( "Something didn't work as expected. Please contact us at admin@wordswithfriends.net for assistance." . mysql_error() );
}


// Check uniqueness of new username

if ($username == $nu_username || $nu_username == "") {
}else{
if(trim($nu_username)!='' || strlen(trim($nu_username)) >= 4){

$sql="SELECT * FROM users WHERE username='$nu_username'";
$result=mysql_query($sql);
$count=mysql_num_rows($result);
if($count>0){
$username_already_in_use = 104;
}

}else{ $username_too_short = 104;}

}


//If email was updated, check for proper format and uniqueness 

$email_raw = $_REQUEST['nu_email'];

if ($email_raw != $email){
// format OK?
    if(eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@([a-z0-9-]{2,3})+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email_raw))
    { 
    $nu_email = $email_raw;
    }else{
    $bad_email=104;
    } 

//unique?
    $sql="SELECT * FROM users WHERE email='$nu_email'";
    $result=mysql_query($sql);
    $count=mysql_num_rows($result);
    if($count>0){
    $email_already_in_use=104;
    }
}

//Secure Password Format Checks
$pw_clean = strip_tags(substr($_POST['nu_password'],0,32));
if ($pw_clean != ""){
    if (preg_match("/[a-z]+[0-9]/", $pw_clean, $matches)) {
    }else{
    $pw_insecure = 104;
    }
}

//Create alias insert
if ($nu_username == $username){
}else{
if ($alias != ""){ $alias .= " | " . $username; }
else {$alias = $username;}
}

if($invalid_url!=104 && $username_already_in_use!=104 && $email_already_in_use!=104 && $pw_insecure!=104 && $bad_email!=104 && $username_too_short!=104 && $firstname_fail!=104){

//Encrypt Password
if ($pw_clean == ""){$pw_insert = "";}
else {
$encrypted_pw = md5($pw_clean);
$pw_insert = "`password`='$encrypted_pw', ";}

$query = "UPDATE `users` SET " . $pw_insert . "`lastname`='$nu_lastname',
`firstname`='$nu_firstname',
`email`='$nu_email',
`phone`='$nu_phone',
`password_hint`='$nu_password_hint',
`privacy`='$nu_privacy',
`skill`='$nu_skill',
`timezone`='$nu_timezone',
`comment`='$nu_comment',
`device`='$nu_device',
`url`='$nu_url',
`username`='$nu_username',
`alias`='$alias' 
WHERE `id`='$id'";

// INSERT into the database
$results = mysql_query($query);

if ($nu_username == $username){
}else{
//Update any active tournaments with new screen name, if needed
$query = "SELECT * FROM T_CONTROL WHERE `t_status` = 'In Progress' OR `t_status` = 'Register'";
$result = mysql_query($query) or die("Couldn't execute query");
$count=mysql_num_rows($result);
while ($row= mysql_fetch_array($result)) {
	$t_id= $row["t_id"];
	if (strrpos($t_id,"D20") > -1){ 
	$array = array("player","r1_vs","r2_vs","r3_vs","r4_vs","r5_vs","r6_vs","r7_vs","r8_vs","r9_vs","r10_vs","r11_vs","r12_vs","r13_vs","r14_vs","r15_vs","r16_vs","r17_vs","r18_vs","r19_vs","r20_vs","r21_vs","r22_vs","r23_vs","r24_vs","r25_vs","r26_vs","r27_vs","r28_vs","r29_vs","r30_vs","r31_vs");
	}else{
		$array = array("player","r1_vs","r2_vs","r3_vs","r4_vs","r5_vs","r6_vs","r7_vs","r8_vs","r9_vs","r10_vs","r11_vs","r12_vs","r13_vs","r14_vs","r15_vs");
		}
	foreach($array as $key => $value) {
 	$query1 = "UPDATE `" . $t_id . "` SET " . $value . " = '" . $nu_username . "' WHERE " . $value . " = '" . $username . "'";
	$result1 = mysql_query($query1) or die("Couldn't execute query1 - " . $t_id . "_" . $value . "_" . $nu_username . "_" . $username);
		}
    }
}

// print out the results
if( $results )
{
$cookie_value ="Unset";
setcookie(NAME_COOKIE,$cookie_value,time() + (-3600),"/", DOMAIN_COOKIE);
header("Location:$base_dir/login.php?conf=2&user=$nu_firstname");
}
else{
    die( "Trouble saving information to the database: " . mysql_error() ); }
}

// Build error message
$errors="<div id='errors'>Please address the following error(s):<ol> ";

	if($firstname_fail==104){$errors .= "<li>You must provide your First Name. It needs to be at least 2 characters long.</li>";}
	
	if($username_too_short==104){$errors .= "<li>The proposed new Screen Name is too short.  Please make it more than 4 characters.</li>";}
	
	if($username_already_in_use==104){$errors .= "<li>The proposed new Screen Name is already in use. This probably means you have an existing account with that name. <a href='login.php'>Log in</a> or <a href='email_password.php'>reset your password</a>.</li></li>";}

	if($email_already_in_use==104){$errors .= "<li>That Email is already in use. This probably means you have an existing account. <a href='login.php'>Log in</a> or <a href='email_password.php'>reset your password</a>.</li>";}

	if($pw_insecure==104){$errors .= "<li>Your Password is not formatted correctly.  Please choose a password that is between 4 and 20 characters and has at least one lower case letter and one number. (For example: <i>hello23</i>)</li>";}
	
    if($invalid_url==104){$errors .= "<li>Your URL is not properly formatted. Either leave it blank or format like this: <b>http://mysite.com</b>.</li>";}
	
    if($bad_email==104){$errors .= "<li>Your Email does not appear to be valid.</li>";}
	
$errors .= "</ol></div>";

?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Update User Profile - Errors</title>
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

  <form action="edit_profile_save.php" method="post" name="form" id="form">
    <H3><strong>Update Profile</strong></H3>
        <p><a href="index.php"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Back to MyTourney</font></a></p>
    <table width="474" border="0" cellspacing="0" cellpadding="5">
    <tr><td colspan="2"><span class="required">* - indicates required field</span>
    <tr class="shaded">
      <td width="50%">First Name <span class="required">*</span></td>
      <td><input type = "text" value="<?php echo $nu_firstname; ?>" name="nu_firstname" width="50" /></td>
    </tr>
    <tr>
      <td>Last Name<br />
      <small>Will not appear to the public</small></td>
      <td><input type = "text" value="<?php echo $nu_lastname; ?>" name="nu_lastname" width="50" /></td>
    </tr>

    <tr class="shaded">
      <td>Words With Friends Screen Name</td>
      <td><input type = "text" value="<?php echo $username; ?>" name="nu_username" width="50" /></td>
    </tr>
    <tr>
      <td><span style="color:#009900; font-weight:bold; font-style:italic;">New</span> Password - optional!</td>
      <td><input type = "password" value="" name="nu_password" width="50" /></td>
    </tr>
    <tr>
      <td colspan="2"><small>Required only if you wish to change it. Your password must be at least 4 characters long and contain at least 1 letter and 1 number. </small></td>
    </tr>
        <tr class="shaded">
      <td>Password Hint</td>
      <td><input type = "text" value="<?php echo $nu_password_hint; ?>" name="nu_password_hint" width="50" autocomplete="OFF" /></td>
    </tr>
    <tr>
      <td>Skill Level</td>
      <td><select name="nu_skill">
        <option <?php if ($nu_skill=="Beginner") {echo "selected";} ?> value="Beginner">Beginner</option><option <?php if ($nu_skill=="Intermediate") {echo "selected";} ?> value="Intermediate">Intermediate</option><option <?php if ($nu_skill=="Advanced") {echo "selected";} ?> value="Advanced">Advanced</option><option <?php if ($nu_skill=="Expert") {echo "selected";} ?> value="Expert">Expert</option></select></td>
    </tr>        

    <tr class="shaded">
      <td>Time Zone</td>
      <td>
        <select name="nu_timezone">
        <option <?php if ($nu_timezone=="Pacific") {echo "selected";} ?> value="Pacific">Pacific</option><option <?php if ($nu_timezone=="Eastern") {echo "selected";} ?> value="Eastern">Eastern</option><option <?php if ($nu_timezone=="Central") {echo "selected";} ?> value="Central">Central</option><option <?php if ($nu_timezone=="Mountain") {echo "selected";} ?> value="Mountain">Mountain</option><option <?php if ($nu_timezone=="Outside U.S.") {echo "selected";} ?> value="Outside U.S.">Non-US (specify in comments)</option></select></td>
    </tr>     
       
    <tr>
      <td>Device</td>
      <td>
        <select name="nu_device">
        <option <?php if ($nu_device=="iPhone") {echo "selected";} ?> value="iPhone">iPhone</option><option <?php if ($nu_device=="iPod Touch") {echo "selected";} ?> value="iPod Touch">iPod Touch</option><option <?php if ($nu_device=="iPad") {echo "selected";} ?> value="iPad">iPad</option><option <?php if ($nu_device=="Android") {echo "selected";} ?> value="Android">Android</option></select></td>
    </tr>

        <tr class="shaded">
      <td>Your URL<br />
      <small>Format like this 'http://mysite.com'</small></td>
      <td><input type = "text" value="<?php echo $nu_url; ?>" name="nu_url" width="50" autocomplete="ON" /></td>
    </tr>
    <tr>
      <td>Email <span class="required">*</span><br />
      <small>Required for tournament notifications</small></td>
      <td><input type = "text" value="<?php echo $nu_email; ?>" name="nu_email" width="50" autocomplete="ON" /></td>
    </tr>
    <tr class="shaded">
      <td>Phone</font></td>
      <td><input type = "text" value="<?php echo $nu_phone; ?>" name="nu_phone" width="50" autocomplete="ON" /></td>
    </tr>

        <tr>
<td>Privacy</td>      
        <td>
<input type="checkbox" name="nu_privacy" value="on" <?php if ($nu_privacy=="on") {echo "checked='checked'";} ?>  />
Hide my contact info<br />
 </td></tr>
 <tr><td colspan="2"><small>Checking this box will hide your phone number and email address from your opponents. We do not recommend doing this, as it makes it harder to coordinate games.</small></td>
    </tr>  

        <tr class="shaded">
      <td>Profile Comment <br />
        <small>Visible to everyone; max 140 characters.</small></td>
      <td>
<textarea name="nu_comment" maxlength="140"><?php echo $nu_comment; ?></textarea><input type="hidden" name="nu_id" value="<?php echo $id; ?>" />
 </td>
    </tr>     
  </table>
  <p><input type="submit" value="Save and Continue" name="submit2" /></p>
  </form>

<?php echo $errors; ?>

</div>
</body>
</html>