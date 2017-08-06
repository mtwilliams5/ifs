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
  * Comments: QuickLink for TFCO ship listings
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	?>
	<h3 class="heading text-center">TFCO Ship Listings</h3>
	<p class="text-center">Please note that your login will time out after about 10 minutes of inactivity.</p>

	<h5>With Graphics:</h5>
	<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=<?php echo $tfid ?>&amp;tg=all">All TF<?php echo $tfid ?> ships</a>

	<?php
	$qry = "SELECT tg, name FROM {$spre}taskforces WHERE tf='$tfid' AND tg!='0' ORDER BY tg";
	$result = $database->openConnectionWithReturn($qry);
	
	if(mysql_num_rows($result)>1){
		while (list ($tgid, $tgname) = mysql_fetch_array($result))
			echo '<br /><a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $tfid . '&amp;tg=' . $tgid . '">Task Group ' . $tgid . ' - ' . $tgname . '</a>';
	}
	?>

	<h5>Text-Only:</h5>
	<a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=<?php echo $tfid ?>&amp;tg=all&amp;textonly=1">All TF<?php echo $tfid ?> ships</a>

	<?php
	$qry = "SELECT tg, name FROM {$spre}taskforces WHERE tf='$tfid' AND tg!='0' ORDER BY tg";
	$result = $database->openConnectionWithReturn($qry);

	if(mysql_num_rows($result)>1){
		while (list ($tgid, $tgname) = mysql_fetch_array($result))
		echo '<br /><a role="button" class="btn btn-default btn-sm" href="index.php?option=ships&amp;tf=' . $tfid . '&amp;tg=' . $tgid . '&amp;textonly=1">Task Group ' . $tgid . ' - ' . $tgname . '</a>';
	}
}
?>