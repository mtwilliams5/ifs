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
  * Comments: Prepares monthly report
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	$qry = "SELECT name FROM {$spre}taskforces WHERE tf='$tfid' AND tg='0'";
    $result = $database->openConnectionWithReturn($qry);
    list ($tfname) = mysql_fetch_array($result);

	// total ships
	$qry = "SELECT COUNT(*) FROM {$spre}ships WHERE tf='$tfid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($ships) = mysql_fetch_array($result);

	// active ships
	$qry = "SELECT count(*) FROM {$spre}ships WHERE tf='$tfid' AND
    			(status='Operational' OR status='Docked at Starbase')";
    $result = $database->openConnectionWithReturn($qry);
    list ($actships) = mysql_fetch_array($result);

	// open ships
	$qry = "SELECT count(co) FROM {$spre}ships WHERE tf='$tfid' AND co='0'";
    $result = $database->openConnectionWithReturn($qry);
    list ($openships) = mysql_fetch_array($result);

	$inships = $ships - $actships - $openships;
	$coships = $ships - $openships;

  	// Total characters
	$qry = "SELECT COUNT(*) FROM {$spre}characters c, {$spre}ships s WHERE s.tf='$tfid' AND c.ship = s.id";
    $result = $database->openConnectionWithReturn($qry);
	list ($totalchar) = mysql_fetch_array($result);

    $avchar = $totalchar / $coships;

	?>
    <form method="post" action="index.php?option=ifs&amp;task=tfco&amp;action=save_report">
        <?php
        if ($adminship)
            echo '<input type="hidden" name="adminship" value="' . $adminship . '">';
        ?>
        <h4 class="text-center">Welcome to the monthly report generator.</h4>
        <p class="text-center">As a TFCO in <?php echo $fleetname ?>, you are required to submit a monthly report to the CFOps.</p>
        <p class="text-center">Please check and complete the following information</p>
        <p class="text-center">Your login will not time-out while submitting the report.</p>
        
        <h4>Task Force <?php echo $tfid . ' - ' . $tfname ?></h4>
        
        <ul class="list-unstyled">
          <li><strong>Total Ships:</strong> <?php echo $ships ?></li>
            <li><strong>&nbsp;&nbsp;&nbsp;CO'ed Ships:</strong> <?php echo $coships ?></li>
              <li><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Active Ships:</strong> <?php echo $actships ?></li>
              <li><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Inactive Ships:</strong> <?php echo $inships ?></li>
            <li><strong>&nbsp;&nbsp;&nbsp;Open Ships:</strong> <?php echo $openships ?></li>
          <br />
          <li><strong>Total Characters:</strong> <?php echo $totalchar ?></li>
          <li><strong>Average Characters per COed ship:</strong> <?php echo $avchar ?></li>
        </ul>
		
        <div class="form-group">
        	<label for="promotions">Promotions:</label>
        	<textarea class="form-control" name="promotions" id="promotions" rows="5" cols="60" wrap="physical"></textarea>
        </div>
        <div class="form-group">
        	<label for="newco">New COs since last report:</label>
        	<textarea class="form-control" name="newco" id="newco" rows="5" cols="60" wrap="physical"></textarea>
        </div>
        <div class="form-group">
        	<label for="resigned">COs that Resigned or were Removed since last report:</label>
        	<textarea class="form-control" name="resigned" id="resigned" rows="5" cols="60" wrap="physical"></textarea>
        </div>
        <div class="form-group">
        	<label for="improvements">Improvements since last report:</label>
        	<textarea class="form-control" name="improvements" id="improvements" rows="5" cols="60" wrap="physical"></textarea>
        </div>

        <div class="form-group">
        	<label for="notes">General Notes:</label>
        	<textarea class="form-control" name="notes" id="notes" rows="5" cols="60" wrap="physical"></textarea>
        </div>
        <div class="form-group">
            <input class="btn btn-success" type="submit" name="Submit" value="Submit Report">
            <input class="btn btn-danger" type="reset" name="Reset" value="Clear Form">
        </div>
    </form>
	<?php
}
?>