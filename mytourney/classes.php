<?php

/**
 * Class representing single daily record
 */
class Daily {

    private $match_id;
    private $user_id;
    private $user_name;
    private $round;
    private $score;
    private $result;
    private $private_message;
    private $rated;
    private $cheated;
    private $comment;
    private $admin_comment;
    private $tournament_id;
    private $modified;


    /**
     * construct straight from database
     * @param <type> $row
     */
    public function  __construct($row) {
        $this ->match_id = $row['match_id'];
        $this ->user_id = $row['user_id'];
        $this ->user_name = $row['username'];
        $this ->round = $row['round'];
        $this ->score = $row['score'];
        $this ->result = $row['result'];
        $this ->private_message = $row['private_message'];
        $this ->rated = $row['rated'];
        $this ->cheated = $row['cheated'];
        $this ->comment = $row['comment'];
        $this ->admin_comment = $row['admin_comment'];
        $this ->tournament_id = $row['tournament_id'];
        $this ->modified = $row['modified'];

    }

    public function get_match_id() {
        return $this->match_id;
    }

    public function get_user_id() {
        return $this->user_id;

    }

    public function get_user_name() {
        return $this->user_name;

    }

    public function get_round() {
        return $this->round;
    }

    public function get_score() {
        return $this->score;
    }

    public function get_result() {
        return $this->result;
    }

    public function get_private_message() {
        return $this->private_message;
    }

    public function get_rated() {
        return $this->rated;
    }

    public function get_cheated() {
        return $this->cheated;
    }

    public function get_comment() {
        return $this->comment;
    }

    public function get_admin_comment() {
        return $this->admin_comment;
    }

    public function get_tournament_id() {
        return $this->tournament_id;
    }

    public function get_modified() {
        return $this->modified;
    }

    public function sort_by_name(Daily $d1, Daily $d2) {
        return strcmp($d1->get_user_name(), $d2->get_user_name());
    }

    public function sort_by_modified(Daily $d1, Daily $d2) {
        return $d1->get_modified() > $d2->get_modified();
    }

}

/**
 * Container to hold pairs of matches
 */
class DailyMatch {

    private $d1 = NULL;
    private $d2 = NULL;

    public function  __construct(Daily $d1) {
        $this ->d1 = $d1;
    }

    public function add(Daily $d2) {
        if ($d2 != null) {
            $this ->d2 = $d2;

            // winners listed first
            if ($d2 ->get_result() == "Won") {
                $temp = $this->d1;
                $this->d1 = $this->d2;
                $this->d2 = $temp;
            }

 
        }
        
    }

    public function count() {
        if ($this->d2 == NULL) {
            return 1;
        }
        else {
            return 2;
        }
    }
    public function get_daily1() {
        return $this->d1;
    }

    public function get_daily2() {
        return $this->d2;
    }

    public function sort_by_name(DailyMatch $d1, DailyMatch $d2) {
        if ($d1->count() == 1) {
            return -1;
        }
        else if ($d2->count() == 1) {
            return 1;
        }
        else
            return strcmp($d1->get_daily1()->get_user_name(), $d2->get_daily2()->get_user_name());
    }

    public function sort_by_modified(DailyMatch $d1, DailyMatch $d2) {
        if ($d1->count() == 1) {
            return -1;
        }
        else if ($d2->count() == 1) {
            return 1;
        }
        else
            return $d1->get_daily1()->get_modified() < $d2->get_daily1()->get_modified();
    }

}

/**
 * class representing single tourney record
 */
class Record {

    private $admin_comment;
    private $comment;
    private $id;
    private $message;
    private $opponent;
    private $player;
    private $result;
    private $round;
    private $status;


    public function  __construct($id) {
        $this ->id = $id;
    }

    public function get_admin_comment() {
        return $this->admin_comment;
    }

    public function get_opponent() {
        return $this->opponent;
    }

    public function get_round() {
        return $this->round;
    }

    public function get_status() {
        return $this->status;
    }

    public function is_eliminated() {
        return $this->status == "Eliminated";
    }

    public function set_opponent($opponent) {
        $this ->opponent = $opponent;
    }

    public function set_round($round) {
        $this ->round = $round;
    }

    public function set_status($status) {
        $this ->status = $status;
    }

}

class Tournament {

    private $champion;
    private $description;
    private $id;
    private $record;
    private $round;
    private $start;
    private $status;

    public function  __construct($id) {
        $this ->id = $id;
    }

    public function get_champion() {
        return $this->champion;
    }

    public function get_description() {
        if (isset ($this->description)) {
          return $this->description;
        }
        else {
            return "";
        }

    }

    public function get_id() {
        return $this->id;
    }

    public function get_record() {
        return $this->record;
    }

    public function get_round() {
        return $this->round;
    }

    public function get_short_start() {
        return substr($this -> start, 5, 10);
    }

    public function get_start() {
        return $this->start;
    }

    public function get_status() {
        return $this->status;
    }

    public function set_champion($champion) {
        if (isset ($champion)) {
          $this ->champion = $champion;
        }

    }

    public function set_descripion($description) {
        $this ->description = $description;
    }

    public function set_id($id) {
        $this ->id = $id;
    }

    public function set_record($record) {
        $this ->record = $record;
    }

    public function set_round($round) {
        $this ->round = $round;
    }

    public function set_start($start) {
        $this ->start = $start;
    }

    public function set_status($status) {
        $this -> status = $status;
    }

}


class User {

    private $daily_status;
    private $first_name;
    private $email;
    private $id;
    private $permissions;
    private $screen_name;
    private $skill;

    public function  __construct() {

    }

    public function get_daily_status() {
        return $this->daily_status;
    }

    public function get_first_name() {
        return $this->first_name;
    }

    public function get_email() {
        return $this->email;
    }

    public function get_id() {
        return $this->id;
    }

    public function get_permissions() {
        return $this->permissions;
    }

    public function get_screen_name() {
        return $this->screen_name;
    }

    public function get_skill() {
        return $this->skill;
    }

    public function set_daily_status($daily_status) {
        $this ->daily_status = $daily_status;
    }

    public function set_first_name($first_name) {
        $this ->first_name = $first_name;
    }

    public function set_email($email) {
        $this ->email = $email;
    }

    public function set_id($id) {
        $this ->id = $id;
    }

    public function set_skill($skill) {
        $this ->skill = $skill;
    }

    public function set_permissions($permissions) {
        $this ->permissions = $permissions;
    }

        public function set_screen_name($screen_name) {
        $this ->screen_name = $screen_name;
    }


}


?>
