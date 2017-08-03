<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net/ifs/
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Updated By: Matt Williams
  *             matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.17:   June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * See CHANGELOG for patch details
  *
  * Comments: Pull fleetops, academy, personnel and admin emails from the database dynamically, if not manually set in config
  *
 ***/

function get_emails_by_access($flag, $database, $mpre) {
	
	$qry = "SELECT email, flags FROM {$mpre}users WHERE flags LIKE '%{$flag}%'";
	$result = $database->openConnectionWithReturn($qry);
	
	while (list ($email, $flags) = mysql_fetch_array($result)){
		if($flag != 'A') {
			if($flags{0} != 'A') { //We don't want admins to be pulled into every email, so don't include them for non-admin lists
				$i = 0;
				$emails[$i] = $email;
				$i++;
			}
		} else {
			$i = 0;
			$emails[$i] = $email;
			$i++;
		}
	}

	return $emails;
}

if($academail == '') {
	$emails = get_emails_by_access('D', $database, $mpre);
	if(count($emails) > 1) {
		foreach($emails as $e) {
			$academail .= $e . ', ';
		}
		$academail = substr($academail, 0, -3); //take off the trailing comma and space
	} else {
		$academail = $emails[0];
	}
}

if($fleetopsemail == '') {
	$emails = get_emails_by_access('O', $database, $mpre);
	if(count($emails) > 1) {
		foreach($emails as $e) {
			$fleetopsemail .= $e . ', ';
		}
		$fleetopsemail = substr($personnefleetopsemaillemail, 0, -3); //take off the trailing comma and space
	} else {
		$fleetopsemail = $emails[0];
	}
}

if($personnelemail == '') {
	$emails = get_emails_by_access('P', $database, $mpre);
	if(count($emails) > 1) {
		foreach($emails as $e) {
			$personnelemail .= $e . ', ';
		}
		$personnelemail = substr($personnelemail, 0, -3); //take off the trailing comma and space
	} else {
		$personnelemail = $emails[0];
	}
}

if($adminemail == '') {
	$emails = get_emails_by_access('A', $database, $mpre);
	if(count($emails) > 1) {
		foreach($emails as $e) {
			$adminemail .= $e . ', ';
		}
		$adminemail = substr($adminemail, 0, -3); //take off the trailing comma and space
	} else {
		$adminemail = $emails[0];
	}
}

?>
