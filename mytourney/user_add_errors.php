
<div align="center">

  <table width="500" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td><font size='2' color='#ff0000' face='Verdana, Arial, Helvetica, sans-serif'>Please try again and address the following error(s):</font>
	  <ol><?
    $username_already_in_use = $_REQUEST['username_already_in_use'];
	$email_already_in_use = $_REQUEST['email_already_in_use'];
	$pw_insecure = $_REQUEST['pw_insecure'];
	$bad_email = $_REQUEST['bad_email'];
	$username_too_short = $_REQUEST['username_too_short'];
	$firstname_fail = $_REQUEST['firstname_fail'];
	$invalid_url = $_REQUEST['invalid_url'];
	


	if($firstname_fail==104){echo"<li><font size='2' color='#ff0000' face='Verdana, Arial, Helvetica, sans-serif'>
You must provide your First Name. It needs to be at least 2 characters long.</font></li>";}
	
	if($username_too_short==104){echo"<li><font size='2' color='#ff0000' face='Verdana, Arial, Helvetica, sans-serif'>
That Screen Name is too short.  Please make it more than 4 characters.</font></li>";}
	
	if($username_already_in_use==104){echo"<li><font size='2' color='#ff0000' face='Verdana, Arial, Helvetica, sans-serif'>
That username is already in use.  Please try again or log in to your existing account.</font></li>";}

	if($email_already_in_use==104){echo"<li><font size='2' color='#ff0000' face='Verdana, Arial, Helvetica, sans-serif'>
That email is already in use.  That probably means you have an existing account. Log in or <a href='email_password.php'>reset your password</a></font></li>";}

	if($pw_insecure==104){echo"<li><font size='2' color='#ff0000' face='Verdana, Arial, Helvetica, sans-serif'>
Your Password is not formatted correctly.  Please choose a password that is between 4 and 20 characters and has at least one lower case letter and one number. (For example: <i>hello23</i>)</font></li>";}
	
    if($invalid_url==104){echo"<li><font size='2' color='#ff0000' face='Verdana, Arial, Helvetica, sans-serif'>
Your URL is not properly formatted. Either leave it blank or format like this: <b>http://mysite.com</b>.</font></li>";}
	
    if($bad_email==104){echo"<li><font size='2' color='#ff0000' face='Verdana, Arial, Helvetica, sans-serif'>
Your Email does not appear to be valid.</font></li>";}

    if($captcha_failed==104){echo"<li><font size='2' color='#ff0000' face='Verdana, Arial, Helvetica, sans-serif'>
You entered the wrong Image Verification text.<br><br></font></li>";}


  ?></ol></td>
    </tr>
  </table>
  
  <form action="user_add_save.php" method="post" name="form" id="form">
    <p><strong><font size="4" face="Verdana, Arial, Helvetica, sans-serif">Register a User</font></strong><br />
        <a href="login.php"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Back to Login</font></a></p>
    <table width="474" border="0" cellspacing="0" cellpadding="5">
    <tr>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">First Name </font></td>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <input type = "text" value="<? echo$firstname; ?>" name="firstname" width="50" />
      </font></td>
    </tr>
            <tr>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Last Name<br /><small>(Will not appear to the public)</small> </font></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <input type = "text" value="<? echo$lastname; ?>" name="lastname" width="50" />
      </font></td>
    </tr>

    <tr>
      <td bgcolor="ebebeb" width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">WordsWithFriends Screen Name</font></td>
      <td bgcolor="ebebeb" width="50%"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <input type = "text" value="<? echo$username; ?>" name="username" width="50" />
      </font></td>
    </tr>
                  <tr bgcolor="ebebeb"><td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><small>Important: Do NOT include emoticons <img src="http://wordswithfriends.net/wp-includes/images/smilies/icon_wink.gif" /> Also, you MUST use the same nickname for your iPhone games (e.g., "goodplayer23")</small></font></td></tr>
    <tr>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password</font></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <input type = "password" value="<? echo$password; ?>" name="password" width="50" />
      </font></td>
    </tr>
    <tr>
      <td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><small>Your password must be at least 4 characters long and contain at least 1 letter and 1 number. Also, bear in mind that this password has NOTHING to do with your wwf application login; it will be the password for this site only.</small></font></td>
    </tr>
        <tr>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Password Hint </font></td>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <input type = "text" value="<? echo$password_hint; ?>" name="password_hint" width="50" autocomplete="OFF" />
      </font></td>
    </tr>
    <tr>
      <td bgcolor=""><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Skill Level</font></td>
      <td bgcolor=""><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <select name="skill">
        <option <? if ($skill=="Beginner") {echo "selected";} ?> value="Beginner">Beginner</option><option <? if ($skill=="Intermediate") {echo "selected";} ?> value="Intermediate">Intermediate</option><option <? if ($skill=="Advanced") {echo "selected";} ?> value="Advanced">Advanced</option><option <? if ($skill=="Expert") {echo "selected";} ?> value="Expert">Expert</option></select>
      </font></td>
    </tr>        

    <tr>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Time Zone</font></td>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <select name="timezone">
        <option <? if ($timezone=="Pacific") {echo "selected";} ?> value="Pacific">Pacific</option><option <? if ($timezone=="Eastern") {echo "selected";} ?> value="Eastern">Eastern</option><option <? if ($timezone=="Central") {echo "selected";} ?> value="Central">Central</option><option <? if ($timezone=="Mountain") {echo "selected";} ?> value="Mountain">Mountain</option><option <? if ($timezone=="Outside U.S.") {echo "selected";} ?> value="Outside U.S.">Non-US (specify in comments)</option></select>
      </font></td>
    </tr>     
       
    <tr>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Device</font></td>
      <td>
        <select name="device">
        <option <? if ($device=="iPhone") {echo "selected";} ?> value="iPhone">iPhone</option><option <? if ($device=="iPodTouch") {echo "selected";} ?> value="iPodTouch">iPod Touch</option><option <? if ($device=="iPad") {echo "selected";} ?> value="iPad">iPad</option></select></td>
    </tr>

        <tr>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Your URL - optional</font></td>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <input type = "text" value="<? echo $url; ?>" name="url" width="50" autocomplete="ON" />
      </font></td>
    </tr>

    
    <tr>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Email<br />
      <small>(Required for tournament notifications)</small></font></td>
      <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <input type = "text" value="<? echo $email; ?>" name="email" width="50" autocomplete="ON" />
      </font></td>
    </tr>
    <tr>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Phone - optional</font></td>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <input type = "text" value="<? echo$phone; ?>" name="phone" width="50" autocomplete="ON" />
      </font></td>
    </tr>

        <tr>
<td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Privacy</font></td>      
        <td>
<input type="checkbox" <? if ($privacy=="yes") {echo "checked";} ?> name="privacy" />
<font size="2" face="Verdana, Arial, Helvetica, sans-serif">Hide my contact info<br /></font>
 </td></tr>
 <tr><td colspan="2"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><small>Checking this box hides your phone number and email address from your opponents. We recommended this for your privacy.</small></font></td>
    </tr>  

        <tr>
      <td bgcolor="ebebeb"><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Profile Comment <br />
        <small>Visible to everyone; max 140 characters.</small></font></td>
      <td bgcolor="ebebeb">
<textarea name="comment" id="comment" maxlength="140"><? echo$comment; ?></textarea>
 </td>
    </tr>  
    
    </tr>  
<tr><td><img id="captcha" src="securimage/securimage_show.php" alt="CAPTCHA Image" /></td><td><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Input the code:</font>&nbsp; <input type="text" name="captcha_code" size="10" maxlength="6" /></td></tr>    

  </table>
  <p><font size="1" face="Arial, Helvetica, sans-serif">
    <input type="submit" value="Save and Continue" name="submit2" />
  </font></p>
  </form>

<p style="color:red;">Your attempted submission had errors (see top of page).</p>
</div>
