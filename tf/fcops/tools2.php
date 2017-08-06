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
  *             matt@mtwilliams.uk
  *
  * Version:	1.17
  * Release Date: June 3, 2004
  * Patch 1.17:   August 2017
  *
  * Copyright (C) 2003-2004 Frank Anon for Obsidian Fleet RPG
  * Distributed under the terms of the GNU General Public License
  * See doc/LICENSE for details
  *
  * Comments: FCOps Tools - process them!
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	if ($tfid != $reftf)
    {
    	if ($tfid == "delete")
        {
        	$qry = "SELECT id FROM {$spre}ships WHERE tf='$reftf'";
            $result = $database->openConnectionWithReturn($qry);

            if (mysql_num_rows($result))
            	echo '<h4 class="text-warning">TF must be empty before being deleted!</h4>';
            else
            {
            	$qry = "DELETE FROM {$spre}taskforces WHERE tf='$reftf'";
                $database->openConnectionNoReturn($qry);
                echo '<h4 class="text-success">Task Force ' . $reftf . ' has been deleted!</h4>';
            }
            $nogo = 1;
        }
        else
        {
	        $qry = "SELECT tf FROM {$spre}taskforces WHERE tf='$tfid'";
	        $result = $database->openConnectionWithReturn($qry);
	        if (mysql_num_rows($result))
	        {
	            echo '<h4 class="text-warning">TF number already in use!</h4>';
	            $nogo = 1;
	        }
            else if ($tfid == "0")
	        {
	            echo '<h4 class="text-warning">Invalid TF numer!  Cannot use zero.</h4>';
	            $nogo = 1;
	        }
	        else
	            echo '<h4 class="text-info">... Task Force Number check complete...</h4>';
        }
    }

	if ($tgid != $reftg)
    {
    	if ($tgid == "delete")
        {
        	$qry = "SELECT id FROM {$spre}ships WHERE tf='$tfid' AND tg='$reftg'";
            $result = $database->openConnectionWithReturn($qry);

            if (mysql_num_rows($result))
            	echo '<h4 class="text-warning">TG must be empty before being deleted!</h4>';
            else
            {
            	$qry = "DELETE FROM {$spre}taskforces WHERE tf='$tfid' AND tg='$reftg'";
                $database->openConnectionNoReturn($qry);
                echo '<h4 class="text-success">Task Group ' . $reftg . ' has been deleted!</h4>';
            }
            $nogo = 1;
        }
        else
        {
	        $qry = "SELECT tf FROM {$spre}taskforces WHERE tg='$tgid' AND tf='$tfid'";
	        $result = $database->openConnectionWithReturn($qry);
	        if (mysql_num_rows($result))
	        {
	            echo '<h4 class="text-warning">TG number already in use!</h4>';
	            $nogo = 1;
	        }
            else if ($tgid == "0")
	        {
	            echo '<h4 class="text-warning">Invalid TG numer!  Cannot use zero.<h4>';
	            $nogo = 1;
	        }
	        else
	            echo '<h4 class="text-info">... Task Group Number check complete...</h4>';
        }
    }

	if (!$nogo)
    {
       	echo '<h4 class="text-info">Commencing Database Update...</h4>';

    	if ($tfid != $reftf)
        {
        	if ($reftf == "new")
            {
            	$qry = "INSERT INTO {$spre}taskforces SET tf='$tfid', tg='0'";
                $database->openConnectionNoReturn($qry);

                echo '<h4 class="text-success">Task Force added!</h4>';
            }
            else
            {
	            $qry = "UPDATE {$spre}taskforces SET tf='$tfid' WHERE tf='$reftf'";
	            $database->openConnectionNoReturn($qry);

	            $qry = "UPDATE {$spre}ships SET tf='$tfid' WHERE tf='$reftf'";
	            $database->openConnectionNoReturn($qry);

	            echo '<h4 class="text-success">Task Force Number update complete...</h4>';
            }
        }

    	if ($tgid != $reftg)
        {
        	if ($reftg == "new")
            {
            	$qry = "INSERT INTO {$spre}taskforces SET tf='$tfid', tg='$tgid'";
                $database->openConnectionNoReturn($qry);

                echo '<h4 class="text-success">Task Group added!</h4>';
            }
            else
            {
	            $qry = "UPDATE {$spre}taskforces SET tg='$tgid' WHERE tf='$tfid' AND tg='$reftg'";
	            $database->openConnectionNoReturn($qry);

	            $qry = "UPDATE {$spre}ships SET tg='$tgid' WHERE tf='$tfid' AND tg='$reftg'";
	            $database->openConnectionNoReturn($qry);

	            echo '<h4 class="text-success">Task Group Number update complete...</h4>';
            }
        }

		if ($tgid == "0")
        {
			$qry = "UPDATE {$spre}taskforces SET name='$tfname' WHERE tf='$tfid' AND tg='0'";
		    $database->openConnectionNoReturn($qry);
	       	echo '<h4 class="text-success">Task Force Information update complete...</h4>';
        }
        else
        {
			$qry = "UPDATE {$spre}taskforces SET name='$tfname' WHERE tf='$tfid' AND tg='$tgid'";
		    $database->openConnectionNoReturn($qry);
	       	echo '<h4 class="text-success">Task Group Information update complete...</h4>';
        }

        if ( !$tfcoid )
        	$tfcoid = "0";

		$qry = "SELECT t.co, s.id
        		FROM {$spre}taskforces t, {$spre}ships s
                WHERE t.tf='$tfid' AND t.tg='$tgid' AND s.co=t.co";
        $result = $database->openConnectionWithReturn($qry);
        list ($coid, $sid) = mysql_fetch_array($result);

        if ($coid != $tfcoid)
        {
        	if ($coid != "0")
            {
	            echo '<h4 class="text-info">Change in Command detected... to ' . $tfcoid . '</h4>';
	            $qry = "UPDATE {$spre}ships SET sorder=3 WHERE id='$sid'";
	            $database->openConnectionNoReturn($qry);
	            echo '<h4 class="text-info">Removing old Command Codes... from ' . $coid . '</h4>';
            }

			$qry = "SELECT id FROM {$spre}ships WHERE co='$tfcoid'";
            $result = $database->openConnectionWithReturn($qry);
            list ($sid) = mysql_fetch_array($result);

			if ($tgid == "0")
            {
	            $qry = "UPDATE {$spre}ships SET sorder=1 WHERE id='$sid'";
                $database->openConnectionNoReturn($qry);

                $qry = "SELECT c.player, u.flags
                		FROM {$spre}characters c, {$mpre}users u
                        WHERE c.id='$tfcoid' AND c.player=u.id";
                $result = $database->openConnectionWithReturn($qry);
                list ($uid, $userflags) = mysql_fetch_array($result);

				if (!strchr($userflags, "t"))
                {
	                $userflags = "t" . $userflags;
    	            $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$uid'";
	                $database->openConnectionNoReturn($qry);
                }

                if ($coid != "0")
            	{
	                $qry = "SELECT c.player, u.flags
	                        FROM {$spre}characters c, {$mpre}users u
	                        WHERE c.id='$coid' AND c.player=u.id";
	                $result = $database->openConnectionWithReturn($qry);
	                list ($uid, $userflags) = mysql_fetch_array($result);

	                $userflags = preg_replace("/t/", "", $userflags);
	                $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$uid'";
	                $database->openConnectionNoReturn($qry);
                }
		       	echo '<h4 class="text-success">Change in Task Force command completed...</h4>';
            }
            else
            {
	            $qry = "UPDATE {$spre}ships SET sorder=2 WHERE id='$sid'";
                $database->openConnectionNoReturn($qry);

                $qry = "SELECT c.player, u.flags
                		FROM {$spre}characters c, {$mpre}users u
                        WHERE c.id='$tfcoid' AND c.player=u.id";
                $result = $database->openConnectionWithReturn($qry);
                list ($uid, $userflags) = mysql_fetch_array($result);

				if (!strchr($userflags, "g"))
                {
	                $userflags = "g" . $userflags;
    	            $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$uid'";
	                $database->openConnectionNoReturn($qry);
                }

                if ($coid != "0")
            	{
	                $qry = "SELECT c.player, u.flags
	                        FROM {$spre}characters c, {$mpre}users u
	                        WHERE c.id='$coid' AND c.player=u.id";
	                $result = $database->openConnectionWithReturn($qry);
	                list ($uid, $userflags) = mysql_fetch_array($result);

	                $userflags = preg_replace("/g/", "", $userflags);
	                $qry = "UPDATE {$mpre}users SET flags='$userflags' WHERE id='$uid'";
	                $database->openConnectionNoReturn($qry);
                }

		       	echo '<h4 class="text-success">Change in Task Group command completed...</h4>';
            }
          	$qry = "UPDATE {$spre}taskforces SET co='$tfcoid' WHERE tf='$tfid' AND tg='$tgid'";
            $database->openConnectionNoReturn($qry);
        }
        echo '<h3 class="text-success">Database update COMPLETE.</h3>';

		echo '<h4>';
        echo 'Task Force ' . $tfid;

        if ($tgid == "0")
        	echo ' - ' . $tfname;
        else
        	echo ':<br />Task Group ' . $tgid . ' - ' . $tfname;
		echo '</h4>';

        echo '<h5>CO: ';
        $qry2 = "SELECT c.id, c.name, s.name, r.rankdesc FROM
				{$spre}characters c, {$spre}ships s, {$spre}rank r WHERE
    		    c.id=s.co AND s.tf='$tfid' AND c.rank=r.rankid AND $tfcoid=c.id
                ORDER BY c.rank DESC, c.name";
        $result2 = $database->openConnectionWithReturn($qry2);
		list($coid, $cname, $sname, $rname)=mysql_fetch_array($result2);

        echo $rname . ' ' . $cname . ', ' . $sname;
    }
}
?>