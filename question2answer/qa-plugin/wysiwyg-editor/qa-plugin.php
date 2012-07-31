<?php

/*
	Question2Answer 1.3.1 (c) 2011, Gideon Greenspan

	http://www.question2answer.org/

	
	File: qa-plugin/wysiwyg-editor/qa-plugin.php
	Version: 1.3.1
	Date: 2011-02-01 12:56:28 GMT
	Description: Initiates WYSIWYG editor plugin


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

/*
	Plugin Name: WYSIWYG Editor
	Plugin URI: 
	Plugin Description: Wrapper for CKEditor WYSIWYG rich text editor
	Plugin Version: 1.0
	Plugin Date: 2010-10-31
	Plugin Author: Question2Answer
	Plugin Author URI: http://www.question2answer.org/
	Plugin License: GPLv2
	Plugin Minimum Question2Answer Version: 1.3
*/


	if (!defined('QA_VERSION')) { // don't allow this page to be requested directly from browser
		header('Location: ../../');
		exit;
	}


	qa_register_plugin_module('editor', 'qa-wysiwyg-editor.php', 'qa_wysiwyg_editor', 'WYSIWYG Editor');
	

/*
	Omit PHP closing tag to help avoid accidental output
*/