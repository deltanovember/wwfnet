<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Register a User</title>
<style type="text/css">
#wrap {font-family:Verdana, Arial, Helvetica, sans-serif; font-size:12px; width:515px;}
.shaded {background-color:#ebebeb;}
.required {color:#FF0000; font-weight:bold;}
</style>

</head>

<body topmargin="0">

<div id="wrap" align="center">

  <form action="user_add_save.php" method="post" name="form" id="form">
    <H3><strong>Register a Player</strong></H3>
        <p><a href="login.php"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Back to Login</font></a></p>
    <table width="474" border="0" cellspacing="0" cellpadding="5">
    <tr><td colspan="2"><span class="required">* - indicates required field</span><br /><br />
            <strong>Please note you must have an 
                <a href="http://www.amazon.com/gp/product/B0041E16RC?ie=UTF8&amp;tag=worwitfri-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B0041E16RC" target="_blank">iPhone</a>,
               <a href="http://www.amazon.com/gp/product/B001FA1O1S?ie=UTF8&amp;tag=worwitfri-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B001FA1O1S" target="_blank"> iPod touch</a>,
              <a href="http://www.amazon.com/gp/product/B00365F6LE?ie=UTF8&amp;tag=worwitfri-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B00365F6LE" target="_blank"> iPad</a> or
             <a href="http://www.amazon.com/gp/product/B003N17IJ4?ie=UTF8&amp;tag=worwitfri-20&amp;linkCode=as2&amp;camp=1789&amp;creative=9325&amp;creativeASIN=B003N17IJ4" target="_blank"> Android</a> to participate in tournaments</strong>
            <br /><br />
    <tr class="shaded">
      <td>First Name <span class="required">*</span></td>
      <td><input type = "text" value="<?php if (isset ($firstname)) echo $firstname; ?>" name="firstname" width="50" /></td>
    </tr>
    <tr>
      <td>Last Name<br /><small>(Will not appear to the public)</small></td>
      <td><input type = "text" value="<?php if (isset ($lastname)) echo $lastname; ?>" name="lastname" width="50" /></td>
    </tr>

    <tr class="shaded">
      <td>Words With Friends Screen Name <span class="required">*</span></td>
      <td><input type = "text" value="<?php if (isset ($username)) echo $username; ?>" name="username" width="50" /></td>
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
      <td><input type = "text" value="<?php if (isset ($password_hint)) echo $password_hint; ?>" name="password_hint" width="50" autocomplete="OFF" /></td>
    </tr>
    <tr>
      <td>Skill Level <span class="required">*</span></td>
      <td><select name="skill">
        <option <?php if (isset ($skill) && $skill=="Beginner") {echo "selected";} ?> value="Beginner">Beginner</option><option <?php if (isset ($skill) && $skill=="Intermediate") {echo "selected";} ?> value="Intermediate">Intermediate</option><option <?php if (isset ($skill) && $skill=="Advanced") {echo "selected";} ?> value="Advanced">Advanced</option><option <?php if (isset ($skill) && $skill=="Expert") {echo "selected";} ?> value="Expert">Expert</option></select></td>
    </tr>        

    <tr class="shaded">
      <td>Time Zone <span class="required">*</span></font></td>
      <td>
        <select name="timezone">
        <option <?php if (isset ($timezone) && $timezone=="Pacific") {echo "selected";} ?> value="Pacific">Pacific</option><option <?php if (isset ($timezone) && $timezone=="Eastern") {echo "selected";} ?> value="Eastern">Eastern</option><option <?php if (isset ($timezone) && $timezone=="Central") {echo "selected";} ?> value="Central">Central</option><option <?php if (isset ($timezone) && $timezone=="Mountain") {echo "selected";} ?> value="Mountain">Mountain</option><option <?php if (isset ($timezone) && $timezone=="Outside U.S.") {echo "selected";} ?> value="Outside U.S.">Non-US (specify in comments)</option></select></td>
    </tr>     
       
    <tr>
      <td>Device <span class="required">*</span></td>
      <td>
        <select name="device">
        <option <?php if (isset ($device) && $device=="iPhone") {echo "selected";} ?> value="iPhone">iPhone</option><option <?php if (isset ($device) && $device=="iPod Touch") {echo "selected";} ?> value="iPod Touch">iPod Touch</option><option <?php if (isset ($device) && $device=="iPad") {echo "selected";} ?> value="iPad">iPad</option><option <?php if (isset ($device) && $device=="Android") {echo "selected";} ?> value="Android">Android</option></select></td>
    </tr>

        <tr class="shaded">
      <td>Your URL<br /><small>(include 'http://')</small></td>
      <td><input type = "text" value="<?php if (isset ($url)) echo $url; ?>" name="url" width="50" autocomplete="ON" /></td>
    </tr>
    <tr>
      <td>Email <span class="required">*</span><br />
      <small>(Required for tournament notifications)</small></td>
      <td><input type = "text" value="<?php if (isset ($email)) echo $email; ?>" name="email" width="50" autocomplete="ON" /></td>
    </tr>
    <tr class="shaded">
      <td>Phone</font></td>
      <td><input type = "text" value="<?php if (isset ($phone)) echo $phone; ?>" name="phone" width="50" autocomplete="ON" /></td>
    </tr>

        <tr>
<td>Privacy</td>      
        <td>
            <input type="checkbox" name="privacy" value="on" <?php if (isset ($privacy) && $privacy=="on") {echo "checked='checked'";} ?>  />
Hide my contact info<br />
 </td></tr>
 <tr><td colspan="2"><small>Checking this box hides your phone number and email address from your opponents. We recommend this for your privacy.</small></td>
    </tr>  

        <tr class="shaded">
      <td>Profile Comment <br />
        <small>Visible to everyone; max 140 characters.</small></td>
      <td>
<textarea name="comment" id="comment" maxlength="140"><?php if (isset ($comment)) echo $comment; ?></textarea>
 </td>
    </tr>  
    
    </tr>  
<tr><td><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /></td><td>Input the code <span class="required">*</span>:</font>&nbsp; <input type="text" name="captcha_code" size="10" maxlength="6" /></td></tr>    
  </table>
  <p><input type="submit" value="Save and Continue" name="submit2" /></p>
  </form>
  
</div>
</body>
</html>