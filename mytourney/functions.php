<?php
// Explicitly declare $db to avoid wordpress clashes
require_once 'login_config.php';
require_once 'classes.php';

if (!$debugmode) {
    require_once 'email_smtp.php';
}


// connect to the server
//mysql_connect( $db_host, $db_username, $db_password )
 //   or die( "Error! Could not connect to database: " . mysql_error() );

/* 
 * Common library files
 */

function count_daily_matches($user_id, $tournament_id) {
    $query = "SELECT count(match_id) as num_matches FROM `daily`
    WHERE tournament_id=$tournament_id and user_id=$user_id and result <> ''";

    // get results
    $result = mysql_query($query) or die("Couldn't execute query in countDailyMatches $query");

    while ($row= mysql_fetch_array($result)) {
        // return first value
        return $row["num_matches"];

    }

}

function deactivate_user($user_id) {
    $query = "update users set 
                daily_status='Eliminated', verified=0
            WHERE id=$user_id";

    // get results
    mysql_query($query) or die("unable to deacivate $query");

}

// send email for daily match ups
function emailPlayers($person0,$fname0,$versus0,$email0,$onround,$t_desc){

    global $debugmode;

    $from = "Admin <admin@wordswithfriends.net>";
    $reply_to = "no-reply@wordswithfriends.net";
    $return_path = "no-reply@wordswithfriends.net";

    $to = $email0;

    $subject = "[WordsWithFriends.net] Your Round " . $onround . " Opponent";


    $mailbody = "Dear " . $fname0 . " (" . $person0 . "): <br><br>Round " . $onround . " of the " . $t_desc . " is underway! Your next tournament opponent is '" . $versus0 . ".' You can contact your opponent and take other actions at your MyTourney page. Log in at your <a href=\"http://wordswithfriends.net/?page_id=386\">MyTourney</a> page . Please note that you must already have the game Words With Friends installed to participate in our tournaments. <br><br>Thanks for playing!";

    if (!$debugmode) {
        if (email($to, $subject, $mailbody)){
            $msg_conf = "<p style='background-color:lightyellow; padding:5px;'><span style='font-weight:bold;'>Email message was sent to " . $person0 . ".</span></p>";
        }
        else {
            $msg_conf = "<p style='background-color:lightyellow; padding:5px; font-weight:bold; color:red;'>Email not sent. Please check to see if you have been assigned an opponent for this round. If not, then contact admin@wordswithfriends.net for assistance. Otherwise, you can ignore this message.</span>";

            }

       // echo $msg_conf;

    }
    else {
        print $mailbody;
    }
}

function format_email($info, $format){

	//set the root
	//$root = $_SERVER['DOCUMENT_ROOT'].'/dev/tutorials/email_signup';

	//grab the template content
	$template = file_get_contents('signup_template.'.$format);

	//replace all the tags
	$template = ereg_replace('{USERNAME}', $info['username'], $template);
	$template = ereg_replace('{EMAIL}', $info['email'], $template);
	$template = ereg_replace('{KEY}', $info['key'], $template);
	$template = ereg_replace('{SITEPATH}','http://www.wordswithfriends.net/mytourney', $template);

	//return the html of the template
	return $template;

}


/**
 * Get latest daily tournament ID
 */
function get_daily_id() {

    $db = $GLOBALS['db_name'];
   $query = "SELECT * FROM $db.T_CONTROL WHERE `t_id` LIKE 'D20%' AND `t_status` <> 'Archive' AND `t_status` <> 'Complete' ORDER BY `t_start` DESC";

    // get results
    $result = mysql_query($query) or die("Couldn't execute query in get_daily_id $query");

    while ($row= mysql_fetch_array($result)) {
        // return first value
        return $t_id= $row["t_id"];

    }
}

function get_daily_record() {
    $db = $GLOBALS['db_name'];
   $query = "SELECT * FROM $db.T_CONTROL WHERE `t_id` LIKE 'D20%' AND `t_status` <> 'Archive' AND `t_status` <> 'Complete' ORDER BY `t_start` DESC";

    // get results
    $result = mysql_query($query) or die("Couldn't execute query in get_daily_id $query");

    while ($row= mysql_fetch_array($result)) {
        // return first value
        return $t_id= $row["record"];

    }
}

/**
 *
 * @param <type> $user_id
 * @param <type> $round
 * @param <type> $tournament_id
 * @return <type> user object
 */
function get_daily_opponent($user_id, $round, $tournament_id) {
    $db = $GLOBALS['db_name'];
    $query = "SELECT user_id FROM $db.daily
        WHERE match_id in (SELECT match_id FROM $db.daily WHERE user_id=$user_id and round=$round and tournament_id=$tournament_id)
        and user_id <> $user_id";

    // get results
    $result = mysql_query($query) or die("Couldn't execute query in get_daily_opponent ".  mysql_error());

    while ($row= mysql_fetch_array($result)) {
        // return first value
        return $t_id= $row['user_id'];

    }
}

function get_daily_round() {

    $db = $GLOBALS['db_name'];
   $query = "SELECT * FROM $db.T_CONTROL WHERE `t_id` LIKE 'D20%' AND `t_status` <> 'Archive' AND `t_status` <> 'Complete' ORDER BY `t_start` DESC";

    // get results
    $result = mysql_query($query) or die("Couldn't execute query in get_daily_id $query");

    while ($row= mysql_fetch_array($result)) {
        // return first value
        return $row["t_round"];

    }
}

/**
 * get max id from daily table
 * @return <type>
 */
function get_maximum_daily_id() {

   $db = $GLOBALS['db_name'];
   $query = "SELECT max(match_id) FROM $db.daily";

    // get results
    $result = mysql_query($query) or die("Couldn't execute query in get_daily_id $query");

    if (count($result) == 0) {
        return 0;
    }
    else {
        
        while ($row= mysql_fetch_array($result)) {
            return $row[0];
        }
        
    }
    
}

function get_maximum_tournament_id() {

   $db = $GLOBALS['db_name'];
   $query = "SELECT max(record) FROM $db.T_CONTROL";

    // get results
    $result = mysql_query($query) or die("Couldn't execute query in get_maximum_tournament_id $query");

    if (count($result) == 0) {
        return 0;
    }
    else {

        while ($row= mysql_fetch_array($result)) {
            return $row[0];
        }

    }

}

/**
 * Get latest daily tournament ID
 */
function get_monthly_record() {

    $db = $GLOBALS['db_name'];
   $query = "SELECT * FROM $db.T_CONTROL WHERE `t_id` LIKE 'M20%' AND `t_status` <> 'Archive' AND `t_status` <> 'Complete' ORDER BY `t_start` DESC";

    // get results
    $result = mysql_query($query) or die("Couldn't execute query in get_daily_id $query");

    while ($row= mysql_fetch_array($result)) {
        // return first value
        return $t_id= $row["record"];

    }
}

/**
 *
 * @return open tournament or else NULL if none found
 */
function get_open_monthly() {

   $db = $GLOBALS['db_name'];
   $query = "SELECT * FROM $db.T_CONTROL where t_status='Register'";

    // get results
    $result = mysql_query($query) or die("Couldn't execute query in get_daily_id $query");

    if (count($result) == 0) {
        return NULL;
    }
    else {

        while ($row= mysql_fetch_array($result)) {
           // print get_tournament($row['t_id'])->get_description();
            //die();
            return get_tournament($row['t_id']);
        }

    }

}


function get_opponent() {


}

/**
 * get single tourney record
 */
function get_record($tournament_id, $player_id) {

    $db = $GLOBALS['db_name'];
    $tournament = get_tournament($tournament_id);
    $tournament_id = $tournament->get_record();
    $current_round = get_daily_round();
    
    // Find out if player is already registered for this tourney
    $query = "SELECT * FROM $db.daily WHERE user_id='$player_id' and tournament_id=$tournament_id and round=$current_round";

    $result = mysql_query($query) or die("Couldn't execute query $query");
    $count=mysql_num_rows($result);
    if($count <= 0){
        return NULL;
    }
    // if tourney is not in progress
    if ($tournament->get_status() != "In Progress" &&
            $tournament->get_status() != "Register"){
        return NULL;

     }

     $record = NULL;

    // now you can display the results returned
    while ($row= mysql_fetch_array($result)) {

        $record = new Record($row["match_id"]);
        $record->set_round($row["round"]);
        $opponent_id = get_daily_opponent($player_id, $row["round"], $tournament_id);
        if (!$opponent_id) {
            return NULL;
        }
        $record->set_opponent(get_user_from_id($opponent_id)->get_screen_name());
        //$record->set_status($row["status"]);
        //$player= $row["player"];
        $admcmt= $row["admin_comment"];
        $p_msg= $row["private_message"];
        $p1_g1= $row["score"];
        $p1_rslt= $row["result"];
        $p1_cmt= $row["comment"];

    }

    return $record;
}

function get_tournament($tournament_id) {

    $db = $GLOBALS['db_name'];
    $tournament = new Tournament($tournament_id);
    $query = "SELECT * FROM $db.T_CONTROL WHERE t_id='$tournament_id'";
    $result = mysql_query($query) or die("Couldn't execute $query in function get_tournament");

    while ($row= mysql_fetch_array($result)) {

        $tournament->set_champion($row["t_champ"]);
        $tournament->set_descripion($row["t_desc"]);
        $tournament->set_record($row["record"]);
        $tournament->set_round($row["t_round"]);
        $tournament->set_start($row["t_start"]);
        $tournament->set_status($row["t_status"]);

    }

    return $tournament;

}

function get_tournament_from_record($record_id) {

    $db = $GLOBALS['db_name'];
    $tournament = new Tournament($record_id);
    $query = "SELECT * FROM $db.T_CONTROL WHERE record=$record_id";
    $result = mysql_query($query) or die("Couldn't execute $query in function get_tournament");

    while ($row= mysql_fetch_array($result)) {


        $tournament->set_id($row["t_id"]);
        $tournament->set_champion($row["t_champ"]);
        $tournament->set_descripion($row["t_desc"]);
        $tournament->set_record($row["record"]);
        $tournament->set_round($row["t_round"]);
        $tournament->set_start($row["t_start"]);
        $tournament->set_status($row["t_status"]);

    }

    return $tournament;

}
/**
 *
 * @param <type> $tournament_id
 * @param <type> $round
 * @return <type> array with user_id, username
 */
function get_unmatched_daily_players($tournament_id, $round) {
    $missing_player_query = "SELECT user_id,username FROM `daily`,users
                where tournament_id=$tournament_id and round=$round
                and daily.user_id=users.id
                group by match_id having count(match_id) = 1 ";
    $result = mysql_query($missing_player_query);

    return $result;
}

function get_user($screen_name) {

    $db = $GLOBALS['db_name'];
    $user = new User();
    $query = "SELECT * FROM $db.users WHERE username='$screen_name'";
    $result = mysql_query($query) or die("Couldn't execute $query in get_user".  mysql_error());
/**
    if (mysql_num_rows($result) == 0) {
        print "nat found";
       $query = "SELECT * FROM $db.users WHERE alias='$screen_name'";
       $result = mysql_query($query) or die("Couldn't execute $query in get_user".  mysql_error());
    }*/

    while ($row= mysql_fetch_array($result)) {

        $user->set_first_name($row["firstname"]);
        $user->set_email($row["email"]);
        $user->set_id($row["id"]);
        $user->set_permissions($row["permissions"]);
        $user->set_screen_name($row["username"]);
        $user->set_skill($row["skill"]);
//print $user->get_skill();
     }

    return $user;

}

function get_user_from_id($id) {

    $db = $GLOBALS['db_name'];
    $user = new User();
    $query = "SELECT * FROM $db.users WHERE id=$id";
    $result = mysql_query($query) or die("Couldn't execute $query in get_user_from_id");

    while ($row= mysql_fetch_array($result)) {

        $user->set_daily_status($row['daily_status']);
        $user->set_first_name($row["firstname"]);
        $user->set_email($row["email"]);
        $user->set_id($row["id"]);
        $user->set_permissions($row["permissions"]);
        $user->set_screen_name($row["username"]);

    }

    return $user;

}

function get_userid_from_cookie() {
    return $_COOKIE[ID_COOKIE]; //retrieve contents of cookie
}

function get_username_from_cookie() {
    return $_COOKIE[NAME_COOKIE];
}

function has_recently_been_matched($user_id1, $user_id2, $tournament_id, $round) {

    $past_round = $round - 3;
    $query = "SELECT count(match_id) as num_matches FROM `daily`
        WHERE tournament_id=$tournament_id and round >= $past_round and (user_id=$user_id1 or user_id=$user_id2)
        group by match_id order by num_matches desc limit 1";

    $result = mysql_query($query) or die("Couldn't execute $query in get_user_from_id");

    while ($row= mysql_fetch_array($result)) {
        return $row['num_matches'] > 1;
    }
}

function is_logged_in() {
    return isset ($_COOKIE[NAME_COOKIE]);
}

function join_daily($user_id) {
    $match_id = get_maximum_daily_id();
    $user = get_user_from_id($user_id);
    $username = $user->get_screen_name();
    $user_first_name = $user->get_first_name();
    $user_email = $user->get_email();
    $round = get_daily_round();
    $tournament_id = get_daily_record();

    $tournament = get_tournament_from_record($tournament_id);
    $tournament_description = $tournament->get_description();

    $unmatched = get_unmatched_daily_players($tournament_id, $round);

    if (mysql_num_rows($unmatched) == 0) {
        $match_id++;
    }
    $status_query = "update users set daily_status='Active' where id=$user_id";
    $query = "INSERT INTO daily (`match_id` ,  `user_id` ,  `round`, `result`, `tournament_id`) VALUES ('$match_id',  '$user_id',  '$round', '', $tournament_id);";

    // unmatched
    if (mysql_num_rows($unmatched) == 0) {
        $match_id++;
        print "<script type='text/javascript'>document.getElementById('searching').style.display = 'none';</script>";
        print "<p style='background-color:lightyellow; padding:5px;'><span style='font-weight:bold;'>You are currently unmatched. You will be emailed once an opponent is found</span></p>";
       echo "<br /><form method=GET action='index.php'>
            <input type='Submit' value='Click to Continue'></form>";
    }
    // matched
    else {
        $row = mysql_fetch_array($unmatched);
        $opponent_id = $row['user_id'];
        $opponent_name = $row['username'];
        $opponent = get_user_from_id($opponent_id);
        $opponent_first_name = $opponent->get_first_name();
        $opponent_email = $opponent->get_email();



        print "<script type='text/javascript'>document.getElementById('searching').style.display = 'none';</script>";
        print "<p style='background-color:lightyellow; padding:5px;'><span style='font-weight:bold;'>You have been matched against $opponent_name.</span></p>";
        emailPlayers($opponent_name, $opponent_first_name, $username, $opponent_email, $round, $tournament_description);
        print "<p style='background-color:lightyellow; padding:5px;'><span style='font-weight:bold;'>Email message was sent to $opponent_name.</span></p>";
        emailPlayers($username, $user_first_name, $opponent_name, $user_email, $round, $tournament_description);
        print "<p style='background-color:lightyellow; padding:5px;'><span style='font-weight:bold;'>Email message was sent to $username.</span></p>";
        echo "<br /><form method=GET action='manage_daily.php'>
            <input type=hidden name='tournament_id' value='" . $tournament_id . "'>
            <input type=hidden name='id' value='" . $user_id . "'>
            <input type=hidden name='action' value='play'>
            <input type='Submit' value='Click to Continue'></form>";

    }

    if (mysql_query($query) &&
            mysql_query($status_query)) {
       return true;
    }
    else {
        return false;
    }

 
}

function update_daily_rating($tournament_id, $round, $user_id) {

    $query = "select * from daily where tournament_id=$tournament_id and
                    round=$round and user_id=$user_id";

    $result = mysql_query($query) or die("Couldn't execute $query in update_daily_rating");

    while ($row= mysql_fetch_array($result)) {
        // get result
        $match_id = $row['match_id'];
        $game_result = $row['result'];

        $opponent_query = "select * from daily where match_id=$match_id and
                   user_id<>$user_id";
        $opponent_result = mysql_query($opponent_query) or
            die("Couldn't execute $opponent_query in update_daily_rating");
        while ($opponent_row= mysql_fetch_array($opponent_result)) {
           $opponent_result = $opponent_row['result'];
        }
        
    }
    

    // get latest rating

    // get opponent latest rating

    // calculate new rating

    // insert into database
}

/**
 * Assume calling function has connected to the database
 * @param <type> $username
 * @param <type> $email
 */
function verify_email($username, $email) {

        //$db = $GLOBALS['db_name'];

        //create a random key
        $key = $username . $email . date('mY');
        $key = md5($key);

        $query = "INSERT INTO `confirm` VALUES(NULL,'$username','$key','$email')";

        //add confirm row
        $confirm = mysql_query($query);

        if($confirm){

            //let's send the email
            //put info into an array to send to the function
            $info = array(
                    'username' => $username,
                    'email' => $email,
                    'key' => $key
            );

            $body_txt = format_email($info,'html');

            //send the email
            if(email($email, "Welcome to WordsWithFriends.net", $body_txt)){

                // temporary double email
                email("don@unswrc.com", "Welcome to WordsWithFriends.net", $body_txt);

                //email sent
                $action['result'] = 'success';
                // array_push($text,'Thanks for signing up. Please check your email for confirmation!');

            }
            else{
                    $action['result'] = 'error';
                  //  array_push($text,'Could not send confirm email');
            }


        }
        else{

             $action['result'] = 'error';
             $error_message = mysql_error();
             array_push($text,'Confirm row was not added to the database. Reason: ' . $error_message);
        }


        // confirmation code end
}

function verify_user($user_id) {
    $query = "update users set verified=1
            WHERE id=$user_id";

    // get results
    mysql_query($query) or die("unable to verify $query");

}

?>
