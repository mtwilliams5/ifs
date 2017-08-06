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
  * Patch 1.17:   August 2017
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

<div id="body">
	<div class="container-fluid">
	    <div class="row">

	    <!-- Now starts the top of the main area -->
	        <div class="col-md-8 col-md-push-2">
                <!-- News of the Day aka Messages -->
                <img src="images/motd.jpg"><br>
                <div><?php include ("newsflash.php") ?></div>
                <br />

                <!-- Box thingy around the main content -->
                <?php
                // main body deciding what to display
                require ("mainbody.php");
                ?>
            </div>
        <!-- Left menu -->
            <div class="col-xs-12 col-md-2 col-md-pull-8">
	            <?php include("pathway.php"); ?> <!-- Path to current page -->
                <br />
		        <img src="images/menu.jpg"><br>
	            <?php include ("mainmenu.php") ?>
	            <br />
	            <?php include("leftComponent.php"); ?>
            </div>

        <!-- Right menu -->
            <div class="col-md-2">
                <div class="poll">
                <img src="images/date.jpg"><br>
                <span class="newsarticle"><?php echo date("F j, Y"); ?></span><br />
                <br />

                <?php include("rightComponent.php"); ?>
                <br />
                </div>
            </div>
        </div> <!-- /row -->

        <div class="row">
            <div class="col-xs-12">
                <!-- Display one random Affiliate banner -->
                <?include("banners.php");?>
            </div>
        </div>
    </div>
</div>
