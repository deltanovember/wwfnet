<?php

/*
	Question2Answer 1.3.1 (c) 2011, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-include/qa-ajax.php
	Version: 1.3.1
	Date: 2011-02-01 12:56:28 GMT
	Description: Front line of response to Ajax requests, routing as appropriate


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

//	Output this header as early as possible

	header('Content-Type: text/plain');

//	Ensure no PHP errors are shown in the Ajax response

	@ini_set('display_errors', 0);

//	Load the QA base file which sets up a bunch of crucial functions

	require 'qa-base.php';

//	Get general Ajax parameters from the POST payload

	$qa_root_url_relative=qa_post_text('qa_root');
	$qa_request=qa_post_text('qa_request');
	$qa_operation=qa_post_text('qa_operation');

//	Perform the appropriate Ajax operation

	switch ($qa_operation) {
		case 'vote':
			require QA_INCLUDE_DIR.'qa-ajax-vote.php';
			break;
			
		case 'recalc':
			require QA_INCLUDE_DIR.'qa-ajax-recalc.php';
			break;
	}


/*
	Omit PHP closing tag to help avoid accidental output
*/