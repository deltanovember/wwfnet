<?php
	
/*
	Question2Answer 1.3.1 (c) 2011, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-db-recalc.php
	Version: 1.3.1
	Date: 2011-02-01 12:56:28 GMT
	Description: Database functions for recalculations (clean-up operations)


	This program is free software; you can redistribute it and/or
	modify it under the terms of the GNU General Public License
	as published by the Free Software Foundation; either version 2
	of the License, or (at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	More about this license: http://www.question2answer.org/license.php
*/

	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../');
		exit;
	}

	require_once QA_INCLUDE_DIR.'qa-db-post-create.php';

	
//	For reindexing posts...
	
	function qa_db_posts_get_for_reindexing($startpostid, $count)
/*
	Return the information required to reindex up to $count posts starting from $startpostid in the database
*/
	{
		return qa_db_read_all_assoc(qa_db_query_sub(
			"SELECT ^posts.postid, BINARY ^posts.title AS title, BINARY ^posts.content AS content, ^posts.format, BINARY ^posts.tags AS tags, ^posts.type, IF (^posts.type='Q', ^posts.postid, IF(parent.type='Q', parent.postid, grandparent.postid)) AS questionid FROM ^posts LEFT JOIN ^posts AS parent ON ^posts.parentid=parent.postid LEFT JOIN ^posts as grandparent ON parent.parentid=grandparent.postid WHERE ^posts.postid>=# AND ( (^posts.type='Q') OR (^posts.type='A' AND parent.type<=>'Q') OR (^posts.type='C' AND parent.type<=>'Q') OR (^posts.type='C' AND parent.type<=>'A' AND grandparent.type<=>'Q') ) ORDER BY postid LIMIT #",
			$startpostid, $count
		), 'postid');
	}

	
	function qa_db_prepare_for_reindexing($firstpostid, $lastpostid)
/*
	Prepare posts $firstpostid to $lastpostid for reindexing in the database by removing their prior index entries
*/
	{
		qa_db_query_sub(
			'DELETE FROM ^titlewords WHERE postid>=# AND postid<=#',
			$firstpostid, $lastpostid
		);

		qa_db_query_sub(
			'DELETE FROM ^contentwords WHERE postid>=# AND postid<=#',
			$firstpostid, $lastpostid
		);

		qa_db_query_sub(
			'DELETE FROM ^posttags WHERE postid>=# AND postid<=#',
			$firstpostid, $lastpostid
		);
	}

	
	function qa_db_truncate_indexes($firstpostid)
/*
	Remove any rows in the database word indexes with postid from $firstpostid upwards
*/
	{
		qa_db_query_sub(
			'DELETE FROM ^titlewords WHERE postid>=#',
			$firstpostid
		);

		qa_db_query_sub(
			'DELETE FROM ^contentwords WHERE postid>=#',
			$firstpostid
		);

		qa_db_query_sub(
			'DELETE FROM ^posttags WHERE postid>=#',
			$firstpostid
		);
	}

	
	function qa_db_count_words()
/*
	Return the number of words currently referenced in the database
*/
	{
		return qa_db_read_one_value(qa_db_query_sub(
			'SELECT COUNT(*) FROM ^words'
		));
	}

	
	function qa_db_words_prepare_for_recounting($startwordid, $count)
/*
	Return the ids of up to $count words in the database starting from $startwordid
*/
	{
		return qa_db_read_all_values(qa_db_query_sub(
			'SELECT wordid FROM ^words WHERE wordid>=# ORDER BY wordid LIMIT #',
			$startwordid, $count
		));
	}

	
	function qa_db_words_recount($firstwordid, $lastwordid)
/*
	Recalculate the cached counts for words $firstwordid to $lastwordid in the database
*/
	{
		qa_db_query_sub(
			'UPDATE ^words AS x, (SELECT ^words.wordid, COUNT(^titlewords.wordid) AS titlecount FROM ^words LEFT JOIN ^titlewords ON ^titlewords.wordid=^words.wordid WHERE ^words.wordid>=# AND ^words.wordid<=# GROUP BY wordid) AS a SET x.titlecount=a.titlecount WHERE x.wordid=a.wordid',
			$firstwordid, $lastwordid
		);

		qa_db_query_sub(
			'UPDATE ^words AS x, (SELECT ^words.wordid, COUNT(^contentwords.wordid) AS contentcount FROM ^words LEFT JOIN ^contentwords ON ^contentwords.wordid=^words.wordid WHERE ^words.wordid>=# AND ^words.wordid<=# GROUP BY wordid) AS a SET x.contentcount=a.contentcount WHERE x.wordid=a.wordid',
			$firstwordid, $lastwordid
		);

		qa_db_query_sub(
			'UPDATE ^words AS x, (SELECT ^words.wordid, COUNT(^posttags.wordid) AS tagcount FROM ^words LEFT JOIN ^posttags ON ^posttags.wordid=^words.wordid WHERE ^words.wordid>=# AND ^words.wordid<=# GROUP BY wordid) AS a SET x.tagcount=a.tagcount WHERE x.wordid=a.wordid',
			$firstwordid, $lastwordid
		);
		
		qa_db_query_sub(
			'DELETE FROM ^words WHERE wordid>=# AND wordid<=# AND titlecount=0 AND contentcount=0 AND tagcount=0',
			$firstwordid, $lastwordid
		);
	}


//	For recalculating numbers of votes and answers for questions...

	function qa_db_posts_get_for_recounting($startpostid, $count)
/*
	Return the ids of up to $count posts in the database starting from $startpostid
*/
	{
		return qa_db_read_all_values(qa_db_query_sub(
			'SELECT postid FROM ^posts WHERE postid>=# ORDER BY postid LIMIT #',
			$startpostid, $count
		));
	}

	
	function qa_db_posts_recount($firstpostid, $lastpostid)
/*
	Recalculate the cached counts for posts $firstpostid to $lastpostid in the database
*/
	{
		qa_db_query_sub(
			'UPDATE ^posts AS x, (SELECT ^posts.postid, COALESCE(SUM(GREATEST(0,^uservotes.vote)),0) AS upvotes, -COALESCE(SUM(LEAST(0,^uservotes.vote)),0) AS downvotes FROM ^posts LEFT JOIN ^uservotes ON ^uservotes.postid=^posts.postid WHERE ^posts.postid>=# AND ^posts.postid<=# GROUP BY postid) AS a SET x.upvotes=a.upvotes, x.downvotes=a.downvotes WHERE x.postid=a.postid',
			$firstpostid, $lastpostid
		);
		
		qa_db_query_sub(
			'UPDATE ^posts AS x, (SELECT parents.postid, COUNT(children.postid) AS acount FROM ^posts AS parents LEFT JOIN ^posts AS children ON parents.postid=children.parentid AND children.type=\'A\' WHERE parents.postid>=# AND parents.postid<=# GROUP BY postid) AS a SET x.acount=a.acount WHERE x.postid=a.postid',
			$firstpostid, $lastpostid
		);
	}

	
//	For recalculating user points...

	function qa_db_users_get_for_recalc_points($startuserid, $count)
/*
	Return the ids of up to $count users in the database starting from $startuserid
	If using single sign-on integration, base this on user activity rather than the users table which we don't have
*/
	{
		if (QA_EXTERNAL_USERS)
			return qa_db_read_all_values(qa_db_query_sub(
				'(SELECT DISTINCT userid FROM ^posts WHERE userid>=# ORDER BY userid LIMIT #) UNION (SELECT DISTINCT userid FROM ^uservotes WHERE userid>=# ORDER BY userid LIMIT #)',
				$startuserid, $count, $startuserid, $count
			));
		else
			return qa_db_read_all_values(qa_db_query_sub(
				'SELECT DISTINCT userid FROM ^users WHERE userid>=# ORDER BY userid LIMIT #',
				$startuserid, $count
			));
	}
	
	function qa_db_users_recalc_points($firstuserid, $lastuserid)
/*
	Recalculate all userpoints columns for users $firstuserid to $lastuserid in the database
*/
	{
		require_once QA_INCLUDE_DIR.'qa-db-points.php';
	
		$qa_userpoints_calculations=qa_db_points_calculations();
				
		qa_db_query_sub(
			'DELETE FROM ^userpoints WHERE userid>=# AND userid<=#',
			$firstuserid, $lastuserid
		);
		
		if (QA_EXTERNAL_USERS)
			qa_db_query_sub(
				'INSERT INTO ^userpoints (userid) SELECT DISTINCT userid FROM ^posts WHERE userid>=# AND userid<=# UNION SELECT DISTINCT userid FROM ^uservotes WHERE userid>=# AND userid<=#',
				$firstuserid, $lastuserid, $firstuserid, $lastuserid
			);
		else
			qa_db_query_sub(
				'INSERT INTO ^userpoints (userid) SELECT DISTINCT userid FROM ^users WHERE userid>=# AND userid<=#',
				$firstuserid, $lastuserid
			);
		
		$updatepoints=(int)qa_opt('points_base');
		
		foreach ($qa_userpoints_calculations as $field => $calculation) {
			qa_db_query_sub(
				'UPDATE ^userpoints, (SELECT userid_src.userid, '.str_replace('~', ' BETWEEN # AND #', $calculation['formula']).' GROUP BY userid) AS results '.
				'SET ^userpoints.'.$field.'=results.'.$field.' WHERE ^userpoints.userid=results.userid',
				$firstuserid, $lastuserid
			);
			
			$updatepoints.='+('.((int)$calculation['multiple']).'*'.$field.')';
		}
		
		qa_db_query_sub(
			'UPDATE ^userpoints SET points='.$updatepoints.' WHERE userid>=# AND userid<=#',
			$firstuserid, $lastuserid
		);
	}

	
	function qa_db_truncate_userpoints($firstuserid)
/*
	Remove any rows in the userpoints table from $firstuserid upwards
*/
	{
		qa_db_query_sub(
			'DELETE FROM ^userpoints WHERE userid>=#',
			$firstuserid
		);
	}
	
	
//	For recalculating categories...
	
	function qa_db_posts_get_for_recategorizing($startpostid, $count)
/*
	Return the ids of up to $count answer or comments (including hidden) in the database starting from $startpostid
*/
	{
		return qa_db_read_all_values(qa_db_query_sub(
			"SELECT postid FROM ^posts WHERE postid>=# AND type IN ('A', 'C', 'A_HIDDEN', 'C_HIDDEN') ORDER BY postid LIMIT #",
			$startpostid, $count
		));
	}
	
	
	function qa_db_posts_recategorize($firstpostid, $lastpostid)
/*
	Recalculate the categories for answer or comments (including hidden) between $firstpostid and $lastpostid
	in the database, based on the category of the antecedent question
*/
	{
		qa_db_query_sub(
			"UPDATE ^posts AS x, (SELECT ^posts.postid, IF((parent.type='Q') OR (parent.type='Q_HIDDEN'), parent.categoryid, grandparent.categoryid) AS categoryid FROM ^posts LEFT JOIN ^posts AS parent ON ^posts.parentid=parent.postid LEFT JOIN ^posts AS grandparent ON parent.parentid=grandparent.postid WHERE ^posts.postid BETWEEN # AND # AND ^posts.type IN ('A', 'C', 'A_HIDDEN', 'C_HIDDEN')) AS a SET x.categoryid=a.categoryid WHERE x.postid=a.postid",
			$firstpostid, $lastpostid
		);
	}
	

	function qa_db_categories_recount()
/*
	Recalculate the total number of visible questions in all categories
*/
	{
		qa_db_query_sub("UPDATE ^categories SET qcount=0");

		qa_db_query_sub(
			"UPDATE ^categories AS x, (SELECT ^categories.categoryid, COUNT(*) AS qcount FROM ^categories LEFT JOIN ^posts ON ^posts.categoryid=^categories.categoryid WHERE ^posts.type='Q' GROUP BY categoryid) AS a SET x.qcount=a.qcount WHERE x.categoryid=a.categoryid"
		);
	}


//	For deleting hidden posts...

	function qa_db_posts_get_for_deleting($type, $startpostid=0, $limit=null)
/*
	Return the ids of up to $limit posts of $type that can be deleted from the database (i.e. have no dependents)
*/
	{
		$limitsql=isset($limit) ? (' LIMIT '.(int)$limit) : '';
		
		return qa_db_read_all_values(qa_db_query_sub(
			"SELECT ^posts.postid FROM ^posts LEFT JOIN ^posts AS child ON child.parentid=^posts.postid WHERE ^posts.type=$ AND ^posts.postid>=# AND child.postid IS NULL".$limitsql,
			$type.'_HIDDEN', $startpostid
		));
	}


/*
	Omit PHP closing tag to help avoid accidental output
*/