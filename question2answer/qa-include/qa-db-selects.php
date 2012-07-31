<?php
	
/*
	Question2Answer 1.3.1 (c) 2011, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-db-selects.php
	Version: 1.3.1
	Date: 2011-02-01 12:56:28 GMT
	Description: Builders of selectspec arrays (see qa-db.php) used to specify database SELECTs


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

	require_once QA_INCLUDE_DIR.'qa-db-maxima.php';

	
	function qa_db_select_with_pending() // any number of parameters read via func_get_args()
/*
	Return the results of all the SELECT operations specified by the supplied selectspec parameters, while also
	loading all options, plus custom pages and information about the logged in user, if those were requested.
	Uses one DB query unless QA_OPTIMIZE_LOCAL_DB is true.
	If only one parameter is supplied, return its result, otherwise return an array of results.
*/
	{
		require_once QA_INCLUDE_DIR.'qa-app-options.php';
		
		global $qa_nav_pages_pending, $qa_nav_pages_cached, $qa_logged_in_pending;
		
		$selectspecs=func_get_args();
		$singleresult=(count($selectspecs)==1);
		
		foreach ($selectspecs as $key => $selectspec) // can pass null parameters
			if (empty($selectspec))
				unset($selectspecs[$key]);
		
		$optionselectspecs=qa_options_pending_selectspecs();
		foreach ($optionselectspecs as $key => $selectspec)
			$selectspecs[$key]=$selectspec;
			
		if (@$qa_logged_in_pending && !QA_EXTERNAL_USERS) {
			$loggedinselectspec=qa_logged_in_user_selectspec();
			if (is_array($loggedinselectspec))
				$selectspecs['_loggedin']=$loggedinselectspec;

		} else
			$loggedinselectspec=null;
		
		if (@$qa_nav_pages_pending && !isset($qa_nav_pages_cached))
			$selectspecs['_navpages']=qa_db_pages_selectspec(array('B', 'M', 'O', 'F'));
		
		$outresults=qa_db_multi_select($selectspecs);
		
		qa_options_load_options($optionselectspecs, $outresults);
			
		if (is_array($loggedinselectspec))
			qa_logged_in_user_load($loggedinselectspec, $outresults['_loggedin']);
			
		if (@$qa_nav_pages_pending && !isset($qa_nav_pages_cached))
			$qa_nav_pages_cached=$outresults['_navpages'];
			
		return $singleresult ? $outresults[0] : $outresults;
	}

	
	function qa_db_posts_basic_selectspec($voteuserid=null, $full=false, $user=true)
/*
	Return the common selectspec used to build any selectspecs which retrieve posts from the database.
	If $voteuserid is set, retrieve the vote made by a particular that user on each post.
	If $full is true, get full information on the posts, instead of just information for listing pages.
	If $user is true, get information about the user who wrote the post (or cookie if anonymous).
*/
	{
		$selectspec=array(
			'columns' => array(
				'^posts.postid', '^posts.categoryid', '^posts.type', 'basetype' => 'LEFT(^posts.type,1)',
				'hidden' => "INSTR(^posts.type, '_HIDDEN')>0", '^posts.acount', '^posts.selchildid', '^posts.upvotes', '^posts.downvotes',
				'title' => 'BINARY ^posts.title', 'tags' => 'BINARY ^posts.tags', 'created' => 'UNIX_TIMESTAMP(^posts.created)',
			),
			
			'arraykey' => 'postid',
			'source' => '^posts',
			'arguments' => array(),
		);
		
		if (isset($voteuserid)) {
			$selectspec['columns']['uservote']='^uservotes.vote';
			$selectspec['source'].=' LEFT JOIN ^uservotes ON ^posts.postid=^uservotes.postid AND ^uservotes.userid=$';
			$selectspec['arguments'][]=$voteuserid;
		}
		
		if ($full) {
			$selectspec['columns']['content']='BINARY ^posts.content';
			$selectspec['columns']['notify']='BINARY ^posts.notify';
			$selectspec['columns']['updated']='UNIX_TIMESTAMP(^posts.updated)';
			$selectspec['columns'][]='^posts.format';
			$selectspec['columns'][]='^posts.lastuserid';
			$selectspec['columns']['lastip']='INET_NTOA(^posts.lastip)';
			$selectspec['columns'][]='^posts.parentid';
		};
				
		if ($user) {
			$selectspec['columns'][]='^posts.userid';
			$selectspec['columns'][]='^posts.cookieid';
			$selectspec['columns']['createip']='INET_NTOA(^posts.createip)';
			$selectspec['columns'][]='^userpoints.points';

			if (!QA_EXTERNAL_USERS) {
				$selectspec['columns'][]='^users.flags';
				$selectspec['columns'][]='^users.level';
				$selectspec['columns']['email']='BINARY ^users.email';
				$selectspec['columns']['handle']='BINARY ^users.handle';
				$selectspec['columns'][]='^users.avatarblobid';
				$selectspec['columns'][]='^users.avatarwidth';
				$selectspec['columns'][]='^users.avatarheight';
				$selectspec['source'].=' LEFT JOIN ^users ON ^posts.userid=^users.userid';
				
				if ($full) {
					$selectspec['columns']['lasthandle']='BINARY lastusers.handle';
					$selectspec['source'].=' LEFT JOIN ^users AS lastusers ON ^posts.lastuserid=lastusers.userid';
				}
			}
			
			$selectspec['source'].=' LEFT JOIN ^userpoints ON ^posts.userid=^userpoints.userid';
		}
		
		return $selectspec;
	}

	
	function qa_db_recent_qs_selectspec($voteuserid, $start, $categoryslug=null, $createip=null, $hidden=false, $full=false, $count=QA_DB_RETRIEVE_QS_AS)
/*
	Return the selectspec to retrieve $count recent ($hidden or not) questions,	starting from offset $start,
	restricted to $createip (if not null) and the category for $categoryslug (if not null),
	with the corresponding vote made by $voteuserid (if not null) and including $full content or not
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid, $full);
		
		$selectspec['source'].=" JOIN (SELECT postid FROM ^posts WHERE ".
			(isset($categoryslug) ? "categoryid=(SELECT categoryid FROM ^categories WHERE tags=$ LIMIT 1) AND " : "").
			(isset($createip) ? "createip=INET_ATON($) AND " : "").
			"type=$ ORDER BY ^posts.created DESC LIMIT #,#) y ON ^posts.postid=y.postid";

		if (isset($categoryslug))
			$selectspec['arguments'][]=$categoryslug;

		if (isset($createip))
			$selectspec['arguments'][]=$createip;
		
		array_push($selectspec['arguments'], $hidden ? 'Q_HIDDEN' : 'Q', $start, $count);

		$selectspec['sortdesc']='created';
		
		return $selectspec;
	}

	
	function qa_db_unanswered_qs_selectspec($voteuserid, $start, $categoryslug=null, $hidden=false, $full=false, $count=QA_DB_RETRIEVE_QS_AS)
/*
	Return the selectspec to retrieve $count recent unanswered ($hidden or not) questions, starting from offset $start,
	restricted to the category for $categoryslug (if not null),
	with the corresponding vote made by $voteuserid (if not null) and including $full content or not
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid, $full);
		
		$selectspec['source'].=" JOIN (SELECT postid FROM ^posts WHERE ".(isset($categoryslug) ? "categoryid=(SELECT categoryid FROM ^categories WHERE tags=$ LIMIT 1) AND " : "")."type=$ AND acount=0 ORDER BY ^posts.created DESC LIMIT #,#) y ON ^posts.postid=y.postid";
		
		if (isset($categoryslug))
			$selectspec['arguments'][]=$categoryslug;

		array_push($selectspec['arguments'], $hidden ? 'Q_HIDDEN' : 'Q', $start, $count);

		$selectspec['sortdesc']='created';
		
		return $selectspec;
	}


	function qa_db_recent_a_qs_selectspec($voteuserid, $start, $categoryslug=null, $createip=null, $hidden=false, $fullanswers=false, $count=QA_DB_RETRIEVE_QS_AS)
/*
	For $count most recent ($hidden or not) answers, starting from offset $start,
	restricted to $createip (if not null) and the category for $categoryslug (if not null),
	return the selectspec to retrieve the antecedent questions for those answers,
	with the corresponding vote on those questions made by $voteuserid (if not null).
	The selectspec will also retrieve some information about the answers themselves
	(including the content if $fullanswers is true), in columns named with the prefix 'o'.
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid);
		
		$selectspec['arraykey']='opostid';

		$selectspec['columns']['obasetype']="'A'";
		$selectspec['columns']['opostid']='aposts.postid';
		$selectspec['columns']['ouserid']='aposts.userid';
		$selectspec['columns']['ocookieid']='aposts.cookieid';
		$selectspec['columns']['oip']='INET_NTOA(aposts.createip)';
		$selectspec['columns']['otime']='UNIX_TIMESTAMP(aposts.created)';

		if ($fullanswers) {
			$selectspec['columns']['ocontent']='BINARY aposts.content';
			$selectspec['columns']['oformat']='aposts.format';
		}

		$selectspec['columns']['opoints']='auserpoints.points';
		
		if (!QA_EXTERNAL_USERS) {
			$selectspec['columns']['oflags']='ausers.flags';
			$selectspec['columns']['olevel']='ausers.level';
			$selectspec['columns']['oemail']='BINARY ausers.email';
			$selectspec['columns']['ohandle']='BINARY ausers.handle';
			$selectspec['columns']['oavatarblobid']='BINARY ausers.avatarblobid'; // cast to BINARY due to MySQL bug which renders it signed in a union
			$selectspec['columns']['oavatarwidth']='ausers.avatarwidth';
			$selectspec['columns']['oavatarheight']='ausers.avatarheight';
		}
		
		$selectspec['source'].=" JOIN ^posts AS aposts ON ^posts.postid=aposts.parentid".
			(QA_EXTERNAL_USERS ? "" : " LEFT JOIN ^users AS ausers ON aposts.userid=ausers.userid").
			" LEFT JOIN ^userpoints AS auserpoints ON aposts.userid=auserpoints.userid".
			" JOIN (SELECT postid FROM ^posts WHERE ".
			(isset($categoryslug) ? "categoryid=(SELECT categoryid FROM ^categories WHERE tags=$ LIMIT 1) AND " : "").
			(isset($createip) ? "createip=INET_ATON($) AND " : "").
			"type=$ ORDER BY ^posts.created DESC LIMIT #,#) y ON aposts.postid=y.postid WHERE ^posts.type!='Q_HIDDEN'";
			
		if (isset($categoryslug))
			$selectspec['arguments'][]=$categoryslug;

		if (isset($createip))
			$selectspec['arguments'][]=$createip;

		array_push($selectspec['arguments'], $hidden ? 'A_HIDDEN' : 'A', $start, $count);

		$selectspec['sortdesc']='otime';
		
		return $selectspec;
	}

	
	function qa_db_recent_c_qs_selectspec($voteuserid, $start, $categoryslug=null, $createip=null, $hidden=false, $fullcomments=false, $count=QA_DB_RETRIEVE_QS_AS)
/*
	For $count most recent ($hidden or not) comments, starting from offset $start,
	restricted to $createip (if not null) and the category for $categoryslug (if not null),
	return the selectspec to retrieve the antecedent questions for those comments,
	with the corresponding vote on those questions made by $voteuserid (if not null).
	The selectspec will also retrieve some information about the comments themselves
	(including the content if $fullanswers is true), in columns named with the prefix 'o'.
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid);
		
		$selectspec['arraykey']='opostid';

		$selectspec['columns']['obasetype']="'C'";
		$selectspec['columns']['opostid']='cposts.postid';
		$selectspec['columns']['ouserid']='cposts.userid';
		$selectspec['columns']['ocookieid']='cposts.cookieid';
		$selectspec['columns']['oip']='INET_NTOA(cposts.createip)';
		$selectspec['columns']['otime']='UNIX_TIMESTAMP(cposts.created)';

		if ($fullcomments) {
			$selectspec['columns']['ocontent']='BINARY cposts.content';
			$selectspec['columns']['oformat']='cposts.format';
		}

		$selectspec['columns']['opoints']='cuserpoints.points';
		
		if (!QA_EXTERNAL_USERS) {
			$selectspec['columns']['oflags']='cusers.flags';
			$selectspec['columns']['olevel']='cusers.level';
			$selectspec['columns']['oemail']='BINARY cusers.email';
			$selectspec['columns']['ohandle']='BINARY cusers.handle';
			$selectspec['columns']['oavatarblobid']='BINARY cusers.avatarblobid'; // cast to BINARY due to MySQL bug which renders it signed in a union
			$selectspec['columns']['oavatarwidth']='cusers.avatarwidth';
			$selectspec['columns']['oavatarheight']='cusers.avatarheight';
		}
		
		$selectspec['source'].=" JOIN ^posts AS parentposts ON".
			" ^posts.postid=(CASE parentposts.type WHEN 'A' THEN parentposts.parentid ELSE parentposts.postid END)".
			" JOIN ^posts AS cposts ON parentposts.postid=cposts.parentid".
			(QA_EXTERNAL_USERS ? "" : " LEFT JOIN ^users AS cusers ON cposts.userid=cusers.userid").
			" LEFT JOIN ^userpoints AS cuserpoints ON cposts.userid=cuserpoints.userid".
			" JOIN (SELECT postid FROM ^posts WHERE ".
			(isset($categoryslug) ? "categoryid=(SELECT categoryid FROM ^categories WHERE tags=$ LIMIT 1) AND " : "").
			(isset($createip) ? "createip=INET_ATON($) AND " : "").
			"type=$ ORDER BY ^posts.created DESC LIMIT #,#) y ON cposts.postid=y.postid WHERE (^posts.type!='Q_HIDDEN') AND (parentposts.type!='A_HIDDEN')";
			
		if (isset($categoryslug))
			$selectspec['arguments'][]=$categoryslug;

		if (isset($createip))
			$selectspec['arguments'][]=$createip;

		array_push($selectspec['arguments'], $hidden ? 'C_HIDDEN' : 'C', $start, $count);

		$selectspec['sortdesc']='otime';
		
		return $selectspec;
	}
	
	
	function qa_db_recent_edit_qs_selectspec($voteuserid, $start, $categoryslug=null, $lastip=null, $onlyvisible=true, $full=false, $count=QA_DB_RETRIEVE_QS_AS)
/*
	For $count most recently edited posts, starting from offset $start, restricted to
	edits by $lastip (if not null), the category for $categoryslug (if not null) and only visible posts (if $onlyvisible),
	return the selectspec to retrieve the antecedent questions for those edits,
	with the corresponding vote on those questions made by $voteuserid (if not null).
	The selectspec will also retrieve some information about the edited posts themselves
	(including the content if $full is true), in columns named with the prefix 'o'.
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid);
		
		$selectspec['arraykey']='opostid';

		$selectspec['columns']['obasetype']='LEFT(editposts.type, 1)';
		$selectspec['columns']['oedited']='1';
		$selectspec['columns']['opostid']='editposts.postid';
		$selectspec['columns']['ouserid']='editposts.lastuserid';
		$selectspec['columns']['ocookieid']='editposts.cookieid';
		$selectspec['columns']['oip']='INET_NTOA(editposts.lastip)';
		$selectspec['columns']['otime']='UNIX_TIMESTAMP(editposts.updated)';
		$selectspec['columns']['opoints']='edituserpoints.points';
		
		if ($full) {
			$selectspec['columns']['ocontent']='BINARY editposts.content';
			$selectspec['columns']['oformat']='editposts.format';
		}
		
		if (!QA_EXTERNAL_USERS) {
			$selectspec['columns']['oflags']='editusers.flags';
			$selectspec['columns']['olevel']='editusers.level';
			$selectspec['columns']['oemail']='BINARY editusers.email';
			$selectspec['columns']['ohandle']='BINARY editusers.handle';
			$selectspec['columns']['oavatarblobid']='BINARY editusers.avatarblobid'; // // cast to BINARY due to MySQL bug which renders it signed in a union
			$selectspec['columns']['oavatarwidth']='editusers.avatarwidth';
			$selectspec['columns']['oavatarheight']='editusers.avatarheight';
		}

		$selectspec['source'].=" JOIN ^posts AS parentposts ON".
			" ^posts.postid=IF(parentposts.type IN ('Q', 'Q_HIDDEN'), parentposts.postid, parentposts.parentid)".
			" JOIN ^posts AS editposts ON parentposts.postid=IF(editposts.type IN ('Q', 'Q_HIDDEN'), editposts.postid, editposts.parentid)".
			(QA_EXTERNAL_USERS ? "" : " LEFT JOIN ^users AS editusers ON editposts.lastuserid=editusers.userid").
			" LEFT JOIN ^userpoints AS edituserpoints ON editposts.userid=edituserpoints.userid".
			" JOIN (SELECT postid FROM ^posts WHERE ".
			(isset($categoryslug) ? "categoryid=(SELECT categoryid FROM ^categories WHERE tags=$ LIMIT 1) AND " : "").
			(isset($lastip) ? "lastip=INET_ATON($) AND " : "").
			($onlyvisible ? "type IN ('Q', 'A', 'C')" : "1").
			" ORDER BY ^posts.updated DESC LIMIT #,#) y ON editposts.postid=y.postid".
			($onlyvisible ? " WHERE parentposts.type IN ('Q', 'A', 'C') AND ^posts.type IN ('Q', 'A', 'C')" : "");
			
		if (isset($categoryslug))
			$selectspec['arguments'][]=$categoryslug;

		if (isset($lastip))
			$selectspec['arguments'][]=$lastip;
			
		array_push($selectspec['arguments'], $start, $count);

		$selectspec['sortdesc']='otime';
		
		return $selectspec;		
	}


	function qa_db_full_post_selectspec($voteuserid, $postid)
/*
	Return the selectspec to retrieve the full information for $postid, with the corresponding vote made by $voteuserid (if not null)
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid, true);

		$selectspec['source'].=" WHERE ^posts.postid=#";
		$selectspec['arguments'][]=$postid;
		$selectspec['single']=true;

		return $selectspec;
	}

	
	function qa_db_full_child_posts_selectspec($voteuserid, $parentid)
/*
	Return the selectspec to retrieve the full information for all posts whose parent is $parentid,
	with the corresponding vote made by $voteuserid (if not null)
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid, true);
		
		$selectspec['source'].=" WHERE ^posts.parentid=#";
		$selectspec['arguments'][]=$parentid;
		
		return $selectspec;
	}


	function qa_db_full_a_child_posts_selectspec($voteuserid, $questionid)
/*
	Return the selectspec to retrieve the full information for all posts whose parent is an answer which
	has $questionid as its parent, with the corresponding vote made by $voteuserid (if not null)
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid, true);
		
		$selectspec['source'].=" JOIN ^posts AS parents ON ^posts.parentid=parents.postid WHERE parents.parentid=# AND (parents.type='A' OR parents.type='A_HIDDEN')" ;
		$selectspec['arguments'][]=$questionid;
		
		return $selectspec;
	}
	

	function qa_db_post_parent_q_selectspec($questionid)
/*
	Return the selectspec to retrieve the question for the parent of $questionid (where $questionid is a follow-on question),
	i.e. the parent of $questionid's parent if $questionid's parent is an answer, otherwise $questionid's parent itself.
*/
	{
		$selectspec=qa_db_posts_basic_selectspec();
		
		$selectspec['source'].=" WHERE ^posts.postid=(SELECT IF((parent.type='A') OR (parent.type='A_HIDDEN'), parent.parentid, parent.postid) FROM ^posts AS child LEFT JOIN ^posts AS parent ON parent.postid=child.parentid WHERE child.postid=#)";
		$selectspec['arguments']=array($questionid);
		$selectspec['single']=true;
		
		return $selectspec;
	}
	

	function qa_db_related_qs_selectspec($voteuserid, $questionid, $count=QA_DB_RETRIEVE_QS_AS)
/*
	Return the selectspec to retrieve the $count most closely related questions to $questionid,
	with the corresponding vote made by $voteuserid (if not null). This works by looking for other
	questions which have title words, tag words or a category in common.
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid);
		
		$selectspec['columns'][]='score';
		
		// added LOG(postid)/1000000 here to ensure ordering is deterministic even if several posts have same score
		
		$selectspec['source'].=" JOIN (SELECT postid, SUM(score)+LOG(postid)/1000000 AS score FROM ((SELECT ^titlewords.postid, LOG(#/titlecount) AS score FROM ^titlewords JOIN ^words ON ^titlewords.wordid=^words.wordid JOIN ^titlewords AS source ON ^titlewords.wordid=source.wordid WHERE source.postid=# AND titlecount<#) UNION ALL (SELECT ^posttags.postid, 2*LOG(#/tagcount) AS score FROM ^posttags JOIN ^words ON ^posttags.wordid=^words.wordid JOIN ^posttags AS source ON ^posttags.wordid=source.wordid WHERE source.postid=# AND tagcount<#) UNION ALL (SELECT ^posts.postid, LOG(#/^categories.qcount) FROM ^posts JOIN ^categories ON ^posts.categoryid=^categories.categoryid AND ^posts.type='Q' WHERE ^categories.categoryid=(SELECT categoryid FROM ^posts WHERE postid=#) AND ^categories.qcount<#)) x GROUP BY postid ORDER BY score DESC LIMIT #) y ON ^posts.postid=y.postid";
		
		array_push($selectspec['arguments'], QA_IGNORED_WORDS_FREQ, $questionid, QA_IGNORED_WORDS_FREQ, QA_IGNORED_WORDS_FREQ,
			$questionid, QA_IGNORED_WORDS_FREQ, QA_IGNORED_WORDS_FREQ, $questionid, QA_IGNORED_WORDS_FREQ, $count);
			
		$selectspec['sortdesc']='score';
			
		return $selectspec;
	}
	

	function qa_db_search_posts_selectspec($voteuserid, $titlewords, $contentwords, $tagwords, $handlewords, $start, $full=false, $count=QA_DB_RETRIEVE_QS_AS)
/*
	Return the selectspec to retrieve the $count top question matches, starting from the offset $start,
	with the corresponding vote made by $voteuserid (if not null) and including $full content or not.
	The search is performed for any of $titlewords in the title, $contentwords in the content (of the
	question or an answer or comment for whom that is the antecedent question), $tagwords in tags, and
	for question author usernames which match a word in $handlewords (so this won't help find a question
	by an author with a handle that contains more than one word). The results also include a 'score' column
	based on the matching strength, and a 'matchparts' column that tells us where the score came from
	(since a question could get weight from a match in the question itself, and/or weight from a match in
	its answers, comments, or comments on answers). The 'matchparts' is a comma-separated list of tuples
	matchtype:matchpostid:matchscore to be used with qa_search_max_match_anchor().
*/
	{
		// add LOG(postid)/1000000 here to ensure ordering is deterministic even if several posts have same score

		$selectspec=qa_db_posts_basic_selectspec($voteuserid, $full);
		
		$selectspec['columns'][]='score';
		$selectspec['columns'][]='matchparts';
		$selectspec['source'].=" JOIN (SELECT questionid, SUM(score)+LOG(questionid)/1000000 AS score, GROUP_CONCAT(CONCAT_WS(':', matchposttype, matchpostid, ROUND(score,3))) AS matchparts FROM (";
		$selectspec['sortdesc']='score';
		
		$selectparts=0;
		
		if (!empty($titlewords)) {
			// At the indexing stage, duplicate words in title are ignored, so this doesn't count multiple appearances.
			
			$selectspec['source'].=($selectparts++ ? " UNION ALL " : "").
				"(SELECT postid AS questionid, LOG(#/titlecount) AS score, _utf8 'Q' AS matchposttype, postid AS matchpostid FROM ^titlewords JOIN ^words ON ^titlewords.wordid=^words.wordid WHERE word IN ($) AND titlecount<#)";

			array_push($selectspec['arguments'], QA_IGNORED_WORDS_FREQ, $titlewords, QA_IGNORED_WORDS_FREQ);
		}
		
		if (!empty($contentwords)) {
			// (1-1/(1+count)) weights words in content based on their frequency: If a word appears once in content
			// it's equivalent to 1/2 an appearance in the title (ignoring the contentcount/titlecount factor).
			// If it appears an infinite number of times, it's equivalent to one appearance in the title.
			// This will discourage keyword stuffing while still giving some weight to multiple appearances.
			// On top of that, answer matches are worth half a question match, and comment matches half again.
			
			$selectspec['source'].=($selectparts++ ? " UNION ALL " : "").
				"(SELECT questionid, (1-1/(1+count))*LOG(#/contentcount)*(CASE ^contentwords.type WHEN 'Q' THEN 1.0 WHEN 'A' THEN 0.5 ELSE 0.25 END) AS score, ^contentwords.type AS matchposttype, ^contentwords.postid AS matchpostid FROM ^contentwords JOIN ^words ON ^contentwords.wordid=^words.wordid WHERE word IN ($) AND contentcount<#)";

			array_push($selectspec['arguments'], QA_IGNORED_WORDS_FREQ, $contentwords, QA_IGNORED_WORDS_FREQ);
		}
		
		if (!empty($tagwords)) {
			// Appearances in the tag list count like 2 appearances in the title (ignoring the tagcount/titlecount factor).
			// This is because tags express explicit semantic intent, whereas titles do not necessarily.
			
			$selectspec['source'].=($selectparts++ ? " UNION ALL " : "").
				"(SELECT postid AS questionid, 2*LOG(#/tagcount) AS score, _utf8 'Q' AS matchposttype, postid AS matchpostid FROM ^posttags JOIN ^words ON ^posttags.wordid=^words.wordid WHERE word IN ($) AND tagcount<#)";

			array_push($selectspec['arguments'], QA_IGNORED_WORDS_FREQ, $tagwords, QA_IGNORED_WORDS_FREQ);
		}
		
		if (!empty($handlewords)) {
			if (QA_EXTERNAL_USERS) {
				$userids=qa_get_userids_from_public($handlewords);
				
				if (count($userids)) {
					$selectspec['source'].=($selectparts++ ? " UNION ALL " : "").
						"(SELECT postid AS questionid, LOG(#/qposts) AS score, _utf8 'Q' AS matchposttype, postid AS matchpostid FROM ^posts JOIN ^userpoints ON ^posts.userid=^userpoints.userid WHERE ^posts.userid IN ($) AND type='Q')";
					
					array_push($selectspec['arguments'], QA_IGNORED_WORDS_FREQ, $userids);
				}

			} else {
				$selectspec['source'].=($selectparts++ ? " UNION ALL " : "").
					"(SELECT postid AS questionid, LOG(#/qposts) AS score, _utf8 'Q' AS matchposttype, postid AS matchpostid FROM ^posts JOIN ^users ON ^posts.userid=^users.userid JOIN ^userpoints ON ^userpoints.userid=^users.userid WHERE handle IN ($) AND type='Q')";

				array_push($selectspec['arguments'], QA_IGNORED_WORDS_FREQ, $handlewords);
			}
		}
		
		if ($selectparts==0)
			$selectspec['source'].='(SELECT NULL as questionid, 0 AS score, NULL AS matchposttype, NULL AS matchpostid FROM ^posts WHERE postid=NULL)';

		$selectspec['source'].=") x GROUP BY questionid ORDER BY score DESC LIMIT #,#) y ON ^posts.postid=y.questionid";
		
		array_push($selectspec['arguments'], $start, $count);
		
		return $selectspec;
	}

	
	function qa_search_max_match_anchor($question)
/*
	Processes the matchparts column in $question which was returned from a search performed via qa_db_search_posts_selectspec()
	Returns the id of the strongest matching answer or comment, or null if the question itself was the strongest match
*/
	{
		$anchorscore=array();

		$matchparts=explode(',', $question['matchparts']);
		foreach ($matchparts as $matchpart)
			if (sscanf($matchpart, '%1s:%f:%f', $matchposttype, $matchpostid, $matchscore)==3)
				@$anchorscore[qa_anchor($matchposttype, $matchpostid)]+=$matchscore;
		
		if (count($anchorscore)) {
			$anchor=array_search(max($anchorscore), $anchorscore);
			if ($anchor != qa_anchor('Q', $question['postid']))
				return $anchor;
		}
		
		return null;
	}
	

	function qa_db_categories_selectspec()
/*
	Return the selectspec to retrieve the list of categories, ordered for display
*/
	{
		return array(
			'columns' => array('categoryid', 'title' => 'BINARY title', 'tags' => 'BINARY tags', 'qcount', 'position'),
			'source' => '^categories ORDER BY position',
			'arraykey' => 'categoryid',
			'sortasc' => 'position',
		);
	}
	
	
	function qa_db_slug_to_category_id_selectspec($slug)
/*
	Return the selectspec to retrieve a single category as specified by its slug (tags column)
*/
	{
		return array(
			'columns' => array('categoryid'),
			'source' => '^categories WHERE tags=$',
			'arguments' => array($slug),
			'arrayvalue' => 'categoryid',
			'single' => true,
		);
	}
	
	
	function qa_db_pages_selectspec($onlynavin=null)
/*
	Return the selectspec to retrieve the list of custom pages or links, ordered for display
*/
	{
		$selectspec=array(
			'columns' => array('pageid', 'title' => 'BINARY title', 'flags', 'nav', 'tags' => 'BINARY tags', 'position'),
			'arraykey' => 'pageid',
			'sortasc' => 'position',
		);
		
		if (isset($onlynavin)) {
			$selectspec['source']='^pages WHERE nav IN ($) ORDER BY position';
			$selectspec['arguments']=array($onlynavin);
		} else
			$selectspec['source']='^pages ORDER BY position';
		
		return $selectspec;
	}
	
	
	function qa_db_page_full_selectspec($slugorpageid, $ispageid)
/*
	Return the selectspec to retrieve the full information about a custom page
*/
	{
		return array(
			'columns' => array('pageid', 'title' => 'BINARY title', 'flags', 'nav', 'tags' => 'BINARY tags', 'position', 'heading' => 'BINARY heading', 'content' => 'BINARY content'),
			'source' => '^pages WHERE '.($ispageid ? 'pageid' : 'tags').'=$',
			'arguments' => array($slugorpageid),
			'single' => true,
		);
	}
	
	
	function qa_db_tag_recent_qs_selectspec($voteuserid, $tag, $start, $full=false, $count=QA_DB_RETRIEVE_QS_AS)
/*
	Return the selectspec to retrieve $count recent questions with $tag, starting from offset $start,
	with the corresponding vote on those questions made by $voteuserid (if not null) and including $full content or not
*/
	{
		require_once QA_INCLUDE_DIR.'qa-util-string.php';
		
		$selectspec=qa_db_posts_basic_selectspec($voteuserid, $full);
		
		// use two tests here - one which can use the index, and the other which narrows it down exactly - then limit to 1 just in case
		$selectspec['source'].=" JOIN (SELECT postid FROM ^posttags WHERE wordid=(SELECT wordid FROM ^words WHERE word=$ AND word=$ COLLATE utf8_bin LIMIT 1) ORDER BY postcreated DESC LIMIT #,#) y ON ^posts.postid=y.postid";
		array_push($selectspec['arguments'], $tag, qa_strtolower($tag), $start, $count);
		$selectspec['sortdesc']='created';
		
		return $selectspec;
	}

	
	function qa_db_tag_count_qs_selectspec($tag)
/*
	Return the selectspec to retrieve the number of questions tagged with $tag (single value)
*/
	{
		return array(
			'columns' => array('tagcount'),
			'source' => '^words WHERE word=$',
			'arguments' => array($tag),
			'arrayvalue' => 'tagcount',
			'single' => true,
		);
	}

	
	function qa_db_user_recent_qs_selectspec($voteuserid, $identifier, $count=QA_DB_RETRIEVE_QS_AS)
/*
	Return the selectspec to retrieve $count recent questions by the user identified by $identifier, where
	$identifier is a handle if we're using internal user management, or a userid if QA_EXTERNAL_USERS.
	Also include the corresponding vote on those questions made by $voteuserid (if not null).
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid, false, false);
		
		$selectspec['source'].=" WHERE ^posts.userid=".(QA_EXTERNAL_USERS ? "$" : "(SELECT userid FROM ^users WHERE handle=$ LIMIT 1)")." AND type='Q' ORDER BY ^posts.created DESC LIMIT #";
		array_push($selectspec['arguments'], $identifier, $count);
		$selectspec['sortdesc']='created';
		
		return $selectspec;
	}

	
	function qa_db_user_recent_a_qs_selectspec($voteuserid, $identifier, $count=QA_DB_RETRIEVE_QS_AS)
/*
	For $count recent answers by the user identified by $identifier (see qa_db_user_recent_qs_selectspec() comment)
	return the selectspec to retrieve the antecedent questions for those answers, with the corresponding
	vote on those questions made by $voteuserid (if not null). The selectspec will also retrieve some
	information about the answers themselves, in columns named with the prefix 'o'.
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid);
		
		$selectspec['arraykey']='opostid';

		$selectspec['columns']['obasetype']="'A'";
		$selectspec['columns']['opostid']='aposts.postid';
		$selectspec['columns']['otime']='UNIX_TIMESTAMP(aposts.created)';
		
		$selectspec['source'].=" JOIN ^posts AS aposts ON ^posts.postid=aposts.parentid".
			" JOIN (SELECT postid FROM ^posts WHERE ".
			" userid=".(QA_EXTERNAL_USERS ? "$" : "(SELECT userid FROM ^users WHERE handle=$ LIMIT 1)").
			" AND type='A' ORDER BY created DESC LIMIT #) y ON aposts.postid=y.postid WHERE ^posts.type!='Q_HIDDEN'";
			
		array_push($selectspec['arguments'], $identifier, $count);
		$selectspec['sortdesc']='otime';
		
		return $selectspec;
	}

		
	function qa_db_user_recent_c_qs_selectspec($voteuserid, $identifier, $count=QA_DB_RETRIEVE_QS_AS)
/*
	For $count recent comments by the user identified by $identifier (see qa_db_user_recent_qs_selectspec() comment)
	return the selectspec to retrieve the antecedent questions for those comments, with the corresponding
	vote on those questions made by $voteuserid (if not null). The selectspec will also retrieve some
	information about the comments themselves, in columns named with the prefix 'o'.
*/
	{
		$selectspec=qa_db_posts_basic_selectspec($voteuserid);
		
		$selectspec['arraykey']='opostid';

		$selectspec['columns']['obasetype']="'C'";
		$selectspec['columns']['opostid']='cposts.postid';
		$selectspec['columns']['otime']='UNIX_TIMESTAMP(cposts.created)';
		
		$selectspec['source'].=" JOIN ^posts AS parentposts ON".
			" ^posts.postid=(CASE parentposts.type WHEN 'A' THEN parentposts.parentid ELSE parentposts.postid END)".
			" JOIN ^posts AS cposts ON parentposts.postid=cposts.parentid".
			" JOIN (SELECT postid FROM ^posts WHERE ".
			" userid=".(QA_EXTERNAL_USERS ? "$" : "(SELECT userid FROM ^users WHERE handle=$ LIMIT 1)").
			" AND type='C' ORDER BY created DESC LIMIT #) y ON cposts.postid=y.postid WHERE (^posts.type!='Q_HIDDEN') AND (parentposts.type!='A_HIDDEN')";
			
		array_push($selectspec['arguments'], $identifier, $count);
		$selectspec['sortdesc']='otime';
		
		return $selectspec;
	}
	
	
	function qa_db_popular_tags_selectspec($start, $count=QA_DB_RETRIEVE_TAGS)
/*
	Return the selectspec to retrieve the $count most popular tags, starting from offset $start.
	The selectspec will produce a sorted array with tags in the key, and counts in the values.
*/
	{
		return array(
			'columns' => array('word' => 'BINARY word', 'tagcount'),
			'source' => '^words JOIN (SELECT wordid FROM ^words WHERE tagcount>0 ORDER BY tagcount DESC LIMIT #,#) y ON ^words.wordid=y.wordid',
			'arguments' => array($start, $count),
			'arraykey' => 'word',
			'arrayvalue' => 'tagcount',
			'sortdesc' => 'tagcount',
		);
	}


	function qa_db_userfields_selectspec()
/*
	Return the selectspec to retrieve the list of user profile fields, ordered for display
*/
	{
		return array(
			'columns' => array('fieldid', 'title' => 'BINARY title', 'content' => 'BINARY content', 'flags', 'position'),
			'source' => '^userfields',
			'arraykey' => 'title',
			'sortasc' => 'position',
		);
	}

	
	function qa_db_user_account_selectspec($useridhandle, $isuserid)
/*
	Return the selecspec to retrieve a single array with details of the account of the user identified by
	$useridhandle, which should be a userid if $isuserid is true, otherwise $useridhandle should be a handle.
*/
	{
		return array(
			'columns' => array(
				'userid', 'passsalt', 'passcheck' => 'HEX(passcheck)', 'email' => 'BINARY email', 'level', 'handle' => 'BINARY handle', 'emailcode',
				'created' => 'UNIX_TIMESTAMP(created)', 'sessioncode', 'sessionsource', 'flags', 'loggedin' => 'UNIX_TIMESTAMP(loggedin)',
				'loginip' => 'INET_NTOA(loginip)', 'written' => 'UNIX_TIMESTAMP(written)', 'writeip' => 'INET_NTOA(writeip)',
				'avatarblobid', 'avatarwidth', 'avatarheight'
			),
			
			'source' => '^users WHERE '.($isuserid ? 'userid' : 'handle').'=$',
			'arguments' => array($useridhandle),
			'single' => true,
		);
	}

	
	function qa_db_user_profile_selectspec($useridhandle, $isuserid)
/*
	Return the selectspec to retrieve all user profile information of the user identified by
	$useridhandle (see qa_db_user_account_selectspec() comment), as an array of [field] => [value]
*/
	{
		return array(
			'columns' => array('title' => 'BINARY title', 'content' => 'BINARY content'),
			'source' => '^userprofile WHERE userid='.($isuserid ? '$' : '(SELECT userid FROM ^users WHERE handle=$ LIMIT 1)'),
			'arguments' => array($useridhandle),
			'arraykey' => 'title',
			'arrayvalue' => 'content',
		);
	}

	
	function qa_db_user_points_selectspec($identifier)
/*
	Return the selectspec to retrieve all columns from the userpoints table for the user identified by $identifier
	(see qa_db_user_recent_qs_selectspec() comment), as a single array
*/
	{
		return array(
			'columns' => array('points', 'qposts', 'aposts', 'cposts', 'aselects', 'aselecteds', 'qupvotes', 'qdownvotes', 'aupvotes', 'adownvotes', 'qvoteds', 'avoteds', 'upvoteds', 'downvoteds'),
			'source' => '^userpoints WHERE userid='.(QA_EXTERNAL_USERS ? '$' : '(SELECT userid FROM ^users WHERE handle=$ LIMIT 1)'),
			'arguments' => array($identifier),
			'single' => true,
		);
	}

	
	function qa_db_user_rank_selectspec($identifier)
/*
	Return the selectspec to calculate the rank in points of the user identified by $identifier
	(see qa_db_user_recent_qs_selectspec() comment), as a single value
*/
	{
		return array(
			'columns' => array('rank' => '1+COUNT(*)'),
			'source' => '^userpoints WHERE points>COALESCE((SELECT points FROM ^userpoints WHERE userid='.(QA_EXTERNAL_USERS ? '$' : '(SELECT userid FROM ^users WHERE handle=$ LIMIT 1)').'), 0)',
			'arguments' => array($identifier),
			'arrayvalue' => 'rank',
			'single' => true,
		);
	}

	
	function qa_db_top_users_selectspec($start, $count=QA_DB_RETRIEVE_USERS)
/*
	Return the selectspec to get the $count top scoring users, starting from offset $start, with handles
	if we're using internal user management
*/
	{
		if (QA_EXTERNAL_USERS)
			return array(
				'columns' => array('userid', 'points'),
				'source' => '^userpoints ORDER BY points DESC LIMIT #,#',
				'arguments' => array($start, $count),
				'arraykey' => 'userid',
				'sortdesc' => 'points',
			);
		
		else
			return array(
				'columns' => array('^users.userid', 'handle' => 'BINARY handle', 'points', 'flags', 'email' => 'BINARY ^users.email', 'avatarblobid', 'avatarwidth', 'avatarheight'),
				'source' => '^users JOIN (SELECT userid FROM ^userpoints ORDER BY points DESC LIMIT #,#) y ON ^users.userid=y.userid JOIN ^userpoints ON ^users.userid=^userpoints.userid',
				'arguments' => array($start, $count),
				'arraykey' => 'userid',
				'sortdesc' => 'points',
			);
	}

	
	function qa_db_users_from_level_selectspec($level)
/*
	Return the selectspec to get information about users at a certain privilege level or higher
*/
	{
		return array(
			'columns' => array('^users.userid', 'handle' => 'BINARY handle', 'level'),
			'source' => '^users WHERE level>=# ORDER BY level DESC',
			'arguments' => array($level),
			'sortdesc' => 'level',
		);
	}

	
	function qa_db_users_with_flag_selectspec($flag)
/*
	Return the selectspec to get information about users with the $flag bit set (unindexed query)
*/
	{
		return array(
			'columns' => array('^users.userid', 'handle' => 'BINARY handle', 'flags', 'level'),
			'source' => '^users WHERE (flags & #)',
			'arguments' => array($flag),
		);
	}
	

/*
	Omit PHP closing tag to help avoid accidental output
*/