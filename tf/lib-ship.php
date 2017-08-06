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
  * Patch 1.14n:  March 2010
  * Patch 1.15n:  April 2010
  * Patch 1.16n:  March 2014
  * Patch 1.17: August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This file contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Functions for viewing & manipulating ship info
  *
  * See CHANGELOG for patch details
  *
 ***/

function ship_add ($database, $mpre, $spre, $uflag, $tfid, $format, $sname, $class, $registry, $status, $grpid)
{
	$sname = mysql_real_escape_string($sname);
	$registry = mysql_real_escape_string($registry);
	
	$qry = "SELECT id FROM {$spre}ships WHERE name='$sname' OR registry='$registry'";
    $result = $database->openConnectionWithReturn($qry);
    if (mysql_num_rows($result))
    {
	    echo '<h1 class="text-center">Sim Not Added!!</h1>';
    	echo '<p class="text-center">Name and/or Registry already in use!</p>';
    }
    else
    {
		$qry = "INSERT INTO {$spre}ships
        		SET name='$sname', registry='$registry', class='$class',
                	tf='$tfid', tg='$grpid', status='$status', format='$format',
                    sorder='3'";
		$database->openConnectionNoReturn($qry);
		?>

	    <h1 class="text-center">Sim Added</h1>
	    <dl class="dl-horizontal center-block">
        <dt>Name:</dt>
        <dd><?php echo stripslashes($sname) ?></dd>
	    <dt>Registry</dt>
        <dd><?php echo stripslashes($registry) ?></dd>
		<dt>Class</dt>
        <dd><?php echo stripslashes($class) ?></dd>
	    <dt>Format</dt>
        <dd><?php echo $format ?></dd>
	    <dt>TF</dt>
        <dd><?php echo $tfid ?></dd>
	    <dt>TG</dt>
        <dd><?php echo $grpid ?></dd>
	    <dt>Status</dt>
        <dd><?php echo $status ?></dd>

		<?php
	    echo '<a role="button" class="btn btn-default" href="index.php?option=' . option . '&amp;task=' . task . '&amp;action=common&amp;lib=sview&amp;sid=' . mysql_insert_id() . '">Ship Manifest</a> ';
	    echo '<a role="button" class="btn btn-default" href="index.php?option=' . option . '&amp;task=' . task . '&amp;action=common&amp;lib=sadmin&amp;sid=' . mysql_insert_id() . '">Ship Admin</a>';
    }
}

// Save changes from admin screen
function ship_admin_save ($database, $mpre, $spre, $shipid, $coid, $name, $registry, $class, $website, $format, $grpid, $status, $image, $sorder, $notes, $uflag)
{
	$qry = "SELECT co, tf, status FROM {$spre}ships WHERE id='$shipid'";
	$result=$database->openConnectionWithReturn($qry);
	list($co, $tfid, $oldstatus)=mysql_fetch_array($result);

    // Change in CO?
	if ($co != $coid)
    {
		$qry = "SELECT id FROM {$spre}ships WHERE co='$coid'";
		$result=$database->openConnectionWithReturn($qry);
		list($sid)=mysql_fetch_array($result);

		if ($sid)
        {
			$qry = "UPDATE {$spre}ships SET co='0' WHERE id='$sid'";
			$database->openConnectionNoReturn($qry);
		}

		$qry = "UPDATE {$spre}characters SET ship='$shipid' WHERE id='$coid'";
		$database->openConnectionNoReturn($qry);

       	$qry = "SELECT u.id, u.flags FROM {$mpre}users u, {$spre}characters c WHERE c.id='$coid' AND c.player=u.id";
        $result = $database->openConnectionWithReturn($qry);
        list ($userid, $userflags) = mysql_fetch_array($result);

        if (!strstr($userflags, "c"))
        {
           	$userflags = "c" . $userflags;
            $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$userid'";
            $database->openConnectionNoReturn($qry);
        }

   		$qry = "SELECT u.id, u.flags FROM {$mpre}users u, {$spre}characters c WHERE c.id='$co' AND c.player=u.id";
  	    $result = $database->openConnectionWithReturn($qry);
        list ($userid, $userflags) = mysql_fetch_array($result);

        $userflags = preg_replace("/c/", "", $userflags);
   	    $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$userid'";
        $database->openConnectionNoReturn($qry);
	}

	if ( ($oldstatus == "Waiting for Command Academy completion" && $status != $oldstatus) ||
		 ($oldstatus == "Open" && $status != $oldstatus) )
    {
		$body = "$name changed status from $oldstatus to $status.\n\n";
		$body .= "Congratulate them in the Fleet Update, or whatever it is you do with this info.\n\n";
		$header = "From: " . email-from;
		mail ($webmasteremail, "Ship Status Change", $body, $header);
	}
	
	if($grpid == "") { $grpid = 0; }
	
	// Let's sanitise everything before we update the DB
	$name = mysql_real_escape_string($name);
	$registry = mysql_real_escape_string($registry);
	$class = mysql_real_escape_string($class);
	$website = mysql_real_escape_string($website);
	$image = mysql_real_escape_string($image);
	$notes = mysql_real_escape_string($notes);

	$qry = "UPDATE {$spre}ships
   			SET name='$name', registry='$registry', class='$class',
				website='$website', format='$format', co={$coid},
				tg={$grpid}, status='$status', image='$image',
				description='$notes'
                WHERE id={$shipid}";
	$result=$database->openConnectionWithReturn($qry);
	
    //Re-fetch the notes to deal with a few display issues
	$qry2 = "SELECT description from {$spre}ships WHERE id={$shipid}";
	$result2=$database->openConnectionWithReturn($qry2);
	list($desc)=mysql_fetch_array($result2);
    ?>

   	<h1 class="text-center">Sim info updated</h1>
    <dl class="dl-horizontal center-block">
    	<dt>Name:</dt>
        <dd><?php echo stripslashes($name) ?></dd>
        <dt>Registry:</dt>
        <dd><?php echo stripslashes($registry) ?></dd>
        <dt>Class:</dt>
        <dd><?php echo stripslashes($class) ?></dd>
        <dt>Website:</dt>
        <dd><?php echo stripslashes($website) ?></dd>
        <dt>Format:</dt>
        <dd><?php echo $format ?></dd>
        <dt>CO:</dt>
        <dd><?php echo $coid ?></dd>
        <dt>TG:</dt>
        <dd><?php echo $grpid ?></dd>
        <dt>Status:</dt>
        <dd><?php echo $status ?></dd>
        <dt>Image:</dt>
        <dd><?php echo stripslashes($image) ?></dd>
        <dt>Description:</dt>
        <dd><?php echo stripslashes($desc) ?></dd>
    </dl>
	<a role="button" class="btn btn-default" href="index.php?option=ships&amp;tf=<?php echo $tfid ?>&amp;tg=<?php echo $grpid ?>">Return to Sim Listing</a></p>

	<?php
}

// Empty a ship's crew, transfer them all to "Transferred"
function ship_clear_crew ($database, $mpre, $spre, $sid, $reason, $uflag)
{
	$qry = "SELECT name FROM {$spre}ships WHERE id='$sid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($sname) = mysql_fetch_array($result);

    $qry = "SELECT id, player, name FROM {$spre}characters WHERE ship='$sid'";
    $result = $database->openConnectionWithReturn($qry);
	$i=0;
    while (list($cid, $pid, $cname) = mysql_fetch_array($result))
    {
        $ptime = time();
		$pdate = date("F j, Y, g:i a", time());

		$qry2 = "UPDATE {$spre}characters
        		 SET ship='" . TRANSFER_SHIP . "', ptime='$ptime',
                 other='Transferred on $pdate' WHERE id='$cid'";
		$database->openConnectionNoReturn($qry2);

		$details = "Tranferred from: {$sname}<br />\n";
    	$details .= "Transferred to: Unassigned<br /><br />\n\n";
        $details .= $reason;
		$time = time();
	    $name = get_usertype($database, $mpre, $spre, $cid, $uflag);
		$qry2 = "INSERT INTO {$spre}record
        		 SET cid='$cid', pid='$pid', level='Out-of-Character',
                 date='$time', entry='Transfer', details='$details',
                 name='$name', admin='n'";
		$database->openConnectionNoReturn($qry2);
        $transnames[$i] = $cname;
		
		$i++;
    }

    $qry = "UPDATE {$spre}ships
    		SET co='0', xo='0', status='Waiting for CO '
            WHERE id='$sid'";
    $database->openConnectionNoReturn($qry);

    echo '<h1 class="text-center">';
    echo $sname. ' cleared.';
    echo '</h1>';
    echo '<h3>The following crew have been transferred to UNASSIGNED:</h3>';
	echo '<ul class="list-unstyled unassigned-crewlist">';
   	foreach ($transnames as $crew) {
		echo '<li>';
		echo $crew;
		echo '</li>';
	}
	echo '</ul>';
}

// Mothball a ship
function ship_delete ($database, $mpre, $spre, $sid, $uflag)
{
    $qry = "SELECT  name FROM {$spre}ships WHERE id='$sid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($sname) = mysql_fetch_array($result);

	// Set these crew as unassigned
    $qry = "SELECT id, name FROM {$spre}characters WHERE ship='$sid'";
    $result = $database->openConnectionWithReturn($qry);
	$i=0;
    while (list($cid, $cname) = mysql_fetch_array($result))
    {
    	$details = "Tranferred from: {$sname}<br />\n";
    	$details .= "Transferred to: Unassigned<br /><br />\n\n";
        $details .= "Ship deleted.";
		$time = time();
	    $name = get_usertype($database, $mpre, $spre, $cid, $uflag);
		$qry2 = "INSERT INTO {$spre}record
        		 SET cid='$cid', pid='$pid', level='Out-of-Character',
                 date='$time', entry='Transfer', details='$details',
                 name='$name', admin='n'";
		$database->openConnectionNoReturn($qry2);
        $transnames[$i] = $cname;
	}

	$qry2 = "UPDATE {$spre}characters
    		 SET ship='" . TRANSFER_SHIP . "' WHERE ship='$sid'";
	$database->openConnectionNoReturn($qry2);

	$qry2 = "UPDATE {$spre}ships SET tf='99',tg='2' WHERE id='$sid'";
	$database->openConnectionNoReturn($qry2);

    echo '<h1 class="text-center">';
    echo $sname . ' deleted.';
    echo '</h1>';
    echo '<h3>The following crew have been transferred to UNASSIGNED:</h3>';
	echo '<ul class="list-unstyled unassigned-crewlist">';
	if ($transnames) {
		foreach ($transnames as $crew) {
			echo '<li>';
			echo $crew;
			echo '</li>';
		}
    } else {
		echo '<li>';
    	echo "None";
		echo '</li>';
	}
	echo '</ul>';
}

// Edit "description"
function ship_edit_notes ($database, $mpre, $spre, $shipid, $notes, $uflag)
{
	$qry = "SELECT co FROM {$spre}ships WHERE id='$shipid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($coid) = mysql_fetch_array($result);
	
	$notes = mysql_real_escape_string($notes);

	$qry = "UPDATE {$spre}ships
    		SET description='$notes' WHERE id=$shipid";
	$database->openConnectionNoReturn($qry);

	$qry = "SELECT username FROM {$mpre}users WHERE id='" . uid . "'";
	$result = $database->openConnectionWithReturn($qry);
	list ($uname) = mysql_fetch_array($result);

	$qry = "INSERT INTO {$spre}logs
    	    (date, user, action, comments)
            VALUES (now(), '" . uid . " $uname', 'Description updated', '$notes on ship $shipid')";
	$database->openConnectionNoReturn($qry);

    $qry = "SELECT name, description FROM {$spre}ships WHERE id='$shipid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($sname, $desc) = mysql_fetch_array($result);

	echo '<h1 class="text-center">Update Description</h1>';
	echo '<dl>';
    echo '<dt>Mission Description for <em>' . $sname . '</em> changed to:</dt>';
    echo '<dd>' . stripslashes($desc) . '</dd>';
	echo '</dl>';
}

//Edit sim Play by type
function ship_edit_pbt ($database, $mpre, $spre, $sid, $pbtid, $uflag)
{
	$qry = "SELECT co FROM {$spre}ships WHERE id='$shipid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($coid) = mysql_fetch_array($result);

	$qry = "UPDATE {$spre}ships SET format='$pbtid' WHERE id='$sid'";
	$database->openConnectionNoReturn($qry);

	$qry = "SELECT username FROM {$mpre}users WHERE id='" . uid . "'";
	$result = $database->openConnectionWithReturn($qry);
	list ($uname) = mysql_fetch_array($result);

	$qry = "INSERT INTO {$spre}logs
    	    (date, user, action, comments)
            VALUES (now(), '" . uid . " $uname', 'Play By Type updated', '$pbtid on ship $sid')";
	$database->openConnectionNoReturn($qry);

	$qry = "SELECT name FROM {$spre}ships WHERE id='$sid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($sname) = mysql_fetch_array($result);

	echo '<h1 class="text-center">Update Play By Type</h1>';
	echo '<dl>';
	echo '<dt>Play By Type for <em>' . $sname . '</em> changed to:</dt>';
	echo '<dd>' . $pbtid . '</dd>';
	echo '</dl>';
}

//Edit sim website
function ship_edit_website ($database, $mpre, $spre, $shipid, $url, $uflag)
{
	$qry = "SELECT co FROM {$spre}ships WHERE id='$shipid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($coid) = mysql_fetch_array($result);
	
	// No URL should have strange special characters in it, but let's sanitise the input just in case.
	$url = mysql_real_escape_string($url);

	$qry = "UPDATE {$spre}ships SET website='$url' WHERE id=$shipid";
	$database->openConnectionNoReturn($qry);

	$qry = "SELECT username FROM {$mpre}users WHERE id='" . uid . "'";
	$result = $database->openConnectionWithReturn($qry);
	list ($uname) = mysql_fetch_array($result);

	$qry = "INSERT INTO {$spre}logs
    	    (date, user, action, comments)
            VALUES (now(), '" . uid . " $uname', 'Website updated', '$url on ship $shipid')";
	$database->openConnectionNoReturn($qry);

	$qry = "SELECT name FROM {$spre}ships WHERE id='$shipid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($sname) = mysql_fetch_array($result);

	echo '<h1 class="text-center">Update Website</h1>';
	echo '<dl>';
	echo '<dt>Website for <em>' . $sname . '</em> changed to:</dt>';
	echo '<dd>' . stripslashes($url) . '</dd>';
	echo '</dl>';
}

//Edit sim XO
function ship_edit_xo ($database, $mpre, $spre, $shipid, $xoid, $uflag)
{
	$qry = "SELECT co FROM {$spre}ships WHERE id='$shipid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($coid) = mysql_fetch_array($result);

	$qry = "UPDATE {$spre}ships SET xo=$xoid WHERE id=$shipid";
	$database->openConnectionNoReturn($qry);

	$qry = "SELECT username FROM {$mpre}users WHERE id='" . uid . "'";
	$result = $database->openConnectionWithReturn($qry);
	list ($uname) = mysql_fetch_array($result);

	$qry = "INSERT INTO {$spre}logs
    		(date, user, action, comments)
            VALUES (now(), '" . uid . " $uname', 'XO Updated', '$xoid on ship $shipid')";
	$database->openConnectionNoReturn($qry);

    $qry = "SELECT s.name, c.name
    		FROM {$spre}ships s, {$spre}characters c
            WHERE s.id='$shipid' AND s.xo=c.id";
    $result = $database->openConnectionWithReturn($qry);
    list ($sname, $cname) = mysql_fetch_array($result);

	echo '<h1 class="text-center">Update Executive Officer</h1>';
	echo '<dl>';
	echo '<dt>XO for <em>' . $sname . '</em> changed to:</dt>';
	echo '<dd>' . $cname . '</dd>';
	echo '</dl>';
}

function ship_list ($database, $mpre, $spre, $sdb, $uflag, $textonly, $relpath, $sid, $sname, $reg, $site, $image, $co, $xo, $status, $class, $format, $tf, $tg, $desc)
{
	// While everything going forward should be in the DB as real_escape_characters, let's assume that older stuff isn't, and strip the slashes for display:
	$sname = stripslashes($sname);
	$reg = stripslashes($reg);
	$site = stripslashes($site);
	$image = stripslashes($image);
	$class = stripslashes($class);
	$desc = stripslashes($desc);
	
	?>
	<div class="row sim-listing"> <!-- Start of row for this sim -->
        <div class="col-xs-12">
	        <hr>
	        <br />
	        <div class="row"> <!-- Sim Name Row -->
         	    <div class="col-xs-12 text-center">
                    <?php
                    if( $site != '')
                    {
                        echo '<a href="' . $site . '" target="_blank" class="sim-name">';
                        echo $sname . '</a>';
                    }
                    else
                    {
                        if ( $co > 0)
                        {
                            echo '<span class="sim-name">';
                            echo $sname .'</span>';
                        }
                        else
                        {
                            echo '<a href="index.php?option=app&task=co" class="sim-name">';
                            echo $sname .'</a>';
                        }
                    }
                    ?>
                </div>
            </div> <!-- End of Sim Name Row -->
            <div class="row"> <!-- Sim Image Row -->
                <div class="col-xs-12 text-center"> <!-- Sim Image -->
                    <?php 
                    if (!$textonly)
                    {
                        if ($site != '')
                        {
                            echo '<a href="' . $site .'" target="_blank">';
                            echo '<img src="' . $relpath .'images/ships/' . $image .'"  alt="' . $sname . ' banner" border="0" class="img-responsive center-block" >';
                            echo '</a>';
                        }
                        else
                        {
                            if ($co > 0)
                            {
                                echo '<img src="' . $relpath .'images/ships/' . $image .'" alt="' . $sname .'banner" border="0" class="img-responsive center-block" >';
                            }
                            else
                            {
                                echo '<a href="index.php?option=app&task=co">';
                                echo '<img src="' . $relpath . 'images/ships/' . $image . '" alt="' . $sname . ' banner" border="0" class="img-responsive center-block" >';
                                echo '</a>';
                            }
                        }
                    }
                    ?>
                </div>
            </div> <!-- End of Sim Image Row -->
            <div class="row"> <!-- Command Staff Row -->
            	<div class="col-xs-6 text-center"> <!-- CO Column -->
                    <strong>Commanding Officer:</strong><br />
                    <?php 
                    if ($co > 0)
                    {
                        $shipisopen = "no";
        
                        $qry2 = "SELECT name,rank,player FROM {$spre}characters WHERE id=" . $co;
                        $result2=$database->openConnectionWithReturn($qry2);
                        list ($coname,$corank,$copid) = mysql_fetch_array($result2);
                        $coname = stripslashes($coname);
        
                        $qry2 = "SELECT email FROM {$mpre}users WHERE id='$copid'";
                        $result2 = $database->openConnectionWithReturn($qry2);
                        list($coemail)=mysql_fetch_array($result2);
        
                        $qry2 = "SELECT rankdesc,image FROM {$spre}rank where rankid='$corank'";
                        $result2=$database->openConnectionWithReturn($qry2);
                        list($rname,$rurl)=mysql_fetch_array($result2);
                        
                        // Again, let's assume that there are escape characters in the DB for old entries, and clean them up for display:
                        $coname = stripslashes($coname);
                        $rname = stripslashes($rname);
        
                        if (!$textonly) {
                            $rnkimg = $relpath . 'images/ranks/' . $rurl;
                            if (file_exists($rnkimg)) $rnksize = getimagesize($rnkimg);
                            echo '<div style="max-width:'.$rnksize[0].'px; max-height:'.$rnksize[1].'px;" class="rank img-responsive">';
                            echo '<img src="' . $rnkimg .'" alt="' .$rname .'" border="0" class="img-responsive" />';
                            echo '</div>'; }
                            
                        echo '<a href="mailto:' . $coemail . '">' . $rname . ' ' . $coname . '</a>';
                    }
                    else
                    {
                        echo '&nbsp;&nbsp;&nbsp;Open -- apply today!';
                        $shipisopen = "yes";
                    }
                    ?>
                </div> <!-- End of CO Column -->
                <div class="col-xs-6 text-center"> <!-- XO Column -->
                    <strong>Executive Officer:</strong><br />
                    <?php
                    if($xo !=0)
                    {
                        $qry2 = "SELECT name,rank,player FROM {$spre}characters WHERE id='$xo'";
                        $result2=$database->openConnectionWithReturn($qry2);
                        list ($xname,$xrank,$xpid) = mysql_fetch_array($result2);
                        $xname = stripslashes($xname);
        
                        $qry2 = "SELECT email FROM {$mpre}users WHERE id='$xpid'";
                        $result2=$database->openConnectionWithReturn($qry2);
                        list($xomail)=mysql_fetch_array($result2);
        
                        $qry2 = "SELECT rankdesc,image FROM {$spre}rank where rankid='$xrank'";
                        $result2=$database->openConnectionWithReturn($qry2);
                        list ($rname,$rurl) = mysql_fetch_array($result2);
                        
                        // Again, let's assume that there are escape characters in the DB for old entries, and clean them up for display:
                        $xname = stripslashes($xname);
                        $rname = stripslashes($rname);
        
                        if (!$textonly) {
                            $rnkimg = $relpath . 'images/ranks/' . $rurl;
                            if (file_exists($rnkimg)) $rnksize = getimagesize($rnkimg);
                            echo '<div style="max-width:'.$rnksize[0].'px; max-height:'.$rnksize[1].'px;" class="rank img-responsive">';
                            echo '<img src="' . $rnkimg .'" alt="' .$rname .'" border="0" class="img-responsive" />';
                            echo '</div>'; }
                            
                        echo '<a href="mailto:' . $xomail . '">' . $rname . ' ' . $xname . '</a>';
                    }
                    else
                        echo '&nbsp;&nbsp;&nbsp;Open -- apply today!';
                    ?>
                </div><!-- End of XO Column -->
            </div><!-- End of Command Staff Row -->
        <div class="row text-center"> <!-- Sim Data Row -->
        	<div class="col-xs-6 col-sm-4 col-md-3">
        		<strong>Registry: </strong><?php echo $reg ?>
            </div>
        	<div class="col-xs-6 col-sm-4 col-md-3">
				<?php
                $qry2 = "SELECT id FROM {$sdb}classes WHERE name='$class'";
                $result2 = $database->openShipsWithReturn($qry2);
                list($classid) = mysql_fetch_array($result2);
                ?>    
                <strong>Class: </strong><?php redirect($relpath . 'shipdb.php?sclass=' . $classid . '&pop=y', $class . ' class', 550, 780) ?>
            </div>
        	<div class="col-xs-6 col-sm-4 col-md-3">
	        	<strong>Status: </strong><?php echo $status ?>
	        </div>
        	<div class="col-xs-6 col-sm-4 col-md-3">
				<?php
                $qry2 = "SELECT id FROM {$spre}characters WHERE ship='$sid'";
                $result2=$database->openConnectionWithReturn($qry2);
                $crewcount = mysql_num_rows($result2);
                ?>
                <strong>Total Crew:</strong> <?php echo $crewcount ?>
            </div>
        	<div class="col-xs-6 col-sm-4 col-md-3">
                <?php
                if ($shipisopen == "no")
                    echo '<strong>Sim type: </strong>' . $format;
                ?>
            </div>
        	<div class="col-xs-6 col-sm-4 col-md-3">
            	<strong>Task Force: </strong><?php echo $tf ?>
	        </div>
        	<div class="col-xs-6 col-sm-4 col-md-3">
            	<strong>Task Group:</strong> <?php echo $tg ?>
        	</div>
        </div><!-- End of Sim Data Row -->
        <div class="row sim-desc"><!-- Sim Description Row -->
            <div class="col-xs-12 text-justify">
                <?php echo $desc ?>
            </div>
        </div><!-- End of Sim Description Row -->

        <?php
        // Admin view
        if ($uflag['t'] == 1)
        {
            $qry2 = "SELECT c.id, t.tf, t.name
            		 FROM {$spre}characters c, {$spre}taskforces t
                     WHERE t.co=c.id AND c.player=" . uid . " AND tg='0'";
            $result2 = $database->openConnectionWithReturn($qry2);
            list ($cid, $tfcoid, $tfname) = mysql_fetch_array($result2);
        }

        if ($uflag['g'] == 1)
        {
            $qry2 = "SELECT c.id, t.tf, t.tg, t.name
            		 FROM {$spre}characters c, {$spre}taskforces t
                     WHERE t.co=c.id AND c.player=" . uid . " AND tg!='0'";
            $result2 = $database->openConnectionWithReturn($qry2);
            list ($cid, $tfcoid, $tgcoid, $tgname) = mysql_fetch_array($result2);
        }

		$tsk = "";
        if ($uflag['t'] == 2 || ($uflag['t'] == 1 && $tfcoid == $tf) )
        	$tsk = "tfco";
		elseif ($uflag['g'] == 2 || ($uflag['g'] == 1 && $tfcoid == $tf && $tgcoid == $tg) )
			$tsk = "tgco";

		if ($uflag['o'] > 0)
			$tsk = "fcops";

        if ($tsk)
        {
            ?>
            <div class="row sim-admin"><!-- Admin Buttons Row -->
            	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                    <div class="btn-group" role="group" aria-label="...">
                        <a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=<?php echo $tsk ?>&amp;action=common&amp;lib=sadmin&amp;sid=<?php echo $sid ?>">Admin Edit</a>
                        <a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=<?php echo $tsk ?>&amp;action=common&amp;lib=sdel&amp;sid=<?php echo $sid ?>">Delete Ship</a>
                        <a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=<?php echo $tsk ?>&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid ?>">View Manifest</a>
                        <a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=<?php echo $tsk ?>&amp;action=common&amp;lib=srepl&amp;sid=<?php echo $sid ?>">View Past Reports</a>
                    </div>
                </div>
            </div><!-- End of Admin Buttons Row -->
            <?php
        }
        elseif ($uflag['p'] > 0)
        {
            ?>
            <div class="row sim-admin">
            	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-center">
                	<a role="button" class="btn btn-default" href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid ?>">View Manifest</a>
                </div>
            </div>
            <?php
        }
		?>
        </div>
    </div><!-- End of Sim Listings div -->
    <?php
}

// Lists past monthly reports submitted by a ship
function ship_reports_list ($database, $mpre, $spre, $sid)
{
	$qry = "SELECT name FROM {$spre}ships WHERE id='$sid'";
    $result = $database->openConnectionWithReturn($qry);
    list($sname) = mysql_fetch_array($result);
    echo '<h1>Submitted Monthly Reports for <em>' . stripslashes($sname) . '</em></h1>';

	$qry = "SELECT id, co, date FROM {$spre}reports WHERE ship='$sid' ORDER BY id DESC";
    $result = $database->openConnectionWithReturn($qry);
	
	echo '<div class="list-group reports-list">';

    while (list($rid, $co, $date) = mysql_fetch_array($result))
    {
    	if ($date)
        {
        	$date = date("F j, Y", $date);
	    	echo '<a class="list-group-item" href="index.php?option=' . option . '&amp;task=' . task . '&amp;action=common&amp;lib=srepv&amp;rid=' . $rid . '&amp;sid=' . $sid . '">';
			echo '<h4 class="list-group-item-heading">' . $date . '</h4>';
			echo '<p class="list-group-item-text">Submitted by ' . stripslashes($co) . '</p>';
			echo '</a>';
        }
        else
	    	echo '<a href="index.php?option=' . option . '&amp;task=' . task . '&amp;action=common&amp;lib=srepv&amp;rid={$rid}&amp;sid={$sid}">(date unknown){$co}</a><br />';
    }
	
	echo '</div>';
}

// Views a past monthly report
function ship_reports_view ($database, $mpre, $spre, $rid, $sid)
{
	$qry = "SELECT date, ship, co, url, status, crew, crewlist,
    			newcrew, removedcrew, promotions, mission, missdesc, posts, awards, comments, potm
            FROM {$spre}reports WHERE id='$rid'";
    $result = $database->openConnectionWithReturn($qry);
	list($date, $ship, $co, $site, $status, $crewcount, $crewlisting, $newcrew,
    	 $removedcrew, $promotions, $mission, $missdesc, $posts,
         $awards, $comments, $potm)
         = mysql_fetch_array($result);

	$qry = "SELECT name FROM {$spre}ships WHERE id='$ship'";
    $result = $database->openConnectionWithReturn($qry);
	list ($sname)=mysql_fetch_array($result);

   	if ($date)
       	$date = date("F j, Y", $date);
    else
		$date = "(unknown)";

    if (!$co)
    	$co = "(unknown)";

    if (!$crewlisting)
    	$crewlisting = "(unavailable)";
    else
    	$crewlist = explode("\n", $crewlisting); // We're going to explode the crew listing into an array so we can style it better
		
	// We're going to assume that everything has escape characters in the database, so let's sanitise it for viewing:
	$sname = stripslashes($sname);
	$co = stripslashes($co);
	$newcrew = stripslashes($newcrew);
	$removedcrew = stripslashes($removedcrew);
	$promotions = stripslashes($promotions);
	$mission = stripslashes($mission);
	$missdesc = stripslashes($missdesc);
	$posts = stripslashes($posts);
	$awards = stripslashes($awards);
	$potm = stripslashes($potm);
	$comments = stripslashes($comments);

    ?>
	<h1>Monthly Report for the <?php echo $sname ?></h1>
    <dl class="dl-horizontal">
    	<dt>Date Submitted:</dt><dd><?php echo $date ?></dd>
		<dt>Sim Name:</dt><dd><?php echo $sname ?></dd>
		<dt>Commanding Officer:</dt><dd><?php echo $co ?></dd>
		<dt>Sim's Website:</dt><dd><?php echo $site ?></dd>
		<dt>Sim's Status:</dt><dd><?php echo $status ?></dd>
	</dl>

    <h3>Crew List:</h3>
    <ul class="list-group report-crewlist">
	<?php 
	$a=0; // Crew name
	$b=1; // Crew position
	$c=2; // Crew email
	$d=3; // Empty line - we'll use this to calculate how long to do the loop
	
	while ($d <= count($crewlist)) {
		?>
        <li class="list-group-item">
            <h4 class="list-group-item-heading"><?php echo stripslashes($crewlist[$a]) ?></h4>
			<p class="list-group-item-text"><?php echo stripslashes($crewlist[$b]) ?></p>
			<p class="list-group-item-text"><?php echo stripslashes($crewlist[$c]) ?></p>
		</li>
        <?php
		
		$a=$a+4;
		$b=$b+4;
		$c=$c+4;
		$d=$d+4;
	}
	?>
	</ul>

    <h3>Crew Information:</h3>
    <dl>
        <dt>Total Crew:</dt>
        	<dd><?php echo $crewcount ?></dd>
        <dt>New Crew Since Last Report:</dt>
        	<dd><?php echo $newcrew ?></dd>
        <dt>Crew Removed Since Last Report:</dt>
        	<dd><?php echo $removedcrew ?></dd>
        <dt>Crew Promotions/Demotions Since Last Report:</dt>
        	<dd><?php echo $promotions ?></dd>
    </dl>

	<h3>Sim Information:</h3>
    <dl>
		<dt>Current Mission Title:</dt>
        	<dd><?php echo $mission ?></dd>
        <dt>Mission Description:</dt>
        	<dd><?php echo $missdesc ?></dd>
        <dt>Approximate Number of Posts this Month:</dt>
        	<dd><?php echo $posts ?></dd>
        <dt>Sim/Website Awards and Awards Given Crew:</dt>
        	<dd><?php echo $awards ?></dd>
        <dt>Post of the Month Nomiation:</dt>
        	<dd><?php echo $potm ?></dd>
    </dl>

	<h3>Misc Information:</h3>
	<dl>
    	<dt>Sim Status, Updates and Additional Comments:</dt>
			<dd><?php echo $comments ?></dd>
    </dl>
    <?php
}

// Transfer a ship between TF/TGs
function ship_transfer ($database, $mpre, $spre, $sid, $tfid, $tgid)
{
	$qry = "SELECT id, name FROM {$spre}ships WHERE id='$sid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($test, $sname) = mysql_fetch_array($result);

	if (!$test)
		echo '<h2 class="text-danger">Bad ship ID!</h2>';
    else
    {
	    $qry = "SELECT tf, name FROM {$spre}taskforces WHERE tf='$tfid' AND tg='0'";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($tftest, $tfname) = mysql_fetch_array($result);

	    if (!$tftest)
	        echo '<h2 class="text-danger">Bad destination TF ID!</h2>';
        else
        {
	        $qry = "SELECT tg, name FROM {$spre}taskforces WHERE tf='$tfid' AND tg='$tgid'";
	        $result = $database->openConnectionWithReturn($qry);
	        list ($tgtest, $tgname) = mysql_fetch_array($result);

	        if (!$tgtest)
	            echo '<h2 class="text-danger">Bad destination TG ID!</h2>';
            else
            {
	            $qry = "UPDATE {$spre}ships SET tf='$tfid', tg='$tgid' WHERE id='$sid'";
	            $database->openConnectionNoReturn($qry);

	            echo '<h2>Transfer successful!</h2>';
	            echo '<p>' . $sname . ' transferred to ' . $tfname . ' - ' . $tgname . '</p>';
            }
        }
    }
}

// Admin editing screen
function ship_view_admin ($database, $mpre, $spre, $sdb, $sid, $uflag)
{
	GLOBAL $dbcon;
	$qry = "SELECT * FROM {$spre}ships WHERE id='$sid'";
	$result=$database->openConnectionWithReturn($qry);
	list($sid,$sname,$reg,$class,$site,$co,$xo,$tf,$tg,$status,$image,$sorder,$report,$desc,$format)=mysql_fetch_array($result);

	$qry2 = "SELECT id, name
    		 FROM {$spre}characters
             WHERE pos='Commanding Officer'
             	AND (ship='$sid' OR ship='0' OR ship='1')";
	$resultco=$database->openConnectionWithReturn($qry2);

	$qry3 = "SELECT tg, name FROM {$spre}taskforces WHERE tf='$tf'";
	$result3=$database->openConnectionWithReturn($qry3);
	
	extract($_GET);
    ?>
    <form method="post" action="index.php?option=<?php echo $option; ?>&amp;task=<?php echo $task; ?>&amp;action=common&amp;lib=sadmin2" class="form-horizontal">
        <input type="hidden" name="sid" value="<?php echo $sid; ?>">
        <div class="form-group">
            <label for="shipname" class="col-sm-2 control-label">Sim Name:</label>
            <div class="col-sm-7">
            	<input size="30" type="text" name="shipname" id="shipname" class="form-control" value="<?php echo $sname; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="registry" class="col-sm-2 control-label">Sim Registry:</label>
            <div class="col-sm-7">
            	<input size="30" type="text" name="registry" id="registry" class="form-control" value="<?php echo $reg; ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="class" class="col-sm-2 control-label">Sim Class:</label>
            <div class="col-sm-7">
                <select name="class" id="class" class="form-control">
                    <?php
                    $qry = "SELECT c.name
                            FROM {$sdb}classes c, {$sdb}category d, {$sdb}types t
                            WHERE c.category=d.id AND d.type=t.id AND t.support='n'
                            ORDER BY c.name";
                    $result = $database->openShipsWithReturn($qry);
                    while (list ($sname) = mysql_fetch_array($result))
                        if ($sname == $class)
                            echo '<option value="' . $sname . '" selected="selected">'. $sname . '</option>';
                        else
                            echo '<option value="' . $sname . '">' . $sname . '</option>';

                    if ($class == "")
                        echo '<option value="" selected="selected"></option>';
                    else
                        echo '<option value=""></option>';
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="format" class="col-sm-2 control-label">Sim Type:</label>
            <div class="col-sm-7">
                <select name="format" id="format" class="form-control">

                    <?php
                    if ($format == "Play by Nova")
                        echo '<option value="Play by Nova" selected="selected">Play by Nova</option>';
                    else
                        echo '<option value="Play by Nova">Play by Nova</option>';
                        
                    if ($format == "Play by SMS")
                        echo '<option value="Play by SMS" selected="selected">Play by SMS</option>';
                    else
                        echo '<option value="Play by SMS">Play by SMS</option>';
                        
                    if ($format == "Play by TRSM")
                        echo '<option value="Play by TRSM" selected="selected">Play by TRSM</option>';
                    else
                        echo '<option value="Play by TRSM">Play by TRSM</option>';
						
                    if ($format == "Play by Email")
                        echo '<option value="Play by Email" selected="selected">Play by Email</option>';
                    else
                        echo '<option value="Play by Email">Play by Email</option>';
                        
                    if ($format == "Play by Chat")
                        echo '<option value="Play by Chat" selected="selected">Play by Chat</option>';
                    else
                        echo '<option value="Play by Chat">Play by Chat</option>';

                    if ($format == "Play by Forum")
                        echo '<option value="Forum" selected="selected">Play by Forum</option>';
                    else
                        echo '<option value="Play by Forum">Play by Forum</option>';
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="grpid" class="col-sm-2 control-label">Task Group:</label>
            <div class="col-sm-7">
                <select name="grpid" id="grpid" class="form-control">
                    <?php
                    while( list($grp,$grpname)=mysql_fetch_array($result3) )
                    {
                        if ($grp == 0)
                            list($grp,$grpname)=mysql_fetch_array($result3);

                        if($grp == $tg)
                            echo '<option value="' . $grp . '" selected="selected">' . $grpname . '</option>';
                        else
                            echo '<option value="' . $grp . '">' . $grpname . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="website" class="col-sm-2 control-label">Website:</label>
            <div class="col-sm-7">
            	<input size="30" type="text" name="website" id="website" class="form-control" value="<?php echo $site ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="coid" class="col-sm-2 control-label">CO:</label>
            <div class="col-sm-7">
                <select name="coid" id="coid" class="form-control">
                    <option value="0"<?php if($co == 0) echo ' selected="selected"' ?>>No CO</option>
                    <?php
                    while( list($cid,$coname)=mysql_fetch_array($resultco) )
                        if($cid == $co)
                            echo '<option value="' . $cid . '" selected="selected">' . $coname . '</option>';
                        else
                            echo '<option value="' . $cid . '">' . $coname . '</option>';
                    ?>
                </select>
                <span id="helpBlock" class="help-block">Does not show COs already assigned to other sims</span>
            </div>
        </div>
        <div class="form-group">
            <label for="status" class="col-sm-2 control-label">Status:</label>
            <div class="col-sm-7">
                <?php
                $filename = "tf/status.txt";
                $contents = file($filename);
                $length = sizeof($contents);
                $count = 0;

                echo '<select name="status" id="status" class="form-control">';
                $counter = 0;
                do
                {
                    $contents[$counter] = trim($contents[$counter]);

                    if ($status == $contents[$counter])
                        echo '<option value="' . $contents[$counter] . '" selected="selected">' . $contents[$counter] . '</option>';
                    else
                        echo '<option value="' . $contents[$counter] . '">' . $contents[$counter] . '</option>';
                    $counter = $counter + 1;
                } while ($counter < $length);
                ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="image" class="col-sm-2 control-label">Image:</label>
            <div class="col-sm-7 input-group">
            	<span class="input-group-addon" id="image-addon">images/ships/</span>
                <input size="30" type="text" name="image" id="image" class="form-control" aria-described-by="image-addon" value="<?php echo $image ?>">
            </div>
        </div>
        <div class="form-group">
            <label for="notes" class="col-sm-2 control-label">Description:</label>
            <div class="col-sm-7">
            	<textarea name="notes" id="notes" class="form-control" rows="3"><?php echo $desc ?></textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
    </form>
    <?php
}

// View ship manifest, etc
function ship_view_info ($database, $mpre, $spre, $sid, $uflag, $multiship)
{
	$pbt=array('Play by Nova', 'Play by SMS', 'Play by TRSM', 'Play by Email', 'Play by Forum', 'Play by Chat');
	$npbt=count($pbt);

	$qry = "SELECT * FROM {$spre}ships WHERE id='$sid'";
	$result=$database->openConnectionWithReturn($qry);

    if (!mysql_num_rows($result))
    	echo '<h2 class="text-danger">Bad Ship ID!</h2>';
    else
    {
	    list($sid,$sname,$reg,$class,$site,$co,$xo,$tf,$tg,$status,$image,$sorder,$report,$desc,$format)=mysql_fetch_array($result);

	    $qry = "SELECT name, rank FROM {$spre}characters WHERE id='$co'";
	    $result=$database->openConnectionWithReturn($qry);
	    list($name,$corank)=mysql_fetch_array($result);

	    $qry = "SELECT name FROM {$spre}taskforces WHERE tf='$tf' AND (tg='0' OR tg='$tg') ORDER BY tg ASC";
	    $result=$database->openConnectionWithReturn($qry);
	    list($tfname)=mysql_fetch_array($result);
	    list($tgname)=mysql_fetch_array($result);
			
		// Let's assume that everything in the database has escape characters and sanitise it all
		$sname = stripslashes($sname);
		$reg = stripslashes($reg);
		$class = stripslashes($class);
		$desc = stripslashes($desc);
		$missiontitle = stripslashes($missiontitle);
		$name = stripslashes($name);

	    ?>
	    <div class="form-horizontal sim-details">
        	<div class="form-group form-group-static">
            	<label for="simname" class="col-sm-2 control-label">Sim Name:</label>
                <div class="col-sm-10">
            		<p class="form-control-static" id="simname"><?php echo $sname ?></p>
                </div>
            </div>
        	<div class="form-group form-group-static">
            	<label for="status" class="col-sm-2 control-label">Status:</label>
                <div class="col-sm-10">
            		<p class="form-control-static" id="status"><?php echo $status ?></p>
                </div>
            </div>
        	<div class="form-group form-group-static">
            	<label for="tf" class="col-sm-2 control-label">Task Force:</label>
                <div class="col-sm-10">
            		<p class="form-control-static" id="tf">TF <?php echo $tf ?> - <?php echo $tfname ?></p>
            		<p class="form-control-static" id="tg">TG <?php echo $tg ?> - <?php echo $tgname ?></p>
                </div>
            </div>

        <?php
        if (defined("admin"))
        {
        ?>
        	<div class="form-group form-group-static">
            	<label for="report" class="col-sm-2 control-label">Last Report:</label>
                <div class="col-sm-10">
            		<p class="form-control-static" id="report"><?php echo $report ?></p>
                </div>
            </div>
            <?php
        }
        ?>
            <?php
			if (!defined("admin")) {
			?>
        	<div class="form-group form-group-static">
            	<label for="format" class="col-sm-2 control-label">Format:</label>
                <div class="col-sm-10">
            		<p class="form-control-static" id="format"><?php echo $format ?></p>
                </div>
            </div>
            <?php
            } else {
			?>
                <form class="form-horizontal" action="index.php?option=<?php echo $_REQUEST['option'] ?>&amp;task=<?php echo $_REQUEST['task'] ?>&amp;action=common&amp;lib=spbt" method="post">
                <input type="hidden" name="sid" value="<?php echo $sid; ?>">
                <div class="form-group">
                	<label for="format" class="col-sm-2 control-label">Format:</label>
                    <div class="col-sm-9 col-md-7 col-lg-5">
                        <select class="form-control" name="format" id="format">
                        <?php
                            for ($tlv=0; $tlv<$npbt; $tlv++) {
                                echo '<option value="'.$pbt[$tlv].'"';
                                if ($pbt[$tlv]==$format) echo ' selected="selected"';
                                echo '>'.$pbt[$tlv].'</option>';	
                            } # Close Loop
                        ?>
                        </select>
                    </div>
                    <div class="col-sm-1 col-md-2">
                    	<button type="submit" class="btn btn-default btn-sm">Edit</button>
                    </div>
                </div>
                </form>
            <?php 
            } # Close Else
			
            if (!defined("admin")) {
			?>
        	<div class="form-group form-group-static">
            	<label for="url" class="col-sm-2 control-label">Sim Website:</label>
                <div class="col-sm-10">
            		<p class="form-control-static" id="url"><?php echo $site; ?></p>
                </div>
            </div>
            <?php
            } else {
            ?>
                <form class="form-horizontal" action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&action=common&lib=swebsite" method="post">
                    <input type="hidden" name="sid" value="<?php echo $sid; ?>">
                    <div class="form-group">
                        <label for="url" class="col-sm-2 control-label">Sim Website:</label>
                        <div class="col-sm-9 col-md-7 col-lg-5">
                            <input type="text" class="form-control" name="url" id="url" size="50" value="<?php echo $site; ?>">
                        </div>
                        <?php
                        if ($multiship)
                            echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
                        ?>
                        <div class="col-sm-1 col-md-2">
                            <button type="submit" class="btn btn-default btn-sm">Edit</button>
                        </div>
                    </div>
                </form>
                <?php
            } # Close Else
            ?>

            <form class="form-horizontal" action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=snotes" method="post">
                <input type="hidden" name="sid" value="<?php echo $sid; ?>" />
                <div class="form-group">
                    <label for="notes" class="col-sm-2 control-label">Description:</label></label>
                    <div class="col-sm-9 col-md-7 col-lg-5">
                		<textarea class="form-control" name="notes" id="notes" rows="3" cols="50" aria-describedby="helpBlock"><?php echo $desc; ?></textarea>
                    <?php
                    if ($multiship)
                        echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
                    ?>
                    	<span id="helpBlock" class="help-block">Limited to 500 characters</span>
                        <span id="imageHelpBlock" class="help-block">If adding an image here, please make sure to add class="img-responsive" to ensure it resizes properly on smaller screens.</span>
                    </div>
                    <div class="col-sm-1 col-md-2">
                        <button type="submit" class="btn btn-default btn-sm">Edit</button>
                    </div>
                </div>
            </form>

            <form class="form-horizontal" action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=sxo" method="post">
                <input type="hidden" name="sid" value="<?php echo $sid; ?>" />
                <div class="form-group">
                    <label for="xoid" class="col-sm-2 control-label">Current XO:</label></label>
                    <div class="col-sm-9 col-md-7 col-lg-5">
                		<select class="form-control" name="xoid" id="xoid">
							<?php
                            $qry = "SELECT id, name FROM {$spre}characters WHERE ship='$sid'";
                            $result=$database->openConnectionWithReturn($qry);
    
                            if($xo == 0)
                                echo '<option value="0" selected="selected">No XO currently aboard</option>';
                            else
                                echo '<option value="0">No XO currently aboard</option>';
    
                            while( list($cid,$cname)=mysql_fetch_array($result) ) {
                                if($xo == $cid)
                                    echo '<option value="' . $cid . '" selected="selected">' . stripslashes($cname) . '</option>';
                                else
                                    echo '<option value="' . $cid . '">' . stripslashes($cname) . '</option>';
                            }
                            ?>
                    	</select>
                    </div>
                    <?php
                    if ($multiship)
                        echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
                    ?>
                    <div class="col-sm-1 col-md-2">
                        <button type="submit" class="btn btn-default btn-sm">Edit</button>
                    </div>
                </div>
            </form>
        </div>

	    <h3>Current Crew:</h3>
	    <table class="table manifest">
        	<thead>
                <tr>
                    <th>ID #</th>
                    <th>Rank</th>
                    <th>Name</th>
                    <th>Position</th>
                </tr>
                <tr>
                	<th colspan="2"></th>
                    <th>E-mail</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $qry = "SELECT c.id, c.name, c.race, c.gender, c.rank, c.pos, c.player, c.pending
                        FROM {$spre}characters AS c, {$spre}rank AS r WHERE ship='$sid' AND c.rank = r.rankid
                        ORDER BY c.pending DESC, r.level DESC, c.rank DESC";
                $result=$database->openConnectionWithReturn($qry);
    
                if( !mysql_num_rows($result) )
                {
                    ?>
                    <tr>
                        <td colspan="5">
                            <p class="text-center"><em>No crew currently assigned</em></p>
                        </td>
                    </tr>
                    <?php
                }
                else
                {
                    while( list($cid,$cname,$crace,$cgen,$rank,$pos,$pid,$pending)=mysql_fetch_array($result) )
                    {
						$qry2 = "SELECT rankid, rankdesc,image FROM {$spre}rank WHERE rankid=" . $rank;
						$result2=$database->openConnectionWithReturn($qry2);
						list($rid,$rname,$rimg)=mysql_fetch_array($result2);

						$qry2 = "SELECT email FROM {$mpre}users WHERE id = '$pid'";
						$result2=$database->openConnectionWithReturn($qry2);
						list($email)=mysql_fetch_array($result2);
						
						// Let's sanitise everything again...
						$cname = stripslashes($cname);
						$crace = stripslashes($crace);
						$cgen = stripslashes($cgen);
						$pos = stripslashes($pos);
						$rname = stripslashes($rname);
						
						?>
                        <tr>
                            <td><?php echo $cid ?></td>
                            <?php if ($pending == "1"){echo '<td>';}else{echo '<td rowspan="2">';} 
                            
                            $rnkimg = $relpath . 'images/ranks/' . $rimg;
                            if (file_exists($rnkimg)) $rnksize = getimagesize($rnkimg);
                            echo '<div style="max-width:'.$rnksize[0].'px; max-height:'.$rnksize[1].'px;" class="rank img-responsive">';
                            echo '<img src="' . $rnkimg .'" alt="' .$rname .'" border="0" class="img-responsive" />';
                            echo '</div>'; ?>
                            </td>
                            <td><?php echo $rname . " " . $cname ?><br /><?php echo $crace . " " . $cgen; ?></td>
                            <td><?php echo $pos ?></td>
                        </tr>
                        <tr>
                                <?php
                                if ($pending == "1")
                                { 
                                ?>
                                    <td colspan="2">
                                    <p class="text-center text-uppercase text-warning"><strong>Pending</strong></p>
                                    <a role="button" class="btn btn-default btn-sm" href="index.php?option="<?php echo option ?>"&amp;task="<?php echo task ?>"&amp;action=common&amp;lib=capp&amp;cid=<?php echo $cid ?>&amp;sid=<?php echo $sid ?>">View App</a>
                                <?php 
                                }
                                else
                                    echo '<td>&nbsp;';
                                ?>
                            </td>
                            <td><?php echo $email ?></td>
                            <?php
                            if (defined("IFS"))
                            {
                               ?>
                                <td>
                                    <div class="btn-group text-center" role="group" aria-label="...">
                                    <a role="button" class="btn btn-default btn-sm" href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid ?>&amp;sid=<?php echo $sid ?><?php if ($multiship)?>&amp;multiship=<?php echo $multiship ?>">
                                        Edit</a>
                                    <a role="button" class="btn btn-default btn-sm" href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=cdel&amp;cid=<?php echo $cid ?>&amp;sid=<?php echo $sid ?><?php if ($multiship)?>&amp;multiship=<?php echo $multiship ?>">
                                        Delete</a>
                                    <a role="button" class="btn btn-default btn-sm" href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=rview&amp;cid=<?php echo $cid ?>&amp;sid=<?php echo $sid ?><?php if ($multiship)?>&amp;multiship=<?php echo $multiship ?>">
                                        Service Record</a>
                                    </div>
                                </td>
                                <?php
                            }
                            else
                                echo '<td>&nbsp;</td>';
                            ?>
                        </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
            </table>

            <form action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=cadd" method="post">
                <input type="hidden" name="sid" value="<?php echo $sid; ?>">
                <?php if ($multiship)
                    echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
                ?>
                <button type="submit" class="btn btn-default">Add Crew</button>
            </form>


    <form action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=sclear" method="post">
    	<h4><label for="reason">Transfer all crew to unassigned:</label></h4>
    	<span class="help-block">(only TFCOs/FCOps/Admin can do this)</span>
        <textarea class="form-control" name="reason" id="reason" rows="5" cols="60" wrap="physical">Enter your reason here</textarea>
        <input type="hidden" name="sid" value="<?php echo $sid; ?>">
        <button type="submit" class="btn btn-default">Clear Crew</button>
    </form>

    <?php
    }
}

?>
