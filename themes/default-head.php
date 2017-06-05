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
  *   matt@mtwilliams.uk
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
  *
  * See CHANGELOG for patch details
  *
***/
// This needs to be in a PHP echo() command to avoid PHP thinking it's a
//		PHP command
echo "<?xml version=\"1.0\" encoding=\"iso-8859-1\"?>\n";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?php echo fleetname ?></title>
    <link rel="stylesheet" href="<?php echo $relpath ?>themes/default.css" type="text/css" />
  </head>

  <body>
    <?php
    $newstop = "";
    $logintop = "";
    $searchtop = "";

    if (defined("IFS")){
    ?>
      <header id="head">
        <div class="text-center">
          <img src="<?php echo $fleetbanner ?>" alt="<?php echo $fleetname ?>">
        </div>
      </header><br />
    <?php } ?>
