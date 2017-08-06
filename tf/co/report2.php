<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net/ifs/
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Updated By: Nolan
  *		john.pbem@gmail.com
  *
  * Updated By: Matt Williams
  *       matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.13n:  December 2009
  * Patch 1.14n:  March 2010
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This program contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Submits & files monthly report
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
elseif ($sid == "0" || !$sid)
	echo '<h3 class="text-warning">Error!  Sim ID not specified!</h3>';
else
{
	$qry = "SELECT * FROM {$spre}ships WHERE id='$sid'";
	$result=$database->openConnectionWithReturn($qry);
	list($sid,$sname,$reg,$class,$site,$co,$xo,$tf,$tg,$status,$image,,,$desc)=mysql_fetch_array($result);

	$qry = "SELECT id, name, rank, pos, player FROM {$spre}characters WHERE ship='$sid'";
	$result=$database->openConnectionWithReturn($qry);

	$qry2 = "SELECT name, rank, player FROM {$spre}characters WHERE id='$co'";
	$result2 = $database->openConnectionWithReturn($qry2);
	list($coname, $corank, $uid) = mysql_fetch_array($result2);

	$qry2 = "SELECT email FROM " . $mpre . "users WHERE id='$uid'";
	$result2=$database->openConnectionWithReturn($qry2);
	list($coemail)=mysql_fetch_array($result2);
	$recip = $coemail;

	$qry3 = "SELECT co, tg FROM {$spre}taskforces WHERE tf='$tf'";
	$result3=$database->openConnectionWithReturn($qry3);
	while ( list($tgco,$tgid)=mysql_fetch_array($result3) )
    {
		if ($tgid == $tg || $tgid == 0)
        {
			$qry4 = "SELECT player FROM {$spre}characters WHERE id='$tgco'";
			$result4=$database->openConnectionWithReturn($qry4);
			list($pid)=mysql_fetch_array($result4);

			$qry4 = "SELECT email FROM " . $mpre . "users WHERE id='$pid'";
			$result4=$database->openConnectionWithReturn($qry4);
			list($tfemail)=mysql_fetch_array($result4);
			$recip .= ", " . $tfemail;
		}
	}
	
	//Add Personnel to report email
		$recip .= ", " . $personnelemail;
		
	//Add CFOps to report email
		$recip .= ", " . $fleetopsemail;

	$qry4 = "SELECT rankdesc FROM {$spre}rank WHERE rankid='$corank'";
	$result4=$database->openConnectionWithReturn($qry4);
	list($rank) = mysql_fetch_array($result4);
    $commoff = $rank . " " . $coname . " (" . $coemail . ")";

	$crewcount = 0;
	while ( list($cid,$cname,$crank,$pos,$player)=mysql_fetch_array($result) )
    {
		++$crewcount;

		$qry4 = "SELECT rankdesc FROM {$spre}rank WHERE rankid='$crank'";
		$result4=$database->openConnectionWithReturn($qry4);
		list($rank) = mysql_fetch_array($result4);

		$qry4 = "SELECT email FROM " . $mpre . "users WHERE id='$player'";
		$result4=$database->openConnectionWithReturn($qry4);
		list($email) = mysql_fetch_array($result4);

		$crewlisting .= $rank . " " . $cname . "\n" . $pos . "\n" . $email . "\n\n";
	}
	require_once "includes/mail/report_ship.mail.php";

	$header = "From: " . $coemail;
	mail ($recip, $mailersubject, $mailerbody, $header);

	$qry = "UPDATE {$spre}ships SET report=now() WHERE id=" . $sid;
	$result=$database->openConnectionWithReturn($qry);

	$date = time();
	$crewlisting = addslashes($crewlisting);
	$commoff = mysql_real_escape_string($commoff);
	// Use nl2br() to add line breaks in to larger text boxes before adding them to the database, for easier readability.
	$missdesc = nl2br($missdesc);
	$comments = nl2br($comments);
	// Now let's sanitise everything before we insert it
	$crewlisting = mysql_real_escape_string($crewlisting);
	$newcrew = mysql_real_escape_string($newcrew);
	$removedcrew = mysql_real_escape_string($removedcrew);
	$promotions = mysql_real_escape_string($promotions);
	$mission = mysql_real_escape_string($mission);
	$missdesc = mysql_real_escape_string($missdesc);
	$posts = mysql_real_escape_string($posts);
	$awards = mysql_real_escape_string($awards);
	$comments = mysql_real_escape_string($comments);
	
	$qry = "INSERT INTO {$spre}reports (date, ship, co, url, status, crew, crewlist, newcrew, removedcrew, promotions, mission, missdesc, posts, awards, comments)
			VALUES ('$date', '$sid', '$commoff', '$site',
    		'$status', '$crewcount', '$crewlisting', '$newcrew', '$removedcrew', '$promotions', '$mission',
            '$missdesc', '$posts', '$awards', '$comments')";
	$database->openConnectionNoReturn($qry);

	echo '<h2 class="text-success">Your report has been submitted.</h2>';
}
?>
