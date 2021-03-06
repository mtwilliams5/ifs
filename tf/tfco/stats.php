<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net
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
  * Comments: Displays various TF statistics
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	// total ships
	$qry = "SELECT COUNT(*) FROM {$spre}ships WHERE tf='$tfid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($ships) = mysql_fetch_array($result);

	// active ships
	$qry = "SELECT count(*) FROM {$spre}ships
    		WHERE tf='$tfid' AND (status='Operational' OR status='Docked at Starbase')";
    $result = $database->openConnectionWithReturn($qry);
    list ($actships) = mysql_fetch_array($result);

	// open ships
	$qry = "SELECT count(co) FROM {$spre}ships WHERE tf='$tfid' AND co='0'";
    $result = $database->openConnectionWithReturn($qry);
    list ($openships) = mysql_fetch_array($result);

	$inships = $ships - $actships - $openships;
	$coships = $ships - $openships;

	// Character count
	$realcharcount = 0;
	$qry = "SELECT COUNT(*) FROM {$spre}characters c, {$spre}ships s
    		WHERE s.tf='$tfid' AND c.ship = s.id";
    $result = $database->openConnectionWithReturn($qry);
	list ($realcharcount) = mysql_fetch_array($result);

	// Characters assigned to active ships
	$actcharcount = 0;
	$qry = "SELECT COUNT(*) FROM {$spre}characters c, {$spre}ships s
    		WHERE (s.status='Operational' OR s.status='Docked at Starbase')
            	AND c.ship = s.id AND tf='$tfid'";
    $result = $database->openConnectionWithReturn($qry);
	list ($actcharcount) = mysql_fetch_array($result);

	// total players (users with at least one character)
	$qry = "SELECT c.player FROM {$spre}characters c, {$spre}ships s
    		WHERE s.tf='$tfid' AND c.ship=s.id GROUP BY player";
	$result = $database->openConnectionWithReturn($qry);
	$pnum = mysql_num_rows($result);

	$avgchar = round(($realcharcount / $coships),2);
	$actavgchar = round(($actcharcount / $actships),2);
	$sleepchar = $realcharcount - $actcharcount;
	$charperplay = round(($actcharcount / $pnum),2);

	?>
	<h1 class="text-center">Task Force <?php echo $tfid; ?> Statistics</h1>
	<br />
	<b>Total Ships: </b><?php echo $ships ?><br />
	&nbsp;&nbsp;&nbsp;<b>CO'ed Ships: </b><?php echo $coships ?><br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Active Ships: </b><?php echo $actships ?><br />
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Inactive Ships: </b><?php echo $inships ?><br />
	&nbsp;&nbsp;&nbsp;<b>Open Ships: </b><?php echo $openships ?><br />
	<br />
    <b>Total Characters:</b> <?php echo $realcharcount ?><br />
	&nbsp;&nbsp;&nbsp;<b>Active Characters:</b> <?php echo $actcharcount ?><br />
	&nbsp;&nbsp;&nbsp;<b>Inactive Characters:</b> <?php echo $sleepchar ?><br /><br />

	<b>Average Characters per CO'ed Ship:</b> <?php echo $avgchar ?><br />
	<b>Average characters per active ship:</b> <?php echo $actavgchar ?><br /><br />

	<b>Total Players:</b> <?php echo $pnum ?><br />
	<b>Average Characters per Player:</b> <?php echo $charperplay ?><br />

	<br /><br />
    <?php
	$qry = "SELECT tg, name FROM {$spre}taskforces WHERE tg!='0' AND tf='$tfid' ORDER BY tg";
	$result = $database->openConnectionWithReturn($qry);
	
	if (mysql_num_rows($result)>1){ ?>
        <h3>Task Group Listings</h3>
        <?php
        while ( list ($tgid,$tgname) = mysql_fetch_array($result) )
        {
            $qry2 = "SELECT id FROM {$spre}ships WHERE tf='$tfid' AND tg='$tgid'";
            $result2=$database->openConnectionWithReturn($qry2);
            $ships = mysql_num_rows($result2);
    
            $qry3 = "SELECT id FROM {$spre}ships
                     WHERE (status='Operational' OR status='Docked at Starbase')
                         AND tf='$tfid' AND tg='$tgid'";
            $result3=$database->openConnectionWithReturn($qry3);
            $actships = mysql_num_rows($result3);
    
            $qry3 = "SELECT id FROM {$spre}ships WHERE tf='$tfid' AND tg='$tgid' AND co='0'";
            $result3=$database->openConnectionWithReturn($qry3);
            $openships = mysql_num_rows($result3);
    
            $inships = $ships - $actships - $openships;
            $coships = $ships - $openships;
    
            $qry2 = "SELECT COUNT(*) FROM {$spre}ships s, {$spre}characters c
                     WHERE s.tf='$tfid' AND s.tg='$tgid' AND s.id = c.ship";
            $result2 = $database->openConnectionWithReturn($qry2);
            list($charcount) = mysql_fetch_array($result2);
    
            $qry2 = "SELECT COUNT(*) FROM {$spre}ships s, {$spre}characters c
                     WHERE s.tf='$tfid' AND s.tg='$tgid' AND s.id = c.ship
                        AND (s.status='Operational' OR s.status='Docked at Starbase')";
            $result2 = $database->openConnectionWithReturn($qry2);
            list($actcharcount) = mysql_fetch_array($result2);
    
            if ($coships != 0)
                $avgchar = round(($charcount / $coships),2);
            else
                $avgchar = 0;
            $actavgchar = round(($actcharcount / $actships),2);
    
            ?>
            <b>Task Group <?php echo $tgid; ?> -- <?php echo $tgname; ?></b><br />
            <b>Total Ships: </b><?php echo $ships; ?><br />
            &nbsp;&nbsp;&nbsp;<b>CO'ed Ships: </b><?php echo $coships; ?><br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Active Ships: </b><?php echo $actships; ?><br />
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>Inactive Ships: </b><?php echo $inships; ?><br />
            &nbsp;&nbsp;&nbsp;<b>Open Ships: </b><?php echo $openships; ?><br />
            <b>Total Characters:</b> <?php echo $charcount; ?><br />
            <b>Active Characters:</b> <?php echo $actcharcount; ?><br />
            <b>Average Characters per CO'ed Ship:</b> <?php echo $avgchar; ?><br />
            <b>Average Characters per active ship:</b> <?php echo $actavgchar; ?><br />
            <br />
            <?php
        }
	}
	?>

	<h3 class="heading">Ship Statistics</h3>

	<table class="table table-bordered">
      <thead>
		<tr>
        	<th>Class</th>
			<th>Operational</th>
			<th>Inactive</th>
			<th>Open</th>
        </tr>
      </thead>
      <tbody>

		<?php
		$qry = "SELECT c.name
	    		FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
	            WHERE c.category=d.id AND d.type=t.id AND t.support='n'
	            ORDER BY c.name";
		$result = $database->openShipsWithReturn($qry);
		while ( list ($cname) = mysql_fetch_array($result) )
        {
			?>
            <tr>
            	<td><?php echo $cname ?></td>
				<?php
				$qry2 = "SELECT id FROM {$spre}ships
						 WHERE class='$cname' AND tf='$tfid'
							AND (status='Operational' OR status='Docked at Starbase')";
				$result2 = $database->openConnectionWithReturn($qry2);
				$oper = mysql_num_rows($result2);
				?>
				<td<?php if($oper>0) echo ' class="text-success"'?>><?php echo $oper ?></td>
				<?php
				$qry2 = "SELECT id FROM {$spre}ships
						 WHERE class='$cname' AND tf='$tfid'
							AND (status='Waiting for Command Academy completion' OR status='Waiting for Crew')";
				$result2 = $database->openConnectionWithReturn($qry2);
				$inac = mysql_num_rows($result2);
				?>
				<td<?php if($inac>0) echo ' class="text-danger"'?>><?php echo $inac ?></td>
				<?php
				$qry2 = "SELECT id FROM {$spre}ships
						 WHERE class='$cname' AND tf='$tfid' AND status='Waiting for CO'";
				$result2 = $database->openConnectionWithReturn($qry2);
				$open = mysql_num_rows($result2);
				?>
				<td<?php if($open>0) echo ' class="text-info"'?>><?php echo $open ?></td>
            </tr>
            <?php
		}
		?>
	  </tbody>
    </table>
    <?php
}
?>