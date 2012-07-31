<?php

require_once 'login_config.php';
require_once 'functions.php';
if (!$debugmode) {
  require_once 'email_smtp.php';
}

ini_set('display_errors', 1);
error_reporting(E_ALL);


class email {
    private $body;
    private $subject;
    private $to;


    public function  __construct($to, $subject, $body) {
        $this ->body = $body;
        $this ->subject = $subject;
        $this ->to = $to;
    }

    public function get_body() {
        return $this->body;
    }

    public function get_subject() {
        return $this->subject;
    }

    public function get_to() {
        return $this->to;
    }
}



$emails = array();
$emailBody = "";
$argument = "";
if (isset ($_GET["command"])) {
    $argument = $_GET["command"];
}

if ($argument == 'start' || $debugmode) {
   // print "success";

}
else {
   print "failure to start";
   mail('don@unswrc.com','cronAmerica failure',phpversion());
   die();
}

//Define database update function
function assignPlayers($t_id,$person0,$fname0,$new_round,$versus_sn,$email0,$t_desc){

	//populate database and send email
        global $emails;

	$from = "Admin <admin@wordswithfriends.net>";
	$reply_to = "no-reply@wordswithfriends.net";
	$return_path = "no-reply@wordswithfriends.net";

	$to = $email0;

	$subject = "[WordsWithFriends.net] Your next opponent is '" . $versus_sn . "'";


	$mailbody = "Dear " . $fname0 . " ('" . $person0 . "'): <br><br>'" . $versus_sn . "' is your Round " . $new_round . " opponent in the " . $t_desc . "! You can contact '" . $versus_sn . "' and take other actions at your <a href='http://wordswithfriends.net/?page_id=386'>MyTourney page</a>.<br /><br /><em>NOTE: This round will close approximately 24 hours from this email.</em><br><br>Please do not reply to this message. Thanks for playing!";

        array_push($emails, new email($to, $subject, $mailbody));
        return $mailbody;

}

function get_collisions($tournament_id, $round) {
    $collisions = array();
    $one_back = $round - 1;
    $query = "SELECT match_id,count(match_id) as num_matches,round,user_id FROM `daily`
        where tournament_id=$tournament_id and round > $one_back
        group by match_id having count(match_id) = 2";

    $result = mysql_query($query) or die("Couldn't execute $query");
    $count=mysql_num_rows($result);
    $round = 0;

    while ($row= mysql_fetch_array($result)) {
        $match_id = $row['match_id'];
        $user_id = $row['user_id'];
        $round = $row['round'];
        $opponent_id = get_daily_opponent($user_id, $round, $tournament_id);
        $collisions[$user_id."-".$round] = $opponent_id;
        $collisions[$opponent_id."-".$round] = $user_id;
    }

    return $collisions;
    
}

function increment_round() {
    $query = "SELECT * FROM T_CONTROL WHERE `t_status` = 'In Progress' AND `t_status` <> 'Complete' AND `t_id` LIKE('%BAM%') ORDER BY `t_start` DESC LIMIT 1";

    $result = mysql_query($query) or die("Couldn't execute query");
    $count=mysql_num_rows($result);
    $round = 0;

    while ($row= mysql_fetch_array($result)) {

	$t_id= $row["t_id"];
	$t_start= $row["t_start"];
	$t_desc= $row["t_desc"];
	$round= $row["t_round"];
	$max_round = $row["max_round"];
	$new_round= $round+1;
	$t_status= $row["t_status"];
	$drop = $round-1;

	if ($new_round <= $max_round) {
		$query2 = "UPDATE `T_CONTROL` SET t_round = '" . $new_round . "' WHERE t_id = '" . $t_id . "'";
		$result2 = mysql_query($query2) or die("Couldn't execute query2");

	}

        else {
            $query3 = "UPDATE `T_CONTROL` SET t_status = 'Complete' WHERE t_id = '" . $t_id . "'";
            $result3 = mysql_query($query3) or die("Couldn't execute query3");
            die();
    }
    }
}


    mysql_connect( $db_host, $db_username, $db_password )
          or die( "Error! Could not connect to database: " . mysql_error());

   // select the database
   mysql_select_db( $db )
      or die( "Error! Could not select the database: " . mysql_error()
);

function insert_daily($user_id) {
    $match_id = get_maximum_daily_id();
    $user = get_user_from_id($user_id);
    $username = $user->get_screen_name();
    $user_first_name = $user->get_first_name();
    $user_email = $user->get_email();
    $round = get_daily_round();
    $tournament_id = get_daily_record();

    $tournament = get_tournament($tournament_id);
    $tournament_description = $tournament->get_description();

    $unmatched = get_unmatched_daily_players($tournament_id, $round);

    if (mysql_num_rows($unmatched) == 0) {
        $match_id++;
    }
    $status_query = "update users set daily_status='Active' where id=$user_id";
    $query = "INSERT INTO daily (`match_id` ,  `user_id` ,  `round`, `result`, `tournament_id`) VALUES ('$match_id',  '$user_id',  '$round', '', $tournament_id);";

    //print $query;
    if (mysql_query($query) &&
            mysql_query($status_query)) {
       return true;
    }
    else {
        return false;
    }


}

function is_blocked_matchup($user_id1, $user_id2, $tournament_id, $round) {

    if ($user_id1 == 10500 ||
            $user_id2 == 10500) {
        print "debug";
    }
    $num_matches_1 = count_daily_matches($user_id1, $tournament_id);
    $num_matches_2 = count_daily_matches($user_id2, $tournament_id);

    if ($num_matches_1 >= 10 &&
            $num_matches_1 > 2 * $num_matches_2) {
        return true;
    }

    if ($num_matches_2 >= 10 &&
            $num_matches_2 > 2 * $num_matches_1) {
        return true;
    }

    if ($num_matches_1 > 0 &&
            $num_matches_2 == 0) {
        return true;
    }

    if ($num_matches_1 == 0 &&
            $num_matches_2 > 0) {
        return true;
    }
    if ($num_matches_1 > 3 * $num_matches_2) {
        return true;
    }
    if ($num_matches_2 > 3 * $num_matches_1) {
        return true;
    }
    if (has_recently_been_matched($user_id1, $user_id2, $tournament_id, $round)) {
        return true;
    }

    return false;
    
}
//set php script timeout, 0 to disable
set_time_limit(0);

$tournament_id = get_daily_record();
$round = get_daily_round();
$one_back_round = $round - 1;
$two_back_round = $round - 2;

// Assignment script

// get all players this round and last round with valid results
$filter_sql = "select user_id from daily where 
                (round = $round and tournament_id=$tournament_id and result <> '' and result != 'No-Show') or
                (round = $one_back_round and tournament_id=$tournament_id and result <> '' and result != 'No-Show')
";

// current round players who showed
$current_round_sql = "select user_id, username from daily, users where round = $round and tournament_id=$tournament_id and daily.user_id = users.id and result<>'No-Show'";

// prior round players
$last_round_sql = "select user_id, username from daily, users where round = $one_back_round and tournament_id=$tournament_id and daily.user_id = users.id";

// rejoining after taking a break
$waiting_sql= "select id as user_id, username from users where daily_status='Waiting'";

// get users ranked win %
$score_query = "
    SELECT PointsFor.user_id,username,
    sum(case when PointsFor.result='Won' then 1 Else 0 End)/sum(case when PointsFor.result<>'' Then 1 Else 0 End)*100.0 As winpercent,
    sum(case when PointsFor.result<>'' Then 1 Else 0 End) as totalgames,
    sum(PointsFor.score) as pointsfor,
    sum(PointsAgainst.score) as pointsagainst,
    daily_status
    FROM users,`daily` PointsFor
    JOIN `daily` PointsAgainst
      on PointsFor.match_id = PointsAgainst.match_id
    where PointsFor.user_id <> PointsAgainst.user_id and
        PointsFor.user_id=users.id and
        PointsFor.tournament_id=$tournament_id and
        (daily_status='Active' or daily_status='Waiting')
    group by user_id
    order By WinPercent desc, totalgames desc";

$score_result = mysql_query($score_query) or die("Couldn't execute $score_query");
//print $score_query;die();

$ranked_results = array();
$ordered_results = array();
$filtered_results = array();
$last_round = array();
$current_round = array();
$matches = array();

// store ranked results by user_id
while ($row= mysql_fetch_array($score_result)) {
    $user_id = $row['user_id'];
    $ranked_results[$user_id] = $row;
}

// filter by player activity in this round and last round
$result = mysql_query($filter_sql) or die("Couldn't execute $filter_sql");
while ($row= mysql_fetch_array($result)) {
    $user_id = $row['user_id'];
    // valid players this round and last
    $filtered_results[$user_id] = $row;
}

// store valid players by user_id
$result = mysql_query($last_round_sql) or die("Couldn't execute $last_round_sql");
while ($row= mysql_fetch_array($result)) {
    $user_id = $row['user_id'];
    $last_round[$user_id] = $row;

}

$result = mysql_query($current_round_sql) or die("Couldn't execute $current_round_sql");

// filter ranked results by adding valid current round players
// get current round plaiers who
// i) were not in last round (likely new)
// ii) not filtered out
while ($row= mysql_fetch_array($result)) {
    $user_id = $row['user_id'];
    if (!array_key_exists($row['user_id'], $filtered_results) &&
          !array_key_exists($row['user_id'], $last_round)) {
        $filtered_results[$user_id] = $row;
        $ranked_results[$user_id] = $row;
       // print $user_id;
    }


}


mysql_query("UPDATE users set daily_status='Active' where daily_status='Waiting'");


increment_round();

$new_round = get_daily_round();

$final = array();
$counter = 0;
// store results by index
foreach ($ranked_results as $row) {
    if (array_key_exists($row['user_id'], $filtered_results) ||
            $row['daily_status'] == 'Waiting') {
        $final[$counter] = $row;
        $counter++;
    }    

}
print "init size ".count($final);
// collision detection
//$collisions = get_collisions($tournament_id, $round);
for ($i=0; $i < count($final) - 1; $i+=2) {
    $user_id1 = $final[$i]['user_id'];
    $user_id2 = $final[$i + 1]['user_id'];
    if (is_blocked_matchup($user_id1, $user_id2, $tournament_id, $new_round)) {
        $row_counter = $i + 1;
        while (is_blocked_matchup($user_id1, $user_id2, $tournament_id, $new_round) &&
                $row_counter < count($final) - 1) {
            $row_counter++;
           // if ($row_counter == count($final)) {
            //    print "$";
           // }
            $user_id2 = $final[$row_counter]['user_id'];

        }

        // replace
        

        array_splice($final, $i + 1, 0, array($final[$row_counter]));
        array_splice($final, $row_counter+1, count($final), array_slice($final, $row_counter + 2));


    }

}
print "final size ".count($final);
// insert row by row
for ($i=0; $i < count($final); $i++) {
    $row = $final[$i];
    insert_daily($row['user_id']);

 
}

for ($i=0; $i < count($final); $i+=2) {
    if ($i % 2 == 0 && $i == count($final) - 1) {
        // do nothing
    }
    else {
        $user_id = $final[$i]['user_id'];
        $opponent_id = $final[$i+1]['user_id'];
        $tournament_id = get_daily_record();
        $tournament = get_tournament_from_record($tournament_id);

        $user = get_user_from_id($user_id);
        $opponent = get_user_from_id($opponent_id);
        
        print assignPlayers($tournament->get_id(), $user->get_screen_name(), $user->get_first_name(), $new_round, $opponent->get_screen_name(), $user->get_email(), $tournament->get_description())."<br />";
        print assignPlayers($tournament->get_id(), $opponent->get_screen_name(), $opponent->get_first_name(), $new_round, $user->get_screen_name(), $opponent->get_email(), $tournament->get_description())."<br />";
    }
}


if (!$debugmode) {
    email('don@unswrc.com','cronAmerica begin',$emailBody);
    foreach ($emails as $email) {
        email($email->get_to(), $email->get_subject(), $email->get_body());
        sleep(2);
    }
   email('don@unswrc.com','cronAmerica success',$emailBody);

}
 

//Reset to 30 seconds.
set_time_limit(30);

?>
