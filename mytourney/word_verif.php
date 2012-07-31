<?php 

include"master_inc.php"; 


$word1 = strip_tags(substr($_GET['w1'],0,25));
$word2 = strip_tags(substr($_GET['w2'],0,25));
$word3 = strip_tags(substr($_GET['w3'],0,25));
$word4 = strip_tags(substr($_GET['w4'],0,25));

function check($thisword) {
$query = "SELECT * FROM `allwords` WHERE word = '$thisword'"; 
$result = mysql_query($query) or die("Couldn't execute query");
$count=mysql_num_rows($result);
if($count>0){
$msg = "<span style='color:green; font-weight:bold;'>'" . $thisword . "' is valid</span><br /><em>See</em>: <a href='http://www.dictionary.reference.com/browse/" . $thisword . "' target='_blank'>Dictionary.com</a> | <a href='http://www.merriam-webster.com/dictionary/" . $thisword . "' target='_blank'>Merriam-Webster</a><br /><br />";
}

else {
$msg = "<span style='color:red; font-weight:bold;'>'" . $thisword . "' is invalid</span><br /><br /><script type='text/javascript'>alert('\'" . $thisword . "\' is not a valid word in WordsWithFriends.');</script>";
}

echo $msg;

}


?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
<head>
<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
<title>Tournament Results</title>

<style type="text/css">
#wrap {font-family:Arial, Helvetica, sans-serif; font-size:12px;}
#mymenu {float:right;}
</style>

</head>
<body topmargin="0">
<div id='wrap'>
<? if ($word1 == ''){echo '<p style="margin-top:0px">Enter a suspect word to be validated.</p>';} ?>
<form action="word_verif.php" method="get">
<input style="float:left;" type="text" name="w1" />&nbsp;<input type="submit" value="Check" />
</form><br />

<?
$arr = array($word1, $word2, $word3, $word4);
foreach ($arr as $value) {
if ($value != ""){
check($value);
	}
}

?>
</div>
</body>
</html>