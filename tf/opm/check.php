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
* Comments: Check for players with more characters than the max, as set in configuration.php
*
* See CHANGELOG for patch details
*
***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	echo '<h2 class="text-center">Max Characters Audit</h2>';
	echo '<h3>The following user accounts have more than ' . $maxchars . ' active characters within the fleet.</h3>';
	echo '<br />';
	$none=true;
	$res=mysql_query("SELECT DISTINCT player FROM ifs_characters 
					WHERE ship<>" . UNASSIGNED_SHIP . " AND ship<>" . TRANSFER_SHIP . " AND ship<>" . DELETED_SHIP . " AND ship<>" . FSS_SHIP . "
					ORDER BY player");
	while (list($player)=mysql_fetch_array($res)) {
		list($uid, $email)=mysql_fetch_array(mysql_query("SELECT id, email FROM www_users WHERE id='$player'")); 
		$res2=mysql_query("SELECT name, pos, ship FROM ifs_characters WHERE player='$player' 
						AND ship<>" . UNASSIGNED_SHIP . " AND ship<>" . TRANSFER_SHIP . " AND ship<>" . DELETED_SHIP . " AND ship<>" . FSS_SHIP);
		$num=mysql_num_rows($res2);
		if ($num>$maxchars) {
			echo '<h4>User ID #' . $uid . ' - Email: ' . $email . ' (' . $num . ' characters)</h4>';
			echo '<div class="list-group">';
			while (list($name, $position, $ship)=mysql_fetch_row($res2)) {
				list($vessel)=mysql_fetch_array(mysql_query("SELECT name FROM ifs_ships WHERE id='$ship'"));
				echo '<li class="list-group-item">';
				echo '<h5 class="list-group-item-heading">' . $name . '</h5>';
				echo '<p class="list-group-item-text">On ' . $vessel . '</p>';
				echo '<p class="list-group-item-text">As ' . $position . '</p>';
				echo '</li>';
			}
			echo '</div>';
			$none=false;
		}
	}
	if ($none)
		echo '<h4>No players found who exceed ' . $maxchars . ' active characters in the fleet.</h4>';
}

?>