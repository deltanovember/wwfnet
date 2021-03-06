<?php

//Set permission level threshold for this page remove if page is good for all levels
$permission_level=5;

include "../auth_check_header.php";

$id = $_REQUEST['id'];

// GET CURRENT USER'S VALUES AND ASSIGN SESSION VARIABLES
			
$query = "SELECT * FROM users WHERE id='$id'"; 

// get results
$result = mysql_query($query) or die("Couldn't execute query");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

$username= $row["username"];
$password_hint= $row["password_hint"];
$lastname= $row["lastname"];
$firstname= $row["firstname"];
$phone= $row["phone"];
$email= $row["email"];
$permissions = $row["permissions"];
$email_sub = substr($email, 0, 50);
$privacy= $row["privacy"];
$timezone= $row["timezone"];
$comment= $row["comment"];
$skill= $row["skill"];
$device= $row["device"];
$url= $row["url"];

}
?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Update User Profile</title>
<style type="text/css">
#wrap {font-family:Arial, Helvetica, sans-serif; font-size:13px; width:506px;}
#mymenu {float:right;}
.shaded {background-color:#ebebeb;}
.required {color:#FF0000; font-weight:bold;}
</style>

</head>

<body topmargin="0">

<div id="wrap">
<div id="mymenu"><a href="admin_index.php">Admin Index</a> | <a href="logout.php">Log Out</a></div>      

  <form action="edit_user_save.php" method="post" name="form" id="form">
    <H3><strong>Update Profile</strong></H3>
    <table width="474" border="0" cellspacing="0" cellpadding="5">
    <tr><td colspan="2"><span class="required">* - indicates required field</span>
    <tr class="shaded">
      <td width="50%">First Name <span class="required">*</span></td>
      <td><input type = "text" value="<?php echo $firstname; ?>" name="nu_firstname" width="50" /></td>
    </tr>
    <tr>
      <td>Last Name<br />
      <small>Will not appear to the public</small></td>
      <td><input type = "text" value="<?php echo $lastname; ?>" name="nu_lastname" width="50" /></td>
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
      <td><input type = "text" value="<?php echo $password_hint; ?>" name="nu_password_hint" width="50" autocomplete="OFF" /></td>
    </tr>
    <tr>
      <td>Skill Level</td>
      <td><select name="nu_skill">
        <option <?php if ($skill=="Beginner") {echo "selected";} ?> value="Beginner">Beginner</option><option <?php if ($skill=="Intermediate") {echo "selected";} ?> value="Intermediate">Intermediate</option><option <?php if ($skill=="Advanced") {echo "selected";} ?> value="Advanced">Advanced</option><option <?php if ($skill=="Expert") {echo "selected";} ?> value="Expert">Expert</option></select></td>
    </tr>        

    <tr class="shaded">
      <td>Time Zone</td>
      <td>
        <select name="nu_timezone">
        <option <?php if ($timezone=="Pacific") {echo "selected";} ?> value="Pacific">Pacific</option><option <?php if ($timezone=="Eastern") {echo "selected";} ?> value="Eastern">Eastern</option><option <?php if ($timezone=="Central") {echo "selected";} ?> value="Central">Central</option><option <?php if ($timezone=="Mountain") {echo "selected";} ?> value="Mountain">Mountain</option><option <?php if ($timezone=="Outside U.S.") {echo "selected";} ?> value="Outside U.S.">Non-US (specify in comments)</option></select></td>
    </tr>     
       
    <tr>
      <td>Device</td>
      <td>
        <select name="nu_device">
        <option <?php if ($device=="iPhone") {echo "selected";} ?> value="iPhone">iPhone</option><option <?php if ($device=="iPod Touch") {echo "selected";} ?> value="iPod Touch">iPod Touch</option><option <?php if ($device=="iPad") {echo "selected";} ?> value="iPad">iPad</option></select></td>
    </tr>

        <tr class="shaded">
      <td>Your URL<br />
      <small>Format like this 'http://mysite.com'</small></td>
      <td><input type = "text" value="<?php echo $url; ?>" name="nu_url" width="50" autocomplete="ON" /></td>
    </tr>
    <tr>
      <td>Email <span class="required">*</span><br />
      <small>Required for tournament notifications</small></td>
      <td><input type = "text" value="<?php echo $email; ?>" name="nu_email" width="50" autocomplete="ON" /></td>
    </tr>
    <tr class="shaded">
      <td>Phone</font></td>
      <td><input type = "text" value="<?php echo $phone; ?>" name="nu_phone" width="50" autocomplete="ON" /></td>
    </tr>

        <tr>
<td>Privacy</td>      
        <td>
<input type="checkbox" name="nu_privacy" value="on" <?php if ($privacy=="on") {echo "checked='checked'";} ?>  />
Hide my contact info<br />
 </td></tr>
 <tr><td colspan="2"><small>Checking this box will hide your phone number and email address from your opponents. We do not recommend doing this, as it makes it harder to coordinate games.</small></td>
    </tr>  

        <tr class="shaded">
      <td>Profile Comment <br />
        <small>Visible to everyone; max 140 characters.</small></td>
      <td>
<textarea name="nu_comment" maxlength="140"><?php echo $comment; ?></textarea><input type="hidden" name="nu_id" value="<?php echo $id; ?>" />
 </td>
    </tr>     
  </table>
  <p><input type="submit" value="Save and Continue" name="submit2" /></p>
  </form>
  
</div>
</body>
</html>