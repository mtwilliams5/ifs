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
  * Patch 1.17:   June 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * This program contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: Functions for viewing & manipulating crew entries
  *
 ***/

// View Academy history
function crew_academy ($database, $mpre, $spre, $pid)
{
    $qry = "SELECT name, email FROM {$mpre}users WHERE id='$pid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($pname, $pemail) = mysql_fetch_array($result);
	
	$pname = stripslashes($pname);

	$qry = "SELECT st.id, r.rankdesc, c.name, s.name, co.name,
    			st.status, st.sdate, st.edate
            FROM {$spre}acad_courses co, {$spre}characters c, {$spre}ships s,
            	{$spre}acad_students st, {$spre}rank r
            WHERE st.course=co.course AND st.pid='$pid' AND st.cid=c.id
            	AND c.ship=s.id AND c.rank=r.rankid AND co.section='0'
            ORDER BY st.sdate DESC";
    $result = $database->openConnectionWithReturn($qry);

    echo '<h2>Academy History for ' . $pname . ' - ' . $pemail . '</h2>';

    while (list($sid, $rank, $cname, $ship, $course, $status,
    		$start, $end) = mysql_fetch_array($result) )
    {
		// Let's sanitise everything
		$cname = stripslashes($cname);
		$ship = stripslashes($ship);
		$course = stripslashes($course);
		
		echo '<h4>' . $course . '</h4>';
		echo '<ul class="list-unstyled">';
        echo '<li><strong>Character:</strong> '. $rank . ' ' . $cname . '</li>';
        echo '<li><strong>Sim:</strong> ' . $ship . '</li>';

        $qry2 = "SELECT c.name, u.email
                 FROM {$spre}characters c, {$mpre}users u,
                    {$spre}acad_students s, {$spre}acad_instructors i
                 WHERE s.id='$sid' AND s.inst=i.id AND i.cid=c.id AND c.player=u.id";
        $result2 = $database->openConnectionWithReturn($qry2);
        if (list($instname, $instemail) = mysql_fetch_array($result2))
            echo '<li><strong>Instructor:</strong> ' . stripslashes($instname) . '  - ' . $instemail . '</li>';

        echo '<li><strong>Registered on:</strong> ' . date("F j, Y, g:i a", $start) . '</li>';

        if ($status == "c")
        	echo '<li><strong>Status:</strong> Completed (pass) - ' . date("F j, Y", $end) . '</li>';
        else if ($status == "w")
        	echo '<li><strong>Status:</strong> Waiting to begin</li>';
        else if ($status == "f")
        	echo '<li><strong>Status:</strong> Failed - ' . date("F j, Y", $end) . '</li>';
        else if ($status == "d")
        	echo '<li><strong>Status:</strong> Dropped Out - ' . date("F j, Y", $end) . '</li>';
        else if ($status == "p")
        	echo '<li><strong>Status:</strong> In Progress</li>';
		echo '<br />';
		echo '<table class="table table-bordered">';
		echo '<tbody>';
    	$qry2 = "SELECT date, section, secname, grade, comments, name
        		 FROM {$spre}acad_marks WHERE sid='$sid' AND section !='0'
                 ORDER BY section";
        $result2 = $database->openConnectionWithReturn($qry2);

        while (list ($sdate, $secid, $secname, $grade, $comments, $name)
        		= mysql_fetch_array($result2) )
        {
		?>
        	<tr>
            	<td>
					<?php echo $secname ?><br />
            		<?php echo date("F j, Y", $sdate) ?><br />
            		<?php echo $name ?>
                </td>
            	<td>
					<?php echo $grade ?> %<br />
                    <?php echo $comments ?>
                </td>
            </tr>
        <?php
        }
		echo '</tbody>';
        echo '</table>';
    }
}

// Auto-OPM
// *** STILL IN TESTING ***
function crew_assign ($database, $mpre, $spre, $class1, $class2, $position1, $position2)
{
	$contents = file($relpath . "tf/positions.txt");
	$length = sizeof($contents);
	do
    {
		$counter = $counter + 1;
		$contents[$counter] = trim($contents[$counter]);
        $poslist .= $contents[$counter] . "\n";
	} while ($counter < ($length - 1));

	// Selected position is on master list
	if (strstr($poslist, $position1))
    {
		$qry = "SELECT s.id, s.name, COUNT(*) AS crew
				FROM {$spre}ships s, {$spre}characters c, ${spre}positions p
				WHERE s.class = '$class1' AND c.ship = s.id
                AND p.pos='$position1' AND p.ship=s.id AND p.action<>'rem'
				GROUP BY s.id ORDER BY crew";
       	$result = $database->openConnectionWithReturn($qry);
		if (mysql_num_rows($result))
        {
			$listing = "";
            while(list($sid, $sname, $crewcount) = mysql_fetch_array($result))
            {
            	$qry2 = "SELECT id, name FROM {$spre}characters WHERE ship='$sid' AND pos='$position1'";
                $result2 = $database->openConnectionWithReturn($qry2);
                if (!mysql_num_rows($result2))
	            	$listing .= "$sid - $sname<br />";
           	}
            if ($listing != "")
            {
            	echo "Best Results:<br />";
                echo $listing;
            }
            else
            	echo "No matches - please assign manually.";
                // go to 2nd choice pos?
        }
        else
			echo "No Matches - please assign manually.";
    }
    else
    {
		$qry = "SELECT s.id, s.name, COUNT(*) AS crew
				FROM {$spre}ships s, {$spre}characters c, ${spre}positions p
				WHERE s.class = '$class1' AND c.ship = s.id
                AND p.pos='$position1' AND p.ship=s.id AND p.action='add'
				GROUP BY s.id ORDER BY crew";
       	$result = $database->openConnectionWithReturn($qry);
		if (mysql_num_rows($result))
        {
        	$listing = "";
            while(list($sid, $sname, $crewcount) = mysql_fetch_array($result))
            {
            	$qry2 = "SELECT id, name FROM {$spre}characters
                		 WHERE ship='$sid' AND pos='$position1'";
                $result2 = $database->openConnectionWithReturn($qry2);
                if (!mysql_num_rows($result2))
	            	$listing .= "$sid - $sname<br />";
			}
            if ($listing != "")
            {
            	echo "Best Results:<br />";
                echo $listing;
            }
            else
            	echo "No matches - please assign manually.<br />";
		}
        else
	       	echo "No matches - please assign manually.<br />";
    }
}

// Delete all crew (used to clear out the Transfer queue)
function crew_delete_all ($database, $mpre, $spre, $deletechars, $delreason)
{
	for ($i=0; $i<sizeof($deletechars); $i++)
    {
		$id = $deletechars[$i];

		$deltime = date("Y-m-d H:i:s") . " from Pending Character Pool";
		$qry = "UPDATE {$spre}characters
        		SET ship='" . DELETED_SHIP . "',other='$deltime' WHERE id='{$id}'";
		$database->openConnectionNoReturn($qry);

		$qry = "SELECT username FROM {$mpre}users WHERE id='" . uid . "'";
		$result = $database->openConnectionWithReturn($qry);
		list ($uname) = mysql_fetch_array($result);

		// Papertrails are good.  Log this!
	    $qry = "INSERT INTO {$spre}logs
        		(date, user, action, comments)
                VALUES (now(), '" . uid . " $uname', 'Character Deleted',
                	'$id from transfer character pool')";
		$database->openConnectionNoReturn($qry);
    }

	redirect("");
}

// Confirm crew delete
function crew_delete_confirm ($database, $mpre, $spre, $id, $shipid, $uflag, $multiship)
{
	echo '<h2>Confirm Crew Delete</h2>';
    $qry = "SELECT name FROM {$spre}characters WHERE id='$id' AND ship='$shipid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($cname) = mysql_fetch_array($result);

    if (!$cname)
    	echo '<h4 class="text-danger">Database error - cannot find character ID!</h4>';
	else
    {
    	?>
		<p class="lead">Do you really want to delete <?php echo $cname ?>?</p>
        <form action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=cdel2" method="post">
		<input type="hidden" name="cid" value="<?php echo $id ?>">
        <input type="hidden" name="sid" value="<?php echo $shipid ?>">
		<?php
        if ($multiship)
            echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
        ?>
		<div class="form-group">
        	<label for="reason">Reason for deletion:</label>
        	<textarea class="form-control" name="reason" id="reason" rows="5" cols="60" wrap="physical"></textarea>
		</div>
        <div class="row">
        	<div class="col-sm-1">
        		<input class="btn btn-danger" type="submit" value="DELETE!">
            </div>
        </form>
        	<form action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>" method="post">
				<?php
                if ($multiship)
                    echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
                ?>
                <div class="col-sm-1">
                	<input class="btn btn-default" type="submit" value="Cancel">
                </div>
            </form>
        </div>
		<?php
    }
}

// Do crew delete
function crew_delete_save ($database, $mpre, $spre, $id, $shipid, $reason, $uflag, $multiship)
{
	$deltime = date("Y-m-d H:i:s") . " from ship $shipid";
	$qry = "UPDATE {$spre}characters SET ship='3',other='$deltime' WHERE id=" . $id;
	$result=$database->openConnectionWithReturn($qry);

    // take them off the academy wait lists, if needed
    $qry = "SELECT s.id FROM {$spre}acad_students s, {$spre}characters c
            WHERE c.id='$id' AND c.player=s.pid AND s.status='w'";
    $result = $database->openConnectionWithReturn($qry);
    while (list($stuid) = mysql_fetch_array($result))
    {
    	$qry = "DELETE FROM {$spre}acad_students WHERE id='$stuid'";
        $database->openConnectionNoReturn($qry);
    }

    $qry = "SELECT s.id, u.email, c.name
    		FROM {$spre}acad_students s, {$spre}characters c, {$mpre}users u,
            	{$spre}acad_instructors i, {$spre}characters c2
            WHERE c.id='$id' AND c.player=s.pid AND s.inst=c2.id
            	AND c2.player=u.id AND s.status='p'";
    $result = $database->openConnectionWithReturn($qry);
    $recip = "";
    while (list($stuid, $instemail, $charname) = mysql_fetch_array($result))
    	$recip .= ", $instemail";

    if ($recip)
    {
    	$recip = substr($recip, 2);
	require_once "includes/mail/academy_shipremoved.mail.php";
    }

	// if it's a co or xo, remove them from the ship listing too
	$qry = "SELECT co, xo FROM {$spre}ships WHERE id='$shipid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($coid, $xoid) = mysql_fetch_array($result);

	if ($coid == $id)
    {
		$qry = "UPDATE {$spre}ships SET co='0' WHERE id='$shipid'";
		$database->openConnectionNoReturn($qry);

        // Remove CO userlevel
   		$qry = "SELECT u.id, u.flags
        		FROM {$mpre}users u, {$spre}characters c
                WHERE c.id='$coid' AND c.player=u.id";
  	    $result = $database->openConnectionWithReturn($qry);
        list ($userid, $userflags) = mysql_fetch_array($result);

        $userflags = str_replace("c", "", $userflags);
   	    $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$userid'";
        $database->openConnectionNoReturn($qry);

        // Add a 90-day ban, too
        $date = time();
        $expire = time() + 60*60*24*90;
        $auth = get_usertype($database, $mpre, $spre, $cid, $uflag);
        $qry = "SELECT u.email FROM {$mpre}users u, {$spre}characters c
        		WHERE u.id=c.player AND c.id='$coid'";
        $result = $database->openConnectionWithReturn($qry);
        list($email) = mysql_fetch_array($result);

        $qry = "INSERT INTO {$spre}banlist
        		SET date='$date', auth='$auth',
                	reason='90-day penalty on resignation/removal from command',
                    email='$email', level='command',
                    expire='$expire', active='1'";
		$database->openConnectionNoReturn($qry);

	}
    elseif ($xoid == $id)
    {
		$qry = "UPDATE {$spre}ships SET xo='0' WHERE id='$shipid'";
		$database->openConnectionNoReturn($qry);
	}

    /*
    // Remove from PBB listings
	$qry = "SELECT username FROM pbb_users WHERE user_char='$id'";
	$result = $database->openPBBWithReturn($qry);
	list ($tvar) = mysql_fetch_array($result);

	if ($tvar)
    {
  		$qry = "UPDATE pbb_users SET user_active='0' WHERE user_char='$id'";
		$result = $database->openPBBWithReturn($qry);
	}
    */

	$qry = "SELECT username FROM {$mpre}users WHERE id='" . uid . "'";
	$result = $database->openConnectionWithReturn($qry);
	list ($uname) = mysql_fetch_array($result);

	// Papertrails are good.  Log this!
    $qry = "INSERT INTO {$spre}logs
    		(date, user, action, comments)
            VALUES (now(), '" . uid . " $uname', 'Character Deleted', '$id from ship $shipid')";
	$database->openConnectionNoReturn($qry);

	// JAG/Personnel should be notified when someone is removed.  So let's do it.
	
    $qry = "SELECT name, tf, tg FROM {$spre}ships WHERE id='$shipid'";
    $result = $database->openConnectionWithReturn($qry);
    list ($sname, $tfid, $tgid) = mysql_fetch_array($result);

    $qry = "SELECT name, rank, player FROM {$spre}characters WHERE id=" . $id;
    $result = $database->openConnectionWithReturn($qry);
    list ($cname, $rankid, $pid) = mysql_fetch_array($result);

	$qry = "SELECT rankdesc FROM {$spre}rank WHERE rankid='$rankid'";
	$result = $database->openConnectionWithReturn($qry);
	list($rankname) = mysql_fetch_array($result);

	$qry = "SELECT email FROM {$mpre}users WHERE id='$pid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($pemail) = mysql_fetch_array($result);
	
	$qry = "SELECT jag FROM {$spre}taskforces WHERE tf='$tfid' AND tg='0'";
    $result = $database->openConnectionWithReturn($qry);
    list ($jag) = mysql_fetch_array($result);

    $coname = get_usertype($database, $mpre, $spre, $cid, $uflag);
	$qry = "SELECT email FROM {$mpre}users WHERE id='" . UID . "'";
	$result = $database->openConnectionWithReturn($qry);
	list ($coemail) = mysql_fetch_array($result);

	require_once "includes/mail/jag_playerremove.mail.php";
	
	require_once "includes/mail/pers_playerremove.mail.php";

	// We should also add this to the service record
	$coname = mysql_real_escape_string($coname);
	$time = time();
	$details .= mysql_real_escape_string($reason);
	$qry = "SELECT player FROM {$spre}characters WHERE id='$id'";
	$result = $database->openConnectionWithReturn($qry);
	list ($uid) = mysql_fetch_array($result);

	$qry = "INSERT INTO {$spre}record
    		SET pid='$uid', cid='$id', level='Out-of-Character', date='$time',
            	entry='Removal: $cname', details='$details', name='$coname'";
	$database->openConnectionNoReturn($qry);
	$qry = "INSERT INTO {$spre}record
    		SET pid='$uid', cid='$id', level='Player', date='$time',
            	entry='Character Deletion: $cname', details='CID: $id',
                name='$fleetname IFS'";
	$database->openConnectionNoReturn($qry);

	redirect("");
}

// Edit Crew page
function crew_edit ($database, $spre, $mpre, $cid, $sid, $action, $uflag, $multiship)
{
	$qry = "SELECT id FROM {$spre}characters WHERE id='$cid'";
    $result = $database->openConnectionWithReturn($qry);
    if (!mysql_num_rows($result) && $action != "add")
    	echo "Bad Character ID!";

    else
    {
	    $qry = "SELECT c.player
        		FROM {$spre}ships s, {$spre}characters c
                WHERE s.id='$sid' AND s.co=c.id";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($couid) = mysql_fetch_array($result);

	    $qry = "SELECT * FROM {$spre}rank ORDER BY rankid";
	    $result=$database->openConnectionWithReturn($qry);
	    list($rid,$rname,$rimg,$rcol)=mysql_fetch_array($result);

	    if ($action != 'add')
        {
	        $qry2 = "SELECT id, name, race, gender, rank, ship, pos, player, pending
            		 FROM {$spre}characters WHERE id=" . $cid;
	        $result2=$database->openConnectionWithReturn($qry2);
	        list($cid,$cname,$crace,$cgender,$crank,$sid,$pos,$player,$pending)
            	=mysql_fetch_array($result2);

	        $qry3 = "SELECT email FROM {$mpre}users WHERE id='$player'";
	        $result3=$database->openConnectionWithReturn($qry3);
	        list($cemail)=mysql_fetch_array($result3);

			// Let's sanitise the character name, since it shouldn't need backslashes anymore
			$cname = stripslashes($cname);
		}

	    $qry3 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
	    $result3=$database->openConnectionWithReturn($qry3);
	    list($sname)=mysql_fetch_array($result3);
		$sname = stripslashes($sname);

	    ?>
	    <h2 class="text-center">Edit a Crewman</h2>
        <form class="form-horizontal" method="post" action="index.php?option=<?php echo option ?>&task=<?php echo task ?>&action=common&lib=cedit">
        <input type="hidden" name="cid" value="<?php echo $cid ?>">
        <p class="help-block text-center">All of the following information is required.</p>
        <div class="form-group">
        	<label for="cname" class="col-sm-2 control-label">Name:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
                <input type="text" class="form-control" name="cname" id="cname" size="30" value="<?php echo $cname ?>">
        		<span class="help-block">DO NOT include the rank with the character's name. It will be automatically generated when you pick the rank below</span>
            </div>
        </div>
        <div class="form-group">
        	<label for="crace" class="col-sm-2 control-label">Race:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
                <input type="text" class="form-control" name="race" id="crace" size="30" value="<?php echo $crace ?>">
            </div>
        </div>
        <div class="form-group">
        	<label for="cgender" class="col-sm-2 control-label">Gender:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
                <input type="text" class="form-control" name="gender" id="cgender" size="30" value="<?php echo $cgender ?>">
            </div>
        </div>
        <div class="form-group">
        	<label for="email" class="col-sm-2 control-label">E-mail Address:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
                <?php
            	if ($action == "add")
            	    echo '<input type="text" class="form-control" name="email" id="email" size="30">';
            	else
            	    echo '<p class="form-control-static">' . $cemail . '</p>';
            	?>
            </div>
        </div>
        <div class="form-group">
        	<label for="sname" class="col-sm-2 control-label">Ship:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
                <p class="form-control-static" id="sname"><?php echo $sname ?></p>
            </div>
        </div>
        <div class="form-group">
        	<label for="position" class="col-sm-2 control-label">Position:</label>
            <div class="col-sm-10 col-md-6 col-lg-4">
                    <select class="form-control" name="position" id="position">
					<?php
                    $qry2 = "SELECT t.tf
                            FROM {$spre}characters c, {$spre}taskforces t, {$spre}ships s
                            WHERE t.tg=0 AND t.co=c.id AND c.player='$uid' AND s.tf=t.tf
                                AND s.id='$sid'";
                    $result2 = $database->openConnectionWithReturn($qry2);
                    list ($usertf) = mysql_fetch_array($result2);
    
                    $qry2 = "SELECT t.tf
                            FROM {$spre}characters c, {$spre}taskforces t, {$spre}ships s
                            WHERE t.tg!=0 AND t.co=c.id AND c.player='$uid' AND s.tf=t.tf
                                AND s.id='$sid' AND s.tg=t.tg";
                    $result2 = $database->openConnectionWithReturn($qry2);
                    list ($usertg) = mysql_fetch_array($result2);
    
                    $qry2 = "SELECT co FROM {$spre}ships WHERE id='$sid'";
                    $result2 = $database->openConnectionWithReturn($qry2);
                    list ($coid) = mysql_fetch_array($result2);
                    if ($coid == 0 && $action=="add" && (($usertf || $uflag['t'] == 2 || $usertg || $uflag['g'] == 2)&&$sid!=4) )
                        $pos = "Commanding Officer";
                        
					$filename = $relpath . "tf/positions.txt";
					$contents = file($filename);
					$length = sizeof($contents);
					$counter = 0;
					$matched = 0;

					do
					{
						$pos2 = trim($contents[$counter]);
						$pos2 = mysql_real_escape_string($pos2);

						$qry2 = "SELECT pos FROM {$spre}positions
								 WHERE ship='$sid' AND action='rem' AND pos='$pos2'";
						$result2 = $database->openConnectionWithReturn($qry2);

						if (!mysql_num_rows($result2))
						{
							$pos2 = stripslashes($pos2);
							if ($pos == $pos2)
							{ ?>
								<option value="<?php echo $pos2 ?>" selected="selected"><?php echo $pos2 ?></option>
                            <?php
								$matched = 1;
							}
							else
							{ ?>
								<option value="<?php echo $pos2 ?>"><?php echo $pos2 ?></option>
                            <?php
							}
						}
						$counter = $counter + 1;

					} while ($counter < ($length));

					$qry2 = "SELECT pos FROM {$spre}positions
							 WHERE ship='$sid' AND action='add'";
					$result2 = $database->openConnectionWithReturn($qry2);

					while (list ($pos2) = mysql_fetch_array($result2))
					{
						if ($pos == $pos2)
						{ ?>
							<option value="<?php echo $pos2 ?>" selected="selected"><?php echo $pos2 ?></option>
						<?php
							$matched = 1;
						}
						else
						{ ?>
							<option value="<?php echo $pos2 ?>"><?php echo $pos2 ?></option>
						<?php
						}
					}
					if ($matched == 0)
						echo '<option value="Other" selected="selected">Other</option>';
					else
						echo '<option value="Other">Other</option>';
					?>
					</select>
                <div class="help-block form-inline">
                    <div class="form-group">
                        <label for="otherpos">If other:</label> <input type="text" name="otherpos" size="30" value="<?php if ($matched != 1) echo $pos ?>">
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
        	<div class="col-xs-12 col-sm-6">
                <div class="form-group">
                    <label for="rank" class="col-sm-4 control-label">Rank Pips:</label>
                    <div class="col-sm-8">
                        <select class="form-control" name="rank" id="rank">
                        <?php
							$rpath = $relpath . 'images/ranks/';
						
                        while($rid)
                        {
							echo '<option value="' . $rid . '"';
							if ($rid == $crank)
								echo ' selected="selected"';
							echo ' img="' . $rpath . $rimg . '"';
							if ($rcol != '')
								echo '>' . $rname . ' (' . $rcol . ')</option>';
							else
								echo '>' . $rname . '</option>';
        
                            list($rid,$rname,$rimg,$rcol)=mysql_fetch_array($result);
                        }
                        ?>
                        </select><br />
                        <div class="rank"><img id="rnkpreview" src="<?php echo $rpath . $rimg?>" alt="<?php echo $rname ?>" class="img-responsive"></div>
                    </div>
                </div>
			</div>
		</div>
        <div class="form-group">
			<p class="col-sm-10 col-sm-offset-2 help-block">
				Standard colours: Command and helm officers wear red; engineering, security and operations wear yellow; medical and sciences wear teal.
            </p>
		</div>
        <div class="checkbox">
            <label for="pending" class="col-sm-10 col-sm-offset-2">
				<?php
                echo '<input type="checkbox" name="pending"';
                if ($pending == "1")
                    echo ' checked="checked"';
                echo '>';
                ?>
                Pending?
            	<span class="help-block">If this box is checked, it will show up on the DPM Listings, and the DPM Hounds will chase you and tell you to process the app!</span>
            </label>
        </div>
		<?php
        if ($action == "add")
            echo '<input type="hidden" name="add" value="yes">';
        if ($multiship)
            echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
        ?>
        <input type="hidden" name="sid" value="<?php echo $sid ?>">
        <div class="form-group">
        	<div class="col-sm-10 col-sm-offset-2">
                <input class="btn btn-success btn-sm" type="submit" value="Edit">
                <input class="btn btn-danger btn-sm" type="reset" value="Clear Form">
            </div>
        </div>
        </form>
		<script>
			$(document).ready(function(){
				$('#rank').change(function(){
					$('#rnkpreview').attr({
						"src" : $('#rank option:selected').attr('img'),
						"alt" : $('#rank option:selected').text()
					});
				});
			});
		</script>
	    <?php
    }
}

// Reason for promotion or demotion
function crew_edit_reason ($database, $spre, $mpre, $add, $position, $otherpos, $name, $email, $race, $gender, $rank, $sid, $crewid, $pending, $uflag, $multiship)
{
	if ($add == "yes")
        crew_edit_save($database, $spre, $mpre, $add, $position, $otherpos, $name, $email, $race, $gender, $rank, '', $sid, $crewid, $pending, $uflag, $multiship);
	else
    {
		$qry = "SELECT rank FROM {$spre}characters WHERE id='$crewid'";
		$result = $database->openConnectionWithReturn($qry);
		list ($oldrankid) = mysql_fetch_array($result);

		$qry = "SELECT level FROM {$spre}rank WHERE rankid = '$oldrankid'";
    	$result = $database->openConnectionWithReturn($qry);
		list ($oldranklevel) = mysql_fetch_array($result);

	    $qry = "SELECT level FROM {$spre}rank WHERE rankid = '$rank'";
    	$result = $database->openConnectionWithReturn($qry);
	    list ($newranklevel) = mysql_fetch_array($result);

	  	if ($oldranklevel == $newranklevel)
    	    crew_edit_save($database, $spre, $mpre, $add, $position, $otherpos, $name, $email, $race, $gender, $rank, '', $sid, $crewid, $pending, $uflag);
		else
        {
	 	   ?>

	        <form action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=cedit2" method="post">
                <input type="hidden" name="cid" value="<?php echo $crewid ?>">
                <input type="hidden" name="sid" value="<?php echo $sid ?>">
                <?php
                if ($multiship)
                    echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
                ?>
                <div class="form-group">
                    <label for="reason">
                        <?php
                        if ($oldranklevel < $newranklevel)
                            echo 'Reason for promotion:';
                        else
                            echo 'Reason for demotion:';
                        ?>
                    </label>
                    <textarea class="form-control" name="reason" id="reason" rows="5" cols="60" wrap="physical"></textarea>
                </div>
                <input type="hidden" name="position" value="<?php echo $position ?>">
                <input type="hidden" name="otherpos" value="<?php echo $otherpos ?>">
                <input type="hidden" name="cname" value="<?php echo $name ?>">
                <input type="hidden" name="email" value="<?php echo $email ?>">
                <input type="hidden" name="race" value="<?php echo $race ?>">
                <input type="hidden" name="gender" value="<?php echo $gender ?>">
                <input type="hidden" name="rank" value="<?php echo $rank ?>">
                <input type="hidden" name="pending" value="<?php echo $pending ?>">
                <div class="row">
                	<div class="col-sm-1">
                		<input class="btn btn-default" type="submit" value="Submit">
                    </div>
            </form>
    	    <form action="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>" method="post">
				<?php
                if ($multiship)
                    echo '<input type="hidden" name="multiship" value="' . $multiship . '">';
                ?>
                	<div class="col-sm-1">
                		<input class="btn btn-default" type="submit" value="Cancel">
                    </div>
                </div>
            </form>
            <?php
		}
	}
}

// Save changes
function crew_edit_save ($database, $spre, $mpre, $add, $position, $otherpos, $name, $email, $race, $gender, $rank, $reason, $sid, $crewid, $pending, $uflag, $multiship)
{
	if ($position == "Other")
    {
		$position = $otherpos;
        $postype = "crew";
	}
    elseif ($position == "Commanding Officer")
    	$postype = "command";
    else
    	$postype = "crew";

    if ($pending == "on")
    	$pending = "1";
    elseif ($pending == "")
    	$pending = "0";
			
	if ($add == "yes")
    {
		if ($reason = check_ban($database, $mpre, $spre, $email, 'na', $postype))
        {
			echo '<h3 class="text-danger">This player has been banned from ' . $fleetname . '!</h3>';
		    echo '<p>' . $reason . '</p>';
		}
        elseif( $name != "" && $email != "" && $position != "")
        {
			// find out if this person already has a UID
			$qry = "SELECT id FROM {$mpre}users WHERE email='$email'";
			$result = $database->openConnectionWithReturn($qry);
			list ($uid) = mysql_fetch_array($result);

			// if they don't have a UID, create one
			if (!$uid)
            {
            	list ($username, $pass, $uid) = make_uid ($database, $mpre, $name, $name, $email);

				$name = stripslashes($name);

				require_once "includes/mail/crew_newplayer.mail.php";
				
				//Let's sanitise some values for the DB query
				$username = mysql_real_escape_string($username);
				$couname = mysql_real_escape_string($couname);

				$qry2 = "INSERT INTO {$spre}logs
                		 (date, user, action, comments)
                         VALUES (now(), '" . uid . " $username', 'User created',
                         	'by $couid $couname')";
				$database->openConnectionNoReturn($qry2);

			    $details = "UID created: " . $uid . "<br />\n";
			}
            else
				$details = "UID found: " . $uid . "<br />\n";

			$qry2 = "SELECT username FROM {$mpre}users WHERE id='" . uid . "'";
			$result2 = $database->openConnectionWithReturn($qry2);
			list ($couname) = mysql_fetch_array($result2);
			
			// Let's sanitise some of the values
			$name = mysql_real_escape_string($name);
			$race = mysql_real_escape_string($race);
			$gender = mysql_real_escape_string($gender);
			$position = mysql_real_escape_string($position);

			$qry = "INSERT INTO {$spre}characters
            		(name,race,gender,rank,ship,pos,player,app,pending)
                    VALUES('$name','$race','$gender',$rank,$sid,'$position','$uid','0','$pending')";
			$result=$database->openConnectionWithReturn($qry);

            $qry = "SELECT id FROM {$spre}characters WHERE id=LAST_INSERT_ID()";
            $result = $database->openConnectionWithReturn($qry);
            list ($cid) = mysql_fetch_array($result);

			$qry = "SELECT name, rank, player FROM {$spre}characters WHERE id=" . $cid;
			$result = $database->openConnectionWithReturn($qry);
			list ($cname, $rankid, $pid) = mysql_fetch_array($result);

			$qry = "SELECT rankdesc FROM {$spre}rank WHERE rankid='$rankid'";
			$result = $database->openConnectionWithReturn($qry);
			list($rankname) = mysql_fetch_array($result);

            $qry = "SELECT name, tf, tg FROM {$spre}ships WHERE id='$sid'";
            $result = $database->openConnectionWithReturn($qry);
            list ($sname, $tfid, $tgid) = mysql_fetch_array($result);

            $coname = addslashes(get_usertype($database, $mpre, $spre, $cid, $uflag));
			$qry = "SELECT email FROM {$mpre}users WHERE id='" . UID . "'";
			$result = $database->openConnectionWithReturn($qry);
			list ($coemail) = mysql_fetch_array($result);
			
			// We should email Personnel to let them know someone has been added
			require_once "includes/mail/pers_playeradd.mail.php";			
			
			// We should also add this to the service record
			$details .= "Sim: " . $sname . "<br />\n";
			$details = mysql_real_escape_string($details);
            $time = time();
			$qry = "INSERT INTO {$spre}record
            		SET pid='$uid', cid='$cid', level='Out-of-Character', date='$time',
                    	entry='Character Created: $name', details='$details', name='$coname'";
			$database->openConnectionNoReturn($qry);
			$qry = "INSERT INTO {$spre}record
            		SET pid='$uid', cid='$cid', level='Player', date='$time',
                    	entry='Character Created: $name', details='CID: $cid',
                        name='$fleetname IFS'";
			$database->openConnectionNoReturn($qry);

            // If it's a CO, they deserve the userlevel too...
            if ($position == "Commanding Officer")
            {
            	$qry = "SELECT flags FROM {$mpre}users WHERE id='$uid'";
                $result = $database->openConnectionWithReturn($qry);
                list ($userflags) = mysql_fetch_array($result);

                if (!strstr($userflags, "c"))
                {
                	$userflags = "c" . $userflags;
                    $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$uid'";
                    $database->openConnectionNoReturn($qry);

	                $qry = "UPDATE {$spre}ships SET co='$cid' WHERE id='$sid'";
	                $database->openConnectionNoReturn($qry);
                }
            }

            /*
			// sync the PBB userdb if needed
			$qry = "SELECT name, format FROM {$spre}ships WHERE id='$sid'";
			$result=$database->openConnectionWithReturn($qry);
			list ($sname, $format) = mysql_fetch_array($result);

			if ($format == "Play by Bulletin Board") {

				$qry = "SELECT password FROM {$mpre}users WHERE id='$uid'";
				$result=$database->openConnectionWithReturn($qry);
				list ($password) = mysql_fetch_array($result);

				$qry = "INSERT INTO pbb_users (user_id, username, user_password, user_char, user_regdate) VALUES (NULL,'$name','$password',LAST_INSERT_ID(), time())";
				$result = $database->openPBBWithReturn($qry);

				// now set up groups
				$qry = "SELECT group_id FROM pbb_groups WHERE group_name='$sname'";
				$result = $database->openPBBWithReturn($qry);
				list ($gid) = mysql_fetch_array($result);

				$qry = "INSERT INTO pbb_user_group (group_id, user_id, user_pending) VALUES ('$gid', LAST_INSERT_ID(), '0')";
				$result = $database->openPBBWithReturn($qry);
			}
            */

            redirect("");
		}
        else
			echo '<h3 class="text-warning">You did not enter all required information. Hit your BACK button and try again.</h3>';
	}
    else
    {
		if ($name != "" && $position != ""){

			$qry = "UPDATE {$spre}characters SET name='$name' WHERE id=" . $crewid;
			$result=$database->openConnectionWithReturn($qry);

            $qry = "SELECT pending, ptime FROM {$spre}characters WHERE id='$crewid'";
            $result = $database->openConnectionWithReturn($qry);
            list ($oldpending, $oldptime) = mysql_fetch_array($result);
            if ($oldpending == '0' && $pending == '1')
				$ptime = time();
            elseif ($oldpending == '1' && $pending == '1')
            	$ptime = $oldptime;

            /*
			// sync the PBB userdb if needed
			$qry = "SELECT name, format FROM {$spre}ships WHERE id='$sid'";
			$result=$database->openConnectionWithReturn($qry);
			list ($sname, $format) = mysql_fetch_array($result);

			if ($format == "Play by Bulletin Board") {
				$qry = "UPDATE pbb_users SET username='$name' WHERE user_char=" . $crewid;
				$result = $database->openPBBWithReturn($qry);
			}
            */

			$qry = "UPDATE {$spre}characters
            		SET race='$race', gender='$gender', pending='$pending', ptime='$ptime'
                    WHERE id=" . $crewid;
			$result=$database->openConnectionWithReturn($qry);

			if ($reason)
            {
		        $coname = get_usertype($database, $mpre, $spre, $crewid, $uflag);

                $qry = "SELECT name, rank, player FROM {$spre}characters WHERE id=" . $crewid;
   	   		    $result = $database->openConnectionWithReturn($qry);
    	        list ($cname, $rankid, $pid) = mysql_fetch_array($result);

	            $qry = "SELECT rankdesc, level FROM {$spre}rank WHERE rankid='$rankid'";
   	   		    $result = $database->openConnectionWithReturn($qry);
    	        list ($oldrank, $oldranklevel) = mysql_fetch_array($result);

                $qry = "SELECT rankdesc, level FROM {$spre}rank WHERE rankid='$rank'";
   	   		    $result = $database->openConnectionWithReturn($qry);
    	        list ($newrank, $newranklevel) = mysql_fetch_array($result);
				
				$qry = "SELECT name, tf FROM {$spre}ships WHERE id='$sid'";
		        $result = $database->openConnectionWithReturn($qry);
		        list ($sname, $tfid) = mysql_fetch_array($result);

		        $qry = "SELECT email FROM {$mpre}users WHERE id='$pid'";
    	   		$result = $database->openConnectionWithReturn($qry);
	    	    list ($pemail) = mysql_fetch_array($result);

                if ($oldranklevel > $newranklevel)
                {
					$qry = "SELECT jag FROM {$spre}taskforces WHERE tf='$tfid' AND tg='0'";
				    $result = $database->openConnectionWithReturn($qry);
				    list ($jag) = mysql_fetch_array($result);

					require_once "includes/mail/jag_playerdemoted.mail.php";
					
					require_once "includes/mail/pers_playerdemoted.mail.php";

                    $entry = "Demotion: " . $cname;
                }
                else
					require_once "includes/mail/pers_playerpromoted.mail.php";
					
					$entry = "Promotion: " . $cname;

                // We should also add this to the service record
				$coname = mysql_real_escape_string($coname);
                $time = time();
                $details = "Old Rank: " . $oldrank . "<br />\n";
                $details .= "New Rank: " . $newrank . "<br />\n";
                $details .= $reason;
				$details = mysql_real_escape_string($details);
                $qry = "INSERT INTO {$spre}record
                		SET pid='$pid', cid='$crewid', level='Out-of-Character', date='$time',
                        	entry='$entry', details='$details', name='$coname'";
				$database->openConnectionNoReturn($qry);
            }

			if ($area != "co" || $position != "Commanding Officer")
            {
				$qry = "UPDATE {$spre}characters SET rank=" . $rank . " WHERE id=" . $crewid;
				$result=$database->openConnectionWithReturn($qry);
			}

	        if ($position == "other")
				$position = $otherpos;

            $qry = "UPDATE {$spre}characters SET pos='$position' WHERE id=" . $crewid;
			$result=$database->openConnectionWithReturn($qry);

            redirect("");
		}
        else
			echo '<h3 class="text-warning">You did not enter all required information. Hit your BACK button and try again.</h3>';
	}
}

// Search for all crew associated with an email address
function crew_list_email ($database, $mpre, $spre, $email, $uflag)
{
   	echo '<h3>Searching for email address ' . $email . '...</h3>';

	$qry = "SELECT id FROM {$mpre}users WHERE email='$email'";
	$result = $database->openConnectionWithReturn($qry);
	list ($uid) = mysql_fetch_array($result);

	if ($uid)
    {
		?>
        <ul class="list-group">
        <?php
		$results = false;
		
		# So we can sort better, let's find characters who aren't deleted first
		$qry = "SELECT id, name, ship FROM {$spre}characters WHERE player='$uid' AND ship <> " . DELETED_SHIP;
		$result = $database->openConnectionWithReturn($qry);

		if (mysql_num_rows($result))
			$results = true;
			
		while ( list($cid,$name,$sid)=mysql_fetch_array($result) )
        {
			$qry2 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($ship)=mysql_fetch_array($result2);
			?>
            <li class="list-group-item">
			<h4 class="list-group-item-heading">Character ID #<?php echo $cid ?> - 
                <a href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid ?>">
                    <?php echo $name ?>
                </a>
            </h4>
            <p class="list-group-item-text">On <a href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid ?>">
            	<?php echo $ship ?>
            </a></p>
            <a class="list-group-item-text" href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=rview&amp;cid=<?php echo $cid ?>">
				View Service Record
            </a>
            </li>
            <?php
		}
		# Now let's add the deleted characters
		$qry = "SELECT id, name, ship FROM {$spre}characters WHERE player='$uid' AND ship = " . DELETED_SHIP;
		$result = $database->openConnectionWithReturn($qry);

		if (mysql_num_rows($result))
			$results = true;
			
		while ( list($cid,$name,$sid)=mysql_fetch_array($result) )
        {
			$qry2 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($ship)=mysql_fetch_array($result2);
			?>
            <li class="list-group-item">
			<h4 class="list-group-item-heading">Character ID #<?php echo $cid ?> - 
                <a href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid ?>">
                    <?php echo $name ?>
                </a> <span class="help-inline text-uppercase">[Deleted]</span>
            </h4>
            <p class="list-group-item-text">On <a href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid ?>">
            	<?php echo $ship ?>
            </a></p>
            <a class="list-group-item-text" href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=rview&amp;cid=<?php echo $cid ?>">
				View Service Record
            </a>
            </li>
            <?php
		}
		?>
        </ul>
        <?php
		if (!$results)
			echo '<h3 class="text-warning">No characters found.</h3>';
	}
    else
		echo '<h4 class="text-warning">Email address not found!</h4>';
}

// List all characters associated with a player ID
function crew_list_id ($database, $mpre, $spre, $pid, $uflag)
{
   	echo '<h3>Searching for Player ID #' . $pid . '...</h3>';

	$qry = "SELECT id FROM {$mpre}users WHERE id='$pid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($uid) = mysql_fetch_array($result);

	if ($uid)
    {
		?>
        <ul class="list-group">
        <?php
		$results = false;
		
		# So we can sort better, let's find characters who aren't deleted first
		$qry = "SELECT id, name, ship FROM {$spre}characters WHERE player='$uid' AND ship <> " . DELETED_SHIP;
		$result = $database->openConnectionWithReturn($qry);

		if (mysql_num_rows($result))
			$results = true;

		while ( list($cid,$name,$sid)=mysql_fetch_array($result) )
        {
			$qry2 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($ship)=mysql_fetch_array($result2);
			?>
            <li class="list-group-item">
			<h4 class="list-group-item-heading">Character ID #<?php echo $cid ?> - 
                <a href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid ?>">
                    <?php echo $name ?>
                </a>
            </h4>
            <p class="list-group-item-text">On <a href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid ?>">
            	<?php echo $ship ?>
            </a></p>
            <a class="list-group-item-text" href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=rview&amp;cid=<?php echo $cid ?>">
				View Service Record
            </a>
            </li>
            <?php
		}
		# Now let's add the deleted characters
		$qry = "SELECT id, name, ship FROM {$spre}characters WHERE player='$uid' AND ship = " . DELETED_SHIP;
		$result = $database->openConnectionWithReturn($qry);

		if (mysql_num_rows($result))
			$results = true;
			
		while ( list($cid,$name,$sid)=mysql_fetch_array($result) )
        {
			$qry2 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($ship)=mysql_fetch_array($result2);
			?>
            <li class="list-group-item">
			<h4 class="list-group-item-heading">Character ID #<?php echo $cid ?> - 
                <a href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid ?>">
                    <?php echo $name ?>
                </a> <span class="help-inline text-uppercase">[Deleted]</span>
            </h4>
            <p class="list-group-item-text">On <a href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid ?>">
            	<?php echo $ship ?>
            </a></p>
            <a class="list-group-item-text" href="index.php?option=<?php echo option ?>&amp;task=<?php echo task ?>&amp;action=common&amp;lib=rview&amp;cid=<?php echo $cid ?>">
				View Service Record
            </a>
            </li>
            <?php
		}
		?>
        </ul>
        <?php
		if (!$results)
			echo '<h3 class="text-warning">No characters found.</h3>';
	}
    else
		echo '<h4 class="text-warning">Invalid ID!</h4>';
}

// Transfer a character to another simm
function crew_transfer ($database, $mpre, $spre, $cid, $sid)
{
	echo '<h2>Character Transfer</h2>';

	$qry = "SELECT id, name, player, ship FROM {$spre}characters WHERE id='$cid'";
	$result = $database->openConnectionWithReturn($qry);
	list ($test, $charname, $pid, $oldsid) = mysql_fetch_array($result);

	if (!$test)
		echo '<h3 class="text-warning">Bad character ID!</h3>';
	else
    {
	    $qry = "SELECT id, name FROM {$spre}ships WHERE id='$sid'";
	    $result = $database->openConnectionWithReturn($qry);
	    list ($stest, $sname) = mysql_fetch_array($result);

	    if (!$stest)
	        echo '<h3 class="text-warning">Bad destination ID!</h3>';
	    else
        {
	        $qry = "SELECT name FROM {$spre}ships WHERE id='$oldsid'";
	        $result = $database->openConnectionWithReturn($qry);
	        list ($oldname) = mysql_fetch_array($result);

	        $ptime = time();
	        $pdate = date("F j, Y, g:i a", time());

	        $qry = "UPDATE {$spre}characters
            		SET ship='$sid', ptime='$ptime', other='Transferred on $pdate'
                    WHERE id='$cid'";
	        $database->openConnectionNoReturn($qry);

            // If it's a CO or XO, set their position "open" on the old ship
	        $qry = "SELECT id FROM {$spre}ships WHERE co='$cid'";
	        $result = $database->openConnectionWithReturn($qry);
	        list($coid) = mysql_fetch_array($result);

	        if ($coid)
            {
	            $qry = "UPDATE {$spre}ships SET co='0' WHERE ship='$coid'";
	            $database->openConnectionNoReturn($qry);
	        }

	        $qry = "SELECT id FROM {$spre}ships WHERE xo='$cid'";
	        $result = $database->openConnectionWithReturn($qry);
	        list($xoid) = mysql_fetch_array($result);

	        if ($xoid)
            {
	            $qry = "UPDATE {$spre}ships SET xo='0' WHERE ship='$xoid'";
	            $database->openConnectionNoReturn($qry);
	        }

            // Service record entry
	        $details = "Tranferred from: " . $oldname . "<br />\n";
	        $details .= "Transferred to: " . $sname . "<br />\n";
	        $time = time();

	        $name = get_usertype($database, $mpre, $spre, $cid, $uflag);

	        $qry = "INSERT INTO {$spre}record
            		SET cid='$cid', pid='$pid', level='Out-of-Character', date='$time',
                    	entry='Transfer', details='$details', name='$name'";
	        $database->openConnectionNoReturn($qry);

	        echo '<h3 class="text-success">Transfer successful!</h3>';
	        echo '<h5>' . $charname . ' transferred to ' . $sname . '</h5>';
	    }
    }
}

// View the character's original app
function crew_view_app ($database, $spre, $cid)
{
	?>
	<h2 class="text-center">View Application</h2>

	<?php
	$qry = "SELECT a.date, a.app
    		FROM {$spre}characters c, {$spre}apps a
            WHERE c.id='$cid' AND a.id=c.app";
    $result = $database->openConnectionWithReturn($qry);

    if (!mysql_num_rows($result))
    {
    	echo '<h4 class="text-warning">Application Not Found.</h4>';
    }
    else
    {
    	list ($date, $app) = mysql_fetch_array($result);
		$date = date("F j, Y, g:i a", $date);
    	?>
        <p class="lead text-center">Date: <?php echo $date; ?></p>
        
        <?php
        echo '<p>' . nl2br($app) . '</p>';
    }
}

// Lookup characters by name
function crew_lookup_name ($database, $mpre, $spre, $charname, $uflag)
{
   	echo '<h3>Searching for characters named ' . $charname . '...</h3>';

	$qry = "SELECT c.id, c.name, c.rank, c.ship, c.pos, c.gender, c.race, r.level FROM {$spre}characters AS c, {$spre}rank AS r WHERE c.name LIKE '%$charname%' AND r.rankid = c.rank ORDER BY r.level DESC, c.rank DESC";
	$result = $database->openConnectionWithReturn($qry);
	
	$num = mysql_num_rows($result);
	
	echo '<h3>Found ' . $num . '</h3>';

	if (!mysql_num_rows($result))
		{ echo '<h3 class="text-warning">No characters found.</h3>'; }
	else {
	?>
	<table class="table manifest">
      <thead>
	   	<tr>
        	<th>ID#</th>
            <th>Rank</th>
            <th>Name</th>
            <th>Position</th>
            <th>Ship</th>
        </tr>
      </thead>
      <tbody>
    
<?php	while ( list($cid,$name,$rank,$sid,$pos,$gender,$race)=mysql_fetch_array($result) )
    	{
			$qry2 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($ship)=mysql_fetch_array($result2);
			
			$qry2 = "SELECT rankid, rankdesc, image FROM {$spre}rank WHERE rankid='$rank'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($rid,$rname,$rimg)=mysql_fetch_array($result2);
		
		?>
			<tr>
				<td><?php echo $cid; ?></td>
				<td rowspan="2">
                    	<?php
						$rnkimg = $relpath . 'images/ranks/' . $rimg;
						if (file_exists($rnkimg)) $rnksize = getimagesize($rnkimg);
						echo '<div style="max-width:'.$rnksize[0].'px; max-height:'.$rnksize[1].'px;" class="rank img-responsive">';
						echo '<img src="' . $rnkimg .'" alt="' .$rname .'" border="0" class="img-responsive" />';
						echo '</div>'; ?>
                </td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid; ?>"><?php echo stripslashes($rname) . ' ' . stripslashes($name); ?></a><br /><?php echo stripslashes($race) . " " . stripslashes($gender); ?></td>
				<td><?php echo $pos; ?></td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid; ?>"><?php echo stripslashes($ship); ?></a></td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" class="text-center">
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=capp" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input class="btn btn-default btn-sm" type="submit" value="View App">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Edit">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cdel" method="post">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Delete">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=rview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Service Record">
                        </form>
                </td>
				<td class="text-right">
                    <form class="form-inline" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=ctrans" method="post">
                        <input type="hidden" name="cid" value="<?php echo $cid ?>">
                        <div class="form-group">
                            <label for="transsid<?php echo $cid ?>" class="sr-only">Transfer to ship:</label>
                            <select class="form-control input-sm" name="sid" id="transsid<?php echo $cid ?>">
                                <?php
                                $res69=mysql_query("SELECT id, name FROM ifs_ships WHERE (tf != 99 AND name NOT LIKE '%Your Own%') OR id = 4 ORDER BY tf DESC, name ASC");
                                while (list($vd, $ve)=mysql_fetch_array($res69)) {
                                    echo '<option value="'.$vd.'">'.stripslashes($ve).'</option>
                                '; }
                                ?>      
                            </select>
                        </div>
                        <input class="btn btn-default btn-sm" type="submit" value="Transfer">
                    </form>
				</td>
            </tr>
<?php	} ?>
	  </tbody>
	</table>
<?php
	}
}

// Lookup characters by species
function crew_lookup_race ($database, $mpre, $spre, $charrace, $uflag)
{
   	echo '<h3>Searching for ' . $charrace . ' characters...</h3>';

	$qry = "SELECT c.id, c.name, c.rank, c.ship, c.pos, c.gender, c.race, r.level FROM {$spre}characters AS c, {$spre}rank AS r WHERE c.race LIKE '%$charrace%' AND r.rankid = c.rank ORDER BY r.level DESC, c.rank DESC, c.color ASC";
	$result = $database->openConnectionWithReturn($qry);
	
	$num = mysql_num_rows($result);
	
	echo '<h3>Found ' . $num . '</h3>';

	if (!mysql_num_rows($result))
		{ echo '<h3 class="text-warning">No characters found.</h3>'; }
	else {
	?>
	<table class="table manifest">
      <thead>
	   	<tr>
        	<th>ID#</th>
            <th>Rank</th>
            <th>Name</th>
            <th>Position</th>
            <th>Ship</th>
        </tr>
      </thead>
      <tbody>
    
<?php	while ( list($cid,$name,$rank,$sid,$pos,$gender,$race)=mysql_fetch_array($result) )
    	{
			$qry2 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($ship)=mysql_fetch_array($result2);
			
			$qry2 = "SELECT rankid, rankdesc, image FROM {$spre}rank WHERE rankid='$rank'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($rid,$rname,$rimg)=mysql_fetch_array($result2);
		
		?>
			<tr>
				<td><?php echo $cid; ?></td>
				<td rowspan="2">
                    	<?php
						$rnkimg = $relpath . 'images/ranks/' . $rimg;
						if (file_exists($rnkimg)) $rnksize = getimagesize($rnkimg);
						echo '<div style="max-width:'.$rnksize[0].'px; max-height:'.$rnksize[1].'px;" class="rank img-responsive">';
						echo '<img src="' . $rnkimg .'" alt="' .$rname .'" border="0" class="img-responsive" />';
						echo '</div>'; ?>
                </td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid; ?>"><?php echo stripslashes($rname) . ' ' . stripslashes($name); ?></a><br /><?php echo stripslashes($race) . " " . stripslashes($gender); ?></td>
				<td><?php echo $pos; ?></td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid; ?>"><?php echo stripslashes($ship); ?></a></td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" class="text-center">
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=capp" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input class="btn btn-default btn-sm" type="submit" value="View App">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Edit">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cdel" method="post">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Delete">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=rview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Service Record">
                        </form>
                </td>
				<td class="text-right">
                    <form class="form-inline" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=ctrans" method="post">
                        <input type="hidden" name="cid" value="<?php echo $cid ?>">
                        <div class="form-group">
                            <label for="transsid<?php echo $cid ?>" class="sr-only">Transfer to ship:</label>
                            <select class="form-control input-sm" name="sid" id="transsid<?php echo $cid ?>">
                                <?php
                                $res69=mysql_query("SELECT id, name FROM ifs_ships WHERE (tf != 99 AND name NOT LIKE '%Your Own%') OR id = 4 ORDER BY tf DESC, name ASC");
                                while (list($vd, $ve)=mysql_fetch_array($res69)) {
                                    echo '<option value="'.$vd.'">'.stripslashes($ve).'</option>
                                '; }
                                ?>      
                            </select>
                        </div>
                        <input class="btn btn-default btn-sm" type="submit" value="Transfer">
                    </form>
				</td>
            </tr>
<?php	} ?>

	</table>
<?php
	}
}

// Lookup characters by gender
function crew_lookup_gender ($database, $mpre, $spre, $chargender, $uflag)
{
   	echo '<h3>Searching for ' . $chargender . ' characters...</h3>';

	$qry = "SELECT c.id, c.name, c.rank, c.ship, c.pos, c.gender, c.race, r.level FROM {$spre}characters AS c, {$spre}rank AS r WHERE c.gender = '$chargender' AND r.rankid = c.rank ORDER BY r.level DESC, c.rank DESC, c.color ASC";
	$result = $database->openConnectionWithReturn($qry);
	
	$num = mysql_num_rows($result);
	
	echo '<h3>Found ' . $num . '</h3>';

	if (!mysql_num_rows($result))
		{ echo '<h3 class="text-warning">No characters found.</h3>'; }
	else {
	?>
	<table class="table manifest">
      <thead>
	   	<tr>
        	<th>ID#</th>
            <th>Rank</th>
            <th>Name</th>
            <th>Position</th>
            <th>Ship</th>
        </tr>
      </thead>
      <tbody>
    
<?php	while ( list($cid,$name,$rank,$sid,$pos,$gender,$race)=mysql_fetch_array($result) )
    	{
			$qry2 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($ship)=mysql_fetch_array($result2);
			
			$qry2 = "SELECT rankid, rankdesc, image FROM {$spre}rank WHERE rankid='$rank'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($rid,$rname,$rimg)=mysql_fetch_array($result2);
		
		?>
			<tr>
				<td><?php echo $cid; ?></td>
				<td rowspan="2">
                    	<?php
						$rnkimg = $relpath . 'images/ranks/' . $rimg;
						if (file_exists($rnkimg)) $rnksize = getimagesize($rnkimg);
						echo '<div style="max-width:'.$rnksize[0].'px; max-height:'.$rnksize[1].'px;" class="rank img-responsive">';
						echo '<img src="' . $rnkimg .'" alt="' .$rname .'" border="0" class="img-responsive" />';
						echo '</div>'; ?>
                </td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid; ?>"><?php echo stripslashes($rname) . ' ' . stripslashes($name); ?></a><br /><?php echo stripslashes($race) . " " . stripslashes($gender); ?></td>
				<td><?php echo $pos; ?></td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid; ?>"><?php echo stripslashes($ship); ?></a></td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" class="text-center">
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=capp" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input class="btn btn-default btn-sm" type="submit" value="View App">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Edit">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cdel" method="post">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Delete">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=rview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Service Record">
                        </form>
                </td>
				<td class="text-right">
                    <form class="form-inline" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=ctrans" method="post">
                        <input type="hidden" name="cid" value="<?php echo $cid ?>">
                        <div class="form-group">
                            <label for="transsid<?php echo $cid ?>" class="sr-only">Transfer to ship:</label>
                            <select class="form-control input-sm" name="sid" id="transsid<?php echo $cid ?>">
                                <?php
                                $res69=mysql_query("SELECT id, name FROM ifs_ships WHERE (tf != 99 AND name NOT LIKE '%Your Own%') OR id = 4 ORDER BY tf DESC, name ASC");
                                while (list($vd, $ve)=mysql_fetch_array($res69)) {
                                    echo '<option value="'.$vd.'">'.stripslashes($ve).'</option>
                                '; }
                                ?>      
                            </select>
                        </div>
                        <input class="btn btn-default btn-sm" type="submit" value="Transfer">
                    </form>
				</td>
            </tr>
<?php	} ?>
	  </tbody>
	</table>
<?php
	}
}

// Lookup characters by rank
function crew_lookup_rank ($database, $mpre, $spre, $charrank, $uflag)
{	
	$qry = "SELECT rankid, rankdesc FROM {$spre}rank WHERE rankid='$charrank'";
	$result=$database->openConnectionWithReturn($qry);
	list($rid,$rname)=mysql_fetch_array($result);
   	echo '<h3>Searching for ' . $rname . 's...</h3>';

	$qry = "SELECT c.id, c.name, c.rank, c.ship, c.pos, c.gender, c.race, r.level FROM {$spre}characters AS c, {$spre}rank AS r WHERE c.rank = '$charrank' AND r.rankid = c.rank ORDER BY r.level DESC, c.rank DESC, c.color ASC";
	$result = $database->openConnectionWithReturn($qry);
	
	$num = mysql_num_rows($result);
	
	echo '<h3>Found ' . $num . '</h3>';

	if (!mysql_num_rows($result))
		{ echo '<h3 class="text-warning">No characters found.</h3>'; }
	else {
	?>
	<table class="table manifest">
      <thead>
	   	<tr>
        	<th>ID#</th>
            <th>Rank</th>
            <th>Name</th>
            <th>Position</th>
            <th>Ship</th>
        </tr>
      </thead>
      <tbody>
<?php	while ( list($cid,$name,$rank,$sid,$pos,$gender,$race)=mysql_fetch_array($result) )
    	{
			$qry2 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($ship)=mysql_fetch_array($result2);
			
			$qry2 = "SELECT rankid, rankdesc, image FROM {$spre}rank WHERE rankid='$rank'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($rid,$rname,$rimg)=mysql_fetch_array($result2);
		
		?>
			<tr>
				<td><?php echo $cid; ?></td>
				<td rowspan="2">
                    	<?php
						$rnkimg = $relpath . 'images/ranks/' . $rimg;
						if (file_exists($rnkimg)) $rnksize = getimagesize($rnkimg);
						echo '<div style="max-width:'.$rnksize[0].'px; max-height:'.$rnksize[1].'px;" class="rank img-responsive">';
						echo '<img src="' . $rnkimg .'" alt="' .$rname .'" border="0" class="img-responsive" />';
						echo '</div>'; ?>
                </td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid; ?>"><?php echo stripslashes($rname) . ' ' . stripslashes($name); ?></a><br /><?php echo stripslashes($race) . " " . stripslashes($gender); ?></td>
				<td><?php echo $pos; ?></td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid; ?>"><?php echo stripslashes($ship); ?></a></td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" class="text-center">
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=capp" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input class="btn btn-default btn-sm" type="submit" value="View App">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Edit">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cdel" method="post">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Delete">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=rview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Service Record">
                        </form>
                </td>
				<td class="text-right">
                    <form class="form-inline" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=ctrans" method="post">
                        <input type="hidden" name="cid" value="<?php echo $cid ?>">
                        <div class="form-group">
                            <label for="transsid<?php echo $cid ?>" class="sr-only">Transfer to ship:</label>
                            <select class="form-control input-sm" name="sid" id="transsid<?php echo $cid ?>">
                                <?php
                                $res69=mysql_query("SELECT id, name FROM ifs_ships WHERE (tf != 99 AND name NOT LIKE '%Your Own%') OR id = 4 ORDER BY tf DESC, name ASC");
                                while (list($vd, $ve)=mysql_fetch_array($res69)) {
                                    echo '<option value="'.$vd.'">'.stripslashes($ve).'</option>
                                '; }
                                ?>      
                            </select>
                        </div>
                        <input class="btn btn-default btn-sm" type="submit" value="Transfer">
                    </form>
				</td>
            </tr>
<?php	} ?>
	  </tbody>
	</table>
<?php
	}
}

// Lookup characters by position
function crew_lookup_pos ($database, $mpre, $spre, $charpos, $uflag)
{	
   	echo '<h3>Searching for ' . $charpos . ' characters...</h3>';

	$qry = "SELECT c.id, c.name, c.rank, c.ship, c.pos, c.gender, c.race, r.level FROM {$spre}characters AS c, {$spre}rank AS r WHERE c.pos = '$charpos' AND r.rankid = c.rank ORDER BY r.level DESC, c.rank DESC, c.color ASC";
	$result = $database->openConnectionWithReturn($qry);
	
	$num = mysql_num_rows($result);
	
	echo '<h3>Found ' . $num . '</h3>';

	if (!mysql_num_rows($result))
		{ echo '<h3 class="text-warning">No characters found.</h3>'; }
	else {
	?>
	<table class="table manifest">
      <thead>
	   	<tr>
        	<th>ID#</th>
            <th>Rank</th>
            <th>Name</th>
            <th>Position</th>
            <th>Ship</th>
        </tr>
      </thead>
      <tbody>
    
<?php	while ( list($cid,$name,$rank,$sid,$pos,$gender,$race)=mysql_fetch_array($result) )
    	{
			$qry2 = "SELECT name FROM {$spre}ships WHERE id='$sid'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($ship)=mysql_fetch_array($result2);
			
			$qry2 = "SELECT rankid, rankdesc, image FROM {$spre}rank WHERE rankid='$rank'";
			$result2=$database->openConnectionWithReturn($qry2);
			list($rid,$rname,$rimg)=mysql_fetch_array($result2);
		
		?>
			<tr>
				<td><?php echo $cid; ?></td>
				<td rowspan="2">
                    	<?php
						$rnkimg = $relpath . 'images/ranks/' . $rimg;
						if (file_exists($rnkimg)) $rnksize = getimagesize($rnkimg);
						echo '<div style="max-width:'.$rnksize[0].'px; max-height:'.$rnksize[1].'px;" class="rank img-responsive">';
						echo '<img src="' . $rnkimg .'" alt="' .$rname .'" border="0" class="img-responsive" />';
						echo '</div>'; ?>
                </td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview&amp;cid=<?php echo $cid; ?>"><?php echo stripslashes($rname) . ' ' . stripslashes($name); ?></a><br /><?php echo stripslashes($race) . " " . stripslashes($gender); ?></td>
				<td><?php echo $pos; ?></td>
	        	<td><a href="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=sview&amp;sid=<?php echo $sid; ?>"><?php echo stripslashes($ship); ?></a></td>
			</tr>
			
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" class="text-center">
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=capp" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input class="btn btn-default btn-sm" type="submit" value="View App">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid; ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Edit">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=cdel" method="post">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Delete">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                        </form>
                        <form class="btn-group" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=rview" method="post">
                            <input type="hidden" name="cid" value="<?php echo $cid ?>">
                            <input type="hidden" name="sid" value="<?php echo $sid ?>">
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input type="hidden" class="btn" /><!-- Fake button sibling -->
                            <input class="btn btn-default btn-sm" type="submit" value="Service Record">
                        </form>
                </td>
				<td class="text-right">
                    <form class="form-inline" action="index.php?option=ifs&amp;task=opm&amp;action=common&amp;lib=ctrans" method="post">
                        <input type="hidden" name="cid" value="<?php echo $cid ?>">
                        <div class="form-group">
                            <label for="transsid<?php echo $cid ?>" class="sr-only">Transfer to ship:</label>
                            <select class="form-control input-sm" name="sid" id="transsid<?php echo $cid ?>">
                                <?php
                                $res69=mysql_query("SELECT id, name FROM ifs_ships WHERE (tf != 99 AND name NOT LIKE '%Your Own%') OR id = 4 ORDER BY tf DESC, name ASC");
                                while (list($vd, $ve)=mysql_fetch_array($res69)) {
                                    echo '<option value="'.$vd.'">'.stripslashes($ve).'</option>
                                '; }
                                ?>      
                            </select>
                        </div>
                        <input class="btn btn-default btn-sm" type="submit" value="Transfer">
                    </form>
				</td>
            </tr>
<?php	} ?>
	  </tbody>
	</table>
<?php
	}
}
?>
