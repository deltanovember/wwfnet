<?php
$permission_level=5;

include"../master_inc.php";
require_once "../login_config.php";
require_once 'functions.php';

$source = "D2011_03_BAM";
$tournament_id = 0;

$query = "SELECT * FROM T_CONTROL where t_id='$source'";
$result = mysql_query($query) or die("Couldn't execute $query");
while ($row= mysql_fetch_array($result)) {
    $tournament_id = $row['record'];
}


// use hash table to avoid double counting
$ignore_list = array();

$query = "SELECT * FROM $source";

// get results
$result = mysql_query($query) or die("Couldn't execute $query");
$row_counter = 1;

// now you can display the results returned
while ($row= mysql_fetch_array($result)) {

    $player= $row['player'];
    $id= $row['id'];

    $i = 1;
    while ($i <= 31) {


        if (array_key_exists($i.$player, $ignore_list)) {
            // already processed
        }

        else {
            $opponent_name = $row['r' . $i . '_vs'];
            $opponent = get_user($row['r' . $i . '_vs']);
            $opponent_id = $opponent->get_id();

            // only proceed if opponent
            if ($opponent_id) {
                $game_result = $row['r' . $i . '_rslt'];
                $score = 0;
                $score = $row['r' . $i . '_g1'];

                $private_message = mysql_real_escape_string($row['r' . $i . '_pmsg']);
                $rated = $row['r' . $i . '_rated'];
                $cheated = -1;
                $comment = mysql_real_escape_string($row['r' . $i . '_cmt']);
                $admin_comment = mysql_real_escape_string($row['r' . $i . '_admcmt']);
                $control = $row['r' . $i . '_ctrl'];
                $status = $row['status'];
                $key = $i.$opponent->get_screen_name();
                $ignore_list[$key] = "found";
                $time = time();

                // enter record two at a time
                $match_id = get_maximum_daily_id();
                $match_id++;

                // first record
                $insert_sql1 = "INSERT INTO $db.daily VALUES($match_id, $id, $i, '$score', '$game_result', '$private_message', '$rated', $cheated, '$comment', '$admin_comment', $tournament_id, $time)";
                $insert_sql2 = "";
                $insert_result = mysql_query($insert_sql1) or die("Couldn't execute $insert_sql1");


                $update_sql1 = "update users set daily_status='$status' where id=$id";
                $update_result = mysql_query($update_sql1) or die("Couldn't execute $update_sql1");

                // second record
                $query = "SELECT * FROM $source where id=$opponent_id";

                $opponent_result = mysql_query($query) or die("Couldn't execute $query");

                // now you can display the results returned
                while ($opponent_row = mysql_fetch_array($opponent_result)) {
                    $game_result = $opponent_row['r' . $i . '_rslt'];
                    $score = $opponent_row['r' . $i . '_g1'];
                    $status = $opponent_row['status'];
                    $private_message = mysql_real_escape_string($opponent_row['r' . $i . '_pmsg']);
                    $rated = $opponent_row['r' . $i . '_rated'];
                    $cheated = -1;
                    $comment = mysql_real_escape_string($opponent_row['r' . $i . '_cmt']);
                    $admin_comment = mysql_real_escape_string($opponent_row['r' . $i . '_admcmt']);
                    $insert_sql2 = "INSERT INTO $db.daily VALUES($match_id, $opponent_id, $i, '$score', '$game_result', '$private_message', '$rated', $cheated, '$comment', '$admin_comment', $tournament_id, $time)";
                    $insert_result = mysql_query($insert_sql2) or die("Couldn't execute $insert_sql2");


                    $update_sql2 = "update users set daily_status='$status' where id=$opponent_id";
                    $update_result = mysql_query($update_sql2) or die("Couldn't execute $update_sql2");
                    


                }

                if ($control == "yes") {
                    //mysql_query($insert_sql1) or die("Couldn't execute $insert_sql1");
                   // mysql_query($insert_sql2) or die("Couldn't execute $insert_sql2");
                }
                else {
                    //mysql_query($insert_sql2) or die("Couldn't execute $insert_sql2");
                   // mysql_query($insert_sql1) or die("Couldn't execute $insert_sql1");
                }

            }

        }

        $i++;
    }


}

?>
