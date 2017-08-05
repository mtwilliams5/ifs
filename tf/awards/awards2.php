<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
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
  * Comments: Save Awards Admin
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	echo '<h1 class="text-center">Awards Admin</h1>';

	if ($action == "save")
    {
    	if ($aid != "add")
	    	$qry = "UPDATE {$spre}awards
            		SET name='$aname', image='$image', level='$level',
                    	intro='$intro', descrip='$descrip' WHERE id='$aid'";
		else
        	$qry = "INSERT INTO {$spre}awards
            		SET name='$aname', image='$image', level='$level',
                    	intro='$intro', descrip='$descrip', active='1'";
		$database->openConnectionNoReturn($qry);
    }
    elseif ($action == "del")
    {
    	$qry = "UPDATE {$spre}awards SET active='0' WHERE id='$aid'";
        $database->openConnectionNoReturn($qry);
    }
	redirect("&amp;action=edit");
    exit;
}
?>