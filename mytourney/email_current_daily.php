<?php


include "login_config.php";
include "email_smtp.php";

   // connect to the server
   mysql_connect( $db_host, $db_username, $db_password )
      or die( "Error! Could not connect to database: " . mysql_error() );

   // select the database
   mysql_select_db( $db )
      or die( "Error! Could not select the database: " . mysql_error() );

$players = "Ahol1616;Nlindsay586;angeloyster;Domrandazzo;AtomicPopcornDude;Area51official;audra10;Gregoaks;ayocoker;MMaple;Azianbutterfly;allisonrose1;Badspeller22;Flipchickmc;baldheaddred;joe445;blackjag;Miss_Queen_Bee;Blackjh;jessicafm;blzlovr;timblamo;Bradinwoods;mcook77;BShaw94;ravi285;CaptainWylie;Another Dude;Ccg753;LS3000;chinpie;I Spelled;Chuckburro;SSElisabeth;Cindyalex;Miikal1;comedypace;ashleybaby17;Corgi Lover;girlspazdog;cougar48;Claytini;DaFlySwatta;Squirrel Girl;Daniel McKearan;mo818;Doripiz;Lmarie;Eastwoman;dc12;eeceebee;iJenQ;eeyore 33;zzinggs;emmafudd;CDMSL;Evil, PhD;hbludman;gogogo4;benjamyn;Heagle;Rad333;Hsoy;Insane inspector;ItsSSS;Biff Krunchy;jcom10;Dieu de Louange;kenjos;CherryBum2;KevOCCRN;PW1606;ledforddds;Nixster!;liliavo;Venus66;Lisajt77;grizzgrazz;LLM96;JayHutchinson;luvs2laugh;Chele312;Luvthisgame;Kevin.Redmond;Maaria786;okmax;masher44;DarthVader707;meganharnett;jajajime;MegS626;aeadon;Miamiheat8;myrna17;Missyj15;gd929;Mmmarcus;Nicoles742;mom2boys3girls;Jalloken;MrRager15;Tad Man;mrsaltmiller;amalia411;Namaste;Frozen Shade;nicsmith25;sunnyplaya;Opus61;dikangelo;polymath3;XFeral;rjk1107;whoneycu;schristian1;Tracy72903;Scotty1980;@peace;SGJade;Tmaxy;speller wex;Hawaiiangirl64;Steve18);kcphua1;stevieboy123;Debbyoc;The Gentleman Surfer;Michelechell;Trueheartd;Jane Champ;Tweety2;Grifffish;TwistyTiler;colonelosu;TXplorer;Birds5;valcharles;Kitkat7145;Vyper911;Artemis;";
$shortplayers = "Ahol1616;Nlindsay586;angeloyster;Domrandazzo;AtomicPopcornDude;Area51official";

$exploded = explode(';', $shortplayers);

for ($i = 0;$i < count($exploded) - 1; $i+=2) {

    print $exploded[$i].",";
    $sql = "select email, firstname from users where username='$exploded[$i]'";

    $result = mysql_query($sql) or die("Couldn't execute $sql");
$new_round = 18;
$t_desc = "Battle for America [daily]";

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {
   
    $fname0 = $row['firstname'];
    $person0 = $exploded[$i];
$versus_sn = $exploded[$i + 1];
    	$from = "Admin <admin@wordswithfriends.net>";
	$reply_to = "no-reply@wordswithfriends.net";
	$return_path = "no-reply@wordswithfriends.net";

	$to = $email0;

	$subject = "[WordsWithFriends.net] Your next opponent is '" . $versus_sn . "'";


	$mailbody = "Dear " . $fname0 . " ('" . $person0 . "'): <br><br>'" . $versus_sn . "' is your Round " . $new_round . " opponent in the " . $t_desc . "! You can contact '" . $versus_sn . "' and take other actions at your <a href='http://wordswithfriends.net/?page_id=386'>MyTourney page</a> (Password hint: '" . $hint0 . "').<br /><br /><em>NOTE: This round will close approximately 24 hours from this email.</em><br><br>Please do not reply to this message. Thanks for playing!";

	//____________________________Begin Multipart Mail Sender
	//add From: header
	$headers = "From:$from\nReply-to:$reply_to\nReturn-path:$return_path\nJobID:".time()."\n";

	//specify MIME version 1.0
	$headers .= "MIME-Version: 1.0\n";

	//unique boundary
	$boundary = uniqid("HTMLDEMO8656856");

	//tell e-mail client this e-mail contains//alternate versions
	$headers.="X-Priority: 3\n";
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
        base_mail("polymath333@yahoo.ca", $subject, $mailbody);
        //mail("polymath3333@hotmail.com", $subject, "", $headers);
       // my_mail("polymath3333@hotmail.com", $subject, $mailbody);
        die();
}







    print $exploded[$i+1].",";
    $sql = "select email, firstname from users where username='".$exploded[$i+1]."'";
        $result = mysql_query($sql) or die("Couldn't execute $sql");

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $fname0 = $row['firstname'];
    $person0 = $exploded[$i+1];
$versus_sn = $exploded[$i];
    	$from = "Admin <admin@wordswithfriends.net>";
	$reply_to = "no-reply@wordswithfriends.net";
	$return_path = "no-reply@wordswithfriends.net";

	$to = $email0;

	$subject = "[WordsWithFriends.net] Your next opponent is '" . $versus_sn . "'";


	$mailbody = "Dear " . $fname0 . " ('" . $person0 . "'): <br><br>'" . $versus_sn . "' is your Round " . $new_round . " opponent in the " . $t_desc . "! You can contact '" . $versus_sn . "' and take other actions at your <a href='http://wordswithfriends.net/?page_id=386'>MyTourney page</a> (Password hint: '" . $hint0 . "').<br /><br /><em>NOTE: This round will close approximately 24 hours from this email.</em><br><br>Please do not reply to this message. Thanks for playing!";

	//____________________________Begin Multipart Mail Sender
	//add From: header
	$headers = "From:$from\nReply-to:$reply_to\nReturn-path:$return_path\nJobID:".time()."\n";

	//specify MIME version 1.0
	$headers .= "MIME-Version: 1.0\n";

	//unique boundary
	$boundary = uniqid("HTMLDEMO8656856");

	//tell e-mail client this e-mail contains//alternate versions
	$headers.="X-Priority: 3\n";
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

        mail($row['email'], $subject, "", $headers);



}


}
?>



