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
*       matt@mtwilliams.uk
*
* Version:	1.17
* Release Date: June 3, 2004
* Patch 1.17:   August 2017
*
* Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
* Distributed under the terms of the GNU General Public License
* See doc/LICENSE for details
*
* Comments: Check for orphaned characters
*
* See CHANGELOG for patch details
*
***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	echo '<h1 class="text-center">Lost Souls Finder</h1>';
	echo '<h4>This tool is designed to find characters assigned to ship ids which no longer exist and transfer them to the unassigned characters pool.</h4>';
	
	$none=true;
	$res=mysql_query("SELECT id, ship, name FROM ifs_characters 
			WHERE ship<>" . UNASSIGNED_SHIP . " AND ship<>" . TRANSFER_SHIP . " AND ship<>" . DELETED_SHIP . " AND ship<>" . FSS_SHIP . " ORDER BY id");
	while (list($vd, $ve, $name)=mysql_fetch_array($res)) {
		$res2=mysql_query("SELECT id FROM ifs_ships WHERE id='$ve'");
		if (mysql_num_rows($res2)<1) {
			mysql_query("UPDATE ifs_characters set ship='" . UNASSIGNED_SHIP . "' WHERE id='$vd'");
			echo $name.' recovered to unassigned characters.<br />';
			$none=false;
		}
	}
	if (!$none)
		echo '<h5 class="text-success">All lost souls successfully transferred to the unassigned characters pool.</h5>';
	else
		echo '<h5>There were no lost souls to save!</h5>';
}

?>