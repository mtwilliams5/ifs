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
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: JAG tools
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	$qry = "UPDATE {$spre}taskforces SET jag='$djag' WHERE tf='$tfid' AND tg='0'";
    $database->openConnectionNoReturn($qry);

	echo '<h2 class="text-center">Update Divisional JAG</h2>';
    echo '<p class="text-success">Divisional JAG for Task Force ' . $tfid . ' changed to ' . $djag . '.</p>';
}
?>