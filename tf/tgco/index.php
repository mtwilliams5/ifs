<?php
/***
  * INTEGRATED FLEET MANAGEMENT SYSTEM
  * OBSIDIAN FLEET
  * http://www.obsidianfleet.net
  *
  * Developer:	Frank Anon
  * 	    	fanon@obsidianfleet.net
  *
  * Updated by: Matt Williams
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
  * Comments: Main ship admin page for TGCOs
  *
 ***/

if (!defined("IFS"))
	echo "Hacking attempt!";
else
{
	// Let people with admin-access choose which TG to play with
	if ($uflag['g'] == 2 || $uflag['t'] > 0)
    {
		if (!$adminship && !$sid)
        {
			?>
			<p class="text-center">Hey, you're an admin!  That makes you special!<br />
            Choose which TG you want to modify.</p>
			<form class="form-inline text-center" action="index.php?option=ifs&amp;task=tgco&amp;action=<?php echo $action ?>" method="post">
				<select class="form-control" name="adminship">
					<?php
                    if ($uflag['t'] == 1)
                    {
	                    $qry = "SELECT t.name, t.tf
	                            FROM {$spre}taskforces t, {$spre}characters c
	                            WHERE c.player='" . uid . "' AND t.tg=0 AND t.co=c.id";
	                    $result=$database->openConnectionWithReturn($qry);
	                    list($tfname,$tfid)=mysql_fetch_array($result);
                        $tflimit = "AND tf='$tfid'";
                    }
                    else
                    	$tflimit = "";

			        $qry = "SELECT tf, tg, name FROM {$spre}taskforces
		                    WHERE tg!='0' $tflimit ORDER BY tf, tg";
			        $result = $database->openConnectionWithReturn($qry);
			        while ( list ($tfid, $tgid, $tgname) = mysql_fetch_array($result) )
						echo "<option value=\"{$tfid}-{$tgid}\">$tfid - $tgid $tgname</option>\n";
					?>
				</select>
				<input class="btn btn-default btn-small" type="submit" value="Submit">
            </form>
			<?php
	        $tfid = "selecting";
		}
        // Once you've chosen a TG, we need the system to think you're the TGCO
        elseif (!$sid)
        {
			$tfid = substr($adminship, 0, strpos($adminship, "-"));
            $tgid = substr($adminship, strpos($adminship, "-")+1);
	        $name = "Mr. Big-Shot Admin";
	    }
        else
	    	$tfid = "na";
	}
    elseif ($uflag['g'] == 1)
    {
		$qry = "SELECT t.name, t.tf, t.tg
        		FROM {$spre}taskforces t, {$spre}characters c
                WHERE c.player='" . uid . "' AND t.tg!=0 AND t.co=c.id";
		$result=$database->openConnectionWithReturn($qry);
		list($tgname,$tfid,$tgid)=mysql_fetch_array($result);
	}

	if ($uflag['g'] > 0)
    {
		if (!$tfid)
			echo '<h4 class="text-center text-warning text-center">You have a TGCO User Level, but you are not listed as the CO of a TG!<br />' .
            	 'Sorry, can\'t let you in!</h4>';
		elseif ($tfid != 'selecting')
        {
			switch ($action)
            {
	        	case 'acad':
    	        	include("tf/tfco/academy.php");
        	        break;
	        	case 'common':
    	        	include("tf/tools.php");
        	        break;
            	case 'listing':
            		redirect("index.php?option=ships&tf={$tfid}&tg={$tgid}");
	                break;
                case 'stats':
                	include("tf/tfco/stats.php");
                    break;
				default:
            		redirect("index.php?option=ships&tf={$tfid}&tg={$tgid}");
                	break;
			}
	    }
	}
    else
		echo '<h3 class="text-danger text-center">You do not have access to this area!</h4>';
}
?>