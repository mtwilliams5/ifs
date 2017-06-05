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
  * This file based on code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Display page
 ***/

class displaycontent
{
	function displaypage($content, $heading)
    {
	    if ($heading!="")
	    {
	    	?>
	        <h2 class="articlehead"><?php echo $heading ?></h2>
	        <?php
	    }
	    echo $content;
    }
}
?>