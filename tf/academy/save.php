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
  *             matt@mtwilliams.uk
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
  * This file contains code from Mambo Site Server 4.0.12
  * Copyright (C) 2000 - 2002 Miro International Pty Ltd
  *
  * Comments: List Academy students
  *
  * See CHANGELOG for patch details
  *
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
    if ($lib == "cdel")
    {
    	$qry = "UPDATE {$spre}acad_courses SET active='0' WHERE course='$cid'";
        $database->openConnectionNoReturn($qry);

        // Remove instructors too
    	$qry = "SELECT id FROM {$spre}acad_instructors
        		WHERE course='$cid' AND active='1'";
        $result = $database->openConnectionWithReturn($qry);

        $qry = "UPDATE {$spre}acad_instructors SET active='0' WHERE course='$cid'";
        $database->openConnectionNoReturn($qry);

        while (list($iid) = mysql_fetch_array($result))
        {
	        $qry2 = "SELECT j.id FROM {$spre}acad_instructors i, {$spre}acad_instructors j
	                WHERE i.id='$iid' AND i.pid=j.pid AND j.active='1' AND i.id != j.id";
	        $result2 = $database->openConnectionWithReturn($qry2);
	        if (!mysql_num_rows($result2))
	        {
	            $qry2 = "SELECT u.id, u.flags
	                    FROM {$spre}acad_instructors i, {$mpre}users u
	                    WHERE i.id='$iid' AND i.pid=u.id";
	            $result2 = $database->openConnectionWithReturn($qry2);
	            list ($pid, $userflags) = mysql_fetch_array($result2);

	            $userflags = str_replace("d", "", $userflags);
	            $userflags = str_replace("D", "", $userflags);
	            $qry2 = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$pid'";
	            $database->openConnectionNoReturn($qry2);
	        }
        }

        echo '<h3 class="text-success">Course has been set to inactive.</h3>';
        $lib = "";
        include("tf/academy/admin.php");
    }
    else if ($lib == "sdel")
    {
    	$qry = "UPDATE {$spre}acad_courses SET active='0'
        		WHERE course='$cid' AND section='$sid'";
        $database->openConnectionNoReturn($qry);

        echo '<h3 class="text-success">Section has been set to inactive.</h3>';
        $lib = "";
        include("tf/academy/admin.php");
	}
    else if ($lib == "idel")
    {
    	$qry = "UPDATE {$spre}acad_instructors SET active='0'
        		WHERE course='$cid' AND id='$iid'";
        $database->openConnectionNoReturn($qry);

        // Remove userlevel if needed
        $qry = "SELECT j.id FROM {$spre}acad_instructors i, {$spre}acad_instructors j
        		WHERE i.id='$iid' AND i.pid=j.pid AND j.active='1'";
        $result = $database->openConnectionWithReturn($qry);
        if (!mysql_num_rows($result))
        {
        	$qry = "SELECT u.id, u.flags
            		FROM {$spre}acad_instructors i, {$mpre}users u
                    WHERE i.id='$iid' AND i.pid=u.id";
            $result = $database->openConnectionWithReturn($qry);
            list ($pid, $userflags) = mysql_fetch_array($result);

	        $userflags = str_replace("d", "", $userflags);
	        $userflags = str_replace("D", "", $userflags);
	   	    $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$pid'";
	        $database->openConnectionNoReturn($qry);
        }

        echo '<h3 class="text-success">Instructor has been set inactive.</h3>';
        $lib = "";
        include("tf/academy/admin.php");
	}
    else if ($lib == "cedit")
    {
    	$qry = "SELECT coord FROM {$spre}acad_courses
        		WHERE course='$cid' AND section='0'";
        $result = $database->openConnectionWithReturn($qry);
        list ($oldCoord) = mysql_fetch_array($result);
		
		//Let's sanitise the inputs
		$cname = mysql_real_escape_string($cname);
		$desc = mysql_real_escape_string($desc);
		
    	$qry = "UPDATE {$spre}acad_courses
        		SET name='$cname', pass='$pass', coord='$coord', descrip='$desc'
                WHERE course='$cid' AND section='0'";
        $database->openConnectionNoReturn($qry);

        if ($oldCoord != $coord)
        {
	        // Course coordinators get super Academy access
	        // Remove userlevel from old coordinator
            if ($oldCoord != "0")
            {
	            $qry = "SELECT course FROM {$spre}acad_courses
	                    WHERE coord = '$oldCoord' AND course != '$cid'";
	            $result = $database->openConnectionWithReturn($qry);
	            if (!mysql_num_rows($result))
	            {
	                $qry = "SELECT u.id, u.flags
	                        FROM {$spre}acad_instructors i, {$mpre}users u
	                        WHERE i.id='$oldCoord' AND i.pid=u.id";
	                $result = $database->openConnectionWithReturn($qry);
	                list ($pid, $userflags) = mysql_fetch_array($result);

	                $userflags = str_replace("D", "d", $userflags);
	                $qry = "UPDATE {$mpre}users SET flags='$userflags'
	                        WHERE id='$pid'";
	                $database->openConnectionNoReturn($qry);
	            }
            }

	        // Grant userlevel to new coordinator
            if ($coord != "0")
            {
	            $qry = "SELECT u.id, u.flags
	                    FROM {$mpre}users u, {$spre}acad_instructors i
	                    WHERE i.id='$coord' AND i.pid=u.id";
	            $result = $database->openConnectionWithReturn($qry);
	            list ($pid, $userflags) = mysql_fetch_array($result);

	            $userflags = str_replace("d", "D", $userflags);
	            if (!strstr($userflags, "D"))
	                $userflags = "D" . $userflags;
	            $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$pid'";
	            $database->openConnectionNoReturn($qry);
            }
        }

        echo '<h3 class="text-success">Course has been updated.</h3>';
        $lib = "";
        include("tf/academy/admin.php");
	}
    else if ($lib == "sedit")
    {
		//Let's sanitise the inputs
		$sname = mysql_real_escape_string($sname);
		$desc = mysql_real_escape_string($desc);
		
    	$qry = "UPDATE {$spre}acad_courses SET name='$sname', descrip='$desc'
        		WHERE course='$cid' AND section='$sid'";
        $database->openConnectionNoReturn($qry);

        echo '<h3 class="text-success">Section has been updated.</h3>';
        $lib = "";
        include("tf/academy/admin.php");
	}
    else if ($lib == "cadd")
    {
    	$cid = 0;
    	do {
        	$cid++;
        	$qry = "SELECT course FROM {$spre}acad_courses WHERE course='$cid'";
            $result = $database->openConnectionWithReturn($qry);
        } while (mysql_num_rows($result));
		
		//Let's sanitise the inputs 
		$cname = mysql_real_escape_string($cname);
		$desc = mysql_real_escape_string($desc);
		
    	$qry = "INSERT INTO {$spre}acad_courses
        		SET name='$cname', pass='$pass', descrip='$desc',
                course='$cid', section='0', active='1'";
        $database->openConnectionNoReturn($qry);

        $qry = "INSERT INTO {$spre}acad_courses
        		SET name='Graduation', pass='$pass', descrip='Successful " .
                	"completion of the course (this must always be the last " .
                    "section)', course='$cid', section='1', active='1'";
    	$database->openConnectionNoReturn($qry);

        echo '<h3 class="text-success">Course has been added.</h3>';
        $lib = "";
        include("tf/academy/admin.php");
    }
	else if ($lib == "sadd")
    {
    	$qry = "UPDATE {$spre}acad_courses SET section=section+1
        		WHERE section > '$order' AND course='$cid'";
        $database->openConnectionNoReturn($qry);

		$order++;
		
		// Let's sanitise the inputs
		$sname = mysql_real_escape_string($sname);
		$desc = mysql_real_escape_string($desc);
		
        $qry = "INSERT INTO {$spre}acad_courses
        		SET name='$sname', descrip='$desc', course='$cid',
                	section='$order', active='1'";
        $database->openConnectionNoReturn($qry);

        echo '<h3 class="text-success">Section has been added.</h3>';
        $lib = "";
        include("tf/academy/admin.php");
	}
    else if ($lib == "iadd")
    {
    	$qry = "SELECT id FROM {$spre}acad_instructors
        		WHERE pid='$ipid' AND cid='$ichar' AND course='$course'";
        $result = $database->openConnectionWithReturn($qry);

        if (mysql_num_rows($result))
        	$qry = "UPDATE {$spre}acad_instructors SET active='1'
            		WHERE pid='$ipid' AND cid='$ichar' AND course='$course'";
        else
        	$qry = "INSERT INTO {$spre}acad_instructors
            		SET pid='$ipid', cid='$ichar', course='$course', active='1'";
        $database->openConnectionNoReturn($qry);

        // Give them user-based access flag here
       	$qry = "SELECT flags FROM {$mpre}users WHERE id='$ipid'";
        $result = $database->openConnectionWithReturn($qry);
        list ($userflags) = mysql_fetch_array($result);

        if (!strstr($userflags, "d"))
        {
           	$userflags = "d" . $userflags;
            $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$ipid'";
            $database->openConnectionNoReturn($qry);
        }

        echo '<h3 class="text-success">Instructor has been added.</h3>';
        $lib = "";
        include("tf/academy/admin.php");
    }
    else if ($lib == "lupdate")
    {
    	foreach ($student as $stuid => $instid)
        {
        	if ($instid != "0")
            {
            	$qry = "UPDATE {$spre}acad_students
                		SET inst='$instid', status='p' WHERE id='$stuid'";
            	$database->openConnectionNoReturn($qry);

                $now = time();
		        $name = get_usertype($database, $mpre, $spre, 0, $uflag);
                $qry = "INSERT INTO {$spre}acad_marks
                		SET date='$now', sid='$stuid', section='0', name='$name'";
                $database->openConnectionNoReturn($qry);
            }
        }
        include("tf/academy/wait.php");
	}
    else if ($lib == "inst")
    {
    	foreach ($stupdate as $stuid => $action)
        {
			// Complete section
        	if ($action == "2")
            {
                $qry = "SELECT MAX(section) FROM {$spre}acad_marks WHERE sid='$stuid'";
                $result = $database->openConnectionWithReturn($qry);
                list ($secid) = mysql_fetch_array($result);
                $secid++;

                $qry = "SELECT c.name
                		FROM {$spre}acad_courses c, {$spre}acad_students s
                        WHERE s.id='$stuid' AND s.course=c.course
                        	AND c.section='" . ($secid+1) . "' AND active='1'";
                $result = $database->openConnectionWithReturn($qry);

            	if (!$mark[$stuid])
                	echo '<h4 class="text-warning">Enter the mark for the section! Record not updated...</h4>';
                else if (!mysql_num_rows($result))	   // Completed last section
                {
                	// Graduation!
                    $qry = "UPDATE {$spre}acad_marks
                    		SET grade='" . $mark[$stuid] . "'
                            WHERE sid='$stuid' AND section='0'";
                    $database->openConnectionNoReturn($qry);

                	$now = time();
					$qry = "UPDATE {$spre}acad_students
                    		SET edate='$now', status='c' WHERE id='$stuid'";
                    $database->openConnectionNoReturn($qry);

                    // Notify the person's CO (either ship or TFCO/TGCO)
                    $qry = "SELECT s.cid, c.id, u.email, h.id
                    		FROM {$spre}acad_students s, {$mpre}users u,
                            	{$spre}characters c, {$spre}ships h,
                                {$spre}characters a
                            WHERE s.cid=a.id AND a.ship=h.id AND h.co=c.id
                            	AND c.player=u.id AND s.id='$stuid'";
                    $result = $database->openConnectionWithReturn($qry);
                    list ($cid, $coid, $coemail, $shid) = mysql_fetch_array($result);

	                if ($cid == $coid)
	                {
						$qrytf = "SELECT u.email, t.tf
								FROM {$spre} characters c, {$mpre}users u, {$spre}ships s, {$spre}taskforces t
								WHERE s.co='$coid' AND s.tf=t.tf AND t.tg='0'
									AND t.co=c.id AND c.player=u.id";
						$resulttf = $database->openConnectionWithReturn($qry);
						list ($tfcoemail, $tfid) = mysql_fetch_array($resulttf);
	                    
						$qrytg = "SELECT u.email t.tg
	                            FROM {$spre}characters c, {$mpre}users u1, {$spre}ships s, {$spre}taskforces t
	                             WHERE s.co='$coid' AND s.tf=t.tf AND s.tg=t.tg
                                    AND t.co=c.id AND c.player=u.id";
	                    $resulttg = $database->openConnectionWithReturn($qrytg);
	                    list ($tgcoemail, $tgid) = mysql_fetch_array($resulttg);

                    	$coemail = $tfcoemail . ", " . $tgcoemail;
                    }
					else if ($shid<=4)
					{
						$coemail = $fleetopsemail;
					}

                    $qry = "SELECT c.name
                    		FROM {$spre}acad_courses c, {$spre}acad_students s
                            WHERE s.course=c.course AND s.id='$stuid'";
                    $result = $database->openConnectionWithReturn($qry);
                    list ($coursename) = mysql_fetch_array($result);

                    $qry = "SELECT r.rankdesc, c.name, c.player, s.name
                    		FROM {$spre}characters c, {$spre}rank r, {$spre}ships s
                            WHERE c.id='$cid' AND c.rank=r.rankid AND c.ship=s.id";
                    $result = $database->openConnectionWithReturn($qry);
                	list ($rank, $charname, $player, $ship) = mysql_fetch_array($result);

                    $qry = "SELECT email FROM {$mpre}users WHERE id='" . UID . "'";
                    $result = $database->openConnectionWithReturn($qry);
                    list ($instemail) = mysql_fetch_array($result);
			        $name = get_usertype($database, $mpre, $spre, 0, $uflag);

					require_once "includes/mail/academy_graduation_co.mail.php";

                    // Service record entry
                    $name = mysql_real_escape_string($name);
					$body = mysql_real_escape_string($body);
					$qry = "INSERT INTO {$spre}record
				    		SET pid='$player', cid='$cid',
                            	level='Out-of-Character', date='$now',
				            	entry='Academy Completion: $coursename',
                                details='$body', name='$fleetname IFS'";
                    $database->openConnectionNoReturn($qry);
                }
                else
                {
                	$now = time();
	                $qry = "SELECT c.name
	                		FROM {$spre}acad_courses c, {$spre}acad_students s
	                        WHERE s.id='$stuid' AND s.course=c.course
	                        	AND c.section='$secid' AND active='1'";
	                $result = $database->openConnectionWithReturn($qry);
					list ($secname) = mysql_fetch_array($result);
					
					$secname = mysql_real_escape_string($secname,$dbcon);

			        $name = get_usertype($database, $mpre, $spre, 0, $uflag);
                	$qry = "INSERT INTO {$spre}acad_marks
                    		SET date='$now', sid='$stuid', section='$secid',
                            	secname = '$secname',
                                grade='" . $mark[$stuid] . "', name='$name'";
                    $database->openConnectionNoReturn($qry);

					echo '<h4 class="text-success">Marks updated!</h4>';
				}
            }

            // Fail the course
            else if ($action == "3")
            {
              	if (!$mark[$stuid])
                	echo '<h4 class="text-warning">Enter the (failing) mark for the course! Record not updated...</h4>';
                else
                {
		          	$now = time();
					$qry = "UPDATE {$spre}acad_students
	                   		SET edate='$now', status='f' WHERE id='$stuid'";
	                $database->openConnectionNoReturn($qry);

                      // Notify the person's CO (either ship or TFCO/TGCO)
                    $qry = "SELECT s.cid, c.id, u.email, h.id
                    		FROM {$spre}acad_students s, {$mpre}users u,
                            	{$spre}characters c, {$spre}ships h,
                                {$spre}characters a
                            WHERE s.cid=a.id AND a.ship=h.id AND h.co=c.id
                            	AND c.player=u.id AND s.id='$stuid'";
                    $result = $database->openConnectionWithReturn($qry);
                    list ($cid, $coid, $coemail, $shid) = mysql_fetch_array($result);

	                if ($cid == $coid)
	                {
						$qrytf = "SELECT u.email, t.tf
								FROM {$spre} characters c, {$mpre}users u, {$spre}ships s, {$spre}taskforces t
								WHERE s.co='$coid' AND s.tf=t.tf AND t.tg='0'
									AND t.co=c.id AND c.player=u.id";
						$resulttf = $database->openConnectionWithReturn($qry);
						list ($tfcoemail, $tfid) = mysql_fetch_array($resulttf);
	                    
						$qrytg = "SELECT u.email t.tg
	                            FROM {$spre}characters c, {$mpre}users u1, {$spre}ships s, {$spre}taskforces t
	                             WHERE s.co='$coid' AND s.tf=t.tf AND s.tg=t.tg
                                    AND t.co=c.id AND c.player=u.id";
	                    $resulttg = $database->openConnectionWithReturn($qrytg);
	                    list ($tgcoemail, $tgid) = mysql_fetch_array($resulttg);

                    	$coemail = $tfcoemail . ", " . $tgcoemail;
                    }
					else if ($shid<=4)
					{
						$coemail = $fleetopsemail;
					}

                    $qry = "SELECT c.name
                    		FROM {$spre}acad_courses c, {$spre}acad_students s
                            WHERE s.course=c.course AND s.id='$stuid'";
                    $result = $database->openConnectionWithReturn($qry);
                    list ($coursename) = mysql_fetch_array($result);

                    $qry = "SELECT r.rankdesc, c.name, c.player, s.name
                    		FROM {$spre}characters c, {$spre}rank r, {$spre}ships s
                            WHERE c.id='$cid' AND c.rank=r.rankid AND c.ship=s.id";
                    $result = $database->openConnectionWithReturn($qry);
                	list ($rank, $charname, $player, $ship) = mysql_fetch_array($result);

                    $qry = "SELECT email FROM {$mpre}users WHERE id='" . UID . "'";
                    $result = $database->openConnectionWithReturn($qry);
                    list ($instemail) = mysql_fetch_array($result);
			        $name = get_usertype($database, $mpre, $spre, 0, $uflag);

			require_once "includes/mail/academy_failure_co.mail.php";

                    // Service record entry
                    $name = mysql_real_escape_string($name);
					$body = mysql_real_escape_string($body);
					$qry = "INSERT INTO {$spre}record
				    		SET pid='$player', cid='$cid',
                            	level='Out-of-Character', date='$now',
				            	entry='Failed Academy Course: $coursename',
                                details='$body', name='$fleetname IFS'";
                    $database->openConnectionNoReturn($qry);
                }
            }

            // Drop out
            else if ($action == "4")
            {
                $now = time();
                $qry = "UPDATE {$spre}acad_students
                        SET edate='$now', status='d' WHERE id='$stuid'";
                $database->openConnectionNoReturn($qry);

                  // Notify the person's CO (either ship or TFCO/TGCO)
                $qry = "SELECT s.cid, c.id, u.email, h.id
                        FROM {$spre}acad_students s, {$mpre}users u,
                            {$spre}characters c, {$spre}ships h,
                            {$spre}characters a
                        WHERE s.cid=a.id AND a.ship=h.id AND h.co=c.id
                            AND c.player=u.id AND s.id='$stuid'";
                $result = $database->openConnectionWithReturn($qry);
                list ($cid, $coid, $coemail, $shid) = mysql_fetch_array($result);

                if ($cid == $coid)
                {
					$qrytf = "SELECT u.email, t.tf
							FROM {$spre} characters c, {$mpre}users u, {$spre}ships s, {$spre}taskforces t
							WHERE s.co='$coid' AND s.tf=t.tf AND t.tg='0'
								AND t.co=c.id AND c.player=u.id";
					$resulttf = $database->openConnectionWithReturn($qry);
					list ($tfcoemail, $tfid) = mysql_fetch_array($resulttf);
					
					$qrytg = "SELECT u.email t.tg
							FROM {$spre}characters c, {$mpre}users u1, {$spre}ships s, {$spre}taskforces t
							 WHERE s.co='$coid' AND s.tf=t.tf AND s.tg=t.tg
								AND t.co=c.id AND c.player=u.id";
					$resulttg = $database->openConnectionWithReturn($qrytg);
					list ($tgcoemail, $tgid) = mysql_fetch_array($resulttg);

                   	$coemail = $tfcoemail . ", " . $tgcoemail;
                }
				else if ($shid<=4)
				{
					$coemail = $fleetopsemail;
				}

                $qry = "SELECT name
                        FROM {$spre}acad_courses c, {$spre}acad_students s
                        WHERE s.course=c.course AND s.id='$stuid'";
                $result = $database->openConnectionWithReturn($qry);
                list ($coursename) = mysql_fetch_array($result);

                $qry = "SELECT r.rankdesc, c.name, c.player, s.name
                        FROM {$spre}characters c, {$spre}rank r, {$spre}ships s
                        WHERE c.id='$cid' AND c.rank=r.rankid AND c.ship=s.id";
                $result = $database->openConnectionWithReturn($qry);
                list ($rank, $charname, $player, $ship) = mysql_fetch_array($result);

                $qry = "SELECT email FROM {$mpre}users WHERE id='" . UID . "'";
                $result = $database->openConnectionWithReturn($qry);
                list ($instemail) = mysql_fetch_array($result);
                $name = get_usertype($database, $mpre, $spre, 0, $uflag);

                require_once "includes/mail/academy_dropout_co.mail.php";

                // Service record entry
                $name = mysql_real_escape_string($name);
				$body = mysql_real_escape_string($body);
                $qry = "INSERT INTO {$spre}record
                        SET pid='$player', cid='$cid',
                            level='Out-of-Character', date='$now',
                            entry='Incomplete Academy Course: $coursename',
                            details='$body', name='$fleetname IFS'";
                $database->openConnectionNoReturn($qry);
            }
        }
	$pid = $iid;
        include("tf/academy/instructor.php");
	}
}
?>
