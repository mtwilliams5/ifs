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
  *     matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.13n:  December 2009
  * Patch 1.17:   June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Default theme for IFS
  *
  * See CHANGELOG for patch details
  *
 ***/

?>

<!-- Path to current page -->
<!-- Left menu -->
<table width="100%" border="0" cellspacing="3" cellpadding="0">
	<tr>
	    <td width="160" valign="top" rowspan="2">
	        <?php include("pathway.php"); ?>
            <br />
		<img src="images/menu.jpg"><br>
	        <?php include ("mainmenu.php") ?>
	        <br />
	        <?php include("leftComponent.php"); ?>
	    </td>

	    <!-- Now starts the top of the main area -->

	    <td valign="top" style="padding: 0 20px;">
	        <!-- News of the Day aka Messages -->
		<img src="images/motd.jpg"><br>
	        <div><?php include ("newsflash.php") ?></div>
	        <br />

	        <!-- Box thingy around the main content -->
	        <?php
	        // main body deciding what to display
	        require ("mainbody.php");
	        ?>
	    </td>

	    <td width="160" valign="top" class="poll" rowspan="2">
		<img src="images/date.jpg"><br>
	        <span class="newsarticle"><?php echo date("F j, Y"); ?></span><br />
	        <br />

	        <?php include("rightComponent.php"); ?>
	        <br />
	    </td>
	</tr>

    <tr>
    	<td>
	        <!-- Display one random OF Affiliate banner -->
	        <?include("banners.php");?>
	    </td>
    </tr>
</table>
