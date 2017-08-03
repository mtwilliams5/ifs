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
  * Comments: Main ship admin page for COs
  *
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h2 class="text-center">Welcome, <?php echo $name; ?>, to the CO's Administration Interface.</h2>
    <p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

    <?php
    $qry = "SELECT name FROM {$spre}characters WHERE (pos='Pending' OR pending='1') AND ship='$sid'";
    $result = $database->openConnectionWithReturn($qry);

    if ( mysql_num_rows($result) )
    {
        ?>
        <div id="pending-crew">
            <h3 class="text-center">You have pending crew members!!</h3>
            <ul class="list-group">
                <?php
                while ( list ($cname) = mysql_fetch_array($result) )
                    echo '<li class="list-group-item">' . $cname . '</li>';
                ?>
            </ul>
        </div>
        <?php
    }
    ship_view_info($database, $mpre, $spre, $sid, $uflag, $multiship);
    ?>
	<?php
}
?>
